<?php
    $myid = $_SESSION['userid'];  
    $sql = "select * from users where user_id != '$myid' limit 10";
    $myusers = $DB->read($sql,[]);

    $mydata =
    //if you want animations video 32 
    '
    <style>
        #contact{
            cursor:pointer;
            transition: all .2s ease;
        }
        #contact:hover{
            transform: scale(1.1);
        }

    
    </style>
     
     
    <div style="text-align: center;">';

       
        if(is_array($myusers))
        {
            foreach ($myusers as $row){ 

                $image = ($row->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
                if(file_exists($row->image)){
                    $image= $row->image;
                }                
                $mydata .= "
                <div id='contact' userid='$row->user_id' onclick='start_chat(event)'>
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

                    