<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * Commentary: A Class which provides the rp-chat in logd
 * 
 * @author        Basilius Sauter
 * @copyright    2006-2009
 * @version        2.105
 * @package        commentary
 *
 *     Features which are new in 3.0:
 *         - Supports Edit/Delete last Post (And only the last)
 *         - New Settings: 
 *             - Position of Navigation (top, bottom, both) 
 *             - Seperate position for rp-export-link (top, bottom or both)
 *             - Type of Navigation (anchor, button or fakebutton), button and fakebutton uses CSS-Class "button" for styling
 *         - Placeholder for typographic quotation marks
 *             - Configurable Quotation-Type: English, German, French, Swiss-Guillemets and German-Guillemets
 *             - Different Types of Placeholders: "" and '' or [] and {}
 *             - Hook to insert automatic player-own color
 *         - Hook to calculate rp-points
 */

# Einstellungen
Include 'commentary.setting.php';

class Commentary {
    /**
     * @var string
     * @see Commentary::__construct()
     */
    protected $section;
    
    /**
     * @var int
     * @see Commentary::__construct()
     */
    protected $limit;
    
    /**
     * @var string
     * @see Commentary::__construct()
     */
    protected $talkline;
    
    /**
     * @var string
     * @see Commentary::__construct()
     */
    protected $message;
    
    /**
     * @var int ID für das Emote. 0 für keines, 1 für dritte Person, 2 für Landschaftsemote, 3 für dritte Person ohne Leerzeichen
     * @see Commentary::Add(), Commentary::Edit()
     */
    protected $emote;
    
    /**
     * @var string Navigationsstumpf ohne Cheatschutz-Attribut und comscroll
     */
    protected $baselink;

    /**
     * Der Konstruktor der Klasse
     * 
     * @param link $user Ein symbolischer Link auf die Speicheradresse des Users (&)
     * @param string $section Die Chatsektion, dient als ID für den Chatort
     * @param string $message Die Nachricht, die über dem Chat erscheint (optional)
     * @param int $limit Soundsoviele Kommentare
     * @param string $talkline Die Sprechzeile, wenn kein Emote erfolgt (sagt, spricht, flüstert...)
     */
    public function __construct(&$user, $section, $message = COMMENTARY_DEFAULTMESSAGE,$limit = COMMENTARY_DEFAULTLIMIT,  $talkline = COMMENTARY_DEFAULTTALKLINE) {
        # Eine Referenz von $user intern speichern
        $this->user = &$user;
        
        # Wenn $section ein Array ist, dann muss es eine Admin-Seite sein
        if(is_array($section)) {
            $this->section = $section; # Admin *g*
        }
        # $section kein Array => säubern
        else {
            $this->section = trim(db_real_escape_string(stripslashes($section)));
        }
        
        # Übergebene Parameter intern verwahren
        $this->message = $message;
        $this->limit = (int)$limit;
        $this->talkline = trim(db_real_escape_string(stripslashes($talkline)));
        
        # Standard-Farben festlegen
        $this->color3person = '`&'; // => /X $farbe Die Sonne scheint
        $this->coloremote = '`3'; // => /me $farbe schreitet über den Boden. Auch für die Talkline
        $this->colorspeak = '`#'; // Direkte Rede (Kein Emote, oder mit Spezialzeichen): «$farbeIch singe!»
        $this->playernamecolor = '`&'; // $farbe(Bauernmädchen Samira)
        
        # Navigations-Stumpf erstellen
        if(!empty($_GET)) {
            $req1 = '';
            foreach($_GET as $arg => $val) {
                if($arg != 'c' AND $arg != 'comscroll') {
                    $req1.= $arg.'='.$val.'&';
                }
            }
            
            $req1 = basename($_SERVER['SCRIPT_NAME']).'?'.$req1;
        }
        else {
            $req1 = basename($_SERVER['SCRIPT_NAME']).'?';
        }
        $this->baselink = $req1;
    }
  
    /**
     * Ändert die Standardfarben
     * 
     * @param mixed $speak false für keine Änderung, ansonsten der Farbcode für die Sprechfarbe
     * @param mixed $shirtpersion false für keine Änderung, ansonsten der Farbcode für die dritte Person
     * @param mixed $emote false für keine Änderungen, ansonsten der Farbcode für /em
     */
    public function ChangeDefaultColors($speak = false, $thirdperson = false, $emote = false) {
        # Wenn Sprechfarbe nicht false, dann zuweisen
        if($speak !== false) {
            $this->colorspeak = $speak;
        }
        
        # Wenn Emote-Farbe nicht false, dann Emote zuweisen
        if($thirdperson !== false) {
            $this->coloremote = $thirdperson;            
        }
        
        # Wenn Landschaft-Emote nicht false, dann zuweisen
        if($emote !== false) {
            $this->color3person = $emote;
        }        
    }
    
    /**
     * Fügt den Kommentar hinzu
     * @todo: Newday-Post-Hack entfernen
     */
    public function Add() {
        # Hack für Newday-Posts. Funktioniert nur nicht. Verdammt. Für was brauchte ich das schon wieder?
        if(isset($_SESSION['comment_for_insert'])) {
            $_POST = $_SESSION['comment_for_insert'];
        }

        # Sektion darf keine HTML-Spezialzeichen erhalten
        if($_POST['section'] == HTMLSpecialchars($this->section)) {
            $commentary = trim($_POST['commentary']); // Leerzeichen am Anfang und am Ende der Kommentare entfernen
            $commentary = stripslashes($commentary); // \ rausfiltern. Mistdinger.
            
            # RP-CMD verwenden, sofern aktiviert. Muss vor dem Emote-Check passieren.
            if(COMMENTARY_USE_RPCMD) {
                $commentary = $this->processRPCMD($commentary);
            }
            
            # Kommentar nicht leer?
            if($commentary != '') {
                # Emote-Check
                $commentary = $this->processEmote($commentary);
                
                # Kommentar säubern    
                $commentary = $this->Clear($commentary);
                
                # Anführungszeichen interpretieren, sofern nicht direkte Rede
                if($this->emote > 0) {
                    $commentary = $this->interpretQuotationMarks($commentary);
                }
                
                # RP-Punkte berechnen
                if(COMMENTARY_USE_RPPOINTS) {
                    $this->calculateRPPoints($commentary);
                }
                
                # Kommentar einfügen
                $this->Insert($commentary,$this->user['acctid']);
            }
            
            unset($session['comment_for_insert']);
            $_POST = array();
            
            return true;        
        }
        # Spezialzeichen in der Sektion => Falsche Bearbeitung?
        else {
            return false;
        }
    }
    
    /**
     * Vearbeitet die Kommentare nach Emote. Gibt den verarbeiteten Kommentar zurück und speichert das emote
     * Fügt gleichzeitig auch noch die passende Farbe ein.
     * 
     * @param string $commentary Der Kommentar, bei dem das Emote gefunden werden soll
     * @return string Angepasster Kommentar
     */
    protected function processEmote($commentary) {
        $emote = 0; // Emote initialisieren
        
        if(mb_substr($commentary,0,2) === '::') {
            # :: ist ein /me-Synonym => 1
            $commentary = $this->coloremote.mb_substr($commentary,2);
            $emote = 1;
        }
        elseif(mb_substr($commentary,0,1) === ':') {
            # : ist ein /me-Synonym => 1
            $commentary = $this->coloremote.mb_substr($commentary,1);
            $emote = 1;
        }
        elseif(mb_strtolower(mb_substr($commentary,0,3)) === '/me') {
            # /me-Emote => 1
            $commentary = $this->coloremote.mb_substr($commentary,3);
            $emote = 1;
        }
        elseif(mb_strtolower(mb_substr($commentary,0,3)) === '/ms') {
            # Danke an Rikkarda für die Idee
            # /ms-Emote => 3
            $commentary = $this->coloremote.mb_substr($commentary,3);
            $emote = 3;
        }
        elseif(mb_strtolower(mb_substr($commentary,0,3)) === '/em') {
            # /em ist ein /X-Synonym => 2
            $commentary = $this->color3person.mb_substr($commentary,3);
            $emote = 2;
        }
        elseif(mb_strtolower(mb_substr($commentary,0,2)) === '/x') {
            # /X-Emote => 2
            $commentary = $this->color3person.mb_substr($commentary,2);
            $emote = 2;
        }            
        else {
            # Weder noch, emote bleibt 0
            $commentary = $commentary;
        }
        
        $this->emote = $emote;
        return $commentary;
    }
  
      /**
     * Säubert einen Kommentar und eliminiert «böse» Dinger
     * 
     * @param string $commentary Der abgesendete Kommentar
     * @return string Gesäuberter Kommentar
     */
    protected function Clear($commentary) {
        # Absätze rausfiltern
        $commentary = str_replace('`n','',$commentary);
        
        # HTML-Spezialzeichen rausfiltern (Maskiert auch ' !)
        $commentary = HTMLSpecialchars($commentary, ENT_QUOTES);
        
        # Unechte Farben rausfiltern (Warum mach ich das nicht schon lange hier?)
        $commentary = preg_replace("/[`][^".COMMENTARY_ALLOWEDTAGS."]/" ,"" ,$commentary);
        
        # Gesäuberter Kommentar zurückgeben
        return $commentary;
    }
    
