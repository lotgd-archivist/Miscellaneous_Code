
<?php

require_once("common.php");

isnewday(2);

addcommentary();



if ($session['user']['jail']>0){

    addnav("ZurÃ¼ck zum Pranger","jail2.php");

}else{

    addnav("W?ZurÃ¼ck zum Weltlichen","village.php");

}

if ($_GET['op']=="newsdelete"){

    $sql = "DELETE FROM news WHERE newsid='{$_GET['newsid']}'";

    db_query($sql);

    $return = $_GET['return'];

    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);

    $return = substr($return,strrpos($return,"/")+1);

    redirect($return);

}

if ($_GET['op']=="commentdelete"){

    $sql = "DELETE FROM commentary WHERE commentid='{$_GET['commentid']}'";

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

if ($_GET['op']=="dbrepair"){

    $sql="REPAIR TABLE `accounts`";

    db_query($sql);

    $sql="REPAIR TABLE `bans`";

    db_query($sql);

    $sql="REPAIR TABLE `commentary`";

    db_query($sql);

    $sql="REPAIR TABLE `debuglog`";

    db_query($sql);

    $sql="REPAIR TABLE `faillog`";

    db_query($sql);

    $sql="REPAIR TABLE `houses`";

    db_query($sql);

    $sql="REPAIR TABLE `items`";

    db_query($sql);

    $sql="REPAIR TABLE `mail`";

    db_query($sql);

    $sql="REPAIR TABLE `motd`";

    db_query($sql);

    $sql="REPAIR TABLE `news`";

    db_query($sql);

    $sql="REPAIR TABLE `petitions`";

    db_query($sql);

    $sql="REPAIR TABLE `pollresults`";

    db_query($sql);

    $sql="REPAIR TABLE `pvp`";

    db_query($sql);

    $sql="REPAIR TABLE `referers`";

    db_query($sql);

    $sql="REPAIR TABLE `settings`";

    db_query($sql);

    output("Tabellen gefixt.`n`n");

}





page_header("Admin Grotte");

if ($_GET['op']=="checkcommentary"){

    addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

    viewcommentary("' or '1'='1","X",100);

}else if ($_GET['op'] == "bounties") {

    addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

    output("`c`bDie Kopfgeldliste`b`c`n");

    $sql = "SELECT name,alive,sex,level,laston,loggedin,lastip,uniqueid,bounty FROM accounts WHERE bounty>0 ORDER BY bounty DESC";

    $result = db_query($sql) or die(sql_error($sql));

    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);

    output("<tr class='trhead'><td><b>Kopfgeld</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b>Ort</b></td><td><b>Geschlecht</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></tr>",true);

    for($i=0;$i<db_num_rows($result);$i++){

      $row = db_fetch_assoc($result);

      output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);

      output("`^{$row['bounty']}`0");

      output("</td><td>",true);

      output("`^{$row['level']}`0");

      output("</td><td>",true);

      output("`&{$row['name']}`0");

      if ($session['user']['loggedin']) output("</a>",true);

      output("</td><td>",true);

      $loggedin=(strtotime(date("Y-m-d H:i:s")) - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);

      switch($row['location']){

          case 1:

          output("`3Kneipe`0");

          break;

          case 2:

          output("`2Haus`0");

          break;

          default:

          output($loggedin?"`#Online`0" :"`3Die Felder`0");

          break;

      }

      output("</td><td>",true);

      output($row['sex']?"`!Weiblich`0":"`!MÃ¤nnlich`0");

      output("</td><td>",true);

      output($row['alive']?"`1Lebt`0":"`4Tot`0");

      output("</td><td>",true);

      $laston=round((strtotime(date("Y-m-d H:i:s"))-strtotime($row['laston'])) / 86400,0)." Tage";

      if (substr($laston,0,2)=="1 ") $laston="1 Tag";

      if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";

      if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("Y-m-d H:i:s")."-1 day"))) $laston="Gestern";

      if ($loggedin) $laston="Jetzt";

      output($laston);

      output("</td></tr>",true);

    }

    output("</table>",true);

}else{

    if ($session['user']['sex']){

        output("`^Du tauchst in eine geheime HÃ¶hle unter, die nur wenige kennen. Dort wirst du von ");

        output("einigen muskulÃ¶sen MÃ¤nnern mit nacktem OberkÃ¶rper empfangen, die ");

        output("dir mit Palmwedeln entgegen winken und dir anbieten, dich mit Trauben zu fÃ¼ttern, wÃ¤hrend du auf einer ");

        output("mit Seide bedeckten griechisch-rÃ¶mischen Liege faulenzt.`n`n");

    }else{

        output("`^Du tauchst in eine geheime HÃ¶hle unter, die nur wenige kennen. Dort wirst du von ");

        output("einigen spÃ¤rlich bekleideten Frauen empfangen, die ");

        output("dir mit Palmwedeln entgegen winken und dir anbieten, dich mit Trauben zu fÃ¼ttern, wÃ¤hrend du auf einer ");

        output("mit Seide bedeckten griechisch-rÃ¶mischen Liege faulenzt.`n`n");

    }

    viewcommentary("superuser","Mit anderen GÃ¶ttern unterhalten:",25,"sagt");

    addnav("Aktionen");

    addnav("Anfragen","viewpetition.php");

    if ($session['user']['superuser']>=3) addnav("K?Aktuelle Kommentare","superuser.php?op=checkcommentary");

    addnav("B?Spieler Biografien","bios.php");

    addnav("Schwarzes Brett","innboard.php");

    if (getsetting("avatare",0)==1) addnav("Avatare","avatars.php");

    if ($session['user']['superuser']>=3) addnav("Spendenseite","donators.php");

    if ($session['user']['superuser']>=3) addnav("Retitler","retitle.php");

    if ($session['user']['superuser']>=3) addnav("Faillog & Mail","logs.php");

    if ($session['user']['superuser']>=2) addnav("Datenbank reparieren","superuser.php?op=dbrepair");

    addnav("Kopfgeldliste", "superuser.php?op=bounties");



    addnav("Editoren");

    if ($session['user']['superuser']>=3) addnav("User Editor","user.php");

    addnav("E?Monster Editor","creatures.php");

    if ($session['user']['superuser']>=3) addnav("Stalltier Editor","mounts.php");

    if ($session['user']['superuser']>=2) addnav("Item Editor","itemeditor.php");

    //if ($session['user']['superuser']>=2) addnav("HandelsdÃ¶rfer","tradervillages.php?op=admin");

    addnav("Spott Editor","taunt.php");

    addnav("Waffen Editor","weaponeditor.php");

    addnav("RÃ¼stungs Editor","armoreditor.php");

    if ($session['user']['superuser']>=2) addnav("RÃ¤tsel Editor","riddleditor.php");

    if ($session['user']['superuser']>=3) addnav("Hausmeister","suhouses.php");

    addnav("Bibliothek","sulib.php");

    addnav("Wortfilter","badword.php");



    addnav("Mechanik");

    if ($session['user']['superuser']>=3) addnav("Spieleinstellungen","configuration.php");

    addnav("HerfÃ¼hrende URLs","referers.php");

    addnav("Statistiken","stats.php");

    if ($session['user']['superuser']>=3) addnav("Mass Email","email.php");



}

page_footer();

?>

