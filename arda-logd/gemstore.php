<?
require_once "common.php" ;
//$time_start = TIMETUNER();
// Gemstore script, based on Ampera's German Script with Strider's Translation further modification.
// serious modification of vesa.php with variable gem prices, stock limited by what is sold and quite a bit more dialogue.
// gemshop.php v. 1.9      
//
// Brought to you by Ampera and LoneStrider  -- feel free to use, modify or whatever. Just have fun.
//
//
// To install, you need to add the following line to your configuration.php
//
// --  in configuration.php      search for
//          "LOGINTIMEOUT"=>"Seconds of inactivity before auto-logoff,int",
// --  right under that line add:
//    "Misc,title",
//    "selledgems"=>"Gems Vessa has to sell,int",
//
//  Now connect markt.php to your gemshop or where ever you'd like it. It is currently configured for the Village by default.

if ($session['user']['level']>14) {
    page_header("Catriona`s Edelsteinhandel");
    output("`n`nDu näherst dich dem Edelsteinhandel und siehst einige dunkle Gestalten. Einige von ihnen stoßen gegen deine Schulter und flüstern
    `%'Denkst du nicht es ist Zeit, den Drachen zu töten?'");
    addnav ("Marktplatz" ,"markt.php" );}
else{
page_header ("Catriona`s Edelsteinhandel");
//$gems =array( 1=>1,2,3,4,5);
$gems =array( 1=>1,5,10,50,100);
$costs =array( 1=>10000,50000,100000,500000,1000000);
$scosts =array( 1=>10000,50000,100000,500000,1000000);
/*$costs =array( 1=>3800 -2*getsetting ("selledgems" ,0),4600-4*getsetting("selledgems" ,0),5700-6*getsetting("selledgems" , 0),10800-8*getsetting("selledgems" ,0),13700-10*getsetting("selledgems" , 0));*/
/*if (getsetting("selledgems",0)<=2){$scost =2000 -getsetting ("selledgems" ,0); }
else{ $scost =1100 -getsetting ("selledgems" ,0); }*/

if ($_GET[op]=="buy"){
    if ($session[user][transferredtoday]>getsetting("transferreceive",3)){
        output("`5Du hast heute schon genug Geschäfte gemacht. `6Catriona`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
    }else if ($session[user][gems]>getsetting("selledgems",0)) {
        output("`6Catriona`5wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben.");
    } else {
if ($session[user][gold]>=$costs[$_GET[level]]){
if (getsetting("selledgems",0) >= $_GET[level]){
output ("`6Catriona`5 nimmt Deine `^".($costs[$_GET[level]])." gold `5 und gibt Dir `#".($gems[$_GET[level]])."`5 Edelstein".($gems[$_GET[level]]>=2?"e":"").".`n`n");
$session[user][gold]-=$costs[$_GET[level]];
$session[user][gems]+=$gems [$_GET[level]];
if (getsetting("selledgems",0) -$_GET[level]<1){
savesetting ("selledgems","0");
}else{
savesetting ("selledgems",getsetting("selledgems",0)-$gems[$_GET[level]]);
}
}else{
output ("`6Catriona`5 sieht dich mit ihren gemischten Augen an und schüttelt den Kopf. `#\"`3 Es tut mir leid ich habe keine Edelsteine mehr zum Verkaufen.`#\"`5`n`n`n" );
}
}else{
output ("`6Catriona`5 schaut dich böse an und meint, dass du nicht genügend Gold dabei hast. Sie schaut zu einigen ihrer schattenhaften Freunde im Raum und du fühlst dich plötzlich sehr unbehaglich und gehst.`n`n" );
}
addnav ("Edelstein-Börse","gemstore.php");
addnav ("Marktplatz" ,"markt.php" );
}
}elseif( $_GET[op]== "sell"){
page_header ("Catrionas Edelsteinhandel");
if ( $session[user][gems]<$gems[$_GET[level]]){
output ("`6Catriona`5 schreit `#\"`3 Du blutiger Anfänger!! Was willst Du!!!`#\"`5`n" );
output ("Die junge Frau beruhigt sich schnell und erinnert dich dann daran dass du keine Edelsteine hast, die du ihr verkaufen kannst.`n" );
}else{
output ("`6Catriona`5 nimmt deine ".($gems[$_GET[level]])." Edelsteine und gibt dir ".($scosts[$_GET[level]])."Gold.`n`n" );
$session[user][gold]+= $scosts[$_GET[level]];
$session[user][gems]-= $gems [$_GET[level]];
savesetting ("selledgems" ,getsetting ("selledgems",0)+ $gems[$_GET[level]]);

}
addnav ("Edelstein-Börse","gemstore.php");
addnav ("Marktplatz" ,"markt.php" );
}else{
checkday ();
page_header ("Catrionas Edelsteinhandel");
output("`c`bViele bunte Steinchen`b`c");
//output("`c<img src=images/armadale/steine.jpeg>`c",true);
output ("`v`nDu betrittst den kleinen Laden `MCatrionas`v, die hier ihre Edelsteine feilbietet. `MCatriona `vsitzt hinter ihrem großen Schreibtisch. Einige Kerzen flackern auf der Tischplatte, ihr Licht wird von den Edelsteinen und Juwelen, die auf dem 
Schreibtisch liegen widergespiegelt und zaubern groteske Lichtspiele auf das blasse Gesicht der jungen Edelsteinhändlerin, verleiht ihr somit ein geheimnisvolles Aussehen. Als sie dich eintreten hört, hebt `MCatriona `vihren Kopf, lächelt freundlich 
und deutet auf ein Schild, auf dem die aktuellen Edelsteinpreise stehen. Außerdem steht auf dem Schild, daß noch ".getsetting("selledgems",0)." Edelsteine zu haben sind.");
addnav ("Edelstein-Kaufen" );
if ( $session['user']['level']<14){
addnav ("Kaufe 1 Edelstein ($costs[1]Gold)" ,"gemstore.php?op=buy&level=1" );
addnav ("Kaufe 5 Edelsteine ($costs[2]Gold)" ,"gemstore.php?op=buy&level=2" );
addnav ("Kaufe 10 Edelsteine ($costs[3]Gold)" ,"gemstore.php?op=buy&level=3" );
addnav ("Kaufe 50 Edelsteine ($costs[4]Gold)" ,"gemstore.php?op=buy&level=4" );
addnav ("Kaufe 100 Edelsteine ($costs[5]Gold)" ,"gemstore.php?op=buy&level=5" );
}
addnav ("Edelstein-Verkaufen" );
//if ($session[user][level]>2 && getsetting("selledgems",0)<500) 
addnav ("1 Edelstein ($scosts[1] Gold)" ,"gemstore.php?op=sell&level=1" );
addnav ("5 Edelsteine ($scosts[2] Gold)" ,"gemstore.php?op=sell&level=2" );
addnav ("10 Edelsteine ($scosts[3] Gold)" ,"gemstore.php?op=sell&level=3" );
addnav ("50 Edelsteine ($scosts[4] Gold)" ,"gemstore.php?op=sell&level=4" );
addnav ("100 Edelsteine ($scosts[5] Gold)" ,"gemstore.php?op=sell&level=5" );
addnav ("Wege");

addnav ("Marktplatz" ,"markt.php" );
}
}
page_footer ();
?> 