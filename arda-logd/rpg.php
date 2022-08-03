<?php

//Beginn RPG-Level-System
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//            
              //erstellt die Variablen
        $rpgplacegeld = explode(',',getsetting('rpgplacegeld','all'));
        $rpgplaceedels = explode(',',getsetting('rpgplaceedels','all'));
        $rpgplacedonpoints = explode(',',getsetting('rpgplacedonpoints','all'));
        $rpgplaceexp = explode(',',getsetting('rpgplaceexp','all'));
        $rpgplacesee = explode(',',getsetting('rpgplacesee','all'));
        $rpggeld = getsetting('rpggeld','100');
        $rpgedels = getsetting('rpgedels','.1');
        $rpgdonpoints = getsetting('rpgdonpoints','2');
        $rpgprozent = getsetting('rpgprozent','500');
        $rpgexp = getsetting('rpgexp','100');
        $dkexp = getsetting('dkexp','2');
        $rpgsee = getsetting('rpgsee','5');
            
        if($session['user']['spirits']==''.RP_RESURRECTION.''){
    $spirit=0.1;
    }
    if($session['user']['spirits']==-2){
    $spirit=0.25;
    }
    if($session['user']['spirits']==-1){
    $spirit=0.50;
    }
    if($session['user']['spirits']==0){
    $spirit=0.1;
    }
    if($session['user']['spirits']==1){
    $spirit=1.25;
    }
    if($session['user']['spirits']==2){
    $spirit=1.5;
    }


//Auswertung und Belohnung Posts
if($session['user']['turns']>0 && $session['user']['admin']==1 || $session['user']['turns']>0 && $session['user']['admin']==2 || $session['user']['turns']>0 && $session['user']['admin']==4)
 {
         $session['user']['turns']--;
  if(in_array($section,$rpgplacegeld) || $rpgplacegeld[0]=='all')
   {
         $session['user']['gold']=$session['user']['gold']+($rpggeld*$session['user']['level']);
   }
    if(in_array($section,$rpgplaceedels) || $rpgplaceedels[0]=='all')
   {    
             $session['user']['gems']=$session['user']['gems']+$rpgedels;
     }
  if(in_array($section,$rpgplacedonpoints) || $rpgplacedonpoints[0]=='all')
   {    
             $session['user']['donation']=$session['user']['donation']+($rpgdonpoints/*($commentary/$rpgprozent)*/);
     }
  if(in_array($section,$rpgplaceexp) || $rpgplaceexp[0]=='all')
   {    
             $session['user']['experience']=$session['user']['experience']+($rpgexp*$session['user']['level']+($session['user']['dragonkills']*$dkexp));
     }
  if(in_array($section,$rpgplacesee) || $rpgplacesee[0]=='all')
   {    
             $session['user']['reputation']=$session['user']['reputation']+$rpgsee;

  }
 }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
            //Ende RPG-Level-System

?>