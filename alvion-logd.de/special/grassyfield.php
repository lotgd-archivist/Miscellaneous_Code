
<?php//Original idea by Sean McKillion//modifications by Eric Stevens//further modifications by JT Traubif ($_GET['op']=="return") {    $session['user']['specialmisc']="";    $session['user']['specialinc']="";    redirect("forest.php");}addcommentary();checkday();output("`n`c`#Du stolperst auf eine grasbewachsene Lichtung`c");addnav("Zurück in den Wald","forest.php?op=return");if ($session['user']['specialmisc'] != "Hier gibt's nichts zu sehen.") {    if ($session[user][hashorse]>0){        $config = unserialize($session['user']['donationconfig']);        $mountfights=0;        $begleiter=unserialize($playermount['mountbuff']);        $runden=round($begleiter['rounds']/10);        if (!is_array($config['mountfights'])) $config['mountfights']=array();        reset($config['mountfights']);        while (list($key,$val)=each($config['mountfights'])){            $config['mountfights'][$key]['left']--;            $mountfights++;            if ($val['left']>1){            }else{                unset($config['mountfights'][$key]);            }        }        $begleiter['rounds']=round($begleiter['rounds']*(1+$mountfights/10));        $buff = $begleiter;        if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {            if ($playermount['partrecharge']) {                output("`n`n{$playermount['partrecharge']}");            } else {                output("`n`n`&Du erlaubst deinem {$playermount['mountname']}`&, sich hier zu stärken und herum zu tollen.");            }        } else {            if ($playermount['recharge']) {                output("`n`n{$playermount['recharge']}");            } else {                output("`n`n`&Du erlaubst deinem {$playermount['mountname']}`&, zu jagen und sich auszuruhen.");            }        }        if($session['bufflist']['mount']['rounds']>0) $session['bufflist']['mount']['rounds']=$buff['rounds'];        else $session['bufflist']['mount']=$buff;        if ($session['user']['gott']==6){            if($session['user']['hashorse']>0){                $session['bufflist']['mount']['rounds']+=floor($session['bufflist']['mount']['rounds']/10);            }        }        if ($session[user][hitpoints]<$session[user][maxhitpoints]){            output("`n`^Dein Nickerchen hat dich vollständig geheilt!");            $session[user][hitpoints] = $session[user][maxhitpoints];        }        $session['user']['turns']--;        output("`n`n`^Du verlierst einen Waldkampf für heute.");    } else {        output("`n`n`&Du beschließt, dir einen Moment Zeit zu nehmen und deinen armen Füßen eine kurze Pause von deinen riskanten Abenteuern zu gönnen. Du genießt die wunderschöne Umgebung.");        output("`n`n`^Du regenerierst vollständig!");        if ($session[user][hitpoints]<$session[user][maxhitpoints])            $session[user][hitpoints] = $session[user][maxhitpoints];    }    $session['user']['specialmisc'] = "Hier gibt's nichts zu sehen.";} else {    output("`n`n`&Du ruhst dich eine Weile hier aus und genießt die Sonne und den Schatten.");}$session['user']['specialinc'] = "grassyfield.php";output("`n`n`@Rede mit den anderen, die hier herumlungern.`n");viewcommentary("grassyfield","Hinzufügen",10,"sagt",1,1);?>

