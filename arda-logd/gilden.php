<?php
#-----------------------------------------#
#   Gildensystem Version: 1.5b            #
#   ~~ Gildeninnern ~~                    #
#   Autor: Eliwood, Serra                 #
#-----------------------------------------#

/* Require */
require_once "common.php";
require_once "lib/gilden.php";

/* Gilde laden und speichern in $session['guild'] */
loadguild($session['user']['memberid']);

/* Maintitle */
$title = "Gildenhaus ".$session['guild']['gildenname_b']."";

/* Script */
switch($_GET['op']):
    case "":

      /* Kommentare hinzufügen */
      addcommentary();

      /* Ein kleiner Text */
      page_title($session['guild']['gildenname']);
      output("`3Die Mitglieder der Gilde laufen geschäftig umher. Eine schwer bewachte Eisentüre schützt den Schatz der Gilde vor Fremden zugriffen, eine Tafel über dem Arbeitstisch des Gildenführers zeigt den Momentanen Schatzspiegel, sowie die Punkte:`n");
      /* Gildenspiegel - Anzeige von Gold, Edelsteinen & Punkten */
      rawoutput("<pre>");
      output("`2- `2Gold der Gilde: `^".$session['guild']['gold']);
      output("`2- `2Edelsteine der Gilde: `%".$session['guild']['gems']);
      output("`2- `2Punkte der Gilde: `&".$session['guild']['gildenpunkte']);
      rawoutput("</pre>");
      /* Gildenspiegel Ende */
      /* Kommentare anzeigen & Eingabeform */
      viewcommentary("gildenhaus ".$session['guild']['gildenid'],"`iUnterhalte dich mit anderen Mitgliedern`i",15,"sagt");

      break;
    case "tribut":
      /* Ja, Gilde, wir, die Member, zahlen die Steuer! */
      output("`3Du nimmst dein Säckchen mit deinem Gold und deinen Edelsteinen hervor und überlegst, wieviel du spenden magst.`nDu kannst 1000 `^Gold  und 10 `%Edelsteine `3pro Level von dir einzahlen.");
      /* Nächsteres nicht beachten... Sehr unübersichtlich... ô__Ò */
      $link = "gilden.php?op=tribut2";
      allownav($link);
      rawoutput("<form action='$link' method='POST'>");
      rawoutput("<table>");
      rawoutput("<tr><td>");
      output("`^Gold einzahlen");
      rawoutput("</td><td>");
      rawoutput("<input type='text' name='gold'>");
      rawoutput("</td></tr>");
      rawoutput("<tr><td>");
      output("`%Edelsteine einzahlen");
      rawoutput("</td><td>");
      rawoutput("<input type='text' name='gems'>");
      rawoutput("</td></tr>");
      rawoutput("<tr><td colspan='2'>");
      rawoutput("<input type='submit' value='Tribut entrichten'>");
      rawoutput("</td></tr>");
      rawoutput("</table>");
      rawoutput("</form>");
      break;
    /* Jetzt wieder Augen auf, geht weiter! */
    case "tribut2";
      /* Kürzere Variablen, die brauch ich */
      $gold = $_POST['gold'];
      $gems = $_POST['gems'];
      /* Leeres Feld? Nimm an, es is Null ;) */
      if($gold=="") $gold = 0;
      if($gems=="") $gems = 0;
      
      /* Legale Eingabe? */
      if(check_tribut())
      {
        /* Maximale Goldtranserrate und Edelsteintranferrate festlegen */
        $maxgoldtrans = ($session['user']['level']*goldperlevel);
        $maxgemstrans = ($session['user']['level']*gemsperlevel);
        /* Prüfen, ob User schon ge*/
        if($session['user']['gildengold']<$maxgoldtrans
           && ($session['user']['gildengold']+$_POST['gold'])<=$maxgoldtrans
           && $session['user']['gold']>=$_POST['gold'])
        {
          /* Und schreiben, schreiben, abrechnen... */
          guild_update("gold",$session['guild']['gold']+=$gold);
          $session['user']['gold']-=$gold;
          $session['user']['gildengold']+=$gold;
          
        }
        else
        {
          /* Na ja... Die Gilde hat solch spendable User ja gerne... Wir Admins weniger */
          output("`\$Du hast schon zuviel Gold eingezahlt, mehr geht wirklich nicht! Es könnte natürlich auch sein, dass du zu wenig Gold dabei hast, wer weiss.`n");
          $gold = 0;
        }
        if($session['user']['gildengems']<$maxgemstrans
           && ($session['user']['gildengems']+$_POST['gems'])<=$maxgemstrans
           && $session['user']['gems']>=$_POST['gems'])
        {
          guild_update("gems",$session['guild']['gems']+=$gems);
          $session['user']['gems']-=$gems;
          $session['user']['gildengems']+=$gems;
        }
        else
        {
          /* Na ja... Die Gilde hat solch spendable User ja gerne... Wir Admins weniger */
          output("`\$Du hast schon zuviele Edelsteine eingezahlt, mehr geht wirklich nicht! Es könnte natürlich auch sein, dass du einfach zu wenig Edelsteine dabei hast, wer weiss.`n");
          $gems = 0;
        }
        if($gold > 0)
        {
          /* Gold eingezahlt? Ausgeben! */
          output("`#Du gibts dem Wächter des Schatzes `^".$gold." Golstücke`#, die Gilde hat nun `^".$session['guild']['gold']." Goldstücke im Schatz.`n");
        }
        if($gems > 0)
        {
          /* Edelsteine eingezahlt? Ausgeben! */
          output("`#Du gibts dem Wächter des Schatzes `%".$gems." Edelsteine`#, die Gilde hat nun `%".$session['guild']['gems']." Edelsteine im Schatz.`n");
        }
      }
      /* Muahahahaha... Fehler, fehler, fehler *freu* */
      else output("`\$Fehler `^$errornum`\$: ".$error);
      break;
    case "members":
      /* Alle User auflisten... Pffff.... */
      showuser_public();
      break;
    case "infos":
      /* Das mag ich *gg* Beschreibung, Geschichte und Regeln... Jaja */
      rawoutput("<center>");
      switch($_GET['what']):
        case "desc":
          output("`3~~ Beschreibung `3~~`n`n");
          output(stripslashes($session['guild']['gildendesc']),true);
          break;
        case "story":
          output("`3~~ Geschichte `3~~`n`n");
          output(stripslashes($session['guild']['gildenstory']),true);
          break;
        case "regeln":
          output("`3~~ Regeln `3~~`n`n");
          output(stripslashes($session['guild']['gildenregeln']),true);
          break;
        default:
          output("ERROR!");
        endswitch;
        rawoutput("</center>");
      break;
    case "dropme":
      /* *Sing* Lass ich fallen ;D *sing*  */
      drop_me($session['user']['acctid'],$session['user']['name'],$session['user']['isleader']);
      break;
    default:
      /* Weder noch? Dann Gildengebäude besorgen ;D */
      if($_GET['op'] == "build" && file_exists("lib/gildenbuilding.php"))
      {
        /* Ausbau-Modifikation by Eliwood */
        require_once "lib/gildenbuilding.php";
        switch($_GET['action']):
          /* Waffenshop by Eliwood */
          case "weapon":
            if(isset($_GET['weaponid']))
            {
              buyweapon($_GET['weaponid']);
            }
            output("`3Du betrittst den Waffenshop der Gilde. Hier kannst du Waffen kaufen, welche die Verwaltung der Gilde bei MithtyE eingekauft hat, billiger. Das Geld kommt der Schatzkammer zu Gute, was die Verwaltung damit anstellt, fragt sie einfach.`n`n");
            showweapons();
            break;
           case "armor":
            /* Na ja... Das Gleiche, im Prinzip :/ */
            if(isset($_GET['armorid']))
            {
              buyarmor($_GET['armorid']);
            }
            output("`3Du betrittst den Waffenshop der Gilde. Hier kannst du Waffen kaufen, welche die Verwaltung der Gilde bei MithtyE eingekauft hat, billiger. Das Geld kommt der Schatzkammer zu Gute, was die Verwaltung damit anstellt, fragt sie einfach.`n`n");
            showarmors();
            break;
          endswitch;
      }
      else
      {
        /* Fehler, fehler... *ERROR* Sofort Script abbrechen, eine 2meterlange Beschwerdemail aufsetzen
           und dann merken,dass es eigentlich nicht sein kann *rolling eyes */
        $title = "FEHLER!";
        output("`\$Fehler! Melde es unverzüglich den Administratoren, wenn du das sehen kannst");
      }
  endswitch;

