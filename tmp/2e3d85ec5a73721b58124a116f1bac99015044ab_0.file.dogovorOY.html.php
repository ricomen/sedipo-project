<?php
/* Smarty version 4.3.2, created on 2026-04-29 04:21:41
  from '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOY.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_69f187556df9f3_08230052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2e3d85ec5a73721b58124a116f1bac99015044ab' => 
    array (
      0 => '/var/www/sed20.sedipo.ru/public_html/documents/contract1/dogovorOY.html',
      1 => 1772265418,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f187556df9f3_08230052 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/sed20.sedipo.ru/public_html/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style type="text/css">
	@page { margin-left: 1.4cm; margin-right: 0.9cm; margin-top: 2cm; margin-bottom: 1.5cm; size: A4 portrait; }
	p { margin-bottom: 0.25cm; direction: ltr; line-height: 120%; text-align: left; orphans: 2; widows: 2 }
	p.western {  font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; margin-top: 0; margin-bottom: 0.1cm; text-indent:40px; line-height:1;}
	p.westernli {  font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: justify; margin-top: 0.1cm; margin-bottom: 0.1cm; }
	h1.western { font-family: "DejaVu Serif", serif; font-size: 12pt; text-align: center; line-height: 90%; margin-top: 0cm; margin-bottom: 0.1cm; margin-left: 1cm; margin-right: 1cm   }
	h2.western { font-family: "DejaVu Serif", serif; font-size: 11pt; text-align: center }
	h3.western { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: center; margin-bottom: 0; }
	li.western { font-family: "DejaVu Serif", serif; font-weight:bold; font-size: 10pt; text-align: center; margin-left:-40px;}
	li.westernnormal { font-family: "DejaVu Serif", serif; font-weight:normal; font-size: 10pt; text-align: justify; margin-left:-40px;}
	p.table { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-top: 0; margin-bottom: 0;  text-align: justify; line-height: 120%; }
	li { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm; text-indent:20px; }
	td, th { font-family: "DejaVu Serif", serif; font-size: 10pt; text-align: left; margin-top: 0.1cm; margin-bottom: 0.1cm;  }
    
	p.cjk { font-family: "DejaVu Sans"; font-size: 12pt }
	p.ctl { font-size: 12pt }
	a:link { color: #0000ff }
	a.ctl:link { font-family: "DejaVu Sans" }
	ol {
    list-style: none;
    counter-reset: li;
    }
    li:before {
    counter-increment: li;
    content: counters(li,".") ". ";
    }
	.header,
	.footer {
    	width: 100%;
    	text-align: center;
    	position: fixed;
	}
	.header {
	    top: -75px;
		text-align: left;
	}
	.footer {
	    bottom: -20px;
	}
	.pagenum:before {
	    content: counter(page);
	}
	.logo { display: inline-block; padding-left:20px; width:50px; height:50px; position: absolute; margin-top:12px;margin-right:10px;}
	.text { display: inline-block; vertical-align: top; width: 100%; font-family: "Roboto", sans-serif; font-weight: 300; font-size: 8pt; line-height:95%; text-align: center; margin-left:0px; margin-top:30px; position: relative;}

    </style>
	<title>ДОГОВОР</title>
</head>
<body lang="ru-RU" link="#0000ff" vlink="#800000" dir="ltr">
	<div class="header">
	
		<div class="text" ><?php echo $_smarty_tpl->tpl_vars['self_data']->value['name'];?>
</div>
		<hr style="border-width:1px; margin-top: 20px;">
	</div>
	<div class="logo" style="margin-top:-65px; ;"><img src="https://<?php echo $_SERVER['SERVER_NAME'];?>
/images/logo.png" width="50" height="50"></div>
	<div class="footer">
		<span class="pagenum"></span>
	</div>



<h1 class="western">ДОГОВОР № <?php echo $_smarty_tpl->tpl_vars['contract_data']->value['contract_number'];?>
</h1>


<table border="0" width="100%"><tr><td><p class="westernli" >г.Уфа</p></td><td></td> <td align="right" style="text-align: right"><p class="westernli" style="text-align: right" ><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['contract_data']->value['date_contract'],"%e.%m.%Y");?>
</p></td></tr></table>
<p class="western" > 
<b><?php echo $_smarty_tpl->tpl_vars['order_data']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>
)</b>, именуемое в дальнейшем ЗАКАЗЧИК,  
в лице <?php echo $_smarty_tpl->tpl_vars['order_data']->value['position_head2'];?>
  
<?php echo $_smarty_tpl->tpl_vars['order_data']->value['enterprise_manager2'];?>
,
действующего на основании Устава, с одной стороны и</p>

<p class="western" > <b><?php echo $_smarty_tpl->tpl_vars['self_data']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
),</b>
именуемое в дальнейшем ИСПОЛНИТЕЛЬ, в лице директора <?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager2'];?>
,
действующего на основании Устава, с другой стороны, заключили настоящий договор о нижеследующем:</p>




<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->


<h3 style="page-break-before: avoid;"  class="western">1.	ПРЕДМЕТ ДОГОВОРА</h3>


<p class="western">1.1.&nbsp;ЗАКАЗЧИК поручает, а ИСПОЛНИТЕЛЬ принимает на себя обязательства по выполнению услуг по подготовке и аттестации персонала в области неразрушающего контроля специалистов ЗАКАЗЧИКА.</p>

<p class="western">1.2.&nbsp;Требования к услугам, являющимся предметом Договора:
подготовка и аттестация должны проводиться в соответствии с «Правилами аттестации персонала в области неразрушающего контроля» СДАНК-02-2020.
</p>
<p class="western">1.3.&nbsp;Права на оказание услуг по аттестации предоставлены ИСПОЛНИТЕЛЮ на основании Свидетельства о признании экзаменационного центра №39-06 от 04.12.2024 г.
</p>
<p class="western">1.4.&nbsp;Сроки аттестации определяются заявкой на аттестацию.
</p>
<p class="western">1.5.&nbsp;Место выполнения работ по настоящему Договору: г. Уфа, ул. Проспект Октября, 152.
</p> 
<h3 style="page-break-before: avoid;"  class="western">2.	ОБЯЗАТЕЛЬСТВА И ПРАВА СТОРОН</h3>


<p class="western">2.1.&nbsp;ИСПОЛНИТЕЛЬ обязуется:
</p>
<p class="western">2.1.1.&nbsp;Обеспечить выполнение услуг, составляющих предмет Договора, в соответствии с действующими нормативами.
</p>
<p class="western">2.1.2.&nbsp;Кандидату, успешно прошедшему аттестацию, выдать документы установленного образца.
</p>
<p class="western">2.1.3.&nbsp;Кандидату, не получившему проходной оценки, по любой части квалификационного экзамена, предоставить право дважды пересдать несданную часть не ранее, чем через один месяц и не позднее, чем через два года после первой попытки сдать экзамен (п. 8.2 СДАНК-02-2020).
</p>
<p class="western">2.1.4.&nbsp;При повторной сдаче экзамена взимается дополнительная плата в размере 2 500 (две тысячи пятьсот) рублей на одного специалиста (НДС не облагается), с оформлением Дополнительного соглашения.
</p>
<p class="western">2.1.5.&nbsp;В случае отсутствия на занятиях кандидата предоставить ему возможность завершить подготовку и/или аттестацию с другой группой в соответствии с планом комплектования групп и графиком аттестации, утвержденным Исполнителем.
</p>
<p class="western">2.2.&nbsp;ЗАКАЗЧИК обязуется:
</p>
<p class="western">2.2.1.&nbsp;Документально подтвердить действительность сведений, предоставляемых на кандидата, включающие данные об образовании, подготовке и стаже, состоянии зрения (п. 2.1 СДАНК-02-2020).
</p>
<p class="western">2.2.2.&nbsp;При первичной аттестации предоставить документальное подтверждение, что кандидат успешно закончил курс подготовки по методу (виду), заявленному на аттестацию (п. 3.2 СДАНК 02-2020).
</p>
<p class="western">2.2.3.&nbsp;До начала срока аттестации, указанного в п.1.4. настоящего Договора, представить ИСПОЛНИТЕЛЮ заявку с приложением документов в соответствии с п. 5.3. СДАНК-02-2020 на кандидата в соответствии с установленной формой.
</p>
<p class="western">2.2.4.&nbsp;Обеспечить явку на занятия своих сотрудников.
</p>
<p class="western">2.2.5.&nbsp;Извещать ИСПОЛНИТЕЛЯ об уважительных причинах отсутствия своих сотрудников.
</p>
<p class="western">2.2.6.&nbsp;Своевременно оплатить стоимость работ.
</p>
<p class="western">2.3.&nbsp;ИСПОЛНИТЕЛЬ вправе:
</p>
<p class="western">2.3.1.&nbsp;Выбирать формы, методы и средства при организации подготовки к аттестации.
</p>
<p class="western">2.3.2.&nbsp;Самостоятельно осуществлять процесс подготовки и аттестации продолжительностью и сроками, определенными программами подготовки, расписанием занятий, графиком аттестации.
</p>
<p class="western">2.3.3.&nbsp;Производить перенос сроков аттестации кандидата, не завершившего сдачу какой-либо части квалификационного экзамена по уважительным причинам, на следующий за текущим период аттестации согласно графику аттестации.
</p>
<p class="western">2.3.3.&nbsp;Производить перенос сроков аттестации кандидата, не завершившего сдачу какой-либо части квалификационного экзамена по уважительным причинам, на следующий за текущим период аттестации согласно графику аттестации.
</p>


<p class="western">2.4.&nbsp;ЗАКАЗЧИК вправе:
</p>
<p class="western">2.4.1.&nbsp;Получать информацию от Исполнителя по вопросам организации и осуществления подготовки и аттестации специалистов.
</p>
<p class="western">2.4.2.&nbsp;Требовать представления бухгалтерской (финансовой) отчётности ИСПОЛНИТЕЛЯ в течение 10 дней с даты запроса.
</p>
<p class="western">2.4.3.&nbsp;Переносить сроки оказания услуг по письменному согласованию с ИСПОЛНИТЕЛЕМ на другие даты в соответствии с графиком аттестации ИСПОЛНИТЕЛЯ.
</p>
<h3 style="page-break-before: avoid;"  class="western">3.	СТОИМОСТЬ РАБОТ И ПОРЯДОК РАСЧЕТОВ</h3>


<p class="western">3.1.&nbsp;Стоимость работ по настоящему Договору определяется заявкой на аттестацию. НДС не облагается в связи с применением упрощённой системы налогообложения (ст. 346.11 НК РФ).
</p>
<p class="western">3.2.&nbsp;Оплата по настоящему Договору производится ЗАКАЗЧИКОМ в виде предоплаты в размере 100% стоимости работ, согласно выставленному счёту.
</p>
<p class="western">3.3.&nbsp;Стороны пришли к соглашению, что в рамках Договора проценты в соответствии со ст. 317.1 ГК РФ Сторонами не начисляются и не уплачиваются.
</p>
<h3 style="page-break-before: avoid;"  class="western">4.  ПОРЯДОК СДАЧИ И ПРИЕМКИ РАБОТ</h3>


<p class="western">4.1.&nbsp;Оказанные услуги принимаются ЗАКАЗЧИКОМ на основании акта выполненных работ. ЗАКАЗЧИК в течение 10 дней со дня получения акта выполненных работ обязан передать ИСПОЛНИТЕЛЮ подписанный акт или мотивированный отказ от подписания акта.
</p>
<p class="western">4.2.&nbsp;Удостоверения выдаются ЗАКАЗЧИКУ после завершения процедуры аттестации, подписания акта выполненных работ и оплаты услуг по Договору.
</p>
<h3 style="page-break-before: avoid;"  class="western">5. ОТВЕТСТВЕННОСТЬ СТОРОН</h3>


<p class="western">5.1.&nbsp;В случае умышленной или неосторожной порчи или уничтожения имущества ИСПОЛНИТЕЛЯ (оборудования, материалов и комплектующих изделий, вычислительной техники, учебных образцов и т. п.) аттестуемым специалистом ЗАКАЗЧИКА, ЗАКАЗЧИК несёт материальную ответственность и обязан возместить нанесённый ИСПОЛНИТЕЛЮ ущерб.
</p>
<p class="western">5.2.&nbsp;За нарушение условий настоящего Договора стороны несут ответственность в соответствии с действующим законодательством.
</p>
<p class="western">5.3.&nbsp;Споры по данному Договору, неурегулированные путём переговоров, рассматриваются в Арбитражном суде Республики Башкортостан.</p>

<h3 style="page-break-before: avoid;"  class="western">6. ФОРС - МАЖОРНЫЕ ОБСТОЯТЕЛЬСТВА</h3>


<p class="western">6.1.&nbsp;Стороны освобождаются от ответственности за частичное или полное неисполнение обязательств по настоящему Договору, если неисполнение явилось следствием природных явлений, действий внешних объективных факторов и прочих обстоятельств непреодолимой силы, за которые стороны не отвечают и предотвратить неблагоприятное
</p>
<h3 style="page-break-before: avoid;"  class="western">7.	КОНФИДЕНЦИАЛЬНОСТЬ</h3>


<p class="western">7.1.&nbsp;Стороны принимают на себя обязательство обеспечить конфиденциальность всей информации (документации, знаний, опыта и т.д.), которая станет им известной в связи с заключением настоящего договора.
</p>
<p class="western">7.2.&nbsp;Каждая Сторона вправе передавать другой Стороне персональные данные своих сотрудников, представителей: фамилия имя, отчество, номер  телефона, адрес  электронной почты, должность, иные сведения, а  друга  Сторона  вправе  их  обрабатывать  (включая сбор, запись,  систематизацию, накопление, хранение, уточнение (обновление, изменение) извлечение, использование, передачу (предоставление, доступ), обезличивание, блокирование, удаление, уничтожение с  использованием  средств  автоматизации или  без  использования таких средств), когда такая обработка необходима для заключения и исполнения, расторжения договора, обеспечения  прав  и законных интересов  Сторон,  в течение срока действия договора либо до момента отзыва согласия,  если  более  длительный  срок  не  предусмотрен законодательством.
</p>
<p class="western">7.3.&nbsp;Заказчик поручает Исполнителю обработку персональных данных работников для целей обучения и проверки знаний (фамилия, имя, отчество, дата рождения, пол, данные документа удостоверяющего личность, данные СНИЛС, информация о документах об образовании, квалификация по диплому.) на срок исполнения договора и хранения документов Исполнителем, путем осуществления сбора, записи, систематизации, накопления, хранения, уточнения (обновления, изменения), использования, извлечения, передачу (предоставление, доступ) в  образовательные  организации, аттестационные комиссии, формируемые в соответствии с  требованиями законодательства для целей исполнения настоящего договора, в том числе для оформления документов о прохождении обучения, проверке  знаний, занесения данных в Федеральный реестр сведений о документах об образовании и (или) о квалификации, документах об обучении (ФИС ФРДО), а  также обезличивания, блокирование, удаление, уничтожения персональных данных.
</p>
<p class="western">7.4.&nbsp;Получающая Сторона обязуется обеспечивать конфиденциальность и безопасность при обработке персональных данных, принимать необходимые правовые, организационные, технические меры для защиты персональных данных от неправомерного доступа или случайного доступа к ним, уничтожения, изменения, блокирования, копирования, предоставления, распространения, иных неправомерных действий в отношении персональных данных.
</p>
<p class="western">7.5.&nbsp;Стороны заверяют, что имеются действующие согласия субъектов или иные законные основания на передачу их персональных данных в адрес получающей Стороны и на последующую обработку их персональных данных получающей Стороной в объёме и на условиях договора.
</p>
<p class="western">7.6.&nbsp;Каждая Сторона обязуется по запросу другой Стороны в течение 5 (пяти) рабочих дней с даты получения такого запроса предоставить такой другой Стороне доказательства надлежащего исполнения своих обязательств в области обработки персональных данных и обеспечения мер их защиты.
</p>
<p class="western">7.7.&nbsp;Каждая из Сторон обязуется незамедлительно уведомлять другую Сторону обо всех фактах и попытках несанкционированного доступа к полученным персональным данным и (или) информации, содержащей персональные данные, других нарушениях порядка обработки персональных данных.
</p>
<p class="western">7.8.&nbsp;Стороны не осуществляют трансграничную передачу персональных данных
</p>
<h3 style="page-break-before: avoid;"  class="western">8. СРОК ДЕЙСТВИЯ ДОГОВОРА</h3>


<p class="western">8.1.&nbsp;Настоящий Договор вступает в силу со дня его подписания обеими сторонами и действует до полного исполнения сторонами Договорных обязательств.
</p>
<h3 style="page-break-before: avoid;"  class="western">9. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</h3>


<p class="western">9.1.&nbsp;ЗАКАЗЧИК даёт согласие на получение специалистами ЗАКАЗЧИКА информации о ходе работ по Договору в виде СМС-рассылок от Экзаменационного центра №39-06 в течение срока действия Договора.</p>

<p class="western">9.2.&nbsp;Любые изменения и дополнения к настоящему Договору действительны при условии, если они совершены в письменной форме и подписаны уполномоченными на то представителями сторон.
</p>
<p class="western">9.3.&nbsp;Настоящий Договор составлен в двух экземплярах, имеющих одинаковую юридическую силу, по одному экземпляру для каждой из сторон.
</p>
<h3 style="page-break-before: avoid;"  class="western">10. ЮРИДИЧЕСКИЕ АДРЕСА И БАНКОВСКИЕ РЕКВИЗИТЫ СТОРОН</h3>

<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

<table style="page-break-before: avoid;" width="100%" cellpadding="7" cellspacing="0" style="page-break-before: avoid;">
	<tr valign="top">


		<td  width="49%" style="border: none; padding: 0mm 1.91mm"><p class="table" >
            <p class="table" >  Заказчик:</p>
			<p class="table" > <?php echo $_smarty_tpl->tpl_vars['order_data']->value['shortname'];?>
  </p>
			<p class="table" >тел. <?php echo $_smarty_tpl->tpl_vars['order_data']->value['phone'];?>
 </p>			
			<p class="table" >ИНН: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['inn'];?>
</p>
			<p class="table" >КПП: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['kpp'];?>
</p>
			<p class="table" >ОГРН: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['ogrn'];?>
</p>
			<p class="table" >Юридический адрес: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['addres1'];?>
 </p>			
			<p class="table" >Расчетный счет: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['checking_account'];?>
</p>
			<p class="table" ><?php echo $_smarty_tpl->tpl_vars['order_data']->value['bank'];?>
 </p>
			<p class="table" >БИК: <?php echo $_smarty_tpl->tpl_vars['order_data']->value['bik'];?>
</p>
			<p class="table" ><br/></p>
			<p class="table" ><br/></p>
			<p class="table" ><br/></p>
			<p class="table" ><br/></p>
		</td>
		<td width="49%" style="border: none; padding: 0mm 1.91mm"><p class="table" >
			<p class="table" > Исполнитель:</p>
			<p class="table" > <?php echo $_smarty_tpl->tpl_vars['self_data']->value['shortname'];?>
 </p>
			<p class="table" > тел. <?php echo $_smarty_tpl->tpl_vars['self_data']->value['phone'];?>
</p>
			<p class="table" > ИНН / КПП <?php echo $_smarty_tpl->tpl_vars['self_data']->value['inn'];?>
 / <?php echo $_smarty_tpl->tpl_vars['self_data']->value['kpp'];?>
</p>
			<p class="table" > ОГРН <?php echo $_smarty_tpl->tpl_vars['self_data']->value['ogrn'];?>
</p>
			<p class="table" > Юридический адрес: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['addres1'];?>
</p>
			<p class="table" > Почтовый адрес: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['addres2'];?>
</p>
			<p class="table" > Расчетный счет: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['checking_account'];?>
</p>
			<p class="table" > БИК: <?php echo $_smarty_tpl->tpl_vars['self_data']->value['bik'];?>
 <?php echo $_smarty_tpl->tpl_vars['self_data']->value['bank'];?>
</p>
		</td>

	</tr>
	<tr>

		<td>
		<p class="table" > <?php echo $_smarty_tpl->tpl_vars['order_data']->value['position_head'];?>
</p>
		<p class="table" >_________________________/<?php echo $_smarty_tpl->tpl_vars['order_data']->value['enterprise_manager3'];?>
/</p>
		<p class="table">М.П.</p>
		</td>
		<td>
			<p class="table" > Директор			</p>
			<p class="table" > ___________________ /<?php echo $_smarty_tpl->tpl_vars['self_data']->value['enterprise_manager3'];?>
/</p>
			<p class="table" > М.П.</p>
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
</html><?php }
}
