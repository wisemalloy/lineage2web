<?php
define('INWEB', True);
require_once("include/config.php");
//пароль
head("My Characters");
includeLang('user');
includeLang('myacc');
if (logedin())
{
    echo sprintf($Lang['welcome'], $_SESSION['account']);?>
    <br /><?php
    $timevoted = $_SESSION['vote_time'];
$now = time();

if ($timevoted <= ($now-60*60*12))
{
    echo "<a href=\"vote.php\"><font color=\"red\">{$Lang['vote']}</font></a><br />";
}else{
    echo "<font color=\"red\">You can vote again after ". date('H:i:s', $timevoted -($now-60*60*12)-60*60*2) ."<br />";
}?>
    <a href="changepass.php"><?php echo $Lang['changepass'];?></a><br />
    Your Referal Url: <input type="text" name="refurl" value="http://l2.pvpland.lv/reg.php?ref=<?php echo $_SESSION['account'];?>" readonly="" size="40" onclick="select()" /><br />
    Every user who registers from your link will add you <?php echo $Config['reg_reward'];?> webpoints<br />
    <h1>Your Chars</h1>
    <?php
    $sql=mysql_query("SELECT `account_name`, `charId`, `char_name`, `level`, `maxHp`, `maxCp`, `maxMp`, `sex`, `karma`, `fame`, `pvpkills`, `pkkills`, `race`, `online`, `onlinetime`, `lastAccess`, `nobless`, `vitality_points`, `ClassName`, `clan_id`, `clan_name` FROM `characters` INNER JOIN `char_templates` ON `characters`.`classid` = `char_templates`.`ClassId` LEFT OUTER JOIN `clan_data` ON `characters`.`clanid`=`clan_data`.`clan_id` WHERE `account_name` = '{$_SESSION['account']}';");
    if (mysql_num_rows($sql) != 0)
    {
    	?>
        <table border="1">
        <tr><td><?php echo $Lang['face'];?></td><td><?php echo $Lang['name'];?></td><td><?php echo $Lang['level'];?></td><td><?php echo $Lang['class'];?></td><td class="maxCp"><?php echo $Lang['cp'];?></td><td class="maxHp"><?php echo $Lang['hp'];?></td><td class="maxMp"><?php echo $Lang['mp'];?></td><td><?php echo $Lang['clan'];?></td><td><?php echo $Lang['pvp_pk'];?></td><td><?php echo $Lang['online_time'];?></td><td><?php echo $Lang['online'];?></td><td><?php echo $Lang['unstuck'];?></td></tr>
<?php
$i=0;
    while($char=mysql_fetch_assoc($sql))
    {
        $i++;
        $onlinetimeH=round(($char['onlinetime']/60/60)-0.5);
	$onlinetimeM=round(((($char['onlinetime']/60/60)-$onlinetimeH)*60)-0.5);
        if ($char['online']) {$online='<img src="img/status/online.png" />';} 
	else {$online='<img src="img/status/offline.png" />';} 
        if ($char['clan_id']) {$clan_link = "<a href=\"claninfo.php?clan={$char['clan_id']}\">{$char['clan_name']}</a>";}else{$clan_link = "No Clan";}
 ?>
<tr<?php echo ($i%2==0)?' style="altRow"':'';?> ><td><img src="img/face/<?php echo $char['race'].'_'.$char['sex'];?>.gif" /></td><td><a href="user.php?cid=<?php echo $char['charId'];?>"><font color="<?php echo $color;?>"><?php echo $char['char_name'];?></font></a></td><td><?php echo $char['level'];?></td><td><?php echo $char['ClassName'];?></td><td class="maxCp"><?php echo $char['maxCp'];?></td><td class="maxHp"><?php echo $char['maxHp'];?></td><td class="maxMp"><?php echo $char['maxMp'];?></td><td><?php echo $clan_link;?></td><td><b><?php echo $char['pvpkills'];?><font color="red"><?php echo $char['pkkills'];?></font></b></td><td><?php echo $onlinetimeH.' '.$Lang['hours'].' '.$onlinetimeM.' '.$Lang['min'];?></td><td><?php echo $online;?></td><td><a href="unstuck.php?cid=<?php echo $char['charId'];?>"><?php echo $Lang['unstuck'];?></a></td></tr>
<?php
    }
    echo "</table>";
    } else {echo '<h1>'.$Lang['no_characters'].'</h1>';}
} else {echo '<h1>'.$Lang['login'].'</h1>';}
foot();
?>