
<?php
/**
 * Commentary: A Class which provides the rp-chat in logd
 * 
 * @author        Basilius Sauter
 * @copyright    2006-2009
 * @version        2.9
 * @package        commentary
 */

if(!defined('NOBIO')) {
    /**
     * Hack, um den Biograpie-Link zu unterdrücken
     * @const NOBIO
     */
    define('NOBIO',false);
}

/**
 * Kompabilitätsmodus für alte LoGD's aktivieren. Meint zur Zeit vor allem, alte Wrapper-Funktionen brauchen
 * @const COMMENTARY_USE_COMPABILITY_MODE
 */
define('COMMENTARY_USE_COMPABILITY_MODE', true);

/**
 * Cache-Funktionalität aktivieren
 * @const COMMENTARY_USE_CACHED_QUERIES
 */
define('COMMENTARY_USE_CACHED_QUERIES', false);

/**
 * Angabe der Spaltenzahl für Textareas
 * @const COMMENTARY_TEXTAREA_COLS
 */
define('COMMENTARY_TEXTAREA_COLS', 50);

/**
 * Angabe der Zeilenzahl für Textareas
 * @const COMMENTARY_TEXTAREA_ROWS
 */
define('COMMENTARY_TEXTAREA_ROWS', 3);

/**
 * Fontfamliy-CSS-Attribut für die Textarea
 * @const COMMENTARY_TEXTAREA_FONTFAMILY
 */
define('COMMENTARY_TEXTAREA_FONTFAMILY', 'sans');

/**
 * Anzahl übrig bleibender Zeichen anzeigeh?
 * @const COMMENTARY_TEXTAREA_SHOWCHARS
 */
define('COMMENTARY_TEXTAREA_SHOWCHARS', false);

/**
 * Grösse des Input-Feldes für die Zeichenanzeige in Zeichen
 * @const COMMENTARY_SHOWCHARS_SIZE
 */
define('COMMENTARY_SHOWCHARS_SIZE', 7);

/**
 * Grösse des Input-Feldes für den Chatpost in Zeichen
 * @const COMMENTARY_INPUTFIELD_SIZE
 */
define('COMMENTARY_INPUTFIELD_SIZE', 50);

/**
 * Automatische Textarea
 * @const COMMENTARY_AUTOTEXTAREA
 */
define('COMMENTARY_AUTOTEXTAREA',  true);

/**
 * Textarea an?
 * @const COMMENTARY_ACTIVETEXTAREA
 */
define('COMMENTARY_ACTIVETEXTAREA',  false);


/**
 * Anzahl Zeichen, bei der im Autotextarea-Modus die Textarea angezeigt werden soll
 * @const COMMENTARY_AUTEXTAREA_CHARS
 */
define('COMMENTARY_AUTEXTAREA_CHARS',  200);

/**
 * Standardlimite für angezeigte Posts
 * @const COMMENTARY_DEFAULTLIMIT
 */
define('COMMENTARY_DEFAULTLIMIT', 7);

/**
 * Standardsprechprefix ($user >>sagt<<: "")
 * @const COMMENTARY_DEFAULTTALKLINE
 */
define('COMMENTARY_DEFAULTTALKLINE', 'sagt');

/**
 * Standardüberschrift für den Chat
 * @const COMMENTARY_DEFAULTMESSAGE
 */
define('COMMENTARY_DEFAULTMESSAGE', 'Spiele hier Rollenspiel:'); # Standardüberschrift für den Chat

/**
 * Maximale Zeichenanzahl je Post
 * @const COMMENTARY_MAXLENGHT
 */
define('COMMENTARY_MAXLENGHT', 2000);

/**
 * Paragraphen-Tags verwenden?
 * @const COMMENTARY_USEPARAGRAPHS
 */
define('COMMENTARY_USEPARAGRAPHS', true);

/**
 * Absätze in Chats erlauben (\n)
 * @const COMMENTARY_INPUTFIELD_SIZE
 */
define('COMMENTARY_ALLOWPARAGRAPHS', true);

