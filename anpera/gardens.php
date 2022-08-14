
ï»¿<?php



// 20140816



// gardenflirt 1.0 by anpera

// uses 'charisma' entry in database to determine how far a love goes, and 'marriedto' to know who with whom. ;)

// no changes necessary in database

// some changes in newday.php, hof.php, dragon.php, and inn.php required and in user.php optional!

// See http://www.anpera.net/forum/viewforum.php?f=27 for details



require_once("common.php");



$buff = array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst deine groÃŸe Liebe!`0","defmod"=>1.2,"roundmsg"=>"Deine groÃŸe Liebe lÃ¤sst dich an deine Sicherheit denken!","activate"=>"defense");



page_header("Die GÃ¤rten");

if ($_GET['op']=="flirt1"){

// output("`b`c`\$IN ÃœBERARBEITUNG`b`c`0`n");

    if ($session['user']['seenlover']){

          $sql = "SELECT name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";

          $result = db_query($sql) or die(db_error(LINK));

        $row = db_fetch_assoc($result);

        $partner=$row['name'];

        if ($partner=="") $partner = $session['user']['sex']?"`^Seth`0":"`5Violet`0";

        output("Du versuchst, dich in Gedanken auf einen heiÃŸen Flirt vorzubereiten, aber irgendwie bekommst du`3 $partner`0 nicht aus dem Kopf. Vielleicht solltest du bis morgen warten.");

         addnav("ZurÃ¼ck zum Garten","gardens.php");

    } else {

        if (isset($_POST['search']) || $_GET['search']>""){

            if ($_GET['search']>"") $_POST['search']=$_GET['search'];

            $search="%";

            for ($x=0;$x<strlen($_POST['search']);$x++){

                $search .= substr($_POST['search'],$x,1)."%";

            }

            $search="name LIKE '".$search."' AND ";

        }else{

            $search="";

        }

        $ppp=25; // Player Per Page to display

        if (!$_GET['limit']){

            $page=0;

        }else{

            $page=(int)$_GET['limit'];

            addnav("Vorherige Seite","gardens.php?op=flirt1&limit=".($page-1)."&search=$_POST[search]");

        }

        $limit="".($page*$ppp).",".($ppp+1);

        if ($session['user']['marriedto']==4294967295) output("Du denkst nochmal Ã¼ber deine Ehe mit ".($session['user']['sex']?"`^Seth`0":"`5Violet`0")." nach und Ã¼berlegst, ob du ".($session['user']['sex']?"ihn":"sie")." in der Kneipe besuchen sollst, oder fÃ¼r wen du diese Ehe aufs Spiel setzen wÃ¼rdest.`n");

        if($session['user']['charme']==4294967295) output("Du Ã¼berlegst dir, dass du dir mal wieder etwas Zeit fÃ¼r ".($session['user']['sex']?"deinen Mann":"deine Frau")." nehmen solltest. WÃ¤hrend du ".($session['user']['sex']?"ihn":"sie")." im Garten suchst, stellst du aber fest, dass der Rest der ".($session['user']['sex']?"MÃ¤nner":"Frauen")." hier auch nicht zu verachten ist.`n");

        output("FÃ¼r wen entscheidest du dich?`n`n");

        output("<form action='gardens.php?op=flirt1' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);

        addnav("","gardens.php?op=flirt1");

          $sql = "SELECT acctid,name,sex,level,race,login,marriedto,charisma FROM accounts WHERE

        $search

        (locked=0) AND

        (sex <> ".$session['user']['sex'].") AND

        (alive=1) AND

        (acctid <> ".$session['user']['acctid'].") AND

        (laston > '".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -346000 sec"))."' OR (charisma=4294967295 AND acctid=".$session['user']['marriedto'].") )

        ORDER BY (acctid=".$session['user']['marriedto'].") DESC, charm DESC LIMIT $limit";

          $result = db_query($sql) or die(db_error(LINK));

        output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>",true);

        output(($session['user']['sex']?"<img src=\"images/male.gif\">":"<img src=\"images/female.gif\">")."</td><td><b>Name</b></td><td><b>Level</b></td><td><b>Rasse</b></td><td><b>Status</b><td><b>Ops</b></td></tr>",true);

        if (db_num_rows($result)>$ppp) addnav("NÃ¤chste Seite","gardens.php?op=flirt1&limit=".($page+1)."&search=$_POST[search]");

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

              $biolink="bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);

              addnav("", $biolink);

            if ($session['user']['charisma']<=$row['charisma']) $flirtnum=$session['user']['charisma'];

            if ($row['charisma']<$session['user']['charisma']) $flirtnum=$row['charisma'];

              output("<tr class='".($i%2?"trlight":"trdark")."'><td></td><td>$row[name]</td><td>$row[level]</td><td>",true);

            output($colraces[$row['race']]);

            output("</td><td>",true);

            if ($session['user']['acctid']==$row['marriedto'] && $session['user']['marriedto']==$row['acctid']){

                if ($session['user']['charisma']==4294967295 && $row['charisma']==4294967295){

                    output("`@`bDein".($session['user']['sex']?" Mann":"e Frau")."!`b`0");

                }else if ($flirtnum>=5){

                    output("`\$Heiratsantrag!`0");

                } else {

                    output("`^$flirtnum von ".$session['user']['charisma']." Flirts erwidert!`0");

                }

            } else if ($session['user']['acctid']==$row['marriedto']) {

                output("Flirtet ".$row['charisma']." mal mit dir");

            } else if ($session['user']['marriedto']==$row['acctid']) {

                output("Deine letzten ".$session['user']['charisma']." Flirts");

            } else if ($row['marriedto']==4294967295 || $row['charisma']==4294967295){

                output("`iVerheiratet`i");

            } else {

                output("-");

            }

            output("</td><td>[ <a href='$biolink'>Bio</a> | <a href='gardens.php?act=flirt&name=".rawurlencode($row['login'])."'>Flirten</a> ]</td></tr>",true);

            addnav("","gardens.php?act=flirt&name=".rawurlencode($row['login']));

        }

        output("</table>",true);

        addnav("ZurÃ¼ck zum Garten","gardens.php");

    }

} else if ($_GET['act']=="flirt"){

     if ($session['user']['goldinbank']>0) $getgold=round($session['user']['goldinbank']/2);

     $sql = "SELECT acctid,name,experience,charm,charisma,lastip,emailaddress,race,marriedto,uniqueid FROM accounts WHERE login=\"$_GET[name]\"";

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)>0){

        $row = db_fetch_assoc($result);

        if ($session['user']['charisma']<=$row['charisma']) $flirtnum=$session['user']['charisma'];

        if ($row['charisma']<$session['user']['charisma']) $flirtnum=$row['charisma'];

        if (ac_check($row)){

        //if ($session[user][lastip]==$row[lastip] || ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress])){

            output("`\$`bDas geht doch nicht!!`b Du kannst doch nicht mit deinen eigenen Charakteren oder deiner eigenen Familie flirten!");

             addnav("ZurÃ¼ck zum Garten","gardens.php");

        } else if (($session['user']['race']==2 && $row['race']==4) || ($session['user']['race']==4 && $row['race']==2)){

            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session['user']['sex']?"ihn":"sie")." eine Weile. Bei nÃ¤herer Betrachtung stellst du aber fest, dass Elfen und Zwerge vielleicht doch niemals zusammen passen werden.");

            output(" So verlÃ¤sst du den Garten.");

        } else if ($session['user']['turns']<1){

            output("Als `6$row[name]`0 endlich im Garten auftaucht, fÃ¼hlst du dich plÃ¶tzlich vom vielen KÃ¤mpfen so erledigt und geschwÃ¤cht, dass du es fÃ¼r besser hÃ¤ltst, mit dem Flirten bis morgen zu warten.`nDu hast deine Runden fÃ¼r heute aufgebraucht. ");

        } else if ($session['user']['charm']<=1 && $session['user']['charisma']!=4294967295){

            output("Du nÃ¤herst dich `6$row[name]`0 und mit dem Charme einer Blattlaus sprichst du ".($session['user']['sex']?"ihn":"sie")." an. Schon fast beleidigt dreht sich $row[name] um und stapft davon.`nDu solltest etwas an deiner Ausstrahlung arbeiten...");

        } else if ($row['charm']<=1 && $session['user']['charisma']!=4294967295){

            output("Du nÃ¤herst dich `6$row[name]`0. Je nÃ¤her du ".($session['user']['sex']?"ihm":"ihr")." kommst, umso hÃ¤sslicher kommt ".($session['user']['sex']?"er":"sie")." dir vor. Am Ende wirkt ".($session['user']['sex']?"er":"sie")." so abstoÃŸend auf dich, dass du einfach vorbei zurÃ¼ck ins Dorf lÃ¤ufst.");

        } else if (abs($row['charm']-$session['user']['charm'])>10 && $session['user']['charisma']!=4294967295){

            output("Du nÃ¤herst dich `6$row[name]`0. Ihr beginnt ein GesprÃ¤ch, aber irgendwie redet ihr aneinander vorbei. Ein richtiger Flirt entwickelt sich nicht. Du beschlieÃŸt, es spÃ¤ter nochmal zu versuchen und machst dich auf den Weg zurÃ¼ck ins Dorf.");

        } else if ($session['user']['drunkenness']>66){

            output("Du entdeckst `6$row[name]`0 im Schatten unter einer Gruppe BÃ¤ume und machst dich sofort daran, ".($session['user']['sex']?"ihn":"sie")." mit deiner Alefahne vollzulallen. Als ".($session['user']['sex']?"er":"sie")." Ã¼berhaupt ");

            output("nicht reagiert und immer noch irgendwie auf den Boden zu starren scheint, willst du ".($session['user']['sex']?"seinen":"ihren")." Kopf heben - und greifst voll in das DornengestrÃ¼pp vor dir.`n");

            output("Du hast in deinem Rausch diesen Busch fÃ¼r $row[name] gehalten!! Vielleicht ist es besser, erst etwas auszunÃ¼chtern, bevor du es nochmal versuchst.`n`n");

            output("`^Dieser Irrtum hat dich einen Waldkampf und einen Charmepunkt gekostet!");

            $session['user']['turns']-=1;

            $session['user']['charm']-=1;

        } else if (($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295) && ($row['marriedto']==4294967295 || $row['charisma']==4294967295)) { // MÃ¶glichkeiten, wenn beide verheiratet

            if ($session['user']['marriedto']==$row['acctid'] && $session['user']['acctid']==$row['marriedto']){

                output("`%Du fÃ¼hrst ".($session['user']['sex']?"deinen Mann":"deine Frau")." `6$row[name]`% in den Garten aus und ihr nehmt euch etwas Zeit fÃ¼reinander. ");

                output("`nDu bekommst einen Charmepunkt.");

                $session['bufflist']['lover']=$buff;

                $session['user']['charm']++;

                $session['user']['seenlover']=1;

            } else if ($session['user']['charm']==$row['charm']){

                output("`%Du nÃ¤herst dich `6$row[name]`%. Sofort entsteht ein heftiger Flirt und ein angeregtes GesprÃ¤ch. Du verstehst dich einfach blendend mit `6$row[name]`%!");

                output(" Ihr verzieht euch eine Weile an einen etwas abseits gelegenen Ort");

                output(" und verbringt ein paar sehr schÃ¶ne Stunden miteinander. Da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird.");

                output("`n`nIhr bekommt beide einen Charmepunkt!");

                $session['user']['charm']+=1;

                $session['user']['seenlover']=1;

                $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='$row[acctid]'";

                db_query($sql);

                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar wunderschÃ¶ne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor eurem Ehepartner verborgen.");

            } else {

                output("`%Du nÃ¤herst dich `6$row[name]`% und fÃ¤ngst an zu flirten was das Zeug hÃ¤lt. `6$row[name]`% steigt darauf ein, ");

                switch(e_rand(1,4)){

                    case 1:

                    case 2:

                    output("`% und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird.");

                    output("`n`nIhr VERLIERT beide einen Charmepunkt, da ihr euer schlechtes Gewissen nicht vor eurem Ehepartner verbergen kÃ¶nnt!");

                    $sql = "UPDATE accounts SET charm=charm-1 WHERE acctid='$row[acctid]'";

                    $session['user']['charm']-=1;

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt VERLOREN, da euer schlechtes Gewissen eurem Ehepartner nicht verborgen blieb.");

                    db_query($sql);

                    $session['user']['seenlover']=1;

                    break;

                    case 3:

                    output("`% und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird.");

                    output("`n`nIhr bekommt beide einen Charmepunkt!");

                    $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='$row[acctid]'";

                    $session['user']['charm']+=1;

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor euren Ehepartnern verborgen.");

                    db_query($sql);

                    $session['user']['seenlover']=1;

                    break;

                    case 4:

                    output(" aber ihr werdet bei eurem VergnÃ¼gen von ".($session['user']['sex']?"deinem Mann":"deiner Frau")." erwischt.`nDie Katastrophe ist komplett.`0`n`n".($session['user']['sex']?"Dein Mann":"Deine Frau")." verlÃ¤sst dich");

                    if ($session['user']['charisma']==4294967295){

                        output(" und bekommt 50% deines VermÃ¶gens von der Bank zugesprochen");

                        $sql = "UPDATE accounts SET marriedto=0,charisma=0,goldinbank=goldinbank+$getgold WHERE acctid='{$session['user']['marriedto']}'";

                        db_query($sql);

                        systemmail($session['user']['marriedto'],"`\$Scheidung!`0","`6Du hast `&{$session['user']['name']}`6 mit `&{$row[name]} im Garten erwischt und verlÃ¤sst ".($session['user']['sex']?"sie":"ihn").".`nDir werden `^$getgold`6 Gold von deinem ehemaligen Ehepartner zugesprochen.");

                        $session['user']['goldinbank']-=$getgold;

                    }

                    output(".`nDu verlierst einen Charmepunkt.");

                    $session['user']['marriedto']=$row['acctid'];

                    $session['user']['charisma']=1;

                    $session['user']['seenlover']=1;

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir geflirtet und wurde dabei von ".($session['user']['sex']?"ihrem Mann":"seiner Frau")." erwischt.");

                    $session['user']['charm']-=1;

                    addnews("`\$".$session['user']['name']."`\$ wurde beim Flirten mit $row[name] `\$im Garten von ".($session['user']['sex']?"ihrem Mann":"seiner Frau")." erwischt und ist jetzt wieder solo.");

                    break;

                }

            }

        } else if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295) { // MÃ¶glichkeiten, wenn nur selbst verheiratet

            if ($session['user']['marriedto']==4294967295 && $session['user']['charisma']>=5){

                output("`6".($session['user']['sex']?"Seth":"Violet")." springt aus einem GebÃ¼sch und beschimpft dich aufs Heftigste, als du dich $row[name] nÃ¤hern willst. ".($session['user']['sex']?"Er":"Sie")."  beobachtet deine \"Gartenarbeit\" schon eine ganze Weile!`0`n`n".($session['user']['sex']?"Seth":"Violet")." verlÃ¤sst dich.`nDu verlierst einen Charmepunkt.");

                $session['user']['marriedto']=$row['acctid'];

                $session['user']['charisma']-=1;

                $session['user']['seenlover']=1;

                $session['user']['charm']-=1;

                addnews("`\$".$session['user']['name']."`\$ wurde beim Flirten mit $row[name] im Garten von ".($session['user']['sex']?"Seth":"Violet")." erwischt und ist jetzt wieder solo.");

            } else {

                if ($session['user']['acctid']==$row['marriedto']){

                    output("`%Obwohl du verheiratet bist, gehst du auf die Flirtversuche von $row[name] ein. Ihr versteht euch prima und fÃ¼r einen Moment vergisst du ".($session['user']['sex']?"deinen Mann":"deine Frau").". ");

                } else {

                    output("`%Obwohl du verheiratet bist, lÃ¤sst du dich auf einen Flirt ein. Ihr versteht euch prima und fÃ¼r einen Moment vergisst du ".($session['user']['sex']?"deinen Mann":"deine Frau").". ");

                }

                switch(e_rand(1,4)){

                    case 1:

                    case 2:

                    case 3:

                    output("`% Aber du weiÃŸt, dass eine Beziehung keine Zukunft hat, solange du verheiratet bist.");

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht.");

                    $session['user']['seenlover']=1;

                    if ($session['user']['marriedto']==4294967295) $session['user']['charisma']+=1;

                    break;

                    case 4:

                    output(" Aber ".($session['user']['sex']?"er":"sie")." ruft sich selbst aufs Heftigste ins GedÃ¤chtnis zurÃ¼ck!`nDie Katastrophe ist komplett.`0`n`n".($session['user']['sex']?"Dein Mann":"Deine Frau")." verlÃ¤sst dich ");

                    if ($session['user']['charisma']==4294967295){

                         output(" und bekommt 50% deines VermÃ¶gens von der Bank zugesprochen.`nDu verlierst einen Charmepunkt");

                        $sql = "UPDATE accounts SET marriedto=0,charisma=0,goldinbank=goldinbank+$getgold WHERE acctid='{$session['user']['marriedto']}'";

                        db_query($sql);

                        systemmail($session['user']['marriedto'],"`\$Scheidung!`0","`6Du hast `&{$session['user']['name']}`6 mit `&{$row[name]} im Garten erwischt und verlÃ¤sst ".($session[user][sex]?"sie":"ihn").".`nDir werden `^$getgold`6 Gold von deinem ehemaligen Ehepartner zugesprochen.");

                        $session['user']['goldinbank']-=$getgold;

                    }

                    output(".");

                    $session['user']['marriedto']=$row['acctid'];

                    $session['user']['charisma']=1;

                    $session['user']['seenlover']=1;

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir geflirtet, wurde dabei aber von ".($session['user']['sex']?"ihrem Mann":"seiner Frau")." erwischt.");

                    $session['user']['charm']-=1;

                    addnews("`\$".$session['user']['name']."`\$ wurde beim Flirten im Garten von ".($session['user']['sex']?"ihrem Mann":"seiner Frau")." erwischt und ist jetzt wieder solo.");

                    break;

                }

            }

        } else if ($row['marriedto']==4294967295 || $row['charisma']==4294967295) { // MÃ¶glichkeiten, wenn nur GegenÃ¼ber verheiratet

            if ($session['user']['marriedto']==$row['acctid']){

                $session['user']['charisma']+=1;

                $session['user']['seenlover']=1;

                output("`%Du flirtest zum `^{$session[user][charisma]}.`% Mal mit $row[name] `%, weiÃŸt aber, dass der Flirt wohl nicht erwidert wird, da $row[name]`% (noch) verheiratet ist.");

            } else {

                output("`%Du flirtest mit $row[name]`% und ihr verbringt einige Zeit gemeinsam im Garten.");

                $session['user']['charisma']=1;

                $session['user']['seenlover']=1;

            }

            systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir im Garten geflirtet.");

            $session['user']['marriedto']=$row['acctid'];

        } else { // beide unverheiratet

            if ($session['user']['acctid']==$row['marriedto']){

                if ($flirtnum>=5){

                    output("`c`b`&Hochzeit!`0`b`c");

                    output("`&`n`nIhr kennt euch inzwischen so gut, dass ihr bei diesem eurem $flirtnum. Treffen beschlieÃŸt zu heiraten!");

                    output(" Die Hochzeit ist ein gigantisches Fest! Ihr versteht es wirklich zu feiern.`n`nVon nun an seid ihr ein Paar!");

                    if (getsetting("paidales",0)>=1){

                        $amt=e_rand(2,6);

                        output("`nEs bleiben nur $amt Ale vom Festschmaus Ã¼brig, die ihr freundlicherweise der Kneipe spendet.");

                        savesetting("paidales",getsetting("paidales",0)+$amt);

                    }

                    $session['user']['charisma']=4294967295;

                    $sql = "UPDATE accounts SET charisma='4294967295',charm=charm+1 WHERE acctid='$row[acctid]'";

                    db_query($sql);

                    systemmail($row['acctid'],"`&Hochzeit!`0","`6 Du und `&".$session['user']['name']."`& habt nach zahlreichen gemeinsamen Flirts im Garten geheiratet.`nGlÃ¼ckwunsch!");

                    $session['user']['seenlover']=1;

                    $session['bufflist']['lover']=$buff;

                    $session['user']['charm']+=1;

                    $session['user']['donation']+=1;

                    addnews("`%".$session['user']['name']." `&und `%$row[name]`& haben heute feierlich den Bund der Ehe geschlossen!!!");

                    addnav("Liste der Heldenpaare","hof.php?op=paare");

                } else if ($flirtnum>0){

                    $session['user']['charisma']+=1;

                    $session['user']['seenlover']=1;

                    $session['user']['charm']+=1;

                    output("`%Du flirtest zum `^{$session[user][charisma]}. `%Mal mit deiner Flamme $row[name] `%.`n");

                    output("Ihr habt eure Flirts schon $flirtnum Mal gegenseitig erwidert. Gelingt euch das insgesamt 5 Mal, verspricht $row[name] `%dir, dich zu heiraten!");

                    output("`n`n`^Du erhÃ¤ltst einen Charmepunkt.");

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht. Damit habt ihr $flirtnum gegenseitige Flirts. Ab dem 5. gemeinsamen Flirt kÃ¶nnt ihr heiraten!");

                } else {

                    $session['user']['charisma']+=1;

                    $session['user']['seenlover']=1;

                    $session['user']['charm']+=1;

                    output("`%Du erwiderst den Flirt von $row[name] `%und verbringst einige Zeit mit ".($session['user']['sex']?"ihm":"ihr")." im Garten.`n");

                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 erwidert deinen Flirt und hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht.");

                    output("`n`n`^Du erhÃ¤ltst einen Charmepunkt.");

                }

                $session['user']['marriedto']=$row['acctid'];

            } else if ($session['user']['marriedto']==$row['acctid']){

                $session['user']['charisma']+=1;

                $session['user']['seenlover']=1;

                output("`%Du flirtest zum `^{$session[user][charisma]}.`% Mal mit $row[name] `%und hoffst darauf, dass der Flirt erwidert wird.");

                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir schon wieder ein paar schÃ¶ne Stunden im Garten verbracht.`nWillst du nicht mal reagieren?");

            } else {

                output("`%Du flirtest mit $row[name]`% und ihr verbringt einige Zeit gemeinsam im Garten.");

                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schÃ¶ne Stunden im Garten verbracht.");

                $session['user']['charisma']=1;

                $session['user']['seenlover']=1;

                $session['user']['marriedto']=$row['acctid'];

            }

        }

    }else{

        output("`\$Fehler:`4 Dieser Krieger wurde nicht gefunden. Darf ich fragen, wie du Ã¼berhaupt hierher gekommen bist?");

    }

} else {

    addcommentary();

    checkday();



    output("`b`c`2Die GÃ¤rten`0`c`b");

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/vogel.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

    /*

    output("`n`nDu lÃ¤ufst durch einen Torbogen und weiter auf einem der vielen verschlungenen Pfade, die sich durch die wohlgepflegten GÃ¤rten ziehen. Von den Blumenbeeten, die auch im tiefsten Winter blÃ¼hen, bis hin zu den Hecken, deren Schatten verbotene Geheimnisse versprechen, stellen diese GÃ¤rten einen Zufluchtsort fÃ¼r alle dar, die auf der Suche nach dem GrÃ¼nen Drachen sind. Hier kÃ¶nnen sie ihre Sorgen vergessen und einfach mal entspannen.");

    output("`n`nEine der Feen, die hier im Garten umherschwirren, erinnert dich daran, dass der Garten ein Platz fÃ¼r Rollenspiel ist und dass dieser Bereich vollstÃ¤ndig auf charakterspezifische Kommentare beschrÃ¤nkt ist.");

    output("`n`n");

    */

    output("`n`nDu betrittst den Garten und  plÃ¶tzliche Ruhe umfÃ¤ngt dich. ");

    output("Die BlÃ¤tter der BÃ¤ume rascheln leise in einer sanften BÃ¶e und das Gras wiegt sich hin und her, als tanze es zu einer Melodie. ");

    output("Durch den Garten schlÃ¤ngelt sich ein silbrig glÃ¤nzender, frÃ¶hlich plÃ¤tschernder Fluss, Ã¼ber den sich eine BrÃ¼cke aus weiÃŸem Stein spannt. ");

    output("Hier und dort in den natÃ¼rlichen Nischen der BÃ¼sche siehst du das eine oder andere Paar, das sich leise unterhÃ¤lt, sich die ewige Liebe schwÃ¶rt oder sich in den Armen liegt. ");

    output("Du spÃ¼rst, dass eine besonnene Stimmung Ã¼ber diesem Garten liegt und instinktiv ist dir bewusst, dass hier besondere Regeln herrschen, wenn du hier geduldet werden willst.`n`n ");

    viewcommentary("gardens","Hier flÃ¼stern",30,"flÃ¼stert");



    addnav("Flirten","gardens.php?op=flirt1");

    addnav("Geschenkeladen","newgiftshop.php");

    addnav("Blumenbeet","flowers.php");

}

addnav("ZurÃ¼ck zum Dorf","village.php");

page_footer();

?>

