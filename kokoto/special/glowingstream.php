<?php
// überarbeitet von Tidus (www.kokoto.de)
if (!isset($session)) exit();
	if ($_GET['op']=='drink'){
	  $rand = mt_rand(1,10);
		output('`#Im Wissen, daß dieses Wasser dich auch umbringen könnte, willst du trotzdem die Chance wahrnehmen. Du kniest dich am Rand des Stroms nieder und nimmst einen langen, kräftigen Schluck von diesem kalten Wasser. Du fühlst Wärme von deiner Brust heraufziehen, ');
		switch ($rand){
		  case '1':
        output('`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt. `n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daß die Steine, die dir aufgefallen sind die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du.`n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst Dein Atem wird dünner und immer unregelmäßiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift. `n`n`^Du bist an den dunklen Kräften des Stroms gestorben.`n Da die Waldkreaturen die Gefahr dieses Platzes kennen, meiden sie ihn und deinen Körper als Nahrungsquelle. Du behältst dein Gold.`n Die Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.`n Du kannst morgen wieder kämpfen.');
                                $session['user']['specialinc']='';
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				addnav('Tägliche News','news.php');
				addnews($session['user']['name'].' hat seltsame Kräfte im Wald entdeckt und wurde nie wieder gesehen.');
			break;
			case '2':
        output('`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt. `n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daß die Steine, die dir aufgefallen sind die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du. `n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst. Dein Atem wird dünner und immer unregelmäßiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift.`n`n Als du deinen letzten Atem aushauchst, hörst du ein entferntes leises Kichern. Du findest die Kraft, die Augen zu öffnen und siehst eine kleine Fee über deinem Gesicht schweben, die unachtsam ihren Feenstaub überall über dich verstreut. Dieser gibt dir genug Kraft, dich wieder aufzurappeln. Dein abruptes Aufstehen erschreckt die Fee, und noch bevor du die Möglichkeit hast, ihr zu danken, fliegt sie davon. `n`n`^Du bist dem Tod knapp entkommen! Du hast einen Waldkampf und die meisten deiner Lebenspunkte verloren.');
				if ($session['user']['turns']>0) $session['user']['turns']--;
				if ($session['user']['hitpoints']>($session['user']['hitpoints'].1)) $session['user']['hitpoints']=round($session['user']['hitpoints'].1,0);
                                $session['user']['specialinc']='';
			break;
			case '3':
			  output('du fühlst dich GESTÄRKT!');
				output('`n`n`^Deine Lebenspunkte wurden aufgefüllt und du spürst die Kraft für einen weiteren Waldkampf.');
				if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$session['user']['turns']++;
                                $session['user']['specialinc']='';
				break;
			case '4':
			  output('du fühlst deine SINNE GESCHÄRFT! Du bemerkst unter den Kieselsteinen am Bach etwas glitzern.');
				output('`n`n`^Du findest einen EDELSTEIN!');
				$session['user']['gems']++;
                                $session['user']['specialinc']='';
				//debuglog("found 1 gem by the stream");
				break;
			case '5':
			case '6':
			case '7':
			  output('du fühlst dich VOLLER ENERGIE!');
				output('`n`n`^Du bekommst einen zusätzlichen Waldkampf!');
                                $session['user']['specialinc']='';
				$session['user']['turns']++;
				break;
			case '8':
                        case '9':
                        case '10':
			  output('du fühlst dich GESUND!');
                          $session['user']['specialinc']='';
				output('`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt.');
				if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
	}else if ($_GET['op']=='nodrink'){
        $session['user']['specialinc']='';
	  output('`#Weil du die verhängnisvollen Kräfte in diesem Wasser fürchtest, entschließt du dich, es nicht zu trinken und gehst zurück in den Wald.');
	}else{
  output('`#Du entdeckst einen schmalen Strom schwach glühenden Wassers, das über runde, glatte, weiße Steine blubbert. Du kannst eine magische ');
	output('Kraft in diesem Wasser fühlen. Es zu trinken, könnte ungeahnte Kräfte in dir freisetzen - oder es könnte dich zum völligen Krüppel machen. Wagst du es, von dem Wasser zu trinken?');
	output("`n`n<a href='forest.php?op=drink'>Trinken</a>`n<a href='forest.php?op=nodrink'>Nicht trinken</a>",true);
	addnav('Trinken','forest.php?op=drink');
	addnav('Nicht Trinken','forest.php?op=nodrink');
	addnav('','forest.php?op=drink');
	addnav('','forest.php?op=nodrink');
	$session['user']['specialinc']='glowingstream.php';
}

?>