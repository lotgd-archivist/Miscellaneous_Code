<?php
/*****************************************************************
* RP Ort Burgruine
* Datei geschrieben für Ithil (www.ithil-lotgd.de.vu) von Shaiya
* Kopf darf nicht verändert oder gelöscht werden
* Dateiname: burgruine.php
/*****************************************************************
* Beschreibung:
* Der PR Ort Burgruine ist den Götter des Dorfes gewittmet,
* Außerdem gibt es für jeden Gott einen weiteren unter RP Ort.
/*****************************************************************
* Installation:
* Datei hochladen und addnav('zur Burgruine','burgruine.php');
* auf dem Dorfplatz hinzufügen
/*****************************************************************
* Sonstiges bei der Installation:
    -> nichts weiter zu beachten.
/*****************************************************************/
/*versuch Caro - ('zur Heilerhütte','heilerhuette.php') verlinkt in der Heilerhütte im Wald*/
//Liliengarten = Behandlungszimmer 1,heilerhuette.php?po=behandlung1
//Wolfshöhle = Behandlungszimmer 2,heilerhuette.php?po=behandlung1
//heilige Oase= 'Labor','heilerhuette.php?op=labor
//Burgruine = Heilerhütte,heilerhuette.php

require_once "common.php";
addcommentary();                     
                                       
   switch($_GET['op'])
{ 
    case 'behandlung1': 
    {
        page_header("Behandlungszimmer 1");
        output("`c`b`LB`le`Kh`ya`Yn`9d`xl`Xu`mn`&s`fz`Fim`jm`&e`mr `L1`c`b`n
                `9Der erste und zweite Behandlungsraum sind nahezu identisch eingerichtet.`n
`cBis auf darauf, dass der erste gelbe Vorhänge besitzt.`n
Etwas in der Mitte ist ein Bett und zwei Stühle aufgestellt. Im Regal stapeln sich frische Laken und Bezüge.`n
Die verschiedenen Instrumente sind säuberlich auf Tüchern ausgelegt, welche sich in einem kleinen Schränkchen mit Schubladen befinden.
Ist sie trotz Magie auch auf Hilfsmittel angewiesen.`n
Auch hier sind allerlei Tiegel und Töpfchen zu finden, welche Salben und getrocknete Kräuter enthalten.
Ein kleiner Bach mit Quelle in der Nähe, sorgt für frisches Wasser.`c`n");                      

        viewcommentary("behandlung1","sagt:",25);      
        addnav("zum Hauptraum","heilerhuette.php"); 
        addnav("zum zweiten Behandlungsraum","heilerhuette.php?op=behandlung2");
        addnav("zum Labor","heilerhuette.php?op=labor");
        addnav("zum Heiler","healer.php");
        break;
    }
    case 'behandlung2':
    {
        page_header("Behandlungszimmer 2"); 
        output("`c`b`m `LB`le`Kh`ya`Yn`9d`xl`Xu`mn`&s`fz`Fim`jm`&e`mr `L2`c`b`n
        `9`cDer erste und zweite Behandlungsraum sind nahezu identisch eingerichtet.`n
Bis auf darauf, dass dieser hier blaue vorhänge besitzt`n
Etwas in der Mitte ist ein Bett und zwei Stühle aufgestellt. Im Regal stapeln sich frische Laken und Bezüge.
Die verschiedenen Instrumente sind säuberlich auf Tüchern ausgelegt, welche sich in einem kleinen Schränkchen mit Schubladen befinden.
Ist sie trotz Magie auch auf Hilfsmittel angewiesen.`n
Auch hier sind allerlei Tiegel und Töpfchen zu finden, welche Salben und getrocknete Kräuter enthalten.
Ein kleiner Bach mit Quelle in der Nähe, sorgt für frisches Wasser.`c`n");
        viewcommentary("behandlung2","sagt:",25);    
//        addnav("zur Burgruine","burgruine.php");   
//        addnav("zum Dorfplatz","village.php");   
        addnav("zum Hauptraum","heilerhuette.php"); 
        addnav("zum ersten Behandlungsraum","heilerhuette.php?op=behandlung1");
        addnav("zum Labor","heilerhuette.php?op=labor");
        addnav("zum Heiler","healer.php");
        break;
                    
    }
    case 'labor':
    {
        page_header("Labor");
        output("`b`c`9L`xa`Xb`mo`&r`n
        `c`c`9Im Laborbereich lagern im Regal Mörser und Stößel oder auch etliche Glasfläschchen.
        Auch Schalen stehen im Regal, um Dinge einzufüllen. `c`n");                      
//        output("`n`e Unterhalte dich mit den anderen Göttern:`n");   
        viewcommentary("Labor","sagt:",25);      
//        addnav("zur Burgruine","burgruine.php");    
//        addnav("zum Dorfplatz","village.php");
        addnav("zum Hauptraum","heilerhuette.php"); 
        addnav("zum ersten Behandlungsraum","heilerhuette.php?op=behandlung1");
        addnav("zum zweiten behandlungsraum","heilerhuette.php?op=behandlung2");
        addnav("zum Heiler","healer.php");
        break;
/*    }
    case 'ebene':
    {
        page_header("Die Ebene");
        output("`b`c`gDie `kE`Gb`ae`Gn`ke`c`b
`n
`n`gNachdem du an der Burgruine vorbei gelaufen bist und somit in den `.W`'al`.d`g hinein, lichtet sich der `.W`'al`.d`g nach einigen Metern wieder und du bleibst stehen. Vor dir erstreckt sich eine große grasbewachsene `kE`Gb`ae`Gn`ke`g. Hier und dort stehen kleiner `UB`ua`}u`Ie`tr`ynh`tü`It`}t`ue`Un`g, welche aber seit vielen Jahren wohl verlassen sind. Bei vielen ist das Dach eingefallen oder man sieht nur noch das Grundgerüst. Vereinzelt sieht man noch `yW`te`Ii`}d`ue`Un`uz`}ä`Iu`tn`ye`g und sogar wo einst Felder angelegt waren. Doch nun nachdem die Höfe schon so lange verlassen scheinen, wächst alles wild, was einst angepflanzt worden war. In den ehemaligen Feldern sieht man Gerste und Weizen wachsen, doch keiner wird es ernten. Auf den Weiden steht das `@G`kra`@s`g so hoch, dass du bis zu den Knien darin versinkst. Eine Vielzahl von Wildblumen wächst und verleihen der `kE`Gb`ae`Gn`ke`g einen idyllischen Anblick. Sicher kann man hier, in den Abendstunden, viele Wildtier sehen, die sich am `@G`kra`@s`g gütlich tun.");
        viewcommentary("ebene","sagt:",25);   
        addnav("`IVerfallene `UB`ua`}u`Ie`tr`ynh`tü`It`}t`ue","burgruine.php?op=bauernhuette");
        addnav("zur Burgruine","burgruine.php");    
        addnav("zum Dorfplatz","village.php");
    
        break;
    }
    case 'bauernhuette':
    {
        page_header("Die Ebene");
        output("`b`c`IVerfallene `UB`ua`}u`Ie`tr`ynh`tü`It`}t`ue`c`b
`n
`n`IEine Weile bist du auf der Ebene umher gelaufen und stehst nun vor einer der verfallenen `yH`tü`It`}t`ue`Un`I. Neugierig betrittst du sie, dabei musst du dich leicht bücken, da der Türrahmen nicht mehr wirklich da ist wo er einst war. Drinnen schaust du dann leicht verwundert drein. Von außen so schlecht in Schuss wirkend, sieht es hier drin eigenartiger weise recht gemütlich aus. Der Boden ist zwar leicht staubig, aber in einer Ecke ist `tH`yol`tz`I aufgeschichtet, der Kamin hat nur leichte `(A`es`ec`sh`fen`sre`est`7e`I, als hätte dort jemand sauber gemacht und in einer Ecke liegt sogar scheinbar recht frisches `6S`^t`/r`^o`6h`I und Decken. Es wirkt fast so als würde jemand die `yH`tü`It`}t`ue`I zum übernachten nutzen, denn auch das Dach wirkt recht dicht. Wer weis, vielleicht liegt ein Zauber auf der `yH`tü`It`}t`ue`I damit niemand von außen sie als bewohnt erkennt. … Und doch, der Staub zeigt deutlich, dass hier eine ganze Weile, keiner mehr gewesen ist.");
        viewcommentary("bauernhuette","sagt:",25);   
        
        addnav("Zurück","burgruine.php?op=ebene");   
        addnav("zur Burgruine","burgruine.php");   
        addnav("zum Dorfplatz","village.php");
    
        break;*/
    }
    default:
    {
                                             
        page_header("Heilerhütte");           

        output("`c`b`#`fH`Fe`ji`&l`me`Lr`lh`Kü`yt`Yt`9e`&n`c`b`n  `b`c`n
        `9Sobald man durch die Tür tritt, fällt einem der hell und freundlich wirkende Raum auf.
        An den Wänden stehen Regale mit Behältern und Phiolen, die mit Kräutern oder Flüssigkeiten gefüllt sind.
        In einem anderen Regal stapelt sich Mull und Verbandmaterial.`n
        Vor dem Fenster steht ein kleiner Schreibtisch, mit allerlei Blättern und Notizzetteln übersät.
        In einer Ecke befindet sich ein kleiner Ofen, mit einem Wasserkessel für Tee oder warmes Wasser.
        Unverkennbar zieht ein feiner Duft nach Kräutern durch das Zimmer.`n
        Auf der Fensterbank befinden sich allerlei Töpfe, worin verschiedene Heilpflanzen ausgesät sind.
        In Schreibtischnähe sind auf einem kleinen Regal Bücher über Kräuter und deren Anwendungen zu erkennen.
        Bilder von Pflanzen hängen an der Wand.`b`c`n");                     


        viewcommentary("Heilerhuette","sagt",25);      

        addnav("Zurück");
        addnav("zum Heiler","healer.php"); 
        addnav("In der Hütte"); 
        addnav("zum Behandlungszimmer 1", "heilerhuette.php?op=behandlung1");
        addnav("zum Behandlungszimmer 2","heilerhuette.php?op=behandlung2");
        addnav("zum Labor","heilerhuette.php?op=labor");
//        addnav("Ebene","burgruine.php?op=ebene");
/*        if (($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER)))
        {
            addnav('Besonderes');
            addnav('Heilige Oase','burgruine.php?op=heiligeoase');
        }*/
        break;
     }
    
}                                 
page_footer();
?> 