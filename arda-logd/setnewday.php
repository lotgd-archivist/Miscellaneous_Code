<?php

// 11092004

/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net

translation: anpera
*/

if ((int)getsetting("expirecontent",180)>0){
    $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
    db_query($sql);
    $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
    db_query($sql);
}
$sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("oldmail",14)."days"))."' AND seen=1";
db_query($sql);

switch(e_rand(1,9)){
    case 1:
    $clouds="Wechselhaft und kühl, mit sonnigen Abschnitten";
                break;
                case 2:
      $clouds="Warm und sonnig";
                break;
                case 3:
      $clouds="Regnerisch";
                break;
                case 4:
      $clouds="Neblig";
                break;
                case 5:
     $clouds="Kalt bei klarem Himmel";
                break;
                case 6:
      $clouds="Heiß und sonnig";
                break;
                case 7:
      $clouds="Starker Wind mit vereinzelten Regenschauern";
                break;
                case 8:
      $clouds="Gewittersturm";
                break;
                case 9:
      $clouds="Schneeregen";
                break;        
}
savesetting("weather",$clouds);

// Vendor in town?
if (e_rand(1,3)==1){
    savesetting("vendor","1");
    $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('`qDer Wanderhändler ist heute im Dorf!`0',NOW(),0)";
    db_query($sql) or die(db_error($link));
}else{
    savesetting("vendor","0");
}

// Other hidden paths
$spec="Keines";
$what=e_rand(1,3);
if ($what==1) $spec="Waldsee";
if ($what==3) $spec="Orkburg";
savesetting("dailyspecial","$spec");

// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1) {
    $date = getsetting('gamedate','0000-01-01');
    $date = explode('-',$date);
    $date[2]++;
    switch ($date[2]) {
        case 32:
            $date[2] = 1;
            $date[1]++;
            break;
        case 31:
            if (in_array($date[1], array(4,6,9,11))) {
                $date[2] = 1;
                $date[1]++;
            }
            break;
        case 30:
            if ($date[1]==2) {
                $date[2] = 1;
                $date[1]++;
            }
            break;
        case 29:
            if ($date[1]==2 && ($date[0]%4!=0 || ($date[0]%100==0 && $date[0]%400!=0))) {
                $date[2] = 1;
                $date[1]++;
            }
    }
    if ($date[1]==13) {
        $date[1] = 1;
        $date[0]++;
    }
    $date = sprintf('%04d-%02d-%02d',$date[0],$date[1],$date[2]);
    savesetting('gamedate',$date);
}
// Adventsspecial für Merydiâ .. dies ist auf die reale Zeit bezogen, vom 1.12. bis 24.12., jeden Tag gibt es Geschenke
// Auch für anderes nutzbar ^^
// Copyright by Leen/Cassandra (cassandra@leensworld.de)
// SQL: INSERT INTO `settings` ( `setting` , `value` ) VALUES ('weihnacht', '0');

// settings -start-
$realdatum = time();
$datum = date('m-d',$realdatum);
// settings -end-

// Datum festlegen und welcher Dezember gerade ist
/*if ($datum >= '12-01' && $datum <= '12-31')
    {
    $weihnacht = $datum;
    }
else $weihnacht = '0';
// Ende der Datumsabfrage

// speichern in Settings
savesetting('weihnacht',$weihnacht);*/
// fertig mit der Abfrage .. der Rest wird im newday.php gemacht!
// Pilzsuche by Linus & Veskara
if(mt_rand(1,5)==2) savesetting('steintroll','0');
else savesetting('steintroll','1');

// this now includes the database cleanup from index.php
$old = getsetting("expireoldacct",45)-5;
$new = getsetting("expirenewacct",10);
$trash = getsetting("expiretrashacct",1);

$sql = "SELECT acctid,emailaddress FROM accounts WHERE 1=0 "
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\")\n":"")
." AND emailaddress!='' AND sentnotice=0";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);

// can't send mail on anpera.net

    mail($row[emailaddress],"LoGD Charakter verfällt",
    "
    Einer oder mehrere deiner Charaktere von Legend of the Green Dragon auf 
    ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."
    verfällt demnächst und wird gelöscht. Wenn du den Charakter retten willst, solltest
     du dich bald möglichst mal damit einloggen!
     Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.",
    "From: ".getsetting("gameadminemail","postmaster@localhost.com")
    );
    $sql = "UPDATE accounts SET sentnotice=1,house=0,housekey=0,marriedto=0 WHERE acctid='$row[acctid]'";
    if ((int)$row[acctid]==(int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
    db_query($sql);
    $sql = "UPDATE houses SET owner=0 WHERE owner=$row[acctid]";
    db_query($sql);
    $sql = "UPDATE items SET owner=0 WHERE owner=$row[acctid]";
    db_query($sql);
    $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]";
    db_query($sql) or die(db_error(LINK));
    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]";
    db_query($sql);

}

