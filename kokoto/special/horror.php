<?php
/* idea of bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* idea:
*     on your way you find a map of treasure.
*     but the treasure lies in the wood of horrors.
* details:
*     (24.08.04) start of idea
* Angepasst und verschönert by Tidus (www.kokoto.de)
*/
if (!isset($session)) exit();


if($_GET['op']=='horror'){
    $session['user']['specialinc']='horror.php';
    output('`2Nach ein paar Schritten nimmst du Schatten wahr, die dir sehr bedrohlich
    erscheinen.`n');
    switch(mt_rand(1,6)) {
        case '1':
            output('`tTiere kommen auf dich zu und umringen dich. Sie zerren an
            deinem Edelsteinbeutel und ziehen erst wieder weiter,');
            if ($session['user']['gems']>0) {
                output(' als sie dir einen Edelstein abgenommen haben.');
                $session['user']['gems']--;
            }
            else {
                output(' als sie feststellen, dass du gar keine Edelsteine hast.');
            }
            break;
        case '2':
            output('`4Ein lautes Kreischen erhebt sich und schon stürzen sich Vögel
            auf dich und hacken mit ihren Schnäbeln auf dir herum.`n
            `$Du verlierst einige Lebenspunkte.');
            $lose = e_rand(3,$session['user']['maxhitpoints']2);
            $session['user']['hitpoints'] -= $lose;
            if ($session['user']['hitpoints'] < 1) {
                redirect("forest.php?op=dead");
            }
            break;
        case '3':
            output('Du hast das Gefühl, dass die Bäume sich bewegen. Und so fällst du
            auch schon über eine Wurzel, die gerade noch nicht da war. Dein Goldbeutel
            reißt ab.');
            if ($session['user']['gold']>0) {
                output("`nObwohl er nicht geöffnet war, stellst du beim Nachzählen
                fest, dass ein paar Goldstücke fehlen.");
                $lose = round($session['user']['gold']0.1);
                $session['user']['gold'] -= $lose;
            }
            else {
                output('`nDa er leer ist, interessiert dich das recht wenig.');
            }
            break;
        case '4':
            output('`%Plötzlich hörst du ein Grollen, als auch schon etwas aus den Schatten auf dich zuspringt.`n
            Ein Werwolf hat dich angefallen und dir alle Fähigkeiten für heute geraubt.');
            $session['user']['darkartuses'] = 0;
            $session['user']['magicuses'] = 0;
            $session['user']['thieveryuses'] = 0;
            break;
        case '5':
            output('`@Doch hinter den Schatten siehst du den Weg, auf dem du vorhin langgekommen bist.
            Du hast dich verlaufen und musst am Anfang neu beginnen.`0');
            break;
        default:
            redirect("forest.php?op=treasure");
    }
    if (e_rand(1,3)==1){
        output('`n`^Zu hause wirst du viel zu erzählen haben, denn du hast Mut gezeigt.`n
        Du bekommst einen Charmepunkt.`0');
        $session['user']['charm']++;
    }
    output('`n`2`nWillst du nun noch weiter gehen, oder fliehst du lieber?`0');
    if ($session['user']['alive'] == false){
    addnav('Zum Schattenreich','shades.php');
    }else{
    addnav('Schatz suchen','forest.php?op=horror');
    addnav('Fliehen','forest.php?op=leave2');
    }

}else if($_GET['op']=='treasure'){
     $session['user']['specialinc']='';
     $gold = e_rand(500,1000)  $session['user']['level'];
    $gems = e_rand(2,6);
    output('`qDu findest eine Kiste, die voller Gold und Edelsteinen ist. Gierig steckst
    du ein, was du tragen kannst. Aber mehr als `^'.$gold.' Goldstücke`q und `#'.$gems.' Edelsteine`q
    sind es dann doch nicht.`n`n
    Schnell rennst du aus diesem Teil des Waldes in einen freundlicheren.`0');
    $session['user']['gold']+=$gold;
    $session['user']['gems']+=$gems;

} elseif($_GET['op']=='leave'){
    $session['user']['specialinc']='';
    output('Dir ist das alles zu unheimlich, daher gehst du lieber weiter.`n
    `^Durch deine Feigheit verlierst du einen Charmepunkt!`0');
    $session['user']['charm']--;

} elseif($_GET['op']=='leave2'){
    $session['user']['specialinc']='';
    output('Dir ist das alles zu unheimlich, daher gehst du lieber weiter.`0');

} elseif($_GET['op']=='dead'){
    output('`4Ein lautes Kreischen erhebt sich und schon stürzen sich Vögel
    auf dich und hacken mit ihren Schnäbeln auf dir herum.`n
    `$Du hast so viele Lebenspunkte verloren, dass du nun tot bist.');
    $session['user']['alive']=false;
    $session['user']['specialinc']='';
    addnews("`0".$session['user']['name'].'`0 starb im Wald der Gefahren!');
    addnav('Zum Schattenreich','shades.php');

} else {
    $session['user']['specialinc']='horror.php';
    output('Du gehst einen Weg entlang und seit einiger Zeit hast du das Gefühl,
    dass der Wald zu deiner Rechten etwas Böses beherbergt. Plötzlich schießt ein
    Pfeil dicht an deinem Kopf vorbei. Du gehst in Deckung, kannst aber nichts erkennen -
    außer, dass der Pfeil in einem Baum steckt und scheinbar eine Rolle Pergament trägt.`n
    Vorsichtig schleichst du dich hin, wobei du dich immer wieder umblickst.`n
    Als du den Pfeil erreicht hast, ziehst das Pergament ab und entrollst es.`n`n
    `tEs ist eine Schatzkarte!`n Du freust dich schon, musst dann aber feststellen,
    dass der Schatz im Wald der Gefahren verborgen ist. Das kann ja eigentlich
    nur dieser Wald sein.`n
    Du versuchst zu erkennen, wo der Anfang des  aufgezeichneten Pfades ist und
    entdeckst nicht weit von dir einen Baum, der aussieht, wie ein Gnom -
    ebenso, wie er in der Karte eingezeichnet ist.`n`n`0 Was machst du? Suchst du den Schatz oder gehst du lieber weiter?');
    addnav('Schatz suchen','forest.php?op=horror');
    addnav('Weiter gehen','forest.php?op=leave');
}
?>