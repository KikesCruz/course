<?php 
    namespace App;
    use PDO;
    use PDOException;

final class Connection
{
    private static ?PDO $connection = null;

    final private function __construct(){}

    final public static function getConnection(): PDO
    {
        try {
            if(!self::$connection){
                self::$connection = new PDO(
                    $_ENV['DB_DSN'],
                     $_ENV['DB_USERNAME'],
                    $_ENV['DB_PASSWORD']
                );
            }
        } catch (PDOException $e) {
 
            echo $e->getMessage();
            echo match($e -> getCode()){
                1049 => 'Base de datos no encontrada',
                1045 => 'Acceso denegado',
                2002 => 'Conexion rechazada',
                default => 'Error desconocido',
            };
        }

        return self::$connection;

    }
    private function __clone(){}
}

