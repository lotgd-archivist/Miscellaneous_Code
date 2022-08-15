
<?php
// Special zum Weihnachtsmarkt
// http://eranya.de
// Autor: Silva

if(!isset($session))
{
        exit();
}

$str_specname = basename(__FILE__);
$session['user']['specialinc'] = $str_specname;
$str_filename = 'forest.php';
$str_tout = "`n`n";
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
if(date('m') == 12 && date('j') < 27)
{
        $str_tout .= "`c`b`vMitten im Wald...`b`c`n";
        switch($str_op)
        {
                // Anfang: Spiel wählen
                case '':
                        $str_tout .= "`VAuf deinem Weg passierst du eine etwas lichtere Stelle - und hälst verdutzt im Laufen inne. Ein Mädchen rennt wie wild
                                      zwischen den Bäumen umher, den Blick starr nach oben gerichtet, mit nichts bekleidet außer einer dünnen Strumpfhose und
                                      einem langen Nachtgewand, das ihr sicherlich bis zu den Knöcheln reichen dürfte. Genau kannst du es allerdings nicht sagen,
                                      denn das Mädchen hält den unteren Rand des Hemds mit beiden Händen umgriffen, so, als wolle sie damit etwas auffangen - aber
                                      was bitte sollte denn hier mitten im Wald vom Himmel fallen? Ihre Miene ist konzentriert - sie scheint dich nicht einmal
                                      bemerkt zu haben.`n
                                      `n
                                      Was wirst du tun?";
                        addnav('Helfen');
                        addnav('Das Mädchen ansprechen',$str_filename.'?op=talk');
                        addnav('Zurück');
                        addnav('W?Weitergehen',$str_filename.'?op=leave');
                break;
                // Mädchen ansprechen
                case 'talk':
                        $str_tout .= "`VKurz zögerst du, doch die Neugier ist zu groß. Also wanderst du zu dem Mädchen hinüber, räusperst dich laut und wartest,
                                      bis, es dich bemerkt. Ihre Augen sind im ersten Moment groß vor Überraschung, doch dann spricht plötzlich Hoffnung aus
                                      ihrem Blick. Im nächsten Moment steht sie vor dir und sieht dich bittend an. `v\"Gott sei Dank seid Ihr hier! Ihr müsst mir
                                      unbedingt helfen. Gleich kommt der Sternenschauer - und ich muss ihn auffangen, ich, ganz allein! Das schaffe ich doch
                                      nie!\" `VSternenschauer? Davon hast du ja noch nie gehört. Allmählich zweifelst du ein bisschen am Verstand des Mädchens...
                                      Willst du trotzdem deine Zeit für sie opfern und ihr helfen?";
                        addnav('Helfen');
                        addnav('Dem Mädchen helfen',$str_filename.'?op=help');
                        addnav('Zurück');
                        addnav('Lieber nicht',$str_filename.'?op=leave');
                break;
                case 'help':
                        $str_tout .= "`VDu zögerst kurz, doch der bittende Blick lässt dich erweichen. Also nickst du zustimmend - und wirst im nächsten Moment
                                      von dem Mädchen mit zu der Stelle gezogen, an der sie eben noch hin und her gelaufen ist. `v\"Hier irgendwo wird er
                                      herunterkommen. Es kann jeden Moment soweit sein!\"`V, klärt sie dich auf und blickt bereits wieder suchend in den Himmel.
                                      Auch du siehst einmal testweise nach oben, ...`n`n";
                        $int_erand = e_rand(1,5);
                        switch($int_erand)
                        {
                                case 1:
                                case 2:
                                case 3:
                                        $int_sttaler = ceil($int_erand*1.5);
                                        $str_tout .= "... und tatsächlich! Ein Stern fällt herab - und trifft dich ungünstig am Kopf. Autsch! Neben dir hörst du
                                                      das Mädchen laut jubeln, dann rennt es schon zur nächsten Stelle und ruft dir derweil ein: `v\"Los, beeilt
                                                      Euch!\" `Vzu. Nun sind es gleich zwei Leute, die wie die Wilden zwischen den Bäumen umherrennen - und
                                                      erst wieder stoppen, als auch der letzte Stern gefangen oder aufgesammelt ist. Schwer atmend bleiben du und
                                                      das Mädchen schließlich stehen. Es lächelt dir dankbar zu - und greift dann plötzlich vor sich in die Kuhle
                                                      des Nachthemds, um dir anschließend etwas in die Hand zu drücken. `v\"Danke, du warst eine wirklich große
                                                      Hilfe\"`V, meint es noch, dann verschwindet es von jetzt auf gleich zwischen dem Buschwerk. Verdutzt siehst
                                                      du auf deine Hand herab. Das waren gar keine Sterne. Das waren Taler! Und du hast gerade
                                                      `&".$int_sttaler." `Vdavon geschenkt bekommen.";
                                        db_query('UPDATE account_extra_info SET sternentaler = sternentaler+'.$int_sttaler.' WHERE acctid = '.$session['user']['acctid']);
                                break;
                                case 4:
                                case 5:
                                        $str_tout .= "... doch mehr als ein paar Schneewolken kannst du beim besten Willen nicht entdecken. Neben dir beginnt
                                                      das Mädchen wieder hin und her zu laufen, den Blick starr nach oben gerichtet. Und als sie dabei mehrmals fast in
                                                      dich hineinläuft, kommst du um die Erkenntnis nicht herum, dass sie dich schon längst wieder vergessen hat.
                                                      Verärgert darüber, dass du deine Zeit an eine junge Irre verschwendet hast, ziehst du weiter.";
                                break;
                        }
                        $str_tout .= "`n`n`FDu verlierst einen Waldkampf.";
                        $session['user']['turns']--;
                        $session['user']['specialinc'] = '';
                break;
                // Weg hier!
                case 'leave':
                        $str_tout .= "`VDu lässt das Mädchen Mädchen sein und ziehst weiter.`n";
                        $session['user']['specialinc'] = '';
                break;
        }
}
else
{
        $str_tout .= "`VAuf deinem Weg passierst du eine etwas lichtere Stelle im Wald. Du nutzt die Gunst der Stunde und hältst ein kleines Nickerchen.
                       Danach fühlst du dich ausgeruht genug für ein weiteres Abenteuer.`n";
        $session['user']['turns']++;
        $session['user']['specialinc'] = '';
}
output($str_tout);
?>

