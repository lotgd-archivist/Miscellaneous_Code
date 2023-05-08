
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $turns = e_rand(2,3);
    output("`nEine merkwürdige Gestalt kommt Dir auf dem Waldweg entgegen getorkelt.
    Du kannst eine Rüstung erkennen und auch eine Dir unbekannte Waffe.`n`#Das muss ein
    Krieger sein!`0`nDu bereitest Dich auf eine unangenehme Überraschung vor.`n`n
    `2\"Huahhh isch bin starrk\"`0 brüllt der Krieger und sagt dann zu Dir: `2\"Isch bin
    der einzige Überlebende desch grossen Krieges.\"`0`n`n
    Interessiert Dich seine Geschichte, auch wenn es Dich $turns Waldkämpfe kostet?`0");
    //abschluss intro
    addnav("Ja, zuhören","forest.php?op=listen");
    addnav("Nein, weiter gehen","forest.php?op=go");
    $session[user][specialinc] = "madwarrior.php";
}
else if ($HTTP_GET_VARS[op]=="go"){   // gehen
    output("`nDu hast irgendwie keine Lust, Dir seine Geschichte anzuhören.
    Wahrscheinlich eh erstunken und erlogen...`0");
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="listen"){   // gehen
    output("`nDu bittest den Krieger erstmal auf einem Baumstamm platz zu nehmen und
    setzt Dich gleich daneben. Gespannt wartest Du auf seine Geschichte.`0");
    output("`n`n`2\"Isch bin der einzige Überlebende desch grossen Krieges.\"`0 Du hattest
    Dir schon etwas spannenderes vorgestellt, als der Krieger weiterspricht: `2\"
    In dieschem Krieg wurde mit vielen Waffen gekämpft, die unsch fremd waren. Wir
    erhielten Hilfe von den Tieren desch Waldes, sie gaben unsch eine grosse Macht.
    Leider wurden sie zu schpät unschere Verbündeten und der Krieg war nicht
    mehr zu gewinnen. Isch bin der einzige Überlebende desch grossen Krieges. Isch
    trage dasch ganze Wissen in mir, wasch meinen Kopf niemalsch ruhen lässt. Nun
    hab isch jemanden gefunden, dem isch etwasch Wissen weitergeben kann. So komme
    isch vielleicht selbscht zur Ruhe.\"`0`n`nDu kannst nicht verstehen, was der Krieger
    nun zu Dir sagt, es klingt wie eine Beschwörungsformel.`n`n
    `#\"Isch kann Dir keine Erfahrungspunkte geben, aber Du wirscht trotzdem sofort ein
    Level aufschteigen.\"`0 sagt der Krieger und zieht davon. Du hast wirklich nicht den
    Eindruck, dass sein Kopf nun klarer ist.`0");
    increment_specialty();
    $session[user][turns]-=3;
    $session[user][level]++;
    $session[user][maxhitpoints]+=10;   // hitpoints nicht!
    $session[user][soulpoints]+=5;
    $session[user][attack]++;
    $session[user][defence]++;
    $session[user][specialinc] = "";
    addnews("`%".$session[user][name]."`5 hat vom verrückten Krieger genug gelernt, um auf
    Level ".$session[user][level]." aufzusteigen.`0");
}
?>


