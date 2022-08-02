<?php
/* idea of bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* idea:
*     a tournament with a woman warrior
*
* details:
*      (07.01.05) start of idea
*/

// Leicht angepasst für mekkelon.de.vu von Amerilion
if (!isset($session)) exit();

/*
* settings
*/
//$special = "special/".basename(__FILE__);

switch ($_GET['op']) {
    case 'gold':
        output('Du löst ängstlich deinen Goldbeutel und händigst ihn den Amazonen aus.
        Dann lassen sie dich weiterziehen.');
        $session['user']['gold'] = 0;
        $session['user']['specialinc'] = '';
    break;
    case 'try':
        $exparray=array(0=>0,100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930);
		foreach($exparray as $key => $val){
            $exparray[$key]= round(
                $val  ($session['user']['dragonkills']4)  $session['user']['level']  100
            ,0);
        }
        $amazone = round(mt_rand($exparray[$session['user']['level']1],$exparray[$session['user']['level']])0.01);
        $user = round(mt_rand($session['user']['experience'],$exparray[$session['user']['level']])0.02);
        output("Etwas unsicher nimmst du den von ihnen angebotenen Bogen und einen Pfeil.`n
        Die Amazone zielt und erreicht `\$".$amazone."`0 Punkte.
        Nun bist du an der Reihe und erzielst `^".$user."`0 Punkte.`n");
        if ($user > $amazone) {
            output('`@Du hast gewonnen und darfst deinen Weg weitergehen.`n
            Dieses kleine Turnier hat dir einiges an Erfahrung gebracht.`0');
            $exp = mt_rand($session['user']['level']10,$session['user']['level']20);
            $session['user']['experience'] += $exp;
        }
        else {
            output('`4Du hast verloren. Die Amazonen nutzen diese Gelegenheit, um mit ihrem Können anzugeben.`n
            Du verlierst die Zeit für 2 Waldkämpfe.`0');
            if ($session['user']['turns'] > 2) {
                $session['user']['turns'] -=2;
            }
            else {
                $session['user']['turns'] = 0;
            }
        }
    break;

    default:
        output('Ein Pfeil zischt an dir vorbei - Glück gehabt!`n`n
        `3"He, stehenbleiben!`n
        Na, was haben wir denn da?"`0`n
        Plötzlich bist du von einigen Frauen umringt, die scheinbar Amazonen sind.
        Und der Pfeil traf sicher nicht unbeabsichtigt daneben.`n`n
        `3"Du wagst es, einfach so in unserem Gebiet zu wandern?`n
        Das wird dich teuer zu stehen kommen... oder du versuchst dich gegen unsere beste
        Bogenschützin."`0');
        $session['user']['specialinc'] ='amazonen.php';
        addnav('Gold herausgeben','forest.php?op=gold');
        addnav('Herausforderung annehmen','forest.php?op=try');
}
?>