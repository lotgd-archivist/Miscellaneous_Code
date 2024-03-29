
<?php
/**
* forest.php: Der Wald, Hauptkampfort
* @author LOGD-Core, modded by Drachenserver-Team
* @version DS-E V/2
*/

require_once('common.php');
$balance = getsetting('creaturebalance', 0.33);

// Handle updating any commentary that might be around.
addcommentary();

// MUSS NOCH VERSChOBEN WERDEN!
function hoelle_special (&$special) {

    global $session,$out;

    $dmg = round($session['user']['hitpoints'] * e_rand(1,3) * 0.01);

    if($dmg > 0) {

        $out .= '`n`0`b`$Die dunklen Kräfte der Hölle zerren mit aller Gewalt an dir und verrichten `^'.$dmg.' `$Schaden!`0`b`n`n';

        $session['user']['hitpoints'] -= $dmg;

        $special['special_uses']--;

        $special['diddamage'] = 1;
    }

}

if ($_GET['op']=='senddisciple')
{
    output($session['bufflist']['decbuff']['realname'].'`& schnappt sich deinen Beutel mit '.$session['user']['gold'].' Gold und bringt ihn eiligst zur Bank.`nDu genießt eine Weile die frische Waldluft und es dauert gar nicht lange bis dein Knappe wieder zurück ist.');
    $session['user']['goldinbank']+=$session['user']['gold'];
    $session['user']['gold']=0;
    addnav('Weiter','forest.php');
}
elseif ($_GET['op']=='darkhorse')
{
    $_GET['op']='';
    $session['user']['specialinc']='darkhorse.php';
}
elseif ($_GET['op']=='castle')
{
    $_GET['op']='';
    $session['user']['specialinc']='castle.php';
}
$fight = false;
page_header('Der Wald');

music_set('wald');

