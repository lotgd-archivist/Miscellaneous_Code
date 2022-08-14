
<?php

// 22062004

if (!isset($session)) exit();
if ($_GET[op]==""){
  output("`2Du begegnest einem merkwürdigen alten Mann!`n`n\"`3Ich hab mich verlaufen.`2\", sagt er, \"`3Kannst du mich ins Dorf zurückbringen?`2\"`n`n");
    output("Du weißt, dass du einen Waldkampf für heute verlieren wirst, wenn du diesen alten Mann ins Dorf bringst. Wirst du ihm helfen?");
    addnav("Führe ihn ins Dorf","forest.php?op=walk");
    addnav("Lass ihn stehen","forest.php?op=return");
    $session[user][specialinc] = "oldmantown.php";
}else if ($_GET[op]=="walk"){
  $session[user][turns]--;
    if (e_rand(0,1)==0){
      output("`2Du nimmst dir die Zeit, ihn zurück ins Dorf zu geleiten.`n`nAls Gegenleistung schlägt er dich mit seinem hübschen Stock und du erhältst `%einen Charmepunkt`2!");
        $session[user][charm]++;
    }else{
    output("`2Du nimmst dir die Zeit, ihn zurück ins Dorf zu geleiten.`n`nAls Dankeschön gibt er dir `%einen Edelstein`2!");
        $session[user][gems]++;
        //debuglog("got 1 gem for walking old man to village");
    }
    //addnav("Return to the forest","forest.php");
    $session[user][reputation]++;
    $session[user][specialinc]="";
}else if ($_GET[op]=="return"){
  output("`2Du erklärst dem Opa, dass du viel zu beschäftigt bist, um ihm zu helfen.`n`nKeine große Sache, er sollte in der Lage sein, den Weg zurück ");
    output("ins Dorf selbst zu finden. Immerhin hat er es ja auch vom Dorf hierher geschafft, oder? Ein Wolf heult links von dir in der Ferne und wenige Sekunden später ");
    output("antwortet ein anderer Wolf viel näher von rechts. Jawohl, der Mann sollte in Sicherheit sein.");
    //addnav("Zurück in den Wald","forest.php");
    $session[user][specialinc]="";
}
?>


