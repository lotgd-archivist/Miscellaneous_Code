
<?php

// 21072004

/* 
* Old Drawl 
* Figur erfunden von LordRaven 
* 
* Old Drawl ist geschaffen worden, um den Spielern in der Kneipe Specials zu ermöglichen, die Ihnen das 
* Spiel ein wenig erleichtern. Allerdings soll das Ansprechen von Old Drawl sowei das Benutzen seiner 
* Fähigkeiten auch ein Risko enthalten. Es kann sein das er den abgesprochenen Preis nicht einhält, 
* ausflippt und den Fragenden verletzt, so daß dieser einen Charmpunkt verliert etc. 
* Außerdem kann er schon mal das eine oder andere Spezial verwechseln und der Benutzer bekommt für den Preis 
* eventuell weniger oder aber auch ein besseres Special 
* Die Risikoidee ist in dieser Version 1.0 noch nicht enthalten. 
* 
* Version:    1.0 vom 24.04.2004 
* Version:    1.1 Debuglog hinzugefügt - 25.04.2004 LordRaven 
* Version:    1.2 Zufallsfunktion für böse Attacken eingefügt - 26.04.2004 LordRaven 
* Version:    1.3 Old Drawl das Erschlagen des Fragenden auf Zufallsbasis wegen Balancing eingebaut 
* Author:     LordRaven 
* Email:      logd@lordraven.de 
* 
* Leichtes Balancing, debuglog entschlackt (anp)
*/ 
require_once "common.php"; 
// addcommentary(); 
page_header("Old Drawls Tisch"); 

$config = unserialize($session['user']['donationconfig']); 

