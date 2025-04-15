<?php

    $sql = "select * from users limit 10";
    $myusers = $DB->read($sql,[]);

    $mydata =
    //if you want animations video 32 
     '<div style="text-align: center;">';

       
        if(is_array($myusers))
        {
            foreach ($myusers as $row){ 

                $image = ($row->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
                if(file_exists($row->image)){
                    $image= $row->image;
                }                
                $mydata .= "
                <div id='contact'>
                    <img src=$image alt='user'>
                    <br>$row->username
                </div>";
            } 
        }     
       
    $mydata .= '
    </div>';

     //$result = $result[0];
     $info->message = $mydata;
     $info->data_type = "contacts";
     echo json_encode($info);
    
     die;
    
    $info->message = "No contacts were found!";
    $info->data_type = "error";
    echo json_encode($info);

    
?>

                    