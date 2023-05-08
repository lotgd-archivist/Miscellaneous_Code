
<?php
/*
* Version:    15.05.2004
* Author:    lordraven
* Email:        logd@anpera.de
* 
* Purpose:    Administration for Depositboxes
*        
* BETA !!
*
*/

    
require_once("common.php");

page_header("Schliessfachverwaltung");

if ($_GET[op]=="drin"){
    $sql="SELECT * FROM depositbox WHERE boxnr=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`n`@Fachnummer: `^`b$row[boxnr]`b");
    output("`n`@Edelsteine: `^`b$row[value]`b");
    $sql = "SELECT name FROM accounts WHERE acctid=$row[acctid]";
    $result2 = db_query($sql);
    $row2  = db_fetch_assoc($result2);
    output("`n`@Besitzer: `^`b$row[acctid]`b ($row2[name]`^)");
    addnav("Schliessfachverwaltung","depositbox.php");
    addnav("Schliessfach löschen","depositbox.php?op=delete&id=$_GET[id]&acctid=$row[acctid]");
}else if($_GET[op]=="delete"){
    output("`n`6Achtung - beim Löschen des Schließfachs werden alle Inhalte mit gelöscht.");
    addnav("Trotzdem löschen","depositbox.php?op=delete2&id=$_GET[id]&acctid=$_GET[acctid]");
    addnav("Zurück zur Schliessfachverwaltung","depositbox.php");
}else if($_GET[op]=="delete2"){
    $sql="DELETE FROM depositbox WHERE boxnr=$_GET[id]";
    db_query($sql);
    $session[user][depositbox]=0;
    output("`6`nDas Schliessfach Nr.`^ ".$_GET[id]." `6wurde aufgelöst");
    systemmail($_GET[acctid],"Schliessfach gelöscht","`@Dein Schliessfach Nr. `4".$_GET[id]." `@wurde gelöscht.");
    addnav("Zurück zur Schliessfachverwaltung","depositbox.php");
}else if($_GET[op]=="new"){
    output("`6`nHier kannst Du ein neues Schliessfach für einen Benutzer anlegen");
    output("`6`nName des Benutzers:`n");
    output("<form action='depositbox.php?op=new2' method='POST'>`6Name:<input name='depboxuser' id='depboxuser' accesskey='b' width='30'>Edelsteine:<input name='value' id='value' accesskey='b' width='2'>",true);  
        output("<input type='submit' class='button' value='OK'></form>",true); 
        output("<script language='javascript'>document.getElementById('depboxuser').focus();</script>",true); 
        addnav("","depositbox.php?op=new2");
    addnav("Zurück zur Schliessfachverwaltung","depositbox.php");
}else if($_GET[op]=="new2"){
    $sql="SELECT acctid, name FROM accounts where LOGIN='".addslashes($_POST['depboxuser'])."'";
    $result = db_query($sql) or die(db_error(LINK)); 
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        $num=(int)$row[acctid];
        $wert=(int)$_POST[value];
        $sql="SELECT * FROM depositbox WHERE acctid=".$num."";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            output("`6`nFür diesen Benutzer existiert bereits ein Schliessfach");
        }else{
            $sql="INSERT INTO depositbox (acctid,value) VALUES (".$num.",".$wert.")";
            db_query($sql);
            output("`n`6Das Schliessfach für `^".$row['name']."`6 wurde angelegt.");
        }
    }else{
        output("`n`6Der Benutzer ist nicht bekannt");
    }
    addnav("Zurück zur Schliessfachverwaltung","depositbox.php");
}else{
    output("`@`b`cDie Schliessfachuebersicht`c`b`n`n");
    output("Wähle ein Schliessfach:`n`n");
    output("<table cellpadding=2 align='center'><tr><td>`bFach Nr.`b</td><td>`bEigentümer`b</td><td>`bAcctID`b</td><td>`bStatus`b</td></tr>",true);
    $ppp=20; // Player Per Page +1 to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Übersicht","depositbox.php?limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql="SELECT b.boxnr as boxnr, a.acctid as acctid, a.name as name, b.status as status FROM accounts a, depositbox b 
        WHERE a.acctid = b.acctid ORDER BY b.boxnr ASC LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Weitere Fächer","depositbox.php?limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Schliessfächer`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            output("<tr><td align='center'><a href='depositbox.php?op=drin&id=$row2[boxnr]'>$row2[boxnr]</a></td><td>$row2[name]</td><td>$row2[acctid]</td><td>$row2[status]</td></tr>",true);
            addnav("","depositbox.php?op=drin&id=$row2[boxnr]");
        }
    }
    output("</table>",true);
    addnav("Schliessfach anlegen","depositbox?op=new");
}
addnav("Zurück zur Bank","bank.php");
output("`n<div align='right'>`)2004 by lordraven</div>",true);
page_footer();
?>

