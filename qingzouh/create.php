
<?php

// 15082004

require_once "common.php";
$trash = getsetting("expiretrashacct",1);
$new = getsetting("expirenewacct",10);
$old = getsetting("expireoldacct",45);

checkban();

if ($_GET['op']=="val"){
    $sql = "SELECT login,name FROM accounts WHERE emailvalidation='".$_GET['id']."' AND emailvalidation!=''";
    $result = db_query($sql);
    if (db_num_rows($result)>0) {
        $row = db_fetch_assoc($result);
        if (substr($_GET['id'],0,1)=='x') {
            $form = true;
            if (!empty($_POST['pass1'])) {
                if ($_POST['pass1']!=$_POST['pass2']){
                    output("`#Deine Passwörter stimmen nicht überein.`n");
                }else{
                    if (strlen($_POST['pass1'])>3){
                        $sql = "UPDATE accounts SET emailvalidation='',password=MD5('".$_POST['pass1']."') WHERE emailvalidation='".$_GET['id']."' AND emailvalidation!=''";
                        db_query($sql);
                        output("`#`cDein Passwort wurde geändert. Du kannst jetzt einloggen.`c`0");
                        output("<form action='login.php' method='POST'><input name='name' value=".$row['login']." type='hidden'><input name='password' value=".$_POST['pass1']." type='hidden'>
                        Dein Passwort wurde geändert. Dein Login Name ist `^".$row['login']."`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);
                        $form = false;
                    }else{
                        output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");
                    }
                }
            }

            if ($form) {
                output("`&`c`bNeues Passwort wählen`b`c`n");
                output("`0<form action=\"create.php?op=val&id=".$_GET['id']."\" method='POST'>",true);
                output("`nDein neues Passwort: <input type='password' name='pass1'>`n",true);
                output("`nPasswort bestätigen: <input type='password' name='pass2'>`n",true);
                output("<input type='submit' class='button' value='Neues Passwort speichern!'>",true);
                output("</form>",true);
            }
        }else {
            $sql = "UPDATE accounts SET emailvalidation='' WHERE emailvalidation='".$_GET['id']."' AND emailvalidation!=''";
            db_query($sql);
            output("`#`cDeine E-Mail Adresse wurde bestätigt. Du kannst jetzt einloggen.`c`0");
            output("<form action='login.php' method='POST'><input name='name' value=".$row['login']." type='hidden'><input name='password' value=".$row['password']." type='hidden'>
            Deine E-Mail Adresse wurde bestätigt. Dein Login Name ist `^".$row['login']."`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n"
            .($trash>0?"Charaktere die nie einloggen werden nach $trash Tag(en) Inaktivität gelöscht.`n":"")
            .($new>0?"Charaktere die nie Level 2 erreichen werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
            .($old>0?"Charaktere die Level 2 erreicht haben werden nach $old Tag(en) Inaktivität gelöscht.":"")
            ."",true);
            output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trägst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die Adressen ein. Bei anderen Browsern gibt es ähnliche Einstellungen.");
            savesetting("newplayer",addslashes($row['name']));
        }
    }else{
        output("`#Deine E-Mail Adresse konnte nicht bestätigt werden. Möglicherweise wurde sie schon bestätigt. Versuch mal dich einzuloggen und informiere den Webmaster, wenn es nicht klappt.");
    }
}
if ($_GET[op]=="forgot"){
    if ($_POST[charname]!=""){
        $sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='$_POST[charname]'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if (trim($row['emailaddress'])!=""){
                if ($row['emailvalidation']==""){
                    $row['emailvalidation']=substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);
                    $sql = "UPDATE accounts SET emailvalidation='".$row['emailvalidation']."' where login='".$row['login']."'";
                    db_query($sql);
                }
                mail(
                    $row[emailaddress],
                    "LoGD Account Verification",
                    "Jemand von ".$_SERVER['REMOTE_ADDR']." hat ein vergessenes Passwort von deinem Accoount angefordert.  Wenn du das warst, ist hier dein"
                    ." Link. Du kannst damit einloggen und dein Passwort im Profil vom Dorfplatz aus einstellen.\n\n"
                    ."Wenn du diese E-Mail nicht angefordert hast, keine Panik! Du hast sie bekommen, sonst niemand."
                    ."\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=".$row['emailvalidation']."\n\nDanke für's Spielen!",
                    "From: ".getsetting("gameadminemail","admina@eassos.de",true)
                );
                
                output("`#Eine neue Bestätigungsmail wurde an die mit diesem Account gespeicherte Adresse verschickt. Du kannst sie zum Einloggen und zum ändern des Passworts verwenden. Solltest du innerhalb der nächsten paar Minuten keine Mail bekommen, schicke bitte eine Anfrage nach Hilfe ab!");
            output("Email: ".$row[emailaddress]." ");
                }else{
                output("`#Bei diesem Account wurde keine E-Mail Adresse angegeben. Wir können mit dem vergessenen Passwort nicht helfen.");
            }
        }else{
            output("`#Dieser Charakter kann nicht gefunden werden. Suche mal in der Kriegerliste danach, vielleicht wurde der Charakter gelöscht.");
        }
    }else{
        output("<form action='create.php?op=forgot' method='POST'>
        `bVergessenes Passwort:`b`n`n
        Gebe den Namen deines Charakters ein (ohne Titel): <input name='charname'>`n
        <input type='submit' class='button' value='Passwort per Mail zuschicken'>
        </form>",true);
addnav("zum Login","index.php");
    }
}
page_header("Charakter erstellen");
if ($_GET['op']=="create"){
    if(getsetting("spaceinname",0) == 0) {
          $shortname = preg_replace("([^[:alpha:]_-])","",$_POST['name']);
    } else {
          $shortname = preg_replace("([^[:alpha:] _-])","",$_POST['name']);
    }

    if (soap($shortname)!=$shortname){
        output("`\$Fehler`^: Unzulässiger Name. Bitte überdenke deinen Namen nochmal.");
        $_GET['op']="";
    }else{
        $blockaccount=false;
        if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1){
            $sql = "SELECT login FROM accounts WHERE emailaddress='".$_POST['email']."'";
            $result = db_query($sql);
            if (db_num_rows($result)>0){
                $blockaccount=true;
                $msg.="Du kannst nur einen Account haben.`n";
            }
        }
        $sql = sprintf("SELECT login FROM accounts WHERE login='%s'", $_POST['name']);
        $result = db_query($sql);
        if (db_num_rows($result) > 0) {
            $blockaccount = true;
            $msg.="Ein Charakter mit diesem Namen existiert bereits.`n";
        }
        if (strlen($_POST['pass1'])<=3){
            $msg.="Dein Passwort muss mindestens 4 Zeichen lang sein.`n";
            $blockaccount=true;
        }
        if ($_POST[pass1]!=$_POST['pass2']){
            $msg.="Die Passwörter stimmen nicht überein.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)<3){
            $msg.="Dein Name muss mindestens 3 Buchstaben lang sein.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)>25){
            $msg.="Der Name ist zu lang. Maximal 25 Buchstaben zugelassen.`n";
            $blockaccount=true;
        }
        if (getsetting("requireemail",0)==1 && is_email($_POST['email']) || getsetting("requireemail",0)==0){
        }else{
            $msg.="Du musst eine gültige E-Mail Adresse eingeben.`n";
            $blockaccount=true;
        }
        if (!$blockaccount){
            $sql = "SELECT name FROM accounts WHERE login='$shortname'";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0){
                output("`\$Fehler`^: Diesen Namen gibt es schon. Bitte versuchs nochmal.");
                $_GET['op']="";
            }else{
                $title = ($_POST['sex']?"Fremde":"Fremder");
                if (getsetting("requirevalidemail",0)){
                    $emailverification=md5(date("Y-m-d H:i:s").$_POST['email']);
                }
                if ($_GET['r']>""){
                    $sql = "SELECT acctid FROM accounts WHERE login='".rawurldecode($_GET['r'])."'";
                    //$sql = "SELECT acctid FROM accounts WHERE login='{$_GET['r']}'";
                    $result = db_query($sql);
                    $ref = db_fetch_assoc($result);
                    $referer=$ref['acctid'];
                }else{
                    $referer = 1;
                }
                
                $checkrpg = 1;
                $newplayer = 1;
                $biodata = e_rand(1,50);
                
                $sql = "INSERT INTO accounts
                    (name,
                    title,
                    password,
                    sex,
                    login,
                    laston,
                    uniqueid,
                    lastip,
                    superuser,
                    gold,
                    emailaddress,
                    emailvalidation,
                    referer,
                    checkrpg,
                    newplayer,
                    birthday,
                    biodata
                ) VALUES (
                    '$title $shortname',
                    '$title',
                    MD5('".$_POST['pass1']."'),
                    '".$_POST['sex']."',
                    '$shortname',
                    '".date("Y-m-d H:i:s",strtotime(date("c")."-1 day"))."',
                    '".$_COOKIE['lgi']."',
                    '".$_SERVER['REMOTE_ADDR']."',
                    ".getsetting("superuser",0).",
                    ".getsetting("newplayerstartgold",50).",
                    '".$_POST['email']."',
                    '$emailverification',
                    '$referer',
                    '$newplayer',
                    '$checkrpg',
                    '".date("d m Y")."',
                    '$biodata'
                )";
                db_query($sql) or die(db_error(LINK));
                
                $sql1 = "SELECT acctid FROM accounts WHERE name='".$title." ".$shortname."'";
                $result1 = db_query($sql1) or die(db_error(LINK));
                $row1 = db_fetch_assoc($result1);
                
                $sql = "INSERT INTO bios (acctid) VALUES ('".$row1['acctid']."')";
                db_query($sql) or die(db_error(LINK));
                
                if (db_affected_rows(LINK)<=0){
                    output("`\$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal. ");
                }else{
                    if ($emailverification!=""){
                        mail(
                            $_POST['email'],
                            "LoGD Account Verification",
                            "Um deinen LoGD-Account freizuschalten, musst du nur noch auf den folgenden Link klicken.\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$emailverification\n\nDanke für's Spielen!",
                            "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                        );
                        output("`4Eine E-Mail wurde an `\$".$_POST['email']."`4 geschickt, um die Adresse zu bestätigen. Klicke auf den Link darin, um den Account zu aktivieren.`0`n`n");
                    }else{
                        output("<form action='login.php' method='POST'><input name='name' value='".$shortname."' type='hidden'><input name='password' value='".$_POST['pass1']."' type='hidden'>
                        Dein Charaker wurde erstellt. Dein Login Name ist `^$shortname`0.  `n`n",true);
                        output("<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n"
                        .($trash > 0?"Charaktere die nie einloggen werden nach $trash Tag(en) Inaktivität gelöscht.`n":"")
                        .($new > 0?"Charaktere die nie Level 2 erreichen werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
                        .($old > 0?"Charaktere die Level 2 erreicht haben werden nach $old Tag(en) Inaktivität gelöscht.":"")
                        ."",true);
                        addnav("","login.php");
                        savesetting("newplayer",addslashes("$title $shortname"));
                        output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trägst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die Adressen ein. Bei anderen Browsern gibt es ähnliche Einstellungen.");
                    }
                }
            }
        }else{
            output("`\$Fehler`^:`n$msg`&");
            $_GET['op']="";
        }
    }
}
if ($_GET['op']==""){
    output("<form action=\"create.php?op=create".($_GET['r']>""?"&r=".$_GET['r']:"")."\" method='POST'>",true);
    output("<table border='0' cellpadding='1' cellspacing='1' align='center'><tr>",true);
    output("<td colspan='2'>`&`c`bCharakter erstellen`b`c`0</td></tr>",true);
    output("<tr><td>Wie willst du in dieser Welt heissen?</td><td><input name='name'></td></tr>",true);
    output("<tr><td>Dein Passwort:</td><td><input type='password' name='pass1'></td></tr>",true);
    output("<tr><td>Passwort bestätigen:</td><td><input type='password' name='pass2'></td></tr>",true);
    output("<tr><td>Deine Email Adresse:</td><td><input name='email'> ".(getsetting("requireemail",0)==0?"(freiwillige Angabe -- aber wenn du keine eingibst, kann dein Account nicht gerettet werden, wenn du dein Passwort vergisst!)":"(benötigt".(getsetting("requirevalidemail",0)==0?"":", eine E-Mail wird zur Bestätigung an diese Adresse geschickt, bevor du einloggen kannst").")")."`n</td></tr>",true);
    output("<tr><td colspan='2'>Du bist <input type='radio' name='sex' value='1'>Weiblich oder <input type='radio' name='sex' value='0' checked>Männlich?</td></tr>",true);
    //Unnötig, da zuvor bereits durch User bestätigt!
    //output("<tr><td colspan='2'>`^`cMit dem Erstellen deines Charakters stimmst du den hier geltenden ><a href='popup_petition.php?op=rules' target='_blank' onClick=\"".popup("popup_petition.php?op=rules").";return false;\">Regeln</a>< zu!`c</td></tr>",true);
    output("<tr><td colspan='2'>`c<input type='submit' class='button' value='Charakter erstellen'>`c",true);
    output("</table></form>",true);
addnav("Login","index.php");
}



