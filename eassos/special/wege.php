
<?php
/*
    __                             __         ____   ______
   / /  ___  ____ ____  ____  ____/ /  ____  / __/  / ____/________  _____
  / /  / _ \/ __ `/ _ \/ __ \/ __  /  / __ \/ /_   / __/ / ___/ __ \/ ___/
 / /__/  __/ /_/ /  __/ / / / /_/ /  / /_/ / __/  / /___/ /  / /_/ (__  )
/____/\___/\__, /\___/_/ /_/\__,_/   \____/_/    /_____/_/   \____/____/
          /____/


                     \|||/
                    (o o)
           ,----ooO--(_)-------------.
           |         Wege            |
           |     Idee by Ajuba       |
           |  Code by Rhebanna&Ajuba |
           |   Texte by Rhebanna     |
           |    www.lottr.de/logd    |
           '----------------Ooo------'
                   |__|__|
                    || ||
                   ooO Ooo
Bild in das images Verzeichnis im logd root laden
*/



page_header("Wege");

if ($_GET[op]=="")
{
        output('`]Neugierig läufst du durch den `2W`@al`2d`], als du an eine lichte Stelle kommst. Von hier
        gehen mehrere `aWege `]ab. Einer führt auf einen Hügel, ein anderer zu einem Dickicht, und der
        dritte führt dich auf einen kleinen, unscheinbaren Trampelpfad.');

 switch(e_rand(1,45)){
case 1:
        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";
break;
case 2:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 3:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 4:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 5:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 6:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 7:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 8:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op3=");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 9:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 10:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 11:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 12:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 13:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 14:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 15:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 16:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 17:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 18:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 19:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 20:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 21:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 22:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 23:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 24:

        addnav("Hügel","forest.php?op=1");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 25:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 26:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 27:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 28:

        addnav("Hügel","forest.php?op=2");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 29:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 30:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 31:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 32:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";
break;

case 33:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 34:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 35:

        addnav("Hügel","forest.php?op=3");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 36:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

break;

case 37:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=1");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 38:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 39:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=2");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 40:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 41:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=3");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 42:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=4");
        $session[user][specialinc]="wege.php";

break;

case 43:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=3");
        $session[user][specialinc]="wege.php";

break;

case 44:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=2");
        $session[user][specialinc]="wege.php";

break;

case 45:

        addnav("Hügel","forest.php?op=4");
        addnav("Dickicht","forest.php?op=4");
        addnav("Pfad","forest.php?op=1");
        $session[user][specialinc]="wege.php";

}
}
if ($_GET[op]=="1")
{
        output('`]Plötzlich trittst du in einen `aAme`Sis`ae`Tn`ah`Sau`afen`]. Erschrocken beobachtest du hunderte von ihnen, wie sie
        deine Beine hoch krabbeln. Obwohl du los rennst, wie vom `[Dä`~m`[on `]verfolgt, kannst du sie nicht alle abschütteln.
        Mit wunden Beinen kommst du aus dem Wald zurück. `nDu verlierst `e10 `]Charmepunkte.');
        
        $session[user][charm]-=10;
        addnews("".$session['user']['name']."`@ musste feststellen, wie bissig Ameisen sind.");
        
        addnav("in den Wald","forest.php");
}
if ($_GET[op]=="2")
{
        output('`]Du schreist auf, als du das Gleichgewicht verlierst und haltlos einen `aAb`Tha`ang `]hinunter stürzt. Deine
        eigene Waffe durchbohrt dich. `nDu bist tot.');


        addnews("".$session['user']['name']."`@ starb durch die eigene Hand");
        $session[user][alive]=false;
        $session[user][hitpoints]=0;
        addnav("Zu den News","news.php");
}
if ($_GET[op]=="3")
{
         output('`]Überrascht bleibst du stehen. Vor dir rauscht ein wunderschöner `3Wa`#sserf`3all `]in die Tiefe. Begeistert
        legst du deine Kleidung ab und erfrischst dich eine ganze Weile an dem kühlen Nass. Du fühlst dich wieder frisch
        und sauber. `nDu bekommst `^3 `]Waldkämpfe dazu. `nAußerdem bekommst du `e5 `]Charmpunkte.');
        
        $session[user][charm]+=5;
        $session[user][turns]+=3;
        addnews("".$session['user']['name']."`@ hüpft munter und gut gelaunt durch den Wald.");
        
        addnav("in den Wald","forest.php");
}
if ($_GET[op]=="4")
{
        $session[user][specialinc]="wege.php";
        output("<img src='images/rancor.jpg' align='right'>",true);
        output('`]Nachdem du stundenlang herum gerannt bist, stehst du plötzlich vor einem `)F`7e`&ls`{e`7n`], in den eine `STür
        `]eingelassen ist. Neugierig öffnest du sie und gehst den dunklen Gang dahinter entlang, doch enttäuscht musst du
        fest stellen, dass dies eine Sackgasse ist. Auf dem Weg zurück steht unvermittelt ein wütend schnaubender `ZRankor
        `]vor dir und versperrt dir den Rückweg. Hinter dir der im Nichts endende Weg, hast du keine Möglichkeit zur
        Flucht und musst dich dem `4Ka`Nm`4pf `]mit diesem Ungeheuer stellen.');

$maxh = $session['user']['maxhitpoints'];
$att = $session['user']['attack'];
$def = $session['user']['defence'];

$badguy = array(
"creaturename"=>"`Z Rankor `0"
,"creaturelevel"=>$session['user']['level']
,"creatureweapon"=>"`aPranken`0"
,"creatureattack"=>$att +=2
,"creaturedefense"=>$def +=5
,"creaturehealth"=>$max +=200
,"diddamage"=>0);
$session['user']['badguy']=createstring($badguy);



$HTTP_GET_VARS['op']="fight";

}

if ($HTTP_GET_VARS[op]=="run"){

        output("`c`b`\$Es gelingt dir nicht zu entkommen.`0`b`c`n`n");
        $battle=true;
}

if ($HTTP_GET_VARS['op']=="fight"){
    $battle=true;
     $session[user][specialinc]="wege.php";
}

if ($battle) {
    include("battle.php");
        if ($victory){
             $badguy=array();
               $session['user']['badguy']="";
     $session[user][specialinc]="";
$session['user']['experience']+=1000;
$session['user']['gems']+=5;
addnews("".$session['user']['name']."`@ lässt sich auch von einem Rankor nicht erschrecken.");
output("`]Erschöpft, aber glücklich kannst du wieder in den `2W`@al`2d `]gehen. Vorher untersuchst du noch die Leiche des
`ZUng`2eheu`Zers`]. `nDabei findest du `e5 `]Edelsteine in dessen Taschen.`nStolz kehrst du in den Wald zurück.`n`0");
}
elseif($defeat)
        {
        $badguy=array();

                output("Du wurdest von ".$badguy['creaturename']." besiegt.");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                addnews("".$session['user']['name']."`@ diente einem Rankor als Frühstück.");





                addnav("Tägliche News","news.php");
}
else
{
fightnav(true,true);
  }
}
?>

