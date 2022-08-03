<?php
/************************/
/*   strand.php         */
/* Copyright by Hadriel */
/* Made by Hadriel      */
/*                      */
/* Fix 1 by Gargamel    */
/* @ silienta-logd.de   */
/*                      */
/* Fix 2 by Hadriel     */
/* @ hadrielnet.ch      */
/*                      */
/************************/
/*
History °*Amerilion*° @ greenmano@gmx.de
21.30 Angeschaut... Meer an meinen Dorf... nöööö
21.40 Transforming in huegel.php
22.10 Fertig, start des Bugfixings ^^
22.20 Dämliche Umlaute ^^ ansonten ging alles ^^

//Sanela-Pack Version 1.1
*/

require_once "common.php";

//Belohnung im Stollen
$goldschatz = e_rand($session[user][level]*200,$session[user][level]*500);

page_header("Der Hügel");
$session['user']['sanela']=unserialize($session['user']['sanela']);
output("`c`b`2Der Hügel`c`b`n`n");
if ($_GET[op]==""){
        output("`2Du betrittst einen kleinen Hügel, welcher ein Stück ausserhalb des Dorfes liegt.");
        output("Er wird gerne von den Abenteueren als Ausflugsort und zum Picknicken benutzt.");
        output("Ausserdem befindet sich eine grosse Runde alte Tür hinter der ein Stollen liegt");
        output("an dem Fusse des Hügels. Der nahe `1See`2 sieht von hier aus noch schöner aus.");
        output("Hier unterhalten sich einige Abenteurer.`n");
        addcommentary();
        viewcommentary("huegel","Sprechen",15,"spricht");

        addnav("Spazieren","huegel.php?op=spazieren");
        if ( $session['user']['turns']>0 && $session['user']['sanela']['huegel']<1) {
                addnav("Picknicken","huegel.php?op=essen");
        }
        if ($session['user']['sanela']['huegel']<1) {
                addnav("Die alte Tür","huegel.php?op=stollen");
        }
        addnav("Zum See","sanelasee.php");
}

