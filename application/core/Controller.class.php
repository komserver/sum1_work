<?php
//контроллер

class Controller
{
    protected $get_data;    //  GET параметры
    protected $post_data;    //  POST параметры
    protected $response_code = 400;
    protected $method;

    protected $response = [
        "result" => false,
        "data" => [],
        "errors" => []
    ];

    function __construct()
    {
        $post_data = json_decode(file_get_contents("php://input"), true) ?? [];

        $this->get_data = !empty($_GET) ? $_GET : [];
        $this->post_data = !empty($post_data) ? $post_data : [];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    //  проверка url
    public function actionCheck(array $routerUrls)
    {
        $this->Response();
    }

    public function action_404()
    {
        $this->response_code = 404;
        $this->Response();
    }

    protected function Response()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        http_response_code($this->response_code);
        echo json_encode($this->response);
        exit();
    }
}
