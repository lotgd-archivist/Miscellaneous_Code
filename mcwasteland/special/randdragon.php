
<?php
/* Random Green Dragon Encounter v1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)
This is a simple "forest special" which helps to keep the main idea in mind, by giving any player an
encounter with the Green Dragon, and the results could be deadly.
The following names/locations are server-specific and should be changed:
    Plains of Al'Khadar (and reference to "plains")
    Domarr's Keep (the main city)

Version History
1.0 original version

german translation by anpera
some changes for my game - may not work with other versions!
*/

if (!isset($session)) exit();
$session['user']['specialinc']="randdragon.php";
if ($_GET['count']==3) {
    output("`tDer `@GrÃ¼ne Drachen`t hat genug von deinem Geschwafel. Er blÃ¤st dich mit einem FeuerstoÃŸ weg!`n`nDu fragst dich noch, was schlimmer ist, der Schmerz, oder der Gestank deines verbrennenden Fleischs. Aber das spielt keine Rolle. Das Reich der Schatten empfÃ¤ngt dich.`n`n`4Du wurdest vom `@GrÃ¼nen Drachen`4 gegrillt!`nDu verlierst 5% deiner Erfahrung und alles Gold.");
    addnews("`%".$session['user']['name']."`t wurde bei einer zufÃ¤lligen Begegnung im Wald vom `@GrÃ¼nen Drachen`t getÃ¶tet!");
    $session['user']['gold']=0;
    $session['user']['experience']=round($session['user']['experience']*.95,0);
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['specialinc']="";
    addnav("TÃ¤gliche News","news.php");
} else {
    switch($_GET['op']){
        case "":
            output("`tBei deinem Streifzug durch die WÃ¤lder hÃ¶rst du plÃ¶tzlich ein lautes BrÃ¼llen. Das GerÃ¤usch lÃ¤sst das Blut in deinen Adern gefrieren.`nEin tiefes Stampfen ist hinter dir zu hÃ¶ren. Starr vor Schreck fÃ¼hlst du einen StoÃŸ heiÃŸen Atem in deinem Nacken. Langsam drehst du dich um - und siehst einen riesigen `@GrÃ¼nen Drachen`t vor dir stehen. `n`nDas kÃ¶nnte Ã„rger geben...");
            addnav("Angreifen!","forest.php?op=slay");
            addnav("Um Gnade winseln","forest.php?op=cower");
            addnav("Rede dich raus","forest.php?op=talk");
            addnav("Lauf weg!","forest.php?op=flee");
            $session['user']['specialinc']="randdragon.php";
            break;    
        case "slay":
            output("`tDu hÃ¤ltst deine Waffe fest im Griff und bereitest dich auf den Angriff auf diese gewaltige Kreatur vor.`n`nDu brÃ¼llst einen Kampfschrei und springst auf den Drachen zu!`nDoch bevor deine Waffe den Drachen berÃ¼hrt, schlÃ¤gt er sie dir mit seinem Schwanz aus der Hand und spuckt dir seinen Feueratem entgegen. ");
            if($session['user']['level'] < 15){
                output("`n`nDer Strahl wirft dich zu Boden. Du kannst fÃ¼hlen, wie sich durch die groÃŸe Hitze schwere Blasen auf deiner Haut bilden.`nGeschwÃ¤cht schaust du zum `@GrÃ¼nen Drachen`t auf, der auf dich zu stolziert. ");
                if(rand(1,4)==1){
                    output("Er beugt sich gerade zu dir herunter, um dich zu verschlingen, als plÃ¶tzlich ein Pfeil scheinbar aus dem Nichts im Kopf des Drachen einschlÃ¤gt.`nMit einem entsetzlichen BrÃ¼llen fliegt der Drachen davon.`nDu kannst gerade noch einen Elfen auf dich zurennen sehen, dann wird dir schwarz vor Augen.`n`nEinige Zeit spÃ¤ter erwachst du auf einer Lichtung. Deine Wunden wurden geheilt, aber nichts kann die Verletzungen, die Drachenatem verursacht, wirklich vollstÃ¤ndig beseitigen.`nDu verlierst zwei Charmepunkte durch die Verbrennungen!");
                    addnews("`%".$session['user']['name']."`t hat irgendwie eine zufÃ¤llige Begegnung mit dem `@GrÃ¼nen Drachen`t Ã¼berlebt.");
                    $session['user']['charm']-=2;
                    $session['user']['turns']-=2;
                    if ($session['user']['turns'] < 0) $session['user']['turns']=0;
                    $session['user']['reputation']++;
                    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                    $session['user']['specialinc']="";
                }else{
                    output("`nDas ist das Letzte, was du siehst, bevor du in die ewige Dunkelheit gleitest.`n`n`4Du wurdest vom `@GrÃ¼nen Drachen`4 gefressen! Du verlierst 10% deiner Erfahrung und alles Gold.");
                    addnews("`%".$session['user']['name']."`t wurde bei einer zufÃ¤lligen Begegnung im Wald vom `@GrÃ¼nen Drachen`t getÃ¶tet!");
                    $session['user']['gold']=0;
                    $session['user']['experience']=round($session['user']['experience']*.9,0);
                    $session['user']['alive']=false;
                    $session['user']['hitpoints']=0;
                    $session['user']['specialinc']="";
                    addnav("TÃ¤gliche News","news.php");
                }
            } else {
                output("`nDu schaffst es im letzten Moment, dem FeuerstoÃŸ aus dem Weg zu stolpern, um dich kurz darauf Auge in Auge mit diesem gewaltigen Biest zu finden. SpÃ¶ttisch sagt er zu dir: \"`5Nich hier. Nicht jetzt.`t\"`nMit diesen Worten hebt der Drachen ab und steigt in die LÃ¼fte davon. Du bist wieder alleine mit deinen Gedanken.");
                $session['user']['specialinc']="";
            }
            break;
        case "cower":
            output("`tDu kauerst dich vor dem `@GrÃ¼nen Drachen`t zusammen und flehst um dein Leben. Der Drachen schnaubt dir erneut seinen heiÃŸen Atem entgegen. \"`5An jemandem, der so erbÃ¤rmlich jammert, wÃ¼rde ich mir sicher nur den Magen verderben.`n`5Hau schon ab.`@\"`nDu beschlieÃŸt, dass es das Beste ist, den Anweisungen der Kreatur zu folgen, und so hoppelst du verÃ¤ngstigt davon.");
            //addnews("`%".$session['user']['name']."`5 grovelled ".($session['user']['sex']?"her":"his")." way out of being dinner for the Green Dragon.");
            //$session['user']['charm']--;
            $session['user']['specialinc']="";
            $session['user']['reputation']-=2;
            break;
        case "talk":
            output("`tDu bist der Meinung, dass du diese Begegnung Ã¼berleben kÃ¶nntest, wenn es dir gelingt, den `@GrÃ¼nen Drachen`t in ein GesprÃ¤ch zu verwickeln. Jetzt brauchst du nur noch etwas, worÃ¼ber ihr reden kÃ¶nntet.`n");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count=0");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count=0");
            addnav("Violet","forest.php?op=violet&count=0");
            addnav("Seth","forest.php?op=seth&count=0");
            addnav("Cedrik","forest.php?op=cedrik&count=0");
            addnav("Degolburg","forest.php?op=city&count=0");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count=0");
            break;
        case "weather":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qAlso ".getsetting("weather","Wetter").", was hÃ¤ltst du von diesem Wetter?`t\"`nDer Drachen legt den Kopf schief und schaut dich an. Ein kurzes Schnauben schlÃ¤gt dir heiÃŸe, dampfende Luft entgegen.`n`nVielleicht interessiert den Drachen etwas anderes mehr?");
            addnav("Themen");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Degolburg","forest.php?op=city&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "dragon":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qDu bist also der GrÃ¼ne Drachen, hÃ¤?`t\"`nDer Drachen gibt ein ohrenbetÃ¤ubendes BrÃ¼llen von sich und leckt sich dann die Lippen. Vielleicht wÃ¤re ein anderes Thema besser zur Unterhaltung geeignet.");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Degolburg","forest.php?op=city&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "violet":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qDiese Violet ist ganz schÃ¶n sÃ¼ss, was?`t\"`nDer Drachen nickt. \"`5Ein schmackhafter, sÃ¼sser Happen wÃ¤re das Eine Schande, dass sie niemals die Schenke verlÃ¤sst. Aber vielleicht wirst du meinen Hunger solang stillen?.`t\"`nDu solltest dir etwas anderes ausdenken. Schnell!");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Degolburg","forest.php?op=city&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "seth":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qSeth ist ein netter Kerl, stimmts?`t\"`nDer Drachen dreht den Kopf in Gedanken.`n\"`5Ein bisschen schwer zu schlucken, wÃ¼rde ich wetten, aber er verlÃ¤sst ja nie die Schenke. Du dagegen hast es getan. `t\"`nDer Drachen schaut dich hungrig an. Zeit fÃ¼r eien Themenwechsel!");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Degolburg","forest.php?op=city&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "cedrik":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qCedrik ist ein mÃ¼rrischer alter Kerl, meinst du nicht auch?`t\"`nDer Drachen blinzelt langsam. \"`5Dieser Sterbliche interessiet mich nicht. Aber du bietest dich mir doch geradezu an.`t\"`nMan braucht keinen Gedankenleser, um zu erfahren, was dieses Ding denkt. Und du solltest seine Gedanken schnell in eine andere Richtung lenken.");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Degolburg","forest.php?op=city&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "city":
            $count=$_GET['count'];
            $count++;
            output("`t\"`qWusstest du, dass das Dorf Degolburg heiÃŸt? Ist ein ziemlich beeindruckendes Ã–rtchen!`t\"`nDer Drachen grÃ¶hlt laut.`n\"`5Diese stinkende Stadt ist mir ein Dorn im Auge, weiter nichts! Ich sollte ihre schwachen Mauern niederreiÃŸen und die Stadt nieerbrennen! Alle sollten `bmeinen`b Namen kennen und mich fÃ¼rchten!`t\"`nNun, es scheint so, als ob du die Kreatur verÃ¤rgert hast. Vielleicht hilft ein Themenwechsel.");
            addnav("Themen");
            addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
            addnav("G?Der GrÃ¼ne Drachen","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Name?","forest.php?op=name&count={$count}");
            addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
            break;
        case "stutter":
            $count=$_GET['count'];
            $count++;
            output("`tDu versuchst, ein intelligentes Thema zu finden, aber stattdessen stotterst du nur unkontrolliert vor dich hin. Der Drachen rollt dramatisch mit den Augen. Er schlÃ¤gt dich mit seinem Schwanz auf den Hinterkopf und du wirst ohnmÃ¤chtig.`n`nEinige Zeit spÃ¤ter wachst du mit einer gewaltigen Beule am Kopf wieder auf.`n");
            if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']*.1) {
                $session['user']['hitpoints']=round($session['user']['maxhitpoints']*.1);
            } else {
                $session['user']['hitpoints']=1;
            }        
            $session['user']['turns']--;
            if ($session['user']['turns'] < 0) $session['user']['turns']=0;
            addnews("`%".$session['user']['name']."`t hat irgendwie eine zufÃ¤llige Begegnung mit dem `@GrÃ¼nen Drachen`t Ã¼berlebt.");
            $session['user']['reputation']++;
            $session['user']['specialinc']="";
            break;
        case "name":
            output("`t\"`qWie ist dein Name, oh mÃ¤chtiger Drachen?`t\"`nDer Drachen betrachtet dich ernst. \"`5Du wÃ¤rst nicht in der Lage, ihn richtig auszusprechen. Es sind nur wenige in dieser Welt Ã¼brig, die das kÃ¶nnen, denn es verlangt die Sprachfertigkeit eines ausgewachsenen Drachen, von denen es nur noch wenige gibt. Unsere einst groÃŸe und stolze Rasse wurde von den niederen Rassen zu Aasfressern reduziert, aus Angst, wir kÃ¶nnten sie alle vernichten.`t\"`nDer Drachen schaut einen Moment weg, dann wendet er sich dir erneut zu. \"`5Drachen haben nur getÃ¶tet, was wir als Nahrung brauchten. Jetzt tÃ¶ten wir, um zu Ã¼berleben.`t\"`n`n\"`5Weiche von mir, bevor ich mich entschlieÃŸe, dich ohne Grund zu tÃ¶ten.`t\"`nDu hÃ¤ltst es fÃ¼r eine gute Idee, dich zu beeilen, bevor es sich der Drache anders Ã¼berlegt und einen Snack aus dir macht.");
            addnews("`%".$session['user']['name']."`t hat irgendwie eine zufÃ¤llige Begegnung mit dem `@GrÃ¼nen Drachen`t Ã¼berlebt.");
            $session['user']['specialinc']="";
            break;
        case "flee":
            $results=rand(1,4);
            if ($results==1) {
                output("`tDu drehst dich um und flÃ¼chtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hÃ¶rst keinen Verfolger hinter dir.`nDu hÃ¤ltst an, um dich umzudrehen. Keine Spur vom Drachen!`nDu hast wirklich GlÃ¼ck gehabt.");
                addnews("`%".$session['user']['name']."`t hat irgendwie eine zufÃ¤llige Begegnung mit dem `@GrÃ¼nen Drachen`t Ã¼berlebt.");
                $session['user']['specialinc']="";
                $session['user']['reputation']--;
            } elseif ($results==4) {
                output("`tDu drehst dich um und flÃ¼chtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hÃ¶rst keinen Verfolger hinter dir.`nDu hÃ¤ltst an, um dich umzudrehen. Keine Spur vom Drachen!`nDu glaubst schon, du bist dem Biest entkommen und drehst dich wieder um. Und da steht der Drachen direkt vor dir und sein weit aufgerissene Maul rast auf dich zu.`nBevor du Zeit hast, zu reagieren, hat dich der Drachen verspeist..`4Du bist gestorben!`nDu verlierst 10% deiner Erfahrung und all dein Gold.");
                addnews("`%".$session['user']['name']."`t wurde bei einer zufÃ¤lligen Begegnung im Wald vom `@GrÃ¼nen Drachen`t getÃ¶tet!");
                $session['user']['gold']=0;
                $session['user']['experience']=round($session['user']['experience']*.9,0);
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['specialinc']="";
                addnav("TÃ¤gliche News","news.php");
            } else {
                output("`tDu drehst dich um um vor der Macht des Drachen zu fliehen. WÃ¤hrend du rennst, fÃ¼hlst du plÃ¶tzlich, wie du von einer Welle der Hitze umschlossen wirst. Der Drachen hat dich mit einem FeuerstoÃŸ erwischt!");
                $damage=e_rand(round($session['user']['maxhitpoints']*.5,0),$session['user']['maxhitpoints']);
                output("Du verlierst ".$damage." Lebenspunkte durch diesen Treffer!`n");
                $session['user']['hitpoints']-=$damage;
                if ($session['user']['hitpoints'] < 1) {
                    $session['user']['hitpoints']=0;
                    output("`4Du bist gestorben!`nDu verlierst 10% deiner Erfahrungspunkte und alles Gold!");
                    addnews("`%".$session['user']['name']."`t wurde bei einer zufÃ¤lligen Begegnung im Wald vom `@GrÃ¼nen Drachen`t getÃ¶tet!");
                    $session['user']['gold']=0;
                    $session['user']['experience']=round($session['user']['experience']*.9,0);
                    $session['user']['alive']=false;
                    $session['user']['specialinc']="";
                    addnav("TÃ¤gliche News","news.php");
                } else {
                    output("Du rollst dich auf dem Boden, um das Feuer zu lÃ¶schen. Nachdem du festgestellt hast, dass du nicht tot bist, blickst du dich nach dem Drachen um. Doch der ist verschwunden. Hat er dich absichtlich nicht getÃ¶tet? Oder war er bloÃŸ der Meinung, dass du jetzt verkocht bist?`nDie Antwort wirst du nie erfahren, so machst du dich wieder auf den Weg. Ein Besuch beim Heiler dÃ¼rfte jetzt erstmal an der Reihe sein.");
                    addnews("`%".$session['user']['name']."`t hat irgendwie eine zufÃ¤llige Begegnung mit dem `@GrÃ¼nen Drachen`t Ã¼berlebt.");
                    $session['user']['specialinc']="";
                }
            }
            break;
    }
}
?>

