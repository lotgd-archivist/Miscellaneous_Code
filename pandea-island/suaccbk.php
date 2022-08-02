<?PHP

/*
codet: 2006/10/14
bugfixed on: 2006/10/15  yyyy/mm/dd
changes on: 2007/02/25
coder: aragon for aranialogd ... ported by aragon to pandea-island.de (2014/02/08)


idea: a system to backup accounts, in case of crash of the accounts-table or if the wrong user got deleted (had 2 crashes since i've started my own server)

what is needed for it?
- on installing this system to your logd, you have to copy the structure of your accounts-table
(maybe i build sometime an extension to set the script up easy ...)

- run the following line on your database and replace the name `accountsbackup` with the name you've choosen for your backuptable
ALTER TABLE `accountsbackup` ADD `lastbackup` DATETIME NOT NULL ;

- on logd 0.9x an update in your common.php
- i don't use logd 1.x now, so i don't give support on it, but you can try to copy the file "savebackups.php" to your /lib directory to get this script here work


options you've got:
- create a single account backup
- update a single account backup
- delete a backup
- create a complete account-tables-backup
- update all backups at once
- delete all backups at once


/////////////////////////////////

faq:
- what get't not backupped?
cells: output, allowednavs
why? to save space on the db (otherwise, the backup-table would be bigger, than the accounts-table)

- what's with those //adminlog(); things?
on my server i do adminlogs, so i can see, what my staff did when i wasn't online for a long time.
this function is just needet to track back administrative faults of other admins (or even yourself, if you know you did a misstake but can't remember what u did)
on this sourcefile, it's not activated, because i don't know, who has such a logfeature installed
wanna have the codes? just ask

- wich superuser-level i need to use the backuptool?
same as on user-editor (user.php)

/////////////////////////////////

bugfixes made on 2006/10/15  yyyy/mm/dd
- something witch shouldn't happen: backupping an account wich isn't active anymore --> set it in an if-else when the account doesn't exist on accounts-table but you want to backup it from it

/////////////////////////////////

changes made on 2007/02/25 yyyy/mm/dd
- addet info how many backups exist
- nav to delete all killed cause of a stupid activity during drunk ... (never drink and admin at once)

*/

require_once "common.php";
if(!in_array("savebackup.php",get_included_files()))
    if(!file_exists("lib/savebackup.php")) require_once "savebackup.php";
    else
        require_once "lib/savebackup.php";
page_header("Account-Backups");
isnewday(3);

addnav("grotto","superuser.php");
addnav("refresh","suaccbk.php");
addnav("Options");
addnav("Backup all","suaccbk.php?op=bkall");
addnav("Restore all","suaccbk.php?op=resall");
//addnav("Delete all","suaccbk.php?op=delall");
addnav("Pages");

