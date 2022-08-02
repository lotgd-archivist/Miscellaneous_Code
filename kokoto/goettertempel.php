<?php

// Tempel der Götter (c)by Ventus
// Erstmals erschienen auf www.Elfen-Portal.de

require_once "common.php";
    addcommentary();
    checkday();
if ($_GET['op']=='') {

    page_header("Tempel der Götter.");
    output('`b`c`2Der Saal:`0`c`b `n`%Du betrittst den imposanten Tempel. Du befindest dich in einer grossen und geräumigen Halle mit hohen Marmorsäulen. Um dich herum beten eifrig viele Priester zu ihrer Gottheit. Dieser Tempel ist der Tempel der Toleranz. Jeder der an die guten Götter glaubt, ist hier willkommen! Was möchtest du tun?`0 `n`QMit den anderen anwesenden flüstern`0 `n`n`n');
	//`n`n`$`bDie Götter wurden nur nach den Moderatoren benannt! diese sind NICHT ins RP einzubauen da es nur zur änderung der Namen gedient hat sie sind ganz normale Spieler bitte beachten!
	viewcommentary("Tempel","Flüstern:",10,"sagt");

				addnav('Zum Oberpriester gehen','goettertempel.php?op=priester');
                        addnav('Den Priester im roten Gewand anreden','goettertempel.php?op=roterpriester');
                        addnav('Den Priester im grünen Gewand anreden','goettertempel.php?op=gruenerpriester'); 
                        addnav('Den Priester im schwarzen Gewand anreden','goettertempel.php?op=schwarzerpriester');
                        addnav('Den Priester im sandfarbenen Gewand anreden','goettertempel.php?op=sandpriester');
			      addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
}
if ($_GET['op']=='priester') {

    page_header("Tempel der Götter.");
 
    output('`b`c`2Der Saal:`0`c`b');
if($session['user']['gottjanein']==0) output('`n`%Du sprichst den Oberpriester an, aber er antwortet dir nicht. Er zeigt nur Stumm auf ein Buch. Als du dir das Buch näher ansiehst, stellst du fest, dass hier tausende Leute eingetragen sind. Hinter ihren Namen stehen ihre Gottheiten. nachdem du das gesehen hast, fragst du dich, warum DU eigentlich keinen Gott vererst. Was tun?`0');


if($session['user']['gottjanein']==1) output('Der Priester scheint nicht answesend zu sein, du suchst ihn vergeblich!');
    output('`n`QMit den anderen anwesenden flüstern`0`n`n');
	viewcommentary("Tempel","Flüstern:",10,"sagt");
			if($session['user']['gottjanein']==0 || $session['user']['superuser']>=4) addnav('Einen Gott auswählen','gottwahl.php');
			addnav('Zurück','goettertempel.php');
}
if ($_GET['op']=='roterpriester') {
    page_header("Tempel der Götter.");
 
    output('`b`c`2Der rote Priester:`0`c`b');
    

if ($session['user']['gott']==1){
output('`n`%Du sprichst den Priester im roten Gewand an.`0 `nAh, ein Sohn des `QT`qæ`tr`&u`Qn`%! Herzlich willkommen! Du bist doch sicher gekommen um etwas über deinen Gott zu erfahren?`0 `nBevor du wiedersprechen kannst fängt er an zu reden: `n`$ `QT`qæ`tr`&u`Qn`$, als Gott der Schlachten und Krieger wird immer dann angebetet, wenn es um die Wendung des Schlachtenglücks, um Mut im Angesicht des Feindes und um Härte im Kampf Mann-gegen-Mann geht.
`QT`qæ`tr`&u`Qn`$ ist ein chaotischer Gott. Für ihn ist Krieg der beste aller möglichen Zustände, daher schürt er Konflikte um den Mutigen und Standhaften das Schlachtenglück zu schenken. Oftmals hilft er einfach beiden Parteien, um so die Schlacht zu verlängern. Er ist Ehrenhaft und folgt seinem Kodex, unterjocht sich jedoch keinen Regeln und folgt keinem anderen Ideal als dem Ruhm eines erfahrenen Kriegers. Ebenso wie Mystryl entstand `QT`qæ`tr`&u`Qn`$ aus einer der Schlachten zwischen Selûne und `IS`F`eh`aa`eï`ad`Fr`Ie`$. Anders als viele höhere Gottheiten hält `QT`qæ`tr`&u`Qn`$ niemals direkt Kontakt mit seinen Anhängern, sondern teilt seine Wünsche durch die Geister gefallener Helden und berühmter Krieger mit. `QT`qæ`tr`&u`Qn`%`$ steht mit der Roten Ritterin im Bunde, die er einst zu einer Gottheit machte und die ihm nun untersteht. Desweiteren steht er mit Uthgard, Gond, Nobanion und Valkur im Bunde. Er beschützt die Kirche Eldaths, da er das Ideal des Friedens als Kontrapunkt zu seinem Ideal des Krieges aufrecht erhalten will. Sein einziger wirklicher Feind ist der Gott Garagos, der Gott des Abschlachtens und Blutvergießens, da dieser das Ideal des Mordens über einen ehrenhaften Kodex des Kriegers stellt.
`QT`qæ`tr`&u`Qn`%`$ schlägt keine Schlachten. Nein, seine Gläubigen schlagen für ihn die Schlachten, erfüllt von seinem Mut und seiner Kraft. Die Zivilisation ist ein Wechselspiel aus Frieden und Krieg und nur der ist erfolgreich, der sich im Kriege behaupten kann, denn schweigen und sich verstecken, dass kann jeder zu Friedenszeiten. Nur die wahrhaft ehrbaren und standhaften Helden verdienen es, in `QT`qæ`tr`&u`Qn`%´s`$ Gnade zu baden. Schmeichler, die jeden Konflikt vermeiden, richten weit mehr Schaden an als der energische Tyrann.
Die Anhängerschaft `QT`qæ`tr`&u`Qn`%`$ ist ebenso chaotisch wie ihr Schutzpatron. Vertreter jeder Gesinnung finden sich unter dem Banner der Krieger vereint, auch wenn die Kleriker des `QT`qæ`tr`&u`Qn`%`$ weiterhin nur einen Gesinnungsschritt von ihrer Schutzgottheit abweichen. Sie agieren als Wächter über Schlachten, darauf bedacht einen Krieg ruhmreich zu führen und ihn nicht in ein Gemetzel ausarten zu lassen. Oftmals ziehen sie auch umher um sich den Herausforderungen zu stellen, die auf ihrem Weg zu einem Veteranen der Kriegskunst liegen.
Seine Tempel ähneln festunsgartigen Militärkasernen. `0');
addnav('Zurück','goettertempel.php');
}else{
output('`n`%Du sprichst den Priester im roten Gewand an.`0');
output('`n `$ Mit dir habe ich nichts zu besprechen!`0');
addnav('Zurück','goettertempel.php');
}