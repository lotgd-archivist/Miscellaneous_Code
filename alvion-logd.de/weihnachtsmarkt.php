
<?php/* Weihnachtsmarkt by Opal */require_once "common.php";page_header("Weihnachtsmarkt");addcommentary();page_header("Weihnachtsmarkt");$session['user']['standort']="Weihnachtsmarkt";output("`c`b`vWei`=hna`&chts`=ma`vrkt`b`c`n`n`TÜberall i`Bn der `tLuft `Bvermisc`Then sich die `Rs`=ü`&ßen Düf`=t`Re `Tvon `TSc`Bh`tokol`Ba`Tde`T, anderen `^Le`tck`Oer`tei`^en `Tund dem angeneh`Bmen Hauch von `7Rä`Fuc`vher`Fwe`7rk,  `Tals du in die Nähe des Weihnachtsmarktes kommst. Ber`Beits jetzt beein`tdruckt, schaust du dich um u`Bnd erblic`Tkst dutzen`Bde von `NVe`Qrk`qau`^fss`qtä`Qnd`Nen`T, die mit ihren Ware`Bn um d`tie Gunst der Besuche`Br buhlen. An je`Tder Ecke tragen hel`Ble `4F`Nac`Xk`qe`Oln `Tund `=Ker`qz`Ne`4n`T, wie du sie in ei`Bner solche`tn Vielfalt noc`Bh nie zu sehen b`Tekommen ha`Bst, zum festlic`then Flair des M`Barktes `Tbei und versetzen `Balle Gäste i`tn `4weih`Nnach`&tliche Stim`Nmu`4ng`T. In einer Ecke e`Brblickst du k`tleine Kinder, die unter Anleitung ih`Bres Lehrers beruhigen`Tde Lieder sing`Ben. Hän`tdler laufen aufgere`Bgt mi`Tt lächelnden Ges`Bichte`trn umher und biet`Ben all`Tes nur Erd`Benkliche a`tn. Von `^Ges`9ch`^en`9k`^en`T für Freunde und Fam`Bilie, über `QNa`lsc`ther`lei`Qen `T bis hin zu `Th`Be`Cr`Xzh`Naf`4te`Nn D`Xin`Cg`Be`Tn `Tund reichl`Bich `twa`8rm`&en Getr`8ä`tn`ìken, `Tum die Kälte der J`tahreszeit au`Bs den Gli`Tedern zu vertreiben. Ein herrlich`Bes Ereig`tnis für Groß und Klein. Voll`Ber Be`Tgeisterung schlen`Bderst d`tu durch die vielen kle`Binen `TGassen, immer dar`Bauf bed`tacht, niemanden im `BGedr`Tänge anzuremp`Beln. Do`tch wohin sollt`Best du di`Tch zuer`Bst wend`ten?`n`n");$session['user']['whereuser']=1;// addnav("Grillstand","grill.php");// addnav("Süßigkeitenstand","sues.php");addnav("Geschenkestand","wgeschenke.php");addnav("Glühweinstand","gluewein.php");       addnav("Wurfbude","wurfbude.php");addnav("Zum See","gefrorenersee.php");addnav("Losbude","losbude.php");addnav("Schneeballwettkampf","schneeball.php?op=schball");addnav("Zum Dorf","village.php");output("`n`n`nMit anderen Besuchern unterhalten:`n");viewcommentary("weihnachtsmarkt","Hinzufügen",25,"sagt",1,1);page_footer();?>
