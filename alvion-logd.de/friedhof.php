
<?php// Friedhof// Idee von Master// umgesetzt von Tweety// newgiftshop.php als Vorlage genutzt// 29.07.2005// ergänzt durch Teile des Friedhofes von Gregor_Samsa (http://lotgd.gamaxx.de)// von Linus für alvion-logd.de/logd/// 09.01.2008// für den Einbau nötig:// SQL: ALTER TABLE `accounts` ADD `trauer` TINYINT( 11 ) NOT NULL;// newday.php: $session['user']['trauer'] = 0;require_once "common.php";require_once "func/systemmail.php";page_header("Der Friedhof");$session['user']['standort']="Der Friedhof";output("`c`b`&Der Friedhof`0`b`c`n`n");addcommentary();checkday();//if ($session[user][locate]!=83){//$session[user][locate]=38;//redirect("friedhof.php");//}if ($_GET[op]==""){    //output("`c<img src='http://technomaus.the-replikant.de/images/graveyard.jpg'/>`c", true);    output("`cDu betrittst den Friedhof von Alvion. Langsam schaust du dich um wer hier alles so begraben liegt!`n        Du erblickst ein Buch das auf einem Altar liegt und schlägst es auf!`n        Ramius führt Buch über seine Sklaven...wer wo liegt...wer ihm tapfer dient...`n        Dann fällt dir ein ziemlich kleiner Schriftzug auf `i`qPflegt die Gräber!`0`i`n        Irgendwie lässt dich das erschauern und als du dich umdrehst steht eine Fee`n        wie aus dem Nichts vor dir! Sie spricht nicht dennoch verstehst du sie!`n        Du schaust in deinem Geldbeutel nach und zeugst ihr den Inhalt woraufhin sie dir`n        zeigt welche Blumen du dir leisten kannst!`c`n`n`n");//    output("<table width='100%'><tr><td width='10%'></td><td width='80%'>", true);//    output("</td><td width='10%'></td></tr></table>", true);    if ($session[user][gems] > 0){        addnav("Veilchen - 1 Edelstein","friedhof.php?op=send&op2=gefallen1");        output("<a href=\"friedhof.php?op=send&op2=gefallen1\">Jemanden Veilchen aufs Grab legen 1 Edelsteine und ihm somit 2 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen1");    }    if ($session[user][gems] > 1){        addnav("Tulpen - 2 Edelsteine","friedhof.php?op=send&op2=gefallen2");        output("<a href=\"friedhof.php?op=send&op2=gefallen2\">Jemanden Tulpen aufs Grab legen 2 Edelsteine und ihm somit 5 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen2");    }    if ($session[user][gems] > 2){         addnav("Narzissen - 3 Edelsteine","friedhof.php?op=send&op2=gefallen3");         output("<a href=\"friedhof.php?op=send&op2=gefallen3\">Jemanden Narzissen aufs Grab legen 3 Edelsteine und ihm somit 8 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen3");    }    if ($session[user][gems] > 3){         addnav("Lilien - 4 Edelsteine","friedhof.php?op=send&op2=gefallen4");        output("<a href=\"friedhof.php?op=send&op2=gefallen4\">Jemanden Lilien aufs Grab legen 4 Edelsteine und ihm somit 12 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen4");    }    if ($session[user][gems] > 4){         addnav("Kakteen - 5 Edelsteine","friedhof.php?op=send&op2=gefallen5");        output("<a href=\"friedhof.php?op=send&op2=gefallen5\">Jemanden Kakteen aufs Grab legen 5 Edelsteine und ihm somit 15 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen5");    }    if ($session[user][gems] > 5){         addnav("Primel - 6 Edelsteine","friedhof.php?op=send&op2=gefallen6");        output("<a href=\"friedhof.php?op=send&op2=gefallen6\">Jemanden Primeln aufs Grab legen 6 Edelsteine und ihm somit 18 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen6");    }    if ($session[user][gems] > 6){         addnav("Dornenrose - 7 Edelsteine","friedhof.php?op=send&op2=gefallen7");        output("<a href=\"friedhof.php?op=send&op2=gefallen7\">Jemanden Dornenrosen aufs Grab legen 7 Edelstein und ihm somit 24 Gefallen gewähren.</a><br>",true);        addnav("","friedhof.php?op=send&op2=gefallen7");    }    output("</ul>",true);    addnav("Sonstiges");    addnav("Kapelle","friedhof.php?op=kapelle");    addnav("Abteil der Toten","friedhof.php?op=tote");    addnav("Mit den Trauernden reden","friedhof2.php");    addnav("Zurück zum Platz der Künste","kunstplatz.php");    addnav("Zurück","village.php");                                   } else if ($_GET[op]=="send"){     $gefallen=$_GET[op2];     if (isset($_POST['search']) || $_GET['search']>""){          if ($_GET['search']>"") $_POST['search']=$_GET['search'];          $search="%";          for ($x=0;$x<strlen($_POST['search']);$x++){                $search .= substr($_POST['search'],$x,1)."%";          }          $search="name LIKE '".$search."' AND ";          if ($_POST['search']=="weiblich") $search="sex=1 AND ";          else if  ($_POST['search']=="mänlich") $search="sex=0 AND ";     } else {          $search="";     }     $ppp=25; // Player Per Page to display     if (!$_GET[limit]){          $page=0;     } else {          $page=(int)$_GET[limit];          addnav("Vorherige Seite","friedhof.php?op=send&op2=$gefallen&limit=".($page-1)."&search=$_POST[search]");     }     $limit="".($page*$ppp).",".($ppp+1);     $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND alive=0 ORDER BY login,level LIMIT $limit";     $result = db_query($sql);     if (db_num_rows($result)>$ppp) addnav("Nächste Seite","friedhof.php?op=send&op2=$gefallen&limit=".($page+1)."&search=$_POST[search]");     output("`rWessen Grab willst du mit Blumen schmücken?`n`n");     output("<form action='friedhof.php?op=send&op2=$gefallen' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);     addnav("","friedhof.php?op=send&op2=$gefallen");     output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);     for ($i=0;$i<db_num_rows($result);$i++){          $row = db_fetch_assoc($result);          output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='friedhof.php?op=send2&op2=$gefallen&name=".HTMLSpecialChars($row['acctid'])."'>",true);          output($row['name']);          output("</a></td><td>",true);          output($row['level']);          output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);          addnav("","friedhof.php?op=send2&op2=$gefallen&name=".HTMLSpecialChars($row['acctid']));     }     output("</table>",true);     addnav("Zurück","friedhof.php");} else if ($_GET[op]=="send2"){    $name=$_GET[name];//    $vorher=db_fetch_assoc(db_query('SELECT deathpower, login FROM accounts WHERE acctid='.$name.''));    $effekt="";    if ($_GET[op2]=="gefallen1"){          $gift="Veilchen";          $gefallen=2;          $effekt="Jemand hat dein Grab gepflegt und darauf Veilchen geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=1;    } else if ($_GET[op2]=="gefallen2"){          $gift="Tulpen";          $gefallen=5;          $effekt="Jemand hat dein Grab gepflegt und darauf Tulpen geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=2;    } else if ($_GET[op2]=="gefallen3"){          $gift="Narzissen";          $gefallen=8;          $effekt="Jemand hat dein Grab gepflegt und darauf Narzissen geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=3;    } else if ($_GET[op2]=="gefallen4"){          $gift="Lilien";          $gefallen=12;          $effekt="Jemand hat dein Grab gepflegt und darauf Lilien geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=4;    } else if ($_GET[op2]=="gefallen5"){          $gift="Kakteen";          $gefallen=15;          $effekt="Jemand hat dein Grab gepflegt und darauf Kakteen geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=5;    } else if ($_GET[op2]=="gefallen6"){          $gift="Primeln";          $gefallen=18;          $effekt="Jemand hat dein Grab gepflegt und darauf Primeln geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=6;    } else if ($_GET[op2]=="gefallen7"){          $gift="Dornenrosen";          $gefallen=24;          $effekt="Jemand hat dein Grab gepflegt und darauf Dornenrosen geplanzt! Ramius fand diese Geste so herzerweichend, dass er dir Weichei $gefallen Gefallen gewährt!";//          db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");          $session[user][gems]-=7;    }    updateuser($name,array('deathpower'=>"+$gefallen"));    //    $nachher=db_fetch_assoc(db_query('SELECT deathpower FROM accounts WHERE acctid='.$name.''));//    debuglog("pflanzte $gift auf das Grab von {$vorher['login']}, sie/er hatte zuvor {$vorher['deathpower']} und hat nun {$nachher['deathpower']} Gefallen!");          $mailmessage=$session[user][name];     $mailmessage.="`7 tut dir etwas Gutes.  Du solltest dich bei ihm/ihr bedanken, dass er dein Grab mit `6";     $mailmessage.=$gift;     //you can change the following the match what you name your gift shop     $mailmessage.="`7 bepflanzt hat.`n".$effekt;     systemmail($name,"`2Grab gepflegt!`2",$mailmessage);     output("`rDu hast erfolgreich $gift auf einem Grab gepflanzt! Ramius musste sich so totlachen, dass er ihm/ihr Gefallen gewärte!");     if (e_rand(1,3)==2){          output(" Bei der liebevollen Bepflanzung und Pflege vergisst du die Zeit und vertrödelst einen Waldkampf.");          $session[user][turns]--;     }     addnav("Weiter","friedhof.php");} else if($_GET[op]=="kapelle"){    if ($_GET['act']=='inside') {        if ($session['user']['deathpower']>10) {//            $session['user']['deathpower'] -= 10;            output("Ramius fragt dich wem du die Gefallen schicken willst?!");            output("<form action='friedhof.php?op=kapelle&act=search' method='POST'>Nach Name suchen: <input name='it' type='text' value='$_POST[it]'>`nAnzahl der Gefallen: <input type='text' name='amt' value='".($session['user']['deathpower']-10)."'>`n<input type='submit' class='button' value='Suchen'></form>",true);            addnav("","friedhof.php?op=kapelle&act=search");            //output("wenn user nicht tot ist....text= name des spieler legt doch...der brauch keine gefallen");            //output("Wenn user tot dann feld wieviele gefallen man geben will");        } else {                output("Du hast nicht genug Gefallen um jemandem Gefallen zu senden und Ramius den Preis zu zahlen.");        }        addnav("Zurück");        addnav("Zum Friedhof","friedhof.php");        } else if ($_GET['act']=='search') {        $sql = "SELECT `acctid`,`name`,`login`,`deathpower`,`emailaddress`,`lastip`,`uniqueid`, `alive` FROM `accounts` WHERE (`login` LIKE '$_POST[it]');";        $result = db_query($sql) or die(db_error(LINK));        if (db_num_rows($result) < 1) {             output("Die Person wurde nicht gefunden, aber du darfst nocheinmal suchen:`n");             output("<form action='friedhof.php?op=kapelle&act=search' method='POST'>Nach Name suchen: <input name='it' type='text' value='$_POST[it]'>`nAnzahl der Gefallen: <input type='text' name='amt' value='$_POST[amt]'>`n<input type='submit' class='button' value='Suchen'></form>",true);             addnav("","friedhof.php?op=kapelle&act=search");                } else if (!is_numeric($_POST['amt']) || $_POST['amt'] > $session['user']['deathpower']-10) {             output("Soviele Gefallen hast du gar nicht.`n");             output("<form action='friedhof.php?op=kapelle&act=search' method='POST'>Nach Name suchen: <input name='it' type='text' id='it' value='$_POST[it]'>`nAnzahl der Gefallen: <input type='text' name='amt' value='".$session['user']['deathpower']."'>`n<input type='submit' class='button' value='Suchen'><script language='javascript'>document.getElementById('it').focus();</script></form>",true);//             output("<script language='javascript'>document.getElementById('it').focus();</script>",true);             addnav("","friedhof.php?op=kapelle&act=search");        } else {            for ($i = 0;$i < db_num_rows($result);$i++) {                $row=db_fetch_assoc($result);                if ($row['emailaddress']==$session['user']['emailaddress'] || $row['uniqueid']==$session['user']['uniqueid'] || $row['lastip']==$session['user']['lastip']){                    output("Transaktionen unter Charaktern nicht möglich. Lies bitte noch einmal die Regeln.");                } else if ($row['alive']>0){                    output("".$row['name']."`0 lebt und braucht keine Gefallen!`n");                    output("<form action='friedhof.php?op=kapelle&act=search' method='POST'>Nach Name suchen: <input name='it' type='text' value='$_POST[it]'>`nAnzahl der Gefallen: <input type='text' name='amt' value='$_POST[amt]'>`n<input type='submit' class='button' value='Suchen'></form>",true);                    addnav("","friedhof.php?op=kapelle&act=search");                        } else {                    output("<a href='friedhof.php?op=kapelle&act=send&user=$row[acctid]&amt=$_POST[amt]'>$row[name]</a>`n", true);                    addnav("","friedhof.php?op=kapelle&act=send&user=$row[acctid]&amt=$_POST[amt]");                    }                        }        }                addnav("Zurück");        addnav("Zum Friedhof", "friedhof.php");             } else if ($_GET['act']=='send') {//        $vorher=db_fetch_assoc(db_query('SELECT login, deathpower FROM accounts WHERE acctid='.$_GET['user'].''));          //        db_query("UPDATE `accounts` SET `deathpower`=`deathpower` + '$_GET[amt]' WHERE `acctid`='$_GET[user]'");        updateuser($_GET['user'],array('deathpower'=>"+$_GET[amt]"));        $session['user']['deathpower'] -= 10;        $session['user']['deathpower'] -= $_GET['amt'];//        db_query("INSERT INTO `mail`(`msgfrom`,`msgto`,`subject`,`body`,`sent`) VALUES('".$session[user][acctid]."','$_GET[user]','Gefallen erhalten','".$session[user][name]." hat dir $_GET[amt] Gefallen geschenkt.',now())");        systemmail($_GET['user'],'Gefallen erhalten','`0'.$session['user']['name'].'`0  hat dir '.$_GET['amt'].' Gefallen geschenkt.');        output("Du gibst Ramius die ".($_GET['amt']+10)." Gefallen. 10 hält er davon ein und ".($_GET['amt'])."  werden von dir verschenkt.");//        $nachher=db_fetch_assoc(db_query('SELECT deathpower FROM accounts WHERE acctid='.$_GET['user'].''));//        debuglog("verschenkte Gefallen an {$vorher['login']}, sie/er hatte zuvor {$vorher['deathpower']} Gefallen, bekam {$_GET['amt']} hinzu und hat nun {$nachher['deathpower']} Gefallen!");        addnav("Zurück");        addnav("F?Zum Friedhof","friedhof.php");        addnav("Z?Zurück zum Dorf","village.php");            } else {        //output("`c<img src='http://www.walsermuseum.at/Walserweg/Bilder%20Walserweg/37%20St.%20Anna%20Kapelle.gif'/>", true);        output("Du betrittst die Kapelle und entdeckst Ramius hinter einem Schreibtisch...`n            Er grummelt etwas vor sich hin `q''Was willst du?''`0 fragt er dich und dreht sich dabei nicht um.             Du trittst näher an den Tisch heran und stotterst vor dich hin, dass du jemandem helfen willst in dem du deine Gefallen verschenkst!             Ramius dreht sich um und grinst `q''So so verschenken... Du hast {$session['user']['deathpower']} Gefallen.             Ich gebe deine Gefallen einem anderen wenn du `qmir 10 Gefallen für meine Arbeit gibst!''`0             Du überlegst ob du auf dieses Angebot eingehen sollst!");        addnav("Gefallen vegeben");        addnav("Ja","friedhof.php?op=kapelle&act=inside");        addnav("Nein","friedhof.php");    }/*} else if($_GET[op]=="ja"){          output("Du gibst Ramius die 10 Gefallen und er fragt dich wem du den Rest schicken willst?!");          output("<form action='friedhof.php?op=gefallen&op2=$gefallen' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);          addnav("","friedhof.php?op=gefallen&op2=$gefallen");          //output("wenn user nicht tot ist....text= name des spieler legt doch...der brauch keine gefallen");          //output("Wenn user tot dann feld wieviele gefallen man geben will");} else if($_GET[op2]==gefallen){          addnav("zurück","friedhof.php");*/} else if($_GET[op]=="tote") {    output("`b`5Das Abteil der Toten`b`n`n");    output("Du hast dich für den Weg der Toten entschieden...`n");    output("Ruhig Schreitest du den Weg entlang und liest aufmerksam die Namen auf den Gräbern. Wem soll deine Trauer gehöhren?`n`n");        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td><td>Status</td></tr>",true);        $sql = "SELECT acctid, login, name, trauer, turns, loggedin, laston, stealth FROM accounts WHERE alive = 0 ORDER BY laston DESC";    $sql1 = "SELECT count(acctid) AS c FROM accounts WHERE alive = 0 ORDER BY level";    $result1 = db_query($sql1) or die(db_error(LINK));    $row1 = db_fetch_assoc($result1);    $result = db_query($sql) or die(db_error(LINK));    $i==1;    if (!db_num_rows($result)){        output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gräber</td></tr>",true);    }else {        while ($row = db_fetch_assoc($result)) {            $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');            $i++;            output("<tr class='$bgclass'><td>",true);            output($i);            output("</td><td>",true);            output("<a href='friedhof.php?op=status&id=".$row[acctid]."'>".$row[name]."</a>",true);            output("</td><td>",true);            if($row['stealth']>0){                output("`4offline",true);            }else{                output($loggedin?"`@online":"`4offline",true);            }            output("</td></tr>",true);            addnav("","friedhof.php?op=status&id=".$row[acctid]);        }    }    output("</table>",true);    //Navigation    addnav("Trauere","friedhof.php?op=trauer");    addnav("Zurück");    addnav("F?Zurück zum Friedhof","friedhof.php");    addnav("Z?Zurück zum Dorf","village.php");} else if($_GET[op]=="status"){    $sql = "SELECT acctid, name, login, sex, age FROM accounts WHERE acctid=".$_GET[id];    $result = db_query($sql) or die(db_error(LINK));    $row = db_fetch_assoc($result);    output("Du Stehst vor dem Grab von ".$row[name]."`5 und staunst, dass ".($row[sex]?"sie":"er")." schon mit ".$row[age]." Jahren gestorben ist.`n`n");    output("Auf dem Grab steht folgendes:`n`n");    output("`c<table cellpadding=2 cellspacing=1><tr><td><center>`bHier ruht ".$row[login]."`b</center></td></tr>`n",true);    output("<tr><td>".($row[sex]?"Sie":"Er")." war bekannt als ".$row[name]."</td></tr>",true);    output("<tr><td>Nun liegt ".($row[sex]?"sie":"er")." mit ".$row[age]." Jahren hier begraben.</td></tr>",true);    output("</table>`c",true);          //Navigation          if($session[user][trauer]==0 && $session[user][turns]>0)            addnav("Trauere um ".$row[login],"friedhof.php?op=trauern&id=".$row[acctid]);          addnav("Zurück");          addnav("Abteil der Toten","friedhof.php?op=tote");    addnav("F?Zurück zum Friedhof","friedhof.php");    addnav("Z?Zurück zum Dorf","village.php");} else if($_GET[op]=="trauern"){    $session[user][trauer]++;    $session[user][turns]--;    $sql = "SELECT acctid, name, login, sex, age, deathpower  FROM accounts WHERE acctid=".$_GET[id];    $result = db_query($sql) or die(db_error(LINK));    $row = db_fetch_assoc($result);//    $vorher=$row['deathpower'];    output("`5Mit verweinten Augen rufst du zu `$ Ramius`5 und flehst, er solle ".$row[login]." eine weitere Chance geben, ".($row[sex]?"ihr":"sein")." Leben fortzusetzen.`n`n");    switch(mt_rand(1,10)){        case 1:        case 2:            output("`$ Ramius`5 ist gerührt von deiner Liebe zu ".$row[login]." und gewährt ".($row[sex]?"ihr":"ihm")." `$20 Gefallen`5.");//            $gefallen=$row[deathpower]+20;//            output(($row[sex]?"Sie":"Er")." hat nun ".$gefallen." Gefallen.");//            $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET[id];//            $result = db_query($sql) or die(db_error(LINK));//            systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session[user][name].'`0 ließ den Tränen freien Lauf und rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte dir `$20 Gefallen`0.');            $plus=20;        break;        case 3:        case 4:        case 5:        case 6:            output("`$ Ramius`5 ist gerührt von deiner Liebe zu ".$row[login]." und gewährt ".($row[sex]?"ihr":"ihm")." `$10 Gefallen`5.");//            $gefallen=$row[deathpower]+10;//            output(($row[sex]?"Sie":"Er")." hat nun ".$gefallen." Gefallen.");//            $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET[id];//            $result = db_query($sql) or die(db_error(LINK));//            systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session[user][name].'`0 ließ den Tränen freien Lauf und rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte dir `$10 Gefallen`0.');            $plus=10;        break;        case 7:        case 8:        case 9:            output("`5Nichts passiert...");        break;        case 10:            output("`$ Ramius`5 ist so gerührt von deiner Liebe zu ".$row[login]." dass er  ".($row[sex]?"ihr":"ihm")." `$ eine neue Chance`5 gibt. ".($row[sex]?"Sie":"Er")." bekommt `$ 100 Gefallen`5.");//            output($row[deathpower]);//            $gefallen=$row[deathpower]+100;//            //            output(($row[sex]?"Sie":"Er")." hat nun ".$gefallen." Gefallen.");//            $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET[id];//            $result = db_query($sql) or die(db_error(LINK));//            systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session[user][name].'`0 ließ den Tränen freien Lauf und rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte dir `$100 Gefallen`0.');            $plus=100;    }    if($plus>0){        updateuser($_GET['id'],array('deathpower'=>"+$plus"));        $sqlg="SELECT SUM(chgval) as gefallen FROM accounts_update WHERE  acctcol='deathpower' AND acctid=$_GET[id]";        $result=db_query($sqlg);        if (db_num_rows($result)){            $update=db_fetch_assoc($result);            $gplus=$update['gefallen'];        }else{            $gplus=0;        }        output(($row[sex]?"Sie":"Er")." hat nun ".($row['deathpower']+$gplus)." Gefallen.");        systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session['user']['name'].'`0 ließ den Tränen freien Lauf und rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte dir `$'.$plus.' Gefallen`0.');        //        $nachher=db_fetch_assoc(db_query('SELECT deathpower FROM accounts WHERE acctid='.$row['acctid'].''));//        debuglog("trauerte um {$row['login']}, sie/er hatte zuvor {$vorher} Gefallen, bekam {$plus} hinzu und hat nun {$nachher['deathpower']} Gefallen!");    }                //Navigation    addnav("Zurück");    addnav("Abteil der Toten","friedhof.php?op=tote");    addnav("F?Zurück zum Friedhof","friedhof.php");    addnav("Z?Zurück zum Dorf","village.php");}if($_GET[op]=="trauer"){    output("Du stellst dich zu den Elenden und trauerst mit ihnen.`n`n");        //Kommentare    viewcommentary("friedhof_trauer","Hinzufügen",25,"trauert",1,1);    //Navigation    addnav("Zurück");    addnav("Abteil der Toten","friedhof.php?op=tote");    addnav("F?Zurück zum Friedhof","friedhof.php");    addnav("Z?Zurück zum Dorf","village.php");}page_footer();
