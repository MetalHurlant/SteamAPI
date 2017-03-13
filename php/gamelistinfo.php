<?php
session_start();
require("smarty-3.1.30/libs/Smarty.class.php");
$tpl = new Smarty();


		
if (empty($_SESSION['steam_uptodate']) or empty($_SESSION['steam_game_count'])) {
	require '../steamauth/SteamConfig.php';
	$url = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&format=json");
	$content = json_decode($url, true);
	$_SESSION['time'] = time();
	$_SESSION['jsonurl'] = $url;
	
	
	$_SESSION['steam_game_count'] = $content['response']['game_count'];
	$_SESSION['steam_games'] = $content['response']['games'];
	
	$gameplayed=0;
	$totalprice=0;
	$games=array();
	foreach ($_SESSION['steam_games'] as $game){
		$id=$game['appid'];
		$tps=$game['playtime_forever'];
		
		$gameurl = file_get_contents("http://store.steampowered.com/api/appdetails?appids=".$id);
		$gamecontent = json_decode($gameurl, true);
		

		$name = $gamecontent[$id]['data']['name'];
		$price = $gamecontent[$id]['data']['price_overview']['initial'];
		$soldprice = $gamecontent[$id]['data']['price_overview']['final'];
		
		array_push($games, array('id' => $id, 'name' => $name, 'tps' => $tps));
		
		$gameplayed += $game['playtime_forever'];
		$totalprice += $price;
		$totalsoldprice += $soldprice;
	};
	
	$_SESSION['steam_games']=$games;
	$_SESSION['steam_gameplayed']=$gameplayed;
	$_SESSION['steam_totalprice']=$totalprice;
	$_SESSION['steam_totalsoldprice']=$totalsoldprice;
	
	$_SESSION['steam_uptodate'] = time();
}

$tpl->assign('gameCount',$_SESSION['steam_game_count']);
$tpl->assign('games',$_SESSION['steam_games']);
$tpl->assign('gameplayed',$_SESSION['steam_gameplayed']/60);
$tpl->assign('totalprice',$_SESSION['steam_totalprice']/100);
$tpl->assign('totalsoldprice',$_SESSION['steam_totalsoldprice']/100);

$tpl->assign('apikey','D004601E2AC5480D014CBD90EE981BA4');
$tpl->assign('json',$_SESSION['jsonurl']);

switch ($_POST['template']){
	case 1:
		$tpl->display("../templates/gamelistinfo/query.html");
		break;
	case 2:
		echo $_SESSION['jsonurl'];
		break;
	default:
	case 3:
		$tpl->display("../templates/gamelistinfo/gamelistinfo.html");
}




// Version 3.2
?>