
ï»¿<?php



// idea of gargamel @ www.rabenthal.de

/*

 * modifications by  bibir (logd_bibir@email.de)

 *               for http://logd.chaosonline.de

 *

 * details:

 *  (20.08.04) $x[y] => $x['y']

 *             $HTTP_GET_VARS => $_GET

 *

 * modifications by  Linus (webmaster@alvion-logd.de)

 *               for http://alvion-logd.de/logd/

 *

 */



if (!isset($session)) exit();



if ( $session['user']['hashorse']>0 && $session['bufflist']['mount']['rounds'] > 0 ) {

    $keep = e_rand(75,90)/100;

    output("`n`QVerflucht!`0 Auf dem Streifzug durch den Wald ist dein ".

    $playermount['mountname']." `0offenbar in ein `9Loch`0 getreten. `nDu hast Mitleid mit deinem humpelnden Tier, das

    durch seine Verletzung `Qerheblich an Kraft verloren`0 hat.`n`n");

    //die sache mit dem buff

    $session['bufflist']['mount']['rounds'] = round($session['bufflist']['mount']['rounds']*$keep);

    if ( $session['bufflist']['mount']['rounds'] == 0 ) $session['bufflist']['mount']['rounds'] = 1;

}else { // kein Pferd

    output("`nAuf deinem Streifzug durch den Wald trittst du in ein `9Loch`0, das

    du Ã¼bersehen hast.`nDu verstauchst dir den FuÃŸ und solltest den Heiler aufsuchen.`0 Er wird deinen

    Gesundheitsverlust mit edlen KrÃ¤uterzubereitungen ausgleichen kÃ¶nnen.`0`n`n");

    $session['user']['hitpoints'] = round($session['user']['hitpoints']*0.65);

}

?>

