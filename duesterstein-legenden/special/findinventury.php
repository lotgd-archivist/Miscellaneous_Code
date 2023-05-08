
<?
// ----------------------------------------------------------------------------
// Inventory System - find items
// 21st October 2004
// Version: 0.9
// Author: Gargamel
//
// Find a random item in the forest for later use.
// Each item will be displayed as small clickable item in your stats.
// This modul is part of the re-release package of the guilds mod.
//
// Preliminaries:
// Mod: Portable Potions with clickable icons
// Author: Lonnyl of http://www.pqcomp.com/logd
//
// Credits:
// Thanks Lonnyl, you're mod (Portable Potion) was inspiration and
// general code base for this.
//
// Install instruction:
// - copy this file into your LOGD special folder.
// - see all instructions commin' together with the re-release of the
//   Clans/Guilds mod by Dasher with Changes/Enhancements by Gargamel.
//
// Contact Gargamel:
// eMail: gargamel@rabenthal.de or gargamel@silienta-logd.de
// Forum: Gargi at dragonprime
//
//
// This program is free software; you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by the
// Free Software Foundation; either version 2 of the License, or (at your option)
// any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT
// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
// FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// this program; if not, write to the
// Free Software Foundation, Inc., 59 Temple Place, Suite 330,
// Boston, MA 02111-1307, USA.
//
// ----------------------------------------------------------------------------
//

$was = e_rand(1,12);
switch ($was) {
    case 1:
    $val="heal";
    $text="einen mittleren Trank mit heilenden Kräften.";
    break;
    case 2:
    $val="ring";
    $text="einen silbernen Ring. Er verleiht Dir einen magischen Schutz, der Deine Verteidigung stärkt.";
    break;
    case 3:
    $val="fullheal";
    $text="einen starken Heiltrank.";
    break;
    case 4:
    $val="dagger";
    $text="einen kleinen Dolch, der Deinem Gegener zusätzlichen Schaden zufügt.";
    break;
    case 5:
    $val="blueflame";
    $text="eine blaue Flamme. Jeder Gegner wird davon eingeschüchtert werden.";
    break;
    case 6:
    $val="exp";
    $text="ein Buch über Kampfkunst, dass Deine Erfahrung steigern wird.";
    break;
    case 7:
    $val="mornstar";
    $text="einen Morgenstern, der Dir als Waffe gute Dienste leisten wird. ";
    break;
    case 8:
    $val="skull";
    $text="einen Totenschädel. Ramius wird begeistert sein.";
    break;
    case 9:
    $val="injury";
    $text="eine giftige Flüssigkeit, die Deinem Gegner nicht wohl bekommen wird. ";
    break;
    case 10:
    $val="strength";
    $text="einen Trank der Stärke, der kurzfristig Deine Muskeln wachsen lässt.";
    break;
    case 11:
    $val="magic";
    $text="einen magischen Trank. Irgendeinen Zauber wird das Ding auslösen...";
    break;
    case 12:
    $val="vitality";
    $text="einen Vital-Trank. Erfrischt kannst Du wieder auf Deine Spezialfähigkeiten vertrauen.";
    break;
    case 13:
    $val="mana";
    $text="einen Trank, der Deinem Tier neue Kraft gibt.";
    break;
    case 14:
    $val="special";
    $text="einen Trank, der Dir Kraft in einer Spezialfähigkeit gibt.";
    break;
    case 15:
    $val="plate";
    $text="eine Rüstung, die natürlich Deine Verteidigung stärkt.";
    break;
    //
}

output("`nHeute ist Dein Glückstag!!`n`n
Du findest $text");
array_push($session['user']['inventory'],$val);
?>


