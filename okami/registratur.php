
<?

//Dieser Part wird nur durch einen asynchronen HTTP REQUEST aufgerufen
//und gibt alle User zurück die mit der übergebenen IP/ID assoziiert sind
if (isset($_REQUEST['get_more_user_info']))
{
    $DONT_OVERWRITE_NAV     = true;
    $BOOL_JS_HTTP_REQUEST     = true;
    require_once('common.php');

    $str_ip = $_REQUEST['get_more_user_info_ip'];
    $str_id = $_REQUEST['get_more_user_info_id'];

    //$str_output = get_more_user_info(array('lastip'=>$str_ip,'uniqueid'=>$str_id));
    $str_output = get_more_user_info(array('uniqueid'=>$str_id));

    //Führt implizit ein die() aus und gibt den Text zurück der ausgegeben wird
    jslib_http_text_output(appoencode($str_output));
}


require_once "common.php";
page_header("Die Registratur");

/**
* Funktion gibt ein Plumihtml Fragment zurück welches Informationen über den user gibt der durch $row spezifiziert ist
*
* @param array $row Array der acctid, lastip und uniqueid des users enthalten muss
* @return string plumifizierter String
*/
function get_more_user_info($row = array())
{
    //
    // Gleich bei Registrierung feststellen ob ein user schon da ist mit einem Multiaccount
    //
    if (count($row)==0)
    {
        return '';
    }
    $str_sql = 'SELECT name,lastip FROM accounts WHERE lastip="'.$row['lastip'].'" OR uniqueid="'.$row['uniqueid'].'"';

    $db_res = db_query($str_sql);

    $int_count = db_num_rows($db_res);

    //Alle User mit gleicher IP oder ID auflisten
    if ($int_count>0)
    {
        //Assoziiert ist:
        $str_user = '<hr>`$Assoziiert mit:`0`n';
        while ($arr_user = db_fetch_assoc($db_res))
        {
            //den aktuellen User übergehen selbst übergehen
            /*if ($arr_ip['lastip'] == $row['lastip'])
            {
            continue;
            }
            */
            $str_user .= $arr_user['name'].'`n';
        }
        if (strlen($str_user) == 0)
        {
            $str_user .= '`iNiemandem`i';
        }
    }

    return $str_user;
}

$access_control->su_check(access_control::SU_RIGHT_REGISTRATUR,true);

// Grundnavi erstellen
addnav('Zurück');
grotto_nav();
addnav('Registratur');
addnav("ungeprüfte Namen","registratur.php?op=newname");
if ($access_control->su_check(access_control::SU_RIGHT_REGISTRATUR_EDIT_USER))
{
    addnav("angemailte Namen","registratur.php?op=mailname");
    addnav("akzeptierte Namen","registratur.php?op=accname");
}

// Anzeigefilter
$bool_show_all = false;
if (isset($_GET['show_all']))
{
    $session['registratur']['show_all'] = ($_GET['show_all'] == 1 ? true : false);
}
if (isset($session['registratur']['show_all']))
{
    $bool_show_all = $session['registratur']['show_all'];
}

//addnav('Pinnbrett','registratur.php?op=board');
addnav('Aktionen');

//Steuerung
$trenn = 5;

$arr_form = array('rec'=>'An:,viewonly',
'subject'=>'Betreff:',
'body'=>'Text:,textarea,60,30'
);

$str_changesubj_a = '`^Namensänderung`0';
$str_changemail_a = get_extended_text('changemail_a_body','*',false,false);

$str_changesubj_b = '`$Namensänderung 2. Verwarnung`0';
$str_changemail_b = get_extended_text('changemail_b_body','*',false,false);

$str_welcomesubj = '`^Herzlich Willkommen!`0';
$str_welcomemail = get_extended_text('welcomemail_body','*',false,false);

