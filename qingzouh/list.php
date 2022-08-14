
<?php

/* 


Plotbilder für die Einwohnerlist

Umsetzung: Aramus
Server Saint-Omar


*/



require_once "common.php";

output("
<style>
.head {
   background-color: #a26842;
   font-family: Times;
   font-size: 20px;
   color: #1e1919;
   text-align: center;
   letter-spacing: 0.1em;
}

.name {
   font-size:8px;
   font-weight:bold;
   letter-spacing: 0.1em;
   text-transform: uppercase;
   line-height:160%;
}

.work {
   font-family: arial;
   font-size:10px;
   letter-spacing: 0.1em;
   line-height:150%;
}
</style>", true);

if ($session['user']['loggedin']) {
 addnav("Gerade Online","list.php?ret=" . $_GET['ret'] . "");
    addnav("Zurück");
 $village = false;
 if ($session['user']['alive']) {
  if ($_GET['ret'] == 'village') {
   addnav("Qingzouh","village.php");
  } else if ($_GET['ret'] == 'eassos') {
   addnav("nach Eassos","eassos.php");
  } else if ($_GET['ret'] == 'deassos') {
   addnav("nach Dark Eassos","deassos.php");
  } else if ($_GET['ret'] == 'passkontrolle') {
   addnav("zur Passkontrolle","passkontrolle.php");
  } else if ($_GET['ret'] == 'carvo') {
   addnav("nach Carvo","carvo.php");
  } else if ($_GET['ret'] == 'baalos') {
   addnav("nach Baalos","baalos.php");
  } else if ($_GET['ret'] == 'barra') {
   addnav("nach Al Barra","barra.php");
  } else if ($_GET['ret'] == 'kryphton') {
   addnav("nach Kryphton","kryphton.php?op=platz");
  } else if ($_GET['ret'] == 'illy') {
   addnav("nach Illyorzzad","illy.php");
  } else if ($_GET['ret'] == 'zhul') {
   addnav("nach Karaz-a-Zhul","zhul.php");
  } else {
   $village = true;
  }
 } else {
  if ($_GET['ret'] == 'shades') {
   addnav("zu den Schatten","shades.php");
  }
 }

 if (!$_GET['ret'] || $_GET['ret'] == "" || $village)
  addnav("Qingzouh","village.php");

//addnav($text, $link = false, $priv = false, $pop = false, $newwin = false, $tier = 0)
        addnav("Sonstiges");
        addnav("Teamliste","list_adv.php?op=team&ret=" . $_GET['ret'] . "");
        //addnav("X-Char Übersicht","list_adv.php?op=npc&ret=" . $_GET['ret'] . "");
 addnav("Avatarliste","avatarlist.php?ret=" . $_GET['ret'] . "");
 
 


} else {
 addnav("Gerade Online","list.php");
    addnav("Zurück");
    addnav("zur Login Seite","index.php");
}

page_header("Einwohnerliste");

$session['user']['standort'] = "Bewohnerliste";



output("
<table border='0' align='center' cellspacing='0' cellpadding='0'>
<tr>
<td width='10'></td>",true);
$time_check = date("H");
if($time_check >= "07" AND $time_check <= "19"){
    output("<br><br><td align='center'><img src='images/quin/Ortebilder/list2.png' width='100%' height='100%'></td>",true);
}else{
    output("<br><br><td align='center'><img src='images/quin/Ortebilder/list2.png' width='100%' height='100%'></td>",true);
}
output("<td width='11'></td>
</tr>
</table>
`n

",true);

output("

<div class='name'>`cFolgende Bewohner aus Qingzouh sind anwesend!`n
        Hier findest du jeden, der in unserer schönen Welt lebt und kannst dich über ihren Status informieren.`n
        `0`c`n`n</div>", true);

$playersperpage = 30;

$sql             = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
$result             = db_query($sql);
$row             = db_fetch_assoc($result);
$totalplayers     = $row['c'];

if ($_GET['op'] == "search") {
    $search = "%";
    for ($x = 0; $x < strlen($_POST['name']); $x++) {
        $search .= substr($_POST['name'],$x,1) . "%";
    }
    $search = " AND name LIKE '" . addslashes($search) . "' ";
} else {
    $pageoffset     = (int) $_GET['page'];
    if ($pageoffset > 0)
        $pageoffset--;
    $pageoffset *= $playersperpage;
    $from         = $pageoffset + 1;
    $to             = min($pageoffset + $playersperpage,$totalplayers);

    $limit = " LIMIT $pageoffset,$playersperpage ";
}




addnav("Seiten");
for ($i = 0; $i < $totalplayers; $i += $playersperpage) {
    addnav("Seite " . ($i / $playersperpage + 1) . " (" . ($i + 1) . "-" . min($i + $playersperpage,$totalplayers) . ")","list.php?page=" . ($i / $playersperpage + 1) . "&ret=" . $_GET['ret'] . "");
}

// Order the list by level,dragonkills,name so that the ordering is total!
// Without this,some users would show up on multiple pages and some users
// wouldn't show up

if ($session['user']['prefs']['list_type']) {

    output("<link href='lib/styles/list_style.css' rel='stylesheet' type='text/css'>",true);

    if ($session['user']['loggedin']) {
        output("`c<form autocomplete='off' action='list.php?op=search&ret=" . $_GET['ret'] . "' method='POST'>Nach Name suchen: 
        <input list='names' name='name' placeholder='Spielername' required>
        <datalist id='names'>", true);
        
        $names_list_sql = 'SELECT name, acctid FROM accounts';
        $names_list_res = db_query($names_list_sql);
        while($names_list_row = db_fetch_assoc($names_list_res))
        {
            $names_list_name = stripColors($names_list_row['name']);
            rawoutput('<option value="'.$names_list_name.'">');
        }
        
        output("</datalist>
                <input type='submit' class='button' value='Suchen'>
        </form>`c`n`n",true);
        addnav("","list.php?op=search&ret=" . $_GET['ret'] . "");
    }

    output("<table border='0' cellpadding='0' cellspacing='0' align='center' id='bio'>",true);

    if ($_GET['page'] == "" && $_GET['op'] == "") {
        output("<tr class='trdark'><td colspan='9'><span class='title'>`c`b#c0c0c0 `b`c</span></td></tr>",true);
        $sql = "SELECT rp_char,is_plot_char,plotplayer,acctid,rpbulb,superuser,superuser2,name,login,alive,location,sex,level,laston,loggedin,lastip,uniqueid,race,urace,prefs,dragonkills,punch,standort,ghost_mode,ZMeister FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date("Y-m-d H:i:s",strtotime(date("c") . "-" . getsetting("LOGINTIMEOUT",900) . " seconds")) . "' AND ghost_mode!=1 
            ORDER BY 
                CASE superuser 
                 WHEN 0 THEN 0
                 WHEN 1 THEN 1
                 WHEN 2 THEN 2
                 WHEN 3 THEN 4
                 WHEN 4 THEN 3
                 WHEN 5 THEN 0
                 WHEN 6 THEN 0
                END DESC, 
                CASE rpbulb
                 WHEN 0 THEN 4
                 WHEN 1 THEN 0
                 WHEN 2 THEN 2
                 WHEN 3 THEN 1
                 WHEN 4 THEN 3
                END ASC,
                level DESC,dragonkills DESC,login ASC    
                ";
    } else {
        output("<tr class='trdark'><td colspan='9'><span class='title'>`c`bBewohner des Landes (Seite " . ($pageoffset / $playersperpage + 1) . ": $from-$to von $totalplayers)`b`c</span></td></tr>",true);
        $sql = "SELECT acctid,name,plotplayer,login,alive,rp_char,rpbulb,superuser,superuser2,location,sex,level,laston,loggedin,lastip,uniqueid,race,urace,prefs,standort,ghost_mode, ZMeister FROM accounts WHERE locked=0 $search ORDER BY level DESC,dragonkills DESC,login ASC $limit";
    }


    $result     = db_query($sql) or die(db_error(LINK));
    $max     = db_num_rows($result);
    if ($max > 100) {
        output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
    }

    for ($i = 0; $i < $max; $i++) {
        $row     = db_fetch_assoc($result);
        $prefs     = unserialize($row['prefs']);

        $sql_bio     = "SELECT avatar FROM bios WHERE acctid=" . $row['acctid'] . "";
        $result_bio     = db_query($sql_bio) or die(db_error(LINK));
        $row_bio     = db_fetch_assoc($result_bio);

        if ($row['rp_char'] == 0)
            $rp     = "Keine Klasse";
        if ($row['rp_char'] == 1)
            $rp     = "#ffffffR#fbc0c0P #f78181C#f78181h#b77c7ca#787878r`0";
        if ($row['rp_char'] == 2)
            $rp     = "#ffffffM#e1ccfbi#c49af8x #b681f7C#a17ecch#8c7ba2a#787878r`0";
        if ($row['rp_char'] == 3)
            $rp     = "#ffffffL#fafcdbe#f5fab7v#f1f893e#eff781l #d1d77eC#b3b77ch#95977aa#787878r`0";
        if ($row['rp_char'] == 4)
            $rp     = "`\$Spezielle-Charas";



        
if ($row['superuser'] == 0){
            $rank     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";

if ($row['sex'] == 1){
            $rank     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";

 }
}
            
if ($row['superuser'] == 0 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcä#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser'] == 0 && $row['sex'] == 1 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6ä#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser'] == 1){
            $rank     = "#787878M#8e9a8do#a5bda3d#bce0b9e#c8f2c4r#d2f5cfa#ddf8dat#e7fbe5o#f2fff0r`0";
if ($row['sex'] == 1){
            $rank     = "#787878M#899388o#9bae99d#adc9aae#bfe4bbr#c8f2c4a#d0f4cct#d8f7d5o#e1f9der#e9fce7i#f2fff0n`0";
 }
}
        
if ($row['superuser'] == 2){
            $rank     = "#787878D#91a0a0e#aac9c8s#c4f2f1i#c4f2f1g#d2f6f5n#e1faf9e#f0fffer`0";
                                           
if ($row['sex'] == 1){
                        $rank     = "#787878D#8b9696e#9eb5b4s#b1d3d2i#c4f2f1g#c4f2f1n#cff5f4e#daf8f7r#e5fbfai#f0fffen`0";
 }
}
        
if($row['superuser'] == 3){
                  $rank  = "#2e2e2eA#46474ed#5e606fm#767990i#8e92b1n#a6abd2i#b3b8e3s#bcc0e4t#c5c9e5r#cfd1e7a#d8dae8t#e1e2e9o#ebebebr`0";

if($row['sex'] == 1){
                        $rank  = "#cc99adA#cf9fb2d#d3a6b8m#d6adbdi#dab4c3n#ddbac8i#e1c1ces#e3c5d1t#e4cad4r#e5cfd8a#e6d5dct#e7dadfo#e8e0e3r#e9e5e7i#ebebebn`0";}


if($row['acctid'] == 1 OR $row['acctid'] == 2){
                        $rank  = "#f5f5f5S#f6edf0e#f8e5ecr#fadde7v#fcd5e3e#fecddfr#ffc9ddl#f7cee2e#f0d4e8i#e9daeet#e2dff3u#dbe5f9n#d4ebffg`0";


if($row['sex'] == 1){
                        $rank  = "#787878S#8e858be#a4939fr#baa1b2v#d0afc6e#e6bddar#f2c4e4l#f4cbe8e#f6d2edi#f8daf1t#fae1f6u#fce8fan#fff0ffg`0"; 
}
 }
   }



        
if ($row['superuser'] == 4){
            $rank     = "#787878A#9a918dr#bdaaa3c#e0c3b9h#f2d0c4i#f5dacft#f8e4dae#fbeee5k#fff9f0t`0";
if ($row['sex'] == 1){
            $rank     = "#787878A#938b88r#ae9f99c#c9b2aah#e4c6bbi#f2d0c4t#f4d8cce#f7e0d5k#f9e8det#fcf0e7i#fff9f0n`0";
 }
}
        
if ($row['superuser'] == 5){
            $rank     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";
if ($row['sex'] == 1){
            $rank     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";
 }
}

        //if ($row['superuser'] == 6)
        

    
        
        /*
          if ($row['superuser'] == 5) $rank = "`PBerater`0";
          if ($row['superuser'] == 6) $rank = "`6Kreativler`0";
          if ($row['superuser'] == 7) $rank = "`ÒHerrscher`0";
          if ($row['superuser'] == 8) $rank = "`9Programmierer`0";
          if ($row['superuser'] == 9) $rank = "`@Bürger`0";
         */




if ($row['superuser2'] == 0){
            $rank2     = "";

if ($row['sex'] == 1){
            $rank2     = "";
 }
}
            
if ($row['superuser2'] == 0 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcä#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser2'] == 0 && $row['sex'] == 1 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6ä#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser2'] == 1){
            $rank2     = "#787878M#8e9a8do#a5bda3d#bce0b9e#c8f2c4r#d2f5cfa#ddf8dat#e7fbe5o#f2fff0r`0";
if ($row['sex'] == 1){
            $rank2     = "#787878M#899388o#9bae99d#adc9aae#bfe4bbr#c8f2c4a#d0f4cct#d8f7d5o#e1f9der#e9fce7i#f2fff0n`0";
 }
}
        
if ($row['superuser2'] == 2){
            $rank2     = "#787878D#91a0a0e#aac9c8s#c4f2f1i#c4f2f1g#d2f6f5n#e1faf9e#f0fffer`0";
                                           
if ($row['sex'] == 1){
                        $rank2     = "#787878D#8b9696e#9eb5b4s#b1d3d2i#c4f2f1g#c4f2f1n#cff5f4e#daf8f7r#e5fbfai#f0fffen`0";
 }
}
        
if($row['superuser2'] == 3){
                  $rank2  = "#2e2e2eA#46474ed#5e606fm#767990i#8e92b1n#a6abd2i#b3b8e3s#bcc0e4t#c5c9e5r#cfd1e7a#d8dae8t#e1e2e9o#ebebebr`0";

if($row['sex'] == 1){
                        $rank2  = "#cc99adA#cf9fb2d#d3a6b8m#d6adbdi#dab4c3n#ddbac8i#e1c1ces#e3c5d1t#e4cad4r#e5cfd8a#e6d5dct#e7dadfo#e8e0e3r#e9e5e7i#ebebebn`0";}


if($row['acctid'] == 1 OR $row['acctid'] == 2){
                        $rank2  = "#f5f5f5S#f6edf0e#f8e5ecr#fadde7v#fcd5e3e#fecddfr#ffc9ddl#f7cee2e#f0d4e8i#e9daeet#e2dff3u#dbe5f9n#d4ebffg`0";


if($row['sex'] == 1){
                        $rank2  = "#787878S#8e858be#a4939fr#baa1b2v#d0afc6e#e6bddar#f2c4e4l#f4cbe8e#f6d2edi#f8daf1t#fae1f6u#fce8fan#fff0ffg`0"; 
}
 }
   }



        
if ($row['superuser2'] == 4){
            $rank2     = "#787878A#9a918dr#bdaaa3c#e0c3b9h#f2d0c4i#f5dacft#f8e4dae#fbeee5k#fff9f0t`0";
if ($row['sex'] == 1){
            $rank2     = "#787878A#938b88r#ae9f99c#c9b2aah#e4c6bbi#f2d0c4t#f4d8cce#f7e0d5k#f9e8det#fcf0e7i#fff9f0n`0";
 }
}
        
if ($row['superuser2'] == 5){
            $rank2     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";
if ($row['sex'] == 1){
            $rank2     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";
 }
}

    //    if ($row['superuser2'] == 6)
    //        $rank2     = "`uZeremonienmeister`0";
        /*
          if ($row['superuser2'] == 5) $rank2 = "`PBerater`0";
          if ($row['superuser2'] == 6) $rank2 = "`6Kreativler`0";
          if ($row['superuser2'] == 7) $rank2 = "`ÒHerrscher`0";
          if ($row['superuser2'] == 8) $rank2 = "`9Programmierer`0";
         */


        if ($row['rpbulb'] == 0)
            $rpbulb     = "<img src='images/Assos/bulb0.png' alt='Kein Interesse am RP' titel='Kein Interesse am RP'>";
        if ($row['rpbulb'] == 1)
            $rpbulb     = "<img src='images/Assos/bulb1.png' alt='Bin bereit zum RPn' titel='Bin bereit zum RPn'>";
        if ($row['rpbulb'] == 2)
            $rpbulb     = "<img src='images/Assos/bulb2.png' alt='Im RP - Weitere Partner erwünscht' titel='Im RP - Weitere Partner erwünscht'>";
        if ($row['rpbulb'] == 3)
            $rpbulb     = "<img src='images/Assos/bulb3.png' alt='Suche späteren RP-Partner' titel='Suche späteren RP-Partner'>";
        if ($row['rpbulb'] == 4)
            $rpbulb     = "<img src='images/Assos/bulb4.png' alt='Derzeit in einem RP' titel='Derzeit in einem RP'>";

        output("<tr class='bio_bg'><td>",true);

        output("<div class='table'>",true);
        output("<table border='0' width='100%' style='max-width: 600px;' class='biotable'><tr><td width='110px' rowspan='8'>",true);

        output("<div class='avatar'>",true);
        if ($row_bio['avatar']) {
            if ($session['user']['loggedin']) {
                output("<a href=\"popup_bio.php?char=" . rawurlencode($row['login']) . "\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\"> <img src='" . $row_bio['avatar'] . "' border='0' width='100' alt='" . preg_replace("/#[0-9a-f]{6}|[`]./i","",$row['name']) . "' /> </a>",true);
            } else {
                output("<img src='" . $row_bio['avatar'] . "' border='1' width='100' alt='" . preg_replace("/#[0-9a-f]{6}|[`]./i","",$row['name']) . "' />",true);
            }
        } else {
            output("kein Avatar",true);
        }
        output("</div>",true);

        output("</td></tr><tr><td colspan='2'>",true);

        output("<div class='name'>",true);
        if ($session['user']['loggedin']) {
            output("<a href=\"popup_mail.php?op=write&to=" . rawurlencode($row['login']) . "\" target=\"_blank\" title=\"Nachricht senden\" onClick=\"" . popup("popup_mail.php?op=write&to=" . rawurlencode($row['login']) . "") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
            output("<a href=\"popup_bio.php?char=" . rawurlencode($row['login']) . "\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">" . $row['name'] . "</a>",true);
            output($row['sex'] ? "<img src=\"images/system/common/female.gif\">" : "<img src=\"images/system/common/male.gif\">",true);

            if ($session['user']['superuser'] > 0 && $session['user']['superuser'] < 6) {
                output("<span class='admin'>",true);
                output("[<a href='su_repnav.php?char=" . rawurlencode($row['login']) . "&ret=" . $_GET['ret'] . "'>Fix Nav</a>] ",true);
                addnav("","su_repnav.php?char=" . rawurlencode($row['login']) . "&ret=" . $_GET['ret']);

                if ($session['user']['loggedin']) {
                    $rights_sql     = 'SELECT editor_blocks FROM su_rights WHERE su_id = ' . $session['user']['acctid'];
                    $rights_res     = db_query($rights_sql);

                                      if (db_num_rows($rights_res) == 1) {
                                       $blockedUsers = array(7162);
                                       $allowUser = true;
                                       if(in_array($session['user']['acctid'], $blockedUsers)) $allowUser = false;

                    if (db_num_rows($rights_res) == 1) {
                        $rights_row = db_fetch_assoc($rights_res);

                        if (!empty($rights_row['editor_blocks']) && $allowUser) {
                            output(' [<a href="su_user.php?op=edit&userid=' . $row['acctid'] . '&ret=list">Edit</a>]',true);
                            addnav('','su_user.php?op=edit&userid=' . $row['acctid'] . '&ret=list');
                        }
                    }
                }
                         }

            }    output("</div>",true);
            
         }else {
            output($row['name'], true);
        }

        
        output("</div>",true);

        output("</td></tr><tr><td>",true);

        output("<div class='race'>",true);
        output("`iRasse`i: " . $row['race']);
        output("</div>",true);

        output("<div class='urace'>",true);
        output("`iSpezifizierung`i: " . $row['urace']);
        output("</div>",true);

        output("</td><td width='200px' rowspan='4'>",true);

        output("<div class='bulb'>",true);
        output($rpbulb,true);
        output("</div>",true);

        output("</td></tr><tr><td>",true);
        
        /*output('<div class="char_plot">
                `iPlot-Char?`i ', true);
        switch($row['is_plot_char'])
        {
            case 0:
                output('`7Ungewiss`0.');
                break;
            case 1:
                output('#0066ffNein`0.');
                break;
            case 2:
                output('#33cc33Ja`0.');
                break;
        }
        output('</div>', true);*/
        
        output("<div class='char_type'>",true);
        output("`iChar-Klasse`i: " . $rp);
        output("</div>",true);

        output("</td></tr><tr><td>",true);

        output("<div class='amt'>",true);
        output("`iAmt`i: " . $rank . " " . ($rank2 ? '`&(' . $rank2 . '`&)' : ''),true);
        output("</div>",true);

        output("</td></tr><tr><td>",true);

        output("<div class='alive'>",true);
        output("`iStatus`i: ");
        output($row['alive'] ? "`2Lebt`0" : "`4Tot`0");
        output("</div>",true);

        output("</td></tr><tr><td>",true);

        output("<div class='laston'>",true);
        $loggedin     = (date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",3600) && $row['loggedin']);
        $laston         = round((strtotime(date("c")) - strtotime($row['laston'])) / 86400,0) . " Tage";
        if (substr($laston,0,2) == "1 ")
            $laston         = "`$1 Tag";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d"))
            $laston         = "`6Heute";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("c") . "-1 day")))
            $laston         = "`6Gestern";
        if ($loggedin && $row['ghost_mode'] == 0) {
            $timeDelta = round((strtotime(date("c")) - strtotime($row['laston'])) / 60,0);
            if ($timeDelta == 0) {
                $laston = "gerade eben.";
            } else {
                $laston = "vor " . $timeDelta . " Minute" . ($timeDelta > 1 ? 'n' : '') . ".";
            }
        } else if ($loggedin && $row['ghost_mode'] == 1) {
            $laston = "`6Heute";
        }
        output("`iZuletzt da`i: " . $laston);
        output("</div>",true);

        output("</td><td>`c",true);
        output("<div class='places'>",true);

        $place = $row['standort'];
        if ($place == "Bei der Arbeit" && $row['superuser'] == 5) {
            $place = "Bewohnerliste";
        }
        output("`iOrt`i: ");
        output($place);
        output("</div>",true);

        output("`c</td></tr>",true);
        output("<tr><td colspan='2'>",true);

        output("<div class='info'>",true);
        if ($prefs['info2'] == "1") {
            output("`7`iInfo`i: " . html_entity_decode(stripslashes($prefs['info'])) . "`0");
        }
        output("</div>",true);

        output("</td></tr></table>",true);

        output("</div>",true);

        output("</td></tr>",true);
    }
    output("</table>",true);
} else {
    output("<script type=\"text/javascript\" src=\"templates/common/wz_tooltip.js\"></script>",true);

    if ($_GET['page'] == "" && $_GET['op'] == "") {
        output("`c`bDiese Bewohner sind gerade online`b`c");
        $sql = "SELECT is_plot_char,acctid,plotplayer,name,login,alive,location,sex,laston,loggedin,lastip,uniqueid,race,urace,standort,rpbulb,prefs,rp_char,punch,dragonkills,c_memberid,superuser,superuser2,ghost_mode, ZMeister FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date("Y-m-d H:i:s",strtotime(date("c") . "-" . getsetting("LOGINTIMEOUT",900) . " seconds")) . "' AND ghost_mode!=1 ORDER BY CASE superuser 
 WHEN 0 THEN 0
 WHEN 1 THEN 1
 WHEN 2 THEN 2
 WHEN 3 THEN 3
 WHEN 4 THEN 4
 WHEN 5 THEN 0
END DESC,level DESC,dragonkills DESC,login ASC";
    } else {
        output("`c`bBewohner in dieser Welt (Seite " . ($pageoffset / $playersperpage + 1) . ": $from-$to von $totalplayers)`b`c");
        $sql = "SELECT acctid,plotplayer,name,login,alive,location,sex,laston,loggedin,lastip,uniqueid,race,urace,standort,rpbulb,prefs,rp_char,punch,dragonkills,c_memberid,superuser,superuser2,ghost_mode, ZMeister FROM accounts WHERE locked=0 $search ORDER BY CASE superuser 
 WHEN 0 THEN 0
 WHEN 1 THEN 1
 WHEN 2 THEN 2
 WHEN 3 THEN 3
 WHEN 4 THEN 4
 WHEN 5 THEN 0
END DESC, level DESC,dragonkills DESC,login ASC $limit";
    }
    if ($session['user']['loggedin']) {
        output("`n`c<form action='list.php?op=search&ret=" . $_GET['ret'] . "' method='POST'>Nach Name suchen: <input name='name'> <input type='submit' class='button' value='Suchen'></form>`c`n",true);
        addnav("","list.php?op=search&ret=" . $_GET['ret'] . "");
    }

    $result     = db_query($sql) or die(sql_error($sql));
    $max     = db_num_rows($result);
    if ($max > 100) {
        output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
    
}
    output("<table border='0' align='center' cellpadding='2' cellspacing='1'>",true);
    output("<tr class='trhead' align='center'><td><b>Name</b></td><td><b>Rasse</b></td><td><b>Spezifikation</b></td><td><b>Char-Klasse</b></td><td><b><img src=\"images/system/common/female.gif\">/<img src=\"images/system/common/male.gif\"></b></td><td><b>Amt</b></td><td><b>Ort</b></td><td><b>RP-Bereit?</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></td>",true);
    if ($session['user']['superuser'] > 0)
        output("<td><b>Navs</b></td><td><b>Ops</b></td>",true);
    output("</tr>",true);

    for ($i = 0; $i < $max; $i++) {
        $row     = db_fetch_assoc($result);
        $prefs     = unserialize($row['prefs']);

        if ($row['c_memberid'] > 0) {
            $sql_clan     = "SELECT clanid,clanname FROM clans WHERE clanid=" . $row['c_memberid'] . "";
            $result_clan = db_query($sql_clan) or die(sql_error(LINK));
            $row_clan     = db_fetch_assoc($result_clan);


            if ($session['user']['loggedin']) {
                $clan     = "<a href='popup_showdetail.php?op=clan&id=" . $row_clan['clanid'] . "' target='window_popup' onClick=\"" . popup_edit("popup_showdetail.php?op=clan&id=" . $row_clan['clanid']) . "; return false;\">`&" . $row_clan['clanname'] . "`0</a>";
                $charbio = "<a href=\"popup_bio.php?char=" . rawurlencode($row['login']) . "\"  onmouseover=\"TagToTip('" . $row['acctid'] . "',TITLEBGCOLOR,'#550000',BGCOLOR,'#050505',FONTCOLOR,'#EEEEEE' ,BORDERWIDTH,2,BORDERCOLOR,'#880000',TITLE,'Kurzinformationen über " . preg_replace("/#[a-f0-9]{6}|[`]./i","",$row['name']) . "`0')\" onmouseout=\"UnTip()\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">" . appoencode($row['name']) . " " . $clan . "`0</a>";
            } else {
                $clan     = $row_clan['clanname'];
                $charbio = $row['name'] . " " . $clan . "`0";
            }
        } else {
            if ($session['user']['loggedin']) {
                $charbio = "<a href='popup_bio.php?char=" . rawurlencode($row['login']) . "'  onmouseover=\"TagToTip('" . $row['acctid'] . "',TITLEBGCOLOR,'#550000',BGCOLOR,'#050505',FONTCOLOR,'#EEEEEE' ,BORDERWIDTH,2,BORDERCOLOR,'#880000',TITLE,'Kurzinformationen über " . preg_replace("/#[a-f0-9]{6}|[`]./i","",$row['name']) . "`0')\" onmouseout=\"UnTip()\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">" . appoencode($row['name'], true) . "`0</a>";
            } else {
                $charbio = $row['name'] . "`0";
            }
        }
        
        /*switch($row['is_plot_char'])
        {
            case 0:
                $plot_char = '`7Ungewiss';
                break;
            case 1:
                $plot_char = '#0066ffNein.';
                break;
            case 2:
                $plot_char = '#33cc33Ja.';
                break;
        }*/

        if ($row['rp_char'] == 0)
            $rp     = "Keine Klasse";
        if ($row['rp_char'] == 1)
            $rp     = "#ffffffR#ffedeaP #ffdcd5C#ffcbc0h#ffbaaba#ffb2a1r#e4a698a#c99a90k#ae8f88t#938380e#787878r`0";
        if ($row['rp_char'] == 2)
            $rp     = "#ffffffM#f5f0ffi#ece1ffx #e2d2ffC#d9c3ffh#d0b5ffa#d0b5ffr#bea8e4a#ac9cc9k#9b90aet#898493e#787878r`0";
        if ($row['rp_char'] == 3)
            $rp     = "#ffffffL#fcfdeae#f9fcd5v#f7fbc0e#f4f9abl #f1f896C#eff781h#eff781a#dbe17fr#c7cc7ea#b3b77ck#9fa27bt#8b8d79e#787878r`0";
        if ($row['rp_char'] == 4)
            $rp     = "`\$Spezielle-Charas";


        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            if ($session['user']['loggedin']) {
                output("<a href=\"popup_mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" title=\"Nachricht senden\" onClick=\"".popup("popup_mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
                output($charbio,true);
            


            $sql_bio     = "SELECT avatar FROM bios WHERE acctid=" . $row['acctid'] . "";
            $result_bio     = db_query($sql_bio) or die(sql_error($sql_bio));
            $row_bio     = db_fetch_assoc($result_bio); 

            /*                 if ($row_bio['avatar'] != '') {
              $table_width = 100;

              $ava_pic = img_resize($row_bio['avatar'],100,1);
              if ($ava_pic != '') {
              $ava = img_resize($row_bio['avatar'],100,1)." border=\"2\" alt=\"".preg_replace("/[`]./","",$row['name'])."\">";
              } else {
              $ava = "<img src='' border=\"2\" alt=\"Bildfehler\">";
              }
              } else {
              $table_width = 100;
              $ava = "Kein Avatar";
              }
             */
            if ($row_bio['avatar']) {
                $ava = "<img src='" . $row_bio['avatar'] . "' border='1' width='100' alt='" . preg_replace("/#[0-9a-f]{6}|[`]./i","",$row['name']) . "' />";
            } else {
                $ava = "kein Avatar";
            }
            

            output("<span id=" . $row['acctid'] . ">
                            <table>
                                <tr>
                                    <td width=\"" . $table_width . "\">`c" . $ava . "`c</td>
                                    <td width=\"200\">Charklasse: " . $rp . "<br />`0Rasse: " . $row['race'] . "<br />`0Spezifizierung: " . $row['urace'] . "<br />`0Bester Angriff: " . $row['punch'] . "<br />Drachenkills: " . $row['dragonkills'] . "</td>
                                </tr>
                            </table>
                        </span>",true);
            } else {
                output($charbio,true);
            }

        if ($prefs['info2'] == "1") {
            output("`n`7Info: " . stripslashes($prefs['info']) . "`0");
        }


        output("</td><td align=\"center\">",true);
        output($row['race']);
        output("</td><td align=\"center\">",true);
        output($row['urace']);
    //    output("</td><td align=\"center\">",true);
        //output($plot_char);
        output("</td><td align=\"center\">",true);
        output($rp);
        output("</td><td align=\"center\">",true);
        output($row['sex'] ? "<img src=\"images/system/common/female.gif\">" : "<img src=\"images/system/common/male.gif\">",true);
        output("</td><td align=\"center\">",true);

        /* if ($row['superuser'] == 0) $rank = "`@Bürger`0";
          if ($row['superuser'] == 1) $rank = "`@Bürger `Z(Eventler)`0";
          if ($row['superuser'] == 2) $rank = "`@Bürger `m(Priester)`0";

          if ($row['superuser'] == 3) $rank = "`!Lehrer`0";
          if ($row['superuser'] == 4) $rank = "`èRichter`0";
          if ($row['superuser'] == 5) $rank = "`^Berater`0";
          if ($row['superuser'] == 6) $rank = "`PKreativler`0";
          if ($row['superuser'] == 7) $rank = "`ÒHerrscher`0";
          if ($row['superuser'] == 8) $rank = "`9Programmierer`0";

          if ($row['superuser2'] == 0) $rank2 = "";
          if ($row['superuser2'] == 1) $rank2 = "`ZEventler`0";
          if ($row['superuser2'] == 2) $rank2 = "`mPriester`0";

          if ($row['superuser2'] == 3) $rank2 = "`!Lehrer`0";
          if ($row['superuser2'] == 4) $rank2 = "`èRichter`0";
          if ($row['superuser2'] == 5) $rank2 = "`PBerater`0";
          if ($row['superuser2'] == 6) $rank2 = "`6Kreativler`0";
          if ($row['superuser2'] == 7) $rank2 = "`ÒHerrscher`0";
          if ($row['superuser2'] == 8) $rank2 = "`9Programmierer`0";
         */


if ($row['superuser'] == 0){
            $rank     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";

if ($row['sex'] == 1){
            $rank     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";

 }
}
            
if ($row['superuser'] == 0 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcä#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser'] == 0 && $row['sex'] == 1 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6ä#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser'] == 1){
            $rank     = "#787878M#8e9a8do#a5bda3d#bce0b9e#c8f2c4r#d2f5cfa#ddf8dat#e7fbe5o#f2fff0r`0";
if ($row['sex'] == 1){
            $rank     = "#787878M#899388o#9bae99d#adc9aae#bfe4bbr#c8f2c4a#d0f4cct#d8f7d5o#e1f9der#e9fce7i#f2fff0n`0";
 }
}
        
if ($row['superuser'] == 2){
            $rank     = "#787878D#91a0a0e#aac9c8s#c4f2f1i#c4f2f1g#d2f6f5n#e1faf9e#f0fffer`0";
                                           
if ($row['sex'] == 1){
                        $rank     = "#787878D#8b9696e#9eb5b4s#b1d3d2i#c4f2f1g#c4f2f1n#cff5f4e#daf8f7r#e5fbfai#f0fffen`0";
 }
}
        
if($row['superuser'] == 3){
                  $rank  = "#2e2e2eA#46474ed#5e606fm#767990i#8e92b1n#a6abd2i#b3b8e3s#bcc0e4t#c5c9e5r#cfd1e7a#d8dae8t#e1e2e9o#ebebebr`0";

if($row['sex'] == 1){
                        $rank  = "#cc99adA#cf9fb2d#d3a6b8m#d6adbdi#dab4c3n#ddbac8i#e1c1ces#e3c5d1t#e4cad4r#e5cfd8a#e6d5dct#e7dadfo#e8e0e3r#e9e5e7i#ebebebn`0";}


if($row['acctid'] == 1 OR $row['acctid'] == 2){
                        $rank  = "#f5f5f5S#f6edf0e#f8e5ecr#fadde7v#fcd5e3e#fecddfr#ffc9ddl#f7cee2e#f0d4e8i#e9daeet#e2dff3u#dbe5f9n#d4ebffg`0";


if($row['sex'] == 1){
                        $rank  = "#787878S#8e858be#a4939fr#baa1b2v#d0afc6e#e6bddar#f2c4e4l#f4cbe8e#f6d2edi#f8daf1t#fae1f6u#fce8fan#fff0ffg`0"; 
}
 }
   }



        
if ($row['superuser'] == 4){
            $rank     = "#787878A#9a918dr#bdaaa3c#e0c3b9h#f2d0c4i#f5dacft#f8e4dae#fbeee5k#fff9f0t`0";
if ($row['sex'] == 1){
            $rank     = "#787878A#938b88r#ae9f99c#c9b2aah#e4c6bbi#f2d0c4t#f4d8cce#f7e0d5k#f9e8det#fcf0e7i#fff9f0n`0";
 }
}
        
if ($row['superuser'] == 5){
            $rank     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";
if ($row['sex'] == 1){
            $rank     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";
 }
}
        /*if ($row['superuser'] == 6)
            $rank     = "`uZeremonienmeister`0";
            */ 











if ($row['superuser2'] == 0){
            $rank2     = "";

if ($row['sex'] == 1){
            $rank2     = "";
 }
}
            
if ($row['superuser2'] == 0 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcä#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser2'] == 0 && $row['sex'] == 1 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6ä#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser2'] == 1){
            $rank2     = "#787878M#8e9a8do#a5bda3d#bce0b9e#c8f2c4r#d2f5cfa#ddf8dat#e7fbe5o#f2fff0r`0";
if ($row['sex'] == 1){
            $rank2     = "#787878M#899388o#9bae99d#adc9aae#bfe4bbr#c8f2c4a#d0f4cct#d8f7d5o#e1f9der#e9fce7i#f2fff0n`0";
 }
}
        
if ($row['superuser2'] == 2){
            $rank2     = "#787878D#91a0a0e#aac9c8s#c4f2f1i#c4f2f1g#d2f6f5n#e1faf9e#f0fffer`0";
                                           
if ($row['sex'] == 1){
                        $rank2     = "#787878D#8b9696e#9eb5b4s#b1d3d2i#c4f2f1g#c4f2f1n#cff5f4e#daf8f7r#e5fbfai#f0fffen`0";
 }
}
        
if($row['superuser2'] == 3){
                  $rank2  = "#2e2e2eA#46474ed#5e606fm#767990i#8e92b1n#a6abd2i#b3b8e3s#bcc0e4t#c5c9e5r#cfd1e7a#d8dae8t#e1e2e9o#ebebebr`0";

if($row['sex'] == 1){
                        $rank2  = "#cc99adA#cf9fb2d#d3a6b8m#d6adbdi#dab4c3n#ddbac8i#e1c1ces#e3c5d1t#e4cad4r#e5cfd8a#e6d5dct#e7dadfo#e8e0e3r#e9e5e7i#ebebebn`0";}


if($row['acctid'] == 1 OR $row['acctid'] == 2){
                        $rank2  = "#f5f5f5S#f6edf0e#f8e5ecr#fadde7v#fcd5e3e#fecddfr#ffc9ddl#f7cee2e#f0d4e8i#e9daeet#e2dff3u#dbe5f9n#d4ebffg`0";


if($row['sex'] == 1){
                        $rank2  = "#787878S#8e858be#a4939fr#baa1b2v#d0afc6e#e6bddar#f2c4e4l#f4cbe8e#f6d2edi#f8daf1t#fae1f6u#fce8fan#fff0ffg`0"; 
}
 }
   }



        
if ($row['superuser2'] == 4){
            $rank2     = "#787878A#9a918dr#bdaaa3c#e0c3b9h#f2d0c4i#f5dacft#f8e4dae#fbeee5k#fff9f0t`0";
if ($row['sex'] == 1){
            $rank2     = "#787878A#938b88r#ae9f99c#c9b2aah#e4c6bbi#f2d0c4t#f4d8cce#f7e0d5k#f9e8det#fcf0e7i#fff9f0n`0";
 }
}
        
if ($row['superuser2'] == 5){
            $rank2     = "#787878E#929292i#acacacn#c6c6c6w#d4d4d4o#dededeh#e9e9e9n#f4f4f4e#ffffffr`0";
if ($row['sex'] == 1){
            $rank2     = "#787878E#8c8c8ci#a0a0a0n#b5b5b5w#c9c9c9o#d4d4d4h#dcdcdcn#e5e5e5e#edededr#f6f6f6i#ffffffn`0";
 }
}
        /*if ($row['superuser2'] == 6)
            $rank2     = "`uZeremonienmeister`0";
            */






        output($rank . ' ' . ($rank2 ? '`&(' . $rank2 . '`&)' : ''));
        output("</td><td>",true);
        output("`c" . $row['standort'] . "`c");
        output("</td><td>",true);
        output($row['alive'] ? "`1`c`bLebt`b`c`0" : "`4`c`bTot`b`c`0");
        output("</td><td align=\"center\">",true);
        if ($row['rpbulb'] == 0)
            $rpbulb     = "<img src='images/Assos/bulb0.png' alt='Kein Interesse am RP' titel='Kein Interesse am RP'>";
        if ($row['rpbulb'] == 1)
            $rpbulb     = "<img src='images/Assos/bulb1.png' alt='Bin bereit zum RPn' titel='Bin bereit zum RPn'>";
        if ($row['rpbulb'] == 2)
            $rpbulb     = "<img src='images/Assos/bulb2.png' alt='Im RP - Weitere Partner erwünscht' titel='Im RP - Weitere Partner erwünscht'>";
        if ($row['rpbulb'] == 3)
            $rpbulb     = "<img src='images/Assos/bulb3.png' alt='Suche späteren RP-Partner' titel='Suche späteren RP-Partner'>";
        if ($row['rpbulb'] == 4)
            $rpbulb     = "<img src='images/Assos/bulb4.png' alt='Derzeit in einem RP' titel='Derzeit in einem RP'>";

        output($rpbulb,true);
        output("</td><td>",true);
        $loggedin     = (date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",3600) && $row['loggedin']);
        $laston         = round((strtotime(date("c")) - strtotime($row['laston'])) / 86400,0) . " Tage";
        if (substr($laston,0,2) == "1 ")
            $laston         = "1 Tag";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d"))
            $laston         = "Heute";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("c") . "-1 day")))
            $laston         = "Gestern";
        if ($loggedin && $row['ghost_mode'] == 0) {
            $timeDelta = round((strtotime(date("c")) - strtotime($row['laston'])) / 60,0);
            if ($timeDelta == 0) {
                $laston = "gerade eben";
            } else {
                $laston = "vor " . $timeDelta . " Minute" . ($timeDelta > 1 ? 'n' : '') . "";
            }
        } else if ($loggedin && $row['ghost_mode'] == 1) {
            $laston = "Heute";
        }
output("`c" . $laston . "`c");
output("</td>",true);
if ($session['user']['superuser'] > 0) {
 output("<td align=\"center\"><a href='su_repnav.php?char=" . rawurlencode($row['login']) . "&ret=" . $_GET['ret'] . "'>Rep</a>",true);
 addnav("","su_repnav.php?char=" . rawurlencode($row['login']) . "&ret=" . $_GET['ret'] . "");

 if ($session['user']['loggedin']) {
  $rights_sql  = 'SELECT editor_blocks FROM su_rights WHERE su_id = ' . $session['user']['acctid'];
  $rights_res  = db_query($rights_sql);

  if (db_num_rows($rights_res) == 1) {
    $blockedUsers = array();
    $allowUser = true;
    if(in_array($session['user']['acctid'], $blockedUsers)) $allowUser = false;

   $rights_row = db_fetch_assoc($rights_res);

   if (!empty($rights_row['editor_blocks']) && $allowUser) {
    output('<td align="center"><a href="su_user.php?op=edit&userid=' . $row['acctid'] . '&ret=list">Edit</a>',true);
    addnav('','su_user.php?op=edit&userid=' . $row['acctid'] . '&ret=list');
   }
  }
 }

 rawoutput('</td>');
}
output("</tr>",true);
    }
    output("</table>`n`n`n`n`n",true);
}


output("`n`n`n`n`n
<table border='0' align='center' cellspacing='1' cellpadding='1' bgcolor='#1A1216'>
    <table border='0' align='center' cellspacing='2' cellpadding='2' bgcolor='#1A1216'>
    <tr><td>
      <table border='0' cellspacing='3' cellpadding='3' bgcolor='#090707'>
        <tr>
            <td colspan='6'><font size='2pt'>`b`cLegende:`c`b`0</font></td>
        </tr>
        <tr>
                     <td><img src='images/Assos/bulb0.png'></td><td>`b&raquo; Derzeit kein RP möglich.`b</td>
            <td><img src='images/Assos/bulb1.png'></td><td>`b&raquo; Ich bin sofort startklar! Frag mich!!`b</td>
            <td><img src='images/Assos/bulb4.png'></td><td>`b&raquo; Ich bin bereits in einem Play.`b</td>            
            <td>&nbsp</td><td>&nbsp</td>
        </tr>
        <tr><center>
                        <td><img src='images/Assos/bulb2.png'></td><td>`b&raquo; In mein Spiel darf sich jeder reinschreiben!`b</td>
            <td><img src='images/Assos/bulb3.png'></td><td>`b&raquo; Suche für später einen RP-Partner.`b</td></center>


        </tr>
      </table>
    </td></tr>
</table></table>",true);

 if($session['user']['loggedin']) 
 {
    if($session['user']['prefs']['otset'] == 0 OR $session['user']['prefs']['otset'] ==2 ) 
    {
        if($session['user']['ooc_lock'] == 0)
        {
            addcommentary();

            viewcommentary("ooc","Plaudern:`0");
        }
        else 
        {
            output("`b`c`n`n`n`n`\$Du hast momentan keine Berechtigung, den OOC zu betreten!`c`b");
        }
        
    }
    
    
    checkday();
}


page_footer();

?>

