
<?php

// 15082004

// inventory action handler by anpera 2004

require_once "common.php";
if (isset($_GET['id'])){
    $sql="SELECT * FROM items WHERE id=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $item = db_fetch_assoc($result);
}else{
    redirect("village.php");
}

page_header("Inventar");
if ($_GET['op']=="fit"){
    output("`QDu tauschst `q`n");
    if ($item['class']=="Rüstung"){
        output($session[user][armor]."`Q (`q".$session[user][armordef]."`Q Verteidigung, Wert: `q".$session[user][armorvalue]."`Q Gold)`n gegen`n`q$item[name]`Q (`q$item[value1]`Q Verteidigung, Wert: `q$item[gold] `QGold) aus.`n Deine Verteidigung ändert sich damit von `q".$session[user][defence]."`Q auf `q");
        $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($session[user][armor])."','Rüstung',".$session[user][acctid].",".$session[user][armorvalue].",".$session[user][armordef].",'Gebrauchte Rüstung mit ".$session[user][armordef]." Verteidigung.')";
        $session['user']['defence']=$session['user']['defence']-$session['user']['armordef']+$item['value1'];
        $session['user']['armordef']=$item['value1'];
        $session['user']['armor']=$item['name'];
        $session['user']['armorvalue']=$item['gold']-1;
        if ($session['user']['armorvalue']<1) $session['user']['armorvalue']=1;
        output($session[user][defence]."`Q.`n`n");
    }
    if ($item['class']=="Waffe"){
        output($session[user][weapon]."`Q (`q".$session[user][weapondmg]."`Q Angriff, Wert: `q".$session[user][weaponvalue]."`Q Gold)`n gegen`n`q$item[name]`Q (`q$item[value1]`Q Angriff, Wert: `q$item[gold] `QGold) aus.`n Dein Angriffswert ändert sich damit von `q".$session[user][attack]."`Q auf `q");
        $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($session[user][weapon])."','Waffe',".$session[user][acctid].",".$session[user][weaponvalue].",".$session[user][weapondmg].",'Gebrauchte Waffe mit ".$session[user][weapondmg]." Angriffswert.')";
        $session['user']['attack']=$session['user']['attack']-$session['user']['weapondmg']+$item['value1'];
        $session['user']['weapondmg']=$item['value1'];
        $session['user']['weapon']=$item['name'];
        $session['user']['weaponvalue']=$item['gold']-1;
        output($session[user][attack]."`Q.");
        if ($session['user']['weaponvalue']<1) $session['user']['weaponvalue']=1;
    }
    db_query($sql) or die(sql_error($sql));
    db_query("DELETE FROM items WHERE id=$_GET[id]");
}else if ($_GET['op']=="house"){
    if (db_num_rows(db_query("SELECT id FROM items WHERE name='".stripslashes($item[name])."' AND class='Möbel' AND owner=$item[owner]"))>0){
        db_query("DELETE FROM items WHERE name='".stripslashes($item[name])."' AND class='Möbel' AND owner=$item[owner]");
        output("Du hast `q$item[name]`Q schon im Haus. Kurzerhand fliegt `q$item[name]`Q raus und wird duch das neuere Stück ersetzt.");
    }else{
        output("`QDu suchst für `q$item[name]`Q einen Ehrenplatz in deinem Haus, an dem `q$item[name]`Q von jetzt an den Staub fangen wird.");
    }
    db_query("UPDATE items SET class='Möbel',gold=1,gems=0,value1=".$session[user][house]." WHERE id=$_GET[id]");
}else if ($_GET['op']=="throw"){
    output("`QDu wirfst `q$item[name]`Q einem hungrigen Strassenköter vor die Füsse, der `q$item[name]`Q sofort in sein Versteck schleppt.`nWas will ein Hund damit anfangen?");
    db_query("DELETE FROM items WHERE id=$_GET[id]");
}else{
    output("`QDu drehst `q$item[name] `Qin deiner Hand. Dabei vergisst du, was du eigentlich damit machen wolltest.");
}

addnav("Zurück zum Inventar","prefs.php?op=inventory&back=$_GET[back]");
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
* *Möbel
*   - class: Möbel
*   - name: itemname
*   - description: description
*   - owner: >0
*   - gold/gems: >0 to enable selling
*   - rest unused (keep 0 or empty)
*   - generated automatically from Möbel.Prot
*
* *Möbel.Prot
*   - class: Möbel.Prot
*   - name: item name
*   - description: description
*   - owner: =0
*   - gold/gems: >0 to enable selling
*   - rest unused (keep 0 or empty)
*   - hidden from players and generated by admin
*
* *Rüstung
*   - class: Rüstung
*   - name: armor name
*   - description: description
*   - owner: >0
*   - value1: armordefence
*   - gold: >0 (armorvalue)
*   - gems: =0
*   - rest unused (keep 0 or empty)
*   - generated automatically
*
* *Schlüssel
*   - class: Schlüssel
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
*/

?>