if ($_GET['op']=="rules"){

page_header("Die Regeln in New Orleans");

addnav("Login","index.php");

output("
<font color=#800000>`bEin Hinweis vorweg:`b`n`n</font>
<div align=justify><font color=#A26844>Die Regeln in der Stadt New Orleans dienen ihren Bewohnern zur <i>Orientierung und Information</i> und muss von jedem Spieler selbstständig gelesen werden!
Mit dem <b>betreten</b> New Orleans <b>stimmst du den Regeln automatisch zu</b>. Solltest du Fragen haben, oder ist dir etwas unklar, so wende dich ruhig ans
Team und frage nach. Es steht dir natürlich auch frei die Regeln nicht zu lesen, doch werden wir dir nicht im Nachhinein die Regeln
vorkauen und deshalb mit dir diskutieren.<br>
Die <i>Regeln unterliegen ständiger Bearbeitung</i>, weshalb es auch von vorteil ist, immer mal wieder reinzuschauen. Bei einem größeren Ausbau
der Regeln erfährst du das aber über die <i><b>MotD</b> Funktion, die zu lesen ebenfalls <b>Pflicht</b> ist</i>.</div></font>

<br><br><br><br>

<u><font color=#A26844>Serverzeit</font></u>
<br><br>
<font color=#A26844>Der Server spielt in der modernen Gegenwart. Bitte beim Erstellen des Charakters beachten!</font>
<br><br><br>

<b><font color=#800000>§1 Accountregeln</font> </b>
<br><br>
<font color=#A26844>1. Auf diesem Server sind vorerst alle Arten und Vorstellungen von Chars erlaubt, die in die Moderne passen. Highfantasycharaktere 
 wie Elfen und Co sind hier nur ungern gesehen. Bei Unklarheiten kann gern das Team befragt werden.<br>
2. Füll das Feld der Rasse bitte auch mit einer erkennbaren Rasse aus. Für Besonderheiten um den Char kannst du das Feld <i>Spezifizierung</i>
nutzen. Sollte dein Char mehr als eine Rasse besitzen, so trage die ein, die am Stärksten vertreten ist.<br>
3. Trage bitte im entsprechenden Feld deiner Bio den Künstler deines Avatars ein. Diesen brauchen wir, um zu kontrollieren, ob der Avatar
zur Nutzung in Ordnung geht und für dich und die anderen Spieler ist so erkennbar, welches Model schon vergeben ist.<br>
4. Grundsätzlich gestatten wir Doppelbelegungen von Avatarpersonen und im Falle von identischer Fotobenutzung, sprich dich bitte mit dem Spieler ab, der bereits vor dir auf dem
Server war. Im Streitfall gilt: Wer zuerst kommt... Wird der Char verändert, besteht später kein Anspruch mehr auf die Avatarperson.
<br>&raquo; X-Chars in Tagebuch oder OOC gelten nicht als Chars und haben keinerlei Richtwert oder Anspruch.<br>
5. Tags in der Bio müssen geschlossen werden! Machst du ``i/``b/``c... auf, so musst du am Ende ``i/``b/``c... auch wieder zu machen. Tust du das nicht,
verzerrst du damit die komplette folgende Bewohnerliste und kostet das Team und dich unnötig Mühe. <br>
6. Multiaccounts sind auf diesem Server <i>erlaubt</i>. Eine Begrenzung gibt es derzeit nicht.<br>
7. Multiaccounts müssen mit <i>derselben Emailadresse</i> wie der Main angelegt werden.<br>
8. Sollte es in deinem Haushalt mehrere Spieler auf unserem Server geben, so melde uns das bitte gleich bei deiner Bitte um Einlass.<br>
9. Accountsharing erfolgt auf eigene Gefahr und das Team übernimmt dafür keine Haftung!<br>
10. Umbenennungen sind in New Orleans möglich, erfolgen jedoch nur, wenn bereits <i>die neue Biografie samt Avatar eingefügt ist</i>.<br>
11. Sich selbst spielerische Vorteile zu Verschaffen ist <i>verboten</i>! Darunter zählt das zuschustern von Gold und Gems, oder das
ercheaten von RP Punkten. Das Team kann alle Orte einsehen, weshalb der Versuch zwecklos ist. Solltest du es doch versuchen, wird
dir ein altes Backup von deinem Char aufgespielt und das kann notfalls mehrere Monate zurückliegen. Wir raten dir deshalb von solchen
Versuchen ab.<br>
12. Accounts können weder vom Team noch vom Spieler gelöscht werden, sondern verfallen automatisch nach einem halben Jahr (Umbenennungen sind aber möglich).<br>
13. Kein User hat Anspruch auf die Verfügbarkeit des Servers oder Wiederherstellung entstandener Schäden.<br>
14. Die von den Usern erhobenen Daten werden zu internen Zwecken von uns gespeichert, werden jedoch nicht an Dritte weitergegeben.<br>
15. Nicht jeder Char muss einen Titel haben. Wesen wie Engel zum Beispiel können ihren Titel auch weglassen. Schreib uns also ein kleine Begründung, warum dein Char keinen Titel haben sollte und wir kümmern uns dann darum.<br>
</font>
<br><br><br>

<b><font color=#800000>§2 Biografieregeln</font> </b>
<br><br>
<font color=#A26844>1. Achte bitte bei der Wahl deines Namens darauf, dass es sich dabei nicht um einen unseriösen oder anstößigen Namen
handelt. Auch Markennamen und Sonderzeichen sind verboten. Ausnahme bildet das ´ -Zeichen, wenn ihr einen Apostroph im Namen habt.<br>
2. Sieh von Titeln wie Gott, Kaiser, etc ab, um sie den Serverplotspielern auf dem Server zu lassen. Jedoch kann es Ausnahmen geben,
sprich uns also an, wenn du einen solchen Char mit Titel haben möchtest.<br>
3. Wir im Team wissen, dass es immer schwerer wird, die richtigen Bilder für seinen Char zu finden. Trotzdem müssen wir auch hier noch
ein paar Einschränkungen machen:
<blockquote>
&raquo; Achtet auf das Copyright der Bilder! Dazu später noch mehr.<br>
&raquo; Pornografische oder Gewaltverherrlichende Bilder und Texte sind ebenfalls nicht gestattet. Für Inhalte dieser Art kannst du auf die
ü-18 Biografie zurückgreifen, oder das Bild mit einem FSK18 Tag versehen (geht auch bei Texten ;) ): [fsk18]Text/URL[/fsk18]<br>
&raquo; Sollte es zu Unsicherheiten des Teams bezüglich eines Bioinhalts kommen, so muss im schlimmsten Fall das Entsprechende
gelöscht werden.</blockquote>
<br>
4. Videos und Besucherzähler sind in den Bios nicht gestattet. Hyperlinks und Direktverlinkungen auf <b>öffentliche Seiten</b> sind nicht erlaubt. Lade deine Bilder bitte
bei einem entsprechenden Anbieter wie directupload, oder 666kb hoch und benutze die Direkturl für dein Bild. Auch hier gilt: Wer
Hilfe benötigt, muss fragen.<br>
4a. Im OOC sind Urls für <b>Quellenangaben</b> zu den jeweiligen Seiten und Künstlern, sowie für gif-Bildchen erlaubt.<br>
5. In der Bio und dem OOC ist Musik erlaubt, solange kein Video eingebettet ist und die Musik <b>nicht</b> automatisch abspielt. <br> 

<br><br>
<u>Voraussetzungen zum Einlass:</u><br><br>
&raquo; In New Orleans ist ein <i>Avatar</i> und eine kleine <i>Biografie</i> pflicht. <br>
&raquo; Der Avatar muss unseren Regeln entsprechen und sollte nicht größer als 400x400 Pixel sein.<br>
&raquo; Der Künstler/Das Model muss ausgefüllt sein.<br>
&raquo; Die Bio muss ebenso unseren Regeln entsprechen und mindestens <b>fünf</b> oder mehr <i>ausformulierte</i> Stichpunkte/Sätze beinhalten, die den Charakter beschreiben.<br>
Beispiel: -Käthe machte ihr Abitur in New York.<br>
-Besuchte danach die Uni in LA.<br>
-Arbeitet heute als Lektorin bei Cosmopolitan.<br>
-... <br>
&raquo; Wenn du der Meinung bist, du hast die Auflagen für den Einlass erfüllt, so schreibe uns eine freundlich formulierte Teamanfrage und
und wir kümmern uns schnellstmöglich um dein Anliegen.<br> 
</font>
<br><br>

<b><font color=#800000>§3 Verifizierungsregeln</font> </b>
<br><br>
<font color=#A26844>Genaue Informationen zur Verifizierung findest du in den spezifischen FAQs von New Orleans!<br>
1. Du verpflichtest dich dazu, dich vor sexuellen/gewalttätigen Handlungen im RP darüber zu informieren, ob dein Playpartner ebenfalls verifiziert ist.<br>
2. Nur verifizierte Spieler dürfen Inhalte für über 18jährige ausspielen.<br>
3. Es ist von den Spielern des FSK18-RPs sicherzustellen, dass User ohne Verifizierung keinen Zutritt zu dem Play haben.<br>
4. Eventuelle Strafen treffen sowohl den verifizierten, als auch den nicht-verifizierten Spieler.<br>
5. Es werden nur Multi-Accounts verifiziert die auch die gleiche E-Mail Adresse verwenden.<br>
</font>
<br><br>

<b><font color=#800000>§4 Ingameregeln</font> </b>
<br><br>
<font color=#A26844>
1. Das Lesen der <b>Rasseninfo</b> und der <b>Story</b> ist <b>Pflicht</b>! Wir wünschen uns ein einheitliches Niveau :) <br>
2. Die <b>Regeln des Rates</b> sind ebenso Regeln für den Server und das Spiel. <br>
3. Spiele, die an frei zugänglichen Plätzen laufen, erklären sich damit einverstanden, das Spieler im Auftrag der Story sich dazu schreiben dürfen und
die Situation beeinflussen können.<br>
4. Magische Ringe werden <b>nur vom Team</b> vergeben (und in der Bio kenntlich gemacht). Um einen Ring zu bekommen kann es Turniere geben, oder RP-Events, oder oder oder... <br>
5. Beachtet bitte die Machtverhältnisse! Natürlich kannst du dich mit deinem Partner absprechen und auch mal überwältigt werden, aber dann bitte mit plausibler Begründung. <br>

