
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3\"Hallo. Haaaallo.\"`0 Du hörst Rufe aus dem Wald und schaust Dich
    suchend um. Links und rechts des Weges kannst Du aber niemanden sehen. Vor Dir
    steht auch niemand, also drehst Du mit einem unguten Gefühl Deinen Kopf, um
    hinter Dich zu blicken. Auch nichts. Aber Du hast doch etwas gehört....`n`n
    `3\"Hallo. Haaaallo.\"`0 Da ist es wieder. Es scheint irgendwie von oben zu
    kommen und Du siehst Dir die Kronen der umliegenden Bäume an. Plötzlich entdeckst
    Du einen alten Druiden, der auf seiner Suche nach Misteln hoch oben in einem
    Baum sitzt. Du gehst zu dem Baum und bist neugierig, was der alte Druide wohl
    von Dir will. `n`n
    Der Druide macht Dir ein interessantes Tausch-Angebot:`n
    \"`3Ich gebe Dir nochmal soviel Gold wie Du bei Dir trägst`0\" sagt der Druide,
    \"`3Aber nur im Tausch gegen Deine Rüstung oder Deine Waffe.`0\"`n`n
    Du überlegst nun, ob Du darauf eingehen willst.`0");
    //abschluss intro
    addnav("Tausch ausführen","forest.php?op=change");
    addnav("Lieber nicht","forest.php?op=dontdo");
    $session[user][specialinc] = "druid.php";
}
else if ($HTTP_GET_VARS[op]=="change"){   // Tauschen
    // Current balance
    //
    //
    $goldold = $session[user][gold];
    $goldnew = $goldold * 2;
    $def = $session[user][armordef];
    $att = $session[user][weapondmg];
    If ( ($def <= 1) and ($att <= 1) ) {
         output("`n\"`3Also, wenn ich mir genauer ansehe, was Du zu bieten hast, ziehe ich
         mein Tauschangebot zurück`0\" sagt der alte Druide. Er hat einfach kein
         Interesse an ".$session[user][weapon]." und ".$session[user][armor].". `n`nDu
         zeigst dafür Verständnis und gehst weiter.");
         $session[user][specialinc]="";
    }
    else if ( $session[user][weapondmg] < $session[user][armordef] ) {
         // Rüstung ist stärker und wird getauscht

         output("`nDer Druide grinst freudig, murmelt etwas in seinen Bart und plötzlich
         spürst Du eine wohlige Wärme, die von Deiner Tasche auszugehen scheint. Nun grinst
         Du auch, denn statt `9$goldold Gold`0 hast Du nun `9$goldnew Gold`0 in Händen.`0");

         $sql = "SELECT max(level) AS level FROM armor WHERE level<=".$session[user][dragonkills];
         $result = db_query($sql) or die(db_error(LINK));
         $row = db_fetch_assoc($result);

         $sql = "SELECT * FROM armor WHERE level=$row[level] and defense=1";
         $result = db_query($sql) or die(db_error(LINK));
         for ($i=0;$i<db_num_rows($result);$i++){
             $row = db_fetch_assoc($result);
         }
         output("`n`nDu musst nun Deine Rüstung `8".$session[user][armor]."`0 ablegen. `n`nDu
         willst gerade beschämt Deinen Weg fortsetzen, als Dir der alte Druide aus
         Mitleid wenigstens die kleine Rüstung $row[armorname] gibt.");
         // changes->db
         $session[user][gold] = $goldnew;
           $session[user][armor] = $row[armorname];
           $session[user][defence]-=$session[user][armordef];
           $session[user][armordef] = $row[defense];
           $session[user][defence]+=$session[user][armordef];
           $session[user][armorvalue] = $row[value];
         $session[user][specialinc]="";
    }
    else {
         // Waffe ist stärker oder gleichstark und wird getauscht

         output("`nDer Druide grinst freudig, murmelt etwas in seinen Bart und plötzlich
         spürst Du eine wohlige Wärme, die von Deiner Tasche auszugehen scheint. Nun grinst
         Du auch, denn statt `9$goldold Gold`0 hast Du nun `9$goldnew Gold`0 in Händen.`0");

         $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session[user][dragonkills];
         $result = db_query($sql) or die(db_error(LINK));
         $row = db_fetch_assoc($result);

         $sql = "SELECT * FROM weapons WHERE level = ".(int)$row[level]." and damage = 1";
         $result = db_query($sql) or die(db_error(LINK));
          for ($i=0;$i<db_num_rows($result);$i++){
             $row = db_fetch_assoc($result);
         }
         output("`n`nDu musst nun Deine Waffe `8".$session[user][weapon]."`0 ablegen. `n`nDu
         willst gerade beschämt Deinen Weg fortsetzen, als Dir der alte Druide aus
         Mitleid wenigstens die kleine Waffe $row[weaponname] gibt.");
         // changes->db
         $session[user][gold] = $goldnew;
           $session[user][weapon] = $row[weaponname];
           $session[user][attack]-=$session[user][weapondmg];
           $session[user][weapondmg] = $row[damage];
           $session[user][attack]+=$session[user][weapondmg];
           $session[user][weaponvalue] = $row[value];
         $session[user][specialinc]="";
    }
}
else if ($HTTP_GET_VARS[op]=="dontdo"){   // Tauschhandel ablehen
    output("`n`5Du lässt den Druiden im Baum sitzen und setzt lieber Deinen Weg fort.`0
    Irgendwie kam Dir das Angebot eh komisch vor....`0");
    $session[user][specialinc]="";
}
?>


