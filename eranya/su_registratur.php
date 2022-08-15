
<?php


require_once "common.php";
page_header("Die Registratur");

su_check(SU_RIGHT_REGISTRATUR,true);

// Grundnavi erstellen
addnav('Zurück');
addnav('G?Zur Grotte','superuser.php');
addnav('W?Zum Weltlichen',$session['su_return']);
addnav('Registratur');
addnav("ungeprüfte Namen","su_registratur.php?op=newname");
addnav("angemailte Namen","su_registratur.php?op=mailname");
addnav("akzeptierte Namen","su_registratur.php?op=accname");
addnav('Aktionen');

//Steuerung
$trenn = 5;

$arr_form = array(                'rec'=>'An:,viewonly',
                                                'subject'=>'Betreff:',
                                                'body'=>'Text:,textarea,60,15'
                                        );

$str_changesubj_a = '`^Namensänderung`0';
$str_changemail_a = get_extended_text('changemail_a_body','*',false,false);
        
$str_changesubj_b = '`$Namensänderung 2. Verwarnung`0';
$str_changemail_b = get_extended_text('changemail_b_body','*',false,false);

$str_welcomesubj = '`^Herzlich Willkommen!`0';        
$str_welcomemail = get_extended_text('welcomemail_body','*',false,false);

