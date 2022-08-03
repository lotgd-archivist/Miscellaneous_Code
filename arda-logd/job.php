<?php

/*
Berufe Script (0.4v)
by Savomas & Gimmick (a.k.a.Tiresias)
http://www.ocasia.de
Job.php (Arbeitsamt)
--------------------------------------------------
Das Arbeitsmat bietet die möglichkeit einen Job zu suchen, oder zu kündigen

------------------------------------------------
0.1v
    -Job suchen
    -Bugs erkannt
0.2v
    -Jobs kündigen
    -Bugs weggemacht
    -neue Sprüche hinzugefügt
0.3v
    -Job annehmen

0.4v
    -Bug-Free (Wahrscheinlich ;-] )
    -Büroangestellten Namen erweitert
    -noch mehr neue Sprüche hinzugefügt
    
0.5v
    -Text Optimiert
    -Letzte Bugs behoben (Diese kleinen Teufel- waren doch noch drinne *lol*)
    -Code optimiert
-------------------------------------------------
THX an Kevz (für seine sql-Hilfe-Hotline *lol*)
------------------------------------------------
*/
require_once "common.php";
addcommentary();
page_header("Das Arbeitsamt");
if ($HTTP_GET_VARS['op']==""){
    if ($session[user][jobid]==0){
 addnav ("Job suchen","job.php?op=berat");
    }
if ($session[user][jobid]>0){
addnav ("Job Kündigen","job.php?op=quitjob");
}
addnav ("Sonstiges");
addnav ("Mit Mr. Shriek reden","job.php?op=shriek");
addnav ("Informationsblatt lesen","job.php?op=info");
addnav ("Das Arbeitsamt verlassen","markt.php");

output ("`&Du betrittst das kleine Gebäude am Ende der Straße, auf dem in großen goldenen Lettern `^'Arbeitsamt'`& geschrieben steht. Von aussen wirkt es klein, doch innen drin scheinen sich die Schreibtische der Berater bis zum Horizont zu erstrecken, Mr. Shriek, erkennst du nur noch als einen kleinen Punkt in den Tiefen der Bürokratischen Hölle auf Erden. Langsam schreitest du an den kleinen, mit Papier überladenen, Tischen vorbei, zu einem großen, mit Marmor bekleideten, Rezeptionsstisch. `q'Guten Tag!', `&sagt eine Frau höflich zu dir, `q'Wie kann ich ihnen Helfen?'`&, du murmelst leise, da es dir etwas peinlich ist keinen Job zu haben, dass du Arbeit suchst, doch die Frau lächelt immernoch breiter als nötig. `q'Aber mein".($session['user']['sex']?"e Frau":"Herr").", das ist doch nichts schlimmes!`&. Erleichtert grinst du zurück.`n");

// Namen des Büroangestellten werden per Zufall bestimmt
    switch(e_rand(1,20)){
case 1: $name="Rudolph"; 
    break;
case 2: $name="Gustav"; 
    break;
case 3: $name="Eisenhardt"; 
    break;
case 4: $name="Titus"; 
    break;
case 5: $name="Herr Shriek"; 
    break;
case 6: $name="Lukas"; 
    break;
case 7: $name="Herr Seltsam"; 
    break;
case 8: $name="Herr Schnapper"; 
    break;
case 9: $name="Ahriman"; 
    break;
case 10: $name="Herr Ramius"; 
    break;
case 11: $name="Orpheus"; 
    break;
case 12: $name="Domizeus"; 
    break;
case 13: $name="Findus"; 
    break;
case 14: $name="Maria"; 
    break;
case 15: $name="General Custer"; 
    break;
case 16: $name="Mr. Verwirrter Gnom mit Spiegel"; 
    break;
case 17: $name="Graf Dracula"; 
    break;
case 18: $name="Herr Dr. med. Prof. Frankenstein"; 
    break;
case 19: $name="Frau Ursula"; 
    break;
case 20: $name="Mr. Sir"; 
    break;
    }

}

