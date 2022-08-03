<?php
/**
* _______________________________________________
* | postkutsche.php:                            |
* |                                             |
* | hiermit kann man alle Bankgeschäfte aus dem |
* | Wald heraus tätigen, ohne dabei dem Meister |
* | über den Weg zu laufen.                     |
* |                                             |
* | ©2009 by Liath www.germany-project.de/logd  |
* | programmiert in der LoGD DragonSlayer 2.5   |
* |                                             |
* | fertiggestellt:                             |
* | am 07. Februar 2009 um 16°° Uhr             |
* |                                             |
* | Benutzung, Veränderungen, Verschönerungen,  |
* | oder auch eigene Anpassungen sind erlaubt,  |
* | solange dieser Header beibehalten wird und  |
* | die Datei im Source offen gehalten wird.    |
* |                                             |
* | Changelog:                                  |
* |                                             |
* | 08.02.09                                    |
* | Zufallsereignisse eingebaut                 |
* | Bug behoben, das mehr eingezahlt/abgehoben  |
* | werden konnte wie vorhanden war             |
* |                                             |
* ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
  Eigene Veränderungen dürfen ab hier
  gekennzeichnet werden:
* _______________________________________________ 
* | Author:                                     |
* | Date:                                       |
* | Changed:                                    |
* ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯ 

* _______________________________________________ 
* | Einbauanleitung                             |
* |                                             |
* | dieses Addon ist auch auf Edelsteine in der |
* | Bank ausgelegt, um die Funktion zu          |
* | aktivieren, stell einfach die Variable      |
* | $allowgems = '0'; auf '1'                   |
* |                                             |
* | um die Zufallsereignisse an- oder auszu-    |
* | schalten setze die Variable "$randomeffect" |
* | auf '0' für aus und '1' für an              |
* |                                             |
* | öffne die common.php und such nach          |
* | "Sonstiges" danach trag folgendes ein:      |
* |                                             |
* | addnav("Postkutsche","postkutsche.php");    |
* |                                             |
* | in den Variablen $file, $ort und $back      |
* | kannst Du noch den zu verlinkenden Ort und  |
* | den Namen eintragen, von wo Du gekommen     |
* | bist.                                       |
* |                                             |
* | danach Änderungen speichern, diese Datei in |
* | den Root von Deinem LoGD kopieren, fertig   |
* ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
*/

require_once "common.php";
page_header("die Postkutsche");

$file = 'postkutsche.php';
$ort = 'zurück in den Wald';
$back = 'forest.php';

// Bestimmung des Accountnamens, der Anrede und des Geschlechts
$name = $session['user']['name'];
$anr = ($session['user']['sex']?"`2Werte`t":"`2Werter`t");
$du = "$anr $name";

// Edelsteine und Zufallsereignisse erlauben (zum deaktivieren auf '0' setzen)
$allowgems = '1';
$randomeffect = '0';

// Das anzuzeigende Bild wird bestimmt
//$img_post = './images/postkutsche.jpg';

// Es wird überprüft ob das Bild existiert, wenn ja angezeigt
//if (is_file($img_post)) { output('<div align="center"><img border="0" src="'.$img_post.'" alt="die Postkutsche"></div>`n`n'); }

