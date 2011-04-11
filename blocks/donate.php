<?php
if (!defined('IN_BLOCK')) {
    header("Location: ../index.php");
    exit();
}
$cachefile='blocks/donate';
if($cache->needUpdate($cachefile))
{
    $content = $tpl->parsetemplate('blocks/donate', NULL, 1);
    $cache->updateCache($cachefile, $content);
    
    echo $content;
}
else
{
    echo $cache->getCache($cachefile);
}
?>