if ($_REQUEST['op']=="newname" || $_REQUEST['op'] == '')
{
    //Liste ungeprüfte Bewohner

    require_once(LIB_PATH.'board.lib.php');

    output('Du schlägst das große Buch der neuen Bewohner auf und blätterst aufmerksam durch die Seiten.`n

    '.plu_mi('board_multi_ex',0,false).'Multis`n
    <div id="'.plu_mi_unique_id('board_multi_ex').'" style="display:none;">');

    board_view('multi_ex',2,'`&Aktuelle Ausnahmen von der Multiregelung:','',false,true);

    output('<hr>
    </div>
    `n`0Die folgenden Charakter-Namen sind noch unbearbeitet:`n`n`0`c');

    $sql = 'SELECT accounts.acctid as acctid, name, login, laston, namecheck, loggedin, namecheckday, lastip, uniqueid
    FROM accounts
    LEFT JOIN account_extra_info USING(acctid)
    WHERE locked=0 and (namecheck=0)
    ORDER BY acctid DESC';
    $result = db_query($sql);
    if (db_num_rows($result) == 0)
    {
        output('Es sind keine Bewohner mit ungeprüften Namen verzeichnet!`0`n');
    }
    else
    {
        output(form_header('registratur.php'));
        output("<table border=0 cellpadding=3 cellspacing=3 >",true);
        output("<tr class='trhead'><td>&nbsp;</td><td>Nummer</td><td>Spieler</td><td>Last On</td>
        <td>Namenswechsel</td><td>Akzeptieren</td><td>Löschung</td>",true);
        $count = 0;
        while ($row = db_fetch_assoc($result))
        {
            if ($count == 5 )
            {
                output("<tr class='trhead'><td colspan='7'><hr></td></tr>",true);
                $count = 1;
            }
            else
            {
                $count++;
            }
            $tmp = $row['acctid'];

            $tmp2 = jslib_getmenuuserlink($row,$row,true).($row['namecheckday'] == 1 ? ' `i(nach Umbenennung)`i':'').'`n[ <a href="#" onclick="'.popup('http://google.de/search?q='.urlencode($row['login']),array('width'=>700,'height'=>550)).';return false;">Google?</a> ]';
            $tmp3 = $row['login'];

            $str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');

            $str_more_info  = '`n'.plu_mi($row['acctid'],0,false).'Mehr Informationen:`n';
            $str_more_info .= jslib_dyn_content($row['acctid'], 'registratur.php?get_more_user_info=1&get_more_user_info_ip='.$row['lastip'].'&get_more_user_info_id='.$row['uniqueid'], DYN_CONNECT_TO_PLUMI | DYN_PLUMI_HIDE | DYN_LOAD_ONCE);

            output("<tr class='".$str_trclass."'>",true);
            output("<td><input type='checkbox' name='userid[]' value='".$tmp."'></td>");
            output("<td>".$tmp."</td><td>".$tmp2.$str_more_info."</td>",true);
            output("<td>",true);
            $laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ")
            {
                $laston="1 Tag";
            }
            if(date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";
            if(date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if(user_get_online(0,$row))
            {
                $laston="Jetzt";
            }
            output($laston);
            output("</td><td>",true);
            if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))
            {
                output("<a href='registratur.php?op=mail&userid=".$tmp."'>`^Mail`0</a>",true);
                addnav("","registratur.php?op=mail&userid=$tmp");
            }
            output('</td><td>'.create_lnk('`@ok`0','registratur.php?op=accept&userid='.$tmp.($row['namecheckday'] == 1 ? '&nonew=1' : '')).'</td><td>',true);
            if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))
            {
                $str_lnk = 'su_delete.php?ids[]='.$tmp.'&ret='.urlencode(calcreturnpath());
                output(''.create_lnk('`4löschen`0',$str_lnk).'',true);
            }
            output("</td></tr>",true);

            addnav("","registratur.php?op=accept&userid=$tmp");

        }
        output("</table>",true);
        output("<button name='op' value='accept' class='button'>Gewählte zulassen</button><button name='op' value='mail' class='button'>Gewählte anmailen</button></form>");
        jslib_initmenu();
    }
    output('`c');

    require_once(LIB_PATH.'board.lib.php');

    if ($_GET['board_action'] == 'add')
    {
        board_add('regi_brd');
    }

    board_view('regi_brd',2,'`&Aktuelle Anmerkungen:','',false,true);
    output('`n`n`&Anmerkung hinterlassen:`0`n');
    board_view_form('Speichern!','');
    output('`n');
}
else if ($_REQUEST['op']=="mail")
{
    //mehlen

    if (isset($_REQUEST['userid']) && !is_array($_REQUEST['userid']))
    {
        $_REQUEST['userid'] = array((int)$_REQUEST['userid']);
    }
    else if (!isset($_REQUEST['userid']) && $session['user']['pqtemp'] != '')
    {
        $_REQUEST['userid'] = unserialize($session['user']['pqtemp']);
    }
    $str_userids = serialize($_REQUEST['userid']);


    if ($_GET['act'] == 'send')
    {
        foreach ($_REQUEST['userid'] as $int_id)
        {
            $int_id = (int)$int_id;

            $sql =  "SELECT age,name,login,acctid,sex FROM accounts WHERE acctid='$int_id'";
            $result2 = db_query($sql);
            $arr_user = db_fetch_assoc($result2);


            $to = $int_id;
            $subject = $_POST['subject'];
            //betreff
            $from = $session['user']['acctid'];
            //absender: eingeloggter(halb)gott
            $body = str_replace('{name}',$arr_user['login'],$_POST['body']);

            if ($_POST['blacklist'])
            {
                $str_login = trim(addslashes(strtolower($arr_user['login'])));

                // Duplikate vermeiden
                $sql = 'DELETE FROM blacklist WHERE value="'.$str_login.'" AND type=(0 ^ '.BLACKLIST_LOGIN.')';
                db_query($sql);

                $sql = 'INSERT INTO blacklist SET value="'.$str_login.'",type='.BLACKLIST_LOGIN;
                db_query($sql);
            }

            systemmail($to,$subject,$body,$from);

            $tag = $arr_user['age'];

            $sql = "UPDATE account_extra_info SET namecheck=".$session['user']['acctid'].", namecheckday=$tag WHERE acctid=$int_id";
            db_query($sql);

            $namechange=getsetting("unaccepted_namechange","0");

            if ($namechange==1)
            {
                $number=getsetting("namechange_number","1");

                $newname="Neuling mit unzulässigem Namen Nr. {$number}";
                user_update(
                    array
                    (
                        'name'=>$newname
                    ),
                    $int_id
                );
                $number++;
                savesetting("namechange_number",$number);

            }
        }

        $session['user']['pqtemp'] = '';
        redirect("registratur.php?op=newname");
    }
    else
    {

        $session['user']['pqtemp'] = $str_userids;

        $arr_form['blacklist'] = $arr_user['login'].' auf Blacklist setzen,checkbox,1';

        $arr_data = array('rec'=>$arr_user['login'].'`0',
        'subject'=>$str_changesubj_a,
        'body'=>$str_changemail_a
        );

        $str_lnk = 'registratur.php?op=mail&act=send';
        addnav('',$str_lnk);

        output('<form action="'.$str_lnk.'" method="POST">',true);

        showform($arr_form,$arr_data,false,'Absenden!');

        output('</form>',true);

    }

}
else if ($_REQUEST['op']=="imprison")
{
    //einkerkern

    if (isset($_REQUEST['userid']) && !is_array($_REQUEST['userid']))
    {
        $_REQUEST['userid'] = array((int)$_REQUEST['userid']);
    }

    foreach ($_REQUEST['userid'] as $int_id)
    {
        $int_id = (int)$int_id;

        $to = $int_id;
        $subject = $str_changesubj_b;
        //betreff
        $from = $session['user']['acctid'];
        //absender: eingeloggter(halb)gott
        $body = $str_changemail_b;

        systemmail($to,$subject,$body,$from);

        $id = $int_id;
        user_update(
            array
            (
                'imprisoned'=>-2
            ),
            $id
        );

        systemlog('`qEinkerkerung von:`0 ',$session['user']['acctid'],$id);
    }

    redirect("registratur.php?op=mailname");
}
else if ($_REQUEST['op']=="accept")
{
    //akzeptieren

    if (isset($_REQUEST['userid']) && !is_array($_REQUEST['userid']))
    {
        $_REQUEST['userid'] = array((int)$_REQUEST['userid']);
    }
    else if (!isset($_REQUEST['userid']) && $session['user']['pqtemp'] != '')
    {
        $_REQUEST['userid'] = unserialize($session['user']['pqtemp']);
    }
    $str_userids = serialize($_REQUEST['userid']);


    if ($_GET['act'] == 'send' || $_GET['nonew'])
    {
        foreach ($_REQUEST['userid'] as $int_id)
        {
            $int_id = (int)$int_id;

            $sql =  "SELECT age,name,login,acctid,sex FROM accounts WHERE acctid='$int_id'";
            $result2 = db_query($sql);
            $arr_user = db_fetch_assoc($result2);

            // Mail nur versenden, wenn Account noch nicht angemailt wurde
            if (!$_GET['nonew'])
            {
                $to = $int_id;
                $subject = $_POST['subject'];
                //betreff
                $from = $session['user']['acctid'];
                //absender: eingeloggter(halb)gott
                $body = str_replace('{name}',$arr_user['login'],$_POST['body']);

                systemmail($to,$subject,$body,$from);
            }

            $sql = "UPDATE account_extra_info SET namecheck=16777215,namecheckday=0 WHERE acctid=".$int_id;
            db_query($sql);

            user_update(
                array
                (
                    'imprisoned'=>0,
                    'where'=>'acctid='.$int_id.' AND imprisoned<0'
                )
            );

            //Jeder neuling bekommt das Noobschutz Item
            item_add($int_id,'noobschutz');
        }
        $session['user']['pqtemp'] = '';

        redirect("registratur.php?op=newname");
    }
    else
    {
        $session['user']['pqtemp'] = $str_userids;

        $arr_data = array('rec'=>$arr_user['name'].'`0','subject'=>$str_welcomesubj,'body'=>$str_welcomemail);

        $str_lnk = 'registratur.php?op=accept&act=send';
        addnav('',$str_lnk);

        output('<form action="'.$str_lnk.'" method="POST">',true);

        showform($arr_form,$arr_data,false,'Absenden!');

        output('</form>',true);

        addnav('KEINE Willkommensmail versenden','registratur.php?op=accept&nonew=1');

    }

}
else if ($_GET['op']=="rename")
{
    // umbenennen

    $acctid = (int)$_GET['acctid'];

    if (!$acctid)
    {
        redirect('registratur.php?op=mailname');
    }

    $sql = 'SELECT name,title,login,ctitle,imprisoned FROM accounts LEFT JOIN account_extra_info USING(acctid) WHERE accounts.acctid='.$acctid;
    $acc = db_fetch_assoc(db_query($sql));

    output('`&'.$acc['name'].' umbenennen:`n`n');

    $str_name = trim($_POST['name']);

    if (!empty($str_name))
    {

        // Name checken
        // Auf jeden Fall Formatierungstags raus
        $str_name = strip_appoencode($str_name,3);

        // Auf Korrektheit prüfen
        $str_valid = user_rename($acctid, stripslashes($str_name));

        $str_msg = '';

        if (true !== $str_valid)
        {

            switch ($str_valid)
            {

                case 'login_banned':
                    $str_msg .= 'Dieser Name ist gebannt!';
                    break;

                case 'login_blacklist':
                    $str_msg .= 'Dieser Name ist verboten!';
                    break;

                case 'login_dupe':
                    $str_msg .= 'Diesen Namen gibt es leider schon!';
                    break;

                case 'login_tooshort':
                    $str_msg .= 'Der gewählte Name ist zu kurz (Min. '.getsetting('nameminlen',3).' Zeichen)!';
                    break;

                case 'login_toolong':
                    $str_msg .= 'Der gewählte Name ist zu lang (Max. '.getsetting('namemaxlen',3).' Zeichen)!';
                    break;

                case 'login_badword':
                    $str_msg .= 'Der gewählte Name enthält unzulässige Begriffe!';
                    break;

                case 'login_spaceinname':
                    $str_msg .= 'Der gewählte Name enthält Leerzeichen, was leider nicht erlaubt ist!';
                    break;

                case 'login_specialcharinname':
                    $str_msg .= 'Der gewählte Name enthält Sonderzeichen, was leider nicht erlaubt ist!';
                    break;

                case 'login_criticalcharinname':
                    $str_msg .= 'Der gewählte Name enthält Zeichen, die für einen Namen nicht geeignet sind (z.B. Zahlen oder der Unterstrich)!';
                    break;

                case 'login_titleinname':
                    $str_msg .= 'Der gewählte Name enthält einen Titel, der ein Teil des Spiels ist!';
                    break;

                default:
                    $str_msg .= 'Irgendwas stimmt mit diesem Namen nicht, ich weiß nur nicht was ; ) Schreibe bitte eine Anfrage!';
                    break;

            }

            output('`$'.$str_msg.'`0`n`n');

        }
        else
        {
            user_set_name($acctid);

            user_set_aei(array('namecheckday'=>0,'namecheck'=>16777215),$acctid);

            if ($acc['imprisoned']==-1 || $acc['imprisoned']==-2)
                user_update(array("imprisoned"=>0),$acctid);

            redirect('registratur.php?op=mailname');
        }
    }

    $link = 'registratur.php?op=rename&acctid='.$acctid;

    output('<form method="POST" action="'.$link.'">
<input type="text" name="name" maxlength="40" value="'.($name ? $name : $acc['login']).'">
<input type="submit" value="Ändern!">
</form>',true);

    addnav('',$link);

}
else if ($_GET['op']=="accname")
{
    //liste akzeptierte Bewohner

    output("Du schmökerst im grossen Buch der Bewohner, wer alles hier
gemeldet ist:`n`n`0");

    $sql = 'SELECT accounts.acctid, name, login, title FROM accounts
LEFT JOIN account_extra_info USING(acctid)
WHERE locked=0 and namecheck=16777215
ORDER BY login ASC';
    $result = db_query($sql);
    if (db_num_rows($result) == 0)
    {
        output("Es sind keine Bewohner mit akzeptierten Namen verzeichnet!`0`n");
    }
    else
    {
        $comp = "";
        $space = " ";
        output("<table border=0 cellpadding=2 cellspacing=1 >",true);
        output("<tr class='input'><td>Nummer</td><td>Name</td><td>Titel</td>",true);
        while ($row = db_fetch_assoc($result))
        {
            $tmp = $row['acctid'];
            $letter = ucfirst(substr($row['login'],0,1));
            if ($letter != $comp )
            {
                output("<tr class='trmain'>",true);
                output("<td>".$space."</td><td>`6-----`^  `b".$letter."`b  `6------`0 </td><td>".$space."</td>",true);
                output("</tr>",true);

                $comp = $letter;
            }
            output("<tr class='trmain'>",true);
            output("<td>".$tmp."</td><td>".$row['login']."</td><td>".$row['title']."</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
    }
}
else if ($_GET['op']=="mailname")
{
    //liste angemailte Bewohner

    addnav('Anzeigen');
    addnav(($bool_show_all ? '`^' : '').'Alle','registratur.php?op='.$_REQUEST['op'].'&show_all=1');
    addnav((!$bool_show_all ? '`^' : '').'Nur die eigenen','registratur.php?op='.$_REQUEST['op'].'&show_all=0');

    output("Du schmökerst in einer Pergament-Rolle, in der alle Bewohner verzeichnet
    sind, die wegen ihrer Namenswahl angeschrieben wurden:`n`n`n`n`n`n`c`0");

    $sql = 'SELECT a.acctid, a.lastip,a.uniqueid,a.age,a.imprisoned, a.acctid, a.name, a.laston, a.login, a.loggedin, su.login AS superusername,aei.namecheck, aei.namecheckday
    FROM accounts a
    LEFT JOIN account_extra_info aei ON a.acctid=aei.acctid
    LEFT JOIN accounts su ON aei.namecheck = su.acctid
    WHERE a.locked=0 AND (aei.namecheck>0 AND aei.namecheck < 16777215)
    '.(!$bool_show_all ? ' AND aei.namecheck = '.$session['user']['acctid'] : '').'
    ORDER BY a.acctid ASC';
    $result = db_query($sql);
    if (db_num_rows($result) == 0)
    {
        output("Es sind keine angeschriebenen Bewohner verzeichnet!`0`n");
    }
    else
    {
        output(form_header('registratur.php'));
        output("<table border=0 cellpadding=3 cellspacing=3 >",true);
        output("<tr class='trhead'><td></td><td>Nummer</td><td>Spieler</td><td>Last On&nbsp;</td><td>Spieltage seit Anschreiben</td><td>Superuser</td><td>Namenswechsel</td><td>Akzeptieren</td><td>Einkerkern</td><td>Löschung</td>",true);

        $arr_mail_notseen = db_create_list(db_query('SELECT seen,msgto FROM mail WHERE (subject="'.$str_changesubj_a.'" OR subject="'.$str_changesubj_b.'") AND msgfrom='.$session['user']['acctid'].' AND seen=0'),'msgto');

        while ($row = db_fetch_assoc($result))
        {
            if ($row['namecheckday'] != 0)
            {
                $tagheute = $row['age'];
                $tagnum = ($tagheute - $row['namecheckday']+1);
                $tag = $tagnum." Tag(e)";
            }
            else
            {
                $tag = "Keiner";
            }

            if ($row['namecheck'] == $session['user']['acctid'])
            {
                if ($arr_mail_notseen[$row['acctid']])
                {
                    $tag .= ' `i(Ungelesen)`i';
                }
                else
                {
                    $tag .= ' `i`$(Gelesen)`0`i';
                }
            }

            $str_more_info  = '`n'.plu_mi($row['acctid'],0,false).'Mehr Informationen:`n';
            $str_more_info .= jslib_dyn_content($row['acctid'], 'registratur.php?get_more_user_info=1&get_more_user_info_ip='.$row['lastip'].'&get_more_user_info_id='.$row['uniqueid'], DYN_CONNECT_TO_PLUMI | DYN_PLUMI_HIDE | DYN_LOAD_ONCE);

            $tmp = $row['acctid'];
            $row['name'] = str_replace('Neuling mit unzulässigem Namen','NmuN',$row['name']);

            $str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');

            output("<tr class='".$str_trclass."'>",true);
            output("<td><input type='checkbox' name='userid[]' value='".$tmp."'></td>");
            output("<td>".$tmp."</td><td>".jslib_getmenuuserlink($row,$row,true)."`n(`i".$row['login']."`i)".$str_more_info."</td>",true);
            output("<td>",true);
            $laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ")
            {
                $laston="1 Tag";
            }
            if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";
            if(date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if(user_get_online(0,$row))
            {
                $laston="Jetzt";
            }
            output($laston);
            output("</td>",true);
            output("<td>".$tag."</td>",true);
            output("<td>".$row['superusername']."</td>",true);
            output("<td><a href='registratur.php?op=rename&acctid=".$tmp."'>`Qumbenennen`0</a></td>",true);
            output("<td><a href='registratur.php?op=accept&userid=".$tmp."&nonew=1'>`@ok`0</a></td>",true);
            if ($row['imprisoned']<0)
            {
                output("<td>`isitzt schon`i</td>",true);
            }
            else if ($row['namecheck'] == $session['user']['acctid'])
            {
                output("<td><a href='registratur.php?op=imprison&userid=".$tmp."'>`@In den Kerker`0</a></td>",true);
            }
            else
            {
                output('<td>`@ - </td>',true);
            }

            if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER) && $row['namecheck'] == $session['user']['acctid'])
            {
                $str_lnk = 'su_delete.php?ids[]='.$tmp.'&ret='.urlencode(calcreturnpath());
                output('<td>'.create_lnk('`4löschen`0',$str_lnk).'</td>',true);
            }
            else
            {
                output('<td>`@ - </td>',true);
            }
            output("</tr>",true);
            //umbenennung ueber verwaltungsbuero
            addnav("","registratur.php?op=rename&acctid=".$tmp);
            addnav("","registratur.php?op=imprison&userid=$tmp");
            addnav("","registratur.php?op=accept&userid=$tmp&nonew=1");
        }
        output("</table>",true);
        output("<button name='op' value='accept' class='button'>Gewählte zulassen</button><button name='op' value='imprison' class='button'>Gewählte einkerkern</button></form>");
        jslib_initmenu();
    }
    output('`c');


    require_once(LIB_PATH.'board.lib.php');

    if ($_GET['board_action'] == 'add')
    {
        board_add('regi_brd');
    }

    board_view('regi_brd',2,'`&Aktuelle Anmerkungen:','',false,true);
    output('`n`n`&Anmerkung hinterlassen:`n');
    board_view_form('Speichern!','');
    output('`n');

}

page_footer();
?>


