
<?php

// SQL strict mode ready

// 20140816

// PHP 5.4



require_once("common.php");

$trash = getsetting("expiretrashacct",1);

$newacct = getsetting("expirenewacct",10);

$old = getsetting("expireoldacct",45);



checkban();



if ($_GET['op']=="val"){

    $sql = "SELECT login,name FROM accounts WHERE emailvalidation='".addslashes(stripslashes($_GET['id']))."' AND emailvalidation!=''";

    $result = db_query($sql);

    if (db_num_rows($result)>0) {

        $row = db_fetch_assoc($result);

        if (substr($_GET['id'],0,1)=='x') {

            $form = true;

            if (!empty($_POST['pass1'])) {

                if ($_POST['pass1']!=$_POST['pass2']){

                    output("`#Deine PasswÃ¶rter stimmen nicht Ã¼berein.`n");

                }else{

                    if (strlen($_POST['pass1'])>3){

                        $sql = "UPDATE accounts SET emailvalidation='',password=MD5('{$_POST['pass1']}') WHERE emailvalidation='".addslashes(stripslashes($_GET['id']))."' AND emailvalidation!=''";

                        db_query($sql);

                        output("`#`cDein Passwort wurde geÃ¤ndert. Du kannst jetzt einloggen.`c`0");

                        output("<form action='login.php' method='POST'><input name='name' value=\"{$row['login']}\" type='hidden'><input name='password' value=\"{$_POST['pass1']}\" type='hidden'>

                        Dein Passwort wurde ge&auml;ndert. Dein Login Name ist `^".htmlentities($row['login'])."`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);

                        $form = false;

                    }else{

                        output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");

                    }

                }

            }



            if ($form) {

                output("`&`c`bNeues Passwort wÃ¤hlen`b`c`n");

                output("`0<form action=\"create.php?op=val&id={$_GET['id']}\" method='POST'>",true);

                output("`nDein neues Passwort: <input type='password' name='pass1'>`n",true);

                output("`nPasswort best&auml;tigen: <input type='password' name='pass2'>`n",true);

                output("<input type='submit' class='button' value='Neues Passwort speichern!'>",true);

                output("</form>",true);

            }

        }else {

            $sql = "UPDATE accounts SET emailvalidation='' WHERE emailvalidation='".addslashes(stripslashes($_GET['id']))."' AND emailvalidation!=''";

            db_query($sql);

            output("`#`cDeine E-Mail Adresse wurde bestÃ¤tigt. Du kannst jetzt einloggen.`c`0");

            output("<form action='login.php' method='POST'><input name='name' value=\"{$row['login']}\" type='hidden'><input name='password' value=\"{$row['password']}\" type='hidden'>

            Deine E-Mail Adresse wurde best&auml;tigt. Dein Login Name ist `^".htmlentities($row['login'])."`0.  `n`n<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n"

            .($trash>0?"Charaktere, die nie einloggen, werden nach $trash Tag(en) Inaktivit&auml;t gel&ouml;scht.`n":"")

            .($newacct>0?"Charaktere, die nie Level 2 erreichen, werden nach $newacct Tag(en) Inaktivit&auml;t gel&ouml;scht.`n":"")

            .($old>0?"Charaktere, die Level 2 erreicht haben, werden nach $old Tag(en) Inaktivit&auml;t gel&ouml;scht.":"")

            ."",true);

            output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trÃ¤gst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"VertrauenswÃ¼rdige Sites\" - Sites`i und trÃ¤gst dort die Adressen ein. Bei anderen Browsern gibt es Ã¤hnliche Einstellungen.");

        }

    }else{

        output("`#Deine E-Mail Adresse konnte nicht bestÃ¤tigt werden. MÃ¶glicherweise wurde sie schon bestÃ¤tigt. Versuch mal dich einzuloggen und informiere den Webmaster, wenn es nicht klappt.");

    }

}

