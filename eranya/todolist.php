
<?php
// entdeckt und kopiert auf Rabenthal.de ;)
// Vielen Dank an die dortigen Programmierer für diese großartige Arbeit

// advanced by Silva

require_once "common.php";

define('COLORSU_TASKLISTBOLD','`w');
define('COLORSU_TASKLISTTEXT','`^');

page_header('Todolist');


addnav('Zurück');
addnav('W?Zurück zum Weltlichen',$session['su_return']);
addnav("G?Zurück zur Grotte","superuser.php");

if ($_GET['op'])
{
        if ($_GET['op']=='inserttask') {
            if (trim($_POST['title'])!='' && trim($_POST['task'])!='') {
                $sql = 'INSERT INTO todolist (acctid,postdate,title,task,importance) VALUES ('.$session['user']['acctid'].',NOW(),"'.$_POST['title'].'","'.$_POST['task'].'","'.$_POST['importance'].'")';
                db_query($sql);
                $id = db_insert_id(LINK);
                // adminlog();
                redirect('todolist.php?op=viewtask&id='.$id);
            }
            else {
                output('`4`bFehler: Bitte gib sowohl Titel als auch Beschreibung an!`b`0`n`n');
                $_GET['op'] = 'newtask';
            }
        }
        elseif ($_GET['op']=='deltask') {
            $sql = 'SELECT * FROM commentary WHERE section="todolist-'.$_GET['id'].'"';
            db_query($sql);
            $sql = 'DELETE FROM todolist WHERE taskid='.$_GET['id'];
            db_query($sql);
            // adminlog();
            $_GET['op'] = '';
        }


        if ($_GET['op']=='viewtask') {
            addnav('Aktualisieren','todolist.php?op=viewtask&id='.$_GET['id']);
            addnav('Zurück','todolist.php');
            output('`c`bTodoliste - Aufgabendetails`b`c`n`n');

            addcommentary(false);
            if ($_POST['edittask']!='') {
                if (trim($_POST['title'])!='' && trim($_POST['task'])!='') {
                    $sql = 'UPDATE todolist SET title="'.$_POST['title'].'",task="'.$_POST['task'].'",importance="'.$_POST['importance'].'",status="'.$_POST['status'].'",userinfo="'.$_POST['userinfo'].'"'.($_POST['status']=='umgesetzt'?',finished=NOW()':',finished=""').' WHERE taskid='.$_GET['id'];
                    db_query($sql);
                    // adminlog();
                }
                else {
                    output('`4`bFehler: Bitte gib sowohl Titel als auch Beschreibung an!`b`0`n`n');
                }
            }
            elseif ($_GET['act']=='taketask') {
                $sql = 'UPDATE todolist SET implementation='.$session['user']['acctid'].' WHERE taskid='.$_GET['id'];
                db_query($sql);
                // adminlog();
                redirect('todolist.php?op=viewtask&id='.$_GET['id']);
            }
            elseif ($_GET['act']=='droptask') {
                $sql = 'UPDATE todolist SET implementation=0 WHERE taskid='.$_GET['id'];
                db_query($sql);
                // adminlog();
                redirect('todolist.php?op=viewtask&id='.$_GET['id']);
            }

            $session['todolist'][$_GET['id']] = date('Y-m-d H:i:s');

            $sql = 'SELECT t.*, a1.name AS poster, a2.name AS implementor FROM todolist t LEFT JOIN accounts a1 USING(acctid) LEFT JOIN accounts a2 ON a2.acctid=t.implementation WHERE t.taskid='.$_GET['id'];
            $result = db_query($sql);
            $row = db_fetch_assoc($result);

            if ($row['implementation']==0) {
                $row['implementor'] = '`iniemand`i [<a href="todolist.php?op=viewtask&act=taketask&id='.$_GET['id'].'">übernehmen</a>]';
                addnav('','todolist.php?op=viewtask&act=taketask&id='.$_GET['id']);
            }
            else {
                if ($row['implementation']==$session['user']['acctid']) {
                    $row['implementor'] .= ' [<a href="todolist.php?op=viewtask&act=droptask&id='.$_GET['id'].'">abgeben</a>]';
                    addnav('','todolist.php?op=viewtask&act=droptask&id='.$_GET['id']);
                }
                else {
                    $row['implementor'] .= ' [<a href="todolist.php?op=viewtask&act=droptask&id='.$_GET['id'].'">abnehmen</a> ';
                    addnav('','todolist.php?op=viewtask&act=droptask&id='.$_GET['id']);
                    $row['implementor'] .= '| <a href="todolist.php?op=viewtask&act=taketask&id='.$_GET['id'].'">übernehmen</a>]';
                    addnav('','todolist.php?op=viewtask&act=taketask&id='.$_GET['id']);
                }
            }

            if ($row['finished']<=0) $row['finished'] = '---';

            output('<form action="todolist.php?op=viewtask&id='.$_GET['id'].'" method="post">',true);
            addnav('','todolist.php?op=viewtask&id='.$_GET['id']);
            output('<input type="hidden" name="edittask" value="1" />',true);
            $form = array(
                    'title'=>'Titel (max. 50 Zeichen)',
                    'task'=>'Beschreibung,textarea,60,10',
                    'postdate'=>'Erstellt,viewonly',
                    'poster'=>'Von,viewonly',
                    'implementor'=>'Umsetzung,viewonly',
                    'importance'=>'Dringlichkeit,enum,unwichtig,,nicht dringend,,normal,,dringend,,sehr dringend,',
                    'status'=>'Status,enum,offen,,angenommen,,abgelehnt,,umgesetzt,',
                    'userinfo'=>'Infos,enum,geheim,,publik,',
                    'finished'=>'Fertiggestellt,viewonly'
                    );
            showform($form,$row);
            output('</form>',true);
            output('<form action="todolist.php?op=deltask&id='.$_GET['id'].'" method="post">',true);
            addnav('','todolist.php?op=deltask&id='.$_GET['id']);
            output('<input type="submit" class="button" value="Eintrag löschen" onClick="return confirm(\'Soll der Eintrag wirklich gelöscht werden?\');" />',true);
            output('</form>',true);

            output("`n`@Kommentare:`n");
            viewcommentary("todolist-{$_GET['id']}","Hinzufügen",200);
            $show_ooc = false;
        }
        elseif ($_GET['op']=='newtask') {
            addnav('Zurück','todolist.php');
            output('`c`bTodoliste - Aufgabe hinzufügen`b`c`n`n');
            output('<form action="todolist.php?op=inserttask" method="post">',true);
            addnav('','todolist.php?op=inserttask');
            $form = array(
                    'title'=>'Titel (max. 50 Zeichen)',
                    'task'=>'Beschreibung,textarea,60,10',
                    'importance'=>'Dringlichkeit,enum,unwichtig,,nicht dringend,,normal,,dringend,,sehr dringend,'
                    );
            $row = array('title'=>$_POST['title'],'task'=>$_POST['task'],'importance'=>$_POST['importance']);
            showform($form,$row);
            output('</form>',true);
        }

        if ($_GET['op'] == 'su_tasklist')
        {
            if($_GET['subop'])
            {
                    addnav('Zuständigkeitsliste');
                    addnav('Zurück zur Zuständigkeitsliste','todolist.php?op=su_tasklist');

                    if ($_GET['subop'] == 'new')
            {
                if ($_GET['act'] == 'save')
                {
                    if (!empty($_POST['task']) && trim($_POST['task']) != '')
                    {
                        if ($_POST['name'] != 'Noch offen')
                        {
                            $sql = "SELECT name FROM accounts WHERE login='".$_POST['name']."'";
                            $result = db_query($sql);
                            $row = db_fetch_assoc($result);

                            $sql2 = "INSERT INTO su_tasklist (name,description) VALUES ('".$row['name']."','".$_POST['task']."')";
                            db_query($sql2);
                            redirect('todolist.php?op=su_tasklist');
                        }
                        else
                        {
                            $sql2 = "INSERT INTO su_tasklist (name,description) VALUES ('`b`#".$_POST['name']."`b','".$_POST['task']."')";
                            db_query($sql2);
                            redirect('todolist.php?op=su_tasklist');
                        }
                    }
                    else
                    {
                        output(COLORSU_TASKLISTTEXT.'Bitte gib eine Aufgabenbeschreibung ein.');
                        addnav('Zurück','todolist.php?op=su_tasklist&subop=new');
                    }
                }
                else
                {
                    $sql = 'SELECT login FROM accounts WHERE superuser>0 AND superuser!=5';
                    $result = db_query($sql) or die(db_error(LINK));
                    $int_x = db_num_rows($result);
                    for ($i=0;$i<$int_x;$i++)
                    {
                        $row = db_fetch_assoc($result);
                        if (!$formnames) {$formnames = "".$row['login'].",".($i+1)."";}
                        else {$formnames .= ",".$row['login'].",".($i+1)."";}
                    }
                    output("<form action=\"todolist.php?op=su_tasklist&subop=new&act=save\" method='POST'>",true);
                    addnav('','todolist.php?op=su_tasklist&subop=new&act=save');
                    $form = array(
                            'name'=>'Name,enum,Noch offen,0,'.$formnames,
                            'task'=>'Aufgaben,textarea,60,10'
                            );
                    $row = array(
                           'name'=>$_POST['name'],
                           'task'=>$_POST['task']
                           );
                    showform($form,$row);
                    output('</form>',true);
                }
            }
                    elseif ($_GET['subop'] == 'edit')
                    {
                        if ($_GET['act'] == 'add')
                        {
                            if (isset($_POST['save']))
                            {
                                $sql = "UPDATE su_tasklist SET description='".$_POST['task']."' WHERE su_taskid=".$_GET['id'];
                                db_query($sql) or die(db_error(LINK));
                                redirect('todolist.php?op=su_tasklist');
                            }
                            else redirect('todolist.php?op=su_tasklist');
                        }
                        else
                        {
                            $sql = 'SELECT * FROM su_tasklist WHERE su_taskid='.$_GET['id'].'';
                            $result = db_query($sql) or die(db_error(LINK));
                            $row = db_fetch_assoc($result);

                            output(COLORSU_TASKLISTBOLD.'`c`b'.$row['name'].'`b`n`n');
                            output("<form action=\"todolist.php?op=su_tasklist&subop=edit&act=add&id=".$_GET['id']."\" name='task' method='POST'>",true);
                            output("<table><tr class='trdark'><td colspan='2'>");
                            output("<textarea name='task' class='input' cols='60' rows='10'>",true);
                            output(str_replace('`','``',$row['description']));
                            output("</textarea>`n<input type='submit' class='button' name='save' value='Speichern'>`c",true);
                            addnav('','todolist.php?op=su_tasklist&subop=edit&act=add&id='.$_GET['id']);
                            output('</td></tr></table>');
                            output('</form>',true);
                        }
                    }
                    elseif ($_GET['subop'] == 'delete')
                    {
                        $sql = 'DELETE FROM su_tasklist WHERE su_taskid='.$_GET['id'];
                        db_query($sql) or die(db_error(LINK));
                        redirect('todolist.php?op=su_tasklist');
                    }
            }
            else
            {
                addnav('ToDo-Liste');
                addnav('Zurück zur ToDo-Liste','todolist.php');
                addnav('Hinzufügen');
                addnav('Hinzufügen','todolist.php?op=su_tasklist&subop=new');
                output(COLORSU_TASKLISTBOLD.'`c`bWer macht was?`b`0`n`n`n`n');
                output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
                output("<tr class='trhead' align='center'><td><b>Name</b></td><td><b>Zuständigkeitsbereich</b></td><td><b>Editieren</b></td></tr>",true);

                $sql = 'SELECT * FROM su_tasklist ORDER BY su_taskid ASC';
                $result = db_query($sql) or die(db_error(LINK));

                $i = 0;
                while ($row = db_fetch_assoc($result))
                {
                    output("<tr bgcolor='".($i%2?"#111111":"#333333")."'>",true);
                    output("<td align='left' style=\"padding: 3px 3px 3px 3px;\">`&".$row['name']."`0</td>",true);
                    output("<td align='left' style=\"padding: 3px 3px 3px 3px;\">`e".$row['description']."`0</td>",true);
                    output("<td align='center' style=\"padding: 3px 3px 3px 3px;\">",true);
                    output("<a href='todolist.php?op=su_tasklist&subop=edit&id=".$row['su_taskid']."'>`9Editieren</a> `0| ",true);
                    addnav('','todolist.php?op=su_tasklist&subop=edit&id='.$row['su_taskid']);
                    output("<a href='todolist.php?op=su_tasklist&subop=delete&id=".$row['su_taskid']."' onClick='return confirm(\"Wirklich entfernen?\");'>`\$Entfernen</a>",true);
                    addnav('','todolist.php?op=su_tasklist&subop=delete&id='.$row['su_taskid']);
                    output("</td></tr>",true);
                    $i++;
                }
                output("</table>`c`n`n`n`n",true);
            }
        }
}
else {
    addnav('Aufgaben');
    addnav('Aktualisieren','todolist.php');
    output('`c`bTodoliste - aktuelle Aufgaben`b`c`n`n');
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Aufgabe</b></td><td><b>Erstellt</b></td><td><b>Von</b></td><td><b>Umsetzung</b></td><td><b>Kommentare</b></td><td><b>Letzter Kommentar</b></td><td><b>Dringlichkeit</b></td><td><b>Status</b></td><td><b>Infos</b></td></tr>",true);
    $i = 0;
    $sql = 'SELECT t.*, a1.name AS poster, a2.name AS implementor, IF(c.section IS NULL,0,COUNT(*)) AS commentcount, MAX(c.postdate) AS lastcomment FROM todolist t LEFT JOIN accounts a1 USING(acctid) LEFT JOIN accounts a2 ON a2.acctid=t.implementation LEFT JOIN commentary c ON c.section=CONCAT("todolist-",t.taskid) GROUP BY t.taskid ORDER BY t.status ASC, t.importance DESC, lastcomment DESC, postdate DESC';
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        if (max($row['postdate'],$row['lastcomment'])>max($session['lastlogoff'],$session['todolist'][$row['taskid']])) {
            output('`4*`0');
        }
        output('<a href="todolist.php?op=viewtask&id='.$row['taskid'].'">',true);
        addnav('','todolist.php?op=viewtask&id='.$row['taskid']);
        output($row['title']);
        output('</a>',true);
        output('</td><td>',true);
        output($row['postdate']);
        output('</td><td>',true);
        output($row['poster']);
        output('</td><td>',true);
        if ($row['implementation']>0) output($row['implementor']);
        else output('---');
        output('</td><td>',true);
        output($row['commentcount']);
        output('</td><td>',true);
        if ($row['lastcomment']>0) output($row['lastcomment']);
        else output('---');
        output('</td><td>',true);
        output($row['importance']);
        output('</td><td>',true);
        output($row['status']);
        output('</td><td>',true);
        output($row['userinfo']);
        output('</td></tr>',true);
        $i++;
    }
    output('</table>',true);
    addnav('Aufgabe hinzufügen','todolist.php?op=newtask');
    //addnav('Zuständigkeit');
    //addnav('Zuständigkeitsliste','todolist.php?op=su_tasklist');
}

page_footer();
?> 
