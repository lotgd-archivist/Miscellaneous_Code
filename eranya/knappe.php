
<?php
require_once('common.php');
page_header();

output('Knappen-Testskript - wohin soll\'s gehen?`n
        `n
        - Zu <a href="knappentraining.php?npc=akademie">Zerindos Akademie (heilkundiger Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=yaris">Yaris\' Waffenladen (geschickter Knappe)</a>`n`n
        - Zum <a href="knappentraining.php?npc=v_ship">Handelsschiff (kräftiger Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=ophelia">Ophelía (hübscher Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=petersen">Petersen (stolzer Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=riyad">Riyad (vorlauter Knappe)</a>`n`n
        - Zu <a href="knappentraining.php?npc=aeris">Aeris (verträumter Knappe)</a> *in Arbeit*`n`n
        - Zur <a href="knappentraining.php?npc=lib">Bibliothek (neunmalkluger Knappe)</a> *fertig*`n`n
        - Zum <a href="knappentraining.php?npc=baker">Bäcker (dicklicher Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=marek">Marek (nichtsnutziger Knappe)</a> *fast fertig, Farben fehlen*`n`n
        - Zu <a href="knappentraining.php?npc=thoya">Thoyas Rüstungsladen (treuer Knappe)</a> *fertig*`n`n
        - Zu <a href="knappentraining.php?npc=silas">Silas (hinterhältiger Knappe)</a>`n`n
        - Zu <a href="knappentraining.php?npc=scytha">Scytha (listiger Knappe)</a>`n`n
        - Zu <a href="knappentraining.php?npc=train">Bryden (flinker Knappe)</a> *fertig*`n`n');
addnav('','knappentraining.php?npc=akademie');
addnav('','knappentraining.php?npc=yaris');
addnav('','knappentraining.php?npc=v_ship');
addnav('','knappentraining.php?npc=ophelia');
addnav('','knappentraining.php?npc=petersen');
addnav('','knappentraining.php?npc=riyad');
addnav('','knappentraining.php?npc=aeris');
addnav('','knappentraining.php?npc=lib');
addnav('','knappentraining.php?npc=baker');
addnav('','knappentraining.php?npc=marek');
addnav('','knappentraining.php?npc=thoya');
addnav('','knappentraining.php?npc=silas');
addnav('','knappentraining.php?npc=scytha');
addnav('','knappentraining.php?npc=train');
addnav('Zurück');
addnav('Zum Stadtplatz','village.php');

page_footer();
?>

