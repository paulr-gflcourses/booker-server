<?php
include_once "../../libs/RestServer.php";
include_once "../../libs/SQL.php";
include_once "../../libs/MySQL.php";
include_once "../../config.php";
class Cars
{

    public function getCars($params=false)
    {
        if ($params)
        {
            $id = $params[0];
            if (is_numeric($id))
            {
                return $this->getById($id);
            }
        }

        if (isset($_GET['filter']))
        {
            return $this->CarFilter($_GET['filter']);
        }
        try
        {
            $mysql = new MySQL();
            $mysql->setSql("SELECT id, mark, model FROM Cars");
            $result = $mysql->select();
        }catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    public function postCars()
    {

    }

    public function putCars($id)
    {

    }

    public function deleteCars($id)
    {

    }

    private function getById($id)
    {
        
        if ( !$id || !is_numeric($id) || $id<0)
        {
            throw new Exception(ERR_CAR_ID_INVALID);
        }
        try
        {
            $mysql = new MySQL();
            $mysql->setSql("SELECT id, mark, model, year, engine, color, maxspeed, price FROM Cars WHERE id=$id");
            $result = $mysql->select();
        }catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetch(PDO::FETCH_OBJ);
    }


    public function CarFilter($filter)
    {
        $year = $filter['year'];
        $mark = $filter['mark'];
        $model = $filter['model'];
        $engine = $filter['engine'];
        $color =  $filter['color'];
        $maxspeed = $filter['maxspeed'];
        $price = $filter['price'];
        
        if (!$year || !is_numeric($year) || !is_integer(+$year) || +$year<1930 || +$year>2018)
        {
            throw new Exception(ERR_YEAR_INVALID); 
        }
        $sql="SELECT id, mark, model FROM Cars";
        $sql.=" WHERE year=$year";
        if ($mark)
        {
            if (!is_string($mark))
            {
                throw new Exception(ERR_MARK_INVALID); 
            }
            $sql.=" AND mark='$mark'";
        }
        if ($model)
        {
            if (!is_string($model))
            {
                throw new Exception(ERR_MODEL_INVALID); 
            }
            $sql.=" AND model='$model'";
        }
        if ($engine)
        {
            if (!is_numeric($engine) || $engine<0)
            {
                throw new Exception(ERR_ENGINE_INVALID); 
            }
            $sql.=" AND engine=$engine";
        }
        if ($color)
        {
            if (!is_string($color))
            {
                throw new Exception(ERR_COLOR_INVALID); 
            }
            $sql.=" AND color='$color'";
        }
        if ($maxspeed)
        {
            if (!is_integer($maxspeed) || $maxspeed<0)
            {
                throw new Exception(ERR_MAXSPEED_INVALID); 
            }
            $sql.=" AND maxspeed=$maxspeed";
        }
        if ($price)
        {
            if (!is_numeric($price) || $price<0)
            {
                throw new Exception(ERR_PRICE_INVALID); 
            }
            $sql.=" AND price=$price";
        }
        try
        {
            $mysql = new MySQL();
            $mysql->setSql($sql);
            $result = $mysql->select();

        }catch(Exception $e)
        {
            throw new Exception($e->getMessage()); 
        }
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    
    

}

$cars = new Cars();
$server = new RestServer($cars);
