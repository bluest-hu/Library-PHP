<?php



if(function_exists($_GET['method'])){
    //Call the passed function
    $_GET['method']();
}
//Here is the function to get
function allUsers(){
    
    //Set $users to json encode $users
    $users=json_encode($users);
    //Okay here is the JSONP
    echo $_GET['jsoncallback'].'('.$users.')';
}
?>