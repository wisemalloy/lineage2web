<?php
Define('INWEB', True);
include("include/config.php");
head('Castles Map',0);

/********  GIRAN  *************/
$giranOwner = "No Owner";
$giranSiegeDate = " ... ";
$giranTax ="";
/*********  OREN  **************/
$orenOwner = "No Owner";
$orenSiegeDate = " ... ";
$orenTax ="";
/**********  ADEN  **************/
$adenOwner = "No Owner";
$adenSiegeDate = " ... ";
$adenTax ="";
/********  Gludio  **************/
$gludioOwner = "No Owner";
$gludioSiegeDate = "...";
$gludioTax ="";
/**********  DION  ***************/
$dionOwner = "No Owner";
$dionSiegeDate = " ... ";
$dionTax ="";
/********  INNADRIL  *************/
$innadrilOwner = "No Owner";
$innadrilSiegeDate = " ... ";
$innadrilTax ="";
/********  GODDARD  *************/
$godadOwner = "No Owner";
$godadSiegeDate = " ... ";
$godadTax ="";
/*********************************/

$sql = mysql_query("SELECT castle.name, clan_data.clan_name FROM castle,clan_data WHERE clan_data.hasCastle=castle.id");
while($row= mysql_fetch_array($sql,MYSQL_ASSOC)){
	switch($row['name']){
		case 'Giran':$giranOwner=$row['clan_name'];break;
		case 'Oren':$orenOwner=$row['clan_name'];break;	
		case 'Aden':$adenOwner=$row['clan_name'];break;
		case 'Gludio':$gludioOwner=$row['clan_name'];break;
		case 'Dion':$dionOwner=$row['clan_name'];break;
		case 'Innadril':$innadrilOwner=$row['clan_name'];break;
		case 'Goddard':$godadOwner=$row['clan_name'];break;
	}
}
$sql = mysql_query("SELECT name,taxPercent,siegeDate FROM castle");
while($row=mysql_fetch_array($sql,MYSQL_ASSOC)){
	switch($row['name']){
		case 'Giran':$giranTax=$row['taxPercent'].'%';
		$giranSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Oren':$orenTax=$row['taxPercent'].'%';
		$orenSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Aden':$adenTax=$row['taxPercent'].'%';
		$adenSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Gludio':$gludioTax=$row['taxPercent'].'%';
		$gludioSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Dion':$dionTax=$row['taxPercent'].'%';
		$dionSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Innadril':$innadrilTax=$row['taxPercent'].'%';
		$innadrilSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;
		case 'Goddard':$godadTax=$row['taxPercent'].'%';
		$godadSiegeDate=date('D\, j M Y H\:i',$row['siegeDate']/1000);break;	
	}
}	     
?>
<script language="JavaScript" type="text/JavaScript">
<!--
oreninfo = '<strong>Oren Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $orenOwner;?><br/><strong>Tax:</strong><br/><? echo $orenTax;?><br/><strong>Siege:</strong><br/><? echo $orenSiegeDate;?><br/>';
adeninfo =  '<strong>Aden Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $adenOwner;?><br/><strong>Tax:</strong><br/><? echo $adenTax;?><br/><strong>Siege:</strong><br/><? echo $adenSiegeDate;?><br/>';
innadrilinfo =  '<strong>Innadril Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $innadrilOwner;?><br/><strong>Tax:</strong><br/><? echo $innadrilTax;?><br/><strong>Siege:</strong><br/><? echo $innadrilSiegeDate;?><br/>';
dioninfo = '<strong>Dion Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $dionOwner;?><br/><strong>Tax:</strong><br/><? echo $dionTax;?><br/><strong>Siege:</strong><br/><? echo $dionSiegeDate;?><br/>';
giraninfo =   '<strong>Giran Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $giranOwner;?><br/><strong>Tax:</strong><br/><? echo $giranTax;?><br/><strong>Siege:</strong><br/><? echo $giranSiegeDate;?><br/>';
gludioinfo= '<strong>Gludio Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $gludioOwner;?><br/><strong>Tax:</strong><br/><? echo $gludioTax;?><br/><strong>Siege:</strong><br/><? echo $gludioSiegeDate;?><br/>';
godadinfo= '<strong>Goddard Castle</strong><br/><br/><strong>Owner:</strong><br/><?echo $godadOwner;?><br/><strong>Tax:</strong><br/><? echo $godadTax;?><br/><strong>Siege:</strong><br/><? echo $godadSiegeDate;?><br/>';
function displayControlText (name, state) {
	var object = "castleinfo";
	var innertext;
	switch (name){
		case 'oren': innertext=oreninfo;break;
		case 'aden': innertext=adeninfo;break;
		case 'innadril': innertext=innadrilinfo;break;
		case 'dion': innertext = dioninfo;break;
		case 'giran': innertext=giraninfo;break;
		case 'gludio': innertext=gludioinfo;break;
		case 'godad': innertext=godadinfo;break;
		}	
	if (state == "show"){
		//alert('showing');
    	if (document.getElementById && document.getElementById(object) != null) {
		 document.getElementById(object).innerHTML = innertext;		
         document.getElementById(object).style.visibility='visible';
         document.getElementById(object).style.display='block';
		}		
	} else {
		//alert('hiding');
    	if (document.getElementById && document.getElementById(object) != null) {
         document.getElementById(object).style.visibility='hidden';
         document.getElementById(object).style.display='none';
		}			
	}
}

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0

  var argv = MM_showHideLayers.arguments;
  var name = argv[0]; 
  var state = argv[2]; 
  
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
	
	displayControlText(name, state);
}
// -->
</script>

