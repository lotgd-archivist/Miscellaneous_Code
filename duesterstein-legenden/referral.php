
<?
require_once "common.php";

if ($session['user']['loggedin']){
    page_header("Empfehlungen");
    addnav("Zurück zur Jägerhütte","lodge.php?op=entry");
    output("Für jede Person, die Du geworben hast und die Level 4 erreicht, erhälst Du automatisch 25 Donation Punkte.
    `n`n
    Wie weiß die Seite, wer die Person geworben hat?`n
  Ganz einfach!  Wenn Du Deinen Freunden von dieser Seite erzählst, gibst Du ihnen den folgenden Link:`n`n
  ".getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']))."/referral.php?r=". rawurlencode($session['user']['login'])."`n`n
    und die Seite wird wissen, daß Du es warst, der denjenigen geworben hat.  Wenn derjenige dann den Level 4 erreicht hat, bekommst Du die Donation Punkte!");
    
    $sql = "SELECT name,level,refererawarded FROM accounts WHERE referer={$session['user']['acctid']} ORDER BY dragonkills,level";
  //    output($sql);
    $result = db_query($sql);
  output("`n`nAccounts die Du geworben hast:`n<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Belohnt?</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
        output($row['name']);
        output("</td><td>{$row['level']}</td><td>".($row['refererawarded']?"`@Yes!`0":"`\$No!`0")."</td></tr>",true);
    }
    if (db_num_rows($result)==0){
        output("<tr><td colspan='3' align='center'>`iNone!</td><?tr>",true);
    }
    output("</table>",true);
    page_footer();
}else{
    page_header("Willkommen bei Legend of the Green Dragon");
    output("`@Legend of the Green Dragon ist eine Nachmache von dem klassischem BBS Door Spiel Legend of the Red Dragon.  
    Komm rein und nehme am Abenteuer in einem Realm teil, der eines der ersten Multiplayer Rollenspiele der Welt darstellte!
    ");
    addnav("Einen Charakter erstellen","create.php?r=".HTMLEntities($_GET['r']));
    addnav("Anmeldeseite","index.php");
    page_footer();
}?>

