<?php
/* class ausbaustufe {
    var $level, $name, $gold, $gems, $keys, $zimmer, $goldchest, $gemschest;

    // Konstrucktor
    function ausbaustufe($level=1) {
            // Sicherung:
        if($level <= $this->hoechste()) {
                // Abfrage:
            $sql="SELECT * FROM hauslevels WHERE level = ".$level;
            $result=db_query($sql) or die(db_error(LINK));
            $row=db_fetch_assoc($result);

                // Deklarationen:
            foreach($row as $key=>$val) {
                $this->{$key} = $val;
            }
        } else {
            die("Fehler - Zugrif auf einen nicht eingetragenen Level nicht möglich. Bitte Admin kontaktieren!<br>\n"
                    ."Folgende Werte wurden übergeben:<br> Eingetragene Level: ".$this->levels().",<br> Angeforterter Level: ".$level);
        }
    }    // Ende Funktion

    function levels() {
        $sql="SELECT COUNT(level) AS anzahl FROM hauslevels";
        $result=db_query($sql) or die(db_error(LINK));
        $row=db_fetch_assoc($result);
        return (int) $row['anzahl'];
    }    // Ende Funktion

    function hoechste() {
        $sql="SELECT MAX(level) AS maximum FROM hauslevels";
        $result=db_query($sql) or die(db_error(LINK));
        $row=db_fetch_assoc($result);
        return (int) $row['maximum'];
    }    // Ende Funktion

    function speichern($aufid=false) {
        if($aufid == false) $aufid = $this->level;
        if($aufid <= $this->levels()) {
            $sql = "UPDATE `houselevels` SET level=".$aufid.", name=".$this->name.", gold=".$this->gold.", gems=".$this->gems.", keys=".$this->keys.", goldchest=".$this->goldchest.", gemschest=".$this->gemschest." WHERE level=".$this->level;
        } else {
            $sql = "INSERT INTO houselevels SET level=".$aufid.", name=".$this->name.", gold=".$this->gold.", gems=".$this->gems.", keys=".$this->keys.", goldchest=".$this->goldchest.", gemschest=".$this->gemschest;
        }
        db_query($sql) or dir(db_error(LINK));
    }    // Ende Funktion
}    // Ende Klasse

class haus {
        // Allgemeines:
    var $id, $name, $besitzer, $besitzerid, $gold, $gems, $status, $text, $level, $ausbauten, $baulevel, $goldkosten, $gemskosten, $kosten;

        // Konstruktor:
    function haus($id,$owner=false) {
            // Abfrage:
        if($owner == false) {
            $sql="SELECT * FROM `houses` WHERE houseid=".$id;
        } else {
            $sql="SELECT * FROM `houses` WHERE owner=".$id;
        }
        $result=db_query($sql) or die(db_error(LINK));
        $row=db_fetch_assoc($result);

            // Deklarationen:
        $this->id = $row['houseid'];
        $this->name = $row['housename'];
        $this->status = $row['status'];
        $this->gold = $row['gold'];
        $this->gems = $row['gems'];
        $this->text = $row['description'];
        $this->besitzerid = $row['owner'];
            // Levelbrechnungen:
        $this->level = new ausbaustufe($row['level']+1);
            // Ausbautenermittlung:
        $this->ausbauten = explode(',',$row['ausbauten']);
        if(empty($this->ausbauten[0])) $this->ausbauten=array();

        if($this->besitzerid > 0) {
            $row=db_fetch_assoc(db_query("SELECT name AS besitzer FROM accounts WHERE acctid=".$row['owner']));
            $this->besitzer = $row['besitzer'];
        } else  {
            $this->besitzer = "";
        }
            // Fixe Werte:
        $this->goldkosten = getsetting("baukostengold",50000);
        $this->gemskosten = getsetting("baukostengems",30);
            // Sonstiges
        $baulevel = $this->blevel();
        $this->baustatus = $baulevel[$this->status];
        $this->kosten = $this->preise(false);
    }    // Ende Funktion

        //#####################################################################################//
        //################################ Allgemeine METHODEN ################################//
        //#####################################################################################//
    function zimmer_laden($zimmer) {
            // Funktion für die Implementierung eines Zimmer(-Moduls)
        include_once("zimmer.php");
        if(!class_exists($zimmer)) {
                // Prüfen, ob das Zimmer existiert. Wenn nicht auf Flur umleiten.
            $this->flur("`b`4Upps, den Raum gibt es wohl noch nicht....`b`0");
        } else {
            $this->zimmer = new $zimmer();
        }
    }    // Ende Funktion
    function levelup() {
            // Prozedur zum Steigern des Hauslevels
        $nextlevel = $this->level->level;
        $sql="UPDATE houses SET level = ".$nextlevel." WHERE houseid=".$this->id;
        db_query($sql) or die(db_error(LINK));
        $this->level = new ausbaustufe($nextlevel);
    }    // Ende Funktion

    function wert($level=false) {
            // Funktion zur Ermittlung des Hauswertes in Gold und Gems
        $kosten=array('levelgold'=>0,'levelgems'=>0);
        if($level==false) $level = $this->level->level;
        $sql = "SELECT level, gold, gems FROM hauslevels WHERE level <= $level";
        $result = db_query($sql) or die(db_error(LINK));
        for($i=0;$i < db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $kosten['levelgold'] += $row['gold'];
            $kosten['levelgems'] += $row['gems'];
        }    // Ende For
        $kosten['basisgold'] = getsetting("baukostengold",50000);
        $kosten['basisgems'] = getsetting("baukostengems",50000);
        $kosten['gold'] = $kosten['levelgold'] + $kosten['basisgold'];
        $kosten['gems'] = $kosten['levelgems'] + $kosten['basisgems'];
        return $kosten;
    }    // Ende Funktion

    function preise($level=false) {
            // Funktion Zur berchnung der Maximal-, Minimal- und Maklerpreise
        if(!$level) $level = $this->level->level;
        $standard = $this->wert(false);
        $kosten = $standard;
        if($this->status == 2 && $this->besitzerid == 0) {
            // Maklerverkauf - standardwerte behalten
            /*
            $kosten['gold'] = $standard['gold'];
            $kosten['gems'] = $standard['gems'];
            */
        } elseif($this->status == 2 && $this->besitzerid > 0) {
            // Privatverkauf
            $kosten['gold'] = $this->gold;
            $kosten['gems'] = $this->gems;
        } elseif($this->status == 3) {
            // Verlassen (noch gold enthalten)
            $kosten['gold'] = $standard['gold'] + $this->gold;
            $kosten['gems'] = $standard['gems'] + $this->gems;
        } else {
            // Alles andere->Bauruine
            $kosten['gold'] = $this->gold;
            $kosten['gems'] = $this->gems;
        }    // Ende (ELSE-)IF
        return $kosten;
    }    // Ende Funktion

    function blevel() {
            // Ausgabe des Baulevels - auch für externe Abfragen geeignet
        return array("`6im Bau`0","`!bewohnt`0","`^zum Verkauf`0","`4Verlassen`0","`\$Bauruine`0");
    }    // Ende Funktion

    function eintragen() {
            // Prozedur zum speichern der aktualisierten Seiten
        $sql="UPDATE `houses` SET owner = ".$this->besitzerid.",status = ".$this->status.", gold = ".$this->gold.", gems = ".$this->gems.", housename = '".$this->name."', description = '".addslashes($this->text)."', ausbauten = '".implode(',',$this->ausbauten)."' WHERE houseid = ".$this->id;
        db_query($sql) or die(db_error(LINK));
    }    // Ende Funktion

    function strongest() {
            // Funktion zur Ermittlung des stärksten Users im Haus
        global $session;
        $pvptime = getsetting("pvptimeout",600);
        $pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
        $days = getsetting("pvpimmunity", 5);
        $exp = getsetting("pvpminexp", 1500);
        $sql = "SELECT acctid,name,maxhitpoints,defence,attack,level,laston,loggedin,login,housekey FROM accounts WHERE
        (locked=0) AND
        (alive=1 AND location=2) AND
        (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",4900)." sec"))."' OR loggedin=0) AND
        (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
        (acctid <> ".$session['user']['acctid'].") AND
        (pvpflag <> '5013-10-06 00:42:00') AND
        (pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $athome=0;
        $name="";
        $hp=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $sql = "SELECT value1 FROM items WHERE value1=".(int)$session['housekey']." AND class='Schlüssel' AND owner=$row[acctid] AND hvalue=".(int)$session['housekey']." ORDER BY id";
            $result2 = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result2)>0 || ((int)$row[housekey]==(int)$session['housekey'] && 0==db_num_rows(db_query("SELECT hvalue FROM items WHERE hvalue<>0 AND class='Schlüssel' AND value1<>$session[housekey] AND owner=$row[acctid]")))){
                $athome++;
                if ($row['maxhitpoints']>$hp){
                    $hp=$row['maxhitpoints'];
                    $name=$row['login'];
                }
            }
            db_free_result($result2);
        }
        $data=array("athome"=>$athome,"name"=>$name,"hp"=>$hp);
        return $data;
    }    // Ende Funktion

    function flur($text="") {
            // Ausgangsbereich des Hauses
        output("`2`b`c".$this->name."`b, `&ein ".$this->level->name."`&`n `bDer Flur`b `c`");
        if ($this->text) output("`0`c".$this->text."`c`n");
        output($text."`n");
        output("`2Du und deine Mitbewohner haben `^".$this->gold."`2 Gold und `#".$this->gems."`2 Edelsteine im Haus gelagert.`n");
        if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`2.`n");
        output ("Es ist jetzt `^".getgametime()."`2 Uhr.`n`n");
        viewcommentary("haus-".$this->id,"Mit Mitbewohnern reden:",30,"sagt");
        output("`n`n`n`n`%`GOOC-Bereich:`n");
        viewcommentary("ooc");
        addnav("Im Haus");
            addnav("Schatzkammer","nhouses.php?op=drin&go=schatz");
            addnav("Schlafzimmer","nhouses.php?op=drin&go=schlafzimmer");
            addnav("Arbeitszimmer","nhouses.php?op=drin&go=office");
        // Ermittlung der Installierten und Aktiven Hauserweiterungen START
        $sql = "SELECT * FROM `zimmer` WHERE aktiv=1 AND level < ".$this->level->level." ORDER BY `zimmerid` ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $schalter = false;
        for($i=0;$i<db_num_rows($result);$i++) {
                $row = db_fetch_assoc($result);
            if(in_array($row['zimmerid'],$this->ausbauten)) {
                if($schalter == false) {
                    addnav("Weitere Zimmer");
                    $schalter = true;
                }
                addnav($row['label'],$row['link']);
            }
        }
        // Ermittlung der Installierten und Aktiven Hauserweiterungen ENDE

        addnav("Ausgang");
            addnav("W?Zurück zum Wohnviertel","nhouses.php");
            addnav("Zurück zum Dorf","village.php");
    }    // Ende Funktion

        //################################################################################//
        //################################ EXTERNE MODULE ################################//
        //################################################################################//

    function bio() {
        global $session;
            // Informationsausgabe über das Haus:
        output("`c`b`@Infos über Haus Nummer ".$this->id."`b`c`n`n`2Du näherst dich Haus Nummer ".$this->id.", um es aus der Nähe zu betrachten. ");
        if($this->text > 0) output("Über dem Eingang von ".$this->name."`2 steht geschrieben:`n`& ".$this->text."`n`n");
        else output("Das Haus trägt den Namen \"`&".$this->name."`2\".`n");

        output("`2Dieses Haus gehört ".(($this->besitzer=="")?"niemandenm":$this->besitzer)."`2 und ist ein");
        output($this->level->name."`2Du riskierst einen Blick durch eines der Fenster");

                /* Item-Anzeige START */
        $sql="SELECT * FROM items WHERE class='Möbel' AND value1=".$this->id." ORDER BY id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) > 0) {
            output(" und erkennst ");
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("`@".$row['name']);
                if($i+1<db_num_rows($result)) output(", ");
            }
            output(".`n");
        }else{
            output(", aber das Haus hat sonst nichts weiter zu bieten.");
        }        /* Item-Anzeige ENDE */

        $sql="SELECT value1 FROM items WHERE class = 'Schlüssel' AND owner = ".$session['user']['acctid']." AND value1 = '".$this->id."' LIMIT 1";
        if(db_num_rows(db_query($sql)) == 1) addnav("Haus betreten","nhouses.php?op=drin&id=".$this->id);
        if($session['user']['housekey'] == 0 && $haus->status >= 2 && $haus->status <= 4) addnav("Haus Kaufen","nhouses.php?op=kaufen&id=".$haus->id);
        addnav("Zurück","nhouses.php");
    }    // Ende Funktion
    function hausbauen() {
            // Prozedur zum Verarbeiten der Vertigstellung eines Hauses
        global $session;
        $basis = new ausbaustufe(1);
        output("`n`n`bGlückwunsch!`b Dein Haus ist fertig. Du bekommst`b ".$basis->keys." `bSchlüssel überreicht, von denen du ".($basis->keys - 1)." an andere weitergeben kannst, und besitzt nun deine eigene kleine Burg.");
        $this->gold = 0;
        $this->gems = 0;
        $session['user']['housekey'] = $this->id;
        $this->status = 1;
        addnews("`2".$session['user']['name']."`3 hat das Haus `2".$this->name."`3 fertiggestellt.");
        for ($i=1;$i < $basis->keys;$i++){
            $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',".$session['user']['acctid'].",'Schlüssel',".$this->id.",$i,0,0,'Schlüssel für Haus Nummer ".$this->id."')";
            db_query($sql);
            if (db_affected_rows(LINK)<=0) output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin. ");
        }
        $this->eintragen();
    }    // Ende Funktion

    function klauen() {
            // Prozedur zur Ermittlung der Beute beim Einbruch
        global $session;
        addnav("Zurück zum Dorf","village.php");
        switch(e_rand(1,3)) {
            case 1:
            $getgems=0;
            $getgold=e_rand(0,round($this->gold/10));
            break;
            case 2:
            $getgems=e_rand(0,round($this->gems/10));
            $getgold=e_rand(0,round($this->gold/10));
            break;
            case 3:
            $getgems=e_rand(0,round($this->gems/10));
            $getgold=0;
            break;
        }
        $session['user']['gold'] +=$getgold;
        $session['user']['gems'] +=$getgems;
        $this->gold -= $getgold;
        $this->gems -= $getgems;
        output("`@Es gelingt dir, `^".$getgold." `@Gold und  `#".$getgems." `@Edelsteine aus dem Schatz zu klauen!");
        addnews("`6".$session['user']['name']."`6 erbeutet `#".$getgems."`6 Edelsteine und `^".$getgold."`6 Gold bei einem Einbruch!");
        systemmail($this->besitzerid,"`\$Einbruch!`0","`\$".$session['user']['name']."`\$ ist in dein Haus eingebrochen und hat `^".$getgold."`\$ Gold und `#".$getgems."`\$ Edelsteine erbeutet!");
        db_query("INSERT INTO `commentary`(section,author,comment,postdate) VALUES('schatz-".$this->id."',".$session['user']['acctid'].",'/me `4stiehlt `^".$getgold." Goldstücke `4und `#".$getgems." Edelsteine`4 aus dem Hausschatz");
        $this->eintragen();
    }    // Ende Funktion

    function kaufen() {
            // Prozedur zur Verarbeitung eines Hauskaufes
        global $session;
        if ($session['user']['acctid']==$this->besitzerid) {
                // Eigenes Haus wiederkaufeb
            output("`@du hängst doch zu sehr an deinem Haus und beschließt, es noch nicht zu verkaufen.");
            $session['user']['housekey']=$this->id;
            $this->gold = 0;
            $this->gems = 0;
        }elseif ($session['user']['gold']< $this->kosten['gold'] || $session['user']['gems'] < $this->kosten['gems']){
                // zu Teuer
            output("`@Dieses edle Haus übersteigt wohl deine finanziellen Mittel.");
        }else {
                // Erfolgreicher Kauf
            output("`@Glückwunsch zu deinem neuen Haus!`n`n");
            $session['user']['gold'] -= $this->kosten['gold'];
            $session['user']['gems'] -= $this->kosten['gems'];
            $session['user']['house'] = $this->id;
            output("Du übergibst `^".$this->kosten['gold']."`@ Gold und `#".$this->kosten['gems']."`@ Edelsteine an den Verkäufer, und dieser händigt dir dafür einen Satz Schlüssel für Haus `b".$this->id."`b aus.");
            if ($this->besitzerid > 0){
                $sql = "UPDATE accounts SET goldinbank=goldinbank+".$this->kosten['gold'].",gems=gems+".$this->kosten['gems'].",house=0,housekey=0 WHERE acctid=".$this->besitzerid;
                db_query($sql);
                systemmail($this->besitzerid,"`@Haus verkauft!`0","`&".$session['user']['name']."`2 hat dein Haus gekauft. Du bekommst `^".$this->kosten['gold']."`2 Gold auf die Bank und `#".$this->kosten['gems']."`2!");
                $session['user']['housekey']=$this->id;
            }
            if ($this->status == 3){
                    // Verlassen
                $this->status = 1;
                $this->besitzerid = $session['user']['acctid'];
                $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE owner=0 and class='Schlüssel' AND value1=".$this->id;
                output(" Bitte bedenke, dass du ein verlassenes Haus gekauft hast, zu dem vielleicht noch andere einen Schlüssel haben!");
                $session['user']['housekey']=$this->id;
            }else if ($this->status == 4){
                    // Bauruine
                $this->status = 0;
                $this->besitzerid = $session['user']['acctid'];
                output(" Bitte bedenke, dass du eine Bauruine gekauft hast, die du erst fertigbauen musst!");
            }else{
                    // Anderer Status(2)
                $this->gold = 0;
                $this->gems = 0;
                $this->status = 1;
                $this->besitzerid = $session['user']['acctid'];
                $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE class='Schlüssel' AND value1=".$this->id;
                $session['user']['housekey']=$this->id;
            }    // Ende ELSE-IF innen
            db_query($sql);
        }    // Ende ELSE-IF aussen
        addnav("Zurück zum Dorf","village.php");
        $this->eintragen();
    }    // Ende Funktion

    function verkaufsabwicklung() {
            // Allgemeine Abwicklung eines Hauskaufs
        global $session;
        // Gold und Edelsteine an Bewohner verteilen und Schlüssel einziehen
        $sql = "SELECT owner FROM items WHERE value1=".$this->id." AND class='Schlüssel' AND owner<>".$session['user']['acctid']." ORDER BY id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $amt=db_num_rows($result);
        $goldgive=round($this->gold/($amt+1));
        $gemsgive=round($this->gems/($amt+1));
        $session['user']['gold']+=$goldgive;
        $session['user']['gems']+=$gemsgive;
        for ($i=0;$i<db_num_rows($result);$i++) {
            $item = db_fetch_assoc($result);
            $sql = "UPDATE accounts SET goldinbank=goldinbank+".$goldgive.",gems=gems+".$gemsgive." WHERE acctid=".$item['owner'];
            db_query($sql);
            systemmail($item['owner'],"`@Rauswurf!`0","`&".$session['user']['name']."`2 hat das Haus `b".$this->name."`b`2 verkauft, in dem du als Untermieter gewohnt hast. Du bekommst `^".$goldgive."`2 Gold auf die Bank und `#".$gemsgive."`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");
        }
        $sql = "UPDATE items SET owner=".$this->besitzerid." WHERE class='Schlüssel' AND value1=".$this->id;
        db_query($sql);
        $this->gold = $_POST['gold'];
        $this->gems = $_POST['gems'];
        $this->status = 2;
        $session['user']['housekey'] = 0;
    }    // Ende Funktion

    function verkauf() {
            // Übersicht zum Verkauf eines Hauses und Privatverkauf
        global $session;
        if (!$_POST['gold'] || !$_POST['gems']){
        $this->kosten = $this->wert(false);
        output("`@Gib einen Preis für dein Haus ein, oder lass einen Makler den Verkauf übernehmen.`n");
        output("`3Der schmierige Makler würde dir sofort `^".round($this->kosten['basisgold']/3)."`3 Gold und `#".round($this->kosten['basisgems']/3)."`3 Edelsteine ");
        output("plus `^".round($this->kosten['levelgold']/3)." `3Gold und `#".round($this->kosten['levelgems']/3)." `3Edelsteine für deine Hausausbauten, ");
        output("`nalso insgesammt: `^".round($this->kosten['gold']/3)."`3 Gold und `#".round($this->kosten['gems']/3)."`3 Edelsteine geben.`n");
        output("`@Wenn du selbst verkaufst, kannst du vielleicht einen höheren Preis erzielen, musst aber auf dein Geld warten, bis jemand kauft.`nAlles, was sich noch im Haus befindet, wird ");
        output("gleichmässig unter allen Bewohnern aufgeteilt.`n`n");
        output("`0<form action='nhouses.php?op=verkaufen&act=verkauf' method='POST'>",true);
        output("`nWieviel Gold willst du verlangen? <input type='gold' name='gold'>`n",true);
        output("`nWieviele Edelsteine soll das Haus kosten? <input type='gems' name='gems'>`n",true);
        output("<input type='submit' class='button' value='Anbieten'>",true);
        addnav("","nhouses.php?op=verkaufen&act=verkauf");
        addnav("An den Makler","nhouses.php?op=verkaufen&act=makler");
        addnav("W?Zurück zum Wohnviertel","nhouses.php");
        addnav("Zurück zum Dorf","village.php");
        } else {
            $standard = $this->wert(false);
            if($_POST['gold'] <= ($standard['gold']/2) || $_POST['gems'] <= ($standard['gems']/2)) {// $_POST['gold'] > ($standard['gems']*2) || $_POST['gems'] > ($standard['gems']*2)) {
                output("`4`b Mit diesen Preisen wirst du viel zu wahnsinnige Verluste machen. Überdenke deine Angaben noch einmal - dein Haus ist mindestens die Hälfte des Baupreises Wert!`b`n`n");
                unset($_POST['gold']);
                unset($_POST['gems']);
                $this->verkauf();
            } elseif($_POST['gold'] > ($standard['gems']*2) || $_POST['gems'] > ($standard['gems']*2)) {
                output("`4`b Mit diesen Preisen wird niemand dein Haus kaufen. Überdenke deine Angaben noch einmal - dein Haus ist höchstens die Hälfte des Baupreises Wert!`b`n`n");
                unset($_POST['gold']);
                unset($_POST['gems']);
                $this->verkauf();
            } else {
                output("`@Dein Haus steht ab sofort für `^".$_POST['gold']."`@ Gold und `#".$_POST['gems']."`@ Edelsteine zum Verkauf. Du und alle Mitbewohner habt den Schatz des Hauses gleichmäßig ");
                output(" unter euch aufgeteilt und deine Untermieter haben ihre Schlüssel abgegeben.");
                $this->verkaufsabwicklung(true);
                $this->eintragen();
                addnav("W?Zurück zum Wohnviertel","nhouses.php");
                addnav("Zurück zum Dorf","village.php");
            }    // Ende innere IF
        }    // Ende äußere IF
    }    // Ende Funktion

    function maklerverkauf() {
            // Haus an den Makler verkaufen
        global $session;
        $kosten = $this->wert(false);
        $halfgold=round($kosten['gold']/3);
        $halfgems=round($kosten['gems']/3);
        if($_GET['valid']==true) {
            output("`@Dem Makler entfährt ungewollt ein freudiges Glucksen, als er dir`^ ".$halfgold."`@ Gold und die `#".$halfgems."`@ Edelsteine vorzählt.`n`n");
            output("Ab sofort steht dein Haus zum Verkauf und du kannst ein neues bauen, woanders mit einziehen, oder ein anderes Haus kaufen.");
            $session['user']['gold'] += $halfgold;
            $session['user']['gems'] += $halfgems;
            $session['user']['house'] = 0;
            $session['user']['donation'] += 1;
            $_POST['gold'] = $halfgold;
            $_POST['gems'] = $halfgems;
            $this->verkaufsabwicklung();
            $this->besitzerid = 0;
            $sql = "UPDATE items SET owner=0 WHERE class='Schlüssel' AND value1=".$this->id;
            db_query($sql) or die(db_error(LINK));
            $this->eintragen();
        } else {
            output("`@Möchstest du dein Haus wirklich an den schmierigen Makler verkaufen?`n du bekämest`^ $halfgold`@ Gold und `#$halfgems`@ Edelsteine.");
            addnav("Ja, Verkaufen!","nhouses.php?op=verkaufen&act=makler&valid=true");
        }
        addnav("W?Zurück zum Wohnviertel","nhouses.php");
        addnav("Zurück zum Dorf","village.php");
    } // Ende Funktion
}    // Ende Klasse

