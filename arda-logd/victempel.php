<?php
/*
 * Viconias Tempel Tempel des Glücks Einzufügen in die Berge (victempel.php von Narjana von Arda
 */
require_once "common.php";
addcommentary ();
checkday ();

switch ($_GET ['op']) {
    case "gold1" :
        if ($_GET [op] == "gold1") {
            
            if ($session ['user'] ['munz'] == 0) {
                
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel - der Hufeisenbrunnen`b`c`n`n
                        `5Du hast dich entschlossen 1000 Münze in den Brunnen zu werfen und hoffst auf dein Glück und den Segen der Göttin.
                        Als du das platschen hörst merkst du `n`n" );
                
                $session ['user'] ['munz'] = '1';
                $session ['user'] ['gold'] -= 1000;
                switch (e_rand ( 1, 3 )) {
                    case 1 :
                        output ( "erstmal gar nichts. Doch auf einmal schwirrt ein Glücksfluffel um dich herum und ruft: \"`VHey, Danke. Weil mir das so gefallen hat, hab ich was für dich.\"`% Er wirft dir einen schweren Beutel zu, in dem 4000 Goldstücke sind." );
                        
                        $session ['user'] ['gold'] += 4000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        
                        break;
                    
                    case 2 :
                        output ( "erstmal gar nichts. Doch plötzlich taucht ein Fluflafliffel vor dir auf und schnautzt dich an. \"`MDeine ganzen Münzen sind mir auf dem Kopf gelandet. Weil ich nun Kopfweh hab, darfst du mir gleich noch ein paar geben.\" `5" );
                        
                        
                        if ($session ['user'] ['gold'] < 5000) {
                        output("Da du nicht genug Gold dabei hast, knurrt der Fluflafliffel dich an. \"`MAch, du hast nicht genug dabei? Ich sag meinen Verwandten bescheid, ich krieg dein Gold von der Bank.\" `5Mit einem hämischen Grinsen verschwindet er und dir wird schlagartig bewusst, daß du gerade 5000 Gold von der Bank verloren hast.");
                            $session ['user'] ['goldinbank'] -= 5000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
} else {
                        output("Der Fluflafliffel verschwindet in deinem Goldbeutel und kommt mit 5000 Goldstücken wieder heraus, nur um taumelnd davonzufliegen.");
                            $session ['user'] ['gold'] -= 5000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        }
                        break;
                    
                    case 3 :
                        output ( "erstmal gar nichts. Auch nachdem du einige Zeit gewartet hast, passiert nichts und du beschließt zu gehen.");
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        break;
                    
                    
                }
            } else {
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel`c`b`n
                                Du hast doch schon genug Geld an die Göttin gegeben. Lass sie mal was ausspannen und zielwerfen mit den Münzen spielen.`n" );
                
                addnav ( "zurück", "victempel.php?op=brunnen" );
                
                break;
            }
            break;
        }
    case "gold3" :
        if ($_GET [op] == "gold3") {
            
            if ($session ['user'] ['munz'] == 0) {
                
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel - der Hufeisenbrunnen`b`c`n`n
                        `5Du hast dich entschlossen 3000 Münze in den Brunnen zu werfen und hoffst auf dein Glück und den Segen der Göttin.
                        Als du das platschen hörst merkst du `n`n" );
                
                $session ['user'] ['munz'] = '1';
                $session ['user'] ['gold'] -= 3000;
                switch (e_rand ( 1, 3 )) {
                    case 1 :
                        output ( "erstmal gar nichts. Doch auf einmal schwirrt ein Glücksfluffel um dich herum und ruft: \"`VHey, Danke. Weil mir das so gefallen hat, hab ich was für dich.\"`% Er wirft dir einen schweren Beutel zu, in dem 12000 Goldstücke sind." );
                        
                        $session ['user'] ['gold'] += 12000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        
                        break;
                    
                    case 2 :
                        output ( "erstmal gar nichts. Doch plötzlich taucht ein Fluflafliffel vor dir auf und schnautzt dich an. \"`MDeine ganzen Münzen sind mir auf dem Kopf gelandet. Weil ich nun Kopfweh hab, darfst du mir gleich noch ein paar geben.\" `5" );
                        
                        
                        if ($session ['user'] ['gold'] < 5000) {
                        output("Da du nicht genug Gold dabei hast, knurrt der Fluflafliffel dich an. \"`MAch, du hast nicht genug dabei? Ich sag meinen Verwandten bescheid, ich krieg dein Gold von der Bank.\" `5Mit einem hämischen Grinsen verschwindet er und dir wird schlagartig bewusst, daß du gerade 5000 Gold von der Bank verloren hast.");
                            $session ['user'] ['goldinbank'] -= 5000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
} else {
                        output("Der Fluflafliffel verschwindet in deinem Goldbeutel und kommt mit 7000 Goldstücken wieder heraus, nur um damit taumelnd davonzufliegen.");
                            $session ['user'] ['gold'] -= 5000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        }
                        break;
                    
                    case 3 :
                        output ( "erstmal gar nichts. Auch nachdem du einige Zeit gewartet hast, passiert nichts und du beschließt zu gehen.");
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        break;
                    
                    
                }
            } else {
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel`c`b`n
                                Du hast doch schon genug Geld an die Göttin gegeben. Lass sie mal was ausspannen und zielwerfen mit den Münzen spielen.`n" );
                
                addnav ( "zurück", "victempel.php?op=brunnen" );
                
                break;
            }
            break;
        }
