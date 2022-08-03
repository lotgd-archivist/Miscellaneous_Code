<?php
/*Narjanas Tempel*/

require_once "common.php";
addcommentary();
checkday();

switch ($_GET['op'])
{
    case "quell":
    if ($_GET[op]=="quell")
    {
        page_header("Quell des Wahnsinns");
        output("`c`b`TQuell des Wahnsinns`c`b`nAus der Nähe erkennst du, dass der Quell von drei freischwebenden Objekten gespeist wird. Aus einem Kristallkelch, einer Gießkanne mit Blumenmotiv, und dem Schädel eines nicht besonders freundlichen, gehörnten Wesens laufen stetig verschiedenfarbige Flüssigkeiten in das darunter liegende Becken.
Auch das Plätschern des Quells klingt mal nach Wasser, mal nach Vogelzwitschern, mal nach Blätterrauschen, mal nach rieselndem Sand, aber bleibt nie lange gleich. Wer einen näheren Blick auf die Flüssigkeit wirft, auf den könnte das ungeahnte Wirkung haben...`n`n");
        
        viewcommentary("quell","sagt:",25);

            addnav("Museum","narjan.php?op=muse");
            addnav("zurück","narjan.php");
        break;
    }
    case "muse":
    if ($_GET[op]=="muse")    {
        page_header("Museum der Kunst des Wahnsinns");
        output("`cMuseum der Kunst des Wahnsinns`c`n`n
An guten - oder schlechten Tagen, je nachdem wie man es halt benennen will kann man hier die Göttin selbst treffen wie sie neue Kunstwerke erschafft. Es sind aber auch allerlei Seltsamheiten von ihren sterblichen Lieblingen vorhanden. Von Gemälden aus Blut, auf zarter Haut gemalt über grotesk aussehende Rindenstücke bis hin zu etwas das Blumengestecken ähnelt - allerdings aus Fingerknöchelchen, den Schädeln von kleinen Tieren und - hellgrün-gelben Lilien besteht. Es gibt hier nichts was es nichts gibt - und man muss sein Kunstverständniss schon sehr erweitern um wirklich damit umgehen zu können was einen für Kuriositäten anstarren. In der Ecke steht ein ausgestopfter Caramelldrache an dem schon ein paar Zacken fehlen. Da hatte wohl jemand Hunger. Willst du auch?
        ");
        
        viewcommentary("muse","sagt:",25);

               addnav("zurück","narjan.php");

        break;
    }
    case "nanz":
        if ($_GET[op]=="nanz")    {
            page_header("Narjanas Zimmer");
            output("`c<img src='images/haustier.png' alt='' >`c`n",true);
            output("`c`bNarjanas Zimmer`c`b`n
            Sobald du das zimmer betrittst erklingt ein lautes BRAAAAAAP! und ein rosagelb geflügeltes Etwas mit blauem Körper und grün-braunen Pfoten kommt zu dir hingerannt. Du wirst am gesamten Körper beschnüffelt - besonders wichtig scheint der Schritt zu sein, bevor es zweimal nießt, dich umstößt und zurückrennt - auf ein recht schmales Bett an der Seite hoppsend. Während du verwirrt Narjanas aufdringlichstes Haustier beobachtest, bemerkst du Stück für Sütck auch den Rest in diesem Raum. Das Bett ist mit Regenbogenfarbener Bettwäsche bezogen. Darüber hängt ein unheimlich echt wirkendes Bild von einer Fliegenden Insel mit Buntem Boden, einem Pavillion und vielen vielen Regenbögen. Wenn du ganz genau hinguckst kannst du sogar sehen dass sich das Bild bewegt - und viel eher eine Art Fenster zu sein scheint. Links von dir ist eine Tür - zumindest sieht sie auf den ersten Blick so aus. Wenn du näher trittst merkst du dass es eine Wand aus Wasser ist - die seltsamerweise nicht auf dich niederstürzt. Wenn du unvorsichtig bist wird spätestens jetzt Cally, eine der blutsaugenden Pflanzen Narjanas versuchen dich als Zwischensnack zu verspeisen. Überall hängen verschiedenfarbige Stoffe, es steht etwas was man mit viel viel Mühe als Schreibtisch identifizieren kann mit unheimlich viel Krimskrams darauf. Eines hat das Zimmer auf jedenfall nicht: Ordnung. Auf dem Boden liegt der halbe Kleiderschrank und das komplette Spielzimmer der kleinen Lieblinge von Narjana. Dazu kommen noch diverse Malsachen und ein zerbrochener Ceramiktopf. Wohin willst du dich wenden? Oder ist dir das Chaos doch zuviel?
`n");
        
        viewcommentary("nanz","sagt:",25);
             addnav("zurück","narjan.php?op=gang");

        break;
        }
    case "gang":
        if($_GET [op]=="gang"){
        page_header("Korridor");
        output("`cKorridor`n`n
                    Die Luft im engen Korridor ist warm, feucht und trüb. Wände und Boden ein fleischiges Rosa. Du versucht nicht darauf zu achten, wie der Boden unter deinen
                    Füßen nachgibt oder darauf, dass die weichen Seitenwände rythmisch zu pulsieren scheinen, während sie einen schleimigen Glanz abgeben.`n`c");
            addnav("renne zurück","narjan.php");
            addnav("Narjanas Zimmer","narjan.php?op=nanz");
        break;
        }
            

case "lurn":
if ($_GET[op]=="lurn")    {
        page_header("Lurnfälle");
        output("`c`bLurnfälle`c`b`n
Du kommst zu einem Tiefen einschnitt in den Bergen. Der Weg scheint sich hier zu verlaufen.`n
Ein Wasserfall stürzt mit brachialer Gewalt den Berg hinunter, speißt einen recht großen See. Das Daraus entstehende Flüsschen scheint viel zu klein
zu sein, aber vielleicht ist es nicht der einzige Abfluss. Auch schillert das Wasser als es an die Kante des Plateaus kommt in seltsamen Farben und scheint
für Wasser verhältnissmäßig fest zu sein. Wenn du dem Strahl mit dem Blick folgst siehst du ihn im nebel verschwinden, aber nicht aufsprühen.`n
Direkt am Ufer des Strandes glitzern Bunte Kiesel auf und scheinen sich zu einem Regelrechten Pfad auszuweiten");
        
        viewcommentary("nan","sagt:",25);
        
        addnav("zum Wasserfall","narjan.php?op=fall");
        addnav("buntem Kieselpfad folgen","victempel.php");
        addnav("zurück in die Berge","berge.php");
        addnav("Wasserfall runterhopsen","moor_unten.php?op=treppe");

        break;
    }
case "fall":
if ($_GET[op]=="fall")
    {
        page_header("Lurnfälle");
        output("`c`bLurnfälle`c`b`n
Todesmutig springst du in den See, der von dem Ständig rauschenden Wasserfall gefüllt wird. Die Strömung ist reißend und du weißt, dass du so unmöglich kämpfen kannst.
Mit einem male Reißt das Wasser auf und ein Riesenhaftes, steinernes Chamäleon entsteigt dem Wasser. Eine lange, dunkle Lavazunge züngelt aus dem Rachen und du glaubst wirklich
gesprochene Laute zu vernehmen. Nur wenige Sekunden später merkst du, es ist keine Einbildung. Das Wesen fragt dich etwas.  Du solltest besser das richtige Antworten oder aber dich auf einen äußerst schmerzvollen, aber immerhin schnellen Tod vorbereiten. `n`b`^Etwas über`0`b`n");
        switch (e_rand(1,6))
        {
        case 1:
            output("\"Die Farben des Regenbogens\"");
            addnav("\"Blau?\"","narjan.php?op=falsch");
            addnav("\"GELB!\"","narjan.php?op=falsch");
            addnav("\"Ich weiß nicht...\"","narjan.php?op=falsch");
            addnav("\"Kunterdiebunt!\"","narjan.php?op=richtig");
            addnav("\"Grün! Jawollja\"","narjan.php?op=falsch");
            addnav("still und heimlich verdrücken","berge.php");
            break;
        case 2:
            output("Das Tier der Veränderung");
            addnav("Ein Pferd","narjan.php?op=falsch");
            addnav("ein Chamäleon - schön bund","narjan.php?op=richtig");
            addnav("ein Fabeltier wie - ein Greif","narjan.php?op=falsch");
            addnav("ganz klar ne Chimäre","narjan.php?op=falsch");
            addnav("still und heimlich verdrücken","berge.php");
            break;
        case 3:
            output("Die drei Mächte der Göttin");
            addnav("öhm - keine Ahnung...","narjan.php?op=falsch");
            addnav("Kuchen essen, Feuer machen und Essen kochen","narjan.php?op=falsch");
            addnav("Wind, Wasser und Blitz","narjan.php?op=falsch");
            addnav("Chaos, Veränderung und Wahnsinn","narjan.php?op=richtig");
            addnav("still und heimlich verdrücken","berge.php");
            break;
        case 4:
            output("Name der Manie");
            addnav("BACCHUS!","narjan.php?op=falsch");
            addnav("Earendil?","narjan.php?op=falsch");
            addnav("Sheogorath - kennt doch jeder","narjan.php?op=falsch");
            addnav("SunSun","narjan.php?op=richtig");
            addnav("Sanguin","narjan.php?op=falsch");
            addnav("still und heimlich verkrümmeln","berge.php");
            break;
        case 5:
            output("Name der Demenz");
            addnav("Darky - ganz klar","narjan.php?op=richtig");
            addnav("Hades hat es!","narjan.php?op=falsch");
            addnav("Rui","narjan.php?op=falsch");
            addnav("Morpheus...","narjan.php?op=falsch");
            addnav("still und heimlich verkrümeln","berge.php");
            break;
        case 6:
            output("Die Kunst des Kristallgartens");
            addnav("Ähm - eine moderne Kunstaustellung?","narjan.php?op=falsch");
            addnav("Kunst interessiert mich nicht","narjan.php?op=falsch");
            addnav("Die Kunst der Toten","narjan.php?op=richtig");
            addnav("Shirigami - oder so.","narjan.php?op=falsch");
            addnav("still und heimlich verdrücken","berge.php");
            break;

        }
        
       
        
        break;
    }
case "falsch":
if ($_GET[op]=="falsch")    {
        page_header("Lurnfälle");
        output("Das war eindeutig die FALSCHE antwort. Das Riesenhafte Wesen streckt sich, und verschlingt dich in einem habs. DU bist tot.");
        
        addnews("`3".$session['user']['name']." `3konnte die Frage der Sphinx - äh - des Chamäleons nicht beantworten und wurde gefressen`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        addnav("Tägliche News","news.php");
        break;
    }
case "richtig":
if ($_GET[op]=="richtig")    {
        page_header("Lurnfälle");
        output("Scheinbar war dies die richtige Antwort. Auf jedenfall grinst das Chamäleon dich schelmisch an und  trollt sich zur Seite, wo es sich wohlig
in der Sonne brutzelt. Dir steht der Weg zum Wasserfall jetzt frei, aber noch lange rätzelst du, ob Chamäleons wirklich grinsen können.");
        $session['user']['experience']*1.3;
        addnav("unter dem Wasserfall durch","narjan.php");
        addnav("zurück in die Berge","berge.php");
        break;
    }
case "laby":
if ($_GET[op]=="laby")    {
        page_header("Labyrinth");
        output("`c`bLabyrinth`c`b`nBeschreibung`n");
        
        viewcommentary("laby","sagt:",25);
        
                addnav("zur magischen Wendeltreppe","moor_unten.php");
                addnav("in den Sumpf","moor.php");
                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
                addnav("In die Felder (Logout)","login.php?op=logout",true);
        break;
    }
    default:
if ($_GET[op]=="")    {
        
        page_header("Tempel des Chaos");
        
        output("`c`b`STempel des Chaos`b`c`nDu betrittst den Hauptraum - man könnte ihn als kreisförmig bezeichnen, aber wenn du dir die Wände näher betrachtest siehst du dass sie aus verzerrten, zersplitterten Spiegeln bestehen aus denen du dir so oft und so grotesk selbst entgegenblickst dass du die Umrisse des Raums lieber weiter ignorierst. 
Zentral befindet sich ein stetig überlaufendes Becken mit Flüssigkeit in allen Farben: der Wahnsinnsquell. Nur die durchsichtigen Platten die dicht and dicht auf der Flüssigkeit zu schwimmen scheinen wie Eisschollen schützen dich vor dem Kontakt damit, und wer weißt wie sicher das ist? 
In unregelmäßigen Abständen kannst du weitere Türen und Gänge sehen die in andere Teile des Tempels führen.`n`n");
        
        
        viewcommentary("narjana","sagt",25);
        
        addnav("Quelle des Wahnsinns","narjan.php?op=quell");
        addnav("Korridor","narjan.php?op=gang");
        addnav("Museum","narjan.php?op=muse");
        addnav("aus dem Tempel","narjan.php?op=lurn");        
        break;
    }
    
}
page_footer();
?>