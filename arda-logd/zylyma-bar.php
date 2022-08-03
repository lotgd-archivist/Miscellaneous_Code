<? 
/***************************************
Strand-Bar
Written by Gimmick for firedragonfly.de
Thanks to Diego for the Drinks and Ideas
23.05.2005
****************************************/
require_once "common.php"; 
checkday();
if ($session[user][drunkenness]>20) {
    page_header("Strand-Bar");
    output("`n`n`6Du kommst in die Kneipe um dich zu betrinken, sofort siehst du es ist sowie Zuhause, alles ist Verraucht und es stinkt nach Hundepisse.");
    output(" `nAber plötzlich stellt sich ein Zwerg vor dich und meint mit Zylymischen Akzent: `n`%Denkste due nciht du habe genug gehabt, bitte verlasse mein Lokal è !");
    addnav("Zurück nach Zylyma" ,"zylyma.php");
    }

else{

addnav("Drinks"); 
addnav("(A) Bacardi - 150 Gold","zylyma-bar.php?op=bc"); 
addnav("(S)  Smirnoff- 1 Edelstein","zylyma-bar.php?op=swill"); 
addnav("(G) Grappa - 2 Edelsteine","zylyma-bar.php?op=gra"); 
addnav("Verlassen"); 
addnav("Zurück nach Zylyma","zylyma.php");
page_header("Strand-Bar");  
if ($HTTP_GET_VARS[op]==""){
output("`c<font size='+1'>`6Strand-Bar</font>`c`n",true);
output("`n`n`6Du kommst in die Kneipe um dich zu betrinken, sofort siehst du es ist sowie Zuhause, alles ist Verraucht und es stinkt nach Hundepisse.");
output ("Du siehst einen gut gekleideten Barkeeper der so gar nicht in das schäbige Bild der Kneiep passt.`n Du siehst ebenfalls ein paar kleine Drinks zur Auswahl");

}else if ($_GET[op]=="bc"){ 
if ($session[user][gold] > 149){ 
     $session[bufflist][101] = array("name"=>"`4Bacardi-Rausch","rounds"=>10,"wearoff"=>"Dein Rausch lässt nach.","atkmod"=>1.2,"roundmsg"=>"Du bist ziemlich angetrunken.","activate"=>"offense"); 
     $session[user][gold]-=150; 
     output("`n`n`6TDer Barkeeper gibt dir einen kühlen Barcadi und du lehnst dich entspannt nach hinten.`n"); 
     output("Du spürst plötzlich das deine Muskeln größer sind."); 
     $session[user][drunkenness]+=25;
     debuglog("gave 100 gold to barkeeper in tavern for barcadi");
 } else { 
             output("`n`n`4Du hast nicht so viel Geld!"); 
} 
}else if ($_GET[op]=="swill"){ 
if ($session[user][gems] > 0){ 
     $session[bufflist][101] = array("name"=>"`^Smirnhoff","rounds"=>15,"wearoff"=>"Dein Rausch lässt nach.","defmod"=>1.5,"roundmsg"=>"Du bist besoffen.","activate"=>"offense"); 
     $session[user][gems]--; 
     output(" `n`n`6Der Barkeeper öffnet eine Flasche und gießt dir Smirnhoff ein.`n");  
     $session[user][drunkenness]+=45;
     debuglog("gave 1 gem to dwarf tavern for smirn");
 } else { 
             output("`n`n`4Du kannst dir keinen  leisten- hol dir Edelsteine!");  
}
}else if ($_GET[op]=="gra"){ 
if ($session[user][gems] > 1){ 
     $buff = array("name"=>"`^Grappe","rounds"=>25,"wearoff"=>"`!Dein Grappa-Rausch verfliegt und du fühlst dich schlecht", "defmod"=>1.3,"atkmod"=>1.4,"roundmsg"=>"You feel good!","activate"=>"defense");
     $session[bufflist][magicweak] = $buff; 
     $session[user][gems]-=2;  
     output("`n`n`6DU kippst dir einen Grappa runter und fühlst dich echt gut.`n"); 
     $session[user][drunkenness]+=65;
     debuglog("gave 2 gems to dwarf tavern for grappa");
 } else { 
             output("`n`n`4Du kannst dir den Grappa nicht leisten hol dir Edeslteine!");  
}
}
}
page_footer();
?>