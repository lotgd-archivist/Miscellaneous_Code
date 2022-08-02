<?php
/*

Spam-Protection
aus der Todo:
Pflichtfeld Mail-adresse, wenn nicht eingeloggt

Speicherung von Session-ID und IP und anzeige dessen (+ link zu domaintools für die IP)

Step 1: Speicherung Verbindungsdaten (IP, Hostnamen, Proxies, etc)
Step 2: Session-Daten
Step 3: Stopforumspam-Api ?
Step 4: ... mehr überlegen


aragon
2014-04-08    Anti-Spam-Mod ... Step 1
*/

require_once "common.php";


if ($_GET['op']=="faq")
{
    $id=(int)$_GET['id'];

    if($id>0)
    {
        $row=db_fetch_assoc(db_query("SELECT * FROM ruleset WHERE id={$id}"));

        popup_header($row['title']);
        output("<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n",true);
        output("`c`b".$row['subtitle']."`b`c`n`n");

        $res=db_query("SELECT * FROM rules WHERE rsid={$id} ORDER BY orderid ASC");

        while($row=db_fetch_assoc($res))
        {
            $content=$row['content'];

            $dynamic=$row['variable'];

            if(strlen($dynamic)>0)
            {
                $dynamic=explode("\n",$dynamic); // ** every line is 1 setting
                while(list($k,$v)=each($dynamic))
                {
                    $v=explode(":",$v); // 3-4 options
                    $set=explode(",",$v[0]);
                    $text="";

                    if($set[0]=="setting")
                    {
                        $v[1]=explode(",",$v[1]);
                        $case=getsetting($v[1][0],$v[1][1]); // getsetting(setting-name, default value)

                        if($case==$v[1][2])
                            $text=$v[2];
                        else
                            $text=$v[3];
                    }
                    elseif($set[0]=="getsetting")
                    {
                        $v[1]=explode(",",$v[1]);
                        $text=getsetting($v[1][0],$v[1][1]); // getsetting(setting-name, default value)
                    }
                    else
                    {
                        $text=$v[1];
                    }

                    $content=str_replace("{{".$set[1]."}}",$text,$content);
                }
            }
            output("`^".$row['id']." ".$row['title']."`@`n".$content."`n`n");
        }

    }
    else
    {

        popup_header("Frequently Asked Questions (FAQ)");
        output("
        `^Willkommen bei Legend of the Green Dragon. `n
        `n`@
        Eines Tages wachst du in einem Dorf auf. Du weisst nicht warum. Verwirrt läufst du durch das Dorf, bis du schliesslich auf den Dorfplatz stolperst. Da du nun schonmal da bist, fängst du an, lauter dumme Fragen zu stellen. Die Leute (die aus irgendeinem Grund alle fast nackt sind) werfen dir alles mögliche an den Kopf. Du entkommst in eine Kneipe, wo du in der nähe des Eingangs ein Regal mit Flugblättern findest. Der Titel der Blätter lautet: \"Fragen, die schon immer fragen wolltest, es dich aber nie getraut hast\". Du schaust dich um, um sicherzustellen, dass dich niemand beobachtet, und fängst an zu lesen:`n
        `n
        \"Du bist also ein Newbie. Willkommen im Club. Hier findest du Antworten auf Fragen, die dich quälen. Nun, zumindest findest du Antworten auf Fragen, die UNS quälten. So, und jetzt lese und lass uns in Ruhe!\" `n
        `n
        `bInhalt:`b`n
        ",true);
        $res=db_query("SELECT * FROM ruleset ORDER BY orderid ASC;");
        while($row=db_fetch_assoc($res))
        {
            $url="petition.php?op=faq&id=".$row['id']."";
            output("<a href=\"{$url}\">{$row['title']}</a>`n",true);
        }
        output("`n
        ~Danke,`n
        das Management.`n
        ",true);

    }

}else{
    popup_header("Anfrage für Hilfe");
    if ( ( count ( $_POST ) > 0 ) and ( isset ( $_POST['description'] ) ) and ( trim ( $_POST['description'] != "" ) ) ) {
        $reply = false;
        if ($_POST['pid']!='') {
            $sql = 'SELECT petitionid,subject FROM petitionmail WHERE MD5(CONCAT(petitionid,msgfrom))="'.$_POST['pid'].'"';
            $result = db_query($sql);
            if (db_num_rows($result)!=0) {
                $reply = true;
                $row = db_fetch_assoc($result);
            }
        }
        if (!$reply) {
            unset($_POST['pid']);
            $p = $session[user][password];
            unset($session[user][password]);
#            $sql = "INSERT INTO petitions (author,date,body,pageinfo,lastact) VALUES (".(int)$session[user][acctid].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\",NOW())";

        // *** ANTI SPAM Mod

        $radd=$_SERVER['REMOTE_ADDR'];
        $rnam=$_SERVER['REMOTE_HOST'];

        $additional="";

        $proxy_headers = array(
                'HTTP_VIA',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_FORWARDED',
                'HTTP_CLIENT_IP',
                'HTTP_FORWARDED_FOR_IP',
                'VIA',
                'X_FORWARDED_FOR',
                'FORWARDED_FOR',
                'X_FORWARDED',
                'FORWARDED',
                'CLIENT_IP',
                'FORWARDED_FOR_IP',
                'HTTP_PROXY_CONNECTION'
            );
            foreach($proxy_headers as $x){
                if (isset($_SERVER[$x])) $additional.=$x."=".$_SERVER[$x].",";
            }

            $sql = "INSERT INTO petitions (author,date,body,pageinfo,lastact,
            ip,
            hostname,
            additionalinfo
            )
            VALUES (".(int)$session[user][acctid].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\",NOW(),
            \"{$radd}\",
            \"".urlencode($rnam)."\",
            \"".urlencode($additional)."\"
            )
            ";
            db_query($sql);
            $session[user][password]=$p;
        }
        else {
            petitionmail('RE: '.$row['subject'],$_POST['description'],$row['petitionid'],$session['user']['acctid']);
        }
        output("Deine Anfrage wurde an die Admins gesendet. Bitte hab etwas Geduld, die meisten Admins
        haben Jobs und Verpflichtungen ausserhalb dieses Spiels. Antworten und Reaktionen können eine Weile dauern.");

    }else{
        if ($_GET['pid']!='') {
            $pid = $_GET['pid'];
            $sql = 'SELECT body FROM petitionmail WHERE MD5(CONCAT(petitionid,msgfrom))="'.$pid.'"';
            $result = db_query($sql);
            if (db_num_rows($result)==0) {
                output('`c`b`4Die Anfrage existiert nicht. Bitte erstelle eine neue Anfrage!`0`b`c`n`n');
                $charname = $email = $description = $pid = '';
            }
            else {
                $row = db_fetch_assoc($result);
                preg_match('/\[email\] = (.+)/',$row['body'],$email);
                $email = trim($email[1]);
                preg_match('/\[charname\] = (.+)/',$row['body'],$loginname);
                $charname = trim($loginname[1]);
                $description = "\n\n----- Deine Anfrage -----\n".$row['body'];
            }
        }
        else {
            $charname = $email = $description = $pid = '';
        }
        output("<form action='petition.php?op=submit' method='POST'>
        <input type='hidden' name='pid' value='$pid'>`n
        Name deines Characters: <input name='charname' value='$charname'>`n
        Deine E-Mail Adresse: <input name='email' value='$email'>`n
        Beschreibe dein Problem:`n
        <textarea name='description' cols='30' rows='5' class='input'>$description</textarea>`n
        <input type='submit' class='button' value='Absenden'>`n
        Bitte beschreibe das Problem so präzise wie möglich. Wenn du Fragen über das Spiel hast,
        check die <a href='petition.php?op=faq'>FAQ</a>. `nAnfragen, die das Spielgeschehen betreffen, werden
        nicht bearbeitet - es sei denn, sie haben etwas mit einem Fehler zu tun.
        </form>
        ",true);

        $radd=$_SERVER['REMOTE_ADDR'];
        $rnam=$_SERVER['REMOTE_HOST'];

        output("Spam-Protection: folgende Daten werden zusätzlich gespeichert:`n
        deine IP: $radd `n
        dein Anschlussname: $rnam `n");
    }
}
popup_footer();
?>