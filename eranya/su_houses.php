
<?php
/*
* Version:        25.04.2004
* Author:        anpera
* Email:                logd@anpera.de
*
* Purpose:        Admin tool for houses
*
* BETA !!
*
* Ok, lets do the code...
*/
// 5.5.05: Edit by tcb: Häuserstatus jetzt in common.php, zentrale Funktion, get_house_state($state)
// 07.08.05 Kompatibilität mit neuen Haustypen by Maris
// 25.08.06 Diverse Verbesserungen by Maris

define('SUHOUSESCOLORTEXT','`3');
define('SUHOUSESCOLORHEADER','`#');
define('SUHOUSESCOLORKEY','`#');

require_once("common.php");
require_once(LIB_PATH.'house.lib.php');

page_header("Hausmeister");

function disp_status()
{
        output("<ul>",true);
        output("`n".SUHOUSESCOLORTEXT."Häuserstatus:`n`n");
        for ($i=1;$i<=110;$i++)
        {
                output("`^".$i.": ".get_house_state($i,false)."`n");
        }
        output("</ul>",true);
}

if ($_GET[op]=="drin")
{

        if ($_GET[subop]=="delete")
        {
                $sql = "DELETE FROM commentary WHERE commentid='$_GET[commentid]'";
                db_query($sql);
        }

        addnav("Schlüssel hinzufügen","su_houses.php?op=keys&hid=$_GET[id]");
        addnav("Daten ändern","su_houses.php?op=data&id=$_GET[id]");
        addnav("Haus abreißen","su_houses.php?op=raze&id=$_GET[id]",false,false,false,false); // Leeres Grundstück erzeugen
        addnav("~~~");
        addnav("Haus löschen","su_houses.php?op=destroy&id=$_GET[id]",false,false,false,false); // Vorsicht!!
        addnav("~~~");
        addnav("Privaträume einsehen","su_houses.php?op=private&id=".$_GET['id']);
        addnav('Räume verwalten','su_houses.php?op=rooms_ext&id='.$_GET['id']);
        addnav('~~~');
        addnav("Zum Haus","inside_houses.php?id=".$_GET['id']);
        addnav("Hausmeister","su_houses.php");
        $sql="SELECT * FROM houses WHERE houseid=$_GET[id]";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`n".SUHOUSESCOLORTEXT."Name: `^`b$row[housename]`b`^ (Nr. `b$row[houseid]`b)");
        output("`n".SUHOUSESCOLORTEXT."Beschreibung: `^`b$row[desc_floor]`b");
        output("`n".SUHOUSESCOLORTEXT."Gold: `^`b$row[gold]`b / ");
        output("".SUHOUSESCOLORTEXT."Edelsteine: `^`b$row[gems]`b");
        output("`n".SUHOUSESCOLORTEXT."Status: `^`b".get_house_state($row['status'],false)."`b");

        $sql = "SELECT name FROM accounts WHERE acctid=$row[owner]";
        $result2 = db_query($sql);
        $row2  = db_fetch_assoc($result2);
        output("`^`n`@Besitzer: `^`b$row[owner]`b ($row2[name]`^)");

        output("`n`nKommentare:`n");

        viewcommentary("house-".$_GET['id']."-floor",'Kommentare überwachen',100,'');

        output("`n`n".SUHOUSESCOLORTEXT."Schlüssel: `^`n");
        output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nr.</td><td>Owner ID (Name)</td><td>Hausnr</td><td>Nr. (DB)</td><td>gebraucht?</td><td>Ops</td></tr>",true);
        $sql = "SELECT *,accounts.acctid, accounts.name FROM keylist LEFT JOIN accounts ON accounts.acctid=keylist.owner WHERE keylist.value1=$row[houseid] ORDER BY keylist.value2 ASC,keylist.id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        for ($i=1;$i<=db_num_rows($result);$i++)
        {
                $item = db_fetch_assoc($result);
                output("<tr><td>`b$i`b</td><td>".($item['acctid']?"$item[acctid] ($item[name])":"0 (`4Verloren`0)")."</td><td>$item[value1]</td><td>$item[value2]</td><td>$item[hvalue]</td><td>",true);
                if ($item['acctid'] == 0 && $row['owner'] != 0)
                {
                        output("<a href='su_houses.php?op=keys&subop=change&hid=$_GET[id]&kid=".$item['id']."&id2=$i&owner=$row[owner]'>Reset</a> | ",true);
                        addnav("","su_houses.php?op=keys&subop=change&hid=$_GET[id]&kid=".$item['id']."&id2=$i&owner=$row[owner]");
                }
                output("<a href='su_houses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]'>Edit</a> | <a href='su_houses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]' onClick=\"return confirm('Diesen Schlüssel wirklich löschen?');\">Löschen</a>",true);
                addnav("","su_houses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]");
                addnav("","su_houses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]");
                output("</td></tr>",true);

        }
        output("</table>`n",true);

}

