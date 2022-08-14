
<?

#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#       mit Unterstützung von       #
#     Laserian, Amon Chan und mfs   #
#      Texte von Brenna Hravani     #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################

require_once "common.php";
page_header("Narzissen");

if (!isset($session)) exit();

if ($_GET[op]==""){
   output("`n`n`c `b`@Di`&e N`^ar`&zi`@s`&se`^nw`&ie`@se`c `b `n `n ");
   output("`n`n`@Du schlenderst einen Weg entlang und lauschst dem fröhlichen Gezwitscher der Vögel, die
   aufgeregt vom aufkeimenden Frühling erzählen. Überall sprießt das frische Grün, zarte Knospen zieren
   die kahlen Äste und ein strahlend blauer Himmel lässt auch bei dir langsam Frühlingsgefühle erwachen.
   Mit einem träumerischen Lächeln auf den Lippen denkst du an deine/n Liebste/n, als dein Blick, wie
   magisch angezogen, auf ein Sonnenbeschienenes Fleckchen am Wegesrand fällt.`n`n");
   addnav("Das Interessiert mich","forest.php?op=ja");
   addnav("Kein Interesse","forest.php?op=nein");
   $session[user][specialinc] = "narzissen.php";
}

if ($_GET[op]=="ja"){
   output("`n`n`@Im ersten Moment bist du derart geblendet, dass es eine Weile dauert bis du erkennst was
   sich deinem Auge dort offenbart: `^Eine Narzissenwiese.`@`n`n

   Dicht an dicht drängen sich die goldgelben Blumen. Eine schöner als die andere. Vor Staunen über so
   iel Pracht bleibt dir kurzzeitig der Mund offen stehen und du denkst darüber nach, einen Strauß für
   deinen Schatz zu pflücken. Wie entscheidest du dich?`n`n");
   addnav("Ja, ich pflücke einen Strauß","forest.php?op=pfluecken");
   addnav("Nein, was soll ich mit dem Unkraut","forest.php?op=unkraut");
   $session[user][specialinc] = "narzissen.php";
}

if ($_GET[op]=="nein"){
   output("`n`n`@Dieses Fleckchen ist für dich in deinen Augen nicht interessant genug, deshalb ziehst
   du weiter deines Weges.`n`n");
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="pfluecken"){
   if ($session[user][turns]<1){
   output("`n`n`@Der Gedanke allein zählt, doch leider hast du heute keine Zeit mehr, Blumen zu pflücken.
   Du erhällst 1 Charmepunkt.`n`n");
   addnav("weiter gehen","forest.php");
   $session['user']['charm']++;
   } else {
   $runden=e_rand(1,3);
   output("`n`n`@Mit den Gedanken bei der Person deines Herzens betrittst du die Wiese und wählst
   sorgfältig die schönste Narzisse aus. Vorsichtig legst du die gepflückte Blume in deine Armbeuge
   und wanderst zur nächsten hinüber. Langsam wächst der Strauß und wird immer größer. Erst als du
   kaum noch alle Osterglocken halten kannst, machst du dich auf den Heimweg. Vor lauter Vorfreude
   über die Reaktion auf dieses spontane Geschenk, strahlst du über das ganze Gesicht.`n`n

   Durch die Mühe verlierst du `^".$runden." Waldkämpfe`@, erhältst jedoch `^5 Charmepunkte`@.`n`n");
   $session['user']['turns'] -= $runden;
   $session['user']['charm'] += 5;
   addnav("Zurück zum Wald","forest.php");
   }
}

if ($_GET[op]=="unkraut"){
   output("`n`n`@Nachdem du eine Weile hin und her überlegt hast, schüttelst du schließlich den Kopf.
   So ein Humbug ist doch reine Zeitverschwendung, findest du und wendest dich ab. Ohne einen weiteren
   Gedanken an diesen beschaulichen Ort ziehst du von dannen. Als du zu Hause deinem Schatz von der
   Blumenwiese erzählst, ist dieser enttäuscht, dass du keine einzige Narzisse mit gebracht hast.`n`n");
   $session['user']['charm'] -= 5;
   addnav("Zurück zum Wald","forest.php");
}

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

page_footer();
?>

