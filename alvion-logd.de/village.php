
<?phprequire_once "common.php";addcommentary();checkday();///AB IN DIE SCHULE///if ($session['user']['schoolprocess']<4){    redirect("school.php");}if ($session['user']['alive']){ }else{    redirect("shades.php");}if($session['user']['prison']==1){    redirect("kerker.php");}if($session['user']['einzelhaft']==1){    redirect("kerker.php");}if($session['user']['charm']<0){    $session['user']['charm']=0;}$session['user']['whereuser']=0;$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";$result = db_query($sql);$row = db_fetch_assoc($result);if(($row['acctid1']==$session['user']['acctid'] && $row['turn']==1) || ($row['acctid2']==$session['user']['acctid'] && $row['turn']==2)){    redirect("pvparena.php");}if (getsetting("automaster",1) && $session['user']['seenmaster']!=2 && $session['user']['rp_only']==0){    //masters hunt down truant students    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);    while (list($key,$val)=each($exparray)){        $exparray[$key]= round(            $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100            ,0);    }    $expreqd=$exparray[$session['user']['level']+1];    if ($session['user']['experience']>$expreqd && $session['user']['level']<15){        redirect("train.php?op=autochallenge");    }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){        redirect("dragon.php?op=autochallenge");    }}$session['user']['specialinc']="";$session['user']['specialmisc']="";addnav("a?`^Gesetzestafeln","rules.php");addnav("e?Wegweiser","wegweiser.php");addnav("Die Umgebung");addnav("Düsterer Wald","forest.php");addnav("Waldlichtung","lichtung.php");addnav("Die Sümpfe Alvions","suempfe.php");addnav("Die Felder","felder.php");addnav("Waldsiedlung","waldsiedlung.php");addnav("Waldsee","waldsee.php");addnav("Das Dorf Alvion");addnav("Dorfsiedlung","wohnviertel.php");addnav("Rathausplatz","rathausplatz.php");addnav("Vergnügungsviertel","vergnueviertel.php");addnav("Träumergasse","traumer.php");addnav("Marktplatz","marktplatz.php");if (getsetting('weihnacht',0)){    $turnsperday=0;    $datum = getsetting('weihnacht','01-01');    $adventtag = explode('-',$datum);    if (($adventtag[1] <= 26 && $adventtag[1] > 0) || ($adventtag[0] == 11 && $adventtag[1] >=30))    addnav('Weihnachtsmarkt','weihnachtsmarkt.php');}elseif (getsetting('samhain',0)) {    addnav('`òS`4a`$m`Qh`$a`$i`òn','samhain.php');}addnav("Versteckte Gasse","kunstplatz.php");addnav("Gildenstraße","gildenstrasse.php");// if (($session['user']['orden']>=10 || $session['user']['superuser']>=3) && !$session['user']['rp_only']) addnav("Olymp","olymp.php");//Gottheiten-Addon//if ($session['user']['superuser']>2){//  addnav("`bNeuen Gott wählen`b","gottwahl.php");//}addnav("p?Tempel der Toleranz","goettertempel.php");addnav("`bSonstiges`b");$datum = getsetting('weihnacht','0');if($datum>0){    $adventtag = explode('-',$datum);    if ($adventtag[1] <= 24 && $adventtag[1] > 0){        addnav("`^Adventskalender","adventskalender.php");    }}addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);addnav("N?Tägliche News","news.php");// addnav("Ringtafel","ringtafel.php");addnav("&?Profil & Inventar","prefs.php");addnav("Kämpferliste","list.php");addnav("`4Übungsraum","uebungsraum.php");if ($session['user']['superuser']>=2){  addnav("X?`bKuschelecke`b","superuser.php");  if (@file_exists("test.php")) addnav("Test","test.php");}//let users try to cheat, we protect against this and will know if they try.addnav("","superuser.php");addnav("","user.php");addnav("","taunt.php");addnav("","creatures.php");addnav("","configuration.php");addnav("","badword.php");addnav("","armoreditor.php");addnav("","bios.php");addnav("","badword.php");addnav("","donators.php");addnav("","referers.php");addnav("","retitle.php");addnav("","stats.php");addnav("","viewpetition.php");addnav("","weaponeditor.php");if ($session['user']['superuser']>1){  addnav("(?()Neuer Tag","newday.php");}page_header("Dorfplatz");$session['user']['standort']="Dorfplatz";//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);$team="`c`b`$ Ansprechpersonen bei Fragen oder Problemen sind:`n`n";$sql="SELECT name,loggedin,login,laston,stealth FROM accounts WHERE superuser>2 AND superuser<6 ORDER BY login ASC";$result = db_query($sql) or die(db_error(LINK));$j=db_num_rows($result);for ($i=0;$i<$j;$i++){    $row = db_fetch_assoc($result);    $loggedin=(date("U") - strtotime($row['laston']) <    getsetting("LOGINTIMEOUT",900) && $row['loggedin']);    $team.="`0<a href='mail.php?op=write&to=".rawurlencode($row['login'])."' target='_blank' onClick=\"".popup('mail.php?op=write&to='.rawurlencode($row['login'])).";return false;\">"    ."`7".$row['login']."`0</a>:&nbsp;".((!$row['stealth']&&$loggedin)?"`#On":"`4Off")."";    if($i<$j-1) $team.="`0, ";    if($i==2) $team.="`n";}$team.="`c`b`n";output($team,true);output("`@`c`b`pD`*o`2r`of`@p`ol`2a`*t`pz`b`c`n`pSanft`* stre`2icht `oder W`@ind ü`gber d`@ie Wi`opfel `2der B`*äume,`p die `*wie e`2in ur`oalter`@ Schu`gtzwal`@l rin`ogs um`2 das `*Dorf `pherum`* lieg`2en un`od sel`@bst v`gon hi`@er au`os deu`2tlich`* über`p die `*Däche`2r der`o Häus`@er er`gkennb`@ar si`ond. `nB`2einah`*e mei`pnt ma`*n das`2 Flüs`otern `@der W`gälder`@ zu v`oerneh`2men, `*die v`pon Ja`*hrhun`2derte`o alte`@n Ges`gchich`@ten er`ozähle`2n und`* nich`pt nur`* den `2Tiere`on Zuf`@lucht`g biet`@en, s`oonder`2n auch`* jene`pn Wes`*en, d`2ie si`och hi`@er an`g dies`@em ru`ohigen `2Ort n`*ieder`p gela`*ssen `2haben`o. `nGesc`@häfti`gg geh`@t es `oumher`2, doc`*h ver`pweilt`* auch`2 hier`o und `@da de`gr ein`@ oder`o ande`2re au`*f ein`per de`*r Bän`2ke, d`oie de`@n Sta`gmm ei`@nes m`oächti`2gen B`*aumes`p umsä`*umen `2und l`oegt d`@ie Ar`gbeit `@niede`or, um `2einfa`*ch ei`pn wen`*ig de`2m Zwi`otsche`@rn de`gr Vög`@el od`oer de`2m Rau`*schen`p des `*Winde`2s zu `olausc`@hen, b`gevor `@er wi`oeder `2in ir`*gende`piner `*Gasse`2 vers`ochwin`@det u`gm wei`@ter s`oeinem`2 Tage`*werk `pnach `*zu ge`2hen. ");$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";$result = db_query($sql) or die(db_error(LINK));$row = db_fetch_assoc($result);output("`n`oSogar`@ ein `gklein`@er Ju`onge s`2itzt `*auf e`pinem `*Felse`2n um `odie n`@euste`gn Nac`@hrich`oten z`2u ver`*künde`pn: `n`n`c$row[newstext]`c`n");//gesehen auf www.elfenherz.de angepasst für Silienta  von Rikkarda@silienta-logd.de/*herzlichen Dank an BlackFin von Elfenherz für dieses hervorragende RP-Tool*/$thistime=date("H:i",gametime());//$thistime = date("H:i") ;$thistime_exploded = explode(":",$thistime) ;$thishour = (int)$thistime_exploded[0] ;$thisminute_exploded = explode(" ",$thistime_exploded[1]);// $this_time = $thisminute_exploded[1];if($thishour < 7  ||$thishour >= 19   ) {         $coloredtime = '`9' ;         $daytide = "`9Nacht`@" ;         $thisweather = getsetting('weather_night','0') ;}else{          $coloredtime = '`^' ;          $daytide = "`^Tag`@" ;          $thisweather = $settings['weather'] ;}// if (getsetting('activategamedate','0')==1) output("`tWir schreiben den `^".getgamedate()."`t im Zeitalter des Drachen. ");output("`t`cDie Uhr am Rathaus zeigt $coloredtime".$thistime."`t Uhr. Es ist $daytide.`n");output(" `tDas derzeitige Wetter: `^ $thisweather`@.`c`n");if((getsetting('pudel_active','0')==1) && (getsetting('pudel','0')!='0') ){    $thispudel=getsetting('pudel','0');    $sql="SELECT name, sex From accounts WHERE acctid='".$thispudel."'";    $result = db_query($sql);    $pudel = db_fetch_assoc($result);    output("`t`cEine einzelne Regenwolke verfolgt hartnäckig`& ".$pudel['name']."`t. Aus der Wolke giesst es in Strömen auf ".($pudel['sex']?"sie":"ihn")." hinab.`@`c");}//output("`n".$thistime."`n".$thishour."`n".getsetting('moonphase',1)."`n");output("`n");if($thishour < 7  || $thishour >= 19 ) {    $this_moonphase = getsetting('moonphase',1) ;    if($this_moonphase < 10) $moonstring = "zunehmend" ;        elseif($this_moonphase == 10) {        $moonstring = "Vollmond" ;    }else {        $moonstring = "abnehmend" ;    }    output("`c`vHeutige Mondphase: `n",true) ;    $this_phasepicture = "images/moonphase/gifs/phase".$this_moonphase.".gif" ;    output("<img src=\"$this_phasepicture\"></img>`n`&`i$moonstring`i`c",true);}output("`n");output("`n`n`%`@In der Nähe reden einige Dorfbewohner:`n");viewcommentary("village","Hinzufügen",25,"sagt",1,1);if (!$session['user']['prefs']['keinot']){    if ($session['user']['ot_denied']){        output("`n`n`n`4Es ist dir nicht erlaubt im Off-Topic zu posten!`n`n");        viewcommentary("offtopic","hier labern",15,"sagt",0,0);    }else{        output("`n`n`n`b`4Off-Topic:`b`n");        viewcommentary("offtopic","hier labern",15);    }}page_footer();
