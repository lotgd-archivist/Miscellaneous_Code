<?php

// 24072004

// The vendor Mortimer sells furniture for houses and buys items found at beaten monsters in the forest.
// items.class for furniture prototypes must be 'Möbel.Prot'.
// items.class for bought furniture is set to 'Möbel' automatically.
// Use itemeditor.php in admin grotto to generate furniture prototyps and items.
// More classes will be added soon!
//
// Requires 'items' table first introduced with my houses mod and a few other modifications for
// inventory management and drop items.
// No installation instructions available so far. Sorry!!
//
// Vendor only appears on a few (game) days in village
// This is controlled by weather mod by Talisman
//
// by anpera (2004) while listening to music by 'The Sweet' ;)

require_once "common.php";
page_header("Dark Asylum");

if ($_GET[op]=="buy"){ // Wig-Wam Bam
    if (!$_GET[id]){
        $sorti=($_GET[sorti]?"$_GET[sorti]":"class DESC, name");
        output("`qAbweisend präsentiert dir der Händler `tXarox`q seine Waren. Knurrend gibt er dir Auskunft zu jedem Stück seiner Auslage. `RWenn de nix kaufen willst, hau ab. `q`n ");
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Vorherige Waren","asylum.php?op=buy&sorti=$sorti&limit=".($page-1));
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql="SELECT * FROM items WHERE owner=0 AND class='Asylum.prot' ORDER BY $sorti ASC LIMIT $limit";
        $result=db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Mehr Waren","asylum.php?op=buy&sorti=$sorti&limit=".($page+1));
        if (db_num_rows($result)){
            output("<table border='0' cellpadding='2' cellspacing='2'>",true);
            output("<tr class='trhead'><td>`b<a href='asylum.php?op=buy&sorti=name&limit=$_GET[limit]'>Name</a>`b</td><td>`b<a href='asylum.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit=$_GET[limit]'>Preis</a>`b</td></tr>",true);
            addnav("","asylum.php?op=buy&sorti=name&limit=$_GET[limit]");
            addnav("","asylum.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit=$_GET[limit]");
            //addnav("","asylum.php?op=buy&sorti=".urlencode("class DESC,name")."&limit=$_GET[limit]");
            for ($i=0;$i<db_num_rows($result);$i++){
                  $row = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td><a href='asylum.php?op=buy&id=$row[id]'>$row[name]</a></td><td align='right'>`^$row[gold]`0 Gold, `#$row[gems]`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='3'>$row[description]</td></tr>",true);
                addnav("","asylum.php?op=buy&id=$row[id]");
            }
            output("</table>",true);
        
        } else {
            output("`qDa `tXarox `qheute schon ein gutes Geschäft gemacht hat, will er sich leider nicht von seinen verbliebenen Sachen trennen. Enttäuscht schlenderst du zurück zum Dorfplatz.");
        }
    }else{ // Alexander Graham Bell (what? no, he's not the author of this part. It's the name of a song by The Sweet)
        $sql="SELECT * FROM items WHERE id=$_GET[id]";
        $result=db_query($sql);
          $row = db_fetch_assoc($result);
        if ($session[user][gems]<$row[gems] || $session[user][gold]<$row[gold]){
            output("`qHoppla! Das kannst du dir nicht leisten. Der Händler schüttelt nur traurig den Kopf und verstaut $row[name] wieder in seinem Wagen.");
            addnav("Etwas anderes kaufen","asylum.php?op=buy");
        }else if ($row['class']=="Asylum.Prot" && $session[user][housekey]<=0 ){
            output("`q$row[name]`q gefällt dir wirklich gut, aber da du kein eigenes Haus besitzt, kannst du mit Möbeln auch nichts anfangen.");
            addnav("Etwas anderes kaufen","asylum.php?op=buy");
        }else if (db_num_rows(db_query("SELECT id FROM items WHERE name='$row[name]' AND owner=".$session[user][acctid]." AND class='Asylum'"))>0){
            output("`qDu hast $row[name]`q schon. Du überlegst, ob sich eine Neuanschaffung wirklich lohnt. Allerdings müsstest du dazu auch erst den alten Krempel verkaufen.");
            addnav("Etwas anderes kaufen","asylum.php?op=buy");
        }else{
            output("`qDer Händler reibt sich die Hände und übergibt dir $row[name], während du ".($row[gold]?"`^$row[gold] `qGold":"")." ".($row[gems]?"`#$row[gems]`q Edelsteine":"")." abzählst. ");
            if ($row['class']=="Asylum.Prot") output(" Er knurrt dich noch an: `RMusste selber schaun, wie du den Krempel wech kriegst. `q, bevor er sich seinem nächsten Kunden zuwendet.");
            addnav("Mehr kaufen","asylum.php?op=buy");
            $sql="UPDATE items SET owner=".$session[user][acctid]." WHERE id=$_GET[id]";
            // insert SQL for special classes here to reset their values
            if ($row['class']=="Asylum.Prot") $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('$row[name]','Asylum',".$session[user][acctid].",".$session[user][house].",1,".(round($row[gems]/2)).",'$row[description]')";
            $session[user][gold]-=$row[gold];
            $session[user][gems]-=$row[gems];
            db_query($sql);
        }
    }
    addnav("Zurück","asylum.php");
    addnav("Zurück zum Marktplatz","marktplatz.php");
}else if ($_GET[op]=="sell"){ // Ballroom Blitz
    if (!$_GET[id]){
        output("`qDer Händler begutachtet deinen Besitz. Mit dem geübten Auge eines Kenners sortiert er die Dinge aus, die ihn interessieren würden und nennt dir einen Preis dafür.`n`n");
        $sql="SELECT * FROM items WHERE owner=".$session[user][acctid]." AND (gold>0 OR gems>0) AND class='Asylum'";
        $result=db_query($sql);
        if (db_num_rows($result)){
            output("<table border='0' cellpadding='0'>",true);
            output("<tr class='trhead'><td>`bName`b</td><td>`bPreis`b</td></tr>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                  $row = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td><a href='asylum.php?op=sell&id=$row[id]'>$row[name]</a></td><td align='right'>`^$row[gold]`0 Gold, `#$row[gems]`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='2'>$row[description]</td></tr>",true);
                addnav("","asylum.php?op=sell&id=$row[id]");
            }
            output("</table>",true);
        
        } else {
            output("Du hast aber nichts, was `tXarox`q interessieren würde. Enttäuscht schlenderst du zurück zum Marktplatz..");
        }
    }else{ // Hell Raiser
        $sql="SELECT * FROM items WHERE id=$_GET[id]";
        $result=db_query($sql);
          $row = db_fetch_assoc($result);
        output("`qMit einem breiten und siegessicheren Grinsen gibt er dir die vereinbarten ".($row[gold]?"`^$row[gold] `qGold":"")." ".($row[gems]?"`#$row[gems]`q Edelsteine":"")." und schnappt sich $row[name]. ");
        //if ($row['class']=="Beute") output(" Noch bevor du fragen kannst, wofür $row[name] wirklich zu gebrauchen ist, lässt der Händler das Teil in seinem Wagen verschwinden, grinst immer noch und fragt, ob du sonst noch etwas für ihn hast.");
        addnav("Mehr verkaufen","asylum.php?op=sell");
        $sql="UPDATE items SET owner=0 WHERE id=$_GET[id]";
        // insert SQL für special classes here to reset their values
        if ($row['class']=="Asylum") $sql="DELETE FROM items WHERE id=$_GET[id]";
        //if ($row['class']=="Beute") $sql="DELETE FROM items WHERE id=$_GET[id]";
        //if ($row['class']=="Waffe" || $row['class']=="Rüstung") $sql="DELETE FROM items WHERE id=$_GET[id]";
        //if ($row['class']=="Schmuck" AND $row['name']=="Elfenkunst") $sql="DELETE FROM items WHERE id=$_GET[id]";
        $session[user][gold]+=$row[gold];
        $session[user][gems]+=$row[gems];
        db_query($sql);
    }
    addnav("Zurück zum Dorf","marktplatz.php");
}else{ // Teenage Rampage
    checkday();
    //if (!getsetting("vendor",0)) redirect("marktplatz.php");
    output("`c`wDa`4rk `RAs`4sy`wlum `n`n`c`c`wEi`4n d`Rüsterer Ort, den du hier gefunden `4ha`wst.`n
`wDi`4e s`Rchwarze Magie, die hier verkauft `4wi`wrd,`n
`wer`4sc`Rhlägt dich fast und nur deine jahrelange Erfahrung hält dich auf den Be`4in`wen.`n
`wEs `4gi`Rbt keine Lampen an diesem Ort der Dunkel`4he`wit.`n
`wDi`4e S`Rammlerstücke, die du hier kaufen ka`4nn`wst,`n
`wle`4uc`Rhten von Innen und strömen vor dunkler M`4ag`wie.`n
`wEi`4n T`Rotenschädel neben der Tür spricht di`4ch `wan.`n
\"`MWe`-nn `Rdu nix kaufen willst, verschwinde oder es wird dir lei`-d t`Mun.\"`n
`wDa `4du `Rweißt, daß du etwas kaufen willst, ignorierst du ihn und gehst we`4it`wer.`n
`wIm`4me`Rr tiefer in diesen Laden der Verdammnis und schaust dir die Stüc`4ke `wan.`c");
    addnav("Waren durchstöbern","asylum.php?op=buy");
    //addnav("Etwas verkaufen","asylum.php?op=sell");
    //addnav("Inventar anzeigen","prefs.php?op=inventory&back=asylum.php");
    addnav("Zurück zum Marktplatz","marktplatz.php");
}
page_footer();
// reading source code can seriously damage your eyes! Well, at least it can take out the fun of a game...
?>