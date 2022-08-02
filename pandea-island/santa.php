<?php
require_once "common.php";
page_header("Xmas - Special - ADMIN interface");
if ($_GET['op']=="yes"){
    $name=$_GET['name'];
    $sql2="UPDATE xmas SET richtig=1 WHERE user='".$name."'";
    db_query($sql2);
    output("`@$name wurde zu den Gewinnern hinzugefügt `n`n`0");
}
elseif ($_GET['op']=="no"){
    $name=$_GET['name'];
    $sql2="DELETE * FROM xmas WHERE user='".$name."'";
    db_query($sql2);
    output("`@$name wurde zu den Verlierern hinzugefügt `n`n`0");
}
else{
    output("Auf Grund der Vielfalt von Fragen und den dazugehörigen Antworten lies es sich leider nicht über PHP regeln. Aber es sollte hier schnell machbar sein die richtigen Fragen auszusortieren:`n`n");
    output("<table border=1><tr><td>Name</td><td>Frage</td><td>Antwort des Users:</td><td colspan=2>Aktion</td></tr>",true);
    $sql="SELECT * FROM xmas WHERE richtig=0";
    $result = db_query($sql) or die(db_error(LINK));
    for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr><td>",true);
        output("$row[user]");
        output("</td><td>",true);
        output("$row[frage]");
        output("</td><td>",true);
        output("$row[antwort]");
        output("</td><td>",true);
        output("<a href='http://pandea.schnobbel.de/santa.php?op=yes&name=$row[user]'>Richtig</a>",true);
        output("</td><td>",true);
        output("<a href='http://pandea.schnobbel.de/santa.php?op=no&name=$row[user]'>Falsch</a>",true);
        output("</td></tr>",true);
        addnav("","santa.php?op=yes&name=$row[user]");
        addnav("","santa.php?op=no&name=$row[user]");
    }
    output("</table>",true);
}
addnav("Zurück zur Grotte","superuser.php");
page_footer();
?>