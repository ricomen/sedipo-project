var CourseEdit  = {
  data: function () {
    return {
      role: '',
      info: [],
      info2: [],
      info3: [],
      info4: [],
      info5: [],
      info7: [],
      info8: [],
      info9: [],
      info6: [],
      info10: [],
      info11: [],
      info12: [],
      message: '',
      course_id: 0,
      a_category_id: 0,
      performer_id: 0,
      name: '',
      shortname: '',
      profession: '',
      rank_of_profession: -1,
      description: '',
      competence: '',
      lms_course_id: 0,
      price: 0,
      price2: ' ',
      hours: '',
      hours_l: '',
      hours_p: '',
      hours_i: '',
      hours_c: '',
      price_multiplier: 1.0,
      hours_add: '',
      hours_l_add: '',
      hours_p_add: '',
      hours_i_add: '',
      hours_c_add: '',
      price_multiplier2: 1.0,
      hours_add2: '',
      hours_l_add2: '',
      hours_p_add2: '',
      hours_i_add2: '',
      hours_c_add2: '',
      is_main_module: 'false', 
      is_modules: 'false',
      is_rank_of_profession: 'true',
      category: 0,
      type_of_program_id: -1,
      counterparty_id: -1,
      form_of_study: [ {form_id: 1, name: 'очная'}, {form_id: 2, name:  'очная с применением дистанционных технологий'} , {form_id: 3, name: 'заочная'}, {form_id: 4, name:  'заочная с применением дистанционных технологий'} , {form_id: 5, name: 'очно-заочная'}, {form_id: 6, name:  'очно-заочная с применением дистанционных технологий'} ],
      form_id: 0,
      form2_id: [],
      delta2: '',
      qualification_work: '',
      name_common: '',
      lms_course_id: '',
      certificate1_template: 0,
      certificate1_name: '',
      certificate2_template: 0,
      certificate2_name: '',
      protocol_template: 0,
      teachers_commission: 0,
      qualification: '',
      opd: 0,
      eisot_id: 0,
      eisot_id_arr: [
        {'id': 1, 'name': 'Оказание первой помощи пострадавшим'},
        {'id': 2, 'name':  'Использование (применение) средств индивидуальной защиты'},
        {'id': 3, 'name':  'Общие вопросы охраны труда и функционирования системы управления охраной труда'},
        {'id': 4, 'name':  'Безопасные методы и приемы выполнения работ при воздействии вредных и (или) опасных производственных факторов, источников опасности, идентифицированных в рамках специальной оценки условий труда и оценки профессиональных рисков'},
        //{'id': 5, 'name':  'Безопасные методы и приемы выполнения работ повышенной опасности, к которым предъявляются дополнительные требования в соответствии с нормативными правовыми актами, содержащими государственные нормативные требования охраны труда'},
        {'id': 6, 'name':  'Безопасные методы и приемы выполнения земляных работ'},
        {'id': 7, 'name':  'Безопасные методы и приемы выполнения ремонтных, монтажных и демонтажных работ зданий и сооружений'},
        {'id': 8, 'name':  'Безопасные методы и приемы выполнения работ при размещении, монтаже, техническом обслуживании и ремонте технологического оборудования (включая технологическое оборудование)'},
        {'id': 9, 'name':  'Безопасные методы и приемы выполнения работ на высоте'},
        {'id': 10, 'name':  'Безопасные методы и приемы выполнения пожароопасных работ'},
        {'id': 11, 'name':  'Безопасные методы и приемы выполнения работ в ограниченных и замкнутых пространствах (ОЗП)'},
        {'id': 12, 'name':  'Безопасные методы и приемы выполнения строительных работ, в том числе: - окрасочные работы - электросварочные и газосварочные работы'},
        {'id': 13, 'name':  'Безопасные методы и приемы выполнения работ, связанных с опасностью воздействия сильнодействующих и ядовитых веществ'},
        {'id': 14, 'name':  'Безопасные методы и приемы выполнения газоопасных работ'},
        {'id': 15, 'name':  'Безопасные методы и приемы выполнения огневых работ'},
        {'id': 16, 'name':  'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией подъемных сооружений'},
        {'id': 17, 'name':  'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией тепловых энергоустановок'},
        {'id': 18, 'name':  'Безопасные методы и приемы выполнения работ в электроустановках'},
        {'id': 19, 'name':  'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией сосудов, работающих под избыточным давлением'},
        {'id': 20, 'name':  'Безопасные методы и приемы обращения с животными'},
        {'id': 21, 'name':  'Безопасные методы и приемы при выполнении водолазных работ'},
        {'id': 22, 'name':  'Безопасные методы и приемы работ по поиску, идентификации, обезвреживанию и уничтожению взрывоопасных предметов'},
        {'id': 23, 'name':  'Безопасные методы и приемы работ в непосредственной близости от полотна или проезжей части эксплуатируемых автомобильных и железных дорог'},
        {'id': 24, 'name':  'Безопасные методы и приемы работ, на участках с патогенным заражением почвы'},
        {'id': 25, 'name':  'Безопасные методы и приемы работ по валке леса в особо опасных условиях'},
        {'id': 26, 'name':  'Безопасные методы и приемы работ по перемещению тяжеловесных и крупногабаритных грузов при отсутствии машин соответствующей грузоподъемности и разборке покосившихся и опасных (неправильно уложенных) штабелей круглых лесоматериалов'},
        {'id': 27, 'name':  'Безопасные методы и приемы работ с радиоактивными веществами и источниками ионизирующих излучений'},
        {'id': 28, 'name':  'Безопасные методы и приемы работ с ручным инструментом, в том числе с пиротехническим'},
        {'id': 29, 'name':  'Безопасные методы и приемы работ в театрах'}
      ],

      opd_arr: [
         { 'id': 0, 'name': ''},
         { 'id': 1, 'name': 'Образование и наука'},
         { 'id': 2, 'name': 'Здравоохранение'},
         { 'id': 3, 'name': 'Социальное обслуживание'},
         { 'id': 4, 'name': 'Культура, искусство'},
         { 'id': 5, 'name': 'Физическая культура и спорт'},
         { 'id': 6, 'name': 'Связь, информационные и коммуникационные технологии'},
         { 'id': 7, 'name': 'Административно-управленческая и офисная деятельность'},
         { 'id': 8, 'name': 'Финансы и экономика'},
         { 'id': 9, 'name': 'Юриспруденция'},
         { 'id': 10, 'name': 'Архитектура, проектирование, геодезия, топография и дизайн'},
         { 'id': 11, 'name': 'Средства массовой информации, издательство и полиграфия'},
         { 'id': 12, 'name': 'Обеспечение безопасности'},
         { 'id': 13, 'name': 'Сельское хозяйство'},
         { 'id': 14, 'name': 'Лесное хозяйство, охота'},
         { 'id': 15, 'name': 'Рыбоводство и рыболовство'},
         { 'id': 16, 'name': 'Строительство и жилищно-коммунальное хозяйство'},
         { 'id': 17, 'name': 'Транспорт'},
         { 'id': 18, 'name': 'Добыча, переработка угля, руд и других полезных ископаемых'},
         { 'id': 19, 'name': 'Добыча, переработка, транспортировка нефти и газа'},
         { 'id': 20, 'name': 'Электроэнергетика'},
         { 'id': 21, 'name': 'Легкая и текстильная промышленность'},
         { 'id': 22, 'name': 'Пищевая промышленность, включая производство напитков и табака'},
         { 'id': 23, 'name': 'Деревообрабатывающая и целлюлозно-бумажная промышленность, мебельное производство'},
         { 'id': 24, 'name': 'Атомная промышленность'},
         { 'id': 25, 'name': 'Ракетно-космическая промышленность'},
         { 'id': 26, 'name': 'Химическое, химико-технологическое производство'},
         { 'id': 27, 'name': 'Металлургическое производство'},
         { 'id': 26, 'name': 'Производство машин и оборудования'},
         { 'id': 29, 'name': 'Производство электрооборудования, электронного и оптического оборудования'},
         { 'id': 30, 'name': 'Судостроение'},
         { 'id': 31, 'name': 'Автомобилестроение'},
         { 'id': 32, 'name': 'Авиастроение'},
         { 'id': 33, 'name': 'Сервис, оказание услуг населению (торговля, техническое обслуживание, ремонт, предоставление персональных услуг, услуги гостеприимства, общественное питание и пр.)'},
         { 'id': 40, 'name': 'Сквозные виды профессиональной деятельности'}
      ],

      okpdtr_code: '',
      okpdtr_area: '',
      okpdtr_arr: '',
      area: '',
      copy_id: 0,
      courses_lms: [],
      moodle_category_id: 0,

      helpers: {},
      normative: {},
      }
   },

   mounted() {
    this.course_id = Number(this.$route.params.courseid)
    this.a_category_id = Number(this.$route.params.categoryid)
    if(isNaN(this.a_category_id))
                this.a_category_id = 0
    
    this.category = this.a_category_id
    this.counterparty_id = Number(this.$route.params.counterpartyid)
    this.copy_id = Number(this.$route.params.copyid)
    if(isNaN(this.copy_id))
                this.copy_id = 0
    

    if( this.course_id > 0 ||   this.copy_id > 0 ){
     if( this.course_id > 0)
        var courseId = this.course_id
     else 
        var courseId = this.copy_id

     axios
      .post(JsonApiURL+'api/courses_json.php', {object: {objectId: courseId, sessionId: session_t.sessionId } })
      .then(response => { 
            var main_module=0
            this.info = response.data
            this.name = this.info.result.name
            this.shortname = this.info.result.shortname
            this.profession = this.info.result.profession
            this.rank_of_profession = this.info.result.rank_of_profession
            this.category = this.info.result.category_id
            this.performer_id = this.info.result.performer_id
            this.lms_course_id = this.info.result.moodle_course_id
            this.hours = this.info.result.hours
            this.hours_l = this.info.result.hours_l
            this.hours_p = this.info.result.hours_p
            this.hours_i = this.info.result.hours_i
            this.hours_c = this.info.result.hours_c
            this.hours_add = this.info.result.hours_add
            this.hours_l_add = this.info.result.hours_l_add
            this.hours_p_add = this.info.result.hours_p_add
            this.hours_i_add = this.info.result.hours_i_add
            this.hours_c_add = this.info.result.hours_c_add
            this.hours_add2 = this.info.result.hours_add2
            this.hours_l_add2 = this.info.result.hours_l_add2
            this.hours_p_add2 = this.info.result.hours_p_add2
            this.hours_i_add2 = this.info.result.hours_i_add2
            this.hours_c_add2 = this.info.result.hours_c_add2
            this.price_multiplier = this.info.result.price_multiplier
            this.price_multiplier2 = this.info.result.price_multiplier2
            this.form_id = this.info.result.form_of_study
            this.form2_id =  this.info.result.form_of_study_2.split(',')
            this.price = this.info.result.price
            this.name_common = this.info.result.name_common
            this.description = this.info.result.description
            this.competence = this.info.result.competence
            this.qualification_work = this.info.result.qualification_work
            this.delta2 = this.info.result.delta2
            this.protocol_template =   this.info.result.protocol_template
            this.certificate1_template =   this.info.result.certificate1_template
            this.certificate1_name =  this.info.result.certificate1_name
            this.certificate2_template =   this.info.result.certificate2_template
            this.certificate2_name =  this.info.result.certificate2_name
            this.teachers_commission =  this.info.result.commission_id
            this.qualification =  this.info.result.qualification
            this.okpdtr_code =  this.info.result.okpdtr_code
            this.area =  this.info.result.area
            this.eisot_id =  this.info.result.eisot_id
            this.opd =  this.info.result.opd
            
            if(this.okpdtr_code!=''){
                axios
                 .post(JsonApiURL+'api/okpdtr_json.php', {object: {objectId: this.okpdtr_code,   sessionId: session_t.sessionId }})
                 .then(response2 => { 
                   this.info9 = response2.data
                   this.okpdtr_area = this.info9.result.okpdtr_area
                   this.on_select_okpdtr_area()
                    console.log(response2)
                 })
                 .catch(error => {
                    console.log(error.response)
                 })
             }

            if(this.info.result.category_id>0){
                axios
                 .post(JsonApiURL+'api/course_category_json.php', {object: {objectId: this.info.result.category_id,   sessionId: session_t.sessionId }})
                 .then(response2 => { 
                   this.info9 = response2.data
                   this.moodle_category_id = this.info9.result.moodle_category_id

                   if(this.moodle_category_id>0){
                      axios
                       .post(JsonApiURL+'lms-api/lms_info_json.php', {courses_list: {category_id: this.moodle_category_id,  sessionId: session_t.sessionId }})
                       .then(response => { 
                            this.courses_lms = response.data.list
                       })
                       .catch(error => {
                         console.log(error.response)
                        })
                   }

                 })
                 .catch(error => {
                    console.log(error.response)
                 })
             }


            if(this.eisot_id <= 0){
                    this.eisot_id  = this.eisot_id_arr[this.eisot_id_arr.findIndex(item => item.name == this.name)].id
            }
            this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].modules
            this.type_of_program_id   = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].type_of_program_id
            this.type_of_education_id = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].type_of_education_id

            if(this.shortname == '')
                   this.shortname = this.info.result.name.substring(0, 50)

            if( this.course_id == 0 ) {
                this.name = this.info.result.name + ' - копия'
                this.shortname = this.shortname + ' - копия'
            }

            if(this.qualification_work == 'NULL' || this.qualification_work == null )
                   this.qualification_work = ''

            main_module = this.info.result.main_module
            if(main_module == 1)
                this.is_main_module = 'true'

            if(this.delta2 == 0 )
                  this.delta2 = '' 


        })
       .catch(error => {
              console.log(error.response)
        })
    }


    if(this.counterparty_id > 0 && this.course_id >0) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
    		.then(response3 => { 
        	    console.log(response3)
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	}),

    	    axios
    		.post(JsonApiURL+'api/price_json.php', {object: {objectId: this.course_id , where: "`counterparty_id`="+this.counterparty_id,   sessionId: session_t.sessionId } })
    		.then(response5 => { 
        	    console.log(response5)
        	    this.info5 = response5.data
        	    this.price2 = this.info5.result.price
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
    }
    

    axios
      .post(JsonApiURL+'api/template_json.php', {list: {where: "type=2", sessionId: session_t.sessionId } })
      .then(response => { 
            this.info7 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),
    
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {where: "type=3", sessionId: session_t.sessionId } })
      .then(response => { 
            this.info8 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),

    axios
      .post(JsonApiURL+'api/template_json.php', {list: {where: "type=1", sessionId: session_t.sessionId } })
      .then(response => { 
            this.info10 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),



    axios
      .post(JsonApiURL+'api/counterparty_json.php', {list: { where: "`type`=1", sessionId: session_t.sessionId }})
      .then(response => { 
            this.info2 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),

    axios
      .post(JsonApiURL+'api/teachers_commission_json.php', {list: { sessionId: session_t.sessionId } })
      .then(response => { 
            this.info6 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),


    axios
      .post(JsonApiURL+'api/course_category_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info4 = response.data
            //this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].is_rank_of_profession
            if(this.category>0){
                this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].modules
                this.type_of_program_id  = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].type_of_program_id
                this.type_of_education_id = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].type_of_education_id
            }
            this.role = this.info4.role
       })
      .catch(error => {
              console.log(error.response)
        }),



    axios
      .post(JsonApiURL+'api/okpdtr_json.php', {list1: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info12 = response.data
            this.okpdtr_area_arr = this.info12.list
       })
      .catch(error => {
              console.log(error.response)
        }),



     axios
      .post(JsonApiURL+'api/helpers_json.php', {table: 'a_course_edit',  sessionId: session_t.sessionId })
      .then(response => {
            const data = response.data;

            if(Object.keys(data).length) {

                const values = Object.values(data);
                for (const value of values) {
                    if(value['description']) this.helpers[value['name']] = value['description'];
                    if(value['normative']) this.normative[value['name']] = value['normative'];
                }

                const labels = document.querySelectorAll('label');
                labels.forEach(label => {
                    const getFor = label.htmlFor;
                    if(getFor in this.helpers) {
                        const help_block = ` <a title='${this.helpers[getFor]}'><span class="text-primary"><i class="fa-regular fa-circle-question"></i></span></a>`;
                        label.innerHTML = label.innerHTML + (help_block);
                    }
                    if(getFor in this.normative) {
                        const help_block = ` <a title='${this.normative[getFor]}'><span class="text-primary"><i class="fa-solid fa-book"></i></span></a>`;
                        label.innerHTML = label.innerHTML + (help_block);
                    }

                })

            } else {
                console.log('NO DATA')
            }

       })
      .catch(error => {
              console.log(error.response)
      })




  },

