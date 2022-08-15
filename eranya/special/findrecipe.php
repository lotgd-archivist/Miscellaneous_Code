
<?php
if(isset($_GET['leave']) && strlen($_GET['leave']) > 1) {
    $session['user']['specialinc'] = '';
    if($_GET['leave'] == 'stadt') {
        redirect('village.php');
    } else {
        redirect('forest.php');
    }
} else {
    $session['user']['specialinc'] = 'findrecipe.php';
    output('`¢Auf deinen Streifzügen entdeckst du plötzlich ein Stück beschriebenes Papier. Anscheinend hat es jemand verloren. Du hebst es auf
                           und liest, was da geschrieben steht:`n
                           `n');
    $arr_tmp = user_get_aei('combos');
    $arr_combo_ids = item_get_combolist(0,ITEM_COMBO_ALCHEMY);

    $sql='SELECT combo_id,combo_name,id1,id2,id3,result,type,chance
    FROM items_combos
    WHERE type=2
    AND combo_id NOT IN (0'.addslashes(implode(',',array_keys($arr_combo_ids))).')
    AND no_entry = 0
    ORDER BY rand()
    LIMIT 1';
    $result=db_query($sql);
    if(db_num_rows($result) == 0 || e_rand(1,3) == 2)
    { //schon alle verfügbaren Rezepte bekannt
        output('`b`&"`hVielsaft-Trank`b`n`n`&Man nehme: einige Florfliegen, frische Blutegel, etwas Flussgras, ein Hauch von Knöterich, gemahlenes
                                Horn eines Zweihorns, kleingeschnittene Baumschlangenhaut und ein Stück der Person, in die man sich verwandeln möchte (z.B. Haare)"`n
                                `n
                                `¢..Haare?!? Irks! Du schüttelst dich. Wirklich schauerlich, was sich spielende Kinder manchmal einfallen lassen. Kopfschüttelnd
                                zerknüllst du die Seite und wirfst sie weg, ehe du weiterziehst.');
        redirect('forest.php?leave=1');
        exit;
    }
    $row=db_fetch_assoc($result);
    $int_zutatencount = $row['id3']?3:2;
    for($i=1;$i<=$int_zutatencount;$i++) {
        $row2 = db_fetch_assoc(db_query('SELECT tpl_name FROM items_tpl WHERE tpl_id="'.$row['id'.$i].'"'));
        $arr_zutaten[$i] = $row2['tpl_name'];
    }
    if(strlen($row['result']) > 3) {
        $result = db_fetch_assoc(db_query('SELECT tpl_name FROM items_tpl WHERE tpl_id="'.$row['result'].'"'));
        $tpl_name = $result['tpl_name'];
    } else {
        $tpl_name = $row['combo_name'];
    }
    $quality=array('am besten frisch','kann auch getrocknet sein','nicht zu alt','vorher gut abwaschen','mit ein wenig Feenstaub bestreut','eine Woche in grünem Drachenschnaps eingelegt','zu Pulver zerstoßen');
    output('`b`&"`h'.$tpl_name.'`b`n`n`&Man nehme:`n`n');
    foreach($arr_zutaten AS $k=>$v) {
        $q = e_rand(0,count($quality));
        output('als '.$k.'. Zutat: '.$v.' `&('.$quality[$q].($k == $int_zutatencount ? ')."' : ') - '));
        unset($quality[$q-1]);
    }
    output('`n
            `n
            `¢Interessant, dieses Rezept kanntest du bisher noch gar nicht. Das solltest du unbedingt einmal ausprobieren (auch wenn dir einige
            Angaben etwas merkwürdig vorkommen). Wie war das noch gleich?
            Zum Mischen braucht man einen `@Al`2ch`uem`kis`Ñti`Ýs`ÿc`Ýh`Ñen S`kch`umel`2zti`@gel`¢, nicht?`n');
    addnav('Und nun?');
    addnav('Weiter geht\'s','forest.php?leave=wald');
    addnav('In die Stadt','village.php?leave=stadt');
}
?>

