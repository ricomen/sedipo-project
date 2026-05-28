<?php
/* Smarty version 4.3.2, created on 2026-04-13 09:50:37
  from '/var/www/sed20.sedipo.ru/public_html/documents/certificate/ipo-diplom1-a1.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69dcbc6d9bcba6_04869086',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '48d469d182a658f06d2ed9aa2d077639c376cff8' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/certificate/ipo-diplom1-a1.html',
      1 => 1758525571,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69dcbc6d9bcba6_04869086 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 0.1cm; margin-right: 0.1cm; margin-top: 0.1cm; margin-bottom: 0.1cm; size: A4 landscape;  }
	p {  text-align: left; orphans: 2; widows: 2 }
	p.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify;  margin-bottom: 4px;  margin-top: 4px; }
    p.westerntbl { font-family: "DejaVu Serif", serif; font-size: 7pt; text-align: justify;  margin-bottom: 2px;  margin-top: 2px; line-height: 70%;}
    p.centertbl { font-family: "DejaVu Serif", serif; font-size: 7pt; text-align: center;  margin-bottom: 2px;  margin-top: 2px; line-height: 70%;}
	p.center { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center;   margin-bottom: 4px;  margin-top: 4px;  }
	p.left { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: left;   margin-bottom: 4px;  margin-top: 4px;  }
	p.right { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: right;   margin-bottom: 4px;  margin-top: 4px;  }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 14pt; text-align: center;  margin-bottom: 15px;  margin-top: 15px;  }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 13pt; text-align: center;  margin-bottom: 7px;  margin-top: 7px;  }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center;  margin-bottom: 5px;  margin-top: 5px;  }
	h4.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center;  margin-bottom: 5px;  margin-top: 5px;  }
	p.table { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left }
    </style>
	<title>Документ о квалификации</title>
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#000000" vlink="#000000" alink="#000000" >

<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?>
<div style="position: absolute; z-index: -1; margin-left: 52.5%;  margin-top: -9px;">
    <img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/fonimages/diplom2.png" width="50%" >
</div>
<?php }?>
<table border="0" width="100%">
<!--<tr align="center" style="background-image: url(documents/udostoverenie1_1.png); background-repeat: no-repeat;">-->
    <tr valign="top">
        <td width="50%"></td>    
        <td  align="left"  width="50%" style="padding-left: 30px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td height="67px" valign="bottom" colspan="2">
                        <p class="right" style=" margin-bottom: 4px;  margin-top: 30px; margin-right: 10px; font-size: 9pt;"><?php if ($_smarty_tpl->tpl_vars['user']->value['blank_number'] != '') {
echo $_smarty_tpl->tpl_vars['user']->value['blank_number'];
} else { ?>????????<?php }?></p>
                        
                    </td>
                </tr>
                <tr>
                    <td width="40%" valign="top">
                        <p class="western" ></p>
                    </td>
                    <td height="145px">
                        <p class="western" style=" margin-bottom: 4px;  margin-top: 45px; margin-left: 50px; line-height: 200%;"><?php echo $_smarty_tpl->tpl_vars['user']->value['lastname'];?>
 <br /><?php echo $_smarty_tpl->tpl_vars['user']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['middlename'];?>
</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="126px" valign="bottom">
                        <p class="western" style=" margin-bottom: 4px;  margin-top: 4px; margin-left: 150px; margin-right: 10px; text-indent: 170px; line-height: 180%;"><?php echo $_smarty_tpl->tpl_vars['user']->value['level_of_diplom'];?>
 </p>
                        <p class="western" style=" margin-bottom: 4px;  margin-top: 4px; margin-left: 100px; line-height: 180%;"><!--<?php echo $_smarty_tpl->tpl_vars['user']->value['level_of_education'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['diplom_series'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['diplom_number'];?>
 <?php echo $_smarty_tpl->tpl_vars['user']->value['diplom_lastname'];?>
--> </p>
                        <p class="center" style=" margin-bottom: 4px;  margin-top: 4px; margin-left: 10px; line-height: 180%;"><?php echo $_smarty_tpl->tpl_vars['cert']->value['date_begin_rus'];?>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $_smarty_tpl->tpl_vars['cert']->value['date_end_rus'];?>
</p>
<!--                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="40px" valign="bottom">
                        <p class="center" style=" margin-bottom: 4px;  margin-top: 0px;  margin-left: 70px;  margin-right: 50px; line-height: 180%;"><?php echo $_smarty_tpl->tpl_vars['cert']->value['date_begin_rus'];?>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $_smarty_tpl->tpl_vars['cert']->value['date_end_rus'];?>
</p>
-->                        
                    </td>
                </tr>    
                <tr>
                    <td colspan="2" height="108px" valign="bottom">
                        <p class="center" style=" margin-bottom: 4px;  margin-top: 10px;  margin-left: 70px;  margin-right: 30px; line-height: 200%; ">Общество с ограниченной ответственностью<br /> «Институт профессионального образования»</p>  
                    </td>
                </tr>    
                <tr>
                    <td colspan="2" valign="top"  style="height: 112px; ">
                        <p class="center" style=" margin-bottom: 4px;  margin-top: 5px;  margin-left: 130px;  margin-right: 30px; line-height: 180%;">профессиональной переподготовки: </p>
                        <p class="center" style=" margin-bottom: 4px;  margin-top: 0px;  margin-left: 70px;  margin-right: 30px; line-height: 200%;"><?php echo $_smarty_tpl->tpl_vars['cert']->value['name'];?>
</p>
                    </td>
                </tr> 
                <tr >
                    <td colspan="2" valign="top"  style="height: 65px;" >
                        <p class="right" style=" margin-bottom: 4px;  margin-top: 0px;  margin-left: 20px;  margin-right: 10px; font-size: 9pt; line-height: 180%;">не предусмотрена</p>
                    </td>
                </tr>  
                <tr>
                    <td colspan="2" valign="top" style="height: 0px;">
                        <p class="right" style=" margin-bottom: 4px;  margin-top: 8px;  margin-left: 20px;  margin-right: 10px; font-size: 9pt; line-height: 180%;">не предусмотрена</p>
                        <br><br><br><br>
                    </td>
                </tr> 
            
            </table>
        </td>
    
    </tr>
</table>
<!--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE-->
<!--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE-->
<!--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE--NEXPAGE-->

<?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?>
<div style="position: absolute; z-index: -1; margin-left: 53%;  margin-top: -5px; page-break-before:always ">
    <img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/fonimages/diplom3.png"   width="50%" >
</div>
<table border="0" width="100%">
<?php } else { ?>
<table border="0" width="100%" style="page-break-before: always;">
<?php }?>
    <tr>
        <td width="48%"></td>
        <td width="50%">
            <div  style="border: 0px solid black" >
                <table border="0" width="80%" cellpadding="2" cellspacing="0"  style="margin-top: 127px; margin-left: 82px; height: 445px; " >
            
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['course_programm']->value, 'row', false, NULL, 'i', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
                    <tr>
                        <td valign="top" width="8%" style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="westerntbl" ><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</p></td>
                        <td valign="top" width="48%" style=" padding-left: 8px;  padding-right: 8px; text-align: left; "><p class="westerntbl" ><?php echo $_smarty_tpl->tpl_vars['row']->value['name_topic'];?>
</p></td>
                        <td valign="top" width="27%"style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="westerntbl" style="margin-left: 40px;"><?php echo round($_smarty_tpl->tpl_vars['row']->value['hours'],0);?>
</p></td>
                        <td valign="top" style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="centertbl" >хорошо</p></td>
                    </tr>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <tr>
                        <td valign="top" width="8%" style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="westerntbl" ><?php echo count($_smarty_tpl->tpl_vars['course_programm']->value)+1;?>
</p></td>
                        <td valign="top" width="48%" style=" padding-left: 8px;  padding-right: 8px; text-align: left; "><p class="westerntbl" >Итоговая аттестация</p></td>
                        <td valign="top" width="27%"style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="westerntbl" style="margin-left: 40px;"><?php echo $_smarty_tpl->tpl_vars['cert']->value['hours_c'];?>
</p></td>
                        <td valign="top" style=" padding-left: 8px;  padding-right: 8px; text-align: center; "><p class="centertbl" >хорошо</p></td>
                    </tr>
                </table>
            </div>
        
            <table border="0" width="100%" cellpadding="0" cellspacing="0" >
                <tr >
                    <td  valign="top" height="40px" colspan="2" style=" padding-left: 100px;">
                        <p class="western" style="margin-bottom: 4px;  margin-top: 7px;  margin-left: 70px;  margin-right: 30px; font-size: 9pt;" ><nobr><b><?php echo $_smarty_tpl->tpl_vars['cert']->value['hours'];?>
</b></nobr></p>
                    </td>
                </tr>
                <tr >
                    <td  valign="top" height="20px" colspan="2" style="padding-top: 4px; padding-left: 100px;">
                        <p class="western" style="margin-bottom: 4px;  margin-top: 4px;  margin-left: 350px;  margin-right: 30px; font-size: 9pt; line-height: 180%;" ><nobr><b><i><?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
</i></b></nobr></p>
                    </td>
                </tr>
                <tr>
                    <td valign="top" height="20px" colspan="2" style="padding-left: 100px;">
                        <p class="western" style="margin-bottom: 4px;  margin-top: 5px;  margin-left: 350px;  margin-right: 30px; font-size: 9pt; line-height: 180%;" ><nobr><b><i>Батыров И. Х.</i></b></nobr></p>
                    </td>
                </tr>
                <tr>
                    <td >
        
                    <?php if ($_smarty_tpl->tpl_vars['print_v']->value == "false") {?> 
                    <div style="position: relative; top: 0px; right: 0px;">        
                    <div style="position: absolute; top: -80px; right: -175px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/podpis.png" width="70px" align="top" ></div>
                    <div style="position: absolute; top: -45px; right: -170px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/BatirovIH.png" width="70px" align="top" ></div>
                    <div style="position: absolute; top: -105px; right:-80px; "><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/documents/signs/Stamp.png" width="150px" align="top" ></div>
                    </div>
                    <?php }?>
        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html><?php }
}
