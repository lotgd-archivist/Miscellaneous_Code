<?php

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
/*        case '':
        {
                page_header("Kleiner Teich");
                output("`c`bTeich 1`c`b`n
                                Beschreibung`n");

                viewcommentary("teich","sagt:",15);
                addnav("zu den Gräbern","moor.php?op=wander");
                addnav("in den Sumpf","moor.php");
                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }*/
        case 'wiese':
        {
                page_header("Bergwiese");
                output("`c`PMa`sn m`Suss schon wirklich geschickte Beine haben `sod`Per `n
di`se v`Sersteckten Wege der Zwerge kennen, um hier h`sin `Pzu `n
ko`smm`Sen. Aber der Anblick lohnt sich. Die Höhens`son`Pne `n
sc`she`Sint wunderbar warm und das Gras ist grün un`sd h`Pat `n
ei`sne`Sn ganz eigenen würzigen Duft. Von hier aus `ska`Pnn `n
ma`sn d`Sie Sümpfe, die Wälder und sogar einen kle`sin`Pen `n
Te`sil `Sder `tK`^o`dr`Zn`6k`^a`tm`Zm`ge`Zr `Süberbli`sck`Pen.`n
Um `sdi`Sch herum schwirren und flattern Insekten, die du `sno`Pch`n
ni`se g`Sesehen hast und du auch keine Ahnung hast, was sie von dir wo`sll`Pen.`n
We`snn `Sdu dich leise und unauffällig verhälst, entdeck`sst `Pdu`n
vi`sel`Sleicht eins von `lN`La`Mr`-j`âa`5n`%a`Ss Haustieren, die hier manc`shm`Pal`n
gr`sas`Sen und sich so`snn`Pen.`n`c`n`n`n");

                viewcommentary("ue18wiese","sagt:",15);
        //        addnav("zurück in die Gärten","elfengärten.php");
        //        addnav("Quelle des Lebens","elfengärten.php?op=quelle");
        //        addnav("magische Wendeltreppe runtersteigen","moor_unten.php");
                addnav("Zurück in die Berge","berge.php");
                addnav("heiße Quellen","berge.php?op=quelle2");
                break;
        }
        
        case 'quelle2':
        {
                page_header("Heiße Quelle");
        output("`c<img src='images/quelle5.jpg' alt='' >`c`n",true);
                output("`c`MMi`Rtt`5en durch eine Art plötzlich auftretenden Nebel näh`Rer`Mst `n
du`R di`5ch einem Bereich, in dem es immer wärme`Rr u`Mnd `n
fe`Ruc`5hter wird. Doch noch während du überlegst, d`Rei`Mne `n
Rü`Rst`5ung abzulegen, bleibst du plötzlich st`Reh`Men. `n
Di`Rre`5kt vor dir tauchen die heißen Quelle`Rn a`Muf. `n`n
Da `Rdu `5eh schon schwitzt, legst du deine Kleidun`Rg n`Mun `n
wi`Rrk`5lich ab und nimmst ein entspannendes Bad. Nac`Rhd`Mem `n
du `Rim `5Wasser bist, siehst du einen Schatten durchs Wa`Rss`Mer `n
gl`Rei`5ten. Schlagartig wird dir bewusst, daß hier ja woh`Rl d`Mer `n
ar`Rde`5nische Fischfliegendrache lebt. Gerüchten zuf`Rol`Mge `n
so`Rll`5en diese Wesen alles anknabbern, was sich unter Wa`Rss`Mer `n
be`Rfi`5ndet. Oder sich auch mit den Leuten unterhalten, die `Rhi`Mer `n
ba`Rde`5n. Wirst du ihn zu Gesicht beko`Rmm`Men?`n`c`n`n");

                        viewcommentary("quelle2","sagt:",15);
                        addnav("zurück","berge.php");

               

                                break;
        }
        default:
        {

                page_header("Die Berge Ardeniens");

                output("`c`b`KD`li`Le Berge Ardenie`ln`Ks`b`c`n`n");
                output("`c`KI`lm `LNorden Ardeniens erhebt sich das Gebirge der Ins`le`Kl. `n
`KP`lf`Lade führen von vielen Orten hinauf, doch führen n`lu`Kr `n
w`le`Lnige wirklich hindurch. Hier und dort sieht man d`li`Ke `n
A`lr`Lbeiten der Zwerge aus vergangenen Tagen, do`lc`Kh `n
e`lr`Lkennt man auch, dass sich nur selten ein Wander`le`Kr `n
h`li`Ler her wagt. Die vom ewigen Eis bedeckten Gipf`le`Kl, `n
b`le`Lrgen so manches Geheimnis. Einige sind schön w`li`Ke `n
d`le`Lr beginnende Morgen, andere dunkel wie die mondlo`ls`Ke `n
N`la`Lcht. Der, der den richtigen Weg wählt, erreicht die Lurnfäl`ll`Ke, `n
b`le`Lzaubernd und erschreckend zugleich. Ein anderer W`le`Kg `n
f`lü`Lhrt zu den heißen Quellen, entspannend und manchm`la`Kl `n
s`lo`Lgar heilend für Körper und Geist. Der jedoch, der d`le`Kn `n
f`la`Llschen Weg wählt, muss um sein Leben bangen, erwart`le`Kn `n
i`lh`Ln doch Schrecken und Furcht: Pflanzenwesen, die geh`le`Kn `n
u`ln`Ld stehen wie die Menschen und hungernd nach Fleis`lc`Kh.`c`n
`n`n`n");

                viewcommentary("berge"," ",15);

                addnav("Lurnfälle","narjan.php?op=lurn"); //Auskommentiert, bis Fehler in anderer Datei behoben.
                addnav("zurück");
                addnav("Wegkreuzung","kreuzung.php");
                addnav("Zwergenstadt","zwergenstadt-reise2.php");
                addnav("heiße Quellen","berge.php?op=quelle2");
               
                        addnav("Bergwiese","berge.php?op=wiese");
                
                break;
     }

}
page_footer();

?>