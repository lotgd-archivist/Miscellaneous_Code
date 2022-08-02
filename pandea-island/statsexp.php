<?php

##**Standartnavs & Header
require_once "common.php";
page_header("Stats Exp.");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
##**End of Standartnavs & Header

if ($_GET['op']=="racestats"){  ##Rassenauflistung nach Geschlecht
        /*
        ##**Gargoyle Männlich
        $sql="SELECT sex , race FROM accounts WHERE race=8 AND sex=0";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $gargoylemale=$i;
        }
        ##**Gargoyle Weiblich
        $sql="SELECT sex , race FROM accounts WHERE race=8 AND sex=1";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $gargoylefemale=$i;
        }
        */
        ##**Tabelle erstellen
        output("<table border=1 frame=\"void\" cellspacing=0 cellpadding=5><tr><td><b>Rasse</b></td><td><b>insgesamt</b></td><td><b>männlich</b></td><td><b>weiblich</b></td></tr>",true);
        $sql="SELECT * FROM race ORDER BY rid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $gesamt=0;
        $male=0;
        $weiblich=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $m=0;
            $w=0;
            $sql2="SELECT sex FROM accounts WHERE race='".$row['name']."' AND sex=0";
            $result2 = db_query($sql2);
            $m=db_num_rows($result2);
            $sql3="SELECT sex FROM accounts WHERE race='".$row['name']."' AND sex=1";
            $result3 = db_query($sql3);
            $w=db_num_rows($result3);
            $g=$m+$w;
            $gesamt+=$g;
            $male+=$m;
            $weiblich+=$w;
            output("<tr><td><b>",true);
            output("$row[colorname]");
            output("</b></td><td>$g</td><td>$m</td><td>$w</td></tr>",true);
        }
        /*
        output("<tr><td><b>Unbekannt</b></td><td>$unbekanntgesamt</td><td>$unbekanntmale</td><td>$unbekanntfemale</td></tr>",true);
        output("<tr><td><b>Trolle</b></td><td>$trollgesamt</td><td>$trollmale</td><td>$trollfemale</td></tr>",true);
        output("<tr><td><b>Elfen</b></td><td>$elfgesamt</td><td>$elfmale</td><td>$elffemale</td></tr>",true);
        output("<tr><td><b>Menschen</td><td>$menschgesamt</td><td>$menschmale</td><td>$menschfemale</td></tr>",true);
        output("<tr><td><b>Zwerge</td><td>$zwerggesamt</td><td>$zwergmale</td><td>$zwergfemale</td></tr>",true);
        output("<tr><td><b>Echsenwesen</td><td>$echsenwesengesamt</td><td>$echsenwesenmale</td><td>$echsenwesenfemale</td></tr>",true);
        output("<tr><td><b>Lichtgestalten</td><td>$lichtgestaltgesamt</td><td>$lichtgestaltmale</td><td>$lichtgestaltfemale</td></tr>",true);
        output("<tr><td><b>Gargoyles</td><td>$gargoylegesamt</td><td>$gargoylemale</td><td>$gargoylefemale</td></tr>",true);
        */
        output("<tr><td><b>Alle</b></td><td>$gesamt</td><td>$male</td><td>$weiblich</td></tr>",true);
        output("</table>",true);
        ##**End
}elseif ($_GET['op']=="heiratsrassen"){
        output("<table border=1 frame=\"void\" cellspacing=0 cellpadding=5><tr><td></td>",true);
        $sql="SELECT * FROM race ORDER BY rid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            output("<td>$row[colorname]`0</td>",true);
        }
        output("</tr>",true);
        $sql="SELECT * FROM race ORDER BY rid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        for ($a=1;$a<=db_num_rows($result);$a++){
            $row=db_fetch_assoc($result);
            output("<tr><td>$row[colorname]</td>",true);
            $sql2="SELECT * FROM race ORDER BY rid DESC";
            $result2 = db_query($sql) or die(db_error(LINK));
            for ($b=0;$b<db_num_rows($result2);$b++){
                $row2=db_fetch_assoc($result2);
                output("<td>",true);
                if($row['rid']==$row2['rid']){
                  output("`^");
                }
                if(in_array($row['rid'],unserialize($row2['cantmarry'])) || in_array($row2['rid'],unserialize($row['cantmarry']))) output("nein");
                else output("ja");
                output("`0");
                output("</td>",true);
            }
            output("</tr>",true);
        }
        output("</table>",true);
