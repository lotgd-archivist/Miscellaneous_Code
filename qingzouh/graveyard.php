
<?php

// 15082004

require_once "common.php";

if ($session['user']['alive']) redirect("village.php");

page_header("Der Friedhof");

$session['bufflist']=array();
$session['user']['drunkenness'] = 0;
$max = $session['user']['level'] * 5 + 50;
$favortoheal = round(10 * ($max-$session['user']['soulpoints'])/$max);

if ($_GET['op']=="search"){
    if ($session['user']['gravefights']<=0){
        output("`\$`bDeine Seele kann keine weiteren Qualen in diesem Nachleben mehr ertragen.`b`0");
        $_GET['op']="";
    }else{
        $session['user']['gravefights']--;
          $battle=true;
          $sql = "SELECT * FROM creatures WHERE location=1 ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $badguy = db_fetch_assoc($result);
        $level = $session['user']['level'];
        $shift = 0;
        if ($level < 5) $shift = -1;
        $badguy['creatureattack'] = 9 + $shift + (int)(($level-1) * 1.5);
        // Make graveyard creatures easier.
        $badguy['creaturedefense'] = (int)((9 + $shift + (($level-1) * 1.5)) * .7);
        $badguy['creaturehealth'] = $level * 5 + 50;
        $badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
        $badguy['creaturelevel'] = $level;
        //output("`#DEBUG: Creature level: {$badguy['creaturelevel']}`n");
        //output("`#DEBUG: Creature attack: {$badguy['creatureattack']}`n");
        //output("`#DEBUG: Creature defense: {$badguy['creaturedefense']}`n");
        //output("`#DEBUG: Creature health: {$badguy['creaturehealth']}`n");
        //output("`#DEBUG: Creature exp: {$badguy['creatureexp']}`n");
        $session['user']['badguy']=createstring($badguy);
    }
}
if ($_GET[op]=="fight" || $_GET[op]=="run"){
    if ($_GET['op']=="run"){
        if (e_rand(0,2)==1) {
            output("`\$Ramius`) verflucht dich für deine Feigheit.`n`n");
            $favor = 5 + e_rand(0, $session['user']['level']);
            if ($favor > $session['user']['deathpower'])
                $favor = $session['user']['deathpower'];
            if ($favor > 0) {
                output("`)Du hast `^$favor`) Gefallen bei `\$Ramius VERLOREN`).");
                $session['user']['deathpower']-=$favor;
            }
            addnav("Zurück zum Friedhof","graveyard.php");
            $session[user][reputation]--;
        } else {
            output("`)Als du zu fliehen versuchst, wirst du zum Kampf zurückberufen!`n`n");
            $battle=true;
        }
    } else {
        $battle = true;
    }
}

