<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Schulungsraum");

output("`v`c`bSchulungsraum`b`c`2 Du betrittst einen Raum indem es geschäftig zugeht. An mindestens 20 Tischen siehst du Leute in Arbeitskleidung herumwerkeln. Du näherst dich einem und versuchst zu erkennen was dort gerade entsteht. Es sieht aus wie ein Regal und als du den eifrigen Arbeiter fragst, nickt dieser und ist stolz das man es erkennt. Ein Tisch in der linken Ecke fesselt deine Aufmerksamkeit da direkt neben diesem eine Feuerstelle errichtet wurde und auf ihm ein kleiner Amboss steht. Harte Schläge von Metall auf Metall hallen durch den Raum. Aus der linken Ecke strömt dir ein lieblicher Duft entgegen und auf diesem Tisch steht ein großer Topf. Du trittst etwas näher und erkennst sich in dem Topf heißes Wachs befindet, in das  eine Arbeiterin immer wieder einen langen Docht hinein hält und wieder herauszieht und so langsam eine Kerze entsteht.
Vorne an der Tafel stehen die für diese Stunde vorgesehen Arbeiten. Eine streng wirkende Frau bekleidet mit einem grauen Kleid mit hoch geschlossenem Kragen beendet gerade einen Satz den sie an die Tafel schrieb und dreht sich um. Sie läuft durch den Raum und betrachtet die Werke der Arbeitenden. Sie nickt zustimmend, gibt Hinweise oder schüttelt missbilligend den Kopf.
Dann entdeckt sie dich und kommt etwas angestrengt lächelnd auf dich zu. `& Seid gegrüßt. Ich bin Direa und die Ausbildungsleiterin. Ihr befindet euch hier im Ausbildungsraum. Hier könnt ihr euch beruflich weiterbilden. Oder sucht ihr etwa Arbeit?`2 Bei dem letzten Satz sieht sie dich hoffnungsvoll an.
`n");
addnav("`9Weiterbilden","weiterbildung.php?op=wb");
if($session['user']['jobid'] ==30){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","weiterbildung.php?op=dadrei");
addnav("Kündigen","weiterildung.php?op=go");
addnav("Stehlen","weiterbildung.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

// Beginn Weiterbildung

if ($_GET['op']=="wb"){
if($session['user']['schulef'] ==1){
output("`9`n Ohne lange nachdenken zu müssen sagst du ihr, dass du dich weiterbilden lassen willst. `n
`&Für die erste Weiterbildung müsst ihr ein Schulgeld von insgesamt 70.000 Gold bezahlen und es wird euch 14 Runden kosten. `n`n");
addnav("Weiterbilden","weiterbildung.php?op=wbzwei");
addnav("Lieber doch nicht","ausbildung.php");
}elseif($session['user']['schulef'] ==2){
output("`9`n`n `&Ich muss für die Weiterbildung eine Summe von insgesamt 80.000 Gold verlangen. Und es wird euch 16 Runden kosten.Um euch weiterzubilden `9sagt sie. `n");
addnav("Weiterbilden","weiterbildung.php?op=wbdrei");
addnav("Lieber doch nicht","ausbildung.php");
}elseif($session['user']['schulef'] >=3){
output("`9`n`n Du hast schon alle Ausbildungsgrade`n");
addnav("Zurück","ausbildung.php");
}
}
if ($_GET['op']=="wbzwei"){
if(($session['user']['gold'] >=70000)||($session['user']['turns'] >14)){
output("`9`n`n Ich wünsche euch gutes Gelingen `2 meint sie daraufhin und verweist dich auf den Lehrraum. `n");
$session['user']['turns']-=14;
$session['user']['gold']-=70000;
$session['user']['schulef']+=1;
}else{
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe oder Gold hast `n");
}
}
if ($_GET['op']=="wbdrei"){
if(($session['user']['gold'] >=80000)||($session['user']['turns'] >16)){
output("`9`n`n `2Du entschließt dich zu einer Weiterbildung `n
`2Ein Seufzen dringt über deine Lippen, da das wirklich sehr viel war, doch hatte es eben seinen Preis einen der besten Arbeitsstellen in diesem Dorf aufnehmen zu können.`n");
$session[user]['turns']-=15;
$session[user]['gold']-=80000;
$session[user]['schulef']+=1;
}else{
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe oder Gold hast `n");
}
}
// Ende Weiterbildung

if ($_GET['op']=="dadrei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda']==0){

output("`8Jetzt fiel es dir ja nun wirklich nicht mehr schwer dich für die Arbeit zu entscheiden. In letzter zeit hattest du auch deiner Meinung nach erst einmal genug gelernt und brauchtest etwas Abwechslung als immer nur die Schulbank zu drücken. `n
Sie nickt dir mit freundlichem Lächeln zu und zeigt dir eine Liste mit allen Berufen, die du erlernen kannst. `n
Jetzt musstest du dich nur noch entscheiden.
`n`n");

switch(e_rand(1,5)){

       case '1':
output("`4Sie seufzt erleichtert als du ihr sagst dass du Arbeit suchst.`& Das trifft sich gut. Ich wollte gerade zum Mittagessen gehen und mag es nicht mich dabei sputen zu müssen. 2 Stunden sind für den Anfang auch ganz gut und du wirst es hoffentlich schaffen, dass die Schüler nicht gleich den ganzen Raum auseinander nehmen.`4 Sie lacht leise und meint: `&Natürlich hast auch du etwas davon ich denke 2000 Goldstücke werden als Bezahlung für deine Zeit genügen.`4 Sie rauscht lächelnd davon und überlässt dich den 18 neugierigen Gesichtern vor dir.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;

case '2':
output("`4Kaum hast du ausgesprochen dass du Arbeit suchst packst Direa auch schon in fliegender Hast hre Sachen zusammen und schreit dir bereits halb aus der Tür noch zu : `& Ich werde in 3 Stunden wiederkommen und bis dahin möchte ich auf jedem Tisch ein fertiges Werkstück sehen. Wenn alles zu meiner Zufriedenheit ist bekommst du 3000 Goldstücke für deine Mühe. Ach ja und die Schüler beißen nicht. Auf bald!`4 Mit diesen Worten schließt sich die Tür hinter ihr und eine drückende Stille legt sich über den Raum. Alle Anwesenden haben ihre Arbeit eingestellt und starren dich an. Du schluckst trocken und versuchst autoritär zu wirken. Dann erhebst du die Stimme:`& Guten Tag. Zuerst werden wir uns mit den Schutzvorschriften bei den Arbeiten befassen.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '3':
output("`4Direa atmet erleichtert auf als sie hört dass du gekommen bist um zu arbeiten. Sie meint:`& Gott sei Dank dass du hier bist. Wie ich bereits sagte bin ich eigentlich die Leiterin dieser Einrichtung und keine wirkliche Ausbilderin. Leider sind all meine Angestellten von einer Krankheit befallen und ich musste ihre Arbeit übernehmen. Dabei habe ich selber jede Menge zu tun. Wenn du mir diese Klasse vielleicht für 4 Stunden abnehmen könntest wäre ich dir sehr verbunden. Meine Dankbarkeit würde ich in Form von 4000 Goldstücken ausdrücken. Bist du einverstanden?`4 Sobald du etwas von einer Belohnung gehört hattest, war die Sache für dich klar und du streckst ihr die Hand entgegen um die Abmachung zu besiegeln. Direa schlägt freudig ein und entschwindet schnell durch die Tür.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=4000;
$session['user']['jobda']+=1;

break;
case '4':
output("`4Direa sieht dich mit leuchtenden Augen an bevor sie dich etwas zur Seite zieht und dir zuflüstert:`& Das passt ja hervorragend, dass du gerade hier auftauchst und Arbeit suchst. Du musst nämlich wissen dass ich in einer Woche heirate und mich eigentlich heute mit der Schneiderin meines Hochzeitskleides treffen wollte. Dies wird etwas dauern da wir auch gleich die Kleider der Brautjungfern besprechen. Doch ich denke in 5 Stunden könnte ich wieder hier sein. Könntest du diese Meute hier solange beschäftigen? Eigentlich kennen alle ihre Aufgaben du müsstest sie nur im Auge behalten und von Zeit zu Zeit etwas antreiben. Ich würde dir etwa 5000 Goldstücke zahlen, ein geringer Preis wenn ich bedenke dass du zum Gelingen meiner Hochzeit beiträgst. Also sind wir uns einig?`4 Du lächelst sie an und nickst. Sie umarmt dich wird jedoch gleich darauf wieder ernst und zieht ihr Kleid zu recht. Dann nickt sie dir höflich zu und marschiert erhobenen Hauptes durch die Tür.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=5000;
$session['user']['jobda']+=1;

break;
case '5':
output("`4Direa sieht dich verwirrt an. Jetzt wo du sie aus der Nähe siehst, bemerkst du die dunklen Schatten unter ihren Augen und den schmerzvollen Zug um den Mund. Sei schwankt leicht und hat einen glasigen Blick. Du gehst ihr noch etwas entgegen und stützt sie. Sie blickt zu dir auf:`&Danke…oh ich bin so schwach…eigentlich hat mir der Arzt verboten zur Arbeit zu gehen doch heute morgen ging es mir noch bestens. Ich habe mir eine schwere Grippe zugezogen. Was wolltest du noch einmal wissen? Ach ja wegen der Arbeit…nun in  diesem  Zustand bin ich als Ausbilderin wohl nicht viel wert. Es wäre schön wenn du diese Klasse noch etwa 6 Stunden unterrichten kannst und ich nach hause gehe um mich auszukurieren. Ich würde dich auch für deine Mühe entschädigen und dir 6000 Goldstücke zahlen. Bist du einverstanden?`4 Schnell willigst du ein und fragst sie ob es weit ist bis zu ihrem Haus.`& Oh nein ich wohne gleich im Nachbarhaus das schaffe ich schon aber dennoch danke.`4 Sie geht langsam zur Tür, blickt sich noch einmal schwach lächelnd zu dir um und schließt dann leise die Tür hinter sich.`n`n");
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
output("`7Bevor dich Direa deinem Schicksal überlässt weist sie dich noch daraufhin, dass dies die 1. Unterrichtsstunde dieser Klasse ist und die Teilnehmer am Ende für diese und für die  folgenden Stunden im Voraus bezahlen müssen. Sie nennt dir den Preis und winkt dir zum Abschied bevor sie den Raum verlässt. Deine  Arbeitszeit verfliegt im Nu und gegen Ende der letzten Stunde überlegst du dir, dass die Schüler doch eigentlich nicht wissen können wie viel sie zahlen müssen und du beschließt einen Aufschlag auf die Bezahlung zu erheben um deine Zeit noch besser zu belohnen. Nacheinander geben die Teilnehmer brav ihr Gold ab und du zuckst beim Nennen des Preises mit keiner  Wimper. Als du schließlich das Gold zählst bleiben dir nach Abzug des Geldes für Direa noch 5 000 Goldstücke übrig. Du lächelst glücklich und machst dich auf den Weg zur ihr.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Während du etwas an die Tafel schreibst fragt dich einer der Schüler etwas und du drehst dich schwungvoll zu ihm. Dabei stößt du die Schulkasse vom Tisch die auf der Kante deines Tisches stand. Die Kiste springt auf und ihr Inhalt verteilt sich in der Hälfte des Raumes. Schnell weist du die Schüler an dir beim einsammeln zu helfen. Während du so auf den Knien herumrobbst und Goldstücke einsammelst kommt dir eine Idee und du steckst diese in deine eigene Tasche statt sie wieder zurück in die Kiste zu legen. Als Direa am Ende des Arbeitstages das Fehlen des Goldes bemerkt, erklärst du ihr dass wohl einige Schüler etwas genommen haben. Direa glaubt dir nicht so recht und weißt dich auf die Höhe der Summe hin. Du rechnest ihr jedoch vor dass dies durchaus sein kann bei 25 Schülern. Auf dem Nachhauseweg zählst du deine Ausbeute und kommst so auf stattliche 3000 Gold.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`7An einem der Tische im Raum befindet sich eine Art Schmiede. Dort soll einer der Schüler ein selbst geschmiedetes Schwert mit Edelsteinen sockeln. Direa hat dir dafür 30 Edelsteine gegeben. Du denkst dir grade welche Verschwendung das ist als dir einfällt, dass ja keiner etwas von einer bestimmten Anzahl von Edelsteinen auf dem Schwert gesagt hat. Also gibst du dem Schmiedelehrling nur 10 Edelsteine und befühlst am Ende deines Arbeitstages glücklich die 6 Edelsteine in deiner Tasche.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`7Als du an deinem Tisch vor der Tafel sitzt wird es dir langsam zu viel immer nur den Schülern beim Arbeiten zuzusehen also wendest du den Blick nach draußen. Du siehst auf dem Fensterbrett ein  Nest liegen und als du genauer hinsiehst bemerkst du ein Blinken im Inneren. Langsam erhebst du dich und öffnest das Fenster. Als du den fragenden Blicken der Arbeiter begegnest, räusperst du dich und meinst:`& Etwas frische Luft wird den Geist und auch die Hände beflügeln.`7 Du lehnst dich nach draußen und greifst in das Nest. Du fühlst etwas Hartes und nimmst es heraus. Es ist ein Edelstein wie du verwundert feststellst. Diese Prozedur wiederholst du noch 3mal und hast so 4 Edelsteine in deiner Tasche. Glücklich schließt du das Fenster und setzt dich wieder an deinen Tisch um auf das Ende deiner Arbeitszeit zu warten.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Direa muss ein Gespräch mit einem der Ausbilder wegen ungebührlichem Verhalten führen und trägt dir auf das Gold in der Schulkasse zu zählen während sie mit ihm im Hinterzimmer spricht. Du leerst also den Inhalt der Kiste auf dem Tisch aus und beginnst zu zählen. Direa hatte dir vorher einen Betrag genannt der sich darin befinden musste und du stutzt als du fertig bist. Du schüttelst den Kopf und beginnst von vorn doch es ändert sich nichts am Ergebnis. Es sind 5000 Goldstücke zu viel. Da es zwischen Hinterzimmer und Ausbildungsraum ein Fenster gibt und Direa dich genau beobachten kann streckst du langsam eine Hand nach dem Gold aus und lässt es in deiner Tasche verschwinden. Dies wiederholst du einige Male, hälst jedoch abrupt inne als sich hinter dir jemand räuspert. Du verharrst mit ausgestreckter Hand und drehst den Kopf etwas. Du erblickst Direa die dich aufmerksam mustert.`& Nun wie viel Gold befindet sich in der Schulkasse? Ist es der Betrag den ich dir nannte?`7 Du verneinst das und sagst ihr dass der vor dir liegende Goldhaufen zu viel ist und das Gold in der Kiste genau ihrem Betrag entspricht. In Direas Augen blitzt es auf und schnell steckt sie das restliche Geld in ihre eigene Tasche dann zögert sie gibt dir 5 Goldstücke, meint: `&Du hast nichts gesehen`7 und verschwindet im Hinterzimmer. Als du die Ausbildungsstätte verlässt befinden sich 1005 Goldstücke in deiner Tasche.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1005;
$session['user']['dieb']+=1;
break;
       case'6':
output("`7An einem besonders sonnigen Tag musst du natürlich arbeiten und bist in dementsprechender Laune. Missmutig bemerkst du dass die Sonne dich blendet und stehst auf um die schweren Samtvorhänge an den Fenstern zuziehen. Diese sind jedoch mit einem Band und einer Art Knopf befestigt. Du betrachtest den Knopf näher und stellst fest das es ein Edelstein ist. Mit neuem Elan machst du dich an die Arbeit die Vorhänger zu schließen, lässt dir dabei aber genug Zeit um jedes Mal den Edelstein zu entfernen und in deiner Tasche verschwinden zu lassen. Dein Verhalten erregt zuerst bei den Schülern Aufsehen und die daraufhin folgende Unruhe bleibt von Direa nicht unbemerkt. Sie verlässt das Hinterzimmer und kommt in deine Richtung um herauszufinden wie schwierig und zeitaufwendig es sein kann einen Vorhang zu öffnen. Du warst grade dabei einen weiteren Edelstein zu entfernen, bemerkt Direa aber noch rechtzeitig.
Als du ihren fragenden Blick auffängst, versuchst  du zu erklären, dass es deshalb so lange gedauert hat, weil du eine Abneigung gegen Samt hast und die Vorhänge so wenig wie möglich berühren wolltest. Sie nickt dir zu und geht kopfschüttelnd wieder ins Hinterzimmer. Du setzt dich wieder an deinen  Tisch und befühlst die 2 Edelsteine in deiner Tasche.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`7Du musstest dich einmal mehr zum Tisch eines besonders schwierigen Schülers begeben. Der junge Mann stammt aus adligem Hause und wahrscheinlich hat man ihm dort gesagt er könne sich etwas darauf einbilden. Nun hatte sein Vater etwas gegen das Nichtstun seiner Sohnes und ließ ihn her einen Beruf erlernen. Der junge Mann jedoch hatte weder Lust noch Talent zur Arbeit und dementsprechend sahen seine Werke auch aus. Was er jedoch im Überfluss hatte war Geld und so hatte ihr eine Übereinkunft getroffen. Du hilfst ihm und er bezahlt dich für diesen Privatunterricht. Dieser Schüler versuchte nun schon seit geraumer Zeit ein Regal zu fertigen und die Stunde war bald zu Ende. Also schiebst du ihn kurzerhand zur Seite, legst deinen Mantel ab und baust selber ein Regal. Nach getaner Arbeit nimmst du deinen Mantel und streckst nachdem du dich kurz umgesehen hast die Hand aus. Er gibt dir etwa 500 Goldstücke und du nickst dankend. Du drehst dich um und plötzlich findest du dich Auge in Auge mit einer sehr erbosten Direa wieder.`& Du wagst es den Stand der Ausbilder zu beschmutzen indem du dich kaufen lässt?`7 ruft sie aus. Sie kommt dir immer näher und sticht dir mit ihrem Zeigefinger in die Brust`& Wie kannst du nur! Nachdem ich dich hier so freundlich aufgenommen habe und dir mehr als genug Lohn zahle!`7 Sie drängt dich langsam in Richtung Tür. `&Hinaus mit dir. Und glaub bloß nicht dass ich das jemals vergesse. Wage es nicht dich hier noch einmal blicken zu lassen.`7 Bei ihren letzten Worten findest du dich im Türrahmen wieder. Sie gibt dir einen festen Stoß und wirft dir die Tür vor der Nase zu.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7An einem brütend heißen Tag musst du natürlich im Ausbildungsraum sitzen und die Schüler beaufsichtigen. Du lehnst dich in deinem Stuhl zurück, seufzt auf und streckst dich. Dann gähnst du verhalten. Die letzte Nacht war wohl doch etwas kurz gewesen, doch wer kann schon einer Pokerrunde in der Schenke `& Zum durstigen Troll`7 widerstehen. Du jedenfalls nicht und deshalb bist du jetzt auch müde. Du lässt deinen Blick über die fleißige Klasse schweifen. Alle schienen so in ihre Arbeit vertieft zu sein, dass es niemanden stören würde, wenn du ein kleines Nickerchen machst. Du legst den Kopf auf die Tischplatte und bist kurz darauf eingeschlafen. Du wirst von einem Räuspern geweckt und öffnest die Augen. Vor dir steht Direa mit verschränkten Armen und blickt dich fragend an. Du richtest dich schnell auf und entschuldigst dich dass du wohl vor Erschöpfung eingeschlafen sein musst. `& Warum schlaft ihr denn des Nachts nicht ausgiebig?`7 fragt dich Direa. Du antwortest dass du die Nacht am Bett deiner kranken Großmutter verbracht hast um ihr behilflich zu sein wenn sie etwas brauch. Da ändert sich Direas Blick und er wird weich und wohlwollend. `& Hättest du doch etwas gesagt. Ich kann die Klasse den Rest des Tages übernehmen. Geh du nur und pflege deine Großmutter.`& Als du schon fast aus der Tür hinaus bist hörst du sie sagen:`& Aber das nächste Mal wenn ich dich schlafend erwische bin ich nicht mehr so gnädig. Bedenke das fallls dein Großvater auch noch erkranken sollte.`8 Du verlässt schleunigst den Raum und fragst dich wie sie dich durchschauen konnte.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '9':
output("`7Du musstest dich einmal mehr zum Tisch eines besonders schwierigen Schülers begeben. Der junge Mann stammt aus adligem Hause und wahrscheinlich hat man ihm dort gesagt er könne sich etwas darauf einbilden. Nun hatte sein Vater etwas gegen das Nichtstun seiner Sohnes und ließ ihn her einen Beruf erlernen. Der junge Mann jedoch hatte weder Lust noch Talent zur Arbeit und dementsprechend sahen seine Werke auch aus. Was er jedoch im Überfluss hatte war Geld und so hatte ihr eine Übereinkunft getroffen. Du hilfst ihm und er bezahlt dich für diesen Privatunterricht. Dieser Schüler versuchte nun schon seit geraumer Zeit ein Regal zu fertigen und die Stunde war bald zu Ende. Also schiebst du ihn kurzerhand zur Seite, legst deinen Mantel ab und baust selber ein Regal. Nach getaner Arbeit nimmst du deinen Mantel und streckst nachdem du dich kurz umgesehen hast die Hand aus. Er gibt dir etwa 500 Goldstücke und du nickst dankend. Du drehst dich um und plötzlich findest du dich Auge in Auge mit einer sehr erbosten Direa wieder.`& Du wagst es den Stand der Ausbilder zu beschmutzen indem du dich kaufen lässt?`7 ruft sie aus. Sie kommt dir immer näher und sticht dir mit ihrem Zeigefinger in die Brust`& Wie kannst du nur! Nachdem ich dich hier so freundlich aufgenommen habe und dir mehr als genug Lohn zahle!`7 Sie drängt dich langsam in Richtung Tür. `&Hinaus mit dir. Und glaub bloß nicht dass ich das jemals vergesse. Wage es nicht dich hier noch einmal blicken zu lassen.`7 Bei ihren letzten Worten findest du dich im Türrahmen wieder. Sie gibt dir einen festen Stoß und wirft dir die Tür vor der Nase zu.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7An einem brütend heißen Tag musst du natürlich im Ausbildungsraum sitzen und die Schüler beaufsichtigen. Du lehnst dich in deinem Stuhl zurück, seufzt auf und streckst dich. Dann gähnst du verhalten. Die letzte Nacht war wohl doch etwas kurz gewesen, doch wer kann schon einer Pokerrunde in der Schenke `& Zum durstigen Troll`7 widerstehen. Du jedenfalls nicht und deshalb bist du jetzt auch müde. Du lässt deinen Blick über die fleißige Klasse schweifen. Alle schienen so in ihre Arbeit vertieft zu sein, dass es niemanden stören würde, wenn du ein kleines Nickerchen machst. Du legst den Kopf auf die Tischplatte und bist kurz darauf eingeschlafen. Du wirst von einem Räuspern geweckt und öffnest die Augen. Vor dir steht Direa mit verschränkten Armen und blickt dich fragend an. Du richtest dich schnell auf und entschuldigst dich dass du wohl vor Erschöpfung eingeschlafen sein musst. `& Warum schlaft ihr denn des Nachts nicht ausgiebig?`7 fragt dich Direa. Du antwortest dass du die Nacht am Bett deiner kranken Großmutter verbracht hast um ihr behilflich zu sein wenn sie etwas brauch. Da ändert sich Direas Blick und er wird weich und wohlwollend. `& Hättest du doch etwas gesagt. Ich kann die Klasse den Rest des Tages übernehmen. Geh du nur und pflege deine Großmutter.`& Als du schon fast aus der Tür hinaus bist hörst du sie sagen:`& Aber das nächste Mal wenn ich dich schlafend erwische bin ich nicht mehr so gnädig. Bedenke das fallls dein Großvater auch noch erkranken sollte.`8 Du verlässt schleunigst den Raum und fragst dich wie sie dich durchschauen konnte.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '11':
output("`7Du musstest dich einmal mehr zum Tisch eines besonders schwierigen Schülers begeben. Der junge Mann stammt aus adligem Hause und wahrscheinlich hat man ihm dort gesagt er könne sich etwas darauf einbilden. Nun hatte sein Vater etwas gegen das Nichtstun seiner Sohnes und ließ ihn her einen Beruf erlernen. Der junge Mann jedoch hatte weder Lust noch Talent zur Arbeit und dementsprechend sahen seine Werke auch aus. Was er jedoch im Überfluss hatte war Geld und so hatte ihr eine Übereinkunft getroffen. Du hilfst ihm und er bezahlt dich für diesen Privatunterricht. Dieser Schüler versuchte nun schon seit geraumer Zeit ein Regal zu fertigen und die Stunde war bald zu Ende. Also schiebst du ihn kurzerhand zur Seite, legst deinen Mantel ab und baust selber ein Regal. Nach getaner Arbeit nimmst du deinen Mantel und streckst nachdem du dich kurz umgesehen hast die Hand aus. Er gibt dir etwa 500 Goldstücke und du nickst dankend. Du drehst dich um und plötzlich findest du dich Auge in Auge mit einer sehr erbosten Direa wieder.`& Du wagst es den Stand der Ausbilder zu beschmutzen indem du dich kaufen lässt?`7 ruft sie aus. Sie kommt dir immer näher und sticht dir mit ihrem Zeigefinger in die Brust`& Wie kannst du nur! Nachdem ich dich hier so freundlich aufgenommen habe und dir mehr als genug Lohn zahle!`7 Sie drängt dich langsam in Richtung Tür. `&Hinaus mit dir. Und glaub bloß nicht dass ich das jemals vergesse. Wage es nicht dich hier noch einmal blicken zu lassen.`7 Bei ihren letzten Worten findest du dich im Türrahmen wieder. Sie gibt dir einen festen Stoß und wirft dir die Tür vor der Nase zu.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7An einem brütend heißen Tag musst du natürlich im Ausbildungsraum sitzen und die Schüler beaufsichtigen. Du lehnst dich in deinem Stuhl zurück, seufzt auf und streckst dich. Dann gähnst du verhalten. Die letzte Nacht war wohl doch etwas kurz gewesen, doch wer kann schon einer Pokerrunde in der Schenke `& Zum durstigen Troll`7 widerstehen. Du jedenfalls nicht und deshalb bist du jetzt auch müde. Du lässt deinen Blick über die fleißige Klasse schweifen. Alle schienen so in ihre Arbeit vertieft zu sein, dass es niemanden stören würde, wenn du ein kleines Nickerchen machst. Du legst den Kopf auf die Tischplatte und bist kurz darauf eingeschlafen. Du wirst von einem Räuspern geweckt und öffnest die Augen. Vor dir steht Direa mit verschränkten Armen und blickt dich fragend an. Du richtest dich schnell auf und entschuldigst dich dass du wohl vor Erschöpfung eingeschlafen sein musst. `& Warum schlaft ihr denn des Nachts nicht ausgiebig?`7 fragt dich Direa. Du antwortest dass du die Nacht am Bett deiner kranken Großmutter verbracht hast um ihr behilflich zu sein wenn sie etwas brauch. Da ändert sich Direas Blick und er wird weich und wohlwollend. `& Hättest du doch etwas gesagt. Ich kann die Klasse den Rest des Tages übernehmen. Geh du nur und pflege deine Großmutter.`& Als du schon fast aus der Tür hinaus bist hörst du sie sagen:`& Aber das nächste Mal wenn ich dich schlafend erwische bin ich nicht mehr so gnädig. Bedenke das fallls dein Großvater auch noch erkranken sollte.`8 Du verlässt schleunigst den Raum und fragst dich wie sie dich durchschauen konnte.`n`n");
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
output("`2Mit festen Schritten trittst du vor den Schreibtisch von  und holst tief Luft. Plötzlich kamen dir Zweifel auf. Solltest du es denn wirklich aussprechen? Wie würde sie wohl darauf reagieren? Aber es half nun doch alles nichts. `n
Doch dann nimmst du all deinen Mut zusammen und berichtest ihr von deinem Entschluss zu kündigen. Du konntest richtig beobachten, wie sich eine ihrer Augenbrauen hob und sie dich mit prüfenden Blicken beinahe durchlöcherte. `n
Aber zu deiner Überraschung nickte sie nur und widmete sich danach wieder ihrer eigenen Arbeit. Zur Kenntnis genommen hatte sie es anscheinend also machst du dich einfach davon.`n");
$session['user']['jobid']=0;
}


addnav("zurück","ausbildung.php");
page_footer();
?> 