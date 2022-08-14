
<?php

#####################################
#                                   #
#            Osterspezial           #
#        Das traurige Häschen       #
#            für den  Wald          #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#           von Amon Chan           #
#      mit der Unterstützung von    #
#         Laserian und mfs          #
#          Text von Calamus         #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################

require_once "common.php";

page_header("Das traurige Häschen");

function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("sadbunny.jpg");
if (!isset($session)) exit();

switch($_GET['op']) {
        case 'suche':

        break;

        case 'weiter':
                if($_SESSION['tmp']['ei']>-1 && $_SESSION['tmp']['ei']<=10){
                        switch(mt_rand(1,5)){
                                case '1':
                                        $out='`@Nach kurzer Zeit siehst Du wieder etwas weisses am Wegrand im Gras blitzen und findest noch ein Ei,
                                                das Du ebenfalls einwickelst und in Deinen Beutel packst. Du setzt Deinen Weg fort.`n';
                                        $_SESSION['tmp']['ei']+=1;
                                        $session['user']['specialinc'] = 'sadbunny.php';
                                        addnav('Weiter');
                                        addnav('Weiter','forest.php?op=weiter');
                                break;
                                case '2':
                                        $out='`@Vor Dir siehst Du am Wegesrand ein kleines Häschen sitzen, dass fürchterlich weint. Vor dem Häschen
                                                steht ein Körbchen, in dem ein einzelnes Ei liegt. Du fragst das Häschen, was es denn hat. Schluchzend 
                                                erzählt Dir das Häschen `&"Ich sollte die Eier zum bemalen für Ostern holen. Ich hab mich so gefreut,
                                                dass ich das durfte, da bin ich wohl zu schnell gehüpft und nun habe ich alle Eier verloren"`@`n';
                                        if($_SESSION['tmp']['ei']==1) $out.='`@Du denkst an `^das Ei `@in Deinem Beutel. ';
                                        else $out.='`@Du denkst an die `^'.$_SESSION['tmp']['ei'].' Eier `@in Deinem Beutel. ';
                                        if($_SESSION['tmp']['ei']==1) $out.='`@Klar, Du hattest es zum Abendessen geplant, aber das kannst Du ';
                                        else $out.='`@Klar, Du hattest die zum Abendessen geplant, aber das kannst Du ';
                                        $out.='`@nun doch nicht übers Herz bringen. Also nimmst Du Deinen Beutel herunter und packst die gefundenen Eier
                                                aus und gibst sie dem Häschen. Mit jedem Ei strahlt das Häschen mehr und das macht Dich glücklich.`n`n';

                                        $charme=ceil($_SESSION['tmp']['ei']/2);
                                        if($charme<2) $charme=2;
                                        $out.='`@Du erhälst `^'.$charme.' Charmepunkte`@`n';
                                        $session['user']['charm']+=$charme;
                                        $_SESSION['tmp']['ei']=0;
                                        $session['user']['specialinc'] = '';
                                        addnews($session['user']['name'].' `@hat `&ein `^kleines `@Häschen `&sehr `^glücklich `@gemacht.');
                                        addnav('Zurück');
                                        addnav('Zurück zur Wiese','forest.php');
                                break;
                                case '3':
                                case '4':
                                case '5':
                                        $out='`@Nach kurzer Zeit siehst Du wieder etwas weisses am Wegrand im Gras blitzen und findest noch ein Ei,
                                                das Du ebenfalls einwickelst und in Deinen Beutel packst. Du setzt Deinen Weg fort.`n';
                                        $_SESSION['tmp']['ei']+=1;
                                        $session['user']['specialinc'] = 'sadbunny.php';
                                        addnav('Weiter');
                                        addnav('Weiter','forest.php?op=weiter');

                                break;
                        }

                } elseif ($_SESSION['tmp']['ei']>10) {
                        $out='`@Vor Dir siehst Du am Wegesrand ein kleines Häschen sitzen, dass fürchterlich weint. Vor dem Häschen
                                steht ein Körbchen, in dem ein einzelnes Ei liegt. Du fragst das Häschen, was es denn hat. Schluchzend 
                                erzählt Dir das Häschen `&"Ich sollte die Eier zum bemalen für Ostern holen. Ich hab mich so gefreut,
                                dass ich das durfte, da bin ich wohl zu schnell gehüpft und nun habe ich alle Eier verloren"`@`n';
                        $out.='`@Du denkst an die `^'.$_SESSION['tmp']['ei'].' Eier `@in Deinem Beutel. Klar, Du hattest die zum Abendessen geplant,
                                aber das kannst Du nun doch nicht übers Herz bringen. Also nimmst Du Deinen Beutel herunter und packst 
                                die gefundenen Eier aus und gibst sie dem Häschen. Mit jedem Ei strahlt das Häschen mehr und das macht 
                                Dich glücklich.`n`n';
                        $charme=ceil($_SESSION['tmp']['ei']/3);
                        if($charme<2) $charme=2;
                        $out.='`@Du erhälst `^'.$charme.' Charmepunkte`@`n';
                        $ession['user']['charm']+=$charme;
                        $_SESSION['tmp']['ei']=0;
                        $session['user']['specialinc'] = '';
                        addnews($session['user']['name'].' `@hat `&ein `^kleines `@Häschen `&sehr `^glücklich `@gemacht.');
                        addnav('Zurück');
                        addnav('Zurück zur Wiese','forest.php');
                } else {
                        $out='`@Vor Dir siehst Du am Wegesrand ein kleines Häschen sitzen, dass fürchterlich weint. Vor dem Häschen
                                steht ein Körbchen, in dem ein einzelnes Ei liegt. Du fragst das Häschen, was es denn hat. Schluchzend 
                                erzählt Dir das Häschen `&"Ich sollte die Eier zum bemalen für Ostern holen. Ich hab mich so gefreut,
                                dass ich das durfte, da bin ich wohl zu schnell gehüpft und nun habe ich alle Eier verloren"`@`n';
                        $out.='`@Hm - hättest Du nur die Eier aufgehoben, dann könntest Du dem kleinen Häschen wohl jetzt helfen, aber so? Irgendwie
                                fühlst Du  Dich nicht wirklich glücklich und ziehst beschämt weiter. `n`nDein Charme hat gelitten.`n`n';
                        $session['user']['charm']-=3;
                        addnews($session['user']['name'].' `@liess `&ein `^kleines `@Häschen `&weinend `^am `@Wegrand `&sitzen.');
                        addnav('Zurück');
                        addnav('Zurück zur Wiese','forest.php');
                        $_SESSION['tmp']['ei']=0;
                        $session['user']['specialinc'] = '';
                }
        break;
        
        case 'mitnehmen':
                switch ($_GET['act']) {
                        default:
                                $out='`@Du magst keine Eier, also kehrst du zum Wald zurück.`n';
                                addnav('Zurück');
                                addnav('Zurück zur Wiese','forest.php');
                                $_SESSION['tmp']['ei']=0;
                                $session['user']['specialinc'] = '';
                        break;
                        case 'weiter':
                                $out='`@Das Ei interessiert Dich nicht, also setzt Du einfach Deinen Weg fort.`n';
                                addnav('Weiter');
                                addnav('Weiter','forest.php?op=weiter');
                                $_SESSION['tmp']['ei']=-1;
                                $session['user']['specialinc'] = 'sadbunny.php';
                                break;
                        case 'ja':                
                                $out='`@Du nimmst das Ei, suchst aus Deinem Beutel ein Tuch, in das Du das Ei vorsichtig einwickelst und dann in Deinen
                                        Beutel packst. In Gedanken freust Du Dich schon auf den Abend und überlegst, ob Du das Ei kochen oder braten 
                                        willst. Dann setzt Du Deinen Weg fort.`n';
                                $_SESSION['tmp']['ei']=1;
                                $session['user']['specialinc'] = 'sadbunny.php';
                                addnav('Weiter');
                                addnav('Weiter','forest.php?op=weiter');
                        break;
                }
        break;
                
        default:
                $session['user']['specialinc'] = 'sadbunny.php';
                $out="`@Als Du wie immer sehr aufmerksam über die Wiese wanderst, siehst Du am Wegesrand im Gras etwas weiß leuchten.
                        Neugierig trittst Du näher und findest ein weisses Ei. Du siehst Dich um, kannst aber nirgendwo in der Nähe 
                        ein Nest erkennen, aus dem das Ei stammen könnte. Was wirst Du tun? Nimmst Du das Ei mit oder lässt Du das Ei 
                        liegen und setzt Deinen Weg fort?`n`n`0";
                addnav('Möglichkeiten');
                addnav('Ei mitnehmen','forest.php?op=mitnehmen&act=ja');
                addnav('Weiter gehen','forest.php?op=mitnehmen&act=weiter');
                addnav('Zurück zur Wiese','forest.php?op=mitnehmen');
        break;
}

output($out,true);

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

page_footer();
?>