if ($battle){
    //make some adjustments to the user to put them on mostly even ground with the undead guy.
    $originalhitpoints = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $session['user']['soulpoints'];
    $originalattack = $session['user']['attack'];
    $originaldefense = $session['user']['defence'];
    $session['user']['attack'] = 10 + round(($session['user']['level'] - 1) * 1.5);
    $session['user']['defence'] = 10 + round(($session['user']['level'] - 1) * 1.5);
    include("battle.php");
    //reverse those adjustments, battle calculations are over.
    $session['user']['attack'] = $originalattack;
    $session['user']['defence'] = $originaldefense;
    $session['user']['soulpoints'] = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $originalhitpoints;
    if ($victory) {
        output("`b`&{$badguy['creaturelose']}`0`b`n"); 
        output("`b`\$Du hast {$badguy['creaturename']} erniedrigt!`0`b`n");
        output("`#Du bekommst `^{$badguy['creatureexp']}`# Gefallen bei `\$Ramius`#!`n`0");
        $session['user']['deathpower']+=$badguy['creatureexp'];
        $badguy=array();
        $_GET['op']="";
        if (e_rand(1,7)==3) addnav("Fluss der Seelen","styx.php");
                $_GET['op']="";
                if (e_rand(1,37)==4) addnav("Johannes der Täufer","johannes.php");
    }else{
        if ($defeat){
            //addnav("Zurück zu den Schatten","shades.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"ihre(m/r)":"seine(r/m)"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
            $taunt = str_replace("%W",$badguy[creaturename],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            
            addnews("`)".$session[user][name]."`) wurde auf dem Friedhof von {$badguy['creaturename']} erniedrigt.`n$taunt");
            output("`b`&Du wurdest von `%{$badguy['creaturename']} `&erniedrigt!!!`n");
            output("Du kannst heute keine weiteren Seelen mehr quälen.");
            $session['user']['donation']+=1;
            $session['user']['gravefights']=0;
            addnav("F?Zurück zum Friedhof","graveyard.php");
        }else{
            addnav("Q?Quälen","graveyard.php?op=fight");
            addnav("F?Fliehen","graveyard.php?op=run");
            if (getsetting("autofight",0)){
                addnav("AutoFight");
                addnav("5 Runden quälen","graveyard.php?op=fight&auto=five");
                addnav("Bis zum bitteren Ende","graveyard.php?op=fight&auto=full");
            }
        }
    }
}

if ($_GET['op']==""){
    output("`)`c`bDer Friedhof`b`c");
    output("Dein Geist wandert auf einen einsamen, mit Unkraut überwucherten Friedhof. Die Pflanzen scheinen nach deinem Geist im Vorbeischweben zu greifen.
    Du bist umgeben von den Überresten alter Grabsteine. Einige liegen auf dem Gesicht, andere sind in Stücke zerbrochen. Fast kannst du das Wehklagen 
    der hier gefangenen Seelen hören.
    `n`nMitten im Friedhof steht ein altertümliches Mausoleum, dem die Spuren ungezählter Jahre deutlich anzusehen sind. 
    Ein böse schauender Steingargoyle ziert die Dachspitze; seine Augen scheinen dir zu folgen und sein aufklaffender Mund ist gespickt mit scharfen Steinzähnen.
    Auf der Gedenktafel über der Tür ist zu lesen: `\$Ramius, Herr über den Tod`).");

    addnav("Etwas zum Quälen suchen","graveyard.php?op=search");
    addnav("M?Mausoleum betreten","graveyard.php?op=enter");
    addnav("Zurück zu den Schatten","shades.php");
}elseif ($_GET['op']=="enter"){
    output("`)`b`cDas Mausoleum`c`b");
    output("Du betrittst das Mausoleum und siehst dich in einer kalten, kahlen Kammer aus Marmor. Die Luft um dich herum trägt die Kälte des Todes selbst.
    Aus der Dunkelheit starren zwei schwarze Augen direkt in deine Seele. Ein feuchtkalter Griff scheint deine Seele zu umklammern und sie mit den Worten des Todesgottes `\$Ramius`) höchstpersönlich zu erfüllen.`n`n
    \"`0Dein sterblicher Körper hat dich im Stich gelassen. Und jetzt wendest du dich an mich. Es gibt in diesem Land diejenigen, die sich meinem Griff entziehen konnten und ein Leben über das Leben hinaus besitzen. Um mir deinen Wert für mich zu beweisen 
    und dir Gefallen zu verdienen, gehe raus und quäle deren Seelen. Solltest du mir genug Gefallen getan haben, werde ich dich belohnen.`)\"");
    addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
    addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
    
    addnav("F?Zurück zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="restore"){
    output("`)`b`cDas Mausoleum`c`b");
    if ($session['user']['soulpoints']<$max){
        if ($session['user']['deathpower']>=$favortoheal){
            output("`\$Ramius`) nennt dich einen Schwächling, weil du nach Wiederherstellung deiner Seele fragst. Aber da du genug Gefallen bei ihm gut hast, gibt er deiner Bitte zum Preis von `4$favortoheal`) Gefallen nach.");
            $session['user']['deathpower']-=$favortoheal;
            $session['user']['soulpoints']=$max;
        }else{
            output("`\$Ramius`) verflucht dich und wirft dich aus dem Mausoleum. Du mußt ihm erst genug Gefallen getan haben, bevor er dir die Wiederherstellung deiner Seele gewährt.");
        }
    }else{
        output("`\$Ramius`) seufzt und murmelt etwas von \"`7Nur weil sie tot sind, heißt das doch nicht, dass sie nicht zu denken brauchen, oder?`)\"`n`n");
        output("Vielleicht solltest du erstmal eine Wiederherstellung `inötig`i haben, bevor du danach fragst.");
    }
    addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
    //addnav("Restore Your Soul ($favortoheal favor)","graveyard.php?op=restore");
    
    addnav("Zurück zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="question"){
    if ($session['user']['deathpower']>=100) {
        output("`\$Ramius`) spricht: \"`7Du hast mich tatsächlich beeindruckt. Ich sollte dir die Möglichkeit gewähren, deine Feinde in der Welt der Sterblichen zu besuchen.`)\"");
        addnav("Ramius' Gefallen");
        addnav("h?Feind heimsuchen (25 Gefallen)","graveyard.php?op=haunt");
        if ($session[user][reputation]<=-10) output(" Er weist dich noch darauf hin, dass er keinen Einfluss auf das Gedächtnis der Lebenden - und besonders der Händler -  hat.");
        if ($session[user][reputation]<=-40) output("`n`n\"`7Wegen der Unehrenhaftigkeit deines Lebens kann ich dir nicht erlauben, vorzeitig zu den Lebenden zurückzukehren, obwohl du mir gute Dienste geleistet hast.`)\"");
        if ($session[user][reputation]>-40) addnav("e?Wiedererwecken (100 Gefallen)","newday.php?resurrection=true");
        addnav("5 Donationpoints (100 Gefallen)","graveyard.php?op=dona");
    }elseif ($session['user']['deathpower']>=10){
        addnav("Zum Rp wiederbeleben (10 Gefallen)","graveyard.php?op=rpreturn");

        addnav("Sonstiges");
    }elseif ($session['user']['deathpower'] >= 25){
        output("`\$Ramius`) spricht: \"`7Ich bin nicht wirklich beeindruckt von deinen Bemühungen, aber einen kleinen Gefallen werde ich dir gewähren. Führe meine Arbeit fort und ich kann dir vielleicht mehr meiner Kraft anbieten.`)\""); 
        addnav("Ramius' Gefallen");
        addnav("h?Feind heimsuchen (25 Gefallen)","graveyard.php?op=haunt");
        addnav("Sonstiges");
    }else{
        output("`\$Ramius`) spricht: \"`7Ich bin von deinen Bemühungen noch nicht beeindruckt. Führe meine Arbeit fort und wir können weiter reden.`)\"");
        if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/lachen.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
    }
    if ($session['user']['deathpower'] >= 1) addnav("Zeit bei den Schatten (1 Gefallen)","graveyard.php?op=time");
    output("`n`nDu hast `6{$session['user']['deathpower']}`) Gefallen bei `\$Ramius`).");
    addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
    addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
    
    addnav("Zurück zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="dona"){
    output("`\$Ramius`)' Gelächter lässt den Boden erbeben. \"`7Du verzichtest für ein paar Punkte auf das Leben? Bitte, soll mir nur Recht sein.`)\" Mit diesen Worten gibt er deiner Bitte nach.`nDu bekommst 5 Donationpoints.");
    $session['user']['deathpower']-=100;
    $session['user']['donation']+=5; 
    addnav("Zurück zum Mausoleum","graveyard.php?op=enter");
    addnav("Zurück zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="rpreturn"){
    output("`\$Ramius`)' Gelächter lässt den Boden erbeben. \"`7Du willst also umbedingt zu den Lebenden, auch wenn du wohl sehr geschwächt sein wirst?`)\" Mit diesen Worten gibt er deiner Bitte nach.`nDu kehrst zu den Lebenden zurück..");
    $session['user']['deathpower']-=10;
        $session['user']['alive']=true;
        $session['user']['hitpoints']=1;
        $session['user']['turns']=0;
        $session['user']['spirits']=0;
        $session['user']['witch']=3;
        set_special_val('witch', 3);
    addnav("Zurück zu den Lebenden","village.php");
}elseif ($_GET['op']=="time"){
    $zeit=timetotomorrow();
    output("`\$Ramius`) raunzt dir genervt entgegen, dass du noch `^{$zeit['hours']} Stunden, {$zeit['minutes']} Minuten und {$zeit['seconds']} Sekunden hier bleiben musst, wenn du nicht weiter für ihn arbeiten willst.");
    $session['user']['deathpower']-=1;
    addnav("Zurück zum Mausoleum","graveyard.php?op=enter");
    addnav("Zurück zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="haunt"){
    output("`\$Ramius`)  ist von deinen Aktionen beeindruckt und gewährt dir die Macht, einen Feind heimzusuchen.`n`n");
    output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
    addnav("","graveyard.php?op=haunt2");
    output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
    output("</form>",true);
    output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    addnav("Zurück zum Mausoleum","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt2"){
    $string="%";
    for ($x=0;$x<strlen($_POST['name']);$x++){
        $string .= substr($_POST['name'],$x,1)."%";
    }
    $sql = "SELECT login,name,level FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`\$Ramius`)  kann niemanden mit einem solchen Namen finden.");
    }elseif(db_num_rows($result)>100){
        output("`\$Ramius`) denkt, du solltest die Zahl derer, die du heimsuchen willst, etwas einschränken.");
        output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
        addnav("","graveyard.php?op=haunt2");
        output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
        output("</form>",true);
        output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    }else{
        output("`\$Ramius`) wird dir gestatten, eine der folgenden Personen heimzusuchen:`n");
        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='graveyard.php?op=haunt3&name=".HTMLEntities($row['login'])."'>",true);
            output($row['name']);
            output("</a></td><td>",true);
            output($row['level']);
            output("</td></tr>",true);
            addnav("","graveyard.php?op=haunt3&name=".HTMLEntities($row['login']));
        }
        output("</table>",true);
    }
    addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
    addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
    addnav("M?Zurück zum Mausoleum","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt3"){
    output("`)`c`bDas Mausoleum`b`c");
    $sql = "SELECT name,level,hauntedby,acctid FROM accounts WHERE login='{$_GET['name']}'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if ($row['hauntedby']!=""){
            output("Diese Person wurde bereits heimgesucht. Wähle eine andere");
        }else{
            $session['user']['deathpower']-=25;
            $roll1 = e_rand(0,$row['level']);
            $roll2 = e_rand(0,$session['user']['level']);
            if ($roll2>$roll1){
                output("Du hast `7{$row['name']}`) erfolgreich heimgesucht!");
                $sql = "UPDATE accounts SET hauntedby='{$session['user']['name']}' WHERE login='{$_GET['name']}'";
                db_query($sql);
                addnews("`7{$session['user']['name']}`) hat `7{$row['name']}`) heimgesucht!");
                $session['user']['donation']+=1;
                 systemmail($row['acctid'],"`)du wurdest heimgesucht","`)Du wurdest von {$session['user']['name']} heimgesucht"); 
            }else{
                addnews("`7{$session['user']['name']}`) hat erfolglos versucht, `7{$row['name']}`) heimzusuchen!");
                switch (e_rand(0,5)){
                case 0:
                    output("Gerade als du `7{$row['name']}`) heimsuchen wolltest, versaut dir ein Niesen komplett den Erfolg.");
                    break;
                case 1:
                    output("Die Heimsuchung von `7{$row['name']}`) läuft richtig gut. Leider schläft dein Opfer tief und fest und bekommt von deiner Anwesenheit absolut nichts mit.");
                    break;
                case 2:
                    output("Du machst dich zur Heimsuchung von `7{$row['name']}`) bereit, stolperst aber über deinen Geisterschwanz und landest flach auf deinem .... ähm ... Gesicht.");
                    break;
                case 3:
                    output("Du willst `7{$row['name']}`) im Schlaf heimsuchen, doch dein Opfer dreht sich nur im Bett um und murmelt etwas von 'nie wieder Würstchen so kurz vor dem Schlafengehen'.");
                    break;
                case 4:
                    output("Du weckst `7{$row['name']}`) auf. Dein Opfer schaut dich kurz an, sagt \"Niedlich!\" und versucht dich in einem Einmachglas einzufangen.");
                    break;
                case 5:
                    output("Du versuchst `7{$row['name']}`) zu erschrecken, siehst dich dabei im Augenwinkel selbst im Spiegel und gerätst in Panik, weil du einen Geist gesehen hast!");
                    break;
                }
            }
        }
    }else{
        output("`\$Ramius`) kann sich nicht mehr auf diese Person konzentrieren. Du kannst sie jetzt nicht heimsuchen.");
    }
    addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
    addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
    addnav("M?Zurück zum Mausoleum","graveyard.php?op=enter");
}
checkday();


page_footer();

?>

