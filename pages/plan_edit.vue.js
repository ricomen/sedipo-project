var PlanEdit = { 
  data: function () {
    return {
      role: '',
      message: '',
      info: [],
      info2: [],


      course_id: 0,
      a_category_id: 0,
      performer_id: 0,
      name: '',
      shortname: '',
      rank_of_profession: -1,
      description: '',
      competence: '',
      moodle_course_id: 0,
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
      is_rank_of_profession: 'false',
      category: 0,
      counterparty_id: -1,
      form_of_study: [ {form_id: 1, name: 'Очная'}, {form_id: 2, name:  'Очная с применением дистанционных технологий'} , {form_id: 3, name: 'Заочная'}, {form_id: 4, name:  'Заочная с применением дистанционных технологий'} , {form_id: 5, name: 'Очно-заочная'}, {form_id: 6, name:  'Очно-заочная с применением дистанционных технологий'} ],
      form_id: 0,
      form2_id: [],
      delta2: '',
      qualification_work: '',
      name_common: '',
      certificate1_template: 0,
      certificate1_name: '',
      certificate2_template: 0,
      certificate2_name: '',
      protocol_template: 0,
      directive_num: '',
      teachers_commission: 0,





      list: [],
      pos: 0,
      course_id: 0,
      name: '',
      hours_o: '',
      hours_z: '',
      hours_s: '',
      hours_a: '',
      hours_k: '',
      s_hours_o: '',
      s_hours_z: '',
      s_hours_l: '',
      s_hours_p: '',
      s_hours_s: '',
      s_hours_a: '',
      //s_hours_k: '',
      //s_hours_i: '',
      form: '',
      form1: '',
      form2: '',
      form3: '',
      att_i: '',
      norm_hours: 8,
      p0: '',
      p1: '',
      p2: '',
      p3: '',
      p4: '',
      p5: '',
      p6: '',
      s_fl: 'Самоподготовка',
     }
   },



  updated() {
//        window.scrollTo(0, this.pos );
  },



   mounted() {
    this.course_id = this.$route.params.id
    //this.pos = this.$route.params.pos
    //if(Number(this.pos)==0){
	//this.pos = 250
    //}
    //window.scrollTo(0, this.pos );


    if(this.course_id > 0 ){
    axios
      .post(JsonApiURL+'api/courses_json.php', {object: {objectId: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            var main_module=0
            this.info = response.data
            this.name = this.info.result.name
            this.shortname = this.info.result.shortname
            this.rank_of_profession = this.info.result.rank_of_profession
            this.category = this.info.result.category_id
            this.performer_id = this.info.result.performer_id
            this.moodle_course_id = this.info.result.moodle_course_id
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
            this.directive = this.info.result.directive
            this.teachers_commission =  this.info.result.commission_id

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









    axios
      .post(JsonApiURL+'api/plan_json.php', {object: {ObjectId: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info2 = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    //this.hours_s = this.hours - this.hours_l  - this.hours_p  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })
  },


  methods: {
        rprgSave(cls) {

	    axios
            .post(JsonApiURL+'api/plan_json.php', {rprg_save: {Id: this.course_id, name: this.name, form: this.form, form1: this.form1, form2: this.form2, form3: this.form3, att_i: this.att_i,  hours: this.hours, p0: this.p0, p1: this.p1, p2: this.p2, p3: this.p3, p4: this.p4, p5: this.p5,  p6: this.p6,  s_fl: this.s_fl, hours_o: this.hours_o, hours_z: this.hours_z, hours_l: this.hours_l, hours_p: this.hours_p, hours_s: this.hours_s, hours_a: this.hours_a, hours_k: this.hours_k, hours_i: this.hours_i, norm_hours: this.norm_hours,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

                if(response.data.rprg.Id>0) {
		    if(cls==1){
		      this.$router.push({ name: 'list' })
		    }
		    if(cls==2){
		      this.$router.push({ name: 'p_item', params: {id: this.course_id,  parent: 0,  p_id: 0  }})
		    }
                }
                else {
                    this.message = response.data.error
                }
            })
            .catch(error => {
              console.log(error.response)
            })

        },

        hours1Calc(){
	    this.hours_p = this.hours - this.hours_l  
       },

        itemDel(p_id, name) {
	  var fl = confirm('Удалить: ' + name + '?');
	  if(fl) {
	    axios
            .post(JsonApiURL+'api/plan_json.php', {p_item_del: {p_id: p_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

            })
            .catch(error => {
              console.log(error.response)
            })
	  }


    axios
      .post(JsonApiURL+'api/plan_json.php', {object: {ObjectId: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    //this.hours_s = this.hours - this.hours_l  - this.hours_p  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })
     },


        itemUp(p_id){
	    axios
            .post(JsonApiURL+'api/plan_json.php', {p_item_up: {p_id: p_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

    axios
      .post(JsonApiURL+'api/plan_json.php', {rprg_detalies: {Id: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    //this.hours_s = this.hours - this.hours_l  - this.hours_p  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })




            })
            .catch(error => {
              console.log(error.response)
            })

/*    axios
      .post(JsonApiURL+'api/plan_json.php', {rprg_detalies: {Id: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })
*/

     },

	itemDown(p_id){
	    axios
            .post(JsonApiURL+'api/plan_json.php', {p_item_down: {p_id: p_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

    axios
      .post(JsonApiURL+'api/plan_json.php', {rprg_detalies: {Id: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    //this.hours_s = this.hours - this.hours_l  - this.hours_p  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })




            })
            .catch(error => {
              console.log(error.response)
            })
/*
     axios
      .post(JsonApiURL+'api/plan_json.php', {rprg_detalies: {Id: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })
*/


     },




        calc() {
	  var fl = confirm('Запланированное количество часов будет распределено по всем темам. Существующие значения будут перезаписаны. Продолжить?');
	  if(fl) {
	    axios
            .post(JsonApiURL+'api/plan_json.php', {p_calc: {ObjectId: this.course_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

    axios
      .post(JsonApiURL+'api/plan_json.php', {object: {ObjectId: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.list = response.data.item
	    this.name = response.data.rprg.name
	    this.hours = response.data.rprg.hours
	    this.hours_o = response.data.rprg.hours_o
	    this.hours_z = response.data.rprg.hours_z  
	    this.hours_l = response.data.rprg.hours_l
	    this.hours_p = response.data.rprg.hours_p  
	    this.hours_s = response.data.rprg.hours_s  
	    this.hours_a = response.data.rprg.hours_a  
	    this.hours_k = response.data.rprg.hours_k  
	    this.hours_i = response.data.rprg.hours_i  
	    //this.hours_s = this.hours - this.hours_l  - this.hours_p  
	    this.s_hours_o = response.data.rprg.s_hours_o
	    this.s_hours_z = response.data.rprg.s_hours_z
	    this.s_hours_l = response.data.rprg.s_hours_l
	    this.s_hours_p = response.data.rprg.s_hours_p
	    this.s_hours_s = response.data.rprg.s_hours_s
	    this.s_hours_a = response.data.rprg.s_hours_a
	    this.form = response.data.rprg.form
	    this.form1 = response.data.rprg.form1
	    this.form2 = response.data.rprg.form2
	    this.form3 = response.data.rprg.form3
	    this.att_i = response.data.rprg.att_i
	    this.p0 = response.data.rprg.p0
	    this.p1 = response.data.rprg.p1
	    this.p2 = response.data.rprg.p2
	    this.p3 = response.data.rprg.p3
	    this.p4 = response.data.rprg.p4
	    this.p5 = response.data.rprg.p5
	    this.p6 = response.data.rprg.p6
	    this.s_fl = response.data.rprg.s_fl
	    this.norm_hours = response.data.rprg.norm_hours
       })
      .catch(error => {
              console.log(error.response)
            })


            })
            .catch(error => {
              console.log(error.response)
            })
	  }
    }

  },




	template: `<div><navigation></navigation> <h3>Сформировать рабочую программу</h3>
<div align="left">

<p><span class="os">Название программы:</span> <input type="text" class="form-control" placeholder="Название программы" v-model="name" ></p>
<p><span class="os">Нормативный срок освоения программы [часов]:</span> <input type="text" class="form-control" placeholder="Нормативный срок освоения программы" v-model="hours"   @input="hoursCalc()" ></p>

<p><span class="os">Форма обучения:</span>
<!--  <select  v-model="form" class="form-select" aria-label="Форма обучения"><option value=''></option><option value='Очная, очно-заочная'>Очная, очно-заочная</option><option value='Заочная'>Заочная</option><option value='Очно-заочная'>Очно-заочная</option><option value='Очно-заочная, заочная'>Очно-заочная, заочная</option></select></p>-->

<div class="form-check">
  <input class="form-check-input" type="checkbox"  id="Check1" v-model="form1">
  <label class="form-check-label" for="Check1"> Очная </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox"  id="Check2" v-model="form2">
  <label class="form-check-label" for="Check1"> Заочная </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox"  id="Check3" v-model="form3">
  <label class="form-check-label" for="Check1"> Очно-заочная </label>
</div>
</p>

<p><span class="os">Аннотация:</span>  <textarea class="form-control" v-model="p0" rows="7" > </textarea></p>
<p>1 ПОЯСНИТЕЛЬНАЯ ЗАПИСКА</p>
<p><span class="os"> Цель реализации программы:</span>  <textarea class="form-control" v-model="p1" rows="7"  > </textarea></p>
<p><span class="os"> Планируемые результаты обучения:</span>  <textarea class="form-control" v-model="p2" rows="7"  > </textarea></p>
<br />
<p class="os">2 КАЛЕНДАРНЫЙ УЧЕБНЫЙ ГРАФИК</p>
<p>(<i>формируется автоматически</i>)</p>
<br />

<p class="os">3. Учебный план дополнительной профессиональной образовательной программы</p>
<br>
<h4 class="os">Очная форма:</h4>
<div class="row">
<div class="col">
<p>Лекций (теория): <input type="text" class="form-control"  v-model="hours_l"   @input="hoursCalc()"></p>
</div>
<div class="col">
<p>Пр. занятий: <input type="text" class="form-control"  v-model="hours_p"  @input="hoursCalc()" ></p>
</div>
<div class="col">
<p><font siz=="+1"><br />({{Number(hours_l)+Number(hours_p)}})</font></p>
</div>
</div>


<h4 class="os">Заочная форма:</h4>
<div class="row">
<div class="col">
<p>Лекций (Теории): <input type="text" class="form-control"  v-model="hours_z"  @input="hoursCalc()" ></p>
</div>
<div class="col">
<p>Пр. занятий: <input type="text" class="form-control"  v-model="hours_o"   @input="hoursCalc()"></p>
</div>
<div class="col">
<p><font siz=="+1"><br />({{Number(hours_o)+Number(hours_z)}})</font></p>
</div>
</div>

<div class="row">
<div class="col-3">
<p> <select  v-model="s_fl" ><option value="Стажировка">Стажировка</option><option value="Самоподготовка">Самоподготовка</option></select> :
<input type="text" class="form-control" placeholder="часов" v-model="hours_s" ></p>
</div>
</div>



<!--<div class="col-3">
<p>Контроль знаний: <input type="text" class="form-control" placeholder="часов" v-model="hours_a" ></p>
</div>-->


<div class="row">
<div class="col-3">
<p>Консультации: <input type="text" class="form-control" placeholder="часов" v-model="hours_k" >
</div>
</div>

<div class="row">
<div class="col-5">
Итоговая аттестация: форма 
<select class="form-select"  v-model="att_i" >
	<option value=''></option>
	<option value='Итоговая  аттестация: экзамен'>Итоговая  аттестация: экзамен</option>
	<option value='Итоговая  аттестация: квалификационный экзамен'>Итоговая  аттестация: квалификационный экзамен</option>
	<option value='Итоговая  аттестация: итоговая аттестационная работа'>Итоговая  аттестация: итоговая аттестационная работа</option>
	<option value='Квалификационный экзамен: тестирование'>Квалификационный экзамен: тестирование</option>
	<option value='Квалификационный экзамен: итоговая квалификационная работа'>Квалификационный экзамен:  итоговая квалификационная работа</option>
	<option value='Проверка знаний: экзамен'>Проверка знаний: экзамен</option>
	<option value='Проверка знаний: тестирование'>Проверка знаний: тестирование</option>
</select>
</div>
<div class="col-3">
<p>часов  <input type="text" class="form-control" placeholder="часов" v-model="hours_i" ></p>
</div>
</div>
<div class="row">
<div class="col-3">
<p>Норма часов в день: <input type="text" class="form-control" placeholder="часов" v-model="norm_hours" >
</div>
</div>

<p><button class="green" @click="rprgSave(0)"><i class="fa-solid fa-floppy-disk"></i> Сохранить и продолжить редактирование </button></p>
<br />


<p class="os">4. Учебно-тематический план дополнительной профессиональной образовательной программы</p>
<table width="100%" border="1" class="table">
<tr><th>№ п/п</th><th>Наименование учебных модулей</th><th colspan="6" align="center">Количество часов</th><th colspan="2">Контроль знаний</th><th></th></tr>
<tr><th></th><th></th><th> </th><th colspan="2">Очная форма</th><th colspan="2">Заочная форма</th><th> </th><th> </th><th> </th><th wodth="1%"></th></tr>
<tr><th></th><th></th><th>Всего</th><th>Лекций</th><th>Пр. занятий</th><th>Лекций</span></th><th>Пр. занятий</span></th><th>Стажировки / <br />Самоподготовка</th><th>форма <br />контроля</th><th> часов</th><th wodth="1%"></th></tr>

<tr v-for="item  in list"><td><span v-if="item.num>0" class="num">{{item.num}}</span><span v-if="item.parent==0"><small><router-link :to="{ name: 'p_item', params: {parent: item.p_Id, id: this.course_id,    p_id: 0  }}"><button  v-if="item.num>0"  class="add"> + </button></router-link><small></span></td><td valign="center"><router-link  :to="{ name: 'p_item', params: {parent: item.parent, id: this.course_id,    p_id: item.p_Id  }}">{{item.name}}</router-link></td><td><b v-if="item.parent==0"><span v-if="item.num==0">{{ Number(item.hours_o) +  Number(item.hours_z) +  Number(item.hours_l) +  Number(item.hours_p) +  Number(item.hours_s) +  Number(item.hours_a) }}</span></b><span v-else>{{ Number(item.hours_o) +  Number(item.hours_z) +  Number(item.hours_l) +  Number(item.hours_p) +  Number(item.hours_s) +  Number(item.hours_a) }}</span></td><td><b v-if="item.parent==0">{{item.hours_l}}</b><span v-else>{{item.hours_l}}</span> </td><td><b v-if="item.parent==0">{{item.hours_p}}</b><span v-else>{{item.hours_p}}</span></td><td><b v-if="item.parent==0">{{item.hours_z}}</b><span v-else>{{item.hours_z}}</span></td><td><b v-if="item.parent==0">{{item.hours_o}}</b><span v-else>{{item.hours_o}}</span></td><td><b v-if="item.parent==0">{{item.hours_s}}</b><span v-else>{{item.hours_s}}</span></td><td>{{item.form}}</td><td><b v-if="item.parent==0">{{item.hours_a}}</b><span v-else>{{item.hours_a}}</span></td><td align="right"><span v-if="item.num>0"><a @click="itemUp(item.p_Id)"><i class="fa-solid fa-caret-up"></i></a> <a  @click="itemDown(item.p_Id)"><i class="fa-solid fa-caret-down"></i></a></span> <button v-if="item.num>0"  class="btn btn-primary" @click="itemDel(item.p_Id, item.name)"> Удалить </button></td></tr>


<tr><td> </td><td><button class="new" @click="rprgSave(2)" >+ Новый модуль</button> </td><th> </th><td></td><td></td><td></td></tr>

<tr><td> </td><td> Консультации  </td><td><span> {{hours_k}} </span></td><td></td><td></td><td></td></tr>
<tr><td> </td><td> Итоговая аттестация  </td><td><span> {{hours_i}}  </span></td><td></td><td></td><td></td><td></td><td></td><td>  </td><td></td></tr>
<!--<tr><td> </td><td><router-link :to="{ name: 'p_item', params: {id: this.course_id,  parent: 0,  p_id: 0  }}"><button class="new">+ Новый модуль</button></router-link>  </td><th> </th><td></td><td></td><td></td></tr>-->
<tr><td> </td><td>Итого: </td><td>{{Number(s_hours_o) + Number(s_hours_z) + Number(s_hours_l) + Number(s_hours_p) + Number(s_hours_s) + Number(s_hours_a) + Number(hours_i) + Number(hours_k)}} </td><td>{{s_hours_l}}</td><td>{{s_hours_p}} </td><td>{{s_hours_z}}</td><td>{{s_hours_o}}</td><td>{{s_hours_s}} </td><td></td><td>{{s_hours_a}} </td><td><button class="new"  @click="calc()">Пересчитать часы</button></td></tr>
<tr><td> </td><td>Запланированное количество часов:</td><td> {{hours}}</td><td>{{hours_l}}</td><td>{{hours_p}}</td><td>{{hours_z}}</td><td>{{hours_o}}</td><td>{{hours_s}}</td><td></td><td>{{hours_a}}</td><td></td></tr>


</table>

<p><span class="os">5 СОДЕРЖАНИЕ </span></p>
<p>(<i>формируется автоматически из содержания модулей</i>)</p>
<br />
<p><span class="os">6 ОРГАНИЗАЦИОННО-ПЕДАГОГИЧЕСКИЕ УСЛОВИЯ РЕАЛИЗАЦИИ ПРОГРАММЫ</span></p>
<p><span class="os">Материально-технические условия реализации программы и образовательные  технологии:</span>  <textarea class="form-control" v-model="p5" rows="10"  > </textarea></p>
<p><span class="os">7 Форма аттестации:</span>  <textarea class="form-control" v-model="p6" rows="10"  > </textarea></p>
<p><span class="os">8 Оценочные материалы:</span>  <textarea class="form-control" v-model="p3" rows="10"  > </textarea></p>
<p><span class="os">9 ЛИТЕРАТУРА </span></p>
<p>(<i>формируется автоматически из модулей</i>)</p>
<p><span class="os">10 Составители программы:</span>  <textarea class="form-control" v-model="p4"  rows="5" > </textarea></p>

<button class="green" @click="rprgSave(0)"><i class="btn btn-primary"></i> Сохранить </button>
<button class="green" @click="rprgSave(1)"><i class="btn btn-primary"></i> Сохранить и закрыть </button>
      &nbsp <router-link   :to="{ name: 'courses_list', params: { } }" > <button  class="btn btn-outline-primary">Отмена</button></router-link>

<a :href="'view.php?id='+course_id
" target="_blank"><button class="pred"><i class="btn btn-secondary"></i> Предварительный просмотр </button></a>


</div></div>`
};
