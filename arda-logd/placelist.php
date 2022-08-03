<?php
/*
Superuser können mit diesem Editor auf alle aktiven Spielorte zugreifen und Kommentare lesen/schreiben/löschen 

Angezeigt werden section, Ortsbeschreibung, Anzahl an Kommentaren im Ort und die Superuser-Funktionen 
(im Ort schreiben, einzelne Kommentare löschen, Ort leeren) ;

Autor:   Blackfin
Datum: Oktober 2005 
Version: 0.6a
Email:   blackfin@elfenherz.de
Für:       http://logd.elfenherz.de

Kompatibel mit LotgD 0.7+jt ext (GER) + houses MOD

-- benötigt zusätzlich viewplaces.php!! (in der Zip-Datei enthalten) ---

Die Ortsnamen müssen in der Datenbank dem jeweiligen Server angepasst werden.
dabei ist 'room' die section aus den commentary-einträgen, 'alias' ist die Ortsbeschreibung
Sie stellen eine Klartext-Erklärung der Orts-Abkürzungen in der "viewcommentary"-Funktion dar.
z.B. 'room' beggar => 'alias' "Der Bettelstein" etc.

Dynamisch erstelle Orte werden auch berücksichtigt, sie müssen jedoch nicht in die Datenbank!
(z.B. Häuserräume, Trampelpfad, Gilden usw.) 
evtl. ist eine Anpassung der Ortsnamen in den einzelnen php-Dateien von Nöten, da die Ortsnamen keine Umlaute 
und Sonderzeichen enthalten dürfen.  

Für die Häuser-Räume gilt folgendes String-Schema: house-x...xHausnummer
Für die Gilden-versammlungsräume: gildenhausGildennummer
Für die Trampelpfad-Räume: clearing_acctid1_acctid2


### Installation###

1) placelist.php in superuser.php mit op=viewcom verlinken =>  addnav("Orte ansehen","placelist.php?op=viewcom") ;

2) Neue Datenbank-Tabelle (da müssen die serverspezifischen Orte rein!):

DROP TABLE IF EXISTS `room_aliases`;
CREATE TABLE  `room_aliases` (
  `room` varchar(100) default NULL,
  `alias` varchar(100) default NULL,
  `autoid` smallint(5) NOT NULL auto_increment,
  PRIMARY KEY  (`autoid`)
) TYPE=MyISAM;

INSERT INTO `room_aliases` (`room`, `alias`, `autoid`) VALUES 
('beggar', 'Der Bettelstein', 1),
('pool', 'Waldsee', 2),
('motd', 'Kommentare in der MotD', 3),
('OOC', 'OOC-Diskussionen`n (Im Dorfamt)', 4),
('prison', 'Der Kerker', 5),
('shade', 'Land der Schatten', 6),
('superuser', 'Admin-Grotte', 7),
('village', 'Dorfplatz', 8),
('wald', 'Im tiefen Wald', 9),
('library', 'Drachenbibliothek', 10),
('hunterlodge', 'Jägerhütte', 11),
('veterans', 'Seltsamer Felsen', 12),
('rat', 'Diskussionsraum `n (Im Dorfamt)', 13),
('gardens', 'Der Garten', 14),
('fishing', 'Waldsee II', 15),
('tempel', 'Kirchliche Heirat`n(Im Tempel)', 16),
('priesterraum', 'Schrein der Prieser`n (Im Tempel)', 17),
('well', 'Brunnen des Dorfes`n(Wohnviertel)', 18),
('inn', 'Schenke zum Eberkopf', 19),
('pvparena', 'Die Arena', 20),
('grassyfield', 'Lichtung im Wald`n(Waldspecial)', 21),
('darkhorse', 'Tische der Darkhorse-Taverne', 22);

*/

// ^^ die obige Liste kann nach Belieben erweitert werden, klar...

require_once "common.php";

// Hausnamen ermitteln 
//(---- wer kein Häuser-Addon oder keine 'houses'-Tabelle hat , der kommentiert innerhalb der Funktion einfach alles aus ----)
function get_house_name($hausnummer) {    
    
    $sql7 = "SELECT housename FROM houses WHERE houseid='$hausnummer'" ;
    $result7 = db_query($sql7) or die(db_error(LINK));
      $hnrs = db_fetch_assoc($result7);      
      $this_house_name = $hnrs['housename'] ;      
      return $this_house_name ;
}

