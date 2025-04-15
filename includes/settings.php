<?php

    $sql = "select * from users where user_id = :userid limit 1";
    $id=$_SESSION['userid'];
    $data = $DB->read($sql,['userid'=>$id]);
    $mydata = "";

 if(is_array($data))
 {
    $data = $data[0];
    
    //check if image exists
    $image = ($data->gender == "Male") ? "./ui/images/user_male.jpg" : "./ui/images/user_male.jpg";
        if(file_exists($data->image)){
            $image= $data->image;
        }        
    $gender_male = "";    
    $gender_female = "";
    
    if($data->gender == "Male")
    {
        $gender_male = "checked";
    }else{
        $gender_female = "checked";
    }

    $mydata ='
        <style>

        #error{
        
            text-align: center;
            padding: 0.5em;
            background-color: red;
            color: white;
            display: none;
        }


        form{
            text-align: left;
            margin: auto;
            padding: 10px;
            width: 100%;
            max-width: 400px;
        }

        input[type=text], input[type=password], input[type=submit]{
            
            padding: 10px;
            margin: 10px;
            width: 200px;
            border-radius: 5px;
            border: solid 1px grey;

        }

        input[type=submit]{
            width: 220px;
            padding: 10px;
            cursor: pointer;
            background-color:rgb(95, 170, 164);
            color: white;
        }

        input[type=radio]{

            transform: scale(1.2);
            cursor: pointer;
        }

        #logout{
            float: right;

        }

        #have_account{

            display: block;
            text-align: center;
            text-decoration: none;
        }
        
        .dragging{
            border: dashed 2px #aaa;
        

        }




        </style>
   
           
                <div id="error">ERROR</div>
                <div style="display:flex;">
                    <div>
                        <img ondragover="handle_drag_and_drop(event)" ondrop="handle_drag_and_drop(event)" ondragleave="handle_drag_and_drop(event)" src="'.$image.'" style="width:200px; height:200px; margin:10px;" />
                        <label for="change_image_input" id="change_image_button" style="background-color:red;display:inline-block;padding:1em;border-radius:5px;cursor:pointer;">
                            Change Image, or Drag & Drop
                        </label>
                    <input type="file" onchange="upload_profile_image(this.files)" id="change_image_input" style="display:none;"><br>

                    </div>
                    <form id="myform">
                        
                            <input type="text" name="username" placeholder="Username" value="'.$data->username.'"><br>
                            <input type="text" name="email" placeholder="E-mail" value="'.$data->email.'"><br>
                        
                        
                        <div style="padding: 10px;">
                        <br>Gender:<br>
                        <input type="radio" value="Male" name="gender" '.$gender_male.'> Male<br>
                        <input type="radio" value="Female" name="gender" '.$gender_female.'> Female<br>
                        </div>


                        <input type="password" name="password" placeholder="Password" value="'.$data->password.'"><br>
                        <input type="password" name="password2" placeholder="Retype Password" value="'.$data->password.'"><br>   
                        <input type="submit" value="Save Settings" id="save_settings_button" onclick="collect_data(event)"><br>


                    </form>
                </div>
           
       
     ';

     
     $info->message = $mydata;
     $info->data_type = "contacts";
     echo json_encode($info);
    
 }else{
    $info->message = "No contacts were found!";
    $info->data_type = "error";
    echo json_encode($info);
 }   
    

    
?>

                    