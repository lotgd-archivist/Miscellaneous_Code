<?php

require_once "common.php";

page_header("Aloha Bar");

output("`v`c`bAloha Bar`b`c`&Rustikal und stickig, ganz so, wie man sich eine alte Bar vorzustellen hatte. `n
Holzstühle standen um den Tresen herum, teils besetzt, teils noch leer für weitere Gäste. Die Luft stand förmlich im Raum und roch nach Alkohol, den sich bereits zur frühen Stunde so einige Einwohner zu Gemüte geführt hatten. `n
Stimmen wurden laut und klangen ausgelassen in deinen Ohren wieder. Klagen über die Arbeit und die Familie, Scherze über Bekannte und alles, was man sich nur vorstellen konnte. Den Anwesenden war es gleich, ob es die anderen mitbekamen. `n
Ein Wirt stand inmitten all der anderen und wienerte die Gläser sauber als er dich gleich fragte, was er dir bringen konnte.
`n`n
`c `q`bPreisliste`b`n`n
`7Kokusmilch `&5 Gold`n
`7Kibasaft `&7 Gold`n
`7Apfelsaft `& 7 Gold`n
`7Kirschnecktar `& 7 Gold`n
`7Quellwasser `& 3 Gold`n
`7Früchtetee `&5 Gold`n
`7Kräutertee `& 5 Gold`n
`7Kakao `& 6 Gold`n
`7Inselkaffee `& 6 Gold`n
`7Kokusschnaps `&8 Gold`n
`7Apfelschnaps `& 8 Gold`n
`7Kirschschnaps `& 8 Gold`n
`7Inselvodka `& 16 Gold`n
`7Kirschtraum `&20 Gold`n
`7Kokustraum `& 20 Gold`n
`7Apfelwolke `& 20 Gold`n
`7Ok `& 20 Gold`n
`7Rissing Erdbeer `&25 Gold`n
`7Inselfieber `& 25 Gold`n
`7Eisprinz `& 25 Gold`n`c
");

addnav("`bGetränkekarte`b");
addnav("`bOhne Alkohol`b");
addnav("Kokusnussmilch","bar.php?op=kokus");
addnav("Kibasaft","bar.php?op=kiba");
addnav("Apfelsaft","bar.php?op=apfel");
addnav("Kirschnektar","bar.php?op=kirsch");
addnav("Quellwasser","bar.php?op=quell");
addnav("Früchtetee","bar.php?op=frucht");
addnav("Kräutertee","bar.php?op=kraut");
addnav("Kakao","bar.php?op=kakao");
addnav("Inselkaffee","bar.php?op=kaffe");
addnav("`bMit Alkohol`b");
addnav("Kokusschnaps","bar.php?op=akokus");
addnav("Apfelschnaps","bar.php?op=aapfel");
addnav("Kirschschnaps","bar.php?op=akirsch");
addnav("Inselvodka","bar.php?op=vodka");
addnav("`bCoktails`b");
addnav("`bOhne Alkohol`b");
addnav("Kirschtraum","bar.php?op=ckirsch");
addnav("Kokustraum","bar.php?op=ckokus");
addnav("Apfelwolke","bar.php?op=capfel");
addnav("OK","bar.php?op=ok");
addnav("`bMit Alkohol`b");
addnav("Rising Erdbeer","bar.php?op=erdbeer");
addnav("Inselfieber","bar.php?op=insel");
addnav("Eisprinz","bar.php?op=eis");

if($session['user']['jobid'] ==5){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","bar.php?op=da");
addnav("Kündigen","bar.php?op=go");
addnav("Stehlen","bar.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==4){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","bar.php?op=da");
addnav("Kündigen","bar.php?op=go");
addnav("Stehlen","bar.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==14){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","bar.php?op=dazwei");
addnav("Kündigen","bar.php?op=go");
addnav("Stehlen","bar.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`7Dir war, als hättest du einen Kloß im Hals als dir dein künftiger Chef entgegen trat. Ein beleibter und auch noch großer Hüne mit Namen Hassan vom Walde. `n
`&Soso ihr seid die neue Arbeitskraft, wenn ich richtig verstanden habe. Ha und mit diesen dünnen Ärmchen wollt ihr hier arbeiten? `7 er packt dich bei diesen Worten ein wenig unsanft an, doch vermittelte er, dass er es nur scherzhaft meinte. Durchaus schien er von recht ausgelassener Art und liebte es zu scherzen. Ohne ein weiteres Wort führt er dich zu deiner künftigen Arbeitsstelle und gibt dir eine kurze Einweisung
`n`n");

switch(e_rand(1,5)){

       case '1':
output("`3Der Wirt tritt dir entgegen und deutet nur noch auf deinen Arbeitsplatz. Für 2 Stunden solltest du also wieder hier schaffen. Eigentlich konntest du dich glücklich schätzen dafür wenigstens den Lohn von 1000 Gold zu erhalten`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`3Ganze 3 Stunden waren für heute angesagt gewesen. Brav und ohne zu murren erledigst du deine Aufgaben und gehst nach Feierabend mit ganzen 1500 Gold als Bezahlung nach Hause.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`3So leid es mit tut, doch werdet ihr 4 Stunden für mich arbeiten müssen. Am besten macht ihr euch gleich ran, damit ihr fertig werdet bevor die Nacht herein bricht. `q meint dein Chef zu dir und weist dir gleich mit einer Handgeste den Weg. Den Lohn von 2000 Gold würdest du wohl erst am Ende erhalten.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`3Es war wohl mal wieder soweit… `n
Dein Chef brummt dir für heute grinsend 5 Stunden Arbeit auf  und winkte nur ab bevor du überhaupt die Frage nach Pausen stellen konntest. `nNachdem nicht nur die Arbeit erledigt war, sondern auch du, drückt er dir die verdienten 2500 Goldstücke in die Hand und wünscht dir noch eine gute Nacht.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`3Du konntest es dem mürrischem Blick deines Chefs bereits ansehen, dass es heute wirklich sehr viel zu tun gab und er dich bereits zähneknirschend erwartet hatte. `n
Ein Seufzen dran über deine Lippen als er dir das heutige Arbeitsmaß mitteilte. `n
6 Stunden arbeiten und dass für 3000 Gold als Belohnung. Aber was blieb dir schon anderes übrig?`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
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

output("`7Dir war, als hättest du einen Kloß im Hals als dir dein künftiger Chef entgegen trat. Ein beleibter und auch noch großer Hüne mit Namen Hassan vom Walde. `n
`&Soso ihr seid die neue Arbeitskraft, wenn ich richtig verstanden habe. Ha und mit diesen dünnen Ärmchen wollt ihr hier arbeiten? `7 er packt dich bei diesen Worten ein wenig unsanft an, doch vermittelte er, dass er es nur scherzhaft meinte. Durchaus schien er von recht ausgelassener Art und liebte es zu scherzen. Ohne ein weiteres Wort führt er dich zu deiner künftigen Arbeitsstelle und gibt dir eine kurze Einweisung`n`n");

switch(e_rand(1,5)){

       case '1':
output("`2Ich benötige euch heute nur für 2 Stunden. `2 meinte Hassan als du zur Arbeit erscheinst. Wohl gab es nicht viel zu tun oder er hatte bereits genug Mitarbeiter. `n
Nachdem alles erledigt war, überreicht er dir in seiner Eile 1500 Gold, die du nur schulterzuckend einsteckst und dich für heute davon machst
`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`2Noch ein wenig müde von gestern erscheinst du zur Arbeit, wo dich Hassan bereits grinsend erwartete. `&3 Stunden, keine Widerrede `2 war das Einzigste, was er zu dir sagte und war im nächsten Augenblick schon wieder verschwunden. Als du jedoch das Goldsäckchen mit 2250 Goldstücken erblickst, ringst du dir ein Lächeln ab und machst dich sofort ans Werk.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case '3':
output("`& Hier sind 3000 Gold, doch erwarte ich dafür auch, dass ihr heute für 4 Stunden arbeitet. `2 sagte Hassan mit strenger Stimme zu dir und rückte bereits ein Stück zur Seite um dir Platz zu machen, damit du sofort anfangen konntest. Schweigsam erledigst du die für heute anstehenden Arbeiten und machst dich im Anschluss geschwind davon, damit er nicht doch noch auf die Idee kam eine weitere Stunde ran zu hängen`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '4':
output("`2Mit merkwürdigem Blick wurdest du von deinem Chef empfangen und du dachtest schon, dass du zu spät warst, doch weit gefehlt. Er freute sich nur darauf dir mit grinsendem Ausdruck zu sagen, dass er dich heute für 5 Stunden arbeiten lassen würde zum Lohn von 3750 Goldstücken.`2 Nun, zur Flucht war es ja bereits zu spät.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case '5':
output("`2Dir blieb das Wort im Halse stecken. 6 Stunden solltest du heute wieder hier deinem Werk nachgehen? Und dass ohne regelmäßige Pausen? Zum mickrigen Lohn von 4500 Gold? `n
Tja mit dir kann man's ja machen. Solange du das Leben eines fleißig Arbeitenden dem eines Tagelöhners vorziehst…
`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=4500;
$session['user']['jobda']+=1;

break;
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
output("`8Es gab nun kein Zurück mehr für dich. Die Kasse lag offen vor dir und niemand war sonst noch anwesend. Möglichst ohne ein Geräusch zu verursachen nimmst du dir 5.000 Gold aus dem Tagessatz und verschwindest so schnell du nur konntest. `n
Zum Glück hatte dich keiner bemerkt und du kannst dein Diebesgut sicher verstauen. `n
Was Hassan wohl dazu sagen wird, wenn er den Verlust bemerkt?`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`8So verlockend und reizend… all die prächtigen Goldstücke, die Hassan besaß und die nun für dich zum Greifen nahe waren. Niemand hatte gesehen, wie du dich in das Hinterzimmer geschlichen hast und auch keiner bemerkte, dass du dich an den Schätzen zuschaffen machst. `n
Finster grinsend steckst du dir 3000 Gold in die Hosentasche und verlässt die Bar danach mit völlig unschuldigem Blick. `n
Ehe Hassan etwas bemerkte, warst du sowieso schon weit weg und außer Verdacht.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`8Freude breitete sich in dir aus. So nah am Ziel. So nah an den Edelsteinen, die dir nach eigener Ansicht zustanden. `n
Hassan hat dich doch auch lange genug schuften lassen ohne dich dafür gebührend zu entlohnen und das würdest du jetzt selbst nachholen und nimmst dir ohne zu zögern 20 der wertvollen Steine weg. Noch bevor jemand auf die Idee kam nachzusehen, wieso die Tür zu Hassans Zimmer aufstand, warst du bereits in Sicherheit und um die gestohlenen 6 Edelsteine reicher.
`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`84 Edelsteine, mehr war es doch gar nicht, was du von Hassan verlangt hattest. Er besaß doch wahrlich genug, doch blieb er auch genauso starrsinnig auf ihnen sitzen und wollte dir keinen einzigen davon geben mit der Meinung, dass dein Lohn wohl schon ausreichte. `n
Tja selbst schuld dachtest du so bei dir und bedienst dich nun einfach auf eigene Faust. Zu deinem Glück bemerkte es niemand und so verschwandest du mit deiner Beute und der Alte hatte eben das Nachsehen.
`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`8Leise quietschte die Zimmertür als du gerade dabei warst, dich an den Tageseinnahmen zu bedienen. Lediglich 2000 Goldstücke konntest du bisher in deine Hosentasche stecken, als du nun die Flucht ergreifen musstest. `n
Schnell flüchtest du nach draußen und hoffst dabei, dass dich keiner gesehen hatte. Auf jeden Fall warst du nun um gar nicht so unbeachtliche 1000 Gold reicher`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`8Wohlig war dir zumute als die Edelsteine einer nach dem anderen in deiner Tasche klimperten. War doch gar nicht so schwer gewesen, zumal die Tür nicht einmal abgeschlossen gewesen war. Da war es doch Hassans eigene Schuld gewesen, wenn sein Schatz kleiner wird. `n
Doch du hattest dich wohl doch zu früh gefreut. Seine knarrenden Schritte hallten bedrohlich in deinen Ohren wieder und du sahst dich dazu veranlasst das Weite zu suchen. `n
Immerhin konntest du noch 2 Edelsteine in deinen Kleidern verschwinden lassen und gesehen hat er dich zum Glück wohl auch nicht.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`8Es war nicht mehr als ein lautes Grollen und ein dumpfer Schlag auf deinen Hinterkopf, was du nach deinem geplanten Diebstahl noch wahrnehmen konntest. `n
Als du wieder aufwachst, findest du dich im Regen auf der Straße wieder und von innen hörte man nur noch die tobende Stimme deines nunmehr ehemaligen Chefs Hassan. `n
Trotz deiner Gutmütigkeit konnte er wohl doch recht gut zuschlagen, was du an deinem schmerzenden Kopf nur zu gut feststellen konntest. Aber hier noch einmal blicken lassen? Nein, dass solltest du wohl besser nicht tun`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`8Na wen haben wir denn da? `q hörtest du plötzlich mit ernster Stimme jemanden hinter dir sprechen als du gerade dabei warst deinen Lohn ein wenig aufzubessern und dich an Hassans Schätzen zu bereichern. Und wer sollte es auch anders sein, als der Hüne selbst, der hinter dir stand? `n
Mit einer wahrhaft ernst gemeinten Verwarnung schickt er dich mürrisch nach Hause, auf das du ihm heute nicht mehr unter die Augen kommen solltest und natürlich gingst du bei dieser Aktion völlig leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;

 case '9':
output("`8Es war nicht mehr als ein lautes Grollen und ein dumpfer Schlag auf deinen Hinterkopf, was du nach deinem geplanten Diebstahl noch wahrnehmen konntest. `n
Als du wieder aufwachst, findest du dich im Regen auf der Straße wieder und von innen hörte man nur noch die tobende Stimme deines nunmehr ehemaligen Chefs Hassan. `n
Trotz deiner Gutmütigkeit konnte er wohl doch recht gut zuschlagen, was du an deinem schmerzenden Kopf nur zu gut feststellen konntest. Aber hier noch einmal blicken lassen? Nein, dass solltest du wohl besser nicht tun`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`8Na wen haben wir denn da? `q hörtest du plötzlich mit ernster Stimme jemanden hinter dir sprechen als du gerade dabei warst deinen Lohn ein wenig aufzubessern und dich an Hassans Schätzen zu bereichern. Und wer sollte es auch anders sein, als der Hüne selbst, der hinter dir stand? `n
Mit einer wahrhaft ernst gemeinten Verwarnung schickt er dich mürrisch nach Hause, auf das du ihm heute nicht mehr unter die Augen kommen solltest und natürlich gingst du bei dieser Aktion völlig leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;

 case '11':
output("`8Es war nicht mehr als ein lautes Grollen und ein dumpfer Schlag auf deinen Hinterkopf, was du nach deinem geplanten Diebstahl noch wahrnehmen konntest. `n
Als du wieder aufwachst, findest du dich im Regen auf der Straße wieder und von innen hörte man nur noch die tobende Stimme deines nunmehr ehemaligen Chefs Hassan. `n
Trotz deiner Gutmütigkeit konnte er wohl doch recht gut zuschlagen, was du an deinem schmerzenden Kopf nur zu gut feststellen konntest. Aber hier noch einmal blicken lassen? Nein, dass solltest du wohl besser nicht tun`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`8Na wen haben wir denn da? `q hörtest du plötzlich mit ernster Stimme jemanden hinter dir sprechen als du gerade dabei warst deinen Lohn ein wenig aufzubessern und dich an Hassans Schätzen zu bereichern. Und wer sollte es auch anders sein, als der Hüne selbst, der hinter dir stand? `n
Mit einer wahrhaft ernst gemeinten Verwarnung schickt er dich mürrisch nach Hause, auf das du ihm heute nicht mehr unter die Augen kommen solltest und natürlich gingst du bei dieser Aktion völlig leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`5Nachdenklich betrittst du das Hinterzimmer, wo sich Hassan immer aufhielt, wenn er die Einnahmen nachzählte oder einfach nur die Buchführung übernahm. `n
Dir war ein wenig mulmig zumute und du denkst sogar daran, es doch lieber zu lassen…
Doch es hatte durchaus seine Gründe, wieso du kündigen wolltest, selbst, wenn du hier einen recht annehmlichen Arbeitskreis gefunden hattest. `n
`&Was kann ich für euch tun? `q murmelte er in seinen Bart hinein und schaute nur ganz kurz von den Rechnungen auf um dich zu mustern. Schwer musstest du schlucken und erst einmal all deinen Mut zusammen nehmen um dein Anliegen heraus zu bringen, woraufhin er dich nur schief ansah. Er zuckt mit den breiten Schultern und meinte nur noch, dass er sicherlich nicht auf dich angewiesen war, damit seine Bar gut läuft. `n
Mit der Hand deutete er unmissverständlich zur Tür und widmete sich wieder seinen Rechnungen.
`n");
$session['user']['jobid']=0;
}

if ($_GET['op']=="kokus"){
if($session['user']['gold']>=5){
output("`9Du bestellst eine Kokusmilch bei der Bedienung , langsamm geht die Bedienung zu einem Stapel Kokusnüssen mit einem Messer bohrt sie ein Loch hinein und füllt mit der Flüssigkeit die austritt ein Glas,sie stellt das Glas dann freundlich vor die ab und haucht `& 5 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an dem Glas `n");
$session['user']['gold']-=5;
}elseif($session['user']['gold']<=5){
output("Du hast keine 5 Gold übrig");
}
}
if ($_GET['op']=="kiba"){
if($session['user']['gold']>=7){
output("`9Du bestellst einen Kiba , die Bedienung wendet sich der Früchtetheke zu nimmt eine Bananne und ein paar Kirschen zerstampft das ganze in einer Schüssel bis es fast flüssig ist diese Sut schüttet sie in ein Glas füllt es mit Milch auf und rührt es um , sie stellt das Glas dann freundlich vor die ab und haucht `& 7 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an dem Kiba`n");
$session['user']['gold']-=7;
}elseif($session['user']['gold']<=7){
output("Du hast keine 7 Gold übrig");
}
}
if ($_GET['op']=="apfel"){
if($session['user']['gold']>=7){
output("`9Du bestellst einen Apfelsaft , die Bedienung wendet sich der Früchtetheke zu nimmt einen Apfel zerstampft diesen in einer Schüssel nach dem sie ihn schälte und endkernte bis der Brei fast flüssig ist diese Sut schüttet sie in ein Glas füllt es mit Milch auf und rührt es um , sie stellt das Glas dann freundlich vor die ab und haucht `& 7 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an dem Apfelsaft`n");
$session['user']['gold']-=7;
}elseif($session['user']['gold']<=7){
output("Du hast keine 7 Gold übrig");
}
}
if ($_GET['op']=="kirsch"){
if($session['user']['gold']>=7){
output("`9Du bestellst einen Kirschnecktar , die Bedienung wendet sich der Früchtetheke zu nimmt einen paar Kirschen zerstampft diese in einer Schüssel nach dem sie diese geschälte und endkernthat bis der Brei flüssig ist diese Sut schüttet sie in ein Glas füllt , sie stellt das Glas dann freundlich vor die ab und haucht `& 7 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an dem Kirschnecktar`n");
$session['user']['gold']-=7;
}elseif($session['user']['gold']<=7){
output("Du hast keine 7 Gold übrig");
}
}
if ($_GET['op']=="quell"){
if($session['user']['gold']>=3){
output("`9Du bestellst ein Quellwasser , die Bedienung wendet sich einem grossem Faß zu nimmt eine Kelle tungt diese ins Fass und füllt die klare Flüssigkeit in ein Glas, sie stellt das Glas dann freundlich vor die ab und haucht `& 3 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an dem Kirschnecktar`n");
$session['user']['gold']-=3;
}elseif($session['user']['gold']<=3){
output("Du hast keine 3 Gold übrig");
}
}
if ($_GET['op']=="frucht"){
if($session['user']['gold']>=5){
output("`9Du bestellst einen Früchtetee , die Bedienung wendet sich einem grossem Faß zu nimmt eine Kelle tungt diese ins Fass und füllt die klare Flüssigkeit in ein Glas schüttet dieses durch ein Sieb in dem sich getrocknete Früchte befinden, sie stellt das Glas in dem sich nun die Flüssigkeit befindet freundlich vor dir ab und haucht `& 5 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Früchtetee`n");
$session['user']['gold']-=5;
}elseif($session['user']['gold']<=5){
output("Du hast keine 6 Gold übrig");
}
}
if ($_GET['op']=="kraut"){
if($session['user']['gold']>=5){
output("`9Du bestellst einen Kräutertee , die Bedienung wendet sich einem grossem Faß zu nimmt eine Kelle tungt diese ins Fass und füllt die klare Flüssigkeit in ein Glas schüttet dieses durch ein Sieb in dem sich getrocknete Kräuter befinden, sie stellt das Glas in dem sich nun die Flüssigkeit befindet freundlich vor dir ab und haucht `& 5 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kräutertee`n");
$session['user']['gold']-=5;
}elseif($session['user']['gold']<=5){
output("Du hast keine 5 Gold übrig");
}
}
if ($_GET['op']=="kakao"){
if($session['user']['gold']>=6){
output("`9Du bestellst einen Kakao , die Bedienung wendet sich einem grossem Faß zu nimmt eine Kelle tungt diese ins Fass und füllt die Milch in ein Glas in dem sich Kakaopulver befindet, sie stellt das Glas freundlich vor dir ab und haucht `& 6 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kakao`n");
$session['user']['gold']-=6;
}elseif($session['user']['gold']<=6){
output("Du hast keine 6 Gold übrig");
}
}
if ($_GET['op']=="kaffe"){
if($session['user']['gold']>=6){
output("`9Du bestellst einen Inselkaffee , die Bedienung wendet sich einem grossem Faß zu nimmt eine Kelle tungt diese ins Fass und füllt die klare Flüssigkeit durch ein Sieb in dem sich Kaffepulver befindet, sie stellt das Glas in dem sich nun die Flüssigkeit befindet freundlich vor dir ab und haucht `& 6 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kaffe`n");
$session['user']['gold']-=6;
}elseif($session['user']['gold']<=6){
output("Du hast keine 6 Gold übrig");
}
}
if ($_GET['op']=="akokus"){
if($session['user']['gold']>=8){
output("`9Du bestellst einen Kokusschnaps , die Bedienung wendet sich einem leicht schräg stehendem Faß zu und öffnet durch das entfernen eines kleinen Korkens ein kleines Loch aus dem nun eine Flüssigkeit in ein Glas fließt, sie stellt das Glas freundlich vor dir ab und haucht `& 8 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kokusschnaps`n");
$session['user']['gold']-=8;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']<=8){
output("Du hast keine 8 Gold übrig");
}
}
if ($_GET['op']=="aapfel"){
if($session['user']['gold']>=8){
output("`9Du bestellst einen Apfelschnaps , die Bedienung wendet sich einem leicht schräg stehendem Faß zu und öffnet durch das entfernen eines kleinen Korkens ein kleines Loch aus dem nun eine Flüssigkeit in ein Glas fließt, sie stellt das Glas freundlich vor dir ab und haucht `& 8 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Apfelschnaps`n");
$session['user']['gold']-=8;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']<=8){
output("Du hast keine 8 Gold übrig");
}
}
if ($_GET['op']=="akirsch"){
if($session['user']['gold']>=8){
output("`9Du bestellst einen Kirschschnaps , die Bedienung wendet sich einem leicht schräg stehendem Faß zu und öffnet durch das entfernen eines kleinen Korkens ein kleines Loch aus dem nun eine rote Flüssigkeit in ein Glas fließt, sie stellt das Glas freundlich vor dir ab und haucht `& 8 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kirschschnaps`n");
$session['user']['gold']-=8;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']<=8){
output("Du hast keine 8 Gold übrig");
}
}
if ($_GET['op']=="vodka"){
if($session['user']['gold']>=16){
output("`9Du bestellst einen Inselvodka , die Bedienung wendet sich einem leicht schräg stehendem Faß zu und öffnet durch das entfernen eines kleinen Korkens ein kleines Loch aus dem nun eine klare Flüssigkeit in ein Glas fließt, sie stellt das Glas freundlich vor dir ab und haucht `& 16 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinen Inselvodka`n");
$session['user']['gold']-=16;
$session['user']['drunkenness']+=70;
}elseif($session['user']['gold']<=16){
output("Du hast keine 16 Gold übrig");
}
}
if ($_GET['op']=="ckirsch"){
if($session['user']['gold']>=20){
output("`9Du bestellst einen Kirschtraum , die Bedienung wendet sich ab und beginnt dan ein Glas zu Dekorieren befüllt das Glas mit verschiedenen Sachen , sie stellt das Glas freundlich vor dir ab und haucht `& 20 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kirschtraum`n");
$session['user']['gold']-=20;
}elseif($session['user']['gold']<=20){
output("Du hast keine 20 Gold übrig");
}
}
if ($_GET['op']=="ckokus"){
if($session['user']['gold']>=20){
output("`9Du bestellst einen Kokustraum , die Bedienung wendet sich ab und beginnt dan ein Glas zu Dekorieren befüllt das Glas verschiedenen Sachen , sie stellt das Glas freundlich vor dir ab und haucht `& 20 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Kokustraum`n");
$session['user']['gold']-=20;
}elseif($session['user']['gold']<=20){
output("Du hast keine 20 Gold übrig");
}
}
if ($_GET['op']=="capfel"){
if($session['user']['gold']>=20){
output("`9Du bestellst eine Apfelwolke , die Bedienung wendet sich ab und beginnt dann ein Glas zu Dekorieren befüllt dieses mit Apfelsaft,Zucker und Kokusmilch , sie stellt das Glas freundlich vor dir ab und haucht `& 20 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Apfelwolke`n");
$session['user']['gold']-=20;
}elseif($session['user']['gold']<=20){
output("Du hast keine 20 Gold übrig");
}
}
if ($_GET['op']=="ok"){
if($session['user']['gold']>=20){
output("`9Du bestellst einen Ok , die Bedienung wendet sich ab und beginnt dann ein Glas zu Dekorieren befüllt dieses  mit Orangensaft,Kirschsaft und Quellwasser , sie stellt das Glas freundlich vor dir ab und haucht `& 20 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinen Ok`n");
$session['user']['gold']-=20;
}elseif($session['user']['gold']<=20){
output("Du hast keine 20 Gold übrig");
}
}
if ($_GET['op']=="erdbeer"){
if($session['user']['gold']>=25){
output("`9Du bestellst einen Rissing Erdbeer , die Bedienung wendet sich ab und beginnt dann ein Glas zu Dekorieren befüllt dieses  mit Erdbeeren und verschiedenen Schnäpsen , sie stellt das Glas freundlich vor dir ab und haucht `& 25 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Rissing Erdbeer`n");
$session['user']['gold']-=25;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']<=25){
output("Du hast keine 25 Gold übrig");
}
}
if ($_GET['op']=="insel"){
if($session['user']['gold']>=25){
output("`9Du bestellst einen Inselfieber , die Bedienung wendet sich ab und beginnt dann ein Glas zu Dekorieren befüllt dieses  mit verschiedenen Schnäpsen , sie stellt das Glas freundlich vor dir ab und haucht `& 25 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinem Inselfieber`n");
$session['user']['gold']-=25;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']<=25){
output("Du hast keine 25 Gold übrig");
}
}
if ($_GET['op']=="eis"){
if($session['user']['gold']>=25){
output("`9Du bestellst einen Eisprinz , die Bedienung wendet sich ab und beginnt dann ein Glas zu Dekorieren befüllt dieses  mit verschiedenen Schnäpsen und Eis , sie stellt das Glas freundlich vor dir ab und haucht `& 25 Goldstücke bitte`9. Da du nicht anders kannst gibst du ihr das Gold und schlürfst dann an deinen Eisprinz`n");
$session['user']['gold']-=25;
$session['user']['drunkenness']+=35;
}elseif($session['user']['gold']>=25){
output("Du hast keine 25 Gold übrig");
}
}
addnav("`bZurück`b");
addnav("Zürück zum Dorf","village.php");
if ($session['user']['drunkenness']>69) $session['user']['reputation']--;
                        $session['bufflist'][101] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
output("`n`n`nMit anderen Gästen Reden:`n");
viewcommentary("Bar","Hinzufügen",40);
page_footer();
?>