<?php
/*
 * Author: Chaosmaker <webmaster@chaosonline.de>
 * Server: http://logd.chaosonline.de
 *
 * Todolist for superuser grotto

CREATE TABLE IF NOT EXISTS `todolist` (
  `taskid` int(10) unsigned NOT NULL auto_increment,
  `acctid` int(10) unsigned NOT NULL default '0',
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(50) NOT NULL default '',
  `task` text NOT NULL,
  `importance` enum('unwichtig','nicht dringend','normal','dringend','sehr dringend') NOT NULL default 'normal',
  `implementation` int(10) unsigned NOT NULL default '0',
  `finished` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('offen','angenommen','abgelehnt','umgesetzt') NOT NULL default 'offen',
  `userinfo` enum('geheim','publik') NOT NULL default 'geheim',
  PRIMARY KEY  (`taskid`),
  KEY `status` (`status`),
  KEY `finished` (`finished`)
) TYPE=MyISAM COMMENT='Todoliste';


// 2006/08/28
// change by aragon for arania-logd: all navs changed from todolist.php to sutodolist.php

 */
require_once "common.php";
//isnewday(2);

page_header('Todolist');

if($session['user']['superuser']<2)addnav("Zurück zum Dorfplatz","village.php");
else addnav("W?Zurück zum Dorfplatz","village.php");
if($session['user']['superuser']>=3) addnav("G?Zurück zur Grotte","superuser.php");

if($session['user']['superuser']>=3)
{
    if ($_GET['op']=='inserttask') {
        if (trim($_POST['title'])!='' && trim($_POST['task'])!='') {
            $sql = 'INSERT INTO todolist (acctid,postdate,title,task,importance) VALUES ('.$session['user']['acctid'].',NOW(),"'.$_POST['title'].'","'.$_POST['task'].'","'.$_POST['importance'].'")';
            db_query($sql);
            $id = db_insert_id(LINK);
            adminlog();
            redirect('sutodolist.php?op=viewtask&id='.$id);
        }
        else {
            output('`4`bFehler: Bitte gib sowohl Titel als auch Beschreibung an!`b`0`n`n');
            $_GET['op'] = 'newtask';
        }
    }
    elseif ($_GET['op']=='deltask') {
        $sql = 'DELETE FROM commentary WHERE section="todolist-'.$_GET['id'].'"';
        db_query($sql);
        $sql = 'DELETE FROM todolist WHERE taskid='.$_GET['id'];
        db_query($sql);
        adminlog();
        $_GET['op'] = '';
    }
}

