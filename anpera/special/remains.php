
ï»¿<?php



// 11072004



/* Skeletal Remains v1.1 by Timothy Drescher (Voratus)

Current version can be found at Domarr's Keep (lgd.tod-online.com)



Version History

1.0 original version

1.1 bug fix (would not award gold nor gem(s) upon defeating the warrior)



- german translation by anpera

- some changes for my version - may not work with other versions!

- taken as base code to introduce curses based on my item system ;)

*/



if (!isset($session)) exit();

if ($HTTP_GET_VARS['op']=="" || $HTTP_GET_VARS['op']=="search"){

    $result = db_query("SELECT name,sex FROM accounts WHERE alive=0 ORDER BY rand(".e_rand().") LIMIT 1");

    $row2 = db_fetch_assoc($result);

    $tname = $row2[name];

    output("`GDu stoplerst Ã¼ber etwas. Es sind die skelettierten Ãœberreste dessen, was einst ein Abenteuer wie du gewesen sein kÃ¶nnte. TatsÃ¤chlich deutes vieles darauf hin, dass dies die Ãœberreste von `%$tname`G sind.");

    output("Die sterblichen Ãœberreste scheinen unberÃ¼hrt und was immer ".($row2[sex]?"sie":"er")." fÃ¼r SchÃ¤tze dabei gehabt haben mag, kÃ¶nnte immer noch dort sein. ");

    output("`n`nWas wirst du tun?`n`n");

    addnav("Nach ReichtÃ¼mern suchen","forest.php?op=desecrate");

    addnav("Die Leiche in Ruhe lassen","forest.php?op=leave");

    if ($session[user][turns] > 2) {

        addnav("Begraben (3 WaldkÃ¤mpfe)","forest.php?op=bury");

    }

    $session[user][specialinc]="remains.php";

} elseif ($HTTP_GET_VARS['op']=="bury"){

    $session[user][turns]-=3;

    // The following two lines are specific to lgd.tod-online.com

    // changed to fit the anpera.net code

    $session[user][reputation]+=2;

    //check_align();

    output("`GDu verbringst einen groÃŸen Teil des Tages damit, diesem gefallenen Abenteuerer ein Grab zu Schaufeln und ihm ein ordentliches BegrÃ¤bnis zukommen zu lassen. ");

    output("Du verzichtest auf Reichtum, wenn du ihn stehlen mÃ¼sstest - erst Recht von Toten.`n ");

    output("`n`nAuÃŸerdem willst du nicht die Rache der Toten hinter dir wissen, zusÃ¤tzlich zu den Kreaturen des Waldes.`n`n");

    output("Als du endlich mit dem Grab fertig bist, erscheint eine kleine Fee vor dir. \"`#Das war sehr edel, was du getan hast. ");

    output(" Ich werde dich dafÃ¼r belohnen.`G\"`n`n");

    $reward = rand(1,12);

    switch ($reward) {

        case 1:

        case 2:

        case 3:

            output("Die Fee gibt dir einen Edelstein!");

            // debuglog("received one gem for burying a corpse");

            $session[user][gems]++;

            break;

        case 4:

        case 5:

        case 6:

            $cash = rand(($session[user][level]*20),($session[user][level]*40));

            output("Die Fee gibt dir $cash Gold!");

            // debuglog("received $cash gold for burying a corpse");

            $session[user][gold]+=$cash;

            break;

        case 7:

        case 8:

        case 9:

            $sql="SELECT id,name,value1 FROM items WHERE class='Fluch' AND owner=".$session[user][acctid]." ORDER BY id LIMIT 1";

            $result=db_query($sql);

            if (db_num_rows($result)>0){

                  $row = db_fetch_assoc($result);

                output("Die Fee befreit dich von `\$$row[name]`G.");

                if ($row[name]=="Der Ring") $session[user][maxhitpoints]+=$row[value1];

                db_query("DELETE FROM items WHERE id=$row[id]");

            }else{

                output("Die Fee segnet dich.");

                $session['bufflist']['segen2'] = array("name"=>"`GSegen","rounds"=>8,"wearoff"=>"`GDer Segen wirkt nicht mehr.","dmgmod"=>1.1,"roundmsg"=>"`9Der Segen der Grabfee gibt dir Kraft.","activate"=>"offense");

            }

            break;

        default:

            output("Du fÃ¼hlst dich gut bei dem, was du getan hast.`n");

            output("Du bekommst mehr Charme!");

            // debuglog("gained two charm for burying a corpse");

            $session[user][charm]+=e_rand(1,2);

            break;

    }

    $session['user']['specialinc']="";

} elseif ($HTTP_GET_VARS['op']=="leave"){

    output("`GIrgendetwas hat diese Person getÃ¶tet und dieses Etwas kÃ¶nnte immer noch hier sein. Es ist sicherer, schnell zu verschwinden.`n`n ");

    $session[user][specialinc]="";

} elseif ($HTTP_GET_VARS['op']=="desecrate"){

    $session['user']['turns']--;

    output("`GDu durchwÃ¼hlst die Sachen der Leiche nach etwas Brauchbarem.`n");

    // The following line is specific to lgd.tod-online.com

    // changed for anpera.net code

    $session[user][reputation]-=2;

    switch (rand(1,8)) {

        case 1:

        case 2:

            $gem_gain = rand(1,3);

            $gold_gain = rand($session[user][level]*10,$session[user][level]*20);

            $gemword = "Edelsteine";

            if ($gem_gain == 1) $gemword = "Edelstein";

            output("Die Leiche zu plÃ¼ndern hat sich bezahlt gemacht! Du hast $gem_gain $gemword ");

            output("und $gold_gain GoldstÃ¼cke gefunden.`n`n");

            $session[user][gems]+=$gem_gain;

            $session[user][gold]+=$gold_gain;

            $session[user][specialinc]="";

            //debuglog("stole $gem_gain gems and $gold_gain gold from a corpse");

            break;

        case 3:

        case 4:

            output("Du durchsuchst die Leiche nach Reichtum, aber alles, was du finden kannst, sind vermoderte Lumpen und verrostete Waffen. ");

            output("Vielleicht hast du bei der nÃ¤chsten Leiche mehr GlÃ¼ck.`n`n");

            $session[user][specialinc]="";

            //debuglog("looted a corpse and found nothing");

            break;

        default:

            output("Als du die Leiche nach ReichtÃ¼mern durchwÃ¼hlst, lenkt eine plÃ¶tzliche Bewegung deine Aufmerksamkeit auf sich. Du machst einen Satz zurÃ¼ck und ");

            output("siehst, wie sich dieses tote Skelett aufrappelt. Seine leeren AugenhÃ¶hlen staren dich mit einem roten GlÃ¼hen an. ");

            output("Wenn es reden kÃ¶nnte, wÃ¼rde es dich sicher verfluchen, aber schon das kranke SchleifgerÃ¤usch von Knochen auf Knochen lÃ¤sst es dir eiskalt den RÃ¼cken runterlaufen. ");

            output("`n`n");

            $badguy = array(

                            "creaturename"=>"`\$Skelettkrieger`0",

                            "creaturelevel"=>$session[user][level]+1,

                            "creatureweapon"=>"Verrostetes Schwert",

                            "creatureattack"=>$session['user']['attack']+1,

                            "creaturedefense"=>$session['user']['defence']+1,

                            "creaturehealth"=>round($session['user']['maxhitpoints']*1.05,0), 

                            "diddamage"=>0);

            $session['user']['badguy']=createstring($badguy);

                    $session['user']['specialinc']="remains.php";

            $HTTP_GET_VARS['op']="fight";

            break;

        }

    }

