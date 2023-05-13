
<?php
#-----------------------------------------#
#   Gildensystem Version: 1.5b            #
#   ~~ Gildeninnern ~~                    #
#   Autor: Eliwood, Serra                 #
#-----------------------------------------#

/* Require */
require_once "common.php";require_once "func/systemmail.php";require_once "lib/gilden.php";

/* Gilde laden und speichern in $session['guild'] */
loadguild($session['user']['memberid']);

/* Maintitle */
$title = "Gildenhaus ".$session['guild']['gildenname_b']."";

$schlafsaal=false;

/* Script */
switch($_GET['op']):
    case "":

        /* Kommentare hinzufügen */
        addcommentary();
        /* Ein kleiner Text */
        page_title($session['guild']['gildenname']);
        output("`YDie Mitglieder der Gilde laufen geschäftig umher. Eine schwer bewachte Eisentür schützt den Schatz der Gilde vor fremden Zugriffen, eine Tafel über dem Arbeitstisch des Gildenführers zeigt den momentanen Schatzspiegel, sowie die Punkte:`n");
        /* Gildenspiegel - Anzeige von Gold, Edelsteinen & Punkten */
        rawoutput("<pre>");
        output("`2- `YGold der Gilde: `^".$session['guild']['gold']);
        output("`2- `YEdelsteine der Gilde: `%".$session['guild']['gems']);
        output("`2- `YPunkte der Gilde: `&".$session['guild']['gildenpunkte']);
        rawoutput("</pre>");
        /* Gildenspiegel Ende */
        /* Kommentare anzeigen & Eingabeform */
        viewcommentary("gildenhaus ".$session['guild']['gildenid'],"`iUnterhalte dich mit anderen Mitgliedern`i",25,"sagt",1,1);
        break;

    case "schlaf":
        $schlafsaal=true;
        /* Kommentare hinzufügen */
        addcommentary();
        /* Ein kleiner Text */
        page_title($session['guild']['gildenname']);
        output("`YDu betrittst den Schlafsaal deiner Gilde. An den Längsseiten des langen Saales sind gemütlich aussehende Betten aufgereiht, durch lange und dichte Vorhänge von einander abgetrennt. Du kannst dich mit deinen Bettnachbarn unterhalten, doch leise, um die anderen Ruhe suchenden Mitglieder nicht zu wecken.`n`n`n");
        /* Gildenspiegel - Anzeige von Gold, Edelsteinen & Punkten */
        /* Kommentare anzeigen & Eingabeform */
        viewcommentary("gildenschlafsaal ".$session['guild']['gildenid'],"`iUnterhalte dich leise mit anderen Mitgliedern`i",25,"flüstert",1,1);
        break;
    case "schlaf2":
        debuglog("Im Gildenschlafsaal ausgeloggt ");
        $session['user']['location']=33;
        $sql = "UPDATE accounts SET loggedin=0, location=33 WHERE acctid = ".$session['user']['acctid'];
        db_query($sql) or die(sql_error($sql));
