
<?
/* 
* Old Drawl
* Figur erfunden von Raven
*
* Old Drawl ist geschaffen worden, um den Spielern in der Kneipe Specials zu ermöglichen, die Ihnen das 
* Spiel ein wenig erleichtern. Allerdings soll das Ansprechen von Old Drawl sowei das Benutzen seiner
* Fähigkeiten auch ein Risko enthalten. Es kann sein das er den abgesprochenen Preis nicht einhält, 
* ausflippt und den Fragenden verletzt, so daß dieser einen Charmpunkt verliert etc.
* Außerdem kann er schon mal das eine oder andere Spezial verwechseln und der Benutzer bekommt für den Preis 
* eventuell weniger oder aber auch ein besseres Special
*
* Version:    1.0 vom 24.04.2004
* Version:    1.1 Debuglog hinzugefügt - 25.04.2004 Raven
* Version:    1.2 Zufallsfunktion für böse Attacken eingefügt - 26.04.2004 Raven
* Version:    1.3 Old Drawl das Erschlagen des Fragenden auf Zufallsbasis wegen Balancing eingebaut
* Version:    1.4 Old Drawl nur noch einmal am Tag, nicht mehr mit Level 15 - 06.08.2004 Raven
* Author:     Raven @ Rabenthal
* Email:      logd@rabenthal.de 
* 
*/ 
require_once "common.php";
addcommentary();
page_header("Old Drawls Tisch");

$config = unserialize($session['user']['donationconfig']); 

if ($_GET[op]==""){
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
    addnav("Aktionen");
    addnav("Old Drawl ansprechen","olddrawl.php?op=speak");
}

