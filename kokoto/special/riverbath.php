<?
//Author: Lonny Luberts  Web: http://www.pqcomp.com/logd
//tranferred into forest special
//with some modifications : gargamel @ www.rabenthal.de
//small modifications by Tidus www.kokoto.de
if (!isset($session)) exit();

if ($_GET['op']=='bath'){
    output('`n`8Schnell ziehst Du Dich aus und springst in den Bach. Als Du wieder
    heraussteigst fühlst Du Dich viel frischer und sauberer!`n`0');
    $was = mt_rand(1,5);
    switch ($was) {
        case '1':
        output('Zufrieden gehst Du weiter.`0');
        $session['user']['specialinc'] = '';
        break;
        case '2':
        output('`nFrisch gewaschen bekommst Du einen Charmepunkt.`0');
        $session['user']['charm']++;
        $session['user']['specialinc'] = '';
        break;
        case '3':
        output('`nIm Bachbett siehst Du plötzlich 5 Goldstücke funkeln.`0');
        $session['user']['gold']+= 5;
        $session['user']['specialinc'] = '';
        break;
        case '4':
        output('`nDeine Klamotten wurden versteckt während du gebadet hast, ohne das du es bemerkt hast!!`n`$Beim suchen verlierst du 1 Waldkampf da du deine Kleider nicht wieder unbeaufsichtigt rumliegen lassen wirst gewinnst du an Erfahrung!');
        $session['user']['turns']--;
        $session['user']['experience']+=200;
        $session['user']['specialinc'] = '';
        break;
        case '5':
        output('`n`PDu findest beim baden einen Edelstein!!`nFreudig ziehst du dich wieder an und setzt deine reise fort!');
        $session['user']['specialinc'] = '';
        break;
    }
}else if ($_GET['op']=='cont'){
    output('`n"Sauberes Wasser kann täuschen" denkst du und gehst weiter.`0');
    $session['user']['specialinc'] = '';
}else{
 output('`n`8Du kommst bei einem kleinen Bach vorbei. Das Wasser ist einladend
    sauber. Was für ein schöner Platz zum baden!`0');
    //abschluss intro
    addnav('Baden gehen','forest.php?op=bath');
    addnav('Weitergehen','forest.php?op=cont');
    $session['user']['specialinc'] = 'riverbath.php';
}
?>