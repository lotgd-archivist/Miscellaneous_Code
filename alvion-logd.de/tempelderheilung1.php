
<?php

// 24062004

require_once "common.php";


page_header("Tempel des Aeskulap");
$session['user']['standort']="Das Kloster";
output("`q`b`cTempel des Aeskulap`c`b");

$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
$cost = round($cost,0);

if ($_GET[op]==""){
checkday();

output("`n<table align='center'><tr><td><IMG SRC=\"images/aeskulap.jpg\"></tr></td></table>`n",true);
output("`3Du schreitest in den großen, prachtvollen Tempel, welcher dem Gott Aeskulap geweiht ist.`n Du wirst gebeten, deine Waffen am Eingang abzugeben, denn niemand darf mit Waffen vor das Antlitz der Göttin treten.`n`n");

if($session[user][hitpoints] < $session[user][maxhitpoints]){
    output("`3\Du siehst dich um und gehst zu einem Kleriker. Er sieht dich freundlich lächelnd an und spricht: \"`qAeskulap zum Gruße. Ihr sucht Heilung, nicht wahr? Nun, ich bin bereit, Euch zu heilen, solange man mich für meine Dienste angemessen entschädigt.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du.`n`nEr mustert dich von oben bis unten: \"`qFür dich... `^`b$cost`b Goldstücke `qfür eine komplette Heilung!!`3\". Der schlanke, hochgewachsene Kleriker schiebt die Ärmel seiner Kutte leicht nach oben und weißes, warmes Licht erhellt seine Fingerkuppen.");
        addnav("Heilung");
        addnav("`^Komplette Heilung`0","tempelderheilung1.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
        addnav("$i% - ".round($cost*$i/100,0)." Gold","tempelderheilung1.php?op=buy&pct=$i");
     }

}
else if($session[user][hitpoints] == $session[user][maxhitpoints]){
      output("`3Der Kleriker mustert dich von oben bis unten: \"`qHeilung scheint Ihr nicht zu benötigen. Sonst kann ich leider auch nichts für Euch tun. Möge Aeskulap Euch auf Eurem Wege geleiten.`3\" Du nickst dem Kleriker zu und verabschiedest dich freundlich.");
      }
else{
output("`3Der Kleriker mustert dich von oben bis unten: \"`qHeilung scheint Ihr nicht zu benötigen. Sonst kann ich leider auch nichts für Euch tun. Möge Aeskulap Euch auf Eurem Wege geleiten.`3\" Du nickst dem Kleriker zu und verabschiedest dich freundlich.");
$session[user][hitpoints] = $session[user][maxhitpoints];
}
  addnav("`bZurück`b");
  addnav("Zurück zum Klosterhof", "kloster.php");
}
else{
    $newcost=round($_GET[pct]*$cost/100,0);
if ($session[user][gold]>=$newcost){
    $session[user][gold]-=$newcost;
    //debuglog("spent $newcost gold on healing");
    $diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$_GET[pct]/100,0);
    $session[user][hitpoints] += $diff;
    output("`3Du zeigst dem Kleriker deine Wunden und er bittet dich auf einer Liege Platz zu nehmen. Du legst dich hin, er reibt sich kurz die Hände, legt den Kopf in den Nacken und schickt ein Stoßgebet zu dem Gott. Weißes Licht leuchtet um seinen Händen. Er beugt sich mit trüben Blick über dich, legt dir die Hände auf und du spürst, wie eine pulsierende Wärme durch deinen Körper fährt. Erstaunt über diese schnelle Heilung stehst du auf, ziehst dich an und gibst dem leicht erschöpften Kleriker das Gold.");
    output("`n`n`#Du wurdest um $diff Punkte geheilt!");
  }
else{
  output("`3Du willst dem Kleriker gerade deine Wunden zeigen, als du bemerkst, dass du nicht genug Gold für die Heilung dabei hast. Der Kleriker nickt kurz bei deiner Erklärung und bittet dich mit genügend Gold wieder zu kommen.");
  addnav("Heilung");
    addnav("`^Komplette Heilung`0","tempelderheilung1.php?op=buy&pct=100");
    for ($i=90;$i>0;$i-=10){
    addnav("$i% - ".round($cost*$i/100,0)." Gold","tempelderheilung1.php?op=buy&pct=$i");
    }
}
    addnav("`bZurück`b");
  addnav("Zurück zum Klosterhof", "kloster.php");
}


page_footer();

?>

