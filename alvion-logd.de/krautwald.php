
<?php



/*

*****************************************************************************************************

*                                                                                                    *

*                                    Jobystem Beta 0.1                                                *

*     Autor    : Aramus/Taikun                                                                            *

*     Kontakt    : redwing13@web.de                                                                        *

*                                                                                                    *

*                                                                                                    *

*        Dieses Script darf ohne Einverständniserklärung, nicht von anderen Usern benutzt werden.    * 

*        Sollte solch ein Vergehen festgestellt werden, werden gerichtliche Konsequenzen folgen.        *

*                                                                                                    *

* Copyright © by Benjamin Rouzieres 2009                                                        *

*                                                                                                    *

*****************************************************************************************************

*/



require_once "common.php";

require_once "classes.berufe.php";





    page_header("Kräuterwald");



//Define: Zufallszahl zum Finden der Kräuter.

$find=e_rand(1,10);





    $res=db_query('SELECT charid, skill, berufid FROM berufe');

    $row=db_fetch_assoc($res);

        

        



//Anfang: IF Überprüfung ob User Kräuterberuf hat - wenn ja + Chance auf "Drop".

        if($session['user']['acctid']==$row['charid'] && $row['berufid']==18 && find==7){

            

                    

            $res1=db_query("SELECT kraut, skill FROM kraut");

            $row1=db_fetch_assoc($res1);



//Define: Zufällige Kräuter aus der Liste auswählen und den User bei vorhandenem Skill finden lassen.





do    {

    while ($row1 = db_fetch_assoc($res1))        {

                    

                    

        $kraut[] = $row1['kraut']." ".$row1['skill'];        

        $a = $kraut[array_rand($kraut)];    

        

//END: While-Schleife 1

        }

        



            $ab = explode(" ", $a);



//END: Do-Schleife

    } 

    

                while ($res1 < 15);



                    

                    for ($i=0; $i<$row['skill']; $i++)        {

                    if($i==$row['skill'])                {

                    break;

//END: IF                

                }

//END: For

            }



            

                        $krt1 = $ab[0];

                        $skll1 = $ab[1];

//Anfang: IF Skillvergleich                

                            if($i >= $skll1){

        

        

                                $res2=db_query('SELECT * FROM kraut_besitzer');

                                $row2=db_fetch_assoc($res2);

        

        

//DEFINE: Kräuter dem Besitzer zuweisen.

//Anfang: Besitzervergleich der Kräuter    

if($session['user']['acctid'] != $row2['charid'] OR $krt1 != $row2['kraut'])    {

    

            

    db_query("INSERT INTO kraut_besitzer (charid,kraut,skill, anzahl) VALUES ('".$session['user']['acctid']."','".$krt1."','".$skll1."','1')");

    

            

        output("Du findest ".$krt1."!");





//DEFINE: Anfang Klasse Skillpunkte.    

//Anfang: IF Skillfertigkeit    

if ($row['skill'] <350)        {

    



    $objekt = new skillpunkt;

    $objekt->upskill(skill,$session['user']['acctid']);

        

    

        $res=db_query('SELECT charid, skill, berufid FROM berufe');

        $row=db_fetch_assoc($res);



        

            output("Dein Skill hat sich auf ".$row['skill']." erhöht.");

            

//END: IF Skillfertigkeit            

        }



//END: Besitzervergleich der Kräuter

    } 

                else             {

    

       

                    db_query("UPDATE kraut_besitzer SET anzahl = anzahl +1 WHERE charid='{$session['user']['acctid']}' && kraut='{$krt1}'");

        

                    

                        output("Du findest ".$krt1."!");

                        

                         if ($row['skill'] <350)        {

                            $objekt = new skillpunkt;

                            $objekt->upskill(skill,$session['user']['acctid']);

        

    

                                $res=db_query('SELECT charid, skill, berufid FROM berufe');

                                $row=db_fetch_assoc($res);



        

                                    output("Dein Skill hat sich auf ".$row['skill']." erhöht.");

                                }



//END: Else                                                                            

            }

    

//END: IF Skillvergleich                    

        }



//END: IF Überprüfung ob User Kräuterberuf hat - wenn ja + Chance auf "Drop".        

}    

    



//Abschluss



    addnav("Zurück","testseite.php");

    addnav("Zur Grotte","superuser.php");

    

    

    page_footer();

        

        

?>

