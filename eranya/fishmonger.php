
<?php
require_once 'common.php';
$show_invent = true;
// Variablen
define('MONGERCOLORHEAD','`Ô');
define('MONGERCOLORTEXT','`í');
define('MONGERCOLORTALK','`7');
$trader = 'Elric';
$tout = '';
$bool_sendok = false;
// end
// Funktionen
function searchlist($gift)
{
        global $session,$_GET,$_POST;

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
        addnav('Weiter');
        if (!$_GET['limit']){
                $page=0;
        }
        else{
                $page=(int)$_GET['limit'];
                addnav('Vorherige Seite','fishmonger.php?op=buy&what='.$gift.'&limit='.($page-1).'&search='.$_POST['search']);
        }

        $limit="".($page*$ppp).",".($ppp+1);

        $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session['user']['acctid']." AND uniqueid<>'".$session['user']['uniqueid']."' ORDER BY login,level LIMIT $limit";
        $result = db_query($sql);

        $count = db_num_rows($result);

        if ($count>$ppp) addnav('Nächste Seite','fishmonger.php?op=buy&what='.$gift.'&limit='.($page+1).'&search='.$_POST['search']);

        $link = 'fishmonger.php?op=buy&what='.$gift;

        output('`+Wem willst du das Geschenk schicken? Du hast außerdem die Möglichkeit, eine nette Botschaft beizulegen.`n`n');
        output('<form action="'.$link.'" method="POST">
                Nach Name suchen: <input name="search" value="'.$_POST['search'].'">`n`n',true);
        output('<input type="submit" class="button" value="Suchen"></form>',true);

        addnav('',$link);

        output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Versenden</td></tr>",true);
        for ($i=0;$i<$count;$i++){

                $row = db_fetch_assoc($result);

                output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
                output($row['name']);
                output("</td><td>",true);
                output($row['level']);

                $link = 'fishmonger.php?op=buy&act=send&what='.$gift.'&name='.$row['acctid'];

                output("</td><td>
                                     [ <a href='".$link."'> Ohne </a> ]
                                     [ <a href='".$link."&send_msg=1'> Mit </a> ] Nachricht
                             </td>
                             </tr>",true);
                addnav('',$link);
                addnav('',$link.'&send_msg=1');
        }
        output("</table>",true);
}

function sendpresent($to)
{
        global $session,$_POST,$_GET,$bool_sendok;

        $name = (int)$to;

        $giftmsg = '`+'.$_POST['message'];

        $sq3 = "SELECT name,acctid FROM accounts WHERE acctid=".$name."";
        $result3=db_query($sq3);
        $row3 = db_fetch_assoc($result3);

        if($_GET['send_msg'])
        {
                $link = 'fishmonger.php?op=buy&act=send&what='.$_GET['what'].'&name='.$name;

                addnav('',$link);

                output("`+Du kannst ".$row3['name']."`+ eine kleine Nachricht (max. 500 Zeichen) beilegen:`n`n
                        <form action='".$link."' method='post'>
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
                        `+Noch <input type='text' id='message_jscounter' size='4' value='500' readonly> Zeichen übrig.`n
                        <textarea name='message' id='message' class='input' cols='50' rows='4' onkeydown='CountMax(500,\"message\");' onkeyup='CountMax(500,\"message\");'></textarea>`n`n
                        <input type='submit' class='button' value='Geschenk abschicken!'>`n`n
                        <label><input type='checkbox' name='savecard' value='1' checked> `+Der Kärtchentext darf in Häusern etc. auch von anderen gelesen werden.</label></form>",true);

                $check = 1;
        }

        if ($check!=1)
        {
                $gift = item_get_tpl ( ' tpl_id="'.$_GET['what'].'"' );

                // Platzhalter in den Beschreibungen, die in Geschenkitems verwendet werden können
                // Wenn Verwendung in Geschenkehook: global nicht vergessen!
                $arr_placeholder = array('{name}'=>$session['user']['name'],
                                                                '{shortname}'=>$session['user']['login'],
                                                                '{date}'=>getgamedate(),
                                                                '{recipient_name}'=>$row3['name'],
                                                                '{gift_name}'=>$gift['tpl_name']
                                                                );
                $arr_search = array_keys($arr_placeholder);
                $arr_rpl = array_values($arr_placeholder);
                $gift['tpl_description'] = str_replace($arr_search,$arr_rpl,$gift['tpl_description']);

                $item_hook_info ['mailmsg'] = '';
                $item_hook_info ['failmsg'] = '';
                $item_hook_info ['effect'] = '';
                $item_hook_info ['acctid'] = $name;
                $item_hook_info ['rec_name'] = $row3['name'];
                $item_hook_info ['check'] = 0;

                if ( $gift ['gift_hook'] != '' )
                {
                        item_load_hook ( $gift ['gift_hook'] , 'gift' , $gift );
                }

                if(!$item_hook_info['hookstop'])
                {
                        $item_hook_info['effect'] = $gift['tpl_description'];

                        //$session['user']['gold'] -= $gift['tpl_gold'];
                        //$session['user']['gems'] -= $gift['tpl_gems'];

                        $gift['tpl_gold'] = 1;
                        $gift['tpl_gems'] = 0;
                        
                        if($_POST['savecard'] == 1) {$gift['tpl_special_info'] = $giftmsg.'||0';}

                        $int_itemid = item_add ( $item_hook_info['acctid'] , 0 , $gift );
                }
                if($item_hook_info['check'] != 1)
                {
                        $item_hook_info['mailmsg'] .= $session['user']['name'];
                        $item_hook_info['mailmsg'] .= '`& hat dir ein Geschenk geschickt. Du öffnest es. Es ist eine `+'
                                                                                        . $gift['tpl_name'] . '`&.`n`7"'
                                                                                        . $item_hook_info['effect'] . '"';
                        if(strlen($giftmsg) >= 4) {
                            $item_hook_info ['mailmsg'] .= '`&`n`nAls du die Verpackung näher betrachtest, fällt dir eine handgeschriebene Botschaft auf:`n'.$giftmsg.'`&';
                            if(isset($int_itemid) && $_POST['savecard'] == 1) {
                                $item_hook_info['mailmsg'] .= '`n`n`i`SÜber dein Inventar kannst du einstellen, ob der Kärtchentext auch für andere in Häusern etc. angezeigt werden soll.`i';
                            }
                        }
                        systemmail($name,"`+Geschenk erhalten!",'`&'.$item_hook_info['mailmsg']);

                        debuglog('Hat Geschenk '.$gift['tpl_name'].'`0 versendet an: ',$name);

                        output('`+Deine '.$gift['tpl_name'].'`+ wurde als Geschenk verschickt!');
                        $bool_sendok = true;
                }
        }
}
// end
page_header('Fischhändler');
switch($_GET['op'])
{
        case '':
                if(!empty($_SESSION['to'])) {unset($_SESSION['to']);}
                $tout = MONGERCOLORHEAD."`c`bDer Fischhändler`b`c`n".
                        MONGERCOLORTEXT."Unweit von der Kneipe entfernt zieht ein übergroßes Schild deinen Blick auf sich. ".MONGERCOLORHEAD."'".$trader."s Fische
                        und mehr' ".MONGERCOLORTEXT."steht darauf geschrieben - und zeigt dir somit, dass in dem unscheinbaren Lädchen darunter ein
                        Fischhändler seinen Sitz hat. Die Tür steht offen, also beschließt du, einen Blick zu riskieren - und staunst nicht schlecht,
                        als du das Angebot in den Regalen erspähst: Von Halsketten über Buddelschiffe bis hin zu kleinen Giftphiolen scheint alles
                        dabei zu sein. Einen Moment lang scheint es, als wärest du allein in dem Raum, doch dann eilt ".$trader.MONGERCOLORTEXT." aus
                        einem Nebenzimmer herbei und nimmt seinen Platz hinter dem Tresen ein. ".MONGERCOLORTALK."\"Moin, mien ".
                        ($session['user']['sex'] ? 'Deern' : 'Jung')."\"".MONGERCOLORTEXT.", begrüßt er dich. ".MONGERCOLORTALK."\"Willkommen in
                        meinem Laden. Was darf's sein?\"`n
                        `n";
                addnav('Verkauf');
                addnav('v?Etwas verkaufen','fishmonger.php?op=sell');
                // Wieviel von was?
                $crab_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_crab" ');
                $shell_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_shell" ');
                $jllfsh_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_jllfsh" ');
                $pearl_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_pearl" ');
                $bottle_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="emptybttle" ');
                $wood_count = item_count(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_wood" ');
                // end
                addnav('Items');
                if($pearl_count >= 10)
                {
                        $tout .= MONGERCOLORHEAD."<a href='fishmonger.php?op=buy&what=prlncklace'>`&Perlenkette verschenken (10 Perlen)</a>`n";
                        addnav('','fishmonger.php?op=buy&what=prlncklace');
                        addnav('Perlenkette verschenken','fishmonger.php?op=buy&what=prlncklace');
                }
                else {$tout .= MONGERCOLORHEAD."`iPerlenkette verschenken (10 Perlen)`i`n";}
                if($jllfsh_count >= 5)
                {
                        $tout .= MONGERCOLORHEAD."<a href='fishmonger.php?op=buy&what=poison'>`&Truhengift erwerben (5 Quallen)</a>`n";
                        addnav('','fishmonger.php?op=buy&what=poison');
                        addnav('Truhengift erwerben','fishmonger.php?op=buy&what=poison');
                }
                else {$tout .= MONGERCOLORHEAD."`iTruhengift erwerben (5 Quallen)`i`n";}
                if($bottle_count >= 1 && $wood_count >= 1)
                {
                        $tout .= MONGERCOLORHEAD."<a href='fishmonger.php?op=buy&what=ship_bttle'>`&Buddelschiff anfertigen lassen (1 leere Flasche & 1x Treibholz)</a>`n";
                        addnav('','fishmonger.php?op=buy&what=ship_bttle');
                        addnav('Buddelschiff anfertigen lassen','fishmonger.php?op=buy&what=ship_bttle');
                }
                else {$tout .= MONGERCOLORHEAD."`iBuddelschiff anfertigen lassen (1 leere Flasche & 1x Treibholz)`i`n";}
                if($shell_count >= 5 && $crab_count >= 5)
                {
                        $tout .= MONGERCOLORHEAD."<a href='fishmonger.php?op=buy&what=seafd_soup'>`&Süppchen kochen (5 Muscheln & 5 Krabben)</a>`n";
                        addnav('','fishmonger.php?op=buy&what=seafd_soup');
                        addnav('Süppchen kochen','fishmonger.php?op=buy&what=seafd_soup');
                }
                else {$tout .= MONGERCOLORHEAD."`iSüppchen kochen (5 Muscheln & 5 Krabben)`i`n";}
                addnav('Zurück');
        break;
        case 'buy':
                switch($_GET['what'])
                {
                        // Perlenkette - Geschenk
                        case 'prlncklace':
                                switch($_GET['act'])
                                {
                                        case '':
                                                searchlist($_GET['what']);
                                        break;
                                        case 'send':
                                                sendpresent($_GET['name']);
                                                if($bool_sendok == true)
                                                {
                                                        item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_pearl" ',10);
                                                        $bool_sendok = false;
                                                }
                                        break;
                                }
                        break;
                        // Truhengift - Item
                        case 'poison':
                                $tout = MONGERCOLORTEXT."Kaum ausgesprochen, schnappt sich ".$trader.MONGERCOLORTEXT." auch schon deine Quallen und verschwindet damit
                                        in den Nebenraum. Die Geräusche, die anschließend zu dir hinüber schallen, erinnern dich an das saugende
                                        Geräusch von durchtränktem Matsch... Doch schon wenig später taucht ".$trader.MONGERCOLORTEXT." wieder auf -
                                        mitsamt dem Truhengift, das du bestellt hast.`n`n";
                                item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_jllfsh" ',5);
                                item_add($session['user']['acctid'],'gftph');
                        break;
                        // Perlenkette - Geschenk
                        case 'ship_bttle':
                                switch($_GET['act'])
                                {
                                        case '':
                                                searchlist($_GET['what']);
                                        break;
                                        case 'send':
                                                sendpresent($_GET['name']);
                                                if($bool_sendok == true)
                                                {
                                                        item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="emptybttle" ',1);
                                                        item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_wood" ',1);
                                                        $bool_sendok = false;
                                                }
                                        break;
                                }
                        break;
                        // Suppe - Geschenk oder Item
                        case 'seafd_soup':
                                if($_GET['to'] == 1 || $_GET['to'] == 2) {$_SESSION['to'] = $_GET['to'];}
                                if($_SESSION['to'] == 1)
                                {
                                        $tout = MONGERCOLORTEXT."Du nimmst ".$trader.MONGERCOLORTEXT." die Suppe ab und füllst sie um, sodass du sie
                                                in deinem Beutel verstauen kannst. Mmh, lecker! Die wirst du dir nachher so richtig schmecken lassen.
                                                `n`n";
                                        item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_shell" ',5);
                                        item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_crab" ',5);
                                        item_add($session['user']['acctid'],'seafd_soup');
                                        unset($_SESSION['to']);
                                }
                                elseif($_SESSION['to'] == 2)
                                {
                                        switch($_GET['act'])
                                        {
                                                case '':
                                                        searchlist($_GET['what']);
                                                break;
                                                case 'send':
                                                        sendpresent($_GET['name']);
                                                        if($bool_sendok == true)
                                                        {
                                                                item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_shell" ',5);
                                                                item_delete(' owner='.$session['user']['acctid'].' AND tpl_id="fsh_crab" ',5);
                                                                $bool_sendok = false;
                                                                unset($_SESSION['to']);
                                                        }
                                                break;
                                        }
                                }
                                else
                                {
                                        $tout = MONGERCOLORTEXT."Sogleich schnappt sich ".$trader.MONGERCOLORTEXT." deine Krabben und Muscheln und trägt
                                                sie in den Raum nebenan. Dann siehst und hörst du eine ganze Weile lang nichts.., bis der Händler
                                                schließlich wieder zurückkehrt - mitsamt einem Topf voll leckerer Meeresfrüchte-Suppe. Mmh.. wie die
                                                duftet..! Doch bevor du die Finger nach dem Eisenbehältnis ausstrecken kannst, hält ".$trader.MONGERCOLORTEXT."
                                                dich zurück und merkt an, dass er die Suppe auch an jemanden deiner Wahl ausliefern kann. Möchtest du
                                                sie verschenken - oder doch lieber selbst essen?`n`n";
                                        addnav('Suppe');
                                        addnav('Selbst essen','fishmonger.php?op=buy&what=seafd_soup&to=1');
                                        addnav('Verschenken','fishmonger.php?op=buy&what=seafd_soup&to=2');
                                }
                        break;
                        // Debug
                        default:
                                $tout = "`&Nanu! Was machst du denn hier? Hm, anscheinend ist etwas schief gegangen. Schicke bitte folgenden Satz via Anfrage ans
                                         Admin-Team:`n
                                         `n
                                         `^what: ".$_GET['what']." in fishmonger nicht vorhanden.";
                        break;
                }
                addnav('Zurück');
                addnav('Ü?Zur Übersicht','fishmonger.php');
        break;
        case 'sell':
                output(MONGERCOLORTEXT."Mit dem Blick eines Profis begutachtet ".$trader.MONGERCOLORTEXT." deinen Beutelinhalt und sucht sich die Dinge
                       heraus, die für ihn infrage kommen.`n`n");
                //item_show_invent(' owner='.$session['user']['acctid'].' AND (v_ship=2 OR v_ship=3 OR vendor=2 OR vendor=3) AND tpl_class=26 ', false, 2, 1, 1);
                item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_MULTI | ITEM_INVENT_HEAD_SHOP_SELL | ITEM_INVENT_HEAD_SEARCH);
                item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND (v_ship=2 OR v_ship=3 OR vendor=2 OR vendor=3) AND tpl_class=26 ',20), MONGERCOLORTEXT.'Überraschenderweise besitzt du jedoch nichts, das '.$trader.MONGERCOLORTEXT.' interessieren würde!');
                addnav('Zurück');
                addnav('Ü?Zur Übersicht','fishmonger.php');
        break;
        case 'sell_do':
                $show_invent = false;
                $arr_items = array();

                // Multiselect
                if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
                        $str_ids = implode(',',$_POST['ids']);
                        $res_items = item_list_get(' id IN ('.addslashes(stripslashes($str_ids)).') AND owner='.$session['user']['acctid'].' AND (it.v_ship=2 OR it.v_ship=3 OR it.vendor = 2 OR it.vendor = 3) ','',true,'name,id,it.tpl_id,gold,gems');

                        if(db_num_rows($res_items) == 0) {
                                redirect('fishmonger.php?op=sell');
                        }

                        $arr_items = db_create_list($res_items);

                }
                else {

                        if(empty($_GET['id']) || ($arr_tmp = item_get(' id="'.(int)$_GET['id'].'" ')) === false) {
                                redirect('fishmonger.php?op=sell');
                        }

                        $arr_items = array($arr_tmp);
                }

                $goldprice_ges = 0;
                $gemsprice_ges = 0;

                foreach ($arr_items as $item) {

                        $item_hook_info['goldprice'] = round($item['gold'] * $_GET['gold_r']);

                        // 10%iger Händlerbones (Preis modifizieren)
                                /*if ($p_job==6)
                                {
                        $item_hook_info['goldprice'] *= 1.1;
                    }*/

                        $item_hook_info['gemsprice'] = round($item['gems'] * $_GET['gems_r']);

                        $item_hook_info['dealer'] = 'fishmonger';
                        $item_hook_info['do'] = 'sell';
                        item_load_hook($item['trade_hook'],'trade',$item);

                        $goldprice_ges += $item_hook_info['goldprice'];
                        $gemsprice_ges += $item_hook_info['gemsprice'];

                        // Wenn Gebraucht-Ankauf bei Wanderhändler möglich
                        if($item['vendor'] == 1 || $item['vendor'] == 3 || $item['v_ship'] == 1 || $item['v_ship'] == 3) {
                                // Der Wanderhändler kann auch nicht unendlich viel aufnehmen, irgendwann muss er aussortieren!
                                // Doppelt Vorhandenes kommt weg
                                item_delete(' tpl_id="'.$item['tpl_id'].'" AND owner='.ITEM_OWNER_VENDOR);

                                item_set(' id='.$item['id'],array('deposit1'=>0,'deposit2'=>0,'gold'=>$item_hook_info['goldprice'],'gems'=>$item_hook_info['gemsprice'],'owner'=>ITEM_OWNER_VENDOR) );
                        }
                        else {        // Neuware
                                item_delete(' id='.$item['id']);
                        }
                }

                $session['user']['gold'] += $goldprice_ges;
                $session['user']['gems'] += $gemsprice_ges;
                
                $tout = MONGERCOLORTEXT."Mit einem Nicken zählt ".$trader.MONGERCOLORTEXT." dir `^".$goldprice_ges." Gold
                        ".($gemsprice_ges?"und ".$gemsprice_ges." Edelsteine":"").MONGERCOLORTEXT." ab und bringt dann die Ware in den Nebenraum.`n`n";
                addnav('Mehr verkaufen','fishmonger.php?op=sell');
                addnav('Zurück');
                addnav('Ü?Zur Übersicht','fishmonger.php');
        break;
        // Debug
        default:
                $tout = "`&Nanu! Was machst du denn hier? Hm, anscheinend ist etwas schief gegangen. Schicke bitte folgenden Satz via Anfrage ans
                         Admin-Team:`n
                         `n
                         `^op: ".$_GET['op']." in fishmonger nicht vorhanden.";
                addnav('Zurück');
                addnav('Ü?Zur Übersicht','fishmonger.php');
        break;
}
addnav('H?Zum Hafen','harbor.php');
// Ausgabe & Abschluss
if(!empty($tout)) {output($tout,true);}
page_footer();
?>

