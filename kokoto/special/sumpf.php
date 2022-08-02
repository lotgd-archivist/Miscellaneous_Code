<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();
output('`n`#Du bemerkst, dass das Gelände immer sumpfiger wird.`0 Du hast es deutlich
schwerer, nun weiter voranzukommen.`0`n`n');
switch(mt_rand(1,4)){
    case '1':
    $decrease = round($session['user']['maxhitpoints']  0.15);
    output('Du bemerkst plötzlich ein stechen am Bein. Instinktiv wischt Du mit
    der Hand über die schmerzende Stelle und entledigst Dich so des fetten Blutegels,
    der von Dir genascht hat.');
    if (  ($session['user']['hitpoints']  $decrease) <= 0 ) {
        output('`n`QDa Du bereits gesundheitlich geschwächt bist, hast Du dem Blutegel
        nicht genug Abwehrkräfte entgegenzusetzen. Du stirbst an einer Blutvergiftung`0');
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold'] = 0;
        $session['user']['gems'] = 0;
        addnav('Tägliche News','news.php');
        addnews($session['user']['name'].' starb in den Sümpfen. Es war doch nur eine kleine Verletzung....');
    }
    else {
        output("`nMit dem Blutverlust leidet auch Deine Gesundheit ein wenig und
        `QDu verlierst $decrease Lebenspunkte.`0");
        $session['user']['hitpoints']-=$decrease;
        $session['user']['specialinc']='';
    }
    break;

    case '2':
    output('Nach einem anstrengenden Marsch kannst Du das sumpfige Gebiet endlich
    verlassen. Deine Kleidung ist zwar voller Matsch, aber sonst bist Du wohl auf.
    Als Du auf die Uhr siehst, stellst Du aber fest, dass Du einige Zeit im Sumpf
    gefangen warst. Du kannst heute nicht mehr soviel erledigen wie ursprünglich
    geplant.`0');
    output('`n`n`QDu verlierst 1 Waldkampf.`0');
    $session['user']['turns']-=1;
    $session['user']['specialinc']='';
    break;

    case '3':
    output('Du hast Angst davor, irgendwo im Sumpf stecken zu bleiben und entscheidest
    Dich daher, das Gebiet zu umwandern. "`QDas kostet mich zwar 1 Waldkampf, aber so
    bin ich sicher"`0 sagst Du zu Dir selbst.`0');
    $session['user']['turns']-=1;
    $session['user']['specialinc']='';
    break;

    case '4':
    //case 5:
    $exp=$session['user']['level']  mt_rand(10,50);
    output("Du überlegst, ob Du weitergehen sollst, denn der Sumpf birgt bestimmt
    Gefahren. Du erinnerst Dich an Deinen Mut und gehst voran. Schon bald bemerkst Du,
    dass Du eine Abkürzung zu dem Waldgebiet entdeckt hast, das Du nach Monstern
    durchsuchen wolltest. `n`2Durch die Abkürzung kannst Du heute einen zusätzlichen
    Waldkampf führen!`0 Das Wissen um diese Abkürzung bringt Dir auch `2$exp zusätzliche
    Erfahrungspunkte.`0");
    $session['user']['turns']+=1;
    $session['user']['experience']+=$exp;
    $session['user']['specialinc']='';
    break;
}
?>