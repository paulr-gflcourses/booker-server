<?php
class UsersModel
{
    public function getUsers($pathParams, $queryParams)
    {
        try
        {
            $mysql = new MySQL();
            $mysql->setSql("SELECT id, username, password, email, fullname, is_admin, is_active FROM booker_users");
            $result = $mysql->select();
        }catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    
    public function insertUser($pathParams, $post)
    {
        $id = 0;
        $fullname = $post['fullname'];
        $email = $post['email'];
        $username = $post['username'];
        $password = $post['password'];
        $is_admin = ($post['is_admin'] == 'true') ? 1 : 0;
        $is_active = '1';
            try
            {
                $mysql = new MySQL();
                $mysql->setSql("INSERT INTO booker_users(id, fullname, email, username, password, is_admin, is_active)"
                    ." VALUE(?, ?, ?, ?, ?, ?, ?)");
                $result = $mysql->insert([0, $fullname, $email, $username, $password, $is_admin, $is_active]);
                return $result;
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
    }
    
    public function updateUser($pathParams, $post)
    {
        $id = $post['id'];
        $fullname = $post['fullname'];
        $email = $post['email'];
        $username = $post['username'];
        $password = $post['password'];
        $is_admin = ($post['is_admin'] == 'true') ? 1 : 0;
        $is_active = '1';
        try
        {
            $mysql = new MySQL();
            $mysql->setSql("UPDATE booker_users SET fullname=?, email=?, username=?, password=?, is_admin=?"
                ." WHERE id=?");
            $result = $mysql->update([$fullname, $email, $username, $password, $is_admin, $id]);
            return $result;
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteUser($pathParams, $post)
    {
        $id = $post['id'];
        try
        {
            $mysql = new MySQL();
            $mysql->setSql("UPDATE booker_users SET is_active=0"
                ." WHERE id=?");
            $result = $mysql->update([$id]);
            return $result;
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }

    }
}
