
<?php

/*###############################################################
Skript : dragonmind.php
Ersteller: deZent / draKarr von www.plueschdrache.de
Edit by: Hadriel von http://www.hadrielnet.ch
Version: 0.15
Beschreibung : siehe Regeln im Spiel ;-)
Gründe für den chaoscode : Eigentlich sollte das Skript nur ein kleiner $_POST[]-Test werden.. Am Ende wurds halt Mastermind.
--> also nicht motzen--> besser machen
Install:
1. dragonmind.php in root Ordner kopieren
2. neuen Ordner "dragonmind" im "images" Ordner erstellen.
3. checken ob die Datei "trans.gif" schon im images Ordner ist.
3.1 JA --> gut so
3.2 Nein --> also reinkopieren

in "inn.php"
------------
addnav("DragonMind","dragonmind.php");

DATENBANK:
----------
Ich habe absichtlich auf $session[user] Felder weitesgehend verzichtet.
Darum nutze ich das Feld "pqtemp" , das bereits in diversen Skripten angezogen wird.

Falls noch nicht vorhanden:
ALTER TABLE `accounts` ADD `pqtemp` TEXT NOT NULL
Bin mir nicht sicher ob das Feld orginalerweise vom Typ "TEXT" war. Hier würde eigentlich auch VARCHAR 255 locker reichen.

Viel Spaß,
deZent

P.S. WER WÜRDE MAL EIN PAAR EINTRÄGE FÜR DIE TAUNTS TABELLE BEI ANPERA POSTEN!?! *Verdammt!*
*/

// Modified by Maris (Maraxxus [-[at]-] gmx.de)
// Modified by talion

require_once "common.php";
page_header("Dragonmind");
output("`c`b`]Dragonmind V0.15`0`b`c`n`n");

$str_backlink = 'inn_spielhoehle.php';
$str_backtext = 'Zur Spielhöhle';

// Max. Gewinn bei Spielen (Memory + Dragonmind)
$arr_info = user_get_aei('games_played');
$int_max_win = (150 * $session['user']['level']);
$bool_no_gold = ($int_max_win <= $arr_info['games_played'] ? true : false);

$__anzahl_versuche = 10;
// wieviele Versuche um den Code zu knacken
$__anzahl_farben   = 12;
// wieviele der 10 Farben?
$__einsatz         = 200;
// Einsatz Gold
$__gewinn          = 300;
// Gewinn Gold    achte darauf, dass der Gewinn nicht zu extrem wird, da es Programme gibt, die
// Mastermind in 5 Zügen lösen. Somit cheat-Gefahr...
$farbe[0]['farbe']="#800000";
$farbe[0]['name']="dunkelrot";

$farbe[1]['farbe']="#008000";
$farbe[1]['name']="grün";

$farbe[2]['farbe']="#E6E629";
$farbe[2]['name']="gelb";

$farbe[3]['farbe']="#0000F0";
$farbe[3]['name']="blau";

$farbe[4]['farbe']="#800080";
$farbe[4]['name']="lila";

$farbe[5]['farbe']="#FF0000";
$farbe[5]['name']="rot";

$farbe[6]['farbe']="#14EAD3";
$farbe[6]['name']="türkis";

$farbe[7]['farbe']="#F26A10";
$farbe[7]['name']="orange";

$farbe[8]['farbe']="#00A8FF";
$farbe[8]['name']="hellblau";

$farbe[9]['farbe']="#FFFFFF";
$farbe[9]['name']="weiß";

$farbe['10']['farbe']="#100000";
$farbe['10']['name']="schwarz";

$farbe['11']['farbe']="#B0A0B0";
$farbe['11']['name']="grau";

