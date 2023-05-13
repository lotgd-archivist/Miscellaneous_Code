
<?php 
// Strider's Thief script. .  . LoneStrider's pals strike  v0.5 
// (another version is planned) 
// Modified slightly for bug fixes and clarity (and effect) by JT 
// 
// Translation by Blanidur 

if (!isset($session)) exit(); 

if ($session['user']['gold']>0){ 
    $cost = (int)($session['user']['gold']/10); 
    $cost++; 
    output("`6Ein starker Wind pfeift Dir von Osten ins Gesicht. Ehe Du Dich versiehst, haben Diebe Dich umgeben. Insgeheim verfluchst Du Dich, sie nicht eher entdeckt zu haben. Sie verlangen Gold und fuchteln bedrohlich mit Ihren Messern vor Deinem Gesicht. Ihr Führer macht auf Dich einen ehrvollen Eindruck und Du weißt, dass Dir nichts geschehen wird, wenn Du den Forderungen nachkommst.`n`n`^Da Du heute gerade keine Lust zu sterben hast, gibst Du Ihnen `% $cost Gold`^ !`0"); 
    $session['user']['gold'] -= $cost; 
    debuglog("lost $cost Gold an die Diebe."); 
}else{ 
    output("`n`n`6Ein starker Wind pfeift Dir von Osten ins Gesicht. Ehe Du Dich versiehst, haben Diebe Dich umgeben. Insgeheim verfluchst Du Dich, sie nicht eher entdeckt zu haben. Sie verlangen Gold und fuchteln bedrohlich mit Ihren Messern vor Deinem Gesicht. Du versuchst zu erklären, dass Du kein Gold besitzt. Sie glauben Dir natürlich kein Wort und durchsuchen Dich. Da sie aber wirklich kein Gold finden, verprügeln sie Dich ordentlich, bevor sie schließlich eilig in den Bergen verschwinden. `n`n Die blauen Flecke und Schrammen lassen Dich irgendwie deutlich weniger bezaubernd aussehen.`0"); 
    //if ($session['user']['hitpoints'] > 10) 
        $session['user']['hitpoints'] *= 0.90; 
}
?>

