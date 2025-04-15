<?php

$info = (object)[];

    //sign up, auto-generated
    $data= false;
   
    //validate info

    $data['user_id'] = $_SESSION['userid'];
   
   


    if($error == ""){

        $query = "select * from users where user_id = :user_id limit 1 ";
        $result = $DB->read($query,$data);

        if(is_array($result))
        {
            $result = $result[0];
            $result->data_type = "user_info";
            
            //check if image exists
                $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
                if(file_exists($result->image)){
                    $image= $result->image;
                }   
                $result->image = $image;
            echo json_encode($result);
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
