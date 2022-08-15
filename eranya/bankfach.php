
<?php

// 22062004

require_once "common.php";

page_header("Die alte Bank");
output("`c`b`^Schließfach Verwaltung`b`c`n");

$sql = db_query("SELECT gemsinbank FROM account_extra_info WHERE acctid=".$session['user']['acctid']);
$row = db_fetch_assoc($sql);

if($_GET['op']=="fachanlegen")
{
    if ($session['user']['gold']>=25000){
        output("Du hast dir erfolgreich ein Schließfach angelegt.");
        debuglog("hat sich das Schließfach freigeschaltet.");
        $session['user']['gold']-=25000;
        $session['user']['gemsfach']++;
    }
    else
    {
        output("Du hast nicht genug Gold dabei!");
    }
}
else if($_GET[op]=="gemsdepositfinish")
{
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0){
        $_POST['amount']=$session['user']['gems'];
    }
    if ($_POST['amount']>$session['user']['gems']){
        output("Nanana! Nicht versuchen zu tricksen, so viele Edelsteine hast du garnicht bei dir!`n");
                //output("Du schmeißt deine `&".$session[user][gold]."`^ Gold auf den Schaltertisch und erklärst, dass du die ganzen `&$_POST[amount]`^ Gold einzahlen möchtest.");
                //output("`n`nDer kleine alte Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen können?");
    }
    else if($_POST['amount']+$row['gemsinbank']>500)
    {
        output("`2In dein Schließfach passen leider nicht mehr als 500 Edelsteine.");
    }
    else
    {
        output("`^Du legst `&{$_POST['amount']}`^ Edelsteine in dein Schließfach.");
        debuglog("deposited " . $_POST['amount'] . " gems in the bank");
        $session['user']['gems']-=$_POST['amount'];
        db_query('UPDATE account_extra_info SET gemsinbank=gemsinbank+'.$_POST['amount'].' WHERE acctid='.$session['user']['acctid']);
        output("`nDamit hast du `&".($row['gemsinbank']+$_POST['amount'])." `^Edelsteine im Schließfach.");
    }
}
else if($_GET['op']=="gemswithdrawfinish")
{
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0){
        $_POST['amount']=abs($row['gemsinbank']);
    }
    if ($_POST['amount']>$row['gemsinbank'])
    {
        output("`\$FEHLER: Nicht genug Edelsteine in deinem Schließfach.`^`n`n");
    }
    else
    {
        output("`^Du hast `&{$_POST['amount']}`^ Edelsteine aus deinem Schließfach genommen.");
        $session['user']['gems']+=$_POST['amount'];
        db_query('UPDATE account_extra_info SET gemsinbank=gemsinbank-'.$_POST['amount'].' WHERE acctid='.$session['user']['acctid']);
        debuglog("withdrew " . $_POST[amount] . " gems from the bank");
        output("`nDamit hast du `&".($row['gemsinbank']-$_POST['amount'])."`^ Edelsteine in deinem Schließfach und
                `&".$session['user']['gems']."`^ Edelsteine bei dir.");
    }
}
else
{
    if ($session['user']['gemsfach']==0){
        addnav("Schließfach anlegen (`^25000 Gold`0)","bankfach.php?op=fachanlegen");
        output("`qHier kannst du ein Schließfach anlegen, in dem du bis zu 500 Edelsteine deponieren kannst!`n`n");
    }
    else
    {
        output("`qDu stehst vor deinem Schließfach und siehst, dass du
                bereits `^".$row['gemsinbank']." `qEdelsteine darin verwahrt hast.`n`n");
        //einzahlen
        output("<form action='bankfach.php?op=gemsdepositfinish' method='POST'>`n",true);
        output("`qHier kannst du deine Edelsteine hinterlegen: ");
        output("<input id='input' name='amount' width=5 accesskey='g'> <input type='submit' class='button' value='Hineinlegen'></form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","bankfach.php?op=gemsdepositfinish");
        //abheben
        output("<form action='bankfach.php?op=gemswithdrawfinish' method='POST'>`n",true);
        output("`qHier kannst du deine Edelsteine wieder mitnehmen: ");
        output("<input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='Herausnehmen'></form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","bankfach.php?op=gemswithdrawfinish");
    }
}
addnav('Zurück zur Bank','bank.php');

page_footer();

?>

