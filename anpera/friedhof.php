
ï»¿<?php

/*

#########################################

#Autor:         Gregor_Samsa            #

#E-Mail:        gregor-samsa@arcor.de   #

#Url:           http://lotgd.gamaxx.de  #

#Version:       1.4                     #

#########################################

#Idee:                 Fenja            #

#E-Mail:        sinnlos_mail@web.de     #

#########################################

#Anpassung:     logd@anpera.de          #

#########################################



+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

+Beschreibung:                                                              +

+Ein Friedhof, auf dem man fÃ¼r seine AngehÃ¶rigen und Freunde trauern kann,  +

+um ihnen das leben in der Unterwelt zu erleichtern...                      +

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

*/



$author='Gregor_Samsa';

$copyright='&copy; 2005';



$website='http://lotgd.gamaxx.de';

$version='1.0 (anpera)';



require_once("common.php");

page_header("Der Friedhof");



output("`5`b`cDer Friedhof`b`c`n");

if($_GET['op']==""){

    $angreifer=(int)getsetting("angreiferzahl",0);

    addnav("Abteil der Toten","friedhof.php?op=tote");

    addnav("Abteil der Verlorenen","friedhof.php?op=verlorene");

    addnav("Abteil der Vergessenen","friedhof.php?op=vergessene");

    output("Mit leisen Schritten betrittst du den Friedhof. Ãœberall stehen GrÃ¤ber...Alte, Neue, GroÃŸe, Kleine.`n");

    if ($angreifer>0){

    output("Etwas abseits liegt ein groÃŸer Haufen umgestÃ¼rzter und zerbrochener Gedenktafeln. Leider ist auch die oberste Tafel");

    output("durch den laufenden Angriff der `%".getsetting("angreifername","Kekse")."`5 umgestÃ¼rzt. Man wird wohl eine neue ");

        output("aufstellen mÃ¼ssen.`n");

    }else{

    output("Etwas abseits liegt ein groÃŸer Haufen umgestÃ¼rzter und zerbrochener Gedenktafeln.`n");

        addnav("Gedenktafelberg","dorfangriff.php?op=over");

    }

    output("Ãœber dem Platz der Toten schwebt ein sonderbarer Nebel, der sich auch Tags Ã¼ber nicht verflÃ¼chtigt`n`n");

    output("Du kommst an eine Weggabelung.`n Wohin geht es weiter?`n`n");

    output("Du merkst, dass hier einige Personen stehen.`n`n");

    output("Die Umherstehenden sagen:`n`n");

    //Kommentare

    addcommentary();

    viewcommentary("friedhof_gabelung","HinzufÃ¼gen",25,"spricht leise");

    addnav("ZurÃ¼ck","village.php");

}

if($_GET['op']=="tote"){

    output("`b`5Das Abteil der Toten`b`n`n");

    output("Du hast dich fÃ¼r den Weg der Toten entschieden...`n");

    output("Ruhig schreitest du den Weg entlang und liest aufmerksam die Namen auf den GrÃ¤bern.`n`n");

    output("<table cellpadding='2' cellspacing='1' bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td></tr>",true);

    $sql = "SELECT * FROM accounts WHERE alive=0 ORDER BY level LIMIT 100";

    $result = db_query($sql) or die(db_error(LINK));

    $i=1;

    if (!db_num_rows($result)){

        output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gr&auml;ber</td></tr>",true);

    }else{

        while ($row = db_fetch_assoc($result)){

            $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

            $i++;

            output("<tr class='$bgclass'><td>",true);

            output($i);

            output("</td><td>",true);

            output("<a href='friedhof.php?op=status&abteil=1&id=".$row['acctid']."'>".$row['name']."</a>",true);

            output("</td></tr>",true);

            addnav("","friedhof.php?op=status&abteil=1&id=".$row['acctid']);

        }

    }

    output("</table>",true);

    //Navigation

    addnav("Trauere","friedhof.php?op=trauer&trauer=1");

    addnav("ZurÃ¼ck");

    addnav("Zur Weggabelung","friedhof.php?op=back");

}



if($_GET['op']=="verlorene"){

    output("`b`5Das Abteil der Verlorenen`b`n`n");

    output("Du hast dich fÃ¼r den Weg der Verlorenen entschieden...`n");

    output("Ruhig schreitest du den Weg entlang und liest aufmerksam die Namen auf den GrÃ¤bern.`n`n");

    output("<table cellpadding='2' cellspacing='1' bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td>",true);

    if($session['user']['superuser']>=3){

        output("<td>Op</td></tr>",true);

    }

    $sql = "SELECT * FROM graeber WHERE status=1 ORDER BY dk";

    $result = db_query($sql) or die(db_error(LINK));

    $i=1;

    if (!db_num_rows($result)){

       output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gr&auml;ber</td></tr>",true);

    }else{

        while ($row = db_fetch_assoc($result)) {

            $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

           $i++;

           output("<tr class='$bgclass'><td>",true);

           output($i);

           output("</td><td>",true);

           output("<a href='friedhof.php?op=status&abteil=2&id=".$row['id']."'>".$row['name']."</a>",true);

           output("</td>",true);

           if($session['user']['superuser']>=3){

                   output("<td><a href='friedhof.php?op=del&abteil=2&id=".$row['id']."'>del</a></td>",true);

           }

           output("</tr>",true);

           addnav("","friedhof.php?op=status&abteil=2&id=".$row['id']);

           addnav("","friedhof.php?op=del&abteil=2&id=".$row['id']);

        }

    }

    output("</table>",true);

    //Navigation

    addnav("Trauere","friedhof.php?op=trauer&trauer=2");

    addnav("ZurÃ¼ck");

    addnav("Zur Weggabelung","friedhof.php?op=back");

}



