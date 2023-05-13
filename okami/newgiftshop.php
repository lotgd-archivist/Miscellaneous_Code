
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
// mailed to logd [-[at]-] pqcomp.com for consideration as a permenant inclusion
// please do not remove the comments from this file.
// Version: 03212004
//
// changes to fit ext (GER) and translation by anpera
// added items with buffs
//
// Bugfix u. Kerker-Addon by Maris (Maraxxus [-[at]-] gmx.de)
// Nachrichten zusammen mit Geschenken versenden by talion (t [-[at]-] ssilo.de),
// Umgestellt auf neues Itemsystem

require_once "common.php";
checkday();
page_header("Geschenkeladen");

output("`c`b`IGeschenkeladen`0`b`c`n");

switch ( $_GET['op'] )
{
    case 'send':
    {
        $gift = $_GET['op2'];
        if (isset($_POST['search']) || $_GET['search']>"")
        {
            if ($_GET['search']>"") $_POST['search']=urldecode($_GET['search']);
            $search = str_create_search_string($_POST['search']);
            $search = 'name LIKE "'.$search.'" AND ';
            if ($_POST['search']=="weiblich") $search="sex=1 AND ";
            if ($_POST['search']=="männlich") $search="sex=0 AND ";
        }
        else
        {
            $search="";
        }
        $ppp=25; // Player Per Page to display
        if (!$_GET['limit'])
        {
            $page=0;
        }
        else
        {
            $page=(int)$_GET['limit'];
            addnav('Vorherige Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page-1).'&search='.urlencode($_POST['search']));
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT login,name,level,sex,acctid
            FROM accounts
            WHERE $search
            locked=0
            AND acctid<>".$session['user']['acctid']."
            AND charm>-1
            ORDER BY (login='".db_real_escape_string($_POST['search'])."') DESC, acctid=".$session['user']['marriedto']." DESC,login,level
            LIMIT $limit";
        $result = db_query($sql);
        $count = db_num_rows($result);
        if ($count>$ppp) addnav('Nächste Seite','newgiftshop.php?op=send&op2='.$gift.'&limit='.($page+1).'&search='.$_POST['search']);
        $link = 'newgiftshop.php?op=send&op2='.$gift;
        addnav('',$link);
        $item=item_get_tpl('tpl_id="'.$gift.'"');
        $arr_placeholder = array('{name}'=>$session['user']['name'],
                                '{shortname}'=>$session['user']['login'],
                                '{date}'=>getgamedate(),
                                '{recipient_name}'=>'(Empfänger)',
                                '{gift_name}'=>$gift['tpl_name']
                                );
        $arr_search = array_keys($arr_placeholder);
        $arr_rpl = array_values($arr_placeholder);
        $item['tpl_description'] = words_by_sex(str_replace($arr_search,$arr_rpl,$item['tpl_description']));
        $str_out.='`b'.$item['tpl_name'].'`0`b
        `n'.$item['tpl_description'].'
        `n
        `n`0Wem willst du das Geschenk schicken? Du hast außerdem die Möglichkeit, eine nette Botschaft beizulegen.
        `n`n<form action="'.htmlentities($link).'" method="POST">
        Nach Name suchen: <input name="search" value="'.stripslashes($_POST['search']).'">
        `n<input type="submit" class="button" value="Suchen">
        </form>
        `n<table cellpadding="3" cellspacing="0" border="0">
        <tr class="trhead">
        <th>Name</th>
        <th>Level</th>
        <th>Geschlecht</th>
        <th>Versenden</th>
        </tr>';
        for ($i=0;$i<$count;$i++)
        {
            $row = db_fetch_assoc($result);
            $link = 'newgiftshop.php?op=send2&op2='.$gift.'&name='.$row['acctid'];
            $str_out.='<tr class="'.($i%2?'trlight':'trdark').'">
            <td>'.$row['name'].'`0</td>
            <td>'.$row['level'].'</td>
            <td align="center"><img src="images/'.($row['sex']?'female':'male').'.gif"></td>
            <td>
                [ '.create_lnk('Ohne',$link).' ]
                [ '.create_lnk('Mit',$link.'&send_msg=1').' ]
                Nachricht
            </td>
            </tr>';
        }
        output($str_out.'</table>');
        addnav('Zurück zum Laden','newgiftshop.php');
        break;
    }

    case 'send2':
    {
        $name = (int)$_GET['name'];
        $giftmsg = $_POST['message'];
        $sq3 = "SELECT name,acctid,sex FROM accounts WHERE acctid=".$name."";
        $result3=db_query($sq3);
        $row3 = db_fetch_assoc($result3);
        if($_GET['send_msg'])
        {
            $link = 'newgiftshop.php?op=send2&op2='.$_GET['op2'].'&name='.$name;
            addnav('',$link);
            output("`0Du kannst hier ".$row3['name']."`0 eine nette Botschaft beilegen:`n`n");
            $form = array('Vorschau:,preview,message', 'message'=>'Deine Botschaft:,textarea,50,3');
            output('<form action="'.htmlentities($link).'" method="POST">');
            showform($form,$persons,false,'Geschenk abschicken');
            //500-Zeichen-Begrenzung der Botschaft entfällt durch Benutzung von textarea
            $check = 1;
        }
        if ($check!=1)
        {
            $gift = item_get_tpl ( ' tpl_id="'.$_GET['op2'].'"' );
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
            $gift['tpl_description'] = words_by_sex(str_replace($arr_search,$arr_rpl,$gift['tpl_description']));

            $item_hook_info ['mailmsg'] = '';
            $item_hook_info ['failmsg'] = '';
            $item_hook_info ['effect'] = '';
            $item_hook_info ['acctid'] = $name;
            $item_hook_info ['rec_name'] = $row3['name'];
            $item_hook_info ['rec_sex'] = $row3['sex'];
            $item_hook_info ['check'] = 0;

            if ( $gift ['gift_hook'] != '' )
            {
                item_load_hook ( $gift ['gift_hook'] , 'gift' , $gift );
            }

            if(!$item_hook_info['hookstop'])
            {
                $item_hook_info['effect'] = $gift['tpl_description'];
                $session['user']['gold'] -= $gift['tpl_gold'];
                $session['user']['gems'] -= $gift['tpl_gems'];
                $gift['tpl_gold'] = 1;
                $gift['tpl_gems'] = 0;
                item_add ( $item_hook_info['acctid'] , 0 , $gift );
            }

            if($item_hook_info['check'] != 1)
            {
                $item_hook_info ['mailmsg'] .= $session['user']['name'];
                $item_hook_info ['mailmsg'] .= '`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6'
                . $gift['tpl_name'] . '`7 aus dem Geschenkeladen.`n'
                . $item_hook_info ['effect'];
                if($giftmsg != '')
                {
                    $item_hook_info ['mailmsg'] .= '`0`n`nAls du die Verpackung näher betrachtest, fällt dir eine handgeschriebene Botschaft auf:`n'.$giftmsg.'`7';
                }
                systemmail($name,"`2Geschenk erhalten!`2",$item_hook_info ['mailmsg']);
                debuglog('Hat Geschenk '.$gift['tpl_name'].' versendet an: ',$name);
                output('`0Dein '.$gift['tpl_name'].'`0 wurde als Geschenk verschickt!');
                if (e_rand(1,3)==2){
                    output(' `0Bei der Wahl des Geschenks und dem liebevollen Verpacken vergisst du die Zeit und vertrödelst einen Waldkampf.');
                    $session['user']['turns']--;
                }
            }
        }
        addnav("Zum Laden","newgiftshop.php");
        break;
    }

    default:
    {
        if($session['user']['turns']>0)
        {
            output('`0Etwas abgelegen im Garten befindet sich ein einfaches, schlichtes Ziegelhaus, dessen Innenraum vollkommen mit Holzregalen ausgefüllt ist. Auf den Brettern liegen unzählige Geschenke nach Preisklassen sortiert. Hier findet bestimmt jeder ein passendes Present, denn von Abartigkeiten bis hin zu kostbarem Schmuck und hilfreichen Utensilien ist hier wirklich alles vertreten.`n');
            output('Ein'.($session['user']['sex']?' junger Mann':'e junge Frau').' steht hinter der Ladentheke und beoachtet aufmerksam, was du im Laden treibst und dir ansiehst. Sobald du aber kurz zu '.($session['user']['sex']?'ihm':'ihr').' schaust, bekommst du ein geschäftsmäßig freundliches Lächeln.`n');
            output('Ein Schild an der Wand verspricht "`iGeschenkverpackung und Lieferung frei.`i"`n`n`n`n<ul>',true);
            addnav('Geschenke');
            // Itemliste aller Geschenke
            $res = item_tpl_list_get ( ' giftshop>0 ' , ' ORDER BY tpl_gold ASC, tpl_gems ASC ' );
            while ( $g = db_fetch_assoc ( $res ) )
            {
                //if( $session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems'] ) {
                    $link = 'newgiftshop.php?op=send&op2=' . $g['tpl_id'];
                    output( '<li>' .
                            ($session['user']['gold'] >= $g['tpl_gold'] && $session['user']['gems'] >= $g['tpl_gems']
                                ? create_lnk($g['tpl_name'].'`0', $link, true, true)
                                : '`i'.$g['tpl_name'].'`0`i') .
                                ($g['tpl_gold'] > 0 ? '`0 ( '. $g['tpl_gold'] . ' Gold ) ' : '').
                                ($g['tpl_gems'] > 0 ? '`0 ( '. $g['tpl_gems'] . ' Edelsteine ) ' : '').
                            ''
                            , true );
                //}
            }
            // END Geschenkliste
            output('</ul>',true);
            addnav('Sonstiges');
            if (getsetting("activategamedate","0")>0){
                $cakecost=$session['user']['level']*15;
                //addnav("Torte werfen ($cakecost Gold)","newgiftshop.php?op=cake");
            }
            addnav('Zurück');
        }
        else    // Keine Runden mehr
        {
            output('`0Der Geschenkeladen hat jetzt leider schon geschlossen.');
        }
        $show_invent = true;
        addnav('G?Zum Garten','gardens.php');
        addnav('D?Zum Dorf','village.php');
        break;    // END default
    }
}

page_footer();
?>

