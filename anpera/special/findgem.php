
ï»¿<?php



// 22102005



// idea with ape by manweru, coding by anpera



switch(e_rand(1,3)){

    case 1:

    output("`^Das GlÃ¼ck lÃ¤chelt dich an. Du findest einen Edelstein!`0");

    $session['user']['gems']++;

    break;

    case 2:

    output("`^Du hÃ¶rst ein lautes Kreischen und spÃ¼rst einen leichten Ruck in der NÃ¤he deiner Edelsteinsammlung.");

    if ($session['user']['gems']>0){

        $session['user']['gems']--;

        output(" Kurz darauf siehst du ein Ã„ffchen mit einem deiner Edelsteine im Wald verschwinden.`0");

    }else{

        output(" GlÃ¼cklicherweise hast du keine Edelsteine dabei und machst dir darum auch keine Sorgen wegen dem Ã„ffchen, das scheinbar enttÃ¤uscht zurÃ¼ck in den Wald lÃ¤uft.`0");

    }

    break;

    case 3:

    output("`^Ein kleines Ã„ffchen wirft dir einen Edelstein an den Kopf und verschwindet im Wald. Du verlierst ein paar Lebenspunkte, aber der Edelstein lÃ¤sst dich den Ã„rger darÃ¼ber vergessen.`0");

    $session['user']['gems']++;

    $session['user']['hitpoints']*=0.9;

    break;

}

?>

