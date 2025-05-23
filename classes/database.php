<?php

Class Database
{
    private $con;
    
    //construct
    function __construct()
    {
        $this-> con = $this->connect();        

    }

    //connect to db
    private function connect()
    {
        $string = "mysql:host=localhost;dbname=mychat_db";
        try 
        {
            $connection = new PDO($string,DBUSER,DBPASS);
            return $connection;

        }catch(PDOException $e)
        {
            echo $e ->getMessage();
            die;
        }

       
    }
    //write to database
    public function write($query, $data_array = [])
    {
        $con = $this->connect();
        $statement = $con->prepare($query);
        $check = $statement->execute($data_array);
        
        if($check)
        {
            return true;
        }
        return false;
    
    }   


    //read from database
    public function read($query, $data_array = [])
    {
        $con = $this->connect();
        $statement = $con->prepare($query);
        $check = $statement->execute($data_array);
        
        if($check)
        {
            $result = $statement->fetchAll(PDO::FETCH_OBJ); //:: MEANS ACCESSING FUNCTION INSIDE A CLASS
            if(is_array($result) && count($result) > 0)     
            {
                return $result;
            }       
            return false;
        }
        return false;
    
    }   
    //get user from database
    public function get_user($user_id)
    {
        $con = $this->connect();
        $arr['user_id'] = $user_id;
        $query = "select * from users where user_id = :user_id limit 1";
        $statement = $con->prepare($query);
        $check = $statement->execute($arr);
        
        if($check)
        {
            $result = $statement->fetchAll(PDO::FETCH_OBJ); //:: MEANS ACCESSING FUNCTION INSIDE A CLASS
            if(is_array($result) && count($result) > 0)     
            {
                return $result[0];
            }       
            return false;
        }
        return false;
    
    }  

    public function generate_id($max) //generates a random id
    {
        $rand = "";
        $rand_count = rand(4,$max);
        for ($i=0; $i < $rand_count; $i++) { 
            
            $r = rand(0,9);
            $rand .= $r;
        }

        return $rand;

    }

}