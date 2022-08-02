<?php

##Wohnviertel Betaversion!!


require_once("common.php");
page_header("Das Wohnviertel");
#$sql="ALTER TABLE `accounts` ADD `diedinwohnviertel` INT( 20 ) DEFAULT '0' NOT NULL ,
#ADD `killedinwohnviertel` INT( 20 ) DEFAULT '0' NOT NULL";
#db_query($sql);
if ($_GET['op']=="newday"){
    output("`2Gut erholt wachst du im Haus auf und bist bereit für neue Abenteuer.");
    $session[user][location]=0;
    $session[user][housekey]=0;
    addnav("Tägliche News","news.php");
    addnav("Ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="haus"){
    $id=$_GET['id'];
    $me=$session['user']['acctid'];
    $sql="SELECT * FROM items WHERE owner=$me AND value1=$id AND class='Schlüssel'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        $drin="0";
    }else{
        $drin="1";
    }
    $sql="SELECT * FROM wohnviertel WHERE houseid=$id";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($drin=="0"){
        if ($me==$row['owner1']) $drin="2";
        if ($me==$row['owner2'] && $drin=="0") $drin="2";
        if ($me==$row['owner3'] && $drin=="0") $drin="2";
        if ($me==$row['owner4'] && $drin=="0") $drin="2";
        if ($me==$row['owner5'] && $drin=="0") $drin="2";
        if ($me==$row['owner6'] && $drin=="0") $drin="2";
        if ($me==$row['owner7'] && $drin=="0") $drin="2";
        if ($me==$row['owner8'] && $drin=="0") $drin="2";
    }

    if ($drin!="0"){
        if ($row['status']=="1" && $drin=="2") addnav("Baustatus","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen");
        if ($row['status']=="2") addnav("Haus betreten","wohnviertel.php?op=hausdrin&id=$row[houseid]");
    }else{
        if ($row['status']=="2"){
            $sqltest="SELECT * FROM accounts WHERE housekey=$row[houseid]";
            $resulttest = db_query($sqltest) or die(db_error(LINK));
            if (db_num_rows($result)==0){
                output("`4In diesem Haus sind alle Bewohner bereits tot und es ist nichts mehr zu holen!");
            }else{
                addnav("Einbrechen","wohnviertel.php?op=einbruch&id=$row[houseid]");
            }
        }
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="fiesling"){
    $id=$_GET['id'];
    if (!$_POST[msg]){
        output("`@Da du den letzten Bewohner getötet hast, hast du hier die möglichkeit den Bewohnern des Hauses eine kleine Nachricht zukommen zu lassen");
        output("`2<form action=\"wohnviertel.php?op=fiesling&id=$id\" method='POST'>",true);
        output("`nWas möchtest du ihnen sagen? <input type='msf' name='msg' maxlength='255'>`n`n",true);
        output("<input type='submit' class='button' value='Mitteilen'>",true);
        addnav("","wohnviertel.php?op=fiesling&id=$id");
    }else{
        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `4ist in diesem Haus eingebrochen und hinterlässt folgende Nachricht: ".$_POST[msg]."')";
        db_query($sql) or die(db_error(LINK));
        output("Deine Nachricht liegt nun auf einem Tisch in der Küche. Schon bald werden sie die Bewohner finden...");
        addnav("Wohnviertel","wohnviertel.php");
    }
}elseif ($_GET['op']=="einbruch"){
    $id=$_GET['id'];
    $me=$session['user']['acctid'];
    /*if ($session['user']['race']['nopvp']==1) {
        output('`@Nachdem du nochmal genau nachgedacht hast, bist du dann doch der Meinung, dass du
                        keiner Kriegerrasse angehörst und wohl kaum Chancen hättest.`0');
        addnav("Umkehren","wohnviertel.php");
    }else{*/
        if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
        if ($session[user][turns]<1 || $session[user][playerfights]<=0){
            output("`nDu bist wirklich schon zu müde, um ein Haus zu überfallen.");
            addnav("Zurück","wohnviertel.php");
        }else{
            output("`2Du näherst dich vorsichtig Haus Nummer $id.");
            $krieger="SELECT * FROM accounts WHERE housekey=$id AND alive=1 AND marriedto!=$me";
            $resultk=db_query($krieger);
            if (db_num_rows($resultk)>0){
                $max=db_num_rows($resultk);
                $zufall=e_rand(1,$max);
                for ($i=0;$i<$zufall;$i++){
                    $rowk = db_fetch_assoc($resultk);
                }
                   $badguy = array("creaturename"=>$rowk['name'],"creaturelevel"=>$rowk['level'],"creatureweapon"=>$rowk['weapon'],"creatureattack"=>$rowk['attack'],"creaturedefense"=>$rowk['defence'],"creaturehealth"=>$rowk['hitpoints'], "diddamage"=>0);
                   updatetexts('badguy',createstring($badguy));
                   $fight=true;
                   redirect("wohnviertel.php?op=attack&name=".rawurlencode($rowk['login'])."");
            }else{
                output("Du wolltest gerade einbrechen aber als du das Haus betrittst siehst du niemanden mehr der noch da ist. Jedoch hörst du schon die Stadtwachen auf ihren Pferden kommen und entschließt dich besser zu flüchten!");
                addnav("Wohnviertel","wohnviertel.php");
            }
        }
    }
}elseif ($_GET['op']=="hausdrin"){
    $command=$_GET['command'];
    $id=$_GET['id'];
    $sql="SELECT * FROM wohnviertel WHERE houseid=$id";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($command=="bauen"){
        $edelsteinegesamt=0;
        if ($_GET['subop']=="gems"){
            $owner="0";
            $me=$session['user']['acctid'];
            if ($me==$row['owner1']) $owner="1";
            if ($me==$row['owner2']) $owner="2";
            if ($me==$row['owner3']) $owner="3";
            if ($me==$row['owner4']) $owner="4";
            if ($me==$row['owner5']) $owner="5";
            if ($me==$row['owner6']) $owner="6";
            if ($me==$row['owner7']) $owner="7";
            if ($me==$row['owner8']) $owner="8";
            if ($_GET['gems']=="1" || $_GET['gems']=="2" || $_GET['gems']=="3" || $_GET['gems']=="4" || $_GET['gems']=="5" || $_GET['gems']=="10"){
                if ($_GET['gems']=="1") $anzahl=1;
                if ($_GET['gems']=="2") $anzahl=2;
                if ($_GET['gems']=="3") $anzahl=3;
                if ($_GET['gems']=="4") $anzahl=4;
                if ($_GET['gems']=="5") $anzahl=5;
                if ($_GET['gems']=="10") $anzahl=10;
                if ($owner=="0"){
                    output("Da liegt ein Fehler vor. Du bist nicht Besitzer dieses Hauses.`n");
                }
                if ($owner=="1"){
                    $newgems=$row[gems1]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems1=$newgems WHERE houseid=$id";
                }
                if ($owner=="2"){
                    $newgems=$row[gems2]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems2=$newgems WHERE houseid=$id";
                }
                if ($owner=="3"){
                    $newgems=$row[gems3]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems3=$newgems WHERE houseid=$id";
                }
                if ($owner=="4"){
                    $newgems=$row[gems4]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems4=$newgems WHERE houseid=$id";
                }
                if ($owner=="5"){
                    $newgems=$row[gems5]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems5=$newgems WHERE houseid=$id";
                }
                if ($owner=="6"){
                    $newgems=$row[gems6]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems6=$newgems WHERE houseid=$id";
                }
                if ($owner=="7"){
                    $newgems=$row[gems7]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems7=$newgems WHERE houseid=$id";
                }
                if ($owner=="8"){
                    $newgems=$row[gems8]-$anzahl;
                    $sql555="UPDATE wohnviertel SET gems8=$newgems WHERE houseid=$id";
                }
                db_query($sql555);
                $session['user']['gems']=$session['user']['gems']-$anzahl;
                output("Du hast nun $anzahl Stein(e) weniger an die Bank zu zahlen`n");
            }else{
                if ($owner=="0"){
                    output("Da liegt ein Fehler vor. Du bist nicht Besitzer dieses Hauses.");
                }else{
                    output("`GWieviel Edelsteine möchtest du investieren?`n");
                    if ($owner=="1") $gems=$row[gems1];
                    if ($owner=="2") $gems=$row[gems2];
                    if ($owner=="3") $gems=$row[gems3];
                    if ($owner=="4") $gems=$row[gems4];
                    if ($owner=="5") $gems=$row[gems5];
                    if ($owner=="6") $gems=$row[gems6];
                    if ($owner=="7") $gems=$row[gems7];
                    if ($owner=="8") $gems=$row[gems8];
                    if ($session['user']['gems']>0 && $gems>0){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=1'>1 Edelstein</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=1");
                    }
                    if ($session['user']['gems']>1 && $gems>1){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=2'>2 Edelsteine</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=2");
                    }
                    if ($session['user']['gems']>2 && $gems>2){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=3'>3 Edelsteine</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=3");
                    }
                    if ($session['user']['gems']>3 && $gems>3){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=4'>4 Edelsteine</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=4");
                    }
                    if ($session['user']['gems']>4 && $gems>4){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=5'>5 Edelsteine</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=5");
                    }
                    if ($session['user']['gems']>9 && $gems>9){
                        output("<a href='wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=10'>10 Edelsteine</a>`n",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems&gems=10");
                    }
                }
            }
        }else{
            output("Momentaner Baustatus:`n");
            if ($row['owner1']!="0"){
                $sql2 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner1].'';
                $result2 = db_query($sql2) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                output("$row2[name] `Ghat noch `@$row[gems1]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems1];
            }
            if ($row['owner2']!="0"){
                $sql3 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner2].'';
                $result3 = db_query($sql3) or die(db_error(LINK));
                $row3 = db_fetch_assoc($result3);
                output("$row3[name] `Ghat noch `@$row[gems2]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems2];
            }
            if ($row['owner3']!="0"){
                $sql4 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner3].'';
                $result4 = db_query($sql4) or die(db_error(LINK));
                $row4 = db_fetch_assoc($result4);
                output("$row4[name] `Ghat noch `@$row[gems3]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems3];
            }
            if ($row['owner4']!="0"){
                $sql5 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner4].'';
                $result5 = db_query($sql5) or die(db_error(LINK));
                $row5 = db_fetch_assoc($result5);
                output("$row5[name] `Ghat noch `@$row[gems4]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems4];
            }
            if ($row['owner5']!="0"){
                $sql6 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner5].'';
                $result6 = db_query($sql6) or die(db_error(LINK));
                $row6 = db_fetch_assoc($result6);
                output("$row6[name] `Ghat noch `@$row[gems5]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems5];
            }
            if ($row['owner6']!="0"){
                $sql7 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner6].'';
                $result7 = db_query($sql7) or die(db_error(LINK));
                $row7 = db_fetch_assoc($result7);
                output("$row7[name] `Ghat noch `@$row[gems6]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems6];
            }
            if ($row['owner7']!="0"){
                $sql8 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner7].'';
                $result8 = db_query($sql8) or die(db_error(LINK));
                $row8 = db_fetch_assoc($result8);
                output("$row8[name] `Ghat noch `@$row[gems7]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems7];
            }
            if ($row['owner8']!="0"){
                $sql9 = 'SELECT * FROM accounts WHERE '.acctid.'='.$row[owner8].'';
                $result9 = db_query($sql9) or die(db_error(LINK));
                $row9 = db_fetch_assoc($result9);
                output("$row9[name] `Ghat noch `@$row[gems8]`G Edelsteine zu zahlen.`n");
                $edelsteinegesamt=$edelsteinegesamt+$row[gems8];
            }
            output("Somit sind ingesamt noch `@$edelsteinegesamt`G Edelsteine zu zahlen.");
            addnav("Gems zahlen","wohnviertel.php?op=hausdrin&id=$row[houseid]&command=bauen&subop=gems");
        }
           addnav("Zurück","wohnviertel.php?op=haus&id=$id");
        addnav("Wohnviertel","wohnviertel.php");
    }elseif ($command=="goldraus"){
        $maxtfer = $session[user][level]*getsetting("transferperlevel",25);
        if (!$_POST[gold]){
            $transleft = getsetting("transferreceive",3) - $session[user][transferredtoday];
            output("`2Es befindet sich `^$row[gold]`2 Gold in der Schatztruhe des Hauses.`nDu darfst heute noch $transleft x bis zu `^$maxtfer`2 Gold mitnehmen.`n");
            output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=goldraus\" method='POST'>",true);
            output("`nWieviel Gold mitnehmen? <input type='gold' name='gold'>`n`n",true);
            output("<input type='submit' class='button' value='Mitnehmen'>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=goldraus");
        }else{
            $amt=abs((int)$_POST[gold]);
            if ($amt>$row[gold]){
                output("`2So viel Gold ist nicht mehr da.");
            }else if ($maxtfer<$amt){
                output("`2Du darfst maximal `^$maxtfer`2 Gold auf einmal nehmen.");
            }else if ($amt<0){
                output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
            }else if($session[user][transferredtoday]>=getsetting("transferreceive",3)){
                output("`2Du hast heute schon genug Gold bekommen. Du wirst bis morgen warten müssen.");
            }else{
                $row[gold]-=$amt;
                $session[user][gold]+=$amt;
                $session[user][transferredtoday]+=1;
                $sql = "UPDATE wohnviertel SET gold=$row[gold] WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                output("`2Du hast `^$amt`2 Gold genommen. Insgesamt befindet sich jetzt noch `^$row[gold]`2 Gold im Haus.");
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `\$nimmt `^$amt`\$ Gold.')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }else if ($command=="goldrein"){
        $maxout = $session[user][level]*getsetting("maxtransferout",25);
        if (!$_POST[gold]){
            $transleft = $maxout - $session[user][amountouttoday];
            output("`2Du darfst heute noch `^$transleft`2 Gold deponieren.`n");
            output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=goldrein\" method='POST'>",true);
            output("`nWieviel Gold deponieren? <input type='gold' name='gold'>`n`n",true);
            output("<input type='submit' class='button' value='Deponieren'>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=goldrein");
        }else{
            $amt=abs((int)$_POST[gold]);
            if ($amt>$session[user][gold]){
                output("`2So viel Gold hast du nicht dabei.");
            }else if($row[gold]>=10000){
                output("`2Der Schatz ist voll.");
            }else if($amt>(10000-$row[gold])){
                output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
            }else if ($amt<0){
                output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
            }else if ($session[user][amountouttoday]+$amt > $maxout) {
                output("`2Du darfst nicht mehr als `^$maxout`2 Gold pro Tag deponieren.");
            }else{
                $row[gold]+=$amt;
                $session[user][gold]-=$amt;
                $session[user][amountouttoday]+= $amt;
                output("`2Du hast `^$amt`2 Gold deponiert. Insgesamt befinden sich jetzt `^$row[gold]`2 Gold im Haus.");
                $sql = "UPDATE wohnviertel SET gold=$row[gold] WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `@deponiert `^$amt`@ Gold.')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        addnav("Zurück","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="privat"){
        $owner=$_GET['owner'];
        $me=$session['user']['acctid'];
        if ($row['owner1']==$owner) $desc=$row[desc1];
        if ($row['owner2']==$owner) $desc=$row[desc2];
        if ($row['owner3']==$owner) $desc=$row[desc3];
        if ($row['owner4']==$owner) $desc=$row[desc4];
        if ($row['owner5']==$owner) $desc=$row[desc5];
        if ($row['owner6']==$owner) $desc=$row[desc6];
        if ($row['owner7']==$owner) $desc=$row[desc7];
        if ($row['owner8']==$owner) $desc=$row[desc8];
        if ($_GET['edition']=="desc"){
            if (!$_POST[newdesc]){
                output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me&edition=desc\" method='POST'>",true);
                output("`nWelche Beschreibung soll dein Privatraum zukünftig haben? <input type='newdesc' name='newdesc'>`n`n",true);
                output("<input type='submit' class='button' value='Beschreibung Ändern'>",true);
                addnav("","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me&edition=desc");
            }else{
                output("`2Du hast die Beschreibung deines Privatraums geändert.");
                if ($row['owner1']==$owner) $sql="UPDATE wohnviertel SET desc1='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner2']==$owner) $sql="UPDATE wohnviertel SET desc2='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner3']==$owner) $sql="UPDATE wohnviertel SET desc3='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner4']==$owner) $sql="UPDATE wohnviertel SET desc4='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner5']==$owner) $sql="UPDATE wohnviertel SET desc5='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner6']==$owner) $sql="UPDATE wohnviertel SET desc6='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner7']==$owner) $sql="UPDATE wohnviertel SET desc7='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                if ($row['owner8']==$owner) $sql="UPDATE wohnviertel SET desc8='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
            }
        }else{
            output($desc."`n`n");
            addcommentary();
            $owner2="Privatraum von ".$owner."";
            viewcommentary($owner2,"Hinzufügen",25);

            if ($owner==$me) addnav("Beschreibung ändern","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me&edition=desc");
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="schlafzimmer"){
        $owner=$_GET['owner'];
        output("`rDu betrittst das Schlafzimmer und siehst mit einem Blick nach oben einen wunderschönen Sternenhimmel der nur
        bei sehr genauer Betrachtung als Verziehrung der Decke zu erkennen ist. Schräg gegenüber befindet sich ein Kamin mit einem
        großen Bärenfellteppich genau davor und etwas weiter entfernt steht ein Prachtstück eines Bettes. Es bietet eine riesige
        Schlaffläche und ein goldenes Tuch das von der Decke wie ein Zelt über dem Bett hängt ziehrt es. Du weißt erst gar nicht
        was gemütlicher sein muss - das Bett oder der flauschige Teppich?`n`n`n");
        addcommentary();
        $ownerx="Schlafzimmer-".$owner."";
        viewcommentary($ownerx,"Hinzufügen",25);
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="description"){
        if (!$_POST[newdesc]){
            output("`2Das Ändern der Beschreibung kostet dich 1000 Goldstücke.`n");
            output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=description\" method='POST'>",true);
            output("`nWelche Beschreibung soll das Haus zukünftig haben? <input type='newdesc' name='newdesc'>`n`n",true);
            output("<input type='submit' class='button' value='Beschreibung Ändern'>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=description");
        }else{
            if (1000>$session[user][gold]){
                output("`2Du hast nicht genug Gold dabei.");
            }else{
                $session[user][gold]-=1000;
                output("`2Du hast für 1000 Goldstücke die Beschreibung geändert.");
                $sql = "UPDATE wohnviertel SET description='".$_POST[newdesc]."' WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `qändert die Beschreibung des Hauses')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="name"){
        if (!$_POST[newname]){
            output("`2Das Ändern des Namens kostet dich 5000 Goldstücke und 2 Edelsteine.`n");
            output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=name\" method='POST'>",true);
            output("`nWelchen Namen soll das Haus zukünftig haben? <input type='newname' name='newname' maxlength='25'>`n`n",true);
            output("<input type='submit' class='button' value='Namen Ändern'>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=name");
        }else{
            if (5000>$session[user][gold]){
                output("`2Du hast nicht genug Gold dabei.");
            }elseif (2>$session[user][gems]){
                output("`2Du hast nicht genug Edelsteine dabei.");
            }else{
                $session[user][gold]-=5000;
                $session[user][gems]-=2;
                output("`2Du hast für 5000 Goldstücke und 2 Edelsteine den Namen geändert.");
                $sql = "UPDATE wohnviertel SET housename='".$_POST[newname]."' WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `qändert den Namen des Hauses')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="verkaufen"){
        $entscheidung=$_GET['und'];
        $me=$session['user']['acctid'];
        if ($entscheidung=="japs"){
            if (!$_POST[newname]){
                output("`2Bitte gib uns eine kurze Begründung warum wir dem Verkauf des Hauses zustimmen sollten.`n");
                output("`2<form action=\"wohnviertel.php?op=hausdrin&id=$id&command=verkaufen&und=japs\" method='POST'>",true);
                output("`nBeg.: <input type='newname' name='newname' maxlength='225'>`n`n",true);
                output("<input type='submit' class='button' value='Verkaufen'>",true);
                addnav("","wohnviertel.php?op=hausdrin&id=$id&command=verkaufen&und=japs");
            }else{
                output("`2Dein Anliegen wird überprüft und gegebenfalls wird euch die Stadt Pandea 30 Edelsteine zurückzahlen. Ein Rundschreiben an alle Besitzer wurde rausgeschickt die nun die Möglichkeit haben den Antrag rückgängig zu machen. Ein 2ter Besitzer muss allerdings ebenfalls zustimmen!!");
                $sql = "UPDATE wohnviertel SET description='".$_POST[newname]."' WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql = "UPDATE wohnviertel SET status=3 WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql = "UPDATE wohnviertel SET housename='".$session['user']['login']."' WHERE houseid=$row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $text="`&{$session['user']['name']}`2 hat einen Antrag beim Bauamt abgegeben der den Verkauf des Hauses veranlässt! Die Begründung war: $_POST[newname].`2Du hast nicht lange Zeit bis dieser Antrag auch durchgeführt werden könnte. Bitte begib dich ins Bauamt um deine Entscheidung zu vermitteln. Für das Verkaufen ist eine weitere Stimme benötigt allerdings kannst du auch Beschwerde einreichen. Gezeichnet, PandeaIsland Wohnviertelaufsichtsbehörde";
                if ($row['owner1']!="0") systemmail($row['owner1'],"`4Hausverkauf!`0",$text);
                if ($row['owner2']!="0") systemmail($row['owner2'],"`4Hausverkauf!`0",$text);
                if ($row['owner3']!="0") systemmail($row['owner3'],"`4Hausverkauf!`0",$text);
                if ($row['owner4']!="0") systemmail($row['owner4'],"`4Hausverkauf!`0",$text);
                if ($row['owner5']!="0") systemmail($row['owner5'],"`4Hausverkauf!`0",$text);
                if ($row['owner6']!="0") systemmail($row['owner6'],"`4Hausverkauf!`0",$text);
                if ($row['owner7']!="0") systemmail($row['owner7'],"`4Hausverkauf!`0",$text);
                if ($row['owner8']!="0") systemmail($row['owner8'],"`4Hausverkauf!`0",$text);
                db_query($sql) or die(db_error(LINK));
            }
            addnav("Wohnviertel","wohnviertel.php");
        }else{
            output("Bist du sicher das du und deine Mitbewohner das Haus verkaufen möchtet?? Ihr würdet nur 30 Edelsteine pro Kopf wieder zurück bekommen und bedenkt: Die Bauplätze sind beschränkt auf Pandea. Wissen die anderen nichts von deinem Vorhaben, ist das nicht gerade die feine englische Art ...");
            addnav("Ja verkaufen!","wohnviertel.php?op=hausdrin&id=$id&command=verkaufen&und=japs");
            addnav("Nein","wohnviertel.php?op=hausdrin&id=$id");
        }
    }elseif ($command=="takekey"){
        $me=$session['user']['acctid'];
        $sqlkeys="SELECT * FROM items WHERE owner!=$me AND value1=$id AND value2=$me AND class='Schlüssel'";
        $resultkeys=db_query($sqlkeys);
        if (db_num_rows($resultkeys)==0){
            output("Es existiert kein einziger Schlüssel der du einziehen könntest.");
        }else{
            output("`nWelchen der Schlüssel möchtest du wieder haben?`n`n");
            output("<table border=1><tr><td><b>Hausnummer</b></td><td><b>Momentaner Besitzer</b></td><td><b>Aktion</b></td></tr>",true);
            for ($i=0;$i<db_num_rows($resultkeys);$i++){
                $rowkey = db_fetch_assoc($resultkeys);
                output("<tr><td>",true);
                output("$rowkey[value1]");
                output("</td><td>",true);
                $sqlexowner="SELECT * FROM accounts WHERE acctid=$rowkey[owner]";
                $resultexowner=db_query($sqlexowner);
                $rowexowner = db_fetch_assoc($resultexowner);
                output("$rowexowner[name]");
                output("</td><td>",true);
                output("<a href='wohnviertel.php?op=hausdrin&id=$id&command=takekeyfinal&key=$rowkey[id]'>Zurückfordern</a>",true);
                addnav("","wohnviertel.php?op=hausdrin&id=$id&command=takekeyfinal&key=$rowkey[id]");
                output("</td></tr>",true);
            }
            output("</table>",true);
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="takekeyfinal"){
        $key=$_GET['key'];
        $sql222="SELECT * FROM items WHERE id=$key";
        $result222=db_query($sql222);
        $row222 = db_fetch_assoc($result222);
        $exowner=$row222['owner'];
        $sqlrefresh="UPDATE items SET owner=$row222[value2] WHERE id=$key";
        db_query($sqlrefresh);
        $gold=$row['gold'];
        $count=0;
        if ($row['owner1']!="0") $count++;
        if ($row['owner2']!="0") $count++;
        if ($row['owner3']!="0") $count++;
        if ($row['owner4']!="0") $count++;
        if ($row['owner5']!="0") $count++;
        if ($row['owner6']!="0") $count++;
        if ($row['owner7']!="0") $count++;
        if ($row['owner8']!="0") $count++;
        $sqlcount="SELECT * FROM items WHERE value1=$id AND class='Schlüssel'";
        $resultcount=db_query($sqlcount);
        for ($i=0;$i<db_num_rows($resultcount);$i++);
        $countfinal=$count+$i;
        $goldgive=round($row[gold]/($countfinal));
        $sqljustiz="SELECT * FROM accounts WHERE acctid=$exowner";
        $resultjustiz=db_query($sqljustiz);
        $row555 = db_fetch_assoc($resultjustiz);
        $oldgold=$row555['gold'];
        $newgold=$oldgold+$goldgive;
        $sqljustiz="UPDATE accounts SET gold=$newgold WHERE acctid=$exowner";
        db_query($sqljustiz);
        $newgold=$row[gold]-$goldgive;
        $sqljustiz="UPDATE wohnviertel SET gold=$newgold WHERE houseid=$id";
        db_query($sqljustiz);
        $sqlcomment="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `4zieht einen der Schlüssel wieder für sich ein')";
        db_query($sqlcomment);
        output("Du hast den Schlüssel eingezogen. Der alte Besitzer des Schlüssels hat einen gerechten Anteil des Schatzes bekommen.");
        systemmail($row555[acctid],"`4Schlüssel verlangt!`0","`&{$session['user']['name']}`2 hat den Schlüssel zu Haus Nummer `b$row[houseid]`b ($row[housename]`2) wieder zurückverlangt. Du wurdest dafür mit einem Teil des Schatzes entschädigt!");
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="givekey"){
        $me=$session['user']['acctid'];
        $sqlkeys="SELECT * FROM items WHERE owner=$me AND value1=$id AND value2=$me AND class='Schlüssel'";
        $resultkeys=db_query($sqlkeys);
        if (db_num_rows($resultkeys)==0){
            output("Du hast keine weiteren Schlüssel mehr, die du noch vergeben kannst");
        }else{
            output("`nWelchen der Schlüssel möchtest du verschenken?`n`n");
            output("<table border=1><tr><td><b>Hausnummer</b></td><td><b>Aktion</b></td></tr>",true);
            for ($i=0;$i<db_num_rows($resultkeys);$i++){
                $rowkey = db_fetch_assoc($resultkeys);
                output("<tr><td>",true);
                output("$rowkey[value1]");
                output("</td><td>",true);
                output("<a href='wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$rowkey[id]'>Vergeben</a>",true);
                addnav("","wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$rowkey[id]");
                output("</td></tr>",true);
            }
            output("</table>",true);
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="givekeyfinal"){
        $key=$_GET['key'];
        if ($_GET['search']==1){
            if ($_GET['subfinal']==1){
                $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."'";
            }else{
                $contractname = stripslashes(rawurldecode($_POST['contractname']));
                $name="%";
                for ($x=0;$x<strlen($contractname);$x++){
                    $name.=substr($contractname,$x,1)."%";
                }
                $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."'";
            }
            $result = db_query($sql);
            if (db_num_rows($result) == 0) {
                output("Kein solcher User gefunden");
            } elseif(db_num_rows($result) > 100) {
                output("Zu viele User gefunden");
            } elseif(db_num_rows($result) > 1) {
                output("Bitte definier genauer:");
                output("<form action='wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1&subfinal=1' method='POST'>",true);
                output("`2Besitzer: <select name='contractname'>",true);
                for ($i=0;$i<db_num_rows($result);$i++){
                    $row = db_fetch_assoc($result);
                    output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
                }
                output("</select>`n`n",true);
                output("<input type='submit' class='button' value='Auswählen'></form>",true);
                addnav("","wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1&subfinal=1");
            } else {
                $row  = db_fetch_assoc($result);
                $sqlkeycheck="SELECT * FROM items WHERE owner=$row[acctid] AND class='Schlüssel' AND value2!=$row[acctid]";
                $resultkc = db_query($sqlkeycheck);
                if (db_num_rows($resultkc) != 0) {
                    output("Dieser User besitzt schon einen Schlüssel für ein Haus");
                }else{
                    if ($_GET['subfinal']==1){
                        $sql="UPDATE items SET owner=$row[acctid] WHERE id=$key";
                        db_query($sql);
                        output("Du übergibst den Schlüssel an seinen neuen Besitzer");
                        $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `qgibt $row[name] `qeinen Schlüssel')";
                        db_query($sql) or die(db_error(LINK));
                        systemmail($row[acctid],"`@Schlüssel erhalten!`0","`&{$session['user']['name']}`2 hat dir einen Schlüssel zu Haus Nummer `b$row[houseid]`b ($row[housename]`2) gegeben!");
                    }else{
                        output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                        output("<form action='wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1&subfinal=1' method='POST'>",true);
                        output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                        output("`2Besitzer: `^{$row['name']}`n`n");
                        output("<input type='submit' class='button' value='Auswählen'></form>",true);
                        addnav("","wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1&subfinal=1");
                    }
                }
            }
        }else{
            output("Wem möchtest du einen Schlüssel geben? (nicht möglich: Personen die schon für ein anderes Haus Schlüssel besitzen");
            output("<form action='wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1' method='POST'>",true);
            output("`2Neuer Schlüsselbesitzer: <input name='contractname'>`n", true);
            output("<input type='submit' class='button' value='Suchen'></form>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=givekeyfinal&key=$key&search=1");
        }
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="givebackkey"){
        $me=$session['user']['acctid'];
        $sqlkeys="SELECT * FROM items WHERE owner=$me AND value1=$id AND value2!=$me AND class='Schlüssel'";
        $resultkeys=db_query($sqlkeys);
        output("Du schaust dir deinen Schlüsselbund an und findest, dass du vielleicht den ein oder anderen Schlüssel
        gar nicht mehr gebrauchen kannst.");
        output("`nWelchen der Schlüssel willst du zurückgeben?`n`n");
        output("<table border=1><tr><td><b>Hausnummer</b></td><td><b>Erhalten von</b></td><td><b>Aktion</b></td></tr>",true);
        for ($i=0;$i<db_num_rows($resultkeys);$i++){
            $rowkey = db_fetch_assoc($resultkeys);
            output("<tr><td>",true);
            output("$rowkey[value1]");
            output("</td><td>",true);
            $sqlexowner="SELECT * FROM accounts WHERE acctid=$rowkey[value2]";
            $resultexowner=db_query($sqlexowner);
            $rowexowner = db_fetch_assoc($resultexowner);
            output("$rowexowner[name]");
            output("</td><td>",true);
            output("<a href='wohnviertel.php?op=hausdrin&id=$id&command=givebackkeyfinal&key=$rowkey[id]'>Zurückgeben</a>",true);
            addnav("","wohnviertel.php?op=hausdrin&id=$id&command=givebackkeyfinal&key=$rowkey[id]");
            output("</td></tr>",true);
        }
        output("</table>",true);
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="givebackkeyfinal"){
        $key=$_GET['key'];
        $sql222="SELECT * FROM items WHERE id=$key";
        $result222=db_query($sql222);
        $row222 = db_fetch_assoc($result222);
        $sqlrefresh="UPDATE items SET owner=$row222[value2] WHERE id=$key";
        db_query($sqlrefresh);
        $sqlcomment="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'haus-".$id."',".$session[user][acctid].",'/me `4gibt einen Schlüssel zurück')";
        db_query($sqlcomment);
        output("Du hast den Schlüssel an seinen alten Besitzer zurückgegeben");
        addnav("Zurück zum Haus","wohnviertel.php?op=hausdrin&id=$id");
    }elseif ($command=="logout"){
        debuglog("logged out in a house ");
        $sql = "UPDATE accounts SET loggedin=0,location=2,housekey=$id WHERE acctid = ".$session[user][acctid];
        db_query($sql);
        $session=array();
        redirect("index.php");
    }else{
        $owner1=0;
        $owner2=0;
        $owner3=0;
        $owner4=0;
        $owner5=0;
        $owner6=0;
        $owner7=0;
        $owner8=0;
        if ($row[owner1]!=0) $owner1=1;
        if ($row[owner2]!=0) $owner2=1;
        if ($row[owner3]!=0) $owner3=1;
        if ($row[owner4]!=0) $owner4=1;
        if ($row[owner5]!=0) $owner5=1;
        if ($row[owner6]!=0) $owner6=1;
        if ($row[owner7]!=0) $owner7=1;
        if ($row[owner8]!=0) $owner8=1;
        if ($owner1==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner1]."";
            $result=db_query($sql);
            $rowowner1 = db_fetch_assoc($result);
        }
        if ($owner2==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner2]."";
            $result=db_query($sql);
            $rowowner2 = db_fetch_assoc($result);
        }
        if ($owner3==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner3]."";
            $result=db_query($sql);
            $rowowner3 = db_fetch_assoc($result);
        }
        if ($owner4==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner4]."";
            $result=db_query($sql);
            $rowowner4 = db_fetch_assoc($result);
        }
        if ($owner5==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner5]."";
            $result=db_query($sql);
            $rowowner5 = db_fetch_assoc($result);
        }
        if ($owner6==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner6]."";
            $result=db_query($sql);
            $rowowner6 = db_fetch_assoc($result);
        }
        if ($owner7==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner7]."";
            $result=db_query($sql);
            $rowowner7 = db_fetch_assoc($result);
        }
        if ($owner8==1){
            $sql="SELECT * FROM accounts WHERE acctid=".$row[owner8]."";
            $result=db_query($sql);
            $rowowner8 = db_fetch_assoc($result);
        }
        output("`c");
        output($row['description']);
        output("`c");
        output("`n`n`@Momentan befinden sich `^$row[gold] `@Goldstücke in der Schatztruhe");
        output("`n`nDu hörst deine Mitbewohner reden:`n`n");
        addcommentary();
        $haus="haus-".$id."";
        viewcommentary($haus,"Hinzufügen",25);
        output("`@`b`n`nDieses Haus können betreten:`n");
        output("`^Die Besitzer:`b`0`n");
        if ($owner1==1){
            output($rowowner1['name']);
            output("`0`n");
        }
        if ($owner2==1){
            output($rowowner2['name']);
            output("`0`n");
        }
        if ($owner3==1){
            output($rowowner3['name']);
            output("`0`n");
        }
        if ($owner4==1){
            output($rowowner4['name']);
            output("`0`n");
        }
        if ($owner5==1){
            output($rowowner5['name']);
            output("`0`n");
        }
        if ($owner6==1){
            output($rowowner6['name']);
            output("`0`n");
        }
        if ($owner7==1){
            output($rowowner7['name']);
            output("`0`n");
        }
        if ($owner8==1){
            output($rowowner8['name']);
            output("`0`n");
        }
        output("`b`n`^Die Schlüsselinhaber:`b`0`n");
        $sql="SELECT * FROM items WHERE value1=$id";
        $resultkeys = db_query($sql) or die(db_error(LINK));
        for ($i=0;$i<db_num_rows($resultkeys);$i++){
            $rowkey = db_fetch_assoc($resultkeys);
            if ($rowkey['owner']!="0"){
                $sqlowner="SELECT * FROM accounts WHERE acctid=$rowkey[owner]";
                $resultowner = db_query($sqlowner) or die(db_error(LINK));
                $rowowner = db_fetch_assoc($resultowner);
                output($rowowner['name']);
                output("`0`n");
            }
        }
        addnav("Gold");
        addnav("Schatztruhe füllen","wohnviertel.php?op=hausdrin&id=$id&command=goldrein");
        addnav("Schatztruhe leeren","wohnviertel.php?op=hausdrin&id=$id&command=goldraus");
        $privat=0;
        if ($owner1==1){
            if ($session['user']['name']==$rowowner1['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner1['acctid'];
                $ownername=$rowowner1['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner2==1){
            if ($session['user']['name']==$rowowner2['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner2['acctid'];
                $ownername=$rowowner2['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner3==1){
            if ($session['user']['name']==$rowowner3['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner3['acctid'];
                $ownername=$rowowner3['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner4==1){
            if ($session['user']['name']==$rowowner4['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner4['acctid'];
                $ownername=$rowowner4['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner5==1){
            if ($session['user']['name']==$rowowner5['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner5['acctid'];
                $ownername=$rowowner5['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner6==1){
            if ($session['user']['name']==$rowowner6['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner6['acctid'];
                $ownername=$rowowner6['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner7==1){
            if ($session['user']['name']==$rowowner7['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner7['acctid'];
                $ownername=$rowowner7['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        if ($owner8==1){
            if ($session['user']['name']==$rowowner8['name']){
                if ($privat==0){
                    addnav("Privaträume");
                    $privat==1;
                }
                $me=$session['user']['acctid'];
                addnav("Dein Privatraum","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$me");
                if ($session['user']['marriedto']!=0) addnav("Schlafzimmer","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$me");
            }else{
                $me=$session['user']['acctid'];
                $owner=$rowowner8['acctid'];
                $ownername=$rowowner8['name'];
                $sql="SELECT * FROM items WHERE owner=$me AND value2=$owner AND class='Schlüssel'";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)!=0){
                    addnav("Privatraum von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=privat&owner=$owner");
                    if ($session['user']['marriedto']==$owner) addnav("Schlafzimmer von $ownername","wohnviertel.php?op=hausdrin&id=$id&command=schlafzimmer&owner=$owner");
                }
            }
        }
        $drin=="0";
        if ($me==$row['owner1']) $drin="1";
        if ($me==$row['owner2'] && $drin=="0") $drin="1";
        if ($me==$row['owner3'] && $drin=="0") $drin="1";
        if ($me==$row['owner4'] && $drin=="0") $drin="1";
        if ($me==$row['owner5'] && $drin=="0") $drin="1";
        if ($me==$row['owner6'] && $drin=="0") $drin="1";
        if ($me==$row['owner7'] && $drin=="0") $drin="1";
        if ($me==$row['owner8'] && $drin=="0") $drin="1";
        if ($drin=="1"){
            addnav("Allg. Einstellungen");
            addnav("Beschreibung ändern","wohnviertel.php?op=hausdrin&id=$id&command=description");
            addnav("Hausnamen ändern","wohnviertel.php?op=hausdrin&id=$id&command=name");
            addnav("Haus verkaufen","wohnviertel.php?op=hausdrin&id=$id&command=verkaufen");
        }
        addnav("Priv. Einstellungen");
        if ($drin=="1"){
            addnav("Schlüssel vergeben","wohnviertel.php?op=hausdrin&id=$id&command=givekey");
            addnav("Schlüssel zurückfordern","wohnviertel.php?op=hausdrin&id=$id&command=takekey");
        }
        addnav("Schlüssel zurückgeben","wohnviertel.php?op=hausdrin&id=$id&command=givebackkey");
        addnav("Sonstiges");
        addnav("LogOut","wohnviertel.php?op=hausdrin&id=$id&command=logout");
        addnav("Zurück","wohnviertel.php?op=haus&id=$id");
    }
}elseif ($_GET['op']=="aktenzeugs"){
    $command=$_GET['command'];
    if ($command=="buildready"){
        $sql="SELECT * FROM wohnviertel WHERE ".houseid." = ".$session[user][house]."";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $ok=9;
        if ($row[owner1]!=0){
            if ($row[gems1]==0){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner2]!=0){
            if ($row[gems2]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner3]!=0){
            if ($row[gems3]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner4]!=0){
            if ($row[gems4]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner5]!=0){
            if ($row[gems5]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner6]!=0){
            if ($row[gems6]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner7]!=0){
            if ($row[gems7]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($row[owner8]!=0){
            if ($row[gems8]==0 && $ok==9){
                $ok=9;
            }else{
                $ok=0;
            }
        }
        if ($ok==0){
            output("Tut mir leid aber dein Haus ist noch nicht fertig abbezahlt.");
        }else{
            output("Dein Haus ist fertig abbezahlt und wurde nun als bewohnbar von einem königlichen Bediensteten abgesegnet");
            $sql="UPDATE wohnviertel SET status=2 WHERE ".houseid." = ".$session[user][house]."";
            db_query($sql);
            for ($i=0;$i<getwvs("keys",3);$i++){
                if ($row[owner1]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner1],'Schlüssel',$row[houseid],$row[owner1],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner2]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner2],'Schlüssel',$row[houseid],$row[owner2],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner3]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner3],'Schlüssel',$row[houseid],$row[owner3],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner4]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner4],'Schlüssel',$row[houseid],$row[owner4],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner5]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner5],'Schlüssel',$row[houseid],$row[owner5],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner6]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner6],'Schlüssel',$row[houseid],$row[owner6],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner7]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner7],'Schlüssel',$row[houseid],$row[owner7],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
                if ($row[owner8]!=0){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',$row[owner8],'Schlüssel',$row[houseid],$row[owner8],0,0,'Schlüssel für Haus Nummer $row[houseid]')";
                    db_query($sql);
                }
            }

        }
        addnav("Wohnviertel","wohnviertel.php");
    }elseif ($command=="builderror"){
        if ($_GET['subop']=="doit"){
            $sql="UPDATE wohnviertel SET status=4 WHERE ".houseid." = ".$session[user][house]."";
            db_query($sql);
            $sql="SELECT * FROM wohnviertel WHERE ".houseid." = ".$session[user][house]."";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $haus=$session['user']['house'];
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner1]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner2]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner3]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner4]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner5]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner6]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner7]";
            db_query($sql2);
            $sql2="UPDATE accounts SET house=0 WHERE acctid=$row[owner8]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner1]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner2]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner3]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner4]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner5]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner6]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner7]";
            db_query($sql2);
            $sql2="DELETE FROM items WHERE value2=$row[owner8]";
            db_query($sql2);
            $sql="UPDATE wohnviertel SET owner1=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner2=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner3=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner4=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner5=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner6=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner7=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET owner8=0 WHERE ".houseid." = ".$haus."";
            db_query($sql);
            $sql="UPDATE wohnviertel SET housename='`xBaustelle' WHERE ".houseid." = ".$haus."";
            db_query($sql);
            output("Dein Haus wurde erfolgreich abgerissen und der Bauschutt steht als Ruine auf dem Grundstück");
        }else{
            output("Wenn du den Bau als Ruine melden lässt wird das Haus abgerissen und Bezahltes als Abrissprovision genommen!");
            output("`nVerwende diese Funktion also nur wenn einer der Bauteilnehmer Pandea Island verlässt/verlassen hat, und somit die Baukosten von niemanden mehr voll bezahlt werden können!");
            addnav("Abriss","wohnviertel.php?op=aktenzeugs&command=builderror&subop=doit");
        }
        addnav("Bauamt verlassen","wohnviertel.php");
    }elseif ($command=="verkaufen"){
        $hid=$session['user']['house'];
        $verkauf="Zum Verkauf";
        $sql11="UPDATE wohnviertel SET housename='".$verkauf."' WHERE houseid='".$hid."'";
        db_query($sql11);
        $sql12="UPDATE wohnviertel SET description='".$verkauf."' WHERE houseid='".$hid."'";
        db_query($sql12);
        $sql111="SELECT * FROM accounts WHERE house='".$hid."' AND acctid='".$session['user']['acctid']."'";
        $result111=db_query($sql111);
        for ($i=0;$i<db_num_rows($result111);$i++){
            $row111 = db_fetch_assoc($result111);
            $newgems=$row111['gems']+30;
            $sql1111="UPDATE accounts SET gems=$newgems, house=0, housekey=0 WHERE login='".$row111['login']."'";
            db_query($sql1111);
        }
        $sql13="UPDATE wohnviertel SET owner1=0 WHERE houseid='".$hid."'";
        db_query($sql13);
        $sql14="UPDATE wohnviertel SET owner2=0 WHERE houseid='".$hid."'";
        db_query($sql14);
        $sql15="UPDATE wohnviertel SET owner3=0 WHERE houseid='".$hid."'";
        db_query($sql15);
        $sql16="UPDATE wohnviertel SET owner4=0 WHERE houseid='".$hid."'";
        db_query($sql16);
        $sql17="UPDATE wohnviertel SET owner5=0 WHERE houseid='".$hid."'";
        db_query($sql17);
        $sql18="UPDATE wohnviertel SET owner6=0 WHERE houseid='".$hid."'";
        db_query($sql18);
        $sql19="UPDATE wohnviertel SET owner7=0 WHERE houseid='".$hid."'";
        db_query($sql19);
        $sql20="UPDATE wohnviertel SET owner8=0 WHERE houseid='".$hid."'";
        db_query($sql20);
        $sql29="UPDATE wohnviertel SET status=4 WHERE houseid='".$hid."'";
        db_query($sql29);
        $sql30="UPDATE wohnviertel SET gems1=50 WHERE houseid='".$hid."'";
        db_query($sql30);
        $sql31="UPDATE wohnviertel SET gems2=50 WHERE houseid='".$hid."'";
        db_query($sql31);
        $sql32="UPDATE wohnviertel SET gems3=50 WHERE houseid='".$hid."'";
        db_query($sql32);
        $sql33="UPDATE wohnviertel SET gems4=50 WHERE houseid='".$hid."'";
        db_query($sql33);
        $sql34="UPDATE wohnviertel SET gems5=50 WHERE houseid='".$hid."'";
        db_query($sql34);
        $sql35="UPDATE wohnviertel SET gems6=50 WHERE houseid='".$hid."'";
        db_query($sql35);
        $sql36="UPDATE wohnviertel SET gems7=50 WHERE houseid='".$hid."'";
        db_query($sql36);
        $sql37="UPDATE wohnviertel SET gems8=50 WHERE houseid='".$hid."'";
        db_query($sql37);
        $sql38="UPDATE wohnviertel SET gold=0 WHERE houseid='".$hid."'";
        db_query($sql38);
        $sql39="DELETE FROM items WHERE value1='".$hid."' AND class='Schlüssel'";
        addnav("Dorfplatz","village.php");
    }
}elseif ($_GET['op']=="bauen"){
    if ($session['user']['house']!="" && $session['user']['house']!="0"){
        $sql="SELECT * FROM wohnviertel WHERE ".houseid." = ".$session[user][house]."";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($_GET['subop']=="antihaus"){
            if ($session['user']['login']==$row['housename']){
                output("DU hast doch den Hausbau veranlasst. Geb den anderen Besitzern eine Chance mitzubestimmen");
            }else{
                $text="Das Haus wurde verkauft. Als ehemaliger Eigentümer wurden dir 30 Edelsteine gutgeschrieben";
                if ($row['owner1']!="0") systemmail($row['owner1'],"`4Hausverkauf!`0",$text);
                if ($row['owner2']!="0") systemmail($row['owner2'],"`4Hausverkauf!`0",$text);
                if ($row['owner3']!="0") systemmail($row['owner3'],"`4Hausverkauf!`0",$text);
                if ($row['owner4']!="0") systemmail($row['owner4'],"`4Hausverkauf!`0",$text);
                if ($row['owner5']!="0") systemmail($row['owner5'],"`4Hausverkauf!`0",$text);
                if ($row['owner6']!="0") systemmail($row['owner6'],"`4Hausverkauf!`0",$text);
                if ($row['owner7']!="0") systemmail($row['owner7'],"`4Hausverkauf!`0",$text);
                if ($row['owner8']!="0") systemmail($row['owner8'],"`4Hausverkauf!`0",$text);
                $session['user']['gems']=$session['user']['gems']+30;
                redirect("wohnviertel.php?op=aktenzeugs&command=verkaufen");
            }
        }
        if ($_GET['subop']=="beschwerde"){
            if (!$_POST[newname]){
                output("`2Bitte gib hier deine Beschwerde zu diesem Fall ab.`n");
                output("`2<form action=\"wohnviertel.php?op=bauen&subop=beschwerde\" method='POST'>",true);
                output("`nDeine Beschwerde (max 255.) <input type='newname' name='newname' maxlength='255'>`n`n",true);
                output("<input type='submit' class='button' value='Namen Ändern'>",true);
                addnav("","wohnviertel.php?op=bauen&subop=beschwerde");
            }else{
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'zuverkaufendehaueser',".$session[user][acctid].",'/me hat eine Beschwerde aufgrund von Hausnummer $row[houseid]: ".$_POST[newname]."')";
                db_query($sql) or die(db_error(LINK));
            }
        }else{
            if ($row[status]==2){
                output("Dein Haus steht doch schon. Also was willst du noch hier?");
            }
            if ($row[status]==3){
                output("Es liegt ein Antrag vor der den Verkauf des Hauses mit der Nummer $row[houseid] veranlässt.");
                addnav("Veto einlegen","wohnviertel.php?op=bauen&subop=beschwerde");
                addnav("Verkauf zustimmen","wohnviertel.php?op=bauen&subop=antihaus");
            }else{
                output("Dein Haus ist noch im Baustatus");
                addnav("Haus als fertig gebaut melden","wohnviertel.php?op=aktenzeugs&command=buildready");
                addnav("Bau als Ruine melden (Bau aufgeben)","wohnviertel.php?op=aktenzeugs&command=builderror");
            }
        }
    }else{
        $sql="SELECT houseid,housename,status FROM wohnviertel WHERE status!=0";
        $result = db_query($sql);
        $sql2="SELECT * FROM wohnviertel WHERE status > 4";
        $result2 = db_query($sql2);
        if (db_num_rows($result)>=getwvs("houses",120) && db_num_rows($result2) == 0) {
            output("`4Ein Beamter weißt dich höflich darauf hin das alle Bauplätze vergeben sind");
        }else{
            output("`$Hier sollte vielleicht noch ein Text rein aber mir fällt keiner ein");
            $min=getwvs("minowner",4);
            $max=getwvs("maxowner",8);
            $me=$session['user']['name'];
            output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
            output("`GBesitzer 1: `&$me`n",true);
            if ($min<2){
                output("<a href='wohnviertel.php?op=bauenfinal&zwei=0&drei=0&vier=0&funf=0&sechs=0&sieben=0&acht=0'>Fertig</a><br>",true);
                addnav("","wohnviertel.php?op=bauenfinal&zwei=0&drei=0&vier=0&funf=0&sechs=0&sieben=0&acht=0");
            }
            if ($min>1 || $max>1){
                output("<a href='wohnviertel.php?op=bauen2'>Weiteren Besitzer eintragen</a>`n",true);
                addnav("","wohnviertel.php?op=bauen2");
            }
        }
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen2"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    output("`GBesitzer 1: `&$me`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen2&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen2&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 2:`& $row[name]`n");
                    if ($min<3){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$row[acctid]&drei=0&vier=0&funf=0&sechs=0&sieben=0&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$row[acctid]&drei=0&vier=0&funf=0&sechs=0&sieben=0&acht=0");
                    }
                    if ($min>2 || $max>2){
                        output("<a href='wohnviertel.php?op=bauen3&zwei=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen3&zwei=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen2&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen2&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen2&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen2&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen3"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen3&zwei=$zwei&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen3&zwei=$zwei&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 3: $row[name]`n");
                    if ($min<4){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$row[acctid]&vier=0&funf=0&sechs=0&sieben=0&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$row[acctid]&vier=0&funf=0&sechs=0&sieben=0&acht=0");
                    }
                    if ($min>3 || $max>3){
                        output("<a href='wohnviertel.php?op=bauen4&zwei=$zwei&drei=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen4&zwei=$zwei&drei=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen3&zwei=$zwei&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen3&zwei=$zwei&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen3&zwei=$zwei&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen3&zwei=$zwei&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen4"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $drei=$_GET['drei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen4&zwei=$zwei&drei=$drei&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen4&zwei=$zwei&drei=$drei&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 4: $row[name]`n");
                    if ($min<5){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$row[acctid]&funf=0&sechs=0&sieben=0&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$row[acctid]&funf=0&sechs=0&sieben=0&acht=0");
                    }
                    if ($min>4 || $max>4){
                        output("<a href='wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen4&zwei=$zwei&drei=drei&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen4&zwei=$zwei&drei=drei&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen4&zwei=$zwei&drei=$drei&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen4&zwei=$zwei&drei=$drei&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen5"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $drei=$_GET['drei'];
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    $vier=$_GET['vier'];
    $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
    $resultvier = db_query($sqlvier);
    $rowvier = db_fetch_assoc($resultvier);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    output("`GBesitzer 4: `&$rowvier[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 5: $row[name]`n");
                    if ($min<6){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$row[acctid]&sechs=0&sieben=0&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$row[acctid]&sechs=0&sieben=0&acht=0");
                    }
                    if ($min>5 || $max>5){
                        output("<a href='wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen5&zwei=$zwei&drei=$drei&vier=$vier&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen6"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $drei=$_GET['drei'];
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    $vier=$_GET['vier'];
    $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
    $resultvier = db_query($sqlvier);
    $rowvier = db_fetch_assoc($resultvier);
    $funf=$_GET['funf'];
    $sqlfunf="SELECT * FROM accounts WHERE acctid=$funf";
    $resultfunf = db_query($sqlfunf);
    $rowfunf = db_fetch_assoc($resultfunf);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    output("`GBesitzer 4: `&$rowvier[name]`n");
    output("`GBesitzer 5: `&$rowfunf[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 6: $row[name]`n");
                    if ($min<7){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$row[acctid]&sieben=0&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$row[acctid]&sieben=0&acht=0");
                    }
                    if ($min>6 || $max>6){
                        output("<a href='wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen6&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen7"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $drei=$_GET['drei'];
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    $vier=$_GET['vier'];
    $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
    $resultvier = db_query($sqlvier);
    $rowvier = db_fetch_assoc($resultvier);
    $funf=$_GET['funf'];
    $sqlfunf="SELECT * FROM accounts WHERE acctid=$funf";
    $resultfunf = db_query($sqlfunf);
    $rowfunf = db_fetch_assoc($resultfunf);
    $sechs=$_GET['sechs'];
    $sqlsechs="SELECT * FROM accounts WHERE acctid=$sechs";
    $resultsechs = db_query($sqlsechs);
    $rowsechs = db_fetch_assoc($resultsechs);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    output("`GBesitzer 4: `&$rowvier[name]`n");
    output("`GBesitzer 5: `&$rowfunf[name]`n");
    output("`GBesitzer 6: `&$rowsechs[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."' AND name!='".$rowsechs[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."' AND name!='".$rowsechs[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 7: $row[name]`n");
                    if ($min<8){
                        output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$row[acctid]&acht=0'>Fertig</a><br>",true);
                        addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$row[acctid]&acht=0");
                    }
                    if ($min>7 || $max>7){
                        output("<a href='wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$row[acctid]'>Weiteren Besitzer eintragen</a>`n",true);
                        addnav("","wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$row[acctid]");
                    }
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen7&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauen8"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $drei=$_GET['drei'];
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    $vier=$_GET['vier'];
    $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
    $resultvier = db_query($sqlvier);
    $rowvier = db_fetch_assoc($resultvier);
    $funf=$_GET['funf'];
    $sqlfunf="SELECT * FROM accounts WHERE acctid=$funf";
    $resultfunf = db_query($sqlfunf);
    $rowfunf = db_fetch_assoc($resultfunf);
    $sechs=$_GET['sechs'];
    $sqlsechs="SELECT * FROM accounts WHERE acctid=$sechs";
    $resultsechs = db_query($sqlsechs);
    $rowsechs = db_fetch_assoc($resultsechs);
    $sieben=$_GET['sieben'];
    $sqlsieben="SELECT * FROM accounts WHERE acctid=$sieben";
    $resultsieben = db_query($sqlsieben);
    $rowsieben = db_fetch_assoc($resultsieben);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    output("`GBesitzer 4: `&$rowvier[name]`n");
    output("`GBesitzer 5: `&$rowfunf[name]`n");
    output("`GBesitzer 6: `&$rowsechs[name]`n");
    output("`GBesitzer 7: `&$rowsieben[name]`n");
    $min=getwvs("minowner",4);
    $max=getwvs("maxowner",8);
    if ($_GET['search']==1){
        if ($_GET['subfinal']==1){
            $sql = "SELECT * FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."' AND name!='".$rowsechs[name]."' AND name!='".$rowsieben[name]."'";
        }else{
            $contractname = stripslashes(rawurldecode($_POST['contractname']));
            $name="%";
            for ($x=0;$x<strlen($contractname);$x++){
                $name.=substr($contractname,$x,1)."%";
            }
            $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($name)."' AND name!='".$me."' AND name!='".$rowzwei[name]."' AND name!='".$rowdrei[name]."' AND name!='".$rowvier[name]."' AND name!='".$rowfunf[name]."' AND name!='".$rowsechs[name]."' AND name!='".$rowsieben[name]."'";
        }
        $result = db_query($sql);
        if (db_num_rows($result) == 0) {
            output("Kein solcher User gefunden");
        } elseif(db_num_rows($result) > 100) {
            output("Zu viele User gefunden");
        } elseif(db_num_rows($result) > 1) {
            output("Bitte definier genauer:");
            output("<form action='wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1&subfinal=1' method='POST'>",true);
            output("`2Besitzer: <select name='contractname'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("","wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1&subfinal=1");
        } else {
            $row  = db_fetch_assoc($result);
            if ($row['house']!="" && $row['house']!="0"){
                output("Dieser User hat schon ein Haus");
            }else{
                if ($_GET['subfinal']==1){
                    output("`GBesitzer 8: $row[name]`n");
                    output("<a href='wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&acht=$row[acctid]'>Fertig</a><br>",true);
                    addnav("","wohnviertel.php?op=bauenfinal&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&acht=$row[acctid]");
                }else{
                    output("Da ist eine Person, die du meinen könntest. Ist es die richtige?`n");
                    output("<form action='wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1&subfinal=1' method='POST'>",true);
                    output("<input type='hidden' name='contractname' value='".rawurlencode($row['name'])."'>",true);
                    output("`2Besitzer: `^{$row['name']}`n`n");
                    output("<input type='submit' class='button' value='Auswählen'></form>",true);
                    addnav("","wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1&subfinal=1");
                }
            }
        }
    }else{
        output("<form action='wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1' method='POST'>",true);
        output("`2Neuer Owner: <input name='contractname'>`n", true);
        output("<input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","wohnviertel.php?op=bauen8&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&search=1");
    }
    addnav("Zurück ins Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauenfinal"){
    output("`@`cStaatlicher Antrag auf Bauland im innerstädtischen Bereich`c`n");
    $me=$session['user']['name'];
    $zwei=$_GET['zwei'];
    $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
    $resultzwei = db_query($sqlzwei);
    $rowzwei = db_fetch_assoc($resultzwei);
    $drei=$_GET['drei'];
    $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
    $resultdrei = db_query($sqldrei);
    $rowdrei = db_fetch_assoc($resultdrei);
    $vier=$_GET['vier'];
    $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
    $resultvier = db_query($sqlvier);
    $rowvier = db_fetch_assoc($resultvier);
    $funf=$_GET['funf'];
    $sqlfunf="SELECT * FROM accounts WHERE acctid=$funf";
    $resultfunf = db_query($sqlfunf);
    $rowfunf = db_fetch_assoc($resultfunf);
    $sechs=$_GET['sechs'];
    $sqlsechs="SELECT * FROM accounts WHERE acctid=$sechs";
    $resultsechs = db_query($sqlsechs);
    $rowsechs = db_fetch_assoc($resultsechs);
    $sieben=$_GET['sieben'];
    $sqlsieben="SELECT * FROM accounts WHERE acctid=$sieben";
    $resultsieben = db_query($sqlsieben);
    $rowsieben = db_fetch_assoc($resultsieben);
    $acht=$_GET['acht'];
    $sqlacht="SELECT * FROM accounts WHERE acctid=$acht";
    $resultacht = db_query($sqlacht);
    $rowacht = db_fetch_assoc($resultacht);
    output("`GBesitzer 1: `&$me`n");
    output("`GBesitzer 2: `&$rowzwei[name]`n");
    output("`GBesitzer 3: `&$rowdrei[name]`n");
    output("`GBesitzer 4: `&$rowvier[name]`n");
    output("`GBesitzer 5: `&$rowfunf[name]`n");
    output("`GBesitzer 6: `&$rowsechs[name]`n");
    output("`GBesitzer 7: `&$rowsieben[name]`n");
    output("`GBesitzer 8: `&$rowacht[name]`n`n");
    output("<form action='wohnviertel.php?op=bauenfinal2&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&acht=$acht' method='POST'>",true);
    output("`2Hausname: <input name='hausname'>`n",true);
    output("`2Begründung warum gerade ihr ein Haus verdient habt: <input name='description'> `n`n",true);
    output("<input type='submit' class='button' value='Antrag abgeben'></form>",true);
    addnav("","wohnviertel.php?op=bauenfinal2&zwei=$zwei&drei=$drei&vier=$vier&funf=$funf&sechs=$sechs&sieben=$sieben&acht=$acht");
    addnav("Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']=="bauenfinal2"){
    output("`@Dein Antrag wurde erfolgreich eingetragen und wird demnächst bearbeitet");
    $me=$session['user']['acctid'];
    $zwei=$_GET['zwei'];
    $drei=$_GET['drei'];
    $vier=$_GET['vier'];
    $funf=$_GET['funf'];
    $sechs=$_GET['sechs'];
    $sieben=$_GET['sieben'];
    $acht=$_GET['acht'];
    $hausname=$_POST['hausname'];
    $description=$_POST['description'];
    $sqlx="SELECT houseid,housename,status FROM wohnviertel WHERE status!=0";
    $resultx = db_query($sqlx);
    $sqly="SELECT * FROM wohnviertel WHERE status > 4";
    $resulty = db_query($sqly);
    $maximale=getwvs("houses",120);
    $weiter=1;
    if (db_num_rows($resultx) >= $maximale && db_num_rows($resulty) == 0) {
        output("Tut mir leid aber da war wohl jemand schneller als du. Alle Bauplätze sind nun vergeben!");
        $weiter=0;
    }else if (db_num_rows($resultx) >= $maximale && db_num_rows($resulty) != 0) {
        $rowy = db_fetch_assoc($resulty);
        $id = $rowy['houseid'];
        $rowyweg = "DELETE FROM wohnviertel WHERE houseid = $id";
        db_query($rowyweg);
    }else if (db_num_rows($resultx) < $maximale){
        $sql2 = 'SELECT * FROM wohnviertel ORDER BY `houseid` DESC LIMIT 0 , 1';
        $result = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result);
        $id = $row2['houseid'];
        $id++;
    }
    if ($weiter==1){
        $sqltest = 'SELECT * FROM wohnviertel WHERE `houseid` = '.$id.'';
        $resulttest = db_query($sqltest);
        $gems1=80;
        $gems2=80;
        $gems3=80;
        $gems4=80;
        $gems5=80;
        $gems6=80;
        $gems7=80;
        $gems8=80;
        if ($session['user']['gems']>100) $gems1=e_rand(80,($session['user']['gems']-10));
        $sqlzwei="SELECT * FROM accounts WHERE acctid=$zwei";
        $resultzwei = db_query($sqlzwei);
        $rowzwei = db_fetch_assoc($resultzwei);
        $sqldrei="SELECT * FROM accounts WHERE acctid=$drei";
        $resultdrei = db_query($sqldrei);
        $rowdrei = db_fetch_assoc($resultdrei);
        $sqlvier="SELECT * FROM accounts WHERE acctid=$vier";
        $resultvier = db_query($sqlvier);
        $rowvier = db_fetch_assoc($resultvier);
        $sqlfunf="SELECT * FROM accounts WHERE acctid=$funf";
        $resultfunf = db_query($sqlfunf);
        $rowfunf = db_fetch_assoc($resultfunf);
        $sqlsechs="SELECT * FROM accounts WHERE acctid=$sechs";
        $resultsechs = db_query($sqlsechs);
        $rowsechs = db_fetch_assoc($resultsechs);
        $sqlsieben="SELECT * FROM accounts WHERE acctid=$sieben";
        $resultsieben = db_query($sqlsieben);
        $rowsieben = db_fetch_assoc($resultsieben);
        $sqlacht="SELECT * FROM accounts WHERE acctid=$acht";
        $resultacht = db_query($sqlacht);
        $rowacht = db_fetch_assoc($resultacht);
        if ($rowzwei['gems']>100) $gems2=e_rand(80,($rowzwei['gems']-10));
        if ($rowdrei['gems']>100) $gems3=e_rand(80,($rowdrei['gems']-10));
        if ($rowvier['gems']>100) $gems4=e_rand(80,($rowvier['gems']-10));
        if ($rowfunf['gems']>100) $gems5=e_rand(80,($rowfunf['gems']-10));
        if ($rowsechs['gems']>100) $gems6=e_rand(80,($rowsechs['gems']-10));
        if ($rowsieben['gems']>100) $gems7=e_rand(80,($rowsieben['gems']-10));
        if ($rowacht['gems']>100) $gems8=e_rand(80,($rowacht['gems']-10));
        if (db_num_rows($resulttest) == 0){
            $sql = 'INSERT INTO `wohnviertel` ( `houseid` , `status` , `gold` , `housename` , `description` , `owner1` , `owner2` , `owner3` , `owner4` , `owner5` , `owner6` , `owner7` , `owner8` , `gems1` , `gems2` , `gems3` , `gems4` , `gems5` , `gems6` , `gems7` , `gems8` , `desc1` , `desc2` , `desc3` , `desc4` , `desc5` , `desc6` , `desc7` , `desc8` ) ';
            $sql .= 'VALUES ( '.$id.', \'0\', \'0\', \''.$hausname.'\', \''.$description.'\', \''.$me.'\', \''.$zwei.'\', \''.$drei.'\', \''.$vier.'\', \''.$funf.'\', \''.$sechs.'\', \''.$sieben.'\', \''.$acht.'\', \''.$gems1.'\', \''.$gems2.'\', \''.$gems3.'\', \''.$gems4.'\', \''.$gems5.'\', \''.$gems6.'\', \''.$gems7.'\', \''.$gems8.'\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\' );';
            $sql .= '';
            db_query($sql);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$zwei."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$drei."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$vier."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$funf."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$sechs."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$sieben."";
            db_query($sqltt);
            $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$acht."";
            db_query($sqltt);
            $session['user']['house']=$id;
        }else{
            $rowtest = db_fetch_assoc($resulttest);
            if ($rowtest['status']!="3" || $rowtest['status']!="4"){
                output("Ups - da ist uns wohl ein kleiner Fehler unterlaufen. Bitte versuche dich erneut für einen Hausbau zu melden");
            }else{
                $sqldelete='DELETE * FROM `items` WHERE `value1`='.$id.'';
                db_query($sqldelete);
                $sqldelete='DELETE * FROM `wohnviertel` WHERE WHERE `houseid` = '.$id.'';
                db_query($sqldelete);
                $sql = 'INSERT INTO `wohnviertel` ( `houseid` , `status` , `desc` , `housename` , `description` , `owner1` , `owner2` , `owner3` , `owner4` , `owner5` , `owner6` , `owner7` , `owner8` , `gems1` , `gems2` , `gems3` , `gems4` , `gems5` , `gems6` , `gems7` , `gems8` , `desc1` , `desc2` , `desc3` , `desc4` , `desc5` , `desc6` , `desc7` , `desc8` ) ';
                $sql .= 'VALUES ( '.$id.', \'0\', \'0\', \''.$hausname.'\', \''.$description.'\', \''.$me.'\', \''.$zwei.'\', \''.$drei.'\', \''.$vier.'\', \''.$funf.'\', \''.$sechs.'\', \''.$sieben.'\', \''.$acht.'\', \'70\', \'70\', \'70\', \'70\', \'70\', \'70\', \'70\', \'70\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\', \'keine Beschreibung vorhanden\' );';
                $sql .= '';
                db_query($sql);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$zwei."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$drei."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$vier."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$funf."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$sechs."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$sieben."";
                db_query($sqltt);
                $sqltt = "UPDATE accounts SET house=".$id." WHERE acctid=".$acht."";
                db_query($sqltt);
                $session['user']['house']=$id;
            }
        }
    }
    addnav("Wohnviertel","wohnviertel.php");
}elseif ($_GET['op']==""){
    $text=getwvs("beschreibung","text");
    output($text);
    if ($session['user']['house']!="" && $session['user']['house']!="0"){
        output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td></tr>",true);
        $sql3 = "SELECT * FROM wohnviertel WHERE houseid=".$session['user']['house']."";
        $result3 = db_query($sql3) or die(db_error(LINK));
        $row3 = db_fetch_assoc($result3);
        if ($row3['status']=="0"){
            output("<tr><td align='center'>$row3[houseid]</td><td>$row3[housename] (als Akte beim Bauamt)</td></tr>",true);
        }elseif ($row3['status']=="3"){
            output("<tr><td align='center'>$row3[houseid]</td><td>$row3[housename] (als Antrag auf Verkauf beim Bauamt)</td></tr>",true);
        }else{
            output("<tr><td align='center'>$row3[houseid]</td><td><a href='wohnviertel.php?op=haus&id=$row3[houseid]'>$row3[housename]</a></td></tr>",true);
            addnav("","wohnviertel.php?op=haus&id=$row3[houseid]");
        }
        output("</table>",true);
    }
    output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td></tr>",true);
    $ppp=25;
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Straße","wohnviertel.php?limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql = "SELECT houseid,housename,status FROM wohnviertel WHERE status!=0 AND status!=1 AND status!=4 ORDER BY houseid ASC LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","wohnviertel.php?limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=3 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            output("<tr><td align='center'>$row2[houseid]</td><td><a href='wohnviertel.php?op=haus&id=$row2[houseid]'>$row2[housename]</a></td></tr>",true);
            addnav("","wohnviertel.php?op=haus&id=$row2[houseid]");
        }

    }
    output("</table>",true);
    addnav("Bauamt","wohnviertel.php?op=bauen");
    addnav("Dorfplatz","village.php");
    if ($session[user][superuser]>=2)
    {
        addnav("Admin Grotte","superuser.php");
    }
}elseif ($_GET['op']=="attack"){
    $sql = "SELECT name AS creaturename, level AS creaturelevel, weapon AS creatureweapon, gold AS creaturegold, experience AS creatureexp, hitpoints AS creaturehealth, attack AS creatureattack, defence AS creaturedefense, SUM(bounties.amount) AS creaturebounty, loggedin, location, housekey, dragonkills, laston, alive, accounts.acctid, lastip, emailaddress, pvpflag FROM accounts LEFT JOIN bounties ON bounties.acctid=accounts.acctid AND bounties.setby!=".$session['user']['acctid']." WHERE login=\"$_GET[name]\" GROUP BY bounties.acctid";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if (ac_check($row)){
            output("Nicht schummeln!!`b Du darfst deinen eigenen Charakter nicht angreifen!");
        }else{
              if (strtotime($row[laston]) > strtotime("-".getsetting("LOGINTIMEOUT",900)." sec") && $row[loggedin]){
                  output("`\$Fehler:`4 Dieser Krieger ist inzwischen online.");
            }else{
                  if ((int)$row[location]!=0 && 0 && $row[location]!=2){
                      output("`\$ Fehler:`4 Dieser Krieger befindet sich nicht an einem Ort, wo du ihn angreifen kannst.");
                }else{
                      if((int)$row[alive]!=1){
                          output("`\$Fehler:`4 Dieser Krieger lebt nicht.");
                    }else{
                          if ($session[user][playerfights]>0){
                            $sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=$row[acctid]";
                            db_query($sql);
                            $battle=true;
                            $row[pvp]=1;
                            $row[creatureexp] = round($row[creatureexp],0);
                            $row[playerstarthp] = $row[hitpoints];
                            //$session[user][badguy]=createstring($row);
                            updatetexts('badguy',createstring($row));
                            $session[user][playerfights]--;
                            updatetexts('buffbackup',"");
                            if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
                                $session['user']['pvpflag']="1986-10-06 00:42:00";
                                output("`n`4`bDeine Immunität ist hiermit verfallen!`b`0`n");
                            }
                            pvpwarning(true);
                        }else{
                              output("`4Du bist zu müde, um heute einen weiteren Kampf mit einem Krieger zu riskieren.");
                        }
                    }
                }
            }
        }
    }else{
          output("`\$Fehler:`4 Dieser Krieger wurde nicht gefunden. Darf ich fragen, wie du überhaupt hierher gekommen bist?");
    }
      if ($battle){

    }else{
          addnav("Zurück zum Dorf","village.php");
    }
}
if ($_GET[op]=="run"){
      output("Deine Ehre verbietet es dir wegzulaufen.");
    $_GET[op]="fight";
}
if ($_GET[skill]!=""){
      output("Deine Ehre verbietet es dir, deine besonderen Fähigkeiten einzusetzen.");
    $_GET[skill]="";
}
if ($_GET[op]=="fight" || $_GET[op]=="run"){
    $battle=true;
}
if ($battle){
      include("battle.php");
    if ($victory){
        $exp = round(getsetting("pvpattgain",10)*$badguy[creatureexp]/100,0);
        $expbonus = round(($exp * (1+.1*($badguy[creaturelevel]-$session[user][level]))) - $exp,0);
        output("`b`&$badguy[creaturelose]`0`b`n");
        output("`b`\$Du hast $badguy[creaturename] besiegt!`0`b`n");
        output("`#Du erbeutest `^$badguy[creaturegold]`# Gold!`n");
        if ($badguy[creaturebounty]>0){
            if ($session['user']['level'] < getsetting("bountylevel",3)) output("`#Als du das dir zustehende Kopfgeld abholen willst, lacht Dag Durnick dich nur schallend aus: `7\"Du willst das Kopfgeld kassieren? Werd erstmal selbst stark genug, dass jemand auf dich ein Kopfgeld aussetzen könnte! Jetzt geh mir aus den Augen!\"`#`n");
            else {
                $session['user']['gold']+=$badguy['creaturebounty'];
                output("`#Außerdem erhältst du das Kopfgeld in Höhe von `^$badguy[creaturebounty]`# Gold!`n");
                $session['user']['donation']+=1;
            }
        }
        if ($expbonus>0){
              output("`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^$expbonus`# Erfahrungspunkte!`n");
        }else if ($expbonus<0){
              output("`#*** Weil dieser Kampf so leicht war, verlierst du `^".abs($expbonus)."`# Erfahrungspunkte!`n");
        }
        output("Du bekommst insgesamt `^".($exp+$expbonus)."`# Erfahrungspunkte!`n`0");
        output("Du bekommst 1 Erfolgspunkt im Wohnviertelkampf!`n`0");
        $session['user']['killedinwohnviertel']++;
        $xplossfactor = 0;
        $mindks = getsetting("pvpmindkxploss",10);
        $dksdiff = $session['user']['dragonkills'] - $badguy['dragonkills'];
        if ($dksdiff>$mindks){
            $xplossfactor = 1 - (($badguy['dragonkills'] + 3) / ($session['user']['dragonkills']));

            $loss = round(($exp+$expbonus) * $xplossfactor);
            output("`#Davon werden dir `\$$loss `#Erfahrungspunkte abgezogen, weil dein Gegner $dksdiff Drachenkills weniger als du hat.");
        }
        $session['user']['gold']+=$badguy['creaturegold'];
        if ($badguy['creaturegold']) {
            debuglog("gained {$badguy['creaturegold']} gold for killing ", $badguy['acctid']);
        }
        $session['user']['experience']+=($exp+$expbonus-$loss);
        addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 bei einem Einbruch ins Haus!");
        if ($badguy['creaturebounty']>0){
                addnews("`4".$session['user']['name']."`3 verdient `4{$badguy['creaturebounty']} Gold`3 für den Kopf von `4{$badguy['creaturename']}`3!");
        }
        if ($badguy['acctid']==getsetting("hasegg",0)){
            savesetting("hasegg",stripslashes($session[user][acctid]));
            output("`n`^Du nimmst $badguy[creaturename] `^das goldene Ei ab!`0`n");
            addnews("`^".$session['user']['name']."`^ nimmt {$badguy['creaturename']}`^ das goldene Ei ab!");
        }
        $sql = "SELECT gold FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $badguy[creaturegold]=((int)$row[gold]>(int)$badguy[creaturegold]?(int)$badguy[creaturegold]:(int)$row[gold]);
        $lostexp = round($badguy['creatureexp']*getsetting("pvpdeflose",5)/100,0);
        debuglog("`\$gained ".($exp+$expbonus-$loss)." XP; badguy: XP before:{$badguy['creatureexp']}, XP lost:{$lostexp}, lossfactor:".round($xplossfactor,2)."`0", $badguy['acctid']);
        $lostexp -= round($lostexp*$xplossfactor,0);
         $mailmessage = "`^".$session['user']['name']."`2 hat dich mit %p `^".$session['user']['weapon']."`2 in einem Haus angegriffen und gewonnen!"
                ." `n`n".($session['user']['sex']?"Sie":"Er")." hatte anfangs `^".$badguy['playerstarthp']."`2 Lebenspunkte und kurz bevor du gestorben bist, hatte %o noch `^".$session['user']['hitpoints']."`2 Lebenspunkte übrig."
                ." `n`nDu hast `\$".round(getsetting("pvpdeflose",5)-$xplossfactor*getsetting("pvpdeflose",5),1)."%`2 deiner Erfahrungspunkte (etwa $lostexp Punkte) und `^".$badguy[creaturegold]."`2 Gold verloren.".($badguy[creaturebounty]>0?" Dein Angreifer kassierte außerdem das Kopfgeld in Höhe von `^".$badguy[creaturebounty]." `2Gold ein.":"").""
                ." `n`nGlaubst du nicht auch, es ist Zeit dich zu rächen?";
         $mailmessage = str_replace("%p",($session['user']['sex']?"ihre(r/m)":"seine(r/m)"),$mailmessage);
         $mailmessage = str_replace("%o",($session['user']['sex']?"sie":"er"),$mailmessage);
         systemmail($badguy['acctid'],"`2Du wurdest in einem Haus umgebracht",$mailmessage);
         $sql = "SELECT * FROM accounts WHERE acctid=".(int)$badguy[acctid]."";
         $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $diedpoints = $row['diedinwohnviertel']+1;
        $sql = "UPDATE accounts SET alive=0, goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience-$lostexp, diedinwohnviertel=$diedpoints WHERE acctid=".(int)$badguy[acctid]."";
        db_query($sql);
        if ($session['user']['level'] >= getsetting("bountylevel",3)) {
            $sql = "DELETE FROM bounties WHERE acctid=".(int)$badguy['acctid']." AND setby!=".$session['user']['acctid'];
            db_query($sql);
        }
        $_GET[op]="";
        $sql="UPDATE accounts SET location=0,alive=0,hitpoints=0,gold=0 WHERE acctid=$badguy[acctid]";
        db_query($sql);
        $sql="SELECT * FROM accounts WHERE housekey=$badguy[housekey] AND alive=1";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
            output("`@Du hast den letzten Bewohner umgebracht!!!");
            addnav("Nachricht hinterlassen","wohnviertel.php?op=fiesling&id=$badguy[housekey]");
        }else{
            addnav("Wohnviertel","wohnviertel.php");
        }
        $badguy=array();
    }else{
        if($defeat){
            addnav("Tägliche News","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"ihr(e/n)":"sein(e/n)"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
            $taunt = str_replace("%W",$badguy[creaturename],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            $badguy[acctid]=(int)$badguy[acctid];
            $badguy[creaturegold]=(int)$badguy[creaturegold];
            systemmail($badguy[acctid],"`2Du warst in einem Haus erfolgreich!","`^".$session[user][name]."`2 hat dich in $killedin`2 angegriffen, aber du hast gewonnen!`n`nDafür hast du `^".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0)."`2 Erfahrungspunkte und `^".$session[user][gold]."`2 Gold erhalten!");
            addnews("`%".$session[user][name]."`5 wurde bei ".($session[user][sex]?"ihrem":"seinem")."`5 Angriff auf`% $badguy[creaturename] `5  in einem Haus `5getötet.`n$taunt");
            $sql = "SELECT * FROM accounts WHERE acctid=".(int)$badguy[acctid]."";
             $result = db_query($sql);
            $row = db_fetch_assoc($result);
            $killerpoints = $row['killedinwohnviertel']+1;
            $sql = "UPDATE accounts SET gold=gold+".(int)$session[user][gold].", experience=experience+".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0).", killedinwohnviertel=$killerpoints WHERE acctid=".(int)$badguy[acctid]."";
            db_query($sql);
            $session[user][alive]=false;
            debuglog("lost {$session['user']['gold']} gold being slain by ", $badguy['acctid']);
            $session[user][diedinwohnviertel]++;
            $session[user][gold]=0;
            $session[user][hitpoints]=0;
            $session[user][experience]=round($session[user][experience]*(100-getsetting("pvpattlose",15))/100,0);
            updatetexts('badguy','');
            $sql="UPDATE accounts SET hitpoints=$badguy[creaturehealth] WHERE name='".$badguy['creaturename']."'";
            db_query($sql);
            output("`b`&Du wurdest von `%$badguy[creaturename] `&besiegt!!!`n");
            output("`4Alles Gold, das du bei dir hattest, hast du verloren!`n");
            output("`4".getsetting("pvpattlose",15)."%  deiner Erfahrung ging verloren!`n");
            output("Du kannst morgen wieder kämpfen.");
            page_footer();
        }else{
              fightnav(false,false);
        }
    }
}
page_footer();
?>