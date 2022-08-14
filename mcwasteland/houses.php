
<?php

/*

* Author:    anpera

* Email:        logd@anpera.de

*

* Purpose:    Houses for storing gold and gems and for a save place to sleep (logout)

*

* Features:    Build house, sell house, buy house, share house with others, private chat-area, PvP

*

* Every warrior can have his own house. He can build it with his own hands or buy one that was sold (or left) before.

* In a house he can store some of his gems and gold and houses are the savest place for log out.

* The player can give keys to other players. So he is able to share his gems and gold for example

* with his wife or he can make up a clan house. A player can only have one house but unlimited keys.

* Each house has its own private chat area.

* Other players can rob a house if they beat the guard and all players that are sleeping in the house.

*

*

* SEE  INSTRUCTIONS  FOR  INSTALLATION  AT  http://www.anpera.net/forum/viewtopic.php?t=323

* English translation available at DragonPrime

*

* Added furniture 05/25/2004

*  (Buy at vendor - vendor.php)

* Added Durandil's hidden path 05/30/2004

*

* Added Chaosmaker's pets 12/15/2004

*

* Ã„nderungen an der Datenbank (22.10.2005):

* ALTER TABLE `houses` ADD `datum` DATE NOT NULL ;

* ALTER TABLE `accounts` ADD `housedeal` DATE NOT NULL AFTER `housekey` ;

* Ok, lets do the code...

0000-00-00

*/





require_once("common.php");

addcommentary();

checkday();



// base values for pricing and chest size:

$goldcost=30000;

$gemcost=50;

// all other values are controlled by banksettings



// real days to wait between house trades:

$tradewait=2;



$status=array(

    0=>"`6im Bau`0",

    1=>"`9bewohnt`0",

    2=>"`^zu Verkaufen`0",

    3=>"`4verlassen`0",

    4=>"`\$Bauruine`0"

);



//not needed v

if ($session['user']['slainby']!=""){

    page_header("Du wurdest besiegt!");

    output("`\$Du wurdest in ".$session['user']['killedin']."`\$ von `%".$session['user']['slainby']."`\$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht es ist Zeit fÃ¼r Rache?");

    addnav("Weiter",$REQUEST_URI);

    $session['user']['slainby']="";

    $session['user']['donation']+=1;

    page_footer();

}

// ^



page_header("Das Wohnviertel");



