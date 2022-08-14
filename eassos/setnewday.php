
<?php

// 11092004

/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net

translation: anpera
*/

if($session['user']['jailtime'] > 0)
    $session['user']['jailtime']--;
if($session['user']['prisondays'] > 0)
    $session['user']['prisondays']--;
    
db_query('UPDATE accounts SET jailtime = jailtime-1 WHERE jailtime > 0');
db_query('UPDATE accounts SET prisondays = prisondays-1 WHERE prisondays > 1');

if ((int)getsetting("expirecontent",180)>0){
    $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("c")."-".getsetting("expirecontent",180)." days"))."'";
    db_query($sql);
    //if(db_affected_rows() > 0)
        //global_log('Es wurden `b'.db_affected_rows().' Posts`b gelöscht!', 'setnewday.php');
    
    $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime(date("c")."-".getsetting("expirecontent",180)." days"))."'";
    db_query($sql);
    //if(db_affected_rows() > 0)
        //global_log('Es wurden `b'.db_affected_rows().' Newseinträge`b gelöscht!', 'setnewday.php');
}

$result = db_query("SELECT COUNT(*) c FROM commentary WHERE section = 'ooc'") or die(db_error(LINK));
$row = db_fetch_assoc($result);
$limit = $row['c'] - 1000;
db_query("DELETE FROM commentary WHERE section = 'ooc' ORDER BY postdate ASC LIMIT " . ($limit < 0 ? 0 : $limit)) or die(db_error(LINK));
global_log("$limit comments in OOC chat deleted.", "setnewday.php");

