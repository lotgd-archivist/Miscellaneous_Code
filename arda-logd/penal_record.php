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
require_once "common.php";
isnewday(1);
page_header("Strafregister");

addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

switch($_GET["op"]){
  case "":
    output("`bBisher verwarnte Spieler:`b<dl>",true);
    $name = "0";
    $query = mysql_query("SELECT penal_record.id,penal_record.timestamp, penal_record.type, penal_record.remark, accounts.name FROM penal_record LEFT JOIN accounts USING(acctid) ORDER BY accounts.login");
    while($row=db_fetch_assoc($query)){
      if($name != $row["name"]){
        output("</ul></dd>",true);
        output("<dt>`b".$row["name"]."`b</dt>",true);
        $name = $row["name"];
        output("<dd><ul><li>".date("j.m.Y",$row["timestamp"]).": ".$penal_record_type["".$row["type"].""]."",true);
        if($row["remark"]!="") output("(Bemerkung: ".$row["remark"].")");
        output(" [<a href=\"penal_record.php?op=delete&id=".$row["id"]."\">Löschen</a>]",true);
        addnav("","penal_record.php?op=delete&id=".$row["id"]);
        output("</li>",true);
        }
      else{
        output("<li>".date("j.m.Y",$row["timestamp"]).": ".$penal_record_type["".$row["type"].""]."",true);
        if($row["remark"]!="") output("(Bemerkung: ".$row["remark"].")");
        output(" [<a href=\"penal_record.php?op=delete&id=".$row["id"]."\">Löschen</a>]",true);
        addnav("","penal_record.php?op=delete&id=".$row["id"]);
        output("</li>",true);
        }
      }
    output("</ul></dd></dl>",true);
    addnav("Eintrag hinzufügen","penal_record.php?op=search");
    break;
    
  case "search":
    output("<form action=\"penal_record.php?op=add\" method=\"post\"><label for=\"name\">Charakter:</label> <input type=\"text\" name=\"name\" id=\"name\"><input type=\"submit\" value=\"Suchen\"></form>",true);
    addnav("","penal_record.php?op=add");
    break;
    
  case "add":
    $query = mysql_query("SELECT acctid,name FROM accounts WHERE login LIKE '".addslashes($_POST["name"])."'");
    if (db_num_rows($query)<=0){
      output("Es wurde kein Charakter mit diesem Namen gefunden.");
      addnav("Zurück zum Strafregister","penal_record.php");
      }
    while($row = db_fetch_assoc($query)){
      output("`b".$row["name"].":`b",true);
      output("<form action=\"penal_record.php?op=insert\" method=\"post\">",true);
      output("<input type=\"hidden\" name=\"acctid\" value=\"".$row["acctid"]."\">",true);
      output("<label for=\"type\">Art des Eintrags:</label> <select name=\"type\" id=\"type\"><option value=\"1\">".$penal_record_type["1"]."</option> <option value=\"2\">".$penal_record_type["2"]."</option></select>",true);
      output("<label for=\"remark\">Bemerkung:</label> <input type=\"text\" name=\"remark\" id=\"remark\"> (optional)",true);
      output("<input type=\"submit\" value=\"Eintragen\">",true);
      output("</form>",true);
      }
    addnav("","penal_record.php?op=insert");
    break;
  
  case "insert":
    mysql_query("INSERT INTO penal_record (acctid, timestamp, type, remark, insertedby) VALUES ('".$_POST["acctid"]."', '".time()."', '".$_POST["type"]."', '".$_POST["remark"]."', '".$session[user][acctid]."')");
    if($_POST["type"] == "1"){
      $body = "Hallo,`n
        ich bitte dich OOC (out of Charakter) an öffentlichen Plätzen zu unterlassen und nur noch rollenspielgerechtes zu schreiben.`n
        Schaue bei Fragen zum Spiel in den FAQ nach und wenn deine Fragen nicht geklärt werden, schicke eine Hilfeanfrage ab, oder mache dich im Forum kundig.`n
        Bei privaten Unterhaltungen von Spieler zu Spieler, verwende bitte die Ye Olde Mail-Funktion.`n
        Bei weiteren Vergehen, sehen wir uns gezwungen Maßnahmen zu ergreifen. Diese gehen von einer Geldstrafe, über eine Anprangerung bis hin zur Löschung des Accounts und Verbannung vom Server.`n
        MfG`nDie Spielleitung";
      systemmail($_POST['acctid'],"Verwarnung wegen Spam",$body,$session['user']['acctid']);
      }
    elseif($_POST["type"] == "2"){
      output("Es wurde keine Ye Olde Mail abgeschickt. Wenn nötig muss diese noch manuell verschickt werden.`n");
      }
    output("Die Verwarnung wurde eingetragen.");
    addnav("Strafregister","penal_record.php");
    break;
    
  case "delete":
    output("Soll der Eintrag wirklich gelöscht werden?");
    addnav("Ja, löschen","penal_record.php?op=delete2&id=".$_GET["id"]);
    addnav("Nein, zurück","penal_record.php");
    break;

  case "delete2":
    mysql_query("DELETE FROM penal_record WHERE `id` = ".$_GET["id"]);
    output("Erfolgreich gelöscht");
    addnav("Zurück","penal_record.php");
    break;
  }

page_footer();
?> 