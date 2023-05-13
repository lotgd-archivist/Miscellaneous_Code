
<?php

// 08082006
/*
Stierkampf für brave Bürger
by Salator (salator [-[at]-] gmx.de)
basierend auf pvp.php und trophy.php
Aktivierung: in pvparena:    addnav('Stierkampfarena','bullfight.php');
*/
require_once "common.php";
require_once(LIB_PATH.'dg_funcs.lib.php');

$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",time()-$pvptime);
$crimedate=date("Y-m-d H:i:s",time()-(21*86400));

page_header('Stierkampf!');
if ($_GET['op']=='' && $_GET['act']!='attack')
{
    checkday();
    output('`4Du machst dich auf in die Arena, wo einige wilde Stiere warten.`n`n');
    $row=user_get_aei('last_crime');
    if ($row['last_crime'] < $crimedate)
//    if ($session['user']['daysinjail']<=$session['user']['dragonkills']/10)
    {
        if ($session['user']['age'] > getsetting('maxagepvp',50) )
        {
            output('`nDu spürst allerdings instinktiv, dass es wohl besser wäre, erst eine richtige Heldentat zu vollbringen.');
        }
        else
        {
            output('Du hast noch Kraft für `^'.$session['user']['playerfights'].'`4 Stierkämpfe.');
            addnav('Stiere auflisten','bullfight.php?op=list');
        }
    }
    else
    {
        output('Stierkampf ist etwas für ehrenwerte Bürger, du mußt dir eingestehen in den letzten Wochen nicht ehrenwert gelebt zu haben.');
    }
    addnav('Zurück');
    addnav('D?Zum Dorfplatz','village.php');
    addnav('A?Zurück zur Arena','pvparena.php');
}
else if ($_GET['op']=="list")
{
    checkday();
    $days = getsetting('pvpimmunity', 5);
    $exp = getsetting('pvpminexp', 1500);
    $dk = round($session['user']['dragonkills']*0.9);
    if ($dk>130) $dk=130; //Chance für alte Spieler
    if ($dk==0) $dk=1; //Neulingsflut unterbinden
    
    $sql = 'SELECT name,alive,location,profession,sex,level,laston,loggedin,login,pvpflag,acctid,dragonkills FROM accounts WHERE
    (locked=0) AND
    (dragonkills >= '.$dk.') AND
    (level >= '.($session['user']['level']-1).' AND level <= '.($session['user']['level']+2).') AND
    (alive=0 AND location='.USER_LOC_FIELDS.') AND
    (race!=\'\' AND specialty>0) AND
    (pvpflag<>\'5013-10-06 00:42:00\' AND pvpflag<>\'1986-10-06 00:42:00\') AND
    !('.user_get_online(0,0,true).') AND
    (hitpoints = 0)
    ORDER BY level DESC LIMIT 30';

/*test
    $sql = "SELECT accounts.name,alive,location,profession,sex,level,laston,loggedin,login,pvpflag,acctid,dragonkills FROM accounts  WHERE
    locked=0 AND
    (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
    (race!='' AND specialty>0) AND
    loggedin=0 AND
    (acctid <> ".$session['user']['acctid'].")
    ORDER BY level DESC";
//*/
    $result = db_query($sql);
    
    output("`c<table bgcolor='#999999' border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td width='200'>Name</td><td width='45'>Level</td><td width='45'>Alter</td><td>Ops</td></tr>");
    
    $count = db_num_rows($result);
    
    if ($count == 0)
    {
        output('<tr><td colspan="4" class="trlight">`iLeider ist gerade kein Stier verfügbar, mit dem ein fairer Kampf möglich wäre!`0`i</td></tr>');
    }
    
    for ($i=0; $i<$count; $i++)
    {
        $row = db_fetch_assoc($result);
        $str_out.='<tr class="'.($i%2?'trlight':'trdark').'"><td>'.ucwords(strtolower(strrev($row['login']))).'</td><td>'.$row['level'].'</td><td>'.$row['dragonkills'].'</td><td>[ ';
        if ($row['pvpflag']>$pvptimeout)
        {
            $str_out.='`ierschöpft`i ]</td></tr>';
        }
        else
        {
                $str_out.='<a href="bullfight.php?act=attack&id='.$row['acctid'].'">Kampf</a> ]</td></tr>';
                addnav('','bullfight.php?act=attack&id='.$row['acctid']);
        }
    }
    output($str_out.'</table>`c');
    addnav('Stiere auflisten','bullfight.php?op=list');
    addnav('D?Zurück zum Dorf','village.php');
    addnav('A?Zurück zur Arena','pvparena.php');
    
}
else if ($_GET['act'] == 'attack')
{
    $sql = 'SELECT login AS creaturename,
    level AS creaturelevel,
    gold AS creaturegold,
    experience AS creatureexp,
    maxhitpoints AS creaturehealth,
    attack AS creatureattack,
    defence AS creaturedefense,
    pvpflag,
    dragonkills,
    acctid
    FROM accounts
    WHERE acctid='.$_GET['id'];
    
    $result = db_query($sql);
    if (db_num_rows($result)>0)
    {
        $row = db_fetch_assoc($result);
        $row['creaturename']='Stier '.ucwords(strtolower(strrev($row['creaturename'])));
        $row['creatureweapon']='Hörner';
        $row['creaturehealth']=e_rand($row['creaturehealth']*0.8,$row['creaturehealth']*1.1);
        if ($row['creaturegold']==0 || $row['creaturegold']>1000)
        {
            $row['creaturegold']=e_rand($row['creaturelevel']*10,$row['creaturelevel']*50);
        }
        if ($session['user']['playerfights']>0)
        {
            user_update(array('pvpflag'=>array('sql'=>true,'value'=>'now()')),$row['acctid']);
            
            $battle=true;
            $row['pvp']=1;
            $row['creatureexp'] = round($row['creatureexp'],0);
            $row['playerstarthp'] = $session['user']['hitpoints'];
            $session['user']['badguy']=createstring($row);
            $session['user']['playerfights']--;
            $session['buffbackup']='';
            $session['user']['buffbackup']='';
            if (!$session['user']['prefs']['nosounds'])
            {
                output('<embed src="media/bigbong.wav" width=10 height=10 autostart=true loop=false hidden=true volume=100>');
            }
        }
        else
        {
            output('`4Du bist zu müde, um heute einen weiteren Stierkampf zu riskieren.');
        }
    }
    if (!$battle)
    {
        addnav('D?Zurück zum Dorf','village.php');
        addnav('A?Zurück zur Arena','pvparena.php');
    }
}
if ($_GET['op']=='take') //Trophäe mitnehmen
{
    $name=rawurldecode($_GET['who']);
    $dks=$_GET['dks'];
    $id=$_GET['id'];

    $value=($dks+1)*25;
    if($_GET['set']==1)
    {
        output('`3Du machst dich an deine blutige Arbeit......`nDer Kopf von '.$name.'`3 verschwindet kurze Zeit später in deinem Rucksack.`n`n');
    }
    else
    {
        output('`3Du zückst deine Mitgliedskarte der Jägerhütte. Während man dir 3 Punkte streicht macht sich ein geübter Präparator an seine blutige Arbeit...`nDer Kopf von '.$name.'`3 verschwindet kurze Zeit später in deinem Rucksack.`n`n');
        $session['user']['donationspent']+=3;
        debuglog('gab 3 DP für den Kopf von '.$name);
    }

    $item['tpl_name'] = addslashes('Der Kopf von '.$name);
    $item['tpl_gold'] = $value;
    $item['tpl_value1'] = $dks;
    $item['tpl_value2'] = 7;
    $item['tpl_hvalue'] = $id;
    $item['tpl_description'] = addslashes('Der Kopf von '.$name.'`0. Erworben in einem fairen Kampf.');

    item_add($session['user']['acctid'],'trph',$item,true);

    addnav('D?Zurück zum Dorf','village.php');
    addnav('A?Zurück zur Arena','pvparena.php');

}
if ($_GET['op']=='run')
{
    output('Deine Ehre verbietet es dir, wegzulaufen.');
    $battle=true;
}
if ($_GET['skill']!='')
{
    output('Deine Ehre verbietet es dir, deine besonderen Fähigkeiten einzusetzen.');
    $_GET['skill']='';
}
if ($_GET['op']=='fight' || $_GET['op']=='run')
{
    $battle=true;
}
if ($battle)
{
    include('battle.php');
    if ($victory)
    {
        $exp = round(getsetting('pvpattgain',10)*$badguy['creatureexp']/100,0);
        $expbonus = round(($exp * (1+.1*($badguy['creaturelevel']-$session['user']['level']))) - $exp,0);
        output('`b`&'.$badguy['creaturelose'].'`0`b`n');
        output('`b`$Du hast '.$badguy['creaturename'].' besiegt!`0`b`n');
        output('`#Du erbeutest `^'.$badguy['creaturegold'].'`# Gold!`n');
        $session['user']['gold']+=$badguy['creaturegold'];

        if ($expbonus>0)
        {
            output('`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^'.$expbonus.'`# Erfahrungspunkte!`n');
            $session['user']['reputation']++;
        }
        elseif ($expbonus<0)
        {
            output('`#*** Weil dieser Kampf so leicht war, verlierst du `^'.abs($expbonus).'`# Erfahrungspunkte!`n');
            $session['user']['reputation']--;
        }
        output('Du bekommst insgesamt `^'.($exp+$expbonus).'`# Erfahrungspunkte!`n`0');
        // start: xp-loss for killing lowdk players
        $xplossfactor = 0;
        $mindks = getsetting('pvpmindkxploss',10);
        $dksdiff = $session['user']['dragonkills'] - $badguy['dragonkills'];
        if ($dksdiff>$mindks)
        {
            $xplossfactor = 1 - (($badguy['dragonkills'] + 3) / ($session['user']['dragonkills']));
            $loss = round(($exp+$expbonus) * $xplossfactor);
            output('`#Davon werden dir `$'.abs($loss).' `#Erfahrungspunkte abgezogen, weil der Stier so jung war.');
        }
        // end: xp-loss for killing lowdk players
        $session['user']['experience']+=($exp+$expbonus-$loss);
        
        addnews('`@'.$session['user']['name'].'`3 gewann einen Stierkampf gegen `4'.$badguy['creaturename'].'`3.');
        $sql='UPDATE account_extra_info SET bullfightwins=bullfightwins+1 WHERE acctid='.$session['user']['acctid'];
        db_query($sql);
        
        addnav('Zurück zum Dorf','village.php');

        //Trophäensammler, mit Präparierset kostenlos, sonst3DP
        $id=$badguy['acctid'];
        if (($session['user']['donation']-$session['user']['donationspent'])>=3 && ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='7' AND name LIKE '%".addslashes($badguy['creaturename'])."%'") == 0)) //Suche nach Name ist beabsichtigt
        {
            output('`n`n`^Du überlegst dir, ob du dir nicht vielleicht ein Andenken an diesen Kampf für deinen Schaukasten mitnehmen solltest...');
            addnav('Trophäe');
            $who=rawurlencode($badguy['creaturename']);
            $rowextra = user_get_aei('trophyhunter');
            if ($rowextra['trophyhunter']==1)
            {
                addnav('Kopf mitnehmen','bullfight.php?op=take&set=1&who='.$who.'&id='.$id.'&dks='.$badguy['dragonkills']);
            }
            else
            {
                output('`n`$Die Haltbarmachung kostet dich 3 Donationpoints!');
                addnav('Kopf mitnehmen `$(3DP)','bullfight.php?op=take&who='.$who.'&id='.$id.'&dks='.$badguy['dragonkills']);
            }
        }

        $badguy=array();
        
    }
    else if ($defeat)
    {
        addnav('D?Zurück zum Dorf','village.php');
        addnav('N?Tägliche News','news.php');
        
        addnews('`%'.$session['user']['name'].'`5 hat einen Stierkampf gegen`% '.$badguy['creaturename'].' `5  verloren.`n'.get_taunt(false));
    
        $session['user']['gold']=0;
        $session['user']['hitpoints']=1;
        $session['user']['experience']=round($session['user']['experience']*(100-getsetting('pvpattlose',15))/100,0);
        $session['user']['badguy']="";
        $session['user']['playerfights']=0;
        $session['user']['turns']=0;
        
        output('`b`&Du wurdest von `%'.$badguy['creaturename'].' `&besiegt!!!
        `n`4Alles Gold, das du bei dir hattest, hast du verloren!
        `n'.getsetting('pvpattlose',15).'% deiner Erfahrung ging verloren!
        `nDu bist zu schwach um heute noch zu kämpfen.');
        $session['user']['reputation']--;
        $badguy=array();
    }
    else
    {
        fightnav(false,false);
    }
}
page_footer();
?>