</font>
<br><br>

<b><font color=#800000>§5 Regeln rund ums RP</font> </b>
<br><br>
<font color=#A26844>
1. Da wir kein reiner ü-18 Server sind, ist es verboten auf öffentlichen Plätzen und Orten, zu denen Minderjährige zugriff haben,
Gewalt verherrlichende, pornografische, oder rassistische Texte zu veröffentlichen.<br>
1a. Desweiteren untersagen wir generell das Ausspielen von Kindesmissbrauch und Leichenschändung.<br> 
2. Unabgesprochenes Powerplay (PP) ist auf unserem Server nicht gern gesehen. Sprich dich vorher mit deinem Gegenüber ab, oder gib
 die Chance noch zu reagieren. Alles andere gilt hier als verboten.<br>
3. RP-Breaks sind nicht immer zu vermeiden, daher bietet New Orleans eine RP-Break-Funktion. Diese ist dafür gedacht, den anderen Spielern
kenntlich zu machen, dass an diesem Ort noch ein Play offen ist. RP-Breaks werden vom Team nach zwei Wochen gelöscht, sollte das Play nicht weitergeführt werden.<br>
4. RP-Breaks ohne deutliche Zeitangabe werden ebenfalls vom Team gelöscht. Deswegen gebt bitte eine konkrete Zeitangabe an, wann du und dein
Partner das RP fortsetzt, wie z.B.: Dienstag, 23.1.2014, abends. Editieren des Eintrags ist möglich und sollte ausgiebig genutzt werden!<br>
4a. Ausnahmen wie Post by Post absprachen sind dementsprechend per Breakfunktion zu kennzeichnen. Das Team behält sich in dem Fall vor, den Ort freizugeben, wenn länger als zwei Wochen nicht gepostet wurde.<br>
5. Für Plays, die absehbar Post-by-Post verlaufen, suche dir mit deinem Partner einen weniger genutzten Ort, oder greife dafür auf
die magischen Orte, oder das Haussystem zurück. Dort bedarf es keiner Rp-Breaks.<br>
6. Breaks auf dem Stadtplatz, im Marktviertel und bei der Passkontrolle sind nicht gestattet und werden umgehend gelöscht!<br>
7. Öffentliche Plätze sind selten nur von zwei Personen besucht, weshalb es durchaus gestattet ist, wenn sich weitere Spieler in
euer Play schreiben. Sollte dies gar nicht in eurem Sinne sein, so greift auf die privaten Orte zurück.<br>
8. ./X-Motes dürfen benutzt werden, doch auf öffentlichen Plätzen ist ein kenntlich machen im RP erforderlich. <br>
9. Inspiration ist bei uns gern gesehen, egal ob Film/Serien/Buch/Computerspielcharakter. Du kannst spielen, was du willst, doch sieh davon ab die
Charaktere zu kopieren und die Bücher etc. 1zu1 nachzuspielen. Diese sind nämlich rechtlich geschützt!<br>
10. In New Orleans gibt es allgemeine Rassenansichten zu den Hauptrassen. Diese zeigen dir, wie wir diese Rassen sehen und wie die anderen Spieler deinen Char wahrnehmen.
</font>
<br><br>

