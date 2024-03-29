
<?php
#-----------------------------------------#
#   Gildensystem Version: 1.5b            #
#   ~~ Gildeninnern ~~                    #
#   Autor: Eliwood, Serra                 #
#-----------------------------------------#

/* Require */
require_once "common.php";require_once "func/systemmail.php";require_once "lib/gilden.php";
require_once "lib/gildenverwaltfunc.php";

/* Gilde laden und speichern in $session['guild'] */
loadguild($session['user']['memberid']);

/* Maintitle */
$title = "Gildenhaus ".$session['guild']['gildenname_b']."";

/* Script */
switch($_GET['op']):
    /* Übersicht */
     case "":
         addcommentary();
        /* Seitentitel festlegen */
        page_title("Verwaltung der Gilde");
        output("`YHier kannst die komplette Gilde bearbeiten und verwalten.`nTexte bearbeiten, Ränge verteilen, Mitglieder belohnen... Es gibt viele Möglichkeiten.`n`n");
        viewcommentary("gildenverwaltung ".$session['guild']['gildenid'],"Unterhalte dich mit Leadern und Verwaltern",25,"sagt",1,1);
        break;

    /* Bewerbungen - Übersicht */
    case "bewerbungen":
        showbewerber();
        break;

    /*Massenmail*/
    case "mail":
        function MyNavs($NavName=false,$NavLink=false,$NavHeader=false){
            if($NavHeader!=false) addnav($NavHeader);
            if($NavName!=false && $NavLink!=false) addnav($NavName,$NavLink);
            addnav('Umkehren');
            addnav('Zurück','gildenverwalt.php');
        }

        if(empty($_POST['senden'])){
            if((!empty($_POST['mail']) || !empty($_POST['subject'])) && (empty($_POST['mail']) || empty($_POST['subject']))){
                output('<BIG><BIG>`n`4Achtung! `&Du musst alle Felder ausfüllen!`n`n`2</BIG></BIG>',true);    
            }
            $out .= '<form action="gildenverwalt.php?op=mail" method="POST">
                <table border="0" bgcolor="#999999" cellspacing="1" cellpadding="3">
                    <tr class="trhead">
                        <td colspan="2" align="center"><b>Massenmail verschicken</b></td>
                    </tr>
    
                    <tr class="trlight">
                        <td><b>An User</b></td>
                        <td><select name="users">
                            <option '.($_POST['users'] == 1 ? 'selected="selected"' : '').' value="1">Alle Mitglieder</option>
                            <option '.($_POST['users'] == 2 ? 'selected="selected"' : '').' value="2">Keine Verwalter</option>
                            <option '.($_POST['users'] == 3 ? 'selected="selected"' : '').' value="3">Nur Verwalter</option>
                        </select></td>
                    </tr>
                
                    <tr class="trdark">
                        <td><b>Betreff</b></td>
                        <td><input name="subject" value="'.stripslashes($_POST['subject']).'"></td>
                    </tr>
                
                    <tr class="trdark">
                        <td><b>Text</b></td>
                        <td><textarea name="mail" class="input" cols="60" rows="8">'.stripslashes($_POST['mail']).'</textarea></td>
                    </tr>
                
                    <tr class="trlight">
                        <td colspan="2" align="center">
                            <input type="submit" class="button" name="senden" value="Abschicken">
                            <input type="submit" class="button" name="vorschau" value="Vorschau">
                        </td>
                    </tr>
                
                </table>';
                allownav('gildenverwalt.php?op=mail');
            if(!empty($_POST['vorschau'])){
                systemmail($session['user']['acctid'],'`^Gilde: `0'.$_POST['subject'],$_POST['mail'],$session['user']['acctid']);
                output('<BIG><BIG>`n`&Eine Vorschau dieser Massenmail wurde an deinen Taubenschlag geschickt!`n`2</BIG></BIG>',true);
            }
    
        } elseif($_POST['senden']=='Abschicken') {
            MyNavs();
    //                break;
    //            case 'posted':
    
            if(empty($_POST['mail']) || empty($_POST['subject'])){
                $out .= 'Du musst ALLE Felder ausfüllen!';
                MyNavs('Zurück','gildenverwalt.php?op=mail','Erneut eingeben');
            } else {
                $what = $_POST['what'];
                $subject = $_POST['subject'];
                $mail = $_POST['mail'];
                $users = $_POST['users'];
                
                switch($users){
                    case 1:
                        $sqladd = '';
                    break;
                    case 2:
                        $sqladd = ' AND `isleader` < "1"';
                    break;
                    case 3:
                        $sqladd = ' AND `isleader` > "0"';
                    break;
                }
                $sql="SELECT `acctid` FROM `accounts` WHERE `locked` = '0' AND `gildenactive`='1' AND `memberid`='".$session['user']['memberid']."' ".$sqladd;
                $result = db_query($sql);
                if(db_num_rows($result)==0){
                    $out .= 'Massenmails sind dazu da um an eine MASSE von Spielern geschickt zu werden. Du hast aber von den ausgewählten Mitgliedern keine Masse...nicht einmal einen :/ ...';
                    MyNavs('Zurück','massmail.php','Erneut eingeben');
                } else {
                    $players = db_num_rows($result);
                    for($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        systemmail($row['acctid'],'`^Gilde: `0'.$subject,$mail,$session['user']['acctid']);
                   }
                   $out .= '<BIG><BIG>YoM an '.$players.' Mitglieder geschickt!</BIG></BIG>';
                }
            }
                MyNavs('Neue Massenmail','gildenverwalt.php?op=mail','Weiter');
        }
        rawoutput($out);
    break;

    /* Bewerbungen - Aufnehmen */
    case "nimm":
      aufnehm($_GET['id'],RawUrlDecode($_GET['login']));
      break;

    /* Bewerbungen - Ablehnen */
    case "ablehn";
      ablehn($_GET['id'],RawUrlDecode($_GET['login']));
      break;

    /* Ränge */
    case "ranks":
      /* Wenn kein subop gesetzt ist, Titel anzeigen */
      if(!isset($_GET['subop']))
        showtitles();
      /* Titel editieren */
      elseif($_GET['subop']=="edit")
      {
        createtitle((int)$_GET['id']);
      }
      /* Titel löschen & Liste anzeigen */
      elseif($_GET['subop']=="del")
      {
        deletetitle((int)$_GET['id']);  // Titel löschen
        showtitles(); // Titel wieder anzeigen
      }
      /* Speichern */
      elseif($_GET['subop']=="save")
      {
        if($_POST['rankid']=="not")
          createtitle();
        else
          createtitle((int)$_POST['rankid']);
        showtitles();
      }
      /* Hinzufügen */
      elseif($_GET['subop']=="add")
      {
        createtitle();
      }
      break;

    /* Mitglieder */
    case "members":
      if($_GET['action']=="leader")
      {
        /* Wenn die gespostete Leadergrösse grösser oder gleich dem  maximalen Wert ist, soll sie 1 unter dem maximal Wert liegen. */
        if(highestleader<=$_POST['id']) $_POST['id'] = highestleader-1;
        // output(output_array($_POST,"DEBUG: ")); // Debug Ausgabe
        $sql = "UPDATE accounts SET isleader='$_POST[id]' WHERE acctid='$_POST[acctid]' AND (acctid!='{$session[user][acctid]}' AND acctid!='{$session[guild][leaderid]}')";
        db_query($sql); // Update in die Datenbank schreiben
      }
      elseif($_GET['action']=="rank")
      {
        // output(output_array($_POST,"DEBUG: ")); // Debug Ausgabe
        $sql = "UPDATE accounts SET rankid='$_POST[rank]' WHERE acctid='$_POST[acctid]'";
        db_query($sql); // Update in die Datenbank schreiben
        /* Anti-Updatefehler | Wenn der Bearbeiter sich selbst einen Rang zuteilt, kann nicht aktualisiert werden. */
        if($session['user']['acctid'] == $_POST['acctid']) $session['user']['rankid'] = (int)$_POST['rank'];
      }
      elseif($_GET['action']=="dropmember")
      {
        drop_member($_GET['dropid']);
      }
      showuser();
      break;
    case "texte":
      /* Texte editieren */
      if(isset($_POST['field'])) // Prüfen, ob der Key field im Post-Array gesetzt wurde
      {
        guild_update($_POST['field'],$_POST['text']); // Gildenwerte updaten
        output("`c".$session['guild'][$_POST['field']]."`c",true); // Neuen Text ausgeben
        output("Änderungen übernommen"); // Erfolgreiche Änderungen posten
      }
      else
        show_text($_GET['text']); // Text anzeigen & Feld zum Editieren anzeigen
      break;
    case "belohnen":
        /* Spieler belohnen */
        if(isset($_POST['value'])){ // Prüfen, ob der Key value im Post-Array gesetzt wurde
          if("gold" == $_POST['art']){
            $sql = "SELECT gold,gildengold,level,name,acctid FROM accounts WHERE acctid='".$_POST['acctid']."'";
            $row = db_fetch_assoc(db_query($sql));
            if($row['gildengold'] == (int)-($row['level']*1000) OR
                ($row['gildengold'] - $_POST['value']) < (int)-($row['level']*1000) OR
                $session['guild']['gold'] < $_POST['value'] OR
                ($_POST['value']<0)){
                    output("`\$Es ist ein Fehler aufgetreten!");
                }else{
                    output("`@Okay!");
                    db_unbuffered_query("UPDATE accounts SET gold=gold+'".$_POST['value']."',gildengold=gildengold-'".$_POST['value']."' WHERE acctid='".$_POST['acctid']."'");
                    guild_update("gold",0-$_POST['value']);
                    $row_buch=db_fetch_assoc(db_query("SELECT name FROM accounts WHERE acctid='".$_POST['acctid']."'"));
                    gildenbuch("-".$_POST['value'], "0", "Belohnung für $row_buch[name]");
                    systemmail($_POST['acctid'],"Belohnung","Die Gilde hat dir eine Belohnung in Höhe von ".$_POST['value']." Goldstücken zukommen lassen.");
                }
            }elseif("gems" == $_POST['art']){
                $sql = "SELECT gems,gildengems,level,name,acctid FROM accounts WHERE acctid='".$_POST['acctid']."'";
                $row = db_fetch_assoc(db_query($sql));
                if($row['gildengems'] == (int)-($row['level']*2) OR
                    ($row['gildengems'] - $_POST['value']) < (int)-($row['level']*2) OR
                    $session['guild']['gems'] < $_POST['value'] OR
                    ($_POST['value']<0)){
                    output("`\$Fehler aufgetreten! ");
                }else{
                output("`@Okay!");
                db_unbuffered_query("UPDATE accounts SET gems=gems+'".$_POST['value']."',gildengems=gildengems-'".$_POST['value']."' WHERE acctid='".$_POST['acctid']."'");
                guild_update("gems",0-$_POST['value']);
                $row=db_fetch_assoc(db_query("SELECT name FROM accounts WHERE acctid='".$_POST['acctid']."'"));
                    gildenbuch("0", "-".$_POST['value'], "Belohnung für $row[name]");
                    systemmail($_POST['acctid'],"Belohnung","Die Gilde hat dir eine Belohnung in Höhe von ".$_POST['value']." Edelsteinen zukommen lassen.");
                }
            }
        }
        showuser_pay();
    break;
    /* Ausbau-Modifikation by Eliwood */
    case "build":
      if(!isset($_GET['step']))
      {
        if(!isset($_GET['stufe'])) $_GET['stufe'] = 1;
        $sql = "SELECT * FROM gilden_ausbau WHERE ownerguild='0' AND link='$_GET[action]' AND stufe='$_GET[stufe]'";
        $row = db_fetch_assoc(db_unbuffered_query($sql));
        output("`#$row[name] kostet `^$row[goldcost] Goldstücke`# und `%$row[gemcost] Edelsteine`#.`n`n");
        if($session['guild']['gold']>=$row['goldcost'] && $session['guild']['gems']>=$row['gemcost'])
        {
          output("`#Deine Schätze sind gross genug, du kannst also mit dem Bau beginnen.`n`n");
          addnav($row['name']." (Stufe `^".$row['stufe']."`0)","gildenverwalt.php?op=build&action=$_GET[action]&stufe=$_GET[stufe]".($row['value1']>0?"&value=$row[value1]":"")."&step=1");
        }
      }
      else
      {
        if($_GET['stufe']>1)
        {
          $sql = "UPDATE `gilden_ausbau` SET `stufe`='$_GET[stufe]', `value1`='$_GET[value]' WHERE `ownerguild`='{$session['guild']['gildenid']}' AND `link`='$_GET[action]'";
          db_query($sql);
          $row_buch=db_fetch_assoc(db_query("SELECT * FROM gilden_ausbau WHERE stufe='$_GET[stufe]' AND `link`='$_GET[action]' AND `ownerguild`='0'"));
                    gildenbuch("-".$row_buch['goldcost'], "-".$row_buch['gemcost'], "Bau von ".$row_buch['name']);

        }
        else
        {
          $sql = "SELECT * FROM gilden_ausbau WHERE ownerguild='0' AND link='$_GET[action]' AND stufe='1'";
          $row = db_fetch_assoc(db_unbuffered_query($sql));
          db_query("INSERT INTO gilden_ausbau (ownerguild,name,stufe,value1,value2,link)VALUES ('".$session['guild']['gildenid']."','$row[name]','$row[stufe]','$row[value1]','$row[value2]','$row[link]')") or die(db_error(LINK));
          $row_buch=db_fetch_assoc(db_query("SELECT * FROM gilden_ausbau WHERE stufe='1' AND `link`='$_GET[action]' AND `ownerguild`='0'"));
          gildenbuch("-".$row_buch['goldcost'], "-".$row_buch['gemcost'], "Bau von ".$row_buch['name']);

        }
        output("`#$row[name] Stufe $row[stufe] wurde (aus)gebaut.");
        guild_update("gold",(0-$row['goldcost']));
        guild_update("gems",(0-$row['gemcost']));
        guild_update("gildenpunkte",$row['givepoint']);
      }
      break;
    /* Ausbau verwalten */
    case "verwaltbuild":
      // page_title("Gebäudeverwaltung");
      require_once "lib/gildenbuilding.php";
      switch($_GET['action'])
      {
        case "weapon":
          include_once "builds/weapon.php";
          break;
        case "armor":
          include_once "builds/armor.php";
          break;
        case "diplomat":
          include_once "builds/diplomat.php";
          break;
      }
      break;
    /* Fehlermeldung, falls kein Fall für op vorhanden */
    case "rename":
      if(!isset($_GET['step']) || $_GET['step'] == 1)
      {
        renameform("gildenverwalt.php?op=rename&step=2");
      }
      else
      {
        if(check_input($_POST)==false)
        {
          rawoutput($error);
          renameform("gildenverwalt.php?op=rename&step=2");
        }
        else
        {

          guild_update("gildenname",$_POST['gildenname']);
          guild_update("gildenname_b",$_POST['gildenname_b']);
          guild_update("gildenprefix",$_POST['gildenprefix']);
          guild_update("gildenprefix_b",$_POST['gildenprefix_b']);
          output("Gilde umbenannt!");
        }
      }
      break;
    case "dropguild":
      if(!isset($_GET['step']))
      {
        $droplink = "gildenverwalt.php?op=dropguild&step=1";
        allownav($droplink);
        output("<form action='$droplink' method='POST'><input type='submit' class='button' value='Gilde auflösen' onClick='return confirm(\"Willst du die Gilde WIRKLICH LÖSCHEN?\");'></form>", true);
      }
      else  dropguild($session['guild']['gildenid']);
      break;

    case "buch":
        output("Hier werden alle Finanztransaktionen der Gilde angezeigt`n");
        output("<table cellspacing=5><tr><th width='150'>Name</th><th width='60'>Gold</th><th width='30'>Edelstein</th><th width='200'>Beschreibung</th><th width='100'>Datum</th></tr>", true);
        
        $result=db_query("SELECT * FROM gildenbuch WHERE guild='".$session['guild']['gildenid']."' ORDER BY id DESC");
        while($row=db_fetch_assoc($result)) {
         output("<tr><td valign=top>$row[user]</td><td valign=top>",true);
          if ($row[gold]>0) {
           output("`@ $row[gold]");
          } elseif ($row[gold]==0) {
           output("`V $row[gold]");
          } else {
           output("`$ $row[gold]");
          }
         output("</td><td valign=top>",true);
          if ($row[gem]>0) {
           output("`@ $row[gem]");
          } elseif ($row['gem']==0) {
           output("`V $row[gem]");
          } else {
           output("`$ $row[gem]");
          }
          $datum=explode(' ',$row['datum']);
          $tag=explode('-',$datum[0]);
         output("</td><td valign=top>$row[grund]</td><td valign=top>".$tag[2].".".$tag[1].".".$tag[0]."&nbsp;".$datum[1]."</td></tr>", true);
        }
        output("</table>",true);
   break;
    default:
        $title = "FEHLER!";
        output("`\$Fehler! Melde es unverzüglich den Administratoren, wenn du das sehen kannst");
