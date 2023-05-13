
<?php 
/* 
Statue 
by Vaan 
12//4//2004 
Erstmal erschienen auf http://cop-logd.de/logd
*/ 

require_once"common.php"; 
if (!isset($session)) exit(); 
page_header("Seltsame Statue"); 
if ($_GET['op']==""){ 
        $session['user']['specialinc']="statue.php"; 
        output("Du gehst so deinen Weg entlang und kommst an einer riesigen Statue vorbei, an der ein großes Schild angelehnt ist. Du versuchst zu entziffern, was auf dem alten Schild steht.");
        output("Du liest: \"In mir ist etwas verborgen, in mir ist was versteckt, in mir ist etwas Gutes oder etwas Böses! Wenn du herausfinden willst was es ist, guck in mich hinein.\"");
        output("Was willst du machen?"); 
        addnav("In die Statue kriechen und nach irgend einem Gegenstand suchen","berge.php?op=such"); 
        addnav("Einfach weiter gehen","berge.php?op=gehe"); 
} 
else if ($_GET['op']=="such"){ 
        output("Du fängst an dem Einstieg der Statue an, zu suchen. Nach einiger Zeit findest du ein Loch. Du steckst deinen Arm durch das Loch und bekommst etwas zu fassen.");
        switch(e_rand(1,17)){ 
                case 1:
                case 2: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert fast eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
                        //$session['user']['turns']-=1; 
                        $session['user']['attack']++; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
 
                case 3: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Da du so lange gebraucht hast, verlierst du für heute einen Waldkampf");
                        output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
                        $session['user']['turns']-=1; 
                        $session['user']['attack']+=3; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 

                case 4: 
                case 5: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert fast eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
                        $session['user']['defence']++; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
        
                case 6: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Da du so lange gebraucht hast, verlierst du für heute einen Waldkampf");
                        output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
                        $session['user']['turns']-=1; 
                        $session['user']['defence']+=3; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
                case 7: 
                        output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an.");
                        output("Ein stechender Schmerz, der von der Hand kommt, in der der kleine Gegenstand liegt, lässt dich zusammen sacken. Als du wieder aufwachst, fühlst du dich geschwächt.");
                        $angriff = ($session['user']['attack']-$session['user']['weapondmg']);
                        if($angriff>$session['user']['level']) $session['user']['attack']--; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
                case 8: 
                        output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an."); 
                        output("Ein stechender Schmerz, der von der Hand kommt, in dem der kleine Gegenstand liegt, lässt dich zusammen sacken. Als du wieder aufwachst, fühlst du dich geschwächt.");
                        $def = ($session['user']['defence']-$session['user']['armordef']);
                        if($def>$session['user']['level']) $session['user']['defence']--;  
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
                case 9: 
                case 10: 
                        output("Als du dir das kleine Ding in deiner Hand anschaust, bekommst du aus irgendeinem Grund Glücksgefühle und willst kämpfen.");
                        output("Du erhältst einen zusätzlichen Waldkampf.");
                        $session['user']['turns']+=1; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
                case 11: 
                case 12: 
                        output("Du ziehst und ziehst und ziehst, aber das kleine Ding in der Statue will einfach nicht raus kommen.");
                        output("Du verlierst einen Waldkampf."); 
                        output("Wütend gehst du Zurück in die Berge."); 
                        $session['user']['turns']-=1; 
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break; 
                case 13: 
                case 14: 
                        output("Grade, als du den Gegenstand aus der Statue rausziehen willst, spürst du, dass du von etwas gebissen worden bist.");
                        output("Du bist am Gift einer giftigen Schlange gestorben. Du verlierst 5% deiner Erfahrung.");
                        $session['user']['alive']=false; 
                        $session['user']['hitpoints']=0; 
                        $session['user']['experience']*=0.95; 
                        addnav("Tägliche News","news.php"); 
                        $session['user']['specialinc']=""; 
                        addnews($session['user']['name']." `0starb durch eine Giftschlange"); 
                        break;

                case 15: 
                case 16: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Da du so lange gebraucht hast, verlierst du für heute einen Waldkampf");
                        output("Doch nun fühlst du, wie eine nie zuvor erlebte Kraft deinen Leib durchfährt.");
                        $buff = array("name"=>"`qInnere Kraft`0","rounds"=>50,"wearoff"=>"`qDeine innere Kraft verschwindet spurlos.`0","atkmod"=>1.25,"defmod"=>1.25,"roundmsg"=>"`qDeine innere Kraft stärkt Agriff und Verteidigung.`0","activate"=>"roundstart");
                        $session['bufflist']['statuegut']=$buff;
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break;                 
                case 17: 
                        output("Es scheint so, als ob der Gegenstand festgebunden wäre. Es dauert eine Ewigkeit, bis du den Gegenstand hinaus bekommen hast.");
                        output("Da du so lange gebraucht hast, verlierst du für heute einen Waldkampf");
                        output("Du schaust auf den Gegenstand, und grenzenlose Furcht lässt deine Knie weich werden.");
                        $buff = array("name"=>"`qWeiche Knie`0","rounds"=>25,"wearoff"=>"`qDein Mut kehrt zurück.`0","atkmod"=>0.9,"defmod"=>0.9,"roundmsg"=>"`qDeine Knie sind vor Angst weich.`0","activate"=>"roundstart");
                        $session['bufflist']['statueschlecht']=$buff;
                        $session['user']['specialinc']=""; 
                        addnav("Zurück in die Berge","berge.php"); 
                        break;                 

        } 
} else if ($_GET['op']=="gehe"){ 
        output("Mit schnellen Schritten verlässt du den Ort."); 
        addnav("Schnell Zurück in die Berge","berge.php"); 
} 
?>