if ($_GET['op']=='')
{
    addnav("Dragonmind");

    output('`[In einem abgelegenen Teil der Schenke, die man unter den Dorfbewohnern nur als "Spielhöhle" bezeichnet, ist mit leuchtender
    Schrift das Wort
<font size=+1>`b`$D`4ragon`$M`4ind`0`b </font>`n`n`[an die Wand gepinselt wurden.`n`n
',true);
    if ($session['user']['gold']<$__einsatz && !$bool_no_gold)
    {
        output("Der Wirt raunzt dich an:`s\"Hier kannst du um ein paar Goldstücke spielen. Der Spieleinsatz ist jedoch 200 Gold. Soviel Gold hast du allerdings nicht bei dir! Entweder du suchst dir ein weniger kostenintensives Spiel und du machst dich vom Acker!\"`0");
    }
    else if ($session['user']['turns'] <= -1)
    {
        output("Der Wirt raunzt dich an:`s\"Hier kannst du um ein paar Goldstücke spielen. Du bist aber schon viel zu erschöpft, das sehe ich mit dem Auge des Kenners auf den ersten Blick! Also ruh dich lieber erstmal aus, bevor du hier noch leichtsinnig dein ganzes Gold verspielst!\".`0");
    }
    else
    {
        output("Der Wirt raunzt dich an:`s\"Hier kannst du um ein paar Goldstücke spielen.\"`0");

        if($bool_no_gold) {
            output('`n`s"Nur hast du heute schon genug gewonnen.. Dieses Mal darfst du dich ganz ohne finanzielle Beteiligung am Spiel versuchen."`0');
        }

        addnav("Einfaches Spiel spielen","dragonmind.php?op=new&type=1");
        addnav("Schwieriges Spiel spielen","dragonmind.php?op=new&type=2");
    }
    addnav("Regeln","dragonmind.php?op=regeln");
    addnav("Zurück");
    addnav($str_backtext,$str_backlink);
}
else if ($_GET['op']=='new')
{

    $type=$_GET['type'];
    if ($type==1)
    {
        $__anzahl_farben   = 10;
        // wieviele der 12 Farben?
        $__gewinn          = 100;
    }
    else if ($type==2)
    {
        $__anzahl_farben   = 12;
        // wieviele der 12 Farben?
        $__gewinn          = 200;
    }

    if($bool_no_gold) {
        $__einsatz = 0;
        $__gewinn = 0;
    }

    //$session['user']['turns']--;
    $session['user']['gold']-=$__einsatz;
    addnav("`[Du hast noch:");
    addnav("$__anzahl_versuche Versuche");

    // farbkombi festlegen
    $zuf=array();
    for ($i=0; $i<4; $i++)
    {
        while (true)
        {
            $check=e_rand(0,($__anzahl_farben-1));
            if (array_search($check,$zuf)===false)
            {
                $zuf[$i]=$check;
                break;
            }
        }
        $zufall[$i]['farbe']=$farbe[$check]['farbe'];
        $zufall[$i]['name']=$farbe[$check]['name'];
    }
    $session['user']['pqtemp']=serialize($zufall);
    output("`[Wähle deine Farben:`0`n`n");
    output("<form action='dragonmind.php?op=play&type=$type' method='post' name='f1'>",true);
    for ($i=0; $i<4; $i++)
    {
        output("<select name='auswahl[".$i."]'>",true);
        if ($__anzahl_farben<12)
        {
            $sel=$__anzahl_farben;
        }
        if ($__anzahl_farben>=12)
        {
            $sel=count($farbe);
        }
        for ($j=0; $j<($sel); $j++)
        {
            output("<option value='".$farbe[$j]['farbe']."' style='background-color:".$farbe[$j]['farbe']."'>".$farbe[$j]['name']."</option>",true);
        }
        output("</select>",true);
    }

    output("<br><br><input type='submit' name='rate' value='Tipp abgeben'>",true);
    output("</form>",true);
    addnav("","dragonmind.php?op=play&type=$type");
}
else if ($_GET['op']=='play')
{
    $type=$_GET['type'];
    if ($type==1)
    {
        $__anzahl_farben   = 10;
        // wieviele der 10 Farben?
        $__gewinn          = 400;
    }
    else if ($type==2)
    {
        $__anzahl_farben   = 12;
        // wieviele der 10 Farben?
        $__gewinn          = 600;
    }

    if($bool_no_gold) {
        $__einsatz = 0;
        $__gewinn = 0;
    }

    // erstmal die such farben wieder auslesen
    $farben=unserialize($session['user']['pqtemp']);
    // mal schauen ob er was erraten hat.
    $rs=0;
    // richtige stelle  + richtige Farbe
    $rf=0;
    // richtige farbe
    // check ob richtige farbe an richtiger stelle
    for ($i=0; $i<count($farben); $i++)
    {
        for ($j=0; $j<count($farben); $j++)
        {
            if ($_POST['auswahl'][$i]==$farben[$j]['farbe'])
            {
                if ($i==$j)
                {
                    $rs++;
                    $farben[$j]['farbe']='';
                }
            }
        }
    }
    // richtige farbe aber falsche stelle
    for ($i=0; $i<count($farben); $i++)
    {
        for ($j=0; $j<count($farben); $j++)
        {
            if ($_POST['auswahl'][$i]==$farben[$j]['farbe'])
            {
                $rf++;
                $farben[$j]['farbe']='';
            }
        }
    }
    $farben=unserialize($session['user']['pqtemp']);
    // Farbpunkte für aktuellen rateversuch zusammenbauen
    for ($i=0; $i<$rs; $i++)
    {
        $bilder_aktuell.='<img src="./images/dragonmind/gruen.gif" alt="Richtige Farbe an richtiger Stelle" title="Richtige Farbe an richtiger Stelle">';
    }
    for ($i=0; $i<$rf; $i++)
    {
        $bilder_aktuell.='<img src="./images/dragonmind/rot.gif" alt="Richtige Farbe an falscher Stelle" title="Richtige Farbe an falscher Stelle">';
    }

    if ($rs==4)
    {
        $gewonnen=true;
    }
    // player hat gewonnen
    addnav("","dragonmind.php?op=play&type=$type");
    output("<form action='dragonmind.php?op=play&type=$type' method='post' name='f1'>",true);
    if (count($_POST['versuche'])>=$__anzahl_versuche-1 || $gewonnen)
    {
        output("<table>
<tr><td colspan='5' align='center' style='background-color:#AFDB02;color:#000000;font-weight:bold;'>LÖSUNG</td></tr>
<tr>
<td bgcolor=".$farben[0]['farbe'].">
<img src='./images/trans.gif' width='83' height='10'>
</td>
<td bgcolor=".$farben[1]['farbe'].">
<img src='./images/trans.gif' width='83' height='10'>
</td>
<td bgcolor=".$farben[2]['farbe'].">
<img src='./images/trans.gif' width='83' height='10'>
</td>
<td bgcolor=".$farben[3]['farbe'].">
<img src='./images/trans.gif' width='83' height='10'>
</td>
<td>
Lösung
</td>
</tr>",true);
    }
    else
    {
        output("<table>",true);
    }
    output("<tr>
<td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
Farbe 1
</td>
<td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
Farbe 2
</td>
<td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
Farbe 3
</td>
<td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
Farbe 4
</td>
<td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
Info
</td>
</tr>",true);
    for ($i=0; $i<count($_POST['versuche']); $i++)
    {
        // letzte auswertung auslesen und Bilder -code schreiben.
        $auswertung = explode("-",$_POST['tip'][$i]);
        $bilder='';
        for ($k=0; $k<$auswertung[0]; $k++)
        {
            $bilder.='<img src="./images/dragonmind/gruen.gif" alt="Richtige Farbe an richtiger Stelle" title="Richtige Farbe an richtiger Stelle">';
        }
        for ($k=0; $k<$auswertung[1]; $k++)
        {
            $bilder.='<img src="./images/dragonmind/rot.gif" alt="Richtige Farbe an falscher Stelle" title="Richtige Farbe an falscher Stelle">';
        }
        output("<tr>
<td bgcolor=".$_POST['versuche'][$i][0].">
&nbsp;
</td>
<td bgcolor=".$_POST['versuche'][$i][1].">
</td>
<td bgcolor=".$_POST['versuche'][$i][2].">
</td>
<td bgcolor=".$_POST['versuche'][$i][3].">
</td>
<td>
$bilder",true);
        for ($k=0; $k<count($_POST['versuche'][$i]); $k++)
        {
            output("<input type='hidden' name='versuche[$i][$k]' value='".$_POST['versuche'][$i][$k]."'> ",true);
        }
        output("<input type='hidden' name='tip[".$i."]' value='".$_POST['tip'][$i]."'> ",true);
    }
    output("
</td>
</tr><tr>
<td bgcolor=".$_POST['auswahl'][0].">
&nbsp;
</td>
<td bgcolor=".$_POST['auswahl'][1].">
</td>
<td bgcolor=".$_POST['auswahl'][2].">
</td>
<td bgcolor=".$_POST['auswahl'][3].">
</td>
<td>
$bilder_aktuell",true);

    for ($k=0; $k<count($_POST['auswahl']); $k++)
    {
        output("<input type='hidden' name='versuche[".$i."][$k]' value='".$_POST['auswahl'][$k]."'>",true);
    }
    output("<input type='hidden' name='tip[".$i."]' value='".$rs."-".$rf."'>
</td>
</tr>",true);

    if ((count($_POST['versuche'])<$__anzahl_versuche-1) && $gewonnen!=true)
    {
        output("<tr>",true);

        for ($i=0; $i<4; $i++)
        {
            output("<td><select name='auswahl[".$i."]'>",true);
            if ($__anzahl_farben<12)
            {
                $sel=$__anzahl_farben;
            }
            if ($__anzahl_farben>=12)
            {
                $sel=count($farbe);
            }
            for ($j=0; $j<($sel); $j++)
            {
                output("<option value='".$farbe[$j]['farbe']."' style='background-color:".$farbe[$j]['farbe']."' ".($_POST['auswahl'][$i] == $farbe[$j]['farbe']?" selected":"").">
".$farbe[$j]['name']."
</option>",true);
            }
            output("</select></td>",true);
        }
        output("</tr></table>",true);
        output("<br><br><input type='submit' name='rate' value='Tipp abgeben'>",true);
        output("</form>",true);
        $versuche=$__anzahl_versuche - count($_POST['versuche']) -1;
        if ($versuche!=0)
        {
            addnav("Du hast noch");
            addnav("$versuche Versuche");
        }
    }
    else
    {
        // fertig
        output("</table></form>",true);

        //schauen ob gewonnen oder Ende
        if ($gewonnen)
        {
            user_set_aei(array('games_played'=>$arr_info['games_played']+$__gewinn+$__einsatz));
            $session['user']['gold']+=$__gewinn +$__einsatz;
            //redirect("dragonmind.php?op=gewonnen"); //redirect löscht die Ausgabe
            $_GET['op']='gewonnen';
        }
        else
        {
            output("<h1>DU HAST VERLOREN</h1>",true);
        }
        if ($session['user']['gold']>=$__einsatz)
        {
            addnav("Erneut spielen","dragonmind.php");
        }
        addnav("Zurück");
        addnav("Zur Kneipe","inn.php");
    }

}
else if ($_GET['op']=='regeln')
{
    addnav("Zurück","dragonmind.php");
    output("`c`b`]Dragonmind Regeln `0`b`c`n`n");
    output("`[Das Ziel des Spiels ist das Erraten der Farbkombination, die der Spielführer ausgewählt hat.
Jede Farbe kommt nur EINMAL vor!`n
Du kannst zu Beginn wählen ob du einfach (mit 10 Farben) oder schwierig (mit 12 Farben) spielen willst. Das einfache Spiel bringt dir bei einem Sieg 400 Goldmünzen ein, das Schwierige 600.
Du wählst in den Drop-Down Feldern deine Farbkombination aus und drückst auf 'raten'.
Daraufhin erscheint deine Auswahl. Dir wird mitgeteilt ob du richtige Farben gewählt bzw diese auch an der richtigen Stelle plaziert hast.`n

Welche Farben deiner Auswahl richtig gewählt oder richtig plaziert wurden wird nicht verraten!
Du hast maximal 10 Versuche, die richtige Auswahl zu erraten.`n`n
P.S.  Bei z.B. 10 Farben gibt es 5040 verschiedene Farbkombinationen... Nur durch probieren wirst du es wohl eher nicht schaffen.`n`n
www.plueschdrache.de
");
}
if ($_GET['op']=='gewonnen')
{
    output("<h1>DU HAST GEWONNEN</h1>`$ Stolz gehst du zurück zur Spielhöhle`0");
    addnav("Zurück");
    addnav($str_backtext,$str_backlink);

}
//show_array($_POST);
page_footer();
?>

