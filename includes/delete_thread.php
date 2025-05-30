<?php
    $arr['user_id'] = "null";
    if(isset($DATA_OBJ->find->user_id)){
        $arr['user_id'] = $DATA_OBJ->find->user_id;
    }

    $arr['sender'] = $_SESSION['userid'];
    $arr['receiver'] = $arr['user_id'];
    
    $sql = "select * from messages where (sender = :sender && receiver = :receiver) || (receiver = :sender && sender = :receiver)";
    $result = $DB->read($sql,$arr);
    
    if(is_array($result))
    {
        foreach($result as $row)
        {
            if($_SESSION['userid'] == $row->sender)
            {
                $sql = "update messages set deleted_sender = 1 where id = :rowid limit 1";
                $DB->write($sql, ['rowid' => $row->id]);
            }
            if($_SESSION['userid'] == $row->receiver)
            {
                $sql = "update messages set deleted_receiver = 1 where id = :rowid limit 1";
                $DB->write($sql, ['rowid' => $row->id]);
            }

        }
    }
?>