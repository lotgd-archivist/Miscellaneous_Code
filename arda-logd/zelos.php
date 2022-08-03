<?php

/*_____________________________________________________________
  |Zelos' Rüstungen                                             |
  |von Lord Eliwood                                           |
  |Vereinfachte Rechnungen dank bibir                         |
  |___________________________________________________________|
*/
require_once "common.php";
page_header("Zelos' Rüstungen");
///////////////////////////////////////////////////////////////////////////////////////////////////
output("`c`b`QZelos' Rüstungen`c`b`n`n");
$tradeinvalue = round(($session[user][weaponvalue]*.75),0);
///////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="")
{
    output("Ein Gott mit strenger Miene steht im Laden und beobachtet dich. Er mustert dich mit erfahren Augen und lässt dich dann sein Angebot sehen.");
    output("Du siehst verschiedene Rüstungen, die du nie zuvor gesehen hast, weisst aber auch, dass sie nicht billig werden.");
    output("Du stehst nun vor einer schweren Entscheidung. Kaufen oder den Laden so schnell wie es geht verlasen?");
    addnav("Rüstungen");
    addnav("Aura Stein - 15'000 Gold","zelos.php?op=a1");
    addnav("Edelbrustpanzer - 25'000 Gold","zelos.php?op=a2");
    addnav("Runencape - 50'000 Gold","zelos.php?op=a3");
    addnav("Sonnenpanzer - 75'000 Gold","zelos.php?op=a4");
    addnav("Mumbane - 100'000 Gold","zelos.php?op=a5");
    addnav("Sonstiges");
    addnav("Zurück zum Olymp","olymp.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a1")
{
    $cost=15000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst das Aura Stein aus, einem Stein, welcher eine schützende Aura ausstrahlt. Als du den Stein packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Rüstung Aura Stein");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tAura Stein','Rüstung','".$session[user][acctid]."','20','15000','Ein Aura-Stein.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a2")
{
    $cost=25000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst den Edelbrustpanzer aus, ein Brustpanzer, der zu den edlesten gehört. Als du den Brustpanzer packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Rüstung Edelbrustpanzer`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tEdelbrustpanzer','Rüstung','".$session[user][acctid]."','25','25000','Ein Edelbrustpanzer')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=25000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a3")
{
    $cost=50000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst das Runencape aus, ein Cape, das Runen als Verziehrungen besitz. Als du das Cape packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Rüstung Runencape`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tRunencape','Rüstung','".$session[user][acctid]."','30','50000','Ein Cape mit Runenverzierung')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=50000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a4")
{
    $cost=75000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst den Sonnenpanzer aus, ein Panzer, der die Kraft der Sonne hat. Als du den Panzer packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Rüstung Sonnenpanzer`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tSonnenpanzer','Rüstung','".$session[user][acctid]."','35','75000','Ein Panzer mit der Kraft der Sonne')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=75000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a5")
{
    $cost=100000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst Mumbane aus eine Harnisch, dem göttliche Kraft inne wohnt. ");
        output("Als du den Harnisch zu dir nimmst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Mumbane`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tMumbane','Rüstung','".$session[user][acctid]."','40','100000','Die Göttliche Rüstung Mumbane')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=100000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
page_footer();
?> 