
<?php

// Die Frösche
// Wenn der Ehegatte ein Frosch ist kann man versuchen ihn zu erlösen. Dabei kann man auch selbst zum Frosch werden.
// idea: Shandi, coding: Salator
//todo: in kommentaren sagt durch quakt ersetzen

require_once('common.php');
page_header('Die Frösche');
checkday();
addcommentary();

music_set('waldsee');

output(get_title('`@Die Frösche').'`2');

if ($_GET['op']=='frogselect') //einen Frosch zum Flirten auswählen
{
    $picture='images/'.($session['user']['sex']?'kermit.jpg':'toad.jpg');

    //beide Partner sind Frösche
    if($session['user']['title']=='`2Frosch`0' || $session['user']['title']=='`2Kröte`0')
    {
        output('`c<img src="'.$picture.'">');
        output(words_by_sex('`n`cAls [Frosch|Kröte] kannst du unter all den [Kröten|Fröschen], die hier am Waldsee sitzen, ohne Probleme [deine Frau|deinen Mann] erkennen.
        `nIhr fröschelt eine Weile miteinander, was dir einen Charmepunkt einbringt.'));
        $session['user']['charm']++;
        $session['user']['seenlover']=1;
        $session['bufflist']['lover'] = array
        (
            'name'=>'`2Schutz des Quakens'
            ,'rounds'=>60
            ,'wearoff'=>'`2Du vermisst deine große Liebe!`0'
            ,'defmod'=>1.2
            ,'roundmsg'=>'Deine große Liebe lässt dich an deine Sicherheit denken!'
            ,'activate'=>'defense'
        );
    }
    //ein paar Frösche zur Auswahl
    else
    {
        $anzahl=$_GET['lv']+1;
        output(words_by_sex('Du suchst das Ufer ab, ob du irgendwo unter den Fröschen [deine Frau|deinen Mann] erblickst.
        `nVerdammt, die sehen alle so gleich aus. Welches könnte denn dein Ehepartner sein?
        `n'.$anzahl.' Frösche kommen in die engere Wahl. Doch Vorsicht, wählst du den Falschen, könntest du selbst zum Frosch werden.
        `nAlso, für wen entscheidest du dich?`c'));
        for($i=0;$i<$anzahl;$i++)
        {
            $pic_w=e_rand(75,150);
            $pic_h=e_rand(85,170);
            $space=e_rand(15,70);
            output('<a href="frogs.php?op=frogkiss&lv='.$_GET['lv'].'"><img src="'.$picture.'" width='.$pic_w.' height='.$pic_h.' border=0></a>
            <img src="images/trans.gif" width='.$space.' height=1 border=0>');
        }
        output('`c`n');
        addnav('','frogs.php?op=frogkiss&lv='.$_GET['lv']);
    }
}

elseif ($_GET['op']=='frogkiss') //einen Frosch küssen - was passiert nun?
{
    $level=intval($_GET['lv']);
    $chance=e_rand($level,45);
    if($chance<15) //Erfolg: Partner wird zurückverwandelt
    {
        $sql='SELECT a.acctid,cname,ctitle,csign,login,dragonkills,sex
            FROM account_extra_info aei
            LEFT JOIN accounts a ON a.acctid=aei.acctid
            WHERE a.acctid='.(int)$session['user']['marriedto'];
        $row=db_fetch_assoc(db_query($sql));
        //$titles = unserialize(getsetting('title_array',null));
        $row['title']=$titles[$row['dragonkills']][$row['sex']];
        if (empty($newtitle))
        {
            $row['title'] = $titles[sizeof($titles)-1][$row['sex']];
        }
        $str_csign = !empty($row['csign']) ? $row['csign'].'`&' : '';
        $str_realtitle = !empty($row['ctitle']) ? str_replace('`0','',$row['ctitle']) : $row['title'];
        $str_realname = !empty($row['cname'])     ? str_replace('`0','',$row['cname']) : $row['login'];
        $str_name = trim($str_csign).trim($str_realtitle).' '.trim($str_realname).'`0';

        user_update(
            array
            (
                'name'=>addslashes($str_name)
                ,'title'=>addslashes($row['title'])
            ),
            $row['acctid']
        );
        systemmail($row['acctid'],'Entfroscht!',$session['user']['name'].'`^ hat dich am Waldsee unter all den anderen Fröschen gefunden und dich mit einem Kuss von deinem Fluch befreit. Ist das nicht toll?');

        output(words_by_sex('Du greifst dir eine[|n] der [Kröten|Frösche], von [der|dem] du glaubst, [sie sei deine verwunschene Ehefrau|er sei dein verwunschener Ehemann]. Du denkst an deine große Liebe und gibst [der Kröte|dem Frosch] einen innigen Kuss.
        `nUnd sieheda, plötzlich steht '.$str_name.'`2 vor dir.
        `nNa das ist ja grad nochmal gut gegangen... Glücklich kehrt ihr ins Dorf zurück.'));
        $session['user']['charm']++;
        $session['bufflist']['lover'] = array
        (
            'name'=>'`!Schutz der Liebe'
            ,'rounds'=>60
            ,'wearoff'=>'`!Du vermisst deine große Liebe!`0'
            ,'defmod'=>1.2
            ,'roundmsg'=>'Deine große Liebe lässt dich an deine Sicherheit denken!'
            ,'activate'=>'defense'
        );
    }
    elseif($chance>31) //Misserfolg: Man wird selbst zum Frosch
    {
        $oldweaponname=$session['user']['weapon'];
        $oldarmorname=$session['user']['armor'];

        item_set_weapon('Klebrige Zunge', 1, 1);
        item_set_armor('Schleimige Haut', 1, 1);

        output(words_by_sex('`nTja, das war wohl die schlechteste Entscheidung, die du heute getroffen hast. Nicht [die Kröte|der Frosch] verwandelt sich, sondern DU veränderst deine Gestalt!
        `n`#Du wurdest in [einen Frosch|eine Kröte] verwandelt und musst jetzt in dieser Form dein Dasein fristen.
        `n`n`n`0Die Welt kommt dir plötzlich so groß vor. Auch dein '.$oldweaponname.'`0 und dein '.$oldarmorname.'`0 sind nicht mehr da wo sie eben noch waren. Wenigstens hast du deinen Inventarbeutel noch bei dir, in den du bald mal reingucken solltest.`n'));
        addnews('`@'.$session['user']['name'].'`@ hat heute einen Imagewandel erfahren.');

        $session['user']['title'] = ($session['user']['sex']?'`2Kröte`0':'`2Frosch`0');
        user_set_name($session['user']['acctid']);
    }
    else //nichts passiert
    {
        output(words_by_sex('Du greifst dir eine[|n] der [Kröten|Frösche], von [der|dem] du glaubst, [sie|er] sei dein verwunschener Partner. Du denkst an deine große Liebe und gibst [der Kröte|dem Frosch] einen innigen Kuss. Aber leider ... nichts passiert. [Kröten|Frösche], die sich in [Prinzessinnen|Prinzen] verwandeln, gibts wohl doch nur im Märchen.
        `nFür heute hast du deine Chance auf eine Romanze mit deinem Partner vertan.'));
    }
    $session['user']['seenlover']=2;
}

elseif ($_GET['op']=='do_nothing')
{
    if($_POST['turns']!=0)
    {
        $turns=abs(intval($_POST['turns']));
        output('Du lauschst '.$turns.' Runden den Fröschen. Aber sonst passiert nichts.
        `n`nWie, du glaubst das nicht? Dann probier es eben nochmal, ist ja deine Zeit, die du verplemperst...');
        $session['user']['turns']=max(0,$session['user']['turns']-$turns);
    }
    else
    {
        output('Du könntest deine Zeit damit verbringen, den Fröschen zu lauschen. Erwarte aber nicht, dass dir das `birgendwas`b nützt.');
    }
    if($session['user']['turns'] > 0) {
        output('`n`nWie lange willst du den Fröschen lauschen?
        <form action="frogs.php?op=do_nothing" method="post">
        <input type="text" name="turns" id="turns" size="2" maxlength="2">
        <input type="submit" class="button" value="Runden nichts tun">
        </form>
        ');
        addnav('','frogs.php?op=do_nothing');
    }
    else {
        output('`n`nDu bist zu erschöpft, um weiter dem einschläfernden Quaken der Frösche zu lauschen..');
    }
    addnav('Nix da!','frogs.php');
}

else //Startbild
{
    if (!$session['user']['prefs']['nosounds'])
    {
        output('<embed src="media/frosch.wav" width=10 height=10 autostart=true loop=true hidden=true volume=100>');
    }
    output('Du verlässt den belebteren Teil des Sees und schlägst dich tiefer in die Büsche. Ein unüberhörbares Quaken dringt an dein Ohr. Hier, abseits vom Platz der Angler, sitzen hunderte Frösche am See und lassen sich von der Sonne wärmen.`n`n');
    addnav('Q?Lausche dem Quaken','frogs.php?op=do_nothing');

    if($session['user']['charisma']>999 && $session['user']['marriedto']>0 && $session['user']['marriedto']<1234567)
    {
        $sql='SELECT name,title,level FROM accounts WHERE acctid='.$session['user']['marriedto'];
        $row=db_fetch_assoc(db_query($sql));
        if($row['title']=='`2Frosch`0' || $row['title']=='`2Kröte`0')
        {
            output('Irgendwo inmitten von diesem grünen Getümmel müsste auch '.$row['name'].'`2 zu finden sein. ');
            if($session['user']['title']=='`2Frosch`0' || $session['user']['title']=='`2Kröte`0')
            {
                output('Du könntest hier ein paar schöne Momente mit deinem Partner verbringen...`n`n');
                if($session['user']['seenlover']==0)
                {
                    addnav('Flirten','frogs.php?op=frogselect&lv='.$row['level']);
                }
                else
                {
                    output('`@Allerdings wirst du damit bis morgen warten müssen.`n`n');
                }
            }
            else
            {
                output('Du könntest mit einem Kuss deinen verwunschenen Partner befreien - wenn du den richtigen Frosch findest...`n`n');
                if($session['user']['seenlover']<2)
                {
                    addnav('Frösche betrachten','frogs.php?op=frogselect&lv='.$row['level']);
                }
                else
                {
                    output('`@Allerdings wirst du damit bis morgen warten müssen.`n`n');
                }
            }
        }
    }
    viewcommentary('frogger','Etwas Quaken',25,'quakt');
}

addnav('Zurück');
addnav('s?Zum Waldsee','pool.php');
addnav('D?Zum Dorf','village.php');

page_footer();
?>


