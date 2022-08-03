<?php 
//°-------------------------°
//|      einsiedler.php     |
//|         Idee by         |
//|      Merlin/Kakeru      |
//|        Script by        |
//|        xitachix         |
//|      mcitachi@web.de    |
//°-------------------------°
//http://logd.macjan.de/

if (!isset($session)) exit();
if ($_GET['op']==""){
output("`n`c`&Der Einsiedler`c`n`n");
output("`n`VDu stolperst über einen Alten Einsieder der bietet dir für dein gesamtes gold eine zufällige ware an.");
$session['user']['specialinc']="einsiedler.php";
if($session['user']['gold']>1) addnav("Ware kaufen","forest.php?op=gold");
addnav("Zurück in den Wald","forest.php?op=z");
}
if ($_GET['op']=="gold"){
output("`n`VDu greifst in deine Taschen und holst all dein Gold heraus.");
$session['user']['specialinc']="einsiedler.php";
            $session['user']['gold']=0;
        switch(e_rand(1,5)){ 
            case 1: 
            output("`n`3Der Einsiedler nimmt dein Gold und hält dir seine Ware hin."); 
            output("`n`$ Du erhälst nichts!");
            break; 
            case 2: 
            output("`n`3Der Einsiedler nimmt dein Gold und hält dir seine Ware hin."); 
            output("`n`$ Du erhälst 5 EDELSTEINE!");
            $session['user']['gems']+=5;
            break;
            case 3: 
            output("`n`3Der Einsiedler nimmt dein Gold und hält dir seine Ware hin."); 
            output("`n`$ Du erhälst 10 EDELSTEINE!"); 
            $session['user']['gems']+=10;
            break;
            case 4:
            output("`n`3Der Einsiedler nimmt dein Gold und hält dir seine Ware hin. Jedoch war seine Ware ein Schlag, der dich tötet."); 
            output("`n`$ Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            addnav("Tägliche News","news.php");
            addnews("`#".$session['user']['name']." `0 wurde von einem Einsiedler im Wald getötet");
            break;
            case 5: 
            output("`n`3Der Einsiedler nimmt dein Gold und hält dir seine Ware hin."); 
            output("`n`$ Du erhälst 15 EDELSTEINE!"); 
            $session['user']['gems']+=15;
            break;
            }
      }
if ($_GET['op']=="z"){
output("`n`^Du rennst davon und stolperst über einen Stein");
            output("`n`$Du verlierst ein paar Lebenspunkte");
            $hurt = e_rand($lvl,3*$lvl);
            $session['user']['hitpoints']-=$hurt;

}
?> 