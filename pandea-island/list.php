<?php
/*
info: if anything gets changed, add comments! or I'll punch you in the face over the internet !!!

changes:
2005-xx-yy    aragon    shop-description for chars (juwe, wache, architekt, admin)
2013-09-19    aragon    repaired the links for this ... it didn't look perfect or so ...
-

modification:
2013-09-19    aragon    added: logoff for grotto ... shameless copied from codes, written for arania-logd

*/
require_once "common.php";
if ($session[user][loggedin]) {
        checkday();
        if ($session[user][alive]) {
                addnav("Zurück zum Dorf","village.php");
        } else {
                addnav("Zurück zu den Schatten","shades.php");
        }
        addnav("Gerade Online","list.php");
}else{
        addnav("Login Seite","index.php");
        addnav("Gerade Online","list.php");
}
page_header("Kämpferliste");
if ($_GET['desc']=="arch"){
        output("`c`tArchitekten`c`n");
        output("`tDie Architekten dieser Welt gehören zum Team der Admins und");
        output("`tkümmern sich um die Instandhaltung der Gebäude, Käferbekämpfung sowie um Neubauten.`n");
        output("`7Admins mit dem Aufgabenbereich 'Scripts'`n`0");
}
if ($_GET['desc']=="invi"){
        output("`c`tUnsichtbarkeit`c`n");
        output("`tDie Unsichtbarkeit dient dazu, dass Admins und Juweliere sich unerkannt auf Pandea bewegen können.`n");
        output("`7Der Unsichtbarkeitsmodus ist nur für Admins und Juweliere verfügbar.");
        output("`7Für normale User, also `bNicht-Admins`b ist die Spalte \"Unsichtbar\" nicht sichtbar.`n`0");
}
if ($_GET['desc']=="juwe"){
        output("`c`tJuweliere`c`n");
        output("`tJuweliere dieser Welt sind diejenigen, die als einzige die Erlaubnis haben,");
        output("`teuch im Juweliergeschäft in der Handelstraße Edelsteine abzukaufen oder zu verkaufen.`n`0");
}
if ($_GET['desc']=="wach"){
        output("`c`tStadtwachen`c`n");
        output("`tDie Stadtwachen achten u.a. darauf, dass Gespräche regelkonform ablaufen und kein Dieb, der den Juwelieren");
        output("`tEdelsteine oder Gold gestohlen hat, ungestraft davonkommt. Eine Stadtwache hat die Möglichkeit, bestimmte Kommentare eines");
        output("`tDorfbewohners zu löschen `tund Bewohner an den Pranger zu stellen.`n`0");
}
if ($_GET['desc']=="admi"){ // && $session[user][superuser]>=3){
        output("`c`tAdmins`c`n");
        output("`tAdmins sind in dieser Welt wahrhaft allmächtig - und damit sollten eigentlich schon alle Fragen beantwortet sein!`n`0");
}
if ($_GET['desc']=="prie"){
        output("`c`tPriester`c`n");
        output("`tPriester sind diejenigen, die sich ganz in den Dienst ihrer Gottheit gestellt haben. Sie kümmern sich um die anderen Gläubigen,");
        output("`tverbreiten ihren Glauben weiter und sind befugt, den Bund der Ehe zwischen zwei Liebenden zu schließen.`n`0");
}
$playersperpage=50;

$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];

if ($_GET['op']=="search"){
        $search="%";
        for ($x=0;$x<strlen($_POST['name']);$x++){
                $search .= substr($_POST['name'],$x,1)."%";
        }
        $search=" AND name LIKE '".addslashes($search)."' ";
        //addnav("List Warriors","list.php");
}else{
        $pageoffset = (int)$_GET['page'];
        if ($pageoffset>0) $pageoffset--;
        $pageoffset*=$playersperpage;
        $from = $pageoffset+1;
        $to = min($pageoffset+$playersperpage,$totalplayers);

        $limit=" LIMIT $pageoffset,$playersperpage ";
}
addnav("Seiten");
for ($i=0;$i<$totalplayers;$i+=$playersperpage){
        addnav("Seite ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","list.php?page=".($i/$playersperpage+1));
}

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['page']=="" && $_GET['op']==""){
        output("`n`c`bDiese Krieger sind gerade online`b`c");

        // 2013-09-09 ... invisibility modified ... unneeded things removed ... makes the code much faster!
        $sql = "SELECT * FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ".($session['user']['superuser']>=3?"":"AND invisible < 2")." ORDER BY level DESC, dragonkills DESC, login ASC";
}else{
        output("`n`c`bKrieger in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers)`b`c");
        $sql = "SELECT * FROM accounts WHERE locked=0 $search ".($session['user']['superuser']>=3?"":"AND invisible < 2")." ORDER BY level DESC, dragonkills DESC, login ASC $limit";
}
if ($session[user][loggedin]){
        output("<form action='list.php?op=search' method='POST'>Nach Name suchen: <input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","list.php?op=search");
}
output("`n");
$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);
if ($max>100) {
        output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
}

