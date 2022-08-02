<?php
//robin hood and his band of merry men forest event 
//Created by Lonny Luberts of http://www.pqcomp.com/logd e-mail logd@pqcomp.com 
page_header("Robin Hood"); 
output("`n`n`v`c`bRobin Hood`b`c`0`n`n");

if ($_GET['op'] == 'loose'){ 

    if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1; 
$loot = $session['user']['gold'];
        $session['user']['gold'] = 0;
        $sql = ("SELECT acctid,name,goldinbank,gold,login FROM accounts WHERE goldinbank < 3000 AND gold < 5000 AND acctid <> ".$session['user']['acctid']." ORDER BY rand()");
        $result = db_query($sql);
        $num = db_num_rows($result);
		$num = mt_rand(0,$num);
        $dist = round(($loot$num),0);
		
        if ($num > 1){
            for ($i=0;$i<$num;$i++){
                $row = db_fetch_assoc($result);
                $sql2 = ("UPDATE accounts SET goldinbank=goldinbank+$dist WHERE acctid = ".$row['acctid']);
                db_query($sql2);
                $mailmessage = $session['user']['name'];
                if ($_GET['op2'] == "give"){
                    $mailmessage .= " `0hat Robin Hood und seinen treuen Gesellen `^";
                    $mailmessage .= $loot;
                    $mailmessage .= " Gold`0 gegeben. Robin Hood verteilt es unter `\$";
                    $mailmessage .= $num;
                    $mailmessage .= " `0Leuten. Jeder von euch erhielt `^";
                    $mailmessage .= $dist;
                    $mailmessage .= " Gold`0. Das Gold wurde in euer Bankfach gelegt.";
                }else{
                    $mailmessage .= " `0wurde von Robin Hood und seinen treuen Gesellen ausgeraubt.  Sie nahmen `^";
                    $mailmessage .= $loot;
                    $mailmessage .= " Gold`0 an sich und verteilten es unter `\$";
                    $mailmessage .= $num;
                    $mailmessage .= " `0Leuten. Jeder von euch erhielt `^";
                    $mailmessage .= $dist;
                    $mailmessage .= " Gold`0. Das Gold wurde in euer Bankfach gelegt.";
                }
                systemmail($row['acctid'],"`2Robin Hood hat dir etwas Gold gegeben!",$mailmessage);
            } 

    if ($num > 0) addnews("Robin Hood stahl $loot Gold von ".$session['user']['name']."`7 und übergab es den Armen!"); 
    if ($num < 1) addnews("Robin Hood stahl $loot Gold von ".$session['user']['name']."`@und behielt eine Menge selbst!"); 
    if ($_GET['op2'] <> "give") output("`4Du hast verloren!`n"); 
    if ($_GET['op2'] == "give") output("`7Du übergibst das Gold!"); 
    if ($_GET['op2'] <> "give") output("`7Robin Hood und seine Gefährten sind nicht nur böse. Sei froh, dass Sie Dich am Leben liessen.`n"); 
    output("`3Robin Hood nahm Dein Gold um es unter den ärmsten der Armen zu verteilen."); 
    if ($session['user']['hitpoints'] == 1){ 
    output("Bevor sie gehen, schaut Robin Hood zurück und gibt Dir ein Getränk. Du trinkst es auf und"); 
    switch(mt_rand(1,10)){ 
        case 1: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .1; 
        output('es gab dir 10% deiner Gesundheit.`n'); 
        break; 
        case 2: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .2; 
        output('es gab Dir 20% deiner Gesundheit.`n'); 
        break; 
        case 3: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .3; 
        output('es gab dir 30% deiner Gesundheit.`n'); 
        break; 
        case 4: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .4; 
        output('es gab dir 40% deiner Gesundheit.`n'); 
        break; 
        case 5: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .5; 
        output('es gab dir 50% deiner Gesundheit.`n'); 
        break; 
        case 6: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .6; 
        output('es gab dir 60% deiner Gesundheit.`n'); 
        break; 
        case 7: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .7; 
        output('es gab dir 70% deiner Gesundheit.`n'); 
        break; 
        case 8: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .8; 
        output('es gab dir 80% deiner Gesundheit.`n'); 
        break; 
        case 9: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']  .9; 
        output('es gab dir 90% deiner Gesundheit.`n'); 
        break; 
        case 0: 
        $session['user']['hitpoints'] = $session['user']['maxhitpoints']; 
        output('es gab dir 100 % deiner Gesundheit.`n'); 
        break; 
    } 
} 

}
addnav('zum Wald','forest.php');
$session['user']['specialinc'] = "";

}else if ($_GET['op'] == 'win'){
   $session['user']['specialinc'] = ""; 
   output('Du hast Robin Hood und seine Gefährten besiegt! Du beschliesst zu gehen, bevor sie sich erholen.'); 
    addnews("Robin Hood und seine Gefährten wurden im Wald besiegt von ".$session['user']['name']."`7!");
     addnav('zum Wald','forest.php');
}else if ($_GET['op'] == 'fight1'){ 
$badguy = array(        "creaturename"=>"`@Bruder Tuck`0" 
                                ,"creaturelevel"=>0 
                                ,"creatureweapon"=>"Beer Belly" 
                                ,"creatureattack"=>0 
                                ,"creaturedefense"=>1 
                                ,"creaturehealth"=>2 
                                ,"creaturegold"=>0 
                                ,"diddamage"=>0); 

                                $userlevel=$session['user']['level']; 
                                $userattack=e_rand(2,$session['user']['atack'])2; 
                                $userhealth=e_rand(30,110)$session['user']['level']; 
                                $userdefense=e_rand(2,$session['user']['defence'])2; 
                                $badguy['creaturelevel']+=$userlevel; 
                                $badguy['creatureattack']+=$userattack; 
                                $badguy['creaturehealth']=$userhealth; 
                                $badguy['creaturedefense']+=$userdefense; 
                                $badguy['creaturegold']=0; 
                                $session['user']['badguy']=createstring($badguy); 
                                $battle=true; 
								$session['user']['specialinc'] = "robinhoodf.php";
                             
}else if ($_GET['op'] == 'fight2'){
$badguy = array(        "creaturename"=>"`@Will Scarlet`0" 
                                ,"creaturelevel"=>0 
                                ,"creatureweapon"=>"Sword" 
                                ,"creatureattack"=>1 
                                ,"creaturedefense"=>2 
                                ,"creaturehealth"=>2 
                                ,"creaturegold"=>0 
                                ,"diddamage"=>0); 

                                $userlevel=$session['user']['level']; 
                                $userattack=e_rand(2,$session['user']['atack'])4; 
                                $userhealth=e_rand(40,120)$session['user']['level']; 
                                $userdefense=e_rand(2,$session['user']['defence'])4; 
                                $badguy['creaturelevel']+=$userlevel; 
                                $badguy['creatureattack']+=$userattack; 
                                $badguy['creaturehealth']=$userhealth; 
                                $badguy['creaturedefense']+=$userdefense; 
                                $badguy['creaturegold']=0; 
                                $session['user']['badguy']=createstring($badguy); 
                                $battle=true; 
								$session['user']['specialinc'] = "robinhoodf.php";
                             
}else if ($_GET['op'] == 'fight3'){
$badguy = array(        "creaturename"=>"`@Little John`0" 
                                ,"creaturelevel"=>1 
                                ,"creatureweapon"=>"Staff" 
                                ,"creatureattack"=>2 
                                ,"creaturedefense"=>3 
                                ,"creaturehealth"=>2 
                                ,"creaturegold"=>0 
                                ,"diddamage"=>0); 

                                $userlevel=$session['user']['level']; 
                                $userattack=e_rand(2,$session['user']['atack'])6; 
                                $userhealth=e_rand(50,130)$session['user']['level']; 
                                $userdefense=e_rand(2,$session['user']['defence'])6; 
                                $badguy['creaturelevel']+=$userlevel; 
                                $badguy['creatureattack']+=$userattack; 
                                $badguy['creaturehealth']=$userhealth; 
                                $badguy['creaturedefense']+=$userdefense; 
                                $badguy['creaturegold']=0; 
                                $session['user']['badguy']=createstring($badguy); 
                                $battle=true;  
								$session['user']['specialinc'] = "robinhoodf.php";
                            
}else if ($_GET['op'] == 'fight4'){
$badguy = array(        "creaturename"=>"`@Robin Hood`0" 
                                ,"creaturelevel"=>2 
                                ,"creatureweapon"=>"Flying Arrows" 
                                ,"creatureattack"=>3 
                                ,"creaturedefense"=>4 
                                ,"creaturehealth"=>2 
                                ,"creaturegold"=>0 
                                ,"diddamage"=>0); 

                                $userlevel=$session['user']['level']; 
                                $userattack=e_rand(2,$session['user']['atack'])8; 
                                $userhealth=e_rand(60,140)$session['user']['level']; 
                                $userdefense=e_rand(2,$session['user']['defence'])8; 
                                $badguy['creaturelevel']+=$userlevel; 
                                $badguy['creatureattack']+=$userattack; 
                                $badguy['creaturehealth']=$userhealth; 
                                $badguy['creaturedefense']+=$userdefense; 
                                $badguy['creaturegold']=0; 
                                $session['user']['badguy']=createstring($badguy); 
                                $battle=true; 
								$session['user']['specialinc'] = "robinhoodf.php";
}else if ($_GET['op'] == 'fight'){
$battle=true;                                
}else{
    $session['user']['specialinc'] = "robinhoodf.php";
	$totalgold = $session['user']['gold']; 
    if ($session['user']['gold']<=299) $totalgold = 0; 
output('`c`7Als Du durch den Wald wanderst fällst Du Robin Hood und seinem Trupp in die Hände.`n`7Robin Hood erklärt er wolle Dein ganzes Gold, um es unter den Armen verteilen zu können.`n'); 
if ($totalgold < 299) output('`7Aber da du so wenig hast.. ziehen Sie von dannen ohne dir ein Haar zu krümmen.`c'); 
if ($totalgold > 299) output('`4Robin Hood meint Du hast zuviel Gold für Dich.`n'); 
if ($totalgold > 299) output('`7Was wirst Du tun?  Hilfst Du den Armen, oder willst Du Dein Gold verteidigen?`c'); 
if ($totalgold > 299) addnav('Gib Ihnen Dein Gold','forest.php?op=loose&op2=give'); 
if ($totalgold > 299) addnav('Bekämpfe sie','forest.php?op=fight1'); 
if ($totalgold < 299) addnav('Abhauen','forest.php'); 
if ($totalgold < 299) $session['user']['specialinc'] = "";


}
                                             

