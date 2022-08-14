
ï»¿<?php



// 20140816



require_once("common.php");

isnewday(3);



if ($_GET['op']=="save"){

    if ($_POST['requirevalidemail']==1) $_POST['requireemail']=1;

    reset($_POST);

    while (list($key,$val)=each($_POST)){

        savesetting($key,"$val");

        output("Setze $key auf ".htmlentities(stripslashes($val))."`n");

    }

}



page_header("Spieleinstellungen");

addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

addnav("W?ZurÃ¼ck zum Weltlichen","village.php");

addnav("",$REQUEST_URI);



$time = gametime();

$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));

$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));

$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);

$secstotomorrow = $tomorrow-$time;

$secssofartoday = $time - $today;

$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4),0);

$realsecssofartoday = round($secssofartoday / getsetting("daysperday",4),0);



$enum="enum";

for ($i=0;$i<=86400;$i+=900){

    $enum.=",$i,".((int)($i/60/60)).":".($i/60 %60)."";

}

$setup = array(

    "Spieleinstellungen,title",

    "loginbanner"=>"Login Banner (unterhalb der Login-Aufforderung; 255 Zeichen)",

    "blocknewchar"=>"Neuanmeldungen sperren?,bool",

    "impressum"=>"Server betrieben von: (255 Zeichen)",

    "outputcompression"=>"Verwende Software-Ausgabekomprimierung,bool",

    "refererlog"=>"HerfÃ¼hrende Seiten protokollieren?,bool",

    "soap"=>"UserbeitrÃ¤ge sÃ¤ubern (filtert Gossensprache und trennt WÃ¶rter mit Ã¼ber 45 Zeichen),bool",

    "postcount"=>"Spieler-Kommentare pro Tag und Region begrenzen?,bool",

    "maxonline"=>"Maximal gleichzeitig online (0 fÃ¼r unbegrenzt),int",

    "maxcolors"=>"Maximale # erlaubter Farbwechsel in Userkommentaren,int",

    "gameadminemail"=>"Admin Email",

    "paypalemail"=>"E-Mail Adresse fÃ¼r den PayPal Account des Admins",

    "currency"=>"PayPal WÃ¤hrung,enum,0,US$,1,â‚¬",

    //"defaultlanguage"=>"Voreingestellte Sprache (z. Zt nur de),enum,en,English,dk,Danish,de,Deutsch,es,Espanol,fr,French",

    "timezone"=>"Zeitzone,enum,0,GMT,1,CET (MEZ)",

    "forum"=>"Link (URL) zum Forum",

    "automaster"=>"Meister jagt sÃ¤umige Lehrlinge,bool",

    //"multimaster"=>"Meister kann mehrmals pro Tag herausgefordert werden?,bool",

    "libdp"=>"Donationpoints fÃ¼r neue BÃ¼cher,int",

    "topwebid"=>"ID fÃ¼r Top Web Games (wenn du dort registriert bist),int",

    "beta"=>"Beta-Features fÃ¼r alle Spieler aktivieren?,bool",

    "limithp"=>"Lebenpunkte maximal Level*12+5*DPinHP+x*DK (0=deaktiviert),int",

    "autofight"=>"Automatische Kampfrunden ermÃ¶glichen,bool",



    "Account-Erstellung,title",

    "superuser"=>"Default superuser level,enum,0,Standard play days per calendar day,1,Unlimited play days per calendar day,2,Admin creatures and taunts,3,Admin users",

    "newplayerstartgold"=>"Gold mit dem ein neuer Char startet,int",

    "requireemail"=>"E-Mail Adresse beim Anmelden verlangen,bool",

    "requirevalidemail"=>"E-Mail Adresse bestÃ¤tigen lassen,bool",

    "blockdupeemail"=>"Nur ein Account pro E-Mail Adresse,bool",

    "spaceinname"=>"Erlaube Leerzeichen in Benutzernamen,bool",

    "selfdelete"=>"Erlaube den Spielern ihren Charakter zu lÃ¶schen,bool",

    "avatare"=>"Erlaube den Spielern Avatare zu verlinken,bool",



    "Neue Tage,title",

    "fightsforinterest"=>"HÃ¶chste Anzahl an Ã¼brigen WaldkÃ¤mpfen um Zinsen zu bekommen,int",

    "maxinterest"=>"Maximaler Zinssatz (%),int",

    "mininterest"=>"Minimaler Zinssatz (%),int",

    "daysperday"=>"Spieltage pro Kalendertag,int",

    "dispnextday"=>"Zeit zum nÃ¤chsten Tag in Vital Info,bool",

    "specialtybonus"=>"ZusÃ¤tzliche EinsÃ¤tze der Spezialfertigkeit am Tag,int",

    //"activategamedate"=>"Spieldatum aktiv,bool",

    "gamedateformat"=>"Datumsformat (zusammengesetzt aus: %Y; %y; %m; %n; %d; %j)",

    "gametimeformat"=>"Zeitformat",



    "Wald,title",

    "turns"=>"WaldkÃ¤mpfe pro Tag,int",

    "dropmingold"=>"Waldkreaturen lassen mindestens 1/4 des mÃ¶glichen Goldes fallen,bool",

    "lowslumlevel"=>"Mindestlevel bei dem perfekte KÃ¤mpfe eine Extrarunde geben,int",



    "Kopfgeld,title",

    "bountymin"=>"Mindestbetrag pro Level der Zielperson,int",

    "bountymax"=>"Maximalbetrag pro Level der Zielperson,int",

    "bountylevel"=>"Mindestlevel um Opfer sein zu kÃ¶nnen,int",

    "bountyfee"=>"GebÃ¼hr fÃ¼r Dag Durnick in Prozent,int",

    "maxbounties"=>"Anzahl an Kopfgeldern die ein Spieler pro Tag aussetzen darf,int",



    "Handelseinstellungen,title",

    "borrowperlevel"=>"Maximalwert den ein Spieler pro Level leihen kann (Bank),int",

    "maxinbank"=>"+/- Maximalbetrag fÃ¼r den noch Zinsen bezahlt/verlangt werden,int",

    "allowgoldtransfer"=>"Erlaube Ãœberweisungen (Gold und Edelsteine),bool",

    "transferperlevel"=>"Maximalwert den ein Spieler pro Level empfangen oder nehmen kann,int",

    "transferreceive"=>"Anzahl an Ãœberweisungen die ein Spieler pro Tag empfangen kann,int",

    "mintransferlev"=>"Mindestlevel fÃ¼r Ãœberweisungen (bei 0 DKs),int",

    "maxtransferout"=>"Menge die ein Spieler an andere Ã¼berweisen kann (Wert x Level),int",

    "innfee"=>"GebÃ¼hr fÃ¼r Expressbezahlung in der Kneipe (x oder x%),int",

    "selledgems"=>"Edelsteine die Vessa vorrÃ¤tig hat,int",

    "usedspells"=>"Weiterverkauf von gebrauchten Zaubern zulassen?,bool",

    "vendor"=>"HÃ¤ndler heute in der Stadt?,bool",



    "Besonderes/Sonstiges,title",

    "witchvisits"=>"Erlaubte Besuche bei der Hexe,int",

    "dailyspecial"=>"Heutiges besonderes Ereignis",

    "paidales"=>"Ale das als 'Runde' spendiert wurde (Wert-1),int",

    "maxales"=>"Maximale Anzahl Ale die bei einer 'Runde' spendiert werden kann,int",

    "paidgold"=>"Gold das in Bettlergasse spendiert wurde,int",

    "cakevip"=>"Login-Name des Spielers der mit Torte beworfen werden kann",

    "angreiferzahl"=>"Anzahl Angreifer,int",

    "angreifername"=>"Name der Angreifer",

    "angreiferwaffe"=>"Waffenname der Angreifer",

    "angreiferzufall"=>"Angriffe zufÃ¤llig automatisch starten?,bool",



    "Mail Einstellungen,title",

    "mailsizelimit"=>"Maximale Anzahl an Zeichen in einer Nachricht,int",

    "inboxlimit"=>"Anzahl an Nachrichten in der Inbox,int",

    "oldmail"=>"Alte Nachrichten automatisch lÃ¶schen nach x Tagen. x =,int",



    "PvP,title",

    "pvp"=>"Spieler gegen Spieler aktivieren,bool",

    "pvpday"=>"SpielerkÃ¤mpfe pro Tag,int",

    "hasegg"=>"Aktueller Besitzer des goldene Eis (Account-ID - 0=Niemand),int",

    "pvpimmunity"=>"Tage die neue Spieler vor PvP sicher sind,int",

    "pvpminexp"=>"Mindest-Erfahrungspunkte fÃ¼r PvP-Opfer,int",

    "pvpattgain"=>"Prozentsatz der Erfahrung des Opfers den der Angreifer bei Sieg bekommt,int",

    "pvpattlose"=>"Prozentsatz an Erfahrung den der Angreifer bei Niederlage verliert,int",

    "pvpdefgain"=>"Prozentsatz an Erfahrung des Angreifers den der Verteiger bei einem Sieg gewinnt,int",

    "pvpdeflose"=>"Prozentsatz an Erfahrung den der Verteidiger bei Niederlage verliert,int",

    "pvpmindkxploss"=>"DKs Unterschied zwischen TÃ¤ter und Opfer bis zu dem noch 0% XP abgezogen werden,int",



    "Inhalte lÃ¶schen (0 fÃ¼r nie lÃ¶schen),title",

    "expirecontent"=>"Tage die Kommentare und News aufgehoben werden,int",

    "expiretrashacct"=>"Tage die Accounts gespeichert werden die nie eingeloggt waren,int",

    "expirenewacct"=>"Tage die Level 1 Accounts ohne Drachenkill aufgehoben werden,int",

    "expireoldacct"=>"Tage die alle anderen Accounts aufgehoben werden,int",

    "LOGINTIMEOUT"=>"Sekunden InaktivitÃ¤t bis zum automatischen Logout,int",



    "NÃ¼tzliche Informationen,title",

    "weather"=>"Heutiges Wetter:,viewonly",

    "kleineswesen"=>"Kleines Wesen (default Violet):",

    "Letzter neuer Tag: ".date("H:i:s",strtotime(date("Y-m-d H:i:s")." -$realsecssofartoday seconds")).",viewonly",

    "NÃ¤chster neuer Tag: ".date("H:i:s",strtotime(date("Y-m-d H:i:s")." +$realsecstotomorrow seconds")).",viewonly",

    "Aktuelle Spielzeit: ".getgametime().",viewonly",

    "TageslÃ¤nge: ".($dayduration/60/60)." Stunden,viewonly",

    "Aktuelle Serveruhrzeit: ".date("d.m.Y H:i:s").",viewonly",

    "gameoffsetseconds"=>"Offset der Spieltage,$enum",

    "gamedate"=>"aktuelles Spieldatum (Y-m-d)",



    "LoGD-Netz Einstellungen (file wrappers mÃ¼ssen in der PHP Konfiguration aktiviert sein!!),title",

    "logdnet"=>"Beim LoGD-Netz eintragen?,bool",

    "serverurl"=>"Server URL",

    "serverdesc"=>"Serverbeschreibung (255 Zeichen)",

    "logdnetserver"=>"LoGD-Netz Zentralserver (Default: http://logdnet.logd.com/)",



    "Spieleinstellungen Ende,title"

    );



if ($_GET['op']==""){

    loadsettings();

    output("<form action='configuration.php?op=save' method='POST'>",true);

    addnav("","configuration.php?op=save");

    showform($setup,$settings);

    output("</form>",true);

}

page_footer();

?>

