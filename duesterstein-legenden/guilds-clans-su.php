
<?php
//
// ----------------------------------------------------------------------------
//
// Clans/Guilds Pages
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
//
// Gargamel:
// This module contains the superuser funcs from the original guilds-clans.php
// The mentioned guilds-clans.php is still mandatory. Please see comment there
// for install instructions and credits.
//
// ----------------------------------------------------------------------------
//

require_once "common.php";
require_once "guildclanfuncs.php";
if ($session[user][alive]==0) redirect("shades.php");

page_header("Guilds/Clans");
output("`c`& ~ Guilds/Clans ~ `c",true);
addcommentary();
checkday(true);
populate_guilds();  // Ensures we always have the most up to date info in the session

//variables by Gargamel
$helpid=1;

global $NavSystem;

if ($session[user][superuser]>=2) {
    $NavSystem["Aufsichtsamt"]["b?Übersicht"]="guilds-clans-su.php?type=applications";
}

// standard navs (bottom) - 0:visible 1:suppressed
$nonav = 0;

$guildID=$HTTP_GET_VARS['id'];
$ManageGuild=&$session['guilds'][$guildID];
if ( $ManageGuild['IsGuild'] ) $was = "Gilde";
   else $was = "Clan";
   
switch ($HTTP_GET_VARS['type']){
    case "applications":
    switch ($HTTP_GET_VARS['action']){
        case "delete":
        switch ($HTTP_GET_VARS['stage']) {
            case "2":
            output("`nRemoving all the Ranks associated with this $was");
            $sql="delete from lotbd_guildranks where GuildID=".$guildID;
            db_query($sql);
            output("`nAll Ranks Deleated");
            output("`nUpdating all accounts...");
            $sql="select count(*) as affected from accounts where guildID=".$guildID." OR clanID=".$guildID;
            $result=db_query($sql);
            $row=db_fetch_assoc($result);
            output("`n ...... there are ".$row['affected']." accounts affected");
            db_free_result($result);
            // Update any acocunts that have the guildID set to this guild
            $sql="update accounts set goldafterdk=0, clanID=0, guildID=0, guildrank=0 where guildid=".$guildID." OR clanID = ".$guildID;
            db_query($sql);
            output("`nAccounts have been changed!");
            output("`nDeleting $was Info");
            $sql="delete from lotbd_guilds where ID=".$guildID;
            db_query($sql);
            output("`n$was has been deleted");
            break;

            default:
            if ( $ManageGuild['IsGuild'] ) {
                output("`nWillst du wirklich die Gilde: ".$ManageGuild['Name']."`n
                jetzt `$ unwiderruflich löschen`0?`n");
            } else {
                output("`nWillst du wirklich den Clan: ".$ManageGuild['Name']."`n
                jetzt `$ unwiderruflich löschen`0?`n");
            }
            $NavSystem["$was löschen"]["J?`\$Ja, löschen"]="guilds-clans-su.php?type=applications&action=delete&id=".$guildID."&stage=2";
            $NavSystem["$was löschen"]["N?Nein - zurück"]="guilds-clans-su.php?type=applications";
            unset($NavSystem["Aufsichtsamt"]);
            $nonav=1;
            break;
        }
        break;

        case "save":
        $Info=($_POST["info"]);
        update_guild_info($Info);
        output("`n`&Information has been saved`n");
        break;

        case "edit":
        if ( $ThisGuild['IsGuild'] ) {
            display_edit_guild($ManageGuild,true,true);//####
        } else {
            display_edit_clan($ManageGuild,true,true);//####
        }
        break;

        case "pending": //###
        $ManageGuild['Status']=0;
        update_guild_info($ManageGuild);
        if ( $ManageGuild['IsGuild'] ) {
            output("Guild application marked as pending");
            systemmail($ManageGuild['GuildLeader'],"`%Guild Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Guild to Pending.`n`nReply to this
                  mail for more information", $session['user']['acctid']);
        } else {
            output("Clan application marked as pending");
            systemmail($ManageGuild['GuildLeader'],"`%Clan Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Clan to Pending.`n`nReply to this
                  mail for more information", $session['user']['acctid']);
        }
        break;

        case "approve": //###
        $ManageGuild['Status']=1;
        update_guild_info($ManageGuild);
        if ( $ManageGuild['IsGuild'] ) {
            output("Guild application Approved");
            // Inform the owner
            systemmail($ManageGuild['GuildLeader'],"`%Guild Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Guild to Approved.`n Congrats people
                  can now apply to join the guild.  Good luck!",
                  $session['user']['acctid']);
        } else {
            output("Clan application Approved");
            // Inform the owner
            systemmail($ManageGuild['GuildLeader'],"`%Clan Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Clan to Approved.`n Congrats people
                  can now apply to join the clan.  Good luck!",
                  $session['user']['acctid']);
        }
        break;

        case "deny": //###
        $ManageGuild['Status']=-999;
        update_guild_info($ManageGuild);
        if ( $ManageGuild['IsGuild'] ) {
            output("Guild application Deny");
            systemmail($ManageGuild['GuildLeader'],"`%Guild Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Guild to Denied.`n`nReply to this
                  mail for more information", $session['user']['acctid']);
        } else {
            output("Clan application Deny");
            systemmail($ManageGuild['GuildLeader'],"`%Clan Status changed `&["
                  .$ManageGuild['Name']."`&]",
                  ($session['user']['name'])." `0has changed the status of "
                  .$ManageGuild['Name']."`0 Clan to Denied.`n`nReply to this
                  mail for more information", $session['user']['acctid']);
        }
        break;


        default:
        $guildID=$HTTP_GET_VARS['id'];
        $ManageGuild=&$session['guilds'][$guildID];
        switch ($HTTP_GET_VARS['display']){
            case "item":
            $backhelp=101;
            $NavSystem["Aufsichtsamt"]["D?Daten ändern"]="guilds-clans-su.php?type=applications&action=edit&id=".$guildID;
            switch ($ManageGuild['Status']) {
                case "-999":
                $NavSystem["Aufsichtsamt"]["Antrag akzeptieren"]="guilds-clans-su.php?type=applications&action=approve&id=".$guildID;
                $NavSystem["Aufsichtsamt"]["Antrag zurückstellen"]="guilds-clans-su.php?type=applications&action=pending&id=".$guildID;
                $NavSystem["Aufsichtsamt"]["$was löschen"]="guilds-clans-su.php?type=applications&action=delete&id=".$guildID;
                break;
                
                case "0":
                $NavSystem["Aufsichtsamt"]["Antrag akzeptieren"]="guilds-clans-su.php?type=applications&action=approve&id=".$guildID;
                $NavSystem["Aufsichtsamt"]["Antrag ablehnen"]="guilds-clans-su.php?type=applications&action=deny&id=".$guildID;
                break;
                
                case "1":
                $NavSystem["Aufsichtsamt"]["Antrag ablehnen"]="guilds-clans-su.php?type=applications&action=deny&id=".$guildID;
                $NavSystem["Aufsichtsamt"]["Antrag zurückstellen"]="guilds-clans-su.php?type=applications&action=pending&id=".$guildID;
                break;

                default:
                break;
            }
            Display_Record($ManageGuild);
            break;

            default:
            // Display outstanding applications
            listall_guildclan(1);
        }

        break;

    }
    break;

    default:
    //output("Display other options");
    break;
}

$linkback = URLEncode(calcreturnpath());
if ( $nonav == 0 ) {
    $NavSystem["Auskunftsbüro"]["A?Alle Gilden/Clans"]="guild.php?op=nonmember&action=list";
    $NavSystem["Auskunftsbüro"]["H?Hilfe"]="guildclanhelp.php?id=".$helpid."&ret=".$linkback;
    $NavSystem["Sonstiges"]["Z?Zurück zum Dorf"]="village.php";
}

PopulateNavs();

page_footer();
?>