/**
 * Markieren von Absätzen mithilfe eines Extra-Charakters erlauben (für Einzeilfelder..)
 * @const COMMENTARY_MANUALPARAGRAPHCHAR
 * @todo Auch verwenden, haha..
 */
define('COMMENTARY_MANUALPARAGRAPHCHAR', '\n');

/**
 * Zwischenabstände zwischen einzelnen Posts, wenn Paragraphen benutzt werden
 * @const COMMENTARY_PARAGRAPHS_MARGIN
 */
define('COMMENTARY_PARAGRAPHS_MARGIN', 1);

/**
 * Zeilenhöhe für die Posts
 * @const COMMENTARY_LINEHEIGHT
 */
define('COMMENTARY_LINEHEIGHT', 1.15);

/**
 * Ist der Farbmod («Farben-in-der-Datenbank») installiert?
 * @const COMMENTARY_FARBHACK_IS_INSTALLED
 */
define('COMMENTARY_FARBHACK_IS_INSTALLED', false);

/**
 * Anzeigetyp für die Navigation: anchor, button, fakebutton
 * @const COMMENTARY_NAVIGATION_DISPLAYTYPE
 */
define('COMMENTARY_NAVIGATION_DISPLAYTYPE', 'anchor');

/**
 * Navigationsanzeige: oben (top), unten (bottom) oder an beiden Orten (both)?
 * @const COMMENTARY_NAVIGATION_POSITION
 */
define('COMMENTARY_NAVIGATION_POSITION', 'bottom');

/**
 * Navigation: Verwende "Letzte Kommentare" und "Neuste"
 * @const COMMENTARY_NAVIGATION_USE_SKIPLINKS
 */
define('COMMENTARY_NAVIGATION_USE_SKIPLINKS', true);

/**
 * Exportieren von geposteten Kommentaren erlauben?
 * @const COMMENTARY_GUILDTAG_DISPLAY
 */
define('COMMENTARY_ALLOW_EXPORT', false);

/**
 * RP-Speichern-Link-Position: oben (top), unten (bottom) oder an beiden Orten (both)?
 * @const COMMENTARY_EXPORTLINK_POSITION
 */
define('COMMENTARY_EXPORTLINK_POSITION', 'bottom');

/**
 * Verwendete, typographisch-korrekte Anführungs- und Schlusszeichen.
 *     english => Anführungszeichen oben und oben
 *  german => Anführungszeichen unten und oben
 *     french => Guillemets mit Leerschlag vor und nachher
 *     swiss-guillemets => Guillemets offen-zu (Without spaces!)
 *  german-guillemets => Guillemets zu-offen (Without spaces!)
 * @const COMMENTARY_QUOTATION_TYPE 
 */
define('COMMENTARY_QUOTATION_TYPE', 'swiss-guillemets');

/**
 * Chatvorschau verwenden?
 * @const COMMENTARY_USE_CHATPREVIEW
 */
define('COMMENTARY_USE_CHATPREVIEW', true);

/**
 * Zeit anzeigen?
 * @const COMMENTARY_TIMESTAMP_DISPLAY
 */
define('COMMENTARY_TIMESTAMP_DISPLAY', true);

/**
 * Zeitanzeigetyp
 * @const COMMENTARY_TIMESTAMP_TYPE
 */
define('COMMENTARY_TIMESTAMP_TYPE', '');

/**
 * Zeitanzeigeformat (Identisch zu date())?
 * @const COMMENTARY_TIMESTAMP_FORMAT
 */
define('COMMENTARY_TIMESTAMP_FORMAT', 'G:i');

/**
 * Gildentag-Version (dashguild, eliguild, eliguildv2)
 * @const COMMENTARY_GUILDTAG_VERSION
 */
define('COMMENTARY_GUILDTAG_VERSION', '');

/**
 * Gildentags anzeigen?
 * @const COMMENTARY_GUILDTAG_DISPLAY
 */
define('COMMENTARY_GUILDTAG_DISPLAY', false);

/**
 * Template für den HTML-Link zur Biographie, {LOGIN}, {REQUESTURI}, {ACCTID}, {NAME}, {POPUP}
 * @const COMMENTARY_BIO_LINKTEMPLATE
 */