class collector {
    var $datei, $datum, $letztesdatum, $dstring, $letztesdstring, $ausgabe, $ausnahmen;

        // Konstruktor
    function collector($nichtausgeben=false) {
        $this->datei = "zimmer.php";
        $eingelesen = explode(' ',getsetting("zimmerausnahmen",""));
        $this->ausnahmen = (empty($eingelesen[0])?array():$eingelesen);
        //Initialisierung und Finden des letzten Zugriffstadtums:
        $this->datum = filemtime($this->datei);
        $this->letztesdatum = getsetting("letzterzimmerzugriff",0000000000);
            // Daten "lesbar machen":
        $this->dstring = date("Y:m:d-H:i:s",$this->datum);
        $this->letztesdstring = date("Y:m:d-H:i:s",$this->letztesdatum);
            // Ausgabe:
        if(!$nichstausgeben) $this->ausgabe = $this->ausgeben();
    }    // Ende Funktion

    function ausgeben() {
        $ausgabe = "`c`b`^Übersicht - Letzte Aktualisierungen der Datei `#".$this->datei."`^,`b`c`n\n";
        $ausgabe .="<table align='center' cellspacing=5 valign='top'><tr><td>\n";
            $ausgabe .="<table cellpadding=2 cellspacing=1 border=1 align='left' valign='top'>\n";
            $ausgabe .="<tr class='trhead'><td>`bLetzte Änderung`b</td><td>`bLetzte Überprüfung`b</td></tr>\n";
            $ausgabe .="<tr class='trdark'><td colspan='2' align='center'>`9Daten als Unix-Timestamp</td></tr>\n";
            $ausgabe .="<tr class='trlight'><td>`^".$this->datum."`0</td><td>`^".$this->letztesdatum."`0</td></tr>\n";
            $ausgabe .="<tr class='trdark'><td colspan='2' align='center'>`9Daten in Lesbarer Form (JJJJ:MM:TT-HH:MM:SS)</td></tr>\n";
            $ausgabe .="<tr class='trlight'><td>`^".$this->dstring."`0</td><td>`^".$this->letztesdstring."`0</td></tr>\n";
            if($this->datum > $this->letztesdatum) $ausgabe .="<tr class='trhead'><td colspan='2' align='center'>`bEine erneute Prüfung ist zu Empfehlen`b</td></tr>\n";
            else $ausgabe .="<tr class='trhead'><td colspan='2' align='center'>`bEine erneute Prüfung nicht nötig.`b</td></tr>\n";
            $ausgabe .="</table>";
        $ausgabe .="</td><td>\n";
            $ausgabe .="<table cellpadding=4 cellspacing=4 align='right' valign='top'>\n";
            $ausgabe .="<tr class='trhead'><td>ID</td><td>Name</td><td>Link</td><td>Label</td><td>Level</td><td>aktiv</td><td>Gold</td><td>Gems</td><td>ops</td></tr>\n";
            $sql = "SELECT * FROM `zimmer` ORDER BY `zimmerid`";
            $result = db_query($sql) or die(db_error(LINK));
            if(db_num_rows($result) < 1) {
                $ausgabe .= "<tr class='trdark'><td colspan='5'>`iKEINE ZIMMERKLASSEN GEFUNDEN`i</td></tr>\n";
            } else {
                include_once("zimmer.php");
                for($i=0;$i< db_num_rows($result);$i++) {
                    $bgcolor=($i%2==1?"trlight":"trdark");
                    $row = db_fetch_assoc($result);
                    $zimmer = new zimmer;
                    $zimmer->einlesen($row);
                    $ausgabe .= "<tr class='".$bgcolor."'><td>".$zimmer->id."</td><td>".$zimmer->name."</td><td>".str_replace('nhouses.php?op=drin','',$zimmer->linker)."</td><td>"
                    .$zimmer->label."</td><td>".$zimmer->level."</td><td>".$zimmer->aktiv."</td><td>"
                    .$zimmer->gold."</td><td>".$zimmer->gems."</td><td><a href='nhmaster.php?op=zimmeredit&zimmer=".$zimmer->id."'>edit</a></td></tr>\n";
                    addnav("","nhmaster.php?op=zimmeredit&zimmer=".$zimmer->id);
                    unset($zimmer);
                }    // Ende FOR
            }    // Ende (ELSE-)IF
            $ausgabe .="</table>\n";
        $ausgabe .="</td></tr></table>\n";
        return $ausgabe;
    }    // Ende Funktion