//if(db_affected_rows() > 0)
    //global_log('Es wurden `b'.db_affected_rows().' YOMs`b gelöscht!', 'setnewday.php');

        //Wetter
        $datum_day = substr(getgamedate(),0,2);
        $datum_month = substr(getgamedate(),3,2);
        $datum = $datum_month.".".$datum_day.".";
        
        if(getgametime() >= "00:00" AND getgametime() < "03:00"){
            $daytime = 1;
        }else if(getgametime() >= "03:00" AND getgametime() < "06:00"){
            $daytime = 2;
        }else if(getgametime() >= "06:00" AND getgametime() < "09:00"){
            $daytime = 3;
        }else if(getgametime() >= "09:00" AND getgametime() < "12:00"){
            $daytime = 4;
        }else if(getgametime() >= "12:00" AND getgametime() < "15:00"){
            $daytime = 5;
        }else if(getgametime() >= "15:00" AND getgametime() < "18:00"){
            $daytime = 6;
        }else if(getgametime() >= "18:00" AND getgametime() < "21:00"){
            $daytime = 7;
        }else if(getgametime() >= "21:00" AND getgametime() < "24:00"){
            $daytime = 8;
        }
        
        if($datum >= "01.01." AND $datum < "03.20."){
            $season = 4;
        }else if($datum >= "03.20." AND $datum < "06.21."){
            $season = 1;
        }else if($datum >= "06.21." AND $datum < "09.22."){
            $season = 2;
        }else if($datum >= "09.22." AND $datum < "12.21."){
            $season = 3;
        }else if($datum >= "12.21." AND $datum >= "12.31."){
            $season = 4;
        }
        
        if($datum >= "01.01." AND $datum < "03.20."){
            switch($daytime){
                case 1:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 2:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 3:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 4:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 5:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 6:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 7:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 8:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
            }
        }else if($datum >= "03.20." AND $datum < "06.21."){
            switch($daytime){
                case 1:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 2:
                            $clouds = "Neblig";
                        break;
                  
                        case 3:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 4:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 5:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 6:
                            $clouds = "Hagel";
                        break;
                    }
                break;

                case 2:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 2:
                            $clouds = "Neblig";
                        break;
                  
                        case 3:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 4:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 5:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 6:
                            $clouds = "Hagel";
                        break;
                    }
                break;

                case 3:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Wechselhaft und kühl, mit sonnigen Abschnitten";
                        break;
                  
                        case 2:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 3:
                            $clouds = "Neblig";
                        break;
                  
                        case 4:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 5:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Sonnig aber kühl";
                        break;
                  
                        case 7:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                    }
                break;
                
                case 4:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Wechselhaft und kühl, mit sonnigen Abschnitten";
                        break;
                  
                        case 2:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 3:
                            $clouds = "Neblig";
                        break;
                  
                        case 4:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 5:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Sonnig aber kühl";
                        break;
                  
                        case 7:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                    }
                break;

                case 5:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Wechselhaft und kühl, mit sonnigen Abschnitten";
                        break;
                  
                        case 2:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 3:
                            $clouds = "Neblig";
                        break;
                  
                        case 4:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 5:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Sonnig aber kühl";
                        break;
                  
                        case 7:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                    }
                break;
                
                case 6:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Wechselhaft und kühl, mit sonnigen Abschnitten";
                        break;
                  
                        case 2:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 3:
                            $clouds = "Neblig";
                        break;
                  
                        case 4:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 5:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Sonnig aber kühl";
                        break;
                  
                        case 7:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                    }
                break;

                case 7:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 2:
                            $clouds = "Neblig";
                        break;
                  
                        case 3:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 4:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 5:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 6:
                            $clouds = "Hagel";
                        break;
                    }
                break;

                case 8:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Regnerisch";
                        break;
                  
                        case 2:
                            $clouds = "Neblig";
                        break;
                  
                        case 3:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 4:
                            $clouds = "Kalt bei klarem Himmel";
                        break;
                  
                        case 5:
                            $clouds = "Leichter Niesel";
                        break;
                  
                        case 6:
                            $clouds = "Hagel";
                        break;
                    }
                break;
            }
        }else if($datum >= "06.21." AND $datum < "09.22."){
            switch($daytime){
                case 1:
                    switch(e_rand(1,4)){
                        case 1:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 2:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 3:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 4:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
                
                case 2:
                    switch(e_rand(1,4)){
                        case 1:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 2:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 3:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 4:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 3:
                    switch(e_rand(1,5)){
                        case 1:
                            $clouds = "Warm und sonnig";
                        break;
                  
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
              
                        case 5:
                            $clouds = "Taufrischer Morgenwind";
                        break;
                    }
                break;

                case 4:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Warm und sonnig";
                        break;
                  
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 5:
                            $clouds = "Leuchtend blauer Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Strahlender Sonnenschein";  
                        break;
                    }
                break;

                case 5:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Warm und sonnig";
                        break;
                  
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 5:
                            $clouds = "Leuchtend blauer Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Strahlender Sonnenschein";  
                        break;
                    }
                break;
                
                case 6:
                    switch(e_rand(1,6)){
                        case 1:
                            $clouds = "Warm und sonnig";
                        break;
                  
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 5:
                            $clouds = "Leuchtend blauer Himmel";
                        break;
                  
                        case 6:
                            $clouds = "Strahlender Sonnenschein";  
                        break;
                    }
                break;
                
                case 7:
                    switch(e_rand(1,4)){
                        case 1:
                            $clouds = "angenehm Warm";
                        break;
                  
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                    }
                break;

                case 8:
                    switch(e_rand(1,7)){
                        case 2:
                            $clouds = "Drückend heiß und schwül";
                        break;
                  
                        case 3:
                            $clouds = "Warmer Schauer";
                        break;
                  
                        case 4:
                            $clouds = "Föhn aus dem Gebirge";
                        break;
                  
                        case 5:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
            }
        }else if($datum >= "09.22." AND $datum < "12.21."){
            switch($daytime){
                case 1:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 2:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
                
                case 3:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "Sonnig";
                        break;
                    }
                break;
                
                case 4:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "Sonnig";
                        break;
                    }
                break;

                case 5:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "Sonnig";
                        break;
                    }
                break;

                case 6:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "Sonnig";
                        break;
                    }
                break;

                case 7:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 8:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Wolkenlos und windig";
                        break; 
                  
                        case 2:
                            $clouds = "Stürmisch mit vereinzelten Regenschauern";
                        break;
                  
                        case 3:
                            $clouds = "Orkanartige Böen";
                        break;
                  
                        case 4:
                            $clouds = "Nasser, dichter Nebel";
                        break;
                  
                        case 5:
                            $clouds = "Regen und fallendes Laub";
                        break;
                  
                        case 6:
                            $clouds = "Bedeckt";
                        break;
                  
                        case 7:
                            $clouds = "Gewittersturm";
                        break;
                  
                        case 8:
                            $clouds = "Stürmisch";
                        break;
                        
                        case 9:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
            }
        }else if($datum >= "12.21." AND $datum >= "12.31."){
            switch($daytime){
                case 1:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 2:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 3:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 4:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 5:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 6:
                    switch(e_rand(1,9)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Kalt und bedeckt, mit einzelnen Sonnestrahlen";
                        break;
                  
                        case 5:
                            $clouds = "Blizzard";
                        break;
                  
                        case 6:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 7:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 8:
                            $clouds = "Hagel";
                        break;
                  
                        case 9:
                            $clouds = "Heiter";
                        break;
                    }
                break;

                case 7:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;

                case 8:
                    switch(e_rand(1,8)){
                        case 1:
                            $clouds = "Frostig";
                        break;
                  
                        case 2:
                            $clouds = "Kalter Nebel und gefrorener Tau";
                        break;
                  
                        case 3:
                            $clouds = "Kühl und bedeckt";
                        break;
                  
                        case 4:
                            $clouds = "Blizzard";
                        break;
                  
                        case 5:
                            $clouds = "Schneeregen";
                        break;
                  
                        case 6:
                            $clouds = "Malerischer Schneefall";
                        break;
                  
                        case 7:
                            $clouds = "Hagel";
                        break;
                  
                        case 8:
                            $clouds = "klarer Himmel";
                        break;
                    }
                break;
            }
        }

