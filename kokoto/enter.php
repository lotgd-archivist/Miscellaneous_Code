<?php

/*
*Palisaden by Taraen (www.legend-green-dragon.de)
*Idee: Taraen
*Script: Taraen
*Mail: F.Tuska@web.de

*/
require_once "common.php";
addcommentary();
checkday();

if ($session['user']['namecheck']<=2){
  
page_header("Vor den Palisaden");
addnav('Navigation');
addnav("Log out","login.php?op=logout",true);
addnav('`bSonstiges`b');
addnav('Offtopic Bereich','village.php?op=chat');
addnav("Profil & Inventar","prefs.php");
addnav('Kämpferliste','list.php');
addnav('`bAvatar-Liste`b',"modellist.php?ret=enter.php");
output('`3Du stehst nun vor den Palisaden des Dorfes und kannst 2 Wachen sehen, die dich misstrauisch angucken. `#Halt! `3Sagt einer von ihnen und du denkst das es wohl besser ist, sich nicht mit ihnen anzulegen. `#Eure Papiere bitte! Wir werden euch im Dorf melden. Erst dann können wir euch herreinlassen... Es tut mir leid aber in letzter Zeit läuft hier so viel Abschaum herum, dass wir hier nicht jeden hereinlassen möchten...`3Enttäuscht wendest du dich ab. `n`n`n`4`bDie Admins oder Mods werden deine Daten so schnell wie möglich überprüfen. Erst dann kannst du passieren... `$`n`c...Weiterhin wäre es gut wenn du dir eine Bio machst in der Wartezeit am besten mit einem Avatar von Avatarbase.de denn diese dürft ihr ohne Rechtliche bedenken benutzen, es muss keine große sein aber ein kleiner Ansatz genügt schon, es wäre ratsam das aussehen und kleinere Details zu beschreiben und etwaige eigenheiten oder eigenschaften!(`^Solltest du nur leveln wollen, benachrichtige Tidus für einlass)`$`b`c`n`n`c`b`iSollten sie einen Popup Blocker anhaben bitte diese Seite für Popups freigeben da diverse Spielinhalte in einem Popup sein könnten! Es gibt keine Werbe Popups auf dieser Seite! (Wie z.b. die Regeln! diese sind zu Lesen und zu beachten!!)`i`b`c`n`n`n`n`n`%`@Hier kannst du dich mit einigen neulingen, die auch noch nicht weiter dürfen unterhalten:`n`n');


}else{
addnav('Navigation');
addnav('Zum Dorf zurück','village.php');
addnav('`bSonstiges`b');
addnav('T?Tägliche News','news.php');
addnav('I?Profil & Inventar','prefs.php');
addnav('Kämpferliste','list.php');
addnav('L?In die Felder (Logout)','login.php?op=logout',true);
page_header("Palisaden");
output('`PEin weiteres mal trittst du vor die mächtigen Palisaden des Dorfes, aber diesmal nicht weil du neu bist, sondern um dich hier mit den Neulingen zu unterhalten, außerhalb der Palisaden umgibt das Dorf fast vollständig ein dichter Wald, du schaust dich um und die wachen lächeln dir freundlich entgegen weil sie dich ja nun kennen.`n`n`n`n`n`%`@Hier kannst du dich mit den Neuankömmlingen und den anwesenden Unterhalten:`n`n');

}
viewcommentary("enter","`tAuf `$ Rollenspiel`t gerechte Kommentare Achten!",10);
page_footer();
?>