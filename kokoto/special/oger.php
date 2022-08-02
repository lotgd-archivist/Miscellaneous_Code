<?php
//*-------------------------*
//|        Scriptet by      |
//|       °*Amerilion*°     |
//| steffenmischnick@gmx.de |
//|   Modified by Hadriel   |
//*-------------------------*
// überarbeitet von Tidus www.kokoto.de
//Kampf aus der mill.php

if (!isset($session)) exit();

if ($_GET['op']=='gehe'){
output('`9Du gehst mit gezogener Waffe auf das Wesen zu, welches dich sofort angreift.');
$badguy = array(
                "creaturename"=>"`\$Oger`0",
                "creaturelevel"=>$session['user']['level']2,
                "creatureweapon"=>"Ein massiver Baumstamm",
                "creatureattack"=>$session['user']['attack']4,
                "creaturedefense"=>$session['user']['defence']2,
                "creaturehealth"=>round($session['user']['maxhitpoints']1.25,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']='oger.php';
        $battle=true;
        $session['user']['specialinc']='';
}
//Battle Settings
else if ($_GET['op']=='run'){   // Flucht
    if (e_rand()3 == 0){
    $session['user']['specialinc']='';
        output ('`c`b`&Du konntest dem Oger entkommen!`0`b`c`n');
        $_GET['op']='';
    }else{
        output('`c`b`$Der Oger war schneller als du!`0`b`c');
        $battle=true;
    }
}else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='oger.php';
}else if ($_GET['op']=='beobachte'){
$session['user']['specialinc']='';
output('`9Du beobachtest das seltsame Geschöpf...`n`n');
switch(mt_rand(1,5)){
case 1:
                    output('`@als der Wind dir seinen Gestank in die Nase weht, du brichst voller Abscheu zusammen und als du aufwachst ist das Wesen weg. Du gehst weiter und bemerkst am stand der Sonne das es `iviel`i später ist und du nun nicht mehr viel tun kannst.');
                    if($session['user']['turns']>5){
                    $session['user']['turns']-=5;
                    }else{
                    $session['user']['turns']=0;
                    }
break;
case 2:
                    output('`@als dieses seinen Baumstamm plötzlich fallen lässt, erstaunt stehst du auf um zu sehen was geschehen ist. In dem Moment sieht dich das Wesen, nimmt eins der weidenden Schafe und schleudert es auf dich.`n`n`4Du verletzt dich und rennst schnell weg');
                    $session['user']['hitpoints']=$session['user']['hitpoints']0.5;
break;
case 3:
                    output('`@als diese weiterstapft. Du wendest dich ab und findest in den Strauch in den du dich versteckt hattest etwas Gold in einem Ledersack welchen du dir einsteckst');
                       $session['user']['gold']+=500;
break;
case 4:
case 5:
                    output('`@als du durch ein Schreien aufmerksam wirst. Ein dir unbekannter Abenteurer stürzt sich der Kreatur entgegen und wird von dieser mit dem Baumstamm weit wegeschlagen. Du verfolgst den Flug des Abenteurers welcher im Fluss vor dir endet. Das Tier ist verschwunden als du wieder aufblickst und du endeckst bei den Toten einige Edelsteine');
                    $session['user']['gems']+=1;
break;
                   }
}else if ($_GET['op']=='schleich'){
$session['user']['specialinc']='';
output('`9Du schleichst wieder in den Wald und schreibst einen Bericht über deine Beobachtung');
switch(mt_rand(1,9)){
case 1:
case 2:
$gold=rand($session['user']['level']50,$session['user']['level']100);
$session['user']['gold']=$gold;
output('welcher sich großer Beliebtheit erfreut. Du bekommst einiges an Gold');
addnews("`@`b".$session['user']['name'].'`b `@hat einen interessanten Bericht über ein seltsames Tier verfasst, und damit Gold verdient');
break;
case 3:
case 4:
case 5:
case 6:
case 7:
case 8:
case 9:
output('welchen du leider verlierst...');
break;
}
}else{
output('`n`c`b`2Das seltsame Geschöpf`b`c`n`n
`9Du streifst wie jeden Tag durch den Wald und kommst an einen Fluss,
auf dessen andere Seite eine Wiese liegt. Du siehst das du den Fluss
an der Stelle an der du stehst leicht überqueren kannst. Während du dich
aus dem Unterholz befreist, durch das du grade gekrochen bist, bemerkst du
auf der anderen Seite ein lautes Geräusch. Du siehst genauer hin
und erblickst ein großes, sich bewegendes etwas, scheinbar ein Tier.
Es hat eine runzelige Haut und bewegt sich auf zwei Beinen vorwärts
In der einen Hand hält es einen Baum, ähnlich wie eine Waffe. Außerdem
weiden einige wilde Schafe auf der andern Seite.');
$session['user']['specialinc']='oger.php';
addnav('Gehe hinüber','forest.php?op=gehe');
addnav('Beobachte es','forest.php?op=beobachte');
addnav('Schleiche wieder in den Wald.','forest.php?op=schleich');
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']='oger.php';
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`9Du konntest nach einem schweren Kampf den Oger besiegen!');
            debuglog("defeated the Oger");
            //Navigation
            addnav('Zurück in den Wald','forest.php');
            if (rand(1,2)==1) {
                $gem_gain = rand(2,3);
                $gold_gain = rand($session['user']['level']10,$session['user']['level']20);
                output(" Als Du Dich noch einmal umdrehst findest Du $gem_gain Edelsteine
                und $gold_gain Goldstücke.`n`n");
            $session['user']['gems']+=$gem_gain;
            $session['user']['gold']+=$gold_gain;
            }
            $exp = round($session['user']['experience']0.08);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['specialinc']='';
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            debuglog('was killed by a Oger.');
            output('`n`9Der Oger war stärker!`n`nDu verlierst 6% Deiner Erfahrung.`0 `nOger können nichts mit Gold anfangen. Du kannst morgen
            wieder kämpfen!`0');
            addnav('Tägliche News','news.php');
            addnews("`QEin Baumstamm des Ogers hat ".$session['user']['name']." `Qeinfach so zerquetscht!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'].94,0);
            $session['user']['specialinc']='';
        } else {
            fightnav(true,true);
        }

}
?>