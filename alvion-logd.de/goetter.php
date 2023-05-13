
<?php

//Turm der Götter

require_once "common.php";
addcommentary();

page_header("Turm der Götter");
$session['user']['standort']="Turm der Götter";

addnav("Turm");
addnav("Im Turm übernachten","goetter.php?op=logout");
addnav("Wege");
addnav("Zurück teleportieren","turm.php");

switch($_GET['op']){
    case 'logout':
        debuglog("logged out im Turm der Götter");
        $session['user']['donationconfig']=serialize($config);
        $session['user']['location']=25;
        $session['user']['loggedin']=0;
        saveuser();
    
        $file = fopen('./cache/c'.$session['user']['acctid'].'.txt','w');
        fputs($file,'');
        fclose($file); 

        $session=array();
        redirect("index.php");
    break;
    case 'erwachen':
        output("`2Gut erholt wachst du im Turm der Götter auf und bist bereit für neue Abenteuer.");
        $session['user']['location'] = 0;
        addnav("Tägliche News","news.php");
    break;

    default:
        output("`^`c`bTurm der Götter`c`bDu teleportierst dich in den Turm der Götter. Als du oben angelangt bist schaust du dich um was es hier alles gibt. Du siehst das kleine Portal was dich wieder zurück zum Turm der Elemente bringt, und viele andere Sachen. Was wirst du tun?`n`n`5Die Krieger unterhalten sich:`n");
        viewcommentary("goetter","Hinzufügen",23,"sagt",1,1);
}


page_footer();


