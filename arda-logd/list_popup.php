<?php
require_once "common.php";

addcommentary ();
session_write_close ();
addcommentary ();

output ( " `c[ <a href='list_popup.php'>Liste</a> | <a href='list_popup.php?op=ooc'>OoC-Raum</a> ]`c`n", true );

if ($_GET ['op'] == 'ooc') {
    popup_header ( "OoC-Raum" );
    if ($session[user][einlass]==1) {
        output("`\$Hier ist der OOC, bitte auch so handeln!");
    viewcommentary ( "list", "mit anderen unterhalten", 25 );
    }else{
        output("`\$Als verifizierter Spieler sieht man hier den OOC.");
    }
 }else {
    popup_header ( "Einwohnerliste" );
    $sql = "SELECT acctid,name,login,alive,admin,location,sex,level,laston,loggedin,lastip,uniqueid,crace,prison,superuser,rplamp FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date ( "Y-m-d H:i:s", strtotime ( date ( "r" ) . "-" . getsetting ( "LOGINTIMEOUT", 900 ) . " seconds" ) ) . "' ORDER BY level DESC, dragonkills DESC, login ASC";

    $result = db_query ( $sql ) or die ( sql_error ( $sql ) );
    $max = db_num_rows ( $result );

    output ( "<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>", true );
    output ( "<tr style='background-color:#990000;color:#FFFFFF;font-size:12px;'> <td><b>Level</b></td><td><b>RP</b></td><td><b>Name</b></td><td><b>Rasse</b></td><td><b>Char-Klasse</b></td><td><b>Char-Status</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ort</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></tr>", true );
    for($i = 0; $i < $max; $i ++) {
        $row = db_fetch_assoc ( $result );
        output ( "<tr style='background-color:" . ($i % 2 ? "#000000" : "#330000") . "'><td>", true );
        output ( "`^$row[level]`0" );
        output ( "</td><td align=\"center\">", true );
        output ( $row [rplamp] ? "<img src=\"images/on.png\">" : "<img src=\"images/off.png\">", true );
        output ( "</td><td>", true );

        if ($session [user] [loggedin])
            output ( "<a href=\"mail.php?op=write&to=" . rawurlencode ( $row ['login'] ) . "\" target=\"_blank\" onClick=\"" . popup ( "mail.php?op=write&to=" . rawurlencode ( $row ['login'] ) . "" ) . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>", true );
        if ($session [user] [loggedin])
            output ( "<a href=\"biopopup.php?char=" . rawurlencode ( $row ['login'] ) . "\" target=\"_blank\" onClick=\"" . popupbio ( "biopopup.php?char=" . rawurlencode ( $row ['login'] ) . "" ) . ";return false;\">", true );

        output ( "`" . ($row [acctid] == getsetting ( "hasegg", 0 ) ? "^" : "&") . "$row[name]`0" );

        output ( "</td><td>", true );
        output ( $row ['crace'] );
        output ( "</td><td align=\"center\">", true );
        if ($row ['admin'] == 0)
            output ( 'Keine Klasse', true );
        if ($row ['admin'] == 1)
            output ( '`9RP`3G-`#C`3ha`9ra`0' );
        if ($row ['admin'] == 2)
            output ( 'Mi`4x-`$C`4hara`0' );
        if ($row ['admin'] == 3)
            output ( 'Le`qve`2l-C`ghara`0' );
        if ($row ['admin'] == 4)
            output ( '`$Spezielle-Charas' );
        output ( "</td><td align=\"center\">", true );
        if ($row ['superuser'] == 0)
            output ( $row [sex] ? '`ÀSpi`êele`Àrin' : '`XSp`miel`Xer' );
        if ($row ['superuser'] == 1)
            output ( '`IKon`8to`pll`8kob`Iold`0' );
        if ($row ['superuser'] == 2)
            output ( '`lMär`Lchen`Lkob`lold`0' );
        if ($row ['superuser'] == 3)
            output ( '`dGr`Sot`ste`Pnk`sob`Sol`dd`0' );
        if ($row ['superuser'] == 4) {
            if ($row ['login'] == "Kibarashi") {
            output ( '`fC`Fo`jd`De-`rG`êr`àe`lml`Kin`0' );
            } else {
                output ( '`#Gr`Fo`3tte`fno`#lm' );
            }
        }
        output ( "</td><td>", true );
        output ( $row [sex] ? "<img src=\"images/female.gif\">" : "<img src=\"images/male.gif\">", true );
        output ( "</td><td>", true );
        $loggedin = (date ( "U" ) - strtotime ( $row [laston] ) < getsetting ( "LOGINTIMEOUT", 900 ) && $row [loggedin]);
        if ($row [location] == 0)
            output ( $loggedin ? "`#Online`0" : "`3Die Felder`0" );
        if ($row [location] == 1)
            output ( "`3Zimmer in Kneipe`0" );
        if ($row [location] == 2)
            output ( "`3Im Haus`0" );
        output ( "</td><td>", true );
        output ( $row [alive] ? "`1Lebt`0" : "`4Tot`0" );
        output ( "</td><td>", true );
        $laston = round ( (strtotime ( date ( "r" ) ) - strtotime ( $row [laston] )) / 86400, 0 ) . " Tage";
        if (substr ( $laston, 0, 2 ) == "1 ")
            $laston = "1 Tag";
        if (date ( "Y-m-d", strtotime ( $row [laston] ) ) == date ( "Y-m-d" ))
            $laston = "Heute";
        if (date ( "Y-m-d", strtotime ( $row [laston] ) ) == date ( "Y-m-d", strtotime ( date ( "r" ) . "-1 day" ) ))
            $laston = "Gestern";
        if ($loggedin)
            $laston = "Jetzt";
        output ( $laston );
        output ( "</td></tr>", true );
    }
    output ( "</table>", true );
output("Legende:`n`^Kontrollkobold=Stadtwächter, `@Märchenkobold=Biowächter, `3Grottenkobold=Moderator, `\$Grottenolm=Admin");
}
popup_footer ();
?>