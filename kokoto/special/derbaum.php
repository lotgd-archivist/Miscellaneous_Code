<?php
//////////////////////////
// Make By Kev          //
// 25.07.2004           //
// Copyright by Kev     //
// v0.2 Der Baum        //
// Idea by Hadriel      //
//////////////////////////
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit();

if ($_GET['op']=='ask'){
      $rand = mt_rand(1,3);
switch ($rand){
          case '1':
          case '2':
    addnav('Die Person retten','forest.php?op=lance');
    addnav('Weitergehen','forest.php?op=lance1');
    $session['user']['specialinc'] = 'derbaum.php';
    output('`2Du kommst zu einem Baum, er sieht etwas modrig aus...`n Du hörst eine Stimme und denkst, sie kommt aus dem Baum, du bist dir aber nicht sicher...`nWas willst du tun?`n');
    break;
    case '3':
    $session['user']['hitpoints']=0;
    $session['user']['alive']=false;
    $session['user']['specialinc']='';
    addnav('Tägliche News','news.php');
    output('`2Du hast den falschen Weg gewählt! Du bist tot!!!`n');
    addnews($session['user']['name'].' `2wurde tot in der Nähe eines modrigen Baumes gefunden!');
  }
  }else if ($_GET['op']=='lance'){
    $session['user']['specialinc'] = '';
    $session['user']['gold']+=1200;
    $reward = round($session['user']['experience']  0.5);
    $session['user']['experience'] += $reward;
    addnav('Zurück in den Wald','forest.php?=leave');
    output("`2Du fängst sofort an, die vermutete Person zu retten, indem du versuchst, den Schlitz größer zu bekommen.`nAls endlich der Schlitz groß genug ist, kommt eine Frau heraus...`n`@Danke, du hast mich gerettet. Ich wurde von einem bösen Zauberer in den Baum eingesperrt und war viele Jahre dort drin, aber niemand wollte mich retten...`nIch werde dich für deine Hilfe belohnen...`n`2Du bekommst für deine Hilfe `^1200Gold `2und `^$reward Erfahrung`2!`n");
    addnews($session['user']['name'].' `2hat eine Frau aus einem modrigen Baum gerettet!');
    }else if ($_GET['op']=='lance1'){
      $rand = mt_rand(1,3);
switch ($rand){
    case '1':
    case '2':
    $session['user']['hitpoints']=0;
    $session['user']['alive']=false;
    $session['user']['specialinc']='';
    addnav('Tägliche News','news.php');
    output('`2Du gehst einfach weiter, da sagt eine Stimme...`n`@Du hast der Frau, die im Baum ist, nicht geholfen - ich werde dich dafür bestrafen!`n`2Du verlierst all deine Lebenspunkte, du darfst morgen weiterspielen...`n');
    addnews($session['user']['name'].' `2wurde tot in der Nähe eines modrigen Baum gefunden!');
    break;
    case '3':
    $session['user']['specialinc']='';
    output('Du gehst weiter deines Weges');
    addnav('Zurück','forest.php');
    }
    }else if ($_GET['op']=='ask1'){
         $rand = mt_rand(1,3);
switch ($rand){
          case '1':
          case '2':
    addnav('Die Person retten','forest.php?op=lance');
    addnav('Weitergehen','forest.php?op=lance1');
    $session['user']['specialinc'] = 'derbaum.php';
    output('`2Du kommst zu einem Baum, er sieht etwas modrig aus...`n Du hörst eine Stimme und denkst, sie kommt aus dem Baum, du bist dir aber nicht sicher...`nWas willst du tun?`n');
    break;
    case '3':
    $session['user']['hitpoints']=0;
    $session['user']['alive']=false;
    $session['user']['specialinc']='';
    addnav('Tägliche News','news.php');
    output('`2Du hast den falschen Weg gewählt! Du bist tot!!!`n');
    addnews($session['user']['name'].' `2wurde tot in der Nähe eines modrigen Baumes gefunden!');
  }
  }else if ($_GET['op']=='ask2'){
      $rand = mt_rand(1,3);
switch ($rand){
          case '1':
          case '2':
    addnav('Die Person retten','forest.php?op=lance');
    addnav('Weitergehen','forest.php?op=lance1');
    $session['user']['specialinc'] = 'derbaum.php';
    output('`2Du kommst zu einem Baum, er sieht etwas modrig aus...`n Du hörst eine Stimme und denkst, sie kommt aus dem Baum, du bist dir aber nicht sicher...`nWas willst du tun?`n');
    break;
    case '3':
    $session['user']['hitpoints']=0;
    $session['user']['alive']=false;
    $session['user']['specialinc']='';
    addnav('Tägliche News','news.php');
    output('`2Du hast den falschen Weg gewählt! Du bist tot!!!`n');
    addnews($session['user']['name'].' `2wurde tot in der Nähe eines modrigen Baumes gefunden!');
  }
  }else if ($_GET['op']=='leave'){
$session['user']['specialinc']='';
output('`2Du traust dich nicht so recht, einen Weg auszusuchen und gehst deshalb den Weg, den du gekommen bist, wieder zurück');
addnav('Zurück in den Wald','forest.php');
}else{
$session['user']['specialinc']='derbaum.php';
    addnav('Links','forest.php?op=ask');
    addnav('Gradeaus','forest.php?op=ask1');
    addnav('Rechts','forest.php?op=ask2');
    addnav('Zurück in den Wald','forest.php?op=leave');
    $session['user']['specialinc'] = 'derbaum.php';
    output('`n`2`c`bDer Baum`b `n `n `2Du gehst durch den Wald, plötzlich kommst du an eine Kreuzung...`n Es sind dort 3 Wege..., `@es gibt nur 2 richtige Wege, also überleg gut, welchen du nimmst...`2Wolang willst du gehen???`n`c');
    }
?>