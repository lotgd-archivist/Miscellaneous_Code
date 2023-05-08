
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
// This module processes the user actions, the included funcs module
// provides the main/supporting functionality.
//
// The activities are in the form:
//     op= Activity type {SU|Manage|member|nonmember}
//     action= Action to be performed
//     task=sub task
//
//     ID=The guild|Clan ID
//     who=The member affected
//     type=Where functionality depends if it's a guild or a clan
//     stage=When a multi stage action is being performed - keeps track of progress
//
// A Nav Management system has been implemented to simplify functionality
// in this and the funcs module, and to aid customisation.
// The nav system is for User Display of Nav's and isn't used for hidden Nav's (HTML Forms/Display URL's)
//
// The following examples illustrate how it works:
//     NavSystem["Separator"]="";
//     NavSystem["DisplayName"]="url";
//     NavSystem["Separator"]["DisplayName"]="url";
//
// ----------------------------------------------------------------------------
// Changelog:
//   Ranks, NavSysem, Split into 2 modules: guild.php and guildclanfuncs.php
//   Many functions:
//          now support a dymanic return path
//          are aware of the guilds/clans diff
//          Deleting a user now changes the management team if they are a member
//   Pay guild funds to members
//   Simplified the URL paths
//   SU can now modify the management team
//   Promote members to the management team
//   Ranks can now be assigned
//   Tidied up the display in many funcs - still needs more work
//   Custom Weapons & Armor can now be defined in each guild
//   Going into the Weaponry/Armoury via the guilds (setting the guild=GuildID attribute in the URL) now activates guild functionality
//   with guild discounts on standard items, access to Special Guild Items and extra earned on trade in price
//   Now you can spend SitePoints on discounts and revamped the display of Discounts
//   Interest earned on Guild Funds (just gold), interest is accrued once per real day on management member entering
//   The Guild TLA can now be a prefix/suffix next to the display name in chat
//   ViewCommentary code now links to the display summary info for the guild
//   Fixed a bug with the default ranks (rankorder 0 & 20), now created if they don't exist in PopulateRanks
//   Fixed a bug in the weapon/Armor upgrade
//   Fixed a bug in the spying
//   Fixed a return path problem in Armor.php/Weapons.php
//   Modified common: Checkday, If passed true, checks if the user is dead, clears the output buffers if set and redirects to the shades if they are dead
//   and now checks for newday in guild.php
//   Updated the Healer to use the same stage process
//   Removed some latent debug info
//   Updated common: loadsettings/getsettings/savesettings to always use a lowercase settingname
//   Cost of upgrading weapons/armor is progressively more expensive and the weapon/armor value increases by 10% of the upgrade cost
//
// Settings used with GetSettings:
//      MaxInterest - Max Bank Interest, Default:10
//      MaxFightPercent - Maximum % from fights (FF/PvP), Default: $MaxInterest
//      RatioOfSitePoints - Ratio of SitePoints to % increase, Default: 5
//      WeaponUpgradeCost - Cost of Upgrading Weapons, Default: 500
//      ArmourUpgradeCost - Cost of Upgrading Armour, Default: 500
// ----------------------------------------------------------------------------
//
// ToDO:
//  Differentiate Clans from Guilds - High Priority
//  Mail other management team members when a management member leaves - low Priority
//  Spending of SitePoints needs to be a little more balanced and there needs to be some way of unspending SitePoints
//  Partially done - currently Each DK in dragon.php earns 1 sitepoint for a normal kill and 2 SitePoints for a flawless fight
//                 - PvP earns sitepoints:
//                       -2 => killing somebody on your clan
//                       +1 => standard kill
//                       +1 => Member on your guild hitlist
//                       +1 => You are on their hitlist
//                       +1 => Their guild in on your guild hitlist (Guild Wars)
//
//  Council Management
//      Assign some kind of reason when an application is denied - low Priority
//  Guild Management
//      Define votes - medium Priority
//      Currently there are no limits on how much you can upgrade weapons/armor - the cost is progressivly more expensive
//  Member Options
//      Vote - medium Priority
//  Site changes
//      Guild Discounts, update the other pages to support the discounts - background
//      Bio update - high Priority
//
// ----------------------------------------------------------------------------
// Gargamel:
// - renamed to guild.php
// - navigation options by variables: supress parts of the whole menu
// - all superuser options transferred into guilds-clans-su.php
// - totally separated for guilds
// - fixed bug of missing initial cost in case of withdraw guild application
// - chatarea "fireside" for management teams of ALL guilds
// - help system implemented. For help texts pls. see guildclanhelp.php
//
// Install instruction:
// - copy this file into your LOGD main folder.
//
// Contact Gargamel:
// eMail: gargamel@rabenthal.de or gargamel@silienta-logd.de
// Forum: Gargi at dragonprime
// ----------------------------------------------------------------------------
// Modified by Raven @ rabenthal.de - locate
// ----------------------------------------------------------------------------
//
require_once "common.php";
require_once "guildclanfuncs.php";

