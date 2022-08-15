
<?php

// 15082004

$vampirschutz=1;
require_once "common.php";
if ($session[user][loggedin]) {
    if ($session[user][alive]) {
        addnav("Zurück","list.php");
    } else {
        addnav("Zurück zu den Schatten", "shades.php");
    }
    //addnav("Gerade Online","list.php");
}else{
    addnav("Login Seite","index.php");
    //addnav("Gerade Online","list.php");
}
popup_header("Avatarliste");
$session[user][ort]='Einwohnermeldeliste';
$playersperpage=25;

$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];


if ($_GET['op']=="search"){
    $search="%";
    for ($x=0;$x<strlen($_POST['avatarvorname']);$x++){
        $search .= substr($_POST['avatarvorname'],$x,1)."%";
    }
    $search=" AND avatarvorname LIKE '".addslashes($search)."' ";
    //addnav("List Warriors","list.php");
}else{
    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage,$totalplayers);
    
    $limit=" LIMIT $pageoffset,$playersperpage ";
}
if ($_GET['op']=="searchvorname"){
    $search="%";
    for ($x=0;$x<strlen($_POST['avatarnachname']);$x++){
        $search .= substr($_POST['avatarnachname'],$x,1)."%";
    }
    $search=" AND avatarnachname LIKE '".addslashes($search)."' ";
    //addnav("List Warriors","list.php");
}else{
    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage,$totalplayers);
    
    $limit=" LIMIT $pageoffset,$playersperpage ";
}
if ($_GET['op']=="searchlogin"){
    $search="%";
    for ($x=0;$x<strlen($_POST['login']);$x++){
        $search .= substr($_POST['login'],$x,1)."%";
    }
    $search=" AND login LIKE '".addslashes($search)."' ";
    //addnav("List Warriors","list.php");
}else{
    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage,$totalplayers);
    
    $limit=" LIMIT $pageoffset,$playersperpage ";
}
/*
addnav("Seiten");
for ($i=0;$i<$totalplayers;$i+=$playersperpage){
    addnav("Seite ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","avatarlist.php?page=".($i/$playersperpage+1));
}*/

output('`c`&`bSeiten`b `n');
for($i = 0; $i < $totalplayers; $i += $playersperpage)
{
    $out .= '<a href="avatarlist.php?page='.($i/$playersperpage+1).'">'.($i/$playersperpage+1).'</a> - ';
}
$out = subStr($out, 0, strLen($out) - 3);
output($out.'`n`n`c', true);

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['page']=="" && $_GET['op']==""){
    output("`c`bAvatar-Liste (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers)`b`c");
    $sql = "SELECT `restorepage`,acctid,name,login,avatarnachname,avatarvorname,sex FROM accounts WHERE locked=0 $search ORDER BY avatarvorname ASC";
}else{
    output("`c`bAvatar-Liste (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers)`b`c");
    $sql = "SELECT `restorepage`,acctid,name,login,avatarnachname,avatarvorname,sex FROM accounts WHERE locked=0 $search ORDER BY avatarvorname ASC $limit";
}
if ($session[user][loggedin]){
    output("<form action='avatarlist.php?op=search' method='POST'>Nach Avatarperson (Vorname // Model) suchen: <input name='avatarvorname'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","avatarlist.php?op=search");
    output("<form action='avatarlist.php?op=searchvorname' method='POST'>Nach Avatarperson (Nachname // Künstler) suchen: <input name='avatarnachname'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","avatarlist.php?op=searchvorname");
    output("<form action='avatarlist.php?op=searchlogin' method='POST'>Nach Charaktername suchen: <input name='login'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","avatarlist.php?op=searchlogin");
}


$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);
if ($max>100) {
    output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
}

output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trdark'><td><b>Name</b></td><td><b><img src='images/geschlecht.png' width='20' height='21'></b></td><td><b>Avatarvorname</b></td><td><b>Avatarnachname</b></td></tr>",true);
for($i=0;$i<$max;$i++){
    $row = db_fetch_assoc($result);
    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
    if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
        if($session['user']['prefs']['biopopup']==true){
    if ($session[user][loggedin]) output("<a href='biopopup.php?char=".rawurlencode($row['login'])."' target='_blank' onClick='".popup("biopopup.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']."")).";return false;' onmouseover=\"TagToTip('".$row['acctid']."',TITLEBGCOLOR,'#FFFFFF',BGCOLOR, '#000000',FONTCOLOR, '#000000' ,BORDERWIDTH,1,BORDERCOLOR,'#FFFFFF', TITLEFONTCOLOR,'#000000',TITLE, 'Kurzinformationen über den Charakter')\" onmouseout=\"UnTip()\">",true);
    if ($session[user][loggedin]) addnav("","biopopup.php?char=".rawurlencode($row['login'])."",false,true);
    }else{
    if ($session[user][loggedin]) output("<a href='biopopup.php?char=".rawurlencode($row['login'])."' target='_blank' onClick='".popup("biopopup.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']."")).";return false;' onmouseover=\"TagToTip('".$row['acctid']."',TITLEBGCOLOR,'#FFFFFF',BGCOLOR, '#000000',FONTCOLOR, '#000000' ,BORDERWIDTH,1,BORDERCOLOR,'#FFFFFF', TITLEFONTCOLOR,'#000000',TITLE, 'Kurzinformationen über den Charakter')\" onmouseout=\"UnTip()\">",true);
    if ($session[user][loggedin]) addnav("","biopopup.php?char=".rawurlencode($row['login'])."",false,true);
    }
    output("`".($row[acctid]==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
    if ($session[user][loggedin]) output("</a>",true);
    output("</td>",true);
    output("<td align=\"center\">",true);
    output($row[sex]?"<img src=\"images/female.png\">":"<img src=\"images/male.png\">",true);
        output("</td><td>",true);
       output($row['avatarvorname']);
    output("</td><td>",true);
    output($row['avatarnachname']);
    output("</td>",true);
    output("</tr>",true);
}
output("</table>",true);
popup_footer();
?>

