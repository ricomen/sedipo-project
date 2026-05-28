<?php
/* Smarty version 4.3.2, created on 2026-05-19 07:32:56
  from '/var/www/sed20.sedipo.ru/public_html/documents/certificate/itc-udostoverenie-visota.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_6a0c1228a86362_07007591',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '229a6459e6af651ef27f77d174b8ba06a02dffff' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/certificate/itc-udostoverenie-visota.html',
      1 => 1753707027,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a0c1228a86362_07007591 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 210.01mm 297mm; style="padding-left: 0; margin-left: 5.01mm; margin-right: 5.01mm; margin-top: 5.01mm; margin-bottom: 5.01mm }
	p {  text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: justify;  margin-bottom: 2px;  margin-top: 2px;  line-height: 95%;  }
	p.center { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: center;  margin-bottom: 2px;  margin-top: 2px;  line-height: 95%;  }
	p.left { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: left;   margin-bottom: 1px;  margin-top: 1px;  line-height: 95%;  }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 13pt; text-align: center }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	h4.western { font-family: "DejaVu Serif", serif; font-size: 8pt; line-height: 80%; text-align: center }
	td { font-family: "DejaVu Serif", serif; font-size: 7pt; text-align: left }
	</style>
</head>
<body lang="ru-RU" text="#000000" link="#0066cc" vlink="#800000" dir="ltr">

	<table dir="ltr" align="left" width="100%"  cellpadding="7" cellspacing="0" border="1">

		<tr valign="top">
			<td  width="50%"  >
                <p class="center"  style="margin-bottom: 2px;  margin-top: 0px; text-transform: uppercase; font-size: 7pt;" ><b>ООО «ИНЖЕНЕРНО-ТЕХНИЧЕСКИЙ ЦЕНТР <br />«БЕЗОПАСНОСТЬ»</b></p>
                <h2 class="western"  style="margin-bottom: 0;  margin-top: 5px;" ><b>УДОСТОВЕРЕНИЕ №  <?php echo $_smarty_tpl->tpl_vars['user']->value['certificate_num'];?>
</b></h2>
                <h4 class="western"  style="margin-bottom: 5px;  margin-top: 0;" >о допуске к работам на высоте</h4>


<table width="100%" border="0">
<tr  >
    <td width="35%"  valign="top" style="border-top: 1px solid #000000; border-bottom:  1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;" >
        <p class="western" > <br /></p>
    </td>
    <td>
        <table  width="100%"  border="0">
        <tr><td style="padding-left: 8px;  line-height: 80%; padding-top: 0px; padding-bottom: 0px; "><b>Фамилия</b></td><td style="line-height: 80%; padding-top: 0px; padding-bottom: 0px; font-size: larger;"> <?php echo $_smarty_tpl->tpl_vars['user']->value['lastname'];?>
</td></tr>
        <tr><td style="padding-left: 8px;  line-height: 80%;  padding-top: 0px; padding-bottom: 0px; "><b>Имя</b></td><td style="line-height: 80%; padding-top: 0px; padding-bottom: 0px; font-size: larger;"> <?php echo $_smarty_tpl->tpl_vars['user']->value['firstname'];?>
</td></tr>
        <tr><td valign="top" style="padding-left: 8px; line-height: 80%;  padding-top: 0px; padding-bottom: 0px;  "><b>Отчество</b></td><td style="line-height: 80%; padding-top: 0px; padding-bottom: 0px; font-size: larger;"> <?php echo $_smarty_tpl->tpl_vars['user']->value['middlename'];?>
<br /><small><small><small>(при наличии)</small></small></small></td></tr>
        <tr><td colspan="2"  style="line-height: 80%; padding-top: 8px; padding-bottom: 2px; "><center><b><?php echo $_smarty_tpl->tpl_vars['user']->value['position_name'];?>
</b><br /><small><small>(профессия, должность)</small></small></center></td></tr>
        <tr><td colspan="2" style="line-height: 80%; padding-top: 2px; padding-bottom: 0px; "><center><b><?php echo $_smarty_tpl->tpl_vars['user']->value['counterparty_shortname'];?>
</b><br /><small><small>(организация)</small></small></center></td></tr>
        <tr><td colspan="2"> <table  width="100%"  border="0"><tr><td><center><b>Дата выдачи</b> <br /><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['cert']->value['date_protocol'],"%02e.%m.%Y");?>
 </center></td><td><center><b>Действительно до</b> <br /><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['cert']->value['date_finish'],"%02e.%m.%Y");?>
 </center></td></tr></table> </td></tr>
        <tr><td colspan="2" style="padding-left: 8px; line-height: 80%; padding-top: 4px;  padding-bottom: 0px; margin-bottom: 0px; " > <b>Личная подпись      _______________________</b> </td></tr>
        </table>
    </td>
</tr>    

</table>
			</td>

			<td  width="50%">
<table width="100%" border="0">
<tr>
    <td colspan="2">
        <p class="western" style=" margin-bottom: 0px;  margin-top: 0px; " ><b>Прошел (ла):</b></p>
        <p class="left" style=" margin-bottom: 0px;  margin-top: 0px; " >- обучение безопасным методам и приемам выполнения работ на высоте</p>
        <p class="left" style=" margin-bottom: 0px;  margin-top: 0px; " >- практическое обучение  продолжительностью<u> <?php echo $_smarty_tpl->tpl_vars['cert']->value['hours_p'];?>
 ч.</u></p>
        <p class="center" style=" margin-bottom: 2px;  margin-top: 0px; text-align: right; margin-right: 15px; " > <small><small>(количество часов)</small></small> </p>
    </td>
</tr>    

<tr>
    <td colspan="2">
        <p class="western" style=" margin-bottom: 4px;  margin-top: 4px; font-size: 8;" >Решением экзаменационной  комиссии может быть допущен(а) к работам <u>на высоте в соответствии с должностными обязанностями</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><small><small>(наименование работы)</small></small></p>
        <!--<p class="center" style=" margin-bottom: 4px;  margin-top: 0px; " > <small><small>(наименование работы)</small></small> </p>-->
        <p class="western" style=" margin-bottom: 8px;  margin-top: 8px; margin-right: 20px;" > <?php echo $_smarty_tpl->tpl_vars['cert']->value['competence'];?>
 </p>
        <p class="left" style=" margin-bottom: 4px;  margin-top: 4px;"><b>Основание:</b> протокол № <?php echo $_smarty_tpl->tpl_vars['cert']->value['protocol_num'];?>
 от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['cert']->value['date_protocol'],"%02e.%m.%Y");?>
</p>
    </td>
</tr>    


<tr valign="bottom">
    <td  valign="top">
        <p class="western" style="margin-bottom: 0;  margin-top: 8px;" ><nobr><b>Председатель комиссии</b></nobr></p>
    </td>
    <td>
        <p class="western" style="margin-bottom: 0;  margin-top: 5px;" ><nobr><b><?php echo $_smarty_tpl->tpl_vars['teachers_commission']->value['chairman_short_b'];?>
</b></nobr></p>
    </td>
</tr>    
<tr>
    <td  valign="top">
        <p class="western" style="margin-bottom: 0;  margin-top: 5px;" ><b>М.П.</b></p>
    </td>
    <td>

<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?> 
<div style="position: relative; top: 0px; right: 0px;">        
<!--<div style="position: absolute; top: -70px; right: 130px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis-manager.png" width="120px" align="top" ></div>-->
<div style="position: absolute; top: -100px; right: 190px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="130px" align="top" ></div>
</div>
<?php }?>

    </td>
</tr>

</table>


			</td>
		</tr>
	</table><br/>



</body><?php }
}
