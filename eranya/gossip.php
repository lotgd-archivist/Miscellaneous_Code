
<?php
/**
* gossip.php: Stellt bewährte gossip-Funktionalität zur Verfügung, erweitert
*                        um Editfunktion, Autorangabe, Datumsanzeige, Archiv.
* @author LOGD-Core, modified and rewritten by talion <t@ssilo.de>
* @version DS-E V/2
*/

require_once('common.php');

session_write_close();
popup_header('Rasende Reporter');

function gossipitem($subject,$body,$date='',$author='',$str_targets='') {
    global $output;
    output('`b'.($author != '' ? $author : '').' '.$subject.'`b`n',true);
    if ($date!='') {
        output('`Y`i[ '.strftime('%A, %e. %B %Y, %H:%M',strtotime($date)).' ]`i`n');
    }
    output('`3'.closetags($body,'`i`b`c'));
    if(strlen($str_targets) > 2) {        # wenn $str_targets gegeben, ggf. Bio verlinken
        $arr_targets = explode('||',$str_targets); $str_target_bio = '';
        foreach($arr_targets AS $v) {
            $arr_target = explode(':',$v);
            // Überprüfen, welche Daten geladen werden sollen (Haupt- oder Zusatzchar)
            if($arr_target[1] == 'knappe') {              # Knappe verlinken
                $row_t = db_fetch_assoc(db_query("SELECT name AS targetname, master AS targetid FROM disciples WHERE name LIKE '".str_create_search_string($arr_target[0])."' LIMIT 1"));
                $arr_target[1] = 'disc';
            } elseif($arr_target[1] == 'xchar1') {       # 1. X-Char verlinken
                $row_t = db_fetch_assoc(db_query("SELECT mount_category AS targetname, acctid AS targetid FROM account_bios WHERE mount_category LIKE '".str_create_search_string($arr_target[0])."' LIMIT 1"));
                $arr_target[1] = 'mount';
            } elseif($arr_target[1] == 'xchar2') {       # 2. X-Char verlinken
                $row_t = db_fetch_assoc(db_query("SELECT xchar_category AS targetname, acctid AS targetid FROM account_bios WHERE xchar_category LIKE '".str_create_search_string($arr_target[0])."' LIMIT 1"));
                $arr_target[1] = 'xchar';
            } else {                                    # Hauptchar verlinken
                $row_t = db_fetch_assoc(db_query("SELECT name AS targetname, acctid AS targetid FROM accounts WHERE name LIKE '".str_create_search_string($arr_target[0])."' LIMIT 1"));
                $arr_target[1] = 'hc';
            }
            // Link setzen - aber nur, wenn DB-Abfrage erfolgreich war
            if($row_t !== false) {
                $str_link = 'bio_popup.php?id='.$row_t['targetid'].'&bioid='.$arr_target[1];
                $str_target_bio .= '<a href="'.$str_link.'" target="_blank" onClick="'.popup($str_link).';return:false;" style="text-decoration: none">`h'.$row_t['targetname'].'</a>`Y, ';
            } else {
                $str_target_bio .= '`h'.$arr_target[0].'`Y, ';
            }
        }
        output('`n`i`Y[ Ansprechperson'.(count($arr_targets) > 1 ? 'en' : '').': '.substr($str_target_bio,0,-4).' `Y]`i`7');
    }
    $output.='<hr>';
}

$g = user_get_aei('getgossip');

