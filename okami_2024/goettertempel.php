
<?

/* 
 Tempel der Götter (c)by Ventus
 Erstmals erschienen auf www.Elfen-Portal.de
 Tempus = Sharem
 Mielikki = Shira
 Shar = Kiri
 Deneir = Shiana
*/

require_once "common.php";

if ($_GET[op]=="") {
    addcommentary();
    checkday();
    page_header("Gottes Tempel.");
    $session[user][standort]="Gottes Tempel";
    
    output("`b`c`2Der Saal:`0`c`b");
    output("`c`n`n`&Du betrittst den Tempel der Götter von Wolfsrealm. Du kannst dich für einen der drei Götter entscheiden
    und jeden Tag zu ihm beten. Doch bedenke deine Wahl gut, den einen um entscheiden ist nicht mehr 
    möglich. Um mehr über die Götter zu erfahren trete zu ihnen.`c");
    output("`n`n`n`lMit anderen flüster:`n`n");
    viewcommentary("Gott","flüstern",5);
    output("`n`n");
    viewcommentary("Tempel","Flüstern:",30,"sagt");
                
                
                addnav("Zum Oberpriester gehen","goettertempel.php?op=priester");
                addnav("Den Priester im roten Gewand anreden","goettertempel.php?op=roterpriester");
                addnav("Den Priester im grünen Gewand anreden","goettertempel.php?op=gruenerpriester"); 
                addnav("Den Priester im schwarzen Gewand anreden","goettertempel.php?op=schwarzerpriester");
                addnav("Den Priester im sandfarbenen Gewand anreden","goettertempel.php?op=sandpriester");
                addnav("Zurück","kir.php");

}

if ($_GET[op]=="priester") {

    checkday();
    page_header("Gottes Tempel.");
    output("`b`c`2Der Saal:`0`c`b");

if($session['user']['gottjanein']==0) output("`n`%Du sprichst den Oberpriester an, aber er antwortet dir nicht. Er zeigt nur Stumm auf ein Buch. Als du dir das Buch näher ansiehst, stellst du fest, dass hier tausende Leute eingetragen sind. Hinter ihren Namen stehen ihre Gottheiten. nachdem du das gesehen hast, fragst du dich, warum DU eigentlich keinen Gott vererst. Was tun?`0");

if($session['user']['gottjanein']==1) output("Der Priester scheint nicht answesend zu sein, du suchst ihn vergeblich!");
    output("`n`QMit den anderen anwesenden flüstern`0");
    output("`n`n");
    viewcommentary("Tempel","Flüstern:",30,"sagt");

                if($session['user']['gottjanein']==0) addnav("Einen Gott auswählen","gottwahl.php");
                addnav("Zurück","goettertempel.php");
}



if ($_GET[op]=="roterpriester") {

    checkday();
    page_header("Tempel der Götter.");
 
    output("`b`c`2Der rote Priester:`0`c`b");
    

if ($session['user']['gott']==1){
output("`n`%Du sprichst den Priester im roten Gewand an.`0");
output("`nAh, ein Sohn des Sharem! Herzlich willkommen! Du bist doch sicher gekommen um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du wiedersprechen kannst fängt er an zu reden:?`0");
output("`n`4 Sharem, 
kommt noch`0");
addnav("Zurück","goettertempel.php");


}else{

output("`n`%Du sprichst den Priester im roten Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");
addnav("Zurück","goettertempel.php");
}
}

if ($_GET[op]=="gruenerpriester") {
    checkday();
    page_header("Tempel der Götter.");

    output("`b`c`2Der grüne Priester:`0`c`b");

if ($session['user']['gott']==2){
output("`n`%Du sprichst den Priester im grünen Gewand an.`0");
output("`nAh, ein Sohn der Shira! Herzlich willkommen! Du bist doch sicher gekommen um etwas über deine Göttin zu erfahren?`0");
output("`nBevor du wiedersprechen kannst fängt er an zu reden:?`0");
output("`n`gShira, 
kommt noch`0");
addnav("Zurück","goettertempel.php");

}else{

output("`n`%Du sprichst den Priester im grünen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}

if ($_GET[op]=="schwarzerpriester") {

    checkday();
    page_header("Tempel der Götter.");
    output("`b`c`2Der schwarze Priester:`0`c`b");

if ($session['user']['gott']==3){
output("`n`%Du sprichst den Priester im schwarzen Gewand an.`0");
output("`nAh, ein Sohn der Kiri! Herzlich willkommen! Du bist doch sicher gekommen um etwas über deinen Göttin zu erfahren?`0");
output("`nBevor du wiedersprechen kannst fängt er an zu reden:?`0");
output("`LKiri, 
kommt noch`0");
addnav("Zurück","goettertempel.php");

}else{

output("`n`%Du sprichst den Priester im schwarzen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}

if ($_GET[op]=="sandpriester") {

    checkday();
    page_header("Tempel der Götter.");
 
    output("`b`c`2Der sandfarbene Priester:`0`c`b");

if ($session['user']['gott']==4){
output("`n`%Du sprichst den Priester im sandfarbenen Gewand an.`0");
output("`nAh, ein Sohn des Shianas! Herzlich willkommen! Du bist doch sicher gekommen um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du wiedersprechen kannst fängt er an zu reden:?`0");
output("`TShiana, 
kommt noch`0");
addnav("Zurück","goettertempel.php");

    //output("`n");

}else{

output("`n`%Du sprichst den Priester im sandfarbenen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}


page_footer();
?> 

