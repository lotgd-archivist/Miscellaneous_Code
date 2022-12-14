
<?php
/**
* su_bans.php: Werkzeug zum Verbannen von Spielern
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

$str_filename = basename(__FILE__);
require_once('common.php');
su_lvl_check(1,true,true);

page_header('Spieler verbannen');

output('`c`b`&Spieler verbannen`&`b`c`n`n');

// Grundnavi erstellen
addnav('Zurück');
addnav('G?Zur Grotte','superuser.php');
addnav('W?Zum Weltlichen',$session['su_return']);

$str_ret = urldecode($_REQUEST['ret']);
if(!empty($str_ret)) {
    addnav('Zurück', $str_ret);
}
addnav('Aktionen');
if(su_check(SU_RIGHT_EDITORUSER)) {
    addnav('Neuer Ban',$str_filename.'?op=edit_ban&ret='.urlencode($str_ret));
}
addnav('Banliste',$str_filename.'?ret='.urlencode($str_ret));
// END Grundnavi erstellen

// Evtl. Fehler / Erfolgsmeldungen anzeigen
if($session['message'] != '') {
    output('`n`b'.$session['message'].'`0`b`n`n');
    $session['message'] = '';
}
// END Evtl. Fehler / Erfolgsmeldungen anzeigen



// MAIN SWITCH
$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {
    
    // Eingabemaske
    case 'edit_ban':            
        
        $arr_form = array();
        $arr_data = array();
        $int_bancount = 1;
        $int_y_to = date('Y');
        $int_m_to = date('m') + 1;
        if($int_m_to > 12) {
            $int_m_to -= 12;
        }
        $int_d_to = date('d');
                
        // Fehlgeschlagener Eintrag
        if(!empty($session['ban_post'])) {
            
            $arr_data = $session['ban_post'];
            unset($session['ban_post']);
            $int_bancount = count($arr_data['ban']);
            
        }
            
            
        if(!empty($_GET['ids']) && is_array($_GET['ids'])) {
            $str_ids = implode(',',$_GET['ids']);
            
            $sql = 'SELECT login,acctid,emailaddress,lastip,uniqueid FROM accounts WHERE acctid IN ('.$str_ids.')';
            $res = db_query($sql);
            
            $int_bancount = db_num_rows($res);
            
            if($int_bancount > 1) {
                $arr_form[] = 'Gemeinsame Bedingungen,title';
                $arr_form['reason_custom_ges'] = 'Grund:';
                $arr_form['date_ges'] = 'Ein Datum für alle:,checkbox,1';
                $arr_form['perm_ges'] = 'Permanenter Ban:,checkbox,1';
                $arr_form['to_d_ges'] = 'BIS Tag,enum_order,1,31';
                $arr_form['to_m_ges'] = 'BIS Monat,enum_order,1,12';
                $arr_form['to_y_ges'] = 'BIS Jahr,enum_order,'.($int_y_to-1).','.($int_y_to+1);
                $arr_form[] = 'Die Bans im einzelnen,title';
                $arr_data['to_d_ges'] = $int_d_to;
                $arr_data['to_m_ges'] = $int_m_to;
                $arr_data['to_y_ges'] = $int_y_to;
            }
        }
    
                                            
        for($i=1; $i<=$int_bancount; $i++) {
            
            $arr_data['ban['.$i.']'] = $i;
            
            // Wenn Account gegeben
            if(isset($res)) {
                $acc = db_fetch_assoc($res);
                $arr_data['ip['.$i.']'] = $acc['lastip'];
                $arr_data['id['.$i.']'] = $acc['uniqueid'];
                $arr_data['mail['.$i.']'] = $acc['emailaddress'];
                $arr_data['login['.$i.']'] = $acc['login'];
            }
            
            if( empty($arr_data['to_d['.$i.']']) ) {
                $arr_data['to_d['.$i.']'] = $int_d_to;
                $arr_data['to_m['.$i.']'] = $int_m_to;
                $arr_data['to_y['.$i.']'] = $int_y_to;
            }
                        
            $arr_form = array_merge( 
                            $arr_form, array(
                                'ban['.$i.']'=>'BanNr.,hidden',
                                'reason_custom['.$i.']'=>'Grund:',
                                'ip['.$i.']'=>'IP:',
                                'id['.$i.']'=>'ID:',                                    
                                'mail['.$i.']'=>'EMail:',
                                'login['.$i.']'=>'Login:',
                                'perm['.$i.']'=> 'Permanenter Ban:,checkbox,1',
                                'to_d['.$i.']'=>'BIS Tag,enum_order,1,31',
                                'to_m['.$i.']'=>'BIS Monat,enum_order,1,12',
                                'to_y['.$i.']'=>'BIS Jahr,enum_order,'.($int_y_to-1).','.($int_y_to+1),
                                )
                            );
            
        }
        
        $str_lnk = $str_filename.'?op=insert_ban&ret='.urlencode($str_ret);
        addnav('',$str_lnk);
                    
        $str_out = '<form method="POST" action="'.$str_lnk.'">';
                    
        $str_out .= generateform($arr_form,$arr_data,false,'Ban(s) eintragen!');
        
        $str_out .= '</form>';
        
        output($str_out, true);
                    
    break;
    
    // Ban einfügen
    case 'insert_ban':
        
        su_check(SU_RIGHT_EDITORUSER,true);
        
        $int_bancount = count($_POST['ban']);
        
        unset($session['error']);
        
        $arr_users = array();
        
        $str_reason_ges = '';
        $date_exp_ges = '';            
        $bool_perm_ges = false;
                
        if(!empty($_POST['reason_custom_ges'])) {
            $str_reason_ges = $_POST['reason_custom_ges'];            
        }
        if($_POST['date_ges']) {
            $date_exp_ges = $_POST['to_y_ges'].'-'.$_POST['to_m_ges'].'-'.$_POST['to_d_ges'];            
        }
        if($_POST['perm_ges']) {
            $bool_perm_ges = true;            
        }
                        
        for($i=1; $i<=$int_bancount; $i++) {
            
            $session['message'] .= '`n`^Trage Ban Nr. '.$i.' ein..`n';
                        
            $str_reason     = (!empty($_POST['reason_custom_ges']) ? $_POST['reason_custom_ges'] : $_POST['reason_custom'][$i]);
            $str_ip         = (!empty($_POST['ip'][$i]) ? $_POST['ip'][$i] : false);
            $str_id         = (!empty($_POST['id'][$i]) ? $_POST['id'][$i] : false);
            $str_mail         = (!empty($_POST['mail'][$i]) ? $_POST['mail'][$i] : false);
            $str_login         = (!empty($_POST['login'][$i]) ? $_POST['login'][$i] : false);
            $date_exp         = ($_POST['date_ges'] ? $date_exp_ges : $_POST['to_y'][$i].'-'.$_POST['to_m'][$i].'-'.$_POST['to_d'][$i]);
            $bool_perm         = ($bool_perm_ges || $_POST['perm'][$i] ? true : false);
            $date_exp         = ($bool_perm ? '0000-00-00' : $date_exp);
                                    
            $result = setban(0, $str_reason, $date_exp, $str_ip, $str_id, $str_mail, $str_login);
            
            if(false === $result) {    // Fehler
                
                $session['ban_post'] = $_POST;
                                                
                switch($session['error']) {
                
                    case 'setban_expire_invalid':
                        $session['message'] .= '`$Die Ablaufzeit ist ungültig, muss in der Zukunft liegen!';
                    break;
                    
                    case 'setban_noconditions':
                        $session['message'] .= '`$Es sind keine Eigenschaften für den Ban gegeben!';
                    break;
                    
                    case 'setban_account_logout_failed':
                        $session['message'] .= '`$Logout der betroffenen Accounts fehlgeschlagen, nochmal versuchen!';
                    break;
                    
                    case 'setban_insert_failed':
                        $session['message'] .= '`$Einfügen des Bans fehlgeschlagen, nochmal versuchen!';
                    break;
                }
                                                
            }
            else {
                $session['message'] .= '`@Ban erfolgreich eingetragen!`n';
                
                $int_banid = db_insert_id();
                
                // Diesen Ban nicht mehr bearbeiten
                unset($session['ban_post']['ban'][$i]);
                unset($_POST['ban'][$i]);
                
                $str_user_affected = '';
                
                if(is_array($result) && sizeof($result) > 0) {
                                                            
                    $session['message'] .= 'Folgende Spieler sind zur Zeit davon betroffen:`n';
                    
                    foreach($result as $acctid => $acc) {
                        $str_user_affected .= $acc['name'].'`0; ';
                        $session['message'] .= $acctid.': `@'.$acc['name'].'`@`n';
                    }
                    
                    // Systemlog schreiben
                    systemlog('`QBan ID '.$int_banid.' gegen Spieler`0`n'.$str_user_affected,$session['user']['acctid']);                                        
                    
                }
                else {        
                    systemlog('`QAllg. Ban ID '.$int_banid.' eingetragen!',$session['user']['acctid']);
                
                    $session['message'] .= 'Der Ban betrifft zur Zeit keine Spieler!';
                }    
            }
            
        }
                    
        if(empty($session['error'])) {    
            redirect($str_filename.'?ret='.urlencode($str_ret));
        }
        else {
            redirect($str_filename.'?op=edit_ban&ret='.urlencode($str_ret));
        }
        
    break;
    
    // Ban löschen
    case 'del_ban':
        $int_banid = (int)$_GET['id'];
        
        $mixed_result = delban($int_banid);
        
        if( false === $mixed_result ) {
            $session['message'] = '`$Fehler bei Löschen des Bans ID '.$int_banid.':`n';
            
            switch($session['error']) {
                
                case 'delban_ban_notfound':
                    $session['message'] .= '`$Der gegebene Ban wurde nicht gefunden!';
                break;
                
                case 'delban_delete_failed':
                    $session['message'] .= '`$Löschen des Bans fehlgeschlagen!';
                break;
            }
        }    
        else {
            $session['message'] = '`@Ban ID '.$int_banid.' erfolgreich gelöscht!`0';         
            
            $str_affected = '`nBetroffen: ';
            foreach ($mixed_result as $a) {
                $str_affected .= $a['login'].'; ';
            }
            
            systemlog('`@Ban ID '.$int_banid.' aufgehoben!'.$str_affected,$session['user']['acctid']);
        }
        redirect($str_filename.'?ret='.urlencode($str_ret));
        
    break;

    // EMail oder Login auf Blacklist setzen
    case 'blacklist':
        
        $int_banid = (int)$_GET['id'];
        
        $arr_ban = db_fetch_assoc(db_query('SELECT mailfilter,loginfilter,banreason FROM bans WHERE id='.$int_banid));
        
        $int_type = 0;
        
        if($_GET['what'] == 'mail') {
            $int_type = BLACKLIST_EMAIL;
            $str_val = $arr_ban['mailfilter'];
        }
        else {
            $int_type = BLACKLIST_LOGIN;
            $str_val = $arr_ban['loginfilter'];
        }
        
        // Duplikate vermeiden
        $sql = 'DELETE FROM blacklist WHERE value="'.addslashes($str_val).'" AND type=(0 ^ '.$int_type.')';
        db_query($sql);
        
        $sql = 'INSERT INTO blacklist SET value="'.addslashes($str_val).'",type='.$int_type.',remarks="Anlässl. Ban ID '.$int_banid.', Grund: '.addslashes($arr_ban['banreason']).(!empty($arr_ban['loginfilter']) ? ', Login: '.addslashes($arr_ban['loginfilter']) : '').'"';
        db_query($sql);
        
        $session['message'] = '`@'.$str_val.' auf die Blacklist gesetzt!`0';         
        
        redirect($str_filename.'?ret='.urlencode($str_ret));
        
    break;
    
    // Ban übergehen
    case 'override_ban_on':
        
        $int_id = (int)$_GET['id'];
        
        $sql = 'UPDATE accounts SET banoverride = 1 WHERE acctid='.$int_id;
        db_query($sql);
        
        $session['message'] = '`@AcctID '.$int_id.' übergeht nun Ban!`0';
        
        redirect($str_filename.'?ret='.urlencode($str_ret));
        
    break;
    
    // Ban übergehen AUS
    case 'override_ban_off':
        
        $int_id = (int)$_GET['id'];
        
        // Gleich mit ausloggen
        $sql = 'UPDATE accounts SET banoverride = 0,loggedin = 0 WHERE acctid='.$int_id;
        db_query($sql);
        
        $session['message'] = '`@AcctID '.$int_id.' übergeht Ban nicht mehr!`0';
        
        redirect($str_filename.'?ret='.urlencode($str_ret));
        
    break;
    
    // Liste der Bans        
    default:
        
        $str_out = '';
        $str_trclass = '';    
                        
        $sql = 'SELECT         b.*, a.name, a.login, a.acctid, a.banoverride 
                FROM         bans b
                LEFT JOIN    accounts a ON 
                            (
                                 (b.ipfilter != "" AND b.ipfilter = a.lastip) OR
                                (b.uniqueid != "" AND b.uniqueid = a.uniqueid) OR 
                                (b.loginfilter != "" AND b.loginfilter = a.login) OR 
                                (b.mailfilter != "" AND b.mailfilter = a.emailaddress)                
                            )
                ORDER BY     banexpire DESC';
        $res = db_query($sql);
        
        $str_out .= '`c<table cellspacing="3" cellpadding="3">
                            <tr class="trhead">
                                <td>`bAktionen`b</td>
                                <td>`bBanID`b</td>
                                <td>`bLäuft aus`b</td>
                                <td>`bIP / ID`b</td>
                                
                                <td>`bEMail`b</td>
                                <td>`bLogin`b</td>
                            </tr>';
                            
        if(db_num_rows($res) == 0) {
            $str_out .= '<tr><td colspan="8">`iKeine Bans vorhanden!`i</td></tr>';
        }
        
        $int_lastban = 0;

        $bool_editbans = su_check(SU_RIGHT_EDITORUSER);
            
        while($b = db_fetch_assoc($res)) {
            
            // Nächsten Ban darstellen
            if($int_lastban != $b['id']) {
                
                $b['ipfilter'] = (!empty($b['ipfilter']) ? $b['ipfilter'] : 'Keine IP');
                $b['idfilter'] = (!empty($b['uniqueid']) ? $b['uniqueid'] : 'Keine ID');
                
                // Letzte Zeile noch schließen
                $str_out .= '`n</td></tr><tr><td colspan="8">&nbsp;</td></tr>';
                    
                $int_lastban = $b['id'];                    
                $str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
                
                $str_out .= '<tr class="'.$str_trclass.'">';
                $str_out .= '<td>'.($bool_editbans ? '['.create_lnk('Del',$str_filename.'?op=del_ban&id='.$b['id'].'&ret='.urlencode($str_ret),true,false,'Diesen Ban wirklich aufheben?').']' : '').'</td>';
                $str_out .= '<td>`b'.$b['id'].'`b</td>';
                $str_out .= '<td>`b'.( $b['banexpire'] == '0000-00-00' ? 'St.Nimmerleins-Tag' : date('d.m.Y',strtotime($b['banexpire'])) ).'`b</td>';
                $str_out .= '<td>`b'.$b['ipfilter'].'`b';
                $str_out .= '`n`b'.$b['uniqueid'].'`b</td>';
                $str_out .= '<td>`b'.$b['mailfilter'].'`b'.
                            (!empty($b['mailfilter']) && $bool_editbans ? '`n['.create_lnk('Blacklist',$str_filename.'?op=blacklist&id='.$b['id'].'&what=mail&ret='.urlencode($str_ret),true,false,'Diese EMail wirklich auf die Blacklist?').']' : '').
                            '</td>';
                $str_out .= '<td>`b'.$b['loginfilter'].'`b'.
                            (!empty($b['loginfilter']) && $bool_editbans ? '`n['.create_lnk('Blacklist',$str_filename.'?op=blacklist&id='.$b['id'].'&what=login&ret='.urlencode($str_ret),true,false,'Diesen Login wirklich auf die Blacklist?').']' : '').
                            '</td>';
                $str_out .= '</tr>';
                
                $str_out .= '<tr class="'.$str_trclass.'">';
                $str_out .= '<td colspan="8" valign="top">
                                Grund: `^'.$b['banreason'].'`&`n
                                Letzter Loginversuch: `^'.($b['last_try'] != '0000-00-00 00:00:00' ? date('d.m.Y H:i:s',strtotime($b['last_try'])) : ' - ').'`&`n
                                Betrifft zur Zeit: ';            
                            
            }
            
            if($b['acctid'] > 0) {
                $str_out .= '`^ `b'.$b['login'].'`^ (ID '.$b['acctid'];
                if($b['banoverride']) {
                    $str_out .= ', `@Übergeht Ban!`^'
                                .($bool_editbans ? ' [ '.create_lnk('Aus!',$str_filename.'?op=override_ban_off&id='.$b['acctid'].'&ret='.urlencode($str_ret),true,false,'Wirklich?!').' ]' : '');
                }
                else {
                    $str_out .= ($bool_editbans ? ' [ '.create_lnk('Übergehen!',$str_filename.'?op=override_ban_on&id='.$b['acctid'].'&ret='.urlencode($str_ret),true,false,'Wirklich?!').' ]' : '');
                }
                $str_out .= ');`b';
            }
            else {
                $str_out .= '`b`$Niemanden`^`b ';                    
            }        
        }
        
        $str_out .= '`n</td></tr>';
        $str_out .= '</table>`c';
        
        output($str_out, true);
        
    break;
}


page_footer();
?>


