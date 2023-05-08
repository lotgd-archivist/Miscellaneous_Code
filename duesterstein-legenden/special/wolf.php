
<?
//Author:  Hadriel
//small adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

    output("`nDu gehst fröhlich an einer Lichtung vorbei. Plötzlich greift Dich
    ein Wolf an und verletzt Dich.`n");
    $lhp = e_rand(0,($session['user']['hitpoints']-1));
    output("`4`nDu verlierst $lhp Deiner Lebenspunkte und durch die Kratzer
    verlierst Du an `5Charme!");
    $session['user']['hitpoints']-= $lhp;
    $session['user']['charm']--;

?>


