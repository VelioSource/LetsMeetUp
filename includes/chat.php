<?php
    $arr['user_id'] = "null";
    if(isset($DATA_OBJ->find->user_id)){
        $arr['user_id'] = $DATA_OBJ->find->user_id;
    }


    $sql = "select * from users where user_id = :user_id limit 1";
    $result = $DB->read($sql,$arr);
    //vuv videoto na picha result e row
    if(is_array($result)){
    //user found
    $result = $result[0];
    
    $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
    if(file_exists($result->image)){
            $image= $result->image;
    }       

    $result->image= $image;
    $mydata = "Now chatting with:<br>
                <div id='active_contact'>
                    <img src=$image alt='user'>
                    $result->username
                </div>";
    $messages= "
                <div id='message_holder_parent' style='height:630px;'>
                <div id='message_holder' style='height:490px;overflow-y:scroll;'>";
                 
    

                $messages.="
                </div>    
                <div style='display:flex; width:100%;height:40px;'>
                <label for='message_file'><img src='./ui/icons/clip.png' style='opacity:0.8;width:30px; height:30px; cursor: pointer; margin:5px;'></label>
                <input type='file' id='message_file' name='file' style=display:none;/>
                <input id='message_text' style='flex:6;border: solid thin #ccc; border-bottom:none;;font-size:14px;padding:4px;' type='text' placeholder='Type your message...'>
                <input style='flex:1;cursor:pointer;' type='button' value='send' onclick='send_message(event)'>
                </div>
                </div>
                
                
                ";
    
    $info->user = $mydata;
    $info->messages = $messages;
    $info->data_type = "chat";
    echo json_encode($info);
    }else{
    //user not found
    $info->message = "That contact was not found!";
    $info->data_type = "chat";
    echo json_encode($info);
    
    }



?>