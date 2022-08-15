
<?php

// 11092004

/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net

translation: anpera
*/

if ((int)getsetting("expirecontent",180)>0){
        $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
        db_query($sql);
        $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
        db_query($sql);
}
$sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("oldmail",14)."days"))."'";
db_query($sql);

 /*
  * Wetter Mod by Kevz
  * (c) 2007 Kevin Gödecker
  * http://www.lotgd.dyndns.biz/
  *
  * SQL:
  * ALTER TABLE `settings` CHANGE `value` `value` TEXT NOT NULL
  */
  if ( getsetting('weatherActivate', 0) ) {
    $weathers = explode("\n", getsetting('weathers', ''));

    if ( count($weathers) ) {
      $weatherRandom = array_rand($weathers, 2);
      savesetting('weather', trim($weathers[$weatherRandom[1]]));
    }
  }

/* Vendor in town?
if (e_rand(1,3)==1){
        savesetting("vendor","1");
        $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('`qDer Wanderhändler ist heute im Dorf!`0',NOW(),0)";
        db_query($sql) or die(db_error($link));
}else{
        savesetting("vendor","0");
}*/

// Other hidden paths
$spec="Keines";
$what=e_rand(1,3);
if ($what==1) $spec="Waldsee";
if ($what==3) $spec="Orkburg";
savesetting("dailyspecial","$spec");

// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1) {
        $date = getsetting('gamedate','0000-01-01');
        $date = explode('-',$date);
        $date[2]++;
        switch ($date[2]) {
                case 32:
                        $date[2] = 1;
                        $date[1]++;
                        break;
                case 31:
                        if (in_array($date[1], array(4,6,9,11))) {
                                $date[2] = 1;
                                $date[1]++;
                        }
                        break;
                case 30:
                        if ($date[1]==2) {
                                $date[2] = 1;
                                $date[1]++;
                        }
                        break;
                case 29:
                        if ($date[1]==2 && ($date[0]%4!=0 || ($date[0]%100==0 && $date[0]%400!=0))) {
                                $date[2] = 1;
                                $date[1]++;
                        }
        }
        if ($date[1]==13) {
                $date[1] = 1;
                $date[0]++;
        }
        $date = sprintf('%04d-%02d-%02d',$date[0],$date[1],$date[2]);
        savesetting('gamedate',$date);
}


// this now includes the database cleanup from index.php
$old = getsetting("expireoldacct",45)-5;
$new = getsetting("expirenewacct",10);
$trash = getsetting("expiretrashacct",1);

$sql = "SELECT acctid,emailaddress FROM accounts WHERE 1=0 "
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\")\n":"")
." AND emailaddress!='' AND sentnotice=0";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);

// can't send mail on anpera.net

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
        $sql = "UPDATE houses SET owner=0 WHERE owner=$row[acctid]";
        db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner=$row[acctid]";
        db_query($sql);
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]";
        db_query($sql);

}

$old+=5;
        $delaccts = '0';
        $sql = "SELECT acctid FROM accounts WHERE superuser<=1 AND (1=0\n"
        .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$old)."\")\n":"")
        .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$new)."\" AND level=1 AND dragonkills=0)\n":"")
        .($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*($trash+1))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
        .")";
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
                $delaccts .= ','.$row['acctid'];
                if ($row['acctid']==getsetting("hasegg",0)) savesetting("hasegg","0");
        }
        $sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE kinder SET mama=0 WHERE  mama IN ($delaccts)";
        db_query($sql);
        $sql = "UPDATE kinder SET papa=0 WHERE papa IN ($delaccts)";
        db_query($sql);
        //17.12.2008
        //Haustierdatenbank
        $sql = 'DELETE FROM `rporte` WHERE acctid IN ('.$delaccts.')';
        db_query($sql) or die (db_error(LINK));
        $sql = "UPDATE houses SET owner=0 WHERE owner IN ($delaccts)";
        db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner IN ($delaccts) AND class='Schlüssel'";
        db_query($sql);
        $sql = "DELETE FROM mail WHERE msgto IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto IN ($delaccts)";
        db_query($sql);
    
        // end cleanup

savesetting("lastdboptimize",date("Y-m-d H:i:s"));
$result = db_query("SHOW TABLES");
$n = db_num_rows($result);
for ($i=0;$i<$n;$i++)
{
    //list($key,$val)=each(db_fetch_assoc($result));
    $row = db_fetch_assoc($result);
    foreach($row AS $val)
        db_query("OPTIMIZE TABLE $val");
}
?>

