<?php
/*
†  Stadttor + einlass verlangen ( siehe: bewerbrp.php )
†  By Whatever ( whatever_und_so@gmx.de )

†  SQL: 
    ALTER TABLE accounts ADD einlass TINYINT( 4 ) NOT NULL DEFAULT '0';
          --------------------------------
†  Suche in user.php und wenn vorhanden modedit.php :
         "banoverride"=>"Verbannungen übergehen,bool",
         
†  Füge danach ein:
         "einlass"=>"Zulassen,bool",    
          --------------------------------
†  Suche in village.php
                       page_footer

†  Füge davor ein:    
         if($session['user']['einlass']==0){
                       redirect("dorftor.php");
                      }        
          --------------------------------
†  Suche in dragon.php ( 2x)  :
         ,"charm"=>1
         
†  Füge danach ein:
         ,"einlass"=>1    
          --------------------------------
†  Suche in user.php und wenn vorhanden modedit.php :
                 "rpchar"=>"Nur RP Charakter,enum,0,Nein,1,Ja",
                 
†  Füge danach ein:
                 "bewerbr"=>"RPchar-Bewerbung?,enum,0,Nein,1,Ja",    
         --------------------------------
†  Addnav  -> addnav("Stadttor","tor.php");  <- überall dort hin verlinken wo man halt möchte und so..             
          --------------------------------
    Save, close & upload        
          --------------------------------
†  Rechtschreibfehler darf man behalten :P oder verbessern (;        
†  Bildname muss angepasst werden wenn Bild erwünscht und so.. natürlich auch Stadt/Dorfname rein..
*/
require_once "common.php";
if ($session['user']['alive']){ }else{
        redirect("shades.php");
        }
/*Schreiben sollen sie ja auch können..*/
addcommentary();
/*Neuen Tag soll's auch geben...*/
checkday();
/*Kämpferlisten Eintrag und so..(wenn vorhanden dann dekommentieren) */
//$session['user']['wo']= "Vor dem Stadttor";
/*Farb-Definitionen*/
$c1 = '`%';
$c2 = '`3';
$c3 = '`$';
$c4 = '`l';
$g1 = '`%`iGrotte`i';
/*Bild..*/
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
/*Wenn schon eingelassen wurde.. */
if ($session['user']['einlass']==1){
        addnav("Arda");
        addnav("In die Stadt","village.php");
        addnav("Umland");//Narjana
        addnav("zur Wegkreuzung","kreuzung.php");
        addnav("Kornkammer Ardas","kreuzung.php?op=korn");
        addnav("Friedhof Ardas","friedhof.php");
    }
    
/*Immer zu sehen...*/
            addnav("Vor dem Tor");
    //        addnav("Der verlassene Hof","der_verlassene_hof.php");
            addnav("In die Felder (Logout)","login.php?op=logout",true);
            //#addnav("Einlass/Rpchar","bewerbrp.php");
            /*if ($session['user']['rlalter']==0){
  addnav("`MAlter verifizieren","perso.php");
}*/
            addnav("Sonstiges");
            addnav("Kämpferliste","list.php");
            addnav("Profil & Inventar","prefs.php");
            //addnav("`MFarblegende", "chat.php",false,true);
            addnav("??F.A.Q. ".$c4."(für neue Spieler)", "petition.php?op=faq",false,true);
            //addnav("`bOOC`b");
            //addnav("`MOOC","ooc.php");
/*Admins ham's halt gut.. */
if ($session['user']['superuser']>=3){
                addnav("ADMIN");
                addnav("".$g1."","superuser.php");
        }
/*Überschrift..*/
page_header("Vor dem Stadttor");
//$session[user][ort] = "Stadttor";

        output("`n`c`b `sStadttor`b`c`n`n",true);
/*Bild so da so..ändern in Bildname das man benutzen möchte und in den Ordner Images geladen hat */       
        bild("dorftor.jpg");
/*Text wenn schon Einlass und so..*/            
if ($session['user']['einlass']==1){
        output("`S`c `wLa`4ng`Wsa`em s`\$ch`Eälen sich aus dem dichten `7N`Ge`Rb`Ge`7l`E die Konturen der Stadt heraus, die dir nur zu bekannt zu sein scheint. Du lässt dich von den `gI`pr`jr`ml`yi`Vc`rh`Zt`te`^r`qn `Enicht länger auf die falschen Wege leiten und gehst daher zielstrebig auf die Tore zu. Sind sie grade aus Stahl, Bronze oder doch aus Eichenholz? Du siehst nur, das es Dunkel ist, während die Wachen davor wie helle Fackeln zu strahlen scheinen. Sie sehen dich an und nicken kurz, als sie dich als Bewohner der Stadt erkennen und lassen dich gern durch das Tor schreiten. Mit einem Schritt kannst du dich also in die Sicherheit Ardas begeben und den feuchtkalten `7N`Ge`Rb`Ge`7l`E hinter dir lassen, oder aber dich weiter vor den Toren aufhalten und das `gF`pa`jr`mb`ys`Vp`ri`Ze`tl `Egenießen. Achte nur darauf, das du dich nicht in unruhigen Nächten hier aufhältst, man flüstert, das sich die gefährlichen Waldbewohner in Solchen sogar bis kurz vor di`\$e T`eor`We w`4ag`wen.`c`n`n ");
        }else{        
/*Beschreibungstext wenn noch vor dem Tor ohne Einlass...*/
        output("`wDu `4ha`Wst `ees `\$du`Erch den dichten, grau und schwarz schillernden `7N`Ge`Rb`Ge`7l`E geschafft, in dem immer wieder `gI`pr`jr`ml`yi`Vc`rh`Zt`te`^r `Ein den unterschiedlichsten Pastelltönen die Passanten auf den falschen Weg führen. Angekommen an den großen Stadttoren die mal aus Eiche, mal aus Eisen zu sein scheinen, erwarten dich die Wachen der Stadt, die dich von der Einkehr in die ortsansässige Schenke abhalten. Die Männer, welche in ihren Rüstungen wirken wie Schränke, verlangen deine Personalien und einige Dinge über deine Vergangenheit zu wissen, um dich besser einschätzen zu können. Sie wollen schließlich nicht, das du die Stadt mitten im Chaos in Schutt und Asche legst. Wenn du dich nicht zurück in den `7N`Ge`Rb`Ge`7l`E wagen möchtest, um dich doch noch von den `gI`pr`jr`ml`yi`Vc`rh`Zt`te`^r`qn `Everzaubern zu lassen, solltest du wohl besser den Bogen ausfüllen, den die Wächter dir reichen. Sie versichern dir, das du danach endlich in die Stadt eintreten darfst, um den Abend in der Schenke zu `\$ve`erb`Wri`4ng`wen. `i`c`n`n`n`n 
`\$Falls du noch nicht verifiziert bist, sprich mit dem Admin bitte die Details dazu ab, damit du schnellstmöglich hinein kommst. Wie das mit der Verifizierung genau geht, steht auch in den Regeln, unter Punkt 8.`i`n`n");
   } 

       viewcommentary("tor","Hinzufügen",15); 

page_footer();
?> 