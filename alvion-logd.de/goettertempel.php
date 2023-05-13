
<?php

//
// Tempel der Toleranz (c)by Ventus
// Erstmals erschienen auf www.Elfen-Portal.de

require_once "common.php";

if ($_GET[op]=="") {
    addcommentary();
    checkday();
    page_header("Tempel der Toleranz.");
    $session['user']['standort']="Tempel der Toleranz";
    output("`b`c`_Der Saal:`0`c`b");
    output("`n`ÍEhr`Áfür`_chti`Og betrittst du den imposanten Te`_mpe`Ál. Du `Íbefindest dich in einer gro`Áßen un`_d ger`Oäumigen Halle mit hohen M`_arm`Áorsäu`Ílen, welche bis zur Decke r`Áeich`_en u`Ond mit merkwürdigen Symbolen verzi`_ert `Ásind`Í. Um dich herum beten ei`Áfrig`_ viel`Oe der gläubigen Priester z`_u ih`Árer e`Írwählten Gottheit. Dieser b`Áeein`_drucke`Onde Tempel muss der Tempel `_der T`Áoler`Íanz sein und jeder, der an die g`Áuten `_Gött`Oer glaubt, ist hier w`_illko`Ámmen!`Í Was möchtest du tun`Á?`n`n`0");
        //output("`n");
    output("`n`ÍMit den anderen Anwesenden flüstern`0");
    output("`n`n");
        viewcommentary("Tempel","Flüstern:",30,"sagt",1,1);

                                addnav("Zum Oberpriester gehen","goettertempel.php?op=priester");
                        addnav("Den Priester im roten Gewand anreden","goettertempel.php?op=roterpriester");
                        addnav("Den Priester im grünen Gewand anreden","goettertempel.php?op=gruenerpriester");
                        addnav("Den Priester im schwarzen Gewand anreden","goettertempel.php?op=schwarzerpriester");
                        addnav("Den Priester im sandfarbenen Gewand anreden","goettertempel.php?op=sandpriester");
                        addnav("Den Priester im grauen Gewand anreden","goettertempel.php?op=grauerpriester");
                        addnav("Den Priester im braunen Gewand anreden","goettertempel.php?op=braunerpriester");
                        addnav("Den Priester im weissen Gewand anreden","goettertempel.php?op=weisserpriester");
                        addnav("Den Priester im violetten Gewand anreden","goettertempel.php?op=violetterpriester");
                              addnav("Zurück in die Stadt","village.php");



}


if ($_GET[op]=="priester") {
        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        output("`b`c`2Der Saal:`0`c`b");
        if($session['user']['gottjanein']==0) output("`n`%Du sprichst den Oberpriester an, aber er antwortet dir nicht. Er zeigt nur Stumm auf ein Buch. Als du dir das Buch näher ansiehst, stellst du fest, dass hier tausende Leute eingetragen sind. Hinter ihren Namen stehen ihre Gottheiten. Nachdem du das gesehen hast, fragst du dich, warum DU eigentlich keinen Gott verehrst. Was tun?`0");
        if($session['user']['gottjanein']==1){
                if($session['user']['rp_only']=='0'){
                        output("Der Priester scheint nicht answesend zu sein, du suchst ihn vergeblich!");
                }else{
                        addnav("Deinem Gott entsagen","goettertempel.php?op=entsagen");
                }
        }
        output("`n`QMit den anderen Anwesenden flüstern`0");
        output("`n`n");
        viewcommentary("Tempel","Flüstern:",30,"sagt");

        if($session['user']['gottjanein']==0) addnav("Einen Gott auswählen","gottwahl.php");
        addnav("Zurück","goettertempel.php");
}

if ($_GET[op]=="entsagen"){
        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        output("Willst du wirklich deinem Gott entsagen? Es wird ihn nicht freundlich stimmen und es wird dich 10 Donationpunkte kosten.");
        addnav("Ja, ich will","goettertempel.php?op=entsagen2");
        addnav("Zurück");
        addnav("Nein, zurück","goettertempel.php");
}
if ($_GET[op]=="entsagen2"){
        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        $donleft=$session['user']['donation']-$session['user']['donationspent'];
        if($donleft>=10){
                $session['user']['donationspent']+=10;
                $session['user']['gottjanein']='0';
                $session['user']['gott']=0;
                $session['user']['reputation']-=5;
                output("Du hast deinem bisherigen Gott entsagt. Er wird nicht erfreut darüber sein. Du verlierst Ansehen.");
        } else {
                output("Besorge dir erst einmal die nötigen Mittel.");
        }
        addnav("Zurück");
        addnav("Zurück zum Tempel","goettertempel.php");
}

