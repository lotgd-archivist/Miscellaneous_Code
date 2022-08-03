<?
//*-------------------------*
//|        Scriptet by      |
//|       ï¿½*Amerilion*ï¿½     |
//|      greenmano@gmx.de   |
//*-------------------------*

//Sanela-Pack Version 1.1

require_once "common.php";
page_header("Der See");
//$session['user']['sanela']=unserialize($session['user']['sanela']);
output("`c`b`9Der S`ye`9e`n`n`c`b");
if ($HTTP_GET_VARS['op']==""){
    output("`c`KDu `lsc`Lhlenderst alleine auf den See am Waldrand zu, lï¿½sst die Hektik des Dorfplatzes hinter dir, und s`let`Kzt`n
`Kdi`lch `Lauf eine Bank an seinem Ufer. Du beobachtest, wie einige Abenteurer im See schwimmen, und denks`lt d`Kir,`n
`Kda`lss `Ldir ein Bad auch mal wieder ganz gut tun wï¿½rde. In der Nï¿½he soll auch noch eine `GTu`7rm`ï¿½ru`7i`Gne`L st`leh`Ken,`n
`Kwe`llc`Lhe allerdings hinter einigen Bï¿½schen versteckt ist. Du kannst keinen Weg dorthin entde`lck`Ken.`n
`KAl`lle`Lrdings lï¿½dt ein kleiner Weg zum `2Hï¿½`@g`2el`L zum Spaziergan`lg e`Kin.`n`n`n`c");
    addcommentary();
    viewcommentary("sanelasee","Sprechen",10,"spricht");
    addnav("Auswahl");
    addnav("Gehe schwimmen","sanelasee.php?op=schwimm");
    addnav("Zum Hï¿½gel","huegel.php");
    addnav("Zurï¿½ck zum Dorf","sanela.php");
}
else if($HTTP_GET_VARS['op']=="schwimm"){
    if ($session['user']['sanela']['schwimm'] <1){
        output("`n`9Du legst dein Gewand ab und lï¿½sst dich in das Wasser gleiten, schwimmst einge Zeit und...`n");
        switch (e_rand(1,10)){
            case 1:
            output("bekommst einen Krampf im Bein! Mit letzter Kraft versuchst du zum Ufer zu schwimmen, schaffst es aber nichtmehr.");
            $session['user']['alive']=0;
            $session['user']['hitpoints']=0;
            addnews($session['user']['name']."`5 ist ertrunken!");
            $session['user']['sanela']['schwimm']++;
            if(e_rand(1,2)==1){
                output("`nEine Seenixe schenkt dir einen Edelstein");
                $session['user']['gems']++;
            }
            addnav("Die Schatten","shades.php");
            break;
            case 2:
            case 3:
            output("bemerkst wï¿½hrend du dich wieder anziehst bei deinen Kleidern einen Edelstein.");
            $session['user']['gems']++;
            $session['user']['sanela']['schwimm']++;
            break;
            case 4:
            output("bemerkst ein kleines Kind, welches sich wohl ï¿½berschï¿½tzt hat. Du schwimmst zu ihm und rettest es.");
            output("Die Mutter berichtet ï¿½berall von deiner guten Tat`n`n`^Du bekommst 2 Charmepunkte");
            $session['user']['charm']+=2;
            $session['user']['sanela']['schwimm']++;
            break;
            case 5:
            case 6:
            case 7:
            output("hast eine schï¿½ne Zeit und sauber bist du auch geworden.");
            $session['user']['sanela']['schwimm']++;
            break;
            case 8:
            case 9:
            output("findest beim Tauchen einen Beutel mit Gold.`n`n`^Du bekommst 200 Gold");
            $session['user']['gold']+=200;
            $session['user']['sanela']['schwimm']++;
            break;
            case 10:
            $session['user']['sanela']['schwimm']++;
            /*if($session['user']['gems']>2){
                output("`^ dir fehlen zwei Edelsteine als du dich wieder anziehst.");
                $session['user']['gems']-=2;
            }else{
                output("`^ siehst wie jemand den du nicht leiden kannst auch schwimmen geht. Du gehst aus dem Wasser und nimmst");
                output("\"zufï¿½llig\" zwei seiner Edelsteine mit.");
                $session['user']['gems']+=2;
            }*/
            break;
        }
        if ($session[user][alive]==1){
            addnav("An den See","sanelasee.php");
    }else{
        output("`9 Du bist heute schon genug geschwommen.");
        addnav("Zurï¿½ck zum See","sanelasee.php");
    }
}
page_footer();
$session['user']['sanela']=serialize($session['user']['sanela']);
?> 