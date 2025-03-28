<?php

$info = (object)[];
    //sign up, auto-generated
    $data= false;
    $data['user_id'] = $DB->generate_id(20);
    $data['date'] = date('Y-m-d H:i:s');
    //sign up, generated outside the api
    $data['username'] = $DATA_OBJ->username;
    
    if(empty($DATA_OBJ->username))
    {
        $error .= "Please, enter a valid username. <br>";
    }else{
        
        if(strlen($DATA_OBJ->username) < 4)
        {
            $error .= "Username must be at least 4 characters long. <br>";
        }
        if(!preg_match("/^[a-z A-Z 0-9 _ . -]*$/", $DATA_OBJ -> username))
        {
            $error .= "Please enter a valid username. <br>";
        }
    }

    $data['email'] = $DATA_OBJ->email;

    if(empty($DATA_OBJ->email))
    {
        $error .= "Please, enter a valid email. <br>";
    }else{
        
       
       
        if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $DATA_OBJ -> email))
        {
            $error .= "Please enter a valid email. <br>";
        }
    }

    $data['password'] = $DATA_OBJ->password;

    if(empty($DATA_OBJ->password))
    {
        $error .= "Please, enter a password. <br>";
    }else{
        
        if(strlen($DATA_OBJ->password) < 8)
        {
            $error .= "Password must be at least 8 characters long. <br>";
        }
        if($DATA_OBJ->password != $DATA_OBJ->password2)
        {
            $error .= "Passwords do not match. <br>";
        }
    }

    $password = $DATA_OBJ->password2;
    

    if($error == ""){

        $query = "insert into users (user_id,username,email,password,date) values (:user_id,:username,:email,:password,:date)";
        $result = $DB->write($query,$data);

        if($result)
        {
            
            $info->message = "Your profile was created";
            $info->data_type = "info";
            echo json_encode($info);
        }else {
            $info->message = "Your profile was NOT created due to an error";
            $info->data_type = "error";
            echo json_encode($info);
        }


    }else {
      
        $info->message = $error;
        $info->data_type = "error";
        echo json_encode($info);
    }