// Gildenname ermitteln 
// (--- es gilt das gleiche, wie bei den Häusern, wer nicht Eliwood's Gilden-Addon oder keine 'gilden'-Tabelle hat, in der Funktion auskommentieren! ---) 
function get_guild_name($gildennummer) {
    
    $sql7 = "SELECT gildenname FROM gilden WHERE gildenid='$gildennummer'" ;
      $result7 = db_query($sql7) or die(db_error(LINK));
      $hnrs = db_fetch_assoc($result7);      
      $this_guild_name = $hnrs['gildenname'] ;    
     return $this_guild_name ;
 }


isnewday(2);
addcommentary();

page_header("Kommentare aller Orte");
//Autor-Hinweis (bitte nicht entfernen!)
output("`n`@`b`c (Kommentareditor by <a href=\"mailto:blackfin@elfenherz.de\">blackFin</a> Okt 2005)`&`n`n`c`b",true) ;
addnav("","mailto:blackfin@elfenherz.de") ;

// Orte anzeigen
if ($_GET[op]=="viewcom" && $_GET[placeisset]==""){

  if(!$_POST[cplace]) {
    
           if(!isset($_POST['filter'])) $filter = "%%" ;
           else $filter = "%".$_POST['filter']."%" ;
           
           if($_GET['filter']!="" && !isset($_POST['filter'])) {
               $filter = "%".$_GET['filter']."%" ;               
           }
           
             $filter2 = str_replace("%","",$filter) ;
             
             
             
             $thisuser = $_POST['thisuser'] ;
             $seluser = $_GET['seluser'] ;
                    
             
           
             if($thisuser =="") {  
             
                 if($_GET['showlast']!="yes") output("`0<form action=\"placelist.php?op=viewcom&filter=$filter2\"method='POST'>",true);
                 else output("`0<form action=\"placelist.php?op=viewcom&showlast=yes&filter=$filter2&seluser=$seluser\"method='POST'>",true);
                output("`nOrts-Alias nach Suchmuster filtern: (leer lassen = alle Orte) `n`n `@Ortsname: `&<input name='filter' maxlength='25' value=''>",true);          
                output("<input type='submit' class='button' value='Filtern'></form>",true);
                         
                
                
                output("`n`n`0<form action=\"placelist.php?op=viewcom&user=$thisuser\"method='POST'>",true);
                output("`nDie letzen 5 Kommentare eines Mitspielers suchen: `n`n `@Spielername: `&<input name='thisuser' maxlength='25' value=''>",true);          
                output("<input type='submit' class='button' value='Suchen'></form>",true);
               }
           
           
            if($thisuser !="") {
                      
                       output("<table cellpadding=2 cellspacing=2><tr class='trdark'><td align='left' width='300'>`b`&Name`b</td><td align='center' width='20'>`b`^Acctid`b</td><td width='150' align='center'>`9Selektieren</td></tr>",true) ;
                      
                      $sql9 = "SELECT acctid,name FROM accounts WHERE 1" ;
                      $result9 = db_query($sql9) or die(db_error(LINK));
                      for ($b=0;$b<db_num_rows($result9);$b++){
                           $userload= db_fetch_assoc($result9);
                           $clear_name = strtolower(preg_replace("'[`].'","",$userload[name])) ;
                          if (strchr($clear_name,$thisuser)) {
                              $users_names_selected[$b] = $userload['name'] ;
                              $users_acctids_selected[$b] = $userload['acctid'] ;    
                              $this_acctid = $users_acctids_selected[$b] ;
                              output ("<tr class='trdark'><td><a href='placelist.php?op=viewcom&seluser=$this_acctid&showlast=yes'>`&{$users_names_selected[$b]}</a></td><td align='center'>`^{$users_acctids_selected[$b]}</td><td align='center'><a href='placelist.php?op=viewcom&seluser=$this_acctid&showlast=yes'> auswählen </a></td></tr>",true) ;
                              addnav("","placelist.php?op=viewcom&seluser=$this_acctid&showlast=yes") ;
                          }
                      }
                      
                      $usercount = count($users_acctids_selected) ;
                      
                      
                  output("</table>",true) ;
                  
              }
           
           
           
       if($thisuser =="" && $seluser =="") {    
           output("`n`n`c") ;
           if($_GET['showlast']!="yes") output("     <a href='placelist.php?op=viewcom&showlast=yes&filter=$filter2'>letzten Kommentar einblenden</a></form>",true) ;
           else output("     <a href='placelist.php?op=viewcom&filter=$filter2'>letzten Kommentar ausblenden</a>",true) ;
          output("`c") ;
         } 
           output("`n`n`n") ;
                  
          addnav("","placelist.php?op=viewcom&filter=$filter2");
          addnav("","placelist.php?op=viewcom&showlast=yes&filter=$filter2");
          addnav("","placelist.php?op=viewcom&showlast=yes&filter=$filter2&seluser=$seluser");
          addnav("","placelist.php?op=viewcom&user=$thisuser");
           
           
           
           
           
                      
           $housenr = 3000 ;
           
           // Wer keine dynamisch erstellen Räume hat und somit ALLE! seine Orte in der room_aliases-Tabelle spezifiziert hat,
           // kann auch den auskommentierten SQL-String verwenden und somit auch nach Beschreibungen suchen, 
           // statt nur nach Aliases  (Vorsicht, jedes LoGD hat normalerweise dynamische Räume!)        
           
           //$sql3 = " SELECT DISTINCT commentary.section,room_aliases.alias,room_aliases.room FROM commentary INNER JOIN  room_aliases ON commentary.section=room_aliases.room WHERE commentary.section LIKE '$filter' OR room_aliases.alias LIKE '$filter'";
          if($seluser =="")  $sql3 = " SELECT DISTINCT section FROM commentary WHERE section LIKE '$filter' ";
          else $sql3 = " SELECT DISTINCT section FROM commentary WHERE section LIKE '$filter' AND author='$seluser'";
           
           $result3 = db_query($sql3) or die(db_error(LINK));
           $ortszahl = db_num_rows($result3)-1;
              
               for ($i=0;$i<db_num_rows($result3);$i++){
            $ortsliste= db_fetch_assoc($result3);
            $sorte[$i] = $ortsliste['section'] ;
    }
         
            
            output("<center><table cellpadding=4 cellspacing=4><tr class='$bgcolor'><td align='center' width='50'>`b`^Alias`b</td><td align='center' width='300'>`b`8Ort`b</td><td align='center'>`b`vKommentare`b</td><td align='center'>`b`9Schreiben`b</td><td align='center'>`b`q Bereinigen`b</td><td align='center'>`b`\$ Leeren`b</td></tr>",true) ;
                       
      for ($i=0;$i<=$ortszahl;$i++){
          $bgcolor=($i%2==1?"trlight":"trdark");
          $current_place = $sorte[$i] ;
          
          $sql6 = "SELECT alias FROM room_aliases WHERE room='$current_place'" ;
          $result6 = db_query($sql6) or die(db_error(LINK));
          $alias = db_fetch_assoc($result6);          
          
              if ($alias['alias'] != "") { 
                      $current_alias = $alias['alias'] ;
                  
                  
                  // Anfragen / Petitionen
                  }elseif (substr($current_place,0,4) == "pet-") {
                                 $petnr = (int)substr($current_place,4,5) ;                                
                      $current_alias = "Kommentare zu `@ `nUser-Anfrage Nr.".$petnr."`8" ;
                      
                  
                  // Haus-Flur
                  }elseif (substr($current_place,0,12) == "house-gemein") {
                                 $housenr = (int)substr($current_place,12,4) ;                                                                  
                                 $current_housename = get_house_name($housenr) ;                                 
                      $current_alias = "Flur von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                             
                             
                             // Haus-Küche
                             }elseif (substr($current_place,0,13) == "house-kitchen") {
                                 $housenr = (int)substr($current_place,13,4) ;                                 
                                 $current_housename = get_house_name($housenr);                                 
                      $current_alias = "Küche von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                             
                             
                             // Haus-Privatraum
                             }elseif (substr($current_place,0,13) == "house-private") {
                                 $housenr = (int)substr($current_place,13,4) ;                                 
                                 $current_housename = get_house_name($housenr);                                 
                      $current_alias = "Privatraum von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                  
                  
                  // Haus-Ställe
                             }elseif (substr($current_place,0,13) == "house-stables") {
                                 $housenr = (int)substr($current_place,13,4) ;                                 
                                 $current_housename = get_house_name($housenr);                                 
                      $current_alias = "Ställe von Haus ".$housenr."`n(`9".$current_housename."`8)" ;    
                      
                  
                  // Haus-Veranda
                             }elseif (substr($current_place,0,13) == "house-veranda") {
                                 $housenr = (int)substr($current_place,13,4) ;                                 
                                 $current_housename = get_house_name($housenr) ;                                 
                      $current_alias = "Veranda von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                  
                  // Haus-Whirlpool
                             }elseif (substr($current_place,0,11) == "house-whirl") {
                                 $housenr = (int)substr($current_place,11,4) ;                                 
                                 $current_housename = get_house_name($housenr) ;                                 
                      $current_alias = "Whirlpool von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                      
                  
                  // Haus-Schatzkammer
                             }elseif (substr($current_place,0,12) == "house-schatz") {
                                 $housenr = (int)substr($current_place,12,4) ;                                 
                                 $current_housename = get_house_name($housenr) ;                                 
                      $current_alias = "Schatzkammer von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                                 
                             
                             
                             // Haus-OOC
                  }elseif (substr($current_place,0,9) == "house-ooc") {
                                 $housenr = (int)substr($current_place,9,4) ;                                 
                                 $current_housename = get_house_name($housenr) ;                                 
                      $current_alias = "OOC-Raum von Haus ".$housenr."`n(`9".$current_housename."`8)" ;
                      
                  
                  // Gilden-Versammlungsraum
                  }elseif (substr($current_place,0,10) == "gildenhaus") {
                                 $guildnr = (int)substr($current_place,10,4) ;                                 
                                 $current_guildname = get_guild_name($guildnr) ;
                      if($current_guildname!="") $current_alias = "Raum der Gilde`n\"".$current_guildname."`8\"" ;
                      else $current_alias = "`c`\$`iRaum einer aufgelösten oder unbekannten Gilde`i`0`c" ; 
                      
                  
                   // Trampfelpfad-Privaträume
                  }elseif (substr($current_place,0,9) == "clearing_") {
                         $reststring = substr($current_place,9,10) ;                      
                      $acctids = "" ;
                      $acctids = explode('_', $reststring); 
                                         
                                                        
                                      for ($t=0;$t<2;$t++){
                                         $current_acctid = $acctids[$t] ;
                                            if($current_acctid !="" || $current_acctid !="0") {
                                         $sql7 = "SELECT name FROM accounts WHERE acctid='$current_acctid'" ;
                                         $result7 = db_query($sql7) or die(db_error(LINK));
                              $names7 = db_fetch_assoc($result7); 
                              $current_uname['name'][$t] = $names7['name'] ;
                                   }
                          }
                      
                      $uname1 = $current_uname['name'][0] ;    
                      $uname2 = $current_uname['name'][1] ;    
                      
                      reset($current_uname) ;
                      
                      if($uname1 == "") $uname1 = "`\$`i>unbekannt<`i`8" ; 
                      if($uname2 == "") $uname2 = "`\$`i>unbekannt<`i`8" ; 
                      
                      $current_alias = "Trampelpfad-Privatraum`n (`&".$uname1." `@und`& ".$uname2."`8)" ;
                  
                      
                         
                          }else {
                              $current_alias = "`c`\$`i>unspezifiziert<`i`0`c" ; 
                          }
                         
                         
              
          
           $sql4 = "SELECT count(comment) AS c FROM commentary WHERE section='$current_place'";
           $result = db_query($sql4) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $comcount =  $row['c'] ;
            
          
          if($_GET['showlast']=="yes") {
              
             
               if($seluser =="") $sql5 = "SELECT author,comment,postdate  FROM commentary WHERE section='$current_place' ORDER by postdate DESC LIMIT 1";
               else $sql5 = "SELECT author,comment,postdate  FROM commentary WHERE section='$current_place' AND author='$seluser' ORDER by postdate DESC LIMIT 5";
               $result5 = db_query($sql5) or die(db_error(LINK));
              
              
              $now_day = date("d") ;
              $now_date = date("Y-m")."-".$now_day ;    
              $yes_day =   $now_day -1 ;    
              $two_days_before_day =   $now_day -2 ;    
              $yesterday_date = date("Y-m")."-".$yes_day;
              $two_days_before_date = date("Y-m")."-".$two_days_before_day;
              
              $comnum = 0 ;
              for ($w=0;$w<=db_num_rows($result5)-1;$w++){
                   $row5 = db_fetch_assoc($result5);
                   $comnum++ ;
                   $lastcomment[$w] =  $row5['comment'] ;
                   $this_author[$w] = $row5['author'] ;
                   $this_poststring[$w] = $row5['postdate'] ;
                   $this_tempdate[$w] = substr($this_poststring[$w],0,10) ; 
                   $this_temptime[$w] = substr($this_poststring[$w],10,6) ; 
                   
                   if($this_tempdate[$w] == $now_date) {
                       $this_postdate[$w] = "`@Heute um ".$this_temptime[$w]."`0" ;
                   
                   }elseif($this_tempdate[$w] == $yesterday_date) { 
                       $this_postdate[$w] = "`^Gestern um ".$this_temptime[$w]."`0" ;
                       
                   }elseif($this_tempdate[$w] == $two_days_before_date) { 
                       $this_postdate[$w] = "`qVorgestern um ".$this_temptime[$w]."`0" ;    
                       
                   }else $this_postdate[$w] = date("d.m.Y u\m G:i",strtotime($this_poststring[$w])) ;
                   
                   
                   
                    $current_author = $this_author[$w] ;
                              
                    $sql6 = "SELECT name FROM accounts WHERE acctid='$current_author'";
                    $result6 = db_query($sql6) or die(db_error(LINK));
                    $row6 = db_fetch_assoc($result6);
                    $this_author_name[$w] = $row6['name'] ;
                   
                   
               }
          
               if($seluser == "") {
                   $lastcomment[0] = str_replace(":: ","`&",$lastcomment[0]) ;    
                   $lastcomment[0] = str_replace("/me ","`&",$lastcomment[0]) ;    
                   $lastcomment[0] = str_replace(":","`&",$lastcomment[0]) ;    
                   $lastcomment[0] =  "`&".$this_author_name[0]."`# ".$lastcomment[0]."" ;
              }
                    
          }
          output("<tr class='$bgcolor'><td align='left'>`^ $current_place</td><td align='left'>`8 $current_alias`0</td><td align='center'>`b`v $comcount`b</td><td align='center'><a href='viewplaces.php?which=$current_place&schreiben=1'>`9Ort betreten</a></td><td align='center'><a href='viewplaces.php?which=$current_place&schreiben=0'>`q Bereinigen</a></td><td align='center'><a href='placelist.php?op=flushplace&which=$current_place'>`\$Leeren</a></td></tr>",true) ;
          
          if($_GET['showlast']=="yes") {
               if($seluser == "") {
                   output("<tr class='$bgcolor'><td>Letzter Kommentar:</td><td align='left'>{$lastcomment[0]} </td><td>{$this_postdate[0]}</td></tr><tr><td></td></tr>",true) ;
                   
               }else {
                   for ($u=$comnum-1;$u>=0;$u--){
                       $u2 = $u+1 ;
                       $thiscomment = "Kommentar ".$u2 ;
                       if($u==0) $thiscomment = "Letzter Kommentar" ;
                       $lastcomment[$u] = str_replace(":: ","`&",$lastcomment[$u]) ;    
                       $lastcomment[$u] = str_replace("/me ","`&",$lastcomment[$u]) ;    
                       $lastcomment[$u] = str_replace(":","`&",$lastcomment[$u]) ;
                       $lastcomment[$u] = str_replace("$this_author_name[$u]","",$lastcomment[$u]) ;        
                       $lastcomment[$u] =  "`&".$this_author_name[$u]."`# ".$lastcomment[$u]."" ;
                       if($this_postdate[$u]!="") output("<tr class='$bgcolor'><td>$thiscomment:</td><td align='left'>{$lastcomment[$u]} </td><td>{$this_postdate[$u]}</td></tr>",true) ;    
                   }
                   output("<tr></tr><td></td>",true) ;
               }
               
          }
          
          addnav("","viewplaces.php?which=$current_place&schreiben=0") ;
          addnav("","viewplaces.php?which=$current_place&schreiben=1") ;
          addnav("","placelist.php?op=flushplace&which=$current_place") ;
          
      }        
      
      output("</table></center>",true) ;
            
                 output("`n`n`n`n") ;
                 output("`0<form action=\"placelist.php?op=viewcom&placeisset=1&filter=$filter2\"method='POST'>",true);
                output("`nAnderen Platz anzeigen?: <input name='cplace' maxlength='25'>",true);
                 output("<input type='checkbox' name='schr' value='JANEIN'> Bereinigen`n`n`n ",true);
                 output("<input type='submit' class='button' value='Kommentare anzeigen'></form>",true);
          
                addnav("","placelist.php?op=viewcom&placeisset=1&filter=$filter2");
             
           
            
  }else{
     $plac = $_POST[cplace] ;

      if($plac =="") {
    output("`@Diesen Ort `$ gibt es nicht!`0") ;
      }


} 


// Alle Kommentare eines Ortes löschen 
}elseif ($_GET[op]=="flushplace") {
    $place_to_flush = $_GET['which'] ;

    output("`@Möchtest du in `v $place_to_flush `@ wirklich `b `\$ alle Kommentare löschen?`b`0") ;
    addnav("Ja","placelist.php?op=flushconfirm&which=$place_to_flush");
    addnav("Nein","placelist.php?op=viewcom?&filter=$filter2") ;
    

// Ortslöschung bestätigt
}elseif ($_GET[op]=="flushconfirm") {
    $place_to_flush = $_GET['which'] ;
    
    if($place_to_flush!="") {
        $sql8 = "DELETE FROM commentary WHERE section='$place_to_flush'" ;
        $result8 = db_query($sql8) or die(db_error(LINK));
        output("`@Alle Kommentare in `v $place_to_flush `@ wurden `\$  gelöscht!`0`b") ;
    }
    
    
    
// Zm Ort wechseln (schreiben oder einzelne Kommentare löschen)
}elseif ($_GET[placeisset]!="") {

   $plac = $_POST[section] ;    
   if($plac == "") $plac = $_POST[cplace] ;

     if(!$_POST[schr]) { 
    $schreiben = 1 ;
    addnav("","viewplaces.php?which=$plac&schreiben=1") ;
    redirect("viewplaces.php?which=$plac&schreiben=1") ;
      }
      else {    
    $schreiben = 0 ;
    addnav("","viewplaces.php?which=$plac&schreiben=0") ;
    redirect("viewplaces.php?which=$plac&schreiben=0") ;
      }

       $_POST[cplace] =  $plac ;  


    addnav("Neuen Ort auswählen","placelist.php?op=viewcom&filter=$filter2");
    addnav("------") ;
    output("`n`n`n`n") ;
    output("`0<form action=\"placelist.php?op=viewcom&placeisset=1&filter=$filter2\"method='POST'>",true);
                output("`nNeuen Platz anzeigen?: <input name='cplace' maxlength='25' value=$plac>",true);
                if(!$_POST[schr]) output("<input type='checkbox' name='schr' value='JANEIN' > löschen`n ",true);
                else output("<input type='checkbox' name='schr' value='JANEIN' checked> löschen`n ",true);
                output("<input type='submit' class='button' value='Kommentare anzeigen'></form>",true);
              
                addnav("","placelist.php?op=viewcom&placeisset=1&filter=$filter2");
    
    }




     if ($_GET[op]!="flushplace") {
    addnav("W?Zurück zum Weltlichen","village.php");
    addnav("-----") ;
    
    if($thisuser =="" && $seluser== "") {
         if($_GET['showlast']!="yes") addnav("Aktualisieren","placelist.php?op=viewcom&filter=$filter2") ;
         else  addnav("Aktualisieren","placelist.php?op=viewcom&showlast=yes&filter=$filter2") ;
     }
    else addnav("Zurück","placelist.php?op=viewcom") ;
    addnav("-----") ;
    addnav("G?Zurück zur Grotte","superuser.php");
      }
      

page_footer();
output("`n`n`n`0") ;
?>