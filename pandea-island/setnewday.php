<?php

// 25052004

/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net

translation: anpera
*/

/*

setweather.php angel Version
wetter überarbeitet und nach der jahreszeit gerichtet sowie weitere wetterarten hinzugefügt
*/
require_once "lib/savebackup.php";
#require_once "common.php";
if (getsetting('activategamedate',0)==1) { #ist datum eigentlich aktiviert?
        $date = getsetting('gamedate','1600-01-01');
        $date = explode('-',$date);
        $monat = $date[1]; #monatsauswahl
        if ($monat==11 || $monat==12 || $monat==1){ ### "Winter" Szenario
                $normal=e_rand(1,4); ##25% für andere vorkommenden wetterarten
                if ($normal==1){
                        switch(e_rand(1,5)){
                        case 1:
            $clouds="Regnerisch";
                        break;
                        case 2:
            $clouds="Neblig";
                        break;
                        case 3:
            $clouds="Starker Wind mit vereinzelten Regenschauern";
                        break;
                        case 4:
            $clouds="Gewittersturm";
                        break;
                        case 5:
            $clouds="stark bewölkt"; #*new
                                    break;
                }
        }else{ ##Winterwonderlandwetter
                    switch(e_rand(1,4)){
                case 1:
                $clouds="Kalt bei klarem Himmel";
                    break;
                    case 2:
                $clouds="Schneesturm"; #*new
                    break;
                    case 3:
                $clouds="Wechselhaft und kühl, mit sonnigen Abschnitten";
                    break;
                    case 4:
                $clouds="Frostige Temperaturen und stark bewölkt"; #*new
                    break;
            }
        }
    }elseif ($monat==2 || $monat==3 || $monat==4){ #hach ja der frühling kommt "Frühlingsszenario"
            $normal=e_rand(1,4); ##25% für andere vorkommenden wetterarten
                if ($normal==1){
                        switch(e_rand(1,4)){
                        case 1:
            $clouds="Regnerisch";
                        break;
                        case 2:
            $clouds="leichter Nebel"; #*new
                        break;
                        case 3:
            $clouds="leichter Wind mit vereinzelten Regenschauern"; #*new
                        break;
                        case 4:
            $clouds="leicht bewölkt"; #*new
                                    break;
                }
        }else{ ##Blumenblühwetter
                    switch(e_rand(1,4)){
                case 1:
                $clouds="Warm und sonnig";
                    break;
                    case 2:
                $clouds="Kühler Wind bei klarem Himmel"; #*new
                    break;
                    case 3:
                $clouds="kaum Wind bei klarem Himmel"; #*new
                    break;
                    case 4:
                $clouds="Warm und teilweise bewölkt"; #*new
                    break;
            }
        }
    }elseif ($monat==5 || $monat==6 || $monat==7 || $monat==8){ #Spring time! "sommer" szenario
            $normal=e_rand(1,4); ##25% für andere vorkommenden wetterarten
                if ($normal==1){
                        switch(e_rand(1,3)){
                        case 1:
            $clouds="kaum Wind bei klarem Himmel"; #*new
                        break;
                        case 2:
            $clouds="Kühler Wind bei klarem Himmel"; #*new
                        break;
                        case 3:
            $clouds="leicht bewölkt"; #*new
                                    break;
                }
        }else{ ##Sommertime
                    switch(e_rand(1,4)){
                case 1:
                $clouds="Warm und sonnig";
                    break;
                    case 2:
                $clouds="Heiß und sonnig";
                    break;
                    case 3:
                $clouds="sommerliche Temperaturen bei wolkenfreiem Himmel"; #*new
                    break;
                    case 4:
                $clouds="Heiß und leicht bewölkt"; #*new
                    break;
            }
        }
    }else{
            switch(e_rand(1,6)){
                        case 1:
            $clouds="Regnerisch";
                    break;
                    case 2:
            $clouds="leichter Nebel"; #*new
                    break;
                    case 3:
            $clouds="starker Nebel"; #*new
                    break;
                    case 4:
            $clouds="Gewittersturm";
                    break;
                    case 5:
            $clouds="starker Wind bei vereinzelten Regenschauern";
                    break;
                    case 6:
            $clouds="starker Wind bei Dauerregen";
                    break;
        }
    }
}else{
    switch(e_rand(1,8)){
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
    }
}
savesetting("weather",$clouds);
/*
// Vendor in town?
if (e_rand(1,3)==1){
        savesetting("vendor","1");
        //$sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('`qDer Wanderhändler ist heute im Dorf!`0',NOW(),0)";
        //db_query($sql) or die(db_error($link));
        addnews('`qDer Wanderhändler ist heute im Dorf!`0',0);
}else{
        savesetting("vendor","0");
}
*/


