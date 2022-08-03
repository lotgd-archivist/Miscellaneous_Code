<?
/**
 * Die Höhle, ein Special für den Wald
 * by Basilius «Wasili» Sauter
 * Version 1.4
 * 
 * Required  function give_new_loot
 * -----------------------------------------------------------
    function give_new_loot($name,$description,$gold=0,$gems=0) {
        // Gives Users a New Loot
        // by Basilius «Wasili» Sauter
        global $session;
        $sql = "INSERT INTO items (name,description,class,owner,gold,gems) "
           ."VALUES ('$name','$description','Beute','".$session['user']['acctid']."','$gold','$gems')";
        db_query($sql) or die("Ach, so nen Mist, schon wieder verbockt!");
    }
  -----------------------------------------------------------
  *
  * History:
  *     1.4.1
  *         - Rechtschreibfehler behoben (Stala_g_tit => Stalaktit)
  *         - Balance-Fix:
  *             - Edelsteinmaximum (standard) verringert
  *             - Schwindende Wahrscheinlichkeit bei grossem Besitz (Thx@Salator)
  *         - Einstellung für Edelsteinmaximum hinzugefügt
  *         - Einstellung für Edelsteinmaximumanpassung hinzugefügt
  *     1.4
  *         - Einstellungen der Wahrscheinlichkeiten zugefügt
  *         - Code verschönert und aktuellem Codestil angepasst
  *         - Texte verbessert
  *         - Kleiner Bug mit specialinc gefixt
  *         - User kann nun, wenn er alles nimmt, überleben
  *         - User kann nun, wenn er nichts nimmt, auch sterben.
  *         - User kann nun auch graben und nichts finden
  *         - Funktion mit Existierprüfung nun direkt ins Special eingefügt.
**/

if (!isset($session)) exit();

// Wahrscheinlichkeiten (0 => Keine Chance, 100 => Volle Chance)
define('SPECIAL_HÖHLE_CHANCE_ENTER', 20); // Wahrscheinlichkeit, dass der Felsen eine Höhle ist
define('SPECIAL_HÖHLE_CHANCE_FOUND', 40); //         "         , dass etwas beim Graben gefunden wird
define('SPECIAL_HÖHLE_CHANCE_DGOLD', 25); //         "         , dass der User beim Goldnehmen stirbt
define('SPECIAL_HÖHLE_CHANCE_RINGG', 50); //         "         , dass der User beim Glasring stirbt
define('SPECIAL_HÖHLE_CHANCE_RINGE', 75); //         "         , dass der User beim Edelring stirbt
define('SPECIAL_HÖHLE_CHANCE_GIERF', 99); //         "         , dass der User beim Gierfall stirbt
define('SPECIAL_HÖHLE_CHANCE_NICHT',  5); //         "         , dass der User beim Flüchten stirbt

// Balance-Einstellungen
define('SPECIAL_HÖHLE_BALANCE_MAXGEMS', 3); // Edelsteinmaximum, das beim buddeln gefunden werden kann (Achtung: Extremwerte sind nur halb so häufig wie die anderen)
define('SPECIAL_HÖHLE_BALANCE_MAXGEMMALUS', 10); // Je ... Edelsteine in der Hand wird Maxgems um 1 Schritt verkleinert.

if(!function_exists('give_new_loot')) { // Wenn es die Funktion nicht gibt, dann soll er sie definieren. Kompabilität!
    function give_new_loot($name, $description, $gold=0, $gems=0) {
        // Gives users a new loot
        // by Basilius «Wasili» Sauter
        global $session;
        $sql = "INSERT INTO items (name,description,class,owner,gold,gems) "
           ."VALUES ('$name','$description','Beute','".$session['user']['acctid']."','$gold','$gems')";
        db_query($sql);
    }
}