case "gold5" :
        if ($_GET [op] == "gold5") {
            
            if ($session ['user'] ['munz'] == 0) {
                
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel - der Hufeisenbrunnen`b`c`n`n
                        `5Du hast dich entschlossen 5000 Münze in den Brunnen zu werfen und hoffst auf dein Glück und den Segen der Göttin.
                        Als du das platschen hörst merkst du `n`n" );
                
                $session ['user'] ['munz'] = '1';
                $session ['user'] ['gold'] -= 5000;
                switch (e_rand ( 1, 3 )) {
                    case 1 :
                        output ( "erstmal gar nichts. Doch auf einmal schwirrt ein Glücksfluffel um dich herum und ruft: \"`VHey, Danke. Weil mir das so gefallen hat, hab ich was für dich.\"`% Er wirft dir einen schweren Beutel zu, in dem 20000 Goldstücke sind." );
                        
                        $session ['user'] ['gold'] += 20000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        
                        break;
                    
                    case 2 :
                        output ( "erstmal gar nichts. Doch plötzlich taucht ein Fluflafliffel vor dir auf und schnautzt dich an. \"`MDeine ganzen Münzen sind mir auf dem Kopf gelandet. Weil ich nun Kopfweh hab, darfst du mir gleich noch ein paar geben.\" `5" );
                        
                        
                        if ($session ['user'] ['gold'] < 7000) {
                        output("Da du nicht genug Gold dabei hast, knurrt der Fluflafliffel dich an. \"`MAch, du hast nicht genug dabei? Ich sag meinen Verwandten bescheid, ich krieg dein Gold von der Bank.\" `5Mit einem hämischen Grinsen verschwindet er und dir wird schlagartig bewusst, daß du gerade 7000 Gold von der Bank verloren hast.");
                            $session ['user'] ['goldinbank'] -= 7000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
} else {
                        output("Der Fluflafliffel verschwindet in deinem Goldbeutel und kommt mit 7000 Goldstücken wieder heraus, nur um damit taumelnd davonzufliegen.");
                            $session ['user'] ['gold'] -= 7000;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        }
                        break;
                    
                    case 3 :
                        output ( "erstmal gar nichts. Auch nachdem du einige Zeit gewartet hast, passiert nichts und du beschließt zu gehen.");
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        break;
                    
                    
                }
            } else {
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel`c`b`n
                                Du hast doch schon genug Geld an die Göttin gegeben. Lass sie mal was ausspannen und zielwerfen mit den Münzen spielen.`n" );
                
                addnav ( "zurück", "victempel.php?op=brunnen" );
                
                break;
            }
            break;
        }
