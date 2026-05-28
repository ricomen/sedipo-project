<?php
/* Smarty version 4.3.2, created on 2026-04-29 04:48:04
  from '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOOY-a3.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69f18d84d47b65_40684671',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '32e76d25e747474a61be3579bf65cbba6d1238af' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOOY-a3.html',
      1 => 1774856160,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f18d84d47b65_40684671 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.6cm; margin-right: 0.9cm; margin-top: 0.5cm; margin-bottom: 0.5cm; size: A4 portrait;  }
	p {  line-height: 120%; text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; line-height:1;  margin-top: 4px; margin-bottom: 4px; }
        p.western li { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; line-height:1; }
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
	<title>Согласие на обработку персональных данных</title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000">
<?php $_smarty_tpl->_assignInScope('per', 0);
$_smarty_tpl->_assignInScope('users1', array());
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['courses']->value, 'row', false, NULL, 'course', array (
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>    

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value['users'], 'row2', false, NULL, 'user', array (
));
$_smarty_tpl->tpl_vars['row2']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row2']->value) {
$_smarty_tpl->tpl_vars['row2']->do_else = false;
?>

<?php if (!in_array($_smarty_tpl->tpl_vars['row2']->value,$_smarty_tpl->tpl_vars['users1']->value)) {
$_tmp_array = isset($_smarty_tpl->tpl_vars['users1']) ? $_smarty_tpl->tpl_vars['users1']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = $_smarty_tpl->tpl_vars['row2']->value;
$_smarty_tpl->_assignInScope('users1', $_tmp_array);
}?>


<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users1']->value, 'row2', false, NULL, 'user', array (
));
$_smarty_tpl->tpl_vars['row2']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row2']->value) {
$_smarty_tpl->tpl_vars['row2']->do_else = false;
?>
<!--<p class="western">TUT<?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contr_id'];?>
</p>-->
<!-- <table width="100%" cellpadding="7" cellspacing="0"  <?php if ($_smarty_tpl->tpl_vars['per']->value != 0) {?> style="page-break-before: always; " <?php }?>>
    
    <tr>
        <td width="40%">
            
        </td>
        <td>
            <p class="western" style="text-align: left; text-indent:0px ;">
                На имя <?php echo $_smarty_tpl->tpl_vars['self_data']->value['position_head2'];?>
 <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
<br />
                <?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager2'];?>
<br />
                
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['lastname'] != '') {?>
                Фамилия, имя, отчество: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['middlename'];?>
</u><br />
                <?php } else { ?>
                Фамилия, имя, отчество:_____________________________
                __________________________________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['date_of_birth'] != '') {?>
                Дата рождения: <u><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row2']->value['date_of_birth'],"%02e.%m.%Y");?>
</u><br />
                <?php } else { ?>
                Дата рождения: _____________________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['address'] != '') {?>
                Адрес проживания: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['address'];?>
</u><br />
                <?php } else { ?>
                Адрес проживания: _________________________________
                ______________________________________________________<br />
                <?php }?>
                Документ удостоверяющий личность: <u>паспорт</u><br />
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['pasport_series'] != '') {?>
                серия <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_series'];?>
</u> № <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_number'];?>
</u><br />
                <?php } else { ?>
                серия _________ № ________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['pasport_unit'] != '') {?>
                кем и когда выдан: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_unit'];?>
</u><br />
                <?php } else { ?>
                кем и когда выдан: __________________________________________
                ______________________________________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['pasport_unit_number'] != '') {?>
                код подразделения: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_unit_number'];?>
</u><br />
                <?php } else { ?>
                код подразделения: ________________________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['snils'] != '') {?>
                СНИЛС: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['snils'];?>
</u><br />
                <?php } else { ?>
                СНИЛС: _____________________________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['phone'] != '') {?>
                Контактный телефон: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['phone'];?>
</u><br />
                <?php } else { ?>
                Контактный телефон: ______________________________<br />
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['row2']->value['email'] != '') {?>
                E-mail: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['email'];?>
</u>                  
                <?php } else { ?>
                E-mail: ______________________________________________
                <?php }?>
            </p>
            
        </td>
    </tr>
</table>

<h1 class="western" style="text-align:center;  margin-bottom: 0;"><b>ЗАЯВЛЕНИЕ
    </b></h1>

<p class="western"  >
    <span style="margin-left: 20px;">&nbsp;</span>Согласно договору № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
 от <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['contract_data']->value['date_contract'],"%02e.%m.%Y");?>
 на оказание платных образовательных услуг, прошу Вас зачислить меня в учебные группы по образовательным программам профессионального обучения и (или) дополнительного образования.
    Ознакомлен с Уставом, лицензией на осуществление образовательной деятельности, «Правилами приема обучающихся», с программами обучения, с учебной документацией, другими документами, регламентирующими процесс и осуществление образовательной деятельности организации <?php echo $_smarty_tpl->tpl_vars['self_data']->value['name'];?>
 (ИНН <?php echo $_smarty_tpl->tpl_vars['self_data']->value['inn'];?>
, ОГРН <?php echo $_smarty_tpl->tpl_vars['self_data']->value['ogrn'];?>
, юридический адрес: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['addres1'];?>
) (далее - <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
).
    Ознакомлен с Правилами оказания платных образовательных услуг (Утв. Постановлением Правительства РФ от 15.09.2020 № 1441). 
</p>
<table width="100%" >
    <tr>
        <td width="30%" style="border-bottom: 1px solid #000000; text-align: center;"></td>
        <td style="border-bottom: 1px solid #000000; text-align: center;"><?php echo $_smarty_tpl->tpl_vars['row2']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['middlename'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['lastname'];?>
</td>
    </tr>
    <tr>
        <td style="font-size: 5pt; text-align: center;">(подпись)</td>
        <td style="font-size: 5pt; text-align: center;">(ИО Фамилия)</td>
    </tr>
</table>
 -->

<h1 class="western" style=" <?php if ($_smarty_tpl->tpl_vars['per']->value != 0) {?>page-break-before: always;<?php }?>">
<?php $_smarty_tpl->_assignInScope('per', 1);?>
    Согласие на обработку персональных данных обучающегося
</h1>

<!--////////////////////////////////////////////////////////////////////////-->
<p class="western" style="text-align: left;" >
<span style="margin-left: 40px;">&nbsp;</span>
<?php if ($_smarty_tpl->tpl_vars['row2']->value['lastname'] != '') {?>
Я (далее – Субъект), <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['middlename'];?>
</u>
<?php } else { ?>
Я (далее – Субъект), ____________________________________________________________ 
<?php }?>
документ, удостоверяющий личность паспорт серия <?php if ($_smarty_tpl->tpl_vars['row2']->value['pasport_series'] != '') {?>
<u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_series'];?>
</u> № <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_number'];?>
</u>
<?php } else { ?>
_________ № ________________
<?php }
if ($_smarty_tpl->tpl_vars['row2']->value['pasport_unit'] != '') {?>
выдан <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['pasport_unit'];?>
</u>
<?php } else { ?>      
выдан _______________________________________________________________________________ 
<?php }
if ($_smarty_tpl->tpl_vars['row2']->value['address'] != '') {?>
зарегистрированный (ая) по адресу: <u><?php echo $_smarty_tpl->tpl_vars['row2']->value['address'];?>
</u>
<?php } else { ?>
зарегистрированный (ая) по адресу: ______________________________________________________,
<?php }?> 
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>в соответствии со статьей 9 Федерального закона от 27.07.2006 № 152-ФЗ «О персональных данных», представляю ООО «ИТЦ «БЕЗОПАСНОСТЬ» (ОГРН 1220200042006, ИНН 0276972620 450055, зарегистрированному по  адресу: Республика Башкортостан, г. Уфа, Пр. Октября, д. 152) (далее-Оператор), 
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span><b>согласие</b> на обработку, включая сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, извлечение, передачу (предоставление, доступ), обезличивание, блокирование, удаление, уничтожение моих персональных данных, относящихся исключительно к перечисленным ниже категориям персональных данных: 
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>фамилия, имя, отчество, дата рождения (число, месяц, год), пол, гражданство, данные документа удостоверяющего личность, данные СНИЛС, ИНН, сведения об образовании, квалификации, о занимаемой должности, информация о трудовой деятельности (место работы, должность, трудовой стаж), адрес проживания/регистрации, контактные данные (телефонный номер, адрес электронной почты), сведения документов,  выданных органами  ЗАГС (при смене  Ф.И.О.), а в случаях предусмотренных законодательством -  специальные персональные данные – сведений о состоянии здоровья.
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>в целях получения образовательной услуги, в том числе для оформления документов о прохождении обучения /аттестации, ведения учета и  хранения  информации о  полученной  услуге, результатах освоения образовательной  программы,  выдачи справок  и  дубликатов документов, подтверждающих обучение  при  их  утере, занесения данных в Федеральный реестр сведений о документах об образовании и (или) о квалификации, документах об обучении (ФИС ФРДО),  планирования  и  организации  деятельности Оператора.
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Я информирован(-а), что персональные данные (ФИО, дата рождения, ИНН, СНИЛС, номер и дата выдачи документа об образовании и (или) о квалификации, документах об обучении/аттестации) будут использоваться в целях внесения данных в Федеральную информационную систему «Федеральный реестр сведений о документах об образовании и (или) о квалификации, обучении».
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Я информирован(-а), что обработка персональных данных осуществляется как с использованием средств автоматизации, в том числе в информационно-телекоммуникационных сетях, так и без использования таких средств. 
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Я информирован(-а), что по письменному запросу вправе получать информацию, касающейся обработки его персональных данных (в соответствии с п.4 ст 14 Федерального закона от 27.06.2006                   № 152-ФЗ) «О персональных данных».
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Вводный инструктаж пройден в ООО «ИТЦ «БЕЗОПАСНОСТЬ» по утвержденным в Обществе программам вводного инструктажа по охране труда, инструктажа по пожарной безопасности. 
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Настоящее Согласие может быть отозвано путем предоставления в ООО «ИТЦ «БЕЗОПАСНОСТЬ» соответствующего заявления в письменной форме.
</p>
<p class="western" >
<span style="margin-left: 40px;">&nbsp;</span>Действие настоящего согласия: со дня подписания до дня отзыва в письменной форме.	
</p>





<!--////////////////////////////////////////////////////////////////////////-->



<table width="100%" border="1" cellpadding="0" cellspacing="0">
<tr>
        <td>Дата</td>
        <td>Подпись</td>
        <td>Расшифровка</td>
</tr>
<tr>
        <td></td>
        <td></td>
        <td><?php echo $_smarty_tpl->tpl_vars['row2']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['middlename'];?>
 <?php echo $_smarty_tpl->tpl_vars['row2']->value['lastname'];?>
</td>
</tr>

</table>

<!--_____________________________  ____________________________________________________________	
        (подпись)		                             (ИО Фамилия)

<p class="western" style="text-indent: 0px;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order_data']->value['date_order'],"%02e.%m.%Y");?>
</p>
-->

<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</body>
</html>
<?php }
}
