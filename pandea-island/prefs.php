<?php
#DJ ANTOINE - All we need#
/*
Modifkations by ang3l
COPYRIGHT 2005 by ang37@arcor.de
- kompletter Freundemod
- erweiterete Optionen
- Avataruploadservice

2014 by aragon
-02-07 - friendslist überarbeitet, dynamifiziert um die account-tabelle zu verschlanken
-03-22 - password > wird jetzt anders gespeichert
*/
if (isset($_POST['template'])){
        setcookie("template",$_POST['template'],strtotime("+45 days"));
        $_COOKIE['template']=$_POST['template'];
}
require_once "common.php";
require_once "lib/friends.php";
require_once "lib/logd_pw.php";

page_header("Einstellungen & Profil");

// *** add by aragon --> if user = gem-dealer then user can make himself invisible like admins
$sql_h= "SELECT acctid FROM shops_owner where shopid=1 and acctid=".$session['user']['acctid'];
$result_h=db_query($sql_h);
$row_h = db_fetch_assoc($result_h);
if($session['user']['acctid']==$row_h['acctid'])$juwi=1;
// *** end - add by aragon

if ($_GET[op]=="suicide" && getsetting("selfdelete",0)!=0) {
        if($session[user][acctid]==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));

        // inventar und haus löschen und partner freigeben
        //if ((int)$HTTP_GET[userid]==(int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
            $sql = "UPDATE items SET owner=0 WHERE owner=$_GET[userid]";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$_GET[userid] AND status=1";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$_GET[userid] AND status=0";
        db_query($sql);
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$_GET[userid]";
        db_query($sql);
        $sql = "DELETE FROM pvp WHERE acctid2=$_GET[userid] OR acctid1=$_GET[userid]";
        db_query($sql) or die(db_error(LINK));
        $sql = "DELETE FROM bounties WHERE acctid='$_GET[userid]'";
        db_query($sql);
        // user löschen
        $sql = "DELETE FROM accounts_text WHERE acctid='$_GET[userid]'";
        db_query($sql);
        $sql = "DELETE FROM accounts WHERE acctid='$_GET[userid]'";
        db_query($sql);
        output("Dein Charakter, sein Inventar und alle seine Kommentare wurden gelöscht!");
        addnews("`#{$session['user']['name']} beging Selbstmord.");
        addnav("Login Seite", "index.php");
        $session=array();
        $session[user] = array();
        $session[loggedin] = false;
        $session[user][loggedin] = false;

}else if ($_GET[op]=="inventory") {
        output("`c`bDie Besitztümer von ".$session[user][name]."`b`c`n`n");
        output("`^`bSchlüssel`b`0`n");
        if ($session[user][house]!=0){
           $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' AND value1='".$session[user][house]."'";
           $result = db_query($sql) or die(db_error(LINK));
           $own = db_num_rows($result);
           output("`&Du hast `^$own`& Schlüssel für dein eigenes Haus (Hausnr. `@".$session[user][house]."`&) einstecken.`n");
        }
        $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' AND value1!='".$session[user][house]."' ORDER BY value1 ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $a=0;
        for ($i=0;$i<db_num_rows($result);$i++){
             $item = db_fetch_assoc($result);
             if ($a==0){
                     //if mehrere :)
                     $sql2 = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' AND value1='".$item[value1]."'";
                     $result2 = db_query($sql2) or die(db_error(LINK));
                     $sql3 = "SELECT name FROM accounts WHERE house='".$item[value1]."'";
                     $result3 = db_query($sql3) or die(db_error(LINK));
                     $owner = db_fetch_assoc($result3);
                     if (db_num_rows($result2)!=1){
                        $c=db_num_rows($result2);
                        $a=$c-1;
                        output("`&Du hast `^$c`& Schlüssel für Haus Nummer `@".$item[value1]." `& ($owner[name]`&).`n");
                     }else{
                           output("`&Du hast einen Schlüssel für Haus Nummer `@".$item[value1]."`& ($owner[name]`&).`n");
                     }
             }else{
                $a--;
             }
        }
        if (db_num_rows($result)==0) output("Du besitzt keinerlei Schlüssel");
        output("`n`n`n`^`bSonstiges Inventar`b`0`n");
        output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>`bItem`b</td><td>`bKlasse`b</td><td>`bWert`b</td></tr>",true);
        $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class!='Schlüssel' ORDER BY class ASC";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
                  output("<tr><td colspan=4 align='center'>`&`iDu hast nichts im Inventar`i`0</td></tr>",true);
        }else{
                for ($i=0;$i<db_num_rows($result);$i++){
                        $item = db_fetch_assoc($result);
                        $bgcolor=($i%2==1?"trlight":"trdark");
                        output("<tr class='$bgcolor'><td>`&$item[name]`0</td><td>`!$item[class]`0</td><td>",true);
                        if ($item[gold]==0 && $item[gems]==0){
                                output("`4--`0");
                        }else{
                                output("`^$item[gold]`0 Gold, `#$item[gems]`0 Edelsteine");
                        }
                        output("</td></tr><tr class='$bgcolor'><td align='right'>Beschreibung:</td><td colspan=4>$item[description]</td></tr>",true);
                }
        }
        if (getsetting("hasegg",0)==$session[user][acctid]){
                        $bgcolor=($i%2==1?"trdark":"trlight");
                        output("<tr class='$bgcolor'><td>`^Das goldene Ei`0</td><td></td><td></td><td></td><td>`4Unverkäuflich`0</td></tr>",true);
        }
        output("</table>",true);
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="friends") {
        addnav("Zurück","prefs.php");
        addnav("Aktualisieren","prefs.php?op=friends");
        addnav("Freund hinzufügen","prefs.php?op=friends&act=add");
        $acctid=$session['user']['acctid'];
        $f=(int)$_GET['f'];
        $p=(int)$_GET['p'];
        $p%=2;

        if($_GET['act']=="add")
        {
            $url="prefs.php?op=friends&act=add2&f=".$f;
            output("<form action='$url' method='POST'>",true);
            output("`2Name: <input name='contractname'>`n", true);
            output("<input type='submit' class='button' value='Auswählen'></form>",true);
            addnav("",$url);
        }
        elseif($_GET['act']=="add2")
        {
            if ($_GET['subfinal']==1){
                $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."'";
            }else{
                $contractname = stripslashes(rawurldecode($_POST['contractname']));
                $name="%";
                for ($x=0;$x<strlen($contractname);$x++){
                    $name.=substr($contractname,$x,1)."%";
                }
                $sql = "SELECT login,acctid,name,rpstars FROM accounts WHERE name LIKE '".addslashes($name)."'";
            }
            $result = db_query($sql);
            if (db_num_rows($result) == 0) {
                output("Es wurde leider keine Person gefunden.");
            } elseif(db_num_rows($result) > 100) {
                output("Es wurden zu viele Personen gefunden.");
            } elseif(db_num_rows($result) > 1) {
                $url="prefs.php?op=friends&act=add2&subfinal=1&f=".$f;
                output("Etwas genauer solltest du deine Freunde schon kennen, oder? Bitte beschreibe die gesuchte Person doch noch etwas:");
                output("<form action='$url' method='POST'>",true);
                output("`2Person: <select name='contractname'>",true);
                for ($i=0;$i<db_num_rows($result);$i++){
                    $row = db_fetch_assoc($result);
                    output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
                }
                output("</select>`n`n",true);
                output("<input type='submit' class='button' value='Die Person ist es!'></form>",true);
                addnav("",$url);
            } else {
                $row = db_fetch_assoc($result);
                if ($row['login']==$session['user']['login']){
                        output("`n`@Jaajaa. Ist zwar nett, dass du dich so gut mit dir verstehst aber etwas egoistisch ist das schon, oder?");
                }elseif(allreadyfriend($acctid,$row['acctid'])){
                    output("`n`@Auch wenn du mit `i$row[name] `i`@super dicke Freunde bist, 1 Eintrag in der Liste reicht doch ;)`n");
                } else {
                    if($f>0)
                    {
                        updatefriend($acctid,$f,$row['acctid']);
                        output("`n`@Du hast `i $row[name] `i`@zu deinen Freunden hinzugefügt. `n");
                        $row=db_fetch_assoc(db_query("select name from accounts where acctid=".$f));
                        output("`nleider hast du damit `@`i$row[name] `i`@ersertzt. `n`nAwwww :(`n`n");
                    }
                    else
                    {
                        addfriend($session['user']['acctid'],$row['acctid']);
                        output("`n`@Du hast `i $row[name] `i`@zu deinen Freunden hinzugefügt. `n");
                    }
                }
            }

        }
        elseif($_GET['act']=="delete")
        {
            deletefriend($acctid,$f);
            $row=db_fetch_assoc(db_query("select name from accounts where acctid=".$f));
            output("`n`@`i$row[name] `i`@von der Freundesliste gelöscht. `n`nAwwww :(`n`n");

        }
        elseif($_GET['act']=="private")
        {
            updatefriend($acctid,$f,$f,$p);
            $row=db_fetch_assoc(db_query("select name from accounts where acctid=".$f));
            if($p)output("`n`@`i$row[name] `i`@ darf jetzt in dein Privatgemach`n`n;)`n`n");
            else output("`n`\$`i$row[name] `i`\$ darf jetzt nicht mehr in dein Privatgemach`n`nAwwww :(`n`n");

        }


        output("`c`bDie Freunde von ".$session[user][name]."`b`c`n`n");


        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td>Name</td><td colspan=\"2\">Aktionen</td><td>Privatgemach</td></tr>",true);


        $sql="SELECT f.*,a.name FROM friendlist f, accounts a WHERE a.acctid=f.friend AND f.acctid=".$session['user']['acctid'];
        $res=db_query($sql);