if ($_GET['op']=="newday"){

    output("`2Gut erholt wachst du im Haus auf und bist bereit fÃ¼r neue Abenteuer.");

    $session['user']['location']=0;

    $sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=".$session['user']['acctid']." AND class='SchlÃ¼ssel'";

    db_query($sql) or die(sql_error($sql));

    addnav("TÃ¤gliche News","news.php");

    addnav("Wohnviertel","houses.php?op=enter");

    addnav("ZurÃ¼ck ins Dorf","village.php");



}else if ($_GET['op']=="bio"){

    if (!$_GET['id']) redirect("houses.php");

    $sql="SELECT houses.*,accounts.name AS besitzer FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE houseid={$_GET['id']}";

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);

    output("`c`b`@Infos Ã¼ber Haus Nummer ".$row['houseid']."`b`c`n`n`2Du nÃ¤herst dich Haus Nummer ".$row['houseid'].", um es aus der NÃ¤he zu betrachten. ");

    if(strlen($row['description'])>1){

        output("Ãœber dem Eingang  von ".$row['housename']."`2 steht geschrieben:`n`& ".$row['description']."`n`n");

    }else{

        output("Das Haus trÃ¤gt den Namen \"`&".$row['housename']."`2\".`n");

    }

    $sql="SELECT * FROM items WHERE class='MÃ¶bel' AND value1={$_GET['id']} ORDER BY id ASC";

    $result = db_query($sql);

    if ($row['besitzer']=="") $row['besitzer']="niemandem";

    output("`2Das Haus gehÃ¶rt `^".$row['besitzer']."`2 und ist ".$status[$row['status']]."`2. ");

    if ($row['datum']>"0000-00-00" && getsetting("activategamedate",0)==1){

        if ($row['status']==0){

            output("Der Grundstein wurde am `^".getgamedate($row['datum'])." `2gelegt.");

        }else{

            output("Das Haus wurde am `^".getgamedate($row['datum'])." `2fertig gestellt.");

        }

    }

    output("`2Du riskierst einen Blick durch eines der Fenster");

    if (db_num_rows($result)>0){

        output(" und erkennst ");

        for ($i=0;$i<db_num_rows($result);$i++){

            $row2 = db_fetch_assoc($result);

            output("`@".$row2['name']);

            if($i+1<db_num_rows($result)) output(", ");

        }

        output(".`n");

    }else{

        output(", aber das Haus hat sonst nichts weiter zu bieten.");

    }

    if (getsetting("dailyspecial",0)=="Waldsee"){

        output("`n`n`@WÃ¤hrend du dir das Haus genau ansiehst, fÃ¤llt dir ein kleiner Trampelpfad auf...");

        addnav("Trampelpfad","paths.php?ziel=forestlake");

    }

    if ($_GET['id']==$session['user']['housekey']) addnav("Haus betreten","houses.php?op=drin&id={$_GET['id']}");

    addnav("ZurÃ¼ck","houses.php");



}else if ($_GET['op']=="build"){

    if ($_GET['act']=="start") {

        $sql = "INSERT INTO houses (owner,status,gold,gems,housename,datum) VALUES ('".$session['user']['acctid']."','0','0','0','".$session['user']['login']."s Haus','".(getsetting("activategamedate",0)?getsetting("gamedate","0000-00-00"):"")."')";

        db_query($sql) or die(db_error(LINK));

        //if (db_affected_rows(LINK)<=0) redirect("houses.php");

        $sql = "SELECT * FROM houses WHERE status='0' AND owner='".$session['user']['acctid']."' ORDER BY houseid DESC";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        $session['user']['house']=$row['houseid'];

        output("`@Du erklÃ¤rst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `^{$row['houseid']}`@ beginnen.`n`n");

        output("`0<form action=\"houses.php?op=build&act=build2\" method='POST'>",true);

        output("`nGib einen Namen fÃ¼r dein Haus ein: <input type='text' name='housename' maxlength='25'>`n",true);

        output("`i(Der Name lÃ¤sst sich nach der Fertigstellung des Hauses Ã¤ndern.)`i");

        output("`nWieviel Gold anzahlen? <input type='text' name='gold' maxlength='9'>`n",true);

        output("`nWieviele Edelsteine? <input type='text' name='gems' maxlength='9'>`n",true);

        output("<input type='submit' class='button' value='Bauen'></form>",true);

        addnav("","houses.php?op=build&act=build2");

    }else if ($_GET['act']=="build2") {

        $sql = "SELECT * FROM houses WHERE status=0 AND owner=".$session['user']['acctid']." ORDER BY houseid DESC";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        $paidgold=(int)$_POST['gold'];

        if ($_POST['housename']>""){

            $housename=stripslashes($_POST['housename']);

        }else{

            $housename=stripslashes($row['housename']);

        }

        $paidgems=(int)$_POST['gems'];

        if ($session['user']['gold']<$paidgold || $session['user']['gems']<$paidgems) {

            output("`@Du hast nicht genug dabei!");

            addnav("Nochmal","houses.php?op=build");

        } else if ($session['user']['turns']<1){

            output("`@Du bist zu mÃ¼de, um heute noch an deinem Haus zu arbeiten!");

        } else if ($paidgold<0 || $paidgems<0){

            output("`@Versuch hier besser nicht zu beschummeln.`nDu verlierst an Ansehen.");

            $session['user']['reputation']--;

        } else {

            output("`@Du baust fÃ¼r `^$paidgold`@ Gold und `#$paidgems`@ Edelsteine an deinem Haus \"`&$housename`@\"...`n");

            $row['gold']+=$paidgold;

            $session['user']['gold']-=$paidgold;

            output("`nDu verlierst einen Waldkampf.");

            $session['user']['turns']--;

            if ($row['gold']>$goldcost) {

                output("`nDu hast die kompletten Goldkosten bezahlt und bekommst das Ã¼berschÃ¼ssige Gold zurÃ¼ck.");

                $session['user']['gold']+=$row['gold']-$goldcost;

                $row['gold']=$goldcost;

            }

            $row['gems']+=$paidgems;

            $session['user']['gems']-=$paidgems;

            if ($row['gems']>$gemcost) {

                output("`nDu hast die kompletten Edelsteinkosten bezahlt und bekommst Ã¼berschÃ¼ssige Edelsteine zurÃ¼ck.");

                $session['user']['gems']+=$row['gems']-$gemcost;

                $row['gems']=$gemcost;

            }

            $goldtopay=$goldcost-$row['gold'];

            $gemstopay=$gemcost-$row['gems'];

            $done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);

            output("`nDein Haus ist damit zu `\$$done%`@ fertig. Du musst noch `^$goldtopay`@ Gold und `#$gemstopay `@Edelsteine bezahlen, bis du einziehen kannst.");

            if ($row['gems']>=$gemcost && $row['gold']>=$goldcost) {

                output("`n`n`bGlÃ¼ckwunsch!`b Dein Haus ist fertig. Du bekommst `b10`b SchlÃ¼ssel Ã¼berreicht, von denen du 9 an andere weitergeben kannst, und besitzt nun deine eigene kleine Burg.");

                $row['gems']=0;

                $row['gold']=0;

                $session['user']['housekey']=$row['houseid'];

                $row['status']=1;

                debuglog("Hausnummer ".$row['houseid']." fertiggestellt.");

                addnews("`2".$session['user']['name']."`3 hat das Haus `2".$row['housename']."`3 fertiggestellt.");

                //$sql="";

                for ($i=1;$i<10;$i++){

                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('HausschlÃ¼ssel',".$session['user']['acctid'].",'SchlÃ¼ssel',".$row['houseid'].",$i,0,0,'SchlÃ¼ssel fÃ¼r Haus Nummer ".$row['houseid']."')";

                    db_query($sql);

                    //if (db_affected_rows(LINK)<=0) output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin. ");

                }

            }

            $sql = "UPDATE houses SET gold=".$row['gold'].",gems=".$row['gems'].",housename='".addslashes($housename)."',status=".(int)$row['status'].",datum='".(getsetting("activategamedate",0)?getsetting("gamedate","0000-00-00"):"")."' WHERE houseid=".$row['houseid'];

            db_query($sql);

        }

    } else {

        if ($session['user']['housekey']>0) {

            output("`@Du hast bereits Zugang zu einem fertigen Haus und brauchst kein zweites. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.");

        } else if ($session['user']['dragonkills']<1 || ($session['user']['dragonkills']==1 && $session['user']['level']<5)) {

            output("`@Du hast noch nicht genug Erfahrung, um ein eigenes Haus bauen zu kÃ¶nnen. Du kannst aber bei einem Freund einziehen, wenn er dir einen SchlÃ¼ssel fÃ¼r sein Haus gibt.");

        } else if ($session['user']['turns']<1) {

            output("`@Du bist zu erschÃ¶pft, um heute noch irgendetwas zu bauen. Warte bis morgen.");

        } else if ($session['user']['house']>0) {

            $sql = "SELECT * FROM houses WHERE status=0 AND owner=".$session['user']['acctid']." ORDER BY houseid DESC";

            $result = db_query($sql) or die(db_error(LINK));

            $row = db_fetch_assoc($result);

            output("`@Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `3".$row['houseid']."`@.`n`n");

            $goldtopay=$goldcost-$row['gold'];

            $gemstopay=$gemcost-$row['gems'];

            $done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);

            output(grafbar(100,$done,"75%",20),true);

            if ($row['datum']>"0000-00-00" && getsetting("activategamedate",0)==1) output("`n`@Der Grundstein wurde am `^".getgamedate($row['datum'])." `2gelegt.");

            output("`nEs ist zu `\$$done%`@ fertig. Du musst noch `^$goldtopay`@ Gold und `#$gemstopay `@Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n`0");

            $output.="<form action=\"houses.php?op=build&act=build2\" method='POST'>";

            $output.="<br>Wieviel Gold zahlen? <input type='text' name='gold'><br>";

            $output.="<br>Wieviele Edelsteine? <input type='text' name='gems'><br>";

            $output.="<input type='submit' class='button' value='Bauen'></form>";

            addnav("","houses.php?op=build&act=build2");

        } else {

            output("`@Du siehst ein schÃ¶nes Fleckchen fÃ¼r ein Haus und Ã¼berlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch lÃ¤nger in Kneipe und Feldern zu Ã¼bernachten.");

            output(" Ein Haus zu bauen wÃ¼rde dich `^$goldcost Gold`@ und `#$gemcost Edelsteine`@ kosten. Du muÃŸt das nicht auf einmal bezahlen, sondern kÃ¶nntest immer wieder mal fÃ¼r einen kleineren Betrag ein StÃ¼ck ");

            output("weiter bauen. Wie schnell du zu deinem Haus kommst, hÃ¤ngt also davon ab, wie oft und wieviel du bezahlst.`n");

            output("Du kannst in deinem zukÃ¼nftigen Haus alleine wohnen, oder es mit anderen teilen. Es bietet einen sicheren Platz zum Ãœbernachten und einen Lagerplatz fÃ¼r einen Teil deiner ReichtÃ¼mer.");

            output(" Ein gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?");

            addnav("Hausbau beginnen","houses.php?op=build&act=start");

        }

    }

    addnav("ZurÃ¼ck zum Wohnviertel","houses.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");



}else if ($_GET['op']=="einbruch"){

    if (!$_GET['id']){

        if ($_POST['search']>"" || $_GET['search']>""){

            if ($_GET['search']>"") $_POST['search']=$_GET['search'];

            if (strcspn($_POST['search'],"0123456789")<=1){

                $search="houseid=".intval($_POST['search'])." AND ";

            }else{

                $search="%";

                for ($x=0;$x<strlen($_POST['search']);$x++){

                    $search .= substr($_POST['search'],$x,1)."%";

                }

                $search="housename LIKE '".$search."' AND ";

            }

        }else{

            $search="";

        }

        $ppp=25; // Player Per Page to display

        if (!$_GET['limit']){

            $page=0;

        }else{

            $page=(int)$_GET['limit'];

            addnav("Vorherige Strasse","houses.php?op=einbruch&limit=".($page-1)."&search={$_POST['search']}");

        }

        $limit="".($page*$ppp).",".($ppp+1);

        $sql = "SELECT houses.*,accounts.name AS besitzer FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE $search (status=1 OR status=3) AND owner<>".$session['user']['acctid']." ORDER BY houseid ASC LIMIT $limit";

        output("`c`b`^Einbruch`b`c`0`n");

        output("`@Du siehst dich um und suchst dir ein bewohntes Haus fÃ¼r einen Einbruch aus. ");

        output("Leider kannst du nicht erkennen, wieviele Bewohner sich gerade darin aufhalten und wie stark diese sind. So ein Einbruch ist also sehr riskant.`nFÃ¼r welches Haus entscheidest du dich?`n`n");

        output("<form action='houses.php?op=einbruch' method='POST'>Nach Hausname oder Nummer <input name='search' value='{$_POST['search']}' maxlength='25'> <input type='submit' class='button' value='Suchen'></form>",true);

        addnav("","houses.php?op=einbruch");

        if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`n`&(Du hast PvP-ImmunitÃ¤t gekauft. Diese verfÃ¤llt, wenn du jetzt angreifst!)`0`n`n");

        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentÃ¼mer`b</td></tr>",true);

        $result = db_query($sql) or die(db_error(LINK));

        if (db_num_rows($result)>$ppp) addnav("NÃ¤chste Strasse","houses.php?op=einbruch&limit=".($page+1)."&search={$_POST['search']}");

        if (db_num_rows($result)==0){

              output("<tr><td colspan=4 align='center'>`&`iEs gibt momentan keine bewohnten HÃ¤user`i`0</td></tr>",true);

        }else{

            for ($i=0;$i<db_num_rows($result);$i++){

                  $row = db_fetch_assoc($result);

                $bgcolor=($i%2==1?"trlight":"trdark");

                output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses.php?op=einbruch&id={$row['houseid']}'>{$row['housename']}</a></td><td>{$row['besitzer']}</td></tr>",true);

                addnav("","houses.php?op=einbruch&id={$row['houseid']}");

            }

        }

        output("</table>",true);

        addnav("Umkehren","houses.php");

    }else{

        if ($session['user']['turns']<1 || $session['user']['playerfights']<=0){

            output("`nDu bist wirklich schon zu mÃ¼de, um ein Haus zu Ã¼berfallen.");

            addnav("ZurÃ¼ck","houses.php");

        }else{

            output("`2Du nÃ¤herst dich vorsichtig Haus Nummer {$_GET['id']}.");

            $session['housekey']=$_GET['id'];

            // Abfrage, ob SchlÃ¼ssel vorhanden!!

            $sql = "SELECT id FROM items WHERE owner=".$session['user']['acctid']." AND class='SchlÃ¼ssel' AND value1=".(int)$_GET['id']." ORDER BY id DESC";

            $result2 = db_query($sql) or die(db_error(LINK));

            $row2 = db_fetch_assoc($result2);

            if (db_num_rows($result2)>0) {

                output(" An der HaustÃ¼r angekommen suchst du etwas, um die TÃ¼r mÃ¶glichst unauffÃ¤llig zu Ã¶ffnen. Am besten dÃ¼rfte dafÃ¼r der HausschlÃ¼ssel geeignet sein, ");

                output(" den du einstecken hast.`nWolltest du wirklich gerade in ein Haus einbrechen, fÃ¼r das du einen SchlÃ¼ssel hast?");

                addnav("Haus betreten","houses.php?op=drin&id={$_GET['id']}");

                addnav("ZurÃ¼ck zum Dorf","village.php");

            } else {

                // Wache besiegen

                output("Deine gebÃ¼ckte Haltung und der schleichende Gang machen eine Stadtwache aufmerksam...`n");

                $pvptime = getsetting("pvptimeout",600);

                $pvptimeout = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -$pvptime seconds"));

                $days = getsetting("pvpimmunity", 5);

                $exp = getsetting("pvpminexp", 1500);

                $sql = "SELECT acctid,level,maxhitpoints,login,housekey FROM accounts WHERE

                (locked=0) AND

                (alive=1 AND location=2) AND

                (laston < '".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND

                (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND

                (acctid <> ".$session['user']['acctid'].") AND

                (pvpflag <> '5013-10-06 00:42:00') AND

                (pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";

                $result = db_query($sql) or die(db_error(LINK));

                $hp=0;

                $count=0;

                // count chars at home and find strongest

                if(db_num_rows($result)){

                    for ($i=0;$i<db_num_rows($result);$i++){

                        $row = db_fetch_assoc($result);

                        $sql = "SELECT value1 FROM items WHERE value1=".(int)$session['housekey']." AND owner={$row['acctid']} AND class='SchlÃ¼ssel' AND hvalue=".(int)$session['housekey']." ORDER BY id";

                        $result2 = db_query($sql) or die(db_error(LINK));

                        if (db_num_rows($result2)>0 || ((int)$row['housekey']==(int)$session['housekey'] && 0==db_num_rows(db_query("SELECT hvalue FROM items WHERE hvalue<>0 AND class='SchlÃ¼ssel' AND value1<>{$session['housekey']} AND owner={$row['acctid']}")))){

                            if ($row['maxhitpoints']>$hp){

                                $hp=(int)$row['maxhitpoints'];

                                $count++;

                            }

                        }

                        db_free_result($result2);

                    }

                }

                if ($count>0){

                    $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"HolzknÃ¼ppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs($session['user']['maxhitpoints']-$hp)+1, "diddamage"=>0);

                }else{

                    $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"starker HolzknÃ¼ppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs(max($session['user']['maxhitpoints'], $session['user']['hitpoints'])), "diddamage"=>0);

                    $session['user']['playerfights']--;

                    $session['user']['reputation']-=7;

                }

                $session['user']['badguy']=createstring($badguy);

                $fight=true;

            }

        }

    }



}elseif ($_GET['op'] == "fight") {

    $fight=true;



}elseif ($_GET['op'] == "run") {

    $badguy = createarray($session['user']['badguy']);

    // fight against guard

    if ($badguy['creaturename']=='Stadtwache') {

        output("`%Die Wache lÃ¤sst dich nicht entkommen!`n");

        $session['user']['reputation']--;

    }

    // fight against pet

    else {

        output("`%".$badguy['creaturename']."`% lÃ¤sst dich nicht entkommen!`n");

    }

    $fight=true;



}else if ($_GET['op']=="einbruch2"){

    $badguy = createarray($session['user']['badguy']);

    $fightpet = false;

    // check for pet

    if ($badguy['creaturename']=='Stadtwache') {

        $sql = 'SELECT accounts.petid AS pet, items.name, items.buff FROM accounts LEFT JOIN items ON accounts.petid=items.id WHERE accounts.house='.$session['housekey'].' AND accounts.petfeed > NOW()';

        $result = db_query($sql);

        if ($row = db_fetch_assoc($result)) {

            if ($row['pet']>0) {

                $petbuff = unserialize($row['buff']);

                $badguy = array(

                    'creaturename'=>$row['name'],

                    'creaturelevel'=>$session['user']['level'],

                    'creatureweapon'=>$petbuff['name'],

                    'creatureattack'=>$petbuff['atkmod'],

                    'creaturedefense'=>$petbuff['defmod'],

                    'creaturehealth'=>$petbuff['regen'],

                    'diddamage'=>0

                );

                $session['user']['badguy'] = createstring($badguy);

                $fight = $fightpet = true;

                output('`$Gerade willst du ins Haus schleichen, als du hinter dir plÃ¶tzlich ein Knurren vernimmst.`0`n');

            }

        }

    }



    if (!$fightpet) {

        // Spieler besiegen

        $pvptime = getsetting("pvptimeout",600);

        $pvptimeout = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -$pvptime seconds"));

        $days = getsetting("pvpimmunity", 5);

        $exp = getsetting("pvpminexp", 1500);

        $sql = "SELECT acctid,name,maxhitpoints,defence,attack,level,laston,loggedin,login,housekey FROM accounts WHERE

        (locked=0) AND

        (alive=1 AND location=2) AND

        (laston < '".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND

        (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND

        (acctid <> ".$session['user']['acctid'].") AND

        (pvpflag <> '5013-10-06 00:42:00') AND

        (pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";

        $result = db_query($sql) or die(db_error(LINK));

        $athome=0;

        $name="";

        $hp=0;

        // count chars at home and find strongest

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            $sql = "SELECT value1 FROM items WHERE value1=".(int)$session['housekey']." AND class='SchlÃ¼ssel' AND owner=".$row['acctid']." AND hvalue=".(int)$session['housekey']." ORDER BY id";

            $result2 = db_query($sql) or die(db_error(LINK));

            if (db_num_rows($result2)>0 || ((int)$row['housekey']==(int)$session['housekey'] && 0==db_num_rows(db_query("SELECT hvalue FROM items WHERE hvalue<>0 AND class='SchlÃ¼ssel' AND value1<>{$session['housekey']} AND owner={$row['acctid']}")))){

                $athome++;

                if ($row['maxhitpoints']>$hp){

                    $hp=$row['maxhitpoints'];

                    $name=$row['login'];

                }

            }

            db_free_result($result2);

        }

        addnav("FlÃ¼chte","village.php");

        if ($athome>0){

            output("`n Dir kommen $athome misstrauische Bewohner schwer bewaffnet entgegen. Der wahrscheinlich stÃ¤rkste von ihnen wird sich jeden Augenblick auf dich stÃ¼rzen, ");

            output(" wenn du die Situation nicht sofort entschÃ¤rfst.");

            addnav("KÃ¤mpfe","pvp.php?act=attack&bg=2&name=".rawurlencode($name));

        } else {

            output(" Du hast GlÃ¼ck, denn es scheint niemand daheim zu sein. Das wird sicher ein Kinderspiel.");

            addnav("Einsteigen","houses.php?op=klauen&id={$session['housekey']}");

        }

    }



}else if ($_GET['op']=="klauen"){

    if (!$_GET['id']){

        output("Und jetzt? Bitte benachrichtige den Admin. Ich weiÃŸ nicht, was ich jetzt tun soll...");

        addnav("ZurÃ¼ck zum Dorf","village.php");

    } else {

        addnav("ZurÃ¼ck zum Dorf","village.php");

        $sql = "SELECT * FROM houses WHERE houseid=".$session['housekey']." ORDER BY houseid ASC";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        $wasnu=e_rand(1,3);

        $sql="UPDATE houses SET ";

        switch($wasnu){

            case 1:

            $getgems=e_rand(0,round($row['gems']/10));

            $getgold=e_rand(0,round($row['gold']/10));

            $sql.="gold=gold-$getgold,gems=gems-$getgems";

            break;

            case 2:

            $getgems=0;

            $getgold=e_rand(0,round($row['gold']/10));

            $sql.="gold=gold-$getgold";

            break;

            case 3:

            $getgems=e_rand(0,round($row['gems']/10));

            $getgold=0;

            $sql.="gems=gems-$getgems";

            break;

        }

        $sql.=" WHERE houseid={$row['houseid']}";

        db_query($sql) or die(db_error(LINK));

        $session['user']['gold']+=$getgold;

        $session['user']['gems']+=$getgems;

        output("`@Es gelingt dir, `^$getgold `@Gold und  `#$getgems `@Edelsteine aus dem Schatz zu klauen!");

        addnews("`6".$session['user']['name']."`6 erbeutet `#$getgems`6 Edelsteine und `^$getgold`6 Gold bei einem Einbruch!");

        systemmail($row['owner'],"`\$Einbruch!`0","`\${$session['user']['name']}`\$ ist ".(getsetting("activategamedate",0)?" am ".getgamedate():"")." in dein Haus eingebrochen und hat `^$getgold`\$ Gold und `#$getgems`\$ Edelsteine erbeutet!");

    }



}else if ($_GET['op']=="buy"){

    if (strtotime($session['user']['housedeal']."+ $tradewait days")>strtotime(date("Y-m-d"))){

        output("`@Du hast erst vor Kurzem einen HÃ¤userhandel abgeschlossen. Du musst noch ".round(getsetting("daysperday",4)*((strtotime($session['user']['housedeal']." + $tradewait days")-strtotime(date("Y-m-d")))/60/60/24)));

        output(" Tage warten, oder den GrÃ¼nen Drachen tÃ¶ten, bevor du den nÃ¤chsten Handel abschlieÃŸen kannst.");

    }elseif (!$_GET['id']){

        $ppp=20; // Player Per Page to display

        if (!$_GET['limit']){

            $page=0;

        }else{

            $page=(int)$_GET['limit'];

            addnav("Vorherige Seite","houses.php?op=buy&limit=".($page-1)."");

        }

        $limit="".($page*$ppp).",".($ppp+1);

        $sql = "SELECT * FROM houses WHERE status=2 OR status=3 OR status=4 ORDER BY houseid ASC LIMIT $limit";

        output("`c`b`^Unbewohnte HÃ¤user`b`c`0`n");

        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bGold`b</td><td>`bEdelsteine`b</td><td>`bBemerkung`b</td></tr>",true);

        $result = db_query($sql) or die(db_error(LINK));

        if (db_num_rows($result)>$ppp) addnav("NÃ¤chste Seite","houses.php?op=buy&limit=".($page+1)."");

        if (db_num_rows($result)==0){

              output("<tr><td colspan=4 align='center'>`&`iEs stehen momentan keine HÃ¤user zum Verkauf`i`0</td></tr>",true);

        }else{

            for ($i=0;$i<db_num_rows($result);$i++){

                  $row = db_fetch_assoc($result);

                $bgcolor=($i%2==1?"trlight":"trdark");

                output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses.php?op=buy&id={$row['houseid']}'>{$row['housename']}</a></td><td align='right'>{$row['gold']}</td><td align='right'>{$row['gems']}</td><td>",true);

                if ($row['status']==3){

                    output("`4Verlassen`0");

                }else if ($row['status']==4){

                    output("`\$Bauruine`0");

                }else if ($row['owner']==0){

                    output("`^Maklerverkauf`0");

                }else{

                    output("`6Privatverkauf`0");

                }

                output("</td></tr>",true);

                addnav("","houses.php?op=buy&id={$row['houseid']}");

            }

        }

        output("</table>",true);

        output("`n`i`4Hinweis:`0 Nach dem Kauf eines Hauses kannst du ".round($tradewait*getsetting("daysperday",4))." Spieltage lang, oder bis zu deinem nÃ¤chsten Drachenkill, keinen weiteren Handel mit HÃ¤usern abschlieÃŸen.`i");

    } else {

        $sql = "SELECT * FROM houses WHERE houseid=".(int)$_GET['id']." ORDER BY houseid DESC";

        $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        if ($session['user']['acctid']==$row['owner']) {

            output("`@du hÃ¤ngst doch zu sehr an deinem Haus und beschlieÃŸt, es noch nicht zu verkaufen.");

            $session['user']['housekey']=$row['houseid'];

            $sql = "UPDATE houses SET gold=0,gems=0,status=1";

            if (getsetting("activategamedate",0)==1 && $row['datum']<="0000-00-00") $sql.=",datum='".getsetting("gamedate","0000-00-00")."'";

            $sql.=" WHERE houseid={$row['houseid']}";

            db_query($sql);

        }else if ($session['user']['gold']<$row['gold'] || $session['user']['gems']<$row['gems']){

            output("`@Dieses edle Haus Ã¼bersteigt wohl deine finanziellen Mittel.");

        }else {

            output("`@GlÃ¼ckwunsch zu deinem neuen Haus!`n`n");

            debuglog("Haus Nummer {$row['houseid']} fÃ¼r {$row['gold']} Gold und {$row['gems']} gekauft. (Status: {$status[$row['status']]}`0");

            $session['user']['gold']-=$row['gold'];

            $session['user']['gems']-=$row['gems'];

            $session['user']['house']=$row['houseid'];

            $session['user']['housedeal']=date("Y-m-d");

            output("Du Ã¼bergibst `^{$row['gold']}`@ Gold und `#{$row['gems']}`@ Edelsteine an den VerkÃ¤ufer, und dieser hÃ¤ndigt dir dafÃ¼r einen Satz SchlÃ¼ssel fÃ¼r Haus `b{$row['houseid']}`b aus.");

            if ($row['owner']>0){

                $sql = "UPDATE accounts SET goldinbank=goldinbank+{$row['gold']},gems=gems+{$row['gems']},house=0,housekey=0 WHERE acctid={$row['owner']}";

                db_query($sql);

                systemmail($row['owner'],"`@Haus verkauft!`0","`&{$session['user']['name']}`2 hat dein Haus gekauft. Du bekommst `^{$row['gold']}`2 Gold auf die Bank und `#{$row['gems']}`2!");

                $session['user']['housekey']=$row['houseid'];

            }

            if ($row['status']==3){

                $sql = "UPDATE houses SET status=1,owner=".$session['user']['acctid']." WHERE houseid=".$row['houseid'];

                db_query($sql);

                $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE owner=0 and class='SchlÃ¼ssel' AND value1=".$row['houseid'];

                output("`n`4 Bitte bedenke, dass du ein verlassenes Haus gekauft hast, zu dem vielleicht noch andere einen SchlÃ¼ssel haben!`0");

                $session['user']['housekey']=$row['houseid'];

            }else if ($row['status']==4){

                $sql = "UPDATE houses SET status=0,owner=".$session['user']['acctid']." WHERE houseid=".$row['houseid'];

                output("`n`4 Bitte bedenke, dass du eine Bauruine gekauft hast, die du erst fertigbauen musst!`0");

            }else{

                $sql = "UPDATE houses SET gold=0,gems=0,status=1,owner=".$session['user']['acctid']." WHERE houseid=".$row['houseid'];

                db_query($sql);

                $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE class='SchlÃ¼ssel' AND value1=".$row['houseid'];

                $session['user']['housekey']=$row['houseid'];

            }

            db_query($sql);

        }

    }

    addnav("W?ZurÃ¼ck zum Wohnviertel","houses.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");



}else if ($_GET['op']=="sell"){

    $sql = "SELECT * FROM houses WHERE houseid=".$session['user']['housekey']." ORDER BY houseid DESC";

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);

    $halfgold=round($goldcost/3);

    $halfgems=round($gemcost/3);

    if (strtotime($session['user']['housedeal']."+ $tradewait days")>strtotime(date("Y-m-d"))){

        output("`@Du hast erst vor Kurzem einen HÃ¤userhandel abgeschlossen. Du musst noch ".round(getsetting("daysperday",4)*((strtotime($session['user']['housedeal']." + $tradewait days")-strtotime(date("Y-m-d")))/60/60/24)));

        output(" Tage warten, oder den GrÃ¼nen Drachen tÃ¶ten, bevor du den nÃ¤chsten Handel abschlieÃŸen kannst.");

    }elseif ($_GET['act']=="sold"){

        if (!$_POST['gold'] && !$_POST['gems']){

            output("`@Du denkst ernsthaft darÃ¼ber nach, dein HÃ¤uschen zu verkaufen. Wenn du selbst einen Preis festlegst, bedenke, daÃŸ er auf einmal bezahlt werden muss ");

            output(" und vom KÃ¤ufer nicht in Raten abgezahlt werden kann. AuÃŸerdem kannst du weder ein neues Haus bauen, noch in diesem Haus wohnen, bis es verkauft ist.");

            output(" Du bekommst dein Geld erst, wenn das Haus verkauft ist. Der Verkauf lÃ¤ÃŸt sich abbrechen, indem du selbst das Haus von dir kaufst.");

            output("`nWenn du sofort Geld sehen willst, musst du dein Haus fÃ¼r `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine an einen Makler verkaufen.");

            output("`0<form action=\"houses.php?op=sell&act=sold\" method='POST'>",true);

            output("`nWieviel Gold willst du verlangen? <input type='gold' name='gold'>`n",true);

            output("`nWieviele Edelsteine soll das Haus kosten? <input type='gems' name='gems'>`n",true);

            output("<input type='submit' class='button' value='Anbieten'></form>",true);

            addnav("","houses.php?op=sell&act=sold");

            addnav("An den Makler","houses.php?op=sell&act=makler");

            output("`n`n`i`4Hinweis:`0 Nach dem Verkauf deines Hauses kannst du ".($tradewait*getsetting("daysperday",4))." Spieltage lang, oder bis zu dienem nÃ¤chsten Drachenkill, keinen weiteren Handel mit HÃ¤usern abschlieÃŸen.`i");

        }else{

            $halfgold=(int)$_POST['gold'];

            $halfgems=(int)$_POST['gems'];

            if (($halfgold<$goldcost/40 && $halfgems<$gemcost/10) || ($halfgold==0 && $halfgems<$gemcost/2) || ($halfgold<$goldcost/20 && $halfgems==0)){

                output("`@Du solltest vielleicht erst deinen Ale-Rausch ausschlafen, bevor du Ã¼ber einen Preis nachdenkst. Wie? Du bist nÃ¼chtern? Das glaubt dir so kein Mensch.");

                addnav("Neuer Preis","houses.php?op=sell&act=sold");

            }else if ($halfgold>$goldcost*2 || $halfgems>$gemcost*4){

                output("`@Bei so einem hohen Preis bist du dir nicht sicher, ob du wirklich verkaufen sollst. Ãœberlege es dir nochmal.");

                addnav("Neuer Preis","houses.php?op=sell&act=sold");

            }else{

                output("`@Dein Haus steht ab sofort fÃ¼r `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine zum Verkauf. Du und alle Mitbewohner habt den Schatz des Hauses gleichmÃ¤ÃŸig ");

                output(" unter euch aufgeteilt und deine Untermieter haben ihre SchlÃ¼ssel abgegeben.");

                debuglog("Haus Nummer {$row['houseid']} fÃ¼r $halfgold Gold und $halfgems Edelsteine verkauft.");

                // Gold und Edelsteine an Bewohner verteilen und SchlÃ¼ssel einziehen

                $sql = "SELECT owner FROM items WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' AND owner<>{$row['owner']} ORDER BY id ASC";

                $result = db_query($sql) or die(db_error(LINK));

                $amt=db_num_rows($result);

                $goldgive=round($row['gold']/($amt+1));

                $gemsgive=round($row['gems']/($amt+1));

                $session['user']['gold']+=$goldgive;

                $session['user']['gems']+=$gemsgive;

                $session['user']['housedeal']=date("Y-m-d");

                // $sql="";

                for ($i=0;$i<db_num_rows($result);$i++){

                    $item = db_fetch_assoc($result);

                    $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid={$item['owner']}";

                    db_query($sql);

                    systemmail($item['owner'],"`@Rauswurf!`0","`&{$session['user']['name']}`2 hat das Haus `b{$row['housename']}`b`2 verkauft, in dem du als Untermieter gewohnt hast. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");

                }

                $sql = "UPDATE items SET owner={$row['owner']} WHERE class='SchlÃ¼ssel' AND value1={$row['houseid']}";

                db_query($sql);

                // Variablen setzen und Datenbank updaten

                $row['gold']=$halfgold;

                $row['gems']=$halfgems;

                $session['user']['housekey']=0;

                $sql = "UPDATE houses SET gold={$row['gold']},gems={$row['gems']},status=2 WHERE houseid={$row['houseid']}";

                db_query($sql);

            }

        }

    }else if ($_GET['act']=="makler"){

        output("`@Dem Makler entfÃ¤hrt ungewollt ein freudiges Glucksen, als er dir `^$halfcost`@ Gold und die `#$halfcost`@ Edelsteine vorzÃ¤hlt.`n`n");

        output("Ab sofort steht dein Haus zum Verkauf und du kannst ein neues bauen, woanders mit einziehen, oder in ".($tradewait*getsetting("daysperday",4))." Tagen ein anderes Haus kaufen.");

        debuglog("Haus Nummer {$row['houseid']} an den Makler verkauft.");

        // Gold und Edelsteine an Bewohner verteilen und SchlÃ¼ssel einziehen

        $sql = "SELECT owner FROM items WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' AND owner<>{$row['owner']} ORDER BY id ASC";

        $result = db_query($sql) or die(db_error(LINK));

        $goldgive=round($row['gold']/(db_num_rows($result)+1));

        $gemsgive=round($row['gems']/(db_num_rows($result)+1));

        $session['user']['gold']+=$goldgive;

        $session['user']['gems']+=$gemsgive;

        $session['user']['housedeal']=date("Y-m-d");

        //$sql="";

        for ($i=0;$i<db_num_rows($result);$i++){

            $item = db_fetch_assoc($result);

            $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid={$item['owner']}";

            db_query($sql);

            systemmail($item['owner'],"`@Rauswurf!`0","`&{$session['user']['name']}`2 hat das Haus `b{$row['housename']}`b`2 verkauft, in dem du als Untermieter gewohnt hast. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");

        }

        $sql = "UPDATE items SET owner=0 WHERE class='SchlÃ¼ssel' AND value1={$row['houseid']}";

        db_query($sql);

        // Variablen setzen und Datenbank updaten

        $row['gold']=$goldcost-$halfgold;

        $row['gems']=$gemcost-$halfgems;

        $session['user']['gold']+=$halfgold;

        $session['user']['gems']+=$halfgems;

        $session['user']['house']=0;

        $session['user']['housekey']=0;

        $session['user']['donation']+=1;

        $sql = "UPDATE houses SET owner=0,gold={$row['gold']},gems={$row['gems']},status=2 WHERE houseid={$row['houseid']}";

        db_query($sql);

    } else {

        output("`@Gib einen Preis fÃ¼r dein Haus ein, oder lass einen Makler den Verkauf Ã¼bernehmen. Der schmierige Makler wÃ¼rde dir sofort `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine geben. ");

        output("Wenn du selbst verkaufst, kannst du vielleicht einen hÃ¶heren Preis erzielen, musst aber auf dein Geld warten, bis jemand kauft.`nAlles, was sich noch im gemeinsamen Schatz befindet, wird ");

        output("gleichmÃ¤ssig unter allen Bewohnern aufgeteilt.`n`n");

        output("`0<form action=\"houses.php?op=sell&act=sold\" method='POST'>",true);

        output("`nWieviel Gold verlangen? <input type='text' name='gold' maxlength='9'>`n",true);

        output("`nWieviele Edelsteine? <input type='text' name='gems' maxlength='9'>`n`n",true);

        output("<input type='submit' class='button' value='FÃ¼r diesen Preis verkaufen'></form>",true);

        addnav("","houses.php?op=sell&act=sold");

        addnav("An den Makler","houses.php?op=sell&act=makler");

    }

    addnav("W?ZurÃ¼ck zum Wohnviertel","houses.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");



}else if ($_GET['op']=="drin"){

    if ($_GET['id']) $session['housekey']=(int)$_GET['id'];

    if (!$session['housekey']) redirect("houses.php");

    $sql = "SELECT * FROM houses WHERE houseid=".$session['housekey']." ORDER BY houseid DESC";

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);

    if ($_GET['act']=="takekey"){

        if (!$_POST['ziel']){

            $sql = "SELECT items.owner,accounts.acctid,accounts.name AS besitzer,accounts.login FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' ORDER BY login DESC, value2 ASC";

            $result = db_query($sql) or die(db_error(LINK));

            output("<form action='houses.php?op=drin&act=takekey' method='POST'>",true);

            output("`2Wem willst du den SchlÃ¼ssel wegnehmen? <select name='ziel'>",true);

            for ($i=0;$i<db_num_rows($result);$i++){

                $item = db_fetch_assoc($result);

                if ($amt!=$item['acctid'] && $item['acctid']!=$row['owner'] && $item['besitzer']!="") output("<option value=\"".rawurlencode($item['besitzer'])."\">".preg_replace("'[`].'","",$item['besitzer'])."</option>",true);

                $amt=$item['acctid'];

            }

            output("</select>`n`n",true);

            output("<input type='submit' class='button' value='SchlÃ¼ssel abnehmen'></form>",true);

            addnav("","houses.php?op=drin&act=takekey");

        }else{

            $sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";

            $result2 = db_query($sql);

            $row2  = db_fetch_assoc($result2);

            output("`2Du verlangst den SchlÃ¼ssel von `&{$row2['name']}`2 zurÃ¼ck.`n");

            $sql = "SELECT owner FROM items WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' AND owner<>{$row['owner']} ORDER BY id ASC";

            $result = db_query($sql) or die(db_error(LINK));

            $goldgive=round($row['gold']/(db_num_rows($result)+1));

            $gemsgive=round($row['gems']/(db_num_rows($result)+1));

            systemmail($row2['acctid'],"`@SchlÃ¼ssel zurÃ¼ckverlangt!`0","`&{$session['user']['name']}`2 hat den SchlÃ¼ssel zu Haus Nummer `b{$row['houseid']}`b ({$row['housename']}`2) zurÃ¼ckverlangt. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");

            output("{$row2['name']}`2 bekommt `^$goldgive`2 Gold und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz.");

            $sql = "UPDATE items SET owner={$row['owner']},hvalue=0 WHERE owner={$row2['acctid']} AND class='SchlÃ¼ssel' AND value1={$row['houseid']}";

            db_query($sql);

            $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid={$row2['acctid']}";

            db_query($sql);

            $sql = "UPDATE houses SET gold=gold-$goldgive,gems=gems-$gemsgive WHERE houseid={$row['houseid']}";

            db_query($sql);

            $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `^nimmt {$row2['name']}`^ einen SchlÃ¼ssel ab. {$row2['name']}`^ bekommt einen Teil aus dem Schatz.')";

            db_query($sql) or die(db_error(LINK));

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="givekey"){

        if (!$_POST['ziel']){

            output("`2Einen SchlÃ¼ssel fÃ¼r dieses Haus hat:`n`n");

            $sql = "SELECT items.*,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' AND owner<>".$session['user']['acctid']." ORDER BY value2 ASC";

            $result = db_query($sql) or die(db_error(LINK));

            for ($i=0;$i<db_num_rows($result);$i++){

                $item = db_fetch_assoc($result);

                output("`c`& {$item['besitzer']}`0`c");

            }

            $sql = "SELECT value2 FROM items WHERE value1={$row['houseid']} AND class='SchlÃ¼ssel' AND owner={$row['owner']} ORDER BY id ASC";

            $result = db_query($sql) or die(db_error(LINK));

            if (db_num_rows($result)>0) {

                output("`n`2Du kannst noch `b".db_num_rows($result)."`b SchlÃ¼ssel vergeben.");

                output("<form action='houses.php?op=drin&act=givekey' method='POST'>",true);

                output("An wen willst du einen SchlÃ¼ssel Ã¼bergeben? <input name='ziel' maxlength='25'>`n", true);

                output("<input type='submit' class='button' value='Ãœbergeben'></form>",true);

                output("`n`nWenn du einen SchlÃ¼ssel vergibst, wird der Schatz des Hauses gemeinsam genutzt. Du kannst einem Mitbewohner zwar jederzeit den SchlÃ¼ssel wieder wegnehmen, ");

                output("aber er wird dann einen gerechten Anteil aus dem gemeinsamen Schatz bekommen.");

                addnav("","houses.php?op=drin&act=givekey");

            }else{

                output("`n`2Du hast keine SchlÃ¼ssel mehr Ã¼brig. Vielleicht kannst du in der JÃ¤gerhÃ¼tte noch einen nachmachen lassen?");

            }

        } else {

            if ($_GET['subfinal']==1){

                $sql = "SELECT acctid,name,login,lastip,emailaddress,dragonkills,level,sex FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";

            }else{

                $ziel = stripslashes(rawurldecode($_POST['ziel']));

                $name="%";

                for ($x=0;$x<strlen($ziel);$x++){

                    $name.=substr($ziel,$x,1)."%";

                }

                $sql = "SELECT acctid,name,login,lastip,emailaddress,dragonkills,level,sex FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";

            }

            $result2 = db_query($sql);

            if (db_num_rows($result2) == 0) {

                output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");

            } elseif(db_num_rows($result2) > 100) {

                output("`2Es gibt Ã¼ber 100 Krieger mit einem Ã¤hnlichen Namen. Bitte sei etwas genauer.");

            } elseif(db_num_rows($result2) > 1) {

                output("`2Es gibt mehrere mÃ¶gliche Krieger, denen du einen SchlÃ¼ssel Ã¼bergeben kannst.`n");

                output("<form action='houses.php?op=drin&act=givekey&subfinal=1' method='POST'>",true);

                output("`2Wen genau meinst du? <select name='ziel'>",true);

                for ($i=0;$i<db_num_rows($result2);$i++){

                    $row2 = db_fetch_assoc($result2);

                    output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);

                }

                output("</select>`n`n",true);

                output("<input type='submit' class='button' value='SchlÃ¼ssel Ã¼bergeben'></form>",true);

                addnav("","houses.php?op=drin&act=givekey&subfinal=1");

            } else {

                $row2  = db_fetch_assoc($result2);

                $sql = "SELECT owner FROM items WHERE owner=".$row2['acctid']." AND value1=".$row['houseid']." AND class='SchlÃ¼ssel' ORDER BY id ASC";

                $result = db_query($sql) or die(db_error(LINK));

                $item = db_fetch_assoc($result);

                if ($row2['login'] == $session['user']['login']) {

                    output("`2Du kannst dir nicht selbst einen SchlÃ¼ssel geben.");

                } elseif ($item['owner']==$row['owner']) {

                    output("`2{$row2['name']}`2 hat bereits einen SchlÃ¼ssel!");

                 //} elseif ($session['user']['lastip'] == $row2['lastip'] || ($session['user']['emailaddress'] == $row2['emailaddress'] && $row2['emailaddress'])){

                 } elseif ($session['user']['emailaddress'] == $row2['emailaddress'] && $row2['emailaddress']){

                    output("`2Diese Charaktere dÃ¼rfen leider nicht in dieser Weise miteinander interagieren!");

                } elseif ($row2['level']<5 && $row2['dragonkills']<1){

                    output("`2{$row2['name']}`2 ist noch nicht lange genug im Dorf, als dass du ".($row2['sex']?"ihr":"ihm")." vertrauen kÃ¶nntest. Also beschlieÃŸt du, noch eine Weile zu beobachten.");

                } else {

                    $sql = "SELECT value2 FROM items WHERE value1=".$row['houseid']." AND class='SchlÃ¼ssel' AND owner=".$row['owner']." ORDER BY id ASC LIMIT 1";

                    $result = db_query($sql) or die(db_error(LINK));

                    $knr = db_fetch_assoc($result);

                    $knr=$knr['value2'];

                    output("`2Du Ã¼bergibst `&{$row2['name']}`2 einen SchlÃ¼ssel fÃ¼r dein Haus. Du kannst den SchlÃ¼ssel zum Haus jederzeit wieder wegnehmen, aber {$row2['name']}`2 wird dann ");

                    output("einen gerechten Anteil aus dem gemeinsamen Schatz des Hauses bekommen.`n");

                    systemmail($row2['acctid'],"`@SchlÃ¼ssel erhalten!`0","`&{$session['user']['name']}`2 hat dir einen SchlÃ¼ssel zu Haus Nummer `b{$row['houseid']}`b ({$row['housename']}`2) gegeben!");

                    $sql = "UPDATE items SET owner=".$row2['acctid'].",hvalue=0 WHERE owner=".$row['owner']." AND class='SchlÃ¼ssel' AND value1=".$row['houseid']." AND value2=$knr";

                    db_query($sql);

                    $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `^gibt ".$row2['name']."`^ einen SchlÃ¼ssel.')";

                    db_query($sql) or die(db_error(LINK));

                }

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="takegold"){

        $maxtfer = $session['user']['level']*getsetting("transferperlevel",25);

        if (!$_POST['gold']){

            $transleft = getsetting("transferreceive",3) - $session['user']['transferredtoday'];

            output("`2Es befindet sich `^{$row['gold']}`2 Gold in der Schatztruhe des Hauses.`nDu darfst heute noch $transleft x bis zu `^$maxtfer`2 Gold mitnehmen.`n");

            output("`2<form action=\"houses.php?op=drin&act=takegold\" method='POST'>",true);

            output("`nWieviel Gold mitnehmen? <input type='text' name='gold' maxlength='9'>`n`n",true);

            output("<input type='submit' class='button' value='Mitnehmen'></form>",true);

            addnav("","houses.php?op=drin&act=takegold");

        }else{

            $amt=abs((int)$_POST['gold']);

            if ($amt>$row['gold']){

                output("`2So viel Gold ist nicht mehr da.");

            }else if ($maxtfer<$amt){

                output("`2Du darfst maximal `^$maxtfer`2 Gold auf einmal nehmen.");

            }else if ($amt<0){

                output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");

            }else if($session['user']['transferredtoday']>=getsetting("transferreceive",3)){

                output("`2Du hast heute schon genug Gold bekommen. Du wirst bis morgen warten mÃ¼ssen.");

            }else{

                $row['gold']-=$amt;

                $session['user']['gold']+=$amt;

                $session['user']['transferredtoday']+=1;

                $sql = "UPDATE houses SET gold={$row['gold']} WHERE houseid={$row['houseid']}";

                db_query($sql) or die(db_error(LINK));

                output("`2Du hast `^$amt`2 Gold genommen. Insgesamt befindet sich jetzt noch `^{$row['gold']}`2 Gold im Haus.");

                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `\$nimmt `^$amt`\$ Gold.')";

                db_query($sql) or die(db_error(LINK));

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="givegold"){

        $maxout = $session['user']['level']*getsetting("maxtransferout",25);

        if (!$_POST['gold']){

            $transleft = $maxout - $session['user']['amountouttoday'];

            output("`2Du darfst heute noch `^$transleft`2 Gold deponieren.`n");

            output("`2<form action=\"houses.php?op=drin&act=givegold\" method='POST'>",true);

            output("`nWieviel Gold deponieren? <input type='gold' name='gold'>`n`n",true);

            output("<input type='submit' class='button' value='Deponieren'>",true);

            addnav("","houses.php?op=drin&act=givegold");

        }else{

            $amt=abs((int)$_POST['gold']);

            if ($amt>$session['user']['gold']){

                output("`2So viel Gold hast du nicht dabei.");

            }else if($row['gold']>round($goldcost/2)){

                output("`2Der Schatz ist voll.");

            }else if($amt>(round($goldcost/2)-$row['gold'])){

                output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");

            }else if ($amt<0){

                output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");

            }else if ($session['user']['amountouttoday']+$amt > $maxout) {

                output("`2Du darfst nicht mehr als `^$maxout`2 Gold pro Tag deponieren.");

            }else{

                $row['gold']+=$amt;

                $session['user']['gold']-=$amt;

                $session['user']['amountouttoday']+= $amt;

                output("`2Du hast `^$amt`2 Gold deponiert. Insgesamt befinden sich jetzt `^{$row['gold']}`2 Gold im Haus.");

                $sql = "UPDATE houses SET gold={$row['gold']} WHERE houseid={$row['houseid']}";

                db_query($sql) or die(db_error(LINK));

                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `@deponiert `^$amt`@ Gold.')";

                db_query($sql) or die(db_error(LINK));

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="takegems"){

        if (!$_POST['gems']){

            output("`2Es befinden sich `#{$row['gems']}`2 Edelsteine in der Schatztruhe des Hauses.`n`n");

            output("`2<form action=\"houses.php?op=drin&act=takegems\" method='POST'>",true);

            output("`nWieviele Edelsteine mitnehmen? <input type='text' name='gems' maxlength='9'>`n`n",true);

            output("<input type='submit' class='button' value='Mitnehmen'></form>",true);

            addnav("","houses.php?op=drin&act=takegems");

        }else{

            $amt=abs((int)$_POST['gems']);

            if ($amt>$row['gems']){

                output("`2So viele Edelsteine sind nicht mehr da.");

            }else if ($amt<0){

                output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");

            }else{

                $row['gems']-=$amt;

                $session['user']['gems']+=$amt;

                $sql = "UPDATE houses SET gems={$row['gems']} WHERE houseid={$row['houseid']}";

                db_query($sql);

                output("`2Du hast `#$amt`2 Edelsteine genommen. Insgesamt befinden sich jetzt noch `#{$row['gems']}`2 Edelsteine im Haus.");

                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `\$nimmt `#$amt`\$ Edelsteine.')";

                db_query($sql) or die(db_error(LINK));

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="givegems"){

        if (!$_POST['gems']){

            output("`2<form action=\"houses.php?op=drin&act=givegems\" method='POST'>",true);

            output("`nWieviele Edelsteine deponieren? <input type='text' name='gems' maxlength='9'>`n`n",true);

            output("<input type='submit' class='button' value='Deponieren'></form>",true);

            addnav("","houses.php?op=drin&act=givegems");

        }else{

            $amt=abs((int)$_POST['gems']);

            if ($amt>$session['user']['gems']){

                output("`2So viele Edelsteine hast du nicht.");

            }else if($row['gems']>=round($gemcost/2)){

                output("`2Der Schatz ist voll.");

            }else if($amt>(round($gemcost/2)-$row['gems'])){

                output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");

            }else if ($amt<0){

                output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");

            }else{

                $row['gems']+=$amt;

                $session['user']['gems']-=$amt;

                $sql = "UPDATE houses SET gems={$row['gems']} WHERE houseid={$row['houseid']}";

                db_query($sql);

                output("`2Du hast `#$amt`2 Edelsteine deponiert. Insgesamt befinden sich jetzt `#{$row['gems']}`2 Edelsteine im Haus.");

                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",'/me `@deponiert `#$amt`@ Edelsteine.')";

                db_query($sql) or die(db_error(LINK));

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="rename"){

        if (!$_POST['housename']){

            output("`2Das Haus umbenennen kostet `^1000`2 Gold und `#1`2 Edelstein.`n`n");

            output("`0<form action=\"houses.php?op=drin&act=rename\" method='POST'>",true);

            output("`nGib einen neuen Namen fÃ¼r dein Haus ein: <input name='housename' maxlength='25'>`n",true);

            output("<input type='submit' class='button' value='Umbenennen'></form>",true);

            addnav("","houses.php?op=drin&act=rename");

        }else{

            if ($session['user']['gold']<1000 || $session['user']['gems']<1){

                output("`2Das kannst du nicht bezahlen.");

            }else{

                output("`2Dein Haus `@{$row['housename']}`2 heiÃŸt jetzt `@".stripslashes($_POST['housename'])."`2.");

                $sql = "UPDATE houses SET housename='".addslashes(stripslashes($_POST['housename']))."' WHERE houseid={$row['houseid']}";

                db_query($sql);

                $session['user']['gold']-=1000;

                $session['user']['gems']-=1;

            }

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="desc"){

        if (!$_POST['desc']){

            output("`2Hier kannst du die Beschreibung fÃ¼r dein Haus Ã¤ndern.`n`nDie aktuelle Beschreibung lautet:`0{$row['description']}`0`n");

            output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);

            output("`n`2Gebe eine Beschreibung fÃ¼r dein Haus ein:`n<input name='desc' maxlength='250' size='50'>`n",true);

            output("<input type='submit' class='button' value='Abschicken'></form>",true);

            addnav("","houses.php?op=drin&act=desc");

        }else{

            output("`2Die Beschreibung wurde geÃ¤ndert.`n`0".stripslashes($_POST['desc'])."`2.");

            $sql = "UPDATE houses SET description='".addslashes(stripslashes($_POST['desc']))."' WHERE houseid={$row['houseid']}";

            db_query($sql);

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="logout"){

        if ($session['user']['housekey']!=$session['housekey']){

            $sql = "UPDATE items SET hvalue=".$session['housekey']." WHERE value1=".(int)$session['housekey']." AND owner=".$session['user']['acctid']." AND class='SchlÃ¼ssel'";

            db_query($sql) or die(sql_error($sql));

        }

        debuglog("logged out in a house ");

        $session['user']['location']=2;

        $session['user']['loggedin']=0;

        $sql = "UPDATE accounts SET loggedin=0,location=2 WHERE acctid = ".$session['user']['acctid'];

        db_query($sql) or die(sql_error($sql));

        $session=array();

        redirect("index.php");

// actions by items/furniture

    }else if ($_GET['act']=="mirror"){

        switch(e_rand(1,5)){

            case 1:

            output("`2Du blickst in den Spiegel, in der Hoffnung etwas erfreuliches zu sehen. ");

            break;

            case 2:

            output("`2Dir starrt ".($session['user']['sex']?"eine vÃ¶llig unbekannte Frau":"ein vÃ¶llig unbekannter Mann")." entgegen. ");

            break;

            case 3:

            if ($session['user']['charm']<10) output("`2Du fragst dich, warum dieses hÃ¤ÃŸliche Bild im Haus hÃ¤ngt. ");

            if ($session['user']['charm']>=10 and $session['user']['charm']<50) output("`2Naja, wer auch immer dieses Bild gemalt hat, hat Talent, sollte aber noch etwas Ã¼ben. ");

            if ($session['user']['charm']>=50) output("`2Das ist ein wirklich tolles Bild von ".($session['user']['sex']?"einer Frau":"einem Mann")."! ");

            break;

            case 4:

            output("`2Erstaunlicher Apparat. ");

            break;

            case 5:

            output("`2Du verbringst eine ganze Weile vor dem Spiegel. ");

            if (e_rand(1,3)==2 && $sesion['user']['turns']>0){

                output("Dabei merkst du nicht, wie die Zeit vergeht. Du vertrÃ¶delst einen Waldkampf! ");

                $session['user']['turns']--;

            }

            break;

        }

        $was=e_rand(1,3);

        if ($was==1 && $session['user']['turns']>0){

            $session['user']['charm']--;

            if ($session['user']['charm']<=0) $session['user']['charm']=0;

            output("`nDu `4verlierst`2 einen Charmepunkt!");

        }else if ($was==3 && $session['user']['turns']>0){

            $session['user']['charm']++;

            output("`nDu `@bekommst`2 einen Charmepunkt.");

        }else if ($session['user']['turns']<=0){

            output("`nDu hast heute keine Zeit mehr, dich um dein Ã„uÃŸeres zu kÃ¼mmern.");

        }else{

        }

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

    }else if ($_GET['act']=="regal"){

        if ($_GET['regal']=="sauf"){

            if ($session['user']['drunkenness']>66){

                output("`tDu schwankst zum Weinregal und nimmst zwei - nein, es ist doch nur eine - Flasche heraus. ");

                output("Leider bist du zu betrunken um sie aufzubekommen. Um trotzdem an den Inhalt zu gelangen, zerschmetterst du den Flaschenhals am Regal`n`n");

                output("Pech gehabt. Die gesamte Flasche ist hin und der leckere Wein verteilt sich auf dem Boden.`n");

                $was=e_rand(1,4);

                if ($was==1){

                    $session['user']['hitpoints']-=2;

                    output("`tBeim Auflecken verletzt du dich auch noch an den HÃ¤nden und an der Zunge!`nDu verlierst 2 Lebenspunkte.");

                }else if ($was==4){

                    $session['user']['hitpoints']--;

                    output("`tDass du dich dabei an der Hand verletzt hast, nimmst du in deinem Rausch nur am Rande wahr.`nDu verlierst einen Lebenspunkt.");

                }else{

                    output("Dir ist nichts passiert und so torkelst du zurÃ¼ck, um nicht noch eine weitere Flasche zu zerstÃ¶ren.");

                }

                if ($session['user']['hitpoints']<=0){

                    $session['user']['alive']=0;

                    $session['user']['reputation']--;

                    output("`n`4`bDu bist tot!`b");

                    addnews("`&".$session['user']['name']."`5 hat sich vÃ¶llig betrunken beim Ã–ffnen einer Weinflasche tÃ¶dlich verletzt.");

                    addnav("Zu den News","news.php");

                }else{

                    addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

                }

            }else{

                addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

                $session['user']['drunkenness']+=33;

                $session['bufflist']['101'] = array("name"=>"`#Rausch","rounds"=>8,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.2,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");

                output("`5Du kannst der Versuchung nicht widerstehen und bist der Meinung, dass ein Schluck Wein niemals schaden kann. Du nimmst eine Flasche aus dem Regal und setzt zu einem Schluck an. Nunja, wo du schonmal dabei bist...");

            }

        }else{

            output("`tDu gehst zum Weinregal und betrachtest die vielen Flaschen. Unter dem Weinregal ist der Boden von getrocknetem Wein, der fast wie Blut aussieht, brÃ¤unlich verfÃ¤rbt.");

            $drunkenness = array(-1=>"absolut nÃ¼chtern", 0=>"ziemlich nÃ¼chtern",     1=>"kaum berauscht", 2=>"leicht berauscht", 3=>"angetrunken", 4=>"leicht betrunken", 5=>"betrunken", 6=>"ordentlich betrunken", 7=>"besoffen", 8=>"richtig zugedrÃ¶hnt", 9=>"fast bewusstlos"    );

            $drunk = round($session['user']['drunkenness']/10-.5,0);

                if ($drunkenness[$drunk]){

                    output("`n`n`5Du fÃ¼hlst dich ".$drunkenness[$drunk]."`n");

                }else{

                    output("`n`n`5Du fÃ¼hlst dich nicht mehr.`n`n");

                }

            addnav("Wein trinken","houses.php?op=drin&act=regal&regal=sauf");

            addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

        }

    }else if ($_GET['act']=="tv"){

        output("`9Der seltsame Kasten knistert und surrt, bevor er tatsÃ¤chlich einen Bericht Ã¼ber ein aktuelles Ereignis zeigt:`n`n");

             $sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";

             $result = db_query($sql) or die(db_error(LINK));

             $row6 = db_fetch_assoc($result);

             output("`n`c`i".$row6['newstext']."`i`c`n`n");

        addnav("Aktualisieren","houses.php?op=drin&act=tv");

        addnav("ZurÃ¼ck zum Haus","houses.php?op=drin");

// end items

    }else{

        output("`2`b`c{$row['housename']}`c`b`n");

        if ($row['description']) output("`0`c{$row['description']}`c`n");

        output("`2Du und deine Mitbewohner haben `^{$row['gold']}`2 Gold und `#{$row['gems']}`2 Edelsteine im Haus gelagert.`n");

        if (getsetting('activategamedate','0')==1){

            output("Wir schreiben den `^".getgamedate()."`2.`n");

            if ($row['datum']!="0000-00-00") output("Dieses Haus ist `@".round((strtotime(getsetting("gamedate","0005-01-01"))-strtotime($row['datum']))/60/60/24)." `2Tage alt.`n");

        }

        output ("Es ist jetzt `^".getgametime()."`2 Uhr.`n`n");

        if ($session['user']['petid']>0 && $session['user']['housekey']==$row['houseid']) {

            $pettime = strtotime($session['user']['petfeed'])-time();

            output("<table><tr><td>`2Dein Haustier: </td><td>".grafbar(24*3600,$pettime,100,10)."</td></tr></table>`n",true);

        }

        viewcommentary("house-".$row['houseid'],"Mit Mitbewohnern reden:",30,"sagt");

        output("`n`n`n<table border='0'><tr><td>`2`bDie SchlÃ¼ssel:`b `0</td><td>`2`bExtra Ausstattung`b</td></tr><tr><td valign='top'>",true);

        $sql = "SELECT items.*,accounts.acctid AS aid,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1=".$row['houseid']." AND class='SchlÃ¼ssel' ORDER BY id ASC";

        $result = db_query($sql) or die(db_error(LINK));

        for ($i=1;$i<=db_num_rows($result);$i++){

            $item = db_fetch_assoc($result);

            if ($item['besitzer']==""){

                output("`n`2$i: `4`iVerloren`i`0");

            }else{

                output("`n`2$i: `&".$item['besitzer']."`0");

            }

            if ($item['aid']==$row['owner']) output(" (der EigentÃ¼mer) ");

            if ($item['hvalue']>0 && $item['owner']>0) output(" `ischlÃ¤ft hier`i");

        }

        if (db_num_rows(db_query("SELECT id FROM items WHERE owner=".$row['owner']." AND value1<>".$row['houseid']." AND hvalue>0 AND class='SchlÃ¼ssel'"))==0 && db_num_rows(db_query("SELECT acctid FROM accounts WHERE acctid=".$row['owner']." AND location=2"))>0) output("`nDer EigentÃ¼mer schlÃ¤ft hier");

        output("</td><td valign='top'>",true);

// items

        $sql = "SELECT * FROM items WHERE value1=".$row['houseid']." AND class='MÃ¶bel' ORDER BY class,id ASC";

        $result = db_query($sql) or die(db_error(LINK));

        for ($i=1;$i<=db_num_rows($result);$i++){

            $item = db_fetch_assoc($result);

            output("`n`&{$item['name']}`0 (`i{$item['description']}`i)");

            if ($item['name']=="GroÃŸer Spiegel") addnav("Spiegel","houses.php?op=drin&act=mirror");

            if ($item['name']=="Weinregal") addnav("Weinregal","houses.php?op=drin&act=regal");

            if ($item['name']=="Fern Seher") addnav("Fern Seher","houses.php?op=drin&act=tv");

        }

// end items

        output("</td></tr></table>",true);

        addnav("Gold");

        addnav("Deponieren","houses.php?op=drin&act=givegold");

        addnav("Mitnehmen","houses.php?op=drin&act=takegold");

        addnav("Edelsteine");

        addnav("Deponieren","houses.php?op=drin&act=givegems");

        addnav("Mitnehmen","houses.php?op=drin&act=takegems");

        if ($session['user']['house']==$session['housekey']){

            addnav("SchlÃ¼ssel");

            addnav("Vergeben","houses.php?op=drin&act=givekey");

            addnav("n?ZurÃ¼cknehmen","houses.php?op=drin&act=takekey");

        }

        addnav("Sonstiges");

        if ($session['user']['house']==$session['housekey']){

            addnav("Haus umbenennen","houses.php?op=drin&act=rename");

            addnav("Beschreibung Ã¤ndern","houses.php?op=drin&act=desc");

        }

        addnav("Log Out","houses.php?op=drin&act=logout");

        addnav("W?ZurÃ¼ck zum Wohnviertel","houses.php");

        addnav("ZurÃ¼ck zum Dorf","village.php");

    }

}else if ($_GET['op']=="enter"){

    output("`@Du hast Zugang zu folgenden HÃ¤usern:`n`n");

    $sql = "UPDATE items SET hvalue='0' WHERE hvalue>'0' AND owner='".$session['user']['acctid']."' AND class='SchlÃ¼ssel'";

    db_query($sql) or die(sql_error($sql));

    $sql = "SELECT * FROM items WHERE owner='".$session['user']['acctid']."' AND class='SchlÃ¼ssel' ORDER BY id ASC";

    $result = db_query($sql) or die(db_error(LINK));

    output("<table cellpadding='2' align='center'><tr><td>`bHaus Nr.`b</td><td>`bName`b</td></tr>",true);

    $ppp=25; // Player Per Page +1 to display

    if (!$_GET['limit']){

        $page=0;

    }else{

        $page=(int)$_GET['limit'];

        addnav("Vorherige StraÃŸe","houses.php?op=enter&limit=".($page-1)."");

    }

    $limit="".($page*$ppp).",".($ppp+1);

    if ($session['user']['house']>0 && $session['user']['housekey']>0){

        $sql = "SELECT houseid,housename FROM houses WHERE houseid=".$session['user']['house']." ORDER BY houseid DESC LIMIT $limit";

        $result2 = db_query($sql) or die(db_error(LINK));

        $row2 = db_fetch_assoc($result2);

        output("<tr><td align='center'>{$row2['houseid']}</td><td><a href='houses.php?op=drin&id={$row2['houseid']}'>{$row2['housename']}</a> (dein eigenes)</td></tr>",true);

        addnav("","houses.php?op=drin&id={$row2['houseid']}");

    }else if ($session['user']['house']>0 && $session['user']['housekey']==0){

        output("<tr><td colspan=2 align='center'>`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>",true);

    }

    if (db_num_rows($result)>$ppp) addnav("NÃ¤chste Seite","houses.php?op=enter&limit=".($page+1)."");

    if (db_num_rows($result)==0){

        output("<tr><td colspan=4 align='center'>`&`iDu hast keinen SchlÃ¼ssel`i`0</td></tr>",true);

    }else{

        $rebuy=0;

        for ($i=0;$i<db_num_rows($result);$i++){

            $item = db_fetch_assoc($result);

            if ($item['value1']==$session['user']['house'] && $session['user']['housekey']==0) $rebuy=1;

            $bgcolor=($i%2==1?"trlight":"trdark");

            $sql = "SELECT houseid,housename FROM houses WHERE houseid={$item['value1']} ORDER BY houseid DESC";

            $result2 = db_query($sql) or die(db_error(LINK));

            $row2 = db_fetch_assoc($result2);

            if ($amt!=$item['value1'] && $item['value1']!=$session['user']['house']){

                output("<tr class='$bgcolor'><td align='center'>{$row2['houseid']}</td><td><a href='houses.php?op=drin&id={$row2['houseid']}'>{$row2['housename']}</a></td></tr>",true);

                addnav("","houses.php?op=drin&id={$row2['houseid']}");

            }

            $amt=$item['value1'];

        }

    }

    output("</table>",true);

    if ($rebuy==1) addnav("Verkauf rÃ¼ckgÃ¤ngig","houses.php?op=buy&id=".$session['user']['house']);

    if (getsetting("dailyspecial",0)=="Waldsee"){

        output("`n`n`@WÃ¤hrend du deine SchlÃ¼ssel suchst, fÃ¤llt dir ein kleiner Trampelpfad auf...");

        addnav("Trampelpfad","paths.php?ziel=forestlake");

    }

    addnav("ZurÃ¼ck zum Dorf","village.php");

    addnav("W?ZurÃ¼ck zum Wohnviertel","houses.php");



}else{

    output("`@`b`cDas Wohnviertel`c`b`n`n");

    $session['housekey']=0;

    $sql = "SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND class='SchlÃ¼ssel' ORDER BY id ASC";

    $result2 = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result2)>0 || $session['user']['housekey']>0) addnav("Haus betreten","houses.php?op=enter");

    output("Du verlÃ¤sst den Dorfplatz und schlenderst Richtung Wohnviertel. In diesem schÃ¶n angelegten Teil des Dorfes siehst du einige Baustellen zwischen bewohnten ");

    output("und unbewohnten HÃ¤usern. Hier wohnen also die Helden...`n`n");

    if ($_POST['search']>""){

        if ($_GET['search']>"" || $_GET['search']>"") $_POST['search']=$_GET['search'];

        if (strcspn($_POST['search'],"0123456789")<=1){

            $search="houseid=".intval($_POST['search'])." AND ";

        }else{

            $search="%";

            for ($x=0;$x<strlen($_POST['search']);$x++){

                $search .= substr($_POST['search'],$x,1)."%";

            }

            $search="housename LIKE '".$search."' AND ";

        }

    }else{

        $search="";

    }

    $ppp=30; // Player Per Page +1 to display

    if (!$_GET['limit']){

        $page=0;

    }else{

        $page=(int)$_GET['limit'];

        addnav("Vorherige StraÃŸe","houses.php?limit=".($page-1)."&search={$_POST['search']}");

    }

    $limit="".($page*$ppp).",".($ppp+1);

    //DOC JOKUS/TWEANS: Performancekillenden Schleifenquery entsorgt

    $sql = "SELECT houses.*,accounts.name AS schluesselinhaber FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE $search status<100 ORDER BY houseid ASC LIMIT $limit";

    output("<form action='houses.php' method='POST'>Nach Hausname oder Nummer <input name='search' value='{$_POST['search']}'> <input type='submit' class='button' value='Suchen'></form>",true);

    addnav("","houses.php");

    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentÃ¼mer`b</td><td>`bStatus`b</td></tr>",true);

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)>$ppp) addnav("NÃ¤chste StraÃŸe","houses.php?limit=".($page+1)."&search={$_POST['search']}");

    if (db_num_rows($result)==0){

          output("<tr><td colspan=4 align='center'>`&`iEs gibt noch keine HÃ¤user`i`0</td></tr>",true);

    }else{

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            $bgcolor=($i%2==1?"trlight":"trdark");

            output("<tr class='$bgcolor'><td align='right'>".$row['houseid']."</td><td><a href='houses.php?op=bio&id=".$row['houseid']."'>".$row['housename']."</a></td><td>",true);

            addnav("","houses.php?op=bio&id=".$row['houseid']);

    //DOC JOKUS/TWEANS: Performancekillenden Schleifenquery entsorgt

            output($row['schluesselinhaber']."</td><td>".$status[$row['status']]."</td></tr>",true);

        }

    }

    $output.="</table>";

    if ($session['user']['housekey']) {

        output("`nStolz schwingst du den SchlÃ¼ssel zu deinem Haus im Gehen hin und her.");

    }

    if ($session['user']['house'] && $session['user']['housekey']) {

        addnav("Haus verkaufen","houses.php?op=sell");

    } else {

        if ($session['user']['house']<=0) addnav("Haus kaufen","houses.php?op=buy");

        addnav("Haus bauen","houses.php?op=build");

    }

    if (getsetting("pvp",1)==1) addnav("Einbrechen","houses.php?op=einbruch");

    if (@file_exists("well.php")) addnav("Dorfbrunnen","well.php");

    if ($session['user']['superuser']>1) addnav("Admin Grotte","superuser.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");

}



if ($fight){

    if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){

        $_GET['skill']="";

        if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);

        $session['bufflist']=array();

        output("`&Die ungewohnte Umgebung verhindert den Einsatz deiner besonderen FÃ¤higkeiten!`0");

    }

    include "battle.php";

    if ($victory){

        addnav("Weiter zum Haus","houses.php?op=einbruch2&id={$session['housekey']}");

        addnav("ZurÃ¼ck zum Dorf","village.php");

        // check for pet

        if ($badguy['creaturename']=='Stadtwache') {

            output("`n`#Du hast die Stadtwache besiegt und der Weg zum Haus ist frei!`nDu bekommst ein paar Erfahrungspunkte.");

            $session['user']['experience'] += $session['user']['level']*10;

            $session['user']['turns']--;

        }else {

            output('`n`#'.$badguy['creaturename'].'`# zieht sich jaulend zurÃ¼ck und gibt den Weg zum Haus frei!');

        }

        $badguy=array();

    }elseif ($defeat){

        if ($badguy['creaturename']=='Stadtwache') {

            output("`n`\$Die Stadtwache hat dich besiegt. Du bist tot!`nDu verlierst 10% deiner Erfahrungspunkte, aber kein Gold.`nDu kannst morgen wieder kÃ¤mpfen.");

            $session['user']['hitpoints']=1;

            //$session['user']['alive']=0;

            $session['user']['experience']=round($session['user']['experience']*0.9);

            $session['user']['badguy']="";

            $session['user']['jail']=true;

            $session['user']['location']=9;

            addnews("`%".$session['user']['name']."`3 wurde von der Stadtwache bei einem Einbruch festgenommen.");

            addnav("Am Pranger","jail2.php");

        }else {

            output('`n`$'.$badguy['creaturename'].'`$ hat dich besiegt. Du liegst schwer verletzt am Boden!`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.');

            $session['user']['hitpoints'] = 1;

            $session['user']['charm'] -= 3;

            addnews("`%".$session['user']['name']."`3 stieÃŸ bei einem Einbruch auf unerwartete Gegenwehr und verletzte sich schwer.");

            addnav('Davonkriechen',"houses.php?op=leave");

        }

    }else{

        fightnav(false,true);

    }

}



page_footer();

?>

