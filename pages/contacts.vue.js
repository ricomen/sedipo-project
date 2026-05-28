var Contacts = { 
	data: function () {
    return {
	info9: '',
        role: '',
     }
   },


   mounted() {
    this.session = session_t


    },

	template: `<div><navigation></navigation><h3></h3>
	<div class="wrapper"  style="height: 75vh;">
	<div style="margin-bottom:30px;"><h1>Контакты</h1>
	</div>
	<div style="align:left; margin-left:75px" >

	<table>
	<tr><td width="40%" style="text-align:left">Наименование организации:</td><td style="text-align:left">ООО «Интегрированное программное обеспечение»;</td></tr>
	<tr><td width="40%" style="text-align:left">Адрес:</td><td style="text-align:left">РФ, г. Москва, ул. Академика Королева, дом 13, стр.1;</td></tr>
	<tr><td width="40%" style="text-align:left">Официальный сайт:</td><td style="text-align:left">sedipo.ru;</td></tr>
	<tr><td width="40%" style="text-align:left">Электронная почта:</td><td style="text-align:left">sed@ipo5.ru;</td></tr>
	<tr><td width="40%" style="text-align:left">Тел.:</td><td style="text-align:left"><a href="tel:+79856198877" style="text-decoration: none; color: #000">+7 985-619 88 77</a>;</td></tr>
	<tr><td width="40%" style="text-align:left">Руководитель:</td><td style="text-align:left">Кадырбаева Гузель Радиковна</td></tr>
	</table>

	</div>
	</div>`
};