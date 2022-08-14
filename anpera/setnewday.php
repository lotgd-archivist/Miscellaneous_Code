
ï»¿<?php



// 20060327



/*setweather.php

An element of the global weather mod Version 0.5

Written by Talisman

Latest version available at http://dragonprime.cawsquad.net



translation & extension: anpera

*/



if ((int)getsetting("expirecontent",180)>0){

    $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".getsetting("expirecontent",180)." days"))."'";

    db_query($sql);

    $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".getsetting("expirecontent",180)." days"))."'";

    db_query($sql);

}

$sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".getsetting("oldmail",14)." days"))."'";

db_query($sql);



switch(e_rand(1,10)){

    case 1:

    $clouds="Wechselhaft und kÃ¼hl, mit sonnigen Abschnitten";

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

      $clouds="HeiÃŸ und sonnig";

                break;

                case 7:

      $clouds="Starker Wind mit vereinzelten Regenschauern";

                break;

                case 8:

      $clouds="Gewittersturm";

                break;

                case 9:

      $clouds="Schneeregen";

                break;

                case 10:

      $clouds="Hagel";

                break;

}

savesetting("weather",$clouds);



// Angriff aufs Dorf?

if (getsetting("angreiferzahl",0)<=0 && getsetting("angreiferzufall",false)==true && e_rand(1,40)>37){

   savesetting("angreiferzahl",e_rand(50,500));

   savesetting("angreiferopfer","0");

}



// Vendor in town?

if (e_rand(1,6)==1){

    savesetting("vendor","1");

    db_query("INSERT INTO news(newstext,newsdate,accountid) VALUES ('`qDer WanderhÃ¤ndler ist heute im Dorf!`0',NOW(),0)") or die(db_error($link));

}else{

    savesetting("vendor","0");

}



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

.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -$old days"))."\")\n":"")

." AND emailaddress!='' AND sentnotice=0";

$result = db_query($sql);

for ($i=0;$i<db_num_rows($result);$i++){

    $row = db_fetch_assoc($result);



// can't send mail on anpera.net

/*

    mail($row['emailaddress'],"LoGD Charakter verfÃ¤llt",

    "

    Einer oder mehrere deiner Charaktere von Legend of the Green Dragon auf

    ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."

    verfÃ¤llt demnÃ¤chst und wird gelÃ¶scht. Wenn du den Charakter retten willst, solltest

     du dich bald mÃ¶glichst mal damit einloggen!

     Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.",

    "From: ".getsetting("gameadminemail","postmaster@localhost.com")

    );

*/

    $sql = "UPDATE accounts SET sentnotice=1,house=0,housekey=0,marriedto=0 WHERE acctid='{$row['acctid']}'";

    if ((int)$row['acctid']==(int)getsetting("hasegg",0)) savesetting("hasegg","0");

    db_query($sql);



//trademod

    $result3=db_query("SELECT itemid,offerfrom,target FROM trade WHERE offerfrom={$row['acctid']} OR target={$row['acctid']}");

    for ($j=0;$j<db_num_rows($result3);$j++){

        $row3 = db_fetch_assoc($result3);

        if ($row3['target']==$row['acctid']) db_query("UPDATE items SET owner={$row3['offerfrom']} WHERE id={$row3['itemid']}");

        if ($row3['offerfrom']==$row['acctid']) db_query("UPDATE items SET owner=0 WHERE id={$row3['itemid']}");

    }

    db_query("DELETE FROM trade WHERE target={$row['acctid']} OR offerfrom={$row['acctid']}");

// end trademod

// HÃ¤user

    $sql = "UPDATE houses SET owner=0,status=3 WHERE owner={$row['acctid']} AND status=1";

    db_query($sql);

    $sql = "UPDATE houses SET owner=0,status=4 WHERE owner={$row['acctid']} AND status=0";

    db_query($sql);

    $sql = "UPDATE items SET owner=0 WHERE owner={$row['acctid']}";

    db_query($sql);

    $sql = "DELETE FROM pvp WHERE acctid2={$row['acctid']} OR acctid1={$row['acctid']}";

    db_query($sql) or die(db_error(LINK));

    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto={$row['acctid']}";

    db_query($sql);

}



$old+=5;

$delaccts = "0";

$sql = "SELECT * FROM accounts WHERE superuser<=1 AND (1=0\n"

    .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$old)."\")\n":"")

    .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$new)."\" AND level=1 AND dragonkills=0)\n":"")

    .($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*($trash+1))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")

    .")";

$result = db_query($sql);

while ($row = db_fetch_assoc($result)) {

    deleteuser($row['acctid'],"`0(AltersschwÃ¤che)");

}



$sql = "DELETE FROM referers WHERE last<'".date("Y-m-d H:i:s",strtotime(date ("Y-m-d H:i:s")." -".getsetting("expirecontent",180)." days"))."'";

db_query($sql);



// Leser in der Bibliothek zurÃ¼cksetzen

db_query("UPDATE lib_books SET lastreadby=0 WHERE 1");



db_query("UPDATE accounts SET gensize=1 WHERE gensize>4294800000"); //MySQL 5 bringt sonst Fehler

// end cleanup



db_query("DELETE FROM items WHERE owner=0 AND (class='Schmuck' OR class='Beute' OR class='MÃ¶bel' OR class='Kleidung' OR class='Rezept' OR class='Komponente')");



//trademod

$result=db_query("SELECT offerfrom,itemid FROM trade WHERE date<'".date("Y-m-d",strtotime(date("Y-m-d H:i:s")." -14 days"))."'");

for ($i=0;$i<db_num_rows($result);$i++){

    $row = db_fetch_assoc($result);

    db_query("UPDATE items SET owner={$row['offerfrom']} WHERE id={$row['itemid']}");

    db_query("DELETE FROM trade WHERE itemid={$row['itemid']}");

}

// end trademod



savesetting("lastdboptimize",date("Y-m-d H:i:s"));

$result = db_query("SHOW TABLES");

while ($helferlein=db_fetch_assoc($result)){

    list($key,$val)=each($helferlein);

    db_query("OPTIMIZE TABLE $val");

}

?>

