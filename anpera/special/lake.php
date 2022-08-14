
ï»¿<?php



// 27062004



/* lake.php - an ol' temple's lake (Der Tempelsee)

* by weasel

* v2.0

* Extended by Warchild for lotgd.de

* in June 2004

* Idea by Burn

*

* Changelog

* 01.06.2004-11:32-warchild: Modifikation 1: Texte ausgedehnt, mehr MÃ¶glichkeiten

* 07.06.2004-12:16-warchild: kleinere Korrekturen

*/

output("`n`c`b`&Der Tempelsee`c`b`n");



if ($HTTP_GET_VARS[op]=="norp")

{

    $session[user][specialinc]="";

    output("`2Dir kommt das Ganze etwas komisch vor. Du machst lieber einen groÃŸen Bogen um diese FlÃ¼ssigkeit.`n");

    $chance = e_rand(1,4);

    if ($chance > 3 && $session[user][turns] >0)

    {

        output("Deine Neugier treibt Dich allerdings dazu an, diesen Ort weiter zu erkunden. Doch auch nachdem Du in jedem Winkel gestÃ¶bert hast und selbst noch `7einen Stein `2aus der Strasse gebrochen hast, kannst Du nichts von Bedeutung finden. Frustiert stapfst Du zurÃ¼ck in den Wald.`n`n");

        output("`^Die verlorene Zeit hÃ¤ttest Du besser in einen Waldkampf gesteckt!");

        $session[user][turns]--;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

}

else if ($HTTP_GET_VARS[op]=="trinken")

{

    // Wasserfarbe festhalten

    $colour = $HTTP_GET_VARS[water];



    $session[user][specialinc]="";



    $rand = e_rand(1,3);

    if ($colour=="red")

        $rand += 3;

    else if ($colour=="brown")

        $rand += 6;

    // result: blue 1-3, red 4-6, brown 7-9

    // wished results: blue 2/3 positive, red 1/3 positive, brown negative // not exactly with e_rand. red most



    output("`2Du nimmst einen krÃ¤ftigen Schluck, und wartest ab was passiert..`n`n");



    switch ($rand)

    {

        // blue

        case 1:

        output("`2AuÃŸer das die BrÃ¼he kalt war hast du nichts weiter gespÃ¼rt.`n");

        output("`2Naja du fÃ¼hlst dich wenigstens wieder frisch.`n`n");

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        case 2:

        output("`^Du spÃ¼rst wie dein Blut pulsiert und sich das blaue GlÃ¼hen auf deinen KÃ¶rper Ã¼bertrÃ¤gt.`n`n");

        output("`^Als du deine Hand ansiehst fÃ¤llt dir auf, das dort das GlÃ¼hen gebÃ¼ndelt wird.`n");

        $session[user][gems]++;

        output("`^Ein Edelstein hat sich in deiner Hand gebildet. Du hast jetzt insgesamt ".$session[user][gems].", `n");

        output("wird es nicht Zeit langsam einen Juwelierladen zu erÃ¶ffnen?");

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        case 3:

        output("`^Du fÃ¼hlst wie dein KÃ¶rper regeneriert.`n");

        output("`^Jetzt wo du weiÃŸt, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig `n");

        output("und trÃ¤umst davon den GrÃ¼nen Drachen zu besiegen.");

        if ($session[user][turns]>0) $session[user][turns]--;

        $session[user][hitpoints] = $session[user][maxhitpoints];

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        // red

        case 4: // Brogads hot Chili by Burn

        output("`^Du probierst das Wasser und stellst fest, dass es genauso schmeckt wie `\$Brogads `&heisse `\$ChilisoÃŸe`^! Verdammt, von dem Zeug hÃ¤ttest du schon damals die Finger lassen sollen!`n");

        output("`^Die extreme SchÃ¤rfe lÃ¤sst deine Augen trÃ¤nen und dich unkontrolliert husten. Als sich die SoÃŸe ihren Weg durch Deine Eingeweide wÃ¼hlt, weiÃŸt du instinktiv, dass du die nÃ¤chsten Stunden heulend in den BÃ¼schen verbringen wirst.`n");

        if ($session[user][turns]<3) $session[user][turns] = 0;

        else

            $session[user][turns] -= 2;

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        case 5: // DÃ¤monenblut by Burn

        output("`^Als du das Zeug hinunterschluckst, spÃ¼rst du, wie sich deine Wahrnehmung leicht verÃ¤ndert. Die eingestÃ¼rzten SÃ¤ulenteile tragen zum Teil dÃ¤monische Fratzen, die dich hÃ¶hnisch anzugrinsen scheinen. Du spÃ¼rst eine VerÃ¤nderung in deinem KÃ¶rper...`n");

        if ($session['user']['specialty'] == 1) // Darkarts

        {

            output("da die `\$Essenz des BÃ¶sen`^ durch deine Adern rinnt! Die DÃ¤monen wispern dir dunkle Geheimnisse zu!`n`#");

            increment_specialty();

        }

        else

        {

            output("da etwas `\$BÃ¶sartiges, Fremdes`^ durch deine Adern rinnt. Du bekommst einen juckenden Ausschlag, der auch nach ein paar Tagen nicht verschwinden wird.`n");

            output("Du verlierst einen Charmpunkt!");

            $session[user][charm]--;

    $session[user][reputation]--;

        }

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        case 6:

        output("`^Du fÃ¼hlst wie dein KÃ¶rper regeneriert.`n");

        output("`^Jetzt wo du weiÃŸt, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig. Dann kehrst Du mit neuem Mut in den Wald zurÃ¼ck.`nDu kannst heute ein paar Monster mehr erschlagen!`n");

        $session[user][turns]++;

        $session[user][hitpoints] = $session[user][maxhitpoints];

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        // brown

        case 7:

        output("`^Die BrÃ¼he ist einfach ekelhaft.`n");

        output("`^Du Ã¼bergibst Dich spontan und kehrst dem Ort dann hastig den RÃ¼cken zu. GlÃ¼cklicherweise hatte dein unvorsichtiger Trunk keine weiteren Konsequenzen.`n");

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;



        case 8:

        output("`^Du versuchst zu schlucken, doch irgend ein kleiner Tierknochen bleibt in deinem Hals stecken.`n");

        output("`^WÃ¤hrend du auf die Knie sinkst und vergeblich nach Luft schnappst, Ã¼berlegst du noch, dass es eine dumme Idee war, von dem Zeug zu trinken...`n`n");

        output("`&Du bist gestorben! Du verlierst 10% deiner Erfahrung und kannst morgen wieder spielen!");

        $session[user][alive]=0;

        $session[user][hitpoints]=0;

        $session[user][experience]=$session[user][experience]*0.9;

        addnav("TÃ¤gliche News","news.php");

        addnews("`6".$session[user][name]." `6erstickte qualvoll an einem Vogelknochen!");

        break;



        case 9:

        output("`^Der faulige Geschmack lÃ¤ÃŸt dich wÃ¼rgen, und du stolperst in die BÃ¼sche, wÃ¤hrend sich dein Magen umdreht.`n");

        output("`^Erstaunlicherweise findest du auf dem RÃ¼ckweg `42 Edelsteine`^ als Du in ein verlassenes Ã„ffchennest trittst.`n");

        $session[user][gems] += 2;

        addnav("ZurÃ¼ck in den Wald","forest.php");

        break;

    }

}else if ($HTTP_GET_VARS[op]=="spiegel") {

    output("`2Du beugst dich Ã¼ber den See, um dein Spiegelbild zu betrachten. Das blÃ¤uliche Wasser zeigt tatsÃ¤chlich ein Bild von dir, allerdings ist es ");

    output("ungewÃ¶hnlich verschwommen. Als du das Bild genauer betrachtest, stellst du fest, dass es dir weit mehr zeigt, als nur dein Aussehen:`n`n");

    output("`#Charme: ".grafbar(100,$session[user][charm],"20%",10)."`n`#Ansehen: ".grafbar(100,($session[user][reputation]+50),"20%",10),true);

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session[user][specialinc]=="";

}

else

{

    output("`2Du stehst am Rande einer alten Tempelruine. Einige SÃ¤ulen sind zerfallen und liegen verteilt auf dem Boden. Ein seltsames graues Zwielicht herrscht Ã¼ber diesem Ort, als sei er zwischen Zeit und Raum eingefroren. `n");

    output("`2Die fein behauenen Rundsteine, mit denen die Umgebung gepflastert sind, sind `@grasbewachsen `2und an unregelmÃ¤ssigen Stellen herausgebrochen, als habe jemand willkÃ¼rlich Stolperstellen erzeugen wollen.");

    output("`2Vorsichtig schaust du dich etwas um und entdeckst eine kleine Quelle, die aus einer Wand des GemÃ¤uers austritt.`n`n");

    // Farbe zufÃ¤llig ermitteln, Codes:

    // blÃ¤ulich ++ (60%) // percentage does not fit. values in the middle appear more often with e_rand()

    // rÃ¶tlich + (30%)

    // braun -- (10%)

    $watertype = e_rand(1,10);

    switch ($watertype)

    {

        case 1:

        case 2:

        case 3:

        case 4:

        case 5:

        case 6:

            $colour = "blue";

            output("`2Dir fÃ¤llt auf, dass das Wasser `#leicht blÃ¤ulich `2glÃ¼ht. Nach nÃ¤herer Untersuchung kannst du nichts feststellen ");

            output("auÃŸer das es eben `#blÃ¤ulich glÃ¼ht`2.`n");

            break;

        case 7:

        case 8:

        case 9:

            $colour = "red";

            output("`2Dir fÃ¤llt auf, dass das Wasser einen `4roten Schimmer`2 hat. Nach nÃ¤herer Untersuchung kannst du nichts feststellen ");

            output("auÃŸer das es eben einen `4roten Schimmer `2hat.`n");

            break;

        case 10:

            $colour = "brown";

            output("`2Dir fÃ¤llt auf, dass das Wasser nur eine stinkende `6braune Suppe `2ist. Nach nÃ¤herer Untersuchung kannst du feststellen ");

            output("dass in der Suppe einige tote Tiere treiben. `n");

            break;

    }

    output("Das Wasser sammelt sich in einem halbrunden Becken am FuÃŸ der alten Mauer, doch scheint das Becken nicht Ã¼berzulaufen. ");

    output("Wahrscheinlich gibt es irgendwo einen Abfluss.`n");

    output("In dem kleinen Becken hat sich jedoch genug angesammelt, um von ".($session[user][sex]?"einer zufÃ¤llig vorbeiziehenden Kriegerin":"einem zufÃ¤llig vorbeiziehenden Krieger")." getrunken werden zu kÃ¶nnen ");

    output("- wenn ".($session[user][sex]?"sie":"er")." wollte...");

    output("Das Wasser ist glatt und spiegelt die Landschaft wider.`n");

    addnav("Ich hab Durst!","forest.php?op=trinken&water=".$colour);

    addnav("Spiegelbild","forest.php?op=spiegel");

    addnav("Ich lasse es lieber bleiben!","forest.php?op=norp");

    $session[user][specialinc]="lake.php";

}

?>

