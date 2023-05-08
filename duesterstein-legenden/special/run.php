
<?
//run.php
//idea and written by aska    
//
//V1.2_ger only mounts
//remove or comment all lines with the commtent "customized to rabenthal.de commment or modify if used elsewhere" if you're not using the rabenthal.de enviroment
if ($HTTP_GET_VARS[op]=="ride"){
        $buff = unserialize($playermount['mountbuff']);
        $enmount = (e_rand(0,14)*$buff['rounds']/10);         
        switch($playermount['mountcategory']){    //customized to rabenthal.de commment or modify if used elsewhere
            case Haustiere:                //customized to rabenthal.de commment or modify if used elsewhere
            output("`2Du schwingst dich auf dein ".$playermount['mountname']." und willst losreiten als das arme Tier unter dir zusammenbricht. Beschämt machst du dich wieder auf den Weg, dein ".$playermount['mountname']." hinter dir herziehend.");//customized to rabenthal.de commment or modify if used elsewhere)
            break;
            default://customized to rabenthal.de commment or modify if used elsewhere
            if ($session['bufflist']['mount']['rounds']>$enmount){
            output("`2Du holst den Reiter noch vor dem Dorf ein und kommst auch wieder als ersters zurück. Der Reiter und sein schwarzes Ross kommen erst ein paar Sekunden später an. `Q\"Wahrlich, ein schnelles Tier habt ihr da. Nimmt dies.\"`2`n Du erhälst ");
            switch(e_rand(1,5)){
                case 1:
                case 2:  
                    $gold = e_rand($session[user][level]*50,$session[user][level]*80);
                    $session[user][gold]+=$gold;
                    output("`^".$gold." `2Gold");
                    break;
                case 3:
                case 4:    
                    $session[user][gems]+=2;
                    output("`^2 `2Edelsteine");
                    break;
                case 5:
                    $gold = e_rand($session[user][level]*35,$session[user][level]*80);
                    $session[user][gold]+=$gold;
                    $session[user][gems]+=1;
                    output("`^einen`2 Edelstein und `^".$gold." `2Gold");
                    break;
            }
        }
        else{
            output("`2Du treibst dein Tier an und verfolgst den Reiter und sein Ross. Trotz ein paar Überholversuche schaffst du es nicht in Führung zu gehen. Er kommt als ersters an und meint `Q\"Ich würde sagen, ich habe gewonnen.\"`2 `nDer Mann nimmt dir ");
            if($$session[user][gold]==0 && $session[user][gems]==0){
                output("`^ nichts`2. Er hat Mitleid mit dir und lässt dich ziehen. Reicher macht dich dass auch nicht...");
            }else{
            switch(e_rand(1,5)){
                case 1:
                case 2:  
                    $session[user][gold]=(int)($session[user][gold]/2);
                    output("`^die Hälfte deines Goldes`2.");
                    break;
                case 3:
                case 4:    
                if($session[user][gems]<0){
                    $session[user][gold]=(int)($session[user][gold]/2);
                    output("`^die Hälfte deines Goldes`2.");
                    break;
                }
                else if($session[user][gems]==1){
                    $session[user][gems]--;
                    output("`^deinen letzten Edelstein`2.");
                    break;
                }
                else if($session[user][gems]>0){
                    $session[user][gems]=(int)($session[user][gems]/2);
                    output("`^die Hälfte deiner Edelsteine`2.");
                    break;
                }
                case 5:
                    if($session[user][gems]>0){
                        $session[user][gold]=(int)($session[user][gold]/2);
                        $session[user][gems]=(int)($session[user][gems]/2);
                        output("`^die Hälfte deiner Edelsteine und deines Gold`2.");
                    }
                    else{
                        $session[user][gold]=0;
                        output("`^alle dein Gold`2.");
                    }
        
                    break;
            }
            output(" `nWütend über deinen Verlust verpasst du deinem Tier einen Klaps und gehst weiter.");
            }
            
        }
            $session['bufflist']['mount']['rounds']=$session['bufflist']['mount']['rounds'] - (int)($session['bufflist']['mount']['rounds']*(e_rand(10,30)/100));
            output("`nVon dem Rennen ist dein ".$playermount['mountname']." erschöpft und verliert an Kraft.");
            break;//customized to rabenthal.de commment or modify if used elsewhere
    }//customized to rabenthal.de commment or modify if used elsewhere
}
else if($HTTP_GET_VARS[op]=="ignore"){  
        $session[user][specialinc]="";
        output("`2Du drehst dich um und gehst....Das Vieh war bestimmt gedopt.");
}
else if($HTTP_GET_VARS[op]==""){
    if($session['user']['hashorse']>0){
        if($session['bufflist']['mount']['rounds']==0){
            output("`2Ein Mann taucht auf seinem schwarzen Pferd neben dir auf. `Q\"Wie wärs mit einem Rennen?\"`2 fragt er dich und braust schon davon. Du versuchst dein/en ".$playermount['mountname']." anzutreiben doch das Tier ist für heute schon zu erschöpft für ein Rennen. Du hörst noch ein irres Lachen aus der Richtung in die der Reiter abgedüst ist, kümmerst dich jedoch nicht weiter darum.");
        }
        else{
            if($playermount['mountname']=="Drache" || $playermount['mountname']=="Grisu"){//customized to rabenthal.de commment or modify if used elsewhere
                output("`2Ein Mann taucht auf seinem schwarzen Pferd neben dir auf. `Q\"Wie wärs mit einen Ren....\" `2fängt er an kommt aber nicht weiter weil dein Drache ihn mitsamt Pferd schnell auffrisst. Du tätschelst das Tier kurz und fliegst dann mit einen satten Drachen weiter.");//customized to rabenthal.de commment or modify if used elsewhere
                $buff = unserialize($playermount['mountbuff']);//customized to rabenthal.de commment or modify if used elsewhere
                $session['bufflist']['mount']=$buff;//customized to rabenthal.de commment or modify if used elsewhere
                output("`n`n`gDein Drache ist wieder komplett erholt");//customized to rabenthal.de commment or modify if used elsewhere
            }//customized to rabenthal.de commment or modify if used elsewhere
            else{//customized to rabenthal.de commment or modify if used elsewhere
                output("`2Ein Mann taucht auf seinem schwarzen Pferd neben dir auf. `Q\"Wie wärs mit einem Rennen? Bis zum Dorf und zurück?\"`2 fragt er dich und braust schon davon. Du überlegst ob du deinen Tier diese Strapazen antuen willst.");
                addnav("Reiten","forest.php?op=ride"); 
                addnav("Ignorieren","forest.php?op=ignore"); 
                $session[user][specialinc]="run.php";
            }//customized to rabenthal.de commment or modify if used elsewhere
        }
    }
    else{
        output("`2Ein Mann reitet auf seinem schwarzen Pferd an dir vorbei. Was würdest du dafür geben auf so ein Tier zu haben...");
    }
}
?>