// Candy die Süßwarenhändlerin (by Angel)
$warsch=getsetting('warschcandy',6);
if (e_rand(1,$warsch)==1 && $warsch!=0){
        savesetting("candy","1");
        addnews('`%Candy ist heute im Dorf!`0',0);
        $sweet1=0;
        $sweet2=0;
        $sweet3=0;
        $sql1="SELECT * FROM sweets WHERE class=1";
        $result1 = db_query($sql1);
        $rand1=rand(1,db_num_rows($result1));
        for ($x=0;$x<$rand1;$x++){
            $row1 = db_fetch_assoc($result1);
            $sweet1=$row1[id];
        }
        $sql2="SELECT * FROM sweets WHERE class=2";
        $result2 = db_query($sql2);
        $rand2=rand(1,db_num_rows($result2));
        for ($y=0;$y<$rand2;$y++){
            $row2 = db_fetch_assoc($result2);
            $sweet2=$row2[id];
        }
        $sql3="SELECT * FROM sweets WHERE class=3";
        $result3 = db_query($sql3);
        $rand3=rand(1,db_num_rows($result3));
        for ($z=0;$z<$rand3;$z++){
            $row3 = db_fetch_assoc($result3);
            $sweet3=$row3[id];
        }
        savesetting("sweet1","$sweet1");
        savesetting("sweet2","$sweet2");
        savesetting("sweet3","$sweet3");
}else{
        savesetting("candy","0");
}


// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1) {
        $date = getsetting('gamedate','1600-01-01');
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

// Reduce time in jail for chars who need it

$sql = "UPDATE accounts SET jailtime=jailtime-1 WHERE jailtime > 0";
db_query($sql);

// this now includes the database cleanup from index.php
$old = getsetting("expireoldacct",45)-5;
$new = getsetting("expirenewacct",10);
$trash = getsetting("expiretrashacct",1);

$sql = "SELECT acctid,emailaddress,login FROM accounts WHERE 1=0 "
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"")
." AND emailaddress!='' AND sentnotice=0";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        mail($row[emailaddress],"LoGD Charakter verfällt",
//        Einer oder mehrere deiner Charaktere von Legend of the Green Dragon auf
        "
        Dein Legend of the Green Dragon Charakter - ".$row['login']." - auf
        ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."
        verfällt demnächst und wird gelöscht. Wenn du den Charakter retten willst, solltest
         du dich bald möglichst mal damit einloggen!
         Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.",
        "From: ".getsetting("gameadminemail","postmaster@localhost.com")
        );
        $sql = "UPDATE accounts SET sentnotice=1,house=0,housekey=0,marriedto=0 WHERE acctid='$row[acctid]'";
        if ((int)$row[acctid]==(int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$row[acctid] AND status=1";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$row[acctid] AND status=0";
        db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner=$row[acctid]";
        db_query($sql);
        $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]";
        db_query($sql) or die(db_error(LINK));
        $sql = "DELETE FROM bounties WHERE acctid=$row[acctid]";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]";
        db_query($sql);
}

$old+=5;
$delaccts = '0';
$sql = "SELECT acctid FROM accounts WHERE superuser<=1 AND (1=0\n"
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"")
.($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$new days"))."\" AND level=1 AND dragonkills=0)\n":"")
.($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-".($trash+1)." days"))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
.")";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)) {  $delaccts .= ','.$row['acctid'];  savebackup($row['acctid']); /* save backup inb4 delete */ }
$sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
//echo "<pre>".HTMLEntities($sql)."</pre>";
db_query($sql) or die(db_error(LINK));
$sql = "DELETE FROM accounts_text WHERE acctid IN ($delaccts)";
db_query($sql);
// end cleanup

// commentary-cleanup
if ((int)getsetting("expirecontent",180)>0){
        $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime("-".getsetting("expirecontent",180)." days"))."'";
        db_query($sql);
}

// news-cleanup
if ((int)getsetting("expirecontent",180)>0){
        $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime("-".getsetting("expirecontent",180)." days"))."'";
        //echo $sql;
        db_query($sql);
}

// messageboard-cleanup
$sql = 'DELETE FROM messageboard WHERE expire!="0000-00-00 00:00:00" AND expire < NOW()';
db_query($sql);

// mail-cleanup
//FEHLERHAFT $sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime("-".getsetting("oldmail",14)."days"))."'";
$sql= "DELETE FROM mail WHERE sent < date_add(current_date, interval - 14 day) AND archiv='0'";
db_query($sql);

// bounty-cleanup
$sql = 'SELECT bounties.acctid FROM bounties LEFT JOIN accounts USING(acctid) WHERE IFNULL(accounts.acctid,0)=0 GROUP BY bounties.acctid';
$result = db_query($sql);
while ($row = db_fetch_assoc($result)) db_query('DELETE FROM bounties WHERE acctid="'.$row['acctid'].'"');

// petition-cleanup
$sql = "SELECT petitionid FROM petitions WHERE status=2 AND date<'".date("Y-m-d H:i:s",strtotime("-7 days"))."'";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)) db_query('DELETE FROM petitionmail WHERE petitionid="'.$row['petitionid'].'"');
$sql = "DELETE FROM petitions WHERE status=2 AND date<'".date("Y-m-d H:i:s",strtotime("-7 days"))."'";
db_query($sql);

// debuglog-cleanup
$sql = "DELETE from debuglog WHERE date <'".date("Y-m-d H:i:s",strtotime("-".(getsetting("expirecontent",180)/10)." days"))."'";
db_query($sql);

// optimize tables
if (
        strtotime(
                getsetting(
                        "lastdboptimize",
                        date(
                                "Y-m-d H:i:s",
                                strtotime("-1 day")
                        )
                )
        ) < strtotime("-1 day")
){
        savesetting("lastdboptimize",date("Y-m-d H:i:s"));
        $result = db_query("SHOW TABLES");
        for ($i=0;$i<db_num_rows($result);$i++){
                list($key,$val)=each(db_fetch_assoc($result));
                db_query("OPTIMIZE TABLE $val");
        }
}
#redirect("village.php");
?> 