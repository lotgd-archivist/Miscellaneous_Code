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
///////////////////////////////////////////////////////////////////////////////////////////////////
$zustand = $session['user']['armorhealth']10000;
$tradeinvalue = round((($session['user']['armorvalue'].80)$zustand),0);
if($_GET['op']=='')
{
	output("Ein Gott mit strenger Miene steht im Laden und beobachtet dich. Er mustert dich mit erfahren Augen und lässt dich dann sein Angebot sehen. Du siehst verschiedene Rüstungen, die du nie zuvor gesehen hast, weisst aber auch, dass sie nicht billig werden. Du stehst nun vor einer schweren Entscheidung. Kaufen oder den Laden so schnell wie es geht verlassen?`n`nFür deine alte Rüstung bekommst du noch $tradeinvalue Gold.");
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
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
	$session['user']['armor'] = '`tAura Stein';
$session['user']['defence']-=$session['user']['armordef'];
$session['user']['armordef'] = 20;
$session['user']['defence']+=$session['user']['armordef'];
$session['user']['armorvalue'] = 15000;
$session['user']['armorhealth']=10000;
		output("Du wählst das Aura Stein aus, einem Stein, welcher eine schützende Aura ausstrahlt. Als du den Stein packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Aura Stein");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a2")
{
	$cost=25000;
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
		$session['user']['armor'] = '`tEdelbrustpanzer';
$session['user']['defence']-=$session['user']['armordef'];
$session['user']['armordef'] = 25;
$session['user']['defence']+=$session['user']['armordef'];
$session['user']['armorvalue'] = 25000;
$session['user']['armorhealth']=10000;
		output("Du wählst den Edelbrustpanzer aus, ein Brustpanzer, der zu den edlesten gehört. Als du den Brustpanzer packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Edelbrustpanzer`n");

		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a3")
{
	$cost=50000;
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
			$session['user']['armor'] = '`tRunencape';
$session['user']['defence']-=$session['user']['armordef'];
$session['user']['armordef'] = 30;
$session['user']['defence']+=$session['user']['armordef'];
$session['user']['armorvalue'] = 50000;
$session['user']['armorhealth']=10000;
		output("Du wählst das Runencape aus, ein Cape, das Runen als Verziehrungen besitz. Als du das Cape packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Runencape`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a4")
{
	$cost=75000;
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
$session['user']['armor'] = '`tSonnenpanzer';
$session['user']['defence']-=$session['user']['armordef'];
$session['user']['armordef'] = 35;
$session['user']['defence']+=$session['user']['armordef'];
$session['user']['armorvalue'] = 75000;
$session['user']['armorhealth']=10000;
		output("Du wählst den Sonnenpanzer aus, ein Panzer, der die Kraft der Sonne hat. Als du den Panzer packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Sonnenpanzer`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="a5")
{
	$cost=100000;
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
	$session['user']['armor'] = '`tMumbane';
$session['user']['defence']-=$session['user']['armordef'];
$session['user']['armordef'] = 40;
$session['user']['defence']+=$session['user']['armordef'];
$session['user']['armorvalue'] = 100000;
$session['user']['armorhealth']=10000;
		output("Du wählst Mumbane aus eine Harnisch, dem göttliche Kraft inne wohnt.  Als du den Harnisch zu dir nimmst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Rüstung Mumbane`n");
		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Zelos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Rüstungen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
page_footer();
?>