    protected function calculateRPPoints($comment) {
        # Tags entfernen
        $comment = $this->StripTag($comment);
        
        # Text in Sätze brechen
        $sentences = explode(". ", $comment);
        # Variablen präparieren
        $numSentences = 0;
        $sentencesInfos = array();
        $numWords = 0;
        $numChars = mb_strlen($comment);
        # Sätze zählen
        foreach($sentences as $sentence) {
            # Sätze in Wörter brechen
            $words = explode(" ", $sentence);
            # Wörter zählen
            $wordnum = count($words);
            
            # Nur wenn ein Satz aus 3 Wörtern und mindestens 11 Zeichen besteht, ist der Satz ein Satz.
            if(mb_strlen($sentence) > 11 AND $wordnum > 3) {
                $numSentences++;
                $sentencesInfo['wordnum'][] = $wordnum;
                $sentencesInfo['strlen'][] = mb_strlen($sentence);
                $numWords += $wordnum;
            }
        }
        
        $averageWordsPerSentence = $numWords/$numSentences;
        $averageCharsPerSentence = $numChars/$numSentences;
        
        switch(COMMENTARY_USE_RPPOINTS) {
            # Für das Engelsreich
            case 'engelsreich': {
                break;
            }
            
            # Für Luzifels RP-System ("rpg.php"), Code überarbeitet von Eliwood
            case 'luzifel': {
                # $spirit definieren
                switch($this->user['spirits']) {
                    case (defined('RP_RESURRECTION') ? RP_RESURRECTION : -128):
                        $spirit = 0.1;
                        break;
                        
                    case -2:
                        $spirit = 0.25;
                        break;
                        
                    case -1:
                        $spirit = 0.5;
                        break;
                        
                    case 0:
                        $spirit = 0.1;
                        break;
                        
                    case 1:
                        $spirit = 1.25;
                        break;
                        
                    case 2:
                        $spirit = 1.5;
                        break;
                }
                
                # Spieleinstellungen holen
                $rpgplacegeld = explode(',',getsetting('rpgplacegeld','all'));
                $rpgplaceedels = explode(',',getsetting('rpgplaceedels','all'));
                $rpgplacedonpoints = explode(',',getsetting('rpgplacedonpoints','all'));
                $rpgplaceexp = explode(',',getsetting('rpgplaceexp','all'));
                $rpgplacesee = explode(',',getsetting('rpgplacesee','all'));
                $rpggeld = getsetting('rpggeld','100');
                $rpgedels = getsetting('rpgedels','2');
                $rpgdonpoints = getsetting('rpgdonpoints','2');
                $rpgprozent = getsetting('rpgprozent','500');
                $rpgexp = getsetting('rpgexp','20');
                $dkexp = getsetting('dkexp','2');
                $rpgsee = getsetting('rpgsee','5');
                
                # Auswertung und Belohnung für den Post
                if (
                    ($this->user['turns'] > 0 AND (
                        $this->user['admin'] == 1 OR $this->user['admin'] == 2)
                    )
                    OR $this->user['admin'] == 4
                ) {
                    # 1 Post = 1 Runde im Wald
                    $this->user['turns']--;
                    
                    # Ist der User an einem Ort, an dem man durch RP Geld verdienen darf?
                    if($rpgplacegeld[0] == 'all' OR in_Array($this->section, $rpgplacegeld)) {
                        $this->user['gold'] = $this->user['gold'] + ($rpggeld*$this->user['level']);
                    }
                    
                    # Ist der User an einem Ort, an dem man durch RP Edelsteine verdienen kann?
                    if($rpgplaceedels[0] == 'all' OR in_Array($this->section, $rpgplaceedels)) {
                        $this->user['gems'] = $this->user['gems'] + $rpgedels;
                    }
                    
                    # Vielleicht ist der User an einem Ort, an dem man Donationspunkte für sein Rollenspiel bekommt?
                    if($rpgplacedonpoints[0] == 'all' OR in_Array($this->section, $rpgplacedonpoints)) {
                        $this->user['donation'] = $this->user['donation'] + $rpgdonpoints;
                    }
                    
                    # Darf der User durch den Post hier Erfahrung bekommen?
                    if($rpgplaceexp[0] == 'all' OR in_Array($this->section, $rpgplaceexp)) {
                        $this->user['experience'] = $this->user['experience'] + ((($rpgexp * $this->user['level']) + ($djexp * $this->user['dragonkills'])) * $spirit);
                    }
                    
                    # Bekommt der User durch einen Post hier Reputation?
                    if($rpgplacesee[0] == 'all' OR in_Array($this->section, $rpgplacesee)) {
                        $this->user['reputation'] = $this->user['reputation'] + $rpgsee;
                    }
                }
                break;
            }
            
            # Wintertalsystem, Original von Abarax, modifiziert von Eliwood
            case 'wintertal': {
                $blockedsectionsstatic = explode(',', COMMENTARY_RPPOINTS_BLOCKEDSECTIONS_STATIC);
                $blockedsectionsvariable = explode(',', COMMENTARY_RPPOINTS_BLOCKEDSECTIONS_VARIABLE);
                
                # Sektionssname statisch: Ist die Sektion blockiert=
                if(!isset($blockedsectionsstatic[$this->section])) {
                    $blocked = false;
                    
                    # Sektionssname variabel: Ist die Sektion blockiert?
                    foreach($blockedsectionsvariable as $section) {
                        if(sscanf($this->section, $section)) {
                            $blocked = true;
                        }
                    }
                }
                else {
                    $blocked = true;
                }
                
                # Keine Blockierung: Punkteverteilung bei 100 Zeichen oder mehr
                if(!$blocked AND $numChars > 100) {
                    if($this->user['rppointloss'] != 0) {
                        $this->user['rppointloss'] = 0;
                    }
                    
                    # Vergütung RP-Punkte
                    $this->user['rppoints']++;
                    if($numChars >= 400) $this->user['rppoints']++;
                    if($numChars >= 800) $this->user['rppoints']++;
                    if($numChars >=1500) $this->user['rppoints']++;
                    
                    # DoPo-Verteilung für normale Chars
                    if($this->user['rpchar'] == 0 AND $this->user['rpclass'] == 3 AND $numChars >= 200) {
                        $count = floor($numChars/500, 0); # All 500 Zeichen geben wir einen Punkt.
                        $this->user['dpchange']+= 1 + $count;
                    }
                    
                    # Dopo-Verteilung für RP-Chars
                    if($this->user['rpchar']==1) {
                        if($this->user['rpclass'] == 2 AND $numChars >= 200) {
                            $count = floor($numChars/500, 0); # All 500 Zeichen geben wir einen Punkt.
                            $this->user['dpchange']+= 1 + $count;
                        }
                        
                        if($this->user['rpclass'] == 3) {
                            $count = floor($numChars/250, 0); # All 250 Zeichen geben wir einen Punkt. + einen für den 100.
                            $this->user['dpchange']+= 1 + $count;
                        }
                    }
                    
                    # Attribute und Talente erhöhen
                    $this->user['talentanstieg']++;
                    $this->user['attributsanstieg']++;
                    
                    if($numChars >= 450) {
                        $this->user['talentanstieg']++;
                        $this->user['attributsanstieg']++;
                    }
                    
                    if($numChars >= 900) {
                        $this->user['talentanstieg']++;
                        $this->user['attributsanstieg']++;
                    }
                    
                    if($numChars >= 1600) {
                        $this->user['talentanstieg']++;
                        $this->user['attributsanstieg']++;
                    }
                }
                break;
            }
            
            case 'silienta': {
                break;
            }
        }
    }
    
