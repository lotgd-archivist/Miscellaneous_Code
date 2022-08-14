
<?php 
//°-------------------------°
//|    motwd_blacksheep.php   |
//|         Idee by         |
//|       Arina & Sari      |
//|        Script by        |
//|        xitachix         |
//|      mcitachi@web.de    |
//°-------------------------°
//http://logd.macjan.de/
//Kampf aus der killeraffen.php

/*Dieser Kommentar darf nicht entfernt werden!!!
08112007
Komplett überarbeitet, Abläufe optimiert und Fehler bereinigt für Mind of the White Dragon (MotWD - http://my-logd.com)
Von Adrian C. Gloth (-DoM / a.gloth@gmx.net)
Kontakt: motwd@my-logd.com
*/

if (function_exists('bild') === false){
    function bild($dn){
        //Von Adrian C. Gloth (-DoM - a.gloth@gmx.net / motwd@my-logd.com) 
        $pic = 'images/'.$dn;
        $pic_size = getimagesize($pic);
        rawoutput('<p style="text-align: center;">'.
                '<img alt="Vom schwarzen Schaf - Mind of the White Dragon" src="'.$pic.'" width="'.$pic_size[0].'" height="'.$pic_size[1].'" />'.
                '</p>'
        );
    }
}

$nav_back = 'forest.php';
$spi = 'motwd_blacksheep.php';

switch ($_GET['op']){
    case 'fight':
        bild('bad_sheep.jpg');
        $session['user']['specialinc'] = $spi;
        $battle = true;
    break;
    case 'run':
        if (e_rand() % 4 == 1){
            bild('no_sheep.jpg');
            $str_out = '`c`b`rDu konntest dem Schaf entkommen!`0`b`c`n';
            $session['user']['specialinc'] = '';
            $session['user']['specialmisc'] = '';
            unset($session['b_sheeps']);
        }else{
            bild('bad_sheep.jpg');
            output('`c`b`\(Das Schaf war schneller als du!`0`b`c');
            $battle = true;
        }
    break;
    case 'weiter':
        $str_out = '`7 Du hast ein'.($session['user']['specialmisc']>1?' weiteres':'').' Schäfchen besiegt';
        switch(e_rand(1,3)){
            case 1:
            case 2:
                if ($session['user']['specialmisc'] != $session['b_sheeps']){
                    bild('bad_sheep.jpg');
                    $str_out .= ', als auch schon das '.($session['user']['specialmisc'] + 1).'. angetrabt kommt`n';
                    $session['user']['specialinc'] = $spi;
                    addnav('Weiter');
                    addnav('Kämpfe','forest.php?op=kampf');
                }else{
                    $gems = e_rand(2,7);
                    $str_out .= '.`n`7Die '.$session['user']['specialmisc'].' kleinen Schäfchen liegen tot auf der Lichtung und du bekommst leichte Skrupel, '.
                            'solch süße Wesen umgebracht zu haben. Doch die Edelsteine, die du findest, wischen alle Skrupel weg und du sammelst sie eilig ein.`n`n'.
                            'Du bekommst `#'.$gems.' Edelsteine`7.';
                    addnews($session['user']['name'].'`7 hat '.$session['user']['specialmisc'].' kleine, süße Schäfchen umgebracht`0');
                    $session['user']['specialinc'] = '';
                    $session['user']['specialmisc'] = '';
                    $session['user']['gems'] += $gems;
                    unset($session['b_sheeps']);
                }
            break;
            case 3:
                bild('no_sheep.jpg');
                $gems = e_rand(2,7);
                $str_out .= '.`n`7Die '.$session['user']['specialmisc'].' kleinen Schäfchen liegen tot auf der Lichtung und du bekommst leichte Skrupel, '.
                        'solch süße Wesen umgebracht zu haben. Doch die Edelsteine, die du findest, wischen alle Skrupel weg und du sammelst sie eilig ein.`n`n'.
                        'Du bekommst `#'.$gems.' Edelsteine`7.';
                addnews($session['user']['name'].'`7 hat '.$session['user']['specialmisc'].' kleine, süße Schäfchen umgebracht`0');
                $session['user']['specialinc'] = '';
                $session['user']['specialmisc'] = '';
                $session['user']['gems'] += $gems;
                unset($session['b_sheeps']);
        }
    break;
    case 'kampf':
        bild('bad_sheep.jpg');
        output('`7Du entdeckst den Gegner `%Killerschaf`7, der sich mit seiner Waffe `%Reißzähne und Riesenkrallen`7 auf dich stürzt!');
        $r_buff = ($session['user']['specialmisc'] / 10);
        $badguy = array('creaturename'=>'`%Killerschaf`0',
                    'creaturelevel'=>$session['user']['level'],
                    'creatureweapon'=>'`%Reißzähne und Riesenkrallen',
                    'creatureattack'=>($session['user']['attack'] * (0.8 + $r_buff)),
                    'creaturedefense'=>($session['user']['defence'] * (0.8 + $r_buff)),
                    'creaturehealth'=>round($session['user']['maxhitpoints'] * (0.8 + $r_buff)),
                    'diddamage'=>0
        );
        $session['user']['badguy'] = createstring($badguy);
        $battle = true;
    break;
    case 'schmusen':
        bild('bad_sheep.jpg');
        $str_out = '`7Du setzt dich zu den Schäfchen auf die Wiese. Plötzlich verwandeln sich die kleinen Schäfchen und ihre Kulleraugen '.
                'verwandeln sich in kleine Feuerbälle. Ihre Zähne werden länger und schärfer und sie stürzen sich mit ihren Reißzähnen auf dich!';
        $session['user']['specialinc'] = $spi;
        addnav('Weiter');
        addnav('Kämpfe',$nav_back.'?op=kampf');
    break;
    case 'zurueck':
        bild('good1_sheep.jpg');
        $str_out = '`n`n`7Du möchtest keine Zeit verschwenden und gehst zurück in den Wald. Seufzend guckst du dich noch mal nach den süßen Schäfchen um, '.
                'doch du bekräftigst dich selbst in der Entscheidung und gehst weiter in den Wald.';
        $session['user']['specialinc'] = '';
        $session['user']['specialmisc'] = '';
        unset($session['b_sheeps']);
    break;
    default:
        bild('good_sheep.jpg');
        $str_out = '`c`b`8Die Schäfchenlichtung.`b`c`n`n'.
                '`7Plötzlich findest du dich auf einer Lichtung wieder. Überall sind kleine süsse Schäfchen, die dich mit ihren großen Kulleraugen anschauen. '.
                'Du könntest jetzt auf der Wiese bleiben, um mit den Schäfchen zu schmusen, oder wieder gehen.';
        $session['user']['specialinc'] = $spi;
        $session['user']['specialmisc'] = 0;
        $session['b_sheeps'] = e_rand(5,15);
        addnav('Aktionen');
        addnav('Mit den Schäfchen schmusen',$nav_back.'?op=schmusen');
        addnav('Sonstiges');
        addnav('Zurück in den Wald',$nav_back.'?op=zurueck');
}

