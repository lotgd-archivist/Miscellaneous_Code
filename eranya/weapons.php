
<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance) and 'pay from bank'

// Anpassung fürs Gildenmod durch Talion: rebate-Var

require_once "common.php";
checkday();

require_once(LIB_PATH.'dg_funcs.lib.php');
if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {        
        $rebate = dg_calc_boni($session['user']['guildid'],'rebates_weapon',0);
}

if ($_GET['op']=="fight") {$battle=true;}

page_header("Yaris' Waffenladen");
output("`c`b`&Yaris' Waffen`0`b`c");
$tradeinvalue = round(($session['user']['weaponvalue']*.75),0);
if ($_GET['op']==""){
        $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session['user']['dragonkills'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        $sql = "SELECT * FROM weapons WHERE level = ".(int)$row['level']." ORDER BY damage ASC";
        $result = db_query($sql) or die(db_error(LINK));
        output("`!Yaris `7steht hinter einem Ladentisch und scheint dir nur wenig Interesse entgegen zu bringen, als du eintrittst. Aus Erfahrung weißt du aber,
                dass er jede deiner Bewegungen misstrauisch beobachtet. Er mag ein bescheidener Waffenhändler sein, aber er trägt immer noch die Grazie eines
                Mannes in sich, der seine Waffen gebraucht hat, um stärkere ".($session['user']['sex']?"Frauen":"Männer")." als dich zu töten.`n
                Der massive Griff eines Claymore ragt hinter seiner Schulter hervor, dessen Schimmer im Licht der Fackeln viel heller wirkt, als seine Glatze, die
                er mehr zum strategischen Vorteil rasiert hält, obwohl auch die Natur bereits auf einem bestimmten Level der Kahlköpfigkeit besteht.
                `!Yaris`7 nickt dir schließlich zu und wünscht sich, während er seinen Spitzbart streichelt, eine Gelegenheit, um eine seiner Waffen benutzen zu
                können.`n
                `7Du schlenderst durch den Laden und tust dein Bestes, so auszusehen, als ob du wüßtest, wozu die meisten dieser Objekte gut sind. `!Yaris`7 sieht
                dir eine Weile lang zu und meint dann mit einem kurzen Nicken in Richtung deiner Waffe: \"`#Ich gebe dir `^$tradeinvalue`# Gold für
                `5".$session['user']['weapon']."`#".($rebate?" und `^".$rebate." %`# Rabatt auf neue Waffen dank deiner Gildenmitgliedschaft.":"").".`7\"");
        if($session['user']['reputation']<=-10) {
            output("`nDabei macht er allerdings keinen Hehl aus seinem speziellen Misstrauen dir gegenüber, als ob er wüsste, dass du hier hin und wieder versuchst, ihm
                    seine schönen Waffen zu klauen.");
        }
        output("`n`n<table border='0' cellpadding='0'>
                <tr class='trhead'><td>`bName`b</td><td align='center'>`bSchaden`b</td><td align='right'>`bPreis`b</td></tr>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
                  $row = db_fetch_assoc($result);
                $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);
                $bgcolor=($i%2==1?"trlight":"trdark");
                if ($row['value']<=($session['user']['gold']+$tradeinvalue)){
                        output("<tr class='$bgcolor'><td>Kaufe <a href='weapons.php?op=buy&id=".$row['weaponid']."'>".$row['weaponname']."</a></td><td align='center'>".$row['damage']."</td><td align='right'>".$row['value']."</td></tr>",true);
                        addnav("","weapons.php?op=buy&id=".$row['weaponid']."");
                }else{
//                        output("<tr class='$bgcolor'><td>".$row['weaponname']."</td><td align='center'>".$row['damage']."</td><td align='right'>$row['value']</td></tr>",true);
//                        addnav("","weapons.php?op=buy&id=".$row['weaponname']."");
                        output("<tr class='$bgcolor'><td>- - - - <a href='weapons.php?op=buy&id=".$row['weaponid']."'>".$row['weaponname']."</a></td><td align='center'>".$row['damage']."</td><td align='right'>".$row['value']."</td></tr>",true);
                        addnav("","weapons.php?op=buy&id=".$row['weaponid']."");
                }
        }
        output("</table>",true);

        $show_invent = true;

        addnav('Waffenladen');
        addnav("u?Mit anderen unterhalten","weapons.php?op=chat");
        addnav('S?Zur Schmiede','blacksmith.php');
        addnav('Yaris');
        if ($session['user']['rename_weapons']==0)
        {addnav("Yaris zum Kampf herausfordern (500 DP)","weapons.php?op=duel");}
        else {addnav("In den geheimen Shop","xshop.php?op=peruse");}
        addnav('Zurück');
        addnav("Zurück zum Marktplatz","market.php");

// Duell für die Waffenumbenennung
}else if ($_GET['op']=="duel"){
$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
if ($pointsavailable<500) {
output("Yaris lacht aus vollem Halse als du ihm entgegentrittst und wendet sich dann auch wieder seiner Arbeit zu, nachdem er etwas Unverständliches gemurmelt hat. Was immer es war, es klang nicht sehr freundlich.");
addnav("Zurück zum Marktplatz","market.php");
} else {
output("Yaris legt seine Sachen bei Seite und mustert dich eindringlich und nickt. Ihr steht euch nun gegenüber. Einige Schaulustige haben sich bereits um euch versammelt. Noch kannst du weglaufen. Der Kampf kostet dich, egal wie er ausgeht, 100 Donation Punkte und weiter 400 wenn du gewinnst.");
addnav("Angreifen","weapons.php?op=duel2");
addnav("Zurück zum Marktplatz","market.php");
}

}else if ($_GET['op']=="duel2"){
$session['user']['donationspent']+=100;
if ($session['user']['prefs']['sounds']) output("<embed src=\"media/bigbong.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
$battle=true;
            
             $badguy = array("creaturename"=>"`#Yaris`0","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"Katana","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user'][defence],"creaturehealth"=>$session['user']['hitpoints'], "diddamage"=>0);

$session['user']['badguy']=createstring($badguy);

// Ende1

} else if ($_GET['op']=="chat"){
   // RP-Ort
   addcommentary();
   output("`7Du gesellst dich zu den anderen Bürgern, die sich wie du gerade im Laden aufhalten, um das Angebot an Waffen zu bestaunen.`n`n");
   viewcommentary('weapons','Sagen',15,'sagt');
   addnav('Zurück');
   addnav('L?Zum Laden','weapons.php');
   addnav('Zum Marktplatz','market.php');
}else if ($_GET['op']=="buy"){
          $sql = "SELECT * FROM weapons WHERE weaponid=".$_GET['id'];
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
                  output("`!Yaris`7 schaut dich eine Sekunde lang verwirrt an und kommt zu dem Schluss, dass du ein paar Schläge zuviel auf den Kopf bekommen hast. Schließlich nickt er und grinst.");
                addnav("Nochmal versuchen?","weapons.php");
                addnav("Zurück zum Marktplatz","market.php");
        }else{
                  $row = db_fetch_assoc($result);
                 $row['value'] = ceil( $row['value'] * (100 - $rebate) * 0.01);
                if ($row['value']>($session['user']['gold']+$tradeinvalue)){
                        if ($session['user']['specialtyuses']['thievery']>=2) {
                                $klau=e_rand(1,15);
                        } else {
                                $klau=e_rand(2,18);
                        }
                        $session['user']['reputation']-=10;
                        if ($session['user']['reputation']<=-10){
                                if ($session['user']['reputation']<=-20) $klau=10;
                                if ($klau==1){ // Fall nur für Diebe
                                        output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%".$row['weaponname']."`5 gegen `%".$session['user']['weapon']."`5 aus und verlässt fröhlich pfeifend den Laden. ");
                                        output(" `bGlück gehabt!`b  `!Yaris`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal passiert ihm das nicht! Stolz auf deine ");
                                        output("fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
                                        $arr_wpn['tpl_name'] = $row['weaponname'];
                                        $arr_wpn['tpl_value1'] = $row['damage'];
                                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        if ($session['user']['charm']) $session['user']['charm']-=1;
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
                                        output("`5Da dir das nötige Kleingold fehlt, grapschst du dir `%".$row['weaponname']."`5 und tauschst `%".$session['user']['weapon']."`5 unauffällig dagegen aus. ");
                                        output(" `bGlück gehabt!`b `!Yaris`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal wird ihm das nicht passieren! Stolz auf deine ");
                                        output("fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
                                        $arr_wpn['tpl_name'] = $row['weaponname'];
                                        $arr_wpn['tpl_value1'] = $row['damage'];
                                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        if ($session['user']['charm']) $session['user']['charm']-=1;
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
                                        output("`5Du grapschst dir `%".$row['weaponname']."`5 und tauschst `%".$session['user']['weapon']."`5 unauffällig dagegen aus. ");
                                        output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem ");
                                        output("Augenwinkel `#Yaris`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session['user']['armor']." und zerrt dich mit zur Stadtbank...`n`n");
                                        output("`#Yaris`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!");
                                        if ($session['user']['goldinbank']<0){
                                                output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
                                                output("Er entreißt dir ".$row['weaponname']." gewaltsam, ");
                                                output(" drückt dir dein(e/n) alte(n/s) ".$session['user']['weapon']." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er dich umgebracht und dass er dich beim nächsten Diebstahl");
                                                output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
                                                $session['user']['hitpoints']=round($session['user']['hitpoints']/2);
                                        }else{
                                                $session['user']['goldinbank']-=($row['value']-$tradeinvalue);
                                                if ($session['user']['goldinbank']<0) output("`nDu hast dadurch jetzt `^".abs($session['user']['goldinbank'])." Gold`5 Schulden bei der Bank!!");
                                                output("`nDas nächste Mal bringt er dich um. Da bist du ganz sicher.");
                                                //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
                                                $arr_wpn['tpl_name'] = $row['weaponname'];
                                                $arr_wpn['tpl_value1'] = $row['damage'];
                                                $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        }
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else { // Diebstahl gelingt nicht
                                          output("Während du wartest, bis `!Yaris`7 in eine andere Richtung schaut, näherst du dich vorsichtig dem `5".$row['weaponname']."`7 und nimmst es leise vom Regal. ");
                                        output("Deiner fetten Beute gewiss drehst du dich leise, vorsichtig, wie ein Ninja, zur Tür, nur um zu entdecken, ");
                                        output("dass `!Yaris`7 drohend in der Tür steht und dir den Weg abschneidet. Du versuchst einen Flugtritt. Mitten im Flug hörst du das \"SCHING\" eines Schwerts, ");
                                        output("das seine Scheide verlässt.... dein Fuß ist weg. Du landest auf dem Beinstumpf und `!Yaris`7 steht immer noch im Torbogen, das Schwert ohne Gebrauchsspuren wieder im  Halfter und mit ");
                                        output("vor der stämmigen Brust bedrohlich verschränkten Armen. \"`#Vielleicht willst du dafür bezahlen?`7\" ist alles, was er sagt, ");
                                        output("während du vor seinen Füßen zusammen brichst und deinen Lebenssaft unter deinem dir verbliebenen Fuß über den Boden ausschüttest.`n");
                                        $session['user']['alive']=false;
                                        //debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus");
                                        $session['user']['gold']=0;
                                        $session['user']['hitpoints']=0;
                                        $session['user']['experience']=round($session['user']['experience']*.9,0);
                                        $session['user']['gravefights']=round($session['user']['gravefights']*0.75);
                                        output("`b`&Du wurdest von `!Yaris`& umgebracht!!!`n");
                                        output("`4Das Gold, das du dabei hattest, hast du verloren!`n");
                                        output("`4Du hast 10% deiner Erfahrung verloren!`n");
                                        output("Du kannst morgen wieder kämpfen.`n");
                                        output("`nWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!");
                                        addnav("Tägliche News","news.php");
                                        addnews("`%".$session['user']['name']."`5 wurde beim Versuch, in `!Yaris`5' Waffenladen zu stehlen, niedergemetzelt.");
                                }
                                if ($session['user']['reputation']<=-10) $session['user']['reputation']-=10;
                        }else{
                                $session['user']['reputation']-=10;
                                if ($klau==1){ // Fall nur für Diebe
                                        output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%".$row['weaponname']."`5 gegen `%".$session['user']['weapon']."`5 aus und verlässt fröhlich pfeifend den Laden. ");
                                        output(" `bGlück gehabt!`b  `!Yaris`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft wesentlich besser aufpassen! Stolz auf deine ");
                                        output("fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
                                        
                                        $arr_wpn['tpl_name'] = $row['weaponname'];
                                        $arr_wpn['tpl_value1'] = $row['damage'];
                                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
                                        output("`5Da dir das nötige Kleingold fehlt, grapschst du dir `%".$row['weaponname']."`5 und tauschst `%".$session['user']['weapon']."`5 unauffällig dagegen aus. ");
                                        output(" `bGlück gehabt!`b `!Yaris`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft besser aufpassen. Stolz auf deine ");
                                        output("fette Beute stolzierst du über den Marktplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
                                        
                                        $arr_wpn['tpl_name'] = $row['weaponname'];
                                        $arr_wpn['tpl_value1'] = $row['damage'];
                                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        
                                        if ($session['user']['charm']) $session['user']['charm']-=1;
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
                                        output("`5Du grapschst dir `%".$row['weaponname']."`5 und tauschst `%".$session['user']['weapon']."`5 unauffällig dagegen aus. ");
                                        output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem ");
                                        output("Augenwinkel `#Yaris`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session['user']['armor']." und zerrt dich mit zur Stadtbank...`n`n");
                                        output("`#Yaris`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!");
                                        if ($session['user']['goldinbank']<0){
                                                output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
                                                output("Er entreißt dir ".$row['weaponname']." gewaltsam, ");
                                                output(" drückt dir dein(e/n) alte(n/s) ".$session['user']['weapon']." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er dich umgebracht und dass er dich beim nächsten Diebstahl");
                                                output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
                                                $session['user']['hitpoints']=round($session['user']['hitpoints']/2);
                                        }else{
                                                $session['user']['goldinbank']-=($row['value']-$tradeinvalue);
                                                if ($session['user']['goldinbank']<0) output("`nDu hast dadurch jetzt `^".abs($session['user']['goldinbank'])." Gold`5 Schulden bei der Bank!!");
                                                //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
                                                output("`nDas nächste Mal bringt er dich wahrscheinlich um.");
                                                
                                                $arr_wpn['tpl_name'] = $row['weaponname'];
                                                $arr_wpn['tpl_value1'] = $row['damage'];
                                                $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                        }
                                        addnav("Zurück zum Marktplatz","market.php");
                                } else { // Diebstahl gelingt nicht
                                        output("`5Du grapschst dir `%".$row['weaponname']."`5 und tauschst `%".$session['user']['weapon']."`5 unauffällig dagegen aus. ");
                                        output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Marktplatz stolzierst, siehst du aus dem ");
                                        output("Augenwinkel `#Yaris`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session['user']['armor'].".`n`n");
                                        output("Er entreißt dir ".$row['weaponname']." gewaltsam, ");
                                        output(" drückt dir dein(e/n) alte(n/s) ".$session['user']['weapon']." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass er dich beim nächsten Diebstahl");
                                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
                                        $session['user']['hitpoints']=1;
                                        if ($session['user']['turns']>0){
                                                output("`n`4Du verlierst einen Waldkampf und fast alle Lebenspunkte.");
                                                $session['user']['turns']-=1;
                                        }else{
                                                output("`n`4Yaris hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.");
                                                $session['user']['charm']-=3;
                                                if ($session['user']['charm']<0) $session['user']['charm']=0;
                                        }
                                        addnav("Zurück zum Marktplatz","market.php");
                                }
                        }
                }else{
                        output("`!Yaris`7 nimmt dein `5".$session['user']['weapon']."`7 stellt es aus und hängt sofort ein neues Preisschild dran. ");
                        //debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['weaponname'] . " weapon");
                        $session['user']['gold']-=$row['value'];
                        $session['user']['gold']+=$tradeinvalue;
                        
                        $arr_wpn['tpl_name'] = $row['weaponname'];
                        $arr_wpn['tpl_value1'] = $row['damage'];
                        $arr_wpn['tpl_gold'] = round($row['value'] * 0.75);
                                                
                        output("`n`nIm Gegenzug händigt er dir ein glänzendes, neues `5".$row['weaponname']."`7 aus, das du probeweise im Raum schwingst. Dabei schlägst du `!Yaris`7 fast den Kopf ab. ");
                        output("Er duckt sich so, als ob du nicht der erste bist, der seine neue Waffe sofort ausprobieren will...");
                        addnav("Zurück zum Marktplatz","market.php");
                }
        }
}

if(is_array($arr_wpn)) {
        
        // Zu invent hinzufügen
        $int_wid = item_add($session['user']['acctid'],'waffedummy',$arr_wpn);
        // Als Waffe ausrüsten (dabei alte Waffe löschen)
        item_set_weapon($arr_wpn['tpl_name'],$arr_wpn['tpl_value1'],$arr_wpn['tpl_gold'],$int_wid,0,2);
        
}

if ($battle){
                include("battle.php");

                if ($victory) {
                $badguy=array();
                $session['user']['badguy']="";
                $battle=false;
                    output("`7Bevor du zum letzten Schlag ansetzen kannst, hebt Yaris eine Hand.`n");
                output ("`#Du hast dich wahrhaft würdig erwiesen. Komm mit mir, ich zeige dir einen Ort an dem ich besondere Arbeiten für ganz besondere Leute vollbringe.");
                addnews("`#".$session['user']['name']."`5 hat `!Yaris`5 in einem fairen Zweikampf bezwungen.");
                    $session['user']['donationspent']+=400;
                    $session['user']['rename_weapons']=1;
                    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                addnav("Mitgehen","xshop.php?op=peruse");
                addnav("Zurück zum Marktplatz","market.php");
                } else if ($defeat) {
                    output ("`7Anstatt dich in das Reich des Schlafes zu befördern reicht `#Yaris`7 dir eine Hand und hilft dir auf. Das war wohl nichts!");
                    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                    $battle=false;
                addnews("`%".$session['user']['name']."`5 wurde von `!Yaris`5 in einem fairen Zweikampf windelweich geschlagen.");
                addnav("Zurück zum Marktplatz","market.php");
                } else { fightnav(false,false); }



//Duell Ende

}

page_footer();
?>

