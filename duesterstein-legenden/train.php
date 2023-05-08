
<?
require_once "common.php";
if ($session[user][locate]!=17){
    $session[user][locate]=17;
    redirect("train.php");
}
checkday();
page_header("Bluspring's Trainingslager für Krieger");

output("`b`cBluspring's Trainingslager für Krieger`c`b");
$sql = "SELECT * FROM masters WHERE creaturelevel = ".$session[user][level];
$result = db_query($sql) or die(sql_error($sql));
if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    if ($master[creaturename] == "Gadriel the Elven Ranger" && $session[user][race] == 2) {
        $master[creaturewin] = "Sowas nennt sich Elf?? Halb-Elf höchstens! Komm wieder, wenn du mehr trainiert hast.";
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
        output("Der Klang von Kampf umfängt dich. Das Geklirr von Waffen in mächtigen Kämpfen lässt dein Kriegerherz höher schlagen. ");
        output("`n`nDein Meister ist `^$master[creaturename]`0.");
        addnav("Meister befragen","train.php?op=question");
        addnav("Meister herausfordern","train.php?op=challenge");
        if ($session['user']['superuser'] >= 4) {
            addnav("Superuser Level erhöhen","train.php?op=challenge&victory=1");
        }
        addnav("Zurück zum Dorf","village.php");
    }else if($HTTP_GET_VARS[op]=="challenge"){
        if ($HTTP_GET_VARS['victory']) {
            $victory=true;
            $defeat=false;
            if ($session['user']['experience'] < $exprequired)
                $session['user']['experience'] = $exprequired;
            $session['user']['seenmaster'] = 0;
        }
        if ($session[user][seenmaster]){
            output("Du bist der Meinung, dass du heute vielleicht schon genug von deinem Meister hast. Die Lektion, die du heute gelernt hast, hält dich davon ab, dich nochmal so bereitwillig ");
            output("einer derartigen Demütigung zu unterwerfen.");
            addnav("Zurück zum Dorf","village.php");
        }else{
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
                    output("Mit einem Wirbelsturm aus Schlägen schlägst du deinen Meister nieder.`n");
                }
            }else{
                output("Du machst dich mit ".$session[user][weapon]." und ".$session[user][armor]." bereit und näherst dich Meister `^$master[creaturename]`0.`n`nEine kleine Menge Zuschauer ");
                output("hat sich versammelt und du bemerkst das grinsen in ihren Gesichtern. Aber du fühlst dich selbstsicher. Du verbeugst dich vor `^$master[creaturename]`0, und führst ");
                output("einen perfekten Drehangriff aus, nur um zu bemerken, dass du NICHTS in den Händen hast!  `^$master[creaturename]`0 steht vor dir - mit deiner Waffe in der Hand. ");
                output("Kleinlaut nimmst du ".$session[user][weapon].", entgegen und schleichst unter dem schallenden Gelächter der Zuschauer vom Trainingsplatz.");
                addnav("Zurück zum Dorf.","village.php");
                $session[user][seenmaster]=1;
            }
        }
    }else if($HTTP_GET_VARS[op]=="question"){
        output("Furchtsam näherst du dich `^$master[creaturename]`0 um ihn zu fragen, ob du bereits in der selben Klasse wie er kämpfst.");
        if($session[user][experience]>=$exprequired){
            output("`n`n`^$master[creaturename]`0 sagt: \"Gee, deine Muskeln werden ja grösser als meine...\"");
        }else{
            output("`n`n`^$master[creaturename]`0 ist der Meinung, dass du noch mindestens `5".($exprequired-$session[user][experience])."`0 Erfahrungspunkte mehr brauchst, bevor du bereit bist, ihn zu einem Kampf herauszufordern. ");
        }
        addnav("Meister befragen","train.php?op=question");
        addnav("Meister herausfordern","train.php?op=challenge");
        if ($session['user']['superuser'] > 2) {
            addnav("Superuser Level erhöhen","train.php?op=challenge&victory=1");
        }
        addnav("Zurück zum Dorf","village.php");
    }else if($_GET['op']=="autochallenge"){
        addnav("Gegen den Meister antreten","train.php?op=challenge");
        output("`^{$master['creaturename']}`0 ist deine Tapferkeit als Krieger zu Ohren gekommen, und er hat Gerüchte gehört, dass du glaubst, 
        du bist so viel mächtiger als er, dass du nichteinmal gegen ihn kämpfen müsstest, um irgendetwas zu beweisen. Das hat sein Ego 
        verständlicherweise verletzt. So hat er sich aufgemacht, dich zu finden. `^{$master['creaturename']}`0 fordert einen sofortigen 
        Kampf von dir und dein eigener Stolz hindert dich daran, seine Forderung abzulehnen.");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
            output("`n`nAls fairer Kämpfer gibt dir dein Meister vor dem Kapmf einen Heiltrank.");
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
        addnews("`3{$session['user']['name']}`3 wurde von Meister `^{$master['creaturename']}`3 wegen Überheblichkeit gejagt und gestellt.");
    }
    if ($HTTP_GET_VARS[op]=="fight"){
        $battle=true;
    }
    if ($HTTP_GET_VARS[op]=="run"){
        output("`\$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen! `0");
        $HTTP_GET_VARS[op]="fight";
        $battle=true;
    }
    
    if($battle){
        if (count($session[bufflist])>0 && is_array($session[bufflist]) || $HTTP_GET_VARS[skill]!=""){
            $HTTP_GET_VARS[skill]="";
            if ($HTTP_GET_VARS['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session[bufflist]=array();
            output("`&Dein Stolz verbietet es dir, während des Kampfes Gebrauch von deinen besonderen Fähigkeiten zu machen. `0");
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
            $replace=array(    ($session[user][sex]?"ihr":"sein"),
                                            ($session[user][sex]?"sie":"er"),
                                            ($session[user][sex]?"ihr":"sein"),
                                            ($session[user][weapon]),
                                            $badguy[creatureweapon],
                                            $badguy[creaturename],
                                            $session[user][name]
                                        );
            $badguy[creaturelose]=str_replace($search,$replace,$badguy[creaturelose]);
    
            output("`b`&$badguy[creaturelose]`0`b`n"); 
            output("`b`\$Du bezwingst deinen Meister $badguy[creaturename]!`0`b`n");

            $session[user][level]++;
            $session[user][maxhitpoints]+=10;
            $session[user][soulpoints]+=5;
            $session[user][attack]++;
            $session[user][defence]++;
            $session[user][seenmaster]=0;
            output("`#Du steigst auf zu Level `^".$session[user][level]."`#!`n");
            output("Deine maximalen Lebenspunkte sind jetzt `^".$session[user][maxhitpoints]."`#!`n");
            output("Du bekommst einen Angriffspunkt dazu!`n");
            output("Du bekommst einen Verteidigungspunkt dazu!`n");
            if ($session['user']['level']<15){
                output("Du hast jetzt einen neuen Meister.`n");
            }else{
                output("Keiner im Land ist mächtiger als du!`n");
            }
            if ($session['user']['referer']>0 && $session['user']['level']>=4 && $session['user']['refererawarded']<1){
                $sql = "UPDATE accounts SET donation=donation+25 WHERE acctid={$session['user']['referer']}";
                db_query($sql);
                $session['user']['refererawarded']=1;
                systemmail($session['user']['referer'],"`%Einer Deiner geworbenen ist aufgestiegen!`0","`%{$session['user']['name']}`# ist auf Level `^{$session['user']['level']}`# aufgestiegen, und Du bekommst `^25`# Donation Punkte!");
            }
            increment_specialty();
            $sql="SELECT acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid].""; 
            $result = db_query($sql) or die(db_error(LINK)); 
            $row = db_fetch_assoc($result); 
            if($row[acctid2]==$session[user][acctid] && $row[turn]==0){ 
                output("`n`6`bDu kannst die offene Herausforderung in der Arena jetzt nicht mehr annehmen.`b"); 
                $sql = "DELETE FROM pvp WHERE acctid2=".$session[user][acctid]." AND turn=0"; 
                db_query($sql) or die(db_error(LINK)); 
            }

            addnav("Meister befragen","train.php?op=question");
            addnav("Meister herausfordern","train.php?op=challenge");
            if ($session['user']['superuser'] > 2) {
                addnav("Superuser Level erhöhen","train.php?op=challenge&victory=1");
            }
            addnav("Zurück zum Dorf","village.php");
            addnews("`%".$session[user][name]."`3 hat ".($session[user][sex]?"ihren":"seinen")." Meister `%$badguy[creaturename]`3 besiegt und steigt auf Level `^".$session[user][level]."`3 an ".($session[user][sex]?"ihrem":"seinem")." `^".ordinal($session[user][age])."`3 Tag auf!!");
            $badguy=array();
            $session[user][hitpoints] = $session[user][maxhitpoints];
            //$session[user][seenmaster]=1;
        }else{
            if($defeat){
                //addnav("Daily news","news.php");
                $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $taunt = db_fetch_assoc($result);
                $taunt = str_replace("%s",($session[user][gender]?"sein":"ihr"),$taunt[taunt]);
                $taunt = str_replace("%o",($session[user][gender]?"er":"sie"),$taunt);
                $taunt = str_replace("%p",($session[user][gender]?"sein":"ihr"),$taunt);
                $taunt = str_replace("%x",($session[user][weapon]),$taunt);
                $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
                $taunt = str_replace("%W",$badguy[creaturename],$taunt);
                $taunt = str_replace("%w",$session[user][name],$taunt);
                
                addnews("`%".$session[user][name]."`5 hat ".($session[user][sex]?"ihren":"seinen")." Meister $badguy[creaturename] herausgefordert und verloren!`n$taunt");
                //$session[user][alive]=false;
                //$session[user][gold]=0;
                $session[user][hitpoints]=$session[user][maxhitpoints];
                output("`&`bDu wurdest besiegt von `%$badguy[creaturename]`&!`b`n");
                output("`%$badguy[creaturename]`\$ hält vor dem vernichtenden Schlag inne und reicht dir stattdessen seine Hand, um dir auf die Beine zu helfen. Er verabreicht dir einen kostenlosen Heiltrank.`n");
                $search=array(    "%s",
                                                "%o",
                                                "%p",
                                                "%x",
                                                "%X",
                                                "%W",
                                                "%w"
                                            );
                $replace=array(    ($session[user][gender]?"sein":"ihr"),
                                                ($session[user][gender]?"er":"sie"),
                                                ($session[user][gender]?"sein":"ihr"),
                                                ($session[user][weapon]),
                                                $badguy[creatureweapon],
                                                $badguy[creaturename],
                                                $session[user][name]
                                            );
                $badguy[creaturewin]=str_replace($search,$replace,$badguy[creaturewin]);
                output("`^`b$badguy[creaturewin]`b`0`n");
                addnav("Meister befragen","train.php?op=question");
                addnav("Meister herausfordern","train.php?op=challenge");
                if ($session['user']['superuser'] > 2) {
                    addnav("Superuser Level erhöhen","train.php?op=challenge&victory=1");
                }
                addnav("Zurück zum Dorf","village.php");
                $session[user][seenmaster]=1;
            }else{
              fightnav(false,false);
            }
        }
    }
}else{
  output("Du bummelst über den Übungsplatz. Jüngere Krieger drängen sich zusammen und deuten auf dich, als du vorüber läufst.  ");
    output("Du kennst diesen Platz gut. Bluspring grüßt dich und du gibts ihr einen starken Händedruck. Außer Erinnerungen ");
    output("gibt es hier nichts mehr für dich. Du bleibst noch eine Weile und siehst den jungen Kriegern beim Training zu, bevor du ");
    output("zum Dorf zurückkehrst. ");
    addnav("Zurück zum Dorf","village.php");
}
page_footer();
?>


