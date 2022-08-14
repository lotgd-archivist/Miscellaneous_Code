
ï»¿<?php 

/* 

 * cedrick.php 

 * Version:   18.09.2004 

 * Author:   bibir 

 * Email:   logd_bibir@email.de 

 * 

 * Purpose: a special for more/less free-ale and inn-coupons 

 */ 

  



$val = e_rand(1,100); 

if($val > 70) { 

    output("`0FrÃ¶hlichen Schrittes kommt dir Cedrick entgegen, als du schon recht erschÃ¶pft durch den Wald gehst.`n"); 

    output("\"`#Einen wunderschÃ¶nen Tag - na, heute schon erfolgreich gewesen?`n`9".$session['user']['name']."`#, du siehst so aus, als ob du eine erholsame Nacht vertragen kÃ¶nntest. Was hÃ¤ltst du davon, diese Nacht kostenlos bei mir zu schlafen?`0\"`n"); 

    output("Daraufhin steckt er dir ungefragt einen Ãœbernachtungsgutschein ('`inur heute gÃ¼ltig`i') zu und verschwindet, bevor du - verwirrt wie du bist - etwas antworten kannst."); 

    //ueber die donationconfig 

    /*$config = unserialize($session['user']['donationconfig']); 

    $config['innstays']++; 

    $session['user']['donationconfig'] = serialize($config);*/ 

    $session['user']['boughtroomtoday'] = 1; //als ob schon bezahlt 



} elseif($val > 30) { 

    output("`0FrÃ¶hlichen Schrittes kommt dir Cedrick entgegen, als du schon recht erschÃ¶pft durch den Wald gehst.`n"); 

    output("\"`#Einen wunderschÃ¶nen Tag - na, heute schon erfolgreich gewesen?`n`9".$session['user']['name'].", `#du siehst so aus, als ob dich ein Ale wieder auf Vordermann bringen kÃ¶nnte. Vielleicht solltest du mal bei mir vorbeischauen.`0\"`n"); 

    $session['user']['gotfreeale']=abs($session['user']['gotfreeale']-2); //als ob noch keines getrunken 



} else { 

    output("`0Cedrick kommt dir entgegen - mit sichtbar schlechter Laune.`n"); 

    output("\"`#Diese Diebe - jetzt klauen die mir schon alle meine FÃ¤sser - ich glaub das nicht!`n und DU - FROINDCHEN - DU BRAUCHST DICH HEUTE AUCH NICHT MEHR BEI MIT BLICKEN LASSEN - SO BESOFFEN, WIE DU IMMER BIST!`0\"`n"); 

    output("Du schaust ihn nur verdutzt an und bringst kein Wort heraus und gehst dann eilig weiter deines Weges.`n Aber eines weiÃŸt du sicher: heute wirst du nicht an der Bar in seiner schmierigen Kneipe auftauchen."); 

    $session['user']['gotfreeale']+=($session['user']['gotfreeale']>=2?0:2);

} 



?>

