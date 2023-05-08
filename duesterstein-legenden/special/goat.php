
<?
// originaly written by Robert for Maddnet LoGD
// translation by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

    output("`nDu findest einen windschiefen Verschlag mitten im Wald. Nachdem Du
    keine Wertsachen gefunden hast, nutzt Du Deinen Fund wenigstens für ein kurzes
    Nickerchen.`n`n
    Als Du wieder erwachst, siehst du eine Ziege, die Dich am Schuh leckt.`n`n`0");
if ($session[user][hashorse]> 0){
    output("Du springst auf und verschwindest.");
    if ( $session[user][hitpoints] < $session[user][maxhitpoints] ) {
        output("Durch den Schlaf bist Du vollständig geheilt.`0");
        $session[user][hitpoints]=$session[user][maxhitpoints];
    }
}
else {
    output("Das Tier läuft Dir hinterher. Nachdem alle Versuche es zu verscheuchen
    fehlgeschlagen sind, nimmst Du es einfach mit.`n
    `8Was Du damit sollst, ist Dir jedoch unklar.`0");
     debuglog("found a Goat at a small house");
     $session[user][hashorse] = 9;
     $playermount = getmount($session['user']['hashorse']);
     $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
}

?>


