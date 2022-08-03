<?php
/*Ort Moor
geschrieben für Arda
www.arda-logd.de
Umland der Stadt Zylyma
von Narjana*/

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'kloster':
        {
                page_header("Verfallenes Kloster");
                output("`c`bVerfallenes Kloster 1`c`b`n
                                Du findest das alte, verlassenes Gemäuer, dessen stellenweise eingebrochene Mauern noch lange nachdem es aufgegeben wurde in den verhangenen Himmel emporragen. Es sieht nicht gerade einladend aus, die gähnenden, leeren Fenster, die eisernen Beschläge auf der Tür von Rost zerfressen...`n");

                viewcommentary("kloster","sagt:",15);

                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("zum alten Friedhof","moor.php?op=friedhof");
                addnav("Inneres des Klosters","moor.php?op=inner");
                addnav("ins Mausoleum","moor.php?op=mauso");
                break;
        }
        case 'inner':
        {
                page_header("Inneres Kloster");
                output("`c`bInneres Kloster`c`b`n
                                Düster ist es im inneren des Klosters. Kreaturen huschen, flattern oder kriechen davon als du dich dort einfindest. Es sind Rissen in den alten Wänden, auf dem Boden ist Erde festgetrampelten und die Vegetation scheint sich diesen Ort langsam aber stetig zurückerobern. Es wirkt als wäre hier alles vor langer Zeit vergessen worden.
                                Oder es hat nur jemand vergessen sich darum zu kümmern. Obwohl zwischen den alten zersplitterten Bleifenstern und dem Efeu sogar einige Pflanzen Wachsen, die sorgfältiger Pflege bedürfen. Dort - wo man sonst den Altar vermutet hätte stand der Rohbau einer Statue, aber unvollendet. Der künstler hatte wohl vergessen, wen er darstellen wollte.
                                Ob man es glaubt oder nicht, dieser Ort ist einem Gott geweiht und Ellalith, der Gott von Verwirrung und vergessen wird hier gebührend vereehrt und gehuldigt.");

                viewcommentary("inner","flüstert:",15);

                addnav("Zum Kloster","moor.php?op=kloster");
                addnav("kleiner Nebenraum","moor.php?op=nebenraum");
                break;
        }
        case 'nebenraum':
        {
                page_header("Kleiner Nebenraum");
                output("`c`bKleiner Nebenraum`c`b`n
                                        Obwohl es hier nicht weniger unordentlich ist, als im Hauptkomplex scheint hier doch noch jemand zu wohnen. Zumindest gibt es eine Lagerstätte und ein paar Habseligkeiten stehen herum. Ein seltsamer Geruch hängt in der Luft,
                                        und wabert auch zum Kirchenschiff herüber. Es ist der Raum des obersten Vergessenspriester.");
                addnav("zurück");
                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("alter Friedhof","moor.php?op=friedhof");
                break;
                }
        case 'friedhof':
        {
                page_header("Alter Friedhof");
                output("`c`b`öAl`Tt`Ger `7Fri`Ged`Tho`öf`n`b`n

`7E`Gs `Tist schon lange, lange her, dass hier jemand bestattet wur`Gd`7e.`n
`7V`Gi`Tele Grabsteine  sind beschädigt, umgestoßen oder zerbroch`Ge`7n,`n
`7a`Gu`Tf keinem ist noch irgendeine Inschrift zu erkenn`Ge`7n.`n
`7E`Gs `Tlebt kaum noch ein Wesen auf Arda, dass sich überhaupt noch an diese Tot`Ge`7n`n
`7e`Gr`Tinnern wür`Gd`7e.`n
`7V`Go`Tr allen Dingen gibt es hier zwischen den überwuchterten Pfad`Ge`7n`n
`7g`Ge`Tlegentlich größere offene Flächen - ohne Bebauung, ohne Markieru`Gn`7g.`n
`7D`Gi`Te alten Massengräber aus Zeiten, da schreckliche Krankheiten Ardenien heimsucht`Ge`7n.`n
`7S`Ge`Tlbst die Pflanzen die hier wachsen sind verkrümmt, kränklich oder gift`Gi`7g,`n
`7g`Ge`Trade so als ob der Boden selbst alles Gedeihen verhinde`Gr`7t.`n
`7N`Gu`Tr wenige können sogar jetzt noch die stille Wut und Verzweiflung spür`Ge`7n,`n
`7d`Gi`Te stets von diesem Ort aufstei`Gg`7t.`n
`7N`Ga`Ttürlich wollte niemand einen solchen Ort direkt außerhalb der Stadt liegen hab`Ge`7n.`n
`7E`Gi`Tn stiller Ort mit schlechtem Ruf kann natürlich alles Mögliche verberg`Ge`7n...`n
`c `n");

                viewcommentary("friedhof","sagt:",25);
                addnav("zurück");
                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("kleiner Teich","moor_unten.php?op=teich");
                addnav("verfallenes Kloster","moor.php?op=kloster");
  //              addnav("Zwischen den Gräbern wandern","moor.php?op=wander");
                addnav("ins Mausoleum","moor.php?op=mauso");
                break;
        }
        case 'mauso':
        {
                page_header("Das Mausoleum");
                output("`c`öD`Ta`Gs `7Ma`&us`7ol`Ge`Tu`öm`n`n

`öD`Ta`Gs dunkelgraue, marmorne Gebäude ist nicht sehr groß, aber beeindruckend detaillie`Tr`öt.`n
`öU`Tn`Gd beeindruckend gutem Zustand noch dazu, im Kontrast zu seiner Umgebu`Tn`ög.`n
`öS`Tt`Gatuen Säumen den Eingang, lebensgroß und beinahe lebensec`Th`öt.`n
`öM`Tu`Gster und Zierwerk bilden kunstvoll angedeutete Fenst`Te`ör,`n
`ös`Tt`Geinerne Skelette und sagenhafte Kreaturen starren und grinsen vom Da`Tc`öh.`n
`öI`Tm `GInneren führt eine perfekt erhaltene Treppe  abwär`Tt`ös,`n
`öd`To`Gch trotz der eindrucksvollen Steinmetzarbe`Ti`öt,`n
`öd`Te`Gr selbst die Zeit nichts anhaben zu können schei`Tn`öt,`n
`ög`Ti`Gbt es hier nichts als Staub und das eine oder andere Blatt, das der Wind hereintr`Tu`ög.`n
`öB`Te`Gi näherem hinsehen könnte allerdings in der unteren Eta`Tg`öe,`n
`ön`Te`Gben einem Relief, das wohl eine schreckliche Hungersnot darstel`Tl`öt,`n
`öh`Ta`Glb versteckt hinter der bedrohlichen Statue eines Dämo`Tn`ös,`n
`öe`Ti`Gn Riss in der Wand auffall`Te`ön.`n
`öD`Ta`Ghinter: gähnende Dunkelhe`Ti`öt.`n
`öM`Tö`Gchte man wirklich in die Erde, in der so viele Pesttote verscharrt wurd`Te`ön?`n
`c`n");

                viewcommentary("mauso","sagt:",25);
                addnav("zurück");
                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("alter Friedhof","moor.php?op=friedhof");
//                addnav("Gräber betrachten");
//                addnav("Grabräuberei","moor.php?op=grabrauber");
                addnav("Riss in der Wand","katakomp.php");
                break;
        }
/*        case 'grabrauber':
        {
                if($session['user']['grabraub']){
                $out.=("Du hast heute schon mehr oder weniger erfolgreich versucht die Gräber zu plündern. Du solltest dein Glück nicht überstrapazieren.`n");
                addnav("Zurück","moor.php?op=mauso");
                } else {
                        page_header("Das Mausoleum");
                        output ("Du näherst dich einem der Gräber - vielleicht findest du ja was. Gold, Edelsteine - irgendwas worauf die Göttin Rui noch keinen Anspruch
                                                erhoben hat. A propos - du solltest zu Ellalith beten, dass diese gerade abgelenkt ist und nicht bemekrt, was du vorhast.");
                        $session['user']['grabraub']='1';

                        switch (e_rand(1,10)){

                                case 1:
                                        output ("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Aber bevor du auch nur im Ansatz reagieren kannst, steht mit einem
                                                        Male eine dünne, dunkel gekleidete Gestalt hinter dir. Eine Hand landet sachte auf deiner Schulter. `M\"Ungezogen...\", `&hörst du ein heiseres Flüstern,
                                                        Dass dir Gänsehaut über den Rücken jagt. So schnell reißt du dich los und läufst davon, dass dein mantel an Ort und stelle verbleibt. Vor angst zitternd wirst
                                                        du heute wohl weniger Kämpfen können.");
                                        $session['user']['turns']-=2;
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        break;
                                case 2:
                                        output ("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Aber bevor du auch nur im Ansatz reagieren kannst, steht mit einem
                                                        Male eine dünne, dunkel gekleidete Gestalt hinter dir. Eine Hand landet sachte auf deiner Schulter. `M\"Ungezogen...\", `&hörst du ein heiseres Flüstern,
                                                        Dass dir Gänsehaut über den Rücken jagt. Du zitterst vor angst und kannst dich nicht bewegen. `M\"Weißt du was? Ich nehm dich einfach mal mit..\"spricht die Stimme
                                                        die, wie du nun erkennst der Göttin Rui gehört weiter. Das Licht vor deinen Augen flimmert und es wird schwarz rundum. Du bist tot.");
                                        $session['user']['alive']=false;
                                        $session['user']['hitpoints']=0;
//                                        $session['user']['experience']*=1.3;
                                        addnav("Tägliche News","news.php");
                                        break;
                                case 3:
                                        output ("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. In erwartung der kommenden Schätze holst du tief luft, wodurch es anfängt, sich in deinem Kopf zu drehen.
                                                        Ein leises Stöhnen kommt über deine Lippen, als die Jahrhunderte alte Substanz sich durch deine Adern den Weg zum Gehirn brennt. Ein zittern fährt durch deine Glieder und in dir entfaltet sich");
                                        output ("Eine Unbändige Macht, die den Weg nach außen sucht - jetzt kannst du wohl jedem Wesen dieser Welt entgegentreten.");
                                        $session['user']['attack']*1.10;
                                        $session['user']['defense']*1.10;
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        addnav("ins Mausoleum","moor.php?op=mauso");
                                        break;
                                case 4:
                                        output ("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. In erwartung der kommenden Schätze holst du tief luft, wodurch es anfängt, sich in deinem Kopf zu drehen.
                                                        Ein leises Stöhnen kommt über deine Lippen, als die Jahrhunderte alte Substanz sich durch deine Adern den Weg zum Gehirn brennt. Ein zittern fährt durch deine Glieder und in dir entfaltet sich");
                                        output ("Ein zittern läuft durch deine Muskeln und du fühlst wie Schwäche deine Knie weich macht. Dein Kopf ist völlig benebelt - du solltest aufpassen, mit was für Kreaturen du heute noch Kämpfst");
                                        $session['user']['attack']*0.90;
                                        $session['user']['defense']*0.90;
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        addnav("ins Mausoleum","moor.php?op=mauso");
                                case 5:
                                        output("So sehr du dich auch anstrengst, die alte Grabplatte bewegt sich keinen Milimeter. Fustriert schlägst du auf die Inschrift, wo der alte, verwitterte Name dich höhnisch anzugrinsen scheint. Du wirst
                                                        unverrichteter Dinge abziehen müssen");
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        addnav("ins Mausoleum","moor.php?op=mauso");
                                        break;
                                case 6:
                                        output("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Genauso wie ein dumpfes, unheilvolles Knurren. Tja, diesen Ghul hier aufzuwecken gehört wohl nicht zu deinen Glanzleistungen.
                                                        Jetzt wirst du um dein Leben Kämpfen müssen. Aber vielleicht lässt er, wenn du Glück hast - ja doch noch einen Gewinn springen");

                                                $badguy = array(
                                                "creaturename"=>"`sSchlecht gelaunter Ghul`0",
                                                "creaturelevel"=>$session[user][level]+30,
                                                "creatureweapon"=>"`7Halb verweste Klauen",
                                                "creatureattack"=>$session['user']['attack']+5,
                                                "creaturedefense"=>$session['user']['defence']+5,
                                                "creaturehealth"=>round($session['user']['maxhitpoints']*2.05,0),
                                                "diddamage"=>7);
                                                $session['user']['badguy']=createstring($badguy);
                                                $battle=true;

                                //Battle with Ghul


                                                $battle=true;

                                                if ($battle) {
                                                        include("battle.php");
                                                        if ($victory){
                                                                $badguy=array();
                                                                $session['user']['badguy']="";
                                                                output("`n`9Nur knapp schaffst du es das wiederwärtige Wesen zurück in sein Grab zu schicken.");
                                //Navigation
                                                                addnav("Weiter","moor.php?op=mauso.php");
                                                                if (rand(1,4)==1) {
                                                                        $gem_gain = rand(1,3);
                                                                        $gold_gain = rand($session['user']['level']*10,$session['user']['level']*15);
                                                                        output("Du findest $gem_gain Edelsteine und $gold_gain Gold.`n`n");
                                                                }
                                                        $exp = round($session['user']['experience']*0.03);
                                                        output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
                                                        $session['user']['experience']+=$exp;
                                                        $session['user']['gold']+=$gold_gain;
                                                        $session['user']['gems']+=$gem_gain;
                                                        } elseif ($defeat){
                                                                $badguy=array();
                                                                $session['user']['badguy']="";
                                                                output("`n`9Du hättest dich wohl besser niemals in diese Gruft gewagt. Der Gühl freut sich allerdings über ein Frühstück. Du verlierst 5% Deiner Erfahrung.`0");
                                                                addnav("Tägliche News","news.php");
                                                                addnews($session['user']['name']."`4 diente einem Guhl als Zwischenmahlzeit.");
                                                                $session['user']['alive']=false;
                                                                $session['user']['hitpoints']=0;

                                                                $session['user']['gems']=round($session['user']['gems']*0.5);
                                                                $session['user']['experience']=round($session['user']['experience']*.95,0);
                                                        } else {
                                                                fightnav(true,true);
                                                        }
                                                }

                                        break;
                                case 7;
                                        output("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Vor deinen Gierigen Augen breiten sich Klingende Münze so wie einige Juwelen aus.
                                                        Hat sich also doch gelohnt");
                                        $session['user']['gold']+=2500;
                                        $session['user']['gems']+=10;
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        addnav("ins Mausoleum","moor.php?op=mauso");

                                        break;
                                case 8;
                                        output("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Aber deine hoffnungsvoll glänzenden Augen können leider nicht viel entdecken. Nur ein
                                                        paar wenige Goldmünzen liegen in dem Grab");
                                        $session['user']['gold']+=50;
                                        addnav("Sümpfe der Verlorenen","moor.php");
                                        addnav("alter Friedhof","moor.php?op=friedhof");
                                        addnav("Riss in der Wand","katakomp.php");
                                        addnav("ins Mausoleum","moor.php?op=mauso");

                                        break;
                                case 9;
                                        output("Die alten Steinplatten knirschen unter deinen Händen, staub weht hervor. Aber leider findest du - absolut nichts");
                                        break;
                        }
                }
                break;

        }*/
 /*       case 'wander':
        {
                page_header("Friedhofswege");
                output("`c`bFriedhofswege`c`b`n
                                Eh du dich versiehst streifst du zwischen Gräbern umher. Manche sind mit einfachen Holzmarkierungen gekennzeichnet, viele schon lange verfallen, andere ziert eine steinerne Platte oder sogar ein Monument. Goldverzierte Inschriften, Gravuren, Jahreszahlen, Namen, Schriftzeichen die dir bekannt oder fremd sind, all dies ziert deinen Weg zwischen Bäumen und Sträuchern. Manchmal sind Tiere zu hören, doch was immer hier haust ist ganz klar zurückhaltender als die Kreaturen des Waldes.
 `nWohin führt dich wohl deine Wanderung zwischen den Überresten der Vergangenheit...?`n");

                viewcommentary("wander","sagt:",25);

                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("zum alten Friedhof","moor.php?op=friedhof");
                if ($session[user][superuser]>1){ addnav("Mausoleum","moor.php?op=mauso");
                }
                addnav("kleiner Teich","moor_unten.php?op=teich");
                break;
        }*/
        case 'lights':
        {
                page_header("Irrlichter");
                output("`cIrrlichter`n
                Wider aller Vernunft folgst du den Lichtern. Sie bleiben immer gerade außer Reichweite, tanzende Flammen die sich auf stillen Wassern spiegeln, an anderen Stellen einfaches, formloses Glühen in fahlem Blau, bleichem Grün oder kränklichem, gräulichem Weiß. Jetzt wo du dir sicher bist dass du dich nur noch weiter verirren wirst ist es sicher Zeit umzukehren...

                `n");

                viewcommentary("lights","sagt:",25);
                addnav("zurück");
                addnav("Sümpfe der Verlorenen","moor.php");
//                addnav("Nachtschwärmer");
//                addnav("Tanz der Irrlichter");
                break;
        }
        default:
        {

                page_header("Sümpfe der Verlorenen");

                output("`c`uS`ïü`um`Up`uf`ïe `Td`ue`Tr `RV`òe`Gr`Rl`òo`Rr`7e`Rn`Ge`Rn`n`n`c

`uGe`ïrü`uch`Ute `uvo`ïn e`Tiner Stadt, in der das Glücksspiel und das Laster zu Hause sind, haben dich neugierig gemacht und so folgst du einem schmalen Pfad, der immer kleiner zu werden scheint. Schon jetzt ist der Geruch von Moder und Verwesung allgegenwärtig, als du ein altes, verwittertes Schild entdeckst. Die Nachricht ist schon lange verblasst und \"`RAchtung... Wander..fressen... Sumpf..Wesen...`T\" ist alles, was du noch entziffern kannst. Unsicher verharrst du einen Moment, doch ist deine Neugier stärker. Vorsichtig gehst du weiter, bis der Gestank fast unerträglich wird und der Pfad sich im Nichts auflöst. Vor dir erstrecken sich die `uS`ïü`um`Up`uf`ïe `Td`ue`Tr `RV`òe`Gr`Rl`òo`Rr`7e`Rn`Ge`Rn `Tund du fragst dich, ob die Legenden wahr sind. Es scheint, als würde die Sonne diesen Bereich schon aus Protest meiden, ist alles in ein zwielichtiges, grünes Licht getaucht, hier und da tanzen bunte Irrlichter, doch ist es nicht ratsam, ihnen zu folgen. Es gibt auch keinen Pfad mehr, der dir helfen könnte, einen sicheren Weg hier durch zu finden. Überall sind versteckte Löcher und es scheint, als würde der Schlamm versuchen, nach dir zu schnappen und dich in die Ti`ïef`uen`U zu `uzi`ïeh`uen.`0`n`n");


                viewcommentary("moor","sagt",25);


                addnav("nach Zylyma","zylyma-reise2.php");
                addnav("verfallenes Kloster","moor.php?op=kloster");
                addnav("zum alten Friedhof","moor.php?op=friedhof");
                addnav("zur Wegkreuzung","kreuzung.php");
                addnav("Folge den Irrlichtern","moor.php?op=lights");
                addnav("kleiner Teich","moor_unten.php?op=teich");
  //              addnav("Friedhofswege","moor.php?op=wander");
//                addnav("in die Berge",""); (noch nicht aktiv)
//                addnav("Strand","sanelastrand.php");
//                addnav("Sümpfe der Verborgenen"); (noch nicht aktiv)

                break;
     }

}
page_footer();

?> 