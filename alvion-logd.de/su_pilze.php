
<?php/** Version:    15.02.2010* Author:    Linus* Email:    webmaster@alvion-logd.de* Zweck:    Admintool für die Pilze**/require_once("common.php");require_once "func/isnewday.php";isnewday(3);function analyse(){    $accts=array();    $sql="SELECT `acctid` FROM `accounts`";    $result=db_query($sql);    while ($row = db_fetch_assoc($result)) {        $accts[$i]=(int)$row['acctid'];        $i++;    }    $gut=0;    $fehl=0;    $sql="SELECT `acctid` FROM `pilze`";    $result=db_query($sql);    while ($row = db_fetch_assoc($result)) {        if(in_array((int)$row['acctid'],$accts)){            $gut++;        }else{            $fehl++;        }        $j++;    }    return array($j, $gut, $fehl);}page_header("Pilztabellen");switch($_GET['op']){    case "delete":        $accts=array();        $sql="SELECT `acctid` FROM `accounts`";        $result=db_query($sql);        while ($row = db_fetch_assoc($result)) {            $accts[$i]=(int)$row['acctid'];            $i++;        }        $gut=0;        $fehl=0;        $sql="SELECT `acctid` FROM `pilze`";        $result=db_query($sql);        while ($row = db_fetch_assoc($result)) {            if(in_array((int)$row['acctid'],$accts)){                $gut++;            }else{                $fehl++;                db_query("DELETE FROM `pilze` WHERE `acctid`=".(int)$row['acctid'].";");            }            $j++;        }        output("`&".$fehl." Pilztabellen wurden gelöscht.`n");        addnav('Zurück','su_pilze.php?');    break;    default:        list($j, $gut, $fehl)=analyse();        if($fehl>0) addnav('unnütze Pilztabellen löschen','su_pilze.php?op=delete');        output("`@`b`cDie Pilztabellen`c`b`n`n"        ."`7Anzahl gesamt: `@".$j."`n`7von existierenden Spielern: `@".$gut."`n`7Tabellen von gelöschten Spielern: `@".$fehl."`n`n");}addnav("G?Zurück zur Grotte","superuser.php");addnav("W?Zurück zum Weltlichen","village.php");output("`n<div align='right'>`72010 by Linus, www.alvion-logd.de</div>",true);page_footer();

