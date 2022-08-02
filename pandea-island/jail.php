<?php
/*
LoGD - Gefängniserweiterung
19.05.2004
Matthias "Vanion" Strauch
*/

require_once "common.php";

checkday();

page_header("Der Pranger");


$sql_h= "SELECT acctid FROM shops_owner where shopid=2 and acctid=".$session['user']['acctid']; // Stadtwachen
$result_h=db_query($sql_h);
$row_h = db_fetch_assoc($result_h);

if($session[user][superuser] >= 3 || $session['user']['acctid']==$row_h['acctid']) addnav("Nervenheilanstalt","sanctum.php");


if ($session['user']['jailtime'] == 0) {
    // Wenn man grad freigekommen ist...
    if ($session['user']['location']==9) $session['user']['location']=0;

    addcommentary();
    output("Auf dem Marktplatz ist ein Pranger aufgebaut worden - hier werden diejenigen angeprangert, die sich im Dorf nicht sonderlich beliebt gemacht haben, auf welche Art auch immer.
    Die vorbeikommenden Dorfbewohner beschimpfen die Angeprangerten lautstark, gelegentlich fliegt auch mal ein faules Ei oder eine Tomate.`n`n");
    //$sql = "SELECT name,jailreason FROM accounts WHERE jailtime > 0";
    $sql = "SELECT name,reason FROM jail WHERE freedate > NOW()";
    $result = db_query($sql);

    if(db_num_rows($result) == "0") output("`nDerzeit ist niemand angeprangert.");
    else {
        output("Derzeit am Pranger:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row[name]);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }
    }
    output("`n`n");

    viewcommentary("jail","Verhöhne die Angeprangerten:",25,"höhnt");

    addnav("Zurück zum Dorf","village.php");
}
else {

    $session['user']['location'] = 9;

    if($_GET[act] == "sanctum"){
        addcommentary();
        output("`7Du bist aufgrund deines Verhaltens in die Nervenheilanstalt eingewiesen worden.`nDu solltest dein Bestes versuchen, um heil wieder hier wegzukommen.`nBedenke, die Ärzte beobachten dein Verhalten genau und wenn es nicht der Norm entspricht, bleibst du hier noch etwas länger.`n`n");
        viewcommentary("sanctum_room","Hinzufügen",25,"verteidigt sich");
        addnav("P?Zurück zum Pranger","jail.php");
    }

    elseif ($_GET[act] == "logout") {
        //$session['user']['loggedin']=0;
        //$session=array();
        //redirect("index.php");
        redirect("login.php?op=logout");
    }
    else {
        output("Du bist noch für ".$session['user']['jailtime']." Tag(e) am Pranger! Die vorbeikommenden Dorfbewohner schimpfen auf dich, und eine Tomate verfehlt deinen Kopf nur knapp. Wie konntest du nur in diese Situation geraten...`n`n");

        $sql = "SELECT name,reason FROM jail WHERE freedate > NOW()";
        $result = db_query($sql);

        output("Derzeit am Pranger:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row[name]);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }



        output("`n`n");

        viewcommentary("jail","Dein Klagen geht im Geschrei der Dorfbewohner unter...",25,"schimpft");
        addnav("Nervenheilanstalt","jail.php?act=sanctum");
        addnav("Logout","jail.php?act=logout");

        }
     }
page_footer();
?>