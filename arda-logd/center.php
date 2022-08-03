<?php

/* ********************
Codierung von Ray
Ideen von Ray
ICQ: 230406044
******************** */
/* ************************ Informationen ****************************************
Folgende Dateien gehören zu diesen einkaufszentrum:
Center.php
barbier.php
clothes.php
fri.php
nagel.php
drushop.php

Einbauanleitung:
öffne newday.php und suche:
}else if ($session['user']['reputation']>=30){
                        if ($session['user']['reputation']>50) $session['user']['reputation']=50;
                        output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kämpfen.`n`n");
                        $session['user']['turns']++;
                        $session['user']['playerfights']++;
                }

setze darunter:
}if ($session['user']['schuhe']==1){
                        output("`n`9Durch deine Wanderstiefel erhählst du `^1 `9Waldkampf mehr.`n`n");
                        $session['user']['turns']++;
                }if ($session['user']['schuhe']==2){
                        output("`n`9Durch deine Sportschuhe erhählst du `^2 `9Waldkämpfe mehr.`n`n");
                        $session['user']['turns']+=2;
                }if ($session['user']['schuhe']==3){
                        output("`n`9Durch deine Lederstiefel erhählst du `^2 `9Waldkämpfe mehr.`n`n");
                        $session['user']['turns']+=3;
                }if ($session['user']['schuhe']==4){
                        output("`n`9Durch deine Drachen Stiefel erhählst du `^4 `9Waldkämpfe mehr.`n`n");
                        $session['user']['turns']+=4;
                }if ($session['user']['armband']==1){
                        if ($session['user']['ause']==1){
                        output("`nDurch dein Goldenes Armband erhählst du 1 Verteidigung mehr.`n`n");
                        $session['user']['defence']-=1;
                        $session['user']['defence']+=1;
                        }else{
                        output("`nDurch dein Goldenes Armband erhählst du 1 Verteidigung mehr.`n`n");
                        $session['user']['defence']+=1;
                        $session['user']['ause']=1;
                        }
                }if ($session['user']['armband']==2){
                        if ($session['user']['ause']==1){
                        output("`nDurch deine Drachen Armband erhählst du 2 Verteidigungspunkte mehr.`n`n");
                        $session['user']['defence']-=2;
                        $session['user']['defence']+=2;
                        }else{
                        output("`nDurch dein Drachen Armband erhählst du 2 Verteidigung mehr.`n`n");
                        $session['user']['defence']+=2;
                        $session['user']['ause']=1;
                        }
                }if ($session['user']['klamotten']==1){
                         if ($session['user']['kuse']==1){
                        output("`nDurch dein Weises Kleid erhählst du 1 Angriff mehr.`n`n");
                        $session['user']['attack']-=1;
                        $session['user']['attack']+=1;
                        }else{
                        output("`nDurch dein Weises Kleid erhählst du 1 Angriff mehr.`n`n");
                        $session['user']['attack']+=1;
                        $session['user']['kuse']=1;
                        }
                }if ($session['user']['klamotten']==2){
                        if ($session['user']['kuse']==1){
                        output("`nDurch dein Drachenleder Kleid erhählst du 2 Angriffe mehr.`n`n");
                        $session['user']['attack']-=2;
                        $session['user']['attack']+=2;
                        }else{
                        output("`nDurch dein Drachenleder Kleid erhählst du 2 Angriff mehr.`n`n");
                        $session['user']['attack']+=2;
                        $session['user']['kuse']=1;
                        }
                }if ($session['user']['klamotten']==3){
                        if ($session['user']['kuse']==1){
                        output("`nDurch dein Schwarzes Jacket erhählst du 1 Angriff mehr.`n`n");
                        $session['user']['attack']-=1;
                        $session['user']['attack']+=1;
                        }else{
                        output("`nDurch dein Schwarzes Jacket erhählst du 1 Angriff mehr.`n`n");
                        $session['user']['attack']+=1;
                        $session['user']['kuse']=1;
                        }
                }if ($session['user']['klamotten']==4){
                        if ($session['user']['kuse']==1){
                        output("`nDurch dein Drachenleder Jacket erhählst du 2 Angriffe mehr.`n`n");
                        $session['user']['attack']-=2;
                        $session['user']['attack']+=2;
                        }else{
                        output("`nDurch dein Drachenleder Jacket erhählst du 2 Angriff mehr.`n`n");
                        $session['user']['attack']+=2;
                        $session['user']['kuse']=1;
                        }
                }

öffne common.php und suche:
.templatereplace("statrow",array("title"=>"Edelsteine","value"=>$u['gems']))

setze darunter:
.templatereplace("stathead",array("title"=>"Ausrüstung"))
                .templatereplace("statrow",array("title"=>"Waffe","value"=>$u['weapon']))
                .templatereplace("statrow",array("title"=>"Rüstung","value"=>$u['armor']))
.templatereplace("statrow",array("title"=>"Klamotten","value"=>$kla[$u['klamotten']]))
.templatereplace("statrow",array("title"=>"Armband","value"=>$arm[$u['armband']]))
.templatereplace("statrow",array("title"=>"Schuhe","value"=>$shoes[$u['schuhe']]))
                .templatereplace("stathead",array("title"=>"Aussehen"))
                .templatereplace("statrow",array("title"=>"Haare","value"=>$hair[$u['frisur']]))
                .templatereplace("statrow",array("title"=>"Haar Farbe","value"=>$hairco[$u['hairco']]))



suche:
$def = ($def == $u[defence] ? "`^" : ($def > $u[defence] ? "`@" : "`$")) . "`b$def`b`0";


setze darunter:
$kla=array(0=>"Keine Klamotten",1=>"Weises Kleid",2=>"Drachenleder Kleid",3=>"Schwarzes Jacket",4=>"Drachenleder Jacket");

                $arm=array(0=>"Kein Armband",1=>"Goldenes Armband",2=>"Drachen Armband");

                $shoes=array(0=>"Keine Schuhe",1=>"Wanderschuhe",2=>"Sportschue",3=>"Lederstiefel",4=>"Drachen Stiefel");

                $hair=array(0=>"Glatze",1=>"Glatze",2=>"Glatze",3=>"Glatze",4=>"Glatze",5=>"Glatze",6=>"Glatze",7=>"Glatze",8=>"Glatze",9=>"Glatze",10=>"Kurze Haare",11=>"Kurze Haare",12=>"Kurze Haare",13=>"Kurze Haare",14=>"Kurze Haare",15=>"Kurze Haare",16=>"Kurze Haare",17=>"Kurze Haare",18=>"Kurze Haare",19=>"Kurze Haare",20=>"Normale Länge",21=>"Normale Länge",22=>"Normale Länge",23=>"Normale Länge",24=>"Normale Länge",25=>"Normale Länge",26=>"Normale Länge",27=>"Normale Länge",28=>"Normale Länge",29=>"Normale Länge",30=>"Normale Länge",31=>"Normale Länge",32=>"Normale Länge",33=>"Normale Länge",34=>"Normale Länge",35=>"Normale Länge",36=>"Normale Länge",37=>"Normale Länge",38=>"Normale Länge",39=>"Normale Länge",40=>"Lange Haare",41=>"Lange Haare",42=>"Lange Haare",43=>"Lange Haare",44=>"Lange Haare",45=>"Lange Haare",46=>"Lange Haare",47=>"Lange Haare",48=>"Lange Haare",49=>"Lange Haare",50=>"Lange Haare",51=>"Lange Haare",52=>"Lange Haare",53=>"Lange Haare",54=>"Lange Haare",55=>"Lange Haare",56=>"Lange Haare",57=>"Lange Haare",58=>"Lange Haare",59=>"Lange Haare",60=>"Lang und Ungeflegt",61=>"Lang und Ungeflegt",62=>"Lang und Ungeflegt",63=>"Lang und Ungeflegt",64=>"Lang und Ungeflegt",65=>"Lang und Ungeflegt",66=>"Lang und Ungeflegt",67=>"Lang und Ungeflegt",68=>"Lang und Ungeflegt",69=>"Lang und Ungeflegt",70=>"Lang und Ungeflegt",71=>"Lang und Ungeflegt",72=>"Lang und Ungeflegt",73=>"Lang und Ungeflegt",74=>"Lang und Ungeflegt",75=>"Lang und Ungeflegt",76=>"Lang und Ungeflegt",77=>"Lang und Ungeflegt",78=>"Lang und Ungeflegt",79=>"Lang und Ungeflegt",80=>"Lang und Ungeflegt");

                nagel=array(0=>"Kurz",1=>"Kurz",2=>"Kurz",3=>"Kurz",4=>"Kurz",5=>"Kurz",6=>"Kurz",7=>"Kurz",8=>"Kurz",9=>"Kurz",10=>"Normal",11=>"Normal",12=>"Normal",13=>"Normal",14=>"Normal",15=>"Normal",16=>"Normal",17=>"Normal",18=>"Normal",19=>"Normal",20=>"Gepflägt",21=>"Gepflägt",22=>"Gepflägt",23=>"Gepflägt",24=>"Gepflägt",25=>"Gepflägt",26=>"Gepflägt",27=>"Gepflägt",28=>"Gepflägt",29=>"Gepflägt",30=>"Gepflägt",31=>"Gepflägt",32=>"Gepflägt",33=>"Gepflägt",34=>"Gepflägt",35=>"Gepflägt",36=>"Gepflägt",37=>"Gepflägt",38=>"Gepflägt",39=>"Gepflägt",40=>"Ungeplfegt",41=>"Ungeplfegt",42=>"Ungeplfegt",43=>"Ungeplfegt",44=>"Ungeplfegt",45=>"Ungeplfegt",46=>"Ungeplfegt",47=>"Ungeplfegt",48=>"Ungeplfegt",49=>"Ungeplfegt",50=>"Ungeplfegt",51=>"Ungeplfegt",52=>"Ungeplfegt",53=>"Ungeplfegt",54=>"Ungeplfegt",55=>"Ungeplfegt",56=>"Ungeplfegt",57=>"Ungeplfegt",58=>"Ungeplfegt",59=>"Ungeplfegt",60=>"Ungeplfegt");

                nagelco=array(0=>"Kein",1=>"Schwarz",2=>"Gelb",3=>"Lila",4=>"Rot",5=>"Blau",6=>"Pink", 7=>"Klarlack");





öffne user.php und suche:
        "Grabkämpfe,title",
        "deathpower"=>"Gefallen bei Ramius,int",
        "gravefights"=>"Grabkämpfe übrig,int",
        "soulpoints"=>"Seelenpunkte (HP im Tod),int",

setze darunter:
"Aussehen,title",
"schuhe"=>"Schuhe,enum,0,Keine,1,Wanderschuhe,2,Sportschuhe,3,Lederstiefel,4,Drachenleder Stiefel",
        "armband"=>"Armband,enum,0,Kein,1,Goldenes Armband,2,Drachen Armband",
        "klamotten"=>"Klamotten,enum,0,Keine,1,Weises Kleid,2,Drachenleder Kleid,3,Schwarzes Jacket,4,Drachenleder Jacket",
        "frisur"=>"Frisur - 0=>Glatze 10=>Kurze Haare
        20=>Normale Länge `n40=>Lange Haare 60=>Lang und Ungeflegt,int",
        "hairco"=>"Haarfarbe,enum,0,Braun,1,Schwarz,2,Blond,3,Grün,4,Pink,5,Rot,6,Blau",
        "nagel"=>"Nägel - 0=>Kurze Nägel 10=>Normale Nägel `n20=>Gepflägte Nägel 40=>Ungeplfegt,int",
        "nagelco"=>"Nagellack,enum,0,Kein,1,Schwarz,2,Gelb,3,Lila,4,Rot,5,Blau,6,Pink, 7,Klarlack",



öffne newday.php und suche:
$session['user']['bounties']=0;

setze daruntern:
if ($session['user']['frisur']<=80){
                $session['user']['frisur']+=1;
                }else{
                $session['user']['frisur']+=0;
                }
if ($session['user']['nagel']<=50){
                $session['user']['nagel']+=1;
                }else{
                $session['user']['nagel']+=0;
                }






Damitt es nach DK nicht verschwindet
!!! achtung 2 mal !!!
---öffne dragon.php:----

suche:
,"reputation"=>1

und fürge danach ein:
,"armband"=>1
,"klamotten"=>1
,"schuhe"=>1
,"blaukraut"=>1
,"rotkraut"=>1
,"gelbkraut"=>1
,"kraut"=>1
,"frisur"=>1
,"hairco"=>1
,"nagel"=>1
,"nagelco"=>1

---save&close----
*********************************************************************************/

