
ï»¿<?php



// 07092005



if (!isset($session)) exit();

if ($_GET['op']==""){

  output("`@Du begegnest einem merkwÃ¼rdigen alten Mann!`n`n\"`#Ich hab mich verlaufen.`@\", sagt er, \"`#Kannst du mich ins Dorf zurÃ¼ckbringen?`@\"`n`n");

    output("Du weiÃŸt, daÃŸ du einen Waldkampf fÃ¼r heute verlieren wirst, wenn du diesen alten Mann ins Dorf bringst. Wirst du ihm helfen?");

    addnav("FÃ¼hre ihn ins Dorf","forest.php?op=walk");

    addnav("Lass ihn stehen","forest.php?op=return");

    $session['user']['specialinc'] = "oldmantown.php";

}else if ($_GET['op']=="walk"){

  $session['user']['turns']--;

    if (e_rand(0,1)==0){

      output("`@Du nimmst dir die Zeit, ihn zurÃ¼ck ins Dorf zu geleiten.`n`nAls Gegenleistung schlÃ¤gt er dich mit seinem hÃ¼bschen Stock und du erhÃ¤ltst `%einen Charmepunkt`@!");

        $session['user']['charm']++;

    }else{

    output("`@Du nimmst dir die Zeit, ihn zurÃ¼ck ins Dorf zu geleiten.`n`nAls DankeschÃ¶n gibt er dir `%einen Edelstein`@!");

        $session['user']['gems']++;

        //debuglog("got 1 gem for walking old man to village");

    }

    $session['user']['reputation']++;

    $session['user']['specialinc']="";

}else if ($_GET['op']=="return"){

  output("`@Du erklÃ¤rst dem Opa, daÃŸ du viel zu beschÃ¤ftigt bist, um ihm zu helfen.`n`nKeine groÃŸe Sache, er sollte in der Lage sein, den Weg zurÃ¼ck ");

    output("ins Dorf selbst zu finden. Immerhin hat er es ja auch vom Dorf hierher geschafft, oder? Ein Wolf heult links von dir in der Ferne und wenige Sekunden spÃ¤ter ");

    output("antwortet ein anderer Wolf viel nÃ¤her von rechts. Jup, der Mann sollte in Sicherheit sein.");

    $session['user']['specialinc']="";

}

?>

