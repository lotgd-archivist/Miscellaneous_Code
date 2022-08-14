
ï»¿<?php

//20160417

require_once("common.php");

checkday();

page_header("Sudoku");



// multivillages mod

if (isset($_GET['mv']) && $_GET['mv']!=$session['user']['specialmisc']) $session['user']['specialmisc']=$_GET['mv'];

$village=($session['user']['specialmisc']>0)?"tradervillages.php?village={$session['user']['specialmisc']}":"village.php";

  

if ($_GET['op']=='' && $_GET['level']>0){

  

    

    /**

    * Method to add puzzle

    * puzzle generated using the "Peraita method - Author unknown"

    * @param string $difficulty The difficulty raing of the puzzle (used to remove numbers)

    * $size the base size of the grid eg. 3 = 9x9 board, 5 = 25*25 board

    * use only dif up to 3, else the graphical output will not fit

    **/

    function generate($difficulty)

    {

        // set up board size

        if($difficulty != 4){

            $size = 3;

        }else{

            $size = 5;

        }

        // randomize numbers

        $numbers = range(1, pow($size, 2));

        shuffle($numbers);



        // row index sets

        $x = 1;

        for($i = 1; $i <= pow($size, 2); $i++){

            $a = "rowIndex_" . $i; //set up variable names eg $rowIndex_1

            for($ii = 1; $ii <= pow($size, 2); $ii++){

                ${$a}[$ii] = $x; //set up variable eg $rowIndex[0] = 1

                $x = $x + 1;

            }

            $allRows[$i] = $$a; //set up array eg $temp[0] = $rowIndex_1

        }

        $temp = array_chunk($allRows, $size, true);

        foreach($temp as $key => $arrRow){

            $a = "arrRow_" . $key; // set up variable names

            $$a = $arrRow; // set up variable

            $arrAllRows[$key] = $$a; // set up array

        }



        // column index sets

        for($i = 1; $i <= pow($size, 2); $i++){

            $a = "colIndex_" . $i; // set up variable names

            $x = $i;

            for($ii = 1; $ii <= pow($size, 2); $ii++){

                ${$a}[$ii] = $x; // set up variable

                $x = $x + pow($size, 2);

            }

            $allCols[$i] = $$a; // set up array

        }

        $temp = array_chunk($allCols, $size, true);

        foreach($temp as $key => $arrCol){

            $a = "arrCol_" . $key; // set up variable names

            $$a = $arrCol;  // set up variable

            $arrAllCols[$key] = $$a; // set up array

        }



        // block index sets

        $x = 1;

        $y = 1;

        for($i = 1; $i <= $size; $i++){

            for($ii = 1; $ii <= $size; $ii++){

                $a = "blockIndex_" . $x; // set up variable names

                $z = 1;

                for($iii = 1; $iii <= $size; $iii++){

                    for($iv = 1; $iv <= $size; $iv++){

                        ${$a}[$z++] = $y; // set up variable

                        $y = $y + 1;

                    }

                    $y = $y + ((pow($size, 2)) - ($size));

                }

                $arrAllBlocks[$x] = $$a; // set up array

                $x = $x + 1;

            }

            $y = ($i * $size) + 1;

        }



        // set up basic board

        for($i = 1; $i <= pow($size, 2); $i++){

            foreach($arrAllBlocks as $block){

                $temp = $numbers;

                foreach($block as $index){

                    $data[$index] = array_shift($temp);

                }

                $firstNumber = array_shift($numbers);

                $numbers = array_pad($numbers, pow($size, 2), $firstNumber);

            }

        }

        ksort($data);



        // shuffle rows

        for($i = 0; $i <= $size - 2; $i++){

            foreach($arrAllRows as $arrRows){

                shuffle($arrRows);

                $arrRows = array_slice($arrRows, 0, 2); // takes first 2 rows

                foreach($arrRows as $key => $row){

                    foreach($row as $rowKey => $index){

                        if($key == 0){

                            $row_1[$rowKey] = $data[$index];

                        }else{

                            $row_2[$rowKey] = $data[$index];

                        }

                    }

                }

                foreach($arrRows as $key => $row){ // swops them

                    foreach($row as $rowKey => $index){

                        if($key == 0){

                            $data[$index] = $row_2[$rowKey];

                        }else{

                            $data[$index] = $row_1[$rowKey];

                        }

                    }

                }

            }

        }



        // shuffle columns

        for($i = 0; $i <= $size - 2; $i++){

            foreach($arrAllCols as $arrCols){

                shuffle($arrCols);

                $arrCols = array_slice($arrCols, 0, 2); // takes first 2 columns

                foreach($arrCols as $key => $col){

                    foreach($col as $colKey => $index){

                        if($key == 0){

                            $col_1[$colKey] = $data[$index];

                        }else{

                            $col_2[$colKey] = $data[$index];

                        }

                    }

                }

                foreach($arrCols as $key => $col){ // swops them

                    foreach($col as $colKey => $index){

                        if($key == 0){

                            $data[$index] = $col_2[$colKey];

                        }else{

                            $data[$index] = $col_1[$colKey];

                        }

                    }

                }

            }

        }

        $solution = implode(",", $data);



        //remove pairs of numbers symetrically

        if($difficulty == 1){

            //$pairs = 16;

            $pairs = 18;

        }elseif($difficulty == 2){

            //$pairs = 22;

            $pairs = 24;

        }elseif($difficulty == 3){

            //$pairs = 30;

            $pairs = 32;

        }else{

            $pairs = 170;

        }

        for($i = 1; $i <= $pairs; $i++){

            do{

                $number_1 = rand(1, pow($size, 4));

            }while($number_1 == (((pow($size, 4) - 1) / 2) + 1));

            $data[$number_1] = '';

            $number_2 = (pow($size, 4) + 1) - $number_1;

            $data[$number_2] = '';

        }



        $puzzle = implode(",", $data);

        return $puzzle;

     }

    

    /* Programm start*/

    $level=$_GET['level'];    

    if ($level=='') $level=2;

    $test = generate($level);

    $sudoku= explode(",",$test);

    

    /* ARRAY mit Werten fÃ¼llen*/

    $counter=0;

    for ($x=0;$x<9;$x++){

      for ($y=0;$y<9;$y++){

      $_sudoku[$x][$y]=$sudoku[$counter];

      $counter++; 

      }   

    }

$session['pqtemp']=serialize($_sudoku);    

    

output("<div style='position: relative; 

    top: 0pt; 

    left: 0pt; 

    width: 780px; 

    height: 477px;'>",true);

output("<div style='position: absolute; 

    top: 0px; 

    left: 0px;'><img src='./images/sudoku.jpg'></div>",true);

$ausgabe.="<form action='sudoku2.php?op=pruefen' method='post' name='f1'>";

addnav("","sudoku2.php?op=pruefen");



    for ($x=0;$x<9;$x++){

      for ($y=0;$y<9;$y++){

         if ($_sudoku[$x][$y]!=''){

             $color='FFFFFF';

             $style='font-weight:bold;';

             $ausgabe.='<div style="position:absolute;left:'.(28+$y*50).'px ;top: '.(30+$x*50).'px;z-index : 2; ">

              <input name="s['.$x.']['.$y.']" type="hidden" value="'.$_sudoku[$x][$y].'">';   

             $ausgabe.='<font size=+2 color="#'.$color.'"><b>'.$_sudoku[$x][$y].'</b></font>';

             $ausgabe.='</div>';

             }

              else{

             $color='FFFFFF';

             $style='';  

                $ausgabe.='<div style="position:absolute;left:'.(28+$y*50).'px ;top: '.(30+$x*50).'px;z-index : 2; ">

                 <input name="s['.$x.']['.$y.']" type="text" value="'.$_sudoku[$x][$y].'" size="1" maxlength="1" style="text-align : center; color:#'.$color.';'.$style.' zoom:1.1"></div>';   



            }

      }

      

    }

 $ausgabe.='<div style="position:absolute;left:190px ;top:480px;z-index : 2; "><input type="submit" name="s1" value="Sudoku prÃ¼fen"></div>';  

 $ausgabe.='</form>';   

output($ausgabe,true);    

output("</div><div style='    width: 780px;

    margin-left:200px;'>&nbsp;</div>",true);       // kommt von nem anderen Projekt --> copy&paste ;)





    

} // ende IF$op    

elseif($_GET['op']=='pruefen'){ 

output("Sudoku wird geprÃ¼ft....`n");

$fehlerfrei=true; 

// sudoku REIHEN prÃ¼fen

for ($x=0;$x<9;$x++){

    // check array fÃ¼llen

    for ($z=1;$z<=9;$z++){

      $_check[$z]=$z;    

    }

    // array ausstreichen

    for ($y=0;$y<9;$y++){

       $_check[$_POST[s][$x][$y]]='';

    }

    $fehler='';

    if (array_sum($_check)!=0){

        $fehler='Du hast mindestens eine Zahl in der '.($x+1).'. Reihe vergessen, bzw dort kommt eine Zahl doppelt vor!';

        $fehlerfrei=false;

        break;

    }

}

if ($fehler!='') {output("<h2>$fehler</h2>",true);}



// SUDOKU SPALTEN prÃ¼fen

    for ($y=0;$y<9;$y++){

        // check array fÃ¼llen

        for ($z=1;$z<=9;$z++){

          $_check[$z]=$z;    

        }

        // array ausstreichen

        for ($x=0;$x<9;$x++){

           $_check[$_POST[s][$x][$y]]='';

        }

        $fehler='';

        if (array_sum($_check)!=0){

            $fehler='Du hast mindestens eine Zahl in der '.($y+1).'. Spalte vergessen, bzw dort kommt eine Zahl doppelt vor!';

            $fehlerfrei=false;

            break;

        }

    }  // ende spalten Schleife

if ($fehlerfrei){

   $fehler='Gratulation! Du hast das Sudoku erfolgreich gelÃ¶st!';

   if ($session['user']['jail']==true){

      $fehler.=" Dir wird plÃ¶tzlich klar, wie du den Pranger knacken und fliehen kannst!";

      addnav("Fliehen!","jail2.php?op=fliehen");

   }

}

output("<h2>$fehler</h2>",true);





// Spiel auf verbessern vorbereiten

if (!$fehlerfrei){ 

    addnav("Sudoku verbessern","sudoku2.php?op=verbessern");   

    // eingetragene Werte in array schreiben

        for ($x=0;$x<9;$x++){

           for ($y=0;$y<9;$y++){

             $_werte[i][$x][$y] = $_POST[s][$x][$y];

           }

        } 

        $_sudoku=unserialize($session['pqtemp']);

        for ($x=0;$x<9;$x++){

           for ($y=0;$y<9;$y++){

             $_werte[o][$x][$y] = $_sudoku[$x][$y];

           }

        }        

        // werte mit i (input) und o (orginal) in session['pqtemp'] schreiben

        $session['pqtemp']=serialize($_werte);

    

}

}elseif($_GET['op']=='verbessern'){

  // sudoku wieder mit den alten Orginalwerten anzeigen und die inputboxen mit den den eingegebenen werten fÃ¼llen

      $_sudoku=unserialize($session['pqtemp']);

      

output("<div style='position: relative; 

    top: 0pt; 

    left: 0pt; 

    width: 780px; 

    height: 477px;'>",true);       // keine ahnung ob das hier nÃ¶tig ist... es war halz ZWINGEND nÃ¶tig bei einem anderen Projekt, somit c&p

output("<div style='position: absolute; 

    top: 0px; 

    left: 0px;'><img src='./images/sudoku.jpg'></div>",true);

$ausgabe.="<form action='sudoku2.php?op=pruefen' method='post' name='f1'>";

addnav("","sudoku2.php?op=pruefen");      

      for ($x=0;$x<9;$x++){

           for ($y=0;$y<9;$y++){

             if ($_sudoku[o][$x][$y] != ''){

                $color='FFFFFF';

                $style='font-weight:bold;';

                $ausgabe.='<div style="position:absolute;left:'.(28+$y*50).'px ;top: '.(30+$x*50).'px;z-index : 2; ">

                <input name="s['.$x.']['.$y.']" type="hidden" value="'.$_sudoku[o][$x][$y].'">';   

                $ausgabe.='<font size=+2 color="#'.$color.'"><b>'.$_sudoku[o][$x][$y].'</b></font>';

                $ausgabe.='</div>';

             }

             else{ // inputfeld mit value anzeigen

                $color='FFFFFF';

                $style='';  

                $ausgabe.='<div style="position:absolute;left:'.(28+$y*50).'px ;top: '.(30+$x*50).'px;z-index : 2; ">

                 <input name="s['.$x.']['.$y.']" type="text" value="'.$_sudoku[i][$x][$y].'" size="1" maxlength="1" style="text-align : center; color:#'.$color.';'.$style.' zoom:1.1"></div>';   

             }

           }

        }   

 $ausgabe.='<div style="position:absolute;left:190px ;top:480px;z-index : 2; "><input type="submit" name="s1" value="Sudoku erneut prÃ¼fen"></div>';  

 $ausgabe.='</form>';   

output($ausgabe,true);    

output("</div><div style='    width: 780px;

    margin-left:200px;'>&nbsp;</div>",true);       // kommt von nem anderen Projekt --> copy&paste ;)   

 $_sudoku=$_sudoku[o];

 $session['pqtemp']=serialize($_sudoku);

                                 

}elseif($_GET['op']=='regeln'){

output("<h2>Sudokuregelwerk</h2><h3>

Du musst das Rastergitter so ausfÃ¼llen, dass:`n

jede Reihe, `n

jede Spalte, und  `n

alle 3 x 3 Boxen`n

die Zahlen 1 bis 9 beinhalten.`n`n



Die einzugebenen Zahlen sind: `b1, 2, 3, 4, 5, 6, 7, 8, 9`b.

</h3><br>Das wars auch schon mit den Regeln<br><br>

<a href='http://de.wikipedia.org/wiki/Sudoku' target='_blank'><h2>Infos zu Sodoku</h2></a> `n`n

<i><small>written by www.plueschdrache.de</small></i>

",true);

}elseif($_GET['op']=='' && $_GET['level']==''){

  if ($session['user']['jail']>=1){

  output("`7Vielleicht fÃ¤llt dir beim Nachdenken sogar ein Weg hier raus ein?");

}else{

  output("`7Etwas verunsichert was das seltsame Wort `bSudoku`b bedeuten soll gehst du vorsichtig weiter.`n

           Ein alter Mann mit weiÃŸem Bart und einem seltsamen Bambus-Hut begrÃ¼ÃŸt dich:`n

           \"`qSei geglÃ¼st edles Seinsei... Ich abe hiel eine luustige Spielll fÃ¼l dich volbeleitet.`n

               Du kannst hiel deinen Velstand trainielen und elfahlenel welden.`n`n

               In ein paal tausend Jahlen welden es dil deine Nachfahlen danken, sobald ihle Kindel das Wolt Pisa hÃ¶len.`n`n

               Falls du Flagen zum Spiel hast kannst du natÃ¼lich im Legelwelk nachlesen.

               `7\"`n

                Etwas verwirrt beschliesst du erst mal im REGELWERK zu lesen und dann vielleicht ein einfaches \"Sudoku\"-Dinges zu versuchen.`n

         ");

} 

}

    addnav("Sudoku erstellen"); 

   addnav("leicht","sudoku2.php?level=1");

   addnav("mittel","sudoku2.php?level=2");

   addnav("schwer","sudoku2.php?level=3");

   addnav("Regelwerk","sudoku2.php?op=regeln");

   addnav("ZurÃ¼ck"); 

   if ($session['user']['jail']==false){

      addnav("Verlassen",$village);

   } else {

      addnav("Verlassen","jail2.php");

   }



output("<br><br><br><br><br><br><br>",true);

page_footer();    



?>

