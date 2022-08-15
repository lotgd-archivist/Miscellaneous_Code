
<?php
/* Urlaubsmodus
* Verhindert das Löschen von accounts nach der in expireoldacct voreingestellten Zeit
* Accounts werden nach der in expirevacationacct einstellbaren Zeit gelöscht (Default 1 Jahr)
* Während der Abwesenheit ist man nicht angreifbar. Man muß jedoch eine Mindestzeit abwesend sein.
* author: Salator

Zu ändernde Dateien:

user.lib.php:
bei den user_loc Konstanten einfügen:
        define('USER_LOC_VACATION',99);

in der Funktion user_show_list bei der Orts-Anzeige einfügen:
        if ($row['location']==USER_LOC_VACATION)
        {
                $str_output .= '`3In Sibirien`0';
        }

-----------------

settings.lib.php:
bei Abgelaufene Accounts: Warnungen verschicken ersetzen:
        $vacation = getsetting('expirevacationacct',365)-getsetting('expire_sendmail_before',5);
        $old = getsetting('expireoldacct',45)-getsetting('expire_sendmail_before',5);
        $new = getsetting('expirenewacct',10);
        $trash = getsetting('expiretrashacct',1);

        $sql = 'SELECT acctid,emailaddress,login FROM accounts WHERE 1=0 '
        .($vacation>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($vacation*86400))."\")\n":"")
        .($old>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($old*86400))."\" AND location !=".USER_LOC_VACATION.")\n":"")
        ." AND (emailaddress!='' AND activated!=".USER_ACTIVATED_SENTNOTICE." AND activated!=".USER_ACTIVATED_MUTE.")";

bei Inaktive Accounts löschen ersetzen:
        $vacation+=getsetting('expire_sendmail_before',5);
        $old+=getsetting('expire_sendmail_before',5);

        $sql = 'SELECT acctid,login FROM accounts WHERE superuser=0 AND (1=0 '
        .($vacation>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($vacation*86400))."\")\n":"")
        .($old>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($old*86400))."\" AND location !=".USER_LOC_VACATION.")\n":"")
        .($new>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($new*86400))."\" AND level=1 AND dragonkills=0)\n":'')
        .($trash>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-(($trash+1)*86400))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":'')
        .')';

-----------------

login.php:
bei den redirects einfügen:
        case USER_LOC_VACATION: // war im Urlaubsmodus
                redirect("vacation.php?op=return");
        break;

-----------------

su_configuration.php:
an passender Stelle die Einstellfelder für expirevacationoldacct und vacation_ban_time anlegen

-----------------

hof.php:
sql-Befehl bei op==abwesend ändern:
        $sql = 'SELECT accounts.acctid,accounts.login,accounts.name, DATEDIFF(NOW(),laston) AS data1,dragonkills AS data2 FROM accounts WHERE locked=0 AND DATEDIFF(NOW(),laston) > 3 AND dragonkills>0 AND location !='.USER_LOC_VACATION.' ORDER BY data1 '.$order.', dragonkills '.$order.', acctid '.$order.' LIMIT '.$limit;

*/
require_once "common.php";

$vacation=getsetting('expirevacationacct',150);
$bantime=getsetting('vacation_ban_time',14);

$guardname = '`sMartin';

page_header('Auf Reisen');
$str_out='`c`b`)Auf einer langen Reise`0`b`c`n';

