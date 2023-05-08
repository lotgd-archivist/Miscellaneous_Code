
<?php 

// 25052004 

/*setweather.php 
An element of the global weather mod Version 0.5 
Written by Talisman 
Latest version available at http://dragonprime.cawsquad.net 

translation: anpera 
*/ 
switch(e_rand(1,8)){ 
    case 1: 
    $clouds="Wechselhaft und kühl, mit sonnigen Abschnitten"; 
                break; 
                case 2: 
      $clouds="Warm und sonnig"; 
                break; 
                case 3: 
      $clouds="Regnerisch"; 
                break; 
                case 4: 
      $clouds="Neblig"; 
                break; 
                case 5: 
    $clouds="Kalt bei klarem Himmel"; 
                break; 
                case 6: 
      $clouds="Heiß und sonnig"; 
                break; 
                case 7: 
      $clouds="Starker Wind mit vereinzelten Regenschauern"; 
                break; 
                case 8: 
      $clouds="Gewittersturm"; 
                break;             
} 
savesetting("weather",$clouds); 

// calendar

$daysalive=getsetting("daysalive","0")+1;
savesetting("daysalive",$daysalive); 

// Houses Update - Flag Angriff nur einmal pro Tag zurücksetzen
$sql="UPDATE houses SET attacked=0 WHERE attacked=1";
db_query($sql);

// Pranger Update
$tag = getsetting("daysalive",0);
$sql="UPDATE accounts SET jailtime=0, location=0 WHERE jailtime<=$tag and jailtime<>0 and location=9";
db_query($sql);


// Vendor in town? 
// noch nicht moeglich
/*if (e_rand(1,3)==1){ 
    savesetting("vendor","1"); 
    $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('`qDer Wanderhändler ist heute im Dorf!`0',NOW(),0)"; 
    db_query($sql) or die(db_error($link)); 
}else{ 
    savesetting("vendor","0"); 
}*/ 


// this now includes the database cleanup from index.php 
$old = getsetting("expireoldacct",45)-5; 
$new = getsetting("expirenewacct",10); 
$trash = getsetting("expiretrashacct",1); 

$sql = "SELECT acctid,emailaddress FROM accounts WHERE 1=0 " 
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"") 
." AND emailaddress!='' AND sentnotice=0"; 
$result = db_query($sql); 
for ($i=0;$i<db_num_rows($result);$i++){ 
    $row = db_fetch_assoc($result); 
    mail($row[emailaddress],"LoGD Charakter verfällt", 
    " 
    Einer oder mehrere deiner Charaktere von Legend of the Green Dragon auf 
    ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']." 
    verfällt demnächst und wird gelöscht. Wenn du den Charakter retten willst, solltest 
     du dich bald möglichst mal damit einloggen! 
     Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.", 
    "From: ".getsetting("gameadminemail","postmaster@localhost.com") 
    ); 
    $sql = "UPDATE accounts SET sentnotice=1,house=0,housekey=0,marriedto=0 WHERE acctid='$row[acctid]'"; 
    if ((int)$row[acctid]==(int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0)); 
    db_query($sql); 
    $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$row[acctid] AND status=1"; 
    db_query($sql); 
    $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$row[acctid] AND status=0"; 
    db_query($sql); 
    $sql = "UPDATE items SET owner=0 WHERE owner=$row[acctid]"; 
    db_query($sql); 
    $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]"; 
    db_query($sql) or die(db_error(LINK)); 
    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]"; 
    db_query($sql); 
} 

$old+=5; 
$sql = "DELETE FROM accounts WHERE superuser<=1 AND (1=0\n" 
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"") 
.($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$new days"))."\" AND level=1 AND dragonkills=0)\n":"") 
.($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-".($trash+1)." days"))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"") 
.")"; 
//echo "<pre>".HTMLEntities($sql)."</pre>"; 
db_query($sql) or die(db_error(LINK)); 
// end cleanup 

?> 