    protected function processRPCMD($comment) {
        switch(COMMENTARY_USE_RPCMD) {
            # Begin Command Mod by Hadriel v1.7, changes by Basilius "Wasili" Sauter 
            case 'meteora': {
                # Überprüfen, ob CMD-Line notwendig ist
                if(mb_substr($comment, 0, 6) == '/rpcmd') {
                    # Commands seen at silienta-logd.de | Idea by DoM
                    
                    # Darf der User die RPCMD's überhaupt verwenden?
                    if($this->user['superuser'] >= COMMENTARY_RPCMD_SULEVEL) {
                        $intro = explode(' ', $comment, 2);
                        $parts = explode(';', $intro[1]);
                        
                        switch($parts[0]) {
                            case 'userb': {
                                /*
                                 * /rpcmd userb;[userlogin];fullife
                                 *     -> user [userlogin] gets full life
                                 * 
                                 * /rpcmd userb;[userlogin];onehp
                                 *  -> user has only 1 hp left
                                 * 
                                 * /rpcmd userb;[userlogin];gold;[pieces]
                                 *  -> user gets [pieces] gold. can be -[pieces].
                                 * 
                                 * /rpcmd userb;[userlogin];gems;[pieces]
                                 *  -> user gets [pieces] gems. can be -[pieces].
                                 */
                                
                                if(!empty($parts[1])) {
                                    switch($parts[2]) {
                                        case 'fulllife':# Added by Basilius. Supports the right spelling
                                        case 'fullife': { 
                                            $frm = 'hitpoints = maxhitpoints';
                                            break;
                                        }
                                            
                                        case 'onehp': {
                                            $frm = 'hitpoints = 1';
                                            break;
                                        }
                                            
                                        case 'gold': {
                                            $frm = 'gold = gold + '.intval($parts[3]);
                                            break;
                                        }
                                            
                                        case 'gems': {
                                            $frm = 'gems = gems + '.intval($parts[3]);
                                            break;
                                        }
                                    }
                                    
                                    $sql = 'UPDATE accounts SET '.$frm.' WHERE login = :login';
                                    $args = array(':login' => $parts[1]);
                                    
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {    
                                        db_query_secure($sql, $args);
                                    }
                                    else {
                                        $res = new Query($sql, $args);
                                    }
                                }
                                
                                # Returns an empty comment to force non-post
                                $comment = '';
                                break;
                            }
                            
                            case 'addnews': {
                                /*
                                 * /rpcmd addnews;[news]
                                 *     -> adds a news '[News]'
                                 * 
                                 * /rpcmd addnews;Ich heisse %user% und trage %wep% und %arm%.
                                 *  -> adds a news 'Ich heisse [username] und trage [userweapon] und [userarmor]
                                 */
                                
                                if(!empty($parts[1])) {
                                    $parts[1] = str_replace('%user%', $this->user['name'], $parts[1]);
                                    $parts[1] = str_replace('%wep%', $this->user['weapon'], $parts[1]);
                                    $parts[1] = str_replace('%arm%', $this->user['armor'], $parts[1]);
                                    
                                    addnews($parts[1]);
                                }
                                
                                # Returns an empty comment to force non-post
                                $comment = '';
                                break;
                            }
                            
                            case 'weather': {
                                /*
                                 * /rpcmd weather;Sonnig und Warm
                                 *     -> Change Weather to 'Sonnig und Warm'
                                 * 
                                 * /rpcmd weather;Sonnig und Warm;[Loginname_of_a_user]
                                 *  -> Change Weather to 'Sonnig und Warm - Extra für [Name_of_[Loginname_of_a_user]]'
                                 */
                                
                                $comment = '';
                                
                                if(!empty($parts[1])) {
                                    # Wenn User angegeben überprüfen, obs den gibt und welchen richtigen Namen er denn hat
                                    $sql = 'SELECT name FROM accounts WHERE login = :login';
                                    $args = array(':login' => $parts[2]);
                                    
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {
                                        $res = db_query_secure($sql, $args);
                                        
                                        if(db_num_rows($res) > 0) {
                                            $row = mysqli_fetch_assoc($res);
                                            $extra = '`0 - Extra für '.$row['name'];
                                        }
                                        else {
                                            $extra = '';
                                        }
                                    }
                                    else {
                                        $res = new Query($sql, $args);
                                        
                                        if(count($res) > 0) {
                                            $extra = '`0 - Extra für '.$res[0]['name'];
                                        }
                                        else {
                                            $extra = '';
                                        }
                                    }
                                    
                                    # Ausgabe, dass etwas passiert ist
                                    output('Weather called %s', $parts[1].$extra);
                                    
                                    # Einstellung speichern
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {
                                        savesetting('weather', $parts[1].$extra);
                                    }
                                    else {
                                        Settings::Save('weather', $parts[1].$extra);
                                    }
                                    
                                    # Kommentar anpassen
                                    $comment = '/me ändert das Wetter per göttlicher Macht auf '.$parts[1].$extra;
                                }
                                
                                output(print_r($parts, true));
                                break;
                            }
                            
                            case 'rebirh': {
                                /*
                                 * /rpcmd rebirth;[Loginname_of_a_user]
                                 *     -> [Loginname_of_a_user] will be alive
                                 */
                                
                                $comment = '';
                                
                                # Der User will sich doch nicht selber beleben - oder?
                                if(!empty($parts[1]) AND $parts[1] != $this->user['login']) {
                                    $sql = 'SELECT name FROM accounts WHERE login = :login AND alive = 0 AND loggedin = 1';
                                    $args = array(':login', $parts[1]);
                                    
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {
                                        $res = db_query($sql, $args);
                                        
                                        if(db_num_rows($res) > 0) {
                                            $row = db_fetch_assoc($res);
                                            $name = $row['name'];
                                            
                                            $sql = 'UPDATE accounts SET alive = 1, hitpoints = maxhitpoints WHERE login = :login';
                                            db_query_secure($sql, $args);
                                        }
                                        else {
                                            $name = '';
                                        }
                                    }
                                    else {
                                        $res = new Query($sql, $args);
                                        
                                        if(count($res) > 0) {
                                            $name = $res[0]['name'];
                                            
                                            $sql = 'UPDATE accounts SET alive = 1, hitpoints = maxhitpoints WHERE login = :login';
                                            db_query_secure($sql, $args);
                                        }
                                        else {
                                            $name = '';
                                        }
                                    }
                                    
                                    if(!empty($name)) {
                                        $comment = '/me schenkt '.$name.'`0 neues Leben.';
                                        output('Rebirth called %s', $parts[1]);
                                    }
                                }
                                else {
                                    output('Rebirth called %s - ERROR!', $parts[1]);
                                }
                                break;
                            }
                            
                            case 'die': {
                                /*
                                 * /rpcmd die;[Loginname_of_a_user]
                                 *     -> [Loginname_of_a_user] will be dead (works only at the same place!)
                                 */
                                
                                $comment = '';
                                $currentpage = $this->user['restorepage'];
                                
                                if (mb_strstr($currentpage, "?comscroll=") !=""){
                                    $position=mb_strrpos($currentpage,"?comscroll=");
                                    $currentpage=mb_substr($currentpage,0,$position);
                                }
                                
                                if (mb_strstr($currentpage, "&comscroll=") !=""){
                                    $position=mb_strrpos($currentpage,"&comscroll=");
                                    $currentpage=mb_substr($currentpage,0,$position);
                                }
                                
                                if (mb_strstr($currentpage, "&c=") !=""){
                                    $position=mb_strrpos($currentpage,"&c=");
                                    $currentpage=mb_substr($currentpage,0,$position);
                                }
                                
                                if (mb_strstr($currentpage, "?c=") !=""){
                                    $position=mb_strrpos($currentpage,"?c=");
                                    $currentpage=mb_substr($currentpage,0,$position);
                                }
                                
                                output($currentpage);
                                
                                if(!empty($parts[1]) AND $parts[1] != $this->user['login']) {
                                    $sql = 'SELECT name FROM accounts WHERE login = :login AND alive = 1 AND loggedin = 1 AND restorepage LIKE :restorepage';
                                    $args = array(':login', $parts[1], ':restorepage' => $currentpage);
                                    
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {
                                        $res = db_query($sql, $args);
                                        
                                        if(db_num_rows($res) > 0) {
                                            $row = db_fetch_assoc($res);
                                            $name = $row['name'];
                                            
                                            $sql = 'UPDATE accounts SET alive = 0, hitpoints = 0 WHERE login = :login';
                                            $args = array(':login', $parts[1]);
                                            db_query_secure($sql, $args);
                                        }
                                        else {
                                            $name = '';
                                        }
                                    }
                                    else {
                                        $res = new Query($sql, $args);
                                        
                                        if(count($res) > 0) {
                                            $name = $res[0]['name'];
                                            
                                            $sql = 'UPDATE accounts SET alive = 0, hitpoints = 0 WHERE login = :login';
                                            $args = array(':login', $parts[1]);
                                            db_query_secure($sql, $args);
                                        }
                                        else {
                                            $name = '';
                                        }
                                    }
                                    
                                    if(!empty($name)) {
                                        $comment = '/me lässt einen göttlichen Blitz in den Körper von '.$name
                                            .'`0 einfahren. '.$name.' ist dabei gestorben!';
                                        output('Die called %s', $parts[1]);
                                    }
                                }
                                else {
                                    output('Die called %s - ERROR!', $parts[1]);
                                }
                                break;
                            }
                            
                            case 'setting': {
                                /*
                                 * /rpcmd setting;[setting_name];[setting_value]
                                 *     -> Setting [setting_name]'s value will be [setting_value]
                                 */
                                
                                if(!empty($parts[1]) AND !empty($parts[2])) {
                                    output('Changes setting %s (original value %s) to %s`n',
                                        $parts[1],
                                        ($settings[$parts[1]]?$settings[$parts[1]]:"`iUNSET`i"),
                                        $parts[2]
                                    );
                                    
                                    if(COMMENTARY_USE_COMPABILITY_MODE) {
                                        savesetting($parts[1], $parts[2]);
                                    }
                                    else {
                                        Settings::Save($parts[1], $parts[2]);
                                    }
                                }
                                
                                # Set comment empty to skip the post
                                $comment = '';
                                break;
                            }
                        }
                    }
                }
                break;
            }
        }
        
        return $comment;    
    }
    
    
    /**
     * Interpretiert Anführungs- und Schlusszeichen und ersetzt sie mit der Eingestellten Version
     * 
     * @param string $commentary Der abgesendete Kommentar
     * @return string Quotierter Kommentar
     */
    protected function interpretQuotationMarks($commentary) {
        $quotationtype = array(
            'english' => array(
                'open' => '&ldquo;',
                'close' => '&rdquo;',
                'open-alt' => '&lsquo;',
                'close-alt' => '&rsquo;',
            ),

            'german' => array(
                'open' => '&bdquo;',
                'close' => '&ldquo;',
                'open-alt' => '&sbquo;',
                'close-alt' => '&lsquo;',
            ),
            
            'french' => array(
                'open' => '&laquo;&nbsp;',
                'close' => '&nbsp;&raquo;',
                'open-alt' => '&lsaquo;&nbsp;',
                'close-alt' => '&nbsp;&rsaquo;',
            ),
            
            'swiss-guillemets' => array(
                'open' => '&laquo;',
                'close' => '&raquo;',
                'open-alt' => '&lsaquo;',
                'close-alt' => '&rsaquo;',
            ),
            
            'german-guillemets' => array(
                'open' => '&raquo;',
                'close' => '&laquo;',
                'open-alt' => '&rsaquo;',
                'close-alt' => '&lsaquo;',
            ),
        );
        
        # Hinzufügen der Farben
        $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open'] = $quotationtype[COMMENTARY_QUOTATION_TYPE]['open'].$this->colorspeak;
        $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open-alt'] = $quotationtype[COMMENTARY_QUOTATION_TYPE]['open-alt'].$this->colorspeak;
        
        if($this->emote == 2) {
            # Wenn emote = 2 (/X), dann andere Farbe gebrauchen
            $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close'] = $this->color3person.$quotationtype[COMMENTARY_QUOTATION_TYPE]['close'];
            $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close-alt'] = $this->color3person.$quotationtype[COMMENTARY_QUOTATION_TYPE]['close-alt'];
        }
        else {
            $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close'] = $this->coloremote.$quotationtype[COMMENTARY_QUOTATION_TYPE]['close'];
            $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close-alt'] = $this->coloremote.$quotationtype[COMMENTARY_QUOTATION_TYPE]['close-alt'];
        }

        if(mb_strpos($commentary, '[') OR mb_strpos($commentary, '{')) {
            # Verwendet die richtigen Platzhalter. Annahme: " und ' werden nicht gebraucht, ignorieren.
            $commentary = str_replace('[', $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open'], $commentary);
            $commentary = str_replace(']', $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close'], $commentary);
            $commentary = str_replace('{', $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open-alt'], $commentary);
            $commentary = str_replace('}', $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close-alt'], $commentary);
        }
        elseif(mb_strpos($commentary, '&quot;') OR mb_strpos($commentary, '&#039;')) {
            # Kommentar bei " splitten
            $commentary = explode('&quot;', $commentary);
            $pieces = count($commentary);        
            
            # Schauen, ob gerade Anzahl an " gebraucht wurde (Also eine ungerade Anzahl Teile), wenn nicht => nicht interpretierbar =/
            if($pieces%2 == 1) {
                $comments = ''; $nocolor = false;
                foreach($commentary as $i => $comment) {
                    if($i > 0) {
                        if($i%2==1) {
                            $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open'];
                        }
                        else {
                            if($nocolor) {
                                $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['close'];
                            }
                            else {
                                $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close'];
                            }
                        }
                    }
                    
                    if(mb_substr($comment, -2, 1) == '`') {
                        $nocolor = true;
                    }
                    else {
                        $nocolor = false;
                    }
                    
                    # Okay. Hier parsen wir ' innerhalb von ", damit beim ausserhalb-parsen kein Verschachtelungsfehler auftritt
                    $subcommentary = explode('&#039;', $comment);
                    $subpieces = count($subcommentary);

                    # Wenn die Teilstücke mehr als eines sind und gerade, dann können wir das sicher interpretieren.
                    if($subpieces > 1 AND $subpieces%2==1) {
                        $subcomments = ''; $subnocolor = false;
                        foreach($subcommentary as $subi => $subcomment) {
                            if($subi > 0) {
                                if($subi%2==1) {
                                    $subcomments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-open-alt'];
                                }
                                else {
                                    if($subnocolor) {
                                        $subcomments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['close-alt'];
                                    }
                                    else {
                                        $subcomments .= $this->speakcolor.$quotationtype[COMMENTARY_QUOTATION_TYPE]['close-alt'];
                                    }
                                }
                            }
                            
                            if(mb_substr($subcomment, -2, 1) == '`') {
                                $subnocolor = true;
                            }
                            else {
                                $subnocolor = false;
                            }
                            
                            $subcomments .= $subcomment;
                        }
                        
                        $comment = $subcomments;
                    }
                    
                    $comments .= $comment;
                }
                
                $commentary = $comments;
            }
            else {
                $commentary = implode('"', $commentary);
            }
            
            # Kommentar nun bei ' splitten
            $commentary = explode('&#039;', $commentary);
            $pieces = count($commentary);        
            
            # Schauen, ob gerade Anzahl an " gebraucht wurde (Also eine ungerade Anzahl Teile), wenn nicht => nicht interpretierbar =/
            if($pieces%2 == 1) {
                $comments = ''; $nocolor = false;
                foreach($commentary as $i => $comment) {
                    if($i > 0) {
                        if($i%2==1) {
                            $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['open-alt'];
                        }
                        else {
                            if($nocolor) {
                                $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['close-alt'];
                            }
                            else {
                                $comments .= $quotationtype[COMMENTARY_QUOTATION_TYPE]['colored-close-alt'];
                            }
                        }
                    }
                    
                    if(mb_substr($comment, -2, 1) == '`') {
                        $nocolor = true;
                    }
                    else {
                        $nocolor = false;
                    }
                    
                    $comments .= $comment;
                }
                
                $commentary = $comments;
            }
            else {
                $commentary = implode('"', $commentary);
            }
        }
        
        return $commentary;
    }
    
    /**
     * Fügt den fertigen Kommentar in die Datenbank ein
     * 
     * @param string $commentary Der (fast) fertige Kommentar
     * @param int $id Die Autoren-ID
     */
    private function Insert($commentary, $author) {
        # Kommentare kürzen, ohne Rücksicht auf Verluste
        $commentary = mb_substr($commentary, 0, COMMENTARY_MAXLENGHT);
    
        # Alte Kommentare sperren
        $sql = 'UPDATE commentary SET locked = 1 WHERE author = :author';
        $args = array(':author' => $author);
        
        if(COMMENTARY_USE_COMPABILITY_MODE === true) {
            db_query_secure($sql, $args);
        }
        else {
            $res = new Query($sql, $args);
        }
        
        # Neuen Kommentar einfügen
        $sql = 'INSERT INTO commentary (author, comment, section, emote, postdate, locked) '
            .'VALUES (:author, :comment, :section, :emote, NOW(), 0)';
        $args = array(
            ':author' => $author,
            ':comment' => $commentary,
            ':section' => $this->section,
            ':emote' => $this->emote,
        );
        
        if(COMMENTARY_USE_COMPABILITY_MODE === true) {
            db_query_secure($sql, $args);
        }
        else {
            $res = new Query($sql, $args);
        }
        
        # Cache verwerfen
        if(COMMENTARY_USE_CACHED_QUERIES) {
            $DataCache = Datacache::getInstance();
            $DataCache->massiveDropCache("commentary-{$this->section}-com");
        }
    }
    
    /**
     * Editiert einen bestehenden Kommentar
     * 
     * @param int $id Die ID des Kommentars
     * @param string $commentary Der (fast) fertige Kommentar
     */
    public function Edit($id, $commentary) {
        # Kommentar trimmen
        $commentary = trim($commentary);
        
        # Kommentar leer? Wenn ja => Keine Überarbeitung gewünscht (Annahme)
        if($commentary != '') {
            $commentary = stripslashes($commentary); // \ rausfiltern. Mistdinger.
            
            # Emote-Check
            $commentary = $this->processEmote($commentary);
            
            # Kommentar säubern
            $commentary = $this->Clear($commentary);
            
            # Schreiben...
            $sql = 'UPDATE commentary 
                SET 
                    emote = :emote, 
                    comment = :comment
                WHERE 
                    commentid = :id AND 
                    locked = 0 AND 
                    author = :author
            ';
            $args = array(
                ':emote' => $this->emote,
                ':comment' => $commentary,
                ':id' => $id,
                ':author' => $this->user['acctid'],
            );
            
            
            if(COMMENTARY_USE_COMPABILITY_MODE === true) {
                db_query_secure($sql, $args);
            }
            else {
                $res = new Query($sql, $args);
            }
            
            # Cache verwerfen
            if(COMMENTARY_USE_CACHED_QUERIES) {
                $DataCache = Datacache::getInstance();
                $DataCache->massiveDropCache("commentary-{$this->section}-com");
            }
        }
    }
    
    /**
     * Für Externe Kommentar-Eingaben (Wie zum Beispiel bei speziellen Ereignissen)
     * 
     * @param int $author Die Autoren-ID
     * @param int $emote Das Emote des Kommentars
     * @param string $commentary Der Kommentar
     * @param string $section Der Ort, wo der Kommentar auftauchen darf
     */
    static public function exInsert($author, $emote, $commentary, $section) {
        # Kommentare kürzen, ohne Rücksicht auf Verluste
        $commentary = mb_substr($commentary, 0, COMMENTARY_MAXLENGHT);
        
        # Neuen Kommentar einfügen
        $sql = 'INSERT INTO commentary (author, comment, section, emote, postdate, locked) '
            .'VALUES (:author, :comment, :section, :emote, NOW(), 1)';
        $args = array(
            ':author' => $author,
            ':comment' => $commentary,
            ':section' => $section,
            ':emote' => $emote,
        );
        
        if(COMMENTARY_USE_COMPABILITY_MODE === true) {
            db_query_secure($sql, $args);
        }
        else {
            $res = new Query($sql, $args);
        }
        
        # Cache verwerfen
        if(COMMENTARY_USE_CACHED_QUERIES) {
            $DataCache = Datacache::getInstance();
            $DataCache->massiveDropCache("commentary-{$this->section}-com");
        }
    }
    
    /**
     * Parst den Link zur Biographie
     * 
     * @param string $link Das Linktemplate zur Biographie
     * @param array $vals Die Argumente, die es in $link zu ersetzen gilt
     * @return string Der geparste und fertige Link
     */
    private function ParseBioLink($link, array $vals) {
        foreach($vals as $search => $replace) {
            $link = str_replace($search, $replace, $link);
        }
        return $link;
    }
    
    /**
     * Gibt die Nachricht oberhalb des Chats («Hier Rollenspiel spielen») aus.
     */
    private function printMessage() {
        output('<p style="margin-top: 2em;">'.$this->message.'</p>', true);
    }
    
    /**
     * Gibt alle Kommentare aus für die aktuelle Seite (Oder den Ajax-Part)
     * 
     * @param bool $postfield Postfeld anzeigen oder ausblenden? true für anzeigen
     */
    public function View($postfield = true) {
        # «Message» ausgeben
        $this->printMessage();
        
        # Aktuelle Seitenzahl zuweisen
        $com = (int)$_GET['comscroll'];
        
        # Kommentare aus der Datenbank holen und formatieren
        $comments = $this->fetch($com);
        
        # Navigation (oben) ausgeben (Vorwärts, Aktualisieren, Weiter)
        if(COMMENTARY_NAVIGATION_POSITION === 'top' OR COMMENTARY_NAVIGATION_POSITION === 'both') {
            $this->Navigation($this->result, 'top');
        }
        
        # Ausgabe der Kommentare
        output($comments, true);
        
        # Textfeld ausgeben, sofern erlaubt
        if($postfield === true) {
            $this->Field();
        }
        
        # Navigation (unten) ausgeben (Vorwärts, Aktualisieren, Weiter)
        if(COMMENTARY_NAVIGATION_POSITION === 'bottom' OR COMMENTARY_NAVIGATION_POSITION === 'both') {
            $this->Navigation($this->result, 'bottom');
        }
    }
    
    /**
     * Gibt das Eingabe-Feld aus
     */
    public function Field($forcenav = false, $filledcomment = '') {        
        # URL für Formular bereiten
        
        # Wenn Forcenav einen Link enthält den eigentlichen Link überschreiben.
        if($forcenav !== false) {
            $url = $forcenav;
        }
        else {
            $url = $this->baselink;
            $url .= 'c='.ER_NAV_EXTRA;
        }
        
        allownav($url);
        rawoutput('<br /><form action="%s" method="post" id="commentform"> ', HTMLSpecialchars($url));
        
        # Eingabefeld erstellen
        
        # Automatische Textarea?
        if(COMMENTARY_AUTOTEXTAREA) {
            # Ja, aber die Mindestmaximalzeichenlimite wurde nicht erreicht
            if(COMMENTARY_MAXLENGHT <= COMMENTARY_AUTOTEXTAREA_CHARS) {
                $forcetextarea = false;
            }
            # Die Limite wurde erreicht => Textarea
            else {
                $forcetextarea = true;
            }
        }
        else {
            # Nein, keine automatische Textarea. Also auch keine Textarea "forcen".
            $forcetextarea = false;
        }
        
        # Chatvorschau?
        if(COMMENTARY_USE_CHATPREVIEW) {
            if(COMMENTARY_FARBHACK_IS_INSTALLED) {
                # Farbhack installiert, hole entsprechendes JavaScript
                $this->PrintJS('chatpreview.withfarbhack');
            }
            else {
                # Farbhack nicht installiert, starre Version verwenden
                $this->PrintJS('chatpreview.withoutfarbhack');
            }
            
            # Chatpreview-Div ausgeben
            rawoutput('<div id="chatpreview"></div><br />'); // ID bitte nicht ändern
            
            $my_name = $this->user['name'] ;
            $clearname = str_replace("`0","",$this->striptag($my_name)) ;
            $my_lastchar = mb_substr($clearname,mb_strlen($clearname)-1,0);
                
            $onkeyupcode = 'document.getElementById(\'chatpreview\').innerHTML = appoencode(
                    this.value,
                    \''.$this->talkline.'\',
                    \''.$my_name.'\',
                    \''.$my_lastchar.'\',
                    \''.mb_substr($this->colorspeak, 1, 1).'\',
                    \''.mb_substr($this->color3person, 1, 1).'\',
                    \''.mb_substr($this->coloremote, 1, 1).'\',
                    \''.$this->user['superuser'].'\',
                    \''.$this->section.'\',
                    '.(COMMENTARY_USE_RPCMD === false ? 'false' : '\''.COMMENTARY_USE_RPCMD.'\'').'
            );';
        }
        else {
            $onkeyupcode = '';
        }
        
        if(COMMENTARY_ACTIVETEXTAREA OR $forcetextarea) {
            # Textarea-Version des Eingabefeldes
            
            # Eventuell Zeichen-Übrig-Anzeige?
            if(COMMENTARY_TEXTAREA_SHOWCHARS) {
                # Ja, Anzeige einblenden
                
                # JavaScript ausgeben für die Anzeige
                $this->PrintJS('textarea.chars');
                
                # Ausgabe der Textarea und der Zeichen-Übrig-Anzeige
                rawoutput('<textarea '.(
                    COMMENTARY_TEXTAREA_FONTFAMILY !== '' ? 
                        'style="font-family: '.COMMENTARY_TEXTAREA_FONTFAMILY.';"'
                        : 
                        ''
                    ).' 
                    rows="'.COMMENTARY_TEXTAREA_ROWS.'" 
                    cols="'.COMMENTARY_TEXTAREA_COLS.'" 
                    name="commentary" 
                    id="textfield" 
                    class="input" 
                    onkeyup="CountMax('.COMMENTARY_MAXLENGHT.'); '.$onkeyupcode.'"
                    >'.$filledcomment.'</textarea>
                    
                    <br />Übrige Zeichen: 
                    
                    <input id="showchars" 
                        value="'.COMMENTARY_MAXLENGHT.'" 
                        size="'.COMMENTARY_SHOWCHARS_SIZE.'" 
                        disabled="disabled" 
                    /><br />');
            }
            else {
                # Keine Solche Anzeige
                
                # Ausgabe der Textarea
                rawoutput('<textarea '.(
                    COMMENTARY_TEXTAREA_FONTFAMILY !== '' ? 
                        'style="font-family: '.COMMENTARY_TEXTAREA_FONTFAMILY.';"'
                        : 
                        ''
                    ).' 
                    rows="'.COMMENTARY_TEXTAREA_ROWS.'" 
                    cols="'.COMMENTARY_TEXTAREA_COLS.'" 
                    name="commentary" 
                    id="textfield" 
                    class="input" 
                    onkeyup="'.$onkeyupcode.'"
                >'.$filledcomment.'</textarea>
                    
                <br />');
            }
        }
        else {
            # Ausgabe des Einzeilerfeldes
            rawoutput('<input 
                id="textfield" 
                size='.COMMENTARY_INPUTFIELD_SIZE.' 
                maxlenght='.COMMENTARY_MAXLENGHT.' 
                name="commentary" 
                class="input"
                onkeyup="'.$onkeyupcode.'"
                value="'.$filledcomment.'" 
            /><br />');
        }

        # Nein, also normalen Knopf geben
        rawoutput('<input type="hidden" value="'.HTMLEntities($this->section).'" name="section" />
            <input type="submit" class="button" value="Absenden" />
            </form>
        ');
    }
    
    /**
     * Holt die gewünschten Kommentare aus der Datenbank
     * 
     * @param int $com Seitenzahl, 0 = neuste Kommentare
     */
    public function Fetch($com) { 
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        
        # SQL präparieren
        $sql = 'SELECT 
                commentary.*, 
                accounts.name, 
                accounts.login, 
                accounts.loggedin, 
                accounts.location, 
                accounts.laston,
                accounts.acctid
            FROM 
                commentary 
            INNER JOIN 
                accounts 
            ON
                accounts.acctid = commentary.author 
            WHERE
                commentary.section = :section 
            ORDER BY 
                commentid DESC 
            LIMIT '.$com*$this->limit.','.$this->limit.'
        ';
        
        # Argumente präparieren
        $args = array(
            ':section' => $this->section,
        );
        
        # Query
        if(COMMENTARY_GUILDTAG_DISPLAY AND COMMENTARY_GUILDTAG_VERSION != '') {
            $sql = $this->GuildPrefixes('overwrite_sql', COMMENTARY_GUILDTAG_VERSION, array('start' => $com*$this->limit, 'limit' => $this->limit));
        }
        
        # Datensätze holen
        if(COMMENTARY_USE_COMPABILITY_MODE === true) {
            $res = db_query_secure($sql, $args);
            
            $result = array();
            
            # Emulation für das Verhalten von Query
            while($row = db_fetch_assoc($res)) {
                $result[] = $row;
            }
        }
        else {
            $result = new Query($sql, $args);
        }
        
        # Resultat speichern
        $this->result = &$result;    
        
        # Variablen vordefinieren
        $i = 0;
        $comments = array();
        define('endl',"\r\n");
        
        # Je nach Situation ist ein Biolink gewünscht - oder eben nicht
        if(NOBIO === false) {
            # Biolink nicht unerwünscht, Templates aus Einstellungen lesen
            $linktemplate = COMMENTARY_BIO_LINKTEMPLATE;
            $link = COMMENTARY_BIO_LINK;
        }
        else {
            # Biolink unerwünscht, nehme linklose Standardtemplates
            $linktemplate = '{NAME}';
            $link = '';
        }
        
        # Für jeden Datensatz...
        foreach($result as $row) {        
            # Für die Biotemplates Variablen präparieren
            $replacearray = array(
                '{ACCTID}' => $row['acctid'],
                '{REQUESTURI}' => RawURLEncode($this->baselink), 
                '{LOGIN}' => RawURLEncode($row['login'])
            );
            
            if(COMMENTARY_USE_COMPABILITY_MODE) {
                $replacearray['{REQUESTURI}'] = '/'.$replacearray['{REQUESTURI}'];
            }
            
            # Der (unersetzte) Name zurückstellen
            $row['namebackup'] = $row['name'];
            
            # Link zur Bio parsen
            if($link != '') {
                $link = $this->ParseBioLink(COMMENTARY_BIO_LINK, $replacearray);
                allownav($link);
            }
            
            # Zusätzliche Tags einspeisen
            $replacearray['{NAME}'] = $row['name'];
            $replacearray['{POPUP}'] = popup($link).'; return false;';
            
            # Name zu Link umwandeln
            $row['name'] = $this->ParseBioLink($linktemplate, $replacearray);
            
            # Gildentag-Anzeige
            if(COMMENTARY_GUILDTAG_DISPLAY) {
                $row['name'] = $this->GuildPrefixes('addprefix', COMMENTARY_GUILDTAG_VERSION, array('row' => $row, 'name' => $row['name']));
            }
            
            # Für etwaige Suffixe
            $suffix = '';
            
            # Holen von Edit- und Del-Prefixen
            $delprefix = $this->getDelPrefix($row);
            
            # Holen des Timestamps, sofern aktiviert
            if(COMMENTARY_TIMESTAMP_DISPLAY) {
                $tsprefix = $this->getTimestampPrefix($row);
            }
            
            # Präfixe aneinander ordnen
            $prefix = $delprefix.$tsprefix;
            #echo $tsprefix;
            
            # Verwender von /X entlarven
            if(COMMENTARY_DISPLAYEMOTLERNAME === true AND $this->user['superuser'] >= COMMENTARY_DISPLAYEMOTLERNAME_SULEVEL) {
                $emotename = ' `0('.trim($row['namebackup']).')`0 ';
            }
            else {
                $emotename = '';
            }
            
            # Kommentar verarbeiten
            $comments[] = $this->processComment($row, $emotename, $prefix, $suffix);
            $i++;
        }
        
        # Kommentare rückwärts sortieren
        krsort($comments);
        
        # Kommentare zusammenfügen und mit Absätzen trennen - nur aus Schönheitsgründen
        $rtext = implode("\n\n", $comments);
        
        # Zusammengeklebte Kommentare zurückgeben
        return $rtext;
    }
    
    /**
     * Erstellt das Lösch- und das Editier-Präfix
     * 
     * @param array $row Die Zeile des Datensatzes, für den die Präfixe erstellt werden sollen
     * @return string Die erstellen Präfixe hintereinander geordnet
     */
    protected function getDelPrefix($row) {
        # Prefix für Superuser-Löschen
        if($this->user['superuser'] >= COMMENTARY_LIVEDELETING_SULEVEL) {
            # Füge den Löschlink hinzu        
            $link = sprintf('%s&commentid=%d&section=%s&return=%s', 
                COMMENTARY_LIVEDELETING_DELETETARGET,
                $row['commentid'],
                rawURLEncode($this->section),
                RawURLEncode(navStripC($_SERVER['REQUEST_URI']))        
            );
            
            $prefix = sprintf('[<a href="%s">X</a>]&nbsp;', HTMLSpecialchars($link));

            allownav($link);
            
            # Prefix fürs Editieren beim eigenen (letzten) Post...
            if($this->user['acctid'] == $row['acctid'] AND $row['locked'] == 0) {
                $link = 'commentary_handler.php?q=%s&id=%s&return=%s';
                $editlink = sprintf($link, 'edit', $row['commentid'], self::encodeStringForURL($this->baselink));
                allownav($editlink);
                
                $prefix .= sprintf('[<a href="%s">Edit</a>]&nbsp;', $editlink);
            }
        }
        # Prefixe für Löschen und/oder editieren des letzten Postes
        elseif($this->user['acctid'] == $row['acctid'] AND $row['locked'] == 0) {
            $link = 'commentary_handler.php?q=%s&id=%s&return=%s';
            $editlink = sprintf($link, 'edit', $row['commentid'], self::encodeStringForURL($this->baselink));
            $dellink = sprintf($link, 'delete', $row['commentid'], self::encodeStringForURL($this->baselink));
            allownav($editlink);
            allownav($dellink);
            
            $prefix = sprintf('<a href="%s">[X]</a>&nbsp;<a href="%s">[Edit]</a>', $dellink, $editlink);
        }
        # Kein Prefix...
        else {
            $prefix = '';
        }
        
        return $prefix;
    }
    
    /**
     * Erstellt das Präfix für den Timestamp
     * 
     * @param array $row Die Zeile des Datensatzes, für den das Timestamp-Präfix erstellt werden soll
     * @return string Das erstelle Timestamp-Prefix
     */
    protected function getTimestampPrefix($row) {
        switch(COMMENTARY_TIMESTAMP_TYPE) {
            case 'wz_tooltip': {
                $prefix = '<img src="images/hourglass.png" style="height: 1em; position: relative; top: 0.2em; margin-right: 1ex;" onmouseover="Tip(\''.date(COMMENTARY_TIMESTAMP_FORMAT, strToTime($row['postdate'])).'\')" />';
                break;
            }
            
            default:
                $prefix = '`0['.date(COMMENTARY_TIMESTAMP_FORMAT, strToTime($row['postdate'])).']`0';
                break;
        }
        
        return $prefix;
    }
    
    /**
     *  Verarbeitet den Kommentar nach Emote und setzt ihn zusammen, so dass diese Zeile fertig ist.
     * 
     * @param array $row Die Zeile des Datensatzes, dessen Kommentar verarbeitet werden soll
     * @param string $emotename Der Name des Verwenders von /X - wird nur für /X-Emotes gebraucht
     * @param string $prefix Etwas, das dem eigentlichen Kommentar vorangestellt werden soll
     * @param string $suffix Etwas, das dem eigentlichen Kommentar hintergestellt werden soll
     * @return string Der verarbeitete Kommentar
     */
    protected function processComment($row, $emotename = '', $prefix = '', $suffix = '') {
        $ampsearch = array('`&amp;');
        $ampreplace = array('`&');
        
        # Post nach Emote zusammenbauen
        switch($row['emote']) {
            case 3: {
                $lastchar = mb_strtolower(mb_substr($this->StripTag($row['namebackup']), -1));
                
                switch($lastchar) {
                    case 's':
                    case 'z':
                    case 'c':
                        $post = $this->playernamecolor.mb_substr($row['name'], 0, -2)."&rsquo; ".$this->coloremote.$this->nl2paragraph($row['comment'])."`0".endl;
                        break;
                        
                    default:
                        $post = $this->playernamecolor.mb_substr($row['name'], 0, -2)."s ".$this->coloremote.$this->nl2paragraph($row['comment'])."`0".endl;
                        break;
                }
                break;
            }
            
            case 2: {
                $post = $emotename.$this->coloremote.$this->nl2paragraph($row['comment'])."`0".endl;;
                break;
            }
            
            case 1: {
                $post = $this->playernamecolor.$row['name'].' '.$this->color3person.$this->nl2paragraph($row['comment'])."`0".endl;
                break;
            }
            
            case 0: {
                $post = $this->playernamecolor.mb_substr($row['name'], 0, -2).$this->coloremote." sagt: «".$this->colorspeak.trim($this->nl2paragraph($row['comment'])).$this->coloremote."»`0".endl;
                break;
            }
        }
        
        # Kommentar erstellen
        $comment = $prefix.str_replace($ampsearch, $ampreplace, $post).$suffix;
        
        # Parapraphen um den Kommentar herum stellen
        if(COMMENTARY_USEPARAGRAPHS === true) {
            $comment = sprintf('<p style="line-height: %fem; margin-top: %fem; margin-bottom: %dem;">%s</p>',
                COMMENTARY_LINEHEIGHT,
                COMMENTARY_PARAGRAPHS_MARGIN/2,
                COMMENTARY_PARAGRAPHS_MARGIN/2,
                $comment
            );
        }
        else {
            $comment = $comment.'<br />';
        }
        
        return $comment;
    }
        
    /**
     * Gibt das Datum des neusten Kommentares zurück
     * 
     * @return string Y-m-d H:i:s des neusten Kommentares
     */
      public function getLastModified() {
        return $this->result[0]['postdate'];
    }
    
    /**
     * Gibt die maximale Anzahl Seiten zurück
     * 
     * @return int Seitenanzahl
     */
    public function getPageNum() {
        static $coms;
        
        if(empty($coms) AND $coms !== 0) {
            if(COMMENTARY_USE_COMPABILITY_MODE) {
                $res = db_query_secure("SELECT COUNT(commentary.commentid) as counter FROM commentary WHERE section=:section", array(':section' => $this->section));
                $row = db_fetch_assoc($res);
            }
            else {
                $res = new Query("SELECT COUNT(commentary.commentid) as counter FROM commentary WHERE section=:section", array(':section' => $this->section));
                $row = $res[0];
            }
                
            $rownum = $row['counter'];
            
            $coms = ceil($rownum/$this->limit)-1;
            
            if($coms < 0) {
                $coms = 0;
            }
        }
        
        return $coms;
    }
    
    public function getSuPageNum($section) {
        static $coms;
        
        if(empty($coms) AND $coms !== 0) {
            if(COMMENTARY_USE_COMPABILITY_MODE) {
                $res = db_query_secure("SELECT COUNT(commentary.commentid) as counter FROM commentary WHERE section=:section", array(':section' => $section));
                $row = db_fetch_assoc($res);
            }
            else {
                $res = new Query("SELECT COUNT(commentary.commentid) as counter FROM commentary WHERE section=:section", array(':section' => $section));
                $row = $res[0];
            }
                
            $rownum = $row['counter'];
            
            $coms = ceil($rownum/$this->limit)-1;
            
            if($coms < 0) {
                $coms = 0;
            }
        }
        
        return $coms;
    }
    /**
     * Löscht _alle_ Farbtags aus dem übergebenen Parameter
     * 
     * @param string $input String, dem die Farbtags entnommen werden sollen
     * @return string Der gesäuberte String
     */
    private function StripTag($input) {
      // 2005-2006 by Eliwood
      return preg_replace("'[`].'","",$input);
    } 
    
    /**
     * Gibt das gewünschte JavaScript aus
     * 
     * @param string $which Angabe, welches JS gewünscht ist: chatpreview.withoutfarbhack, chatpreview.withfarbhack, textarea.chars, chat.ajax.part1, chat.ajax.part2
     */
    private function PrintJS($which, $para = false, $bothnav = false) {
        switch($which) {
            case 'textarea.chars': {
                // Script taken from anpera.NET; Originaly by Day aka Kevz, modified by Eliwood
                rawoutput('<script language="javascript" src="lib/commentary.textarea.chars.js"></script>');
                break;
            }
                
            case 'chatpreview.withoutfarbhack': {
                # Chatvorschau, Orignal von Chaosmaker, modifziziert von blackfin. Danke an Rikka für das rausrücken vom Code *g*
                # Weitere, kleine Modifikationen von Basilius Sauter.
                
                rawoutput('<script language="javascript" src="lib/commentary.chatpreview.withoutfarbhack.js"></script>');
                break;
            }
            
            case 'chatpreview.withfarbhack': {
                global $appoencode;
                # Chatvorschau, Orignal von Chaosmaker, modifiziert von blackfin. Danke an Rikka für das rausrücken vom Code *g*
                # Weitere, kleine Modifikationen von Basilius Sauter.
                
                rawoutput('<script language="javascript" src="lib/commentary.chatpreview.withfarbhack.js.php"></script>');
                break;
            }
        }
    }
    
    /**
     * Gibt die Navigationlinks für den Chat aus
     * 
     * @param DB_Result $result Das Objekt, das die Ergebnisse des Queries in sich hat
     */
    private function Navigation($result, $position) {        
        $com = (int)$_GET['comscroll'];
        
        /*
         * Blätter-Links:
         *   %1$s => Link
         *   %2$s => Beschriftung des Links
         *   %3$s => Linker Zusatz
         *   %4$s => Rechter Zusatz
         * Aktualisier-Link:
         *      %1$s => Link
         *   %2$s => Beschriftunk des Links
         */
        switch(COMMENTARY_NAVIGATION_DISPLAYTYPE) {
            case 'anchor': {
                $template = array(
                    '%3$s<a href="%1$s">%2$s</a>%4$s</span>',
                    '<a href="%1$s">%2$s</a>',
                    '&nbsp;-&nbsp;',
                    '<p><a href="%1$s">%2$s</a></p>',
                );
                break;
            }
            
            case 'button': {
                $template = array(
                    '<form action="%1$s" style="float: left; !important" method="post">%3$s<input value="%2$s" type="submit" class="button" />%4$s</form>',
                    '<form action="%1$s" style="float: left; !important" method="post"><input value="%2$s" type="submit" class="button" /></form>',
                    '',
                    '<form action="%1$s" method="post"><p><input value="%2$s" type="submit" class="button" /></p></form>',
                );
                break;
            }
            
            case 'fakebutton': {
                $template = array(
                    '%3$s<a href="%1$s" class="button">%2$s</a>%4$s</span>',
                    '<a href="%1$s" class="button">%2$s</a>',
                    '',
                    '<p><a href="%1$s" class="button">%2$s</a></p>',
                );
                break;
            }
        }
        
        
        $maxcom = $this->getPageNum();
        
        # Erste Seite-Link (In Anaras vor langer Zeit gesehen)
        if($com < $maxcom-1 AND COMMENTARY_NAVIGATION_USE_SKIPLINKS) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $maxcom, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), '«« Erste Seite', '', $template[2]);
            //rawoutput('<a href="%s">«« Erste Seite</a>&nbsp;|&nbsp;', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Zurück-Link
        if($com < $maxcom) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com+1, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), '« Vorherige', '', $template[2]);
            //rawoutput('<a href="%s">« Vorherige</a>&nbsp;|&nbsp;', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Aktualisieren-Link
        $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com, ER_NAV_EXTRA); // Nav präparieren
        allownav($req); // Nav auf die Whitelist setzen
        rawoutput($template[1], HTMLSpecialchars($req), 'Aktualisieren');
        //rawoutput('<a href="%s">Aktualisieren</a>', HTMLSpecialchars($req)); // Ausgeben
        
        # Vorwärts-Link
        if($com > 0) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com-1, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), 'Nächste »', $template[2], '');
            //rawoutput('&nbsp;|&nbsp;<a href="%s">Nächste »</a>', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Neuste Seite-Link (In Anaras vor langer Zeit gesehen)
        if($com > 1 AND COMMENTARY_NAVIGATION_USE_SKIPLINKS) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, 0, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), 'Neuste »»', $template[2], '');
            //rawoutput('&nbsp;|&nbsp;<a href="%s">Neuste »»</a>', HTMLSpecialchars($req)); // Ausgeben
        }
        
        rawoutput('<br />');
        
        if($position === COMMENTARY_EXPORTLINK_POSITION OR COMMENTARY_EXPORTLINK_POSITION === 'both') {
            # Export-Funktion
            if(COMMENTARY_ALLOW_EXPORT) {
                $req = 'exportcomments.php?section='.$this->section.'&return='.encodeStringForURL($this->baselink);
                allownav($req);
                rawoutput($template[3], HTMLSpecialchars($req), 'RP speichern');
            }
        }
        
        # Nur für Wintertal
        if(COMMENTARY_USE_RPPOINTS == 'wintertal') {
            # Chathilfe [Added by Abraxas @ wintertal.de]
            $req = 'chathilfe.php';
            allownav($req);
            rawoutput($template[3], HTMLSpecialchars($req), 'Chathilfe');
            
            # Farblegende [Added by Abraxas @ wintertal.de]
            $req = 'farblegende.php';
            allownav($req);
            rawoutput($template[3], HTMLSpecialchars($req), 'Farblegende');
        }
    }

    /**
     * Maskiert die Farbtags im String
     * 
     * @param string $inpurt String, dem die Farbtags maskiert werden sollen
     * @return string maskierter String
     */
    private function maskColors($input) {
        return str_replace('`', '``', $input);    
    }
    
    public static function encodeStringForURL($url) {
        if(mb_substr($url, -1) == '?') {
            $url = mb_substr($url, 0, -1);
        }
        $url = str_replace('&', ';;;', $url);
        $url = rawURLEncode($url);
        return $url;
    }
    
    public static function decodeStringForURL($url) {
        $url = rawURLDecode($url);
        $url = str_replace(';;;', '&', $url);
        return $url;
    }
    
    /**
     * Verwandelt newline in richtige Absätze
     * 
     * @param string $input der String, der in Absätze verwandelt werden soll
     * @return string Der neue String mit echten Absätzen
     */
    private function nl2paragraph($input) {
        if(COMMENTARY_ALLOWPARAGRAPHS) {
            $input = str_replace(COMMENTARY_MANUALPARAGRAPHCHAR, "\n", $input);
            if(COMMENTARY_USEPARAGRAPHS) {
                $input = str_replace("\r\n", "\n", $input);
                $input = str_replace("\r", "\n", $input);
                $exploded = explode("\n", $input);
                return implode('</p><p style="line-height: '.COMMENTARY_LINEHEIGHT.'em; margin-top: '.(COMMENTARY_PARAGRAPHS_MARGIN/2).'em; margin-bottom: '.(COMMENTARY_PARAGRAPHS_MARGIN/2).'em;">', $exploded);
            }
            else {
                $search = array("\r\n", "\r", "\n");
                return str_Replace($search, '<br />', $input);
            }
        }
        else return $input;
    }
    
    /**
     * Kommentar-Ansicht für die Chatgrotte
     * 
     * @param string $deletetarget Das Ziel des Linkes zum Löschabschnitt
     * @param string $sectionviewfile Der Link zur Sektionsübersicht
     */
    public function SuView($deletetarget = 'superuser.php?op=commentdelete', $sectionviewfile = 'superuser.php?op=checkcommentary') {
        $com = (int)$_GET['comscroll'];
        $REQUEST_URI = $_SERVER['REQUEST_URI'];        
        
        if(empty($_GET['section'])) {
            $where = '';
            $i = 0;
            
            foreach($this->section as $disallowedsection) {
                $where.= ($i > 0?'AND ':'WHERE ').'`commentary`.`section` NOT LIKE "'.$disallowedsection.'"';
                $i++;
            }
            
            $sql = 'SELECT 
                    `commentary`.`section`,
                    COUNT(`commentid`) as counter
                FROM 
                    `commentary` 
                INNER JOIN 
                    `accounts` 
                ON
                    `accounts`.`acctid` = `commentary`.`author` 
                '.$where.'
                GROUP BY 
                    `section`
                LIMIT '.($com*$this->limit).','.$this->limit.' ';
        }
        else {
            $where = 'WHERE `section` = "'.$_GET['section'].'" ';
            
            $sql = 'SELECT 
                    `commentary`.*, 
                    `accounts`.`name`, 
                    `accounts`.`login`, 
                    `accounts`.`loggedin`, 
                    `accounts`.`location`, 
                    `accounts`.`laston` 
                FROM 
                    `commentary` 
                INNER JOIN 
                    `accounts` 
                ON
                    `accounts`.`acctid` = `commentary`.`author` 
                '.$where.'
                ORDER BY 
                    `section` ASC,
                    `commentid` DESC
                LIMIT '.($com*$this->limit).','.$this->limit.' ';
        }
        
        if(COMMENTARY_USE_COMPABILITY_MODE) {
            $res = db_query($sql);
            $result = array();
            
            # Emulation für das Verhalten von Query()
            while($row = db_fetch_assoc($res)) {
                $result[] = $row;
            }
        }
        else {
            $result = new Query($sql);
        }
        
        $i = 0;
        $comments = array();
        $sections = array();
        $counts = array();
        $search = array('`&amp;');
        $replace = array('`&');
        define('endl',"\r\n");

        $linktemplate = '{$NAME}';
        $acsection = '';
        
        foreach($result as $row) {
            $row['comment'] = preg_replace("'[`][^".COMMENTARY_ALLOWEDTAGS."]'","",$row['comment']);
          
            $sea4linktemplate = array('{$NAME}');
            $rep4linktemplate = array($row['name']);

            $sections[] = $row['section'];
            
            $row['namebackup'] = $row['name'];
            $row['name'] = str_replace($sea4linktemplate,$rep4linktemplate,$linktemplate);
            $delprefix = '[ <a href="'.$deletetarget.'&commentid='.$row['commentid'].'&return='.RawURLEncode($_SERVER['REQUEST_URI']).'">X</a> ]&nbsp;';
            addnav("",$deletetarget.'&commentid='.$row['commentid'].'&return='.RawURLEncode($_SERVER['REQUEST_URI']));
            
            // Timestamp
            if(COMMENTARY_TIMESTAMP_DISPLAY === true) {
                $prefix .= '`0['.date(COMMENTARY_TIMESTAMP_FORMAT, strToTime($row['postdate'])).']`0';
            }    
            // Emotler entlarven
            if(COMMENTARY_DISPLAYEMOTLERNAME === true AND $this->user['superuser'] >= COMMENTARY_DISPLAYEMOTLERNAME_SULEVEL) {
                $emotename = ' `0('.trim($row['namebackup']).')`0 ';
            }
            else {
                $emotename = '';
            }
            
            switch($row['emote']) {
                case 3:
                    $lastchar = mb_strtolower(mb_substr($this->StripTag($row['namebackup']), -1));
                    switch($lastchar) {
                        case 's':
                        case 'z':
                        case 'c':
                            $comments[] = $delprefix.str_replace($search,$replace,
                                '`&'.mb_substr($row['name'], 0, -2)."`` ".$this->nl2paragraph($row['comment'])."`0\r\n");
                            break;
                            
                        default:
                            $comments[] = $delprefix.str_replace($search,$replace,
                                '`&'.mb_substr($row['name'], 0, -2)."s ".$this->nl2paragraph($row['comment'])."`0\r\n");
                            break;
                    }
                    break;
                    
                case 2:
                    $comments[] = $delprefix.str_replace($search,$replace,
                    $emotename.$this->nl2paragraph($row['comment'])."`0\r\n");
                    break;
                    
                case 1:
                    $comments[] = $delprefix.str_replace($search,$replace,
                    '`&'.$row['name'].' '.$this->nl2paragraph($row['comment'])."`0\r\n");
                    break;
                    
                default:
                    $comments[] = $delprefix.str_replace($search,$replace,
                    '`&'.$row['name'].' '.$this->colortalkline.$this->talkline.': "'.$this->nl2paragraph($row['comment']).$this->colortalkline."\"`0\r\n");
            }
            
            if(empty($_GET['section'])) {
                $counts[] = $row['counter'];
            }
            $i++;
        }
        
        // Ausgabe
        krsort($comments); krsort($sections); ksort($counts);
        reset($comments);    reset($sections); reset($counts);
        $acsection = '';
        while (list($sec,$v)=each($comments)){
            if($acsection != $sections[$sec]) {
                $acsection = $sections[$sec];
                if(empty($_GET['section'])) {
                    $count = $counts[$sec];
                    output('<h3><b><a href="'.$sectionviewfile.'&section='.RawURLEncode($acsection).'">'.$acsection.'</a> ('.$count.')</b></h3>', true);
                    addnav('', $sectionviewfile.'&section='.RawURLEncode($acsection));
                }
                else {
                    output('<h3><b>'.$acsection.'</b></h3>', true);
                }        
            }
            
            if(!empty($_GET['section'])) {
                if(COMMENTARY_USEPARAGRAPHS === true) {
                    output('<p style="line-height: '.COMMENTARY_LINEHEIGHT.'em; margin-top: '.(COMMENTARY_PARAGRAPHS_MARGIN/2).'em; margin-bottom: '.(COMMENTARY_PARAGRAPHS_MARGIN/2).'em;">'.$v.'</p>',true);
                }
                else {
                    output($v.'<br />',true);
                }
            }
        }
        
        $this->SuNavigation($result, $sectionviewfile, $_GET['section']);
    }

    /**
     * Gibt die Navigationlinks für den Superuser-Chat aus
     * 
     * @param DB_Result $result Das Objekt, das die Ergebnisse des Queries in sich hat
     * @param string $sectionviewfile Der Link zur Sektionsübersicht
     */
    private function SuNavigation($result, $sectionviewfile, $section) {
        /*
         * Blätter-Links:
         *   %1$s => Link
         *   %2$s => Beschriftung des Links
         *   %3$s => Linker Zusatz
         *   %4$s => Rechter Zusatz
         * Aktualisier-Link:
         *      %1$s => Link
         *   %2$s => Beschriftunk des Links
         */
        switch(COMMENTARY_NAVIGATION_DISPLAYTYPE) {
            case 'anchor': {
                $template = array(
                    '%3$s<a href="%1$s">%2$s</a>%4$s</span>',
                    '<a href="%1$s">%2$s</a>',
                    '&nbsp;-&nbsp;',
                    '<p><a href="%1$s">%2$s</a></p>',
                );
                break;
            }
            
            case 'button': {
                $template = array(
                    '<form action="%1$s" style="float: left; !important" method="post">%3$s<input value="%2$s" type="submit" class="button" />%4$s</form>',
                    '<form action="%1$s" style="float: left; !important" method="post"><input value="%2$s" type="submit" class="button" /></form>',
                    '',
                    '<form action="%1$s" method="post"><p><input value="%2$s" type="submit" class="button" /></p></form>',
                );
                break;
            }
            
            case 'fakebutton': {
                $template = array(
                    '%3$s<a href="%1$s" class="button">%2$s</a>%4$s</span>',
                    '<a href="%1$s" class="button">%2$s</a>',
                    '',
                    '<p><a href="%1$s" class="button">%2$s</a></p>',
                );
                break;
            }
        }
        
        $maxcom = $this->getSuPageNum($section);
        $com = (int)$_GET['comscroll'];

        # Erste Seite-Link (In Anaras vor langer Zeit gesehen)
        if($com < $maxcom-1 AND COMMENTARY_NAVIGATION_USE_SKIPLINKS) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $maxcom, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), '«« Erste Seite', '', $template[2]);
            //rawoutput('<a href="%s">«« Erste Seite</a>&nbsp;|&nbsp;', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Zurück-Link
        if($com < $maxcom) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com+1, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), '« Vorherige', '', $template[2]);
            //rawoutput('<a href="%s">« Vorherige</a>&nbsp;|&nbsp;', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Aktualisieren-Link
        $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com, ER_NAV_EXTRA); // Nav präparieren
        allownav($req); // Nav auf die Whitelist setzen
        rawoutput($template[1], HTMLSpecialchars($req), 'Aktualisieren');
        //rawoutput('<a href="%s">Aktualisieren</a>', HTMLSpecialchars($req)); // Ausgeben
        
        # Vorwärts-Link
        if($com > 0) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, $com-1, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), 'Nächste »', $template[2], '');
            //rawoutput('&nbsp;|&nbsp;<a href="%s">Nächste »</a>', HTMLSpecialchars($req)); // Ausgeben
        }
        
        # Neuste Seite-Link (In Anaras vor langer Zeit gesehen)
        if($com > 1 AND COMMENTARY_NAVIGATION_USE_SKIPLINKS) {
            $req = sprintf('%scomscroll=%d&c=%s', $this->baselink, 0, ER_NAV_EXTRA); // Nav präparieren
            allownav($req); // Nav auf die Whitelist setzen
            rawoutput($template[0], HTMLSpecialchars($req), 'Neuste »»', $template[2], '');
            //rawoutput('&nbsp;|&nbsp;<a href="%s">Neuste »»</a>', HTMLSpecialchars($req)); // Ausgeben
        }
            
        rawoutput('<br />');
            
        # Zurück zur Übersicht
        if(!empty($_GET['section'])) {
            //rawoutput('<br />&nbsp;<a href="'.$sectionviewfile.'">Zurück zur Übersicht</a>&nbsp;');
            rawoutput($template[3], $sectionviewfile, 'Zurück zur Übersicht');
            allownav($sectionviewfile);
        }
    }
    
    /**
     * Fügt die Gildenpräfixe (oder -suffixe) an oder erstellt den Kommentar-Hol-Query für Gildenpräfixe
     * 
     * @param string $hook overwrite_sql oder addprefix
     * @param string $guildv ID der Gildenversion (dashguild, eliguild, eliguildv2)
     * @param mixed $args zusätzliche Argumente
     */ 
    private function GuildPrefixes($hook, $guildv, $args) {
        switch($hook) {
            case 'overwrite_sql': {
                switch($guildv) {
                    case 'dashguild': {
                        $sql = 'SELECT
                                commentary.*,
                                accounts.loggedin,
                                accounts.name,
                                accounts.login,
                                accounts.guildID,
                                accounts.prefs,
                                accounts.acctid,
                                lotbd_guilds.GuildPrefix
                            FROM commentary
                            INNER JOIN accounts
                                ON accounts.acctid = commentary.author
                            LEFT JOIN lotbd_guilds
                                ON lotbd_guilds.ID = accounts.GuildID
                            WHERE section = :section
                                AND accounts.locked=0
                            ORDER BY commentid DESC
                            LIMIT '.$arg['start'].','.$arg['limit'];
                        break;
                    }
                        
                    case 'eliguild': {
                        $sql = 'SELECT
                                commentary.*,
                                accounts.name,
                                accounts.login,
                                accounts.prefs,
                                accounts.loggedin,
                                accounts.location,
                                accounts.laston,
                                accounts.memberid,
                                accounts.acctid,
                                gilden.gildenprefix,
                                gilden.gildenid,
                                gilden.leaderid,
                                accounts.acctid
                            FROM commentary
                            INNER JOIN accounts
                                ON accounts.acctid = commentary.author
                            LEFT JOIN gilden
                                ON gilden.leaderid = accounts.acctid OR gilden.gildenid = accounts.memberid
                            WHERE section = :section
                                  AND accounts.locked=0
                            ORDER BY commentid DESC
                              LIMIT '.$arg['start'].','.$arg['limit'];
                        break;
                    }
                        
                    case 'eliguildv2': {
                        $sql = 'SELECT 
                                c.*,
                                a.name,
                                a.login,
                                a.loggedin,
                                a.location,
                                a.laston,
                                a.acctid,
                                g.tag AS guildtag,
                                g.guildid,
                                a.`acctid`,
                                gs.value AS tagposition
                            FROM commentary c
                            INNER JOIN accounts a
                                ON a.acctid = c.author
                            LEFT OUTER JOIN guild_members gm
                                ON gm.acctid = a.acctid
                            LEFT JOIN guilds g
                                ON g.guildid = gm.guildid
                            LEFT JOIN guild_settings gs
                                ON gs.guildid = g.guildid AND 
                                gs.settingname = \'tagposition\'
                            WHERE c.section LIKE :section
                                AND a.locked=0
                            ORDER BY c.commentid DESC
                            LIMIT '.$arg['start'].','.$arg['limit'];
                        break;
                    }
                
                    default:  {
                        $sql = 'SELECT 
                                commentary.*, 
                                accounts.name, 
                                accounts.login, 
                                accounts.loggedin, 
                                accounts.location, 
                                accounts.laston,
                                accounts.acctid
                            FROM 
                                commentary 
                            INNER JOIN 
                                accounts 
                            ON
                                accounts.acctid = commentary.author 
                            WHERE
                                commentary.section = :section
                            ORDER BY 
                                commentid DESC 
                            LIMIT '.$arg['start'].','.$arg['limit'];
                    }
                }
                return $sql;
                break;
            }
                
            case 'addprefix': {
                $row = $args['row'];
                
                switch($guildv) {
                    case 'dashguild': {    
                        # Dashers Gilden
                        if (!(unserialize($row['GuildPrefix'])===false)) {
                            $row['GuildPrefix']=unserialize($row['GuildPrefix']);
                            $pre=$row['GuildPrefix']['pre']; // Prefix or postfix
                            $prefix=$row['GuildPrefix']['display']."";  // This Guild TLA
                            if ($prefix!="") {
                                // The link to display the guild info for non-members
                                $Guildshortlink = "guild.php?op=nonmember&action=examine&id=".$row['guildID'].
                                "&return=".URLEncode(CalcReturnPath());
                                $Guildlink = "`0<a href='".$Guildshortlink."' style='text-decoration: none'>`0[$prefix`0]</a>`& ";
                                switch ( $pre ) {
                                    case 0:  // suffix
                                    $guildsuf = $Guildlink;
                                    $guildpre = "";
                                    addnav("",$Guildshortlink);
                                    break;
                                    case 1:  // prefix
                                    $guildsuf = "";
                                    $guildpre = $Guildlink;
                                    addnav("",$Guildshortlink);
                                    break;
                                    case 2:  // nofix
                                    default:
                                    $guildsuf = "";
                                    $guildpre = "";
                                    break;
                                }
                            }
                        }
                        $name = $guildpre.' '.$args['name'].' '.$guildsuf;
                        break;
                    }
                        
                    case 'eliguild': {
                        if($row['gildenid'] > 0) {
                            $link2 = "`2[`0<a href='showdetail.php?id=".$row['gildenid']."' target='window_popup' onClick=\"".popup("showdetail.php?id=".$row['gildenid'])."; return false;\">`&".stripslashes($row['gildenprefix'])."`&</a>`2]`0";
                        }
                        
                        $name = $link2.' '.$args['name'];
                        break;
                    }
                        
                    case 'eliguildv2': {
                        if(!empty($row['guildid']) AND $row['tagposition'] != 'none') {
                            #guild.show.php?guildid=1
                            $tag = '`2[`0<a href="guild.show.php?guildid='.$row['guildid'].'" target="window_popup" onClick="'.popup("guild.show.php?guildid=".$row['guildid']).'; return false;">`&'.stripslashes($row['guildtag']).'`&</a>`2]`0';
                            
                            if($row['tagposition'] == 'prefix') {
                                $name = $tag.' '.$args['name'];
                            }
                            else {
                                $name = $args['name'].' '.$tag;
                            }
                        }
                        else {
                            $name = $args['name'];
                        }
                        break;
                    }
                    
                    default: {
                        $name = $args['name'];
                        break;
                    }
                }
                return $name;
                break;
            }
        }
    }
}


