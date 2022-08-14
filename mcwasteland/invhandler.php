
<?php
// inventory action handler by anpera 2004

require_once "common.php";
if (isset($_GET['id'])){
    $sql="SELECT * FROM items WHERE id='".$_GET['id']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $item = db_fetch_assoc($result);
}else{
    redirect("village.php");
}

page_header("Inventar");
if ($_GET['op']=="fit"){
    output("`QDu tauschst `q`n");
    if ($item['class']=="RÃ¼stung"){
        output($session['user']['armor']."`Q (`q".$session['user']['armordef']."`Q Verteidigung, Wert: `q".$session['user']['armorvalue']."`Q Gold)`n gegen`n`q".$item['name']."`Q (`q".$item['value1']."`Q Verteidigung, Wert: `q".$item['gold']." `QGold) aus.`n Deine Verteidigung Ã¤ndert sich damit von `q".$session['user']['defence']."`Q auf `q");
        $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($session['user']['armor'])."','RÃ¼stung',".$session['user']['acctid'].",".$session['user']['armorvalue'].",".$session['user']['armordef'].",'Gebrauchte RÃ¼stung mit ".$session['user']['armordef']." Verteidigung.')";
        $session['user']['defence']=$session['user']['defence']-$session['user']['armordef']+$item['value1'];
        $session['user']['armordef']=$item['value1'];
        $session['user']['armor']=$item['name'];
        $session['user']['armorvalue']=$item['gold']-1;
        if ($session['user']['armorvalue']<1) $session['user']['armorvalue']=1;
        output($session['user']['defence']."`Q.`n`n");
    }
    if ($item['class']=="Waffe"){
        output($session['user']['weapon']."`Q (`q".$session['user']['weapondmg']."`Q Angriff, Wert: `q".$session['user']['weaponvalue']."`Q Gold)`n gegen`n`q".$item['name']."`Q (`q".$item['value1']."`Q Angriff, Wert: `q".$item['gold']." `QGold) aus.`n Dein Angriffswert Ã¤ndert sich damit von `q".$session['user']['attack']."`Q auf `q");
        $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($session['user']['weapon'])."','Waffe',".$session['user']['acctid'].",".$session['user']['weaponvalue'].",".$session['user']['weapondmg'].",'Gebrauchte Waffe mit ".$session['user']['weapondmg']." Angriffswert.')";
        $session['user']['attack']=$session['user']['attack']-$session['user']['weapondmg']+$item['value1'];
        $session['user']['weapondmg']=$item['value1'];
        $session['user']['weapon']=$item['name'];
        $session['user']['weaponvalue']=$item['gold']-1;
        output($session['user']['attack']."`Q.");
        if ($session['user']['weaponvalue']<1) $session['user']['weaponvalue']=1;
    }
    db_query($sql) or die(sql_error($sql));
    db_query("DELETE FROM items WHERE id='".$_GET['id']."'");
}elseif ($_GET['op']=="dress"){
    if ($item['id']==$session['user']['dress']){
        output("`qDu ziehst `t".$item['name']."`q aus. Damit Ã¤ndert sich deine Verteidigung von `t".$session['user']['defence']."`q auf ");
        $session['user']['dress']=0;
        $session['user']['defence']=$session['user']['defence']-($item['value1']/10);
        if ($session['user']['defence']<1) $session['user']['defence']=1;
        output("`t".$session['user']['defence']."`q.`n`n");
    }elseif ($session['user']['dress']==0){
        if($item['value2']==2){
            output("`qDu legst `t".$item['name']."`q an. Damit Ã¤ndert sich deine Verteidigung von `t".$session['user']['defence']."`q auf ");
            $session['user']['dress']=$item['id'];
            $session['user']['defence']=$session['user']['defence']+($item['value1']/10);
            if ($session['user']['defence']<1) $session['user']['defence']=1;
            output("`t".$session['user']['defence']."`q. Die Kleidung sitzt etwas locker. Was wÃ¼rdest du jetzt fÃ¼r einen Spiegel geben...`n`n");
        }elseif($item['value2']==$session['user']['sex']){
            output("`qDu legst `t".$item['name']."`q an. Damit Ã¤ndert sich deine Verteidigung von `t".$session['user']['defence']."`q auf ");
            $session['user']['dress']=$item['id'];
            $session['user']['defence']=$session['user']['defence']+($item['value1']/10);
            if ($session['user']['defence']<1) $session['user']['defence']=1;
            output("`t".$session['user']['defence']."`q. Was wÃ¼rdest du jetzt fÃ¼r einen Spiegel geben...`n`n");
        }else{
            output("`qDu willst `t".$item['name']."`q gerade anlegen, aber die Kleidung passt dir einfach nicht.`n`n");
        }
    }elseif ($session['user']['dress']!=0 && $item['id']!=$session['user']['dress']){
        if($item['value2']==2){
            $item2 = db_fetch_assoc(db_query("SELECT * FROM items WHERE id='".$session['user']['dress']."'"));
            output("`qDu ziehst `t".$item2['name']."`q aus und `t".$item['name']."`q an. Damit Ã¤ndert sich deine Verteidigung von `t".$session['user']['defence']."`q auf ");
            $session['user']['dress']=$item['id'];
            $session['user']['defence']=$session['user']['defence']-($item2['value1']/10);
            $session['user']['defence']=$session['user']['defence']+($item['value1']/10);
            if ($session['user']['defence']<1) $session['user']['defence']=1;
            output("`t".$session['user']['defence']."`q. Die Kleidung sitzt etwas locker. Was wÃ¼rdest du jetzt fÃ¼r einen Spiegel geben...`n`n");
        }elseif($item['value2']==$session['user']['sex']){
            $item2 = db_fetch_assoc(db_query("SELECT * FROM items WHERE id='".$session['user']['dress']."'"));
            output("`qDu ziehst `t".$item2['name']."`q aus und `t".$item['name']."`q an. Damit Ã¤ndert sich deine Verteidigung von `t".$session['user']['defence']."`q auf ");
            $session['user']['dress']=$item['id'];
            $session['user']['defence']=$session['user']['defence']-($item2['value1']/10);
            $session['user']['defence']=$session['user']['defence']+($item['value1']/10);
            if ($session['user']['defence']<1) $session['user']['defence']=1;
            output("`t".$session['user']['defence']."`q. Was wÃ¼rdest du jetzt fÃ¼r einen Spiegel geben...`n`n");
        }else{
            output("`qDu willst `t".$item['name']."`q gerade anlegen, aber die Kleidung passt dir einfach nicht.`n`n");
        }
    }
}else if ($_GET['op']=="house"){
    if (db_num_rows(db_query("SELECT id FROM items WHERE name='".stripslashes($item['name'])."' AND class='MÃ¶bel' AND owner='".$item[owner]."'"))>0){
        db_query("DELETE FROM items WHERE name='".stripslashes($item['name'])."' AND class='MÃ¶bel' AND owner='".$item[owner]."'");
        output("Du hast `q".$item['name']."`Q schon im Haus. Kurzerhand fliegt `q".$item['name']."`Q raus und wird duch das neuere StÃ¼ck ersetzt.");
    }else{
        output("`QDu suchst fÃ¼r `q".$item['name']."`Q einen Ehrenplatz in deinem Haus, an dem `q".$item['name']."`Q von jetzt an den Staub fangen wird.");
    }
    db_query("UPDATE items SET class='MÃ¶bel',gold=1,gems=0,value1=".$session['user'][house]." WHERE id='".$_GET['id']."'");
}else if ($_GET['op']=="house2") {
    if (db_num_rows(db_query("SELECT id FROM items WHERE name='".stripslashes($item['name'])."' AND class='MÃ¶bel' AND owner=".$item['owner']."'"))>1){
        db_query("DELETE FROM items WHERE name='".stripslashes($item['name'])."' AND class='MÃ¶bel' AND owner='".$item['owner']."'");
        output("Du hast `q".$item['name']."`Q schon im Haus. Kurzerhand fliegt `q".$item['name']."`Q raus und wird duch das neuere StÃ¼ck ersetzt.");
    }else{
        output("`QDu suchst fÃ¼r `q".$item['name']."`Q einen Ehrenplatz in deinem Haus, an dem `q".$item['name']."`Q von jetzt an den Staub fangen wird.");
    }
    db_query("UPDATE items SET class='MÃ¶bel',gold=1,gems=0,value1='".$session['user']['house']."' WHERE id='".$_GET['id']."'");
}else if ($_GET['op']=="throw"){
    output("`QDu wirfst `q".$item['name']."`Q einem hungrigen StrassenkÃ¶ter vor die FÃ¼sse, der `q".$item['name']."`Q sofort in sein Versteck schleppt.`nWas will ein Hund damit anfangen?");
    db_query("DELETE FROM items WHERE id='".$_GET['id']."'");
}else{
    output("`QDu drehst `q".$item['name']." `Qin deiner Hand. Dabei vergisst du, was du eigentlich damit machen wolltest.");
}

