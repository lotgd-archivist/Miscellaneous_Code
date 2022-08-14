
<?php

// 21072004
require_once "common.php";
addcommentary();

$date_check = date("d.m");

$saint_omar_season = array(                12    => "winter",
                            1    => "winter",
                            2    => "winter",
                            3     => "spring",
                            4    => "spring",
                            5    => "spring",
                            6    => "summer",
                            7    => "summer",
                            8    => "summer",
                            9    => "fall",
                            10    => "fall",
                            11    => "fall"
                           );
//if($session['user']['acctid'] == 0) {
//    phpinfo();
//}

// Schatten
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}

// Stadttor
if($session['user']['zugang'] == 0){
   redirect("bootsanleger.php");
}

// Kerker
if($session['user']['prison'] == 1){
    redirect("kerker.php");
}

if($session['user']['einzelhaft'] == 1){
    redirect("kerker.php");
}

if($session['user']['acctid'] == 0) {
    output($session['admin_msg']);
    unset($session['admin_msg']);
}

$sql = "SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row['acctid1'] == $session['user']['acctid'] AND $row['turn'] == 1) OR ($row['acctid2'] == $session['user']['acctid'] AND $row['turn'] == 2)){
    redirect("heldengasse_pvparena.php");
}

if (getsetting('automaster',1) && $session['user']['seenmaster']!=1){
    //masters hunt down truant students
    $exparray = array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
    while(list($key,$val) = each($exparray)){
        $exparray[$key] = round(
        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
        ,0);
    }
    $expreqd = $exparray[$session['user']['level']+1];
    if ($session['user']['experience'] > $expreqd AND $session['user']['level'] < 15){
        redirect("train.php?op=autochallenge");
    }else if ($session['user']['experience'] > $expreqd AND $session['user']['level'] >= 15){
        redirect("dragon.php?op=autochallenge");
    }
}
$session['user']['specialinc'] = "";
$session['user']['specialmisc'] = "";
    
        addnav("Das Festland");
        addnav("Der Wald","forest.php");
        addnav("Die nÃ¤here Umgebung","umgebung.php");
        addnav("Das Land erkunden", "eassos.php");

        addnav("Die Hauptinsel");
        //if ($session['user']['fsk'] == 2) addnav("`4Halloweenball*`0","event_halloween.php");
        addnav("Das Zentrum","astaros_zentrum.php");
        addnav("Das Wohlstandsviertel","astaros_wohlstandsviertel.php");
        addnav("Die mediterranen GÃ¤rten","garden.php");
        if ($settings['xmas'] > '0') {    
        addnav("Weihnachtsviertel","event_weihnachtsviertel.php");
        }

        addnav("Die groÃŸe Insel");
        addnav("Der groÃŸe Markt","markt.php");
        addnav("Der Handelshafen","hafen.php");
        addnav("Der Bootsanleger","bootsanleger.php");
        addnav("Der Ã¶ffentliche Strand","strand.php");

        addnav("Die kleinen Inseln");
        addnav("Die Armeninsel","astaros_armeninsel.php");
        addnav("Das Marineford","astaros_marineford.php");
        addnav("Die Sireneninsel","astaros_sireneninsel.php");
        addnav("Die Pirateninsel","pirateninsel.php");
        if ($session['user']['fsk'] == 2) { 
        addnav("Die Freudeninsel*","astaros_freudeninsel.php"); }
            
        //Bewohnerliste und zurÃ¼ck zur Stadtauswahl
        addnav("Organisatorisches");
        if ($session['user']['superuser'] > 1) addnav("Stadtregeln von Astaros","astaros_regeln.php");
        addnav("JÃ¤gerhÃ¼tte","lodge.php");
        addnav("Aktuelle Bekundungen","news.php");
        addnav("Bewohnerliste","list.php?ret=Astaros");
        addnav("In die Felder (Logout)","login.php?op=logout",true);

//let users try to cheat, we protect against this and will know if they try.
addnav("","superuser.php");
addnav("","user.php");
addnav("","taunt.php");
addnav("","creatures.php");
addnav("","configuration.php");
addnav("","badword.php");
addnav("","armoreditor.php");
addnav("","bios.php");
addnav("","badword.php");
addnav("","donators.php");
addnav("","referers.php");
addnav("","retitle.php");
addnav("","stats.php");
addnav("","viewpetition.php");
addnav("","weaponeditor.php");

if(getsetting("topwebid", 0) != 0){
    addnav("Top Web Games");
    if(date("Y-W", strtotime($session['user']['lastwebvote'])) < date("Y-W")){
        $hilight="`&";
    }else{
        $hilight="";
        addnav("S?".$hilight."Stimme abgeben", "http://www.topwebgames.com/in.asp?id=".getsetting("topwebid", 0)."&acctid={$session['user']['acctid']}", false, true);
    }
}

