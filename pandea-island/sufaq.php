<?php
/*
2014/02/05    by aragon
FAQ & Rules-System
dynamic System for setting Rules viewable in the FAQs


**** Database
Tabelle 1: Ruleset (Regelsätze)
id, integer (8) .... primary key, auto increment
orderid, integer (8) ... (reihenfolge, falls ein neuer regelsatz wo eingefügt werden sollte)
title, varchar (50) ... not null
creator, integer (11) ... account-id von dem, ders angefangen hat

Tabelle 2: Rules (regeln)
rsid, integer (die ruleset-id) ... foreign key ruleset.id, primary key mit id
id, integer (8) ... primary key mit rsid
orderid, integer (8) ... (reihenfolge der Regeln innerhalb eines Regelsatzes)
title, varchar (100) ... der Titel der Regel
content, text .... (also der text, der regel ... datentyp text, nachdem ich nicht weiß wie lang er wird ^^)
version, integer (8) .... ich will ne versionierung haben
creator, integer (11) ... account-id von dem, ders geändert hat



**** structure (how i thought of it)
übersicht:
$sql="SELECT * FROM Ruleset ORDER BY orderid ASC"

Regelansicht:
$sql="SELECT * FROM Rules WHERE rsid=$rsid AND version=MAX(version) ORDER BY orderid ASC"


Administration:
Änderung der Reihenfolge:
if($change<$old) // rauf reihen
{
    $sql="UPDATE Rules SET orderid=(orderid+1) WHERE orderid>=$change AND orderid<=$old"
    $sql="UPDATE Rules SET orderid=$change WHERE id="
}


if($change>$old) // rauf reihen
{
    $sql="UPDATE Rules SET orderid=(orderid-1) WHERE orderid>=$old AND orderid<=$change"
    $sql="UPDATE Rules SET orderid=$change WHERE id="
}
*/


require_once "common.php";
isnewday(2); // man muss zumindest SU2 sein

page_header("Faq Editor");

// *** NAV
addnav("G?Zurück zur Grotte","superuser.php");
addnav("Optionen");
addnav("Übersicht","sufaq.php");
addnav("Regelsatz hinzufügen","sufaq.php?op=addset");
addnav("Regel hinzufügen","sufaq.php?op=addrule");

addnav("Regelsätze");

// *** NAV for general Ruleset-Edit ... + title for later use
$sql="SELECT * FROM ruleset ORDER BY `orderid` ASC";
$res=db_query($sql);
$enum_rid="";
$highestorder=0;
while($row=db_fetch_assoc($res))
{
    $url="sufaq.php?op=viewset&id=".$row['id'];
    addnav($row['id']."? {$row['title']}",$url);
    $enum.=",".$row['orderid'].",".$row['title'];
    $enum_rid.=",".$row['id'].",".$row['title'];
    $highestorder=$row['orderid'];
}
#$enum=substr($enum,0,strlen($enum)-1);
#$enum_rid=substr($enum_rid,0,strlen($enum_rid)-1);

// *** id of a ruleset
$id=(int)$_GET['id'];
// *** id of a rule (inside a ruleset)
$rid=(int)$_GET['rid'];
// *** id of a rule (inside a ruleset)
$version=(int)$_GET['version'];

// *** preparations for the Forms
$ruleset=array("Regelsatz: erstellen,title",
"orderid"=>"Reihenfolge: nach,enum".$enum,
"title"=>"Titel",
"subtitle"=>"Subtitel",
"creator"=>"Poster,viewonly"
);

$ruleset=array("Regelsatz: bearbeiten,title",
"orderid"=>"Reihenfolge: nach,enum".$enum,
"title"=>"Titel",
"subtitle"=>"Subtitel",
"creator"=>"Poster,viewonly"
);

$rule=array("Regel erstellen,title",
"rsid"=>"Regelsatz,enum".$enum_rid,
"orderid"=>"Reihenfolge,integer",
"title"=>"Titel",
"content"=>"Regel,textarea,60,7",
"variable"=>"Variabler Content,textarea,60,5",
"version"=>"Version,viewonly",
"creator"=>"Poster,viewonly",
"id"=>"ID,viewonly"
);

#rsid, integer (die ruleset-id) ... foreign key ruleset.id, primary key mit id
#id, integer (8) ... primary key mit rsid
#orderid, integer (8) ... (reihenfolge der Regeln innerhalb eines Regelsatzes)
#title, varchar (100) ... der Titel der Regel
#content, text .... (also der text, der regel ... datentyp text, nachdem ich nicht weiß wie lang er wird ^^)
#version, integer (8) .... ich will ne versionierung haben
#creator, integer (11) ... account-id von dem, ders geändert hat






