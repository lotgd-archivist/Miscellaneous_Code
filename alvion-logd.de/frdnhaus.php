
<?php/* Ein Freudenhaus *fg*   Von : Taikun   Domain: www.logd-midgar.de----   SQL:--   CREATE TABLE `frdn` (   `acctid` int(11) unsigned NOT NULL default '0',  `id` int(10) unsigned NOT NULL auto_increment,  `kosten` int(11) unsigned NOT NULL default '0',  `user` varchar(200) NOT NULL default '0', `sex` tinyint(4) unsigned NOT NULL default '0', `raum` varchar(200) NOT NULL default '0',  PRIMARY KEY  (`id`),  UNIQUE KEY `user` (`user`)) TYPE=MyISAM AUTO_INCREMENT=1 ;---- SQL Accounts:--ALTER TABLE accountsADD `room` enum('0','1') NOT NULL default '0',ADD `roomid` INT ( 11 ) NOT NULL default '0';ALTER TABLE `accounts` ADD COLUMN `frdnpartner` INT(11) NOT NULL DEFAULT '0' AFTER `roomid` ;Version 0.2*/// Anfangssachen :)// #############################################require_once "common.php";// checkday();page_header("Das Freudenhaus");addcommentary();$stadt = "Alvion";$verw = "2500";$start = "10:00";$end  = "06:00";$sql = "SELECT name, sex FROM accounts";$result = db_query($sql);$row = db_fetch_assoc($result);switch($_GET['op']):// Anfangstext + Navigation    case "";        if (date('H:i') >= $start || date('H:i') <= $end) {            if($session['user']['room']==1){                output("`@Bitte beachtet, dass wenn Ihr in das Zimmer geht, erst wieder rausgehen könnt, wenn Ihr fertig seid. Also wartet ab, bis sich jemand gefunden hat, der sich zu Euch gesellt.`n`n`n");                addnav("Ins Zimmer","frdnhaus.php?op=room&id=".$_GET['id']."");            } else {                output("`@Willkommen im Freundenhaus von ".$stadt.", ".($row['sex']?"werte":"werter")." ".$session['user']['name'].".                    `@Wollt Ihr Euch selber anbieten oder Euch lieber verwöhnen lassen? Wenn Ihr Euch verwöhnen lassen wollt, müsst Ihr beachten, dass die Preise variieren können.`n                    Wenn Ihr Euch selber anbieten wollt, kostet dies eine geringe Bearbeitungsgebühr von `^".$verw." Gold.`n`n`n");                addnav("Ein Zimmer wählen","frdnhaus.php?op=ver");                if($session['user']['gold']>=$verw) {                    addnav("Dich Anbieten","frdnhaus.php?op=anbiet");                }            }        } else {            output("`@Leider hat das Freudenhaus ".$stadt."'s im moment geschlossen. Kommt doch ein anderes mal vorbei.`n");        }        viewcommentary("frdn","Mit den Anderen reden:",25,"sagt",1,1);        addnav("Zurück ins Dorf","village.php");    break;// Raum für *peep* :D    case "room";        $sqla = "SELECT name FROM accounts WHERE acctid=".$session['user']['frdnpartner'];        $resulta=db_query($sqla);        $rowa=db_fetch_assoc($resulta);        output("`@Du befindest dich mit {$rowa['name']} `@in einem Zimmer des Freudenhauses. Im gedämpften Licht welches durch die roten Vorhänge            scheint erkennst du die geschmackvolle Einrichtung. Dieses behagliche Zimmer ist geschmückt mit Bildern von Paaren in eindeutigen            Situationen an den Wänden. Links siehts du ein bequem aussehendes Ledersofa und auf der rechten Seite steht ein großes Bett.            Auch ein Regal mit allerlei nützlichem Spielzeug ist vorhanden.`n`n");        viewcommentary("frdn-".$session['user']['roomid'],"Hinzufügen",25,"flüstert",1,1);        addnav("RP Beenden","frdnhaus.php?op=beend");        addnav("Zurück ins Dorf","village.php");    break;// RP beenden    case "beend";        output("Du kannst nun wieder in ein neues Zimmer bzw. Dich wieder selber anbieten.");        addnav("Zurück zum Eingang","frdnhaus.php");        $sqlb = "SELECT frdnpartner FROM accounts WHERE acctid=".$session['user']['frdnpartner'];        $resultb=db_query($sqlb);        $rowb=db_fetch_assoc($resultb);        if($rowb['frdnpartner']>0){            $sql = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('`System`0','{$session['user']['frdnpartner']}','`^Freudenhaus!`0','`&{$session['user']['name']}`6 hat das RP im Zimmer beendet!',now())";            db_query($sql);        }        $session['user']['room']=0;        $session['user']['roomid']=0;        $session['user']['frdnpartner']=0;    break;// ############################################// Gold u. Zimmer ID// ######################################################################################################case "anbiet";addnav("Zurück zum Freudenhaus","frdnhaus.php");output("<form action=\"frdnhaus.php?op=send\" method='post'>Bitte gebe an, für wieviel Gold Du Dich verkaufen möchtest und das Zimmer heissen soll.:<br><br>",true);output("<table><tr><td>Kosten:</td><td>Zimmername:</td></tr>",true);output("<td valign=top><input name='kosten' size='15'></td>",true);output("<td valign=top><input name='raum' size='30'></td>",true);// output("<td valign=top><input name='id' size='10'></td>",true);output("</table><input type='submit' value='Abschicken'></form>",true);addnav("","frdnhaus.php?op=send");output("`n");break;// ######################################################################################################// Absenden und Eintragen// ######################################################################################################    case "send";        $raum=mysqli_real_escape_string($mysqli, stripslashes($_POST['raum']));        $name=mysqli_real_escape_string($mysqli, stripslashes($session['user']['name']));if ($_POST["kosten"]<="20000" && $_POST['kosten']!="" && $_POST['raum']!=""){$sql = "INSERT INTO frdn (acctid,user,kosten,sex,raum) VALUES ('".$session['user']['acctid']."','{$name}','".$_POST['kosten']."','".$session['user']['sex']."','{$raum}')";$result = db_query($sql) or die(db_error(LINK));$id = db_insert_id(LINK);$session['user']['gold']-=$verw;$session['user']['room']=1;// $session['user']['house'] = $houseid;//$session['user']['roomid'] = $_POST['id'];$session['user']['roomid'] = $id;output("Erfolgreich eingetragen. Ihr müsst jetzt nurnoch warten, bis sich jemand meldet.");addnav("Zurück ins Dorf","village.php");}else if ($_POST["raum"]=="" || $_POST["kosten"]==""){    output("Bitte alle Felder ausfüllen.");    addnav("Zurück","frdnhaus.php?op=anbiet");}else if($_POST['kosten']>"20000"){output("Bitte einen Preis unter 20000 Gold wählen.");addnav("Zurück","frdnhaus.php?op=anbiet");addnav("Zurück ins Dorf","village.php");}break;// ######################################################################################################// 'Partner' auswählen// ######################################################################################################case "ver";output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Zimmername</td><td>Name</td><td>Kosten</td><td>Nummer</td></tr>",true);    $sql = "SELECT id,user,raum,kosten,sex FROM frdn"; // WHERE (sex <> ".$session[user][sex].")";    $row = db_fetch_assoc($result);    $result = db_query($sql) or die(db_error(LINK));if (db_num_rows($result)==0){    output("<tr class='trdark'><td colspan=5 align='center'>`&`iDerzeit bietet sich niemand an!`i`0</td></tr>",true);addnav("Zurück","frdnhaus.php");} else {    addnav("Doch lieber nicht","frdnhaus.php");while ($row = db_fetch_assoc($result)) {    $bgclass = ($bgclass=='trdark'?'trlight':'trdark');    output("<tr class='".$bgclass."'><td><a href='frdnhaus.php?op=getroom&id=".$row['id']."' onClick='return confirm(\"Willst du wirklich in dieses Zimmer?\");'>".$row['raum']."</a></td><td>".$row['user'],true);    output("</td><td>".$row['kosten']."</td><td>".$row['id']."</td>",true);    addnav("","frdnhaus.php?op=getroom&id=".$row['id']);}}output("</table>",true);output('</form>',true);break;// 'Partner' und Zimmer bekommen.    case "getroom";        $sql2 = "SELECT * FROM frdn WHERE id='$_GET[id]'";        $result2 = db_query($sql2);        $row = db_fetch_assoc($result2);        $gold = $row['kosten'];        $id = $row['id'];        $acctid = $row['acctid'];        if($session[user][gold]>=$row[kosten]){            output("Du kannst nun in das Zimmer.");            addnav("Zurück","frdnhaus.php");            $session['user']['roomid'] = $row['id'];            $session['user']['gold'] -= $gold;            $session['user']['room']=1;            $session['user']['frdnpartner'] = $row['acctid'];            //$sql = "UPDATE accounts SET goldinbank = goldinbank+$row[kosten] WHERE acctid='{$row['acctid']}'";            //db_query($sql) or die(db_error(LINK));            updateuser($row['acctid'],array('goldinbank'=>"+$row[kosten]"));            $sql = "UPDATE accounts SET frdnpartner = ".$session['user']['acctid']." WHERE acctid='{$row['acctid']}'";            db_query($sql) or die(db_error(LINK));                        $name=mysqli_real_escape_string($mysqli, stripslashes($session['user']['name']));            $sql9 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('`System`0','$row[acctid]','`^Freudenhaus!`0','`&{$name}`6 hat sich für ein Zimmer bei Dir beworben und Dir dafür den Preis in Höhe von ".$row['kosten']." Gold auf deine Bank überwiesen!',now())";            db_query($sql9);            $sql2="DELETE FROM frdn WHERE id='$_GET[id]'";            $result2=db_query($sql2);        }        else if($session[user][gold]<$row[kosten]){            output("Das kannst Du Dir garnicht leisten!");            addnav("Zurück","frdnhaus.php");        }    break;endswitch;page_footer();

