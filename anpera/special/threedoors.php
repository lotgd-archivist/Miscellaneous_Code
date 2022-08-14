
ï»¿<?php

//06052006 by -DoM (http://my-logd.com/motwd) for MoT (http://my-logd.com/mot)

// Idea by Cadderly (Player from MotWD)

// 20160629

if (!isset($session)) exit();

$fn = "forest.php";

switch ($_GET['op']){

    default:

        $session['user']['specialinc']="threedoors.php";

        output("`@Als du durch den Wald gehst, siehst du in einiger Entfernung eine Lichtung, vorsichtig blickst du Dich nochmal

                um ob dir auch niemand folgt und beschliesst diese Lichtung aufzusuchen.`n`n

                Dort angekommen bemerkst du wie ein blauer Lichtschimmer auf der Lichtung erscheint und langsam die Konturen eines

                Hauses annimmt.`n

                Das Haus ist schlicht und einfach, du kannst hineingehen oder wieder umkehren.`n`n

                `QWas wirst du machen?");

        addnav("`@Gehe hinein",$fn."?op=hinein");

        addnav("`\$Kehre um",$fn."?op=umkehr1");

    break;

    case "hinein":

        $session['user']['specialinc']="threedoors.php";

        output("`@Du betrittst das Haus und siehst vor dir 3 TÃ¼ren. Alle TÃ¼ren sehen gleich aus, sie sind aus schlichtem Holz

                gemacht und du kannst dich nun fÃ¼r eine der 3 TÃ¼ren entscheiden.");

        $output.="<table border='0' width='365' id='table1' cellspacing='0' cellpadding='0' background='images/3doors.jpg' height='229' align='center'>

                    <tr>

                        <td height='25' width='35'>&nbsp;</td>

                        <td height='25' width='57'>&nbsp;</td>

                        <td height='25' width='59'>&nbsp;</td>

                        <td height='25' width='57'>&nbsp;</td>

                        <td height='25'>&nbsp;</td>

                        <td height='25' width='57'>&nbsp;</td>

                        <td height='25' width='37'>&nbsp;</td>

                    </tr>

                    <tr>

                        <td width='35'>&nbsp;</td>

                        <td width='57' valign='top'><a title='TÃ¼r Nr.1' href='$fn?op=tuer&nr=1'>

                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>

                        <td width='59'>&nbsp;</td>

                        <td width='57' valign='top'><a title='TÃ¼r Nr.2' href='$fn?op=tuer&nr=2'>

                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>

                        <td>&nbsp;</td>

                        <td width='57' valign='top'><a title='TÃ¼r Nr.3' href='$fn?op=tuer&nr=3'>

                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>

                        <td width='37'>&nbsp;</td>

                    </tr>

                    <tr>

                        <td height='60' width='35'>&nbsp;</td>

                        <td height='60' width='57'>&nbsp;</td>

                        <td height='60' width='59'>&nbsp;</td>

                        <td height='60' width='57'>&nbsp;</td>

                        <td height='60'>&nbsp;</td>

                        <td height='60' width='57'>&nbsp;</td>

                        <td height='60' width='37'>&nbsp;</td>

                    </tr>

                </table>";

        addnav("",$fn."?op=tuer&nr=1");

        addnav("",$fn."?op=tuer&nr=2");

        addnav("",$fn."?op=tuer&nr=3");

        addnav("Aktionen");

        addnav("TÃ¼r Nr.1",$fn."?op=tuer&nr=1");

        addnav("TÃ¼r Nr.2",$fn."?op=tuer&nr=2");

        addnav("TÃ¼r Nr.3",$fn."?op=tuer&nr=3");

        addnav("Sonstiges");

        addnav("ZurÃ¼ck",$fn."?op=umkehr1");

    break;

    case "tuer":

        $session['user']['specialinc']="threedoors.php";

        output("`@Du hast dich fÃ¼r TÃ¼r Nr.".$_GET['nr']." entschieden.`n");

        switch (e_rand(1,6)){

            case 1:

            case 4:

                output("Du bleibst wie angewurzelt stehen, vor Dir steht

                        ".($session['user']['sex']?"ein bildhÃ¼bscher Mann, der":"eine bildhÃ¼bsche Frau, die")." dich verlockend in

                        ".($session['user']['sex']?"sein":"ihr")." Zimmer winkt. `n`n

                        `QWillst du Dich der Liebe hingeben oder lieber fortlaufen?");

                addnav("Dich hingeben",$fn."?op=hingeben");

                addnav("Bloss weg hier",$fn."?op=umkehr2");

            break;

            case 2:

            case 5:

                output("Du blickst dich irritiert um, da du zuerst nichts erkennst, doch nachdem du genauer hinsiehst kannst

                        du auf dem Boden 5 Edelsteine entdecken, die nur darauf warten von dir mitgenommen zu werden.`n`n

                        `QWirst du dir die Edelsteine nehmen?");

                addnav("Nimm die Edelsteine",$fn."?op=gemsnehmen");

                addnav("Bloss weg hier",$fn."?op=umkehr2");

            break;

            case 3:

            case 6:

                output("Du siehst ein kleines MÃ¤dchen in dem Zimmer was auf ihrem Bettchen sitzt und mit ihren PÃ¼ppchen spielt.

                        Mit einem freundlichen winken begrÃ¼ÃŸt es Dich und lÃ¤dt Dich ein, sich zu ihr zu begeben und mit ihr und

                        ihren PÃ¼ppchen zu spielen. `n`n

                        `QMagst Du ihrem Wunsch entsprechen oder gehst du lieber?");

                addnav("Mit ihr spielen",$fn."?op=spielen");

                addnav("Bloss weg hier",$fn."?op=umkehr2");

            break;

        }

    break;

    case "hingeben":

        output("`@Du entscheidest dich deinen GelÃ¼sten nachzugeben und gehst in das Zimmer hinein, die TÃ¼r hinter dir schlieÃŸend.

                Was du nicht bemerkst ist das die TÃ¼r sich magisch verschlieÃŸt.`n");

        switch (e_rand(1,4)){

            case 1:

            case 3:

                $session['user']['specialinc']="threedoors.php";

                output("".($session['user']['sex']?"Der bildhÃ¼bsche Mann, dem":"Die bildhÃ¼bsche Frau, der")." du dich eben noch

                        hingeben wolltest, hat sich in eine widerliche Hexe verwandelt die dich sofort mit Ihrem Hexenbesen angreift.

                        Hastig versuchst du zu entkommen doch es gibt kein entrinnen, die TÃ¼r ist fest versiegelt; dir bleibt

                        nichts weiter als der Kampf");

                addnav("In den Kampf",$fn."?op=kampf1");

            break;

            case 2:

            case 4:

                $session['user']['specialinc']="";

                output("Du verbringst eine wundervolle Zeit mit ".($session['user']['sex']?"dem bildhÃ¼bschen Mann":"der bildhÃ¼bschen Frau").".

                        ErschÃ¶pft fÃ¤llst du in einen tiefen Schlaf.`n`n");

                if (e_rand(1,10)==9){

                    output("Als du wieder aufwachst stellst du fest, dass ".($session['user']['sex']?"dein Liebhaber":"deine Liebhaberin")."

                            verschwunden ist. Nur einen kleinen Zettel findest du auf dem Tisch:`n`n

                            `Q\"Du warst so grottenschlecht im Bett, dass ich mich entschieden habe, unsere AffÃ¤re

                            ".($session['user']['sex']?"deinem Mann":"deiner Frau")." zu erzÃ¤hlen\"`n`n");

                    if ($session['user']['marriedto']>0 && $session['user']['charisma']>0 && $session['user']['marriedto']<4294967295){

                        output("`@Voller Panik versuchst du ".($session['user']['sex']?"dein Liebhaber":"deine Liebhaberin")." zu finden und

                                aufzuhalten. Aber du kannst niemanden mehr entdecken.`n`n

                                `QWars das mit deiner Ehe?");

                        //systemmail($session['user']['acctid'], "Deine Ehe steht vor dem AUS!", "Durch deinen Seitensprung, den ".($session['user']['sex']?"dein Mann":"deine Frau")." mitbekommen hat, wollte ".($session['user']['sex']?"dein Mann":"deine Frau")." die sofortige Scheidung.`n`n`\$Du bist nicht lÃ¤nger verheiratet!!!`0");

                        //db_query("UPDATE accounts SET marriedto=0,charisma=0 WHERE acctid='".$session['user']['marriedto']."'");

                if ($session['user']['marriedto']==4294967295){

                           $session['user']['marriedto']=0;

                           $session['user']['charisma']=0;

                           output("`nLeider ja. Du kannst ".($session['user']['sex']?"Seth":"Violet")." bis in den Wald FlÃ¼che gegen dich schreien hÃ¶ren. Vielleicht solltest du dich besser eine Weile fernhalten.");

            }else{

                           systemmail($session['user']['marriedto'], "".($session['user']['sex']?"Deine Frau":"Dein Mann")." ist im Wald schwach geworden!", "".$session['user']['name']."`@ hat dich im Wald betrogen! ".($session['user']['sex']?"Ihr Liebhaber":"Seine Liebahberin")." hat dir ALLES erzÃ¤hlt.`0");

            }

                    }else{

                        output("`@Was fÃ¼r ein stÃ¼ck GlÃ¼ck, dass du gar nicht verheiratet bist..... Du kicherst dir ins FÃ¤ustchen");

                    }

                }else{

                    $gems = e_rand(1,5);

                        if ($gems==1)

                            $textgem = "`#1 Edelstein";

                        else

                            $textgem = "`#$gems Edelsteine";

                    $gold = e_rand(1000,5000);

                    output("Du wachst aus deinem Schlaf auf und willst dich gerade an ".($session['user']['sex']?"den HÃ¼bschen":"die HÃ¼bsche")."

                            kuscheln, als du dich ins Leere drehst. ".($session['user']['sex']?"Er":"Sie")." ist einfach verschwunden. Du ziehst

                            dich wieder an und entdeckst einen kleinen Lederbeutel, den du sogleich Ã¶ffnest.`n`n");

                    output("`QDu findest darin `^$gold Gold`Q und $textgem`Q!");

                    $session['user']['gems']+=$gems;

                    $session['user']['gold']+=$gold;

                }

                addnav("In den Wald",$fn);

            break;

        }

    break;

    case "gemsnehmen":

        output("`@Du bist also tapfer genug und nimmst all Deinen Mut zusammen und betrittst den Raum um die 5 Edelsteine an dich zu

                nehmen. Doch was ist das? Du hÃ¶rst wie die TÃ¼r hinter Dir zugemacht wird. Hastig rennst du zur TÃ¼r und versuchst

                sie zu Ã¶ffnen doch sie bleibt verschlossen.");

        switch (e_rand(1,6)){

            case 1:

            case 4:

                $session['user']['specialinc']="threedoors.php";

                output("Als Du Dich umdrehst bildet sich im Raum ein dichter Nebelschleier

                        der bemerkenswerter Weise genauso aussieht wie Du und es spricht sogar mit dir:`n`n

                        `c`&\"Willst du Diese Edelsteine haben,`n

                        musst du zu mir kommen und mir Angst einjagen.`n

                        Und wenn du willst schnell hier raus,`n

                        musst du machen mir den Gar schnell aus.\"`c`n`n

                        `@Da dir keine andere Wahl bleibt stellst Du Dich deinem eigenen Ich und trittst ihm gegenÃ¼ber um ihn

                        zu bekÃ¤mpfen.");

                addnav("BekÃ¤mpfe dein ICH",$fn."?op=kampf2");

            break;

            case 2:

            case 5:

                $session['user']['specialinc']="";

                $wk = e_rand(2,5);

                output("Du gehst wieder in die Mitte des Raumes zu den Edelsteinen und willst diese nehmen, doch gerade als du sie dir

                        nehmen willst, lÃ¶sen diese sich in Luft auf!`n`n

                        `&\"Na Toll!!!\"`@ denkst du dir, und du sitzt in einem fest verschlossenen Raum. Dir bleibt nichts anderes Ã¼brig,

                        nach Hilfe zu rufen und gegen die TÃ¼r zu hÃ¤mmern.`n`n

                        `Qdu musst ganz schÃ¶n lange warten bis Hilfe herbeieilt und verlierst dadurch $wk WaldkÃ¤mpfe.");

                addnews($session['user']['name']."`4 saÃŸ in einem dunklen Raum gefangen und rief um Hilfe!!!");

                if ($session['user']['turns']>$wk){

                    $session['user']['turns']-=$wk;

                }else{

                    $session['user']['turns']=0;

                }

            break;

            case 3:

            case 6:

                $session['user']['specialinc']="";

                output("Du gehst in die Mitte des Raumes und nimmst dir die `#5 Edelsteine`@.`n`n

                        `&\"Das war ja einfach\"`@ denkst du dir und gehst deiner Wege.");

                $session['user']['gems']+=5;

            break;

        }

    break;

    case "spielen":

        $session['user']['specialinc']="";

        output("`@Du hast Dich entschlossen in das Zimmer zu gehen und setzt dich zu der kleinen aufs Bett und spielst mit ihr.

                Aus lauter Dankbarkeit schenkt dir das MÃ¤dchen spÃ¤ter eine Ihrer Puppen. Als Du dann das Haus wieder verlÃ¤sst,

                weil das MÃ¤dchen jetzt schlafen muss, schaust Du Dir die Puppe genau an und stellst fest, dass ihre Augen aus

                Edelsteinen bestehen.");

        //$session['user']['gems']+=2;

    db_query("INSERT INTO items(name,class,owner,gold,gems,description) VALUES ('Puppe eines MÃ¤dchens','Geschenk',".$session['user']['acctid'].",250,2,'Du hast diese Puppe von einem kleinen MÃ¤dchen geschenkt bekommen. Die Augen sind Edelsteine')");

    break;

    case "umkehr1":

        $session['user']['specialinc']="";

        output("`@Dir ist das alles nicht geheuer..... Wer geht schon einfach, in so plÃ¶tzlich auftauchende HÃ¤user hinein.`n

                Du fÃ¼hrst deinen alten Weg fort.");

        addnav("Weiter","forest.php");

    break;

    case "umkehr2":

        $session['user']['specialinc']="";

        $wk = e_rand(1,5);

        if ($wk==1)

            $text = "Waldkampf";

        else

            $text = "WaldkÃ¤mpfe";

        output("`@Dir ist das alles nicht geheuer..... Du willst nur noch weg hier und rennst in panischer Angst davon!`n

                `\$Nach dieser anstrengenden Flucht musst du erstmal wieder zu Atem kommen, daher verlierst du `^$wk`\$ $text.");

        if ($session['user']['turns']>$wk){

            $session['user']['turns']-=$wk;

    }else{

        $session['user']['turns']=0;

        }

        addnav("Weiter","forest.php");

    break;

    case "kampf1":

        $session['user']['specialinc']="threedoors.php";

        $lvflux=0;

        $session['user']['turns']--;

        output("`\$Der Kampf beginnt`0");

        $badguy = array(

           "creaturename"=>"widerliche Hexe",

           "creaturelevel"=>$session['user']['level']+$lvflux,

           "creatureweapon"=>"Hexenbesen",

           "creatureattack"=>$session['user']['attack']/2,

           "creaturedefense"=>$session['user']['defence']/2,

           "creaturehealth"=>round($session['user']['maxhitpoints']/2),0,"diddamage"=>0);

        $session['user']['badguy']=createstring($badguy);

        //updatetexts('badguy',createstring($badguy));

        $battle=true;

        redirect($fn."?op=fight");

    break;

    case "kampf2":

        $session['user']['specialinc']="threedoors.php";

        $badguy = array(

                "creaturename"=>"".$session['user']['name']."`@'s bÃ¶ser Zwilling",

                "creaturelevel"=>$session['user']['level'],

                "creatureweapon"=>"".$session['user']['weapon']."",

                "creatureattack"=>$session['user']['attack'],

                "creaturedefense"=>$session['user']['defence'],

                "creaturehealth"=>round($session['user']['maxhitpoints']),

                "diddamage"=>0);

        $session['user']['badguy']=createstring($badguy);

        //updatetexts('badguy',createstring($badguy));

        $battle=true;

        redirect($fn."?op=fight");

    break;

    case "run":

        $session['user']['specialinc']="threedoors.php";

        output("`c`bDie TÃ¼re ist noch immer verschlossen, es gibt kein Entrinnen!`0`b`c`n`n");

        $battle=true;

    break;

    case "fight":

        $session['user']['specialinc']="threedoors.php";

        $battle=true;

    break;

}



if ($battle) {

    include("battle.php");

    $session['user']['specialinc']="threedoors.php";

    if ($victory){

        $session['user']['specialinc']="";

        if ($badguy['creaturename']=="widerliche Hexe"){

            $expbonus = round($session['user']['experience']*0.1);

            $gold = e_rand(1000,5000);

            $gems = e_rand(2,8);

            output("`n`n`QDie widerliche Hexe ist besiegt! Der Zauber auf die TÃ¼re hat sich aufgelÃ¶st und dein Weg ist nun wieder

                    frei`n");

            output("Du bekommst durch diesen Kampf `^$gold Gold`Q, `#$gems Edelsteine`Q und `@$expbonus Erfahrung`Q!`n");

            addnews($session['user']['name']."`% konnte eine \"verfÃ¼hrerische\" Hexe tÃ¶ten und wurde dafÃ¼r reich belohnt.");

            $session['user']['experience']+=$expbonus;

            $session['user']['gold']+=$gold;

            $session['user']['gems']+=$gems;

        }else{

            $expbonus = round($session['user']['experience']*0.2);

            output("`n`n`QDu hast deine nebliges Spiegelbild erledigt. Die TÃ¼re lÃ¤sst sich seltsamerweise auch wieder ohne Probleme

                    Ã¶ffen und du gehst deinen Weg nun wieder.`n");

            output("Du bekommst fÃ¼r diesen Kampf `@$expbonus Erfahrungspunkte`Q und sackst dir die `#5 Edelsteine`Q ein.");

            addnews($session['user']['name']."`% konnte ".($session[user][sex]?"ihr ":"sein ")." \"nebliges\" Spiegelbild tÃ¶ten und wurde dafÃ¼r reich belohnt.");

            $session['user']['experience']+=$expbonus;

            $session['user']['gems']+=5;

        }

        $session['user']['specialinc']="";

        $badguy=array();

        $session['user']['badguy']="";

        //updatetexts('badguy','');

     } elseif ($defeat){

        $session['user']['specialinc']="";

        if ($badguy['creaturename']=="wiederliche Hexe"){

            output("`n`n`\$Die Hexe hat dich besiegt. Du bist tot.`n");

                   output("`\$Du hast all dein Gold verloren und kannst Morgen weiter spielen!`n");

            addnews($session['user']['name']."`% wurde von einer \"verfÃ¼hrerischen\" Hexe getÃ¶tet und verlor dabei alles Gold");

        }else{

            output("`n`n`\$Dein nebliger Zwillung hat dich besiegt. Du bist tot.");

            output("`\$Du hast all dein Gold verloren und kannst Morgen weiter spielen!`n");

            addnews($session['user']['name']."`% wurde von ".($session[user][sex]?"ihrem ":"seinem ")." \"nebligen\" Spiegelbild getÃ¶tet und verlor dabei alles Gold");

        }

        $badguy=array();

        $session['user']['badguy']="";

        //updatetexts('badguy','');

        $session['user']['gold']=0;

        $session['user']['hitpoints']=0;

        $session['user']['alive']=false;

        addnav("TÃ¤gliche News","news.php");

    }else{

        fightnav(true,true);

    }

}

?>