if ($_GET['op']=="forgot"){

    if ($_POST['charname']!=""){

        $sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='".addslashes(stripslashes($_POST['charname']))."'";

        $result = db_query($sql);

        if (db_num_rows($result)>0){

            $row = db_fetch_assoc($result);

            if (trim($row['emailaddress'])!=""){

                if ($row['emailvalidation']==""){

                    $row['emailvalidation']=substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);

                    $sql = "UPDATE accounts SET emailvalidation='{$row['emailvalidation']}' where login='{$row['login']}'";

                    db_query($sql);

                }

                mail(

                    $row['emailaddress'],

                    "LoGD Account Verification",

                    "Jemand von ".$_SERVER['REMOTE_ADDR']." hat ein vergessenes Passwort von deinem Accoount angefordert.  Wenn du das warst, ist hier dein"

                    ." Link. Du kannst damit einloggen und dein Passwort im Profil vom Dorfplatz aus einstellen.\n\n"

                    ."Wenn du diese E-Mail nicht angefordert hast, keine Panik! Du hast sie bekommen, sonst niemand."

                    ."\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id={$row['emailvalidation']}\n\nDanke fÃ¼r's Spielen!",

                    "From: ".getsetting('gameadminemail','postmaster@localhost.com')

                );

                output("`#Eine neue BestÃ¤tigungsmail wurde an die mit diesem Account gespeicherte Adresse verschickt. Du kannst sie zum Einloggen und zum Ã¤ndern des Passworts verwenden. Solltest du innerhalb der nÃ¤chsten paar Minuten keine Mail bekommen, schicke bitte eine Anfrage nach Hilfe ab!");

            }else{

                output("`#Bei diesem Account wurde keine E-Mail Adresse angegeben. Wir kÃ¶nnen mit dem vergessenen Passwort nicht helfen.");

            }

        }else{

            output("`#Dieser Charakter kann nicht gefunden werden. Suche mal in der Kriegerliste danach, vielleicht wurde der Charakter gelÃ¶scht.");

        }

    }else{

        output("<form action='create.php?op=forgot' method='POST'>

        `bVergessenes Passwort:`b`n`n

        Gib den Namen deines Charakters ein (ohne Titel): <input name='charname'>`n

        <input type='submit' class='button' value='Passwort per Mail zuschicken'>

        </form>",true);

    }

}

page_header("Charakter erstellen");

if (getsetting("blocknewchar","0")==1){

    output("`^Im Moment sind keine Neuanmeldungen mÃ¶glich.");

}else{

if ($_GET['op']=="create"){

    if(getsetting("spaceinname",0) == 0) {

          $shortname = preg_replace("([^[:alpha:]_-])","",$_POST['name']);

    } else {

          $shortname = preg_replace("([^[:alpha:] _-])","",$_POST['name']);

    }



    if (soap($shortname)!=$shortname){

        output("`\$Fehler`^: UnzulÃ¤ssiger Name. Bitte Ã¼berdenke deinen Namen nochmal.");

        $_GET['op']="";

    }else{

        $blockaccount=false;

        if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1){

            $sql = "SELECT login FROM accounts WHERE emailaddress='".addslashes(stripslashes($_POST['email']))."'";

            $result = db_query($sql);

            if (db_num_rows($result)>0){

                $blockaccount=true;

                $msg.="Du kannst nur einen Account haben.`n";

            }

        }

        if (strlen($_POST['pass1'])<=3){

            $msg.="Dein Passwort muss mindestens 4 Zeichen lang sein.`n";

            $blockaccount=true;

        }

        if ($_POST['pass1']!=$_POST['pass2']){

            $msg.="Die PasswÃ¶rter stimmen nicht Ã¼berein.`n";

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

            $msg.="Du musst eine gÃ¼ltige E-Mail Adresse eingeben.`n";

            $blockaccount=true;

        }

        if (!$blockaccount){

            $sql = "SELECT name FROM accounts WHERE login='$shortname'";

            $result = db_query($sql) or die(db_error(LINK));

            if (db_num_rows($result)>0){

                output("`\$Fehler`^: Diesen Namen gibt es schon. Bitte versuchs nochmal.");

                $_GET['op']="";

            }else{

                $email=addslashes(stripslashes($_POST['email']));

                $sex=(int)($_POST['sex']?"1":"0");

                $title = ($sex?"BauernmÃ¤dchen":"Bauernjunge");

                if (getsetting("requirevalidemail",0)){

                    $emailverification=md5(date("Y-m-d H:i:s").$_POST['email']);

                }

                if ($_GET['r']>""){

                    $sql = "SELECT acctid FROM accounts WHERE login='".rawurldecode($_GET['r'])."'";

                    $result = db_query($sql);

                    $ref = db_fetch_assoc($result);

                    $referer=$ref['acctid'];

                }else{

                    $referer=0;

                }

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

                    birthday,

                    badguy,specialmisc,bufflist,dragonpoints,prefs,donationconfig,buffbackup,history,avatar,message

                ) VALUES (

                    '".$title." ".$shortname."',

                    '".$title."',

                    MD5('".$_POST['pass1']."'),

                    '".$sex."',

                    '".$shortname."',

                    '".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -1 day"))."',

                    '".$_COOKIE['lgi']."',

                    '".$_SERVER['REMOTE_ADDR']."',

                    '".getsetting("superuser",0)."',

                    '".getsetting("newplayerstartgold",50)."',

                    '".$email."',

                    '".$emailverification."',

                    '".$referer."',

                    '".(getsetting("activategamedate","0")?getsetting("gamedate","0000-01-01"):date("Y-m-d"))."',

                    '','','','','','','','','',''

                )";

                db_query($sql);

                $sql="SELECT login from accounts WHERE login = '".$shortname."'";

                if (db_num_rows(db_query($sql))==0){

                    output("`\$Fehler`^: Dein Account konnte aus unbekannten GrÃ¼nden nicht erstellt werden. Versuchs bitte einfach nochmal. ");

                }else{

                    if ($emailverification!=""){

                        mail(

                            $_POST[email],

                            "LoGD Account Verification",

                            "Um deinen LoGD-Account freizuschalten, musst du nur noch auf den folgenden Link klicken.\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=".$emailverification."\n\nDanke fÃ¼r's Spielen!",

                            "From: ".getsetting("gameadminemail","postmaster@localhost.com")

                        );

                        output("`4Eine E-Mail wurde an `\$".$_POST['email']."`4 geschickt, um die Adresse zu bestÃ¤tigen. Klicke auf den Link darin, um den Account zu aktivieren.`0`n`n");

                    }else{

                        output("<form action='login.php' method='POST'><input name='name' value=\"$shortname\" type='hidden'><input name='password' value=\"".stripslashes($_POST['pass1'])."\" type='hidden'>",true);

                        output("Dein Charaker wurde erstellt. Dein Login Name ist `^$shortname`0.  `n`n");

                        output("<input type='submit' class='button' value='Hier klicken zum Einloggen'></form>`n`n",true);

                        output(($trash>0?"Charaktere, die nie einloggen, werden nach $trash Tag(en) Inaktivit&auml;t gel&ouml;scht.`n":"")

                        .($newacct>0?"Charaktere, die nie Level 2 erreichen, werden nach $newacct Tag(en) Inaktivit&auml;t gel&ouml;scht.`n":"")

                        .($old>0?"Charaktere, die Level 2 erreicht haben, werden nach $old Tag(en) Inaktivit&auml;t gel&ouml;scht.":"")

                        ,true);

                        output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trÃ¤gst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"VertrauenswÃ¼rdige Sites\" - Sites`i und trÃ¤gst dort die Adressen ein. Bei anderen Browsern gibt es Ã¤hnliche Einstellungen.");

                    }

                }

            }

        }else{

            output("`\$Fehler`^:`n$msg");

            $_GET['op']="";

        }

    }

}

