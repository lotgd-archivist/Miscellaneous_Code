
<?php

// 14072004

require_once "common.php";
checkday();
page_header("Brydens Trainingslager");

function train_navi () {
    global $session;

    addnav('Training');
    addnav("Meister befragen","train.php?op=question");
    if($session['user']['level'] < 15) {addnav("Meister herausfordern","train.php?op=challenge");}

    if($session['user']['dragonkills'] >= 10) {
        addnav("Schlosstraining");
        if($session['user']['turns'] >= getsetting("wk_castle_turns",6)) {addnav("1 Schlossrunde für ".getsetting("wk_castle_turns",6)." Waldkämpfe","train.php?op=change&what=turns");}
        if($session['user']['castleturns']) {addnav(getsetting("castle_turns_wk",4)." Waldkämpfe für 1 Schlossrunde","train.php?op=change&what=castleturns");}
    }
    knappentraining_link('train');
    addnav('Zurück');
    addnav("Zurück zur Stadt","village.php");
    
    if (su_check(SU_RIGHT_SULVL)) {
        addnav('Mod-Aktionen');
        addnav("Superuser Level erhöhen","train.php?op=challenge&victory=1", false, false, false, false);
    }
}

output("`b`c`°Brydens Trainingslager`c`b`n");
$sql = "SELECT * FROM masters WHERE creaturelevel = ".$session['user']['level'];
$result = db_query($sql) or die(sql_error($sql));
if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    if ($master['creaturename'] == "Gadriel the Elven Ranger" && $session['user']['race'] == 'elf') {
        $master['creaturewin'] = "Sowas nennt sich Elf?? Halbelf höchstens! Komm wieder, wenn du mehr trainiert hast.";
        $master['creaturelose'] = "Es ist nur passend, dass ein anderer Elf sich mit mir messen konnte. Du machst gute Fortschritte.";
    }

    $exprequired= get_exp_required($session['user']['level'],$session['user']['dragonkills']);
    
    if ($_GET['op']==""){
        output('`ôDas Klirren aufeinander schlagenden Eisens hallt dir schon aus weiter Ferne entgegen - bei wenig Trubel muss es sogar bereits vom Stadtplatz aus
                zu hören sein. Und das Tag für Tag, denn Krieger finden sich immer, die in dem großen, runden Areal gegen ihre Meister antreten und dadurch ihre
                Fertigkeiten weiter verbessern wollen. Bryden selbst steht gerade am Rand des Trainingsplatzes und sieht sich einen Übungskampf an. Die
                vermeintliche Ruhe, die der Mann mit dem kurzen, strohblonden Haar ausstrahlt, täuscht dich jedoch nicht: Wer es schafft und irgendwann gut genug
                ist, um diesen Kämpfer herauszufordern, sieht sich einem Gegner gegenüber, dessen Stärke und Schnelligkeit selbst die verfluchten grünen Echsen
                erzittern lässt.
                `nEtwas entfernt vom Rand des Trainingsplatzes wurde ein schmaler, etwa hüfthoher, fünfeckiger Pfahl als schlichtem Stein aufgestellt. Jede seiner
                Seiten ziert das Abbild eines schlichten Schwertes mit breiter Klinge, während ein Ring aus Metall auf die flache Pfahlspitze angebracht worden ist.
                Der Ring sieht seltsam abgegriffen aus. Ob es sich womöglich um einen Glücksbringer handelt? Vielleicht solltest du es ausprobieren und ihn ebenfalls vor deinem
                nächsten Kampf berühren, damit die Götter dir gewogen sind.
                `n`nDein Meister ist `^'.$master['creaturename'].'`ô.');
        
        train_navi();
                
    }
        
    else if($_GET['op']=="challenge"){
        if ($_GET['victory']) {
            $victory=true;
            $defeat=false;
            
            debuglog("`-Nutzte den Superuser-Button im Trainingslager");
            
            if ($session['user']['experience'] < $exprequired)
                $session['user']['experience'] = $exprequired;
            // $session['user']['seenmaster'] = 0;
            if ($session['user']['seenmaster']==2){
                $session['user']['seenmaster']=1;
            }else{
                $session['user']['seenmaster']=0;
            }
            //train_navi();
        }
        if ($session['user']['seenmaster']>=1 && !$_GET['auto']){
            output("`ôDu bist der Meinung, dass du heute vielleicht schon genug von deinem Meister hast. Die Lektion, die du heute gelernt hast, hält dich davon ab, dich nochmal so bereitwillig ");
            output("`ôeiner derartigen Demütigung zu unterwerfen.");
            train_navi();
            
        }else{
            if ($session['user']['prefs']['sounds']) output("<embed src=\"media/bigbong.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            if (getsetting("multimaster",1)==0 && $session['user']['seenmaster']!=2) $session['user']['seenmaster'] = 1;
            if ($session['user']['experience']>=$exprequired){
                                                
                $changeat = e_rand(2, min($session['user']['dragonkills'] * 0.3, 2));
                $changedef = e_rand(2, min($session['user']['dragonkills'] * 0.3, 2));
                $changehp = e_rand(2,12);
                                                
                $atkflux = (int)$session['user']['attack']*0.9+0.1*$changeat;
                $defflux = (int)$session['user']['defence']*0.9+0.1*$changedef;
                $hpflux = (int)($session['user']['maxhitpoints']*0.9+0.1*$changehp);
                $master['creatureattack']=$atkflux;
                $master['creaturedefense']=$defflux;
                $master['creaturehealth']=$hpflux;
                $session['user']['badguy']=createstring($master);
 
                $battle=true;
                if ($victory) {
                    $badguy = createarray($session['user']['badguy']);
                    output("Mit einem Wirbelsturm aus Schlägen schlägst du deinen Meister nieder.`n");
                    //train_navi();
                }
            }else{
                output("`ôDu machst dich mit ".$session['user']['weapon']." `ôund ".$session['user']['armor']." `ôbereit und näherst dich Meister `^{$master['creaturename']}`ô.`n`nEine kleine Menge Zuschauer ");
                output("`ôhat sich versammelt und du bemerkst das Grinsen in ihren Gesichtern. Aber du fühlst dich selbstsicher. Du verneigst dich vor `^{$master['creaturename']}`ô und führst ");
                output("`ôeinen perfekten Drehangriff aus, nur um zu bemerken, dass du NICHTS in den Händen hast!  `^{$master['creaturename']}`ô steht vor dir - mit deiner Waffe in der Hand.  ");
                output("`ôKleinlaut nimmst du ".$session['user']['weapon']." `ôentgegen und schleichst unter dem schallenden Gelächter der Zuschauer vom Trainingsplatz.");
                $session['user']['seenmaster']=1;
                train_navi();
            }
            
        }
    }else if($_GET['op']=="question"){
        output("`ôFurchtsam näherst du dich `^{$master['creaturename']}`ô, um ihn zu fragen, ob du bereits in der selben Klasse wie er kämpfst.");
        if($session['user']['experience']>=$exprequired){
            output("`n`n`^{$master['creaturename']}`ô sagt: `&\"Gee, deine Muskeln werden ja größer als meine...\"");
        }else{
            output("`n`n`^{$master['creaturename']}`ô stellt fest, dass du noch mindestens `I".($exprequired-$session['user']['experience'])."`ô Erfahrungspunkte mehr brauchst, bevor du bereit bist, ihn zu einem Kampf herauszufordern.");
        }
        if ($session['user']['reputation']>20) output("`nAußerdem ist {$master['creaturename']} von deinem ausgezeichneten Ruf begeistert.");
        if ($session['user']['reputation']<-20) output("`n{$master['creaturename']} zeigt sich sehr enttäuscht von deinem Verhalten als Kämpfer in der Welt.");
        
        train_navi();
        
    }else if($_GET['op']=="autochallenge"){
        addnav("Gegen den Meister antreten","train.php?op=challenge&auto=1");
        output("`^{$master['creaturename']}`ô ist deine Tapferkeit als Krieger zu Ohren gekommen und er hat Gerüchte gehört, dass du glaubst,
        du bist so viel mächtiger als er, dass du nicht einmal gegen ihn kämpfen müsstest, um irgendetwas zu beweisen. Das hat sein Ego 
        verständlicherweise verletzt. So hat er sich aufgemacht, dich zu finden.  `^{$master['creaturename']}`ô fordert einen sofortigen
        Kampf von dir und dein eigener Stolz hindert dich daran, seine Forderung abzulehnen.");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
            output("`n`nAls fairer Kämpfer gibt dir dein Meister vor dem Kampf einen Heiltrank.");
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
        $session['user']['reputation']-=2;
        if ($session['user']['seenmaster']==1) $session['user']['seenmaster']=2;
        //addnews("`3{$session['user']['name']}`3 wurde von Meister `^{$master['creaturename']}`3 wegen Überheblichkeit gejagt und gestellt.");
    }
    if ($_GET['op']=="fight"){
        $battle=true;
    }
    if ($_GET['op']=="run"){
        output("`\$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen!`0");
        $_GET['op']="fight";
        $battle=true;
    }
    
    if($battle){
        if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
            $_GET['skill']="";
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session['bufflist']=array();
            output("`&Dein Stolz verbietet es dir, während des Kampfes Gebrauch von deinen besonderen Fähigkeiten zu machen!`0");
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
            $replace=array(    ($session['user']['sex']?"sie":"ihn"),
                                            ($session['user']['sex']?"sie":"er"),
                                            ($session['user']['sex']?"ihr":"sein"),
                                            ($session['user']['weapon']),
                                            $badguy['creatureweapon'],
                                            $badguy['creaturename'],
                                            $session['user']['name']
                                        );
            $badguy['creaturelose']=str_replace($search,$replace,$badguy['creaturelose']);
    
            output("`b`&{$badguy['creaturelose']}`0`b`n");
            output("`b`\$Du hast deinen Meister {$badguy['creaturename']} bezwungen!`0`b`n");
            
            increment_level();
            
            train_navi();
                        
            addnews("`%".$session['user']['name']."`3 hat ".($session['user']['sex']?"ihren":"seinen")." Meister `%$badguy[creaturename]`3 an ".($session['user']['sex']?"ihrem":"seinem")." `^".ordinal_accusative($session['user'][age])."`3 Tagesabschnitt besiegt und steigt auf Level `^".$session['user']['level']."`3 auf!");
            $badguy=array();
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            $sql="SELECT acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if($row['acctid2']==$session['user']['acctid'] && $row['turn']==0){
                output("`n`6`bDu kannst die offene Herausforderung in der Arena jetzt nicht mehr annehmen.`b");
                $sql = "DELETE FROM pvp WHERE acctid2=".$session['user']['acctid']." AND turn=0";
                db_query($sql) or die(db_error(LINK));
            }
            //$session['user']['seenmaster']=1;
        }else{
            if($defeat){
                addnews("`%".$session['user']['name']."`5 hat Meister {$badguy['creaturename']} herausgefordert und verloren.");
                //$session['user'][alive]=false;
                //$session['user'][gold]=0;
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                output("`&`bDu wurdest von `%{$badguy['creaturename']}`& besiegt!`b`n");
                output("`%{$badguy['creaturename']}`\$ hält vor dem vernichtenden Schlag inne und reicht dir stattdessen seine Hand, um dir auf die Beine zu helfen. Er verabreicht dir einen kostenlosen Heiltrank.`n");
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
                                                ($session['user']['weapon']),
                                                $badguy['creatureweapon'],
                                                $badguy['creaturename'],
                                                $session['user']['name']
                                            );
                $badguy['creaturewin']=str_replace($search,$replace,$badguy['creaturewin']);
                output("`^`b$badguy[creaturewin]`b`0`n");
                
                train_navi();
                
                //$session['user']['seenmaster']=1;
                if ($session['user']['seenmaster']!=2) $session['user']['seenmaster']=1;
            }else{
              fightnav(false,false);
            }
        }
    }
}
    
