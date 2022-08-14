
<?php

// Angepasst, dass der Testchar nicht sein Passwort ändern kann (für Omar)
// by Nashyan (nashyan@habmalnefrage.de) - 03.06.2011

require_once "common.php";
page_header("Einstellungen & Profil");

if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime(date("c")."+45 days"));
    $_COOKIE['template']=$_POST['template'];
    }

switch ($_GET['op']) {
    case "suicide":
        if ( getsetting("selfdelete",0) != 0) {
            if($session['user']['acctid']==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));

            if ($session['user']['acctid'] == getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
            //inventar, haus löschen und partner freigeben
            if ((int)$_GET['userid'] == (int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
            $sql = "UPDATE items SET owner=0 WHERE owner='".$_GET['userid']."'";
            db_query($sql) or die(db_error(LINK));
            $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=".$_GET['userid']." AND status=1";
            db_query($sql) or die(db_error(LINK));
            $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=".$_GET['userid']." AND status=0";
            db_query($sql) or die(db_error(LINK));
            $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto='".$_GET['userid']."'";
            db_query($sql) or die(db_error(LINK));
            $sql = "DELETE FROM pvp WHERE acctid2=".$_GET['userid']." OR acctid1='".$_GET_['userid']."'";
            db_query($sql) or die(db_error(LINK));
            //Bio und Avatar löschen
            $sql = "DELETE FROM bios WHERE acctid='".$_GET['userid']."'";
            db_query($sql) or die(db_error(LINK));
            //Friedhof Skript
            $sql = "INSERT INTO graeber (name,spruch,status,level,age,titel,dk,sex) VALUES ('".$session[user][login]."','".$spruch."','1','".$session[user][level]."','".$session[user][age]."','".$session[user][title]."','".$session[user][dk]."','".$row[sex]."')";
            db_query($sql) or die(db_error(LINK));
            //User löschen
            $sql = "DELETE FROM accounts WHERE acctid='".$_GET['userid']."'";
            db_query($sql) or die(db_error(LINK));
            $sql = "DELETE FORM `rporte` WHERE acctid IN ('.$delaccts.')";
            db_query($sql) or die (db_error(LINK));
            output("Dein Charakter, sein Inventar und alle seine Kommentare wurden gelöscht!",true);
            addnews("`#{$session['user']['name']} beging Selbstmord.");
            addnav("Login Seite", "index.php");
            $session = array();
            $session['user'] = array();
            $session['loggedin'] = false;
            $session['user']['loggedin'] = false;
            }
    break;

    case "":
    default:
        checkday();
        
        $sql = "SELECT * FROM bios WHERE acctid='".$session['user']['acctid']."'";
        $res = db_query($sql) or die(db_error(LINK));
        $bios = db_fetch_assoc($res);
        
        addnav("Hilfsmittel");
        addnav("Bio-Schreibmaschine","biotool/index.php",false,false,true);
        addnav("Zurück");

        if($session['user']['alive']){
            addnav("zur Stadt","village.php");
        }else{
            addnav("zu den News","news.php");
        }
        
        if (count($_POST) == 0) {}
        else{
            if ($_POST['pass1'] != $_POST['pass2']) {
            output("`#Deine Passwörter stimmen nicht überein.`n",true);
            }
        else {
            if ($_POST['pass1'] != "") {
                if (strlen($_POST['pass1']) > 3) {
                    $session['user']['password'] = md5($_POST['pass1']);
                    output("`#Dein Passwort wurde geändert.`n",true);
                    }
                else output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n",true);
                }
            }
        reset($_POST);
        $nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>1,"template"=>1,"bio"=>1,"avatar"=>1);
        while (list($key,$val) = each($_POST)){
            if (!$nonsettings[$key]) $session['user']['prefs'][$key]=$_POST[$key];
        }

        //Personal-Infos to DB
/*         if(closetags(stripslashes($_POST['personal_name']),'`i`b`c`H') != $bios['personal_name']){
            $personal_name = closetags(stripslashes($_POST['personal_name']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_name='".$personal_name."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(stripslashes($_POST['personal_avatar']) != $bios['personal_avatar']) {
            $personal_avatar = stripslashes(preg_replace("'[\"\'\\><@?*&#; ]'","",$_POST['personal_avatar']));
            if ($personal_avatar > "" && strpos($personal_avatar,".gif") < 1 && strpos($personal_avatar,".GIF") < 1 && strpos($personal_avatar,".jpg") < 1 && strpos($personal_avatar,".JPG") < 1 && strpos($personal_avatar,".png") < 1 && strpos($personal_avatar,".PNG") < 1){
                $personal_avatar = "";
                output("`\$Ungültiger Avatar! Nur *.jpg, *.png oder *.gif!`0`n",true);
                }
            $sql = "UPDATE bios SET personal_avatar='".$personal_avatar."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if($_POST['personal_sex'] != $bios['personal_sex']){
            $sql = "UPDATE bios SET personal_sex=".$_POST['personal_sex']." WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['personal_place']),'`i`b`c`H') != $bios['personal_place']){
            $personal_place = closetags(stripslashes($_POST['personal_place']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_place='".$personal_place."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['personal_bday']),'`i`b`c`H') != $bios['personal_bday']){
            $personal_bday = closetags(stripslashes($_POST['personal_bday']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_bday='".$personal_bday."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['personal_couple']),'`i`b`c`H') != $bios['personal_couple']){
            $personal_couple = closetags(stripslashes($_POST['personal_couple']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_couple='".$personal_couple."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['personal_hobby']),'`i`b`c`H') != $bios['personal_hobby']){
            $personal_hobby = closetags(stripslashes($_POST['personal_hobby']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_hobby='".$personal_hobby."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['personal_about']),'`i`b`c`H') != $bios['personal_about']){
            $personal_about = closetags(stripslashes($_POST['personal_about']),'`i`b`c`H');
            $sql = "UPDATE bios SET personal_about='".$personal_about."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
 */        //Personal-Infos Ende
                
        //Char-Infos to DB    
        /*if(closetags(stripslashes($_POST['char_race']),'`i`b`c`H') != $session['user']['race']){
            $char_race = closetags(stripslashes($_POST['char_race']),'`i`b`c`H');
            $session['user']['race'] = $char_race;
            }
        if(closetags(stripslashes($_POST['char_place']),'`i`b`c`H') != $bios['char_place']){
            $char_place = closetags(stripslashes($_POST['char_place']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_place='".$char_place."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_age']),'`i`b`c`H') != $bios['char_age']){
            $char_age = closetags(stripslashes($_POST['char_age']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_age='".$char_age."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_parents']),'`i`b`c`H') != $bios['char_parents']){
            $char_parents = closetags(stripslashes($_POST['char_parents']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_parents='".$char_parents."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_bro']),'`i`b`c`H') != $bios['char_bro']){
            $char_bro = closetags(stripslashes($_POST['char_bro']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_bro='".$char_bro."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_sis']),'`i`b`c`H') != $bios['char_sis']){
            $char_sis = closetags(stripslashes($_POST['char_sis']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_sis='".$char_sis."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_adopted']),'`i`b`c`H') != $bios['char_adopted']){
            $char_adopted = closetags(stripslashes($_POST['char_adopted']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_adopted='".$char_adopted."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_affairs']),'`i`b`c`H') != $bios['char_affairs']){
            $char_affairs = closetags(stripslashes($_POST['char_affairs']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_affairs='".$char_affairs."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }
        if(closetags(stripslashes($_POST['char_pate']),'`i`b`c`H') != $bios['char_pate']){
            $char_pate = closetags(stripslashes($_POST['char_pate']),'`i`b`c`H');
            $sql = "UPDATE bios SET char_pate='".$char_pate."' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            }*/
        //Char-Infos Ende
/*
        if (mysql_real_escape_string(closetags(stripslashes($_POST['bio']),'`i`b`c`H')) != $bios['bio']) {
            if ($bios['biotime'] > "9000-01-01") output("`n`\$Du kannst deine Beschreibung nicht ändern. Der Admin hat diese Funktion blockiert!`0`n",true);
            else {
                $bio = mysql_real_escape_string(closetags(stripslashes($_POST['bio']),'`i`b`c`H'));
                $sql = "UPDATE bios SET bio='".$bio."', biotime='".date("Y-m-d H:i:s")."' WHERE acctid='".$session['user']['acctid']."'";
                db_query($sql) or die(db_error(LINK));
                }
            }
        if (stripslashes($_POST['avatar']) != $bios['avatar']) {
            if ($bios['avadate'] > "9000-01-01") output("`n`\$Du kannst deinen Avatar nicht ändern. Der Admin hat diese Funktion blockiert!`0`n",true);
            else {
                $avatar = stripslashes(preg_replace("'[\"\'\\><@?*&#; ]'","",$_POST['avatar']));
                if ($avatar > "" && strpos($avatar,".gif") < 1 && strpos($avatar,".GIF") < 1 && strpos($avatar,".jpg") < 1 && strpos($avatar,".JPG") < 1 && strpos($avatar,".png") < 1 && strpos($avatar,".PNG") < 1) {
                    $avatar = "";
                    output("`\$Ungültiger Avatar! Nur *.jpg, *.png oder *.gif!`0`n",true);
                    }
                $sql = "UPDATE bios SET avatar='".$avatar."', avadate='".date("Y-m-d H:i:s")."' WHERE acctid='".$session['user']['acctid']."'";
                db_query($sql) or die(db_error(LINK));
                }
           }
        if (mysql_real_escape_string(closetags(stripslashes($_POST['veribio']),'`i`b`c`H')) != $bios['veribio']) {
            if ($bios['biotime'] > "9000-01-01") output("`n`\$Du kannst deine Beschreibung nicht ändern. Der Admin hat diese Funktion blockiert!`0`n",true);
            else {
                $veribio = mysql_real_escape_string(closetags(stripslashes($_POST['veribio']),'`i`b`c`H'));
                $sql = "UPDATE bios SET veribio='".$veribio."' WHERE acctid='".$session['user']['acctid']."'";
                db_query($sql) or die(db_error(LINK));
                }
            }
        if ($_POST['email'] != $session['user']['emailaddress']) {
            if (is_email($_POST['email'])) {
                if (getsetting("requirevalidemail",0) == 1) output("`#Die E-Mail Adresse kann nicht geändert werden, die Systemeinstellungen verbieten es. (E-Mail Adressen können nur geändert werden, wenn der Server mehr als einen Account pro Adresse zulässt.) Sende eine Petition, wenn du deine Adresse ändern willst, weil sie nicht mehr länger gültig ist.`n",true);
                else {
                    output("`#Deine E-Mail Adresse wurde geändert.`n",true);
                    $session['user']['emailaddress'] = $_POST['email'];
                    }
                }
            else {
                if (getsetting("requireemail",0) == 1) output("`#Das ist keine gültige E-Mail Adresse.`n",true);
                else {
                    output("`#Deine E-Mail Adresse wurde geändert.`n",true);
                    $session['user']['emailaddress'] = $_POST['email'];
                    }
                }
            }*/
        output("`nEinstellungen gespeichert`0",true);
        addnav("Zurück zum Profil","prefs.php");
        redirect("prefs.php");
    break;
}
    
    $form = array(
        "Allgemein Einstellungen,title",
        "emailonmail"=>"E-Mail senden wenn du eine Ye Olde Mail bekommst?,bool",
        "systemmail"=>"E-Mail bei Systemmeldungen senden (z.B. Niederlage im PvP)?,bool",
        "dirtyemail"=>"Kein Wortfilter für Ye Olde Mail?,bool",
        "nosounds"=>"Die Sounds deaktivieren?,bool",
        "flist"=>"Freundesliste anzeigen?,bool",
        "ulist"=>"'Wer ist hier' anzeigen?,bool",
        "log"=>"Changelog anzeigen?,bool",
        "otset"                => "OOC Chat,enum,0,Einwohnerliste,1,Obere Navigation,2,Beides",
        "list_type"=>"Einwohnerlisten Stil:,enum,0,alt,1,neu",
            
        "Farb- und Kommentareinstellungen,title",
        "commenttalkcolor"=>"Standardfarbe bei Gesprächen",
        "commentemotecolor"=>"Standardfarbe bei Emotes (/me)",
        "comlim"=>"Angezeigte Kommentare auf Plätzen`n(`bStandart 10`b),int",
        "yom_sig"=>"Signatur für YoM-Nachrichten",
        
        "Schnelleingaben,title",
        "tags_1" => "Schnelleingabe 1",
        "tags_2" => "Schnelleingabe 2",
        "tags_3" => "Schnelleingabe 3",
        "tags_4" => "Schnelleingabe 4",
        "tags_5" => "Schnelleingabe 5",
        
        "AFK-Meldung (Kämpferliste),title",
        "afk" => "Abwesenheitsmeldung für die Kämpferliste:",
        "afk2" => "Abwesend?,bool"

 /*        "Persönliche Informationen (Bio),title",
        "personal_name" => "Name:",
        "personal_sex" => "Geschlecht:,enum,0,Keine Angabe,1,Männlich,2,Weiblich",
        "personal_place"=>"Wohnort:",
        "personal_bday"=>"Geburtstag:",
        "personal_couple"=>"Familienstand:",
        "personal_hobby"=>"Hobbys:",
        "personal_about"=>"Über mich:,textarea,40,10",
        "personal_avatar"=>"Link auf ein Bild von dir:`n(Bilddatei - maximal 300x300 Pixel)`n",
                
        "Charakter Informationen (Bio),title",
        "char_race"=>"`bRasse des Charakters`b:",
        "char_place"=>"Herkunft des Charakters:",
        "char_age"=>"Alter des Charakters:",
        "char_parents"=>"Eltern des Charakters:",
        "char_bro"=>"Brüder des Chrakaters:",
        "char_sis"=>"Schwestern des Charakters:",
        "char_adopted"=>"Adoptierte Kinder des Charakters:",
        "char_affairs"=>"Affären des Charakters:",
        "char_pate"=>"Pate des Charakters:",

        "Bio,title",
        "bio"=>"Kurzbeschreibung des Charakters,textarea,50,10`n",
        "avatar"=>"Link auf einen Avatar`n(Bilddatei - maximal 400x400 Pixel)`n",
        
        "ü18-Biografie,title",
        "veribio"=>"ü18-Bio `n(wird nur bei Volljährigkeit angezeigt),textarea,50,10"
*/         );

    // Füge danach ein:
    if($session['user']['npc']) {
        require_once "lib/npc.class.php";
        //$npcs = getUsrNPCsData($session['user']['acctid']);
        $npcs = npc::getAllOf($session['user']['acctid'],false);
        $text = "Der NPC den du im moment verwenden möchtest,enum";
        foreach($npcs as $npc) {
            $text .= ",".$npc->getId().",".$npc->getName();
        }
    } else {
        $text = "Leider hast du noch keine NPCs,viewonly";
    }
    $form['akt_npc'] = $text;

    output("<form action='prefs.php?op=save' method='POST'>",true);
    
    $result = db_query('SELECT `templatename` AS `tpl_name`, `tsrc` AS `tpl`, `freefor` AS `user` FROM `templates` ORDER BY `templatename`') or die(db_error(LINK));
    if (db_num_rows($result)) {
        rawoutput('<table><tbody>');
        while ($row = db_fetch_assoc($result)) {
            if($session['user']['superuser'] >= $row['user'])
                if($_COOKIE['template'] == $row['tpl']){
                    rawoutput('<tr><td><input type="radio" checked name="template" value="'.$row['tpl'].'">'.$row['tpl_name'].'</td></tr>');
                }elseif($_COOKIE['template']=="" && $row['tpl']=="saintomar.htm"){
                    rawoutput('<tr><td><input type="radio" checked name="template" value="'.$row['tpl'].'">'.$row['tpl_name'].'</td></tr>');
                }else{
                    rawoutput('<tr><td><input type="radio" name="template" value="'.$row['tpl'].'">'.$row['tpl_name'].'</td></tr>');
                    }
                }
            rawoutput('</tbody></table><br /><br />');
            } else {
                rawoutput('<strong style="color: #FF0000;">Es sind keine Templates in der Tabelle vorhanden!</strong><br /><br />');
                }

    if($session['user']['acctid'] != 344)
        output("Neues Passwort: <input name='pass1' type='password'> (lasse das Feld leer, wenn du es nicht ändern willst)`n
        Wiederholen: <input name='pass2' type='password'>`n
        E-Mail Adresse: <input name='email' value=\"".HTMLEntities($session['user']['emailaddress'])."\">`n
        ",true);
    
    $prefs = $session['user']['prefs'];
    if(isset($prefs['afk']))
        $prefs['afk'] = stripslashes($prefs['afk']);
    if(isset($prefs['tags_1']))
        $prefs['tags_1'] = stripslashes($prefs['tags_1']);
    if(isset($prefs['tags_2']))
        $prefs['tags_2'] = stripslashes($prefs['tags_2']);
    if(isset($prefs['tags_3']))
        $prefs['tags_3'] = stripslashes($prefs['tags_3']);
    if(isset($prefs['tags_4']))
        $prefs['tags_4'] = stripslashes($prefs['tags_4']);
    if(isset($prefs['tags_5']))
        $prefs['tags_5'] = stripslashes($prefs['tags_5']);
    
/*     $prefs['bio'] = $bios['bio'];
    $prefs['avatar'] = $bios['avatar'];
    $prefs['veribio'] = $bios['veribio'];
    $prefs['personal_name'] = $bios['personal_name'];
    $prefs['personal_avatar'] = $bios['personal_avatar'];
    $prefs['personal_sex'] = $bios['personal_sex'];
    $prefs['personal_place'] = $bios['personal_place'];
    $prefs['personal_bday'] = $bios['personal_bday'];
    $prefs['personal_couple'] = $bios['personal_couple'];
    $prefs['personal_hobby'] = $bios['personal_hobby'];
    $prefs['personal_about'] = $bios['personal_about'];
    $prefs['char_race'] = $session['user']['race'];
    $prefs['char_place'] = $bios['char_place'];
    $prefs['char_age'] = $bios['char_age'];
    $prefs['char_parents'] = $bios['char_parents'];
    $prefs['char_bro'] = $bios['char_bro'];
    $prefs['char_sis'] = $bios['char_sis'];
    $prefs['char_adopted'] = $bios['char_adopted'];
    $prefs['char_affairs'] = $bios['char_affairs'];
    $prefs['char_pate'] = $bios['char_pate'];
 */    
    showform($form,$prefs);
    output("</form>",true);
    addnav("","prefs.php?op=save");
    
    // Stop clueless lusers from deleting their character just because a monster killed them.
    if ($session['user']['alive'] && getsetting("selfdelete",0) != 0) {
        output("`n`n`n<form action='prefs.php?op=suicide&userid=".$session['user']['acctid']."' method='POST'>
            <input type='submit' class='button' value='Charakter löschen' onClick='return confirm(\"Willst du deinen Charakter wirklich löschen?\");'>
            </form>",true);
        addnav("","prefs.php?op=suicide&userid=".$session['user']['acctid']);
        }
    }


page_footer();

?>

