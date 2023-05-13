
<?php
/**
* motd.php: Stellt bewährte MoTD-Funktionalität zur Verfügung, erweitert
*            um Editfunktion, Autorangabe, Datumsanzeige, Archiv.
* @author LOGD-Core, modified and rewritten by talion <t [-[at]-] ssilo.de>
* @version DS-E V/2
*/

require_once('common.php');

define('MOTDGROUP_ALL',99);
$str_filename = basename(__FILE__);

$arr_groups = $access_control->user_get_sugroups();
$arr_groups[MOTDGROUP_ALL][1] = 'Alle Grottenolme';

popup_header(getsetting('townname','Atrahor').': Message of the Day (MoTD)',true);

$str_output = '';
$str_output  = '<center>[<a href="motd.php">MoTD-Index</a> | <a href="motd-coding.php?check=all">MoTC-Index</a>]</center><br />';
if ($access_control->su_check(access_control::SU_RIGHT_MOTD))
{
    $str_output .= '<center>[<a href="motd.php?op=edit">MoTD / Umfrage erstellen</a> | <a href="motd-coding.php?op=neu">MoTC erstellen</a>]</center><br />';
}

/**
 * Schreibt ein MOTD Item
 *
 * @param String $subject
 * @param String $body
 * @param int $group
 * @param string $date
 * @param string $author
 * @return string MOTDItem
 */
function motditem($subject,$body,$group=0,$date='',$author='')
{    
    global $arr_groups;
    $str_output = ('`b'.($author?$author:'').' '.$subject.' '.($group>0?'(Für '.$arr_groups[$group][1].'`0)':'').'`b`n');
    if ($date)
    {
        $str_output .= ('`#`i[ '.strftime('%A, %e. %B %Y, %H:%M',strtotime($date)).' ]`i`n');
    }
    motd_place_links($body);
    $str_output .= ('`3'.$body.'<hr />`0');
    return $str_output;
}

/**
 * Ersetzt BBCode Style url Tags mit einem echten Link
 *
 * @param String $str_text Text in dem nach url-bbcode gesucht werden soll
 */
function motd_place_links(&$str_text)
{    
    $str_text = preg_replace('#\[url=([\w]+?://[^ \"\n\r\t<]*?)\](.*?)\[/url\]#is','<a href="$1">$2</a>',$str_text);
}

/**
 * Schreibt ein Poll item
 *
 * @param int $id
 * @param string $subject
 * @param String $body
 * @param string $date
 * @param string $author
 * @return string PollItem
 */
function pollitem($id,$subject,$body,$group=0,$date='',$author='')
{
    global $Char,$arr_groups;
    
    $sql = "
            SELECT
                count(`resultid`)     AS `c`,
                MAX(`choice`)         AS `choice`
            FROM
                `pollresults`
            WHERE
                `motditem`    = '".$id."'        AND
                `account`    = '".$Char->acctid."'
            ";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    
    $choice = $row['choice'];
    $body = unserialize($body);
    
    //Darf noch abgestimmt werden    
    $bool_poll_active = time() < strtotime($body['end_date']);    
    if($bool_poll_active)
    {
        $str_output .= form_header($str_filename.'?op=vote');
        $str_output .= '<input type="hidden" name="motditem" value="'.$id.'">';
    }
    
    $str_output .= '`b'.($author ? $author : '').' Umfrage: '.$subject.' '.($group>0?'(Für '.$arr_groups[$group][1].'`0)':'').'`b`n';
    if ($date)
    {
        $str_output .= '`#`i[ '.strftime('%A, %e. %B %Y, %H:%M',strtotime($date)).' ]`i`n';
    }
    $motd_body = $body['body'];
    motd_place_links($motd_body);
    
    $str_output .= ('`3'.stripslashes($motd_body));
    $sql = "
            SELECT
                count(`resultid`) AS `c`,
                `choice`
            FROM
                `pollresults`
            WHERE
                `motditem`    = '".$id."'
            GROUP BY
                `choice`
            ORDER BY
                `choice`
            ";
    $result = db_query($sql);
    $choices = array();
    $totalanswers = 0;
    $maxitem = 0;

    while ($row = db_fetch_assoc($result))
    {
        $choices[$row['choice']]=$row['c'];
        $totalanswers+=$row['c'];
        if ($row['c']>$maxitem)
        {
            $maxitem = $row['c'];
        }
    }
    
    foreach ($body['opt'] as $key=>$val)
    {
        if (trim($val)!='')
        {
            if ($totalanswers<=0)
            {
                $totalanswers=1;
            }
            $str_output .= '`n';
            $percent = round($choices[$key] / $totalanswers * 100,1);
            $str_output .= $bool_poll_active? "<input type='radio' name='choice' value='".$key."'".($choice==$key?" checked":"").">":'';
            $str_output .= stripslashes($val)." (".(int)$choices[$key]." - ".$percent."%)";
            if ($maxitem==0)
            {
                $width=1;
            }
            else
            {
                $width = round(($choices[$key]/$maxitem) * 400,0);
            }
            $width = max($width,1);
            $str_output .= "`n<img src='images/rule.gif' width='".$width."' height='2' alt='".$percent."'>";
        }
    }
    if($bool_poll_active)
    {
        $str_output .= '<br /><input type="submit" class="button" value="Abstimmen" />';    
        $str_output .= form_footer();
    }
    $str_output .= '<hr>';
    
    return $str_output;
}

