<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Unterhaltungssaal");

output("`v`c`bUnterhaltungssaal`b`c`& `8Sanfte Klänge hallen durch den Raum, die ein Barde aus seinem Instrument entlockt und sie mit melodischer Stimme begleitet. In einer Ecke konntest du eine kleine Menschenansammlung sehen, die mit großen, aufmerksamen Augen den Geschichten eines Mannes lauschten, die jedes Wort noch mit heftiger Gestikulierung unterstrich. Leider war er etwa zu weit um ihn verstehen zu können. Überall standen noch freie Stühle herum, die nur so auf weitere Zuhörer und Zuschauer warteten, die den Musizier- und Erzählkünsten der Darsteller lauschen wollten.`n`n");

if($session[user]['jobid'] ==12){
if($session[user]['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","unterhaltung.php?op=dazwei");
addnav("Kündigen","unterhaltung.php?op=go");
addnav("Stehlen","unterhaltung.php?op=ste");
}elseif($session[user]['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session[user]['jobid'] ==13){
if($session[user]['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","unterhaltung.php?op=dazwei");
addnav("Kündigen","unterhaltung.php?op=go");
addnav("Stehlen","unterhaltung.php?op=ste");
}elseif($session[user]['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session[user]['jobid'] ==22){
if($session[user]['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","unterhaltung.php?op=dadrei");
addnav("Kündigen","unterhaltung.php?op=go");
addnav("Stehlen","unterhaltung.php?op=ste");
}elseif($session[user]['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session[user]['jobid'] ==26){
if($session[user]['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","unterhaltung.php?op=dadrei");
addnav("Stehlen","unterhaltung.php?op=ste");
addnav("Kündigen","unterhaltung.php?op=go");
}elseif($session[user]['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="dazwei"){

if ($session[user]['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session[user]['jobda'] ==0){

output("`8Voller Zuversicht klopfst du an die Tür des Leiters dieser Einrichtung und wirst sofort herein gebeten. `n
Mit neugierigen Blicken musterst du dir edle Einrichtung des Zimmers und denkst dir sofort, dass es hier wohl viel zu verdienen gab. Oder war es genau umgedreht? Das müsstest du wohl noch herausfinden. `n
`&Was kann ich für sie tun? `8meldete sich ein faltiger, alter Mann hinter seinem Schreibtisch worauf ein Schild den Namen Sillophos verriet. `n
`&Sie suchen Arbeit ist das richtig? Wenn, dann haben wir gerade noch eine freie Stelle für euch frei. `n
`8Als du seine Vermutung mit einem Nicken bejahst, deutet er unmittelbar auf das Nebenzimmer und meint zu euch, dass ihr euch erst einmal in Ruhe alles ansehen sollt, bevor er euch einweist.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`8Frischen Mutes machst du dich auf zu neuen Taten und an die Arbeit. 2 Stunden sollten es für heute sein, wie du erfahren hattest. Schmunzelnd verrichtest du schnell dein Werk um anschließend mit 1500 Gold nach Hause gehen zu können.`n`n");
$session[user]['turns']-=2;
$session[user]['gold']+=1500;
$session[user]['jobda']+=1;

break;

case '2':
output("`&Eure Dienste werden heute für 3 Stunden benötigt, danach könnt ihr euch euren Lohn von 2250 Goldstücken bei mir abholen und keine Minute früher `8meint dein Vorgesetzter zu dir und zwinkert.`n`n");
$session[user]['turns']-=3;
$session[user]['gold']+=2250;
$session[user]['jobda']+=1;

break;
case '3':
output("Mit einem breiten Grinsen im Gesicht wirst du bereits von deinem Vorgesetzten erwartet, der dich gleich an deinen Arbeitsplatz führt. Er steckt dir in seiner Eile einen Beutel zu in dem sich 3000 Goldstücke befanden und ein Zettel mit dem Arbeitsauftrag für 4 Stunden.`n`n");
$session[user]['turns']-=4;
$session[user]['gold']+=3000;
$session[user]['jobda']+=1;

break;
case '4':
output("`&Ah ich habe euch bereits erwartet `8 wirst du von deinem Chef begrüßt `&Eure Dienste werden wir heute leider ziemlich lange in Anspruch nehmen müssen und deshalb werdet ihr die nächsten 5 Stunden hier im Gebäude bleiben. Den Lohn von 3750 Gold erhaltet ihr im Anschluss. `n `8Ohne ein Widerwort machst du dich an die Arbeit.`n`n");
$session[user]['turns']-=5;
$session[user]['gold']+=3750;
$session[user]['jobda']+=1;

break;
case '5':
output("`86 Stunden… 6 volle Stunden zum Lohn von 4500 Gold. So lange solltest du es also heute hier aushalten können. Das war wohl immer ein sehr bitterer Wehrmutstropfen gewesen, der mit dieser Tätigkeit zusammenhing. Aber was blieb dir schon anderes übrig…`n`n");
$session[user]['turns']-=6;
$session[user]['gold']+=4500;
$session[user]['jobda']+=1;

break;
}
}
if($session[user]['jobf'] >=1){
$session[user]['jobf']-=1;
}
}
if ($_GET['op']=="dadrei"){

if ($session[user]['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session[user]['jobda'] ==0){

output("`8Voller Zuversicht klopfst du an die Tür des Leiters dieser Einrichtung und wirst sofort herein gebeten. `n
Mit neugierigen Blicken musterst du dir edle Einrichtung des Zimmers und denkst dir sofort, dass es hier wohl viel zu verdienen gab. Oder war es genau umgedreht? Das müsstest du wohl noch herausfinden. `n
`&Was kann ich für sie tun? `8meldete sich ein faltiger, alter Mann hinter seinem Schreibtisch worauf ein Schild den Namen Sillophos verriet. `n
`&Sie suchen Arbeit ist das richtig? Wenn, dann haben wir gerade noch eine freie Stelle für euch frei. `n
`8Als du seine Vermutung mit einem Nicken bejahst, deutet er unmittelbar auf das Nebenzimmer und meint zu euch, dass ihr euch erst einmal in Ruhe alles ansehen sollt, bevor er euch einweist.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`&Da seid ihr ja bitte folgt mir. Es gibt heute nicht viel zu tu aber es sollte so schnell wie möglich erledigt werden. `8sagte Sillophos zu dir und führt dich in einen der hinteren Räume, wo er dir zeigte, was es zu tun gab. `&Auf, auf die Arbeit erledigt sich nicht alleine. Wenn ihr fertig seid, warten eure 2000 Gold Lohn auf euch.`n`n");
$session[user]['turns']-=2;
$session[user]['gold']+=2000;
$session[user]['jobda']+=1;

break;

case '2':
output("`&3 Stunden und meine Sekunde weniger `8säußelte dir dein Chef fröhlich entgegen. Natürlich… er hatte ja mit der schweren Schufterei nicht viel am Hut und ließ lieber für sich arbeiten. Mürrisch machst du dich dann jedoch an deine Aufgaben heran, die wohl wieder nur einen mageren Lohn von 3000 Goldstücken versprachen.`n`n");
$session[user]['turns']-=3;
$session[user]['gold']+=3000;
$session[user]['jobda']+=1;

break;
case '3':
output("`8Ein Seufzen drang aus deinen Lippen als du die Tür zu deinem Arbeitszimmer aufdrückst und dich bereits ein ganzer Berg von Arbeit erwartete. Ohne lange zu zögern machst du dich daran alles zu erledigen und brauchst dafür ganze 4 Stunden. Immerhin gab dir dein Chef für diese Einsatz magere 4000 Goldstücke.`n`n");
$session[user]['turns']-=4;
$session[user]['gold']+=4000;
$session[user]['jobda']+=1;

break;
case '4':
output("`&Für heute sind 5 Stunden Arbeit vorgesehen `8 meint dein Vorgesetzter mit einem lächelnden Gesicht und drückt dir schon einmal im Voraus das Goldsäckchen mit 5000 Gold in die Hand. `&Ich erwarte wie immer hervorragende Arbeit verstanden?`n`n");
$session[user]['turns']-=5;
$session[user]['gold']+=5000;
$session[user]['jobda']+=1;

break;
case '5':
output("`7Du wurdest schon wieder erwartet… Das konnte ja nur eines bedeuten. Heute waren wieder ganze 6 Stunden Arbeit angesagt und das zum mickrigen Lohn von 6000 Goldstücken. Mit hängenden Armen machst du dich aber doch an die Arbeit, sonst würdest du ja nie fertig werden.`n`n");
$session[user]['turns']-=6;
$session[user]['gold']+=6000;
$session[user]['jobda']+=1;

break;
}
}
if($session[user]['jobf'] >=1){
$session[user]['jobf']-=1;
}
}

if ($_GET['op']=="ste"){
if(($session[user]['turns'] <=2)||($session[user]['dieb'] <=1)){
switch(e_rand(1,8)){

       case '1':
output("`8Ob Sillophos es wohl verstehen würde, wenn du dich an seinen Schätzen bedienst, da du dringend eine kleine Finanzspritze brauchst? `n
Der Lohn reichte deiner Ansicht nach ja auch vorne und hinten nicht aus. `n
Ohne Skrupel greifst du dir so viele Goldstücke, wie du nur tragen konntest und schaffst sie unbemerkt nach draußen. Insgesamt zählt deine Ausbeute 5.000 Gold, die von nun an dir gehören sollten`n`n");
$session[user]['turns']-=1;
$session[user]['gold']+=5000;
$session[user]['dieb']+=1;
break;
       case '2':
output("`8Bis auf deine schleichenden Schritte war nicht weiter in den Gängen zu hören. Das Ziel lag zum greifen nahe und mehr als eine Tür und der Deckel einer Truhe war es nicht, was dich noch von den Schätzen des Saales trennte. `n
Mit Bedacht und ohne ein Geräusch zu verursachen beseitigst du auch diese Hindernisse und ergötzt dich an den Goldmünzen, die vor deinen Augen lagen. `n
Du nimmst dir insgesamt 3000 Gold und machst dich damit aus dem Staub, sodass dich keiner sehen oder bemerken konnte.`n`n");
$session[user]['turns']-=1;
$session[user]['gold']+=3000;
$session[user]['dieb']+=1;
break;
       case '3':
output("Ein Grinsen breitete sich auf deinen Lippen aus als du zum ersten Mal zu sehen bekommst, wie reich diese Einrichtung hier wirklich war. Da konnte man doch einfach nicht widerstehen und so steckst du 6 der Edelsteine ein. `n
Bei solchen Mengen würde es wohl kaum auffallen. Nie hättest du zu träumen gewagt, dass dein Raubzug so erfolgreich sein würde. Und es hatte noch nicht mal einer bemerkt, bis du in Sicherheit warst.`n`n");
$session[user]['turns']-=1;
$session[user]['gems']+=6;
$session[user]['dieb']+=1;
break;
       case '4':
output("`8Getrieben von deiner Gier, vergisst du sogar, dass es eine Straftat war, die du hier gerade begehst. Niemand war mehr hier außer dir und so bedienst du dich einfach an den heutigen Tagesseinahmen. `n
Dabei erbeutest du insgesamt 4 Edlesteine, die du gut versteckt in deinen Taschen nach draußen beförderst. `n
Keine Menschenseele hatte etwas bemerkt… das würde wohl erst am nächsten Morgen geschehen, wo du ja zum Glück bereits über alle Berge warst.`n`n");
$session[user]['turns']-=1;
$session[user]['gems']+=4;
$session[user]['dieb']+=1;
break;
       case '5':
output("`8Du warst dir sicher, dass keiner mehr im Gebäude war aber woher tauchten denn auf einmal die Schritte auf, die du im Gang hören konntest? `n
Dicht an die Wand gedrängt lauschst du, ob sie sich näherten oder verschwanden. Wenn dich einer mit den Einnahmen in der Tasche erwischte, warst du sicherlich deine Arbeit los und für alle Zeit als Dieb abgestempelt. `n
Als es für einen Augenblick still war. Nutzt du die Gelegenheit und rennst so schnell es nur ging davon. Aber du konntest immerhin noch 1000 Goldstücke erbeuten.`n`n");
$session[user]['turns']-=2;
$session[user]['gold']+=1000;
$session[user]['dieb']+=1;
break;
       case '6':
output("`8Lautlos drückst du die Tür zur Schatzkammer des Unterhaltungsraumes auf und vergewisserst dich erst einmal, dass du auch wirklich ungestört sein würdest. Nachdem alles ruhig blieb trittst du vorsichtig ein und beginnst damit, deine Taschen zu füllen. `n
Leider kamen in diesem Augenblick einige Barden um die Ecke und du musstest dich schnell verstecken. Dabei fällt dir ein Großteil des Diebesgutes wieder herunter und du kannst im Endeffekt nur 2 Edelsteine sicher herausschaffen. Aber es war immer noch besser als, wenn sie dich erwischt hätten.`n`n");
$session[user]['turns']-=2;
$session[user]['gems']+=2;
$session[user]['dieb']+=1;
break;
       case '7':
output("`&Wie konntet ihr es nur wagen euch an den Schätzen dieser Einrichtung zu vergreifen? `8donnerte Sillophos Worte auf dich ein als würde ein heftiges Blitzgewitter über dich hereinbrechen. Dass dieser alte Mann auch eine so gute Lunge hatte, konnte man auch schlecht erahnen. Jedenfalls hattest du versucht dich an den Einnahmen zu bedienen und für ihn gab es da keine große Diskussion.Du fliegst im hohen Bogen auf die Straße und brauchtest dich hier wahrlich nicht mehr blicken zu lassen. `n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
$session[user]['dieb']+=1;
break;
       case '8':
output("`8Mit grummelnden Blicken mustert dich Sillophos und du wartest schon darauf, dass er dir eine ordentliche Strafpredigt erteilt. Dabei wolltest du dein Gehalt doch nur ein wenig aufbessern indem du einen kleinen Teil der Einnahmen in deine Tasche steckst… `n
Zu früh gefreut, denn deine Tat wurde beobachtet und sofort gemeldet. `n
Mit einer deftigen Verwarnung schickt Sillophos dich zurück an die Arbeit. `n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
$session[user]['dieb']+=1;
break;
       case '9':
output("`&Wie konntet ihr es nur wagen euch an den Schätzen dieser Einrichtung zu vergreifen? `8donnerte Sillophos Worte auf dich ein als würde ein heftiges Blitzgewitter über dich hereinbrechen. Dass dieser alte Mann auch eine so gute Lunge hatte, konnte man auch schlecht erahnen. Jedenfalls hattest du versucht dich an den Einnahmen zu bedienen und für ihn gab es da keine große Diskussion.Du fliegst im hohen Bogen auf die Straße und brauchtest dich hier wahrlich nicht mehr blicken zu lassen. `n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
$session[user]['dieb']+=1;
break;
       case '10':
output("`8Mit grummelnden Blicken mustert dich Sillophos und du wartest schon darauf, dass er dir eine ordentliche Strafpredigt erteilt. Dabei wolltest du dein Gehalt doch nur ein wenig aufbessern indem du einen kleinen Teil der Einnahmen in deine Tasche steckst… `n
Zu früh gefreut, denn deine Tat wurde beobachtet und sofort gemeldet. `n
Mit einer deftigen Verwarnung schickt Sillophos dich zurück an die Arbeit. `n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
$session[user]['dieb']+=1;
break;
       case '11':
output("`&Wie konntet ihr es nur wagen euch an den Schätzen dieser Einrichtung zu vergreifen? `8donnerte Sillophos Worte auf dich ein als würde ein heftiges Blitzgewitter über dich hereinbrechen. Dass dieser alte Mann auch eine so gute Lunge hatte, konnte man auch schlecht erahnen. Jedenfalls hattest du versucht dich an den Einnahmen zu bedienen und für ihn gab es da keine große Diskussion.Du fliegst im hohen Bogen auf die Straße und brauchtest dich hier wahrlich nicht mehr blicken zu lassen. `n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
$session[user]['dieb']+=1;
break;
       case '12':
output("`8Mit grummelnden Blicken mustert dich Sillophos und du wartest schon darauf, dass er dir eine ordentliche Strafpredigt erteilt. Dabei wolltest du dein Gehalt doch nur ein wenig aufbessern indem du einen kleinen Teil der Einnahmen in deine Tasche steckst… `n
Zu früh gefreut, denn deine Tat wurde beobachtet und sofort gemeldet. `n
Mit einer deftigen Verwarnung schickt Sillophos dich zurück an die Arbeit. `n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
$session[user]['dieb']+=1;
break;
}
}
if($session[user]['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`7Betrübt aber entschlossen betrittst du das Büro von Sillophos, der dir gleich einen seltsamen Blick zuwarf als wüsste er, was du vorhattest. `n
Mit leiser Stimme bringst du ihm vorsichtig bei, dass du keine Lust mehr auf die Arbeitsstelle hast und wartest danach geduldig auf eine Regung seinerseits. Erst geschah nichts, doch dann seufzte er mit geschlossenen Augen. `n
`&Da ich euch nicht aufhalten kann, so wünsche ich euch noch viel Erfolg in der Zukunft. Ihr habt wahrlich Fleiß und vielleicht kehrt ihr eines Tages auch zu uns zurück. `n
`8Nach diesen Worten verabschiedest du dich noch von ihm und siehst dich nach neuen Ufern um.`n");
$session[user]['jobid']=0;
}


addnav("`c`bzurück`c`b","village.php");
page_footer();
?>