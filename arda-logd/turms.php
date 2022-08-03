<?php

/*

   Funktionsweise:

   Jeder Raum hat einen anderen Effekt.
   Heißt soviel wie: In einem Raum bekommt man z.b. Gold, LP, Charm, was auch immer
   Allerdings kann man manche Räume erst betreten, wenn man eine
   bestimmte Anzahl an Dks erreicht hat. (Sonst wäre es ja zu einfach ;D)
   Natürlich kann man auch Gold, Charm etc oder sogar sein Leben verlieren^^
   * HINWEIS: if ($session['user']['sueturm']>=1) <- sorgt dafür das man es nur 1x pro Tag betreten kann!
   Ist auch wichtig, da das Script sonst zu leicht ausnutzbar ist^^

   Für die die es sich einbauenn wollen, bitte mir bescheid sagen, wäre lieb ;)

   ***Kästchen bitte stehen lassen:***
   ------------------
   Idee: Lunastra - Diana G.
   Inspiration: Turm der Elemente
   für LogD Lunaria erstellt - http://lunari.vs120154.hl-users.com
   ------------------
              *** Danke :) ***


    ***Einbau:***

    ~irgendwo, da wo ihr es hinhaben wollt~

    addnav("Turm der 7 Sünden","turm7suenden.php");

    einfügen

    ~newday.php :~

    $session['user']['sueturm']=0;

    einfügen

    SQL:
    ALTER TABLE `accounts` ADD `sueturm` INT(2) NOT NULL DEFAULT '0'

*/

require_once "common.php";
addcommentary();
checkday();


page_header("Turm der 7 Sünden");
//$session['user']['standort']= "`~Turm der 7 Sünden`0";



if(empty($_GET['op']))
{
if ($session['user']['sueturm']>=1){
output ("`4Für heute hast du wohl genug! Komm morgen wieder!");
addnav("Zurück","zylyma.php");
}else{

        //Seitenkoerper
        output("`c<img src='http://i25.tinypic.com/16gn56x.png'>`c`n`n",true); // Bildlink austauschen, wenn ihr meins nicht wollt ;)
        output("`c`bTurm der 7 Sünden`b`c`n`n`0`cDu betrittst den Turm und erblickst eine riesige Wendeltreppe,`n die schier unendlich weit nach oben führt.`nAuf jeder Etage ist eine Tür, die in ein Zimmer zu führen scheint.`n Verschiedene Mächte verbieten dir den Eintritt in manche Zimmer. `nEs scheint, als müsstest du bei diesen Zimmern`n erst eine Gewisse Stärke erreicht haben um eintreten zu dürfen.`n`n`n`c");
        //Chat
        viewcommentary("turm7suenden","`nStimmen schallen",15);



        //Navigation
        addnav("Eingang");
        addnav("Buch der Sünden","turm7suenden.php?op=buch7");
        addnav("Turmzimmer");
$var = array(1  => 'acedia',
             10 => 'invidia',
             15 => 'gula',                //<= Danke Kevz! :3
             20 => 'ira',
             25 => 'luxuria',
             50 => 'avaritia'
       );
foreach ($var AS $dragonkills => $room)
  if($session['user']['dragonkills'] >= $dragonkills)
    {addnav('Raum der '.ucfirst($room), 'turm7suenden.php?op='.$room);}
        /*if($session['user']['reputation']>=50){*/addnav("Raum der Superbia","turm7suenden.php?op=superbia");}
        addnav("Verschwinden");
        addnav("Schnell Raus rennen","zylyma.php");
}

