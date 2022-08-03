<?php

/*_____________________________________________________________
  |Kratos' Waffen                                             |
  |von Lord Eliwood                                           |
  |Dank bibir ist zunächst die Rechnung vereinfacht...        |
  |Dann aber ist sie überflüssig geworden....                 |
  |___________________________________________________________|
*/
require_once "common.php";
page_header("Kratos' Waffen");
///////////////////////////////////////////////////////////////////////////////////////////////////
output("`c`b`QKratos' Waffen`c`b`n`n");
$tradeinvalue = round(($session[user][weaponvalue]*.75),0);
///////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="")
{
    output("Ein Gott mit strenger Miene steht im Laden und beobachtet dich. Er mustert dich mit erfahren Augen und lässt dich dann sein Angebot sehen.");
    output("Du siehst verschiedene Waffen, die du nie zuvor gesehen hast, weisst aber auch, dass sie nicht billig werden.");
    output("Du stehst nun vor einer schweren Entscheidung. Kaufen oder den Laden so schnell wie es geht verlasen?");
    addnav("Waffen");
    addnav("Flamberge - 15'000 Gold","kratos.php?op=a1");
    addnav("Sol Katti - 25'000 Gold","kratos.php?op=a2");
    addnav("Armads - 50'000 Gold","kratos.php?op=a3");
    addnav("Durandal - 75'000 Gold","kratos.php?op=a4");
    addnav("Zeus Blitze - 100'000 Gold","kratos.php?op=a5");
    addnav("Sonstiges");
    addnav("Zurück zum Olymp","olymp.php");
}
////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a1")
{
    $cost=15000;
    if ($session['user']['gold']==$cost)
    {
        output("Du wählst das Flameberge aus, ein Schwert mit einer rötlichen Klinge. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe Flamberge");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tFlameberge','Waffe','".$session[user][acctid]."','20','15000','Ein Schwert mit der Kraft des Feuers.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a2")
{
    $cost=25000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst das Sol Katti aus, einem heiligen Schwert, dem ein Geist innewohnt. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe Sol Katti`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tSol Katti','Waffe','".$session[user][acctid]."','25','25000','Eine Waffe, in der ein Geist wohnt')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=25000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a3")
{
    $cost=50000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst den Armads aus, einer Axt, der die Kraft der Blitze inne hat. Als du die Axt packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe Armads`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tArmads','Waffe','".$session[user][acctid]."','30','50000','Eine Axt, welche die Kraft der Blitze hat')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=50000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a4")
{
    $cost=75000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst den Durandal aus, eine heilige Klinge, welche die Kraft hat, Wyvern zu töten. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe Durandal`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tDurandal','Waffe','".$session[user][acctid]."','35','75000','Eine Waffe mit der Kraft, Wyvern zu töten')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=75000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a5")
{
    $cost=100000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst Zeus Blitze aus, Blitze, welche vom Gott Hephaistos geschmiedet wurden, doch sind diese von billiger Qualität, aber dennoch stärker als die anderen Waffen.");
        output("Als du die Blitze zu dir nimmst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Zeus Blitze`n");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tZeus Blitze','Waffe','".$session[user][acctid]."','40','100000','Die Blitze von Zeus. Schlechte Qualität')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=100000;
        $session['user']['gold']-=$cost;
        addnav("Zurück zum Olymp","olymp.php");
    }
    else
    {
        output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest.");
        output("Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
        addnav("Zurück zum Olymp","olymp.php");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
page_footer();
?> 