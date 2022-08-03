<?php
/*
//*-------------------------*
//|        Scriptet by      |
//|       °*Amerilion*°     |
//|      greenmano@gmx.de   |
//|       first seen at     |
//|      mekkelon.de.vu     |
//*-------------------------*
/*
BEDINGUNGEN
Mit den einbaue dieser oder einer abgeänderten Version dieser Datei stimme ich folgenden Bedingungen zu

1. Ich verändere keine Grundlegenden Sachen, Bugs dürfen gefixt werden, outputs dürfen umgeschrieben und die
Belohnung aus Balancing-Gründen erhöt oder gesenkt werden, solange die Veränderung nicht den Sinn der
Programmierung verändert. 

2. Die Source meines LoGDs ist jederzeit einsehbar

Bei Verstoß gegen diese Bedingung ist es nicht erlaubt dieses Script zu nutzen!!!
*/

require_once "common.php";
page_header("Name -> ID");
output("`n`c`i`tName -> ID`i`c`n`n");

if($_GET['op']==""){
    output("`3Gebe hier den Namen des Spielers ein um die ID zu bekommen:");
    output("<form action='idwiz.php?op=weiter' method='POST'>
    <input type='TEXT' name='test' width=5>`n`n
    <input type='SUBMIT' value='Weiter'></form>",true);
    addnav("","idwiz.php?op=weiter");
    addnav("Zurück","gericht.php");
}

elseif($_GET['op']=="weiter"){
    $check=$_POST['test'];
    $sql = "SELECT `acctid` FROM `accounts` WHERE `name` LIKE '%$check%' OR `login` LIKE '%$check%'";
    $query = db_query($sql) or die(db_error(LINK));
    $result = db_fetch_assoc($query);
    if(count($result)>1)
    {
    output("`3Zuviele Ids gefunden.. Bitte geben den Namen GENAUER an!");
    output("`3Gebe hier den Namen des Spielers ein um die ID zu bekommen:");
    output("<form action='idwiz.php?op=weiter' method='POST'>
    <input type='TEXT' name='test' width=5>`n`n
    <input type='SUBMIT' value='Weiter'></form>",true);
    addnav("","idwiz.php?op=weiter");
     }
    else    {output("`3Der Name ist `^".$_POST['test']."`3 - die ID ist `^".$result [acctid]);}
    addnav("Zurück","gericht.php");
}
page_footer();
?>
