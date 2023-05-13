
<?php 

require_once "common.php"; 
$infos=array(
"owner"=>"Magische Ringtafel der Götter", 
"creator" => "Tiger313",
"copyright"=>"© 2005",
"homepage" => "http://www.mlcrew.de",
"version" => "1.0"
);
$infos2=array(
"owner"=>"Magische Ringtafel der Götter", 
"creator" => "Linus",
"copyright"=>"Bugfix in 2013",
"homepage" => "http://www.alvion-logd.de/logd",
"version" => "2.0"
);

page_header("Magische Ringe der Götter"); 
addnav("Weg");
addnav("R?Zurück zum Rathaus","rathaus.php"); 
addnav("Zurück zum Dorf","village.php"); 
output("`!`b`c<font size='+1'>Göttliche Ringtafel</font>`c`b`n`n",true); 
output("`@`cDu betrittst einen verfallenen Schrein. Vor dir steht auf einer kleinen Tafel geschrieben `n`n`&`b<font size='+2'>Magische Ringe der Götter</font>`b`@`n`n Darunter sind alle magischen Ringe abgebildet, deren Besitzer und wie lange sie ihn schon haben.`c`n",true);
output("<table cellspacing=2 cellpadding=2 align='center'>",true); 
output("<tr bgcolor='#cccccc'><td align='center'>`1`b#`b</td><td align='center'>`1`bRing`b</td><td align='center'>`b`1Krieger`b</td><td align='center'>`b`1Tage im Besitz`b</td></tr>",true);

for ($i = 1; $i < 26; $i++){ 
    $sql3="SELECT owner,stonename,ringday FROM stones WHERE stone = $i"; 
    $result3 = db_query($sql3);
    $row3 = db_fetch_assoc($result3); 
    $stonename = $welcher = $row3['stonename'];
    $tage = $row3['ringday'];

    $sql = "SELECT name FROM accounts WHERE acctid=".$row3['owner']." AND stones>0"; 
    $result = db_query($sql); 
    $row = db_fetch_assoc($result);

    if (db_num_rows($result) == 0) { 
        $row[name]="`b`\$Verfügbar`b"; 
        $stonename="`^Unbekannt";
        $tage2="`3bei den Göttern"; 
    }else{
//        $stonename=$welcher;
        $tage2 ="$tage Tage";
    }  
    if ($row[name] == $session[user][name]) { 
        output("<tr bgcolor='#007700'>", true); 
    } else {   
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true); 
    }
    output("<td align='center'>`&".$i."</td><td align='center'>`&`b$stonename`b</td><td align='center'>`&`b{$row[name]}`b</td><td align='center'>`&`b$tage2`b</td></tr>",true); 
}
output("</table>", true); 
//copyright
output("`n`n`n`n `2".$infos[copyright]." by <a href='".$infos['homepage']."' target='_blank'>".$infos['creator']."</a> `2Version ".$infos['version']."",true);
output("`n `2".$infos2[copyright]." by <a href='".$infos2['homepage']."' target='_blank'>".$infos2['creator']."</a> `2Version ".$infos2['version']."",true);
page_footer(); 


