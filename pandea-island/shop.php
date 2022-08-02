<?php
// shop by dso

require_once "common.php";
checkday();

page_header("Das kleine Handelsviertel");

// check if someone is inside

if (($session[user][specialinc]!="")&&($_GET[id])){
    $sql="SELECT * FROM shops WHERE shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if(insideshop($row[shopid])){
        include("shops/".$row[source]);
        page_footer();
        exit();
    }
    else {
        $session[user][specialinc] = "";
        redirect('shop.php');
    }
// check if soneone get in

}else if ($_GET[op]=="drin"){
    $sql="SELECT * FROM shops WHERE shopid='$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    /*
     * ueberpruefen, ob shop offen, wenn nein:
     * schild: geschlossen
     */
   // $session[user][specialinc] = $row[source];
    /*ueberpruefen, ob shop offen/geoeffnet werden kann */
    if(insideshop($row[shopid])){
        if(@file_exists("./shops/".$row[source])){
            //$session['user']['specialinc']=$row[source];
            include("shops/".$row[source]);
        } else {    //entsprechender shop ist noch nciht ausgepraegt - also noch im bau
          output("`n`n`@Dieser Shop ist noch nicht fertig.`n`n");
          addnav("Hanseviertel","shop.php");
          addnav("Zurück zum Dorf","village.php");
        }
    } else {
        addnav("Handelsviertel","shop.php");
        addnav("Zum Dorfplatz","village.php");
        output("`n`n`^Der Eingang zum $row[shopname] ist zur Zeit geschlossen.`0");
//        output("`n`nDu siehst ein Notizbrett, auf der du eine Nachricht hinterlassen kannst.");
//        addnav("Notizwand","shop.php?op=blackboard&id=$row[shopid]");
            if ($session[user][superuser]>=2){
            addnav("Generalschluessel","shop.php?op=general");
        }
    }

}else {
    $session[user][specialinc] = "";
    shops();
}


function insideshop($shopid){
    global $session;
    $sql1="SELECT COUNT(*) AS owner FROM shops_owner so
            LEFT JOIN shops s USING(shopid)
            LEFT JOIN accounts a ON a.acctid=so.acctid
            WHERE so.shopid='$shopid'
            AND (
                a.acctid=".$session['user']['acctid']."
                OR
                (
                    a.specialinc=s.source
                    AND a.loggedin='1'
                    AND a.locked='0'
                    AND a.laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
                )
            )
            ";
    $result1 = db_query($sql1) or die(db_error(LINK));
    $row1 = db_fetch_assoc($result1);
    if ($row1['owner']>0) return true;
    else return false;
}

function owneris($acctid, $shopid){
    $sql="SELECT * FROM shops_owner WHERE shopid = $shopid";
    $result = db_query($sql) or die(db_error(LINK));
    for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if($row[acctid]==$acctid){
            return true;
        }
    }
    return false;
}

function shops(){
    global $session;
    $session['user']['specialinc']="";

    $sql = "SELECT shopid, shopname, source FROM shops WHERE 1 ORDER BY shopid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Shops, die von anderen geleitet werden, als MigtyE und Pegasus`i`0</td></tr>",true);
    }else{
        output('`c`bDie Straße der Händler`b`c`n`n');
        output('Du biegst in eine Seitengasse ab und erstaunt stellst du fest, dass dies wohl
                ein noblerer Teil des Dorfes sein muss. Die Straße ist gepflastert und die
                Häuser sehen edler aus. Leuchtend bunte Markisen zeigen dir, dass dies wohl die
                Ladenstraße sein muss. Langsam trittst du an die großen Fenster heran, in denen
                die Händler ihre Waren ausgestellt haben. Schon jetzt wird dir klar bei den
                vielen bunten und glänzenden Dingen, dass die Entscheidung schwer fallen wird,
                was du wohl als erstes benötigst.`n`n');
    output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td></tr>",true);
    output("<tr><td align='center'>1</td><td><a href='weapons.php'>MightyE's Waffen</a></td></tr>",true);
        addnav("MightyE's Waffen","weapons.php");
        addnav("","weapons.php");
    output("<tr><td align='center'>2</td><td><a href='armor.php'>Pegasus Rüstungen</a></td></tr>",true);
        addnav("Pegasus Rüstungen","armor.php");
        addnav("","armor.php");
        if ($session[user][shopban]>0){
            output("Die Händler haben entschlossen, vorerst nicht mit dir zu handeln ....");
        }else{
            output("<tr><td colspan='2'>Spielergebäude die aktuell geöffnet sind</td></tr>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $a=$i+3;
                $row3 = db_fetch_assoc($result);
                $sql2="SELECT * FROM shops WHERE shopid='$_GET[id]'";
                $result2=db_query($sql2) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
 //             addnav ($row3['shopid']."JHj","kjjk.php");
                if(insideshop($row3['shopid'])){

                    output("<tr><td align='center'>$a</td><td><a href='shop.php?op=drin&id=$row3[shopid]'>$row3[shopname]</a></td></tr>",true);
                    addnav($row3['shopname'],"shop.php?op=drin&id=$row3[shopid]");
                    addnav('',"shop.php?op=drin&id=$row3[shopid]");
                        }
            }
        }
        output("</table>",true);
    }
    addnav("Zurück zum Dorf","village.php");


}
page_footer();
?>