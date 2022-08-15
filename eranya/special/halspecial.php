
<?php
// Special zum Halloween-Event
// http://eranya.de
// Autor: Silva

if(!isset($session))
{
        exit();
}

$str_specname = basename(__FILE__);
$session['user']['specialinc'] = $str_specname;
$str_filename = 'forest.php';
$str_godname = 'Ishra&#269;z';
$str_tout = "`n`n";
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
if(getsetting('halloween_special'))
{
        $str_tout .= "`c`b`qHalloween, Halloween!!`b`c`n";
        $row_pc = user_get_aei('pumpkin_coins');
        $row_wc = user_get_aei('witch_coins');
        switch($str_op)
        {
                // Anfang: Spiel wählen
                case '':
                        $str_tout .= "`QDu durchquerst einen besonders finster geratenen Teil des Waldes. Hohe, dicht stehende Tannen haben hier sämtliche
                                      andere Pflanzensorten vertrieben - nur Moos bedeckt den Waldboden hier und da. Plötzlich siehst du in der Ferne den
                                      orangeroten Schein eines Feuers aufflackern. Was das wohl ist? Kurzerhand ziehst du deine Waffe und steuerst auf
                                      die Stelle zu.`n
                                      `QNur wenig später öffnet sich der Wald, gibt den Blick frei auf eine kleine Lichtung, in deren Mitte sich ein Lagerfeuer
                                      hoch in den Nachthimmel (Nachthimmel?!?) reckt. Um es herum stehen drei schwarz gekleidete Gestalten, die Arme gen der Flammen gestreckt, und
                                      schon von hier kannst du deutlich hören, dass sie im Einklang Worte rufen, auch wenn du nicht wirklich verstehen kannst,
                                      was genau sie sagen.`n
                                      `QMit einem Mal dreht sich eine der Gestalten zu dir um, und du erkennst, dass es sich um eine Frau handelt - mit
                                      `@giftig grünem`Q, glattem Haar! Auch die anderen beiden Fremden - ebenfalls Frauen - wenden sich dir zu; eine von ihnen
                                      besitzt eine `\$feuerrote `QLockenpracht, die andere hat kurze Haare von der `úFarbe der See`Q. Sie alle winken dich zu
                                      sich und rufen deinen Namen.`n
                                      `QOb das wohl eine Falle ist? Nun, es gibt nur einen Weg, das herauszufinden. Zu welcher der Fremden wirst du gehen?";
                        #addnav('',$str_filename.'');
                        addnav('Dem Rufen folgen');
                        addnav('g?Frau mit `@grünem`0 Haar',$str_filename.'?op=green');
                        addnav('r?Frau mit `$rotem`0 Haar',$str_filename.'?op=red');
                        addnav('b?Frau mit `úblauem`0 Haar',$str_filename.'?op=blue');
                        addnav('Zurück');
                        addnav('S?Schnell weg hier!',$str_filename.'?op=leave');
                break;
                // 1: Grüne Hexe - Kürbismünzen-Spiel
                case 'green':
                        $str_tout .= "`QKurz zögerst du, doch dann wendest du dich der Frau mit dem `@grünen`Q Haar zu und gehst du ihr hinüber. ";
                        if($row_pc['pumpkin_coins'] < 10)
                        {
                                $str_tout .= "Anfangs begegnet sie dir mit einem breiten Grinsen, doch dann verwandelt sich dieses plötzlich in ein
                                              noch deutlicheres Zähnefletschen. \"`@Mhr, nein! Verschwinde! Und komm erst wieder, wenn du hast, was ich
                                              brauche!`Q\" Verwirrt wendest du dich ab - und siehst lieber zu, dass du Land gewinnst.";
                                $session['user']['specialinc'] = '';
                        }
                        else
                        {
                                $str_tout .= "Sie empfängt dich mit einem breiten Grinsen, wobei dir tiefbraune Augen entgegen blitzen. \"`@Ah, ich wusste es!
                                              Komm näher! Komm näher, ".$session['user']['login']."!`Q\" Du lässt dich von ihr zu dem Lagerfeuer
                                              schieben - und bemerkst erst jetzt, dass dort ein Tisch mit einer goldenen Schale steht. Genau
                                              vor dieser bleibt die Frau stehen und blickt dich erneut aus ihren dunklen Augen an. \"`@Sieh,
                                              ".$session['user']['login']."! Dies ist die Opferschale des ".$str_godname.", der Seele der Geisterwelt.
                                              Seine Launen sind unberechenbar - doch einem kann er nicht widerstehen: dem goldenen Glanz der
                                              `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men`@. Los, ".$session['user']['login'].", opfere ihm `g10 deiner Münzen`@!
                                              Finde heraus, ob die Geister dir gewogen sind.`Q\" Ob du das wirklich tun solltest..?`n`n";
                                addnav('Opfern');
                                addnav('10 Kürbismünzen opfern',$str_filename.'?op=green2');
                                addnav('Zurück');
                                addnav('Lieber nicht',$str_filename.'?op=leave');
                        }
                break;
                case 'green2':
                        $str_tout .= "`QDu überlegst nicht lange, zückst deinen Beutel mit den `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men `Qund lässt nach und nach
                                      10 Münzen in die Schale fallen. Kaum hat die letzte das goldene Gefäß berührt, beginnt dieses auch schon zu
                                      leuchten - so sehr, dass du den Arm schützend vor deine Augen halten musst. Neben dir hörst du die grüne Hexe
                                      leise kichern. Nur ein weiterer Augenblick, und das
                                      Leuchten vergeht so schnell, wie es auch gekommen ist. Du senkst deinen Arm und stellst fest, dass...`n`n";
                        $int_erand = e_rand(1,5);
                        switch($int_erand)
                        {
                                case 1:
                                        $str_tout .= "... von deinen Münzen jegliche Spur fehlt. Auch die grüne Hexe ist verschwunden - ebenso wie ihre
                                                      zwei Schwestern. Verdammt! Man hat dich reingelegt!";
                                        $row_pc['pumpkin_coins'] -= 10;
                                break;
                                case 2:
                                        $str_tout .= "... in der Schale vor dir plötzlich die doppelte Menge an Münzen liegt. Erfreut steckst du sie ein,
                                                      wobei du eine Münze für den Hexengott in der Schale liegen lässt. Dann lässt du die Hexen auf ihrer
                                                      Lichtung zurück.";
                                        $row_pc['pumpkin_coins'] += 9;
                                break;
                                case 3:
                                case 4:
                                case 5:
                                        $str_tout .= "... du plötzlich allein vor dem Lagerfeuer stehst. Verwirrt blickst du dich um, doch von der grünen
                                                      Hexe und ihren Schwestern ist nichts mehr zu sehen. Mit einem Schulterzucken steckst du deine
                                                      Münzen wieder ein und ziehst von dannen.";
                                break;
                        }
                        user_set_aei($row_pc);
                        $session['user']['specialinc'] = '';
                break;
                // 2: Rote Hexe - Hexentaler-Spiel
                case 'red':
                        $str_tout .= "`QKurz zögerst du, doch dann wendest du dich der Frau mit dem `\$roten`Q Haar zu und gehst du ihr hinüber. ";
                        if($row_wc['witch_coins'] < 5)
                        {
                                $str_tout .= "Anfangs begegnet sie dir mit einem wissenden Grinsen, doch dann verwandelt sich dieses plötzlich in ein
                                              deutliches Zähnefletschen. \"`\$Mhr, nein! Verschwinde! Und komm erst wieder, wenn du hast, was ich
                                              brauche!`Q\" Verwirrt wendest du dich ab - und siehst lieber zu, dass du Land gewinnst.";
                                $session['user']['specialinc'] = '';
                        }
                        else
                        {
                                $str_tout .= "Sie empfängt dich mit einem wissenden Grinsen, wobei dir blattgrüne Augen entgegen funkeln. \"`\$Die Geister
                                              haben dich angekündigt, ".$session['user']['login'].".`Q\" Du lässt dich von ihr zu dem Lagerfeuer
                                              schieben - und bemerkst erst jetzt, dass dort ein Tisch mit einem goldenen Kelch steht. Genau
                                              vor diesem bleibt die Frau stehen und blickt dich erneut mit glühenden Augen an. \"`\$Dies,
                                              ".$session['user']['login'].", ist der Opferkelch des ".$str_godname.", der Seele der Geisterwelt.
                                              So zerstörerisch wie das Feuer, so können auch seine Launen sein. Doch es gibt etwas, das ihn besänftigt:
                                              das leise Klimpern von `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Ârn`$. Wage es, ".$session['user']['login'].",
                                              und opfere ihm `§5 deiner Taler`$. Auf dass die Geister sich deiner annehmen.`Q\" Ob du das wirklich
                                              tun solltest..?`n`n";
                                addnav('Opfern');
                                addnav('5 Hexentaler opfern',$str_filename.'?op=red2');
                                addnav('Zurück');
                                addnav('Lieber nicht',$str_filename.'?op=leave');
                        }
                break;
                case 'red2':
                        $str_tout .= "`QDu überlegst nicht lange, zückst deinen Beutel mit den `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Âr `Qund lässt nach und nach
                                      5 Taler in den Kelch fallen. Kaum hat der letzte das goldene Gefäß berührt, beginnt dieses auch schon zu
                                      leuchten - so sehr, dass du den Arm schützend vor deine Augen halten musst. Neben dir hörst du die rote Hexe
                                      leise kichern. Nur ein weiterer Augenblick, und das
                                      Leuchten vergeht so schnell, wie es auch gekommen ist. Du senkst deinen Arm und stellst fest, dass...`n`n";
                        $int_erand = e_rand(1,5);
                        switch($int_erand)
                        {
                                case 1:
                                        $str_tout .= "... von deinen Münzen jegliche Spur fehlt. Auch die rote Hexe ist verschwunden - ebenso wie ihre
                                                      zwei Schwestern. Verdammt! Man hat dich reingelegt!";
                                        $row_wc['witch_coins'] -= 5;
                                break;
                                case 2:
                                        $str_tout .= "... in dem Kelch vor dir plötzlich die doppelte Menge an Talern liegt. Erfreut steckst du sie ein,
                                                      wobei du einen Taler für den Hexengott im Kelch liegen lässt. Dann lässt du die Hexen auf ihrer
                                                      Lichtung zurück.";
                                        $row_wc['witch_coins'] += 4;
                                break;
                                case 3:
                                case 4:
                                case 5:
                                        $str_tout .= "... du plötzlich allein vor dem Lagerfeuer stehst. Verwirrt blickst du dich um, doch von der roten
                                                      Hexe und ihren Schwestern ist nichts mehr zu sehen. Mit einem Schulterzucken steckst du deine
                                                      Taler wieder ein und ziehst von dannen.";
                                break;
                        }
                        user_set_aei($row_wc);
                        $session['user']['specialinc'] = '';
                break;
                // 3: Blaue Hexe - Glücksspiel
                case 'blue':
                        $str_tout .= "`QKurz zögerst du, doch dann wendest du dich der Frau mit dem `úblauen`Q Haar zu und gehst du ihr hinüber. ";
                        $str_tout .= "Sie empfängt dich mit einem verschmitzten Grinsen, wobei dir Augen in einem milchigen Weiß entgegenblicken.
                                      Ohne weitere Worte schiebt sie dich zum Lagerfeuer hin - wobei du
                                      erst jetzt bemerkst, dass dort ein Tisch steht, auf dem drei Utensilien nebeneinander aufgereiht liegen:
                                      eine `3Vogelmaske`Q, eine `Utote Fledermaus `Qund ein `FZierkürbis`Q. Genau
                                      vor diesen bleibt die Frau stehen und streicht sanft mit den Fingerspitzen über den Rand der Maske.
                                      Dann fährt sie mit einem Ruck zu dir herum. \"`úWähle!`Q\", fordert sie dich auf. Dann kehrt mit einem Mal das
                                      Grinsen von eben auf ihre Lippen zurück. \"`úAber wähle mit Bedacht. Denn entscheidest du dich falsch, ziehst
                                      du den Zorn des ".$str_godname.", der Seele der Geisterwelt, auf dich.`Q\" Ob du es dann überhaupt wagen
                                      solltest..?`n`n";
                        addnav('Wählen');
                        addnav('Die Vogelmaske',$str_filename.'?op=blue2&act=mask');
                        addnav('Die Fledermaus',$str_filename.'?op=blue2&act=bat');
                        addnav('Den Zierkürbis',$str_filename.'?op=blue2&act=pumpkin');
                        addnav('Zurück');
                        addnav('Lieber nicht',$str_filename.'?op=leave');
                break;
                case 'blue2':
                        $str_act = (isset($_GET['act']) ? $_GET['act'] : '');
                        $int_erand = e_rand(1,3);
                        $arr_choices = array(1=>'mask',2=>'bat',3=>'pumpkin');
                        $arr_items = array('mask'=>'die Vogelmaske','bat'=>'die tote Fledermaus','pumpkin'=>'den Zierkürbis');
                        $str_tout .= "`QDu überlegst kurz und deutest dann auf ".$arr_items[$str_act].". ";
                        if($str_act == $arr_choices[$int_erand])
                        {

                                $str_tout .= "Prompt weicht das Grinsen aus dem Gesicht der blauen Hexe, verzerrt sich zu einem Zähnefletschen.
                                              \"`úUnmöglich! Betrug!`Q\", faucht sie wütend - und auch hinter dir hörst du die anderen beiden Hexen
                                              Ähnliches schreien. Als sich dann die Hände der blauen Hexe zu Klauen verformen, reicht es dir endgültig:
                                              Du wirfst dich herum und rennst zurück in die düsteren Tiefen des Waldes.`n
                                              Erst, als du das Gekeife der Hexen nicht mehr hören kannst, bleibst du wieder stehen. Dein Atem geht
                                              stoßweise, und du musst dich erstmal setzen und Pause machen, ehe es weitergehen kann. Dabei fällt dir
                                              auf, dass ";
                                if($str_act == 'pumpkin')
                                {
                                        $str_tout .= "dein Beutel mit den `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men `Qseltsam schwer wiegt. Du siehst nach und
                                                      entdeckst, dass du nun `F15 Münzen mehr `Qbesitzt.";
                                        $row_pc['pumpkin_coins'] += 15;
                                        user_set_aei($row_pc);
                                }
                                elseif($str_act == 'mask')
                                {
                                        $str_tout .= "dein Beutel mit den `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Ârn `Qseltsam schwer wiegt. Du siehst nach und
                                                      entdeckst, dass du nun `F7 Taler mehr `Qbesitzt.";
                                        $row_wc['witch_coins'] += 7;
                                        user_set_aei($row_wc);
                                }
                                else
                                {
                                        $str_tout .= "eine Handvoll Knochen in deiner Hosentasche gelandet sind. Igitt! Schnell leerst du die Tasche aus,
                                                      wobei die Knochen zu Boden fallen und dort in abertausend kleine Teile zersplittern. Du erhältst
                                                      `F25 Gefallen`Q bei Jarcath!";
                                        $session['user']['deathpower'] += 25;
                                }
                                $session['user']['turns']--;
                        }
                        else
                        {
                                $str_tout .= "Augenblicklich fängt es hinter deinem Rücken an zu kichern - und als du den Kopf wendest, siehst du die
                                              beiden anderen Hexen direkt hinter dir stehen. Ihre Hände wirken plötzlich eher wie Klauen.. und das
                                              vermeintliche Grinsen ist so verzerrt, dass es eigentlich schon gar nicht mehr als solches durchgehen
                                              kann. Dir schwant Übles, weshalb du dich ruckartig herumwirfst - doch leider bist du nicht schnell genug.
                                              Eine Klaue schließt sich um deine Kehle, milchig weiße, pupillenlose Augen starren dich an, und du hörst
                                              noch die Worte: \"`úSo sei es. Du wähltest den Tod!`Q\", ehe es schwarz um dich herum wird.`n
                                              `n
                                              `4Dein Körper ist den Anhängerinnen des Hexengottes ".$str_godname." zum Opfer gefallen. Du bist tot.";
                                $session['user']['hitpoints'] = 0;
                                $session['user']['alive'] = 0;
                                addnews($session['user']['name']." `Qwurde von Anhängerinnen des Hexengottes ".$str_godname." getötet!");
                                addnav('Verdammt!');
                                addnav('Zu den Schatten','shades.php');
                        }
                        $session['user']['specialinc'] = '';
                break;
                // Weg hier!
                case 'leave':
                        $str_tout .= "`QDir ist das Ganze zu suspekt. Deshalb wendest du dich kurzerhand um und siehst zu, dass du zurück in den Teil
                                      des Waldes kommst, der nicht so schaurig finster ist.";
                        $session['user']['specialinc'] = '';
                break;
        }
}
else
{
        $str_tout .= "`QDu durchquerst einen besonders finster geratenen Teil des Waldes. Hohe, dicht stehende Tannen haben hier sämtliche
                     andere Pflanzensorten vertrieben - nur Moos bedeckt den Waldboden hier und da. Als du schließlich eine kleine Lichtung
                     erreichst, bleibst du kurz stehen und atmest einmal tief durch. Irrst du dich oder riecht es hier tatsächlich dezent nach
                     verbranntem Holz..? Mit einem Kopfschütteln ziehst du weiter.`n
                     `n
                     Durch die Pause fühlst du dich ausgeruhter.`n
                     `n";
        if($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
        {
                $session['user']['hitpoints'] *= 1.03;
        }
        if($session['user']['hitpoints'] > $session['user']['maxhitpoints'])
        {
                $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        }
        $session['user']['turns']--;
        $session['user']['specialinc'] = '';
}
output($str_tout);
?>

