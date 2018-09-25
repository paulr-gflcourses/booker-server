<?php
include_once "../../libs/RestServer.php";
include_once "../../libs/SQL.php";
include_once "../../libs/MySQL.php";
include_once "../../config.php";
class Events
{

    public function getEvents($params=false)
    {
        if ($params)
        {
            $id = $params[0];
            if (is_numeric($id))
            {
                return $this->getById($id);
            }
        }
        $sql = "SELECT id, is_recurring, idrec, description, start_time, end_time, created_time, idroom, iduser FROM booker_events";
        if (isset($_GET['idroom']))
        {
            $sql.= " WHERE idroom=".$_GET['idroom'];
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

    public function postEvents()
    {
        //$mysql = new MySQL();
        //$mysql->setSql('INSERT INTO Events(id, username, password) VALUE(?, ?, ?)');
        //$params=[];
        //$result = $mysql->insert($params);
        return json_encode(['post: '=>json_encode($_POST)]);

    }

    public function putEvents($id)
    {
        //$mysql = new MySQL();
        //$mysql->setSql('UPDATE Events SET description=? WHERE id=?');
        //$params=[];
        //$result = $mysql->update($params);
        $put = "";
        parse_str(file_get_contents("php://input"), $put);
        //$event = json_decode($put);
        return json_encode(['status'=>'ok','id'=>$id, 'postdata'=>$put, 'putid'=>$put['id']]);

    }

    public function deleteEvents($id)
    {

    }





}

$events = new Events();
$server = new RestServer($events);
