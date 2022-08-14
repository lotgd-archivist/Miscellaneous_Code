
ï»¿<?php



// 08102005



// The vendor Aeki sells furniture for houses and buys items found at beaten monsters in the forest.

// items.class for furniture prototypes must be 'MÃ¶bel.Prot'.

// items.class for bought furniture is set to 'MÃ¶bel' automatically.

// Use itemeditor.php in admin grotto to generate furniture prototyps and items.

// More classes will be added soon!

//

// Requires 'items' table first introduced with my houses mod and a few other modifications for

// inventory management and drop items.

// No installation instructions available so far. Sorry!!

//

// Vendor only appears on a few (game) days in village

// This is controlled by weather mod by Talisman

//

// by anpera (2004) while listening to music by 'The Sweet' ;)



require_once("common.php");

page_header("WanderhÃ¤ndler");



if ($_GET['op']=="buy"){ // Wig-Wam Bam

    if (!$_GET['id']){

        $sorti=($_GET['sorti']?"{$_GET['sorti']}":"class DESC, name");

        output("`qStolz prÃ¤sentiert dir der HÃ¤ndler `tAeki`q seinen Wagen. Zu jedem der seltsamen GegenstÃ¤nde, Artefakte und Zauber scheint er eine kleine Geschichte zu kennen. Dabei scheint er auffÃ¤llig oft darauf ");

        output("hinzuweisen, dass viele Leute, von denen er etwas gekauft hat, den wahren Wert dieser Dinge nicht zu kennen scheinen.`n ");

        $ppp=25; // Player Per Page to display

        if (!$_GET['limit']){

            $page=0;

        }else{

            $page=(int)$_GET['limit'];

            addnav("Vorherige Waren","vendor.php?op=buy&sorti=$sorti&limit=".($page-1));

        }

        $limit="".($page*$ppp).",".($ppp+1);

        $sql="SELECT * FROM items WHERE owner=0 AND (class='MÃ¶bel.Prot' OR (class='Rezept.Prot' AND (gold>0 OR gems>0))) ORDER BY $sorti ASC LIMIT $limit";

        $result=db_query($sql);

        if (db_num_rows($result)>$ppp) addnav("Mehr Waren","vendor.php?op=buy&sorti=$sorti&limit=".($page+1));

        if (db_num_rows($result)){

            output("<table border='0' cellpadding='2' cellspacing='2'>",true);

            output("<tr class='trhead'><td>`b<a href='vendor.php?op=buy&sorti=name&limit={$_GET['limit']}'>Name</a>`b</td><td>`b<a href='vendor.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit={$_GET['limit']}'>Preis</a>`b</td><td>`b<a href='vendor.php?op=buy&sorti=".urlencode("class DESC,name")."&limit={$_GET['limit']}'>Klasse</a>`b</td></tr>",true);

            addnav("","vendor.php?op=buy&sorti=name&limit={$_GET['limit']}");

            addnav("","vendor.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit={$_GET['limit']}");

            addnav("","vendor.php?op=buy&sorti=".urlencode("class DESC,name")."&limit={$_GET['limit']}");

            for ($i=0;$i<db_num_rows($result);$i++){

                  $row = db_fetch_assoc($result);

                $bgcolor=($i%2==1?"trlight":"trdark");

                if (strpos($row['class'],"ezept")) $row['description']="Rezept zum Erzeugen von ".$row['name'];

                output("<tr class='$bgcolor'><td><a href='vendor.php?op=buy&id={$row['id']}'>{$row['name']}</a></td><td align='right'>`^{$row['gold']}`0 Gold, `#{$row['gems']}`0 Edelsteine</td><td>{$row['class']}</td></tr><tr class='$bgcolor'><td colspan='3'>{$row['description']}</td></tr>",true);

                addnav("","vendor.php?op=buy&id={$row['id']}");

            }

            output("</table>",true);



        } else {

            output("`qDa `tAeki `qheute schon ein gutes GeschÃ¤ft gemacht hat, will er sich leider nicht von seinen verbliebenen Sachen trennen. EnttÃ¤uscht schlenderst du zurÃ¼ck zum Dorfplatz.");

        }

    }else{ // Alexander Graham Bell (what? no, he's not the author of this part. It's the name of a song by The Sweet)

        $sql="SELECT * FROM items WHERE id={$_GET['id']}";

        $result=db_query($sql);

          $row = db_fetch_assoc($result);

        if ($session['user']['gems']<$row['gems'] || $session['user']['gold']<$row['gold']){

            output("`qHoppla! Das kannst du dir nicht leisten. Der HÃ¤ndler schÃ¼ttelt nur traurig den Kopf und verstaut {$row['name']} wieder in seinem Wagen.");

            addnav("Etwas anderes kaufen","vendor.php?op=buy");

        }else if ($row['class']=="MÃ¶bel.Prot" && $session['user']['housekey']<=0 ){

            output("`q$row[name]`q gefÃ¤llt dir wirklich gut, aber da du kein eigenes Haus besitzt, kannst du mit MÃ¶beln auch nichts anfangen.");

            addnav("Etwas anderes kaufen","vendor.php?op=buy");

        }else if (db_num_rows(db_query("SELECT id FROM items WHERE name='{$row['name']}' AND owner=".$session['user']['acctid']." AND (class='MÃ¶bel' OR class='Rezept')"))>0){

            output("`qDu hast {$row['name']}`q schon. Du Ã¼berlegst, ob sich eine Neuanschaffung wirklich lohnt. Allerdings mÃ¼sstest du dazu auch erst den alten Krempel verkaufen.");

            addnav("Etwas anderes kaufen","vendor.php?op=buy");

        }else{

            output("`qDer HÃ¤ndler reibt sich die HÃ¤nde und Ã¼bergibt dir {$row['name']}, wÃ¤hrend du ".($row['gold']?"`^{$row['gold']} `qGold":"")." ".($row['gems']?"`#{$row['gems']}`q Edelsteine":"")." abzÃ¤hlst. ");

            if ($row['class']=="MÃ¶bel.Prot") output(" Er ist dir noch kurz beim Transport behilflich, bevor er sich seinem nÃ¤chsten Kunden zuwendet.");

            addnav("Mehr kaufen","vendor.php?op=buy");

            $sql="UPDATE items SET owner=".$session['user']['acctid']." WHERE id={$_GET['id']}";

            // insert SQL for special classes here to reset their values

            if ($row['class']=="MÃ¶bel.Prot") $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('{$row['name']}','MÃ¶bel',".$session['user']['acctid'].",".$session['user']['house'].",1,".(round($row['gems']/2)).",'{$row['description']}')";

            if ($row['class']=="Rezept.Prot") $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('{$row['name']}','Rezept',".$session['user']['acctid'].",".$row['value1'].",{$row['gold']},".$row['gems'].",'{$row['description']}')";

            $session['user']['gold']-=$row['gold'];

            $session['user']['gems']-=$row['gems'];

            db_query($sql);

        }

    }

    addnav("ZurÃ¼ck","vendor.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");



}else if ($_GET['op']=="sell"){ // Ballroom Blitz

    if (!$_GET['id']){

        output("`qDer HÃ¤ndler begutachtet deinen Besitz. Mit dem geÃ¼bten Auge eines Kenners sortiert er die Dinge aus, die ihn interessieren wÃ¼rden und nennt dir einen Preis dafÃ¼r.`n`n");

        $sql="SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND (gold>0 OR gems>0) AND class<>'Fluch' AND class<>'Zauber' AND class<>'Haustiere' AND class<>'Rezept'";

        $result=db_query($sql);

        if (db_num_rows($result)){

            output("<table border='0' cellpadding='0'>",true);

            output("<tr class='trhead'><td>`bName`b</td><td>`bPreis`b</td></tr>",true);

            for ($i=0;$i<db_num_rows($result);$i++){

                $row = db_fetch_assoc($result);

                if (!($row['class']=="Kleidung" && $row['hvalue']==1)){

                    $bgcolor=($i%2==1?"trlight":"trdark");

                    output("<tr class='$bgcolor'><td><a href='vendor.php?op=sell&id={$row['id']}'>{$row['name']}</a></td><td align='right'>`^{$row['gold']}`0 Gold, `#{$row['gems']}`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='2'>{$row['description']}</td></tr>",true);

                    addnav("","vendor.php?op=sell&id={$row['id']}");

                }

            }

            output("</table>",true);



        } else {

            output("Du hast aber nichts, was `tAeki`q interessieren wÃ¼rde. EnttÃ¤uscht schlenderst du zurÃ¼ck zum Dorfplatz.");

        }

    }else{ // Hell Raiser

        $sql="SELECT * FROM items WHERE id=".$_GET['id'];

        $result=db_query($sql);

          $row = db_fetch_assoc($result);

        output("`qMit einem breiten und siegessicheren Grinsen gibt er dir die vereinbarten ".($row['gold']?"`^{$row['gold']} `qGold":"")." ".($row['gems']?"`#{$row['gems']}`q Edelsteine":"")." und schnappt sich {$row['name']}. ");

        if ($row['class']=="Beute") output(" Noch bevor du fragen kannst, wofÃ¼r {$row['name']} wirklich zu gebrauchen ist, lÃ¤sst der HÃ¤ndler das Teil in seinem Wagen verschwinden, grinst immer noch und fragt, ob du sonst noch etwas fÃ¼r ihn hast.");

        addnav("Mehr verkaufen","vendor.php?op=sell");

        $sql="UPDATE items SET owner=0 WHERE id=".$_GET['id'];

        // insert SQL fÃ¼r special classes here to reset their values

        if ($row['class']=="MÃ¶bel") $sql="DELETE FROM items WHERE id=".$_GET['id'];

        if ($row['class']=="Beute") $sql="DELETE FROM items WHERE id=".$_GET['id'];

if ($row['class']=="Kleidung") $sql="DELETE FROM items WHERE id=".$_GET['id'];

        if ($row['class']=="Waffe" || $row['class']=="RÃ¼stung") $sql="DELETE FROM items WHERE id=".$_GET['id'];

        if ($row['class']=="Schmuck" AND $row['name']=="Elfenkunst") $sql="DELETE FROM items WHERE id=".$_GET['id'];

        $session['user']['gold']+=$row['gold'];

        $session['user']['gems']+=$row['gems'];

        db_query($sql);

    }

    addnav("ZurÃ¼ck zum Dorf","village.php");

}else{ // Teenage Rampage

    checkday();

    if (!getsetting("vendor",0)) redirect("village.php");

    output("`qHeute ist der WanderhÃ¤ndler `tAeki `qwieder im Dorf! Direkt vor `!MightyE`qs Waffenladen hat er seinen Wagen aufgebaut, was MightyE sichtlich missfÃ¤llt. Da er aber ");

    output(" selbst hin und wieder Handel mit ihm betreibt, lÃ¤ÃŸt er ihn gewÃ¤hren.`nNeugierig nÃ¤herst du dich dem Wagen, um zu sehen, ob der HÃ¤ndler diesmal etwas Interessantes");

    output(" fÃ¼r dich dabei hat. Vielleicht hast du aber auch etwas, das du ihm verkaufen kannst?`n");

    addnav("Waren durchstÃ¶bern","vendor.php?op=buy");

    addnav("Etwas verkaufen","vendor.php?op=sell");

    addnav("Inventar anzeigen","prefs.php?op=inventory&back=vendor.php");

    addnav("ZurÃ¼ck zum Dorf","village.php");

}

page_footer();

// reading source code can seriously damage your eyes! Well, at least it can take out the fun of a game...

?>

