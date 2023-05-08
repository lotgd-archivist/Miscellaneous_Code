
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nSag mal, ".$session[user][name].", hast Du eigentlich heute schon zum
    Himmel geschaut? Das Wetter ist \"`^".$settings['weather']."`0\" !!`n`0");

    if ( $settings['weather'] == "Wechselhaft und kühl, mit sonnigen Abschnitten" ) {
        output("\"Könnte besser sein\" denkst Du Dir und gehst weiter.`0");
    }
    else if ( $settings['weather']=="Warm und sonnig" ) {
        output("Du bist hier ganz in der Nähe von einem kleinen Waldsee. Und so
        wundert es nicht, dass bei diesem Wetter eine ware Mückenplage herrscht.`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du musste die Plagegeister ständig wegscheuchen, was Dich etwas
            Aufmerksamkeit im nächsten Kampf kostet. `n`^Deine Verteidigung wird schwächer.`n`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>0.92,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Die Mücken behindern Dich.",
                                        "activate"=>"defense");
            break;

            case 2:
            output("Bei dem ständigen Geschwirre kannst Du Dich kaum auf den nächsten
            Kampf konzentrieren. `n`^Deine Angriffsfähigkeit ist daher eingeschränkt.`0");
            $session[bufflist]['muecken'] = array("name"=>"`4Mücken",
                                        "rounds"=>10,
                                        "wearoff"=>"Die Mücken haben sich verzogen.",
                                        "defmod"=>1,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Die Mücken behindern Dich.",
                                        "activate"=>"offense");
            break;
        }
    }
    else if ( $settings['weather']=="Regnerisch" ) {
        if ( $session['user']['specialty'] == 1 ) {
            output("Als Du nun bei dem miesen Wetter durch den Wald stapfst, wird
            Deine Stimmung nochmal schlechter.`n
            Deinen Fähigkeiten tut dies jedoch gut und `^Du steigst eine Stufe auf.`0");
            increment_specialty();
        } else {
            output("Als nun ein weiteres Schauer niedergeht, ziehst Du Dir erstmal
            schnell Deinen Regenschutz über.`n
            `^Leider behindert er Dich etwas beim kämpfen...`0");
            $session[bufflist]['regenjacke'] = array("name"=>"`4Regenschutz",
                                        "rounds"=>25,
                                        "wearoff"=>"Gut! Der Regenschauer ist vorbei.",
                                        "defmod"=>0.96,
                                        "atkmod"=>0.92,
                                        "roundmsg"=>"Der Regenschutz behindert Dich.",
                                        "activate"=>"defense");
        }
    }
    else if ( $settings['weather']=="Neblig" ) {
        if ( $session['user']['specialty'] == 3 ) {
            output("Das kommt Dir mit Deinen Diebesfähigkeiten natürlich entgegen.
            `^Du erhälst einen zusätzlichen Waldkampf!`0");
            $session['user']['turns']++;
        } else {
            output("Da ist es noch schwieriger, sich im Wald zurechtzufinden. Und
            prompt nimmst Du einen falschen Abzweig vom Waldweg.`n
            `^Du verlierst einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="Kalt bei klarem Himmel" ) {
       output("Meinst Du wirklich, ".$session[user][armor]." ist da die richtige
        Kleidung?`n`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("`^Du handelst Dir einen Schnupfen ein und verlierst ein paar
            Lebenspunkte.`0");
            $session[user][hitpoints]=round($session[user][hitpoints]*0.95);
            break;
            
            case 2:
            output("Du sammelst etwas Reisig im Unterholz und wärmst Dich erstmal
            an einem kleinen Feuerchen.`n
            `^Die Pause kostet Dich einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
    else if ( $settings['weather']=="Heiß und sonnig" ) {
        output("Im Dorf hast Du es sogar als schwül empfunden und geniesst daher
        die Zeit im schattigen, kühlen Wald.`n
        `^Du bekommst einen Waldkampf.`0");
        $session['user']['turns']++;
    }
    else if ( $settings['weather']=="Starker Wind mit vereinzelten Regenschauern" ) {
        output("Die großen alten Bäume hier biegen sich unter der Wucht einzelner
        Windböen. Ein großer Ast kann dem Wind nicht mehr standhalten und kracht zu
        Boden.`0");
        $case = e_rand(1,2);
        switch ( $case ) {
            case 1:
            output("Du hast mehr Glück als Verstand! Der mächtige Ast schlägt nur
            wenige Schritte von Dir entfernt auf. Dir ist nichts passiert.`n
            `^Etwas eingeschüchtert gehst Du weiter.`0");
            break;
            
            case 2:
            output("Zum Glück schlägt der Ast neben Dir ein, aber ein paar kleinere
            Äste treffen Dich doch. `^Du büsst Lebenspunkte ein!`0");
            $hp = e_rand(1,$session[user][hitpoints]);
            $session[user][hitpoints]=$hp;
            break;
        }
    }
    else if ( $settings['weather']=="Gewittersturm" ) {
        if ( $session['user']['specialty'] == 2 ) {
            output("Um Dich herum zucken die Blitze durch den verdunkelten Himmel.
            Genau richtig, um die magischen Kräfte aufzuladen.`n
            `^Du kannst Deine Fähigkeiten wieder einsetzen.`0");
            //-> fähigkeiten aktivieren
            $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
            $session[user][magicuses]=floor ( $session[user][magic]/3 );
            $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
        } else {
            output("Gerade im Wald ist das nicht ungefährlich!`n`n
            Um Dich vor Blitzschlag zu schützen, stellst Du Dich in einer Höhle
            unter.`n
            `^Du verlierst einen Waldkampf.`0");
            $session['user']['turns']--;
        }
    }
}
?>


