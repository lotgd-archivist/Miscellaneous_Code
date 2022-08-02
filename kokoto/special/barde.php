<?php
//*-------------------------*
//|         barde.php       |
//|        Scriptet by      |
//|       °*Amerilion*°     |
//|   for mekkelon.de.vu    |
//|     greenmano@gmx.de    |
//|     Idee von Raven      |
//*-------------------------*
/*
BEDINGUNGEN
Mit den einbaue dieser oder einer abgeänderten Version dieser Datei stimme ich folgenden Bedingungen zu

1. Ich verändere keine Grundlegenden Sachen, Bugs dürfen gefixt werden, outputs dürfen umgeschrieben und die
Belohnung aus Balancing-Gründen erhöt oder gesenkt werden, solange die Veränderung nicht den Sinn der
Programmierung verändert.

2. Die Source meines LoGDs ist jederzeit einsehbar

Bei Verstoß gegen diese Bedingung ist es nicht erlaubt dieses Script zu nutzen!!!

//Sanela-Pack Version 1.05
*/
if (!isset($session)) exit();

switch ($_GET['op']) {
    case 'weg':
        output('`rDu betrachtest die jämmerliche Gestalt, findest das sie noch gut genährt aussieht und gehst weiter ohne die Laute und den Barden weiter zu beachten.');
        $session['user']['specialinc'] = '';
    break;
    case 'rep':
        $bgem=mt_rand(1,2);
        $bgold=mt_rand(1,1000);
        $exp=round($session['user']['experience']0.03);
        output('`rDu nimmst den Barden seine Laute worauf er dir die Saiten reicht. Nachdem du sie so gut wie du denkst eingespannt hast reichst du Gerald v.d. Vohgelhaide seine Laute. Dieser sieht sie an');
        switch(mt_rand(1,5)){
               case '1':
                      output('`rund blickt erschreckt auf.`n`i`#"W`bWas`b hast du getan! Nun ist sie völlig hinüber, ich kann mir eine neue kaufen!"`i`n`rEr holt mit der Laute aus und schlägt sie dir über den Kopf. Dir wird schwarz vor Augen und als nächstes erkennst du Luzifer der dich einläd zum Tanz aufzuspielen...');
                      output("`^`n`nDu bist tot.`nGerald von der Vohgelhaide nimmt dir all dein Gold.`nDu verlierst $exp Erfahrung");
                      $session['user']['alive']=false;
                      $session['user']['hitpoints']=0;
                      $session['user']['gold']=0;
                      $session['user']['experience']0.97;
                      addnews($session['user']['name']."spielt nun bei Luzifer zum Tanz auf.");
                      addnav('Tägliche News','news.php');
                      break;
               case '2':
               case '3':
                      output('`rund seine Augen werde groß. Er beginnt zu spielen und es hört sich einfach nur wunderbar an. Du bleibst eine Zeit bei ihm und genießt die Musik. Als er schließlich aufhört zu spielen reicht er dir mit leuchtenden Augen einen Beutel.`n`i`#"So wunderbar hat sich mein Laute noch nie angehört, ich danke euch von Herzen."`i`r`nEr geht fröhlich weiter.`n`n');
                      switch(mt_rand(1,4)){
                              case '1':
                                     output("`^Du bekommst $bgem Edelsteine.");
                                     $session['user']['gems']+=$brohdiamant;
                                     break;
                              case '2':
                              case '3':
                                     output("`^Du bekommst $bgold Goldstücke.");
                                     $session['user']['gold']+=$bgold;
                                     break;
                              case '4':
                                     output("`^Du bekommst $bgem Edelsteine und $bgold Goldstücke.");
                                     $session['user']['gems']+=$bgem;
                                     $session['user']['gold']+=$bgold;
                                     break;
                      }
                      if($session['user']['turns']>2){
                      output('`n`^Du hat bei der Sache 2 Runden verloren.');
                      $session['user']['turns']-=2;
                      }else{
                      output('`n`^Du hast alle deine restlichen Runden verloren.');
                      $session['user']['turns']=0;
                      }
                      $session['user']['specialinc'] = '';
                      break;
               case '4':
               case '5':
                      output('`rund blickt skeptisch. Er spielt ein paar Töne und sieht dich vorwurfsvoll an. `n`i`#"Weißt du, wenn du es nicht kannst dann solltest du es lassen."`i`r`n Er lässt dich stehen und geht seiner Wege');
                      if($session['user']['turns']>2){
                      output('`n`^Du bleibst erst mal vor Ärger über so viel Dreistigkeit 2 Runden stehn.');
                      $session['user']['turns']-=2;
                      }else{
                      output('`n`^Vor Ärger über diese Dreistigkeit verlierst du deine restlichen Runden');
                      $session['user']['turns']=0;
                      }
                      $session['user']['specialinc'] = '';
                      break;
        }

    break;

    default:
        output('`n`c`b`5Der Barde`b`c`n`n `rDir kommt ein traurig aussehnder Barde entgegen. In der Hand trägt er eine Laute, an welcher die Saiten gerissen sind. Er sieht dich an und erzählt dir ungefragt seine Geschichte.`n`#`i "Seit gegrüsst, sagt könnt ihr mit helfen mit meiner Laute? Mir sind die Saiten gerissen, als ich auf der Burg gespielt habe. Ich habe mir zwar neue Saiten besorgt, doch fehlt mir die Kraft sie zu spannen. Nun habe ich bald kein Gold mehr, da ich nichts mehr verdienen kann, welch trauriger Tod für Gerald von der Vohgelhaide, verhungert da er nicht mehr spielen kann.."`i`r`nEr stockt, bricht in Tränen aus und hält dir die Laute hin.');
        $session['user']['specialinc'] ='barde.php';
        addnav('Laute reparieren','forest.php?op=rep');
        addnav('Weitergehen','forest.php?op=weg');
        break;
}
?>