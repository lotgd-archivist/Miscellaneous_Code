
<?php/***********************************                                 ** Der Steintroll (steintroll.php) **          Idee: Veskara          **     Programmierung: Linus       **    für alvion-logd.de/logd      **           2007/2008             **                                 ***********************************//*Bitte die Einbauhinweise in der pilzsuche.php beachten!*/require_once "common.php";require_once "func/systemmail.php";global $session;define ('MAX_MOEBEL', getsetting("maxmoebel",500));if ((int)getsetting("steintroll",0)==false){    $steintroll=false;    page_header("Der Grasdrache");} else {    $steintroll=true;    page_header("Der Steintroll");}switch($_GET['op']) {    case "":        if($steintroll){            // Hier der Begrüssungstext            $out=" `c `b`àDe`èr St`7ein`ètro`àll`b `c `n `n`àDu lats`èchst mal kräftig g`7egen den großen St`èein, was du noch i`àm selben Moment bereust. Laut f`èluchend hüpfst du auf e`7inem Bein herum und hä`èltst den schmerze`ànden Fuß mit deinen Händen fest.  ";            if($session['user']['level']>1){                $out.="Plö`ètzlich beginnt der S`7tein sich zu bewege`èn und in einen kle`àinen Troll zu verwandeln, de`èr dich mit böse funkeln`7den Augen ansta`èrrt. ";                $out.="`&Was fällt dich ein? `èschreit er dich an `&Erst tust du mich treten und jetzt tust du machen grosses Geschrei!`n";                $out.="`èDas bö`àse Funkeln in de`7n Augen des Ste`èintrolls wande`àlt sich zu ein`èem gierigen Gl`7anz `&Tust du Kristalle für mich haben? Gib mich Kristalle ich hab ein paar scheene Dinge dafür.`n`n ";                $out.="`c<img src='http://www.alvion-logd.de/logd/images/stroll.gif'>`c`n`n";            }        } else {            $out=" `c `b `²De`2r G`or`@as`odr`2ac`²he`b `c `n`n`²Du latsc`2hst mal kräft`oig gegen den `@großen Stein, was d`ou noch im selb`2en Moment bere`²ust. Laut fluche`2nd hüpfst du a`ouf einem Bein h`@erum und hä`oltst den schmerze`2nden Fuß mit dein`²en Händen fest.  ";            if($session['user']['level']>1){                $out.="`2Plötzlich ersche`oint hinter de`@m Stein ein klein`oer, seltsam auss`2ehender Dra`²che und schaut dich be`2lustigt schmun`ozelnd an. ";                $out.="`EWas machst du hier für einen Alarm? `owill er vo`@n dir wis`osen `EHat deine Mammi dir dein Spielzeug genommen, oder was soll der Krach?`n";                $out.="`2Dann mustert d`²er kleine Drac`2he aufmerks`oam den Beutel w`@elcher an deiner Se`oite baumelt `EHast du Kristalle für mich? Ich gebe dir ein paar schöne Dinge dafür.`n`n";                $out.="`c<img src='http://www.alvion-logd.de/logd/images/gdrache.gif'>`c`n`n";            }        }        if($session['user']['kristalle']>=10 && $session['user']['level']>1) addnav("Kristalle eintauschen","steintroll.php?op=tausch");        elseif($session['user']['kristalle']<10) $out.=$steintroll?"`àEnttäusc`èht wendet si`7ch der Tro`èll ab `&Nichts für mich haben du tust!`n":"`²Enttäus`2cht schütte`olt der Dra`@che seinen Kopf `ENein, Kristalle hast du nicht! `ound versc`2hwindet zwisc`²hen den Obstbäumen`n";        else $out.="`9Nichts passiert`n";    break;    case "tausch":        // Geschenke-Array in der Folge: name, class, gold, gems, description, hvalue, buff, Kristalle, geschenktes Gold, geschenkte gems, geschenkte cp, geschenkte Gefallen, nur Kämpfer        if($steintroll){            $tausch = array(                1=>array("`KGl`wü`^c`Ok`8sm`Oü`^n`wz`Ke`0","Schmuck","500","0","Trage sie stets bei dir. Sie trägt ihren Namen sicher nicht zu unrecht.",0,0,"100",0,0,3,0,0),                2=>array("`2Mi`ost`@elz`owe`2ig `of`@ü`or `2Ve`or`@lie`ob`2te`0","Geschenk","500","0","Ein Mistelzweig, der die Liebe noch größer werden lässt.",0,0,"100",0,0,3,0,0),                3=>array("`òH`4e`kr`\$zkis`ks`4e`òn`0","Geschenk","500","0","Ein Kissen in Herzform.",0,0,"100",0,0,3,0,0),                4=>array("`tEu`hle`Cns`Bta`Ttue`0","Möbel","1000","0","Eine unheimliche Statue ...sie kann alles und jeden sehen.",0,0,"200",0,0,0,0,0),                5=>array("`jTh`Úor`Ss-Ham`Úm`jer-A`Úm`Sulett`0","Schmuck","1000","0","Ein Amulett welches den Donner ruft, wenn man an ihm reibt.",3,5,"200",0,0,0,0,1),                6=>array("`=Si`7lb`)er`Sne`)s K`7re`=uz`0","Schmuck","1000","0","Ein Anhänger für eine Kette. Es schützt vor Vampiren.",0,0,"200",0,0,0,0,0),                7=>array("`dKr`dis`#tall`Dri`dng`0","Geschenk","1000","0","Ein bezaubernder Ring, für den oder die Liebste gut geeignet.",0,0,"200",0,0,5,0,0),                8=>array("`TR`Bu`Cn`tens`Ct`Bei`Tn`0","Schmuck","1000","0","Ein mystischer Stein, auf dem Druiden geheimnisvolle Symbole verewigten.",0,0,"200",0,0,0,0,0),                9=>array("`&M`=ar`7m`=o`7rs`&pl`7it`=ter`0","Geschenk","1000","1","Ein kostbares Stück Marmor, glänzend und edel.",0,0,"500",0,0,0,0,0),                10=>array("`&Z`=e`7p`)t`àe`ur `òd`4e`òs `uR`àa`)m`7i`=u`&s`0","Geschenk","1000","1","Ramius Zepter. Ob einem damit die Toten gehorchen?",0,0,"500",0,0,0,30,0),                11=>array("`4Ru`\$bi`qn`^ri`Kng`0","Geschenk","2500","1","Ein Ring, welcher mit einem funkelndem roten Rubin geschmückt ist.",0,0,"500",0,0,0,0,0),                12=>array("`&El`=fe`&nb`=ei`&ns`=ku`&lp`=tur`0","Möbel","2500","1","Prächtige Skulptur, für den Kaminsims geeignet.",0,0,"500",0,0,0,0,0),                13=>array("`ÍDi`Éam`Áan`Ytv`=er`&ziertes `8A`Om`íu`íle`^tt`0","Geschenk","1000","1","Reich geschmücktes Amulett mit wertvollen Diamanten.",0,0,"500",0,0,0,0,0),                14=>array("`)S`7i`=l`&berke`=t`7t`)e`0","Geschenk","1500","1","Eine Kette aus reinstem Silber, Werwölfe werden sie lieben.",0,0,"350",0,0,0,0,0)            );        } else {            $tausch = array(                1=>array("`ED`*r`pa`Tc`Bh`Cen`Bl`Te`pd`*e`Er`0","Schmuck","500","0","Gut für schützende Kleidung geeignet.",1,4,"100",0,0,0,0,1),                2=>array("`!R`9e`2g`@e`en`^b`Oo`8g`le`qns`Qta`\$u`4b`0","Geschenk","500","0","Er glitzert schön und ist fein anzusehen.",0,0,"100",0,0,3,0,0),                3=>array("`KS`6t`we`^rn`Oe`8n`=st`&au`=b`0","Geschenk","500","0","Der Sternenstaub lässt dich sehr hübsch aussehen.",0,0,"100",0,0,3,0,0),                4=>array("`pD`*r`Ea`yc`7h`=en`&zahn`0","Schmuck","500","0","Ein echter Zahn eines Drachen, es heißt, man hätte durch ihn magische Fähigkeiten.",1,3,"100",0,0,0,0,1),                5=>array("`&S`8t`Oe`ír`^nschn`íu`Op`8p`&e`0","Geschenk","1000","0","Wünsch Dir was, und es wird erfüllt werden.",0,0,"200",0,0,5,0,0),                6=>array("`2Dr`oac`@he`Gna`On`íhä`^nger`0","Schmuck","1000","0","Ein Anhänger, der es in sich hat, doch was ist seine eigene Magie?",0,0,"200",0,0,0,0,0),                7=>array("`!En`9de `2de`@s R`^ege`qnb`\$og`4ens`0","Geschenk","1000","0","Eine kleine Skulptur, die einen Teil des Regenbogens zeigt.",0,0,"200",1000,3,0,0,0,0),                8=>array("`ÁSe`Éel`Íe`7n`=st`&ein`0","Schmuck","1000","0","Hunderte von Seelen wohnen in ihm, die dich nun beschützen.",3,2,"200",0,0,0,0,1),                9=>array("`pD`*ra`Ech`yen`7st`=e`&in`0","Möbel","1000","0","Ein Stein der Freude bringt...er wechselt regelmäßig seine Farbe.",0,0,"200",0,0,0,0,0),                10=>array("`pD`*ra`Ech`yenam`Eul`*e`ptt`0","Schmuck","1000","0","Ein Schutzamulett, welches fast unzerstörbar ist.",5,1,"200",0,0,0,0,1),                11=>array("`KG`wo`^ld`íe`Oner S`ít`^e`wr`Kn`0","Geschenk","1000","1","Ein Goldener Stern, der wunderbar sanftes Licht ausstrahlt.",0,0,"500",0,0,0,0,0),                12=>array("`&S`=e`Íe`Éle`Ánsp`Éli`Ítt`=e`&r`0","Geschenk","1000","1","Ein Splitter, der heller leuchtet, als die Sonne.",0,0,"500",0,0,0,0,0),                13=>array("`óG`ío`^ld`we`ín`óe `2Dr`*ac`Ehe`yn`Est`*at`2ue`0","Möbel","2500","1","Eine prachtvolle Statue.",0,0,"500",0,0,0,0,0),                14=>array("`8Ve`Org`íol`Kde`2te `*Drac`2he`Kns`ích`Oup`8pe`0","Möbel","2500","1","Ein seltener Fund, der dem Besitzer Glück bringen soll.",0,0,"500",0,0,0,0,0),                15=>array("`2A`olv`@io`gn`Gk`gri`@st`oa`2ll`0","Möbel","2500","1","Ein glasklarer Kristall, der laut einer Legende Alvion in seinem Inneren abbildet.",0,0,"500",0,0,0,0,0)            );        }        $buffs = array(            1=>array("name" => "Drachenkraft", "roundmsg" => "Die Kraft des Drachen schützt dich.",                    "wearoff" => "Der Drache legt sich schlafen.",                    "rounds" => 40, "defmod" => "1.25", "activate" => "defense"),            2=>array("name" => "Seelenheil", "roundmsg" => "Hunderte von Seelen beschützen dich.",                    "wearoff" => "Das Strahlen des Steins verblasst.", "effectmsg" => "Dein Gegner trifft nur mit halber Kraft.",                    "rounds" => 30, "defmod" => "1.5", "activate" => "defense"),            3=>array("name" => "Urkraft des Drachen", "roundmsg" => "Die Kraft des Drachen stärkt dich.",                    "wearoff" => "Der Drache legt sich schlafen.", "effectmsg" => "Die Urkraft verstärkt deine Schläge.",                    "rounds" => 20, "atkmod" => "1.5", "activate" => "offense"),            4=>array("name" => "Schutz des Drachen", "roundmsg" => "Der Drache schützt dich.",                    "wearoff" => "Der Drache legt sich schlafen.",                    "rounds" => 20, "defmod" => "1.20", "activate" => "defense"),            5=>array("name" => "Thors Hammer", "roundmsg" => "Der Donner verstärkt deine Schläge.",                    "wearoff" => "Der Donner verzieht sich.",                    "rounds" => 30, "atkmod" => "1.50", "activate" => "offense")        );        switch($_GET['act']){            case "":                $out="`nDu holst deinen mit Kristallen prall gefüllten Beutel heraus und zeigst ihn dem ".($steintroll?"Steintroll":"Grasdrachen").". Interessiert betrachtest du die Dinge, welche er gegen deine Kristalle eintauschen möchte.`n`n"; // Blabla ... blablabla .. :D                $out.=""; // noch mehr mehr Blabla? *rofl*                $i=1;                $out.="<table border='0' cellpadding='2' cellspacing='2'><tr class='trhead'><td>`bName`b</td><td>`bTyp`b</td><td>`bKristalle`b</td><td>`bBescheibung`b</td></tr>";                while(!empty($tausch[$i])){                    if($session['user']['rp_only']==0 || $tausch[$i][12]==0 && ($tausch[$i][1]!='Möbel' || $session['user']['house']!=0)){                        $bgcolor=($i%2==1?"trlight":"trdark");                        $out.="<tr class='$bgcolor'><td>".($session['user']['kristalle']>=$tausch[$i][7]?"<a href='steintroll.php?op=tausch&act=kauf&id=$i'>":"")."{$tausch[$i][0]}".($session['user']['kristalle']>=$tausch[$i][7]?"</a>":"")."</td><td>`#{$tausch[$i][1]}`0</td><td>`^{$tausch[$i][7]}`0</td><td>`&{$tausch[$i][4]}`0</td></tr>";                        if($session['user']['kristalle']>=$tausch[$i][7]) addnav("","steintroll.php?op=tausch&act=kauf&id=$i");                    }                    $i++;                }                $out.="</table>";                addnav("`^Gold","steintroll.php?op=gold");                addnav("`%Edelsteine","steintroll.php?op=gems");            break;            case "kauf":                $id=$_GET['id'];                switch($tausch[$id][1]){                    case "Möbel":                        $resultm=db_query("SELECT COUNT(*) AS anzahl FROM `items` where owner=".$session['user']['acctid']." AND value1=".$session['user']['house']);                        $erg=db_fetch_assoc($resultm);                        if ($session[user][housekey]<=0 ){                        output("{$tausch[$id][0]} gefällt dir wirklich gut, aber da du kein eigenes Haus besitzt, kannst du mit Möbeln auch nichts anfangen.");                                            } else if($erg['anzahl']>MAX_MOEBEL){                            output("`n<BIG>`QDu hast bereits mehr als `q".MAX_MOEBEL."`Q Gegenstände im Haus (`q".$erg['anzahl']."`Q). Mehr kannst du nicht einlagern! Du musst erst einige Dinge verkaufen, bevor du neue einlagern kannst.</BIG>",true);                        } else if($erg['anzahl']<MAX_MOEBEL){                            $out="`n`&Du gibst dem ".($steintroll?"Steintroll":"Grasdrachen")." `#{$tausch[$id][7]} Kristalle`& und bekommst dafür {$tausch[$id][0]}`&.`n";                            db_query("INSERT INTO items (name,owner,class,gold,gems,description,value1,hvalue,buff) VALUES ('".$tausch[$id][0]."',".$session['user']['acctid'].",'".$tausch[$id][1]."',".$tausch[$id][2].",".$tausch[$id][3].",'".$tausch[$id][4]."',".$session['user']['house'].",".$tausch[$id][5].",'".$buff."')");                            $session['user']['kristalle']-=$tausch[$_GET['id']][7];                        }                    break;                    case "Schmuck":                    $out="`n`&Du gibst dem ".($steintroll?"Steintroll":"Grasdrachen")." `#{$tausch[$id][7]} Kristalle`& und bekommst dafür {$tausch[$id][0]}`&.`n";                        if($tausch[$id][6]!=0){                            $out.="`&Sofort als du {$tausch[$id][0]}`& anlegst spürst du, dass irgend etwas mit dir passiert, und du bekommst Lust auf einen Kampf!`n";                            $buffid=$tausch[$id][6];                            $buff=serialize($buffs[$buffid]);                            $session[bufflist][$buffs[$buffid][name]]=$buffs[$buffid];                            $buff=serialize($buffs[$buffid]);                        }else{                            $buff="";                        }                        db_query("INSERT INTO items (name,owner,class,gold,gems,description,hvalue,buff) VALUES ('".$tausch[$id][0]."',".$session['user']['acctid'].",'".$tausch[$id][1]."',".$tausch[$id][2].",".$tausch[$id][3].",'".$tausch[$id][4]."',".$tausch[$id][5].",'".$buff."')");                        $session['user']['kristalle']-=$tausch[$_GET['id']][7];                        //Gold zur Zeit nicht implementiert                        //if($tausch[$id][8]>0){                        //}                        //Gems zur Zeit nicht implementiert                        //if($tausch[$id][9]>0){                        //}                        //Charme                        if($tausch[$id][10]>0){                            $session['user']['charm']+=$tausch[$id][10];                            $out.="`nDu merkst sofort, als du das Schmuckstück anlegst, dass du dadurch schöner wirst. (Du erhältst {$tausch[$id][10]} Charmepunkte.)";                        }                        //Gefallen zur Zeit nicht implementiert                        //if($tausch[$id][11]>0){                        //}                    break;                    case "Geschenk":                        if ($_GET['act2']=="send" || $_GET['act2']==""){                            $id=$_GET['id'];                            if (isset($_POST['search']) || $_GET['search']>""){                                if ($_GET['search']>"") $_POST['search']=$_GET['search'];                                $search="%";                                for ($x=0;$x<strlen($_POST['search']);$x++){                                    $search .= substr($_POST['search'],$x,1)."%";                                }                                $search="name LIKE '".$search."' AND ";                                if ($_POST['search']=="weiblich") $search="sex=1 AND ";                                if ($_POST['search']=="männlich") $search="sex=0 AND ";                            }else{                                $search="";                            }                            $ppp=25; // Player Per Page to display                            if (!$_GET[limit]){                                $page=0;                            }else{                                $page=(int)$_GET[limit];                                addnav("Vorherige Seite","steintroll.php?op=tausch&act=kauf&id=$id&act2=send&limit=".($page-1)."&search=$_POST[search]");                            }                            $limit="".($page*$ppp).",".($ppp+1);                            $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' ORDER BY login,level LIMIT $limit";                            $result = db_query($sql);                            if (db_num_rows($result)>$ppp) addnav("Nächste Seite","steintroll.php?op=tausch&act=kauf&id=$id&act2=send&limit=".($page+1)."&search=$_POST[search]");                            $out="`n`rWem willst du {$tausch[$_GET['id']][0]}`r schicken?`n`n";                            $out.="<form action='steintroll.php?op=tausch&act=kauf&id=$id&act2=send' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>";                            addnav("","steintroll.php?op=tausch&act=kauf&id=$id&act2=send");                            $out.="<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>";                            for ($i=0;$i<db_num_rows($result);$i++){                                $row = db_fetch_assoc($result);                                $out.="<tr class='".($i%2?"trlight":"trdark")."'><td><a href='steintroll.php?op=tausch&act=kauf&id=$id&act2=send2&name=".HTMLEntities($row['acctid'])."'>";                                $out.="{$row['name']}</a></td><td>{$row['level']}</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>";                                addnav("","steintroll.php?op=tausch&act=kauf&id=$id&act2=send2&name=".HTMLEntities($row['acctid']));                            }                            $out.="</table>";                            addnav("Zurück","steintroll.php");                        }                        if ($_GET['act2']=="send2"){                        // Geschenke-Array in der Folge: name (0), class (1), gold(2), gems (3), description(4), hvalue(5), buff(6), Kristalle(7), geschenktes Gold(8), geschenkte gems, geschenkte cp, geschenkte Gefallen, nur Kämpfer                            $name=$_GET['name'];                            db_query("INSERT INTO items (name,owner,class,gold,gems,description) VALUES ('".$tausch[$id][0]."',$name,'Geschenk',".$tausch[$id][2].",".$tausch[$id][3].",'`=".$tausch[$id][4]." `=Ein Geschenk von ".mysqli_real_escape_string($mysqli, $session['user']['name'])."`=.')");                            $mailmessage=$session['user']['name'];                            $mailmessage.="`7 hat dir ein Geschenk geschickt. Du öffnest es. Es ist ein/e {$tausch[$id][0]}`7.`n";                            //Gold zur Zeit nicht implementiert                            //if($tausch[$id][8]>0){                            //}                            //Gems zur Zeit nicht implementiert                            //if($tausch[$id][9]>0){                            //}                            //Charme                            if($tausch[$id][10]>0){//                                db_query("UPDATE `accounts` SET charm=charm+".$tausch[$id][10]." WHERE acctid=$name");                                updateuser($name,array('charm'=>"+".$tausch[$id][10]));                                $mailmessage.="`n`7Du spürst sofort, als du das Geschenk auspackst, dass es dich schöner macht. (Du erhältst {$tausch[$id][10]} Charmepunkte.)";                            }                            //Gefallen                            if($tausch[$id][11]>0){//                                db_query("UPDATE `accounts` SET deathpower=deathpower+".$tausch[$id][11]." WHERE acctid=$name");                                updateuser($name,array('deathpower'=>"+".$tausch[$id][11]));                                $mailmessage.="`n`7Du weißt, als du das Geschenk auspackst, dass es dir bei Ramius Gefallen bringen wird. (Du erhältst {$tausch[$id][11]} Gefallen bei Ramius.)";                            }                            systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);                            $session['user']['kristalle']-=$tausch[$_GET['id']][7];                            $out="`n`&Dein/e {$tausch[$_GET['id']][0]} `& wurde hübsch verpackt und verschickt!";                        }                    break;                }            break;        }    break;    case "gold":        $out="`n`&Du hast {$session['user']['kristalle']} `&Kristalle bei dir. Für einen `#Kristall `&bekommst du `^20 Goldstücke`&. ";        $out.="`&Ich gebe dir `^".($session['user']['kristalle']*20)." Goldstücke `&für alle `#Kristalle, `&die du bei dir trägst!`n`n";        $out.="<form action='steintroll.php?op=gold2' method='POST'>`&Wie <u>v</u>iele Kristalle gibst du mir?: <input id='input' name='amount' width=5 accesskey='v'>";        $out.="<input type='submit' class='button' value='Geben'></form>";        $out.="<script language='javascript'>document.getElementById('input').focus();</script>";        addnav("","steintroll.php?op=gold2");        addnav("`^Alle Kristalle geben!","steintroll.php?op=allesgold");    break;    case "gold2":        if($_POST['amount']>$session['user']['kristalle']){            $out="`n`&So viele Kristalle besitzt du nicht!";        }else{            $gold=$_POST['amount']*20;            $out="`n`&Der ".($steintroll?"Steintroll":"Grasdrache")." gibt dir `^$gold Goldstücke `&für ".($_POST['amount']>1? "deine":"")." `#{$_POST['amount']} ".($_POST['amount']>1? "Kristalle":"Kristall")."`&.`n";            $out.=""; // Hier mehr blablabla :D            $session['user']['gold']+=$gold;            $session['user']['kristalle']-=$_POST['amount'];        }    break;    case "allesgold":        $out="`n`&Bist du ganz sicher, dass du mir alle deine `#Kristalle `&für `^".($session['user']['kristalle']*20)." Goldstücke `&geben willst?`n";        addnav("Jaaaa! Alle!","steintroll.php?op=allesgold2");        addnav("Halt! Nein! Zurück!","steintroll.php");    break;    case "allesgold2":        $gold=$session['user']['kristalle']*20;        $out="`n`&Der ".($steintroll?"Steintroll":"Grasdrache")." gibt dir `^$gold Goldstücke `&für deine `#{$session['user']['kristalle']} Kristalle`&.`n";        $out.=""; // Hier mehr blablabla :D        $session['user']['gold']+=$gold;        $session['user']['kristalle']=0;    break;    case "gems":        $out="`n`&Du hast {$session['user']['kristalle']} `&Kristalle bei dir. Für `#100 Kristalle `&gebe ich dir `%einen Edelstein`&. ";        $out.="`&Ich gebe dir `%".floor($session['user']['kristalle']/100)." Edelsteine `&für alle `#Kristalle, `&die du bei dir trägst!`n`n";        $out.="<form action='steintroll.php?op=gems2' method='POST'>`&Wie <u>v</u>iele Kristalle gibst du mir?: <input id='input' name='amount' width=5 accesskey='v'>";        $out.="<input type='submit' class='button' value='Geben'></form>";        $out.="<script language='javascript'>document.getElementById('input').focus();</script>";        addnav("","steintroll.php?op=gems2");        addnav("`^Alle Kristalle geben!","steintroll.php?op=allegems");    break;    case "gems2":        if($_POST['amount']>$session['user']['kristalle']){            $out="`n`&So viele `#Kristalle `&besitzt du nicht!";        }elseif($_POST['amount']<100){            $out="`n`&Mindestens `#100 Kristalle`&! Darunter geht mal gar nichts!";        }else{            $gems=floor($_POST['amount']/100);            $rest=$session['user']['kristalle']-($gems*100);            $out="`n`&Der ".($steintroll?"Steintroll":"Grasdrache")." gibt dir `%$gems ".($gems>1? "Edelsteine":"Edelstein")." `&für deine `#".($gems*100)." Kristalle`&.`n";            $out.=""; // Hier mehr blablabla :D            $session['user']['gems']+=$gems;            $session['user']['kristalle']=$rest;        }    break;    case "allegems":        $out="`n`&Bist du ganz sicher, dass du mir alle deine `#Kristalle `&für `%".floor($session['user']['kristalle']/100)." Edelsteine `&geben willst?`n";        addnav("Jaaaa! Alle!","steintroll.php?op=allegems2");        addnav("Halt! Nein! Zurück!","steintroll.php");    break;    case "allegems2":        $gems=floor($session['user']['kristalle']/100);        $kristalle=$gems*100;        $out="`n`&Der ".($steintroll?"Steintroll":"Grasdrache")." gibt dir `%{$gems} Edelsteine `&für deine `#{$kristalle} Kristalle`&.`n";        $out.=""; // Hier mehr blablabla :D        $session['user']['gems']+=$gems;        $session['user']['kristalle']-=$kristalle;    break;}output($out,true);addnav("Zurück");if(!empty($_GET['op'])) addnav("Zurück","steintroll.php");addnav("Zum Obstgarten","obstgarten.php");page_footer();
