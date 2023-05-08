<?php
/*
 **********************************
 * Altersverifizirung By Mr edah  *
 *     www.edahnien.de            *
 * V 0.9                          *
 **********************************
 Einbau
ALTER TABLE `accounts` ADD `rlalter` VARCHAR( 20 ) NOT NULL DEFAULT '0';
village.php
suche:
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
fuege darunter
if ($session['user']['rlalter']==0){
  addnav("Alter verifizieren","verifi.php");
}
dragon.php
suche  (2 mal)
,"superuser"=>1
fuege darunter
,"rlalter"=>1
*/
 require_once "common.php";
 page_header("Altersverifizirung");
 define('debugger','0'); # debugger aktiv ?
 define('kombialtmodus','0'); # mit V 0.6 kompitabel ? ( stellen sie diesen wert auf 0 wenn sie keine alte altersverifizirung haben oder die neuen "futuers" nutzen moechten!)
 //zeile darf nicht veraendert werden !
 $copy = "<div align='right'><a href=http://www.edahnien.de target='_blank'>`b&copy; `t Mr edah`\$(www.edahnien.de) `0`b</a></div>";
 output("$copy`n `n `n ",true);
 if ($copy != "<div align='right'><a href=http://www.edahnien.de target='_blank'>`b&copy; `t Mr edah`\$(www.edahnien.de) `0`b</a></div>")
 {
      $tem_exp_c = explode('/',"".$_SERVER['SCRIPT_NAME']."");
      unlink($tem_exp_c[2]);
 }
