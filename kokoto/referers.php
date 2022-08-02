<?php

// 15082004

require_once "common.php";
isnewday(4);

$sql = "DELETE FROM referers WHERE last<'".date("Y-m-d H:i:s",strtotime(date ("r")."-".getsetting("expirecontent",180)." days"))."'";
db_query($sql);
if ($_GET['op']=="rebuild"){
	$sql = "SELECT * FROM referers";
	$result = db_query($sql);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$site = str_replace_c("http://","",$row['uri']);
		if (strpos_c($site,"/")) $site = substr_c($site,0,strpos_c($site,"/"));
		$sql = "UPDATE referers SET site='".mysql_real_escape_string($site)."' WHERE refererid='{$row['refererid']}'";
		db_query($sql);
	}
}
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Referer Optionen");
addnav("",$_SERVER['REQUEST_URI']);
addnav("Aktualisieren","referers.php?sort=".URLEncode($_GET['sort'])."");
addnav("a?Nach Anzahl sortieren","referers.php?sort=count".($_GET['sort']=="count DESC"?"":"+DESC"));
addnav("U?Nach URL sortieren","referers.php?sort=uri".($_GET['sort']=="uri"?"+DESC":""));
addnav("Z?Nach Zeit sortieren","referers.php?sort=last".($_GET['sort']=="last DESC"?"":"+DESC"));

addnav("Neu aufbauen","referers.php?op=rebuild");

page_header("Referers");
$order = "count DESC";
if ($_GET['sort']!="") $order=$_GET['sort'];
$sql = "SELECT SUM(count) AS count, MAX(last) AS last,site FROM referers GROUP BY site ORDER BY $order";
rawoutput("<table><tr><td>Anzahl</td><td>Zuletzt</td><td>URL</td></tr>");
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
	$row = db_fetch_assoc($result);
	output("<tr class='trdark'><td valign='top'>`b",true);
	output($row['count']);
	output("`b</td><td valign='top'>`b",true);
	$diffsecs = strtotime(date("c"))strtotime($row['last']);
		output((int)($diffsecs86400)."d ".(int)($diffsecs36003600)."h ".(int)($diffsecs6060)."m ".(int)($diffsecs60)."s");
	output("`b</td><td>`b".htmlspecialchars($row['site']==""?"`iKeine`i":$row['site'])."`b</td></tr>",true);
	$sql = "SELECT count,last,uri FROM referers WHERE site='".mysql_real_escape_string($row['site'])."' ORDER BY {$order}";
	$result1 = db_query($sql);
	$skippedcount=0;
	$skippedtotal=0;
	for ($k=0;$k<db_num_rows($result1);$k++){
		$row1=db_fetch_assoc($result1);
		$diffsecs = strtotime(date("c"))strtotime($row1['last']);
		if ($diffsecs<=604800){
			rawoutput("<tr class='trlight'><td>");
			output($row1['count']);
			rawoutput('</td><td valign="top">');
			output(dhms($diffsecs));
			rawoutput("</td><td valign='top'>");
			if ($row1['uri']>'')
				output("<a href='".htmlspecialchars($row1['uri'])."' target='_blank'>".htmlspecialchars(substr_c($row1['uri'],0,150))."</a>`n",true);
			else
				output("`i`bKeine`b`i`n");
			rawoutput('</td></tr>');
		}else{
			$skippedcount++;
			$skippedtotal+=$row1['count'];
		}
	}
	if ($skippedcount>0){
		output("<tr class='trlight'><td>$skippedtotal</td><td valign='top' colspan='2'>`i$übersprungene Aufzeichnungen (über eine Woche alt)`i</td></tr>",true);
	}
}
rawoutput("</table>");
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>