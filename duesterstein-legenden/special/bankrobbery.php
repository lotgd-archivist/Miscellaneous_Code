
<?
// idea of raven @ www.rabenthal.de
// 29.10.2004
if (!isset($session)) exit();
$session[user][specialinc] = "bankrobbery.php";

output("`n`^Während Du im Wald Monster jagst stutzt Du auf einmal - was sind das denn da 
    für dunke Gestalten, die sich da unweit von Dir durchs Gebüsch schlagen?`n`n
    Du entdeckst den schweren Sack, aus dem einzelne Goldstücke herausfallen.
    Du erschrickst und denkst Dir, daß die doch wohl nicht etwa...`n`n`0");

if ($session[user][goldinbank]<10){
    output("`^Augenblicklich beruhigst Du Dich aber wieder, fällt Dir ein, daß Du eh 
        nur `4".$session[user][goldinbank]." `^Goldstücke auf der Bank hast.
        Beruhigt gehst Du weiter jagen.");
}else{
    $chance = e_rand(1,50);

    if ($chance == 1){
        output("`^In Panik rennst Du zur Bank und entdeckst den Bankier bewußtlos auf dem Boden liegen.
            Du rennst zu Deinem Bankfach und findest es aufgebrochen vor. 
            `n`nAll Dein Gold ist geraubt worden.
            Verzweifelt gehst Du zurück in den Wald 
            `n`n`4\"Was soll nun aus mir werden?\"`^ denkst Du bei Dir.`0");
        $session[user][goldinbank]=0;
        addnews($session[user][name]." wurde bei einem Bankraub das ganze Gold aus der Bank geraubt."); 
    }else{
        $restgold = abs($session[user][goldinbank]*0.9);
        output("`^Du setzt an ins Dorf zu rennen und zu überprüfen, ob die Bank beraubt wurde doch da siehst Du
            die tapferen Helden der `4Dorfwachen `^schon die Schufte einholen und überwaltigen.
            `n`n`4\"Puh - das ist ja nochmal gut gegangen\" `^denkst Du bei Dir, als die Wachen mit dem Goldsack
            von `4".$session[user][goldinbank]." `^Goldstücken bei Dir ankommen und ganz frech 
            10 % Finderlohn verlangen.`n`n
            Bevor Du Dich versiehst, greifen die Wachen
            in den Sack, nehmen das Gold und ziehen lachend von dannen.
            `n`n`4\"Na klasse...\" `^denkst Du bei Dir
            und bringst den Sack mit den `4".$restgold." `^Goldstücken mit saurer Miene 
            zurück in die Bank, um das Gold wieder einzuzahlen.");
        $session[user][goldinbank]=$restgold;
        addnews($session[user][name]." wäre beinahe Opfer eines miesen Bankraubes geworden."); 
    }
}
addnav("Zurück","forest.php");
$session[user][specialinc]="";

?>