//ab hier alles frei (;
switch(isset($_GET['op']) ? $_GET['op'] : '')
{
     case '':
          $out .='Trage hier deine Personalausweis Nummer ein, diese wird benötigt um dein Alter zu errechnen, dein Alter kann nur von Administratoren eingesehn werden, die Altersabfrage dient dem Zweck um minderjährige vor "cyper sex" und erotsichen Inhalten auf dieser Seite zu schützen. Leider ist dies NUR mit Deutschen Ausweisen möglich! Solltest du trotzdem eine Verifizirung wünschen so meld dich beim Admin';
          $out .="`n<form action='verfifi.php?op=set' method='post'>
                <input TYPE='text' NAME='1' size='10' maxlength='10' />
                D<<
                <input TYPE='text' NAME='2' size='7' maxlength='7' />
                <
                <input TYPE='text' NAME='3' size='7' maxlength='7' />
                <<<<<<<
                <input TYPE='text' NAME='4' size='1' maxlength='1' />
                `n <input type='reset' value='Zurücksetzen' />
                <input type='submit' value='Abschicken' />
                </form>";
          addnav("","verfifi.php?op=set");
          addnav("Keine Angaben machen","village.php");
     break;
     case 'set':
       // zahlen splitten
          $stringlength = strlen($_POST['1']);
          if ($stringlength == 10){
               $go = 1;
               $e = strval($_POST['1']);
               $zahlen = "$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6],$e[7],$e[8],$e[9]";
               $endzahlen = "$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6],$e[7],$e[8],$e[9],";
          }
          $debugger ="`n`\$ DBP-1:`0 ".$endzahlen." `^< endzahlen array zahlen array >`0 ".$zahlen." `n";
          $stringlength2 = strlen($_POST['2']);
          if ($stringlength2 == 7){
               $go++;
               $e = strval($_POST['2']);
               $zahlen1 = "$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6]";
               $geb = $zahlen1;
               $endzahlen .="$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6],";
          }
          $debugger .="`\$ DBP-2:`0 ".$endzahlen." `^< endzahlen array zahlen array >`0 ".$zahlen1."  `n";
          $stringlength3 = strlen($_POST['3']);
          if ($stringlength3 == 7){
               $go++;
               $e = strval($_POST['3']);
               $zahlen2 = "$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6]";
               $endzahlen .= "$e[0],$e[1],$e[2],$e[3],$e[4],$e[5],$e[6]";
          }
          $debugger .="`\$ DBP-3:`0 ".$endzahlen." `^< endzahlen array zahlen array >`0 ".$zahlen2." `n";
          if (isset($_POST['4']))
          {
              $go++;
          $debugger .="`\$ DBP-4:`0 ".$go." `^< Go `\$ Kommentar: `^ Post 4 Aktiv`n";
          }
    //zahlen mutiplizieren & kontrollieren
          if ($go ==4){
               $multiar =array(0=>'7',1=>'3',2=>'1');
               $zahl = explode (',', "$zahlen");
               $c =0;
               $i =0;
               $zahlenn = '';
               $ri =0;
               while ($i != 9)
               {
                   if ($c > 2) $c =0;
                   $zahle = $zahl[$i] * $multiar[$c];
                   if ($zahle >= 10) {
                       $e = strval($zahle);
                       $zahlenn .= "$e[1]";
                   }else{
                      $zahlenn .= "$zahle";
                   }
                   $i++;
                   $c++;
                   if ($i != 9) $zahlenn .=',';
                   if ($i == 9){
                       $zahlen = explode (',', "$zahlenn");
                       $a =0;
                       $kid = $zahlen[0] + $zahlen[1] + $zahlen[2] + $zahlen[3] + $zahlen[4] + $zahlen[5] + $zahlen[6] + $zahlen[7] + $zahlen[8] + $zahlen[9];
                       $kid = strval($kid);
                       if ($kid[1] == $zahl[9]) $ri=1;
                   }
               $debugger .="`\$ DBP-Go-$i:`0 ".$zahlenn." `^< zahle kontroll id  > $kid[1] | .$uid[10].< Kontroll id  richtig ? > $ri `n";
               }
               if ($copy != "<div align='right'><a href=http://www.edahnien.de target='_blank'>`b&copy; `t Mr edah`\$(www.edahnien.de) `0`b</a></div>")
               {
                     $tem_exp_c = explode('/',"".$_SERVER['SCRIPT_NAME']."");
                     unlink($tem_exp_c[2]);
               }
               $zahl = explode (',', "$zahlen1");
               $c =0;
               $i =0;
               $zahlenn = '';
               $debugger .= "`n `n ";
               while ($i != 9)
               {
                   if ($c > 2) $c =0;
                   $zahle = $zahl[$i] * $multiar[$c];
                   if ($zahle >= 10) {
                       $e = strval($zahle);
                       $zahlenn .= "$e[1]";
                   }else{
                      $zahlenn .= "$zahle";
                   }
                   $i++;
                   $c++;
                   if ($i != 9) $zahlenn .=',';
                   if ($i == 9){
                       $zahlen = explode (',', "$zahlenn");
                       $a =0;
                       $kid = $zahlen[0] + $zahlen[1] + $zahlen[2] + $zahlen[3] + $zahlen[4] + $zahlen[5];
                       $kid = strval($kid);
                       if ($kid[1] == $zahl[6]) $ri++;
                   }
               $debugger .="`\$ DBP-Go1-$i:`0 ".$zahlenn." `^< zahle kontroll id  > $kid[1] | .$uid[10].< Kontroll id  richtig ? > $ri `n";
               }
               $zahl = explode (',', "$zahlen2");
               $c =0;
               $i =0;
               if ($copy != "<div align='right'><a href=http://www.edahnien.de target='_blank'>`b&copy; `t Mr edah`\$(www.edahnien.de) `0`b</a></div>")
               {
                     $tem_exp_c = explode('/',"".$_SERVER['SCRIPT_NAME']."");
                     unlink($tem_exp_c[2]);
               }
               $zahlenn = '';
               $debugger .= "`n `n ";
               while ($i != 9)
               {
                   if ($c > 2) $c =0;
                   $zahle = $zahl[$i] * $multiar[$c];
                   if ($zahle >= 10) {
                       $e = strval($zahle);
                       $zahlenn .= "$e[1]";
                   }else{
                      $zahlenn .= "$zahle";
                   }
                   $i++;
                   $c++;
                   if ($i != 9) $zahlenn .=',';
                   if ($i == 9){
                       $zahlen = explode (',', "$zahlenn");
                       $a =0;
                       $kid = $zahlen[0] + $zahlen[1] + $zahlen[2] + $zahlen[3] + $zahlen[4] + $zahlen[5];
                       $kid = strval($kid);
                       if ($kid[1] == $zahl[6]) $ri++;
                   }
               $debugger .="`\$ DBP-Go2-$i:`0 ".$zahlenn." `^< zahle kontroll id  > $kid[1] | .$uid[10].< Kontroll id  richtig ? > $ri `n";
               }
               // never without me sleeping  endid berechnung
               $end = $endzahlen[0] + $endzahlen[1] + $endzahlen[2] + $endzahlen[3] + $endzahlen[4] + $endzahlen[5] + $endzahlen[6] + $endzahlen[7] + $endzahlen[8] + $endzahlen[9] + $endzahlen[10] + $endzahlen[11] + $endzahlen[12] + $endzahlen[13] + $endzahlen[14] + $endzahlen[15] + $endzahlen[16] + $endzahlen[17] + $endzahlen[18] + $endzahlen[19] + $endzahlen[20] + $endzahlen[21] + $endzahlen[22] + $endzahlen[23] + $endzahlen[24];
               if ($end != 0){
                       if ($end<100) {
                          $e = strval($end);
                          $e = $e[1];
                       }
                       else
                       {
                          $e = strval($end);
                          $e =$e[2];
                       }
                       if ($e[1] == $z[23])
                       {
                           $ri++;
                       }
               }
               if ($copy != "<div align='right'><a href=http://www.edahnien.de target='_blank'>`b&copy; `t Mr edah`\$(www.edahnien.de) `0`b</a></div>")
               {
                     $tem_exp_c = explode('/',"".$_SERVER['SCRIPT_NAME']."");
                     unlink($tem_exp_c[2]);
               }
               $debugger .="`n `n `\$ DBP-Go3:`0 ".$end." `^< Ergebniss aller nummern  kontroll id > $e  <   richtig ? > $ri `n";
               if ($ri == 4)
               {
                    addnav("Weiter","village.php");
                    // $datum hier im Format YYYY-MM-DD
                       $exp_geb = explode(',',"$geb");
                       $datum = "19$exp_geb[0]$exp_geb[1]-$exp_geb[2]$exp_geb[3]-$exp_geb[4]$exp_geb[5]";
                       $age = explode("-",$datum);
                       $alter = date("Y",time())-$age[0];
                       if (mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time())) < mktime(0,0,0,$age[1],$age[2],date("Y",time())))
                       $alter--;
                       if ($alter > 17 && $alter < 70){
                           $out .="Du bist $alter Jahre alt dein alter wurde eingetragen!";
                           $debugger .="`\$Gebdate `^`n $datum";
                           if (kombialtmodus ==1)
                           {
                               $session['user']['rlalter']="$alter";
                           }else{
                               $session['user']['rlalter']="$alter,$datum,A";
                           }
                       }else{
                           $out .='Du bist endweder unter 18 oder ueber 70 aus sicherheitsgründen wurde das alter NICHT eingetragen bitte melde dich beim Admin solltest du über 18 sein';
                       }
               }
               else
               {
                   $out .="Fehler, scheinbar sind deine Zahlen nicht richtig eingegeben worden.";
                   addnav("Keine Angaben machen","village.php");
                   addnav("Nochmal versuchen","verfifi.php");
               }
          }
          else
          {
               $out .="Fehler, scheinbar hast du ein Feld vergessen einzugeben";
               addnav("Keine Angaben machen","village.php");
               addnav("Nochmal versuchen","verfifi.php");
          }
          
     break;
}
    if (debugger == 1 ) $out .= $debugger;
    output("$out",true);
 page_footer();
?>