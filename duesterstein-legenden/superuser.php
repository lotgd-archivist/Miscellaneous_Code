
<?
require_once "common.php";
isnewday(2);

addcommentary();

addnav("W?Zurück zum Weltlichen","village.php");
if ($_GET[op]=="newsdelete"){
    $sql = "DELETE FROM news WHERE newsid='$_GET[newsid]'";
    db_query($sql);
    $return = $_GET['return'];
    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
    $return = substr($return,strrpos($return,"/")+1);
    redirect($return);
}
if ($_GET[op]=="commentdelete"){
    $sql = "DELETE FROM commentary WHERE commentid='$_GET[commentid]'";
    db_query($sql);
    $return = $_GET['return'];
    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
    $return = substr($return,strrpos($return,"/")+1);
    if (strpos($return,"?")===false && strpos($return,"&")!==false){
        $x = strpos($return,"&");
        $return = substr($return,0,$x-1)."?".substr($return,$x+1);
    }
    redirect($return);
}

page_header("Admin Grotte");
if ($session[user][locate]!=22){
    $session[user][locate]=22;
    redirect("superuser.php");
}
if ($_GET[op]=="checkcommentary"){
    addnav("G?Zurück zur Grotte","superuser.php");
    viewcommentary("' or '1'='1","X",100);
}else if ($_GET[op] == "bounties") {
    addnav("G?Zurück zur Grotte","superuser.php");
    output("`c`bDie Kopfgeldliste`b`c`n");
    $sql = "SELECT name,alive,sex,level,laston,loggedin,lastip,uniqueid,bounty FROM accounts WHERE bounty>0 ORDER BY bounty DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Kopfgeld</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b>Ort</b></td><td><b>Geschlecht</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></tr>",true);
    for($i=0;$i<db_num_rows($result);$i++){
      $row = db_fetch_assoc($result);
      output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
      output("`^$row[bounty]`0");
      output("</td><td>",true);
      output("`^$row[level]`0");
      output("</td><td>",true);
      output("`&$row[name]`0");
      if ($session[user][loggedin]) output("</a>",true);
      output("</td><td>",true);
      $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
      output($row[location] ?"`3Kneipe`0" :($loggedin ?"`#Online`0" :"`3Die Felder`0"));
      output("</td><td>",true);
      output($row[sex]?"`!Weiblich`0":"`!Männlich`0");
      output("</td><td>",true);
      output($row[alive]?"`1Lebt`0":"`4Tot`0");
      output("</td><td>",true);
      $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
      if (substr($laston,0,2)=="1 ") $laston="1 Tag";
      if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
      if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
      if ($loggedin) $laston="Jetzt";
      output($laston);
      output("</td></tr>",true);
    }
    output("</table>",true); 
}else{
    if ($session[user][sex]){
        output("`^Du tauchst in eine geheime Höhle unter, die nur wenige kennen. Dort wirst du von ");
        output("einigen muskulösen Männern mit nacktem Oberkörper empfangen, die ");
        output("dir mit Palmwedeln entgegen winken und dir anbieten, dich mit Trauben zu füttern, während du auf einer ");
        output("mit Seide bedeckten griechisch-römischen Liege faulenzt.`n`n");
    }else{
        output("`^Du tauchst in eine geheime Höhle unter, die nur wenige kennen. Dort wirst du von ");
        output("einigen spärlich bekleideten Frauen empfangen, die ");
        output("dir mit Palmwedeln entgegen winken und dir anbieten, dich mit Trauben zu füttern, während du auf einer ");
        output("mit Seide bedeckten griechisch-römischen Liege faulenzt.`n`n");
    }
    $time = gametime();
//echo ($time);
$gametimeis=strtotime(date("Y-m-d H:i:s",$time));
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4));
output(" `@Der nächste Spieltag startet in: `^".date("G\\h, i\\m, s\\s \\(\\r\\e\\a\\l\\ \\t\\i\\m\\e\\)",strtotime("1990-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`@");
output("`n`n");
    viewcommentary("superuser","Mit anderen Göttern unterhalten:",25,"sagt");
    addnav("Aktionen");
    addnav("Anfragen","viewpetition.php");
    if ($session[user][superuser]>=3) addnav("K?Aktuelle Kommentare","superuser.php?op=checkcommentary");
    if ($session[user][superuser]>=3) addnav("Häuser","suhouses.php"); // fuer haeuser
    addnav("B?SpielerBiografien","bios.php");
    if (getsetting("avatare",0)==1) addnav("Avatare","avatars.php");
    if ($session[user][superuser]>=3) addnav("Spendenseite","donators.php");
    if ($session[user][superuser]>=3) addnav("Retitler","retitle.php");
    addnav("Kopfgeldliste", "superuser.php?op=bounties");
    if ($session[user][superuser]>=3) addnav("Todoliste", "todolist.php");

    if ($session[user][superuser]>=3){
        addnav("Editoren");
        addnav("User Editor","user.php");
        addnav("E?Monster Editor","creatures.php");
        addnav("Stalltier Editor","mounts.php");
        addnav("Spott Editor","taunt.php");
        addnav("Waffen Editor","weaponeditor.php");
        addnav("Rüstungs Editor","armoreditor.php");
            addnav("Rätsel Editor","riddleditor.php");
        addnav("Wortfilter","badword.php");
    }

    if ($session[user][superuser]>=4){
        addnav("Mechanik");
        addnav("Spieleinstellungen","configuration.php");
        addnav("Herführende URLs","referers.php");
        addnav("Statistiken","stats.php");
        addnav("Multis","logs.php");
    }

}
page_footer();
?>


