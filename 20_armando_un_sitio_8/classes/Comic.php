<?PHP

class Comic
{

    private $id;
    private $personaje_principal;
    private $serie;
    private $volumen;
    private $numero;
    private $titulo;
    private $publicacion;
    private $guionista;
    private $artista;
    private $bajada;
    private $personajes_secundarios;
    private $origen;
    private $editorial;
    private $portada;
    private $precio;

    private static $createValues = ['id', 'volumen', 'numero', 'titulo', 'publicacion', 'bajada', 'portada', 'origen', 'editorial', 'precio'];


    /**
     * Devuelve una instancia del objeto Comic, con todas sus propiedades configuradas
     *@return Comic
     */
    private function createComic($comicData): Comic
    {
        //         echo "<pre>";
        // print_r($comicData);
        // echo "</pre>";

        $comic = new self();

        /*ACÁ TENEMOS QUE CONFIGURAR NUESTRO OBJETO */
        /*Primero, por cada valor en nuestro array de valores, buscamos el valor correspondiente y se lo asignamos a la propiedad*/
        foreach (self::$createValues as $value) {
            $comic->{$value} = $comicData[$value];
        }

        /* Vamos a crear un objeto Guionista con los datos correspondientes y lo asignamos a la propiedad */
        $comic->guionista = (new Guionista())->get_x_id($comicData['guionista_id']);
        /* Vamos a crear un objeto Artista con los datos correspondientes y lo asignamos a la propiedad */
        $comic->artista = (new Artista())->get_x_id($comicData['artista_id']);
        /* Vamos a crear un objeto Personaje con los datos correspondientes al personaje principal y lo asignamos a la propiedad */
        $comic->personaje_principal = (new Personaje())->get_x_id($comicData['personaje_principal_id']);
        /* Vamos a crear un objeto Serie con los datos correspondientes y lo asignamos a la propiedad */
        $comic->serie = (new Serie())->get_x_id($comicData['serie_id']);

        /* Vamos a asignar los personajes secundarios a la propiedad */
        $PSIds = !empty($comicData['personajes_secundarios']) ? explode(",", $comicData['personajes_secundarios']) : [];

        $personajes_secundarios = [];
        foreach ($PSIds as $PSId) {
            $personajes_secundarios[] = (new Personaje())->get_x_id(intval($PSId));
        }

        $comic->personajes_secundarios = $personajes_secundarios;

        return $comic;
    }
    /**
     * Devuelve el catálgo completo
     * @return Comic[] Un Array lleno de instancias de objeto Comic.
     */
    public function catalogo_completo(): array
    {

        $catalogo = [];

        $conexion = Conexion::getConexion();
        $query = "SELECT comics.*, GROUP_CONCAT(pxc.personaje_id) AS personajes_secundarios FROM comics 
        LEFT JOIN personajes_x_comic AS pxc ON comics.id = pxc.comic_id     
        GROUP BY comics.id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute();

        // $catalogo = $PDOStatement->fetchAll();

        //  echo "<pre>";
        //  print_r($catalogo);
        //  echo "</pre>";

        while ($result = $PDOStatement->fetch()) {
            $catalogo[] = $this->createComic($result);
        }

        //ACÁ TENEMOS QUE CREAR NUESTO PROPIO SET DE DATOS


        return $catalogo;
    }

