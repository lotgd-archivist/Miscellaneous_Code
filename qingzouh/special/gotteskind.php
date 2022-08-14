
<?php
/*Copyright, Idee und Umsetzung: Lori (Dorit Graumann) lori_merydia@hotmail.de
Beschreibung: Das Gotteskind ist ein kleines Waldspecial. Wenn man ihm begegnet hat man verschiedene AuswahlmÃ¶glichkeiten, welche immer eine gute, schlechte oder neutrale Auswirkung haben kÃ¶nnen.
Einbau: Einfach in den Specialordner hochladen und fertig ist es. Ihr kÃ¶nnt die Belohnungen Ã¤ndern und solltet die GÃ¶tternamen anpassen an euer Logd ;) 
*/
if (!isset($session))
    exit();
output('`n`n`cDas Kind im Wald`c`n`n');
switch ($_GET['op'])
    {
    case 'kind':
        output('`#Du gehst auf das Kind zu und grÃ¼ÃŸt hÃ¶flich. Der Junge dreht sich um, so als ob er dich erwartet hÃ¤tte: "'.$session['user']['name'].'`2welch GlÃ¼ck dich hier zu treffen. ');
        $session['user']['specialinc'] = 'gotteskind.php';
        switch (mt_rand(1,7))
            {
            case 1:
                output('Willst du mir ein wenig Gold geben?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Gold geben','forest.php?op=ja&was=gold');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            case 2:
                output('Willst du mir einen Edelstein geben?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Edelstein geben','forest.php?op=ja&was=edel');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            case 3:
                output('Willst du mit mir ein wenig spielen?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Spielen','forest.php?op=ja&was=spiel');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            case 4:
                output('Willst du mich retten?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Retten','forest.php?op=ja&was=retter');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            case 5:
                output('Willst du mit mir ein wenig trainieren?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Trainieren','forest.php?op=ja&was=trainer');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            case 6:
                output('Willst du dich mir opfern?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie entscheidest du dich nun?`0');
                addnav('Opfern','forest.php?op=ja&was=opfer');
                addnav('Ablehnen','forest.php?op=nein');
            break;
            default:
                output('Welcher Gott ist der Beste?`#" Du bist schon allein verwundert, dass er deinen Namen kennt, aber noch perplexer wirst du, als er die Frage stellt. Wie ist deine Antwort?`0');
                addnav('Ramius','forest.php?op=ja&was=gott&act=1');
                addnav('Aphrodite','forest.php?op=ja&was=gott&act=2');
                addnav('Fexez','forest.php?op=ja&was=gott&act=3');
                addnav('Foilwench','forest.php?op=ja&was=gott&act=4');
                addnav('Keiner','forest.php?op=nein');
            break;
            }
    break;
    case 'ja':
        $zufall = mt_rand(1,8);
        $session['user']['specialinc'] = '';
        if ($_GET['was'] == 'gold')
            $text = array(1 => 'gibst dem Kind ein GoldstÃ¼ck',
                            2 => 'Willst du mich bestechen?',
                            3 => 'nimmt dein Gold dankend entgegen',
                            4 => 'nimmt dein Gold freudestrahlend entgegen',
                            5 => 'beachtet dein Gold nicht weiter');
        elseif ($_GET['was'] == 'edel')
            $text = array(1 => 'gibst dem Kind einen Edelstein',
                            2 => 'Willst du mich bestechen?',
                            3 => 'nimmt deinen Edelstein dankend entgegen',
                            4 => 'nimmt deinen Edelstein freudestrahlend entgegen',
                            5 => 'beachtet deinen Edelstein nicht weiter');
        elseif ($_GET['was'] == 'spiel')
            $text = array(1 => 'entscheidest dich, mit dem Kind zu spielen',
                            2 => 'Bin ich ein Kleinkind, dass ich nach spielen aussehe?',
                            3 => 'spielt mit dir ein wenig',
                            4 => 'spielt freudestrahlend mit dir ein wenig',
                            5 => 'beachtet deine Antwort nicht weiter');
        elseif ($_GET['was'] == 'retter')
            $text = array(1 => 'willst dem Kind helfen',
                            2 => 'Du willst mich retten? Vor wem?',
                            3 => 'nimmt deine Antwort dankend entgegen',
                            4 => 'nimmt deine Antwort freudestrahlend entgegen',
                            5 => 'beachtet deine Antwort nicht weiter');
        elseif ($_GET['was'] == 'trainer')
            $text = array(1 => 'willst mit dem Kind trainieren',
                            2 => 'Du willst mit mir trainieren? Mit dieser Waffe?',
                            3 => 'nimmt deine Antwort dankend entgegen und trainiert mit dir ein paar Runden',
                            4 => 'nimmt deine Antwort freudestrahlend entgegen und trainiert mit dir ein paar Runden',
                            5 => 'beachtet deine Antwort nicht weiter');
        elseif ($_GET['was'] == 'opfer')
            $text = array(1 => 'dich dem Kind opfern',
                            2 => 'Willst du mich bestechen?',
                            3 => 'nimmt dein Angebot dankend entgegen und fÃ¼hrt an dir ein Ritual aus, ehe er dir ein Messer in dein Herz rammt. Zu deinem GlÃ¼ck hast du Ã¼berlebt',
                            4 => 'nimmt dein Angebot freudestrahlend entgegen und fÃ¼hrt an dir ein Ritual aus, ehe er dir ein Messer in dein Herz rammt. Zu deinem GlÃ¼ck hast du Ã¼berlebt',
                            5 => 'beachtet dein Angebot nicht weiter');
        else
            {
            $gott = array(1 => 'Ramius', 2 => 'Aphrodite', 3 => 'Fexez', 4 => 'Foilwench');
            $text = array(1 => 'antwortest, dass '.$gott[$_GET['act']].' der beste Gott ist',
                            2 => 'Willst du mich verÃ¤rgern? Wieso ausgerechnet '.$gott[$_GET['act']].'?',
                            3 => 'nickt leicht und murmelt: "`2Mein'.($_GET['act']%2 == 0?'e Mutter':' Vater').' ist das.`#"',
                            4 => 'nickt freudestrahlend und murmelt: "`2Mein'.($_GET['act']%2 == 0?'e Mutter':' Vater').' ist das, wie schÃ¶n.`#"',
                            5 => 'beachtet deine Antwort nicht weiter');
            }
        output('`#Du '.$text[1].' und wartest gespannt auf die Reaktion des Jungen. ');
        if (($session['user']['gold'] < 1 && $_GET['was'] == 'gold') || ($session['user']['gems'] < 1 && $_GET['was'] == 'edel'))
            {
            output('`#"`2Du hast nichts? Dein Pech, komm wieder, wenn du etwas hast.`#" meint er. Du sagst noch etwas, von wegen, dass du es nicht bemerkt hast. ');
            $zufall = 4;
            }
        elseif ($session['user']['gold'] >= 1 && $_GET['was'] == 'gold')
            $session['user']['gold'] --;
        elseif ($session['user']['gems'] >= 1 && $_GET['was'] == 'edel')
            $session['user']['gems'] --;
        else     {}
        switch($zufall)
            {
            case 1:
                output('Er schaut mit einem mal bÃ¶se drein und sagt in einem gereizten Ton: "`2'.$text[2].' Das wirst du noch bereuen!`#" Er murmelt einige Worte und du fÃ¤llst in Ohnmacht. Erwachen? Von wegen, du bist tot!`0');
                $session['user']['alive'] = 0;
                $session['user']['hitpoints'] = 0;
                $session['user']['turns'] = 0;
                addnews($session['user']['name'].' `2hat sich mit einem Kind angelegt und Ã¼berlebte nicht.');
                addnav('Zu den News','news.php');
            break;
            case 2:
                output('Er '.$text[3].'. Weiterhin bedankt er sich bei dir und wÃ¼nscht dir noch einen schÃ¶nen Tag im Wald. Dann lÃ¶st er sich in Luft auf und du bemerkst, dass du bereit fÃ¼r einen weiteren Kampf bist.`0');
                $session['user']['turns'] ++;
            break;
            case 3:
                output('Er '.$text[4].'. Weiterhin bedankt er sich ausgiebig bei dir, indem du ');
                if ($_GET['was'] == 'gold' || $_GET['was'] == 'edel')
                    {
                    $gold = mt_rand(0,1000);
                    $edel = mt_rand(0,2);
                    output($gold.' Gold und '.$edel.' Edelstein'.($edel == 1?'e':'').' erhÃ¤ltst.`0');
                    $session['user']['gold'] += $gold;
                    $session['user']['gems'] += $gems;
                    }
                elseif ($_GET['was'] == 'trainer' || $_GET['was'] == 'opfer')
                    {
                    output(' Erfahrung erhÃ¤ltst.`0');
                    $session['user']['experience'] += mt_rand(10,100)*$session['user']['level'];
                    }
                else
                    {
                    switch(mt_rand(1,6))
                        {
                        case 1:
                            $session['user']['gold'] += mt_rand(30,500)*$session['user']['level'];
                            output(' Gold erhÃ¤ltst.`0');
                        break;
                        case 2:
                            $session['user']['gems'] += mt_rand(1,3);
                            output(' Edelsteine erhÃ¤ltst.`0');
                        break;
                        case 3:
                            $session['user']['attack'] ++;
                            output(' mehr Schlagkraft erhÃ¤ltst.`0');
                        break;
                        case 4:
                            $session['user']['defence'] ++;
                            output(' mehr Verteidigung erhÃ¤ltst.`0');
                        break;
                        case 5:
                            $session['user']['maxhitpoints'] ++;
                            output(' mehr Leben erhÃ¤ltst.`0');
                        break;
                        default:
                            $session['user']['turns'] += mt_rand(1,4);
                            output(' dich erholter fÃ¼hlst und so mehr KÃ¤mpfe bestreiten kannst.`0');
                        break;
                        }                        
                    }
                addnews($session['user']['name'].' `2machte ein Kind unheimlich glÃ¼cklich und wurde dafÃ¼r reichlich belohnt.`0');
            break;
            default:
                output('Er '.$text[5].', dreht sich wieder um und spielt mit seinen Holztieren. Du wartest noch ein Weilchen, doch nichts tut sich. So ziehst du wieder in den Wald.`0');
            break;
            }
    break;
    case 'nein':
        $session['user']['specialinc'] = '';
        $zufall = mt_rand(1,3);
        switch($zufall)
            {
            case 1:
                output('`#HÃ¶flich lehnst du ab und entschuldigst dich, der Wald wÃ¼rde wieder nach dir rufen. Du siehst TrÃ¤nen in den Augen des Jungen, doch beachtest du dies nicht weiter. Als du halb im Wald verschwunden bist hÃ¶rst du ein lautes Weinen, welches immer lauter wird. Es raubt dir deine Sinne. Als wieder Stille im Wald eingekehrt ist erholst du dich langsam. Leider braucht dasd einige Zeit, die du lieber mit KÃ¤mpfen verbracht hÃ¤ttest.`0');
                $session['user']['turns'] -= 1;
            break;
            default:
                output('`#HÃ¶flich lehnst du ab und entschuldigst dich. Der Junge lÃ¤chelt dich an und wÃ¼nscht dir noch viel SpaÃŸ im Wald. Dann dreht er sich um und spielt wieder mit seinen Holztieren, wÃ¤hrend du dich in den Wald aufmachst.`0');
            break;
            }
    break;
    case 'geh':
        output('`#Du drehst dich um, ohne das Kind weiter zu beachten und willst zurÃ¼ck in den Wald gehen. ');
        $session['user']['specialinc'] = '';
        if (mt_rand(1,3) == 2)
            {
            output('So ein Pech aber auch. Das Kind hat deine Anwesenheit bemerkt und ist ein wenig verÃ¤rgert Ã¼ber dich. Wie kannst du aber auch einem Kind der GÃ¶tter einfach so den RÃ¼cken kehren, ohne es wenigstens zu grÃ¼ÃŸen. ');
            $zufall = mt_rand(1,8);
            switch($zufall)
                {
                case 1:
                    output('Du hÃ¶rst ein Gemurmel, was immer lauter wird und von Ã¼berall herzukommen scheint. Es scheint, als ob die BÃ¤ume immer nÃ¤her kommen. Du atmest schneller, kannst dich nicht bewegen. Immer nÃ¤her kommen die BÃ¤ume und scheinen dich zu erdrÃ¼cken. Mit einem Schrei brichst du zusammen. Noch bevor du den letzten Atemzug tust siehst du noch, wie das Kind sich umdreht und ruhig weiter spielt, als wÃ¤re nichts geschehen.`0');
                    $session['user']['alive'] = 0;
                    $session['user']['hitpoints'] = 0;
                    $session['user']['turns'] = 0;
                    addnews($session['user']['name'].' `2hÃ¤tte sich nicht mit einem Kind anlegen sollen. Nun liegen seine Knochen im Wald verstreut.`0');
                    addnav('Zu den News','news.php');
                break;
                case 3:
                    output('Du hÃ¶rst einen lauten Schrei und schnelle Schritte. Schnell versuchst du dich umzudrehen, doch das Kind war schneller. Es hat sich mit einer Keule bewaffnet auf dich gestÃ¼rzt und schlÃ¤gt einmal krÃ¤ftig zu. Du spÃ¼rst den dumpfen Schlag und taumelst herum, als auch schon wieder Stille herrscht. Zum GlÃ¼ck hast du nur ein paar blaue Flecken und Kratzer als Andenken bekommen. Es hÃ¤tte auch schlimmer ausgehen kÃ¶nnen. Schnell gehst du weiter, um den Heiler aufzusuchen.`0');
                    $session['user']['hitpoints'] -= floor($session['user']['maxhitpoints']*0.3);
                    if ($session['user']['hitpoints'] < 1)
                        $session['user']['hitpoints'] = 1;
                break;
                case 5:
                    output('Du hÃ¶rst noch leise gemurmelte Worte ehe du zusammensinkst, als wÃ¤rst du von einer schweren Keule hinterrÃ¼cks getroffen worden. Die Zeit, die du zur Erholung brauchst fehlt dir bei deinen WaldkÃ¤mpfen.`0');
                    $session['user']['turns'] -= 2;
                    if ($session['user']['turns'] < 0)
                        $session['user']['turns'] = 0;
                break;
                case 7:
                    output('Du hÃ¶rst noch ein leises Kichern hinter dir, als du auch schon im wald verschwunden bist. Irgendwie scheint dein Goldbeutel schwerer zu sein. Du zÃ¤hlst schnell nach und siehe da, du hast mehr Gold im Beutel, als vor der Begegnung.`0');
                    $session['user']['gold'] += mt_rand(2,10)*$session['user']['level'];
                break;
                case 8:
                    output('Du hÃ¶rst noch ein leises Kichern hinter dir, als du auch schon im wald verschwunden bist. Irgendwie scheint dein Edelsteinbeutel schwerer zu sein. Du zÃ¤hlst schnell nach und siehe da, du hast mehr Edelseine im Beutel, als vor der Begegnung.`0');
                    $session['user']['gems'] ++;
                break;
                default:
                    output('Ob es der Zufall wollte oder nicht, jedenfalls bist du schneller gewesen, als das Kind einen Fluch auf dich sprechen konnte. GlÃ¼ck gehabt.`0');
                break;
                }
            }
        else
            output('Zum GlÃ¼ck hat es dich nicht bemerkt, so dass du unbemerkt verschwinden kannst.`0');
    break;
    default:
        output('`#Bei deinen StreifzÃ¼gen durch den Wald trittst du auf eine Lichtung, welche durch einen Sonnenstrahl hell erleuchtet ist. In der Mitte der Lichtung sitzt ein kleiner Junge auf dem Boden und spielt mit ein paar geschnitzten Holztieren. Er scheint dich nicht zu bemerken. Du fragst dich insgeheim, was das fÃ¼r ein Kind ist und wer die Eltern sein mÃ¶gen, die ihr Kind alleine in einem von wilden Bestien besiedelten Wald lassen. Nun musst du dich entscheiden, was du machen willst: Zu ihm hingehen oder wieder in den Wald verschwinden?`0');
        $session['user']['specialinc'] = 'gotteskind.php';
        addnav('Zu dem Kind','forest.php?op=kind');
        addnav('In den Wald','forest.php?op=geh');
    break;
    }
output('`n`n');
?>

