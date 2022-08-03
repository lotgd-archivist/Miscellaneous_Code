<?php

// Erstellt von JayOlino
require_once "common.php";

//Einstellungen für die Punkte
$diarypoint = getsetting("rpdiary", 10);
$petpoint = getsetting("rppetbio", 10);
$racepoints = getsetting("rprasse", 10);
$bespoints = getsetting("raumbesch", 10);
$raumpoints = getsetting("rpraum",10);

page_header("Der RP-Punkte-Shop");

checkday();

// HauptNavigation
addnav("Navigation");
addnav("Zurück", "prefs.php");

//SeitenInhalt
output("Willkommen im RP-Punkte-Shop`n`n");
output("Hier kannst du für deine RP-Punkte zuätzliche Funktionen für deinen Account freischalten.`n`n");

if ($HTTP_GET_VARS[op] == "petbio") {
    if ($session[user][petbio] == 0) {
        if ($session[user][rppunkte] >= $petpoint) {
            $session[user][petbio] = 1;
            $session[user][rppunkte] = ($session[user][rppunkte] - $petpoint);
            output("Dein Einkauf war erfolgreich! Viel Spaß mit der Biografie-Möglichkeit für dein Tier");
        }
    }
    else {
        $session[user][petbio] = 0;
        $session[user][rppunkte] = ($session[user][rppunkte] + $petpoint);
        output("Rückgabe erfolgreich!!");
    }
    addnav("Zurück zum Shop", "rpstore.php");

}
else if ($HTTP_GET_VARS[op] == "diary") {
    if ($session[user][diary] == 0) {
        if ($session[user][rppunkte] >= $diarypoint) {
            $session[user][diary] = 1;
            $session[user][rppunkte] = ($session[user][rppunkte] - $diarypoint);
            output("Dein Einkauf war erfolgreich! Viel Spaß mit deinem Tagebuch");
        }
    }
    else {
        $session[user][diary] = 0;
        $session[user][rppunkte] = ($session[user][rppunkte] + $diarypoint);
        output("Rückgabe erfolgreich!!");
    }
    addnav("Zurück zum Shop", "rpstore.php");

}

else if ($HTTP_GET_VARS[op] == "rprasse") {
        if ($session[user][rppunkte] >= $racepoints) {
      
            $session[user][rppunkte] = ($session[user][rppunkte] - $racepoints);
            output("Dein Einkauf war erfolgreich! Ein Admin wird sich demnächst mit dir in Verbindung setzen und alles mit dir besprechen.");
             $sql = "INSERT INTO petitions (author,date,body,pageinfo) VALUES (" . ( int )$session[user][acctid] . ",now(),\"Rasse bitte besprechen\",\"-\")";
             db_query($sql);
        }
    
    addnav("Zurück zum Shop", "rpstore.php");

}
else if ($HTTP_GET_VARS[op] == "beschreib") {
        if ($session[user][rppunkte] >= $bespoints) {
           
            $session[user][rppunkte] = ($session[user][rppunkte] - $bespoints);
            output("Dein Einkauf war erfolgreich! Ein Admin wird sich demnächst mit dir in Verbindung setzen und alles mit dir besprechen.");
             $sql = "INSERT INTO petitions (author,date,body,pageinfo) VALUES (" . ( int )$session[user][acctid] . ",now(),\"Raumbeschreibung bitte besprechen\",\"-\")";
             db_query($sql);
        }
     addnav("Zurück zum Shop", "rpstore.php");

}
else if ($HTTP_GET_VARS[op] == "raum") {
        if ($session[user][rppunkte] >= $raumpoints) {
           
            $session[user][rppunkte] = ($session[user][rppunkte] - $raumpoints);
            output("Dein Einkauf war erfolgreich! Ein Admin wird sich demnächst mit dir in Verbindung setzen und alles mit dir besprechen.");
             $sql = "INSERT INTO petitions (author,date,body,pageinfo) VALUES (" . ( int )$session[user][acctid] . ",now(),\"Raumkauf bitte besprechen\",\"-\")";
             db_query($sql);
        }
    
    addnav("Zurück zum Shop", "rpstore.php");

}