    /**
     * Devuelve el catalogo de productos de un personaje en particular
     * @param int $personaje_principal_id El ID del personaje principal a buscar.
     * @return Comic[] Un Array lleno de instancias de objeto Comic.
     */
    public function catalogo_x_personaje(int $personaje_principal_id): array
    {

        $catalogo = [];

        $conexion = Conexion::getConexion();
        $query = "SELECT comics.*, GROUP_CONCAT(pcx.personaje_id) AS personajes_secundarios
        FROM comics 
        LEFT JOIN personajes_x_comic AS pcx ON comics.id = pcx.comic_id
        WHERE personaje_principal_id = ?
        GROUP BY comics.id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$personaje_principal_id]);

        while ($result = $PDOStatement->fetch()) {
            $catalogo[] = $this->createComic($result);
        }
        return $catalogo;
    }

    /**
     * Devuelve los datos de un producto en particular
     * @param int $idProducto El ID único del producto a mostrar 
     */
    public function producto_x_id(int $idProducto): ?Comic
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT comics.*, GROUP_CONCAT(pxc.personaje_id) AS personajes_secundarios FROM comics 
        LEFT JOIN personajes_x_comic AS pxc ON comics.id = pxc.comic_id
        WHERE comics.id = ?
        GROUP BY comics.id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$idProducto]);

        $result = $this->createComic($PDOStatement->fetch());

        return $result ?? null;
    }


    /**
     * Devuelve los comics illustrados por un determinado artista
     * @param int $idArtista El ID único del artista buscado 
     * 
     * @return Comic[] Un array de objetos Comic
     */
    public function comic_x_artista(int $idArtista): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM comics WHERE artista_id = ?";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$idArtista]);

        $catalogo = [];
        while ($result = $PDOStatement->fetch()) {
            $catalogo[] = $this->createComic($result);
        }

        return $catalogo ?? null;
    }

    public function buscador(string $terminoBusqueda): array
    {

        /*PROCESAR EL TERMINO DE BUSQUEDA */

        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM comics WHERE titulo LIKE :termino OR bajada LIKE :termino";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute(['termino' => "%$terminoBusqueda%"]);

        $catalogo = $PDOStatement->fetchAll();

        return $catalogo;
    }

    /**
     * Devuelve los comics en un determinado rango de precios
     * @param int $minimo El precio minimo. De no proveerlo se asumira 0;
     * @param int $maximo El precio máximo. De no proveerlo se asumiran infinito. 
     * 
     * @return Comic[] Un array de objetos Comic
     */
    public function comics_x_rango(int $minimo = 0, int $maximo = 0)
    {

        $conexion = Conexion::getConexion();
        if ($maximo) {
            /* PRECIOS ENTRE MIN Y MAX */
            $query = "SELECT * FROM comics WHERE precio BETWEEN :minimo AND :maximo;";
            $valores = [
                'minimo' => $minimo,
                'maximo' => $maximo
            ];
        } else {
            /* PRECIOS MAYORES A MIN */
            $query = "SELECT * FROM comics WHERE precio > :minimo";
            $valores = [
                'minimo' => $minimo
            ];
        }

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute($valores);

        $catalogo = $PDOStatement->fetchAll();

        return $catalogo;
    }


    /**
     * Inserta un nuevo comic a la base de datos
     * @param string $titulo
     * @param int $personaje_pricipal_id
     * @param int $serie_id
     * @param int $guionista_id
     * @param int $artista_id
     * @param int $volumen
     * @param int $numero
     * @param string $titulo El titulo del comic
     * @param string $publicacion El dia de publicación en formato YYYY-DD-MM
     * @param string $bajada 
     * @param string $origen 
     * @param string $editorial 
     * @param string $portada ruta a un archivo .jpg o .png 
     * @param float $precio   
     */
    public function insert($titulo, $personaje_pricipal_id, $serie_id, $guionista_id, $artista_id, $volumen, $numero, $publicacion, $origen, $editorial, $bajada, $portada, $precio): int
    {

        $conexion = Conexion::getConexion();
        $query = "INSERT INTO comics VALUES (NULL, :titulo, :personaje_pricipal_id, :serie_id, :guionista_id, :artista_id, :volumen, :numero, :publicacion, :origen, :editorial, :bajada, :portada, :precio)";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'titulo' => $titulo,
                'personaje_pricipal_id' => $personaje_pricipal_id,
                'serie_id' => $serie_id,
                'guionista_id' => $guionista_id,
                'artista_id' => $artista_id,
                'volumen' => $volumen,
                'numero' => $numero,
                'publicacion' => $publicacion,
                'origen' => $origen,
                'editorial' => $editorial,
                'bajada' => $bajada,
                'portada' => $portada,
                'precio' => $precio,
            ]
        );

        return $conexion->lastInsertId();
    }

    public function edit($titulo, $personaje_principal_id, $serie_id, $guionista_id, $artista_id, $volumen, $numero, $publicacion, $origen, $editorial, $bajada, $precio, $portada)
    {

        $conexion = Conexion::getConexion();
        $query = "UPDATE comics SET
         titulo = :titulo,
         personaje_principal_id = :personaje_principal_id,
         serie_id = :serie_id,
         guionista_id = :guionista_id,
         artista_id = :artista_id,
         volumen = :volumen,
         numero = :numero,
         publicacion = :publicacion,
         origen = :origen,
         editorial = :editorial,
         bajada = :bajada,
         precio = :precio,
         portada = :portada          
        WHERE id = :id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'id' => $this->id,
                'titulo' => $titulo,
                'personaje_principal_id' => $personaje_principal_id,
                'serie_id' => $serie_id,
                'guionista_id' => $guionista_id,
                'artista_id' => $artista_id,
                'volumen' => $volumen,
                'numero' => $numero,
                'publicacion' => $publicacion,
                'origen' => $origen,
                'editorial' => $editorial,
                'bajada' => $bajada,
                'precio' => $precio,
                'portada' => $portada
            ]
        );
    }

    /**
     * Elimina esta instancia de la base de datos
     */
    public function delete()
    {
        $conexion = Conexion::getConexion();
        $query = "DELETE FROM comics WHERE id = ?";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$this->id]);
    }

    /**
     * Crea un vinculo entre un comic y un personaje Secundario
     * @param int $comic_id
     * @param int $personaje_id
     */
    public function add_personajes_sec(int $comic_id, int $personaje_id)
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO personajes_x_comic VALUES (NULL, :comic_id, :personaje_id)";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'comic_id' => $comic_id,
                'personaje_id' => $personaje_id
            ]
        );
    }

    /**
     * Vaciar lista de personajes secundarios
     * @param int $comic_id
     */
    public function clear_personajes_sec()
    {
        $conexion = Conexion::getConexion();
        $query = "DELETE FROM personajes_x_comic WHERE comic_id = :comic_id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'comic_id' => $this->id
            ]
        );
    }


    /**
     * Devuelve el nombre completo de la edición
     */
    public function nombre_completo(): string
    {
        return $this->getSerie()->getNombre() . " Vol." . $this->getVolumen() . " #" . $this->getNumero();
    }

    /**
     * Devuelve el precio de la unidad, formateado correctamente
     */
    public function precio_formateado(): string
    {
        return number_format($this->precio, 2, ",", ".");
    }

    /**
     * Esta función devuelve las primeras x palabras de un párrafo 
     * @param int $cantidad Esta es la cantidad de palabras a extraer (Opcional)
     */
    public function bajada_reducida(int $cantidad = 10): string
    {
        $texto = $this->bajada;

        $array = explode(" ", $texto);
        if (count($array) <= $cantidad) {
            $resultado = $texto;
        } else {
            array_splice($array, $cantidad);
            $resultado = implode(" ", $array) . "...";
        }

        return $resultado;
    }



    /**
     * Get the value of personaje
     */
    public function getPersonaje()
    {
        return $this->personaje_principal->getTitulo();

        //return "personaje no encontrado";
    }

    /**
     * Get the value of serie
     */
    public function getSerie()
    {

        return $this->serie;
    }

    /**
     * Get the value of volumen
     */
    public function getVolumen()
    {
        return $this->volumen;
    }

    /**
     * Get the value of numero
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Get the value of titulo
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Get the value of publicacion
     */
    public function getPublicacion()
    {
        return $this->publicacion;
    }

    /**
     * Get the value of guion
     */
    public function getGuion()
    {
        return $this->guionista->getNombre_completo();
    }

    /**
     * Get the value of arte
     */
    public function getArte()
    {


        return $this->artista->getNombre_completo();
        //return "Artista no econtrado";
    }

    /**
     * Get the value of bajada
     */
    public function getBajada()
    {
        return $this->bajada;
    }

    /**
     * Get the value of portada
     */
    public function getPortada()
    {
        return $this->portada;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio()
    {
        return $this->precio;
    }



    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of origen
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Get the value of editorial
     */
    public function getEditorial()
    {
        return $this->editorial;
    }

    /**
     * Get the value of personaje_principal
     */
    public function getPersonaje_principal()
    {
        return $this->personaje_principal;
    }

    /**
     * Get the value of personajes_secundarios
     */
    public function getPersonajes_secundarios()
    {
        return $this->personajes_secundarios;
    }

    /**
     * Get the value of guionista
     */
    public function getGuionista()
    {
        return $this->guionista;
    }

    /**
     * Get the value of artista
     */
    public function getArtista()
    {
        return $this->artista;
    }

    /**
     * Devuelve un array compuesto por IDs de todos los personajes secundarios
     */
    public function getPersonajes_secundarios_ids(): array
    {
        $result = [];
        foreach ($this->personajes_secundarios as $value) {
            $result[] = intval($value->getId());
        }
        return $result;
    }
}