if ($HTTP_GET_VARS['op']=="berat"){
addnav ("Zurück","job.php");

output ("`&Du fragst die junge Frau an der Rezeption nach einem Berater, der dir in deiner miserablen Lage weiter helfen kann , sie blickt kurz in die Unterlagen und vergleicht sie mit deinen Angaben über deine Ausbildung und deine Noten, ein Grinsen huscht über ihr Gesicht, als sie deine Beste Note sieht.`n");
    if ($session[user][lektion]>=400){
addnav ("Beratung nehmen","job.php?op=searchjob");
output ("`^'Gehen sie zu ".$name.", da wird ihnen geholfen!' `& zwinkert sie dir zu, natürlich gehst du sofort zu ".$name." um dir helfen zu lassen, einen ordentlichen Job zu bekommen.`n");
output ($name." weist dich zu seinem Schreibtisch und beginnt in deine Unterlagen zu sehen.`^'Ich nehme stark an, dass sie einen Job möchten....'`&");
    } else {
$ver=400-$session['user']['lektion'];
output ("`&Du fragst die junge Frau an der Rezeption nach einem Berater, der dir in deiner mieserablen Lage weiter helfen kann , sie blickt kurz in die Unterlagen und vergleicht sie mit deinen Angaben über deine Ausbildung. Plötzlich schaut sie dich eindringlich an und meint: `^'Wie ich sehe, hast du noch nicht alle  Lektionen deiner Ausbildung durchgemacht, du musst noch einige Lektionen machen...' `& beschämt drehst du dich um...`n");
    }
}
if ($HTTP_GET_VARS['op']=="quitjob"){
//Kündigen
addnav ("Endgültig Kündigen","job.php?op=quitjob2");
addnav ("Lieber nicht","job.php");
output ("`^Ah, sie sind im Moment wie ich sehe, `%".$session[user][jobname].", aber das wollen sie nicht mehr sein? Das kostet aber 100 Gold Bearbeitungsgebühr`n");
}
if ($HTTP_GET_VARS['op']=="quitjob2"){
addnav ("Zum Eingang","job.php");
    if ($session[user][gold]>=100){
output ("`^In Ordnung, nun sind sie wieder Arbeitslos, viel Glück bei der Suche nach einem neuen Job! `$`b100 Gold abgebucht`b`n");
$session[user][gold]-=100;
$session[user][jobid]=0;
$session[user][jobname]="Arbeitsloser";
    } else {
output ("`^ Mein Beileid, sie sind sogar zu arm, zum Kündigen, das tut mir echt leid, aber ich kann da nichts Machen!");
    }
}
if ($_GET['op']=="searchjob") {
    addnav ("Zurück","job.php");    
    output ("`^Lassen sie mich mal sehen, aha....da haben wir es: ");    
// Heir wird kontrolliert ob der Spieler genug Ehre hat um einen Job auszuüben
    if($session[user][reputation]<=0) {
        output ("Leider bist du nicht Ehrenhaft genug für einen Ehrbaren Job`n");
    } else {
        output ("Deine Ehrenhaftigkeit ist gut genug für einen Ehrbaren Job`n");
//Um Spieler kurz vor dem Phoenixkill zum Dreachenkill zu zwingen
    if ($session[user][level]>=14) {
            output ("Aber ich sehe gerade, du bist so stark, du solltest deine Zeit nicht mit Arbeiten verschwenden, sondern gegen Ungeheuer kämpfen`n");
            addnav ("Gegen Ungeheuer kämpfen gehen","forest.php");
            addnav ("Sich trollen","village.php");
        }else{
//Wenn alles passt bekommt der Spieler die Jobs angezeigt
output ("Nun gut, deine Vorraussetzungen scheinen zu stimmen, dann suchen wir gemeinsam einen passenden Job, wir hätten da diese Jobs:`n`n");
output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Option</td><td>Name</td><td>Lohn</td><td>Runden</td></tr>",true);
        $sql = "SELECT name,lohn,aubid,id FROM jobs";
          $result = db_query('SELECT `aubid`,`name`,`lohn`,`turns`,`id`  FROM `jobs` WHERE `aubid` = '.$session['user']['aubid']);
    // Damit der User sieht das er 'Schwer vermittelbar' ist *lol*
    if (db_num_rows($result)==0) {
output("<tr class='trdark'><td colspan=5 align='center'>`&`iEs gibt keine passenden Jobs`i`0</td></tr>",true);
       } else {
while ($row = db_fetch_assoc($result)) {
    $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
    output("<tr class='".$bgclass."'><td><a href='job.php?op=getjob&id=".$row['id']."'>Annehmen</a></td><td>".$row['name'],true);
          output("</td><td>".$row['lohn']."</td><td>".$row['turns']."</td>",true);
          addnav("","job.php?op=getjob&id=".$row['id']);
        }
        output("</table>",true);
            output('</form>',true);
            output ("`$ ACHTUNG: Deine Auswahl ist Unwiederrufbar!!!");
        }
        }
    }
}
if ($_GET['op']=="getjob") {
    $sql = "SELECT name,id FROM jobs";
    $result = db_query('SELECT name,id  FROM jobs WHERE id = '.$_GET['id']);
    while ($job = db_fetch_assoc($result)) {
    output ("Ihr wollt also diesen Job? Nun gut! Nun seid ihr ein Stolzer Zunftgenosse der Arbeiterbewegung, ihr seid nun ein ".$job['name']."! In Ordnung, und vergessen sie nicht:' Immer schön fleißig sein!!!'`n");
    $session['user']['jobid']=$job['id'];
    $session['user']['jobname']=$job['name'];
    addnav ("Weiter","job.php");
}
}
if ($HTTP_GET_VARS['op']=="info"){ 
// Informationsblatt für Spieler, damit sie nicht ratlos sind was sie eig. jetzt machen
addnav ("Zurück","job.php");

output ("`^.Du gehst zu dem Informationblatt mit dem Vielsagenden Titel:`%  Was ist Arbeit?`n`n");
output ("
`6`bJeder Krieger in Arda kann einen Job ergreifen, doch dazu muss er die Passenden Qualifikationen haben, diese kann er in der Universität bekommen. `n
Sobald jemand die Qualifikation für einen der `$ 3 `6 Ausbildungszweige erreicht hat, kann er hierher kommen, um sich einen Job zu suchen, dabei ist es wichtig, das er`n
ein Ehrbahrer Bürger ist, denn falls er  Unehrenhaft ist, bekommt er keinen Beruf. Nun sucht er sich seinen Job aus, der zu ihm passt, und von nun an muss er täglich`n
einen Teil seiner Zeit bei der Arbeit verbringen, doch manchmal muss er eben auch Überstunden machen, oder vielleicht passiert etwas auf der Arbeit, wer weiß?`n
Manchmal kommt es vor, dass ein Krieger sehr böse ist, und dann von seinem Chef gefeuert wird, doch selten können die Bösewichte erwischt werden.`n");
}
if ($HTTP_GET_VARS['op']=="shriek"){
//Shriek- unwichtige Person die Sprüche kloppt
addnav ("Zurück","job.php");

output ("Du wanderst zu dem weit entfernten Punkt am anderen Ende des kleinen Gebäudes. Shriek sitzt hinter seinem Schreibtisch und kalkuliert mal wieder seine Anteile an der Mine, anstatt richtig zu arbeiten. Als dein Schatten auf seinen Schreibtisch fällt blickt er nervös auf. Du fragst ihn, was er denn heute für eine Weisheit parat hat, und er lächelt dünn:`n");
// Eventuell Sprüche anpassen falls nicht RP-Gaming grecht
switch(e_rand(1,16)){
              case 1:
    output ("`c`^Von zwei Narren hält der größere den kleinen für den größeren.`c");
                break;
              case 2:
    output ("`c`^Tu erst das Notwendige, dann das Mögliche, und plötzlich schaffst du das Unmögliche.`c");
                break;
            case 3:
    output ("`c`^Reichtum macht ein Herz schneller hart
als kochendes Wasser ein Ei.`c");    
                break;
            case 4:
    output ("`c`^Der frühe Wurm fängt den Vogel`c");
                break;
            case 5:
    output ("`c`^Rache ist süß, doch vergeben wie ein Brot mit Honig`c");
                break;
            case 6:
    output ("`c`^Den grünen Phoenix zu besiegen, ist wahrlich eine Leistung`c");
                break;
            case 7:
    output ("`c`^Folge deinem Herzen, denn der Kopf denkt zu viel`c");
                break;
            case 8:
    output ("`c`^Verschwinde!`c");
                break;
              case 9:
    output ("`c`^Das Ziel ist ein Weg`c");
                break;
            case 10:    
    output ("`c`^Gold stinkt nur manchmal`c");
                break;
            case 11:
    output ("`c`^Ich kam, ich sah, ich verlor kläglich`c");
                break;
            case 12:
    output ("`c`^Wann, wenn nicht jetzt?`c");
                break;
            case 13:
    output ("`c`^Was bedeutet glauben für DICH?`c");
                break;
            case 14:
    output ("`c`^Lüge nicht, du stehst auch auf Lila Kühe`c");
                break;
            case 15:
    output ("`c`^Wenn Schweine fliegen könnten, wärst du ne Rakete`c");
                break;
            case 16:
    output ("`c`^Wo bin ich? Wer bist du? Wer bin ich? Oh...Mann!!!`c");
                break;
            case 16:
    output ("`c`^Ich hasse meinen Job...und du?`c");
                break;
        }
}
output('`c`b&copy; by <a href="http://www.firedragonfly.de/underworld" target="_blank">Gimmick & Savomas</a>`b`c',true);
page_footer();
?>