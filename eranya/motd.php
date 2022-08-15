
<?php
/**
* motd.php: Stellt bewährte MoTD-Funktionalität zur Verfügung, erweitert
*                        um Editfunktion, Autorangabe, Datumsanzeige, Archiv.
* @author LOGD-Core, modified and rewritten by talion <t@ssilo.de>
* @version DS-E V/2
*/

require_once('common.php');

addcommentary(false);
session_write_close();
popup_header(getsetting('townname','Atrahor').': Message of the Day (MoTD)');
output('<script language="javascript">window.resizeTo(800,600);</script>',true);
$bool_hotmotc = false;
if ($session['user']['lastmotc'] == '0000-00-00 00:00:00') {
        $bool_hotmotc = true;
}
output('<center>`X[<a href="motd.php">`hMoTD-Index</a> `X| <a href="motd-coding.php?check=all">`hMoTC-Index'.($bool_hotmotc ? ' `$!' : '').'</a>`X]</center><br />');
if (su_check(SU_RIGHT_MOTD))
{
        output('<center>`X[<a href="motd.php?op=edit">`YMoTD / Umfrage erstellen</a> `X| <a href="motd-coding.php?op=neu">`YMoTC erstellen</a>`X]</center><br />');
}

function motditem($subject,$body,$date='',$author='')
{
        global $output;
    output('`b'.($author != '' ? $author : '').' '.$subject.'`b`n',true);
    if ($date!='')
    {
                output('`Y`i[ '.strftime('%A, %e. %B %Y, %H:%M',strtotime($date)).' ]`i`n');
    }
    output('`3'.closetags($body,'`i`b`c'));
    $output.='<hr>';
}

function pollitem($id,$subject,$body,$date='',$author='')
{
    global $session, $output;
    $sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='{$session['user']['acctid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $choice = $row['choice'];
    $body = unserialize($body);
    $bool_poll_active = time() < strtotime($body['end_date']);
    if($bool_poll_active)
    {
        $output .= '<form action="motd.php?op=vote" method="POST">';
        $output .= '<input type="hidden" name="motditem" value="'.$id.'">';
    }
    output('`b'.($author != '' ? $author : '').' Umfrage: '.$subject.'`b`n',true);
    if ($date!='')
    {
        output('`Y`i[ '.strftime('%A, %e. %B %Y, %H:%M',strtotime($date)).' ]`i`n');
    }
    output('`3'.stripslashes(closetags($body['body'],'`i`b`c')));
    $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
    $result = db_query($sql);
    $choices=array();
    $totalanswers=0;
    $maxitem = 0;
    $count = db_num_rows($result);
    for ($i=0; $i<$count; $i++)
    {
        $row = db_fetch_assoc($result);
        $choices[$row['choice']]=$row['c'];
        $totalanswers+=$row['c'];
        if ($row['c']>$maxitem)
        {
            $maxitem = $row['c'];
        }
    }
    foreach($body['opt'] as $key=>$val)
    {
        if (trim($val)!='')
        {
            if ($totalanswers<=0)
            {
                $totalanswers=1;
            }
            $percent = round($choices[$key] / $totalanswers * 100,1);
            output("`n<input type='radio' name='choice' value='$key'".($choice==$key?" checked":"").">&nbsp;",true);
            output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
        }
    }
    $output .= '<br /><br />';
    // button nur anzeigen, wenn User eingeloggt ist:
    if($session['user']['loggedin']) {
        // Abstimmung ist erst möglich, wenn Char älter als 1 Tag ist:
        $row_rpdate = rpdate(); $row_rpdate = explode('.',$row_rpdate);                       # [0] => Tag, [1] => Monat, [2] => Jahr
        $row_aei = user_get_aei('birthday'); $row_aei = explode('-',$row_aei['birthday']);    # [0] => Jahr, [1] => Monat, [2] => Tag
        if(!($row_rpdate[2] == $row_aei[0] && $row_rpdate[1] == $row_aei[1] && $row_rpdate[0] == $row_aei[2])) {
            $output .= '<input type="submit" class="button" value="Abstimmen">';
        }
    }
    // end
    $output .= '</form>';
    
    $output .= '<hr>';
}