page_header("Astaros Hauptplatz");

$session['user']['standort'] = "Astaros Hauptplatz";

//place();

output("
<table border='0' align='center' cellspacing='2' cellpadding='2'>
<tr>
<td width='10'></td>",true);
$time_check = date("H");
if($time_check >= "07" AND $time_check <= "19"){
    output("<td align='center'><img src='images/Astaros/neu/platz.png' width='100%' height='100%'></td>",true);
}else{
    output("<td align='center'><img src='images/Astaros/neu/platzn.png' width='100%' height='100%'></td>",true);
}
output("<td width='10'></td>
</tr>
</table>
`n`n


",true);

$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);

//output("`c<img src=images/sonstiges/candle-light(1).gif>`c`n`n", true);

output("`n`c#4a4a4aA#4c4c4cu#4f4f4ff #515151e#545454i#565656n#595959e#5b5b5br #5e5e5eT#606060a#636363f#656565e#686868l #6a6a6ak#6d6d6da#6f6f6fn#727272n#747474s#777777t #797979d#7c7c7cu #7e7e7ed#818181i#838383e #868686w#888888i#8b8b8bc#8d8d8dh#909090t#929292i#949494g#959595s#969696t#989898e#999999n 
#9b9b9bM#9c9c9ce#9e9e9el#9f9f9fd#a1a1a1u#a2a2a2n#a4a4a4g#a5a5a5e#a7a7a7n #a8a8a8a#aaaaaau#abababs #acacacEa#afafafs#b1b1b1s#b2b2b2o#b4b4b4s #b5b5b5e#b7b7b7r#b8b8b8f#bababaa#bbbbbbh#bdbdbdr#bebebee#c0c0c0n:`0`c`n`c`i`&".$row['newstext']."`n",true);
output("`n<a href='popup_serverstory.php?op=volksstimme' target='_blank' onclick=\"window.open('popup_serverstory.php?op=volksstimme','popupserverstoryphp','scrollbars=yes,resizable=yes,width=700,height=500');return false;\">`m>> HÃ¶ret, hÃ¶ret! Die Volksstimme hat gesprochen! <<`0</a>`i`c`n`n", true);

/*if (getsetting('activategamedate','0')==1)*/ output("`c#4a4a4aW#505050e#565656i#5c5c5ct#626262e#686868r #6f6f6fu#757575n#7b7b7bt#818181e#878787n #8d8d8ds#949494t#949494e#979797h#9b9b9bt #9f9f9ff#a2a2a2o#a6a6a6l#aaaaaag#adadade#b1b1b1n#b5b5b5d#b8b8b8e#bcbcbcs#c0c0c0:`n
#4a4a4aW#4e4e4ei#525252r #575757s#5b5b5bc#5f5f5fh#646464r#686868e#6c6c6ci#717171b#757575e#797979n #7e7e7ed#828282e#868686n`0 `&".getgamedate()." #8b8b8bi#8f8f8fm #949494Z#949494e#969696i#999999t#9b9b9ba#9e9e9el#a0a0a0t#a3a3a3e#a6a6a6r #a8a8a8d#abababe#adadadr #b0b0b0D#b3b3b3r#b5b5b5a#b8b8b8c#bababah#bdbdbde#c0c0c0n.`c`0");
output("`c#4a4a4aD#505050i#565656e #5d5d5dU#636363h#6a6a6ar #707070d#777777e#7d7d7dr #838383T#8a8a8aa#909090v#949494e#979797r#9b9b9bn#9f9f9fe #a2a2a2z#a6a6a6e#aaaaaai#adadadg#b1b1b1t`0 `&".getgametime()." #b5b5b5U#b8b8b8h#bcbcbcr#c0c0c0.`c`0`");
output("`c#4a4a4aD#535353a#5c5c5cs #656565h#6f6f6fe#787878u#818181t#8a8a8ai#949494g#949494e #999999W#9f9f9fe#a4a4a4t#aaaaaat#afafafe#b5b5b5r#bababa:`0 `&".$settings['weather']."#c0c0c0.`c`0`n`n");


viewcommentary("astaros_hauptplatz","#303030I#343434n#393939s #3d3d3dG#424242e#464646s#4b4b4bp#4f4f4fr#545454Ã¤#545454c#5e5e5eh #686868k#727272o#7d7d7dm#878787m#919191e#9b9b9bn#a6a6a6:`0`n");



checkday();

page_footer();

?>


