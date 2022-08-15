
<?php
// MOD tcb, 17.5.05: Bettelstein -> Tempel

require_once("common.php");
// This idea is Imusade's from lotgd.net
addcommentary();
define("ROCKCOLORHEAD","`È");
define("ROCKCOLORTEXT","`1");

checkday();
if ($_GET['op'] == "egg"){
               $show_ooc = true;

        page_header("Das goldene Ei");
        output("`^Du untersuchst das Ei und entdeckst winzige Inschriften:`n`n");
        viewcommentary("goldenegg","Botschaft hinterlassen:",10,"schreibt");
        addnav('Zurück');
        addnav("Ei wieder wegstecken","rock.php");
}else if($_GET['op'] == "egg2"){
        page_header("Das goldene Ei");
        $preis=$session['user']['level']*60;
        output("".ROCKCOLORTEXT."Du fragst ein paar Leute hier, ob sie wissen, wo sich der Besitzer des legendären goldenen Eis aufhält. Einige lachen dich aus, weil du nach
                einer Legende suchst, und schütteln nur den Kopf. Du willst gerade ".($session['user']['sex']?"einen jungen Mann":"eine junge Dame")." ansprechen,
                als dich eine nervös wirkende Echse zur Seite zieht: \"`#Psssst! Ich weissss, wen Ihr ssssucht und wo ssssich diesssser Jemand aufhält. Aber wenn
                ich Euch dassss ssssagen ssssoll, müsssst Ihr mir einen Gefallen tun. Ich habe Sssschulden in Höhe von `^".$preis."`# Gold. Helft mir, diesssse
                losssszzzzuwerden, und ich ssssag Euch, wassss ich weissss. Anssssonssssten habt Ihr mich nie gessssehen.".ROCKCOLORTEXT."\"");
        addnav('Goldenes Ei');
        addnav("G?Zahle `^".$preis."`0 Gold","rock.php?op=egg3");
        addnav("Zucke mit den Schultern","rock.php");
}else if($_GET['op'] == "egg3"){
        page_header("Das goldene Ei");
        $preis=$session['user']['level']*60;
        if ($session['user']['gold']<$preis){
                output("".ROCKCOLORTEXT."Energisch schüttelt die Echse den Kopf. \"`#Von dem bisssschen Gold kann ich meine Sssschulden nicht bezzzzahlen. Vergesssst essss!".ROCKCOLORTEXT."\"");
        }else{
                $sql="SELECT acctid,name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts WHERE acctid=".getsetting("hasegg",0);
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $loggedin=user_get_online(0,$row);
                if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"Online":"in den Feldern");
                if ($row['location']==USER_LOC_INN) $loc="in einem Zzzzimmer in der Kneipe";
                // part from houses.php
                if ($row['location']==USER_LOC_HOUSE){
                        $loc="im Haussss Nummer ".($row['restatlocation'])."";
                }
                // end houses
                $row['name']=str_replace("s","ssss",$row['name']);
                $row['name']=str_replace("z","zzzz",$row['name']);
                output("".ROCKCOLORTEXT."Die Echse nimmt deine `^".$preis."".ROCKCOLORTEXT." Gold, schaut sich nervös um und flüstert dir zu: \"`#".$row['name']."`# isssst ".$loc." ".($row['alive']?"und lebt.":", isssst aber tot!")." Und jetzzzzt lasssst mich bitte in Ruhe. Ach, ja: Diesssse Information habt Ihr nicht von mir!".ROCKCOLORTEXT."\"");
                $session['user']['gold']-=$preis;
        }
        addnav('Zurück');
        addnav("Danke für die Info!","rock.php");
}else if($_GET['op'] == "idols"){
        page_header("Idole");
        output("".ROCKCOLORTEXT."Du mischst dich unter die Leute und beginnst kleine, belanglose Plaudereien.`nWie zufällig näherst du das Gesprächsthema einem ganz bestimmten Begriff an: `^Idol".ROCKCOLORTEXT.".`nNach einer ganzen Reihe von ernüchternden wie auch spöttischen Antworten stehst du plötzlich vor einem Zwerg, der genüsslich sein Ale aus einem 2-liter Krug schlürft.`nDu machst es kurz und kommst direkt zur Sache, doch der kräftige Mann scheint dich gar nicht wahrzunehmen.`nAls du deinen Redefluss für einen Moment unterbrichst, um Luft zu holen, deutet er dir mit der Hand die Zahl `^1".ROCKCOLORTEXT.", bevor er sich weiter seinem Ale widmet.");
        addnav('Idole');
        addnav("G?Zeige 1 Goldstück","rock.php?op=idols2");
        addnav("E?Zeige 1 Edelstein","rock.php?op=idols3");
        addnav("Zucke mit den Schultern","rock.php");
}else if($_GET['op'] == "idols2"){
    page_header("Idole");
  if ($session['user']['gold']<1)
  {
    output("`4Du kannst dir ja noch nicht mal das leisten!`n".ROCKCOLORTEXT."Beschämt über deine Armut lässt du den Zwergen stehen und mischst dich wieder unter die Leute.`n");
    addnav("Zurück","rock.php");
  }
  else
  {
    $session['user']['gold']--;
    output("".ROCKCOLORTEXT."Mit stolzem Grinsen ziehst du ein funkelnagelneues, strahlendes und auf Hochglanz poliertes Goldstück aus deiner Tasche und hälst es dem Zwerg unter die Nase, als wären es die Kronjuwelen der südlichen Reiche.`nRecht unbeeindruckt nippt dieser jedoch weiter an seinem Ale und als du ihm gerade deinen Schatz noch darbietungsvoller hinhalten willst, rempelt dich jemand von hinten an und das Goldstück fällt dir aus der Hand!`nDas wirst du wohl nicht wieder sehen.`n");
    addnav("Mist!","rock.php?op=idols");
  }
}else if($_GET['op'] == "idols3"){
    page_header("Idole");
  if ($session['user']['gems']<1)
  {
    output("`4Du kannst dir ja noch nicht mal das leisten!`n".ROCKCOLORTEXT."Beschämt über deine Armut lässt du den Zwergen stehen und mischst dich wieder unter die Leute.`n");
    addnav("Zurück","rock.php");
  }
  else
  {
    $price=$session['user']['level']*70;
    $session['user']['gems']--;
    output(ROCKCOLORTEXT.'Der Zwerg schnappt sich den Edelstein und lässt ihn in seine Tasche fallen, dann blickt er dich von oben bis unten an.`n`n"`@Wie ein Schatzjäger seht Ihr mir aber nicht aus.".ROCKCOLORTEXT."", urteilt er knapp und fährt fort ohne dir die Möglichkeit einer Rechtfertigung zu lassen,"`@Aber gut gut... Ihr wollt also etwas über die Idole wissen. Tja, wo fange ich an? Ja, genau! Es gibt 5 Stück davon, warum weiß keiner so recht. Manche munkeln es würde mit den Elementen zu tun haben, aber davon sind mir auch nur 3 bekannt:`nFeuer, Erz und Gold. Naja, vielleicht noch Silber, aber das zählt nicht so wirklich.`nWo war ich stehen geblieben? Ah ja, es gibt davon 5, das `^Idol des Waldläufers`@, das `!Idol des Genies`@, das `4Idol des Kriegers`@, das `2Idol des Anglers`@ und das `&Idol des Totenbeschwörers`@... Allen sagt man nach, dass sie magische Kräfte haben sollen und ihrem Träger übernatürliche Stärke verleihen.`nUnd ich glaube sogar zu wissen, wo sich diese befinden... könnten.`nAber oh je! Mein Krug ist leer und meine Stimme wird rauh! Ich kann Euch so unmöglich etwas erzählen... wäret Ihr so gut meine Rechnung zu zahlen und mir ein weiteres Ale zu holen?"'.ROCKCOLORTEXT.'`n`nDu stellst fest, dass sich die Rechnung des guten Zwerges auf mittlerweile `^'.$price.ROCKCOLORTEXT.' Goldmünzen beläuft, und weniger wird es sicher nicht, je länger du wartest.`nWillst du zahlen und deine Frage stellen?');
   addnav('Idole');
   addnav("`^Idol des Waldläufers`&?","rock.php?op=idols4&what=1&price=".$price);
   addnav("`!Idol des Genies`&?","rock.php?op=idols4&what=2&price=".$price);
   addnav("`4Idol des Kriegers`&?","rock.php?op=idols4&what=3&price=".$price);
   addnav("`2Idol des Anglers`&?","rock.php?op=idols4&what=4&price=".$price);
   addnav("`&Idol des Totenbeschwörers?","rock.php?op=idols4&what=5&price=".$price);
   addnav('Genug davon');
   addnav("Alles Humbug!","rock.php");
  }
}else if($_GET['op'] == "idols4"){
    page_header("Idole");
    $price=$_GET['price'];
    if ($session['user']['gold']<$price)
    {
      output("".ROCKCOLORTEXT."Beschämt musst du dem Zwerg beichten, dass du nicht in der Lage bist, seine Rechnung zu bezahlen, und dich still und leise davon machen.`n");
      addnav("Zurück","rock.php");
    }
    else
    {
      $session['user']['gold']-=$price;
      $what=$_GET['what'];
      switch ($what)
      {
        case 1:
        $id="idolrnds";
        $name="`^Idol des Waldläufers";
        break;
        case 2:
        $id="idolgnie";
        $name="`!Idol des Genies";
        break;
        case 3:
        $id="idolkmpf";
        $name="`4Idol des Kriegers";
        break;
        case 4:
        $id="idolfish";
        $name="`2Idol des Anglers";
        break;
        case 5:
        $id="idoldead";
        $name="`&Idol des Totenbeschwörers";
        break;
      }
      
                $sql="SELECT acctid,accounts.name,location,loggedin,laston,alive,housekey,activated,restatlocation FROM accounts LEFT JOIN items it ON acctid=it.owner WHERE it.tpl_id='$id'";
        $result = db_query($sql) or die(db_error(LINK));
        $price=round($price*1.5);
        output(ROCKCOLORTEXT.'Der Zwerg nimmt dankend ein neues Ale entgegen und beobachtet mit Freude wie du seine Zeche zahlst.`nDann raunt er dir zu:`n');
        if (db_num_rows($result)>0)
        {
                $row = db_fetch_assoc($result);
                $loggedin=user_get_online(0,$row);
                if ($row['location']==USER_LOC_FIELDS) $loc=($loggedin?"online":"in den Feldern");
                if ($row['location']==USER_LOC_INN) $loc="in einem Zimmer in der Kneipe";
                if ($row['location']==USER_LOC_HOUSE){
                        $loc="im Haus Nummer ".($row['restatlocation'])."";
                }
        output('"`@Jemand namens '.$row['name'].'`@ soll derzeit das '.$name.' `@mit sich herum schleppen, befindet sich '.$loc.'`@ und '.($row['alive']?" erfreut sich bester Gesundheit.":" ist mausetot!").'`n');
        }
        else
        {
        output('"`@Tief im Wald, so munkelt man, gibt es ein Grab, das Grab eines alten Recken, den letztendlich auch sein Schicksal ereilt hat und den das Idol nicht vor dem Tod bewahren konnte.`nIn diesem Grab werdet Ihr finden, was Ihr sucht, wenn Euch nicht ein anderer zuvorkommt!`n');
        }
        output('Wenn Ihr mehr wissen wollt... Nur zu... Fragt! Mein Krug ist schon wieder leer und da gibt es auch noch eine alte Rechnung zu zahlen.. Ich glaube, sie beträgt um die `^'.$price.'`@ Goldmünzen.'.ROCKCOLORTEXT.'"');
        addnav('Idole');
        addnav("`^Idol des Waldläufers`&?","rock.php?op=idols4&what=1&price=".$price);
        addnav("`!Idol des Genies`&?","rock.php?op=idols4&what=2&price=".$price);
        addnav("`4Idol des Kriegers`&?","rock.php?op=idols4&what=3&price=".$price);
        addnav("`2Idol des Anglers`&?","rock.php?op=idols4&what=4&price=".$price);
        addnav("`&Idol des Totenbeschwörers?","rock.php?op=idols4&what=5&price=".$price);
        addnav('Genug davon');
        addnav("Danke für die Info!","rock.php");
    }
} elseif($_GET['op'] == 'map') {
        
        page_header('Die Schatzkarten');
        
        $preis = $session['user']['level'] * 60;
                
        if($_GET['act'] == 'ok') {
                
                if ($session['user']['gold']<$preis){
                        output("".ROCKCOLORTEXT."Energisch schüttelt die Echse den Kopf. \"`#Von dem bisssschen Gold kann ich meine Sssschulden nicht bezzzzahlen. Vergesssst essss!".ROCKCOLORTEXT."\"");
                }
                else{
                        $sql = 'SELECT a.name,a.loggedin,a.location,a.laston,a.acctid,a.activated,a.restatlocation FROM items i
                                        LEFT JOIN accounts a ON a.acctid=i.owner WHERE i.tpl_id="mapt" AND i.owner!='.$session['user']['acctid'].' GROUP BY i.owner ORDER BY RAND() LIMIT 4';
                        $res = db_query($sql);                        
                        
                        output("".ROCKCOLORTEXT."Die Echse nimmt deine `^".$preis."".ROCKCOLORTEXT." Gold, schaut sich nervös um und flüstert dir zu: `n");
                        
                        if(db_num_rows($res) == 0) {
                                output('NIEMAND hat eine Ssssschatzzzkarte!');
                        }
                        else {
                        
                                while($p = db_fetch_assoc($res)) {
                                        
                                        $loggedin=user_get_online(0,$p);
                                        if ($p['location']==USER_LOC_FIELDS) $loc=($loggedin?"Online":"in den Feldern");
                                        if ($p['location']==USER_LOC_INN) $loc="in einem Zzzzimmer in der Kneipe";
                                        // part from houses.php
                                        if ($p['location']==USER_LOC_HOUSE){
                                                $loc="im Haussss Nummer ".($p['restatlocation'])."";
                                        }
                                        // end houses
                                        $p['name']=str_replace("s","ssss",$p['name']);
                                        $p['name']=str_replace("z","zzzz",$p['name']);
                                        
                                        output('`n'.ROCKCOLORTEXT.''.$p['name'].ROCKCOLORTEXT.' besssitzt mindestens einen Teil und issst '.$loc.'.');
                                                                                                
                                }                        
                                
                                output('`n`nDassss wissssssst Ihr aber nicht von mir, und nun verssssschwindet.');
                                
                        }        // END if vorhanden
                        
                        $session['user']['gold']-=$preis;
                        
                }        // END if gold
                
        }        // END if ok
        else {
                output("".ROCKCOLORTEXT."Du fragst ein paar Leute hier, ob sie wissen, wer bisher Schatzkarten besitzt. Da zieht dich eine nervös wirkende Echse zur Seite: ");
                output("\"`#Psssst! Ich weissss, was Ihr ssssucht und wo sssssich einiges davon befindet. Aber wenn ich Euch dassss ssssagen ssssoll, müsssst Ihr
                        mir einen Gefallen tun. Ich habe Sssschulden in Höhe von `^".$preis."`# Gold. Helft mir, diesssse losssszzzzuwerden und ich ssssage Euch,
                        wassss ich weissss. Anssssonssssten habt Ihr mich nie gessssehen.".ROCKCOLORTEXT."\"");
                addnav('Schatzkartenteile');
                addnav("G?Zahle `^".$preis."`0 Gold","rock.php?op=map&act=ok");
                addnav("Zucke mit den Schultern","rock.php");
        }
        
}
else{
        page_header("Veteranenkeller");

        output("`b`c".ROCKCOLORHEAD."Im Keller der Schenke`0`c`b`n".ROCKCOLORTEXT."
                Neben Mareks Schenke führt eine Treppe auf direktem Wege hinab in den Keller des Wirtshauses, wo dich ein merklich kleinerer, aber ebenfalls mit
                Tischgruppen eingerichteter Raum erwartet. Eine eigene Bar gibt es nicht; stattdessen führt eine weitere Treppe auf der gegenüberliegenden Seite
                hinauf ins Erdgeschoss und zum eigentlichen Schankraum. Zu deiner Rechten prasselt ein Feuer im in die Wand eingelassenen Kamin, was den einen und
                anderen Anwesenden dazu bewogen hat, seinen Stuhl in unmittelbare Nähe zu schieben, um sich entspannt aufzuwärmen. An manchen Tischen werden
                Karten gespielt, an anderen Geschichten über vergangene Zeiten und heldenhafte Taten erzählt. Ein Hauch von Nostalgie liegt in der Luft. Hier
                sitzen ".getsetting('townname','Eranya')."s Veteranen zusammen, ohne vom täglichen Trubel der Schenke gestört zu werden.`n`n");
        $int_hasegg_acctid = getsetting("hasegg",0);
        if ($session['user']['acctid'] == $int_hasegg_acctid) {
            output("Da du dich hier zurückziehen kannst, könntest du das `^goldene Ei".ROCKCOLORTEXT." einmal näher untersuchen.`n`n");
            addnav('Goldenes Ei');
            addnav("u?Ei untersuchen","rock.php?op=egg");
        } elseif($int_hasegg_acctid > 0) {
            addnav('Goldenes Ei');
            addnav("N?Nach dem goldenen Ei fragen","rock.php?op=egg2");
        }
        addnav('Informationen');
        if (getsetting("idols_acttivated",1)>0){
            addnav("I?Nach den Idolen fragen","rock.php?op=idols");
        }
        addnav('S?Nach Schatzkarten fragen','rock.php?op=map');
        addnav('Magisches');
        addnav("J?Schrein des Jarcath","shrine.php");
        addnav("E?Schrein der Erneuerung","rebirth.php");
        addnav('R?Runenmeister','runemaster.php?op=master');
        viewcommentary("veteranenkeller","Sagen",15,"sagt");
}
addnav('Verlassen');
addnav("Zurück zum Stadtplatz","village.php");

page_footer();
?>

