<?php
    $arr['user_id'] = "null";
    //reciever
    if(isset($DATA_OBJ->find->user_id)){
        $arr['user_id'] = $DATA_OBJ->find->user_id;
    }


    $sql = "select * from users where user_id = :user_id limit 1";
    $result = $DB->read($sql,$arr);
    //vuv videoto na picha result e row
    if(is_array($result))
    {

        $arr['message']= $DATA_OBJ->find->message;
        $arr['date']= date("Y-m-d H:i:s");
        $arr['sender']= $_SESSION['userid'];
        $arr['msgid']= get_random_string_max(60);
            

            $arr2['sender']= $_SESSION['userid'];
            $arr2['receiver']=  $arr['user_id'];
            
            $sql = "select * from messages where (sender = :sender && receiver = :receiver) || (receiver = :sender && sender = :receiver) limit 1";
            
            $result2 = $DB->read($sql,$arr2);
            
            //vuv videoto na picha result e row
            
            if(is_array($result2))
            {
                $arr['msgid'] = $result2[0]->msgid;
            }

        $query="insert into messages (sender,receiver,message,date,msgid) values (:sender,:user_id,:message,:date,:msgid)";
        $DB->write($query,$arr);
        //user found
        $result = $result[0];
        
        $image = ($result->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
        if(file_exists($result->image))
        {
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
                    
                        //read from db
                        
                        $a['msgid'] = $arr['msgid'];

                        $sql = "select * from messages where msgid = :msgid order by id desc limit 20";
                        
                        $result2 = $DB->read($sql,$a);
                        
                        //vuv videoto na picha result e row
                        
                        if(is_array($result2))
                        {
                            $result2 = array_reverse($result2);
                            //getting the first result
                            foreach ($result2 as $data)
                            {
                                $myusers = $DB->get_user($data->sender);
                               
                                if($_SESSION['userid'] == $data->sender)
                                {
                                    $messages .= message_right($data, $myusers);
                                }else{
                                    $messages .= message_left($data, $myusers);

                                }   

                            }
                        }

        $messages.= message_controls();

        
        $info->user = $mydata;
        $info->messages = $messages;
        $info->data_type = "send_message";
        echo json_encode($info);
    }else{
    //user not found
    $info->message = "That contact was not found!";
    $info->data_type = "chat";
    echo json_encode($info);
    
    }

    function get_random_string_max($length){
        $array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $text = "";

        $length = rand(4,$length);

        for($i=0;$i<$length;$i++)
        {
            $random = rand(0,61);

            $text .= $array[$random];
        }

        return $text;

    }

?>