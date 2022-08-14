
<?php

/* 


Plotbilder fÃ¼r die Einwohnerlist

Umsetzung: Aramus
Server Saint-Omar


*/



require_once "common.php";

if ($session['user']['loggedin']) {
 addnav("Gerade Online","list.php?ret=" . $_GET['ret'] . "");
    addnav("ZurÃ¼ck");
 $village = false;
 if ($session['user']['alive']) {
  if ($_GET['ret'] == 'village') {
   addnav("Jackson Square","village.php");
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
  addnav("Jackson Square","village.php");

//addnav($text, $link = false, $priv = false, $pop = false, $newwin = false, $tier = 0)
        addnav("Sonstiges");
        addnav("Teamliste","list_adv.php?op=team&ret=" . $_GET['ret'] . "");
        //addnav("X-Char Ãœbersicht","list_adv.php?op=npc&ret=" . $_GET['ret'] . "");
if($session['user']['acctid'] == 1) addnav("Avatarliste","avatarlist.php?ret=" . $_GET['ret'] . "");
 
 


} else {
 addnav("Gerade Online","list.php");
    addnav("ZurÃ¼ck");
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
    output("<br><br><td align='center'><img src='templates/orleans/img/bl.png' width='100%' height='100%'></td>",true);
}else{
    output("<td align='center'><img src='templates/orleans/img/bl.png' width='100%' height='100%'></td>",true);
}
output("<td width='10'></td>
</tr>
</table>
`n

",true);
output("`cFolgende Bewohner in New Orleans sind online!`n
        Hier findest du jeden, der in unserer schÃ¶nen Stadt wohnt.`n
        AuÃŸerdem kannst du dich hier Ã¼ber deine MitbÃ¼rger informieren und ihren Status einsehen.`0`c`n`n");

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
            $rp     = "#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0";
        if ($row['rp_char'] == 2)
            $rp     = "#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0";
        if ($row['rp_char'] == 3)
            $rp     = "#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0";
        if ($row['rp_char'] == 4)
            $rp     = "`\$Spezielle-Charas";



        
if ($row['superuser'] == 0){
            $rank     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`0";

if ($row['sex'] == 1){
            $rank     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`0";

 }
}
            
if ($row['superuser'] == 0 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcÃ¤#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser'] == 0 && $row['sex'] == 1 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6Ã¤#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser'] == 1){
            $rank     = "#c0c0c0M#b1b1b1o#a2a2a2d#939393e#858585r#767676a#676767t#585858o#4a4a4ar`0";
if ($row['sex'] == 1){
            $rank     = "#c0c0c0M#b4b4b4o#a8a8a8d#9c9c9ce#909090r#858585a#797979t#6d6d6do#616161r#555555i#4a4a4an`0";
 }
}
        
if ($row['superuser'] == 2){
            $rank     = "#4a4a4aM#585858o#676767d#767676e#858585r#939393a#a2a2a2t#b1b1b1o#c0c0c0r`0";
                                           
if ($row['sex'] == 1){
                        $rank     = "#4a4a4aM#555555o#616161d#6d6d6de#797979r#858585a#909090t#9c9c9co#a8a8a8r#b4b4b4i#c0c0c0n`0";
 }
}


    
if ($row['superuser'] == 3){
            $rank     = "#635219A#7f6920d#9b8028m#b7972fi#d4af37n`0";

if ($row['sex'] == 1){
                        $rank     = "#635219A#7b682ad#947f3bm#947f3bi#b49739n#d4af37a`0";

if ($row['acctid'] == 1){
                        $rank     = "#202020S#363121e#4c4222r#635424v#796525e#8f7727r#9b8028l#a18a41e#a7955ai#ada074t#b3aa8du#b9b5a6n#c0c0c0g`0";
  }
 }
}

        
if ($row['superuser'] == 4){
            $rank     = "#d4af37B#c7a433a#ba9a30u#ae902dm#a18529e#957b26i#887123s#7c661ft#6f5c1ce#635219r`0";
if ($row['sex'] == 1){
            $rank     = "#d4af37B#c7a537a#ba9b38u#ad9239m#a0883ae#947f3bi#947f3bs#8a7634t#806d2de#766426r#6c5b1fi#635219n`0";
if ($row['acctid'] == 2){
                        $rank     = "#ff7575V#fc8383e#f89191n#f59e9es#f1acac #eebabaT#eac8c8e#e7d5d5s#d1d1d1t#bfbfbfs#adadadk#9c9c9cl#8a8a8aa#787878v#666666i#545454n`0";
  }
 }
}
        
if ($row['superuser'] == 5){
            $rank     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`0";
if ($row['sex'] == 1){
            $rank     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`0";
 }
}

        //if ($row['superuser'] == 6)
        

    
        
        /*
          if ($row['superuser'] == 5) $rank = "`PBerater`0";
          if ($row['superuser'] == 6) $rank = "`6Kreativler`0";
          if ($row['superuser'] == 7) $rank = "`7Herrscher`0";
          if ($row['superuser'] == 8) $rank = "`9Programmierer`0";
          if ($row['superuser'] == 9) $rank = "`@BÃ¼rger`0";
         */



if ($row['superuser2'] == 0){
            $rank2     = "";

if ($row['sex'] == 1){
            $rank2     = "";
 }
}

if ($row['superuser2'] == 0 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcÃ¤#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser2'] == 0 && $row['sex'] == 1 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6Ã¤#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser2'] == 1){
            $rank2     = "#c0c0c0M#b1b1b1o#a2a2a2d#939393e#858585r#767676a#676767t#585858o#4a4a4ar`0";
if ($row['sex'] == 1){
            $rank2     = "#c0c0c0M#b4b4b4o#a8a8a8d#9c9c9ce#909090r#858585a#797979t#6d6d6do#616161r#555555i#4a4a4an`0";
 }
}
        
if ($row['superuser2'] == 2){
            $rank2     = "#4a4a4aM#585858o#676767d#767676e#858585r#939393a#a2a2a2t#b1b1b1o#c0c0c0r`0";
                                           
if ($row['sex'] == 1){
                        $rank2     = "#4a4a4aM#555555o#616161d#6d6d6de#797979r#858585a#909090t#9c9c9co#a8a8a8r#b4b4b4i#c0c0c0n`0";
 }
}
        
if ($row['superuser2'] == 3){
            $rank2     = "#635219A#7f6920d#9b8028m#b7972fi#d4af37n`0";

if ($row['sex'] == 1){
                        $rank2     = "#635219A#7b682ad#947f3bm#947f3bi#b49739n#d4af37a`0";

if ($row['acctid'] == 1){
                        $rank2     = "#202020S#363121e#4c4222r#635424v#796525e#8f7727r#9b8028l#a18a41e#a7955ai#ada074t#b3aa8du#b9b5a6n#c0c0c0g`0";
  }
 }
}

        
if ($row['superuser2'] == 4){
            $rank2     = "#d4af37B#c7a433a#ba9a30u#ae902dm#a18529e#957b26i#887123s#7c661ft#6f5c1ce#635219r`0";
if ($row['sex'] == 1){
            $rank2     = "#d4af37B#c7a537a#ba9b38u#ad9239m#a0883ae#947f3bi#947f3bs#8a7634t#806d2de#766426r#6c5b1fi#635219n`0";
 }
}
/*
if ($row['superuser2'] == 5){
            $rank2     = "#ff7575V#fc8383e#f89191n#f59e9es#f1acac #eebabaT#eac8c8e#e7d5d5s#cfcfcft#bababas#a6a6a6k#919191l#7d7d7da#686868v#545454e`0";
if ($row['sex'] == 1){
            $rank2     = "#ff7575V#fc8383e#f89191n#f59e9es#f1acac #eebabaT#eac8c8e#e7d5d5s#d1d1d1t#bfbfbfs#adadadk#9c9c9cl#8a8a8aa#787878v#666666i#545454n`0";
 }
}
*/        

if ($row['superuser2'] == 5){
            $rank2     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`0";
if ($row['sex'] == 1){
            $rank2     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`0";
 }
}

    //    if ($row['superuser2'] == 6)
    //        $rank2     = "`uZeremonienmeister`0";
        /*
          if ($row['superuser2'] == 5) $rank2 = "`PBerater`0";
          if ($row['superuser2'] == 6) $rank2 = "`6Kreativler`0";
          if ($row['superuser2'] == 7) $rank2 = "`7Herrscher`0";
          if ($row['superuser2'] == 8) $rank2 = "`9Programmierer`0";
         */


        if ($row['rpbulb'] == 0)
            $rpbulb     = "<img src='images/Assos/bulb0.png' alt='Kein Interesse am RP' titel='Kein Interesse am RP'>";
        if ($row['rpbulb'] == 1)
            $rpbulb     = "<img src='images/Assos/bulb1.png' alt='Bin bereit zum RPn' titel='Bin bereit zum RPn'>";
        if ($row['rpbulb'] == 2)
            $rpbulb     = "<img src='images/Assos/bulb2.png' alt='Im RP - Weitere Partner erwÃ¼nscht' titel='Im RP - Weitere Partner erwÃ¼nscht'>";
        if ($row['rpbulb'] == 3)
            $rpbulb     = "<img src='images/Assos/bulb3.png' alt='Suche spÃ¤teren RP-Partner' titel='Suche spÃ¤teren RP-Partner'>";
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
    output("<tr class='trhead' align='center'><td><b>Name</b></td><td><b>Rasse</b></td><td><b>Spezifikation</b></td><td><b>Char-Klasse</b></td><td><b><img src=\"images/system/common/female.gif\">/<img src=\"images/system/common/male.gif\"></b></td><td><b>Amt</b></td><td><b>Ort</b></td><td><b>Status</b></td><td><b>RP-Bereit?</b></td><td><b>Zuletzt da</b></td>",true);
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
                $charbio = "<a href=\"popup_bio.php?char=" . rawurlencode($row['login']) . "\"  onmouseover=\"TagToTip('" . $row['acctid'] . "',TITLEBGCOLOR,'#550000',BGCOLOR,'#050505',FONTCOLOR,'#EEEEEE' ,BORDERWIDTH,2,BORDERCOLOR,'#880000',TITLE,'Kurzinformationen Ã¼ber " . preg_replace("/#[a-f0-9]{6}|[`]./i","",$row['name']) . "`0')\" onmouseout=\"UnTip()\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">" . $row['name'] . " " . $clan . "`0</a>";
            } else {
                $clan     = $row_clan['clanname'];
                $charbio = $row['name'] . " " . $clan . "`0";
            }
        } else {
            if ($session['user']['loggedin']) {
                $charbio = "<a href='popup_bio.php?char=" . rawurlencode($row['login']) . "'  onmouseover=\"TagToTip('" . $row['acctid'] . "',TITLEBGCOLOR,'#550000',BGCOLOR,'#050505',FONTCOLOR,'#EEEEEE' ,BORDERWIDTH,2,BORDERCOLOR,'#880000',TITLE,'Kurzinformationen Ã¼ber " . preg_replace("/#[a-f0-9]{6}|[`]./i","",$row['name']) . "`0')\" onmouseout=\"UnTip()\" onClick=\"" . popup_bio("popup_bio.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">" . $row['name'] . "`0</a>";
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
            $rp     = "#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0";
        if ($row['rp_char'] == 2)
            $rp     = "#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0";
        if ($row['rp_char'] == 3)
            $rp     = "#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0";


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
            output("`n`7Info: " . html_entity_decode(stripslashes($prefs['info'])) . "`0");
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

        /* if ($row['superuser'] == 0) $rank = "`@BÃ¼rger`0";
          if ($row['superuser'] == 1) $rank = "`@BÃ¼rger `Z(Eventler)`0";
          if ($row['superuser'] == 2) $rank = "`@BÃ¼rger `m(Priester)`0";

          if ($row['superuser'] == 3) $rank = "`!Lehrer`0";
          if ($row['superuser'] == 4) $rank = "`7Richter`0";
          if ($row['superuser'] == 5) $rank = "`^Berater`0";
          if ($row['superuser'] == 6) $rank = "`PKreativler`0";
          if ($row['superuser'] == 7) $rank = "`7Herrscher`0";
          if ($row['superuser'] == 8) $rank = "`9Programmierer`0";

          if ($row['superuser2'] == 0) $rank2 = "";
          if ($row['superuser2'] == 1) $rank2 = "`ZEventler`0";
          if ($row['superuser2'] == 2) $rank2 = "`mPriester`0";

          if ($row['superuser2'] == 3) $rank2 = "`!Lehrer`0";
          if ($row['superuser2'] == 4) $rank2 = "`7Richter`0";
          if ($row['superuser2'] == 5) $rank2 = "`PBerater`0";
          if ($row['superuser2'] == 6) $rank2 = "`6Kreativler`0";
          if ($row['superuser2'] == 7) $rank2 = "`7Herrscher`0";
          if ($row['superuser2'] == 8) $rank2 = "`9Programmierer`0";
         */


if ($row['superuser'] == 0){
            $rank     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`0";

if ($row['sex'] == 1){
            $rank     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`0";
            
if ($row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcÃ¤#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['sex'] == 1 && $row['ZMeister'] == 1){
                $rank     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6Ã¤#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";
   }
  }
 }
}    
if ($row['superuser'] == 1){
            $rank     = "#c0c0c0M#b1b1b1o#a2a2a2d#939393e#858585r#767676a#676767t#585858o#4a4a4ar`7`0";
if ($row['sex'] == 1){
            $rank     = "#c0c0c0M#b4b4b4o#a8a8a8d#9c9c9ce#909090r#858585a#797979t#6d6d6do#616161r#555555i#4a4a4an`7`0";
 }
}
        
if ($row['superuser'] == 2){
            $rank     = "#4a4a4aM#585858o#676767d#767676e#858585r#939393a#a2a2a2t#b1b1b1o#c0c0c0r`7`0";
                                           
if ($row['sex'] == 1){
                        $rank     = "#4a4a4aM#555555o#616161d#6d6d6de#797979r#858585a#909090t#9c9c9co#a8a8a8r#b4b4b4i#c0c0c0n`7`0";
 }
}
        
if ($row['superuser'] == 3){
            $rank     = "#635219A#7f6920d#9b8028m#b7972fi#d4af37n`7`0";

if ($row['sex'] == 1){
                        $rank     = "#635219A#7b682ad#947f3bm#947f3bi#b49739n#d4af37a`7`0";

if ($row['acctid'] == 1){
                        $rank     = "#202020S#363121e#4c4222r#635424v#796525e#8f7727r#9b8028l#a18a41e#a7955ai#ada074t#b3aa8du#b9b5a6n#c0c0c0g`7`0";
  }
 }
}

        
if ($row['superuser'] == 4){
            $rank     = "#d4af37B#c7a433a#ba9a30u#ae902dm#a18529e#957b26i#887123s#7c661ft#6f5c1ce#635219r`7`0";
if ($row['sex'] == 1){
            $rank     = "#d4af37B#c7a537a#ba9b38u#ad9239m#a0883ae#947f3bi#947f3bs#8a7634t#806d2de#766426r#6c5b1fi#635219n`7`0";
 }
}
        
if ($row['superuser'] == 5){
            $rank     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`7`0";
if ($row['sex'] == 1){
            $rank     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`7`0";
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
                $rank2     = "`u#c0c0c0B#c7c7c7i#cececeo#d5d5d5w#dcdcdcÃ¤#e3e3e3c#eaeaeah#f1f1f1t#f8f8f8e#ffffffr`0";

if ($row['superuser2'] == 0 && $row['sex'] == 1 && $row['ZMeister2'] == 1){
                $rank2     = "`u#c0c0c0B#c5c5c5i#cbcbcbo#d1d1d1w#d6d6d6Ã¤#dcdcdcc#e2e2e2h#e8e8e8t#ededede#f3f3f3r#f9f9f9i#ffffffn`0";

 }
}
        
if ($row['superuser2'] == 1){
            $rank2     = "#c0c0c0M#b1b1b1o#a2a2a2d#939393e#858585r#767676a#676767t#585858o#4a4a4ar`7`0";
if ($row['sex'] == 1){
            $rank2     = "#c0c0c0M#b4b4b4o#a8a8a8d#9c9c9ce#909090r#858585a#797979t#6d6d6do#616161r#555555i#4a4a4an`7`0";
 }
}
        
if ($row['superuser2'] == 2){
            $rank2     = "#4a4a4aM#585858o#676767d#767676e#858585r#939393a#a2a2a2t#b1b1b1o#c0c0c0r`7`0";
                                           
if ($row['sex'] == 1){
                        $rank2     = "#4a4a4aM#555555o#616161d#6d6d6de#797979r#858585a#909090t#9c9c9co#a8a8a8r#b4b4b4i#c0c0c0n`7`0";
 }
}
        
if ($row['superuser2'] == 3){
            $rank2     = "#635219A#7f6920d#9b8028m#b7972fi#d4af37n`7`0";

if ($row['sex'] == 1){
                        $rank2     = "#635219A#7b682ad#947f3bm#947f3bi#b49739n#d4af37a`7`0";

if ($row['acctid'] == 1){
                        $rank2     = "#202020S#363121e#4c4222r#635424v#796525e#8f7727r#9b8028l#a18a41e#a7955ai#ada074t#b3aa8du#b9b5a6n#c0c0c0g`7`0";
  }
 }
}

        
if ($row['superuser2'] == 4){
            $rank2     = "#d4af37B#c7a433a#ba9a30u#ae902dm#a18529e#957b26i#887123s#7c661ft#6f5c1ce#635219r`7`0";
if ($row['sex'] == 1){
            $rank2     = "#d4af37B#c7a537a#ba9b38u#ad9239m#a0883ae#947f3bi#947f3bs#8a7634t#806d2de#766426r#6c5b1fi#635219n`7`0";
 }
}
        
if ($row['superuser2'] == 5){
            $rank2     = "#70481fB#805223e#905c27w#a1662bo#a1662bh#af6e2dn#be762fe#cd7f32r`7`0";
if ($row['sex'] == 1){
            $rank2     = "#70481fB#7c4f22e#885725w#945e28o#a1662bh#a1662bn#ac6c2ce#b7722er#c27830i#cd7f32n`7`0";
 }
}
        /*if ($row['superuser2'] == 6)
            $rank2     = "`uZeremonienmeister`0";
            */






        output($rank . ' ' . ($rank2 ? '`&(' . $rank2 . '`&)' : ''));



        output("</td><td>",true);
        
        $place = $row['standort'];
        if ($place == "Bei der Arbeit" && $row['superuser'] == 5) {
            $place = "Bewohnerliste";
        }
        output("`c" . $place . "`c");
        output("</td><td>",true);


        output($row['alive'] ? "`1`c`bLebt`b`c`0" : "`4`c`bTot`b`c`0");
        output("</td><td align=\"center\">",true);
        if ($row['rpbulb'] == 0)
            $rpbulb     = "<img src='images/Assos/bulb0.png' alt='Kein Interesse am RP' titel='Kein Interesse am RP'>";
        if ($row['rpbulb'] == 1)
            $rpbulb     = "<img src='images/Assos/bulb1.png' alt='Bin bereit zum RPn' titel='Bin bereit zum RPn'>";
        if ($row['rpbulb'] == 2)
            $rpbulb     = "<img src='images/Assos/bulb2.png' alt='Im RP - Weitere Partner erwÃ¼nscht' titel='Im RP - Weitere Partner erwÃ¼nscht'>";
        if ($row['rpbulb'] == 3)
            $rpbulb     = "<img src='images/Assos/bulb3.png' alt='Suche spÃ¤teren RP-Partner' titel='Suche spÃ¤teren RP-Partner'>";
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
<table border='0' align='center' cellspacing='1' cellpadding='1' bgcolor='#202020'>
    <table border='0' align='center' cellspacing='2' cellpadding='2' bgcolor='#202020'>
    <tr><td>
      <table border='0' cellspacing='3' cellpadding='3' bgcolor='#080808'>
        <tr>
            <td colspan='6'><font size='2pt'>`b`cLegende:`c`b`0</font></td>
        </tr>
        <tr>
                     <td><img src='images/Assos/bulb0.png'></td><td>`b&raquo; Derzeit kein RP mÃ¶glich.`b</td>
            <td><img src='images/Assos/bulb1.png'></td><td>`b&raquo; Ich bin sofort startklar! Frag mich!!`b</td>
            <td><img src='images/Assos/bulb4.png'></td><td>`b&raquo; Ich bin bereits in einem Play.`b</td>            
            <td>&nbsp</td><td>&nbsp</td>
        </tr>
        <tr><center>
                        <td><img src='images/Assos/bulb2.png'></td><td>`b&raquo; In mein Spiel darf sich jeder reinschreiben!`b</td>
            <td><img src='images/Assos/bulb3.png'></td><td>`b&raquo; Suche fÃ¼r spÃ¤ter einen RP-Partner.`b</td></center>


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

