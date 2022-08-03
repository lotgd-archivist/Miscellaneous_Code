<?php
/*********************************************************************
*                                                                    *
*                                                                    *
**********************************************************************/

require_once("common.php");
addcommentary();

page_header("Der Weihnachtsmarkt");
switch($_GET['op'])
    {
    case 'schball':
    page_header('Schneeballwettkampf');
    output('`8Du betrittst ein kleines Zelt, welches direkt unter der Eiche steht. Im Innenraum sitzt ein Gnom an einem kleinen Tisch und schaut zu dir auf.`n');
    output('`iWas kann ich für euch tun, Bürger?`i`n');
    output('Damit zeigt er auf eine Tafel neben dem Eingang, auf dem mit magischer Schrift Namen und Trefferpunkte von der jetzigen Schneeballschlacht sich ständig ändern.`n`n');
    addnav('Schneeball per Bote','weihnachtsmarkt.php?op=schball&op1=mail');
    addnav('Schneeball- wettkampf');
    addnav('Schneeballmeister','weihnachtsmarkt.php?op=schball&op1=user');
    switch($_GET['op1'])
        {
        case 'user':
        $userperpage=10;
        $sql = 'SELECT count(acctid) AS c FROM accounts';
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $totaluser = $row['c'];
        $pageoffset = (int)$_GET['page'];
        if ($pageoffset>0) $pageoffset--;
        $pageoffset*=$userperpage;
        $from = $pageoffset+1;
        $to = min($pageoffset+$userperpage,$totaluser);
        $limit="LIMIT $pageoffset,$userperpage";
        addnav('Seiten');

        for ($i=0;$i<$totaluser;$i+=$userperpage)
            {
            addnav('Seite '.($i/$userperpage+1).' ('.($i+1).'-'.min($i+$userperpage,$totaluser).')','weihnachtsmarkt.php?op=schball&op1=user&page='.($i/$userperpage+1));
            }
        output('`c`bSchneeballmeister der Bürger (Seite '.($pageoffset/$userperpage+1).': '.$from.'-'.$to.' von '.$totaluser.')`b`c`n`n');
        output('`c<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">`c',true);
        output('<tr class="trhead"><td><b>`cName`c</b></td><td><b>Punkte</b></td>',true);
        $sql = "SELECT * FROM accounts ORDER BY schneerang DESC $limit";
        $result = db_query($sql);
        if (db_num_rows($result)==0){ output("<tr><td colspan=4 align='center'>`&`i`cEs sind keine Einträge vorhanden`c`i`0</td></tr>",true);}
        for ($i=0;$i<db_num_rows($result);$i++)
            {
            $row = db_fetch_assoc($result);
            if ($row['name']==$session['user']['name'])
                {
                output("<tr bgcolor='#005500'><td>",true);
                }
            else
                {
                output('<tr class="'.($i%2?'trdark':'trlight').'"><td>',true);
                }
            if ($session['user']['loggedin']) output('<a href="mail.php?op=write&to='.rawurlencode($row['login']).'" target="_blank" onClick="'.popup('mail.php?op=write&to='.rawurlencode($row['login']).'').';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>',true);
            if ($session['user']['loggedin'])
            output($row[name].'`0');
            if ($session['user']['loggedin']) output('</a>',true);
            output('</td><td>',true);
            output('`c`^'.$row[schneerang].'`c`0');
            output('</td>',true);
            }
        output('</table>`n`n',true);
        break;
        case 'mail':
        if ($session['user']['schneeball'] < 20) // <- Mengenfestlegung
            {
            if (isset($_POST['search']) || $_GET['search']>"")
                {
                if ($_GET['search']>"") $_POST['search']=$_GET['search'];
                $search="%";
                for ($x=0;$x<strlen($_POST['search']);$x++)
                    {
                    $search .= substr($_POST['search'],$x,1)."%";
                    }
                $search="name LIKE '".$search."' AND ";
                if ($_POST['search']=="weiblich") $search="sex=1 AND ";
                if ($_POST['search']=="männlich") $search="sex=0 AND ";
                }
            else
                {
                $search="";
                }
            $ppp=25; // Player Per Page to display
            if (!$_GET[limit])
                {
                $page=0;
                }
            else
                {
                $page=(int)$_GET[limit];
                addnav("Vorherige Seite","weihnachtsmarkt.php?op=schball&op1=mail&limit=".($page-1)."&search=$_POST[search]");
                }
            $limit="".($page*$ppp).",".($ppp+1);
            $sql = "SELECT login,name,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." ORDER BY login LIMIT $limit";
            $result = db_query($sql);
            if (db_num_rows($result)>$ppp) addnav("Nächste Seite","weihnachtsmarkt.php?op=schball&op1=mail&limit=".($page+1)."&search=$_POST[search]");
            output("`&An wen wollt ihr denn einen kalten Gruß schicken?`n`n");
            output("<form action='weihnachtsmarkt.php?op=schball&op1=mail' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
            addnav("","weihnachtsmarkt.php?op=schball&op1=mail");
            output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Geschlecht</td></tr>",true);
            for ($i=0;$i<db_num_rows($result);$i++)
            {
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='weihnachtsmarkt.php?op=schball&op1=send&name=".HTMLEntities($row['acctid'])."'>",true);
            output($row['name']);
            output("</a>",true);
            output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
            addnav("","weihnachtsmarkt.php?op=schball&op1=send&name=".HTMLEntities($row['acctid']));
            }
            output("</table>",true);
            }
        else
            {
            output('Du hast heute schon genug Schneebälle verteilt!');
            }
        break;
        case 'send':
        $name=$HTTP_GET_VARS[name];
        $sql1="SELECT * FROM accounts WHERE acctid='$name'";
        $result1 = db_query($sql1);
        $row1 = db_fetch_assoc($result1);
        $session['user']['schneeball'] ++;
        switch(e_rand(1,5))
            {
            case 1:
            case 2:
            case 3:
            $punkt = '1';
            $session['user']['schneerang'] ++;
            $inhalt = ('`&Der Botenjunge von '.$session['user']['name'].' `&holt aus und wirft einen Schneeball auf dich.`n`#Er hat getroffen und seinem Meister damit 1 Punkt gebracht!`n`n`&Viel Spass noch wünscht das Team von Arda!`0');
            $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `&\'s Botenjunge hat seinem Meister `#1 Punkt`& bei ".$row1['name']."`& eingebracht!')";
            db_query($sql) or die(db_error(LINK));
            break;
            case 4:
            $punkt = '2';
            $session['user']['schneerang'] += 2;
            $inhalt = ('`&Der Botenjunge von '.$session['user']['name'].' `&holt aus und wirft einen Schneeball auf dich.`n`#Er hat sehr gut getroffen und seinem Meister damit 2 Punkte gebracht!`n`n`&Viel Spass noch wünscht das Team von Arda!`0');
            $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `&\'s Botenjunge hat seinem Meister `#2 Punkte`& bei ".$row1['name']."`& eingebracht!')";
            db_query($sql) or die(db_error(LINK));
            break;
            case 5:
            $punkt = '-1';
            $session['user']['schneerang'] --;
            $inhalt = ('`&Der Botenjunge von '.$session['user']['name'].' `&holt aus und wirft einen Schneeball auf dich.`n`#Er hat nicht getroffen, seinem Meister wurde 1 Punkt abgezogen!`n`n`&Viel Spass noch wünscht das Team von Arda!`0');
            $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `&\'s Botenjunge hat seinen Meister bei ".$row1['name']."`& enttäuscht - `#ein Minuspunkt!')";
            db_query($sql) or die(db_error(LINK));
            break;
            }
        $mailmessage=$inhalt;
        systemmail($name,"`#`bFrostiger Gruß!`b`2",$mailmessage);
        output("`&Dein Gruß wurde geschickt! Du hast ".$punkt." Punkt/e dafür bekommen!");
        break;
        }
    addnav('Sonstiges');
    addnav('Zurück','village.php');
    break;
    }

page_footer();



?>