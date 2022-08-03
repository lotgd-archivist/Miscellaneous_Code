<?
//07052006 by -DoM (http://my-logd.com/motwd) for MoT (http://my-logd.com/mot)
// Idea by Cadderly (Player from MotWD)
if (!isset($session)) exit();
$fn = "forest.php";
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
switch ($_GET['op']){
    default:
        $session['user']['specialinc']="baumhirten.php";
        bild("baumhirte.jpg");
        output("`@Du gehst durch den finsteren Wald, Dein Herz schlägt schneller als gewöhnlich, Dein Atem ist schnell und 
                kurz, irgendwas stimmt hier nicht.`n`n
                Du bleibst stehen und als Du Dich umblickst siehst Du rings um Dich herum viele Augenpaare die Dich anstarren. 
                Dich beschleicht ein mulmiges Gefühl. Plötzlich hörst Du links neben Dir ein lautes Knacken, schnell wendest Du 
                den Blick dorthin, die Augenpaare starren dich immer noch an und du spürst wie dir langsam kalter Angstschweiß 
                auf die Stirn läuft. Als Du dann siehst was das knacken verursacht hat hast Du das Gefühl als würde Dir dein Herz 
                stehen bleiben. Vor Dir erblickst Du einen Baumhirten der langsam auf Dich zukommt.`n`n
                `QWillst Du lieber warten oder nimmst Du die Beine in die Hand und rennst?");
        addnav("`@Ich warte",$fn."?op=warten");
        addnav("`\$Renn um dein Leben",$fn."?op=rennen");
    break;
    case "rennen":
        $session['user']['specialinc']="";
        switch (e_rand(1,4)){
            case 1:
                bild("baumhirte.jpg");
                $exp = round($session['user']['experience']*0.05);
                output("`QDu versuchst wegzurennen, doch die Augenpaare bewegen sich hastig auf Dich zu, ein Rudel Wölfe kommt aus den 
                        Büschen gesprungen und fällt über Dich her und zerfleischen dich!`n`n
                        `\$Du bist tot und kannst Morgen weiter spielen!`n
                        Du verlierst all dein `^Gold`\$ und `^".$exp."`\$ Erfahrung");
                addnews($session['user']['name']."`\$ wurde im besonders dunklen Teil des Waldes, von einem Rudel Wölfen zerfleischt.");
                $session['user']['alive']=false;
                $session['user']['gold']=0;
                $session['user']['hitpoints']=0;
                $session['user']['experience']-=$exp;
                addnav("Tägliche News","news.php");
            break;
            case 2:
            case 3:
            case 4:
                $wk = e_rand(1,3);
                if ($wk==1){
                    $text = "Waldkampf";
                }else{
                    $text = "Waldkämpfe";
                }
                output("`QDu rennst so schnell du kannst davon! Zu welchen Lebewesen die Augenpaare gehören, kannst du nicht 
                        mehr erkennen!`n`n
                        `\$Du bist so ausser Atem, dass du dich erstmal ausruhen musst. Du verlierst `^".$wk."`\$ $text");
                if ($session['user']['turns']>$wk){
                    $session['user']['turns']-=$wk;
                }else{
                    $session['user']['turns']=0;
                }
            break;
        }
    break;
    case "warten":
        $session['user']['specialinc']="baumhirten.php";
        switch ($_GET['act']){
            default:
                bild("baumhirte.jpg");
                output("`@Mutig wie du bist wartest Du auf den Baumhirten, viele Geschichten hast Du schon über Sie gehört doch sehen tust 
                        Du sie jetzt zum ersten Mal. Langsam fangen Deine Knie an zu schlottern, hinter dir vernimmst Du ein jaulen, von 
                        rechts ein knurren, und dann weißt Du es, die Augen gehören zu Wölfen.`n`n
                        Sie haben Dich umstellt und wollen Dich gerade anspringen als der Baumhirte ein lautes Knacken von sich gibt und 
                        langsam beginnt zu sprechen.`n`n
                        `&\"Lasst von diesem Wesen ab Ihr habt heute schon genug gefressen.\"`@`n`n
                        Die Stimme des Baumhirten klingt alt, kalt und grausam und dir läuft ein eisiger Schauer über den Rücken, als 
                        Du dann noch erkennst das der Baum ein Gesicht hat mit 2 riesigen Augen die Dich anglotzen und mit einer Nase 
                        die so krumm ist das selbst eine Hexe neidisch wäre fällst Du in Ohnmacht.");
                addnav("Weiter",$fn."?op=warten&act=aufwachen");
            break;
            case "aufwachen":
                switch (e_rand(1,4)){
                    case 1:
                        $session['user']['specialinc']="";
                        output("`@Als Du Deine Augen wieder aufschlägst befindest Du Dich an einem noch finsterem Ort als zuvor, 
                                neugierig blickst Du Dich um und dieser Ort kommt Dir merkwürdig bekannt vor, Du erblickst Dein 
                                eigenes Grab auf dem geschrieben steht: `n`n");
                        bild("grabstein.jpg");
                        output("`c`&\"Hier ruht ein Tapferer Abenteurer der versuchte sich einen Baumhirten in den weg zu stellen 
                                und von diesem ausversehen zertreten wurde.\"`n`n`c
                                `\$Du bist tot und kannst Morgen weiter spielen.");
                        addnews($session['user']['name']."`\$ wurde von einem Baumhirten nieder getrampelt!!!`n`QMan könnte ".($session['user']['sex']?"Sie":"Ihn")." nun prima als Läufer für den Hausflur nutzen.");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        addnav("Tägliche News","news.php");
                    break;
                    case 2:
                    case 3:
                    case 4:
                        bild("hirte.jpg");
                        $session['user']['specialinc']="baumhirten.php";
                        output("`@Als Du Deine Augen öffnest und um dich herum Äste und den Himmel siehst, atmest Du erleichtert aus 
                                und glaubst Du hattest nur einen bösen Traum. Du entschließt Dich zu erheben und kannst Dich gerade 
                                noch festhalten als Du bemerkst, dass Du in luftiger Höhe bist und anscheinend in der Hand, die sehr 
                                verwurzelt aussieht, des Baumhirten bist.`n`n
                                Sofort bekommst Du wieder Panik und wendest dich hilfesuchend um, doch schnell stellst Du fest, dass 
                                Dir hier niemand helfen kann, also nimmst Du Deinen Mut zusammen und blickst zum Baumhirten auf und 
                                fragst:`n`n
                                `&\"Wohin bringst Du mich, Wächter des Waldes?.\"`@`n`n 
                                Der Baumhirte blickt zu Dir hinunter und wenn Deine Augen Dir keinen Streich spielen könntest Du 
                                meinen ein lächeln gesehen zu haben, schließlich antwortet der Baumhirte:`n`n
                                `&\"Du bist mutig, in meinem See sitzt ein kleiner Drache und nimmt mir mein Wasser, da er für mich 
                                zu schnell ist wirst Du ihn für mich erlegen.\"`@`n`n
                                Du schluckst und Dich überkommt wieder eine Panik die Dich wieder ohnmächtig werden lässt. Einen 
                                Augenblick später öffnest Du erneut die Augen und du liegst auf dem Boden, vor Dir siehst Du einen 
                                kleinen See, der kaum größer ist als der heimische in Nightwood. Der Baumhirte steht hinter Dir 
                                und deutet auf einen Felsen wo ein kleiner Drache, gerade mal 1 Meter groß, sitzt und ein Sonnenbad 
                                nimmt. Verwirrt blickst Du zum Baumhirten hoch. `#\"Der kleine Drache da macht Dir Angst?\"`@`n`n
                                Der Baumhirte grummelt leicht und nickt: `&\"Er spuckt Feuer\"`@`n`n
                                Wie durch Zufall holt der Drache tief Luft und aus seinem kleinen Maul kommt Feuer geschossen. 
                                Lauthals fängst du an zu lachen und gehst zum Drachen hinüber der Dich anblickt und gespielt 
                                gefährlich sein Maul aufreißt.`n`n
                                `QWas wirst Du wohl tun?");
                        addnav("Gehe zu dem Drachen",$fn."?op=drachen");
                        addnav("Renn weg",$fn."?op=wegrenn");
                    break;
                }
            break;
        }
    break;
    case "drachen":
        $session['user']['specialinc']="baumhirten.php";
        bild("lildragon.jpg");
        output("`@Auf der Suche nach Ehre und Ruhm näherst Du dich dem Babydrachen, während im See munter die Fische springen und 
                der Baumhirte Dich beobachtet.`n`n
                Vorsichtig und langsam näherst Du Dich dem Feuerspuckendem Ungeheuer welches erneut scharf die Luft einsaugt und 
                niesen muss, worauf hin kleine Rauchwolken aus den Nasenlöchern kommen. Du beschließt das Untier zu Umkreisen und 
                es von hinten zu erwischen, vorsichtig gehst Du weiter, Deine Waffe ".$session['user']['weapon']."`@ kampfbereit.`n`n
                Nie hättest Du damit gerechnet das so kleine Drachen einen so kräftigen Schwanz haben der auf Dich zuschnellt und 
                Dich ins Wasser fegt. Deine Schwere Rüstung zieht Dich auf den Grund des Sees, doch als Du die Augen öffnest 
                erblickst du wie der Drache ebenfalls ins Wasser gesprungen ist, hastig greifst Du Deine Waffe fester um, auch 
                wenn du sowieso gleich ertrinkst, wenigstens nicht lebendig gefressen zu werden.`n`n
                Doch ehe Du Dich versiehst ist der Drache unter Dir und Du sitzt auf seinem Rücken, hastig bewegt er seine Flügel 
                und trägt Dich zurück an die Oberfläche wo er Dich sicher ans Ufer bringt. Eine kleine Stimme erscheint in Deinem 
                Kopf und spricht zu Dir:`n`n
                `7\"Dieser Baumhirte ist böse, er will das ich von hier verschwinde, dabei war ich zuerst hier, ich bin doch noch 
                so klein und brauche das Wasser, bitte hilf mir ich werde Dich auch dafür belohnen.\"`n`n
                `QWas willst Du nun machen, dem Wunsch des Baumhirten entsprechen oder dem Drachen dienen?");
        addnav("Den Babydrachen töten",$fn."?op=drachentoeten");
        addnav("Den Baumhirten töten",$fn."?op=baumhirtetoeten");
        addnav("Renne weg",$fn."?op=wegrenn");
    break;
    case "drachentoeten":
        $charm = e_rand(10,30);
        $repu = e_rand(25,50);
        output("`@Du holst mit deiner Waffe aus und ohne mit der Wimper zu zucken, tötest du den süssen Babydrachen.`n`n
                Nachdem Du den Drachen besiegt hast fühlst du Dich nicht sonderlich wohl, es war zwar ein Drache doch war es 
                ein Baby.`n`n
                `\$Du verlierst ".$charm." Charmpunkte und ".$repu." Ansehen!");
        addnews($session['user']['name']."`q brachte einen kleinen Babydrachen um. Mit seinesgleichen kann ".($session['user']['sex']?"Sie":"Er")." es scheinbar nicht aufnehmen...");
        if ($session['user']['charm']>$charm){
            $session['user']['charm']-=$charm;
        }else{
            $session['user']['charm']=0;
        }
        $session['user']['reputation']-=$repu;
    break;
    case "baumhirtetoeten":
        output("`QDu entscheidest dich für den Drachen. Der Baumhirte ist sehr erzürnt darüber und will dich vernichten!!!`n");
        $session['user']['specialinc']="baumhirten.php";
        $badguy = array(
                "creaturename"=>"Baumhirte",
                "creaturelevel"=>$session['user']['level'],
                "creatureweapon"=>"viele Äste",
                "creatureattack"=>$session['user']['attack']-1,
                "creaturedefense"=>$session['user']['defence']-1,
                "creaturehealth"=>round($session['user']['maxhitpoints']*1.3),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $battle=true;
        $_GET['op']=="fight";
    break;
    case "fight":
        $session['user']['specialinc']="baumhirten.php";
        $battle=true;
    break;
    case "run":
        $session['user']['specialinc']="baumhirten.php";
        output("`QEs gibt kein Entkommen, du bist von Wölfen umstellt.");
        $battle=true;
    break;
    case "wegrenn":
        $session['user']['specialinc']="";
        $wk = e_rand(1,3);
        if ($wk==1){
            $text = "Waldkampf";
        }else{
            $text = "Waldkämpfe";
        }
        output("`QDu rennst so schnell du kannst davon! Du blickst dich nicht um und rennst und rennst und rennst.....`n`n
                `\$Du bist so ausser Atem, dass du dich erstmal ausruhen musst. Du verlierst `^".$wk."`\$ $text");
        if ($session['user']['turns']>$wk){
            $session['user']['turns']-=$wk;
        }else{
            $session['user']['turns']=0;
        }
    break;
}
if ($battle) {
    include("battle.php");
     $session['user']['specialinc']="baumhirten.php";
    if ($victory){
        $session['user']['specialinc']="";
        $badguy=array();
        $session['user']['badguy']="";
        $gems = e_rand(2,5);
        $expbonus = round($session['user']['experience']*0.05);
        output("`n`n`QNachdem du den Baumhirten getötet hast fühlst Du, dass Du das richtige getan hast. Als Dank bekommst Du den 
                Segen des Babydrachens sowie `#$gems Edelsteine`Q und `^".$expbonus."`Q Erfahrung.");
        addnews($session['user']['name']."`@ half einem kleinen Drachen, den Baumhirten loszuwerden. Dafür erhielt er seinen Segen.");
        $session['user']['experience']+=$expbonus;
        $session['user']['gems']+=$gems;
        $buff = array("name"=>"`qSegen des kleinen Drachens`0","rounds"=>100,"wearoff"=>"`qDer Segen des kleinen Drachens verlässt dich.`0","defmod"=>2.5,"roundmsg"=>"`qDer Segen des kleinen Drachen stärkt deine Abwehr.`0","activate"=>"roundstart");
        $session['bufflist']['lildragon']=$buff;
      }elseif ($defeat){
        $expmalus = round($session['user']['experience']*0.1);
        $session['user']['specialinc']="";
        $badguy=array();
        output("`n`n`\$Du wurdest von dem Baumhirten getötet. Dein edler gedanke hat dir den tot gebracht..... War es dieser kleine Drache wert?");
        addnews($session['user']['name']."`\$ wurde von einem Baumhirten getötet.");
        $session['user']['badguy']="";
        $session['user']['alive']=false;
        $session['user']['gold']=0;
        $session['user']['hitpoints']=0;
        $session['user']['experience']-=$expmalus;
        addnav("Tägliche News","news.php");
    }else{
        fightnav(true,true);
    }
}
?>