<?php
include_once '../../libs/RestServer.php';
include_once '../../libs/SQL.php';
include_once '../../libs/MySQL.php';
include_once '../../config.php';
include_once "../../libs/models/UsersModel.php";

class Users
{
    
    private $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public function getUsers($pathParams, $queryParams)
    {
        $result = $this->model->getUsers($pathParams, $queryParams);
        return $result;
    }

    public function postUsers($pathParams, $queryParams)
    {
        $result = $this->model->insertUser($pathParams, $queryParams);
        return $result;
    }

    public function putUsers($pathParams, $queryParams)
    {
        $result = $this->model->updateUser($pathParams, $queryParams);
        return $result;

    }

    public function deleteUsers($pathParams, $queryParams)
    {
        $result = $this->model->deleteUsers($pathParams, $queryParams);
        return $result;
    }

    //public function putUsers()
    //{
        ////if (!isset($_SERVER['PHP_AUTH_USER']))
        ////{
            ////header('WWW-Authenticate: Basic realm="My Realm"');
            ////header('HTTP/1.0 401 Unauthorized');
            ////echo 'Canceled';
            ////exit;
        ////}
        ////else
        ////{
            ////$username = $_SERVER['PHP_AUTH_USER'];
            ////$password = $_SERVER['PHP_AUTH_PASSWORD'];
            ////if ($this->isUserExist($username, $password))
            ////{
                ////echo json_encode(['username' => $username]);
            ////}
        ////}
    //}
    //public function deleteUsers()
    //{
        ////if (isset($_SERVER['PHP_AUTH_USER']))
        ////{
            ////unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $_SERVER['AUTH_TYPE'], $_SERVER['REMOTE_USER']);
            ////// header('WWW-Authenticate: Basic realm="My Realm"');
            ////header('HTTP/1.0 401 Unauthorized');
            ////// exit;
        ////}
        ////echo json_encode(['logout' => 'success']);
        ////exit;
    ////}
    ////private function isUserExist($username, $password)
    ////{
        ////return true;
    //}
}
$Users = new Users();
$server = new RestServer($Users);
