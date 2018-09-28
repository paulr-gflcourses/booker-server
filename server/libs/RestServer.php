<?php

class RestServer
{
    private $service;

    public function __construct($service)
    {
        try
        {
            $this->parseMethod($service);
        }
        catch (Exception $e)
        {
            http_response_code(500);
            echo json_encode(['errors' => $e->getMessage()]);
        }
    }

    private function parseMethod($service)
    {
        $this->service = $service;
        $url = $_SERVER['REQUEST_URI'];

        //list($b, $c, $s, $a, $d, $e, $db, $table, $path) = explode('/', $url, 9);
        //$params = explode('/', $url, 9);
         list($c, $s, $a, $d, $e, $db, $table, $path) = explode('/', $url, 8);
         $params = explode('/', $url, 8);



        $method = $_SERVER['REQUEST_METHOD'];
        $funcName = ucfirst($table);
        $pathParams = explode('/', $path);

        //  print_r($params);
        //  echo "\n method: $method, funcname = $funcName, table = $table. Params:";
        //  print_r($pathParams);

        $result = '';

      
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');
        // header('Access-Control-Allow-Headers: Authorization, Lang');


        //header('Access-Control-Allow-Origin: *');
        //header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        //header('Access-Control-Allow-Headers: Authorization, Content-Type');

         header('Access-Control-Allow-Origin: *');
         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
         header('Access-Control-Max-Age: 1000');
         header('Access-Control-Allow-Headers: *');

        
        switch ($method) {
        case 'GET':
            $result = $this->setMethod('get' . $funcName, $pathParams, $_GET);
            break;
        case 'POST':
            $contents = file_get_contents("php://input");
            $queryParams =json_decode($contents, true);
            $result = $this->setMethod('post' . $funcName, $pathParams, $queryParams);
            break;
        case 'PUT':
            $queryParams = json_decode(file_get_contents("php://input"), true);
            $result = $this->setMethod('put' . $funcName, $pathParams, $queryParams);
            break;
        case 'DELETE':
            $queryParams = json_decode(file_get_contents("php://input"), true);
            $result = $this->setMethod('delete' . $funcName, $pathParams, $queryParams);
            break;
        default:
            return false;
        }
        $this->show_results($result);
    }

    private function setMethod($funcName, $pathParams = false, $queryParams=false)
    {
        $ret = false;
        if (method_exists($this->service, $funcName))
        {
            $ret = call_user_func([$this->service, $funcName], $pathParams, $queryParams);
        }
        return $ret;
    }

    private function show_results($result)
    {
        //header('Access-Control-Allow-Origin: *');
        //header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
            header('Content-Type: application/json');
            echo json_encode($result);
    }

    
}
