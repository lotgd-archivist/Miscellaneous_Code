
<?php

// 10092004

// created by Lonny Luberts for http://www.pqcomp.com/logd, built on idea from quest's giftshop with all new code.
// this file needs customization before use and is designed to be added in many places if need be
// as different gift shops.
// search and replace (newgiftshop.php) with what you name the giftshop php file
// search and replace (gift 1)-(your gift) with your gifts - make sure you use the space inbetween gift & 1 etc...
// if you do an auto replace with your editor.
// be sure to edit the return nav
// please feel free to use and edit this file, any major upgrades or improvements should be
// mailed to logd@pqcomp.com for consideration as a permenant inclusion
// please do not remove the comments from this file.
// Version: 03212004
//
// changes to fit ext (GER) and translation by anpera
// added items with buffs
//
// Bugfix u. Kerker-Addon by Maris (Maraxxus@gmx.de)
// Nachrichten zusammen mit Geschenken versenden by talion (t@ssilo.de),
// Umgestellt auf neues Itemsystem

require_once "common.php";
checkday();
page_header("Geschenkeladen");

output("`c`b`&Geschenkeladen`0`b`c`n`n");

switch ( $_GET['op'] ) {

        case 'send':

                $gift = $_GET['op2'];

                if (isset($_POST['search']) || $_GET['search']>""){
                        if ($_GET['search']>"") $_POST['search']=$_GET['search'];
                        $search = str_create_search_string($_POST['search']);
                        $search = 'name LIKE "'.$search.'" AND ';
                        if ($_POST['search']=="weiblich") $search="sex=1 AND ";
                        if ($_POST['search']=="männlich") $search="sex=0 AND ";
                }else{
                        $search="";
                }
                $ppp=25; // Player Per Page to display
                if (!$_GET['limit']){
                        $page=0;
                }
                else{
                        $page=(int)$_GET['limit'];
                        addnav('Vorherige Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page-1).'&search='.$_POST['search']);
                }

                $limit="".($page*$ppp).",".($ppp+1);

                $sql = "SELECT login,name,level,sex,acctid FROM accounts
                               WHERE $search locked=0
                                     AND acctid<>".$session['user']['acctid']."
                                     AND level >
                                         CASE WHEN dragonkills = 0 THEN 1 ELSE 0 END
                                         AND uniqueid != '".$session['user']['uniqueid']."'
                                         ORDER BY login,level LIMIT $limit";

                $result = db_query($sql);

                $count = db_num_rows($result);

                if ($count>$ppp) addnav('Nächste Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page+1).'&search='.$_POST['search']);

                $link = 'newgiftshop.php?op=send&op2='.$gift;

                output('`rWem willst du das Geschenk schicken? Du hast außerdem die Möglichkeit, eine nette Botschaft beizulegen.`n
                        `n
                        <form action="'.$link.'" method="POST">
                        Nach Name suchen: <input name="search" value="'.$_POST['search'].'"> <input type="submit" class="button" value="Suchen"></form>',true);

                addnav('',$link);

                output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td><td>Versenden</td></tr>",true);
                for ($i=0;$i<$count;$i++){

                        $row = db_fetch_assoc($result);

                        output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
                        output($row['name']);
                        output("</td><td>",true);
                        output($row['level']);

                        $link = 'newgiftshop.php?op=send2&op2='.$gift.'&name='.$row['acctid'];

                        output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td>
                                        <td>
                                                [ <a href='".$link."'> Ohne </a> ]
                                                [ <a href='".$link."&send_msg=1'> Mit </a> ]
                                                Nachricht
                                        </td>
                                        </tr>",true);
                        addnav('',$link);
                        addnav('',$link.'&send_msg=1');
                }
                output("</table>",true);
                addnav("Zurück zum Laden","newgiftshop.php");

                break;

        case 'send2':

                $name = (int)$_GET['name'];

                // Daten des Empfängers laden
                $sq3 = "SELECT name,acctid FROM accounts WHERE acctid=".$name."";
                $result3=db_query($sq3);
                $row3 = db_fetch_assoc($result3);
                // end
                // Daten zum eigenen Char laden
                $sql = db_query('SELECT ab.has_addchars,ab.addchars_details,ab.xchar_category,ab.mount_category,aei.chat_selected_char AS chosenchar,d.name AS kname
                                        FROM account_extra_info aei
                                             LEFT JOIN accounts a USING(acctid)
                                             LEFT JOIN account_bios ab USING(acctid)
                                             LEFT JOIN disciples d ON d.master=aei.acctid
                                        WHERE acctid='.$session['user']['acctid'].'');
                $row_char = db_fetch_assoc($sql);
                $acs = unserialize($row_char['has_addchars']);
                $acbios = mb_unserialize($row_char['addchars_details']);
                $mount_det = $acbios['mount'];
                $disc_det = $acbios['disc'];
                $xchar_det = $acbios['xchar'];
                // end
                // Überprüfen, welche Bios vorhanden und aktiv sind:
                $bool_has_dbio = (($acs['has_disciplebio'] == 1 && $disc_det['discbio_active'] == 1 && isset($row_char['kname'])) ? true : false);
                $bool_has_mbio = (($acs['has_mountbio'] == 1 && $mount_det['mountbio_active'] == 1 && strlen($row_char['mount_category']) >= 3) ? true : false);
                $bool_has_xcbio = (($acs['has_xcharbio'] == 1 && $xchar_det['xcharbio_active'] == 1 && strlen($row_char['xchar_category']) >= 3) ? true : false);
                // Soll eine Nachricht hinzugefügt werden? Oder sind mehrere Chars vorhanden?
                if($_GET['send_msg'] || $bool_has_mbio || $bool_has_dbio || $bool_has_xcbio) {
                        $link = 'newgiftshop.php?op=send3&op2='.$_GET['op2'].'&name='.$name;
                        output("<form action='".$link."' method='POST'>");
                        addnav('',$link);
                        // wenn mehrere Chars vorhanden: User auswählen lassen:
                        if($bool_has_mbio || $bool_has_dbio || $bool_has_xcbio) {
                            output('`rIn wessen Namen soll das Geschenk an '.$row3['name'].' `rversandt werden?`n`n
                                    <input type="radio" name="char" value="hc" checked>Hauptchar: '.$session['user']['name'].'`r`n');
                            if($bool_has_dbio) {output('<input type="radio" name="char" value="disc">Knappe: '.$row_char['kname'].'`r`n');}
                            if($bool_has_mbio) {output('<input type="radio" name="char" value="mount">1. X-Char: '.$row_char['mount_category'].'`r`n');}
                            if($bool_has_xcbio) {output('<input type="radio" name="char" value="xchar">2. X-Char: '.$row_char['xchar_category'].'`r`n');}
                            output('`n');
                        }
                        if($_GET['send_msg']) {
                            output("`n`rDu kannst hier ".$row3['name']."`r eine nette Botschaft (max. 500 Zeichen) beilegen:`n`n
                                    <script language='JavaScript'>
                                    <!--
                                    function CountMax(wert,el)
                                    {
                                            var max = wert;
                                            var handler_counter = document.getElementById(el+'_jscounter');
                                            var handler = document.getElementById(el);
                                            var str = handler.value;
                                            wert = max - str.length;
                                            if (wert < 0)
                                            {
                                                    handler.value = str.substring(0,max);
                                                    wert = max-str.length;
                                                    handler_counter.value = wert;
                                            }
                                            else
                                            {
                                                    handler_counter.value = max - str.length;
                                            }
                                    }
                                    //-->
                                    </script>
                                    Noch <input type='text' id='message_jscounter' size='4' value='500' readonly> Zeichen übrig.`n
                                    <textarea name='message' id='message' class='input' cols='50' rows='4' onkeydown='CountMax(500,\"message\");' onkeyup='CountMax(500,\"message\");'></textarea>`n`n");

                        }
                        output("<input type='submit' class='button' value='Geschenk abschicken!'>`n
                                `n
                                <label><input type='checkbox' name='savecard' value='1' checked> `rDer Kärtchentext darf in Häusern etc. auch von anderen gelesen werden.</label></form>");
                // Wenn nur Hauptchar vorhanden und keine Nachricht gewünscht -> weiterleiten zu op=send3
                } else {
                    redirect('newgiftshop.php?op=send3&op2='.$_GET['op2'].'&name='.$name);
                    page_footer();
                    exit;
                }

                addnav("Zum Laden","newgiftshop.php");

                break;

        case 'send3':
                $name = (int)$_GET['name'];
                // Welcher Char als Absender?
                $str_char = (isset($_POST['char']) ? $_POST['char'] : 'hc');
                if($str_char == 'mount') {
                    $row_char = db_fetch_assoc(db_query("SELECT mount_category AS mname FROM account_bios WHERE acctid=".$session['user']['acctid']));
                    $str_msgfrom = $row_char['mname'];
                } elseif($str_char == 'disc') {
                    $row_char = db_fetch_assoc(db_query("SELECT name AS kname FROM disciples WHERE master=".$session['user']['acctid']));
                    $str_msgfrom = $row_char['kname'];
                } elseif($str_char == 'xchar') {
                    $row_char = db_fetch_assoc(db_query("SELECT xchar_category AS xcname FROM account_bios WHERE acctid=".$session['user']['acctid']));
                    $str_msgfrom = $row_char['xcname'];
                } else {
                    $str_msgfrom = $session['user']['name'];
                }
                // end
                $giftmsg = (strlen($_POST['message']) > 1 ? closetags(strip_tags($_POST['message']),'`i`c`b') : '');

                $sq3 = "SELECT name,acctid FROM accounts WHERE acctid=".$name."";
                $result3=db_query($sq3);
                $row3 = db_fetch_assoc($result3);
                
                $gift = item_get_tpl ( ' tpl_id="'.$_GET['op2'].'"' );

                // Platzhalter in den Beschreibungen, die in Geschenkitems verwendet werden können
                // Wenn Verwendung in Geschenkehook: global nicht vergessen!
                $arr_placeholder = array('{name}'=>$str_msgfrom,
                                         '{shortname}'=>$str_msgfrom,
                                         '{date}'=>getgamedate(),
                                         '{recipient_name}'=>$row3['name'],
                                         '{gift_name}'=>$gift['tpl_name']
                                         );
                $arr_search = array_keys($arr_placeholder);
                $arr_rpl = array_values($arr_placeholder);
                $gift['tpl_description'] = str_replace($arr_search,$arr_rpl,$gift['tpl_description']);

                $item_hook_info['mailmsg'] = '';
                $item_hook_info['failmsg'] = '';
                $item_hook_info['effect'] = '';
                $item_hook_info['acctid'] = $name;
                $item_hook_info['rec_name'] = $row3['name'];
                $item_hook_info['rec_char'] = $_POST['char'];
                $item_hook_info['check'] = 0;
                $item_hook_info['link'] = 'newgiftshop.php?op=send3&op2='.$_GET['op2'].'&name='.$name;

                if ( $gift ['gift_hook'] != '' ) {

                        item_load_hook ( $gift ['gift_hook'] , 'gift' , $gift );

                }
                if(!$item_hook_info['hookstop']) {

                        $item_hook_info['effect'] = $gift['tpl_description'];

                        $session['user']['gold'] -= $gift['tpl_gold'];
                        $session['user']['gems'] -= $gift['tpl_gems'];

                        $gift['tpl_gold'] = 1;
                        $gift['tpl_gems'] = 0;
                        
                        if($_POST['savecard'] == 1) {$gift['tpl_special_info'] = $giftmsg.'-||-0';}

                        $int_itemid = item_add ( $item_hook_info['acctid'] , 0 , $gift );
                }

                if($item_hook_info['check'] != 1) {

                        $item_hook_info['mailmsg'] .= $str_msgfrom;

                        $item_hook_info['mailmsg'] .= '`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6'
                                                                                        . $gift['tpl_name'] . '`7 aus dem Geschenkeladen.`n'
                                                                                        . $item_hook_info['effect'];

                        if(strlen($giftmsg) >= 4) {
                            $item_hook_info['mailmsg'] .= '`7`n`nAls du die Verpackung näher betrachtest, fällt dir eine handgeschriebene Botschaft auf:`n';
                            if(isset($int_itemid) && $_POST['savecard'] == 1) {
                                $item_hook_info['mailmsg'] .= $giftmsg.'`7`n`n`i`SÜber dein Inventar kannst du einstellen, ob der Kärtchentext auch für andere in Häusern etc. angezeigt werden soll.`i';
                            } else {
                                $item_hook_info['mailmsg'] .= $giftmsg;
                            }
                        }

                        systemmail($name,"`2Geschenk erhalten!`0",$item_hook_info['mailmsg']);

                        debuglog('Hat Geschenk '.$gift['tpl_name'].' versendet an: ',$name);

                        output('`rDein '.$gift['tpl_name'].'`r wurde als Geschenk verschickt!');

                        if (e_rand(1,3)==2){
                                output(' Bei der Wahl des Geschenks und dem liebevollen Verpacken vergisst du die Zeit und vertrödelst einen Waldkampf.');
                                $session['user']['turns']--;
                        }
                }

                addnav("Zum Laden","newgiftshop.php");

                break;
                
        default:
                if($session['user']['turns']>0) {
                        // Für Beschreibungen js-Code einbinden
                        output('<script type="text/javascript" language="JavaScript" src="templates/plumi.js"></script>');
                        // end
                        output('`rDu betrittst den Geschenkeladen und siehst eine Menge einzigartiger Gegenstände.`n
                                Ein'.($session['user']['sex']?' junger Mann':'e junge Frau').' steht hinter der Ladentheke und lächelt dich sanft an.`n
                                Ein Schild an der Wand verspricht "`iGeschenkverpackung und Lieferung frei.`i"`n`n<ul>',true);

                        // Itemliste aller Geschenke
                        $res = item_tpl_list_get ( ' giftshop>0 ' , ' ORDER BY tpl_class ASC, tpl_gold ASC, tpl_gems ASC ' );
                        $int_class = 0;
                        while ( $g = db_fetch_assoc ( $res ) ) {

                                //if( $session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems'] ) {

                                        if($int_class != $g['tpl_class']) {
                                            $g_class = db_fetch_assoc(db_query("SELECT id,class_name FROM items_classes WHERE id=".$g['tpl_class']));
                                            addnav($g_class['class_name']);
                                            $int_class = $g_class['id'];
                                            output('</ul>`n'.$g_class['class_name'].':<ul>');
                                        }
                                        // PluMi und Beschr.text setzen:
                                        $arr_shownodesc = array('gesmlskt','aufmerksmk','abartigk','glckskeks'); # array mit Geschenke-IDs, deren Beschr. nicht angezeigt werden sollen
                                        if(strlen($g['tpl_description']) > 1 && !in_array($g['tpl_id'],$arr_shownodesc)) {
                                            $arr_placeholder = array('{name}'=>'~Name des Absenders~',
                                                                     '{shortname}'=>'~Name des Absenders~',
                                                                     '{date}'=>getgamedate(),
                                                                     '{recipient_name}'=>'~Name des Empfängers~',
                                                                     '{gift_name}'=>$g['tpl_name']
                                                                     );
                                            $arr_search = array_keys($arr_placeholder);
                                            $arr_rpl = array_values($arr_placeholder);
                                            $str_desc = '<div id="'.$g['tpl_id'].'" style="width: 500px; padding-top: 4px; display: none;">`i`&'.str_replace($arr_search,$arr_rpl,$g['tpl_description']).'`i`n`n</div>';
                                            $str_plumi = '<a href="#"'.set_plumi_onclick($g['tpl_id']).' style="vertical-align: middle;">'.set_plumi_img('desc').'</a>&nbsp;&nbsp;';
                                        } else {
                                            $str_plumi = '';
                                            $str_desc = '';
                                        }
                                        // end
                                        $link = 'newgiftshop.php?op=send&op2=' . $g['tpl_id'];
                                        output( '<li>' . $str_plumi .
                                                        ($session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems']
                                                                ? create_lnk($g['tpl_name'], $link, true, true)
                                                                : '`i'.$g['tpl_name'].'`i') .
                                                                ($g['tpl_gold'] > 0 ? '`r ( '. $g['tpl_gold'] . ' Gold ) ' : '').
                                                                ($g['tpl_gems'] > 0 ? '`r ( '. $g['tpl_gems'] . ' Edelsteine ) ' : '').
                                                        $str_desc
                                                        , true );
                                //}

                        }
                        // END Geschenkliste

                        output('</ul>',true);

                        addnav('Zurück');


                }
                else {        // Keine Runden mehr

                        output('`rDer Geschenkeladen hat leider schon geschlossen. Das kommt dir auch ganz gelegen, denn nach den letzten anstrengenden Stunden bist du viel zu müde,
                                um jetzt noch Geschenke kaufen zu gehen.');


                }

                $show_invent = true;

                addnav('#?Zum Garten','gardens.php');

                break;        // END default


}

page_footer();
?>