switch ($_GET['op']) {
    default: {
        // Der Anfang: User kommt zu Felsen. Entweder findet der User eine Höhle, oder nicht - im letzteren Fall
        // findet der User möglicherweise einen (zufälligen) Schatz.
        output('<p>`3Während du durch den Wald wanderst und an einer Anhöhe vorbei gehst, 
            fällt dir tatsächlich eine ungewöhnliche Einbuchtung an der Felswand auf. Sofort
            packst du deine Waffe und beginnst, das Loch zu bearbeiten. Warum du das genau tust, weisst du nicht -
            aber du folgst deiner Intuition, und die enttäuscht dich selten.`0</p>', true);
        
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_ENTER) {    
            // Der User hat die Höhle gefunden. Bei mehr als einer übrigen Runde kann der User nun eintreten - 
            // ansonsten hat er auch noch die letze Runde verbraucht.
            if ($session['user']['turns']>1) {
                output('<p>`3Du schaufelst, buddelst, kratzt. Erschöpft betrachtest du dein Werk. Tatsächlich 
                    hast du es geschafft, ein Loch freizulegen, das vielleicht zu einer Höhle führt. Was
                    wirst du tun? Auf jeden Fall hast du alleine für diese Arbeit zwei Waldkämpfe verbraucht.`0</p>', true);
                
                addnav("Die Höhle betreten","forest.php?op=betritt");
                addnav("Den Ort verlassen","forest.php?op=goforest");
                
                $session['user']['turns']-=2;
                $session['user']['specialinc'] = "hoehle.php";
            }
            else {
                output('<p>`3Du schaufelst, buddelst, kratzt. Erschöpft gibst du auf. Die Einbuchtung ist nur 
                    wenig grösser, deine Kräfte aber völlig verbraucht. Du weisst, dass du heute keine Monster
                    mehr erschlagen wirst.`0</p>', true);
                
                $session['user']['turns'] = 0;
                $session['user']['specialinc'] = "";
            }
            
        } 
        else {
            // Der User hat keine Höhle gefunden. Vielleicht aber findet er einen Schatz?
            $chance = mt_Rand(1, 1000)%100;
            if($change < SPECIAL_HÖHLE_CHANCE_FOUND) {
                // Der User hat zwar keine Höhle, wohl aber einen Schatz gefunden. Glückwunsch.
                $gemsbalance = SPECIAL_HÖHLE_BALANCE_MAXGEMS - $session['user']['gems'] / SPECIAL_HÖHLE_BALANCE_MAXGEMMALUS;
                $maxgems = ceil(max(0, $gemsbalance)); // Thx@Salator.
                $gems = e_rand(0, $maxgems);
                $gold = mt_rand($session['user']['level']*50,$session['user']['level']*100);
            
                output(sprintf('<p>`3Du schaufelst, buddelst, kratzt. Erschöpft betrachtest du dein Werk. Du findest, versteckt 
                    zwischen den Felsen, `^%s`3 Goldstücke und %s`3. Du bist Stolz auf den kleinen Schatz, den du 
                    gefunden hast.`0</p>', $gold, ($gems==1?"`%1 Edelstein":"`%$gems Edelsteine")), true);
                    
                if ($session['user']['turns']!=0) $session['user']['turns']--;
                $session['user']['gold']+=$gold;
                $session['user']['gems']+=$gems;
                $session['user']['specialinc'] = "";
            }
            else {
                // Der User hat Pech - er bekommt garnix.
                output('<p>`3Du schaufelst, buddelst, kratzt. Erschöpft gibst du auf. Die Einbuchtung ist nur 
                    wenig grösser, gefunden hast du rein gar nichts. Toll. In dieser Zeit hättest du ein
                    Monster schlagen können und du hättest mehr verdient.`0</p>', true);
                    
                if ($session['user']['turns']!=0) $session['user']['turns']--;
                $session['user']['specialinc'] = "";
            }            
        }
    
        break;
    }
    
    case "goforest": {
        // Der User betritt die Höhle nicht. Nehmen wir doch einfach an, dass er ein Angsthase ist... *fg*
        output('<p>`3Obwohl du dich beim Buddeln verausgabt hast, weigerst du dich, die Höhle zu betreten. Leider hat
            diese unrühmliche Aktion jemand gesehen - baldschon wird es jeder Wissen, dass du ein <b>FEIGLING</b> bist.
            Damit musst du nun leben.`0</p>', true);
        
        addnews("`3".$session['user']['name']." `3hatte Angst davor, eine kleine Höhle zu betreten.");
        $session['user']['specialinc'] = "";
        break;
    }
    
    case "betritt": {
        // Der User betritt die Höhle. Kurze Beschreibung der Momentanten Situation und eine "Was-willst-du"-Auswahl
        output('<p>`3Vorsichtig quetscht du deinen Körper durch die Öffnung und findest dich, nach langem robben durch
            die Finsternis, in einer Höhle wieder, beleuchtet vom Licht irgendwelcher Leuchtsteine. Riesige, uralte 
            Stalaktiten hängen von der Decke und wachsen gegen die Stalagmiten, die vom Boden her aufsteigen.
            Manche dieser Kalkanlagerungen sehen äusserst spitz aus - ist das dort nicht ein Blutfleck auf dem Boden?</p>
            
            <p>`3Die Mitte der Höhle wird von Stalagnaten geziert, verwachsene Stalaktiten und -miten. Sie sind von 
            stattlicher Natur und kreisförmig angeordnet. In der Mitte des Kreises befindet sich ein Altar, auf dem verschiedene
            Gegenstände liegen. Jeder dieser Gegenstände liegt in einem Schälchen, das farblich zum Altar passt.`0</p>
            
            <p>`3Im mittleren Schälchen liegt ein Häufchen Gold. Das linke Schälchen beinhaltet einen Ring, aus Glas
            gefertigst und im rechten Schälchen liegt ein zweiter Ring, Edel verziert mit den verschiedensten Diamanten
            und Edelsteinen. Ein wertvolles Stück.`0</p>
            
            <p>`3Was wirst du nun tun?`0</p>', true);
            
        # Auswahl-Navigation
        addnav('Nimm...');
        addnav('... das Gold', 'forest.php?op=nimmgold');
        addnav('... den Glasring', 'forest.php?op=nimmglas');
        addnav('... den Edelsteinring', 'forest.php?op=nimmring');
        addnav('... ALLES', 'forest.php?op=nimmalles');
        
        addnav('Abbrechen');
        addnav('Verlasse die Höhle', 'forest.php?op=nimmnichts');
        
        $session['user']['specialinc'] = "hoehle.php";
        break;
    }
    
    case "nimmgold": {
        // Der User nimmt nur das Gold. Soll in der Regel die beste Wahl sein, abgesehen vom Verzicht.
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_DGOLD) {
            output('<p>`3Du nimmst das Gold aus der Schale. Dabei hörst du ein kleiner Klick und im selben Augenblick weisst du
                - dass war eine Falle. Ein Stalaktit löst sich von der Decke, als du zu verschwinden versuchst, und erschlägt dich.`0</p>
                
                <p>`$Du bist TOT!`0</p>
                <p>`#Du verlierst all dein Gold und 10% Deiner Erfahrung.`0</p>', true);
                
            addnav('Tägliche News', 'news.php');
                
            $session['user']['gold'] = 0;
            $session['user']['experience'] *= 0.9;
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            
            addnews("`3".$session['user']['name']." `3wurde in einer Höhle zu gierig. Ein Stalaktit setzte seinem Leben ein Ende.");
        }
        else {
            $gold = mt_rand($session['user']['level']*50,$session['user']['level']*100);
            
            output('<p>`3Du nimmst das Gold, packst es in deine Tasche und verschwindest so schnell du kannst aus der Höhle. Krachend 
                hörst du hinter dir ein Stalaktit - aber du warst schneller. Stolz zählst du draussen deine Goldstücke. Du hast dein 
                Leben für `^'.$gold.' Goldstücke`3 riskiert.`0</p>', true);
                
            addnews("`3".$session['user']['name']." `3betrat eine Höhle und wurde reich.");
            
            $session['user']['gold']+=$gold;
        }

        $session['user']['specialinc'] = "";
        break;
    }
        
    case "nimmglas": {
        // Der User nimmt nur den Glasring. Die mittlere Wahl, gefährlicher als das Gold.
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_RINGG) {
            output('<p>`3Du nimmst den gläsernen Ring aus der Schale. Dabei hörst du ein kleiner Klick und im selben Augenblick weisst du
                - dass war eine Falle. Ein Stalaktit löst sich von der Decke, als du zu verschwinden versuchst, und erschlägt dich.`0</p>
                
                <p>`$Du bist TOT!`0</p>
                <p>`#Du verlierst all dein Gold und 10% Deiner Erfahrung.`0</p>', true);
                
            addnav('Tägliche News', 'news.php');
                
            $session['user']['gold'] = 0;
            $session['user']['experience'] *= 0.9;
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            
            addnews("`3".$session['user']['name']." `3wurde in einer Höhle zu gierig. Ein Stalaktit setzte seinem Leben ein Ende.");
        }
        else {    
            output('<p>`3Du nimmst den Ring aus dem Schälchen, packst es in deine Tasche und verschwindest so schnell du kannst aus der Höhle. 
                Krachend hörst du hinter dir ein Stalaktit - aber du warst schneller. Du hast die Falle und die Höhle überlebt.`0</p>', true);
                
            give_new_loot("Glasring", "Ein Ring, welcher aus Vulkanglas gefertigst ist. Rötlich schimmert er und hat sicher etwas Wert", 2000, 0);
                
            addnews("`3".$session['user']['name']." `3betrat eine Höhle und hat einen Ring mitgenommen.");
        }

        $session['user']['specialinc'] = "";
        break;
    }
        
    case "nimmring": {
        // Der als zweitgefährlichsten Gegenstand gedachten Edelsteinring ist einiges Wert - und man kann gut dazu sterben.
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_RINGE) {
            output('<p>`3Du nimmst den funkelnden, edlen Ring aus der Schale. Dabei hörst du ein kleiner Klick und im selben Augenblick weisst du
                - dass war eine Falle. Ein Stalaktit löst sich von der Decke, als du zu verschwinden versuchst, und erschlägt dich.`0</p>
                
                <p>`$Du bist TOT!`0</p>
                <p>`#Du verlierst all dein Gold und 10% Deiner Erfahrung.`0</p>', true);
                
            addnav('Tägliche News', 'news.php');
                
            $session['user']['gold'] = 0;
            $session['user']['experience'] *= 0.9;
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            
            addnews("`3".$session['user']['name']." `3wurde in einer Höhle zu gierig. Ein Stalaktit setzte seinem Leben ein Ende.");
        }
        else {    
            output('<p>`3Du nimmst den  funkelnden, edlen Ring aus dem Schälchen, packst es in deine Tasche und verschwindest so schnell 
                du kannst aus der Höhle. Krachend hörst du hinter dir ein Stalaktit - aber du warst schneller. Du hast die Falle und 
                die Höhle überlebt.`0</p>', true);
                
            give_new_loot("Edelring", "Ein Ring, in der die Buchstabenkombination B&S eingezeichnet ist. Viele Edelsteine
                verzieren ihn. Ein Ring mit grossem Wert.", 1000, 3);
                
            addnews("`3".$session['user']['name']." `3betrat eine Höhle und hat einen Ring mitgenommen.");
        }

        $session['user']['specialinc'] = "";
        break;
    }
        
    case "nimmalles": {
        // Der User ist Gierig. Gier muss bestraft werden. Diese Aktion ist hochriskant - und weil der User so gierig ist,
        // verliert er auch gleich 20% seiner Erfahrung. Strafe muss sein, oder? ;)
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_GIERF) {
            output('<p>`3Du öffnest deine Tasche und packst alle Gegenstände ein. Leider löst du damit nicht nur eine Falle aus, sondern
                gleich mehrere - es ist dir unmöglich, zu entkommen. Du wirst von einem herabfallenden Stalagiten erschlagen. Schade, aber
                Gier muss nun einmal bestraft werden.`0</p>
                
                <p>`$Du bist TOT!`0</p>
                <p>`#Du verlierst all dein Gold und 20% Deiner Erfahrung.`0</p>', true);
                
            addnav('Tägliche News', 'news.php');
                
            $session['user']['gold'] = 0;
            $session['user']['experience'] *= 0.8;
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            
            addnews("`3".$session['user']['name']." `3wurde in einer Höhle zu gierig. Ein Stalaktit setzte dieser Gier ein Ende.");
        }
        else {
            $gold = mt_rand($session['user']['level']*50,$session['user']['level']*100);
            
            output('<p>`3Du hast Glück. Verdammtes Glück. Du packst alle Gegenstände in deine Tasche und verschwindest 
                so schnell du kannst aus der Höhle. Geschickt weichst du jeder Falle aus, die in der Höhle aufgestellt 
                wurde. Du hast es überlebt. Und bist um viel reicher. Du bist nun Besitzer zweier Ringe und bist um
                `^'.$gold.' Goldstücke`3 reicher. Glückwunsch.`0</p>', true);
                
            give_new_loot("Edelring", "Ein Ring, in der die Buchstabenkombination B&S eingezeichnet ist. Viele Edelsteine
                verzieren ihn. Er ist von grossem Wert.", 1000, 3);
            give_new_loot("Glasring", "Ein Ring, welcher aus Vulkanglas gefertigst ist. Rötlich schimmert er und hat sicher 
                etwas Wert.", 2000, 0);
                
            $session['user']['gold']+=$gold;
                
            addnews("`3".$session['user']['name']." `3betrat eine Höhle, riss alles an sich - und hat überlebt. Glückspilz!");
        }

        $session['user']['specialinc'] = "";
        break;
    }
        
    case "nimmnichts": {
        // Dem User ist das nicht so ganz geheuer und verzichtet ganz. Er hat nun eine kleine Chance, dass er einfach Pech 
        // hat - sorry, aber so fies bin ich nunmal. Wenn er stirbt, verliert er aber nur 5% der Erfahrung. Immerhin. Und er
        // gewinnt etwas an Erfahrung, wenn er nicht stirbt.
        $chance = mt_Rand(1, 1000)%100;
        if($chance < SPECIAL_HÖHLE_CHANCE_NICHT) {
            output('<p>`3Dir ist das ganze nicht geheuer. Vorsichtig verlässt du die Höhle - und übersiehst, dass der Boden
                nass ist. Du ruschtst aus, fällst - und wirst von einem Stalagmit aufgespiesst. Deine Leiche wird nun für immer
                dort liegen bleiben - als Warnung für jeden weiteren, unvorsichtigen Höhlenforscher.`0</p>
                
                <p>`$Du bist TOT!`0</p>
                <p>`#Du verlierst all dein Gold und 5% Deiner Erfahrung.`0</p>', true);
                
            addnav('Tägliche News', 'news.php');
                
            $session['user']['gold'] = 0;
            $session['user']['experience'] *= 0.95;
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            
            addnews("`3".$session['user']['name']." `3starb in einer Höhle als Folge einer Verkettung unglücklicher Unfälle.");
        }
        else {            
            $exp = e_rand(round($session['user']['experience']*0.05),round($session['user']['experience']*0.1));
            
            output('<p>`3Dir ist das ganze nicht geheuer. Du verlässt die Höhle wieder - und gerade, als du sie endgültig mit 
                deinem Körper verlassen möchtest, hörst du weit hinten ein Krachen. Hast du Glück. Du wärst wohl gestorben, 
                hättest du etwas vom Altar genommen. Diese Erkenntnis macht dich um '.$exp.' Erfahrung reicher.`0</p>', true);
                
            $session['user']['experience']+=$exp;
        }

        $session['user']['specialinc'] = "";
        break;
    }
}
?>