//        saveuser();

        $file = fopen('./cache/c'.$session['user']['acctid'].'.txt','w');
        fputs($file,'');
        fclose($file);

            $session=array();
        redirect("index.php");
        break;

    case "tribut":
      /* Ja, Gilde, wir, die Member, zahlen die Steuer! */
      output("`YDu nimmst dein Säckchen mit deinem Gold und deinen Edelsteinen hervor und überlegst, wieviel du spenden magst.");
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
        $maxgoldtrans = ($session['user']['level']*goldperlevel*2);
        $maxgemstrans = ($session['user']['level']*gemsperlevel*2);
        /* Prüfen, ob User schon ge*/
        /*if($session['user']['gildengold']<$maxgoldtrans
           && ($session['user']['gildengold']+$_POST['gold'])<=$maxgoldtrans
           && $session['user']['gold']>=$_POST['gold'])
      */
      if ($_POST['gold']<=$maxgoldtrans && $session['user']['gold']>=$_POST['gold'])
        {
          /* Und schreiben, schreiben, abrechnen... */
          guild_update("gold",$gold);
          $session['user']['gold']-=$gold;
          $session['user']['gildengold']+=$gold;
        }
        else
        {
          /* Na ja... Die Gilde hat solch spendable User ja gerne... Wir Admins weniger */
          output("`\$Du hast schon zu viel Gold eingezahlt, mehr geht wirklich nicht! Es könnte natürlich auch sein, dass du zu wenig Gold dabei hast, wer weiss.`n");
          $gold = 0;
        }
        if ($_POST['gems']<=$maxgemstrans && $session['user']['gems']>=$_POST['gems'])

        /* if($session['user']['gildengems']<$maxgemstrans
           && ($session['user']['gildengems']+$_POST['gems'])<=$maxgemstrans
           && $session['user']['gems']>=$_POST['gems'])
           */
        {
          guild_update("gems",$gems);
          $session['user']['gems']-=$gems;
          $session['user']['gildengems']+=$gems;
        }
        else
        {
          /* Na ja... Die Gilde hat solch spendable User ja gerne... Wir Admins weniger */
          output("`\$Du hast schon zu viele Edelsteine eingezahlt, mehr geht wirklich nicht! Es könnte natürlich auch sein, dass du einfach zu wenig Edelsteine dabei hast, wer weiss.`n");
          $gems = 0;
        }
        if($gold > 0)
        {
          /* Gold eingezahlt? Ausgeben! */
          output("`#Du gibst dem Wächter des Schatzes `^".$gold." Goldstücke`#, die Gilde hat nun `^".$session['guild']['gold']." Goldstücke im Schatz.`n");
        }
        if($gems > 0)
        {
          /* Edelsteine eingezahlt? Ausgeben! */
          output("`#Du gibst dem Wächter des Schatzes `%".$gems." Edelsteine`#, die Gilde hat nun `%".$session['guild']['gems']." Edelsteine im Schatz.`n");
        }

          gildenbuch($gold, $gems, "Tribut zahlung");
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
        output("Bitte hinterlasse dem Leader eine Nachricht mit einer kurzen Begründung, weshalb du die Gilde verlässt!`n`n");
        output("<form action='gilden.php?op=dropme2' method='POST'>Folgenden Text schicken: <input name='ktext' size='40'><input type='submit' class='button' value='Senden'></form>",true);
        addnav("Keine Nachricht","gilden.php?op=dropme2&act=no");
        addnav("Halt, ich will nicht kündigen!","gilden.php");
        allownav("gilden.php?op=dropme2");
    break;
    case "dropme2":
        /* *Sing* Lass ich fallen ;D *sing*  */
        drop_me($session['user']['acctid'],$session['user']['name'],$session['user']['isleader'],$_POST['ktext']);
    break;
    default:
        /* Weder noch? Dann Gildengebäude besorgen ;D */
        if($_GET['op'] == "build" && file_exists("lib/gildenbuilding.php")){
            /* Ausbau-Modifikation by Eliwood */
            require_once "lib/gildenbuilding.php";
            switch($_GET['action']):
                /* Waffenshop by Eliwood */
                case "weapon":
                    if(isset($_GET['weaponid'])){
                          buyweapon($_GET['weaponid']);
                    }
                    output("`YDu betrittst den Waffenshop der Gilde. Hier kannst du billiger Waffen kaufen, welche die Verwaltung der Gilde bei MithtyE eingekauft hat. Das Gold kommt der Schatzkammer zu Gute. Was die Verwaltung damit anstellt? Fragt sie einfach.`n`n");
                    showweapons();
                break;
                case "armor":
                    /* Na ja... Das Gleiche, im Prinzip :/ */
                    if(isset($_GET['armorid'])){
                         buyarmor($_GET['armorid']);
                    }
                    output("`YDu betrittst den Waffenshop der Gilde. Hier kannst du billiger Waffen kaufen, welche die Verwaltung der Gilde bei MithtyE eingekauft hat. Das Gold kommt der Schatzkammer zu Gute. Was die Verwaltung damit anstellt? Fragt sie einfach.`n`n");
                    showarmors();
                break;
                case "rschmied":
        /* Na ja... Das Gleiche, im Prinzip :/ */
        if(isset($_GET['armordef'])){
            upgradearmor($_GET['armordef']);
        }
        output("`YDu betrittst die Waffenschmiede der Gilde. Hier kannst du deine Waffe aufwerten lassen. Das Gold kommt der Schatzkammer zu Gute. Was die Verwaltung damit anstellt? Fragt sie einfach.`0`n`n");
        updatearmors();
        break;
           case "wschmied":
        /* Na ja... Das Gleiche, im Prinzip :/ */
        if(isset($_GET['weapondmg'])){
            upgradeweapon($_GET['weapondmg']);
        }
        output("`YDu betrittst die Waffenschmiede der Gilde. Hier kannst du deine Waffe aufwerten lassen. Das Gold kommt der Schatzkammer zu Gute. was die Verwaltung damit anstellt? Fragt sie einfach.`0`n`n");
        updateweapons();
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

if($schlafsaal==false) addnav("Schlafsaal",($_GET['op']=="schlaf"?"":"gilden.php?op=schlaf"));
else {
    addnav("Schlafen legen");
    addnav("Einschlafen","gilden.php?op=schlaf2");
}
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
$session['user']['standort']="Gildenhaus";

page_footer();

?>

