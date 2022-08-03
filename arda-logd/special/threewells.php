<?php

/******************

* Author: Hadriel
* Idea: Hadriel
* Filename: threewells.php
* Descr: Three wells in the forest

******************/

if(!isset($session['user']['specialinc'])) exit();
  
$well=array();

$well[1]['name']="Brunnen des Tigers";
$well[1]['drowtext']="Drital dal uns'aa lu' tlu gareth saph natha oura.";
$well[1]['normaltext']="Trink aus mir und werde stark wie ein Tiger.";
$well[1]['id']=1;
$well[1]['nick']="Erster";

$well[2]['name']="Brunnen des Menschen";
$well[2]['drowtext']="Drital dal uns'aa lu' satiir dos endar.";
$well[2]['normaltext']="Trink aus mir und du fühlst dich anders.";
$well[2]['id']=2;
$well[2]['nick']="Zweiter";

$well[3]['name']="Brunnen der Gnome";
$well[3]['drowtext']="Drital dal uns'aa lu' satiir dos inlul.";
$well[3]['normaltext']="Trink aus mir und werde winzig.";
$well[3]['id']=3;
$well[3]['nick']="Dritter";

$wellsetting['lang']=1; //Drown text (Set to '0' if you use human language)

switch($_GET['op']){
   case "":
   case "search":
     $session['user']['specialinc']="threewells.php";
   $ausgabe="Du wanderst durch den Wald, als du ein merkwürdiges Schimmern von rechts bemerkst."
           ."`nAls du näher gehst, bemerkst du 3 Brunnen, die alle unterschiedlich aussehen.`n"; 
   for($i=1;$i<count($well)+1;$i++){
     addnav($well[$i]['nick']." Brunnen","forest.php?op=well&well=".$well[$i]['id']);
     $ausgabe .="<a href='forest.php?op=well&well=".$well[$i]['id']."'><img height='200' weight='290' src='./images/well_r".$well[$i]['id'].".gif' border='0'></a> &nbsp;";
     addnav("","forest.php?op=well&well=".$well[$i]['id']."");
     }
   addnav("Weggehen","forest.php?op=flee");
     output($ausgabe,true);
   break;
   case "well":
     if($wellsetting[lang]==1)$ausgabe=$well[$_GET['well']]['drowtext']; else $ausgabe=$well[$_GET['well']]['normaltext'];
     $ausgabe.="`n`n<font size='+2'>".$well[$_GET['well']]['name'];
     $ausgabe.="</font>`n`nDu trinkst aus dem Brunnen";
     if($well[$_GET['well']]['id']==1){ $session['user']['attack']++; $ausgabe.=" und fühlst dich viel stärker";
       }else if($well[$_GET['well']]['id']==2){ $session['user']['charm']+=10; $ausgabe.=" und fühlst dich viel schöner";
        } else if($well[$_GET['well']]['id']==3){ $session['user']['maxhitpoints']+=3; $ausgabe.=" und fühlst dich viel lebendiger"; }
     $session['user']['specialinc']="";
     output($ausgabe,true);
   break;
   case "flee":
     $session['user']['specialinc']="";
   break;
  }
?> 