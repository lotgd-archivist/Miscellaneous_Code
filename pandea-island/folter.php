<?php
/*
LoGD - Whispers Folterkammer
10.10.05
Pandi war es
*/

require_once "common.php";

checkday();

page_header("Whispers Kammer");

if ($session['user']['folter'] == 0) {
    // Wenn man grad freigekommen ist...
    if ($session['user']['location']==9) $session['user']['location']=0;

    addcommentary();
    output("`4Du befindest dich in einem düsteren Raum, der durch ein paar Fackeln und eine glimmende Feuerstelle schwach beleuchtet wird. 
Langsam gewöhnen sich deine Augen an das Zwielicht und du erkennst verschiedene Gegenstände: einige Peitschen und Ketten, ein Streckbett, massive Eisenringe in der Wand, Gitterstäbe - und andere Dinge, die du dir lieber nicht genauer ansehen möchtest.`n`n");
    //$sql = "SELECT name,jailreason FROM accounts WHERE jailtime > 0";
    $sql = "SELECT name,reason FROM uhaft WHERE freedate > NOW()";
    $result = db_query($sql);

    if(db_num_rows($result) == "0") output("`nDerzeit ist niemand in U-Haft.");
    else {
        output("In Untersuchungshaft befindet sich:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row[name]);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }
    }
    output("`n`n");

    viewcommentary("folterkammer","Verhöre die Eingesperrten:",25,"höhnt");

    addnav("Zurück zum Wachhaus","shop.php?id=2&");
}
else {

    

    if ($_GET[act] == "logout") {
        //$session['user']['loggedin']=0;
        //$session=array();
        //redirect("index.php");
        redirect("login.php?op=logout");
    }
    else {
        addcommentary();
        output("Du bist noch für ".$session['user']['folter']." Tag(e) in Untersuchhungshaft. Die anwesenden Wachen sehen sehr ernst aus. Wie konntest du nur in diese Situation geraten...`n`n");

        $sql = "SELECT name,reason FROM uhaft WHERE freedate > NOW()";
        $result = db_query($sql);

        output("Derzeit in U-Haft:");
        while($row = db_fetch_assoc($result)) {
            output("`n".$row[name]);
            if ($row['reason']!='') output(" wegen ".$row['reason']);
        }



        output("`n`n");

        viewcommentary("folterkammer","Nur die Wachen können dich hören",25," ");

        addnav("Logout","folter.php?act=logout");

        }
     }
page_footer();
?>