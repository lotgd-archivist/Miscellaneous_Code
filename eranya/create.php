
<?php
/**
* create.php:        Script zur Erstellung eines neuen Accounts
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/
require_once('common.php');

page_header( getsetting('townname','Atrahor').' - Registratur' );                

function show_created_screen () {
        global $str_name,$str_pass1,$trash,$new,$old;
        
        $trash = getsetting("expiretrashacct",1);
        $new = getsetting("expirenewacct",10);
        $old = getsetting("expireoldacct",45);
        
        // temporär deaktiviert, da fehlerhaft
        /*output("<form action='login.php' method='POST'>
                        <input name='name' value=\"".$str_name."\" type='hidden'>
                        <input name='hidden_pw' value=\"".$str_pass1."\" type='hidden'>
                        Dein Login-Name ist `^".$str_name."`0.`n`n
                        <input type='submit' class='button' value='Hier klicken zum Einloggen!'>
                        </form>`n`n",true);*/
        output("`c`#Dein Login-Name lautet `^".$str_name."`0.`n`n
               <a href='index.php' class='button'>Auf der Startseite einloggen</a>
               `c`n`n",true);
        output(($trash>0?"Charaktere, die nie einloggen, werden nach $trash Tag(en) Inaktivität gelöscht.`n":"")
        .($new>0?"Charaktere, die nie Level 2 erreichen, werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
        .($old>0?"Charaktere, die Level 2 erreicht haben, werden nach $old Tag(en) Inaktivität gelöscht.":"")
        ."",true);
        output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen! Im Internet Explorer 6 klickst du dazu `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i und trägst dort die URL dieses Servers (".getsetting("serverurl","www.anpera.net").") als `iZugelassen`i ein. Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die Adressen ein. Bei anderen Browsern gibt es ähnliche Einstellungen.");
}

addnav('Startseite','index.php');

// Filter auf PC checken
checkban();

$str_op = $_GET['op'];

switch($str_op) {

        case 'val':
                
                $str_vali = $_GET['id'];
                
                $sql = "SELECT login,name,password,emailaddress,uniqueid,lastip FROM accounts WHERE emailvalidation='$str_vali' AND emailvalidation!=''";
                $result = db_query($sql);
                // Wenn Account mit dieser ValidierungsID existiert
                if (db_num_rows($result)>0)
                {
                                                        
                        $row = db_fetch_assoc($result);
                        
                        checkban($row['login'], $row['lastip'], $row['uniqueid'], $row['emailaddress']);
                        
                        // Passwort vergessen, neues aussuchen
                        if (substr($str_vali,0,1)=='x')
                        {
                                $str_pass1 = $_POST['pass1'];
                                $str_pass2 = $_POST['pass2'];
                        
                                $form = true;
                                if (!empty($str_pass1))
                                {
                                        $form = false;
                                        if ($str_pass1 != $str_pass2)
                                        {
                                                output("`#Deine Passwörter stimmen nicht überein.`n");
                                                $form = true;                                        
                                        }
                                        
                                        if (strlen($str_pass1)<=5)
                                        {
                                                output("`#Dein Passwort ist zu kurz. Es muss mindestens 6 Zeichen lang sein.`n");
                                                $form = true;
                                        }
                                        
                                        if(ctype_alpha($str_pass1)) {
                                                output("`#Dein Passwort besteht nur aus Buchstaben, deshalb ist es nicht sicher genug. Bitte suche dir ein Passwort aus, dass mindestens aus Buchstaben `bund`b Zahlen besteht.`n");
                                                $form = true;
                                        } elseif(ctype_digit($str_pass1)) {
                                                output("`#Dein Passwort besteht nur aus Zahlen, deshalb ist es nicht sicher genug. Bitte suche dir ein Passwort aus, dass mindestens aus Buchstaben `bund`b Zahlen besteht.`n");
                                                $form = true;
                                        }
                                        
                                        // Mit den Passwörtern stimmt alles
                                        if(!$form) {
                                                $sql = "UPDATE accounts SET emailvalidation='',password='".get_hash($str_pass1)."' WHERE emailvalidation='$str_vali' AND emailvalidation!=''";
                                                db_query($sql);
                                                output("`#`cDein Passwort wurde geändert. Du kannst jetzt einloggen.`c`0");
                                                
                                                $row['password'] = get_hash($str_pass1);
                                                
                                                show_created_screen();
                                                                                
                                                $form = false;
                                        }
                                        
                                }        // END Wenn Pw gegeben
                                
                                if ($form)
                                {
                                        $arr_form = array('pass1'=>'Dein neues Passwort:,password',
                                                          'pass2'=>'Passwort bestätigen:,password');
                                                                                
                                        $str_lnk = 'create.php?op=val&id='.$str_vali;
                                
                                        output("`&`c`bNeues Passwort wählen`b`c`n");
                                        output("`0<form action=\"$str_lnk\" method='POST'>",true);
                                        showform($arr_form,array(),false,'Neues Passwort speichern!');
                                        output("</form>",true);
                                }
                                
                        }
                        // Standard der EMail-Aktivierung
                        else
                        {
                                                                                                
                                $sql = "UPDATE accounts SET emailvalidation='' WHERE emailvalidation='$str_vali' AND emailvalidation!=''";
                                db_query($sql);
                                output("`#`cDeine E-Mail Adresse wurde bestätigt. Du kannst jetzt einloggen.`c`0");
                                
                                show_created_screen();
                                
                                savesetting("newplayer",addslashes($row['name']));
                                
                        }
                }
                else
                {
                        output("`#Deine E-Mail Adresse konnte nicht bestätigt werden. Möglicherweise wurde sie schon bestätigt. Versuch mal dich einzuloggen und schreibe eine Anfrage, falls es nicht klappt.");
                        page_footer();
                        exit;
                }
        // END Validierung        
        break;
        
        // Passwort vergessen
        case 'forgot':
        
                $str_login = $_POST['charname'];
                                        
                if (!empty($str_login))
                {
                        checkban($str_login);
                
                        $sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='$str_login'";
                        $result = db_query($sql);
                        
                        // Wenn Account gefunden
                        if (db_num_rows($result)>0)
                        {
                                $row = db_fetch_assoc($result);
                                
                                // Wenn gültige Emailadresse
                                if ( is_email( $row['emailaddress'] ) )
                                {
                                        // Wenn Validierung noch nicht aktiviert, nun vornehmen
                                        if ($row['emailvalidation']=='')
                                        {
                                                $row['emailvalidation']=substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);
                                                $sql = "UPDATE accounts SET emailvalidation='$row[emailvalidation]' where login='$row[login]'";
                                                db_query($sql);
                                        }                                        
                                        // EMail versenden
                                        send_email($row['emailaddress'],getsetting('townname','Atrahor')."-Account: Passwort vergessen",
                                        "Lieber Spieler, liebe Spielerin,\n\njemand von ".$_SERVER['REMOTE_ADDR']." hat ein vergessenes Passwort von deinem Account angefordert.  Wenn du das warst, ist hier dein Link. Du kannst dich damit einloggen und dein Passwort im Profil einstellen.\n\nWenn du diese E-Mail nicht angefordert hast, keine Panik! Nur du hast sie bekommen, sonst niemand.\n\nhttp://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=".$row[emailvalidation]."\n\nDanke für's Spielen!\n\nDein ".getsetting('townname','Atrahor')."-Team");
                                        output("`#Eine neue Bestätigungsmail wurde an die mit diesem Account verknüpfte E-Mail-Adresse verschickt. Du kannst sie zum Einloggen und zum Ändern des Passworts verwenden. Solltest du innerhalb der nächsten paar Minuten keine E-Mail bekommen, schicke bitte eine Anfrage an das Adminteam.");
                                        
                                }
                                else
                                {
                                        output("`#Bei diesem Account wurde keine gültige E-Mail Adresse angegeben. Wir können mit dem vergessenen Passwort nicht helfen.");
        
                                }
                        }
                        else
                        {
                                output("`#Dieser Charakter kann nicht gefunden werden. Suche mal in der Einwohnerliste danach, vielleicht wurde der Charakter gelöscht.");
                        }
                }
                // Noch keine Passwort-Anfrage abgeschickt, Formular anzeigen
                else
                {
                        $arr_form = array('charname'=>'Gebe den Login-Namen deines Charakters ein (ohne Titel):');
                        
                        $str_lnk = 'create.php?op=forgot';
                        
                        output('`c`&`bVergessenes Passwort:`b`c`n`n
                                                <form action="'.$str_lnk.'" method="POST">',true);
                
                        showform($arr_form,array(),false,'Passwort per E-Mail zuschicken');
                        
                        output('</form>',true);
                }                
        
        // END Passwort vergessen
        break;                
        
        // Standard: Charakter erstellen - Formular anzeigen
        default:
                                        
                // Wenn keine Neuanmeldungen möglich
                if (getsetting("blocknewchar","0")==1)
                {
                        output("`^Im Moment sind keine Neuanmeldungen möglich.");
                        page_footer();
                        exit;
                }                
                
                // Anmeldeform. abgeschickt
                if ($str_op == 'create')
                {        
                
                        $str_pass1 = $_POST['pass1'];
                        $str_pass2 = $_POST['pass2'];
                        $str_name = trim($_POST['name']);
                        $str_mail = $_POST['email'];
                        
                        // EMail checken
                        // Emailaddy gegeben?                                
                        if ( (getsetting("requireemail",0)==1 && is_email($str_mail)) || getsetting("requireemail",0)==0)
                        {
                                
                                // Ban?
                                if (checkban(false, false, false, $str_mail, 0, false))
                                {
                                        output('`c`b`$Fehler:`0`b`n`n');
                                        output("Du bist hier nicht erwünscht (E-Mail Adresse gesperrt).`c`n");
                                        page_footer();
                                        exit;
                                }
                                
                                // Blacklist?
                                if( check_blacklist( BLACKLIST_EMAIL, stripslashes(strtolower($str_mail)) ) ) 
                                {
                                        output('`c`b`$Fehler:`0`b`n`n');
                                        output("Du bist hier nicht erwünscht (E-Mail Adresse verboten).`c`n");
                                        page_footer();
                                        exit;
                                }
                                
                                // Auf doppelte Emailaddys checken
                                if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1)
                                {
                                        $sql = "SELECT login FROM accounts WHERE emailaddress='$str_mail'";
                                        $result = db_query($sql);
                                        if (db_num_rows($result)>0)
                                        {
                                                $blockaccount=true;
                                                $msg.="Du kannst nur einen Account pro Emailadresse haben.`n";
                                        }
                                }
                        }
                        else
                        {
                                $msg.="Du musst eine gültige E-Mail Adresse eingeben.`n";
                                $blockaccount=true;
                        }
                        
                        // Passwörter
                        // Passwort zu kurz
                        if (strlen($str_pass1)<=5)
                        {
                                $msg.="Dein Passwort muss mindestens 6 Zeichen lang sein.`n";
                                $blockaccount=true;
                        }
                        
                        // Passwortkontrolle falsch
                        if ($str_pass1!=$str_pass2)
                        {
                                $msg.="Die Passwörter stimmen nicht überein.`n";
                                $blockaccount=true;
                        }
                        
                        if(ctype_alpha($str_pass1)) {
                                output("`#Dein Passwort besteht nur aus Buchstaben, deshalb ist es nicht sicher genug. Bitte suche dir ein Passwort aus, dass mindestens aus Buchstaben `bund`b Zahlen besteht.`n");
                                $blockaccount = true;
                        } elseif(ctype_digit($str_pass1)) {
                                output("`#Dein Passwort besteht nur aus Zahlen, deshalb ist es nicht sicher genug. Bitte suche dir ein Passwort aus, dass mindestens aus Buchstaben `bund`b Zahlen besteht.`n");
                                $blockaccount = true;
                        }
                        
                        // Name checken
                        // Auf jeden Fall Formatierungstags raus
                        $str_name = strip_tags(strip_appoencode($str_name,3));
                        
                        // Auf Korrektheit prüfen
                        $str_valid = user_rename(0, stripslashes($str_name), false, false);
                                                                        
                        if(true !== $str_valid) {
                                
                                switch($str_valid) {
                                        
                                        case 'login_banned':
                                                $msg .= 'Dieser Name ist gebannt!';                                                
                                        break;
                                        
                                        case 'login_blacklist':
                                                $msg .= 'Dieser Name ist verboten!';                                                
                                        break;
                                        
                                        case 'login_dupe':
                                                $msg .= 'Diesen Namen gibt es leider schon!';                                                
                                        break;
                                        
                                        case 'login_tooshort':
                                                $msg .= 'Dein gewählter Name ist zu kurz (Min. '.getsetting('nameminlen',3).' Zeichen)!';                                                
                                        break;
                                        
                                        case 'login_toolong':
                                                $msg .= 'Dein gewählter Name ist zu lang (Max. '.getsetting('namemaxlen',3).' Zeichen)!';                                                
                                        break;
                                        
                                        case 'login_badword':
                                                $msg .= 'Dein gewählter Name enthält unzulässige Begriffe!';                                                
                                        break;
                                        
                                        case 'login_spaceinname':
                                                $msg .= 'Dein gewählter Name enthält Leerzeichen, was leider nicht erlaubt ist!';                                                
                                        break;
                                        
                                        case 'login_specialcharinname':
                                                $msg .= 'Dein gewählter Name enthält Sonderzeichen, was leider nicht erlaubt ist!';                                                
                                        break;
                                        
                                        case 'login_criticalcharinname':
                                                $msg .= 'Dein gewählter Name enthält eines der folgenden Zeichen, die für einen Namen nicht geeignet sind:`n
                                                                '.str_replace('\\','',getsetting('criticalchars',''));                                                
                                        break;
                                        
                                        case 'login_titleinname':
                                                $msg .= 'Dein gewählter Name enthält einen Titel, der ein Teil des Spiels ist!';                                                
                                        break;
                                        
                                        default:
                                                $msg .= 'Irgendwas stimmt mit deinem Namen nicht, ich weiß nur nicht was ; ) Schreibe bitte eine Anfrage!';
                                        break;
                                        
                                }
                                
                                $blockaccount = true;
                                
                        }
                                                                
                        // Account anlegen!
                        if (!$blockaccount)
                        {
                                                                                                                                                        
                                $int_sex = $_POST['sex']==1 ? 1 : 0;
                                
                                // Namen in reiner Großschreibung verhindern
                                if(!getsetting('allletter_up_allow',1)) {
                                        if(ctype_upper($str_name)) {
                                                $str_name = strtolower($str_name);
                                        }
                                }
                                // 1. Buchstabe immer groß
                                if(getsetting('firstletter_up',1)) {
                                        $str_name = ucfirst($str_name);
                                }
                                                                
                                //Getting the titles from the settings table
                                //Dragonslayer
                                $titles = unserialize( stripslashes(getsetting('title_array',null)) );
                                $title = addslashes($titles[0][$int_sex]);
                                
                                // Emailvalidation
                                if (getsetting("requirevalidemail",0))
                                {
                                        $emailverification=md5(date("Y-m-d H:i:s").$str_mail);
                                }
                                
                                // Empfehlung
                                $int_refid = (int)$_GET['r'];
                                $int_demouser_acctid = getsetting('demouser_acctid',5);
                                if ( $int_refid > 0 && $int_refid != $int_demouser_acctid)
                                {
                                        $referer=$int_refid;
                                }
                                else
                                {
                                        $referer=0;
                                }
                                
                                // Datensatz in accounts anlegen
                                $sql = "INSERT INTO accounts 
                                                SET 
                                                        name='$title $str_name',
                                                        title='$title',
                                                        password='".get_hash($str_pass1)."',
                                                        sex=$int_sex,
                                                        login='$str_name',
                                                        laston=NOW(),
                                                        uniqueid='".$_COOKIE['lgi']."',
                                                        lastip='".$_SERVER['REMOTE_ADDR']."',
                                                        gold=".(int)getsetting("newplayerstartgold",50).",
                                                        emailaddress='$str_mail',
                                                        emailvalidation='$emailverification'";                    

                                db_query($sql) or die(db_error(LINK));
                                if (db_affected_rows()<=0)
                                {
                                        output("`\$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal oder schreibe eine Anfrage.");
                                        page_footer();
                                        exit;
                                }
                                
                                // Datensatz für Bios anlegen
                                $int_acctid = db_insert_id();

                                $sql = "INSERT INTO account_bios
                                                SET
                                                        acctid=".$int_acctid.",
                                                        addchars_details='a:0:{}'
                                                                ";
                                db_query($sql) or die(db_error(LINK));

                                // Datensatz in Extra-Info anlegen
                                $int_acctid = db_insert_id();
                                                                                                                                                                                
                                $sql = "INSERT INTO account_extra_info 
                                                SET
                                                        acctid=".$int_acctid.",
                                                        birthday='".convert_rpdate()."',
                                                        referer='".$referer."'
                                                                ";
                                db_query($sql) or die(db_error(LINK));
                                
                                // Datensatz in Statistik anlegen
                                $sql = "INSERT INTO account_stats 
                                                SET
                                                        acctid=".$int_acctid."
                                                ";
                                db_query($sql) or die(db_error(LINK));
                                item_add($int_acctid,'yllwfeath');
                                                                                                                                                                
                                if ($emailverification!="")
                                {
                                        // Aktivierungsmail versenden
                                        send_email($_POST['email'],
                                                getsetting('townname','Atrahor')."-Account: Bestätigung",
                                                "Um deinen Charakter in ".getsetting('townname','Atrahor')." freizuschalten, musst du nur noch auf den folgenden Link klicken.\n\n
                                                http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$emailverification\n\n ~ Danke für's Spielen!"
                                        );
                                        output("`4Eine E-Mail wurde an `$".$str_mail."`4 geschickt, um die E-Mail-Adresse zu bestätigen. Klicke auf den Link darin, um deinen Account zu aktivieren.`0`n`n");
                                }
                                else
                                {
                                        output("`#`cDein Charakter wurde erstellt. Du kannst jetzt einloggen.`c`0");
                                        
                                        show_created_screen();
                                        
                                        savesetting("newplayer",addslashes("$title $str_name"));
                                        
                                }
                                
                                systemlog('`@Neuen Spieler registriert: `0',0,$int_acctid);
                                
                        }        
                        // END Account anlegen
                        // Wenn Anmeldung fehlerhaft:
                        else
                        {
                                output('`c`$Fehler`^:`n'.$msg.'`c`n');
                                $str_op='';
                        }
                }        
                // END Formular abgesendet
                        
                // Formular anzeigen
                if ($str_op=='')
                {
                                
                        output("`&`c`bCharakter erstellen`b`c`n");
                        
                        // Multi-Warnung
                        $sql = 'SELECT login,acctid,uniqueid,lastip FROM accounts WHERE lastip = "'.addslashes($session['user']['lastip']).'" OR uniqueid = "'.addslashes($session['user']['uniqueid']).'" ORDER BY login, acctid';
                        $res = db_query($sql);
                                
                        if (db_num_rows($res) > 4)
                        {
                                output('`n
                                        `$Achtung: `^über deinen Anschluss / deinen PC laufen bereits fünf Accounts!`n
                                        Bevor du einen Sechsten erstellst, solltest du die Regeln noch einmal ganz genau lesen
                                        und bei evtl. Unklarheiten eine Anfrage verfassen.`n
                                        Bei Löschungen aufgrund von Verstößen gegen die Multiaccountregeln gibt es keinerlei Anspruch
                                        auf Entschädigung oder Wiederherstellung!`n`n`n');
                        }
                        // END Multi-Warnung
                                
                        $arr_data = array('sex'=>0);
                        
                        $_POST['name'] = trim($_POST['name']);
                        $arr_data = array_merge($arr_data,$_POST);
                        
                        $arr_form = array('name'=>'Wie soll Dein `bVorname`b in dieser Welt lauten?|?Im Spiel kannst du dir sofort kostenlos einen farblosen Titel aussuchen, den du vor oder hinter deinen Vornamen setzen kannst. Bitte melde dich deshalb mit dem Vornamen deines Charakters an.',
                        'pass1'=>'Gebe bitte ein Passwort an:,password',
                        'pass2'=>'Wiederhole dieses Passwort:,password',
                        'email'=>'Deine E-Mail Adresse '.
                        (getsetting("requireemail",0)==0?"(freiwillige Angabe -- aber wenn du keine eingibst kann dein Account nicht gerettet werden wenn du dein Passwort vergisst!)":"(benätigt".(getsetting("requirevalidemail",0)==0?"":": eine E-Mail wird zur Bestätigung an diese Adresse geschickt bevor du einloggen kannst").")").':',
                        'sex'=>'Dein Geschlecht in dieser Welt soll sein:,radio,1,Weiblich,0,Männlich');
                        
                        $str_lnk = 'create.php?op=create'.(!empty($_GET['r'])?'&r='.$_GET['r']:'');
                        
                        output("`0<form action=\"$str_lnk\" method='POST'>",true);
                        output('`^Dein Name darf keinen Titel (Lord, Graf, Meister etc.) und keine Beschreibung (ScharfesSchwert, grünerHund etc.) enthalten. Er
                                sollte nach Mittelalter klingen, mindestens jedoch nach Mythen und Sagen. Englische Namen sind dafür nur bedingt geeignet. Namen
                                von Prominenten, Personen der Zeitgeschichte oder Film-Helden sind ebenfalls nicht erwünscht. Anhängsel wie -chan etc. solltest
                                du auch vermeiden.`n`n
                                Gib bitte unbedingt den <u>`bVornamen`b</u>, nicht den Nachnamen, deines Charakters an. Einen Nachnamen kannst du nach der Anmeldung sofort über den eigenen Titel des Charakters ergänzen.`0`n');
                        output("`n`^Mit dem Erstellen deines Charakters stimmst du ausdrücklich den hier geltenden ~`b`$<a href='petition.php?op=faq&page=faq_rules' target='_blank'>Regeln</a>`b`^~ zu!`n`n",true);
                        
                        showform($arr_form,$arr_data,false,getsetting('townname','Atrahor').' betreten!');
                        
                        output("</form>",true);
                        
                }
                // END Formular anzeigen
        
        // END default
        break;
}
// END Main-Switch

page_footer();
?>

