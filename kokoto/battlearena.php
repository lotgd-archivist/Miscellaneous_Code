<?php
// 09092004
//Battle Arena - first release
//Created by Lonny Luberts of http://www.pqcomp.com/logd e-mail logd@pqcomp.com
//this way battlepoints do not reset after dragon kill
//translation by anpera
require_once "common.php";
checkday();
page_header("Kämpfer-Arena von Kokoto");
output("`c`b`&Kämpfer-Arena von Kokoto`0`b`c`n`n");
//checkevent();
if ($_GET['op']==''){
output('`3Die Arena ist überfüllt mit Zuschauern, der Lärm ist ohrenbetäubend.  Einige Krieger kämpfen in der Mitte der Arena um ihre Ehre und um die Platzierung. Du siehst eine Tür zu einem exklusiven Gemeinschaftsraum. Eine große Tafel hängt an einer Wand.`n');
$sql = "SELECT battlepoints,name FROM accounts WHERE battlepoints > 0  ORDER BY battlepoints DESC,name";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
if ($row['battlepoints'] > $topbattle and $row['battlepoints'] > 0){
$topbattle = $row['battlepoints'];
$plaque = $row['name'];
}
}
output('`PAuf dieser wird der Arena Champion angepriesen: ');
if ($plaque <> ''){
output("$plaque`7.`n"); 
}else{
output('Niemand.`n');
}
output('`#Auf der Anmeldetabelle sind alle Gladiatoren aufgelistet, gegen die du antreten kannst.`n<ul>',true);
if ($session['user']['battlepoints'] < 13 || $session['user']['dragonkills']<1) output("`2Cicero `6Level 9`n");
if ($session['user']['battlepoints'] > 12 && $session['user']['dragonkills'] >= 1) output('`@Vibius `^Level 10`n');
if ($session['user']['battlepoints'] >= 36 && $session['user']['dragonkills'] > 2) output('`2Quintus `6Level 11`n');
if ($session['user']['battlepoints'] >= 72 && $session['user']['dragonkills'] > 3) output('`@Cassius `^Level 12`n');
if ($session['user']['battlepoints'] >= 120 && $session['user']['dragonkills'] > 4) output('`2Lucius `6Level 13`n');
if ($session['user']['battlepoints'] >= 180 && $session['user']['dragonkills'] > 5) output('`@Aurelius `^Level 14`n');
if ($session['user']['battlepoints'] >= 252 && $session['user']['dragonkills'] > 7) output('`2Proximo `6Level 15`n');
if ($session['user']['battlepoints'] >= 336 && $session['user']['dragonkills'] > 9) output('`@Maximus `^Level 15`n');
output("</ul>`n`#Ein Kampf in der Arena wird dich einen Waldkampf kosten.`n",true);
output('`3Es ist sehr empfehlenswert, dass du dich nur in bester Verfassung einem Kampf stellst.`n`#Du musst eine Nutzungsgebühr bezahlen.`n');
if ($session['user']['gold'] < 1) output('Leider stellst du fest, dass deine Taschen leer sind.`n');
if ($session['user']['gold'] > 0 && $session['user']['gold'] < 50) output('Leider bemerkst du, dass du nicht genug Gold hast.`n');
if ($session['user']['gold'] > 49 && $session['user']['turns'] > 0) addnav('Zahle Eintritt (50 Gold)','battlearena.php?op=pay');
if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4) addnav('Gesellschaftsraum','battlearena.php?op=lounge');

addnav('Rangliste','hof.php?op=battlepoints&subop=most');
addnav('Zurück zum Dorf','village.php');
}
if ($_GET['op'] == 'lounge'){
output('`c`b`&Veterans Lounge`0`b`c`n`n');
addcommentary();
viewcommentary("battlearena","Angeben:",10,"prahlt:");
addnav('Zurück zur Arena','battlearena.php');
}

