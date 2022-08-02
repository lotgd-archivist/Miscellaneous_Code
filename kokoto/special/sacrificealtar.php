<?php
/* ******************* 
Altar of Sacrifice 
Written by TheDragonReborn 
    Based on Forest.php

Translation by Lendara Mondkind (Lisandra)
// überarbeitet von Tidus www.kokoto.de
******************* */  

$specialbat='sacrificealtar.php'; 
$allowflee=true; 
$allowspecial=true; 

if ($_GET['op']=='Sacrifice'){
        if ($_GET['type']=='Yourself'){
        output('`@Du legst Deine Sachen ab und legst Dich auf den Altar. Als Du Dein/e/n '.$session['user']['weapon'].' erhebst, denkst Du an die Liebe. Dann, ohne weitere Verzögerung, nimmst du dir mit '.$session['user']['weapon'].' das Leben. Als sich die Dunkelheit Deiner bemächtigt '); 
        switch(mt_rand(1,15)){ 
        case '1': 
        case '2': 
        case '3': 
            output('Denkst Du, dass Du genug getan hast um die Götter zu besänftigen, damit diese die Welt zu einem besseren Ort machen...`n`n Leider wirst Du nicht nicht dabei sein, um es zu sehen.`n`n`^Du bist tot!`n Du verlierst all Dein Gold!`n Du verlierst 5% Deiner Erfahrung.`n Du kannst morgen wieder weiterspielen.'); 
            $session['user']['alive']=false; 
            $session['user']['hitpoints']=0; 
            $session['user']['experience']*=0.95; 
            $session['user']['gold'] = 0; 
            addnav('Tägliche News','news.php'); 
            if (strtolower_c(substr_c($session['user']['name'],1))=="s") addnews($session['user']['name']."' Körper wurde auf einem Altar in den Wäldern gefunden."); 
            else addnews($session['user']['name']."'s Körper wurde auf einem Altar in den Wäldern gefunden.");  
            break; 
        case '4': 
        case '5': 
            output('siehst Du wie der Himmel rot wird aufgrund des Zorns der Götter. Sie sind nicht so leichtgläubig wie Du gedacht hast. Sie wissen warum Du das getan hast. Niemand, der sich selbst respektiert, würde einer Selbstopferung zustimmen, wenn er nicht denken würde, dass er etwas dadurch erhält. Ein gewaltiger Blitz kommt vom Himmel herab und trifft Deinen toten Körper. Dabei nimmt der Blitz einige Deiner Angriffs- und Verteidigungsfähigkeiten mit. Nun, das ist es was Du dafür erhältst, dass Du die Götter betrügen wolltest. `n`n`^Du bist gestorben!`n Du verlierst all Dein Gold!`nDu verlierst 10% Deiner Erfahrung!`n Du verlierst 1 Punkt in Angriff und Verteidigung!`n Du kannst morgen wieder weiterspielen.'); 
            $session['user']['alive']=false; 
            $session['user']['hitpoints']=0; 
            $session['user']['experience']*=0.95; 
            $session['user']['donation']+=2; 
            $session['user']['gold'] = 0; 
            if ($session['user']['attack'] >= 2)$session['user']['attack']--; 
            if ($session['user']['defence'] >= 2)$session['user']['defence']--; 
            addnav('Tägliche News','news.php'); 
            if (strtolower_c(substr_c($session['user']['name'],1))=="s") addnews($session['user']['name']."'s Überbleibsel wurden verkohlt auf einem Altar gefunden."); 
            else addnews($session['user']['name']."'s Überbleibsel wurden verkohlt auf einem Altar gefunden."); 
            break; 
        case '6': 
        case '7': 
        case '8': 
        case '9': 
 output('siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.`n`n `#'.($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@"`n`n Er erhebt seine Hand und fährt sie an der gesamten Länge Deines Körpers entlang. Er hält sie ganz knapp vor der Berührung mit Dir. Du fühlst wie eine warme Energie durch Dich wandert und alles fängt an klarer zu werden. Du stehst auf und erkennst, dass die Wunde von Deine/r/m '.$session['user']['weapon'].' komplett geheilt ist. Du schaust Dich nach dem alten Mann um, doch er war verschwunden.`n`n Du nimmst Deine Sachen wieder auf und machst Du bereit weiterzugehen. Als Du an einer Wasserpfütze vorbei gehst, siehst Du zufällig in sie und siehst Dein Spiegelbild. Du siehst wesentlich '.($session['user']['sex']?"schöner":"angenehmer").' aus als je zuvor. Es muss ein Geschenk der Götter sein.`n`n `^Du erhältst 2 Charmepunkte!'); 
            $session['user']['charm']+=2; 
            break; 
        case '10': 
        case '11': 
        case '12': 
        case '13': 
            output('siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.`n`n "`#'.($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@\"`n`n Er erhebt seine Hand und fährt sie an der gesamten Länge Deines Körpers entlang. Er hält sie ganz knapp vor der Berührung mit Dir. Du fühlst wie eine warme Energie durch Dich wandert und alles fängt an klarer zu werden. Du stehst auf und erkennst, dass die Wunde von Deine/r/m '.$session['user']['weapon'].'  komplett geheilt ist. Du schaust Dich nach dem alten Mann um, doch er war verschwunden.`n`n Als Du den Altar verlässt, fällt Dir auf, dass Du mehr Lebenspunkte als zuvor hast.'); 
            $reward=$session['user']['maxhitpoints']  0.05;  
            $reward=1;  
            output("`n`n`^Deine maximalen Lebenspunkte sind `bpermanent`b gestiegen um $reward Punkte!"); 
            $session['user']['maxhitpoints']+=$reward; 
            break; 
        case '14': 
        case '15': 
            output('siehst Du ein strahlendes Leuchten. Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.`n`n "`#'.($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@"`n`n Er erhebt seine Hand und fährt sie an der gesamten Länge Deines Körpers entlang. Er hält sie ganz knapp vor der Berührung mit Dir. Du fühlst wie eine warme Energie durch Dich wandert und alles fängt an klarer zu werden. Du stehst auf und erkennst, dass die Wunde von Deine/r/m '.$session['user']['weapon'].'  komplett geheilt ist. Du schaust Dich nach dem alten Mann um, doch er war verschwunden.`n`n Als Du den Altar verlässt, fällt Dir auf, dass Deine Muskeln größer geworden sind. `n`n`^Du erhältst +1 Angriff und +1 Verteidigung!'); 
            $session['user']['attack']++; 
            $session['user']['defence']++; 
            break;                                                 
        } 
     
    }    elseif ($_GET['type']=='Creature'){
        output('Du entscheidest Dich eine unglückselige Kreatur an die Götter zu opfern. Darum gehst Du in den Wald und schaust Dich nach einem passenden Geschenk um.`n');
        $session['user']['turns']--;
        $battle=true;
        if (mt_rand(0,2)==1){
            $plev = (mt_rand(1,5)==1?1:0);
            $nlev = (mt_rand(1,3)==1?1:0);
        }else{
            $plev=0;
            $nlev=0;
        }
        if ($_GET['Difficulty']=='Weak'){
            $nlev++;
            output('`$Du gehst in ein Gebiet des Waldes, von dem Du weisst, dass sich dort eher leichtere Gegner aufhalten.`0`n');
        }
        if ($_GET['Difficulty']=='Strong'){
            $plev++;
            output('`$Du gehst in ein Gebiet des Waldes, welches Kreaturen aus Deinen Alpträumen enthält, in der Hoffnung, dass Du ein verletztes findest.`0`n');
        }
        $targetlevel=($session['user']['level']$plev$nlev);
        if ($targetlevel<1) $targetlevel=1;
        $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $badguy = db_fetch_assoc($result);
        $expflux = round($badguy['creatureexp']10,0);
        $expflux = e_rand($expflux,$expflux);
        $badguy['creatureexp']+=$expflux;
        //make badguys get harder as you advance in dragon kills.
        $badguy['playerstarthp']=$session['user']['hitpoints'];
        $dk = 0;
        foreach($session['user']['dragonpoints'] as $key => $val){
            if ($val=="at" || $val=="de") $dk++;
        }
        $dk+=(int)(($session['user']['maxhitpoints']($session['user']['level']10))5);
        $atkflux = e_rand(0, $dk);
        $defflux = e_rand(0, ($dk$atkflux));
        $hpflux = ($dk  ($atkflux$defflux))  5;
        $badguy['creatureattack']+=$atkflux;
        $badguy['creaturedefense']+=$defflux;
        $badguy['creaturehealth']+=$hpflux;
        if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;
        $badguy['diddamage']=0;
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']='sacrificealtar.php';

    }elseif ($_GET['type']=='Edelstein'){
    switch(mt_rand(1,2)){ 
        case '1': 
        output('`#Du legst einen Deiner hart verdienten Edelsteine auf den Altar und wartest ab was passiert. Aber es passiert nichts, gar nichts. Du bist natürlich schlau und versuchst ein paar Tricks wie im Busch verstecken, eine Art Regentanz, zu Edelstein und Altar sprechen, beten und Purzelbäume schlagen, aber trotz Deiner Bemühungen... es passiert nichts.`nAlso beschließt Du den Edelstein wieder mitzunehmen und stattdessen ein paar Monster zu töten. `nDu verlierst einen Waldkampf wegen Deiner versuchten Tricks.'); 
        $session['user']['turns']--; 
        addnav('`nZum Altar zurückkehren','forest.php?op='); 
        break; 
        case '2': 
        output('`#Du legst einen Deiner hart verdienten Edelsteine auf den Altar und als Du ihn aus den Fingern lässt ist der Edelstein verschwunden!`n Du wartest ob etwas passiert, aber es passiert nichts. Du wirst wütend wegen Deiner Dummheit und erhältst einen Waldkampf!'); 
        addnav('`nZum Altar zurückkehren','forest.php?op='); 
        $session['user']['turns']++; 
        $session['user']['gems']--; 
        $session['user']['donation']+=1; 
        break; 
        } 
    }elseif ($_GET['type']=='Flowers'){ 
        if (!$_GET['flower']){ 
            $session['user']['turns']--; 
            output('`@Du suchst im Wald nach wilden Blumen, bis Du auf eine Wiese mit verschiedenen Blumen gelangst. Dort sind`$ Rosen`@, `&Gänseblümchen`@, und `^Löwenzahn`@.`n Welche möchtest Du opfern?'); 
            output("`n`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Roses'>Opfere Rosen</a>`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Daisies'>Opfere Gänseblümchen</a>`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Dandelions'>Opfere Löwenzahn</a>`n`n<a href='forest.php?op='>Zum Altar zurückkehren</a>",true); 
            addnav('Opfere Rosen','forest.php?op=Sacrifice&type=Flowers&flower=Roses'); 
            addnav('Opfere Gänseblümchen','forest.php?op=Sacrifice&type=Flowers&flower=Daisies'); 
            addnav('Opfere Löwenzahn','forest.php?op=Sacrifice&type=Flowers&flower=Dandelions'); 

            addnav('`nZum Altar zurückkehren','forest.php?op='); 

            allownav('forest.php?op=Sacrifice&type=Flowers&flower=Roses'); 
            allownav('forest.php?op=Sacrifice&type=Flowers&flower=Daisies'); 
            allownav('forest.php?op=Sacrifice&type=Flowers&flower=Dandelions'); 

            allownav('forest.php?op='); 
            $session['user']['specialinc']=$specialbat; 
        }else{ 
            if ($_GET['flower']=='Roses'){ 
                output('`@Du legst die Rosen als Opfergabe auf den Altar. Du senkst Deinen Kopf um ein Gebet an die Götter zu richten, Du bittest sie '); 
                output('Deine Opfergabe anzunehmen. Als Du Deinen Kopf wieder anhebst um auf den Altar zu schauen, '); 
                switch(mt_rand(1,7)){ 
                    case '1': 
                        output('siehst Du einen `^wütenden Hasen`@! Du dachtest nicht wirklich, dass Götter, die einen blutverschmierten Altar haben wirklich eine Opfergabe bestehend aus Blumen akzeptieren würden, dachtest Du? Wirklich, wer würde so etwas denken? Jetzt wirst Du Deinen Tod finden, welcher Dich mit großen und scharfen Zähnen erwartet! `n`n`^Du wurdest getötet von einem `$wütenden Hasen`^!`n Du verlierst all Dein Gold!`n Du verlierst 10% Deiner Erfahrung! Du kannst morgen wieder weiterspielen.'); 
                        $session['user']['alive']=false; 
                        $session['user']['hitpoints']=0; 
                        $session['user']['experience']*=0.9; 
                        $session['user']['gold'] = 0; 
                        $session['user']['donation']+=1; 
                        addnav('Tägliche News','news.php'); 
                        if (strtolower_c(substr_c($session['user']['name'],1))=="s") addnews($session['user']['name']."'s Körper wurde gefunden... angeknabbert von Hasen!"); 
                        else addnews($session['user']['name']."'s Körper wurde gefunden... angeknabbert von Hasen!"); 
                        break; 
                    case '2': 
                    case '3': 
                    case '4': 
                        output('siehst Du eine wunderschöne Frau vor Dir stehen.`n`n "`#'.($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").',`@" sagt sie, "`# ich danke Dir für das Geschenk der Rosen. Ich  weiss, dass Du ein hartes Leben hinter Dir hast, also erhältst Du ein Geschenk von mir.`@"`n`n Sie legt ihre Hand auf Deinen Kopf und Du fühlst ein warmes Gefühl durch Deinen Körper gleiten. Als sie ihre Hand von Deinem Kopf nimmt, sagt sie Dir, dass Du in die Wasserpfütze beim Altar schauen sollst. Du gehst zur Wasserpfütze und schaust hinein. Du bemerkst, dass Du ein wenig '.($session['user']['sex']?"schöner":"angenehmer").' aussiehst als zuvor. Du gehst zum Altar zurück und bemerkst, dass die Göttin verschwunden ist. Wie war wohl ihr Name? `n`n`^Du erhältst 1 Charmepunkt!'); 
                        $session['user']['charm']++; 
                        break; 
                    case '5': 
                    case '6': 
                    case '7': 
                        output('siehst Du eine wunderschöne Frau vor Dir stehen.`n`n `#'.($session['user']['sex']?"Meine geliebte Tochter":"Mein geliebter Sohn").',`@" sagt sie, "`# ich danke Dir für das Geschenk der Rosen. Ich weiss, dass Du ein hartes Leben hinter Dir hast, also erhältst Du ein Geschenk von uns.`@"`n`n Sie sagt Dir, dass Du in die Wasserpfütze beim Altar schauen sollst. Du gehst zur Wasserpfütze und schaust hinein. Du siehst etwas funkelndes im Wasser! Du schaust zurück zum Altar und bemerkst, dass die Göttin verschwunden ist. Wie war wohl ihr Name? `n`n`^Du hast `%ZWEI`^ Edelsteine gefunden!'); 
                        $session['user']['gems']+=2; 
                        break; 
                 
                } 
            } 
            elseif ($_GET['flower']=='Daisies'){ 
                output('`@Du legst die Gänseblümchen als Opfergabe auf den Altar. Du senkst Deinen Kopf zum Gebet an die Götter und bittest sie '); 
                output('die Opfergabe anzunehmen. Als Du Deinen Kopf hebst und zum Altar schaust '); 
                switch(mt_rand(1,12)){ 
                    case '1': 
                        output('siehst Du wie sich die Gänseblümchen in eine `^Riesen Venus Fliegenfalle`@, verwandelt, mit dem Unterschied, dass diese keine Fliegen fängt.  Bevor Du fliehen kannst, oder Deine Waffe in die Hand nimmst, hat Dich die Pflanze bereits mit ihrem Maul verschlungen. Du bist nun dabei  in den nächsten 100 Jahren langsam verdaut zu werden. Denk über Deine Fehler nach, genug Zeit dafür hast Du nun... `n`n`^Du wurdest gefressen von einer `$Riesen Venus Fliegenfalle`^!`n Du verlierst all Dein Gold!`n Du verlierst 10% Deiner Erfahrung! Du kannst morgen wieder weiterspielen.'); 
                        $session['user']['alive']=false; 
                        $session['user']['hitpoints']=0; 
                        $session['user']['experience']*=0.9; 
                        $session['user']['gold'] = 0; 
                        addnav('Tägliche News','news.php'); 
                        $session['user']['donation']+=1; 
                        if (strtolower_c(substr_c($session['user']['name'],1))=="s") addnews($session['user']['name']."'s Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden."); 
                        else addnews($session['user']['name']."'s Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden."); 
                        break; 
                    case '2': 
                    case '3': 
                    case '4': 
                    case '5': 
                    case '6': 
                        output('siehst Du ein junges Mädchen, das auf dem Altar sitzt und die Gänseblümchen in der Händen hält.`n`n "`#Er liebt mich, er liebt mich nicht. Er liebt mich, er liebt mich nicht,`@" sagt sie während sie die Blumenblätter abrupft.  Du starrst sie bewundernd an, bis sie das letzte Blumenblatt rupft.`n`n'); 
                         
                        if (mt_rand(0,1)==0){ 
                            output("\"`#Er liebt mich nicht. Was?!`@\" schreit sie laut und fängt an zu weinen. Sie hüpft vom Altar und rennt "); 
                            output('dicht an Dir vorbei in den Wald. Du fühlst Dich weniger charmant.`n`n `^Du verlierst 1 Charmepunkt!'); 
                            $session['user']['charm']--; 
                        }else{ 
                            output("\"`#Er liebt mich. Juchu! Er liebt mich, er liebt mich!`@\" sagt sie und hüpft auf und ab. "); 
                            output('Sie springt vom Altar und rennt dicht an Dir vorbei in den Wald. Du fühlst Dich nach der Freude des Mädchens charmanter.`n`n `^Du erhältst 1 Charmepunkt!'); 
                            $session['user']['charm']++; 
                        } 
                        break; 
                    case '7': 
                    case '8': 
                    case '9': 
                    case '10': 
                    case '11': 
                    case '12': 
                        $reward=mt_rand($session['user']['experience']0.02510, $session['user']['experience']0.110); 
                        output('siehst Du eine wunderschöne Frau in Deiner Nähe.`n`n "`#'.($session['user']['sex']?"Meine Tochter":"Mein Sohn").',`@" sagt sie, "`#Ich danke Dir für das Geschenk. Ich weiß Du hattest ein hartes Leben bisher, darum erhältst Du ein Geschenk von uns.`@"`n`n Sie gibt Dir etwas, das wie ein leckerer Brotlaib aussieht und motiviert Dich es zu essen. Da Du nicht unhöflich sein wilst nimmst Du das Brot in den Mund und ißt es. Auf einmal fühlst Du Dich so als ob sich mehr Wissen in Deinem Gedächtnis breitgemacht hat. Du schliesst kurz Deine Augen und als Du sie wieder öffnest ist die Göttin '); 
                        output('verschwunden. Wie war wohl ihr Name?'); 
                        output("`n`n`^Du erhältst $reward Erfahrungspunkte!"); 
                        $session['user']['experience']+=$reward; 
                        break; 
                    } 
                 
                }elseif ($_GET['flower']=='Dandelions'){ 
                output('`@Du legst den Löwenzahn auf den Opferaltar. Du senkst den Kopf zum Gebet an die Götter, mit der Hoffnung, '); 
                output('dass sie Dein Geschenk akzeptieren. Als Du Deinen Kopf wieder anhebst schaust Du auf den Altar und '); 
                switch(mt_rand(1,5)){ 
                    case 1: 
                        output('siehst eine Göttin, die mißbilligend auf Dein Geschenk schaut. Plötzlich dreht sie sich zu Dir und ihre Wut bricht aus. '); 
                        output('Sie geht voller Zorn auf Dich zu! `n`n"`#A `iUnkraut`i!! Du schenkst `iUnkraut`i an die mächtigen Götter! Wurm! Du verdienst es nicht einmal zu leben!`@" sagt sie und schleudert dann einen Feuerball auf Dich.`n`n Der erste durchwandert Dich einfach, verwandelt Deinen Oberkörper in Asche und Deine Arme, Beine und Dein Kopf sterben langsam ab. Als Dein Kopf auf den Boden fällt und rollt, tritt die Göttin diesen mit ihrem Fuß, nimmt diesen dann auf und schaut in Deine Augen. `n`n "`#Nun, '.$session['user']['name'].', ich denke Du hast Deine Lektion gelernt. Störe die Götter nie wieder mit solchen Kleinigkeiten.`@" `n`nAls Dein Geist in die Schatten abtaucht, denkst Du noch "`&Sie irren sich, ich denke nicht, dass es der Gedanke ist der zählt...`@"`n`n'); 
                        output('`^Du bist tot!`n'); 
                        output('Du verlierst all Dein Geld!`n'); 
                        output('Diese Lektion hat Dir mehr Erfahrung eingebracht als Du verlieren könntest.'); 
                        $session['user']['alive']=false; 
                        $session['user']['hitpoints']=0; 
                        $session['user']['gold'] = 0; 
                        addnav('Tägliche News','news.php'); 
                        if (strtolower_c(substr_c($session['user']['name'],1))=="s") addnews($session['user']['name']."'s Kopf wurde gefunden... auf einem Speer in der Nähe eines Altars für die Götter."); 
                        else addnews($session['user']['name']."'s Kopf wurde gefunden... auf einem Speer in der Nähe eines Altars für die Götter"); 
                        break; 
                    case '2': 
                    case '3': 
                    case '4': 
                    case '5': 
                        output('Dein Geschenk geht in Flammen auf. Feuer umgibt den Löwenzahn. Als die Flammen alles in Asche verwandelt haben gehst Du zu ihnen hin und entsorgst die Asche.'); 
                        switch(mt_rand(1,3)){ 
                            case '1': 
                                output('`iDu findest dort nichts!`i Die Götter müssen Dein Geschenk abgelehnt haben. Deine Hände sind ganz klebrig '); 
                                output('von dem ganzen Löwenzahn. Naja, es war ja nur Unkraut...'); 
                                break; 
                            case '2': 
                            case '3': 
                                output('`iDu findest einen Edelstein!!`i Die Götter müssen Dein Geschenk angenommen haben. Deine Hände sind ganz klebrig '); 
                                output("von dem ganzen Löwenzahn, aber der Edelstein war es wert!"); 
                                output("`n`n`^ Du findest `%EINEN`^ Edelstein!"); 
                                $session['user']['gems'] +=1; 
                                break; 
                        } 
                } 
            } 
        } 
    }         
    
}elseif ($_GET['op']=='Leave'){ 
  output('`#Das ist ein heiliger Ort, für Götter und Priester. Am besten machst Du Dich schnellstens wieder auf den Weg bevor die Götter zornig werden, weil Du an ihrem heligen Altar verweilst.'); 
}elseif ($_GET['op']=='Won'){
    $dif=$_GET['Difficulty'];
    $badguyname=urldecode($_GET['badguyname']);
    output('`@Du trägst Dein Geschenk, `^'.$badguyname.'`@, zurück zum Altar. Du legst den toten Leichnahm auf den Altar und führst das Blutritual durch. Als Du dieses beendet hast ');
    switch(mt_rand(1,15)){
        case '1':
        output('`i erwacht `^'.$badguyname.'`@ zu neuem Leben!`i Mit dem Unterschied, dass es nun Fangarme und Krallen besitzt und es sieht sehr hungrig aus. Dein Pech ist, Du hast es bereits  getötet, weil Du nichts töten kannst das bereits tot ist. Du hättest wissen müssen, dass die Götter solche Opfer nicht annehmen. Das war `imenschliches`i Blut auf dem Altar.`n`nDie Götter wollen Blut und sie bekommen es nun von Dir, ob Dir das nun gefällt oder nicht. `n`n`^Du bist tot!`n Die Götter scheinen auch glänzendes gelbes Metall zu lieben, denn sie nahmen Dir all Dein Gold!`n Du verlierst 5% Deiner Erfahrung.`n Du kannst morgen wieder weiterspielen.');
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['experience']*=0.95;
        $session['user']['gold']=0;
        addnav('Tägliche News','news.php');
        if (strtolower_c(substr_c($session['user']['name'],1))=="s"){
            addnews($session['user']['name']."s Überreste waren nicht sehr schön als sie gefunden wurden...");
        }else{
            addnews($session['user']['name']."'s Überreste waren nicht sehr schön als sie gefunden wurden...");
        }
        break;
        case '2':
        case '3':
        case '4':
        case '5':
        $reward=1;
        $rewardnum='EINEN`^ Edelstein';
        if ($dif=='Moderate'){
            $reward = 2;
            $rewardnum='ZWEI`^ Edelsteine';
        }
        if ($dif=='Strong'){
            $reward = 3;
            $rewardnum='DREI`^ Edelsteine';
        }
        output('sprichst Du ein Gebet für den Geist des toten `^'.$badguyname.'`@ aus. Du drehst Dich um umd wäscht Deine Hände in einer kleinen Pfütze beim Altar. Als Du fertig bist, stehst Du wieder auf und drehst Dich wieder zum Altar. `i`^'.$badguyname.'`@ ist verschwunden!`i An dessen Stelle ist nun ein Beutel. Du gehst hin und schaust in den Beutel hinein. Im Beutel findest Du '.$reward.' Edelsteine! Die Götter haben Dein Opfer wohl akzeptiert und Dich für Deine Mühen entlohnt. `n`n`^Du findest `%'.$rewardnum.'!`n');
        $session['user']['gems']+=$reward;
        break;
        case '6':
        case '7':
        case '8':
        $reward = mt_rand(10, 100);
        if ($dif=='Strong') $reward = e_rand(175, 300);
        if ($dif=='Moderate') $reward = e_rand(75, 200);
        output('sprichst Du ein Gebet für den Geist des toten `^'.$badguyname.'`@ aus. Du drehst Dich um umd wäscht Deine Hände in einer kleinen Pfütze beim Altar. Als Du fertig bist, stehst Du wieder auf und drehst Dich wieder zum Altar. `i`^'.$badguyname.'`@ ist verschwunden!`i An dessen Stelle ist nun ein Beutel. Du gehst hin und schaust in den Beutel hinein. Im Beutel findest Du '.$reward.' Gold! Die Götter haben Dein Opfer wohl akzeptiert und Dich für Deine Mühen entlohnt. `n`n`^Du findest '.$reward.' Gold!`n');
        $session['user']['gold']+=$reward;
        break;
        case '9':
        case '10':
        case '11':
        case '12':
        $reward = 2;
        if ($dif=='Moderate') $reward = 3;
        if ($dif=='Strong') $reward = 4;
        output('legst Du Deine Hand auf den toten Körper um zu beten, aber als Deine Hand das Fleisch des toten '.$badguyname.' berührt, fühlst Du Dich von Energie durchflossen. Deine Schwäche wurde ausgesaugt und Deine Müdigkeit besänftigt. Die Götter haben Dir genug Stärke gegeben für weitere '.$reward.' Waldkämpfe! `n`n`^Du erhältst weitere '.$reward.' Waldkämpfe!!');
        $session['user']['turns']+=$reward;
        break;
        case '13':
        case '14':
        $charmloss=3;
        if ($dif=='Moderate') $charmloss=2;
        if ($dif=='Strong') $charmloss=1;
        output('fängt der Leichnahm an größer zu werden, als ob er mit Luft gefüllt wird! Er wird immer noch größer. Du bist zu überrascht um Dich zu bewegen. Letztlich explodiert `^'.$badguyname.'`@ und beschmutzt Dich mit Blut und Überresten. Das Opfer muss wohl nicht genug gewesen sein und Du wurdest dafür bestraft. `n`n`^Du verlierst '.$charmloss.' Charmepunkte!');
        $session['user']['charm']-=$charmloss;
        if ($session['user']['charm']<=0) $session['user']['charm']=0;
        $session['user']['donation']+=$charmloss;
        break;
        case '15':
        output('`$färbt sich der Himmel rot. `@Du fürchtest Dich davor das Du die Götter verärgert hast und drehst Dich um um den Ort zu verlassen. Gerade als Du den Ort `n`n verlassen willst, fällt ein Blitz vom Himmel und trifft Dich. Du wirst zurückgeschleudert und als Du den Boden triffst, bist Du bereits tot '.$session['user']['weapon'].'. Es ist nicht gut den Göttern zu wenig Respekt zu zollen und Du fandest das auf dem harten Weg heraus. `n`n`^Du bist tot!`nDu verlierst all Dein Gold!`n Du verlierst 10% Deiner Erfahrung!`n Du kannst morgen wieder weiterspielen.');
        $session['user']['donation']+=1;
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['experience']*=0.9;
        $session['user']['gold'] = 0;
        addnav('Tägliche News','news.php');
        addnews('Der verkohlte Körper von '.$session['user']['name'].' wurde irgendwo im Wald gefunden.');
        break;
    }
    $session['user']['specialinc']='';

}else if ($_GET['op']=='run'){ 
     
    if (e_rand()3 == 0){ 
        output ('`c`b`&Du bist erfolgreich vor Deinem Feind geflohen!`0`b`c`n'); 
        $_GET['op']=''; 
        output('Du fliehst feige vor Deiner Beute und hast dabei vergessen wo sich der Altar befindet. Du wirst möglicherweise nie mehr etwas opfern. Denk immer daran, es ist alleine Deine Schuld.'); 
    }else{ 
        output('`c`b`$Du konntest vor Deinem Feind nicht fliehen!`0`b`c'); 
    }
    }elseif ($_GET['op']=='fight'){
    $battle=true;

}else{
    output('`@Als Du durch den Wald wanderst, entdeckst Du plötzlich einen Steinaltar. Er wurde aus Basaltstein unter einen riesigen Baum gebaut. Du gehst näher zu ihm hin und Du siehst eingetrocknete Blutflecken von Jahrhunderten der Opferungen. Das ist eindeutig ein besonderer Ort und Du kannst eine göttliche Präsenz spüren. `n Du solltest den Göttern vielleicht etwas opfern, um sie nicht zu beleidigen. `n`nWas wirst du tun?'); 
     
     
    addnav('Was opfern?'); 
    addnav('Dich selbst','forest.php?op=Sacrifice&type=Yourself'); 
    addnav('Blumen','forest.php?op=Sacrifice&type=Flowers'); 
    if ($session['user']['gems']>0) addnav('Edelstein','forest.php?op=Sacrifice&type=Edelstein'); 
    addnav('`nAltar verlassen','forest.php?op=Leave'); 
addnav('s?Ein starkes Monster','forest.php?op=Sacrifice&type=Creature&Difficulty=Strong');
    addnav('m?Ein mittleres Monster','forest.php?op=Sacrifice&type=Creature&Difficulty=Moderate');
    addnav('w?Ein schwaches Monster','forest.php?op=Sacrifice&type=Creature&Difficulty=Weak');
    $session['user']['specialinc']=$specialbat; 
}
if ($battle){
    include("battle.php");
    if ($victory){
           if (getsetting("dropmingold",0)){
            $badguy['creaturegold']=e_rand($badguy['creaturegold']4,3$badguy['creaturegold']4);
        }else{
            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);
        }
        $expbonus=round(($badguy['creatureexp'](1.25($badguy['creaturelevel']$session['user']['level'])))$badguy['creatureexp'],0);
        output("`b`&{$badguy['creaturelose']}`0`b`n");
        output("`b`\$Du hast {$badguy['creaturename']} getötet!`0`b`n");
        output("`#Du erhältst `^{$badguy['creaturegold']}`# Gold!`n");
        if (e_rand(1,25)==1){
            output("`&Du findest einen Edelstein!`n`#");
            $session['user']['gems']++;
        }
        if ($expbonus>0){
            output("`#***Weil der Kampf schwieriger war, erhältst Du zusätzliche `^$expbonus`# Erfahrungspunkte! `n({$badguy['creatureexp']} + ".abs($expbonus)." = ".($badguy['creatureexp']$expbonus).") ");
            $dif="Strong";
        }elseif ($expbonus<0){
            output("`#***Weil der Kampf so leicht war, werden Dir `^".abs($expbonus)."`# Erfahrungspunkte abgezogen! `n({$badguy['creatureexp']} - ".abs($expbonus)." = ".($badguy['creatureexp']$expbonus).") ");
            $dif="Weak";
        }
        output("Du erhältst insgesamt `^".($badguy['creatureexp']$expbonus)."`# Erfahrungspunkte!`n`0");
        $session['user']['gold']+=$badguy['creaturegold'];
        $session['user']['experience']+=($badguy['creatureexp']$expbonus);
        $creaturelevel=$badguy['creaturelevel'];
        if ($badguy['diddamage']!=1){
            if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel){
                output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhältst einen Extra-Waldkampf!`c`0`n");
                $session['user']['turns']++;
            }else{
                output("`b`c`&~~ Unglaublicher Kampf! ~~`b`\$`nEin schwierigerer Kampf hätte Dir einen Extra-Waldkampf eingebracht.`c`n`0");
            }
        }
        $dontdisplayforestmessage=true;
        $badguyname=$badguy['creaturename'];
        $badguy=array();
//    Add victory possiblilities below:
        addnav('Zum Altar zurückkehren',"forest.php?op=Won&Difficulty=$dif&badguyname=".urlencode($badguyname));
        $session['user']['specialinc']="sacrificealtar.php";
//    End of Victory Possibilities,
    }elseif ($defeat){
        addnav("Tägliche News","news.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql);
			$taunt = db_fetch_assoc($result);
			$taunt = stripslashes($taunt['taunt']);
			$taunt = str_replace_c("%s",($session['user']['sex']?"sie":"ihn"),$taunt);
			$taunt = str_replace_c("%o",($session['user']['sex']?"sie":"er"),$taunt);
			$taunt = str_replace_c("%p",($session['user']['sex']?"ihr":"sein"),$taunt);
			$taunt = str_replace_c("%x",($session['user']['weapon']),$taunt);
			$taunt = str_replace_c("%X",$badguy['creatureweapon'],$taunt);
			$taunt = str_replace_c("%W",$badguy['creaturename'],$taunt);
			$taunt = str_replace_c("%w",$session['user']['name'],$taunt);
        addnews("`%".$session['user']['name']."`5 wurde im Wald von {$badguy['creaturename']} getötet.`n$taunt");
        $session['user']['alive']=0;
        $session['user']['gold']=0;
        $session['user']['hitpoints']=0;
        $session['user']['experience']=round($session['user']['experience'].9,0);
        $session['user']['badguy']='';
        output("`b`&Du wurdest von `%{$badguy['creaturename']}`& getötet!!!`n");
        output('`4Du hast all Dein Gold verloren!`n `410% Deiner Erfahrung ging verloren!`n Du kannst morgen wieder weiterspielen.');
        $session['user']['specialinc']='';
    }else{
        $session['user']['specialinc']='sacrificealtar.php';
        fightnav(true,true);
    }
    }
?>