savesetting("weather",$clouds);

// Vendor in town?
if (e_rand(1,3)==1){
    savesetting("vendor","1");
}else{
    savesetting("vendor","0");
}

// Other hidden paths
$spec="Keines";
$what=e_rand(1,3);
if ($what==1) $spec="Waldsee";
if ($what==3) $spec="Orkburg";
savesetting("dailyspecial","$spec");

// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1) {
    $date = getsetting('gamedate','0000-01-01');
    $date = explode('-',$date);
    $date[2]++;
    switch ($date[2]) {
        case 32:
            $date[2] = 1;
            $date[1]++;
            break;
        case 31:
            if (in_array($date[1], array(4,6,9,11))) {
                $date[2] = 1;
                $date[1]++;
            }
            break;
        case 30:
            if ($date[1]==2) {
                $date[2] = 1;
                $date[1]++;
            }
            break;
        case 29:
            if ($date[1]==2 && ($date[0]%4!=0 || ($date[0]%100==0 && $date[0]%400!=0))) {
                $date[2] = 1;
                $date[1]++;
            }
    }
    if ($date[1]==13) {
        $date[1] = 1;
        $date[0]++;
    }
    $date = sprintf('%04d-%02d-%02d',$date[0],$date[1],$date[2]);
    savesetting('gamedate',$date);
}

// Adventsspecial für Merydiâ .. dies ist auf die reale Zeit bezogen, vom 1.12. bis 24.12., jeden Tag gibt es Geschenke
// Auch für anderes nutzbar ^^
// Copyright by Leen/Cassandra (cassandra@leensworld.de)
// SQL: INSERT INTO `settings` ( `setting` , `value` ) VALUES ('weihnacht', '0');

// settings -start-
$realdatum = time();
$datum = date('m-d',$realdatum);
// settings -end-

// Datum festlegen und welcher Dezember gerade ist
if ($datum >= '12-01' && $datum <= '12-31') {
    $weihnacht = $datum;
} else {
    $weihnacht = '0';
}
// Ende der Datumsabfrage

// speichern in Settings
savesetting('weihnacht',$weihnacht);
// fertig mit der Abfrage .. der Rest wird im newday.php gemacht!

// this now includes the database cleanup from index.php
$old = getsetting("expireoldacct",45)-5;
$new = getsetting("expirenewacct",10);
$trash = getsetting("expiretrashacct",1);

// Charas herausfinden, die eine Mail gesendet bekommen sollen
$sql = 'SELECT 
                acctid,
                name,
                emailaddress,
                laston
        FROM
                accounts
        WHERE
                laston < "'.date('Y-m-d H:i:s', strtotime(date('c').' - '.$old.' days')).'"
            AND
                emailaddress <> ""
            AND
                sentnotice = 0';
$result = db_query($sql);
$n = db_num_rows($result);

