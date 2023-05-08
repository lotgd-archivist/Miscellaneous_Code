
<?
//Author: Lonny Luberts  Web: http://www.pqcomp.com/logd
//small adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

    output("`nDu fällst!  Ohhhh! Direkt in ein `triesiges Schlammloch!`0  `n
    Du bist von oben bist unten mit Schlamm bedeckt und verlierst 2 Charmepunkte!");
    $session[user][charm]-=2;

?>


