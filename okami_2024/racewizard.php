
<?php
/*
common.php
Suche:
PHP:
$races=array(1=>"Troll",2=>"Elf",3=>"Mensch",4=>"Zwerg",5=>"Echse",0=>"Unbekannt",50=>"Hoverschaf");


Ersetze durch:
PHP:
$races = array(1=>'Troll'
              ,2=>'Elf'
              ,3=>'Mensch'
              ,4=>'Zwerg'
              ,5=>'Echse'
              ,0=>'Unbekannt'
              ,50=>'Hoverschaf');


Damit machen wir es erstma übersichtlicher beim einfügen einer Rasse, jetzt füge vor ,0=>'Unbekannt' deine Wunsch Rasse ein, also den Namen dieser Rasse zb so: ,6=>'Blublub', beachte aber hier bei die Zahl die davor steht! Denn bei jeder neuen Rasse die du einfügst musst du auch bei der Rassen zahl höher gehen.

Suche:
PHP:
$colraces=array(1=>'`2Troll`0',2=>'`^Elf`0',3=>'`0Mensch',4=>'`#Zwerg`0',5=>'`5Echse`0',0=>'`)Unbekannt`0',50=>'Hoverschaf');


Ersetze durch:
PHP:
$colraces = array(1=>'`2Troll`0'
                 ,2=>'`^Elf`0'
                 ,3=>'`0Mensch'
                 ,4=>'`#Zwerg`0'
                 ,5=>'`5Echse`0'
                 ,0=>'`)Unbekannt`0'
                 ,50=>'Hoverschaf');


Hier ist das gleiche Prinzip wie oben, aber beachte hier diesmal das du hier einen Farbcode eingeben kannst. Du kannst also hier die Farbe der Rasse beinträchtigen.


hof.php
Suche:
PHP:
$racesel = "CASE race WHEN 1 THEN '`2Troll`0' WHEN 2 THEN '`^Elf`0' WHEN 3 THEN '`&Mensch`0' WHEN 4 THEN '`#Zwerg`0' WHEN 5 THEN '`5Echse`0' ELSE '`7Unbekannt`0' END";


Ersetze durch:
PHP:
$racesel = "CASE race WHEN 1 THEN '`2Troll`0'
                      WHEN 2 THEN '`^Elf`0'
                      WHEN 3 THEN '`&Mensch`0'
                      WHEN 4 THEN '`#Zwerg`0'
                      WHEN 5 THEN '`5Echse`0'
                      ELSE '`7Unbekannt`0' END";


Hier finden wir nun die MySQL abfrage die bestimmt welche Rassennummer welchen Namen haben muss und ersetzt es ggf. um nicht mehr ins Detail zu gehen zeig ich euch jetzt wie man ganz Simpel diese Abfrage erweitert und seine Rasse dort mit einbringt. Ich hoffe ihr habt euren Rassen namen noch im Kopf, also füge nach WHEN 5 THEN '`5Echse`0' folgendes ein WHEN 6 THEN '`2Blublub`0', hier legen wir nun die Rassen nummer Fest, wenn der User die Rasse benutzt bzw. auswählt und diese Nummer hat, hat er die Rasse Blublub diese Abfrage kann man xxx erweitern.


newday.php
Suche:
PHP:
case "5":
            output("`5Als Echsenwesen hast du durch deine Häutungen einen klaren gesundheitlichen Vorteil gegenüber anderen Rassen.`n`^Du startest mit einem permanenten Lebenspunkt mehr!");
            $session['user']['maxhitpoints']++;
            break;


Hier finden wir nun bereits die erste Vorgeschichte einer Rasse, hier werden auch LP also Lebenspunkte vergeben ob ihr das auch macht bleibt euch selber überlassen. Wenn ihr nun eine eigene Rasse erstellent wollt fügt ihr dahinter folgendes ein und ändert es ggf.:

PHP:
case "6":
            output("Das ist Vorgeschichte von der Rasse `bBlublub`b.");
            break;



Suche:
Dann suche anschließend folgendes addnav("Wähle deine Rasse"); füge davor dies hier:
PHP:
output("<a href='newday.php?setrace=6$resline'>Dies ist die Herkunftsgesichte von der Rasse </a> `bBlublub`b.`n`n",true);



Suche:
PHP:
addnav("","newday.php?setrace=1$resline");


Davor:
PHP:
addnav("Blublub","newday.php?setrace=6$resline");


Suche:
PHP:
addnav("","newday.php?setrace=5$resline");


Davor:
PHP:
addnav("","newday.php?setrace=6$resline");
*/
/*
    (c) Rassenwizard 2005 by Serra & Eliwood
    
    Basierend auf Kevz' Einbauanleitung für Rassen
*/

Require_once "common.php";

page_header("Rassenwizard");

addnav("Zurück");
addnav("Zurück zur Grotte","superuser.php");
addnav("Zurück zum Weltlichen","village.php");
addnav("Wizard");
addnav("Neue Rasse","racewizard.php");

