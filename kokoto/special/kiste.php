<?php 

//Kiste Special 
//Idee von Taikun
//umgesetzt vonTaikun
//gefixt am 05.07.05 von Taikun 
//04.07.2005 
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit(); 


if ($_GET['op']=='ja'){
   switch (mt_rand(1,6)){ 
         case '1': 
         output('Du öffnest die kiste aber sie ist leer. Entäuschst ziehst du von dannen. Du verlierst 2 Waldkämpfe'); 
         $session['user']['turns']-=2; 
         addnav('weiter','forest.php'); 
         $session['user']['specialinc']='';
         break; 
         case '2': 
         output('Du öffnest die Kiste und findest das Buch eines Kriegers. Du bekommst etwas Erfahrung'); 
         $xp=$session['user']['level']10; 
         $session['user']['experience']+=$xp; 
         addnav('weiter','forest.php'); 
         $session['user']['specialinc']='';
         break; 
         case '3': 
         output('Du öffnest die Kiste und findest Gesichtscreme. Du trägst sie auf und bekommst einen Charmpunkt.'); 
         $session['user']['charm']+=1; 
         addnav('weiter','forest.php'); 
         $session['user']['specialinc']='';
         break; 
         case '4': 
         output('Du öffnest die Kiste und findest zwei Edelsteine'); 
         $session['user']['gems']+=2; 
         addnav('weiter','forest.php'); 
         $session['user']['specialinc']='';
         break; 
         case '5': 
         output('Du öffnest die kiste und sie ist voller Gold. Du sammelst das Gold ein und gehst weiter deines weges'); 
         $session['user']['gold']+=5000; 
         addnav('weiter','forest.php'); 
         $session['user']['specialinc']='';
         break; 
         case '6': 
         output('Gerade als du die kiste öffnen willst feuert ein Räuber einen giftigen Pfeil ab, der genau deinen Rücken trifft. Du verendest elendig am Gift.'); 
         addnews($session['user']['name']."`0 krepierte an einem giftigen Pfeil"); 
         addnav('Tägliche news','news.php'); 
         $session['user']['specialinc']='';
         $session['user']['gold']=0; 
         $session['user']['hitpoints']=0; 
         $session['user']['alive']=false; 
         break; 

 } 


}else if ($_GET['op']=='nein'){
   output('Du ignorierst die kiste einfach und gehst weiter deines weges.'); 
   addnav('zurück in den Wald','forest.php'); 
    
$session['user']['specialinc']=''; 

}else{
   output('Auf deinem Weg findest du eine kleine Kiste. Du fragst dich ob du sie öffnen willst oder nicht.'); 
   addnav('Die Kiste öffnen ?'); 
   addnav('Ja','forest.php?op=ja'); 
   addnav('Nein','forest.php?op=nein'); 
   $session['user']['specialinc'] = 'kiste.php'; 
  
}
page_footer(); 
?> 