if($n > 0) {
    for($i=0; $i<$n; $i++) {
        $row = db_fetch_assoc($result);
        
        // Email losschicken
        mail($row['emailaddress'],
             'LoGD Charakter verfällt',
             'Dein Charakter '.StripColors($row['name']).' von Legend of the Green Dragon auf 
             '.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'
             verfällt demnächst und wird gelöscht. Wenn du den Charakter retten willst, solltest
             du dich bald möglichst mal damit einloggen!
             Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.',
             'From: '.getsetting('gameadminemail', 'postmaster@localhost.com'));
        
        // Auf "angeschrieben" setzen, Haus wegnehmen, Ehepartner freigeben.
        $sql = 'UPDATE 
                        accounts 
                SET 
                        sentnotice = 1,
                        house = 0,
                        housekey = 0,
                        marriedto = 0 
                WHERE 
                        acctid = '.$row['acctid'];
        db_query($sql);
        
        // Wenn der User das Ei hat, es ihm wegnehmen
        if((int)$row['acctid'] == (int)getsetting('hasegg', 0))
            savesetting('hasegg', 0);
        
        // Haus zum Verkauf freigeben
        $sql = 'UPDATE 
                        houses 
                SET 
                        owner = 0 
                WHERE 
                        owner='.$row['acctid'];
        db_query($sql);
        
        // Die Items, die der User bei sich trug, freigeben
        $sql = 'UPDATE 
                        items 
                SET 
                        owner = 0 
                WHERE 
                        owner = '.$row['acctid'];
        db_query($sql);
        
        // Arenakämpfe in die der User verwickelt ist, zurücksetzen.
        $sql = 'DELETE FROM 
                        pvp 
                WHERE 
                        acctid2 = '.$row['acctid'].' 
                    OR 
                        acctid1 = '.$row['acctid'];
        db_query($sql);
        
        // Den Ehepartner wieder freigeben
        $sql = 'UPDATE 
                        accounts 
                SET 
                        charisma = 0,
                        marriedto = 0 
                WHERE 
                        marriedto = '.$row['acctid'];
        db_query($sql);
        
        // globalen Logeintrag erstellen.
        global_log($row['name'].' `0angeschrieben, dass der Charakter bald gelöscht wird. Haus, Items, Arenakämpfe und Ehepartner zurückgesetzt. Letzte Aktivität des Charas: '.date('d.m.Y', StrToTime($row['laston'])), 'setnewday.php');
    }
}


    
    $old+=5;
    
    // Alle Spieler aus der DB holen, die sich noch nie eingeloggt haben.
    $sql = 'SELECT
                    acctid,
                    name,
                    laston
            FROM
                    accounts
            WHERE
                    laston < "'.date('Y-m-d H:i:s', strtotime(date('c').' - '.$trash.' days')).'"
                AND
                    experience < 10
                AND
                    level = 1
                AND
                    dragonkills = 0
                AND
                    lasthit = "0000-00-00 00:00:00"';
    $result = db_query($sql);
    if(db_num_rows($result) > 0) {
        $del_accs = array();
        
        while($row = db_fetch_assoc($result)) {
            $del_accs[$row['acctid']] = array('name' => $row['name'],
                                              'type' => 'trashacc',
                                              'laston' => $row['laston']);
        }
    }
    else
        $del_accs = array();
    
    
    // Alle Spieler aus der DB holen, die sonst noch gelöscht werden müssen
    // Löschgrenze auf Omar nach über 60 Tagen inaktivität, egal welches Level und wie viele DKs
    $sql = 'SELECT
                    acctid,
                    name,
                    laston
            FROM
                    accounts
            WHERE
                    laston < "'.date('Y-m-d H:i:s', strtotime(date('c').' - '.$old.' days')).'"';
    $result = db_query($sql);
    if(db_num_rows($result) > 0) {
        while($row = db_fetch_assoc($result)) {
            $del_accs[$row['acctid']] = array('name' => $row['name'],
                                              'type' => 'oldacc',
                                              'laston' => $row['laston']);
        }
    }
    
    if(!empty($del_accs)) {
        global_log('Insgesamt `b'.count($del_accs).'`b Chara'.(count($del_accs)>1?'s':'').' gelöscht!', 'setnewday.php');
        
        foreach($del_accs AS $acctid => $values) {
            // Wenn der User das Ei hat, ihm dieses wegnehmen
            if($acctid == getsetting("hasegg", 0)) 
                savesetting("hasegg", "0");
            
            // User aus der accounts Tabelle löschen
            $sql = 'DELETE FROM 
                            accounts 
                    WHERE 
                            acctid = '.$acctid;
            db_query($sql);
            
            // Haus zum Verkauf freigeben
            $sql = 'UPDATE 
                            houses 
                    SET 
                            owner = 0 
                    WHERE 
                            owner = '.$acctid;
            db_query($sql);
            
            // Schlüssel freigeben
            $sql = 'UPDATE 
                            items 
                    SET 
                            owner = 0 
                    WHERE 
                            owner = '.$acctid.'
                        AND 
                            class = "Schlüssel"';
            db_query($sql);
            
            // Items freigeben
            $sql = 'DELETE FROM
                            items 
                    WHERE 
                            owner = '.$acctid.'
                        AND 
                            owner != 0';
            db_query($sql);
            
            // Arenakämpfe, an denen der Spieler beteiligt ist, löschen
            $sql = 'DELETE FROM 
                            pvp 
                    WHERE 
                            acctid2 = '.$acctid.'
                        OR 
                            acctid1 = '.$acctid;
            db_query($sql);

            
            // Partner/in wieder freigeben
            $sql = 'UPDATE 
                            accounts 
                    SET 
                            charisma = 0,
                            marriedto = 0 
                    WHERE 
                            marriedto = '.$acctid;
            db_query($sql);
            
            // Selber erstellten RP Ort löschen
            $sql = 'DELETE FROM 
                            rporte 
                    WHERE 
                            acctid = '.$acctid;
            db_query($sql);
            
            // Konversationen löschen
            $sql = 'DELETE FROM
                            conversations
                    WHERE
                            (starter = '.$acctid.'
                                OR
                             receiver = '.$acctid.')
                        AND
                            (deleted_by != '.$acctid.'
                                AND
                             deleted_by != 0)';
            
            db_query($sql);
            
            $sql = 'UPDATE
                            conversations
                    SET
                            deleted_by = '.$acctid.'
                    WHERE
                            (starter = '.$acctid.'
                                OR
                             receiver = '.$acctid.')
                        AND
                            deleted_by = 0';
            db_query($sql);
            
            // Freundesliste löschen
            $sql = 'DELETE FROM
                            friendslist
                    WHERE
                            acctid = '.$acctid.'
                        OR
                            player = '.$acctid;
            db_query($sql);
            
            global_log('`b'.$values['name'].'`b`0 als '.($values['type']=='oldacc'?'normalen Chara gelöscht':'Chara, der sich nie einloggte, gelöscht').'. Letzter Login am '.date('d.m.Y', StrToTime($values['laston'])), 'setnewday.php');
        }
    }
            
    

