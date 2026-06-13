<?php
    declare(strict_types=1);

    namespace MVC\Model;

    use Exception;
    use mysqli;
    use mysqli_result;

    class DatabaseInteractor
    {
        private const DB_NAME = 'a0884824_metropros';
        private static DatabaseInteractor $instance; // Синглтон, так как мы работаем с одной БД
        private mysqli $connection;

        public function __construct()
        {
            $parsedConfig = array();

            $connectionAttempt = mysqli_connect(
                'localhost', 
                'a0884824_metropros', 
                'SAMogyg6',
                self::DB_NAME
            );
            if ($connectionAttempt != false)
                $this->connection = $connectionAttempt;
        }

        public static function getInstance() : DatabaseInteractor
        {
            if (!isset(self::$instance))
                self::$instance = new DatabaseInteractor();
            return self::$instance;
        }

        public function query(string $sqlCommand) : mysqli_result|bool
        {
            return mysqli_query($this->connection, $sqlCommand);
        }

        public function error() : string
        {
            return mysqli_error($this->connection);
        }
        
        // Получение всех строк
        public function fetch_array(string $sqlCommand, int $mode) : ?array
        {
            $mysqliResult = $this->query($sqlCommand);
            if ($mysqliResult instanceof mysqli_result) // Не использовал ли я INSERT, DELETE?
                return mysqli_fetch_all($mysqliResult, $mode);
            return null;
        }

        public function fetch_numeric_array(string $sqlCommand) : ?array
        {
            $array = $this->fetch_array($sqlCommand, MYSQLI_NUM);
            return empty($array) ? null : $array[0];
        }

        public function fetch_hash_table(string $sqlCommand) : ?array
        {
            $array = $this->fetch_array($sqlCommand, MYSQLI_ASSOC);
            return empty($array) ? null : $array[0];
        }

        public function fetch_both_array(string $sqlCommand) : ?array
        {
            $array = $this->fetch_array($sqlCommand, MYSQLI_BOTH);
            return empty($array) ? null : $array;
        }
    }
?>