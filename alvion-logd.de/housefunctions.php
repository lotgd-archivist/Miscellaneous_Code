
<?php
/*****************************************
 *
 * housefunctions.php
 * Author: Chaosmaker <webmaster@chaosonline.de>
 * Version: 1.1
 * Server: biosLoGD http://logd.chaosonline.de
 *
 * Some functions used by the house scripts
 *
 *****************************************/

// well, let's do some functions...

// check if building houses is allowed
function checkbuild() {
    global $session;
    // build deactivated for this area
    if ($session['user']['specialmisc']['build']==0) return false;
    elseif (getsetting('startbuild','1')==0) return false;
    else return true;
}
// get id of a module
function getmoduleid($module) {
    $sql = 'SELECT moduleid FROM housemodules WHERE modulename="'.$module.'"';
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    return $row['moduleid'];
}
// check if a certain module is installed
function module_installed($module) {
    $sql = 'SELECT moduleid FROM housemodules WHERE modulename="'.$module.'"';
    $result = db_query($sql);
    if ($row = db_fetch_assoc($result)) return $row['moduleid'];
    else return false;
}
// check if a certain module is built in in a house
function module_builtin($module,$houseid) {
    $sql = 'SELECT hm.moduleid, hm.built_in, hmd.value
                FROM housemodules hm
                LEFT JOIN housemoduledata hmd
                ON hmd.moduleid=hm.moduleid
                AND hmd.houseid="'.$houseid.'"
                AND hmd.name="#activated#"
                WHERE hm.modulename="'.$module.'"';
    $result = db_query($sql);
    if (!($row = db_fetch_assoc($result))) return false;
    elseif ($row['built_in']==1 || $row['value']==1) return $row['moduleid'];
    else return false;
}
// getting some module data
function getmoduledata($moduleid,$name,$houseid=0) {
    $sql = 'SELECT value
                FROM housemoduledata
                WHERE moduleid="'.$moduleid.'"
                AND houseid="'.$houseid.'"
                AND name="'.$name.'"';
    $result = db_query($sql);
    if ($row = db_fetch_assoc($result)) {
        return $row['value'];
    }
    else return false;
}
// setting some module data
function setmoduledata($moduleid,$name,$value,$houseid=0) {
    $sql = 'REPLACE INTO housemoduledata (moduleid,name,houseid,value)
                VALUES ("'.$moduleid.'","'.$name.'","'.$houseid.'","'.addslashes($value).'")';
    $result = db_query($sql);
    return db_affected_rows(LINK);
}
// getting data for pvp (burgling a house)
function getpvpdata($select,$order='a.maxhitpoints DESC',$limit=1) {
    global $session;
    $pvptime = getsetting("pvptimeout",600);
    $pvptimeout = date("Y-m-d H:i:s",time()-$pvptime);
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    $sql = "SELECT $select FROM accounts a
        LEFT JOIN items i1 ON i1.class='Schlüssel' AND i1.owner=a.acctid AND i1.hvalue > 0 WHERE
        (a.locked=0) AND
        (a.alive=1 AND a.location=2) AND
        (a.laston < '".date("Y-m-d H:i:s",time()-getsetting("LOGINTIMEOUT",900))."' OR a.loggedin=0) AND
        (a.age > $days OR a.dragonkills > 0 OR a.pk > 0 OR a.experience > $exp) AND
        (a.acctid <> ".$session['user']['acctid'].") AND
        (a.pvpflag <> '5013-10-06 00:42:00') AND
        (a.pvpflag < '$pvptimeout') AND
        ((a.housekey=".(int)$session['user']['specialmisc']['houseid']." AND i1.id IS NULL) OR i1.value1=".(int)$session['user']['specialmisc']['houseid'].")
        ".($order!=''?'ORDER BY '.$order:'')." ".($limit>0?'LIMIT '.$limit:'');
    $result = db_query($sql) or die(db_error(LINK));
    return $result;
}

// [© 2005 by Day aliaz Kevz]
function houselog($houseid, $acctid, $value, $gold=0, $gems=0){
    $sql    = 'select logid from houselog where acctid = '.$acctid.' and houseid = '.$houseid.' ';
    $result = db_query($sql) or die(db_error($sql));
    
    if(db_num_rows($result)==0) db_query('insert into houselog (houseid, acctid, gold, gems) values ('.$houseid.', '.$acctid.', '.$gold.', '.$gems.')');
         else db_query('update houselog set gold = gold'.$value.''.$gold.', gems = gems'.$value.''.$gems.' where acctid = '.$acctid.' and houseid = '.$houseid.'');
}
?>

