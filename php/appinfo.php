<?php
session_start();
require("smarty-3.1.30/libs/Smarty.class.php");
$tpl = new Smarty();

if (empty($_SESSION['steam_uptodate']) or empty($_SESSION['steam_appid'])) {
	require '../steamauth/SteamConfig.php';
	$appid=227300;
	$url = file_get_contents("http://store.steampowered.com/api/appdetails?appids=".$appid);
	$content = json_decode($url, true);
	$_SESSION['time'] = time();
	$_SESSION['jsonurl'] = $url;
	
	$_SESSION['steam_appid'] = $appid;
	$_SESSION['steam_success'] = $content[$appid]['success'];
	$_SESSION['steam_gametype'] = $content[$appid]['data']['type'];
	$_SESSION['steam_gamename'] = $content[$appid]['data']['name'];
	$_SESSION['steam_gameminage'] = $content[$appid]['data']['required_age'];
	$_SESSION['steam_isfree'] = $content[$appid]['data']['is_free'];
	if ($_SESSION['steam_gametype']=="game"){
		$_SESSION['steam_dlc']=$content[$appid]['data']['dlc'];
	};
	
	$_SESSION['steam_uptodate'] = time();
}


$tpl->assign('appid',$_SESSION['steam_appid']);
if ($_SESSION['steam_success']) {	
	$tpl->assign('success',"Disponnible");
}else {	
	$tpl->assign('success',"Non disponnible");
};
$tpl->assign('gametype',$_SESSION['steam_gametype']);
$tpl->assign('gamename',$_SESSION['steam_gamename']);
$tpl->assign('gameminage',$_SESSION['steam_gameminage']);
if ($_SESSION['steam_isfree']) {	
	$tpl->assign('isfree',"Oui");
}else {	
	$tpl->assign('isfree',"Non");
};
if ($_SESSION['steam_gametype']=="game") {
	$tpl->assign('dlcs',$_SESSION['steam_dlc']);
}


$tpl->assign('apikey','D004601E2AC5480D014CBD90EE981BA4');
$tpl->assign('json',$_SESSION['jsonurl']);

switch ($_POST['template']){
	case 1:
		$tpl->display("../templates/appinfo/query.html");
		break;
	case 2:
		echo $_SESSION['jsonurl'];
		break;
	default:
	case 3:
		$tpl->display("../templates/appinfo/appinfo.html");
}




// Version 3.2
?>