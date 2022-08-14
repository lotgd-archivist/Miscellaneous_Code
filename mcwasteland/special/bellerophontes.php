
<?php

// Bellerophontes' Turm, Version 1.16

//

// Bellerophontes' Turm birgt viele Ãœberraschungen.

// Wohl dem, der es schafft, ihn zu erreichen!

// Wohl dem ... ?

//

// Erdacht und umgesetzt von Oliver Wellinghoff.

// E-Mail: wellinghoff@gmx.de

// Erstmals erschienen auf: http://www.green-dragon.info

//

//  - 25.06.2004 -

//  - Version vom 27.12.2004 -



if (!isset($session)) exit();

$session['user']['specialinc'] = "bellerophontes.php";

switch($_GET['op']){



    case "abbiegen1":

    output("`@Du biegst an der Kreuzung ab und verlÃ¤sst den Weg.");

    $session['user']['specialinc']="";

    break;



    case "weiter":

    switch(e_rand(1,10)){

        case 1:

        case 2:

        case 3:

        case 4:

        case 5:

        case 6:

        output("`@Du folgst dem Pfad immer tiefer in den Wald hinein, stundenlang, doch der Turm bleibt fest am Horizont. Es ist, als kÃ¶nnte man nicht zu ihm gelangen .... Du willst schon aufgeben - als er plÃ¶tzlich mit jedem weiteren Schritt einige Hundert Meter nÃ¤her kommt!`n`n");

        $turns2 = e_rand(1,3);

        output("`^Bis hierher zu gelangen hat Dich bereits ".$turns2." WaldkÃ¤mpfe gekostet!");

        $session['user']['turns']-=($session['user']['turns']>=$turns2?$turns2:$session['user']['turns']);

        output("`n`n`@<a href='forest.php?op=turm'>Weiter</a>", true);

        addnav("","forest.php?op=turm");

        addnav("Weiter","forest.php?op=turm");

        break;

        case 7:

        case 8:

        case 9:

        case 10:

        output("`@Du folgst dem Pfad immer tiefer in den Wald, stundenlang. Er scheint nicht enden zu wollen - und immer siehst Du den Turm an seinem Ende. An der nÃ¤chsten Weggabelung bleibst Du stehen.");

        if ($session['user']['turns']<=0){

            output("`n`nDas war Dein `^letzter`@ Waldkampf und es ist schon dunkel geworden! `n`nDu machst Dich mit dem festen Vorsatz auf den Heimweg, morgen noch einmal zu versuchen, den Turm zu erreichen.");

            $session['user']['specialinc']="";

        }else {

            output(" Weiter nach dem Turm zu suchen wird Dich mÃ¶glicherweise alle Deine WaldkÃ¤mpfe kosten, aber Du spÃ¼rst, dass Du `bganz dicht dran`b bist ...");

            output("`n`n`@<a href='forest.php?op=weiter2'>Weiter.</a>", true);

            output("`n`n`@<a href='forest.php?op=abbiegen2'>Abbiegen.</a>", true);

            addnav("","forest.php?op=weiter2");

            addnav("","forest.php?op=abbiegen2");

            addnav("Weitergehen","forest.php?op=weiter2");

            addnav("Abbiegen","forest.php?op=abbiegen2");

        }

        break;

    }

    break;



    case "abbiegen2":

    output("`@Du biegst an der Kreuzung ab und verlÃ¤sst den Weg.`n`n");

    output("`^Bis hierher zu gelangen hat Dich jedoch bereits einen Waldkampf gekostet!");

    if ($session['user']['turns']>=1) $session['user']['turns']-=1;

    $session['user']['specialinc']="";

    break;



    case "weiter2":

    output("`@Du gibst nicht auf und folgst dem Pfad noch tiefer in den Wald hinein. Er scheint noch immer nicht enden zu wollen, und es wird immer dunkler. Noch etwa eine Stunde und auch das letzte Licht, das sich seinen Weg durch die BÃ¤ume kÃ¤mpft, wird erloschen sein - und immer siehst Du den Turm vor Dir, am Ende des Weges.`n`n");

    switch(e_rand(1,9)){

        case 1:

        case 2:

        case 3:

        output("`@SchlieÃŸlich kannst Du Deine Hand kaum noch vor Augen sehen - doch der Turm bleibt am Horizont, als wÃ¼rde es dort niemals dunkel werden. Es hilft nichts; schwer enttÃ¤uscht nimmst Du die nÃ¤chste Abzweigung und gelangst spÃ¤t in der Nacht und vÃ¶llig Ã¼bermÃ¼det zurÃ¼ck ins Dorf. Da Du im Dunkeln nichts sehen konntest, hast Du Dir einige derbe Schrammen eingehandelt. Immerhin eine Erfahrung, die man nicht jeden Tag macht.`n`n");

        if ($session['user']['turns']>=20){

            output("`n`nDu bekommst `^".round($session['user']['experience']*0.08)."`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen WaldkÃ¤mpfe!");

            $session['user']['experience']*=1.08;

        }else if($session['user']['turns']>=13){

            output("`n`nDu bekommst `^".round($session['user']['experience']*0.07)."`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen WaldkÃ¤mpfe!");

            $session['user']['experience']*=1.07;

        }else if($session['user']['turns']>=6){

            output("`n`nDu bekommst `^".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen WaldkÃ¤mpfe!");

            $session['user']['experience']*=1.05;

        }else{

            output("Du bekommst `^".round($session['user']['experience']*0.04)."`@ Erfahrungspunkte hinzu, verlierst aber `\$".round($session['user']['hitpoints']*0.20)."`@ Lebenspunkte und alle verbliebenen WaldkÃ¤mpfe!`n");

            $session['user']['hitpoints']*=0.80;

            $session['user']['experience']*=1.04;

        }

        $session['user']['turns']=0;

        $session['user']['specialinc']="";

        break;

        case 4:

        case 5:

        case 6:

        case 7:

        case 8:

        case 9:

        output("`@SchlieÃŸlich kannst Du Deine Hand kaum noch vor Augen erkennen - doch der Turm bleibt am Horizont, als wÃ¼rde es dort niemals dunkel werden. Du willst schon an der nÃ¤chsten Abbiegung aufgeben - als der Turm beginnt, sich mit jedem weiteren Schritt um einige Hundert Meter zu nÃ¤hern! Er liegt trotz der spÃ¤ten Stunde noch immer im Hellen ...`n`n");

        output("`^Die Suche hat Dich alle verbliebenen WaldkÃ¤mpfe gekostet!");

        $session['user']['turns']=0;

        output("`n`n`@<a href='forest.php?op=turm'>Weiter.</a>", true);

        addnav("","forest.php?op=turm");

        addnav("Weiter","forest.php?op=turm");

        break;

    }

    break;



    case "turm":

    output("`@Nun stehst Du vor ihm, einem verwitterten, mit Efeu bewachsenen Wehrturm, der von den Ãœberresten einer einstigen Mauer umgeben ist. Den Eingang bildet eine schwere EichentÃ¼r, die kein Zeichen der Abnutzung aufweist. An einem Pfosten ist ein weiÃŸes Pferd mit FlÃ¼geln angebunden; ein Pegasus, der friedlich grast, und an dessem Sattel ein praller Lederbeutel hÃ¤ngt. Schaust Du nach oben, erblickst Du einen Balkon.");

    output("`n`nWas wirst Du tun?");

    output("`n`n<a href='forest.php?op=klopfen'>An die schwere EichentÃ¼r klopfen.</a>",true);

    output("`n`n<a href='forest.php?op=rufen'>Zum Balkon hinaufrufen.</a>",true);

    output("`n`n<a href='forest.php?op=stehlen'>Zu dem Pegasus gehen und den Beutel stehlen.</a>",true);

    output("`n`n<a href='forest.php?op=oeffnen'>Versuchen, die EichentÃ¼r zu Ã¶ffnen, um unbemerkt hineinzugelangen.</a>",true);

    output("`n`n<a href='forest.php?op=klettern'>Ãœber das Efeu zum Balkon hinaufklettern.</a>",true);

    output("`n`n<a href='forest.php?op=ausruhen'>Diesen auf eine besondere Art friedlichen Ort zum Ausruhen nutzen.</a>",true);

    output("`n`n<a href='forest.php?op=gehen'>Dem Ganzen den RÃ¼cken kehren - das sieht doch sehr verdÃ¤chtig aus ...</a>",true);

    addnav("","forest.php?op=klopfen");

    addnav("","forest.php?op=rufen");

    addnav("","forest.php?op=stehlen");

    addnav("","forest.php?op=oeffnen");

    addnav("","forest.php?op=klettern");

    addnav("","forest.php?op=ausruhen");

    addnav("","forest.php?op=gehen");

    addnav("Klopfen","forest.php?op=klopfen");

    addnav("Rufen","forest.php?op=rufen");

    addnav("Stehlen","forest.php?op=stehlen");

    addnav("Ã–ffnen","forest.php?op=oeffnen");

    addnav("Klettern","forest.php?op=klettern");

    addnav("Ausruhen","forest.php?op=ausruhen");

    addnav("Gehen","forest.php?op=gehen");

    break;



    case "klopfen":

    output("`@Du nimmst all Deinen Mut zusammen und klopfst an die EichentÃ¼r. Die Schritte schwerer Eisenstulpen ertÃ¶nen aus dem Innern des Turmes und werden immer lauter ...`n`n");

          switch(e_rand(1,13)){

        case 1:

        case 2:

        case 3:

        output("`@Jemand drÃ¼ckt die TÃ¼r von innen auf - doch wer es war, sollst Du nie erfahren. Die Wucht muss jedenfalls gewaltig gewesen sein, sonst hÃ¤ttest Du es Ã¼berlebt.`n`n");

        output("`\$Du bist tot!`n");

        output("`@Du verlierst `\$".round($session['user']['experience']*0.03)."`@ Erfahrungspunkte und all Dein Gold!`n");

        output("Du kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.97;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$`b".$session['user']['name']."`b `\$wurde im Wald von einer schweren EichentÃ¼r erschlagen.");

        $session['user']['specialinc']="";

        break;

        case 4:

        case 5:

        case 6:

        case 7:

        case 8:

        case 9:

        output("Zumindest in Deiner Einbildung. Als sich Dein Herzschlag wieder beruhigt, musst Du zu Deiner EnttÃ¤uschung feststellen, dass wohl niemand zu Hause ist. Du gehst zurÃ¼ck in den Wald.");

        $session['user']['specialinc']="";

        break;

        case 10:

        case 11:

        output("Die TÃ¼r Ã¶ffnet sich und Du stehst vor Bellerophontes, dem groÃŸen Heros und ChimÃ¤renbezwinger! Und tatsÃ¤chlich, auf einem Tisch im Innern siehst Du das Mischwesen liegen; halb LÃ¶we, halb Skorpion. Aber Dein Blick wird sofort wieder auf den Helden gezogen, diesen Ã¼beraus stattlichen Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trÃ¤gt eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den GÃ¶ttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen wÃ¼rde, um mich nach bestandenem Kampf zu ermorden.'");

        output("`@Er mustert Dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja wohl kaum gemeint haben, Wurm!'`n`n `@Er nimmt sich etwas Zeit und zeigt Dir, wie man sich im Wald verteidigt, damit Du Deinen Weg zum Dorf sicher zurÃ¼cklegen kannst!");

        output("`n`n`^Du erhÃ¤ltst vorrÃ¼bergehend 1 Punkt Verteidigung!");

        $session['user']['defence']++;

        $session['user']['specialinc']="";

        break;

        case 12:

        case 13:

        output("Die TÃ¼r Ã¶ffnet sich und Du stehst vor Bellerophontes, dem groÃŸen Heros und ChimÃ¤renbezwinger! Und tatsÃ¤chlich, auf einem Tisch im Innern siehst Du das Mischwesen liegen; halb LÃ¶we, halb Skorpion. Aber Dein Blick wird sofort wieder auf den Helden gezogen, diesen Ã¼beraus stattlichen Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trÃ¤gt eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den GÃ¶ttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen wÃ¼rde, um mich nach bestandenem Kampf zu ermorden.'");

        output("`@Er mustert Dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja wohl kaum gemeint haben, Wurm!'`@`n`n Er nimmt sich etwas Zeit und zeigt Dir, wie man groÃŸ und stark wird!");

        output("`n`n`^Du erhÃ¤ltst vorrÃ¼bergehend 1 Punkt Angriff!");

        $session['user']['attack']++;

        $session['user']['specialinc']="";

        break;

    }

    break;



    case "rufen":

    output("`@Du rÃ¤usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");

    switch(e_rand(1,11)){

        case 1:

        case 2:

        case 3:

        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... als Du einen Schlag im Genick spÃ¼rst. Und es ist das letzte, was Du spÃ¼rst.'");

        output("`\$ Du bist tot!`n");

        output("`@Du verlierst `\$".round($session['user']['experience']*0.03)."`@ Erfahrungspunkte und all Dein Gold!`n");

        output("Du kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.97;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$`b".$session['user']['name']."`b `\$machte durch ".($session['user']['sex']?"ihr":"sein")." lautes Rufen einen hungrigen Ork auf sich aufmerksam ...");

        break;

        case 4:

        case 5:

        case 6:

        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... als jemand zurÃ¼ckruft: `#'Nein, hier ist niemand!'");

        output("`@`n`nTja, das nenne ich ein Pech! Du findest es zwar seltsam, dass niemand zu Hause ist, schlieÃŸlich steht ja drauÃŸen der Pegasus, aber Dir bleibt wohl nichts anderes Ã¼brig, als diesen Ort zu verlassen.");

        break;

        case 7:

        case 8:

        output("`@Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... als jemand zurÃ¼ckruft: `#'Herakles, bist Du's? Nimm Dir von dem Gold in dem Beutel, es ist auch das Deine!'");

        output("`n`@Mit etwas dumpferer Stimme rufst Du zurÃ¼ck - `#'Danke!'`@ -, greifst in den Beutel auf dem RÃ¼cken des Pegasus und begibst Dich so schnell Du kannst zurÃ¼ck zum Dorf.`n`n");

        $gold = e_rand(400,900);

        output("`@Du bekommst `^".round($session['user']['experience']*0.03)." `@Erfahrungspunkte hinzu und `^".($gold * $session['user']['level'])." `@GoldstÃ¼cke!");

        $session['user']['experience']*=1.03;

        $session['user']['gold'] += ($gold * $session['user']['level']);

        break;

        case 9:

        case 10:

        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trÃ¤gt eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den GÃ¶ttern entstammt ...");

        output("`n`n`#'Sei gegrÃ¼ÃŸt, Sterblicher! Du hast groÃŸe Entbehrungen auf Dich genommen, um meinen Turm zu erreichen. DafÃ¼r hast Du Dir eine Belohnung redlich verdient! Nimm! Und berichte in aller Welt, dass ich, Bellerophontes, die ChimÃ¤re besiegt habe!'`&`n`n `@Er wirft Dir einen Beutel herunter!`n");

        $gems = e_rand(2,3);

        output("`nIn dem Beutel befanden sich `^$gems`@ Edelsteine!");

        $session['user']['gems']+=$gems;

        addnews("`@`b".$session['user']['name']."`b `@hielt heute auf dem Dorfplatz einen langen Vortrag Ã¼ber `#Bellerophontes'`@ groÃŸartige Heldentaten!");

        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `@stellt sich in die NÃ¤he des Dorfbrunnens, rÃ¤uspert sich und hÃ¤lt einen langen Vortrag Ã¼ber die Heldentaten eines gewissen `#Bellerophontes`@!')";

        db_query($sql) or die(db_error(LINK));

        break;

        case 11:

        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trÃ¤gt eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den GÃ¶ttern entstammt ...");

        output("`#Ich habe viel von Deinen Heldentaten gehÃ¶rt, ".$session['user']['name']."! Hier, dies soll Dir auf Deinen Drachenjagden behilflich sein! Nach meinem Sieg Ã¼ber die ChimÃ¤re brauche ich es nicht mehr.'`@`n`n Er Ã¼berreicht Dir sein Amulett des Lebens!");

        output("`n`n`@Du erhÃ¤ltst `^einen`@ permanenten Lebenspunkt!");

        $session['user']['maxhitpoints']++;

        $session['user']['hitpoints']++;

        break;

    }

    $session['user']['specialinc']="";

    break;



    case "stehlen":

    output("`@Ein wahrhaft edles Tier ... weiÃŸ wie Milch in der Sonne ... umgeben von einem blendenden Schimmer ...");

    switch(e_rand(1,12)){

        case 1:

        case 2:

        output("`@Aber jetzt bleibt keine Zeit fÃ¼r SentimentalitÃ¤ten! Du greifst nach dem Beutel und ... `n`n ... wirst von den Hufen des krÃ¤ftigen Tiers gegen die Mauerreste geschleudert. Erschrocken, aber froh um Dein Leben rappelst Du Dich auf und rennst davon.");

        output("`n`n`@Du bekommst `^".round($session['user']['experience']*0.04)."`@ Erfahrungspunkte hinzu, verlierst aber fast alle Deine Lebenspunkte!`n");

        $session['user']['hitpoints']=1;

        $session['user']['experience']*=1.04;

        $session['user']['reputation']-=5;

        break;

        case 3:

        case 4:

        case 5:

        case 6:

        case 7:

        case 8:

        output("`@Aber jetzt bleibt keine Zeit fÃ¼r SentimentalitÃ¤ten! Du greifst nach dem Beutel und ... `n`n ... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer hÃ¤tte das gedacht? Und je mehr du herausnimmst, desto schwerer scheint er zu werden! Gierig holst Du immer mehr heraus, und mehr, und mehr ... das Gold sprudelt nur so hervor - und hat Dich bald begraben.");

        output("`\$`n`nDu bist tot!");

        output("`n`n`@Du verlierst `\$".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte und all Dein Gold!`n");

        output("`nDu kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.95;

        $session['user']['reputation']-=5;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$`b".$session['user']['name']."`b `\$wurde in ".($session['user']['sex']?"ihrer":"seiner")." Gier unter einem riesigen Haufen griechischer GoldmÃ¼nzen begraben.");

        break;

        case 9:

        case 10:

        output("`@Aber jetzt bleibt keine Zeit fÃ¼r SentimentalitÃ¤ten! Du greifst nach dem Beutel und ... `n`n ... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer hÃ¤tte das gedacht? Und je mehr du herausnimmst, desto schwerer scheint er zu werden! Du nimmst soviel Gold mit, wie Du tragen kannst und verschwindest von diesem seltsamen Ort. Schade, dass man den Beutel nicht mitnehmen kann ...");

        $foundgold = e_rand(500,1000) * $session['user']['level'];

        output("`n`n`@Du erhÃ¤ltst `^".round($session['user']['experience']*0.03)."`@ Erfahrungspunkte und erbeutest `^".$foundgold." `@GoldstÃ¼cke!`n");

        $session['user']['gold'] += $foundgold;

        $session['user']['experience']*=1.03;

        $session['user'][reputation]-=7;

        addnews("`b`@".$session['user']['name']."`b `@gelang es, dem griechischen Heros `#Bellerophontes`^ ".$foundgold."`@ GoldmÃ¼nzen zu stehlen!");

        break;

        case 11:

        case 12:

        output("`@Aber jetzt bleibt keine Zeit fÃ¼r SentimentalitÃ¤ten! Du greifst nach dem Beutel und ... `n`n ... hÃ¤ltst kurz bevor Du ihn berÃ¼hren kannst inne. Der Turm, der Pegasus, der Beutel ... das alles kommt Dir doch sehr, sehr merkwÃ¼rdig vor. Du nimmst dieses Ereignis als wertvolle Erfahrung, von der Du noch Deinen Enkeln wirst erzÃ¤hlen kÃ¶nnen, und gehst Deines Weges.");

        output("`n`n`@Du erhÃ¤ltst `^".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte!`n");

        $session['user']['experience']*=1.05;

        addnews("`@`b".$session['user']['name']."`b `@hat ein wundervolles MÃ¤rchen Ã¼ber einen seltsamen Turm im Wald geschrieben - und `balle`b Dorfbewohner schwÃ¤rmen davon!");

        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `@freut sich, als ".($session['user']['sex']?"sie":"er")." einige Dorfbewohner Ã¼ber das MÃ¤rchen sprechen hÃ¶rt, das ".($session['user']['sex']?"sie":"er")." geschrieben hat!')";

         db_query($sql) or die(db_error(LINK));

        $session['user']['reputation']+=3;

        break;

    }

    $session['user']['specialinc']="";

    break;



    case "oeffnen":

        switch(e_rand(1,10)){

        case 1:

        case 2:

        output("`@Zu Deiner Freude bemerkst Du, dass die TÃ¼r unverschlossen ist! Vorsichtig versuchst Du sie aufzuschieben ... als sie plÃ¶tzlich ... aus ... den ... Angeln ...`n`n `#'Neeeeeeeiiiiiiin ...!'");

        output("`\$`n`nDu bist tot!");

        output("`n`@Du verlierst `\$".round($session['user']['experience']*0.02)."`@ Erfahrungspunkte und all Dein Gold!`n");

        output("`@Du kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

         $session['user']['gold']=0;

        $session['user']['experience']*=0.98;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$`b".$session['user']['name']."`b `\$wurde im Wald von einer schweren EichentÃ¼r erschlagen.");

        $session['user']['specialinc']="";

        break;

        case 3:

        case 4:

        case 5:

        case 6:

        case 7:

        case 8:

        case 9:

        case 10:

        output("`@Zu Deiner Freude bemerkst Du, dass die TÃ¼r unverschlossen ist! Vorsichtig schiebst Du sie auf ... und wirfst einen ersten Blick hinein. Du siehst einen gemÃ¼tlichen Vorraum, von dem aus eine Wendeltreppe nach oben fÃ¼hrt. Es gibt einen Holztisch, der sich unter der Last des schwerverletzten KÃ¶rper eines seltsamen Wesens biegt. Es ist halb LÃ¶we, halb Skorpion ... eine ChimÃ¤re! `n`nDas ist aber interessant ... Du gehst hinein, um Dir das Mischwesen genauer anzusehen.");

        addnav("Weiter","forest.php?op=drinnen");

        break;

    }

    break;



    case "drinnen":

    switch(e_rand(1,10)){

        case 1:

        case 2:

        case 3:

        case 4:

        case 5:

        output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wÃ¤ren ... Als Du plÃ¶tzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten Gegenstand, den Du zu fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich genommen haben. Es ist ein bronzenes Amulett ...");

        output("`n`n`@Du hast dem griechischen Heros Bellerophontes das Amulett des Lebens gestohlen!");

        $session['user']['reputation']-=3;

        $session['user']['maxhitpoints']++;

        $session['user']['hitpoints']++;

        output("`n`n`@Du erhÃ¤ltst `^".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte!");

        output("`n`n`@Du erhÃ¤ltst `^einen`@ permanenten Lebenspunkt!");

        $session['user']['experience']*=1.05;

        $session['user']['specialinc']="";

        break;

        case 6:

        case 7:

        output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wÃ¤ren ...");

        output("`@Als Du plÃ¶tzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten Gegenstand, den Du zu fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich genommen haben. Es ist ein bronzenes Amulett - das Du wÃ¼nschtest, nun lieber nicht in der Hand zu halten. Vor Dir steht der griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der ChimÃ¤ren!");

        output("`#'Wer bist Du, Wurm, dass Du es wagst, mich zu bestehlen?!' `@`n`n Er erweist sich als wahrer Meister der Rhetorik und streckt Dich kurzerhand mit seinem Flammenschwert nieder.");

        output("`\$`n`nDu bist tot!");

        output("`n`@Du verlierst `\$".round($session['user']['experience']*0.07)."`@ Erfahrungspunkte und all Dein Gold!");

        output("`n`@Du kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.93;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$Der ebenso gemeine wie unfÃ¤hige Dieb `b".$session['user']['name']."`b `\$wurde von `#Bellerophontes`\$ mit einem Flammenschwert in der Mitte zerteilt.");

        break;

        case 8:

        case 9:

        case 10:

        output("`@Der Wunde nach muss das Wesen mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wÃ¤ren ... Na, Hauptsache es ist tot. Als Du plÃ¶tzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten Gegenstand, den Du zu fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich genommen haben. Es ist ein bronzenes Amulett - das Dir aus der Hand rutscht, als Du Dich umdrehst. Vor Dir steht der griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der ChimÃ¤ren! Er reiÃŸt sein flammendes Schwert nach oben, um zum Schlag auszuholen. Jetzt ist es aus!");

        output("`#'Runter mit Dir, Du Wurm!'`@ Reflexartig tust Du, wie Dir geheiÃŸen und spÃ¼rst die Hitze des Schwertes an Deiner Wange entlangsausen. Wi-der-lich-es, grÃ¼nes ChimÃ¤renblut bespritzt Dich Ã¼ber und Ã¼ber. Dankbar schaust Du auf, Deinem Retter ins Gesicht.`n`n `#'Das wÃ¤re beinahe Dein Tod gewesen, Du schÃ¤biger Dieb. Aber diesmal sei Dir der Schrecken Lehre genug!' `@Bellerophontes ist gnÃ¤dig und jagt Dich mit FuÃŸtritten nach drauÃŸen.");

        output("`n`n`@Du erhÃ¤ltst `^".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte!");

        output("`@`n`nDu verlierst `\$2`@ Charmepunkte!");

        $session['user']['charm']-=2;

        output("`n`n`@Auf der Flucht hast Du die HÃ¤lfte Deines Goldes verloren!`n");

        $session['user']['experience']*=1.05;

        $session['user']['gold']/=2;

        break;

    }

    $session['user']['specialinc']="";

    break;



    case "klettern":

    output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. Vorsichtig beginnst Du hinaufzuklettern ...");

    switch(e_rand(1,10)){

        case 1:

        case 2:

        case 3:

        output("`@Du hast gerade die HÃ¤lfte des Weges bis zum Balkon erklommen, als Du plÃ¶tzlich mit einem FuÃŸ hÃ¤ngen bleibst. Du schÃ¼ttelst ihn, um ihn freizubekommen, doch vergebens - die Pflanze scheint Dich bei sich behalten zu wollen! In Panik verfallen, wirst Du immer hektischer, aber alle MÃ¼he wird bestraft: schon bald kannst Du Dich Ã¼berhaupt nicht mehr bewegen. Die Pflanze hÃ¤lt Dich fÃ¼r die Ewigkeit gefangen.");

        output("`\$`n`nDu bist tot!");

        output("`@`n`nDu verlierst `\$".round($session['user']['experience']*0.03)."`@ Erfahrungspunkte und all Dein Gold!");

        output("`@`n`nDu kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.97;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$`b".$session['user']['name']."`b `\$verhedderte sich im Efeu von `#Bellerophontes'`\$ Turm und ist dort verhungert.");

        break;

        case 4:

        case 5:

        case 6:

        case 7:

        case 8:

        output("`@Das ist aber einfach! Ohne Probleme erklimmst Du das Efeu bis zum Balkon. Mit einem letzten, kraftvollen Zug hievst Du Deinen edlen KÃ¶rper Ã¼ber die BrÃ¼stung und erblickst: Bellerophontes, den griechischen Heros!");

        output("`@Er tritt Dir mit gemessenen Schritten entgegen, wÃ¤hrend Du nichts empfindest als Bewunderung fÃ¼r seine groÃŸartige Erscheinung: langes, dunkles Haar, das von einem Reif gehalten wird; eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert; der ehrfurchtgebietende Blick eines Mannes, der den GÃ¶ttern entstammt ...");

        output("`@Dein Bewusstsein schwindet und Du hast einen Traum, wie keinen je zuvor. Ein groÃŸes Mischwesen aus LÃ¶we und Skorpion kommt darin vor ... `n`nAls Du wieder erwachst, liegst Du irgendwo im Wald und schwelgst noch immer - mit genauer Erinnerung an Bellerophontes' Ã¤sthetische Kampftaktik!");

        output("`n`n`@Da Du von nun an anmutiger kÃ¤mpfen wirst, erhÃ¤ltst Du `^2`@ Charmepunkte!");

        $session['user']['charm']+=2;

        output("`n`n`@Du erhÃ¤ltst vorrÃ¼bergehend `^1`@ Punkt Angriff!");

        $session['user']['attack']++;

        break;

        case 9:

        case 10:

        output("`@Das ist aber einfach! Ohne Probleme erklimmst Du das Efeu bis zum Balkon. Mit einem letzten, kraftvollen Zug hievst Du Deinen edlen KÃ¶rper Ã¼ber die BrÃ¼stung und erblickst: Bellerophontes, den griechischen Heros!");

        output("`@Er tritt Dir mit gemessenen Schritten entgegen, wÃ¤hrend Du nichts empfindest als Bewunderung fÃ¼r seine groÃŸartige Erscheinung: langes, dunkles Haar, das von einem Reif gehalten wird; eine strahlend weiÃŸe Robe, die das Zeichen des Poseidon ziert; der ehrfurchtgebietende Blick eines Mannes, der den GÃ¶ttern entstammt ...");

        output("`@Kam erst der Schlag und dann der Flug? Oder war es umgekehrt?");

        output("`\$`n`nDu bist tot!");

        output("`n`n`@Du verlierst `\$".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte und wÃ¤hrend des Fluges all Dein Gold!`n");

        output("`n`@Du kannst morgen weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['gold']=0;

        $session['user']['experience']*=0.95;

        addnav("TÃ¤gliche News","news.php");

        addnews("`\$Es wurde beobachtet, wie `b".$session['user']['name']."`b`\$ aus heiterem Himmel herab auf den Dorfplatz fiel und beim Aufprall zerplatzte.");

        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `\$fÃ¤llt aus heiterem Himmel herab auf den Dorfplatz und zerplatzt beim Aufprall!')";

        db_query($sql) or die(db_error(LINK));

        break;

    }

    $session['user']['specialinc']="";

    break;



    case "ausruhen":

    output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und schlieÃŸt die Augen ...`n`n");

    switch(e_rand(1,10)){

        case 1:

        case 2:

        case 3:

        output("`@Als Du wieder erwachst, liegst Du noch immer im Gras - aber der Turm ist verschwunden. Verwundert stehst Du auf und verlÃ¤sst die friedliche Lichtung. Du musst wohl getrÃ¤umt haben.`n`n");

        $turns3 = e_rand(4,9);

        output("`^Ãœber die MaÃŸen ausgeruht erhÃ¤ltst Du $turns3 WaldkÃ¤mpfe hinzu!");

        $session['user']['turns']+=$turns3;

        break;

        case 4:

        case 5:

        output("`@Dein Schlaf ist unruhig ... und Deine TrÃ¤ume sind es auch ... Als Du wieder erwachst, liegst Du noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein Satz, von dem Du getrÃ¤umt haben musst: `#'Hier ist es zu gefÃ¤hrlich ...'`n`n");

        output("`^Irgendjemand scheint Dir 30 GoldstÃ¼cke zugesteckt zu haben ...");

        $session['user']['gold'] += 30;

        break;

        case 6:

        case 7:

        case 8:

        output("`@Dein Schlaf ist unruhig ... und Deine TrÃ¤ume sind es auch ... Als Du wieder erwachst, liegst Du noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein unwohles GefÃ¼hl in der Magengegend.`n`n");

        output("`^Man hat Dich im Schlaf um all Dein Gold erleichtert!");

        $session['user']['gold'] = 0;

        break;

        case 9:

        case 10:

        output("`@Dein Schlaf ist ruhig ... und Deine TrÃ¤ume sind es auch ... Als Du wieder erwachst, liegst Du noch immer im Gras - und jemand steht vor Dir. Er hat langes, dunkles Haar, das von einem Reif gehalten wird, und trÃ¤gt eine strahlendweiÃŸe Robe, die das Zeichen des Poseidon ziert. Mit dem ehrfurchtgebietenden Blick eines Mannes, der den GÃ¶ttern entstammt sagt er:`n");

             output("`#'Ich weiÃŸ, wer Du bist, ".$session['user']['name'].". Im Schlaf hast Du mir alles Ã¼ber Dich erzÃ¤hlt, was ich wissen wollte. Deine grÃ¶ÃŸte Angst gilt dem Drachen ... nun, wenn es weiter nichts ist: Trink von diesem Ambrosia ...'`n`n");

        output("`@Bereits nach einem winzigen Schluck schlÃ¤fst Du wieder ein ... Und als Du aufwachst, befindest Du Dich allein auf einer leeren Lichtung.`n`n");

        output("`n`n`@Du erhÃ¤ltst `^2`@ permanente Lebenspunkte!");

        $session['user']['maxhitpoints']+=2;

        $session['user']['hitpoints']+=2;

        addnews("`#Bellerophontes`@ lieÃŸ ".$session['user']['name']." `@am Trank der GÃ¶tter nippen!");

        break;

    }

    $session['user']['specialinc']="";

    break;



    case "gehen":

    output("`@Du verlÃ¤sst diesen seltsamen Ort und kehrst in den Wald zurÃ¼ck. Eine vernÃ¼nftige Entscheidung! Aber Dein Entdeckerherz fragt sich, ob `bVernunft`b fÃ¼r einen Abenteurer die beste aller Eigenschaften ist ...");

    $session['user']['specialinc']="";

    break;



    default:

    output("`@Vor Dir liegt ein langer, gerader Waldweg, Ã¼ber dem die BÃ¤ume zu dicht wachsen, als dass man reiten kÃ¶nnte. Es ist schon seit langem nichts Aufregendes mehr passiert - da erblickst Du, als Du eine Kreuzung erreichst, plÃ¶tzlich etwas am Ende des ausgetrampelten Pfades: einen Turm im dunstigen Zwielicht des Waldes.`n`n");

    output("Was wirst Du tun?`n`n <a href='forest.php?op=weiter'>Weitergehen und versuchen, den Turm zu finden,</a>`n oder <a href='forest.php?op=abbiegen1'>hier abbiegen und den Weg verlassen.</a>`n", true);

    addnav("","forest.php?op=weiter");

    addnav("","forest.php?op=abbiegen1");

    addnav("Weitergehen","forest.php?op=weiter");

    addnav("Abbiegen","forest.php?op=abbiegen1");

    break;

}

if ($session['user']['turns']<0) $session['user']['turns']=0;

?>

