<?php
//*-------------------------*
//|      hohlerbaum.php     |
//|        Scriptet by      |
//|       °*Amerilion*°     |
//| steffenmischnick@gmx.de |
//*-------------------------*
//Überarbeitet für www.kokoto.de von Tidus

$gold=($session['user']['level']100);
if (!isset($session)) exit();
output("`n`c`b`@Der hohle Baum`b`c`n`n");
if($_GET['op']=='w'){
output('`2Du bückst dich und fasst in das Loch hinein,');
switch(mt_rand(1,4)){
case 1:
       output("und fühlst einen kleinen Lederbeutel mit Gold!`n`n`^Du bekommst $gold Goldstücke.");
       $session['user']['gold']+=$gold;
	   $session['user']['specialinc']='';
       break;
case 2:
case 3:
       output('und fühlst einen stechenden Schmerz an deiner Hand. Bienen haben sich hier ihr Nest gebaut.Sie stechen dir die Hand wund so dass du nicht mehr so gut kämpfen kannst.');
       if($session['user']['turns']>3){
       output('Außerdem sie verfolgen dich, so das du drei Runden verlierst.');
       $session['user']['turns']-=3;
       }
       $session['user']['hitpoints']=$session['user']['hitpoints']0.8;
                  $session['bufflist']['bienen'] = array("name"=>"`6Bienenstiche",
                  "rounds"=>25,
                  "wearoff"=>"`6Deine Hand schmerzt nicht mehr.",
                  "defmod"=>0.9,
                  "atkmod"=>0.9,
                  "roundmsg"=>"`6Deine Hand schmerzt",
                  "activate"=>"defense");
				  $session['user']['specialinc']='';
       break;
case 4:
       output('Du findest ein kleines Eichhörnchen, welches du auf einen stabilen Baum setzt. Genau in dem Moment stürtzt der morsche Baum um und dir erscheint eine kleine Fee.`i`v"Du hast das Eichhörnchen vor einem sicheren Tod bewahrt das war sehr nobel von dir, für eine Zeit wird dir der Geist der Eichhörnchen beistehen."`i`2,spricht sie und verschwindet in einem kleinen Lichtblitz.');
                  $session['bufflist']['eichhorn'] = array("name"=>"`2Geist der Eichhörnchen",
                  "rounds"=>20,
                  "wearoff"=>"`2Der Geist der Eichhörnchen verlässt dich",
                  "atkmod"=>1.1,
                  "minioncount"=>3,
                  "minbadguydamage"=>10,
                  "maxbadguydamage"=>20,
                  "effectmsg"=>"`2Eichhörnchengeister helfen dir!",
                  "activate"=>"offense");
				  $session['user']['specialinc']='';
       break;
}
}else if($_GET['op']=='z'){
output('`2Du machst dir nichts aus hohlen Bäumen und gehst weiter, ein kleiner Affe springt dich plötzlich von hinten an und stiehlt greift in deinen Edelsteinbeutel.`n');
if($session['user']['gems']<1){
output('`#Du hast zum Glück kein Edelstein gehabt.`n');
}else{
output('`$Du verlierst 1 Edelstein.`n');
$session['user']['gems']-=1;
}
output('`n`2Er muss sich wohl in dem Baum versteckt haben...');
$session['user']['specialinc']='';
}else{

output('`2Du bemerkst am Wegrand einen Baum. Dieser hat an der einen Seite eine kleine Öffnung, welche von deinem Bauch bis zum Boden reicht, und am Boden etwa 2 Fuss groß ist, nach oben aber spitz zuläuft.');
addnav('Der Baum');
$session['user']['specialinc']='hohlerbaum.php';
addnav('Fasse hinein','forest.php?op=w');
addnav('Gehe weiter','forest.php?op=z');
}
?>