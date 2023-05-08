
<?
// basic szenario seen at: www.hadriel.ch
// some mods by gargamel @ www.rabenthal.de
if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`^Du entdeckst einen Lederschlauch mit Orkbier, der über einem Baumstumpf
    hängt. Es ist auf ihm vermerkt, dass er aus der Haut eines Kämpfer gefertigt ist,
    den Du als sehr starken Krieger kanntest. `0");
    //abschluss intro
    addnav("Orkbier trinken","forest.php?op=drink");
    addnav("einfach weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "orkbier.php";
}
else if ($HTTP_GET_VARS[op]=="drink"){   // trinken
    output("`nDu beschliesst, dass es nicht schaden kann, einen Schluck daraus zu nehmen.`n`n`n
    Wow, dass Zeug haut rein!  `^Du fühlst dich `!super.`n`n
    Und etwas angetrunken, `%erhältst aber einen extra Waldkampf!`^ `0");
    $session[user][turns]++;
    $session[user][drunkenness]+= 75;
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du verstehst die Aufschrift als Warnung und gehst weiter. Orkplörre schmeckt
    eh nicht!`0");
    $session[user][specialinc]="";
}
?>


