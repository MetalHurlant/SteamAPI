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
	$_SESSION['steam_detailed_description'] = $content[$appid]['data']['detailed_description'];
	$_SESSION['steam_supported_languages'] = $content[$appid]['data']['supported_languages'];
	$_SESSION['header_image'] = $content[$appid]['data']['header_image'];
	$_SESSION['currency'] = $content[$appid]['data']['price_overview']['currency'];
	$_SESSION['initialPrice'] = $content[$appid]['data']['price_overview']['initial'];
	$_SESSION['finalPrice'] = $content[$appid]['data']['price_overview']['final'];
	$_SESSION['releaseDate'] = $content[$appid]['data']['release_date']['date'];
	
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
};
$tpl->assign('description',$_SESSION['steam_detailed_description']);
$tpl->assign('languages',$_SESSION['steam_supported_languages']);
$tpl->assign('headerImage',$_SESSION['header_image']);
$tpl->assign('currency',$_SESSION['currency']);
$tpl->assign('initialPrice',$_SESSION['initialPrice']/100);
$tpl->assign('finalPrice',$_SESSION['finalPrice']/100);
$tpl->assign('releaseDate',$_SESSION['releaseDate']);

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