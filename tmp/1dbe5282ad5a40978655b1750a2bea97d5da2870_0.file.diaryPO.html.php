<?php
/* Smarty version 4.3.2, created on 2026-04-29 06:57:28
  from '/var/www/sed20.sedipo.ru/public_html/documents/usercase/po/diaryPO.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69f1abd8ac3659_66039774',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1dbe5282ad5a40978655b1750a2bea97d5da2870' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/usercase/po/diaryPO.html',
      1 => 1748087332,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f1abd8ac3659_66039774 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.6cm; margin-right: 0.9cm; margin-top: 0.5cm; margin-bottom: 0.5cm; size: A4 portrait;  }
	p {  direction: ltr; line-height: 120%; text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; line-height:1; }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center; margin-top: 0.4cm; margin-bottom: 0.4cm; margin-left: 1cm; margin-right: 1cm   }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	p.table { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-top: 0.4cm; margin-bottom: 0.4cm  }
	li { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-bottom: 0.1cm;  }
	th { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: center; margin-top: 0.1cmargin-top: 0.1cm; m; margin-bottom: 0.1cm;  }
	td { font-family: "DejaVu Serif", serif; font-size: 8pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }

	p.cjk { font-family: "DejaVu Sans"; font-size: 12pt }
	p.ctl { font-size: 12pt }
	a:link { color: #0000ff }
	a.ctl:link { font-family: "DejaVu Sans" }
    </style>


	<title>Дневник ПО</title>
</head>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'cell', false, NULL, 'users', array (
));
$_smarty_tpl->tpl_vars['cell']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->do_else = false;
?>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
 <!--
    <table width="100%" cellpadding="7" cellspacing="0">
        <tr>
            <td width="35">
                
            </td>
            <td>
                <p class="western" style="text-align: right;">
                <b>УТВЕРЖДАЮ</b> <br />
                Директор <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>

				<?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>

                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order_data']->value['date_order'],"%e.%m.%Y");?>
 г.<br />
                </p>
            </td>
        </tr>
        </table>
-->   

        <h1 class="western">ДНЕВНИК <br \>производственного обучения
		</h1>
		<br \>
		<br \>
		<br \>
<p class="western" style="text-align:center;  margin-bottom: 0;"><b><u><?php echo $_smarty_tpl->tpl_vars['cell']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['middlename'];?>
</u>
    </b></p>
    <p class="western" style="text-align:center;  margin-bottom: 0; margin-top: 0; font-size: 7pt;">(Ф.И.О. обучающегося)
    </p>


    <br \>
    <br \>
    <br \>

<p class="western" style="margin-left: 40px; margin-right: 40px;"><b>Изучаемая профессия (специальность): <?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
</b></p>
<p class="western" style="margin-left: 40px; margin-right: 40px;"><b>Начало производственной практики: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['schedulerprf']->value,"%02e.%m.%Y");?>
</b></p>
<p class="western" style="margin-left: 40px; margin-right: 40px;"><b>Окончание производственной практики: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['schedulerprl']->value,"%02e.%m.%Y");?>
</b></p>
<p class="western" style="margin-left: 40px; margin-right: 40px;"><b>Мастер п/о:  ________________________</b></p>
<p class="western" style="margin-left: 40px; margin-right: 40px;"><b>Место прохождения производственного обучения: _______________________________</b></p>
   
<p class="western" style="text-align:center; font-style: italic; page-break-before: always;"><b>Правила ведения дневника производственного обучения</b></p>
<p class="western" style="font-style: italic; text-align: justify;">
    Дневник является основным документом, подтверждающим прохождение производственной практики.  Дневник производственного обучения заполняет обучающийся под руководством мастера производственного обучения. Обучающийся после окончания каждой темы программы записывает в разделе «Содержание производственного обучения» дневника производственные работу и ее количество.  По окончании производственного обучения, <u>заполненный дневник с подписью мастера производственного обучения сдается <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
</u>
    </p>

<h1 class="western" style="page-break-before: always;">СОДЕРЖАНИЕ ПРОИЗВОДСТВЕННОЙ ПРАКТИКИ</h1>

<table width="100%" border="1" cellpadding="7" cellspacing="0" style="page-break-before: avoid;">
    <tr>
        <th>Дата</th>
        <th>Наименование и содержание 
        выполненных работ
        </th>
        <th>Кол-во выполненной работы ч.</th>
        <th>Оценка выполненной работы</th>
        <th>Подпись мастера ПО</th>
    </tr>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['calendar2']->value, 'row');
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
        <tr>
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row']->value['date'],"%02e.%m.%Y");?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
            <td style="text-align: center;"><?php echo round(intval($_smarty_tpl->tpl_vars['row']->value['hours']),0);?>
</td>
            <td></td>
            <td><!--<?php echo $_smarty_tpl->tpl_vars['teacher']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['teacher']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['teacher']->value['middlename'];?>
--></td>
        </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>

<h1 class="western" style="page-break-before: always;">ЗАКЛЮЧЕНИЕ <br \>
    на квалификационную работу
    </h1>
<p class="western"></p>
<!--
<p class="western" style=" margin-bottom: 0;">Выполнил <?php echo $_smarty_tpl->tpl_vars['cell']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['middlename'];?>
</p>
<p class="western" style=" margin-bottom: 0; margin-top: 0; font-size: 7pt;">(фамилия, имя, отчество)</p>
-->
<p class="western">Составлено: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_protocol'],"%02e.%m.%Y");?>
 о том, что обучающийся <?php echo $_smarty_tpl->tpl_vars['cell']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['cell']->value['middlename'];?>

выполнил квалификационную работу по профессии (специальности) <?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
:<br /><?php echo $_smarty_tpl->tpl_vars['lstream']->value['qualification_work'];?>
</p>

<p class="western" style=" margin-bottom: 0; margin-top: -15px; font-size: 7pt;">(наименование работы и краткая ее характеристика)</p>

<p class="western">Оценка «4 (хорошо)» – работа выполнена на 90 баллов в соответствии с техническими требованиями и условиями к выполнению задания практического экзамена в установленное время с хорошим качеством. Обучающийся отлично владеет инструментом, инвентарем, оборудованием, приспособлениями, вспомогательными материалами. Применяет теорию на практике, соблюдает правила охраны труда, пожарной безопасности.</p>

<p class="western">Выполненная работа соответствует уровню квалификации <?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
, квалификационный разряд – <?php echo $_smarty_tpl->tpl_vars['lstream']->value['qualification'];?>
.</p>

<table style="page-break-before: avoid;" width="100%" cellpadding="7" cellspacing="0" style="page-break-before: avoid; text-align: justify; line-height: 90%;">

<tr>

    <td>
        <p class="western">Мастер производственного обучения</p>
    </td>
    <td>
<p class="western">____________</p>
    </td>
    <td>
<p class="western">____________</p>
    </td>
</tr>
<tr>

    <td>
<p class="western">Директор</p>
    </td>
    <td>
<p class="western">____________</p>
    </td>
    <td>
<p class="western">____________</p>
    </td>
</tr>
</table>

</body>
<!--sh =<?php echo count($_smarty_tpl->tpl_vars['scheduler']->value);?>
 i= <?php echo $_smarty_tpl->tpl_vars['date_i']->value;?>
 tc=<?php echo $_smarty_tpl->tpl_vars['teorcount']->value;?>
 tcs=<?php echo $_smarty_tpl->tpl_vars['teorcountsum']->value;?>
-->
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</html>
<?php }
}