savesetting("lastdboptimize",date("Y-m-d H:i:s"));
$result = db_query("SHOW TABLES");
while ($helferlein=db_fetch_assoc($result)){
    list($key,$val)=each($helferlein);
    db_query("OPTIMIZE TABLE $val");


// Pilzsuche by Linus & Veskara
if(mt_rand(1,5)==2) savesetting('steintroll','0');
else savesetting('steintroll','1');

}



$se=getsetting("tournament_c",7)-1;
  savesetting("tournament_c",$se);
  if(getsetting("tournament_c",7)<=1){
  savesetting("tournament_c",7);
 $sql = "SELECT acctid,name,gems,goldinbank,melee_result FROM accounts WHERE melee_result > 0  ORDER BY melee_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Nahkampf belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }
                
    $sql = "SELECT acctid,name,gems,goldinbank,bow_result FROM accounts WHERE bow_result > 0  ORDER BY bow_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Bogenschiessen belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }
                
    $sql = "SELECT acctid,name,gems,goldinbank,emagic_result FROM accounts WHERE emagic_result > 0  ORDER BY emagic_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Kampfmagie belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }
                
        $sql = "SELECT acctid,name,gems,goldinbank,gmagic_result FROM accounts WHERE gmagic_result > 0  ORDER BY gmagic_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Naturmagie belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }
                
    $sql = "SELECT acctid,name,gems,goldinbank,cook_result FROM accounts WHERE cook_result > 0  ORDER BY cook_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Kochen belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }

    $sql = "SELECT acctid,name,gems,goldinbank,swim_result FROM accounts WHERE swim_result > 0  ORDER BY swim_result DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0){
        $account=$row[acctid];
        $oro=$row[goldinbank]+5000;
        $gemme=$row[gems]+5;
        output("`^$row[name] has $row[gems] gems and $row[goldinbank] gold in bank `n");
        addnews("`#$row[name] `#hat den `^1. Platz beim Turnier der Klassen in der Kategorie Schwimmen belegt`n
        `#$row[name] `#hat `^5 Edelsteine`# und `&5.000 gold bekommen !!"); 
        }
        $sql = "UPDATE `accounts` SET `gems` = $gemme WHERE `acctid` = $account";
        $result1=db_query($sql);
        $sql = "UPDATE `accounts` SET `goldinbank` = $oro WHERE `acctid` = $account";
        $result2=db_query($sql);
                if($session[user][acctid]==$row[acctid]) $session[user][gems]+=5; $session[user][goldinbank]+=5000;
        }
  
  
  
  db_query("UPDATE accounts SET melee_result=0,bow_result=0,gmagic_result=0,emagic_result=0,cook_result=0,swim_result=0 WHERE melee_result>=1 OR bow_result>=1 OR emagic_result>=1 OR gmagic_result>=1 OR cook_result>=1 OR swim_result>=1");
  db_query("UPDATE accounts SET melee_result=0,bow_result=0,gmagic_result=0,emagic_result=0,cook_result=0,swim_result=0 WHERE melee_result>=1 OR bow_result>=1 OR emagic_result>=1 OR gmagic_result>=1 OR cook_result>=1 OR swim_result>=1");
  
  }

?>

