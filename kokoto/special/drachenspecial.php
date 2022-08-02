<?php
/*
* Erstellt von Tidus (www.kokoto.de)
* Neue version dieses Specials! Einfach in den Specialordner Kopieren
* Wer diese Version einbauen will, muss diese sichtbar im Source liegen haben, ein verstecken der Datei ist Verboten.
*/

if (!isset($session)) exit();

if ($_GET['op']=='rein'){
   switch (mt_rand(1,5)){
         case '1':
         output('In der Höhle ist es so dunkel das man überhaupt nix sieht. Plötzlich spürst du ein Schnaufen im Nacken...`n');
         output('Der Grüne Drachen steht hinter dir ohne zu überlegen rennst du weg, schneller als du es je gerannt bist.');
         addnews($session['user']['name'].'`0 wurde gesehen, wie '.($session['user']['sex']?"sie":"er").', weinend wie ein Baby, aus der Höhle des Grünen Drachen rannte!');
         if($session['user']['turns']>=3){
         $session['user']['turns']-=3;
         output('`9 `nDu verlierst 3 Waldkämpfe weil du Dich ausruhen musst.');
         }else{
         $session['user']['turns']=0;
         output('`9 `n Du verlierst deine restlichen Waldkämpfe');
         }
         $session['user']['specialinc']='';
         addnav('Zurück in den Wald','forest.php');
         break;
         case '2':
         output('In einer Höhle liegen Leichen du nimmst dir die Zeit und begrägbst diese. ');
         output('`9 `nEine Fee kommt und Segnet dich mit 5 Waldkämpfen weil du so gehandelt hast. Dein Ruf hat sich verbessert.');

         $session['user']['turns']+=5;
         $session['user']['reputation']+=10;
         addnews($session['user']['name'].'`0 wurde dabei beobachtet wie '.($session['user']['sex']?"sie":"er").' in einer Höhle von einer Fee gesegnet wurde!');
         $session['user']['specialinc']='';
         addnav('Zurück in den Wald','forest.php');
         break;
         case '3':
         output('Du nimmst eine Fackel mit und läufst tiefer und tiefer in die Höhle hinein. Du findest du eine Schatztruhe die du öffnest....');
         output('`9`n Du Bekommst 5 Edelsteine 5000 Gold.');
         output('`nDu verlierst einen Waldkampf.');
         $session['user']['gold']+=5000;
         $session['user']['gems']+=5;
         $session['user']['turns']-=1;
         addnews($session['user']['name'].'`0 wurde gesehen als '.($session['user']['sex']?"sie":"er").' mit einem Schatz aus einer Höhle herauskam!');
         $session['user']['specialinc']="";
         addnav('Zurück in den Wald','forest.php');
         break;
         case '4':
         output('Du gehst in die Höhle, dort wartet Aphrodite auf dich...');
         output('`9`nDu wurdest mit 5 Charmepunkten gesegnet!!!');
         output('`nDu verlierst einen Waldkampf');
         $session['user']['turns']-=1;
         $session['user']['charm']+=5;
         addnews($session['user']['name'].'`0 Ist in eine Höhle gegangen und kam viel schöner wieder heraus!');
         $session['user']['specialinc']='';
         addnav('Zurück in den Wald','forest.php');
         break;
         case '5':
         output('Du betrittst die Höhle du bist sehr angsterfüllt in einer Höhle wohnt ja meistens was. Da erblickst du ein Drachenei als du es einpacken wolltest hörst du ein lautes Stampfen..');
         output('`nDer Grüne Drachen kommt in Die Höhle du siehst keinen ausweg...');
         output('`9`n Du bist `bTOT`b der Grüne Drachen hat dich gefressen.`n Du verlierst alles Gold und alle Edelsteine!');
	$session['user']['specialinc']='';
        addnav('Tägliche news','news.php');
        $session['user']['gold']=0;
        $session['user']['gems']=0;
        $session['user']['hitpoints']=0;
        $session['user']['alive']=false;
        addnews($session['user']['name'].'`0 wurde beim stehlen vom Ei des Grünen Drachen vom selben gefressen');
         break;
   }
   }else if ($_GET['op']=='raus'){
   output('Du gehst ganz unauffällig an der Höhle vorbei wird ja keiner gemerkt haben...');
   $session['user']['specialinc']='';
   addnav('Zurück in den Wald','forest.php');
}else{
output('Du läufst so durch den Wald, alle Monster scheinen sich zu
verstecken weit und breit ist kein zu sehen. Du bist sehr deprimiert darüber doch dann erblickst du diese Höhle....');

   addnav('Willst du in die dunkle Höhle gehen??');
   addnav('In Die Höhle gehen','forest.php?op=rein');
   addnav('Feige wegrennen','forest.php?op=raus');
   $session['user']['specialinc'] = 'drachenspecial.php';
}
page_footer();
?>