
<?phprequire_once "common.php";addcommentary();checkday();page_header("Das Samhainfest");$session['user']['standort']="Samhainfest";$out="`c<img src='./images/samhain.jpg'>`c`n`n";$out.="`c`b`qS`Qa`Nm`Xh`§a`_i`7n`)f`èe`às`ut`b`c`n`n`4Klein`ùe fla`Àcke`°rnde Fla`Qmmenschalen und Fackeln säumen den Weg, ";$out.="den du besch`qreite`Qst und welcher dich unweigerlich zu di`qese`Qm Ort führt. Schon aus der En`qtfe`Qrnung kannst ";$out.="du die lauten Stimmen hören, beg`qle`Qitet von leiser Musik. Der Joglar scheint dort also auch sein `qBes`Qtes zu ";$out.="geben. Ein Fest? Na da bist du aber mal `qges`Qpannt, deine Neugierde ist geweckt. Du kommst näher und siehst, dass ";$out.="alles wu`qnde`Qrvoll geschmückt ist. `qLeu`Qchtende Kürbisse zieren hier ebenso den Rand des P`qlat`Qzes und ";$out.="einige andächtig singende Gestalten stehen um ein flackerndes `qLag`Qerfeuer herum, das die Mitte des ";$out.="Platzes einn`qimm`Qt. Was hier für ein Fest gefeiert wird, wird dir natü`qrli`Qch schnell klar, wenn du dir das ";$out.="Alles so ansie`qhst`Q. Hier und dort kannst du auch einen Stand erb`qlic`Qken, wo die köstlichsten Herrlich`qkei`Qten ";$out.="angeboten werden, die man aus `qKür`Qbissen machen kann. Aber nicht nur das, du erblickst auch ein Spanferkel, ";$out.="wel`qche`Qs sich am Spieß brutzelnd über den lodernden Flammen dreht. Der `qGer`Quch ist bereits jetzt nur allzu verlockend, ";$out.="du hättest sicher auch `qger`Qn ein großes Stück von dem Ferkel. An Wein und anderen Ge`qträ`Qnken soll es ebenfalls nicht ";$out.="mangeln. `qDu`Q beschließt, dich hier noch et`°was g`Àenaue`ùr umzus`4ehen.`n`n`0";addnav("Geschenkestand","samhain_geschenke.php");addnav("Getränkestand","samhain_getraenke.php");       addnav("Tanzfläche","samhain_tanz.php");       addnav("Zum Dorf","village.php");$out.="`n`n`nMit anderen Feiernden unterhalten:`n";output($out,true);viewcommentary("samhain","Hinzufügen",25,"sagt",1,1);page_footer();
