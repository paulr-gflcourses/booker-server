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
            echo json_encode(['errors' => $e->getMessage()]);
        }
    }

    private function parseMethod($service)
    {
        $this->service = $service;
        $url = $_SERVER['REQUEST_URI'];
        //list($b, $c, $s, $a, $d, $e, $db, $table, $path) = explode('/', $url, 9);
        //$params = explode('/', $url, 9);
         list( $c, $s, $a, $d, $db, $table, $path) = explode('/', $url, 7);
         $params = explode('/', $url, 7);

        $method = $_SERVER['REQUEST_METHOD'];
        $funcName = ucfirst($table);
        $funcParams = explode('/', $path);

        // print_r($params);
        // echo "\n method: $method, funcname = $funcName, table = $table. Params:";
        // print_r($funcParams);

        $result = '';
        $viewType = '.json';
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');
        // header('Access-Control-Allow-Headers: Authorization, Lang');

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: *');
        
        switch ($method) {
        case 'GET':
            $viewType = array_pop($funcParams);
            $viewType = explode('?', $viewType)[0];
            $result = $this->setMethod('get' . $funcName, $funcParams);
            break;
        case 'POST':
            $result = $this->setMethod('post' . $funcName, $funcParams);
            break;
        case 'PUT':
            $result = $this->setMethod('put' . $funcName, $funcParams);
            break;
        case 'DELETE':
            $result = $this->setMethod('delete' . $funcName, $funcParams);
            break;
        default:
            return false;
        }
        $this->show_results($result, $viewType);
    }

    private function setMethod($funcName, $param = false)
    {
        $ret = false;
        if (method_exists($this->service, $funcName))
        {
            $ret = call_user_func([$this->service, $funcName], $param);
        }
        return $ret;
    }

    private function show_results($result, $viewType = 'json')
    {
        //header('Access-Control-Allow-Origin: *');
        //header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        switch ($viewType) {
        case '.json':
            header('Content-Type: application/json');
            echo json_encode($result);
            break;
        case '.txt':
            header('Content-type: text/plain');
            echo $this->toText($result);
            break;
        case '.xhtml':
            header('Content-type: text/html');
            echo $this->toHtml($result);
            break;
        case '.xml':
            header('Content-type: application/xml');
            echo $this->toXml($result);
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode($result);
            //echo 'No such type!';
            break;
        }
    }

    private function toText($obj)
    {
        return print_r($obj);
    }

    private function toHtml($obj)
    {
        $res = '<table>';
        if (is_array($obj))
        {
            $first = $obj[0];
            $res .= '<tr>';
            foreach ($first as $key => $val)
            {
                $res .= '<th>' . $key . '</th>';
            }
            $res .= '</tr>';
            foreach ($obj as $item)
            {
                $res .= '<tr>';
                foreach ($item as $field)
                {
                    $res .= '<td>' . $field . '</td>';
                }
            }
            $res .= '</tr>';
        }
        elseif (is_object($obj))
        {
            $first = $obj;
            $res .= '<tr>';
            foreach ($first as $key => $val)
            {
                $res .= '<th>' . $key . '</th>';
            }
            $res .= '</tr>';
            $res .= '<tr>';
            foreach ($obj as $field)
            {
                $res .= '<td>' . $field . '</td>';
            }
            $res .= '</tr>';
        }
        $res .= '</table>';
        return $res;
    }

    private function toXml($obj)
    {
        $xml = new SimpleXMLElement('<cars/>');
        $arrToParse = $obj;
        if (is_object($obj))
        {
            $arrToParse = [$obj];
        }
        foreach ($arrToParse as $item)
        {
            $car = $xml->addChild('car');
            foreach ($item as $key => $val)
            {
                $car->addChild($key, $val);
            }
        }

        return $xml->asXML();
    }
}
