var DocsHowto = {
  data: function () {
    return {
	info9: '',
        role: '',
     }
   },


   mounted() {
    this.session = session_t


    },

	template: `
	<div><navigation></navigation><h3></h3>

        <div style="text-align: left;">
        <a href="/#/docs" class="nav-link" aria-current="page"><button class="btn btn-light" type="button"><i class="fa-regular fa-circle-left"></i>  Инструкция по системе </button></a>

        <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Куратор</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p style="margin: 0px;"><strong>Техническая поддержка</strong></p>
                    </div>
                    <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button></div>
                </div>
            </div>
        </div>
	<h3 style="text-align:center; margin-top:10px; margin-bottom:20px;">Часто задаваемые вопросы</h3>	
	<h5 style="color:red; text-align:left">Для обновления страницы используйте сочетание клавиш "ctrl + shift + R"</h5>
	<h4 style="text-align:left">Порядок оформления заявки в соответствии со статусом:</h4>
    <table class="iksweb"  width="100%" border=1 style="margin-top:10px; margin-bottom:20px;">
	<tbody>
		<tr style="border: 1px solid #595959; ">
			<td style="border: 1px solid #595959; ">№ шага</td>
			<td style="border: 1px solid #595959; ">Статус</td>
			<td style="border: 1px solid #595959; ">Описание</td>
			<td style="border: 1px solid #595959; ">Операции администратора</td>
			<td style="border: 1px solid #595959; ">Операции пользователя личного кабинета</td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">1</td>
			<td style="border: 1px solid #595959; ">Новая заявка </td>
			<td style="border: 1px solid #595959; ">Оформление списка слушателей, запись на курсы, оформление консультационных услуг</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; ">Направить на рассмотрение</td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">2</td>
			<td style="border: 1px solid #595959; ">Согласование договора, оплата</td>
			<td style="border: 1px solid #595959; ">формирование счета в 1C, замораживание прайс листа услуг и реквизитов участников, на момент формирования заявки</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; ">1)Оплачено<br />
			2)  вернуть на доработку (шаг 1)
			</td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">3</td>
			<td style="border: 1px solid #595959; ">Ожидание оплаты</td>
			<td style="border: 1px solid #595959; ">Проводка платежа через 1С</td>
			<td style="border: 1px solid #595959; ">Оплата получена</td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">3.1</td>
			<td style="border: 1px solid #595959; ">Оформление бухгалтерских документов до начала обучения,</td>
			<td style="border: 1px solid #595959; ">Оформление акта выполненых работ при предоплате.</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">4</td>
			<td style="border: 1px solid #595959; ">Комплектование групп</td>
			<td style="border: 1px solid #595959; ">Составление расписания занятий, назначение преподавателя</td>
			<td style="border: 1px solid #595959; ">Приступить к обучению</td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">5</td>
			<td style="border: 1px solid #595959; ">Обучение</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; ">Завершить обучение</td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">5.1</td>
			<td style="border: 1px solid #595959; ">Приостановлено</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">6</td>
			<td style="border: 1px solid #595959; ">Обучение завершено</td>
			<td style="border: 1px solid #595959; ">Назначение даты оформления акта выполненных работ</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">7</td>
			<td style="border: 1px solid #595959; ">Оформление документов</td>
			<td style="border: 1px solid #595959; ">Оформление акта выполненных работ и синхронизация акта с 1С</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
		<tr>
			<td style="border: 1px solid #595959; ">8</td>
			<td style="border: 1px solid #595959; ">Архив</td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
			<td style="border: 1px solid #595959; "></td>
		</tr>
	</tbody>
    </table>

    </div>
    </div>`
};