// *** Funktionelle aufspaltung
if($_GET['op']=="addset")
{    //*** ADD Ruleset
    if($_GET['act']=="add")
    {
        $sql1="";
        $sql2="";
        $_POST['creator']=$session['user']['acctid'];
        while(list($k,$v)=each($_POST))
        {
            if($v!="")
            {
                $sql1.=$k.",";
                if($k=="orderid") $v++;
                if($k=="creator"||$k=="orderid")
                    $sql2.="".((int)$v).",";
                else
                    $sql2.="\"".$v."\",";
            }
        }
        $sql1=substr($sql1,0,strlen($sql1)-1);
        $sql2=substr($sql2,0,strlen($sql2)-1);

        $sql="INSERT INTO ruleset (".$sql1.") VALUES (".$sql2.");";

        if(db_query($sql))
        {
            output("Regel hinzugefügt!");
            $row=db_fetch_assoc(db_query("SELECT * FROM ruleset WHERE title=\"".$_POST['title']."\""));
        }
        else
            output("SQL: ".$sql);
    }
    else
    {
        $url="sufaq.php?op=addset&act=add";
        addnav("",$url);

        output("<form action=\"{$url}\" method=\"POST\">",true);
        showform($ruleset,NULL);
        output("</form>",true);


    }
}
elseif($_GET['op']=="delrule")
{
    $url="sufaq.php?op=viewset&id={$id}";
    addnav("",$url);


    $sql="DELETE FROM rules WHERE rsid={$id} AND id={$rid} AND version={$version}";
    if(db_query($sql)) output("Regel gelöscht!");

    output("`n`n<a href=\"{$url}\">zurück zum Regelsatz</a>",true);

}
elseif($_GET['op']=="addrule")
{    // *** ADD Rule to Ruleset
    if($_GET['act']=="add")
    {
        $sql1="";
        $sql2="";

        // if we edit, we need those 2 lines, that we can do versioning correct
        if($rid>0) $_POST['id']=$rid;
        if($version>0) $_POST['version']=$version;

        $_POST['creator']=$session['user']['acctid'];
        while(list($k,$v)=each($_POST))
        {

            if($k=="version" && $v>0)
                $v++;


            if($v!="")
            {
                $sql1.=$k.",";
                if($k=="variable")
                {
                    $v=str_replace("\r","\n",$v); // if mac or windows
                    $v=str_replace("\n\n","\n",$v); // if windows, and #1 happened ...

                    // Line Format == setting,##:a,b,c:text1(:text2)
                    //                    [key    ]:[v1 ]:[v2  ]:[v3]
                    $v=str_replace("\"","\\\"",$v);
                }
                if($k=="rsid"||$k=="id"||$k=="creator"||$k=="orderid")
                    $sql2.="".((int)$v).",";
                else
                    $sql2.="\"".$v."\",";
            }
        }
        $sql1=substr($sql1,0,strlen($sql1)-1);
        $sql2=substr($sql2,0,strlen($sql2)-1);

        $sql="INSERT INTO rules (".$sql1.") VALUES (".$sql2.");";

        if(db_query($sql))
        {
            output("Regel hinzugefügt!`n`n");
            output("{$sql}`n`n");


#            $row=db_fetch_assoc(db_query("SELECT * FROM rules WHERE title=\"".$_POST['title']."\""));

            $url1="sufaq.php?op=addrule&id=".$_POST['rsid'];
            addnav("",$url1);
            output("<a href=\"{$url1}\">weitere Regel hinzufügen</a>",true);

            $url2="sufaq.php?op=viewset&id=".$_POST['rsid'];
            addnav("",$url2);
            output("| <a href=\"{$url2}\">zurück zur Set-Übersicht</a>",true);

        }
        else
            output("SQL: ".$sql);
    }
    else
    {

        $url="sufaq.php?op=addrule&act=add";
        if($rid>0)
        {
            $sql="SELECT * FROM rules WHERE id={$rid} AND rsid={$id} AND version={$version};";
            $row=db_fetch_assoc(db_query($sql));
            $url.="&rid={$rid}&id={$id}&version={$version}";
        }
        elseif($id>0)
        {
            $row['rsid']=$id;
            $url.="&id={$id}";
        }
        else
            $row=NULL;


        addnav("",$url);
        output("<form action=\"{$url}\" method=\"POST\">",true);
        showform($rule,$row);
        output("</form>",true);
    }


        output("`n`n`bErläuterung`b:`n`iRegel`i == Text der Regel`n
        `iVariabler Content`i == Dynamischer Content mit Ersetzungseigenschaften aus dem Regel-Text`n`n
        Beispiel:`nRegeltext:`n
        Dies ist eine Regel mit Dynamischen Text. {{1}}`n`n
        Variabler Content:`n
        setting,1:multimaster,1,0:Du kannst deinen Meister nur einmal am Tag herausfordern`n`n
        Es wird getrennt mit :`n
        `i1. Element`i: sagt, dass von den Systemsettings genommen werden soll, und an welcher Stelle eingefügt werden soll (durchnummerierte Zahlen, oder du kannst es auch hugo nennen)`n
        `i2. Element`i: aufgetrennt ist es [dass es ein setting ist],[Ersetzungsnummer im Regeltext]:[settingname],[standardwert],[in welchem Fall der Text so angeteigt werden soll]`n
        `i3. Element`i: ist der Text und alternativtext`n
        `i4. Element`i: ein optionaler alternativer Text, der kommt, wenn der Fall nicht zutrifft (man muss es aber nicht setzen, dann kümmert sich das System um den rest ;D )
        `n`n
        nochmal zusammengefasst -- setting,##:a,b,c:text1(:text2)`n`n
        `bAchtung`b: Variabler content: pro Zeile nur 1 Setting/Var-Eintrag
        ");

}
elseif($_GET['op']=="viewset")
{ //*** VIEW Ruleset
    $sql="SELECT * FROM rules WHERE rsid={$id} ORDER BY `orderid` ASC";
    $res=db_query($sql);

    if(db_num_rows($res)==0)
    {
        $url1="sufaq.php?op=addrule&id=".$id;
        addnav("",$url1);

        output("Hm, anscheinend gibts grad keine Regeln.`n`nANARCHIE!!!`n`n`nNein, spaß.`n`n");
        output("<a href=\"{$url1}\">Regel hinzufügen</a>",true);

    }
    else
    {
        output("<table>",true);
            output("<tr>",true);
            output("<th>#.Version</th>",true);
            output("<th>Reihenfolge</th>",true);
            output("<th>Titel</th>",true);
#            output("<th>Sub-Titel</th>",true);
            output("<th>(acc-id) Ersteller</th>",true);
            output("<th>Optionen</th>",true);
            output("</tr>",true);

        $rn=0;
        while($row=db_fetch_assoc($res))
        {
            $rn++;
            $url="sufaq.php?op=addrule&id={$id}&rid=".$row['id']."&version=".$row['version'];
            $url1="sufaq.php?op=addrule&id={$id}";
            $url2="sufaq.php?op=delrule&id={$id}&rid=".$row['id']."&version=".$row['version'];


            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td rowspan=\"3\">".$row['rsid'].".".$row['id'].".".$row['version']."</td>",true);
            output("<td rowspan=\"3\">".$row['orderid']."</td>",true);
            output("<td>".$row['title']."</td>",true);
#            output("<td>".$row['subtitle']."</td>",true);
            $row2=db_fetch_assoc(db_query("SELECT login, name FROM accounts where acctid={$row['creator']};"));
            output("<td>(".$row['creator'].") ".(strlen($row2['name'])>0?$row2['name']:"gelöschter User")."</td>",true);
            output("<td>[<a href=\"{$url}\">bearbeiten</a>|<a href=\"{$url2}\">löschen</a> ][<a href=\"{$url1}\">Regel hinzufügen</a>]</td>",true);
            output("</tr>",true);

            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td colspan=\"3\">".$row['content']."</td>",true);
            output("</tr>",true);
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td colspan=\"3\">".$row['variable']."</td>",true);
            output("</tr>",true);

            addnav("",$url);
            addnav("",$url1);
            addnav("",$url2);
        }
        output("</table>",true);
    }


}
else
{ // *** VIEW List of Rulesets

    $sql="SELECT * FROM ruleset ORDER BY `orderid` ASC";
    $res=db_query($sql);

    if(db_num_rows($res)==0)
    {
        output("Hm, anscheinend gibts grad keine Regeln.`n`nANARCHIE!!!`n`n`nNein, spaß.");
    }
    else
    {
        output("<table>",true);
            output("<tr>",true);
            output("<th rowspan=\"2\">#</th>",true);
            output("<th rowspan=\"2\">Reihenfolge</th>",true);
            output("<th>Titel</th>",true);
            output("<th>(acc-id)</th>",true);
            output("<th rowspan=\"2\">Optionen</th>",true);
            output("</tr>",true);
            output("<tr>",true);
            output("<td>Sub-Titel</td>",true);
            output("<td>Ersteller</td>",true);
            output("</tr>",true);

        $rn=0;
        while($row=db_fetch_assoc($res))
        {
            $rn++;
            $url="sufaq.php?op=viewset&id=".$row['id'];
            $url1="sufaq.php?op=addrule&id=".$row['id'];
            $url2="sufaq.php?op=delset&id=".$row['id'];


            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td rowspan=\"2\">".$row['id'].".".$row['version']."</td>",true);
            output("<td rowspan=\"2\">".$row['orderid']."</td>",true);
            output("<td>".$row['title']."</td>",true);
            $row2=db_fetch_assoc(db_query("SELECT login, name FROM accounts where acctid={$row['creator']};"));
            output("<td>(".$row['creator'].")</td>",true);
            output("<td rowspan=\"2\">[<a href=\"{$url}\">bearbeiten</a>|<a href=\"{$url2}\">löschen</a> ][<a href=\"{$url1}\">Regel hinzufügen</a>]</td>",true);
            output("</tr>",true);

            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td style=\"padding-left:2em;\">".$row['subtitle']."</td>",true);
            output("<td>".(strlen($row2['name'])>0?$row2['name']:"gelöschter User")."</td>",true);
            output("</tr>",true);


            addnav("",$url);
            addnav("",$url1);
            addnav("",$url2);
        }
        output("</table>",true);
    }

}



page_footer();
?> 