switch ($_GET['op'])
{
    case 'vote':
        if (!isset($Char->acctid))
        {
            header('Location: motd.php');
            exit();
        }
        $sql = "
            DELETE FROM
                `pollresults`
            WHERE
                `motditem`    = '".$_POST['motditem']."'             AND
                `account`    = '".$Char->acctid."'
            ";
        db_query($sql);
        $sql = "
            INSERT INTO
                `pollresults`
            SET
                `choice`    = '".$_POST['choice']."',
                `account`    = '".$Char->acctid."',
                `motditem`    = '".$_POST['motditem']."'
            ";
        db_query($sql);
        header('Location: motd.php');
        exit();
        
        break;

    case 'edit':
        $access_control->su_check(access_control::SU_RIGHT_MOTD,true);

        $str_output .= ' [<a href="motd.php">MoTD Index</a>] ';

        $int_item         = (int)$_REQUEST['motditem'];
        $str_body         = $_POST['motdbody'];
        $str_savebody     = '';
        $str_title         = $_POST['motdtitle'];
        $int_type         = (int)$_POST['motdtype'];
        $int_group         = (int)$_POST['motdgroup'];
        $int_author     = (int)$_POST['motdauthor'];
        $str_opt         = $_POST['opt'];
        $str_enddate    = $_POST['end_date'];
        $arr_body         = array();
        $arr_opt         = array();

        if ($_GET['act'] == 'save')
        {
            if ($int_type == 1)
            {
                $arr_opt     = explode('||',stripslashes($str_opt));
                $arr_body     = array('body'=>stripslashes(nl2br($str_body)),'opt'=>$arr_opt,'end_date'=>$str_enddate);
                $str_savebody = addslashes(serialize($arr_body));
            }
            else
            {
                $str_savebody = nl2br($str_body);
            }

            $sql = ($int_item ? 'UPDATE ' : 'INSERT INTO ');
            $sql .= ' `motd` SET ';

            $sql .= "
            `motdtitle`    = '".$str_title."',
            `motdbody`    = '".$str_savebody."',
            `motddate`    = ".($int_item==0 || $_POST['newmotd'] ? 'NOW()' : '`motddate`').",
            `motdtype`    = '".$int_type."',
            `motdgroup`    = '".$int_group."',
            `motdauthor`= ".($int_author > -1 ? "'".$int_author."'" : 'motdauthor')."
            ";

            $sql .= ($int_item ? " WHERE `motditem`    = '".$int_item."'" : '');

            db_query($sql);

            if (!db_error(LINK))
            {
                if ($int_item==0 || $_POST['newmotd'])
                {
                    //MOTD Status nur für bestimmte Leute updaten
                    if(!is_null_or_empty($_POST['motdgroup']))
                    {
                        $id = (int)$_POST['motdgroup'];
                        if($id > 0 && $id < MOTDGROUP_ALL)
                        {
                            $str_where = 'acctid != '.$Char->acctid.' AND superuser='.(int)$_POST['motdgroup'];
                        }
                        elseif($id == MOTDGROUP_ALL)
                        {
                            $str_where = 'acctid != '.$Char->acctid.' AND superuser IN ('.implode(',',$access_control->get_superuser_sugroups()).') ';
                        }
                        else
                        {
                            $str_where = 'acctid != '.$Char->acctid;
                        }
                    }
                    else
                    {
                        $str_where = 'acctid != '.$Char->acctid;
                    }

                    user_update(
                        array('lastmotd'=>'0000-00-00 00:00:00',
                            'where'=>$str_where
                        )
                    );
                }

                $session['message'] = '`@MoTD erfolgreich eingetragen!`0';

                //Write the RSS Webfeed
                if (getsetting('rss_enable_motd_feed',false)==true && ($int_item==0 || $_POST['newmotd']))
                {
                    require_once(LIB_PATH.'rss.lib.php');
                    rss_write_motd_feed_file();
                }

                header('Location: motd.php');
                exit;
            }
        }

        $str_author_list = ',enum,0,'.getsetting('teamname','Drachenserver-Team').','.$Char->acctid.','.$Char->login;

        $str_type_list = ',radio,0,Ohne Umfrage,1,Mit Umfrage';

        $str_group_list = ',enum';
        foreach ($arr_groups as $key=>$arr_group)
        {
            $str_group_list .= ','.$key.','.$arr_group[1];
        }

        $arr_form = array(
            'motditem'=>',hidden',
            'motdauthor'=>'Autor:'.$str_author_list,
            'motdtitle'=>'Titel:',
            'motdbody'=>'Inhalt:`n,textarea,35,8|?BBCode Style URLs werden zu korrekten Links umgeformt: [url=http://www.atrahor.de]Atrahor[/url] => <a href="http://www.atrahor.de">Atrahor.de</a>',
            'motdtype'=>'Typ:'.$str_type_list,
            'motdgroup'=>'Gruppe:'.$str_group_list,
            'opt'=>'Antwortmöglichkeiten für die Umfrage`n(mit || abtrennen)`n,textarea,35,8',
            'end_date' => 'Ende der Umfrage|?Es wird ein gültiges PHP Date Format erwartet z.B. NOW + 2 days oder im Format YYYY-MM-DD HH:MM:SS',
        );
        $arr_data = array(
            'motditem'=>$int_id,
            'motdauthor'=>($int_author?$int_author:$Char->acctid),
            'motdtitle'=>$str_title,
            'motdbody'=>$str_body,
            'motdtype'=>$int_type,
            'motdgroup'=>$int_group,
            'opt'=>$str_opt,
            'end_date' => $str_enddate?$str_enddate:date('Y-m-d H:i:s',strtotime("NOW +2 days"))
        );

        if ($int_item > 0)
        {
            $sql = 'SELECT * FROM motd WHERE motditem='.$int_item;
            $arr_motd = db_fetch_assoc(db_query($sql));

            //Falls es eine Umfrage ist
            if ($arr_motd['motdtype'] == 1)
            {
                // Umfrage vorhanden
                $arr_body = unserialize($arr_motd['motdbody']);
                $arr_motd['motdbody'] = $arr_body['body'];
                $arr_motd['opt'] = implode('||',$arr_body['opt']);
                $arr_motd['end_date'] = date('Y-m-d H:i:s',strtotime($arr_body['end_date']));
            }

            $arr_motd['motdbody'] = str_replace('<br />','',$arr_motd['motdbody']);

            $arr_form['newmotd'] = 'MoTD als neu markieren:,bool';
            $arr_data['newmotd'] = 0;

            $arr_form['motdauthor'] .= ',-1,~ Keine Änderung ~';
            $arr_motd['motdauthor'] = -1;

            $arr_data = array_merge($arr_data,$arr_motd);
        }

        $str_output .= form_header($str_filename.'?op=edit&act=save');

        //Ausgabe des Formulars, farbtags werden escaped
        $str_output .= str_replace('`','``',generateform($arr_form,$arr_data,false,'Veröffentlichen!'));

        $str_output .= form_footer();

        break;

    case 'del':

        if ($access_control->su_check(access_control::SU_RIGHT_MOTD))
        {
            $sql = "
            DELETE FROM
                `motd`
            WHERE
                `motditem`    = '".(int)$_GET['id']."'
            ";
            db_query($sql);

            $sql = "
            DELETE FROM
                `pollresults`
            WHERE
                `motditem`    = '".(int)$_GET['id']."'
            ";
            db_query($sql);

            header('Location: motd.php');
            exit();
        }

        break;

    default:

        $last_motddate = '0000-00-00 00:00:00';
        $per_page = 10;

        $str_output .= '`&';
        if (getsetting('rss_enable_motd_feed',false) == true)
        {
            $str_rss_header = '<img src="images/rss/feed_icon.gif" align="left" />RSS Feed';
            $str_rss_html = '
            Diese Nachrichten werden auch von einem Herold verkündet.
            <link rel="alternate" type="application/rss+xml"
            title="RSS" href="'.getsetting('server_address','http://www.atrahor.de').'/rss/motd.xml" />
            ';
            $str_output .= motditem($str_rss_header,$str_rss_html);
        }

        if($Char instanceof CCharacter )
        {
            $sql_where = ' WHERE (motdgroup=0 OR motdgroup='.$Char->superuser.' OR ('.$Char->superuser .'>0 AND motdgroup='.MOTDGROUP_ALL.'))' ;
        }
        else
        {
            $sql_where = ' WHERE (motdgroup = 0)' ;
        }

        //Suche speichern
        $arr_nav_vars = persistent_nav_vars(array('search'));
        
        if(!is_null_or_empty($arr_nav_vars['search']))
        {
            $arr_nav_vars['search']=str_replace('"','',stripslashes($arr_nav_vars['search']));
            $search=db_real_escape_string($arr_nav_vars['search']);
            $sql_where .= ' AND (motdbody LIKE "%'.$search.'%" OR motdtitle LIKE "%'.$search.'%")';
        }

        $sql = 'SELECT COUNT(*) AS anzahl FROM motd';
        $res = db_query($sql);
        $nr = db_fetch_assoc($res);

        $pagecount = ceil($nr['anzahl']/$per_page);
        $page = ($_POST['page'])?$_POST['page']:1;
        $from = ($page-1) * $per_page;
        $select = form_header($str_filename).'
        -&#8212; MotD-Archiv: <select name="page" size="1" onChange="this.form.submit();">';

        for ($i=1; $i<=$pagecount; $i++)
        {
            $select .= '<option value="'.$i.'" '.(($page==$i)?'selected="selected"':'').'>Seite '.$i.'</option>';
        }
        $select .= '</select>  &#8212;-
        <input type="hidden" name="search" value="'.$_POST['search'].'"> </form>';

        $sql = "
            SELECT
                `m`.*,
                `a`.`login`
            FROM
                `motd` `m`
            LEFT JOIN `accounts` `a` ON
                `a`.`acctid`     = `m`.`motdauthor`
                ".$sql_where."
            ORDER BY
                `m`.`motddate`     DESC
            LIMIT
                ".$from.",".$per_page;

        $result = db_query($sql);

        $last_motddate = 0;
        while ($row = db_fetch_assoc($result))
        {
            if ($last_motddate < $row['motddate'])
            {
                $last_motddate = $row['motddate'];
            }

            $author = '`&'.($row['login'] != '' ? $row['login'] : getsetting('teamname','Drachenserver-Team')).' :';

            $str_subj = $row['motdtitle']
            .($access_control->su_check(access_control::SU_RIGHT_MOTD)?
            " [<a href='motd.php?op=del&id=".$row['motditem']."' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">Del</a>]
            [<a href='motd.php?op=edit&motditem=".$row['motditem']."'>Edit</a>] "
            :"");
            if (!$row['motdtype'])
            {
                $str_body = '`3'.$row['motdbody'];
                $str_output .= motditem($str_subj,$str_body,$row['motdgroup'],$row['motddate'],$author);
            }
            else
            {
                $str_body = $row['motdbody'];
                $str_output .= pollitem($row['motditem'],$str_subj,$str_body,$row['motdgroup'],$row['motddate'],$author);
            }
        }
        
        //Die blöde Betameldung ist nervig
        //$str_output .= '`&';
        //$str_output .= motditem('Beta!','Dieses Spiel ist im Beta-Status! Wir basteln an der Dragonslayer-Edition, wenn wir Zeit haben und versuchen, das Spiel so bugfrei wie möglich zu halten. Das ist KEIN Freibrief zum Ausnutzen von Bugs, sondern alle Spieler (Teilnehmer am Beta-Test) sind verpflichtet, gefundene Fehler zu melden! Wünsche und Anregungen werden ebenfalls jederzeit gerne angenommen. : )');
        
        $str_output .= '`c'.$select.'`c';

        if($Char instanceof CCharacter)
        {
            $str_output .= form_header($str_filename).'
            `n`c-&#8212; Stichwort: <input type="text" name="search" value="'.$arr_nav_vars['search'].'">
            <input type="submit" class="button" value="Suchen"> &#8212;-`c';
            $str_output .= form_footer();
        }

        if ((isset($session['needtoviewmotd']) && $session['needtoviewmotd'] === true) || $Char->lastmotd == '0000-00-00 00:00:00')
        {
            $session['needtoviewmotd']     = false;
            $Char->lastmotd = $last_motddate;
            saveuser();
        }

        break;
}
output($str_output);
popup_footer(false);
?>

