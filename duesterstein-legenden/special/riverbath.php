
<?
//Author: Lonny Luberts  Web: http://www.pqcomp.com/logd
//tranferred into forest special
//with some modifications : gargamel @ www.rabenthal.de

if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`n`8Du kommst bei einem kleinen Bach vorbei. Das Wasser ist einladend
    sauber. Was für ein schöner Platz zum baden!`0");
    //abschluss intro
    addnav("Baden gehen","forest.php?op=bathe");
    addnav("Weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "riverbath.php";
}
else if ($HTTP_GET_VARS[op]=="bathe"){
    output("`n`8Schnell ziehst Du Dich aus und springst in den Bach. Als Du wieder
    heraussteigst fühlst Du Dich viel frischer und sauberer!`n`0");
    $was = e_rand(1,3);
    switch ($was) {
        case 1:
        output("Zufrieden gehst Du weiter.`0");
        break;
        case 2:
        output("`nFrisch gewaschen bekommst Du einen Charmepunkt.`0");
        $session[user][charm]++;
        break;
        case 3:
        output("`nIm Bachbett siehst Du plötzlich 5 Goldstücke funkeln.`0");
        $session[user][gold]+= 5;
        break;
    }
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="cont"){
    output("`n\"Sauberes Wasser kann täuschen\" denkst du und gehst weiter.`0");
    $session[user][specialinc] = "";
}
?>


