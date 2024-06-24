<?PHP

class Autor
{

    protected $id;
    protected $nombre_completo;
    protected $biografia;
    protected $foto_perfil;

    protected string $table;

        /**
     * Devuelve todos los xxxxx en base
     * @return
     */
    public function lista_completa(): array
    {
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT * FROM $this->table";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        $result = $PDOStatement->fetchAll();

        return $result;
    }

    /**
     * Devuelve los datos de un artista en particular
     * @param int $id El ID único del artista 
     */
    public function get_x_id(int $id)
    {
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT * FROM $this->table WHERE id = $id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute();

        $result = $PDOStatement->fetch();

     
        return $result ?? null;
    }

    /**
     * Get the value of nombre_completo
     */ 
    public function getNombre_completo()
    {
        return $this->nombre_completo;
    }


    /**
     * Get the value of biografia
     */
    public function getBiografia()
    {
        return $this->biografia;
    }

    /**
     * Get the value of foto_perfil
     */
    public function getImagen()
    {
        return $this->foto_perfil;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}