endswitch;

/* Navigation */
/* Aktionen */
addnav("Aktionen");
addnav("Bewerbungen",($_GET['op']=="bewerbungen"?"":"gildenverwalt.php?op=bewerbungen")); // Bewebungen einsehen
addnav("Massenmail",($_GET['op']=="mail"?"":"gildenverwalt.php?op=mail")); // Massenmail
addnav("Mitgliederliste",($_GET['op']=="members"?"":"gildenverwalt.php?op=members")); // Mitgliederliste einsehen &Ränge zuteilen
addnav("Spieler belohnen",($_GET['op']=="belohnen"?"":"gildenverwalt.php?op=belohnen")); // Spieler mit Gems oder Gold belohnen
addnav("Kontoauszug",($_GET['op']=="buch"?"":"gildenverwalt.php?op=buch"));
addnav("Ränge bearbeiten",($_GET['op']=="ranks"?"":"gildenverwalt.php?op=ranks")); // Ränge verwalten
if ($session['user']['isleader'] >= highestleader)
{
  addnav("Gilde umbenennen",($_GET['op']=="rename"?"":"gildenverwalt.php?op=rename")); // Gilde umbennen
  addnav("`\$Gilde auflösen",($_GET['op']=="dropguild"?"":"gildenverwalt.php?op=dropguild")); // Auflösen der Gilde
}
addnav("Texte");
addnav("Editiere...","");
addnav("Geschichte","gildenverwalt.php?op=texte&text=story"); // Geschichte editieren
addnav("Beschreibung","gildenverwalt.php?op=texte&text=desc"); // Beschreibung der Gilde editieren
addnav("Regeln","gildenverwalt.php?op=texte&text=regeln"); // Die Regeln editieren


/* Ausbau-Modifikation by Eliwood */
if(buildactive===true)
{
  addnav("Gebäudeverwaltung");
  if(is_buildet("weapon")) addnav("Waffenverwaltung","gildenverwalt.php?op=verwaltbuild&action=weapon");
  if(is_buildet("armor")) addnav("Rüstungsverwaltung","gildenverwalt.php?op=verwaltbuild&action=armor");
  if(is_buildet("diplomat")) addnav("Diplomatie","gildenverwalt.php?op=verwaltbuild&action=diplomat");
  if($_GET['step']!=1)
  {
    addnav("Ausbau");
    show_build_navs();
  }
}

/* Ausgang */
addnav("Ausgang");
addnav("Aufenthaltsraum","gilden.php"); // Hauptraum
addnav("Gildenstrasse","gildenstrasse.php"); // Zurück zur Gildenstrasse
if(function_exists("backplace")) // Prüfen ob Funktion backplace überhaupt definiert ist
backplace(0); // Zurück zum Platz
addnav("Dorfplatz","village.php"); // Zum Dorfplatz

/* Gildenwerte in der Datenbank wieder abspeichern */
// saveguild($session['guild']['memberid']);

/* Output ausgeben */
page_header($title);
page_footer();

?>

