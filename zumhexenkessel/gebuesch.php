<?php
require_once "common.php";
page_header("Gebüsch");
$session['user']['standort'] = "Gebüsch";
 if (isset($_GET['id'])){
    $sql="SELECT * FROM kauf WHERE id=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $item = db_fetch_assoc($result);
}

if ($_GET['op']=="saveh"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Hose"){
        output("".$session['user']['hose']."`Q gegen `q ".$item['teil']."");
        $session['user']['hose']=$item['teil'];
    }
}elseif ($_GET['op']=="saveo"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Oberteil"){
        output("".$session['user']['oberteil']."`Q gegen `q ".$item['teil']."");
        $session['user']['oberteil']=$item['teil'];
    }
}elseif ($_GET['op']=="savek"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Kleid"){
        output("".$session['user']['kleid']."`Q gegen `q ".$item['teil']."");
        $session['user']['kleid']=$item['teil'];
    }
}elseif ($_GET['op']=="savej"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Jacke"){
        output("".$session['user']['jacke']."`Q gegen `q ".$item['teil']."");
        $session['user']['jacke']=$item['teil'];
    }
}elseif ($_GET['op']=="saves"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Schuhe"){
        output("".$session['user']['schuhe']."`Q gegen `q ".$item['teil']."");
        $session['user']['schuhe']=$item['teil'];
    }
}elseif ($_GET['op']=="saveuh"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Hoeschen"){
        output("".$session['user']['hoeschen']."`Q gegen `q ".$item['teil']."");
        $session['user']['hoeschen']=$item['teil'];
    }
}elseif ($_GET['op']=="saveuo"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Unterhemd"){
        output("".$session['user']['uhemd']."`Q gegen `q ".$item['teil']."");
        $session['user']['uhemd']=$item['teil'];
    }
}elseif ($_GET['op']=="saver"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Ring"){
        output("".$session['user']['ring']."`Q gegen `q ".$item['teil']."");
        $session['user']['ring']=$item['teil'];
    }
}elseif ($_GET['op']=="savek"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Kette"){
        output("".$session['user']['kette']."`Q gegen `q ".$item['teil']."");
        $session['user']['kette']=$item['teil'];
    }
}elseif ($_GET['op']=="savep"){
    output("`QDu tauschst `q`n");
    if ($item['kat']=="Pircing"){
        output("".$session['user']['pircing']."`Q gegen `q ".$item['teil']."");
        $session['user']['pircing']=$item['teil'];
    }
}
if ($_GET['op']=="umziehen"){
$back=$_GET[back];
    if ($back=="") $back="village.php";
    if ($_GET[sorti]=="") $_GET[sorti]="id";
    output("`c`bDiese Sachen könntest du anziehen `b`c`n`n");
    output("<table cellspacing='1' cellpadding='2' align='center'><tr><td>`b<a href='gebuesch.php?op=umziehen&sorti=teil&back=$back&limit=$_GET[limit]'>Kleidung</a>`b</td><td>`b<a href='gebuesch.php?op=umziehen&sorti=kat&back=$back&limit=$_GET[limit]'>Klasse</a>`b</td><td>`bAktion`b</td></tr>",true);
    addnav("","gebuesch.php?op=umziehen&sorti=teil&back=$back&limit=$_GET[limit]");
    addnav("","gebuesch.php?op=umziehen&sorti=kat&back=$back&limit=$_GET[limit]");
    //addnav("","gebuesch.php?op=umziehen&sorti=gems&back=$back&limit=$_GET[limit]");
    $ppp=25; // Player Per Page to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Seite","gebuesch.php?op=umziehen&limit=".($page-1)."&back=$back"."&sorti=$_GET[sorti]");
    }
    $limit="".($page*$ppp).",".($ppp+1);

    $sql = "SELECT * FROM kauf WHERE besitzerid=".$session['user']['acctid']." ORDER BY $_GET[sorti] ASC LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","gebuesch.php?op=umziehen&limit=".($page+1)."&sorti=$_GET[sorti]"."&back=$back");
    if (db_num_rows($result)==0){
          output("<tr><td colspan=5 align='center'>`&`iDu hast nichts zum Anziehen`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $item = db_fetch_assoc($result);
            $bgcolor=($i%2==1?"trlight":"trdark");
            output("<tr class='$bgcolor'><td>`&$item[teil]`0</td><td>`!$item[kat]`0</td>",true);

            output("</td><td>[",true);
            if ($item['kat']=="Hose"){
                output("<a href='gebuesch.php?op=saveh&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saveh&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Oberteil"){
                output("<a href='gebuesch.php?op=saveo&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saveo&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Kleid"){
                output("<a href='gebuesch.php?op=savek&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=savek&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Jacke"){
                output("<a href='gebuesch.php?op=savej&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=savej&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Schuhe"){
                output("<a href='gebuesch.php?op=saves&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saves&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Hoeschen"){
                output("<a href='gebuesch.php?op=saveuh&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saveuh&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Unterhemd"){
                output("<a href='gebuesch.php?op=saveuo&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saveuo&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Ring"){
                output("<a href='gebuesch.php?op=saver&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=saver&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Kette"){
                output("<a href='gebuesch.php?op=savek&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=savek&id=$item[id]&back=$back");
            }elseif ($item['kat']=="Pircing"){
                output("<a href='gebuesch.php?op=savep&id=$item[id]&back=$back'>Anziehen</a>",true);
                addnav("","gebuesch.php?op=savep&id=$item[id]&back=$back");
            }else{
                output(" - ");
            }
            output("]</td></tr>",true);
        }
    }


 }



addnav("Umziehen","gebuesch.php?op=umziehen");
if ($session[user][zeit]==0)addnav("Zurück zur Stadt","village.php");
if ($session[user][zeit]==1)addnav("Zurück zum Dorf","villagezwei.php");

addnav("Zurück zum Dorf","villagezwei.php");
page_footer();
?> 