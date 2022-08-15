
<?php

// 15082004

// advanced by Silva

define('LISTCOLORTEXT','`7');
define('LISTCOLORBOLD','`s');

require_once('common.php');

if ($session['user']['loggedin']) {
        checkday();
        $show_ooc = true;

        if ($session['user']['alive']) {
            addnav('Zurück');
            addnav('S?Zum Stadtplatz','village.php');
            addnav('M?Zum Marktplatz','market.php');
            addnav('H?Zum Hafen','harbor.php');
        } else {
            addnav('Zurück');
            addnav('S?Zurück zu den Schatten', 'shades.php');
        }
        addnav('RP-Suche');
        addnav('+?`2P`Gi`pnnwa`Gn`2d`0','rp_request.php');
} else {
        addnav('Login Seite','index.php');
}
addnav('Anzeige Hauptchars');
addnav('Gerade Online','list.php');
addnav('Alle Spieler','list.php?p=all');
addnav('t?Adminteam','list.php?p=su');
page_header('Einwohnerliste');
if($_GET['op'] == 'rpchange') {
        $session['user']['rpbulb'] = (int)$_POST['rpstatus'];
        redirect("list.php");
} elseif($_GET['op'] == 'rescue' && su_check(SU_RIGHT_GROTTO)) {
        $id = (int)$_GET['id'];
        $sql = "UPDATE accounts SET allowednavs='',output='',restorepage='' WHERE acctid=".$id;
        saveuser();
        db_query($sql);
        debuglog("`&hat Benutzer mit AcctID ".$id."`& einen Rettungsnav geschickt.");
        redirect('list.php');
}
if($session['user']['loggedin']) {
        // Pinwand-Hinweis
        $sql = db_query("SELECT COUNT(section) AS anz FROM boards WHERE section='list'");
        $rp_entries = db_fetch_assoc($sql);
        if($rp_entries['anz'] > 0) {
            output(LISTCOLORTEXT."`n
                    `c`GDerzeit ".($rp_entries['anz'] == 1 ? "sucht `b`geine Person`b" : "suchen `b`g".$rp_entries['anz']." Personen`b")." `Gnach einem RP.`c
                   ");
        }
        // end
        // Diskussionsraum-Marker an?
        $d_count = db_num_rows(db_query("SELECT commentid FROM commentary WHERE section = 'Diskussionsraum' AND postdate > DATE_SUB(NOW(), INTERVAL 2 DAY)"));
        if($d_count > 0) {
            output(LISTCOLORTEXT."
                    `n`c`wEs wurde eine `bDiskussion`b im Diskussionsraum gestartet.`c`n`n
                   ");
            $str_diskus_navname = '`wDiskussionsraum`0';
        } else {
            $str_diskus_navname = 'Diskussionsraum';
        }
        // end
        addnav('Anzeige Zusatz-Chars');
        addnav('Knappenliste','list.php?p=disc');
        addnav('X-Charliste','list.php?p=xchar');
        addnav('Diskutieren');
        addnav($str_diskus_navname,'diskus.php?op=list');
        addnav('Externes');
        addnav('Discord-Channel', 'https://discord.com/channels/662224017293312030',false,false,true,true);
        // RP-Status-Wechsel über Radio Buttons
        output("`n".LISTCOLORTEXT."<u>Eigener RP-Status:</u>`n
                <form action='list.php?op=rpchange' method='post'>
                  <table><tr>
                    <td>
                      <input type='radio' id='rponnow' name='rpstatus' onchange='this.form.submit();' value='1' ".($session['user']['rpbulb'] == 1 ? "checked" : "")."><label for='rponnow'><img src='images/green.png' alt='Suche RP für ab sofort'>&nbsp;".LISTCOLORTEXT."Suche ein RP für ab sofort.</label>`n
                      <input type='radio' id='rponlater' name='rpstatus' onchange='this.form.submit();' value='3' ".($session['user']['rpbulb'] == 3 ? "checked" : "")."><label for='rponlater'><img src='images/blue.png' alt='Suche RP für später'>&nbsp;".LISTCOLORTEXT."Suche ein RP für einen späteren Zeitpunkt.</label>
                    </td>
                    <td style='padding-left: 1.5em; vertical-align: top;'>
                      <input type='radio' id='rppublic' name='rpstatus' onchange='this.form.submit();' value='2' ".($session['user']['rpbulb'] == 2 ? "checked" : "")."><label for='rppublic'><img src='images/yellow.png' alt='Im öffentlichen RP'>&nbsp;".LISTCOLORTEXT."Im öffentlichen RP - schreib dich dazu!</label>`n
                      <input type='radio' id='norp' name='rpstatus' onchange='this.form.submit();' value='0' ".($session['user']['rpbulb'] == 0 ? "checked" : "")."><label for='norp'><img src='images/red.png' alt='Suche kein RP'>&nbsp;".LISTCOLORTEXT."Nicht auf der Suche nach einem RP</label>
                    </td>
                    <td style='padding-left: 1.5em; vertical-align: top;'>
                      <noscript><input type='submit' class='button' value='Ändern'></noscript>
                    </td>
                  </tr></table>
                </form>",true);
        addnav('','list.php?op=rpchange');
        // end
} else {
        output("".LISTCOLORTEXT."<u>RP-Status:</u>`n
                  <table><tr>
                    <td>
                      <img src='images/green.png' alt='Suche RP für ab sofort'>&nbsp;".LISTCOLORTEXT."Suche ein RP für ab sofort.`n
                      <img src='images/blue.png' alt='Suche RP für später'>&nbsp;".LISTCOLORTEXT."Suche ein RP für einen späteren Zeitpunkt.
                    </td>
                    <td style='padding-left: 1.5em; vertical-align: top;'>
                      <img src='images/yellow.png' alt='Im öffentlichen RP'>&nbsp;".LISTCOLORTEXT."Im öffentlichen RP - schreib dich dazu!`n
                      <img src='images/red.png' alt='Suche kein RP'>&nbsp;".LISTCOLORTEXT."Nicht auf der Suche nach einem RP
                    </td>
                  </tr></table>",true);
}
output("`n`n",true);
// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
// ~ Einwohnerliste Gerade online
if ($_GET['p']=='' && $_GET['op'] == '') {
        output('`c`b'.LISTCOLORBOLD.'Folgende Bürger '.getsetting('townname','Eranya').'s sind gerade online:`b`c'.LISTCOLORTEXT);
        user_show_list(100,user_get_online(),'level DESC, dragonkills DESC, login ASC',true,true,200);
// ~ Einwohnerliste Knappen
} elseif($_GET['p'] == 'disc') {
        output('`c`b'.LISTCOLORTEXT.'Die Knappen in '.getsetting('townname','Eranya').':`b`c');
        user_show_list(30,$_GET['p'],'disc_level DESC, a.level DESC, a.dragonkills DESC, a.login ASC',true,true);
// ~ Einwohnerliste X-Chars
} elseif($_GET['p'] == 'xchar') {
        output('`c`b'.LISTCOLORTEXT.'Die X-Chars in '.getsetting('townname','Eranya').':`b`c');
        user_show_list(30,$_GET['p'],'a.login ASC,a.level DESC, a.dragonkills DESC, a.login ASC',true,true);
// ~ Einwohnerliste Adminteam
} elseif($_GET['p'] == 'su') {
        $arr_usergroups = unserialize( stripslashes(getsetting('sugroups','')) );
        $str_not_show = '0';
        foreach($arr_usergroups as $id=>$val) {
                // Gesondert zeigen?
                if(!$val[3]) {
                        $str_not_show .= ','.$id;
                }
        }
        output('`c`b'.LISTCOLORTEXT.getsetting('townname','Eranya').'s Administrationsteam:`b`c`n');
        user_show_list(100,' superuser NOT IN('.$str_not_show.')',' superuser ASC, login ASC ',false,false);
// ~ Einwohnerliste Alle Spieler
} else {
        output('`c`b'.LISTCOLORTEXT.getsetting('townname','Eranya').'s ehrenhafte Bürger:`b`c');
        user_show_list(30,'','level DESC, dragonkills DESC, login ASC',true,true);
}
// OOC-Chatfeld hinzufügen:
if($session['user']['loggedin']) {
        output(LISTCOLORTEXT.'`n`n`nOOC:`n`n');
        addcommentary();
        viewcommentary('OOC','Tippen',35,'tippt');
}

page_footer();

?> 
