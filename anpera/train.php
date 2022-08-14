
ï»¿<?php



// 23112005



require_once("common.php");

checkday();

page_header("Blusprings Trainingslager fÃ¼r Krieger");



output("`b`cBlusprings Trainingslager fÃ¼r Krieger`c`b");

// multivillages mod

if (isset($_GET['mv']) && $_GET['mv']!=$session['user']['specialmisc']) $session['user']['specialmisc']=$_GET['mv'];

$village=($session['user']['specialmisc']>0)?"tradervillages.php?village={$session['user']['specialmisc']}":"village.php";



$sql = "SELECT * FROM masters WHERE creaturelevel = ".$session['user']['level'];

$result = db_query($sql) or die(sql_error($sql));

if (db_num_rows($result) > 0){

    $master = db_fetch_assoc($result);

    if ($master[creaturename] == "Gadriel the Elven Ranger" && $session[user][race] == 2) {

        $master[creaturewin] = "Sowas nennt sich Elf?? Halb-Elf hÃ¶chstens! Komm wieder, wenn du mehr trainiert hast.";

        $master[creaturelose] = "Es ist nur passend, dass ein anderer Elf sich mit mir messen konnte. Du machst gute Fortschritte.";

    }

    $level = $session[user][level];

    //$exprequired=((pow((($level-1)/15),3)*3+1)*100*$level);

    //$exparray=array(1=>100,400,602,1012,1540,2207,3041,4085,5395,7043,9121,11740,15037,19171,24330);

//    $exparray=array(1=>100,300,602,1012,1540,2207,3041,4085,5395,7043,9121,11740,15037,19171,24330);

    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930);

    while (list($key,$val)=each($exparray)){

        $exparray[$key]= round(

            $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100

        ,0);

    }

    $exprequired=$exparray[$session[user][level]];

    //output("`\$Exp Required: $exprequired; exp possessed: ".$session[user][experience]."`0`n");



    if ($HTTP_GET_VARS[op]==""){

        output("Der Klang von Kampf umfÃ¤ngt dich. Das Geklirr von Waffen in mÃ¤chtigen KÃ¤mpfen lÃ¤sst dein Kriegerherz hÃ¶her schlagen. ");

        output("Das alte GebÃ¤ude hinter dem Ãœbungsplatz ist die groÃŸe Akademie der geheimen KÃ¼nste.");

        output("`n`nDein Meister ist `^$master[creaturename]`0.");

        if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

        addnav("Meister befragen","train.php?op=question");

        addnav("Meister herausfordern","train.php?op=challenge");

        /*

        if ($session['user']['superuser'] > 2) {

            addnav("Superuser Level erhÃ¶hen","train.php?op=challenge&victory=1");

        }

        */

        addnav("ZurÃ¼ck zum Dorf",$village);

    }else if($HTTP_GET_VARS[op]=="challenge"){

        if ($HTTP_GET_VARS['victory']) {

            $victory=true;

            $defeat=false;

            if ($session['user']['experience'] < $exprequired)

                $session['user']['experience'] = $exprequired;

            // $session['user']['seenmaster'] = 0;

            if ($session['user']['seenmaster']==2){

                $session['user']['seenmaster']=1;

            }else{

                $session['user']['seenmaster']=0;

            }

        }

        if ($session[user][seenmaster]==1 || ($session[user][seenmaster]>=1 && getsetting("multimaster",1)==1)){

            output("Du bist der Meinung, dass du heute vielleicht schon genug von deinem Meister hast. Die Lektion, die du heute gelernt hast, hÃ¤lt dich davon ab, dich nochmal so bereitwillig ");

            output("einer derartigen DemÃ¼tigung zu unterwerfen.");

            addnav("ZurÃ¼ck zum Dorf",$village);

            if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

        }else{

            if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/bigbong.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

            if (getsetting("multimaster",1)==0) $session['user']['seenmaster'] = 1;

            if ($session[user][experience]>=$exprequired){

                $atkflux = e_rand(0,$session['user']['dragonkills']);

                $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux));

                $hpflux = ($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5;

                $master['creatureattack']+=$atkflux;

                $master['creaturedefense']+=$defflux;

                $master['creaturehealth']+=$hpflux;

                $session[user][badguy]=createstring($master);



                $battle=true;

                if ($victory) {

                    $badguy = createarray($session['user']['badguy']);

                    output("Mit einem Wirbelsturm aus SchlÃ¤gen schlÃ¤gst du deinen Meister nieder.`n");

                }

            }else{

                output("Du machst dich mit ".$session[user][weapon]." und ".$session[user][armor]." bereit und nÃ¤herst dich Meister `^$master[creaturename]`0.`n`nEine kleine Menge Zuschauer ");

                output("hat sich versammelt und du bemerkst das Grinsen in ihren Gesichtern. Aber du fÃ¼hlst dich selbstsicher. Du verneigst dich vor `^$master[creaturename]`0 und fÃ¼hrst ");

                output("einen perfekten Drehangriff aus, nur um zu bemerken, dass du NICHTS in den HÃ¤nden hast!  `^$master[creaturename]`0 steht vor dir - mit deiner Waffe in der Hand.  ");

                output("Kleinlaut nimmst du ".$session[user][weapon]." entgegen und schleichst unter dem schallenden GelÃ¤chter der Zuschauer vom Trainingsplatz.");

                addnav("ZurÃ¼ck zum Dorf",$village);

                if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

                $session[user][seenmaster]=1;

            }

        }

    }else if($HTTP_GET_VARS[op]=="question"){

        output("Furchtsam nÃ¤herst du dich `^$master[creaturename]`0, um ihn zu fragen, ob du bereits in der selben Klasse wie er kÃ¤mpfst.");

        if($session[user][experience]>=$exprequired){

            output("`n`n`^$master[creaturename]`0 sagt: \"Gee, deine Muskeln werden ja grÃ¶ÃŸer als meine...\"");

        }else{

            output("`n`n`^$master[creaturename]`0  stellt fest, dass du noch mindestens `%".($exprequired-$session[user][experience])."`0 Erfahrungspunkte mehr brauchst, bevor du bereit bist, ihn zu einem Kampf herauszufordern.");

        }

        if ($session[user][reputation]>20) output("`nAuÃŸerdem ist $master[creaturename] von deinem ausgezeichneten Ruf begeistert.");

        if ($session[user][reputation]<-20) output("`n$master[creaturename] zeigt sich sehr enttÃ¤uscht von deinem Verhalten als KÃ¤mpfer in der Welt.");

        if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

        addnav("Meister befragen","train.php?op=question");

        addnav("Meister herausfordern","train.php?op=challenge");

/*

        if ($session['user']['superuser'] > 2) {

            addnav("Superuser Level erhÃ¶hen","train.php?op=challenge&victory=1");

        }

*/

        addnav("ZurÃ¼ck zum Dorf",$village);

    }else if($_GET['op']=="autochallenge"){

        addnav("Gegen den Meister antreten","train.php?op=challenge");

        output("`^{$master['creaturename']}`0 ist deine Tapferkeit als Krieger zu Ohren gekommen und er hat GerÃ¼chte gehÃ¶rt, dass du glaubst,

        du bist so viel mÃ¤chtiger als er, dass du nicht einmal gegen ihn kÃ¤mpfen mÃ¼sstest, um irgendetwas zu beweisen. Das hat sein Ego

        verstÃ¤ndlicherweise verletzt. So hat er sich aufgemacht, dich zu finden.  `^{$master['creaturename']}`0 fordert einen sofortigen

        Kampf von dir und dein eigener Stolz hindert dich daran, seine Forderung abzulehnen.");

        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){

            output("`n`nAls fairer KÃ¤mpfer gibt dir dein Meister vor dem Kampf einen Heiltrank.");

            $session['user']['hitpoints']=$session['user']['maxhitpoints'];

        }

        $session[user][reputation]-=2;

        if ($session[user][seenmaster]==1) $session[user][seenmaster]=2;

        addnews("`3{$session['user']['name']}`3 wurde von Meister `^{$master['creaturename']}`3 wegen Ãœberheblichkeit gejagt und gestellt.");

    }

    if ($HTTP_GET_VARS[op]=="fight"){

        $battle=true;

    }

    if ($HTTP_GET_VARS[op]=="run"){

        output("`\$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen!`0");

        $HTTP_GET_VARS[op]="fight";

        $battle=true;

    }



    if($battle){

        if (count($session[bufflist])>0 && is_array($session[bufflist]) || $HTTP_GET_VARS[skill]!=""){

            $HTTP_GET_VARS[skill]="";

            if ($HTTP_GET_VARS['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);

            $session[bufflist]=array();

            output("`&Dein Stolz verbietet es dir, wÃ¤hrend des Kampfes Gebrauch von deinen besonderen FÃ¤higkeiten zu machen!`0");

        }

        if (!$victory) include("battle.php");

        if ($victory){

            //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);

            $search=array(    "%s",

                                            "%o",

                                            "%p",

                                            "%X",

                                            "%x",

                                            "%w",

                                            "%W"

                                        );

            $replace=array(    ($session[user][sex]?"sie":"ihn"),

                                            ($session[user][sex]?"sie":"er"),

                                            ($session[user][sex]?"ihr":"sein"),

                                            ($session[user][weapon]),

                                            $badguy[creatureweapon],

                                            $badguy[creaturename],

                                            $session[user][name]

                                        );

            $badguy[creaturelose]=str_replace($search,$replace,$badguy[creaturelose]);



            output("`b`&$badguy[creaturelose]`0`b`n");

            output("`b`\$Du hast deinen Meister $badguy[creaturename] bezwungen!`0`b`n");

            if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/cheer.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

            $session[user][level]++;

            $session[user][maxhitpoints]+=10;

            $session[user][soulpoints]+=5;

            $session[user][attack]++;

            $session[user][defence]++;

            $session[user][seenmaster]=0;

            $session[user][reputation]+=3;

            output("`#Du steigst auf zu Level `^".$session[user][level]."`#!`n");

            output("Deine maximalen Lebenspunkte sind jetzt `^".$session[user][maxhitpoints]."`#!`n");

            output("Du bekommst einen Angriffspunkt dazu!`n");

            output("Du bekommst einen Verteidigungspunkt dazu!`n");

            if ($session['user']['level']<15){

                output("Du hast jetzt einen neuen Meister.`n");

            }else{

                output("Keiner im Land ist mÃ¤chtiger als du!`n");

            }

            if ($session['user']['referer']>0 && $session['user']['level']>=5 && $session['user']['refererawarded']<1){

                $sql = "UPDATE accounts SET donation=donation+50 WHERE acctid={$session['user']['referer']}";

                db_query($sql);

                $session['user']['refererawarded']=1;

                systemmail($session['user']['referer'],"`%Eine deiner Anwerbungen hat's geschafft!`0","`%{$session['user']['name']}`# ist auf Level `^{$session['user']['level']}`# aufgestiegen und du hast deine `^50`# Punkte bekommen!");

            }

            if ($session['user']['level']==10){

                $session['user']['donation']+=1;

            }

            increment_specialty();

            if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

            addnav("Meister befragen","train.php?op=question");

            addnav("Meister herausfordern","train.php?op=challenge");

/*

            if ($session['user']['superuser'] > 2) {

                addnav("Superuser Level erhÃ¶hen","train.php?op=challenge&victory=1");

            }

*/

            addnav("ZurÃ¼ck zum Dorf",$village);

            addnews("`%".$session[user][name]."`3 hat ".($session[user][sex]?"ihren":"seinen")." Meister `%$badguy[creaturename]`3 an ".($session[user][sex]?"ihrem":"seinem")." `^".ordinal($session[user][age])."`3 Tag besiegt und steigt auf Level `^".$session[user][level]."`3 auf!!");

            $badguy=array();

            $session[user][hitpoints] = $session[user][maxhitpoints];

            $sql="SELECT acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";

            $result = db_query($sql) or die(db_error(LINK));

            $row = db_fetch_assoc($result);

            if($row[acctid2]==$session[user][acctid] && $row[turn]==0){

                output("`n`6`bDu kannst die offene Herausforderung in der Arena jetzt nicht mehr annehmen.`b");

                $sql = "DELETE FROM pvp WHERE acctid2=".$session[user][acctid]." AND turn=0";

                db_query($sql) or die(db_error(LINK));

            }

            //$session[user][seenmaster]=1;

        }else{

            if($defeat){

                //addnav("Daily news","news.php");

                $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";

                $result = db_query($sql) or die(db_error(LINK));

                $taunt = db_fetch_assoc($result);

                $taunt = str_replace("%s",($session['user']['sex']?"ihm":"ihr"),$taunt[taunt]);

                $taunt = str_replace("%o",($session['user']['sex']?"er":"sie"),$taunt);

                $taunt = str_replace("%p",($session['user']['sex']?"sein":"ihr"),$taunt);

                $taunt = str_replace("%x",($session[user][weapon]),$taunt);

                $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);

                $taunt = str_replace("%W",$badguy[creaturename],$taunt);

                $taunt = str_replace("%w",$session[user][name],$taunt);



                addnews("`%".$session[user][name]."`5 hat Meister $badguy[creaturename] herausgefordert und verloren!`n$taunt");

                //$session[user][alive]=false;

                //$session[user][gold]=0;

                $session[user][hitpoints]=$session[user][maxhitpoints];

                output("`&`bDu wurdest von `%$badguy[creaturename]`& besiegt!`b`n");

                output("`%$badguy[creaturename]`\$ hÃ¤lt vor dem vernichtenden Schlag inne und reicht dir stattdessen seine Hand, um dir auf die Beine zu helfen. Er verabreicht dir einen kostenlosen Heiltrank.`n");

                $search=array(    "%s",

                                                "%o",

                                                "%p",

                                                "%x",

                                                "%X",

                                                "%W",

                                                "%w"

                                            );

                $replace=array(    ($session['user']['sex']?"ihm":"ihr"),

                                                ($session['user']['sex']?"er":"sie"),

                                                ($session['user']['sex']?"sein":"ihr"),

                                                ($session[user][weapon]),

                                                $badguy[creatureweapon],

                                                $badguy[creaturename],

                                                $session[user][name]

                                            );

                $badguy[creaturewin]=str_replace($search,$replace,$badguy[creaturewin]);

                output("`^`b$badguy[creaturewin]`b`0`n");

                if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

                addnav("Meister befragen","train.php?op=question");

                addnav("Meister herausfordern","train.php?op=challenge");

/*

                if ($session['user']['superuser'] > 2) {

                    addnav("Superuser Level erhÃ¶hen","train.php?op=challenge&victory=1");

                }

*/

                addnav("ZurÃ¼ck zum Dorf",$village);

                //$session[user][seenmaster]=1;

                if ($session['user']['seenmaster']!=2) $session['user']['seenmaster']=1;

            }else{

              fightnav(false,false);

            }

        }

    }

}else{

  output("Du bummelst Ã¼ber den Ãœbungsplatz. JÃ¼ngere Krieger drÃ¤ngen sich zusammen und deuten auf dich, als du vorÃ¼ber lÃ¤ufst.  ");

    output("Du kennst diesen Platz gut. Bluspring grÃ¼ÃŸt dich und du gibst ihr einen starken HÃ¤ndedruck. AuÃŸer Erinnerungen ");

    output("gibt es hier nichts mehr fÃ¼r dich. Du bleibst noch eine Weile und siehst den jungen Kriegern beim Training zu, bevor du ");

    output("zum Dorf zurÃ¼ckkehrst.");

    if ($village=="village.php") addnav("Warchilds Akademie","academy.php");

    addnav("ZurÃ¼ck zum Dorf",$village);

}

page_footer();

?>

