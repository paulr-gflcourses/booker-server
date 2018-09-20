<?php
include_once "../../libs/RestServer.php";
include_once "../../libs/SQL.php";
include_once "../../libs/MySQL.php";
include_once "../../config.php";


class Orders
{
    public function getOrders()
    {
        
    }

    public function postOrders2()
    {
        header('Content-Type: application/json');
        $ar = ['errors'=>'All is good!'];
        return json_encode($ar);
    }
    public function postOrders()
    {
        if (isset($_POST['orderData']))
        {
            $order = $_POST['orderData'];
        $idcar = $order['idcar'];
        $type_pay = $order['payment'];
        $cust_name = $order['firstname'];
        $cust_surname = $order['lastname'];
        }
        
        
        if ( !$idcar || !is_numeric($idcar) || $idcar<0)
        {
            //throw new Exception("Server",ERR_CAR_ID_INVALID);
            throw new Exception("idcar invalid: idcar = $idcar");
        }
        if ($type_pay!="cash" && $type_pay!="credit card"){
            throw new Exception(ERR_PAYMENT_TYPE_INVALID);
        }
        if (!$cust_name)
        {
            throw new Exception(ERR_CUSTOMER_NAME_EMPTY);
        }
        if (!$cust_surname)
        {
            throw new Exception(ERR_CUSTOMER_SURNAME_EMPTY);
        }
        try
        {
            $mysql = new MySQL();
            $sql = "INSERT INTO Orders(id, idcar, type_pay, cust_name, cust_surname) 
                VALUES(?, ?, ?, ?, ?)";
            $params = [0, $idcar, $type_pay, $cust_name, $cust_surname];
            $mysql->setSql($sql);
            $mysql->insert($params);
        }catch(Exception $e)
        {
            throw new Exception($e->getMessage()); 
        }
        return ['id'=>$idcar];
        
    }

    public function putOrders()
    {
        
    }

    public function deleteOrders()
    {
        
    }
}

$orders = new Orders();
$server = new RestServer($orders);
?>