if ($_GET[op]=="speak"){ 
    $zufall = e_rand(1,8); 
        output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n"); 
        output("`@Du hast es gewagt und Old Drawl angesprochen. Langsam dreht der alte Mann seinen Kopf zu Dir herum 
                und schaut Dich durchdringend aus seinen alten Augen an. Dir kommt es so vor als wären sie gelb. 
                Als er zu sprechen beginnt wird Dir klar, woher sein Name kommt. Schleppend setzt er an:`n`n"); 
        if ($zufall!=7){ 
                output("\"`GMeein Sooohn, was stööörst Du meiiiineee Ruuuuuheeee? 
                        Saaag was Duuu voooon mirrrr willlst unnnd daaann laaass miiiich innn 
                        Ruuuuheeee. Fooollgeendee Aaktiooneennn kann iiich Diir anbiiieteeenn. Abeeerrrr giiiib acht - irrgeendwiiieee haaabbeee iiicchhhh maanchmaaal 
                        meeiinnee Kräääftteee niiicht meeeehr iimmeeerr uunterrr Kooontroolleee.`@\""); 
                addnav("Old Drawl Aktionen"); 
                addnav("3x Goldmine","olddrawl.php?op=do&action=goldmine"); 
                addnav("Lotterie spielen","lottery.php"); 
            }else{ 
                output("\"`GMeein Sooohn, was stööörst Du meiiiineee Ruuuuuheeee? 
                        Haabeenn Diir dieee Waarnungennn niiicht gerreicht? Muußteeest Duu uuunbeeeddinngt meeiiiinee Ruuheee 
                        stööörenn? Icchhh haabee voon solcheeen Abstauuuubernn wiiee Diiir diee Naseee volll!!`@\""); 
                output("`n`nOld Drawl macht eine Faust, holt aus und"); 
                switch(e_rand(1,5)){ 
                        case 1: 
                        output("trifft Dich mitten im Gesicht, so daß eine häßliche Narbe entstanden ist. Die Wucht 
                                schleudert Dich bis an den Tresen zurück."); 
                        output("`n`n`@Du hast `42 Charmpunkte`@ verloren."); 
                        $session[user][charm]-=2; 
                        //debuglog("`^Old Drawl `@haut 3 Charmpunkte weg");
            if ($session['user']['charm']<=0) $session['user']['charm']=0;
                           break; 
                        case 2: 
                        output("trifft Dich am Körper und die Wucht schleudert Dich bis an den Tresen zurück."); 
                        /*
            //Viiiiiiiiieeeeeeeel zu gefährlich! Bekomm das als Bauernjunge Level 1 5x und du bist dauertot!!
            // Naja, gibt ja ne Sperre (6 LP minimum in newday.php), aber das muss man ja nicht ausreizen
            output("`n`n`@Du hast `42 Lebenspunkte`@ verloren."); 
                        $session['user']['maxhitpoints']-=2; 
                        //debuglog("`^Old Drawl `@haut 2 Lebenspunkte weg");
            */
            output("`n`n`@Du hast fast alle deine Lebenspunkte verloren.");
            if ($session['user']['hitpoints']>1) $session['user']['hitpoints']=2;
                        break; 
                        case 3: 
                        output("greift Dir in die Tasche und klaut Dir Deinen Geldbeutel mit {$session['user']['gold']} Gold."); 
                        $session['user']['gold']=0; 
                        //debuglog("`^Old Drawl `@raubt {$session['user']['gold']} Gold."); 
                        break;     
                        case 4: 
                        output("trifft Dich so hart, daß Du tot umfällst.`nDu kannst morgen wieder spielen."); 
                        $session['user']['alive']=false; 
                        $session['user']['hitpoints']=0; 
                            debuglog("Hat {$session['user']['gold']} Gold und 3 Edelsteine bei Old Drawl verloren"); 
                        $session['user']['gold']=0; 
                        $session['user']['gems']-=2; 
                        if ($session['user']['gems']<0) $session[user][gems]=0; 
                                       addnav("Tägliche News","news.php"); 
                        addnews("`0".$session[user][name]." `0wurde von Old Drawl erschlagen als ".($session[user][sex]?"sie":"er")." ihn angesprochen hat."); 
                        break; 
                        case 5: 
                        output("haut voll daneben und fällt dabei unsanft auf den Boden. Er hatte wohl schon das eine oder andere Ale zuviel. \"Puh\", denkst Du, \"Glück gehabt...\""); 
                            //debuglog("`^Old Drawl `@haut daneben"); 
                           break; 
                }     
        }     
}else if ($_GET[op]=="do"){ 
        if ($_GET[action]=="goldmine"){ 
                output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n"); 
                output("`@Für die Aktion `^3 mal Goldmine im Wald `@verlangt Old Drawl `42 `@Edelsteine. 
                        Aber achte darauf, daß sie nach wie vor einstürzen kann und es keine Garantie für eine erfolgreiche 
                        Suche gibt. Außerdem verlierst Du nach wie vor jeweils einen Waldkampf`n`n"); 
                output("`@Willst Du ihm die 2 Edelsteine geben?"); 
                addnav("Zwei Edelsteine geben","olddrawl.php?op=do&action=goldmine2"); 
        addnav("Zurück zur Auswahl","olddrawl.php?op=speak"); 
                //debuglog("`^Old Drawl `@wegen Goldmine angesprochen"); 
        }else if ($_GET[action]=="goldmine2"){ 
                output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n"); 
                if ($session[user][gems] >= 2){ 
                        if ($session[user][gems] >= 2 && $config['goldmine']==0 && $config['goldmineday']==0){ 
                            $config['goldmine'] += 3; 
                            $config['goldmineday']=1; 
                            $session[user][gems] -= 2; 
                            output("`n`n`@Old Drawl gibt dir eine halb zerfallene Karte zur Goldmine. Du wirst sie wohl tatsächlich nur 3 mal verwenden können."); 
                            //debuglog("`^Old Drawl `@macht Zugang zur Goldmine auf"); 
                        }elseif ($config['goldmineday']==1){ 
                            output("`n`n`@Old Drawl ist heute zu müde um Dir helfen zu können - komm morgen wieder!"); 
                        }else{ 
                            output("`@Du hast noch {$config['goldmine']} freie Zugänge zur Goldmine zur Verfügung, komme wieder wenn diese verbraucht sind."); 
                        } 
                }else{ 
                        output("`n`n`@Du hast nicht genügend Edelsteine zur Verfügung."); 
                } 
        } 
}else{
        output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n"); 
        output("`@Du siehst, wie die Leute in der Kneipe immer wieder mißtrauisch auf einen Tisch in der Ecke 
                der Kneipe blicken und sich leise über einen alten Mann unterhalten. Im Lärm der Kneipe 
                verstehst Du immer nur Wortfetzen aus den Gesprächen, aber daraus geht für Dich hervor, daß 
                die Leute früher großen Nutzen durch diesem alten Mann hatten, dieser aber mittlerweile 
                wohl verrückt geworden ist und ihn die Leute deswegen lieber meiden, bevor ihnen schlimmes 
                passiert.`n`n"); 
        output("`@Die Neugier siegt in Dir und Du trittst vorsichtig an den Tisch, wo immer der alte Kauz, den alle Old Drawl nennen, 
                sitzt und schweigsam sein Ale trinkt. Du weißt nicht wieso, aber irgendwie scheint dieser alte 
                Mann ein Geheimnis zu verbergen und Dein Gefühl sagt Dir, daß es Dir irgendwie nütztlich sein kann 
                Old Drawl anzusprechen.`n`n"); 
        output("`@Du bist verunsichert, was Du tun sollst. Sprichst Du ihn an oder gehst Du lieber wieder 
                zurück an die Theke?"); 
        addnav("Old Drawl ansprechen","olddrawl.php?op=speak"); 
} 
if ($session['user']['alive']==true) addnav("Zurück an die Theke","inn.php"); 
$session['user']['donationconfig'] = serialize($config); 
page_footer(); 
?>

