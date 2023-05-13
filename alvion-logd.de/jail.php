
<?php
/*
LoGD - Gefängniserweiterung
19.05.2004
Matthias "Vanion" Strauch

Erweitert, umgebaut, angepaßt:
Chaosmaker <webmaster@chaosonline.de>
http://logd.chaosonline.de
*/

require_once "common.php";


page_header("Der Pranger");
$session['user']['standort']="Der Pranger";
if ($session['user']['jailtime'] == 0) {
    // Wenn man grad freigekommen ist...
    if ($session['user']['location']==9) $session['user']['location']=0;

    addcommentary();
    checkday();

    output("Auf dem Marktplatz ist ein Pranger aufgebaut worden - hier werden diejenigen angeprangert, die sich im Dorf nicht sonderlich beliebt gemacht haben, auf welche Art auch immer.
    Die vorbeikommenden Dorfbewohner beschimpfen die Angeprangerten lautstark, gelegentlich fliegt auch mal ein faules Ei oder eine Tomate.`n`n");
    //$sql = "SELECT name,jailreason FROM accounts WHERE jailtime > 0";
    $sql = "SELECT name,reason FROM jail WHERE freedate > NOW()";
    $result = db_query($sql);

    if(db_num_rows($result) == "0") output("`nDerzeit ist niemand angeprangert.");
    else {
        output("Derzeit am Pranger:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row['name']);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }
    addnav("Aktionen");
    addnav("`TPf`ter`Tde`tap`Tfel `3werfen - `^1 Gold","wurf.php?op=pfap");
    addnav("`3Faule `\$To`4ma`\$te `3werfen - `^2Gold","wurf.php?op=tom");
    addnav("`3Faules `&Ei `3werfen - `^3Gold","wurf.php?op=ei");
    addnav("`3Faules `2Ge`@mü`2se `3werfen - `^3 Gold","wurf.php?op=gem");
    }
    output("`n`n");

    viewcommentary("jail","Verhöhne die Angeprangerten:",25,"höhnt",1,1);

    addnav("Zurück zum Dorf","village.php");
    
}
else {
    checkday();
    $session['user']['standort']="`4Am Pranger`0";
    $session['user']['location'] = 9;
    if ($_GET['act'] == "logout") {
        redirect("login.php?op=logout");
    }
    else {
        output("Du bist noch für ".$session['user']['jailtime']." Tag(e) am Pranger! Die vorbeikommenden Dorfbewohner schimpfen auf dich, und eine Tomate verfehlt deinen Kopf nur knapp. Wie konntest du nur in diese Situation geraten...`n`n");

        $sql = "SELECT name,reason FROM jail WHERE freedate > NOW()";
        $result = db_query($sql);

        output("Derzeit am Pranger:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row['name']);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }
        output("`n`n");

        viewcommentary("jail","Keiner kann dich hören!",25,"schimpft",1,1);
        addnav("a?`^Gesetzestafeln","rules.php");
        addnav("Logout","jail.php?act=logout");
    }
}
page_footer();
?>

