
<?php
/*
* Version:    0.01, 01.02.2013
* Author:    Linus
* Email:    webmaster@alvion-logd.de
* Zweck:    Admintool für das Löschen von unnützen Einträgen im accounts_update
*
*/

require_once("common.php");
require_once "func/isnewday.php";
isnewday(3);

function analyse($delete=FALSE){
    $accounts=array();
    $sql="SELECT `acctid` FROM `accounts`";
    $result=db_query($sql);
    while ($row = db_fetch_assoc($result)) {
        $accounts[$i]=(int)$row['acctid'];
        $i++;
    }
    $gut=0;
    $fehl=0;
    $sql="SELECT `updateid`, `acctid` FROM `accounts_update`";
    $result=db_query($sql);
    while ($row = db_fetch_assoc($result)) {
        if(in_array((int)$row['acctid'],$accounts)){
            $gut++;
        }else{
            $fehl++;
            if($delete===TRUE) db_query("DELETE FROM `accounts_update` where `updateid`=".(int)$row['updateid'].";");
        }
        $j++;
    }
    return array($j, $gut, $fehl);
}

page_header("Einträge im accounts_update löschen");

switch($_GET['op']){
    case "delete":
        list($j, $gut, $fehl)=analyse(TRUE);
        output("`&".$fehl." Einträge wurden gelöscht`n");
        addnav('Zurück','su_account_update.php?');
    break;

    default:
        list($j, $gut, $fehl)=analyse();
        output("`@`b`cEinträge im accounts_update`c`b`n`n`7Anzahl Einträge gesamt: `@".$j."`n`7Von existierenden Accounts: `@".$gut."`n`7Von gelöschten Accounts: `@".$fehl."`n`n");
        if($fehl>0) addnav('unnütze Einträge löschen','su_account_update.php?op=delete');
        else output("<table align='center'><tr><td colspan=3 align='center'>`&`i`bKeine Einträge von gelöschten Accounts!`b`i`0</td></tr></table>",true);
    break;
}


addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

output("`n<div align='right'>`72013 by Linus</div>",true);
page_footer();


