<?php
/* 
(c) 2007 by Passion de la glace alias Kamui
erstmals erschienen auf:
http://www.lotgd-zanarkand.com/logd/
Version: 1.2
*/

require_once 'common.php';
require_once './function/orte.php';
addcommentary();
//checkday();

/*
ToDo:

Führe folgende SQL Befehle in deinem phpmyadmin aus:
ALTER TABLE `accounts` ADD `rport` enum('0','1') NOT NULL default '0'; 

CREATE TABLE `rporte`(
 `id` int(11) unsigned NOT NULL auto_increment,
 `acctid` int(11) unsigned NOT NULL default '0',
 `acctname` varchar(50) NOT NULL default '', 
 `name` varchar(200) NOT NULL default '',
 `text` longtext NOT NULL,
 PRIMARY KEY (`id`(,
 KEY `acctid` (`acctid`(
)TYPE=MyISAM;

CREATE TABLE `ortebann`(
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(50) NOT NULL default '',
 `ort` int(11) unsigned NOT NULL default '0',
 `grund` text NOT NULL,
 PRIMARY KEY (`id`(,
 KEY `ort` (`ort`(
)TYPE=MyISAM;



Öffne setnewday.php und suche nach:

$sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
db_query($sql) or die(db_error(LINK));

füge danach ein:

$sql = 'DELETE FORM `rporte` WHERE acctid IN ('.$delaccts.')';
db_query($sql) or die (db_error(LINK));

speichern und schliessen.

Öffne user.php und suche nach:

"seendragon"=>"Phoenix heute gesucht,bool",

füge danach ein:

"rport"=>"Hat RP Ort erstellt,bool",


speichern und schliessen

öffne dragon.php und suche:

"acctid"=>1,

füge danach ein:

"rport"=>1,

speichern und schliessen.

füge in passender Stelle ein:

addnav('Die RP Orte','orte.php');


Falls textarea nicht in der function showform vorhanden ist baue sie endweder ein oder
suche überall nach:

'text'=>'Ortsbeschreibung,textarea,45,20'

und ersetze es gegebenden falls.

Falls du kein html erlauben willst suche:

output(''.CloseTags(removeEvilTags($ort['text']),'`n`c`b`i').'`n`n`n',true);

und ersetze es mit:

 output(''.CloseTags($ort['text'],'`n`c`b`i').'`n`n`n');
 



UPDATE auf Version 1.2.1

- Kleiner Bugfix in der function ShowPlaceCesc();
- Öffnen und schliessen der Orte für Admins

ToDo:
Führe
ALTER TABLE rporte ADD close enum('0','1') NOT NULL default '0';
in deinem phpmyadmin aus und schon fertig :P
*/




$acctid = $session['user']['acctid'];