// START: copied from user.php @ logd 0.97
if ($_GET[op]=="search"){
    $sql = "SELECT acctid FROM accountsbackup WHERE ";
    $where="
    login LIKE '%{$_POST['q']}%' OR
    acctid LIKE '%{$_POST['q']}%' OR
    name LIKE '%{$_POST['q']}%' OR
    emailaddress LIKE '%{$_POST['q']}%' OR
    lastip LIKE '%{$_POST['q']}%' OR
    uniqueid LIKE '%{$_POST['q']}%' OR
    level LIKE '%{$_POST['q']}%'";
    $result = db_query($sql.$where);
    if (db_num_rows($result)<=0){
        output("`\$No results found.`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)>100){
        output("`\$To many results! Please specify a little bit more.`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)==1){
        $_GET[op]="";
        $_GET['page']=0;
    }else{
        $_GET[op]="";
        $_GET['page']=0;
    }
}

    output("<form action='suaccbk.php?op=search' method='POST'>Seek User: <input name='q' id='q'><input type='submit' class='button'></form>",true);
    addnav("","suaccbk.php?op=search");

$sql = "SELECT count(acctid) AS count FROM accountsbackup";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row[count]>0){
    $page++;
    addnav("$page Seite $page","suaccbk.php?page=".($page-1)."&sort=$_GET[sort]");
    $row[count]-=100;
}
// END: copied from user.php @ logd 0.97



if($_GET['op']=="backup") // *** BAK 1
{
    output("Backupping Account $_GET[userid] `n");
    $sql="SELECT * FROM accounts WHERE acctid=$_GET[userid]";
    $result=db_query($sql);
    if(db_num_rows($result)>0)
    {
        $bk=savebackup($_GET[userid]);
        if($bk==1) output("Account $_GET[userid] successfully UPDATED!`n");
        elseif($bk==2) output("Accountbackup for User $_GET[userid] successfully CREATED!`n");
        else output("Error at backupping user $_GET[userid]!`n");
        adminlog();
    }
    else output("Error at backupping user $_GET[userid]! This user doesn't exist as aktive account!`n");
}
elseif($_GET['op']=="restore") // *** RES 1
{
    output("Restoring Account $_GET[userid] `n");
    $bk=returnbackup($_GET[userid]);
    if($bk==1) output("Account $_GET[userid] successfully RESTORED!`n");
    elseif($bk==2) output("Error, can't restore backup on an existend account!");
    else output("Error at restoring user $_GET[userid]!`n");
    adminlog();
}
elseif($_GET['op']=="delete") // *** DEL 1
{
    output("Deleting Account $_GET[userid] `n");
    $bk=deletebackup($_GET[userid]);
    if($bk==1) output("Backup $_GET[userid] successfully DELETED!`n");
    else output("Error at deleting user $_GET[userid]!`n");
    adminlog();
}
elseif($_GET['op']=="bkall") // *** BAK all
{
    output("Backupping all accounts `n`n");

    $update=0; $create=0; $err=0;

    $sql="SELECT acctid FROM accounts";
    $result=db_query($sql);
    while($row=db_fetch_assoc($result))
    {
        $bk=savebackup($row[acctid]);
        if($bk==1) $update++;
        elseif($bk==2) $create++;
        else $err++;
    }
    output("Backups created: $create `nBackups updated: $update `nBackuperrors: $err");
    output("`n`nPlease <a href='suaccbk.php'>refresh</a> this page to update its content.`n",true);
    addnav("","suaccbk.php");

    adminlog();
}
elseif($_GET['op']=="resall") // RES all
{
    output("Restoring Account $_GET[userid] `n`n");

    $res=0; $nonres=0; $err=0;

    $sql="SELECT acctid FROM accountsbackup";
    $result=db_query($sql);
    while($row=db_fetch_assoc($result))
    {
        $bk=returnbackup($row[acctid]);
        if($bk==1) $res++;
        elseif($bk==2) $nonres++;
        else $err++;
    }
    output("Backups Restored: $res `nBackups not restored: $nonres `nBackuperrors: $err");
    output("`n`nPlease <a href='suaccbk.php'>refresh</a> this page to update its content.`n",true);
    addnav("","suaccbk.php");

    adminlog();
}
elseif($_GET['op']=="delall") // DEL all
{
    output("Deleting all Backups`n`n");

    $del=0; $nondel=0; $err=0;
    $sql="SELECT acctid FROM accountsbackup";
    $result=db_query($sql);
    while($row=db_fetch_assoc($result))
    {
        $bk=deletebackup($row[acctid]);
        if($bk==1) $del++;
        elseif($bk==2) $nondel++;
        else $err++;
    }
    output("Backups Deleted: $del `nBackups not deleted: $nondel `nErrors: $err");
    output("`n`nPlease <a href='suaccbk.php'>refresh</a> this page to update its content.`n",true);
    addnav("","suaccbk.php");
    adminlog();
}
else
{
// START: copied from user.php @ logd 0.97
    if (isset($_GET['page'])){
        if(empty($_GET['sort'])) $order = "acctid";
        else $order=$_GET['sort'];
        if ($_GET[sort]!="") $order = "$_GET[sort]";
        $offset=(int)$_GET['page']*100;
#        $sql = "SELECT acctid,login,name,superuser,superuser2,level,lastbackup,lastip,uniqueid,emailaddress FROM accountsbackup WHERE 1".($where>""?" AND ($where) ":"")." ORDER BY ".$order." LIMIT $offset,100";
        $sql = "SELECT acctid,login,name,superuser,level,lastbackup,lastip,uniqueid,emailaddress FROM accountsbackup WHERE 1".($where>""?" AND ($where) ":"")." ORDER BY ".$order." LIMIT $offset,100";
        $result = db_query($sql) or die(db_error(LINK));
        if($session[user][superuser]>=4) output("[debug][def]: [\$order]: ".$order." [\$offset] ".$offset." [\$sql]: ".$sql." ");
        output("<table>",true);
        output("<tr>
        <td>Ops</td>"
        ."<td><a href='suaccbk.php?sort=acctid&page=$_GET[page]'>ID</a></td>"
        ."<td><a href='suaccbk.php?sort=login&page=$_GET[page]'>Login</a></td>"
        ."<td><a href='suaccbk.php?sort=lastbackup&page=$_GET[page]'>Last BK</a></td>"
        ."<td><a href='suaccbk.php?sort=name&page=$_GET[page]'>Name</a></td>"
        ."<td><a href='suaccbk.php?sort=superuser&page=$_GET[page]'>SU1</a></td>"
#        ."<td><a href='suaccbk.php?sort=superuser2&page=$_GET[page]'>SU2</a></td>"
        ."<td><a href='suaccbk.php?sort=level&page=$_GET[page]'>Lev</a></td>"
        ."<td><a href='suaccbk.php?sort=lastip&page=$_GET[page]'>IP</a></td>"
        ."<td><a href='suaccbk.php?sort=uniqueid&page=$_GET[page]'>ID</a></td>"
        ."<td><a href='suaccbk.php?sort=emailaddress&page=$_GET[page]'>E-Mail</a></td>"
        ."</tr>",true);
        addnav("","suaccbk.php?sort=acctid&page=$_GET[page]");
        addnav("","suaccbk.php?sort=login&page=$_GET[page]");
        addnav("","suaccbk.php?sort=name&page=$_GET[page]");
        addnav("","suaccbk.php?sort=superuser&page=$_GET[page]");
#        addnav("","suaccbk.php?sort=superuser2&page=$_GET[page]");
        addnav("","suaccbk.php?sort=level&page=$_GET[page]");
        addnav("","suaccbk.php?sort=lastbackup&page=$_GET[page]");
        addnav("","suaccbk.php?sort=lastip&page=$_GET[page]");
        addnav("","suaccbk.php?sort=uniqueid&page=$_GET[page]");
        addnav("","suaccbk.php?sort=emailaddress&page=$_GET[page]");
        $rn=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $lastbackup=round((strtotime("0 days")-strtotime($row[lastbackup])) / 86400,0)." Tage";
            if (substr($lastbackup,0,2)=="1 ") $lastbackup="1 Tag";
            if (date("Y-m-d",strtotime($row[lastbackup])) == date("Y-m-d")) $lastbackup="Heute";
            if (date("Y-m-d",strtotime($row[lastbackup])) == date("Y-m-d",strtotime("-1 day"))) $lastbackup="Gestern";
            if ($loggedin) $lastbackup="Jetzt";
            $row[lastbackup]=$lastbackup;
            if ($row[$order]!=$oorder) $rn++;
            $oorder = $row[$order];
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

            output("<td>",true);
            output("[<a href='suaccbk.php?op=backup&userid=$row[acctid]'>BAK</a>|".
            "<a href='suaccbk.php?op=restore&userid=$row[acctid]'>Restore</a>|".
            "<a href='suaccbk.php?op=delete&userid=$row[acctid]'>Del</a>]",true);
            addnav("","suaccbk.php?op=backup&userid=$row[acctid]");
            addnav("","suaccbk.php?op=restore&userid=$row[acctid]");
            addnav("","suaccbk.php?op=delete&userid=$row[acctid]");
            output("</td><td>",true);
            output($row[acctid]."</td><td>",true);
            output($row[login]."</td><td>",true);
            output($row[lastbackup]."</td><td>",true);
            output($row[name]."</td><td>",true);
            output($row[superuser]."</td><td>",true);
#            output($row[superuser2]."</td><td>",true);
            output($row[level]."</td><td>",true);
            output($row[lastip]."</td><td>",true);
            output($row[uniqueid]."</td><td>",true);
            output($row[emailaddress]."</td></tr>",true);
        }
        output("</table>",true);
    }
// END: copied from user.php @ logd 0.97

    $row=db_fetch_assoc(db_query("SELECT count(*) as c from accountsbackup"));
    output("Backups total: ".$row['c']);

}


page_footer();

?> 