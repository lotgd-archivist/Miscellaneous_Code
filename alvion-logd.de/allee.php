
<?phprequire_once "common.php";addcommentary();checkday();page_header("Allee zum Obstgarten");$session['user']['standort']="Allee zum Obstgarten";addnav("Zum Obstgarten","obstgarten.php");addnav("Zurück");addnav("Zurück zum Dorf","village.php");output("`b`c`µAll`²ee `2zu`om Obs`2tg`²ar`µten`b`c`n`n");output("`µDu betritt`²st eine Al`2lee, die abw`oechseln`2d mit Kirsch`²- und Apfelb`µäumen gesäu`²mt ist. Es duf`2tet herrlic`oh nach den f`2rischen Bl`²üten diese`µr Bäume, wa`²s dich zum T`2räumen ein`olädt. Zwische`2n den Bäumen s`²teht hier un`µd da eine B`²ank, um ein w`2enig zu verw`oeilen. Ein `2leichter W`²ind geht, und d`µu hörst fröh`²liches Voge`2lgezwitsche`or. Du erkenn`2st, dass de`²r Weg noch w`µeiter führ`²t, hinaus au`2f ein Feld, w`oelches all`2e Arten v`²on Obstbäu`µmen trägt. Es i`²st sogar mö`2glich, hier u`ond da etwas z`2u pflücken, w`²enn man groß g`µenug ist, da`²s Obst an de`2n Bäumen zu e`orreichen. H`2ier könnte m`²an sich zu e`µinen Picknic`²k nieder las`2sen, oder ei`onfach nur t`2räumen.");output("`n`n`@Spazieren gehen:`n");viewcommentary("Allee","Hinzufügen",25,"spricht",1,1);page_footer();?>

