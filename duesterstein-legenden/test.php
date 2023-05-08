
<?
require_once "common.php";
//redirect("xanders.php");
//redirect("rock2.php");
if ($session[user][acctid]==101 || $session[user][acctid]==721) {
    page_header("Test-Site");
    output("`b`!Test-Site`0`b`n`n",true);
    addnav("von Gargamel");
    addnav("Refresh","test.php");
    addnav("Zwergenstadt","race_dwarf.php?mod=test");
    addnav("Elfenhort","race_elf.php?mod=test");
    addnav("Troll-Lager","race_troll.php?mod=test");
    addnav("Menschen-Park","race_human.php?mod=test");
    //addnav("Stats2","stats2.php");
    addnav("Zurück","village.php");
    //addcommentary();
    //viewcommentary("gardens","test",100,"sagt");


    output("`!`b`cDie magischen Steine des Lava-Altars`c`b`n",true);
    output("`@Nun ".(($sex==1)?"junge Kriegerin, ":"junger Krieger, ")." Du möchtest
    wisssen, wer welchen magischen Stein besitzt? Sieh selbst:`n");
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td align='center'>`&`bStein N°`b</td>
    <td align='center'>`&`bStein`b</td>
    <td align='center'>`b`&Besitzer`b</td></tr>",true);
    $numstones = getsetting("magischesteine",0);
    for ($i = 1; $i < ($numstones+1); $i++){
        $sql = "SELECT name FROM accounts WHERE stone=$i";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if (db_num_rows($result) == 0) {
           $row[name]="`b`\$verfügbar`b";
           //$pietra1="`5Unknown";
           $pietra1=$stone[$i];
        }else $pietra1=$stone[$i];
        if ($row[name] == $session[user][name]) {
           output("<tr bgcolor='#007700'>", true);
        } else {
           output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td align='center'>`&".$i."</td><td align='center'>`&`b$pietra1`b</td><td align='center'>`&`b{$row[name]}`b</td></tr>",true);
    }
    output("</table>", true);
}
/*else if ($session[user][acctid]==4) {*/
else {
    page_header("Test-Site");
    output("`b`!Test-Site`0`b`n`n",true);
    addnav("von Gargamel");
    addnav("Zwergenstadt","race_dwarf.php");
    addnav("Elfenhort","race_elf.php");
    addnav("Troll-Lager","race_troll.php");
    addnav("Menschen-Park","race_human.php");
    addnav("Zurück","village.php");
} /**/
/*else {
    redirect("village.php");
}
*/
page_footer();

?>


