<?php

/*************************************************************************\
 **		Partnersystem v1.0 by Alkatar(Alkatar@gmx.net)		**
 **			    www.kaldacin.de.vu				**
\*************************************************************************/


require_once "common.php";
isnewday(4);
addcommentary();
page_header("Partner");
if ($_GET['op']==''){
	if ($_GET['del']=='1'){
		$id=(int)$_GET['id'];
		$sql = "DELETE FROM `partner` WHERE `id`=$id";
		$result = db_query($sql);
		if($result != "1") output("Fehler");
	}
	if ($_GET['aendern']=='1'){
		$name=$_POST['name'];
		$url=$_POST['url'];
		$id=(int)$_GET['id'];
		$sql = "UPDATE `partner` SET `name` = '$name',`url` = '$url' WHERE `partner`.`id` = '$id'";
		$result = db_query($sql);
		if($result != "1") output("Fehler");
	}
	if ($_GET['neu']=="1"){
		$name=$_POST['name'];
		$url=$_POST['url'];
		$id=$_GET['id'];
		$sql = "INSERT INTO `partner`(`id`,`name`,`url`) VALUES ('','$name','$url')";
		$result = db_query($sql);
		if($result != "1") output("Fehler");
	}
	addnav('Edit');
	addnav('Neu','partner.php?op=neu');
	$sql = 'SELECT `id`,`name`, `url` FROM `partner`'; 
	$result = db_query($sql);
	rawoutput('<table>');
	addnav('Partner');
	while ($row = db_fetch_assoc($result)){
		$id=$row['id'];
		output("<tr><td>
			<a href='partner.php?del=1&id=$id'>[Del]</a>
			<a href='partner.php?op=aendern&id=$id'>
			[Ändern]</a></td><td>".$row['name']."</td><td>".$row['url']."</td></tr>
		",true);
		allownav("partner.php?del=1&id=$id");
		allownav("partner.php?op=aendern&id=$id");
		addnav($row['name'],$row['url'],false,false,true);
	}
	rawoutput('</table>');
}
if ($_GET['op']=='aendern'){
	$id=$_GET['id'];
	$sql = "SELECT `name`, `url` FROM `partner` where `id`='$id'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	rawoutput("<center><p>&nbsp;<p>&nbsp;<form action='partner.php?aendern=1&id=$id' method='POST' ><table><tr><td>Name:</td><td><input type='text' name='name' value='".$row['name']."'></td></tr><tr><td>URL:</td><td><input type='text' name='url' value='".$row['url']."'></td></tr></table><input type='submit' value='Ändern'></form></center>");
	allownav("partner.php?aendern=1&id=$id");
	addnav("Zurück","partner.php");
}
if ($_GET['op']=='neu'){
	rawoutput("<center><p>&nbsp;<p>&nbsp;<form action='partner.php?neu=1' method='POST' ><table><tr>");
	rawoutput("<td>Name:</td><td><input type='text' name='name'></td></tr><tr><td>URL:</td><td><input type='text' value='http://' name='url'></td></tr></table><input type='submit' value='Erstellen'></form></center>");
	allownav("partner.php?neu=1");
	addnav("Zurück","partner.php");
}
addnav('Zurück');
addnav('Z?Zurück zur Grotte','superuser.php');
addnav('W?Zurück zum Weltlichen','village.php');
page_footer();
$session['user']['standort'] = "Geheime Grotte";
?>