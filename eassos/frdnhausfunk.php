
<?php


/* 
   Funktionen, SQL usw. für das Freudenhaus.
   Coded by Taikun // www.logd-midgar.de
   
   
   SQL:
   
   -
   - Freudenhaus:
   -
   
   CREATE TABLE `frdn` (
   `acctid` int(11) unsigned NOT NULL default '0',
   `id` int(10) unsigned NOT NULL auto_increment,
   `kosten` int(11) unsigned NOT NULL default '0',
   `user` varchar(200) NOT NULL default '0',
   `sex` tinyint(4) unsigned NOT NULL default '0',
   `raum` varchar(200) NOT NULL default '0',
    PRIMARY KEY  (`id`),
    UNIQUE KEY `name` (`name`)
    ) TYPE=MyISAM AUTO_INCREMENT=1 ;
    
   - 
   - Altersverifizierung:
   -
   
   CREATE TABLE `prufen` (
   `acctid` int(11) unsigned NOT NULL default '0',
   `id` int(11) unsigned NOT NULL auto_increment,
   `nummer1` varchar(100) NOT NULL,
   `nummer2` varchar(100) NOT NULL,
   `nummer3` varchar(100) NOT NULL,
   `nummer4` varchar(100) NOT NULL,
   `erg` varchar(100) NOT NULL,
   `name` varchar(255) NOT NULL,
    PRIMARY KEY  (`id`)
    ) TYPE=MyISAM AUTO_INCREMENT=1 ;
    
    -
    - Accounts
    -
    
    ALTER TABLE accounts
    ADD `room` enum('0','1') NOT NULL default '0',
    ADD `frdnerl` enum('0','1') NOT NULL default '0',
    ADD `roomid` INT ( 11 ) NOT NULL default '0',
    ADD `kondom` INT ( 4 ) default '0';
    
    
    How to install:
    
    Alle Dateien in das Root Verzeichnis laden. Bitte "$stadt" an eure Stadt bzw. Euer Dorf anpassen.
    Zudem müsst ihr folgende Sachen einfügen:
    
    Öffne newday.php:
    
    Suche z.B. 
    $session['user']['seenmaster'] = 0; 
    und füge darunter 
    $session['user']['kondom'] = 0;
    ein.
    
    Öffne dragon.php
    
    Suche z.B. ( 2- MAL )
    ,"name"=>1
    und füger darunter
    ,"frdnerl"=>1
    ein.
    
    Fixes / Adds:
    
    Version 0.1: Das Freudenhaus läuft.
    Version 0.2: Admins können ab nun Räume löschen.
    Version 0.3: Altersverifizierung eingebaut.
    Version 0.4: Kondomladen und Spielzeugladen hinzugefügt.
    Version 0.5: Code optimiert und "aufgeräumt".
    
*/
    

    function settings( $stadt, $verw )
    { // Anfang Funktion
         global $session;
         
        
         output("`@Willkommen im Freundenhaus von ".$stadt.", ".($row['sex']?"werte":"werter")." ".$session['user']['name'].".
                 `@Wollt Ihr Euch selber anbieten oder Euch lieber verwöhnen lassen? Wenn Ihr Euch verwöhnen lassen wollt, müsst Ihr beachten, dass die Preise variieren können.`n
                   Wenn Ihr Euch selber anbieten wollt, kostet dies eine geringe Bearbeitungsgebühr von `^".$verw." Gold.`n`n`n");
            place(1);
         viewcommentary("frdn","Mit den Anderen reden:",25);

         output("`n`n`YEine leicht bekleidete Frau lässt dich wissen, dass dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

         addnav("Ein Zimmer wählen","frdnhaus.php?op=ver");

         if($session['user']['gold']>=$verw) 
         { // Anfang IF

         addnav("Dich Anbieten","frdnhaus.php?op=anbiet");
         
         } // Ende IF
         addnav("Kondomladen","kondom.php");
         addnav("Zurück zum Hafen","hafen.php");  
         
} // Ende Funktion 
      


    function settingsanfang()
    { // Anfang Funktion
    
         output("`@Bitte beachtet, dass wenn Ihr in das Zimmer geht, erst wieder rausgehen könnt, wenn Ihr fertig seid. Also wartet ab, bis sich jemand gefunden hat, der sich zu Euch gesellt.`n`n`n");
place(1);
         viewcommentary("frdn","Mit den Anderen reden",25);
        
         output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");
         if ($session['user']['superuser']>=1)
         {
         addnav("Ein Zimmer wählen","frdnhaus.php?op=ver");
         }
         addnav("Ins Zimmer","frdnhaus.php?op=room&id=".$_GET['id']."");
         addnav("Zurück zum Hafen","hafen.php");
         addnav("Kondomladen","kondom.php");
    
    
    
} // Ende Funktion
 

  
    function inputalter()
    { // Anfang Funktion
     
         output("Wir müssen sicher gehen, dass nur Leute, die mindestens 18 oder älter als 18 sind, das Freudenhaus betreten. Alle, dadurch entstandenen Probleme, kommen nur der Person zur Last, die unter 18 Jahre alt war. Der Serveradmin übernimmt keinerlei Haftung dafür, falls die entsprechende Person unter 18 war und eine falsche Ausweisnummer angegeben hat.");


         output("<form action=\"frdnhaus.php?op=pruf\" method='post'>Bitte gib deine Personalausweisnummer ein:<br><br>",true);

         output("<table>",true);

         output("<td valign=top><input name='eins' size='11' maxlength='11'></td>",true);
         output("<td> << </td>",true);
         output("<td valign=top><input name='zwei' size='7'  maxlength='7'></td>",true);
         output("<td> < </td>",true);
         output("<td valign=top><input name='drei' size='7'  maxlength='7'></td>",true);
         output("<td> <<<<<<< </td>",true);
         output("<td valign=top><input name='vier' size='1'  maxlength='1'></td>",true);

         output("</table><input type='submit' value='Abschicken'></form>",true);

         
         addnav("","frdnhaus.php?op=pruf");
         addnav("Zurück zum Hafen","hafen.php");

} // Ende Funktion
    

    function alterprufen()
    { // Anfang Funktion
        global $session;
       // Die geposteten Zahlen ins eine Variable übergeben.
        $eins = $_POST['eins'];
        $zwei = $_POST['zwei'];
        $drei = $_POST['drei'];
        $vier = $_POST['vier'];
       // Ende Variable übergeben.
         
       
       if ((strlen($eins)==11) && (strlen($zwei)==7) && (strlen($drei)==7) && (strlen($vier)==1)) // Die Zahlen auf ihr Größe überprüfen, wenn sie richtig sind, ab in die Datenbank.
       
       { // Anfang IF
       
       // Zahlen usw. eintragen
         $sql10 = "INSERT INTO prufen (acctid,nummer1,nummer2,nummer3,nummer4,name) VALUES ('".$var1."','".$eins."','".$zwei."','".$drei."','".$vier."','".$var."')";
         $result10 = db_query($sql10) or die(db_error(LINK));
       // Fertig eingetragen
         
         output("Alle Felder korrekt ausgefüllt. Deine Eingabe wird nun weiter ausgewertet.");

       // Nun holen wir die Zahlen wieder aus der Datenbank.
         $sql = "SELECT id FROM prufen WHERE acctid='$session[user][acctid]'"; 
         $result = db_query($sql);
         $row = db_fetch_assoc($result); 
         $id = db_insert_id(LINK); // Hier wird die letzte eingetragene ID, also der Primärschlüssel, aus der Datenbank geholt.


         output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Weiter</td>",true);
         $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
         output("<tr class='".$bgclass."'><td><a href='frdnhaus.php?op=weiterpruf&id=".$id."' onClick='return confirm(\"Weiter?\");'>Weiter</a></td></tr>",true);


         addnav("","frdnhaus.php?op=weiterpruf&id=".$id);
   
         output("</table>",true);
         
      } // Ende IF



      else if ((strlen($eins)<=11) && (strlen($zwei)<=7) && (strlen($drei)<=7) && (strlen($vier)<=1)) // Sind die eingegeben Zahlen kleiner als die Mindestgröße, dann wird der User gebeten, nochmal von vorne einzutippen.
        
      { // Anfang ELSE IF

         output("Bitte die Felder richtig ausfüllen.");

         addnav("Zurück","frdnhaus.php"); 

        

      } // ENDE ELSE IF
    
    
    
}// Ende Funktion

     function alterweiterprufen()
     { // Anfang Funktion
         global $session;
         $sqll = "SELECT * FROM prufen WHERE id='$_GET[id]'"; 
         $resultl = db_query($sqll);
         $rowl = db_fetch_assoc($resultl); 

        // Zuerst werden die Ausweisnummern wieder in Variablen gesteckt.
         $num1 = $rowl['nummer1'];
         $num2 = $rowl['nummer2'];
         $num3 = $rowl['nummer3'];
         $num4 = $rowl['nummer4'];

       // Hier werden die Nummern in ein Array gesteckt und gesplittet, das heißt sie werden in ihrer Größe zerteilt.
         $array1 = str_split($num1);
         $array2 = str_split($num2);
         $array3 = str_split($num3);

       // Nummero 1 :)
         $multiplikator1 = Array('7','3','1'); $mi1 = 0;
         for($i = 0; $i < 10; $i++) 
         { // Anfang for
         $ergl = $multiplikator1[$mi1] * ((int)$array1[$i]);
         $mi1++;
         if($mi1 == 3 )         
         { // Anfang IF          
         $mi1 = 0;          
          } // End IF         
         } // End for
         
       // Nummero 2 :)  
         $multiplikator2 = Array('3','1','7'); $mi2 = 0;
         for($i = 0; $i < 7; $i++) 
         { // Anfang for
         $erggl += $multiplikator2[$mi2] * ((int)$array2[$i]);
         $mi2++;
         if($mi2 == 3 ) 
         { // Anfang IF
         $mi2 = 0; 
          } // End IF
         } // End for

       // Nummero 3 :)  
         $multiplikator3 = Array('1','7','3'); $mi3 = 0;
         for($i = 0; $i < 7; $i++) 
         { // Anfang for
         $ergggl += $multiplikator3[$mi3] * ((int)$array3[$i]);
         $mi3++;
         if($mi3 == 3 ) 
         { // Anfang IF
         $mi3 = 0; 
          } // End IF
         } // End for

       // Alles zusammenrechnen...
         $ergges = $ergggl + $erggl + $ergl;

       // ...und in die Datenbank eintragen. 
         $sqlerg = "UPDATE prufen SET erg = erg+$ergges WHERE id='$_GET[id]'";
         $resulterg = db_query($sqlerg) or die(db_error(LINK));

         
         $sql5 = "SELECT erg FROM prufen WHERE id='$_GET[id]'"; 
         $result5 = db_query($sql5);
         $row5 = db_fetch_assoc($result5); 
         $num5 = $row5['erg'];
 
       // Den vierten Array mit dem Ergebnis splitten.
         $array4 = str_split($num5);
       // Die Arrays in Variablen speichern, um so das Geburtsdatum zu errechnen.  
         $a = $array4[0];$b = $array4[1];$c = $array4[2];$d = $array2[0];$e = $array2[1];
         $f = $array2[2];$g = $array2[3];$h = $array2[4];$i = $array2[5];

       // Alter ausrechnen
         $tag = "$h$i";$monat = "$f$g";$jahr = "19$d$e";
         $jetzt = mktime(0,0,0,date("m"),date("d"),date("Y"));
         $geburt = mktime(0,0,0,$monat,$tag,$jahr);
         $age   = intval(($jetzt - $geburt) / (3600 * 24 * 365));

         output("".$ergges."");
         
         if ($c == $num4 && $age > 17)
         { // Anfang IF
         output("Die Prüfsumme deines Personalausweises stimmt. Du bist zusätzlich ".$age." Jahre alt und darfst nun das Freudenhaus betreten.");
         addnav("Weiter","frdnhaus.php");
         $session['user']['frdnerl']=1;

         } // Ende IF 
         else
         { // Anfang ELSE
         output("`c`4FEHLER`c`n");
         output("Du darfst nicht ins Freudenhaus, da die Prüfsumme deines Personalausweises nicht stimmt oder weil du EVENTUELL noch zu jung bist.`n ");
         if ($age<18)
         { // Anfang IF
        output("Du bist erst ".$age." Jahre alt.");    
         } // Ende IF
        // Ist das Alter, bzw. die Prüfsumme falsch, wird alles wieder gelöscht. 
         $sql7 = "DELETE FROM prufen WHERE id='$_GET[id]'";
         $result7 = db_query($sql7);
         addnav("Zurück","frdnhaus.php");     
         } // Ende ELSE   
} // Ende Funktion    




      function roominside()
      { // Anfang Funktion
        global $session;
        output("`@Das schöne Zimmer dieses Freudenhaus schmücken schöne Bilder an einer Wand, auf der rechten Seite steht ein schönes grosses Bett, indem Ihr Euch austoben könnt...`n`n");

        viewcommentary("frdn-".$session['user']['roomid'],"Hinzufügen",25);
        output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");


        addnav("RP Beenden","frdnhaus.php?op=beend");
        addnav("Zurück ins Dorf","village.php");
} // Ende Funktion


     function rpbeenden()
     { // Anfang Funktion
       global $session;
       output("Du kannst nun wieder in ein neues Zimmer bzw. Dich wieder selber anbieten.");

       addnav("Zurück zum Eingang","frdnhaus.php");

       $session['user']['room']=0;
       $session['user']['roomid']=0;
} // Ende Funktion


     function anbieten()
     { // Anfang Funktion
     
       addnav("Zurück zum Freudenhaus","frdnhaus.php");

       output("<form action=\"frdnhaus.php?op=send\" method='post'>Bitte gebe an, für wieviel Gold Du Dich verkaufen möchtest und das Zimmer heissen soll.:<br><br>",true);
       output("<table><tr><td>Kosten:</td><td>Zimmername:</td></tr>",true);
       output("<td valign=top><input name='kosten' size='15'></td>",true);
       output("<td valign=top><input name='raum' size='30'></td>",true);
       output("</table><input type='submit' value='Abschicken'></form>",true);

       addnav("","frdnhaus.php?op=send");

       output("`n");
     
} // Ende Funktion


     function anbietenprufen()
     { // Anfang Funktion
       global $session;
       if ($_POST["kosten"]<="20000" && $_POST['kosten']!="" && $_POST['raum']!="")
       { // Anfang IF

       $sql = "INSERT INTO frdn (acctid,user,kosten,sex,raum) VALUES ('".$session['user']['acctid']."','".$session['user']['name']."','".$_POST['kosten']."','".$session['user']['sex']."','".$_POST['raum']."')";
       $result = db_query($sql) or die(db_error(LINK));
       $id = db_insert_id(LINK);

       $session['user']['gold']-=$verw;
       $session['user']['room']=1;
       $session['user']['roomid'] = $id;

       output("Erfolgreich eingetragen. Ihr müsst jetzt nurnoch warten, bis sich jemand meldet.");

       addnav("Zurück zum Hafen","hafen.php");

       } // Ende IF

       else
        
       if ($_POST["raum"]=="" || $_POST["kosten"]=="")
       { // Anfang ELSE IF

       output("Bitte alle Felder ausfüllen.");

       addnav("Zurück","frdnhaus.php?op=anbiet"); 

       } // Ende ELSE IF

       else 
       
       if($_POST['kosten']>"20000")
       { // Anfang ELSE IF

       output("Bitte einen Preis unter 20000 Gold wählen.");

       addnav("Zurück","frdnhaus.php?op=anbiet");
       addnav("Zurück zum Hafen","hafen.php");

       } // Ende ELSE IF  
} // Ende Funktion


     function getpartner()
     { // Anfang Funktion
       global $session;
       output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Zimmername</td><td>Name</td><td>Kosten</td><td>Nummer</td>",true);
       if ($session['user']['superuser']>0)
       { // Anfang IF
       output("<td>Aktion</td></tr>",true);
       } // Ende IF

       $sql = "SELECT id,user,raum,kosten,sex FROM frdn";
       $row = db_fetch_assoc($result);
       $result = db_query($sql) or die(db_error(LINK));

       
       if (db_num_rows($result)==0)
       { // Anfang IF


       output("<tr class='trdark'><td colspan=5 align='center'>`&`iDerzeit bietet sich niemand an!`i`0</td></tr>",true);                
       output("`n`n`n`n ".$copyright." ".$website." by ".$author." ");
       
       addnav("Zurück","frdnhaus.php");

       } // Ende IF
       else 
       { // Anfang ELSE

       addnav("Doch lieber nicht","frdnhaus.php");

       while ($row = db_fetch_assoc($result)) 
       { // Anfang while

        
       $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

       output("<tr class='".$bgclass."'><td><a href='frdnhaus.php?op=getroom&id=".$row['id']."' onClick='return confirm(\"Willst du wirklich in dieses Zimmer?\");'>".$row['raum']."</a></td><td>".$row['user'],true);
       output("</td><td>".$row['kosten']."</td><td>".$row['id']."</td>",true);
       if ($session['user']['superuser']>0)
       { // Anfang IF
       output("<td><a href='frdnhaus.php?op=zimmdel&id=".$row['id']."'>Löschen</a></td>",true);
       } // Ende IF

       addnav("","frdnhaus.php?op=getroom&id=".$row['id']);
       addnav("","frdnhaus.php?op=zimmdel&id=".$row['id']);
       } // Ende while
       } // Ende Else

       output("</table>",true);
       output('</form>',true);
} // Ende Funktion


     function getroom()
     { // Anfang Funktion
       global $session;
       $sql2 = "SELECT * FROM frdn WHERE id='$_GET[id]'"; 
       $result2 = db_query($sql2);
       $row = db_fetch_assoc($result2); 

       $gold = $row['kosten'];
       $id = $row['id'];
       $acctid = $row['acctid'];


       if($session[user][gold]>=$row[kosten])
       { // Anfang IF

       output("Du kannst nun in das Zimmer.");

       addnav("Zurück","frdnhaus.php");

       $session['user']['roomid'] = $row['id'];  
       $session['user']['gold'] -= $gold;
       $session['user']['room']=1;

       $sql = "UPDATE accounts SET goldinbank = goldinbank+$row[kosten] WHERE acctid='{$row['acctid']}'";
       db_query($sql) or die(db_error(LINK)); 

       $sql9 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('`System`0','$row[acctid]','`^Freudenhaus!`0','`&{$session['user']['name']}`6 hat sich für ein Zimmer bei Dir beworben und Dir dafür den Preis in Höhe von ".$row['kosten']." Gold auf deine Bank überwiesen!',now())";
       db_query($sql9); 

       $sql2="DELETE FROM frdn WHERE id='$_GET[id]'";
       $result2=db_query($sql2); 

       } // Ende IF

       else 
       if($session[user][gold]<$row[kosten])
       { // Anfang ELSE IF

       output("Das kannst Du Dir garnicht leisten!");

       addnav("Zurück","frdnhaus.php");

       } // Ende ELSE IF
} // Ende Funktion

      function delzimmer1()
      { // Anfang Funktion
          output("Willst du das Zimmer wirklich löschen?");
          
          addnav("Ja","frdnhaus.php?op=zimmdel1&id=".$_GET['id']."");
          addnav("Nein","frdnhaus.php");
          
} // Ende Funktion


      function delzimmer2()
      { // Anfang Funktion
        $sql3 = "DELETE FROM frdn WHERE id='$_GET[id]'";
        $result3 = db_query($sql3); 

        output("Du hast das Zimmer ".$_GET['id']." erfolgreich gelöscht.");

        addnav("Zurück","frdnhaus.php");
        
} // Ende Funktion          
 
?>

