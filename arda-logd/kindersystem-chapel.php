<?
//26022006
// Kinder taufen by -DoM (Kindersystem von Mind of the White Dragon) http://logd.gloth.org logd@gloth.org
// An einen passenden Ort verlinken mit: addnav("Taufen", "kindersystem-chapel.php?op=taufen");
// zum Beispiel eine Kapelle oder Kirche oder Tempel oder wo Ihr sonst wollt.
// den addnav der zurück führt müsst ihr natürlich für den einbauort entsprechend anpassen.
//Diese Datei in den Root laden.

require_once "common.php";
page_header("Das Taufbecken");

if($HTTP_GET_VARS['op']==""){
    output("`c`7`bDas Taufbecken`b`c");
//    output("`n`n`4`bBruder Thomas`b`7 sagt `&\"Schön das ihr euer Kind taufen lassen wollt! Welches wollt ihr denn taufen lassen?\"`7 fragt er.`n`n");

    if($HTTP_GET_VARS[id] != "" && $HTTP_POST_VARS[tname] != "")
    {
        if($session['user']['sex'])
        {
            $art="mama";
            $art2 = "ihre";
        }
        else
        {
            $art="papa";
            $art2 = "seine";
        }
            
        $sql="UPDATE kinder SET name = '" . $HTTP_POST_VARS[tname] . "' WHERE $art = " . $session[user][acctid] . " and id = " . $HTTP_GET_VARS[id];
        $result = db_query($sql) or die(db_error(LINK));
        $sql="SELECT * FROM kinder WHERE id = " . $HTTP_GET_VARS[id];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        
        if($row[geschlecht])
            addnews($session[user][name] . " hat " . $art2 ." Tochter auf den Namen " . $HTTP_POST_VARS[tname] . " getauft.");
        else
            addnews($session[user][name] . " hat " . $art2 ."n Sohn auf den Namen " . $HTTP_POST_VARS[tname] . " getauft.");
    }
    
    if($_GET[id] != "" && $HTTP_POST_VARS[tname] == "")
    {
        addnav("Zurück","chapel.php");
        output("<form action='kindersystem-chapel.php?op=taufen&id=".$HTTP_GET_VARS[id]."' method='POST'>",true);
        output("Taufname : <input name=tname maxlength=50>`n`n", true);
        output("<input type='submit' class='button' value='Taufen'></form>",true);
        addnav("","kindersystem-chapel.php?op=taufen&id=".$HTTP_GET_VARS[id]);
    }
    else
    {
        if($session['user']['sex'])
            $sql="SELECT * FROM kinder WHERE mama = " . $session[user][acctid];
        else
            $sql="SELECT * FROM kinder WHERE papa = " . $session[user][acctid];
            
        output("<table border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td style=\"width:275px\">Name</td><td style=\"width:150px\" align=center>Geburtsdatum</td><td style=\"width:75px\" align=center>Geschlecht</td><td>&nbsp;</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        for ($i=0;$i<db_num_rows($result);$i++){        
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
            if($row['name'] == "")
                output("Neugeborenes", true);
            else
                output($row['name'],true);
            output("</td>",true);
            output("<td>",true);
                output("`c" . $row['gebdat'] . "`c",true);
            output("</td>",true);
            
            if($row['geschlecht'] == 1)
                output("<td>`c<img src=images/female.gif>`c</td>", true);
            else
                output("<td>`c<img src=images/male.gif>`c</td>", true);
                
            if($row['name'] == "")
            {
                output("<td>[<a href='kindersystem-chapel.php?op=taufen&id=".$row[id]."'>Taufen</a>]</td></tr>",true);
                addnav("","kindersystem-chapel.php?op=taufen&id=".$row[id]."");
            }
            else
                output("<td>&nbsp;</td></tr>",true);
                
            
        }    
        output("</table>",true);
    }
}
addnav("Zurück","chapel.php");
page_footer();
?>