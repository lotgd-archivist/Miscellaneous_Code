<?php
#-----------------------------------------#
#   Gildensystem Version: 1.5b            #
#   ~~ Gildenstrasse ~~                   #
#   Autor: Eliwood, Serra                 #
#-----------------------------------------#

/* Require */
require_once "common.php";
require_once "lib/gilden.php";

/* Gilde laden und speichern in $session['guild'] */
loadguild($session['user']['memberid']);

page_header();
addcommentary ();
/* Start Switch */
switch($_GET['op']):

  /* Gildenstrasse */
  case "":
    page_header("Gildenstrasse");
    output("`2Du gehst die Gildenstrasse entlang und siehst die verschiedensten Gildenhäuser. "
          ."Grosse, mit Gold und Edelsteine verzierte Eichentüren schmücken die pompösen Häuser, "
          ."die Flaggen der einzelnen Gilden wehen im Wind.`n`n");
          
    /* Wenn User Mitglied in Gilde ist */
    if($session['user']['memberid']>0)
    {
      $session['rank'] = db_fetch_assoc(db_unbuffered_query("SELECT * FROM gildenranks WHERE rankid='{$session['user']['rankid']}' LIMIT 1"));
      output("`5Du wendest einen Blick auf deinen Gildenausweis. Darauf sind Gilde und die "
            ."Hausnummer der Gilde, sowie der Rang verzeichnet:`n`n");
      output("`3Gilde: `^".$session['guild']['gildenname']."`n");
      output("`3Gildenstrasse Nummer: `^".$session['guild']['gildenid']."`n");
      output("`3Rang: `^".($session['user']['rankid']!=0?$session['rank']['rankname']:"Ranglos")."`n");
    }
    /* Wenn nicht, aber auch keine Bewerbung abgegeben wurde */
    elseif($session['user']['gildenactive'] == '0')
    {
      output("`3Du gehörst momentan keiner Gilde an.`n");
      output("`4Ist es nicht an der Zeit, dich bei einer Gilde zu Bewerben?");
    }
    /* Wenn nichts zutrifft, also User  sich beworben hat, aber noch nicht aufgenommen wurde */
    else
    {
      output("`3Du hast noch keine Antwort erhalten, ob du aufgenommen wurdest. Warte einfach noch ein wenig, ja?");
    }
    output("`n`n");
     viewcommentary("gildenstr","sagt",15);
    
    /* Navigation */
    addnav("Die Gildenstrasse");
    addnav("Gildenhausviertel","houses.php?location=4");
    addnav("Gildenverwaltung","gildenstrasse.php?op=verwaltung");
    addnav("Aktuelle Gilden","gildenstrasse.php?op=brett");
    /* Wenn Mitglied einer Gilde */
    if($session['user']['memberid']>0)
      addnav("Gilde betreten","gilden.php");
    break;

  /* Verwaltung */
  case "verwaltung":
    /* Seitentitel */
    page_header("Verwaltungshaus");
    output("`2Die betrittst das Verwaltungshaus der Gilden. Hier müssen die Gilden gegründet werden, "
          ."damit sie offiziell anerkannt werden, hier werden Bewerbungen für die verschiedenen Gilden "
          ."abgegeben und hier liegt das Archive der Gildengeschichte Phereas, zu dem nur hochrangige "
          ."Gildenmitglieder Zugang haben.`n`n");
         
    /* Wenn User weder Mitglied, noch eine Bewerbung abgeschickt hat. */
    if($session['user']['memberid']==0 && $session['user']['gildenactive']=='0')
    {
      addnav("Aktionen");
      addnav("Bewerben","gildenstrasse.php?op=bewirb");
      output("`5Ein Mann erklärt dir, dass du dich bei einer Gilde bewerben kannst");
      /* Wenn User mehr Phoenixkills als Eingestellt hat, darf er eine Gilde gründen */
      if($session['user']['dragonkills']>=dkrequired)
      {
        addnav("Gilde gründen","gildenstrasse.php?op=grund");
        output(", oder aber auch, sofern du genügend Bares hast, eine eigene Gilde gründen");
      }
      $output.=".<br><br>";
    }
    output("`2An einer Tafel hängen die momentanen Preise: ");
    /* Preistafel anzeigen */
    preistafel();
    break;

  /* Bewerben */
  case "bewirb":
    page_header("Verwaltungshaus");
    // addnav("Aktualisieren","gildenstrasse.php?op=bewirb");
    showguilds(false,"gildenstrasse.php?op=bewirb2");
    addnav("Abbrechen","gildenstrasse.php?op=verwaltung");
    break;
  case "bewirb2":
    page_header("Verwaltungshaus");
    /* Die entsprechende Gilde abrufen */
    $sql2 = "SELECT * FROM gilden WHERE gildenid='".addslashes($_GET['id'])."' LIMIT 1";
    $result = db_unbuffered_query($sql2);
    $row = db_fetch_assoc($result);
    /* Eventuelle Multiaccounts checken */
    $sql3 = "SELECT uniqueid,acctid FROM accounts WHERE acctid='$row[leaderid]'";
    $row2 = db_fetch_assoc(db_query($sql3));
    /* Check heil überstanden? */
    if(ac_check($row2)==false)
    {
      /* Insert vorbereiten */
      $sql = "INSERT INTO `bewerbungen` (`bewerbid`,`bewerberid`,`gildenid`) ";
      $sql .= "VALUES ('','".$session['user']['acctid']."','".$_GET['id']."');";
      /* Und in die Datenbank schreiben */
      db_unbuffered_query($sql) or die($sql);
      /* Mail vorbereiten */
      $subject = "Bewerbung!";
      $body = "`&".$session['user']['name']."`% hat sich bei deiner Gilde beworben.";
      /* Und abschicken */
      db_unbuffered_query("INSERT INTO mail (msgfrom,msgto,subject,body) VALUES (0,'$row[leaderid]','$subject','$body')");
      /* Ausgabe */
      output("`#Du hast eine Bewerbung bei der Gilde ".$row['gildenname']."`# abgegeben. Warte "
            ."noch ein Weilchen, damit dich der Führer aufnehmen oder auch ablehnen kann.");
      /* Merkmal, dass User sich beworben hat, setzen */
      $session['user']['gildenactive'] = 1;
    }
    /* Check doch nicht überstanden? Och... Mach trotzdem was sinnvolles ;) */
    else
    {
      output("Da der Führer dieser Gilde mit dir verwandt ist, darfst du dich nicht bei dieser Gilde bewerben!");
    }
    break;
    
  /* Gründen */
  case "grund":
    /* 2ter Schritt */
    page_header("Verwaltungshaus");
    output("`#Der Mann gibt dir ein Formular und meint nur: \"`^Füll aber alles ordentlich aus!`#\"`n`n");
    /* Formular */
    grundform("gildenstrasse.php?op=grund2");
    break;
  case "grund2":
    /* 1ter Schritt */
    page_header("Verwaltungshaus");
    /* Eingabe prüfen, hehe */
    if(check_input($_POST)==false)
    {
      /* Och... Nicht überstanden, hmm? Formular also wieder geben.... Inkl. Error, natürlich ;) */
      rawoutput($error);
      grundform("gildenstrasse.php?op=grund2");
    }
    else
    {
      /* Juhui, geschafft =D Nun nur noch Gold prüfen *gg* */
      output("`#Du gibst den Mann das Formular zurück, er sieht es sich kurz an und meint dann, mit einem Blick auf deine Ersparnisse gerichtet: ");
      if($session['user']['gold']>=goldprice && $session['user']['gems']>=gemprice)
      {
        /* Ah... Der Spieler genügt den hohen Anforderungen, jaja... Also, gebt ihm eine Gilde =D */
        output("\"`^Gut... Die unterzeichne noch hier, und die Gründung wird erfolgreich abgeschlossen.`#\"");
        /* Abrechnen, jaja */
        $session['user']['gold']-=goldprice;
        $session['user']['gems']-=gemprice;
        /* Gründerid setzen ;) */
        $session['user']['isleader'] = highestleader;
        /* Jaja... User ist Gildenaktiv (Nein, kommt nicht von radioaktiv, sondern von Hyperaktiv *gg*) */
        $session['user']['gildenactive'] = '1';
        /* Gildeninsert vorbereiten... Der kommt irgendwann mal in eine Funktion ô__Ò */
        $sql = "INSERT INTO `gilden` (`gildenname`,`gildenname_b`,`gildenprefix`,`gildenprefix_b`,`leaderid`)";
        $sql.= "VALUES ('".$_POST['gildenname']."','".$_POST['gildenname_b']."','".$_POST['gildenprefix']."','".$_POST['gildenprefix_b']."','".$session['user']['acctid']."')";
        /* Schreiben oder Schreien... */
        db_query($sql) or die(mysql_error(LINK));
        /* Jaja... Die ID der Gilde brauchen mer noch =) */
        $sql = "SELECT gildenid FROM gilden WHERE leaderid='{$session['user']['acctid']}' ";
        $id = db_fetch_assoc(db_unbuffered_query($sql));
        /* Und da haben wir sie... Gleich dem User zuweisen, jaja */
        $session['user']['memberid']=$id['gildenid'];
      }
      else
      {
        /* Tjo... Betrug, und da wird schonmal unfreundlich zurück gewiesen.
           Kostet ja schliesslich alles Geld. Das Haus, die Waffen, das Wappen.... ;) */
        output("\"`^Dein Geld reicht nicht... Komm wieder, wenn du genug hast!`#\"");
      }
    }
    break;
  case "brett":
    /* Alle Gilden anzeigen? ô__Ò
       ...
       ...
       Na gut, da hast du sie, aber lass mich nu in Ruhe, mag nicht mehr :( */
    showguilds(false,false,true);
    break;
  default:
    /* Tjo, wenn das zu sehen ist, ist wirklich was schief gelaufen... Verschrieben? */
    page_header("FEHLER!");
    output("`\$Fehler! Melde es unverzüglich den Administratoren, wenn du das sehen kannst");

/* End Switch */
endswitch;

/* Back */
addnav("Zurück");
if(isset($_GET['op']))
addnav("Gildenstrasse","gildenstrasse.php");
addnav("Zurück ins Dorf","village.php");

/* Und fertig, hihi */
page_footer();
?>