#        $res=getfriends($session['user']['acctid']);
#        $row=db_fetch_assoc($res);
        while($row=db_fetch_assoc($res))
        {
            $f=$row['friend']; //acctid
            $fn=$row['name']; // name
            $pr=$row['private']; //privates: 0=no, 1=yes

            $url1="prefs.php?op=friends&act=delete&f=".$f;
            $url2="prefs.php?op=friends&act=add&f=".$f;
            $url3="prefs.php?op=friends&act=private&f=".$f."&p=".(($pr+1)%2);
            addnav("",$url1);
            addnav("",$url2);
            addnav("",$url3);

                output("<tr>
                <td>{$fn}</td>
                <td> | <a href='$url2'>Edit</a></td>
                <td> | <a href='$url1'>Löschen</a></td>
                <td> | <a href='$url3'>".($pr?"`@on":"`\$off")."</a></td>
                </tr>",true);

        }

        output("</table>",true);

        output("`n`n");

}else if ($_GET[op]=="extraoptions") {
        output("`c`bBesondere Einstellungen für ".$session[user][name]."`b`c`n`n");
        if ($session['user']['preview']==1) output("`^Preview: <a href='prefs.php?op=previewoff'>`4ausschalten</a>",true);
        if ($session['user']['preview']==0) output("`^Preview: <a href='prefs.php?op=previewon'>`@anschalten</a>",true);
        output("`n");
        addnav("","prefs.php?op=autocolor");
        addnav('','prefs.php?op=previewoff');
        addnav('','prefs.php?op=previewon');
        addnav('','prefs.php?op=colnull');
        addnav('','prefs.php?op=coleins');
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="previewoff") {
        output("`c`bVorschau deaktiviert`b`c`n`n");
        $session['user']['preview']=0;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="previewon") {
        output("`c`bVorschau aktiviert`b`c`n`n");
        $session['user']['preview']=1;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="avanull") {
        output("`c`b`^Avatarfunktion auf `4extern `^umgestellt.`b`c`n`n");
        $session['user']['switchavatar']=0;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="avaeins") {
        output("`c`b`^Avatarfunktion auf `@Uploadfunktion `^umgestellt.`b`c`n`n");
        $session['user']['switchavatar']=1;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="colnull") {
        output("`c`b`^Automatische Farbgebung deaktiviert.`b`c`n`n");
        $session['user']['activatecolor']=0;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="coleins") {
        output("`c`b`^Automatische Farbgebung aktiviert.`b`c`n`n");
        $session['user']['activatecolor']=1;
        addnav("Zurück","prefs.php");
}else if ($_GET[op]=="activateava") {
        output("`c`b`^Avatar erfolgreich aktiviert.`b`c`n`n");
        $session['user']['useavatar2']=1;
        addnav("Zurück","prefs.php");
} elseif ($_GET[op]=="skin"){
         if ($handle = @opendir("templates")){
                $skins = array();
                while (false !== ($file = @readdir($handle))){
                        if (strpos($file,".htm")>0){
                                array_push($skins,$file);
                        }
                }
                if (count($skins)==0){
                        output("`b`@Argh, dein Admin hat entschieden, daß du keine Skins benutzen darfst. Beschwer dich bei ihm, nicht bei mir.`n");
                }else{
                        output("<b>Skin:</b><br>",true);
                        /* Update Part 1/2 by Serena of Pandea-Island 2008-06-27 */
                        /* Änderung: erweitert um: &subop=skins
                        Damit beim Speichern/Templatewechsel nicht die komplette Bio gelöscht wird -> weitere Info siehe Teil 2
                        Originalzeile:
                        output("<form action='prefs.php?op=save' method='POST'>",true);
                        */
                        output("<form action='prefs.php?op=save&subop=skins' method='POST'>",true);
                        output("Hier kannst du aus verschiedenen Skins auswählen. Die Änderungen werden wirksam wenn du zu deinem Profil zurückkehrst.`n`n");
                        /* Ende Update Part 1/2 by Serena of Pandea-Island 2008-06-27 */
                        while (list($key,$val)=each($skins)){
                              if($val!="test.htm") output("<input type='radio' name='template' value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" checked":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
                              if($val=="test.htm" && $session[user][acctid]=="3425") output("<input type='radio' name='template' value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" checked":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
                        }
                        output("<input type='submit' class='button' value='Speichern'></form>",true);
                }
        }else{
                output("`c`b`\$FEHLER!!!`b`c`&Kann den Ordner mit den Skins nicht finden. Bitte benachrichtige den Admin!!");
        }
        addnav("","prefs.php?op=save");
        addnav("Zurück","prefs.php");

} elseif ($_GET[op]=="avatarupload"){
        if ($HTTP_POST_FILES['probe']){
            $uploaddir = 'uploads/';
        output("<pre>",true);
        $t=$HTTP_POST_FILES['probe']['type'];
        if ($t=='image/gif'){
                if ($session['user']['avatar2']!='') unlink($session['user']['avatar2']);
            $bild=copy($HTTP_POST_FILES['probe']['tmp_name'],$uploaddir.$session['user']['acctid'].".gif");
            output("<b>Upload beendet!</b><br>",true);
            $session['user']['avatar2']=$uploaddir.$session['user']['acctid'].".gif";
            $session['user']['useavatar2']=0;
        }elseif ($t=='image/jpeg' || $t=='image/jpg' || $t=='image/pjpeg'){
                if ($session['user']['avatar2']!='') unlink($session['user']['avatar2']);
            $bild=copy($HTTP_POST_FILES['probe']['tmp_name'],$uploaddir.$session['user']['acctid'].".jpg");
            output("<b>Upload beendet!</b><br>",true);
                $session['user']['avatar2']=$uploaddir.$session['user']['acctid'].".jpg";
                $session['user']['useavatar2']=0;
        }elseif ($t=='image/jpg'){
                if ($session['user']['avatar2']!='') unlink($session['user']['avatar2']);
            $bild=copy($HTTP_POST_FILES['probe']['tmp_name'],$uploaddir.$session['user']['acctid'].".jpg");
            output("<b>Upload beendet!</b><br>",true);
                $session['user']['avatar2']=$uploaddir.$session['user']['acctid'].".jpg";
                $session['user']['useavatar2']=0;
        }else{
                output("Keine gültige Dateiendung. Erlaubt sind nur .jpg und .gif!");
        }
               output("<img src='".$session['user']['avatar2']."'><br>",true);
        }else{
                output("`7Hier hast du die Möglichkeit, ein Avatar für deine Bio hochzuladen. Wie auch extern verlinkte Avatare dürfen
                diese Dateien nicht mehr als 200x200 Pixel groß sein. Als Dateiendungen sind .gif und .jpg erlaubt. Pro Account
                wird nur ein Avatar zugelassen. Die Admins behalten sich das Recht vor, Bilder die gegen die Nutzungsbedingungen verstoßen,
                kommentarlos zu löschen und den User im Extremfall vom Server zu verweisen.`n`n");
            output("`@`bAvatar Uploadservice`b:`n`n");
            output("<table border=0><tr>",true);
            output("<td width='50%'>`@Dein Bisheriges Bild:<br><br>",true);
            if ($session['user']['avatar2']!=''){
                $info = getimagesize($session['user']['avatar2']);
            if ($info[0]>200)
            {
                $msg="`\$Ungültiger Avatar! Zu Breit`0`n";
            }
            elseif ($info[1]>200)
            {
                $msg="`\$Ungültiger Avatar! Zu Hoch`0`n";
            }else{
                $msg="<img src='".$session['user']['avatar2']."'>";
            }
        }else{
                $msg="Kein Bild hochgeladen";
        }
            output($msg."<br></td><td>",true);
            output("<form action='prefs.php?op=avatarupload' enctype='multipart/form-data' method='POST'>",true);
        output("<input type='file' name='probe'><br>",true);
        output("<input type='submit' value='Senden'>",true);
        output("</form>",true);
        output("</td></tr></table>",true);
        addnav("","prefs.php?op=avatarupload");

        }
    addnav("Zurück","prefs.php");
} else {

checkday();
if ($session[user][alive]){
        addnav("Zurück zum Dorf","village.php");
}else{
        addnav("Zurück zu den News","news.php");
}
if (count($_POST)==0){
}else{
                /* Update Part 2/2 by Serena of Pandea-Island 2008-06-27 */
                /* start modification
                Skinauswahl war in eigener "subpage" ->  Fehler beim Skin-Wechsel in den Profilinhalten
                Diverse Inhalte (emailadresse, biography, etc ...) wurden als "leere" Felder mitgespeichert, Variablen blieben unbefüllt
                Ab nun werden die leeren Inhalte "berücksichtigt", im weitesten Sinne von den Skin ausgeklammert
                Beim Abspeichern der Skins die wird Bio etc. nicht mehr "gelöscht"/leergespeichert ^^

                Anderung: if-else Block, der die Skins/Templates vom Rest der "übriggebliebenen" Einstellungen der Hauptseite trennt
                */
                if($_GET['subop']=="skins")
                {
                        $session['prefs']['template']=$_POST['template'];
                        /* Da hab ich mir  monatelange Fehlersuche erspart - tollem, schlauem Sohn sei Dank :D */
                }
                else /* Bei Aufruf der prefs.php, und/oder speichern mit op=save - wenn keine Skins -> fahre fort:(xyz-zeilen) bis zum Ende dieses else-Blocks */
                {
                                if ($_POST[pass1]!=$_POST[pass2]){
                                                output("`#Deine Passwörter stimmen nicht überein.`n");
                                }else{
                                                if ($_POST[pass1]!=""){
                                                                if (strlen($_POST[pass1])>3){
                                                                                #$session[user][password]=md5($_POST[pass1]);
                                                                                $session[user][password]=logd_pw($_POST[pass1]);
                                                                                output("`#Dein Passwort wurde geändert.`n");
                                                                }else{
                                                                                output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");
                                                                }
                                                }
                                }
                                reset($_POST);
                                $nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>1,"template"=>1,"bio"=>1,"avatar"=>1,"invisible"=>1);
                                while (list($key,$val)=each($_POST)){
                                                if (!$nonsettings[$key]) $session['prefs'][$key]=$_POST[$key];
                                }
                                if (closetags(stripslashes($_POST['bio']),'`i`b`c`H')!=$session['user']['bio']){
                                                if ($session['user']['biotime']>"9000-01-01"){
                                                                output("`n`\$Du kannst deine Beschreibung nicht ändern. Der Admin hat diese Funktion blockiert!`0`n");
                                                }else{
                                                                $session['user']['bio']=closetags(stripslashes($_POST['bio']),'`i`b`c`H');
                                                                $session['user']['biotime']=date("Y-m-d H:i:s");
                                                }

                                }
                                if (getsetting("avatare",0)==1) {
                                                if (stripslashes($_POST['avatar'])!=$session['user']['avatar']){
                                                                $session['user']['avatar']=stripslashes(preg_replace("'[\"\'\\><@?*&#; ]'","",$_POST['avatar']));
                                                                $url=$session[user][avatar];
                                                                $bild = $_POST['avatar'];
                                        $info = getimagesize($bild);
                                                                if ($url>"" && strpos($url,".gif")<1 && strpos($url,".GIF")<1 && strpos($url,".jpg")<1 && strpos($url,".JPG")<1 && strpos($url,".png")<1 && strpos($url,".PNG")<1){
                                                                                $session[user][avatar]="";
                                                                                $msg.="`\$Ungültiger Avatar! Nur .jpg, .png, oder .gif`0`n";
                                                                }
                                                                elseif ($info[0]>200)
                                                                {
                                                                $msg.="`\$Ungültiger Avatar! Zu breit`0`n";
                                                                $session[user][avatar]="";
                                                                }
                                                                elseif ($info[1]>200)
                                                                {
                                                                $msg.="`\$Ungültiger Avatar! Zu hoch`0`n";
                                                                $session[user][avatar]="";
                                        }
                                                }
                                }
                                if ($_POST[email]!=$session[user][emailaddress]){
                                                if (is_email($_POST[email])){
                                                                if (getsetting("requirevalidemail",0)==1){
                                                                                output("`#Die E-Mail-Adresse kann nicht geändert werden, die Systemeinstellungen verbieten es. (E-Mail-Adressen können nur geändert werden, wenn der Server mehr als einen Account pro Adresse zulässt.) Sende eine Petition, wenn du deine Adresse ändern willst, weil sie nicht mehr länger gültig ist.`n");
                                                                }else{
                                                                                output("`#Deine E-Mail-Adresse wurde geändert.`n");
                                                                                $session[user][emailaddress]=$_POST[email];
                                                                }
                                                }else{
                                                                if (getsetting("requireemail",0)==1){
                                                                                output("`#Das ist keine gültige E-Mail-Adresse.`n");
                                                                }else{
                                                                                output("`#Deine E-Mail-Adresse wurde geändert.`n");
                                                                                $session[user][emailaddress]=$_POST[email];
                                                                }
                                                }
                                }
                                if ($session['user']['usercolor']!=$_POST[col]) $session['user']['usercolor']=$_POST['col'];
                                if ($session['user']['superuser']>=2 || $juwi==1) $session['user']['invisible'] = $_POST['invisible'];
                                else $session['user']['invisible'] = '0';
                                if ($session['user']['groeße']!=$_POST['groeße']) $session['user']['groeße'] = $_POST['groeße'];
                                if ($session['user']['gewicht']!=$_POST['gewicht']) $session['user']['gewicht'] = $_POST['gewicht'];
                                if ($session['user']['augenfarbe']!=$_POST['augenfarbe']) $session['user']['augenfarbe'] = $_POST['augenfarbe'];
                                if ($session['user']['haarfarbe']!=$_POST['haarfarbe']) $session['user']['haarfarbe'] = $_POST['haarfarbe'];
                                if ($session['user']['specialdesc']!=$_POST['specialdesc']) $session['user']['specialdesc'] = $_POST['specialdesc'];
                                /* 2nd Update Part 1/3 by Serena of Pandea-Island 2008-07-02 - neues Feld familie hinzugefuegt */
                                if ($session['user']['familie']!=$_POST['familie']) $session['user']['familie'] = $_POST['familie'];
                                output("$msg");
                }
                /* Ende Update Part 2/2 by Serena of Pandea-Island 2008-06-27 */
        output("`nEinstellungen gespeichert");
}
        if ($session['user']['switchavatar']==0){
            $form=array(
            "Einstellungen,title"
            ,"emailonmail"=>"E-Mail senden wenn du eine Ye Olde Mail bekommst?,bool"
            ,"systemmail"=>"E-Mail bei Systemmeldungen senden (z.B. Niederlage im PvP)?,bool"
            ,"dirtyemail"=>"Kein Wortfilter für Ye Olde Mail?,bool"
            ,"nosounds"=>"Die Sounds deaktivieren?,bool"
    //      ,"language"=>"Sprache (noch nicht wählbar),enum,en,English,de,Deutsch,dk,Danish,es,Español"
            ,"Charakter,title"
            ,"bio"=>"Kurzbeschreibung des Charakters,textarea,50,10`n"
            ,"avatar"=>"Link auf einen Avatar`n(Bilddatei - maximal 200x200 Pixel)`n"
            );
        }else{
            $form=array(
            "Einstellungen,title"
            ,"emailonmail"=>"E-Mail senden, wenn du eine Ye Olde Mail bekommst?,bool"
            ,"systemmail"=>"E-Mail bei Systemmeldungen senden (z.B. Niederlage im PvP)?,bool"
            ,"dirtyemail"=>"Kein Wortfilter für Ye Olde Mail?,bool"
            ,"nosounds"=>"Die Sounds deaktivieren?,bool"
    //      ,"language"=>"Sprache (noch nicht wählbar),enum,en,English,de,Deutsch,dk,Danish,es,Español"
            ,"Charakter,title"
            ,"bio"=>"Kurzbeschreibung des Charakters,textarea,50,10`n"
            );

            output("`n`n`b`@AVATAR`n`b");
            if ($session['user']['avatar2']!=''){
               if ($session['user']['useavatar2']==0){
                  output("Dein Avatar ist");
                  $info = getimagesize($session['user']['avatar2']);
                  if ($info[0]>200){
                     output("`\$ungültig! Zu breit. Lade bitte eine kleinere Datei hoch!`0");
                  }elseif ($info[1]>200){
                     output("`\$ungültig! Zu hoch. Lade bitte eine kleinere Datei hoch!`0");
                  }else{
                     output("`@gültig! <a href='prefs.php?op=activateava'>Aktivieren</a>",true);
                     addnav("","prefs.php?op=activateava");
                  }
               }else{
                  output("Dein Avatar:<br><img src='".$session['user']['avatar2']."' border=0>",true);
               }
            }else{
               output("`4Kein Bild als Avatar hochgeladen`0");
            }
            output("`n`n`n`b`@SETTINGS`b`n");
        }
        $r=$session[user][race];
        $form['groeße']="Körpergröße";
        $form['augenfarbe']="Augenfarbe";
        if ($r!='Echsenwesen' && $r!='Gargoyle' && $r!='Lykaner') $form['haarfarbe']="Haarfarbe";
        if ($r=='Echsenwesen') $form['haarfarbe']="Schuppenfarbe";
        if ($r=='Lykaner') $form['haarfarbe']="Fellfarbe";
        $form['gewicht']="Gewicht";
        if ($r=='Zwerg') $form['specialdesc']="Bartlänge";
        if ($r=='Echsenwesen') $form['specialdesc']="Schwanzlänge";
        if ($r=='Lichtgestalt') $form['specialdesc']="Falls Engel: Spannweite";
        /* 2nd Update Part 2/3 by Serena of Pandea-Island 2008-07-02 - neues Feld familie hinzugefuegt */
        $form['familie']="Familie";


        if ($session['user']['superuser']>=2 || $juwi==1) $form["invisible"] = "Unsichtbar in der Spielerliste?,bool";
        output("
        <br><br><br><b><u>`^Profil`7</u></b><br><br><form action='prefs.php?op=save' method='POST'>",true);
        output("
        `7Neues Passwort: <input name='pass1' type='password'> (lasse das Feld leer, wenn du es nicht ändern willst)`n
        Wiederholen: <input name='pass2' type='password'>`n
        E-Mail-Adresse: <input name='email' value=\"".HTMLEntities($session['user']['emailaddress'])."\">`n
        ",true);
        $prefs = $session['prefs'];
        $prefs['bio'] = $session['user']['bio'];
        $prefs['invisible'] = $session['user']['invisible'];
        $prefs['groeße'] = $session['user']['groeße'];
        $prefs['gewicht'] = $session['user']['gewicht'];
        $prefs['haarfarbe'] = $session['user']['haarfarbe'];
        $prefs['augenfarbe'] = $session['user']['augenfarbe'];
        $prefs['specialdesc'] = $session['user']['specialdesc'];
        /* 2nd Update Part 3/3 by Serena of Pandea-Island 2008-07-02 - neues Feld familie hinzugefuegt */
        $prefs['familie'] = $session['user']['familie'];

        if (getsetting("avatare",0)==1) {
                 $prefs['avatar'] = $session['user']['avatar'];
        } else {
                $prefs['avatar'] = "(kein Avatar erlaubt)";
        }
        showform($form,$prefs);
        output("`n`n`n`bChateinstellungen`b`n`n");
        if ($session['user']['preview']==1) output("`&Preview: <a href='prefs.php?op=previewoff'>`4ausschalten</a>",true);
        if ($session['user']['preview']==0) output("`&Preview: <a href='prefs.php?op=previewon'>`@anschalten</a>",true);
        output("`n");
        //if ($session['user']['switchavatar']==1) output("`^Avatar umschalten auf: <a href='prefs.php?op=avanull'>`4extern</a>",true);
        //if ($session['user']['switchavatar']==0) output("`^Avatar umschalten auf: <a href='prefs.php?op=avaeins'>`@Uploadfunktion</a>",true);
        //output("`n");
        if ($session['user']['activatecolor']==1) output("`&Automatische Postingfarbe: <a href='prefs.php?op=colnull'>`4deaktivieren</a>",true);
        if ($session['user']['activatecolor']==0) output("`&Automatische Postingfarbe: <a href='prefs.php?op=coleins'>`@aktivieren</a>",true);
        output("`n");
        output("`&Automatische Postingfarbe: `<input name='col' maxlength='1' value='".$session['user']['usercolor']."'>", true);
        output("<input type='submit' class='button' value='Speichern'></form>",true);

        addnav("","prefs.php?op=autocolor");
        addnav('','prefs.php?op=previewoff');
        addnav('','prefs.php?op=previewon');
        //addnav('','prefs.php?op=avanull');
        //addnav('','prefs.php?op=avaeins');
        addnav('','prefs.php?op=colnull');
        addnav('','prefs.php?op=coleins');
        output("
        </form>",true);
        addnav("","prefs.php?op=save");
        addnav("Inventar anzeigen","prefs.php?op=inventory");
        addnav("Freunde","prefs.php?op=friends");
        addnav("Skin","prefs.php?op=skin");
//        addnav("Avatarupload","prefs.php?op=avatarupload");
        $biolink="bio.php?char=".rawurlencode($session[user][login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
        addnav("Bio", $biolink);

        // Stop clueless lusers from deleting their character just because a
        // monster killed them.
        if ($session['user']['alive'] && getsetting("selfdelete",0)!=0) {
                output("`n`n`n<form action='prefs.php?op=suicide&userid={$session['user']['acctid']}' method='POST'>",true);
                output("<input type='submit' class='button' value='Charakter löschen' onClick='return confirm(\"Willst du deinen Charakter wirklich löschen?\");'>", true);
                output("</form>",true);
                addnav("","prefs.php?op=suicide&userid={$session['user']['acctid']}");
        }
}
page_footer();
?> 