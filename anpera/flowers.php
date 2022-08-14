
ï»¿<?php



//27122004



//Pflanzenzucht 

//Idee von Fichte, Texte von Kisa, Zusammengeschuster von Hecki ) 

//Version: 1.1 

//Erstmals eschienen auf http://www.cirlce-of-prophets.de/logd 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 

//Modifiziert von anpera: Nutzt items table

//

/**** EINBAUANLEITUNG (nur fÃ¼r LoGD 0.9.7 ext GER Release Nr. 3) ***



* In gardens.php finde:

addnav("Geschenkeladen","newgiftshop.php");



* FÃ¼ge danach ein:

 

addnav("Blumenbeet","flowers.php");



* In newday.php finde:

$sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber') AND owner=".$session[user][acctid]." ORDER BY id";



* und ersetze es durch:



$sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber' OR class='Beet') AND owner=".$session[user][acctid]." ORDER BY id";



* Finde weiter:

if (strlen($row[buff])>8){



*FÃ¼ge DAVOR ein:



if ($row['class']=="Beet" && $row['value1']>0) db_query("UPDATE items SET value1=0 WHERE class='Beet' and owner=".$session['user']['acctid']); 



*Datei flowers.php in den Logd Ordern hochladen

*/ 

// Viel Spass ihr HobbygÃ¤rtner! 



require_once "common.php";

$sql="SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND class='Beet' LIMIT 1";

$result=db_query($sql);

$beet=db_fetch_assoc($result);

$beet['bit']=db_num_rows($result);

page_header("Blumenbeet"); 



if ($HTTP_GET_VARS[op] == ""){ 

    output("`c`bPflanzenzucht`c`b"); 

    output("`n`n"); 

    if ($beet['bit']==0){ 

        output("`@ Hier kannst du dir ein Blumenbeet anlegen. Wenn du es tÃ¤glich pflegst wird schon bald die erste Knospe zu einer wunderschÃ¶nen BlÃ¼te werden."); 

        output("`@ Sei sorgsam und liebevoll, dann wird dich deine Pflanze sicher belohnen! Denn der Samen enthÃ¤lt magische Zutaten!"); 

        output("`n`n"); 

        output("`@ Ein Beet kostet einmalig 4000 Gold und 10 Edelsteine!`n"); 

        output("`@ Auf dem Beet ist Platz fÃ¼r eine Blume, aber diese entwickelt unendlich viele Knospen und in jeder ihrer Knospen wartet eine kleine Ãœberraschung auf dich!`n"); 

        addnav("Ein Beet anlegen","flowers.php?op=anlegen"); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

    }else{ 

        output("`@Voller Vorfreude betrittst du dein Beet. Du bist gespannt ob heute vielleicht etwas aus einer der Knospen spriest.`n`n"); 

        output("Du solltest etwas Zeit und Gold in die Aufzucht deiner Pflanze investieren, schliesslich braucht eine Pflanze, Liebe, Wasser und DÃ¼nger damit sie gedeiht!`n`n"); 

        if ($beet['value1']>0){

            output("`n`nDu hast dich heute schon um deine Pflanze gekÃ¼mmert und siehst, dass es ihr gut geht.");

        }

        addnav("Um deine Pflanze kÃ¼mmern (`^100`0 Gold)","flowers.php?op=kuemmern"); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

    } 

} 



if ($HTTP_GET_VARS[op] == "anlegen"){ 

    if ($session['user']['gold']>3999 && $session['user']['gems']>9){ 

        $session['user']['gold'] -= 4000; 

        $session['user']['gems'] -= 10; 

        db_query("INSERT INTO items (class,owner,name,value1,value2,description) VALUES ('Beet',".$session['user']['acctid'].",'Blumenbeet',0,0,'Dein eigenes kleines Blumenbeet')"); 

        output("`n`n`2Du hast jetzt ein schÃ¶nes Blumenbeet, und kannst mit deiner Aufzucht beginnen!`n"); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

        addnav("Zu deinem Beet","flowers.php"); 

    }else{ 

        output("`n`n`2Leider hast du nicht genug Gold und/oder Gems dabei, komm doch spÃ¤ter wieder vorbei!`n"); 

        output("`n`n"); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

    } 

} 



