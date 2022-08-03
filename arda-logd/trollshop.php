<?php

require_once "common.php";
page_header("Bautrollshop");
switch($_GET['op'])
{
default:
{
output("`yDu betrittst einen Laden, in dem eine Art Troll hinter einem Tresen sitzt.`nAls er dich bemerkt, sieht er dich an und schiebt dir eine Art Katalog zu. `qSo. Du willst also dein Haus ausbauen? Dann schau mal, was wir im Angebot haben. Aber ein Ausbau kostet dich 500 Edelsteine und 300000 Gold. `yBei den letzten Worten merkst du, daß er dabei keinen Spaß versteht und du besser nicht versuchen solltest, ihn zu betrügen. Außerdem siehst du ein Schild, auf dem steht, daß es einige Zeit dauern kann bis das Zimmer angebaut ist. Schließlich gibt es mehr als nur deinen Auftrag...");

/*if ($session['user']['rppunkte']>=2000)
{
output("`qAh, ich sehe, du hast schon sehr viel geredet. Wie wärs mit einem Raum für Tagebücher?");
addnav("Tagebuchzimmer","trollshop.php?op=tage");
}*/
if ($session['user']['rppunkte']>=10000)
{
output("`qAh, ich sehe, du hast schon sehr viel geredet. Wie wärs mit einem Rederaum?");
addnav("Geheimer Raum","trollshop.php?op=plus");
}
if ($session['user']['gems']>=500 && $session['user']['gold']>=300000 )
{
addnav("Kaminzimmer","trollshop.php?op=kamin");
addnav("Gemeinschaftsraum","trollshop.php?op=gemein");
addnav("Küche","trollshop.php?op=kuche");
addnav("Badezimmer","trollshop.php?op=bad");
addnav("Bibliothek","trollshop.php?op=buecher");
addnav("Lesezimmer","trollshop.php?op=lese");
addnav("Lagune","trollshop.php?op=lagune");
addnav("Wohnzimmer","trollshop.php?op=wohn");


}
addnav("Schnell wieder raus","village.php");
break;
}
case 'kamin':
{
output("`FDu kaufst ein Kaminzimmer. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein Kaminzimmer angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'bad':
{
output("`FDu kaufst ein Badezimmer. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein Badezimmer angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'kuche':
{
output("`FDu kaufst eine Küche. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein Küche angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'gemein':
{
output("`FDu kaufst einen Gemeinschaftsraum. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." einen Gemeinschaftsraum angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'buecher':
{
output("`FDu kaufst eine Bücherei. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." eine Bücherei angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'lese':
{
output("`FDu kaufst ein Büro. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein Büro angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'lagune':
{
output("`FDu kaufst eine Lagune. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." eine Lagune angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'plus':
{
output("`FDu kaufst einen geheimen Raum. Die Edelsteine legst du auf den Tisch, wo der Troll sie direkt einsteckt. `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." einen Ü18-Raum angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'wohn':
{
output("`FDu kaufst ein Wohnzimmer.  `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['gems']-=500;
$session['user']['gold']-=300000;
savesetting ("selledgems" ,getsetting ("selledgems",0)+500);
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein Wohnzimmer angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
case 'rede':
{
output("`FDu kaufst ein RP-Zusatzzimmer.  `qGut, das Zimmer wird so schnell wie möglich angebaut. `FDabei hat er sich ein paar Notizem gemacht und wendet sich dann diesen wieder zu. ");
$session['user']['rppunkte']-=10000;
systemmail(2,"`eAusbau Haus",$session['user']['name']." `ehat im Haus Nummer ".$session['user']['house']." ein RP-Zusatzzimmer angebaut. Kümmere dich darum.");
addnav("Zurück","village.php");
break;
}
}
page_footer();
?>