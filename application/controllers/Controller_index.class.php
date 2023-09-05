<?php
// контроллер главной страницы

class Controller_index extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function actionCheck(array $routerUrls)
    {
        $this->response_code = 200;
        $this->response['result'] = true;
        $this->response['data']['message'] = "Main Page";
        $this->Response();
    }
}
