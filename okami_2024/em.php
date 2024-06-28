
<?php


/********************
 * Edelsteinhändler *
 ********************/

require_once "common.php";
$session[user][standort]="Edelsteinmarkt";
page_header("Edelsteinmarkt");



if ($_GET['op']==""){
output("`7Du betrittst den Edelsteinhänler und Sven weisst dich auf sein Preisschild hin.
        Scheinbar ist er kein Mann der großen Worte.`n`n`n
         `#1 Edelstein  `&für  `^3000 Gold.`n
         `#5 Edelsteine `&für `^15000 Gold.`n
        `#10 Edelsteine `&für `^30000 Gold.`n`n
         `^2500 Gold `&für  `#1 Edelstein.`n
        `^12500 Gold `&für  `#5 Edelsteine.`n
        `^25000 Gold `&für `#10 Edelsteine.`n`n`0");

addnav("Ankauf");
addnav("`#1 Edelstein `&kostet `^3000 Gold`0","em.php?op=g1");
addnav("`#5 Edelstein `&kostet `^15000 Gold`0","em.php?op=g2");
addnav("`#10 Edelstein `&kostet `^30000 Gold`0","em.php?op=g3");
addnav("Verkaufen");
addnav("`&Für`#1 Edelstein `&bekommst  du `^2500 Gold`0","em.php?op=g4");
addnav("`&Für`#5 Edelstein `&bekommst du `^12500 Gold`0","em.php?op=g5");
addnav("`&Für`#10 Edelstein `&bekommst du `^25000 Gold`0","em.php?op=g6");

addnav("Zurück");
addnav("Zurück","mg.php");
}

else if ($_GET['op']=="g1"){
if ($session['user']['gold']>2999){
    output("Du bezahlst `^3000 `$Gold");
    output("`#Du legst das Gold auf den Tresen und bekommst deine Edelsteine.");
    $session['user']['gems']+=1;
    $session['user']['gold']-=3000;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast nicht genügend Gold.");
    addnav("raus","mg.php");
    }
}

else if ($_GET['op']=="g2"){
if ($session['user']['gold']>14999){
    output("Du bezahlst `^3000 `$Gold");
    output("`#Du legst das Gold auf den Tresen und bekommst deine Edelsteine.");
    $session['user']['gems']+=5;
    $session['user']['gold']-=15000;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast nicht genügend Gold.");
    addnav("raus","mg.php");
    }
}

else if ($_GET['op']=="g3"){
if ($session['user']['gold']>29999){
    output("Du bezahlst `^30000 `$Gold");
    output("`#Du legst das Gold auf den Tresen und bekommst deine Edelsteine.");
    $session['user']['gems']+=10;
    $session['user']['gold']-=30000;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast nicht genügend Gold.");
    addnav("raus","mg.php");
    }
}

else if ($_GET['op']=="g4"){
if ($session['user']['gems']>0){
    output("Du bekommst `^2500 `$Gold");
    output("`#Du legst den Edelstein auf den Tresen und bekommst dafür etwas Gold.");
    $session['user']['gems']-=1;
    $session['user']['gold']+=2500;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast ja gar keinen Edelstein.");
    addnav("raus","mg.php");
    }
}

else if ($_GET['op']=="g5"){
if ($session['user']['gems']>4){
    output("Du bekommst`^12500 `$Gold");
    output("`#Du legst die Edelsteine auf den Tresen und bekommst dafür etwas Gold.");
    $session['user']['gems']-=5;
    $session['user']['gold']+=12500;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast nicht genügend Edelsteine.");
    addnav("raus","mg.php");
    }
}

else if ($_GET['op']=="g6"){
if ($session['user']['gems']>9){
    output("Du bekommst `^25000 `$Gold");
    output("`#Du legst die Edelsteine auf den Tresen und bekommst dafür etwas Gold.");
    $session['user']['gems']-=10;
    $session['user']['gold']+=25000;
    addnav("raus","mg.php");
    }else{
    output("`#Du hast nicht genügend Edelsteine.");
    addnav("raus","mg.php");
    }
}

page_footer();
?>

