<?php
declare(strict_types = 1);
namespace PDOi {

    use PDO;
    use InvalidArgumentException;

    class PDOFactory
    {
        private static $connection =      /*. (string[string][string]) .*/ [];
        private static $pdo =             /*. (PDO[string]) .*/            [];
        private static $options =         /*. (array[string][]) .*/        [];

        /**
         * add
         *
         * Add PDO-authentication data for initializing PDO-objects for PDOFactory to return.
         *
         * @param   string  $connection Identifier of connection
         * @param   string  $dsn        DSN of connection
         * @param   string  $username   Username of connection - if not included in DSN
         * @param   string  $password   Password of connection - if not included in DSN
         * @param   array   $options    Options for connection
         *
         * @return void
         */
        public static function add(string $connection, string $dsn, string $username = null, string $password = null, array $options = []): void
        {
            self::$connection[$connection] = ['dsn' => $dsn, 'username' =>  $username, 'password' => $password];
            self::$options[$connection] = $options;
            self::$pdo[$connection] = null;
        }

        /**
         * get
         *
         * @param   string  $connection The connection identifier of the PDO-connection to return or reinitialize.
         *
         * @return  PDO     The PDO-connection corresponding to the parameter
         *
         * @throws \InvalidArgumentException    Parameter is not a valid PDO connection identifier
         * @throws \PDOException                Supplied authentication data for PDO connection is invalid
         */
        public static function get(string $connection): PDO
        {
            if (!\array_key_exists($connection, self::$connection)) {
                throw new InvalidArgumentException('"' . $connection . '" is not a valid PDO connection identifier');
            }

            if (is_null(self::$pdo[$connection])) { // is null if  not initialized yet or connection has been terminated; in either case - reinitialize
                self::$pdo[$connection] = new PDO(
                    self::$connection[$connection]['dsn'],
                    self::$connection[$connection]['username'],
                    self::$connection[$connection]['username'],
                    self::$options[$connection]
                );

            }

            return self::$pdo[$connection];
        }
    }
}