<?
/*
Abandonded Castle Maze Editor
Author Lonny Luberts
version 1.1
June 2004
outputs copy and paste code for abandondedcastle.php
I did not code this mod with any database access as an admin may want to let users
make maps!  A bad map could cause errors!  Make sure all maps do NOT have any X's
(there is checking for this and the app will not die, but your player will), make
sure all corridors connect or terminate properly or you will have a confusing and
unrealistic maze!  Do NOT use too many traps as players will no longer use a feature
that constantly kills them.  Do NOT use more than one exit... the app allows for this
however 2 exits will confuse the heck out of a player.  
mazes save until cleared....
*/
require_once "common.php";
checkday();
page_header("Maze Editor");
//checkevent();
output("`c`b`&Maze Editor`0`b`c");
output("`cIt is up to you to make a maze that connects and works properly! Please use only 1 exit and at most 1 trap per maze.`c");
output("`cUse every spot of the maze before generating maze.`c");
$section=$_GET[op];
if ($_GET[op] == "clear"){
    output("`4`bAre you Sure you want to Clear this Maze?`b`n");
    addnav("Yes","mazeedit.php?op=clear2");
    addnav("No","mazeedit.php?op=reload");
}
if ($_GET[op] == "clear2"){
    $session['user']['mazeedit']="";
    redirect("mazeedit.php");
}else{
if ($session['user']['mazeedit'] == ""){
    for ($i=0;$i<143;$i++){
        $maze[$i]="x";
    }
    $session['user']['mazeedit']=implode($maze,",");
}
$maze=explode(",",$session['user']['mazeedit']);
$mapkey2="";
$mapkey3="<div align=\"center\"><table border=\"0\" cellspacing=\"0\" width=\"100%\" cellpadding=\"0\"><tr><td width=\"50%\">";
    $mapkey3.="`2`bClick a section to edit it.`b";
    $mapkey="";
    for ($i=0;$i<143;$i++){
    $keymap=ltrim($maze[$i]);
    $mazemap=$keymap;
    $mazemap.="maze.gif";
    if ($section=="$i"){
        $mapkey.="<a href=\"mazeedit.php?op=$i\"><img src=\"./images/mazeflash.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    }else{
    $mapkey.="<a href=\"mazeedit.php?op=$i\"><img src=\"./images/$mazemap\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    addnav("","mazeedit.php?op=$i");
    }
    if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){
        $mapkey="`n".$mapkey;
        $mapkey2=$mapkey.$mapkey2;
        $mapkey="";
    }
    }
    $mapkey2=$mapkey3.$mapkey2."</td>";