if ($_GET['op']==''){

    output("`t`cLangsam und vorsichtig schleichst Du durchs Gebüsch: `dHier muss es doch irgendwo sein`t, fluchst Du. Du bist auf der Suche nach der Postkutsche 
            von der aus Du schnell und sicher Deine Ersparnisse abheben oder einzahlen kannst. Wenige Minuten später steht sie endlich da. `gSeid mir gegrüsst 
            ".$du.", `gwas kann ich heute für Euch tun? `tDu überlegst einen kleinen Moment...`n`n
    ");
    if ($session['user']['gold'] || ($allowgems=='1' && $session['user']['gems'])) {
        output("<form action='".$file."?op=give&act=sel' method='POST'>`tWieviel möchtest Du einzahlen? ",true);
        if ($session['user']['gold']) {
            output("<input id='send' name='gold' size='10' maxlength='10'>`t Gold ",true);
        }
        if ($allowgems=='1' && $session['user']['gems']){
            output("<input id='send' name='gems' size='10' maxlength='10'>`4 Edelsteine ",true);
        } 
        output("<script language='javascript'>document.getElementById('send').focus();</script>",true);
        output("<input type='submit' class='button' value='Einzahlen'>`n`n</form>",true);
        addnav("","".$file."?op=give&act=sel");
    }
    if ($session['user']['goldinbank'] || ($allowgems=='1' && $session['user']['gemsinbank'])) {
        output("<form action='".$file."?op=get&act=sel' method='POST'>`tWieviel möchtest Du abheben? ",true);
        if ($session['user']['goldinbank']) {    
            output("<input id='send' name='gold' size='10' maxlength='10'>`t Gold ",true);
        }
        if ($allowgems=='1' && $session['user']['gemsinbank']){
            output("<input id='send' name='gems' size='10' maxlength='10'>`4 Edelsteine ",true);
        } 
        output("<script language='javascript'>document.getElementById('send').focus();</script>",true);
        output("<input type='submit' class='button' value='Abheben'>`n`n`n`n`c</form>",true);
        addnav("","".$file."?op=get&act=sel");
    }
    if ($session['user']['gold'] || $session['user']['gems']) {
        addnav("Einzahlen");
        addnav("Alles","$file?op=give&act=all");
        addnav("Hälfte","$file?op=give&act=half");
    }
    if ($session['user']['goldinbank'] || $session['user']['gemsinbank']) {
        addnav("Abheben");
        addnav("Alles","$file?op=get&act=all");
        addnav("Hälfte","$file?op=get&act=half");
    }
    addnav("Wege");
    addnav("$ort","$back");        

}
else if ($_GET['op']=='give'){
    
    if ($randomeffect=='1') { $effect = e_rand(1,10); }
    else { $effect = '1'; }
 
    if ($_GET['act']=='all') {
        
        if ($effect>='1' && $effect<='8') {
           
                $gold = $session['user']['gold'];
                $givegold = "`t".$gold." Gold`t";
                $session['user']['goldinbank']+=$gold;
                $session['user']['gold']-=$gold; 

           if ($allowgems=='1') {

                $gems = $session['user']['gems']; 
                $givegems = "`4".$gems." Edelsteine`t"; 
                $session['user']['gemsinbank']+=$gems;
                $session['user']['gems']-=$gems;
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihre ".$text." `tumgehend zur Bank bringen. Sollten Sie weitere Wünsche haben
                    können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir eine Nachricht
                    vom Bankier in die Hand: ".$du." `FDanke für die Einzahlung auf Ihr Konto, Ihr aktueller Kontostand ist jetzt `t".$session['user']['goldinbank']." Gold `Fund 
                    `4".$session['user']['gemsinbank']." Edelsteine.
            ");
        }
        else if ($effect=='9') {
               
                $session['user']['gold']-=$session['user']['gold']; 

            if ($allowgems=='1') {

                $session['user']['gems']-=$session['user']['gems'];
            }
    
            output("`tDer Kutscher schnappt sich Deinen Beutel und macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
            ");
        }
        else if ($effect=='10') {
               
                $gewinn = e_rand(5,15);
                $gold = round(((($session['user']['gold']*$gewinn) /100)),0);
                $session['user']['goldinbank']+=$gold;
                $session['user']['gold']-=$session['user']['gold'];
                $givegold = "`t".$session['user']['goldinbank']." Gold`t"; 

            if ($allowgems=='1') {

                $gewinn = e_rand(5,15);
                $gems = round(((($session['user']['gems']*$gewinn) /100)),0); 
                $session['user']['gemsinbank']+=$gems;
                $session['user']['gems']-=$session['user']['gems'];
                $givegems = "`4".$session['user']['gemsinbank']." Edelsteine`t";
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
            
            output("Variablen:`n".$gold." Gold`n".$gems."`n`n");
    
            output("`tDer Kutscher bedankt sich für Dein Vertrauen, nimmt Deinen Beutel und eilt zur Kutsche. Auf dem Weg stolpert er über einen Stein und 
                    lässt dabei mehrere Beutel fallen. Als er von der Bank wieder kommt und Dir den Beleg des Bankiers aushändigt, wunderst Du Dich über
                    Deinen Kontostand, er muss wohl Deinen Beutel mit dem eines anderen verwechselt haben. Dein neuer Kontostand beträgt ".$text.".
            ");
        }
        
    }
    
    else if ($_GET['act']=='half') {
        
        if ($effect>='1' && $effect<='8') {
        
                $gold = round(($session['user']['gold']*.5),0);
                $givegold = "`t".$gold." Gold`t";
                $session['user']['goldinbank']+=$gold;
                $session['user']['gold']-=$gold; 

            if ($allowgems=='1') {
            
                $gems = round(($session['user']['gems']*.5),0); 
                $givegems = "`4".$gems." Edelsteine`t";
                $session['user']['gemsinbank']+=$gems;
                $session['user']['gems']-=$gems;
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihre ".$text." `tumgehend zur Bank bringen. Sollten Sie weitere Wünsche haben
                    können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir eine Nachricht
                    vom Bankier in die Hand: ".$du." `FDanke für die Einzahlung auf Ihr Konto, Ihr aktueller Kontostand ist jetzt `t".$session['user']['goldinbank']." Gold `Fund 
                    `4".$session['user']['gemsinbank']." Edelsteine.
                ");
        }
        else if ($effect=='9') {               
               
                $gold = round(($session['user']['gold']*.5),0);
                $session['user']['gold']-=$gold; 

            if ($allowgems=='1') {

                $gems = round(($session['user']['gems']*.5),0); 
                $session['user']['gems']-=$gems;
            }
    
            output("`tDer Kutscher schnappt sich Deinen Beutel und macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
            ");
        }
        else if ($effect=='10') {

                $gewinn = e_rand(5,15);
                $gold = round((((($session['user']['gold']*.5)*$gewinn) /100)),0);
                $session['user']['goldinbank']+=$gold;
                $session['user']['gold']-=$session['user']['gold'];
                $givegold = "`t".$session['user']['goldinbank']." Gold`t"; 

            if ($allowgems=='1') {

                $gewinn = e_rand(5,15);
                $gems = round((((($session['user']['gems']*.5)*$gewinn) /100)),0);  
                $session['user']['gemsinbank']+=$gems;
                $session['user']['gems']-=$session['user']['gems'];
                $givegems = "`4".$session['user']['gemsinbank']." Edelsteine`t";
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
            
            output("Variablen:`n".$gold." Gold`n".$gems."`n`n");
    
            output("`tDer Kutscher bedankt sich für Dein Vertrauen, nimmt Deinen Beutel und eilt zur Kutsche. Auf dem Weg stolpert er über einen Stein und 
                    lässt dabei mehrere Beutel fallen. Als er von der Bank wieder kommt und Dir den Beleg des Bankiers aushändigt, wunderst Du Dich über
                    Deinen Kontostand, er muss wohl Deinen Beutel mit dem eines anderen verwechselt haben. Dein neuer Kontostand beträgt ".$text.".
            ");
        }
    }
    
    else if ($_GET['act']=='sel') {        
        if (empty($_POST['gold']) && empty($_POST['gems'])) {
            
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tAber Sie müssen sich schon entscheiden ob ich Ihre Ersparnisse zur Bank bringen soll oder nicht.
                    Ich kann ja nicht mit leeren Händen in der Bank auftauchen.
            ");
        }
        else if ($_POST['gold']>$session['user']['gold'] || $_POST['gems']>$session['user']['gems']) {
            output("`tTut mir leid ".$du.". `tAber Sie können nur einzahlen was Sie auch wirklich bei sich haben, einen Kredit kann ich Ihnen leider nicht gewähren");    
        }
        else {
        
            if ($effect>='1' && $effect<='8') {            
                if ($_POST['gold']) {
             
                    $givegold = "`t".$_POST['gold']." Gold`t";
                    $session['user']['goldinbank']+=$_POST['gold'];
                    $session['user']['gold']-=$_POST['gold']; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
             
                    $givegems = "`4".$_POST['gems']." Edelsteine`t"; 
                    $session['user']['gemsinbank']+=$_POST['gems'];
                    $session['user']['gems']-=$_POST['gems'];
                }
    
                if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
                else { $text = $givegold.$givegems; }
    
                output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihre ".$text." `tumgehend zur Bank bringen. Sollten Sie weitere Wünsche haben
                        können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir eine Nachricht
                        vom Bankier in die Hand: ".$du." `FDanke für die Einzahlung auf Ihr Konto, Ihr aktueller Kontostand ist jetzt `t".$session['user']['goldinbank']." Gold `Fund 
                        `4".$session['user']['gemsinbank']." Edelsteine.
                ");
            }
            
            
            else if ($effect=='9') {            
                if ($_POST['gold']) {
             
                    $session['user']['gold']-=$_POST['gold']; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
              
                    $session['user']['gems']-=$_POST['gems'];
                }
    
                output("`tDer Kutscher schnappt sich Deinen Beutel und macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
                ");
            }
            else if ($effect=='10') {            
                if ($_POST['gold']) {
                    
                    $gewinn = e_rand(5,15);
                    $gold = round(((($_POST['gold']*$gewinn) /100)),0);                                  
                    $session['user']['goldinbank']+=$gold;
                    $session['user']['gold']-=$_POST['gold'];
                    $givegold = "`4".$session['user']['goldinbank']." Gold`t"; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
                    
                    $gewinn = e_rand(5,15);
                    $gems = round(((($_POST['gems']*$gewinn) /100)),0);
                    $session['user']['gemsinbank']+=$gems;
                    $session['user']['gems']-=$_POST['gems'];
                    $givegems = "`4".$session['user']['gemsinbank']." Edelsteine`t";
                }
    
                if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
                else { $text = $givegold.$givegems; }
    
                output("`tDer Kutscher bedankt sich für Dein Vertrauen, nimmt Deinen Beutel und eilt zur Kutsche. Auf dem Weg stolpert er über einen Stein und 
                    lässt dabei mehrere Beutel fallen. Als er von der Bank wieder kommt und Dir den Beleg des Bankiers aushändigt, wunderst Du Dich über
                    Deinen Kontostand, er muss wohl Deinen Beutel mit dem eines anderen verwechselt haben. Dein neuer Kontostand beträgt ".$text.".
                ");
            }
        }
    }
    
    addnav("Wege");
    addnav("zurück","$file");
    addnav("$ort","$back");        
}
else if ($_GET['op']=='get'){

    if ($randomeffect=='1') { $effect = e_rand(1,10); }
    else { $effect = '1'; }
    
    if ($_GET['act']=='all') {
        
        if ($effect>='1' && $effect<='8') {
           
                $gold = $session['user']['goldinbank'];
                $givegold = "`t".$gold." Gold`t";
                $session['user']['goldinbank']-=$gold;
                $session['user']['gold']+=$gold; 

           if ($allowgems=='1') {

                $gems = $session['user']['gemsinbank']; 
                $givegems = "`4".$gems." Edelsteine`t"; 
                $session['user']['gemsinbank']-=$gems;
                $session['user']['gems']+=$gems;
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihnen Ihre ".$text." `tumgehend von der Bank holen. Sollten Sie weitere Wünsche haben
                    können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir 
                    einen Beutel und eine Nachricht vom Bankier in die Hand: ".$du." `FDanke für die Abhebung von Ihrem Konto. Damit ist Ihr Kontostand jetzt bei 0.
                    `tDu zählst eifrig Deine Ersparnisse und stellst fest das Du jetzt `t".$session['user']['gold']." `tGold und `4".$session['user']['gems']." Edelsteine `tin der Tasche hast.
            ");
        }
        else if ($effect=='9') {
               
                $session['user']['goldinbank']-=$session['user']['goldinbank']; 

            if ($allowgems=='1') {

                $session['user']['gemsinbank']-=$session['user']['gemsinbank'];
            }
    
            output("`tDer Kutscher macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
            ");
            
        }
        else if ($effect=='10') {
               
                $gewinn = e_rand(5,15);
                $gold = round(((($session['user']['goldinbank']*$gewinn) /100)),0);
                $session['user']['goldinbank']-=$session['user']['goldinbank'];
                $session['user']['gold']+=$gold;
                $givegold = "`t".$session['user']['gold']." Gold`t"; 

            if ($allowgems=='1') {

                $gewinn = e_rand(5,15);
                $gems = round(((($session['user']['gemsinbank']*$gewinn) /100)),0); 
                $session['user']['gemsinbank']-=$session['user']['gemsinbank'];
                $session['user']['gems']+=$gold;
                $givegems = "`4".$session['user']['gems']." Edelsteine`t";
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tDer Kutscher bedankt sich für Dein Vertrauen und eilt zur Kutsche. Als er von der Bank zurückkommt und Dir Deinen Beutel aushändigt,
                    bemerkst Du beim nachzählen, daß der Bankier sich vertan haben muss und Dir zuviel ausgezahlt hat. Du hast jetzt ".$text." in Deiner Tasche.
            ");
        }
        
    }
    
    else if ($_GET['act']=='half') {
        
        if ($effect>='1' && $effect<='8') {
        
                $gold = round(($session['user']['goldinbank']*.5),0);
                $givegold = "`t".$gold." Gold`t";
                $session['user']['goldinbank']-=$gold;
                $session['user']['gold']+=$gold; 

            if ($allowgems=='1') {
            
                $gems = round(($session['user']['gemsinbank']*.5),0); 
                $givegems = "`4".$gems." Edelsteine`t";
                $session['user']['gemsinbank']-=$gems;
                $session['user']['gems']+=$gems;
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihnen Ihre ".$text." `tumgehend von der Bank holen. Sollten Sie weitere Wünsche haben
                    können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir 
                    einen Beutel und eine Nachricht vom Bankier in die Hand: ".$du." `FDanke für die Abhebung von Ihrem Konto. Damit ist Ihr Kontostand jetzt bei 
                    `t".$session['user']['goldinbank']." Gold `Fund `4".$session['user']['gemsinbank']." Edelsteinen. `tDu zählst eifrig Deine Ersparnisse und stellst 
                    fest das Du jetzt `t".$Session['user']['gold']." `tGold und `4".$Session['user']['gems']." Edelsteine `tin der Tasche hast.
                ");
        }
        else if ($effect=='9') {               
               
                $gold = round(($session['user']['goldinbank']*.5),0);
                $session['user']['goldinbank']-=$gold; 

            if ($allowgems=='1') {

                $gems = round(($session['user']['gemsinbank']*.5),0); 
                $session['user']['gemsinbank']-=$gems;
            }
    
            output("`tDer Kutscher schnappt sich Deinen Beutel und macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
            ");
        }
        else if ($effect=='10') {
               
                $gewinn = e_rand(5,15);
                $gold = round((((($session['user']['goldinbank']*.5)*$gewinn) /100)),0);
                $session['user']['goldinbank']-=$session['user']['gold'];
                $session['user']['gold']+=$gold;
                $givegold = "`t".$session['user']['goldinbank']." Gold`t"; 

            if ($allowgems=='1') {

                $gewinn = e_rand(5,15);
                $gems = round((((($session['user']['gemsinbank']*.5)*$gewinn) /100)),0);  
                $session['user']['gemsinbank']-=$session['user']['gems'];
                $session['user']['gems']+=$gems;
                $givegems = "`4".$session['user']['gemsinbank']." Edelsteine`t";
            }
        
            if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
            else { $text = $givegold.$givegems; }
    
            output("`tDer Kutscher bedankt sich für Dein Vertrauen und eilt zur Kutsche. Als er von der Bank zurückkommt und Dir Deinen Beutel aushändigt,
                    bemerkst Du beim nachzählen, daß der Bankier sich vertan haben muss und Dir zuviel ausgezahlt hat. Du hast jetzt ".$text." in Deiner Tasche.
            ");
        }
    }
    
    else if ($_GET['act']=='sel') {        
        if (empty($_POST['gold']) && empty($_POST['gems'])) {
            
            output("`tVielen Dank für Ihr Vertrauen ".$du.". `tAber Sie müssen sich schon entscheiden ob ich Ihre Ersparnisse zur Bank bringen soll oder nicht.
                    Ich kann ja nicht mit leeren Händen in der Bank auftauchen.
            ");
        }
        else if ($_POST['gold']>$session['user']['goldinbank'] || $_POST['gems']>$session['user']['gemsinbank']) {
            output("`Tut mir leid ".$du.". `tAber Sie können nur abheben was Sie auch wirklich auf Ihrem haben, einen Kredit kann ich Ihnen leider nicht gewähren");    
        }
        else {
        
            if ($effect>='1' && $effect<='8') {            
                if ($_POST['gold']) {
             
                    $givegold = "`t".$_POST['gold']." Gold`t";
                    $session['user']['goldinbank']-=$_POST['gold'];
                    $session['user']['gold']+=$_POST['gold']; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
             
                    $givegems = "`4".$_POST['gems']." Edelsteine`t"; 
                    $session['user']['gemsinbank']-=$_POST['gems'];
                    $session['user']['gems']+=$_POST['gems'];
                }
    
                if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
                else { $text = $givegold.$givegems; }
    
                output("`tVielen Dank für Ihr Vertrauen ".$du.". `tIch werde Ihnen Ihre ".$text." `tumgehend von der Bank holen. Sollten Sie weitere Wünsche haben
                    können Sie mich natürlich jederzeit wieder aufsuchen. Ich stehe stets treu zu Ihren Diensten`n`n Als der Kutscher zurückkommt drückt er Dir 
                    einen Beutel und eine Nachricht vom Bankier in die Hand: ".$du." `FDanke für die Abhebung von Ihrem Konto. Damit ist Ihr Kontostand jetzt bei 
                    `t".$session['user']['goldinbank']." Gold `Fund `4".$session['user']['gemsinbank']." Edelsteinen. `tDu zählst eifrig Deine Ersparnisse und stellst 
                    fest das Du jetzt `t".$Session['user']['gold']." `tGold und `4".$Session['user']['gems']." Edelsteine `tin der Tasche hast.
                ");
            }
            
            
            else if ($effect=='9') {            
                if ($_POST['gold']) {
             
                    $session['user']['goldinbank']-=$_POST['gold']; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
              
                    $session['user']['gemsinbank']-=$_POST['gems'];
                }
    
                output("`tDer Kutscher schnappt sich Deinen Beutel und macht sich auf den Weg. Als er nach einer geschlagenen Stunde nicht wieder auftaucht,
                    machst Du Dich besorgt um Deine Ersparnisse auf die Suche nach ihm. Kurze Zeit später findest Du ihn niedergeschlagen neben seiner 
                    Kutsche liegend. `FEntschuldigen Sie ".$du." aber ich wurde von Gaunern ausgeraubt, es ist alles weg, wirklich alles. Es tut mir leid,
                    aber auch Ihre Ersparnisse sind den Gaunern dabei in die Hände gefallen. `t Enttäuscht und sauer über den Verlust machst Du Dich wieder
                    auf den Weg in den Wald.
                ");
            }
            else if ($effect=='10') {            
                if ($_POST['gold']) {
                    
                    $gewinn = e_rand(5,15);
                    $gold = round(((($session['user']['goldinbank']*$gewinn) /100)),0);
                    $session['user']['goldinbank']-=$_POST['gold'];
                    $session['user']['gold']+=$gold;
                    $givegold = "`4".$session['user']['gold']." Gold`t"; 
                }
                if ($allowgems=='1' && $_POST['gems']) {
                    
                    $gewinn = e_rand(5,15);
                    $gems = round(((($session['user']['gemsinbank']*$gewinn) /100)),0);
                    $session['user']['gemsinbank']-=$_POST['gems'];
                    $session['user']['gems']+=$gold;
                    $givegems = "`4".$session['user']['gems']." Edelsteine`t";
                }
    
                if ($givegold && $givegems) { $text = $givegold." `tund ".$givegems; }
                else { $text = $givegold.$givegems; }
    
                output("`tDer Kutscher bedankt sich für Dein Vertrauen und eilt zur Kutsche. Als er von der Bank zurückkommt und Dir Deinen Beutel aushändigt,
                    bemerkst Du beim nachzählen, daß der Bankier sich vertan haben muss und Dir zuviel ausgezahlt hat. Du hast jetzt ".$text." in Deiner Tasche.
                ");
                
            }
        }
    }
    
    addnav("Wege");
    addnav("zurück","$file");
    addnav("$ort","$back");        
}

page_footer();
?>