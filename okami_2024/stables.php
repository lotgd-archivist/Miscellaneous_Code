
<?php

// 24062004

require_once "common.php";
$session[user][standort]="Söldner";
page_header("Söldner");

$repaygold = round($playermount['mountcostgold']*2/3,0);
$repaygems = round($playermount['mountcostgems']*2/3,0);
$futtercost = $session[user][level]*20;

addnav("Zurück","kg.php");
if ($session['user']['hashorse']>0 && $session[user][fedmount]==0) addnav("f?{$playermount['mountname']} stärken (`^$futtercost`0 Gold)","stables.php?op=futter");

if ($_GET[op]==""){
    checkday();
    output("`7Etwas links von Ying's Rüstungen, befindet sich eine kleine Tür.`n
    Darin kümmert sich Xavier, um die Söldner die man hier mieten kann.
    `n`n
    Du näherst dich ihm, als er plötzlich herumwirbelt und seine Scimitare in deine ungefähre Richtung streckt. \"`&Ach, 
    'tschuldigung mein ".($session[user][sex]?"Mädel":"Junge").", hab dich nicht kommen hören und hab gedacht,
    du bist sich ein Tunichtgut, der ma wieder für unruhe sorgen will. So, was 
    kann ich für dich tun?`7\"");
}elseif($_GET['op']=="examine"){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, ich heb kein solchen Söldner da!`7\" ruft Xavier!");
    }else{
        output("`7\"`&Ai, ich heb wirklich n paar starke Söldner hier!`7\" kommentiert Xavier.`n`n");
        $mount = db_fetch_assoc($result);
        output("`7Söldner: `&{$mount['mountname']}`n");
        output("`7Beschreibung: `&{$mount['mountdesc']}`n");
        output("`7Preis: `^{$mount['mountcostgold']}`& Gold, `%{$mount['mountcostgems']}`& Edelstein".($mount['mountcostgems']==1?"":"e")."`n");
        output("`n");
        addnav("Den Söldner anstellen","stables.php?op=buymount&id={$mount['mountid']}");
    }
}elseif($_GET['op']=='buymount'){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, ich heb keen solches Söldner da!`7\" ruft Xavier!");
    }else{
        $mount = db_fetch_assoc($result);
        if ( 
            ($session['user']['gold']+$repaygold) < $mount['mountcostgold']
             || 
            ($session['user']['gems']+$repaygems) < $mount['mountcostgems']
        ){
            output("`7Xavier schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kannst du nicht sehen, dass der Söldner {$mount['mountname']} `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine kostet?`7\"");
        }else{
            if ($session['user']['hashorse']>0){
                output("`7Du übergibst deinen Söldner {$playermount['mountname']} und bezahlst den Preis für deinen neuen Söldner. Xavier führt deinen neuen Söldner `&{$mount['mountname']}`7  für dich heraus!`n`n");
                $session[user][reputation]--;
            }else{
                output("`7Du bezahlst den Preis für dein neuen Söldner und Xavier führt deinen Söldner `&{$mount['mountname']}`7 für dich heraus!`n`n");
            }
            $session['user']['hashorse']=$mount['mountid'];
            $goldcost = $repaygold-$mount['mountcostgold'];
            $session['user']['gold']+=$goldcost;
            $gemcost = $repaygems-$mount['mountcostgems'];
            $session['user']['gems']+=$gemcost;
            debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
            $session['bufflist']['mount']=unserialize($mount['mountbuff']);
            // Recalculate so the selling stuff works right
            $playermount = getmount($mount['mountid']);
            $repaygold = round($playermount['mountcostgold']*2/3,0);
            $repaygems = round($playermount['mountcostgems']*2/3,0);
        }
    }
}elseif($_GET['op']=='sellmount'){
    $session['user']['gold']+=$repaygold;
    $session['user']['gems']+=$repaygems;
    debuglog("gained $repaygold gold and $repaygems gems selling their mount");
    unset($session['bufflist']['mount']);
    $session['user']['hashorse']=0;
    output("`7So schwer es dir auch fällt, dich von deinem Söldner {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
    output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fühlst du dich gleich ein wenig besser.");
    $session[user][reputation]-=2;
}elseif($_GET['op']=='futter'){
    if ($session[user][gold]>=$futtercost) {
                $buff = unserialize($playermount['mountbuff']);
                if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {
            output("Dein Söldner {$playermount['mountname']} ist gestärkt und rührt den Trank nicht an. Darum gibt Xavier dir dein Gold zurück.");
        }else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
            $futtercost=$futtercost/2;
            output("Dein Söldner{$playermount['mountname']} nippt etwas an dem Trank und lässt den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");
            output("Da aber noch über die Hälfte des Trankes übrig ist, gibt dir Xavier 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");
            $session[user][gold]-=$futtercost;
            $session[user][reputation]--;
        }else{
            $session[user][gold]-=$futtercost;
            output("Dein Söldner {$playermount['mountname']} trinkt den Trank auf ex.`n");
            output("Dein Söldner {$playermount['mountname']} ist vollständig regeneriert und du gibst Xavier die $futtercost Gold."); 
            $session[user][reputation]--;
        }
               $session['bufflist']['mount']=$buff;
        $session[user][fedmount]=1;
    } else {
        output("`7Du hast nicht genug Gold dabei, um den Trank zu bezahlen. Xavier weigert sich den Söldner zu stärken und empfiehlt dir, im Wald nach einem Trank zu suchen.");
    }
}

$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 ORDER BY mountcategory,mountcostgems,mountcostgold";
$result = db_query($sql);
$category="";
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($category!=$row['mountcategory']){
        addnav($row['mountcategory']);
        $category = $row['mountcategory'];
    }
    addnav("Betrachte {$row['mountname']}`0","stables.php?op=examine&id={$row['mountid']}");
}
if ($session['user']['hashorse']>0){
    output("`n`nXavier bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine für deinen Söldner {$playermount['mountname']}.");
    addnav("Sonstiges");
    addnav("Verkaufe {$playermount['mountname']}","stables.php?op=sellmount");
}

page_footer();
?>