    function pruefung() {
        savesetting("letzterzimmerzugriff",mktime());
        output("Die Prüfung auf neue Daten wird gestartet...");
        // Alte Daten:
        $sql = "SELECT * FROM `zimmer` ORDER BY `zimmerid`";
        $result = db_query($sql) or die(db_error(LINK));
        $alte = array();
        for($i=0;$i< db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            $alte[] = $row;
            // Ausnahmen eintragen:
            $this->ausnahmen[] = $row['name'];
        }
            // Neue Daten:
        $input = $this->run();
            // Verarbeitung
        output("Folgende neue Zimmerklassen wurden gefunden:`n`n");
        addnav("","nhmaster.php?op=weiter");
        $match = false; $ausgabe = "";
        if($input!==false) {
            foreach($input as $val) {
                $ausgabe .= "<tr><td>".$val."</td><td><input type='checkbox' name='".$val."' value='aktiv' checked></td></tr>";
                $match = true;
            }
        } else {
            $match = false;
        }
        if($match) {
            output("<form action='nhmaster.php?op=weiter' method='POST'>\n<table><tr><td>Neue Zimmerklasse</td><td>Aktivieren</td></tr>",true);
            output($ausgabe,true);
            output("</table><input type='submit' value='Absenden'></form>",true);
        } else {
            output("`6Es konnten keine neuen Zimmerklassen gefunden werden.");
        }
    }    // Ende Funktion

