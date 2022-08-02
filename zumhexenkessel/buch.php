<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Buchbinderei");

output("`v`c`bBuchbinderei`b`c`8Inmitten des Rathauses, befindet sich die Buchbinderei. `n
Schon seit Generationen werden hier den Büchern Einbände verliehen, die durchaus von hoher Qualität sind. `n
Dir steigt bereits der Duft von Leder und Leim in die Nase, der überall in der Luft lag. Drinnen standen überall Bücher in den Regalen, egal ob sie nun bereits fertig gebunden waren oder nicht. An den Schreibtischen saßen fleißige Arbeiter, die eifrig und mit großer Sorgfalt damit beschäftigt waren ihrem Werk alle Ehre zu machen. `n
Ansonsten wirkte der Raum eher trist und einfach. Kaum ein Wort drang über die Lippen der Anwesenden, da alle hochkonzentriert waren, um ja nichts falsch zu machen.`n`n");

if($session['user']['jobid'] ==3){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","buch.php?op=da");
addnav("Kündigen","buch.php?op=go");
addnav("Stehlen","buch.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}


if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`5Mit Mühe arbeitest du dich an den vielen Arbeitsplätze vorbei um nichts umzustoßen und niemanden bei der Arbeit zu stören. Wieso musste der Platz deines zukünftigen Vorgesetzten auch gerade im hintersten und dunkelsten Bereich der Buchbinderei sein? `n
Als du endlich angekommen warst saß dir ein alter Mann gegenüber, der gerade so aussah als wäre die Korrektheit sein einziger Lebensinhalt. Der abgetragene Anzug lag steif und gerade an seinen dünnen Gliedern an als hätte er niemals schief gesessen oder etwas dergleichen. Das Haar war perfekt nach hinten gekämmt und sein Blick verriet Strenge und Zucht. `n
Auf einem Namensschild erkennst du den Namen Lester Decart und noch bevor du überhaupt ein Wort heraus brachtest wies er dich schon an einen freien Schreibtisch, als hätte er schon mit einem Blick erkannt, dass du die neue Arbeitskraft bist.
`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Müde und Augen reibend betrittst du die Buchbinderei, wo dich dein Chef bereits erwartet mit der Bitte heute für 2 Stunden zu arbeiten. Er drückt dir deinen Lohn von 1000 Goldstücken in die Hand, aber nur mit der Bedingung, dass du gleich anfängst.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`7Mit dem gewohnt korrektem Blick stand Lester Decart unmittelbar vor dir als du zur Arbeit erscheinst. `&3 Stunden werdet ihr für heute schaffen müssen. Ohne Pausen versteht sich. Euer Lohn befindet sich bereits auf eurem Schreibtisch und zählt 1500 Goldstücke. Und, dass ihr mir ja nicht schlampig arbeitet.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`7Noch immer erschöpft von der gestrigen Arbeit wurdest du bereits wieder von deinem Chef in Empfang genommen. Dieses mal waren es also wieder 4 Stunden Arbeit zum Lohn von 2000 Gold, die du absolvieren musstest.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`75 Stunden für heute und dass zum Lohn von 2500 Gold stellst du seufzend fest. Aber was blieb dir schon anderes übrig um deine Arbeit nicht zu verlieren. Irgendwo zwischen seiner Korrektheit war dein Chef doch ein Sklaventreiber… und du musstest alles über dich ergehen lassen.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`7Du wusstest bereits, dass etwas im Gange war als Mister Decart mit breitem Grinsen an der Eingangstür auf dich wartete. `n
6 Stunden sollten es also heute sein? 6 Stunden reine Konzentration uns ja keine Fehler oder schiefe Buchbände? Für den Hungerlohn von 3000 Gold? Mit dir konnte man es ja machen dachtest du dir nur und setzt dich brav an deinen Platz.`n`n");
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

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){

switch(e_rand(1,12)){

       case '1':
output("`6Im Schutze der Nacht schleichst du dich langsam zur Geldkassette vor, wo Decart immer die Einnahmen des Tages deponiert und aufbewahrt. Der alte Knacker konnte ruhig etwas mehr löhnen, wenn man bedenkt, wie er für sich schuften lässt. Ohne Reue greifst du zu und erbeutest dabei ganze 10.000 Goldstücke. `n
So schnell dich deine Beine nur trugen, rennst du davon. Den Verlust wird man wohl erst am nächsten Morgen bemerken.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=10000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`6Gierig starrst du auf das Gold, welches in der Geldkassette gelagert war. `n
Alles ging so einfach… einfach reinkommen und nehmen, was einem zustand nach all der Schufterei für Decart. Beherzt nimmst du, was du tragen kannst und kannst am Ende deines Diebeszuges 5000 Goldstücke zählen, die nun ganz alleine dir gehören sollten. `n
Endlich bekam dieser Sklaventreiber einmal das, was er verdiente.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`6So leise und geschwind wie ein kleiner Taschendieb, gehst du zu Decarts Schatztruhe, wo er all die Edelsteine lagerte, die seine Kundschaft als Anzahlung hinterließen. `n
Zweiel hattest du nicht, denn der Alte hatte es verdient. Er zahlte ohnehin schon viel zu wenig und so steckst du so viele Steine in deine Tasche, wie du nur tragen konntest. Insgesamt 20 Edelsteine passten in deinen Beutel und irgendwie tat es dir schon wieder leid, keinen Größeren mitgenommen zu haben. `n
In aller Ruhe verläst du die Buchbinderei wieder um deine Beute in Sicherheit zu bringen
`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=20;
$session['user']['dieb']+=1;
break;
       case '4':
output("`6Endlich… endlich solltest du einen angemessenen Lohn für die ganze, pausenlose Arbeit erhalten, die du geleistet hast. Es lag alles zum greifen nahe vor deinen Augen. Die Schatztruhe war reich gefüllt mir makellosen Edelsteinen, die du dir sogleich einsteckst. `n
Leider waren es nur noch 10 Stück aber das sollte wohl fürs Erste einmal reichen. `n
Leise und bedächtig, dass dich keiner hört, schließt du die nunmehr leere Truhe wieder und nimmst die Beine in die Hand.
`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=10;
$session['user']['dieb']+=1;
break;
       case '5':
output("`6 Du erschrickst als die Geldkassette beim Öffnen quietschte. Hektisch schaust du dich um und lauschst, ob dich auch niemand gehört hatte. Als du meintest, dass alles still war, willst du in die Kassette greifen, doch plötzlich schien sich jemand zu nähern, da Schritte durch die Gänge hallten. Mit mageren 2000 Gold sahst du zu, dass du Land gewinnst. Aber so war  es wohl noch immer besser als wenn man dich erwischt hätte. `n
Später könnte man es ja vielleicht auch noch mal versuchen`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=2000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`6So leise, wie du nur konntest näherst du dich der Edelsteintruhe und hebst vorsichtig den Deckel hoch. Niemand, der in der Nähe war, das war doch gut dachtest du dir. `n
Mit breitem Grinsen im Gesicht beginnst du damit, den Schatz in deine Taschen zu laden. `n
Aber plötzlich und ohne Vorwarnung knarrte eine Tür hinter dir und wurde aufgeschwungen. `n
In letzter Minute konntest du dich noch hinter einem Schreibtisch verstecken und in einem günstigen Augenblick, als der Angestellte der herein kam nicht aufpasste, die Flucht ergreifen. Aber immerhin hatte dir diese Aktion doch noch 5 Edelsteine eingebracht.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=5;
$session['user']['dieb']+=1;
break;
       case '7':
output("`6Deine Glieder waren einfach nur wie erstarrt. Die strengen und vielleicht sogar tadelnden Blicke Lester Decarts ruhten auf deinem Leib. In der Hand befand sich noch dein Diebesgut. Der unumstößliche Beweis dafür, dass du unkorrekt gehandelt hast. Etwas, was dein Chef auf den Tod nicht ausstehen konnte. Man sah ihm an, dass die Wut in ihm aufbrodelte und noch ehe du ein Wort der Reue herausbringen konntest, standest du ohne Arbeit und ohne Beute auf der regnerischen Straße. Hier brauchtest du dich sicherlich nicht mehr blicken zu lassen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '8':
output("`6Mit Funkeln in den Augen standest du über deiner Beute und stopfst soviel, wie nur ging in deine Taschen. Der Sieg war dein… so dachtest du zumindest. `n
Nichts ahnend führst du deine Tat fort als dir plötzlich jemand von hinten auf die Schulter klopfte. Natürlich wer sollte es auch anders sein… `n
Lester Decart starrte dich mit seltsamen Augen an und wies nur auf den Holzstuhl, der direkt vor seinem Schreibtisch stand. `n
Erst Stunden später konntest du nach einer Strafpredigt, die sich gewaschen hatte nach Hause gehen. Und natürlich gingst du bei diesem versuchten Raubzug leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
       case '9':
output("`6Deine Glieder waren einfach nur wie erstarrt. Die strengen und vielleicht sogar tadelnden Blicke Lester Decarts ruhten auf deinem Leib. In der Hand befand sich noch dein Diebesgut. Der unumstößliche Beweis dafür, dass du unkorrekt gehandelt hast. Etwas, was dein Chef auf den Tod nicht ausstehen konnte. Man sah ihm an, dass die Wut in ihm aufbrodelte und noch ehe du ein Wort der Reue herausbringen konntest, standest du ohne Arbeit und ohne Beute auf der regnerischen Straße. Hier brauchtest du dich sicherlich nicht mehr blicken zu lassen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '10':
output("`6Mit Funkeln in den Augen standest du über deiner Beute und stopfst soviel, wie nur ging in deine Taschen. Der Sieg war dein… so dachtest du zumindest. `n
Nichts ahnend führst du deine Tat fort als dir plötzlich jemand von hinten auf die Schulter klopfte. Natürlich wer sollte es auch anders sein… `n
Lester Decart starrte dich mit seltsamen Augen an und wies nur auf den Holzstuhl, der direkt vor seinem Schreibtisch stand. `n
Erst Stunden später konntest du nach einer Strafpredigt, die sich gewaschen hatte nach Hause gehen. Und natürlich gingst du bei diesem versuchten Raubzug leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
       case '11':
output("`6Deine Glieder waren einfach nur wie erstarrt. Die strengen und vielleicht sogar tadelnden Blicke Lester Decarts ruhten auf deinem Leib. In der Hand befand sich noch dein Diebesgut. Der unumstößliche Beweis dafür, dass du unkorrekt gehandelt hast. Etwas, was dein Chef auf den Tod nicht ausstehen konnte. Man sah ihm an, dass die Wut in ihm aufbrodelte und noch ehe du ein Wort der Reue herausbringen konntest, standest du ohne Arbeit und ohne Beute auf der regnerischen Straße. Hier brauchtest du dich sicherlich nicht mehr blicken zu lassen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '12':
output("`6Mit Funkeln in den Augen standest du über deiner Beute und stopfst soviel, wie nur ging in deine Taschen. Der Sieg war dein… so dachtest du zumindest. `n
Nichts ahnend führst du deine Tat fort als dir plötzlich jemand von hinten auf die Schulter klopfte. Natürlich wer sollte es auch anders sein… `n
Lester Decart starrte dich mit seltsamen Augen an und wies nur auf den Holzstuhl, der direkt vor seinem Schreibtisch stand. `n
Erst Stunden später konntest du nach einer Strafpredigt, die sich gewaschen hatte nach Hause gehen. Und natürlich gingst du bei diesem versuchten Raubzug leer aus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`6Reuig trittst du an den Arbeitsplatz von Lester Decart und hältst die Hände verschränkt vor den Körper, genau wie dein Kopf leicht gesenkt war. Du traust dich irgendwie nicht ihm ins Gesicht zu schauen, da du bereits spüren konntest, wie dich dein nüchterner Blick traf. `n
Natürlich fragte er sogleich nach, was es denn war, was dich von der Arbeit abhielt. Mit stockenden Worten brachtest du dein Anliegen bezüglich deines Kündigungswunsches  heraus. `n
Möglichst lautlos gingst du davon als er dir nur noch  hinterher rief: `& Es ist schade, ihr habt gute Arbeit geleistet. Ihr seid jederzeit wieder willkommen.`n");
$session['user']['jobid']=0;
}


addnav("`c`bzurück`c`b","village.php");
page_footer();
?>