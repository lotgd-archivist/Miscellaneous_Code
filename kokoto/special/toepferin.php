<?php

/*
* Die Töpferin (toepferin.php)
* written by Darkness
* http://darkness.logd.cwsurf.de/
* überarbeitet von Tidus www.kokoto.de
* SQL - Befehl:
    CREATE TABLE `figures` (
      `id` int(11) NOT NULL auto_increment,
      `creaturename` varchar(50) default NULL,
      `creaturelevel` int(11) unsigned default NULL,
      `creatureweapon` varchar(50) default NULL,
      `creaturehealth` int(11) unsigned default NULL,
      `creatureattack` int(11) unsigned default NULL,
      `creaturedefense` int(11) unsigned default NULL,
      `gems` int(11) unsigned default NULL,
      PRIMARY KEY  (`id`)
    ) TYPE=MyISAM AUTO_INCREMENT=1 ;
	
*/

output("`c`b`7Die Töpferin`b`c`n`t");
$session['user']['specialinc']='toepferin.php';


if ($_GET['op']=='prefight') {
	$session['user']['turns']--;
	$sql = "SELECT * FROM figures ORDER BY rand(".mt_rand().") LIMIT 1";
	$result = db_query($sql);
	if (db_num_rows($result)){
		$badguy = db_fetch_assoc($result);
		output("Als du der Figur gegenüberstehst, fällt dir auf, dass sie eine enorme Ähnlichkeit mit ".($session['user']['name']==$badguy['creaturename']?"`bdir`b":$badguy['creaturename'])." `that.`n");
	}else{
		$dk = 0;
        foreach($session['user']['dragonpoints'] as $key => $val){
            if ($val=="at" || $val=="de" || $val=="hp") $dk++;
        }
        $max = round($dk13);
        $min = round($dk23);
        $newdk = $dk;
        $flux = array("atk"=>0,"def"=>0);
		foreach($flux as $key => $val){
            $flux[$key] = e_rand(0,$newdk);
            $flux[$key] = max($flux[$key],$max);
            $flux[$key] = min($flux[$key],$min);
            $newdk-=$flux[$key];
        }
        $lv = $session['user']['level'];
        $hpflux = round(($session['user']['maxhitpoints']$lv10)2);
        $hpflux += ($dk  $newdk)  6;
		$badguy = array(
			"creaturename"=>"`7Tonfigur`0",
            "creaturelevel"=>$lve_rand(1,4),
            "creatureweapon"=>"`7Tonschwert`0",
            "creatureattack"=>$lv4$flux['atk'],
            "creaturedefense"=>$lv4$flux['def'],
            "creaturehealth"=>$lv30$hpflux,
            "diddamage"=>0
		);
	}
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='fight';
        $battle=true;
}else if ($_GET['op']=='fight'){
	$battle=true;
}else{
	output('Du stolperst im Wald über eine merkwürdig aussehende Hütte. Du schleichst dich heimlich hinein und schließt die Tür hinter dir. Vor dir steht eine hübsche junge Frau, die dich mit einem leeren Blick anstarrt. Du läufst auf sie zu, als sie plötzlich zu Lachen beginnt. Aus einer anderen Tür kommt eine Tonfigur hevor. Du bekommst etwas Angst und versuchst zu fliehen, doch die Tür lässt sich nicht öffnen. Dir bleibt wohl nichts anderes übrig als gegen die Figur zu kämpfen.`n');
	addnav('Kämpfe!','forest.php?op=prefight');
}
if ($battle) {
	include("battle.php");
    if ($victory){
        output('`n`n`tDu hast die Tonfigur geschlagen. Sie zerfällt vor deinen Augen zu Tonscherben. ');
        if (isset($badguy['id'])){
            $sql = "DELETE FROM figures WHERE id='".$badguy['id']."'";
            db_query($sql);
            $gems = $badguy['gems'];
            output("Von nun an wird sie niemanden mehr angreifen können.`nDu durchsuchst die Scherben und findest darin `^$gems `tEdelstein".($gems==1?"":"e")."!");
            $session['user']['gems']+=$gems;
        }else{
            output("Du durchsuchst die Scherben der Tonfigur doch findest darin nichts.");
            $sql = "INSERT INTO items(name,class,owner,gold,description) VALUES ('Tonscherben','Beute',".$session['user']['acctid'].",".mt_rand(50,150).",'Die Überreste einer Tonfigur, die du besiegt hast.')";
            db_query($sql) or die(db_error($sql));
        }
        $exp = $session['user']['experience'];
        $exp = mt_rand($exp.01,$exp.05);
        $session['user']['experience'] += $exp;
        $session['user']['reputation']++;
        output("`nDu erhältst `^$exp `tErfahrungspunkte.`n`nDie junge Frau scheint spurlos verschwunden zu sein.");
        addnews("`7".$session['user']['name']." `that im Wald die Tonfigur einer Töpferin erschlagen!");
        $badguy=array();
        $session['user']['badguy']='';
        $session['user']['specialinc']='';
  	} elseif ($defeat){
  		$gems = mt_rand(1,3);
  		output("`n`n`tDie Tonfigur schlägt dich nieder! Du gehst bewusstlos zu Boden...`nAls du im Wald wieder aufwachst bemerkst du, dass dir ");
  		if ($session['user']['gems']>=$gems){
	  		$session['user']['gems']-=$gems;
	  		output("`^$gems `tEdelstein".($gems==1?"":"e"));
	  	}else{
	  		$gems = $session['user']['gems'];
	  		$session['user']['gems'] = 0;
	  		output("alle deine Edelsteine");
	  	}
	  	$session['user']['hitpoints'] = 1;
  		output(" gestohlen wurden!`nWeil du solange bewusstlos warst, verlierst du ");
		$turns = mt_rand(1,2);
  		if ($session['user']['turns']>$turns){
  			$session['user']['turns'] -= $turns;
  			output($turns==1?"einen Waldkampf.":"`^$turns `tWaldkämpfe.");
  		}else{
  			$session['user']['turns'] = 0;
  			output("alle deine Waldkämpfe.");
  		}
  		// Neue Tonfigur
  		$sql = "SELECT * FROM figures WHERE creaturename='".$session['user']['name']."'";
  		$result = db_query($sql);
  		if (db_num_rows($result)){
  			$row = db_fetch_assoc($result);
  			$weapon = array($session['user']['weapon'],$row['creatureweapon']);
  			$figure = array(
                "creaturelevel"=>max($session['user']['level'],$row['creaturelevel']),
                "creatureweapon"=>$weapon[e_rand(0,1)],
                "creatureattack"=>max($session['user']['attack'],$row['creatureattack']),
                "creaturedefense"=>max($session['user']['defence'],$row['creaturedefense']),
                "creaturehealth"=>max($session['user']['maxhitpoints'],$row['creaturehealth']),
                "gems"=>max($gems,$row['gems']),
            );
  			$sql = "UPDATE figures SET ";
			foreach($figure as $key => $val){
                $sql.="$key='".mysql_real_escape_string($val)."', ";
            }
            $sql = substr_c($sql,0,strlen_c($sql)2);
            $sql.=" WHERE id=".$row['id'];
  		}else{
            $figure = array(
                "creaturename"=>$session['user']['name'],
                "creaturelevel"=>$session['user']['level'],
                "creatureweapon"=>$session['user']['weapon'],
                "creatureattack"=>$session['user']['attack'],
                "creaturedefense"=>$session['user']['defence'],
                "creaturehealth"=>$session['user']['maxhitpoints'],
                "gems"=>$gems
            );
		    foreach($figure as $key => $val){
                $insert .= "`$key`, ";
                $values .= "'$val', ";
            }
            $insert = substr_c($insert,0,strlen_c($insert)2);
            $values = substr_c($values,0,strlen_c($values)2);
            $sql = "INSERT INTO figures ($insert) VALUES ($values)";
        }
        db_query($sql);
        addnews("`7".$session['user']['name'].' `twurde im Wald von einer Tonfigur besiegt!');
        $badguy=array();
        $session['user']['badguy']='';
        $session['user']['specialinc']='';
	} else {
		fightnav(true,false);
	}
}
?>
