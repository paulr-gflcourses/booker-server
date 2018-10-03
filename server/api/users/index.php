<?php
include_once '../../libs/RestServer.php';
include_once '../../libs/SQL.php';
include_once '../../libs/MySQL.php';
include_once '../../config.php';
include_once '../../libs/models/UsersModel.php';

/**
 * Performs operations with users
 */
class Users
{
    private $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    /**
     * Executes get query
     */
    public function getUsers($pathParams, $queryParams)
    {
        $result = $this->model->getUsers($pathParams, $queryParams);
        return $result;
    }

    /**
     * Executes post query
     */
    public function postUsers($pathParams, $queryParams)
    {
        $result = $this->model->insertUser($pathParams, $queryParams);
        return $result;
    }

    /**
     * Executes put query
     */
    public function putUsers($pathParams, $queryParams)
    {
        if (isset($queryParams['id']))
        {
            $result = $this->model->updateUser($pathParams, $queryParams);
        }
        else
        {
            $result = $this->model->login($queryParams);    
        }
        return $result;
    }

    /**
     * Executes delete query
     */
    public function deleteUsers($pathParams, $queryParams)
    {
        $result = $this->model->deleteUser($pathParams, $queryParams);
        return $result;
    }
}
$Users = new Users();
$server = new RestServer($Users);
