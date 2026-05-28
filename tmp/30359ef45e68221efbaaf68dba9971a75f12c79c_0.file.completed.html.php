<?php
/* Smarty version 4.3.2, created on 2026-04-13 06:50:24
  from '/var/www/sed20.sedipo.ru/public_html/documents/buhdoc/completed.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69dc9230c2cf58_61552896',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30359ef45e68221efbaaf68dba9971a75f12c79c' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/buhdoc/completed.html',
      1 => 1769689264,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69dc9230c2cf58_61552896 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 210.01mm 297mm; margin: 10mm }
		p { font-family: "DejaVu Serif", serif; font-size: 12pt;  margin-top:  0.23mm;  margin-bottom: 0.23mm; background: transparent }
		
	</style>
</head>
<body lang="ru-RU" link="#000080" vlink="#800000" dir="ltr">
<p align="left" style="font-size: 14pt; padding-left: 16px;">
 	<b>Акт № <?php echo $_smarty_tpl->tpl_vars['order_data']->value['invoice'];?>
   от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order_data']->value['date_completed'],"%d.%m.%Y");?>
 </b>
 	
</p>
<hr />

<table width="100%" cellpadding="0" cellspacing="0">
	<tr style="border: none;">
		<td style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt; ">
			Исполнитель:</p>
		</td>
		<td valign="top" style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt; padding-bottom: 15px;">
			<b>
			<?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
, ИНН  <?php echo $_smarty_tpl->tpl_vars['self_data']->value['inn'];?>
, <?php echo $_smarty_tpl->tpl_vars['self_data']->value['addres1'];?>
, 
			<?php echo $_smarty_tpl->tpl_vars['self_data']->value['checking_account'];?>
 в банке <?php echo $_smarty_tpl->tpl_vars['self_data']->value['bank'];?>
, БИК  <?php echo $_smarty_tpl->tpl_vars['self_data']->value['bik'];?>
, к/с <?php echo $_smarty_tpl->tpl_vars['self_data']->value['correspondent_account'];?>

		</b></p>
		</td>
	</tr>

	<tr>
		<td  style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;" >
			Заказчик:</p>
		</td>
		<td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;  padding-bottom: 15px;" >
			<b>
                            <?php if ($_smarty_tpl->tpl_vars['order_data']->value['counterparty_id'] > 1) {?>
			            <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>
, ИНН  <?php echo $_smarty_tpl->tpl_vars['order_data']->value['inn'];?>
, КПП <?php echo $_smarty_tpl->tpl_vars['order_data']->value['kpp'];?>
, <?php echo $_smarty_tpl->tpl_vars['order_data']->value['addres1'];?>
, тел.: <?php echo $_smarty_tpl->tpl_vars['payer']->value['phone'];?>
,<br />
			            <?php echo $_smarty_tpl->tpl_vars['order_data']->value['checking_account'];?>
 в банке <?php echo $_smarty_tpl->tpl_vars['order_data']->value['bank'];?>
, БИК  <?php echo $_smarty_tpl->tpl_vars['order_data']->value['bik'];?>
, к/с <?php echo $_smarty_tpl->tpl_vars['order_data']->value['correspondent_account'];?>

                            <?php } else { ?>
                                    <?php echo $_smarty_tpl->tpl_vars['customer']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['customer']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['customer']->value['middlename'];?>
,<br />
                                    СНИЛС <?php echo $_smarty_tpl->tpl_vars['customer']->value['snils'];?>
 
                            <?php }?>
			</b></p>
		</td>
	</tr>
    <?php if ($_smarty_tpl->tpl_vars['order_data']->value['payer_id'] > 0) {?> 
	<tr>
		<td  style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;" >
			Плательщик:</p>
		</td>
		<td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;  padding-bottom: 15px;" >
			<b>
                          <?php if ($_smarty_tpl->tpl_vars['order_data']->value['payer_id'] > 1) {?> 
			    <?php echo $_smarty_tpl->tpl_vars['payer']->value['name'];?>
, ИНН  <?php echo $_smarty_tpl->tpl_vars['payer']->value['inn'];?>
, КПП <?php echo $_smarty_tpl->tpl_vars['payer']->value['kpp'];?>
, <?php echo $_smarty_tpl->tpl_vars['payer']->value['addres1'];?>
,
			    <?php echo $_smarty_tpl->tpl_vars['payer']->value['checking_account'];?>
 в банке <?php echo $_smarty_tpl->tpl_vars['payer']->value['bank'];?>
, БИК  <?php echo $_smarty_tpl->tpl_vars['payer']->value['bik'];?>
, к/с <?php echo $_smarty_tpl->tpl_vars['payer']->value['correspondent_account'];?>

                          <?php } else { ?>
                            <?php echo $_smarty_tpl->tpl_vars['payer']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['payer']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['payer']->value['middlename'];?>
,<br />
                            СНИЛС <?php echo $_smarty_tpl->tpl_vars['payer']->value['snils'];?>
 
                          <?php }?>
			</b></p>
		</td>
	</tr>
    <?php }?>	
	
	<tr>
	    <td  style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;" >
			Основание:</p>
		</td>
		<td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="left" style="font-size: 9pt;  padding-bottom: 15px;" >
			<b>
			    <br />
			    Договор №  <?php if ($_smarty_tpl->tpl_vars['order_data']->value['counterparty_id'] > 1) {?> <?php echo $_smarty_tpl->tpl_vars['order_data']->value['contract_number'];?>
 <?php } else { ?> CED-ОУ/ФЛ-<?php echo $_smarty_tpl->tpl_vars['order_data']->value['order_num'];?>
   <!--<?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
--> <?php }?>
			</b></p>
		</td>
	</tr>
</table> 

<table width="100%" cellpadding="0" cellspacing="0">
	
	<tr>
		<td  height="9" valign="bottom" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
		<td rowspan="2" colspan="2"    style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1.25pt solid #000000; border-right: none; padding: 0mm"><p align="center" >
			№</p>
		</td>
		<td rowspan="2" colspan="17" width="361" style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 0mm"><p align="center" >
			<font size="2" style="font-size: 9pt"><b>Наименование
			работ, услуг</b></font></p>
		</td>
		<td rowspan="2" colspan="3"    style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 0mm"><p align="center" >
			<font size="2" style="font-size: 9pt"><b>Кол-во</b></font></p>
		</td>
		<td rowspan="2" colspan="2"    style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 0mm"><p align="center" >
			<font size="2" style="font-size: 9pt"><b>Ед.</b></font></p>
		</td>
		<td rowspan="2" colspan="4"    style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 0mm"><p align="center" >
			<font size="2" style="font-size: 9pt"><b>Цена</b></font></p>
		</td>
		<td rowspan="2" colspan="4"    style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: 1.25pt solid #000000; padding: 0mm"><p align="center" >
			<font size="2" style="font-size: 9pt"><b>Сумма</b></font></p>
		</td>
	</tr>
	<tr valign="bottom">
		<td  height="15" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
	</tr>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['courses']->value, 'row', false, NULL, 'course', array (
  'iteration' => true,
  'total' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration']++;
?>
	<tr>
		<td  valign="bottom" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
		<td colspan="2"    valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1.25pt solid #000000; border-right: none; padding: 1mm"><p align="center" >
			<font size="1" style="font-size: 8pt"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration'] : null);?>
.</font></p>
		</td>
		<td colspan="17" width="361" valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 1mm"><p align="left" >
			<font size="1" style="font-size: 8pt">
			    <!--Программа обучения <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
-->
			    <?php echo $_smarty_tpl->tpl_vars['row']->value['order_service'];?>
 <?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>

			    <?php if ($_smarty_tpl->tpl_vars['row']->value['hours'] != '') {?><nobr>( <?php echo $_smarty_tpl->tpl_vars['row']->value['hours'];?>
 ч. )</nobr><?php }?>
			</font></p>
		</td>
		<td colspan="3"    valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 1mm"><p align="center" >
			<font size="1" style="font-size: 8pt"><?php echo $_smarty_tpl->tpl_vars['row']->value['count'];?>
</font></p>
		</td>
		<td colspan="2"    valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 1mm"><p align="center" >
			<font size="1" style="font-size: 8pt"><?php echo $_smarty_tpl->tpl_vars['row']->value['units'];?>
</font></p>
		</td>
		<td colspan="4"    valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding: 1mm"><p align="center" >
			<font size="1" style="font-size: 8pt"><?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
,00</font></p>
		</td>
		<td colspan="4"    valign="top" style="background: transparent; border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: 1.25pt solid #000000; padding: 1mm"><p align="center" >
			<font size="1" style="font-size: 8pt"><?php echo $_smarty_tpl->tpl_vars['row']->value['price']*$_smarty_tpl->tpl_vars['row']->value['count'];?>
,00</font></p>
		</td>
	</tr>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>	

	<tr valign="bottom">
		<td  height="7" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
		<td  style="background: transparent; border-top: 1.25pt solid #000000; border-bottom: none; border-left: none; border-right: none; padding: 0mm">
		</td>
	</tr>
</table>
	
	
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	     <td width="50%">
	     </td>
	     
		<td   height="17" valign="bottom" style="background: transparent; border: none; padding: 1mm"><p align="right" >
			<font size="2" style="font-size: 9pt"><b>Итого:</b></font></p>
		</td>
		<td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="right" >
			<font size="2" style="font-size: 9pt"><b><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['order_data']->value['price_sum']);?>
 </b></font></p>
		</td>
	</tr>
	<tr>
             <td>
             </td>
             <td   height="5" valign="bottom" style="background: transparent; border: none; padding: 1mm"><p align="right" >
                <font style="font-size: 9pt"><b>В том числе НДС НДС 5%:</b></font></p>
             </td>
             <td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="right" >
                <font style="font-size: 9pt"><b><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['order_data']->value['price_sum']/105));?>
 </b></font></p>
             </td>
        </tr>
	<tr>
	     <td>
	     </td>
		<td   height="17" valign="bottom" style="background: transparent; border: none; padding: 1mm"><p align="right" >
			<font size="2" style="font-size: 9pt"><b>Всего (с учетом НДС):</b></font></p>
		</td>
		<td  valign="top" style="background: transparent; border: none; padding: 1mm"><p align="right" >
			<font size="2" style="font-size: 9pt"><b><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['order_data']->value['price_sum']);?>
 </b></font></p>
		</td>
	</tr>
</table>



	
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="bottom">
		<td  height="15" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
		<td colspan="32"    style="background: transparent; border: none; padding: 0mm"><p align="left" style="padding-top: 15px;" >
			<font size="1" style="font-size: 8pt">Всего оказано услуг <?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['total']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['total'] : null);?>
, на сумму  <?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['order_data']->value['price_sum']);?>
  руб.</font></p>
		</td>
	</tr>
	<tr>
		<td  height="17" valign="bottom" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
		<td colspan="31"    valign="top" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<font size="2" style="font-size: 9pt"><b>   <?php echo $_smarty_tpl->tpl_vars['order_data']->value['price_sum_str'];?>
 </b></font></p>
		</td>
		<td  valign="bottom" style="background: transparent; border: none; padding: 0mm"><p align="left" >
			<br/>

			</p>
		</td>
	</tr>
</table>
	
	


<p align="left"  style="font-size: 9pt; padding-top: 15px; padding-bottom: 15px;  padding-left: 15px;">
			Вышеперечисленные
			услуги выполнены полностью и в срок.
			Заказчик претензий по объему, качеству
			и срокам оказания услуг не имеет.
</p>

	
<div style="padding-left: 15px;">
<hr />	
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="bottom">
		<td  width="50%"   style="background: transparent; border: none; padding: 0mm;"><p align="left"  style="font-size: 10pt">
			<b>ИСПОЛНИТЕЛЬ</b></p>
		</td>
		<td   style="background: transparent; border: none; padding: 0mm"><p align="left"  style="font-size: 10pt">
			<b>ЗАКАЗЧИК</b></p>
		</td>
	</tr>
	<tr valign="bottom">
		<td   style="background: transparent; border: none; padding: 0mm"><p align="left"  style="font-size: 8pt">
			
			<?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
<br />
			<?php echo $_smarty_tpl->tpl_vars['self_data']->value['position_head'];?>

		</p>

		</td>
		<td   style="background: transparent; border: none; padding: 0mm"><p align="left"  style="font-size: 8pt">
            <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>
<br />
			<?php echo $_smarty_tpl->tpl_vars['order_data']->value['position_head'];?>

		</p>
		</td>
	</tr>


	<tr valign="bottom">
		<td  height="17" style="background: transparent; border: none; padding: 0mm">
		    <p align="left" >
			______________________
			</p>
		    <p align="left" style=" padding-left: 10mm" >
			  <small><?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</small>
			</p>
		</td>
		<td   style="background: transparent; border: none; padding: 0mm">
            <p align="left" >
			_______________________
			</p>
		    <p align="left" style=" padding-left: 10mm" >
				<small><?php echo $_smarty_tpl->tpl_vars['order_data']->value['enterprise_manager3'];?>
</small>
			</p>
		</td>
	
	</tr>
</table>
</div>



<?php if ($_smarty_tpl->tpl_vars['print_v']->value == 'false') {?>
<div style="position: relative; top: 0px; right: 0px;">        
<div style="position: absolute; top: -80px; left: 80px;"><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="100px" align="middle" /></div>
<div style="position: absolute; top: -35px; left: 30px;"><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
</div>
<?php }?>
</body>
</html><?php }
}