if ($_GET[op] == "generate"){
    output("Name your Maze!");
    output("<form action='mazeedit.php?op=generate2' method='POST'>",true);
        output("<p><input type=\"text\" name=\"mazename\" size=\"37\"></p>",true);
        output("<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>",true);
        output("</form>",true);
        addnav("","mazeedit.php?op=generate2");
}elseif ($_GET[op] <> ""){
    if ($_GET[op] == "generate2"){
        $mazename=$_POST[mazename];
        for ($i=0;$i<143;$i++){
        $mazecode.=$maze[$i];
        if ($i <142) $mazecode.=",";
    }
    output("`nCopy and Paste into abandonded castle php code.`n");
    output("If you do not have access to the code please e-mail the code to an admin.");
    output("`n`ncase 100:`n");
    $author=str_replace($session['user']['title'],"",$session['user']['name']);
    if ($session['user']['ctitle'] <> "") $author=str_replace($session['user']['ctitle'],"",$author);
    output("//author: ".$author."`n");
    output("//title: $mazename`n");
    output("\$maze = array(".$mazecode.");`n");
    output("break;`n`n");
    }else{
    if ($_GET[op] <> "reload"){
    $mapkey2.="<td width=\"50%\">";
    $mapkey2.="`n`b`2Maze tiles`b`n";
    if ($section == 5 or (($section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131) and ($section > 11 and $section < 132))) $mapkey2.="<a href=\"mazeedit.php?op=$section&piece=a\"><img src=\"./images/amaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5 and ($section <> 0 and $section <> 10 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section < 131)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=b\"><img src=\"./images/bmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section == 5 or ($section > 10 and ($section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131 and $section <> 132 and $section <> 142))) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=c\"><img src=\"./images/cmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5 and ($section <> 0 and $section <> 10 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131 and $section <> 132 and $section <> 142)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=d\"><img src=\"./images/dmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    $mapkey2.="<br>";
    if ($section == 5 or ($section > 10 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section < 132)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=e\"><img src=\"./images/emaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section == 5 or ($section > 10 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131 and $section < 132)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=f\"><img src=\"./images/fmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section == 5 or ($section > 10 and $section < 132)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=g\"><img src=\"./images/gmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section == 5 or ($section > 10 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 132)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=h\"><img src=\"./images/hmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    $mapkey2.="<br>";
    if ($section == 5 or ($section > 10 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131 and $section <> 142)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=i\"><img src=\"./images/imaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5 and ($section < 132 and $section <> 10 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131)) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=j\"><img src=\"./images/jmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 0 and $section <> 5 and $section < 132 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=k\"><img src=\"./images/kmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5 and $section < 132) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=l\"><img src=\"./images/lmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    $mapkey2.="<br>";
    if ($section > 10) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=m\"><img src=\"./images/mmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 0 and $section <> 5 and $section <> 11 and $section <> 22 and $section <> 33 and $section <> 44 and $section <> 55 and $section <> 66 and $section <> 77 and $section <> 88 and $section <> 99 and $section <> 110 and $section <> 121 and $section <> 132) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=n\"><img src=\"./images/nmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5 and $section <> 10 and $section <> 21 and $section <> 32 and $section <> 43 and $section <> 54 and $section <> 65 and $section <> 76 and $section <> 87 and $section <> 98 and $section <> 109 and $section <> 120 and $section <> 131 and $section <> 142) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=o\"><img src=\"./images/omaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=p\"><img src=\"./images/pmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    $mapkey2.="<br>";
    if ($section <> 5) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=q\"><img src=\"./images/qmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=r\"><img src=\"./images/rmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=s\"><img src=\"./images/smaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>";
    if ($section <> 5) $mapkey2.=" <a href=\"mazeedit.php?op=$section&piece=z\"><img src=\"./images/zmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"></a>`n";
    $mapkey2.="<br>";
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=a");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=b");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=c");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=d");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=e");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=f");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=g");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=h");
    if ($section == 5 or $section > 10) addnav("","mazeedit.php?op=$section&piece=i");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=j");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=k");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=l");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=m");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=n");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=o");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=p");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=q");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=r");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=s");
    if ($section <> 5) addnav("","mazeedit.php?op=$section&piece=z");
    if ($section <> 5) $mapkey2.="<img src=\"./images/zmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"> - Exit`n";
    if ($section <> 5) $mapkey2.="<img src=\"./images/pmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"> - Pit trap`n";
    if ($section <> 5) $mapkey2.="<img src=\"./images/qmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"> - Flood trap`n";
    if ($section <> 5) $mapkey2.="<img src=\"./images/rmaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"> - Crush trap`n";
    if ($section <> 5) $mapkey2.="<img src=\"./images/smaze.gif\" title=\"\" alt=\"\" style=\"width: 25px; height: 25px;\"> - Slice trap`n";
    $mapkey2.="</td>";
    }
    $mapkey2.="</tr></table>";
    
    if ($_GET[piece] <> ""){
        $piece=$_GET[piece];
        $maze[$section]=$piece;
        $session['user']['mazeedit']=implode($maze,",");
        redirect("mazeedit.php?op=reload");
    }
}
}else{
        $mapkey2.="<td width=\"50%\">";
        $mapkey2.="</td>";
        $mapkey2.="</tr></table>";
}
if ($_GET[op] <> "clear"){
addnav("Return to the Mundane","village.php");
addnav("Clear Maze","mazeedit.php?op=clear");
addnav("Generate Maze Code","mazeedit.php?op=generate");
}
}
output($mapkey2,true);
//I cannot make you keep this line here but would appreciate it left in.
output("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Abandonded Castle by Lonny @ http://www.pqcomp.com</a><br>",true);
page_footer();
?> 