switch ($_GET['op'])
{
        case 'check':
        { //Bedingungen prüfen
                $session['user']['specialmisc']='';
                $str_out.='`)Du erklärst '.$guardname.'`), dass du wirklich vor hast, die Stadt für längere Zeit zu verlassen. Gerade willst du deinen Weg fortsetzen, als er dich daran hindert: `s`i"Stopp! Führt Ihr Dinge mit, welche die Stadt nicht verlassen dürfen?"`i`)`n';
                if ($session['user']['acctid']==getsetting('hasegg',0))
                {
                        $invent.='Ein goldenes Ei`n';
                }
                $res=item_list_get('tpl_id IN("idolrnds", "idolgnie", "idolfish", "idolkmpf", "idoldead", "mapt") AND owner='.$session['user']['acctid']);
                if(db_num_rows($res)>0)
                {
                        while ($row=db_fetch_assoc($res))
                        {
                                $invent.=$row['name'].'`0`n';
                                $session['user']['specialmisc'].=','.$row['id'];
                        }
                }

                if($invent)
                {
                        $str_out.='Du breitest deine Habseligkeiten aus. Darunter befindet sich:`n`n`I'.$invent.'
                        `n`s`i"So legt diese Dinge hier in den Sack!"`i`) fordert dich '.$guardname.' `)auf.';
                        addnav('Wenns denn sein muss...','vacation.php?op=removeitems');
                }
                else
                {
                        $str_out.='`0`i"Nein, ich habe nichts dergleichen."`i`) antwortest du.
                        `n`n`qDies ist deine letzte Chance, umzukehren! Wenn du jetzt weiter klickst,wirst du dich für `$'.$bantime.' Realtage nicht mehr einloggen können!`q';
                        addnav('Auf Wiedersehen!','vacation.php?op=goodbye');

                }
                addnav('Nö, zurück','fields.php');
                break;
        }

        case 'removeitems':
        { //Items freigeben
                if ($session['user']['acctid']==getsetting('hasegg',0)) {
                        savesetting('hasegg',0);
                        $sql = 'UPDATE items SET owner=0 WHERE tpl_id="goldenegg"';
                        db_query($sql);
                }
                if($session['user']['specialmisc']>'')
                {
                        item_delete('id IN (0'.$session['user']['specialmisc'].')');
                        $session['user']['specialmisc']='';
                }
                $str_out.='`)Schweren Herzens legst du die Dinge in den Sack. Vorschrift ist nunmal Vorschrift...
                `n`n`qDies ist nun deine letzte Chance, umzukehren! Wenn du jetzt weiter klickst, wirst du dich für `$'.$bantime.' Realtage nicht mehr einloggen können!`q';
                addnav('Auf Wiedersehen!','vacation.php?op=goodbye');
                addnav('Nö, zurück','fields.php');
                break;
        }

        case 'goodbye':
        { //auf Abwesend setzen, Selbstbann
                $str_out.='`)'.$guardname.' `)wünscht dir eine gute Reise und du machst dich, ein Liedchen pfeifend, auf den Weg in dein Abenteuer.';
                $session['user']['location'] = USER_LOC_VACATION;
                setban(0,'Automatischer Systembann: User hat sich in den Urlaubsmodus versetzt.',date('Y-m-d H:i:s',time()+($bantime*86400)),false,false,false,$session['user']['login']);
                if(isset($session['error']))
                { //irgendwas ist schiefgelaufen
                        $str_out.='`n`n`$Fehler: '.$session['error'];
                        addnav('S?Zur Stadt','village.php');
                }
                addnav('Startseite','login.php?op=logout&loc='.USER_LOC_VACATION,true);
                break;
        }

        case 'return':
        { //Rückkehr, irgendwas zu tun hier?
                $str_out.='`)Nach deiner schier endlos langen Reise erklimmst du einen kleinen Hügel und kannst von dort aus in der Ferne die vielen kleinen Dächer
                und rauchenden Schornsteine deiner Heimatstadt '.getsetting('townname','Atrahor').' erkennen.`n
                Dein Herz macht einen Sprung und deine Gedanken füllen sich mit den süßen Erinnerungen an all deine Freunde und Verwandte.
                Du gehst schnellen Schrittes weiter durch den dir heimatlich bekannt vorkommenden Wald und kannst schon bald die Felder vor '.getsetting('townname','Atrahor').' erblicken.
                Schon von hier aus kannst du die Marktschreier rufen hören und die Luft ist erfüllt von vielen wohlbekannten Gerüchen. Ja, hier bist du zu Hause. Voller Vorfreude läufst du auf die Stadt zu.`n
                '.$guardname.'`), der Bauer, der dich damals verabschiedet hat, ist gerade dabei, sein Feld zu bestellen, als er dich bemerkt und aufschaut. Erst scheint er dich gar nicht
                zu erkennen, doch schließlich erhellt sich seine Miene und er erinnert sich. `s`i"Willkommen zurück, '.$session['user']['login'].'`s!"`i`)
                `nDu winkst ihm fröhlich zu, dann machst du dich in freudiger Erwartung auf in die Stadt. Was mag sich wohl in deiner Abwesenheit alles verändert haben..?';
                addnav('D?Auf in die Stadt!','village.php');
                break;
        }

        default:
        { //Info und Einstiegspunkt
                $str_out.='`)Es ist schon ein seltsames Bild, das du abgibst, während du mit dem riesigen, voll
                           gefüllten Leinbeutel auf dem Rücken die Straße entlang gehst. Einer der Bauern, '.$guardname.'`),
                           sieht das scheinbar genauso und hält einen Moment in seiner Arbeit inne, um dir ein
                           `s`i"Wohin des Wegs?"`i`) zuzurufen. `0"Ich werde für einige Zeit auf Wanderschaft gehen."`),
                           antwortest du ihm und deutest auf das viele Gepäck auf deinem Rücken. Hierauf reibt sich
                           '.$guardname.'`) kurz den Bart, ehe er seine Harke stehen lässt und zu dir herüber kommt:
                           `s`i"So ist das also. Und wie lange wollt Ihr verreisen? Wisst Ihr, wenn Ihr nur für die
                           üblichen '.getsetting('expireoldacct',50).' Tage wegbleibt, ist das kein Problem und Ihr braucht
                           nichts weiter zu tun, als rechtzeitig wieder zurückzukehren. Doch sollte
                           Eure Reise länger dauern, wäre es gut, wenn Ihr mir dies mitteilen würdet - ich werde dann
                           dafür sorgen, dass Eurem Hab und Gut nichts passiert. Allerdings müsste ich Euch in diesem
                           Fall auch darum bitten, ein paar Dinge zu beachten:`i`0
                           `n`n<ul><li> Einige `$Gegenstände`0 (Eier, Schatzkarten, Idole) dürfen `$nicht ausgeführt`0 werden!
                           `n(Diese Besitztümer werden zu Gemeinschaftseigentum erklärt)
                           `n`n<li> Du wirst '.getsetting('townname','Atrahor').' `$bis zum '.date('d. m. Y',time()+($bantime*86400)).' nicht betreten`0 können!
                           `n(Du bekommst mit diesem Char einen Bann und kannst dich nicht einloggen, bist aber im Ausgleich unangreifbar!)
                           `n`n<li> Du giltst nicht als verschollen und dir wird kein Grabstein gesetzt, sofern du `$innerhalb von '.$vacation.' Tagen`0 (bis '.date('d. m. Y',time()+($vacation*86400)).') in die Stadt `$zurückkehrst`0.
                           `n(Das Verfallsdatum für Accounts wird für diese Zeit ausgesetzt, du fällst also nicht der automatischen Löschung zum Opfer!)`i`s"`i
                           </ul>
                           `n`n`)Nun liegt es an dir: Willst du dich für längere Zeit abmelden?';
                addnav('Ja, Reise antreten','vacation.php?op=check');
                addnav('Nö, zurück','fields.php');
        }
}

output($str_out);
if($session['user']['superuser'])
{
        addnav('Admin');
        addnav('!?Notausgang!','fields.php');
}
page_footer();
?>