if($_GET['op']=="vergessene"){

    output("`b`5Das Abteil der Vergessenen`b`n`n");

    output("Du hast dich fÃ¼r den Weg der Vergessenen entschieden...`n");

    output("Ruhig schreitest du den Weg entlang und liest aufmerksam die Namen auf den GrÃ¤bern.`n`n");

    output("<table cellpadding='2' cellspacing='1' bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td>",true);

    if($session['user']['superuser']>=3) output("<td>Op</td></tr>",true);

    $sql = "SELECT * FROM graeber WHERE status=2 ORDER BY dk";

    $result = db_query($sql) or die(db_error(LINK));

    $i=1;

    if (!db_num_rows($result)){

       output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gr&auml;ber</td></tr>",true);

    }else {

        while ($row = db_fetch_assoc($result)) {

            $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

            $i++;

            output("<tr class='$bgclass'><td>",true);

            output($i);

            output("</td><td>",true);

            output("<a href='friedhof.php?op=status&abteil=3&id=".$row['id']."'>".$row['name']."</a>",true);

            output("</td>",true);

            if($session['user']['superuser']>=3){

                output("<td><a href='friedhof.php?op=del&abteil=3&id=".$row['id']."'>del</a></td>",true);

            }

            output("</tr>",true);

            addnav("","friedhof.php?op=status&abteil=3&id=".$row['id']);

            addnav("","friedhof.php?op=del&abteil=3&id=".$row['id']);

        }

    }

    output("</table>",true);

    //Navigation

    addnav("Trauere","friedhof.php?op=trauer&trauer=3");

    addnav("ZurÃ¼ck");

    addnav("Zur Weggabelung","friedhof.php?op=back");

}



if($_GET['op']=="status"){

    if($_GET['abteil']==1){

        $sql = "SELECT * FROM accounts WHERE acctid=".$_GET['id'];

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        output("Du Stehst vor dem Grab von ".$row['name']."`5 und betrachtest das Grab.`n`n");

        output("Auf dem Grab steht folgendes:`n`n");

        output("`c<table cellpadding='2' cellspacing='1'><tr><td><center>`bHier ruht ".$row['login']."`b</center></td></tr>",true);

        output("<tr><td>".($row['sex']?"Sie":"Er")." war bekannt als ".$row['name']."</td></tr>",true);

        output("<tr><td>Alter: ".$row['age']."</td></tr>",true);

        if ($row['grabinschrift']>"") output("<tr><td>".$row['grabinschrift']."</td></tr>",true);

        output("</table>`c",true);

    }else{

        if($_GET['abteil']==2) $sql = "SELECT * FROM graeber WHERE id=".$_GET['id']." AND status=1";

        if($_GET['abteil']==3) $sql = "SELECT * FROM graeber WHERE id=".$_GET['id']." AND status=2";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        output("Du Stehst vor dem Grab von ".$row['name']."`5 und betrachtest das Grab.`n`n");

        output("Auf dem Grab steht folgendes:`n`n");

        output("`c<table cellpadding='2' cellspacing='1'><tr><td><center>`bHier ruht ".$row['name']."`b</center></td></tr>`n",true);

        output("<tr><td>Letztes Alter: ".$row['age']."</td></tr>",true);

        if ($row['geburtsdatum']>"0000-01-01") output("<tr><td>Geboren: ".$row['geburtsdatum']."</td></tr>",true);

        if ($row['todesdatum']>"0000-01-01") output("<tr><td>Gestorben: ".$row['todesdatum']."</td></tr>",true);

        if ($row['spruch']>"") output("<tr><td>".$row['spruch']."</td></tr>",true);

        output("</table>`c",true);

    }

    //Navigation

    if($session['user']['trauer']==0 && $_GET['abteil']==1 && $session['user']['turns']>0){

        addnav("Trauere um ".$row['login'],"friedhof.php?op=trauern&id=".$row['acctid']);

    }

    addnav("ZurÃ¼ck");

    addnav("Abteil der Toten","friedhof.php?op=tote");

    addnav("Zur Weggabelung","friedhof.php?op=back");

}

