
<?
if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime("+45 days"));
    $_COOKIE['template']=$_POST['template'];
}
require_once "common.php";
if ($session[user][locate]!=21){
    $session[user][locate]=21;
    redirect("prefs.php");
}
page_header("Einstellungen");

if ($HTTP_GET_VARS[op]=="suicide" && getsetting("selfdelete",0)!=0) {
   if($session[user][acctid]==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0)); 
$sql = "UPDATE items SET owner=0 WHERE owner=$HTTP_GET_VARS[userid]"; 
db_query($sql); 
$sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$HTTP_GET_VARS[userid] AND status=1"; 
db_query($sql); 
$sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$HTTP_GET_VARS[userid] AND status=0"; 
db_query($sql);
    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$HTTP_GET_VARS[userid]"; 
    db_query($sql); 
    $sql = "DELETE FROM pvp WHERE acctid2=$HTTP_GET_VARS[userid] OR acctid1=$HTTP_GET_VARS[userid]"; 
    db_query($sql) or die(db_error(LINK)); 
    $sql = "DELETE FROM accounts WHERE acctid='$HTTP_GET_VARS[userid]'";
    db_query($sql);
    output("Your character has been deleted!");
    addnews("`#{$session['user']['name']} committed suicide.");
    addnav("Login Page", "index.php");
    $session=array();
    $session[user] = array();
    $session[loggedin] = false;
    $session[user][loggedin] = false;
}else if ($HTTP_GET_VARS[op]=="inventory") { 
output("`c`bDie Besitztümer von ".$session[user][name]."`b`c`n`n"); 
output("<table border=1 cellspacing=0 cellpadding=2 align='center'><tr><td>`bItem`b</td><td>`bKlasse`b</td><td>`bWert 1`b</td><td>`bWert 2`b</td><td>`bVerkaufswert`b</td></tr>",true); 
$sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." ORDER BY class ASC"; 
$result = db_query($sql) or die(db_error(LINK)); 
if (db_num_rows($result)==0){ 
output("<tr><td colspan=4 align='center'>`&`iDu hast nichts im Inventar`i`0</td></tr>",true); 
}else{ 
for ($i=0;$i<db_num_rows($result);$i++){ 
$item = db_fetch_assoc($result); 
$bgcolor=($i%2==1?"trlight":"trdark"); 
output("<tr class='$bgcolor'><td>`&$item[name]`0</td><td>`!$item[class]`0</td><td align='right'>$item[value1]</td><td align='right'>$item[value2]</td><td>",true); 
if ($item[gold]==0 && $item[gems]==0){ 
output("`4Unverkäuflich`0"); 
}else{ 
output("`^$item[gold]`0 Gold, `#$item[gems]`0 Edelsteine"); 
} 
output("</td></tr><tr class='$bgcolor'><td align='right'>Beschreibung:</td><td colspan=4>$item[description]</td></tr>",true); 
} 
} 
output("</table>",true); 
addnav("Zurück","prefs.php");
} else {

checkday();
if ($session[user][alive] && $session[user][school]==0){
    addnav("Zurück zum Dorf","village.php");
}else{
    addnav("Zurück zu den News","news.php");
}
if (count($_POST)==0){
}else{
    if ($_POST[pass1]!=$_POST[pass2]){
        output("`#Your passwords do not match.`n");
    }else{
        if ($_POST[pass1]!=""){
            if (strlen($_POST[pass1])>3){
                $session[user][password]=$_POST[pass1];
                output("`#Your password has been changed.`n");
            }else{
                output("`#Your password is too short.  It must be at least 4 characters.`n");
            }
        }
    }
    reset($_POST);
    $nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>1,"template"=>1,"bio"=>1);
    while (list($key,$val)=each($_POST)){
        if (!$nonsettings[$key]) $session['user']['prefs'][$key]=$_POST[$key];
    }
    if (stripslashes($_POST['bio'])!=$session['user']['bio']){
        if ($session['user']['biotime']>"9000-01-01"){
            output("`n`\$You cannot modify your bio, it's been blocked by the administrators!`0`n");
        }else{
            $session['user']['bio']=stripslashes($_POST['bio']);
            $session['user']['biotime']=date("Y-m-d H:i:s");
        }
    }
       if (getsetting("avatare",0)==1) { 
              if (stripslashes($_POST['avatar'])!=$session['user']['avatar']){ 
                 $session['user']['avatar']=stripslashes($_POST['avatar']); 
              } 
       }
    if ($_POST[email]!=$session[user][emailaddress]){
        if (is_email($_POST[email])){
            if (getsetting("requirevalidemail",0)==1){
                output("`#Your email cannot be changed, system settings prohibit it.  (Emails may only be changed if the server allows more than one account per email).  Use the Petition link to ask the  server administrator to change your email address if this one is no longer valid.`n");
            }else{
                output("`#Your email address has been changed.`n");
                $session[user][emailaddress]=$_POST[email];
            }
        }else{
            if (getsetting("requireemail",0)==1){
                output("`#That is not a valid email address.`n");
            }else{
                output("`#Your email address has been changed.`n");
                $session[user][emailaddress]=$_POST[email];
            }
        }
    }
    output("Settings Saved");
}

    $form=array(
        "Preferences,title"
        ,"emailonmail"=>"Möchtest du eine Email erhalten wenn du eine priv. nachricht erhälst?,bool"
        ,"systemmail"=>"Send email for system generated messages?,bool"
        ,"dirtyemail"=>"Allow profanity in received Ye Olde Poste messages?,bool"
        ,"language"=>"Sprechen (Noch nicht eingebaut),enum,en,English,de,Deutsch,dk,Danish,es,Español"
        ,"timestamps"=>"Uhrzeit vor Chatnachrichten anzeigen?,bool" 
        ,"bio"=>"Kurze Charakterbeschreibung `n"
        ,"avatar"=>"Link auf einen Avatar`n(Bilddatei - maximal 200x200 Pixel)`n"
    );
    output("
    <form action='prefs.php?op=save' method='POST'>",true);
    if ($handle = @opendir("templates")){
        $skins = array();
        while (false !== ($file = @readdir($handle))){
            //if ($session[user][superuser]<3){      //Raven
            //    if (strpos($file,".htm")>0 && strpos($file,"thal")==0){
            //        array_push($skins,$file);
            //    }
            //}else{
                if (strpos($file,".htm")>0){
                    array_push($skins,$file);
                }
            //}
        }
        if (count($skins)==0){
            output("`b`@Aww, your administrator has decided you're not allowed to have any skins.  Complain to them, not me.`n");
        }else{
            output("<b>Skin:</b><br>",true);
            while (list($key,$val)=each($skins)){
                output("<input type='radio' name='template' value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" checked":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
            }
        }
    }else{
        output("`c`b`\$ERROR!!!`b`c`&Unable to open the templates folder!  Please notify the administrator!!");
    }
    
    output("
    New Password: <input name='pass1' type='password'> (leave blank if you don't want to change it)`n
    Retype: <input name='pass2' type='password'>`n
    Email address: <input name='email' value=\"".HTMLEntities($session['user']['emailaddress'])."\">`n
    ",true);
    $prefs = $session['user']['prefs'];
    $prefs['bio'] = $session['user']['bio'];
    if (getsetting("avatare",0)==1) { 
               $prefs['avatar'] = $session['user']['avatar']; 
       } else { 
              $prefs['avatar'] = "(kein Avatar erlaubt)"; 
       }
    showform($form,$prefs);
    output("
    </form>",true);
    addnav("","prefs.php?op=save");
addnav("Inventar anzeigen","prefs.php?op=inventory"); 

    // Stop clueless lusers from deleting their character just because a
    // monster killed them.
    if ($session['user']['alive'] && getsetting("selfdelete",0)!=0) {
        output("<form action='prefs.php?op=suicide&userid={$session['user']['acctid']}' method='POST'>",true);
        output("<input type='submit' class='button' value='Delete Character' onClick='return confirm(\"Are you sure you wish to delete your character?\");'>", true);
        output("</form>",true);
        addnav("","prefs.php?op=suicide&userid={$session['user']['acctid']}");
    }
}
page_footer();
?>


