<hr />
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
<img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" />
</a>
<script src="http://wos.lv/d.php?11603" type="text/javascript"></script>
<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">
<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="<?php echo $Lang['ValidCSS'];?>!" />
</a>
<br />
<a href="http://la2.mmotop.ru/vote/20088/" target="_blank"><img src="http://la2.mmotop.ru/images/88x31w_la2.png" title="Рейтинг серверов Lineage 2" alt="Рейтинг серверов Lineage 2" border="0" /></a>
<a href="http://games.top.org/lineage-2/" title="Lineage 2 Private Servers (Downloads) - TOP.ORG"><img style="border:none;" src="http://img1.top.org/toporg_12309.gif" alt="Lineage 2 Private Servers (Downloads) - TOP.ORG" /></a>
<a href="http://www.webhostingpages.com/directory/" title="Web Hosting Directory"><img src="http://www.webhostingpages.com/images/uptime_2.gif" style="height:15px;width:80px;" alt="Web Hosting Directory" /></a>
<?php
if($foot){
?>
</td>
<td style="background-image: url(skins/<?php echo $skin;?>/img/t_h_r_b.gif); background-repeat: repeat-y;">&nbsp;</td></tr>
<tr>
<td><img width="40" height="28" alt="" src="skins/<?php echo $skin;?>/img/t_b_lc.gif" /></td>
<td style="background-image: url(skins/<?php echo $skin;?>/img/t_b_c.gif);" colspan="5">&nbsp;</td>
<td><img width="40" height="28" alt="" src="skins/<?php echo $skin;?>/img/t_b_rc.gif" /></td>
</tr></tbody></table></td>
<td width="15%" align="center" valign="top">
<?php
//пароль
includeBlock('stats', $Lang['stats'], true);
includeBlock('top10', $Lang['top10'], true);
?>
</td></tr></table></td></tr><?php }else{ ?></td></tr><?php } ?>
<tr align="center" valign="top">
<td align="center" valign="middle"><b>Lineage II</b> is a trademark of NCsoft Corporation. Copyright © <b>NCsoft Corporation</b>. All rights reserved.<br /><?php echo $Config['CopyRight']; ?></td>
</tr>
<?php
$timeparts = explode(" ",microtime());
$endtime = $timeparts[1].substr($timeparts[0],1);
?>
<tr><td align="center"><?php
echo sprintf($Lang['page_generated'], bcsub($endtime,$starttime,6));
?></td></tr>
</table></body></html>