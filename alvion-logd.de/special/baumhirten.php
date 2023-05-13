
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
        output("`@Du gehst durch den finsteren Wald, dein Herz schlägt schneller als gewöhnlich, dein Atem ist schnell und
                kurz, irgend etwas stimmt hier nicht.`n`n
                Du bleibst stehen, und als du dich umblickst, siehst du rings um dich herum viele Augenpaare, die Dich anstarren.
                Dich beschleicht ein mulmiges Gefühl. Plötzlich hörst du links neben dir ein lautes Knacken, schnell wendest du
                den Blick dorthin, die Augenpaare starren dich immer noch an und du spürst wie dir langsam kalter Angstschweiß 
                auf die Stirn läuft. Als du dann siehst, was das Knacken verursacht hat, hast du das Gefühl, als würde dir dein Herz
                stehen bleiben. Vor dir erblickst Du einen Baumhirten, der langsam auf dich zu kommt.`n`n
                `QWillst du lieber warten, oder nimmst du die Beine in die Hand und rennst?");
        addnav("`@Ich warte",$fn."?op=warten");
        addnav("`\$Renn um dein Leben",$fn."?op=rennen");
    break;
    case "rennen":
        $session['user']['specialinc']="";
        switch (e_rand(1,4)){
            case 1:
                bild("baumhirte.jpg");
                $exp = round($session['user']['experience']*0.05);
                output("`QDu versuchst, wegzurennen, doch die Augenpaare bewegen sich hastig auf dich zu, ein Rudel Wölfe kommt aus den
                        Büschen gesprungen und fällt über dich her und zerfleischen dich!`n`n
                        `\$Du bist tot und kannst morgen weiter spielen!`n
                        Du verlierst all dein `^Gold`\$ und `^".$exp."`\$ Erfahrung");
                addnews($session['user']['name']."`\$ wurde im besonders dunklen Teil des Waldes von einem Rudel Wölfen zerfleischt.");
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
                output("`QDu rennst, so schnell du kannst, davon! Zu welchen Lebewesen die Augenpaare gehören, kannst du nicht
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
                output("`@Mutig wie du bist, wartest du auf den Baumhirten, viele Geschichten hast du schon über ihn gehört doch sehen tust
                        du ihn jetzt zum ersten Mal. Langsam fangen deine Knie an zu schlottern, hinter dir vernimmst du ein Jaulen, von
                        rechts ein Knurren, und dann weißt Du es: die Augen gehören zu Wölfen.`n`n
                        Sie haben dich umstellt und wollen dich gerade anspringen, als der Baumhirte ein lautes Knacken von sich gibt und
                        langsam beginnt, zu sprechen.`n`n
                        `&\"Lasst von diesem Wesen ab, Ihr habt heute schon genug gefressen.\"`@`n`n
                        Die Stimme des Baumhirten klingt alt, kalt und grausam und dir läuft ein eisiger Schauer über den Rücken. Als
                        du dann noch erkennst, dass der Baum ein Gesicht hat, mit 2 riesigen Augen, die dich anglotzen und mit einer Nase,
                        die so krumm ist, dass selbst eine Hexe neidisch wäre, fällst du in Ohnmacht.");
                addnav("Weiter",$fn."?op=warten&act=aufwachen");
            break;
            case "aufwachen":
                switch (e_rand(1,4)){
                    case 1:
                        $session['user']['specialinc']="";
                        output("`@Als du deine Augen wieder aufschlägst, befindest du dich an einem noch finstereren Ort als zuvor,
                                neugierig blickst du dich um, dieser Ort kommt dir merkwürdig bekannt vor, du erblickst dein
                                eigenes Grab, auf dem geschrieben steht: `n`n");
                        bild("grabstein.jpg");
                        output("`c`&\"Hier ruht ein tapferer Abenteurer, der versuchte, sich einem Baumhirten in den Weg zu stellen
                                und von diesem aus Versehen zertreten wurde.\"`n`n`c
                                `\$Du bist tot und kannst morgen weiter spielen.");
                        addnews($session['user']['name']."`\$ wurde von einem Baumhirten nieder getrampelt!!!`n`QMan könnte ".($session['user']['sex']?"sie":"ihn")." nun prima als Läufer für den Hausflur nutzen.");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        addnav("Tägliche News","news.php");
                    break;
                    case 2:
                    case 3:
                    case 4:
                        bild("hirte.jpg");
                        $session['user']['specialinc']="baumhirten.php";
                        output("`@Als du deine Augen öffnest, und um dich herum Äste und den Himmel siehst, atmest Du erleichtert aus
                                und glaubst, du hattest nur einen bösen Traum. Du entschließt dich, dich zu erheben und kannst Dich gerade
                                noch festhalten, als du bemerkst, dass du in luftiger Höhe und anscheinend in der sehr verwurzelt aussehenden Hand
                                des Baumhirten bist.`n`n
                                Sofort bekommst du wieder Panik und wendest dich Hilfe suchend um, doch schnell stellst du fest, dass
                                dir hier niemand helfen kann, also nimmst Du Deinen Mut zusammen, blickst zum Baumhirten auf und
                                fragst:`n`n
                                `&\"Wohin bringst du mich, Wächter des Waldes?.\"`@`n`n
                                Der Baumhirte blickt zu dir hinunter, und wenn deine Augen dir keinen Streich spielen, könntest Du
                                meinen, ein Lächeln gesehen zu haben, schließlich antwortet der Baumhirte:`n`n
                                `&\"Du bist mutig, in meinem See sitzt ein kleiner Drache und nimmt mir mein Wasser, da er für mich 
                                zu schnell ist, wirst du ihn für mich erlegen.\"`@`n`n
                                Du schluckst und dich überkommt wieder eine Panik, die Dich erneut ohnmächtig werden lässt. Einen
                                Augenblick später öffnest du die Augen, und du liegst auf dem Boden. Vor dir siehst du einen
                                kleinen See, der kaum größer ist, als der heimische. Der Baumhirte steht hinter dir
                                und deutet auf einen Felsen, wo ein kleiner Drache, gerade mal 1 Meter groß, sitzt und ein Sonnenbad
                                nimmt. Verwirrt blickst du zum Baumhirten hoch. `#\"Der kleine Drache da macht dir Angst?\"`@`n`n
                                Der Baumhirte grummelt leicht und nickt: `&\"Er spuckt Feuer\"`@`n`n
                                Wie durch Zufall holt der Drache tief Luft, und aus seinem kleinen Maul kommt Feuer geschossen.
                                Lauthals fängst du an zu lachen und gehst zum Drachen hinüber, der dich anblickt und gespielt
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
        output("`@Auf der Suche nach Ehre und Ruhm näherst du dich dem Babydrachen, während im See munter die Fische springen und
                der Baumhirte dich beobachtet.`n`n
                Vorsichtig und langsam näherst Du Dich dem Feuer spuckenden Ungeheuer, welches erneut scharf die Luft einsaugt und
                niesen muss, woraufhin kleine Rauchwolken aus den Nasenlöchern kommen. Du beschließt, das Untier zu umkreisen und
                es von hinten zu erwischen, vorsichtig gehst du weiter, Deine Waffe ".$session['user']['weapon']."`@ kampfbereit.`n`n
                Nie hättest du damit gerechnet, dass so kleine Drachen einen so kräftigen Schwanz haben, der auf dich zuschnellt und
                dich ins Wasser fegt. Deine Schwere Rüstung zieht dich auf den Grund des Sees, doch als du die Augen öffnest,
                erknnst du, dass der Drache ebenfalls ins Wasser gesprungen ist, hastig greifst du deine Waffe fester, um, auch
                wenn du sowieso gleich ertrinkst, wenigstens nicht lebendig gefressen zu werden.`n`n
                Doch ehe du dich versiehst, ist der Drache unter dir und du sitzt auf seinem Rücken, hastig bewegt er seine Flügel
                und trägt dich zurück an die Oberfläche, wo er Dich sicher ans Ufer bringt. Eine kleine Stimme erscheint in deinem
                Kopf und spricht zu Dir:`n`n
                `7\"Dieser Baumhirte ist böse, er will, dass ich von hier verschwinde, dabei war ich zuerst hier, ich bin doch noch
                so klein und brauche das Wasser, bitte hilf mir, ich werde dich auch dafür belohnen.\"`n`n
                `QWas willst Du nun machen, dem Wunsch des Baumhirten entsprechen, oder dem Drachen dienen?");
        addnav("Den Babydrachen töten",$fn."?op=drachentoeten");
        addnav("Den Baumhirten töten",$fn."?op=baumhirtetoeten");
        addnav("Renne weg",$fn."?op=wegrenn");
    break;
    case "drachentoeten":
        $charm = e_rand(10,30);
        $repu = e_rand(25,50);
        output("`@Du holst mit deiner Waffe aus, und ohne mit der Wimper zu zucken, tötest du den süssen Babydrachen.`n`n
                Nachdem du den Drachen besiegt hast, fühlst du dich nicht sonderlich wohl, es war zwar ein Drache, doch war es nur
                ein Baby.`n`n
                `\$Du verlierst ".$charm." Charmpunkte und ".$repu." Ansehen!");
        addnews($session['user']['name']."`q brachte einen kleinen Babydrachen um. Mit ".($session['user']['sex']?"Ihres":"Seines")."gleichen kann ".($session['user']['sex']?"sie":"er")." es scheinbar nicht aufnehmen...");
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
        output("`QDu rennst, so schnell du kannst, davon! Du blickst dich nicht um und rennst und rennst und rennst.....`n`n
                `\$Du bist so außer Atem, dass du dich erst mal ausruhen musst. Du verlierst `^".$wk."`\$ $text");
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
        output("`n`n`QNachdem du den Baumhirten getötet hast, fühlst Du, dass Du das Richtige getan hast. Als Dank bekommst Du den
                Segen des Babydrachen, sowie `#$gems Edelsteine`Q und `^".$expbonus."`Q Erfahrung.");
        addnews($session['user']['name']."`@ half einem kleinen Drachen, den Baumhirten los zu werden. Dafür erhielt ".($session['user']['sex']?"sie":"er")." dessen Segen.");
        $session['user']['experience']+=$expbonus;
        $session['user']['gems']+=$gems;
        $buff = array("name"=>"`qSegen des kleinen Drachens`0","rounds"=>100,"wearoff"=>"`qDer Segen des kleinen Drachen verlässt dich.`0","defmod"=>2.5,"roundmsg"=>"`qDer Segen des kleinen Drachen stärkt deine Abwehr.`0","activate"=>"roundstart");
        $session['bufflist']['lildragon']=$buff;
          }elseif ($defeat){
        $expmalus = round($session['user']['experience']*0.1);
        $session['user']['specialinc']="";
        $badguy=array();
        output("`n`n`\$Du wurdest von dem Baumhirten getötet. Dein edler Gedanke hat dir den Tod gebracht..... War es dieser kleine Drache wert?");
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

