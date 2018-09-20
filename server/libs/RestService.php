<?php
class RestService
{
   

    public function Order($order)
    {
        $idcar = $order->idcar;
        $type_pay = $order->payment;
        $cust_name = $order->firstname;
        $cust_surname = $order->lastname;
        if ( !$idcar || !is_numeric($idcar) || $idcar<0)
        {
            throw new Exception("Server",ERR_CAR_ID_INVALID);
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
        return (object) ['id'=>$idcar];
    }

    
}


?>
