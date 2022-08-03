<?php

/* ********************
Codierung von Ray
Ideen von Ray
ICQ: 230406044
******************** */

/***************************** Informationen ***********************************
ALTER TABLE `accounts` ADD `schuhe` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `armband` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `klamotten` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `kuse` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `ause` INT (12) DEFAULT '0' NOT NULL;

schuhe: 1=>Wanderschuhe, 2=>Sportscuhe, 3=>Lederstiefel, 4=>Drachen Stiefel

Armbänder: 1=>Goldenes Armband, 2=>Drachen Armband

Klamotten: 1=>Weises Kleid, 2=>Drachenleder Kleid, 3=>Schwarzes Jacket, 4=>Drachenleder Jacket
*******************************************************************************/


require_once "common.php";
page_header ("Kleidungsladen");

if ($_GET['op']==""){
output("`#Mit mühe hast du es geschafft dich an den Frauen vorbei ins Kleidungsgeschäft du drängen. Das geschäft ist knapp überfüllt Überall laufen Männder und Frauen umher und gucken sich an was dieser Laden zu bieten hat. Die Männer stehen bei den sehr begehrten Schuhen und die Frauen  GUcken sich die Kleider an.`nWas wirst du Tun?");

addnav("Schuhe");
addnav("Wanderschuhe - 1500 Gold","clothes.php?op=sw");
addnav("Sportschuhe - 2000 Gold","clothes.php?op=ss");
addnav("Lederstiefel - 3200 Gold","clothes.php?op=sl");
addnav("Drachen Stiefel - 5000 Gold","clothes.php?op=sd");
addnav("Armbänder");
addnav("Goldenes Armband - 2000 Gold","clothes.php?op=ag");
addnav("Drachen Armband - 4000 Gold","clothes.php?op=ad");
addnav("Klamotten");
addnav("Weises Kleid - 900 Gold","clothes.php?op=kw");
addnav("Drachenleder Kleid - 2000 Gold","clothes.php?op=kdk");
addnav("Schwarzes Jacket - 900 Gold","clothes.php?op=ks");
addnav("Drachenleder Jacket - 2000 Gold","clothes.php?op=kdj");
addnav("Sonstiges");
addnav("Zurück","center.php");

}else if ($_GET['op']=="sw"){
if ($session['user']['gold']>1499){
output("`#Du kaufst dir Brandneue Wanderschuhe und ziehst die auch gleich an.");
    $session['user']['schuhe']=1;
    $session['user']['gold']-=1500;

addnav("Zurück","center.php");
}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="ss"){
if ($session['user']['gold']>1999){
output("`#Du kaufst dir Brandneue Sportschuhe und ziehst die auch gleich an.");
    $session['user']['schuhe']=2;
    $session['user']['gold']-=2000;

addnav("Zurück","center.php");
}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="sl"){
if ($session['user']['gold']>3199){
output("`#Du kaufst dir Brandneue Lederstiefel und ziehst die auch gleich an.");
    $session['user']['schuhe']=3;
    $session['user']['gold']-=3200;

    addnav("Zurück","center.php");
}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="sd"){
if ($session['user']['gold']>4999){
output("`#Du kaufst dir Brandneue Drachenleder Stiefel und ziehst die auch gleich an.");
    $session['user']['schuhe']=4;
    $session['user']['gold']-=5000;

addnav("Zurück","center.php");

}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="ag"){
if ($session['user']['armband']==2){
if ($session['user']['gold']>1999){
output("`#Du kaufst dir ein ganz neues Goldenes Armband und legst es gleich um.");
    $session['user']['armband']=1;
    $session['user']['defence']-=2;
    $session['user']['defence']+=1;
    $session['user']['gold']-=2000;
    $session['user']['ause']=1;

    addnav("Zurück","center.php");

}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

}

}else{
if ($session['user']['armband']==1){
if ($session['user']['gold']>1999){
output("`#Du kaufst dir ein ganz neues Goldenes Armband und legst es gleich um.");
    $session['user']['armband']=1;
    $session['user']['gold']-=2000;
    $session['user']['ause']=1;


addnav("Zurück","center.php");
}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

}
}else{
if ($session['user']['gold']>1999){
output("`#Du kaufst dir ein ganz neues Goldenes Armband und legst es gleich um.");
    $session['user']['armband']=1;
    $session['user']['defence']+=1;
    $session['user']['gold']-=2000;
    $session['user']['ause']=1;

addnav("Zurück","center.php");
}else{
output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

addnav("Zurück","center.php");

            }
        }
    }
}else if ($_GET['op']=="ad"){
    if ($session['user']['armband']==1){
    if ($session['user']['gold']>3999){
    output("`#Du kaufst dir ein ganz neues Drachen Armband und legst es gleich um.");
    $session['user']['armband']=2;
    $session['user']['defence']-=1;
    $session['user']['defence']+=2;
    $session['user']['gold']-=4000;
    $session['user']['ause']=1;

    addnav("Zurück","center.php");
}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");

}
}else{
if ($session['user']['armband']==2){
if ($session['user']['gold']>3999){
output("`#Du kaufst dir ein ganz neues Drachen Armband und legst es gleich um.");
    $session['user']['armband']=2;
    $session['user']['gold']-=4000;
    $session['user']['ause']=1;


addnav("Zurück","center.php");

}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");

}
}else{
if ($session['user']['gold']>3999){
output("`#Du kaufst dir ein ganz neues Drachen Armband und legst es gleich um.");
    $session['user']['armband']=2;
    $session['user']['defence']+=2;
    $session['user']['gold']-=4000;
    $session['user']['ause']=1;

    addnav("Zurück","center.php");

}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");

            }
        }
    }
}else if ($_GET['op']=="kw"){
if ($session['user']['sex']>0){
if ($session['user']['gold']>899){
output("`#Du willst das Weise Kleid kaufen und bezahlst auch gleich. Sofort gekauft ziehst du es auch an.");

$session['user']['klamotten']=1;
$session['user']['attack']+=1;
$session['user']['gold']-=900;
$session['user']['kuse']=1;

addnav("Weiter","center.php");
}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");
}
}else{
output("`#Der verkäufer sieht dich nur an und sagt dann, tut mir leid an Männer verkaufen wir keine Kleider.");

addnews("`#".$session['user']['name']."wollte sich ein Weises Kleid Kaufen");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="kdk"){
if ($session['user']['sex']>0){
if ($session['user']['gold']>1999){
output("`#Du willst das Drachenleder Kleid kaufen und bezahlst auch gleich. Sofort gekauft ziehst du es auch an.");

$session['user']['klamotten']=2;
$session['user']['attack']+=2;
$session['user']['gold']-=2000;
$session['user']['kuse']=1;

addnav("Weiter","center.php");
}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");
}
}else{
output("`#Der verkäufer sieht dich nur an und sagt dann, tut mir leid an Männer verkaufen wir keine Kleider.");

addnews("`#".$session['user']['name']."wollte sich ein Drachenleder Kleid Kaufen");

addnav("Zurück","center.php");

    }
}else if ($_GET['op']=="ks"){
if ($session['user']['sex']>0){
output("`#Der verkäufer sieht dich nur an und sagt dann, tut mir leid an Frauen verkaufen wir keine Jackets.");

addnews("`#".$session['user']['name']."wollte sich ein Schwarzes Jacket Kaufen");

addnav("Zurück","center.php");
}else{
if ($session['user']['gold']>899){
output("`#Du willst das Schwarze Jacket kaufen und bezahlst auch gleich. Sofort gekauft ziehst du es auch an.");

$session['user']['klamotten']=3;
$session['user']['attack']+=1;
$session['user']['gold']-=900;
$session['user']['kuse']=1;

addnav("Weiter","center.php");
}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");

    addnav("Zurück","center.php");
        }
    }
}else if ($_GET['op']=="kdj"){
if ($session['user']['sex']>0){
output("`#Der verkäufer sieht dich nur an und sagt dann, tut mir leid an Frauen verkaufen wir keine Jackets.");

addnews("`#".$session['user']['name']."wollte sich ein Drachenleder Jacket Kaufen");

addnav("Zurück","center.php");
}else{
if ($session['user']['gold']>1999){
output("`#Du willst das Drachenleder Jacket kaufen und bezahlst auch gleich. Sofort gekauft ziehst du es auch an.");

$session['user']['klamotten']=4;
$session['user']['attack']+=2;
$session['user']['gold']-=2000;
$session['user']['kuse']=1;

addnav("Weiter","center.php");
}else{
    output("`#Du hast nicht genügend Gold komm ein ander mal wieder.");
        addnav("Zurück","center.php");
        }
    }
}


page_footer();
?> 