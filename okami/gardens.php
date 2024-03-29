
<?php

// gardenflirt 1.0 by anpera
// uses 'charisma' entry in database to determine how far a love goes, and 'marriedto' to know who with whom. ;)
// no changes necessary in database
// some changes in newday.php, hof.php, dragon.php, and inn.php required and in user.php optional!
// See http://www.anpera.net/forum/viewforum.php?f=27 for details

// MOD by tcb, 11.5.05: neues Heiratssytem, Details s. tempel.php
// Schaukel-Addon, 26.08.06 by Maris (Maraxxus [-[at]-] gmx.de)

require_once 'common.php';
require_once(LIB_PATH.'profession.lib.php');

page_header('Paradies');
$session[user][standort]="Paradies";

music_set('garten');

if ($_GET['op']=='swing')
{
    music_set('schaukel');
    addcommentary();
    checkday();
    $str_output .= '`c`b`GDie Gartenschaukel`0`b`c
    `n`n`gIm hinteren Teil des Paradies, nahe einer romantischen Laube, befindet sich an mächtigen Pfählen 
  angebracht eine große Schaukel. Sie ist wohl stabil genug, um auch den kräftigsten Troll zu tragen, 
  allerdings bietet sie nur Platz für eine einzige Person. Du kannst dich hier auf den Bänken 
  niederlassen, es dir in der Laube gemütlich machen oder gar einen Schaukelgang wagen.`n`n`0';
    viewcommentary('gardens_swing','Flüstern',5,'flüstert');
    addnav('Schaukeln');
    addnav('Auf die Schaukel','gardens.php?op=swing2');
    addnav('Sonstiges');
}
elseif ($_GET['op']=='swing2')
{
    $str_output .= '`c`b`GDie Gartenschaukel`0`b`c
    `n`n`gDu hüpfst auf die einladend aussehende Schaukel und schaukelst eine Runde.`n`n';
    $chance=e_rand(1,10);
    switch($chance)
    {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
            $str_output .= 'Dabei fühlst du dich wirklich großartig und leicht beschwingt!';
            break;
        case 6:
        case 7:
            if ($session['user']['turns']>0)
            {
                $str_output .= 'Dabei wird dir derart schlecht, dass du die nächste Zeit deinen Bauch halten wirst!`n
        Du `4verlierst`g eine Runde!';
                $session['user']['turns']--;
            }
            else
            {
                $str_output .= 'Dabei wird dir ein wenig schlecht und du beschliesst die Sache langsamer angehen 
        zu lassen.';
            }
            break;
        case 8:
        case 9:
            if ($session['user']['turns']>0)
            {
                $str_output .= 'Dabei fühlst du dich derart beschwingt, dass du neue Kraft für eine `&weitere 
        Runde`g schöpfst!';
                $session['user']['turns']++;
            }
            else
            {
                $str_output .= 'Leider bist du zu müde, um den nötigen Schwung zu finden.';
            }
            break;
        case 10:
            if ($session['user']['turns']>0 && $session['user']['charm']>0)
            {
                $str_output .= 'Bei dem Versuch dich besonders hoch zu schwingen, fällst du von der Schaukel und landest mit dem Gesicht im Matsch!`nSelbstverständlich ist das einer dieser Momente, in denen wirklich JEDER in deine Richtung schaut.`n`4Du verlierst einen Charmepunkt!';
                $session['user']['charm']--;
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'gardens_swing',".$session['user']['acctid'].",': landet beim Versuch besonders hoch zu schaukeln mit dem Gesicht im Matsch!')";
                db_query($sql);
            }
            else
            {
                $str_output .= 'Bei dem Versuch dich besonders hoch zu schwingen, fällst du fast von der 
        Schaukel, kannst dich aber gerade noch so halten.';
            }
            break;
    }
    addnav('Die Schaukel');
    addnav('Verlassen','gardens.php?op=swing');
    addnav('Sonstiges');

}
elseif ($_GET['op']=='flirt1')
{

    $str_output .= get_title('`GFlirten');
    if ($session['user']['seenlover'])
    {
        $sql = "SELECT name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $partner=$row['name'];
        if ($partner=='')
        {
            $partner = $session['user']['sex']?'Seth':'Violet';
        }
        $str_output .= '`gDu wanderst durch das Paradies und bist voller Vorfreude auf eine romantische 
    Begegnung mit deine'.($session['user']['sex']?'m':'r').' Auserwählten, doch deine Gedanken schweifen 
    immer wieder ab. Da du heute bereits einen Flirt mit einer Person hattest, kannst du dich nicht auf 
    dieses Treffen konzentrieren, so dass das gewisse Etwas nun einfach fehlt. Warte lieber bis morgen, 
    wenn du den Kopf wieder frei hast!';
    }
    else
    {

        require_once(LIB_PATH.'jslib.lib.php');

        $charmdiff=$session['user']['dragonkills']*2+23; //neue Charmedifferenz: 23 Basiswert + 2 pro Drachen

        if (isset($_POST['search']) || strlen($_GET['search'])>0)
        {
            if (strlen($_GET['search'])>0)
            {
                $_POST['search']=$_GET['search'];
            }
            $search = str_create_search_string($_POST['search']);
            $search='name LIKE \''.$search.'\' AND ';
        }
        else
        {
            $search='';
        }
        $ppp=25; // Player Per Page to display
        if (!$_GET['limit'])
        {
            $page=0;
        }
        else
        {
            $page=(int)$_GET['limit'];
            addnav('Vorherige Seite','gardens.php?op=flirt1&limit='.($page-1).'&search='.$_POST['search']);
        }
        $limit=($page*$ppp).','.($ppp+1);
        if ($session['user']['marriedto']==4294967295)
        {
            $str_output .= '`gDu denkst nochmal über deine Ehe mit '.($session['user']['sex']?'Seth':'Violet').' nach und überlegst, ob du '.($session['user']['sex']?'ihn':'sie').' in der Kneipe besuchen sollst oder für wen du diese Ehe aufs Spiel setzen würdest.`n';
        }
        if($session['user']['charisma']==4294967295)
        {
            $str_output .= '`gDu überlegst dir, dass du dir mal wieder etwas Zeit für '.($session['user']['sex']?'deinen Mann':'deine Frau').' nehmen solltest. Während du '.($session['user']['sex']?'ihn':'sie').' im Garten suchst, stellst du aber fest, dass der Rest der '.($session['user']['sex']?'Männer':'Frauen').' hier auch nicht zu verachten ist.`n';
        }
        $str_output .= '`gFür wen entscheidest du dich?`n`n`0';
        $str_output .= "<form action='gardens.php?op=flirt1' method='POST'>
        `gNach Name suchen:`0
        <input name='search' value='$_POST[search]'>
        <input type='submit' class='button' value='Suchen'>
        </form>";
        addnav('','gardens.php?op=flirt1');

        $bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML); //unnötigen JOIN vermeiden
        $sql = 'SELECT    accounts.name,
                    accounts.login,
                    accounts.acctid,
                    accounts.loggedin,
                    accounts.laston,
                    accounts.imprisoned,
                    accounts.activated,
                    accounts.expedition,
                    accounts.dragonkills,
                    accounts.sex,
                    accounts.race,
                    accounts.marriedto,
                    accounts.charisma,
                    accounts.charm
                    '.($bool_lockhtml ? ',aei.html_locked' : '').'
                    FROM accounts
                    '.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON accounts.acctid=aei.acctid' : '').'
                    WHERE     '.$search.'
                            (locked=0) AND
                            (sex <> '.$session['user']['sex'].') AND
                            (alive=1 OR hitpoints>0) AND
                            (laston > "'.date('Y-m-d H:i:s',strtotime(date('r').'-7 day')).'" OR (charisma=4294967295 AND accounts.acctid='.$session['user']['marriedto'].') )
                    ORDER BY (accounts.acctid='.$session['user']['marriedto'].') DESC, 
                            charm DESC
                    LIMIT '.$limit;
        $result = db_query($sql);

        $str_output .= "<table border='0' cellpadding='3' cellspacing='0'>
            <tr class='trhead'>
            <th>".($session['user']['sex']?"<img src=\"images/male.gif\">":"<img src=\"images/female.gif\">")."</th>
            <th>Name</th>
            <th>Alter(DK)</th>
            <th>Rasse</th>
            <th>Status
            </th>
            </tr>";
        if (db_num_rows($result)>$ppp)
        {
            addnav('Nächste Seite','gardens.php?op=flirt1&limit='.($page+1).'&search='.$_POST['search']);
        }

        // Rassen abrufen
        $arr_races = db_create_list(db_query('SELECT colname,id FROM races'),'id');

        $count = db_num_rows($result);
        for ($i=0;$i<$count;$i++)
        {
            $row = db_fetch_assoc($result);
            $biolink='bio.php?char='.rawurlencode($row['login']).'&ret='.urlencode($_SERVER['REQUEST_URI']);
            addnav('', $biolink);
            $flirtnum=min($session['user']['charisma'],$row['charisma']);
            $str_output .= '<tr valign="top" class="'.($i%2?'trlight':'trdark').'">
            <td>&nbsp;</td>
            <td>'.jslib_getmenuuserlink( $row, $row, true ).'`0</td>
            <td align="center">'.$row['dragonkills'].'</td>
            <td align="center">'.$arr_races[$row['race']]['colname'].'`0</td>
            <td align="center">';
            if ($session['user']['acctid']==$row['marriedto'] && $session['user']['marriedto']==$row['acctid'])
            {
                if ($session['user']['charisma']==4294967295 && $row['charisma']==4294967295)
                {
                    $str_output .= '`@`bDein'.($session['user']['sex']?' Mann':'e Frau').'!`b`n`n`0';
                }
                else if ($flirtnum==999)
                {
                    $str_output .= '`$Heiratsantrag!`0';
                }
                else
                {
                    $str_output .= '`^'.$flirtnum.' von '.$session['user']['charisma'].' Flirts erwidert!`0';
                }
            }
            else if ($session['user']['acctid']==$row['marriedto'])
            {
                $str_output .= 'Flirtet '.$row['charisma'].' mal mit dir';
            }
            else if ($session['user']['marriedto']==$row['acctid'])
            {
                $str_output .= 'Deine letzten '.$session['user']['charisma'].' Flirts';
            }
            else if ($row['marriedto']==4294967295 || $row['charisma']==4294967295)
            {
                $str_output .= '`q`iVerheiratet`i`0';
            }
            else if ($row['charisma']==999)
            {
                $str_output .= '`q`iVerlobt`i`0';
            }
            else if ($row['charm']>$session['user']['charm']+$charmdiff)
            {
                $str_output .= 'unerreichbar schön';
            }
            else if ($row['charm']<$session['user']['charm']-$charmdiff)
            {
                $str_output .= 'zu unscheinbar';
            }
            else
            {
                $str_output .= '-';
            }
            //$str_output .= '</td><td>[ <a href="'.$biolink.'">Bio</a> | <a href="gardens.php?op=flirt&name='.rawurlencode($row['login']).'">Flirten</a> ]</td></tr>';
            $str_output .= '</td></tr>';
            addnav('','gardens.php?op=flirt&id='.$row['acctid']);
        }
        $str_output .= '</table>';

        $js_add = '
            function JSLIB_FLIRT(){
                window.location.href = "gardens.php?op=flirt&id=" + g_user_menu.m_pl.m_id;
            }';
        $js_afterinit = 'g_user_menu.insertItem(1, new LOTGD.MenuItem( {label: "Flirten", icon: "images/herz.gif", action: JSLIB_FLIRT} ) );';
        jslib_initmenu( $js_add, $js_afterinit );
    }
}

