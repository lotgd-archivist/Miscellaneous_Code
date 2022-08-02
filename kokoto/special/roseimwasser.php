<?php
//*-------------------------*
//|        Scriptet by      |
//|        Arîztokrazî      |
//|      [imperencia.de]    |
//|              &          |
//|          Rikkarda       |
//|      [silienta-logd.de] |
//|                         |
//|            Idea by      |
//|       °*Amerilion*°     |
//|      [mekkelon.de.vu]   |
//*-------------------------*
//Kampf aus mill.php
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit();
$battle = false;

if ($_GET['op']=='unt'){
    output('`gDu untersuchst das kleine Haus genauer. Die Tür ist knapp größer als du und scheinbar nur angelehnt.');
    $session['user']['turns']-=2;
    $session['user']['specialinc']='roseimwasser.php';
    addnav('Klopfe an','forest.php?op=kl');
    addnav('Öffne die Tür','forest.php?op=op');
    addnav('Zurück in den Wald','forest.php?op=weiter');

}else if ($_GET['op']=='weiter'){
    $session['user']['specialinc']='';
    output('`gDa du genug von mysteriösen Dingen hast gehst du einfach weiter.');

}else if ($_GET['op']=='kl'){
    switch(mt_rand(1,3)){
    case '1':
        output('`gDie Tür fällt in sich zusammen, scheinbar ist dieses sonderbare Haus schon lange unbewohnt.');
        $session['user']['specialinc']='';
        break;
    case '2':
    case '3':
        output('`gDie Tür schwingt mit einem knarren auf und du siehst neugierig in das kleine Haus hinein.');
        $session['user']['specialinc']='roseimwasser.php';
        addnav('Weiter','forest.php?op=schau');
        break;
    }

}else if ($_GET['op']=='op'){
    output('`gDu drückst probehalber die Türklinke hinunter,');
    switch(mt_rand(1,3)){
    case '1':
    case '2':
        output('`gwas leider nur dazu führt, dass die Tür zusammenbricht. Ein kurzer Blick zeigt dir, dass dies Haus unbewohnt und leer ist.');
        $session['user']['specialinc']='';
        break;
    case '3':
         output('`gwelche mit einem Quietschen nachgibt. Du schaust neugierig in das Haus hinein.');
         $session['user']['specialinc']='roseimwasser.php';
         addnav('Weiter','forest.php?op=schau');
         break;
    }

}else if ($_GET['op']=='schau'){
    output('`gAls erstes bemerkst du, dass die Wurzeln des Baumes auch im Inneren die Wände bilden. Du siehst ausserdem ein kleines Bett, einen Tisch, ein paar Stühle und noch einige Regale. Als du hineingegangen bist siehst du, dass an einer ansonsten leeren Stelle an der linken Wurzelwand ein kleiner Mamorblock steht, auf welchme wiederrum eine Schale aus Silber ihren Platz gefunden hat. Ansonsten ist hier nichts was auf einen Menschen oder ein anderes Geschöpf hindeutet.');
    $session['user']['specialinc']='roseimwasser.php';
    addnav('Anschauen','forest.php?op=schale');

}else if ($_GET['op']=='schale'){
    $rand = mt_rand(1,3);
    switch($rand){
    case '1':
    case '2':
        output('`gDu erblickst in der Schale dein Gesicht, wie es sich in klarem Wasser spiegelt. In dem Wasser schwimmen einige rote Rosen Du erschauderst denn dir wird klar das diese große magische Kraft besitzen. Du überlegst, ob du sie verschicken oder selbst behalten solltest.');
        addnav('Verschicken','forest.php?op=verschick');
        addnav('Behalten','forest.php?op=behalt');
        $session['user']['specialinc']='roseimwasser.php';
        break;
    case '3':
        output('`gDu hörst hinter dir ein Geräusch!');
        addnav('Umdrehen','forest.php?op=kampf');
        $session['user']['specialinc']='roseimwasser.php';
        break;
    }

}else if ($_GET['op']=='kampf'){
    output('`gHinter dir steht ein `2Waldschrat `gwelcher dich sofort mit ungeheurer Wucht angreift!');
    $badguy = array(
                "creaturename"=>"`2Waldschrat`0",
                "creaturelevel"=>$session['user']['level']1,
                "creatureweapon"=>"Ungeheure Wucht & Totale Überraschung",
                "creatureattack"=>$session['user']['attack']1.1,
                "creaturedefense"=>$session['user']['defence'],
                "creaturehealth"=>round($session['user']['maxhitpoints']1.25,0),
                "diddamage"=>0);
    $session['user']['badguy']=createstring($badguy);
    $session['user']['specialinc']='roseimwasser.php';
    $battle=true;
    $session['user']['specialinc']='';

//Battle Settings
}else if ($_GET['op']=='run'){   // Flucht
    if (e_rand()3 == 0){
        output ('`c`b`&Du konntest dem Waldschrat entkommen!`0`b`c`n');
        $session['user']['specialinc']='';
    }else{
        output('`c`b`$ Der Waldschrat hält dich auf!`0`b`c');
        $battle=true;
        $session['user']['specialinc']='roseimwasser.php';
    }

}else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='';
}
else if ($_GET['op']=='verschick'){
    $session['user']['specialinc']='roseimwasser.php';
    output("<form action='forest.php?op=verschick2' method='POST'>",true);
    allownav("forest.php?op=verschick2");
    output("`v`nWem willst du die Rose schicken?`n <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
    rawoutput('</form><script language="JavaScript">document.getElementById("name").focus()</script>');

}else if ($_GET['op']=='verschick2'){
    $session['user']['specialinc']='roseimwasser.php';
    $string="%";
    for ($x=0;$x<strlen_c($_POST['name']);$x++){
        $string .= substr_c($_POST['name'],$x,1)."%";
    }
    $sql = "SELECT * FROM accounts WHERE name LIKE '".mysql_real_escape_string($string)."' AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output('Du kannst niemanden mit einem solchen Namen finden...`@');
		addnav('zurück','forest.php?op=verschick');
    }elseif(db_num_rows($result)>100){
        rawoutput('Du solltest die Zahl derer, die du stärken willst etwas einschränken.');
        output("<form action='forest.php?op=verschick2' method='POST'>",true);
        allownav("forest.php?op=verschick2");
        output("Wem willst du die Rose schicken? `n<input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
 rawoutput('</form><script language="JavaScript">document.getElementById("name").focus()</script>');
    }else{
	addnav('zurück','forest.php?op=verschick');
        output("Du kannst folgenden Leuten dein Geschenk schicken:`n");
        rawoutput("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td></tr>");
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i2?"trlight":"trdark")."'><td><a href='forest.php?op=verschick3&acctid=".$row['acctid']."'>",true);
            output($row['name']);
            rawoutput('</a></td><td>');
            output($row['level']);
            rawoutput('</td></tr>');
            allownav("forest.php?op=verschick3&acctid=".$row['acctid']);
        }
        rawoutput('</table>');
    }

}else if ($_GET['op']=='verschick3'){
    $session['user']['specialinc']='roseimwasser.php';
    rawoutput("Möchtest du noch eine Karte beilegen?");
    rawoutput("<form action='forest.php?op=verschick4&card=yes&acctid=".(int)$_GET['acctid']."' method='POST'>Folgenden Text schicken: <input name='cardtext' value='".$_POST['cardtext']."'><input type='submit' class='button' value='Senden'></form>");
    addnav("Keine Karte","forest.php?op=verschick4&card=no&acctid=".(int)$_GET['acctid']."");
    allownav("forest.php?op=verschick4&card=yes&acctid=".(int)$_GET['acctid']."");

}else if ($_GET['op']=='verschick4'){
    $session['user']['specialinc']='';
    $name=(int)$_GET['acctid'];
    $exp=$session['user']['experience']0.1;
    $cp=mt_rand(3,8);
    
    $session['user']['experience']+=$exp;
    $session['user']['charm']+=$cp;

    $gift="eine wunderschöne, geheimnisvolle Rose `0";
    $effekt="Als du die Rose so ansiehst wird dir ihre wundervolle Schönheit erst richtig bewusst. Du erkennst in ihren Rosenblättern die ganze Weisheit des uralten Mamors und des Wassers in welchem sie gelegen hat. Diese Schönheit geht auf dich über, ebenso wie die Weisheit der Rose.`n`n`^Du erhälst ".$exp." Erfahrung und ".$cp." Charmpunkte.";
    db_query("UPDATE accounts SET experience=experience+$exp WHERE acctid=$name");
    db_query("UPDATE accounts SET charm=charm+$cp WHERE acctid=$name");
    $mailmessage=$session['user']['name'];
        $mailmessage.="`7 hat dir ein Paket geschickt. Du öffnest es. Es ist `6";
        $mailmessage.=$gift;
        $mailmessage.="`7 aus einer mystischen Hütte.`n".$effekt;
        if($_GET['card']=='yes'){
        $mailmessage.="`7Es liegt eine Karte mit folgenden Text bei: `n`n";
        $mailmessage.= $_POST['cardtext'];
        $mailmessage.="`n";
        }
        if($_GET['card']=='yes' || $_GET['card']=='no'){
        systemmail($_GET['acctid'],"`2Geschenk erhalten!`2",$mailmessage);
        output("`rDie Rose wurde verschickt!");
        
        }
    output("`n`n`^Für dieses Abenteuer erhälst du $exp Erfahrung.`nFür deine nette Geste erhälst du $cp Charme.");

}else if ($_GET['op']=='behalt'){
    $session['user']['specialinc']='';
    $exp=round($session['user']['experience']0.08);
    $cp=mt_rand(3,8);
    output("`gAls du die Rose so ansiehst wird dir ihre wundervolle Schönheit erst richtig bewusst. Du erkennst in ihren Rosenblättern die ganze Weisheit des uralten Mamors und des Wassers, in welchem sie gelegen hat. Diese Schönheit geht auf dich über, ebenso wie die Weisheit der Rose.`n`n`^Du erhälst ".$exp." Erfahrung und ".$cp." Charmpunkte.");
    $session['user']['experience']+=$exp;
    $session['user']['charm']+=$cp;
}else{
    output('`n`c`b`4Die Rosen im Wasser`b`c`n`n `gWie schon oft vorher streifst du durch den Wald. Nach einiger Zeit kommst du an einen sehr großen Baum, zwischen dessen Wurzeln endeckst du ein kleines Haus, welches sich fast zu verstecken scheint.`n`n`^Das untersuchen wird dich 2 Runden kosten!');
    $session['user']['specialinc']='roseimwasser.php';
    if($session['user']['turns']>=2) addnav('Untersuchen','forest.php?op=unt');
    addnav('Weitergehen','forest.php?op=weiter');

}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']='roseimwasser.php';
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`gDu konntest den Waldschrat besiegen!');
            //debuglog("erledigte den Waldschrat");
            //Navigation
            addnav('Zurück in den Wald','forest.php');
            if (rand(1,3)==1){
                $gem_gain = rand(2,3);
                $gold_gain = rand($session['user']['level']10,$session['user']['level']20);
                output("`gAls Du Dich noch einmal umdrehst findest Du $gem_gain Edelsteine
                und $gold_gain Goldstücke.`n`n");
            }
            $exp = round($session['user']['experience']0.08);
            output("`gDurch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['specialinc']='';
        }elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            //debuglog("wurde vom Waldschrat erledigt.");
            output('`n`gDer Waldschrat war stärker!`n`nDu verlierst 5% Deiner Erfahrung.');
            output('`nDein Gold ist wohl weg...`n Du kannst morgen
            wieder kämpfen!`0');
            addnav('Tägliche News','news.php');
            addnews($session['user']['name'].' `gwurde in einer sonderbaren Waldhütte tot aufgefunden. '.($session['user']['sex']?"Sie":"Er").' sah überascht aus.');
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'].95,0);
            $session['user']['specialinc']='';
        }else{
            fightnav(true,true);
        }

}
?>