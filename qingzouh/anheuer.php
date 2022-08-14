
<?php

require_once "common.php";

page_header("Kaserne");
output("`c`b`7Alte Kaserne`c`b");
if($_GET[op]==""){

output("`7Du kommst an eine kleine Kaserne, die am Rande des Schwarzen Moors steht. Die Kaserne wurde aus alten Eichenholz gebaut und ein kleines, geöffnetes Tor führt auf den Hof der Kaserne. In der Mitte des Hofes sitzen einige Leute, die sich unterhalten. `nIn dieser Kaserne kannst du einen Gefährten für Gold anheuern, der dich im Kampf gegen Kreaturen dieser Welt unterstützt. Die Dienstleistungen eines Gefährten sind jedoch nicht ganz billig, aber sie zahlen sich aus. ");

addnav("Gefährten");

if($session[user][gold]>=5000){
addnav("`2Elisa, Waldläuferin `7(`^5000 Gold`7)`0","anheuer.php?op=elisa");
addnav("`9Gorn, Axtkämpfer `7(`^5000 Gold`7)`0","anheuer.php?op=gorn");
addnav("`+Essar, Nekromant `7(`^5000 Gold`7)`0","anheuer.php?op=essar");
}

if($session[user][gold]>=10000){
addnav("`=Dorasch, Schamane `7(`^10000 Gold`7)`0","anheuer.php?op=dorasch");
addnav("`aNoktus, Hexenmeister `7(`^10000 Gold`7)`0","anheuer.php?op=noktus");

}


if($session[user][gold]>=25000){
addnav("`dKathyr, Paladin `7(`^25000 Gold`7)`0","anheuer.php?op=kathyr");
}


addnav("Wege");
addnav("Zurück in den Wald","forest.php");
}

if($_GET[op]=="elisa"){

output("`7Du hast `2Elisa `7angeheuert!`nSie wird dir im Kampf gegen alle Kreaturen dieser Welt helfen, bis sie ihren Dienst bei dir abgeleistet hat.");
$session[user][gold]-=5000;
$session['bufflist'][101] = array("name"=>"`2Elisa","rounds"=>30,"wearoff"=>"Elisa macht sich aus dem Staub.","atkmod"=>1.25,"roundmsg"=>"Elisa greift mit ihrem Bogen den Gegner an.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}


if($_GET[op]=="gorn"){

output("`7Du hast `9Gorn `7angeheuert!`nEr wird seine Axt für dich schwingen, bis er seinen Dienst bei dir abgeleistet hat.");
$session[user][gold]-=5000;
$session['bufflist'][101] = array("name"=>"`9Gorn","rounds"=>30,"wearoff"=>"Gorn macht sich aus dem Staub.","atkmod"=>1.25,"roundmsg"=>"Gorn greift mit seiner Axt den Gegner an.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}

if($_GET[op]=="essar"){

output("`7Du hast `+Essar `7angeheuert!`nEr wird deine Gegner vergiften und verhexen, bis er seinen Dienst bei dir abgeleistet hat.");
$session[user][gold]-=5000;

$session['bufflist'][101] = array("name"=>"`+Essar","rounds"=>30,"wearoff"=>"Essar macht sich aus dem Staub.","atkmod"=>1.25,"roundmsg"=>"Essar greift den Gegner mit seinem Chaos-Stab an.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}


if($_GET[op]=="dorasch"){

output("`7Du hast `=Dorasch `7angeheuert!`nEr wird deine Gegner verzaubern und attackieren, bis er seinen Dienst bei dir abgeleistet hat.");
$session[user][gold]-=10000;

$session['bufflist'][101] = array("name"=>"`=Dorasch","rounds"=>30,"wearoff"=>"Dorasch macht sich aus dem Staub.","atkmod"=>2,"roundmsg"=>"Dorasch verhext deinen Gegner, der sich daraufhin selber verletzt.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}


if($_GET[op]=="noktus"){

output("`7Du hast `aNoktus `7angeheuert!`nEr wird deine Gegner verhexen, bis er seinen Dienst bei dir abgeleistet hat.");
$session[user][gold]-=10000;

$session['bufflist'][101] = array("name"=>"`aNoktus","rounds"=>30,"wearoff"=>"Noktus macht sich aus dem Staub.","atkmod"=>2,"roundmsg"=>"Noktus attackiert den Gegner mit einem Kugelblitz.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}


if($_GET[op]=="kathyr"){

output("`7Du hast `dKathyr `7angeheuert!`nEr wird deine Gegner zusammen mit dir bekämpfen, bis er seinen Dienst bei dir abgeleistet hat.");
$session[user][gold]-=25000;

$session['bufflist'][101] = array("name"=>"`dKathyr","rounds"=>30,"wearoff"=>"Kathyr macht sich aus dem Staub.","atkmod"=>2.5,"roundmsg"=>"Kathyr stürzt sich mit seinem Runenhammer in den Kampf.","activate"=>"offense","survivenewday"=>1);

addnav("Wege");
addnav("Zurück in den Wald","forest.php");

}



page_footer();

?>

