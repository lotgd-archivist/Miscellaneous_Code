
ï»¿<?php
//2010816
require_once("common.php");
if ($session['user']['beta']) addcommentary();
checkday();

if ($_GET['beta']=='true'){
    $session['user']['beta']=1;
}elseif ($_GET['beta']=='false'){
    $session['user']['beta']=0;
}

page_header("Ins Auge fallender Pavillion");
if (!$session['user']['beta']) {
    addnav("Beitreten!", "pavilion.php?beta=true");
} else {
    addnav("Austreten!", "pavilion.php?beta=false");
}
addnav("Z?ZurÃ¼ck zur Stadt", "village.php");

output("`7Du betritts den Pavillion, und befindest dich sofort in einer Menge von Leuten, die sich Ã¼ber Programmfehler, Beta-Programme und Balance unterhalten. MÃ¤chtige Krieger haben eine grosses Empfinden fÃ¼r Gerechtigkeit und Balance, wenn sie droht aus den Fugen zu geraten. Du lachst als du den GesprÃ¤chen zuhÃ¶rt, weil du noch nicht alles verstehst.`n`n");

if (!$session['user']['beta']) {
    output("Ein junger Mann gekleidet in einem Livree der `&Pontifex `5Moon`%childe `7 kommt dir entgegen.  `3\"Du siehst aus wie ein starker und mutiger Abenteurer!\"`7, sagt er, `3\"MÃ¶chtest  du dem `&Pontifex`3 helfen dieses Reich sicherer und besser zu machen fÃ¼r alle seine BÃ¼rger?  Alles was du zu tun hast, ist dem `&Pontifex`3 beizutreten, ich gebe zu es ist ein bisschen gefÃ¤hrlich dem Pontifex zu helfen, aber ich meine wo bekommst du sonst umsonst Nahrung und Trinken wann immer du willst. Du kannst auch jederzeit wieder austreten, falls du nicht mehr mÃ¶chtest !\"`0`n`n");
    output("`7(In Deutsch, 'Bist du bereit die Beta Features zu testen?')`n");
}else{
    output("Nachdem du dich fÃ¼r das `&Pontifex's`7 Beta-Programm verpflichtet hast, versteht du Ã¼ber was hier geredet wird. Deine Stimme kann Ã¼ber leben und Tod entscheiden fÃ¼r alle Krieger, welche hier spielen. Schliesslich bist du jetzt einer von uns, und damit verpflichtet dem mÃ¤chtigen  Pontifex zu sagen, wo sein Code versagt.`0`n`n");
    output("`7(In Deutsch, 'Du bist ein williger Beta Tester.  Bitte tritt wieder aus, wenn du nicht bereit bist ein Versuchsmeerschweinchen zu spielen `&und`7 gib bitte nur konstruktive BeitrÃ¤ge aber und nutze Fehler im Programm nicht aus. Ausserdem poste Fehler so schnell wie mÃ¶glich hier.')`n`n");
    viewcommentary("beta", "Diskutiere hier", 50);
}
page_footer();
?>


