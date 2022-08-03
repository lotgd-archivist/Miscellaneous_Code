<?php
/* 
 * buff_tut.php
 * Version:   04.10.2004
 * Author:   bibir
 * Email:   logd_bibir@email.de
 * For:     http://logd.chaosonline.de
 * 
 *
 * Purpose: a tutorial for buffs
 *          could be included in mounts.php
 *
 in script: add
    addnav("Buff-Tutorial", "buff_tut.php",false,true);
 in common.php: search for
     $allownonnav
   add in array
     ,"buff_tut.php"=>true
 */

require_once "common.php";
popup_header("Buff-Tutorial");

output("<table border=1 cellpadding=2 cellspacing=1 align=center>",true);
output("<tr><td>im Editor(engl)</td><td>Beschreibung</td><td>Beispiel</td></tr>",true);
output("<tr><td colspan=\"3\"><b>Meldungen</b></td></tr>",true);
output("<tr><td>Buff name</td><td>Name des Buffs</td><td>Schutz des Einhorns</td></tr>",true);
output("<tr><td>Message each round</td><td>Meldung, die zu Beginn jeder Runde kommt, hat keine Auswirkung, zeigt dem User nur an, 
    dass der Buff gerade aktiv ist</td><td>{badguy} ist deprimiert und kann nicht so gut angreifen.</td></tr>",true);
output("<tr><td>Wear off message</td><td>Meldung, wenn der Buff verbraucht ist</td><td>Deine Maus verkrÃ¼melt sich irgendwo in deiner RÃ¼stung</td></tr>",true);
output("<tr><td>Effect Message</td><td>Meldung, wenn der Buff gerade wirkt</td><td>Deine Maus zwickt deinen Gegner</td></tr>",true);
output("<tr><td>Effect No Damage Message</td><td>Meldung, wenn der Buff keinen Schaden macht (oder beim Heilen, wenn man schon komplett gesund ist)</td>",true);
    output("<td>Du bist vÃ¶llig gesund</td></tr>",true);
output("<tr><td>Effect Fail Message</td><td>Meldung, wenn der Buff fehlschlÃ¤gt</td><td>Dein Hund kratzt sich</td></tr>",true);
output("<tr><td colspan=\"3\"><b>Effekte</b></td></tr>",true);
output("<tr><td>Rounds to last (from new day)</td><td>Runden, die der Buff andauert</td><td>10</td></tr>",true);
output("<tr><td>Player Atk mod</td><td>Faktor, mit dem der eigene Angriffswert multipliziert wird</td>
    <td>fÃ¼r 20% mehr Angriff: 1.2 </td></tr>",true);
output("<tr><td>Player Def mod</td><td>Faktor, mit dem die eigene Verteidigung multipliziert wird</td>
    <td>fÃ¼r 10% weniger Verteidigung: 0.9 </td></tr>",true);
output("<tr><td>Regen</td><td>Feste Regeneration</td><td>fÃ¼r jeweils 1hp: 1 </td></tr>",true);
output("<tr><td>Minion Count</td><td>Anzahl der 'Buffelemente'; wie bei Skelettkriegern (dunkle KÃ¼nste) greifen hier entsprechend 
     viele Buffs vor der Runde an (wird auch bei Voodoo und Erdenfaust genutzt). WÃ¤hrend die Ã¼brigen MÃ¶glichkeiten nur Kampfwerte 
     Ã¤ndern, greift Minion Count eigentstÃ¤ndig an.</td><td>bei einfachem Einsatz, wie bei Voodoo: 1</td></tr>",true);
output("<tr><td>Min Badguy Damage</td><td>Minimaler Schaden, der angerichtet wird - nur in Zusammenhang mit Minion Count!</td>
    <td>fÃ¼r 10 als Untergrenze: 10 </td></tr>",true);
output("<tr><td>Max Badguy Damage</td><td>Maximaler Schaden, der angerichtet wird - nur in Zusammenhang mit Minion Count!</td>
    <td>fÃ¼r 20 als Obergrenze: 10 </td></tr>",true);
output("<tr><td>Lifetap (multiplier)</td><td>Wenn der Buff aktiviert ist und man Schaden beim Gegner macht, wird der Schaden mit 
    diesem Faktor multipliziert und man selbst um soviel geheilt</td><td>beim Einhorn: 1</td></tr>",true);
output("<tr><td>Damage shield (multiplier)</td><td>Wenn man selbst getroffen wird, wird der Schaden mit diesem Faktor 
    multipliziert udn soviel dem Gegner abgezogen</td><td>bei Blitzaura: 2</td></tr>",true);
output("<tr><td>Badguy Damage mod (multiplier)</td><td>Faktor, mit dem der gegnerische Schaden multipliziert wird.</td><td>Geist verfluchen: 0.5</td></tr>",true);
output("<tr><td>Badguy Atk mod (multiplier)</td><td>Faktor, mit dem der gegnerische Angriffswert multipliziert wird.</td><td>Seele verdorren: 0</td></tr>",true);
output("<tr><td>Badguy Def mod (multiplier)</td><td>Faktor, mit dem die gegnerische Verteidigung multipliziert wird.</td><td>Seele verdorren: 0</td></tr>",true);
output("<tr><td colspan=\"3\"><b>Aktivierungen</b></td></tr>",true);
output("<tr><td>Round Start</td><td>Buff wird zu Rundenbeginn ausgefÃ¼hrt</td><td>Checkbox (Ja/Nein)</td></tr>",true);
output("<tr><td>On Attack</td><td>Buff wird beim eigenen Angriff ausgefÃ¼hrt</td><td>Checkbox (Ja/Nein)</td></tr>",true);
output("<tr><td>On Defend</td><td>Buff wird beim gegnerischen Angriff ausgefÃ¼hrt</td><td>Checkbox (Ja/Nein)</td></tr>",true);
output("</table>",true);

output("`n`nHinweise zum Activate:`n");
output("Es wird ausschlieÃŸlich die Anzeige der Meldung, das Anrechnen als \"Verbrauch\" sowie die Heilung Regen hierdurch beeinflusst.");
output("Angriffs- und Verteidigungsmods werden in jedem Fall bei eigenem Angriff und Verteidigung ausgefÃ¼hrt, ebenso Lifetaps.`n");
output("Beispiel Einhorn: Das steht auf \"defend\"`n");
output("D.h.:Badguy damage mod: 0.9 (d.h. Gegner greift nur mit 90% an) wird sowohl bei Angriff als auch bei Verteidigung ausgefÃ¼hrt, ebenso Lifetap 8steht auf 1, 
d.h. immer wenn man Schaden am Gegner macht, bekomt man diesen Schaden als LP, wenn die nicht schon voll sind.`n");
output("Allerdings: Die Runde wird dem Einhorn nur abgezogen zu Beginn der Verteidigungsrunde, die 2LP (Regen =2) gibt es auch nur zu Beginn der Verteidigungsrunde!");

popup_footer();
?>