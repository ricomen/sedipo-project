<?php
/* Smarty version 4.3.2, created on 2026-04-13 09:51:47
  from '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOY-a1.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69dcbcb39b3378_50854335',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '348f27d3c0a1c3f1f9769e36e0c2f905dea54713' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOY-a1.html',
      1 => 1748581289,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69dcbcb39b3378_50854335 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.6cm; margin-right: 0.9cm; margin-top: 0.5cm; margin-bottom: 0.5cm; size: A4 portrait;  }
	p { margin-bottom: 0.25cm; direction: ltr; line-height: 120%; text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; margin-top: 0.4cm; margin-bottom: 0.4cm;  }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center; margin-top: 0.4cm; margin-bottom: 0.4cm; margin-left: 1cm; margin-right: 1cm   }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	p.table { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-top: 0.4cm; margin-bottom: 0.4cm  }
	li { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-bottom: 0.1cm;  }
	th { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: center; margin-top: 0.1cm; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	td { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }

	p.cjk { font-family: "DejaVu Sans"; font-size: 12pt }
	p.ctl { font-size: 12pt }
	a:link { color: #0000ff }
	a.ctl:link { font-family: "DejaVu Sans" }
    </style>
	<title>ДОГОВОР</title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
    
    <table width="100%" cellpadding="7" cellspacing="0">
    <tr>
        <td width="40%">
            
        </td>
        <td>
            <p class="western" style="text-align: right;">
            <b>Приложение № 1</b> <br />
            к договору оказания услуг № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
 <br \>от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['contract_data']->value['date_contract'],"%e.%m.%Y");?>
 г.
            </p>
        </td>
    </tr>
    </table>
<p class="western" style="text-align:center;  margin-bottom: 0;"><b>Спецификация № <?php echo $_smarty_tpl->tpl_vars['order_data']->value['order_num'];?>
</b><br \>
    к договору оказания услуг № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
 от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['contract_data']->value['date_contract'],"%e.%m.%Y");?>
 г.   
</p>

<p class="western"  style="margin-top: 0.3cm; margin-bottom: 0.3cm;">
1. Наименование образовательной организации: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['name'];?>
  <br />
2. Период обучения: с&nbsp;<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order_data']->value['date_begin'],"%e.%m.%Y");?>
 по&nbsp;<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order_data']->value['date_end'],"%e.%m.%Y");?>
<br />
3. Список сотрудников <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>
, направляемых на обучение:<br />
</p>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['courses']->value, 'row', false, NULL, 'course', array (
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
    <table width="100%" cellpadding="7" cellspacing="0">
	<col width="10%"/>
	<col width="30%"/>
	<col width="15%"/>
	<col width="15%"/>

	<tr valign="top">
		<th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			№<br \>п/п</p>
		</th>
		<th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			Ф.И.О.</p>
		</th>
		<th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			Дата рождения</p>
		</th>
        <th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			СНИЛС</p>
		</th>
		<th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			Адрес регистрации</p>
		</th>
        <th  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center">
			Телефон</p>
		</th>
	</tr>

    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value['users'], 'row2', false, NULL, 'user', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row2']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row2']->value) {
$_smarty_tpl->tpl_vars['row2']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_user']->value['iteration']++;
?>
    <tr>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_user']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_user']->value['iteration'] : null);?>
.</p> </td>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo $_smarty_tpl->tpl_vars['row2']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['firstname'];?>
  <?php echo $_smarty_tpl->tpl_vars['row2']->value['middlename'];?>
 </p></td>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo $_smarty_tpl->tpl_vars['row2']->value['date_of_birth'];?>
</p></td>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo $_smarty_tpl->tpl_vars['row2']->value['snils'];?>
</p></td>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo $_smarty_tpl->tpl_vars['row2']->value['address'];?>
</p> </td>
        <td  style="background: transparent; border: 1px solid #000000; padding: 0mm 1.91mm"><p class="table" style="text-align:center"><?php echo $_smarty_tpl->tpl_vars['row2']->value['phone'];?>
</p> </td>
    </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>
<p class="western" style="line-height: 100%; margin-bottom: 0mm"><br />

Описание образовательной услуги: <br \>
вид / подвид  образовательной услуги: <?php echo $_smarty_tpl->tpl_vars['row']->value['type_of_education'];?>
, <?php echo $_smarty_tpl->tpl_vars['row']->value['subtype_of_education'];?>
;<br />
вид программы:  <?php echo $_smarty_tpl->tpl_vars['row']->value['type_of_program'];?>
;<br />
наименование программы - <u><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
;</u><br />
нормативный срок обучения: <?php echo $_smarty_tpl->tpl_vars['row']->value['hours'];?>
 ч.;<br />
форма обучения: <?php echo $_smarty_tpl->tpl_vars['row']->value['form_of_study'];?>
;<br />
итоговый документ: <?php echo $_smarty_tpl->tpl_vars['row']->value['certificate_name'];?>
;<br />
дата обучения: согласно расписанию;<br />
место обучения: <?php echo $_smarty_tpl->tpl_vars['row']->value['addres'];?>
<br />

</p>

    <p></p>

    <p><br /></p>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


<p class="western" style="text-align:center; page-break-before: avoid;">Подписи сторон:</p>


<table width="100%" cellpadding="7" cellspacing="0">
    <tr valign="top">
        <td width="50%">
            <p class="western" style="text-align: left;">
            <b>Заказчик: <br />
            <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>

            <br />
            <br />
            <br />
            </b>
        </td>

        <td style="vertical-align:top">
            <p class="western" style="text-align: left;">
            <b>
            Исполнитель:<br />
            <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
<br />
            <br />
            <br />
            <br />
            <br />
            </b>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <p class="western" style="text-align: left;">
            <?php echo $_smarty_tpl->tpl_vars['order_data']->value['position_head'];?>
 ________________/<u><?php echo $_smarty_tpl->tpl_vars['order_data']->value['enterprise_manager3'];?>
</u>/ <br />
            М.П.
            </p>

        </td>
        <td width="50%">
            <p class="western" style="text-align: left;">
            <?php echo $_smarty_tpl->tpl_vars['self_data']->value['position_head'];?>
_______________ /<u><?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</u>/<br />
            М.П.
            </p>
			<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?> 
			<div style="position: relative; top: 0px; left: 0px;">        
			<div style="position: absolute; top: -110px; right: 200px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/<?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager_signs'];?>
" width="150px" align="top" ></div>
			<div style="position: absolute; top: -60px; right: 110px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
			</div>
			<?php }?>
        </td>
    </tr>
    </table>
</body>
</html>
<?php }
}