else if ($_GET['op']=='flirt')
{
    $flirt_inc_style='gardens'; 
    //alle Aktionen nach flirt.inc.php ausgelagert. Texte für output und Systemmails hier definieren.
    $flirtmail_subject='`%Gartenflirt!`0';
    $flirtmail_body='`&'.$session['user']['name'].'`6 hat mit dir einige wunderschöne Momente im Paradies verbracht';
    $flirtlocation=' im Paradies ';
    $str_output_noturns .= '`gAls {flirt_name} endlich im Paradies auftaucht, fühlst du dich plötzlich vom vielen Kämpfen so erledigt und geschwächt, dass du es für besser hältst, mit dem Flirten bis morgen zu warten.`nDu hast deine Runden für heute aufgebraucht. ';
    $bool_flirtaffianced=true;
    include ('flirt.inc.php');
}

else if ($_GET['op']=='disband')
{ //Verlobung lösen
    $str_output .= '`gDrum prüfe wer sich ewig bindet, ob sich nicht noch was bess\'res findet.`n`4Du löst deine Verlobung mit '.$session['disband']['oldname'].'`4 auf`g und hoffst, dass '.$session['disband']['newname'].'`0 auf dein Werben reagiert.`n`n(Falls du beim Dinner warst, kannst du jetzt wieder hineingehen)';
    systemmail($session['user']['marriedto'],'`$Trennung!`0','`&'.$session['user']['name'].'`6 erklärt dir kurz und unmissverständlich, dass '.($session['user']['sex']?'sie':'er').' nicht mehr länger mit dir verlobt sein will.`nTraurig stellst du fest dass '.($session['user']['sex']?'sie':'er').' dich für '.$session['disband']['newname'].'`6 verlassen hat.');
    addhistory('`tTrennung von '.$session['user']['name'],1,$session['user']['marriedto']);
    addhistory('`tTrennung von '.$session['disband']['oldname'],1,$session['user']['acctid']);

    user_update(
        array
        (
            'charisma'=>0,
            'marriedto'=>0
        ),
        $session['user']['marriedto']
    );
    
    systemmail($_GET['acctid'],'`%Flirt!`0','`&'.$session['user']['name'].'`6 hat mit dir einige wunderschöne Momente im Paradies verbracht.');
    $session['user']['charisma']=1;
    $session['user']['seenlover']=1;
    $session['user']['marriedto']=$_GET['acctid'];
    unset($session['disband']);
}

