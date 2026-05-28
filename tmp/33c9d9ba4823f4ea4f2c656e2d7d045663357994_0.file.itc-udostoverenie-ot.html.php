<?php
/* Smarty version 4.3.2, created on 2026-04-28 09:48:32
  from '/var/www/sed20.sedipo.ru/public_html/documents/certificate/itc-udostoverenie-ot.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69f08270a08442_13537594',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '33c9d9ba4823f4ea4f2c656e2d7d045663357994' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/certificate/itc-udostoverenie-ot.html',
      1 => 1753706973,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f08270a08442_13537594 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 210.01mm 297mm; style="padding-left: 0; margin-left: 5.01mm; margin-right: 5.01mm; margin-top: 5.01mm; margin-bottom: 5.01mm" }
	p {  text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: justify;  margin-bottom: 4px;  margin-top: 4px; }
	p.center { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: center;  margin-bottom: 4px;  margin-top: 4px; }
	p.left { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	h4.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	td { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; line-height: 96%;  }
	</style>
</head>
<body lang="ru-RU" text="#000000" link="#0066cc" vlink="#800000" dir="ltr">

	<table dir="ltr" align="left" width="100%"  cellpadding="7" cellspacing="0" border="1">

		<tr valign="top">
			<td  width="49%"  >
                <p class="center"  style="margin-bottom: 5px;  margin-top: 1px; text-transform: uppercase; font-size: 7pt;" >ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ<br /> «ИНЖЕНЕРНО-ТЕХНИЧЕСКИЙ ЦЕНТР «БЕЗОПАСНОСТЬ»</p>
                <h2 class="western"  style="margin-bottom: 5px;  margin-top: 5px;" ><b>УДОСТОВЕРЕНИЕ № <?php echo $_smarty_tpl->tpl_vars['user']->value['certificate_num'];?>
</b></h2>

<table width="100%" border="0" cellpadding="3" cellspacing="3">
<tr  >
    <td  valign="top" width="30%">
        ФИО:
    </td>
    <td>
        <b><?php echo $_smarty_tpl->tpl_vars['user']->value['lastname'];?>
<br />
        <?php echo $_smarty_tpl->tpl_vars['user']->value['firstname'];?>
<br />
        <?php echo $_smarty_tpl->tpl_vars['user']->value['middlename'];?>
</b>
    </td>
</tr>    

<tr>
    <td  valign="top" width="30%">
        Должность:
    </td>
    <td>
        <b><?php echo $_smarty_tpl->tpl_vars['user']->value['position_name'];?>
</b>
    </td>
</tr>  
<tr>
    <td  valign="top" width="30%" height="20">
        <p class="western" style="margin-right: 4px;" >Организация:</p>
    </td>
    <td  valign="top" height="20">
        <p class="western" ><b><?php echo $_smarty_tpl->tpl_vars['user']->value['counterparty_shortname'];?>
</b></p>
    </td>
</tr>  

<tr>
    <td width="30%">
        Протокол:
    </td>
    <td>
        <span class="left" style="font-size: smaller;">№ <?php echo $_smarty_tpl->tpl_vars['cert']->value['protocol_num'];?>
 от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['cert']->value['date_protocol'],"%02e.%m.%Y");?>
</span>
    </td>
</tr>    

<tr>
    <td colspan="2" class="left" style=" margin-bottom: 2px;  margin-top: 10px; font-size: 5pt; line-height: 96%;" >
         Аккредитация Минтруда России на оказание услуг в области охраны труда (регистрационный номер 9777 от 12.08.2024)
    </td>
</tr>    
</table>

			</td>

<!--<td  width="2%"> <br /> </td>-->

			<td   width="49%">
<table width="100%" border="0">
<tr>
    <td colspan="2" height="70" valign="top">
        <p class="western" style=" margin-bottom: 4px;  margin-top: 4px; font-size: 8pt;" >Он(а) обучался(ась) по программе:</p>
        <p class="western" style=" margin-bottom: 4px;  margin-top: 4px;  font-size: 8pt;" > <i>«<?php echo $_smarty_tpl->tpl_vars['cert']->value['name'];?>
»</i> </p>
    </td>
</tr>    

<tr valign="bottom">
    <td  valign="top">
        <p class="western" style="margin-bottom: 5x;  margin-top: 35px;  font-size: 8pt;" >Председатель <br/>комиссии:</p>
    </td>
    <td>
        <p class="western" style="margin-bottom: 5x;  margin-top: 5px;  font-size: 8pt;" ><nobr><?php echo $_smarty_tpl->tpl_vars['teachers_commission']->value['chairman_short_b'];?>
</nobr></p>
    </td>
</tr>    
<tr>
    <td  valign="top">
        <p class="western" >М.П.</p>
    </td>
    <td>

<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?> 
<div style="position: relative; top: 0px; right: 0px;">        
<!--<div style="position: absolute; top: -80px; right: 150px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="70px" align="top" ></div>-->
<div style="position: absolute; top: -115px; right: 200px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
</div>
<?php }?>

    </td>
</tr>

<tr>
    <td colspan="2"  >
<div style="position: relative; top: 0px; right: 0px;">        
<div style="position: absolute; top: -26px; right: 0px; ">       
   <p class="center" style=" margin-bottom: 7px;  margin-top: 15px;  margin-right: 30px; text-align: right;  font-size: 7pt;" > <i>Действительно до <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['cert']->value['date_finish'],"%02e.%m.%Y");?>
</i></p>
</div>
    </td>
</tr>    

</table>


			</td>
		</tr>
	</table><br/>



</body><?php }
}
