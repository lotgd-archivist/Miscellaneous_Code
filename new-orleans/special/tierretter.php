
<?php
    /*-------------------------------------------
    # by Lunastra                                #
    # Idee: Lunastra                             #
    # Original auf: lunaria-logd.de              #
    #                                            #
    # Bitte lasst mein Kästchen stehen, danke :3 #
    --------------------------------------------*/
    
/* Anmerkung: Da es mein erstes eigenes Waldspecial ist, habe ich mich bezüglich Bezeichnungen
             und Berechnung der Werte etwas an anderen Specials orientiert.^^
*/

 //Start
if($_GET[op]==""){
    output("`n`n`c`b`tHund, Katze, Maus`b`c`n");
    output("`TDu streifst durch den Wald und denkst dir nichts böses, also du plötzlich einen Ohrenbetäubenden Lärm
            vernimmst. Schnell folgst du dem Geräusch und triffst auf `teinen Hund, eine Katze und eine Maus`T, die
            sich wie angestochen jagen. Es ist ein witziger Anblick wie sich die drei Tiere immerwieder im Kreis jagen,
            aber tuen sie dir nicht ein wenig Leid? Jetzt ist eine Entscheidung von Nöten, welches Tier willst du retten?
            Oder überlässt du sie einfach ihrem Schicksal?");
    addnav("Maus retten","forest.php?op=maus");
    addnav("Katze retten","forest.php?op=katze");
    addnav("Hund retten","forest.php?op=hund");
    addnav("Zurück in den Wald","forest.php?op=go");
    $session['user']['specialinc']="tierretter.php";
}
//Maus retten
if($_GET[op]=="maus"){
    output("`TDu fasst dir ein Herz und rettest das arme kleine Mäuschen...");
    $tier1 = e_rand(1,10);
    switch ($tier1)
    {
        case 1:
        case 2:
        case 3:
        case 4:
                output("`TDu hast dich für das Mäuschen entschieden, und greifst in das Geschehen ein.
                        Deine Heldentat kostet dich einen Waldkampf. Schnell bringst du die Maus in Sicherheit.
                        Das Mäuschen schenkt dir für seine Rettung ein Stück Käse. Nunja, nicht gerade der
                        beste Lohn, aber immerhin hast du eine Gute Tat vollbracht. Du steckst das Stück Käse in den Mund,
                        doch als du daraufherumkaust spürst du plötzlich etwas hartes. Schnell spuckst du es aus
                        und stellst fest das du einen `#Edelstein `Tin deinen Händen hälst. Fröhlich maschierst du
                        wieder zurück in den Wald.");
                $session['user']['specialinc']="";
                $sesssion['user']['turns']--;
                $session['user']['gems']++;
                addnav("In den Wald","forest.php");
                break;
        case 5:
        case 6:
        case 7:
        case 8:
                $gold = e_rand($session['user']['level']*5,$session['user']['level']*50);
                output("`TDu schnappst dir sofort die kleine Maus, und bringst sie in Sicherheit. Diese piepst dich
                        dankbar an und schiebt dir einen kleinen Beutel entgegen, dann verschwindet sie ganz schnell
                        im nächstbesten Loch. Als du den Beutel öffnest kannst du `^".$gold." Goldstücke`T darin zählen.");
                $session['user']['specialinc']="";
                $session['user']['gold']+=$gold;
                addnav("In den Wald","forest.php");
                break;
        case 9:
        case 10:
                output("`TDu eilst sofort zur Rettung des armen kleinen Mäuschens herbei. Du ziehst deine Waffe
                        ".$session['user']['weapon']."`T und schlägst Katze und Hund bewustlos, dann lässt du das Mäuschen
                        fliehen welches sich schnell ins nächste Loch verkriecht. In der Zwischenzeit wachen die Katze 
                        und der Hund wieder auf. Du willst noch fliehen aber...`n
                        `\$Katze und Hund starren die bösartig an, plötzlich gehen sie auf dich los!`n");
                addnav("Kämpfe!","forest.php?op=kampf1");
                $session['user']['specialinc']="tierretter.php";
                break;
    }
    
}

//Kampf Hund & Katze
elseif($_GET[op]=="kampf1"){
  $fight1 = e_rand(1,5);
    switch ($fight1)
    { 
        case 1:
        case 2:
               $session['user']['specialinc']="";
               $gem_gain = rand(1,2);
               $gold_gain = rand($session['user']['level']*5,$session['user']['level']*50);
               output("`TDu kämpfst erbittert gegen Hund und Katze, endlich hat es ein Ende. Stolz aber erledigt
                        durchwühlst du deren Überreste. Du findest `^".$gold_gain." Gold`T und`# ".$gem_gain." Edelsteine");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']*=0.5;
               $session['user']['gold']+=$gold_gain;
               $session['user']['gems']+=$gem_gain;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;               
        case 3:
        case 4:
               $session['user']['specialinc']="";
               output("`\$Du lieferst dir einen erbitterten Kampf mit der Katze und dem Hund. Du hast keine Chance
                        hier zu überleben und stirbst schließlich eines qualvollen Todes.");
               addnav("Tägliche News","news.php");
               addnews($session['user']['name']." `Twurde zerbissen und zerkratzt im Wald gefunden.");
               
               $session['user']['alive']=false;
               $session['user']['hitpoints']=0;
               break;               
        case 5:
               $session['user']['specialinc']="";
               output("`TDu wirst gekratzt und gebissen und glaubst das du hier garnicht mehr entkommen kannst,
                      doch plötzlich verlieren sie das Interesse an dir und stöbern nach der Maus.
                      Schwer verwundet schleppst du dich in den Wald zurück. Die ganze Prozudur kostete dich zusätzlich ein paar Waldkämpfe.");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']=1;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;          
    }          
}
//Katze retten
elseif($_GET[op]=="katze"){
    output("`TDu fasst dir ein Herz und rettest die arme Katze...");
    $tier2 = e_rand(1,10);
    switch ($tier2)
    {
        case 1:
        case 2:
        case 3:
        case 4:
                $session['user']['specialinc']="";
                output("`TDu entschließt dich das Kätzchen zu retten und stürtzt dich zwischen die Tiere. Nach einer
                          Weile ist die Katze in Sicherheit. Erschöpft lässt du sie laufen. Bevor sie verschwinden,
                          legt sie dir noch einen rohen Fisch vor deine Füße. Du hebst ihn auf, plötzlich fällt etwas
                          schimmerdes aus seinem Maul auf den Boden. Du hebst es auf und beginnst zu strahlen, denn nun
                          bist du um `#einen Edelstein`T reicher geworden.");
                $sesssion['user']['turns']--;
                $session['user']['gems']++;
                addnav("In den Wald","forest.php");
                break;
        case 5:
        case 6:
        case 7:
        case 8:
                $session['user']['specialinc']="";
                $gold = e_rand($session['user']['level']*10,$session['user']['level']*90);
                output("`TDu schnappst dir sofort die Katze, und bringst sie in Sicherheit. Diese mauzt dich
                        dankbar an und schiebt dir einen kleinen Beutel entgegen, dann verschwindet sie ganz schnell
                        im dichten Gebüsch. Als du den Beutel öffnest kannst du `^".$gold." Goldstücke`T darin zählen.");
                $session['user']['gold']+=$gold;
                addnav("In den Wald","forest.php");
                break;
        case 9:
        case 10:
                output("`TDu eilst sofort zur Rettung der armen Katze herbei. Du ziehst deine Waffe
                        ".$session['user']['weapon']."`T und schlägst Maus und Hund bewustlos, dann lässt du das Kätzchen
                        fliehen welches sich schnell ins Gebüsch flüchtet. In der Zwischenzeit wachen die Maus
                        und der Hund wieder auf. Du willst noch fliehen aber...`n
                        `\$Maus und Hund starren die bösartig an, plötzlich gehen sie auf dich los!`n");
                addnav("Kämpfe!","forest.php?op=kampf2");
                $session['user']['specialinc']="tierretter.php";
                break;
    }

}

//Kampf Hund & Maus
elseif($_GET[op]=="kampf2"){
    $fight2 = e_rand(1,5);
    switch ($fight2)
    {
        case 1:
        case 2:
               $session['user']['specialinc']="";
               $gem_gain = rand(1,2);
               $gold_gain = rand($session['user']['level']*10,$session['user']['level']*90);
               output("`TDu kämpfst erbittert gegen Hund und Maus, endlich hat es ein Ende. Stolz aber erledigt
                        durchwühlst du deren Überreste. Du findest `^".$gold_gain." Gold`T und`# ".$gem_gain." Edelsteine");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']*=0.7;
               $session['user']['gold']+=$gold_gain;
               $session['user']['gems']+=$gem_gain;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;
        case 3:
        case 4:
               $session['user']['specialinc']="";
               output("`\$Du lieferst dir einen erbitterten Kampf mit der Maus und dem Hund. Du hast keine Chance
                        hier zu überleben und stirbst schließlich eines qualvollen Todes.");
               addnav("Tägliche News","news.php");
               addnews($session['user']['name']." `Twurde zerbissen und angenagt im Wald gefunden.");
               
               $session['user']['alive']=false;
               $session['user']['hitpoints']=0;
               break;
        case 5:
               $session['user']['specialinc']="";
               output("`TDu wirst angenagt und gebissen und glaubst das du hier garnicht mehr entkommen kannst,
                      doch plötzlich verlieren sie das Interesse an dir und stöbern nach der Katze.
                      Schwer verwundet schleppst du dich in den Wald zurück. Die ganze Prozudur kostete dich zusätzlich ein paar Waldkämpfe.");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']=1;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;
    }
}    
//Hund retten
elseif($_GET[op]=="hund"){
    output("`TDu fasst dir ein Herz und rettest den Hund...");
    $tier13 = e_rand(1,10);
    switch ($tier3)
    {
        case 1:
        case 2:
        case 3:
        case 4:
                $session['user']['specialinc']="";
                output("`TDu beschließst den Hund zu retten und stürmst zwischen die Fronten. Irgendwann geben Katze 
                        und Maus auf und der Hund ist in Sicherheit. Fröhlich bellt er und buddelt einen Knochen aus
                        den er dir sogleich als Dankeschön vor die Füße legt, dann läuft er schnell zurück ins Dorf.
                        Du hebst den Knochen auf und überlegst was du damit anfangen sollst, als dieser plötzlich zerbricht.
                        Zu deiner Überraschung fällt `#ein Edelstein `Theraus.");
                $sesssion['user']['turns']--;
                $session['user']['gems']++;
                addnav("In den Wald","forest.php");
                break;
        case 5:
        case 6:
        case 7:
        case 8:
                $session['user']['specialinc']="";
                $gold = e_rand($session['user']['level']*20,$session['user']['level']*90);
                output("`TDu schnappst dir sofort den Hund, und bringst ihn in Sicherheit. DDieser bellt dich
                        dankbar an und schiebt dir einen kleinen Beutel entgegen, dann verschwindet er ganz schnell
                        zurück ins Dorf zu seinem Besitzer. Als du den Beutel öffnest kannst du `^".$gold." Goldstücke`T 
                        darin zählen.");
                $session['user']['gold']+=$gold;
                addnav("In den Wald","forest.php");
                break;
        case 9:
        case 10:
                output("`TDu eilst sofort zur Rettung des armen Hundes herbei. Du ziehst deine Waffe
                        ".$session['user']['weapon']."`T und schlägst Maus und Katze bewustlos, dann lässt du den Hund
                        fliehen welcher sich schnell ins Dorf zu seinem Besitzer flüchtet. In der Zwischenzeit wachen 
                        die Maus und die Katze wieder auf. Du willst noch fliehen aber...`n
                        `\$Katze und Maus starren die bösartig an, plötzlich gehen sie auf dich los!`n");
                addnav("Kämpfe!","forest.php?op=kampf3");
                $session['user']['specialinc']="tierretter.php";
                break;
    }

}

//Kampf Katze & Maus
elseif($_GET[op]=="kampf3"){
    $fight3 = e_rand(1,5);
    switch ($fight3)
    {
        case 1:
        case 2:
               $session['user']['specialinc']="";
               $gem_gain = rand(1,2);
               $gold_gain = rand($session['user']['level']*20,$session['user']['level']*90);
               output("`TDu kämpfst erbittert gegen Katze und Maus, endlich hat es ein Ende. Stolz aber erledigt
                        durchwühlst du deren Überreste. Du findest ``^".$gold_gain." Gold`T und`# ".$gem_gain." Edelsteine");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']*=0.9;
               $session['user']['gold']+=$gold_gain;
               $session['user']['gems']+=$gem_gain;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;
        case 3:
        case 4:
               $session['user']['specialinc']="";
               output("`\$Du lieferst dir einen erbitterten Kampf mit der Maus und der Katze. Du hast keine Chance
                        hier zu überleben und stirbst schließlich eines qualvollen Todes.");
               addnav("Tägliche News","news.php");
               addnews($session['user']['name']." `Twurde zerbissen und angenagt im Wald gefunden.");
               
               $session['user']['alive']=false;
               $session['user']['hitpoints']=0;
               break;
        case 5:
               $session['user']['specialinc']="";
               output("`TDu wirst angenagt und gekratzt und glaubst das du hier garnicht mehr entkommen kannst,
                      doch plötzlich verlieren sie das Interesse an dir und stöbern nach dem Hund.
                      Schwer verwundet schleppst du dich in den Wald zurück. Die ganze Prozudur kostete dich zusätzlich ein paar Waldkämpfe.");
               addnav("Zurück in den Wald","forest.php");
               
               $session['user']['hitpoints']=1;
               if($session['user']['turns']>=3){
                    $session['user']['turns']-=3;
               }else{
                    $session['user']['turns']=0;
               }
               break;
    }
}   
//Gehen
elseif($_GET[op]=="go"){

    $session['user']['specialinc']="";
    output("`TWas interessieren dich denn diese blöden Viecher? Schnell machst du dich wieder auf den Weg.
            `n`tDoch für deine Kaltherzigkeit verlierst du ein paar Waldkämpfe!");
    if($session['user'][turns]>=3){
                    $session['user']['turns']-=3;
    }else{
                    $session['user']['turns']=0;
    }
    addnav("Zurück in den Wald","forest.php");
}




?>

