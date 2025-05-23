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
            //check for new messages
            $msgs = array();
            $me = $_SESSION['userid']; //check here!!!
            $query = "select * from messages where receiver = '$me' && received = 0";
            $mymsg = $DB->read($query,[]);

            if(is_array($mymsg))
            {
                foreach ($mymsg as $row2)
                {
                    $sender = $row2->sender;

                    if(isset($msgs[$sender]))
                    {
                       $msgs[$sender]++;
                    }else
                    {
                        $msgs[$sender] = 1;
                    }

                }
            }
            foreach ($myusers as $row){ 

                $image = ($row->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
                if(file_exists($row->image)){
                    $image= $row->image;
                }  
  
                $mydata .= "
                <div id='contact' style='position:relative;' userid='$row->user_id' onclick='start_chat(event)'>
                    <img src='$image' alt='user'>
                    <br>$row->username";
                if(count($msgs) > 0 && isset($msgs[$row->user_id])) //check here!!!
                {
                    $mydata.= "<div style='width:20px;height:20px;border-radius:50%;background-color:red;color:white;position:absolute;left:0px;top:0px;'>".$msgs[$row->user_id]."</div>";
                } 
                $mydata.="</div>"; //unread meesages bubbles in contacts
            } 
        }     
       
    $mydata .= '
    </div>';

    //$result = $result[0];
    $info->message = $mydata;
    $info->data_type = "contacts";
    echo json_encode($info);
    
    //tuk imashe die
    if (!is_array($myusers)) {
        $info->message = "No contacts were found!";
        $info->data_type = "error";
        echo json_encode($info);
        die;
    }


    
?>

                    