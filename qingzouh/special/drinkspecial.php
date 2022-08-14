
<?php
//Trankspecial by Neppar
//Modificated by Hadriel
//small adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();


if ($_GET[op]==""){
    output("`6Als Du durch den Bergwald läufst, bemerkst Du etwas abgelegen vom Weg
    in der Erde etwas `@Schimmerndes.`6 Du beschließt, es näher anzuschauen, aber
    kannst nichts erkennen, da alles voll mit Erde ist. Du wischst vorsichtig
    die `TErde `6weg und bemerkst, dass es eine `g gefüllte Flasche `6ist!!! Als
    Du sie genauer untersuchst, bemerkst Du eine `#etwas zerrissene Etikette.
    `6Du kannst nicht genau erkennen, was auf ihr steht, doch ein Wort kannst Du
    entziffern:''`^Lebenskraft`6.''`0");
    if ((int)$session[user][race] == 3)
    {
        output("`n`n`6Dein gesunder Menschenverstand lässt Dich ahnen, dass dies
        Deine Lebenskraft auch negativ beeinflussen könnte. `n`n
        `7Möchtest Du trotzdem die merkwürdige Flüssigkeit trinken?`0");
    }
    else
    {
        output("`n`7Trinkst du die Flüssigkeit in der Hoffnung, dass dadurch
        Deine Lebenskraft erhöht wird? Oder lässt Du sie liegen, da das Risiko bei
        etwas im Wald gefundenen gross ist?`0");
    }
    //abschluss intro
    addnav("Flüssigkeit trinken","berge.php?op=drink");
    addnav("Die Finger davon lassen","berge.php?op=nodrink");
    $session[user][specialinc]="drinkspecial.php";
}
else if ($_GET[op]=="drink"){
    $rand = e_rand(1,5);
    output("`6Du beschliesst, trotz allen Gefahren die Flüssigkeit zu trinken. Danach ");
    switch ($rand) {
        case 1: case 2: case 3:
        output("wird Dir sehr wohl zumute! Eine angenehme Wärme umgibt deinen ganzen
        Körper und du merkst, wie dein Körper `bregeneriert`b! Und dann fühlst du,
        wie `^Energie `6in deinen Körper strömt! `n`n
        Der Trank hat dir
        einen `^`bpermanenten`b `6Lebenspunkt beschert!`n`nGlücklich über die positive
        Auswirkung des Trankes gehst du zurück in den Wald.`0");
        $session[user][maxhitpoints]++;
        $session[user][hitpoints]++;
        break;
        case 4: case 5:
        output("`6spürst Du ein komisches Gefühl in Deinem Magen. Dir wird schlecht
        und Du musst brechen. Während Du erbrichst, merkst Du, wie die Energie aus
        Deinem Körper schwindet. Dir wird schwindlig und Du fällst in Ohnmacht. Auch
        nachdem Du wieder aufgewacht bist, fühlst Du Dich schwach. Du hättest lieber
        die Finger von diesem Trank lassen sollen.`n`n`0");
        $session[user][maxhitpoints]--;
        output("`4Du verlierst `bpermanent`b einen Lebenspunkt!!!`0");
        break;
     }
    $session[user][specialinc]="";
}
else if ($_GET[op]=="nodrink"){
    output("`@Du traust dem Braten, ehm, dem Trank nicht und läufst lieber weiter.
    Wer weiss, was da drin sein könnte?! Soll das doch ein anderer ausprobieren...");
    $session[user][specialinc]="";
}
?>

