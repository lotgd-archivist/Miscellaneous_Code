<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Kartographenzimmer");

output("`v`c`bKartographenzimmer`b`c`9 Wohin das Auge nur sah, es erblickte überall Papier und frisch gezeichnete Karten, die auf Leinen aufgehängt waren. `n
Viele Arbeiter saßen an ihren Tischen und werkelten bei Kerzenschein mit Linealen und Zirkeln herum um die erhaltenen Angaben über die Welt auf Karten zusammen zu fassen und auszuwerten. `n
Einige Seefahrer liefen geschäftig umher um sich zu vergewissern, dass ihre bestellten Karten bald fertig wurden und sie endlich wieder in See stechen konnten. `n
Auch Mitglieder der Stadtverwaltung warteten ungeduldig auf die Ergebnisse ihrer Grundstücksvermessungen und irgendwie hattest du Angst, dass die bereits geröteten Augen der Arbeiter irgendwann herausfallen und auf das Papier fallen würden
`n");

if($session['user']['jobid'] ==29){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","kartograph.php?op=dadrei");
addnav("Kündigen","kartograph.php?op=go");
addnav("Stehlen","kartograph.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="dadrei"){

if ($session['user']['jobda'] ==1){
output("`8 Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`8 Zuversichtlich wie immer suchst du zwischen all den Leuten denjenigen heraus, der hier wohl offensichtlich das Sagen hatte. Ein kleiner, zierlicher Mann, der sich mit Namen Pierre vorstellte, tritt dir auf einmal entgegen und setzt ein freundliches Lächeln auf `&Ah gut, dass ihr endlich da seid, wir haben bereits auf euch gewartet. Ich hoffe doch ihr könnt mit einem Zirkel umgehen? `v er stößt dir zwinkernd mit dem Ellenbogen in die Seite, woraufhin du nur ein merkwürdiges Lächeln zustande bringst. `&Einer meiner Arbeiter wird euch alles zeigen, was ihr zu tun habt. Arbeitet gut, und euer Lohn wird angemessen ausfallen. `V meint er nur noch und ist dann bereits wieder verschwunden um neue Angaben von einem Seefahrer entgegen zu nehmen, wer eben hereingekommen war.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`3 2 Stunden werden heute ausreichen. `v meint dein Chef mit freundlicher Stimme. `& Hier habt ihr erst einmal den Lohn von 2000 Gold aber bitte haltet euch ran, die nächsten Aufträge werden bald herein kommen. `v Mit erwiderndem Lächeln machst du dich sogleich ans Werk `n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;

case '2':
output("`3Ausgeruht und frisch erscheinst du zur Arbeit, wo man dich bereits erwartete. 3 Stunden Arbeit standen für heute auf dem Tagesplan und zu deiner Verwunderung waren die Aufgaben auch gar nicht mal so schwer. Nachdem alles erledigt war, verlässt du mit 3000 Gold in der Tasche das Gebäude und gehst nach Hause.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '3':
output("`3Fleißig sitzt du an deinem Platz und werkelst mit dem Zirkel auf dem Papier herum. `n
Der Chef meinte, dass er dich heute 4 Stunden lang beschäftigen will und hat dir den Lohn von 4000 Gold bereits auf dem Tisch bereit gelegt. `n
Erschöpft aber zufrieden mit dir nimmst du das, was dir zusteht und verlässt den Raum.
`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=4000;
$session['user']['jobda']+=1;

break;
case '4':
output("`3Du musst seufzen als der Chef dir für heute 5 Stunden Arbeit auf den Tisch legte. `& Es gibt viel zu tun `v meinte er grinsend und überließ dich deinem Schicksal, welches dir aber mit 5000 Gold belohnt werden sollte. Aber erst, wenn du fertig warst.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=5000;
$session['user']['jobda']+=1;

break;
case '5':
output("`3Immer wieder wanderten deine Blicke zur Wanduhr. Sind diese 6 Stunden denn nicht endlich herum… `n
Es half wohl nichts und so musstest du die volle Zeit absitzen. Zumindest entschädigt dich deine Bezahlung von 6000 Gold für deine Mühen. `n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=6000;
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
output("`7Getrieben von deiner Gier, vergisst du sogar, dass es eine Straftat war, die du hier gerade begehst. Niemand war mehr hier außer dir und so bedienst du dich einfach an den heutigen Tagesseinahmen. `n
Dabei erbeutest du insgesamt 5.000 Gold, die du gut versteckt in deinen Taschen nach draußen beförderst. `n
Keine Menschenseele hatte etwas bemerkt… das würde wohl erst am nächsten Morgen geschehen, wo du ja zum Glück bereits über alle Berge warst`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Schwer wiegte deine Last von 3000 Goldstücken in deinen Taschen und noch zögerst du, einfach so mit deinem Diebesgut nach draußen zu gehen. Immerhin hattest du dich einfach von den Einnahmen bedient… `n
Doch wer konnte dieser Verlockung schon widerstehen? Keiner hatte etwas bemerkt, da sie alle schon nach Hause gegangen waren und auf die Paar Goldstücke kam es doch bei laufendem Geschäft ohnehin nicht an. `n
Leise schleichst du dich schließlich mit der Beute davon und überlegst dir bereits, was du davon alles kaufen könntest.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`7Ob Pierre es wohl verstehen würde, wenn du dich an seinen Edelsteinen bedienst, da du dringend eine kleine Finanzspritze brauchst? `n
Der Lohn reichte deiner Ansicht nach ja auch vorne und hinten nicht aus. `n
Ohne Skrupel greifst du dir so viele Steine, wie du nur tragen konntest und schaffst sie unbemerkt nach draußen. Insgesamt zählt deine Ausbeute 6 Edelsteine, die von nun an dir gehören sollten`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`74 Edelsteine, mehr brauchtest du doch gar nicht. Es würde doch bei der Menge nicht einmal auffallen, die sich noch in der Truhe befand. `n
Ohne weiter drüber nachzudenken stiehlst du den dir vorgenommenen Betrag und siehst danach zu, dass keine verräterischen Spuren zurückbleiben, die auf dich als Täter hinweisen würden. `n
Erleichtert atmest du aus, da dich offensichtlich keiner bemerkt hat und rennst so schnell es nur geht nach Hause.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Es war spät abends und alle waren bereits nach getaner Arbeit erschöpft nach hause gegangen als du als Letzter noch im Gebäude zurück bliebst und deinen Schreibtisch aufräumen musstest. Da funkelten dir die heutigen Einnahmen ins Auge, gerade so als wollten sie dich dazu verleiten einfach zuzugreifen. `n
Der Verlockung konntest du einfach nicht widerstehen und so wirtschaftest du einen Teil davon in deine eigenen Taschen. `n
Doch auf einmal sahst du die Schemen einer Person im Gang nebenan und wartest erschrocken auf einen günstigen Zeitpunkt zur Flucht. Dir war als stünde dein Harz still als du endlich die kühle Nachtluft einatmest und immerhin noch 1000 Gold erbeutet hast. Zumindest hatte dich keiner gesehen`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`7Im Schutz der Dunkelheit näherst du dich den Edelsteinsäcken, die in Laufe des Tages eingenommen wurden und greifst beherzt zu, da du sicher warst, dass keiner mehr in der Nähe war.`n
Doch weit gefehlt. Plötzlich hörtest du von weitem, wie sich Schritte näherten und du musstest eilig die Flucht ergreifen. `n
Das Ergebnis deines Raubzuges war immerhin, neben der Erleichterung gerade noch mal unentdeckt geblieben zu sein, ganze 2 Edelsteine.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`7Das Letzte, was du noch wirklich wahrnehmen konntest, war das Knarren der Tür und die Schritte von Pierre in deinem Rücken. `n
Danach war alles so unwirklich für dich. Das Geschrei um den versuchten Diebstahl, wie die Angestellten aufgeregt hin und herliefen und die erbarmungslose Hand des Hünen, der dich in Pierres Auftrag nach draußen auf die Straße beförderte. `n
Erst danach begreifst du langsam, dass es wohl an der Zeit war, sich einen neuen Arbeitsplatz zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7Eigentlich sollte es doch ganz anders laufen. `n
Rein, Beute schnappen und heraus. Wer konnte schon ahnen, dass dein Chef zu so später Stunde noch im Arbeitszimmer war? `n
Lange musst du dir im Anschluss anhören, wie enttäuscht Pierre doch von dir war und du musstest ihm tausendmal versprechen, dass so was nie wieder vorkommen würde.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '9':
output("`7Das Letzte, was du noch wirklich wahrnehmen konntest, war das Knarren der Tür und die Schritte von Pierre in deinem Rücken. `n
Danach war alles so unwirklich für dich. Das Geschrei um den versuchten Diebstahl, wie die Angestellten aufgeregt hin und herliefen und die erbarmungslose Hand des Hünen, der dich in Pierres Auftrag nach draußen auf die Straße beförderte. `n
Erst danach begreifst du langsam, dass es wohl an der Zeit war, sich einen neuen Arbeitsplatz zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7Eigentlich sollte es doch ganz anders laufen. `n
Rein, Beute schnappen und heraus. Wer konnte schon ahnen, dass dein Chef zu so später Stunde noch im Arbeitszimmer war? `n
Lange musst du dir im Anschluss anhören, wie enttäuscht Pierre doch von dir war und du musstest ihm tausendmal versprechen, dass so was nie wieder vorkommen würde.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '11':
output("`7Das Letzte, was du noch wirklich wahrnehmen konntest, war das Knarren der Tür und die Schritte von Pierre in deinem Rücken. `n
Danach war alles so unwirklich für dich. Das Geschrei um den versuchten Diebstahl, wie die Angestellten aufgeregt hin und herliefen und die erbarmungslose Hand des Hünen, der dich in Pierres Auftrag nach draußen auf die Straße beförderte. `n
Erst danach begreifst du langsam, dass es wohl an der Zeit war, sich einen neuen Arbeitsplatz zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7Eigentlich sollte es doch ganz anders laufen. `n
Rein, Beute schnappen und heraus. Wer konnte schon ahnen, dass dein Chef zu so später Stunde noch im Arbeitszimmer war? `n
Lange musst du dir im Anschluss anhören, wie enttäuscht Pierre doch von dir war und du musstest ihm tausendmal versprechen, dass so was nie wieder vorkommen würde.`n`n");
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
output("`7Mit missmutigem Blick gehst du langsam auf Pierre zu und reichst ihm zur Begrüßung die Hand. Man musste schon blind sein um nicht zu bemerken, dass dir etwas auf der Seele lag und er war es schon von Berufswegen nicht. Er merkte, dass etwas los war und hatte wohl auch schon eine Vorahnung, da sich sein Blick langsam gen Boden richtete. `&Ihr wollt uns verlassen richtig? `v nachdem du nur vorsichtig genickt hast, meint er nur noch `&Da ich euch nicht halten kann bleibt mir nur euch noch viel Glück für die Zukunft zu wünschen. `V Nach diesen Worten wandet ihr euch beide ab und du verlässt deinen ehemaligen Arbeitsplatz.`n");
$session['user']['jobid']=0;
}


addnav("`c`bzurück`c`b","village.php");
page_footer();
?>