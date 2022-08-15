
<?php
require_once('common.php');

if($_GET['op'] == 'del') {
        $sql = 'DELETE FROM history WHERE id='.(int)$_GET['id'];
        db_query($sql);
        redirect($g_ret_page);
}

$sql = 'SELECT name,login,prefs FROM accounts WHERE acctid='.(int)$_GET['acctid'];
$res = db_query($sql);
$act = db_fetch_assoc($res);
$prefs = unserialize($act['prefs']);

if(isset($prefs['history_self_mode']) && $prefs['history_self_mode'] > 0) {$self_mode = $prefs['history_self_mode'];}
else {$self_mode = 0;}
if(isset($prefs['history_shownodate']) && $prefs['history_shownodate'] > 0) {$int_shownodate = $prefs['history_shownodate'];}
else {$int_shownodate = 0;}

page_header('Geschichte von '.$act['login']);

if($_GET['op'] == 'edit')
{
        // Aufzeichnungen editieren
        if($_GET['save'] == 'ok')
        {
                $id = (int)$_GET['id'];
                $newh = soap(closetags(strip_tags($_POST['newh'],'<font><table><tr><td><br>'),'`i`b`c'));
                $newdate_text = soap(closetags(strip_tags($_POST['newdate_text']),'`i`b`c'));
                $newdate_text = str_replace('`n','',substr($newdate_text,0,255));
                if(strlen(strip_appoencode($newdate_text,3)) > 30) {
                    output('`b`$Fehler!`b `^Der Text für das alternative Datum ist länger als 30 Zeichen!');
                    addnav('Nochmal','history.php?op=edit&id='.$id.'&acctid='.(int)$_GET['acctid'].'&ret='.urlencode($_GET['ret']));
                } else {
                    db_query('UPDATE history SET msg="'.$newh.'",gamedate_text="'.$newdate_text.'" WHERE id='.$id);
                    redirect('history.php?acctid='.(int)$_GET['acctid']);
                }
        }
        else
        {
                $id = (int)$_GET['id'];
                $sql = db_query('SELECT gamedate,gamedate_text,msg FROM history WHERE id='.$id.' LIMIT 1');
                $oldh = db_fetch_assoc($sql);
                output('`F`c`bAufzeichnung editieren`b`c
                        `n
                        <form action="history.php?op=edit&save=ok&id='.$id.'&acctid='.(int)$_GET['acctid'].'" method="post">');
                showform(array('gamedate'=>'Eingetragenes Datum:,viewonly'
                              ,'newdate_text'=>'Eigenes Datum:,text,255|?Max. 30 Zeichen exklusive Farbcodes;<br>wird anstelle des Ingame-Datums in der Liste ausgegeben, hat aber keinen Einfluss auf die Reihenfolge der Aufzeichnungen'
                              ,'newsdate_text_prev'=>'Vorschau:,preview,newdate_text'
                              ,'newh'=>'Deine Aufzeichnung:,textarea,60,4,1200'
                              ,'newh_prev'=>'Vorschau:,preview,newh',
                              )
                        ,array('gamedate'=>getgamedate($oldh['gamedate'])
                              ,'newh'=>$oldh['msg']
                              ,'newdate_text'=>$oldh['gamedate_text']
                              )
                        ,false,'ändern');
                output('</form>`n`n');
                addnav('','history.php?op=edit&save=ok&id='.$id.'&acctid='.(int)$_GET['acctid']);
                rawoutput(js_preview('newsh'));
        }
}
elseif ($_GET['what'] == 'news')
{
        // NEWS
        output('`n`^`bLetzte Leistungen (und Niederlagen) von '.$act['name'].'`^:`b`n');
        $result = db_query("SELECT newstext,newsdate FROM news WHERE accountid={$_GET['acctid']} ORDER BY newsdate DESC,newsid ASC LIMIT 70");
        $odate="";

        $news_count = db_num_rows($result);

        $news_out = '';

        for ($i=0;$i<$news_count;$i++){
                $news_row = db_fetch_assoc($result);
                if ($odate!=$news_row[newsdate]){
                        $news_out.='`n`b`@'.strftime('%A, %e. %B',strtotime($news_row['newsdate'])).'`b`n';
                        $odate=$news_row['newsdate'];
                }
                $news_out .= $news_row['newstext'].'`n';

        }
        output($news_out);
        // END NEWS
}
else
{
        output('`c`&`bGeschichte von '.$act['name'].'`&`b`c`n`n`n');

        show_history(1,$_GET['acctid'],false,$self_mode,0,$int_shownodate);
}

addnav('Zurück zur Bio','bio.php?char='.rawurlencode($act['login']).'&op='.$_GET['op'].'&ret='.urlencode($_GET['ret']));

page_footer();
?>

