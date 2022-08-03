<?php
//Slumps des Dorfes
//Immer wieder die Langeweile.
//By Anubis btw. Benjamin aka Tamakun

require_once("common.php");

//Einstellungen

$name1 = "Bel Shirash";
$name2 = "Sysurel";
$name3 = "Darus";
$name4 = "Karfang";


//Ende (Name Zwei steht für ein Pferd der rest für Personen)

if ($_GET['op']==""){
page_header("Die dunkle Gasse");
addcommentary("");


output("`wBu`4nt `Wis`et e`Es auch hier, nur wirkt es alles hier ein wenig dreckiger, als hätte man ein Stück `GK`Rohl`Ge `Ein die Farbe gemischt die sich auch hier ständig ändert. Mal ist es ein `7ma`Gus`7gr`Gau`E, dann wieder ein dreckiges `qo`Br`öan`Bg`qe`E, genau definieren kann man es allerdings nie. Einige Leute scheinen hier sogar in den engen Pfaden zwischen den Häusern zu leben, weshalb viele `ZFe`^ue`qrs`Qte`\$ll`ee`Wn`E zwischen dem Kopfsteinpflaster zu erkennen sind. Man sollte also aufpassen wo man hin tritt um sich nicht die Füße zu verbrennen, denn man weiß ja das das Feuer hier, auch wenn es schon vor Stunden erloschen ist, sehr wechselhaft sein kann und sich entscheidet, bei der kleinsten Berührung in einer Explosion wieder auszubrechen. Die Gasse führt an einigen Häusern vorbei auf einen kleinen Platz mit `7S`St`^a`Wt`^u`Se`7n`E. Schon von hier hörst du das Gasthaus, welches wieder einmal lauthals vor sich hin singt und ebenfalls an dem Platz mit Stat`eue`Wn l`4ie`wgt.
`n`n`n");


addnav("Die Slums");
addnav("Zum Singenden Gasthaus","inn.php");
addnav("Zu den Statuen","slums.php?op=stat");
addnav("Weiter Durchsuchen","slums.php?op=suchen");
addnav("Zurück");
addnav("Zum Dorf","village.php");

viewcommentary("slums","Sprechen:",15);

}


if ($_GET['op']=="stat"){


page_header("Die Statuen");
addcommentary("");
output("`wHi`4er `Wha`ebe`En die `\$F`qa`^r`Ob`3g`5n`Ro`Äm`öe`E wohl einmal richtig zugeschlagen. Drei `7S`St`^a`Wt`^u`Se`7n`E stehen hier und sollen die Bewohner der Stadt an deren Geschichte erinnern. In strahlend bunten `\$F`qa`^r`Ob`3e`5n`E blicken diese drei Gestalten einem entgegen und man glaubt fast, von den Farben blind zu werden, aber immer wieder kannst du dunkle Stellen erkennen, die dringend mal wieder geputzt werden müssen. Wie alles in der Gasse scheint auch der Platz grundsätzlich ein wenig dreckige Farbe abbekommen zu haben und so wäre es vielleicht nicht schlecht wenn du den einzigen Lichtblick ein wenig säuberst, und der Bevölkerung dieser Gassen damit hilfst, denn die Farbe scheint sogar nachts in prächtigem `WR`\$e`Qg`qe`^n`Zb`Oo`2g`3e`fn`E,  zu `ele`Wuc`4ht`wen.`n`n`n`n`n");

addnav("Die Slums");
if ($session['user']['statue']==0) {
addnav("Die Statuen Putzen","slums.php?op=putzen");
}
addnav("Zum Singenden Gasthaus","inn.php");
addnav("Weiter Umschauen","slums.php?op=suchen");
addnav("Zurück","slums.php");

viewcommentary("statuen","Sagen:",15);


}

if ($_GET['op']=="putzen"){

page_header("Statuen Putzen");
$name = $session[user][name];
output("`7 Du putzt Stundenlang die Statuen bis sie glänzen`n`n.");


$event = e_rand(1,3);
if ($event==1) {addnews("$name `0 wurde von `qM`Qi`en`Way`4a`0 Gesegnet!");
$session['user']['charm']++;
//$session['user']['reputation']++;
$session['user']['statue']='1';
output("`7 `EDu putzt die Statuen gründlich und verlierst dich ganz in deiner Arbeit. Stundenlang bist du an ihren knallbunten Winkeln und Krümmungen zugange, als du plötzlich eine Stimme hörst: `q\"Vielen Dank für Deine Mühe! Ich bin `qM`Qi`en`Way`4a`q, die Schutzheilige der Künste. Lass mich dir einen Segen geben für all die Arbeit.\" `EDu spürst, wie ein Kribbeln von deinen Fingerspitzen durch deinen ganzen Körper zieht und ein gutes Gefühl hinterlässt. Da wirst du gleich ein wenig selbstbewusster!`n`n`n");
addnav("Zurück");
addnav("Zurück zu den Slums","slums.php");

}

else if ($event==2) { addnews("$name `0 wurde von `qM`Qi`en`Way`4a `0Gesegnet!");
$session['user']['maxhitpoints']++;
//$session['user']['attack']++;
$session['user']['statue']='1';
output("`ENach langer, mühevoller Arbeit hast du die Statuen zum Glänzen gebracht. Zufrieden stemmst du die Hände in die Hüften und wischst dir den Schweiß von der Stirn, als du bemerkst, dass jemand neben dir stehengeblieben ist und ebenfalls die Statuen betrachtet.
Es ist `ê´e`%l`5O`1h`ya `Ys`#c`3h`Oe `o´`uo`Um `Ian`tUt `qM`Qi`en`Way`4a`E, die Schutzheilige der Kunst! Sie lächelt dich fröhlich an. `q\"Danke, dass Du Dich so um die Statuen gekümmert hast. Schau wie sie sich freuen jetzt wo sie wieder glänzen! Lass mich dir ein Geschenk machen zum Dank.\" `EIhre Lebensfreude steckt an, du fühlst dich gleich gesünder.
`n`n");
addnav("Zurück");
addnav("Zurück zu den Slums","slums.php");

}


else if ($event==3) {addnews("$name `0war unvorsichtig und wurde von einem Räuber getötet!");

//$session['user']['maxhitpoints']--;

$session['user']['statue']='1';
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session[user][gold]=0;
addnav("Tägliche News","news.php");
output("`EGanz in deine Arbeit versunken, spürst du plötzlich einen harten Schlag auf den Hinterkopf, und dir wird `Rschwarz vor Augen`E. Du wurdest von einem Räuber niedergeschlagen! Du hast alles Gold verloren das du bei dir hattest.`n
...Leider kommt auch so schnell keiner vorbei, der jemanden zu heilen versuchen würde, der blutend in der Dunklen Gasse liegt.`n`n

`$ Du bist tot!
");
}


}
if ($_GET['op']=="suchen"){


page_header("Der Bettler");

output("Als du dich weiter Umschaust erkennst du einen Alten Bettler...Du kennst ihn aus deiner Jugend er hat dir damals immer Geschichte erzählt von denen Du Geträumt hast...Ob er wohl heute Geschichten von dir Erzählt? Aber du denkst nicht weiter Drüber nach als du bemerkst das er Erblindet ist und Stumm so wie er sich Ausdrückt...");


addnav("Wege");
addnav("Zur Schenke","inn.php");
addnav("Zurück ins Dorf");
}

page_footer();
 ?> 