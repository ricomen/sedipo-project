var CourseCategoryEdit = {
  data: function () {
    return {
      role: '',
      info: [],
      info2: [],
      info3: [],
      info4: [],
      info7: [],
      message: '',
      object_id: 0,
      name: '',
      type_of_education_a: ['', 'Профессиональное обучение', 'Дополнительное образование', 'Охрана труда' ],
      subtype_of_education_a: [[], ['', 'Основная программа профессионального обучения'], ['', 'Дополнительное профессиональное образование', 'Дополнительное образование детей и взрослых' ], []],
      is_modules: 'false',
      consulting: 'false', 
      is_rank_of_profession: 'false',
      type_of_education_id: 0,
      type_of_education: '',
      subtype_of_education: '',
      type_of_program_a: [[],[[], ['', 'Повышение квалификации',  'Профессиональная переподготовка', 'Профессиональная подготовка' ]], [[], ['', 'Повышение квалификации',  'Профессиональная переподготовка' ], ['-']], []],
      type_of_program: '',
      name_of_type: '',
      name_of_type_a: [[],[[], ['', 'Основная программа повышение квалификации',  'Основная программа профессиональная переподготовки', 'Основная программа профессиональной подготовки' ]], [[], ['', 'Дополнительная профессиональная программа повышения квалификации',  'Дополнительная профессиональная программа  профессиональной переподготовки' ], ['-']], []],
/*      delta: '',

      certificate_prefix: '',
      directive: '',
      protocol_h: 'заседания аттестационной комиссии', 
      protocol_p1: 'Аттестационная комиссия в составе:',
      protocol_p2: 'Провела проверку знаний руководителей и специалистов по дополнительной профессиональной программе:',
      protocol_p3: '',
      is_hours_p: 'true',
      is_position: 'true',
      is_organization: 'true',
      is_certificate: 'true',
      certificate_header: '№ выданного удостоверения',
      add_header: 'Результаты промежуточной аттестации ; Итоговая аттестация',
      add_default: 'Зачет ; Зачет',
*/
      need_snils: 'false',
      need_birth: 'false',
      need_address: 'false',
      need_diplom: 'false',
      need_photo: 'false',
      contract_id: 0,
      contract2_id: 0,
      lms_category_id: 0,
      lms_categories_list: [],

      helpers: {},
      normative: {},
      is_DEVELOPMENT: IS_DEVELOPMENT,

     }
   },

   mounted() {
//    updated() {
    this.object_id = Number(this.$route.params.categoryid)
    if(this.object_id > 0 ){
    axios
      .post(JsonApiURL+'api/course_category_json.php', {object: {objectId: this.object_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.name = this.info.result.name
            this.is_rank_of_profession = this.info.result.is_rank_of_profession
            this.is_modules = this.info.result.modules
            this.type_of_education_id  = this.info.result.type_of_education_id
            this.subtype_of_education_id = this.info.result.subtype_of_education_id
            this.type_of_program_id = this.info.result.type_of_program_id
            this.type_of_education  = this.info.result.type_of_education 
            this.subtype_of_education = this.info.result.subtype_of_education
            this.type_of_program = this.info.result.type_of_program
            this.consulting = this.info.result.consulting
            this.lms_category_id = this.info.result.moodle_category_id

/*
            this.protocol_h = this.info.result.protocol_h
            this.protocol_p1 = this.info.result.protocol_p1
            this.protocol_p2 = this.info.result.protocol_p2
            this.protocol_p3 = this.info.result.protocol_p3
            this.is_hours_p = this.info.result.is_hours_p
            this.directive = this.info.result.directive
            this.is_position = this.info.result.is_position
            this.is_organization = this.info.result.is_organization
            this.is_finalexamination = this.info.result.is_finalexamination
            this.is_certificate = this.info.result.is_certificate
            this.certificate_header = this.info.result.certificate_header
            this.add_header = this.info.result.add_header
            this.add_default = this.info.result.add_default
*/

            this.need_snils = this.info.result.need_snils
            this.need_birth = this.info.result.need_birth
            this.need_address = this.info.result.need_address
            this.need_diplom = this.info.result.need_diplom
            this.need_photo = this.info.result.need_photo
            this.contract_id = this.info.result.contract_id
            this.contract2_id = this.info.result.contract2_id
            this.role = this.info.role
            

            if( this.consulting >= 1)
                    this.consulting = 'true'

            if( this.is_modules >= 1)
                    this.is_modules = 'true'
            if( this.need_snils == 1)
                    this.need_snils = 'true'
            if( this.need_birth == 1)
                    this.need_birth = 'true'
            if( this.need_address == 1)
                    this.need_address = 'true'
            if( this.need_diplom == 1)
                    this.need_diplom = 'true'
            if( this.need_photo == 1)
                    this.need_photo = 'true'

            if( this.type_of_education_id==0 && this.subtype_of_education!='')
                  this.type_of_education_id=subtype_of_education_a.findIndex(i => i == this.subtype_of_education)

/*            if( this.is_rank_of_profession == 1)
                    this.is_rank_of_profession = 'true'
            if( this.is_hours_p == 1)
                    this.is_hours_p = 'true'
            if( this.is_position == 1)
                    this.is_position = 'true'
            if( this.is_organization == 1)
                    this.is_organization = 'true'
            if( this.is_finalexamination == 1)
                    this.is_finalexamination = 'true'
            if( this.is_certificate == 1)
                    this.is_certificate = 'true'
*/

       })
      .catch(error => {
              console.log(error.response)
       })
    }
    
    axios
      .post(JsonApiURL+'api/contract_json.php', {list: {where: "type=0 AND contract_id<>105", sessionId: session_t.sessionId } })
      .then(response => { 
            this.info7 = response.data
       })
      .catch(error => {
              console.log(error.response)
       }),

     axios
      .post(JsonApiURL+'api/helpers_json.php', {table: 'a_course_category_edit'})
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

                    // добавляем подсказку
                    if(getFor in this.helpers) {
                        const help_block = ` <a title='${this.helpers[getFor]}'><span class="text-primary"><i class="fa-regular fa-circle-question"></i></span></a>`;
                        label.innerHTML = label.innerHTML + (help_block);
                    }
                    // добавляем нормативку
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
      }),


      axios
      .post(JsonApiURL+'lms-api/lms_info_json.php', {categories_list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.lms_categories_list = response.data.list
       })
      .catch(error => {
              console.log(error.response)
        })


  },



  methods: {
        objectSave() {
          var modules = 0    
          if(this.is_modules=='true')
              modules = this.object_id
          if( this.consulting == true)
                 this.consulting = 1
          else
                 this.consulting = 0


	      axios
            .post(JsonApiURL+'api/course_category_json.php', {update: {objectId: this.object_id,  name: this.name, is_rank_of_profession: this.is_rank_of_profession, consulting: this.consulting,  modules: modules, type_of_education: this.type_of_education, subtype_of_education: this.subtype_of_education, type_of_program: this.type_of_program,  document_txt: this.document_txt,     need_snils: this.need_snils, need_birth: this.need_birth, need_address: this.need_address, need_diplom: this.need_diplom, need_photo: this.need_photo, contract_id: this.contract_id, contract2_id: this.contract2_id, type_of_education_id: this.type_of_education_id, subtype_of_education_id: this.subtype_of_education_id, type_of_program_id: this.type_of_program_id, moodle_category_id: this.lms_category_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

                   this.$router.push({ path: '/course_category_list'}) 
            })
            .catch(error => {
              console.log(error.response)
            })

        },


        objectCreate() {
          var modules = 0    
          if(this.is_modules=='true')
              modules = this.object_id
          if( this.consulting == true)
                 this.consulting = 1
          else
                 this.consulting = 0

	      axios
            .post(JsonApiURL+'api/course_category_json.php', {insert: {  name: this.name, is_rank_of_profession: this.is_rank_of_profession, modules: modules,  type_of_education: this.type_of_education, subtype_of_education: this.subtype_of_education, type_of_program: this.type_of_program,  document_txt: this.document_txt,    need_snils: this.need_snils, need_birth: this.need_birth, need_address: this.need_address, need_diplom: this.need_diplom, need_photo: this.need_photo,  contract_id: this.contract_id, contract2_id: this.contract2_id, type_of_education_id: this.type_of_education_id, subtype_of_education_id: this.subtype_of_education_id, type_of_program_id: this.type_of_program_id, moodle_category_id: this.lms_category_id, sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

                this.$router.push({ path: '/course_category_list'}) 
            })
            .catch(error => {
              console.log(error.response)
            })

        }

  },



	template: `<div><navigation></navigation><h3>Редактирование категории</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">

  <div class="mb-2 row">	
  <label for="input_name"  class="form-label col-sm-2"   >Категория</label>
    <input v-model="name"   class="form-control col" id="input_fullname"   type="text" readonly  >
  </div>

  <div class="mb-2 row">
      <label for="inlineCheckboxModules1" class="form-label col-sm-2"> Тип контента </label>
      <div class="mb-2 col-2">
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckboxModules" v-model="is_modules">
        <label class="form-check-label" for="inlineCheckboxModules"> модули </label>&nbsp;&nbsp;
        </div>
      </div>
      <div class="mb-2 col-4">
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckboxConsulting" v-model="consulting">
        <label class="form-check-label" for="inlineCheckboxConsulting"> консультационные услуги </label>&nbsp;&nbsp;
        </div>
      </div>
      <div class="mb-2 col">
      </div>
  </div>
  <hr />

<span   v-show="is_DEVELOPMENT==1">

  <div class="mb-2 row">
  <label for="input_type_of_education"  class="form-label col-sm-4"   >Вид образовательной услуги </label>
  <select v-model="type_of_education" class="form-select col" id="input_type_of_education" @change="this.type_of_education_id=type_of_education_a.findIndex(i => i == this.type_of_education)"  readonly  >
      <option v-for="(item, index) in type_of_education_a" :value="item">{{item}}</option>
  </select>
  </div>

  <div class="mb-2 row">
  <label for="input_subtype_of_education"  class="form-label col-sm-4"   >Подвид образовательной услуги </label>
  <select v-model="subtype_of_education" class="form-select col" id="input_subtype_of_education" @change="this.subtype_of_education_id=subtype_of_education_a[this.type_of_education_id].findIndex(i => i == this.subtype_of_education)" >
      <option v-for="(item, index) in subtype_of_education_a[this.type_of_education_id]" :value="item">{{item}}</option>
  </select>
  </div>


  <div class="mb-2 row">
  <label for="input_type_of_program"  class="form-label col-sm-4"   >Вид образовательной программы  </label>
  <select v-model="type_of_program" class="form-select col" id="input_type_of_program" @change="this.type_of_program_id=type_of_program_a[this.type_of_education_id][this.subtype_of_education_id].findIndex(i => i == this.type_of_program); this.name_of_type=name_of_type_a[this.type_of_education_id][this.subtype_of_education_id][this.type_of_program_id] " >
      <option v-for="(item, index) in type_of_program_a[this.type_of_education_id][this.subtype_of_education_id]" :value="item">{{item}}</option>
  </select>
  </div>
</span>

  <div class="mb-2 row">
      <label for="input_name_type_of_program" class="form-label col-sm-4">Наименование вида программы в документах</label>
      <input v-model="name_of_type"   class="form-control col" id="input_name_type_of_program"   type="text"  >
  </div>


  <!--<div class="mb-2 row">	
      <label for="input_delta" class="form-label col-sm-4">Периодичность прохождения обучения</label>
      <input v-model="delta"   class="form-control col" id="input_delta"   type="text"  >
      <label class="form-label col-sm-4"> лет </label>
      <div class="col">  </div>
  </div>-->

  <div class="mb-2 row">	
    <label for="input_rank" class="form-label col-sm-4"> Дополнительная информация </label>
    <div class="col form-check form-check-inline">
      <input  v-model="is_rank_of_profession"  class="form-check-input" type="checkbox" id="Checkbox_rank" >
      <label class="form-check-label" for="Checkbox_rank">Учитывать разряд  / уровень профессии </label>
    </div>
  </div>

  <br />
  <div class="mb-3 row">
      <label for="input_contract" class="form-label col-sm-3"> Договор </label>
      <div class="mb-3 col">
      	<select  v-model="contract_id"  id="input_contract" class="form-select col" aria-label="template"  >
	        <option value="0"> -  Договор - </option>
            <option v-for="item7 in info7.list" :value="item7.contract_id">
	            {{item7.name.substring(0, 100)}}
	        </option>
	    </select>
      </div>
  </div>
  <div class="mb-3 row">
      <label for="input_contract2" class="form-label col-sm-3"> Договор для учебных центров  </label>
      <div class="mb-3 col">
      	<select  v-model="contract2_id"  id="input_contract2" class="form-select col" aria-label="template"  >
	        <option value="0"> -  Договор - </option>
            <option v-for="item7 in info7.list" :value="item7.contract_id">
	            {{item7.name.substring(0, 100)}}
	        </option>
	    </select>
      </div>
  </div>


  <hr />
  <div class="mb-3 row">
      <label for="input_certificate_a1" class="form-label col-sm-3">Обязательные для заполнения поля профиля обучающегося</label>
      <div class="mb-3 col">

        <div class="form-check form-check-inline">
        <input  v-model="need_snils"  class="form-check-input" type="checkbox" id="Checkbox_need_snils" >
        <label class="form-check-label" for="Checkbox_need_snils">СНИЛС </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input  v-model="need_birth" class="form-check-input" type="checkbox" id="Checkbox_need_birth" >
        <label class="form-check-label" for="Checkbox_need_birth">Дата рождения </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input  v-model="need_address" class="form-check-input" type="checkbox" id="Checkbox_need_address" >
        <label class="form-check-label" for="Checkbox_need_address">Адрес регистрации </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input  v-model="need_diplom" class="form-check-input" type="checkbox" id="Checkbox_need_diplom" >
        <label class="form-check-label" for="Checkbox_need_diplom">Документ об образовании </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input  v-model="need_photo" class="form-check-input" type="checkbox" id="Checkbox_need_photo" >
        <label class="form-check-label" for="Checkbox_need_photo">Фото </label>&nbsp;&nbsp;
        </div>

      </div>
  </div>

  <hr />
  <div class="mb-3 row">
  <label for="input_lms_category"  class="form-label col-sm-3"  style="font-size: small;"  >Идентификационный номер в LMS <br />(Заполняется при необходимости) </label>
    <select  v-model="lms_category_id"  class="form-select mb-3 col" id="input_lms_category" style="font-size: small;"> 
        <option  value="0"></option> 
        <option v-for="(item, index) in lms_categories_list" :value="item.id">
           {{item.name.substr(0, 110)}}
        </option>
    </select>
  </div>


<!--
  <hr /><br />
  <div class="mb-2 row">	
      <label for="input_protocol_h" class="form-label col-sm-3">Текст в названии протокола</label>
      <input v-model="protocol_h"   class="form-control col" id="input_protocol_h" type="text" />
  </div>

  <div class="mb-2 row">
      <label for="input_directive" class="form-label col-sm-3">Абзац протокола / Номер приказа</label>
      <input v-model="directive"   class="form-control col" id="input_directive" type="text" />
  </div>

  <div class="mb-2 row">	
      <label for="input_protocol_p1" class="form-label col-sm-3">Абзац протокола</label>
      <textarea v-model="protocol_p1"   class="form-control col" id="input_protocol_p1" ></textarea>
  </div>

  <div class="mb-2 row">
      <label for="input_protocil_p_a" class="form-label col-sm-3"> Отображаемая информация </label>
      <div class="mb-3 col">

        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckboxP1" v-model="is_hours_p">
        <label class="form-check-label" for="inlineCheckboxP1"> Количество часов </label>&nbsp;&nbsp;
        </div>

      </div>
  </div>

  <div class="mb-2 row">	
      <label for="input_protocol_p2" class="form-label col-sm-3">Завершение абзаца протокола</label>
      <textarea v-model="protocol_p2"   class="form-control col" id="input_protocol_p2"  ></textarea>
  </div>

  <br />
  <div class="mb-2 row">
      <label for="input_certific" class="form-label col-sm-3"> Поля списка слушателей </label>
      <div class="mb-3 col">

        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" v-model="is_position">
        <label class="form-check-label" for="inlineCheckbox1"> Должность </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" v-model="is_organization">
        <label class="form-check-label" for="inlineCheckbox2"> Организация </label>&nbsp;&nbsp;
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox3" v-model="is_finalexamination">
        <label class="form-check-label" for="inlineCheckbox3"> Итоговая аттестация </label>&nbsp;&nbsp;
        </div>

      </div>
  </div>

  <div class="mb-2 row">	
    <label for="input_certificate_header" class="form-label col-sm-3">Наименование и № выданного документа  об образовании </label>
    <div class="input-group col-sm">
      <span class="input-group-text" id="phone-addon"><input class="form-check-input" type="checkbox" id="inlineCheckbox4" v-model="is_certificate"></span>
      <input v-model="certificate_header"   class="form-control col" id="input_certificate_header"   type="text"  >
    </div>  
  </div>

  <div class="mb-2 row">	
      <label  class="form-label col-sm-3"> </label>
      <p class=" col"><i>* поля протокола редактируются также в настройках учебной группы </i></p>
  </div>


  <div class="mb-2 row">	
      <label for="input_add_header" class="form-label col-sm-3">Дополнительные поля протокола </label>
      <input v-model="add_header"   class="form-control col" id="input_add_header"   type="text"  >
  </div>


  <div class="mb-2 row">	
      <label for="input_add_default" class="form-label col-sm-3">Значения по умолчанию для полей протокола </label>
      <input v-model="add_default"   class="form-control col" id="input_add_default"   type="text"  >
  </div>


  <br />
  <div class="mb-2 row">	
      <label for="input_protocol_p3" class="form-label col-sm-3">Дополнительный абзац протокола</label>
      <input v-model="protocol_p3"   class="form-control col" id="input_protocol_p3"   type="text"  >
  </div>
-->

  <br />
  <div    class="mb-2">	
    <div align="right">
    <button v-if="object_id>0"   class="btn btn-primary"    @click="objectSave()"> Сохранить </button>
    <button v-if="object_id==0"  class="btn btn-primary"    @click="objectCreate()"> Добавить категорию </button>
      &nbsp<router-link to="/course_category_list" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>




</div>
	</div>`

};




