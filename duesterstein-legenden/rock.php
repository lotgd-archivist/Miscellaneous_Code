
<?
require_once("common.php");
if ($session[user][locate]!=19){
    $session[user][locate]=19;
    redirect("rock.php");
}
// This idea is Imusade's from lotgd.net
// Sword added by gargamel

if ($_GET[op]=="") {
    if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1){
    addcommentary();}

    checkday();
    if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1) {
    page_header("Der Club der Veteranen");
    output("`b`c`2Der Club der Veteranen`0`c`b");
    output("`n`%Etwas veranlasst Dich, den seltsam aussehenden Felsen genauer zu
    untersuchen. Als Du den Felsen erreichst, spürst Du plötzlich eine alte Wunde, die
    Du Dir einst im Kampf gegen den Drachen zugezogen hast. Deine Wunde begint in einem
    magischen Licht zu strahlen, dass von dem Felsen zu kommen scheint.`n
    Als Du ins Licht guckst, stellt zu fest, dass dies mehr als nur ein Felsen ist. Es
    ist der Eingang in eine andere Welt. Gemeinsam mit anderen verdienten Helden, die
    eine ähnliche Wunde tragen, kannst Du ihn betreten: Den Club der Veteranen!`0");
    //output("`n");
    output("`n`QGleich neben dem Eingang entdeckst Du ein Schwert im Felsen.`0");
    output("`n`n");
    viewcommentary("veterans","Hier prahlen",30,"prahlt");
    }
    else {
    page_header("Seltsamer Felsen");
    output("`b`c`2Seltsamer Felsen`0`c`b");
    output("`nDu erreichst den seltsam aussehenden Felsen. Nachdem Du ihn Dir angesehen hast,
    und auch die nähere Umgebung eingehend betrachet hast, steht fest:`n
    `6Es ist wirklich ein seltsam aussehender Felsen.`n
    `QUnd es steckt ein Schwert im Fels!`0");
    }
    addnav("Gehe zum Schwert","rock.php?op=sword");
    addnav("Schrein der Erneuerung","rebirth.php");
    addnav("Zurück zum Dorfplatz","village.php");
}
else if ($_GET[op]=="sword") {
    page_header("Das Schwert im Felsen");
    output("`c`b`&Das Schwert im Felsen`0`b`c");
    output("`n`@Viele mutige ".($session[user][gender]?"Kämpferinnen":"Kämpfer")."
    haben schon versucht, das Schwert aus dem Fels zu ziehen. Erfolgreich war niemand!`n`n");
    output("`2Möchtest Du, `4".($session[user][name])."`2, einen Versuch wagen?");
    //$session[user][specialmisc]=e_rand(1,100);
    addnav("Versuch wagen","rock.php?op=pull");
    addnav("Zurück zum Felsen","rock.php");
}
else if ($_GET[op]=="pull") {
    page_header("Das Schwert im Felsen");
    if ($session[user][turns] <=0) {
        output("`\$`bDu hast heute keine Kraft mehr, einen Versuch zu starten. Es
        ist sowieso aussichtslos.`b`0");
    }
    else {
        $randid = e_rand(1,20);
        $match = 10;
        if ($match==$randid) {
            output("`n`2Mit aller Macht ziehst Du am Schwert, und es fühlt sich so an,
            als ob es sich lockert. Du ziehst weiter und weiter...`n`n`0");
            output("`!G `@L `#Ü `5C `3K `^W `4U `1N `2S `2C `4H `6!`n`n
            `2Du hast erfolgreich das Schwert gezogen. Du kannst nun seine unheimliche
            Macht für Dich nutzen!`n`n`0");
            output("Das Schwert wird Dich in Deinem nächsten Kampf zum Sieg führen!`0");
            addnews("`%".$session[user][name]."`3 hat das Schwert aus dem Fels ziehen können!!");
            $session[bufflist][rocksword] = array("name"=>"`&Schwert aus dem Fels",
                                            "rounds"=>2,
                                            "wearoff"=>"Das Schwert wird in Deiner Hand unsichtbar. Es ist weg!",
                                            "atkmod"=>20,
                                            "roundmsg"=>"Du wirst sterben!!",
                                            "activate"=>"offense");
        }
        else {
            output("`n`^Mit aller Kraft ziehst und rüttelst Du an dem Schwert, ohne
            dass es sich bewegt.`nDir ist es nicht gelungen! Du bist wie so viele gescheitert.`n
            `QDein Versuch kostet Dich einen Waldkampf.`0");
            $session[user][turns]-=1;
        }
    }
    addnav("Zurück zum Felsen","rock.php");
}

page_footer();
?>


