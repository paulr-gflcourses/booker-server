<?php
class EventsModel
{

    public function getEvents($pathParams, $queryParams)
    {
        if ($pathParams)
        {
            $id = $pathParams[0];
            if (is_numeric($id))
            {
                return $this->getById($id);
            }
        }
        $sql = "SELECT id, is_recurring, idrec, description, start_time, end_time, created_time, idroom, iduser FROM booker_events";
        if (isset($queryParams['idroom']))
        {
            $sql.= " WHERE idroom=".$queryParams['idroom'];
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

    public function insertEvents($pathParams, $post)
    {
        $id=0;
        $is_recurring = ($post['is_recurring'] == true)? 1:0;
        $idrec = $this->getNewIdRec();
        $period = $post['period'];
        $duration_recurring = $post['duration_recurring'];
        $description = $post['description'];
        $date = $post['date'];
        $start_time = $date.' '.$post['start_time'];
        $end_time = $date.' '.$post['end_time'];
        $idroom = $post['idroom'];
        $iduser = $post['iduser'];
    
        $mysql = new MySQL();
        $sql = "INSERT INTO booker_events 
        (id, is_recurring, idrec, description, start_time, end_time, idroom, iduser) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $mysql->setSql($sql);
        $sqlParams=[$id, $is_recurring, $idrec, $description, $start_time, $end_time, $idroom, $iduser];

        $tmp = [$id, $is_recurring, $idrec, $period, $duration_recurring, $description, $date, $start_time, $end_time, $idroom, $iduser];
        //$result = $mysql->insert($sqlParams);
        return $result;
    }

    public function updateEvents($pathParams, $put)
    {
        $id=$pathParams[0];
        $is_recurring = ($put['is_recurring'] == true)? 1:0;
        $idrec = $put['idrec'];
        $description = $put['description'];
        $start_time = $put['start_time'];
        $end_time = strtotime($put['end_time']);
        $idroom = $put['idroom'];
        $iduser = $put['iduser'];
        $mysql = new MySQL();
        $sql = "UPDATE booker_events SET 
        is_recurring=?, description=?, start_time=?, end_time=?, iduser=? 
        WHERE id=?";
        $mysql->setSql($sql);
        //$sqlParams=[0, 1, 'updated event','2018-09-27 18:00:00', '2018-09-27 18:30:00', 1, 2, $id];
        $sqlParams = [$is_recurring, $description, $start_time, $end_time, $iduser, $id];
        $result = $mysql->update($sqlParams);
        $tmp = [$id, $is_recurring, $idrec, $description, $start_time, $end_time, $idroom, $iduser];
        return $sqlParams;

    }

    public function deleteEvents($pathParams, $queryParams)
    {
        $id=$pathParams[0];
        $mysql = new MySQL();
        $sql = "DELETE FROM booker_events WHERE id=?";
        $mysql->setSql($sql);
        $sqlParams=[$id];
        $result = $mysql->update($sqlParams);
        return $queryParams;
    }
    
    private function getNewIdRec()
    {
        return strtotime("now");
    }
    
}


?>