    function weiterverarbeitung() {
        global $output, $nav;
        $ausgabe = "";
        require_once "zimmer.php";
        foreach($_POST as $key=>$val) {
                // Cheatsicherung
            if(!in_array($key,$this->run())) die("Deinen Moderatorposten sollten wir wohl mal überdenken, was...?");
                // Verarbeitung
            if($val = "aktiv") {
                    // Daten zwischenspeichern
                $original['output'] = $output;
                $original['nav']        = $nav;
                    // Instanz Erstellen:
                $daten = new $key;
                    // Ausgabe und Navigation zurücksetzen
                $output = $original['output'];
                $nav         = $original['nav'];
                $sql = "INSERT INTO `zimmer` (name,link,label,level,aktiv,gold,gems) VALUES ('".$key."','nhouses.php?op=drin&go=".$key."','".$key."',
                ".(is_int($daten->level)?$daten->level:0).",0,".(is_int($daten->gold)?$daten->gold:0).",".(is_int($daten->gems)?$daten->gems:0).")";
                db_query($sql) or die(db_error(LINK));
                $ausgabe .= "Zimmer ".$key." wurder erfolgreich eingetragen!`n`n";
            }    // Ende IF
        }    // Ende FOREACH
        if($ausgabe != "") {
            output("`^Die angegebenen Zimmerklassen werden nun in die Datenbank eingelesen...`n`n`@");
            output($ausgabe);
            output("`^Du kannst diese nun im Bearbeitungs-Menü finden und dort aktivieren oder ihre Einstellungen verändern.");
        }    else {
            output("`6Also wenn du keine Zimmerklassen auswählst, können hier auch keine eingetragen werden...");
        }
    }    // Ende Funktion

        // Prüfungs-Haupt-Funktion
    function run() {
        // Suchmustereinstellungen und Prädefinitionen
        $suchmuster="^class.* extends zimmer \{";
        $treffer = array(); $gesammt = array(); $zwischen = array();
        $x=0;    $y=0;                                                // Zählvariable - nicht ändern!
        $datei = fopen($this->datei,"r");    // Datei Einlesen
        do{ // Suche
            $zeile = fgets($datei);
            $suche = mb_ereg($suchmuster,$zeile,$treffer[$x]);
            if($suche) {
                    // Stringbearbeitung
                $gesammt = array_merge($gesammt, $treffer[$x]);
                $zwischen[$x] = explode(" ",$gesammt[$x]);
                $zwischen[$x] = $zwischen[$x][1];
                $x++;
            }
        } while($zeile);
        foreach($zwischen as $val) {
            if(!in_array($val,$this->ausnahmen)) {
            $klassen[$y] = $val;
            $y++;
            }    // Ende IF
        }    // Ende FOREACH
        if($y==0) $klassen = false;
        return $klassen;
    }    // Ende Funktion

}    // Ende Klasse
*/
?> 