<?php
/*
    in Entwicklung
    ein Waisenhaus mit der Möglichkeit, elternlose Kinder zu finden und einen Adoptionsgesuch zu verfassen
    oder den Kindern etwas Schönes zukommen zu lassen -> möglicherweise gute Auswirkung auf den Spieler

    Idee & Script von Nebel
    für die Nebel von Mystara
    http://www.mystara-logd.net
    April 2007
    Grundlage ist das Kindersystem von -DoM
    
SQL, um die 'Rangliste' beim Spenden erstellen zu können:
    ALTER TABLE `accounts` ADD `waisen` INT( 11 ) DEFAULT '0' NOT NULL

um die Spenden pro Spieltag einschränken zu können:
SQL:
    ALTER TABLE `accounts` ADD `waisen2` INT( 11 ) DEFAULT '0' NOT NULL
öffne die newday.php
und füge ein:
    $session['user']['waisen2']=0;

zur automatischen Anzeige der Waisenkinder 
öffne die setnewday.php
und suche
    $sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
    db_query($sql) or die(db_error(LINK));    
füge danach ein:
    $sql = "UPDATE kinder SET mama=0 WHERE mama=$row[acctid]";
    db_query($sql);
    $sql = "UPDATE kinder SET papa=0 WHERE papa=$row[acctid]";
    db_query($sql);
    
Letzte Änderung: November 2007
*/

require_once "common.php";
{
page_header("Waisenhaus von Essos");

$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];

