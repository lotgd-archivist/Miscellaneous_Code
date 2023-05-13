
<?php 
// idea of baldawin @ www.rabenthal.de 
// programmed by gargamel @ www.rabenthal.de 

if (!isset($session)) exit(); 


if ($_GET[op]==""){ 
    output("Als du deinen alltäglichen Rundgang im Wald machst, hörst du in deiner
    unmittelbaren Nähe plötzlich das Knacken eines zertretenen Zweiges.`n`nAls du dich,
    bereit für das Schlimmste, schnell in die Richtung des Geräusches drehst,
    erwartet dich eine angenehme Überraschung:`n`n`^"
    .($session[user][sex]?"Ein wunderschöner junger Mann ":"Eine wunderschöne junge Frau "). 
    "`0wirft dir stumm ein verführerisches Lächeln zu...`n
    Einen Moment überlegst du, was du davon halten sollst... `n
    Du hast " 
    .($session[user][sex]?"diesen Mann ":"diese Frau "). 
    "noch nie gesehen und kennst nicht mal "
    .($session[user][sex]?"seinen ":"ihren "). 
    "Namen...`0"); 
    //abschluss intro 
    if ($session['user']['sex']>0){ //frau 
        addnav("Gebe dich ihm hin","berge.php?op=hin");
        addnav("Lass ihn stehen","berge.php?op=weg"); 
    } else { 
        addnav("Gebe dich ihr hin","berge.php?op=hin");
        addnav("Lass sie stehen","berge.php?op=weg"); 
    } 
    $session[user][specialinc] = "kubus.php"; 
} 
else if ($_GET[op]=="hin"){ 
    output("Du zwinkerst ".($session[user][sex]?"dem Fremden ":"der Fremden "). 
    "zu und kurze Zeit später seid ihr beide auch schon im nächsten Gebüsch 
    verschwunden...`n`n`0"); 
    $grenzwert = 70; 
    if ($session['user']['charisma']==4294967295) 
       $grenzwert = 40; 

    $chance = e_rand(1,100); 
    //output("chance $chance < grenzwert $grenzwert`n"); //debug 
    if ( $chance < $grenzwert ) {     // positiv 
        output("Als du wieder Herr deiner Sinne bist, ist "
        .($session[user][sex]?"der mysteriöse Mann ":"die mysteriöse Frau "). 
        "wie vom Erdboden verschluckt.`n`n 
        \"Schade...\" denkst du, ziehst dich wieder an und setzt deinen Weg fort.`n`n
        `^Du fühlst dich großartig, darum bekommst du 2 Charmepunkte und regenerierst
        vollständig.`0"); 
        $session[user][charm]+=2; 
         if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) 
             $session[user][hitpoints]=$session[user][maxhitpoints]; 
             addnews("`\$`b".$session[user][name]."`b `\$wurde in den Bergen von " .($session[user][sex]?"einem schönen Mann ":"einer schönen Frau "). "verführt."); 
    }else {                  // negativ 
        output("Während eures rauschhaften Liebesspieles spürst du eine Veränderung an "
        .($session[user][sex]?"ihm":"ihr"). 
        "...`nDu schließt Deine Augen...`n
        Als du sie wieder öffnest, möchtest du nicht wahr haben, was du siehst:`n`n"
        .($session[user][sex]?"Der Unbekannte ":"Die Unbekannte "). 
        "starrt dich mit einer hässlichen Fratze an und verfällt in wildes Kichern!
        Scheinbar bist du "
        .($session[user][sex]?"einem Inkubus ":"einer Sukkubus ").
        "auf den Leim gegangen! Nachdem du das Liebesspiel überstanden hast,
        verkriecht sich der Dämon schallend lachend im Wald, auf dass ihm "
        .($session[user][sex]?"die nächste unvorsichtige Abenteuerin ":"der nächste unvorsichtige Abenteurer "). 
        "über den Weg läuft...`n`n 
        `^Du fühlst dich benutzt und ausgelaugt, daher verlierst du 2 Charmepunkte
        und bist sehr schwach!`0"); 
        $session[user][charm]-=2; 
        $session[user][hitpoints]=1; 
        addnews("`\$`b".$session[user][name]."`b `\$wurde in den Bergen von ".($session[user][sex]?"einem Inkubus ":"einer Sukkubus ")."in Gestalt ".($session[user][sex]?"eines schönen Mannes ":"einer schönen Frau "). "ausgenutzt.");
    } 
    $session[user][specialinc]=""; 
} 
else if ($_GET[op]=="weg"){   // einfach weitergehen 
    output("Ein wenig komisch fühlst du dich schon... daher verneigst du dich nur
    höflich und wünschst " 
    .($session[user][sex]?"dem Schönen ":"der Schönen "). 
    "einen angenehmen Tag und gehst weiter deines Weges.`0"); 
    $session[user][specialinc]=""; 
} 
?>

