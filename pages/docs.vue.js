var Docs = {
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
	<h3 style="text-align:center; margin-top:10px; margin-bottom:20px;"> Инструкции по работе в СЭД</h3>	
<!--
        <ul>
           <li style="text-align: left;"><a href="/#/docs_howto" class="nav-link" aria-current="page">Часто задаваемые вопросы </a></li>
        </ul>
-->
    <div style="text-align: left" class="accordion border-bottom-0">
        <details class="accordion-item border-bottom-0">
            <summary class="accordion-button rounded-top" style="padding-bottom: 8px; ">
                <div class="accordion-header user-select-none">Инструкции по подключению к СЭД </div>
            </summary>
            <div class="accordion-body border-bottom" style="padding-left: 40px">
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/connection_order.pdf" target="_blank">Порядок подключения к СЭД</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/1_company_reg.pdf" target="_blank">1. Регистрация компании</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/2_commission_add.pdf" target="_blank">2. Добавление комиссии</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/3_course_category_edit.pdf" target="_blank">3. Редактирование категорий курсов</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/4_course_add.pdf" target="_blank">4. Добавление курса</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/5_teacher_add.pdf" target="_blank">5. Добавление преподавателя</a>
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="../docs/instructions/6_counterparty_add.pdf" target="_blank">6. Добавление контрагента</a>
            </div>
        </details>

        <details class="accordion-item border-bottom-0">
            <summary class="accordion-button" style="padding-bottom: 8px; ">
                <div class="accordion-header user-select-none">Инструкция по работе в системе </div>
            </summary>
            <div class="accordion-body border-bottom" style="padding-left: 40px">
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="https://center.sedipo.ru/docs/instructions/instruction.pdf" target="_blank">Инструкция для администратора</a>
            </div>
        </details>

        <details class="accordion-item border-bottom-0">
            <summary class="accordion-button" style="padding-bottom: 8px; ">
                <div class="accordion-header user-select-none">Инструкция по созданию и добавлению шаблона Word </div>
            </summary>
            <div class="accordion-body border-bottom" style="padding-left: 40px">
                <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover instruction-link"
                  href="https://center.sedipo.ru/docs/instructions/word.docx" target="_blank">Создание шаблона Word</a>
            </div>
        </details>

        <details class="accordion-item border-bottom-0">
            <summary class="accordion-button" style="padding-bottom: 8px; ">
                <div class="accordion-header user-select-none">Порядок оформления заявки</div>
            </summary>
            <div class="accordion-body border-bottom" style="padding-left: 40px">
                <h4 style="text-align: center">Порядок оформления заявки в соответствии со статусом:</h4>
                <table class="table table-striped" >
                <thead>
                    <tr>
                        <th>№ шага</th>
                        <th>Статус</th>
                        <th>Описание</th>
                        <th>Операции администратора</th>
                        <th>Операции пользователя личного кабинета</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Новая заявка </td>
                        <td>Оформление списка слушателей, запись на курсы, оформление
                            консультационных услуг</td>
                        <td></td>
                        <td>Направить на рассмотрение</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Согласование договора, оплата</td>
                        <td>формирование счета в 1C, замораживание прайс листа услуг и
                            реквизитов участников, на момент формирования
                            заявки</td>
                        <td></td>
                        <td>1) Оплачено<br />
                            2) Вернуть на доработку (шаг 1)
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Ожидание оплаты</td>
                        <td>Проводка платежа через 1С</td>
                        <td>Оплата получена</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3.1</td>
                        <td>Постоплата</td>
                        <td>Оформление акта выполненых работ при предоплате</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Комплектование групп</td>
                        <td>Составление расписания занятий, назначение преподавателя</td>
                        <td>Приступить к обучению</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Обучение</td>
                        <td></td>
                        <td>Завершить обучение</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5.1</td>
                        <td>Приостановлено</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Оформление документов</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Подписание актов выполненных работ</td>
                        <td>Выставление даты оформления акта выполненных работ</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Ожидание оплаты(постоплата)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Оформление документов</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Архив</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>

            </div>
        </details>


        <details class="accordion-item">
            <summary class="accordion-button" style="padding-bottom: 8px; ">
                <div class="accordion-header user-select-none">Часто задаваемые вопросы</div>
            </summary>
           
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />При открытии удостоверения (свидетельства, диплома) или протокола не появляется удостоверение (свидетельство, диплом) или протокол.
            </div>
             <div class="col-6">
                <b>Возможное решение:</b><br />Выбрать необходимый документ в настройках соответствующего курса (для удостоверения (свидетельства, диплома) – пункт шаблон документа, для протокола – пункт шаблон протокола)
            </div>
            </div>

            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />При открытии удостоверения (свидетельства, диплома) или протокола не появляется удостоверение (свидетельство, диплом) или протокол.
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Выбрать необходимый документ в настройках соответствующего курса (для удостоверения (свидетельства, диплома) – пункт шаблон документа, для протокола – пункт шаблон протокола)
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />В протоколе (дипломе, свидетельстве, удостоверении ) нет членов комиссии.
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />В настройках курса в пункте «состав комиссии» выбрать соответствующую курсу комиссию.
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />В протоколе (дипломе, свидетельстве, удостоверении) не появляются подписи членов комиссии.
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Добавить подписи в соответствующих составах комиссии (пункт курсы - комиссии)
                Так же такое возможно, если группа была добавлена на поздних этапах заявки. В этом случае необходимо перевести заявку в статус "формирование групп" (в нестройках нужной заявки на странице заявок), затем во вкладке "учебные группы" для данной заявки сформировать расписание для необходимого направления и перевести заявку на следующий статус.
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Не меняется (не появляется) номер приказа (новый номер приказа) в комиссии.
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />В пункте составы комиссий в соответствующей курсу комиссии добавить необходимую строку в пункте «Абзац протокола / Номер приказа»
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Диплом или удостоверение распечатываются на бланке неровно (текст не попадает в нужные поля или выходит за границы)
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Обычно данная ошибка появляется после изменения настроек при выводе на печать. При выводе документа на печать, в настройках печати попробуйте выбрать другой масштаб печати (обычно пункт "масштаб"). Так же могут быть изменены физические параметры принтера (передвинуты направляющие в лотке подачи бумаги), попробуйте их отрегулировать.
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />В протоколе выходит меньше обучающихся, чем заведено в заявке (в протоколе не хватает обучающегося)
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Такая ситуация может возникнуть при добавлении слушателя при уже сформированных группах. Необходимо перевести заявку в статус "формирование групп" и пройти по следующим за этим статусам.
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Слушатели из различных заявок попали в разные потоки. Как перенести слушателей в один поток?
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Нужно перевести заявки (кроме первой из них) в статус формирование групп, убрать соответствующие группы из учебного потока и при составлении календаря (формировании расписания) выбрать уже существующий поток (тот, который в первой заявке)
            </div>
            </div>            
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px; padding-bottom: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Не сохраняются подписи членов комиссии
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Такое может быть, если название файла подписи содержит недопустимые символы (например название на кириллице). Правильное название файла должно содержать только строчные и заглавные латинские буквы, формат файла - png (например FamiliyaIO.png) 
            </div>
            </div>
            <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px; padding-bottom: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Где находятся бухгалтерские документы?
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />Окно с бухгалтерскими документами отображается при нажатии на кнопку с номером заявки
            </div>
            </div>
                        <div class="row" style="padding-left: 40px; padding-right: 40px; padding-top: 20px; padding-bottom: 20px;">
            <div class="col-6">
                <b>Вопрос:</b><br />Нет названия удостоверения у слушателя в группе (на странице группы в пункте "документы" пустая строка)
            </div>
            <div class="col-6">
                <b>Возможное решение:</b><br />В настройках курса в пункте "Наименование документа" добавьте требуемое наименование документа. (Обычно можно добавить такое же как название шаблона)
            </div>
            </div>
        </details>

    </div>


    </div>`
};