output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);

output("<tr class='trhead'><td><b>Level</b></td><td><b>Name</b></td><td><b>Rasse</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ort</b></td><td><b>Status</b></td><td><b>Besondere Angaben</b></td></td>",true);
if($session['user']['superuser']>=3){
addnav("","list.php?desc=invi");
output("<td><b><a href=\"list.php?desc=invi\">`&Unsichtbar*</a></b></td>",true);
output("<td><b>`&Tools*`0</b></td>",true);
}
output("<td><b>Zuletzt da</b></tr>",true);

for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^$row[level]`0");
        output("</td><td>",true);
        if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
        if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>",true);
        if ($session[user][loggedin]) addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."");
        output("`".($row[acctid]==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
        if ($session[user][loggedin]) output("</a>",true);
        output("</td><td>",true);
        /*
        switch($row['race']){
        case 0:
        output("`7Unbekannt`0");
        break;
        case 1:
        output("`2Troll`0");
        break;
        case 2:
        output("`^Elf`0");
        break;
        case 3:
        output("`0Mensch`0");
        break;
        case 4:
        output("`#Zwerg`0");
        break;
        case 5:
        output("`5Echse`0");
        break;        }
        */
        $sql2="SELECT colorname FROM race WHERE name='".$row['race']."'";
        $result2 = db_query($sql2) or die(sql_error($sql2));
        $row2 = db_fetch_assoc($result2);
        output($row2['colorname'].'`0');
        output("</td><td align=\"center\">",true);
        output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
        $invisible=$row[invisible];
        // ** 2013-09-19 by aragon    invisible and location stuff modified ... shameless copy of my arania-logd server ;D
/*    if ($row[location]==0){
        if ($row['invisible']<1){
            output($loggedin?"`#Online`0":"`3Die Felder`0");
        }else{
            output("`3Die Felder`0");
        }
    }
*/        switch($row['location'])
        {
        case '0': output(($loggedin && !$row[invisible])?"`#Online`0":"`3Die Felder`0"); break;
        case '1': output("`3Zimmer in Kneipe`0"); break;
        case '2': output("`3Im Haus`0"); break;
        case '9': output("`3Am Pranger`0"); break;
        case '100': output("`^An einem unerreichbaren Ort`0"); break;
        }
        output("</td><td>",true);
        if ($invisible<1){
            output($row[alive]?"`1Lebt`0":"`4Tot`0");
        }else{
            output("`4Tot`0");
        }
        output("</td><td>",true);
        $sonderinfos="";
        $admin=0;
        $wache=0;
        $juwelier=0;
        $architekt=0;
        $priester=0;
        /* Angel vorübergehend als Architektin rausgenommen, bis sie wieder Zeit hat
        if ($row['acctid']==3425){
                $architekt=1;
        }
        */
        if ($row['acctid']==8963){
        $priester=1;
        }
        if ($row['superuser']>2){
                $admin=1;
        }
        $acc=$row['acctid'];
        $sqlx = "SELECT * FROM shops_owner WHERE acctid = '".$acc."'";
        $resultx=db_query($sqlx);
