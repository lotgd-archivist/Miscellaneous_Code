<?php
 /**
  * Project: Penal record hack for Legend of the Green Dragon
  *
  * PHP Version 4 and 5
  *
  * Copyright (C) 2006 Thibaud Roth
  *
  * This program is free software; you can redistribute it and/or
  * modify it under the terms of the GNU General Public License
  * as published by the Free Software Foundation; either version 2
  * of the License, or (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program; if not, write to the Free Software
  * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
  *
  * @category   Browsergame
  * @package    Legend of the Green Dragon
  * @author     Thibaud Roth <thibaud.roth@betriebsdirektor.de>
  * @copyright  2006 Thibaud Roth
  * @license    http://www.fsf.org/licensing/licenses/gpl.txt GNU GPL Version 2
  * @version    1.2; 04102006
  * @link       http://thibaudroth.magicforrest.de/?lotgd/penal_record, http://www.kerhoat.de
  */
  
  /*Version von Rikkarda@silienta-logd.de geändert für die Server, wo der timestamp ein 1.01.1970 
  ausgeworfen hat*/
require_once "common.php";
$penal_record_type = array(1=>'Verwarnung wegen Spam', 2=>'Verwarnung wegen OOC auf Rollenspiel Plätzen.', 3=>'Verwarnung wegen ungehorsam den Admins gegenüber.', 4=>'Verwarnung wegen Beleidigung von Mitspielern', 5=>'Verwarnung wegen Fehler ausnutzung.', 6=>'Verwarnung wegen Sonstigem');
isnewday(2);
page_header("Strafregister");

addnav('G?Zurück zur Grotte','superuser.php');
addnav('W?Zurück zum Weltlichen','village.php');

switch($_GET['op']){
  case '':
   rawoutput('<b>Bisher verwarnte Spieler:</b><dl><br /><br />');
    $name = '0';
    $query = mysql_query('SELECT penal_record.id,penal_record.timestamp, penal_record.type, penal_record.remark, accounts.name FROM penal_record LEFT JOIN accounts USING(acctid) ORDER BY accounts.login');
    while($row=db_fetch_assoc($query)){
      if($name != $row['name']){
        rawoutput('</ul></dd><dt><b>'.$row['name'].'</b></dt><br />');
        $name = $row["name"];
        rawoutput('<dd><ul><li>'.date("d.m.Y",$row['timestamp']).': '.$penal_record_type[$row['type']]);
        if($row['remark']!='') output('(Bemerkung: '.$row['remark'].')');
        if($session['user']['superuser']>=4)rawoutput('[<a href="penal_record.php?op=delete&id='.$row['id'].'">Löschen</a>]</li>');
        if($session['user']['superuser']>=4)allownav("penal_record.php?op=delete&id=".$row['id']);
        }
      else{
        rawoutput('<li>'.date("d.m.Y",$row['timestamp']).': '.$penal_record_type[$row['type']]);
        if($row['remark']!='') rawoutput('(Bemerkung: '.$row['remark'].')');
        if($session['user']['superuser']>=4)rawoutput(' [<a href="penal_record.php?op=delete&id='.$row['id'].'">Löschen</a>]</li>');
        if($session['user']['superuser']>=4)allownav("penal_record.php?op=delete&id=".$row['id']);
        }
      }
    rawoutput('</ul></dd></dl>');
    addnav('Eintrag hinzufügen','penal_record.php?op=search');
    break;
    
  case 'search':
    rawoutput('<form action="penal_record.php?op=add" method="post"><label for="name">Charakter:</label> <input type="text" name="name" id="name"><input type="submit" value="Suchen"></form>');
    allownav("penal_record.php?op=add");
    break;
    
  case 'add':
  addnav('Zurück','penal_record.php');
    $query = db_query("SELECT acctid,name FROM accounts WHERE login LIKE '".mysql_real_escape_string($_POST['name'])."'");
    if (db_num_rows($query)<=0){
      output('Es wurde kein Charakter mit diesem Namen gefunden.');
      addnav('Zurück zum Strafregister','penal_record.php');
      }
    while($row = db_fetch_assoc($query)){
      output('`b'.$row['name'].':`b');   
	  rawoutput('<form action="penal_record.php?op=insert" method="post"><input type="hidden" name="acctid" value="'.$row['acctid'].'"><label for="type">Art des Eintrags:</label> <select name="type" id="type"><option value="1">'.$penal_record_type['1'].'</option><option value="2">'.$penal_record_type['2'].'</option><option value="3">'.$penal_record_type['3'].'</option><option value="4">'.$penal_record_type['4'].'</option><option value="5">'.$penal_record_type['5'].'</option><option value="6">'.$penal_record_type['6'].'</option></select><br /><label for="remark">Bemerkung:</label> <input type="text" name="remark" id="remark"> (optional) <br /><input type="submit" value="Eintragen"></form><br />');
      }
    allownav('penal_record.php?op=insert');
    break;
  
  case 'insert':
    $time = time();
    db_query("INSERT INTO penal_record (acctid, timestamp, type, remark, insertedby) VALUES ('".$_POST['acctid']."', '$time', '".$_POST['type']."', '".$_POST['remark']."', '".$session['user']['acctid']."')");
    if($_POST['type'] == '2' && $session['user']['superuser']>=4){
      $body = "Hallo,`n
        ich bitte dich OOC (Out of Charakter) an öffentlichen Plätzen zu unterlassen und nur noch rollenspielgerechtes zu schreiben.`n
        Schaue bei Fragen zum Spiel in den FAQ nach und wenn deine Fragen nicht geklärt werden, schicke eine Hilfeanfrage ab, oder mache dich im Forum kundig.`n
        Bei privaten Unterhaltungen von Spieler zu Spieler, verwende bitte die Ye Olde Mail-Funktion.`n
        Bei weiteren Vergehen, sehen wir uns gezwungen Maßnahmen zu ergreifen. Diese gehen von einer Prangerstrafe, über eine zeitweilige Sperrung bis hin zur Löschung des Accounts`n
        Mit Freundlichen Grüßen`n".$session['user']['name'];
      systemmail($_POST['acctid'],"Verwarnung wegen Spam",$body,$session['user']['acctid']);
      }else{
      rawoutput('Es wurde keine Ye Olde Mail abgeschickt. Wenn nötig muss diese noch manuell verschickt werden.<br />');
      }
    rawoutput('Die Verwarnung wurde eingetragen.');
   
    addnav("Strafregister","penal_record.php");
    break;
    
  case 'delete':
    rawoutput('Soll der Eintrag wirklich gelöscht werden?');
    addnav('Ja, löschen','penal_record.php?op=delete2&id='.(int)$_GET['id']);
    addnav('Nein, zurück','penal_record.php');
    break;

  case 'delete2':
    db_query('DELETE FROM penal_record WHERE `id` = '.(int)$_GET['id']);
    rawoutput('Erfolgreich gelöscht');
    addnav('Zurück','penal_record.php');
    break;
  }
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>