$old+=5;
    $delaccts = '0';
    //Friedhof Skript by Samsa (Idee: Fenja)
        $delaccts = '0';
        $sql = "SELECT * FROM accounts WHERE superuser<=1 AND (1=0\n"
        .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$old)."\")\n":"")
        .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$new)."\" AND level=1 AND dragonkills=0)\n":"")
        .($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*($trash+1))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
        .")";
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
                $delaccts .= ','.$row['acctid'];
                if ($row['acctid']==getsetting("hasegg",0)) savesetting("hasegg","0");
        //Friedhof Skript by Samsa (Idee: Fenja)
        $sql="INSERT INTO graeber (name,spruch,status,level,age,titel,dk,sex) VALUES ('".$row[login]."','".$spruch."','2','".$row[level]."','".$row[age]."','".$row[title]."','".$row[dk]."','".$row[sex]."')";
        db_query($sql) or die(db_error(LINK));

                }db_free_result($result);
         $sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
     /*$sql = "DELETE FORM rporte WHERE acctid IN ($delaccts)";
    db_query($sql) or die (db_error(LINK));*/
        $sql = "UPDATE houses SET owner=0 WHERE owner IN ($delaccts)";
        db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner IN ($delaccts) AND class='Schlüssel'";
        db_query($sql);
        $sql = "DELETE FROM items WHERE owner IN ($delaccts) AND owner!=0";
        db_query($sql);
        $sql = "DELETE FROM pvp WHERE acctid2 IN ($delaccts) OR acctid1 IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "DELETE FROM mail WHERE msgto IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto IN ($delaccts)";
        db_query($sql);
        // end cleanup
        /** 
 * Legend of the Green Dragon - Das Goldene Ei (Verfallszeit) 
 * 2007 (C) by Kevin GÃ¶decker [Kevz] 
 * 
 * 
 * Den Aktuellen Besitzer des Goldenen Ei's ermitteln. Falls  
 * dieser nicht vorhanden sein sollte, oder Verfallszeit abgelaufen  
 * ist, wird der Besitzer auf "Unbekannt" zurÃ¼ckgesetzt. 
 */ 
$hasegg = getsetting('hasegg', 0); 
$expirehasegg = getsetting('expirehasegg', 14); 

$result = db_query('SELECT 1 FROM `accounts`  
                    WHERE `acctid` = "'.(int)$hasegg.'" AND  
                          `laston` < DATE_SUB(NOW(), INTERVAL '.$expirehasegg.' DAY) ') or die (db_error($sql)); 

/** 
 * Falls kein Besitzer oder die Verfallszeit abgelaufen ist, wird  
 * der Besitzer zurÃ¼ckgesetzt. 
 */ 
If ( db_num_rows($result) )   
  savesetting('hasegg', '0'); 
        //Ende Friedhof Skript

savesetting("lastdboptimize",date("Y-m-d H:i:s"));
$result = db_query("SHOW TABLES");
for ($i=0;$i<db_num_rows($result);$i++){
    list($key,$val)=each(db_fetch_assoc($result));
    db_query("OPTIMIZE TABLE $val");
}
//Dorfangriff by -DoM (http://logd.gloth.org) (logd@gloth.org) Anfang:
 $howmuchdays = getsetting("datage",0);
 $ststus = getsetting("angriff",0);
 if (($howmuchdays>1) && ($ststus==0)){
 savesetting("datage",$howmuchdays-1);
 }
 if (($howmuchdays==1) && ($ststus==0)){
 $anzahl = (e_rand(1000,3000));
 $name = array(1=>"`#He`8ili`#ge Kr`8ieg`#er`0","Ratten","Plünderer","`9G`&a`9l`&l`9i`&e`9r`0","Banditen","Hunnen","Räuber","Römer","Piraten"
 ,"Kreuzritter","Bewohner Wyrmlands","Heuschrecken","Centauren","Titanen","Skyten","`4Gr`2uft `4Ge`2se`4ll`2en`0"
 ,"`4Grab`6schän`4der`0","`\$Schreckens`7bringer`0","`QK`Tnochen`Qb`Trecher`0","`2D`@r`2a`2c`@h`2e`@n`2z`@ö`2l`@l`2n`@e`2r`0"
 ,"`~Ork`Tschlächter`0","`TW`tütende `TO`trks`0","`2Kampfzwerge`0","`4W`\$esen `4d`\$er `4N`\$acht`0","`3Schreiende `#Banshees`0"
 ,"`QD`8i`Qe `QI`8lluminat`Qi`0","`7Wargh Wölfe");
 $zufallsname = (e_rand(1,27));
 savesetting("gegner",$name[$zufallsname]);
 savesetting("dangreifer",$anzahl);
 savesetting("angriff",1);
 $wolken="`Qes purzeln ".$name[$zufallsname]."`Q vom Himmel";
 savesetting("weather",$wolken);
 }
 //Dorfangriff Ende
?>