//    updated() {
//  },


  methods: {
        objectSave() {
        var main_module = 0 
        if( this.is_main_module == 'true')
            main_module = 1 

        if(this.delta2 == '')
            this.delta2 = '0';
        this.delta2 = parseInt(this.delta2);
        var form_of_study_2 = this.form2_id.join(',')


        axios
            .post(JsonApiURL+'api/courses_json.php', {update: {objectId: this.course_id, category_id: this.category, performer_id: this.performer_id, name: this.name,  shortname: this.shortname, profession: this.profession, rank_of_profession: this.rank_of_profession, hours: this.hours, hours_l: this.hours_l, hours_p: this.hours_p, hours_i: this.hours_i, hours_c: this.hours_c,  hours_add: this.hours_add, hours_l_add: this.hours_l_add, hours_p_add: this.hours_p_add, hours_i_add: this.hours_i_add, hours_c_add: this.hours_c_add,  hours_add2: this.hours_add2, hours_l_add2: this.hours_l_add2, hours_p_add2: this.hours_p_add2, hours_i_add2: this.hours_i_add2, hours_c_add2: this.hours_c_add2, price_multiplier: this.price_multiplier, price_multiplier2: this.price_multiplier2,  main_module: main_module,  form_of_study: this.form_id, form_of_study_2: form_of_study_2,   price: this.price, description: this.description, competence: this.competence, qualification_work: this.qualification_work,  name_common: this.name_common, delta2: this.delta2, protocol_template: this.protocol_template, certificate1_template: this.certificate1_template, certificate1_name: this.certificate1_name, certificate2_template: this.certificate2_template,  certificate2_name: this.certificate2_name, moodle_course_id: this.lms_course_id, commission_id: this.teachers_commission,  qualification: this.qualification, opd: this.opd, area: this.area, okpdtr_code: this.okpdtr_code, eisot_id: this.eisot_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data
              this.$router.push({ name: 'courses_list', params: { counterpartyid: this.counterparty_id }}) 

            })
            .catch(error => {
              console.log(error.response)
            })
         },

        objectCreate() {
        var main_module = 0 
        if( this.is_main_module == 'true')
            main_module = 1 

        if(this.delta2 == '')
            this.delta2 = '0';
        this.delta2 = parseInt(this.delta2);
        var form_of_study_2 = this.form2_id.join(',')
            
	    axios
            .post(JsonApiURL+'api/courses_json.php', {insert: {   category_id: this.category, performer_id: this.performer_id,  name: this.name,  shortname: this.shortname, profession: this.profession, rank_of_profession: this.rank_of_profession, hours: this.hours, hours_l: this.hours_l, hours_p: this.hours_p, hours_i: this.hours_i, hours_c: this.hours_c,  hours_add: this.hours_add, hours_l_add: this.hours_l_add, hours_p_add: this.hours_p_add, hours_i_add: this.hours_i_add, hours_c_add: this.hours_c_add,  hours_add2: this.hours_add2, hours_l_add2: this.hours_l_add2, hours_p_add2: this.hours_p_add2, hours_i_add2: this.hours_i_add2, hours_c_add2: this.hours_c_add2, price_multiplier: this.price_multiplier, price_multiplier2: this.price_multiplier2, main_module: main_module, form_of_study: this.form_id, form_of_study_2: form_of_study_2,   price: this.price,    description: this.description, competence: this.competence, qualification_work: this.qualification_work, name_common: this.name_common, delta2: this.delta2, protocol_template: this.protocol_template,  certificate1_template: this.certificate1_template, certificate1_name: this.certificate1_name, certificate2_template: this.certificate2_template,  certificate2_name: this.certificate2_name, moodle_course_id: this.lms_course_id,   commission_id: this.teachers_commission,  qualification: this.qualification, opd: this.opd, area: this.area, okpdtr_code: this.okpdtr_code, eisot_id: this.eisot_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data
              this.$router.push({ name: 'courses_list', params: { counterpartyid: this.counterparty_id }}) 

            })
            .catch(error => {
              console.log(error.response)
            })
         },

        objectSave2() {
           if( this.price2 != '' &&  Number(this.price2) >0  && this.course_id>0 && this.counterparty_id>0) {
	      axios
              .post(JsonApiURL+'api/price_json.php', {delete: {objectId: this.course_id,  where: "`counterparty_id`="+this.counterparty_id,  sessionId: session_t.sessionId } })
              .then(response => {
                  console.log(response)
    	          //this.info9 = response.data

	          axios
                  .post(JsonApiURL+'api/price_json.php', {insert: {objectId: this.course_id,  counterparty_id: this.counterparty_id,  price: this.price2,  sessionId: session_t.sessionId } })
                  .then(response => {
                     console.log(response)
    	             //this.info9 = response.data
                     this.$router.push({ name: 'courses_list', params: { counterpartyid: this.counterparty_id }}) 

                  })
                  .catch(error => {
                      console.log(error.response)
                  })

              })
              .catch(error => {
                console.log(error.response)
              })
           }
        },
        
        
        rank_of_profession_save()  {
            if(this.rank_of_profession >= 0 )
                    alert('Сохранить изменения?')
        },

        on_select_certificate1(){
            if(this.certificate1_name == ''){
                for(var i=0; i<this.info7.list.length; i++)
                    if(this.info7.list[i].template_id == this.certificate1_template)
                        break
                        
                this.certificate1_name = this.info7.list[i].name
            }
        },

        on_select_certificate2(){
            if(this.certificate2_name == ''){
                for(var i=0; i<this.info8.list.length; i++)
                    if(this.info8.list[i].template_id == this.certificate2_template)
                        break
                        
                this.certificate2_name = this.info8.list[i].name  
            }
        },

        on_select_category(){

        },

        on_select_opd(){
            if(this.area == ''){
                for(var i=0; i<this.opd_arr.length; i++){
                    if(this.opd_arr[i].id == this.opd){
                        this.area = this.opd_arr[i].name  
                        break
                    }
                } 
            }
        },


        on_select_okpdtr_area(){
           if(this.okpdtr_area != ''){
             axios
               .post(JsonApiURL+'api/okpdtr_json.php', {list: { okpdtr_area: this.okpdtr_area,  sessionId: session_t.sessionId }})
               .then(response => { 
                  this.info11 = response.data
                  this.okpdtr_arr = this.info11.list
                  console.log(response)
             })
             .catch(error => {
               console.log(error.response)
             })
          }
          else 
             this.okpdtr_arr = []
        }


   },


	template: `<div><navigation></navigation><h3>Редактирование курса</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
<div class="container">

<!--{{moodle_category_id}} -->

    <h4 v-if="counterparty_id>0"  style="text-align: right;" > {{info3.result.name}} </h4>

<span v-if="counterparty_id==0">
  <p   v-if="moodle_course_id>0"> Курс импортирован из LMS, возможность редактирования названия ограничена </p>
 
  <div  class="mb-3 row">	
  <label for="input_category"  class="form-label col-sm-3"   >Категория </label>
  <select v-model="category" class="form-select col" id="input_category" @change="on_select_category()" ><option  value="0"> - Категория - </option> 
      <option v-for="item_o in info4.list"   :value="item_o.category_id">{{item_o.name}}</option>
  </select>
  </div>

  <div   class="mb-3 row">
  <label for="input_type" class="form-label col-sm-3">Учебный центр</label>
  <select  v-model="performer_id"  class="form-select col" id="input_type" >
    <option value="0"> - Учебный центр - </option>
    <option v-for="item_p in info2.list" :value="item_p.counterparty_id">{{item_p.name}}</option>
  </select>
  </div>


  <div class="mb-3 row">
    <label for="input_name"  class="form-label col-sm-3"   >Название курса </label>
    <input  v-if="moodle_course_id>0"  v-model="name"   class="form-control col" id="input_name"   type="text" readonly  >
    <input v-else  v-model="name"   class="form-control col" id="input_name"   type="text"  >

  </div>


  <div class="mb-3 row">
  <label for="input_shortname"  class="form-label col-sm-3"   >Краткое название курса </label>
    <input v-model="shortname"   class="form-control col" id="input_shortname"   type="text"  >
    <div class="col-2"> <span v-if="rank_of_profession>0">({{rank_of_profession}} разряд)</span>  </div>
  </div>


  <div  v-if="is_rank_of_profession=='true'"  class="mb-3 row">	
  <label for="input_rank"  class="form-label col-sm-3"   >Разряд / Уровень  </label>
    <select  v-model="rank_of_profession"  class="form-control col" id="input_rank"> 
        <option  value="0"> - Разряд - </option> 
        <option v-for="index in [2,3,4,5,6,7,8]" :value="index">
	     {{index}} разряд  
	    </option>
    </select>
    <div class="col-7"><small>Данная опция позволяет сформировать несколько курсов с тем же самым названием, но отличающихся по разряду/уровню подготовки, который будт отражен в документе об образовании<small></div>
  </div>

  <div  v-if="is_rank_of_profession=='true' && rank_of_profession>0"  class="mb-3 row">	
  <label class="form-label col-sm-3"   >   </label>
    <div class="col-2">
        <button v-if="rank_of_profession==0" class="btn btn-outline-secondary"> без разряда </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=0"  v-else class="btn btn-outline-primary"> без разряда </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==2" class="btn btn-outline-secondary"> 2 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=2" v-else class="btn btn-outline-primary"> 2 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==3" class="btn btn-outline-secondary"> 3 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=3" v-else class="btn btn-outline-primary"> 3 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==4" class="btn btn-outline-secondary"> 4 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=4" v-else class="btn btn-outline-primary"> 4 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==5" class="btn btn-outline-secondary"> 5 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=5" v-else class="btn btn-outline-primary"> 5 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==6" class="btn btn-outline-secondary"> 6 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=6" v-else class="btn btn-outline-primary"> 6 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==7" class="btn btn-outline-secondary"> 7 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=7" v-else class="btn btn-outline-primary"> 7 </button> 
    </div>
    <div class="col-1">
        <button v-if="rank_of_profession==8" class="btn btn-outline-secondary"> 8 </button> 
        <button @click="rank_of_profession_save(); rank_of_profession=8" v-else class="btn btn-outline-primary"> 8 </button> 
    </div>
  </div>
  
  
  <!--<span  v-if="rank_of_profession>=0 || is_rank_of_profession!='true'">-->
  <div class="mb-3 row">	
      <label for="input_description" class="form-label col-sm-3">Описание курса</label>
      <textarea v-model="description"    class="form-control col"  id="input_description"   ></textarea>
  </div>

  <div class="mb-3 row">	
  <label for="input_qualification"  class="form-label col-sm-3"   >Квалификация </label>
    <input v-model="qualification"   class="form-control col" id="input_qualification"   type="text"  >
  </div>

  <div class="mb-3 row">	
  <label for="input_area"  class="form-label col-sm-3"   >Область деятельности </label>
    <select  v-model="opd"  class="form-select  form-select-sm  col" id="input_area"  @change="on_select_opd()" >
        <option v-for="(item, index) in opd_arr" :value="item.id">
            <span v-if="item.id>0">{{item.id}}</span> {{item.name.substr(0, 138)}}
        </option>
    </select>
  </div>
  <div class="mb-3 row">
  <p class="col-sm-3"   > </p>
    <small class="col-3" >Формулировка в документе об образовании</small>
    <input v-model="area"   class="form-control col" id="input_area_text"   type="text"  >
  </div>

  <div class="mb-3 row">	
  <label for="input_competence"  class="form-label col-sm-3"   >Навыки </label>
    <input v-model="competence"   class="form-control col" id="input_competence"   type="text"  >
  </div>


  <div  v-if="moodle_course_id==0"  class="mb-3 row">	
  <label for="input_okpdtr_area"  class="form-label col-sm-3"   >Код профессии </label>
  <div class="card col" style="padding-top: 5px;padding-bottom: 5px;" >
     <div class="row" style="padding-bottom: 5px;">
     <select v-model="okpdtr_area" class="form-select col" id="input_okpdtr_area" @change="on_select_okpdtr_area()"  ><option  value=""> - Отрасль - </option> 
      <option v-for="item_c in okpdtr_area_arr"   :value="item_c">{{item_c}}</option>
     </select>
     <div class="col-sm-2"></div>
     </div>
     <select v-model="okpdtr_code" class="form-select col" id="input_okpdtr"  ><option  value=""> - Профессия - </option> 
      <option v-for="item_c in okpdtr_arr"   :value="item_c.code">{{item_c.name}}</option>
     </select>
   </div>
  </div>

  <div v-if="type_of_education_id==1" class="mb-3 row">
  <label for="input_profession"  class="form-label col-sm-3"   ><small>Название профессии в документе об образовании</small> </label>
    <input v-model="profession"   class="form-control col" id="input_profession"   type="text"  >
    <div class="col-2">   </div>
  </div>


  <div class="mb-3 row">	
  <label for="input_qualification_work"  class="form-label col-sm-3"   > Квалификационная работа </label>
    <textarea v-model="qualification_work"   class="form-control col" id="input_qualification_work" ></textarea>
  </div>

<!--
  <div class="mb-3 row">	
  <label for="input_name_common"  class="form-label col-sm-3"   >Общее название блока </label>
    <input   v-model="name_common"   class="form-control col" id="input_name_common"   type="text"  >
  </div>
-->




  <div class="mb-3 row">	
    <label for="input_hours" class="form-label col-sm-3">Количество часов</label>
    <input   v-model="hours"  class="form-control col" id="input_hours"    type="text"  >
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Лекции:</small> </div><input   v-model="hours_l"  class="form-control col" id="input_hours_l"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Практические занятия:</small> </div><input   v-model="hours_p"  class="form-control col" id="input_hours_p"    type="text"  ></div></div>
  </div>
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3"></label>
    <div class="col"> </div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Производственная практика / Стажировка:</small> </div><input   v-model="hours_i"  class="form-control col" id="input_hours_i"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small><b>Итоговая аттестация</b>:</small> </div><input   v-model="hours_c"  class="form-control col" id="input_hours_i"    type="text"  ></div></div>
  </div>
  <br /><hr />
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3"> </label>
    <h6  class="col-4" style="text-align: left;"><i>Индивидуальный учебный план 1</i></h6>
    <div class="col-1"><small>Стоимость:</small></div>
    <div class="col-1"><small><i class="fa-solid fa-xmark"></i></small></div>
    <input   v-model="price_multiplier"  class="form-control col-sm" id="input_price_multiplier"    type="text"  >
    <div class="col-1"></div>
  </div>
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3">Количество часов</label>
    <input   v-model="hours_add"  class="form-control col" id="input_hours_add"    type="text"  >
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Лекции:</small> </div><input   v-model="hours_l_add"  class="form-control col" id="input_hours_l_add"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Практические занятия:</small> </div><input   v-model="hours_p_add"  class="form-control col" id="input_hours_p_add"    type="text"  ></div></div>
  </div>
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3"></label>
    <div class="col"> </div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Производственная практика / Стажировка:</small> </div><input   v-model="hours_i_add"  class="form-control col" id="input_hours_i_add"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small><b>Итоговая аттестация</b>:</small> </div><input   v-model="hours_c_add"  class="form-control col" id="input_hours_i_add"    type="text"  ></div></div>
  </div>
 <br />
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3"> </label>
    <h6  class="col-4" style="text-align: left;"><i>Индивидуальный учебный план 2</i></h6>
    <div class="col-1"><small>Стоимость:</small></div>
    <div class="col-1"><small><i class="fa-solid fa-xmark"></i></small></div>
    <input   v-model="price_multiplier"  class="form-control col-sm" id="input_price_multiplier"    type="text"  >
    <div class="col-1"></div>
  </div>
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3">Количество часов</label>
    <input   v-model="hours_add2"  class="form-control col" id="input_hours_add2"    type="text"  >
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Лекции:</small> </div><input   v-model="hours_l_add2"  class="form-control col" id="input_hours_l_add2"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Практические занятия:</small> </div><input   v-model="hours_p_add2"  class="form-control col" id="input_hours_p_add2"    type="text"  ></div></div>
  </div>
  <div class="mb-3 row">	
    <label  class="form-label col-sm-3"></label>
    <div class="col"> </div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small>Производственная практика / Стажировка:</small> </div><input   v-model="hours_i_add2"  class="form-control col" id="input_hours_i_add2"    type="text"  ></div></div>
    <div class="col-4"><div class="row"><div class="col-9" style="text-align: right;"> <small><b>Итоговая аттестация</b>:</small> </div><input   v-model="hours_c_add2"  class="form-control col" id="input_hours_i_add2"    type="text"  ></div></div>
  </div>
  <hr /> <br />

  <div  v-if="is_modules>0" class="mb-3 row">
      <label for="inlineCheckboxModules1" class="form-label col-sm-3"> Тип курса </label>
      <div class="mb-3 col">

        <div  v-if="is_modules>0" class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckboxModules" v-model="is_main_module">
        <label class="form-check-label" for="inlineCheckboxModules"> основной курс для модулей </label>&nbsp;&nbsp;
        </div>

      </div>
  </div>



  <div class="mb-3 row">
    <label for="input_forms" class="form-label col-sm-3">Основная форма обучения</label>
	<select  v-model="form_id"  class="form-control col"  id="input_form"  aria-label="Форма обучения">
	<option value="0"> -  Форма обучения  - </option>
        <option v-for="item_f in form_of_study" :value="item_f.form_id">
	    {{item_f.name}} 
	</option>
	</select>
    <div class="col-4">  </div>
  </div>
  <div class="mb-3 row">
    <label for="input_forms2" class="form-label col-sm-3">Дополнительно</label>
        <div class="col"><small>
        <span v-for="(item_f, index) in form_of_study">
             <nobr><input class="form-check-input" type="checkbox" :id="'CheckboxForm'+index" v-model="form2_id[index]">&nbsp;
             <label class="form-check-label" :for="'CheckboxForm'+index">{{item_f.name}} </label>&nbsp;&nbsp;&nbsp;&nbsp;</nobr><wbr><span v-if="index==1 || index==3"> <br /></span>
        </span>
      </div>



  <div class="mb-3 row">	
    <label for="input_price" class="form-label col-sm-3">Стоимость обучения</label>
    <input   v-model="price"  class="form-control col" id="input_price"    type="text"  >
    <div class="col-6">  </div>
  </div>


  <div class="mb-2 row">	
      <label for="input_delta2" class="form-label col-sm-3"><small>Периодичность прохождения обучения</small></label>
      <input v-model="delta2"   class="form-control col" id="input_delta2"   type="text"  >
      <label class="form-label col-sm-2"><small>лет,</small></label>
      <div class="col-sm-4"> <small><i></i></small> </div>
  </div>


  <!--<div class="mb-3 row">
      <label for="input_certificate_a1" class="form-label col-sm-3">Обяательные для заполнения поля <br />(дополнительно к стандартным)</label>
      <div class="mb-3 col">

        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
        <label class="form-check-label" for="inlineCheckbox1">СНИЛС </label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
        <label class="form-check-label" for="inlineCheckbox2">Дата рождения </label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
        <label class="form-check-label" for="inlineCheckbox3">Адрес регистрации </label>
        </div>

      </div>
  </div>-->
  <!--</span>-->



  <br /><hr />
  <div class="mb-3 row">
      <label for="input_certificate1" class="form-label col-sm-3">Шаблон документа об образовании</label>
      <div class="mb-3 col">
        <select  v-model="certificate1_template"  id="input_certificate" class="form-select col" aria-label="template"   @change="on_select_certificate1()" >
            <option value="0"> -  Шаблон документа об образовании  -</option>
            <option v-for="item2 in info7.list" :value="item2.template_id">
               {{item2.name.substring(0, 100)}} <span v-if="item2.output_format=='word'"><i class="fa-regular fa-file-word" style="color:#1e88e5;">(word)</i></span><span v-else><i class="fa-regular fa-file-code">(html)</span>
            </option>
         </select>
      </div>
  </div>


  <div class="mb-3 row">	
    <label for="input_certificate1_name" class="form-label col-sm-3">Наименование документа</label>
    <input   v-model="certificate1_name"  class="form-control col" id="input_certificate1_name"    type="text"  >
    <div class="col-2">  </div>
  </div>

  <br /><br />
  <div class="mb-3 row">
      <label for="input_certificate2" class="form-label col-sm-3">Шаблон документа об образовании</label>
      <div class="mb-3 col">
      	<select  v-model="certificate2_template"  id="input_certificate2" class="form-select col" aria-label="template"  @change="on_select_certificate2()">
	        <option value="0"> -  Шаблон документа об образовании - </option>
            <option v-for="item2 in info8.list" :value="item2.template_id">
	            {{item2.name.substring(0, 100)}}<span v-if="item2.output_format=='word'"><i class="fa-regular fa-file-word" style="color:#1e88e5;">(word)</i></span><span v-else><i class="fa-regular fa-file-code">(html)</span>
	        </option>
	    </select>
      </div>
  </div>

  <div class="mb-3 row">
    <label for="input_certificate2_name" class="form-label col-sm-3">Наименование документа</label>
    <input   v-model="certificate2_name"  class="form-control col" id="input_certificate2_name"    type="text"  >
    <div class="col-2">  </div>
  </div>


  <br /><hr />
  <div class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-3">Шаблон протокола обучения</label>
      <div class="mb-3 col">
      	<select  v-model="protocol_template"  id="input_protocol" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон протокола обучения  - </option>
            <option v-for="item2 in info10.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} <span v-if="item2.output_format=='word'"><i class="fa-regular fa-file-word" style="color:#1e88e5;">(word)</i></span><span v-else><i class="fa-regular fa-file-code">(html)</span> 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>


  <div class="mb-3 row">
      <label for="input_commission" class="form-label col-sm-3">Состав комиссии</label>
      <div class="mb-3 col">
      	<select  v-model="teachers_commission"  id="input_commission" class="form-select col" aria-label="template">
	        <option value="0"> -  Состав комиссии - </option>
                <option v-for="item2 in info6.list" :value="item2.commission_id">
	            {{item2.name.substring(0, 100)}}
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>


  <div v-if="type_of_program_id==0"  class="mb-3 row"  style="font-size: small;" >
  <hr />
  <label class="form-label col-sm-1"  for="input_eisot"  > ID ЕИСОТ </label>
    <select  v-model="eisot_id"  class="form-control mb-3 col" id="input_eisot" style="font-size: small;"> 
        <option  value="0"></option> 
        <option v-for="(item, index) in eisot_id_arr" :value="item.id">
	     {{item.id}} - {{item.name.substr(0, 138)}}
        </option>
    </select>
  </div>


  <div class="mb-3 row">
  <label for="input_lms_course"  class="form-label col-sm-3"  style="font-size: small;"  >Идентификационный номер в LMS <br />(Заполняется при необходимости) </label>
    <select  v-model="lms_course_id"  class="form-select mb-3 col" id="input_lms_course" style="font-size: small;"> 
        <option  value="0"></option> 
        <option v-for="(item, index) in courses_lms" :value="item.id">
           {{item.shortname.substr(0, 110)}}
        </option>
    </select>
  </div>





  <br / >
  <div class="mb-2">	
    <div align="right">
    <button v-if="course_id>0"   class="btn btn-primary"    @click="objectSave()"> Сохранить </button>
    <button v-if="course_id==0"  class="btn btn-primary"    @click="objectCreate()"> Добавить курс </button>
      &nbsp<router-link :to="{ name: 'courses_list', params: { counterpartyid: this.counterparty_id  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>
</span>

<span v-if="counterparty_id>0 && course_id>0" >
  <br />
  <div class="mb-3 row">
    <label class="form-label col-sm-3">Курс</label>
    <p class="col" ><b> {{name}} </b><p>
  </div>

  <div class="mb-3 row">
    <label class="form-label col-sm-3">Стоимость обучения</label>
    <p class="col" ><b> {{price}} </b><p>
  </div>

  <div class="mb-3 row">	
    <label for="input_price2" class="form-label col-sm-3">Персональная cтоимость обучения</label>
    <input   v-model="price2"  class="form-control col" id="input_price2"    type="text"  >
    <div class="col-4">  </div>
  </div>



  <br />
  <div   class="mb-2">	
    <div align="right">
    <button v-if="course_id>0"   class="btn btn-primary"    @click="objectSave2()"> Сохранить </button>
      &nbsp<router-link :to="{ name: 'courses_list', params: { counterpartyid: this.counterparty_id  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>
</span>


</div>
	</div>`

};








