<?php
include_once '../../libs/RestServer.php';
include_once '../../libs/SQL.php';
include_once '../../libs/MySQL.php';
include_once '../../config.php';
/**
 * Performs operations with rooms
 */
class Rooms
{
    /**
     * Gets aviable rooms
     */
    public function getRooms($params)
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
            $mysql->setSql("SELECT id, name FROM booker_rooms");
            $result = $mysql->select();
        }catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetchAll(PDO::FETCH_OBJ);
    }
  
}

$rooms = new Rooms();
$server = new RestServer($rooms);
