
<?php 
// COPYRIGHT BY KEV AND NEPPAR 
// WEBSITE logd.de.to 
// MAIL: logd@gmx.net 
// Idea from Neppar 
// Make By Neppar 
// FIX 1.0 

// 04-09-2004 


if (!isset($session)) exit(); 
if ($_GET[op]==""){ 
output("`2Auf der Suche nach Monstern begibst du dich in den `7Berg`@wald...`2`nDu weisst, dass dort `QGefahren lauern `2und bist besonders 
vorsichtig!`nGerade deshalb bemerkst du einen abgelegenen Weg, der zu etwas Speziellem zu führen scheint! `2Angespornt schlägst du diesen 
Weg ein. Und was du dann siehst, kommt dir doch sehr merkwürdig vor...`n`n 
`2Du siehst 5 Kapseln, die aussehen wie Medikamente!`n`QEine rote, `@eine grüne, `^eine gelbe, `#eine blaue `7und eine schwarze. `2Irgendetwas sagt dir, dass du nur eine dieser Kapseln wählen kannst, während du dich hier befindest.`n`n
`@Nun liegt es an dir!`nWirst du eine Kapsel schlucken oder ist dir das ganze doch etwas zu unheimlich?? "); 
    output("`0"); // gargamel: setback to standard output color 
    addnav("Nimm die rote Kapsel","forest.php?op=redcaps"); 
    addnav("Nimm die grüne Kapsel","forest.php?op=greencaps"); 
    //addnav("Nimm die blaue Kapsel","berge.php?op=bluecpas");  bugfix gargamel 
    addnav("Nimm die blaue Kapsel","forest.php?op=bluecaps");  //bugfix gargamel 
    addnav("Nimm die gelbe Kapsel","forest.php?op=yellowcaps"); 
    addnav("Nimm die schwarze Kapsel","forest.php?op=blackcaps"); 
    addnav("Nur weg hier","forest.php?op=nocaps"); 
    $session[user][specialinc] = "kapseln.php"; 
} 
// 
else if ($_GET[op]=="nocaps"){ 
    output("`&Das ganze kommt dir komisch vor. Du erinnerst dich, wie deine Mutter dich immer gewarnt hat, Sachen in den Mund zu nehmen, von denen du nicht weißt, wo sie herkommen."); 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
    } 
// 
else if ($_GET[op]=="redcaps"){ 
    output("`QDu entscheidest dich für die rote Kapsel. `n`n`qDu nimmst die Kapsel in den Mund und"); 
    switch(e_rand(1,6)){ 
        case 1: 
        case 2: 
        case 3: 
        output("`qdu fühlst dich voller `QENERGIE!!!! `n`n 
        Deine Lebenspunkte steigen vorübergehend!"); 
        $session[user][hitpoints]*=1.5; 
        break; 

        case 4: 
        case 5: 
        output("`qdu fühlst dich voller `QLEBENSKRAFT!!!`n`n`qDeine Lebenspunkte steigen `bpermanent um 1!`b"); 
        $session[user][maxhitpoints]++; 
        break; 

        case 6: 
        output("`qdu schluckst heftig, um die Kapsel nach unten zu würgen. Doch dummerweise gelangt sie in die `4falsche Röhre. 
        `qDu versuchst keuchend, sie wieder herauszuwürgen, doch da wird dir schon `4schwarz vor Augen. `qDas letzte, was du denkst, ist, das 
        diese Kapsel einen `4Fluch auf sich `qgehabt haben muss, denn du wirst sie nicht mehr los. `n`n 
        `4 Du erstickst an einer KAPSEL!!!`nDu verlierst 10% deiner Erfahrung und all dein Gold.`n`nViel Spass im Schattenreich!"); 
        $session[user][alive]=false; 
        $session[user][gold]=0; 
        $session[user][hitpoints]=0; 
        $session[user][experience]*=0.9; 
        addnav("Tägliche News","news.php"); 
        addnews($session[user][name]." `&ist an einer `QKapsel `berstickt!`b"); 
        break; 
    } 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
    } 
// 
else if ($_GET[op]=="greencaps"){ 
    output("`@Du entscheidest dich für die grüne Kapsel. `n`n`@Du nimmst die Kapsel in den Mund und"); 
    switch(e_rand(1,6)){ 
        case 1: 
        case 2: 
        case 3: 
        output("`@du fühlst dich kräftig genug, um 2 weitere Waldkämpfe zu bestreiten."); 
        $session[user][turns]+=2; 
        break; 

        case 4: 
        case 5: 
        output("`@du fühlst dich `bkräftig`b, so kräftig, um gleich 3 weiteren Gegnern den Garaus zu machen."); 
        $session[user][turns]+=3; 
        break; 

        case 6: 
        output("`@du schluckst heftig, um die Kapsel nach unten zu würgen. Doch dummerweise gelangt sie in die `4falsche Röhre. 
        `@Du versuchst keuchend, sie wieder herauszuwürgen, doch da wird dir schon `4schwarz vor Augen. `@Das letzte, was du denkst, ist, das 
        diese Kapsel einen `4Fluch auf sich `@gehabt haben muss, denn du wirst sie nicht mehr los. `n`n 
        `4 Du erstickst an einer KAPSEL!!!`nDu verlierst 10% deiner Erfahrung und all dein Gold.`n`nViel Spass im Schattenreich!"); 
        $session[user][alive]=false; 
        $session[user][gold]=0; 
        $session[user][hitpoints]=0; 
        $session[user][experience]*=0.9; 
        addnav("Tägliche News","news.php"); 
        addnews($session[user][name]." `&ist an einer `QKapsel `berstickt!`b"); 
        break; 
    } 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
    } 
// 
else if ($_GET[op]=="yellowcaps"){ 
    output("`^Du entscheidest dich für die gelbe Kapsel. `n`n`^Du nimmst die Kapsel in den Mund und"); 
    switch(e_rand(1,6)){ 
        case 1: 
        case 2: 
        case 3: 
        $gold = e_rand($session[user][level]*100,$session[user][level]*250); 
        output("`^du fühlst, wie deine Sinne angeregt werden. Du kannst das Gold in der Umgebung förmlich RIECHEN! `n 
        Und tatsächlich! Durch deine Sinne findest du `b$gold Gold`b, das jemand einmal vergraben hat!"); 
        $session[user][gold]+=$gold; 
        break; 

        case 4: 
        case 5: 
        $gold = e_rand($session[user][level]*200,$session[user][level]*500); 
        output("`^du fühlst, wie deine Sinne sich sträuben und stark angeregt werden. Du kannst das Gold in der Umgebung förmlich RIECHEN! `n 
        Und tatsächlich! Durch deine Sinne findest du ganze `b$gold Gold`b, das jemand einmal vergraben hat!!!!!"); 
        $session[user][gold]+=$gold; 
        break; 

        case 6: 
        output("`^du schluckst heftig, um die Kapsel nach unten zu würgen. Doch dummerweise gelangt sie in die `4falsche Röhre. 
        `^Du versuchst keuchend, sie wieder herauszuwürgen, doch da wird dir schon `4schwarz vor Augen. `^Das letzte, was du denkst, ist, das 
        diese Kapsel einen `4Fluch auf sich `^gehabt haben muss, denn du wirst sie nicht mehr los. `n`n 
        `4 Du erstickst an einer KAPSEL!!!`nDu verlierst 10% deiner Erfahrung und all dein Gold.`n`nViel Spass im Schattenreich!"); 
        $session[user][alive]=false; 
        $session[user][gold]=0; 
        $session[user][hitpoints]=0; 
        $session[user][experience]*=0.9; 
        addnav("Tägliche News","news.php"); 
        addnews($session[user][name]." `&ist an einer `QKapsel `berstickt!`b"); 
        break; 
} 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
} 
// 
else if ($_GET[op]=="bluecaps"){ 
    output("`#Du entscheidest dich für die blaue Kapsel. `n`n`9Du nimmst die Kapsel in den Mund und"); 
    switch(e_rand(1,6)){ 
        case 1: 
        case 2: 
        case 3: 
        output("`9lutschst sie eine Weile. Dann bemerkst du, dass ein kleiner `#Edelstein `9darin versteckt war! `#Klein, aber doch ein Edelstein! `9Zufrieden steckst du ihn ein."); 
        $session[user][gems]++; 
        break; 

        case 4: 
        case 5: 
        output("`9lutschst sie eine Weile. Dann bemerkst du, dass ZWEI kleine `#Edelsteine `9darin versteckt waren! `#Klein, aber doch ZWEI EDELSTEINE!!!! `9Hocherfreut steckst du sie ein."); 
        $session[user][gems]+=2; 
        break; 

        case 6: 
        output("`9du schluckst heftig, um die Kapsel nach unten zu würgen. Doch dummerweise gelangt sie in die `4falsche Röhre. 
        `9Du versuchst keuchend, sie wieder herauszuwürgen, doch da wird dir schon `4schwarz vor Augen. `9Das letzte, was du denkst, ist, das 
        diese Kapsel einen `4Fluch auf sich `9gehabt haben muss, denn du wirst sie nicht mehr los. `n`n 
        `4 Du erstickst an einer KAPSEL!!!`nDu verlierst 10% deiner Erfahrung und all dein Gold.`n`nViel Spass im Schattenreich!"); 
        $session[user][alive]=false; 
        $session[user][gold]=0; 
        $session[user][hitpoints]=0; 
        $session[user][experience]*=0.9; 
        addnav("Tägliche News","news.php"); 
        addnews($session[user][name]." `&ist an einer `QKapsel `berstickt!`b"); 
        break; 
    } 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
    } 
// 
else if ($_GET[op]=="blackcaps"){ 
    output("`7Du entscheidest dich für die SCHWARZE Kapsel. `n`n`7Du nimmst die Kapsel in den Mund und"); 
    switch(e_rand(1,6)){ 
        case 1: 
        case 2: 
        case 3: 
        $favor = e_rand(15, 35); 
        output("`7auf einmal wird dir `4merkwürdig zumute. `7 Als du die Augen öffnest, siehst du `4Ramius `7vor dir. `4O NEIN! `7denkst du, `4ICH BIN TOT!`n`n 
        `7Doch dem ist NICHT so. Ramius sagt, `4dass seine Kapsel dich in eine TRANCE versetzt hat. Du sollst jetzt einen Auftrag für ihn erledigen, dann würdest du wieder erwachen. `n`n`7Obwohl dir das ganze komisch vorkommt, erledigst du den Auftrag und erniedrigst eine Seele, die unerlaubt ins `4Mausoleum eingedrungen `7ist.`n`n`7Und tatsächlich! Danach wachst du wieder auf und dir kommt alles wie ein Traum vor.`n`nRamius belohnt dich jedoch mit `b$favor Gefallen`b für deine Tat."); 
        $session[user][deathpower]+=$favor; 
        break; 

        case 4: 
        case 5: 
        $favor = e_rand(30, 55); 
        output("`7auf einmal wird dir `4merkwürdig zumute. `7 Als du die Augen öffnest, siehst du `4Ramius `7vor dir. `4O NEIN! `7denkst du, `4ICH BIN TOT!`n`n
        `7Doch dem ist NICHT so. Ramius sagt, `4dass seine Kapsel dich in eine TRANCE versetzt hat. Du sollst jetzt einen BESONDEREN Auftrag für ihn erledigen, dann würdest du wieder erwachen.`n`n`7Obwohl dir das ganze komisch vorkommt, erledigst du den nicht ganz einfachen Auftrag und erniedrigst einige Seelen, die unerlaubt ins `4Mausoleum eingedrungen `7sind. `n`n`7Und tatsächlich! Danach wachst du wieder auf und dir kommt alles wie ein Traum vor.`n`nRamius belohnt dich jedoch mit `b$favor Gefallen`b für deine heldenhafte Tat."); 
        $session[user][deathpower]+=$favor; 
        break; 

        case 6: 
        output("`7du schluckst heftig, um die Kapsel nach unten zu würgen. Doch dummerweise gelangt sie in die `4falsche Röhre. 
        `7Du versuchst keuchend, sie wieder herauszuwürgen, doch da wird dir schon `4schwarz vor Augen. `7Das letzte, was du denkst ist, dass 
        diese Kapsel einen `4Fluch auf sich `7gehabt haben muss, denn du wirst sie nicht mehr los. `n`n 
        `4Du erstickst an einer KAPSEL!!!`nDu verlierst 10% deiner Erfahrung und all dein Gold.`n`nViel Spass im Schattenreich!"); 
        $session[user][alive]=false; 
        $session[user][gold]=0; 
        $session[user][hitpoints]=0; 
        $session[user][experience]*=0.9; 
        addnav("Tägliche News","news.php"); 
        addnews($session[user][name]." `&ist an einer `QKapsel `berstickt!`b"); 
        break; 
    } 
    $session[user][specialinc]=""; // gargamel: recommended to clear the include 
    output("`0"); // gargamel: setback to standard output color 
    } 
?>