if ($battle){ 
        include_once("battle.php");                               
       
    if ($victory){ 
	$session['user']['specialinc'] = "robinhoodf.php";
                output("Du hast ihn besiegt den `^".$badguy['creaturename']."."); 
                if ($badguy['creaturename']=="`@Bruder Tuck`0") addnav('Weiter','forest.php?op=fight2'); 
                if ($badguy['creaturename']=="`@Will Scarlet`0") addnav('Weiter','forest.php?op=fight3'); 
                if ($badguy['creaturename']=="`@Little John`0") addnav('Weiter','forest.php?op=fight4'); 
                if ($badguy['creaturename']=="`@Robin Hood`0") addnav('Weiter','forest.php?op=win'); 
                $badguy=array(); 
                $session['user']['badguy']=''; 
    } 
    elseif ($defeat){ 
                output("`5Du wurdest in den Boden gestampft von `^".$badguy['creaturename']."`5 und der Rest seiner Gefährten stiehlt Dein Gold."); 
                addnews("`%".$session['user']['name']."`5 wurde stark geschwächt als ".($session['user']['sex']?"sie":"er")." von Robin Hood und seinen Gefährten überfallen wurde.`0"); 
                $session['user']['hitpoints']=1; 
				$session['user']['specialinc'] = "robinhoodf.php";
                addnav('Weiter','forest.php?op=loose'); 
    } 
    else{ 
                fightnav(true,false); 
    } 
}



page_footer(); 
?>
