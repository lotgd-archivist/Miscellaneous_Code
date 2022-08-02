<?php
/*
2014 by aragon
-03-22 - password > wird jetzt anders gespeichert
*/
require_once "common.php";
require_once "lib/logd_pw.php";

$trash = getsetting("expiretrashacct",1);
$new = getsetting("expirenewacct",10);
$olddk = getsetting("expireoldacct",45);
$old = $olddk - 10;

checkban();

if ($_GET[op]=="val"){
    $sql = "SELECT login,name FROM accounts WHERE emailvalidation='$_GET[id]' AND emailvalidation!=''";
    $result = db_query($sql);
    if (db_num_rows($result)>0) {
        $row = db_fetch_assoc($result);
        if (substr($_GET['id'],0,1)=='x') {
            $form = true;
            if (!empty($_POST['pass1'])) {
                if ($_POST['pass1']!=$_POST['pass2']){
                    output("`#Deine Passwörter stimmen nicht überein.`n");
                }else{
                    if (strlen($_POST['pass1'])>3){
                        $pw=logd_pw($_POST[pass1]);
                        $sql = "UPDATE accounts SET emailvalidation='',password='".$pw."' WHERE emailvalidation='$_GET[id]' AND emailvalidation!=''";
                        db_query($sql);
                        output("`#`cDein Passwort wurde geändert. Du kannst jetzt einloggen.`c`0");
                        output("<form action='login.php' method='POST'><input name='name' value=\"$row[login]\" type='hidden'><input name='password' value=\"$_POST[pass1]\" type='hidden'>
                        Dein Passwort wurde geändert. Dein Login Name ist `^$row[login]`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);
                        $form = false;
                    }else{
                        output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");
                    }
                }
            }

            if ($form) {
                output("`&`c`bNeues Passwort wählen`b`c`n");
                output("`0<form action=\"create.php?op=val&id=$_GET[id]\" method='POST'>",true);
                output("`nDein neues Passwort: <input type='password' name='pass1'>`n",true);
                output("`nPasswort bestätigen: <input type='password' name='pass2'>`n",true);
                output("<input type='submit' class='button' value='Neues Passwort speichern!'>",true);
                output("</form>",true);
            }
        }
        else {
            $sql = "UPDATE accounts SET emailvalidation='' WHERE emailvalidation='$_GET[id]' AND emailvalidation!=''";
            db_query($sql);

            if (getsetting("needadminactivation",0)==0) {
                output("`#`cDeine E-Mail Adresse wurde bestätigt. Du kannst jetzt einloggen.`c`0");
                output("<form action='index.php' method='POST'>
                Deine E-Mail Adresse wurde bestätigt. Dein Login Name ist `^$row[login]`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);
            }
            else {
                output("`#`cDeine E-Mail Adresse wurde bestätigt. Sobald dein Account von einem Admin aktiviert wurde, kannst du dich einloggen.`c`0`n`n");
            }
        }
        output(($trash>0?"Charaktere die nie einloggen werden nach $trash Tag(en) Inaktivität gelöscht.`n":"")
        .($new>0?"Charaktere die nie Level 2 erreichen werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
        .($old>0?"Charaktere die Level 2 erreicht haben werden nach $old Tag(en) Inaktivität gelöscht.`n":"")
        .($old>0?"Charaktere die 2 Drachenkills erreicht haben werden nach $olddk Tag(en) Inaktivität gelöscht.":"")
        ."",true);
        output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trägst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die Adressen ein. Bei anderen Browsern gibt es ähnliche Einstellungen.");
    }else{
        output("`#Deine E-Mail Adresse konnte nicht bestätigt werden. Möglicherweise wurde sie schon bestätigt. Versuch mal dich einzuloggen und informiere den Webmaster, wenn es nicht klappt.");
    }
}
if ($_GET[op]=="forgot"){
    if ($_POST[charname]!=""){
        $sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='$_POST[charname]'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if (trim($row[emailaddress])!=""){
                if ($row[emailvalidation]==""){
                    $row[emailvalidation]=substr("x".md5(date("Y-m-d H:i:s").$row[password]),0,32);
                    $sql = "UPDATE accounts SET emailvalidation='$row[emailvalidation]' where login='$row[login]'";
                    db_query($sql);
                }
                mail(
                    $row[emailaddress],
                    "LoGD Account Verification",
                    "Jemand von ".$_SERVER['REMOTE_ADDR']." hat ein vergessenes Passwort von deinem Accoount angefordert. Wenn du das warst, ist hier dein"
                    ." Link. Du kannst damit einloggen und dein Passwort im Profil vom Dorfplatz aus einstellen.\n\n"
                    ."Wenn du diese E-Mail nicht angefordert hast, keine Panik! Du hast sie bekommen, sonst niemand."
                    ."\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$row[emailvalidation]\n\nDanke für's Spielen!",
                    "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                );
                output("`#Eine neue Bestätigungsmail wurde an die mit diesem Account gespeicherte Adresse verschickt. Du kannst sie zum Einloggen und zum ändern des Passworts verwenden. Solltest du innerhalb der nächsten paar Minuten keine Mail bekommen, schicke bitte eine Anfrage nach Hilfe ab!");
            }else{
                output("`#Bei diesem Account wurde keine E-Mail Adresse angegeben. Wir können mit dem vergessenen Passwort nicht helfen.");
            }
        }else{
            output("`#Dieser Charakter kann nicht gefunden werden. Suche mal in der Kriegerliste danach, vielleicht wurde der Charakter gelöscht.");
        }
    }else{
        output("<form action='create.php?op=forgot' method='POST'>
        `bVergessenes Passwort:`b`n`n
        Gebe den Namen deines Charakters ein (ohne Titel): <input name='charname'>`n
        <input type='submit' class='button' value='Passwort per Mail zuschicken'>
        </form>",true);
    }
}
page_header("Charakter erstellen");
if ($_GET[op]=="create"){
    setlocale(LC_ALL,'de_DE');
    if(getsetting("spaceinname",0) == 0) {
          $shortname = preg_replace("([^[:alpha:]_-])","",$_POST[name]);
    } else {
          $shortname = preg_replace("([^[:alpha:] _-])","",$_POST[name]);
    }

    if (soap($shortname)!=$shortname){
        output("`\$Fehler`^: Unzulässiger Name. Bitte überdenke deinen Namen nochmal.");
        $_GET[op]="";
    }else{
        $blockaccount=false;
        if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1){
            $sql = "SELECT login FROM accounts WHERE emailaddress='$_POST[email]'";
            $result = db_query($sql);
            if (db_num_rows($result)>0){
                $blockaccount=true;
                $msg.="Du kannst nur einen Account haben.`n";
            }
        }
        if (strlen($_POST[pass1])<=3){
            $msg.="Dein Passwort muss mindestens 4 Zeichen lang sein.`n";
            $blockaccount=true;
        }
        if ($_POST[pass1]!=$_POST[pass2]){
            $msg.="Die Passwörter stimmen nicht überein.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)<3){
            $msg.="Dein Name muss mindestens 3 Buchstaben lang sein.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)>25){
            $msg.="Der Name ist zu lang. Maximal 25 Buchstaben zugelassen.`n";
            $blockaccount=true;
        }
        if (getsetting("requireemail",0)==1 && is_email($_POST[email]) || getsetting("requireemail",0)==0){
        }else{
            $msg.="Du musst eine gültige E-Mail Adresse eingeben.`n";
            $blockaccount=true;
        }
        /*
        if ($_POST[pass1]==$_POST[pass2]
        && strlen($_POST[pass1])>3
        && strlen($shortname)>2
        && !$blockaccount
        && (
                getsetting("requireemail",0)==1
            && is_email($_POST[email])
            || getsetting("requireemail",0)==0
                )
        ){*/
        if (!$blockaccount){
            $sql = "SELECT name FROM accounts WHERE login='$shortname'";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0){
                output("`\$Fehler`^: Diesen Namen gibt es schon. Bitte versuchs nochmal.");
                $_GET[op]="";
            }else{
                $title = ($_POST[sex]?"Bauernmädchen":"Bauernjunge");
                if (getsetting("requirevalidemail",0)){
                    $emailverification=md5(date("Y-m-d H:i:s").$_POST[email]);
                }
                if (getsetting("needadminactivation",0)==1) $activated = 0;
                else $activated = 1;
                if ($_GET['r']>""){
                    $sql = "SELECT acctid FROM accounts WHERE login='".rawurldecode($_GET['r'])."'";
                    $result = db_query($sql);
                    $ref = db_fetch_assoc($result);
                    $referer=$ref['acctid'];
                }else{
                    $referer=0;
                }



                $sql = "INSERT INTO accounts
                    (name,
                    title,
                    password,
                    defence,
                    attack,
                    maxhitpoints,
                    sex,
                    login,
                    laston,
                    uniqueid,
                    lastip,
                    superuser,
                    gold,
                    emailaddress,
                    emailvalidation,
                    activated,
                    referer
                ) VALUES (
                    '$title $shortname',
                    '$title',
                    '" . logd_pw($_POST[pass1]) . "',
                    '1',
                    '1',
                    '10',
                    '$_POST[sex]',
                    '$shortname',
                    '".date("Y-m-d H:i:s",strtotime("-1 day"))."',
                    '$_COOKIE[lgi]',
                    '".$_SERVER['REMOTE_ADDR']."',
                    ".getsetting("superuser",0).",
                    ".getsetting("newplayerstartgold",50).",
                    '$_POST[email]',
                    '$emailverification',
                    '$activated',
                    '$referer'
                )";
                db_query($sql) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                    output("`\$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal. ");
                }else{
                    $session['user']['acctid'] = db_insert_id(LINK);
                    $sql = 'INSERT INTO accounts_text (acctid) VALUES ('.$session['user']['acctid'].')';
                    db_query($sql);
                    if ($referer>0) {
                        debuglog("was recruited by", $referer);
                    }
                    unset($session['user']['acctid']);
                    if ($emailverification!=""){
                        mail(
                            $_POST[email],
                            "LoGD Account Verification",
                            "Um deinen LoGD-Account freizuschalten, musst du nur noch auf den folgenden Link klicken.\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$emailverification\n\nDanke für's Spielen!",
                            "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                        );
                        output("`4Eine E-Mail wurde an `\$$_POST[email]`4 geschickt, um die Adresse zu bestätigen. Klicke auf den Link darin, um den Account zu aktivieren.`0`n`n");
                    }else{
                        if ($activated==1) {
                            output("<form action='login.php' method='POST'><input name='name' value=\"$shortname\" type='hidden'><input name='password' value=\"$_POST[pass1]\" type='hidden'>
                                            Dein Charaker wurde erstellt. Dein Login Name ist `^$shortname`0.  ",true);
                            output("`n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);
                        }
                        else {
                            output("Dein Charaker wurde erstellt. Dein Login Name ist `^$shortname`0.  ");
                            output("`n`nBevor du dich einloggen kannst, muss dein Account noch von einem Admin freigeschaltet werden.`n`n");
                        }
                        output(($trash>0?"Charaktere die nie einloggen werden nach $trash Tag(en) gelöscht.`n":"")
                        .($new>0?"Charaktere die nie Level 2 erreichen werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
                        .($old>0?"Charaktere die Level 2 erreicht haben werden nach $old Tag(en) Inaktivität gelöscht.`n":"")
                        .($old>0?"Charaktere die 2 Drachenkills erreicht haben werden nach $olddk Tag(en) Inaktivität gelöscht.":"")
                        ."",true);
                        savesetting("newplayer",addslashes("$title $shortname"));
                        output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trägst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die Adressen ein. Bei anderen Browsern gibt es ähnliche Einstellungen.");
                    }
                }
            }
        }else{
            output("`\$Fehler`^:`n$msg");
            $_GET[op]="";
        }
    }
}
if ($_GET[op]==""){
    output("`&`c`bCharakter erstellen`b`c`n");
    $maxuser=getsetting("maxuser",700);
    $sql="SELECT * FROM accounts";
    $result = db_query($sql);
    if (db_num_rows($result)>=$maxuser){
        output("Vielen Dank für dein Interesse mitzuspielen, aber die Usergrenze von $maxuser ist bereits erreicht. Versuche es in Kürze wieder, wenn du dann noch spielen willst. Bis dahin hat vielleicht jemand aufgehört.");
    }
    else
    {
        output("`0<form action=\"create.php?op=create".($_GET['r']>""?"&r=".$_GET['r']:"")."\" method='POST'>",true);
        output("`nWie willst du in dieser Welt heissen? <input name='name'>`n",true);
        output("`nDein Passwort: <input type='password' name='pass1'>`n",true);
        output("`nPasswort bestätigen: <input type='password' name='pass2'>`n",true);
        output("`nDeine Email Adresse: <input name='email'> ".(getsetting("requireemail",0)==0?"(freiwillige Angabe -- aber wenn du keine eingibst, kann dein Account nicht gerettet werden, wenn du dein Passwort vergisst!)":"(benötigt".(getsetting("requirevalidemail",0)==0?"":", eine E-Mail wird zur Bestätigung an diese Adresse geschickt, bevor du einloggen kannst").")")."`n",true);
        output("`nDu bist <input type='radio' name='sex' value='1'>Weiblich oder <input type='radio' name='sex' value='0' checked>Männlich?`n`n",true);
        output("`^Mit dem Erstellen deines Charakters stimmst du den hier geltenden ><a href='petition.php?op=rules' target='_blank'>Regeln</a>< zu!`n`n",true);
        output("<input type='submit' class='button' value='Charakter erstellen'>",true);
    }
}
addnav("Login","index.php");
page_footer();
?>