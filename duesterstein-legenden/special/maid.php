
<?
// Original by Lord Wolfen for www.FLEIGH.net
// Translation and some adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`@Du entdeckst ein junges Mädchen.`n`n
    \"`#Ich habe mich verlaufen,`@\" sagt das süsse kleine Mädchen, \"`#kannst Du mich
    zurück zur Taverne bringen?`@\"`n`n
    Du weisst, dass Dich Ihr Wunsch einen Waldkampf kostet.`0");
    //abschluss intro
    addnav("Bring sie zurück","forest.php?op=escort");
    addnav("Erkläre den Weg","forest.php?op=speak");
    addnav("Keine Zeit","forest.php?op=away");
    $session[user][specialinc] = "maid.php";
}
else if ($HTTP_GET_VARS[op]=="escort"){
    $session[user][turns]--;
    switch ( e_rand (1,22) ) {
        case 1: case 2: case 3: case 4:
        output("`n`@Du nimmst Dir die Zeit und bringst das Mädchen zurück zur Taverne.
        Als Du sie sicher bei ihren Eltern abgibst, bedanken sich beide überschwenglich.
        Sie erzählen jedem von Deiner Heldentat.`n
        `0Du bekommst zwei Charmpunkte und einen Waldkampf!`0");
        $session[user][turns]++;
        $session[user][charm]+= 2;
        break;
        case 5: case 6: case 7: case 8: case 9: case 10:
        $gold = e_rand($session[user][level]*10, $session[user][level]*30);
        output("`n`@Als Du sicher mit dem Mädchen an der Taverne angelangt bist und sie
        ihren Eltern übergibst, zücken diese ihre Goldbeutel.`n
        `0Zum Dank geben sie Dir $gold Gold.`0");
        $session[user][gold] += $gold;
        break;
        case 11: case 12: case 13: case 14: case 15: case 16:
        output("`n`@Du nimmst Dir die Zeit und bringst das Mädchen zurück zur Taverne.
        Als Du sie sicher bei ihren Eltern abgibst, bedanken sich beide herzlich.
        Sie erzählen ihren Freunden von Deiner Heldentat.`n
        `0Du bekommst einen Charmpunkt.`0");
        $session[user][charm]++;
        break;
        case 17: case 18: case 19: case 20: case 21:
        output("`n`@Du nimmst Dir die Zeit und bringst das Mädchen zurück zur Taverne.
        Als Du sie sicher bei ihren Eltern abgibst, bedanken sich beide besonders herzlich.
        Sie erzählen ihren Freunden und Nachbarn von Deiner Heldentat.`n
        `0Du bekommst zwei Charmpunkte.`0");
        $session[user][charm]+= 2;
        break;
        case 22:
        output("`n`@Du nimmst Dir die Zeit und bringst das Mädchen zurück zur Taverne.
        Dort stellst Du überrascht fest, dass ihr Vater der edle Georg von Rabenfels ist,
        ein Ritter des Königs.`n
        `0Du bekommst einen Verteidigungspunkt!`0");
        $session[user][defence]++;
        break;
    }
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="speak"){
    output("`n`@Du gibst dem Mädchen zu verstehen, dass Du sie nicht zurückbringen
    kannst. Wenigstens gibst Du Dir Mühe, ihr den Weg zurück zu erklären.`n
    `0Trotzdem verlierst Du einen Charmpunkt.`0");
    $session[user][charm]--;
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="away"){
    output("`n`@Du murmelst etwas von \"Keine Zeit\" und drehst Dich um, um schnell
    davonzugehen. In Deiner falschen Eile rennst Du direkt in eine Tanne. Du regst Dich
    furchtbar auf und gibst dem Mädchen die Schuld dafür.`n`0");
    $chance = e_rand(1,100);
    if ( $chance < 40 ) {
        output("Du verlierst 2 Charmpunkte und einen Waldkampf.`0");
        $session[user][charm]-= 2;
        $session[user][turns]--;
    }
    else {
        output("Du verlierst 2 Charmpunkte.`0");
        $session[user][charm]-= 2;
    }
    $session[user][specialinc] = "";
}
?>


