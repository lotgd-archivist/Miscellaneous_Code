
<?php

require_once("common.php");

isnewday(2);



page_header("Wortfilter.");

addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

addnav("W?ZurÃ¼ck zum Weltlichen","village.php");

addnav("Liste aktualisieren","badword.php");

output("Hier kannst du WÃ¶rter festlegen, die das Spiel ausfiltert. Benutze ein * am Anfang oder am Ende ");

output("eines Worts, um Wortkombinationen mit dem Wort zu filtern (wildcard). Die WÃ¶rter werden nur gefiltert, wenn der ");

output("Wortfilter in den Spieleinstellungen aktiviert ist.");

output("<form action='badword.php?op=add' method='POST'>Wort hinzufÃ¼gen: <input name='word'><input type='submit' class='button' value='HinzufÃ¼gen'></form>",true);

output("<form action='badword.php?op=remove' method='POST'>Wort entfernen: <input name='word'><input type='submit' class='button' value='Entfernen'></form>",true);

output("<form action='badword.php?op=test' method='POST'>Wort testen: <input name='word'><input type='submit' class='button' value='Test'></form>",true);



addnav("","badword.php?op=add");

addnav("","badword.php?op=remove");

addnav("","badword.php?op=test");

$sql = "SELECT * FROM nastywords";

$result = db_query($sql);

$row = db_fetch_assoc($result);

$words = explode(" ",$row['words']);

reset($words);



if ($_GET['op']=="add"){

    array_push($words,stripslashes($_POST['word']));

}

if ($_GET['op']=="remove"){

    unset($words[array_search(stripslashes($_POST['word']),$words)]);

}

if ($_GET['op']=="test"){

    output("`7Das Testergebnis lautet: `^".soap($_POST['word'])."`7.  (Wenn der Wortfilter in den Spieleinstellungen deaktiviert ist, wird dieser Test nicht funtkionieren).`n`n");

}

sort($words);

$lastletter="";

while (list($key,$val)=each($words)){

    if (trim($val)==""){

        unset($words[$key]);

    }else{

        if (substr($val,0,1)!=$lastletter){

            $lastletter = substr($val,0,1);

            output("`n`n`^`b" . strtoupper($lastletter) . "`b`@`n");

        }

        output($val." ");

    }

}

if ($_GET['op']=="add" || $_GET['op']=="remove"){

    $sql = "DELETE FROM nastywords";

    db_query($sql);

    $sql = "INSERT INTO nastywords VALUES ('" . addslashes(join(" ",$words)) . "')";

    db_query($sql);

}

page_footer();

?>

