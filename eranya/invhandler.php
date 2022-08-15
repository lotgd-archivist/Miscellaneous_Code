
<?php
/**
 * invhandler.php: neuer Itemhandler für Drachenserver-Itemsystem
 * @author talion <t@ssilo.de>
 * @version DS-E V/2
*/

require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

$id = (int)$_REQUEST['id'];
$ret = $_REQUEST['ret'];

$base_link = 'invhandler.php?ret='.urlencode($ret).($id > 0 ? '&id='.$id : '');

if(isset($id) && $id > 0){
    $item = item_get('id='.$id);
    $_POST['ids'] = array($item['id']);
}

page_header('Inventar');

$str_op = (isset($_POST['op']) ? $_POST['op'] : $_GET['op']);
if($str_op == 'uml') {
    $str_op = 'einl';
    $_GET['how'] = 'intern';
}

switch($str_op) {
        
        // Inventar, Benutzen
        case 'use':
                
                $item_hook_info['link'] = $base_link;
                $item_hook_info['ret'] = $ret;
                
                item_load_hook($item['use_hook'],'use',$item);
                
                break;
        
        // Wegwerfen        
        case 'wegw':        
                
                if($_GET['act'] != 'ok') {
                        output('`QBist du dir sicher, '.$item['name'].'`Q unwiederbringlich aufzugeben?');                
                        
                        addnav('Nein, zurück!',$ret);
                        
                        addnav('Ja, weg damit!',$base_link.'&op=wegw&act=ok');
                }
                else {
                        
                        output('`QDu schleppst '.$item['name'].'`Q in eine dunkle Seitengasse und lässt es dort stehen und liegen. Da wird sich schon jemand drum kümmern..');                
                        
                        item_delete('id='.$id);
                        
                        addnav('Zum Inventar',$ret);                
                        
                }                
        
                break;
        
        // Items mit gleicher tpl_id wegwerfen
        case 'wegw_alle':

                if($_GET['act'] != 'ok') {
                        output('`QBist du dir sicher, alle '.$item['name'].'`Q unwiederbringlich aufzugeben?');

                        addnav('Nein, zurück!',$ret);

                        addnav('Ja, weg damit!',$base_link.'&tid='.$_GET['tid'].'&op=wegw_alle&act=ok');
                } else {

                        output('`QDu schleppst alle '.$item['name'].'`Q in eine dunkle Seitengasse und lässt sie dort stehen und liegen. Da wird sich schon jemand drum kümmern..');

                        item_delete('tpl_id="'.$_GET['tid'].'" AND deposit1 !='.ITEM_LOC_EQUIPPED);

                        addnav('Zum Inventar',$ret);

                }

                break;
        
        // Einlagern in Haus o. Gemach        
        case 'einl':        
                
                // Wird Item innerhalb des Hauses verschoben?
                if(!empty($_GET['how'])) $session['umlagern'] = true;

                if($_GET['act'] == 'house') {
                
                        $sql = 'SELECT k.*,h.status,h.houseid,h.owner,h.housename FROM keylist k LEFT JOIN houses h ON h.houseid=k.value1 WHERE k.owner='.$session['user']['acctid'].' ORDER BY id ASC';
                        $res = db_query($sql);
                        // Baustelle
                        while($k = db_fetch_assoc($res)) {
                                $link = $base_link.'&op=einl&act=room&housenr='.$k['houseid'];
                                output('<a href="'.$link.'">'.$k['housename'].'</a>`n',true);
                                addnav('',$link);                        
        
                        }
                        addnav('Zurück','invhandler.php?op=house&id='.$id);
                        
                }
                elseif($_GET['act'] == 'private') {
                        
                        output('`QDu besitzt Schlüssel zu Privatgemächern in diesen Häusern:`n`n');
                        
                        $sql = 'SELECT i.*,h.status,h.houseid,h.owner,h.housename FROM items i LEFT JOIN houses h ON h.houseid=i.value1 WHERE i.tpl_id="privb" AND i.owner='.$session['user']['acctid'].' AND i.value1!='.$session['user']['house'].' ORDER BY id ASC';
                        $res = db_query($sql);
                        
                        if($session['user']['house']) {
                                
                                $link = $base_link.'&op=einl&act=ok&housenr='.$session['user']['house'].'&private='.$session['user']['acctid'];
                                output('<a href="'.$link.'">Privatgemach in eigenem Haus</a>`n',true);
                                addnav('',$link);
                                
                        }
                                                                
                        while($k = db_fetch_assoc($res)) {
                                $link = $base_link.'&op=einl&act=ok&housenr='.$k['houseid'].'&private='.$session['user']['acctid'];
                                output('<a href="'.$link.'">'.$k['housename'].'</a>`n',true);
                                addnav('',$link);                        
        
                        }
                        addnav('Zurück',$base_link.'&op=einl');
                        
                }
                elseif($_GET['act'] == 'room')
                {
                        // Welcher Raum?
                        $housenr = (int)$_GET['housenr'];
                        output("`QIn welchen Raum möchtest du ".implode('`Q, ',array_unique(array_map(function($arr) {return $arr['name'];},$session['items'])))." `Qeinlagern?`n`n");
                        $link = $base_link.'&op=einl&act=ok&housenr='.$housenr;
                        // Welche Räume gibt es im Haus?
                        $hsql = db_query("SELECT roomid FROM houses_rooms WHERE houseid = ".$housenr);
                        while($hrow = db_fetch_assoc($hsql)) {
                            output("<a href='".$link."&room=".$hrow['roomid']."'>".$arr_rooms_names[$hrow['roomid']]."</a>`n`n");
                            addnav('',$link.'&room='.$hrow['roomid']);
                        }
                        // Schatzkammer & Flur
                        output("<a href='".$link."&room=treas'>Schatzkammer</a>`n`n");
                        addnav('',$link.'&room=treas');
                        output("<a href='".$link."&room=floor'>Flur</a>`n`n");
                        addnav('',$link.'&room=floor');
                        // Zurück
                        addnav('Zurück',$base_link.'&op=einl');
                }
                elseif($_GET['act'] == 'ok') {
                        
                        $housenr = (int)$_GET['housenr'];
                        $private = (int)$_GET['private'];
                        $room = $_GET['room'];
                        
                        // Max. Anzahl an Möbeln pro Ort ermitteln:
                        $sql = 'SELECT status FROM houses WHERE houseid='.$housenr;
                        $house = db_fetch_assoc(db_query($sql));
                        if($private) {
                            $max_count_all = get_max_furniture($house['status'],true);
                        } else {
                            $max_count_all = get_max_furniture($house['status']);
                        }
                        
                        $out_full = '';
                        foreach($session['items'] AS $v) {
                            // Item-Details laden
                            $item = item_get('id='.$v['id']);
                            // Check auf Gesamtzahl dieses Stücks
                            $max_count = $item['deposit'.($private ? '_private' : '')];
                            $count = item_count( ' deposit1 = '.$housenr.' AND deposit2 = '.$private.' AND tpl_id="'.$item['tpl_id'].'"' );
                            // Soll Item nur umgelagert werden? Dann ggf. count um 1 herabsetzen:
                            if($session['umlagern'] && $private == $item['deposit2']) {
                                $count--;
                            }

                            if($max_count == 0) {
                                output('`FMal ehrlich, du willst '.$item['name'].'`F doch nicht wirklich einlagern?! Nicht mal ein Erztroll würde so etwas tun..`n`n');
                            } elseif($count >= $max_count) {
                                output('`FDu kannst von '.$item['name'].'`F maximal '.$max_count.($max_count == 1 ? ' Exemplar' : ' Exemplare').' eingelagert haben. Mehr hätte einfach keinen Stil..`n`n');
                            } else {
                                // Ist noch Platz zum Einlagern?
                                $count = item_count(' deposit1 = '.$housenr.' AND deposit2 = '.$private);
                                if($count > $max_count_all) {     // Wenn nicht, Schleife unterbrechen
                                    $out_full = '`FDu hast an diesem Ort bereits `q'.$count.'`F Möbel deponiert. Mehr passt dort einfach nicht rein.`n`n';
                                    break;
                                } else {                      // Ansonsten Einlagern vorbereiten
                                    $arr_items_names[] = $item['name'];
                                    $arr_items_ids[] = $item['id'];
                                }
                            }
                        }
                        if(isset($arr_items_ids)) {
                            $str_items_names = implode('`Q, ',array_unique($arr_items_names));
                            output("`QDu suchst für `q".$str_items_names."`Q einen Ehrenplatz in deinem Haus, an dem `q".$str_items_names."`Q von jetzt an den Staub fangen ".(count($arr_items_names) == 1 ? "wird" : "werden").".`n`n");
                            db_query("UPDATE items SET deposit1 = ".$housenr.", deposit2 = ".$private.", deposit_where = '".$room."' WHERE id IN (".implode(',',$arr_items_ids).")");
                        }
                        unset($session['items']); unset($session['umlagern']);
                        output($out_full);
                        
                } else {

                        // Multiauswahl berücksichtigen & in session speichern:
                        $str_ids = (isset($_POST['ids']) ? implode(',',$_POST['ids']) : '1');
                        $str_tpl = implode('","',$_POST['tpl']);
                        $res_items = item_list_get(' (id IN ('.addslashes(stripslashes($str_ids)).') OR tpl_id IN ("'.addslashes(stripslashes($str_tpl)).'")) AND owner='.$session['user']['acctid'],'',true,'id,name');

                        if(db_num_rows($res_items) == 0) {
                                //redirect($base_link);
                        }

                        $session['items'] = db_create_list($res_items);
                        $str_items_names = implode('`Q, ',array_unique(array_map(function($arr) {return $arr['name'];},$session['items'])));
                        //

                        output('`QWohin willst du `q'.$str_items_names.' `Qbringen?');
                        
                        if($session['user']['house']) {addnav('Ins Haus',$base_link.'&op=einl&act=room&housenr='.$session['user']['house']);}
                        if($session['user']['house'] || db_num_rows(db_query("SELECT i.id FROM items i WHERE i.tpl_id='privb' AND i.owner=".$session['user']['acctid']." AND i.value1!=".$session['user']['house'])) > 0) {
                                addnav('In Privatgemächer',$base_link.'&op=einl&act=private');
                        }
                        
                }
                
                addnav('Zum Inventar',$ret);
        
                break;
        
        // Auslagern aus Haus o. Gemach        
        case 'ausl':
                
                $str_ids = (isset($_POST['ids']) ? implode(',',$_POST['ids']) : '1');
                $str_tpl = implode('","',$_POST['tpl']);
                $res_items = item_list_get(' (id IN ('.addslashes(stripslashes($str_ids)).') OR tpl_id IN ("'.addslashes(stripslashes($str_tpl)).'")) AND owner='.$session['user']['acctid'].' AND deposit1 != 0','',true,'id,name');

                if(db_num_rows($res_items) == 0) {
                        redirect($base_link);
                }

                $arr_items = db_create_list($res_items);
                $str_items_ids = implode(',',array_map(function($arr) {return $arr['id'];},$arr_items));
                $str_items_names = implode('`Q, ',array_unique(array_map(function($arr) {return $arr['name'];},$arr_items)));
                output('`QDu packst '.$str_items_names.' `Qwieder in dein Inventar.');
                
                item_set('id IN ('.addslashes(stripslashes($str_items_ids)).')',array('deposit1'=>0,'deposit2'=>0,'deposit_where'=>''));
                
                addnav('Zum Inventar',$ret);
                
                break;
                
        // Waffe, Rüstung o.ä. anlegen
        case 'ausr':
                
                if($item['equip'] == ITEM_EQUIP_WEAPON) {
                        
                        $w_old = item_set_weapon($item['name'],$item['value1'],$item['gold'],$id);
                        
                        $old_name = $w_old['name'];
                        
                        $old_attack = $session['user']['attack'] - $session['user']['weapondmg'] + $w_old['tpl_value1'];
                                                
                        output('`QDu tauschst `q'.$old_name.'`Q gegen '.$item['name'].'`Q. 
                                        Dein Angriff verändert sich dadurch von '.$old_attack.' auf '.$session['user']['attack'].'!');
                                                
                }                
                
                else if($item['equip'] == ITEM_EQUIP_ARMOR) {
                        
                        $a_old = item_set_armor($item['name'],$item['value1'],$item['gold'],$id);
                        
                        $old_name = $a_old['name'];
                        
                        $old_defence = $session['user']['defence'] - $session['user']['armordef'] + $a_old['tpl_value1'];
                                                
                        output('`QDu tauschst `q'.$old_name.'`Q gegen '.$item['name'].'`Q. 
                                        Deine Verteidigung verändert sich dadurch von '.$old_defence.' auf '.$session['user']['defence'].'!');
                                                
                }                
                
                addnav('Zum Inventar',$ret);
                
                break;
        
        // Angelegtes Item ablegen und in Invent zurückpacken        
        case 'abl':
                
                if($_GET['what'] != '') {
                        $what = $_GET['what'];
                }
                else {
                        if($item['equip'] == ITEM_EQUIP_WEAPON) {
                                $what = 'weapon';
                        }
                        else if($item['equip'] == ITEM_EQUIP_ARMOR) {
                                $what = 'armor';
                        }
                }                
        
                        
                if($what == 'weapon') {
                        
                        $old = $session['user']['attack'];
                                
                        // ohne Params, um Fists zu setzen
                        $w_old = item_set_weapon();
                        
                        $old_name = $w_old['name'];
                        
                        output('`QDu legst `q'.$old_name.'`Q ab. 
                                        Dein Angriff verändert sich dadurch von '.$old.' auf '.$session['user']['attack'].'!');
                        
                }
                
                else if($what == 'armor') {
                        
                        $old = $session['user']['defence'];
                                
                        // ohne Params, um T-Shirt zu setzen
                        $a_old = item_set_armor();
                        
                        $old_name = $a_old['name'];
                        
                        output('`QDu legst `q'.$old_name.'`Q ab. 
                                        Deine Verteidigung verändert sich dadurch von '.$old.' auf '.$session['user']['defence'].'!');
                        
                }
                
                addnav('Zum Inventar',$ret);                
                                
                break;

        // Kärtchentext verwalten
        case 'card':
                $arr_spinfo = explode('-||-',$item['special_info']);
                if(isset($arr_spinfo[1])) {
                    if($_GET['save'] == 'ok') {   # Bei Bestätigung das Feld special_info anpassen und speichern
                        $arr_spinfo[1] = ((int)$arr_spinfo[1] == 1 ? 0 : 1);
                        $str_spinfo = implode('-||-',$arr_spinfo);
                        db_query("UPDATE items SET special_info = '".$str_spinfo."' WHERE owner = ".$session['user']['acctid']." AND id = ".$item['id']);
                        if($arr_spinfo[1]) {
                            output("`rDie Botschaft auf dem Kärtchen kann nun von dir und anderen in Häusern etc. gelesen werden.`n`n");
                        } else {
                            output("`rDie Botschaft auf dem Kärtchen kann nun nicht mehr von dir und anderen in Häusern etc. eingesehen werden.`n`n");
                        }
                    } else {  # Soll Kärtchentext wirklich an Beschreibungstext angehängt werden? Dies ist ein irreversibler Vorgang!
                        output("`rMöchtest du wirklich, dass die Botschaft auf dem beigelegten Kärtchen "
                               .($arr_spinfo[1] == 0 ? "angezeigt wird? Du und andere könnt dann in Häusern etc. sowohl den Beschreibungstext als auch den Kärtchentext sehen."
                               : "versteckt wird? Dann könnt du und andere sie in Häusern etc. nicht mehr sehen.")."`n
                                `n
                                Die Botschaft lautet:`n
                                \"`&".$arr_spinfo[0]."`r\"`n
                                `n
                                <a href='".$base_link."&op=card&save=ok' class='button'>Kärtchentext-Anzeige ändern</a>`n
                                `n");
                        addnav("",$base_link."&op=card&save=ok");
                    }
                } else {
                    output("`i`^Der Kärtchentext wurde nicht gefunden.`i`n`n");
                }
                
                addnav('Zum Inventar',$ret);
                
                break;
                
        default:
                /*output('`&Huch, was machst du denn hier? Sende bitte folgende Meldung via Anfrage ans Adminteam:`n
                        `n
                        `^fehlende op: '.$str_op.' in invhandler.php');*/
                output('`$Fehler! `^Entweder hast du gerade versucht, einen noch nicht eingelagerten Gegenstand auszulagern, oder du wolltest einen bereits eingelagerten Gegenstand nochmals
                        einlagern.');
                addnav('Zurück zum Inventar','invent.php');
                break;
}

page_footer();
?>

