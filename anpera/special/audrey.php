
ï»¿<?php



// 20061003



if (!isset($session)) exit();



if($_GET['op']=="run"){

    $session['user']['specialinc']="";

    output("`5Du rennst sehr schnell vor dieser durchgedrehten Frau davon.");



}elseif($_GET['op']=="play"){

    $session['user']['specialinc']="";

    $kittens = array("`^G`&e`6s`7c`^h`7e`^c`&k`6t","`7G`&e`7t`&i`7g`&e`7r`&`7t","`6Orangen","`&Weiss","`^`bLanghaarig`b");

    $c1 = e_rand(0,3);

    $c2 = e_rand(0,3);

    $c3 = e_rand(0,3);

    if (e_rand(1,20)==1) {

        $c1=4; $c2=4; $c3=4;

    }

    output("`5Du stimmst einem Spiel mit der verrÃ¼ckten Audrey zu und sie klopft dem ersten Korb auf den ersten Deckel. Das KÃ¤tzchen ist {$kittens[$c1]}`5`n`n");

    output("Die verrÃ¼ckte Audrey klopft auf den Deckel des zweiten Korbes. Das KÃ¤tzchen, das dort den Kopf herausstreckt,  ist {$kittens[$c2]}`5`n`n");

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/cat.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

    output("Sie klopft auf den dritten Korb und ein {$kittens[$c3]}`5es KÃ¤tzchen springt heraus und klettert Audrey auf die Schulter.`n`n");

    if ($c1==$c2 && $c2==$c3){

        if ($c1==4){

            output("\"`%Langhaarige? LANGHAARIGE?? Hahahaha, LANGHAARIGE!!!!`5\", schreit die verrÃ¼ckte Audrey, wÃ¤hrend sie alle einsammelt und schreiend in den Wald rennt

            Du bemerkst, dass sie eine ganze TASCHE dieser wunderbaren Salbe fallengelassen hat.`n`n`^Du erhÃ¤ltst FÃœNF zusÃ¤tzliche WaldkÃ¤mpfe!");

            $session['user']['turns']+=5;

        }else{

            output("\"`%Aaaah! Ihr seid ALLES sehr bÃ¶se KÃ¤tzchen!`5\", schreit die verrÃ¼ckte Audrey. Dann umarmt sie das KÃ¤tzchen auf ihrer Schulter und steckt es

            zurÃ¼ck in den Korb. \"`%Weil es lauter gleiche KÃ¤tzchen waren, werde ich dir zwei Salben geben.`5\"`n`nDu verteilst die Salbe auf deinen Beinen.`n`n`^Du erhÃ¤ltst ZWEI WaldkÃ¤mpfe!");

            $session['user']['turns']+=2;

        }

    }elseif ($c1==$c2 || $c2==$c3 || $c1==$c3){

        output("\"`%Grrr, ihr verrÃ¼ckten Katzen, was denkt ihr euch? Ich sollte euch alle in verschiedenen Farben anmalen!`5\" Trotz ihrer Drohung

         streichelt Audrey das KÃ¤tzchen auf ihrer Schulter, bevor sie es in den Korb zurÃ¼ck steckt. Dann gibt sie dir deine Salbe, die du sofort auf die Beine schmierst.

        `n`n`^Du bekommst einen Waldkampf dazu!");

        $session['user']['turns']++;

    }else{

        output("\"`%Gut gemacht, meine HÃ¼bschen!`5\" schreit Audrey. In diesem Moment springt dich das KÃ¤tzchen von ihrer Schulter an. Beim Versuch, es abzuwehren,

        verlierst du etwas Energie. SchlieÃŸlich hopst es zurÃ¼ck ins KÃ¶rbchen und alles ist wieder still. Die verrÃ¼ckte Audrey schnattert leise vor sich hin und schaut dich dabei an.");

        output("`n`n`^Du verlierst einen Waldkampf!");

        $session['user']['turns']--;

    }



}else{

    output("`5Du stolperst Ã¼ber eine Lichtung, die seltsam ruhig ist. Auf einer Seite stehen drei ordentlich verschlossene KÃ¶rbe. Du findest das

    merkwÃ¼rdig und nÃ¤herst dich den KÃ¶rben vorsichtig. Wie du nÃ¤her kommst, hÃ¶rst du ein schwaches Miauen. Du hast den Deckel des ersten

    Korbes schon fast in der Hand, als die verrÃ¼ckte Audrey wie aus dem Nichts auftaucht und wie im Fieberwahn etwas von farbigen KÃ¤tzchen daherredet. Sie zieht die KÃ¶rbe zu sich heran.

    Etwas verblÃ¼fft befragst du sie Ã¼ber diese KÃ¤tzchen.`n`n

    \"`#Sprich, gute Frau...`5\"`n`n

    \"`%GUT GUT gut gut gutgutgutgutgut...`5\", wiederholt Audrey. Unbeeindruckt fÃ¤hrst du fort.`n`n

    \"`#Was sind das fÃ¼r Katzen, von denen du sprichst?`5\"`n`n

    Erstaunlicherweise wird die verrÃ¼ckte Audrey plÃ¶tzlich ganz ruhig und spricht mit leichtem sowohl melodischen wie auch sanften Akzent.`n`n

    \"`%Von diesen KÃ¶rben habe ich drei.`n

    Vier KÃ¤tzchen in jedem der drei.`n`n

    Ihren eigenen Willen sie alle wohl haben.`n

    Sollten zwei gleiche entkommen, du sollst diese Salbe haben.`n`n

    Energie sie dir bringt gegen deine Feinde.`n

    Wenn gleichmÃ¤ssig verteilte auf die Beine.`n`n

    Wenn keine zwei gleichen den Kopf raus strecken,`n

    ich frÃ¼her heute ins Bett dich werd stecken.`n`n

    Das wÃ¤re mein Angebot,`n

    Nimmst du es an, oder fliehst du hinfort?`5\"`n`n

    Wirst du ihr Spiel mitspielen?");

    addnav("Spielen","forest.php?op=play");

    addnav("Vor Crazy Audrey wegrennen","forest.php?op=run");

    $session['user']['specialinc']="audrey.php";

}

?>

