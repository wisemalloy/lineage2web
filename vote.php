<?php
define('INWEB', True);
require_once("include/config.php");
//пароль
head("Vote");
includeLang('vote');
if (!logedin())
{
    msg('Error', 'You need to login', 'error'); 
}

if (logedin())
{
$action = $_GET['action'];
//$hidden = $HTTP_POST_VARS['secrethiddenfromyou'];

 
$timevoted = $_SESSION['vote_time'];
$now = time();

if ($timevoted >= ($now-60*60*12))
{
msg($Lang['thank_you'], $Lang['vote_tommorow']);
}
if ($action == "vote" && $timevoted <= ($now-60*60*12))
{
if(!$_SESSION['vote_rnd']<time() && !$_SESSION['vote_rnd']>=time()-60*5){die();}
//$charid=mysql_real_escape_string($_POST['char']);
$_SESSION['vote_time']=$now;
$_SESSION['webpoints']+=$Config['vote_reward'];
mysql_query("UPDATE `accounts` SET `voted`='$now', `webpoints`=`webpoints`+'".$Config['vote_reward']."' WHERE `login` = '{$_SESSION['account']}'");
msg($Lang['thank_you'], $Lang['thank_for_voting']);

}elseif($action == "vote" && $timevoted >= ($now-60*60*12))
{
    error('8');
}
}
?>
<b><?php echo $Lang['vote_for_server'];?></b><br />

<script language="javascript" src="scripts/vote.js" type="text/javascript"></script>
<form name="vote" method="post" action="vote.php?action=vote">
<table border="1" cellspacing="0" cellpadding="5">
<tr><td><img src="http://www.xtremeTop100.com/votenew.jpg" alt="xtremeTop100" /></td><td><input type="button" onclick="one()" value="xtremetop" /></td></tr>

<tr><td><img src="http://www.gamingsites100.com/imgs/button_14.jpg" alt="gamingsites100" /></td><td><input type="button" onclick="two()" disabled="disabled" value="gamingsites" /></td></tr>

<tr><td><img src="http://www.gtop100.com/images/votebutton.jpg" alt="gtop100" /></td><td><input type="button" onclick="finish()" disabled="disabled" value="gtop100" /></td></tr>
</table>
<br />
<table border="1" cellspacing="0" cellpadding="5">
<tr><td>
<center><iframe src="http://wos.lv/d.php?11603f" name="wos_b" width="88" height="53" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></iframe></center></td></tr><tr><td>
<iframe width="180" scrolling="no" height="250" frameborder="0" marginheight="0" marginwidth="0" name="wos_a" src="http://wos.lv/a.php?b=180x250&amp;c=11603&amp;f=1"></iframe>
</td></tr>
<tr><td><a href="http://www.lattelecom.lv/pakalpojumi/2516261615/a8269a28.html" target="_top"><img src="http://affiliate.lattelecom.lv/affiliate/accounts/default1/banners/af_optika_468x60_LV_green.gif" alt="Optiskais internets" title="Optiskais internets" width="468" height="60" /></a><img style="border:0" src="http://affiliate.lattelecom.lv/affiliate/scripts/imp.php?a_aid=2516261615&amp;a_bid=a8269a28" width="1" height="1" alt="" /></td></tr>
</table><br />

<?php
if(logedin())
{
    $_SESSION['vote_rnd']=time();
    ?>
    <div align="center">From now for voting you will receive <?php echo $Config['vote_reward'];?> webpoints</div>
    <?php
if($timevoted < ($now-60*60*12)){
?>
<input name="go" type="submit" disabled="disabled" value="<?php echo $Lang['get_reward'];?>" />
<?php 
}
}
?>
</form>
<?php
foot();
?>