else if ($_GET['op']=='su_reset_marriedto')
{ //Bugfix: Jeder Verlobte darf nur 1 User haben der seine acctid in marriedto hat
    $sql='SELECT acctid,marriedto
        FROM accounts
        WHERE charisma >998
        ORDER BY acctid';
    $result=db_query($sql);
    while ($row=db_fetch_assoc($result))
    {
        user_update(
            array
            (
                'charisma'=>0,
                'marriedto'=>0,
                'where'=>'acctid<>'.$row['marriedto'].' AND marriedto='.$row['acctid']
            )
        );

        $db_rows=db_affected_rows();
        if($db_rows>0)
        {
            $str_output.='acctid '.$row['acctid'].': '.$db_rows.' Einträge gelöscht`n';
        }
    }
    $str_output.='Fertig.';
}

else
{
    addcommentary();
    checkday();

    $show_invent = true;

    $str_output .= '`c`b`2Das Paradies`0`b`c`n';
    if (!$session['user']['prefs']['nosounds'])
    {
        $str_output .= '<embed src="media/vogel.wav" width=10 height=10 autostart=true loop=false hidden=true volume=100>';
    }
    $str_output .= '`c`(V`Mo`Pm `@S`gt`8a`&dtplatz her bist du auf einem breiten Weg aus festgetretener Erde an einen Ort gelangt den man in Wolfs Realm nur das Paradies nennt. Saftige grüne Wi`re`Ds`%e`On`n ';
  $str_output .= '`Oe`%r`Ds`rt`&recken sich bis zum Horizont, durchzogen von einem kleinen Fluss der glasklares Wasser führt und übersäht von abertausenden von Wildblumen in den verschied`8e`gn`@s`Pt`Me`(n `n';
  $str_output .= '`(F`Ma`Pr`@b`ge`8n. `&In einiger Entfernung kannst du ein kleines, lichtes Wäldchen entdecken, und hier und da stehen ein paar Sträucher und Büsche, dicht gedrängt wie kleine R`ru`Dd`%e`Ol, `n';
  $str_output .= '`Od`%i`De `rs`&ich gegenseitig Schutz bieten. Je weiter du vordringst, desto mehr Gerüche fliegen dir zu, Schmetterlinge umtanzen dich und der Wind greift mit sachten F`8i`gn`@g`Pe`Mr`(n `n';
  $str_output .= '`(d`Mu`Pr`@c`gh `8d`&ie Blumen. Hier und da siehst du einzelne Bänke ganz verschiedener Beschaffenheit unter Bäumen stehen, und in der Ferne führt eine kleine Brücke über den F`rl`Du`%s`Os,`n '; 
  $str_output .= '`Ok`%u`Dr`rz `&vor der Stelle, wo dieser eine Art flaches Bassin an seiner Seite füllt. Unweit von dir steht zudem eine lichte Ansammlung von Bäumen, jeder mit etwas Ke`8n`gn`@t`Pn`Mi`(s `n';
  $str_output .= '`(w`Mi`Pr`@d `gs`8i`&e als Obstbäume erkennen, von denen jeder nehmen darf. Je mehr du dich umsiehst, um so deutlicher wird dir, dass dieser Ort seinen Namen wirklich zurecht `rt`Dr`%ä`Og.`c';

    if($session['user']['exchangequest']==3) //Tauschquest
    {
        $indate = getsetting('gamedate','0005-01-01');
        $date = explode('-',$indate);
        if ($date[1]==3 && $date[2]<10)
        {
            $str_output.='`n`%Auf einer Bank siehst du ein Mädchen sitzen, welches sinnlose Reime vor sich hin 
      spricht. Sie sieht hübsch aus, aber du kannst dich nicht daran erinnern, sie schon einmal gesehen zu 
      haben.`g ';
            addnav('M?`%Gehe zu dem Mädchen','exchangequest.php');
        }
    }
    $str_output .= '`n`n`0';
    viewcommentary('gardens','Hier flüstern',5,'flüstert');

    //Gartenspecials laden
    spc_get_special('gardens',70,'',array('op'));

addnav('Das Paradies');
addnav('S?Zur Schaukel','gardens.php?op=swing');
addnav('W?Zur Wolkeninsel','wolkeninsel.php');
addnav('Blumenbeet','flowers.php');
addnav('Tiefer ins Paradies','treeoflife.php');

addnav('Zivilisationsränder');
addnav('Palastruine','pre.php');
addnav('Alte Villa','ava.php?op=1');
addnav('Kleiner Weg','kwzt.php');
addnav('Tempel','tempel.php');

addnav('Weite Ebenen');
addnav('Herbstlichte Wiese','hw.php');
addnav('Blumenwiese','blume.php');
addnav('Sturmwiese','sturm.php');

addnav('Augenweiden');
addnav('Flirten','gardens.php?op=flirt1');
addnav('Liebesgarten','lg.php');
addnav('Kristallhöhle','kristallhohle.php');
addnav('Kirschblütenhain','hain.php');

addnav('Azurflächen');
addnav('Gartensee','gsee.php');
addnav('Geheimer Wasserfall','wasserfall.php');
addnav('Heiße Quellen','quelle.php');

addnav('Abenteuerliche Orte');
addnav('Eishöhle','eh.php');
addnav('Sumpf','sumpf.php');
addnav('Steppe','steppe.php');
addnav('Irrgarten','ig.php?op=1');

    if($session['user']['marriedto']>0 && $session['user']['marriedto'] < 4294967295)
    {
addnav('Romanze am Waldsee','forestlake.php');
        if($session['user']['charisma']>=999 && $session['user']['seenlover']==0)
        { //Schnellflirt
            addnav('Quickie','gardens.php?op=flirt&id='.$session['user']['marriedto']);
        }
    }

    //if($access_control->su_check(access_control::SU_RIGHT_DEV)) addnav('marriedto-Einträge prüfen','gardens.php?op=su_reset_marriedto');
    addnav('Zurück');
}

if($_GET['op'])
{
    addnav('G?Zurück zum Garten','gardens.php');
}
addnav('Zurück zur Stadt','village.php');
headoutput($str_output,true);
page_footer();
?>

