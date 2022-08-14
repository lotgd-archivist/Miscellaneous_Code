
<?php
/* ******************* 
Tempel der GÃ¶tter 
Written by Romulus von Grauhaar 
    Visit http://www.scheibenwelt-logd.de.vu

Das Special fÃ¼gt einen Tempel im Wald hinzu, bei dem Spieler eine beliebige Menge Gold spenden kÃ¶nnen. 
Ab einer gewissen Menge Gold passiert ein zufÃ¤lliges Ereignis, je nach dem Gott, dem der Tempel geweiht ist.
Sowohl der benÃ¶tigte Goldbetrag als auch die Namen der Gottheiten lassen sich ganz einfach am Skriptanfang
vom Admin festlegen.
Der Sinn dieses Specials ist, dass viele Spieler kurz vor ihrem Drachenkill eine Menge Gold Ã¼brig haben, was sie 
beim Drachenkill verlieren wÃ¼rden. Hier kÃ¶nnen sie mit etwas GlÃ¼ck brauchbare Dinge dafÃ¼r bekommen, allerdings
kann der Schuss auch nach hinten losgehen (wobei die negativen Auswirkungen nicht dauerhaft sind, immerhin
hat der Spieler ne Menge Gold geopfert)

Um das Special zu benutzen, muss folgender SQL-Befehl ausgefÃ¼hrt werden: 

ALTER TABLE 'accounts' ADD 'tempelgold' INT( 30 ) DEFAULT '0' NOT NULL;

Optional kann in der user.php an geeigneter Stelle eingefÃ¼gt werden:
    "tempelgold"=>"Gold im Tempel gespendet,int",
so dass der Admin die Datenbank-Variable im User-Editor bearbeiten kann.

!!! Changes by anpera !!!
- no field must be added into database
- no changes to user.php needed
- all donations go to settings table and all players must help together

******************* */  


// Die nun folgenden Variablen konfigurieren das Special. Die Variable $spendenbetrag steht fÃ¼r den
// Betrag an Gold, den ein Spieler gespendet haben muss, damit die GÃ¶tter reagieren.
// Die einzelnen GÃ¶tternamen sind frei wÃ¤hlbar.

$spendenbetrag = "10000";
$gott_gem = "Aphrodite";
$gott_defense = "Om";
$gott_hp = "dem Schicksal";
$gott_attack = "Mephistos";
$gott_charm = "Aphrodite";
$gott_fight = "Fexez";
$gott_kill = "der Gott der Waldkreaturen";
$gott_hurt = "der Gott der Waldkreaturen";
$gott_spec="Foilwench";


// $session['user']['specialinc']="tempel.php"; 