if ($battle){
    include('battle.php');
    $session['user']['specialinc'] = $spi;
        if ($victory){
        $str_out = '`n`n`7Du konntest nach einem schweren Kampf das Schaf besiegen!`n';
        if ((e_rand() % 5) == 1){
            $gems = rand(1,3);
            $gold = e_rand(($session['user']['level'] * 10),($session['user']['level'] * 20));
            $str_out .= 'Du findest `#'.$gems.' Edelstein'.($gems==1?'':'e').'`7 und `^'.$gold.
                    ' Goldstücke`7 bei der Leiche des Schafes.`n';
        }
        $exp = round($session['user']['experience'] * 0.005);
        $badguy = array();
        $session['user']['badguy'] = '';
        $session['user']['experience'] += $exp;
        $session['user']['gold'] += $gold;
        $session['user']['gems'] += $gems;
        $session['user']['specialmisc'] += 1;
        $str_out .= 'Durch diesen Kampf steigt Deine Erfahrung um '.$exp.' Punkte.';
        addnav('Aktionen');
        addnav('Weiter',$nav_back.'?op=weiter');
        }elseif ($defeat){
        $str_out = '`n`n`7Während du deinen letzten Atemzug ziehst, trappelt ein Schaf auf deine Brust '.
                'und grinst dich frech an.`n'.
                '`%"Der Schein trügt!"`7.`n'.
                'Du kannst morgen wieder kämpfen!';
        addnews($session['user']['name'].'`7 war zu blöd, um es mit Kuschelschäfchen aufzunehmen!`0');
        $badguy = array();
        $session['user']['badguy'] = '';
        $session['user']['alive'] = 0;
        $session['user']['hitpoints'] = 0;
        $session['user']['gems'] = round($session['user']['gems'] / 2);
        $session['user']['gold'] = 0;
        $session['user']['experience'] = round($session['user']['experience'] * 0.95);
        $session['user']['specialinc'] = '';
        $session['user']['specialmisc'] = '';
        unset($session['b_sheeps']);
        addnav('Du bist tot');
        addnav('Zu den News','news.php');
        }else{
        fightnav(true,true);
        }
}

output($str_out.'`n`n');
//Folgender Rawoutput, darf nicht entfernt werden!!!
rawoutput('<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p style="text-align: center;">'.
        '<a title="Komm und werde ein Teil, dieser fantastischen Welt!" target="_blank" href="http://my-logd.com/">'.
        '<img alt="Komm und werde ein Teil, dieser fantastischen Welt!" src="http://my-logd.com/c_motwd.gif" width="450" height="50" style="border-width: 0px;" /></a>'.
        '</p>'
);
?>

