<?php

/**
 * @file _DIR_/special_necklace.php
 * @author MySQL [User from anpera.net]
 * @desc Teil 1 der "Besonderen Item"-Speciale. Einziger veröffentlichter Teil.
**/

if( !isset($session) ) {

        exit();

}

//define('STADTTOR', false);

switch( $session['user']['item'] ) {

        case 'necklace_1':
        case 'necklace_2':
        case 'necklace_3':
        case 'necklace_4':

                $session['user']['specialinc'] = '';
                output('`@Du hörst ein knacken im Gebüsch. Als du nachsiehst, was dieses Knacken verursacht hat, findest du nichts. Enttäuscht gehst du zurück.');

        break;

        default:

                switch( $_GET['op'] ) {

                        case '':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Obwohl dir die anderen Dorfbewohner sagten, dass der hinterste Teil des Waldes sehr gefährlich ist, steuerst du ihn trotztdem an. Du hoffst hier etwas zu '.
                                           'finden, was kein anderer besitzt. Mit jedem Schritt den du weiter in den hintersten Teil des Waldes gehst, wird es dunkler und kälter. Schon bald befindest '.
                                           'du dich in einer dichten Nebelwand wieder. Du kannst kaum 5 Meter nach vorn sehen, weißt nicht was dich hinter der nächsten Ecke erwartet.`n`n'.
                                           'Schon nach wenigen Minuten laufen dir kalte Schauer über den Rücken und du fragst dich, ob es richtig gewesen ist hierher zu kommen.`nWas wirst du tun?`0');
                                addnav('Aktionen');
                                addnav('Weiter gehen','forest.php?op=further');
                                addnav('Das Weite suchen','forest.php?op=run_away');

                        break;

                        case 'run_away':

                                output('`@Du kriegst es mit der Angst zutun und rennst weg. Soll sich doch jemand anderes in diesem Wald zu Tode ängstigen!`0`n`n`$Du verlierst 2 Waldkämpfe!`0');
                                $session['user']['turns'] -= 2;
                                $session['user']['specialinc'] = '';

                        break;

                        case 'further':

                                $session['user']['specialinc'] = 'special_necklace.php';

                                output('`@');

                                switch( true ) {

                                        case ($session['user']['dragonkills'] <= 19):

                                                output('Du bist zwar noch kein starker Krieger, aber dein Mut ist größer als alles andere. Du strebst nach Stärke und Ruhm, deswegen setzt du deinen Weg fort. '.
                                                           'Vorsichtig durchquerst du die nächste Passage und verharrst einen kurzen Moment auf der Stelle an der du gerade stehst. Lauschend gehst du in die Hocke '.
                                                           'und ziehst deine Waffe, glaubst du doch gerade etwas knacken gehört zu haben. Gerade als du vorsichtig um die Ecke siehst, atmest du erleichtert aus. '.
                                                           'Dort liegt einen Hasen, der gerade über einen Ast gestolpert ist.`0`n`n`$Durch das verharren hast du 2 Waldkämpfe verloren.`0');
                                                $session['user']['turns'] -= 2;

                                                switch( mt_rand(1,20) % 5 ) {

                                                        case 0:

                                                                $further = '1&aktion=0';

                                                        break;

                                                        default:

                                                                $further = '1&aktion=1';

                                                }

                                                addnav('Aktionen');
                                                addnav('Weiter','forest.php?op=further'.$further);

                                        break;

                                        case ($session['user']['dragonkills'] >= 20 && $session['user']['dragonkills'] <= 39):

                                                output('Da du bereits '.$session['user']['dragonkills'].' Phoenix erschlagen konntest, hast du deine Stärke schon das ein oder andere Mal bewiesen. '.
                                                           'Doch du strebst nach noch mehr Stärke und Ruhm, deswegen setzt du deinen Weg fort und trotzt allen Gefahren. Mit schnellen Schritten setzt du deinen '.
                                                           'Weg durch den dichten Nebel fort. Bei jedem Geräusch welches du nicht verursacht hast, zuckst du ein wenig zusammen. Deine Hand umschließt den Griff '.
                                                           'deiner Waffe, jederzeit bereit um sie für einen Kampf empor zu ziehen. Du gelangst auf eine Lichtung, an der der Weg in 2 Richtungen weiter führt.`n`n'.
                                                           'Wo willst du hergehen?');
                                                addnav('Aktionen');
                                                addnav('Nach Norden','forest.php?op=go_north');
                                                addnav('Nach Osten','forest.php?op=go_east');
                                                addnav('Lieber zurück gehen','forest.php?op=go_south');

                                        break;

                                        case ($session['user']['dragonkills'] >= 40 && $session['user']['dragonkills'] <= 59):

                                                output('Schnell überwindest du deine Ängste und durchquerst diesen Abschnitt des Waldes. Du weißt, dass du bereits sehr stark bist und hast deshalb keine Ansgt '.
                                                           'deinen Weg fortzusetzen. Gemütlich gehst du in die Hocke und ziehst einen Stofffetzen aus deinem Inventar. Du suchst die Bäume auf schwache Stellen ab '.
                                                           'findest schließlich einen Baum, der schon einen Riss in der Rinde hat. Schnell gehst du hinüber, bindest den Stofffetzen an einen dickeren Ast und '.
                                                           'tränkst ihn in Baumharz. Als das Harz etwas getrocknet ist, schlägst du zwei Feuersteine übereinander bis der Stofffetzen brennt.`n`n'.
                                                           'Nun siehst du wenigstens wo der Weg herführt. Lange läufst du, bis eine Kreuzung erreicht ist.`n`nWo willst du hergehen?');
                                                addnav('Aktionen');
                                                addnav('Nach Norden','forest.php?op=go_north');
                                                addnav('Nach Osten','forest.php?op=go_east');
                                                addnav('Nach Westen','forest.php?op=go_west');
                                                addnav('Lieber zurück gehen','forest.php?op=go_south');

                                        break;

                                        case ($session['user']['dragonkills'] >= 60) :

                                                output('Grinsend beschreitest du deinen Weg weiter. Du weißt, dass dich so schnell nichts aus den Latschen hauen kann, da du schon mehr als 50 Drachen töten '.
                                                           'konntest. Du strebst zwar nach noch mehr Stärke und Ruhm, doch deine Neugier ist größer. Grinsend gehst du durch den Nebel, wissend das dich so schnell '.
                                                           'nichts umhauen kann. Das knacken welches du aus den Gebüschen hörst interessiert dich nicht. Selbst als eine sehr mysteriöse Kreatur deinen Weg kreuzt, '.
                                                           'zuckst du mit keiner Wimper. Nach ca. einer halben Stunde erreichst du eine Kreuzung. Auf dieser Kreuung führt ein Weg nach Norden, einer nach Osten und '.
                                                           'einer nach Westen. Allerdings gibt es noch in der Mitte der Kreuzung eine Treppe die in die Erde führt.`n`nWo willst du hergehen?');
                                                addnav('Aktionen');
                                                addnav('Nach Norden','forest.php?op=go_north');
                                                addnav('Nach Osten','forest.php?op=go_east');
                                                addnav('Nach Westen','forest.php?op=go_west');
                                                addnav('Treppe hinunter gehen','forest.php?op=stair');
                                                addnav('Lieber zurück gehen','forest.php?op=go_south');

                                        break;

                                }

                        break;

                        case 'stair':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du entschließt dich dazu die Treppe hinunter zu gehen und atmest tief ein, bevor du in die Dunkelheit hinabsteigst. Kurz nachdem du in dem Gang unter der '.
                                           'Erde angekommen bist, ertönt ein lautes Zwischen und an den Wänden des Ganges flackern hunderte von Fackeln auf. Voller Ehrfurcht durchquerst du den Gang '.
                                           'und wampnest dich geistig schon für einen Kampf.`n`n');

                                switch( mt_rand(1,50) % 10 ) {

                                        case 0:

                                                output('Ein lautes Grollen ertönt und geht dir durch Mark und Gebein. Kurz darauf, öffnet sich der Boden unter deinen Füßen und du fällst in einen tiefen '.
                                                           'Schacht.`n`n'.
                                                           'Du fällst und fällst und fällst und fällst und fällst....`n`nEs scheint als würdest du niemals den Boden erreichen.`n`n'.
                                                           '`$Du bist direkt zu Ramius in die Hölle gefallen und bist tot. Du verlierst als dein Gold.`0');
                                                addnews($session['user']['name'].' `$viel in einen dunklen Schacht. '.($session['user']['sex']==0?'Er':'Sie').' wurde seither nicht mehr gesehen!');
                                                $session['user']['specialinc'] = '';
                                                $session['user']['alive'] = 0;
                                                $session['user']['hitpoints'] = 0;
                                                $session['user']['gold'] = 0;
                                                addnav('Aktionen');
                                                addnav('Zu den News','news.php');

                                        break;

                                        default:

                                                output('Nach etwas längerer Zeit erreichst du eine Tür durch welche du hindurchgehen kannst.');
                                                addnav('Aktionen');
                                                addnav('Weiter gehen','forest.php?op=door');
                                                addnav('Umkehren','forest.php?op=go');

                                }

                        break;

                        case 'go':

                                $session['user']['specialinc'] = '';
                                redirect('forest.php');

                        break;

                        case 'door':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Gerade als du durch die Tür hindurch gegangen bist, wirst Du schon angegriffen.`0');
                                addnav('Aktionen');
                                addnav('Auf in den Kampf','forest.php?op=fight');
                                $badguy = array(
                                                                'creaturename' => '`^Meister der Uralten`0',
                                                                'creaturelevel' => $session['user']['level']+10,
                                                                'creatureweapon' => '`^Zweischneidigesschwert`0',
                                                                'creatureattack' => $session['user']['attack']+6,
                                                                'creaturedefense' => $session['user']['defence']+5,
                                                                'creaturehealth' => round($session['user']['maxhitpoints']*3,0),
                                                                'diddamage' => 0,
                                                                'id' => 7);
                                $session['user']['badguy'] = createstring($badguy);


                        break;

                        case 'take4':

                                $session['user']['specialinc'] = '';
                                output('`@Schnell nimmst du dir die Kette und verschwindest.`n`n'.
                                           '`^Du erhälst die Halskette des Waldes <img src="images/forest/necklace_4.png">.`0',true);
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');
                                $session['user']['item_health'] = 500;
                                $session['user']['item'] = 'necklace_4';
                                addnews($session['user']['name'].' `@fand die Halskette des Waldes im Schrein des Südens.`0');

                        break;

                        case 'go_south':

                                $session['user']['specialinc'] = '';
                                output('`@Die Kreuzung ist für dich mehr als uninteressant. Deshalb machst du kehrt und gehst zurück in den Wald.');
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');

                        break;

                        case 'go_west':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du entschließt dich dazu den Weg Richtung Westen zu nehmen. Es kommt dir vor wie eine halbe Ewigkeit, die du auf diesem Weg verbringst. '.
                                           'Nach geraumer Zeit wird die Luft etwas kälter, salziger und der Nebel verschwindet und gibt den Blick auf das Meer und seine Klippen frei.`n`n'.
                                           'Um den Weg weiter zu passieren, musst du dich an eine Felswand quetschen und langsam einen Fuß vor den anderen setzen. Eine zeitlang funktioniert das auch '.
                                           'ganz gut. Doch aufeinmal fängt der Fels an zu beben und es fallen mehrere Brocken hinab, die direkt neben dir auf dem schmalen Pfad treffen und ihn zerstören. '.
                                           'Es gibt nun keinen Weg mehr zurück und keinen nach vorn.`n`nWillst du in den Tod springen oder lieber versuchen nach oben zu klettern?`0');
                                addnav('Aktionen');
                                addnav('Hinauf klettern','forest.php?op=climb');
                                addnav('Runter springen','forest.php?op=jump');

                        break;

                        case 'jump':

                                switch( mt_rand(1,20) % 5 ) {

                                        case 0:

                                                $session['user']['specialinc'] = '';
                                                output('`@Du hast Glück! Du bist um Haaresbreite an den Spitzen im Wasser vorbei gekommenkommen und kannst nun in den Wald schwimmen!`0');
                                                addnav('Aktionen');
                                                addnav('In den Wald','forest.php');

                                        break;

                                        default:

                                                output('`$Du hattest leider kein Glück und wurdest von den Felsen im Wasser aufgespießt.');
                                                addnews($session['user']['name'].'`$ sprang aus lauter Verzweiflung in den Tod.`0');
                                                $session['user']['specialinc'] = '';
                                                $session['user']['alive'] = 0;
                                                $session['user']['hitpoints'] = 0;
                                                $session['user']['gold'] = 0;
                                                addnav('Aktionen');
                                                addnav('Zu den News','news.php');

                                }

                        break;

                        case 'climb':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Langsam erklimmst du die Felswand und wunderst dich, dass der Aufstieg so einfach ist. Kurz bevor du das Ende der Felswand erreicht hast, trägt dich ein '.
                                           'Windstoss der von unten kommt 10 Meter in die Höhe. Schnell packst du den letzten Felsvorsprung bevor du wegen dem Windstoss in die Tiefe fällst. Geschafft.`n`n'.
                                           'Oben angekommen siehst du direkt einen Altar über dem eine Halskette schwebt. Aus Erzählungen weißt du, dass dies die Halskette der Verteidigung ist.`n`n'.
                                           'Direkt neben dem Altar kannst du einen der Uralten sehen, der dich schon längst mit seinen finsteren Augen anglotzt.`n'.
                                           'Aus vergangenen Begegnungen mit den Uralten weißt du, dass du nun gegen diesen hier kämpfen musst.`0');
                                addnav('Aktionen');
                                addnav('Auf in den Kampf','forest.php?op=fight');
                                $badguy = array(
                                                                'creaturename' => '`^Uralter des Westens`0',
                                                                'creaturelevel' => $session['user']['level']+2,
                                                                'creatureweapon' => '`^Zweischneidigesschwert`0',
                                                                'creatureattack' => $session['user']['attack']+2,
                                                                'creaturedefense' => $session['user']['defence']+1,
                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                'diddamage' => 0,
                                                                'id' => 6);
                                $session['user']['badguy'] = createstring($badguy);

                        break;

                        case 'take3':

                                $session['user']['specialinc'] = '';
                                output('`@Schnell nimmst du dir die Kette und verschwindest.`n`n'.
                                           '`^Du erhälst die Halskette der Verteidigung <img src="images/forest/necklace_3.png">.`0',true);
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');
                                $session['user']['item_health'] = 500;
                                $session['user']['item'] = 'necklace_3';
                                addnews($session['user']['name'].' `@fand die Halskette der Verteidigung im Schrein des Westens.`0');

                        break;

                        case 'go_north':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Vorsichtig durchquerst du die nächste Passage und verharrst einen kurzen Moment auf der Stelle an der du gerade stehst. Lauschend gehst du in die Hocke '.
                                           'und ziehst deine Waffe, glaubst du doch gerade etwas knacken gehört zu haben. Als du vorsichtig um die Ecke siehst, atmest du erleichtert aus. Du '.
                                           'siehst einen Hasen, der gerade über einen Ast gestolpert ist.`0`n`n`$Durch das verharren hast du 2 Waldkämpfe verloren.`0');

                                switch( mt_rand(1,20) % 5 ) {

                                        case 0:

                                                $further = '1&aktion=0';

                                        break;

                                        default:

                                                $further = '1&aktion=1';

                                }

                                addnav('Aktionen');
                                addnav('Weiter','forest.php?op=further'.$further);

                        break;

                        case 'go_east':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du wählst den Weg, der dich nach Osten führt. ');

                                switch( mt_rand(1,50) % 6 ) {

                                        case 0:

                                                output('Der Weg führt dich an vielen gefährlichen Stellen vorbei. ');

                                                switch( mt_rand(1,20) % 5 ) {

                                                        case 0:

                                                                output('Gerade führt er dich über einen Engpass über einen Berg. ');

                                                                switch( mt_rand(1,2) ) {

                                                                        case 1:

                                                                                output('Kurz bevor du das Ende des Passes erreicht hast, weht dich ein starker Windstoss vom Pass und du fällst in die Tiefe.`n`n'.
                                                                                           '`$Du bist tot! Verlierst allerdings nur dein Gold!`0');
                                                                                addnews($session['user']['name'].'`$ viel von einem Berg und stürzte in die Tiefe.`0');
                                                                                $session['user']['specialinc'] = '';
                                                                                $session['user']['alive'] = 0;
                                                                                $session['user']['hitpoints'] = 0;
                                                                                $session['user']['gold'] = 0;
                                                                                addnav('Aktionen');
                                                                                addnav('Zu den News','news.php');

                                                                        break;

                                                                        case 2:

                                                                                output('Du hast Glück und konntest den Pass schnell passieren. ');

                                                                        break;

                                                                }

                                                        break;

                                                }

                                        break;

                                }

                                if( $session['user']['alive'] ) {

                                        output('Nach 500 Meter Fußmarsch erreichst du einen Wasserfall der dir den Weg versperrt. Du glaubst zu erkennen, dass hinter dem Wasserfall eine Art Durchgang '.
                                                   'existiert.`n`nWillst du es wagen durch den Wasserfall zu gehen oder lieber zurück in den Wald?');
                                        addnav('Aktionen');
                                        addnav('Hindurch gehen','forest.php?op=waterfall');
                                        addnav('Lieber gehen','forest.php?op=go_waterfall');

                                }

                        break;

                        case 'go_waterfall':

                                $session['user']['specialinc'] = '';
                                output('`@Du entschließt dich dazu wieder zurück in den Wald zu gehen.`n`n`$Du verlierst 5 Waldkämpfe.`0');
                                $session['user']['turns'] -= 5;
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');

                        break;

                        case 'waterfall':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du setzt zu einem Sprung an und springst durch die Wasserwand des Wasserfalls. Stolpernd und durchnässt kommst du auf der anderen Seite zum stehen, du '.
                                           'befindest dich nun in einer großen Höhle. Mit deinen Augen suchst du die Höhle nach irgendwelchen Zeichen für einen weiteren Weg ab. Gerade als du wieder '.
                                           'umkehren wolltest, kannst du in der hintersten Ecke des Höhle etwas funkeln sehen. Leise gehst du auf dieses funkeln zu und kannst dann einen Wasserelementar '.
                                           'erkennen. Als dich der Wasserelementar sieht, spricht er zu dir:`n`n'.
                                           '"`^Halt keinen Schritt weiter. Ich bin der Wächter des Schreins der Uralten des Ostens. Bevor du diese Höhle weiter passieren darfst, musst du mich in '.
                                           'einem Kampf bezwingen. Willst du nicht gegen mich kämpfen, drehe um und verlasse die Höhle wieder.`@"`n`nWas willst du tun?');
                                addnav('Aktionen');
                                addnav('Auf in den Kampf','forest.php?op=fight');
                                addnav('Höhle verlassen','forest.php?op=go_water');
                                $badguy = array(
                                                                'creaturename' => '`9Wasserelementar`0',
                                                                'creaturelevel' => $session['user']['level']+2,
                                                                'creatureweapon' => '`9Wasserschwert`0',
                                                                'creatureattack' => $session['user']['attack']+2,
                                                                'creaturedefense' => $session['user']['defence']+1,
                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                'diddamage' => 0,
                                                                'id' => 4);
                                $session['user']['badguy'] = createstring($badguy);

                        break;

                        case 'go_water':

                                $session['user']['specialinc'] = '';
                                output('`@Du drehst dich um und gehst zurück in den Wald.`n`n`$Durch diesen Ausflug verlierst du 5 Waldkämpfe.`0');
                                $session['user']['turns'] -= 5;
                                $session['user']['badguy'] = '';
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');


                        break;

                        case 'shrine_east':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Vorsichtig betrittst du die nächste größere Höhle und siehst dich um. In der Mitte der Höhle kannst du einen Altar erkennen, über dem eine Kette zu schweben '.
                                           'scheint. Mit langsamen Schritten gehst du auf die Kette zu.`n`nWas willst du tun?');
                                addnav('Aktionen');
                                addnav('Die Kette nehmen','forest.php?op=take');
                                addnav('Höhle verlassen','forest.php?op=go_water');


                        break;

                        case 'take':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du streckst deine Hand aus und greifst nach der Kette. ');

                                switch( mt_rand(1,20) % 2 ) {

                                        case 0:

                                                output('Du lächelst, als du die Kette in deinen Händen hältst. "`&Puuuh, das ging ja einfach.`@", denkst du dir. ');

                                                switch( mt_rand(1,2) ) {

                                                        case 1:

                                                                $session['user']['specialinc'] = '';
                                                                output('Schnell legst du dir die Kette um den Hals und rennst zurück in den Wald.`n`n'.
                                                                           '`^Du erhälst die Halskette der Stärke <img src="images/forest/necklace_2.png">.`0',true);
                                                                addnews($session['user']['name'].' `@fand die Halskette der Stärke im Schrein des Ostens.`0');
                                                                $session['user']['item'] = 'necklace_2';
                                                                $session['user']['item_health'] = 500;
                                                                addnav('Aktionen');
                                                                addnav('In den Wald','forest.php');

                                                        break;

                                                        case 2:

                                                                output('`n`nEin ohrenbetäubendes Grollen ertönt, kurz darauf fängt die Höhle an zu beben. Gerade als du wieder zurück zum Eingang rennen willst, '.
                                                                           'musst du mit Schrecken feststellen das der Eingang wieder versiegelt ist.`n`nJetzt stehst du hier, allein.. ganz allein, wartend auf deinen Tod.'.
                                                                           '`n`nNach kurzer Zeit fallen schon die ersten größeren Brocken von der Decke hinab. Bald schon, liegst du unter den Trümmern.`n`n'.
                                                                           '`$Du bist tot! Du verlierst all dein Gold.`0');
                                                                addnews($session['user']['name'].'`$ starb in einer Höhle. Allein.. ganz allein!');
                                                                $session['user']['specialinc'] = '';
                                                                $session['user']['alive'] = 0;
                                                                $session['user']['hitpoints'] = 0;
                                                                $session['user']['gold'] = 0;
                                                                addnav('Aktionen');
                                                                addnav('Zu den News','news.php');

                                                        break;

                                                }

                                        break;

                                        default:

                                                output('Doch bevor deine Hände die Kette erreichen, wirst du mit einem Ruck von den Füßen gerissen. Als du dich umsiehst, sieht du eine Frau, die ein Schwert '.
                                                           'in den Händen hällt und sich für den Kampf bereit macht.`n');
                                                addnav('Aktionen');
                                                addnav('Auf in den Kampf','forest.php?op=fight');
                                                $badguy = array(
                                                                                'creaturename' => '`^Uralte des Ostens`0',
                                                                                'creaturelevel' => $session['user']['level']+2,
                                                                                'creatureweapon' => '`^Zweischneidigesschwert`0',
                                                                                'creatureattack' => $session['user']['attack']+2,
                                                                                'creaturedefense' => $session['user']['defence']+1,
                                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                                'diddamage' => 0,
                                                                                'id' => 5);
                                                $session['user']['badguy'] = createstring($badguy);

                                }

                        break;

                        case 'take2':

                                $session['user']['specialinc'] = '';
                                output('`@Schnell nimmst du dir die Kette und verschwindest.`n`n'.
                                           '`^Du erhälst die Halskette der Stärke <img src="images/forest/necklace_2.png">.`0',true);
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');
                                $session['user']['item_health'] = 500;
                                $session['user']['item'] = 'necklace_2';
                                addnews($session['user']['name'].' `@fand die Halskette der Stärke im Schrein des Ostens.`0');

                        break;

                        case 'further1':

                                switch( $_GET['aktion'] ) {

                                        case 0:

                                                output('`@Gerade als du deinen Weg weiter beschreiten willst, greift dich das `isüße`i Häschen an. Leider reagierst du zu spät und stirbst.`0`n`n'.
                                                           '`$Du bist tot. Du verlierst all dein Gold und 10% deiner Erfahrung.`0');
                                                addnews($session['user']['name'].'`$ starb Mutterseelenallein in der hintersten Ecke des Waldes.`0');
                                                $session['user']['specialinc'] = '';
                                                $session['user']['alive'] = 0;
                                                $session['user']['hitpoints'] = 0;
                                                $session['user']['gold'] = 0;
                                                $session['user']['experience'] -= $session['user']['experience']*.10;
                                                addnav('Aktionen');
                                                addnav('Tägliche News','news.php');

                                        break;

                                        case 1:

                                                $session['user']['specialinc'] = 'special_necklace.php';
                                                output('`@Schnell richtest du dich wieder auf und gehst zu dem Hasen hinüber.`n`nWas wirst Du tun?`0');
                                                addnav('Aktionen');
                                                addnav('Auf die Beine helfen','forest.php?op=help');
                                                addnav('Liegen lassen','forest.php?op=ignore');
                                                addnav('Angreifen','forest.php?op=fight1');

                                        break;

                                }

                        break;

                        case 'ignore':

                                $session['user']['specialinc'] = '';
                                output('`@Du entschließt dich dazu den Hasen liegen zu lassen und gehst an ihm vorbei. Lange läufst du nun durch den dichten Nebel und merkst, dass du dich '.
                                           'verlaufen hast. Schreist um Hilfe, doch niemand hört dich. Du bist allein.. ganz allein.`n`nDu kriegst es mit der Angst zu tun und fängst an zu '.
                                           'rennen. Du rennst so schnell du kannst. Nach gefühlten 3 Stunden erreichst du endlich einen Abschnitt des Waldes, den du kennst.`0`n`n'.
                                           '`$Du verlierst 5 Waldkämpfe!`0');
                                $session['user']['turns'] -= 5;
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');

                        break;

                        case 'help':

                                $session['user']['specialinc'] = '';
                                $rand = mt_rand(1,10);
                                output('`@Du hilfst dem kleinen Hasen auf die Beine und streichelst ihm kurz das Fell. Gerade als du dich abwenden willst, kannst du etwas im Gebüsch funkeln sehen.'.
                                           'Beim genauem hinsehen, kannst du einen Sack voller Edelsteine erkennen.`n`n`^Du erhälst '.$rand.' Edelsteine.`0');
                                $session['user']['gems'] += $rand;
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');

                        break;

                        case 'fight1':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Du ziehst deine Waffe und machst dich für den Kampf bereit.`0`n');
                                addnav('Aktionen');
                                addnav('Auf in den Kampf!','forest.php?op=fight');
                                $badguy = array(
                                                                'creaturename' => '`^`isüßes`i Häschen`0',
                                                                'creaturelevel' => $session['user']['level']+2,
                                                                'creatureweapon' => '`^sehr scharfe Zähne`0',
                                                                'creatureattack' => $session['user']['attack']+1,
                                                                'creaturedefense' => $session['user']['defence']-1,
                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                'diddamage' => 0,
                                                                'id' => 1);
                                $session['user']['badguy'] = createstring($badguy);

                        break;

                        case 'north':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                //js_process('loadpicture','forest/shrine_north.jpg','Debug: %s %s ',System::load('cache'),Cache::get('get->js_pricess_pictures'));
                                output('`@Schnellen Schrittes gehst du durch den dichten Nebel in Richtung Norden. Es kommt dir vor als würdest du schon eine halbe '.
                                           'Ewigkeit durch diesen dunklen Wald laufen bis du endlich auf einer hell erleuchteten Lichtung ankommst. Vor dir liegt nun ein großer See, über den eine Brücke '.
                                           'hinüber zu einem großen Gebäude führt. Vorsichtig betrittst du die Brücke und überquerst sie. Zwischendurch wird dir etwas mulmig, da die Brücke unter deinen '.
                                           'Füßen ächzt und knackt. Sie scheint morsch zu sein, deshalb fängst du an zu rennen. Gerade als du die andere Seite des See\'s erreicht hast, brechen die '.
                                           'Bretter der Brücke in der Mitte durch und versinken im See. Glück gehabt, denkst du dir.`n`nDa der Rückweg nun schier unmöglich erscheint, gehst du auf das '.
                                           'Gebäude zu. In die erste Stufe ist folgende Inschrift eingemeiselt worden:`n`n`c`&Schrein des Nordens, Hauptsitz der Uralten des Lebens.`@`c`n'.
                                           'Vorsichtig erklimmst du die Stufen die hinauf zur Eingangstür des Schreins führen. Packst den Türknauf mit beiden Händen und ziehst mit voller Stärke daran. '.
                                           'Als die Tür unter lautem ächzen aufspringt, wirst du von einem kühlem Windstoss umgeweht. Rappelst dich schnell wieder auf und betrittst das Innere des '.
                                           'Schreins.`n`n`0');
                                addnav('Aktionen');
                                addnav('Weiter','forest.php?op=shrine_north');

                        break;

                        case 'shrine_north':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Gerade als du das Innere des Schreins betreten hast, wirst du von einer Kreatur, eingehüllt in einem dunklen Mantel, von den Füßen geworfen. Kurz darauf spürst'.
                                           ' du eine Klinge an deinem Hals.`n`n'.
                                           '"`^Was willst du hier, Fremd'.($session['user']['sex']==0?'er':'e').'? Es ist dir nicht gestattet diese heiligen Hallen zu betreten! Verschwinde!`@"`n`n'.
                                           'Du musst dich kurz sammeln und stammelst dann, dass du den Hasen besiegen konntest und er dir sagte, geh Richtung Norden in den Schrein der Uralten. '.
                                           'Die Kreatur nimmt dir ihre Klinge vom Hals und nimmt ihre Kaputze ab. Zum Vorschrein kommt eine Dame mittleren Alters.`n`n'.
                                           '"`^So, ihr konntet also unseren Wächter bezwingen? Sicherlich hat er euch auch gesagt, dass ihr noch eine Prüfung absolvieren müsst, sobald ihr den Schrein '.
                                           'erreicht habt. Richtig?`@"`n`n'.
                                           'Du nickst der Dame zu.`n`n'.
                                           '"`^Nun gut. Steh auf und folge mir! Ich werde dir nun zeigen, was deine Prüfung sein wird.`@"`n`n'.
                                           'Du richtest dich auf und folgst der Frau. Sie führt dich hinüber zu einer großen Tür aus Marmor welche sie mit einem Zauberspruch öffnet. '.
                                           'Die Frau deutet dir mit einer Handbewegung durch die Tür zu gehen.');
                                addnav('Aktionen');
                                addnav('Gehe hinein','forest.php?op=enter1');

                        break;

                        case 'enter1':

                                $session['user']['specialinc'] = 'special_necklace.php';
                                output('`@Schnellen Schrittes gehst du durch die Tür hinüber in den Raum, dicht gefolgt von der Frau. Sie spricht abermals einen Zauberspruch und in der Mitte des '.
                                           'Raums erscheint eine mysteriöse Kreatur.`n`n');

                                switch( mt_rand(1,2) ) {

                                        case 1:

                                                output('Als du genauer hinsiehst, ist zu erkennen das es ein Skelettkrieger von Ramius ist. Die Frau richtet ihre Worte an dich.`n`n'.
                                                           '"`^Solltest du es schaffen diesen Skelettkrieger zu besiegen, übergebe ich dir die Halskette des Lebens. Mit Hilfe dieser Kette wirst du Ramius '.
                                                           'immer sehr schnell entkommen können. Solltest du allerdings scheitern, wirst du dein Dasein bei Ramius für etwas längere Zeit geniesen können, da '.
                                                           'ich dir jegliche Gefallen und Grabkämpfe rauben werde.`@"');

                                                $badguy = array(
                                                                                'creaturename' => '`$Skelettkrieger`0',
                                                                                'creaturelevel' => $session['user']['level']+5,
                                                                                'creatureweapon' => '`$Knochenschwert`0',
                                                                                'creatureattack' => $session['user']['attack']+3,
                                                                                'creaturedefense' => $session['user']['defence'],
                                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                                'diddamage' => 0,
                                                                                'id' => 2);
                                                $session['user']['badguy'] = createstring($badguy);

                                        break;

                                        case 2:

                                                output('Als du genauer hinsiehst, kannst du erkennen das es das `isüße`i Häschen von vorhin ist. Die Frau richtet ihre Worte an dich.`n`n'.
                                                           '"`^Wie du siehst, musst du abermals gegen den Wächter des Schreins der Uralten antreten. Allerdings wird er diesmal viel stärker sein als das letzte '.
                                                           'Mal. Solltest du es schaffen ihn zu besiegen, übergebe ich dir die Halskette des Lebens. Durch diese Halskette, werden deine Lebenspunkte um 50% '.
                                                           'verdoppelt. Der Effekt verklingt natürlich sofort, wenn du die Halskette wieder verlierst. Solltest du allerdings scheitern, werde ich dir jegliche '.
                                                           'Grabkämpfe und Gefallen bei Ramius klauen.`@"');
                                                $badguy = array(
                                                                                'creaturename' => '`^`isüßes`i Häschen`0',
                                                                                'creaturelevel' => $session['user']['level']+5,
                                                                                'creatureweapon' => '`^verdammt scharfe Zähne`0',
                                                                                'creatureattack' => $session['user']['attack']+3,
                                                                                'creaturedefense' => $session['user']['defence'],
                                                                                'creaturehealth' => round($session['user']['maxhitpoints']*2,0),
                                                                                'diddamage' => 0,
                                                                                'id' => 3);
                                                $session['user']['badguy'] = createstring($badguy);

                                        break;

                                }

                                output('`n`nWirst du die Herausforderung annehmen oder lieber feige zurück in den Wald gehen?`0`n');
                                addnav('Aktionen');
                                addnav('Kämpfen!','forest.php?op=fight');
                                addnav('Feige verkriechen!','forest.php?op=run_away2');

                        break;

                        case 'run_away2':

                                $session['user']['specialinc'] = '';
                                output('`@Du sagst der Frau, dass du nun lieber gehen willst und verabschiedest dich von ihr. Der Rückweg in den Wald kostet dich 2 Runden!');
                                $session['user']['turns'] -= 2;
                                addnav('Aktionen');
                                addnav('In den Wald','forest.php');

                        break;

                        case 'necklace':

                                switch( $session['user']['specialmisc'] ) {

                                        case 1:

                                                $session['user']['specialinc'] = '';
                                                $session['user']['specialmisc'] = '';
                                                output('`@Du gehst wieder hinüber zu der Frau und verlangst deine Belohnung. Sie beglückwunscht dich und überreicht dir die Halskette des Lebens.`0`n`n'.
                                                           '`^Du erhälst die Halskette des Lebens <img src="images/forest/necklace_1.png">.',true);
                                                addnav('Aktionen');
                                                addnav('In den Wald','forest.php');
                                                addnews($session['user']['name'].'`@ erhielt im Wald die Halskette des Lebens als Belohnung für eine Prüfung.`0');
                                                $session['user']['item'] = 'necklace_1';
                                                $session['user']['item_ext'] = 1;
                                                $session['user']['item_health'] = 500;

                                        break;

                                        case 2:

                                                $session['user']['specialinc'] = '';
                                                $session['user']['specialmisc'] = '';
                                                output('`@Du gehst wieder hinüber zu der Frau und verlangst deine Belohnung. Sie beglückwunscht dich und überreicht dir die Halskette des Lebens.`0`n`n'.
                                                           '`^Du erhälst die Halskette des Lebens <img src="images/forest/necklace_1.png">.',true);
                                                addnav('Aktionen');
                                                addnav('In den Wald','forest.php');
                                                addnews($session['user']['name'].'`@ erhielt im Wald die Halskette des Lebens als Belohnung für eine Prüfung.`0');
                                                $session['user']['item'] = 'necklace_1';
                                                $session['user']['item_ext'] = 2;
                                                $session['user']['item_health'] = 500;

                                        break;

                                }

                        break;

                }

}
if($_GET['op']=='fight') {

        $battle=true;

}
if( $_GET['op'] == 'run' ) {

        output('Du kannst nicht wegrennen!');
        $_GET['op'] = 'fight';
        $battle=true;

}
if( $battle ) {

        include ('battle.php');
        $session['user']['specialinc'] = 'special_necklace.php';

        if( $victory ) {

                if( $badguy['id'] == 1 ) {

                        output('`n`@Du hast das `isüße`i Häschen besiegen können. Mit seinen letzten Worten sagt Dir das Häschen:');

                        switch( mt_rand(1,2) ) {

                                case 1:

                                        output('"`^Hättest du mich leben gelasen, mein Freund, hätte ich dir helfen können die Halskette des Lebens zu bekommen. Doch mit meinem Tod, erlischt nun auch '.
                                                        'der Weg.`@"`n`nDu kannst nun zurück in den Wald.');
                                        addnews($session['user']['name'].'`& tötete ein `isüßes`i Häschen und legte sich damit selbst Steine in den Weg!`0');
                                        $session['user']['specialinc'] = '';
                                        addnav('Aktionen');
                                        addnav('In den Wald','forest.php');

                                break;

                                case 2:

                                        output('"`^Hör mir nun genau zu, mein Freund. Ich bin der Wächter des Schreins der Uralten. Ein jeder der mich besiegen kann, darf den Schrein der Uralten '.
                                                        'betreten und muss dort noch eine letzte Prüfung ablegen bevor er reich belohnt wird. Gehe nun weiter Richtung Norden und suche den Schrein der Uralten!`@"');
                                        addnav('Aktionen');
                                        addnav('Richtung Norden gehen','forest.php?op=north');

                                break;

                        }

                }else if( $badguy['id'] == 2 ) {

                        output('`n`@Du konntest den `$Skelettkrieger`@ besiegen. Du kannst dir nun deine Belohnung abholen.`0`n');
                        addnav('Aktionen');
                        addnav('Belohnung abholen','forest.php?op=necklace');
                        $session['user']['specialmisc'] = 1;

                }else if( $badguy['id'] == 3 ) {

                        output('`n`@Du konntest das `^`isüße`i Häschen`@ besiegen. Du kannst dir nun deine Belohnung abholen.`0`n');
                        addnav('Aktionen');
                        addnav('Belohnung abholen','forest.php?op=necklace');
                        $session['user']['specialmisc'] = 2;

                }else if( $badguy['id'] == 4 ) {

                        output('`n`@Du konntest den `9Wasserelementar`@ besiegen. Mit einem lautem Grollen öffnet sich nun die Felswand vor dir und gibt den Weg in eine noch größere Höhle frei.`n');
                        addnav('Aktionen');
                        addnav('Höhle betreten.','forest.php?op=shrine_east');

                }else if( $badguy['id'] == 5 ) {

                        output('`n`@Du konntest eine `^Uralte des Ostens`@ besiegen. Mit einem lautem Grollen öffnet sich nun die Felswand vor dir und gibt den Weg in eine noch größere Höhle frei.');
                        addnav('Aktionen');
                        addnav('Weiter','forest.php?op=take2');

                }else if( $badguy['id'] == 6 ) {

                        output('`n`@Du konntest einen `^Uralten des Westens`@ besiegen. Du kannst dir nun die Halskette der Verteidigung nehmen.');
                        addnav('Aktionen');
                        addnav('Weiter','forest.php?op=take3');

                }else if( $badguy['id'] == 7 ) {

                        output('`n`@Du konntest den `^Meister der Uralten`@ besiegen. Du kannst dir nun die Halskette der Waldes nehmen.');
                        addnav('Aktionen');
                        addnav('Weiter','forest.php?op=take4');

                }

                $badguy = array();
                $session['user']['badguy'] = '';

        }elseif( $defeat ) {

                if( $badguy['id'] == 1 ) {

                        output('`n`$Du wurdest von einem `isüßem`i Häschen besiegt. Du verlierst all dein Gold und 10% deiner Erfahrungspunkte.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einem `isüßem`i Häschen besiegt.`0');

                }else if( $badguy['id'] == 2 ) {

                        output('`n`$Du wurdest von einem Skelettkrieger besiegt. Du verlierst all dein Gold, 10% deiner Erfahrung, alle Grabkämpfe und alle Gefallen.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einem Skelettkrieger besiegt und muss sein Dasein nun bei Ramius fristen.');
                        $session['user']['gravefights'] = 0;
                        $session['user']['deathpower'] = 0;

                }else if( $badguy['id'] == 3 ) {

                        output('`n`$Du wurdest von einem `isüßem`i Häschen besiegt. Du verlierst all dein Gold, 10% deiner Erfahrung, alle Grabkämpfe und alle Gefallen.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einem `isüßem`i Häschen besiegt und muss sein Dasein nun bei Ramius fristen.');
                        $session['user']['gravefights'] = 0;
                        $session['user']['deathpower'] = 0;

                }else if( $badguy['id'] == 4 ) {

                        output('`n`$Du wurdest von einem Wasserelementar besiegt. Du verlierst all dein Gold und 10% deiner Erfahrung.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einem Wasserelementar besiegt und muss sein Dasein nun bei Ramius fristen.');

                }else if( $badguy['id'] == 5 ) {

                        output('`n`$Du wurdest von einer der Uralten des Ostens besiegt. Du verlierst all dein Gold und 10% deiner Erfahrung.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einer Uralten des Ostens besiegt und muss sein Dasein nun bei Ramius fristen.');

                }else if( $badguy['id'] == 6 ) {

                        output('`n`$Du wurdest von einem der Uralten des Westens besiegt. Du verlierst all dein Gold und 10% deiner Erfahrung.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde von einem Uralten des Westens besiegt und muss sein Dasein nun bei Ramius fristen.');

                }else if( $badguy['id'] == 7 ) {

                        output('`n`$Du wurdest vom Meister der Uralten besiegt. Du verlierst all dein Gold und 10% deiner Erfahrung.`n`nDu bist tot!`0');
                        addnews($session['user']['name'].'`$ wurde vom Meister der Uralten besiegt und muss sein Dasein nun bei Ramius fristen.');

                }

                $badguy = array();
                $session['user']['badguy'] = '';
                $session['user']['specialinc'] = '';
                $session['user']['specialmisc'] = '';
                $session['user']['alive'] = 0;
                $session['user']['hitpoints'] = 0;
                $session['user']['gold'] = 0;
                $session['user']['experience'] -= $session['user']['experience']*.10;

                addnav('Aktionen');
                addnav('Tägliche News','news.php');

        }else{

                fightnav();

        }

}

?> 