<?php 

Require 'dbconnect.php'; 
$link = mysql_connect($DB_HOST, $DB_USER, $DB_PASS); 
mysql_select_db($DB_NAME, $link); 

$ressource = mysql_query('SELECT * FROM `commentary` WHERE `emote` = 0', $link); 

$i = 0; 
while($row = mysql_fetch_assoc($ressource)) { 
    $commentary = $row['comment']; 
     
    $emote = 0; 
    if(substr($commentary,0,2) === '::') { 
        $commentary = substr($commentary,2); 
        $emote = 1; 
    } 
    elseif(substr($commentary,0,1) === ':') { 
        $commentary = substr($commentary,1); 
        $emote = 1; 
    } 
    elseif(strtolower(substr($commentary,0,3)) === '/me') { 
        $commentary = substr($commentary,3); 
        $emote = 1; 
    } 
    elseif(strtolower(substr($commentary,0,3)) === '/em') { 
        $commentary = substr($commentary,3); 
        $emote = 2; 
    } 
    elseif(strtolower(substr($commentary,0,2)) === '/x') { 
        $commentary = substr($commentary,2); 
        $emote = 2; 
    } 
    elseif(strtolower(substr($commentary,0,3)) === '/ms') { 
        $commentary = substr($commentary,3); 
        $emote = 3; 
    } 
    else { 
        $commentary = $commentary; 
    } 
     
    mysql_query('UPDATE commentary SET comment = "'.mysql_real_escape_string(stripslashes($commentary)).'", emote = '.$emote.' WHERE commentid = '.$row['commentid'].' '); 
     
    $i++; 
} 

print $i.' rows updated'; 
?>