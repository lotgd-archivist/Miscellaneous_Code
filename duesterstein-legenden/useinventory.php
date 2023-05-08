
<?
// ----------------------------------------------------------------------------
// Inventory System - Item usage
// 21st October 2004
// Version: 0.9
// Author: Gargamel
//
// Use your item found in the realm anywhere - anyplace - even in battles.
// Newday and badnav are are skiped.
// The inventory is available in forest fights, pvp and challenging the master.
// The inventory is disabled in forest specials, the housing area and the pvp arena.
// ATTENTION:
// If you run other modules which give benefits to the player, please make
// sure that the use of an item did NOT repeat the last sequence and the
// benefits!
// you may disable the inventury in your module by:
// $session['user']['blockinventory']=1;
// or more specific in the file common.php, search for the line:
// $precheck=CalcReturnPath();
// and add your module to the following code there.
//
// Preliminaries:
// Mod: Portable Potions with clickable icons
// Author: Lonnyl of http://www.pqcomp.com/logd
//
// Credits:
// Thanks Lonnyl, you're mod (Portable Potion) was inspiration and
// general code base for this.
//
// Install instruction:
// - copy this file into your LOGD main folder.
// - see all instructions commin' together with the re-release of the
//   Clans/Guilds mod by Dasher with Changes/Enhancements by Gargamel.
//
// Contact Gargamel:
// eMail: gargamel@rabenthal.de or gargamel@silienta-logd.de
// Forum: Gargi at dragonprime
//
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

require_once "common.php";
checkday();
page_header("Potion");
output("`c`b`&Use a Potion`0`b`c`n`n");

if ($session['user']['hitpoints'] > 0){}else{
    redirect("shades.php");
}

$return=$session['user']['pqrestorepage'];
    //$return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]); 
    //$return = substr($return,strrpos($return,"/")+1); 
    //if (strpos($return,"ewday")>0) $return = "village.php";
    //addnav("Zurück",$return); 

