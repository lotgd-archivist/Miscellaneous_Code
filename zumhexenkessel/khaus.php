<?php
/*Krankenhaus by Spitzi
Für Land Everon http://www.spitzisundcindysgame.de
31.10.06
hier können Mütter in einem Geburtsraum ihre Kinder zur Welt bringen. Kranke können sich von einem Pfleger heilen lassen.
SQL: ALTER TABLE `accounts` ADD `pfleger` INT( 11 ) NOT NULL DEFAULT '0';
*/
require_once "common.php";
addcommentary();
$pfleger=false;
if ($session['user']['pfleger'] ||$session['user']['superuser']>1){
    $pfleger=true;
}
page_header("Das kleine Krankenlager von Aladrion");
if ($HTTP_GET_VARS[op]==""){
output("`kUnsere Hebammen und Pfleger: zur Zeit niemand`n`n");
    output("`RIm Krankenhaus triffst Du viele bekannte Kämpfer; einige haben furchtbare Narben vom Kampf mit der grünen Bestie davongetragen und lassen sich nun hier von einigen guten Seelen und ein paar angehenden Heilern wieder gesund pflegen; doch gibt es auch harmlose Verletzungen wie Beulen vom Umkippen in der Taverne über blaugeschlagene Augen von Ehezwistigkeiten oder Streitereien zwischen Nebenbuhlern um eine schöne Maid sowie ab und an mal ein zerquetschter Fuss vom Baumfällen......und einige Frauen verschwinden weiter hinten in einem separaten Raum um sich bei der Geburt ihres Kindes helfen zu lassen..
Du blickst dich interessiert um...`n`n");
viewcommentary("Khaus","flüsstern",40,"flüsstert");
addnav("Krankenhaus verlassen","village.php");

if($session['user']['pfleger']>=1 || ($session['user']['superuser']>1) || $session['user']['ssstatus'] == 1 && $session['user']['ssmonat'] <= 1){
addnav("zum Geburtsraum","khaus.php?op=geburt");
}
if($session['user']['pfleger']>=1 || ($session['user']['superuser']>1) || $session['user']['ill']>=1){
addnav("Krankenzimmer","khaus.php?op=kzimmer");
}
addnav("Zahnausreisser","khaus.php?op=zahn");
}
if($session['user']['jobid'] ==3){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","khaus.php?op=da");
addnav("Kündigen","khaus.php?op=go");
addnav("Stehlen","khaus.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==2){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","khaus.php?op=da");
addnav("Kündigen","khaus.php?op=go");
addnav("Stehlen","khaus.php?op=ste");

}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
addnav("zurück","khaus.php");
}
}
if($session['user']['jobid'] ==17){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","khaus.php?op=dazwei");
addnav("Kündigen","khaus.php?op=go");
addnav("Stehlen","khaus.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
addnav("zurück","khaus.php");
}
}
if($session['user']['jobid'] ==20){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","khaus.php?op=dazwei");
addnav("Stehlen","khaus.php?op=ste");
addnav("Kündigen","khaus.php?op=go");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
addnav("zurück","khaus.php");
}
}
if ($HTTP_GET_VARS[op]=="geburt"){
output("`gDie Hebamme macht das grosse Lager für die Geburt Eures Nachwuchses bereit. Als sie damit fertig ist, lässt sie leise Musik im Hintergrund spielen zur Entspannung und entzündet in einer Schale wohlriechende Kräuter. Ein paar Minuten später merkt Ihr, wie die Wehen immer schlimmer werden. Es wird nicht mehr lange dauern und Ihr könnt euer kleines Baby mit nach Hause nehmen. Die Stunde der Geburt ist nun nahe. Kreischend und stöhnend windet Ihr euch hin und her.`n `%'Pressen..Pressen'`n`ghört Ihr jemanden rufen. Ihr presst und presst, es kommt Euch wie Tage vor. Nach stundenlanger, schmerzvoller Prozedur merkt Ihr, wie das Baby endlich Euren Körper verlässt. Die Hebamme nimmt das kleine Geschöpf und legt es Euch in ein Tuch gewickelt auf die Brust. Mit Tränen in den Augen bewundert Ihr voller Stolz und Glück Euren Nachkömmling. Nach einigen Stunden Ruhe zieht Ihr Euch wieder an und verlasst mit Eurem Kind das Krankenhaus.`n`n");

viewcommentary("Khausgeburt","flüsstern",40,"flüsstert");
addnav("zurück","khaus.php");
    }

