<?php

/** 
 * Manipulates with events data
 */
class EventsModel
{

    /**
     * Gets all events from the database
     */
    public function getEvents($pathParams, $queryParams)
    {
        $sql = 'SELECT id, is_recurring, idrec, description, start_time, end_time, created_time, idroom, iduser FROM booker_events';
        if (isset($queryParams['idroom']))
        {
            $sql .= ' WHERE idroom=' . $queryParams['idroom'];
        }
        try
        {
            $mysql = new MySQL();
            $mysql->setSql($sql);
            $result = $mysql->select();
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Inserts one or multiple events to the database
     */
    public function insertEvents($pathParams, $post)
    {
        $id = 0;

        $this->validateEmpty($post,['idroom','iduser','description','is_recurring','date','start_time','end_time']);
        $idroom = $post['idroom'];
        $iduser = $post['iduser'];
        $description = $post['description'];

        $is_recurring = ($post['is_recurring'] == 'true') ? 1 : 0;
        if ($is_recurring)
        {
            $this->validateEmpty($post,['period','duration_recurring']);
        }
        $idrec = $this->getNewIdRec();
        $period = $post['period'];
        $duration_recurring = $post['duration_recurring'];

        $date = $post['date'];
        date_default_timezone_set('UTC');
        $start_time = date('Y-m-d H:i', +$post['start_time']);
        $end_time = date('Y-m-d H:i', +$post['end_time']);

        

        if ($is_recurring)
        {
            $dates = $this->createRecDates($start_time, $end_time, $period, $duration_recurring);
            foreach ($dates as $rec_date)
            {
                $rec_start_time = $rec_date[0];
                $rec_end_time = $rec_date[1];
                $this->isActualDate($rec_start_time,$rec_end_time);
                $this->isFreeRange($idroom,$rec_start_time,$rec_end_time);
            }
            foreach ($dates as $rec_date)
            {
                $rec_start_time = $rec_date[0];
                $rec_end_time = $rec_date[1];
                $this->insertSingleEvent($is_recurring, $idrec, $description, $rec_start_time, $rec_end_time, $idroom, $iduser);
            }
        }
        else
        {
            $this->isActualDate($start_time, $end_time);
            $this->isFreeRange($idroom, $start_time, $end_time);
            $this->insertSingleEvent($is_recurring, $idrec, $description, $start_time, $end_time, $idroom, $iduser);
        }
        return false;
    }

    private function validateEmpty($array, $names)
    {
        foreach($names as $name)
        {
            if (!(isset($array[$name]) && $array[$name])){
                http_response_code(400);
                throw new Exception('Some fields are empty!');
            }
        }

    }
    /**
     * Forms the dates to the recurring events
     */
    private function createRecDates($start_time, $end_time, $period, $duration)
    {
        $dates = [[$start_time, $end_time]];
        $n = +$duration;
        $periods = ['weekly' =>['week',1], 'bi-weekly' => ['week',2], 'monthly' => ['week',4]];
        $timeMeasure = $periods[$period][0];
        $nPeriods = $periods[$period][1];
        for ($i = 0; $i < $n; $i++)
        {
            $start = strtotime($start_time . ' +' . $nPeriods * ($i + 1) . ' '. $timeMeasure);
            $end = strtotime($end_time . ' +' . $nPeriods * ($i + 1) . ' '. $timeMeasure);
            $dates[] = [date('Y-m-d H:i', $start), date('Y-m-d H:i', $end)];
        }
        return $dates;
    }

    /**
     * Checks if a date is actual
     */
    private function isActualDate($start_time, $end_time)
    {
        date_default_timezone_set('UTC');
        $now = time();
        $time1 = strtotime($start_time);
        $time2 = strtotime($end_time);
        if ($time1<$now || $time2<$now || $time1>=$time2)
        {
            http_response_code(403);
            throw new Exception("The date is not actual!");
        }
        
    }

    /**
     * Checks if the time for a new event is not bisy
     */
    private function isFreeRange($idroom, $start_time, $end_time, $current_id=false)
    {
        $sql = 'SELECT id, start_time, end_time, idroom, iduser FROM booker_events';
        $sql .= ' WHERE idroom=' . $idroom;
        $sql .= " AND (((start_time >= '$start_time') AND (end_time <= '$end_time'))"
                    ." OR ((start_time >= '$start_time') AND (start_time <= '$end_time'))"
                    ." OR ((end_time >='$start_time') AND (end_time <= '$end_time')))";
        if ($current_id)
        {
            $sql .= " AND id<>$current_id";
        }
        try
        {
            $mysql = new MySQL();
            $mysql->setSql($sql);
            $result = $mysql->select();
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        $events = $result->fetchAll(PDO::FETCH_ASSOC);
        if (count($events)>0)
        {
            http_response_code(403);
            throw new Exception("The time range is bisy!");
        }
        return true;
    }


/**
 * Inserts one single event to the database
 */
    private function insertSingleEvent($is_recurring, $idrec, $description, $start_time, $end_time, $idroom, $iduser)
    {
        $id = 0;
        $mysql = new MySQL();
        $sql = 'INSERT INTO booker_events 
        (id, is_recurring, idrec, description, start_time, end_time, idroom, iduser) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $mysql->setSql($sql);
        $sqlParams = [$id, $is_recurring, $idrec, $description, $start_time, $end_time, $idroom, $iduser];
        $result = $mysql->insert($sqlParams);
        return $result;
    }


    /**
     * Updates one or multiple events to the database
     */
    public function updateEvents($pathParams, $put)
    {
        $id = $pathParams[0];
        $is_recurring = ($put['is_recurring'] == 'true') ? 1 : 0;
        $applyToAllRec = ($put['applyToAllRec'] == 'true') ? 1 : 0;
        $idrec = $put['idrec'];
        $description = $put['description'];
        date_default_timezone_set('UTC');
        $start_time = date('Y-m-d H:i', +$put['start_time']);
        $end_time = date('Y-m-d H:i', +$put['end_time']);
        $idroom = $put['idroom'];
        $iduser = $put['iduser'];

        if ($is_recurring && $applyToAllRec)
        {
            $rec_events = $this->getRecEvents($idrec);
            foreach ($rec_events as $event)
            {
                $id = $event['id'];
                $start = $this->changeTime($event['start_time'], $start_time);
                $end = $this->changeTime($event['end_time'], $end_time);
                $this->isActualDate($start, $end);
                $this->isFreeRange($idroom, $start, $end, $id);

                $mysql = new MySQL();
                $sql = 'UPDATE booker_events SET 
        is_recurring=?, description=?, start_time=?, end_time=?, iduser=? WHERE id=?';
            $mysql->setSql($sql);
            $sqlParams = [$is_recurring, $description, $start, $end, $iduser, $id];
            $result = $mysql->update($sqlParams);

            }
        }
        else
        {
            $this->isActualDate($start_time, $end_time);
            $this->isFreeRange($idroom, $start_time, $end_time, $id);
            $mysql = new MySQL();
            $sql = 'UPDATE booker_events SET 
        is_recurring=?, description=?, start_time=?, end_time=?, iduser=? WHERE id=?';
            $mysql->setSql($sql);
            $sqlParams = [$is_recurring, $description, $start_time, $end_time, $iduser, $id];
            $result = $mysql->update($sqlParams);
        }
        return $sqlParams;
    }

    /**
     * Gets all events that are recurring for the given event
     */
    private function getRecEvents($idrec)
    {
        $mysql = new MySQL();
        $sql = 'SELECT id, is_recurring, idrec, description, start_time, end_time, created_time, idroom, iduser FROM booker_events';
        $sql .= " WHERE idrec=$idrec";
        $mysql->setSql($sql);
        $result = $mysql->select();
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Returns the combination of the given date and time
     */
    private function changeTime($srcDate, $srcTime)
    {
        $time1 = strtotime($srcDate);
        $time2 = strtotime($srcTime);
        $resDate = strtotime(date('Y-m-d', $time1).' '.date('H:i', $time2) );
        return date('Y-m-d H:i',$resDate);

    }

    /**
     * Deletes one or multiple events from the database
     */
    public function deleteEvents($pathParams, $del)
    {
        $id = $pathParams[0];
        $is_recurring = ($del['is_recurring'] == 'true') ? 1 : 0;
        $applyToAllRec = ($del['applyToAllRec'] == 'true') ? 1 : 0;
        $idrec = $del['idrec'];
        $description = $del['description'];
        date_default_timezone_set('UTC');
        $start_time = date('Y-m-d H:i', +$del['start_time']);
        $end_time = date('Y-m-d H:i', +$del['end_time']);
        $idroom = $del['idroom'];
        $iduser = $del['iduser'];
        if ($is_recurring && $applyToAllRec)
        {
            $this->isActualDate($start_time, $end_time);
            $rec_events = $this->getRecEvents($idrec);
            foreach ($rec_events as $event)
            {
                $id = $event['id'];
                $start = $this->changeTime($event['start_time'], $start_time);
                $end = $this->changeTime($event['end_time'], $end_time);

                if (strtotime($start)>=strtotime($start_time))
                {
                    $mysql = new MySQL();
                    $sql = 'DELETE FROM booker_events WHERE id=?';
                    $mysql->setSql($sql);
                    $sqlParams = [$id];
                    $result = $mysql->delete($sqlParams);
                }
            }
        }else
        {
            $this->isActualDate($start_time, $end_time);
            $sql = 'DELETE FROM booker_events WHERE id=?';
            $sqlParams = [$id];
            $mysql = new MySQL();
            $mysql->setSql($sql);
            $result = $mysql->delete($sqlParams);
        }
       
        return false;
    }

    /**
     * Calculates a new recurring id for an event
     */
    private function getNewIdRec()
    {
        return strtotime('now');
    }
}
