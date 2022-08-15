
<?PHP
 /*
  * file: banker.php
  *
  * Copyright (C) 2005 - 2006 Lyra
  * Email: <...>
  * Adresse: http://lyra.x-mashine.de/
  *
  * Beschreibung:
  * Your description!
 */

  require_once ('./common.php'); 

  checkday ();
  page_header ("Der Bankergnom"); 

  addnav ('Z?Zurück in den Wald', 'forest.php'); 
  output ('`1Das kleine Haus am Wegesrand fällt vor allem dadurch auf, dass die Tür halboffen steht. Neugierig siehst du hinein, wobei du dich tief bücken musst, um dich nicht am Türrahmen zu stoßen. `nDrinnen herrscht einiges Durcheinander, Unrat liegt überall verteilt, dazwischen alte Bücher, zerfetzte Kleidung und allerlei Plunder, den man wohl gemeinhin als Abfall bezeichnen würde. Mitten in diesem Durcheinander thront ein Gnom und sieht dich aus großen, runden Augen an. `n
`P"Sei gegrüßt, '.($session['user']['sex']?'edle Dame':'werter Herr').'. Suchst du vielleicht nach einem Boten?"`1, fragt er mit eifriger Stimme und eilt schon zu dir, um sich vor dir überaus tief zu verbeugen. `n
`P"Man nennt mich den Bankgnom. Ich freue mich, wenn ich dir helfen kann."`n`1Von diesem Wesen hast du tatsächlich schon gehört und bisher sind keine Fälle von Betrug bekannt geworden. So überreichst du ihm dein Gold und der Gnom macht sich sogleich auf den Weg in die Stadt. `n`n'); 

  $session['user']['goldinbank'] += $session['user']['gold']; 
  $session['user']['gold'] = 0; 
  output('`^Du hast damit '.($session['user']['goldinbank'] >= 0 ? '`^ein Guthaben von' : 'Schulden in Höhe von').' `&'.abs($session['user']['goldinbank']+$session['user']['gold']).'`^ Gold auf deinem Konto.'); 

page_footer();
?>

