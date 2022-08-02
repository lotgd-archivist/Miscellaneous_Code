<?php 
require_once "common.php"; 
//-------------------------------------------------------------------------------------------------------- 
//| Written by:  rax (rax@sourceforge.net) 
//| Get the latest code at: logd.raxhaven.com 
//| Version: 1.0 - 05/17/2004 
//| 
//| About:   Allows players to go on quests 
//| 
//| Description: 
//|            This mod allows players to go on quests.   It also provides a simple framework 
//|     for content authors to write quests (although no interface has been developed yet). 
//|     Each quest can only be completed once, which is an important feature and makes this 
//|     mod different from an IGM. 
//| 
//|    Please read the readme.txt file available in the zip file on dragonprime. 
//| http://dragonprime.cawsquad.net/users/rax/QuestModule-1_0.zip 
//| 
//-------------------------------------------------------------------------------------------------------- 



page_header("Quests");     

if ($_GET[quest]==""){ 
    $quests = $session[user][quests]; 
    //addnav("Abenteuer", "quests.php"); 
    addnav("Zurück","forest.php"); 

    //output("`n`n2^4 ". pow(2,4) ." ", true); 
    $query = "SELECT * FROM quests ORDER BY dks ASC, level ASC, ff ASC"; 
    $result = db_query($query); 
    $rowAlternate = 1; 
    output("`c`b`^Dir stehen folgende Abenteuer zur Auswahl`b`c`7`n", true); 
    output("`c<table border=0 cellpadding=4 cellspacing=1 bgcolor='#999999'>",true); 
    output("<tr class='trhead'><td style='width:200px'><b>Abenteuer</b></td><td><b>Drachen Kills</b></td><td><b>Level</b></td><td><b>Waldkämpfe</b></td><td><b>Schwierigkeit</b></td><td><b>erneut versuchen</b></td><td><b>Status</b></tr>",true); 

    while ($row = db_fetch_assoc($result)) { 

        if(!($quests & pow(2,$row[qid]))){ 
            if($session[user][dragonkills] >= $row[dks] && 
                     $session[user][level] >= $row[level] && 
                $session[user]['turns'] >= $row[ff]){ 
                $status = "`b`2Möglich`b`7"; 
                $target = "<a href=\"quests.php?quest=load&questnum=".$row[qid]."\">".$row[title]."</a>"; 
            }else{ 
                $status = "`b`6Nicht möglich`b`7"; 
                $target = "`b`7".$row[title]."`b"; 
            } 
        }else{ 
            $status = "`b`4Geschafft`b`7"; 
            $target = "`b`7".$row[title]."`b"; 
        } 
        addnav("", "quests.php?quest=load&questnum=".$row[qid]); 
        output("<tr class='".($rowAlternate%2?"trdark":"trlight")."'><td>",true); 
        output("`&".$target."</td><td style='text-align:center'>`&".$row[dks]."</td><td style='text-align:center'>`&".$row[level]."</td><td style='text-align:center'>`&".$row[ff]."</td><td style='text-align:center'>`&".$row[challenge]."</td><td style='text-align:center'>`&".($row[retry]?"`2Ja`7":"`4Nein`7")."</td><td style='text-align:center'>`&".$status."</td></tr>", true);     
        $rowAlternate++; 
    } 
    output("</table>`c`n`n", true); 
}else if($_GET[quest]=="load"){ 

    $query = "SELECT * FROM quests WHERE qid =".$_GET[questnum]; 
    $result = db_query($query); 
    $questInfo = db_fetch_assoc($result); 

    include("quests/".$questInfo["filename"]); 

} 