if ($HTTP_GET_VARS[op]=="kzimmer"){
$session['user']['ill']=0;
output("`mEine gute Seele und ein angehender Heiler kommen zu Dir gelaufen und betrachten Deine Verletzungen. Sie helfen Dir, auf einer Liege Platz zu nehmen. Nach ein paar Augenblicken rumfuhrwerken merkst Du nichts mehr von Deinem Schmerz und gehst fröhlich wieder zurück in's Dorf zu Deinen Freunden.");

addnav("zurück","khaus.php");
}
if ($HTTP_GET_VARS[op]=="zahn"){
$session['user']['ill']=0;
output("`gText fehlt noch`n`n");




viewcommentary("Khauszahnreisser","flüsstern",40,"flüsstert");
addnav("zurück","khaus.php");
    }

if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`8Voller Zuversicht gehst du zum Leiter des Krankenhauses, dem Heiler Patitus. Es war ein kleiner Mann mit aber durchaus stattlichem Spitzbart, wo du bereits Angst bekamst, dass er jeden Augenblick darüber stolpern würde. `n
Mit prüfenden Blicken begann er dich zu mustern `&Ai, ihr müsst Arbeit suchen nicht wahr? Kommt, kommt es gibt reichlich zu tun. Kranke und Verletzte haben wir zur Genüge bei uns. `8 `n Ohne ein Widerwort lässt du dich von ihm an deinen künftigen Arbeitsplatz schieben.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`&Seid mir gegrüßt, ihr wurdet bereits erwartet `9 heißt es von der Schwester und sie weißt euch in eure Arbeit für die nächsten 2 Stunden ein, für die ihr einen Lohn von 1000 Gold bekommen solltet.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`9Mit eiligen Schritten führt dich die Schwester zu deinem ersten Auftrag, noch ehe du ihr einen guten tag wünschen konntest. Sie meinte nur, dass es zwar nur für 3 Stunden Arbeit gab, doch schien diese wohl umso dringender zu sein. `n
Den Lohn von 1500 Gold erhältst du im Anschluss`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`& Eure Dienste werden heute 4 Stunden lang benötigt `9meint die Schwester in freundlichem Ton und tritt zur Seite, damit du eintreten kannst. Sicher würde sich die Arbeit nicht von alleine erledigen und so erledigst du eine Aufgabe nach der anderen und gehst schließlich mit 2000 Gold als Entschädigung nach Hause.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`9ein Seufzen drang über deine Lippen. Heute würdest du also 5 Stunden lang nichts anderes sehen als die Kranken und die tristen Wände des Krankenhauses. Wenigstens belief sich deine Entschädigung dafür auf gute 2500 Goldstücke`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`&Verzeiht, doch müsst ihr heute leider ganze 6 Stunden arbeiten `9 Dieser Satz hallte dir immer und immer wieder im Kopf hin und her. Es würde ja ewig dauern, bis du endlich fertig warst. 6 Stunden zum Lohn von 3000 Gold… `n
Warst du eigentlich der Einzigste, der so lange arbeiten musste? Irgendwie hattest du das Gefühl, dass es so war.`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
addnav("zurück","khaus.php");
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}

