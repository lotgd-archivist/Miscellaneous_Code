
<?
//Author:  Hadriel
//small adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

    output("`nDu gehst fröhlich an einigen Felsen vorbei. Plötzlich greift dich
    ein wilder Bergwolf an und verletzt Dich.`n");
    $lhp = e_rand(0,($session['user']['hitpoints']-1));
    output("`4`nDu verlierst $lhp deiner Lebenspunkte, und durch die Kratzer
    verlierst du an `5Charme!");
    $session['user']['hitpoints']-= $lhp;
    $session['user']['charm']--;

?>


