<?
/*
                                §@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@§
                                §                                                      §
                                §  Idee und Umsetzung                                  §
                                §  Morpheus aka Apollon                                §
                                §  2008 für Morpheus.Lotgd(LoGD 0.9.7 +jt ext (GER) 3) §
                                §  Mail to Morpheus@magic.ms or Apollon@magic.ms       §
                                §  gewidmet meiner über alles geliebten Blume          §
                                §                                                      §
                                @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
*/
output("`3Auf Deinem Weg durch den `2Wald`3 kommst Du zu einem kleinen `!Weiher`3.");
output("`3Übermütig nimmst Du kleine, flache `7Steine`3 und lässt sie auf dem `#Wasser `3springen, als Du plötzlich ausrutscht und in den `TMatsch `3am Ufer fällst.`n");
output("`3Jetzt siehst Du aus wie ein `rFerkel`3, aber als Du Dich wieder aufrichtest");
//$session['user']['clean']+=5;
//$session['user']['charm']-=5;
switch(e_rand(1,3)){
    case 1:
    $ge=(e_rand(2,4));
    output("`3findest Du einen Beutel mit `@ $ge Gems`3, die du einsteckst.");
    $session['user']['gems']+=$ge;
    break;
    case 2:
    $go=(e_rand(100,500));
    output("`3findest Du einen Beutel mit `^ $go Gold`3, das du einsteckst.");
    $session['user']['gold']+=$go;
    break;
    case 3:
    output("`3findest Du einen merkwürdig ausehenden Ring, den Du reinigst, bis er wieder `^gl`6än`^zt`3 und ihn dann einsteckst. Wer weiß, wem er gehört und wozu er noch gut sein kann!?");
    db_query("INSERT INTO items (name,owner,class,gold,gems,description) VALUES ('Seltsamer Goldring',".$session[user][acctid].",'Schmuck',10000,8,'Ein golden glänzender Ring, den Du im Schlamm gefunden hast, wer weiß, wem er gehört')");
    break;
    }
?>