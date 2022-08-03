<?php 
require_once "common.php"; 
############################################################################# 
# Chance History:                                                           # 
# 26.09.2004/beleggrodion: adding this History                              # 
# 26.09.2004/beleggrodion: Vom Englischen ins Deutsche übersetzen           # 
############################################################################# 

//Set quest info form 
$questInfoArray = array( 
    //"Quest Einstellung Name", 
    "title"=>"Titel", 
    "filename"=>"Dateiname", 
    "dks"=>"Drachenkills,int", 
    "level"=>"Minimum Level,int", 
    "ff"=>"Waldkämpfe,int", 
    "challenge"=>"Schwierigkeitsgrad,enum,Einfach,Leicht,Mittel,Normal,Schwer,Hohes Risiko,Extrem,Sehr Schwer", 
    "retry"=>"Erlaube Spielern es nochmal zu probieren?,bool"     
    ); 


if ($_GET[op]==""){ 
    $query = "SELECT * FROM quests ORDER BY dks ASC, level ASC, ff ASC"; 
    $result = db_query($query); 
    $rowAlternate = 1; 
    page_header("Quest Editor"); 
    output("`c`bFolgende Quests sind auf diesem Server installiert`c`b`n`n", true); 
    output("`c<table border=0 cellpadding=4 cellspacing=1 bgcolor='#999999'>",true); 
    output("<tr class='trhead'><td style='width:100px;text-align:center'><b>Action</b></td><td style='width:200px'><b>Quests</b></td><td><b>Drachenkills</b></td><td><b>Level</b></td><td><b>Runden</b></td><td><b>Schwierigkeitsgrad</b></td><td><b>Wiederholen</b></td></tr>",true); 

    while ($row = db_fetch_assoc($result)) {                         
        output("<tr class='".($rowAlternate%2?"trdark":"trlight")."'><td style='text-align:center'>",true); 
        output("<a href=\"questeditor.php?op=clear&questnum=".$row[qid]."\">Leeren</a> / ", true); 
        output("<a href=\"questeditor.php?op=delete&questnum=".$row[qid]."\">Löschen</a></td><td>", true); 
        output("`&<a href=\"questeditor.php?op=edit&questnum=".$row[qid]."\">".$row[title]."</a></td><td style='text-align:center'>`&".$row[dks]."</td><td", true); output("style='text-align:center'>`&".$row[level]."</td><td style='text-align:center'>`&".$row[ff]."</td><td>`&".$row[challenge]."</td><td style='text-align:center'>`&".($row[retry]?"`2Ja`7":"`4Nein`7")."</td></tr>", true); 
        addnav("","questeditor.php?op=clear&questnum=".$row[qid]); 
        addnav("","questeditor.php?op=edit&questnum=".$row[qid]);     
        addnav("","questeditor.php?op=delete&questnum=".$row[qid]); 
        $rowAlternate++; 
    } 
    output("</table>`c`n`n", true); 
    addnav("G?Zurück zur Grotte","superuser.php"); 
    addnav("W?Zurück zum Weltlichen","village.php"); 
    addnav("Q?Quest hinzufügen","questeditor.php?op=add"); 

}elseif ($_GET[op]=="edit"){ 
    page_header("Quest Editor"); 
    $query = "SELECT * FROM quests where qid = ".$_GET[questnum]; 
    $result = db_query($query); 
    $row = db_fetch_assoc($result); 

    output("<form action='questeditor.php?op=save&questnum=$_GET[questnum]' method='POST'>",true); 
    addnav("","questeditor.php?op=save&questnum=$_GET[questnum]"); 
    showform($questInfoArray,$row); 
    output("<input type='hidden' name='qid' value=".$_GET[questnum].">", true); 
    output("</form>",true); 
    addnav("G?Zurück zur Grotte","superuser.php"); 
    addnav("W?Zurück zum Weltlichen","village.php"); 
    addnav("l?Quests auflisten","questeditor.php"); 

}elseif ($_GET[op]=="save"){ 
    $sql = "UPDATE quests SET "; 
    reset($_POST); 
    while (list($key,$val)=each($_POST)){ 
        if (isset($questInfoArray[$key])){ 
            if ($key=="newpassword" ){ 
                if ($val>"") $sql.="password = \"$val\","; 
            }else{ 
                $sql.="$key = \"$val\","; 
            } 
        } 
    } 
    $sql=substr($sql,0,strlen($sql)-1); 
    $sql.=" WHERE qid=\"".$_POST[qid]."\""; 

    //redirect and save 
    addnav("","questeditor.php"); 
    saveuser(); 
    db_query($sql) or die(db_error(LINK));     
    header("Location: questeditor.php");     
    exit(); 

}elseif ($_GET[op]=="add"){ 
    page_header("Quest Editor"); 
     
    output("<form action='questeditor.php?op=commit' method='POST'>",true);     
    showform($questInfoArray, NULL); 
    output("</form>",true); 
    addnav("","questeditor.php?op=commit"); 
    addnav("l?Questfiles auflisten","questeditor.php"); 


}elseif ($_GET[op]=="commit"){ 
    $sql="INSERT INTO quests ("; 

    reset($_POST); 
    while (list($key,$val)=each($_POST)){ 
        if (isset($questInfoArray[$key])){             
                $sql.="$key,";             
        } 
    } 
    $sql=substr($sql,0,strlen($sql)-1); 
    $sql.=") VALUES ("; 
     
    reset($_POST); 
    while (list($key,$val)=each($_POST)){ 
        if (isset($questInfoArray[$key])){             
                $sql.="\"$val\",";             
        } 
    } 
    $sql=substr($sql,0,strlen($sql)-1); 
    $sql.=")"; 
     
    //redirect and save 
    addnav("","questeditor.php"); 
    saveuser(); 
    db_query($sql) or die(db_error(LINK));     
    header("Location: questeditor.php");     
    exit(); 

}elseif ($_GET[op]=="delete"){ 
    //Remove mask from user records 
    $sql = "SELECT acctid, name, quests FROM accounts WHERE quests > 0"; 
    $result = db_query($sql);     
    while ($row = db_fetch_assoc($result)) { 
        if($row[quests] & pow(2, $_GET[questnum])){ 
            $questsupdate = $row[quests] ^ pow(2, $_GET[questnum]);         
            if($session[user][acctid] == $row[acctid]){ 
                $session[user][quests] = $questsupdate; 
            }else{             
                $sqlupdate = "UPDATE accounts SET quests = ".$questsupdate." WHERE acctid = ".$row[acctid]; 
                db_query($sqlupdate); 
            } 
        } 
    } 
    //Remove Quest record from database 
    $sql = "DELETE FROM quests WHERE qid = \"".$_GET[questnum]."\""; 
    db_query($sql);     
    //redirect and save 
    addnav("","questeditor.php"); 
    saveuser(); 
    db_query($sql) or die(db_error(LINK));     
    header("Location: questeditor.php");     
    exit(); 

}elseif ($_GET[op]=="clear"){ 
    //Clear quest mask from all users records 
    $sql = "SELECT acctid, name, quests FROM accounts WHERE quests > 0"; 
    $result = db_query($sql);     
    while ($row = db_fetch_assoc($result)) { 
        if($row[quests] & pow(2, $_GET[questnum])){ 
            $questsupdate = $row[quests] ^ pow(2, $_GET[questnum]);         
            if($session[user][acctid] == $row[acctid]){ 
                $session[user][quests] = $questsupdate; 
            }else{             
                $sqlupdate = "UPDATE accounts SET quests = ".$questsupdate." WHERE acctid = ".$row[acctid]; 
                db_query($sqlupdate); 
            } 
        } 
    } 
    //redirect and save 
    addnav("","questeditor.php"); 
    saveuser(); 
    header("Location: questeditor.php");     
    exit(); 
} 

page_footer(); 
?> 