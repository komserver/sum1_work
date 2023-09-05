<?php
// модель для работы с таблицей пользователей

class Users extends Model
{
    const TABLE_NAME = "_users";
    protected static $fields = ["name", "login", "password", "email", "phone", "address"];  //  список полей модели

    // обновление данных пользователя
    public static function UpdateByLogin(string $login, array $params)
    {
        $update_fields = static::prepareUpdateFields($params);

        if (empty($update_fields)) {
            return false;
        }

        $query = "UPDATE " . self::TABLE_NAME . " SET "
            . implode(", ", array_map(function ($k) {
                return "$k = ?";
            }, array_keys($update_fields)))
            . " WHERE login = ?;";

        $req = self::db()->prepare($query);
        $req->bind_param(
            implode('', array_fill(0, count($update_fields) + 1, 's')),
            ...[...array_values($update_fields), self::db()->real_escape_string($login)]
        );

        $exec = $req->execute();
        $req->close();

        return $exec;
    }

    // создание нового пользователя
    public static function Create(array $params = [])
    {
        $query = "INSERT INTO " . self::TABLE_NAME . " (name, login, password, email, phone, address) VALUES (?, ?, ?, ?, ?, ?);";
        $fields_arr = static::prepareInsertFields($params);

        $req = self::db()->prepare($query);
        $req->bind_param(
            'ssssss',
            $fields_arr['name'],
            $fields_arr['login'],
            $fields_arr['password'],
            $fields_arr['email'],
            $fields_arr['phone'],
            $fields_arr['address'],
        );

        $exec = $req->execute();
        $req->close();

        return $exec;
    }

    // получаем информацию по логину
    public static function GetByLogin(string $login)
    {
        $query = sprintf("SELECT * FROM `" . self::TABLE_NAME . "` WHERE login = '%s' LIMIT 1", self::db()->real_escape_string($login));
        $req = self::db()->query($query);

        if ($req && $row = $req->fetch_assoc()) {
            return $row;
        }

        return null;
    }
}
