
<?php



// Klassen



// Skillpunkte

require_once "common.php";

class skillpunkt        {

    

    

        public function upskill($typ,$user)    {

    

    

    

db_query('UPDATE `berufe` SET `'.$typ.'`=`'.$typ.'`+1 WHERE `charid`='.$user.'');

                



        }

}



// Ende Skillpunkte

?>

