
<?php
/*
mazemonster.php part of the Abandonded Castle Mod By Lonnyl @ http://www.pqcomp.com/logd
Author Lonnyl
version 1.01
June 2004
*/

// MOD by tcb, 15.5.05: Monster kosten WK
//MOD by Alucard, 14.02.06: Anpassung an Irrgarten
require_once "common.php";
checkday();
page_header("Labyrinth-Monster");

if ($_GET['fiend']=="lost_soul"){
        $badguy = array(        "creaturename"=>"`@Verlorene Seele`0"
        ,"creaturelevel"=>5
        ,"creatureweapon"=>"Geisterkraft"
        ,"creatureattack"=>10
        ,"creaturedefense"=>15
        ,"creaturehealth"=>200
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*2);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="devoured_soul"){
        $badguy = array(        "creaturename"=>"`@Verdorrte Seele`0"
        ,"creaturelevel"=>10
        ,"creatureweapon"=>"Seelendurst"
        ,"creatureattack"=>15
        ,"creaturedefense"=>15
        ,"creaturehealth"=>300
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*2);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="ghost1"){
        $badguy = array(        "creaturename"=>"`@Durchsichtiges Spektre`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Geisterkraft"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>1000
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*2);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="ghost2"){
        $badguy = array(        "creaturename"=>"`@Wütendes Spektre`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Geisterkraft"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>400
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*1.5);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="ghost3"){
        $badguy = array(        "creaturename"=>"`@erbostes Spektre`0"
        ,"creaturelevel"=>10
        ,"creatureweapon"=>"Geisterkraft"
        ,"creatureattack"=>5
        ,"creaturedefense"=>20
        ,"creaturehealth"=>400
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*1.5);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="bat"){
        $badguy = array(        "creaturename"=>"`@Fledermaus`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Scharfe Zähne"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>1
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=($userhealth*.5);
        $badguy['creaturedefense']+=($userdefense*.5);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="bigbat"){
        $badguy = array(        "creaturename"=>"`@Riesige Fledermaus`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Scharfe Zähne"
        ,"creatureattack"=>3
        ,"creaturedefense"=>5
        ,"creaturehealth"=>40
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=($userhealth*.5);
        $badguy['creaturedefense']+=($userdefense*.5);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="rat"){
        $badguy = array(        "creaturename"=>"`@Riesige Ratte`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Scharfe Zähne"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>1
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.75);
        $badguy['creaturehealth']+=($userhealth*.75);
        $badguy['creaturedefense']+=($userdefense*.75);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="minotaur"){
        $badguy = array(        "creaturename"=>"`@Minotaurus`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Hörner"
        ,"creatureattack"=>1
        ,"creaturedefense"=>40
        ,"creaturehealth"=>1000
        ,"diddamage"=>0
        ,"boss"=>true);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack-4);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
}

//welche für irrgarten
if ($_GET['fiend']=="bigspider"){
        $badguy = array(        "creaturename"=>"`^Riesige Spinne`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Spinnengift und klebrige Fäden"
        ,"creatureattack"=>3
        ,"creaturedefense"=>5
        ,"creaturehealth"=>40
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=($userhealth*.5);
        $badguy['creaturedefense']+=($userdefense*.5);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="zyklop"){
        $badguy = array(        "creaturename"=>"`^Zyklop`0"
        ,"creaturelevel"=>10
        ,"creatureweapon"=>"Stachelkeule"
        ,"creatureattack"=>15
        ,"creaturedefense"=>15
        ,"creaturehealth"=>300
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/2);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.5);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*2);
        $session['user']['badguy']=createstring($badguy);
}
if ($_GET['fiend']=="gardner"){
        $badguy = array(        "creaturename"=>"`^irrer Gärtner`0"
        ,"creaturelevel"=>10
        ,"creatureweapon"=>"blutige Heckenschere"
        ,"creatureattack"=>5
        ,"creaturedefense"=>20
        ,"creaturehealth"=>400
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(1,3);
        $userhealth=round($session['user']['hitpoints']/1.5);
        $userdefense=$session['user']['defense']+e_rand(1,3);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack*.7);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=($userdefense*1.7);
        $session['user']['badguy']=createstring($badguy);
}

