<?php
include_once "../../libs/RestServer.php";
include_once "../../libs/SQL.php";
include_once "../../libs/MySQL.php";
include_once "../../config.php";
include_once "../../libs/models/EventsModel.php";

class Events
{
    private $model;

    public function __construct()
    {
        $this->model = new EventsModel();
    }

    public function getEvents($pathParams, $queryParams)
    {
        $result = $this->model->getEvents($pathParams, $queryParams);
        return $result;
    }

    public function postEvents($pathParams, $queryParams)
    {
        $result = $this->model->insertEvents($pathParams, $queryParams);
        return $result;
    }

    public function putEvents($pathParams, $queryParams)
    {
        $result = $this->model->updateEvents($pathParams, $queryParams);
        return $result;

    }

    public function deleteEvents($pathParams, $queryParams)
    {
        $result = $this->model->deleteEvents($pathParams, $queryParams);
        return $result;
    }


}

$events = new Events();
$server = new RestServer($events);
