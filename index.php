<?php
//header('Location: $url');  

require ('steamauth/steamauth.php');

if(!isset($_SESSION['steamid'])) {

    loginbutton(); //login button
    
} else {
	logoutbutton();
}