<?php
session_start();
require("smarty-3.1.30/libs/Smarty.class.php");
$tpl = new Smarty();

if (empty($_SESSION['steam_uptodate']) or empty($_SESSION['steam_personaname'])) {
	require '../steamauth/SteamConfig.php';
	$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']);
	$content = json_decode($url, true);
	$_SESSION['time'] = time();
	$_SESSION['jsonurl'] = $url;
	
	
	//$_SESSION['steam_communityvisibilitystate'] = $content['response']['players'][0]['communityvisibilitystate'];
	//$_SESSION['steam_profilestate'] = $content['response']['players'][0]['profilestate'];
	//$_SESSION['steam_avatar'] = $content['response']['players'][0]['avatar'];
	//$_SESSION['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
	//$_SESSION['steam_personastate'] = $content['response']['players'][0]['personastate'];
	//$_SESSION['steam_primaryclanid'] = $content['response']['players'][0]['primaryclanid'];
	
	$_SESSION['steam_steamid'] = $content['response']['players'][0]['steamid'];
	$_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
	$_SESSION['steam_lastlogoff'] = $content['response']['players'][0]['lastlogoff'];
	$_SESSION['steam_profileurl'] = $content['response']['players'][0]['profileurl'];
	$_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
	if (isset($content['response']['players'][0]['realname'])) { 
		   $_SESSION['steam_realname'] = $content['response']['players'][0]['realname'];
	   } else {
		   $_SESSION['steam_realname'] = "Real name not given";
	}
	$_SESSION['steam_timecreated'] = $content['response']['players'][0]['timecreated'];
	$_SESSION['steam_loccountrycode'] = $content['response']['players'][0]['loccountrycode'];
	$_SESSION['steam_locstatecode'] = $content['response']['players'][0]['locstatecode'];
	$_SESSION['steam_loccityid'] = $content['response']['players'][0]['loccityid'];
	$_SESSION['steam_uptodate'] = time();
}

//$steamprofile['communityvisibilitystate'] = $_SESSION['steam_communityvisibilitystate'];
//$steamprofile['profilestate'] = $_SESSION['steam_profilestate'];
//$steamprofile['avatar'] = $_SESSION['steam_avatar'];
//$steamprofile['avatarmedium'] = $_SESSION['steam_avatarmedium'];
//$steamprofile['personastate'] = $_SESSION['steam_personastate'];
//$steamprofile['primaryclanid'] = $_SESSION['steam_primaryclanid'];

/*
$steamprofile['steamid'] = $_SESSION['steam_steamid'];
$steamprofile['personaname'] = $_SESSION['steam_personaname'];
$steamprofile['lastlogoff'] = $_SESSION['steam_lastlogoff'];
$steamprofile['profileurl'] = $_SESSION['steam_profileurl'];
$steamprofile['avatarfull'] = $_SESSION['steam_avatarfull'];
$steamprofile['realname'] = $_SESSION['steam_realname'];
$steamprofile['timecreated'] = $_SESSION['steam_timecreated'];
$steamprofile['uptodate'] = $_SESSION['steam_uptodate'];*/


//lieu
$PlaceUrl = file_get_contents("steam_countries.json",true);
$PlaceContent = json_decode($PlaceUrl, true);
$UserCountry= $PlaceContent[$_SESSION['steam_loccountrycode']]['name'];
$UserState= $PlaceContent[$_SESSION['steam_loccountrycode']]["states"][$_SESSION['steam_locstatecode']]['name'];
$UserCity= $PlaceContent[$_SESSION['steam_loccountrycode']]["states"][$_SESSION['steam_locstatecode']]["cities"][$_SESSION['steam_loccityid']]['name'];

//tps creation compte
	$timeSpend = $_SESSION['time'] - $_SESSION['steam_timecreated'];
	$timeSpend -= $timeSpend%(3600*24);
	$timeSpend = $timeSpend/(3600*24);
	$dayCreate = $timeSpend%365;
	$yearCreate = ($timeSpend-$dayCreate)/365;
//derniere co
	$lastCo= date('d/m/Y H:i:s e',$_SESSION['steam_lastlogoff']);



$tpl->assign('steamid',$_SESSION['steam_steamid']);
$tpl->assign('personaname',$_SESSION['steam_personaname']);
$tpl->assign('lastlogoff',$_SESSION['steam_lastlogoff']);
$tpl->assign('profileurl',$_SESSION['steam_profileurl']);
$tpl->assign('avatarfull',$_SESSION['steam_avatarfull']);
$tpl->assign('realname',$_SESSION['steam_realname']);
$tpl->assign('timecreated',$_SESSION['steam_timecreated']);
$tpl->assign('yearCreate',$yearCreate);
$tpl->assign('dayCreate',$dayCreate);
$tpl->assign('lastCo',$lastCo);
//$tpl->assign('uptodate',$_SESSION['uptodate']);
$tpl->assign('usercountry',$UserCountry);
$tpl->assign('userstate',$UserState);
$tpl->assign('usercity',$UserCity);

$tpl->assign('apikey','D004601E2AC5480D014CBD90EE981BA4');
$tpl->assign('json',$_SESSION['jsonurl']);

switch ($_POST['template']){
	case 1:
		$tpl->display("../templates/userinfo/query.html");
		break;
	case 2:
		echo $_SESSION['jsonurl'];
		break;
	default:
	case 3:
		$tpl->display("../templates/userinfo/userinfo.html");
}




// Version 3.2
?>