if ($_GET['op']==""){ 
    addcommentary();
    $result = db_query("SELECT * FROM kinder WHERE mama=0 AND papa=0 ORDER BY geschlecht");
    $row = db_fetch_assoc($result);
    if($result==0){
    output("`b`c`q`i`n`nDerzeit ist das Waisenhaus geschlossen, da keine Waisenkinder in der Umgebung bekannt sind!`i`v`c`b`n`n");
    addnav("Verlassen","orange.php"); 
    }else{
    output("`c`b`VDas Waisenhaus von Essos`b`c`n`n
            `vDu betrittst das Waisenhaus in Essos, welches den elternlosen Kindern der Umgebung ein neues Heim bietet. Zumindest haben sie hier ein Dach
            über dem Kopf und bekommen ein wenig zu Essen. Jedoch sind die finanziellen Mittel des Hauses stets knapp, und einstweilen mangelt es an schönen
            Sachen oder auch -im schlimmsten Falle- an Lebensmittel und Kleidung. Meinst Du nicht auch, daß diese armen Kinder ein neues Heim verdient haben?
            Vielleicht solltest Du einmal mit Deinem Partner darüber reden, eines von ihnen bei Euch aufzunehmen. Doch wenn Euch dies nicht möglich ist, oder Ihr
            derzeit keine Kinder bei Euch haben möchtet, so kannst Du ihnen auch etwas Gutes tun, und dem Waisenhaus etwas spenden.`n`n
            Was möchtest Du tun?`n`n");
    viewcommentary("waisenhaus","unterhalten",20);
    viewcommentary("OOC","unterhalten",20);
    //output("`n`n`vEine Mitarbeiterin des Hauses weist Dich darauf hin, dass dies ein Ort des Rollenspiels ist!");
    addnav("Waisenkinder","waisen.php?op=kinder");
    addnav("Spenden","waisen.php?op=spende"); 
    addnav("Verlassen","essos_village.php"); 
    }
}elseif ($_GET['op']=="kinder"){                     // auflistung der waisen
    $result = db_query("SELECT * FROM kinder WHERE mama=0 AND papa=0 ORDER BY geschlecht");
    output("`V`c`bdie Waisenkinder`b`c`n`n
            `vEine Mitarbeiterin des Waisenhauses führt Dich in einen Aufenthaltsraum, in welchem sich die Kinder versammelt haben, um gemeinsam zu spielen.
            Ein mulmiges Gefühl beschleicht Dich, als Dir bewusst wird, daß diese jungen Wesen niemanden haben, der sich richtig um sie kümern kann.
            Denn selbst wenn sie hier ein Heim gefunden haben, so ist es für die Betreuer kaum möglich, Eltern zu ersetzen. Der Reihe nach stellt Dir die
            Mitarbeiterin die Kinder vor:`n`n");

    output("<table cellspacing=0 cellpadding=2 align='center'><tr class=trhead><td>Name</td><td>&nbsp;</td><td>Geburtsdatum</td></tr>", true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class=".($i%2?"trlight":"trdark")."><td>" . $row[name], true);
        if($row['geschlecht'] == 1)
            output("<td>`c<img src=images/female.gif>`c</td>", true);
        else
            output("<td>`c<img src=images/male.gif>`c</td>", true);
            
        output("</td>", true);
        output("<td>" . $row[gebdat] . "</td></tr>", true);
    }
    output("</table>",true);
    output("`n`n`vSolltet Ihr Euch entscheiden, eines dieser Kinder als das Eure anzunehmen, so lasst dies Stella wissen.
    Sie wird mit Euch die nötigen Formalitäten regeln und sich um eine baldige Familienzusammenführung kümmern. Doch ist es einem Paar vorerst nur gestattet, eines der Kinder
    bei sich aufzunehmen.");
       addnav("Zurück","waisen.php");
}elseif ($_GET['op']=="spende"){                     // möglichkeit des spendens
    output("`V`c`bdie Spendenverwaltung`b`c`n`n
    `vHier hast Du die Möglichkeit, dem Waisenhaus eine kleine Spende zukommen zu lassen. Sei Dir gewiss, daß Dir der Dank der Kinder sicher ist,
    wenn sie von Deiner noblen Geste erfahren! Womit möchtest Du ihnen eine Freude machen?`n`n");
    if ($session[user][rpchar]!=1){
    addnav("Spenden");
    addnav("etwas zu Essen - 2.000 Gold","waisen.php?op=essen"); 
    addnav("Spielzeug - 5.000 Gold","waisen.php?op=spiel"); 
    addnav("Kleidung - 10.000 Gold","waisen.php?op=kleid"); 
    addnav("Sonstiges");
    addnav("edelste Spender","waisen.php?op=liste");
    addnav("Zurück","waisen.php");    
}else{
    addnav("Spenden");
    addnav("etwas zu Essen - 4 Punkte","waisen.php?op=essen2"); 
    addnav("Spielzeug - 6 Punkte","waisen.php?op=spiel2"); 
    addnav("Kleidung - 8 Punkte","waisen.php?op=kleid2"); 
    addnav("Sonstiges");
    addnav("edelste Spender","waisen.php?op=liste");
    addnav("Zurück","waisen.php");
    }
}elseif ($_GET['op']=="liste"){                     // die edelsten Spender
   output("`V`c`bdie edelsten Spender`b`c`n`n
            `vEtwas abseits der abgeladenen Spenden hockt ein kleiner Goblin auf dem Boden, in den Händen eine alte Steintafel haltend, auf welcher
        bereits einige Namen zu erkennen sind. Und jedes Mal, wenn jemand seine Spende abliefert, macht er den ein oder andere Strich hinter den Namen
        des Spenders. Du trittst von hinten an den Goblin heran und liest Dir aufmerksam diese Auflistung durch. Ob er wirklich richtig zählen kann?`n`n");
     $result = db_query("SELECT name,waisen FROM accounts WHERE locked=0 AND waisen AND superuser<3 ORDER BY waisen DESC"); 
    output("<table cellspacing=0 cellpadding=2 align='center'><tr class=trhead><td>`b`yN`Ya`4m`fe</td><td>`ySpe`Ynde`4npu`fnkte`0</td></tr>", true);
    for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("<tr class=".($i%2?"trlight":"trdark")."><td>" . $row['name'], true);
    output("</td><td>`c " . $row['waisen'], true);
    output("`c</td></tr>", true);
    }
    output("</table>",true);
     addnav("Zurück","waisen.php?op=spende");
}elseif ($_GET['op']=="essen"){                     // essen spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{    
    if ($session['user']['gold']<2000){ 
        output ("`pDu hast nicht genug Gold bei Dir um dies zu spenden!"); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `i`VNahrung`i `vzukommen zu lassen. So schaffst Du ein paar Brote, einige Phiolen
        klaren Quellwassers sowie andere Leckereien heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['gold']-=2000; 
        $session['user']['waisen']+=1;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']++;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=10;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");
}elseif ($_GET['op']=="spiel"){                     // spielzueug spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{    
    if ($session['user']['gold']<5000){ 
        output ("`pDu hast nicht genug Gold bei Dir um dies zu spenden!"); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `V`iSpielzeug`i`v zukommen zu lassen. So schaffst Du ein paar Bauklötze, einige Plüschtiere
         sowie andere Spielzeuge heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['gold']-=5000; 
        $session['user']['waisen']+=2;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']+=5;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=20;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");
}elseif ($_GET['op']=="kleid"){                     // kleidung spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{
    if ($session['user']['gold']<10000){ 
        output ("`pDu hast nicht genug Gold bei Dir um dies zu spenden!"); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `V`iKleidung`i`v zukommen zu lassen. So schaffst Du ein paar Hosen, einige Kleider
        sowie andere Anziehsachen heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['gold']-=10000; 
        $session['user']['waisen']+=3;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']+=15;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=35;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");

/**** rp-chars start****/

}elseif ($_GET['op']=="essen2"){                     // essen spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{   
   if ($pointsavailable<4){
        output("Du hast nicht genügend Punkte."); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `i`VNahrung`i `vzukommen zu lassen. So schaffst Du ein paar Brote, einige Phiolen
        klaren Quellwassers sowie andere Leckereien heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['donationspent']+=4; 
        $session['user']['waisen']+=1;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']++;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=10;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");
}elseif ($_GET['op']=="spiel2"){                     // spielzueug spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{   
   if ($pointsavailable<6){
        output("Du hast nicht genügend Punkte."); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `V`iSpielzeug`i`v zukommen zu lassen. So schaffst Du ein paar Bauklötze, einige Plüschtiere
         sowie andere Spielzeuge heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['donationspent']+=6; 
        $session['user']['waisen']+=2;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']+=5;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=20;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");
}elseif ($_GET['op']=="kleid2"){                     // kleidung spenden
if ($session['user']['waisen2']>=3){
    output("`p`nDu hast für heute schon genug gespendet.`n 
    Lass die Mitarbeiter des Waisenhauses erst einmal die ganzen Sachen beiseite schaffen.");
}else{    
    if ($pointsavailable<8){
        output("Du hast nicht genügend Punkte."); 
    }else{
        output("`vDu hast Dich entschlossen, dem Waisenhaus etwas `V`iKleidung`i`v zukommen zu lassen. So schaffst Du ein paar Hosen, einige Kleider
        sowie andere Anziehsachen heran und übergibst sie der Leitung des Hauses. Mit dankbaren Augen wird Deine Spende
        entgegengenommen.`n");
        $session['user']['donationspent']+=8; 
        $session['user']['waisen']+=3;
        $session['user']['waisen2']+=1;
 switch(e_rand(1,2)){
    case 1:
        output ("`b`vDu hast das Gefühl, durch Deine noble Geste von den anderen als charmanter angesehen zu werden!`b");                 
        $session['user']['charm']+=15;
    break;
    case 2:
        output ("`b`vDurch Deine edle Geste ist Dein Ansehen in der Stadt gestiegen!");                 
        $session['user']['reputation']+=35;
    break;
        }
    }
}
     addnav("Zurück","waisen.php");
    }
}

//output('`n`n`c`7`b&copy; by <a href="http://www.mystara-logd.net" target="_blank">Chaoshüterin des Nebels</a>`b`c`0',true);
page_footer();
?> 