/*}elseif ($_GET['op']=="skillstransfers"){
        $old=0;
        $new=0;
        $both=0;
        $nothing=0;
        //zurzeit keine Fähigkeit
        $sql="SELECT skill, specialty FROM accounts WHERE skill=0 AND specialty=0";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $nothing=$i;
        }
        //neue Fähigkeiten
        $sql="SELECT skill, specialty FROM accounts WHERE skill!=0 AND specialty=0";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $new=$i;
        }
        //alte Fähigkeiten
        $sql="SELECT skill, specialty FROM accounts WHERE skill=0 AND specialty!=0";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $old=$i;
        }
        //beide (Beta-TEster)
        $sql="SELECT skill, specialty FROM accounts WHERE skill!=0 AND specialty!=0";
        $result = db_query($sql);
        for ($i=1;$i<=db_num_rows($result);$i++){
                $both=$i;
        }
        $all=$both+$nothing+$new+$old;
        if ($old==0) $prozold=0;
        if ($old!=0){
           $prozold1=round(($old/$all)*100);
           $prozold2=($old/$all)*100;
           if ($prozold1==$prozold2) $prozold=$prozold1;
           if ($prozold1!=$prozold2) $prozold="~".$prozold1;
        }
        if ($both==0) $prozboth=0;
        if ($both!=0) {
           $prozboth1=round(($both/$all)*100);
           $prozboth2=($both/$all)*100;
           if ($prozboth1==$prozboth2) $prozboth=$prozboth1;
           if ($prozboth1!=$prozboth2) $prozboth="~".$prozboth1;
        }
        if ($new==0) $proznew=0;
        if ($new!=0) {
           $proznew1=round(($new/$all)*100);
           $proznew2=($new/$all)*100;
           if ($proznew1==$proznew2) $proznew=$proznew1;
           if ($proznew1!=$proznew2) $proznew="~".$proznew1;
        }
        if ($nothing==0) $proznothing=0;
        if ($nothing!=0) {
           $proznothing1=round(($nothing/$all)*100);
           $proznothing2=($nothing/$all)*100;
           if ($proznothing1==$proznothing2) $proznothing=$proznothing1;
           if ($proznothing1!=$proznothing2) $proznothing="~".$proznothing1;
        }
        if ($all==0) $prozall=0;
        if ($all!=0) $prozall=round(($all/$all)*100); //das das rein rechnerisch totaler blödsinn ist weiß ich auch :P
        
        //TABELLE
        output("<table border=1 frame=\"void\" cellspacing=0 cellpadding=5><tr><td><b>Status</b></td><td><b>insgesamt</b></td><td>Prozent</td></tr>",true);
        output("<tr><td><b><a href='statsexp.php?op=seeoldskills' border='0'>Altes System</a></b></td><td>$old</td><td>$prozold %</td></tr>",true);
        output("<tr><td><b>Neues System</b></td><td>$new</td><td>$proznew %</td></tr>",true);
        output("<tr><td><b>Beide Systeme</b></td><td>$both</td><td>$prozboth %</td></tr>",true);
        output("<tr><td><b>Kein System</td><td>$nothing</td><td>$proznothing %</td></tr>",true);
        output("<tr><td><b>Alle</b></td><td>$all</td><td>$prozall %</td></tr>",true);
        output("</table>",true);
        addnav("","statsexp.php?op=seeoldskills");
*/
}elseif ($_GET['op']=="seeoldskills"){
        $sql = "SELECT acctid,login,name,level,laston FROM accounts WHERE specialty!=0 ORDER BY level DESC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td>Ops</td>
        <td>Login</td>
        <td>Name</td>
        <td>Lev</td>
        <td>Zuletzt da</td>
        </tr>",true);
        $rn=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ") $laston="1 Tag";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if ($loggedin) $laston="Jetzt";
            $row[laston]=$laston;
            if ($row[$order]!=$oorder) $rn++;
            $oorder = $row[$order];
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

            output("<td>",true);
            output("<a href='user.php?op=edit&userid=$row[acctid]'>Edit</a>",true);
            addnav("","user.php?op=edit&userid=$row[acctid]");
            output("</td><td>",true);
            output($row[login]);
            output("</td><td>",true);
            output($row[name]);
            output("</td><td>",true);
            output($row[level]);
            output("</td><td>",true);
            output($row[laston]);
            output("</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
}elseif ($_GET['op']=="skillbalance"){
        $sql2="SELECT * FROM skills WHERE activated='1'";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $max = db_num_rows($result2);
        $blub = 0;
        output("<table border=1 frame=\"void\" cellspacing=0 cellpadding=5><tr><td><b>Fähigkeit</b></td><td><b>Anzahl</b></td><td>Prozent</td></tr>",true);
        for($i=0;$i<$max;$i++){
             $row2 = db_fetch_assoc($result2);
             $sqlx="SELECT * FROM accounts WHERE skill=$row2[id] AND specialty=0";
             $resultx = db_query($sqlx) or die(db_error(LINK));
             $x = db_num_rows($resultx);
             $sqly="SELECT * FROM accounts WHERE specialty=0 AND skill!=0";
             $resulty = db_query($sqly) or die(db_error(LINK));
             $y = db_num_rows($resulty);
             if ($y==0) $proz=0;
             if ($y!=0) {
                $proz1=round(($x/$y)*100);
                $proz2=($x/$y)*100;
                if ($proz1==$proz2) $proz=$proz1;
                if ($proz1!=$proz2) $proz="~".$proz1;
             }
             output("<tr><td>$row2[color] $row2[name]</td><td>$x</td><td>$proz%</td></tr>",true);
             $blub+=$x;
        }
        output("<tr><td>alle</td><td>$blub</td><td>100%</td></tr>",true);
        output("</table>",true);
}elseif ($_GET['op']=="common"){
        $sql = "SELECT sum(gentimecount) AS c, sum(gentime) AS t, sum(gensize) AS s, count(*) AS a FROM accounts";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`b`%Für existierende Accounts:`b`n");
        output("`@Accounts insgesamt: `^".number_format($row['a'])."`n");
        output("`@Treffer insgesamt: `^".number_format($row['c'])."`n");
        output("`@Seitengenerierungszeit insgesamt: `^".dhms($row['t'])."`n");
        output("`@Seitengenerierungsgröße insgesamt: `^".number_format($row['s'])."b`n");
        output("`@Durchschnittliche Seitengenerierungszeit: `^".dhms($row['t']/$row['c'],true)."`n");
        output("`@Durchschnittliche Seitengröße: `^".number_format($row['s']/$row['c'])."`n");
        output("`n`%`bTop Referers:`b`0`n");
        output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
        output("<tr class='trhead'><td><b>Name</b></td><td><b>Referrals</b></td></tr>",true);
        $sql = "SELECT count(*) AS c, acct.acctid,acct.name AS referer FROM accounts INNER JOIN accounts AS acct ON acct.acctid = accounts.referer WHERE accounts.referer>0 GROUP BY accounts.referer DESC ORDER BY c DESC";
        $result = db_query($sql);
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                output("`@{$row['referer']}`0</td><td>`^{$row['c']}:`0  ", true);
                $sql = "SELECT name,refererawarded from accounts WHERE referer = ${row['acctid']} ORDER BY acctid ASC";
                $res2 = db_query($sql);
                for ($j = 0; $j < db_num_rows($res2); $j++) {
                        $r = db_fetch_assoc($res2);
                        output(($r['refererawarded']?"`&":"`$") . $r['name'] . "`0");
                        if ($j != db_num_rows($res2)-1) output(",");
                }
                output("</td></tr>",true);
        }
        output("</table>",true);
}elseif ($_GET['op']=='expstats'){
        output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
        output("<tr class='trhead'><td><b>Level</b></td><td><b>Exp/Wk</b></td><td>Basierend auf</td><td>LostExp/PvP*</td><td>Basierend auf</td></tr>",true);
        for ($i=1;$i<=15;$i++){
            $sql = "SELECT * FROM stats WHERE name='expwk".$i."'";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            output("`@Level $i`0</td><td>`^".$row['value']."`0</td>",true);
            $sql2 = "SELECT * FROM stats WHERE name='wk".$i."'";
            $result2 = db_query($sql2);
            $row2 = db_fetch_assoc($result2);
            output("<td>`7".$row2['value']."`0</td>",true);
            $sql3 = "SELECT * FROM stats WHERE name='exppvp".$i."'";
            $result3 = db_query($sql3);
            $row3 = db_fetch_assoc($result3);
            output("<td>`^".$row3['value']."`0</td>",true);
            $sql4 = "SELECT * FROM stats WHERE name='pvp".$i."'";
            $result4 = db_query($sql4);
            $row4 = db_fetch_assoc($result4);
            output("<td>`7".$row4['value']."`0</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
        output("`n`n`n*Hierbei werden nur die PvP betrachtet in denen der Tote angegriffen wurde.");
        addnav("Erfahrungsstatistik");
        addnav("Reset Stats","statsexp.php?op=resetexp");
}elseif ($_GET['op']=='resetexp'){
        output("`\$Möchtest du wirklich die Statistiken auf 0 setzen? Die jetzigen Statistiken sind nicht wiederherstellbar!");
        addnav("Erfahrungsstatistik");
        addnav("`\$Ja","statsexp.php?op=resetexp2");
}elseif ($_GET['op']=='resetexp2'){
        for ($i=1;$i<=15;$i++){
            $sql1="UPDATE stats SET value=0 WHERE name='wk".$i."'";
            $sql2="UPDATE stats SET value=0 WHERE name='expwk".$i."'";
            db_query($sql1);
            db_query($sql2);
        }
        output("`@Resetted");
}elseif ($_GET['op']=='scriptlog'){
        if ($_GET['subop']=='autoupdate') {
                $scripts = array();
                $sql = 'SELECT scriptname FROM scriptlog';
                $result = db_query($sql);
                while ($row = db_fetch_assoc($result)) {
                        $scripts[$row['scriptname']] = 1;
                }

                $insert = '';
                $dir = dir('.');
                while ($file = $dir->read()) {
                        if (substr($file,-4)=='.php') {
                                if ($scripts[$file]==1) $scripts[$file]++;
                                else $insert .= ',("'.$file.'")';
                        }
                }
                $dir->close();

                if ($insert != '') {
                        $sql = 'INSERT INTO scriptlog (scriptname) VALUES '.substr($insert,1);
                        db_query($sql);
                }

                $delete = '0';
                foreach ($scripts AS $key=>$val) {
                        if ($val == 1) $delete .= ' OR scriptname="'.$key.'"';
                }
                $sql = 'DELETE FROM scriptlog WHERE '.$delete;
                db_query($sql);
                output('`$Autoupdate durchgeführt!`0`n`n');
        }
        elseif ($_GET['subop']=='delete') {
                $sql = 'DELETE FROM scriptlog WHERE scriptname="'.$_GET['scriptname'].'"';
                db_query($sql);
        }
        elseif ($_GET['subop']=='insert') {
                $sql = 'INSERT INTO scriptlog (scriptname) VALUES("'.$_POST['scriptname'].'")';
                db_query($sql);
        }

        output('`c`bScript-Statistik`b`c`n`n');
        output('<form action="statsexp.php?op=scriptlog&subop=insert" method="post">',true);
        addnav("","statsexp.php?op=scriptlog&subop=insert");
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
        output("<tr class='trhead'><td>&nbsp;</td><td><b>Script</b></td><td><b>Aufrufe</b></td><td><b>Gesamtzeit</b></td><td><b>Durchschnitt</b></td></tr>",true);
        $i = 0;
        $sql = 'SELECT * FROM scriptlog ORDER BY IF(hits>0,gentime/hits,0) DESC';
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
                output("<tr class='".($i%2?"trdark":"trlight")."'>
                                        <td>[<a href='statsexp.php?op=scriptlog&subop=delete&scriptname={$row['scriptname']}'>del</a>]</td>
                                        <td>{$row['scriptname']}</td>
                                        <td>".number_format($row['hits'])."</td>
                                        <td>".number_format($row['gentime'],3)."s</td>
                                        <td>".($row['hits']>0?round($row['gentime']/$row['hits'],3):'0')."s</td>
                                </tr>",true);
                addnav('',"statsexp.php?op=scriptlog&subop=delete&scriptname={$row['scriptname']}");
                $i++;
        }
        output('<tr class="'.($i%2?"trdark":"trlight").'">
                                <td>&nbsp;</td>
                                <td><input type="text" name="scriptname" class="input" maxlength="255"></td>
                                <td colspan="3"><input type="submit" value="Hinzufügen" class="button"></td>
                        </tr>
                        </table>',true);
        output('</form>',true);
        output('<form action="statsexp.php?op=scriptlog&subop=autoupdate" method="post">',true);
        addnav('','statsexp.php?op=scriptlog&subop=autoupdate');
        output('<input type="submit" value="Automatisches Scriptupdate" class="button">',true);
        output('</form>',true);
}else{
        output("Hier sind die verschiedensten Statistiken verfügbar. Bitte wähle in der Navi die Statistik die du haben möchtest");
}
addnav("Rassen");
addnav("Rassenverteilung","statsexp.php?op=racestats");
addnav("Heiratsmöglichkeiten","statsexp.php?op=heiratsrassen");
addnav("Fähigkeiten");
//addnav("Umstellung auf neues System","statsexp.php?op=skillstransfers");
addnav("Verteilung der Fähigkeiten","statsexp.php?op=skillbalance");
addnav("Sonstiges");
addnav("Allgemeine Statistiken","statsexp.php?op=common");
addnav("Erfahrungsstatisik","statsexp.php?op=expstats");
//addnav("Scriptstatistiken","statsexp.php?op=scriptlog");
page_footer();
?> 