<?php
session_start();
require("smarty-3.1.30/libs/Smarty.class.php");
$tpl = new Smarty();


		
if (empty($_SESSION['steam_uptodate']) or empty($_SESSION['steam_friends'])) {
	require '../steamauth/SteamConfig.php';
	$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&relationship=friend");
	$content = json_decode($url, true);
	$_SESSION['time'] = time();
	$_SESSION['jsonurl'] = $url;
	
	
	$_SESSION['steam_friends'] = $content['friendslist']['friends'];
	
	$friends=[];
	
	foreach ($_SESSION['steam_friends'] as $friend){
		$id=$friend['steamid'];
		$tps=$friend['friend_since'];
		
		$friendurl = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$id);
		$friendcontent = json_decode($friendurl, true);
		
		$name= $friendcontent['response']['players'][0]["personaname"];
		
		array_push($friends, array('id' => $id, 'name' => $name, 'tps' => $tps));
	};
	
	$_SESSION['steam_friends']= $friends;
	
	
	
	$_SESSION['steam_uptodate'] = time();
}

$tpl->assign('friends',$_SESSION['steam_friends']);

$tpl->assign('apikey','D004601E2AC5480D014CBD90EE981BA4');
$tpl->assign('json',$_SESSION['jsonurl']);

switch ($_POST['template']){
	case 1:
		$tpl->display("../templates/friendlistinfo/query.html");
		break;
	case 2:
		echo $_SESSION['jsonurl'];
		break;
	default:
	case 3:
		$tpl->display("../templates/friendlistinfo/friendlistinfo.html");
}




// Version 3.2
?>