if ($_GET['op']=="dazwei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`8Voller Zuversicht gehst du zum Leiter des Krankenhauses, dem Heiler Patitus. Es war ein kleiner Mann mit aber durchaus stattlichem Spitzbart, wo du bereits Angst bekamst, dass er jeden Augenblick darüber stolpern würde. `n
Mit prüfenden Blicken begann er dich zu mustern `&Ai, ihr müsst Arbeit suchen nicht wahr? Kommt, kommt es gibt reichlich zu tun. Kranke und Verletzte haben wir zur Genüge bei uns. `8 `n Ohne ein Widerwort lässt du dich von ihm an deinen künftigen Arbeitsplatz schieben.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`9Lächelnd tritt dir die Schwester entgegen und schaut dich freundlich an `&Schön, dass ihr da seid. Heute wird es nur 2 Stunden lang Arbeit für euch geben. Dennoch werdet ihr 1500 Gold erhalten. `R Danach dreht sie sich herum und deutet euch an, ihr zu folgen, damit sie euch zeigen kann, was es heute zu erledigen gibt.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`9Mit gewohnt typischem Lächeln begrüßt dich die Schwester am Eingang `&3 Stunden werden es heute sein `9 meint sie und du machst dich sofort ans Werk. Nach getaner Arbeit verlässt du mit 2250 Goldstücken das Krankenhaus.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case '3':
output("`&3000 Gold für 4 Stunden Arbeit `9 meint die Schwester freundlich und begleitet dich ins innere des Krankenhauses, wo du dich natürlich sofort an die Arbeit machst, damit du nicht bis in die Nacht zu tun hast.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '4':
output("`9Immer wieder auf die Turmuhr schauend wirst du von der Schwester in Empfang genommen und sie schiebt dich gleich in die Umkleide `&Bitte beeilt euch es warten 5 Stunden Arbeit auf euch. Den Lohn von 3750 Goldstücken werdet ihr im Anschluss erhalten`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case '5':
output("`9Du schaust die Schwester enttäuscht an als sie dir mitteilte, dass du für die nächsten 6 Stunden hier arbeiten würdest. 6 Stunden zum Lohn von 4500 Goldstücken. Irgendwie eine geringe Entschädigung für die Mühe aber was blieb dir schon anderes übrig?`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=4500;
$session['user']['jobda']+=1;

break;
addnav("zurück","khaus.php");
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`7Mit ernstem Blick schleichst du dich zu den Geldern, die zur Unterstützung des Krankenhauses von den Einnahmen beiseite gelegt werden. `n
Irgendwie tat es dir zwar leid, doch hattest du eine kleine und zusätzliche Entschädigung für deine Mühen nötig. Zumindest war das so deine Ansicht. `n
Schnell greifst du dir 5.000 Gold aus der Kasse und verschwindest damit so schnell, wie du gekommen warst.
`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Zufrieden lässt du 3000 Gold in deine Taschen wandern, nachdem dich schon keiner dabei beobachtet hatte, dass du hier eingedrungen warst um etwas zu stehlen. `n
Die beute sollte doch fürs Erste einmal reichen, du könntest es ja später noch einmal versuchen. Auf leisen Sohlen schleichst du dich wieder nach draußen und gibst darauf Acht, dass dich keiner sieht.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`7Ein Grinsen breitete sich auf deinen Lippen aus als du zum ersten Mal zu sehen bekommst, wie reich das Krankenhaus wirklich war. Da konnte man doch einfach nicht widerstehen und so steckst du 6 der Edelsteine ein. `n
Bei solchen Mengen würde es wohl kaum auffallen. Nie hättest du zu träumen gewagt, dass dein Raubzug so erfolgreich sein würde. Und es hatte noch nicht mal einer bemerkt, bis du in Sicherheit warst.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`7Im Schutze der Nacht pirscht du dich leise zur Schatzkammer vor, ja darauf bedacht, dass keiner auf dich aufmerksam wurde. Die Kranken schliefen sowieso und die Pfleger und Schwestern waren nicht anwesend, geschweige denn der Rest des Personals. `n
Lächelnd nimmst du dir 4 Edelsteine und beschließt es am besten wäre es erst einmal dabei zu belassen und es vielleicht später noch einmal zu versuchen. `n
Das Glück war erneut auf deiner Seite, sodass du unerkannt entkommen kannst.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Lautlos drückst du die Tür zur Schatzkammer des Krankenhauses auf und vergewisserst dich erst einmal, dass du auch wirklich ungestört sein würdest. Nachdem alles ruhig blieb trittst du vorsichtig ein und beginnst damit, deine Taschen zu füllen. `n
Leider kamen in diesem Augenblick 2 Pfleger um die Ecke und du musstest dich schnell verstecken. Dabei fällt dir ein Großteil des Diebesgutes wieder herunter und du kannst im Endeffekt nur 1000 Gold sicher herausschaffen. Aber es war immer noch besser als, wenn sie dich erwischt hätten.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`7In aller Stille näherst du dich den Schätzen und schaust dich ein letztes Mal noch um. Als niemand in der Nähe war, greifst du einfach hinein und erbeutest ganze 2 Edelsteine auf einmal. `n
Doch plötzlich wurde es laut als ein schwer Verletzter herein kam und die Heiler und Pfleger aufgeregt hin und her rannten. Nur in einem günstigen Augenblick kannst du unerkannt entkommen und atmest schließlich tief ein, als du endlich wieder in Sicherheit warst. `n
Der Verlust würde angesichts dieser Situation ohnehin nicht so schnell auffallen`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`7Mit Wut im Gesicht blickte Patitus dich an als er dich dabei erwischte, wie du dich an den Schätzen des Krankenhauses bereichern wolltest. `n
`&Interessant, interessant `7 murmelt er in seinen Bart hinein und noch ehe du dich versehen konntest, standest du schon mit einer fristlosen Kündigung vor den geschlossenen Toren der Heilanstalt. Und zu allem Überfluss hatte dir diese Tat auch nichts eingebracht außer der Tatsache, dir einen neuen Arbeitsplatz suchen zu dürfen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7Alles würde gut gehen hast du dir gedacht als alles still und ruhig war. Nirgends eine Menschenseele und die Tür der Schatzkammer ungeschlossen. `n
Langsam und vorsichtig trittst du ein und steckst soviel in die Tasche, wie es nur ging. `n
Doch plötzlich stand er hinter dir. Der Heiler und Leiter des Hauses Patitus. `n
Du konntest nichts mehr sagen bevor er anfing dich wütend anzubrüllen und dir wünscht, dass du wohl am besten nie hierher gekommen wärst. Doch da er dennoch von recht großzügigem Gemüt war, gab er dir noch eine Chance und du kommst noch mal mit einer Verwarnung davon.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '9':
output("`7Mit Wut im Gesicht blickte Patitus dich an als er dich dabei erwischte, wie du dich an den Schätzen des Krankenhauses bereichern wolltest. `n
`&Interessant, interessant `7 murmelt er in seinen Bart hinein und noch ehe du dich versehen konntest, standest du schon mit einer fristlosen Kündigung vor den geschlossenen Toren der Heilanstalt. Und zu allem Überfluss hatte dir diese Tat auch nichts eingebracht außer der Tatsache, dir einen neuen Arbeitsplatz suchen zu dürfen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7Alles würde gut gehen hast du dir gedacht als alles still und ruhig war. Nirgends eine Menschenseele und die Tür der Schatzkammer ungeschlossen. `n
Langsam und vorsichtig trittst du ein und steckst soviel in die Tasche, wie es nur ging. `n
Doch plötzlich stand er hinter dir. Der Heiler und Leiter des Hauses Patitus. `n
Du konntest nichts mehr sagen bevor er anfing dich wütend anzubrüllen und dir wünscht, dass du wohl am besten nie hierher gekommen wärst. Doch da er dennoch von recht großzügigem Gemüt war, gab er dir noch eine Chance und du kommst noch mal mit einer Verwarnung davon.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '11':
output("`7Mit Wut im Gesicht blickte Patitus dich an als er dich dabei erwischte, wie du dich an den Schätzen des Krankenhauses bereichern wolltest. `n
`&Interessant, interessant `7 murmelt er in seinen Bart hinein und noch ehe du dich versehen konntest, standest du schon mit einer fristlosen Kündigung vor den geschlossenen Toren der Heilanstalt. Und zu allem Überfluss hatte dir diese Tat auch nichts eingebracht außer der Tatsache, dir einen neuen Arbeitsplatz suchen zu dürfen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7Alles würde gut gehen hast du dir gedacht als alles still und ruhig war. Nirgends eine Menschenseele und die Tür der Schatzkammer ungeschlossen. `n
Langsam und vorsichtig trittst du ein und steckst soviel in die Tasche, wie es nur ging. `n
Doch plötzlich stand er hinter dir. Der Heiler und Leiter des Hauses Patitus. `n
Du konntest nichts mehr sagen bevor er anfing dich wütend anzubrüllen und dir wünscht, dass du wohl am besten nie hierher gekommen wärst. Doch da er dennoch von recht großzügigem Gemüt war, gab er dir noch eine Chance und du kommst noch mal mit einer Verwarnung davon.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
addnav("zurück","khaus.php");
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`3Zum letzten Mal schaust du dir alle ganz genau an, da das heute wohl dein letzter tag hier sein würde. Zumindest, wenn du beruflich hier zu schaffen hast. `n
Beinahe bedächtig klopfst du bei Patitus an, der dich sofort hereinbittet und dich nicht mal eines Blickes würdigt. `n
Beschwerlich bringst du dein Anliegen hervor und zu aller Verwunderung sagte dein ehemaliger Chef nicht einmal etwas dazu und nickte nur stumm. `n
Schulterzuckend gehst du einfach davon und verabschiedest dich noch schnell von deinen ehemaligen Mitarbeitern`n");
$session['user']['jobid']=0;
addnav("zurück","khaus.php");
}
addnav("Zurück");
addnav("zurück zum Dorf","village.php");
addnav("zurück zum Krankenlager","khaus.php");
page_footer();
?>