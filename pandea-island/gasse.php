<?php
require_once "common.php";
page_header("Dunkle Gasse");
if ($_GET['op']==""){
    addcommentary();
    output("`7Du schnürst deinen Umhang enger, wirfst noch einen letzten, prüfenden Blick zurück um zu sehen, ob dir jemand gefolgt ist, und");
    output("`7gelangst schließlich in eine dunkle Gasse, die als Übergabeort für deine Informationen dient. Hier kannst du deine Nachrichten");
    output("`7über eine alte Box, versteckt hinter einem Stein in einer hohlen Wand, an die Götter weiterleiten und ihnen Bewohner
    mitteilen, die sich als besonders würdig erwiesen oder es nur vorgetäuscht haben.`n`n");
    addnav("RP-Auszeichnung verleihen","gasse.php?op=give");
    addnav("RP-Auszeichnung zurückziehen","gasse.php?op=take");
    addnav("Gespräche","gasse.php?op=kommentare");
    output("`n`n`^Mit anderen Agenten unterhalten`n`0");
    viewcommentary("gasse","Hinzufügen",25);
}else if ($_GET['op']=="give"){
    output("Wer hat deiner Meinung nach eine Rollenspielauszeichnung verdient?");
    output("<form action='gasse.php?op=give2' method='POST'>",true);
    output("`2Name: <input name='contractname'>`n", true);
    output("<input type='submit' class='button' value='Erhöhen'></form>",true);
    addnav("","gasse.php?op=give2");
    addnav("Gasse","gasse.php");
}else if ($_GET['op']=="give2"){
    if ($_GET['subfinal']==1){
        $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."'";
    }else{
        $contractname = stripslashes(rawurldecode($_POST['contractname']));
        $name="%";
        for ($x=0;$x<strlen($contractname);$x++){
            $name.=substr($contractname,$x,1)."%";
        }
        $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name LIKE '".addslashes($name)."'";
    }
    $result = db_query($sql);
    if (db_num_rows($result) == 0) {
        output("Es wurde leider keine Person gefunden.");
    } elseif(db_num_rows($result) > 100) {
        output("Es wurden zuviele Personen gefunden.");
    } elseif(db_num_rows($result) > 1) {
        output("Die Götter stecken dir eine Nachricht zu und du siehst mehrere Namen darauf. Schnell holst du einen Stift heraus und kreist den Namen ein, den du meinst...");
        output("<form action='gasse.php?op=give2&subfinal=1' method='POST'>",true);
        output("`2Person: <select name='contractname'>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select>`n`n",true);
        output("<input type='submit' class='button' value='Zettel weitergeben'></form>",true);
        addnav("","gasse.php?op=give2&subfinal=1");
    } else {
        $row = db_fetch_assoc($result);
        if ($row['rpstars']==5) {
            output("Diese Person hat schon 5 Auszeichnungen. Mehr geht nicht.");
        } else if ($row['login']==$session['user']['login']){
            output("Du kannst dir selbst keine Auszeichnung verleihen. Deine Künste in allen Ehren, aber findest du das nicht etwas egoistisch?");
        } else {
            if ($row['rpstars']>=0 & $row['rpstars']<=4){
                $stars=$row['rpstars']+1;
                $sqlf = "UPDATE accounts SET rpstars = '".$stars."' WHERE login = '".$row['login']."'";
                db_query($sqlf);
                output("Die Auszeichnung wurde verliehen");
                $sqlc = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'gasse_stern',".$session[user][acctid].",'/me `@vergibt einen Stern für gutes RP an ".$row['name'].".`0')";
                db_query($sqlc);
                systemmail($row['acctid'],"`@Auszeichnung","`@Dir wurde eine Auszeichnung für gutes Rollenspiel verliehen.");
            }
        }
    }
    addnav("Gasse","gasse.php");
}else if ($_GET['op']=="take"){
    output("Wem sollte deiner Meinung nach eine Rollenspielauszeichung aberkannt werden?");
    output("<form action='gasse.php?op=take2' method='POST'>",true);
    output("`2Name: <input name='contractname'>`n", true);
    output("<input type='submit' class='button' value='Senken'></form>",true);
    addnav("","gasse.php?op=take2");
    addnav("Gasse","gasse.php");
}else if ($_GET['op']=="take2"){
    if ($_GET['subfinal']==1){
        $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."'";
    }else{
        $contractname = stripslashes(rawurldecode($_POST['contractname']));
        $name="%";
        for ($x=0;$x<strlen($contractname);$x++){
            $name.=substr($contractname,$x,1)."%";
        }
        $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name LIKE '".addslashes($name)."'";
    }
    $result = db_query($sql);
    if (db_num_rows($result) == 0) {
        output("Es wurde leider keine Person gefunden.");
    } elseif(db_num_rows($result) > 100) {
        output("Es wurden zuviele Personen gefunden.");
    } elseif(db_num_rows($result) > 1) {
        output("Die Götter stecken dir eine Nachricht zu und du siehst mehrere Namen darauf. Schnell holst du einen Stift heraus und kreist den Namen ein, den du meinst...");
        output("<form action='gasse.php?op=take2&subfinal=1' method='POST'>",true);
        output("`2Person: <select name='contractname'>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select>`n`n",true);
        output("<input type='submit' class='button' value='Zettel weitergeben'></form>",true);
        addnav("","gasse.php?op=take2&subfinal=1");
    } else {
        $row = db_fetch_assoc($result);
        if ($row['rpstars']==0) {
            output("Diese Person hat keine Auszeichnungen, die du abziehen kannst.");
        } else if ($row['login']==$session['user']['login']){
            output("Behalte deine Auszeichnungen ruhig.");
        } else {
            if ($row['rpstars']>0 & $row['rpstars']<=5){
                $stars=$row['rpstars']-1;
                $sqlf = "UPDATE accounts SET rpstars = '".$stars."' WHERE login = '".$row['login']."'";
                db_query($sqlf);
                output("Die Auszeichnung wurde zurückgenommen.");
                $sqlc = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'gasse_stern',".$session[user][acctid].",'/me `$ entzieht ".$row['name']." `0`$ einen Stern.`0')";
                db_query($sqlc);
                systemmail($row['acctid'],"`$Auszeichnung","`$Dir wurde eine Rollenspielauszeichnung aberkannt.");
            }
        }
    }
    addnav("Gasse","gasse.php");
}else if ($_GET['op']=="kommentare"){
    output("`4Achte auf den Unterschied zwischen Spieler- und Charakterwissen, wenn du dir Kommentare durchliest, die du sonst nicht sehen könntest. Du willst ja auch nicht auffliegen...`n`n`n`0");
    $sections = array('academy','battlearena','Courtyard','darkhorse','dragonvally','gardens',
                        'grassyfield','hunterlodge','inn','jail','motd','pvparena','shade','shop-%',
                        'veterans','village','well','goldenegg','ritual','cardhouse'); // ,'house-%','private-%' <- rausgenommen weil man als agent ja nicht alles sehen sollte (warum auch immer oO)
    $sectionfind = implode("' OR section LIKE '",$sections);
    output('<form action="gasse.php?op=kommentare" method="post">',true);
    addnav('','gasse.php?op=kommentare');
    output('Zeige Kommentare: ');
    output('<select name="subop" size="1"><option value="">--- Bitte auswählen ---</option>',true);
    $sql = 'SELECT section FROM commentary WHERE section LIKE \''.$sectionfind.'\' GROUP BY section ORDER BY section ASC';
    $result = db_query($sql);
    while ($row2 = db_fetch_assoc($result)) {
        $komm=$row2['section'];
        if ($row2['section']=="hunterlodge") $komm="Jägerhütte";
        if ($row2['section']=="village") $komm="Dorfplatz";
        if ($row2['section']=="academy") $komm="Trainingslager";
        if ($row2['section']=="darkhorse") $komm="Dark Horse Taverne";
        if ($row2['section']=="dragonvally") $komm="Drachental";
        if ($row2['section']=="gardens") $komm="Garten";
        if ($row2['section']=="inn") $komm="Kneipe";
        if ($row2['section']=="jail") $komm="Pranger";
        if ($row2['section']=="shade") $komm="Unterwelt";
        if ($row2['section']=="well") $komm="Brunnen";
        if ($row2['section']=="ritual") $komm="Ritualplatz";
        if ($row2['section']=="cardhouse") $komm="Kartenhaus-Zelt";
        output('<option value="'.$row2['section'].'"'.($_POST['subop']==$row2['section']?' selected="selected"':'').'>'.$komm.'</option>',true);
       }
       output('</select> <input type="submit" value="anzeigen" class="button">`n`n',true);
       if ($_POST['subop']!=''){
           $subop = $_POST['subop'];
        $REQUEST_URI .= '&subop='.$_POST['subop'];
        $_SERVER['REQUEST_URI'] .= '&subop='.$_POST['subop'];
           viewcommentary($subop,'Die Eingabe hier funktioniert nicht, lässt sich aber scripttechnisch nicht entfernen...',10,'sagt',true);
       }
       addnav("Gasse","gasse.php");
}
addnav("Zum Dorfplatz","village.php");//damit man immer zurück kann und nicht über petitions gehen muss ;-)
page_footer();
?> 