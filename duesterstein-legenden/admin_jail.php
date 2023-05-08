
<?
/*
LoGD - Gefängniserweiterung
19.05.2004
Matthias "Vanion" Strauch

Modifiziert und lauffähig gemacht von Raven, www.rabenthal.de, 12.06.2004
*/

require_once "common.php";
//isnewday(2);

page_header("Einen User einknasten");
addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
addnav("Zurück zum Dorf","village.php");
addnav("Liste aktualisieren","admin_jail.php");

output("Hier kannst du User, die ein wenig über die Stränge geschlagen haben, zu einer Prangerstrafe verdonnern. ");
output("Die Strafe wird in Spieltagen abgesessen, das heißt der Spieler kann so lange keine Aktionen durchführen! `n`n`n`n`n");

addnav("","admin_jail.php?op=change");
if($_GET[op] == "change") {
$search="%";
        for ($i=0;$i<strlen($_POST['tochange']);$i++){
            $search.=substr($_POST['tochange'],$i,1)."%";
        }
        $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
        $result = db_query($sql);
        output("Bestätige den Namen des anzuprangernden:`n`n");
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<a href='admin_jail.php?op=change2&acct=".$row[acctid]."&days=".$_POST[newtime]."'>",true);
            output("".$row['name']."");
            output("</a>`n",true);
            addnav("","admin_jail.php?op=change2&acct=".$row[acctid]."&days=".$_POST[newtime]."");
            addnav("Zurück","admin_jail.php");
        }
}else if($_GET[op] == "change2") {
    if ($_GET[days]<=0){
    //$sql="UPDATE accounts SET jailtime=0, location=0 where acctid=".$_GET[acct]."";
    //db_query($sql);
    set_special_var("jailtime","0","0",$_GET[acct],0," ");
    set_special_var("location","0","0",$_GET[acct],0," ");
    }else{
        $tagheute = getsetting("daysalive",0);
        $tagbis = $_GET[days] + $tagheute;
    set_special_var("jailtime","".$tagbis."","0",$_GET[acct],0," ");
    set_special_var("location","9","0",$_GET[acct],0," ");
        //$sql = "UPDATE accounts set jailtime = ".$tagbis.", location=9 where acctid = ".$_GET[acct]."";
        //db_query($sql);
    }
}else{
    $tagheute=getsetting("daysalive",0);
    $sql = "SELECT acctid, name, jailtime, login from accounts where jailtime > 0";
    $result = db_query($sql);

    output("`c`bAktuelle Knastbrüder:`b ");
    output("<table><tr><td>Name</td><td>Knastzeit</td><td>-Ändern</td></tr>",true);

    while($row = db_fetch_assoc($result)) {
        $tagerest=$row[jailtime]-$tagheute;
            output("<tr>
        <td>".$row['name']."</td>
        <td>".$tagerest."</td>
        <td><form method='post' action='admin_jail.php?op=change'>
            <input type=hidden name=tochange value=".$row['login'].">
            <input type=text size=2 maxlength=2 name=newtime value=".$tagerest.">
            <input type='submit'>
        </form>
        </td>
        </tr>",true);
    }
    output("</table>`n`n",true);

    output("`bNeue Knaststrafe:`b `n");
    output("<form action='admin_jail.php?op=change' method='post'>
        Name: <input type='text' size='10' name='tochange'> 
        IG-Tage: <input type='text' size='2' name='newtime'> 
        <input type='submit'>
        </form>`c",true);
}

page_footer();

?> 
