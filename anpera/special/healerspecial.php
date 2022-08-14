
ï»¿<?php



// 07092005



/*

 * cedrick.php

 * Version:   18.09.2004

 * Author:   bibir

 * Email:   logd_bibir@email.de

 *

 * Purpose: a special for cheaper healing

 */



// kleine Ã„nderungen am Text - der Heiler spricht wie Yoda



output("`6\"`3Keine Zeit, keine Zeit!`6\" tÃ¶nt es aus den Baumwipfeln - verwirrt schaust du etwas genauer hin. Ach ja, der Heiler - fleiÃŸig bei der Arbeit.`n");

output("`6\"`3Es mir Leid tut, aber absolut keine Zeit fÃ¼r dich ich jetzt habe.`6\" sagt er, als er kurz runterkommt um den nÃ¤chsten Baum hinaufzuklettern.");



if(e_rand(1,100)> 70) {

    output("`6Er steckt dir noch ungefragt einen Coupon fÃ¼r Golinda zu.

    Du schaust ihn zwar fragend an, wagst es aber nicht, etwas zu sagen.

    AuÃŸerdem - so Ã¼berlegst du dir - ist Golinda doch ohnehin gÃ¼nstiger.");

    $config = unserialize($session['user']['donationconfig']);

    $config['healer'] ++;

    $session['user']['donationconfig'] = serialize($config);

} else {

    output("`6\"`3Hier, das du bekommst von mir, damit meine Ruhe ich habe!`6\"`n Er wirft dir eine kleine Phiole zu, die mit einer FlÃ¼ssigkeit gefÃ¼llt ist, die ganz wie der Ã¼bliche Heiltrank aussieht. Du Ã¶ffnest sie und trinkst.`n");

    if(e_rand(1,100) >50){

        output("Du generierst beinahe ganz.");

        if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']*0.8) $session['user']['hitpoints'] = $session['user']['maxhitpoints']*0.8;

    } else {

        output("Dir wird schlecht und du verlierst viele Lebenspunkte. - Da hat der Gute sich wohl vertan");

        $session['user']['hitpoints'] *= 0.3;

        if ($session['user']['hitpoints']<=1) $session['user']['hitpoints']=1;

    }

}

?>

