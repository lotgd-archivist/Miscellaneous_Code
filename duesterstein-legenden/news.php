
<?
require_once "common.php";
if ((int)getsetting("expirecontent",180)>0){
    $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime("-".getsetting("expirecontent",180)." days"))."'";
    //echo $sql;
    db_query($sql);
}
if ($session[user][slainby]!=""){
    page_header("Du wurdest getötet!");
        output("`\$Du wurdest in den ".$session[user][killedin]."`\$ von `%".$session[user][slainby]."`\$ getötet.  Das kostet Dich 5% Deiner Erfahrung, und alles Gold, was Du bei Dir hattest.  Denkst Du nicht, daß es Zeit ist Rache zu nehmen?");
    addnav("Continue",$REQUEST_URI);
    $session[user][slainby]="";
    page_footer();
    //debuglog("Punkt 9a:".date("Y-m-d H:i:s")."");
}else{
    
    if ($session['user']['loggedin']) checkday();
    $newsperpage=50;
    page_header("LoGD News"); 
    if ($session[user][superuser]>=3){ 
        output("`0<form action=\"news.php\" method='POST'>",true); 
        output("[Admin] Meldung manuell eingeben? <input name='meldung' size='40'> ",true); 
        output("<input type='submit' class='button' value='Eintragen'>`n`n",true); 
        addnav("","news.php"); 
        if ($_POST[meldung]){ 
            $sql = "INSERT INTO news(newstext,newsdate,newsdate2,accountid) VALUES ('".addslashes($_POST[meldung])."',NOW(),NOW(),0)"; 
            db_query($sql) or die(db_error($link)); 
            $_POST[meldung]=""; 
        } 
        addnav("","news.php"); 
    }
    //if ($session['user']['loggedin']) debuglog("Punkt 9b:".date("Y-m-d H:i:s")."");
    $offset = (int)$HTTP_GET_VARS[offset];
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
//    page_header("LoGD News");
    $date=date("D, M j, Y",$timestamp);
    
    output("`c`b`!News für den $date".($totaltoday>$newsperpage?" (Items ".($pageoffset+1)." - ".min($pageoffset+$newsperpage,$totaltoday)." of $totaltoday)":"")."`c`b`0");
    
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
        if ($session[user][superuser]>=3){
            output("[ <a href='superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;",true);
            addnav("","superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI']));
        }
        if ($session[user][superuser]<3) output("$row[newstext]`n");
        if ($session[user][superuser]>=3) output("$row[newstext] `T-= $row[newsdate2] =-`n`0");
    }
    if (db_num_rows($result)==0){
        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
        output("`1`b`c Es ist nichts erwähnenswertes passiert. Alles in allem bisher ein langweiliger Tag. `c`b`0");
    }
    //if ($session['user']['loggedin']) debuglog("Punkt 10:".date("Y-m-d H:i:s")."");
    output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
    if ($totaltoday>$newsperpage){
        addnav("Heutige News");
        for ($i=0;$i<$totaltoday;$i+=$newsperpage){
            addnav("Seite ".($i/$newsperpage+1),"news.php?offset=$offset&page=".($i/$newsperpage+1));
        }
        addnav("Anderes");
    }
    //if ($session['user']['loggedin']) debuglog("Punkt 11:".date("Y-m-d H:i:s")."");
    if (!$session[user][loggedin]) {
        addnav("Login Seite", "index.php");
    } else if ($session[user][alive]){
        if($session['user']['school'] > 0) {
            addnav("Zur Schule","school.php");
        } else {
            addnav("Dorfplatz","village.php");
        }
    }else{
        if($session['user']['sex'] == 1) {
            addnav("`!`bDu bist tot, Jane!`b`0");
        } else {
            addnav("`!`bDu bist tot, Jim!`b`0");
        }
        addnav("Einstellungen","prefs.php");
        addnav("Reich der Schatten","shades.php");
        addnav("Log out","login.php?op=mainlogout&wo=0");
        addnav("News");
    }
    addnav("Vorherige News","news.php?offset=".($offset+1));
    if ($offset>0){
        addnav("Nächsten News","news.php?offset=".($offset-1));
    }
    addnav("Über das Spiel","about.php");
    if ($session[user][superuser]>=4){
        addnav("Neuer Tag","newday.php");
    }
    addnav("","news.php");

    page_footer();
    //debuglog("Punkt 12 (news Ende):".date("Y-m-d H:i:s")."");
}
?>


