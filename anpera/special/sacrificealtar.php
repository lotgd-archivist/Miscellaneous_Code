
ï»¿<?php



// 21102005



/* *******************

Altar of Sacrifice

Written by TheDragonReborn

    Based on Forest.php



Translation by Lendara Mondkind (Lisandra)



cleanup by anpera



******************* */



if ($_GET['op']=="Sacrifice"){

    if ($_GET['type']=="Yourself"){

        $session['user']['specialinc']="";

        output("`@Du legst Deine Sachen ab und legst Dich auf den Altar. Als Du Dein/e/n ".$session['user']['weapon']." erhebst, ");

        output("denkst Du an die Liebe. Dann, ohne weitere VerzÃ¶gerung, ");

        output("nimmst du dir mit ".$session['user']['weapon']." das Leben. Als sich die Dunkelheit Deiner bemÃ¤chtigt ");

        switch(e_rand(1,15)){

            case 1:

            case 2:

            case 3:

            output("Denkst Du, dass Du genug getan hast um die GÃ¶tter zu besÃ¤nftigen, damit diese die Welt");

            output("zu einem besseren Ort machen...`n`n");

            output("Leider wirst Du nicht nicht dabei sein, um es zu sehen. Du bist tot!");

            output("`n`n`^Du bist tot!`n");

            output("Du verlierst all Dein Gold!`n");

            output("Du verlierst 5% Deiner Erfahrung.`n");

            output("Du kannst morgen wieder weiterspielen.");

            $session['user']['alive']=0;

            $session['user']['hitpoints']=0;

            $session['user']['experience']*=0.95;

            $session['user']['gold']=0;

            addnav("TÃ¤gliche News","news.php");

            if (strtolower(substr($session['user']['name'],-1))=="s"){

                addnews($session['user']['name']."' KÃ¶rper wurde auf einem Altar in den WÃ¤ldern gefunden.");

            }else{

                addnews($session['user']['name']."'s KÃ¶rper wurde auf einem Altar in den WÃ¤ldern gefunden.");

            }

            break;

             case 4:

             case 5:

            output("siehst Du wie der Himmel rot wird aufgrund des Zorns der GÃ¶tter. Sie sind nicht so leichtglÃ¤ubig wie Du gedacht hast.");

            output("Sie wissen warum Du das getan hast. Niemand, der sich selbst respektiert, wÃ¼rde einer Selbstopferung zustimmen, wenn er ");

            output("nicht denken wÃ¼rde, dass er etwas dadurch erhÃ¤lt. Ein gewaltiger Blitz kommt vom Himmel herab und ");

            output("trifft Deinen toten KÃ¶rper. Dabei nimmt der Blitz einige Deiner Angriffs- und VerteidigungsfÃ¤higkeiten mit. Nun, ");

            output("das ist es was Du dafÃ¼r erhÃ¤ltst, dass Du die GÃ¶tter betrÃ¼gen wolltest.");

            output("`n`n`^Du bist gestorben!`n");

            output("Du verlierst all Dein Gold!`n");

            output("Du verlierst 10% Deiner Erfahrung!`n");

            output("Du verlierst 1 Punkt in Angriff und Verteidigung!`n");

            output("Du kannst morgen wieder weiterspielen.");

            $session['user']['alive']=0;

            $session['user']['hitpoints']=0;

            $session['user']['experience']*=0.95;

            $session['user']['donation']+=2;

            $session['user']['gold']=0;

            if ($session['user']['attack']>2)$session['user']['attack']--;

            if ($session['user']['defence']>2)$session['user']['defence']--;

            addnav("TÃ¤gliche News","news.php");

            if (strtolower(substr($session['user']['name'],-1))=="s"){

                addnews($session['user']['name']."'s Ãœberbleibsel wurden verkohlt auf einem Altar gefunden.");

            }else{

                addnews($session['user']['name']."'s Ãœberbleibsel wurden verkohlt auf einem Altar gefunden.");

            }

            break;

            case 6:

            case 7:

            case 8:

            case 9:

            output("siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmÃ¼tigen alten Mannes.`n`n");

            output("\"`#".($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").",\"`@ sagt er, \"`#Du hast mir die hÃ¶chste");

            output("Opferung erbracht und dafÃ¼r werde ich Dich belohnen.`@\"`n`n");

            output("Er erhebt seine Hand und fÃ¤hrt sie an der gesamten LÃ¤nge Deines KÃ¶rpers entlang. Er hÃ¤lt sie ganz knapp vor der BerÃ¼hrung");

            output("mit Dir. Du fÃ¼hlst wie eine warme Energie durch Dich wandert und alles fÃ¤ngt an klarer zu werden. Du stehst auf ");

            output("und erkennst, dass die Wunde von Deine/r/m ".$session['user']['weapon']." komplett geheilt ist. Du schaust Dich nach dem ");

            output("alten Mann um, doch er war verschwunden.`n`n");

            output("Du nimmst Deine Sachen wieder auf und machst Du bereit weiterzugehen. Als Du an einer WasserpfÃ¼tze vorbei gehst, siehst Du zufÃ¤llig ");

            output("in sie und siehst Dein Spiegelbild. Du siehst wesentlich ".($session['user']['sex']?"schÃ¶ner":"angenehmer"));

            output("aus als je zuvor. Es muss ein Geschenk der GÃ¶tter sein.`n`n");

            output("`^Du erhÃ¤ltst 2 Charmepunkte!");

            $session['user']['charm']+=2;

            break;

            case 10:

            case 11:

            case 12:

            case 13:

            output("siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmÃ¼tigen alten Mannes.`n`n");

            output("\"`#".($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").",\"`@ sagt er, \"`#Du hast mir die hÃ¶chste");

            output("Opferung erbracht und dafÃ¼r werde ich Dich belohnen.`@\"`n`n");

            output("Er erhebt seine Hand und fÃ¤hrt sie an der gesamten LÃ¤nge Deines KÃ¶rpers entlang. Er hÃ¤lt sie ganz knapp vor der BerÃ¼hrung");

            output("mit Dir. Du fÃ¼hlst wie eine warme Energie durch Dich wandert und alles fÃ¤ngt an klarer zu werden. Du stehst auf ");

            output("und erkennst, dass die Wunde von Deine/r/m ".$session['user']['weapon']."  komplett geheilt ist. Du schaust Dich nach dem ");

            output("alten Mann um, doch er war verschwunden.`n`n");

            output("Als Du den Altar verlÃ¤sst, fÃ¤llt Dir auf, dass Du mehr Lebenspunkte als zuvor hast.");

            output("`n`n`^Deine maximalen Lebenspunkte sind `bpermanent`b um 1 Punkt gestiegen!");

            $session['user']['maxhitpoints']++;

            break;

            case 14:

            case 15:

            output("siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmÃ¼tigen alten Mannes.`n`n");

            output("\"`#".($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").",\"`@ sagt er, \"`#Du hast mir die hÃ¶chste");

            output("Opferung erbracht und dafÃ¼r werde ich Dich belohnen.`@\"`n`n");

            output("Er erhebt seine Hand und fÃ¤hrt sie an der gesamten LÃ¤nge Deines KÃ¶rpers entlang. Er hÃ¤lt sie ganz knapp vor der BerÃ¼hrung");

            output("mit Dir. Du fÃ¼hlst wie eine warme Energie durch Dich wandert und alles fÃ¤ngt an klarer zu werden. Du stehst auf ");

            output("und erkennst, dass die Wunde von Deine/r/m ".$session['user']['weapon']."  komplett geheilt ist. Du schaust Dich nach dem ");

            output("alten Mann um, doch er war verschwunden.`n`n");

            output("Als Du den Altar verlÃ¤sst, fÃ¤llt Dir auf, dass Deine Muskeln grÃ¶ÃŸer geworden sind.");

            output("`n`n`^Du erhÃ¤ltst +1 Angriff und +1 Verteidigung!");

            $session['user']['attack']++;

            $session['user']['defence']++;

            break;

        }



    }elseif ($_GET['type']=="Creature"){

        output("Du entscheidest Dich eine unglÃ¼ckselige Kreatur an die GÃ¶tter zu opfern. Darum gehst Du in den Wald und schaust Dich nach einem passenden Geschenk um.`n");

        $session['user']['turns']--;

        $battle=true;

        if (e_rand(0,2)==1){

            $plev = (e_rand(1,5)==1?1:0);

            $nlev = (e_rand(1,3)==1?1:0);

        }else{

            $plev=0;

            $nlev=0;

        }

        if ($_GET['Difficulty']=="Weak"){

            $nlev++;

            output("`\$Du gehst in ein Gebiet des Waldes, von dem Du weisst, dass sich dort eher leichtere Gegner aufhalten.`0`n");

        }

        if ($_GET['Difficulty']=="Strong"){

            $plev++;

            output("`\$Du gehst in ein Gebiet des Waldes, welches Kreaturen aus Deinen AlptrÃ¤umen enthÃ¤lt, in der Hoffnung, dass Du ein verletztes findest.`0`n");

        }

        $targetlevel=($session['user']['level']+$plev-$nlev);

        if ($targetlevel<1) $targetlevel=1;

        $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";

        $result = db_query($sql) or die(db_error(LINK));

        $badguy = db_fetch_assoc($result);

        $expflux = round($badguy['creatureexp']/10,0);

        $expflux = e_rand(-$expflux,$expflux);

        $badguy['creatureexp']+=$expflux;

        //make badguys get harder as you advance in dragon kills.

        $badguy['playerstarthp']=$session['user']['hitpoints'];

        $dk = 0;

        while(list($key, $val)=each($session['user']['dragonpoints'])) {

            if ($val=="at" || $val=="de") $dk++;

        }

        $dk+=(int)(($session['user']['maxhitpoints']-($session['user']['level']*10))/5);

        $atkflux = e_rand(0, $dk);

        $defflux = e_rand(0, ($dk-$atkflux));

        $hpflux = ($dk - ($atkflux+$defflux)) * 5;

        $badguy['creatureattack']+=$atkflux;

        $badguy['creaturedefense']+=$defflux;

        $badguy['creaturehealth']+=$hpflux;

        if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;

        $badguy['diddamage']=0;

        $session['user']['badguy']=createstring($badguy);

        $session['user']['specialinc']="sacrificealtar.php";



    }elseif ($_GET['type']=="Edelstein"){

           switch(e_rand(1,3)){

            case 1:

            case 2:

            output("`#Du legst einen Deiner hart verdienten Edelsteine auf den Altar und wartest ab was passiert. Aber es passiert nichts, gar nichts. Du bist natÃ¼rlich schlau und versuchst ein paar Tricks wie ");

            output("im Busch verstecken, eine Art Regentanz, zu Edelstein und Altar sprechen, beten und PurzelbÃ¤ume schlagen, aber trotz Deiner BemÃ¼hungen... es passiert nichts.`nAlso beschlieÃŸt Du den Edelstein wieder mitzunehmen und stattdessen ein paar Monster zu tÃ¶ten.");

            output("`nDu verlierst einen Waldkampf wegen Deiner versuchten Tricks.");

            $session['user']['turns']--;

            addnav("`nZum Altar zurÃ¼ckkehren","forest.php");

            $session['user']['specialinc']="sacrificealtar.php";

            break;

            case 3:

            output("`#Du legst einen Deiner hart verdienten Edelsteine auf den Altar und als Du ihn aus den Fingern lÃ¤sst ist der Edelstein verschwunden!`n");

            output("Du wartest ob etwas passiert, aber es passiert nichts. Du wirst wÃ¼tend wegen Deiner Dummheit und erhÃ¤ltst einen Waldkampf!");

            addnav("`nZum Altar zurÃ¼ckkehren","forest.php");

            $session['user']['turns']++;

            $session['user']['gems']--;

            $session['user']['donation']+=1;

            $session['user']['specialinc']="sacrificealtar.php";

            break;

        }

    }elseif ($_GET['type']=="Flowers"){

        if (!$_GET['flower']){

            $session['user']['turns']--;

            output("`@Du suchst im Wald nach wilden Blumen, bis Du auf eine Wiese mit verschiedenen Blumen gelangst. Dort sind`$ Rosen`@, `&GÃ¤nseblÃ¼mchen`@, und `^LÃ¶wenzahn`@.`n Welche mÃ¶chtest Du opfern?");

            addnav("Opfere Rosen","forest.php?op=Sacrifice&type=Flowers&flower=Roses");

            addnav("Opfere GÃ¤nseblÃ¼mchen","forest.php?op=Sacrifice&type=Flowers&flower=Daisies");

            addnav("Opfere LÃ¶wenzahn","forest.php?op=Sacrifice&type=Flowers&flower=Dandelions");

            addnav("`nZum Altar zurÃ¼ckkehren","forest.php?op=");

            $session['user']['specialinc']="sacrificealtar.php";

        }else{

            $session['user']['specialinc']="";

            if ($_GET['flower']=="Roses"){

                output("`@Du legst die Rosen als Opfergabe auf den Altar. Du senkst Deinen Kopf um ein Gebet an die GÃ¶tter zu richten, Du bittest sie ");

                output("Deine Opfergabe anzunehmen. Als Du Deinen Kopf wieder anhebst um auf den Altar zu schauen, ");

                switch(e_rand(1,7)){

                    case 1:

                    output("siehst Du einen `^wÃ¼tenden Hasen`@! Du dachtest nicht wirklich, dass GÃ¶tter, die einen blutverschmierten Altar haben ");

                    output("wirklich eine Opfergabe bestehend aus Blumen akzeptieren wÃ¼rden, dachtest Du? Wirklich, wer wÃ¼rde so etwas denken? ");

                    output("Jetzt wirst Du Deinen Tod finden, welcher Dich mit groÃŸen und scharfen ");

                    output("ZÃ¤hnen erwartet!");

                    output("`n`n`^Du wurdest von einem `\$wÃ¼tenden Hasen`^ getÃ¶tet!`n");

                    output("Du verlierst all Dein Gold!`n");

                    output("Du verlierst 10% Deiner Erfahrung!");

                    output("Du kannst morgen wieder weiterspielen.");

                    $session['user']['alive']=0;

                    $session['user']['hitpoints']=0;

                    $session['user']['experience']*=0.9;

                    $session['user']['gold']=0;

                    $session['user']['donation']+=1;

                    addnav("TÃ¤gliche News","news.php");

                    if (strtolower(substr($session['user']['name'],-1))=="s"){

                        addnews($session['user']['name']."'s KÃ¶rper wurde gefunden... angeknabbert von Hasen!");

                    }else{

                        addnews($session['user']['name']."'s KÃ¶rper wurde gefunden... angeknabbert von Hasen!");

                    }

                    break;

                    case 2:

                    case 3:

                    case 4:

                    output("siehst Du eine wunderschÃ¶ne Frau vor Dir stehen.`n`n");

                    output("\"`#".($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").",`@\" sagt sie, \"`# ich danke Dir fÃ¼r das Geschenk der Rosen. Ich ");

                    output("weiss, dass Du ein hartes Leben hinter Dir hast, also erhÃ¤ltst Du ein Geschenk von mir.`@\"`n`n");

                    output("Sie legt ihre Hand auf Deinen Kopf und Du fÃ¼hlst ein warmes GefÃ¼hl durch Deinen KÃ¶rper gleiten. Als sie ihre ");

                    output("Hand von Deinem Kopf nimmt, sagt sie Dir, dass Du in die WasserpfÃ¼tze beim Altar schauen sollst. Du gehst zur WasserpfÃ¼tze ");

                    output("und schaust hinein. Du bemerkst, dass Du ein wenig ".($session['user']['sex']?"schÃ¶ner":"angenehmer"));

                    output("aussiehst als zuvor. Du gehst zum Altar zurÃ¼ck und bemerkst, dass die GÃ¶ttin verschwunden ist. Wie war wohl ihr Name?");

                    output("`n`n`^Du erhÃ¤ltst 1 Charmepunkt!");

                    $session['user']['charm']++;

                    break;

                    case 5:

                    case 6:

                    case 7:

                    output("siehst Du eine wunderschÃ¶ne Frau vor Dir stehen.`n`n");

                    output("\"`#".($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").",`@\" sagt sie, \"`# ich danke Dir fÃ¼r das Geschenk der Rosen. Ich ");

                    output("weiss, dass Du ein hartes Leben hinter Dir hast, also erhÃ¤ltst Du ein Geschenk von uns.`@\"`n`n");

                    output("Sie sagt Dir, dass Du in die WasserpfÃ¼tze beim Altar schauen sollst. Du gehst zur WasserpfÃ¼tze ");

                    output("und schaust hinein. Du siehst etwas funkelndes im Wasser! Du schaust zurÃ¼ck zum Altar und bemerkst, dass die GÃ¶ttin ");

                    output("verschwunden ist. Wie war wohl ihr Name?");

                    output("`n`n`^Du hast `%ZWEI`^ Edelsteine gefunden!");

                    $session['user']['gems']+=2;

                    break;

                }

            }elseif ($_GET['flower']=="Daisies"){

                output("`@Du legst die GÃ¤nseblÃ¼mchen als Opfergabe auf den Altar. Du senkst Deinen Kopf zum Gebet an die GÃ¶tter und bittest sie ");

                output("die Opfergabe anzunehmen. Als Du Deinen Kopf hebst und zum Altar schaust ");

                switch(e_rand(1,12)){

                    case 1:

                    output("siehst Du wie sich die GÃ¤nseblÃ¼mchen in eine `^Riesen Venus Fliegenfalle`@, verwandelt, mit dem Unterschied, dass diese keine Fliegen fÃ¤ngt. ");

                    output("Bevor Du fliehen kannst, oder Deine Waffe in die Hand nimmst, hat Dich die Pflanze bereits mit ihrem Maul verschlungen. Du bist nun dabei ");

                    output("in den nÃ¤chsten 100 Jahren langsam verdaut zu werden. Denk Ã¼ber Deine Fehler nach, genug Zeit dafÃ¼r hast Du nun...");

                    output("`n`n`^Du wurdest gefressen von einer `\$Riesen Venus Fliegenfalle`^!`n");

                    output("Du verlierst all Dein Gold!`n");

                    output("Du verlierst 10% Deiner Erfahrung!");

                    output("Du kannst morgen wieder weiterspielen.");

                    $session['user']['alive']=0;

                    $session['user']['hitpoints']=0;

                    $session['user']['experience']*=0.9;

                    $session['user']['gold']=0;

                    addnav("TÃ¤gliche News","news.php");

                    $session['user']['donation']+=1;

                    if (strtolower(substr($session['user']['name'],-1))=="s"){

                        addnews($session['user']['name']."'s Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden.");

                    }else{

                        addnews($session['user']['name']."'s Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden.");

                    }

                    break;

                    case 2:

                    case 3:

                    case 4:

                    case 5:

                    case 6:

                    output("siehst Du ein junges MÃ¤dchen, das auf dem Altar sitzt und die GÃ¤nseblÃ¼mchen in der HÃ¤nden hÃ¤lt.`n`n");

                    output("\"`#Er liebt mich, er liebt mich nicht. Er liebt mich, er liebt mich nicht,`@\" sagt sie wÃ¤hrend sie die BlumenblÃ¤tter abrupft. ");

                    output("Du starrst sie bewundernd an, bis sie das letzte Blumenblatt rupft.`n`n");

                    if (e_rand(0,1)==0){

                        output("\"`#Er liebt mich nicht. Was?!`@\" schreit sie laut und fÃ¤ngt an zu weinen. Sie hÃ¼pft vom Altar und rennt ");

                        output("dicht an Dir vorbei in den Wald. Du fÃ¼hlst Dich weniger ");

                        output("charmant.`n`n");

                        output("`^Du verlierst 1 Charmepunkt!");

                        $session['user']['charm']--;

                    }else{

                        output("\"`#Er liebt mich. Juchu! Er liebt mich, er liebt mich!`@\" sagt sie und hÃ¼pft auf und ab. ");

                        output("Sie springt vom Altar und rennt dicht an Dir vorbei in den Wald. Du fÃ¼hlst Dich nach der Freude ");

                        output("des MÃ¤dchens charmanter.`n`n");

                        output("`^Du erhÃ¤ltst 1 Charmepunkt!");

                        $session['user']['charm']++;

                    }

                    break;

                    case 7:

                    case 8:

                    case 9:

                    case 10:

                    case 11:

                    case 12:

                    $reward=e_rand($session['user']['experience']*0.025+10, $session['user']['experience']*0.1+10);

                    output("siehst Du eine wunderschÃ¶ne Frau in Deiner NÃ¤he.`n`n");

                    output("\"`#".($session['user']['sex']?"Meine Tochter":"Mein Sohn").",`@\" sagt sie, \"`#Ich danke Dir fÃ¼r das Geschenk. Ich ");

                    output("weiÃŸ Du hattest ein hartes Leben bisher, darum erhÃ¤ltst Du ein Geschenk von uns.`@\"`n`n");

                    output("Sie gibt Dir etwas, das wie ein leckerer Brotlaib aussieht und motiviert Dich es zu essen. Da Du nicht unhÃ¶flich sein wilst ");

                    output("nimmst Du das Brot in den Mund und iÃŸt es. Auf einmal fÃ¼hlst Du Dich so als ob sich mehr Wissen ");

                    output("in Deinem GedÃ¤chtnis breitgemacht hat. Du schliesst kurz Deine Augen und als Du sie wieder Ã¶ffnest ist die GÃ¶ttin ");

                    output("verschwunden. Wie war wohl ihr Name?");

                    output("`n`n`^Du erhÃ¤ltst $reward Erfahrungspunkte!");

                    $session['user']['experience']+=$reward;

                    break;

                }

            }elseif ($_GET['flower']=="Dandelions"){

                output("`@Du legst den LÃ¶wenzahn auf den Opferaltar. Du senkst den Kopf zum Gebet an die GÃ¶tter, mit der Hoffnung, ");

                output("dass sie Dein Geschenk akzeptieren. Als Du Deinen Kopf wieder anhebst schaust Du auf den Altar und ");

                switch(e_rand(1,5)){

                    case 1:

                    output("siehst eine GÃ¶ttin, die miÃŸbilligend auf Dein Geschenk schaut. PlÃ¶tzlich dreht sie sich zu Dir und ihre Wut bricht aus. ");

                    output("Sie geht voller Zorn auf Dich zu!");

                    output("`n`n\"`#A `iUnkraut`i!! Du schenkst `iUnkraut`i an die mÃ¤chtigen GÃ¶tter! Wurm! Du verdienst es nicht einmal zu leben!`@\"");

                    output("sagt sie und schleudert dann einen Feuerball auf Dich.`n`n");

                    output("Der erste durchwandert Dich einfach, verwandelt Deinen OberkÃ¶rper in Asche und Deine Arme, Beine und Dein Kopf");

                    output("sterben langsam ab. Als Dein Kopf auf den Boden fÃ¤llt und rollt, tritt die GÃ¶ttin diesen mit ihrem FuÃŸ, nimmt diesen dann auf und ");

                    output("schaut in Deine Augen. `n`n");

                    output("\"`#Nun, ".$session['user']['name'].", ich denke Du hast Deine Lektion gelernt. StÃ¶re die GÃ¶tter nie wieder mit solchen Kleinigkeiten.`@\"");

                    output("`n`nAls Dein Geist in die Schatten abtaucht, denkst Du noch \"`&Sie irren sich, ich denke nicht, ");

                    output("dass es der Gedanke ist der zÃ¤hlt...`@\"`n`n");

                    output("`^Du bist tot!`n");

                    output("Du verlierst all Dein Geld!`n");

                    output("Diese Lektion hat Dir mehr Erfahrung eingebracht als Du verlieren kÃ¶nntest.");

                    $session['user']['alive']=0;

                    $session['user']['hitpoints']=0;

                    $session['user']['gold']=0;

                    addnav("TÃ¤gliche News","news.php");

                    if (strtolower(substr($session['user']['name'],-1))=="s"){

                        addnews($session['user']['name']."'s Kopf wurde gefunden... auf einem Speer in der NÃ¤he eines Altars fÃ¼r die GÃ¶tter.");

                    }else{

                        addnews($session['user']['name']."'s Kopf wurde gefunden... auf einem Speer in der NÃ¤he eines Altars fÃ¼r die GÃ¶tter");

                    }

                    break;

                    case 2:

                    case 3:

                    case 4:

                    case 5:

                    output("Dein Geschenk geht in Flammen auf. Feuer umgibt den LÃ¶wenzahn. Als die Flammen alles in Asche verwandelt haben gehst Du ");

                    output("zu ihnen hin und entsorgst die Asche.");

                    switch(e_rand(1,3)){

                        case 1:

                        output("`iDu findest dort nichts!`i Die GÃ¶tter mÃ¼ssen Dein Geschenk abgelehnt haben. Deine HÃ¤nde sind ganz klebrig ");

                        output("von dem ganzen LÃ¶wenzahn. Naja, es war ja nur Unkraut...");

                        break;

                        case 2:

                        case 3:

                        output("`iDu findest einen Edelstein!!`i Die GÃ¶tter mÃ¼ssen Dein Geschenk angenommen haben. Deine HÃ¤nde sind ganz klebrig ");

                        output("von dem ganzen LÃ¶wenzahn, aber der Edelstein war es wert!");

                        output("`n`n`^ Du findest `%EINEN`^ Edelstein!");

                        $session['user']['gems']+=1;

                        break;

                    }

                }

            }

        }

    }



}elseif ($_GET['op']=="Leave"){

    output("`#Das ist ein heiliger Ort, fÃ¼r GÃ¶tter und Priester. Am besten machst Du Dich schnellstens wieder auf den Weg bevor die GÃ¶tter zornig werden, ");

    output("weil Du an ihrem heligen Altar verweilst.");

    $session['user']['specialinc']="";



}elseif ($_GET['op']=="Won"){

    $dif=$_GET['Difficulty'];

    $badguyname=urldecode($_GET['badguyname']);

    output("`@Du trÃ¤gst Dein Geschenk, `^".$badguyname."`@, zurÃ¼ck zum Altar. Du legst den toten Leichnahm auf den ");

    output("Altar und fÃ¼hrst das Blutritual durch. Als Du dieses beendet hast ");

    switch(e_rand(1,15)){

        case 1:

        output("`i erwacht `^".$badguyname."`@ zu neuem Leben!`i Mit dem Unterschied, dass es nun Fangarme und Krallen besitzt und es sieht sehr hungrig aus. Dein Pech ist, Du hast es bereits ");

        output("getÃ¶tet, weil Du nichts tÃ¶ten kannst das bereits tot ist. Du hÃ¤ttest wissen mÃ¼ssen, dass die GÃ¶tter ");

        output("solche Opfer nicht annehmen. Das war `imenschliches`i Blut auf dem Altar.`n`nDie GÃ¶tter wollen Blut und ");

        output("sie bekommen es nun von Dir, ob Dir das nun gefÃ¤llt oder nicht.");

        output("`n`n`^Du bist tot!`n");

        output("Die GÃ¶tter scheinen auch glÃ¤nzendes gelbes Metall zu lieben, denn sie nahmen Dir all Dein Gold!`n");

        output("Du verlierst 5% Deiner Erfahrung.`n");

        output("Du kannst morgen wieder weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['experience']*=0.95;

        $session['user']['gold']=0;

        addnav("TÃ¤gliche News","news.php");

        if (strtolower(substr($session['user']['name'],-1))=="s"){

            addnews($session['user']['name']."s Ãœberreste waren nicht sehr schÃ¶n als sie gefunden wurden...");

        }else{

            addnews($session['user']['name']."'s Ãœberreste waren nicht sehr schÃ¶n als sie gefunden wurden...");

        }

        break;

        case 2:

        case 3:

        case 4:

        case 5:

        $reward=1;

        $rewardnum="EINEN`^ Edelstein";

        if ($dif=="Moderate"){

            $reward = 2;

            $rewardnum="ZWEI`^ Edelsteine";

        }

        if ($dif=="Strong"){

            $reward = 3;

            $rewardnum="DREI`^ Edelsteine";

        }

        output("sprichst Du ein Gebet fÃ¼r den Geist des toten `^".$badguyname."`@ aus. Du drehst Dich um umd wÃ¤scht Deine HÃ¤nde in ");

        output("einer kleinen PfÃ¼tze beim Altar. Als Du fertig bist, stehst Du wieder auf und drehst Dich wieder zum Altar. `i`^".$badguyname."`@ ist ");

        output("verschwunden!`i An dessen Stelle ist nun ein Beutel. Du gehst hin und schaust in den Beutel hinein. Im Beutel findest Du $reward Edelsteine! Die GÃ¶tter ");

        output("haben Dein Opfer wohl akzeptiert und Dich fÃ¼r Deine MÃ¼hen entlohnt.");

        output("`n`n`^Du findest `%".$rewardnum."!`n");

        $session['user']['gems']+=$reward;

        break;

        case 6:

        case 7:

        case 8:

        $reward = e_rand(10, 100);

        if ($dif=="Strong") $reward = e_rand(175, 300);

        if ($dif=="Moderate") $reward = e_rand(75, 200);

        output("sprichst Du ein Gebet fÃ¼r den Geist des toten `^".$badguyname."`@ aus. Du drehst Dich um umd wÃ¤scht Deine HÃ¤nde in ");

        output("einer kleinen PfÃ¼tze beim Altar. Als Du fertig bist, stehst Du wieder auf und drehst Dich wieder zum Altar. `i`^".$badguyname."`@ ist ");

        output("verschwunden!`i An dessen Stelle ist nun ein Beutel. Du gehst hin und schaust in den Beutel hinein. Im Beutel findest Du ".$reward." Gold! Die GÃ¶tter ");

        output("haben Dein Opfer wohl akzeptiert und Dich fÃ¼r Deine MÃ¼hen entlohnt.");

        output("`n`n`^Du findest $reward Gold!`n");

        $session['user']['gold']+=$reward;

        break;

        case 9:

        case 10:

        case 11:

        case 12:

        $reward = 2;

        if ($dif=="Moderate") $reward = 3;

        if ($dif=="Strong") $reward = 4;

        output("legst Du Deine Hand auf den toten KÃ¶rper um zu beten, aber als Deine Hand das Fleisch des ");

        output("toten ".$badguyname." berÃ¼hrt, fÃ¼hlst Du Dich von Energie durchflossen. Deine SchwÃ¤che wurde ausgesaugt und ");

        output("Deine MÃ¼digkeit besÃ¤nftigt. Die GÃ¶tter haben Dir genug StÃ¤rke gegeben fÃ¼r weitere $reward WaldkÃ¤mpfe!");

        output("`n`n`^Du erhÃ¤ltst weitere $reward WaldkÃ¤mpfe!!");

        $session['user']['turns']+=$reward;

        break;

        case 13:

        case 14:

        $charmloss=3;

        if ($dif=="Moderate") $charmloss=2;

        if ($dif=="Strong") $charmloss=1;

        output("fÃ¤ngt der Leichnahm an grÃ¶ÃŸer zu werden, als ob er mit Luft gefÃ¼llt wird! Er wird immer noch grÃ¶ÃŸer. Du bist zu Ã¼berrascht um Dich zu bewegen. ");

        output("Letztlich explodiert `^".$badguyname."`@ und beschmutzt Dich mit Blut und Ãœberresten. Das Opfer muss wohl nicht genug gewesen sein ");

        output("und Du wurdest dafÃ¼r bestraft.");

        output("`n`n`^Du verlierst ".$charmloss." Charmepunkte!");

        $session['user']['charm']-=$charmloss;

        if ($session['user']['charm']<=0) $session['user']['charm']=0;

        $session['user']['donation']+=$charmloss;

        break;

        case 15:

        output("`\$fÃ¤rbt sich der Himmel rot. `@Du fÃ¼rchtest Dich davor das Du die GÃ¶tter verÃ¤rgert hast und drehst Dich um um den Ort zu verlassen. Gerade als Du den Ort `n`n");

        output("verlassen willst, fÃ¤llt ein Blitz vom Himmel und trifft Dich. Du wirst zurÃ¼ckgeschleudert und ");

        output("als Du den Boden triffst, bist Du bereits tot ".$session['user']['weapon'].". Es ist nicht gut den GÃ¶ttern");

        output("zu wenig Respekt zu zollen und Du fandest das auf dem harten Weg heraus.");

        output("`n`n`^Du bist tot!`n");

        output("Du verlierst all Dein Gold!`n");

        output("Du verlierst 10% Deiner Erfahrung!`n");

        $session['user']['donation']+=1;

        output("Du kannst morgen wieder weiterspielen.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['experience']*=0.9;

        $session['user']['gold'] = 0;

        addnav("TÃ¤gliche News","news.php");

        addnews("Der verkohlte KÃ¶rper von ".$session['user']['name']." wurde irgendwo im Wald gefunden.");

        break;

    }

    $session['user']['specialinc']="";



}elseif ($_GET['op']=="run"){



    if (e_rand()%3==0){

        output ("`c`b`&Du bist erfolgreich vor Deinem Feind geflohen!`0`b`c`n");

        $_GET['op']="";

        output("Du fliehst feige vor Deiner Beute und hast dabei vergessen wo sich der Altar befindet. Du wirst mÃ¶glicherweise nie mehr etwas opfern. ");

        output("Denk immer daran, es ist alleine Deine Schuld.");

        $session['user']['specialinc']="";

        addnav("ZurÃ¼ck in den Wald","forest.php");

        $battle=false;

    }else{

        output("`c`b`\$Du konntest vor Deinem Feind nicht fliehen!`0`b`c");

        $battle=true;

    }



}elseif ($_GET['op']=="fight"){

    $battle=true;



}else{

    output("`@Als Du durch den Wald wanderst, entdeckst Du plÃ¶tzlich einen Steinaltar. ");

    output("Er wurde aus Basaltstein unter einen riesigen Baum gebaut. Du gehst nÃ¤her zu ihm hin und Du siehst ");

    output("eingetrocknete Blutflecken von Jahrhunderten der Opferungen. Das ist eindeutig ein besonderer Ort und ");

    output("Du kannst eine gÃ¶ttliche PrÃ¤senz spÃ¼ren. `n");

    output("Du solltest den GÃ¶ttern vielleicht etwas opfern, um sie nicht zu beleidigen.");

    output("`n`nWas wirst du tun?");

    addnav("Was opfern?");

    addnav("Dich selbst","forest.php?op=Sacrifice&type=Yourself");

    addnav("s?Ein starkes Monster","forest.php?op=Sacrifice&type=Creature&Difficulty=Strong");

    addnav("m?Ein mittleres Monster","forest.php?op=Sacrifice&type=Creature&Difficulty=Moderate");

    if ($session['user']['level']>1) addnav("w?Ein schwaches Monster","forest.php?op=Sacrifice&type=Creature&Difficulty=Weak");

    addnav("Blumen","forest.php?op=Sacrifice&type=Flowers");

    if ($session['user']['gems']>0) addnav("Edelstein","forest.php?op=Sacrifice&type=Edelstein");

    addnav("`nAltar verlassen","forest.php?op=Leave");

    $session['user']['specialinc']="sacrificealtar.php";

}



if ($battle){

    include("battle.php");

    if ($victory){

           if (getsetting("dropmingold",0)){

            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);

        }else{

            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);

        }

        $expbonus=round(($badguy['creatureexp']*(1+.25*($badguy['creaturelevel']-$session['user']['level'])))-$badguy['creatureexp'],0);

        output("`b`&{$badguy['creaturelose']}`0`b`n");

        output("`b`\$Du hast {$badguy['creaturename']} getÃ¶tet!`0`b`n");

        output("`#Du erhÃ¤ltst `^{$badguy['creaturegold']}`# Gold!`n");

        if (e_rand(1,25)==1){

            output("`&Du findest einen Edelstein!`n`#");

            $session['user']['gems']++;

        }

        if ($expbonus>0){

            output("`#***Weil der Kampf schwieriger war, erhÃ¤ltst Du zusÃ¤tzliche `^$expbonus`# Erfahrungspunkte! `n({$badguy['creatureexp']} + ".abs($expbonus)." = ".($badguy['creatureexp']+$expbonus).") ");

            $dif="Strong";

        }elseif ($expbonus<0){

            output("`#***Weil der Kampf so leicht war, werden Dir `^".abs($expbonus)."`# Erfahrungspunkte abgezogen! `n({$badguy['creatureexp']} - ".abs($expbonus)." = ".($badguy['creatureexp']+$expbonus).") ");

            $dif="Weak";

        }

        output("Du erhÃ¤ltst insgesamt `^".($badguy['creatureexp']+$expbonus)."`# Erfahrungspunkte!`n`0");

        $session['user']['gold']+=$badguy['creaturegold'];

        $session['user']['experience']+=($badguy['creatureexp']+$expbonus);

        $creaturelevel=$badguy['creaturelevel'];

        if ($badguy['diddamage']!=1){

            if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel){

                output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhÃ¤ltst einen Extra-Waldkampf!`c`0`n");

                $session['user']['turns']++;

            }else{

                output("`b`c`&~~ Unglaublicher Kampf! ~~`b`\$`nEin schwierigerer Kampf hÃ¤tte Dir einen Extra-Waldkampf eingebracht.`c`n`0");

            }

        }

        $dontdisplayforestmessage=true;

        $badguyname=$badguy['creaturename'];

        $badguy=array();

//    Add victory possiblilities below:

        addnav("Zum Altar zurÃ¼ckkehren","forest.php?op=Won&Difficulty=$dif&badguyname=".urlencode($badguyname));

        $session['user']['specialinc']="sacrificealtar.php";

//    End of Victory Possibilities,

    }elseif ($defeat){

        addnav("TÃ¤gliche News","news.php");

        $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";

        $result = db_query($sql) or die(db_error(LINK));

        $taunt = db_fetch_assoc($result);

        $taunt = str_replace("%s",($session['user']['sex']?"sie":"ihn"),$taunt['taunt']);

        $taunt = str_replace("%o",($session['user']['sex']?"sie":"er"),$taunt);

        $taunt = str_replace("%p",($session['user']['sex']?"ihr":"sein"),$taunt);

        $taunt = str_replace("%x",($session['user']['weapon']),$taunt);

        $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);

        $taunt = str_replace("%W",$badguy['creaturename'],$taunt);

        $taunt = str_replace("%w",$session['user']['name'],$taunt);

        addnews("`%".$session['user']['name']."`5 wurde im Wald von {$badguy['creaturename']} getÃ¶tet.`n$taunt");

        $session['user']['alive']=0;

        $session['user']['gold']=0;

        $session['user']['hitpoints']=0;

        $session['user']['experience']=round($session['user']['experience']*.9,0);

        $session['user']['badguy']="";

        output("`b`&Du wurdest von `%{$badguy['creaturename']}`& getÃ¶tet!!!`n");

        output("`4Du hast all Dein Gold verloren!`n");

        output("`410% Deiner Erfahrung ging verloren!`n");

        output("Du kannst morgen wieder weiterspielen.");

        $session['user']['specialinc']="";

    }else{

        $session['user']['specialinc']="sacrificealtar.php";

        fightnav(true,true);

    }

}



?>

