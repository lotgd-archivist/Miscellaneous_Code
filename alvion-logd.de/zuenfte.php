
<?php


/*
*****************************************************************************************************
*                                                                                                    *
*                                    Jobystem Beta 0.1                                                *
*     Autor    : Aramus/Taikun                                                                            *
*     Kontakt    : redwing13@web.de                                                                        *
*                                                                                                    *
*                                                                                                    *
*        Dieses Script darf ohne Einverständniserklärung, nicht von anderen Usern benutzt werden.    * 
*        Sollte solch ein Vergehen festgestellt werden, werden gerichtliche Konsequenzen folgen.        *
*                                                                                                    *
* Copyright © by Benjamin Rouzieres 2008                                                            *
*                                                                                                    *
*****************************************************************************************************
*/



require_once "common.php";
page_header("Die Zünfte");

$sql = "SELECT name, sex FROM accounts";
$res = db_query($sql);
$row = db_fetch_assoc($res);

switch($_GET['op']):
    case "";
    
    $sql = "SELECT charid FROM berufe";
    $res=db_query($sql);
    $row=db_fetch_assoc($res);
    
if($session['user']['acctid']!=$row['charid']){
        
        
        
    output("Herzlich Willkommen in den Zünften Alvions, ".($row['sex']?"ehrenwerte":"ehrenwerter")." ".$session['user']['name']." . Hier könnt Ihr euch eine Beschäftigung suchen, falls ihr in Goldsorgen steckt. Hier habt Ihr die Angebote!");
    
    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Berufsname</td><td>Freie Stellen</td></tr>",true);
    $sql = "SELECT name, anzahl, id FROM berufsauswahl ORDER BY name ASC";
    $row = db_fetch_assoc($res);
    $res = db_query($sql) or die(db_error(LINK));
   
    
    if (db_num_rows($res)==0){

    output("<tr class='trdark'><td colspan=5 align='center'>`&`iDerzeit nichts frei!`i`0</td></tr>",true);
addnav("Zurück","superuser.php");
}  else {
    addnav("Doch lieber nicht","superuser.php");
while ($row = db_fetch_assoc($res)) {

    $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
    output("<tr class='".$bgclass."'><td><a href='zuenfte.php?op=getjob&id=".$row['id']."' onClick='return confirm(\"Willst du wirklich diesen Beruf?\");'>".$row['name']."</a></td><td>".$row['anzahl'],true);
    output("</td></tr>",true);
    addnav("","zuenfte.php?op=getjob&id=".$row['id']);
}
}
output("</table>",true);
output('</form>',true);
    
    
    
    
} else {
    output("Herzlich Willkommen in den Zünften Alvions, ".($row['sex']?"ehrenwerte":"ehrenwerter")." ".$session['user']['name']." . Wie ich sehe, habt Ihr eine Beschäftigung. Was wollt Ihr also hier?");
}
    
    break;

    
    case "getjob";
    
    
        $sql = "SELECT * FROM berufsauswahl WHERE id='$_GET[id]'";
        $res = db_query($sql);
        $row = db_fetch_assoc($res);

        
            $sql1 = "INSERT INTO berufe (charid,skill,bname,bgebaeude,berufid) VALUES ('".$session['user']['acctid']."','0','".$row[name]."','0','".$_GET[id]."')";
            db_query($sql1);
            $sql2 = "UPDATE berufsauswahl SET anzahl = anzahl - 1 WHERE name='{$_GET[id]}'";
            db_query($sql2) or die(db_error(LINK));
            output("Erfolgreich eingetragen!");
    break;



endswitch;

addnav("Zur Grotte","superuser.php");
page_footer();
?>

