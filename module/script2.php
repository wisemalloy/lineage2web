<?php
include("module/stat-menu.php");

//��� ��������� ��� ���������
$header="";

//��� ������ � �������������� � "�����������" ���������
$escape=array
(
     "\n" => "",
     "\r" => "",
     "="    => "*#!equals!#*"
);
//�������� �� �������
if( empty($_POST['text']) || empty($_POST['textarea']) )
{
     echo $header;
     echo "One or both fields empty.<br>";
     echo "<input type=button value='Back' OnClick='javascript:history.back()'>";
     exit;
}
//������� ������� ����
if(!$handle=fopen('module/data.txt', 'at'))
{
     echo $header;
     echo "Cannot open File.<br>";
     echo "<input type=button value='Back' OnClick='javascript:history.back()'>";
     exit;
}

//���������� ��� ���������� � ������ - ����� �� ������ �� �� ��� ����
$content='['.getenv("REMOTE_ADDR").']'.$_POST["text"].'*#!divider!#*'.$_POST["textarea"];

//�������� �������� ����� �� <br />
//� ��������� ��� ������ ������ $escape
$content=nl2br($content);
$content=strtr($content, $escape);

//��������� ������ �� ��������� *#!divider!#*, �����
//����������� �� ����� "=" � ��������� � ����� "\n"
$content=implode("=", explode("*#!divider!#*",
$content))."\n";

//������� ���� � ����������� ������� HTML  - ���������� �� XSS
$content=strip_tags($content);
$content=htmlspecialchars($content, ENT_QUOTES);

//��������� ����
flock($handle, LOCK_EX);

//����� � ����
if(fwrite($handle, $content) === FALSE)
{
     echo $header;
     echo "Cannot add things to file";

     exit;
}

//������������ � ��������� ����
flock($handle, LOCK_UN);
fclose($handle);
{
     echo $header;
     echo "<br><br><b>Your message have sended to Administration</b>.<br>";  
}
?>
<form name='form' action='./index.php?id=gm' method='POST'><br />
<input type="submit" name="action" value="Back" /></form>