
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Zeremonien";
page_header("Zeremonien von Wolf's Realm");
switch($_GET['op']){
case '':
output("`c`&`&Es sollte vorher ausgemacht werden welche Zeremonie gewünscht ist.`n
Es wird gewartet bis das Brautpaar bereit ist, doch muss das Paar nachfragen.`n
Der Priester/die Priesterin haben immer ihre Zeremonienkleidung an.`n
Jede Zeremonie kann auch ein wenig auf das Brautpaar angepasst werden.`n
Es werden auch gleichgeschlechtliche Ehe geschlossen.`n
Doch muss dann geklärt werden wer welchen Sprechpart übernimmt.`n`n`c");
output("`n`n`_Mit anderen reden:`n");
viewcommentary("zere","reden",5);
addnav("`lzurück`0","kir.php");

addnav("`&Zeremonien von Wolfsrealm`0");
addnav("`sBänderzeremonie`0","zere.php?op=0");
addnav("`AGöttersegen`0","zere.php?op=1");
addnav("`sWolfstaufe`0","zere.php?op=2");
addnav("`ABarbarische Zeremonie`0","zere.php?op=3");
addnav("`sSilberschalenzeremonie`0","zere.php?op=4");
addnav("`ABandzeremonie`0","zere.php?op=5");
addnav("`sBandzeremonie 2`0","zere.php?op=6");
addnav("`ARosenzeremonie`0","zere.php?op=7");
addnav("`sKristallzeremonie`0","zere.php?op=8");
//addnav("`A`0","zere.php?op=9");
break;

case '0':
output("`c`A`bBänderzeremonien`b`n`s
Wenn es mit Ringen geschehen soll, muss das Paar sich überlegen wer sie hat.`n
Der/Die Priester/in nimmt die linke Hand der Braut und die Rechte des Bräutigam.`n
Er/Sie hatte ein blaues Band in der Hand und legte dies über die Hand der zu Vermählenden.`n
Das blaue Band ist der Segen der Wolfsgötter die Wolfsrealm beschützen.`n
Dann ein rotes Band und man legte es über das blaue Band auf die Hände der anderen.`n
Götter, welche dieses Reich beschützt vor allem Bösen.`n
Zuletzt kommt das Gelbe Band, man legte dies über die anderen zwei Bänder.`n
Das gelbe Band symbolisiert die Zusammengehörigkeit und den Schwur der ewigen Liebe.`n
Nun wird auf die Ringe gezeigt, wenn dies gewünscht ist und welche vorhanden sind.`n
Dann werden die Eheleute gebeten sich die Ringe an zustecken und ihre eignen Treueschwüre zu`n
sprechen.Der Zeremonienmeister/in nimmt die Hände mit den Bänder in seine/ihre eigenen Hände.`n
Durch den Segen Okamis und der Götter verbunden sollt sie einander lieben.`n
Dieser Segen soll ihnen Glück bringen und somit sind sie nun vereint.`n
Für jetzt und auf ewig. Dann werden die Händen der beiden los gelassen.`n
Damit wird das Ritual beendet und die Vermählung ist vollzogen.`n
Dann kommen Glückwünsche und das Fest wird in einen anderen Raum verschoben.`c`n`n");
addnav("Zurück","zere.php");
break;

case '1':
output("`c`s`bGöttersegen`b`n`A
Diese Zeremonie wird von tierischen Paaren bevorzugt.`n
Für diese Zeremonie benötigen sie keine Ringe, was bei Tieren ohnehin unpraktisch wäre.`n
Der/Die Priester/in wartet am Altar auf das zu trauende Paar.`n
Es ist üblich, dass das Paar zusammen nach vorne zum Altar schreitet.`n
Man kniet oder setzt sich vor den Altar, das Paar kann es sich aussuchen.`n
Man bitte die Wolfsgötter durch einen Treueeid um Segen für diese Verbindung.`n
Die Götter verstehen jede Sprache und somit auch jede Bitte um einen Segen für die Liebe.`n
Der/Die Priester/in wird stellvertretend für die Götter diesen Segen aussprechen.`n
Danach wird das Paar gebeten den göttlichen Segen durch einen Kuss zu besiegeln.`n
Ist dies getan ist das Ritual vollzogen und die Vermählung ist besiegelt.`n
Zum Abschluss können Glückwünsche ausgesprochen werden. `n
Das anschließende Fest findet in einem anderem Raum statt.
`c`n`n");
addnav("Zurück","zere.php");
break;

case '2':
output("`c`A`bWolfstaufe`b`n`s
Diese Zeremonie ähnelt einer Taufe, ganz wie der Name schon vermuten lässt.`n
Einst durften nur Wolfsgötter diese Zeremonie für sich halten.`n
Inzwischen kann jedes Wesen auf diese Art und weiße heiraten.`n
Diese Zeremonie ist auch für Tiere gedacht, deshalb werden keine Ringe benötigt.`n
Statt der Ringe kann eine Art Fußkette oder ein Band benutzt werden, wenn das Paar es wünscht.`n
Diese Kette bewahrt entweder der/die Priester/in oder ein/e Helfer/in auf.`n
Bei dieser Zeremonie ist es üblich, dass das Brautpaar zusammen in der Kirche kommt.`n
Sie laufen vor bis zum Altar und setzen sich vor diesen.`n
Dabei legt der Bräutigam eine Pfote/Hand auf die Pfote/Hand der Braut.`n
Der/Die Priester/in nimmt einen Kelch mit Götterwasser und schreitet vor das Paar.`n
Erst wird ein Tropfen aus dem Kelch auf den Kopf des Bräutigam gegossen.`n
Dabei werden die Worte, `IDas ist das Wasser der Götter, `sgesprochen.`n
Dann wird der Braut ein Tropfen Wasser auf die Stirn gegossen.`n
Hierbei werden die Worte, `Idass den Bund der Liebe festigt. `sgesprochen.`n
Dann wendet sich der/die Priester/in an das zu vermählende Paar.`n
Der Letzte Tropfen kommt auf die zuvor aufeinander gelegten Pfoten/Hände.`n
Dieser letzte Schritt wird mit den Worten, `IEs wird euch von nun an verbinden. `sbeendet.`n
Wenn eine Art Fußkette/Band vorhanden ist wird diese nun den Partnern angelegt.`n
Dann wird die Zeremonie vom Priester/in beendet und das mit den Worten.`n
`INun seit ihr vermählt, sollt einander Lieben und euch zur Seite stehen.`n
`sZum Abschluss können Glückwünsche ausgesprochen werden. `n
Das anschließende Fest, falls ein Fest gewünscht ist, findet in einem anderem Raum statt.`n
Oder das Paar geht einfach seiner Wege.`c`n`n");
addnav("Zurück","zere.php");
break;

case '3':
output("`c`s`bBarbarische Zeremonie`b`n
Die Priesterin wartet in der Kirche, das Paar und die Gäste kommen in die Kirche dabei machen sie so viel Lärm wie möglich (Töpfe sind 
prädestiniert dafür).`n
Das Paar lümmelt sich auf einer Decke vor dem Altar hin und Ale wird verteilt.`n
Die Priesterin nimmt ihr Glas und spricht zu den Anwesenden`n
`C„Heute sind wir hier um diese beiden zu verheiraten, da mit sie einen Haufen kleiner Schreihälse in die Welt setzten und sich glücklich bis an ihr 
Lebensende ankeifen.“`n
`sDie Priesterin hebt das Glas `C„Hat wer was dagegen?“`n
`sWenn die Leute einverstanden sind trinken sie ihr Glas in einem Zug leer, wenn nicht schmeißen sie es nach vorne (Priesterin in Deckung!!! XD)`n
Dann wendet sich die Priesterin an das Paar.`n
`C„Wie sieht es bei euch aus? Willst du _____ die hier anwesende Frau _____ nehmen oder nicht? Falls ja, dann rülpse bitte laut, falls nicht, dann raus 
hier.“`n
`sIm besten Fall bebt die Kirche vom Rülpsen des Bräutigams.`n
Die Priesterin wendet sich an die Braut.`n
`C„Wie sieht es mit dir aus? Willst du den hier stehenden Barbaren haben?“`n
`sDie Kirche bebt wieder wenn ja.`n
Die Priesterin nickt `C„So soll es sein.“`n 
`sSie setzt das Glas an die Lippen, nimmt einen großen Schluck und spuckt das Ale auf das Brautpaar.`n
`C„Mit dem hier und meinem Segen seid ihr nun verheiratet, und nun trollt euch ihr Pack.“ `n
`sDas Brautpaar steht auf und marschiert Richtung Ausgang, falls jemand der Gäste noch Ale übrig hat soll er das auf das Paar spucken, wer den Ausschnitt 
der Braut als erster trifft muss/darf sich bei der Feier noch ganz feierlich ein Wettsaufen mit dem Bräutigam liefern.`n
`ADann kommen Glückwünsche und das Fest wird in einen anderen Raum verschoben.`c`n`n");
addnav("Zurück","zere.php");
break;

case '4':
output("`c`A`bSilberschalenzeremonie`b`n
Die Gäste betreten nacheinander die Kirche und setzen sich.`n
Das Paar betritt als letztes die Kirche und schreitet vor zum Altar, wo sie sich auf jeweils ein Kissen niederknien (Farben nach Wahl, mehrere im 
Angebot).`n
Als letztes kommt die Priesterin in die Kirche, sie trägt eine silberne Schale mit blauen Ornamenten, sie ist leer.`n
Die Priesterin tritt zwischen Paar und Altar.`n
Sie wendet sich zum Paar und fragt sie `q„Seid ihr bereit?“`n
`ANachdem beide mit `q„Ja“ `Ageantwortet haben hält sie die Schale in die Höhe, das Licht der Sonne/des Mondes fällt durch das Fenster hinter dem Altar 
und fällt in die Schale, in welcher eine silberne Flüssigkeit sichtbar wird. Sie senkt die Arme wieder bis die Schale auf Brusthöhe ist und beginnt dann 
zu sprechen.`n
`q„Seht in diese Schale, noch scheint ihr Inhalt trüb und kalt, so wie ein Herz ohne Liebe, doch ein kleiner Funken reicht manchmal um ein Feuer zu 
entzünden.“`n
`ADie Priesterin greift in die Schale zu ihrer linken und nimmt mit einer Zange einen kleinen glühenden Splitter heraus und lässt ihn in die Schale 
fallen die sofort in Flammen steht.`n
`q„Doch mit diesem Feuer ist es wie mit allen, ohne Nahrung vermag es nicht zu überleben. So seid füreinander, wie die Nahrung die das Feuer braucht, 
die Nahrung für euere Liebe. Vergesst nicht, das diese Feuer erlöschen kann und bedenkt auch das es umschlagen kann und gefährlich zu werden vermag. 
Doch solange eure Liebe rein ist wird es euch wärmen und schützen.“`n
`ADie Priesterin stellt die Schale auf einen dafür vorgesehenen Sockel und nimmt die jeweils linke Hand des Paares und führt sie zusammen über das Feuer.
(die Hände sollten keinen Schaden nehmen)`n
`q„Ehrlichkeit ist wie die Treue wichtiger Bestandteil dessen was diese Feuer so hell macht, und ihr seid ohne Furcht vor dem was euch erwartet. So möge 
nun das Zeichen eurer Bindung, aus den Flammen erstehen die eure Liebe repräsentieren.“`n
`ADie Flammen erlöschen und in die Schale fallen Die Ringe (wahlweise anderer Schmuck) des Paares.`n
`q„Nehmt nun die Ringe (oder was auch immer) und bringt selbst eure Bindung zur Vollendung.“`n
`ADas Paar steckt sich die Ringe an (legt sich die Ketten um oder die Armreife oder was auch immer)`n
`q„Nun ist vollendet was vollendet sein soll, nun ist zusammengebracht was zusammen sein soll, nun ist vereint was Vereinigung anstrebt. Geht nun und 
tretet hinaus in das Licht eines neuen Lebens, das ihr nun zusammen beschreiten sollt.“`n
`sAlle verlassen die Kirche und gehen feiern.`c`n`n");
addnav("Zurück","zere.php");
break;

case '5':
output("`c`s`bBandzeremonie`b`n
Es ist Abend (muss aber nicht sein)`n 
Die Leute sammeln sich langsam in der Kirche der/die Priester/in war als erstes da und kniet vor dem Altar und betet schon zu den Göttern`n
Das Brautpaar schreitet nach vorne, der/die Priester/in dreht sich zu den Leuten und dem Paar um, fragt das Paar ob sie bereit sind, hebt die Arme und 
beginnt zu sprechen. `n
`C„Es heißt Menschen treffen sich immer 2 Mal im Leben. Treffen sie sich 3 Mal ist es Schicksal. Dieses Schicksal hat dazu geführt, dass ihr heute hier 
steht.`n
`sWenn der neue Tag anbricht wird sich eure Beziehung komplett verändert haben und (so ihr es wollt) in neuem Licht erstrahlen.`n 
Seid ihr ------ (Name 1) und ------ (Name 2) bereit diesen Schritt in eine neue, euch noch unbekannte Zukunft zu tun?“`n
Das Brautpaar sollte mit `C„Ja“ `santworten.`n
Der/die Priester/in dreht sich um und nimmt 2 Bänder (eins ca. 1 Meter lang und das 2. ca. 50 Zentimeter lang, deren Farbe von dem Brautpaar ausgesucht 
worden ist ? einfarbig!), dreht sich wieder zu den Leuten und hebt die Bänder über seinen/ihren Kopf`n
`C„Ihr Götter und guten Geister, erhört mich. Ich bitte euch um euren Segen und eure Hilfe zu Gunsten dieses jungen Paars.“`n
`sDie Götter gewähren ihren Schutz und ein Lichtstrahl fällt durch das Fenster hinter dem/der Priester/in direkt auf das Band, welches anfängt zu 
glitzern, wendet sich wieder an das Brautpaar`n
`C„Die Götter haben euch ihren Schutz gewährt, so legt eure linke Hand flach aneinander, die Hand des Herzens, ich werde euch nun verbinden, sodass eure 
Liebe nichts zu zerbrechen vermag.“`n
`sDer/die Priester/in schlingt das kürzere Band fest um die beiden Hände des Paars`n
`C„Nun ist es eure Aufgabe eure wahre Bindung mit diesem 2. Band zu bezeuge, da ich und auch sonst niemand es vermag euch wahrlich zu binden. Ihr seid es 
die ihr euch binden müsst.“`n
`sDer/die Priester/in reicht dem Paar das längere Band, nur wenn sie beide gemeinsam ihre rechte Hand benutzen können sie es schaffen je ein Ende des 
Bandes um das jeweils linke Handgelenk zu schlingen und zu verknoten -> Zusammenarbeit ist gefordert`n
`C„So habt ihr nun eure Verbindung vor den Augen der Götter und Aller bestätigt, so ist das erste Band sinn- und wertlos.`n
`sDer/die Priester/in berührt das 1.Band und es fällt einfach zu Boden`n
`C„Habt ihr Worte die ihr euch sagen wollt?“`n
`sBei `C„Ja“ `ssollen sie sie sagen (Vorschläge stehen sprachlich weniger Begabten zur Verfügung), bei `C„Nein“ `sgeht es weiter wie gehabt`n
`C„So frage ich euch vor Vollendung der Zeremonie ein weiteres Mal: Seid ihr euch eures Schrittes bewusst?“`n
`sDas Brautpaar antwortet hoffentlich mit `C„Ja“`s
`C„Eure Herzen sollen vereint sein, sie sollen als eins schlagen im Takt eurer Liebe.“`n
`sDer/die Priester/in berührt zuerst das Band und führt beide Hände des Paares zusammen`n
`C„Es wird Zeit, das Zeichen euer Liebe und Verbindung ein Zeichen von Gestalt werden zu lassen.“`n
`sBei diesen Worten leuchtet das Band auf und formt sich zu durchgehenden Reifen ohne Schmiedestelle -> Material wählbar`n
`C„Nun ist vollendet was vollendet sein soll, nun ist zusammengebracht was zusammen sein soll, nun ist vereint was Vereinigung anstrebt. Nehmt diese 
Reifen als materiellen Beweis für eure Liebe. Ihr wolltet es so, die Götter haben es so gewollt, so verlasst nun diesen Ort (diese Kirche), als Ehepaar 
und tretet in das Licht eines neuen Tages, in das Licht eines neuen Lebens.“`n
`ADer/die Priester/in tritt vor und verlässt gefolgt vom Brautpaar und den Gästen den Ort der Zeremonie und geleitet die Menge zu dem Ort wo das Fest 
stattfindet`c`n`n");
addnav("Zurück","zere.php");
break;

case '6':
output("`c`A`bBandzeremonie 2`b`n
Die Priesterin wartet vor der Kirche bis alle Gäste platz genommen haben.`n
Dann schreitet sie in die Kirche und wendet sich vorne am Altar den Gästen zu.`n
Das Paar betritt die Kirche, schreitet den Gang entlang, bleibt vorne stehen, beide der Priesterin zugewandt.`n
Die Priesterin segnet das Paar im Namen der gewählten Gottheit, dann erst beginnt die Zeremonie.`n
`q„Jetzt wo der Tag sich zu Ende neigt und die Nacht ihr Tuch über die Welt breitet, erstrahlt das Licht eurer Liebe heller als je zuvor, und hat uns 
hier zusammen geführt. Nun sagt mir wollt ihr euch hier vor dem Angesicht der Götter die (ewige) Liebe schwören? Wenn ja dann wendet euch zueinander und 
sagt euch in euren Worten, was ihr empfindet“`n
`AEs folgen die Treueschwüre.`n
Die Priesterin hebt wieder an zu sprechen sobald die beiden zu Ende gesprochen haben.`n
`q„Es ist nun an der Zeit eure Verbindung vor den Göttern zu bezeugen“`n
`ASie dreht sich um, nimmt Bänder in die Hände, hält sie hoch, dreht sich wieder zum Paar und spricht: `q„Lasst uns die Götter und guten Geister anrufen, 
damit sie ihre Hand schützend über dieses junge Paar halten“`n
`AEin Lichtstrahl fällt durch das Fenster(wahlweise durch eine Öffnung in der Decke) auf das Band.`n
Die Priesterin legt kurzes Band um die Hände des Paares, nachdem sie sich wieder umgedreht hat.`n
`q„Somit erkläre ich als Vertreterin der Götter diesen Pakt als bindend. Doch dies reicht nicht aus, nun müsst ihr euch selbst verbinden, damit euer 
Ehebund Gültigkeit erlangt. Legt dieses Band“ `Asie hält es hoch `q„um eure Handgelenke und befestigt es mit der Hilfe eures Partners.“`n
`ASie gibt ihnen das Band und wartet, das Paar bindet das Band fest.`n
`q„So sei es dann, eure Bindung ist nun fast vollendet“`n
`ABerührt das Band um die Hände, es fällt zu Boden. Im nächsten Moment erglüht das gebundene Band und formt sich zu einem Amulett/Ringe/Anhänger (mit 
einem Stein in der Farbe des Bandes).`n
`q„Nun geht und feiert mit der Götter Segen.“`n
`sDreht sich kurz zum Altar und spricht ein kurzes Gebet für das Paar, verlässt dann las letzte lange nach dem Paar und den Gästen die Kirche.`c`n`n");
addnav("Zurück","zere.php");
break;

case '7':
output("`c`s`bRosenzeremonie`b`n
Die Gäste betreten nacheinander die Kirche und setzen sich.`n
Das Paar betritt als letztes die Kirche und schreitet vor zum Altar, wo sie sich auf jeweils ein Kissen niederknien (Farben nach Wahl, mehrere im Angebot).`n
Ein Kissen ist auf einer Säule angebracht die leicht versetzt zwischen den Kissen steht.`n
Die Priesterin wartet im Schrein bis alle da sind die dabei sein möchten, dann tritt sie hinaus zum mit Rosen geschmückten Altar.`n
Sie fragt die beiden `C„Seid ihr bereit?“`n
`sWenn die Antwort `C„Ja“ `sist beginn sie mit der Zeremonie.`n
`C„Heute ist ein Tag der besonders ist, ein Tag der euch in Erinnerung bleiben soll. Euch allen die ihr hier seid, doch besonders diesem Paar hier, das 
sich entschlossen hat sich auf den Bund der Ehe einzulassen.“`n
`sDie Priesterin nimmt 2 Rosen vom geschmückten Altar, die eine in die linke, die andere in die rechte Hand und wendet sich wieder zu dem Paar.`n
`C„Seht diese Rosen, sie stehen in voller Blüte, wie auch eure Liebe, strotzt sie vor Kraft, Schönheit und auch Reinheit. Doch bedenkt dass selbst diese 
Rose Dornen hat.`n
Diese Dornen können die Hand verletzen wenn der Griff selbiger zu stark wird, so ist und bleibt es ein Spiel das immer auch Narben hinterlassen kann.“`n
`sDie Priesterin legt die Rosen auf das Kissen zwischen dem Paar.
`C„Denkt daran euch nie von dem Willen nach Besitz lenken zu lassen, den je fester ihr euch an eure Liebe klammert umso zerbrechlicher wird sie. Und 
bedenkt auch, dass ihr euch durch eine solche Nähe zum anderen auch immer Verletzungen zuziehen könnt.`n
Seid ihr gewillt dieses schwierige Unterfangen auf euch zu nehmen und die Schmerzen die euch widerfahren können auf euch zu nehmen?“`n
`sBeide sagen `C„Ja“ `swenn das der Fall ist.`n
`C„Und seid ihr bereit über kleinere Verletzungen die euch der andere zufügen könnte hinweg zusehen?“`n
`sBeide antworten wieder mit `C„Ja“`n
„So hebt nun die Rosen, Symbole eurer zarten und doch starken und vielfältigen Liebe, auf und sagt euch in euren eigenen Worten was ihr empfindet.“`n
`sNun ist es an dem Paar zu sprechen, die Dame zuerst, Kreativität ist gefragt.`n
Nach dem die beiden fertig sind, sie haben immer noch die Rosen in der Hand, setzt die Priesterin ihre Predigt fort.`n
`C„Nun lasst die Rosen in euren Händen, durch eure Herzen zu dem formen was ihr als Zeichen tragen wollt.“`n
`sAus den Blüten formt sich jeweils ein Ring mit einem roten eingeschlossenen Stein.`n
`C„In diesen Ringen befindet sich jeweils ein Tropfen eures Blutes, den ihr vergossen habt, als Symbol dafür das der andere es euch Wert ist und dass ihr 
gewillt seid die Verletzungen die euch trotz eurer großen Liebe widerfahren könnten hinzunehmen.“`n
`sSie wendet sich an die Braut.`n
`C„Nun steckt dem von dir erwählten _______ den Ring an den Finger, der ihn immer an deine Liebe erinnern soll.“`n
`sDie Braut steckt ihrem Zukünftigen den Ring an den Finger.`n
Die Priesterin wendet sich an den Bräutigam.`n
`C„Und nun steckt du __________ die du in Liebe gewählt hast, den Ring an, der sie immer an deine Treue erinnern soll.“`n
`sAuch der Bräutigam steckt der Braut den Ring an.`n
`C„Nun ist vollendet was vollendet sein soll, nun ist zusammengebracht was zusammen sein soll, nun ist vereint was Vereinigung anstrebt. Nehmt diese 
Ringe als materiellen Beweis für eure Liebe und trete nun als Ehepaar aus dieser Kirche hinaus auf einen Weg, den ihr nun gemeinsam beschreiten werdet.“`c`n`n");
addnav("Zurück","zere.php");
break;

case '8':
output("`c`A`bKristallzeremonie`b`n
Die Priesterin erwartet das Paar schon als sie in die Kirche treten. Das Paar schreitet an den Gästen vorbei nach vorne, wo es vor dem Altar 
stehen bleibt.`n
Die Priesterin beginnt zu sprechen.`n
`C„Versammlungen finden aus vielerlei Gründen statt, aus Wichtigeren und Unwichtigeren. Doch heute haben wir uns zu einer ganz besonderen zusammen 
gefunden. Sich abhebend von allen anderen und einzigartig in ihrem sein. Eine Versammlung aus Liebe.“`n
`sSie weist auf den Boden links und rechts sowie vor dem Brautpaar.`n
`C„Seht ihr die Steine die sich vom Boden abheben? Es sind 4 an der Zahl. Einer ist rot wie das Feuer, einer grün wie eine Frühlingswiese, einer blau wie 
die unendlichen Tiefen des Meeres und einer scheint keine Farbe zu besitzen, doch hält man ihn ins Licht strahlt er in allen.`n
Rubin, Smaragd, Saphir und Diamant. Schön, wertvoll und doch unvollkommen.“`n
`sSie hebt die Hände und aus goldenem und silbernem Staub bildet sich ein Muster auf dem Boden das sich sanft um die Steine schlingt und sie verbindet.`n
`C„Ihren Wert erhalten sie durch den Schliff und genauso erhaltet ihr euren Wert durch den Schliff des anderen. Ihr kommt euch näher und eure grauen 
Hüllen bröckeln nur um eure wahre Schönheit einander zu offenbaren. So wie die Steine links, rechts, vor und hinter euch.`n
Ja ihr seid nun mitten unter ihnen. Doch eine weitere Gabe ist ihnen inne und was sie abzuhalten mögen sollen sie tun, und was sie zu geben mögen, dass 
mögen sie eurer Liebe geben.“`n
`sDie Priesterin hebt einen weiteren roten Stein hoch.`n
`C„Dies ist der Rubin, er liegt im Süden von euch sowie auch das Element Feuer diese Richtung zugeordnet wird. Er ist der Bewahrer der Liebe und schenkt 
euch Mut. Den Mut eueren Weg gemeinsam zu gehen.“`n
`sDann nimmt sie einen grünen Stein vom Altar auf.`n
`C„Dies ist der Smaragd, er liegt im Norden von euch, sowie auch das Element Erde dem Norden zugeordnet wird. Er ist der Stein des ewigen Frühlings und 
hält eure Liebe jung, somit ist er auch der Stein der Leidenschaft, die füreinander in euren Herzen schlägt.“`n
`sNun ein blauer Stein.`n
`C„Dies ist der Saphir, er liegt im Westen von euch sowie auch das Element Wasser dieser Richtung zugeordnet wird. Er ist der Stein der Treue, und soll 
euch die Treue schenken dem anderen beizustehen egal was euch erwartet.“`n
`sNun zuletzt einen durchsichtigen Stein, der ebenfalls hochgehalten wird.`n
`C„Dies ist der Diamant, er liegt im Osten von euch, sowie auch das Element Wind dem Osten zugeordnet wird. Er ist der Stein der Wahrheit und Schönheit, 
und so wie dieser Stein soll auch eure Liebe sein, wahr und voller Schönheit.“`n
`sAlle 4 Steine liegen nun nebeneinander auf dem Altar.`n
Die Priesterin dreht nimmt nun die Ringe die auf einem Kissen die ganze Zeit auf dem Altar lagen.`n
`C„Doch genug der großen Worte, ihr wünscht diesen Bund, ihr kennt alle Konsequenzen die er mit sich bringt und ihr wisst auch von den Freuden. So nehmt 
die Hand eures Partners und bezeugt dies nochmals vor ihm.“`n
`sTreueschwüre die Braut beginnt.`n
`C„Nun habt ihr vor allen noch einmal deutlich gemacht wie ihr fühlt, so sollen weder die Götter noch ich zwischen euch stehen, reicht mir eure Hände.“`n
`sSie legt die Hände zusammen und die Ringe hinein.`n
`C„Auf jedem dieser Ringe befinden sich 4 Steine als Erinnerung an das was ihr euch heute geschworen habt.“`n
`sSie wendet sich an die Braut.`n
`C„Nun gilt es zu fragen, mit den Göttern als Zeugen willst du ______ diesen Mann hier zu deinem Manne nehmen?“`n
`sDie Antwort ist hoffentlich `C„Ja.“`n
`sDann wendet sie sich an den Bräutigam.`n
`C„So frage ich auch dich _______ willst du diese Frau in Liebe zu der deinen machen?“`n
`sWieder ein `C„Ja.“ `sOder auch `C„Ja, ich will.“`n
`sDabei streifen sie ihrem Partner die Ringe über.
`C„So seid ihr, unter dem Schutz der Steine und dem der Priesterschaft sowie der Götter nun Mann und Frau. Geht nun zu dem Fest das eurer Liebe, eurer 
großen Liebe, wegen gehalten wird, doch bedenkt die 4 Tugenden die ich euch heute lehrte, Mut, Leidenschaft, Treue und Wahrheit, den schon morgen ist 
nichts mehr so wie heute.“`n
`ADas Paar und die Gäste verlassen die Kirche um zu feiern, die Priesterin bleibt zurück und räumt nachdem alle weg sind die Steine und den Sand 
weg, dann folgt sie zum Festplatz.`c`n`n");
addnav("Zurück","zere.php");
break;

case '9':
output("`c`s`b`b`n
`ADann kommen Glückwünsche und das Fest wird in einen anderen Raum verschoben.`c`n`n");
addnav("Zurück","zere.php");
break;
}
page_footer();
?>

