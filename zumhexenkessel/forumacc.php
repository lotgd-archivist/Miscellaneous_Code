<?php
  /***********************************************************************
   **        Forumeinbindung by Alkatar(Alkatar@gmx.net)        **
   **                Für phpBB2                **
   **                www.kaldacin.de                **
   ***********************************************************************/
  
                /**Einbauanleitung:**
            
            CONFIG bitte anpassen (direkt unter page_header("Forumaccount erstellen");)


            Öffne prefs.php
            Suche
                addnav("Bio", $biolink);
            Füge danach ein
            //Forumseinbindung by Alkatar
                output("`n`n<form action='forumacc.php?op=forumacc' method='POST'>",true);
                output("<input type='submit' class='button' value='forumaccount erstellen'>", true);
                output("</form>",true);
                addnav("","forumacc.php?op=forumacc");
            //Forumseinbindung by Alkatar Ende

                 **Ende Einbauanleitung**/
                
require_once "common.php";
page_header("Forumaccount erstellen");

//CONFIG - BITTE ANPASSEN

$db_name="d00b0e53";      //Name der Datenbank einsetzten
$db_prefix="phpbb3_";        //Prefix der Tabellen einsetzen
$phpBB3= true;            // phpBB3: true | phpBB2: false

//CONFIG - ENDE


if ($_GET[op]=="forumacc")
{
    $user=$session['user']['login'];
    $sql = "SELECT * FROM ".$db_name.".".$db_prefix."users where username='$user'";
    $result = db_query($sql);
    if (db_num_rows($result)==0) {
        output("`^`bHier kannst du dir einen Forumaccount erstellen`b`n");
        output("<form action='forumacc.php?op=forumaccSubmit' method='POST'>
            <table>
            <tr><td>Benutzername</td><td>{$session['user']['login']}</td></tr>
            <tr><td>Passwort:</td><td><input type='password' name='pass'></td></tr>
            <tr><td>Passwort bestätigen:</td><td><input type='password' name='pass2'></td></tr>
            <tr><td></td><td><input type='submit' class='button' value='Erstellen'></td></tr>
            </table>
            </form>",true);
        addnav("","forumacc.php?op=forumaccSubmit");
        addnav("Zurück","prefs.php");
    }
    else{
        output("`b`$ Du hast schon einen!`b");
        addnav("Zurück","prefs.php");
    }
}else if ($_GET[op]=="forumaccSubmit"){
    if($_POST['pass']==$_POST['pass2'])
    {
        $user=$session['user']['login'];
        $pass=MD5($_POST['pass']);
        $email=$session['user']['emailaddress'];
        $sql1 = "SELECT * FROM ".$db_name.".".$db_prefix."users where username='$user'";
        $result1 = db_query($sql1);
        if (db_num_rows($result1)==0)
        {
            if($phpBB3)
            {
                $hash=substr(md5($user.time()),4,16);
                $sql3 = "INSERT INTO ".$db_name.".".$db_prefix."users (group_id,username,username_clean,user_style,user_regdate,user_password,user_passchg,user_lastmark,user_email,user_lang,user_timezone,user_dateformat,user_form_salt) values ('2','$user','".strtolower($user)."','1','".time()."','$pass','".time()."','".time()."','$email','de','1','D j. M Y, H:i','$hash');";
                db_query($sql3);
                $sql4= "INSERT INTO ".$db_name.".".$db_prefix."user_group (group_id,user_id,group_leader,user_pending) values ('2','".mysql_insert_id()."',0,0);";
                db_query($sql4);
                $sql5= "UPDATE ".$db_name.".".$db_prefix."config SET `config_value` = '".mysql_insert_id()."' WHERE `config_name` = 'newest_user_id'";
                db_query($sql5);
                $sql6= "UPDATE ".$db_name.".".$db_prefix."config SET `config_value` = '".$user."' WHERE `config_name` = 'newest_username'";
                db_query($sql6);
            }
            else
            {
                $sql3 = "INSERT INTO ".$db_name.".".$db_prefix."users (username,user_regdate,user_password,user_email) values ('$user','".time()."','$pass','$email')";
                db_query($sql3);
            }
            addnav("Zurück","prefs.php");
            output("`^Benutzer erstellt. Du kannst dich jetzt als`$ {$session['user']['login']} `^mit dem Passwort einloggen");
        }
        else{
            output("`b`$ Du hast schon einen!`b");
            addnav("Zurück","prefs.php");
        }
    }
    else{
        output("Bitte zweimal das gleiche Passwort eingeben");
        addnav("Zurück","forumacc.php?op=forumacc");
    }
}
page_footer();
?> 