if ($HTTP_GET_VARS[op]=="run"){

    if (e_rand(1,5) == 1){

        output ("`c`b`&Deine Flucht vor der untoten Bedrohung war erfolgreich!`0`b`c`n");

        $session[user][reputation]--;

        $HTTP_GET_VARS[op]="";

    }else{

        output("`c`b`\$Es gelingt dir nicht, vor der untoten Bedrohung davon zu laufen!`0`b`c");

    }

}

if ($HTTP_GET_VARS['op']=="fight"){

    $battle=true;

}

if ($battle) {

    include("battle.php");

        $session['user']['specialinc']="remains.php";

        if ($victory){

                $badguy=array();

                    $session['user']['badguy']="";

                    output("`n`GNach einem heftigen Kampf hast du den Skelettkrieger besiegt. Du hoffst, dass er vielleicht jetzt ");

                   output("endlich seine ewige Ruhe finden wird.`n`n");

                    output("Oder wenigstens, bis der nÃ¤chste Abenteurer auf der Suche nach dem schnellen Geld Ã¼ber seine Leiche stolpert.`n`n");

                    // debuglog("defeated a skeletal warrior after disturbing it");

            if (rand(1,2)==1) {

                $gem_gain = rand(1,3);

                $gold_gain = rand($session[user][level]*10,$session[user][level]*20);

                $gemword = "Edelsteine";

                if ($gem_gain == 1) $gemword="Edelstein";

                output("Nach deinem Sieg nimmst du dir, was die deiner Meinung nach zusteht. Du findest $gem_gain $gemword ");

                output("und $gold_gain GoldstÃ¼cke.`n`n");

                $session['user']['gems']+=$gem_gain;

                $session['user']['gold']+=$gold_gain;

            } else {

                output("Trotz allem findest du nichts, was die MÃ¼he wert gewesen wÃ¤re.`n`n");

            }

            $exp_gain=($session[user][level]+1)*20;

            output("Du bekommst $exp_gain Erfahrungspunkte.`n`n");

            $session[user][experience]+=$exp_gain;

            $session['user']['specialinc']="";

            } elseif ($defeat){

                    $badguy=array();

                    $session[user][badguy]="";

                    //debuglog("was killed by a Skeletal Warrior.");

                    output("`n`GDu wurdest vom Skelettkrieger besiegt! Doch anstatt im Totenreich aufzuwachen, bist du immer noch am Leben!`n`nDu verlierst 2% deiner Erfahrung".($session[user][gems]?" und vermisst plÃ¶tzlich einen deiner Edelsteine":"").". ");

        output("`nAuÃŸerdem scheint ein schwerer Fluch vom Skelettkrieger auf dich Ã¼bergesprungen zu sein. Ob das seine Todesursache war? ");

        if ($session[user][gems]) $session[user][gems]--;

        // Don't take away live and wellnes ... just curse the player ;)

        // addnav("Daily news","news.php");

        // $session[user][alive]=false;

        // $session[user][gold]=0;

        $session[user][hitpoints]=1;

        $session[user][experience]=round($session[user][experience]*.98,0);

        $session[user][specialinc]="";

        $result=db_query("SELECT * FROM items WHERE class='Fluch.Prot' ORDER BY rand(".e_rand().") LIMIT 1");

        $row = db_fetch_assoc($result);

        if (strlen($row[buff])>8){

            $row2[buff]=unserialize($row[buff]);

            $session[bufflist][$row2[buff][name]]=$row2[buff];

        }

        db_query("INSERT INTO items (name,owner,class,value1,value2,gold,gems,description,hvalue,buff) VALUES ('".$row[name]."',".$session[user][acctid].",'Fluch',$row[value1],$row[value2],$row[gold],$row[gems],'".$row[description]."',$row[hvalue],'".$row[buff]."')");

        addnews("`G".$session[user][name]."`G kam dem Tod nÃ¤her, als ".($session[user][sex]?"ihr":"ihm")." lieb war.");

    } else {

        fightnav(true,true);

    }

}

?>