else{
    if($_GET['op'] != 'change') {
        output('`ôDas Klirren aufeinander schlagenden Eisens hallt dir schon aus weiter Ferne entgegen - bei wenig Trubel muss es sogar bereits vom Stadtplatz aus
                zu hören sein. Und das Tag für Tag, denn Krieger finden sich immer, die in dem großen, runden Areal gegen ihre Meister antreten und dadurch ihre
                Fertigkeiten weiter verbessern wollen. Bryden selbst steht gerade am Rand des Trainingsplatzes und sieht sich einen Übungskampf an. Die
                vermeintliche Ruhe, die der Mann mit dem kurzen, strohblonden Haar ausstrahlt, täuscht dich jedoch nicht: Wer es schafft und irgendwann gut genug
                ist, um diesen Kämpfer herauszufordern, sieht sich einem Gegner gegenüber, dessen Stärke und Schnelligkeit selbst die verfluchten grünen Echsen
                erzittern lässt.
                `n`nDu allerdings hast deinen eigenen Wert bereits unter Beweis gestellt. Deshalb siehst du den anderen Kriegern lediglich eine Weile lang bei
                ihren Kämpfen zu, ehe du dich daran erinnerst, dass nun eine andere
                Aufgabe auf dich wartet. Es wird Zeit, der Stadt zu beweisen, dass all das harte Training nicht umsonst gewesen ist und dass die Bürger den
                Schrecken in den umliegenden Wäldern nicht länger fürchten müssen. Geh und töte den grünen Drachen!');
        train_navi();        
    }
}

if($_GET['op'] == "change") {
    
        if($_GET['what'] == "castleturns") {
            $session['user']['castleturns']--;
            $session['user']['turns']+=getsetting("castle_turns_wk",4);
            output("`ôDu entschließt dich, die Zeit für das Schloss sinnvoller zu nutzen und erhältst ".getsetting("castle_turns_wk",4)." Waldkämpfe!");
        }    
        else {
            $session['user']['castleturns']++;
            $session['user']['turns']-=getsetting("wk_castle_turns",6);
            output("`ôNachdem Du auf einige Minotauruspuppen eingeprügelt und dich im Umgang mit Feen geübt hast, bekommst Du schließlich eine Schlossrunde!");
        }
        
        //train_navi();
        addnav("Zurück zum Trainingslager","train.php");
        addnav("Zurück zur Stadt","village.php");
        
}

page_footer();
?>