if ($_GET['op']=="verlassen"){    
    output("`@Du lÃ¤ÃŸt den alten, baufÃ¤lligen Tempel hinter dir.");
    $session['user']['specialinc']="";
    //addnav("ZurÃ¼ck in die weite Welt","forest.php");    
}else if ($_GET['op']=="spenden"){    
    $session['user']['specialinc']="tempel.php"; 
    addnav("50 Gold spenden","forest.php?op=spendeneingang&betrag=50");
    addnav("100 Gold spenden","forest.php?op=spendeneingang&betrag=100");
    addnav("500 Gold spenden","forest.php?op=spendeneingang&betrag=500");
    addnav("1000 Gold spenden","forest.php?op=spendeneingang&betrag=1000");
    addnav("5000 Gold spenden","forest.php?op=spendeneingang&betrag=5000");
    addnav("Doch nichts spenden","forest.php?op=verlassen");
    output("Wieviele GoldstÃ¼cke spendest du fÃ¼r die Renovierung des Tempels?",true);
}else if ($_GET['op']=="spendeneingang"){
    if ($_GET['betrag']>$session['user']['gold'])    {
        output("`@Tja, das hast du dir wohl so gedacht. Soviel Gold hast du gar nicht dabei. Wenn das mal hoffentlich nicht die GÃ¶tter bemerkt haben.`n`n");
        output("Du verlÃ¤sst den Tempel, bevor die GÃ¶tter auf deinen kleinen VerzÃ¤hler aufmerksam werden.");
        //addnav("ZurÃ¼ck in die weite Welt","forest.php");
    }else{
        $betrag=$_GET['betrag'];
        $drin=getsetting("tempelgold",0)+$betrag;
        output("`^`bDu spendest `&".$betrag."`^ Gold fÃ¼r die Tempelrenovierung. ");
        //debuglog("spendete $betrag Gold fÃ¼r die Tempelrenovierung");
        savesetting("tempelgold",$drin);
        $session['user']['gold']-=$betrag;
        if ($betrag>100) $session['user']['reputation']+=3;
        output("Die Gottheit, der der Tempel geweiht ist, hat deine Spende registriert.`b");
        addnav("Den Tempel verlassen","forest.php");
        output("`nAm GerÃ¤usch, das deine GoldstÃ¼cke beim Einwerfen verursachen, vermutest du, dass bisher etwa ".($drin+round($drin/100*e_rand(-3,3)))." gespendet worden sein muss. ");
        if($drin >= $spendenbetrag) {
            output("`@Nachdem du die GoldmÃ¼nzen in den Opfer stock geworfen hast, ertÃ¶nt plÃ¶tzlich ein Donnern. Anscheinend hat die Gottheit, der der Tempel geweiht ist, deine groÃŸzÃ¼gigen Gaben bemerkt.`n`n");
            output("`@Vor dir erscheint die Gottheit, der der Tempel geweiht ist, nÃ¤mlich `^");
            savesetting("tempelgold","0");
            switch(e_rand(1,7)) {
                          case 1:
                              output($gott_gem."`@. Das GlÃ¼ck scheint dir hold zu sein, denn ".$gott_gem." Ã¼bberreicht dir `\$4 Edelsteine`@!");
                $session['user']['gems']+=4;
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_gem." mit groÃŸem steinernen Reichtum beschenkt.");
                            break;
                          case 2:
                              output($gott_defense."`@. Mit gÃ¶ttlicher Kraft wÃ¤chst deine `\$VerteidigungsstÃ¤rke`@, als Dank fÃ¼r deine Spenden!");
                $session['user']['defence']+=2;
                addnews("`%".$session['user']['name']."`7s Haut wurde in einem Tempel von ".$gott_defense." widerstandsfÃ¤higer gemacht.");
                            break;
                          case 3:
                              output("`^".$gott_attack."`@. Mit gÃ¶ttlicher Kraft wÃ¤chst deine `\$AngriffssstÃ¤rke`@, als Dank fÃ¼r deine Spenden!");
                $session['user']['attack']+=2;
                addnews("`%".$session['user']['name']."s`7 Muskeln wurden in einem Tempel von ".$gott_attack." gestÃ¤rkt.");
                            break;
                          case 4:
                              output($gott_hp."`@. Dein Schicksal, zusÃ¤tzliche `\$Trefferpunkte`@ dauerhaft zu besitzen, erfÃ¼llt sich als Dank fÃ¼r deine Spenden!");
                $session['user']['maxhitpoints']+=2;
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_hp." mit erhÃ¶hter Lebenskraft versehen.");
                            break;
                          case 5:
                              output($gott_fight."`@. Mit gÃ¶ttlicher Kraft darfst du am heutigen Tag `\$3 WaldkÃ¤mpfe`@ mehr bestreiten, als Dank fÃ¼r deine Spenden!");
                $session['user']['turns']+=3;
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_fight." mit neuen Kampfrunden gesegnet.");
                            break;
                          case 6:
                              output($gott_charm."`@. Mit gÃ¶ttlicher Kraft siehst du wesentlich besser aus. Du erhÃ¤lst `\$3 Charmepunkte`@ als Dank fÃ¼r deine Spenden!");
                $session['user']['charm']+=3;
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_charm." zu einem besser aussehenden ".($races[$session['user']['race']])." `7gemacht.");
                            break;
                          case 7:
                              output($gott_hurt."`@. Was hast du dir nur dabei gedacht, diese Gottheit zu beschwÃ¶ren, die fÃ¼r ihre Ausraster und SchlÃ¤gereien berÃ¼hmt ist? Nach einem harten `\$Schlag`@ erwachst du aus einer Ohnmacht und hast fast alle Lebenspunkte verloren.");
                $session['user']['hitpoints']=1;
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_hurt." schwer verletzt. Man sollte halt nicht mit gefÃ¤hrlichen GÃ¶ttern herumspielen.");
                            break;
                          case 8:
                              output($gott_spec."`@.`n");
                increment_specialty();
                addnews("`%".$session['user']['name']."`7 wurde in einem Tempel von ".$gott_spec." in seiner Fertigkeit unterrichtet.");
                            break;
            } //switch
        } // benÃ¶tigten betrag erreicht?
    }
    $session['user']['specialinc']=""; 
}else{
    output("`@Auf deiner Reise kommst du plÃ¶tzlich an einem Tempel vorbei. Ein imposanter, aber schon leicht verfallener Bau mit SÃ¤ulen vor dem Eingang. Du betrittst das heilige Haus und siehst, dass der Tempel eine Renovierung dringend notwendig hÃ¤tte. Das einzige, was noch intakt zu sein scheint, ist der Opferstock, Ã¼ber dem ein neu wirkendes Schild prangt: `n`&\"Sehr geehrter Besucher, unser Tempel ist leider dem Verfall preisgegeben, bitte spende etwas fÃ¼r die Renovierung. Die GÃ¶tter mÃ¶gen es dir danken.`nGez. der Hohepriester.\"`@"); 
    output("`n`nWas wirst du tun?"); 
    addnav("Spende etwas","forest.php?op=spenden");
    addnav("Tempel verlassen","forest.php?op=verlassen");
    $session['user']['specialinc']="tempel.php"; 
}
//page_footer(); 
?>