if ($_GET['op']=='viewtask') {
    addnav('Aktualisieren','sutodolist.php?op=viewtask&id='.$_GET['id']);
    addnav('Zurück','sutodolist.php');
    output('`c`bTodoliste - Aufgabendetails`b`c`n`n');

    addcommentary();
    if($session['user']['superuser']>=3)
    {
        if ($_POST['edittask']!='') {
            if (trim($_POST['title'])!='' && trim($_POST['task'])!='') {
                $sql = 'UPDATE todolist SET title="'.$_POST['title'].'",task="'.$_POST['task'].'",importance="'.$_POST['importance'].'",status="'.$_POST['status'].'",userinfo="'.$_POST['userinfo'].'"'.($_POST['status']=='umgesetzt'||$_POST['status']=='abgelehnt'?',finished=NOW()':',finished=""').' WHERE taskid='.$_GET['id'];
                db_query($sql);
                adminlog();
                $sql = 'INSERT INTO commentary (section, author, comment, postdate) VALUES ("todolist-'.$_GET['id'].'",'.$session['user']['acctid'].',"/me hat den Eintrag am '.date('d.m.Y, H:i').' Uhr, geändert.",NOW())';
                db_query($sql);
            }
            else {
                output('`4`bFehler: Bitte gib sowohl Titel als auch Beschreibung an!`b`0`n`n');
            }
        }
        elseif ($_GET['act']=='taketask') {
            $sql = 'UPDATE todolist SET implementation='.$session['user']['acctid'].' WHERE taskid='.$_GET['id'];
            db_query($sql);
            adminlog();
            redirect('sutodolist.php?op=viewtask&id='.$_GET['id']);
        }
        elseif ($_GET['act']=='droptask') {
            $sql = 'UPDATE todolist SET implementation=0 WHERE taskid='.$_GET['id'];
            db_query($sql);
            adminlog();
            redirect('sutodolist.php?op=viewtask&id='.$_GET['id']);
        }

        $session['todolist'][$_GET['id']] = date('Y-m-d H:i:s');
    }
    $sql = 'SELECT t.*, a1.name AS poster, a2.name AS implementor FROM todolist t LEFT JOIN accounts a1 USING(acctid) LEFT JOIN accounts a2 ON a2.acctid=t.implementation WHERE t.taskid='.$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    if($session['user']['superuser']>=3)
    {
        if ($row['implementation']==0) {
            $row['implementor'] = '`iniemand`i [<a href="sutodolist.php?op=viewtask&act=taketask&id='.$_GET['id'].'">übernehmen</a>]';
            addnav('','sutodolist.php?op=viewtask&act=taketask&id='.$_GET['id']);
        }
        else {
            if ($row['implementation']==$session['user']['acctid']) {
                $row['implementor'] .= ' [<a href="sutodolist.php?op=viewtask&act=droptask&id='.$_GET['id'].'">abgeben</a>]';
                addnav('','sutodolist.php?op=viewtask&act=droptask&id='.$_GET['id']);
            }
            else {
                $row['implementor'] .= ' [<a href="sutodolist.php?op=viewtask&act=droptask&id='.$_GET['id'].'">abnehmen</a> ';
                addnav('','sutodolist.php?op=viewtask&act=droptask&id='.$_GET['id']);
                $row['implementor'] .= '| <a href="sutodolist.php?op=viewtask&act=taketask&id='.$_GET['id'].'">übernehmen</a>]';
                addnav('','sutodolist.php?op=viewtask&act=taketask&id='.$_GET['id']);
            }
        }

        if ($row['finished']<=0) $row['finished'] = '---';
    }

    if($session['user']['superuser']>=3)
    {
    output('<form action="sutodolist.php?op=deltask&id='.$_GET['id'].'" method="post">',true);
    addnav('','sutodolist.php?op=deltask&id='.$_GET['id']);
    output('<input type="submit" class="button" value="Eintrag löschen" onClick="return confirm(\'Soll der Eintrag wirklich gelöscht werden?\');" />',true);
    output('</form>',true);

    output('<form action="sutodolist.php?op=viewtask&id='.$_GET['id'].'" method="post">',true);
    addnav('','sutodolist.php?op=viewtask&id='.$_GET['id']);
    output('<input type="hidden" name="edittask" value="1" />',true);
    $form = array(
            'title'=>'Titel (max. 50 Zeichen)',
            'task'=>'Beschreibung,textarea,60,10',
            'postdate'=>'Erstellt,viewonly',
            'poster'=>'Von,viewonly',
            'implementor'=>'Umsetzung,viewonly',
            'importance'=>'Dringlichkeit,enum,unwichtig,,nicht dringend,,normal,,dringend,,sehr dringend,',
            'status'=>'Status,enum,offen,,angenommen,,abgelehnt,,umgesetzt,',
            'userinfo'=>'Infos,enum,geheim,,publik,',
            'finished'=>'Fertiggestellt,viewonly'
            );
    showform($form,$row);
    output('</form>',true);
    }
    else
    {
    $form = array(
            'title'=>'Titel,viewonly',
            'task'=>'Beschreibung,textarea,60,10',
            'postdate'=>'Erstellt,viewonly',
            'poster'=>'Von,viewonly',
            'implementor'=>'Umsetzung,viewonly',
            'importance'=>'Dringlichkeit,enum,unwichtig,,nicht dringend,,normal,,dringend,,sehr dringend,',
            'status'=>'Status,enum,offen,,angenommen,,abgelehnt,,umgesetzt,',
            'userinfo'=>'Infos,enum,geheim,,publik,',
            'finished'=>'Fertiggestellt,viewonly'
            );
        showform($form,$row,true);
    }

    output("`n`@Kommentare:`n");
    viewcommentary("todolist-{$_GET['id']}","Hinzufügen",200);
}
elseif ($_GET['op']=='newtask' && $session['user']['superuser']>=3) {
    addnav('Zurück','sutodolist.php');
    output('`c`bTodoliste - Aufgabe hinzufügen`b`c`n`n');
    output('<form action="sutodolist.php?op=inserttask" method="post">',true);
    addnav('','sutodolist.php?op=inserttask');
    $form = array(
            'title'=>'Titel (max. 50 Zeichen)',
            'task'=>'Beschreibung,textarea,60,10',
            'importance'=>'Dringlichkeit,enum,unwichtig,,nicht dringend,,normal,,dringend,,sehr dringend,'
            );
    $row = array('title'=>$_POST['title'],'task'=>$_POST['task'],'importance'=>$_POST['importance']);
    showform($form,$row);
    output('</form>',true);
}
else {
    addnav('Aktualisieren','sutodolist.php');
    output('`c`bTodoliste - aktuelle Aufgaben`b`c`n`n');
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Aufgabe</b></td><td><b>Erstellt</b></td><td><b>Von</b></td><td><b>Umsetzung</b></td><td><b>Kommentare</b></td><td><b>Letzter Kommentar</b></td><td><b>Dringlichkeit</b></td><td><b>Status</b></td><td><b>Infos</b></td></tr>",true);
    $i = 0;
    $sql = 'SELECT t.*, a1.name AS poster, a2.name AS implementor, COUNT(c.commentid) AS commentcount, MAX(c.postdate) AS lastcomment FROM todolist t LEFT JOIN accounts a1 USING(acctid) LEFT JOIN accounts a2 ON a2.acctid=t.implementation LEFT JOIN commentary c ON c.section=CONCAT("todolist-",t.taskid) GROUP BY t.taskid ORDER BY t.status ASC, t.importance DESC, lastcomment DESC, postdate DESC';
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result))
    {
        if(($row['userinfo']!="geheim" && $session['user']['superuser']<2) || $session['user']['superuser']>=2)
        {
            $bb="";
            $b="";
            output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            if (max($row['postdate'],$row['lastcomment'])>max($session['lastlogoff'],$session['todolist'][$row['taskid']])) {
                $bb="`b";
                output('`4*`0`b');
            }
            switch($row['importance']) {
                case 'unwichtig':
                case 'normal':
                    $coli = '`2';
                    break;
                case 'nicht dringend':
                    $coli = '`6';
                    break;
                case 'sehr dringend':
                    $b="`b";
                    $coli = '`4';
                    break;
                case 'dringend':
                    $b="`b";
                default:
                    $coli = '`Q';
            }
            switch($row['status']) {
                case 'angenommen':
                    $col = '`6';
                    break;
                case 'abgelehnt':
                    $col = '`4';
                    break;
                case 'umgesetzt':
                    $col = '`2';
                    break;
                case 'offen':
                default:
                    $col = '`Q';
            }
            output('<a href="sutodolist.php?op=viewtask&id='.$row['taskid'].'">',true);
            addnav('','sutodolist.php?op=viewtask&id='.$row['taskid']);
            output($bb.$row['title'].$bb);
            output('</a>',true);
            output('</td><td>',true);
            output($row['postdate']);
            output('</td><td>',true);
            output($row['poster']);
            output('</td><td>',true);
            if ($row['implementation']>0) output($row['implementor']);
            else output('---');
            output('</td><td>',true);
            output($row['commentcount']);
            output('</td><td>',true);
            if ($row['lastcomment']>0) output($row['lastcomment']);
            else output('---');
            output('</td><td>',true);
            output($coli.$b.$row['importance'].$b);
            output('</td><td>',true);
            output($col.$row['status'].'`0');
            output('</td><td>',true);
            output($row['userinfo']);
            output('</td></tr>',true);
            $i++;
        }
    }
    output('</table>',true);
    if($session['user']['superuser']>=2)addnav('Aufgabe hinzufügen','sutodolist.php?op=newtask');
}

page_footer();
?> 