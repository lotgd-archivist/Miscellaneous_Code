
<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();
require_once "func/increment_specialty.php";

if($_GET['op'] == '' || $_GET['op']== 'search'){
    output("Sag mal, ".$session[user][name].", hast du eigentlich heute schon zum
    Himmel geschaut?`nDas Wetter ist `^".$settings['weather']."`0!!`n`n");

    if ( $settings['weather'] == "bewölkt" ) {
        output("`&\"Könnte besser sein\",`0 denkst du dir und gehst weiter.");
    }
    else if ( $settings['weather'] == "windig" ) {
        output("`&\"Könnte besser sein\",`0 denkst du dir und gehst weiter.");
    }
    else if ( $settings['weather'] == "aufgelockert und etwas wärmer" ) {
        output("`&\"Könnte besser sein\",`0 denkst Du Dir und gehst weiter.");
    }
    else if ( $settings['weather']=="warm und schwül" ) {
        output("Du bist hier ganz in der Nähe von einem kleinen Bergsee. Und so
        wundert es nicht, dass bei diesem Wetter eine wahre Mückenplage herrscht.`n`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du musst die Plagegeister ständig wegscheuchen, was dich etwas
            Aufmerksamkeit im nächsten Kampf kostet. `n`n`^Deine Verteidigung wird schwächer.`n`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>0.92,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"defense");
            break;

            case 2:
            output("Bei dem ständigen Geschwirre kannst du dich kaum auf den nächsten
            Kampf konzentrieren. `n`n`^Deine Angriffsfähigkeit ist daher eingeschränkt.`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>1,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"offense");
            break;
        }
    }else if ( $settings['weather']=="sehr warm" ) {
        output("Du bist hier ganz in der Nähe von einem kleinen Bergsee. Und so
        wundert es nicht, dass bei diesem Wetter eine wahre Mückenplage herrscht.`n`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du musst die Plagegeister ständig wegscheuchen, was dich etwas
            Aufmerksamkeit im nächsten Kampf kostet. `n`n`^Deine Verteidigung wird schwächer.`n`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>0.92,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"defense");
            break;

            case 2:
            output("Bei dem ständigen Geschwirre kannst du dich kaum auf den nächsten
            Kampf konzentrieren. `n`n`^Deine Angriffsfähigkeit ist daher eingeschränkt.`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>1,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"offense");
            break;
        }
    }
    else if ( $settings['weather']=="warm mit Sommergewitter" ) {
        output("Du bist hier ganz in der Nähe von einem kleinen Bergsee. Und so
        wundert es nicht, dass bei diesem Wetter eine wahre Mückenplage herrscht.`n`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du musst die Plagegeister ständig wegscheuchen, was dich etwas
            Aufmerksamkeit im nächsten Kampf kostet. `n`n`^Deine Verteidigung wird schwächer.`n`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>0.92,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"defense");
            break;

            case 2:
            output("Bei dem ständigen Geschwirre kannst du dich kaum auf den nächsten
            Kampf konzentrieren. `n`n`^Deine Angriffsfähigkeit ist daher eingeschränkt.`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>1,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Die Mücken behindern dich.",
                                        "activate"=>"offense");
            break;
        }
    }
    else if ( $settings['weather']=="regnerisch" ) {
        if ( $session['user']['specialty'] == 1 ) {
            output("Als Du nun bei dem miesen Wetter durch die Berge stapfst, wird
            deine Stimmung nochmal schlechter.`n`n
            Deinen Fähigkeiten tut dies jedoch gut und `^du steigst eine Stufe auf.`n`0");
            increment_specialty();
        } else {
            output("Als nun ein weiteres Schauer niedergeht, ziehst du dir erstmal
            schnell Deinen Regenschutz über.`n`n
            `^Leider behindert er dich etwas beim Kämpfen...`0");
            $session[bufflist]['regenjacke'] = array("name"=>"`4Regenschutz",
                                        "rounds"=>25,
                                        "wearoff"=>"Gut! Der Regenschauer ist vorbei.",
                                        "defmod"=>0.96,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Der Regenschutz behindert dich.",
                                        "activate"=>"defense");
        }
    }
    else if ( $settings['weather']=="neblig" ) {
        if ( $session['user']['specialty'] == 3 ) {
            output("Das kommt dir mit deinen Diebesfähigkeiten natürlich entgegen.`n`n
            `^Du erhältst einen zusätzlichen Waldkampf!`0");
            $session['user']['turns']++;
        } else {
            output("Da ist es noch schwieriger, sich im Gebirge zurechtzufinden. Und
            prompt nimmst du eine falsche Abzweigung vom Bergpfad.`n`n
            `^Du verlierst einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kühl bei klarem Himmel" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }else if ( $settings['weather']=="windig und frisch" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kalt mit Schneefall" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="klirrend kalt bei klarem Himmel" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kalt" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kalt bei flockigem Weihnachtsschneefall" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="bewölkt bei leichtem Schneefall" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="saukalt bei klarem Himmel" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kalt bei Schneeregen" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="kalt mit Schneegestöber" ) {
       output("Meinst du wirklich, deine Rüstung ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;

            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst dich erstmal
            an einem kleinen Feuerchen.`n`n
            `^Die Pause kostet dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="sonnig und heiß" ) {
        output("Im Alvion hast du es sogar als schwül empfunden und genießt daher
        die Zeit im schattigen, kühlen Bergwald.`n`n
        `^Du bekommst einen Waldkampf.`0");
        $session['user']['turns']++;
    }
    else if ( $settings['weather']=="sehr, sehr heiß" ) {
        output("In Alvion hast du es sogar als schwül empfunden und genießt daher
        die Zeit im schattigen, kühlen Bergwald.`n`n
        `^Du bekommst einen Waldkampf.`0");
        $session['user']['turns']++;
    }
    else if ( $settings['weather']=="stark windig mit vereinzelten Regenschauern" ) {
        output("Die großen alten Bäume hier biegen sich unter der Wucht einzelner
        Windböen. Ein großer Ast kann dem Wind nicht mehr standhalten und kracht zu
        Boden.`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du hast mehr Glück als Verstand! Der mächtige Ast schlägt nur
            wenige Schritte von dir entfernt auf. Dir ist nichts passiert.`n`n
            `^Etwas eingeschüchtert gehst du weiter.`0");
            break;

            case 2:
            output("Zum Glück schlägt der Ast neben dir ein, aber ein paar kleinere
            Äste treffen dich doch.`n`n `^Du büsst Lebenspunkte ein!`0");
            $hp = e_rand(1,$session[user][hitpoints]);
            $session[user][hitpoints]=$hp;
            break;
        }
    }
    else if ( $settings['weather']=="stark windig, aber warm" ) {
        output("Die großen alten Bäume hier biegen sich unter der Wucht einzelner
        Windböen. Ein großer Ast kann dem Wind nicht mehr standhalten und kracht zu
        Boden.`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du hast mehr Glück als Verstand! Der mächtige Ast schlägt nur
            wenige Schritte von dir entfernt auf. Dir ist nichts passiert.`n`n
            `^Etwas eingeschüchtert gehst du weiter.`0");
            break;

            case 2:
            output("Zum Glück schlägt der Ast neben dir ein, aber ein paar kleinere
            Äste treffen dich doch.`n`n `^Du büßt Lebenspunkte ein!`0");
            $hp = e_rand(1,$session[user][hitpoints]);
            $session[user][hitpoints]=$hp;
            break;
        }
    }
    else if ( $settings['weather']=="regnerisch mit Gewitterstürmen" ) {
        if ( $session['user']['specialty'] == 2 ) {
            output("Um dich herum zucken die Blitze durch den verdunkelten Himmel.
            Genau richtig, um die magischen Kräfte aufzuladen.`n`n
            `^Du kannst deine Fähigkeiten wieder einsetzen.`0");
            //-> fähigkeiten aktivieren
            $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
            $session[user][magicuses]=floor ( $session[user][magic]/3 );
            $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        } else {
            output("Gerade im Gebirge ist das nicht ungefährlich!`n`n
            Um dich vor Blitzschlag zu schützen, stellst du dich in einer Höhle
            unter.`n`n
            `^Du verlierst einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
}
?>

