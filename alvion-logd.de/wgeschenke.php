
<?php//     Geschenkestand by Opal                                             //// gebaut für seinen Weihnachtsmarkt                                   //require_once "common.php";require_once "func/systemmail.php";page_header("Geschenkestand");output("`c`b`&Geschenkestand`0`b`c`n`n");output("`TBe`Bre`tits ein wenig ermüdet von a`Bll `Tden Er`Bei`tgnissen bleibt dir nur noch ein`Bes`T zu tu`Bn.`t Du wolltest unbedingt noch ir`Bge`Tndwo e`Bin p`taar Geschenke für deine Fr`Beu`Tnde kau`Bfe`tn, wobei dein Blick wie d`Bur`Tch Zuf`Ball a`tn einem etwas kleineren Stan`Bd `Tam Ra`Bnd`te des Marktes hingen blieb.Begl`Beit`Tet von fes`Btli`tcher Stimmung trittst du lang`Bsa`Tm näher u`Bnd `tschaust dir erst einmal die Angebot`Be a`Tn.Sofort s`Bte`tigt dir ein süßer Duft in die N`Bas`Te, ausgeh`Bend vo`tn bunten Räucherstäbche`Bn u`Tnd Kerze`Bn.Ke`ttten in allen nur vorstell`Bba`Tren Forme`Bn un`td wunderschön in liebevoll`Ber `THanda`Brb`teit geschnitzte Holzfigur`Ben`T schmück`Bten`t die hinteren Regale.Da fi`Bel d`Tie Auswah`Bl w`tirklich schwer. Eventuell n`Bimm`Tst du a`Buc`th noch etwas, für dich selb`Bst `Tmit?`n`n`c`&`bPreisliste`b`n`qRäucherstäbchen - 10 Gold`nDuftkerzen - 15 Gold`nGoldener Engel - 50 Gold`nPerlenkette - 150 Gold `n`n`n");addnav("`bGeschenke`b");addnav("`&Räucherstäbchen","wgeschenke.php?op=klick");addnav("`&Duftkerze","wgeschenke.php?op=klick1");addnav("`&Goldener Engel","wgeschenke.php?op=klick3");addnav("`&Perlenkette","wgeschenke.php?op=klick2");addnav("`bZurück`b");addnav("Zurück","weihnachtsmarkt.php");if ($_GET['op']=="send"){$gift=$_GET['op2'];if (isset($_POST['search']) || $_GET['search']>""){if ($_GET['search']>"") $_POST['search']=$_GET['search'];$search="%";for ($x=0;$x<strlen($_POST['search']);$x++){$search .= substr($_POST['search'],$x,1)."%";}$search="name LIKE '".$search."' AND ";if ($_POST['search']=="weiblich") $search="sex=1 AND ";if ($_POST['search']=="männlich") $search="sex=0 AND ";}else{$search="";}$ppp=25; // Player Per Page to displayif (!$_GET[limit]){$page=0;}else{$page=(int)$_GET[limit];addnav("Vorherige Seite","wgeschenke.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");}$limit="".($page*$ppp).",".($ppp+1);$sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND charm>1 ORDER BY login,level LIMIT $limit";$result = db_query($sql);if (db_num_rows($result)>$ppp) addnav("Nächste Seite","wgeschenke.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");output("`r`nÜberglücklich strahlt dich die Bedienung an.`n \"Für wen ist den das Geschenk bestimmt ?\"`n`n");output("<form action='wgeschenke.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);addnav("","wgeschenke.php?op=send&op2=$gift");output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);for ($i=0;$i<db_num_rows($result);$i++){$row = db_fetch_assoc($result);output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='wgeschenke.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);output($row['name']);output("</a></td><td>",true);output($row['level']);output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);addnav("","wgeschenke.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));}output("</table>",true);}if ($_GET['op']=="send2"){$name=$_GET['name'];$effekt="";if ($_GET[op2]=="gift2"){        $gift="Räucherstäbchen";        $effekt="Ein Räucherstäbchen das wundervoll duftet";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Räucherstäbchen',$name,'Geschenk',5,'Dieses Räucherstäbchen  hat dir ".$session[user][name]."`0 geschenkt.')");      $session[user][gold]-=10;   }if ($_GET[op2]=="gift3"){        $gift="Duftkerze";        $effekt="Eine Duftkerze die wundervoll duftet";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Duftkerze',$name,'Geschenk',5,'Diese Duftkerze hat dir ".$session[user][name]."`0 geschenkt.')");      $session[user][gold]-=15;   }   if ($_GET[op2]=="gift4"){        $gift="Perlenkette";        $effekt="Eine Perlenkette die jeden Hals einer Frau verschönern sollte";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Perlenkette',$name,'Geschenk',5,'Diese Perlenkette  hat dir ".$session[user][name]."`0 geschenkt.')");      $session[user][gold]-=150;   }   if ($_GET[op2]=="gift5"){        $gift="Goldener Engel";        $effekt="Ein Goldener Engel der wunderbar zu dieser Jahreszeit passt";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Goldener Engel',$name,'Geschenk',5,'Diesen Goldenen Engel  hat dir ".$session[user][name]."`0 geschenkt.')");      $session[user][gold]-=50;   }$mailmessage=$session['user']['name'];$mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";$mailmessage.=$gift;//you can change the following the match what you name your gift shop$mailmessage.="`7 vom Geschenkestand.`n".$effekt;systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);output("`rMit leuchtenden Augen nimmt die Bedienung die Münze entgegen. \"Ich danke euch!\"`nmurmelt sie schüchtern und läuft los um ".($gift=="Goldener Engel"?"den":"die")." $gift zu überbringen...");addnav("Weiter","wgeschenke.php");}// Klick beginif ($_GET['op']=="klick"){output("`rMöchtest du die Räucherstäbchen behalten oder verschenken ?`n");addnav("Verschenken","wgeschenke.php?op=send&op2=gift2");addnav("Behalten","wgeschenke.php?op=selbst&op3=gift2");}if ($_GET['op']=="klick1"){output("`rMöchtest du die Duftkerze behalten oder verschenken ?`n");addnav("Verschenken","wgeschenke.php?op=send&op2=gift3");addnav("Behalten","wgeschenke.php?op=selbst&op3=gift3");}if ($_GET['op']=="klick2"){output("`rMöchtest du die Perlenkette behalten oder verschenken ?`n");addnav("Verschenken","wgeschenke.php?op=send&op2=gift4");addnav("Behalten","wgeschenke.php?op=selbst&op3=gift4");}if ($_GET['op']=="klick3"){output("`rMöchtest du den Goldenen Engel behalten oder verschenken ?`n");addnav("Verschenken","wgeschenke.php?op=send&op2=gift5");addnav("Behalten","wgeschenke.php?op=selbst&op3=gift5");}// Klick endeif ($_GET['op']=="selbst"){$session [user][acctid];$name=$session[user][acctid];$effekt="";if ($_GET[op3]=="gift2"){        $gift="Räucherstäbchen";        $effekt="Ein Räucherstäbchen das wundervoll duftet";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Räucherstäbchen',$name,'Geschenk',5,'Diese/s Räucherstäbchen  hat dir ".$session[user][name]." geschenkt.')");      $session[user][gold]-=10;   }if ($_GET[op3]=="gift3"){        $gift="Duftkerze";        $effekt="Eine Duftkerze die wundervoll duftet";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Duftkerze',$name,'Geschenk',5,'Diese Duftkerze hat dir ".$session[user][name]." geschenkt.')");      $session[user][gold]-=15;   }if ($_GET[op3]=="gift4"){        $gift="Perlenkette";        $effekt="Eine Perlenkette die jeden Hals einer Frau verschönern werden sollte";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Perlenkette',$name,'Geschenk',5,'Diese Perlenkette  hat dir ".$session[user][name]." geschenkt.')");      $session[user][gold]-=150;   }    if ($_GET[op3]=="gift5"){        $gift="Goldener Engel";        $effekt="Ein Goldener Engel der wunderbar zu dieser Jahreszeit passt";      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Goldener Engel',$name,'Geschenk',5,'Diesen Goldenen Engel  hat dir ".$session[user][name]." geschenkt.')");      $session[user][gold]-=50;   }/*$mailmessage=$session['user']['accid'];$mailmessage.="`7 Deine Ware ist geliefert worden .Es ist ein/e `6";$mailmessage.=$gift;//you can change the following the match what you name your gift shop$mailmessage.="`7 vom Geschenkestand.`n".$effekt;systemmail("`2Ware erhalten!`2",$mailmessage);*/output("`rMit leuchtenden Augen nimmt die Bedienung die Münze entgegen. \"Ich danke euch!\"`nmurmelt sie schüchtern und läuft los um die $gift zu überbringen...");addnav("Weiter","wgeschenke.php");}page_footer();?>