if ($session[user][alive]==0) redirect("shades.php");

page_header("Guilds/Clans");
$session[user][locate]=33;

output("`c`& ~ Guilds/Clans ~ `c",true);
addcommentary();
checkday(true);
populate_guilds();  // Ensures we always have the most up to date info in the session

//variables by Gargamel
$helpid=1;
$nonav = 0;  // standard navs (bottom) - 0:visible 1:suppressed
$retnav = 1; // standard return nav - 0:visible 1:suppressed

global $NavSystem;

// Are they a superuser?
if ($session[user][superuser]>=4 || $session[user][prayer]) {
    $NavSystem["Aufsichtsamt"]["b?Übersicht"]="guilds-clans-su.php?type=applications";
}

// ----------------------------------------------------------------------------
// Main decision tree
// ----------------------------------------------------------------------------
switch ($HTTP_GET_VARS['op']) {

// ----------------------------------------------------------------------------
// --         MANAGE
// ----------------------------------------------------------------------------

    case "manage": // Management functions for members who are on the management team
        $action=$HTTP_GET_VARS['action'];
        $guildID=$HTTP_GET_VARS['id'];
    get_clanguild_var($guildID);
    $session[user][locate]=15000+$guildID;
        $ManageGuild=&$session['guilds'][$guildID];
        $who=$HTTP_GET_VARS['who'];
        $helpid=400;

        nav_manageguildoptions($guildID);
        nav_guildoptions($guildID);
        nav_guildinfo($guildID);

        switch ($action) {
            case "sitepoints":
            ManageSitePoints($guildID);
            break;

            case "weapons":
            WeaponEditor($guildID);
            break;

            case "armor":
            ArmorEditor($guildID);
            break;

            case "create":
            unset($NavSystem["Management Options"]);
            unset($NavSystem["Return"]);

            $type=$HTTP_GET_VARS['type'];
            $status=$HTTP_GET_VARS['status'];
            switch ($status) {
                case "verifyInfo":
                
                unset($NavSystem["Management Options"]);
                unset($NavSystem["Return"]);
                $NavSystem["Zurück"]["Antrag verwerfen"]="guild.php";
                // Display data entered by the user, verify for bad language or missing information
                $Info=$_POST['info'];
                $Info['GuildLeader']=$session['user']['acctid'];
                display_guild_info($Info);
                break;

                case "submit":
                
                // Submit data to the council, update the information
                $tmp=base64_decode($_POST['info']);
                $Info=unserialize($tmp);
                if ($Info!=true) {
                    output("`n`nProblem getting data from Verify Form`n");
                } else {
                    if ($Info['BadLanguage']!=0) {
                        unset($NavSystem["Management Optionen"]);
                        unset($NavSystem["Mitglieder Optionen"]);
                        unset($NavSystem["Informationen"]);
                        $NavSystem["Zurück"]["Antrag verwerfen"]="guild.php";
                        output("`n`%*hicks* `&Unerwünschte Begriffe `%$@#%`& - Bitte Antrag überarbeiten.`n`n`n");
                        display_edit_guild($Info);
                    } else {
                        if ($Info['MissingVitalInfo']==true) {
                            unset($NavSystem["Management Optionen"]);
                            unset($NavSystem["Mitglieder Optionen"]);
                            unset($NavSystem["Informationen"]);
                            $NavSystem["Zurück"]["Antrag verwerfen"]="guild.php";
                            output("`n`&Es fehlen Pflichtangaben! Versuche es nochmal.");
                            display_edit_guild($Info);
                        } else {
                            unset($Info['BadLanguage']);
                            unset($Info['MissingVitalInfo']);
                            $Info['IsGuild']=1;
                            // Save the Guild info
                            if (!isset($Info['ID']) or $Info['ID']==0) {
                                $id=create_guild_info($Info);  // Returns the identity of the user
                                $session['user']['guildID']=$id;  // Assign the user to the guild
                                PopulateRanks($guildID);
                                output("`n`0Der freundliche Mitarbeiter im Gründungsbüro
                                nimmt Deinen Antrag lächelnd entgegen und prüft ihn.`n
                                \"`3Vielen Dank, er sieht soweit gut aus und ich werde ihn
                                umgehend den Göttern vorlegen. Du wirst dann eine Nachricht
                                erhalten....\"`0`n
                                Mit diesen Worten bedeutet Dir der Mitarbeiter doch nun zu
                                gehen.`n`n
                                Du bemerkst, dass der Mitarbeiter den Antrag, Deinen Antrag,
                                etwas lieblos auf einen Haufen wirft und etwas von einem
                                pergamentlosem Büro murmelt.`0`n");
                                // Grab the cash
                                $session['user']['gems']-=$GuildPurchaseCostGems;
                                $session['user']['gold']-=$GuildPurchaseCostGold;
                                // Load them the cash into the guild
                                $Info['gems']=$GuildPurchaseCostGems;
                                $Info['gold']=$GuildPurchaseCostGold;
                                $Info['ID']=$id;
                                update_guild_info($Info);
                            } else {
                                update_guild_info($Info);
                                output("`n`0Der Mitarbeiter des Büros schaut auf die
                                Angaben, die Du eingetragen hast. `&\"Hmm... Schaut
                                alles ordentlich aus. Ich werde die Daten übernehmen.\"`0");
                            }
                            $NavSystem["Sonstiges"]["Zurück zum Gildenhaus"]="guild.php";
                        }
                    }
                }
                break;

                default:
                output("Fehler! Kein parameter! Action=create/Status=??`n");
                break;
            }
            break;  // end case "create"

            case "manageranks":
                // Add/modify/remove the ranks of this guild
                ManageRanks($guildID);
            break;

            case "approveapplicant":
                // Approve a pending applicant
                if ($who=="") {
                    // Nothing has been passed
                    output("Wen soll ich denn zulassen?`n");
                } else {
                    $Applicants=&$ManageGuild['ApplicantList'];
                    unset($Applicants[$who]);
                    update_guild_info($ManageGuild);
                    $sql="select name, guildID from accounts where acctid=".$who;
                    $result = db_query($sql);
                    $row = db_fetch_assoc($result);
                    if ($row['guildID']==0) {
                        $sql="update accounts set guildID=".$guildID." where acctid=".$who;
                        db_query($sql);
                        output("`n`nMitgliedschaft bestätigt und Information per Brieftaube abgeschickt!");
                        systemmail($who,"`%Re: Mitgliedsantrag für die Gilde: ".$ManageGuild['Name']."`0",
                               ($session['user']['name'])." `0hat Deinem Antrag auf Mitgliedschaft in der Gilde ".$ManageGuild['Name']." zugestimmt!`n`n Herzlichen Glückwunsch!!", $session['user']['acctid']);
                    } else {
                        output($row['name']." `0wurde bereits von ".$session['guilds'][$row['guildID']]['Name']." aufgenommen",true);
                        output("`0Der Aufnahmeantrag wurde gelöscht.");
                    }
                }
            break;

            case "denyapplicant":
                // Deny an pending applicant
                if ($who!="") {
                    $Applicants=&$ManageGuild['ApplicantList'];
                    unset($Applicants[$who]);
                    update_guild_info($ManageGuild);
                    output("Aufnahme abgelehnt");
                    systemmail($who,"`%Re: Aufnahmeantrag ".$ManageGuild['Name']." Gilde!`0",
                                   ($session['user']['name'])." `0hat Deinen Aufnahmeantrag
                                   für die Gilde ".$ManageGuild['Name']." `iabgelehnt`i.`n`n
                                   Pech....
                                   `nAntworte auf diese Mail, wenn Du genaueres zur Ablehnung
                                   erfahren möchtest.", $session['user']['acctid']);
                } else {
                    output("Du kannst nur jemanden ablehnen, den Du vorher ausgewählt hast!");
                }
            break;

            case "revokemember":
                // Kick somebody out
                if ($who!="") {
                    $sql="update accounts set guildID=0 where acctid=".$who;
                    db_query($sql);
                    systemmail($who,"`%Re: Mitgliedschaft ".$ManageGuild['Name']." Gilde!`0",
                                   ($session['user']['name'])." `0hat Deine Mitgliedschaft
                                   in der Gilde ".$ManageGuild['Name']." `&`iwiderrufen`i!
                                   `n`nAntworte auf diese Mail für weitere Informationen",
                                   $session['user']['acctid']);
                    output("Mitgliedschaft widerrufen");
                } else {
                    output("Du musst jemanden auswählen bevor Du ihn kicken kannst!");
                }
            break;

            case "assignrank":
                // Assign one or more members to a rank
                AssignRank($guildID);
            break;

            case "pay":
                // Pay a stipend to one or more members
                PayMember($guildID, $who);
            break;

            case "promotemember":
                // Promote somebody to the management team
                PromoteMember($guildID);
            break;

            case "members":
                // Display/Manage members
                GuildMembers($guildID,true);
            break;

            case "guildinfo":
                // Display the guild Information for editing
                display_edit_guild($ManageGuild,true,false);
            break;

            case "hitlist":
                //### Verify
                // Display/Manage the current Hitlist
                edit_hitlist($guildID);
            break;

            case "fireside":
                output("`n");
                // this is pure HTML - logd text codes didn't work here....
                $titeltext="Das Kaminzimmer";
                $fliesstext="Du trittst in das Kaminzimmer ein. Viele Türen führen
                in diesen wunderschönen Raum, der liebevoll gestaltet seinen Besuchern
                eine entspannte Gastlichkeit bietet. Ein wärmendes Feuer prasselt in
                der alten Feuerstelle, die diesen Raum beherscht. Nur die Offiziellen
                der Gilden haben hier Zutritt. Einige sitzen vor dem grossen Kamin,
                rauchen eine Pfeiffe oder trinken ein kühles Ale.
                <br>Wolltest Du nicht etwas wichtiges besprechen?";
                rawoutput("<IMG SRC=\"images/fireplace.gif\" align=\"left\">");
                rawoutput("<p>".$titeltext."<br><br>".$fliesstext."</p>");
                //output("`n");
                viewcommentary("GuildFire",
                "Die edlen Führer der Gilden sprechen miteinander", 25,"spricht");
            break;

            default:
                // Should not have got here!
                output("Should not have got here - Option: ".$action);
            break;
        }
    break;  // End of op=manage


// ----------------------------------------------------------------------------
// --         MEMBER
// ----------------------------------------------------------------------------

    case "member":
        $action=$HTTP_GET_VARS['action'];
        $guildID=$HTTP_GET_VARS['id'];
    get_clanguild_var($guildID);
    $session[user][locate]=15000+$guildID;
        $ThisGuild=$session['guilds'][$guildID];

        switch ($action) {
            case "suggestions":
                $helpid=200;  //member menu help
                output("`n`0Willkommen im Vorschlagszimmmer der Gilde!",true);
                output("`n`n`0Auf einem grossen Schild steht:");
                output("`c`i`&Die `bGildenführung`b hat das Recht, `nalle Vorschläge
                zu ignorieren oder zu überlesen, `ndie hier hinterlassen werden.`i`c",true);
                output("`n`0An der Wand siehst Du hunderte kleiner Pergamente, alle vollgeschrieben mit Vorschlägen und Bitten an die Gildenführung.`n`n",true);
                // Display the welcome intro
                viewcommentary("GuildSuggest:".$guildID,"vorschlagen", 25,"schlägt vor");
            break;

            case "donate":
                $helpid=200;  //member menu help
                transfer_cash_guild($guildID, $stage);
            break;

            case "cellar":
                $ExpandedLinks=true;
                $retnav = 0;
                $nonav = 1;
                $NavSystem["~Kellergewölbe~"]["Schatzkammer"]="guild.php?op=member&action=cellar&type=goldsafe&id=".$guildID;
                $NavSystem["~Kellergewölbe~"]["Steinespiel"]="stonesgame.php";

                switch ($HTTP_GET_VARS['type']){
                    case "goldsafe":
                        goldsafe($guildID,$HTTP_GET_VARS['stage']);
                        $retnav = 1;
                    break;
                    default:
                        output("`0`nDu stehst in dem etwas dunklen aber geräumigen
                        Kellergewölbe unterhalb des Gildenhauses.`n
                        Neugierig blickst Du Dich um. Von irgendwoher hallen Schritte
                        und Du kannst einige Stimmen hören - aber niemanden sehen.`0`n");
                    break;
                }

            break;

            case "shop":
                $ExpandedLinks=true;
                $retnav = 0;
                $NavSystem["~Markt~"]["Waffe verbessern"]="guild.php?op=member&action=shop&type=weapon&id=".$guildID;
                $NavSystem["~Markt~"]["Rüstung verbessern"]="guild.php?op=member&action=shop&type=armour&id=".$guildID;
                $NavSystem["~Markt~"]["Dr. Ohura"]="guild.php?op=member&action=shop&type=doctors&id=".$guildID;
                $NavSystem["~Markt~"]["Trainingsraum"]="guild.php?op=member&action=shop&type=practice&id=".$guildID;
                $NavSystem["~Markt~"]["Waffengeschäft"]="weapons.php?guild=".$guildID."&return=".urlencode(CalcReturnPath(true))."";
                $NavSystem["~Markt~"]["Rüstungsgalerie"]="armor.php?guild=".$guildID."&return=".urlencode(CalcReturnPath(true))."";
                $NavSystem["~Markt~"]["Marktschreier"]="guild.php?op=member&action=shop&type=shout&id=".$guildID;

                switch ($HTTP_GET_VARS['type']){
                    case "weapon":
                        display_weapon_upgrade($guildID);
                    break;
                    case "armour":
                        display_armour_upgrade($guildID);
                    break;
                    case "doctors":
                        display_healer($guildID);
                    break;
                    case "practice":
                        display_practice_field($guildID);
                    break;
                    case "shout":
                        mail2whole_guild($guildID,$HTTP_GET_VARS['stage']);
                    break;
                    default:
                        output("`0`nDu betrittst den kleinen Marktplatz der Gilde.
                        Kleine Geschäfte bieten ihre Waren und Dienstleistungen den
                        Mitgliedern der Gilde ".$ThisGuild['Name']."`0 an.`0");
                    break;
                }

            break;

            case "leaveguild":
                $helpid=200;  //member menu help
                switch ($HTTP_GET_VARS['choice']){
                    case "yes":
                        if (IsOnManagementTeam($guildID)) {
                            $ThisGuild=&$session['guilds'][$guildID];
                            // Send mail to other management - ?also to the members
                            if ($ThisGuild['GuildLeader']==$session['user']['acctid']) $ThisGuild['GuildLeader']=0;
                            if ($ThisGuild['HeadOfWar']==$session['user']['acctid']) $ThisGuild['HeadOfWar']=0;
                            if ($ThisGuild['HeadOfMembership']==$session['user']['acctid']) $ThisGuild['HeadOfMembership']=0;
                            update_guild_info($ThisGuild);
                        }
                        $session['user']['guildID']=0;
                        $session['user']['guildrank']=0;
                        output("`n`0Du entscheidest Dich, in Zukunft mehr Zeit mit
                        Deinem eigenen Kram zu verbringen.`nDu gibst Deine Mitgliedskarte
                        ab und verlässt die Gilde mit einer kleinen Träne im Auge.
                        Gerne erinnerst Du Dich an die schönen Momente, die Du hier
                        erlebt hast. Du hältst einen Moment inne. Gleich bist Du wieder
                        im Dorf.");
                        // ### Send a mail to the GuildLeader or the head of membership
                        clearnav();
                        header("Refresh: 10; URL=village.php");
                        addnav("","village.php");
                    break;
                    case "no":
                        output("`n`0Du überlegst noch einmal und entscheidest Dich dann
                        doch zu bleiben. Das Positive überwiegt doch und Du willst es
                        nicht missen.`0");
                    break;
                    default:
                        if (IsOnManagementTeam($guildID)) {
                            output("`nDu gehörst zum Führungsteam!!!");
                            output("`nWie sollen die ohne Deine Erfahrung bloß weitermachen??");
                        }
                        output("`n`n`0Bist Du sicher, dass Du die Gilde verlassen willst?");
                        $NavSystem["~Options~"]["J?Ja - lass mich hier raus"]="guild.php?op=member&action=leaveguild&choice=yes&id=".$guildID;
                        $NavSystem["~Options~"]["N?Nein - Ich will bleiben"]="guild.php?op=member&action=leaveguild&choice=no&id=".$guildID;
                        $ExpandedLinks=true;
                        $nonav = 1; //suppress standard navs
                    break;
                }
            break;

            case "viewrules":
                $helpid=300;  //information menu help
                output("`n`n`&`cRegeln der Gilde`c",true);
                output("`n`c~~~`c",true);
                output("`n`c".$ThisGuild['RulesText']."`c",true);
            break;

            case "viewdesc":
                $helpid=300;  //information menu help
                output("`n`n`&`cBeschreibung der Gilde`c",true);
                output("`n`c~~~`c",true);
                output("`n`c".$ThisGuild['PublicText']."`c",true);
            break;

            case "showstatus":
                  $helpid=300;  //information menu help
                  $sort= $HTTP_GET_VARS['sort'];
                  output("`n`c`iRangliste Gilden`i`c`n");
                  GuildsHOF($guildID,$sort);
            break;

            case "showmembers":
                  $helpid=300;  //information menu help
                  GuildMembers($guildID);
            break;

            case "discounts":
                  $helpid=300;  //information menu help
                  output("`n`0Die folgenden Rabatte und Vorteile werden Dir durch
                  Deine Gilde gewährt:`n`n");
                  output("`c",true);
                  displaySitePoints($guildID);
                  output("`c",true);
            break;

            case "enter":
                $helpid=200;  //member menu help
                $guildID=$HTTP_GET_VARS['id'];
                $ThisGuild=&$session['guilds'][$guildID];
                $ExpandedLinks=false;

            // Wanna fall into default!
            default:
                // We have entered the mightly guild
                output("`n`0Willkommen in der grossen Versammlungshalle der
                Gilde `3".$ThisGuild['Name']."`0. Die Mitglieder stehen in kleinen
                Gruppen zusammen und unterhalten sich...`n
                Einige gehen geschäftig umher und verschwinden in einem der
                zahlreichen Räume.`n`n`0",true);
                //output("`n`0Welcome to the ".$ThisGuild['Name']."`0 commons.`n`n",true);
                $news=fetchNews($ThisGuild['ID']);
                if (isset($news)) output("Die letzte Nachricht aus der Gilde:`n`n`0`c`i".$news['newstext']."`i`c`n`0",true);
                //if (isset($news)) output("The latest Guild News:`n`n`0`c`i".$news['newstext']."`i`c`n`0",true);

                // Display the welcome intro
                viewcommentary("GuildChat:".$guildID,"Rede mit anderen Mitgliedern", 25);
            break;
        }

        // Display the links
        if ($action == "leaveguild") {
            unset($NavSystem["Informationen"]);
            unset($NavSystem["Mitglieder Optionen"]);
            unset($NavSystem["Management Optionen"]);
        }
        if (!$ExpandedLinks) {
           $onTeam=IsOnManagementTeam($guildID);
           if ($onTeam) {
               nav_manageguildoptions($guildID);
           }
           //nav_manageoptions($guildID);
           nav_guildoptions($guildID);
           nav_guildinfo($guildID);
           //  External Page link
           $NavSystem[]="~";
        }
        if (!$retnav) {
           $NavSystem["Zurück"][$ThisGuild['Name']]="guild.php?op=member&action=enter&id=".$guildID;
//           $NavSystem[]="~";
        }

    break;


// ----------------------------------------------------------------------------
// --         NON MEMBER
// ----------------------------------------------------------------------------

    case "nonmember":
        $action=$HTTP_GET_VARS['action'];
        switch ($action) {
            case "join":
                switch ($HTTP_GET_VARS['task']) {
                    default:
                        // You wish to join the Guild
                        $guildID=$HTTP_GET_VARS['id'];
                        $ApplyGuild=&$session['guilds'][$guildID];
                        if ($session['user']['guildID']!=0) {
                            // You are a member of one already
                            $ThisGuild=&$session['guilds'][$session['user']['guildID']];
                            if ($session['user']['guildID']==$guildID){
                                StrollIn();
                                output("Duh! Du bist doch schon Mitglied!");
                            } else {
                                if ($ThisGuild['Status']<>1) {
                                    // You cannot spy unless the guild is approved
                                } else {
                                    // Include the option to spy
                                    addnav("Spy on ".$ApplyGuild['Name'],"guild.php?op=nonmember&action=join&task=spy&id=".$guildID);
                                    output("`0You are already a member of the ".$ThisGuild['Name']."`0 Guild",true);
                                    output("`n`0But you can try and spy - you might get lucky!");
                                }
                            }
                        } else {
                            // You are not a member of any other Guild
                            output("`n`nIf you join the Guild you will be required to adhere to the rules");
                            output("`n`n`&`cRules`c",true);
                            output("`n`c~~~`c",true);
                            output("`n`c".$ApplyGuild['RulesText']."`c",true);
                            output("`0<form action='guild.php?op=nonmember&action=join&task=submit&id=".$guildID."' method='POST'>",true);
                            output("`n`n`c<input type='submit' name='submit' class='button' value='Apply'>`c",true);
                            output("`n`c<input type='submit' name='submit' class='button' value='Forget it'>`c",true);
                            addnav("","guild.php?op=nonmember&action=join&task=submit&id=".$guildID);
                        }
                    break;

                    case "submit":
                        $buttonClicked=$_POST['submit'];
                        $guildID=$HTTP_GET_VARS['id'];

                        if ($buttonClicked=="Apply") {
                            $ApplyGuild=&$session['guilds'][$guildID];
                            // This won't handle applications to multiple Guilds!
                            $ApplicantList=array(); $ApplicantList=&$ApplyGuild['ApplicantList'];
                            if (array_key_exists($session['user']['acctid'],$ApplicantList)==true) {
                                output("You have already applied to join this guild");
                            } else {
                                $ApplicantList[$session['user']['acctid']]="";
                                update_guild_info($ApplyGuild);
                                if ($ApplyGuild['HeadOfMembership']!=0) {
                                    $MailTo=$ApplyGuild['HeadOfMembership'];
                                } else {
                                    $MailTo=$ApplyGuild['GuildLeader'];
                                }

                                systemmail($MailTo,"`%You have an application to join the Guild!`0",
                                    ($session['user']['name'])."`& has applied to join the ".$ApplyGuild['Name']." `&Guild", $session[user]['acctid']);
                                $sql="select name from accounts where acctid=".$MailTo; // Determine the name of the person - makes the mail pretty
                                $result=db_query($sql);
                                $row=db_fetch_assoc($result);

                                systemmail($session['user']['acctid'],"`%You have applied to join the ".$ApplyGuild['Name']." `%Guild!`0",
                                    "Your application has been submitted to ".$row['name'],$MailTo);
                                output("`nYour Application for membership has been submitted");
                                output("`nCheck your mail for notification of your membership");
                            } // Array_Key_Exists
                        } else {
                            $ApplyGuild=&$session['guilds'][$guildID];
                            output("`n`0You decide to back away from joining the ".$ApplyGuild['Name']." `0Guild",true);
                            strollin();
                        } // Button clicked = Apply

                    break;

                    case "spy":
                        $Success = (e_rand(50,100)==76);
                        $guildID=$HTTP_GET_VARS['id'];
                        $SpyGuild=&$session['guilds'][$guildID];
                        output("`n`0You try to spy!");
                        if ($Success) {
                            output("`n`n`0You sneak past the Guild Guards into the office of the management team");
                            output("`n`0With stealth and cunning you manage to open the safe without setting off the alarms!!");
                            $gold = e_rand(($SpyGuild['gold']/10),$SpyGuild['gold']);
                            $gems = e_rand(($SpyGuild['gems']/10),$SpyGuild['gems']);
                            output("`n`nYou manage to steal `^".$gold."`0 Gold and `%".$gems."`0 Gems");
                            $SpyGuild['gold']-=$gold;
                            $SpyGuild['gems']-=$gems;
                            $session['user']['gold']+=$gold;
                            $session['user']['gems']+=$gems;
                            $hitpoints=e_rand(2,20);
                            $experience=e_rand(100,3000);
                            output("`n`0For your bravery you gain an extra `&".$hitpoints." `0Max HitPoints and `&".$experience." `0experience");
                            $session['maxhitpoints']+=$hitpoints;
                            $session['user']['experience']+=$experience;
                            update_guild_info($SpyGuild);
                            $Discovered=(e_rand(1,20)==17);
                            output("`n`nOutside you hear a noise of somebody approaching");
                            output("`n`0You manage to leave through the window before you are seen - but did you put everything back as you should of??");
                            if ($Discovered) {
                                // Inform the leader and head of war about being spied upon
                                systemmail($SpyGuild['GuildLeader'],"`0A Spy has snuck into your guild!!`0",
                                   ($session['user']['name'])."`0 sneaked into the guild offices and managed to steal `^".$gold." `0Gold and `%".$gems."`0 Gems", 0);
                                systemmail($SpyGuild['HeadOfWar'],"`0A Spy has snuck into your guild!!`0",
                                   ($session['user']['name'])."`0 sneaked into the guild offices and managed to steal `^".$gold." `0Gold and `%".$gems."`0 Gems", 0);
                            }

                        } else {
                            output("`n`n`&Yikes..   Spying isn't looked upon lightly by the ".$SpyGuild['Name']." `&Guild",true);
                            output("`n`n`0You are suddently surrounded by warriors, who guard the entrance, carrying short sharp pikes.  The angry ends appear to be pointing at you!!");

                            // Determine the name of the person - makes the mail pretty
                            if ($SpyGuild['HeadOfWar']!=0) {
                                $PersonID=$SpyGuild['HeadOfWar'];
                                $PrettyTitle = "Head of War";
                            } else {
                                $PersonID=$SpyGuild['GuildLeader'];
                                $PrettyTitle = "Guild Leader";
                            }

                            $sql="select name from accounts where acctid=".$PersonID;
                            $result=db_query($sql);
                            $row=db_fetch_assoc($result);

                            output("`n`n`0From the shadows the ".$PrettyTitle." - ".$row['name']." `0walks up with an angry scowl and barks a command to the guards.",true);
                            output("`n`0They grin and suddenly jump you!!");

                            $HPLoss = e_rand(1,$session['user']['hitpoints']);
                            $goldLoss= e_rand(1,$session['user']['gold']);
                            $session['user']['hitpoints']-=$HPLoss;
                            $session['user']['gold']-=$goldLoss;

                            $SpyGuild['gold']+=$goldLoss; //###
                            $SpyGuild['SitePoints']+=(int)($HPLoss/1000);
                            update_guild_info($SpyGuild);

                            output("`n`n`&You lose `4".$HPLoss." `&Hitpoints and `^".$goldLoss." `&gold!!");
                            output("`n`&Bad Luck!!");
                            if ($SpyGuild['GuildLeader']<>0) {
                                systemmail($SpyGuild['GuildLeader'],"`0A Spy has snuck into your guild!!`0",
                                   ($session['user']['name'])."`0 sneaked into the guild offices but your patrols managed detect him and he was caught!", 0);
                            }
                            if ($SpyGuild['HeadOfWar']<>0) {
                                systemmail($SpyGuild['HeadOfWar'],"`0A Spy has snuck into your guild!!`0",
                                   ($session['user']['name'])."`0 sneaked into the guild offices but your patrols managed detect him and he was caught!", 0);
                            }
                            addnews($session['user']['name']." was caught trying to spy on the ".$SpyGuild['Name']."`n They were dealth with severely by the Guards and ".$PrettyTitle."!!");
                        }
                    break;
                }
            break;

            case "examine":
            case "viewintro":
                strollin();
                // View the intro info for a guild
                $guildID=$HTTP_GET_VARS['id'];
                $ThisGuild=&$session['guilds'][$guildID];
                $return=$HTTP_GET_VARS['return'];
                if (isset($return)) {
                    $NavSystem=array();
                    $NavSystem['Return to whence you came']=$return;
                }
                // We have a guild name
                output("`n`n`n`0You wander up to the guild headquarters for the ".$ThisGuild['Name']. "`0 Guild and examine the notice board, looking for information on joining.",true);
                output("`n`n`&`c`iWelcome board`i`c",true);
                output("`n`n`&`c`i`bInformation about our Guild`b`i`c",true);
                output("`%`c~~`c",true);
                output("`&`c".$ThisGuild['PublicText']."`c",true);

                output("`n`n`&`c`i`bInformation for Applicants`b`i`c",true);
                output("`%`c~~`c",true);
                output("`&`c".$ThisGuild['ApplyText']."`c",true);

                output("`n`n`&`c`i`bRegeln der Gilde`b`i`c",true);
                output("`%`c~~`c",true);
                output("`&`c".$ThisGuild['RulesText']."`c",true);
                if ( $session['user']['guildID'] == 0 && $session['user']['clanID'] == 0 ) {
                    //$NavSystem[]="~";
                    $NavSystem["Bewerber"]
                    ["`b`qMitgliedsantrag für ".$ThisGuild['Name']." abgeben`b"]="guild.php?op=nonmember&action=join&id=".$guildID;
                }
            break;

            case "list":
                strollin();
                listall_guildclan();
            break;

            case "create":
                $type=$HTTP_GET_VARS['type'];
                switch ($type){
                    case "guild":
                    $status=$HTTP_GET_VARS['status'];
                    switch ($status) {
                        case "verifyInfo":
                        $NavSystem["Zurück"]["Antrag verwerfen"]="guild.php";
                        // Display data entered by the user
                        $Info=$_POST['info'];
                        $Info['GuildLeader']=$session['user']['acctid'];
                        display_guild_info($Info);
                        break;

                        default:
                            if (($session['user']['gems']>=$GuildPurchaseCostGems) && ($session['user']['gold']>=$GuildPurchaseCostGold)) {
                                output("`n`nIm Gründungsbüro erhälst Du ein Antragspergament:`n`n");
                                display_edit_guild($Info);
                            } else {
                                output("`n`nIm Gründungsbüro bekommst Du mitgeteilt:`n`n");
                                output("`0Du hast nicht genug Reichtümer, um eine Gilde zu gründen.`n
                                Du benötigst `%".$GuildPurchaseCostGems." `0Edelsteine und
                                `^".$GuildPurchaseCostGold." `0Goldstücke.`n`n
                                `0Komm' später wieder!`0`n");
                            }
                        break;
                    }
                    break;

                    case "clan":
                    output("`n`nClans noch nicht implementiert!");
                    break;
                }
                default:
                break;
            }

        break;



    break;


// ----------------------------------------------------------------------------
// --         DEFAULT
// ----------------------------------------------------------------------------

    default:
        // Are they a member of a guild/Clan
        // gargamel: nop, they aren't....
        output("`n`n`0Du gehst in die edle Klingengasse und passierst die Bauten,
        bis Du vor dem majestätischen Hauptgebäude der Gilden von Düsterstein etwas
        ehrfürchtig stehen bleibst.`n`n
        Weiter die Strasse herunter lässt Du Deinen Blick über ein offenes Feld
        schweifen, auf dem sich die Clans von Düsterstein in einem bunten Lager
        gewaltiger Zelte zusammengefunden haben.");
        // Work out what nav's to display
        StrollIn();

    break;
}

$linkback = URLEncode(calcreturnpath());

//$NavSystem[]="~";
if ( $nonav == 0 ) {
    $NavSystem[]="~";
    $NavSystem["Auskunftsbüro"]["A?Alle Gilden/Clans"]="guild.php?op=nonmember&action=list";
    $NavSystem["Auskunftsbüro"]["H?Hilfe"]="guildclanhelp.php?id=".$helpid."&ret=".$linkback;
    //$NavSystem[]="~";
    if ($HTTP_GET_VARS['op'] != "" ) {
        $NavSystem["Sonstiges"]["Z?Zurück zum Hauptgebäude"]="guild.php";
    }
    $NavSystem["Sonstiges"]["Z?Zurück zum Dorf"]="village.php";
}

PopulateNavs();

page_footer();
?>


