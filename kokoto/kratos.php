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
///////////////////////////////////////////////////////////////////////////////////////////////////
$zustand = $session['user']['weaponhealth']10000;
$tradeinvalue = round((($session['user']['weaponvalue']0.80)$zustand),0); 
if($_GET['op']=='')
{
	output("Ein Gott mit strenger Miene steht im Laden und beobachtet dich. Er mustert dich mit erfahren Augen und lässt dich dann sein Angebot sehen. Du siehst verschiedene Waffen, die du nie zuvor gesehen hast, weisst aber auch, dass sie nicht billig werden. Du stehst nun vor einer schweren Entscheidung. Kaufen oder den Laden so schnell wie es geht verlassen?`n`nFür deine alte Waffe bekommst du noch $tradeinvalue Gold.");
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
	$cost=$cost$tradeinvalue;
	if ($session['user']['gold']>=$cost)
	{
		output("Du wählst das Flameberge aus, ein Schwert mit einer rötlichen Klinge. Als du das Schwert packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Flamberge");
					$session['user']['weapon'] = '`tFlameberge';
					$session['user']['attack']-=$session['user']['weapondmg'];
					$session['user']['weapondmg'] = 20;
					$session['user']['attack']+=$session['user']['weapondmg'];
					$session['user']['weaponvalue'] = 15000;
					$session['user']['weaponhealth']=10000; 

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
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
					$session['user']['weapon'] = '`tSol Katti';
					$session['user']['attack']-=$session['user']['weapondmg'];
					$session['user']['weapondmg'] = 25;
					$session['user']['attack']+=$session['user']['weapondmg'];
					$session['user']['weaponvalue'] = 25000;
					$session['user']['weaponhealth']=10000; 
		output("Du wählst das Sol Katti aus, einem heiligen Schwert, dem ein Geist innewohnt. Als du das Schwert packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Sol Katti`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
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
						$session['user']['weapon'] = '`tArmads';
					$session['user']['attack']-=$session['user']['weapondmg'];
					$session['user']['weapondmg'] = 30;
					$session['user']['attack']+=$session['user']['weapondmg'];
					$session['user']['weaponvalue'] = 50000;
					$session['user']['weaponhealth']=10000; 
		output("Du wählst den Armads aus, einer Axt, der die Kraft der Blitze inne hat. Als du die Axt packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Armads`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
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
							$session['user']['weapon'] = '`tDurandal';
					$session['user']['attack']-=$session['user']['weapondmg'];
					$session['user']['weapondmg'] = 35;
					$session['user']['attack']+=$session['user']['weapondmg'];
					$session['user']['weaponvalue'] = 75000;
					$session['user']['weaponhealth']=10000;
		output("Du wählst den Durandal aus, eine heilige Klinge, welche die Kraft hat, Wyvern zu töten. Als du das Schwert packst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Durandal`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
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
								$session['user']['weapon'] = '`tZeus Blitze';
					$session['user']['attack']-=$session['user']['weapondmg'];
					$session['user']['weapondmg'] = 40;
					$session['user']['attack']+=$session['user']['weapondmg'];
					$session['user']['weaponvalue'] = 100000;
					$session['user']['weaponhealth']=10000;
		output("Du wählst Zeus Blitze aus, Blitze, welche vom Gott Hephaistos geschmiedet wurden, doch sind diese von billiger Qualität, aber dennoch stärker als die anderen Waffen. Als du die Blitze zu dir nimmst, strömt Energie durch deinen Körper. Du bist nun stolzer Besitzer der Waffe Zeus Blitze`n");

		$session['user']['gold']-=$cost;
		addnav("Zurück zum Olymp","olymp.php");
	}
	else
	{
		output("Kratos sieht dich verwirrt an und fragt dich mit ärgerlicher Stimme, ob du noch alle Tassen im Schrank hättest. Seine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!");
		addnav("Zurück zum Olymp","olymp.php");
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
page_footer();
?>