<?php
/* Smarty version 4.3.2, created on 2026-04-13 11:57:32
  from '/var/www/sed20.sedipo.ru/public_html/documents/usercase/pp/journal_grp.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69dcda2cbb8288_69330783',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5571605b93a990ea4b138bd14d61a2aa87e4fbff' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/usercase/pp/journal_grp.html',
      1 => 1748002226,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69dcda2cbb8288_69330783 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.0cm; margin-right: 1.0cm; margin-top: 1.0cm; margin-bottom: 1.0cm; size: A5 portrait;  }
	p { margin-bottom: 0.25cm; font-family: "DejaVu Serif";  text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 9pt; line-height: 99%; text-align: justify; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	p.left { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	p.right { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: right; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	p.center { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: center; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 14pt; text-align: center; margin-top: 1cm; margin-bottom: 1cm; margin-left: 1cm; margin-right: 1cm   }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center }
	p.table { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; margin-top: 0.2cm; margin-bottom: 0.2cm  }
	li { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
	td, th { font-family: "DejaVu Serif", serif; font-size: 9pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }

    </style>
	<title>Журнал обучения </title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
    <table border="0" width="100%"  >
        <tr valign="top">
        
            <td valign="center" height="250px;" style="vertical-align:center ;">
                <p class="center"  style="margin-bottom: 15px;  margin-top: 15px;" ><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/certificate_1.png" width="100px" ></p>
                <h1 class="western" style="text-transform:uppercase; ">Журнал обучения</h1>
            </td>
        </tr>
        <tr>
        <td valign="top" height="320px;">  
            <table width="100%" border="1" cellpadding="7" cellspacing="0" style="page-break-before: avoid;"><tr><td colspan="2"><b>Группа №</b> <?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
</td></tr><tr><td><b>Курс:</b></td><td><?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
</td></tr><tr><td><b>Преподаватель:</b></td><td> <?php echo $_smarty_tpl->tpl_vars['teacher']->value['fullname'];?>
</td></tr></table>
        </td>                                                                      
        </tr>     
        <tr>
        <td valign="bottom" height="70px;">                                                                             
            <p class="right" >Начало обучения: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_begin'],"%02e.%m.%Y");?>
</p>
        
            <p class="right" >Окончание обучения: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_protocol'],"%02e.%m.%Y");?>
</p>
        </td> 
        </tr>
    </table>
 
<h3  style="page-break-before: always;"  class="western" >Инструкция по ведению журнала</h3> 

<p class="western" ><span style="margin-right:25px;"> </span>Настоящий журнал предназначен для учета пройденного учебного материала и освоения его слушателями группы.</p>
<p class="western" ><span style="margin-right:25px;"> </span>Данные журнала являются основанием для начисления заработной платы преподавателям.</p>
<p class="western" ><span style="margin-right:25px;"> </span>Журнал хранится в течение одного года после окончания обучения группы.</p>
<p class="western" ><span style="margin-right:25px;"> </span>Заполнение всех данных в журнале обязательно. Ответственность за ведение и хранение журнала возлагается на учебную часть <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
. Специалист учебной части перед началом занятий заносит сведения об учащихся группы из приказа о начале обучения в раздел «Учет подготовки слушателей». </p>
<p class="western" ><span style="margin-right:25px;"> </span>Расписание учебной группы составляется в соответствии с программой обучения и утверждается директором <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
.</p>
<p class="western" ><span style="margin-right:25px;"> </span>В процессе подготовки преподаватель заполняет графы «Дата занятий / оценка» раздела «Учет подготовки слушателей».</p>
<p class="western" ><span style="margin-right:25px;"> </span>Внесение изменений, в состав обучающихся производится учебной частью. При исключении слушателя из состава группы учебной частью делается пометка в разделе «Учет подготовки слушателей» об исключении слушателя из состава группы усвоения слушателями учебного материала с указанием даты и причины исключения и заверяется подписью начальника учебной части.</p>
<p class="western" ><span style="margin-right:25px;"> </span>Выбор методов, способов и видов проведения занятий, а также проверки усвоения слушателями учебного материала производится преподавателем. </p>
<p class="western" ><span style="margin-right:25px;"> </span>После проведения итоговой аттестации/ проверки знаний составляется приказ о выпуске группы с указанием списка слушателей, успешно закончивших обучение.</p>
<p class="western" ><span style="margin-right:25px;"> </span>Содержание изучаемых тем теоретических и практических занятий раскрыты в учебной программе.</p>


<!--
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	ПРИКАЗ
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
-->
<h2 class="western" style="page-break-before: always;">ПРИКАЗ</h2>

<table width="100%"><tr><td style="font-size: 12pt;"><p class="western" > № <?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
 </p></td> <td width="10%" style="font-size: 12pt; text-align: right;"><p class="western"  style="text-align: right;"> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_begin'],"%02e.%m.%Y");?>
</p></td></tr></table>        
<br \>     
<p class="western">О комплектовании группы № <?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
<br \><br \>
</p>
<p class="western">
    На основании договора о предоставлении платных образовательных услуг
    
    <br />
    <br />
</p>    
<p class="western" style="text-align:center;">ПРИКАЗЫВАЮ:</p> 
    


<ol>
    <li style="text-align: justify;">
        В соответствии с утвержденным расписанием начать учебные занятия группы с <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_begin'],"%02e.%m.%Y");?>
 по курсу "<?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
", форма обучения – <?php echo $_smarty_tpl->tpl_vars['lstream']->value['form_of_study'];?>
, нормативный срок освоения программы – <?php echo $_smarty_tpl->tpl_vars['lstream']->value['hours'];?>
  ч.
    </li>
    <li style="text-align: justify;">
        Закрепленный за группой преподаватель: <?php echo $_smarty_tpl->tpl_vars['teacher']->value['fullname'];?>

    </li>
    <li style="text-align: justify;">
            В состав группы включить следующих слушателей:
            <br />
            <br />
            <table width="100%" cellpadding="2" cellspacing="0">
            <tr><th  width="5%" style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" >№ <br /><nobr>п/п</nobr></th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Фамилия </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Имя </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Отчество </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Должность </th></tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'cell', false, NULL, 'users', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['cell']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']++;
?>
            <tr>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" ><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration'] : null);?>
.</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['lastname'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['firstname'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['middlename'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['position'];?>
</td>
            </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </table>
    </li>


</ol>
<br />
<br />
<table  cellpadding="2" cellspacing="0" width="100%" border="0">
    <tr>
        <td width="45%"><span style="text-transform: capitalize; "><?php echo $_smarty_tpl->tpl_vars['self_data']->value['position_head'];?>
<br \></span><?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
</td>
        <td style="border-bottom: 1px solid #00000a;"></td>
        <td valign="bottom"><?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</td>
    </tr>
    <tr>
        <td></td>
        <td><span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td>
        <td><span style="font-size: 8px; padding-left: 30px;">(Ф.И.О)</span></td>
    </tr>
    <tr >
        <?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?>
            <td  colspan="2" >
        
        <div style="position: relative; top: 0px; right: 0px;">        
        <div style="position: absolute; top: -90px; right: -10px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="150px" align="top" ></div>
        <div style="position: absolute; top: -60px; right: 90px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
        </div>
            </td>
        <?php }?>
    </tr>   
    </table>

<!--
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	РАСПИСАНИЕ
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
-->




<p class="right"  style="page-break-before: always;"> УТВЕРЖДАЮ</p>
<p class="right" > Директор <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
</p>
<p class="right" > <?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="150px" align="top" style="position: absolute; top: 5px; right: 50px;"><?php }?>_______________  <?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</p>
<p class="right" > <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_begin'],"%02e.%m.%Y");?>
</p>


<p class="center" ><b>РАСПИСАНИЕ</b></p>
<p class="center" ><b>учебной группы № <?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
</b></p>
<p class="center" ><b>по программе «<?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
»</b></p>

<p class="left" >Нормативный срок освоения  – <?php echo $_smarty_tpl->tpl_vars['lstream']->value['hours'];?>
 ч.</p>
<p class="left" >Период обучения
 с <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_begin'],"%02e.%m.%Y");?>
 по  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_protocol'],"%02e.%m.%Y");?>
</p>
<p class="left" >Занятия с 9.00-18.00 (по 45 минут, 15 минут перемена, с 13.00-14.00 обед)</p>
<br/>

<table width="100%" border="1" cellpadding="4" cellspacing="0" style="page-break-before: avoid;">
    <tr>
        <th style=" text-align: center; font-size: 7pt;">Дата</th>
        <th style=" text-align: center; font-size: 7pt;">№ п/п</th>
        <th style=" text-align: center; font-size: 7pt;">Тема занятий и учебные вопросы</th>
        <th style=" text-align: center; font-size: 7pt;">Кол-во часов</th>
        <th style=" text-align: center; font-size: 7pt;">Преподаватель </th>
    </tr>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['calendar']->value, 'row');
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
        <tr><td style=" font-size: 7pt;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['row']->value['date'],"%02e.%m.%Y");?>
</td>
            <td style=" text-align: center; font-size: 7pt;"><nobr><?php echo $_smarty_tpl->tpl_vars['row']->value['topic'];?>
</nobr></td>
            <td style=" font-size: 7pt;"><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
</td>
            <td style=" font-size: 7pt; text-align: center;"><?php echo round(intval($_smarty_tpl->tpl_vars['row']->value['hours']),0);?>
</td>
            <td style=" font-size: 7pt;"><?php echo $_smarty_tpl->tpl_vars['teacher']->value['fullname'];?>
</td>
        </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>


<!--
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	ПРИКАЗ О ВЫПУСКЕ
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
-->



<h2 class="western" style="page-break-before: always;">ПРИКАЗ</h2>

<table width="100%"><tr><td style="font-size: 12pt;"><p class="western" > №<?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
 </p></td> <td width="10%" style="font-size: 12pt; text-align: right;"><p class="western"  style="text-align: right;"> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_protocol'],"%02e.%m.%Y");?>
</p></td></tr></table>        
<br \>        
        <p class="western">О выпуске группы № <?php echo $_smarty_tpl->tpl_vars['lstream']->value['name'];?>
<br \><br \>
        </p>
<p class="western">
    В связи с окончанием обучения по программе &laquo;<?php echo $_smarty_tpl->tpl_vars['lstream']->value['course_name'];?>
&raquo;
    
    <br />
    <br />
</p>    
<p class="western" style="text-align:center;">ПРИКАЗЫВАЮ:</p> 
    

<ol>
    <li>

            Нижеперечисленных слушателей допустить к итоговой аттестации: 
            <br />
            <br />
            <table width="100%" cellpadding="2" cellspacing="0">
            <tr><th  width="5%" style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" >№ <br /><nobr>п/п</nobr></th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Фамилия </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Имя </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Отчество </th><th style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" > Должность </th></tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'cell', false, NULL, 'users', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['cell']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
$_smarty_tpl->tpl_vars['cell']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']++;
?>
            <tr>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: center;" ><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_users']->value['iteration'] : null);?>
.</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['lastname'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['firstname'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['middlename'];?>
</td>
		    <td style="border: 1px solid #00000a; padding-left: 8px;  padding-right: 8px; text-align: left;" ><?php echo $_smarty_tpl->tpl_vars['cell']->value['position'];?>
</td>
            </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </table>
    </li>
    <li style="text-align: justify;">
            Аттестационной комиссии провести итоговую аттестацию <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['lstream']->value['date_protocol'],"%02e.%m.%Y");?>
 и выдать документ об окончании обучения.
    </li>

</ol>
<br />
<br />
<table  cellpadding="2" cellspacing="0" width="100%" border="0">
    <tr>
        <td width="45%"><span style="text-transform: capitalize; "><?php echo $_smarty_tpl->tpl_vars['self_data']->value['position_head'];?>
<br \></span><?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
</td>
        <td style="border-bottom: 1px solid #00000a;"></td>
        <td valign="bottom"><?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</td>
    </tr>
    <tr>
        <td></td>
        <td><span style="font-size: 8px; padding-left: 30px;">(подпись)</span></td>
        <td><span style="font-size: 8px; padding-left: 30px;">(Ф.И.О)</span></td>
    </tr>
    <tr >
        <?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?>
            <td  colspan="2" >
        
        <div style="position: relative; top: 0px; right: 0px;">        
        <div style="position: absolute; top: -90px; right: -10px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="150px" align="top" ></div>
        <div style="position: absolute; top: -60px; right: 90px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
        </div>
            </td>
        <?php }?>
    </tr>   
    </table>



</body>
</html><?php }
}