if ($_GET[op]=="roterpriester") {

    checkday();
    page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";

    output("`b`c`2Der rote Priester:`0`c`b");


if ($session['user']['gott']==1){
output("`n`%Du sprichst den Priester im roten Gewand an.`0");
output("`nAh, ein Sohn des Tempus! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du widersprechen kannst, fängt er an zu reden:`0");
output("`n`$

Tempus, als Gott der Schlachten und Krieger, wird immer dann angebetet, wenn es um die Wendung des Schlachtenglücks, um Mut im Angesicht des Feindes und um Härte im Kampf Mann-gegen-Mann geht.

Tempus ist ein chaotischer Gott. Für ihn ist Krieg der beste aller möglichen Zustände, daher schürt er Konflikte, um den Mutigen und Standhaften das Schlachtenglück zu schenken. Oftmals hilft er einfach beiden Parteien, um so die Schlacht zu verlängern. Er ist ehrenhaft und folgt seinem Kodex, unterjocht sich jedoch keinen Regeln und folgt keinem anderen Ideal, als dem Ruhm eines erfahrenen Kriegers. Ebenso wie Mystryl entstand Tempus aus einer der Schlachten zwischen Selûne und Shar. Anders, als viele höhere Gottheiten, hält Tempus niemals direkt Kontakt mit seinen Anhängern, sondern teilt seine Wünsche durch die Geister gefallener Helden und berühmter Krieger mit.

Tempus steht mit der roten Ritterin im Bunde, die er einst zu einer Gottheit machte und die ihm nun untersteht. Des Weiteren steht er mit Uthgard, Gond, Nobanion und Valkur im Bunde. Er beschützt die Kirche Eldaths, da er das Ideal des Friedens als Kontrapunkt zu seinem Ideal des Krieges aufrecht erhalten will. Sein einziger wirklicher Feind ist der Gott Garagos, der Gott des Abschlachtens und Blutvergießens, da dieser das Ideal des Mordens über einen ehrenhaften Kodex des Kriegers stellt.

Tempus schlägt keine Schlachten. Nein, seine Gläubigen schlagen für ihn die Schlachten, erfüllt von seinem Mut und seiner Kraft. Die Zivilisation ist ein Wechselspiel aus Frieden und Krieg, und nur der ist erfolgreich, der sich im Kriege behaupten kann, denn schweigen und sich verstecken, das kann jeder zu Friedenszeiten. Nur die wahrhaft ehrbaren und standhaften Helden verdienen es, in Tempus´ Gnade zu baden. Schmeichler, die jeden Konflikt vermeiden, richten weit mehr Schaden an, als der energische Tyrann.

Die Anhängerschaft Tempus´ ist ebenso chaotisch, wie ihr Schutzpatron. Vertreter jeder Gesinnung finden sich unter dem Banner der Krieger vereint, auch wenn die Kleriker des Tempus weiterhin nur einen Gesinnungsschritt von ihrer Schutzgottheit abweichen. Sie agieren als Wächter über Schlachten, darauf bedacht, einen Krieg ruhmreich zu führen und ihn nicht in ein Gemetzel ausarten zu lassen. Oftmals ziehen sie auch umher, um sich den Herausforderungen zu stellen, die auf ihrem Weg zu einem Veteranen der Kriegskunst liegen.
Seine Tempel ähneln festunsgartigen Militärkasernen. `0");
addnav("Zurück","goettertempel.php");

        //output("`n");

}else{


output("`n`%Du sprichst den Priester im roten Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");
addnav("Zurück","goettertempel.php");
}


}








if ($_GET[op]=="gruenerpriester") {

    checkday();
    page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";

    output("`b`c`2Der rote Priester:`0`c`b");


if ($session['user']['gott']==2){
output("`n`%Du sprichst den Priester im grünen Gewand an.`0");
output("`nAh, ein Sohn der Mielikki! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du wiedersprechen kannst, fängt er an zu reden:`0");
output("`nMielikki, als Göttin der Wälder und der Waldläufer, wird immer dann angebetet, wenn es um die Wahrung der Natur und des Gleichgewichtes geht.

Mielikki ist eine fröhliche, selbstbewusste Göttin, die gern lächelt. Sie ist loyal gegenüber allen, die ihr Vertrauen gewonnen haben, und ist daher sehr vorsichtig, wen sie ihren Freund nennt. Obwohl sie den Tod als Teil des Lebenskreises akzeptiert, ist es für sie meist unerträglich, jemanden leiden zu sehen und sie ist daher nur zu gern bereit, zu helfen.

Mielikki ist Silvanus untergeben und steht mit den anderen Naturgöttern (ausser den bösen) im Bunde. Außerdem hegt sie Beziehungen zu Shaundakul und Lathander. Ihre Feinde sind Malar, Talos und Talona.

Das Dogma Mielikkis lautet wie folgt: Harmonie ist mehr als nur ein Traum. Alles, was die Natur hervorgebracht hat, wurde so geformt, dass es mit allen anderen Kindern des Gleichgewichts zusammen leben kann. Akzeptiere die Wildnis und fürchte sie nicht, denn du bist ein Teil von ihr, wenn du akzeptierst, dass auch du ein Kind der Natur bist. Schütze den Wald, verteidige das Gleichgewicht und lehre alle, denen du begegnest, dass sie der Natur zurückgeben sollten, was sie ihr genommen haben, damit das Gleichgewicht gewahrt bleibt.

Die Anhänger Mielikkis bestehen zum größten Teil aus Waldläufern und Druiden. Aber auch Förster finden sich unter ihnen, die bestrebt sind, das ökologische Gleichgewicht der Wildnis zu erhalten. Drizzt Do´Urden, der berühmt-berüchtigte Drowwaldläufer aus dem Norden, dient Mielikki.
Die Tempel Mielikkis sind natürliche Felsformationen und Waldlichtungen`0");
addnav("Zurück","goettertempel.php");

        //output("`n");

}else{


output("`n`%Du sprichst den Priester im grünen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}






if ($_GET[op]=="schwarzerpriester") {

    checkday();
    page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";

    output("`b`c`2Der schwarze Priester:`0`c`b");


if ($session['user']['gott']==3){
output("`n`%Du sprichst den Priester im schwarzen Gewand an.`0");
output("`nAh, ein Sohn der Shar! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du wiedersprechen kannst, fängt er an zu reden:`0");
output("Shar, als Herrin der Dunkelheit, wird immer dann angebetet, wenn es um Rache, Neid, Verlust, Geheimniskrämerei oder einfach nur dem Sieg der Dunkelheit über das Licht geht.

Shar ist in ihrer Erscheinung meist dunkel gewandet, doch stets eindeutig und verlockend weiblich. Ihre Augen sind schwarze, lichtlose Löcher, die jegliche Hoffnung in sich aufsaugen und nie wieder aufkeimen lassen. Als Gegenstück zu Selûne war es Shar, die das Multiversum in Dunkelheit stürzte aus Wut darüber, dass ihre Schwester Toril das Licht eingehaucht hatte. Ebenso war sie es, die als Antwort auf die Erschaffung Mystryls das Schattengewebe erschuf, jenes Gewebe aus Schatten, das es skrupellosen Magiern ermöglicht, Magie zu wirken, ohne sich den Gesetzen Mystras unterwerfen zu müssen. Seit ihrer beider Entstehung ist Shar es, die das Licht Selûnes am stärksten bedroht und den Krieg zwischen Licht und Dunkelheit zugunsten des Vergessens vorantreibt.

Shar hasst ihre Schwester zutiefst, was von jener nicht minder zurückgegeben wird. Sie hat keine wirklichen Verbündeten, arbeitet aber oft mit Talona zusammen. Weitere Feinde Shars sind Shaundakul, Lathander und Loviatar, die Herrin der Schmerzen, deren ebenso dunkle Ziele denen Shars jedoch entgegengesetzt sind, da ihr Vergessen Erlösung von den propagierten Schmerzen bringt.

Geheimnisse, Dunkelheit, die allumfassende Nacht; all dies ist Shar, all dies ist die Wahrheit, versteckt im Vergessen. Es gibt keine Hoffnung, weder auf das Licht, noch auf Erfolg, denn all das ist Lüge. Verlust ist Grundlage des Lebens, und sich ihm zu ergeben, bringt dich näher zu Shar, der dunklen Herrin. Lösche das Licht aus, verstecke dich vor ihm. Hilfsbereitschaft anderen, als Shars Kindern gegenüber, ist eine Sünde. Gehorche denen, die Shars Worte vernehmen, denn sie sind es, die dir Erlösung bringen.

Die Anhängerschaft Shars besteht aus vereinzelten kleinen Zellen, angeführt von einer starken Autorität, welche in einem Bereich von einem Hohepriester aufeinander abgestimmt werden. Diese kleinen Zellen arbeiten darauf hin, still und heimlich die Anhängerschaft Shars zu vergrößern, ohne dabei aufzufallen und so womöglich Widerstand heraufzubeschwören. Dementsprechend würde ein Anhänger Shars niemals seinen Glauben offenbaren, außer er kann sich gewahr sein, dass sein Gegenüber diesen Glauben teilt.
Shartempel sind klein und versteckt, karg eingerichtet und schnell zu vergessen. So schützen die Anhänger ihre Geheimnisse und sorgen für ihre Sicherheit.`0");
addnav("Zurück","goettertempel.php");

        //output("`n");

}else{


output("`n`%Du sprichst den Priester im schwarzen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}











if ($_GET[op]=="sandpriester") {

    checkday();
    page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";

    output("`b`c`2Der sandfarbene Priester:`0`c`b");


if ($session['user']['gott']==4){
output("`n`%Du sprichst den Priester im sandfarbenen Gewand an.`0");
output("`nAh, ein Sohn des Deneir! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
output("`nBevor du wiedersprechen kannst, fängt er an zu reden:`0");
output("Deneir wird immer dann angebetet, wenn es darum geht, um das Gelingen eines Bildes, eines Schriftstücks oder einer Karte zu bitten.

Deneir ist der Schreiber Oghmas und damit gewissermaßen seine rechte Hand. In vielen Zeichnungen wird seine Erscheinung als die eines alten, weisen Mannes mit langem, weißem Bart dargestellt. Er ist der Bewahrer allen Wissens, es wird behauptet, dass Deneir über alles Bescheid weiß und alles hütet und schützt, was jemals geschrieben oder gezeichnet wurde. Dementsprechend ist er ein entschiedener Feind all jener, die sich bemühen, Quellen des Wissens zu zerstören, sie verbergen oder gar falsche Informationen verbreiten. Seine Art, sich zu verhalten, ist bisweilen sehr unterschiedlich. Zu manchen Zeiten verwendet er eine äußerst gestelzte Hochsprache und benimmt sich gemäß einer Etikette, die schon vor langer Zeit aus dem faerûnschen Alltag verschwunden ist, mal versucht er, sich modern zu geben, bemüht sich, die modernen Dialekte zu sprechen und sich entsprechend zu verhalten, was ihm teilweise gelingt, teilweise aber auch unfreiwillige Komik in sein Auftritt bringt. Der Ausspruch: -Eine Geschichte wie Deneir erzählen- beschreibt eine Eigenart Deneirs, eine Geschichte bis ins kleinste Detail mit unzähligen, teilweise ineinander verschachtelten Nebenhandlungen zu erzählen. Derartigem zuzuhören, erfordert sehr viel Zeit und Geduld, vermittelt aber dem, der ausharren kann, gleichzeitig auch eine Menge Wissen und bisweilen auch Einsicht in die Dinge.

Deneir lehrt, das alles Wissen, das nicht aufgeschrieben wird, verloren ist. Demzufolge sind alle Anhänger Deneirs verpflichtet, alles aufzuschreiben, was ihnen in ihrem Leben begegnet und von allen Büchern, die ihnen in die Hände fallen, Kopien anzufertigen und der örtlichen Bibliothek zuzuführen. Eine Auswahl wird dabei nicht getroffen, jede Art des persönlichen Ausdrucks und jede Meinung hat Platz in den großen Bibliotheken. Ein Buch zu zerstören bedeutet, Wissen zu zerstören, ein Frevel, der gesühnt werden muss und erst dann wirklich aus der Welt geschafft ist, wenn der Frevler das Buch ersetzt hat, um das enthaltene Wissen zu bewahren. Lesen und Schreiben sind Fertigkeiten, die verbreitet werden müssen, auch Wissen und Bildung müssen weiter gegeben werden.

Bücher, Papier, Schriftrollen, darum dreht es sich auch in den zahlreichen Tempeln des Deneir, die über ganz Faerûn verbreitet sind. Am besten sind sie zu beschreiben als mehr oder weniger große und dicht zugestellte Bibliotheken, denen ein entsprechendes Heiligtum und einige Schlafräume angegliedert sind. Anhänger des Deneir erfreuen sich in Faerûn zumeist großer Beliebtheit, da sie jedem, der willig ist, aber keinen Lehrer hat, das Lesen und Schreiben beibringen. Die Tatsache, dass sie stets bemüht sind, Wissen zu verbreiten und die Leute zu unterrichten, bedeutet jedoch nicht, dass kein Geheimnis bei ihnen sicher wäre.
Anhänger des Schreibers des Oghma sind darüber hinaus bekannt und geschätzt dafür, die Schreiber des Volkes zu sein. Wann immer es gilt, eine Schrift zu vervielfältigen, findet man unter ihnen gegen entsprechendes Entgelt, dessen Höhe sich nach dem Vermögen des Fragers richtet, stets jemanden, der dazu bereit ist. Auch Geheimnisse werden angefertigt und die Gerüchte über verborgene, geheime Bibliotheken sind nicht aus der Luft gegriffen, auch wenn sie in der Regel durch Magie wie Kreaturen scharf bewacht werden und verteidigt gegen alle, denen es gelingt, unberechtigt in sie einzudringen.
Der einzige echte Feiertag der Kirche Deneirs ist Schildtreff. An diesem Tag steht der Schatz der Bibliotheken jedem offen, und ein jeder kann Einsicht in Schriftstücke erhalten, sofern er genau nach ihnen fragt. Zu ungenaue oder allgemein gefasste Anfragen werden nicht geduldet. An diesem Tag finden auch einige komplexe Zeremonien statt, die von den höheren Priestern ausgeführt werden.
Alle anderen Gebete und Lobpreisungen finden täglich statt, mal wenn eine große Arbeit begonnen wird, mancher spricht ein Gebet, wann immer er eine neue Seite beginnt, andere, bevor sie einen Brief beginnen und nachdem sie ihn fertig gestellt haben. Die Lobpreisungen Deneirs bestehen aus Gesängen, Rezitationen oder stillem Lesen, oder auch stillen, privaten Gebeten.

Die größte Bibliothek, und damit das zentrale Heiligtum der Kirche, ist die Bibliothek des Meisters auf dem Eisendrachenberg. Keine Bibliothek kann sich mit dieser an Größe und Informationsfülle messen, noch nicht einmal Kerzenburg.`0");
addnav("Zurück","goettertempel.php");

        //output("`n");

}else{


output("`n`%Du sprichst den Priester im sandfarbenen Gewand an.`0");
output("`n `$ Mit dir habe ich nichts zu besprechen!`0");

addnav("Zurück","goettertempel.php");
}
}

if ($_GET[op]=="grauerpriester") {

        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";

        output("`b`c`2Der graue Priester:`0`c`b");
        if ($session['user']['gott']==5){
                output("`n`7Du sprichst den Priester im grauen Gewand an.`0");
                output("`j`nAh, ein Kind des Ramius! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
                output("`n`7Bevor du wiedersprechen kannst, fängt er an zu reden:`n`0");
                output("`jRamius ist der Fürst der Schatten. Er ist es, zu dem die gefallenen Seelen übergehen, und auch er ist es, der dem gefallenen Krieger neues Leben schenkt, sofern dieser sich als würdig erwiesen hat in seinen Augen. Hierfür muß der Krieger lediglich ein paar andere gefallene Seelen quälen, und wenn Ramius zufrieden ist, so gewährt er diesem Krieger neues Leben und erweckt ihn aus seinem Reich. Ramius selber ist von sehr wandelbarem Gemüt und durch und durch chaotisch. Seine Anhänger wissen nie so genau, woran sie bei ihm sind, ist er nicht umsonst der Herr der Unterwelt, seiner Schattenlande. Oft wird er für alles Übel verantwortlich gemacht, doch sieht er dies anders. Sicherlich ergötzt er sich an den Qualen und Leiden der Seelen, doch sieht er dies als sein Recht an, da ihm die Seelen gehören und er mit ihnen tun und machen kann, was er will. Solange sie sich in seine Landen aufhalten, sind sie ihm und seiner Macht ausgeliefert. Nicht selten tritt er auch gerne unter seinen Anhängern oder denen, die sich in seinen Schatten befinden, in Erscheinung, um sich einen Spaß mit ihnen zu erlauben oder sich, auf seine Art, mit ihnen zu amüsieren. Die Zahl seiner Anhänger steigt stetig, und dadurch scheinen sein Einfluß und seine Macht noch stärker zu werden und zu wachsen. Schon von Anbeginn der Zeit gab es ihn, und es wird ihn bis zum Ende aller Zeiten geben. So lange es Seelen gibt. Die Finsternis, in der die Seelen niemals ruhen, gepeinigt schreien und gequält werden. Und wie sehr auch einige sich von ihm abwenden wollen, wenn es eines gibt, das alle gemein haben, so ist dies der Tod. Der Tod ist es, der sie vereint, und der Tod ist das Einzige, dem keiner zu entfliehen vermag.`0");
                addnav("Zurück","goettertempel.php");
        }else{
                output("`n`%Du sprichst den Priester im grauen Gewand an.`0");
                output("`n `j Mit dir habe ich nichts zu besprechen!`0");
                addnav("Zurück","goettertempel.php");

        }
}
if ($_GET[op]=="braunerpriester") {

        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        output("`b`c`2Der braune Priester:`0`c`b");
        if ($session['user']['gott']==6){
                output("`n`7Du sprichst den Priester im grauen Gewand an.`0");
                output("`j`nAh, ein Jünger des Bahamut! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
                output("`n`7Bevor du wiedersprechen kannst, fängt er an zu reden:`n`0");
                output("`jBahamut ist ein riesiger Fisch, der das gesamte Weltgebilde trägt. Er ist so riesig und sein Anblick ist so strahlend, dass keines Wesen Augen ihn ertragen können.`0");
                addnav("Zurück","goettertempel.php");
        }else{
                output("`n`%Du sprichst den Priester im braunen Gewand an.`0");
                output("`n `j Mit dir habe ich nichts zu besprechen!`0");
                addnav("Zurück","goettertempel.php");

        }
}
if ($_GET[op]=="weisserpriester") {

        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        output("`b`c`2Der braune Priester:`0`c`b");
        if ($session['user']['gott']==7){
                output("`n`7Du sprichst den Priester im weissen Gewand an.`0");
                output("`j`nAh, ein Kind der Idun! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
                output("`n`7Bevor du wiedersprechen kannst, fängt er an zu reden:`n`0");
                output("`jIdun ist die Göttin der Jugend und der Unsterblichkeit. Hüterin der goldenen Äpfel, die den Göttern die ewige Jugend sichern. Sie bewahrt sie in ihrer Kiste auf, bis die Götter sie benötigen.`0");
                addnav("Zurück","goettertempel.php");
        }else{
                output("`n`%Du sprichst den Priester im weissen Gewand an.`0");
                output("`n `j Mit dir habe ich nichts zu besprechen!`0");
                addnav("Zurück","goettertempel.php");

        }
}
if ($_GET[op]=="violetterpriester") {
        checkday();
        page_header("Tempel der Toleranz.");
        $session['user']['standort']="Tempel der Toleranz";
        output("`b`c`2Der violetten Priester:`0`c`b");
        if ($session['user']['gott']==8){
                output("`n`7Du sprichst den Priester im violetten Gewand an.`0");
                output("`j`nAh, ein Kind der Mystra! Herzlich willkommen! Du bist doch sicher gekommen, um etwas über deinen Gott zu erfahren?`0");
                output("`n`7Bevor du wiedersprechen kannst, fängt er an zu reden:`n`0");
                output("`jMystra, als Göttin der Magie, wird angebetet, wenn es darum geht, ein weiteres Geheimnis der Magie zu erfahren. Doch seid auf der Hut, die Magie ist wohl ein Geschenk der Herrin, doch sollt ihr sie nur benutzen, wenn sie von Nöten ist.`0");
                addnav("Zurück","goettertempel.php");
        }else{
                output("`n`%Du sprichst den Priester im violetten Gewand an.`0");
                output("`n `j Mit dir habe ich nichts zu besprechen!`0");
                addnav("Zurück","goettertempel.php");

        }
}

page_footer();