define('COMMENTARY_BIO_LINKTEMPLATE', "`0<a href=\"bio.php?char={LOGIN}&ret={REQUESTURI}\" style=\"text-decoration: none\">\r\n`Ž{NAME}`0</a>\r\n"); # Der Link mit Name (Anzeige) für die Bio. Geparst wird: {LOGIN}, {REQUESTURI}, {ACCTID}, {NAME}, {POPUP}

/**
 * Template für den Link selbst (für die Whitelist), geparst wird {LOGIN}, {REQUESTURI}, {ACCTID}
 * @const COMMENTARY_BIO_LINK
 */
define('COMMENTARY_BIO_LINK', 'bio.php?char={LOGIN}&ret={REQUESTURI}'); # Der reine Link (Whiteliste der Navigation) für die Bio. Geparst wird: {LOGIN}, {REQUESTURI}, {ACCTID}

/**
 * Level, das für das "Live-Löschen" gebraucht wird (Löschlink vor jedem Kommentar, egal ob eigen oder nicht)
 * @const COMMENTARY_LIVEDELETING_SULEVEL
 */
define('COMMENTARY_LIVEDELETING_SULEVEL', 3);

/**
 * Zielort für das Livedeleting
 * @const COMMENTARY_LIVEDELETING_DELETETARGET
 */
define('COMMENTARY_LIVEDELETING_DELETETARGET', 'superuser.php?op=commentdelete');

/**
 * Für Admins den Schreiber eines /X-Emotes entlarven?
 * @const COMMENTARY_DISPLAYEMOTLERNAME
 */
define('COMMENTARY_DISPLAYEMOTLERNAME', true);

/**
 * Mindest-Sulevel, das gebraucht wird, um diese Anzeige sehen zu können
 * @const COMMENTARY_DISPLAYEMOTLERNAME_SULEVEL
 */
define('COMMENTARY_DISPLAYEMOTLERNAME_SULEVEL', 2); # 

/**
 * Verwende RP-Punkte-Verteilung. False für keine-Verwendung, ansonsten einer der folgenden Werte, je nach RP-Punkte-Verteilungssystem:
 *     engelsreich => Für Engelsreich-RP-Punkte,
 *  luzifel => Für Luzifels System,
 *  wintertal => Für das Wintertal-System,
 *  silienta => Für das Silienta-System
 * @const COMMENTARY_USE_RPPOINTS
 */
define('COMMENTARY_USE_RPPOINTS', false);

/**
 * Verwende RP-CMD. false für keine-Verwendung, aonsten einer der folgenden Werte, je nach gewünschtem System:
 *  meteora => Meteoras RPCMD-System
 * @const COMMENTARY_USE_RPCMD
 */
define('COMMENTARY_USE_RPCMD', false);

/**
 * Mindest-Userlevel um Admin-RPCMD's verwenden zu können
 * @const COMMENTARY_RPCMD_SULEVEL
 */
define('COMMENTARY_RPCMD_SULEVEL', 3);

# Wenn Farbhack installiert ist, Farben vom Farbhack holen.
if(COMMENTARY_FARBHACK_IS_INSTALLED === true) {
    $var = $appoencode_str;
}
# Wenn nicht, Standardfarben nehmen
else {
    $var = '123456789!@#$%&QqRr*~^?VvGgTtAa';
}

/**
 * Mindest-Sulevel, das gebraucht wird, um diese Anzeige sehen zu können
 * @const COMMENTARY_DISPLAYEMOTLERNAME_SULEVEL
 */
define('COMMENTARY_ALLOWEDTAGS', $var);


// Support für wz_tooltip,  ab Version 4.12 garantiert
if(COMMENTARY_TIMESTAMP_TYPE == 'wz_tooltip') {
    #define('COMMENTARY_TOOLTIP_PATH', getsetting('COMMENTARY_TOOLTIP_PATH', 'wz_tooltip.js'));
}

if(!defined('ER_NAV_EXTRA')) {
    define('ER_NAV_EXTRA', $session['counter']."-".date("His"));
}
?>