if ($_GET['op']==""){

    output("`&`c`bCharakter erstellen`b`c`n");

    output("`0`n<form action=\"create.php?op=create".($_GET['r']>""?"&r=".$_GET['r']:"")."\" method='POST'><table border='0'>",true);

    output("<tr><td>Wie willst du in dieser Welt heissen?</td><td><input name='name' value='{$_POST['name']}'></td></tr>",true);

    output("<tr><td>Dein Passwort:</td><td><input type='password' name='pass1'></td></tr>",true);

    output("<tr><td>Passwort best&auml;tigen:</td><td><input type='password' name='pass2'></td></tr>",true);

    output("<tr><td>Deine Email Adresse:</td><td><input name='email' value='{$_POST['email']}'> ".(getsetting("requireemail",0)==0?"(freiwillige Angabe -- aber wenn du keine eingibst, kann dein Account nicht gerettet werden, wenn du dein Passwort vergisst!)":"(ben&ouml;tigt".(getsetting("requirevalidemail",0)==0?"":", eine E-Mail wird zur Best&auml;tigung an diese Adresse geschickt, bevor du einloggen kannst").")")."</td></tr>",true);

    output("<tr><td colspan='2'>Du bist <input type='radio' name='sex' value='1'>Weiblich oder <input type='radio' name='sex' value='0' checked>M&auml;nnlich?</td></tr>",true);

    output("<tr><td colspan='2'>`n`^Mit dem Erstellen deines Charakters stimmst du den hier geltenden ><a href='petition.php?op=rules' target='_blank'>Regeln</a>< zu!`n</td></tr>",true);

    output("<tr><td colspan='2' align='center'><input type='submit' class='button' value='Charakter erstellen'></td></tr></table>",true);

}

}

addnav("Startseite","index.php");

page_footer();

?>

