<?php
//damokles.php
//code: Hadriel
//idea: Hadriel
//for other servers

// Add in SQL:

//INSERT INTO items (name,class,description,gold) VALUES ('Schwert des Damokles','Beute.Prot','Ein altes, mächtiges Schwert',1);

//Let's set variables
$_gold=5000; //gold if quest ok
$_gems=5;    //gems if quest ok

$iddem=db_query("SELECT name,id FROM items WHERE name='Schwert des Damokles' AND owner=".$session['user']['acctid']);
$id=db_fetch_assoc($iddem);
//Let's do the code
switch($_GET['op']){

  default:
  $session['user']['specialinc']='damokles.php';
  addnav('Zurück','forest.php?op=flee');
  if(!$session['user']['prefs']['damoklesq']){
  output('`6Du kommst an eine dicht bewaldete und bewucherte Stelle im Wald, als du ohne vorwarnung einem Pfeil knapp entgehst.^"`t'.($session['user']['sex']?"Puh, mein Mädl":"Puh, mein Jungchn").', das wäre fast daneben gegangen. Du hast glück, dass ich dich mit meinem Bogen nicht erwischt hab, bin nämlich nicht so gut im Treffen.`6" `6Er mustert dich "`tDu hast nich zufällig mein Schwert gesehen? Ich suche es seit langem... vielleicht kannst du es ja für mich holen! Übrigens, mein Name ist `6Damokles`q!`6"');
  addnav('Damokles');
  addnav('Annehmen','forest.php?op=getquest');
  }
  if($session['user']['prefs']['damoklesq']=='ok' && db_num_rows($iddem)>0){
  output('`6Freudig hüpft Damokles aus seinem Versteck. "`t'.($session['user']['sex']?"Danke, mein Mädl":"Danke, mein Jungchn").', du hast das Schwert gefunden! Könntest du mir es eventuell geben? Ich werde dich dafür auch belohnen!`6" ');
  addnav('Damokles');
  addnav('Gib ihm sein Schwert',"forest.php?op=hasquest&id=".$id['id']);
  }
  if($session['user']['prefs']['damoklesq']=='ok' && db_num_rows($iddem)<=0){
  output('`6Du kommst erneut an die Stelle, wo du Damokles zum ersten mal gesehen hast. Jedoch ist er nicht hier. Könnte es vielleicht daran liegen, dass du sein Schwert nicht dabei hast?');
  }
    break;
  case 'getquest':
  $session['user']['specialinc']='';
  output('`6Du beschliesst, Damokles zu helfen. "`tAber wehe, du findest es nicht!`6"');
  $session['user']['prefs']['damoklesq']='ok';
  addnav('Zurück','forest.php');
    break;
  case 'hasquest':
  $session['user']['specialinc']='';
  output("`6Damokles `tbedankt sich bei dir und gibt dir $_gold `^Gold `qund $_gems `%Edelsteine`q. Freudig machst du dich auf den Weg in den Wald");
  $session['user']['prefs']['damoklesq']="";
  db_query("DELETE FROM items WHERE id=".(int)$_GET['id']);
  $session['user']['gold']+=$_gold;
  $session['user']['gems']+=$_gems;
  addnav('Zurück','forest.php');
    break;
  case 'flee':

    $session['user']['specialinc']='';
    output('`6Du versuchst so schnell wie möglich von hier zu verschwinden... ');
  switch(mt_rand(1,4)){
    case '1':
    case '2':
    case '3':
      output('`6Erschöpft erreichst du den Dorfrand.');
      if($session['user']['turns']>=3){
      $session['user']['turns']-=3;
      }else{
      $session['user']['turns']=0;
      }
    addnav('Zurück','forest.php');
      break;
    case '4':
    output('`6Doch dabei trittst du in eine Falle! Jämmerlich stirbst du.');
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['gold']=0;
    $session['user']['experience']*=0.95;
    addnav('Tägliche News','news.php');
    addnews($session['user']['name'].'`6 fand ein spitzes Grab und ruht nun in Frieden');
      break;
    }
    break;
    }

?>