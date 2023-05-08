
<?php
//
// ----------------------------------------------------------------------------
//
// Clans/Guilds Pages: guildclanfuncs.php
// CR#Dasher#004
// 25th March 2004
// Version: 0.98 beta
// The latest version is always runnning at: www.sembrance.uni.cc/rpg
// (c) Dasher [david.ashwood@inspiredthinking.co.uk] 2004
// This module is relased under the LOTGD GNU public license
// You may change/modify this module and it's associated modules as you wish but
// this copyright MUST be retained.
//
// I would apprechiate feedback/updates on how it works and changes you make.
// Dasher
//
// ----------------------------------------------------------------------------
// Changes by Gargamel
// - quite a lot of functions are grouped and moved to separate files, which
//   must be available in a lib "./guilds"
// - Introducing a bunch of variables for all central expressions in the mod.
//   Should be used for easy translation and adjustment of the current names.
// - weapons and armor can now be defined in guilds and clans up to a maximum
//   of 100 each. Basically 10 are available. Increase that number, if needed.
// - if any text of the application form is zensored by badwords, the respetive
//   phrase is show as §$%&. So the applicant gets a clear indication what has
//   been wrong.
// - increased number of possible benefits from sitepoints.
//   - buy your house cheaper, if discount is granted by sitepoints
//   - buy and feed your mount with discount
// - Guilds and Clans fully separated. This has lead to some new functions
//   having "_guild" or "_clan" in their name.
//   (funcs based upon original code of Dasher!)
// - Guilds and Clans can now be listed on one page in respective sections.
//
// ToDO:
// - PvP Purchase Discount option with sitepoints for guilds and/or clans
//
// Install instruction:
// - copy this file into your LOGD main folder.
//
// Contact Gargamel:
// eMail: gargamel@rabenthal.de or gargamel@silienta-logd.de
// Forum: Gargi at dragonprime
// ----------------------------------------------------------------------------
//
require_once "common.php";
// guilds function lib
require_once "guilds/armoreditor.php";
require_once "guilds/cellar.php";
require_once "guilds/clannav.php";
require_once "guilds/clanmembers.php";
require_once "guilds/guildmembers.php";
require_once "guilds/guildnav.php";
require_once "guilds/hofs.php";
require_once "guilds/mailing.php";
require_once "guilds/nav.php";
require_once "guilds/ranks.php";
require_once "guilds/shops.php";
require_once "guilds/sitepoints.php";
require_once "guilds/tent.php";
require_once "guilds/weaponeditor.php";


// Setting for the guilds/clans - when not set in the settings table
// Purchase cost
$GuildPurchaseCostGems=50;
$GuildPurchaseCostGold=10000;
$ClanPurchaseCostGems=25;
$ClanPurchaseCostGold=5000;
// Default gold cost for weapon/armour upgrade per lvl
$DefaultWeaponUpgrade=500;
$DefaultArmourUpgrade=500;
// Ratio of the healing cost for guilds
$HealingGuildDiscount=0.95; // Changed to be 0.95 from 0.75, now that the SitePoints can be spent

// Used for the Nav Management system
$NavSystem=array();

// other variables  by gargamel
// make them global within every used function
   // name of key roles
   $GuildLeader = "Gilden-Vorsitz";
   $HeadOfWar = "Befehlshaber im Kampf";
   $HeadOfMembership = "Mitgliederverwaltung";
   $ClanLeader = "Clanführer";
   $vacant = "Nicht besetzt";
   // name of management actions
   // global $demote, $banish, $addtoHitlist, $assignRank, $Stipend, $promote, $revoke;
   $demote="Entlassung";
   $banish="Bann";
   $addtoHitlist="Hitliste";
   $assignRank="Rangvergabe";
   $Stipend="Besoldung";
   $promote="Beförderung";
   $revoke="Rauswurf";
   // name of other actions
   $Edit="bearbeiten";
   $Remove="löschen";
   // name of site points spending categories
   $SPnames=array(
         "Titel99"          =>"noch nicht umgesetzt",
         "Titel1"          =>"Aktuelle Leistungen der Gilde",
         "Titel2"          =>"Aktuelle Verpflichtungen an die Gilde",
         "Titel3"          =>"Aktuelle Leistungen des Clans",
         "Titel4"          =>"Aktuelle Verpflichtungen an den Clan",
         "Banking Interest"        =>"garantierter Mindest-Zinssatz der Bank",
         "Percent Earned from FF"  =>"Gold-Tribut aus Waldkämpfen",
         "Percent Earned from PvP" =>"Gold-Tribut aus PvP-Kämpfen",
         "Healing Discount"        =>"Vergünstigung beim Heiler",
         "Training Discount"       =>"Erfahrungsbonus im Trainingsraum",
         "PvP Purchase Discount"   =>"PvP Purchase Discount",
         "Gem Discount"            =>"Rabatt beim Edelsteinkauf",
         "Potion Discount"         =>"Rabatt auf Heiltränke",
         "Weapon Purchase Discount"=>"Rabatt bei Waffenkauf und -verbesserung",
         "Armor Purchase Discount" =>"Rabatt bei Rüstungskauf und -verbesserung",
         "house" =>"Rabatt bei Häusern",
         "mount" =>"Rabatt bei Tieren"
         );
   //some configuration
   $maxarmornum = 10;   // max number of allowed guild armors
   $maxweaponnum = 10;  // max number of allowed guild weapons
// end variables by gargamel


function CalcInterestEarned($guildID) {

 global $session;
 $ThisGuild=&$session['guilds'][$guildID];
 $current = mktime(0,0,0,date("m"),date("d"),date("Y"));
 $interest=0;

 if (isset($ThisGuild['PercentOfFightsEarned']['last-interest'])) {
    $prev=$ThisGuild['PercentOfFightsEarned']['last-interest'];
    $daycount=intval(($current-$prev)/(60*60*24));
    if ($daycount>0) {
     if (isset($ThisGuild['PercentOfFightsEarned']['Bank'])) {
        for ($i=$daycount;$i>0;$i--) {
            $interest+=$ThisGuild['gold']*($ThisGuild['PercentOfFightsEarned']['Bank']/100);
        }
        $ThisGuild['gold']+=$interest;
     }
    }
 }
 $ThisGuild['PercentOfFightsEarned']['last-interest']=$current;
 update_guild_info($ThisGuild);
 return $interest;
}

function PromoteMember($guildID) {

// Promote member to the management team
 global $GuildLeader, $HeadOfWar, $HeadOfMembership;
 global $HTTP_GET_VARS, $_POST, $session;
 $stage=$HTTP_GET_VARS['stage'];

 switch ($stage) {
    case "2":
        $who=$_POST['who'];
        if ($who!="") {
            $toPosition=$_POST['sections'];
            $ThisGuild=&$session['guilds'][$guildID];
            switch ($toPosition) {
                case "leader":
                    $ThisGuild['GuildLeader']=$who;
                break;
                case "how":
                    $ThisGuild['HeadOfWar']=$who;
                break;
                case "hom":
                    $ThisGuild['HeadOfMembership']=$who;
                break;
            }
            update_guild_info($ThisGuild);
            output("Das Mitglied wurde befördert!!!");
        } else {
            output("Du musst erst jemanden auswählen!!");
        }
    break;

    default:
        $who=$HTTP_GET_VARS['who'];
        $return=CalcReturnPath();
        if (isset($who)) {
             output("<form action='".$return."&stage=2' method='POST'>",true);
             addnav("",$return."&stage=2");
             output("`n`nBeförderung zum Rang: ");
             output("<input type='hidden' name='who' value='".$who."'/>",true);
             // Promote member to the management team
             output("`n`nBeförderung zum: ");
             // Display a list of ranks in a drop down box that the member can be promoted to.
             output("<SELECT NAME=sections >",true);
             output("<OPTION value=leader>".$GuildLeader."</OPTION>",true);
             output("<OPTION value=how>".$HeadOfWar."</OPTION>",true);
             output("<OPTION value=hom>".$HeadOfMembership."</OPTION>",true);
             output("</SELECT>",true);
             output("<script language='javascript'>document.getElementById('sections').focus();</script>",true);
             output("`n<input type='submit' class='button' value='Speichern'/>",true);
             output("</form>",true);
        } else {
            // Nobody has been selected at all!
            output("Es wurde niemand ausgewählt!");
            return;
        }
    break;
 }
}

