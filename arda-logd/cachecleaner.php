<?php

/*****************************************************************
*                                                                *
*                      Cache-Files löschen                       *
*     für alle die das Cache-File-System von Eliwood nutzen      *
*   Idee und Programmierung von Linus für alvion-logd.de/logd/   *
*                        im Dezember 2007                        *
*                                                                *
******************************************************************/

require_once "common.php";
isnewday(2);

page_header("Überflüssige Cache-Files löschen");

function analysis1(){
   $i=0;
   foreach(glob("cache/*.txt") as $filename){
      $cachefile[$i]['0']=substr($filename,7,strlen($filename)-11);
      $cachefile[$i]['1']=0;
      $i++;
   }
   return $cachefile;
}

function analysis2(){
   $i=0;
   $sql="SELECT `acctid` FROM `accounts`";
   $result=db_query($sql);
   while ($row = db_fetch_assoc($result)) {
      $accts[$i]=(int)$row['acctid'];
      $i++;
   }
   return $accts;
}

$out="`bHier kannst du unnütze Cache-Files löschen.`b`n`n";
switch($_GET['op']){
   case "":
      addnav("Cache-Analyse","cachecleaner.php?op=analyse");
      break;

   case "deleteall":
      $cachefile=array();
      $cachefile=analysis1();
      extract($cachefile);

      $accts=array();
      $accts=analysis2();
      extract($accts);

      $i=0;
      foreach($cachefile as $key => $value){
         if(!in_array((int)$value['0'],$accts)){
            $file="cache/c".$value['0'].".txt";
            unlink($file);
            $i++;
         }
      }
      $out.="$i Cache-Files gelöscht!`n`n";
      addnav("Zurück");
      addnav("Zurück","cachecleaner.php");
      break;

   case "analyse":
      if(isset($_GET['acctid'])){
         $file="cache/c".$_GET['acctid'].".txt";
         unlink($file);
      }

      $i=0;
      $cachefile=array();
      $cachefile=analysis1();
      extract($cachefile);
      $anzahl_cache=count($cachefile);

      $accts=array();
      $accts=analysis2();
      extract($accts);
      $anzahl_accts=count($accts);

      $fehl=0;
      $gut=0;
      $i=0;

      foreach($cachefile as $key => $value){
         if(in_array((int)$value['0'],$accts)){
            $cachefile[$i]['1']=1;
            $gut++;
         } else {
            $fehl++;
         }
         $i++;
      }

      $out.="Anzahl Cache-Files gesamt: ".$anzahl_cache."`nCache-Files von existierenden Spielern: ".$gut."`nCache-Files von gelöschten Spielern: ".$fehl."`n`n";
      $out.="<table><tr><th colspan='2'`^`bCache-Files von gelöschten Spielern:`b</th></tr>";
      $out.="<form action='cachecleaner.php?op=analyse&act=del' method='POST'>";
      for($i=0;$i<$anzahl_cache;$i++){
         if($cachefile[$i]['1']==0){
            $out.="<tr><td>{$cachefile[$i]['0']}</td>";
            $out.="<td><a href='cachecleaner.php?op=analyse&act=del&acctid={$cachefile[$i]['0']}'>Löschen</a></td></tr>";
            addnav("","cachecleaner.php?op=analyse&act=del&acctid={$cachefile[$i]['0']}");
         }
      }

      if($fehl!=0){
         addnav("Alle löschen");
         addnav("Aufräumen","cachecleaner.php?op=deleteall");
      } else {
         $out.="<tr><td>Es gibt zur Zeit keine unnützen Cache-Files!</td></tr>";
      }
      $out.="</table>";
      addnav("Zurück");
      addnav("Zurück","cachecleaner.php");

      break;
}

$out.="<div align='center'><a href=http://www.alvion-logd.de/logd/ target='_blank'>&copy;`^ von Linus im Dezember 2007`0</a></div>";
output($out,true);

addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

page_footer();
?> 