switch($_GET['op']):
  case "":
  case "1":
    output("`#`c`bRasswizard`b`n");
    output("© 2005 by Eliwood & Serra`c`n");
    output("`3Kurzanleitung:`n");
    output("Fülle das Formular aus und drücke auf den Bestätigen-Button.`n");
    output("Nun Befolge die darauf folgende Einbauanleitung, um die Rasse einzubauen.`&`n`n");
    addnav("","racewizard.php?op=2");
    $out = <<< HTML
    <table>
    <form action="racewizard.php?op=2" method="POST">
      <tr>
        <td>Name der Rasse</td>
        <td><input type="text" name="name" size="30" /></td>
      </tr>
      <tr>
        <td>Farbcode</td>
        <td><input type="text" name="color" size="5" maxlength="2" /></td>
      </tr>
      <tr>
        <td>Kurzbeschreibung`n (Bei der Auswahl der Rassen)`n`n
            Hinweis: Um vorzeitig den Link`n
            abzubrechen, &lt;/a&gt; einsetzen.</td>
        <td><textarea rows="5" cols="30" name="short"></textarea></td>
      </tr>
      <tr>
        <td>Hintergrundgeschichte`n (Nach der Auswahl der Rassen)</td>
        <td><textarea rows="5" cols="30" name="long"></textarea></td>
      </tr>
      <tr>
        <td>Freigabe (Usergruppen)</td>
        <td>
          <input type="radio" name="free" value="superuser" />Superuser <br />
          <input type="radio" name="free" value="male" />Männliche User <br />
          <input type="radio" name="free" value="female" />Weibliche User <br />
          <input type="radio" name="free" value="all" checked="checked" />Alle
        </td>
      </tr>
      <tr>
        <td>Freigabe (Drachenkills)`n`$ Wichtig:`n Superuser deaktiviert Limit!</td>
        <td><input type="text" name="dk" size=5 maxlength="3" value="0"></textarea></td>
      </tr>
      <tr>
        <td colspan=2 align="center">
          <button class="button" type="submit" name="button">
            Bestätigen
          </button>
        </td>
    </form>
    </table>
HTML;
    output($out,true);
    break;
  case "2":
    $id = count($races)-1;
    $name = $_POST['name'];
    $color = $_POST['color'];
    $colorname = $color.$name;
    $desc = $_POST['short'];
    $story = HTMLEntities($_POST['long']);
    if(!strpos($desc,"</a>")) {$desc = $desc."</a>";}
    $desc = HTMLEntities($desc);

    $close = "";
    switch($_POST['free']):
      case "superuser":
        $if = <<< HTML
        if(\$session['user']['superuser'] >= 3){
HTML;
        $close = "}";
        break;
      case "all":
        if($_POST['dk'] > 0)
        {
          $if = <<< HTML
          if(\$session['user']['dragonkills'] >= {$_POST['dk']}){
HTML;
        $close = "}";
        }
        break;
      case "male";
        if($_POST['dk'] > 0)
        {
          $if = <<< HTML
          if(\$session['user']['sex'] == 0 && (\$session['user']['dragonkills'] >= {$_POST['dk']}){
HTML;
        $close = "}";
        }
        else
        {
          $if = <<< HTML
          if(\$session['user']['sex'] == 0){
HTML;
        $close = "}";
        }
        break;
      case "female";
        if($_POST['dk'] > 0)
        {
          $if = <<< HTML
          if(\$session['user']['sex'] == 1 && (\$session['user']['dragonkills'] >= {$_POST['dk']}){
HTML;
        $close = "}";
        }
        else
        {
          $if = <<< HTML
          if(\$session['user']['sex'] == 1){
HTML;
        $close = "}";
        }
        break;
      endswitch;
    /*if($_POST['free'] == "superuser")
    {
      $if = <<< HTML
      if(\$session['user']['superuser'] >= 3){
HTML;
      $close = "}";
    }
    elseif($_POST['dragonkills'] > 0)
    {
      if
    }*/
    /*
      Anleitung erstellen
      Alles was bis HTML; kommt, ist kein PHP!
    */
    $anleitung = <<< HTML

    // Öffne common.php
    // Suche:
    
    ,0=>"Unbekannt"
    
    // Füge davor ein:
    
    ,$id=>"$name"
    
    // Suche:
    
    ,0=>"`)Unbekannt"

    // Füge davor ein:

    ,$id=>"$colorname"

    // Speichern und schliessen
    // Öffne hof.php
    // Suche:
    
    ELSE "`7Unbekannt`0"
    
    // Füge davor ein:

    WHEN $id THEN '$colorname'

    // Speichern und schliessen
    // Öffne newday.php
    // Suche:
    
    case "5":
    output("`5Als Echsenwesen hast du durch deine Häutungen einen klaren gesundheitlichen Vorteil gegenüber anderen Rassen.`n`^Du startest mit einem permanenten Lebenspunkt mehr!");
    \$session['user']['maxhitpoints']++;
    break;

    // Füge danach ein:

    case "$id":
    output("$story");
    break;

    // Suche:

    addnav("Wähle deine Rasse");

    // Füge danach ein:
$if
    addnav("$colorname","newday.php?setrace={$id}\$resline");
    addnav("","newday.php?setrace={$id}\$resline");
    output("&lt;a href='newday.php?setrace={$id}\$resline'&gt;$color $desc&lt;/a&gt;",true);
$close
HTML;
    // rawoutput(highlight_string($anleitung,true));
    rawoutput("<pre>".$anleitung."</pre>");
    break;
 endswitch;

page_footer();
?>