case "trink":    
    if ($_GET [op] == "trink") {
            page_header ( "Glückstempel" );
            output ( "`c`bGlückstempel - der Glücksbrunnen`b`c`n`n
                Du trittst an den Hufeisenbrunnen heran und greifst nach einer Münze.`n`n" );
            switch (e_rand ( 1, 3 )) {
                    case 1 :
                        output ( "Du fällst in den Brunnen und die Figur, die du beim Fallen machst, gefällt einem Glücksfluffel. Dieser schenkt dir ein paar Edelsteine." );
                        
                        $session ['user'] ['gems'] += 10;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        
                        break;
                    
                    case 2 :
                        output("Der Fluflafliffel, der dich beim Sturz in den Brunnen beobachtet, macht sich an deinem Edelsteinbeutel zu schaffen. Schließlich schafft er es, mit 5 Edelsteinen zu entkommen.");
                            $session ['user'] ['gems'] -= 5;
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        
                        break;
                    
                    case 3 :
                    
                        output ( "Du fällst einfach in den Brunnen und wirst nass bis auf die Knochen.");
                        addnav ( "zurück", "victempel.php?op=brunnen" );
                        break;
                    
                    
                }
            
            
            break;
            
        }
    case "bad" :
        if ($_GET [op] == "bad") {
            page_header ( "Glückstempel" );
            output ( "`c`bGlückstempel - der Hufeisenbrunnen`b`c`n`n
                Du trittst an den Hufeisenbrunnen heran. Bist du mutig genug zu trinken?`n`n" );
            
            addnav ( "trinken", "victempel.php?op=trink" );
            addnav ( "Münze reinwerfen", "victempel.php?op=gold" );
            addnav ( "imaginären Fisch fangen", "victempel.php?op=fisch" );
            addnav ( "zurück", "victempel.php?op=halle" );
            
            break;
        }
    case "fisch" :
        if ($_GET [op] == "fisch") {
            page_header ( "Glückstempel" );
            output ( "`c`bGlückstempel - der Hufeisenbrunnen`b`c`n`n
                Da - war da nicht eine Bewegung im Brunnen? Du bist ganz fest der Meinung etwas gesehen zu haben und stolperst mit deiner gesamten
                Kleidung durch den gebogenen Brunnen um irgendwie an diesen goldenen Fisch zu kommen. Er MUSS einfach da sein. Fisch... Fisch! FIIIISCHHHH!!`n
                Schließlich glüht in der Mitte des Tempels ein seltsames Licht auf, das sich langsam zu einem in allen Regenbogenfarben schimmerndem Portal erweitert.
                Eine Stimme erklingt mit einem Kichern an deinem Ohr `5\"Oh - ich glaub du hast dich im Tempel geirrt, oder? Komm, ich bring dich zu meinem Schwesterchen.\"`n
                `&Ehe du es dich versiehst wirst du von einer Unsichtbaren Hand durch das Portal geschoben - ohne deinen geliebten Fisch...`n" );
            
            addnav ( "weiter", "narjan.php" );
            addnews ( "`%" . $session [user] [name] . "`5 fing einen Fisch der nicht da war. Narjanas Licht wird über ihm leuchten" );
            
            break;
        }
    
    case "brunnen" :
        if ($_GET [op] == "brunnen") {
            page_header ( "Glückstempel" );
            output ( "`c`bGlückstempel - der Glücksbrunnen`b`c`n`n
                Du trittst an den Glücksbrunnen heran. Was wirst du wohl tun?`n`n" );
            
            viewcommentary ( "brunnen", "reden", 15 );
            
            addnav ( "Nach der Münze greifen", "victempel.php?op=trink" );
            if ($session ['user'] ['gold'] >999) addnav ( "1000 Münzen reinwerfen", "victempel.php?op=gold1" );
            if ($session ['user'] ['gold'] >2999) addnav ( "3000 Münzen reinwerfen", "victempel.php?op=gold3" );
            if ($session ['user'] ['gold'] >4999) addnav ( "5000 Münzen reinwerfen", "victempel.php?op=gold5" );
            addnav ( "imaginären Fisch fangen", "victempel.php?op=fisch" );
            addnav ( "zurück", "victempel.php" );
            
            break;
        }
    
    case "halle" :
        if ($_GET [op] == "halle") {
            page_header ( "Glückstempel" );
            output ( "`c`bGlückstempel`b`c`n`n
                `% Du betrittst den Tempel und findest dich in einer anderen Welt. Sobald du den Tempel betrittst, fühlst du dich glücklich und zufrieden. Der ganze Tempel strahlt Glück nur so aus, überall sind verschiedene Glückssymbole in Gold angebracht, der Rest des Raumes ist in verschiedenen Rosa und Pinktönen gehalten. In der Mitte des Hauptraumes siehst du einen großen, hufeisenförmigen Brunnen.`n`n" );
            
            viewcommentary ( "halle", "reden", 15 );
            
            addnav ( "Labyrinth", "abandoncastle.php" );
            addnav ( "Hufeisenbrunnen", "victempel.php?op=brunnen" );
            addnav ( "zurück", "victempel.php" );
            
            break;
        }
    
    case "luck" :
        if ($_GET [op] == "luck") {
            
            if ($session ['user'] ['lucky'] == 0) {
                
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel`c`b`n
                        Du streckst dich und versuchst den Torbogen zu berühren. Irgendwo hast du mal gehört dass die Göttin so ihr Glück an die Menschen weitergibt`n
                        " );
                
                $session ['user'] ['lucky'] = '1';
                switch (e_rand ( 1, 2 )) {
                    case 1 :
                        output ( "Die Leute im Dorf scheinen Recht zu haben. Du Lachst und spührst wie das Glücksgefühl dich durchstöhmt und dich stärker macht." );
                        
                        $session ['user'] ['turns'] += 5;
                        $session [user] [defence] = round ( $session [user] [defence] * 1.05 );
                        $session [user] [attack] = round ( $session [user] [attack] * 1.05 );
                        addnav ( "zurück", "victempel.php" );
                        
                        break;
                    case 2 :
                        output ( "Nur Scheinbar hat die Glücksgöttin heute keinen Guten Tag. Statt des erhofften Glücks wird eine große Ladung Pech über dir ausgeschüttet. Du fühlst dich müde, schwach und sehr schlecht gelaunt..." );
                        
                        $session ['user'] ['turns'] -= 5;
                        $session [user] [defence] = round ( $session [user] [defence] * 0.95 );
                        $session [user] [attack] = round ( $session [user] [attack] * 0.95 );
                        addnav ( "zurück", "victempel.php" );
                        break;
                }
            } else {
                page_header ( "Glückstempel" );
                output ( "`c`bGlückstempel`c`b`n
                                Mehr als einmal pro Tag sollte man die Gunst der Göttin wirklich nicht herausfordern, meinst du nicht?`n" );
                
                addnav ( "zurück", "victempel.php" );
                
                break;
            }
            break;
        }
    default :
        if ($_GET [op] == "") {
            
            page_header ( "Glückstempel" );
            
            output ( "`c`bGlückstempel`b`c`n`n

                 `%Sofort beim Betreten des Tempels erkennt man, daß es sich um den Tempel der verrückten und launischen Glücksgöttin Viconia handeln muss.
Das ganze Gebäude ist in Rosa und Pink gehalten und die Wände sowie die Decke sind über und über mit Glückssymbolen bedeckt, die lauter goldene Sterne, Hufeisen und hellrote siebenblättrige Marpsblätter darstellen. Um dich herum wuseln lauter Glücksfluffel, die die ganzen Symbole polieren. Doch bei genauerem Umsehen fällt ein unscheinbarer, dunklerer Teil auf. Bei genauerem Betrachten erkennst du die Symbole des Pechs, das violette Hoppelhäschen, die pechschwarze Drachenlilie und die schwarzlila Fluflafliffels. Die letzten sorgen dafür, daß die Symbole deutlich sichtbar sind.
Genau in der Mitte des Raums, wo der helle Bereich in den dunklen übergeht, steht der legendäre Brunnen der Glücksmünzen, in dem auch der unsichtbare regenbogenfarbene Fisch leben soll. 
Wirst du versuchen, eine der Münzen zu erreichen, eine Münze hineinzuwerfen oder den Fisch zu fangen? Oder ist dir das Risiko zu hoch und du gehst einfach wieder?`n`n" );
            
            viewcommentary ( "halle", "reden", 15 );
            
            addnav ( "Labyrinth", "abandoncastle.php" );
            addnav ( "Hufeisenbrunnen", "victempel.php?op=brunnen" );
            addnav ( "zurück" );
            addnav ( "Lurnfälle", "narjan.php?op=lurn" );
            addnav ( "Berge", "berge.php" );
            
            break;
        }
}
page_footer ();
?>