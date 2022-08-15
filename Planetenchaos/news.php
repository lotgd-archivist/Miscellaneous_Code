
<?php

require_once 'common.php';

if ($session['user']['slainby'] != '')

    {

    page_header('Du wurdest besiegt!');

    output('`$Du wurdest in '.$session['user']['killedin'].'`$ von `%'.$session['user']['slainby'].'`$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht es ist Zeit fÃ¼r Rache?');

    addnav('Weiter',$REQUEST_URI);

    $session['user']['slainby'] = '';

    page_footer();

    }

else

    {

    if ($session['user']['loggedin'])

        checkday();

    $newsperpage = 50;

    page_header('Neuigkeiten');

    if ($session['user']['superuser'] == 3)

        {

        rawoutput('<form action="news.php" method="POST">[Admin] Meldung manuell eingeben? <input name="meldung" size="40"> <button>Eintragen</button></ br></ br>');

        addnav('','news.php');

        if ($_POST['meldung'])

            {

            db_query('INSERT INTO news (newstext, newsdate, accountid) VALUES ("'.addslashes($_POST['meldung']).'", NOW(), 0)');

            $_POST['meldung'] = '';

            }

        }

    if (!$session['user']['loggedin'])

        addnav('Login','index.php');

    elseif ($session['user']['alive'])

        addnav('Brunnenplatz','village.php');

    else

        {

        if($session['user']['sex'] == 1)

            addnav('`!`bDu bist tot, Jane!`b`0');

        else

            addnav('`!`bDu bist tot, Jim!`b`0');

        addnav('Profil','prefs.php');

        addnav('Schattenwelt','shades.php');

        addnav('~*~');

        addnav('Log out','login.php?op=logout');

        //addnav("News");

        }

    addnav('Ãœber das Spiel','about.php');

    if ($session['user']['superuser']){

        addnav("Neuer Tag","newday.php");

    }

    $offset = (int)$_GET[offset];

    $timestamp=strtotime((0-$offset)." days");

    $sql = "SELECT count(newsid) AS c FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."'";

    $result = db_query($sql);

    $row = db_fetch_assoc($result);

    $totaltoday=$row['c'];

    $pageoffset = (int)$_GET['page'];

    if ($pageoffset>0) $pageoffset--;

    $pageoffset*=$newsperpage;

    $sql = "SELECT * FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."' ORDER BY newsid DESC LIMIT $pageoffset,$newsperpage";

    $result = db_query($sql) or die(db_error(LINK));

    $date=date("D, M j, Y",$timestamp);

    

    output("`c`b`!News fÃ¼r $date".($totaltoday>$newsperpage?" (Meldungen ".($pageoffset+1)." - ".min($pageoffset+$newsperpage,$totaltoday)." von $totaltoday)":"")."`c`b`0");

    

    for ($i=0;$i<db_num_rows($result);$i++){

        $row = db_fetch_assoc($result);

        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");

        if ($session['user']['superuser']>=3){

            output("[ <a href='superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;",true);

            addnav("","superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI']));

        }

        output($row['newstext'].'`n');

    }

    if (db_num_rows($result)==0){

        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");

        output("`1`b`c Es ist nichts erwÃ¤hnenswertes passiert. Alles in allem bisher ein langweiliger Tag. `c`b`0");

    }

    output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");

    if ($totaltoday>$newsperpage){

        addnav("Heutige News");

        for ($i=0;$i<$totaltoday;$i+=$newsperpage){

            addnav("Seite ".($i/$newsperpage+1),"news.php?offset=$offset&page=".($i/$newsperpage+1));

        }

        //addnav("Sonstiges");

    }

/*

    if (!$session['user']['loggedin']) {

        addnav("Login", "index.php");

    } else if ($session['user']['alive']){

        addnav("Dorfplatz","village.php");

    }else{

        if($session['user']['sex'] == 1) {

            addnav("`!`bDu bist tot, Jane!`b`0");

        } else {

            addnav("`!`bDu bist tot, Jim!`b`0");

        }

        addnav("Profil","prefs.php");

        addnav("Land der Schatten","shades.php");

        addnav("Log out","login.php?op=logout");

        addnav("News");

    }

*/

    addnav("Vorherige News","news.php?offset=".($offset+1));

    if ($offset>0){

        addnav("NÃ¤chste News","news.php?offset=".($offset-1));

    }

/*

    addnav("Ãœber das Spiel","about.php");

    if ($session['user']['superuser']){

        addnav("Neuer Tag","newday.php");

    }

*/



    page_footer();

}

?>

