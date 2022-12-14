
<?php

// 22062004

// Jarcaths Shrine by unknown
// found at sourceforge project page
// translation and addons by anpera

require_once "common.php";

page_header("Schrein des Jarcath");

addcommentary();
checkday();

output("`b`c`ÎSchrein des `²J`ça`Âr`Îc`Âa`çt`²h`Î`c`b");

output("`n`n`ÎIn einer sehr stillen Ecke des Raums entdeckst du einen Schrein des Gottes der Unterwelt. Hier kannst du beten, um geliebte Verstorbene wiederzuerwecken.");
output(" Die Inschriften verraten dir, daß es dich den dreifachen Aufwand kostet, einen anderen zu erwecken, als wenn du dich selbst von `²J`ça`Âr`Îc`Âa`çt`²h `Îwiedererwecken lassen würdest.`n");
output("`n Nachdem du dich eine Weile darauf konzentriert hast, kannst du erkennen, daß du ".$session['user']['deathpower']." Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h`Î hast.`n");

if ($_GET['op'] == ""){
        checkday();
        $count=0;
        if ($session['user']['deathpower']>=150 && $session['user']['marriedto']>0 && $session['user']['charisma']==4294967295){
                addnav("Ehepartner wiedererwecken","shrine.php?op=weiter&what=partner");
                output("`nDu kannst deinen Ehepartner für 150 Gefallen aus dem Reich der Toten zurückholen.");
                $count++;
        }
        if ($session['user']['deathpower']>=300){
                addnav("Krieger erwecken","shrine.php?op=weiter&what=normal");
                output("`nDu kannst einen beliebigen Krieger für 300 Gefallen erwecken.");
                $count++;
        }
        if ($session['user']['acctid']==getsetting("hasegg",0)){
                addnav("Goldenes Ei benutzen","shrine.php?op=weiter&what=egg");
                output("`nDu kannst das `^goldene Ei `Îbenutzen, um jemanden wiederzuerwecken.");
                $count++;
        }
        if (!$count) output("`n Damit kannst du hier nichts anfangen.");
}else if ($_GET['op'] == "weiter"){
        $what = $_GET['what'];
        if ($what=="partner"){
                $sql = "SELECT name,login,acctid,alive,deathpower FROM accounts WHERE alive=0 AND acctid=".$session['user']['marriedto']."";
                $result = db_query($sql);
                if (db_num_rows($result)){
                        $row = db_fetch_assoc($result);
                        output("<form action='shrine.php?op=pickname&what={$what}' method='POST'>",true);
                        output("`&{$row['name']}`S hat {$row['deathpower']} Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h`S. Wiedererwecken?");
                        output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'>`n`n",true);
                        output("<input type='submit' class='button' value='Wiedererwecken'>",true);
                        output("</form>",true);
                        addnav("","shrine.php?op=pickname&what=".$what);
                }else{
                        output("`n`%Dein".($session['user']['sex']?" Partner":"e Partnerin")." ist nicht tot!");
                        addnav("Zurück zum Schrein","shrine.php");
                }
        }else{
                output("Bitte gebe den Namen dessen ein, den du wiedererwecken willst:`n`n");
                output("<form action='shrine.php?op=findname&what={$what}' method='POST'>Name:<input name='to'> (Unvollständige Namen werden automatisch ergänzt).`n",true);
                output("`n`n<input type='submit' class='button' value='Vorschau'></form>",true);
                output("<script language='javascript'>document.getElementById('to').focus();</script>",true);
                addnav("","shrine.php?op=findname&what=".$what);
                output("`n`n");
        }
}else if ($_GET['op'] == "findname"){
        $what = $_GET['what'];
        $string = str_create_search_string($_POST['to']);
        $sql = "SELECT name,login,acctid,alive,deathpower FROM accounts WHERE alive=0 AND name LIKE '".$string."'";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
                $row = db_fetch_assoc($result);
                output("<form action='shrine.php?op=pickname&what={$what}' method='POST'>",true);
                output("`&{$row['name']}`S hat {$row['deathpower']} Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h`S. Wiedererwecken?");
                output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'>`n`n",true);
                output("<input type='submit' class='button' value='Wiedererwecken'>",true);
                output("</form>",true);
                addnav("","shrine.php?op=pickname&what=".$what);
        }elseif(db_num_rows($result)>100){
                output("Der Schrein macht Geräusche, als kämen zu viele körperlose Seelen in Frage. Du solltest die Person genauer beschreiben.`n`n");
                output("<form action='shrine.php?op=findname&what={$what}' method='POST'>",true);
                output("Name: <input name='to' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt).`n",true);
                output("<input type='submit' class='button' value='Vorschau'></form>",true);
                addnav("","shrine.php?op=findname&what=".$what);
        }elseif(db_num_rows($result)>1){
                output("<form action='shrine.php?op=pickname&what={$what}' method='POST'>",true);
                output("`6Erwecke <select name='to' class='input'>",true);
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
                }
                output("</select><input type='hidden' name='acctid' value='".$row['acctid']."'>`n`n",true);
                output("<input type='submit' class='button' value='Wiedererwecken'>",true);
                output("</form>",true);
                addnav("","shrine.php?op=pickname&what=".$what);
        }else{
                output("`6Es konnte niemand mit diesem Namen gefunden werden.");
        }
}else if($_GET['op'] == "pickname") {
        $what = $_GET['what'];
        $result = db_query($sql = "SELECT name,acctid,alive,lasthit,lastip,emailaddress,uniqueid FROM accounts WHERE login='{$_POST['to']}'");
        if (db_num_rows($result)==1){
                $row = db_fetch_assoc($result);
                if (ac_check($row)){
//                if (($session['user'][emailaddress]==$row[emailaddress] && $row[emailaddress]) || $session['user'][lastip]==$row[lastip]){
                        output("`%Die Götter gewähren dir diesen Wunsch nicht. Du kannst deine eigenen oder derart verwandte Krieger nicht wiedererwecken.");
                }else{
                        if ($what=="partner"){
                                $session['user']['deathpower']-=150;
                                addnews("`&".$session['user']['name']."`& hat ".($session['user']['sex']?"ihren Mann":"seine Frau")." {$row['name']}`& aus dem Reich der Toten erweckt.");
                        }else if ($what=="egg"){
                                addnews("`&".$session['user']['name']."`& hat das `^goldene Ei`& benutzt, um {$row['name']}`& aus dem Reich der Toten zu erwecken.");
                                savesetting("hasegg","0");
                                item_set(' tpl_id="goldenegg"', array('owner'=>0) );
                        }else{
                                $session['user']['deathpower']-=300;
                                addnews("`&".$session['user']['name']."`& hat {$row['name']}`& aus dem Reich der Toten erweckt.");
                        }
                        $session['user']['donation']+=1;
                        $sql = "UPDATE accounts SET alive=1,lasthit='".date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds"))."' WHERE acctid='{$row['acctid']}'";
                        db_query($sql);
                        $session['user']['reputation']+=5;
                        output("`n`%{$row['name']} `%ist wiederauferstanden!`n`n");
                        systemmail($row['acctid'],"`^Du wurdest wiedererweckt!`0","`&{$session['user']['name']}`6 hat dich wiedererweckt! Du solltest ".($session['user']['sex']?"ihr":"ihm")." dafür dankbar sein.");
                }
        }else{
                output("Das hat nicht geklappt. Versuche es nochmal.");
                addnav("Zurück","shrine.php");
        }

}

addnav('Verlassen');
addnav("Schrein verlassen","rock.php");

page_footer();
?>

