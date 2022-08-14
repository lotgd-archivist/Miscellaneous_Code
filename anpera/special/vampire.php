
ï»¿<?php



// 20060626



/*************************

Vampire's Lair

Special Event/Add-on

for LoGD

by Mike Counts (genmac)

- Dec. 2003



Install:



-Special event: copy vampire.php into /special directory.



Add-on: copy vampire.php into main LoGD directory, add

link from village.php or wherever you wish.



***



modifications and translation by anpera

special event ONLY!!!



This event can regulate the max hp a player can have to prevent

powergamers from becoming overpowered



in configuration.php somewhere after:

$setup = array(

add:

    "limithp"=>"max maxhitpoints a character can keep (Level*12+HPfromDP+x*DK (0=no limit)),int",

*************************/



if (!isset($session)) exit();

$session['user']['specialinc']="vampire.php";



reset($session['user']['dragonpoints']);

$dkhp=0;

while(list($key,$val)=each($session['user']['dragonpoints'])){

    if ($val=="hp") $dkhp++;

}

$maxhp=getsetting("limithp",0)*$session['user']['dragonkills']+12*$session['user']['level']+5*$dkhp;

$minhp=10*$session['user']['level']+5*$dkhp;



$lifecost = 5;

$gemgain = round($lifecost/2);

$goldgain = $lifecost*100;



if($_GET['op']=="continue"){

    output("`^`c`bDas Lager des Vampirs`b`c");

    output("`n`n`7Ein bÃ¶sartiges Wesen manifestiert sich vor dir. Du erzitterst aus Furcht vor dieser uralten Macht, die jetzt zu dir spricht: ");

    output("\"`\$Sterblicher, ich spÃ¼re viel Lebenskraft in dir. Da ich alt werde, schwindet mein Verlangen zu jagen. Im Austausch ");

    output("fÃ¼r ein kleines bisschen deiner permanenten Lebenskraft gewÃ¤hre ich dir KrÃ¤fte ausserhalb deiner Vorstellungskraft.`7\"");

    output(" Erst jetzt erkennst du, dass du einem Vampir gegenÃ¼berstehst, der auf deine Entscheidung wartet.");

    if($session['user']['maxhitpoints']>$lifecost){

        addnav("Biete $lifecost Lebenspunkte fÃ¼r Angriff","forest.php?op=str");

        addnav("Biete $lifecost Lebenspunkte fÃ¼r Verteidigung","forest.php?op=def");

        addnav("Biete $lifecost Lebenspunkte fÃ¼r Reichtum","forest.php?op=wealth");

    } else{

        addnav("Nicht genug Lebenskraft");

    }

    addnav("FlÃ¼chte in Furcht","forest.php?op=leave");

}else if ($_GET['op']=="leave"){

    if (getsetting("limithp",0)>0 && $session['user']['maxhitpoints']>$maxhp){

        $losthp=$session['user']['maxhitpoints']-$maxhp;

        $exp=$losthp*10;

        $session['user']['maxhitpoints']=$maxhp;

        if ($session['user']['hitpoints']>$maxhp) $session['user']['hitpoints']=$maxhp;

        $session['user']['experience']+=$exp;

        output("Ausgehungert und vom Geruch deiner enormen Lebenskraft fast wahnsinnig Ã¼berwÃ¤ltigt dich ein Vampir auf deiner Flucht und saugt dich aus.");

        output(" Als er endlich satt ist, verschwindet er so lautlos und schnell wie er kam im Wald.`n`nDu hast `\$$losthp`7 Lebenspunkte `bpermanent`b verloren.");

        output("`nDu hast deine Lektion gelernt und bekommst `^$exp`7 Erfahrungspunkte.");

        if ($session['user']['turns']>0){

            output("`nDu fÃ¼hlst dich schlapp und verlierst einen Waldkampf.");

            $session['user']['turns']--;

        }

        addnews("`%".$session['user']['name']."`7 hatte im Wald eine folgenschwere Begegnung mit einem Vampir.");

    }else{

        output("`n`7Du verlÃ¤sst diesen verfluchten Ort so schnell du kannst.");

    }

    $session['user']['specialinc']="";

}else if($_GET['op']=="str" || $_GET['op']=="def" || $_GET['op']=="wealth"){

    output("`^`c`bDas Lager des Vampirs`b`c");

    if (($session['user']['maxhitpoints']-$lifecost)<$minhp){

        output("`n`7Der Vampir schaut dich an und meint schliesslich, dass deine Lebenskraft nicht ausreicht um ihn zu sÃ¤ttigen. Er lÃ¤sst dich ");

        output("unangetastet und ohne Belohnung ziehen.");

    }else{

        $session['user']['maxhitpoints'] -= $lifecost;

        if($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];

        output("`n`n`7Du erschauderst, als der Vampir seine ZÃ¤hne in deinem Hals versenkt. Du fÃ¼hlst deine Lebenskraft durch die Wunde ");

        output("in den Vampir fliessen. Im Gegenzug dafÃ¼r spricht der Vampir einen fluchÃ¤hnlichen Zauber Ã¼ber dich.`n`n`@");

        if($_GET['op']=="str"){

            $session['user']['attack']++;

            output("Dein Angriffwert erhÃ¶ht sich vorÃ¼bergehend um `^1`@ und du verlierst `\$$lifecost `@permanente Lebenspunkte.");

        }else if($_GET['op']=="def"){

            $session['user']['defence']++;

            output("Deine Verteidigung erhÃ¶ht sich vorÃ¼bergehend um `^1`@ und du verlierst`\$$lifecost `@permanente Lebenspunkte.");

        }else if($_GET['op']=="wealth"){

            $session['user']['gold'] += $goldgain;

            $session['user']['gems'] += $gemgain;

            output("FÃ¼r deine geopferten `\$$lifecost`@ permanetnen Lebenspunkte gibt dir der Vampir `^$goldgain `@Gold und `#$gemgain `@Edelsteine.");

        }

    }

    $session['user']['specialinc']="";

} else {

    output("`^`c`bEin dunkler Weg`b`c");

    output("`n`n`7Du stehst vor einem verschlungenen Pfad. Ein dunkler Nebel umgibt dich und du fÃ¼hlst ein kaltes ");

    output("Grausen in der Luft. Wagst du es, dich dem zu stellen, was vor dir liegt?");

    addnav("Gehe tapfer weiter","forest.php?op=continue");

    addnav("FlÃ¼chte in Furcht","forest.php?op=leave");

    if (getsetting("limithp",0)>0 && $session['user']['charm']>250){

        $session['user']['charm']=250;

        output("`n`nDu hast du das unangenehme, kalte GefÃ¼hl, als ob dir etwas unwiederbringlich genommen wurde.");

    }



}

?>

