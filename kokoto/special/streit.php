<?php

//Idee von Zinsis (karotte.php im orginalen hier abgewandelt)
//Umsetzung von LonelyUnicorn
//Übrarbeitet by Tidus für www.kokoto.de (Danke an Zinsis für ihre guten ideen ;D)
if (!isset($session)) exit();

output('`n`b`c`$Der Streit`c`b`n`n');
if ($_GET['op']=='schlichten'){
        output("`^Du bist überzeugt davon, dass ein legendärer 2 Meter Zwerg, einer deiner Vorfahren war oder zumindest einer deiner Vorfahren ihn kannte und bist dir deshalb sicher, dass du es schaffen wirst, genauso wie er, einen Streit zwischen einem Troll und einem Zwerg zu schlichten. Beherzt trittst du auf die beiden zu und bist in dem Moment tatsächlich genauso naiv wie er damals. Mit fröhlichem Unterton bittest du die beiden dir doch zu erklären, worum es geht und behauptest, das es sicherlich eine Lösung für das Problem gibt.`n Die beiden schauen dich völlig entgeistert an, und dann ...`n`n");
    switch(mt_rand(1,5)){
    case 1:
        output('`8tritt der Troll auf dich zu, packt dich am Kragen und grollt dir so was wie `4"Dein Leben dir nicht heilig, sonst du nicht wärst hier. Gehen, sonst ich vergessen mich!" `8Darauf fliegst du auch schon im hohen Bogen durch die Luft. Die Landung auf einem recht spitzen Stein bereit dir arge Schmerzen in deinem Allerwertesten.`n`n `4Du verlierst beinahe alle Lebenspunkte');
        $session['user']['hitpoints']=2;
        addnews("`8".$session['user']['name']."`8 sollte sich nicht immer in fremde Angelegenheiten einmischen!");
        $session['user']['specialinc']='';
        break;
    case 2:
        output('`8tritt der Zwerg auf dich zu, schwingt seine Axt und grinst dabei ein klein wenig diabolisch. "`QHey Du," `8schaut er zu dir hoch "`Qmach, dass du Land gewinnst, bevor meine Axt deinen Knien guten Tag sagt!" Du zitterst, nickst und rennst davon, du rennst und rennst, bis du das Gefühl hast, seit Stunden nicht mehr geatmet zu haben. `8Nach dem du wieder relativ normal atmest , fällt dir auf das du wirklich sehr lang gerannt bist.`n`n `4Du verlierst zwei Kampfrunden!');
        $session['user']['turns']+=2;
        $session['user']['specialinc']='';
        addnews("`8".$session['user']['name']."`8 legte einen wirklich guten Sprint hin!`0");
        break;

    case 3:
        output('`8sind die beiden der Meinung, dass du es nicht Wert bist, dir weiter Beachtung zu schenken und wenden sich wieder ihrem endlosen Streit, bei dem eigentlich keiner mehr weiß, worum es eigentlich geht, zu. Du startest noch einige Versuche, die Aufmerksamkeit auf dich zu ziehen, aber es nützt nichts. Deprimiert darüber verziehst du dich wieder und suchst nach Gegnern, mit dem Wissen, dass wenigstens die dir die Aufmerksamkeit schenken, die dir zusteht.');
        $session['user']['specialinc']='';
        break;
    case 4:
    $exp=$session['user']['experience']0.15;
        output("`8holen die beiden tief Luft und fangen an zu plappern. Du hast Mühe, die ganzen Informationen aufzunehmen. Du nickst hier und da, schüttelst den Kopf, schaust nachdenklich drein, und als sie beide fertig sind, sagst du \"`RNa, dann ist doch alles gut.\" `8Sie schauen dich an, dann sich gegenseitig, lachen und stiefeln jeder für sich in eine andere Richtung. Du fühlst dich wie der Held der Welt und läufst mit stolz geschwellter Brust wieder zurück in die Stadt.`n`n Du erhältst `^$exp Erfahrungspunkte!`8");
        $session['user']['experience']+=$exp;
        $session['user']['specialinc']='';
        break;
    case 5:
        output('`8packt der Troll dich, zieht dich hinter sich her und nimmt wenig Rücksicht darauf, dass du hier und da im Dickicht hängen bleibst. Der Zwerg latscht hinterher und flucht dabei munter weiter. Nach einer Weile bleibt der Troll stehen und zeigt auf einen Haufen Gold und Edelsteine. Darum scheint es in dem Streit zu gehen. Du starrst wie gebannt auf den Haufen, und als du merkst, dass sich die beiden wieder um ihren Streit kümmern, greifst du zu und rennst davon.`n`n Du erhältst `^300 Gold! und `%3 Edelsteine`8');
        $session['user']['gold']+=300;
		$session['user']['gems']+=3;
        $session['user']['specialinc']='';
        break;
    }
}else if ($_GET['op']=='leave'){
    output('`8Du weißt , dass man sich in einen Streit zwischen Trollen und Zwergen nicht einmischen sollte, weil man am Ende dann doch als der Dumme da steht und schleichst dich langsam wieder davon. Vielleicht sind die beiden ja morgen noch da, da kann man dann ja vielleicht mal was sagen, oder auch nicht.');
    $session['user']['specialinc']='';
}else{

    output('`8Nichts ahnend wie immer läufst du durch die Gegend auf der Suche nach Gegnern. Da hörst du plötzlich Stimmen, die sich streiten, ziemlich lauthals, also kaum zu überhören. Deine dir angeborene Neugierde treibt dich näher zu den Stimmen, natürlich vorsichtig, immer mit einem Bein auf der Flucht. Als du fast da bist, erkennst du das sich dort ein ziemlich großer Troll und ein ziemlich, na ja, eben kleiner Zwerg streiten. Es scheint um Steine zu gehen. Du kannst kaum etwas über den Sinn herausfinden, weil der Troll nur grollt und der Zwerg nur flucht. Du betrachtest den Streit eine Weile, bist dir nicht sicher, ob du da eingreifen sollst.');
    addnav("Den Streit schlichten","forest.php?op=schlichten");
    addnav("Umkehren","forest.php?op=leave");
    $session['user']['specialinc']='streit.php';
}
?>