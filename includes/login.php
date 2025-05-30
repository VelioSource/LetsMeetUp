<?php

$info = (object)[];

    //sign up, auto-generated
    $data= false;
   
    //validate info

    $data['email'] = $DATA_OBJ->email;
   
    if(empty($DATA_OBJ->email))
    {
        $error = "Please enter a valid email";
    }

    if(empty($DATA_OBJ->password))
    {
        $error = "Please enter a valid password";
    }


    if($error == ""){

        $query = "select * from users where email = :email limit 1 ";
        $result = $DB->read($query,$data);

        if(is_array($result))
        {
            $result = $result[0];
            if($result->password == $DATA_OBJ->password)
            {
                $_SESSION['userid'] = $result->user_id;
                $info->message = "You're successfully logged in!";
                $info->data_type = "info";
                echo json_encode($info);
            }else{
               
                $info->message = "Wrong email or password!";
                $info->data_type = "error";
                echo json_encode($info);
           
            }


           
        }else {
            $info->message = "Wrong email or password!";
            $info->data_type = "error";
            echo json_encode($info);
        }


    }else {
      
        $info->message = $error;
        $info->data_type = "error";
        echo json_encode($info);
    }
