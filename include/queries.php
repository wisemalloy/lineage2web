<?php
if (!defined('INCONFIG')) {
    Header("Location: ../index.php");
    die();
}
$q = array(
    0 => 'SELECT * FROM `{{table}}`.`config`;',
    1 => 'SELECT `ID`, `Name`, `DataBase` FROM `{{table}}`.`gameservers` WHERE `active` = \'true\'',
    2 => 'SELECT * FROM `{{table}}`.`gameservers` WHERE `active` = \'true\';',
    3 => 'SELECT * FROM `{{table}}`.`gameservers` INNER JOIN `{{table}}`.`gameserver_info` USING (`ID`) WHERE `active` = \'true\';',
    4 => '',
    5 => '',
    6 => '',
    7 => '',
    8 => '',
    9 => '',
    100 => 'SELECT count(`login`) FROM `accounts`;',
    101 => '',
    102 => '',
    103 => '',
    104 => '',
    105 => '',
    106 => '',
    107 => '',
    108 => '',
    109 => '',
    200 => 'SELECT `charId`, `char_name`, `sex` FROM `{{table}}`.`characters` WHERE `accesslevel`=\'0\'  ORDER BY `exp` DESC LIMIT {{limit}};',
    201 => 'SELECT count(`clan_id`) FROM `{{table}}`.`clan_data`;',
    202 => 'SELECT count(`charId`) FROM `{{table}}`.`characters`;',
    203 => 'SELECT count(`online`) FROM `{{table}}`.`characters` WHERE `online` = \'1\' AND `accesslevel`=\'0\';',
    204 => 'SELECT count(`charId`) FROM `{{table}}`.`characters` WHERE `online` = \'1\' AND `accesslevel`>\'0\';',
    205 => '',
    206 => 'SELECT count(`charId`) FROM `{{table}}`.`seven_signs` WHERE `cabal` LIKE \'{{limit}}\'',
    207 => 'SELECT `active_period`, `date`, `avarice_dawn_score`, `gnosis_dawn_score`, `strife_dawn_score`, `avarice_dusk_score`, `gnosis_dusk_score`, `strife_dusk_score`, `avarice_owner`, `gnosis_owner`, `strife_owner` FROM `{{table}}`.`seven_signs_status`',
    208 => '',
    209 => ''
);
?>