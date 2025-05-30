<?php
    $arr['user_id'] = "null";
    if(isset($DATA_OBJ->find->user_id)){
        $arr['user_id'] = $DATA_OBJ->find->user_id;
    }

    $refresh = false;
    $seen = false;
    if($DATA_OBJ->data_type == "chat_refresh"){ //3:07
        $refresh = true;
        $seen = $DATA_OBJ->find->seen;
    }
    $sql = "select * from users where user_id = :user_id limit 1";
    $result = $DB->read($sql,$arr);
    //vuv videoto na picha result e row

    if ($refresh && isset($arr['user_id'])) {
    $me = $_SESSION['userid'];
    $them = $arr['user_id'];

    $query = "UPDATE messages SET received = 1 WHERE sender = :them AND receiver = :me AND received = 0";
    $DB->write($query, ['them' => $them, 'me' => $me]);
}
    if(is_array($result)){
    //user found
    $result = $result[0];
    
    $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_female.jpg";
    if(file_exists($result->image)){
            $image= $result->image;
    }       

    $result->image= $image;

    $mydata = "";

    if(!$refresh)
    {
        $mydata = "Now chatting with:<br>
        <div id='active_contact'>
            <img src=$image alt='user'>
            $result->username
        </div>";
    }
    
    $messages= "";
    $new_message = false;
    if(!$refresh)
    {
        $messages= "
        <div id='message_holder_parent' onclick='set_seen(event)' style='height:630px;'>
        <div id='message_holder' style='height:490px;overflow-y:scroll;'>";
    }

        //read from db
                        
        $a['sender'] = $_SESSION['userid'];
        $a['receiver'] = $arr['user_id'];
        
        $sql = "select * from messages where (sender = :sender && receiver = :receiver && deleted_sender = 0) || (receiver = :sender && sender = :receiver && deleted_receiver = 0) order by id desc limit 10";
        
        $result2 = $DB->read($sql,$a);
        
        //vuv videoto na picha result e row
        
        if(is_array($result2))
        {
            $result2 = array_reverse($result2);
            //getting the first result
            foreach ($result2 as $data)
            {
                $myusers = $DB->get_user($data->sender);
                //check for new messages
                if($data->receiver == $_SESSION['userid'] && $data->received == 0 )
                {
                    $new_message = true;
                }
                if($data->receiver == $_SESSION['userid'] && $data->received == 1 && $seen)
                {
                    $DB->write("update messages set seen = 1 where id = '$data->id' limit 1");
                }
                if($data->receiver == $_SESSION['userid'])
                {
                    $DB->write("update messages set received = 1 where id = '$data->id' limit 1");
                }
                if($_SESSION['userid'] == $data->sender)
                {
                    $messages .= message_right($data, $myusers);
                }else{
                    $messages .= message_left($data, $myusers);

                }   

            }
        }            
    
    if(!$refresh)
    {
        $messages.= message_controls();
    }

    $info->user = $mydata;
    $info->messages = $messages;
    $info->new_message = $new_message;
    $info->data_type = "chat";
    if($refresh)
    {
        $info->data_type = "chat_refresh";
    }
    echo json_encode($info);
    }else{
         //read from db
                        
        $a['user_id'] = $_SESSION['userid'];
        
        $sql = "select * from messages where (sender = :user_id || receiver = :user_id) group by msgid order by id desc limit 10";
        
        $result2 = $DB->read($sql,$a);
        
        $mydata = "Previous Chat:<br>";
        
         if(is_array($result2))
        {
            $result2 = array_reverse($result2);
            //getting the first result
            foreach ($result2 as $data)
            {
                $other_user = $data->sender;
                if($data->sender == $_SESSION['userid'])
                {
                    $other_user = $data->receiver;
                }
                $myuser = $DB->get_user($other_user);
               
                $image = ($myuser->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_female.jpg";
                if(file_exists($myuser->image)){
                        $image= $myuser->image;
                }       
            
                $mydata .= "
                <div id='active_contact'>
                <div id='active_contact1' userid='{$myuser->user_id}' onclick='start_chat(event)' style='cursor:pointer;'>
                    <img src=$image alt='user'>
                    $myuser->username<br>
                    <span style='font-size:11px;'>'$data->message'</span>
                </div>";
            }
        }            
    
        $info->user = $mydata;
        $info->messages = "";
        $info->data_type = "chat";
        echo json_encode($info);
    
    }



?>