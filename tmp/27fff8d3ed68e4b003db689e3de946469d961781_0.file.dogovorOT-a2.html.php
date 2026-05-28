<?php
/* Smarty version 4.3.2, created on 2026-05-19 11:27:23
  from '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOT-a2.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_6a0c491b1ca9a0_50231911',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '27fff8d3ed68e4b003db689e3de946469d961781' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOT-a2.html',
      1 => 1769689679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a0c491b1ca9a0_50231911 (Smarty_Internal_Template $_smarty_tpl) {
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
	<title>Приложение 2</title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
    
    <table width="100%" cellpadding="7" cellspacing="0">
        <tr>
            <td width="30%">
                
            </td>
            <td>
                <p class="western" style="text-align: right;">
                <b>Приложение № 2</b> <br />
                к договору № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
 от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['contract_data']->value['date_contract'],"%e.%m.%Y");?>
<br />
                возмездного оказания услуг по обучению вопросам охраны труда
                </p>
            </td>
        </tr>
        </table>


<p class="western" style="text-align:center;  margin-bottom: 0;"><b>Стоимость возмездного оказания 
    <br />услуг по обучению вопросам охраны труда
    </b></p>
    <br />
    <p class="western"  style="page-break-before: auto; margin-top: 0; margin-bottom: 0.3cm; text-indent: 40px;">
        Стоимость оказываемых услуг Заказчику по спецификации № <?php echo $_smarty_tpl->tpl_vars['order_data']->value['order_num'];?>
 к договору возмездного оказания услуг № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
 составляет <?php echo $_smarty_tpl->tpl_vars['order_data']->value['price_sum'];?>
 рублей (<?php echo $_smarty_tpl->tpl_vars['order_data']->value['price_sum_str'];?>
):
    </p>
    <table width="100%" cellpadding="7" cellspacing="0" style="page-break-before: avoid;" >
	<tr valign="top">
		<th  style="background: transparent; border: 1px solid #000000; ">
			№<br \>п/п
		</th>
		<th  style="background: transparent; border: 1px solid #000000; ">
			Наименование программы
		</th>
		<th  style="background: transparent; border: 1px solid #000000; ">
			Количество, <br \>чел.
		</th>
		<th  style="background: transparent; border: 1px solid #000000; ">
			Стоимость,<br />руб.
		</th>
		<th  style="background: transparent; border: 1px solid #000000; ">
			Общая стоимость, <br />руб.
		</th>
	</tr>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['courses']->value, 'row', false, NULL, 'course', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration']++;
?>
    <tr>
        <td  style="background: transparent; border: 1px solid #000000; text-align:center;"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_course']->value['iteration'] : null);?>
. </td>
        <td  style="background: transparent; border: 1px solid #000000; "><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
 </td>
        <td  style="background: transparent; border: 1px solid #000000; text-align:center;"><?php echo $_smarty_tpl->tpl_vars['row']->value['count'];?>
 </td>
        <td  style="background: transparent; border: 1px solid #000000; text-align:center;"><?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
</td>
        <td  style="background: transparent; border: 1px solid #000000; text-align:center;"><?php echo $_smarty_tpl->tpl_vars['row']->value['price']*$_smarty_tpl->tpl_vars['row']->value['count'];?>
 </td>
    </tr>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <tr style="page-break-before: avoid;">
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "> </td>
        <td  style="background: transparent; padding: 0; text-align:right;">Итого: </td>
        <td  style="background: transparent; padding: 0; text-align:center; "><?php echo $_smarty_tpl->tpl_vars['order_data']->value['price_sum'];?>
</td>
    </tr>
        <tr style="page-break-before: avoid;">
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "> </td>
        <td  style="background: transparent; padding: 0; text-align:right;">НДС 5%: </td>
        <td  style="background: transparent; padding: 0; text-align:center; "><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['order_data']->value['price_sum']/105));?>
</td>
    </tr>
        <tr style="page-break-before: avoid;">
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "></td>
        <td  style="background: transparent; padding: 0; "> </td>
        <td  style="background: transparent; padding: 0; text-align:right;">Всего к оплате: </td>
        <td  style="background: transparent; padding: 0; text-align:center; "><?php echo $_smarty_tpl->tpl_vars['order_data']->value['price_sum'];?>
</td>
    </tr>
    </table>




<table width="100%" cellpadding="7" cellspacing="0">
    <tr valign="top">
        <td width="50%" style="vertical-align:top;"> 
                <p class="western" style="text-align: left;">
                <b>Заказчик: <br />
                <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>

                <br />
                <br />
                <br />
                </b>
                </p>
        </td>
        <td style="vertical-align:top;">
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
                </p>
        </td>
    </tr>
    <tr>
        <td>
            <p class="western" style="text-align: left;">
                <?php echo $_smarty_tpl->tpl_vars['order_data']->value['position_head'];?>
 ________________/<u><?php echo $_smarty_tpl->tpl_vars['order_data']->value['enterprise_manager3'];?>
</u>/ <br />
                М.П.
            </p>
        </td>
        <td>
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
