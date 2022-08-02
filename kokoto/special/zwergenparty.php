<?php
////////////////////////// 
// Make By Kev          // 
// 25.07.2004           // 
// Copyright by Kev     // 
// v0.2 Zwergen Party 	//
// 1.0 by Tidus kokto.de//
//////////////////////////
if (!isset($session)) exit(); 


if ($_GET['op']=='party'){
    addnav('Lauschen','forest.php?op=lauschen'); 
    addnav('Gehen','forest.php?op=leave'); 
    $session['user']['specialinc'] = 'zwergenparty.php'; 
    output('`2Du gehst zu der Zwergenparty hin und kommst immer näher...doch dann bleibst du stehen. Was willst du tun?`n'); 
    }else if ($_GET['op']=='lauschen'){
	if ($session['user']['race']==4){
	addnav('Zurück in den Wald','forest.php'); 
	output('`n`2`c`bZwErGeNpArTy!!!`b`c `n `n `c`LSie erwischen dich beim lauschen!! Aber da du ein `#Zwerg`L bist passiert nichts schlimmes, ganz im gegenteil sie laden dich ein und ihr Trinkt zusammen einige Krüge, und dann ziehst du von dannen.`n`LDu bist leicht angetrunken, und verlierst beim ganzen Feiern einen Waldkampf.`c');
	    $session['user']['turns']--;
	$session['user']['drunkenness']+= 25;
    $session['bufflist']['zwergenbier'] = $buff = array( "name" => "`LZwergenbier","roundmsg" => "`LDu spürst das Zwergenbier!","wearoff" => "Du bist wieder einigermaßen nüchtern.","rounds" => "15","atkmod" => "1.2","defmod" => "1.2","activate" => "offense,defense");
	addnews($session['user']['name'].'`L wurde dabei beobachtet wie er mit den anderen Zwergen im Wald gefeiert hat!');
	$session['user']['specialinc'] = ''; 
	}else{
    $session['user']['specialinc'] = ''; 
    $session['user']['gold'] = 0; 
    addnews($session['user']['name'].' `^wurde gefesselt und nackt an einem Baum gefunden!'); 
    addnav('Zurück in den Wald','forest.php'); 
    output('`2Du gehst und lauschst den Zwergen, was sie sagen, plötzlich drehst du dich um!`nDu siehst lauter Zwerge vor dir, sie stürzen sich auf dich und nehmen dir all dein Gold weg!`n'); 
	}
    }else if ($_GET['op']=='leave'){ 
output('`2Du bist der Meinung, dass du lieber nicht die Zwerge stören solltest und gehst deshalb lieber nicht zu ihnen hin...'); 
addnav('Zurück in den Wald','forest.php'); 
}else{
    addnav('Zur Party','forest.php?op=party'); 
    addnav('Zurück in den Wald','forest.php?op=leave'); 
    $session['user']['specialinc'] = 'zwergenparty.php'; 
    output('`n`2`c`bZwErGeNpArTy!!!`b`c `n `n `c`2Du gehst durch den Wald, plötzlich kommst du an eine Kreuzung...`n`c `c`2Du hörst ein lautes Geräusch...Was willst du tun?`n`c'); 
    }
?>