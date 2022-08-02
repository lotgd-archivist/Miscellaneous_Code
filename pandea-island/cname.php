<?php

require_once "common.php";

page_header("Cnames");
if ($_GET['op']=="go"){
    $sql = "SELECT * FROM accounts WHERE cname='' LIMIT 0,99";
    $result = db_query($sql);
    for ($i = 0; $i < db_num_rows($result); $i++) {
        $row = db_fetch_assoc($result);
        $n=$row[name];
        if ($row[ctitle]==""){
            $neu=substr($n,strlen($row[title])+1);
        } else {
            $neu=substr($n,strlen($row[ctitle])+1);
        }
        $row[cname]=$neu;
        output($n."`0 --> ".$row['cname']."`n`0");
        $nummer=$row['acctid'];
        $sql2="UPDATE `accounts` SET `cname` = '".$row['cname']."' WHERE `acctid` = '".$nummer."'";
        db_query($sql2);
    }
}else{
    $sql = "SELECT * FROM accounts WHERE cname=''";
    $result = db_query($sql);
    for ($i = 0; $i < db_num_rows($result); $i++) {
        ##
    }
    output($i);
}
addnav("Reload Anzahl","cname.php");
addnav("Make 100","cname.php?op=go");
page_footer();
addnav("Dorfplatz","village.php");
?> 