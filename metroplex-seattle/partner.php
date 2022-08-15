
<?php

/*************************************************************************\
 **        Partnersystem v1.0 by Alkatar(Alkatar@gmx.net)        **
 **                www.kaldacin.de.vu                **
\*************************************************************************/

                /**Einbauanleitung:**


                Führe im phpmyadmin aus:
                    CREATE TABLE `partner` (
                    `id` INT NOT NULL AUTO_INCREMENT ,
                    `name` VARCHAR( 254 ) NOT NULL ,
                    `url` VARCHAR( 254 ) NOT NULL ,
                    PRIMARY KEY ( `id` )
                    ) TYPE = MYISAM ;


                Öffne Index.php
                Suche
                    addnav("DragonPrime","http://www.dragonprime.net",false,false,true);
                Füge danach ein
                      //Partnerstädte by Alkatar
                        addnav("Partnerstädte");
                        $sql = 'SELECT `name`, `url` FROM `partner`';
                        $result = db_query($sql);
                        while ($row = db_fetch_assoc($result)){
                            addnav("$row[name]","$row[url]",false,false,true);
                        }
                      //Partnerstädte by Alkatar [Ende]

                In die superuser.php einfügen, wo mans halt haben will;)
                    addnav("Partner","partner.php");


                 **Ende Einbauanleitung**/


require_once "common.php";
isnewday(1);
addcommentary();
page_header("Partner");
$session[user][ort]='Administration';

output("
<table align='center'><tr><td><IMG SRC=\"images/Partner.jpg\"></tr></td></table>
",true);

if ($_GET[op]==""){
    if ($_GET[del]=="1"){
        $id=$_GET[id];
        $sql = "DELETE FROM `partner` WHERE `id`=$id";
        $result = db_query($sql);
        if($result != "1") output("Fehler");
    }
    if ($_GET[aendern]=="1"){
        $name=$_POST[name];
        $url=$_POST[url];
        $id=$_GET[id];
        $sql = "UPDATE `partner` SET `name` = '$name',`url` = '$url' WHERE `partner`.`id` = '$id'";
        $result = db_query($sql);
        if($result != "1") output("Fehler");
    }
    if ($_GET[neu]=="1"){
        $name=$_POST[name];
        $url=$_POST[url];
        $id=$_GET[id];
        $sql = "INSERT INTO `partner`(`id`,`name`,`url`) VALUES ('','$name','$url')";
        $result = db_query($sql);
        if($result != "1") output("Fehler");
    }
    addnav("Edit");
    addnav("Neu","partner.php?op=neu");
    $sql = 'SELECT `id`,`name`, `url` FROM `partner`';
    $result = db_query($sql);
    output("<table>",true);
    addnav("Partner");
    while ($row = db_fetch_assoc($result)){
        $id=$row[id];
        output("<tr><td>
            <a href='partner.php?del=1&id=$id'>[Del]</a>
            <a href='partner.php?op=aendern&id=$id'>
            [Ändern]</a></td><td>$row[name]</td><td>$row[url]</td></tr>
        ",true);
        addnav("","partner.php?del=1&id=$id");
        addnav("","partner.php?op=aendern&id=$id");
        addnav("$row[name]","$row[url]",false,false,true);
    }
    output("</table>",true);
}
if ($_GET[op]=="aendern"){
    $id=$_GET[id];
    $sql = "SELECT `name`, `url` FROM `partner` where `id`='$id'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("<center><p>&nbsp;<p>&nbsp;<form action='partner.php?aendern=1&id=$id' method='POST' ><table><tr>",true);
    rawoutput("<td>Name:</td><td><input type='text' name='name' value='$row[name]'></td></tr>
           <tr><td>URL:</td><td><input type='text' name='url' value='$row[url]'></td></tr></table>",true);
    output("<input type='submit' value='Ändern'></form></center>",true);
    addnav("","partner.php?aendern=1&id=$id");
    addnav("Zurück","partner.php");
}
if ($_GET[op]=="neu"){
    output("<center><p>&nbsp;<p>&nbsp;<form action='partner.php?neu=1' method='POST' ><table><tr>",true);
    rawoutput("<td>Name:</td><td><input type='text' name='name'></td></tr>
           <tr><td>URL:</td><td><input type='text' value='http://' name='url'></td></tr></table>",true);
    output("<input type='submit' value='Erstellen'></form></center>",true);
    addnav("","partner.php?neu=1");
    addnav("Zurück","partner.php");
}
addnav("Zurück");
addnav("Z?Zurück zur Administration","superuser.php");
addnav("W?Zurück zum Freiheitsplatz","freiheitsplatz.php");
page_footer();
?>