function PayMember($guildID, $who) {

// Pay a member a some gold/gems from the guild funds

    global $session, $HTTP_GET_VARS;
    $stage=$HTTP_GET_VARS['stage'];
    $ThisGuild=&$session['guilds'][$guildID];
    if ($who<>$session[user][acctid]){
    $MemberName = ListToNames($who, false);
   }else{
      $MemberName = $session[user][name];
   }
    $sqla="SELECT goldafterdk FROM accounts WHERE acctid = ".$who."";
    $resulta=db_query($sqla);
    $rowa=db_fetch_assoc($resulta);
    $gadk=$rowa[goldafterdk];

    switch ($stage) {
            default:
// Transfer Sequence
                if (($ThisGuild['gold']<=0) and ($ThisGuild['gems'])) {
                    output("`6Der kleine Finanzgnom teilt Dir mit, dass er die
                    Transaktion für einen Schuldner nicht durchführen wird.");
                    return;
                }
                output("`6`bTransfer an Mitglied`b:`n");
                output("Übertrage Gold oder Edelsteine an Dein Mitglied `^"
                .$MemberName."`0",true);
                // Unlimited transfer
                $return=CalcReturnPath();
                output("<form action='".$return."&who=".$who."&stage=2' method='POST'>",true);
                if ($ThisGuild['gold']>0) output("<input type=radio name='type' value='gold' checked> Gold<br>",true);
                if ($ThisGuild['gems']>0) output("<input type=radio name='type' value='gems'> Edelsteine<br>",true);
                output("wie<u>v</u>iel übertragen: <input name='amount' id='amount' accesskey='v' width='5'>`n",true);
                output("wiev<u>i</u>el Gold nach DK übertragen: <input name='amountday' id='amountday' value ='".$gadk."' accesskey='i' width='5'>`n",true);
                output("`n<input type='submit' class='button' value='übertragen'></form>",true);
                output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
                addnav("",$return."&who=".$who."&stage=2");
            break;

            case "2":
                $amt = abs((int)$_POST['amount']);
        $amtmon = abs((int)$_POST['amountday']);
                $type = $_POST['type'];
                output("`&`bStatus der Übertragung:`b`n`0");
                switch ($type) {
                    case "gold":
                        if ($ThisGuild['gold']<$amt){
                            output("`6Wie willst Du `^$amt`6 Gold übertragen, wenn Du nur ".($ThisGuild['gold'])." Gold in der Schatztruhe hast?`6?");
                        }else{
                            debuglog(" `&from the ".$ThisGuild['Name']." transferred ".$amt." gold to ".$MemberName,$who);
                            $ThisGuild['gold']-=$amt;
                            if ($who==$session[user][acctid]){
                               $session[user][gold]+=$amt;
                    set_special_var("goldafterdk","".$amtmon."","0",$who,$session[user][acctid]," ");
                            }else{
                            //$sql= "update accounts set gold=gold+".$amt." where acctid=".$who;
                            //db_query($sql);
                    set_special_var("goldinbank","".$amt."","0",$who,$session[user][acctid],"Überweisung: ".$session[user][name]." hat Dir ".$amt." ClanGold überwiesen.");
                    set_special_var("goldafterdk","".$amtmon."","0",$who,$session[user][acctid]," ");
                            }
                            update_guild_info($ThisGuild);
                            output("`6Übertrag erfolgreich angeschlossen!");
                        }
                    break;
                    case "gems":
                        if ($ThisGuild['gems']<$amt){
                            output("`6Wie willst Du `%$amt`6 Edelsteine übertragen, wenn Du nur ".($ThisGuild['gems'])." Edelsteine in der Schatztruhe hast?`6?");
                        }else{
                            debuglog("`& from the ".$ThisGuild['Name']." transferred ".$amt." gems to ".$MemberName,$who);
                            $ThisGuild['gems']-=$amt;
                            if ($who==$session[user][acctid]){
                               $session[user][gems]+=$amt;
                            }else{
                            //$sql= "update accounts set gems=gems+".$amt." where acctid=".$who;
                            //db_query($sql);
                    set_special_var("gems","".$amt."","0",$who,$session[user][acctid],"Überweisung: ".$session[user][name]." hat Dir ".$amt." Clan Edelsteine zugesteckt");
                           }
                            update_guild_info($ThisGuild);
                            output("`6Übertrag erfolgreich angeschlossen!");
                        }

                    break;
                }
                break;
            }

}


function transfer_cash_guild($guildID, $stage=0) {

// Allows members to donate to the guild/clan

    global $session, $HTTP_GET_VARS, $_POST;
    $stage=$HTTP_GET_VARS['stage'];

    $ThisGuild=&$session['guilds'][$guildID];
    switch ($stage) {
             default:
// Transfer Sequence
                output("`&`bTribut zahlen`b:`n`0");
                if ($session['user']['goldinbank']>=0){
                $maxout = $session[user][level]*getsetting("maxtransferout",25); 
                    $transleft = $maxout - $session[user][amountouttoday];
            if ($transleft <= 0){
                output("`&Du hast heute bereits Dein Einzahlungslimit im Spiel überschritten.");
            }else{
                            output("`0Hier kannst Du `^Gold `0or `%Edelsteine `0an die Gilde `3".$ThisGuild['Name']."`0 übertragen.");
                            // Unlimited transfer
                            $return=CalcReturnPath(true);
                            output("<form action='".$return."&stage=2' method='POST'>",true);
                            output("<input type=radio name='type' value='gold' checked> `^Gold`0<br>",true);
                            output("<input type=radio name='type' value='gems'> `%Edelsteine`0<br>",true);
                            output("`0Wieviel übertragen: <input name='amount' id='amount' accesskey='h' width='5'>`n",true);
                            output("`n<input type='submit' class='button' value='Übertrag'></form>",true);
                            output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
                            addnav("",$return."&stage=2");
            }
                }else{
                    output("`&Ein Übertrag an die Gilde ist für verschuldete Mitglieder nicht möglich!");
                }
                break;

            case "2":
                $amt = abs((int)$_POST['amount']);
                $type = $_POST['type'];
                output("`&`bStatus der Übertragung:`b`n`0");
                switch ($type) {
                    case "gold":
                $maxout = $session[user][level]*getsetting("maxtransferout",25); 
                    $transleft = $maxout - $session[user][amountouttoday];
                        if ($session['user']['gold']+$session['user']['goldinbank']<$amt){
                            output("`6Übertrag unmöglich!`n
                            Wie willst Du der Gilde `^$amt`6 Gold übertragen, wenn Du nur ".($session['user']['gold']+$session['user']['goldinbank'])." Gold besitzt`6?");
            }else if ($amt > $transleft){
                output("`6Soviel Gold darfst Du heute nicht mehr übertragen. `n
                    Insgesamt darfst Du heute nur noch ".$transleft." Gold im Spiel übertragen.");
                        }else{
                            debuglog($session['user']['Name']."transferred ".$amt." gold to ".$ThisGuild['Name']);
                            $session['user']['gold']-=$amt;
                            if ($session['user']['gold']<0){ //withdraw in case they don't have enough on hand.
                                $session['user']['goldinbank']+=$session['user']['gold'];
                                $session['user']['gold']=0;
                            }
                            $session['user']['amountouttoday']+= $amt;
                            $ThisGuild['gold']+=$amt;
                            //update_guild_info($ThisGuild);
                set_clanguild_var("addguildgold","".$amt."","0",$session[user][acctid],$guildID,"Donation Gold");
                            output("`n`&Du hast erfolgreich `^".$amt." `&Gold an die Gilde übertragen!");
                        }
                    break;
                    case "gems":
                        if ($session['user']['gems']+$session['user']['gemsinbank']<$amt){
                            output("`6Übertrag unmöglich!`m
                            Wie willst du der Gilde `^$amt`6 Edelsteine übertragen, wenn Du nur ".($session['user']['gems']+$session['user']['gemsinbank'])." Edelsteine besitzt`6?");
                        }else{
                            debuglog($session['user']['Name']."transferred ".$amt." gems to ".$ThisGuild['Name']);
                            $session['user']['gems']-=$amt;
                            if ($session['user']['gems']<0){ //withdraw in case they don't have enough on hand.
                                $session['user']['gemsinbank']+=$session['user']['gold'];
                                $session['user']['gems']=0;
                            }
                            $ThisGuild['gems']+=$amt;
                            //update_guild_info($ThisGuild);
                set_clanguild_var("addguildgems","".$amt."","0",$session[user][acctid],$guildID,"Donation Gems");
                            output("`n`&Du hast erfolgreich  `%".$amt." `&Edelsteine an die Gilde übertragen!");
                        }

                    break;
                }
                break;
            }
}

function transfer_cash_clan($guildID, $stage=0) {

// Allows members to donate to the guild/clan

    global $session, $HTTP_GET_VARS, $_POST;
    $stage=$HTTP_GET_VARS['stage'];

    $ThisClan=&$session['guilds'][$guildID];
    switch ($stage) {
             default:
// Transfer Sequence
                output("`&`bTribut zahlen`b:`n`0");
                if ($session['user']['goldinbank']>=0){
                $maxout = $session[user][level]*getsetting("maxtransferout",25); 
                    $transleft = $maxout - $session[user][amountouttoday];
            if ($transleft <= 0){
                output("`&Du hast heute bereits Dein Einzahlungslimit im Spiel überschritten.");
            }else{
                            output("`0Hier kannst Du `^Gold `0oder `%Edelsteine `0an den Clan `3".$ThisClan['Name']."`0 übertragen.");
                            // Unlimited transfer
                            $return=CalcReturnPath(true);
                            output("<form action='".$return."&stage=2' method='POST'>",true);
                            output("<input type=radio name='type' value='gold' checked> `^Gold`0<br>",true);
                            output("<input type=radio name='type' value='gems'> `%Edelsteine`0<br>",true);
                            output("`0Wieviel übertragen: <input name='amount' id='amount' accesskey='h' width='5'>`n",true);
                            output("`n<input type='submit' class='button' value='Übertrag'></form>",true);
                            output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
                            addnav("",$return."&stage=2");
            }
                }else{
                    output("`&Ein Übertrag an den Clan ist für verschuldete Mitglieder nicht möglich!");
                }
                break;

            case "2":
                $amt = abs((int)$_POST['amount']);
                $type = $_POST['type'];
                output("`&`bStatus der Übertragung:`b`n`0");
                switch ($type) {
                    case "gold":
                $maxout = $session[user][level]*getsetting("maxtransferout",25); 
                    $transleft = $maxout - $session[user][amountouttoday];            
                        if ($session['user']['gold']+$session['user']['goldinbank']<$amt){
                            output("`6Übertrag unmöglich!`n
                            Wie willst Du dem Clan `^$amt`6 Gold übertragen, wenn Du nur ".($session['user']['gold']+$session['user']['goldinbank'])." Gold besitzt`6?");
            }else if ($amt > $transleft){
                output("`6Soviel Gold darfst Du heute nicht mehr übertragen. `n
                    Insgesamt darfst Du heute nur noch ".$transleft." Gold im Spiel übertragen.");
                        }else{
                            debuglog($session['user']['Name']."transferred ".$amt." gold to ".$ThisClan['Name']);
                            $session['user']['gold']-=$amt;
                            if ($session['user']['gold']<0){ //withdraw in case they don't have enough on hand.
                                $session['user']['goldinbank']+=$session['user']['gold'];
                                $session['user']['gold']=0;
                            }
                            $session['user']['amountouttoday']+= $amt;
                            $ThisClan['gold']+=$amt;
                            //update_guild_info($ThisClan); //handels Clans too
                set_clanguild_var("addclangold","".$amt."","0",$session[user][acctid],$guildID,"Donation Gold");
                            output("`n`&Du hast erfolgreich `^".$amt." `&Gold an den Clan übertragen!");
                        }
                    break;
                    case "gems":
                        if ($session['user']['gems']+$session['user']['gemsinbank']<$amt){
                            output("`6Übertrag unmöglich!`m
                            Wie willst du dem Clan `^$amt`6 Edelsteine übertragen, wenn Du nur ".($session['user']['gems']+$session['user']['gemsinbank'])." Edelsteine besitzt`6?");
                        }else{
                            debuglog($session['user']['Name']."transferred ".$amt." gems to ".$ThisClan['Name']);
                            $session['user']['gems']-=$amt;
                            if ($session['user']['gems']<0){ //withdraw in case they don't have enough on hand.
                                $session['user']['gemsinbank']+=$session['user']['gold'];
                                $session['user']['gems']=0;
                            }
                            $ThisClan['gems']+=$amt;
                            //update_guild_info($ThisClan); //handels Clans too
                set_clanguild_var("addclangems","".$amt."","0",$session[user][acctid],$guildID,"Donation Gems");
                            output("`n`&Du hast erfolgreich  `%".$amt." `&Edelsteine an den Clan übertragen!");
                        }

                    break;
                }
                break;
            }
}

function edit_hitlist($guildID) {

// Edit the guild hitlist
// Won't display members of the guild/clan in the list of available members
global $session, $HTTP_GET_VARS;
$stage=$HTTP_GET_VARS['stage'];
if ($stage=="") $stage=0;

    $ThisGuild=&$session['guilds'][$guildID];
    $HitListMembers=HitlistToNames($ThisGuild['Hitlist'], true);

    if (count($HitListMembers)==0) {
        output("`n`0Es ist momentan niemand auf die Hitliste gesetzt.`n`n");
    } else {
        output("`nEs ".((count($HitListMembers)==1)?"ist":"sind")." momentan ".count($HitListMembers)." Mitglied".((Count($HitListMembers)==1)?"":"er")." auf der Hitliste:`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'>",true);
        output("<tr class='trhead'>
                    <td>`bOption`b</td>
                    <td>`bRang`b</td>
                    <td>`bName`b</td>
                    <td>`bKopfgeld`b</td>
                </tr>",true);
        $return=CalcReturnPath(true);

        foreach ($HitListMembers as $ThisName){
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".
                "<a href='".$return."&type=remove&id=".$guildID."&who=".$ThisName['acctid']."&stage=2'>löschen</a>".
                "</td><td>".($i+1)."</td><td>",true);
                $link = "bio.php?char=".rawurlencode($ThisName['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                output("<a href='".$link."'>",true);
                addnav("",$link);
                output($ThisName['name']);
                output("</a>",true);
                output("</td><td>`^".$ThisName['bounty']."`0 gp</td></tr>",true);

            addnav("",$return."&type=remove&id=".$guildID."&who=".$ThisName['acctid']."&stage=2");
        }
        output("</TABLE>`n",true);
    }

    switch ($stage) {
        case "1":
            // Display results
            $searchname=$_POST['searchname'];
            // Expand name with %
            for ($x=0;$x<strlen($searchname);$x++){
                //$search .= substr($searchname,$x,1)."__";
        $search .= substr($searchname,$x,1)."%";
                }
            $searchname="%".$search."%";
            if (!is_array($ThisGuild['Hitlist'])) $ThisGuild['Hitlist']=array();
            $exclusion=implode(",",array_keys($ThisGuild['Hitlist']));  // Remove people currently in the hitlist
            $MyMembers=implode(",",array_keys(FindGuildMembers($guildID,true)));  // You cannot put a bounty on your own members
            if ($exclusion!="") $exclusion.=",";  // Handles when the HitList is empty
            $exclusion.= $MyMembers;    // Exclude guild members
            if ($exclusion=="") {
                // There is ALWAYS a member of the guild
                output("This shouldn't happen - BUG!!!");
            }

            $sql="select * from accounts where name like '%".$searchname."%' and acctid not in (".$exclusion.")";

            $result=db_query($sql);
            $rows=db_num_rows($result);
            if ($rows==0) {
                output("The Search didn't find any results!");
            } else {
                output("Die Suche hat ".$rows." Krieger gefunden:`n`n");
                output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'>",true);
                output("<tr class='trhead'>
                            <td>Option</td><td>
                            <b>Lebt</b></td>
                            <td><b>Level</b></td>
                            <td><b>Name</b></td>
                            <td><b>Ort</b></td>
                            <td><b>Geschlecht</b></td>
                            <td><b>Kopfgeld</b></td>",true);
                $return=CalcReturnPath(true);
                for($i=0;$i<$rows;$i++){
                    $row = db_fetch_assoc($result);
                    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                    output("<a href='".$return."&type=add&who=".$row['acctid']."&stage=2'>auf Hitliste setzen</a>",true);
                    addnav("",$return."&type=add&who=".$row['acctid']."&stage=2");
                    output("</td><td>",true);
                    output($row['alive']?"`1Ja`0":"`4Nein`0");
                    output("</td><td>",true);
                    output("`^".$row['level']."`0");
                    output("</td><td>",true);
                    output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
                    $link = "bio.php?char=".rawurlencode($row[login]) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                    output("<a href='".$link."'>",true);
                    addnav("",$link);

                    output("`&".$row['name']."`0");
                    output("</a>",true);
                    output("</td><td>",true);
                    $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
                    output($row['location']
                                ?"`3Boar's Head Inn`0"
                                :(
                                    $loggedin
                                    ?"`#Online`0"
                                    :"`3The Fields`0"
                                    )
                            );
                    output("</td><td>",true);
                    output($row['sex']?"`!weiblich`0":"`!männlich`0");
                    output("</td>",true);
                    output("<td>`^".$row['bounty']."`0 Gold</td>",true);
                    output("</tr>",true);
                }
                output("</table>",true);

            }
        break;
        case "2":
            $type=$HTTP_GET_VARS['type'];
            $who=$HTTP_GET_VARS['who'];
            switch ($type) {
                case "add":
                    // Add them to HitList
                    $ThisGuild['Hitlist'][$who]="";
                break;
                case "remove":
                    // Add them to HitList
                    $HitList=&$ThisGuild['Hitlist'];
                    unset($HitList[$who]);
                break;
                default:
                    output("Humm... Now how did we get here?");
                break;
            }

            update_guild_info($ThisGuild);
            redirect("guild.php?op=manage&action=hitlist&id=".$guildID."&stage=0");

        break;

        case "0":
        default:
            $return=CalcReturnPath();
            output("`0<form action='".$return."&stage=1' method='POST'>",true);
            output("`nWen suchst Du? <input name='searchname' size=50>",true);
            output("<input type='submit' class='button' value='finden'>",true);
            addnav("",$return."&stage=1");
        break;
    }
}

function edit_hitlist_clan($guildID) {

// Edit the guild hitlist
// Won't display members of the guild/clan in the list of available members
global $session, $HTTP_GET_VARS;
$stage=$HTTP_GET_VARS['stage'];
if ($stage=="") $stage=0;

    $ThisGuild=&$session['guilds'][$guildID];
    $HitListMembers=HitlistToNames($ThisGuild['Hitlist'], true);

    if (count($HitListMembers)==0) {
        output("`n`0Es ist momentan niemand auf die Hitliste gesetzt.`n`n");
    } else {
        output("`nEs ".((count($HitListMembers)==1)?"ist":"sind")." momentan ".count($HitListMembers)." Mitglied".((Count($HitListMembers)==1)?"":"er")." auf der Hitliste:`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'>",true);
        output("<tr class='trhead'>
                    <td>`bOption`b</td>
                    <td>`bRang`b</td>
                    <td>`bName`b</td>
                    <td>`bKopfgeld`b</td>
                </tr>",true);
        $return=CalcReturnPath(true);

        foreach ($HitListMembers as $ThisName){
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".
                "<a href='".$return."&type=remove&id=".$guildID."&who=".$ThisName['acctid']."&stage=2'>löschen</a>".
                "</td><td>".($i+1)."</td><td>",true);
                $link = "bio.php?char=".rawurlencode($ThisName['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                output("<a href='".$link."'>",true);
                addnav("",$link);
                output($ThisName['name']);
                output("</a>",true);
                output("</td><td>`^".$ThisName['bounty']."`0 gp</td></tr>",true);

            addnav("",$return."&type=remove&id=".$guildID."&who=".$ThisName['acctid']."&stage=2");
        }
        output("</TABLE>`n",true);
    }

    switch ($stage) {
        case "1":
            // Display results
            $searchname=$_POST['searchname'];
            // Expand name with %
            for ($x=0;$x<strlen($searchname);$x++){
                //$search .= substr($searchname,$x,1)."__";
        $search .= substr($searchname,$x,1)."%";
                }
            $searchname="%".$search."%";
            if (!is_array($ThisGuild['Hitlist'])) $ThisGuild['Hitlist']=array();
            $exclusion=implode(",",array_keys($ThisGuild['Hitlist']));  // Remove people currently in the hitlist
            $MyMembers=implode(",",array_keys(FindClanMembers($guildID,true)));  // You cannot put a bounty on your own members
            if ($exclusion!="") $exclusion.=",";  // Handles when the HitList is empty
            $exclusion.= $MyMembers;    // Exclude guild members
            if ($exclusion=="") {
                // There is ALWAYS a member of the guild
                output("This shouldn't happen - BUG!!!");
            }

            $sql="select * from accounts where name like '%".$searchname."%' and acctid not in (".$exclusion.")";

            $result=db_query($sql);
            $rows=db_num_rows($result);
            if ($rows==0) {
                output("The Search didn't find any results!");
            } else {
                output("Die Suche hat ".$rows." Krieger gefunden:`n`n");
                output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'>",true);
                output("<tr class='trhead'>
                            <td>Option</td><td>
                            <b>Lebt</b></td>
                            <td><b>Level</b></td>
                            <td><b>Name</b></td>
                            <td><b>Ort</b></td>
                            <td><b>Geschlecht</b></td>
                            <td><b>Kopfgeld</b></td>",true);
                $return=CalcReturnPath(true);
                for($i=0;$i<$rows;$i++){
                    $row = db_fetch_assoc($result);
                    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                    output("<a href='".$return."&type=add&who=".$row['acctid']."&stage=2'>auf Hitliste setzen</a>",true);
                    addnav("",$return."&type=add&who=".$row['acctid']."&stage=2");
                    output("</td><td>",true);
                    output($row['alive']?"`1Ja`0":"`4Nein`0");
                    output("</td><td>",true);
                    output("`^".$row['level']."`0");
                    output("</td><td>",true);
                    output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
                    $link = "bio.php?char=".rawurlencode($row[login]) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                    output("<a href='".$link."'>",true);
                    addnav("",$link);

                    output("`&".$row['name']."`0");
                    output("</a>",true);
                    output("</td><td>",true);
                    $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
                    output($row['location']
                                ?"`3Boar's Head Inn`0"
                                :(
                                    $loggedin
                                    ?"`#Online`0"
                                    :"`3The Fields`0"
                                    )
                            );
                    output("</td><td>",true);
                    output($row['sex']?"`!weiblich`0":"`!männlich`0");
                    output("</td>",true);
                    output("<td>`^".$row['bounty']."`0 Gold</td>",true);
                    output("</tr>",true);
                }
                output("</table>",true);

            }
        break;
        case "2":
            $type=$HTTP_GET_VARS['type'];
            $who=$HTTP_GET_VARS['who'];
            switch ($type) {
                case "add":
                    // Add them to HitList
                    $ThisGuild['Hitlist'][$who]="";
                break;
                case "remove":
                    // Add them to HitList
                    $HitList=&$ThisGuild['Hitlist'];
                    unset($HitList[$who]);
                break;
                default:
                    output("Humm... Now how did we get here?");
                break;
            }

            update_guild_info($ThisGuild);
            redirect("clan.php?op=manage&action=hitlist&id=".$guildID."&stage=0");

        break;

        case "0":
        default:
            $return=CalcReturnPath();
            output("`0<form action='".$return."&stage=1' method='POST'>",true);
            output("`nWen suchst Du? <input name='searchname' size=50>",true);
            output("<input type='submit' class='button' value='finden'>",true);
            addnav("",$return."&stage=1");
        break;
    }
}

function display_guild_intro($guildname) {
output("`n`%Informationen der Gilde");
}

function ManagementTeam($guildID, $IncludeNames=false) {

// Return the management team (acctid's)
// Guilds have 3 members
// Clans have 1 member
// The keys of the returned array are the table display headings

global $session;
$ThisGuild=&$session['guilds'][$guildID];

global $GuildLeader, $HeadOfWar, $HeadOfMembership, $ClanLeader;

    if ($ThisGuild['IsGuild']==1) {
        $mgmt=array();
        $mgmt[$GuildLeader]=$ThisGuild['GuildLeader'];
        $mgmt[$HeadOfWar]=$ThisGuild['HeadOfWar'];
        $mgmt[$HeadOfMembership]=$ThisGuild['HeadOfMembership'];
    } else {
        $mgmt=array();
        $mgmt[$ClanLeader]=$ThisGuild['GuildLeader'];
    }

    if ($IncludeNames) {
        $sql_include=implode(",", array_values($mgmt));
        $sql="select accounts.name, accounts.acctid, accounts.guildid, lotbd_guildranks.displaytitle
                        from accounts left join lotbd_guildranks on accounts.guildrank=lotbd_guildranks.rankid and accounts.guildid=lotbd_guildranks.guildid
                        where acctid in (".$sql_include.")";
        $result=db_query($sql);
        $RowCount=db_num_rows($result);

        if ($ThisGuild['IsGuild']==1) {
            $results[$GuildLeader]=$ThisGuild['GuildLeader'];
            $results[$HeadOfWar]=$ThisGuild['HeadOfWar'];
            $results[$HeadOfMembership]=$ThisGuild['HeadOfMembership'];
        } else {
            $results[$ClanLeader]=$ThisGuild['GuildLeader'];
        }

        for ($i=0; $i<$RowCount; $i++) {
            $row=db_fetch_assoc($result);
            $key=array_search($row['acctid'], $mgmt);
            $results[$key]=$row;
        }
        return $results;
    } else {
        return $mgmt;
    }
}

function IsOnManagementTeam($guildID, $who=""){

// Is the passed in member or the current user on the management team of the guild

  global $session;
  $onTeam=0;
  if ($who=="") $who=$session['user']['acctid'];

  $TestGuild=&$session['guilds'][$guildID];

  $pos=strpos(":".$TestGuild['GuildLeader'].":".$TestGuild['HeadOfMembership'].":".$TestGuild['HeadOfWar'].":",":".$who.":");
  if ($pos===false) {
     $onTeam=false;
  } else {
     $onTeam=true;
  }

return $onTeam;
}

function remove_bad_marks($in) {    //gargamel: show unwanted text!
    $badtext = "`4 enthält unerwünschte Begriffe`0";
    $zensor  = "`i$@#%`i";
    $plainzensor = "$@#%";
    $out = str_replace ( $badtext,"",$in );
    $out = str_replace ( $zensor,$plainzensor,$out );
return $out;
}

function display_edit_guild($Info,$Admin=false, $SU=false) {

    global $GuildLeader, $HeadOfWar, $HeadOfMembership;

//gargamel: show unwanted text!
    $Info['Name'] = remove_bad_marks($Info['Name']);
    $Info['PublicText'] = remove_bad_marks($Info['PublicText']);
    $Info['ApplyText'] = remove_bad_marks($Info['ApplyText']);
    $Info['RulesText'] = remove_bad_marks($Info['RulesText']);

// Edit the guild info

    $HasData=(count($Info)!=0);
    if ($HasData!=true) $Info=array();

    output("`&`c`bGründung einer Gilde`b`c");
    output("`n`n*`izwingend notwendige Angaben`i`n",true);

    if ($SU) {
        output("`0<form action='guilds-clans-su.php?type=applications&action=save' method='POST'>",true);
        addnav("","guilds-clans-su.php?type=applications&action=save");
    } else {
        output("`0<form action='guild.php?op=nonmember&action=create&type=guild&status=verifyInfo&id=".$Info['ID']."' method='POST'>",true);
        addnav("","guild.php?op=nonmember&action=create&type=guild&status=verifyInfo&id=".$Info['ID']);
    }
    output("<input name='info[ID]'  type='hidden' value='".$Info['ID']."'>",true);
    output("`n* Welchen Namen soll die Gilde führen? <input name='info[Name]' size=100 value='".SafeDisplayString(($HasData)? $Info['Name']: "" )."'>`n",true);

    if (!isset($Info['GuildPrefix']['pre'])) $Info['GuildPrefix']['pre']=1;
    $pre=$Info['GuildPrefix']['pre'];
    $GuildPrefix=$Info['GuildPrefix']['display'];
    output("`nWelches Kürzel sollen die zukünftigen Mitglieder stolz tragen (3 Zeichen, jedes Zeichen kann mit einem Farbcode versehen werden)? <input name='info[GuildPrefix][display]' length=9 size=10 value='".SafeDisplayString(($HasData)? $GuildPrefix: "" )."'>`n",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='1' ".(($pre==1)?"checked":"")."> Als Prefix zum Namen<br>",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='0' ".(($pre==0)?"checked":"")."> Als Suffix zum Namen<br>",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='2' ".(($pre==2)?"checked":"")."> Ohne Kennzeichnung<br>",true);

    output("`n* Beschreibung der Gilde: `n<textarea name='info[PublicText]' size=10 cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['PublicText']: "" )."</textarea>`n",true);
    output("`n* Text für Bewerber: `n<textarea name='info[ApplyText]' cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['ApplyText']: "" )."</textarea>`n",true);
    output("`n* Regeln der Gilde:`n <textarea name='info[RulesText]' cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['RulesText']: "" )."</textarea>`n",true);
    output("`nLink: `n<input name='info[ExternalPagesLink]' size=100 value='".SafeDisplayString(($HasData)? $Info['ExternalPagesLink']: "" )."'>",true);

    if ($SU) {
        output("`nAccount ID's of:");
        output("`n&nbsp;&nbsp;$GuildLeader: `n<input name='info[GuildLeader]' length=9 size=10 value='".SafeDisplayString(($HasData)? $Info['GuildLeader']: "" )."'>`n",true);
        output("`n&nbsp;&nbsp;$HeadOfWar: `n<input name='info[HeadOfWar]' length=9 size=10 value='".SafeDisplayString(($HasData)? $Info['HeadOfWar']: "" )."'>`n",true);
        output("`n&nbsp;&nbsp;$HeadOfMembership: `n<input name='info[HeadOfMembership]' length=9 size=10 value='".SafeDisplayString(($HasData)? $Info['HeadOfMembership']: "" )."'>`n",true);
    }

    if ($Admin) {
     $type="";
    } else {
     $type=" type='hidden' ";
    }


    if (!is_array($Info['Hitlist'])) $Info['Hitlist']=array();
    $HitListDisplay=HitlistToNames($Info['Hitlist'], false);
    output("`nHitList: `n<input name='info[Hitlist]' readonly ".$type."size=100 value='".SafeDisplayString($HitListDisplay)."'>",true);

    DisplaySitePoints($Info['ID']);

    if ($SU) {
     $type="";
    } else {
     $type=" readonly ";
    }

    output("`nGold: `n<input name='info[gold]' ".$type." size=10 value='".(($HasData)? $Info['gold']: "" )."'>",true);
    output("`nEdelsteine: `n<input name='info[gems]' ".$type." size=10 value='".(($HasData)? $Info['gems']: "" )."'>",true);
    output("`nGildenpunkte: `n<input name='info[SitePoints]' ".$type." size=5 value='".(($HasData)? $Info['SitePoints']: "" )."'>",true);

    if ($Admin) {
        output("`n`n<input type='submit' class='button' value='Save'>",true);
    } else {
        output("`n`nWenn Du das Pergament ausgefüllt hast, drücke Antrag. Du wirst die Angaben
        noch einmal prüfen können, bevor Dein Pergament endgültig den Göttern vorgelegt wird.");
        output("`n`n<input type='submit' class='button' value='Antrag'>",true);
    }
}

function display_edit_clan($Info,$Admin=false, $SU=false) {

    global $ClanLeader;

//gargamel: show unwanted text!
    $Info['Name'] = remove_bad_marks($Info['Name']);
    $Info['PublicText'] = remove_bad_marks($Info['PublicText']);
    $Info['ApplyText'] = remove_bad_marks($Info['ApplyText']);
    $Info['RulesText'] = remove_bad_marks($Info['RulesText']);

// Edit the clan info

    $HasData=(count($Info)!=0);
    if ($HasData!=true) $Info=array();

    output("`&`c`bGründung eines Clans`b`c");
    output("`n`n*`izwingend notwendige Angaben`i`n",true);

    if ($SU) {
        output("`0<form action='guilds-clans-su.php?type=applications&action=save' method='POST'>",true);
        addnav("","guilds-clans-su.php?type=applications&action=save");
    } else {
        output("`0<form action='clan.php?op=nonmember&action=create&type=clan&status=verifyInfo&id=".$Info['ID']."' method='POST'>",true);
        addnav("","clan.php?op=nonmember&action=create&type=clan&status=verifyInfo&id=".$Info['ID']);
    }
    output("<input name='info[ID]'  type='hidden' value='".$Info['ID']."'>",true);
    output("`n* Welchen Namen soll der Clan führen? <input name='info[Name]' size=100 value='".SafeDisplayString(($HasData)? $Info['Name']: "" )."'>`n",true);

    if (!isset($Info['GuildPrefix']['pre'])) $Info['GuildPrefix']['pre']=1;
    $pre=$Info['GuildPrefix']['pre'];
    $GuildPrefix=$Info['GuildPrefix']['display'];
    output("`nWelches Kürzel sollen die zukünftigen Mitglieder stolz tragen (3 Zeichen, jedes Zeichen kann mit einem Farbcode versehen werden)? <input name='info[GuildPrefix][display]' length=9 size=10 value='".SafeDisplayString(($HasData)? $GuildPrefix: "" )."'>`n",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='1' ".(($pre==1)?"checked":"")."> Als Prefix zum Namen<br>",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='0' ".(($pre==0)?"checked":"")."> Als Suffix zum Namen<br>",true);
    output("<input type=radio name='info[GuildPrefix][pre]' value='2' ".(($pre==2)?"checked":"")."> Ohne Kennzeichnung<br>",true);

    output("`n* Beschreibung des Clans: `n<textarea name='info[PublicText]' size=10 cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['PublicText']: "" )."</textarea>`n",true);
    output("`n* Text für Bewerber: `n<textarea name='info[ApplyText]' cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['ApplyText']: "" )."</textarea>`n",true);
    output("`n* Regeln des Clans:`n <textarea name='info[RulesText]' cols=75 rows=5 title='Enter what will be displayed to outsiders'>".SafeDisplayString(($HasData)? $Info['RulesText']: "" )."</textarea>`n",true);
    output("`nLink: `n<input name='info[ExternalPagesLink]' size=100 value='".SafeDisplayString(($HasData)? $Info['ExternalPagesLink']: "" )."'>",true);

    if ($SU) {
        output("`nAccount ID's of:");
        output("`n&nbsp;&nbsp;$ClanLeader: `n<input name='info[GuildLeader]' length=9 size=10 value='".SafeDisplayString(($HasData)? $Info['GuildLeader']: "" )."'>`n",true);
    }

    if ($Admin) {
     $type="";
    } else {
     $type=" type='hidden' ";
    }


    if (!is_array($Info['Hitlist'])) $Info['Hitlist']=array();
    $HitListDisplay=HitlistToNames($Info['Hitlist'], false);
    output("`nHitList: `n<input name='info[Hitlist]' readonly ".$type."size=100 value='".SafeDisplayString($HitListDisplay)."'>",true);

    DisplaySitePoints($Info['ID']);

    if ($SU) {
     $type="";
    } else {
     $type=" readonly ";
    }

    output("`nGold: `n<input name='info[gold]' ".$type." size=10 value='".(($HasData)? $Info['gold']: "" )."'>",true);
    output("`nEdelsteine: `n<input name='info[gems]' ".$type." size=10 value='".(($HasData)? $Info['gems']: "" )."'>",true);
    output("`nClanpunkte: `n<input name='info[SitePoints]' ".$type." size=5 value='".(($HasData)? $Info['SitePoints']: "" )."'>",true);

    if ($Admin) {
        output("`n`n<input type='submit' class='button' value='Save'>",true);
    } else {
        output("`n`nWenn Du das Pergament ausgefüllt hast, drücke Antrag. Du wirst die Angaben
        noch einmal prüfen können, bevor Dein Pergament endgültig den Göttern vorgelegt wird.");
        output("`n`n<input type='submit' class='button' value='Antrag'>",true);
    }
}

function display_guild_info($Info) {

    global $GuildLeader, $HeadOfWar, $HeadOfMembership;

// Display and verify the Guild Information
// Checks if the mandatory information has been entered and checks for bad language

    $badtext = "`4 enthält unerwünschte Begriffe`0";

    $InvalidLanguage=0;
    $MissingVitalInfo=false;

    $soapname = soap($GuildName=$Info['Name']);
    if ($soapname!=$GuildName) {
        $Info['Name'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($GuildName=="") $MissingVitalInfo=true;
    }

    $soapname = soap($Description=$Info['PublicText']);
    if ($soapname!=$Description) {
        $Info['PublicText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($Description=="") $MissingVitalInfo=true;
    }

    $soapname = soap($ApplicantText=$Info['ApplyText']);
    if ($soapname!=$ApplicantText) {
        $Info['ApplyText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($ApplicantText=="") $MissingVitalInfo=true;
    }

    $soapname = soap($RulesText=$Info['RulesText']);
    if ($soapname!=$RulesText) {
        $Info['RulesText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($RulesText=="") $MissingVitalInfo=true;
    }

    $Link=$Info['link'];

    $GuildPrefix_display=$Info['GuildPrefix']['display'];
    if (strlen(color_sanitize($GuildPrefix_display))>3) {
        $Info['GuildPrefix']['display']= substr(color_sanitize($GuildPrefix_display),0,3);
    }

    output("<form action='guild.php?op=manage&action=create&type=guild&status=submit&id=".$Info['ID']."' method='POST'>",true);
    output("`n`3Name der Guilde: `n`0".$Info['Name']);
    output("`n`n`3Gildenkürzel: `n`0".$Info['GuildPrefix']['display']);
    output("`n`n`3Beschreibung der Gilde: `n`0".$Info['PublicText']);
    output("`n`n`3Text für Bewerber: `n`0".$Info['ApplyText']);
    output("`n`n`3Regeln der Gilde: `n`0".$Info['RulesText']);
    output("`n`n`3Link: `n`0".$Info['ExternalPagesLink']);
    $sql="select name from accounts where acctid=".$Info['GuildLeader'];
    $result=db_query($sql);
    $row=db_fetch_assoc($result);
    output("`n`n`3$GuildLeader: `n`0".$row['name']);
    $Info['BadLanguage']=$InvalidLangauge;
    $Info['MissingVitalInfo']=$MissingVitalInfo;
    $tmp=base64_encode(serialize($Info));
    output("<input type='hidden' name='info' value='$tmp'>",true);
    if ($InvalidLangauge!=true) {
        if (!isset($Info['ID']) or ($Info['ID']=="")) {
            output("`n`n<input type='submit' class='button' value='Antrag abgeben'>",true);
        } else {
            output("`n`n<input type='submit' class='button' value='Daten aktualisieren'>",true);
        }
    } else {
        output("`n`n`i`bDein Antrag enthält Begriffe, die zensiert werden müssten.`n
        Bitte ändere Deinen Antrag!`b`i",true);
        output("`n`n<input type='submit' class='button' value='Antrag ändern'>",true);
    }
    addnav("","guild.php?op=manage&action=create&type=guild&status=submit&id=".$Info['ID']);
}

function display_clan_info($Info) {

    global $ClanLeader;

// Display and verify the Clan Information
// Checks if the mandatory information has been entered and checks for bad language

    $badtext = "`4 enthält unerwünschte Begriffe`0";

    $InvalidLanguage=0;
    $MissingVitalInfo=false;

    $soapname = soap($GuildName=$Info['Name']);
    if ($soapname!=$GuildName) {
        $Info['Name'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($GuildName=="") $MissingVitalInfo=true;
    }

    $soapname = soap($Description=$Info['PublicText']);
    if ($soapname!=$Description) {
        $Info['PublicText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($Description=="") $MissingVitalInfo=true;
    }

    $soapname = soap($ApplicantText=$Info['ApplyText']);
    if ($soapname!=$ApplicantText) {
        $Info['ApplyText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($ApplicantText=="") $MissingVitalInfo=true;
    }

    $soapname = soap($RulesText=$Info['RulesText']);
    if ($soapname!=$RulesText) {
        $Info['RulesText'] = $soapname.$badtext;
        $InvalidLangauge=1;
    } else {
        if ($RulesText=="") $MissingVitalInfo=true;
    }

    $Link=$Info['link'];

    $GuildPrefix_display=$Info['GuildPrefix']['display'];
    if (strlen(color_sanitize($GuildPrefix_display))>3) {
        $Info['GuildPrefix']['display']= substr(color_sanitize($GuildPrefix_display),0,3);
    }

    output("<form action='clan.php?op=manage&action=create&type=clan&status=submit&id=".$Info['ID']."' method='POST'>",true);
    output("`n`3Name des Clans: `n`0".$Info['Name']);
    output("`n`n`3Clankürzel: `n`0".$Info['GuildPrefix']['display']);
    output("`n`n`3Beschreibung des Clans: `n`0".$Info['PublicText']);
    output("`n`n`3Text für Bewerber: `n`0".$Info['ApplyText']);
    output("`n`n`3Regeln des Clans: `n`0".$Info['RulesText']);
    output("`n`n`3Link: `n`0".$Info['ExternalPagesLink']);
    $sql="select name from accounts where acctid=".$Info['GuildLeader'];
    $result=db_query($sql);
    $row=db_fetch_assoc($result);
    output("`n`n`3$ClanLeader: `n`0".$row['name']);
    $Info['BadLanguage']=$InvalidLangauge;
    $Info['MissingVitalInfo']=$MissingVitalInfo;
    $tmp=base64_encode(serialize($Info));
    output("<input type='hidden' name='info' value='$tmp'>",true);
    if ($InvalidLangauge!=true) {
        if (!isset($Info['ID']) or ($Info['ID']=="")) {
            output("`n`n<input type='submit' class='button' value='Antrag abgeben'>",true);
        } else {
            output("`n`n<input type='submit' class='button' value='Daten aktualisieren'>",true);
        }
    } else {
        output("`n`n`i`bDein Antrag enthält Begriffe, die zensiert werden müssten.`n
        Bitte ändere Deinen Antrag!`b`i",true);
        output("`n`n<input type='submit' class='button' value='Antrag ändern'>",true);
    }
    addnav("","clan.php?op=manage&action=create&type=clan&status=submit&id=".$Info['ID']);
}

function HitlistToNames($Hitlist, $ReturnAsArray=false) {

// Turns the passed in list (string in the form of {n[,n]} where n is the acctid | array of acctid's)
// tuned to returning names and bounties
// Use list to names if you want more information

    if (is_array($Hitlist)) {
     $UserSearch=implode(",",array_keys($Hitlist));
    } else {
     $UserSearch=$Hitlist;
    }

    if ($UserSearch!=""){
        $sql = "select acctid, name, bounty, login from accounts where acctid in (".$UserSearch.")";
        $NameResult = db_query($sql);
        $HitListNames="";
        $ArrayResult=array();
        $RowCount=db_num_rows($NameResult);

        for ($i=0;$i<$RowCount;$i++){
             $NameRows = db_fetch_assoc($NameResult);
             $ArrayResult[]=$NameRows;
             $HitListNames.=$NameRows['name']." `&- `^".$NameRows['bounty']."`& Gold".(($i<($RowCount-1))? ", ":".");
        }
    } else {
        $HitListNames="None";
    }

//db_free_result($NameResult);
  if ($ReturnAsArray!=false) {
    return $ArrayResult;
  } else {
       return $HitListNames;
  }
}

function ListToNames($SearchList, $ReturnAsArray=false) {

// Turns the passed in list (string in the form of {n[,n]} where n is the acctid | array of acctid's)
// tuned to returning names, rank

    if (is_array($SearchList)) {
     $UserSearch=implode(",",array_keys($SearchList));
    } else {
     $UserSearch=$SearchList;
    }

    if ($UserSearch!=""){
        $sql = "select accounts.acctid, accounts.name, accounts.guildid, accounts.guildrank, lotbd_guildranks.displaytitle
                from accounts
                     left join lotbd_guildranks
                     on accounts.guildrank=lotbd_guildranks.rankID
                        and accounts.guildid=lotbd_guildranks.guildid
                where accounts.acctid in (".$UserSearch.")";

        $NameResult = db_query($sql);
        $ListNames="";
        $ArrayResult=array();
        $RowCount=db_num_rows($NameResult);

        for ($i=0;$i<$RowCount;$i++){
             $NameRows = db_fetch_assoc($NameResult);
             $ArrayResult[$NameRows['acctid']]=$NameRows;
             $ListNames.=$NameRows['name']."".(($i<($RowCount-1))? ", ":".");
        }
    } else {
        $ListNames="None";
    }

//db_free_result($NameResult);
  if ($ReturnAsArray!=false) {
    return $ArrayResult;
  } else {
       return $ListNames;
  }
}

function Display_Record($ThisGuild){

    global $GuildLeader, $HeadOfWar, $HeadOfMembership, $vacant, $ClanLeader;
    global $SPnames;

// Names of Leaders
$UserSearch=$ThisGuild['HeadOfWar'].",".$ThisGuild['GuildLeader'].",".$ThisGuild['HeadOfMembership'];
$sql = "select acctid, name from accounts where acctid in (".$UserSearch.")";
$NameResult = db_query($sql);
$GuildLeaderName = $HeadOfMembershipName = $HeadOfWarName = "`i$vacant`i";

for ($i=0;$i<db_num_rows($NameResult);$i++){
     $NameRows = db_fetch_assoc($NameResult);
     if ($NameRows['acctid']==$ThisGuild['GuildLeader']) $GuildLeaderName=$NameRows['name'];
     if ($NameRows['acctid']==$ThisGuild['HeadOfMembership']) $HeadOfMembershipName=$NameRows['name'];
     if ($NameRows['acctid']==$ThisGuild['HeadOfWar']) $HeadOfWarName=$NameRows['name'];
}

// Guild/Clan Status
 switch ($ThisGuild['Status']) {
         case "-999":
           $statustext="`4Denied";
         break;
         case "0":
           $statustext="`%Pending";
         break;
         case "1":
           $statustext="`2Active";
         break;
         default:
           $statustext="`0Unknown";
         break;
 }

$HitListNames = HitlistToNames($ThisGuild['Hitlist']);
if ( $ThisGuild['IsGuild']==1) {
$members=FindGuildMembers($ThisGuild['ID'], true);
} else {
    $members=FindClanMembers($ThisGuild['ID'], true);
}
$display="";
$ranks=PopulateRanks($ThisGuild['ID']); // handles when no rank has been set

output("`n`0");

//table part 1
if ( $ThisGuild['IsGuild'] ) {
    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='left'>",true);
    output("<tr class='trhead'><td>`bAllgemeine`nInformationen`b</td><td>`b `b</td></tr>",true);
    output("<tr class='trlight'><td>`0Name</td><td>`%".$ThisGuild['Name']."</td>",true);
    output("<tr class='trlight'><td>`0Namenszusatz</td><td>`%".$ThisGuild['GuildPrefix']['display']."</td>",true);
    output("<tr class='trlight'><td>`0Status</td><td>`%".$statustext."</td>",true);
    output("<tr class='trlight'><td>`0Mitglieder</td><td>`%".count($members)."</td>",true);
    output("<tr class='trlight'><td>`0Gilde oder Clan</td><td>`%".(($ThisGuild['IsGuild']==1)? "Guild": "Clan")."</td>",true);
    output("<tr class='trlight'><td>`0Beschreibung der Gilde</td><td>`%".$ThisGuild['PublicText']."</td>",true);
    output("<tr class='trlight'><td>`0Text für Bewerber</td><td>`%".$ThisGuild['ApplyText']."</td>",true);
    output("<tr class='trlight'><td>`0Regeln der Gilde</td><td>`%".$ThisGuild['RulesText']."</td>",true);
    output("<tr class='trlight'><td>`0Link</td><td>`%".$ThisGuild['Name']."</td>",true);
    output("<tr class='trlight'><td>`0$GuildLeader</td><td>`%".$GuildLeaderName."</td>",true);
    output("<tr class='trlight'><td>`0$HeadOfMembership</td><td>`%".$HeadOfMembershipName."</td>",true);
    output("<tr class='trlight'><td>`0$HeadOfWar</td><td>`%".$HeadOfWarName."</td>",true);
    output("<tr class='trlight'><td>`0Hit List</td><td>`%".$HitListNames."</td>",true);
} else {
    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='left'>",true);
    output("<tr class='trhead'><td>`bAllgemeine`nInformationen`b</td><td>`b `b</td></tr>",true);
    output("<tr class='trlight'><td>`0Name</td><td>`%".$ThisGuild['Name']."</td>",true);
    output("<tr class='trlight'><td>`0Namenszusatz</td><td>`%".$ThisGuild['GuildPrefix']['display']."</td>",true);
    output("<tr class='trlight'><td>`0Status</td><td>`%".$statustext."</td>",true);
    output("<tr class='trlight'><td>`0Mitglieder</td><td>`%".count($members)."</td>",true);
    output("<tr class='trlight'><td>`0Gilde oder Clan</td><td>`%".(($ThisGuild['IsGuild']==1)? "Guild": "Clan")."</td>",true);
    output("<tr class='trlight'><td>`0Beschreibung des Clans</td><td>`%".$ThisGuild['PublicText']."</td>",true);
    output("<tr class='trlight'><td>`0Text für Bewerber</td><td>`%".$ThisGuild['ApplyText']."</td>",true);
    output("<tr class='trlight'><td>`0Regeln des Clans</td><td>`%".$ThisGuild['RulesText']."</td>",true);
    output("<tr class='trlight'><td>`0Link</td><td>`%".$ThisGuild['Name']."</td>",true);
    output("<tr class='trlight'><td>`0$ClanLeader</td><td>`%".$GuildLeaderName."</td>",true);
    output("<tr class='trlight'><td>`0Hit List</td><td>`%".$HitListNames."</td>",true);
}

    //table part 2
    output("<tr class='trhead'><td>`bMitglieder`nRang`b</td><td>`b`nName`b</td></tr>",true);
    foreach ($members as $member) {
     // Traverse the array and display the names ane their rank
        if (!isset($member['displaytitle'])) {
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$ranks[0]['DisplayTitle']."</td><td>".$member['name']."</td>",true);
        } else {
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$member['displaytitle']."</td><td>".$member['name']."</td>",true);
        }
    }

    //table part 3
if ( $ThisGuild['IsGuild'] ) {
    output("<tr class='trhead'><td>`bÜbersicht`nGildenpunkte und Verwendung`b</td><td>`b`nWert`b</td></tr>",true);
    output("<tr class='trdark'><td>`0Vermögen</td><td> </td>",true);
    output("<tr class='trlight'><td>`0Gold</td><td>`%".$ThisGuild['gold']."</td>",true);
    output("<tr class='trlight'><td>`0Edelsteine</td><td>`%".$ThisGuild['gems']."</td>",true);
    output("<tr class='trdark'><td>`0neue Gildenpunkte</td><td> </td>",true);
    output("<tr class='trlight'><td>`0offene Punkte</td><td>`%".$ThisGuild['SitePoints']."</td>",true);
    output("<tr class='trdark'><td>`0eingesetzte Gildenpunkte</td><td> </td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Banking Interest']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['Bank']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Healing Discount']."</td><td>`%".$ThisGuild['HealDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Gem Discount']."</td><td>`%".$ThisGuild['GemPurchaseDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Weapon Purchase Discount']."</td><td>`%".$ThisGuild['WeaponDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Armor Purchase Discount']."</td><td>`%".$ThisGuild['ArmourDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Potion Discount']."</td><td>`%".$ThisGuild['PotionDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Training Discount']."</td><td>`%".$ThisGuild['TrainDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['house']."</td><td>`%".$ThisGuild['OtherSitepoints']['house']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['mount']."</td><td>`%".$ThisGuild['OtherSitepoints']['mount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Percent Earned from PvP']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['PvP']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Percent Earned from FF']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['FF']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['PvP Purchase Discount']."</td><td>`%".$ThisGuild['PvPDiscount']."</td>",true);
} else {
    output("<tr class='trhead'><td>`bÜbersicht`nClanpunkte und Verwendung`b</td><td>`b`nWert`b</td></tr>",true);
    output("<tr class='trdark'><td>`0Vermögen</td><td> </td>",true);
    output("<tr class='trlight'><td>`0Gold</td><td>`%".$ThisGuild['gold']."</td>",true);
    output("<tr class='trlight'><td>`0Edelsteine</td><td>`%".$ThisGuild['gems']."</td>",true);
    output("<tr class='trdark'><td>`0neue Clanpunkte</td><td> </td>",true);
    output("<tr class='trlight'><td>`0offene Punkte</td><td>`%".$ThisGuild['SitePoints']."</td>",true);
    output("<tr class='trdark'><td>`0eingesetzte Clanpunkte</td><td> </td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Banking Interest']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['Bank']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Healing Discount']."</td><td>`%".$ThisGuild['HealDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Gem Discount']."</td><td>`%".$ThisGuild['GemPurchaseDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Weapon Purchase Discount']."</td><td>`%".$ThisGuild['WeaponDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Armor Purchase Discount']."</td><td>`%".$ThisGuild['ArmourDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Potion Discount']."</td><td>`%".$ThisGuild['PotionDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Training Discount']."</td><td>`%".$ThisGuild['TrainDiscount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['house']."</td><td>`%".$ThisGuild['OtherSitepoints']['house']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['mount']."</td><td>`%".$ThisGuild['OtherSitepoints']['mount']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Percent Earned from PvP']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['PvP']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['Percent Earned from FF']."</td><td>`%".$ThisGuild['PercentOfFightsEarned']['FF']."</td>",true);
    output("<tr class='trlight'><td>`0".$SPnames['PvP Purchase Discount']."</td><td>`%".$ThisGuild['PvPDiscount']."</td>",true);
}
    output("</TABLE>`n`n`n",true);
}

function RemoveUserFromMgmt($who) {

// Removes somebody from the management team - aimed to be used from User.php for Superusers
// If a Guild:
//   If the user being removed is the GuildLeader then try and assign the guild leader from the other mgmt members
//   If this cannot be done then pick a random member if possible
//   Otherwise set the GuildLeader to 0
// If a clan:
//   Pick a random member if one exists
//

global $session;

    $sql = "select guildID, ClanID from accounts where acctid=".$who;
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $guildID = $row['guildID']; $clanID = $row['clanID'];
    if (IsOnManagementTeam($guildID,$who)) {
        $ThisGuild=&$session['guilds'][$guildID];
        if ($ThisGuild['GuildLeader']==$who) {
            if ($ThisGuild['HeadOfWar']<>0) {
                $ThisGuild['GuildLeader']=$ThisGuild['HeadOfWar'];
            } else {
                if ($ThisGuild['HeadOfMembership']<>0) {
                    $ThisGuild['GuildLeader']=$ThisGuild['HeadOfMembership'];
                } else {
                    // There are no management members
                    $NewMgr=RandomGuildMember($guildID,$who);
                    $ThisGuild['GuildLeader']=$NewMgr;
                }
            }
        }
        if ($ThisGuild['HeadOfWar']==$who) {
            $ThisGuild['HeadOfWar']=0;
        }

        if ($ThisGuild['HeadOfMembership']==$who) {
            $ThisGuild['HeadOfMembership']=0;
        }
    update_guild_info($ThisGuild);
    }

    if (IsOnManagementTeam($clanID,$who)) {
        $ThisClan=$session['guilds'][$clanID];
        if ($ThisClan['GuildLeader']==$who) {
            $ThisClan['GuildLeader']=RandomGuildMember($clanID,$who);
        }
    update_guild_info($ThisClan);
    }
}

function RandomGuildMember($guildID, $exclude="") {

//
// Find a random member of a guild
// optionally exclude one or more people
//

global $session;

    if ($exclude!="") $exclude=" and acctid not in (".$exclude.")";

    $ThisGroup=&$session['guilds'][$guildID];
    if ($ThisGroup['IsGuild']==1) {
        $sqlType=" guildID=".$guildID;
    } else {
        $sqlType=" clanID=".$guildID;
    }

    // How many players are we talking about?
    $sql="select count(*) as Players from accounts where ".$sqlType.$exclude;
    $result=db_query($sql);
    $row=db_fetch_assoc($result);
    $players=$row['Players'];
    // Pick a number between 1 and the size of the pot
    if ($players>1) {
        $val=e_rand(1,$players);
        if ($val==0) return 0;
        $sql="select acctid from accounts where ".$sqlType.$exclude." limit ".$val.",1";
    } else {
        if ($players==1) {
            // There is only one choice!
            $sql="select acctid from accounts where ".$sqlType.$exclude;
        } else {
            // There is nobody to select
            return 0;
        }
    }

    $result=db_query($sql);
    $row=db_fetch_assoc($result);
    $who=$row['acctid'];

    return $who;
}

function listall_guildclan($su=0){
// gargamel
// list all available guilds an clans
// code based upon guilds-clans.php?op=nonmember&action=list by Dasher.
//
global $session;

    // Display outstanding applications
    unset($session['guilds'][null]);
    $i=1;
    $anz = count($session['guilds']);
    $anzguild=$anzclan=0;
    foreach ($session['guilds'] as $ThisGuild){
        if ( $ThisGuild['IsGuild'] ) $anzguild++;
        else $anzclan++;
    }
    if ($anzguild==0 && $anzclan ==0) {
        output("`n`0Aktuell sind `3keine`0 Gilden vorhanden!`n");
        output("`n`0Aktuell sind `3keine`0 Clans vorhanden!`n");
        if ($su) {
            output("`nGehe ins Dorf und amüsier Dich!!!");
        }
    } else {
        if ($su) $verb = "zuverwalten";
        else $verb = "vorhanden";
        if ( $anzguild==0 ) {
             output("`n`0Aktuell sind `3keine`0 Gilden vorhanden!`n");
        } else {
            $helpid=100;
            output("`n`0Aktuell ".(($anzguild==1)?"ist":"sind").
            " `^".$anzguild."`0 Gilde".
            (($anzguild==1)?"":"n")." ".$verb.":`n`n");
            output("<table border=0 cellpadding=4 cellspacing=1 bgcolor='#999999' align='center'>",true);
            output("<tr class='trhead'><td>`bID`b</td><td>`bName`b</td><td>`bStatus`b</td>",true);
            foreach ($session['guilds'] as $ThisGuild){
                if ( $ThisGuild['IsGuild'] ) {
                    switch ($ThisGuild['Status']) {
                    case "-999":
                    $statustext="`4Denied";
                    break;

                    case "0":
                    $statustext="`%Pending";
                    break;

                    case "1":
                    $statustext="`2Active";
                    break;

                    default:
                    $statustext="`0Unknown";
                    break;
                    }
                    if ($su) $link="guilds-clans-su.php?type=applications&display=item&id=";
                    else $link="guild.php?op=nonmember&action=examine&id=";
                    
                    output("<tr class='".($i%2?"trlight":"trdark")."'>
                    <td>".$ThisGuild['ID']."</td>
                    <td><a href='".$link.$ThisGuild['ID']."'>".$ThisGuild['Name']."</td>
                    <td>`i".$statustext."`i</td></a>",true);
                    addnav("",$link.$ThisGuild['ID']);
                }
            }
            output("</table>",true);
        }
        if ( $anzclan==0 ) {
             output("`n`0Aktuell sind `3keine`0 Clans vorhanden!`n");
        } else {
            $helpid=100;
            output("`n`0Aktuell ".(($anzclan==1)?"ist":"sind").
            " `^".$anzclan."`0 Clan".
            (($anzclan==1)?"":"s")." ".$verb.":`n`n");
            output("<table border=0 cellpadding=4 cellspacing=1 bgcolor='#999999' align='center'>",true);
            output("<tr class='trhead'><td>`bID`b</td><td>`bName`b</td><td>`bStatus`b</td>",true);
            foreach ($session['guilds'] as $ThisClan){
                if ( !$ThisClan['IsGuild'] ) {
                    switch ($ThisClan['Status']) {
                    case "-999":
                    $statustext="`4Denied";
                    break;

                    case "0":
                    $statustext="`%Pending";
                    break;

                    case "1":
                    $statustext="`2Active";
                    break;

                    default:
                    $statustext="`0Unknown";
                    break;
                    }
                    if ($su) $link="guilds-clans-su.php?type=applications&display=item&id=";
                    else $link="clan.php?op=nonmember&action=examine&id=";
                    output("<tr class='".($i%2?"trlight":"trdark")."'>

                    <td>".$ThisClan['ID']."</td>
                    <td><a href='".$link.$ThisClan['ID']."'>".$ThisClan['Name']."</td>
                    <td>`i".$statustext."`i</td></a>",true);
                    addnav("",$link.$ThisClan['ID']);
                }
            }
            output("</table>",true);
        }
    }
}

?>