/* ************************ Datenbank Anweisung **********************************
ALTER TABLE `accounts` ADD `schuhe` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `armband` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `klamotten` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `kuse` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `ause` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `blaukraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `rotkraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `gelbkraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `kraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `frisur` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `hairco` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `nagel` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `nagelco` INT (12) DEFAULT '0' NOT NULL;
*********************************************************************************/

require_once "common.php";
checkday ();
addcommentary ();
page_header ("Marktplatz");


output("`c`b`#Marktplatz`c`b`n`n");
output("`c<img src='images/symiamarkt.jpg' alt='' >`c`n",true);
output("`c`w Deine Schritte führen Dich von der Landstraße auf den Marktplatz von Symia, das erste was Du siehst ist die zauberhafte Schönheit dieser Elfenstadt.`n
`W Leise erklingen hier und da melodische Stimmen, die an Deinen Ohren wie kleine Symphonien klingen. `n
`e Fasziniert von dieser Schönheit lenkst du deine Schritte weiter auf dem Marktplatz, um dich noch ein wenig mehr umsehen zu können.`n
`$ Erst dort fällt dir auf das die grazile und fast schon unmenschliche Schönheit des Orts durch etwas gestört wird.`n
`E Denn hier und da kann man die Erscheinung von etwas ganz anderem sehen...`n
`Q Im ersten Moment runzelt Du ein wenig die Stirn über dieses, doch dann beim genauer hinsehen bemerkst du das die zwergischen Händler, die Abenteurer `n 
`q Was sich noch so zwischen den Marktständen tummelt doch ins Gesamtbild einfügte...`n 
`^es eher noch vervollständigte.`n
`Z Immer noch gefesselt von dem Anblick bleibst du am südlichen Rand des Marktplatz stehen um den treiben eine Weile zu zuschauen.`n 
`8So bemerkst Du auch im ersten Augenblick gar nicht wie mit einem Mal ein Lachen erklingt... nicht böse oder gar schaurig, sondern warm und fröhlich! `n
`&Es erfüllt Dein Herz mit einer Wärme die Dich an Zuhause erinnert.`n
`7Suchend blicken sich deine Augen um...als ...ja als Du bemerkst das von Norden her eine Art Nebel aufzieht,`n 
`ò der den Marktplatz mit all seinen Farben in etwas hüllt das beinahe grau-weiß erscheint.`n
`G In der Hoffnung das Dir vielleicht ein festes Augen zusammenkneifen die Farben wiederbringt schließt Du diese.`n
`R Doch beim wieder öffnen ist bereits die Hälfte des Marktplatz in einem schwarz-weiß versunken …`n nur der Wind lässt ein paar zarte `r rosa `R Blütenblätter herum wirbeln....was geschieht hier bloß?`n  Gute Frage....`n 
am besten gehst Du ein mal nachfragen bei der `êG`rö`&t`7t`Gi`Rn der F`Ga`7r`&b`re`ên..... `c   `n`n`n");
/*if ($session['user']['job']==2){
output("`nEinige Druiden gehen in den Läden umher und besorgen ihre Kräuter");
}*/ //Auskommentiert, da für anderes Jobsystem


output("Der Marktplatz ist einer der beliebtesten Orte in dem Ort, man hört überall Leute über den Dorfmarkt Reden:`n`n");
viewcommentary("Markteinkauf","Hinzufügen",15);



addnav("Läden");
addnav("Kleidungsshop","clothes.php");
addnav("Friseur","fri.php");
addnav("Nagelstudio","nagel.php");
addnav("Barbier","barbier.php");
//if ($session['user']['job']==2) addnav("Kräuterladen","drushop.php");  //Auskommentiert, da für anderes Jobsystem
addnav("Sonstiges");
addnav("Zurück ins Dorf","sanela.php");


page_footer ();
?> 