if ($_GET[op]=="speak"){
    output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n");
    if ($session[user][seenolddrawl]==1){
        output("`0\"`6Wwaaassssss???? Duuuu schhooooonn wieeedeeeeer? `n
                          Ichh habeee sooo Schmaaaarotzeeerrr wiee Dich satttt. Ichh binnn doooch nichtttt diieee
                          Heeiillsssaarmeeeeee??`0\"`n`n
                          `6Old Drawl greift Dir in die Tasche, klaut Dir `^".$session[user][gold]."`6 Gold und
                           `^".$session[user][gems]." `6Edelsteine und macht sich aus dem Staub. 
                           `n`nFassungslos stehst Du in der Kneipe und kannst wegen einem Schock gar nicht reagieren.`n
                           \"Na, den spreche ich in Zukunft keine zweimal an\" denkst Du Dir.");
        debuglog("`^Old Drawl `@klaut ".$session[user][gold]." Gold und ".$session[user][gems]." Edelsteine wegen 2 mal");
        $session[user][gold]=0;
        $session[user][gems]=0;
    }else if ($session[user][level]>14){
        output("`0\"6Diirrrr kann ichhhh nichhtsss meeehhrrr guuuttesss tuunnn... Koonnzeenntrieeereee Dichh lieeeber
            auff dennn Diiirr bevooorrsthendennn Draachennnkamppffff`0\"`n");
    }else{
        $zufall = e_rand(1,10);
        output("`@Du hast es gewagt und Old Drawl angesprochen. Langsam dreht der alte Mann seinen Kopf zu Dir herum
            und schaut Dich durchdringend aus seinen alten Augen an. Dir kommt es so vor als wären sie gelb.
            Als er zu sprechen beginnt wird Dir klar, woher sein Name kommt. Schleppend setzt er an:`n`n");
        if ($zufall!=7){
            output("\"".($session[user][sex]?"Meeiiinnee Tochhter":"Meein Sooohn").", was stööörst Du meiiiineee Ruuuuuheeee? 
                Saaag was Duuu voooon mirrrr willlst unnnd daaann laaass miiiich innn 
                Ruuuuheeee. Fooollgeendee Aaktiooneennn kann iiich Diir anbiiieteeenn. Abeeerrrr giiiib acht - irrgeendwiiieee haaabbeee iiicchhhh maanchmaaal
                meeiinnee Kräääftteee niiicht meeeehr iimmeeerr uunterrr Kooontroolleee.\"");
            addnav("Old Drawl Aktionen");
            addnav("10 mal Goldmine","olddrawl.php?op=do&action=goldmine");
            addnav("Lotterie spielen","olddrawl.php?op=do&action=lottery");
            addnav("");
        }else{
            output("\"".($session[user][sex]?"Meeiiinnee Tochhter":"Meein Sooohn").", was stööörst Du meiiiineee Ruuuuuheeee? 
                Haabeenn Diir dieee Waarnungennn niiicht gerreicht? Muußteeest Duu uuunbeeeddinngt meeiiiinee Ruuheee
                stööörenn? Icchhh haabee voon solcheeen Abstauuuubernn wiiee Diiir diee Naseee volll!!\"");
            output("`n`nOld Drawl macht eine Faust, holt aus und");
            switch(e_rand(1,5)){
            case 1:
                output("trifft Dich mitten im Gesicht, so daß eine häßliche Narbe entstanden ist, die Wucht
                    schleudert Dich bis an den Tresen zurück.");
                output("`n`n`@Du hast `43 Charmpunkte`@ verloren.");
                $session[user][charm]-=3;
                debuglog("`^Old Drawl `@haut 3 Charmpunkte weg");
                break;
            case 2:
                output("trifft Dich am Körper und die Wucht
                    schleudert Dich bis an den Tresen zurück.");
                output("`n`n`@Du hast `42 Lebenspunkte`@ verloren.");
                $session['user']['maxhitpoints']-=2;
                debuglog("`^Old Drawl `@haut 2 Lebenspunkte weg");
                break;
            case 3:
                output("greift Dir in die Tasche und klaut Dir Deinen Geldbeutel mit 
                    {$session['user']['gold']} Gold.");
                $session['user']['gold']=0;
                debuglog("`^Old Drawl `@raubt {$session['user']['gold']} Gold.");
                break;    
            case 4:
                output("trifft Dich so hart, daß Du tot umfällst.`nDu kannst morgen wieder spielen.");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                    debuglog("Hat {$session['user']['gold']} Gold und 3 Edelsteine bei Old Drawl verloren");
                $session[user][gold]=0;
                $session[user][gems]-=3;
                if ($session['user']['gems']<0){
                    $session[user][gems]==0;
                }
                addnav("Daily News","news.php");
                addnews($session[user][name]." wurde von Old Drawl erschlagen als ".($session[user][sex]?"sie":"er")." ihn angesprochen hat.");
                page_footer();
                break;
            case 5:
                output("haut voll daneben und fällt dabei unsanft auf den Boden. Er hatte wohl
                    schon das eine oder andere Ale zuviel. \"Puh\", denkst Du, \"Glück gehabt...\"");
                    debuglog("`^Old Drawl `@haut daneben");
                break;
            }    
        }
    }    
}
if ($_GET[op]=="do"){
    if ($_GET[action]=="goldmine"){
        $session[user][seenolddrawl]=1;
        output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n");
        output("`@Für die Aktion `^10 mal Goldmine im Wald `@verlangt Old Drawl `42 `@Edelsteine. 
            Aber achte darauf, daß sie nach wie vor einstürzen kann und es keine Garantie für eine erfolgreiche
            Suche gibt. Außerdem verlierst Du nach wie vor jeweils einen Waldkampf`n`n");
        output("`@Willst Du ihm die 2 Edelsteine geben?");
        addnav("2 Edelsteine geben","olddrawl.php?op=do&action=goldmine2");
        addnav("");
        addnav("Zurück zur Auswahl","olddrawl.php?op=speak");
        addnav("");
        debuglog("`^Old Drawl `@wegen Goldmine angesprochen");
    }
    if ($_GET[action]=="goldmine2"){
        output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n");
        if ($session[user][gems] >= 2){
            if ($session[user][gems] >= 2 && $config['goldmine']==0 && $config['goldmineday']==0){
                $config['goldmine'] += 10;
                $config['goldmineday']=1;
                $session[user][gems] -= 2;
                output("`n`n`@Old Drawl sorgt dafür, daß Dir die Goldmine 10 mal zur Verfügung steht.");
                debuglog("`^Old Drawl `@macht Zugang zur Goldmine auf");
            }elseif ($config['goldmineday']==1){
                output("`n`n`@Old Drawl ist heute zu müde um Dir helfen zu können - komm morgen wieder!");
                debuglog("`^Old Drawl `@ist zu müde");
            }else{
            output("`@Du hast noch {$config['goldmine']} freie Zugänge zur Goldmine zur Verfügung, komme wieder wenn diese 
                verbraucht sind.");
            debuglog("`^Old Drawl `@sagt daß noch {$config['goldmine']} Zugänge vorhanden sind");
            }
        }else{
            output("`n`n`@Du hast nicht genügend Edelsteine zur Verfügung");
            debuglog("`^Old Drawl `@sagt, dass für die Goldmine nicht genügend Edelsteine vorhanden sind");
        }
    }
    if ($_GET[action]=="lottery"){
        $gold = $session[user][level]*5;
        output("`@`b`cOld Drawls Stammtisch`c`b`0`n`n");
        output("`@\"Mein junger Geselle, nach Glücksspiel steht Dir also der Sinn - dann zahle 
            `4$gold `@Gold für Dein Los und vielleicht wirst Du der
            Glückliche Gewinner sein\"");
        addnav("Los kaufen","lottery.php");
        addnav("");
        debuglog("`^Old Drawl `@wegen Lotterie angesprochen");
    }
}
if ($session['user']['alive']=true){
    addnav("Zurück an die Theke","inn.php");
}
$session['user']['donationconfig'] = serialize($config);
page_footer();
?>

