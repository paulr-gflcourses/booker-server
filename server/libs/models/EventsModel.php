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
    public function insertEvents($pathParams, $queryParams)
    {
        $mysql = new MySQL();
        $sql = "INSERT INTO booker_events 
        (id, is_recurring, idrec, description, start_time, end_time, idroom, iduser) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $mysql->setSql($sql);
        $sqlParams=[0, 0, 1, 'inserted event','2018-09-27 14:00:00', '2018-09-27 14:30:00', 1, 2];
        $result = $mysql->insert($sqlParams);
        return $queryParams;
    }

    public function updateEvents($pathParams, $queryParams)
    {
        $id=$pathParams[0];
        $mysql = new MySQL();
        $sql = "UPDATE booker_events SET 
        is_recurring=?, idrec=?, description=?, start_time=?, end_time=?, idroom=?, iduser=? 
        WHERE id=?";
        $mysql->setSql($sql);
        $sqlParams=[0, 1, 'updated event','2018-09-27 18:00:00', '2018-09-27 18:30:00', 1, 2, $id];
        $result = $mysql->update($sqlParams);
        return $queryParams;

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
    
}


?>