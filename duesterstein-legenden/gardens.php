
<? 

// gardenflirt 1.0 by anpera 
// uses 'charisma' entry in database to determine how far a love goes, and 'marriedto' to know who with whom. ;) 
// no changes necessary in database 
// some changes in newday.php, hof.php, dragon.php, and inn.php required and in user.php optional! 
// See http://www.anpera.net/forum/viewforum.php?f=27 for details 
//
// Raven, Gargamel @rabenthal
// Add several RP Zones (Old Oak, Romantic Lake, Shrine
// to make it a RP Area and it works fine :)    

require_once "common.php"; 
get_special_var();
$buff = array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst deine große Liebe!`0","defmod"=>1.2,"roundmsg"=>"Deine große Liebe lässt dich an deine Sicherheit denken!","activate"=>"defense"); 

page_header("Die Gärten"); 
if ($_GET[op]=="flirt1"){ 
// output("`b`c`\$IN ÜBERARBEITUNG`b`c`0`n"); 
    if ($session[user][seenlover]){ 
          $sql = "SELECT name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto].""; 
          $result = db_query($sql) or die(db_error(LINK)); 
        $row = db_fetch_assoc($result); 
        $partner=$row[name]; 
        if ($partner=="") $partner = $session[user][sex]?"`^Seth`0":"`5Violet`0"; 
        output("Du versuchst, dich in Gedanken auf einen heißen Flirt vorzubereiten, aber irgendwie bekommst du`3 $partner`0 nicht aus dem Kopf. Vielleicht solltest du bis morgen warten.");
        addnav("Zurück zum Garten","gardens.php"); 
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
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","gardens.php?op=flirt1&limit=".($page-1)."&search=$_POST[search]");
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        if ($session[user][marriedto]==4294967295) output("Du denkst nochmal über deine Ehe mit ".($session[user][sex]?"`^Seth`0":"`5Violet`0")." nach und überlegst, ob du ".($session[user][sex]?"ihn":"sie")." in der Kneipe besuchen sollst, oder für wen du diese Ehe aufs Spiel setzen würdest.`n"); 
        if($session['user']['charisma']==4294967295) output("Du überlegst dir, dass du dir mal wieder etwas Zeit für deine Frau nehmen solltest. Während du sie im Garten suchst, stellst du aber fest, dass der Rest der ".($session[user][sex]?"Männer":"Frauen")." hier auch nicht zu verachten ist.`n");
        output("Für wen entscheidest du dich?`n`n"); 
        output("<form action='gardens.php?op=flirt1' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","gardens.php?op=flirt1");
          $sql = "SELECT acctid,name,sex,level,race,login,marriedto,charisma FROM accounts WHERE 
        $search
        (locked=0) AND 
        (sex <> ".$session[user][sex].") AND 
        (alive=1) AND 
        (acctid <> ".$session[user][acctid].") AND 
        (laston > '".date("Y-m-d H:i:s",strtotime("-346000 sec"))."' OR (charisma=4294967295 AND acctid=".$session[user][marriedto].") ) 
        ORDER BY (acctid=".$session['user']['marriedto'].") DESC, charm DESC LIMIT $limit";
          $result = db_query($sql) or die(db_error(LINK)); 
        output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>",true); 
        output(($session[user][sex]?"<img src=\"images/male.gif\">":"<img src=\"images/female.gif\">")."</td><td><b>Name</b></td><td><b>Level</b></td><td><b>Rasse</b></td><td><b>Status</b><td><b>Ops</b></td></tr>",true); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","gardens.php?op=flirt1&limit=".($page+1)."&search=$_POST[search]");
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
              $biolink="bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']); 
              addnav("", $biolink); 
            if ($session[user][charisma]<=$row[charisma]) $flirtnum=$session[user][charisma]; 
            if ($row[charisma]<$session[user][charisma]) $flirtnum=$row[charisma]; 
              output("<tr class='".($i%2?"trlight":"trdark")."'><td></td><td>$row[name]</td><td>$row[level]</td><td>",true); 
            switch($row['race']){ 
                case 0: 
                output("`7Unbekannt`0"); 
                break; 
                case 1: 
                output("`2Troll`0"); 
                break; 
                case 2: 
                output("`^Elf`0"); 
                break; 
                case 3: 
                output("`0Mensch`0"); 
                break; 
                case 4: 
                output("`#Zwerg`0"); 
                break; 
                case 5: 
                output("`5Ork`0"); 
                break; 
            } 
            output("</td><td>",true); 
            if ($session[user][acctid]==$row[marriedto] && $session[user][marriedto]==$row[acctid]){ 
                if ($session[user][charisma]==4294967295 && $row[charisma]==4294967295){ 
                    output("`@`bDein".($session[user][sex]?" Mann":"e Frau")."!`b`0"); 
                }else if ($flirtnum>=5){ 
                    output("`\$Heiratsantrag!`0"); 
                } else {     
                    output("`^$flirtnum von ".$session[user][charisma]." Flirts erwidert!`0"); 
                } 
            } else if ($session[user][acctid]==$row[marriedto]) { 
                output("Flirtet ".$row[charisma]." mal mit dir"); 
            } else if ($session[user][marriedto]==$row[acctid]) { 
                output("Deine letzten ".$session[user][charisma]." Flirts"); 
            } else if ($row[marriedto]==4294967295 || $row[charisma]==4294967295){ 
                output("`iVerheiratet`i"); 
            } else { 
                output("-"); 
            } 
            output("</td><td>[ <a href='$biolink'>Bio</a> | <a href='gardens.php?act=flirt&name=".rawurlencode($row[login])."'>Flirten</a> ]</td></tr>",true); 
            addnav("","gardens.php?act=flirt&name=".rawurlencode($row[login])); 
        } 
        output("</table>",true); 
        addnav("Zurück zum Garten","gardens.php"); 
    } 
} else if ($_GET[act]=="flirt"){ 
    if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2); 
     $sql = "SELECT acctid,name,experience,charm,charisma,race,marriedto FROM accounts WHERE login=\"$_GET[name]\"";
    $result = db_query($sql) or die(db_error(LINK)); 
    if (db_num_rows($result)>0){ 
        $row = db_fetch_assoc($result); 
    debuglog("".$session[user][name]." hat mit ".$row[name]." geflirtet.");
        if ($session[user][charisma]<=$row[charisma]) $flirtnum=$session[user][charisma]; 
        if ($row[charisma]<$session[user][charisma]) $flirtnum=$row[charisma]; 
        //if ($session[user][lastip]==$row[lastip] || ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress])){ 
    if ($session[user][uniqueid]==$row[uniqueid] || ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress])){ 
            output("`\$`bDas geht doch nicht!!`b Du kannst doch nicht mit deinen eigenen Charakteren oder deiner eigenen Familie flirten!"); 
            addnav("Zurück zum Garten","gardens.php"); 
        } else if (($session[user][race]==2 && $row[race]==4) || ($session[user][race]==4 && $row[race]==2)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Elfen und Zwerge vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten."); 
        } else if (($session[user][race]==3 && $row[race]==4) || ($session[user][race]==4 && $row[race]==3)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Menschen und Zwerge vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==1 && $row[race]==3) || ($session[user][race]==3 && $row[race]==1)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Trolle und Menschen vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==1 && $row[race]==4) || ($session[user][race]==4 && $row[race]==1)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Trolle und Zwerge vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==1 && $row[race]==2) || ($session[user][race]==2 && $row[race]==1)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Trolle und Elfen vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==5 && $row[race]==4) || ($session[user][race]==4 && $row[race]==5)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Orks und Zwerge vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==5 && $row[race]==3) || ($session[user][race]==3 && $row[race]==5)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Orks und Menschen vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if (($session[user][race]==5 && $row[race]==2) || ($session[user][race]==2 && $row[race]==5)){ 
            output("Du wartest im Garten auf `6$row[name]`0 und beobachtest ".($session[user][sex]?"ihn":"sie")." eine Weile. Bei näherer Betrachtung stellst du aber fest, dass Orks und Elfen vielleicht doch niemals zusammen passen werden."); 
            output(" So verlässt du den Garten.");
        } else if ($session[user][turns]<1){ 
            output("Als `6$row[name]`0 endlich im Garten auftaucht, fühlst du dich plötzlich vom vielen Kämpfen so erledigt und geschwächt, dass du es für besser hältst, mit dem Flirten bis morgen zu warten.`nDu hast deine Runden für heute aufgebraucht. "); 
        } else if ($session[user][charm]<=1 && $session[user][charisma]!=4294967295){ 
            output("Du näherst dich `6$row[name]`0 und mit dem Charme einer Blattlaus sprichst du ".($session[user][sex]?"ihn":"sie")." an. Schon fast beleidigt dreht sich $row[name] um und stapft davon.`nDu solltest etwas an deiner Ausstrahlung arbeiten..."); 
        } else if ($row[charm]<=1 && $session[user][charisma]!=4294967295){ 
            output("Du näherst dich `6$row[name]`0. Je näher du ".($session[user][sex]?"ihm":"ihr")." kommst, umso hässlicher kommt ".($session[user][sex]?"er":"sie")." dir vor. Am Ende wirkt ".($session[user][sex]?"er":"sie")." so abstoßend auf dich, dass du einfach vorbei zurück ins Dorf läufst.");
        } else if (abs($row[charm]-$session[user][charm])>10 && $session[user][charisma]!=4294967295){ 
            output("Du näherst dich `6$row[name]`0. Ihr beginnt ein Gespräch, aber irgendwie redet ihr aneinander vorbei. Ein richtiger Flirt entwickelt sich nicht. Du beschließt, es später nochmal zu versuchen und machst dich auf den Weg zurück ins Dorf.");
        } else if ($session[user][drunkenness]>66){ 
            output("Du entdeckst `6$row[name]`0 im Schatten unter einer Gruppe Bäume und machst dich sofort daran, ".($session[user][sex]?"ihn":"sie")." mit deiner Alefahne vollzulallen. Als ".($session[user][sex]?"er":"sie")." überhaupt "); 
            output("nicht reagiert und immer noch irgendwie auf den Boden zu starren scheint, willst du ".($session[user][sex]?"seinen":"ihren")." Kopf heben - und greifst voll in das Dornengestrüpp vor dir.`n"); 
            output("Du hast in deinem Rausch diesen Busch für $row[name] gehalten!! Vielleicht ist es besser, erst etwas auszunüchtern, bevor du es nochmal versuchst.`n`n"); 
            output("`^Dieser Irrtum hat dich einen Waldkampf und einen Charmepunkt gekostet!"); 
            $session[user][turns]-=1; 
            $session[user][charm]-=1; 
        } else if (($session[user][marriedto]==4294967295 || $session[user][charisma]==4294967295) && ($row[marriedto]==4294967295 || $row[charisma]==4294967295)) { // Möglichkeiten, wenn beide verheiratet 
            if ($session[user][marriedto]==$row[acctid] && $session[user][acctid]==$row[marriedto]){ 
                output("`%Du führst ".($session[user][sex]?"deinen Mann":"deine Frau")." `6$row[name]`% in den Garten aus und ihr nehmt euch etwas Zeit füreinander. "); 
                output("`nDu bekommst einen Charmepunkt."); 
                $session['bufflist']['lover']=$buff; 
                $session['user']['charm']++; 
                $session['user']['seenlover']=1; 
            } else if ($session[user][charm]==$row[charm]){ 
                output("`%Du näherst dich `6$row[name]`%. Sofort entsteht ein heftiger Flirt und ein angeregtes Gespräch. Du verstehst dich einfach blendend mit `6$row[name]`%!"); 
                output(" Ihr verzieht euch eine Weile an einen etwas abseits gelegenen Ort"); 
                output(" und verbringt ein paar sehr schöne Stunden miteinander. Da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird."); 
                output("`n`nIhr bekommt beide einen Charmepunkt!"); 
                $session[user][charm]+=1; 
                $session['user']['seenlover']=1; 
                $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='$row[acctid]'"; 
                db_query($sql); 
                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar wunderschöne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor eurem Ehepartner verborgen."); 
            } else { 
                output("`%Du näherst dich `6$row[name]`% und fängst an zu flirten was das Zeug hält. `6$row[name]`% steigt darauf ein, "); 
                switch(e_rand(1,4)){ 
                    case 1: 
                    case 2: 
                    output("`% und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird."); 
                    output("`n`nIhr VERLIERT beide einen Charmepunkt, da ihr euer schlechtes Gewissen nicht vor eurem Ehepartner verbergen könnt!"); 
                    $sql = "UPDATE accounts SET charm=charm-1 WHERE acctid='$row[acctid]'"; 
                    $session[user][charm]-=1; 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schöne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt VERLOREN, da euer schlechtes Gewissen eurem Ehepartner nicht verborgen blieb.");
                    db_query($sql); 
                    $session['user']['seenlover']=1; 
                    break; 
                    case 3: 
                    output("`% und da ihr beide verheiratet seid, versprecht ihr euch gegenseitig, dass niemand jemals davon erfahren wird."); 
                    output("`n`nIhr bekommt beide einen Charmepunkt!"); 
                    $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='$row[acctid]'"; 
                    $session[user][charm]+=1; 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schöne Stunden im Garten verbracht. Ihr habt beide einen Charmepunkt bekommen und haltet euer Geheimnis vor euren Ehepartnern verborgen."); 
                    db_query($sql); 
                    $session['user']['seenlover']=1; 
                    break; 
                    case 4: 
                    output(" aber ihr werdet bei eurem Vergnügen von ".($session[user][sex]?"deinem Mann":"deiner Frau")." erwischt.`nDie Katastrophe ist komplett.`0`n`n".($session[user][sex]?"Dein Mann":"Deine Frau")." verlässt dich"); 
                    if ($session[user][charisma]==4294967295){ 
                        output(" und bekommt 50% deines Vermögens von der Bank zugesprochen"); 
                        $sql = "UPDATE accounts SET marriedto=0,charisma=0,goldinbank=goldinbank+$getgold WHERE acctid='{$session['user']['marriedto']}'"; 
                        db_query($sql); 
                        systemmail($session[user]['marriedto'],"`\$Scheidung!`0","`6Du hast `&{$session['user']['name']}`6 mit `&{$row[name]} im Garten erwischt und verlässt ".($session[user][sex]?"sie":"ihn").".`nDir werden `^$getgold`6 Gold von deinem ehemaligen Ehepartner zugesprochen."); 
                        $session[user][goldinbank]-=$getgold; 
                    } 
                    output(".`nDu verlierst einen Charmepunkt."); 
                    $session[user][marriedto]=$row[acctid]; 
                    $session[user][charisma]=1; 
                    $session['user']['seenlover']=1; 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir geflirtet und wurde dabei von ".($session[user][sex]?"ihrem Mann":"seiner Frau")." erwischt."); 
                    $session[user][charm]-=1; 
                    addnews("`\$".$session[user][name]."`\$ wurde beim Flirten mit $row[name] `\$im Garten von ".($session[user][sex]?"ihrem Mann":"seiner Frau")." erwischt und ist jetzt wieder solo."); 
                    break; 
                } 
            } 
        } else if ($session[user][marriedto]==4294967295 || $session[user][charisma]==4294967295) { // Möglichkeiten, wenn nur selbst verheiratet 
            if ($session[user][marriedto]==4294967295 && $session[user][charisma]>=5){ 
                output("`6".($session[user][sex]?"Seth":"Violet")." springt aus einem Gebüsch und beschimpft dich aufs Heftigste, als du dich $row[name] nähern willst. ".($session[user][sex]?"Er":"Sie")."  beobachtet deine \"Gartenarbeit\" schon eine ganze Weile!`0`n`n".($session[user][sex]?"Seth":"Violet")." verlässt dich.`nDu verlierst einen Charmepunkt.");
                $session[user][marriedto]=$row[acctid]; 
                $session[user][charisma]-=1; 
                $session['user']['seenlover']=1; 
                $session[user][charm]-=1; 
                addnews("`\$".$session[user][name]."`\$ wurde beim Flirten mit $row[name] im Garten von ".($session[user][sex]?"Seth":"Violet")." erwischt und ist jetzt wieder solo."); 
            } else { 
                if ($session[user][acctid]==$row[marriedto]){ 
                    output("`%Obwohl du verheiratet bist, gehst du auf die Flirtversuche von $row[name] ein. Ihr versteht euch prima und für einen Moment vergisst du ".($session[user][sex]?"deinen Mann":"deine Frau").". "); 
                } else { 
                    output("`%Obwohl du verheiratet bist, lässt du dich auf einen Flirt ein. Ihr versteht euch prima und für einen Moment vergisst du ".($session[user][sex]?"deinen Mann":"deine Frau").". "); 
                } 
                switch(e_rand(1,4)){ 
                    case 1: 
                    case 2: 
                    case 3: 
                    output("`% Aber du weißt, dass eine Beziehung keine Zukunft hat, solange du verheiratet bist.");
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schöne Stunden im Garten verbracht."); 
                    $session['user']['seenlover']=1; 
                    if ($session[user][marriedto]==4294967295) $session[user][charisma]+=1; 
                    break; 
                    case 4: 
                    output(" Aber ".($session[user][sex]?"er":"sie")." ruft sich selbst aufs Heftigste ins Gedächtnis zurück!`nDie Katastrophe ist komplett.`0`n`n".($session[user][sex]?"Dein Mann":"Deine Frau")." verlässt dich");
                    if ($session[user][charisma]==4294967295){ 
                         output(" und bekommt 50% deines Vermögens von der Bank zugesprochen.`nDu verlierst einen Charmepunkt"); 
                        $sql = "UPDATE accounts SET marriedto=0,charisma=0,goldinbank=goldinbank+$getgold WHERE acctid='{$session['user']['marriedto']}'"; 
                        db_query($sql); 
                        systemmail($session[user]['marriedto'],"`\$Scheidung!`0","`6Du hast `&{$session['user']['name']}`6 mit `&{$row[name]} im Garten erwischt und verlässt ".($session[user][sex]?"sie":"ihn").".`nDir werden `^$getgold`6 Gold von deinem ehemaligen Ehepartner zugesprochen."); 
                        $session[user][goldinbank]-=$getgold; 
                    } 
                    output("."); 
                    $session[user][marriedto]=$row[acctid]; 
                    $session[user][charisma]=1; 
                    $session['user']['seenlover']=1; 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir geflirtet, wurde dabei aber von ".($session[user][sex]?"ihrem Mann":"seiner Frau")." erwischt."); 
                    $session[user][charm]-=1; 
                    addnews("`\$".$session[user][name]."`\$ wurde beim Flirten im Garten von ".($session[user][sex]?"ihrem Mann":"seiner Frau")." erwischt und ist jetzt wieder solo."); 
                    break; 
                } 
            } 
        } else if ($row[marriedto]==4294967295 || $row[charisma]==4294967295) { // Möglichkeiten, wenn nur Gegenüber verheiratet 
            if ($session[user][marriedto]==$row[acctid]){ 
                $session[user][charisma]+=1; 
                $session['user']['seenlover']=1; 
                output("`%Du flirtest zum `^{$session[user][charisma]}.`% Mal mit $row[name] `%, weißt aber, dass der Flirt wohl nicht erwidert wird, da $row[name]`% (noch) verheiratet ist.");
            } else { 
                output("`%Du flirtest mit $row[name]`% und ihr verbringt einige Zeit gemeinsam im Garten."); 
                $session[user][charisma]=1; 
                $session['user']['seenlover']=1; 
            } 
            systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir im Garten geflirtet."); 
            $session[user][marriedto]=$row[acctid]; 
        } else { // beide unverheiratet 
            if ($session[user][acctid]==$row[marriedto]){ 
                if ($flirtnum>=5){ 
                    output("`c`b`&Hochzeit!`0`b`c"); 
                    //output("`&`n`nIhr kennt euch inzwischen so gut, dass ihr bei diesem eurem $flirtnum. Treffen beschliesst zu heiraten!"); 
                    //output(" Die Hochzeit ist ein gigantisches Fest! Ihr versteht es wirklich zu feiern.`n`nVon nun an seid ihr ein Paar!"); 
            output(" Für Euch können nun die Hochzeitsglocken läuten. In Kürze wird einer der Priester auf Euch zukommen
                und mit Euch die Hochzeitsmodalitäten besprechen. Ihr könnt Euch ja schon einmal in der kleinen
                Kapelle in der Träumergasse umsehen, in welcher die Zeremonie stattfinden wird.");
                    //if (getsetting("paidales",0)>=1){ 
                    //    $amt=e_rand(2,6); 
                    //    output("`nEs bleiben nur $amt Ale vom Festschmaus übrig, die ihr freundlicherweise der Kneipe spendet."); 
                    //    savesetting("paidales",getsetting("paidales",0)+$amt); 
                    //} 
                    //$session[user][charisma]=4294967295; 
                    //$sql = "UPDATE accounts SET charisma='4294967295',charm=charm+1 WHERE acctid='$row[acctid]'"; 
                    //db_query($sql); 
            //systemmail(4,"`&Hochzeit!`0","`6 ".$row[name]." und `&".$session['user']['name']."`& haben Ihr Aufgebot bestellt!"); 
                    //systemmail($row[acctid],"`&Hochzeit!`0","`6 Du und `&".$session['user']['name']."`& habt nach zahlreichen gemeinsamen Flirts im Garten geheiratet.`nGlückwunsch!"); 
                    //$session[user][seenlover]=1; 
                    //$session[bufflist][lover]=$buff; 
                    //$session[user][charm]+=1; 
                    //$session[user][donation]+=1; 
                    //addnews("`%".$session[user][name]." `&und `%$row[name]`& haben heute feierlich den Bund der Ehe geschlossen!!!"); 
            addnews("`%".$session[user][name]." `&und `%$row[name]`& haben heute das Aufgebot zur Hochzeit bestellt!!!");
                    //addnav("Liste der Heldenpaare","hof.php?op=paare"); 
                } else if ($flirtnum>0){ 
                    $session[user][charisma]+=1; 
                    $session['user']['seenlover']=1; 
                    $session[user][charm]+=1; 
                    output("`%Du flirtest zum `^{$session[user][charisma]}. `%Mal mit deiner Flamme $row[name] `%.`n"); 
                    output("Ihr habt eure Flirts schon $flirtnum Mal gegenseitig erwidert. Gelingt euch das insgesamt 5 Mal, verspricht $row[name] `%dir, dich zu heiraten!");
                    output("`n`n`^Du erhältst einen Charmepunkt."); 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schöne Stunden im Garten verbracht. Damit habt ihr $flirtnum gegenseitige Flirts. Ab dem 5. gemeinsamen Flirt könnt ihr heiraten!"); 
                } else { 
                    $session[user][charisma]+=1; 
                    $session['user']['seenlover']=1; 
                    $session[user][charm]+=1; 
                    output("`%Du erwiderst den Flirt von $row[name] `%und verbringst einige Zeit mit ".($session[user][sex]?"ihm":"ihr")." im Garten.`n"); 
                    systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 erwidert deinen Flirt und hat mit dir ein paar schöne Stunden im Garten verbracht."); 
                    output("`n`n`^Du erhältst einen Charmepunkt."); 
                } 
                $session[user][marriedto]=$row[acctid]; 
            } else if ($session[user][marriedto]==$row[acctid]){ 
                $session[user][charisma]+=1; 
                $session['user']['seenlover']=1; 
                output("`%Du flirtest zum `^{$session[user][charisma]}.`% Mal mit $row[name] `%und hoffst darauf, dass der Flirt erwidert wird."); 
                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir schon wieder ein paar schöne Stunden im Garten verbracht.`nWillst du nicht mal reagieren?"); 
            } else { 
                output("`%Du flirtest mit $row[name]`% und ihr verbringt einige Zeit gemeinsam im Garten."); 
                systemmail($row['acctid'],"`%Gartenflirt!`0","`&{$session['user']['name']}`6 hat mit dir ein paar schöne Stunden im Garten verbracht."); 
                $session[user][charisma]=1; 
                $session['user']['seenlover']=1; 
                $session[user][marriedto]=$row[acctid]; 
            } 
        } 
    }else{ 
        output("`\$Fehler:`4 Dieser Krieger wurde nicht gefunden. Darf ich fragen, wie du überhaupt hierher gekommen bist?");
    } 
}else if($_GET[op]=="lake"){     // new Feature by Raven
    addcommentary(); 
    if ($session[user][locate]!=11){
        $session[user][locate]=11;
    redirect("gardens.php?op=lake");
    }         
    output("`b`c`2Der romantische See`0`c`b"); 
    output("`n`n`9Du bist ein Sück weiter durch den Garten gelaufen und nun an dem leicht abseits gelegenen See angekommen. Unwillkürlich erfaßt Dich eine
        romantische Stimmung, als Du siehst, wie das Licht sanft auf der Wasseroberfläche reflektiert wird und die
        Wellen leise an den kleinen Sandstrand plätschern, der direkt vor Dir liegt."); 
    output("Bis zu ".maxspieler(11)." Einwohner gleichzeitig haben heute schon dieses Schauspiel, wie Du es jetzt siehst, bewundert.");
    output(" Das heutige Wetter ist: `6".$settings['weather']."`@.");
    output("`n`n`9Eine der Feen, die hier im Garten umherschwirren, erinnert dich daran, dass der Garten ein Platz für 
        Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische 
        Kommentare beschränkt ist.`n`n"); 
    viewcommentary("lake","Hier flüstern",20,"flüstert"); 

    addnav("Flirten","gardens.php?op=flirt1");
    addnav("Aktualisieren","gardens.php?op=lake");
    addnav("Zurück in den Garten","gardens.php"); 
}else if($_GET[op]=="oldoak"){     // new Feature by Raven
    addcommentary();
    if ($session[user][locate]!=12){
        $session[user][locate]=12;
    redirect("gardens.php?op=oldoak");
    }
    output("`b`c`2Die alte Eiche`0`c`b"); 
    output("`n`n`9Du gehst in die entgegengesetzte Richtung zum See wo am Rand des Gartens eine sehr alte Eiche steht. Unter der Eiche entdeckst Du
        eine kleine Bank, auf der Du Dich niederläßt. Ein wunderbar romantischer Ort, in dem sich das Tageslicht im Blätterdach der Eiche, 
        welches über Dir ist, wunderschön bricht. Du lauschst, wie der Wind die Blätter leise rascheln läßt."); 
    output("Bis zu ".maxspieler(12)." Einwohner waren heute schon gleichzeitig hier, um dem Rauschen der Blätter zu lauschen.");
    output(" Das heutige Wetter ist: `6".$settings['weather']."`@.");
    output("`n`n`9Eine der Feen, die hier im Garten umherschwirren, erinnert dich daran, dass der Garten 
        ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf 
        charakterspezifische Kommentare beschränkt ist.`n`n"); 
    viewcommentary("oldoak","Hier flüstern",20,"flüstert"); 

    addnav("Flirten","gardens.php?op=flirt1");
    addnav("Aktualisieren","gardens.php?op=oldoak");
    addnav("Pfad zum Tempel","gardens.php?op=shrine");
    addnav("Zurück in den Garten","gardens.php");
}else if($_GET[op]=="shrine"){     // new Feature by Gargamel, Idea by Solaris
    addcommentary();
    if ($session[user][locate]!=26){
        $session[user][locate]=26;
        redirect("gardens.php?op=shrine");
    }
    output("`b`c`2Der Gartentempel`0`c`b");
    output("`n`9Du folgst einem kleinen verschlungenen Pfad durch ein lichtes Waldstück. Im
    Unterholz knackt und raschelt es, und in den Baumwipfeln zwitschern Vögel. Du folgst dem
    Weg über eine steile Anhöhe und erblickst dahinter eine kleine idyllische Lichtung.`n
    Hier liegt in der Mitte ein gewaltiger Felsbrocken, aus dem die Urväter einen Altar gehauen
    haben. Davor, in einem steinernen Becken, ist schwarzer Lavasand verteilt. Hier stecken
    einige Räucherstäbchen.`n`0");
    output("Bis zu ".maxspieler(26)." Einwohner waren heute schon gleichzeitig hier, um auf den
    aufgestellten Bänken zu verweilen.");
    output("`n`9Die spirituelle Atmosphäre an diesem Platz erinnert Dich daran, dass der
    Gartentempel ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf
        charakterspezifische Kommentare beschränkt ist.`n`n");
    viewcommentary("gardentemple","Hier flüstern",20,"flüstert");
    //if ( $session[user][turns]>0 ) {
    //    addnav("Räucherstäbchen opfern","gardens.php?op=shrine2");
    //}
    addnav("Aktualisieren","gardens.php?op=shrine");
    addnav("Zurück zur Eiche","gardens.php?op=oldoak");
}else if($_GET[op]=="shrine2"){     // new Feature by Gargamel, Idea by Solaris
        $session[user][turns]--;
        $sql = "INSERT INTO commentary (postdate,section,author,comment)
        VALUES (now(),'gardentemple',".$session[user][acctid].",
        '/me `\$zündet ein Räucherstäbchen an`0 und opfert es den Göttern.')";
        db_query($sql) or die(db_error(LINK));
        $sql = "INSERT INTO commentary (postdate,section,author,comment)
        VALUES (now(),'gardentemple',".$session[user][acctid].",
        '/me `\^murmelt `9\"Mögen die Götter mir gewogen sein und meinen
        Wunsch erfüllen...\"')";
        db_query($sql) or die(db_error(LINK));
        if (e_rand(1,3)!=3) {
            $session['bufflist']['Gartentempel'] =
            array("name"=>"`8Segen der Götter",
                  "rounds"=>20,
                  "wearoff"=>"Der Segen der Götter ist verbraucht.",
                  "defmod"=>1.25,
                  "atkmod"=>1.10,
                  "roundmsg"=>"`8Der Segen der Götter steht Dir bei.",
                  "activate"=>"offense");
        }
        addnav("","gardens.php?op=shrine");
        redirect("gardens.php?op=shrine");
} else {
    addcommentary(); 
    checkday(); 
    if ($session[user][locate]!=4){
        $session[user][locate]=4;
    redirect("gardens.php");
    } 
    output("`b`c`2Die Gärten`0`c`b"); 
    //if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/vogel.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);     
    output("`n`nDu läufst durch einen Torbogen und weiter auf einem der vielen verschlungenen Pfade, die sich durch die wohlgepflegten Gärten ziehen. Von den Blumenbeeten, die auch im tiefsten Winter blühen, bis hin zu den Hecken, deren Schatten verbotene Geheimnisse versprechen, stellen diese Gärten einen Zufluchtsort für alle dar, die auf der Suche nach dem Grünen Drachen sind. Hier können sie ihre Sorgen vergessen und einfach mal entspannen."); 
    output("`n`nEine der Feen, die hier im Garten umherschwirren, erinnert dich daran, dass der Garten ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist."); 
    output("Das die Bewohner von Rabenthal ihren Garten lieben zeigt, das heute schon bis zu ".maxspieler(4)." Einwohner
        hier im Garten, bis zu ".maxspieler(11)." Einwohner am See und bis zu ".maxspieler(12)." Einwohner an der Eiche waren."); 
   output("`nDas heutige Wetter ist: `6".$settings['weather']."`@.`n`n");
    viewcommentary("gardens","Hier flüstern",20,"flüstert"); 

        addnav("Flirten","gardens.php?op=flirt1"); 
    addnav("Der romantische See","gardens.php?op=lake");
    addnav("Die alte Eiche","gardens.php?op=oldoak");
    addnav("-");
    addnav("Aktualisieren","gardens.php");

} 
addnav("Zurück zum Dorf","village.php"); 
page_footer(); 
?>