// for security reason no effect in case of badnav or newday !!
$rp = $session['user']['pqrestorepage'];
$x = max(strrpos("&",$rp),strrpos("?",$rp));
if ($x>0) $rp = substr($rp,0,$x);
if (substr($rp,0,10)=="badnav.php" or substr($rp,0,10)=="newday.php"){
    addnav("Weiter","village.php");
}else{
// effect is granted
    $was = $_GET[op];
    // which item was used
    switch ( $was ) {
        case "heal":
        $amt = e_rand ( round($session['user']['maxhitpoints']*0.30,0),
                        round($session['user']['maxhitpoints']*0.60,0) );
        output("`n`3Du trinkst den mittleren Heiltrank und wirst um bis zu $amt
        Lebenspunkte regeneriert.`n`0");
        if ( ($session['user']['hitpoints']+$amt)>$session['user']['maxhitpoints'] ) {
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        } else {
            $session['user']['hitpoints']+=$amt;
        }
        break;

        case "ring":
        output("`n`3Du kramst den silbernen Ring aus Deinem Beutel und ziehst ihn
        auf einen Finger. Sofort umgibt Dich eine schützende Aura, die aber leider
        schnell ihre Wirkung verliert.`n`0");
        $session[bufflist]['silverring'] = array("name"=>"`&silbener Ring`0",
                                        "rounds"=>10,
                                        "wearoff"=>"Du hast den Ring verloren.",
                                        "defmod"=>1.2,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Die magische Aura des Rings schützt Dich.",
                                        "activate"=>"defense");
        break;

        case "fullheal":
        output("`n`3Du trinkst den starken Heiltrank in einem Zug und spürst sofort,
        dass Deine Lebensenergie komplett wiederhergestellt ist.`n`0");
        if ( $session['user']['hitpoints']<$session['user']['maxhitpoints'] )
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        break;

        case "dagger":
        output("`n`3Du greifst zu Deinem kleinen Dolch. So kannst Du die Überraschung
        Deines Gegners für eine Runde ausnutzen, in der Du besonders stark bist.`n`0");
        $session[bufflist]['dagger'] = array("name"=>"`3kleiner Dolch`0",
                                        "rounds"=>1,
                                        "wearoff"=>"Das war die Überraschung.",
                                        "defmod"=>1,
                                        "atkmod"=>1.5,
                                        "roundmsg"=>"Du überraschst Deinen Gegner und stößt mit dem Dolch zu.",
                                        "activate"=>"offense");
        break;
        
        case "blueflame":
        output("`n`1Du lässt die mystische blaue Flamme auf Deiner Handfläche
        lodern. Sichtlich eingeschüchtert greift Dich Dein Gegner nur noch sehr
        vorsichtig und zögerlich an.`n`0");
        $session[bufflist]['blueflame'] = array("name"=>"`1blaue Flamme`0",
                                        "rounds"=>15,
                                        "wearoff"=>"Die Flamme ist erloschen.",
                                        "defmod"=>1,
                                        "atkmod"=>1,
                                        "badguyatkmod"=>0.75,
                                        "roundmsg"=>"Dein Gegner weicht vor der Flamme zurück.",
                                        "activate"=>"offense");
        break;

        case "exp":
        $faktor = (e_rand(2,5)/100);
        $exp = round( $session['user']['experience'] * $faktor );
        output("`n`#Du liesst im Buch der alten Kampfkunst. Mit grossem Interesse
        verschlingst Du die Seiten förmlich und schon bald bist Du am Ende angekommen.
        Plötzlich löst sich das Buch auf. `n
        Du bist darüber nicht begeistert, lässt Dich aber von $exp Erfahrungspunkten
        trösten, die Du zusätzlich erhältst.`0`n");
        $session['user']['experience']+=$exp;
        break;

        case "mornstar":
        output("`n`QDu entschliesst Dich, jetzt den Morgenstern als zusätzliche
        Waffe einzusetzen. Bis sich Dein Gegner darauf eingestellt hast, bist Du
        für wenige Runden im Vorteil.`0`n");
        $session[bufflist]['mornstar'] = array("name"=>"`QMorgenstern`0",
                                        "rounds"=>7,
                                        "wearoff"=>"Der Morgenstern fällt Dir aus der Hand.",
                                        "defmod"=>1,
                                        "atkmod"=>1.33,
                                        "roundmsg"=>"Du schwingst den Morgenstern zum Angriff.",
                                        "activate"=>"offense");
        break;
        
        case "skull":
        $fav = e_rand(20,80);
        output("`n`%Du legst den Totenschädel an den Fuß eines besonderen Baumes und
        murmelst eine geheime Formel...`n
        Sodann erscheint Ramius vor Deinem geistigen Auge und spricht mit tiefer,
        dröhnender Stimme: \"`$ Ich gewähre Dir $fav Gefallen.`%\".`n`0");
        $session['user']['deathpower']+=$fav;
        break;
        
        case "injury":
        output("`n`2Schnell nimmst Du die Giftflasche aus Deinem Beutel und bereitest
        Dich konzentriert auf den Wurf der Flasche vor.`n
        Sobald das Gift Deinen Gegner trifft, wird seine Verteidigung nachlassen.`n`0");
        $session[bufflist]['injury'] = array("name"=>"`2Gifttrank`0",
                                        "rounds"=>11,
                                        "wearoff"=>"Die Wirkung des Tranks verflüchtigt sich.",
                                        "defmod"=>1,
                                        "atkmod"=>1,
                                        "badguyatkmod"=>0.99,
                                        "badguydefmod"=>0.66,
                                        "roundmsg"=>"`2Der Gifttrank behindert den Gegner.",
                                        "activate"=>"offense");
        break;
        
        case "strength":
        output("`n`6Du trinkst einen mächtigen Trank der Stärke. Um Deinen Körper
        zu schützen, steht Dir die gewaltige Kraft nur für ganz kurze Zeit zur
        Verfügung.`0`n");
        $session[bufflist]['strength'] = array("name"=>"`6Stärketrank`0",
                                        "rounds"=>3,
                                        "wearoff"=>"Nun musst Du Dich wieder auf Deine eigene Kraft verlassen.",
                                        "defmod"=>1,
                                        "atkmod"=>10,
                                        "roundmsg"=>"Mit mächtigen Muskeln drischt Du auf den Gegner ein.",
                                        "activate"=>"offense");
        break;
        
        case "magic":
        output("`n`7Als ob es Deine letzte Hoffnung ist, öffnest du den Trank, der
        eine unbekannte Magie enthält. Nur was wird er bewirken? Mal sehen...`n`0");
        $rnd = e_rand(5,20);
        $def = e_rand (4,14)/10;
        $att = e_rand (6,16)/10;
        $session[bufflist]['magic'] = array("name"=>"`7Magietrank`0",
                                        "rounds"=>$rnd,
                                        "wearoff"=>"Der Zauber ist verflogen.",
                                        "defmod"=>$def,
                                        "atkmod"=>$att,
                                        "roundmsg"=>"Die Magie beeinflusst Dich.",
                                        "activate"=>"offense");
        break;
        
        case "vitality":
        output("`n`9Du nimmst den Vitaltrank und erhältst Deine Spezialfähigkeiten
        zurück.`0`n");
        //-> fähigkeiten aktivieren
        $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
        $session[user][magicuses]=floor ( $session[user][magic]/3 );
        $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        break;

        case "mana":
        output("`n`9Du nimmst den Vitaltrank und erhältst Deine Spezialfähigkeiten
        zurück.`0`n");
        //-> fähigkeiten aktivieren
        $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
        $session[user][magicuses]=floor ( $session[user][magic]/3 );
        $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        break;

        case "special":
        output("`n`9Du nimmst den Vitaltrank und erhältst Deine Spezialfähigkeiten
        zurück.`0`n");
        //-> fähigkeiten aktivieren
        $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
        $session[user][magicuses]=floor ( $session[user][magic]/3 );
        $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        break;

        case "plate":
        output("`n`9Du nimmst den Vitaltrank und erhältst Deine Spezialfähigkeiten
        zurück.`0`n");
        //-> fähigkeiten aktivieren
        $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
        $session[user][magicuses]=floor ( $session[user][magic]/3 );
        $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        break;

        case "mana":
        if ( $session[user][hashorse] > 0 ) {
            $rnd = e_rand(18,41);
            output("`n`@Du gibst Deinem Tier den besonderen Mana-Trank. Und schon
            schöpft es zusätzliche Kraft, um Dir $rnd weitere Runden zur Seite
            zu stehen.`0`n");
            $session[bufflist][mount][rounds]+= $rnd;
        } else{
            output("`n`@Der Mana-Trank ist nur für Tiere gedacht! Da Du ihn selber
            trinkst, kann er keine Wirkung entfalten.`0`n");
        }
        break;

        case "special":
        output("`n`%Sofort nach dem Genuss des Spezialtranks freust Du Dich über
        hinzu gewonnene Spezialfähigkeiten. Auch, wenn Du sie nur heute nutzen
        kannst.`0`n");
        $anw=e_rand(1,3);
        $lvl=$anw*3;
        switch(e_rand(1,3)){
            case 1:
            output("`8Du bekommst $lvl Level in dunklen Künsten gutgeschrieben.`0");
            $session[user][darkarts]+=$lvl;
            $session[user][darkartuses]+=$anw;
            break;

            case 2:
            output("`8Du beherrscht die Mystik nun $lvl Level besser.`0");
            $session[user][magic]+=$lvl;
            $session[user][magicuses]+=$anw;
            break;

            case 3:
            output("`8Deine Diebesfähigkeiten verbessern sich um $lvl Level.`0");
            $session[user][thievery]+=$lvl;
            $session[user][thieveryuses]+=$anw;
            break;
        }
        break;
        
        case "plate":
        output("`n`9Du Zwängst Dich in die gefundene Rüstung. Sie passt nicht perfekt
        und behindert Dich daher etwas, aber Du wirst von ihrem Schutz profitieren.`0`n");
        $session[bufflist]['plate'] = array("name"=>"`9Alte Rüstung`0",
                                        "rounds"=>18,
                                        "wearoff"=>"Die rostige Rüstung fällt auseinander.",
                                        "defmod"=>1.66,
                                        "atkmod"=>0.9,
                                        "roundmsg"=>"In der Rüstung bist Du gut geschützt.",
                                        "activate"=>"offense");
        break;
        //
     }
    // unset item from inventory
    while(list($key,$buff) = each($session['user']['inventory'])) {
        if ($buff==$was){
            unset($session['user']['inventory'][$key]);
            break;
        }
    }
    // navi
    addnav("Weiter",$return);
}
page_footer();
?>


