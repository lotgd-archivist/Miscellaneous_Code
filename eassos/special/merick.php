
<?php
/*
 * merick.php
 * Version:   18.09.2004
 * Author:   bibir
 * Email:   logd_bibir@email.de
 *
 * Purpose: a special for more/less feeding
 */

if(e_rand(1,100)> 40) {
    output("Du gehtst durch den Wald als dir Merick begegnet - er grüßt freundlich und scheint gute Laune zu haben.`n");
    output("`3\"`#Ah, hallo ".$session['user']['name'].", wie gehts? - Heute läßst sich wunderbar Futter finden,
    vieles ist besonders gut heute.`3\"`n`0");
    if ($session['user']['hashorse']>0){
        output("Merick gibt deinem Tier ein wenig zu futtern und streichelt es. Dein Tier bekommt diese Behandlung sehr gut,
        es generiert um einiges.`n");
        $buff = unserialize($playermount['mountbuff']);
        $session['bufflist']['mount']['rounds'] = round(($session['bufflist']['mount']['rounds']+$buff['rounds'])/2,0);
        output("`3\"`#Ich denke, wenn dein ".$playermount['mountname']." später kraftlos ist, werd ich noch genügend Futter haben -
        scheu dich nicht, herzukommen.`3\"`0`n");
        $session['user']['fedmount'] ++;
    }
    output("Mit diesen Worten verabschiedet er sich auch schon wieder und geht seines Weges - zurück ins Dorf.");
} else {
    output("Du gehtst durch den Wald als dir Merick begegnet - er grüßt dich nicht und es scheint, als habe er schlechte Laune.`n");
    output("Als er an dir vorbeigeht, hörst du ihn murmeln:`n");
    output("`3\"`#Argh - kein Futter, nix da....und DU - DU BRAUCHST DICH AUCH NICHT BEI MIR BLICKEN ZU LASSEN.`3\"`n`0");
    output("Erschrocken machst du einen Satz zur Seite.");
    if ($session['user']['hashorse']>0){
        output("Tja, dein ".$playermount['mountname']." wirst du heute wohl nicht noch einmal füttern können.");
        $session['user']['fedmount'] = 1;
    }
   
}

?>