page_header('Die RPG Orte');
switch($_GET['op']){
    default:
         ShowallPlaces();
    break;

/* Die Orte -start- */    
    case 'ort':
     
      ShowPlaceDesc(''.$_GET['id'].'',$session['user']['login']);
     
/*Besitzeroptionen -start- */     
       switch($_GET['editop']){
         case 'edit':
            $ortsinfo = array('name'=>'Name deines Ortes',
                          'text'=>'Ortsbeschreibung,textarea,45,20');
                          
              $select = "SELECT acctid,name,text FROM rporte WHERE id=".$_GET['id']."";
              $result = mysql_query($select) or die (db_error(LINK));
              $row = mysql_fetch_assoc($result);

                output("<form action='orte.php?op=ort&editop=save&id=".$_GET['id']."' method='POST'>",true);
                  addnav('','orte.php?op=ort&editop=save&id='.$_GET['id'].'');
                    output("<input type='submit' class='button' value='Speichern'>",true);
                    showform($ortsinfo,$row);
                  output("</form>", true);
                addnav('Zurück','orte.php?op=ort&id='.$_GET['id'].'');
         break;
         
         case 'save':
             $sql = 'UPDATE `rporte` SET
                            `name` = "'.$_POST['name'].'"
                           ,`text` = "'.$_POST['text'].'"
                                   WHERE
                             `id` = "'.(int)$_GET['id'].'"';
                 db_query($sql) or die (db_error(LINK));
                 redirect('orte.php?op=ort&id='.$_GET['id'].'');
         break;
         
         case 'delete':
           $sql = 'DELETE FROM `rporte` WHERE acctid='.$acctid.'';
             db_query($sql)or die (db_error(LINK));
               $sql = 'DELETE FROM `ortebann` WHERE ort='.$_GET['id'].'';
             db_query($sql)or die (db_error(LINK));    
           redirect('orte.php');
         break;
       }
       
         switch($_GET['act']){
            case 'bann':
              ViewPeoplebanns(''.$_GET['id'].'');
            break;
            
            case 'playerbann':
               output("<form action='orte.php?op=ort&act=save&id=".$_GET['id']."' method='POST'>",true);
                  $ort = array(
                     'id'=>'ID,hidden',
                     'name'=>'Loginname des Spielers',
                     'grund'=>'Grund des banns'
                     );
                     output('`c`bBannungen eintragen`c`b`n`n');
                      $row = 0;
                    showform($ort,$row);
                    output("</form>",true);
            addnav('','orte.php?op=ort&act=save&id='.$_GET['id'].'');
              
            break;
            
            case 'delete':
               $ort = db_fetch_assoc(db_query('SELECT id,ort FROM `ortebann` WHERE id='.$_GET['id'].''));
             
             $sql = 'DELETE FROM `ortebann` WHERE id='.$_GET['id'].'';
               db_query($sql) or die (db_error(LINK));
             redirect('orte.php?op=ort&act=bann&id='.$ort['ort'].'');  
            break;
            
       
       case 'save': 
          $sql = 'SELECT acctid,login,name FROM accounts WHERE login="'.$_POST['name'].'"';
          $result = db_query($sql) or die (db_error(LINK));
        if (db_num_rows($result)>0 && $session['user']['login']!=$_POST['name']){
          $row = db_fetch_assoc($result);
          
          $search = 'SELECT acctname,name FROM rporte WHERE id='.$_GET['id'].'';
          $result = db_query($search) or die (db_error(LINK));
          $ort = db_fetch_assoc($result);
          
          systemmail($row['acctid'],'`$Du wurdest von '.$ort['name'].' gebannt','Hallo '.$row['name'].' du wurdest von '.$ort['acctname'].' dem Ersteller des Ortes: '.$ort['name'].' gebannt. Ab nun hast du kein Zutritt mehr zu diesem Ort falls du den Grund wissen willst frage endweder nach oder schaue an dem Ort nach dort wird dir der Grund genannt.');
          
           $name = $_POST['name'];
          $ort = $_GET['id'];
          $grund = $_POST['grund'];
         $anlegen = "INSERT INTO `ortebann` (name,ort,grund) VALUES (\"$name\",\"$ort\",\"$grund\")";
         db_query($anlegen) or die (db_error(LINK));
         
          output('`n`n'.$row['name'].' ist von nun an von deinem Ort gebannt und kann ihn nicht mehr betreten');
         }else{
          //Eingetragender User ist nicht vorhanden oder der Besitzer sich selbst bannen will o.O wird kein DB Eintrag gemacht 
        if ($session['user']['login']==$_POST['name'])
          output('<h3>`$Du willst dich doch nicht selbst bannen oder etwa doch? o.O</h3>',true);
        else
          output('<h3>`$Dieser User exestiert nicht überprüfe noch einmal deine Eintraungen!</h3>',true);
          }
        addnav('Zurück nach Symia','orte.php?op=ort&act=bann&id='.$_GET['id'].'');
//        addnav("Zur Wegkreuzung","kreuzung.php");//Narjana nötig Zeile 218
       break;
        }
    break;
/* Besitzeroptionen -end-*/    
/* Die Orte -end- */

/* Ort erstellen für Spieler start-*/
    case 'erstellen':
        output("`c`\$Der Ort soll keinen vorhandenen Ort kopieren oder eine eigene Welt auf Dauer sein!`0`c");
        output("<form action='orte.php?op=save' method='POST'>",true);
                  $ort = array(
                     'name'=>'Name des Ortes',
                     'text'=>'Ortsbeschreibung,textarea,45,20'
                     );
                     $row = 0;
                    showform($ort,$row);
                    output("</form>",true);
                addnav('Zu den Orten','orte.php');
            addnav('','orte.php?op=save');
    break;
    
    case 'save':
     if ($_POST['name']==''){
      output('Du musst schon einen Namen angeben ansonsten klappt das nicht');
      }else{
       $login = $session['user']['login'];
       $name = $_POST['name'];
       $text = $_POST['text'];
       $anlegen = "INSERT INTO `rporte` (acctid,acctname,name,text) VALUES ($acctid,\"$login\",\"$name\",\"$text\")";
       db_query($anlegen)or die (db_error(LINK));
       output('`#Der Ort wurde angelegt. Du kannst ihn nun betreten und deine Texte und alles weitere editieren');
       $session['user']['rport'] = 1;
     }
       addnav('Zurück zu den Orten','orte.php');
    break;
/* Ort erstellen für Spieler */


/* SU Optionen -start- */    
    case 'admin':
       switch($_GET['suop']){
         default:
           ViewSUoptions(); 
         break;
      
/* SUoptionen editieren löschen und Tabellen leeren -start- */    
       case 'edit':
           $su_ortsinfo = array('id'=>'ID,viewonly',
                             'acctid'=>'BesitzerID,int',
                             'acctname'=>'Loginname des Besitzers',
                             'close'=>'RP Ort geschlossen?,bool',
                             'name'=>'Name des Ortes',
                             'text'=>'Ortsbeschreibung,textarea,20,20');
                          
              $select = "SELECT * FROM rporte WHERE id=".$_GET['id']."";
              $result = db_query($select) or die(db_error(LINK));
              $row = db_fetch_assoc($result);

                output("<form action='orte.php?op=admin&suop=save&id=".$_GET['id']."' method='POST'>",true);
                  addnav('','orte.php?op=admin&suop=save&id='.$_GET['id'].'');
                    output("<input type='submit' class='button' value='Speichern'>",true);
                    showform($su_ortsinfo,$row);
                  output("</form>", true);
                addnav('Zurück','orte.php?op=admin&suop=');
       break;
       
       case 'save':
          $sql = 'UPDATE `rporte` SET 
                         `acctid` = "'.(int)$_POST['acctid'].'" 
                        ,`acctname` = "'.$_POST['acctname'].'" 
                        ,`name` = "'.$_POST['name'].'"
                        ,`text` = "'.$_POST['text'].'"
                        ,`close` = "'.(int)$_POST['close'].'"
                                WHERE
                        `id` = "'.(int)$_GET['id'].'"';
       db_query($sql) or die (db_error($sql));
       redirect('orte.php?op=admin&suop=');
       break;
       
       case 'delete':
         $sql = 'DELETE FROM `rporte` WHERE id='.$_GET['id'].'';
           db_query($sql) or die (db_error(LINK));
             $sql = 'DELETE FROM `ortebann` WHERE ort='.$_GET['id'].'';
           db_query($sql) or die (db_error(LINK));
         redirect('orte.php?op=admin&suop=');
       break;
       
       case 'leeren':
         $sql = 'TRUNCATE TABLE `rporte`';
           db_query($sql) or die (db_error(LINK));
             $sql = 'TRUNCATE TABLE `ortebann`';
           db_query($sql) or die (db_error(LINK));
         redirect('orte.php?op=admin&suop=');
       break;
/* SUoptionen editieren löschen und Tabellen leeren -end- */       
       

/*Anlegen eines Ortes für SU -start-*/       
       case 'anlegen':
          output("<form action='orte.php?op=admin&suop=erstellen' method='POST'>",true);
                  $ort = array(
                     'id'=>'ID,hidden',
                     'acctid'=>'BesitzerID,int',
                     'acctname'=>'Loginname des Besitzers',
                     'name'=>'Name des Ortes',
                     'text'=>'Ortsbeschreibung,textarea,45,20'
                     );
                     $row = 0;
                    showform($ort,$row);
                    output("</form>",true);
                addnav('Zurück zur Übersicht','orte.php?op=admin&suop=');
            addnav('','orte.php?op=admin&suop=erstellen');
       break;
       
       case 'erstellen':
          $besitzer_id = $_POST['acctid'];
          $login = $_POST['acctname'];
          $name = $_POST['name'];
          $text = $_POST['text'];
       $anlegen = "INSERT INTO `rporte` (acctid,acctname,name,text) VALUES ($besitzer_id,\"$login\",\"$name\",\"$text\")";
       db_query($anlegen)or die (db_error(LINK));
          output('Der Ort wurde angelegt');
       addnav('Zurück zur Übersicht','orte.php?op=admin&suop=');
       
       break;
/*Anlegen eines Ortes für SU -end-*/         
       
       
     }
   break;
/* SU Optionen -end- */
}
//Zeile darf nicht entfernt werden!!
$copyright ="<div align='center'><a href=http://www.lotgd-zanarkand.com/logd/ target='_blank'>&copy; `b`#Passion de la glace`b`0</a></div>";
 output("$copyright`n ",true);
page_footer();
?>