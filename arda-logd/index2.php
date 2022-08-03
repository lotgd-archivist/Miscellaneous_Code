<?php

// 09092004

require_once "common.php";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header("www.arda-logd.de");
output("`cWillkommen bei Legend of the Green Dragon, schamlos abgekupfert von Seth Able's Legend of the Red Dragon.`n");

output("`c<img src='images/monster.jpg' alt='' >`c`n",true);
output("`c`\$Leider müssen wir erst mal Fangen spielen. Mit unserer Herrin und allen ihren Verwandten. Daher können wir grade nicht mal eben die Welt tragen. Hoffentlich haben sie uns bald...`nBis dahin bitten wir um etwas Geduld. `c");

output("`c`2Version auf diesem Gameserver: `@{$logd_version} mit eigenen Erweiterungen`0`c");


clearnav();
addnav("Neu hier?");
addnav("Über LoGD","about.php");
addnav("F.A.Q.","petition.php?op=faq",false,true);
addnav("Charakter erstellen","create.php");
addnav("Das Spiel");
addnav("Liste der Kämpfer","list.php");
addnav("Tägliche News", "news.php");
addnav("Spieleinstellungen", "about.php?op=setup");
addnav("Passwort vergessen?","create.php?op=forgot");
addnav("Unser Spielforum","http://arda-logd.de/phpBB3/index.php",false,false,true);
addnav("Unsere Künstlerin");
addnav("Minayas Studio","http://www.minayas-studio.de/",false,false,true);
addnav("Minaya bei Deviantart","http://minaya86.deviantart.com/gallery/",false,false,true);
addnav("Die LoGD-Welt");
addnav("LoGD Netz","logdnet.php?op=list");
addnav("DragonPrime","http://www.dragonprime.net",false,false,true);
addnav("Rechtliches");
addnav("Impressum","impressum.php");

page_footer();
?> 