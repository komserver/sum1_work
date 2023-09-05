<?php
// модель

include_once WORK_DIR . '/config/mysql.php';

class Model
{
    private static $connection = null;
    protected static $fields = [];

    public static function db()
    {
        if (self::$connection == null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

            if (self::$connection->connect_errno) {
                throw new Exception('Ошибка подключения к базе данных!');
            }

            self::$connection->set_charset('utf8');
        }

        return self::$connection;
    }

    // обработка полей модели
    protected static function prepareInsertFields(array $params = [])
    {
        $fields_list = [];

        foreach (static::$fields as $val) {
            $fields_list[$val] = isset($params[$val]) ? self::db()->real_escape_string($params[$val]) : null;
        }

        return $fields_list;
    }

    // обработка полей модели для обновления данных
    protected static function prepareUpdateFields(array $params = [])
    {
        $fields_list = [];

        foreach (static::$fields as $field) {
            if (array_key_exists($field, $params)) {
                $fields_list[$field] = !empty($params[$field]) ? self::db()->real_escape_string($params[$field]) : NULL;
            }
        }

        return $fields_list;
    }
}
