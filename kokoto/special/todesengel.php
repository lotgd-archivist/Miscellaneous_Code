<?php 
// found by www.mystara-logd.net
// überarbeitet von Tidus für www.kokoto.de
output('`n`#`b`cTodesengel`b`c`n`n`0');
if ($_GET['op']=='laufen'){
        output('Für deine Feigheit verlierst du an Charme.'); 
        $session['user']['charm']-=2; 
        $session['user']['specialinc']=''; 
}else if ($_GET['op']=="attack"){
        output("`9Du gehst mit gezogener Waffe auf das Wesen zu, welches dich sofort angreift."); 
        $badguy = array( 
                "creaturename"=>"`4Todesengel`0", 
                "creaturelevel"=>13, 
                "creatureweapon"=>"Hauch des Todes", 
                "creatureattack"=>$session['user']['attack']6, 
                "creaturedefense"=>$session['user']['defence']8, 
                "creaturehealth"=>600, 
                "diddamage"=>0); 
        $session['user']['badguy']=createstring($badguy); 
        $session['user']['specialinc']='todesengel.php'; 
        $battle=true; 
}else if ($_GET['op']=='run'){   // Flucht 
    if (e_rand()3 == 0){ 
    $session['user']['specialinc']=''; 
        output ("`c`b`&Du konntest dem Todesengel entkommen!`0`b`c`n"); 
        $_GET['op']=''; 
    }else{ 
        output("`c`b`\$Der Todesengel war schneller als du!`0`b`c"); 
        $battle=true; 
    } 
}else if ($_GET['op']=='fight'){   // Kampf 
    $battle=true; 
    $session['user']['specialinc']='todesengel.php'; 
}else if ($_GET['op']=='talk'){
        addnav('Erzähle mir deine Geschichte','forest.php?op=he'); 
        addnav('Erzähle ihm deine Geschichte','forest.php?op=you'); 
        output('Worüber möchtest du mit ihm reden?'); 
        $session['user']['specialinc']="todesengel.php"; 
}else if ($_GET['op']=='he'){
    switch(mt_rand(1,2)){ 
    case 1:  
        output('Der Engel erzählt dir von seinem Leben und bedankt sich für das Zuhören. `nDu fühlst dich charmanter!'); 
        $session['user']['charm']+=2; 
        $session['user']['specialinc']=''; 
    break; 
    case 2:  
        output('Der Engel erzählt dir seine Geschichte, doch langweilst du dich so sehr dabei, dass du leise gähnst. Der Engel hatte dies sehr wohl bemerkt und blickt dich vorwurfsvoll an. Dich etwas schämend verlierst du einige Charmepunkte'); 
        $session['user']['charm']-=2; 
        $session['user']['specialinc']=''; 
    break; 
    } 
}else if ($_GET['op']=='you'){
    switch(mt_rand(1,2)){ 
    case 1:  
        output('Was interessiert mich dein armseliges Leben? Es wird ohnehin gleich vorbei sein.Stirb! `nDu bist tot.'); 
        addnav('News','news.php'); 
            addnews("`Q".$session['user']['name']." `Qwar plötzlich tot, da ein Engel seine Ruhe wollte!"); 
            $session['user']['alive']=false; 
            $session['user']['hitpoints']=0; 
            $session['user']['experience']-=round($session['user']['experience']0.06); 
            $session['user']['specialinc']=''; 
    break; 
    case 2:  
        output('Du erzählst dem Engel deine Geschichte und dieser hört aufmerksam zu. Nachdem du geendet hast, sagt er zu dir, dass er dadurch einiges über die Menschen gelernt habe und dir deshalb etwas an Erfahrung schenkt.'); 
        $session['user']['experience']=($session['user']['experience'])1.05; 
        $session['user']['specialinc']=''; 
    break; 
    } 
    }else{
	        output('Du gehst tiefer in den Wald und um dich herum wird alles dunkler. Die Bäume stehen dichter und es wird kälter, umso weiter du gehst. Du hörst die Werwölfe jaulen und ein kleiner Schauer läuft dir über den Rücken. Plötzlich steht ein Engel mit schwarzen Flügeln vor dir und mustert dich aus schwarzen Augen.'); 
        addnav('Lauf weg','forest.php?op=laufen'); 
        addnav('Greife ihn an','forest.php?op=attack'); 
        addnav('Rede mit ihm','forest.php?op=talk'); 
        $session['user']['specialinc']='todesengel.php'; 
	}
	if ($battle){
    include("battle.php"); 
    $session['user']['specialinc']='todesengel.php'; 
        if ($victory){ 
            $badguy=array(); 
            $session['user']['badguy']=''; 
            output('`n`9Du konntest nach einem schweren Kampf den Todesengel besiegen!'); 

            addnav('Z?Zurück in den Wald','forest.php'); 
            if (mt_rand(1,2)==1){ 
                $gem_gain = mt_rand(2,3); 
                $gold_gain = mt_rand($session['user']['level']15,$session['user']['level']25); 
                output(" Als Du Dich noch einmal umdrehst findest Du $gem_gain Edelsteine und $gold_gain Goldstücke.`n`n"); 
            $session['user']['gems']+=$gem_gain; 
            $session['user']['gold']+=$gold_gain; 
            } 
            $exp = round($session['user']['experience']0.15); 
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n"); 
            $session['user']['experience']+=$exp; 
            $session['user']['specialinc']=''; 
        }else if ($defeat){ 
            $badguy=array(); 
            $session['user']['badguy']=''; 
            output('`n`9Der Todesengel war stärker!`n`nDu verlierst 6% Deiner Erfahrung.`0 `nTodesengel können nichts mit Gold anfangen. Du kannst morgen wieder kämpfen!`0'); 
            addnav('News','news.php'); 
            addnews("`QDer Atem des Todes hat ".$session['user']['name']." `Qumgebracht!"); 
            $session['user']['alive']=false; 
            $session['user']['hitpoints']=0; 
            $session['user']['experience']-=round($session['user']['experience']0.06); 
            $session['user']['specialinc']=''; 
        }else{ 
            fightnav(true,true); 
        } 
}
?>