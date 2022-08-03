// <?php

//               <<<<<          <<<<<<<          <<<      <<    <<<<<<<<    <<                 <<<<<<<
//              <<<            <<<   <<          << <     <<    <<<         <<                <<<   <<
//             <<<            <<<     <<         <<  <    <<    <<<         <<               <<<     <<
//              <<<          <<<       <<        <<   <   <<    <<<<<<<<    <<              <<<       <<
//                <<<<      <<< <<<<<<<<<<       <<    <  <<    <<<         <<             <<< <<<<<<<<<<
//                  <<<    <<<           <<      <<     < <<    <<<         <<            <<<           <<
//                 <<<    <<<             <<     <<      <<<    <<<         <<           <<<             <<
//              <<<<<    <<<               <<    <<       <<    <<<<<<<<    <<<<<<<<<<  <<<               <<

//                          By °*Amerilion*° Version 1.1 for mekkelon.de.vu comments to greenmano@gmx.de
//                      korrigiert und optimiert von Tyrador nochmal korrigiert und optimiert von Devilzimti


// //Einführung 1.0:
// Sanela ist der Name eines kleinen Fischerdorfes in dern Nähe des LoGD-Dorfes.
// Es enthält eine Kirche, eine Grotte, einen Strand und eine Schmiede welche nicht
// immer offen ist.
// Außerdem habe ich dort noch Nerwen und Donolos Haus der Qualen zur Verfügung gestellt,
// welche aber nicht von mir sind und deshalb nicht hier zum download stehen.
// Das selbe gilt für festungsruine.php in der sanelasee.php und für cruxis.php in huegel.php.
// Wenn ihr sie habt müsst ihr mal schaun, ich habe die Nav-Punkte einfach auskommentiert.

// Des weitern sind hier noch ein See und ein Turm vorhanden.
// So wie alles angeordnet ist muss man einfach nur den see mit der village.php verbinden,
// der Rest ist untereinander verbunden.

// Das ganze ist für ein Dorf erstellt worden welches kein Meer hatt,
// wenn ihr schon einen Strand habt ist es ein wenig seltsam das man auch zum See kann und so,
// aber ich denke in der LoGD-Welt ist auch das möglich ;)

// Schaut es euch einfach an.

// //Einführung 1.05:
// Alle bisher gefundenen Bugs gefixt
// Brunnen eingefügt
// Dazu zwei Waldspecials
// ">>Es ist nicht frei von Schreibfehlern<<"

// //
// Einführung 1.1:
// Alle bisherigen Bugs gefixt
// Anleitung gefixt
// Chatzone beim See hinzugeügt
// Dazu 2 Waldspecials
// ">>Es ist nicht frei von Schreibfehlern<<"

// '>>>Finale Version - Keine weitere Entwicklung - Nur noch Bugfixing<<<'

// //Enthält
// -sanela.php
// -sanelasee.php
// -sanelaschmiede.php
// -sanelastrand.php
// -grotte.php
// -huegel.php
// -kirche.php
// -turm.php
// -wanderweg.php
// //Neu in 1.05
// -sanelabrunnen.php
// -specials/sanfind.php
// -specials/sanelabrunnenschatz


// //Install\\
// in village.php suche:
// addnav("Wald","forest.php");
// und füge danach ein:
// addnav("See","sanelasee.php");

// in newday.php:
// suche
// $session['user']['witch'] = 0;
// setzte danch ein
// $session['user']['sanela']['turm']=0;
// $session['user']['sanela']['grotte']=0;
// $session['user']['sanela']['kirche']=0;
// $session['user']['sanela']['sanela']=0;
// $session['user']['sanela']['haganir']=0;
// $session['user']['sanela']['haganirschmiede']=0;
// $session['user']['sanela']['schwimm']=0;
// $session['user']['sanela']['huegel']=0;
// $session['user']['sanela']['strand']=0;



// SQL-Befehl ausführen:

// ALTER TABLE `accounts` ADD `sanela` TEXT NOT NULL;

// 'Das wars, Fehler bitte unbedingt melden!!!'
// ?>