if ($_GET[op]=="spazieren"){
        output("`2Du schlenderst ein wenig über den Hügel, genießt die Aussicht auf das Dorf und den See.");
        output("Nach ein paar Schritten bemerkst du einen Weg zur `7Turmruine`2.");
        addnav("Zum Turm","huegel.php?op=turm");
        addnav("Weiter","huegel.php?op=weiter");
        addnav("Zurück","huegel.php");
}
if ($_GET[op]=="turm"){
        output("Du kommst zu einer wunderschönen abseits gelegenen Turmruine. Der Ort hier scheint sehr romantisch, aber auch etwas unheimlich.`n");
        addcommentary();
        viewcommentary("huegelturm","Sprechen",15,"spricht");
        addnav("zurück zum Hügel","huegel.php");
}

        if ($_GET['op']=="weiter"){
        output("`2Du gehst in Richtung Waldrand, wo du meinst etwas erkennen zu können.");
        addnav("Zum Waldrand","wanderweg.php");
        addnav("Zurück zum Hügel","huegel.php");
}
if ($_GET[op]=="essen"){
        output("`2Du nimmst dir ");
        switch(e_rand(1,5)){
                case 1:output("einen Schinken");break;
                case 2:output("eine Scheibe Brot");break;
                case 3:output("einen Apfel");break;
                case 4:output("eine gebratene Schweinskeule");break;
                case 5:output("eine Stück Kuchen");break;
        }
        output(".`n`n");
        $session['user']['sanela']['huegel']=1;
        switch(e_rand(1,10)){
                case 1:
                output("Das hat gut geschmeckt! Du bekommst 3 Waldkämpfe!");
                $session['user']['turns']+=3;
                addnav("Zurück","huegel.php");
                break;
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                output("Das Essen schmeckt wie immer!");
                addnav("Zurück","huegel.php");
                break;
                case 9:
                output("Anscheind war deine Mahlzeit verdorben, du bekommst Magenkrämpfe und");
                output("dir wird schwarz vor Augen.`n`n`^Du bist tot.`nDu verlierst 3% deiner Erfahrung und all dein Gold");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['gold']=0;
                $session['user']['experience']*=0.97;
                addnav("Tägliche News","news.php");
                addnews($session['user']['name']."`2 hat etwas verdorbenes gegessen.");
                break;
                case 10:
                if ( $session['user']['hitpoints'] > 5 ) {
                        $session['user']['hitpoints']-=5;
                        output("Bäh, das schmeckt widerlich! Du verlierst 5 LP!");
                }
                elseif ( $session['user']['hitpoints'] == 1 ) {
                        output("Bäh, das schmeckt widerlich!");
                } else {
                        $session['user']['hitpoints']=1;
                        output("Bäh, das schmeckt widerlich! Du verlierst fast alle LP!");
                }
                addnav("Zurück","huegel.php");
                break;
        }
}
if ($_GET['op']=="stollen"){
        output("`2Auf der Suche nach Reichtum öffnest du die Tür und betrittst den Stollen.");
        output("Du zündest dir eine Fackel an, von denen einige am Eingang bereit liegen, und gehst tiefer hinein.`n`n");
        $session['user']['sanela']['huegel']=1;
        switch(e_rand(10,18)){
                case 10:
                output("Du findest einen schönen Kristall, den du gleich verkaufst. Du bekommst 2 Edelsteine!");
                $session['user']['gems']+=2;
                addnav("Zurück","huegel.php");
                break;
                case 11:
                case 12:
                case 13:
                case 14:
                output("Du findest leider nichts.");
                addnav("Zurück","huegel.php");
                break;
                case 15:
                output("Du findest einen `b `&RIESIGEN KRISTALL!!!`b `2Du bekommst beim Händler 4 Edelsteine und 1000 Gold dafür!!!");
                addnav("Zurück","huegel.php");
                $session[user][gold]+=1000;
                $session[user][gems]+=4;
                break;
                case 16:
                output("Du gehst den dunklen Stollen entlang und lehnst dich erschöpft an eine Wand.");
                output("Mit einem Rumpeln brechen einige Felsen heraus. Du springst zur Seite und");
                output("entgehst ihnen so. Als sich der Staub gelegt hatt bemerkst du ein kleines");
                output("Loch. Du könntest dich vielleicht durchzwängen, oder aber die glitzernden");
                output("Felsbrocken durchsuchen.");
                addnav("Durchzwängen","huegel.php?op=hoehle");
                addnav("Felsen durchsuchen","huegel.php?op=absuchen");
                break;
                case 17:
                output("Voller Hoffnung auf Reichtum gehst du hinein. Allerdings erlischt deine");
                output("Fackel, du bekommst Angst und rennst schreiend aus dem Stollen. Alle");
                output("anderen Abenteurer lachen über dich!`n`n`^Du verlierst 2 Charmpunkte.");
                $session['user']['charm']-=2;
               // $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",'/me `@kommt aus Richtung Hügel gerannt und verschwindet, laut nach `4Mama`@ rufend im Wohnviertel...')";
               // db_query($sql) or die(db_error(LINK));
                addnav("Weiter","houses.php");
                break;
                case 18:
                output("Auf der Suche nach Kostbarkeiten verausgabst du dich völlig und atmest");
                output("erst mal erschöpft durch, nachdem du aus dem Stollen gekommen bist.");
                output("`n`n`^Du verlierst 3 Runden.");
                $session['user']['turns']-=3;
                addnav("Zurück","huegel.php");
                break;
        }
}
if ($_GET['op']=="hoehle"){
        output("`2Du zwängst dich durch das Loch, ");
        switch(e_rand(19,20)){
                case 19:
                output("und bemerkst nicht, dass die Felsen wieder zu rutschen anfangen.");
                output("Sie zerquetschen dich ohne jeden Kompromiss.`n`n`^Du bist tot!`nDu verlierst 5% deiner Erfahrung.");
                output("`nDu verlierst all dein Gold.");
                $session['user']['alive']=false;
                $session['user']['gold']=0;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.95;
                addnav("Tägliche News","news.php");
                addnews($session['user']['name']." `2wurde zimlich geplättet.");
                break;
                case 20:
                output(" und findest eine kleine Höhle. Du siehst einige vermoderte Kisten, welche");
                output("du eilig mit einem Fusstritt öffnest. Du endeckst ".$goldschatz." Goldmünzen!");
                addnews($session['user']['name']." `2fand einen Schatz von ".$goldschatz." Goldmünzen in einem alten Stollen.");
                $session['user']['gold']+=$goldschatz;
                addnav("Zurück","huegel.php");
                break;
        }
}
if ($_GET['op']=="absuchen"){
        output("`2Du findest Nuggets im Wert von 250 Gold, willst weitersuchen und nimmst einen");
        output("Felsbrocken zur Seite. Dieser hielt aber die anderen, welche nun nachrutschen.");
        output("Du springst grade noch zur Seite, und rennst diesmal eilig aus den Stollen, da du");
        output("dein Glück nicht nocheinmal herrausforden willst");
        $session['user']['gold']+=250;
        addnav("Zurück","huegel.php");
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?> 