//Das Buch
else if($_GET['op'] == 'buch7'){
        page_header("Buch der 7 Sünden");
        output("`c`b`iBuch der 7 Sünden`c`b`i`n`n");
        output("`c`b`vWillkommen Reisender,`b`n
                `aDu befindest dich hier im Turm der hohen 7 Sünden.`n
                Jede von ihnen wartet in einem ihrer Zimmer.`n
                Doch so leicht kommst du in ihre Zimmer nicht herein.`n
                Es gibt keinen Zaubertrick und auch Gewalt wird nicht helfen.`n
                Du selbst musst es dir verdienen, im Kampf gegen den Phoenix.`n
                `n`n

                `i`hDie Sünden sind sieben mächtige Frauen,`n
                Jede hat ein anderes Gemüt, ein anderes Problem`n
                Und eine andere spezielle Eigenschaft.`i`n
                `n`n

                `ÉDie Sünde Arcedia, Sünde, welche die Trägheit des Herzens`n
                Widerspiegeln soll. Um zu ihr zu gelangen musst du `n
                Den Phoenix zumindest ein einziges `n
                Mal geschlagen haben.`n`n

                Die Sünde Invidia, Sünde, welche Neid, Missgunst und `n
                Eifersucht verkörpert. Um in ihre Gemächer `n
                Zu gelangen musst du den Phoenix `n
                Mindestens 10 mal erschlagen haben.`n`n

                Sünde Gula, die gefräßige und maßlose Sünde.`n
                Sie hat jedoch ein Problem, welches das ist,`n
                Wirst du nur erfahren, wenn der Phoenix`n
                Mindestens 20 mal durch deine Hand`n
                Zu Grunde gegangen ist.`n`n

                `7Du bemerkst das ab den nächsten Zeilen die Schrift nur noch`n
                Teilweise lesbar ist. Das liegt wohl am Alter des Buches.
                `n`n

                `ÉDie Sünde Ira, eine von Rachsucht zerfressene`n
                Frau, welcher der Zorn ins Gesicht `n
                Geschrieben steht. Um zu ihr zu`n
                Gelangen, wenn du das wirklich willst, musst du`n
                .....`n`n

                Die Sünde der Wolllust, Luxuria. Sie ist ....`n
                Dennoch sollte man aufpassen, wird`n
                Sie sehr schnell...., wenn man....`n
                Zu ihr gelangst du erst mit`n
                .....`n`n

                Durch Geiz und .... zeichnet sich die Sünde`n
                ..... aus. Sie liebt Schmuck und Juwelen.`n
                Auch hat sie keine Scheu diese.....`n
                Wenn du jedoch zu ihr willst`n
                Musst du den Phoenix schon mindestens 70`n
                Mal getötet haben.`n`n

                Die letzte Sünde nennt sich Su...p...ia.`n
                Sie ist frech, fies und fast so teuflisch wie der `n
                Teufel selbst. Man sollte sich nicht.... und `n
                Lieber gleich das weite suchen, wenn`n
                Man an seinem Leben hängt.`n`n`n

                `7Mit den letzten Sätzen werden die Worte wieder deutlicher. `n`n

                `aNun Reisender bist du über die Sünden informiert.`n
                Erwecke ihren Zorn und sie werden dich hassen`n
                Und dich Strafen, sei gut zu ihnen`n
                Oder erfülle ihre Wünsche, und sie werden`n
                Dich auch dafür belohnen.
                `c");

        addnav("Zurück","turm7suenden.php");
        page_footer();
}

//Die Sünden
else if($_GET['op'] == 'acedia'){
        page_header("Sünde Arcedia");
        $session['user']['sueturm']=1;
        output("`c`b`i`tSünde Arcedia`c`b`i`n`n");
        output("`c`tDu betrittst den ersten Raum der Sünden.`nDie Wände sind in einem warmen gelborangenen Ton`n
                Gestrichen und überall stehen Körbe mit `nFrüchten aufgestellt. Auf einem Sofa,`n
                In der Mitte des Raumes, liegt eine leicht pummelige`nFrau, welche dich freundlich anlächelt.`c");


        addnav("Was willst du tun?");
    addnav("Sie lieber in Ruhe Lassen","turm7suenden.php?op=ruhe");
    addnav("Ihr was zu Essen geben","turm7suenden.php?op=essen");
    addnav("Sie Ignorieren","turm7suenden.php?op=igno");

        page_footer();
}else if($_GET['op'] == 'invidia'){
        page_header("Sünde Invidia");
        $session['user']['sueturm']=1;
        output("`c`b`i`9Sünde Invidia`c`b`i`n`n");
        output("`9`cDer zweite Raum öffnet sich.`n
              Hier herrschen Kälte und Neid. In Mitten`nDes Raumes, sitzt eine weitere schöne Frau.`nSie scheint zu grübeln und regt sich gelegentlich auf`n
              Du hörst sie nur etwas leises murmeln:`n'Diese verdammte Superbia... warum bin ich nicht`n
              so beliebt wie sie? Was mach ich denn falsch?!'`c");

        addnav("Was willst du tun?");
    addnav("Gehen ohne etwas zu sagen","turm7suenden.php?op=geh");
    addnav("Sie in den Arm nehmen","turm7suenden.php?op=arm");
    addnav("Ihr zuhören","turm7suenden.php?op=hoer");

        page_footer();
}else if($_GET['op'] == 'gula'){
        page_header("Sünde Gula");
        $session['user']['sueturm']=1;
        output("`c`b`i`gSünde Gula`c`b`i`n`n");
        output("`g`cDer dritte Raum erstrahlt in einem frischen`nGrün. In mitten des Raumes, ist ein Großer Berg`n
            Aus Kissen, welche eine Art abstraktes Sofa`nBilden. In diesem Kissenberg ragen zwei Beine`n
            Empor, die wohl zur dritten Frau gehören.`n‚Hallo? Ist da wer? Zieh mich bitte raus!!'`nklagt sie. Du musst dich nun entscheiden.`c");

        addnav("Was willst du tun?");
    addnav("Schnell verschwinden","turm7suenden.php?op=schwind");
    addnav("Ihr helfen","turm7suenden.php?op=hilfe");
    addnav("Ihr zuschauen","turm7suenden.php?op=schauen");

        page_footer();
}else if($_GET['op'] == 'ira'){
        page_header("Sünde Ira");
        $session['user']['sueturm']=1;
        output("`c`b`i`QSünde Ira`c`b`i`n`n");
        output("`Q`cDu kommst in den vierten Raum,`n
            In dem du sofort von lautem Geschrei empfangen`nWirst. Eine Frau rennt verärgerten Gesichtsausdrucks`n
            Hin und her, flucht herum und meckert.`n'Ich wollte die Erste sein, deren Raum man betritt!!`n
            MIR sollte diese Ehre zu Teil werden!! Tz...`nAcedia, dir wird ich's schon noch heimzahlen!'`c");
        addnav("Was willst du tun?");

        addnav("Ihr einen Plan vorschlagen","turm7suenden.php?op=plan");
    addnav("Ihr die Rache ausreden","turm7suenden.php?op=rache");
    addnav("Sie in meckern lassen","turm7suenden.php?op=meckern");

        page_footer();
}else if($_GET['op'] == 'luxuria'){
        page_header("Sünde Luxuria");
        $session['user']['sueturm']=1;
        output("`c`b`i`rSünde Luxuria`c`b`i`n`n");
        output("`r`cNun kommst du in den fünften Raum.`nEr ist in einem hellen rosa gestrichen, was dich`n
                Darauf schließen lässt, hier wieder einer Frau`nZu begegnen. Und tatsächlich.`n
                Unter ein paar Schleiern, auf einem Bett mit `nSatinbettwäsche, liegt eine Bildschöne Frau`nSie lächelt verführerisch und winkt dich heran.`c");
        addnav("Was willst du tun?");

        addnav("Wegrennen!","turm7suenden.php?op=renn");
    addnav("Deinen Körper anbieten","turm7suenden.php?op=koerper");
    addnav("Sie verführen","turm7suenden.php?op=verfuehren");

        page_footer();
}else if($_GET['op'] == 'avaritia'){
        page_header("Sünde Avaritia");
        $session['user']['sueturm']=1;
        output("`c`b`i`^Sünde Avaritia`c`b`i`n`n");
        output("`^`cDer sechste Raum, lässt dich große Augen machen`nDie Wände bestehen aus purem Gold und die`n
                Wände sind mit den verschiedensten Edelsteinen`nVerziert. Alles erstrahlt und glitzert.`n
                Eine Frau in goldener Kleidung, ihr Gesicht`nVon einem Schleier, der mit Edelsteinen bestückt ist,`n
                Verdeckt. Trotz des Schleiers erkennst du`nIhr verschmitztes grinsen, du bemerkst gleich, dass`nDu lieber vorsichtig sein solltest.`c");
        addnav("Was willst du tun?");

        addnav("Ihr Gold geben","turm7suenden.php?op=gold");
    addnav("Ihr Edelsteine geben","turm7suenden.php?op=gems");
    addnav("Unbemerkt verschwinden","turm7suenden.php?op=unbemerkt");

        page_footer();
}else if($_GET['op'] == 'superbia'){
        page_header("Sünde Superbia");
        $session['user']['sueturm']=1;
        output("`c`b`i`~Sünde Superbia`c`b`i`n`n");
        output("`R`cEndlich erreichst du den letzten Raum.`nDer Raum der Superbia. Eine wunderschöne Frau,`n
                Mit langen schwarzen Haaren und feuerroten Augen`nErwartet dich bereits. Sie grinst und winkt dich herein.`n
                Du trittst näher und betrachtest den Raum.`nEs ist unheimlich duster, nur Kerzen spenden ein`n
                Wenig Licht. ‚Hallo, ".($session['user']['sex']?"meine Liebe":"mein Lieber")." Was kann`nIch denn für dich tun?' fragt, mit verführerischer Stimme.`n
                Diese Frau, scheint gern mit den Lüsten`nZu spielen, sodass es auch dir sehr schwer fällt`nDich zurück zu halten. Und? Was wirst du nun tun?`c ");
        addnav("Was willst du tun?");

        addnav("Panisch davonrennen","turm7suenden.php?op=panik");
    addnav("Ihr dein Leben anbieten","turm7suenden.php?op=leben");
    addnav("Sie töten","turm7suenden.php?op=tot");

        page_footer();
    }

    //optionen

        //Acedia
    if($_GET['op'] == 'ruhe'){
    output("Du beschließt die Dame bei ihrem Mahl nicht zu stöhren und verschwindest.`n
            Ein Gefühl von Müdigkeit überkommt dich, woraufhin du hinfällst und einschläfst.`n
            Dir wurden 20 Waldkämpfe geklaut!");
    addnav("Zurück","turm7suenden.php");
      if ($session['user']['turns']>20){
                          $session['user']['turns']-=20;
          }else{
      $session ['user']['turns']=0; }
    }else if($_GET['op'] == 'essen'){
    output("Die Frau scheint trotz ihrer Figur einen unsättlichen Appetit zu haben.`n
            Du legst ihr ein paar Äpfel und etwas Brot, Wurst und Käse hin, dazu noch etwas Wein und Milch.`n
            Die Dame lächelt dich freundlich an. Deine Lust noch etwas im Wald nach Monstern zu jagen`n
            steigt ganz Plötzlich. \"Das ist meine Art mich zu bedanken\" meint sie noch zu dir.");
      $session ['user']['turns']+=20;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'igno'){
    output("Du ignorierst das gefräßige Weib und gehst wieder.`n
            Sie sieht dich zornig an. Ein gefühl von Schwäche überkommt dich.
            heute wirst du wohl nicht mehr kämpfen können!");
      $session['user']['turns']=0;
    addnav("Zurück","turm7suenden.php");

        //Invidia
    }else if($_GET['op'] == 'geh'){
    output("Du gehst ohne groß Worte zu verlieren.`n
            Sowas willst du dir nun wirklich nicht antun.`n
            Plötzlich bekommst du éinen Schuh an den Kopf geworfen. \"Männer! Grr\" hörst du sie fluchen
            Du verlierst ein paar Lebenspunkte.");
      if ($session ['user']['hitpoints']>5){
                          $session ['user']['hitpoints']-=5;
                        }else{
      $session['user']['hitpoints']=0;}
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'arm'){
     output("Du nickst verstehend auf ihre Worte und gehst auf sie zu.`n
             Du beschließt sie einfach mal in den Arm zu nehmen.`n
             \"Sehr lieb von dir...\" murmelt sie. Du spürst wie deine Kraft steigt.
             Du hast Lebenspunkte geschenkt bekommen.");
      $session['user']['maxhitpoints']+=5;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'hoer'){
    output("Du schenkst ihr ein wenig deiner zeit und setzt dich zu ihr.`n
            Aufmerksam schenkst du ihren Worten Gehör. Als sie endlich zum Ende gekommen ist`n
            lächelt sie dich zufrieden an. \"Jetzt kennst du mein leiden, danke.\" sagt sie.`n
            Du fühlst dich gut, das du ihr helfen konntest, vertrödelst aber ein paar Runden.");
      if ($session['user']['turns']>10){
                          $session ['user']['turns']-=10;
                        }else{
      $session['user']['turns']=0; }
    addnav("Zurück","turm7suenden.php");

        //Gula
    }else if($_GET['op'] == 'schwind'){
    output("Ohne weiteres Interesse an dem seltsamen Weibsbild verschwindest du schnell zur Tür hinaus`n
            Zornentbranntes kreischen lässt dir das Blut in den Adnern gefrieren.`n
            \"Du, du hast es gewagt, wie eigensinnig und dumm!\" kreischt sie wütend.`n
            Plötzlicher Schwindel überfällt dich und lässt dich zusammenbrechen.`n Du verlierst ein paar Lebenspunkte.");
      if ($session['user']['hitpoints']>20){
                          $session ['user']['hitpoints']-=20;
                        }else{
      $session['user']['hitpoints']=1; }
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'hilfe'){
    output("Da du ein guter Mensch bist, entschließt du dich ihr Preoblem anzuhören.`n
            \"Hach, mein Leben ist sooo eine Tortur. Meine Schwester Arcedia stiehlt mir mein Dasein.\" klagt sie.`n
            \"Ich esse und esse und werde immer dicker, aber sie? Ist das nicht gemein von ihr?\" du nickst verstehend.`n
            Erneut erhebt sich ihre Stimme:  \"Hach, helfen kannst du mir zwar nicht, aber immerhin hast du dir die Zeit genommen, ich danke dir.\"`n
            meint sie mit leicht trauriger, aber dennoch beruhigter Stimme.`n Ein gleisendes Licht umfährt dich.
            \"Du bist nun gesegnet mit meiner Kraft. Ich schütze dich.\" meint sie und winkt dir zum Abschied.`n
            Tatsächlich fühlt du dich stärker in deiner Verteidigung.");
      $session['user']['defence']+=10;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'schauen'){
    output("Da du ein guter Mensch bist, entschließt du dich ihr Preoblem anzuhören.`n
            \"Hach, mein Leben ist sooo eine Tortur. Meine Schwester Arcedia stiehlt mir mein Dasein.\" klagt sie.`n
            \"Ich esse und esse und werde immer dicker, aber sie? Ist das nicht gemein von ihr?\" du nickst verstehend.`n
            Erneut erhebt sich ihre Stimme:  \"Hach, helfen kannst du mir zwar nicht, aber immerhin hast du dir die Zeit genommen, ich danke dir.\"`n
            meint sie mit leicht trauriger, aber dennoch beruhigter Stimme.`n Ein gleisendes Licht umfährt dich.
            \"Du bist nun gesegnet mit meiner Kraft. Ich schütze dich.\" meint sie und winkt dir zum Abschied.`n
            Tatsächlich fühlt du dich stärker in deiner Verteidigung.");
      $session['user']['defence']+=10;
    addnav("Zurück","turm7suenden.php");
    }
        //Ira
    else if($_GET['op'] == 'plan'){
    output("Ihr setzt euch zusammen und heckt einen grandiosen Plan aus. Du hast kein schlechtes gewissen dabei, warum auch? `n
            Ira ist sehr zufrieden mit dir. Sie belebt dich mit einem Zauber, der dich etwas stärker macht.");
      $session['user']['attack']+=10;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'rache'){
    output("Du bist eher für eine friedliche Lösung, und versuchst sie zu beruhigen, allerdings scheint sie davon nicht wirklich begeistert zu sein.`n
            Dich und deine guten Zureden einfach ignorierend, schwächt sie dich, weil sie sich beleidigt fühlt.");
      if ($session['user']['attack']>10){
                          $session['user']['attack']-=10;
                        }else{
      $session['user']['attack']=2; }
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'meckern'){
    output("Dir ist es egal was diese Frauen untereinander für Probleme haben, deshalb beschließt du ohne weitere Worte zu gehen.`n
            Ira scheint davon allerdings nicht sehr begeistert zu sein und belegt dich mit ihrem Fluch.");
      if ($session['user']['hitpoints']>20){
                          $session ['user']['hitpoints'];
                        }else{
      $session['user']['hitpoints']=5;    }
    addnav("Zurück","turm7suenden.php");

        //Luxuria
    }else if($_GET['op'] == 'renn'){
    output("Dir ist das alles nicht so ganz geheuer, deshalb beschließt du lieber unbemerkt das wWeite zu suchen.`n
            Die Frau scheint dich nicht bemerkt zu haben.");
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'koerper'){
    output("Bei so einer Einladung, kann dich nichts mehr halten. Ungehalten stürmst du auf sie zu, und lässt dich ganz auf sie ein.`n
            Ihr verbringt eine Nacht miteinander. Zufrieden meint sie: \"Schön zu wissen, das es noch richtige ".($session[user][sex]?"Frauen":"Männer")." gibt! \"`n
            Du fühlst dich auf einmal viel charmanter!");
      $session['user']['charm']+=10;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'verfuehren'){
    output("Du beginst zu grinsen, und hast schon deine Gedanken. Voller Elan gehst du auf sie zu, und hast schon die schönsten Gedanken.`n
            Zu deiner Überraschung meint sie jedoch: \"Ihr ".($session['user']['sex']?"Frauen":"Männer")." Habt wirklich immer nur das eine im Sinn.. tz tz tz! \"`n
            Sie schickt dich sofort weg, das war wohl ein Schuss in den Ofen. Zudem hast du das Gefühl grade etwas von deinem Charme eingebüßt zu haben.");
      if ($session['user']['charm']=10){
                          $session['user']['charm']-=10;
                        }else{
      $session['user']['charm']=0;}
    addnav("Zurück","turm7suenden.php");

        //Avaritia
    }else if($_GET['op'] == 'gold'){
    output("Du willst ihr eine Freude machen, und sie nicht verärgern, darum bechließt du ihr ein wenig von deinem Gold zu geben.`n
            Sie erhebt sich und meint:  \"Na sieh einer an. Ich bin wirklich sehr überrascht. Ich danke dir. \"`n
            Du bist nun zwar etwas ärmer, dafür hast du das Gefühl, als ob du eine gute Tat vollbracht hast.");
      if ($session['user']['gold']>1000){
                          $session['user']['gold']-=1000;
                        }else{
      $session['user']['gold']=10; }
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'gems'){
    output("Du beschließt ihr ein paar von deinen Edelsteinen abzugeben, und willst ihr diese überreichen. Zu deiner Überraschung, schüttelt sie jedoch mit dem Kopf.`n
            \"Ich liebe das Geld und das Gold ".($session['user']['sex']?"kleine":"kleiner")." Solche Klunker interessieren mich nicht wirklich, zumal ich davon mehr als genug habe! \"`n
            Sie dreht sich weg, woraufhin du ihren Raum wieder verlassen willst. Plötzlich bekommst du etwas hartes an den Kopf. Erstaunt schaust du zu Boden.`n
            Sie hat dir tatsächlich ein paar Edelsteine nachgeschmissen. Schnell steckst du sie in deinen Beutel und machst dich vom Acker.");
      $session['user']['gems']+=10;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'unbemerkt'){
    output("Dir macht ihr verschmitztes Grinsen irgendwie Angst. Schnell willst du das weite suchen, als sie sich auch schon erhebt.`n
            \"Gib mir dein Gold, ich will es haben!! \" zischt sie. Schnell läufst du Richtung Ausgang. `nDoch du passt nicht auf, woraufhin du stolperst und dein gesamtes Gold verlierst.");
      $session['user']['gold']= 0;
    addnav("Zurück","turm7suenden.php");

        //Superbia
    }else if($_GET['op'] == 'panik'){
    output("Du riechst etwas, und das was du riechst, ist definitiv der Tod! Nein, nichts wie weg hier, das ist dir alles nicht geheuer. `n
            Hals über Kopf rennst du wie ein ängstliches Hühnchen nach draußen. Ihr lachen ist kaum zu überhören, deine Feigheit hat dich etwas Ansehen gekostet.");
      if ($session['user']['reputation']>10){
                          $session['user']['reputation']-=10;
                        }else{
      $session['user']['reputation']=0; }
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'leben'){
    output("Du hast Angst, aber dennoch bist du unheimlich mutig und bietest ihr dein Leben an. Sie kichert und umschleicht dich wie eine Schlange ihr Opfer.`n
            Dein Herz rast und du hast Panik was wohl als nächstes passieren wird. Sie entfernt sich wieder und schnippt mit den Fingern.`n
            \"Meine Achtung, nicht schlecht, ich hätte nicht gedacht, das du mir dein leben so einfach aushändigst. Nun denn, ich belohne dich für deinen Mut. \"`n
            Du spürst wie dich plötzlich unheimliche Kräfte umfahren.");
      $session['user']['maxhitpoints']+=2;
    addnav("Zurück","turm7suenden.php");
    }else if($_GET['op'] == 'tot'){
    output("Du traust dieser seltsamen, düsteren Frau nicht über den Weg und beschließt sie einfach zu töten. Du ziehst schnell ".$session['user']['weapon']." Hervor und stürmst mit wildem Kampfgeschrei auf sie zu.`n
            Sie lacht nur und verschwindet in der Finsternis. Du siehst dich um, aber findest sie nicht. \"Das war ein Fehler... \" meint sie lachend. Im nächsten Moment geht alles sehr schnell, `n
            Du spürst ein taubes Gefühl in deinem Körper, bis deine Welt schließlich schwarz wird.`n
            Das war die Strafe der Superbia.");
      $session['user']['alive']=false;
      $session['user']['hitpoints']=0;
        addnav("Tägliche News","news.php");
    }

page_footer();

?> 