<?php

// 1508004

require_once "common.php";
addcommentary();
checkday();
$cost = $session['user']['level']20;
$gems=array(1=>1,2,3,4,5);
$guilddiscount = $session['guilds'][$session['user']['guildID']]['GemPurchaseDiscount']; //guilds
if ( $guilddiscount> 0 ) { //guilds
    $costs=array(1=>round((45003getsetting("selledgems",0))(1$guilddiscount100)),
                    round((87006getsetting("selledgems",0))(1$guilddiscount100)),
                    round((129009getsetting("selledgems",0))(1$guilddiscount100)),
					round((1710012getsetting("selledgems",0))(1$guilddiscount100)),
					round((2100015getsetting("selledgems",0))(1$guilddiscount100))
                 );
} else {
    $costs=array(1=>45003getsetting("selledgems",0),
					87006getsetting("selledgems",0),
					129009getsetting("selledgems",0),
					1710012getsetting("selledgems",0),
					2100015getsetting("selledgems",0)
					);
}
$scost=1000getsetting("selledgems","0");
$scost5=5500getsetting("selledgems","0");
if ($_GET['op']=='pay'){
	if ($session['user']['gold']>=$cost){ // Gunnar Kreitz

		$session['user']['gold']-=$cost;

			redirect("gypsy.php?op=talk");
	}else{
		page_header("Zigeunerzelt");
		addnav("Zurück zum Dorf","village.php");
		output("`5Du bietest der alten Zigeunerin deine `^{$session['user']['gold']}`5 Gold für die Beschwörungssitzung. Sie informiert dich, dass die Toten zwar tot, aber deswegen trotzdem nicht billig sind.");
	}
}else if ($_GET['op']=="talk"){
	page_header("In tiefer Trance sprichst du mit den Schatten");
	// by nTE- with modifications from anpera
	$sql="SELECT name FROM accounts WHERE locked=0 AND loggedin=1 AND alive=0 AND laston>'".date("Y-m-d H:i:s",strtotime(date("c")."-".getsetting("logintimeout","900")." seconds"))."' ORDER BY login ASC";
	$result=db_query($sql);
	$count=db_num_rows($result);
	$names=$count?"":"niemandem";
	for ($i=0;$i<$count;$i++){
		$row=db_fetch_assoc($result);
		$names.="`^".$row['name'];
		if ($i<$count) $names.=", ";
	}
	db_free_result($result);
	output("`5Du fühlst die Anwesenheit von $names`5.`n`n`5Solange du in tiefer Trance bist, kannst du mit den Toten sprechen:`n");
	viewcommentary("shade","Sprich zu den Toten",10,"spricht");
	addnav("Erwachen","village.php");
}else if($_GET['op']=="buy"){
	page_header("Zigeunerzelt");
	if ($session['user']['transferredtoday']>getsetting("transferreceive","3")){
		output("`5Du hast heute schon genug Geschäfte gemacht. `6Naloni`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
	}else if ($session['user']['gems']>getsetting("selledgems","0")) {
		output("`6Naloni`5 wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben.");
	} else {
  	      	if ($session['user']['gold']>=$costs[$_GET['level']]){
           			if (getsetting("selledgems","0") >= $_GET['level']) {
              				output( "`6Naloni`5 grapscht sich deine `^".($costs[$_GET['level']])." Goldstücke`5 und gibt dir im Gegenzug `#".($gems[$_GET['level']])." Edelstein".($gems[$_GET['level']]>=2?"e":"")."`5.`n`n");
              				$session['user']['gold']-=$costs[$_GET['level']];
              				$session['user']['gems']+=$gems[$_GET['level']];
				$session['user']['transferredtoday']+=1;
              				if (getsetting("selledgems","0")  $_GET['level'] < 1) {
                				savesetting("selledgems","0");
              				} else {
                				savesetting("selledgems",getsetting("selledgems","0")$_GET['level']);
              				}
           			} else {
              				output("`6Naloni`5 teilt dir mit, dass sie nicht mehr so viele Edelsteine hat und bittet dich später noch einmal wiederzukommen.`n`n");
           			}
        		}else{
            			output( "`6Naloni`5 zeigt dir den Stinkefinger, als du versuchst, ihr weniger zu zahlen als ihre Edelsteine momentan Wert sind.`n`n");
        		}
	}
	addnav("Zurück zum Dorf","village.php");
}else if($_GET['op']=="sell"){
	page_header("Zigeunerzelt");
	$maxout = $session['user']['level']getsetting("maxtransferout","25");
    	if ($session['user']['gems']<1){
        		output("`6Naloni`5 haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keinen Edelstein.`n`n");
	}else if ($session['user']['transferredtoday']>getsetting("transferreceive","3")){
		output("`5Du hast heute schon genug Geschäfte gemacht. `6Naloni`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
    	}else{
        		output("`6Naloni`5 nimmt deinen Edelstein und gibt dir dafür `^$scost Goldstücke`5.`n`n");
        		$session['user']['gold']+=$scost;
        		$session['user']['gems']-=1;
        		savesetting("selledgems",getsetting("selledgems",0)1);
		$session['user']['transferredtoday']+=1;
    	}
	addnav("Zigeunerzelt","gypsy.php");
	addnav("Zurück zum Dorf","village.php");
}elseif($_GET['op']=="sell5"){
	page_header("Zigeunerzelt");
    	if ($session['user']['gems']<5){
        		output("`6Naloni`5 haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keine 5 Edelsteine.`n`n");
	}else if ($session['user']['transferredtoday']>getsetting("transferreceive","3")){
		output("`5Du hast heute schon genug Geschäfte gemacht. `6Naloni`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
    	}else{
        		output("`6Naloni`5 nimmt deine 5 Edelsteine und gibt dir dafür `^$scost5 Goldstücke`5.`n`n");
        		$session['user']['gold']+=$scost5;
        		$session['user']['gems']-=5;
        		savesetting("selledgems",getsetting("selledgems",0)5);
		$session['user']['transferredtoday']+=1;
    	}
	addnav("Zigeunerzelt","gypsy.php");
	addnav("Zurück zum Dorf","village.php");
}else{
	
	page_header("Zigeunerzelt");
	output("`5Du betrittst das Zigeunerzelt hinter `#Lyrandis`5' Rüstungsladen, welches eine Unterhaltung mit den Verstorbenen verspricht. Im typischen Zigeunerstil sitzt eine alte Frau hinter
	einer irgendwie schmierigen Kristallkugel. Sie sagt dir, dass die Verstorbenen nur mit den Bezahlenden reden. Der Preis ist `^$cost`5 Gold.");
	output("`nDie Zigeunerin `6Naloni`5 gibt dir auch zu verstehen, dass sie mit Edelsteinen handelt.`nMomentan hat sie `#".getsetting("selledgems",0)."`5 Edelsteine auf Lager.");
	if (getsetting("selledgems",0)>=500) output(" Sie scheint aber kein Interesse an weiteren Edelsteinen zu haben. Oder sie hat einfach kein Gold mehr, um weitere Edelsteine zu kaufen.");
	addnav("Bezahle und rede mit den Toten","gypsy.php?op=pay");

	//addnav("Tarotkarten legen (1 Edelstein)","tarot.php");
	addnav("Edelsteine");
	if ($session['user']['level']<15){
		addnav("Kaufe 1 Edelstein (".$costs[1]." Gold)","gypsy.php?op=buy&level=1");
		addnav("Kaufe 2 Edelsteine (".$costs[2]." Gold)","gypsy.php?op=buy&level=2");
		addnav("Kaufe 3 Edelsteine (".$costs[3]." Gold)","gypsy.php?op=buy&level=3");
		addnav("Kaufe 4 Edelsteine (".$costs[4]." Gold)","gypsy.php?op=buy&level=4");
		addnav("Kaufe 5 Edelsteine (".$costs[5]." Gold)","gypsy.php?op=buy&level=5");
	}
	if (getsetting("selledgems","0")<500 && $session['user']['level']>1) addnav("Verkaufe 1 Edelstein für $scost Gold","gypsy.php?op=sell");
	if (getsetting("selledgems","0")<500 && $session['user']['level']>5) addnav("Verkaufe 5 Edelstein für $scost5 Gold","gypsy.php?op=sell5");
	addnav("Zurück");
	addnav("Zurück zum Dorf","village.php");
}
page_footer();
?>