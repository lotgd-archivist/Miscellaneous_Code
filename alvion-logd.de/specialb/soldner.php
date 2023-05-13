
<?php
//
//16.02.2009
//soldner.php von A. Matz (hacktehplanet77@gmx.net) alias Aaron für Burg Elsterfels (www.dragoncity.at) 
//
switch($_GET['op']){case 'soldner':
                $zufall = e_rand(1,5);
                $soldner = array('1'=>array('name'=>'Noroth','msg'=>'Noroth ist tollpatschig und hilft nicht wirklich.','atk'=>'0.8','def'=>'1.1'),
                                                 '2'=>array('name'=>'Eren','msg'=>'Eren ist tollpatschig und hilft nicht wirklich.','atk'=>'0.9','def'=>'1.1'),
                                                 '3'=>array('name'=>'Ruwar','msg'=>'Ruwar geht dir ein wenig zur Hand.','atk'=>'1.1','def'=>'1.0'),
                                                 '4'=>array('name'=>'Rothom','msg'=>'Rothom geht dir ein wenig mehr zur Hand.','atk'=>'1.2','def'=>'1.0'),
                                                 '5'=>array('name'=>'Tanath','msg'=>'Tanath hilft dir voller Elan.','atk'=>'1.4','def'=>'1.2')
                );
                $rounds = mt_rand(15,50) + round($_GET['gold'] / 4);
                $session['bufflist']['soldner'] = array('name' => $soldner[$zufall]['name']
                                                                                                ,'roundmsg' => $soldner[$zufall]['msg']
                                                                                                ,'wearoff' => $soldner[$zufall]['name'].' hat dich verlassen.'
                                                                                                ,'rounds' => $rounds
                                                                                                ,'atkmod' => $soldner[$zufall]['atk']
                                                                                                ,'defmod' => $soldner[$zufall]['def']
                                                                                                ,'activate' => 'roundstart'
                );
                $session['user']['gold'] -= $_GET['gold'];
                $session['user']['specialinc'] = '';
                output('Der Söldner '.$soldner[$zufall]['name'].' hat deine '.$_GET['gold'].
                           ' Gold gefangen und wird für '.$rounds.' Runden an deiner Seite kämpfen.'
                );
        break;
        case 'verlassen2':
                $session['user']['specialinc'] = '';
                redirect('berge.php');
        break;
        case 'untersuchen':
                output('Du näherst dich der Kutsche und findest fünf Söldner. Sie bieten dir ihre Dienste an, '.
                           'aber sind sich nicht einig welcher dich begleiten soll.`n`n'.
                           'Der Zufall soll es bestimmen, werfe etwas Gold über die Kutsche und der Söldner, der es '.
                           'fängt, wird dich begleiten.'
                );
                addnav('Aktion');
                if ($session['user']['gold'] >= 100){
                        addnav('Werfe 100 Gold','berge.php?op=soldner&gold=60');        
                }
                if ($session['user']['gold'] >= 200){
                        addnav('Werfe 200 Gold','berge.php?op=soldner&gold=120');
                }
                if ($session['user']['gold'] >= 500){
                        addnav('Werfe 500 Gold','berge.php?op=soldner&gold=320');
                }
                if ($session['user']['gold'] < 100){
                        output('Du hast genug nicht Gold bei dir, also ab zurück.');
                        addnav('Zurück in die Berge','berge.php?op=verlassen2');
                }
                $session['user']['specialinc'] = 'soldner.php';
        break;        
        case 'verlassen':
                output('Dir ist das zu unheimlich, und gehst deiner Wege.`n'.
                           'Du verlierst 2 Charmepunkte.'
                );
                $session['user']['charm'] -= 2;
                $session['user']['specialinc'] = '';
        break;
        default:
                output('Als du auf der Suche nach Gegnern warst, fällt dir eine alte Kutsche am Wegesrand auf. '.
                           'Was willst du tun?'
                );
                addnav('Aktion');
                addnav('Geh zur Kutsche','berge.php?op=untersuchen');
                addnav('Sonstiges');
                addnav('zurück in die Berge','berge.php?op=verlassen');
                $session['user']['specialinc'] = 'soldner.php';
}
?>

