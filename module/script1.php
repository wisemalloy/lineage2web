<?php
$filename="data.txt";
//���� ���� ����������
if ( file_exists($filename) )
{
     //������ ��������� � ������
     $data=file($filename);
     echo "<table width=500 align=center cellspacing=0 cellpadding=2 border=1><tr><td><b>Nick</b></td><td><b>Message</b></td></tr>";
     //������� ������� $data
     foreach($data as $content)
     {
         //��� - �������� ��������
         //�������� �� ������� ������� ������
         if($content==="")
         {
             continue;
         }
         //��������� ������ �� ����� �� ������������, � ���� � ������
         //������ ������� "=" ���, ��������� ����� � ��������� (������)
         if(!$values=explode("=", $content))
         {
             continue;
         }
         echo "<tr>";
         //���������� - ��� ��� ��������������� ����
         foreach($values as $text)
         {
             echo" <td valign=top>";
             //�������� "���������� ������" �������
             $text=str_replace("*#!equals!#*", "=", $text);
             echo $text;
             echo" </td>";
         }
         echo " </tr>";
     }
     echo "</table> ";
}
else
{
     echo "<b>You dont have new messages</b><br><br>";
}
?>
    

 <b>Delete all messages:</b>
<form name='form' action='./index.php?id=del' method='POST'>
          <br>
      
         <input type=submit name='action' value='Delete'>
     </form>


</font>
</body>
</html>
<!-- end of msg.php--> 
