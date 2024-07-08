<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try {
            $this->objetoPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function prepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }

    // public function exportAllTablesToCSV($directory)
    // {
    //     $tables = $this->getAllTables();

    //     if (!is_dir($directory)) {
    //         mkdir($directory, 0777, true);
    //     }

    //     foreach ($tables as $table) {
    //         $this->exportTableToCSV($table, $directory);
    //     }
    // }

    public function getAllTables()
    {
        $stmt = $this->objetoPDO->query("SHOW TABLES");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function exportTableToCSV($table)
    {
        $stmt = $this->objetoPDO->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $output = fopen('php://output', 'w');
            ob_start();

            fputcsv($output, array_keys($rows[0]));

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
            return ob_get_clean();
        }

        return '';
    }
}
