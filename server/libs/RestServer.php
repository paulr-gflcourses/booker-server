<?php
/*
 * Provides a REST architechture to the server application
 */
class RestServer
{
    private $service;
    /**
     * Constructor for the RestServer
     *  Throws an Exception if there is any error with an appropriate message
     */
    public function __construct($service)
    {
        try
        {
            $this->parseMethod($service);
        }
        catch (Exception $e)
        {
            echo json_encode(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Defines a class and method to be invoked
     * Forms input path- and query-parameters for the method
     */
    private function parseMethod($service)
    {
        $this->service = $service;
        $url = $_SERVER['REQUEST_URI'];
        list($b, $c, $s, $a, $d, $e, $db, $table, $path) = explode('/', $url, 8);
        $method = $_SERVER['REQUEST_METHOD'];
        $funcName = ucfirst($table);
        $pathParams = explode('/', $path);

        $result = '';
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: *');

        switch ($method) {
        case 'GET':
            $result = $this->setMethod('get' . $funcName, $pathParams, $_GET);
            break;
        case 'POST':
            $contents = file_get_contents('php://input');
            $queryParams = json_decode($contents, true);
            $result = $this->setMethod('post' . $funcName, $pathParams, $queryParams);
            break;
        case 'PUT':
            $queryParams = json_decode(file_get_contents('php://input'), true);
            $result = $this->setMethod('put' . $funcName, $pathParams, $queryParams);
            break;
        case 'DELETE':
            $queryParams = json_decode(file_get_contents('php://input'), true);
            $result = $this->setMethod('delete' . $funcName, $pathParams, $queryParams);
            break;
        default:
            return false;
        }
        $this->show_results($result);
    }

    /**
     * Invokes a method by name with given parameters
     */
    private function setMethod($funcName, $pathParams = false, $queryParams = false)
    {
        $ret = false;
        if (method_exists($this->service, $funcName))
        {
            $ret = call_user_func([$this->service, $funcName], $pathParams, $queryParams);
        }
        return $ret;
    }

    /**
     * Shows result of  the method in json
     */
    private function show_results($result)
    {
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
