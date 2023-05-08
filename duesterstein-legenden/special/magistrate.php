
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $boun = $session[user][bounty];
    $freeboun = round((e_rand(66,150)*$boun)/100);
    output("`nDu triffst auf den `9Friedensrichter`0, der offensichtlich seinen freien
    Tag mit einer Wanderung im Wald verbringt. Du hast schon einige merkwürdige
    Gerüchte über ihn in der Kneipe gehört.`0");
    if ( $boun > 0 ) {
        output("`nDu bist daher nur wenig erstaunt, als der Dich anspricht:`n
        `9\"Aha, ".$session[user][name]." hier im Wald. Auf Euch ist doch ein Kopfgeld
        ausgesetzt! Nun, da ich heute frei habe, kann ich Euch vielleicht ein wenig
        helfen..\"`0 `n`nund er fährt grinsend fort \"Das Kopfgeld von $boun bereitet Euch
        sicher Sorge. `9Gegen eine kleine Zahlung von $freeboun Gold nehme ich Euch
        einfach von der Liste.`0`n`nWillst Du auf das Angebot eingehen?`0");
        //abschluss intro
        addnav("Angebot annehmen","forest.php?op=accept&sum=$freeboun");
        addnav("Lieber gehen","forest.php?op=decline");
        $session[user][specialinc] = "magistrate.php";
    }
    else {
        output("`nDu grüsst freundlich, er grüsst freundlich zurück und ihr geht
        beide Eures Weges. `n`9\"Hmm, an diesen komischen Gerüchten scheint nichts wahres
        dran zu sein\" denkst Du bei Dir. Der ist doch ganz nett....`0");
    }

}
else if ($HTTP_GET_VARS[op]=="decline"){   // ablehnen
    output("`nDu schlägst das Angebot dankend aus. Wer weiß, was sich die Dorfverwaltung
    einfallen läßt, wenn das rauskommt....`0");
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="accept"){   // annehmen
    $freeboun = $HTTP_GET_VARS[sum];
    $hand = $session[user][gold];
    $bank = $session[user][goldinbank];
    output("`n`9Du überlegst kurz ob des Schmiergeldes von $freeboun Gold, nimmst das Angebot
    aber dankend an.`0");
    if ( ($hand + $bank) < $freeboun ) {  // keine kohle
        output("`n`nLeider hast Du nicht genug Geld, um das Geschäft abzuwickeln.
        Der Friedensrichter hatte sich schon auf die kleine Einnahme gefreut und geht
        nun verärgert davon.`nHoffentlich hat das kein Nachspiel..`0");
    }
    else if ($hand >= $freeboun) {  // aus der geldbörse zahlen
        output("`n`nDu gibst dem Friedensrichter die geforderten $freeboun Goldstücke
        und er verspricht Dir, Dein Kopfgeld zu streichen.`n`nPlötzlich hast Du ein
        `9komisches Gefühl`0. \"Was ist, wenn er mich nicht streicht?\" fragst Du Dich. Du
        beschliesst, das demnächst bei Dag zu überprüfen...`0");
        $session[user][gold]-= $freeboun;
        $session[user][bounty] = 0;
        $session[user][bounties] = 0;
    }
    else {  // aus Geldbörse und bank zahlen
        output("`n`nDa Du gerade nicht soviel Geld dabei hast, bist Du froh zu
        hören, dass sich der Friedensrichter den Rest von der Bank holen will. \"Der
        Bankmitarbeiter ist ein enger Freund von mir\" sagt der Friedensrichter.`n`nDu
        bist nun ein bischen in `9Sorge`0, ob die beiden Freunde auch alles korrekt abwickeln.
        Bei nächster Gelegenheit willst Du mal wieder nach Deinem Kontostand fragen...`0");
        $freeboun-= $session[user][gold];
        $session[user][gold] = 0;
        $session[user][goldinbank]-=$freeboun;
        $session[user][bounty] = 0;
        $session[user][bounties] = 0;
    }
    $session[user][specialinc] = "";
}
?>