addnav("ZurÃ¼ck zum Inventar","prefs.php?op=inventory&back=".$_GET['back']);
if (isset($_GET['back'])) addnav("Fertig",$_GET['back']);
page_footer();

/* Something about available item classes so far:
*
* *Beute
*   - class: Beute
*   - name: item name
*   - description: description
*   - gold and/or gems >0
*   - rest unused (keep 0 or empty)
*   - generated automatically from Beute.Prot
*
* *Beute.Prot
*   - class: Beute.Prot
*   - name: item name
*   - description: description
*   - owner: =0
*   - gold/gems: >0 to enable selling
*   - rest unused (keep 0 or empty)
*   - hidden from players and generated by admin
*
* *Fluch
*   - class: Fluch
*   - name: item name (appears in inventory only)
*   - description: description
*   - owner: >0
*   - hvalue: how many days this curse lasts (0 for unlimited)
*   - gold/gems: >0 (price for cursing and healing)
*   - buff: buff
*   - rest unused (keep 0 or empty)
*   - generated automatically from Fluch.Prot
*
* *Fluch.Prot
*   - class: Fluch.Prot
*   - name: item name
*   - description: description
*   - owner: =0
*   - hvalue: how many days this curse lasts (0 for unlimited)
*   - gold/gems: >0 (price for cursing and healing)
*   - buff: buff
*   - rest unused (keep 0 or empty)
*   - hidden from players and generated by admin
*
* *Geschenk
*   - class: Geschenk
*   - name: item name
*   - description: description
*   - owner: >0
*   - hvalue: how many days this gift lasts (0 for unlimited)
*   - gold/gems: >0 to enable selling
*   - buff: buff
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *MÃ¶bel
*   - class: MÃ¶bel
*   - name: itemname
*   - description: description
*   - owner: >0
*   - gold/gems: >0 to enable selling
*   - rest unused (keep 0 or empty)
*   - generated automatically from MÃ¶bel.Prot
*
* *MÃ¶bel.Prot
*   - class: MÃ¶bel.Prot
*   - name: item name
*   - description: description
*   - owner: =0
*   - gold/gems: >0 to enable selling
*   - rest unused (keep 0 or empty)
*   - hidden from players and generated by admin
*
* *RÃ¼stung
*   - class: RÃ¼stung
*   - name: armor name
*   - description: description
*   - owner: >0
*   - value1: armordefence
*   - gold: >0 (armorvalue)
*   - gems: =0
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *SchlÃ¼ssel
*   - class: SchlÃ¼ssel
*   - name: item name
*   - description: description
*   - owner: >=0
*   - value1: house number (>0)
*   - value2: key number (>0)
*   - hvalue: key used for house (>=0)
*   - gold/gems: =0
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *Schmuck
*   - class: Schmuck
*   - name: item name
*   - description: description
*   - owner: >0
*   - gold/gems: >0
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *Waffe
*   - class: Waffe
*   - name: weapon name
*   - description: description
*   - owner: >0
*   - value1: weapondamage
*   - gold: >0 (weaponvalue)
*   - gems: =0
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *Zaub.Prot
*   - class: Zaub.Prot
*   - name: item name
*   - description: description
*   - owner: =0
*   - value1: how often it can be used (>0)
*   - value2: how often it can be used on each day (must be =value1 by default)
*   - hvalue: how many days this spell lasts (0 for unlimited)
*   - gold/gems: >0 to enable selling
*   - buff: buff
*   - rest unused (keep 0 or empty)
*   - generated by Admin
*
* *Zauber
*   - class: Zauber
*   - name: itemname (appears in inventory only)
*   - description: description
*   - owner: >0
*   - value1: how often it can be used (>0)
*   - value2: how often it can be used on each day (must be =value1 by default)
*   - hvalue: how many days this spell lasts (0 for unlimited)
*   - gold/gems: >0 to enable selling
*   - buff: buff
*   - rest unused (keep 0 or empty)
*   - generated automatically from Zaub.Prot
*
* *Haust.Prot
*   - class: Haust.Prot
*   - name: pet name
*   - description: description
*   - owner: 0
*   - value1: gold price to feed pet (>= 0)
*   - value2: gem price to feed pet (>= 0)
*   - hvalue: 0
*   - gold: gold price to buy pet (>= 0)
*   - gems: gem price to buy pet (>= 0)
*   - buff:
*      - name: name of pet's weapon
*      - atkmod: pet's att. value
*      - defmod: pet's def. value
*      - regen: pet's health points
*   - rest unused (keep 0 or empty)
*   - generated by Admin
*
* *Haustiere
*   - class: Haustiere
*   - name: pet name
*   - description: description
*   - owner: >0
*   - value1: gold price to feed pet (>= 0)
*   - value2: gem price to feed pet (>= 0)
*   - hvalue: >0 (house id, not used yet)
*   - gold: gold price to buy pet (>= 0)
*   - gems: gem price to buy pet (>= 0)
*   - buff:
*      - name: name of pet's weapon
*      - atkmod: pet's att. value
*      - defmod: pet's def. value
*      - regen: pet's health points
*   - rest unused (keep 0 or empty)
*   - generated automatically from Haust.Prot
*
*/

?>

