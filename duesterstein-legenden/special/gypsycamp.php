
<?
// Gypsy Camp - special by Robert for Maddnet LoGD
// translation by gargamel @ www.rabenthal.de
// Adjustments and modifications by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3`c`bZigeuner-Lager`b`c `n `n");
    output("`2Auf Deinem Weg durch den Wald wirst Du auf eine kleine Lichtung aufmerksam.
    Angezogen von fremden Geräuschen steuerst Du auf ein buntes Treiben zu.`n");
    output("Du hast das `3Zigeuner-Lager`2 entdeckt! `n`n");
    output("`2Du siehst bunte Planwagen und einige Eselskarren. In mitten des Lagers
    flackert ein großes Lagerfeuer und an jedem Wagen brennt ein buntes Licht. Viele
    Gestalten laufen geschäftig durch das Lager, es wird getrunken, gegessen und bei
    typischer Zigeunermusik gefeiert.`n`n
    Dann bemerken Dich die Zigeuner in ihrem Lager. Einige gehen schnell in ihre Wagen,
    während Du von anderen freundlich angelächelt wirst. Eine alte Zigeunerin winkt
    Dich heran zu ihrem Tisch und bietet Dir Speise und Trank an.`0");
    //abschluss intro
    addnav("Essen");
    addnav("Brot","forest.php?op=bread");
    addnav("Trinken");
    addnav("Eimer Wasser","forest.php?op=water");
    addnav("Karaffe Wein","forest.php?op=wine");
    addnav("Spanischer Likör","forest.php?op=fly");
    addnav("Sonstiges");
    addnav("Blinder Zigeuner","forest.php?op=blind");
    addnav("Tänzer","forest.php?op=dancer");
    addnav("Myra","forest.php?op=myra");
    addnav("Vlad","forest.php?op=vlad");
    addnav("Lager verlassen");
    addnav("Zurück in den Wald","forest.php?op=leave");
    $session[user][specialinc] = "gypsycamp.php";


}else if ($HTTP_GET_VARS[op]=="bread"){
    if (e_rand(0,1)==0) {
        output("`2Du nimmst Dir Brot vom Tisch. Hungrig isst Du das ganze Stück
        auf und spürst, wie eine fremde Kraft Deinen Geist stärkt!`n`n
        `&Du erhälst 2 Anwendungen in Diebeskünsten. `n
        `#Aber Du weisst, das diese Kraft morgen wieder vergangen ist.");
        $session[user][thieveryuses] = $session[user][thieveryuses] + 2;
    }
    else {
        output("`2Du nimmst Dir Brot vom Tisch. Hungrig isst Du das ganze Stück
        auf und denkst, dass Du zuviel gegessen hast! `n
        \"Mit vollem Magen kämpft man schlecht!\"`n`n
        `&Du verlierst zwei Waldkämpfe.");
        $session[user][turns]-=2;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="water"){
    if (e_rand(0,1)==0) {
        output("`#Du greift Dir eine Schöpfkelle und füllst Dein Wasserglas aus
        dem grossen Eimer, der neben dem Tisch steht. Nachdem Dein Durst gestillt
        ist, fühlst Du Dich erfrischt!`n`n
        `&Deine Lebenskraft ist wieder vollständig.");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
            $session[user][hitpoints]=$session[user][maxhitpoints];
    }
    else {
        output("`#Du greift Dir eine Schöpfkelle und füllst Dein Wasserglas aus
        dem grossen Eimer, der neben dem Tisch steht. Nachdem Du Dein Glas in einem
        Zug geleert hast, bemerkst Du einen komsichen Nachgeschmack.`n
        Man hat Dir altes Badewasser angeboten!`n`n
        Du verlierst einige Lebenspunkte.`0");
        $session[user][hitpoints]= round($session[user][hitpoints]*0.92);
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="wine"){
    if (e_rand(0,1)==0) {
        output("`2Du nimmst Dir die `3Karaffe mit Wein`2 vom Tisch und füllst Dein Glas.
        Du stillst Deinen Durst und fühlst neue Kraft in Dir.`n`n
        `&Deine Lebenspunkte steigen.");
        $session[user][hitpoints]+=8;
        $session[user][drunkenness]+=50;
    }
    else {
        output("`2Du nimmst Dir die `3Karaffe mit Wein`2 vom Tisch und füllst Dein Glas.
        Während Du es in einem Zug leerst, spürst Du etwas schleimiges Deine Kehle hinunter
        rinnen. Du fühlst Dich krank.`n`n
        Du verlierst einige Lebenspunkte.`0");
        if ( $session[user][hitpoints] > 8 ) {
            $session[user][hitpoints]-= 8;
        }
        else {
            $session[user][hitpoints]=1;
        }
        $session[user][drunkenness]+=50;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="dancer"){
    if (e_rand(0,1)==0) {
        output("`2Du bemerkst ".
        ($session[user][sex]?"einen wunderschönen Tänzer, der "
         :"eine wunderschöne Tänzerin, die ").
        "voller Anmut und Grazie um das Lagerfeuer tanzt. Du gehst hinüber und schaust
        mit Glanz in den Augen zu. Dein Interesse fällt ".
        ($session[user][sex]?"ihm ":"ihr ")."auf und ".($session[user][sex]?"er ":"sie ").
        "schaut liebevoll zurück.`n`n
        `&Du bekommst 2 Charmpunkte.");
        $session[user][charm]+=2;
    }
    else {
        output("`2Du bemerkst ".
        ($session[user][sex]?"einen wunderschönen Tänzer, der "
         :"eine wunderschöne Tänzerin, die ").
        "voller Anmut und Grazie um das Lagerfeuer tanzt. Du gehst hinüber, schaust
        zu und kippst Deinen bitter schmeckenden Wein ins Lagerfeuer. Sofort sticht
        eine hohe Flamme aus dem Lagerfeuer.".
        ($session[user][sex]?"Er ":"Sie ")."erschreckt sich und wirft Dir einen
        `3`iBösen Blick`2`i zu.
        `&`n`n Du verlierst etwas an Charme!");
        $session[user][charm]-=2;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="vlad") {
    if (e_rand(0,1)==0) {
        output("`3Vlad`2 begehrt das Halstuch, dass du trägst. Er bietet Dir
        `^20 Gold `2dafür an.
        `nDa das Halstuch eh alt ist, nimmst Du sein Angebot an!`n`n
        `&Du hast Dein Halstuch für `^20 Gold `2verkauft.");
        $session[user][gold]+=20;
    }
    else {
        output("`3Vlad`2 begehrt das Halstuch, dass du trägst. Er bietet Dir
        `^20 Gold `2dafür an.
        `nDa das Halstuch eh alt ist, nimmst Du sein Angebot an!`n
        Während Du das Halstuch ablegst, gerätst Du ins Straucheln und trittst
        ausversehen ins Lagerfeuer.`n`n
        `&Du verletzt Dich leicht und verlierst einige Lebenspunkte.");
        if ( $session[user][hitpoints] > 8 ) {
            $session[user][hitpoints]-= 8;
        }
        else {
            $session[user][hitpoints]=1;
        }
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="myra") {
    if (e_rand(0,1)==0){
        output("`3Myra `2bietet Dir ein heisses Bad an. Nachdem Du schon eine Weile
        im Wald warst, kannst du ein Bad gut gebrauchen und nimmst ihr Angebot an.`n
        Nach dem Bad in einem Holzzuber fühlst Du Dich erfrischt.`n`n
        `&Du bekommst 5 Lebenspunkte.");
        $session[user][hitpoints]+=5;
    }
    else {
        output("`3Myra `2bietet Dir ein heisses Bad an. Nachdem Du schon eine Weile
        im Wald warst, kannst du ein Bad gut gebrauchen undnimmst ihr Angebot an.`n
        Als Du nach dem Bad aus dem Holzzuber steigst, rutscht Du aus und fällst
        schmerzhaft auf Deinen Hintern.`n`n
        `&Du verlierst ein paar Lebenspunkte.");
        if ( $session[user][hitpoints] > 5 ) {
            $session[user][hitpoints]-= 5;
        }
        else {
            $session[user][hitpoints]=1;
        }
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="blind"){
    if (e_rand(0,1)==0){
          output("`3Ein blinder Zigeuner `2am Tisch bietet Dir die Kraft des Schicksals
          an, wenn er Dein Gesicht berühren darf. Du siehst darin keine Gefahr und
          näherst Dich noch ein Stück, so dass er Dich berühren kann.`n`n
          Er fährt mit seinen alten,knochigen Händen durch Dein Gesicht und sagt:
          \"`3Du bist ein Glückskind!`2\"`n
          Etwas mystisches geht in Dir vor und plötzlich bist Du mit frischer Kraft
          gesegnet!`0");
          if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
             $session[user][hitpoints]=$session[user][maxhitpoints];
          $session[user][turns]+=10;
          $session[user][drunkenness]-=20;
          $session['user']['usedouthouse'] = 0;
          $session['user']['seenmaster'] = 0;
          $session['user']['seenlover'] = 0;
          $session['user']['seenbard'] = 0;
    }
    else {
          output("`3Ein blinder Zigeuner `2am Tisch bietet Dir die Kraft des Schicksals
          an, wenn er Dein Gesicht berühren darf. Du siehst darin keine Gefahr und
          näherst Dich noch ein Stück, so dass er Dich berühren kann.`n`n
          Er fährt mit seinen alten,knochigen Händen durch Dein Gesicht und sagt:
          \"`3Du hast einen schlechten Tag!`2\"`n
          Etwas mystisches geht in Dir vor und plötzlich fühlst Du Dich müde und
          ausgelaugt!`0");
          $session[user][hitpoints]=1;
          if ( $session[user][turns] > 0 ) $session[user][turns]=1;
          $session[user][drunkenness]+=20;
          $session['user']['usedouthouse'] = 1;
          $session['user']['seenmaster'] = 1;
          $session['user']['seenlover'] = 1;
          $session['user']['seenbard'] = 1;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="fly") {
    if (e_rand(0,1)==0){
        output("`2Du beugst Dich über den Tisch und siehst eine Ampulle mit \"Spanischer
        Best Likör\". Du schaust Dich um und als Du Dich kurz unbeobachtet fühlst, trinkst Du
        hastig die Ampulle leer.`n`n
        Als Du die Ampulle wieder weglegst, schaust Du nochmal auf das Etikett. Verdammt!`n
        Da steht `b Spanischer Pest Likör `bnicht `i Best`i. Du erkrankst und kannst nicht
        mehr so gut kämpfen.`0");
        if ( $session[user][hitpoints] > 5 ) {
            $session[user][hitpoints]-= 5;
        }
        else {
            $session[user][hitpoints]=1;
        }
        $session[bufflist]['gypsycamp'] = array("name"=>"`4Spanische Pest",
                                        "rounds"=>35,
                                        "wearoff"=>"Du fühlst Dich besser.",
                                        "defmod"=>0.7,
                                        "atkmod"=>0.7,
                                        "roundmsg"=>"Die Pest schwächt Dich.",
                                        "activate"=>"defense");
    }
    else {
        output("`2Du beugst Dich über den Tisch und siehst eine Ampulle mit \"Spanischer
        Best Likör\". Du schaust Dich um und als Du Dich kurz unbeobachtet fühlst, trinkst Du
        hastig die Ampulle leer.`n
        Sofort fühlt Du Dich viel stärker. Ein Teufelszeug!`0");
        $session[user][hitpoints] += 5;
        $session[bufflist]['gypsycamp'] = array("name"=>"`#Spanischer Likör",
                                        "rounds"=>35,
                                        "wearoff"=>"Deine Kräfte schwinden.",
                                        "defmod"=>1.3,
                                        "atkmod"=>1.3,
                                        "roundmsg"=>"Deine Kräfte sind belebt.",
                                        "activate"=>"defense");
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="leave"){
    output("`n`2Die Zigeuner kommen Dir spanisch vor, Du verlässt das Lager schnellstens und setzt Deinen Weg im Wald fort.");
    $session[user][specialinc]="";
}
?>


