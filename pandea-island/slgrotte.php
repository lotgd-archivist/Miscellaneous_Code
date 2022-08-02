<?php
/*
Brunnen by dso 10/2004
*/

require_once("common.php");
addcommentary();
checkday();
page_header("Die kleine Spielleitergrotte");

if ($_GET['op']=="killuser")
{
    if($_GET['subop']=="killuser")
    {
        $user=$_POST['user2kill'];
        $why=$_POST['why'];
        $explost=$_POST['explost'];


        $string="%";
        for ($x=0;$x<strlen($_POST['user2kill']);$x++){
            $string .= substr($_POST['user2kill'],$x,1)."%";
        }
        $sql = "SELECT acctid, name, login FROM accounts a WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
        $result = db_query($sql);

        output("`7Bitte bestätige nochmals, welchen Krieger du ");
        output("ins Reich der Schatten schicken möchtest (Grund: \"`\$$why`7\").`n`n");
        output("<form action='slgrotte.php?op=killuser&subop=done' method='POST'>",true);
        output("<input type='hidden' name='why' value='".$why."'>",true);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            output("<input type='hidden' name='user2kill' value=\"".$row[login]."\">",true);
            output("`^$row[name]`n");
        }else{
            output("<select name='user2kill'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".HTMLEntities($row[login])."\">",true);
                output(preg_replace("/[`]./","",$row[name]));
            }
            output("</select>`n",true);
        }
        output("Erfahrungsverlust:");
        if($explost>20)
            {
             output("20% (zu hohe Eingabe)`n");
             $explost=20;
            }
        else
            {
             output("$explost%`n");
            }
        output("<input type='hidden' name='explost' value='".$explost."'>",true);

        output("`n<input type='submit' name='senden' value='töten'>",true);
        output("</form>",true);
        addnav("","slgrotte.php?op=killuser&subop=done");

    }
    elseif($_GET['subop']=="done")
    {
        $user=$_POST['user2kill'];
        $why=$_POST['why'];
        $explost=$_POST['explost'];

        $sql="select name, acctid, login, experience from accounts where login='".$user."'";
        $result=db_query($sql);
        $row=db_fetch_assoc($result);

        $exp=round($row['experience']*((100-$explost)/100),0);
        $lost=$row['experience']-$exp;
        $aid=$row['acctid'];

        $sql="update accounts set gravefights=0,alive=0,hitpoints=0,experience=".$exp." where login='".$user."'";
        db_query($sql);
        output("`7Du hast `\$".$row[name]."`7 ins Reich der Schatten befördert. Grund: `#".$why."`n`n`0");

        systemmail($aid,"`\$Reich der Schatten","`#Aufgrund deiner Uneinsichtigkeit als Spieler bzw. mangelnder Beachtung eines Spielleiters wurdest du ins Reich der Schatten befördert. Für das Rollenspiel bedeutet dies, dass dein Charakter nun eine ganze Weile bewusstlos ist (denn der Tod im Rollenspiel wäre endgültig).`nEs bleibt zu hoffen, dass du in Zukunft stärker mit einem Spielleiter spielen wirst anstatt gegen ihn, damit sich dies nicht wiederholt.`n`nDu hast $explost% deiner Erfahrung (etwa $lost Punkte) verloren und kannst erst am nächsten regulären Spieltag weiterspielen.");

//        output("`7Du hast `\$".$row[name]."`7 ins Reich der Schatten geschickt. Grund: `#".$why."`n`n`0");

//        systemmail($user,"`$Gestorben","`#Aufgrund gewisser Aktionen auf öffentlichen Plätzen wurdest du ins Reich der Töten befördert.`nDu must nun bis zum kommenden Tag warten, bis du das Reich wieder verlassen kannst.");
        //addnews($row[name]."`0 ist wegen ".$why."`0 gestorben");

        $sql = 'INSERT INTO commentary (section, author, comment, postdate) VALUES
                    ("wache",'.$session['user']['acctid'].',"/me schickte am '.date('d.m.Y, H:i:s').' Uhr, '.$row[name].' ins Reich der Schatten. Grund: `b'.$why.'`b",NOW())';
        db_query($sql);
        adminlog();


    }
    else
    {
        output("`7Gib bitte an, welchen Kämpfer du warum ins Reich der Schatten befördern möchtest:`n`n");
        output("<form action='slgrotte.php?op=killuser&subop=killuser' method='POST'>",true);
        output("Krieger: <input type='text' name='user2kill'><br>",true);
        output("Grund: <input type='text' name='why'><br>",true);
        output("Erfahrungsverlust in % (max. 20%): <input type='text' name='explost'>",true);
    //    output("<input type='text' name='why'> ",true);
        output("<input type='submit' name='senden' value='töten'>",true);
        output("</form>",true);
        output("`n`n`0");
        addnav("","slgrotte.php?op=killuser&subop=killuser");
    }
    // $session['user']['specialinc']=$shopname;

    addnav("Zurück in die Grotte","slgrotte.php");
}

else {


addnav("Zum Dorf","village.php");
addnav("Krieger zu Ramius befördern","slgrotte.php?op=killuser");

output("`@Du befindest dich nun in der Grotte der Spielleiter. Etwas liegt in der Luft... Kreativität oder doch eher das Gefühl von Macht?`n");
output("Hier kannst du dich mit anderen Spielleitern über laufende Ereignisse austauschen und das weitere Vorgehen besprechen. Grundsätzliche ");
output("Diskussionen sollten allerdings im Forum geführt werden, ebenso wie Ideen für neue (etwas größere/umfangreichere) Questen dort geäußert ");
output("werden sollten.`nWeiterhin kannst du hier Spieler ins Reich der Schatten befördern, wenn sie sich allzu uneinsichtig verhalten haben. ");
output("Dies ist allerdings wirklich nur dazu gedacht, nicht zur \"Unterstreichung\" von Rollenspieltoden, die von Spielern eingeleitet wurden.`n`n");

viewcommentary("slgrotte","Mit Umstehenden reden:",25,"sagt");


}
page_footer();
?>