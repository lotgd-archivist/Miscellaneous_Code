<?php
/*Ort Wegkreuzung
geschrieben für Arda
von Narjana
Ein Versuch*/

require_once "common.php";
addcommentary();
checkday();

if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
//if (getsetting("automaster",1) && $session['user']['seenmaster']!=2){
        //masters hunt down truant students
        $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
        while (list($key,$val)=each($exparray)){
                $exparray[$key]= round(
                        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
                        ,0);
        }
        $expreqd=$exparray[$session['user']['level']+1];
        if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
                redirect("train.php?op=autochallenge");
        }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){
                redirect("dragon.php?op=autochallenge");
        }
}

   switch($_GET['op'])
{
        case 'korn':
        {
                page_header("Kornkammern Ardas");
                output("`c`b`tK`^o`dr`Zn`6k`^a`tm`Zm`ge`Zrn `íA`Kr`wd`Qas `c`b`n`n
                                `wEs `4ha`Wt e`ein`\$ig`Ee Zeit gedauert, bis du durch den dichten `7N`Ge`Rb`Ge`7l`E hindurch treten kannst. Empfangen wirst du von den rot schimmernden Sonnenstrahlen `wN`4i`Wre`4a`ws`E, der größeren der beiden Sonnen Ardas. Dir wird schnell klar, das sie hier für das Wachsen und Gedeihen verantwortlich zu sein scheint. Unterschiedlichste Pflanzen ranken sich hier an alten Kiefern in die Höhe oder bilden selbst Halme, die stark genug sind, die Früchte zu tragen. Türkise `DM`da`Si`ss`Pko`sl`Sb`de`Dn`E wachsen an den rankenden Zweigen, deren grünliche Blätter die Rinde der Bäume vollkommen verdecken. Du gehst an Feldern entlang, deren Halme voller `Kb`ll`La`vu`Le`lr`E und `âv`éi`êo`Àlet`êt`ée`âr`E Äpfel hängen, die du einfach nur pflücken müsstest. Weiter von den Wegen entfernt erkennst du die `wpu`4rp`wur`4ro`wte`4n `EFelder auf denen Gerste, Roggen und andere Getreidesorten angebaut werden. Es ist ein farbenfroher Anblick, dessen du dir am besten aus der Luft bewusst werden kannst. Vielleicht solltest du eines der Gürteltiere fragen, die sich überall zwischen den Bäumen und Halmen verstecken und mit ihren ledernen Schwingen weit über das Land hinweg steigen können. Oder aber du folgst den schlammigen Pfaden weiter, auf denen man bei Regen gern einmal knietief einsinken kann in Richtung der `tB`ha`(u`Be`ör`Än`Bh`(ö`hf`te`E, die die Bewohner der Stadt mit Lebensmitteln von diesen Felder`\$n v`eer`Wso`4rg`wen.

`n");

                viewcommentary("korn","sagt:",15);
                addnav("zur Wegkreuzung","kreuzung.php");
                addnav("nach Arda","dorftor.php");
                addnav("zu den Bauernhöfen","kreuzung.php?op=hof");
                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'hof':
        {
                page_header("Die Bauernhöfe");
                output("`cDie Bauernhöfe `c `n
                `wDu`4rc`Wh d`eie `wpu`4rp`wur`4ro`wte`4n `EGetreidefelder und Acker voller `KA`lp`Lf`velh`Àa`êl`ém`âe`E führt ein schlammiger Weg, der nur hin und wieder mit groben Pflastersteinen gesichert wird, in Richtung einiger `tB`ha`(u`Be`ör`Än`Bh`(ö`hf`te`E. Du kannst schon von weitem das Gackern der `wH`4e`Wn`en`Ee`Qn`E hören, welche über die Wiesen grasen und deren Wolle nur darauf wartet geschoren zu werden. Wenn du den gepflasterten Hof in der Mitte dreier großer Höfe betrittst kannst du auch schon die `&K`Tü`&h`Te`E sehen, die sich wohlfühlend im Schlamm wälzen, ebenso wie die `ÀS`ìchwein`Àe`E, welche sich in den großen Käfigen ihre Hörner abstoßen. Besonders begeistert allerdings bist du von den eierlegenden `íWo`5ll`%mi`rlc`Vhs`ìäu`Àen`E, die frei zwischen den Höfen hin und her hüpfen. Sie sehen aus wie ihre wilden Verwandten, die W`hol`(pe`Brt`öin`Äge`Tr`E, allerdings sind ihre Flügel gestutzt, sodass sie Flugunfähig sind. Auch ihre Geweihe sind viel kleiner, weswegen sie vollkommen ungefährlich sind, aber bessere Milch geben. Meist ist das Fell gelblich oder sandfarben sodass sie auf den Feldern sehr gut auffallen. Wegen ihnen ist der große `jB`#r`Fu`3n`fn`3e`Fn`E in der Mitte des Platzes mit einem Gitter bedeckt, da diese Tiere nicht schwimmen können und sonst ertrinken würden. Wenn du dich weiter umblickst erkennst du die großen Silos, in welchen Getreide, Obst und Gemüse gelagert werden um die Bewohner Ardas mit Nahrung zu versorgen. Auch erkennst du bei genauerem Hinsehen einige Schutzmechanismen die die Tiere und die Bauern vor Angriffen durch Wildtiere oder schlecht gelaunte Bewohn`\$er `esc`Whü`4tz`wen. ");

                viewcommentary("hof","sagt:",15);
                addnav("zurück");
                addnav("zu den Kornfeldern","kreuzung.php?op=korn");
                break;
        }
        default:
        {

                page_header("Wegkreuzung");

               output("`c`b`-Wegkreuzung`b`n
                `Y`nNachdem du die Stadt Arda verlassen hast strandest du an einer Wegkreuzung. `n
 `n
Um dich herum siehst du von weiten angelegte Felder mit Weizen und Gerste,  `n
sowie aber auch ein paar Höfe die den Bauern hier gehören.  `n
 `n
Vor dir befindet sich ein Schild, das zu jeder Wegkreuzung einen Namen auf dem Schild trägt. `n
Auch siehst du ein paar Passanten die wenn du Fragen hast Dir gerne mehr erzählen. `n
Aber nicht nur die Leute erzählen dir gerne etwas, auch die Schilder lesen dir vor, was auf ihnen steht.`n
Außerdem kann es durchaus sein, daß sie das eine oder andere Lied trällern.`n
 `n
 `n
`b`3No`Frd`3en- `öZw`(er`Bge`(nst`öadt`b`n
`YIn den Bergen, liegt diese kleine Stadt. `n
Hier kannst Du dich ausrüsten aber auch deine Muskeln im Training stärken. `n
 `n
 `n
`b`3Os`Ft`3en-  `-Z`My`-l`My`-m`Ma `n`b
`YDie Katzenstadt oder auch die Stadt am Sumpf genannt. `n
 Ein ruchloser dunkler Ort, `n
 wo sich Wesen aller Art tummeln und dort ihr Unwesen treiben. `n
Allerdings ist diese Stadt neben Sklavenhandel auch für seine Casinos bekannt.  `n
 `n `n
`b`3We`Fst`3en– `SS`dy`Dm`di`Sa `b`n
`YDurch den dunklen Wald getrieben, erkennt man diese schon von weiten, `n
ein grünes Schimmern leitet dich zu der hoch gelegenen Elfenstadt.  `n
Dort kannst du einfach die Seele baumeln lassen und `n
 deine Schönheit noch ansehnlicher gestalten.  `n
 `n
 `n
`b`3Sü`Fd`3en- `eA`Wr`4d`ea `n`b
`YIm Süden schließlich liegt die Hauptstadt der Insel Arda.  `n
Welche du gerade hinter Dir gelassen hast um auf weitere Entdeckungsreise zu gehen.  `n
 `n
Etwas außerhalb davon befindet sich auch ein recht großer Friedhof. `0`c`n
 `n
 `n`n`n`n
                ");


                viewcommentary("kreuzung","sagt",15);

                addnav("Reisen");
                addnav("Dörfer");
                addnav("nach Zylyma","zylyma-reise.php");
                addnav("nach Symia","sanela-reise.php");
                addnav("zur Zwergenstadt","zwergenstadt-reise.php");
                addnav("nach Arda","dorftor.php");
                addnav("Umland");
                addnav("Kornkammern Ardas","kreuzung.php?op=korn");
                addnav("in die Berge","berge.php");
                addnav("Strand","sanelastrand.php");
                addnav("Sümpfe der Verlorenen","moor.php");
                addnav("In den Wald","forest.php");
                addnav("Dark Horse Tarverne","forest.php?specialinc=darkhorse.php");
                addnav("Friedhof","friedhof.php");
                addnav("Ebene der Fantasie (Spielerorte)","orte.php");
                break;


     }

}
page_footer();

?> 