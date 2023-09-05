<?php
// контроллер api users

class Controller_users extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function actionCheck(array $routerUrls)
    {
        [$controller, $user_login] = $routerUrls;

        if (count($routerUrls) > 2) {
            $this->action_404();
        }

        if ($this->method === "POST" && empty($user_login)) {
            $this->AddUser();
        }

        if ($this->method === "GET" && !empty($user_login)) {
            $this->GetUser($user_login);
        }

        if ($this->method === "PUT" && !empty($user_login)) {
            $this->EditUser($user_login);
        }
    }

    // добавление нового пользователя
    private function AddUser()
    {
        $this->PrepareRequiredFields($this->post_data);

        if (empty($this->post_data) || empty($this->post_data['login']) || empty($this->post_data['name'])) {
            $this->response['errors'][] = "empty required fields";
        }

        if (!empty($this->response['errors'])) {
            $this->Response();
        }

        $password = $this->GenPass();
        $this->post_data['password'] = hash('sha256', $password);

        if (Users::Create($this->post_data)) {
            $this->response_code = 201;
            $this->response['result'] = true;
            $this->response['data'] += [
                "login" => $this->post_data['login'],
                "password" => $password,
            ];
        } else {
            $this->response['errors'][] = "Request error";
        }

        $this->Response();
    }

    // обновление данных пользователя
    private function EditUser(string $userLogin)
    {
        $this->PrepareRequiredFields($this->post_data);
        $password = trim($this->post_data['password']);

        if (!empty($password)) {
            if (!$this->PreparePass($password)) {
                $this->response['errors'][] = "incorrect password";
            }

            $this->post_data['password'] = $password;
        }

        if (!empty($this->response['errors'])) {
            $this->Response();
        }

        if (Users::UpdateByLogin($userLogin, $this->post_data)) {
            $this->response_code = 200;
            $this->response['result'] = true;
        } else {
            $this->response['errors'][] = "Update error";
        }

        $this->Response();
    }

    // получение данных пользователя
    private function GetUser(string $userLogin)
    {
        $user_info = Users::GetByLogin($userLogin);

        if (empty($user_info)) {
            $this->response_code = 404;
        } else {
            $this->response_code = 200;
            $this->response['result'] = true;

            foreach ($user_info as $key => $value) {
                if ($key !== "password") {
                    $this->response['data'][$key] = $value;
                }
            }
        }

        $this->Response();
    }

    // проверка логина
    private function PrepareLogin(string $login)
    {
        if (preg_match("/^[a-zA-Z0-9_]+$/", $login) !== 1) {
            return false;
        }

        if (mb_strlen($login) < 3 || mb_strlen($login) > 30) {
            return false;
        }

        // проверяем на занятость логина
        if (!empty(Users::GetByLogin($login))) {
            return false;
        }

        return true;
    }

    // проверка пароля
    private function PreparePass(string $password)
    {
        if (preg_match("/^[a-zA-Z0-9_]+$/", $password) !== 1) {
            return false;
        }

        if (mb_strlen($password) < 8 || mb_strlen($password) > 30) {
            return false;
        }

        return true;
    }

    // проверка корректности полей
    private function PrepareRequiredFields(&$fields)
    {
        $login = trim($fields['login']);
        $name = trim($fields['name']);
        $email = trim($fields['email']);

        if (!empty($login)) {
            if (!$this->PrepareLogin($login)) {
                $this->response['errors'][] = "incorrect login";
            }

            $fields['login'] = $login;
        }

        if (!empty($name)) {
            if (mb_strlen($name) < 3 || mb_strlen($name) > 30) {
                $this->response['errors'][] = "incorrect name";
            }

            $fields['name'] = $name;
        }

        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->response['errors'][] = "incorrect email";
            }

            $fields['email'] = $email;
        }
    }

    // генератор паролей
    private function GenPass()
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz_0123456789';
        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < 10; $i++) {
            $str .= $chars[random_int(0, $max)];
        }

        return $str;
    }
}