// Privatgemacherweiterung
else if ($_GET['op'] == 'private')
{

        $hid = (int)$_GET['id'];

        $sql = 'SELECT h.*,a.name AS ownername FROM houses h LEFT JOIN accounts a ON a.acctid=h.owner WHERE h.houseid='.$hid;
        $res = db_query($sql);
        $house = db_fetch_assoc($res);

        output('`c`b`&Privatgemächer in '.$house['housename'].'`& (Nr. '.$hid.')`b`c`n`n');

        output(''.SUHOUSESCOLORTEXT.'Privatgemach des Hausbesitzers '.$house['ownername'].'`@:`n');
        output($house['private_description'].'`@`n`n');
        viewcommentary("h".$hid."-".$house['owner']."privat",'Kommentare überwachen',100,'');

        $sql = 'SELECT i.*,a.name AS ownername FROM items i LEFT JOIN accounts a ON a.acctid=i.owner WHERE i.name="'.HOUSES_PRIVATE_OI_NAME.'" AND value1='.$hid;
        $res = db_query($sql);

        while($p = db_fetch_assoc($res))
        {
                if($p['owner'] != $house['owner'])
                {
                        output(''.SUHOUSESCOLORTEXT.'`c------------------`c`n'.$p['ownername'].': `@`n'.$p['description'].'`@`n');
                        viewcommentary("h".$hid."-".$p['owner']."privat",'Kommentare überwachen',100,'');
                }
        }

        addnav('Zurück zu Haus '.$hid,'su_houses.php?op=drin&id='.$hid);
        addnav("Hausmeister","su_houses.php");

}
// Kontrolle der Räume
else if ($_GET['op'] == 'rooms_ext')
{
        // Button-Einfärbung
        $str_style_add = "style='background-color: #002f00;
                                 border-top: 1px solid #004f00;
                                 border-left: 1px solid #004f00;
                                 border-right: 1px solid #001f00;
                                 border-bottom: 1px solid #001f00;'";
        $str_style_del = "style='background-color: #3f0000;
                                 border-top: 1px solid #5f0000;
                                 border-left: 1px solid #5f0000;
                                 border-right: 1px solid #1f0000;
                                 border-bottom: 1px solid #1f0000;'";
        // end
        $str_baselink = 'su_houses.php?op=rooms_ext&id='.$_GET['id'];
        switch(isset($_GET['what']) ? $_GET['what'] : '')
        {
                // Räume-Details auflisten    clyrax
                case '':
                    $hsql = db_query('SELECT * FROM houses_rooms WHERE houseid = '.$_GET['id']);
                    while($hrow = db_fetch_assoc($hsql)) {
                        $arr_available_rooms_floors[$hrow['roomid']] = array('desc'=>$hrow['desc'],'floor'=>$hrow['floor'],'extra_info'=>$hrow['extra_info']);
                    }
                    output(SUHOUSESCOLORTEXT."`c`bRäume-Übersicht (Haus Nr. ".$_GET['id']."):`b`n`n
                            <table style='background-color: #555555;' cellpadding='10'>
                            <tr class='trhead' style='text-align: center;'><td>`bRaumname`b</td><td>`bBeschreibung`b</td><td>`bStockwerk`b</td><td>`bAktion`b</td></tr>");
                    $i = 0;
                    foreach($arr_rooms_names AS $k=>$v) {
                        output("<tr class='".($i%2 ? "trlight" : "trdark")."'>
                                <td style='vertical-align: top;'>".$v."</td>");
                        if(array_key_exists($k,$arr_available_rooms_floors)) {
                            output("<td>".$arr_available_rooms_floors[$k]['desc']."</td>
                                    <td style='text-align: center;'>".$arr_floors_names[$arr_available_rooms_floors[$k]['floor']]);
                            if($k == 'ownroom') {
                                output("<hr>Eigener Name: ".$arr_available_rooms_floors[$k]['extra_info']."");
                            }
                            output("</td>
                                    <td style='text-align: center;'>
                                    <a href='".$str_baselink."&what=edit&roomid=".$k."' class='button'>Bearbeiten</a>`n`n
                                    <a href='".$str_baselink."&what=del&roomid=".$k."' class='button' ".$str_style_del." onClick='return confirm(\"Soll der Raum wirklich entfernt werden? Alle Daten gehen verloren!\");'>Entfernen</a>
                                    </td>
                                    </tr>");
                            addnav('',$str_baselink.'&what=edit&roomid='.$k);
                            addnav('',$str_baselink.'&what=del&roomid='.$k);
                        } else {
                            output("<td style='text-align: center;'>---</td><td style='text-align: center;'>---</td>
                                    <td style='text-align: center;'><a href='".$str_baselink."&what=add&roomid=".$k."' class='button' ".$str_style_add.">Hinzufügen</a>");
                            addnav('',$str_baselink.'&what=add&roomid='.$k);
                        }
                        output("</td></tr>");
                        $i++;
                    }
                    output("</table>`c");
                break;
                // Raum bearbeiten
                case 'edit':
                    $hsql = db_query('SELECT * FROM houses_rooms WHERE houseid = '.$_GET['id'].' AND roomid = "'.$_GET['roomid'].'"');
                    $hrow = db_fetch_assoc($hsql);
                    $str_roomid = $hrow['roomid'];
                    if($_GET['save'] == 1) {
                        $str_desc = strip_tags(closetags($_POST['desc'],'`c`i`b'),'<img>');
                        if($_GET['roomid'] == 'ownroom') {
                            $str_extra_info = strip_appoencode(strip_tags(soap($_POST['extra_info'])),2);
                            if(strlen(strip_appoencode($_POST['extra_info'],3)) < 3) {  # Name lang genug?
                                output("`^Der Name des individuellen Raums sollte mindestens 3 Zeichen lang sein!`n`n");
                                addnav('Zur Übersicht',$str_baselink);
                            } elseif(strlen(strip_appoencode($str_extra_info,3)) > 25) {   # Name zu lang?
                                output("`^Der Name des individuellen Raums ist länger als die erlaubten 25 Zeichen!`n`n");
                                addnav('Zur Übersicht',$str_baselink);
                            } else {
                                db_query('UPDATE houses_rooms SET floor = '.$_POST['floor'].', `desc` = "'.$str_desc.'", extra_info = "'.$str_extra_info.'" WHERE houseid = '.$_GET['id'].' AND roomid = "ownroom"');
                            }
                        } else {
                            db_query('UPDATE houses_rooms SET floor = '.$_POST['floor'].', `desc` = "'.$str_desc.'" WHERE houseid = '.$_GET['id'].' AND roomid = "'.$str_roomid.'"');
                        }
                        redirect($str_baselink);
                    } else {
                        output(SUHOUSESCOLORTEXT."`b".$arr_rooms_names[$str_roomid]." in Haus Nr. ".$hrow['houseid'].":`b`n
                                `n
                                <form action='".$str_baselink."&what=edit&save=1&roomid=".$str_roomid."' method='post'>");
                        $str_floors_select = '';
                        foreach($arr_floors_names AS $k=>$v) {
                            $str_floors_select .= $k.','.$v.',';
                        }
                        $str_floors_select = substr($str_floors_select,0,-1);
                        $form = array('floor'=>'Stockwerk:,select,'.$str_floors_select,'desc'=>'Beschreibung:,textarea,60,6,1000');
                        $data = array('floor'=>$hrow['floor'],'desc'=>$hrow['desc']);
                        if($str_roomid == 'ownroom') {
                            $form['extra_info'] = 'Eigener Name:';
                            $data['extra_info'] = $hrow['extra_info'];
                        }
                        showform($form,$data);
                        output("</form>");
                        addnav('',$str_baselink.'&what=edit&save=1&roomid='.$str_roomid);
                    }
                break;
                // Raum hinzufügen
                case 'add':
                    db_query('INSERT INTO houses_rooms (houseid,roomid) VALUES ('.$_GET['id'].',"'.$_GET['roomid'].'")');
                    redirect($str_baselink);
                break;
                // Raum löschen
                case 'del':
                    db_query('DELETE FROM houses_rooms WHERE houseid = '.$_GET['id'].' AND roomid = "'.$_GET['roomid'].'"');
                    redirect($str_baselink);
                break;
                default:
                    output('`wHuch! Wie kommst du denn hierher? Solltest du nicht eigentlich ganz woanders sein?`n`n');
                    output('`9(Anmerkung: Solltest du durch die \'Letzten Post löschen\'-Funktion hierher gelangt sein,
                            ist das kein Fehler, sondern beabsichtigt.)`n`n');
                break;
        }
        addnav('Hausmeister');
        addnav('Raumliste',$str_baselink);
        addnav('Zum Haus','su_houses.php?op=drin&id='.$_GET['id']);
}
else if ($_GET[op]=="info")
{
        $sql="SELECT acctid,name,house,housekey FROM accounts WHERE house ORDER BY house ASC";
        output("<table cellpadding=2 align='center'><tr><td>`bacctid`b</td><td>`bName`b</td><td>`bhouse`b</td><td>`bhousekey`b</td><td>`bAktion`b</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0)
        {
                output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
        }
        else
        {
                for ($i=0;$i<db_num_rows($result);$i++)
                {
                        $row = db_fetch_assoc($result);
                        $link = 'su_houses.php?op=drin&id='.$row['house'];
                        addnav('',$link);
                        output("<tr><td align='center'>$row[acctid]</td><td>$row[name]</td><td>$row[house]</td><td>$row[housekey]</td><td>[ <a href=".$link.">Edit</a> ]</td></tr>",true);
                }
        }
        output("</table>",true);
        addnav("Hausmeister","su_houses.php");
}
else if ($_GET[op]=="raze")
{
        if ($_GET[subop]=="confirmed")
        {
                $sql="UPDATE houses SET owner=0,housename='Leere Fläche',gold=0,gems=0,status=6,desc_floor='' WHERE houseid=$_GET[id]";
                db_query($sql);
                $sql="DELETE FROM keylist WHERE value1=$_GET[id]";
                db_query($sql);

                // Möbel
                item_set(' owner<1234567 AND deposit1='.$_GET['id'], array('deposit1'=>0,'deposit2'=>0) );

                // Einladungen in Privatgemächer + Besitzurkunden
                item_delete(' (tpl_id="prive" OR tpl_id="privb") AND value1='.$_GET['id']);

                $sql="UPDATE accounts SET house=0,housekey=0 WHERE house=$_GET[id]";
                db_query($sql);
                output("`@Haus abgerissen");
        }
        else
        {
                output("`b`\$Haus Nummer $_GET[id] zum unbebauten Grundstück machen und alle Schlüssel wirklich löschen?`b");
                addnav("Einreißen","su_houses.php?op=raze&subop=confirmed&id=$_GET[id]");
        }
        addnav("Hausmeister","su_houses.php");
}
else if ($_GET[op]=="destroy")
{
        if ($_GET[subop]=="confirmed")
        {
                $sql="DELETE FROM houses WHERE houseid=$_GET[id]";
                db_query($sql);
                $sql="DELETE FROM keylist WHERE value1=$_GET[id]";
                db_query($sql);

                // Möbel
                item_set(' owner<1234567 AND deposit1='.$_GET['id'], array('deposit1'=>0,'deposit2'=>0) );

                // Einladungen in Privatgemächer + Besitzurkunden
                item_delete(' (tpl_id="prive" OR tpl_id="privb") AND value1='.$_GET['id']);

                $sql="UPDATE accounts SET house=0,housekey=0 WHERE house=".$_GET['id'];
                db_query($sql);
                output("`@Haus gelöscht");
        }
        else
        {
                output("`b`\$Haus Nummer $_GET[id] aus der Datenbank entfernen und alle Schlüssel wirklich löschen?`b");
                addnav("Löschen","su_houses.php?op=destroy&subop=confirmed&id=$_GET[id]");
        }
        addnav("Hausmeister","su_houses.php");
}
else if ($_GET[op]=="newhouse")
{
        addnav("Hausmeister","su_houses.php");
        if ($_GET[subop]=="save")
        { // save new house
                if ($_POST[auto]=="true")
                { // check given data
                        $sql = "SELECT house,housekey FROM accounts WHERE acctid=".(int)$_POST['owner'];
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        if ($row[house]>0 && $_POST[owner])
                        {
                                output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht.");
                        }
                        else if (!$_POST[housename])
                        {
                                output("`\$Fehler: Du musst einen Namen für das Haus eingeben.");
                        }
                        else if ((int)$_POST[owner]<1 && (int)$_POST[status]<=1)
                        {
                                output("`\$Fehler: Für diesen Status ist ein Besitzer zwingend erforderlich.");
                        }
                        else
                        {
                                if ((int)$_POST[status]>1 && (int)$_POST[owner]>0)
                                {
                                        output("`^Warnung: Diesem Status darf kein Besitzer zugeordnet werden. Besitzer auf 0 gesetzt.`n");
                                        $_POST[owner]="0";
                                }
                                output("".SUHOUSESCOLORTEXT."Neues Haus erstellt.`n");
                                if ((int)$_POST[houseid]==0)
                                {
                                        $sql = "INSERT INTO houses (owner,status,gold,gems,housename,desc_floor) VALUES (".(int)$_POST['owner'].",$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[desc_floor]')";
                                        db_query($sql);
                                        $sql = "SELECT houseid FROM houses WHERE owner=$_POST[owner] ORDER BY houseid DESC LIMIT 1";
                                        $result2 = db_query($sql) or die(db_error(LINK));
                                        $row2 = db_fetch_assoc($result2);
                                }
                                else
                                {

                                        $sql = "INSERT INTO houses (houseid,owner,status,gold,gems,housename,desc_floor) VALUES ($_POST[houseid],".(int)$_POST['owner'].",$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[desc_floor]')";
                                        db_query($sql);
                                        $sql = "SELECT houseid FROM houses WHERE owner=$_POST[owner] and houseid=$_POST[houseid] ORDER BY houseid DESC LIMIT 1";
                                        $result2 = db_query($sql) or die(db_error(LINK));
                                        $row2= db_fetch_assoc($result2);

                                }

                                if ($_POST[status]=="1" || $_POST[status]=="2" || $_POST[status]=="3")
                                {
                                        for ($i=1;$i<10;$i++)
                                        {
                                                $sql = "INSERT INTO keylist (owner,value1,value2,description) VALUES (".($_POST[status]=="1"?(int)$_POST['owner']:"0").",$row2[houseid],$i,'Schlüssel für Haus Nummer $row2[houseid]')";
                                                db_query($sql);
                                        }
                                        output("".SUHOUSESCOLORTEXT."Schlüssel in Datenbank eingetragen.`n");
                                }
                                if ($_POST[status]=="0" || $_POST[status]=="1")
                                {
                                        $sql="UPDATE accounts SET house=$row2[houseid],housekey=".($_POST[status]=="1"?"$row2[houseid]":"0")." WHERE acctid=".(int)$_POST['owner'];
                                        output("".SUHOUSESCOLORTEXT."Userdatenbank angepasst`n");
                                        db_query($sql);
                                }
                        }
                }
                else
                {
                        output("".SUHOUSESCOLORTEXT."Neues Haus erstellt.");
                        $sql = "INSERT INTO houses (owner,status,gold,gems,housename,desc_floor) VALUES (".(int)$_POST['owner'].",$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[desc_floor]')";
                        db_query($sql);
                }
        }
        else
        {
                output("".SUHOUSESCOLORTEXT."Neues Haus anlegen:`n`n");
                output("`0<form action=\"su_houses.php?op=newhouse&subop=save\" method='POST'>",true);
                output("<table><tr><td>Name </td><td><input name='housename' maxlength='80'></td></tr>",true);
                output("<tr><td>Gold </td><td><input type='text' name='gold' value='0'> </td></tr>",true);
                output("<tr><td>Edelsteine </td><td><input type='text' name='gems' value='0'></td></tr>",true);
                output("<tr><td>Beschreibung </td><td><input type='text' name='desc_floor' maxlength='1000'></td></tr>",true);
                output("<tr><td>Status </td><td><input type='text' name='status' value='2'></td></tr>",true);
                output("<tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='0'> `4(VORSICHT!)`0</td></tr>",true);
                output("<tr><td>`4Haus-ID`0 </td><td><input type='text' name='houseid' value='0'> `4(VORSICHT!)`0</td></tr>",true);
                output("<tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n",true);
                output("<input type='submit' class='button' value='Speichern'></form>",true);
                output("`0`n`nIm unsicheren Modus Haus auch im User-Editor beim Besitzer eintragen! Status berücksichtigen! Schlüsselverwaltung!`n");
                output("`0House-ID = 0 -> automatische Zuteilung ans Listenende, `4VORSICHT! Andere ID-Werte können bestehende Häuser überschreiben!!`0`n");
                disp_status();
                addnav("","su_houses.php?op=newhouse&subop=save");
        }

}
else if ($_GET[op]=="keys")
{
        addnav("Hausmeister","su_houses.php");
        addnav("Zurück zu Haus $_GET[hid]","su_houses.php?op=drin&id=$_GET[hid]");

        $sql = 'SELECT owner FROM houses WHERE houseid='.$_GET['hid'];
        $res = db_query($sql);
        $house = db_fetch_assoc($res);

        if ($_GET[subop]=="change")
        { // reset key owner

                $sql = 'SELECT owner FROM keylist WHERE id='.$_GET['kid'];
                $res = db_query($sql);
                $old = db_fetch_assoc($res);

                $sql="UPDATE keylist SET owner=".(int)$_GET['owner']." WHERE id=".$_GET['kid'];
                db_query($sql);

                if($old['owner'] > 0)
                {        // wenn Schlüssel davor jemandem gehört hat

                        // Evtl. Einladungen in dessen Privatgemach löschen
                        item_delete(' tpl_id="prive" AND value1='.$_GET['hid'].' AND value2='.$old['owner']);

                        // Privatgemach zurücksetzen
                        item_set(' tpl_id="privb" AND value1='.$_GET['hid'].' AND owner='.$old['owner'], array('owner'=>$house['owner']) );

                        // Evtl. Möbel für dessen Privatgemach zurücksetzen
                        item_set(' owner < 1234567 AND deposit1='.$_GET['hid'].' AND deposit2='.$old['owner'], array('deposit1'=>0,'deposit2'=>0) );

                }

                output("".SUHOUSESCOLORTEXT."Schlüssel ".SUHOUSESCOLORKEY."$_GET[id2]".SUHOUSESCOLORTEXT." für Haus Nummer ".SUHOUSESCOLORKEY."$_GET[hid]".SUHOUSESCOLORTEXT." zurückgesetzt.");
        }
        else if ($_GET[subop]=="edit")
        { // enter new values for key
                $sql = "SELECT * FROM keylist WHERE id=$_GET[id]";
                $result = db_query($sql) or die(db_error(LINK));
                $item = db_fetch_assoc($result);
                output("".SUHOUSESCOLORTEXT."Schlüssel Nr. $item[value2] (item-ID $_GET[id]) für Haus $_GET[hid] bearbeiten:`n`n");
                output("`0<form action=\"su_houses.php?op=keys&subop=edit2&id=".$item['id']."&hid=$_GET[hid]\" method='POST'>",true);
                output("<table>",true);
                output("<tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='$item[owner]'></td></tr>",true);
                // output("<tr><td>Für Haus Nr. (value1) </td><td><input type='text' name='value1' value='$item[value1]'></td></tr>",true); // to change house delete the key and add a new key in other house
                output("<tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='$item[hvalue]'></td></tr>",true);
                output("<tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2' value='$item[value2]'> `4(VORSICHT!)`0</td></tr>",true);
                output("</table>`n",true);
                output("<input type='submit' class='button' value='Speichern'></form>",true);
                output("`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.");
                addnav("","su_houses.php?op=keys&subop=edit2&id=".$item['id']."&hid=$_GET[hid]");
        }
        else if ($_GET[subop]=="edit2")
        { // save new values into DB
                $sql = "SELECT * FROM keylist WHERE id=$_GET[id]";
                $result = db_query($sql) or die(db_error(LINK));
                $item = db_fetch_assoc($result);
                $action=true;
                if ((int)$_POST[value2]!=(int)$item[value2])
                {
                        $sql = "SELECT id FROM keylist WHERE value1=$_GET[hid] AND value2=$_POST[value2]";
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        if ($row[id])
                        {
                                output("`\$Fehler: Diese ID ist bereits vergeben.");
                                $action=false;
                        }
                        else
                        {
                                $action=true;
                        }
                }
                if ((int)$item[owner]!=(int)$_POST[owner])
                {
                        $sql = "SELECT acctid FROM accounts WHERE acctid=".(int)$_POST['owner'];
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        if (!$row[acctid])
                        {
                                output("`\$Fehler: Der User existiert nicht.");
                                $action=false;
                        }
                        else
                        {
                                $action=true;
                        }
                }
                if ($action)
                {
                        $sql = "UPDATE keylist SET owner=".(int)$_POST['owner'].",value2=$_POST[value2],hvalue=$_POST[hvalue] WHERE id=$_GET[id]";
                        db_query($sql);

                        if($row['acctid'] && $item['owner'] > 0 && $item['owner'] != $house['owner'])
                        {
                                // Evtl. Einladungen in dessen Privatgemach löschen
                                item_delete(' tpl_id="prive" AND value1='.$_GET['hid'].' AND value2='.$item['owner']);

                                // Privatgemach zurücksetzen
                                item_set(' tpl_id="privb" AND value1='.$_GET['hid'].' AND owner='.$item['owner'], array('owner'=>0) );

                                // Evtl. Möbel für dessen Privatgemach zurücksetzen
                                item_set(' owner<1234567 AND deposit1='.$_GET['hid'].' AND deposit2='.$item['owner'], array('deposit1'=>0,'deposit2'=>0) );
                        }

                        output("".SUHOUSESCOLORTEXT."Änderungen übernommen.");
                }
        }
        else if ($_GET[subop]=="savenew")
        { // save new key
                if ($_POST[value2])
                {
                        $sql = "SELECT value1,value2 FROM keylist WHERE value2=$_POST[value2] AND value1=$_GET[hid]";
                        $result = db_query($sql) or die(db_error(LINK));
                        $item = db_fetch_assoc($result);
                        $sql="SELECT acctid FROM accounts WHERE acctid=".(int)$_POST['owner'];
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                }
                if (!$_POST[value2])
                {
                        output("`\$Fehler: Du musst eine Schlüssel-ID angeben");
                }
                else if ((int)$item[value2]==(int)$_POST[value2])
                {
                        output("`\$Fehler: Diese ID ist bereits vergeben.");
                }
                else if (!$row[acctid])
                {
                        output("`\$Fehler: Der User existiert nicht.");
                }
                else
                {
                        $sql = "INSERT INTO keylist (owner,value1,value2,hvalue,description) VALUES (".(int)$_POST['owner'].",$_GET[hid],$_POST[value2],$_POST[hvalue],'Schlüssel für Haus Nummer $_GET[hid]')";
                        db_query($sql);
                        output("".SUHOUSESCOLORTEXT."Schlüssel eingetragen.");
                }
        }
        else if ($_GET[subop]=="delete")
        { // delete key

                $sql = "SELECT * FROM keylist WHERE id=$_GET[id]";
                $result = db_query($sql) or die(db_error(LINK));
                $item = db_fetch_assoc($result);

                output("".SUHOUSESCOLORTEXT."Schlüssel gelöscht.");
                $sql = "DELETE FROM keylist WHERE id=$_GET[id]";
                db_query($sql);

                if($item['owner'] > 0)
                {
                        // Evtl. Einladungen in dessen Privatgemach löschen
                        item_delete(' tpl_id="prive" AND value1='.$item['value1'].' AND value2='.$item['owner']);

                        // Privatgemach zurücksetzen
                        item_set(' tpl_id="privb" AND value1='.$item['value1'].' AND owner='.$item['owner'], array('owner'=>0) );

                        // Evtl. Möbel für dessen Privatgemach zurücksetzen
                        item_set(' owner < 1234567 AND deposit1='.$item['value1'].' AND deposit2='.$item['owner'], array('deposit1'=>0,'deposit2'=>0) );
                }

        }
        else
        { // enter new key
                output("".SUHOUSESCOLORTEXT."Neuen Schlüssel für Haus $_GET[hid] anlegen:`n`n");
                output("`0<form action=\"su_houses.php?op=keys&subop=savenew&hid=$_GET[hid]\" method='POST'>",true);
                output("<table>",true);
                output("<tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='0'></td></tr>",true);
                output("<tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='0'></td></tr>",true);
                output("<tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2'> `4(VORSICHT!)`0</td></tr>",true);
                output("</table>`n",true);
                output("<input type='submit' class='button' value='Speichern'></form>",true);
                output("`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.");
                addnav("","su_houses.php?op=keys&subop=savenew&hid=$_GET[hid]");
        }
}
else if ($_GET[op]=="data")
{
        addnav("Hausmeister","su_houses.php");
        addnav("Zurück zu Haus $_GET[id]","su_houses.php?op=drin&id=$_GET[id]");
        if ($_GET[subop]=="save")
        { // save values
                $action=false;
                if ($_POST[auto]=="true")
                { // check given data
                        $sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        $sql = "SELECT house,housekey FROM accounts WHERE acctid=".(int)$_POST['owner'];
                        $result2 = db_query($sql) or die(db_error(LINK));
                        $row2 = db_fetch_assoc($result2);
                        if ($row2[house]!=$_GET[id] && $row2[house]>0)
                        {
                                output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht. Datenbank nicht aktualisiert.");
                        }
                        else if ($row[status]!=$_POST[status] && $row[owner]!=$_POST[owner])
                        {
                                output("`\$Fehler: Status und Besitzer können im sicheren Modus nicht gleichzeitig geändert werden. Datenbank nicht aktualisiert.");
                        }
                        else
                        {
                                if ($row[owner]!=$_POST[owner] && ($_POST[status]=="3" || $_POST[status]=="4"))
                                {
                                        $_POST[status]="0";
                                        output("`^Warnung: Status dieses Hauses lässt keinen Besitzer zu. Status auf 0 (im Bau) gesetzt.`n");
                                }
                                if ($row[status]!=$_POST[status] && (int)$_POST[status]>2 && (int)$_POST[owner]>0)
                                {
                                        $_POST[owner]="0";
                                        output("`^Warnung: Dieser Statuswechsel lässt keinen Besitzer zu. Besitzer auf 0 gesetzt.`n");
                                }
                                if ($row[status]!=$_POST[status] && $row[owner]==0 && (int)$_POST[status]<3)
                                {
                                        output("`^Warnung: Dieser Status erfordert einen Besitzer! Bitte unbedingt einen Besitzer zuordnen!`n");
                                }
                                $action=true;
                                if ((int)$_POST[status]!=(int)$row[status])
                                {
                                        if ($_POST[status]=="0" || $_POST[status]=="4")
                                        {
                                                $sql="DELETE FROM keylist WHERE value1=$_GET[id]";
                                                db_query($sql);
                                                $house=0;
                                                if ($_POST[status]=="0") $house=$_GET[id];
                                                $housekey=0;
                                                output("`@Schlüssel aus Datenbank gelöscht`n");
                                        }
                                        if ($_POST[status]=="3" && $row[status]!=4 && $row[status]!=0)
                                        {
                                                $house=0;
                                                $housekey=0;
                                                $sql="UPDATE keylist SET owner=0 WHERE owner=$row[owner] AND value1=$_GET[id]";
                                                db_query($sql);
                                                output("`@Nicht vergebene Schlüssel zurückgesetzt`n");
                                        }
                                        else if ($_POST[status]=="3")
                                        {
                                                $house=0;
                                                $housekey=0;
                                                for ($i=1;$i<10;$i++)
                                                {
                                                        $sql = "INSERT INTO keylist (owner,value1,value2,description) VALUES (0,$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
                                                        db_query($sql);
                                                }
                                                output("".SUHOUSESCOLORTEXT."Schlüssel in Datenbank eingetragen`n");
                                        }
                                        if ($_POST[status]=="1" && ($row[status]==0 || $row[status]==4))
                                        {
                                                for ($i=1;$i<10;$i++)
                                                {
                                                        $sql = "INSERT INTO keylist (owner,value1,value2,description) VALUES (".(int)$_POST['owner'].",$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
                                                        db_query($sql);
                                                }
                                                $house=$_GET[id];
                                                $housekey=$_GET[id];
                                                output("".SUHOUSESCOLORTEXT."Schlüssel in Datenbank eingetragen`n");
                                        }
                                        else if ($_POST[status]=="1")
                                        {
                                                $sql="UPDATE keylist SET owner=".(int)$_POST['owner']." WHERE owner=0 AND value1=$_GET[id]";
                                                db_query($sql);
                                                $house=$_GET[id];
                                                $housekey=$_GET[id];
                                        }
                                        if ($_POST[status]=="2" && ($row[status]==0 || $row[status]==4))
                                        {
                                                for ($i=1;$i<10;$i++)
                                                {
                                                        $sql = "INSERT INTO keylist (owner,value1,value2,description) VALUES (0,$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
                                                        db_query($sql);
                                                }
                                                $house=$_GET[id];
                                                $housekey=$_GET[id];
                                                output("".SUHOUSESCOLORTEXT."Schlüssel in Datenbank eingetragen`n");
                                        }
                                        else if ($_POST[status]=="2")
                                        {
                                                $sql="UPDATE keylist SET owner=0 WHERE value1=$_GET[id]";
                                                db_query($sql);
                                                $house=$_GET[id];
                                                $housekey=0;
                                        }
                                        if(!isset($house) || !isset($housekey))
                                        {
                                                $house=(int)$_GET['id'];
                                                $housekey=(int)$_GET['id'];
                                        }
                                        $sql="UPDATE accounts SET house=$house,housekey=$housekey WHERE acctid=$row[owner]";
                                        db_query($sql);
                                }
                                else
                                {
                                        $sql="UPDATE accounts SET house=0,housekey=0 WHERE acctid=$row[owner]";
                                        db_query($sql);
                                        if ($_POST[status]=="1")
                                        {
                                                $housekey=$_GET[id];
                                        }
                                        else
                                        {
                                                $housekey=0;
                                        }
                                        $sql="UPDATE accounts SET house=$_GET[id],housekey=$housekey WHERE acctid=".(int)$_POST['owner'];
                                        db_query($sql);
                                        $sql="UPDATE keylist SET owner=".(int)$_POST['owner']." WHERE owner=$row[owner] AND value1=$_GET[id]";
                                        db_query($sql);

                                }
                        }
                }
                else
                {
                        $action=true;
                }
                if ($action)
                {
                        output("".SUHOUSESCOLORTEXT."Daten gespeichert.");
                        $sql="UPDATE houses SET owner=".(int)$_POST['owner'].",housename='".addslashes(rawurldecode($_POST['housename']))."',gold=$_POST[gold],gems=$_POST[gems],status=$_POST[status],desc_floor='".addslashes(rawurldecode($_POST[desc_floor]))."' WHERE houseid=$_GET[id]";
                        db_query($sql);
                }
        }
        else
        {
                $sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                output("".SUHOUSESCOLORTEXT."Daten für Haus `b$_GET[id]`b ändern:`n`n");
                output("`0<form action=\"su_houses.php?op=data&subop=save&id=$_GET[id]\" method='POST'>",true);
                output("<table><tr><td>Name </td><td><input name='housename' maxlength='80' value='",true);
                rawoutput($row[housename]);
                output("'></td></tr>",true);
                output("<tr><td>Gold </td><td><input type='text' name='gold' value='$row[gold]'> </td></tr>",true);
                output("<tr><td>Edelsteine </td><td><input type='text' name='gems' value='$row[gems]'></td></tr>",true);
                output("<tr><td>Beschreibung </td><td><input type='text' name='desc_floor' maxlength='1000' value='".(rawurlencode($row[desc_floor]))."'></td></tr>",true);
                output("<tr><td>`4Status`0 </td><td><input type='text' name='status' value='$row[status]'> `4(VORSICHT!)`0</td></tr>",true);
                output("<tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='$row[owner]'> `4(VORSICHT!)`0</td></tr>",true);
                output("<tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n",true);
                output("<input type='submit' class='button' value='Speichern'></form>",true);
                output("`0`n`nDaten, die nicht geändert werden sollen, `bnicht`b verändern!`nStatusänderung kann Auswirkungen auf die Schlüsselverwaltung haben!`nBesitzer- und Statusänderungen müssen im unsicheren Modus manuell übertragen werden!`n");
                addnav("","su_houses.php?op=data&subop=save&id=$_GET[id]");
                disp_status();
        }
}
else
{
        $anzahl_max = getsetting('maxhouses',300);

        $sql = "SELECT COUNT(houseid) AS c FROM houses h";
        $res = db_query($sql) or die(db_error(LINK));
        $houses = db_fetch_assoc($res) or die(db_error(LINK));

        output("".SUHOUSESCOLORHEADER."`b`cDas Wohnviertel`c`b`n`n");
        output("".SUHOUSESCOLORTEXT."Wähle das Haus (`b".$houses['c']."`b von `b".$anzahl_max."`b Plätzen bebaut):`n`n");
        output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bStatus`b</td></tr>",true);
        
        addnav('Hausmeister');
        addnav("User mit Haus","su_houses.php?op=info");
        addnav("Neues Haus","su_houses.php?op=newhouse");

        if ($houses['c']==0)
        {
                output("<tr><td colspan=3 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
        }
        else
        {
                $housesperpage = 30;
                $pageoffset = (int)$_GET['page'];
                $pageoffset = max(--$pageoffset,0);
                $pageoffset*=$housesperpage;
                $from = $pageoffset+1;
                $to = min($pageoffset+$housesperpage,$houses['c']);

                $limit= ' LIMIT '.$pageoffset.','.$housesperpage;

                addnav("Seiten");
                for ($i=0;$i<$houses['c'];$i+=$housesperpage)
                {
                        addnav("Seite ".($i/$housesperpage+1)." (".($i+1)."-".min($i+$housesperpage,$houses['c']).")","su_houses.php?page=".($i/$housesperpage+1));
                }

                $sql = "SELECT houseid,housename,status FROM houses WHERE 1 ORDER BY houseid ASC ".$limit;
                $result = db_query($sql) or die(db_error(LINK));

                for ($i=0;$i<db_num_rows($result);$i++)
                {
                        $row2 = db_fetch_assoc($result);
                        output("<tr><td align='center'>$row2[houseid]</td><td><a href='su_houses.php?op=drin&id=$row2[houseid]'>$row2[housename]</a></td><td>".get_house_state($row2['status'],false)."</td></tr>",true);
                        addnav("","su_houses.php?op=drin&id=$row2[houseid]");
                }
        }
        output("</table>",true);
}
addnav('Zurück');
addnav("Zurück zur Grotte","superuser.php");
addnav("Zurück zum Weltlichen",$session['su_return']);
output("`n<div align='right'>`)2004 by anpera</div>",true);
page_footer();
?>