<b><font color=#800000>§6 Out Of Charakter Regeln</font> </b>
<br><br>
<font color=#A26844>1. Das Team von New Orleans setzt einen gepflegten Umgangston voraus, sowohl zwischen Team und User, wie auch
unter den Usern selbst. Wir alle sind Menschen aus Fleisch und Blut und New Orleans bietet nicht die Fläche, um persönliche Probleme
auszuleben.<br>
2. Streitigkeiten sind leider nicht immer zu vermeiden, doch tragt diese nicht auf dem Rücken des Servers und seinen Usern aus.<br>
3. Brauchst du Vermittlungshilfe bei einem Streit, so kannst du dich gerne an das Team wenden und wir versuchen objektiv zu vermitteln.<br>
4. ./X-Motes im OT-Chat sind erlaubt, solange man eindeutig den Char dahinter zuordnen kann.<br>
5. Anweisungen des Teams und deren Helfern ist folge zu leisten. Sie machen ihre Arbeit hauptsächlich für dich und alle User auf dem
Server und wünschen sich ein entspanntes und friedliches Miteinander. Niemand im Team ist an Streit oder Mobbing interessiert.<br>
</font>
<br><br>

<b><font color=#800000>§7 Verbotene Avatare</font> </b>
<br><br>
<font color=#A26844>
1. Das Team von New Orleans kontrolliert zwar nach bestem Wissen und Gewissen alle Biografien, dennoch übernehmen wir keine Haftung für die Inhalte der Biografien der Spieler.`n
1.1 Wir behalten uns vor IP-Adressen auf rechtliche Aufforderung heraus zu geben. Achte deswegen bitte darauf, dass du und wir nie in eine solche Situation geraten müssen. `n
1.2 Die folgenden Künstler und Personen (1.6) haben die Nutzung ihrer Werke `iausdrücklich verboten`i (zt auch gerichtlich). Wer (auch vom Team unbemerkt) eines dieser Bilder verwendet, muss damit rechnen
das unter Umständen rechtliche Schritte gegen ihn eingeleitet werden können.`n
1.3 Schriftlich erteilte Genehmigungen von Künstlern müssen dem Team von New Orleans mitgeteilt und zur Verfügung gestellt werden.`n
1.4 Es muss der Künstler in die Avatarliste eingetragen werden, der dem Bild oder Werk eindeutig zugeordnet werden kann (zb wenn Bilder aus DevArt oä benutzt wird).`n
1.4a Unter Umständen kann es neben dem Künstler in der Avatarliste auch sinnvoll sein, im OOC die Seiten zu verlinken, wo die genutzten Bilder herstammen, um so eindeutig auf den Künstler
zu verweisen. Scheu dich also nicht davon gebrauch zu machen. <br>
1.5 Wir wollen dich als Spieler so wenig in deiner Kreativität einschränken wie möglich, deswegen lies den folgenden Paragraphen sehr genau. Es soll eine Hilfe für dich darstellen und dich
keinesfalls bevormunden. Alles, was im Folgenden nicht aufgelistet ist, verwendest du auf <b>eigene</b> Verantwortung! `n
1.6`n
Darsteller und Künstler mit ausdrücklichem <b>Verbot</b>:`n
# Abigail Halliday`n
# Aiden Gonzales`n
# Alan Gutierrez`n
# Alex Evans`n
# Alex McKee`n
# Alysha Nett`n
# Alyssa Bernal`n
# Alyssa Marie`n
# Andrew Gonzales`n
# Anne Geddes`n
# Apnea`n
# ArenaNet`n
# Audrey Kitching`n
# Baldur\'s Gate`n
# Bellz`n
# Bet Hansen-Buth`n
# Blizzard`n
# Boris Vallejo `n
# Chad de Silva`n
# Cris Ortega (dark-spider DevArt)`n
# Dallas Nagata (Dallasnagata DevArt)`n
# David Garett`n
# Demi Lovato `n
# Denver Kids`n
# Diablo`n
# Diane Özdamar aka Dianae`n
# Die Jungs von \'One Direction\'`n
# Disa Braun`n
# Dorian Cleavenger `n
# Elena Kalis (sugarock99 DevArt)`n
# Emilie Autumn`n
# Erik Range`n
# Fabian Götze`n
# Felix Kroos`n
# Foto Dieter Drescher`n
# Frank Franzetta`n
# Frieda Rose`n
# Gaia Online`n
# Games Workshop`n
# Gonzales Brothers `n
# Grace Dever`n
# Hailey Kent`n
# Hannah Beth `n
# Hannah Beth Jackson`n
# Hartmut Nörenberg || Hart-Worx`n
# Heidi Klum`n
# Heise`n
# Jac Vanec`n
# Jada Pinkett Smith`n
# Jaden Smith`n
# James Ryman`n
# Jason Simmons`n
# Jayden Smith `n
# Jeffrey Star`n
# Josefinejönsson`n
# Julie Bell`n
# Justin Bieber `n
# Kaori Yuki`n
# Karae (Deviantart)`n
# Kayaga`n
# Kedralynn`n
# Kent Twins`n
# Kiki Kannibal`n
# Kristopher Simmons`n
# Krzysztof Domaradzki (StudioKxx)`n
# Lara Jade/Lara Fairie`n
# Lauren Wk`n
# Lee Au`n
# Liliana Sanches (Princess-of-Shadows @DevArt)`n
# Linda Bergkvist (furiae)`n
# Lineage 2 (Ncsoft)`n
# Lois Royo`n
# LukasArts`n
# Mandy Murphy`n
# Manips-of-artist2`n
# Marco Reus`n
# Mario Götze`n
# Martina Brandstetter`n
# Mia Hays`n
# Miley Cyrus `n
# Miss Mosh`n
# MissTerror`n
# Model-kartei.de`n
# Natalie Portman `n
# Navate`n
# Otis Sterling`n
# Ophelia Overdose`n
# Picture Alliance`n
# Rachel Dashae`n
# Raquel Reed`n
# Ryan Ken`n
# Saimain bzw Adele Lorienne S. (DevArt)`n
# Sar Bennet`n
# Saridja Bennett`n
# Séraphine Strange`n
# Sheree Smith`n
# Simmons Brothers `n
# Square Enix`n
# Sony Online Entertainment`n
# STARLET-Modelagentur`n
# Susan Coffey`n
# Take-Two Interactive GmbH`n
# Tektek Dream-Avatar (Gaia online)`n
# TheTragicTruth-Of-Me (Victoria Sims)`n
# Toni Kroos`n
# Trey Smith`n
# TwiggX (DevArt)`n
# Valentin Rahmel`n
# Valentina Kallias`n
# Vampirella87 (DevArt)`n
# Victoria Frances`n
# Walt Disney`n
# Warhammer`n
# William Li`n
# Will Smith`n
# Willow Smith`n
# Winter & Wolf (Girltripped)`n
# Wizards of the coast`n
# www.hellgatelondon.de`n
# www.kagayastudio.com`n
# www.swordsswords.com`n
# Yobtaf`n
# Yvonne Catterfeld`n
# Zemotion (Zhang Jingna)`n
# Zoe Kimball`n
# Zui`n
`n

Allgemeine Verbote:`n
# Screenshots von Videospielen `n
# Bilder von DeviantArt deren Nutzung vom Künstler verboten ist`n
# Bilder von Privatpersonen, ohne Erlaubnis `n
# Bilder mit Wasserzeichen `n
# Bezahlbilder `n
`n

`n`n`n
Gez. Venyce`n 
23.06.2016
</font>
<br><br>

", true);


addnav('','create.php?op=check');
output("<form action='create.php?op=check' method='POST'>`c
<input type='checkbox' name='read' value='1'>`^`iIch habe die RP Regeln von New Orleans gelesen, verstanden und Akzeptiere diese.`i`&`n`n
<input id='Send' type='submit' class='button' value='Akzeptieren'>`c
<script type='text/javascript'>
<!--
var Sekunden = 10;
var Buttonbeschriftung = 'Akzeptieren';
document.getElementById('Send').disabled = true;
for(i = 1; i <= Sekunden; i++)
{
    window.setTimeout('ButtonAktualisieren(' + i + ')', i*1000);
}
window.setTimeout('ButtonFreigeben()', Sekunden*1000);
function ButtonAktualisieren(VergangeneSekunden)
{
    if(VergangeneSekunden == Sekunden)
    {
        document.getElementById('Send').value = Buttonbeschriftung;
    }
    else
    {
        Restsekunden = Sekunden-VergangeneSekunden;
        document.getElementById('Send').value = Buttonbeschriftung  + ' (' + Restsekunden + ') ';
    }
}
function ButtonFreigeben()
{
    document.getElementById('Send').disabled = false;
}
//-->
</script></form>",true);
}
if($_GET['op']=="check"){
    if(empty($_POST['read'])){
       page_header("Die Regeln in New Orleans");
       output("<h3>`n`n`n`n`c`4`bUm in New Orleans spielen zu können musst du die Regeln Akzeptieren!`nGehe zurück und versuchs nochmal!`&`c</h3>",true);
       addnav("zurück","create.php?op=rules");
    }else{
        redirect("create.php");
    }
}


page_footer();

?>