#        for ($x=0;$x<db_num_rows($resultx);$x++){
        // ** 2013-09-19 by aragon ... why count shit and then ask for rows? ... "while" instead of it is much faster in this case
        while($row2 = db_fetch_assoc($resultx)){
                if ($row2['shopid']=='1') $juwelier=1;
                if ($row2['shopid']=='2') $wache=1;
        }
        // 2013-09-19 by aragon ... i wrote this here so shitty, i had to modify it .. i hope it looks better afterwards
        $s="";
        if($row['sex']==1) $s="in";
        //if ($admin==1) $sonderinfos="<a href='list.php?desc=admi&page=".$_GET['page']."'>Admin</a>";
        if ($wache==1) $sonderinfos.=" <a href='list.php?desc=wach&page=".$_GET['page']."'>Stadtwache</a> ";
        if ($juwelier==1 && $row['acctid']!="4980") $sonderinfos.=" <a href='list.php?desc=juwe&page=".$_GET['page']."'>Juwelier".$s."`0</a> ";
        if ($architekt==1) $sonderinfos.=" <a href='list.php?desc=arch&page=".$_GET['page']."'>Architekt".$s."`0</a> ";
        if ($priester==1) $sonderinfos=" <a href='list.php?desc=prie&page=".$_GET['page']."'>Priester".$s."</a> ";

        addnav("","list.php?desc=arch&page=".$_GET['page']);
        addnav("","list.php?desc=juwe&page=".$_GET['page']);
        addnav("","list.php?desc=wach&page=".$_GET['page']);
        //addnav("","list.php?desc=admi&page=".$_GET['page']);
        addnav("","list.php?desc=prie&page=".$_GET['page']);
        output($sonderinfos,true);
        output("</td><td>",true);

        if($session['user']['superuser']>=3)
        {
        addnav("","list.php?desc=invi&page=".$_GET['page']);
        output($row['invisible']?"<a href=\"list.php?desc=invi\">Unsichtbar</a>`0":"",true);
        output("</td><td>",true);
        output("<script language='JavaScript'>
                multi = null;
                document.onmousemove = updatemulti;
                function updatemulti(e) {
                        x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX;
                        y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY;
                        if (multi != null) {
                                multi.style.left = (x + 5) + 'px';
                                multi.style.top         = (y + 5) + 'px';
                        }
                }
                function showmulti(id) {
                        multi = document.getElementById(id);
                        multi.style.display = 'block'
                }
                function hidemulti() {
                        multi.style.display = 'none';
                }
                </script>",true);
        $multi='';
        $sqlm="SELECT name FROM accounts WHERE lastip='".$row['lastip']."' AND acctid!=$row[acctid]";
        $resultm = db_query($sqlm) or die(sql_error($sqlm));
        $m = db_num_rows($resultm);
        if ($m>0){
           $multi .="`^Multis nach IP:`7`n";
           for ($h=0;$h<$m;$h++){
               $rowm=db_fetch_assoc($resultm);
               $multi .="$rowm[name]`7, ";
           }
        }
        $sqlm2="SELECT name FROM accounts WHERE uniqueid='".$row['uniqueid']."' AND acctid!=$row[acctid]";
        $resultm2 = db_query($sqlm2) or die(sql_error($sqlm2));
        $m2 = db_num_rows($resultm2);
        if ($m2>0){
           $multi .="`n`^Multis nach ID:`7`n";
           for ($t=0;$t<$m2;$t++){
               $rowm2=db_fetch_assoc($resultm2);
               $multi .="$rowm2[name]`7, ";
           }
        }
        if ($m==0 && $m2 ==0) $multi="`^keine Multis";
        output("<div style='width:260;' class='tooltip' id=".$row[acctid].">$multi</div>",true);
        output("`0[<a onMouseOver=\"showmulti('$row[acctid]')\" onMouseOut=\"hidemulti()\">Multis</a>`0 |",true);
        output("<a href='user.php?op=edit&userid=$row[acctid]'>Edit</a>`0]",true);
        addnav("","user.php?op=edit&userid=$row[acctid]");
        output("</td><td>",true);
        }

        $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
        if (substr($laston,0,2)=="1 ") $laston="1 Tag";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
        if ($loggedin){
                if ($invisible=='0'){
                        $laston="Jetzt";
                }else{
                        $laston="Heute";
                }
        }
        output($laston);
        output("</td></tr>",true);
}
output("</table>",true);
if($session['user']['superuser']>=3) output("`n`n`n`&`i*Sowohl diese Spalten als auch dieser Text hier sind nur für Admins sichtbar.`i");
page_footer();
?> 