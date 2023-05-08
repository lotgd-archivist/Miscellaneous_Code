
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nZwei merkwürdige Gesellen versperren Dir den Weg. Als sie Dich anbrüllen,
    schlägt Dir ihr fauliger Atem entgegen. \"Du willst durch unser Gebiet, das kostet\"
    herrschen sie Dich an.`n`0");
    if ( $session[user][gold] < 100 ) {
        output("\"Aber Du bist ja eine arme Wurst, da darfst Du umsonst passieren\"
        bieten sie Dir großzügig an.`n
        Du vermeidest weitere Diskussionen und nimmst die Beine in die Hand.`0");
    }
    else {
        output("Mit einem fiesen Grinsen verlangen sie `Q30% Deines Goldes als Zoll.`0");
        addnav("Wegezoll zahlen","forest.php?op=pay");
        addnav("Kämpfen","forest.php?op=fight");
        $session[user][specialinc] = "ambusher.php";
    }
}
else if ($HTTP_GET_VARS[op]=="pay"){
    output("`nDu gibst den Spießgesellen das geforderte Gold und sie lassen Dich
    tatsächlich passieren.`n
    Schlecht gelaunt ziehst Du weiter.`0");
    $weg = round($session[user][gold]*0.3);
    $session[user][gold]-= $weg;
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="fight"){
    $chance = e_rand(1,100);
    if ( $chance < 40 ) {
        output("`nDu zückst Deine Waffe und greifst die Typen an. Die beiden haben
        sich offenbar nur stark geredet, `9denn an der Waffe versagen sie jämmerlich.`n`0");
        $reward=e_rand($session[user][experience]*0.02, $session[user][experience]*0.05);
        $reward+=10;
        output("`@Du streckst die Wegelagerer nieder und bekommst $reward Erfahrungspunkte.`0");
        $session[user][experience]+=$reward;
    }
    else if ( $chance < 90 ) {
        output("`nTapfer kämpfst Du gegen die Typen, aber sie sind stärker als Du
        dachtest. Um Dein Leben zu schonen musst Du nun `QDein ganzes Gold herausgeben.`0`n
        Geschlagen machst Du Dich wieder auf den Weg.`0");
        $session[user][gold]=0;
    }
    else {
        output("Es war sicher nicht Deine beste Entscheidung, die beiden Typen zum
        Kampf zu fordern. Wütend schlagen sie auf Dich ein und besiegen Dich mit
        Leichtigkeit. In ihrer Wut finden sie kein Ende und bringen Dich um.`n
        `!Für Deinen Mut wird Dich jedoch Ramius belohnen.`0");
        $session[user][alive]=false;
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][hitpoints]=0;
        $session[user][gravefights]+=1;
        addnews("`^".$session[user][name]."`@ wollte den Wegezoll sparen. Nun ist Gold und Leben futsch!");
        addnav("Tägliche News","news.php");
    }
    $session[user][specialinc] = "";
}
?>