if ($access_control->su_check(access_control::SU_RIGHT_FORESTSPECIAL) && !empty($_GET['specialinc']))
{
    debuglog('rief Waldspecial '.$_GET['specialinc'].' manuell auf.');
    $session['user']['specialinc'] = $_GET['specialinc'];
}
if (!empty($session['user']['specialinc']))
{
    output('`c`b`^Etwas Besonderes!`0`b`c`n');
    $specialinc = $session['user']['specialinc'];
    $session['user']['specialinc'] = '';
    include('./special/'.$specialinc);
    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0)
    {
        forest(true);
        //output(serialize($session['allowednavs']));
    }
    page_footer();
    exit();
}
if ($_GET['op']=='run')
{
    if (e_rand()%3 == 0)
    {
        output ('`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n');
        $session['user']['reputation']--;

        // Hall-of-"Fame"
        $sql = 'UPDATE account_extra_info SET runaway=runaway+1 WHERE acctid='.$session['user']['acctid'];
        db_query($sql);
        // Ende

        $_GET['op']='';
    }
    else
    {
        output('`c`b`$Dir ist es nicht gelungen deinem Gegner zu entkommen!`0`b`c`n');
    }
}
if ($_GET['op']=='search')
{
    checkday();
    if ($session['user']['turns']<=0 && !isset($_GET['forest_special']))
    {
        output('`b`$Du bist zu müde, um heute den Wald weiter zu durchsuchen. Vielleicht hast du morgen mehr Energie dazu.`0`n`n`b');
        $_GET['op']='';
    }
    else
    {
        $session['user']['drunkenness']=round($session['user']['drunkenness']*0.9,0);
        $specialtychance = e_rand()%7;
        if ($specialtychance==0 || isset($_GET['forest_special'])){
            output('`c`b`^Etwas Besonderes!`0`b`c`n');
            // Skip the darkhorse, castle, goldmine if the user knows the way
            $sql_add = '';
            if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0)
            {
                $sql_add = ' AND filename != "darkhorse.php" ';
                $conf=unserialize($session['user']['donationconfig']);
                if ($conf['castle'])
                {
                    $sql_add.=' AND filename != "castle.php" ';
                }
                if ($conf['goldmine']>0)
                {
                    $sql_add.=' AND filename != "goldmine.php" ';
                }
            }
            $sql = "
                SELECT
                    `filename`
                FROM
                    `special_events`
                WHERE
                    `category_id`    =  '1'                                         AND
                    `prio`            <= '".e_rand(0,3)."'                         AND
                    `dk`             <= '".$session['user']['dragonkills']."'     AND
                    `released`        =  '1'
                    ".$sql_add."
                ORDER BY
                    RAND()
                LIMIT
                    1
            ";
            $res = db_query($sql);
            $row = db_fetch_object($res);
            $waldspecial = $row->filename;
            if (substr_count($waldspecial,'.php')>0)
            {
                $yy = $_GET['op'];
                $_GET['op']='';
                $session['specialinc_debug'] = $waldspecial;
                //specialinc hier setzen würde doppelten Aufruf für etliche einteilige Specials bedeuten
                //$session['user']['specialinc'] = $waldspecial;
                include("special/".$waldspecial);
                
                //db_query("UPDATE waldspecial SET anzahl=anzahl+1 WHERE filename='".$waldspecial."';");
                $_GET['op']=$yy;
            }
            else //if (!$waldspecial)
            {
                output('`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben. Beschwer dich bei ihm, nicht beim Programmierer. Es könnte natürlich auch sein, dass es kein Waldspecial gibt, das für dich freigeschalten ist... zu dumm...');
                addnav('Weiter','forest.php');
            }
            if (empty($nav))
            {
                forest(true);
            }
        }
        else
        {
            $session['user']['turns']--;
            $battle=true;

            $atk_mod = 0;
            $def_mod = 0;

            if (e_rand(0,2)==1)
            {
                $plev = (e_rand(1,5)==1?1:0);
                $nlev = (e_rand(1,3)==1?1:0);
            }
            else
            {
                $plev=0;
                $nlev=0;
            }
            if ($_GET['type']=='slum')
            {
                $nlev++;
                output('`$Du steuerst den Abschnitt des Waldes an, von dem du weißt, dass sich dort Feinde aufhalten, die dir ein bisschen angenehmer sind.`0`n');
                if($session['user']['dragonkills']>50){
                    $session['user']['reputation']-=4;
                }
                elseif($session['user']['dragonkills']>15){
                    $session['user']['reputation']-=2;
                }
                elseif($session['user']['dragonkills']>5){
                    $session['user']['reputation']--;
                }
                else{
                    if(e_rand(0,6)<=$session['user']['dragonkills']) $session['user']['reputation']--;
                }
            }
            if ($_GET['type']=='thrill'){
                $plev++;
                output('`$Du steuerst den Abschnitt des Waldes an, in dem sich Kreaturen deiner schlimmsten Albträume aufhalten, in der Hoffnung eine zu finden, die verletzt ist.`0`n');
                $session['thrillfight']++;
                if($session['thrillfight']>=2)
                {
                    $session['user']['reputation']++;
                    $session['thrillfight']=0;
                }

                $atk_mod = 1 + round($session['user']['dragonkills'] * 0.02);
                $def_mod = 1 + round($session['user']['dragonkills'] * 0.02);

            }
            if ($_GET['type']=='extreme')
            {

                $atk_mod = 3 + round($session['user']['dragonkills'] * 0.03);
                $def_mod = 3 + round($session['user']['dragonkills'] * 0.03);

                $atk_mod += ($session['user']['level'] - 1);

                $plev+=2;
                $nlev = 0;
                output('`$Du steuerst den Abschnitt des Waldes an, in dem sich so furchtbare Scheusale aufhalten, dass du schon bei dem Gedanken daran erschauderst!`0`n');
                $session['user']['reputation']++;

                $special = array('uses'=>10,'func'=>'hoelle_special','minbuff'=>0);
            }
            $targetlevel = ($session['user']['level'] + $plev - $nlev );
            if ($targetlevel<1) $targetlevel=1;
            //alter uncached Query
            //$sql = 'SELECT * FROM creatures WHERE creaturelevel = '.$targetlevel.' ORDER BY rand('.e_rand().') LIMIT 1';
            //$result = db_query($sql);
            //$badguy = db_fetch_assoc($result);
            //cached Query
            $sql = 'SELECT * FROM creatures WHERE creaturelevel = '.$targetlevel;
            $arr_creatures=cached_query($sql,'forestcreatures'.$targetlevel,false,86400);
            $badguy=$arr_creatures[e_rand(0,sizeof($arr_creatures)-1)];

            // Specialfähigkeiten
            if(isset($special))    {

                $badguy['special_uses'] = $special['uses'];
                $badguy['special_func'] = $special['func'];
                $badguy['special_minbuff'] = $special['minbuff'];

            }

            $expflux = round($badguy['creatureexp']/10,0);

            $expflux = e_rand(-$expflux,$expflux);
            $badguy['creatureexp']+=$expflux;

            //make badguys get harder as you advance in dragon kills.
            //output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
            $badguy['playerstarthp']=$session['user']['hitpoints'];
            $dk = 0;
            foreach($session['user']['dragonpoints'] as $val)
            {
                if ($val=='at' || $val=='de') $dk++;
            }

            $float_dk_bal = getsetting('forestdkbal',29);
            $int_hp_bal = getsetting('foresthpbal',6);

            $dk += (int)(($session['user']['maxhitpoints']-($session['user']['level']*10))/$int_hp_bal);

            $dk = round($dk * $float_dk_bal * 0.01, 0);

            $atkflux = e_rand(0, $dk);

            $defflux = e_rand(0, ($dk-$atkflux));

            $hpflux = ($dk - ($atkflux+$defflux)) * 5;

            $badguy['creatureattack']+=$atkflux + $atk_mod;
            $badguy['creaturedefense']+=$defflux + $def_mod;
            $badguy['creaturehealth']+=$hpflux;

            if($session['user']['acctid'] == 2310) {
                output('atk davor: '.$badguy['creatureattack']);
            }

            $float_forest_bal = getsetting('forestbal',1.5);

            // Bei Neulingen sollte das hier nicht zu stark durchschlagen
            if($session['user']['dragonkills'] == 0) {
                $int_balance = max($session['user']['balance_forest'] - 3, -2);
            }
            else {
                $int_balance = $session['user']['balance_forest'];
            }

            $badguy['creatureattack'] *= 1 + 0.01 * $float_forest_bal * $int_balance;
            $badguy['creaturedefense'] *= 1 + 0.01 * $float_forest_bal * $int_balance;

            if ($session['user']['race']=='zwg')
            {
                $badguy['creaturegold'] *= 1.1;
            }

            // Für Neulinge gibts ein bißchen Extragold..
            if($session['user']['dragonkills'] < 1) {
                $badguy['creaturegold'] *= 1.2;
            }
            else if($session['user']['dragonkills'] < 3) {
                $badguy['creaturegold'] *= 1.1;
            }

            $badguy['diddamage']=0;
            $session['user']['badguy']=createstring($badguy);
        }
    }
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
        $str_out = '`c`b`@Sieg!`0`b`c`n';

        if (getsetting('dropmingold',0))
        {
            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);
        }
        else
        {
            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);
        }
        $expbonus = round(
            ($badguy['creatureexp'] *
            (1 + .25 *
            ($badguy['creaturelevel']-$session['user']['level'])
            )
            ) - $badguy['creatureexp'],0
        );

        // Gold-Buff
        if($session['bufflist']['goldschaf']) {
            $str_out .= $session['bufflist']['goldschaf']['effectmsg'].'`n';
            $badguy['creaturegold'] = round($badguy['creaturegold']*$session['bufflist']['goldschaf']['goldfind']);
            $session['bufflist']['goldschaf']['rounds']--;
            if($session['bufflist']['goldschaf']['rounds'] <= 0) {
                $str_out .= $session['bufflist']['goldschaf']['wearoff'].'`n';
                unset($session['bufflist']['goldschaf']);
            }
        }

        if ($session['bufflist']['decbuff']['state']==14) {
            $badguy['creaturegold']*=2;
        }

        $str_out .= '`b`&'.$badguy['creaturelose'].'`0`b`n
        `b`$Du hast '.$badguy['creaturename'].'`$ erledigt!`0`b`n';

        //FEHU RUNE
        if( $session['bufflist']['`qFehu - Runenkraft'] ){
            $badguy['creaturegold'] = round($badguy['creaturegold']*1.10);
            $session['bufflist']['`qFehu - Runenkraft']['rounds']--;
            $str_out .= '`qDurch Die Fehu-Rune findest du mehr Gold!`n';
            if( !$session['bufflist']['`qFehu - Runenkraft']['rounds'] ){
                $str_out .= $session['bufflist']['`qFehu - Runenkraft']['wearoff'].'`n';
                buff_remove('`qFehu - Runenkraft');
            }
        }
        //END FEHU RUNE

        $badguy['creaturegold']=intval($badguy['creaturegold']);
        $str_out .= '`#Du erbeutest `^'.$badguy['creaturegold'].'`# Goldstücke!`n';


        // GILDENMOD
        require_once(LIB_PATH.'dg_funcs.lib.php');
        if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {

            $tribute = dg_member_tribute($session['user']['guildid'],$badguy['creaturegold'],0);
            dg_save_guild();
            if($tribute[0] > 0) {
                $str_out .= 'Davon zahlst du `^'.$tribute[0].'`# Goldstücke Tribut an deine Gilde.`n';
                $badguy['creaturegold'] -= $tribute[0];
            }
        }
        // END GILDENMOD

        //find something
        $findit=e_rand(1,30);

        // Beutebuff
        if($session['bufflist']['beutegeier']) {
            $str_out .= $session['bufflist']['beutegeier']['effectmsg'].'`n';

            if(e_rand(1,5) == 1) {
                $findit = 23;
            }
            else {
                $str_out .= $session['bufflist']['beutegeier']['failmsg'].'`n';
            }

            $session['bufflist']['beutegeier']['rounds']--;
            if($session['bufflist']['beutegeier']['rounds'] <= 0) {
                $str_out .= $session['bufflist']['beutegeier']['wearoff'].'`n';
                unset($session['bufflist']['beutegeier']);
            }

        }

        //Knappen helfen beim durchsuchen der Gegner
        if($session['bufflist']['decbuff']['state']==11 || $session['bufflist']['decbuff']['state']==14)
        {
            $str_out .= '`#'.$session['bufflist']['decbuff']['realname'].'`# hilft dir beim Durchsuchen des Gegners`&`n';
        }

        if (($findit == 2 && e_rand(1,2) == 2) || (($findit <= 14) && ($findit >= 12) && ($session['bufflist']['decbuff']['state']==11)))
        { //gem
            $str_out .= '`&Du findest einen Edelstein!`n`#';
            $session['user']['gems']++;
        }
        elseif ($findit == 5) {
            $session['user']['donation']++;
        }

        elseif ($findit == 23)
        { //item

            $item_hook_info['chance'] = item_get_chance();

            if($session['bufflist']['beutegeier']) {
                $item_hook_info['chance'] = max($item_hook_info['chance']-1,1);
            }

            $res = item_tpl_list_get( 'find_forest='.$item_hook_info['chance'] , 'ORDER BY RAND('.e_rand().') LIMIT 1' );

            if( db_num_rows($res) ) {

                $item = db_fetch_assoc($res);

                if( !empty($item['find_forest_hook']) ) {
                    item_load_hook( $item['find_forest_hook'] , 'find_forest' , $item );
                }

                if(!$item_hook_info['hookstop']) {

                    if ( item_add( $session['user']['acctid'], 0, $item ) ) {
                        $str_out .= '`n`qBeim Durchsuchen von '.$badguy['creaturename'].' `qfindest du `&'.$item['tpl_name'].'`q!
                                    '.(!empty($item['tpl_description']) ? '('.$item['tpl_description'].')' : '').'`n`n`#';
                    }

                }

            }

        }
        elseif ($findit == 25 && e_rand(1,6)==2)
        { // armor
            unset ($item);
            $sql = 'SELECT * FROM armor WHERE defense<='.$session['user']['level'].' ORDER BY rand('.e_rand().') LIMIT 1';
            $result2 = db_query($sql);
            if (db_num_rows($result2)>0)
            {
                $row2 = db_fetch_assoc($result2);
                $row2['value']=round($row2['value']/10);

                $item['tpl_name'] = addslashes($row2['armorname']);
                $item['tpl_value1'] = addslashes($row2['defense']);
                $item['tpl_gold'] = addslashes($row2['value']);
                $item['tpl_description'] = 'Gebrauchte Level '.$row2['level'].' Rüstung mit '.$row2['defense'].' Verteidigung.';

                item_add($session['user']['acctid'],'rstdummy',$item);

                $str_out .= '`n`QBeim Durchsuchen von '.$badguy['creaturename'].' `Qfindest du die Rüstung `%'.$row2['armorname'].'`Q!`n`n`#';
            }
        }
        elseif ($findit == 26 && e_rand(1,6)==2)
        { // weapon
            unset ($item);
            $sql = 'SELECT * FROM weapons WHERE damage<='.$session['user']['level'].' ORDER BY rand('.e_rand().') LIMIT 1';
            $result2 = db_query($sql);
            if (db_num_rows($result2)>0)
            {
                $row2 = db_fetch_assoc($result2);
                $row2['value']=round($row2['value']/10);

                $item['tpl_name'] = addslashes($badguy['creatureweapon']);
                $item['tpl_value1'] = $row2['damage'];
                $item['tpl_gold'] = $row2['value'];
                $item['tpl_description'] = 'Gebrauchte Waldmonster-Waffe mit '.$row2['damage'].' Angriff.';

                item_add($session['user']['acctid'],'waffedummy',$item);

                $str_out .= '`n`QBeim Durchsuchen von '.$badguy['creaturename'].' `Qfindest du die Waffe `%'.$badguy['creatureweapon'].'`Q!`n`n`#';
            }
        }

        if ($expbonus>0)
        {
            $str_out .= '`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^'.$expbonus.'`# Erfahrungspunkte! `n('.$badguy['creatureexp'].' + '.abs($expbonus).' = '.($badguy['creatureexp']+$expbonus).') ';
        }
        else if ($expbonus<0)
        {
            $str_out .= '`#*** Weil dieser Kampf so leicht war, verlierst du `^'.abs($expbonus).'`# Erfahrungspunkte! `n('.$badguy['creatureexp'].' - '.abs($expbonus).' = '.($badguy['creatureexp']+$expbonus).') ';
        }

        $str_out .= '`#Du bekommst insgesamt `^'.($badguy['creatureexp']+$expbonus).'`# Erfahrungspunkte!`n`0';

        $session['user']['gold']+=$badguy['creaturegold'];
        $session['user']['experience']+=($badguy['creatureexp']+$expbonus);
        $creaturelevel = $badguy['creaturelevel'];
        $_GET['op']='';

        if ($badguy['diddamage']!=1){
            if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel){
                $str_out .= '`c`b`&~~ Perfekter Kampf! ~~`0`b`n`$Du erhältst eine Extrarunde!`0`c`n';
                $session['user']['turns']++;

                // Wenn Gegner-Level unter unserem liegt und unsere Waldbalance ohnehin schon schwieriger ist:
                // 80% Chance, dass Schwierigkeit nicht steigt
                if($creaturelevel < $session['user']['level'] && e_rand(1,5) != 3 && $session['user']['balance_forest'] > 0) {

                }
                else {

                    if($session['user']['balance_forest'] < 0) {
                        $session['user']['balance_forest']=ceil($session['user']['balance_forest']*0.5);
                    }
                    else {
                        $session['user']['balance_forest']++;
                    }
                    $session['user']['balance_forest'] = min(20,$session['user']['balance_forest']);

                }

                if ($expbonus>0){
                    $session['user']['donation']+=1;
                }
            }
            else
            {
                $str_out .= '`c`b`&~~ Perfekter Kampf! ~~`b`$`nEin schwierigerer Kampf hätte dir eine extra Runde gebracht.`c`n`0';
            }
        }
        $dontdisplayforestmessage=true;
        $badguy=array();

        output('`0');
        headoutput($str_out.'<hr>');
    }
    else if($defeat)
    {

        // Wenn Level des Gegners max. 1 über dem des Spielers
        if($session['user']['level']>=$creaturelevel-1) {
            if($session['user']['balance_forest'] > 1) {
                $session['user']['balance_forest']=floor($session['user']['balance_forest']*0.5);
            }
            else {
                $session['user']['balance_forest']--;
            }
            $session['user']['balance_forest'] = max(-10,$session['user']['balance_forest']);
        }
        // END Balancing

        $str_loose_log = 'Gld: '.$session['user']['gold'];

        // item
        $item_hook_info['min_chance'] = item_get_chance();

        $res = item_list_get( 'owner='.$session['user']['acctid'].' AND deposit1=0 AND loose_forest_death='.$item_hook_info['min_chance'] , 'ORDER BY RAND() LIMIT 1' );

        if( db_num_rows($res) ) {

            $item = db_fetch_assoc($res);

            if( $item['forest_death_hook'] != '' ) {
                item_load_hook( $item['forest_death_hook'] , 'forest_death' , $item );
            }

            if(!$item_hook_info['hookstop']) {
                if ( item_delete( ' id='.$item['id'] ) ) {
                    $lost_str = '`4Du verlierst `^'.$item['name'].'`4!`n';
                    $str_loose_log .= ',Item: '.$item['name'];
                }
            }

        }

        // Gegnerspott, News
        addnews('`%'.$session['user']['name'].'`5 wurde im Wald von '.$badguy['creaturename'].'`5 niedergemetzelt.`n'.get_taunt());
        // END Gegnerspott, News

        $arr_results = killplayer(100, 10, true, 'news.php', 'Tägliche News');
        if($arr_results['disciple']) {
            headoutput('`n`^'.$arr_results['disciple']['name'].' `4wird von `%'.$badguy['creaturename'].'`4 überwältigt und verschleppt!`n`n');
            $str_loose_log .= '; Knappe';
        }

        debuglog('Waldtod: '.$str_loose_log);

        output('`0');
        headoutput('`c`b`$Niederlage!`0`b`c`nDu wurdest von `%'.$badguy['creaturename'].'`0 niedergemetzelt!!!`n
        `4Dein ganzes Gold wurde dir abgenommen!`n
        10% deiner Erfahrung hast du verloren!`n
        '.$lost_str.'
        Du kannst morgen weiter kämpfen.`0<hr>');

        page_footer();
    }

    else
    {
        fightnav();
    }
}

if (empty($_GET['op']))
{
    // Need to pass the variable here so that we show the forest message
    // sometimes, but not others.
    forest($dontdisplayforestmessage);
}

page_footer();
?>