/* Navigation */
/* Schatzkammer */
addnav("Schatzkammer");
addnav("Tribut entrichten",($_GET['op']=="tribut"?"":"gilden.php?op=tribut"));
/* Sonstige Räume */
addnav("Räume");
addnav("Mitgliederliste",($_GET['op']=="members"?"":"gilden.php?op=members"));
addnav("Aufenthaltsraum",($_GET['op']==""?"":"gilden.php"));
if ($session['user']['isleader']>0)
  addnav("Verwaltungsraum","gildenverwalt.php"); // Für Mitglieder isleader > 0
/* Informationen */
addnav("Informationen");
addnav("Beschreibung",($_GET['op'] == "infos" && $_GET['what']=="desc"?"":"gilden.php?op=infos&what=desc"));
addnav("Geschichtliches",($_GET['op'] == "infos" && $_GET['what']=="story"?"":"gilden.php?op=infos&what=story"));
addnav("Regeln",($_GET['op'] == "infos" && $_GET['what']=="regeln"?"":"gilden.php?op=infos&what=regeln"));

/* Ausbau-Modifikation by Eliwood */
if(buildactive===True)
{
  show_builded_navs();
}

addnav("Optionen");
addnav("`\$Kündigung einreichen","gilden.php?op=dropme");
/* Ausgang */
addnav("Ausgang");
addnav("Gildenstrasse","gildenstrasse.php");
addnav("Dorfplatz","village.php");

/* output anzeigen */
page_header($title);
page_footer();

?>