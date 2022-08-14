
<?php


/*

* Kampfsystem V 0.1. Scripted by Aramus. Angepasst für Eassos.

* Datenbank: 0/100 %
* Funktionen: 3/100 %



*/ 



require_once "common.php";
/*
* Klasse Gegner
*/

class getFight{    
/*
* Attribute setzen
* public
*/
    public $spieler;
    public $lifepoints;
    public $angriff;
    public $verteidigung;
    public $nameEnemy;
    public $levelEnemy;
    public $lifepointsEnemy;
    public $angriffEnemy;
    public $verteidigungEnemy;    
    public $multiplikatorPlayer;
    public $critPlayer;
    public $berCritHit;
    public $newPlayerCit;
    public $criticalHit;
// * Enemy Daten holen - Ausweiten auf Datenbank    
        public function getEnemyStats()
    {
        
        $this->nameEnemy = Testname;
        $this->levelenemy = 1;
        $this->lifepointsEnemy = 100;
        $this->angriffEnemy = 1;
        $this->verteidigungEnemy = 1;
        
        $multiplaktorAngriff = 2;
        $multiplaktorDef     = 2;
        
        
        $this->angriffEnemyNew = ($this->angriffEnemy + $this->levelenemy )* $multiplaktorAngriff; 
        $this->verteidigungEnemyNew = ($this->verteidigungEnemy + $this->levelenemy )* $multiplaktorDef; 
                
    }

/*
* Spieler Daten holen
@PARAM $name - Name des User
@PARAM $hitpoints - Lebenspunkte des User
@PARAM $attack - Attack des User
@PARAM $defence - Verteidigung des User
@PARAM $critPlayer
@PARAM $abhärtung
@PARAM 
@PARAM
@PARAM
@PARAM
*/

    
        public function getSpielerStats($name,$hitpoints,$attack,$defence)
    {
        
            
        $this->spieler = $name;
        $this->lifepoints = $hitpoints;
        $this->angriff = $attack;
        $this->verteidigung = $defence;
        $this->multiplikatorPlayer = 20;
        $this->critPlayer = 50;
    }
    
// Funktion zur Berechnung der Critchance. Critchance wird prozentual durch Verteidigung/Abhärtung verringert
// 100 Verteidigung / 4 = Critreduce. Immer durch Faktor 4


    public function getSpielerCrit($crit)
    {
        global $session;
        //Critchance in Bezug auf Verteidung
        $this->berCritHit = $this->verteidigungEnemyNew / 4;
        $this->newPlayerCrit = $crit - $this->berCritHit;
    
        srand ((double)microtime () * 1000000 );
        $chance = rand(1, 100);
        if ($chance <= $this->newPlayerCrit) {
            $this->criticalHit = 2;
           } else {
    $this->criticalHit = 1;
    
            }
        
        
        
    }

// Kampfnavigation setzen. Include in Kampfdatei
    public function kampfnav()
    
    {
      global $PHP_SELF,$session;
    $script = substr($PHP_SELF,strrpos($PHP_SELF,"/")+1);
    addnav("Kämpfen","$script?op=test");

    }

// Der Kampf    
    public function enemyFight()
    {
        
        global $session, $lifepointsEnemy;
        

    if ($session['user']['hitpoints']>0 &&
            $this->lifepointsEnemy>0 &&
            $_GET['op']=="test"){    
        
                
        // Enemy Berechnung         
        $this->berEnemyHit = $this->angriffEnemyNew - $this->verteidigung;
        $this->lifepointsNew = $this->lifepoints - $this->berEnemyHit;
        $session['user']['hitpoints'] = $this->lifepointsNew;
        
        // Player Berechnung
        $this->berPlayerHit = $this->angriff * $this->multiplikatorPlayer  * $this->criticalHit;
        $this->lifepointsEnemy = $this->lifepointsEnemy - $this->berPlayerHit;
        
        
        
        
        
        output("".$this->nameEnemy."s Schlag trifft dich mit ".$this->berEnemyHit." Schadenspunkte. Du hast jetzt noch ".$this->lifepointsNew." ");
        output(" CRIT-".$this->criticalHit."-CRIT ".$this->spieler."s Schlag trifft ".$this->nameEnemy." mit ".$this->berPlayerHit." Schadenspunkte. ".$this->nameEnemy." hat jetzt noch ".$this->lifepointsEnemy." ");
    }


}
    
}





?>

