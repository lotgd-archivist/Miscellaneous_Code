
<?php
//
// ----------------------------------------------------------------------------
//
// Clans/Guilds Help File: guildclanhelp.php
// September 2004
// Version: 0.2
// Author: Gargamel
//
// This helpfile interacts with the incredible Guilds-Mod released by
// Dasher. I'll warmly give all credits to his work.
//
// I would apprechiate feedback/updates on how it works and changes you make.
// Gargamel
//
// Install instruction:
// - copy this file into your LOGD main folder.
//
// Contact Gargamel:
// eMail: gargamel@rabenthal.de or gargamel@silienta-logd.de
// Forum: Gargi at dragonprime
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
/*
update accounts set guildID = 0 where guildID > 1;
delete from lotbd_guilds where ID > 1;
delete from lotbd_guildranks where guildID > 1;
*/

require_once "common.php";

page_header("Gilden/Clans Hilfesystem");
output("`c`& ~ Gilden/Clans Hilfesystem ~ `c",true);

function trenner($oben=0,$unten=0) {
    for ($i=0;$i<$oben;$i++){
        output("`n");
    }
    output("`n`^`c~ ~~ ~~~ ~~ ~`c`0");
    for ($i=0;$i<$unten;$i++){
        output("`n");
    }
}

function antragsb() {
    output("`bAntragsbüro:`b`n
    Hier kannst Du Deinen Antrag auf Mitgliedschaft in einer Gilde oder einem Clan
    abgeben. Du kannst immer nur in `beiner Gruppe zur Zeit`b Mitglied sein. Du kannst
    also weder zur gleichen Zeit in mehreren Gilden oder Clans Mitglied sein, noch
    kannst Du gleichzeitig in einem Clan `bund`b einer Gilde Mitglied sein.`n
    Natürlich kannst Du aus einer Gruppe aus austreten, um Dich dann einer anderen
    Gruppe anzuschliessen oder selbst eine Gilde oder einen Clan zu gründen.`n
    Du kannst so viel Anträge auf Mitgliedschaft stellen, wie Du möchtest. Mitglied
    wirst Du erst, wenn die Clan- bzw. Gildenführung Deinem Antrag auch stattgegeben
    hat. Sollten nach einer Aufnahme noch andere Mitgliedsanträge von Dir existieren,
    werden diese automatisch gelöscht.");
}

function grundung() {
    output("`bGründungsbüro:`b`n
    Hier kannst Du entweder einen Clan gründen, oder eine Gilde gründen. Die Betonung
    liegt auf ODER. Beides geht nicht.`n
    Du als Gründer bist auch automatisch Mitglied. Es gelten also auch für Dich als
    Gründer die Hinweise unter \"Antragsbüro\": Nur eine Mitgliedschaft zur Zeit! Als
    Gründer eines Clans kannst du z.B. nicht in einer Gilde oder einem anderen Clan
    Mitglied sein.");
}

function auskunft() {
    output("`bAuskunftsbüro:`b`n
    Unter \"`bAlle Gilden/Clans`b\" bekommst Du eine Auflistung aller bestehender
    Gilden und Clans.`n
    Für den Punkt \"`bHilfe`b\" brauchst Du ja keine Hilfe, denn sonst wärst Du
    nicht hier.");
}

function sonstiges() {
    output("`bSonstiges:`b`n
    Der Punkt \"Zurück zum Dorf\" bedarf sicher keiner weiteren Erläuterung.");
}


switch ($HTTP_GET_VARS['id']) {
    // main entry page
    case 1:
    output("case 1 // main entry page`n");
    output("`nWillkommen bei den Gilden und Clans!`n`n
    Folgende Punkte stehen Dir hier zur Verfügung:`n`n`0");
    antragsb();
    trenner(1,1);
    grundung();
    trenner(1,1);
    auskunft();
    trenner(1,1);
    sonstiges();
/*
—Bewerber—
Mitgliedsantrag für r abgeben
—~—
—Auskunftsbüro—
Alle Gilden/Clans
Hilfe
—Sonstiges—
Zurück zum Hauptgebäude
Zurück zum Dorf
*/
    break;

    // superuser menue  100 - 199
    case 100:
    output("case 100 // superuser menue  100 - 199`n");
    break;
    
    // member menue 200 - 299
    case 200:
    output("case 200 // member menue 200 - 299`n");
    break;

    // information menue 300 - 399
    case 300:
    output("case 300 // information menue 300 - 399`n");
    break;

    // management menue 400 - 499
    case 400:
    output("case 400 // member menue 400 - 499`n");
    break;

    
    // without return code always back to mod main page
    default:
    output("default // main entry page`n");
    break;
}


// general return to calling point
if ($_GET[ret]==""){
    addnav("Zurück","guild.php");
}else{
    $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]);
    $return = substr($return,strrpos($return,"/")+1);
    addnav("Zurück",$_GET[ret]);
}

page_footer();
?>