if ($_GET['op']=="newname" || $_GET['op'] == '') {    //Liste ungeprüfte Bewohner

    output("Du schlägst das grosse Buch der neuen Bewohner auf und blätterst
    aufmerksam durch die Seiten.`n
    Die folgenden Charakter-Namen sind noch unbearbeitet:`n`n`0`c");

    $sql = 'SELECT accounts.acctid, name, sex, race, login, laston, emailaddress AS email, namecheck, loggedin, namecheckday
                        FROM accounts
                        LEFT JOIN account_extra_info USING(acctid)
            WHERE locked=0 and (namecheck=0)
            ORDER BY acctid DESC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine Bewohner mit ungeprüften Namen verzeichnet!`0`n");
    }
    else {
        output("<table border=0 cellpadding=3 cellspacing=3 >",true);
        output("<tr class='trhead' style='font-weight: bold; text-align: center;'><td>Nummer</td><td>Spieler</td><td><img src='images/female.png'>/ <img src='images/male.png'></td><td>Rasse</td><td>EMail-Adresse</td><td>Last On</td>
        <td>Namenswechsel</td><td>Akzeptieren</td><td>Löschung</td>",true);
        // Rassennamen abrufen
        $arr_races = db_create_list(db_query('SELECT colname,id FROM races'),'id');
        $count = 0;
        while ($row = db_fetch_assoc($result)) {
            if ( $count == 5 ) {
                output("<tr class='trhead'>",true);
                output("<td>----</td><td>----------</td><td>----</td><td>---------</td><td>---------</td>",true);
                output("<td>----</td><td>--</td><td>-------</td>",true);
                output("</tr>",true);
                $count = 1;
            } else $count++;
            $tmp = $row['acctid'];
            $tmp2 = $row['login'].($row['namecheckday'] == 1 ? " `i(nach Umbenennung)`i":"");
            $tmp2 .= "`n`7[ <a href='http://google.de/search?q=".urlencode($row['login'])."' target='_blank' onclick='".popup('http://google.de/search?q='.urlencode($row['login']),700,550).";return false;'>Google?</a> `7]";
            $tmp3 = $row['login'];
            $race = $arr_races[$row['race']]['colname'];
            
            $str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
            
            output("<tr class='".$str_trclass."' style='text-align: center;'>",true);
            output("<td>".$tmp."</td><td><a href='bio_popup.php?id=".$tmp."' target='_blank' onClick='".popup_fullsize($str_p_link).";return:false;'>`&".$tmp2."`0</td>",true);
            output("<td>".($row['sex']?'<img src="images/female.png">':'<img src="images/male.png">')."</td><td>".$race."</td><td>".$row['email']."</td>");
            //output("<td>".$tmp2."</td>",true);
                        output("<td>",true);
                        $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
                        if (substr($laston,0,2)=="1 ") $laston="1 Tag";
                        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
                        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
                        if ($row['loggedin']) $laston="Jetzt";
                        output($laston);
                        output("</td><td>",true);
                        // if(su_check(SU_RIGHT_EDITORUSER)) {
                    output("<a href='su_registratur.php?op=mail&userid=".$tmp."'>`^Mail`0</a>",true);
                    addnav("","su_registratur.php?op=mail&userid=$tmp");
                        //}
            output('</td><td>'.create_lnk('`@OK`0','su_registratur.php?op=accept&userid='.$tmp.($row['namecheckday'] == 1 ? '&nonew=1' : '')).'</td><td>',true);
                        if(su_check(SU_RIGHT_EDITORUSER)) {
                                $str_lnk = 'su_delete.php?ids[]='.$tmp.'&ret='.urlencode(calcreturnpath());
                                output(''.create_lnk('`CLöschen`0',$str_lnk).'',true);
                        }
            output("</td></tr>",true);
            
            addnav("","su_registratur.php?op=accept&userid=$tmp");
            
        }
        output("</table>",true);
    }
    output('`c');
}
else if ($_GET['op']=="mail") {   //mehlen
        
        $int_id = (int)$_GET['userid'];
        
        $sql =  "SELECT age,name,login,acctid,sex FROM accounts WHERE acctid='$int_id'";
        $result2 = db_query($sql) or die(db_error(LINK));
        $arr_user = db_fetch_assoc($result2);
        
        $str_changemail_a = str_replace('{name}',$arr_user['login'],$str_changemail_a);
        
        if($_GET['act'] == 'send') {

                $to = $int_id;
                $subject = $_POST['subject'];     //betreff
                $from = $session['user']['acctid'];  //absender: eingeloggter (halb)gott
                $body = $_POST['body'];
                
                if($_POST['blacklist']) {
                        $str_login = trim(addslashes(strtolower($arr_user['login'])));
                
                        // Duplikate vermeiden
                        $sql = 'DELETE FROM blacklist WHERE value="'.$str_login.'" AND type=(0 ^ '.BLACKLIST_LOGIN.')';
                        db_query($sql);
                
                        $sql = 'INSERT INTO blacklist SET value="'.$str_login.'",type='.BLACKLIST_LOGIN;
                        db_query($sql);
                }
                
                //$sql = "INSERT INTO mail SET msgfrom=".$from.",msgto=".$to.",subject='".$subject."',body='".$body."',sent=NOW()";
                //db_query($sql);
                systemmail($to,$subject,$body,$from);
                        
                $tag = $arr_user['age'];
                
                $sql = "UPDATE account_extra_info SET namecheck=".$session['user']['acctid'].", namecheckday=$tag WHERE acctid=$int_id";
                db_query($sql);
                
                //$namechange=getsetting("unaccepted_namechange","0");
                $namechange = $_POST['namechange'];
                
                if ($namechange==1)
                {
                  $number=getsetting("namechange_number","1");

                  $newname="Neuling mit unzulässigem Namen Nr. {$number}";
                  $sql = 'UPDATE accounts SET name="'.$newname.'" WHERE acctid='.$int_id;
                          db_query($sql);
                  $number++;
                  savesetting("namechange_number",$number);

                }
                redirect("su_registratur.php?op=newname");
        }
        else {
                
                $arr_form['namechange'] = $arr_user['login'].' umbenennen,select,0,Nein,1,Ja|?in \'Neuling mit unzulässigem Namen Nr. X\'';
                $arr_form['blacklist'] = $arr_user['login'].' auf Blacklist setzen,checkbox,1';
                                
                $arr_data = array(
                                                        'rec'=>$arr_user['login'].'`0',
                                                        'subject'=>$str_changesubj_a,
                                                        'body'=>$str_changemail_a
                                                );
                                                        
                $str_lnk = 'su_registratur.php?op=mail&act=send&userid='.$int_id;
                addnav('',$str_lnk);
                
                output('<form action="'.$str_lnk.'" method="POST">',true);
                
                showform($arr_form,$arr_data,false,'Absenden!');
                
                output('</form>',true);                        
                
        }
                    
}
else if ($_GET[op]=="imprison") {   //einkerkern
    //$wer = $_GET['name'];
    $to = $_GET['userid'];
    $subject = $str_changesubj_b;     //betreff
    $from = $session['user']['acctid'];  //absender: eingeloggter (halb)gott
    $body = $str_changemail_b;
    //$sql = "INSERT INTO mail SET msgfrom=".$from.",msgto=".$to.",subject='".$subject."',body='".$body."',sent=NOW()";//systemmail($to,$subject,$body);
    //    db_query($sql);
    systemmail($to,$subject,$body,$from);

        $id = $_GET[userid];
    $sql = "UPDATE accounts SET imprisoned=-2 WHERE acctid=$id";
    db_query($sql);
        
        systemlog('`qEinkerkerung von:`0 ',$session['user']['acctid'],$id);
        
    redirect("su_registratur.php?op=mailname");
}
else if ($_GET[op]=="accept") {  //akzeptieren
        
        $int_id = (int)$_GET['userid'];
        
        $sql =  "SELECT age,name,login,acctid,sex FROM accounts WHERE acctid='$int_id'";
        $result2 = db_query($sql) or die(db_error(LINK));
        $arr_user = db_fetch_assoc($result2);
        
        $str_welcomemail = str_replace('{name}',$arr_user['login'],$str_welcomemail);
        
        if($_GET['act'] == 'send' || $_GET['nonew']) {
                
                // Mail nur versenden, wenn Account noch nicht angemailt wurde
                if(!$_GET['nonew']) {
                        $to = $int_id;
                        $subject = $_POST['subject'];     //betreff
                        $from = $session['user']['acctid'];  //absender: eingeloggter (halb)gott
                        $body = $_POST['body'];
                        
                        //$sql = "INSERT INTO mail SET msgfrom=".$from.",msgto=".$to.",subject='".$subject."',body='".$body."',sent=NOW()";
                        //db_query($sql);
                        systemmail($to,$subject,$body,$from);
                }
                        
                $sql = "UPDATE account_extra_info SET namecheck=16777215,namecheckday=0 WHERE acctid=".$int_id;
                db_query($sql);
                
                $sql = "UPDATE accounts SET imprisoned=0 WHERE acctid=$int_id AND imprisoned<0";
                db_query($sql);
                
                redirect("su_registratur.php?op=newname");
        }
        else {
                                
                $arr_data = array('rec'=>$arr_user['name'].'`0','subject'=>$str_welcomesubj,'body'=>$str_welcomemail);
                                                        
                $str_lnk = 'su_registratur.php?op=accept&act=send&userid='.$int_id;
                addnav('',$str_lnk);
                
                output('<form action="'.$str_lnk.'" method="POST">',true);
                
                showform($arr_form,$arr_data,false,'Absenden!');
                
                output('</form>',true);                        
                
                addnav('KEINE Willkommensmail versenden','su_registratur.php?op=accept&nonew=1&userid='.$int_id);
                
        }
                       
}
else if ($_GET[op]=="rename") {   // umbenennen
        
        $acctid = (int)$_GET['acctid'];
                
        if(!$acctid) {redirect('su_registratur.php?op=mailname');}
        
        $sql = 'SELECT name,title,login,ctitle FROM accounts LEFT JOIN account_extra_info USING(acctid) WHERE accounts.acctid='.$acctid;
        $acc = db_fetch_assoc(db_query($sql));
        
        output('`&'.$acc['name'].' umbenennen:`n`n');
        
        $str_name = trim($_POST['name']);
        
        if(!empty($str_name)) {
        
            // Name checken
            // Auf jeden Fall Formatierungstags raus
        $str_name = strip_appoencode($str_name,3);
            
            // Auf Korrektheit prüfen
            $str_valid = user_rename($acctid, stripslashes($str_name));
    
            $str_msg = '';
            
            if(true !== $str_valid) {
                    
                    switch($str_valid) {
                            
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
            else {
                user_set_name($acctid);
                
                user_set_aei(array('namecheckday'=>0,'namecheck'=>16777215),$acctid);
                
                redirect('su_registratur.php?op=mailname');
            }
        }
            
        $link = 'su_registratur.php?op=rename&acctid='.$acctid;
        
        output('<form method="POST" action="'.$link.'">
                                <input type="text" name="name" maxlength="40" value="'.($name ? $name : $acc['login']).'">
                                <input type="submit" value="Ändern!">
                        </form>',true);
        
        addnav('',$link);    
    
}
else if ($_GET[op]=="accname") {  //liste akzeptierte Bewohner
   
    output("Du schmökerst im grossen Buch der Bewohner, wer alles hier
    gemeldet ist:`n`n`0");

    $sql = 'SELECT accounts.acctid, name, login, title FROM accounts
                        LEFT JOIN account_extra_info USING(acctid) 
            WHERE locked=0 and namecheck=16777215
            ORDER BY login ASC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine Bewohner mit akzeptierten Namen verzeichnet!`0`n");
    }
    else {
        $comp = "";
        $space = " ";
        output("<table border=0 cellpadding=2 cellspacing=1 >",true);
        output("<tr class='input'><td>Nummer</td><td>Name</td><td>Titel</td>",true);
        while ($row = db_fetch_assoc($result)) {
            $tmp = $row['acctid'];
            $letter = ucfirst( substr($row['login'],0,1));
            if ( $letter != $comp ) {
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
} elseif($_GET['op'] == 'nameautochange') {
    $int_id = $_GET['acctid'];
    // Der wievielte Name ist es?
    $number=getsetting("namechange_number","1");
    // Umbenennung vorbereiten...
    $newname="Neuling mit unzulässigem Namen Nr. {$number}";
    // ... und durchführen
    $sql = 'UPDATE accounts SET name="'.$newname.'" WHERE acctid='.$int_id;
    db_query($sql);
    // Anzahl an unpassenden Namen um 1 hochzählen & speichern
    $number++;
    savesetting("namechange_number",$number);
    // Das war's.
    redirect('su_registratur.php?op=mailname');
} else if ($_GET['op']=="mailname") {   //liste angemailte Bewohner

    output("`gDu schmökerst in einer Pergament-Rolle, in der alle Bewohner verzeichnet
    sind, die wegen ihrer Namenswahl angeschrieben wurden:`n`n`c`0");

    $sql = 'SELECT a.age,a.imprisoned, a.acctid, a.name, a.laston, a.login, a.loggedin, su.login AS superusername,aei.namecheck, aei.namecheckday
                         FROM accounts a
                        LEFT JOIN account_extra_info aei ON a.acctid=aei.acctid 
                        LEFT JOIN accounts su ON aei.namecheck = su.acctid
                        WHERE a.locked=0 AND (aei.namecheck>0 AND aei.namecheck < 16777215)
            ORDER BY a.acctid ASC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine angeschriebenen Bewohner verzeichnet!`0`n");
    }
    else {
        output("<table border=0 cellpadding=3 cellspacing=3 style='text-align: center;'>",true);
        output("<tr class='trhead'><td>Nummer</td><td>Spieler</td><td>Last On&nbsp;</td><td>Spieltage seit Anschreiben</td><td>Superuser</td><td>Namenswechsel</td><td>Akzeptieren</td><td>Einkerkern</td><td>Löschung</td>",true);
        
        $arr_mail_notseen = db_create_list(db_query('SELECT seen,msgto FROM mail WHERE (subject="'.$str_changesubj_a.'" OR subject="'.$str_changesubj_b.'") AND msgfrom='.$session['user']['acctid'].' AND seen LIKE "%;b:0;%"'),'msgto');

        while ($row = db_fetch_assoc($result)) {
                        if ($row['namecheckday'] != 0){
                                $tagheute = $row['age'];
                                $tagnum = ($tagheute - $row['namecheckday']+1);
                                $tag = "".$tagnum." Tag(e)";
                        }
                        else{
                                $tag = "Keiner";
                        }
                        
                        if($row['namecheck'] == $session['user']['acctid']) {
                                if($arr_mail_notseen[';'.$row['acctid'].';']) {
                                        $tag .= '`n`i(Ungelesen)`i';
                                }
                                else {
                                        $tag .= '`n`i`$(Gelesen)`0`i';
                                }
                        }
                                                
            $tmp = $row['acctid'];
            
            $str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
            
            output("<tr class='".$str_trclass."'>",true);
            output("<td>".$tmp."</td><td style='text-align: left;'>".$row['name']."`n(");
            if(strpos($row['name'],'Neuling mit unzulässigem Namen Nr.') === false) {
                output("<a href='su_registratur.php?op=nameautochange&acctid=".$tmp."' onClick='return confirm(\"Charname in \'Neuling mit unzulässigem Namen Nr. X\' umtaufen - OK?\");'>`^Umtaufen</a>");
            } else {
                output("`i".$row['login']."`i");
            }
            output(")</td>",true);
                output("<td>",true);
            $laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ") $laston="1 Tag";
            if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";
            if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if ($row['loggedin']) $laston="Jetzt";
            output($laston);
            output("</td>",true);
            output("<td>".$tag."</td>",true);
            output("<td>".$row['superusername']."</td>",true);
            output("<td><a href='su_registratur.php?op=rename&acctid=".$tmp."'>`QUmbenennen`0</a></td>",true);
            output("<td><a href='su_registratur.php?op=accept&userid=".$tmp."&nonew=1'>`@OK`0</a></td>",true);
                        if ($row['imprisoned']<0) {output("<td>`i`Tsitzt schon`i</td>",true);}
                        else if ($row['namecheck'] == $session['user']['acctid']) {
                                output("<td><a href='su_registratur.php?op=imprison&userid=".$tmp."'>`XIn den Kerker`0</a></td>",true);
                        }
                        else {
                                output('<td>`@ - </td>',true);
                        }

            if(su_check(SU_RIGHT_EDITORUSER) && $row['namecheck'] == $session['user']['acctid']) {
                                $str_lnk = 'su_delete.php?ids[]='.$tmp.'&ret='.urlencode(calcreturnpath());
                                output('<td>'.create_lnk('`CLöschen`0',$str_lnk).'</td>',true);
                        }
                        else {
                                output('<td>`@ - </td>',true); 
                        }
            output("</tr>",true);
               //umbenennung ueber verwaltungsbuero
            addnav("","su_registratur.php?op=rename&acctid=".$tmp);
            addnav("","su_registratur.php?op=imprison&userid=$tmp");
            addnav("","su_registratur.php?op=accept&userid=$tmp&nonew=1");
            addnav("","su_registratur.php?op=nameautochange&acctid=".$tmp);
        }
        output("</table>",true);
    }
    output('`c`n`n`n`n
            <u>`GVerfügbare Optionen:</u>`n`n
            <table style="border: none;">
            <tr><td>`^Umtaufen: </td><td>`gändert den gesamten Namen des Chars um in \'Neuling mit unzulässigem Namen Nr. X\';</td></tr>
            <tr><td style="vertical-align: top;">`QUmbenennen: </td><td>`gändert den Login-Namen des Chars (in Absprache mit dem betreffenden User!); der neue Login-Name gilt automatisch als akzeptiert;</td></tr>
            <tr><td>`@OK: </td><td>`gder vom User gewählte Login-Name wird ohne Änderung akzeptiert;</td></tr>
            <tr><td style="vertical-align: top;">`XIn den Kerker: </td><td>`gletzte Verwarnung; der Char wird eingekerkert und erhält eine weitere YOM, in der er zur Namensänderung aufgefordert wird, ansonsten wird der Char gelöscht;</td></tr>
            <tr><td>`CLöschen: </td><td>`gder Char wird (ggf. nach mehrfacher Verwarnung) gelöscht.</td></tr>
            </table>
            `n`n');
}

page_footer();
?>