if ($_GET['op'] == 'pay'){
$session['user']['gold']-=50;
$session['user']['turns']-=1;
output('`cWähle deinen Gegner.`c');
addnav('Wähle deinen Gegner');
if ($session['user']['battlepoints'] < 13 || $session['user']['dragonkills']<1 || $session['user']['superuser']>=4)addnav('`2Cicero','battlearena.php?op=Cicero');
if ($session['user']['battlepoints'] > 12 && $session['user']['dragonkills'] >= 1) addnav('`@Vibius','battlearena.php?op=Vibius');
if ($session['user']['battlepoints'] >= 36 && $session['user']['dragonkills'] > 2) addnav('`2Quintus','battlearena.php?op=Quintus');
if ($session['user']['battlepoints'] >= 72 && $session['user']['dragonkills'] > 3) addnav('`@Cassius','battlearena.php?op=Cassius');
if ($session['user']['battlepoints'] >= 120 && $session['user']['dragonkills'] > 4) addnav('`2Lucius','battlearena.php?op=Lucius');
if ($session['user']['battlepoints'] >= 180 && $session['user']['dragonkills'] > 5) addnav('`@Aurelius','battlearena.php?op=Aurelius');
if ($session['user']['battlepoints'] >= 252 && $session['user']['dragonkills'] > 7) addnav('`2Proximo','battlearena.php?op=Proximo');
if ($session['user']['battlepoints'] >= 336 && $session['user']['dragonkills'] > 9) addnav('`@Maximus','battlearena.php?op=Maximus');
}
if ($_GET['op'] == 'win'){
if ($_GET['op2'] == 'Cicero'){
$winnings = e_rand(75,100);
$points=1;
$session['user']['reputation']+=(11$session['user']['level']);
}
if ($_GET['op2'] == 'Vibius'){
$points=2;
$winnings = e_rand(90,175);
$session['user']['reputation']+=(12$session['user']['level']);
}
if ($_GET['op2'] == 'Quintus'){
$points=3;
$winnings = e_rand(110,228);
$session['user']['reputation']+=(13$session['user']['level']);
}
if ($_GET['op2'] == 'Cassius'){
$points=4;
$winnings = e_rand(150,300);
$session['user']['reputation']+=(14$session['user']['level']);
}
if ($_GET['op2'] == 'Lucius'){
$points=5;
$winnings = e_rand(190,409);
$session['user']['reputation']+=(15$session['user']['level']);
}
if ($_GET['op2'] == 'Aurelius'){
$points=6;
$winnings = e_rand(273,580);
$session['user']['reputation']+=(16$session['user']['level']);
}
if ($_GET['op2'] == 'Proximo'){
$points=7;
$winnings = e_rand(333,680);
$session['user']['reputation']+=(16$session['user']['level']);
}
if ($_GET['op2'] == 'Maximus'){
$points=8;
$winnings = e_rand(399,777);
$session['user']['reputation']+=(17$session['user']['level']);
}
$gladiator=$_GET['op2'];



output("Gratulation! Du hast $gladiator geschlagen!  Du bekommst $points Kampfpunkte!`n");
output("Du gewinnst $winnings Gold!`n");
$session['user']['gold']+=$winnings;
$session['user']['battlepoints']+=$points;



######################################## // Guilds Code, Dasher
            populate_guilds();
            $guildName=$session['guilds'][$session['user']['guildID']]['Name'];
            if ($guildName=="") $guildName="ohne";
            $guildDisplayRank=FindRankForPerson($u['guildID'],$u['acctid'],false);
            if ($guildDisplayRank=="Bugger" or $guildDisplayRank==""){
                   if ($session['user']['guildrank']<>0){
                          $sql="select DisplayTitle from lotbd_guildranks where RankID=".$session['user']['guildrank'];
                          $result=db_query($sql);
                          $row = db_fetch_assoc($result);
                          $guildDisplayRank=$row['DisplayTitle'];
                  //$guildDisplayRank="None";
                   }else{
                       $guildDisplayRank="ohne";
                }
            }
            // End Guilds Code 

##############################################
// Guilds/Clans Code
if ($session['user']['guildID']!=0) {
                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                if (isset($MyGuild)) {
                    if ($badguy['guildID']!=0) {
                        $TheirGuild=$session['guilds'][$badguy['guildID']];
                    } else {
                        // They are not a member of a guild
                    }
                    $GuildFee = round((($MyGuild['PercentOfFightsEarned']['PvP']100)  $winnings),0);
                    if ($GuildFee<=0) $GuildFee=(($session['user']['level']10)  ($MyGuild['PercentOfFightsEarned']['PvP']100)1);
                    output("`&`nDeine Gilde fordert ihren Anteil. Du zahlst `^".$GuildFee." Gold `&Tribut.`n`n");
                        $session['user']['gold']-=$GuildFee;
                    $MyGuild['gold']+=$GuildFee;
                    update_guild_info($MyGuild);
                    addnews("`8Im Namen seiner Gilde ".$guildName."`8 besiegte `5".$session['user']['name']."`8 seinen
                    Gegner ".$gladiator."`8 in der Arena!");
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['guildID'],true);
                    debuglog("MyGuild isn't set: ".$debug);
                }
            } else {
            addnews("`5".$session['user']['name']."`8 hat $gladiator`8 in der Arena besiegt!");
                // They don't belong to a guild
            } //
            
            
            
if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) output('`# Die Arena-Ärzte versorgen deine Wunden.');
if ($session['user']['hitpoints']==$session['user']['maxhitpoints']){
output('`4Ausgezeichneter Kampf! Du bekommst zusätzlich zum Gewinn dein Eintrittsgeld zurück!`n');
$session['user']['gold']+=50;
}
if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']+=round($session['user']['maxhitpoints'].5);
if ($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
addnav('Weiter','battlearena.php');
}
if ($_GET['op'] == 'loose'){
$session['user']['hitpoints']=$session['user']['maxhitpoints'];
$who = $_GET['op2'];
if ($who == 'Cicero') $session['user']['battlepoints']-=1;
if ($who == 'Vibius') $session['user']['battlepoints']-=1;
if ($who == 'Quintus') $session['user']['battlepoints']-=2;
if ($who == 'Cassius') $session['user']['battlepoints']-=2;
if ($who == 'Lucius') $session['user']['battlepoints']-=3;
if ($who == 'Aurelius') $session['user']['battlepoints']-=3;
if ($who == 'Proximo') $session['user']['battlepoints']-=4;
if ($who == 'Maximus') $session['user']['battlepoints']-=4;
output("Du hast gegen $who verloren.`n");
addnews($session['user']['name']." hat gegen $who in der Arena verloren."); 
output('`#Die Heiler der Arena versorgen deine Wunden.`n');
addnav('Weiter','battlearena.php');
if ($session['user']['battlepoints']<0) $session['user']['battlepoints']=0;
}
if ($_GET['op'] == 'Cicero'){
$badguy = array("creaturename"=>"`@Cicero`0"
,"creaturelevel"=>8
,"creatureweapon"=>"Iaculum"
,"creatureattack"=>65
,"creaturedefense"=>65
,"creaturehealth"=>120
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,50);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
    }
if ($_GET['op'] == 'Vibius'){
$badguy = array("creaturename"=>"`@Vibius`0"
,"creaturelevel"=>9
,"creatureweapon"=>"Nagelkeule"
,"creatureattack"=>70
,"creaturedefense"=>70
,"creaturehealth"=>140
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,60);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
    } 
if ($_GET['op'] == 'Quintus'){
$badguy = array("creaturename"=>"`@Quintus`0"
,"creaturelevel"=>10
,"creatureweapon"=>"Sichel"
,"creatureattack"=>75
,"creaturedefense"=>75
,"creaturehealth"=>160
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,70);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
}

if ($_GET['op'] == 'Cassius'){
$badguy = array("creaturename"=>"`@Cassius`0"
,"creaturelevel"=>11
,"creatureweapon"=>"Schlagstock"
,"creatureattack"=>80
,"creaturedefense"=>80
,"creaturehealth"=>180
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,80);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
}
if ($_GET['op'] == 'Lucius'){
$badguy = array("creaturename"=>"`@Lucius`0"
,"creaturelevel"=>12
,"creatureweapon"=>"Lanze"
,"creatureattack"=>85
,"creaturedefense"=>85
,"creaturehealth"=>200
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,90);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
}
if ($_GET['op'] == 'Aurelius'){
$badguy = array("creaturename"=>"`@Aurelius`0"
,"creaturelevel"=>13
,"creatureweapon"=>"Hasta"
,"creatureattack"=>90
,"creaturedefense"=>90
,"creaturehealth"=>220
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,100);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
} 
if ($_GET['op'] == 'Proximo'){
$badguy = array("creaturename"=>"`@Proximo`0"
,"creaturelevel"=>14
,"creatureweapon"=>"Harpune"
,"creatureattack"=>95
,"creaturedefense"=>95
,"creaturehealth"=>240
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=5;
$badguy['creaturehealth']+=e_rand(1,110);
$badguy['creaturedefense']+=5;
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
$_GET['op']='prefight';
}

if ($_GET['op'] == 'Maximus'){
$badguy = array("creaturename"=>"`@Maximus`0"
,"creaturelevel"=>14
,"creatureweapon"=>"Gladiatorenschwert"
,"creatureattack"=>125
,"creaturedefense"=>125
,"creaturehealth"=>340
,"creaturegold"=>0
,"diddamage"=>0);

$badguy['creaturelevel']+=1;
$badguy['creatureattack']+=e_rand(5,50);
$badguy['creaturehealth']+=e_rand(1,160);
$badguy['creaturedefense']+=e_rand(5,50);
$badguy['creaturegold']=0;
$session['user']['badguy']=createstring($badguy);
if ($badguy['creatureattack'] < $session['user']['attack']) $badguy['creatureattack'] = ($session['user']['attack']  e_rand(5,15));
if ($badguy['creaturehealth'] < $session['user']['hitpoints']) $badguy['creaturehealth'] = ($session['user']['hitpoints']  e_rand(5,150));
$_GET['op']='prefight';
} 
if ($_GET['op'] == 'prefight'){ 
output('`#Du wirst in die Arena geführt und buchstäblich auf den Kampfplatz geworfen.`n`#Die Menge jubelt vor Begeisterung auf, als du ziemlich unsanft vor den Füssen deines Gegners landest.`n');
output($badguy['creaturename']." `#stürzt sich wie ein Wirbelwind auf dich und der Kampf beginnt.`n");
$session['user']['specialmisc']=$badguy['creaturehealth']; 
$_GET['op']='fight';
}      
if ($_GET['op'] == 'fight'){
$battle=true;
}
if ($battle){
include_once("battle.php");

if ($victory){
output("`n`7Du hast `^".$badguy['creaturename']." besiegt.`n");
output("`#Die Menge gröhlt: \"".$session['user']['name']."`#, ".$session['user']['name']."`#\".`n");
output("`6Moderator: ".$session['user']['name']."`6 traf mit einem vernichtenden Schlag!");
if ($badguy['creaturename']=='`@Cicero`0') addnav('Weiter','battlearena.php?op=win&op2=Cicero');
if ($badguy['creaturename']=='`@Vibius`0') addnav('Weiter','battlearena.php?op=win&op2=Vibius');
if ($badguy['creaturename']=='`@Quintus`0') addnav('Weiter','battlearena.php?op=win&op2=Quintus');
if ($badguy['creaturename']=='`@Cassius`0') addnav('Weiter','battlearena.php?op=win&op2=Cassius');
if ($badguy['creaturename']=='`@Lucius`0') addnav('Weiter','battlearena.php?op=win&op2=Lucius');
if ($badguy['creaturename']=='`@Aurelius`0') addnav('Weiter','battlearena.php?op=win&op2=Aurelius');
if ($badguy['creaturename']=='`@Proximo`0') addnav('Weiter','battlearena.php?op=win&op2=Proximo');
if ($badguy['creaturename']=='`@Maximus`0') addnav('Weiter','battlearena.php?op=win&op2=Maximus');
output('`n`n`3Deine Gesundheit: `n');
output(grafbar($session['user']['maxhitpoints'],$session['user']['hitpoints'],"50%",15),true);

output("`n`n".$badguy['creaturename']."`3's Gesundheit: `n");
output(grafbar($session['user']['specialmisc'],0,"50%",15),true);
output('`n`n');
$badguy=array();
$session['user']['badguy']='';

}elseif ($defeat){
output("`n`7Du wurdest von `^".$badguy['creaturename']." `7geschlagen.`n");
output("`#Die Menge gröhlt: \"".$badguy['creaturename']." `#".$badguy['creaturename']."`#\".`n");
output("`6Moderator: ".$badguy['creaturename']."`6 macht den letzten Schlag!");
$session['user']['hitpoints']=1;
$who=$badguy['creaturename'];
if ($badguy['creaturename']=='`@Cicero`0') addnav('Weiter','battlearena.php?op=loose&op2=Cicero');
if ($badguy['creaturename']=='`@Vibius`0') addnav('Weiter','battlearena.php?op=loose&op2=Vibius');
if ($badguy['creaturename']=='`@Quintus`0') addnav('Weiter','battlearena.php?op=loose&op2=Quintus');
if ($badguy['creaturename']=='`@Cassius`0') addnav('Weiter','battlearena.php?op=loose&op2=Cassius');
if ($badguy['creaturename']=='`@Lucius`0') addnav('Weiter','battlearena.php?op=loose&op2=Lucius');
if ($badguy['creaturename']=='`@Aurelius`0') addnav('Weiter','battlearena.php?op=loose&op2=Aurelius');
if ($badguy['creaturename']=='`@Proximo`0') addnav('Weiter','battlearena.php?op=loose&op2=Proximo');
if ($badguy['creaturename']=='`@Maximus`0') addnav('Weiter','battlearena.php?op=loose&op2=Maximus');
output('`n`n`3Deine Gesundheit: `n');
output(grafbar($session['user']['maxhitpoints'],0,"50%",15),true);
output("`n`n`n".$badguy['creaturename']."`3's Gesundheit: `n");
output(grafbar($session['user']['specialmisc'],$badguy['creaturehealth'],"50%",15),true);

}else{
fightnav(true,false);

switch(mt_rand(1,11)){
case 1:
output("`n`b".$badguy['creaturename']."`4 versucht einen billigen Trick.`b`n");
break;
case 4:
output("`n`b".$badguy['creaturename']."`4 knurrt dich an.`b`n");
break;
case 5:
output("`n`b".$badguy['creaturename']."`4 versucht, dir ein Ohr abzubeissen!`b`n");
break;
case 6:
output("`n`b".$badguy['creaturename']."`4 schimpft dich einen Feigling!`b`n");
break;
case 8:
output("`n`b".$badguy['creaturename']."`4 behauptet, deine Oma kämpft besser!`b`n");
break;
case 9:
output("`n`b".$badguy['creaturename']."`4 sagt, du kämpfst wie ein Kind!`b`n");
break;
case 10:
output("`n`b".$badguy['creaturename']."`4 sagt, dass du häslich bist und dass dir deine Mami komische Sachen zum Anziehen gibt!`b`n");
break;
default:
break;
}
switch(mt_rand(1,15)){
case 1:
output('`#Die Menge tobt vor Begeisterung!`n');
break;
case 2:
output("`#Die Menge gröhlt: \"".$session['user']['name']." `#".$session['user']['name']."`#\".`n");
break;
case 3:
output("`#Die Menge gröhlt: \"".$badguy['creaturename']." `#".$badguy['creaturename']."`#\".`n");
break;
case 4:
output('`#Die Menge wird still.`n');
break;
case 5:
output('`#Die Menge wird nervös!`n');
break;
case 6:
output('`#Die Menge macht eine Welle.`n');
break;
case 7:
output('`#Die Spannung steigt.`n');
break;
case 8:
output("`#Die Menge brüllt: \"Nieder mit ".$badguy['creaturename']." `#\".`n");
break;
case 9:
output("`#Die Menge brüllt: \"Nieder mit ".$session['user']['name']." `#\".`n");
break;
case 10:
output('`#Die Menge kommt in Bewegung!`n Einige Zuschauer fallen in die Arena, nur um anschließend von einer Wache wieder weggetragen zu werden.`n');
break;
case 11:
output('`#Die Menge ruft:  "Mach ihn fertig! Mach ihn fertig!".`n');
break;
case 12:
output('`#Die Menge schreit was das Zeug hält!`n');
break;
case 13:
output('`#Die Menge tobt.`n');
break;
case 14:
output('`#Ein dicker, fetter Kerl bemalt sich mit roten Kringeln und führt einen Tanz auf.`n');
break;
case 15:
output('`#Ein Fan rennt in die Arena und im Eifer des Gefechts streifst du ihn und er fliegt in eine Ecke der Arena.`n');
break;
}
output('`n`3Deine Gesundheit: `n');
output(grafbar($session['user']['maxhitpoints'],$session['user']['hitpoints'],"50%",15),true);
output("`n`n".$badguy['creaturename']."`3's Gesundheit: `n");
output(grafbar($session['user']['specialmisc'],$badguy['creaturehealth'],"50%",15),true);
}
}else{
}
page_footer();
?>