/**
 * Wrapperfunktion für alte Funktionsaufrufe. Erlaubt das Hinzufügen von Kommentaren
 */
function addcommentary() {
    define('COMMENTARY_ADD',true);
    if(isset($_POST['commentary'])) {
        $session['comment_for_insert'] = $_POST;
    }
}

/**
 * Wrapperfunktion für alte Funktionsaufrufe. Stellt die Anzeige der Kommentare und ihre Werkzeuge bereit
 * 
 * @param string $section Sektions-ID
 * @param string $message Nachricht, die vor dem Chat ausgegeben werden soll. x für Admin-Ansicht, y für View-Only
 * @param int $limit Anzahl Kommentare je Seite
 * @param string $talkline Die Sprechzeile
 * @param mixed $arg5 Zusätzliche Argumente für Superuser-Anzeige oder false, wenn die Argumente nicht übertragen werden sollen
 */
function viewcommentary($section, $message = COMMENTARY_DEFAULTMESSAGE,$limit = COMMENTARY_DEFAULTLIMIT,  $talkline = COMMENTARY_DEFAULTTALKLINE, $arg5 = false) {
    global $session;
    
    if(COMMENTARY_USE_COMPABILITY_MODE) {
        $comment = new Commentary(&$session['user'],$section,$message,$limit,$talkline);
    }
    else {
        $comment = new Commentary(&$session->user->data,$section,$message,$limit,$talkline);
    }
  
    /*    Entklammere die Untere Funktion, wenn du die Standardfarben des Users injezieren willst
     *  Der Erste Parameter ist für die Sprechfarbe, der zweite Parameter für die /me-Farbe, der dritte für die /X-Farbe. */
    $comment->ChangeDefaultColors('`%', '`5', '`^');

    /*if(defined('COMMENTARY_ADD') && mb_strtolower($message) != 'x' && mb_strtolower($message) != 'y') {
        $comment->Add();
    } */
    
    if(mb_strtolower($message) == 'x') {
        // Admin
        if(empty($arg5)) {
            $comment->SuView();
        }
        else {
            $comment->SuView($arg5[0], $arg5[1]);
        }
    }
    elseif(mb_strtolower($message) == 'y') {
        // View only, Idea from anpera.NET, thanks Leen
        $comment->View(false);
    }
    else {
        // Normal *g*
        $comment->View();
    }
    
    unset($comment);
}

?> 