
<?php
/**
* create.php:        Script zur Erstellung eines neuen Accounts
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/

/*in newday.php oben einfügen:
//Demo-Account rauswerfen 
if($session['user']['acctid']==getsetting('demouser_acctid','0') && $session['user']['age']>0)
{
        $sql = "UPDATE `accounts` 
        SET `location`='0',`loggedin`='0',`restatlocation`='0',`output`='Ausgeloggt am ".date('d.m.Y H:i:s')."'
        WHERE `acctid`=".$session['user']['acctid'];
        db_query($sql);

        Atrahor::clearSession();
        redirect('demouser.php?op=logout');
}

Interaktionen wie Edelsteinversand unterbinden
Tauben einschränken
Anfragen wie im ausgeloggten Zustand

*/
require_once('common.php');

page_header( getsetting('townname','Atrahor').' - Registratur' );

function show_created_screen ($str_name,$str_password) {

        output("<form action='login.php' method='POST'>
                        <input name='name' value=\"$str_name\" type='hidden'>
                        <input name='demo_pw' value=\"$str_password\" type='hidden'>
                        <input type='submit' class='button' value='Hier klicken zum Einloggen!'>
                        </form>`n`n
                        Dieser Demo-Account verfällt durch Ausloggen bzw Timeout, oder spätestens zum neuen Spieltag.",true);
}

function show_registration_text()
{
}

addnav('Startseite','index.php'.($_GET['r']>0?'?r='.intval($_GET['r']):''),false,false,false,false);

$demoacctid=intval(getsetting('demouser_acctid',0));
$sql='SELECT count(*) AS c FROM accounts WHERE '.user_get_online();
$result = db_query($sql);
$onlinecount = db_fetch_assoc($result);

if($demoacctid==0) 
//Demozugang deaktiviert
{
        output('Hier gibt es leider keinen Demo-Zugang.');
}

elseif(getsetting('maxonline',0)-$onlinecount['c']<2) 
//Server voll
{
        output('`c`b`$Server voll!`0`b`c`nIm Moment ist die maximal mögliche Zahl an Spielern online. Bitte versuche es später nochmal.');
}

elseif(user_get_online($demoacctid)===true) 
//Demozugang in Benutzung
{
        output('`c`b`$Pech gehabt!`0`b`c`nDer Demo-Zugang wird bereits von jemandem genutzt. Du wirst dich gedulden müssen bis derjenige fertig ist. Oder du meldest dich gleich an, das tut auch nicht weh ;). Bitte beachte aber bei der Anmeldung unsere Regeln, besonders im Hinblick auf den Namen deines Charakters!');
        addnav('`tCharakter erstellen`0','create_rules.php',false,false,false,false);
}

/*elseif($_SERVER['REMOTE_ADDR']==getsetting('demouser_last_IP',0)) 
//IP-Sperre
{
        if($_GET['op']!='logout')
        {
                $row['laston']=date('Y-m-d H:i:s',strtotime("-31 minutes"));
                $sql='SELECT laston FROM accounts WHERE acctid='.$demoacctid;
                $result=db_query($sql);
                if(db_num_rows($result)==1)
                {
                        $row=db_fetch_assoc($result);
                }
                if($row['laston']<date('Y-m-d H:i:s',strtotime("-30 minutes")))
                {
                        savesetting('demouser_last_IP','0');
                }
                output('`c`b`$IP-Sperre!`0`b`c`nDu warst doch gerade erst hier. Der Demo-Zugang ist nicht zum regulären Spielen gedacht. Bitte lass Anderen auch eine Chance!`n`n');
        }
        output('`c`b`IDeine Zeit als Besucher ist leider abgelaufen.`b`c`n
        `nNeugierig geworden?
        `nWenn es dir hier gefallen hat, dann melde dich doch gleich an!
        `nErstelle einen eigenen Charakter und entwickele ihn nach deinen Wünschen! Schalte neue Orte und Features frei und entdecke immer neue Herausforderungen! Ein individuelles Fantasy-Rollenspiel mit viel Spielspaß und freundlicher Community erwartet dich!
        `n');
        addnav('`tCharakter erstellen`0','create_rules.php?r='.$demoacctid,false,false,false,false);
}*/

else
{
// Filter auf PC checken
checkban();

$str_op = $_GET['op'];

switch($str_op) {

        // Standard: Charakter erstellen - Formular anzeigen
        default:

                // Wenn keine Neuanmeldungen möglich
                if (getsetting("blocknewchar","0")==1)
                {
                        output('`c`bDie Anmeldungen in '.getsetting('townname','Atrahor').' sind momentan gesperrt!`b`n');
                        output("`tIm Moment sind leider keine Neuanmeldungen möglich. Wenn Du den Grund erfahren möchtest, so schreibe Bitte eine Anfrage.`0`c");
                        page_footer();
                }

                // Anmeldeform. abgeschickt
                if ($str_op == 'create')
                {

                        $str_pass1 = $_POST['pass1'];
                        $str_pass2 = $_POST['pass2'];
                        $str_name = $_POST['name'];
                        $str_mail = $_POST['email'];

                        // EMail checken
                        // Emailaddy gegeben?
                        if ( (getsetting("requireemail",0)==1 && is_email($str_mail)) || getsetting("requireemail",0)==0)
                        {

                                // Ban?
                                if (checkban(false, false, false, $str_mail, 0, false))
                                {
                                        output('`c`b`$Fehler:`b`c`n');
                                        output("Du bist hier nicht erwünscht (E-Mail Adresse gesperrt).`c`n");
                                        page_footer();
                                        exit;
                                }

                                // Blacklist?
                                if(check_blacklist( BLACKLIST_EMAIL, stripslashes(strtolower($str_mail)) ) )
                                {
                                        output('`c`b`$Fehler:`b`c`n');
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
                                $msg.="Du musst eine gültige E-Mail Adresse eingeben. Diese wird für bestimmte Funktionen des Spiels verwendet!`n";
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

                        // Name checken
                        // Auf jeden Fall Formatierungstags raus
                        $str_name = strip_appoencode($str_name,3);

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
                                // Titel Geschlecht anpassen
                                $str_title = 'Besucher'.($_POST['sex']==1 ? 'in' : '');

                                // Emailvalidation
                                if (getsetting("requirevalidemail",0))
                                {
                                        $emailverification=md5(date("Y-m-d H:i:s").$str_mail);
                                }

                                // Empfehlung
                                $int_refid = (int)$_GET['r'];
                                if ( $int_refid > 0 )
                                {
                                        $referer=$int_refid;
                                }
                                else
                                {
                                        $referer=0;
                                }
                                
                                //alten DemoUser löschen
                                user_delete($demoacctid);
                                $sql='DELETE FROM news WHERE accountid='.$demoacctid;
                                db_query($sql);
                                
                                //Für jeden Account werden per Default folgende Preferences gesetzt
                                $arr_prefs = array(
                                'preview' => 1,
                                'minimail' => 1,
                                'hide_who_is_here' => 1,
                                'tutorial_disabled'        => 1,
                                'sounds' => 0
                                );

                                // Datensatz in accounts anlegen
                                $sql = "INSERT INTO accounts
                                                SET
                                                        acctid=$demoacctid,
                                                        name='$str_title $str_name',
                                                        title='$str_title',
                                                        password=MD5( 'demo' ),
                                                        sex=$int_sex,
                                                        login='~$str_name',
                                                        laston=NOW(),
                                                        uniqueid='".$_COOKIE['lgi']."',
                                                        lastip='".$_SERVER['REMOTE_ADDR']."',
                                                        gold=".(int)getsetting("newplayerstartgold",50).",
                                                        emailaddress='user@domain.invalid',
                                                        emailvalidation='',
                                                        prefs = '".serialize($arr_prefs)."',
                                                        lastmotd=DATE(NOW()),
                                                        race = 'npc',
                                                        specialty = 1,
                                                        activated = ".USER_ACTIVATED_MUTE_AUTO."
                                                ";


                                db_query($sql);
                                if (db_affected_rows()<=0)
                                {
                                        output('`$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal oder schreibe eine Anfrage.');
                                        page_footer();
                                }

                                // Datensatz in Extra-Info anlegen

                                $sql = "INSERT INTO account_extra_info
                                                SET
                                                        acctid=".$demoacctid.",
                                                        birthday='".getsetting('gamedate','0000-00-00')."',
                                                        referer='".$referer."',
                                                        namecheck=16777215,
                                                        namecheckday=0
                                                ";
                                db_query($sql);

                                // Datensatz in Statistik anlegen
                                //entfällt

                                {
                                        output('`c`bDein Charakter wurde erstellt.`b`c`n
                                        Name: `^'.$str_name.'`0`n
                                        Geschlecht: '.($int_sex?'weiblich':'männlich').'`n
                                        Rasse: `2Demo-User`0`n
                                        Fähigkeit: `4Dunkle Künste`0`n`n');

                                        $sql = "SELECT login,password FROM accounts WHERE acctid=$demoacctid";
                                        $result = db_query($sql);
                                        $row = db_fetch_assoc($result);
                                        show_created_screen($row['login'],$row['password']);

                                }

                                $sql='SELECT login FROM accounts WHERE uniqueid="'.$session['uniqueid'].'" AND acctid<>'.$demoacctid;
                                $result=db_query($sql);
                                if(db_num_rows($result)>0)
                                {
                                        $logtext='`nassoziiert mit ';
                                        while($same_id=db_fetch_assoc($result))
                                        {
                                                $logtext.=$same_id['login'].' ';
                                        }
                                }
                                systemlog('`wNeuer Demo-Account, IP: '.$_SERVER['REMOTE_ADDR'].', Name: '.$str_name.'`0'.$logtext);
                                //savesetting('demouser_last_IP',$_SERVER['REMOTE_ADDR']);

                        }
                        // END Account anlegen
                        // Wenn Anmeldung fehlerhaft
                        //Wird direkt über dem Formular angezeigt!
                        else
                        {
                                $str_error_message = '`c`$Fehler`^:`n'.$msg.'`c`n';
                                $str_op='';
                        }
                }
                // END Formular abgesendet

                // Formular anzeigen
                if ($str_op=='')
                {

                        $str_out .= '`c`bDemo-Account`b`c`n';

$vorsilben = array(1=>'Bel','Lu','Dant','Rik','Tal','Dre','Rhag','Hord','Meib','Ast','Kor','Ver','Krag','Kyth','Alb','Tig','Aver','Bor','My','Ang','Dil','Sar','Or','Dra','Drik','Ruk','Nib','Man','Da','Nil','Art','Lak','Tith','Tumk','Est','Erc','Proc','Mar','Cael','Ag','Khaz','Ach','Kal','Art','Ask','Ka','Miy','Bik','Mik','Tar','Wol','Ray','Hal','Rob','Tak','Kar','As','Zor','Nogl','Sedi','Werl','Dir','Bone','Dark','Cap','Ver','Besid','Hage','Cunpol','Deriter','Sawan','Pes','Moad','Crim','Lyni','Ast','Mer','Ror','Des','Vert','War','Lan');
$nachsilben = array(1=>'nu','is','us','ilo','ker','yanki','uz','ius','ven','ar','lay','var','hut','ic','rav','rol','kul','kal','ven','sharr','cil','rak','ahm','lino','ibo','ivo','filo','avo','in','sard','ys','ar','ir','lion','er','ak','tram','icule','enay','ian','acs','har','orus','ka','onis','pil','icles','ra','in','us','ilo','is','as','ik','ak','at','it','ard','ar','ak','re','\'Vreal','ustil','lisdo','\'Vrel','werd','\'Kryon','rit','mak','alk','zar','ad','id','et','wik','lik','dil','lin','en','ketch','asad','lon','gon','ron','rin','lion');
                        $name=$vorsilben[e_rand(1,count($vorsilben))].$nachsilben[e_rand(1,count($nachsilben))];

                        $arr_data = array('sex'=>0,'pass1'=>'testuser','pass2'=>'testuser','email'=>'user@domain.de','name'=>$name);

                        $arr_data = array_merge($arr_data,$_POST);

                        $arr_form = array('name'=>'Wie soll Dein Name in dieser Welt lauten?',
                        'pass1'=>'Gebe bitte ein Passwort an:,hidden',
                        'pass2'=>'Wiederhole dieses Passwort:,hidden',
                        'email'=>'Deine E-Mail Adresse:,hidden',
                        'sex'=>'Dein Geschlecht in dieser Welt ist:,radio,1,Weiblich,0,Männlich');

                        $str_lnk = 'demouser.php?op=create'.(!empty($_GET['r'])?'&r='.$_GET['r']:'');

                        $str_out .= '`0<form action="'.$str_lnk.'" method="POST">
                        `tMit dem Demo-Zugang kannst du dir ein Bild machen, was dich in diesem Spiel erwartet.
                        `nDer Demo-Zugang verfällt beim Logout oder zum neuen Spieltag und ist im Funktionumfang stark eingeschränkt.
                        `n`nBitte wähle einen `&`bNamen`b`t für deine Spielfigur.
                        `nNamen von Prominenten, Personen der Zeitgeschichte oder Film-Helden sind hier nicht erwünscht.';

                        //Infotext wegen email entfällt

                        //Wenn ein Fehler aufgetreten ist dann soll die fehlermeldung da zu sehen sein wo der User hinguckt,
                        //nämlich auf das Formular!
                        $str_out .= $str_error_message;

                        $str_out .= generateform($arr_form,$arr_data,false,'Weiter');

                        $str_out .= "</form>";
                        output($str_out,true);

                }
                // END Formular anzeigen

        // END default
        break;
}
// END Main-Switch
}
page_footer();
?>

