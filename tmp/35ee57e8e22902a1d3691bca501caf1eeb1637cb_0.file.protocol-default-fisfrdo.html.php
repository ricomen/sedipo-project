<?php
/* Smarty version 4.3.2, created on 2026-03-03 12:34:04
  from '/var/www/sed19.sedipo.ru/public_html/documents/protocol/protocol-default-fisfrdo.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69a6d53cc0c667_89094203',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '35ee57e8e22902a1d3691bca501caf1eeb1637cb' => 
    array (
      0 => '/var/www/sed19.sedipo.ru/public_html/documents/protocol/protocol-default-fisfrdo.html',
      1 => 1769589819,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69a6d53cc0c667_89094203 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed19.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.7cm; margin-right: 1.7cm; margin-top: 1cm; margin-bottom: 1cm; size: A4 landscape; }
	p {  direction: ltr;  text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; margin-bottom: 2px;  margin-top: 2px; }
	p.left { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-bottom: 2px;  margin-top: 10px; }
	p.center { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center; margin-bottom: 2px;  margin-top: 2px; }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 14pt; text-align: center }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 13pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center }
	h4.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	p.table { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left }
	th { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left }
	td { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left margin-bottom: 2px;  margin-top: 2px; line-height: 96%; }
	td.boss { text-align:top; font-size: 10pt; line-height: 0%;}
	td.comand { text-align:middle; font-size: 10pt; margin-right: 20px; }
    </style>
	<title>Протокол</title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
<h4  class="western" style="margin-top: 0; margin-bottom: 5px;  text-transform: uppercase;"><?php echo $_smarty_tpl->tpl_vars['self_data']->value['name'];?>
</h4>
<h2 class="western" style="margin-top: 5px; margin-bottom: 2px; " >ПРОТОКОЛ №  <?php echo $_smarty_tpl->tpl_vars['course']->value['protocol_num'];?>
 </h2>
<p  class="center" style="margin-top: 4px;  margin-left: 250px;  margin-right: 250px; line-height: 95%;" ><?php echo $_smarty_tpl->tpl_vars['protocol']->value['protocol_h'];?>
 </p>
<div class="resize" id="resize-1"></div>
<table width="100%"><tr><td style="font-size: 12pt;"><p class="western" > <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['course']->value['date_protocol'],"%02e.%m.%Y");?>
 </p></td><td> </td> <td width="10%" style="font-size: 12pt; text-align: right;"><p class="western"  style="text-align: right;"> г. Уфа</p></td></tr></table>

<p class="western"  style="margin-top: 7px;" > <?php echo $_smarty_tpl->tpl_vars['teachers_commission']->value['directive_num'];?>
</p>
<div class="resize" id="resize-1"></div>
<?php echo $_smarty_tpl->tpl_vars['teachers_commission']->value['html1'];?>

<div class="resize" id="resize-1"></div>

<p class="western" style="margin-bottom: 6px;  margin-top: 6px; line-height: 96%;" > <?php echo $_smarty_tpl->tpl_vars['protocol']->value['protocol_p1'];?>
:
    <i>«<?php echo $_smarty_tpl->tpl_vars['course']->value['name'];?>
»</i><?php if ($_smarty_tpl->tpl_vars['protocol']->value['is_hours_p']) {?> в объеме <?php echo $_smarty_tpl->tpl_vars['course']->value['hours'];?>
 <?php }
echo $_smarty_tpl->tpl_vars['protocol']->value['protocol_p2'];?>
</p>

<p class="western" >
	<div class="resize" id="resize-1"></div>
<table width="100%" cellpadding="2" cellspacing="0" style="page-break-inside: auto; page-break-before: avoid;">
<tr><th   style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center; font-size:8pt;" >№ <br /><nobr>п/п</nobr></th>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users_header']->value, 'cell', false, NULL, 'users_header', array (
));
$_smarty_tpl->tpl_vars['cell']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->do_else = false;
?>
		<th   style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value;?>
</th>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</tr>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'row', false, NULL, 'users', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']++;
?>
    <tr><td   style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" ><nobr><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration'] : null);?>
.</nobr></td>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value, 'cell', false, NULL, 'users_cell', array (
));
$_smarty_tpl->tpl_vars['cell']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->do_else = false;
?>
	    <td   style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center; " ><?php echo $_smarty_tpl->tpl_vars['cell']->value;?>
</td>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </tr>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</table>
<table style="margin-top: 15px; page-break-inside: avoid; page-break-before: avoid;">
<tr style="margin-top: 15px; page-break-inside: avoid; page-break-before: avoid;">
	<td style="margin-top: 15px; page-break-inside: avoid; page-break-before: avoid;" >
		<p class="western" style="margin-top: 15px; page-break-inside: avoid; page-break-before: avoid;" >
			<?php echo $_smarty_tpl->tpl_vars['teachers_commission']->value['html2_shortb'];?>

		</p>
	</td>
		<td width="50%" style="padding-left: 100px;"><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/fonimages/qrcode-fisfrdo.png" width="100px" height="100px" align="bottom" style="float: right;"  >Проверить в ФИС ФРДО &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>

</tr>
</table>


<div class="resize" id="resize-1"></div>

<?php if (strlen($_smarty_tpl->tpl_vars['protocol']->value['protocol_p3']) > 0) {?>
<p class="western" > <?php echo $_smarty_tpl->tpl_vars['protocol']->value['protocol_p3'];?>
 </p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?> 
<div style="position: relative; top: 0px; left: 0px;">        
<div style="position: absolute; top: -125px; left: 75px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
</div>
<?php }?>


<!--</body>
</html>-->
<?php }
}