function questfightnav($questnum, $actnum, $allowspecial=true, $allowflee=true){ 
  global $PHP_SELF,$session; 

    //$return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]); 
    //$return = substr($return,strrpos($return,"/")+1); 
    //output("getret: ".$_GET[ret]."`nreturn detected as: ".$return, true); 

    //$script = str_replace("/","",$PHP_SELF); 
    $script = substr($PHP_SELF,strrpos($PHP_SELF,"/")+1); 
    addnav("Kämpfen","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight"); 
    if ($allowflee) { 
        addnav("Wegrennen","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=run"); 
    } 
    if ($allowspecial) { 
        addnav("`bBesondere Fähigkeiten`b"); 
        if ($session[user][darkartuses]>0) { 
            addnav("`\$Dunkle Künste`0", ""); 
            addnav("`\$&#149; Skelette herbeirufen`7 (1/".$session[user][darkartuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=DA&l=1",true); 
        } 
        if ($session[user][darkartuses]>1) 
            addnav("`\$&#149; Voodoo`7 (2/".$session[user][darkartuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=DA&l=2",true); 
        if ($session[user][darkartuses]>2) 
            addnav("`\$&#149; Geist verfluchen`7 (3/".$session[user][darkartuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=DA&l=3",true); 
        if ($session[user][darkartuses]>4) 
            addnav("`\$&#149; Seele verdorren`7 (5/".$session[user][darkartuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=DA&l=5",true); 
     
        if ($session[user][thieveryuses]>0) { 
            addnav("`^Diebeskünste`0",""); 
            addnav("`^&#149; Beleidigen`7 (1/".$session[user][thieveryuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=TS&l=1",true); 
        } 
        if ($session[user][thieveryuses]>1) 
            addnav("`^&#149; Waffe vergiften`7 (2/".$session[user][thieveryuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=TS&l=2",true); 
        if ($session[user][thieveryuses]>2) 
            addnav("`^&#149; Versteckter Angriff`7 (3/".$session[user][thieveryuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=TS&l=3",true); 
        if ($session[user][thieveryuses]>4) 
            addnav("`^&#149; Angriff von hinten`7 (5/".$session[user][thieveryuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=TS&l=5",true); 
     
        if ($session[user][magicuses]>0) { 
            addnav("`%Mystische Kräfte`0",""); 
            //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe 
            addnav("g?`%&#149; Regeneration`7 (1/".$session[user][magicuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=MP&l=1",true); 
        } 
        if ($session[user][magicuses]>1) 
            addnav("`%&#149; Erdenfaust`7 (2/".$session[user][magicuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=MP&l=2",true); 
        if ($session[user][magicuses]>2) 
            addnav("L?`%&#149; Leben absaugen`7 (3/".$session[user][magicuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=MP&l=3",true); 
        if ($session[user][magicuses]>4) 
            addnav("A?`%&#149; Blitz Aura`7 (5/".$session[user][magicuses].")`0","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=MP&l=5",true); 

        if ($session[user][superuser]>=3) { 
            addnav("`&Superuser`0",""); 
            addnav("!?`&&#149; __GOD MODE","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=godmode",true); 
        } 

        if ($session[user][paladinuses]>0) { 
            addnav("`@Paladin Kräfte`0",""); 
                        //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe 
            addnav("`@&#149; Lichtschild`7 (1/".$session[user][paladinuses].")`0","$script?op=fight&skill=PA&l=1",true); 
        } 
        if ($session[user][paladinuses]>1) 
            addnav("`@&#149; Schwert des Lichts`7 (2/".$session[user][paladinuses].")`0","$script?op=fight&skill=PA&l=2",true); 
        if ($session[user][paladinuses]>2) 
            addnav("`@&#149; Super Regeneration`7 (3/".$session[user][paladinuses].")`0","$script?op=fight&skill=PA&l=3",true); 
        if ($session[user][paladinuses]>4) 
            addnav("`@&#149; Gotteswut`7 (5/".$session[user][paladinuses].")`0","$script?op=fight&skill=PA&l=5",true); 
         
        if ($session[user][druiduses]>0) { 
            addnav("Tierkräfte`0",""); 
            addnav("`$&#149; Bärenstärke `7 (1/".$session[user][druiduses].")`0","$script?op=fight&skill=DR&l=1",true); 
        } 
        if ($session[user][druiduses]>1) 
            addnav("`$&#149; Grosser Freund`7 (2/".$session[user][druiduses].")`0","$script?op=fight&skill=DR&l=2",true); 
        if ($session[user][druiduses]>2) 
            addnav("`$&#149; Gravitations Vortex`7 (3/".$session[user][druiduses].")`0","$script?op=fight&skill=DR&l=3",true); 
        if ($session[user][druiduses]>4) 
            addnav("`$&#149; Wand der Wunder`7 (5/".$session[user][druiduses].")`0","$script?op=fight&skill=DR&l=5",true); 

        if ($session[user][amazonuses]>0) { 
            addnav("`@Amazonen Kräfte`0",""); 
                        //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe 
            addnav("`@&#149; Naturspeer `7 (1/".$session[user][amazonuses].")`0","$script?op=fight&skill=AM&l=1",true); 
        } 
        if ($session[user][amazonuses]>1) 
            addnav("`@&#149; Bewegungsunfähig machen`7 (2/".$session[user][amazonuses].")`0","$script?op=fight&skill=AM&l=2",true); 
        if ($session[user][amazonuses]>2) 
            addnav("`@&#149; Geruchsinn eines Wolfes`7 (3/".$session[user][amazonuses].")`0","$script?op=fight&skill=AM&l=3",true); 
        if ($session[user][amazonuses]>4) 
            addnav("`@&#149; Ruf der Wildniss`7 (5/".$session[user][amazonuses].")`0","$script?op=fight&skill=AM&l=5",true); 

        if ($session[user][swordmasteruses]>0) { 
            addnav("Schwert Kräfte`0",""); 
                        //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe 
            addnav("`$&#149; Teilen `7 (1/".$session[user][swordmasteruses].")`0","$script?op=fight&skill=SM&l=1",true); 
        } 
        if ($session[user][swordmasteruses]>1) 
            addnav("`$&#149; Schwerttanz`7 (2/".$session[user][swordmasteruses].")`0","$script?op=fight&skill=SM&l=2",true); 
        if ($session[user][swordmasteruses]>2) 
            addnav("`$&#149; Schwertspiel`7 (3/".$session[user][swordmasteruses].")`0","$script?op=fight&skill=SM&l=3",true); 
        if ($session[user][swordmasteruses]>4) 
            addnav("`$&#149; Teilen & Würfeln`7 (5/".$session[user][swordmasteruses].")`0","$script?op=fight&skill=SM&l=5",true); 

// spells 
        $sql="SELECT * FROM items WHERE class='Zauber' AND owner=".$session[user][acctid]." AND value1>0 ORDER BY name ASC"; 
        $result=db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)>0) addnav("Zauber"); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
              $row = db_fetch_assoc($result); 
            $spellbuff=unserialize($row[buff]); 
            addnav("`v$spellbuff[name] `0(".$row[value1]."x)","$script?quest=load&questnum=".$questnum."&act=".$actnum."&op=fight&skill=zauber&itemid=$row[id]"); 
        } 
// end spells                  
             
    } 
} 

function updateQuest($questnum){ 
    global $session; 
    //$questnum++; //account for 0 based list 
    $questUpdate = (pow(2,$questnum) | $session[user][quests]); 
    //echo "questUpdate = ".$questUpdate; 
    $sql = "UPDATE accounts SET quests=".$questUpdate." WHERE acctid=".$session[user][acctid]; 
    db_query($sql); 

    $session[user][quests] = $questUpdate; 
} 

function calcHandicap($questLvl){ 
    //Calculate handicap factor (for higher levels) 
    global $session, $badguy, $beta; 

    $dk = 0; 
    while(list($key, $val)=each($session[user][dragonpoints])) { 
        if ($val=="at" || $val=="de") $dk++; 
    } 
    //$dk += (int)(($session['user']['maxhitpoints']- 
        //($session['user']['level']*10))/5); 
    $dk = round($dk,0); 

    $lvl = $session['user']['level']-$questLvl;             
    $atkflux = e_rand(0, ($lvl*2)) + e_rand(0, $dk);         
    $defflux = e_rand(0, $lvl) + e_rand(0, $dk);         
    $hpflux = (8*$lvl)+(($atkflux+$defflux) * 5); 
    $badguy['creatureattack']+=$atkflux; 
    $badguy['creaturedefense']+=$defflux; 
    $badguy['creaturehealth']+=$hpflux; 

    if ($session['user']['race']==4) $badguy['creaturegold']*=1.2; 
    $badguy['creaturelevel'] =$badguy['creaturelevel'] + $lvl; 
    //Display information for playtesting 
    if ($beta) { 
        if ($session['user']['superuser']>=1){ 
            output("Debug: Quest Level $questLvl`n"); 
            output("Debug: $dk dks.`n"); 
            output("Debug: +$lvl level.`n"); 
            output("Debug: +$atkflux attack.`n"); 
            output("Debug: +$defflux defense.`n"); 
            output("Debug: +$hpflux health.`n"); 
        } 
    } 

} 

function calcBonus(){ 
    global $session, $badguy, $beta; 

    if (getsetting("dropmingold",0)){ 
        $badguy[creaturegold]=e_rand($badguy[creaturegold]/4,3*$badguy[creaturegold]/4); 
    }else{ 
        $badguy[creaturegold]=e_rand(0,$badguy[creaturegold]); 
    } 
    $expbonus = round( 
            ($badguy[creatureexp] * 
                (1 + .25 * 
                    ($badguy[creaturelevel]-$session[user][level]) 
                ) 
            ) - $badguy[creatureexp],0 
        ); 
    output("`b`&$badguy[creaturelose]`0`b`n"); 
    output("`n`b`\$Du hast $badguy[creaturename] besiegt!`0`b`n");         
    if (e_rand(1,25) == 1) { 
      output("`&Du hast einen Edelstein gefunden!`n`#"); 
      $session['user']['gems']++; 
      debuglog("hat einen Edelstein bei einem Monster gefunden."); 
    } 
    
    if ($expbonus>0){ 
      output("`n`#Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^".abs($expbonus)."`# Erfahrungspunkte und `^".abs($expbonus*2)."`# Gold! `n");
  //    ($badguy[creatureexp] + ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ($badguy[creaturegold] + ".abs(($expbonus*2))." = ".($badguy[creaturegold]+($expbonus*2)).")");
   //   $badguy[creaturegold] = $badguy[creaturegold]+($expbonus*2);
    }else if ($expbonus<0){ 
      output("`n`#Weil dieser Kampf so leicht war, verlierst du `^".abs($expbonus)."`# Erfahrungspunkte und `^".abs($expbonus*2)."`# Gold! `n  ");
    //  ($badguy[creatureexp] - ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") `n($badguy[creaturegold] - ".abs(($expbonus*2))." = ".($badguy[creaturegold]+($expbonus*2)).")");

     // $badguy[creaturegold] = $badguy[creaturegold]+($expbonus*2);
    } 

    //Keep warriors from being penalized negative gold and experience 
    $badguy[creatureexp] = $badguy[creatureexp]+$expbonus; 
    if($badguy[creaturegold] < 0) $badguy[creaturegold] = 0; 
    if($badguy[creatureexp] < 0) $badguy[creatureexp] = 0; 

    output("`nDu bekommst insgesamt `^".($badguy[creatureexp])."`# Erfahrungspunkte!`n`0"); 
    output("`#Du bekommst insgesamt `^".$badguy[creaturegold]."`# Gold!`n"); 
    if ($badguy['creaturegold']) { 
        debuglog("received {$badguy['creaturegold']} gold for slaying a monster."); 
    } 
    $session[user][gold]+=$badguy[creaturegold]; 
    $session[user][experience]+=($badguy[creatureexp]+$expbonus); 
    $creaturelevel = $badguy[creaturelevel]; 
    $_GET[op]=""; 
    $dontdisplayforestmessage=true; 
    $badguy=array(); 

} 

page_footer();     
?>