<?php
/* special/gemtaxes.php
   Forest-Special by aragon
   Get gem-taxes from players, if gemautomat is empty
   This is a special for the gem-shop, which the gem-dealers come faster to gem-supply

   requires in systemsettings: gemautomat, gemjuwitax
   requires in shops_owner: acctid, shopid
*/


$gemtax=getsetting("gemtaxjuwi",0);

$sql="select acctid from shops_owner where shopid='1' and acctid=".$session['user']['acctid'];
$result=db_query($sql);
$row=db_fetch_assoc($result);

if($session['user']['acctid']==$row['acctid'] && $gemtax==0) $juwisteuer=0;
else $juwisteuer=1;

output("`^`c`bSteuerfahndung!`b`c`0`n`n");

output("`#Du gehst im Wald spazieren als ein Steuerfahnder auf dich zugelaufen kommt.`n");
if(getsetting("gemautomat",0)>=100 or $juwisteuer==0)
{ output("Er meint, bei dir scheint alles in Ordnung zu sein und deine Steuern scheinen zu stimmen.`n");
  output("Ein wenig verwirrt über die Situation gehst du dann weiter.`n`n`0");
}
else
{ output("Er meint, du hättest zu lange keine Steuern bezahlt und fordert von dir einen Ausgleich.`n");
  if($session['user']['gems']>0)
  { if(($session[user][gems]+$session[user][gemsinbank])>=1) $steuer=1;
    if(($session[user][gems]+$session[user][gemsinbank])>=50) $steuer=2;
    if(($session[user][gems]+$session[user][gemsinbank])>=100) $steuer=3;


    output("Du must ihm `$$steuer `#deiner heiß geliebten `\$Edelsteine`# überlassen.`n");
    output("Empört darüber ziehst du wieder weiter auf der Jagd nach anderen Monstern.`n`n");
    if ($session[user][gems]>=$steuer){
            output("`0`^Du verlierst $steuer Edelstein(e).`n`0");
            $session['user']['gems']-=$steuer;
    }else{
       $gems=$session[user][gems];
       $nochoffen=$steuer-$gems;
       $session['user']['gems']-=$gems;
       $session['user']['gemsinbank']-=$nochoffen;
       output("`0`^Du verlierst $gems Edelstein(e) und dir werden die restlichen $nochoffen Edelsteine aus dem Tresor genommen.`n`0");
    }
    $sqlc = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'Steuern',".$session[user][acctid].",'/me `9hat `^$steuer `9Gems Steuern bezahlt.`0')";
    db_query($sqlc);
    $gemsteuer=getsetting("gemautomat",0)+$steuer;
    savesetting("gemautomat",$gemsteuer);
  }
  else
  {
  output("`#Du hast aber nicht genug Edelsteine bei dir. Der Steuerfahnder lässt dich dieses Mal noch laufen. Beim nächsten Mal erwischt er dich bestimmt!`n`n`0");
  }
}

$session['user']['specialinc']="";

?>