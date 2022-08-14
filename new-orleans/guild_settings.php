
<?php

/* 

Projekt: Saint Omar Gilden
Version: 0.1
Author: Aramus

Dateibeschreibung: Settings für die Gilde z.B. Maxgold

*/


/* 

Klasse: class guildSettings ** Gildeneinstellungen
Funktion: const X = 'Wert aus DEFINE';
Aufruf im Ouput: $variable = guildSettings::getGUILD_X();
                 output(' '.$variable.' ');



*/

function loadguildnew($id){
   global $session;

   if($id > 0){
        $sql = "SELECT * FROM guild_n_main WHERE g_id= '".$id."'";
        $result = db_query($sql) or die(db_error(LINK));
        $session['guildNew'] = db_fetch_assoc($result);
        db_free_result($result);
        
        $session['guildNew']['gname'] = stripslashes($session['guildNew']['g_name']);
        $session['guildNew']['gprefix'] = stripslashes($session['guildNew']['g_prefix']);
        
        

    }
}

    class guildSettings {
        // const auf Wert X setzen
        const GUILD_MAXGOLD             = '999999';        
        const GUILD_MAXGEMS             = '999999';
        const GUILD_MAXGEFALLEN         = '999999';
        const GUILD_MAXLEVEL             = '10';
        const GUILD_MAXERFOLGSPUNKTE     = '10000';
        const GUILD_MAXRPROOMS            = '10';
        const GUILD_MAXAUSBAUSTUFE         = '10';
        const GUILD_MAXQUESTS_PERDAY     = '1';
        const GUILD_MAXRUF_PERPLAYER     = '100000';
        const GUILD_MAXRUF_PERDAY         = '100';
             
                // Funktionen setzen
                
                    // maximales Gold der Gilde
              public static function getGUILD_MAXGOLD() {
                return self::GUILD_MAXGOLD;
                
            }
            
                    // maximale Edelsteine der Gilde
            public static function getGUILD_MAXGEMS() {
                return self::GUILD_MAXGEMS;
            }
            
                    // maximale Gefallen der Gilde
            public static function getGUILD_MAXGEFALLEN() {
                return self::GUILD_MAXGEFALLEN;
            }
            
                    // maximales Level der Gilde
            public static function getGUILD_MAXLEVEL() {
                return self::GUILD_MAXLEVEL;
            }
            
                    // maximale Anzahl an Erfolgspunkten die eine Gilde sammeln kann
            public static function getGUILD_MAXERFOLGSPUNKTE() {
                return self::GUILD_MAXERFOLGSPUNKTE;
            }
            
                    // maximale RP-Räume die eine Gilde erstellen kann
            public static function getGUILD_MAXRPROOMS() {
                return self::GUILD_MAXRPROOMS;
            }
            
                    // maxime Ausbaufstufe der Gilde
            public static function getGUILD_MAXAUSBAUSTUFE() {
                return self::GUILD_MAXAUSBAUSTUFE;
            }
            
                    // maximale Quests die ein Spieler für die Gilde machen kann *IngameDay*
            public static function getGUILD_MAXQUESTS_PERDAY() {
                return self::GUILD_MAXQUESTS_PERDAY;
            }
            
                    // maximaler Ruf den ein Spieler bei der Gilde haben kann
            public static function getGUILD_MAXRUF_PERPLAYER() {
                return self::GUILD_MAXRUF_PERPLAYER;
            }
            
                    // maximaler Ruf den ein Spieler pro Tag bei der Gilde erlangen kann
            public static function getGUILD_MAXRUF_PERDAY() {
                return self::GUILD_MAXRUF_PERDAY;
            }    
            
    }
    
                // Variable setzen um Output erzeugen zu können
                $getGUILD_MAXGOLD                = guildSettings::getGUILD_MAXGOLD();
                $getGUILD_MAXGEMS                 = guildSettings::getGUILD_MAXGEMS();
                $getGUILD_MAXGEFALLEN             = guildSettings::getGUILD_MAXGEFALLEN();
                $getGUILD_MAXLEVEL                 = guildSettings::getGUILD_MAXLEVEL();
                $getGUILD_MAXERFOLGSPUNKTE         = guildSettings::getGUILD_MAXERFOLGSPUNKTE();
                $getGUILD_MAXRPROOMS             = guildSettings::getGUILD_MAXRPROOMS();
                $getGUILD_MAXAUSBAUSTUFE        = guildSettings::getGUILD_MAXAUSBAUSTUFE();
                $getGUILD_MAXQUESTS_PERDAY         = guildSettings::getGUILD_MAXQUESTS_PERDAY();
                $getGUILD_MAXRUF_PERPLAYER         = guildSettings::getGUILD_MAXRUF_PERPLAYER();
                $getGUILD_MAXRUF_PERDAY         = guildSettings::getGUILD_MAXRUF_PERDAY();



?>

