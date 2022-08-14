
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
page_header("Entscheidung");

if (!isset($session)) exit();


if ($_GET[op]==""){
   output("`n`c`@Die `&Entsch`^eidung`c");
   output("`n`n`@Du wanderst einen Weg entlang und erfreust dich an den Narzissen,
   die am Wegesrand wachsen. Während du darüber nachsinnst, ob du deinem Herzblatt
   nicht einen Strauß pflücken solltest, entdeckst du plötzlich etwas Buntes in
   dem ganzen Grün. Neugierig, wie du nun einmal bist, beugst du dich hinab, um
   dieses Ding näher zu betrachten. Erstaunt stellst du fest, dass es sich um ein
   bemaltes Ei handelt. Die Farben und Muster gefallen dir so gut, dass du es
   kurzerhand einsteckst.`n`n
   Du wanderst weiter, doch schon nach wenigen Metern siehst du wieder etwas Buntes
   aufblitzen. Ein weiteres Ei, welches du ebenfalls einsteckst. Du lässt deinen Blick
   schweifen und rechts und links vom Wegesrand entdeckst du weitere bunte Flecken im
   Gras. Du blickst zum Ende des Pfades und was du dort ausmachen kannst, lässt dich
   an deinem Verstand zweifeln. Du reibst dir die Augen, doch auch nach dieser Aktion
   bleibt das Bild das Gleiche.`n`n
   Ein kleines, flauschiges Häschen, das einen riesigen Korb voller bunter Eier auf
   seinem Rücken trägt, hoppelt darauf entlang. Ab und an greift es sich ein Ei und
   wirft es ins hohe Gras. Um der Sache auf den Grund zu gehen, eilst du hinter dem
   Langohr her und holst es auch bald ein, obwohl du so viele Eier einsammelst, wie
   du tragen kannst. `&\"He da!\" `@rufst du ihm zu und der Hase wendet sich dir zu. Die
   schwarzen Knopfaugen werden riesengroß, als es die ganzen bunten Eier auf deinen
   Armen sieht. `&\"Aber, die sind doch für die Kinder!\" `@echauffiert es sich und wackelt
   aufgebracht mit den Öhrchen. `&\"Leg sie sofort dahin zurück, wo du sie gefunden hast!\"
   `@verlangt es von dir, doch du bist so verwirrt, dass du es nur anstarrst. Ein
   sprechender Hase? Der Eier versteckt? Ja, ist denn heut schon Ostern?`n`n
   `&\"Was ist nun?\" `@fragt es dich, nachdem du eine Weile stumm auf das Fellknäuel hinab
   gestarrt hast. Wirst du der Aufforderung nachkommen? Oder behältst du die aufgesammelten
   Eier?`n`n
   <a href='forest.php?op=ja'>Ja, ich lege die Eier zurück.</a>`n`n
   <a href='forest.php?op=nein'>Nein, ich behalte sie lieber.</a>`n`n",true);
   addnav("","forest.php?op=ja");
   addnav("","forest.php?op=nein");
   addnav("Eier zurücklegen","forest.php?op=ja");
   addnav("Eier behalten","forest.php?op=nein");
   $session[user][specialinc] = "entscheidung.php";
}

if ($_GET[op]=="nein"){
   output("`n`n`@Du denkst darüber nach, kommst jedoch zu dem Ergebnis, dass du sie viel
   lieber für dich behalten möchtest. `&\"Nö!\" `@entgegnest du entschieden und legst schützend
   die Arme um deine Beute. `&\"Was??\" `@quiekt das Häschen empört und das kleine Stimmchen
   überschlägt sich fast. `&\"Das wird dir noch leid tun!\" `@droht es dir dann und hoppelt von
   dannen. Kichernd, denn du glaubst dem Fellknäuel kein Wort - was konnte es dir auch schon
   groß tun, gehst du den Weg zurück, den du gekommen bist. Die Eier verstaust du sorgfältig
   in einem Beutel, doch schon nach kurzer Zeit hörst du ein leises `^\"Plopp\"`@. Verwundert schaust
   du dich um, kannst jedoch nichts finden, was dieses Geräusch verursachen könnte. So gehst du
   weiter, hörst es aber bald wieder. Und wieder. Und wieder.. Moment, wird dein Beutel nicht
   leichter? Du schaust direkt nach und tatsächlich: Ein buntes Ei nach dem anderen löst sich
   in Luft auf! Von weit her hörst du ein hämisches Lachen und meinst die Worte `&\"Ich habe dich
   gewarnt!\" `@ausmachen zu können. Vielleicht hättest du nicht so habgierig sein sollen!`n`n");
   $session['user']['gems']-=5;
   $session['user']['turns']-=5;
   $session['user']['charm']-=5;
   addnav("Zurück zum Wald","forest.php");
   }

if ($_GET[op]=="ja"){
   output("`n`n`@Ohne ein Wort zu verlieren gehorchst du und bringst deine Beute dorthin
   zurück, wo du sie gefunden hast. Unter den wachsamen Blicken des Häschens, der dir die
   ganze Zeit folgt, deponierst du die zerbrechlichen Dinger wieder im Gras. `&\"So, ist´s fein.\"
   `@lobt der Hase dich und scheint zu lächeln. Du erwiderst das Lächeln, ob wohl du dich
   nicht ganz wohl in deiner Haut fühlst. Nachdem du auch das letzte Ei zurück gelegt hast,
   strahlt der Flauschehase dich an. `&\"Der Dank der Kinder ist dir sicher!\" `@meint er und
   hüpft aufgeregt auf und ab. `&\"W..wer bist du?\" `@ringst du dich dazu durch zu fragen und
   wartest gespannt auf die Antwort. `&\"Na, der Osterhase!\" `@entgegnet er, erstaunt darüber,
   dass du nicht weißt, wer er ist. Osterhase! Wusstest du es doch! Grinsend siehst du nun
   auf den drolligen Kerl hinab, der dich aufmerksam beobachtet hat. `&\"Da du so nett warst
   und die Eier wieder versteckt hast, darfst du fünf von ihnen behalten!\" `@meint es großzügig
   und verabschiedet sich von dir. Das Häschen hoppelt den Weg wieder zurück und wirft alle
   paar Meter ein Ei in die Büsche. Sobald es aus deinem Blickfeld verschwunden ist, nimmst
   du fünf der bunten Eier wieder an dich und machst dich auf den Weg nach Hause.`n`n");
   $session['user']['gems']+=5;
   $session['user']['turns']+=5;
   $session['user']['charm']+=5;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