if ($HTTP_GET_VARS[op] == "kuemmern"){ 

    if($session['user']['gold']>99 && $beet['value1']==0 && $session['user']['turns']>0){ 

        $session['user']['turns'] --; 

        $session['user']['gold'] -=100; 

        $beet['value2'] ++; 

        $beet['value1'] = 1; 

        output("`@Du steckst viel Liebe und Energie in deine Arbeit, und hoffst das dich deine Pflanze in naher Zukunft fÃ¼r deine aufopferungsvollen BemÃ¼hungen belohnen wird!`n`n"); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

        if ($beet['value2']==10){ 

            $up = e_rand(1,5); 

            $beet['value2']=0; 

            switch ($up){ 

                case 1: 

                output("`qVor deinen Augen Ã¶ffnet sich plÃ¶tzlich eine der Knospen und eine wunderschÃ¶ne,"); 

                output("`qlecker riechende Frucht erblickt das Licht der Welt und danach die Dunkelheit deines Rachens.``nn"); 

                output("`@ Diese Frucht bringt dir 1 permanenten Lebenspunkt!"); 

                $session['user']['maxhitpoints']++; 

                break; 

                case 2: 

                output("`qAls du deine Blume hoffnungsvoll anschaust scheint sie sich doch tatsÃ¤chlich zu bewegen."); 

                output("`qJa, es ist wahr, die BlÃ¼te Ã¶ffnet sich ganz langsam und als sie vollkommen aufgeblÃ¼ht ist bist du dir ganz sicher, dass es die allerschÃ¶nste Blume ist, die du je in deinem Leben gesehen hast."); 

                output("`qVor lauter Begeisterung kannst du garnicht reagieren als dein Nachbar auf dich zugerannt kommt,"); 

                output("`qdir 500 Gold in die Hand drÃ¼ckt und mit deiner wunderschÃ¶nen Blume hinter der nÃ¤chsten Ecke verschwindet. Du stehst da mit offenem Mund und fragst dich ob du je wieder eine solch wundervolle Blume zÃ¼chten kannst!!!"); 

                $session['user']['gold']+=500; 

                break; 

                case 3: 

                output("`qVor deinen Augen Ã¶ffnet sich plÃ¶tzlich eine der Knospen und eine wunderschÃ¶ne,"); 

                output("`qlecker riechende Frucht erblickt das Licht der Welt und danach die Dunkelheit deines Rachens.``nn"); 

                output("`@ Diese Frucht bringt dir 5 weitere WaldkÃ¤mpfe!"); 

                $session['user']['turns'] += 5; 

                break; 

                case 4: 

                output("`5VetrÃ¤umt schaust du dein BlÃ¼mchen an und hoffst das du dich bald an ihrer wunderschÃ¶nen BlÃ¼te erfreuen kannst.`n`n"); 

                output("`5PlÃ¶tzlich reckt sich das kleine BlÃ¼mchen und innerhalb von Sekunden erblÃ¼ht eine ihrer Knospen in den schÃ¶nsten Regenbogenfarben.`n"); 

                output("`5Sie scheint richtig zu glÃ¤nzen, nur fÃ¼r dich. Du hÃ¤ltst sie an deine Nase um ihren lieblichen Duft in dir aufzunehmen und je nÃ¤her du sie richtung Nase hÃ¤ltst desto heller leuchtet sie!`n`n"); 

                output("`5Heller, heller und immer heller strahlt sie dich an, du bist von Ihrer SchÃ¶nheit wahrlich geblendet"); 

                output("`5und entdeckst erst als du die Blume ganz an deiner Nase hast, dass ihre BlÃ¼te mit Edelsteinen verziert ist.`n`n"); 

                output("`5Du steckst die 2 Edelsteine sorgsam ein und beschlieÃŸt dich noch intensiver um dein kleines PflÃ¤nzchen zu kÃ¼mmern - wer weiÃŸ was die nÃ¤chste BlÃ¼te fÃ¼r wundersame KrÃ¤fte in sich verbirgt - "); 

                $session['user']['gems']+=2; 

                break; 

                case 5: 

                output("`qGespannt wartest du, wann deine MÃ¼hen endlich belohnt werden und tatsÃ¤chlich, eine der grÃ¶ÃŸten Knospen an deiner Blume reckt und streckt sich und erblÃ¼ht zu einer wahren Pracht."); 

                output("`qDu bist so stolz wie noch nie zuvor auf dich selbst. Jetzt weiÃŸt du was einen richtigen GÃ¤rtner ausmacht.`n`n"); 

                output("`@Dieses Wissen lÃ¤sst deine Erfahrung um 2% ansteigen!"); 

                $session['user']['experience']*=1.02; 

                break; 

            } 

        }

    }else if ($session['user']['gold']<100){

        output("Du hast zuwenig Gold dabei."); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

    }else{ 

        output("Du kannst dir heute keine Zeit mehr fÃ¼r deine Pflaze nehmen."); 

        addnav("ZurÃ¼ck zum Garten","gardens.php"); 

    } 

    db_query("UPDATE items SET value1={$beet['value1']},value2={$beet['value2']} WHERE class='Beet' and owner=".$session['user']['acctid']);

} 



page_footer(); 

?> 
