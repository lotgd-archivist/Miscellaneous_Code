<?php
/*
Six's Super Six by sixf00t4
for http://www.sixf00t4.com/dragon
modded from:

Eric's lotto v. 1.0
Based upon the original LORD IGM Seth's Scratch-offs By Joseph Masters
Author: bwatford
Board: http://www.ftpdreams.com/lotgd

SQL CODE:

ALTER TABLE `accounts` ADD `scratchmatches` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch1` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch2` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch3` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch4` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch5` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratch6` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin1` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin2` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin3` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin5` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin6` INT( 11 ) DEFAULT '0' NOT NULL ,
ADD `scratchwin4` INT( 11 ) DEFAULT '0' NOT NULL ;
*/

require_once "common.php";
checkday();
$jack=stripslashes(getsetting("jackpot",""));

if ($HTTP_GET_VARS[op]==""){
page_header("Casino Perdición");
        $session[user][scratchmatches]=0;
    output("`n`c`^<h4>Casino Perdición</h4>`0`c",true);
    addnav("Spielen","supersix.php?op=yes");
    addnav("oder");
    addnav("Anderer Tisch ", "zylyma_casino.php");
    addnav("Zurück nach Zylyma","zylyma.php");
    output("Du gehst in Richtung eines Tisches der ziemlich interessant ausschaut.".($session[user][sex]?"Ein Mann":"Eine Frau")."
    hat 12 Würfel und einen Berg Goldstücke vor sich liegen. Hier scheint es doch um größere Beträge zu gehen.`n`n");

}
if ($HTTP_GET_VARS[op]=="yes"){
page_header("Casino Perdición");
output("`n`c`^<h4>Casino Perdición</h4>`0`c",true);
if($session[user][gold]<100){
        output("`n`7".($session[user][sex]?"Der Mann":"Die Frau")." schaut dich an und macht dich darauf aufmerksam,
        daß Du nicht genug Gold zum spielen hast und Kredit gibts hier wohl nicht!");
    addnav("Anderer Tisch ", "zylyma_casino.php");
    addnav("Zurück nach Zylyma","zylyma.php");
    }else{
addnav("Vorlegen","supersix.php?op=scratch");
output("`@".($session[user][sex]?"Der Mann":"Die Frau")." erklärt dir die Regeln. `2\"Jedes Spiel kostet 100 Gold.
Ich werde 6 mal würfeln und die Reihe vorlegen. Danach darfst Du 6 mal würfeln. Mit jeder Übereinstimmung erhöht sich dein
Gewinn`@.\"`n`n");
output("`@Lust es mal zu probieren?`n`n");
$session[user][gold]-=100;
$session[necron][jackpot]=$jack+=10;
savesetting("jackpot",addslashes($session[necron][jackpot]));

}
}

if ($HTTP_GET_VARS[op]=="scratch"){
page_header("Casino Perdición");
output("`n`c`^<h4>Casino Perdición</h4>`0`c",true);
addnav("Würfeln","supersix.php?op=match");
output("`@".($session[user][sex]?"Der Mann":"Die Frau")." beginnt und würfelt:`@`n`n`n");
$s1=$session['user']['scratch1']=e_rand(1,6);
$s2=$session['user']['scratch2']=e_rand(1,6);
$s3=$session['user']['scratch3']=e_rand(1,6);
$s4=$session['user']['scratch4']=e_rand(1,6);
$s5=$session['user']['scratch5']=e_rand(1,6);
$s6=$session['user']['scratch6']=e_rand(1,6);

output("`0<h2> $s1 - $s2 - $s3 - $s4 - $s5 - $s6</h2>`n`n`n",true);
output("`2\"Jetzt bist du dran!\"`n");
}


if ($HTTP_GET_VARS[op]=="match"){
page_header("Casino ");
output("`n`c`^<h4>Casino Perdición</h4>`0`c",true);
$s1=$session['user']['scratch1'];
$s2=$session['user']['scratch2'];
$s3=$session['user']['scratch3'];
$s4=$session['user']['scratch4'];
$s5=$session['user']['scratch5'];
$s6=$session['user']['scratch6'];

output("`n`6".($session[user][sex]?"Geber":"Geberin")." hatte:");
output("`0<h2> $s1 - $s2 - $s3 - $s4 - $s5 - $s6</h2>`n`n`n",true);

$sw1=$session[user][scratchwin1]= e_rand(1,6);
$sw2=$session[user][scratchwin2]= e_rand(1,6);
$sw3=$session[user][scratchwin3]= e_rand(1,6);
$sw4=$session[user][scratchwin4]= e_rand(1,6);
$sw5=$session[user][scratchwin5]= e_rand(1,6);
$sw6=$session[user][scratchwin6]= e_rand(1,6);

output("`@Dein Wurf:");
output("`0<h2> $sw1 - $sw2 - $sw3 - $sw4 - $sw5 - $sw6</h2>`",true);

if($session[user][scratch1]==$session[user][scratchwin1]){
output("`^`bDie erste Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratch2]==$session[user][scratchwin2]){
output("`^`bDie zweite Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratch3]==$session[user][scratchwin3]){
output("`^`bDie dritte Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratch4]==$session[user][scratchwin4]){
output("`^`bDie vierte Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratch5]==$session[user][scratchwin5]){
output("`^`bDie fünfte Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratch6]==$session[user][scratchwin6]){
output("`^`bDie sechste Nummer ist identisch!`^`b`n`n");
$session[user][scratchmatches]+=1;
}
if($session[user][scratchmatches]==0){output("`^`bKeine deiner Nummern war richtig!`^`b`n`n");
    addnav("Nochmal spielen","supersix.php");
    addnav("oder");
    addnav("Anderer Tisch ", "zylyma_casino.php");
    addnav("Zurück nach Zylyma","zylyma.php");
}
if($session[user][scratchmatches]>0){
addnav("Nimm Gewinn!","supersix.php?op=win");}
}

if ($HTTP_GET_VARS[op]=="win"){
page_header("Casino Perdición");
output("`n`c`^<h4>Casino Perdición</h4>`0`c",true);
    addnav("Nochmal spielen","supersix.php");
    addnav("oder");
    addnav("Anderer Tisch ", "zylyma_casino.php");
    addnav("Zurück nach Zylyma","zylyma.php");
if($session[user][scratchmatches]==0){
output("`@`cDu hast `bVERLOREN`b!  Wütend schiebst du die Würfel zur Seite.`@`c`n`n");
output("`^`cSorry You Didn't Win!`^`c");
    }else{
if($session[user][scratchmatches]==1){
output("`^`cDu hast 1 Identische! Du gewinnst 50 Gold!`^`c");
$session[user][gold]+=50;
    }else{
if($session[user][scratchmatches]==2){
output("`^`cDu hast 2 Identische! Du gewinnst 100 Gold!`^`c");
$session[user][gold]+=100;
    }else{
if($session[user][scratchmatches]==3){
output("`^`cDu hast 3 Identische! Du gewinnst 300 Gold!`^`c");
$session[user][gold]+=300;
    }else{
if($session[user][scratchmatches]==4){
output("`^`cDu hast 4 Identische! Du gewinnst 500 Gold!`^`c");
$session[user][gold]+=500;
    }else{
if($session[user][scratchmatches]==5){
output("`^`cDu hast 5 Identische! Du gewinnst 1000 Gold!`^`c");
$session[user][gold]+=1000;
    }else{
if($session[user][scratchmatches]==6){
output("`^`cSUPER 6 JACKPOT! Du gewinnst 5000 Gold!`^`c");
$session[user][gold]+=5000;
}
}
}
}
}
}
}
}

page_footer();
?>