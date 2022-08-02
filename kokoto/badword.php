<?
require_once "common.php";

isnewday(4);

page_header("Wortfilter.");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Liste aktualisieren","badword.php");
output('Hier kannst du Wörter festlegen, die das Spiel ausfiltert. Benutze ein * am Anfang oder am Ende eines Worts, um Wortkombinationen mit dem Wort zu filtern (wildcard). Die Wörter werden nur gefiltert, wenn der Wortfilter in den Spieleinstellungen aktiviert ist.');

output("<form action='badword.php?op=add' method='POST'>Wort hinzufügen: <input name='word'><input type='submit' class='button' value='Hinzufügen'></form> <form action='badword.php?op=remove' method='POST'>Wort entfernen: <input name='word'><input type='submit' class='button' value='Entfernen'></form> <form action='badword.php?op=test' method='POST'>Wort testen: <input name='word'><input type='submit' class='button' value='Test'></form>",true);

allownav('badword.php?op=add');
allownav('badword.php?op=remove');
allownav('badword.php?op=test');
$sql = "SELECT * FROM nastywords";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$words = explode(" ",$row['words']);
reset($words);

if ($_GET['op']=="add"){
    array_push($words,stripslashes($_POST['word']));
}
if ($_GET['op']=="remove"){
    unset($words[array_search(stripslashes($_POST['word']),$words)]);
}
if ($_GET['op']=="test"){
    output("`7Das Testergebnis lautet: `^".soap($_POST['word'])."`7.  (Wenn der Wortfilter in den Spieleinstellungen deaktiviert ist, wird dieser Test nicht funtkionieren).`n`n");
}
sort($words);
$lastletter="";
foreach($words as $key => $val){

    if (trim($val)==""){
        unset($words[$key]);
    }else{
        if (substr_c($val,0,1)!=$lastletter){
            $lastletter = substr_c($val,0,1);
            output("`n`n`^`b" . strtoupper_c($lastletter) . "`b`@`n");
        }
        output($val." ");
    }
}
if ($_GET['op']=="add" || $_GET['op']=="remove"){
    $sql = "DELETE FROM nastywords";
    db_query($sql);
    $sql = "INSERT INTO nastywords VALUES ('" . mysql_real_escape_string(join(" ",$words)) . "')";
    db_query($sql);
}
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>