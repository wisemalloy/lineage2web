<?php
define('INWEB', True);
require_once("include/config.php");
//пароль
includeLang('webpoints');


if($user->logged())
{
    $stat = getVar('stat');
    if(isset($_GET['page']))
    {
        $start = getVar('page');
    }
    else
    {
        $start = 1;
    }
    if(!is_numeric($start) || $start==0) {$start = 1;}
    $start=abs($start)-1;
    $startlimit = $start*getConfig('settings', 'TOP', '10');
    $a=getvar('a');
    $par['lang']=getLang();
    $par['a']=$a!=''?$a:'home';
    $par['page']=$start+1;
    $webdb=getConfig('settings','webdb','l2web');
    $params = implode(';', $par);
    $content="";
    $content.= "<h1>WebShop</h1><br />";
    $content.= "<center><a href=\"webshop.php\">All Items</a> | <a href=\"webshop.php?a=add\">Add Item</a> | <a href=\"webshop.php?a=my\">View My Items</a> | <a href=\"webshop.php?a=bought\">Bought Items</a></center><br />";
    switch($a)
    {
        case "additem":
        //print_r($_POST);
        $objectId=getVar('item');
        $sql->query("SELECT * FROM `items` WHERE `object_id`='$objectId' AND `owner_id` IN (SELECT charId FROM characters WHERE account_name='{$_SESSION['account']}' AND online='0') AND item_id!='57' AND count>'0' AND mana_left='-1' AND time='-1'");
        if($sql->num_rows())
        {
            $count=getVar('count');
            $money=getVar('money');
            $moneyc=getVar('money_count');
            $comment=getVar('comment');
            $item=$sql->fetch_array();
            if($count>$item['count'])
            {
                err('Error','Incorrect count!');
            }
            $object_id=getConfig('webshop','inc','0')+1;
            $sql->query("INSERT INTO `l2web`.`webshop` (`owner`, `object_id`, `item_id`, `count`, `enchant_level`, `loc`, `money`, `money_count`, `sticky`, `added`) VALUES ('{$_SESSION['account']}', '$object_id', '{$item['item_id']}', '$count', '{$item['enchant_level']}', 'WEB', '$money', '$moneyc', '0', NOW())");
            
            setConfig('webshop','inc',$object_id);
            if($sql->row_count)
            {
                $sql->query("DELETE FROM `items` WHERE `object_id`='$objectId'");
                $sql->query("SELECT * FROM l2web.all_items WHERE id='{$item['item_id']}'");
                $itemadd=$sql->fetch_array();
                $body="You have added [url=item.php?id={$item['item_id']}][img]img/icons/{$itemadd['icon1']}.png[/img][/url][hr][url=webshop.php?a=view&id=$object_id]View Your Item[/url]|[url=webshop.php?a=add]Add Another[/url]";
                $sql->query("INSERT INTO `l2web`.`messages` (`receiver`, `added`, `subject`, `msg`) VALUES ('{$_SESSION['account']}', NOW(), 'Webshop Item Add', '$body')");
                suc('Success','Your item has been added');
            }
        }
        else
        {
            err('Error','Nothing to add');
        }
        die();
        
        break;
        case "add":
        head("WebShop - Add Item");
        echo $content;
        if($_POST)
        {
            $objectId=getVar('item');
            $sql->query("SELECT * FROM `items` WHERE `object_id`='$objectId' AND `owner_id` IN (SELECT charId FROM characters WHERE account_name='{$_SESSION['account']}' AND online='0') AND item_id!='57' AND count>'0' AND mana_left='-1' AND time='-1'");
            if($sql->num_rows())
            {
                $item=$sql->fetch_array();
                $sql->query("SELECT * FROM l2web.all_items WHERE id='{$item['item_id']}'");
                $itemadd=$sql->fetch_array();
                
                ?>
                <form name="item" action="?a=additem" method="post">
                <table cellpadding="5" cellspacing="5" border="2" width="425px">
    <tr><td><img src="img/icons/<?php echo $itemadd['icon1'];?>.png" alt="<?php echo $itemadd['name'];?>" title="<?php echo $itemadd['name'];?>" width="64" height="64"/></td>
    <td>
    <table border="1" width="315px">
    <tr><td>Name</td><td><?php echo $itemadd['name'].$addname;?></td></tr>
    <tr><td>Type</td><td><?php echo $itemadd['type'];?></td></tr>
    <tr><td>Body Part</td><td><?php echo $itemadd['bodypart'];?></td></tr>
    <?php
    $grade=($itemadd['grade']!='none')?"<img src=\"img/grade/{$itemadd['grade']}-grade.png\" alt=\"{$itemadd['grade']}\" title=\"{$itemadd['grade']}\" />":"none";
    ?>
    <tr><td>Grade</td><td><?php echo $grade;?></td></tr>
    <?php
    if($item['enchant_level']!='' && $item['enchant_level']>0)
    {?>
    <tr><td>Enchant</td><td><?php echo $item['enchant_level'];?></td></tr><?php
    }
    ?>
    <tr><td>Count</td><td><input type="text" name="count" /> Max:<?php echo $item['count'];?></td></tr>
    <tr><td>Price<br /> per 1 item</td><td><select name="money"><option value="0">Adena</option><option value="1">Webpoints</option></select><input type="text" name="money_count" /></td></tr>
    </table>
    </td></tr>
    </table>
    <br />
    <?php
    if($itemadd['desc']!="" || $itemadd['grade']=="none")
    {
        if($itemadd['desc']!="")
        {
        ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $itemadd['desc'];?></td></tr></table>
    
    <?php
    }
    }
    else
    {
        if($itemadd['bodypart']=="lhand")
            $itemadd['bodypart']="shield";
        //try to find chest from armorsets
        $c=$sql->query("SELECT `chest` FROM `armorsets` WHERE `{$itemadd['bodypart']}`='{$itemadd['id']}'");
        if($sql->num_rows($c))
        {
            $chest_id=$sql->result($c);
            $i['desc']=$sql->result($sql->query("SELECT `desc` FROM `$webdb`.`all_items` WHERE `id`='$chest_id'"));
           ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $itemadd['desc'];?></td></tr></table>
    <?php 
        }
    }
        ?>
        Comment:<br />
        <?php
        textbbcode('item','comment');
        ?>
        <br /><input type="hidden" name="item" value="<?php echo $objectId;?>" /> <input type="submit" value="Add Item" /></form>
        <?php
            }
            
        }
        else
        {
        $qry=$sql->query("SELECT * FROM `items` WHERE `owner_id` IN (SELECT charId FROM characters WHERE account_name='{$_SESSION['account']}' AND online='0') AND item_id!='57' AND count>'0' AND mana_left='-1' AND time='-1'");
        echo '<form action="" method="post"><select name="item">';
        while($item=$sql->fetch_array($qry))
        {
            $sql->query("SELECT * FROM l2web.all_items WHERE id='{$item['item_id']}'");
            $all=$sql->fetch_array();
            $addname=$addname!=''?' - '.$addname:'';
            $ench=$item['enchant_level']>0?' + '.$item['enchant_level']:'';
            echo '<option value="'.$item['object_id'].'">'.$all['name'].$addname.$ench.'</option>';
        }
        echo '</select><br /><input type="submit" value="Next" /></form>';
        }
        break;
        case "my":
        
        $qry=$sql->query("SELECT * FROM l2web.webshop WHERE owner='{$_SESSION['account']}'");
        if(!$sql->num_rows($qry))
        {
            err('Error', 'You don\'t have any item in webshop');
        }
        head('WebShop - My Items');
        echo $content;
        echo "<table border=\"1\">";
        echo "<tr><th>Icon</th><th>Name</th><th>Price</th><th>Owner</th><th>Action</th></tr>";
        
        while($item=$sql->fetch_array($qry))
        {

            $details=$sql->query("SELECT * FROM `{webdb}`.`all_items` WHERE `id`='{$item['item_id']}'", array('webdb'=>$webdb));
            $item_d=$sql->fetch_array($details);
            $addname=($item_d['addname']=="")?"":" - ".$item_d['addname'];
            $price=($item['money']=="0")?" Adena":" WebPoints";
            $grade = $item_d["grade"];
            $grade = (!empty($grade) || $grade!="none") ? "&lt;img border=\\'0\\' src=\\'img/grade/" . $grade . "-grade.png\\' />" : "";
            $enchant = $item["enchant_level"] > 0 ? " +" . $item["enchant_level"] : "";
            //print_r($item);
            echo "<tr><td>";
            echo "<img src=\"img/icons/{$item_d['icon1']}.png\" alt=\"{$item_d['name']}\" title=\"{$item_d['name']}\" onmouseover=\"Tip('&lt;img src=\'img/icons/{$item_d['icon1']}.png\' /&rt;&lt;br /> {$item_d['desc']}',TITLE, '{$enchant} {$item_d['name']}$addname {$grade}', FONTCOLOR, '#FFFFFF',BGCOLOR, '#406072', BORDERCOLOR, '#666666', FADEIN, 500, FADEOUT, 500, FONTWEIGHT, 'bold')\" />";
            echo "</td><td>";
            
            echo "{$item_d['name']}$addname x{$item['count']}";
            
            echo "</td><td>";
            echo "{$item['money_count']} $price";
            echo "</td><td>";
            echo "{$item['owner']}";
            echo "</td><td>";
            echo "<a href=\"webshop.php?a=view&amp;id={$item['object_id']}\">View</a> | <a href=\"webshop.php?a=transfer&amp;id={$item['object_id']}\">Transfer to Game</a>";
            echo "</td></tr>";
        
        
        }
        echo "</table>";
        break;
        case "transfer":
        $id=getVar('id');
        if($_POST)
        {
            
        }
        else
        {
            
            $qry=$sql->query("SELECT * FROM l2web.webshop WHERE object_id='$id'");
            if(!$sql->num_rows($qry))
            {
                err('Error','Cannot find item');
            }
            
            $item=$sql->fetch_array($qry);
            $sql->query("SELECT * FROM l2web.all_items WHERE id='{$item['item_id']}'");
            $itemi=$sql->fetch_array();
            $addname=($itemi['addname']!='')?' - '.$itemi['adname']:'';
            head('WebShop - Transfer Item');
            echo $content;
            ?><form name="item" action="?a=edit" method="POST">
            <table cellpadding="5" cellspacing="5" border="2" width="425px">
    <tr><td><img src="img/icons/<?php echo $itemi['icon1'];?>.png" alt="<?php echo $itemi['name'];?>" title="<?php echo $itemi['name'];?>" width="64" height="64"/></td>
    <td>
    <table border="1" width="315px">
    <tr><td>Name</td><td><?php echo $itemi['name'].$addname;?></td></tr>
    <tr><td>Type</td><td><?php echo $itemi['type'];?></td></tr>
    <tr><td>Body Part</td><td><?php echo $itemi['bodypart'];?></td></tr>
    <tr><td>Active:</td><td>Yes: <input name="active" type="checkbox" <?php echo $item['active']?'checked="checked"':'';?> /></td></tr>
    
    <?php
    $grade=($itemi['grade']!='none')?"<img src=\"img/grade/{$itemi['grade']}-grade.png\" alt=\"{$itemi['grade']}\" title=\"{$itemi['grade']}\" />":"none";
    ?>
    <tr><td>Grade</td><td><?php echo $grade;?></td></tr>
    <?php
    if($item['enchant_level']!='' && $item['enchant_level']>0)
    {?>
    <tr><td>Enchant</td><td><?php echo $item['enchant_level'];?></td></tr><?php
    }
    ?>
    <tr><td>Count</td><td><?php echo $item['count'];?></td></tr>
    <tr><td>Price<br /> per 1 item</td><td><input type="text" name="moneyc" value="<?php echo $item['money_count'];?>" /><select name="money"><option value="0" <?php echo $item['money']==0?"selected=\"\"":'';?>>Adena</option><option value="1" <?php echo $item['money']==1?"selected=\"\"":'';?>>Webpoints</option></select></td></tr>
    </table>
    </td></tr>
    </table></form>
    <br />
    <?php
    if($itemi['desc']!="" || $itemi['grade']=="none")
    {
        if($itemi['desc']!="")
        {
        ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $itemi['desc'];?></td></tr></table>
    
    <?php
    }
    }
    else
    {
        if($i['bodypart']=="lhand")
            $i['bodypart']="shield";
        //try to find chest from armorsets
        $c=$sql->query("SELECT `chest` FROM `armorsets` WHERE `{$i['bodypart']}`='{$i['id']}'");
        if($sql->num_rows($c))
        {
            $chest_id=$sql->result($c);
            $i['desc']=$sql->result($sql->query("SELECT `desc` FROM `$webdb`.`all_items` WHERE `id`='$chest_id'"));
           ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $i['desc'];?></td></tr></table>
    <?php 
        }
    }

        ?>
        Comment:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php textbbcode('item','comment',$item['comment']);?></td></tr></table>
     <?php echo button('Save Changes','submit',1,false,'submit');?>
    <form action="?a=transfer" method="POST">
    <table><select name="char">
    <?php
    $sql->query("SELECT charId, char_name FROM characters WHERE account_name='{$_SESSION['account']}' AND online='0'");
    while($char=$sql->fetch_array())
    {
        echo '<option value="'.$char['charId'].'">'.$char['char_name'].'</option>';
    }
    
    ?>
    </select>
    <tr><td><input type="hidden" name="id" id="id" value="<?php echo $item['object_id'];?>" />
    <?php echo button('Send to Game','submit',1,false,'submit');?>
    </form></td></tr>
    </table>
    <?php
            
        }
        break;        
        case "viewbought":
        case "edit":
        case "delete":
            err('NOT YET DONE', 'THIS SECTION IS UNDER DEVELOPMENT');
            break;
        case "view":

            $i_id=getVar('id');
            $qry=$sql->query('SELECT * FROM l2web.webshop WHERE object_id=\''.$i_id.'\'');
            if(!$sql->num_rows($qry))
            {
                err('Error', 'Not found');
            }
            $item=$sql->fetch_array();
            
            $qry=$sql->query('SELECT * FROM l2web.all_items WHERE id=\''.$item['item_id'].'\'');
            $itemi=$sql->fetch_array();
            $addname=($itemi['addname']!='')?' - '.$itemi['adname']:'';
            if($item['owner']=='') $item['owner']='No Owner';
            head("WebShop - View Item");
            echo $content;
            ?>
            <table cellpadding="5" cellspacing="5" border="2" width="425px">
    <tr><td><img src="img/icons/<?php echo $itemi['icon1'];?>.png" alt="<?php echo $itemi['name'];?>" title="<?php echo $itemi['name'];?>" width="64" height="64"/></td>
    <td>
    <table border="1" width="315px">
    <tr><td>Name</td><td><?php echo $itemi['name'].$addname;?></td></tr>
    <tr><td>Type</td><td><?php echo $itemi['type'];?></td></tr>
    <tr><td>Body Part</td><td><?php echo $itemi['bodypart'];?></td></tr>
    <?php
    $grade=($itemi['grade']!='none')?"<img src=\"img/grade/{$itemi['grade']}-grade.png\" alt=\"{$itemi['grade']}\" title=\"{$itemi['grade']}\" />":"none";
    ?>
    <tr><td>Grade</td><td><?php echo $grade;?></td></tr>
    <?php
    if($item['enchant_level']!='' && $item['enchant_level']>0)
    {?>
    <tr><td>Enchant</td><td><?php echo $item['enchant_level'];?></td></tr><?php
    }
    ?>
    <tr><td>Count</td><td><?php echo $item['count'];?></td></tr>
    <tr><td>Price<br /> per 1 item</td><td><?php echo $item['money_count'];?> <?php echo ($item['money']==0)?'Adena':'WebPoints';?></td></tr>
    <tr><td>Owner</td><td><?php echo $item['owner'];?></td></tr>
    </table>
    </td></tr>
    </table>
    <br />
    <?php
    if($itemi['desc']!="" || $itemi['grade']=="none")
    {
        if($itemi['desc']!="")
        {
        ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $itemi['desc'];?></td></tr></table>
    
    <?php
    }
    }
    else
    {
        if($i['bodypart']=="lhand")
            $i['bodypart']="shield";
        //try to find chest from armorsets
        $c=$sql->query("SELECT `chest` FROM `armorsets` WHERE `{$i['bodypart']}`='{$i['id']}'");
        if($sql->num_rows($c))
        {
            $chest_id=$sql->result($c);
            $i['desc']=$sql->result($sql->query("SELECT `desc` FROM `$webdb`.`all_items` WHERE `id`='$chest_id'"));
           ?>
    Description:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $i['desc'];?></td></tr></table>
    <?php 
        }
    }
    if($item['comment']!='')
    {
        ?>
        Comment:<br />
    <table cellpadding="5" cellspacing="5" border="1" width="425px"><tr><td><?php echo $item['comment'];?></td></tr></table>
        <?php
    }
    ?>
    <form action="?a=buy" method="POST">
    <table><tr><td><input type="hidden" name="id" id="id" value="<?php echo $item['object_id'];?>" />
    Count: <input type="text" maxlength="3" id="count" name="count" size="3" title="Count" /><br />
    <?php echo button('Buy','submit',1,false,'submit');?>
    </form></td></tr>
    </table>
    <?php
    break;
        case "buy":
            $id=getVar('id');
            $count=getVar('count');
            head("WebShop - Buy Item");
            echo $content;
            $qry=$sql->query('SELECT * FROM l2web.webshop WHERE object_id=\''.$id.'\'');
            $item=$sql->fetch_array();
            $qry=$sql->query('SELECT * FROM l2web.all_items WHERE id=\''.$item['item_id'].'\'');
            $itemi=$sql->fetch_array();
            $count=getVar('count');
            if($count>$item['count'])$count=$item['count'];
            $sum=$count*$item['money_count'];
            if($item['money']==0) //adena
            {
                $qry=$sql->query('SELECT object_id, SUM(items.`count`) AS adena FROM characters , items WHERE characters.charId =  items.owner_id AND items.item_id =  \'57\' AND characters.account_name =  \''.$_SESSION['account'].'\'');
                //SELECT characters.charId, characters.char_name, items.object_id, items.`count`,items.loc FROM characters ,items WHERE characters.charId =  items.owner_id AND items.item_id =  '57' AND characters.account_name =  '80mxm08'
                //
                
                $itema=$sql->fetch_array();
                if($itema['adena']-$sum<0 || $count>$item['count'])
                {
                    if($itema['adena']-$sum<0)
                        msg('Error', 'You don\'t have '.$sum.' Adena <br /> You have '.$itema['adena'].' Adena in your account','error');
                    else
                        msg('Error', 'Incorrect count', 'error');
                }
                else
                {
                    $taked=0;
                    $left=$item['money_count'];
                    //decrease adena
                    $sql->query('SELECT characters.charId, characters.char_name, items.object_id, items.`count`,items.loc FROM characters ,items WHERE characters.charId =  items.owner_id AND items.item_id =  \'57\' AND characters.account_name =  \''.$_SESSION['account'].'\'');
                    
                    while($adena=$sql->fetch_array())
                    {
                        $cur=0;
                        if($adena['count']>=$left)
                        {
                            $cur=$left;
                        }
                        else
                        {
                            if($adena['count']=='0') continue;
                            if($left<$adena['count'])
                                $cur=$left;
                            else
                                $cur=$adena['count'];
                            
                        }
                        $left-=$cur;
                        $sql->query("UPDATE `items` SET `count` = `count`-'$cur' WHERE `object_id`='{$adena['object_id']}'");
                        if($left<=0)
                            break;
                    }
                    if($count<$item['count'])
                    {
                        $object_id=getConfig('webshop','inc','0')+1;
                        $sql->query('INSERT INTO `l2web`.`webshop` (`owner`, `object_id`, `item_id`, `count`, `loc`, `active`) VALUES (\''.$_SESSION['account'].'\', \''.$object_id.'\', \''.$item['item_id'].'\', \''.$count.'\', \'WEBINV\', \'0\')');
                        setConfig('webshop','inc',$object_id);
                        $sql->query('UPDATE `l2web`.`webshop` SET `count`=`count`-\''.$count.'\' WHERE `object_id`=\''.$item['object_id'].'\'');
                        $object_id=getConfig('webshop','inc','0')+1;
                        $sql->query('INSERT INTO `l2web`.`webshop` (`owner`, `object_id`, `item_id`, `count`, `loc`, `active`) VALUES (\''.$item['owner'].'\', \''.$object_id.'\', \'57\', \''.$sum.'\', \'WEBINV\', \'0\')');
                        setConfig('webshop','inc',$object_id);
                    }
                    else
                    {
                        $object_id=getConfig('webshop','inc','0')+1;
                        $sql->query('UPDATE `l2web`.`webshop` SET `owner`=\''.$_SESSION['account'].'\', `active`=\'0\' WHERE `object_id`=\''.$item['object_id'].'\'');
                        setConfig('webshop','inc',$object_id);
                        $sql->query('INSERT INTO `l2web`.`webshop` (`owner`, `object_id`, `item_id`, `count`, `loc`, `active`) VALUES (\''.$item['owner'].'\', \''.$object_id.'\', \'57\', \''.$count.'\', \'WEBINV\', \'0\')');
                    }
                    $body="You have bought [url=item.php?id={$item['item_id']}][img]img/icons/{$itemi['icon1']}.png[/img][/url][hr][url=webshop.php?a=viewbought&id=$object_id]View Your Item[/url]|[url=webshop.php]Buy Another[/url]";
                    $sql->query("INSERT INTO `l2web`.`messages` (`receiver`, `added`, `subject`, `msg`) VALUES ('{$_SESSION['account']}', NOW(), 'Webshop Item Buy', '$body')");
                    $body="Your item [url=item.php?id={$item['item_id']}][img]img/icons/{$itemadd['icon1']}.png[/img][/url] has been bought[hr]|[url=webshop.php?a=add]Add Another[/url]";
                    $sql->query("INSERT INTO `l2web`.`messages` (`receiver`, `added`, `subject`, `msg`) VALUES ('{$item['owner']}', NOW(), 'Webshop Item Sell', '$body')");
                    msg('Success', 'You have successfully bought item');
                }
            }
            else //webpoints
            {
                if($_SESSION['webpoints']-$sum<0)
                {
                    msg('Error', 'You don\'t have '.$sum.' webpoints <br /> You have '.$_SESSION['webpoints'].' webpoints','error');
                }
                else
                {
                    $sql->query('UPDATE `accounts` SET `webpoints`=`webpoints`-\''.$sum.'\' WHERE `login`=\''.$_SESSION['account'].'\'');
                    $_SESSION['webpoints']-=$sum;
                    $sql->query('UPDATE `accounts` SET `webpoints`=`webpoints`+\''.$sum.'\' WHERE `login`=\''.$item['owner'].'\'');
                    if($count<$item['count'])
                    {
                        $object_id=getConfig('webshop','inc','0')+1;
                        $sql->query('INSERT INTO `l2web`.`webshop` (`owner`, `object_id`, `item_id`, `count`, `loc`, `active`) VALUES (\''.$_SESSION['account'].'\', \''.$object_id.'\', \''.$item['item_id'].'\', \''.$count.'\', \'WEBINV\', \'0\')');
                        setConfig('webshop','inc',$object_id);
                        $sql->query('UPDATE `l2web`.`webshop` SET `count`=`count`-\''.$count.'\' WHERE `object_id`=\''.$item['object_id'].'\'');
                    }
                    else
                    {
                        $sql->query('UPDATE `l2web`.`webshop` SET `owner`=\''.$_SESSION['account'].'\', `active`=\'0\' WHERE `object_id`=\''.$item['object_id'].'\'');
                    }
                    $body="You have bought [url=item.php?id={$item['item_id']}][img]img/icons/{$itemi['icon1']}.png[/img][/url][hr][url=webshop.php?a=viewbought&id=$object_id]View Your Item[/url]|[url=webshop.php]Buy Another[/url]";
                    $sql->query("INSERT INTO `l2web`.`messages` (`receiver`, `added`, `subject`, `msg`) VALUES ('{$_SESSION['account']}', NOW(), 'Webshop Item Buy', '$body')");
                    $body="Your item [url=item.php?id={$item['item_id']}][img]img/icons/{$itemadd['icon1']}.png[/img][/url] has been bought[hr]|[url=webshop.php?a=add]Add Another[/url]";
                    $sql->query("INSERT INTO `l2web`.`messages` (`receiver`, `added`, `subject`, `msg`) VALUES ('{$item['owner']}', NOW(), 'Webshop Item Sell', '$body')");
                    msg('Success', 'You have successfully bought item');
                }
            }
            
        break;
        default:
        head("WebShop");
        echo $content;
        $type=getVar('type');
        $grade=getVar('grade');
            ?>
            <form action="" method="post">
            <table><tr><td>Name</td><td>Type</td><td>Grade</td><td></td></tr>
            <tr><td><input type="text" id="s" name="s" value="<?php echo getVar('s');?>" /></td>
            <td>
            <select id="type" name="type">
            <option value="" <?php echo ($type=='')?'selected="selected"':'';?>>All</option>
            <option value="armor" <?php echo ($type=='armor')?'selected="selected"':'';?>>Armor</option>
            <option value="weapon" <?php echo ($type=='weapon')?'selected="selected"':'';?>>Weapon</option>
            <option value="potion" <?php echo ($type=='potion')?'selected="selected"':'';?>>Potion</option>
            <option value="scroll" <?php echo ($type=='scroll')?'selected="selected"':'';?>>Scroll</option>
            </select></td><td>
            <select id="grade" name="grade">
            <option value="" <?php echo ($grade=='')?'selected="selected"':'';?>>All</option>
            <option value="no" <?php echo ($grade=='no')?'selected="selected"':'';?>>No Grade</option>
            <option value="d" <?php echo ($grade=="d")?' selected="selected"':'';?>>D-Grade</option>
            <option value="c" <?php echo ($grade=='c')?'selected="selected"':'';?>>C-Grade</option>
            <option value="b" <?php echo ($grade=='b')?'selected="selected"':'';?>>B-Grade</option>
            <option value="a" <?php echo ($grade=='a')?'selected="selected"':'';?>>A-Grade</option>
            <option value="s" <?php echo ($grade=='s')?'selected="selected"':'';?>>S-Grade</option>
            <option value="s80" <?php echo ($grade=='s80')?'selected="selected"':'';?>>S80-Grade</option>
            <option value="s84" <?php echo ($grade=='s84')?'selected="selected"':'';?>>S84-Grade</option>
            </select></td>
            <td>
            <input type="submit" value="Submit" />
            </td>
            </tr></table></form>
            <?php
        $i=0;
        $sql_add="";
        //die($type);
        if($type) $sql_add.="AND `type`='$type'";
        if($grade) $sql_add.="AND `grade`='$grade'"; 
        echo "<table border=\"1\">";
        echo "<tr><th>Icon</th><th>Name</th><th>Price</th><th>Owner</th><th>Action</th></tr>";
        $select=$sql->query("SELECT `owner`, `object_id`, `item_id`, `count`, `enchant_level`, `mana_left` , `time`, `money`, `money_count` FROM `{webdb}`.`webshop` WHERE `active`='1' $sql_add LIMIT $startlimit, {$CONFIG['settings']['TOP']}", array('webdb'=>$webdb));
        while($item=$sql->fetch_array($select))
        {
            $details=$sql->query("SELECT * FROM `{webdb}`.`all_items2` WHERE `id`='{$item['item_id']}'", array('webdb'=>$webdb));
            $item_d=$sql->fetch_array($details);
            $addname=($item_d['addname']=="")?"":" - ".$item_d['addname'];
            $price=($item['money']=="0")?" Adena":" WebPoints";
            $grade = $item_d["grade"];
            $grade = (!empty($grade) || $grade!="none") ? "&lt;img border=\\'0\\' src=\\'img/grade/" . $grade . "-grade.png\\' />" : "";
            $enchant = $item["enchant_level"] > 0 ? " +" . $item["enchant_level"] : "";
            //print_r($item);
            echo "<tr><td>";
            echo "<img src=\"img/icons/{$item_d['icon']}.png\" alt=\"{$item_d['name']}\" title=\"{$item_d['name']}\" onmouseover=\"Tip('&lt;img src=\'img/icons/{$item_d['icon']}.png\' /&rt;&lt;br /> {$item_d['desc']}',TITLE, '{$enchant} {$item_d['name']}$addname {$grade}', FONTCOLOR, '#FFFFFF',BGCOLOR, '#406072', BORDERCOLOR, '#666666', FADEIN, 500, FADEOUT, 500, FONTWEIGHT, 'bold')\" />";
            echo "</td><td>";
            
            echo "{$item_d['name']}$addname";
            
            echo "</td><td>";
            echo "{$item['money_count']} $price";
            echo "</td><td>";
            echo "{$item['owner']}";
            echo "</td><td>";
            echo "<a href=\"webshop.php?a=view&amp;id={$item['object_id']}\">View</a>";
            echo "</td></tr>";
        }
        echo "</table>";
        break;
    }
}
else
{
    msg($Lang['error'], $Lang['need_to_login'], 'error');
}
foot();
?>