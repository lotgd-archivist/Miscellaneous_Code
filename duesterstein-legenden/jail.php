
<?php
/*
LoGD - Gefängniserweiterung
19.05.2004
Matthias "Vanion" Strauch
*/

require_once "common.php";

checkday();
get_special_var();

page_header("Der Pranger");

// Gargamel inventory system
$session['user']['blockinventory']=1; // don't use the inventory

if ($session[user][locate]!=14){
    $session[user][locate]=14;
    redirect("jail.php");
}

if ($session['user']['jailtime'] <= getsetting(daysalive,0)) {
    // Wenn man grad freigekommen ist...
    if ($session['user']['location']==9) $session['user']['location']=0;
    $session[user][jailtime]=0;
    
    addcommentary();
    output("Auf dem Marktplatz ist ein Pranger aufgebaut worden - hier werden diejenigen angeprangert, die sich im Dorf nicht sonderlich beliebt gemacht haben, auf welche Art auch immer.
    Die vorbeikommenden Dorfbewohner beschimpfen die Angeprangerten lautstark, gelegentlich fliegt auch mal ein faules Ei oder eine Tomate.`n`n");
    $sql = "SELECT name FROM accounts WHERE jailtime > 0";
    $result = db_query($sql);
    
    output("Derzeit am Pranger:");
    if(mysql_num_rows($result) == "0") output("`nNiemand!");
    else {
        while($row = db_fetch_assoc($result)) output("`n".$row[name]);
        }
    output("`n`n");
    
    viewcommentary("jail","Verhöhne die Angeprangerten:",25,"höhnt");
    
    addnav("Zurück zum Dorf","village.php");
}
else {
    output("Du bist am Pranger! Die vorbeikommenden Dorfbewohner schimpfen auf dich, und eine Tomate verfehlt deinen Kopf nur knapp. Wie konntest du nur in diese Situation geraten...`n`n");
    viewcommentary("jail","Dein Klagen geht im Geschrei der Dorfbewohner unter...",25,"schimpft");
    addnav("Logout","login.php?op=mainlogout&wo=9");
}
page_footer();
?> 

