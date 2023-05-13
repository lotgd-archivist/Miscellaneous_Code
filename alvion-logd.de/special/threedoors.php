
<?
//06052006 by -DoM (http://my-logd.com/motwd) for MoT (http://my-logd.com/mot)
// Idea by Cadderly (Player from MotWD)
if (!isset($session)) exit();
require_once "func/systemmail.php";

$fn = "forest.php";
switch ($_GET['op']){
    default:
        $session['user']['specialinc']="threedoors.php";
        output("`@Als du durch den Wald gehst, siehst du in einiger Entfernung eine Lichtung, vorsichtig blickst du dich nochmal
                um, ob dir auch niemand folgt und beschließt, diese Lichtung aufzusuchen.`n`n
                Dort angekommen bemerkst du, wie ein blauer Lichtschimmer auf der Lichtung erscheint und langsam die Konturen eines
                Hauses annimmt.`n
                Das Haus ist schlicht und einfach, du kannst hinein gehen oder wieder umkehren.`n`n
                `QWas wirst du machen?");
        addnav("`@Gehe hinein",$fn."?op=hinein");
        addnav("`\$Kehre um",$fn."?op=umkehr1");
    break;
    case "hinein":
        $session['user']['specialinc']="threedoors.php";
        output("`@Du betrittst das Haus und siehst vor dir 3 Türen. Alle Türen sehen gleich aus, sie sind aus schlichtem Holz
                gemacht und du kannst dich nun für eine der 3 Türen entscheiden.");
        output("<table border='0' width='365' id='table1' cellspacing='0' cellpadding='0' background='images/3doors.jpg' height='229' align='center'>
                    <tr>
                        <td height='25' width='35'>&nbsp;</td>
                        <td height='25' width='57'>&nbsp;</td>
                        <td height='25' width='59'>&nbsp;</td>
                        <td height='25' width='57'>&nbsp;</td>
                        <td height='25'>&nbsp;</td>
                        <td height='25' width='57'>&nbsp;</td>
                        <td height='25' width='37'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width='35'>&nbsp;</td>
                        <td width='57' valign='top'><a title='Tür Nr.1' href='$fn?op=tuer&nr=1'>
                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>
                        <td width='59'>&nbsp;</td>
                        <td width='57' valign='top'><a title='Tür Nr.2' href='$fn?op=tuer&nr=2'>
                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>
                        <td>&nbsp;</td>
                        <td width='57' valign='top'><a title='Tür Nr.3' href='$fn?op=tuer&nr=3'>
                        <img border='0' src='images/trans.gif' width='57' height='144'></a></td>
                        <td width='37'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td height='60' width='35'>&nbsp;</td>
                        <td height='60' width='57'>&nbsp;</td>
                        <td height='60' width='59'>&nbsp;</td>
                        <td height='60' width='57'>&nbsp;</td>
                        <td height='60'>&nbsp;</td>
                        <td height='60' width='57'>&nbsp;</td>
                        <td height='60' width='37'>&nbsp;</td>
                    </tr>
                </table>",true);
        addnav("",$fn."?op=tuer&nr=1");
        addnav("",$fn."?op=tuer&nr=2");
        addnav("",$fn."?op=tuer&nr=3");
        addnav("Aktionen");
        addnav("1?Tür Nr.1",$fn."?op=tuer&nr=1");
        addnav("2?Tür Nr.2",$fn."?op=tuer&nr=2");
        addnav("3?Tür Nr.3",$fn."?op=tuer&nr=3");
        addnav("Sonstiges");
        addnav("Zurück",$fn."?op=umkehr1");
    break;
    case "tuer":
        $session['user']['specialinc']="threedoors.php";
        output("`@Du hast dich für Tür Nr.".$_GET['nr']." entschieden.`n");
        switch (e_rand(1,6)){
            case 1:
            case 4:
                output("Du bleibst wie angewurzelt stehen, vor Dir steht
                        ".($session['user']['sex']?"ein bildhübscher Mann, der":"eine bildhübsche Frau, die")." dich verlockend in
                        ".($session['user']['sex']?"sein":"ihr")." Zimmer winkt. `n`n
                        `QWillst du dich der Liebe hingeben oder lieber fortlaufen?");
                addnav("Dich hingeben",$fn."?op=hingeben");
                addnav("Bloss weg hier",$fn."?op=umkehr2");
            break;
            case 2:
            case 5:
                output("Du blickst dich irritiert um, da du zuerst nichts erkennst, doch nachdem du genauer hinsiehst, kannst
                        du auf dem Boden 5 Edelsteine entdecken, die nur darauf warten, von dir mitgenommen zu werden.`n`n
                        `QWirst du dir die Edelsteine nehmen?");
                addnav("Nimm die Edelsteine",$fn."?op=gemsnehmen");
                addnav("Bloss weg hier",$fn."?op=umkehr2");
            break;
            case 3:
            case 6:
                output("Du siehst ein kleines Mädchen in dem Zimmer, das auf ihrem Bettchen sitzt und mit ihren Püppchen spielt.
                        Mit einem freundlichen Winken begrüßt es dich und lädt dich ein, sich zu ihr zu begeben und mit ihr und
                        ihren Püppchen zu spielen. `n`n
                        `QMagst du ihrem Wunsch entsprechen, oder gehst du lieber?");
                addnav("Mit ihr spielen",$fn."?op=spielen");
                addnav("Bloss weg hier",$fn."?op=umkehr2");
            break;
        }
    break;
    case "hingeben":
        output("`@Du entscheidest dich, deinen Gelüsten nachzugeben und gehst in das Zimmer hinein, die Tür hinter dir schließend.
                Was du nicht bemerkst ist, dass die Tür sich magisch verschließt.`n");
        switch (e_rand(1,4)){
            case 1:
            case 3:
                $session['user']['specialinc']="threedoors.php";
                output("".($session['user']['sex']?"Der bildhübsche Mann, dem":"Die bildhübsche Frau, die")." du dich eben noch
                        hingeben wolltest, hat sich in eine widerliche Hexe verwandelt, die dich sofort mit ihrem Hexenbesen angreift.
                        Hastig versuchst du zu entkommen, doch es gibt kein Entrinnen, die Tür ist fest versiegelt; dir bleibt
                        nichts weiter als der Kampf");
                addnav("In den Kampf",$fn."?op=kampf1");
            break;
            case 2:
            case 4:
                $session['user']['specialinc']="";
                output("Du verbringst eine wundervolle Zeit mit ".($session['user']['sex']?"dem bildhübchen Mann":"der bildhübchen Frau").".
                        Erschöpft fällst du in einen tiefen Schlaf.`n`n");

                if (e_rand(1,10)==19){
                    output("Als du wieder aufwachst, stellst du fest, dass ".($session['user']['sex']?"dein Liebhaber":"deine Liebhaberin")."
                            verschwunden ist. Nur einen kleinen Zettel findest du auf dem Tisch:`n`n
                            `Q\"Du warst so grottenschlecht im Bett, dass ich mich entschieden habe, unsere Affäre
                            ".($session['user']['sex']?"deinem Mann":"deiner Frau")." zu erzählen\"`n`n");
                    if (($session['user']['marriedto']>0) && ($session['user']['charisma']>0)){
                        output("`@Voller Panik versuchst du ".($session['user']['sex']?"deinen Liebhaber":"deine Liebhaberin")." zu finden und
                                aufzuhalten. Aber kannst niemanden mehr entdecken.`n`n
                                `QDas wars dann wohl mit deiner Ehe!");
                        systemmail($session['user']['acctid'], "Deine Ehe steht vor dem AUS!", "Durch deinen Seitensprung, den ".($session['user']['sex']?"dein Mann":"deine Frau")." mitbekommen hat, wollte ".($session['user']['sex']?"dein Mann":"deine Frau")." die sofortige Scheidung.`n`n`\$Du bist nicht länger verheiratet!`0");
                        systemmail($session['user']['marriedto'], "".($session['user']['sex']?"Deine Frau":"Dein Mann")." hat dich betrogen!", "".$session['user']['name']."`@ hat dich betrogen. Entrüstet hast du die sofortige Scheidung verlangt.`n`n`\$Du bist nicht länger verheiratet!`0");
                       // db_query("UPDATE accounts SET marriedto=0,charisma=0 WHERE acctid='".$session['user']['marriedto']."'");
                            /* Transferbug Fix */
                   updateuser($session['user']['marriedto'],array('marriedto'=>"0", 'charisma'=>"0"));
                       $session['user']['marriedto']=0;
                        $session['user']['charisma']=0;
                    }else{
                        output("`@Was für ein Stück Glück, dass du gar nicht verheiratet bist..... Du kicherst dir ins Fäustchen");
                    }
                }else{
                    $gems = e_rand(1,5);
                        if ($gems==1)
                            $textgem = "`#1 Edelstein";
                        else
                            $textgem = "`#$gems Edelsteine";
                    $gold = e_rand(1000,5000);
                    output("Du wachst aus deinem Schlaf auf und willst dich gerade an ".($session['user']['sex']?"den Hübschen":"die Hübsche")."
                            kuscheln, als du dich ins Leere drehst. ".($session['user']['sex']?"Er":"Sie")." ist einfach verschwunden. Du ziehst
                            dich wieder an und entdeckst einen kleinen Lederbeutel, den du sogleich öffnest.`n`n");
                    output("`QDu findest darin `^$gold Gold`Q und $textgem`Q!");
                    $session['user']['gems']+=$gems;
                    $session['user']['gold']+=$gold;
                }
                addnav("In den Wald",$fn);
            break;
        }
    break;
    case "gemsnehmen":
        output("`@Du bist also tapfer genug, nimmst all Deinen Mut zusammen und betrittst den Raum, um die 5 Edelsteine an dich zu
                nehmen. Doch was ist das? Du hörst, wie die Tür hinter dir zugemacht wird. Hastig rennst du zur Tür und versuchst
                sie zu öffnen, doch sie bleibt verschlossen.");
        switch (e_rand(1,6)){
            case 1:
            case 4:
                $session['user']['specialinc']="threedoors.php";
                output("Als du dich umdrehst, bildet sich im Raum ein dichter Nebelschleier,
                        der bemerkenswerter Weise genauso aussieht, wie du und er spricht sogar mit dir:`n`n
                        `c`&\"Willst du diese Edelsteine haben,`n
                        musst du zu mir kommen und mir Angst einjagen.`n
                        Und wenn du willst schnell hier raus,`n
                        musst du machen mir den Gar schnell aus.\"`c`n`n
                        `@Da dir keine andere Wahl bleibt, stellst du dich deinem eigenen Ich und trittst ihm gegenüber, um es
                        zu bekämpfen.");
                addnav("Bekämpfe dein ICH",$fn."?op=kampf2");
            break;
            case 2:
            case 5:
                $session['user']['specialinc']="";
                $wk = e_rand(2,5);
                output("Du gehst wieder in die Mitte des Raumes zu den Edelsteinen und willst diese nehmen, doch gerade als du sie dir
                        nehmen willst, lösen sie sich in Luft auf!`n`n
                        `&\"Na Toll!\"`@ denkst du dir, und du sitzt in einem fest verschlossenen Raum. Dir bleibt nichts anderes übrig,
                        als nach Hilfe zu rufen und gegen die Tür zu hämmern.`n`n
                        `QDu musst ganz schön lange warten, bis Hilfe herbeieilt und verlierst dadurch $wk Waldkämpfe.");
                addnews($session['user']['name']."`4 saß in einem dunklen Raum gefangen und rief um Hilfe!");
                if ($session['user']['turns']>$wk){
                    $session['user']['turns']-=$wk;
                }else{
                    $session['user']['turns']=0;
                }
            break;
            case 3:
            case 6:
                $session['user']['specialinc']="";
                output("Du gehst in die Mitte des Raumes und nimmst dir die `#5 Edelsteine`@.`n`n
                        `&\"Das war ja einfach\"`@ denkst du dir und gehst deiner Wege.");
                $session['user']['gems']+=5;
            break;
        }
    break;
    case "spielen":
        $session['user']['specialinc']="";
        output("`@Du hast dich entschlossen, in das Zimmer zu gehen, setzt dich zu der Kleinen aufs Bett und spielst mit ihr.
                Aus lauter Dankbarkeit schenkt dir das Mädchen später eine ihrer Puppen. Als du dann das Haus wieder verlässt,
                weil das Mädchen jetzt schlafen muss, schaust du dir die Puppe genau an und stellst fest, dass ihre Augen aus
                Edelsteinen bestehen, die du an dich nehmen kannst.");
        $session['user']['gems']+=2;
    break;
    case "umkehr1":
        $session['user']['specialinc']="";
        output("`@Dir ist das Alles nicht geheuer..... Wer geht schon einfach in so plötzlich auftauchende Häuser hinein?`n
                Du führst deinen alten Weg fort.");
        addnav("Weiter","forest.php");
    break;
    case "umkehr2":
        $session['user']['specialinc']="";
        $wk = e_rand(1,5);
        if ($wk==1)
            $text = "Waldkampf";
        else
            $text = "Waldkämpfe";
        output("`@Dir ist das Alles nicht geheuer..... Du willst nur noch weg hier und rennst in panischer Angst davon!`n
                `\$Nach dieser anstrengenden Flucht musst du erstmal wieder zu Atem kommen, daher verlierst du `^$wk`\$ $text.");
        $session['user']['turns']-=$wk;
        addnav("Weiter","forest.php");
    break;
    case "kampf1":
        $hp = 3;
        $session['user']['specialinc']="threedoors.php";
        $lvflux=0;
        if($hp==1 && $session[user][level]>1) $lvflux=-1;
        if($hp>4) $lvflux=1;
        if($hp>7) $lvflux=2;
        if($hp>10) $lvflux=3;
        if($hp>13) $lvflux=4;
        //$session['user']['maxhitpoints']-=$hp;
        //$session['user']['hitpoints']-=$hp;
        $session['user']['turns']--;
        output("`\$Der Kampf beginnt`0");
        $badguy = array(
            "creaturename"=>"wiederliche Hexe",
                    "creaturelevel"=>$session['user']['level']+$lvflux,
                    "creatureweapon"=>"Hexenbesen",
                    "creatureattack"=>$session['user']['attack']/3*$hp,
                    "creaturedefense"=>$session['user']['defence']/3*$hp,
                    "creaturehealth"=>round($session['user']['maxhitpoints']/3*$hp),0,
                    "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        redirect($fn."?op=fight");
        //$_GET['op']="fight";
    break;
    case "kampf2":
        $session['user']['specialinc']="threedoors.php";
        $badguy = array(
                "creaturename"=>"".$session['user']['name']."`@'s böser Zwilling",
                "creaturelevel"=>$session['user']['level'],
                "creatureweapon"=>"".$session['user']['weapon']."",
                "creatureattack"=>$session['user']['attack'],
                "creaturedefense"=>$session['user']['defence'],
                "creaturehealth"=>round($session['user']['maxhitpoints']),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $battle=true;
        redirect($fn."?op=fight");
    break;
    case "run":
        $session['user']['specialinc']="threedoors.php";
        output("`c`bDie Türe ist noch immer verschlossen, es gibt kein Entrinnen!`0`b`c`n`n");
        $battle=true;
    break;
    case "fight":
        $session['user']['specialinc']="threedoors.php";
        $battle=true;
    break;
}

if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="threedoors.php";
    if ($victory){
        $session['user']['specialinc']="";
        if ($badguy['creaturename']=="wiederliche Hexe"){
            $expbonus = round($session['user']['experience']*0.1);
            $gold = e_rand(1000,5000);
            $gems = e_rand(2,8);
            output("`n`n`QDie widerliche Hexe ist besiegt! Der Zauber auf die Türe hat sich aufgelöst und dein Weg ist nun wieder
                    frei`n");
            output("Du bekommst durch diesen Kampf `^$gold Gold`Q, `#$gems Edelsteine`Q und `@$expbonus Erfahrung`Q!`n");
            addnews($session['user']['name']."`% konnte eine \"verführerische\" Hexe töten und wurde dafür reich belohnt.");
            $session['user']['experience']+=$expbonus;
            $session['user']['gold']+=$gold;
            $session['user']['gems']+=$gems;
        }else{
            $expbonus = round($session['user']['experience']*0.2);
            output("`n`n`QDu hast deine nebliges Spiegelbild erledigt. Die Türe lässt sich seltsamer Weise auch wieder ohne Probleme
                    öffen und du gehst deinen Weg nun weiter.`n");
            output("Du bekommst für diesen Kampf `@$expbonus Erfahrungspunkte`Q und sackst dir die `#5 Edelsteine`Q ein.");
            addnews($session['user']['name']."`% konnte ".($session[user][sex]?"ihr":"sein")." \"nebliges\" Spiegelbild töten und wurde dafür reich belohnt.");
            $session['user']['experience']+=$expbonus;
            $session['user']['gems']+=5;
        }
        $session['user']['specialinc']="";
        $badguy=array();
        $session['user']['badguy']="";
      } elseif ($defeat){
        $session['user']['specialinc']="";
        if ($badguy['creaturename']=="wiederliche Hexe"){
            output("`n`n`\$Die Hexe hat dich besiegt. Du bist tot.`n");
               output("`\$Du hast all dein Gold verloren und kannst Morgen weiter spielen!`n");
            addnews($session['user']['name']."`% wurde von einer \"verführerischen\" Hexe getötet und verlor dabei alles Gold");
        }else{
            output("`n`n`\$Dein nebliger Zwilling hat dich besiegt. Du bist tot.");
            output("`\$Du hast all dein Gold verloren und kannst Morgen weiter spielen!`n");
            addnews($session['user']['name']."`% wurde von ".($session[user][sex]?"ihrem":"seinem")." \"nebligen\" Spiegelbild getötet und verlor dabei alles Gold");
        }
        $badguy=array();
        $session['user']['badguy']="";
        $session['user']['gold']=0;
        $session['user']['hitpoints']=0;
        $session['user']['alive']=false;
        addnav("Tägliche News","news.php");
    }else{
        fightnav(true,true);
    }
}
?>