switch($_GET['op']) 
{
        case 'vote':

                if (!isset($session['user']['acctid'])) {
                        header('Location: motd.php');
                        exit();
                }

                // Abstimmung ist erst möglich, wenn Char älter als 1 Tag ist:
                $row_rpdate = rpdate(); $row_rpdate = explode('.',$row_rpdate);                       # [0] => Tag, [1] => Monat, [2] => Jahr
                $row_aei = user_get_aei('birthday'); $row_aei = explode('-',$row_aei['birthday']);    # [0] => Jahr, [1] => Monat, [2] => Tag
                if($row_rpdate[2] == $row_aei[0] && $row_rpdate[1] == $row_aei[1] && $row_rpdate[0] == $row_aei[2]) {
                        header('Location: motd.php');
                        exit();
                }
                // end
    
                output('true');
                $sql = "DELETE FROM pollresults WHERE motditem='{$_POST['motditem']}
                ' AND account='{$session['user']['acctid']}
                '";
                db_query($sql);
                $sql = "INSERT INTO pollresults(choice,account,motditem,date) VALUES('{$_POST['choice']}
                ','{$session['user']['acctid']}
                ','{$_POST['motditem']}
                ',NOW()
                 )";
                db_query($sql);
                header('Location: motd.php');
                exit();
                
        break;
                
        case 'edit':
                
                if(!su_check(SU_RIGHT_MOTD)) 
                {
                        if ($session['user']['loggedin'])
                        {
                                $session['user']['experience']=round($session['user']['experience']*0.9,0);
                                addnews($session['user']['name'].'wurde für den Versuch, die Götter zu betrügen, bestraft.');
                                output('Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.');
                                saveuser();
                        }
                        exit;
                }
                
                output(' [<a href="motd.php">MoTD Index</a>] ',true);
                //$int_item = (int)$_REQUEST['motditem'];
                $int_item = (int)$_GET['motditem'];
                $str_body = $_POST['motdbody'];
                $str_savebody = '';
                $str_title = $_POST['motdtitle'];
                $int_type = (int)$_POST['motdtype'];
                $int_author = (int)$_POST['motdauthor'];
                $str_opt = $_POST['opt'];
                $str_enddate = $_POST['end_date'];
                $arr_body = array();
                $arr_opt = array();
                                                
                if($_GET['act'] == 'save') 
                {
                        if($int_type == 1) 
                        {
                                $arr_opt = explode('||',stripslashes($str_opt));
                                $arr_body = array('body'=>stripslashes(nl2br($str_body)),'opt'=>$arr_opt,'end_date'=>$str_enddate);
                                $str_savebody = addslashes(serialize($arr_body));                                                                
                        }
                        else 
                        {
                                $str_savebody = nl2br($str_body);                                                        
                        }
                        $sql = ($int_item ? 'UPDATE ' : 'INSERT INTO ');
                        $sql .= ' motd SET ';
                                
                        $sql .= '        motdtitle="'.$str_title.'",
                                                motdbody="'.$str_savebody.'",
                                                motddate='.($int_item==0 || $_POST['newmotd'] ? 'NOW()' : 'motddate').',
                                                motdtype="'.$int_type.'",
                                                motdauthor='.($int_author > -1 ? $int_author : 'motdauthor').'';
                        
                        $sql .= ($int_item ? ' WHERE motditem='.$int_item : '');
                                                
                        db_query($sql);
                                                                        
                        if(!db_error(LINK)) 
                        {
                                if($int_item==0 || $_POST['newmotd']) 
                                {
                                        $sql = 'UPDATE accounts SET lastmotd = "0000-00-00 00:00:00" WHERE acctid!='.$session['user']['acctid'];
                            db_query($sql);
                                }
                        
                                $session['message'] = '`@MoTD erfolgreich eingetragen!`0';
                                
                                //Write the RSS Webfeed
                                if(getsetting('rss_enable_motd_feed',false)==true)
                                {
                                        require_once(LIB_PATH.'rss.lib.php');
                                        rss_write_motd_feed_file();
                                }
                                
                                header('Location: motd.php');
                                exit;
                        }
                }
                
                $str_author_list = ',enum,0,Eranya-Team,
                                                        '.$session['user']['acctid'].','.$session['user']['login'];
                                                        
        
                $str_type_list = ',radio,0,Ohne Umfrage,1,Mit Umfrage';
                
                $arr_form = array('motditem'=>',hidden',
                                                        'motdauthor'=>'Autor:'.$str_author_list,
                                                        'motdtitle'=>'Titel:',
                                                        'motdbody'=>'Inhalt:`n,textarea,60,8',
                                                        'motdtype'=>'Typ:'.$str_type_list,
                                                        'opt'=>'Antwortmöglichkeiten für die Umfrage`n(mit || abtrennen)`n,textarea,60,8',
                                                        'end_date' => 'Ende der Umfrage|?Es wird ein gültiges PHP Date Format erwartet z.B. NOW + 2 days oder im Format YYYY-MM-DD HH:MM:SS'
                                                        );
                $arr_data = array('motditem'=>$int_item,
                                                        'motdauthor'=>($int_author?$int_author:$session['user']['acctid']),
                                                        'motdtitle'=>$str_title,
                                                        'motdbody'=>$str_body,
                                                        'motdtype'=>$int_type,
                                                        'opt'=>$str_opt,
                                                        'end_date' => $str_enddate?$str_enddate:date('Y-m-d H:i:s',strtotime("NOW +2 days"))
                                                        );
                if($int_item > 0) 
                {
                        $sql = 'SELECT * FROM motd WHERE motditem='.$int_item;
                        $arr_motd = db_fetch_assoc(db_query($sql));
                        
                        if($arr_motd['motdtype'] == 1) {        // Umfrage vorhanden
                                
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
                                
                $output.='<form action="motd.php?op=edit&act=save&motditem='.$int_item.'" method="POST">';
                                                        
                showform($arr_form,$arr_data,false,'Veröffentlichen!');
                
                $output .= '</form>';
                output('`n`n`b`YVorschau:`0`b`n`n');
                rawoutput('<u>MoTD-Titel:</u> '.js_preview('motdtitle').'<br /><br />');
                rawoutput('<u>MoTD-Text:</u><br /><br />'.js_preview('motdbody',true).'<br /><br />');
        break;
                
        case 'del':
                
                if (su_check(SU_RIGHT_MOTD))
                {
                        $sql = 'DELETE FROM motd WHERE motditem='.(int)$_GET['id'];
                        db_query($sql);
                        
                        $sql = 'DELETE FROM pollresults WHERE motditem='.(int)$_GET['id'];
                        db_query($sql);
                        
                        header('Location: motd.php');
                        exit();
                }
                else
                {
                        if ($session['user']['loggedin'])
                        {                                
                                $session['user']['experience']=round($session['user']['experience']*0.9,0);
                                addnews($session['user']['name'].' wurde für den Versuch, die Götter zu betrügen, bestraft.');
                                output('Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.');
                                saveuser();
                        }
                        exit;
                }
                
        break;
                
        default:

                /*if($session['user']['loggedin'])
                {*/
                        $last_motddate = '0000-00-00 00:00:00';
                        $per_page = 10;

                        output('`&');
                        if(getsetting('rss_enable_motd_feed',false) == true)
                        {
                                $str_rss_header = '<img src="images/rss/feed_icon.gif" align="left" />RSS Feed';
                                $str_rss_html = '
                                Diese Nachrichten werden auch von einem Herold verkündet.
                                <link rel="alternate" type="application/rss+xml"
                                          title="RSS" href="http://atrahor.de/rss/motd.xml" />
                                ';
                                motditem($str_rss_header,$str_rss_html);
                        }

                        $sql = 'SELECT COUNT(*) AS anzahl FROM motd';
                        $res = db_query($sql);
                        $nr = db_fetch_assoc($res);

                        $pagecount = ceil($nr['anzahl']/$per_page);
                        $page = ($_POST['page'])?$_POST['page']:1;
                        $from = ($page-1) * $per_page;
                        $select = '<form action="motd.php" method="POST">
                        -&#8212; MotD-Archiv: <select name="page" size="1" onChange="this.form.submit();">';

                        for ($i=1; $i<=$pagecount; $i++)
                        {
                                $select .= '<option value="'.$i.'" '.(($page==$i)?'selected="selected"':'').'>Seite '.$i.'</option>';
                        }
                        $select .= '</select>  -&#8212;</form>';

                        $sql = 'SELECT m.*,a.login FROM motd m LEFT JOIN accounts a ON a.acctid=m.motdauthor ORDER BY m.motddate DESC LIMIT '.$from.','.$per_page;
                        $result = db_query($sql);
                        $count = db_num_rows($result);
                        for ($i=0; $i<$count; $i++)
                        {
                                $row = db_fetch_assoc($result);

                                if ($i == 0)
                                {
                                        $last_motddate = $row['motddate'];
                                }

                                $author = '`&'.($row['login'] != '' ? $row['login'] : getsetting('teamname','Eranya-Team')).':';

                                if ($row['motdtype']==0)
                                {
                                        $str_subj = ''.$row['motdtitle']
                                        .(su_check(SU_RIGHT_MOTD)?
                                        " `7[<a href='motd.php?op=edit&motditem=$row[motditem]'>`&Edit</a>`7|<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">`&Del</a>`7]"
                                        :"");
                                        $str_body = '`3'.$row['motdbody'];

                                        motditem($str_subj,$str_body,$row['motddate'],$author);
                                }
                                else
                                {
                                        $str_subj = ''.$row['motdtitle']
                                        .(su_check(SU_RIGHT_MOTD)?
                                        " `7[<a href='motd.php?op=edit&motditem=$row[motditem]'>`&Edit</a>`7|<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">`&Del</a>`7] "
                                        :"");
                                        $str_body = $row['motdbody'];

                                        pollitem($row['motditem'],$str_subj,$str_body,$row['motddate'],$author);
                                }
                        }
                        output('`&');
                        motditem('Beta!','Dieses Spiel ist im Beta-Status! Wir basteln an der Dragonslayer-Edition, wenn wir Zeit haben, und versuchen, das Spiel so bugfrei wie möglich zu halten. Das ist KEIN Freibrief zum Ausnutzen von Bugs, sondern alle Spieler (Teilnehmer am Beta-Test) sind verpflichtet, gefundene Fehler zu melden! Wünsche und Anregungen werden ebenfalls jederzeit gerne angenommen. :)');
                        output('`c'.$select.'`c',true);

                        output('`n`$Kommentare und Fragen können im `^Diskussionsraum (Stadtplatz &#x2192 Stadtamt) `$oder im <a href="http://forum.eranya.de/" target="_blank" style="text-decoration: underline;">`^Forum</a> `$gepostet werden.`n`n',true);

                        $session['needtoviewmotd']=false;
                        $session['user']['lastmotd']=$last_motddate;
                /*}
                else
                {
                        output('`&Interessierst du dich für die neuesten Meldungen in '.getsetting('townname','Eranya').'?
                                Dann tritt ein und werde Teil unserer Welt - einer Welt voller Mythen und Wunder.`n`n`n');
                }*/
        break;                
}

popup_footer();
?>