if ($_GET['fiend']=="snakegod"){
        $badguy = array(        "creaturename"=>"`@Wadjet Schlangengöttin`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Giftzähne"
        ,"creatureattack"=>1
        ,"creaturedefense"=>40
        ,"creaturehealth"=>1000
        ,"diddamage"=>0);


        $userattack=$session['user']['attack']+e_rand(2,5);
        $userhealth=round($session['user']['hitpoints']/1.25);
        $userdefense=$session['user']['defense']+e_rand(1,4);
        $badguy['creaturelevel']=$session['user']['level'];
        $badguy['creatureattack']+=($userattack-4);
        $badguy['creaturehealth']+=$userhealth;
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
}


if ($_GET['op']=="fight" or $_GET['op']=="run"){
        $battle=true;
        $fight=true;
        $maze_type = empty($session['user']['maze_visited']) ? 0 : 1; //0=schloss, 1=irrgarten
        
        
        if ($battle){
                include_once ("battle.php");

                if ($victory){

                        $session['user']['turns'] = max($session['user']['turns']-1,0);

                        output("`b`4Du hast `^".$badguy['creaturename']."`4besiegt.`b`n");
                        $badguy=array();
                        $session['user']['badguy']="";
                        $session['user']['specialinc']="";
                        $gold=e_rand(100,500);
                        $experience=$session['user']['level']*e_rand(37,80);
                        output("`#erhälst `6".$gold." `#Gold!`n");
                        $session['user']['gold']+=$gold;
                        output("`#Du erhälst `6".$experience." `#Erfahrung!`n");
                        $session['user']['experience']+=$experience;
                        
                        if( !$maze_type ){
                                addnav("Weiter","abandoncastle.php?loc=".$session['user']['pqtemp']);
                        }
                        else{
                                addnav("Weiter","gardenmaze.php?pos=".$session['user']['pqtemp']);
                        }

                        if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
                // Knappe nicht vergessen!
                if (is_array($session['bufflist']['decbuff']))
                {
                  $decbuff=$session['bufflist']['decbuff'];
                }
                                // Edelsteinelsterbuff
                                if ($session['bufflist']['gemelster'])
                                {
                                        $arr_gemelster_buff = $session['bufflist']['gemelster'];
                                }
                $_GET['skill']="";
                //                if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
                                $session['bufflist']=array();
                                if (is_array($decbuff))
                {
                  $session['bufflist']['decbuff']=$decbuff;
                }
                                if (is_array($arr_gemelster_buff))
                                {
                                        $session['bufflist']['gemelster'] = $arr_gemelster_buff;
                                }
                        }

                }elseif ($defeat){
                        output("`&Als Du auf dem Boden aufschlägst, rennt `^".$badguy['creaturename']."");

                        $sql = "SELECT name,state FROM disciples WHERE master=".$session['user']['acctid']."";
                        $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0){
                        $rowk = db_fetch_assoc($result);}
                        $kname=$rowk['name'];
                        $kstate=$rowk['state'];
                        
                        if (($kstate>0) && ($kstate<20)) { 
                                output(" `&mit `^".$kname." `&");
                                debuglog("Verlor einen Knappen bei einer Niederlage im Schloss/Irrgarten.");
                        }
                        output("`& weg.");

                        addnews("`%".$session['user']['name']."`5 wurde von ".$badguy['creaturename']." `5im ".($maze_type? "Irrgarten des verlassenen Schlosses" : "Verlassenen Schloss")." erschlagen.");
                        $badguy=array();
                        //$session['user']['badguy']="";
                        //$session['user']['hitpoints']=0;
                        //$session['user']['alive']=0;
                        killplayer(0,0,1,"");
                        $session['user']['specialinc']="";
                        addnav("Weiter","shades.php");
                }else{
                        if ($fight){
                                fightnav(true,false);
                                if ($badguy['creaturehealth'] > 0){
                                        $hp=$badguy['creaturehealth'];
                                }
                        }
                }
        }else{
                
                if( !$maze_type ){
                        redirect("abandoncastle.php?loc=".$session['user']['pqtemp']);
                }
                else{
                        redirect("gardenmaze.php?pos=".$session['user']['pqtemp']);
                }
        }
}
//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Abandonded Castle by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>

