
<?
/*
Portable Potions with clickable icons
Author: Lonnyl of http://www.pqcomp.com/logd
E-Mail: logd@pqcomp.com
*/

//if ($session['user']['potion']<5 and $session['user']['dragonkills']<10){
if ($session['user']['potion']<5 ) {
    output("Was für ein `^Glückstag`0 !!`n
    Du findest einen Heiltrank!");
    $session['user']['potion']+=1;
}else{
    redirect("forest.php?op=search");
}
?>