else {

    output("`n`nFreischalbare Funktionen: `n`n");
    addnav("Freischaltbars");
    //Funktionen des Shops
    output("<div id=upgrades>", true);

    output("<div>
                <table border='0' cellpadding='0' cellspacing='0'>
                    <colgroup>
                        <col width='5%'>
                        <col width='95%'>
                    </colgroup>
                    <tr>
                        <td align='center'>&#149;</td>
                        <td>
                        Die Tier-Biografie für " . $petpoint . " RP-Punkte freischalten`n
                            Hierbei wird die Möglichkeit geschaffen eine Biografie für dein Tier hinterlegen zu können. Diese ist dann für dich und alle anderen ersichtlich.
                        </td>
                    </tr>
                    <tr>
                    <td><br></td><td></td>
                    </tr>
                    <tr>
                        <td align='center'>&#149;</td>
                        <td>
                        Das Tagebuch für " . $diarypoint . " RP-Punkte freischalten`n
                        Hierbei wird die Möglichkeit geschaffen ein Tagebuch für deinen Charakter führen zu können.Dieses ist dann für dich und alle anderen ersichtlich.
                        </td>
                    </tr>
                     <tr>
                    <td><br></td><td></td>
                    </tr>
                    <tr>
                        <td align='center'>&#149;</td>
                        <td>
                        Die eigene Rasse für " . $racepoints . " RP-Punkte freischalten`n
                            Hierbei wird die Möglichkeit geschaffen eine eigene Rasse zu erstellen. Diese ist ganz individuell und dann für dich und alle anderen ersichtlich.
                        </td>
                    </tr>
                     <tr>
                    <td><br></td><td></td>
                    </tr>
                    <tr>
                        <td align='center'>&#149;</td>
                        <td>
                        Eine eigene Raumbeschreibung für " . $bespoints . " RP-Punkte kaufen`n
                            Hierbei wird die Möglichkeit geschaffen eine eigene Raumbeschreibung zu erstellen. Diese ist ganz individuell und dann für dich und alle anderen ersichtlich.
                        </td>
                    </tr>
                     <tr>
                    <td><br></td><td></td>
                    </tr>
                    <tr>
                        <td align='center'>&#149;</td>
                        <td>
                        Eine eigener Raum für " . $raumpoints . " RP-Punkte kaufen`n
                            Hierbei wird die Möglichkeit geschaffen einen eigenen Raum zu erstellen. Dieser ist ganz individuell und dann für dich und alle anderen ersichtlich.
                        </td>
                    </tr>
            </table>
    </div><br>", true);
    //Nav area für die einkäufe
    if ($session[user][rppunkte] >= $petpoint && $session[user][petbio] == 0) {
        addnav("Tier-Biografie`n(" . $petpoint . " RP-Punkte)", "rpstore.php?op=petbio");
    }
    else if ($session[user][rppunkte] >= $petpoint) {
        addnav("Tier-Biografie`nweggeben`n(" . $petpoint . " RP-Punkte)", "rpstore.php?op=petbio");
    }
    if ($session[user][rppunkte] >= $diarypoint && $session[user][diary] == 0) {
        addnav("Tagebuch`n(" . $diarypoint . " RP-Punkte)", "rpstore.php?op=diary");
    }
    else if ($session[user][rppunkte] >= $diarypoint) {
        addnav("Tagebuch`nweggeben`n(" . $diarypoint . " RP-Punkte)", "rpstore.php?op=diary");
    }
     if ($session[user][rppunkte] >= $racepoints) {
        addnav("Rasse erstellen`n(" . $racepoints . " RP-Punkte)", "rpstore.php?op=rprasse");
    }
    if ($session[user][rppunkte] >= $bespoints) {
        addnav("Raumbeschreibung`n(" . $bespoints . " RP-Punkte)", "rpstore.php?op=beschreib");
    }
    if ($session[user][rppunkte] >= $raumpoints) {
        addnav("Raum`n(" . $raumpoints . " RP-Punkte)", "rpstore.php?op=raum");
    }
//ende
    output("</div>", true);

    output("`n`n(Bitte beachte das hier nach und nach weiter Möglichkeiten dazu kommen können.)`n");
}

page_footer();
?>