<?PHP

/**
 * Clase para proveer la conexión de PDO al proyecto.
 */
class Conexion
{

    private const DB_HOST = 'localhost';
    //private const DB_HOST = '127.0.0.1';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_NAME = 'prog2_232_tiendita';

    private const DB_DSN = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';

    // A las propiedades podemos, de así quererlo, definirles el tipo de dato que deben tener.
    private static ?PDO $db = null;

    public static function conectar()
    {

        try {
            self::$db = new PDO(self::DB_DSN, self::DB_USER, self::DB_PASS);
            // echo "<p>Acabo de crear una conexion para poder traer datos! =D</p>";
        } catch (Exception $e) {
            die('Error al conectar con MySQL.');
        }
    }

    /**
     * Función que devuelve una conexión PDO lista para usar
     * @return PDO
     */
    public static function getConexion(): PDO
    {
        if(self::$db === null){
            self::conectar();
        }
        return self::$db;
    }
}
