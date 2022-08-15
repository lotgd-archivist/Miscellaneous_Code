
<?php
/*
Kindereditor by Kamui 26.10.2006
Idee by -Dom
*/

require_once "common.php";
isnewday(1);
page_header("Kindereditor");
$session[user][ort]='Administration';
addnav("G?Zurück zur Administration","superuser.php");
//addnav("Kinder dieser Welt","hof.php?op=kinder");
addnav("Kind neu eintragen","kindereditor.php?op=add");
addnav("Refresh","kindereditor.php");
$array=array(
    "Kind,title",
    "id"=>"ID,hidden`n",
    "name"=>"Name`n",
    "mama"=>"Mutter des Kindes(accountid`n)",
    "papa"=>"Vater des Kindes(accountid)`n",
    "geschlecht"=>"Geschlecht des Kinds,enum,0,Männlich ,1,Weiblich`n",
    "gebdat"=>"Geburtsdatum`n",
    //"unehelich"=>"Unehelich?,bool`n",
);
$kindadd = array(
          "Kind,title",
          "id"=>"ID,hidden`n",
          "name"=>"Name des Kindes`n",
          "mama"=>"Mutter des Kindes(accountid)`n",
          "papa"=>"Vater des Kindes(accountid)`n",
          "geschlecht"=>"Geschlecht des Kinds,enum,0,Männlich ,1,Weiblich`n",
          "gebdat"=>"Geburtsdatum`n",
          //"unehelich"=>"Unehelich?,bool`n",
          );


if ($_GET['op']==""){

$sql = "SELECT * FROM kinder";
    $result = db_query($sql);
    output("<table><tr class=trhead><td>Name</td><td>&nbsp;</td><td>Mama</td><td>Papa</td><td>Geburtsdatum</td>", true);
    output("<td>Edit</td>",true);
    output("<td>Löschen</td>",true);
    while ($row = db_fetch_assoc($result)) {
        output("<tr class=".($i%2?"trlight":"trdark")."><td>".$row[name],true);
        if($row['geschlecht'] == 1)
            output("<td>`c<img src=images/female.png>`c</td>",true);
        else
            output("<td>`c<img src=images/male.png>`c</td>",true);

        output("</td>", true);

        $sqlm = "SELECT name FROM accounts WHERE acctid = " . $row[mama];
        $resultm = db_query($sqlm) or die(db_error(LINK));
        if($rowm = db_fetch_assoc($resultm))
            output(" <td> ".$rowm[name]." </td> ",true);
        else
            output(" <td> `c---`c </td> ",true);
        $sqlp = "SELECT name FROM accounts WHERE acctid = " . $row[papa];
        $resultp = db_query($sqlp) or die(db_error(LINK));
        if($rowp = db_fetch_assoc($resultp))
            output(" <td> ".$rowp[name]." </td> ",true);
        else
            output("<td>`c---`c</td>",true);
        output("</td>", true);

       
        output(" <td> ".$row[gebdat]." </td>",true);
        output("<td><a href='kindereditor.php?op=edit&id=$row[id]'>Edit",true);
        addnav("","kindereditor.php?op=edit&id=$row[id]");
        output("<td><a href='kindereditor.php?op=dele&id=$row[id]'>Löschen",true);
        addnav("","kindereditor.php?op=dele&id=$row[id]");
    }
    output("</tr></table>",true);
}

if($_GET[op]=="edit"){
        $sql = "SELECT * FROM kinder WHERE id='$_GET[id]'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
    output("<form action='kindereditor.php?op=save' method='POST'>",true);
    addnav("","kindereditor.php?op=save");
    showform($array,$row);
    output("</form>",true);
}else if($_GET[op]=="dele"){
    $sql = "DELETE FROM kinder WHERE id='$_GET[id]'";
    db_query($sql);
    redirect("kindereditor.php");
}else if($_GET[op]=="save"){
if ((int)$_POST[id]>0){
$sql = "UPDATE kinder SET name=\"$_POST[name]\",mama=\"$_POST[mama]\",papa=\"$_POST[papa]\",geschlecht=\"$_POST[geschlecht]\",info=\"$_POST[info]\",gebdat=\"$_POST[gebdat]\",unehelich=\"$_POST[unehelich]\" WHERE id='$_POST[id]'";
       
     }
     db_query($sql);
    redirect("kindereditor.php");
    }
if($_GET['op']=="add"){
output("<form action='kindereditor.php?op=save2' method='POST'>",true);
addnav("","kindereditor.php?op=save2");
output("<input type='submit' class='button' value='Speichern'>",true);
showform($kindadd,"");
output("</form>",true);
addnav("Zurück","kindereditor.php");
}
if($_GET['op']=="save"){
// kind=\"$_POST[kind]\", 
$sql = "UPDATE kinder SET name=\"$_POST[name]\", mama=\"$_POST[mama]\", papa=\"$_POST[papa]\" WHERE id=\"$_GET[id]\"";
db_query($sql) or die(db_error(LINK));
output("Die Änderungen wurden übernommen.");
addnav("Zurück","kindereditor.php");
}
if($_GET['op']=="save2"){
//kind,         '$_POST[kind]',
$sql = "INSERT INTO kinder (id,name,mama,papa) VALUES ('$_POST[id]','$_POST[name]','$_POST[mama]','$_POST[papa]')";
db_query($sql) or die(db_error(LINK));
output("Die Änderungen wurden übernommen.");
addnav("Zurück","kindereditor.php");
}

page_footer();
?> 