if($_GET['op']=="del"){

     if($_GET['ak']==""){

        if($_GET['abteil']==2) $sql = "SELECT * FROM graeber WHERE id=".$_GET['id']." AND status=1";

        if($_GET['abteil']==3) $sql = "SELECT * FROM graeber WHERE id=".$_GET['id']." AND status=2";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        output("Willst du das Grab von ".$row['titel']." ".$row['name']."`5 wirklich zerstÃ¶ren?`n`n");

        addnav("Grab ZerstÃ¶ren?");

        output("<a href='friedhof.php?op=del&id=".$_GET['id']."&ak=ja'>Ja</a>`n",true);

        addnav("Ja","friedhof.php?op=del&id=".$_GET['id']."&ak=ja");

        addnav("","friedhof.php?op=del&id=".$_GET['id']."&ak=ja");

        if($_GET['abteil']==2){

            output("<a href='friedhof.php?op=verlorene'>Nein</a>",true);

            addnav("","friedhof.php?op=verlorene");

            addnav("Nein","friedhof.php?op=verlorene");

        }

        if($_GET['abteil']==3){

            output("<a href='friedhof.php?op=vergessene'>Nein</a>",true);

            addnav("Nein","friedhof.php?op=vergessene");

            addnav("","friedhof.php?op=vergessene");

        }

     }

     if($_GET['ak']=="ja"){

         $sql = "DELETE FROM graeber WHERE id=".$_GET['id'];

        db_query($sql);

        redirect("friedhof.php");

     }

}



if ($_GET['op']=="trauern"){

    $session['user']['trauer']++;

    $session['user']['turns']--;

    $sql = "SELECT * FROM accounts WHERE acctid=".$_GET['id'];

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);

    output("Mit verweinten Augen rufst du zu `\$Ramius`5 und flehst, er solle ".$row['login']." eine weitere Chance geben, ".($row['sex']?"ihr":"sein")." Leben fortzusetzen.`n`n");

    switch(e_rand(1,7)){

        case 1:

        case 2:

        case 3:

        case 4:

        output("`\$Ramius`5 ist gerÃ¼hrt von deiner Liebe zu ".$row['login']." und gewÃ¤hrt ".($row['sex']?"ihr":"ihm")." `\$10 Gefallen`5.");

        $gefallen=$row['deathpower']+10;

        output(($row['sex']?"Sie":"er")." hat nun ".$gefallen." Gefallen.");

        $sql="UPDATE accounts SET deathpower=".$gefallen." WHERE acctid=".$_GET['id'];

        $result = db_query($sql) or die(db_error(LINK));

        systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session['user']['name'].'`0 lies den TrÃ¤nen freien Lauf und rief zu `\$Ramius`0.`n`nDieser war gerÃ¼hrt von der Liebe zu dir und gewÃ¤hrte `\$10 Gefallen`0.');

        break;

        case 5:

        case 6:

        output("`5Nichts passiert...");

        break;

        case 7:

        output("`\$Ramius`5 ist so gerÃ¼hrt von deiner Liebe zu ".$row['login']." dass er  ".($row['sex']?"ihr":"ihm")." `\$eine neue Chance`5 gibt. ".($row['sex']?"Sie":"Er")." bekommt `\$25 Gefallen`5.");

        output($row['deathpower']);

        $gefallen=$row['deathpower']+25;

        output(($row['sex']?"Sie":"er")." hat nun ".$gefallen." Gefallen.");

        $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET['id'];

        $result = db_query($sql) or die(db_error(LINK));

        systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session['user']['name'].'`0 lies den TrÃ¤nen freien Lauf und rief zu `\$Ramius`0.`n`nDieser war gerÃ¼hrt von der Liebe zu dir und gewÃ¤hrte `\$25 Gefallen`0.');

    }

    //Navigation

    addnav("ZurÃ¼ck");

    addnav("Abteil der Toten","friedhof.php?op=tote");

    addnav("Zur Weggabelung","friedhof.php?op=back");

}



if($_GET[op]=="trauer"){

    output("Du stellst dich zu den Elenden und trauerst mit ihnen.`n`n");

    //Kommentare

    addcommentary();

    viewcommentary("friedhof_trauer".$_GET['trauer'],"HinzufÃ¼gen",25,"trauert");

    //Navigation

    if($_GET['trauer']==1) addnav("ZurÃ¼ck","friedhof.php?op=tote");

    if($_GET['trauer']==2) addnav("ZurÃ¼ck","friedhof.php?op=verlorene");

    if($_GET['trauer']==3) addnav("ZurÃ¼ck","friedhof.php?op=vergessene");

}



if($_GET['op']=="back"){

    output("Du lÃ¤ufst zurÃ¼ck zur Gabelung und merkst, dass hier einige Personen stehen.`n`n");

    output("Die Umherstehenden sagen:`n`n");

    //Kommentare

    addcommentary();

    viewcommentary("friedhof_gabelung","HinzufÃ¼gen",25,"spricht leise");

    //Navigation

    addnav("Abteil der Toten","friedhof.php?op=tote");

    addnav("Abteil der Verlorenen","friedhof.php?op=verlorene");

    addnav("Abteil der Vergessenen","friedhof.php?op=vergessene");

    addnav("ZurÃ¼ck","village.php");

}



// na hauptsache das copyright wird angezeigt :rolleyes: //anpera

output('`n<div align="right"`)'.$copyright.' by <a href="'.$website.'" target="_blank">'.$author.'</a></div>',true);



page_footer();

?>