<div id="Background" style="position:relative; width:739px; height:799px; z-index:1; margin:10px 10px -40px 10px; top:-25px; overflow:hidden;"><img src="img/castles/background.jpg" width="739" height="799" border="0" usemap="#Map" alt="" />
  <div id="adeninfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">aden</div>
  <div id="dioninfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">dion</div>
  <div id="giraninfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">giran</div>
  <div id="gludioinfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">gludio</div>
  <div id="innadrilinfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">innadril</div>
  <div id="oreninfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">oren</div>        
  <div id="godadinfo" style="position:absolute; width:126px; height:122px; z-index:8; left: 33px; top: 46px;">godad</div>         
  
  <div id="castleinfo">To view information Move your pointer over to the Castle</div>          

  <div id="aden" style="position:absolute; width:401px; height:578px; z-index:2; left: 233px; top: 203px; visibility: hidden;"><img src="img/castles/aden.gif" width="401" height="578" border="0" usemap="#Map2" alt="" /></div>
  <div id="godad" style="position:absolute; width:25px; height:43px; z-index:8; left: 603px; top: 80px; visibility: hidden;"><img src="img/castles/goddard.gif" width="25" height="43" onmouseover="MM_showHideLayers('godad','','show')" onmouseout="MM_showHideLayers('godad','','hide')" alt="" /></div>
  <div id="dion" style="position:absolute; width:85px; height:99px; z-index:3; left: 305px; top: 529px; visibility: hidden;"><img src="img/castles/dion.gif" width="58" height="72" onmouseover="MM_showHideLayers('dion','','show')" onmouseout="MM_showHideLayers('dion','','hide')" alt="" /></div>
  <div id="giran" style="position:absolute; width:131px; height:47px; z-index:4; left: 455px; top: 523px; visibility: hidden;"><img src="img/giran.gif" width="105" height="36" onmouseover="MM_showHideLayers('giran','','show')" onmouseout="MM_showHideLayers('giran','','hide')" alt="" /></div>
  <div id="gludio" style="position:absolute; width:199px; height:349px; z-index:5; left: 92px; top: 445px; visibility: hidden;"><img src="img/castles/gludio.gif" width="179" height="317" onmouseover="MM_showHideLayers('gludio','','show')" onmouseout="MM_showHideLayers('gludio','','hide')" alt="" /></div>
  <div id="innadril" style="position:absolute; width:57px; height:105px; z-index:6; left: 519px; top: 687px; visibility: hidden;"><img src="img/castles/innadril.gif" width="43" height="94" onmouseover="MM_showHideLayers('innadril','','show')" onmouseout="MM_showHideLayers('innadril','','hide')" alt="" /></div>
  <div id="oren" style="position:absolute; width:200px; height:115px; z-index:7; left: 295px; top: 221px; visibility: hidden;"><img src="img/castles/oren.gif" width="195" height="132" border="0" onmouseover="MM_showHideLayers('oren','','show')" onmouseout="MM_showHideLayers('oren','','hide')" alt="" /> </div>
</div>
<map name="Map2" id="Map2">
  <area shape="rect" coords="400,4,438,53" onmouseover="MM_showHideLayers('aden','','show')" onmouseout="MM_showHideLayers('aden','','hide')" alt="" />
</map>
<map name="Map" id="Map">
  <area shape="rect" coords="552,102,680,150" onmouseover="MM_showHideLayers('godad','','show')" onmouseout="MM_showHideLayers('godad','','hide')" alt="" />
  <area shape="rect" coords="416,263,526,319" onmouseover="MM_showHideLayers('oren','','show')" onmouseout="MM_showHideLayers('oren','','hide')" alt="" />
  <area shape="rect" coords="197,423,300,487" onmouseover="MM_showHideLayers('gludio','','show')" onmouseout="MM_showHideLayers('gludio','','hide')" alt="" />
  <area shape="rect" coords="523,514,660,560" onmouseover="MM_showHideLayers('giran','','show')" onmouseout="MM_showHideLayers('giran','','hide')" alt="" />
  <area shape="rect" coords="328,546,451,589" onmouseover="MM_showHideLayers('dion','','show')" onmouseout="MM_showHideLayers('dion','','hide')" alt="" />
  <area shape="rect" coords="488,725,615,789" onmouseover="MM_showHideLayers('innadril','','show')" onmouseout="MM_showHideLayers('innadril','','hide')" alt="" />
  <area shape="rect" coords="586,203,725,247" onmouseover="MM_showHideLayers('aden','','show')" onmouseout="MM_showHideLayers('aden','','hide')" alt="" />
</map>
<?php
foot(0);
?>