<?php

//some changes by Tidus (bugfixes,textchanges) www.kokoto.de
//dont know who done this script, but thx to him =)
if (!isset($session)) exit();
if ($_GET['op']=='weg'){
    output('Du denkst es ist das Beste, sofort von hier zu verschwinden. Das tust du auch und gehst zurück in den Wald.');
    $session['user']['specialinc'] = '';
    }else if ($_GET['op']=='rein'){
    output('Dein Gegner erscheint sofort!');
    $badguy = array(
                    "creaturename"=>"`5 Purpurwurm`0",
                    "creaturelevel"=>15,
                    "creatureweapon"=>"Scharfe Zähne",
                    "creatureattack"=>55,
                    "creaturedefense"=>40,
                    "creaturehealth"=>e_rand(698,789),
                    "diddamage"=>0);
    $session['user']['badguy']=createstring($badguy);
    $session['user']['specialinc']='purpurwurm.php';
    $battle=true;
         }else if ($_GET['op']=='fight') {
    $battle=true;
}else{
   output('`7 Du bleibst vor einem riesigem Erdloch stehen. Du weißt, dass dies das Loch eines Purpurwurms ist und dass er über sagenhafte Schätze verfügen soll. Aber er ist ein harter Gegner und du könntest eventuell dabei draufgehn... also was tust du?');
    addnav('Monsterhöhle betreten','forest.php?op=rein');
    addnav('Schlotternd flüchten','forest.php?op=weg');
    $session['user']['specialinc'] = 'purpurwurm.php';
}
if ($battle) {
    include_once("battle.php");
        $session['user']['specialinc']='purpurwurm.php';
        if ($victory) {
                $badguy=array();
                    $session['user']['badguy']='';
                    output('`9Du hast den `5Purpurwurm `9besiegt!');
                    switch (mt_rand(1,4)){
                    case 1:
                        $gem_gain=mt_rand(5,12);
                        output("Du findest $gem_gain Edelsteine!");
                        $session['user']['gems']+=$gem_gain;
                        addnews("`Q".$session['user']['name']." `Qhat den `5 Purpurwurm `Qbesiegt und `4 $gem_gain `QEdelsteine gefunden!");
                        break;

                    case 2:
                        $gold_gain=mt_rand(4000,7000);
                        output("Du findest $gold_gain Gold!");
                        $session['user']['gold']+=$gold_gain;
                        addnews("`Q".$session['user']['name']." `Qhat den `5 Purpurwurm `Qbesiegt und Schätze im Wert von `4 $gold_gain `Q Gold gefunden!");
                        break;

                     case 3:
                     case 4:
                        switch (mt_rand(1,9)){
                            case 1:
                            case 7:
                            output('Du findest `^Arm des Herodes`0!');
                            $sql="INSERT INTO items (name,class,owner,value1,gold,hvalue,description) VALUES ('`tArm des Herodes','Waffe','".$session['user']['acctid']."','23','23000','23','Ein Schwert mit der Kraft des Feuers.')";
                            db_query($sql);
                            addnews("`Q".$session['user']['name'].' `Qhat den `5 Purpurwurm `Qbesiegt und `^Arm des Herodes`Q gefunden!');
                            break;

                            case 2:
                            case 6:
                            output('Du findest `^Plattenpanzer`0!');
                            $sql="INSERT INTO items (name,class,owner,value1,gold,hvalue,description) VALUES ('`QPlattenpanzer','Rüstung','".$session['user']['acctid']."','28','28000','28','Ein Panzer mit vielen Platten.')";
                            db_query($sql);
                            addnews("`Q".$session['user']['name'].' `Qhat den `5 Purpurwurm `Qbesiegt und `^Plattenpanzer`Q gefunden!');
                            break;

                            case 3:
                            case 8:
                            output('Du findest `^Zweihand-Hammer`0!');
                            $sql="INSERT INTO items (name,class,owner,value1,gold,hvalue,description) VALUES ('`!Zweihand-Hammer','Waffe','".$session['user']['acctid']."','36','36000','36','Ein sehr schwerer Hammer.')";
                            db_query($sql);
                            addnews("`Q".$session['user']['name'].' `Qhat den `5 Purpurwurm `Qbesiegt und `^Zweihand-Hammer`Q gefunden!');
                            break;

                            case 4:
                            output('Du findest `^Schwert des Achilles`0!');
                            $sql="INSERT INTO items (name,class,owner,value1,gold,hvalue,description) VALUES ('`^Schwert des Achilles','Waffe','".$session['user']['acctid']."','50','50000','52','Ein Schwert mit unglaublicher Kraft')";
                            db_query($sql);
                            addnews("`Q".$session['user']['name'].' `Qhat den `5 Purpurwurm `Qbesiegt und `^Schwert des Achilles`Q gefunden!');
                            break;

                            case 5:
                            case 9:
                            output("Du findest `^Sternenrüstung`0!");
                            $sql="INSERT INTO items (name,class,owner,value1,gold,hvalue,description) VALUES ('`^Sternenrüstung','Rüstung','".$session['user']['acctid']."','50','50000','50','Eine Rüstung mit unglaublicher Kraft')";
                            db_query($sql);
                            addnews("`Q".$session['user']['name'].' `Qhat den `5 Purpurwurm `Qbesiegt und `^Sternenrüstung`Q gefunden!');
                            break;
                         }

                       break;

                     }
                    $exp_gain=($session['user']['experience']0.1);
                    output("`@Du bekommst $exp_gain Erfahrungspunkte.");
                    $session['user']['experience']+=$exp_gain;
                    addnav('Zurück in den Wald','forest.php');
                    $session['user']['specialinc']='';
        }elseif ($defeat) {
               $badguy=array();
               $session['user']['badguy']='';
               output('Der Purpurwurm hat dich geschlagen!!`n');
               output('`$Du bist tot.`n');
               if($session['user']['gems']>=10) output('`$Du verlierst 5% deiner Erfahrung, all dein Gold und 10 deiner Edelsteine auch!`n');
               else output('`$Du verlierst 5% deiner Erfahrung, all dein Gold und alle deine Edelsteine auch!`n');
               output('`$Du kannst morgen wieder spielen.');
               $session['user']['alive']=false;
               $session['user']['gold']=0;
               if($session['user']['gems']>=10) $session['user']['gems']-=10;
               else $session['user']['gems']=0;
               $session['user']['experience']*=0.95;
               addnav('Tägliche News','news.php');
               addnews($session['user']['name'].'`Q wollte sich mit dem `5 Purpurwurm`0 `Qanlegen.`$ Schwerer Fehler...`0');
               $session['user']['specialinc']='';
           }
               else{
                fightnav(true,false);
    }
    }
?>