output('<script type="text/javascript" src="templates/bioTooltip.js"></script>
        <style type="text/css">
         .tooltip {
              position: absolute;
              display: none;
              padding: 5px;
              border: 1px solid #444444;
              background-color: #111111;
              color: #ffffff;
         }
         a.squirrel {
               padding: 14px 3px 2px 3px;
               border: 1px dotted #9d3e00;
               height: 23px;
               width: 32px;
         }
         a:active.squirrel {
               border: none;
               top: 3px;
               position: relative;
               padding: 7px 3px 1px 3px;
         }
        </style>`n
        `c`b`IDie Rasenden Reporter`b &ensp;&ensp;<a href="gossip.php?op=subscribe" class="squirrel" onmouseover="showWMTT(\'1\')" onmouseout="hideWMTT()">'.($g['getgossip'] == 1 ? '<img src="images/rss/posthoernchen_x.png" alt="Newsletter abbestellen">' : '<img src="images/rss/posthoernchen.png" alt="Newsletter abonnieren">').'</a>`c`n');
if (su_check(SU_RIGHT_GOSSIP)) {
    output('`c`X[<a href="gossip.php?op=edit">`YNeue Nachricht erstellen</a>`X]`c`n');
}

switch($_GET['op']) {
                
        case 'edit':
                
                if(!su_check(SU_RIGHT_GOSSIP)) {
                        if ($session['user']['loggedin']) {
                                $session['user']['experience']=round($session['user']['experience']*0.9,0);
                                addnews($session['user']['name'].'wurde für den Versuch, die Götter zu betrügen, bestraft.');
                                output('Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.');
                                saveuser();
                        }
                        exit;
                }
                
                output(' `X[<a href="gossip.php">`YZur Startseite</a>`X]`0`n');
                //$int_item = (int)$_REQUEST['gossipitem'];
                $int_item = (int)$_GET['gossipitem'];
                $str_body = encode_specialchars(closetags(strip_tags($_POST['gossipbody']),'`i`b`c'));
                $str_savebody = '';
                $str_title = encode_specialchars(closetags(strip_tags($_POST['gossiptitle']),'`i`b`c'));
                $str_target = encode_specialchars(strip_appoencode(strip_tags($_POST['gossiptarget']),3));
                $int_author = (int)$_POST['gossipauthor'];
                $arr_body = array();
                                                
                if($_GET['act'] == 'save')  {
                        $str_savebody = nl2br($str_body);
                        $sql = ($int_item ? 'UPDATE ' : 'INSERT INTO ');
                        $sql .= ' gossip SET ';
                                
                        $sql .= '        gossiptitle="'.$str_title.'",
                                                gossipbody="'.$str_savebody.'",
                                                gossipdate='.($int_item==0 || $_POST['newgossip'] ? 'NOW()' : 'gossipdate').',
                                                gossipauthor='.($int_author > -1 ? $int_author : 'gossipauthor').',
                                                gossiptarget='.($str_target > -1 ? '"'.$str_target.'"' : 'gossiptarget').'';
                        
                        $sql .= ($int_item ? ' WHERE gossipitem='.$int_item : '');
                                                
                        db_query($sql);
                                                                        
                        if(!db_error(LINK))  {
                                if($int_item==0 || $_POST['newgossip']) {
                                        $sql = db_query('SELECT acctid FROM account_extra_info WHERE getgossip = 1 AND acctid!='.$session['user']['acctid']);
                                        if(db_num_rows($sql) > 0) {
                                                while($m = db_fetch_assoc($sql)) {
                                                    systemmail($m['acctid'],'Neues von den Rasenden Reportern!','Es gibt Neues zu berichten über die Geschehnisse in Eranya. Das solltest du dir auf keinen Fall entgehen lassen!');
                                                }
                                        }
                                }
                        
                                $session['message'] = '`@Die Neuigkeit wurde erfolgreich eingetragen! Alle Abonnenten wurden benachrichtigt.`0';
                                
                                header('Location: gossip.php');
                                exit;
                        }
                }
                
                $str_author_list = ',enum,0,Die Rasenden Reporter,'.$session['user']['acctid'].','.$session['user']['login'];
                
                $arr_form = array('gossipitem'=>',hidden',
                                                        'gossipauthor'=>'Autor:'.$str_author_list,
                                                        'gossiptitle'=>'Titel:',
                                                        'gossipbody'=>'Inhalt:`n,textarea,60,8',
                                                        'gossiptarget'=>'Ansprechpartner:|?Namen ohne Farbcodes eingeben und mit || voneinander trennen; z.B. Samir der Schwätzer||Zephyr Viljanen||...<br>- Bei Knappen -> Authun Yngvulf:knappe||...<br>- Bei X-Chars -> Caligo Gandareva:xchar1||Aron Bareth Hawke:xchar2||...'
                                                        );
                $arr_data = array('gossipitem'=>$int_item,
                                                        'gossipauthor'=>($int_author?$int_author:$session['user']['acctid']),
                                                        'gossiptitle'=>$str_title,
                                                        'gossipbody'=>$str_body,
                                                        'gossiptarget'=>$str_target
                                                        );
                if($int_item > 0)  {
                        $sql = 'SELECT * FROM gossip WHERE gossipitem='.$int_item;
                        $arr_gossip = db_fetch_assoc(db_query($sql));
                        
                        $arr_gossip['gossipbody'] = str_replace('<br />','',$arr_gossip['gossipbody']);
                        
                        $arr_form['newgossip'] = 'Nachricht als neu markieren:,bool';
                        $arr_data['newgossip'] = 0;
                        
                        $arr_form['gossipauthor'] .= ',-1,~ Keine Änderung ~';
                        $arr_gossip['gossipauthor'] = -1;
                        
                        $arr_data = array_merge($arr_data,$arr_gossip);
                }
                                
                $output.='<form action="gossip.php?op=edit&act=save&gossipitem='.$int_item.'" method="POST">';
                                                        
                showform($arr_form,$arr_data,false,'Veröffentlichen!');
                
                $output .= '</form>';
                output('`n`n`b`YVorschau:`0`b`n`n');
                rawoutput('<u>Titel:</u> '.js_preview('gossiptitle').'<br /><br />');
                rawoutput('<u>Neuigkeit:</u><br /><br />'.js_preview('gossipbody',true).'<br /><br />');
        break;
                
        case 'del':
                
                if (su_check(SU_RIGHT_GOSSIP)) {
                        $sql = 'DELETE FROM gossip WHERE gossipitem='.(int)$_GET['id'];
                        db_query($sql);
                        
                        header('Location: gossip.php');
                        exit();
                } else {
                        if ($session['user']['loggedin']) {
                                $session['user']['experience']=round($session['user']['experience']*0.9,0);
                                addnews($session['user']['name'].' wurde für den Versuch, die Götter zu betrügen, bestraft.');
                                output('Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.');
                                saveuser();
                        }
                        exit;
                }
                
        break;
        
        case 'subscribe':
        
                output(' `X[<a href="gossip.php">`YZur Startseite</a>`X]`0`n`n`n');
                if($_GET['confirm'] == 'yes') {
                        if($g['getgossip'] == 1) {
                                output('`^Du hast den Newsletter abbestellt und wirst ab sofort nicht mehr via Posthörnchen über das Stadtgeschehen informiert.`n`n');
                                $g['getgossip'] = 0;
                        } else {
                                output('`@Du hast den Newsletter abonniert und wirst ab sofort via Posthörnchen über das Stadtgeschehen informiert.`n`n');
                                $g['getgossip'] = 1;
                        }
                        user_set_aei(array('getgossip'=>$g['getgossip']));
                } else {
                        if($g['getgossip'] == 1) {
                                output("`^Möchtest du den Newsletter wirklich abbestellen? Dann wirst du zukünftig nicht mehr über die Geschehnisse in der Stadt informiert.`n
                                        `n
                                        <a href='gossip.php?op=subscribe&confirm=yes' class='button'>Ja, abbestellen!</a>`n`n");
                        } else {
                                output("`^Möchtest du den Newsletter abonnieren? Dann wirst du via Posthörnchen darüber informiert, wenn es neue Meldungen zum Stadtgeschehen gibt.`n
                                        Du kannst den Newsletter jederzeit abbestellen.`n
                                        `n
                                        <a href='gossip.php?op=subscribe&confirm=yes' class='button'>Ja, abonnieren!</a>`n`n");
                        }
                }
        
        break;
                
        default:

                $last_gossipdate = '0000-00-00 00:00:00';
                $per_page = 10;

                output('`&');

                $sql = 'SELECT COUNT(*) AS anzahl FROM gossip';
                $res = db_query($sql);
                $nr = db_fetch_assoc($res);

                $pagecount = ceil($nr['anzahl']/$per_page);
                $page = ($_POST['page'])?$_POST['page']:1;
                $from = ($page-1) * $per_page;
                $select = '<form action="gossip.php" method="POST">
                -&#8212; Umblättern: <select name="page" size="1" onChange="this.form.submit();">';

                for ($i=1; $i<=$pagecount; $i++) {
                        $select .= '<option value="'.$i.'" '.(($page==$i)?'selected="selected"':'').'>Seite '.$i.'</option>';
                }
                $select .= '</select>  -&#8212;</form>';

                $sql = 'SELECT g.*,a.login FROM gossip g LEFT JOIN accounts a ON a.acctid=g.gossipauthor ORDER BY g.gossipdate DESC LIMIT '.$from.','.$per_page;
                $result = db_query($sql);
                $count = db_num_rows($result);
                if($count > 0) {
                        for ($i=0; $i<$count; $i++) {
                                $row = db_fetch_assoc($result);

                                if ($i == 0) {
                                        $last_gossipdate = $row['gossipdate'];
                                }

                                $author = '`&'.($row['login'] != '' ? $row['login'] : 'Die Rasenden Reporter').':';

                                $str_subj = ''.$row['gossiptitle']
                                .(su_check(SU_RIGHT_GOSSIP)?
                                " `7[<a href='gossip.php?op=edit&gossipitem=".$row['gossipitem']."'>`FEdit</a>`7|<a href='gossip.php?op=del&id=".$row['gossipitem']."' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">`4Del</a>`7]"
                                :"");
                                $str_body = '`3'.$row['gossipbody'];

                                gossipitem($str_subj,$str_body,$row['gossipdate'],$author,$row['gossiptarget']);
                        }
                        output('`c'.$select.'`c',true);
                } else {
                        output("`n`n`c`i`7Es gibt noch keine Neuigkeiten zu den Geschehnissen der Stadt.`i`c`n`n");
                }
                // Tooltip -> Infotext in eigenem Fenster
                output('<div id="1" class="tooltip" style="text-align: center;">'.($g['getgossip'] == 1 ? 'Newsletter abbestellen' : 'Klick mich!<br><i>(Newsletter abonnieren)</i>').'</div>');
                // end
        break;                
}

popup_footer();
?>

