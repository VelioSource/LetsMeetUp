<?php
     $mydata = 
     '<div id="contact" style="text-align: center;">
        <img src="./ui/images/user1.jpg" alt="user2">
        <br>Username
    </div>
    
    <div id="contact">
        <img src="./ui/images/user2.jpg" alt="user2">
        <br>Username
    </div>
    
    <div id="contact">
        <img src="./ui/images/user3.jpg" alt="user2">
        <br>Username
    </div>
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

                    