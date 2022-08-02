<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";
addcommentary();
page_header("Gerichtssaal");

output("`v`c`bGerichtssaal`b`c`&`7Alles war still im Raum. Niemand rührte sich, da eine Verhandlung kurz bevor stand. `n
Ganz vorn saß der Richter und schlug gelegentlich mit seinem Hammer auf ein kleines, extra dafür vorgesehenes Holzpodest. `n
Gelehrte saßen Stuhl an Stuhl an der Seite um dem Prozess aufmerksam folgen zu können. Im hinteren Bereich hatten sich viele Schaulustige versammelt, die auf knarrenden und quietschenden Stühlen mehr und mehr die Köpfe ausstreckten um mehr sehen und hören zu können. `n
Inmitten dieser Ansammlung von Menschen saß der Angeklagte zusammengekauert vor einem kleinen Tischchen, was wohl nur noch von reiner Willenskraft zusammengehalten wurde. Ein wahrhaft starker Kontrast zur vergleichsweise edlen Ausstattung des restlichen Raumes, wo auch der prächtige Kronleuchter, der den Raum mit vielen Kerzen hell erleuchtete, seinen Beitrag dazu leistete.`n");


if($session['user']['jobid'] ==25){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","gericht.php?op=dadrei");
addnav("Kündigen","gericht.php?op=go");
addnav("Stehlen","gericht.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==28){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","gericht.php?op=dadrei");
addnav("Kündigen","gericht.php?op=go");
addnav("Stehlen","gericht.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}


if ($_GET['op']=="dadrei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`7Langsam waren deine Schritte, die du ganz allmählich in Richtung des Hinterzimmer setzt, wo sich um diese Uhrzeit der oberste Richter namens Sullah aufhielt. Irgendwie zögerst du, bevor du endlich an die alte, rustikale Holztür klopfst und eine tiefe Männerstimme, an der man eine unglaubliche Strenge und Direktheit erkennen konnte, um Eintritt bat. `n
Als die Tür endlich nicht mehr zwischen dir und dem Richter stand, trittst du vorsichtig vor und bringst dein Anliegen vor. `n
`&Arbeit wollt ihr nun da kann ich Abhilfe schaffen. Meldet euch bei meiner Assistentin, sie wird euch einweißen. Und eins noch `7 meint er anschließend `&Verschwiegenheit ist oberstes Gebot also handelt dementsprechend `n
`7 Ob es eine Warnung war oder nicht, vermochtest du nicht einzuschätzen, da du bereits im nächsten Augenblick nach nebenan verwiesen wurdest.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7 Du wurdest bereits am Eingang von Sullahs Assistentin erwartet als du müde zur Arbeit erscheinst `&Beeilt euch es gibt viel zu tun und es werden euch nur 2 Stunden zur Verfügung stehen. `n
`7Seufzend machst du dich an die Arbeit und nachdem alles soweit erledigt war, gehst du mit 2000 Goldstücken in der Tasche nach Hause.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;

case '2':
output("`&3 Stunden heute `7 meinte die Assistentin mit lächelndem Gesicht und achtete nicht einmal darauf, ob es dir so recht war. Was sein musste, musste wohl sein und so verbrachtest du die folgenden Stunden im Gerichtsgebäude. Den Lohn von 3000 Gold gab es für dich erst im Anschluss.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '3':
output("`7Nach geschlagen 4 Stunden warst du endlich soweit fertig, dass du für heute Schluss machen konntest. Für magere 4000 Gold hattest du heute wohl wirklich alles gegeben, was in deiner Macht stand. In jedem Falle warst du froh, dass du nun endlich Feierabend machen konntest.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=4000;
$session['user']['jobda']+=1;

break;
case '4':
output("`&Eure Dienste werden heute für, so leid es mir tut, 5 Stunden benötigt `n
`7Das war wohl der Satz, der dir für den Rest des Tages nicht mehr aus dem Kopf ging. So ruhig die Stimme der Assistentin auch klang, umso härter trafen dich diese Worte. Vor Einbruch der Dunkelheit würdest du wohl nicht mehr zu Hause sein. Aber immerhin bekamst du für deine Mühe 5000 Gold als Entschädigung.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=5000;
$session['user']['jobda']+=1;

break;
case '5':
output("`7Heute war es der Chef selbst, der an der Tür auf dich wartete. Das konnte ja nichts Gutes bedeuten. Entweder er hatte im Moment nichts zu tun, was niemals vorkam oder es wartete ein riesiger Berg Arbeit auf dich, die so schnell wie möglich erledigt werden sollte. `n
Sehnsüchtig blickst du auf die Turmuhr und wartest darauf, dass die Glocken endlich nach 6 Stunden zu deiner Erlösung läuteten. Als es nun endlich soweit war, schleifst du dich mit 6000 Goldstücken in der Tasche erschöpft nach Hause.`n`n");
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
output("`7Der Augenblick war günstig. Alle Gerichtsdiener und Beteiligten waren im Gerichtssaal versammelt und keiner war mehr in der Nähe um dir bei deinen Plänen in die Quere zu kommen. `n
Dennoch bist du so leise und vorsichtig, wie nur möglich als du dir einen großen Teil der Bußgelder in deine eigene Tasche steckst. Immerhin hattest du hart genug gearbeitet um auch einmal eine größere Belohnung dafür zu erhalten. `n
Du kannst unbemerkt aus dem Gebäude fliehen und konntest ganze 5.000 Goldstücke entwenden.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Bis auf deine schleichenden Schritte war nicht weiter in den Gängen zu hören. Das Ziel lag zum greifen nahe und mehr als eine Tür und der Deckel einer Truhe war es nicht, was dich noch von den Geldern des Gerichts trenne. `n
Mit Bedacht und ohne ein Geräusch zu verursachen beseitigst du auch diese Hindernisse und ergötzt dich an den Goldmünzen, die vor deinen Augen lagen. `n
Du nimmst dir insgesamt 3000 Gold und machst dich damit aus dem Staub, sodass dich keiner sehen oder bemerken konnte.
`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`7Vorfreude stieg in die hoch als die Egelsteine des Gerichtes unmittelbar vor deinen Augen lagen. Zum greifen nahe und niemand, der dich dabei hätte aufhalten können. `n
Grinsend und ohne zu zögern packst du soviel, wie nur ging, in deinen Beutel. `n
Entzückt stellst du fest, dass insgesamt 6 Edelsteine darin Platz fanden und du schließt letztendlich den Behälter wieder, der nun sichtlich leerer war. Im Anschluss schleichst du dich leise davon und entkommst unentdeckt.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`7Du hattest es fast geschafft. Niemand hatte dein Eindringen bemerkt und der Behälter für die Edelsteine war unverschlossen, sodass du ihn einfach aufmachen konntest. `n
Er war so prall gefüllt, dass es dir keinerlei Zweifel ließ, dass dir davon einige zustanden und so verschwanden nach und nach einige der wertvollen Steine in deinen Taschen und du siehst zu, dass du möglichst ohne verräterische Spuren zu hinterlassen, verschwinden kannst. `n
Insgesamt erbeutest du dabei 4 Edelsteine.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Du warst dir sicher, dass keiner mehr im Gebäude war aber woher tauchten denn auf einmal die Schritte auf, die du im Gang hören konntest? `n
Dicht an die Wand gedrängt lauschst du, ob sie sich näherten oder verschwanden. Wenn dich einer mit dem Bußgeldern in der Tasche erwischte, warst du sicherlich deine Arbeit los und für alle zeit als Dieb abgestempelt. `n
Als es für einen Augenblick still war. Nutzt du die Gelegenheit und rennst so schnell es nur ging davon. Aber du konntest immerhin noch 1000 Goldstücke erbeuten.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("Prüfend wanderten deine Blicke über die Edelsteinpracht, die sich vor dir erstreckte. Da machte es doch wirklich nichts aus, wenn du dir einfach einige davon wegnehmen würdest. Natürlich so, dass keiner einen Verdacht schöpfte. `n
Behutsam verstaust du einige in deinen Kleidern als plötzlich hinter die eine Tür zuschlug. Zum Glück war es nicht die, von diesem Zimmer aber es musste jemand in der Nähe sein. `n
Schnell siehst du zu, dass du verschwindest und kannst 2 Edelsteine unbemerkt stehlen.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("7Mit Zornesfalten stand er nun auf einmal vor dir. Sullah, der oberste Richter und dein nunmehr ehemaliger Chef. Dir war als hättest du einen Kloß im Hals, wie hättest du auch erklären können, dass ein Teil seiner Schätze in deinen Händen lagen? `n
Seine strengen Blicke würdigten dich keinen Augenblick mehr sondern er sah nur noch auf dein Diebesgut, welches dir ohne weitere Umschweife wieder entrissen wurde. `n
Unsanft packten dich einige der Dorfpolizisten und warfen dich nach draußen vor die Tür. `n
Es war wohl Zeit, sich eine neue Arbeit zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7Sichtlich gereizt wurdest du nach dem versuchten Diebstahl vor Sullah geführt, dessen Strenge beinahe im Raum fühlbar wurde. `n
Du spürtest seinen erbarmungslosen Blick auf dir ruhen und du konntest nicht mehr als den Kopf einziehen und darauf zu warten, dass er das Wort ergriff. Doch zu deiner Verwunderung war er nicht gewillt dich gleich heraus werfen zu lassen. Im Gegenteil. `n
Er ermahnte dich in den laufenden Stunden mehrmalig und setze eine heftige Strafpredigt über Recht und Unrecht hinzu, beließ es aber dann dabei. `n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '9':
output("7Mit Zornesfalten stand er nun auf einmal vor dir. Sullah, der oberste Richter und dein nunmehr ehemaliger Chef. Dir war als hättest du einen Kloß im Hals, wie hättest du auch erklären können, dass ein Teil seiner Schätze in deinen Händen lagen? `n
Seine strengen Blicke würdigten dich keinen Augenblick mehr sondern er sah nur noch auf dein Diebesgut, welches dir ohne weitere Umschweife wieder entrissen wurde. `n
Unsanft packten dich einige der Dorfpolizisten und warfen dich nach draußen vor die Tür. `n
Es war wohl Zeit, sich eine neue Arbeit zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7Sichtlich gereizt wurdest du nach dem versuchten Diebstahl vor Sullah geführt, dessen Strenge beinahe im Raum fühlbar wurde. `n
Du spürtest seinen erbarmungslosen Blick auf dir ruhen und du konntest nicht mehr als den Kopf einziehen und darauf zu warten, dass er das Wort ergriff. Doch zu deiner Verwunderung war er nicht gewillt dich gleich heraus werfen zu lassen. Im Gegenteil. `n
Er ermahnte dich in den laufenden Stunden mehrmalig und setze eine heftige Strafpredigt über Recht und Unrecht hinzu, beließ es aber dann dabei. `n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '11':
output("7Mit Zornesfalten stand er nun auf einmal vor dir. Sullah, der oberste Richter und dein nunmehr ehemaliger Chef. Dir war als hättest du einen Kloß im Hals, wie hättest du auch erklären können, dass ein Teil seiner Schätze in deinen Händen lagen? `n
Seine strengen Blicke würdigten dich keinen Augenblick mehr sondern er sah nur noch auf dein Diebesgut, welches dir ohne weitere Umschweife wieder entrissen wurde. `n
Unsanft packten dich einige der Dorfpolizisten und warfen dich nach draußen vor die Tür. `n
Es war wohl Zeit, sich eine neue Arbeit zu suchen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7Sichtlich gereizt wurdest du nach dem versuchten Diebstahl vor Sullah geführt, dessen Strenge beinahe im Raum fühlbar wurde. `n
Du spürtest seinen erbarmungslosen Blick auf dir ruhen und du konntest nicht mehr als den Kopf einziehen und darauf zu warten, dass er das Wort ergriff. Doch zu deiner Verwunderung war er nicht gewillt dich gleich heraus werfen zu lassen. Im Gegenteil. `n
Er ermahnte dich in den laufenden Stunden mehrmalig und setze eine heftige Strafpredigt über Recht und Unrecht hinzu, beließ es aber dann dabei. `n`n");
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
output("`7Zum letzten Mal holst du tief Luft und drückst danach die Tür zu Sullahs Büro auf. Sofort fiel sein Blick auf dich und du konntest sehen, dass du ihn aus seinen Gedanken gerissen hattest. `n
`&Habt ihr nichts zu tun? Ihr seht doch, dass ich arbeite! `n
Ein wenig erschrocken über seinen Tonfall nimmst du nun doch allen Mut zusammen und unterrichtest ihn von deiner Entscheidung zu kündigen. `n
Etliche Sekunden verstrichen und er schwieg nur. Mit kurzer Handbewegung deutete er zur Tür. Sicherlich war er nicht auf dich angewiesen und so verlässt du nun mit deinen letzten Schritten hier, das Gebäude.`n");
$session['user']['jobid']=0;
}

output('`@Rechtsgelehrter`n`n');
        if ($session['user']['jobid']==25)
            {
            viewcommentary('Rechtsgelehrter','',15);
            }
        else
            {
            viewcommentary('Rechtsgelehrter','Y',15);
            }
output('`@Richterpult`n`n');
        if ($session['user']['jobid']==28)
            {
            viewcommentary('Richterpult','',15);
            }
        else
            {
            viewcommentary('Richterpult','Y',15);
            }

output("`n`n`%`@In der Nähe reden einige Zuschauer:`n");
viewcommentary("Richtersaal","Hinzufügen",25);

addnav("`c`bzurück`c`b","village.php");
page_footer();
?>