<?php
/* *******************
Kryll ist Wissenschaftlicher Assistent in der Feenwerkstadt
by Fly
on 09/18/2004

little modyfications by Hadriel
überarbeitet von Tidus www.kokoto.de
******************* */
     
if ($_GET['op']=='exp'){
     output('`3Der Goblin stellt sich Dir als `@ Kryll `3 vor. Er möchte mit seinem Drachen einen Blitz einfangen und diesen dann mit seinen komischen Geräten analysieren. Doch leider kann er nicht gleichzeitig den Drachen halten und die seltsamen Knöpfe bedienen. `n`n `2"Könntest Du bitte den Drachen steigen lassen. Ich werde Dich natürlich für Deinen Einstatz belohnen!" `3 `n`n Du schaust zum Himmel und erkennst, dass sich das Wetter etwas verschlechtert hat. Es könnte ein Gewitter geben.');
     addnav('Kryll helfen','forest.php?op=help');
     addnav('Greif ihn an','forest.php?op=attack');
     addnav('Verschwinde','forest.php?op=back');
     $session['user']['specialinc']='kryll.php';
     }else if ($_GET['op']=='back'){
		output('`3Du willst dem Goblin nicht helfen und gehst zurück in den Wald');
		addnav('Zurück in den Wald','forest.php');
		$session['user']['specialinc']='';
     }else if ($_GET['op']=='attack'){
		output('`3Du ziehst Deine Waffe, doch der `@Goblin `3 verschwindet schreiend im Wald.');
		$Gold = $session['user']['level']100 ;
		output("`n`n Du betrachtest die verschiedenen Geräte und findest dabei $Gold Gold.");
		addnav('Zurück in den Wald','forest.php');
		$session['user']['gold']+= $Gold;
		$session['user']['specialinc']='';
     }else if ($_GET['op']=='help'){
     output('`3Du nimmst den Drachen und rennst eine Weile umher.`n`n');
     $Gold = $session['user']['level']100  mt_rand(1,3) ;
     switch (mt_rand(1,3))
            {
            case 1:
                 output('Doch es will und will einfach kein richtiges Gewitter aufkommen. `nNach einer Weile verziehen sich die Wolken und Ihr brecht das Experiment ab.`n');
                 output("`@Kryll `3 ist sichtlich enttäuscht und verschwindet, nachdem er Dir $Gold Gold gegeben hat,schweigend im Wald.");
                 addnav('Zurück in den Wald','forest.php');
                 $session['user']['gold']+= $Gold;
                 $session['user']['specialinc']='';
                 break;
            case 2:
            case 3:
                 output('Das Wetter wird immer schlechter und Du siehst auch schon vereizelnt Blitze.`n Auf einmal wird der Drache von einem Blitz erfaßt und Du fühlst, wie eine extreme Kraft in Deinen Körper eindringt.');
                 $lost = mt_rand(1,4);
                 }
switch(mt_rand(1,3)){
case 1:
case 2:
                 output("Der Schmerz ist unbegreiflich und Du wirst Dich wohl $lost ".($lost==1?"Runde ":"Runden ").'erholen müssen! `n`n `2"Noch eine Minute bitte! Dann ist es geschafft!" `3hörst du `@Kryll`3 schreien. `n`n Du gehst davon aus, dass Du sterben könntest, wenn Du die Qualen noch länger aushalten musst!');
                 $session['user']['turns']-= $lost;
                 if ($session['user']['turns']<0)
                      {$session['user']['turns']=0;}
                 addnav('Durchhalten','forest.php?op=fight');
                 addnav('Abbrechen','forest.php?op=break');
                 $session['user']['specialinc']='kryll.php';
                 break;
case 3:
                  output('`nPlötzlich schlägt ein Blitz in dich ein! Du hast keine Chance und verbrätst in der Energiewelle.`n Du bist tot!');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['gold']=0;
$session['user']['experience']0.95;
addnav('Tägliche News','news.php');
addnews($session['user']['name']. '`9 wurde erleuchtet!');
 break;
            }
     }else if ($_GET['op']=='break'){
     $Gold = $session['user']['level']100 ;
     output('`3Du läßt den Drachen los und brichst zusammen.`n`n`@Kryll `3 ist sichtlich enttäuscht und verschwindet, nachdem er Dir '.$Gold.' Gold gegeben hat, schweigend im Wald.');
     //addnav('Zurück in den Wald','forest.php');
     $session['user']['gold']+= $Gold;
     $session['user']['specialinc']='';
     }else if ($_GET['op']=='fight'){
     $Gold = $session['user']['level']100 mt_rand(1,3) ;
     output('`3Es durchströmt soviel Energie Deinen Körper, dass Du die Augen schließt und laut aufschreist.');
     switch(mt_rand(1,5))
            {
            case 1:
            case 2:
            case 3:
                 output('`3`n`nDu fällst leblos auf dei Erde. `n`n`^Du bist tot!`n `^Du verlierst 5% Deiner Erfahrung.`n Du verlierst all Dein Gold.`n Du kannst morgen wieder weiterspielen.`n`n');
                 $session['user']['alive']=false;
                 $session['user']['hitpoints']=0;
                 $session['user']['experience']*=0.95;
                 $session['user']['gold'] = 0;
                 addnav('Tägliche News','news.php');
                 addnews($session['user']['name'].'`9 wurde erleuchtet!');
                 output('`@Kryll `3ist sowas von erfreut über den Erfolg seines Experiments, dass er dein Ableben garnicht wahrnimmt.');
                 break;
            case 4:
            case 5:
                 output('`3 `n`n Du hälst den Schmerz nichtmehr aus und brichst trotz Deiner bemühungen zusammen. `nAls Du die Augen wieder aufmachst erblickst du `@Kryll`3 wie er über die Lichtung tanzt:`n`n `2"Wir haben es geschafft, geschafft, geschafft!"`n`n');
                 $exp= $session['user']['level']200;
                 output("`^Du erhälst $exp Erfahrung.`n");
                 output('Du verlierst einen Waldkampf.`n`n');
                 $session['user']['turns']--;
                 $session['user']['experience']+=$exp;
                 addnav('Zurück in den Wald','forest.php');
                 $session['user']['specialinc']='';
                 addnews($session['user']['name']." `9hat die Mächte der Natur überlebt und verteilt ein paar Elektroschocks!");
                 //$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session[user][acctid].",'/me `\^verteilt eine Runde Elektroschocks!')";
                 //db_query($sql) or die(db_error(LINK));
                 output('`@Kryll `3ist sowas von erfreut über den Erfolg seines Experiments, dass er ganz vergisst Dich auszuzahlen.');
                 break;   
			}

            output("Er rennt freude strahlend in den Wald. Später bemerkt er, dass er Dich nicht ausgezahlt hat und bringt Dir $Gold auf die Bank.`n");
            $session['user']['goldinbank'] += $Gold;
            }else{
                 output('`3 Du hörst komische Geräusche und beschließt diesen auf den Grund zu gehen: `n Sie führen Dich zu einer Lichtung auf der ein `@grüner Goblin `3 zwischen einem Haufen komischer Geräte herumspringt und dabei einen Papierdrachen steigen läßt. Als Dich der Goblin sieht, winkt er Dir zu und ruft: `n`n `2"Hallo Fremder, könntest Du mir bitte bei meinem Experiment helfen?" `n`n');
     addnav('Höre zu was er vorhat','forest.php?op=exp');
     addnav('Greif ihn an','forest.php?op=attack');
     addnav('Verschwinde','forest.php?op=back');
     $session['user']['specialinc']='kryll.php';
     }
            
?>