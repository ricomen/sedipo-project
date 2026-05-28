var RoleEdit = {

  data: function () {
    return {
      info: [],
      message: '',
      role_id: 0,
      account_id: 0,
      role_name: '',
      accountedit_on_rw: 0,
      self_list_on_rw: 0,
      rolelist_on_rw: 0,
      accountslist_on_rw: 0,
      set_template_contract_on_rw: 0,
      validity_period_counterparty_list_on_rw: 0,
      course_category_list_on_rw: 0,
      courses_list_on_rw: 0,
      teachers_commission_list_on_rw: 0,
      teacher_list_on_rw: 0,
      template_list_on_rw: 0,
      counterparty_list_on_rw: 0,
      students_list_on_rw: 0,
      orders_analytics_on_rw: 0,
      orders_table_on_rw: 0,
      stat_report_on_rw: 0,
      groups_list_on_rw: 0,
      lstream_list_on_rw: 0,
      calendar_on_rw: 0,
      eisot_import_on_rw: 0,
      orders_list_on_rw: 0,
      orders_list_buh_on_rw: 0,
      upload_flag_on_rw: 0,
       role_privileges: {
          accountedit: 0,
          self_list: 0,
          rolelist: 0,
          accountslist: 0,
          set_template_contract: 0,
          validity_period_counterparty_list: 0,
          course_category_list: 0,
          courses_list: 0,
          teachers_commission_list: 0,
          teacher_list: 0,
          template_list: 0,
          counterparty_list: 0,
          students_list: 0,
          orders_analytics: 0,
          orders_table: 0,
          stat_report: 0,
          groups_list: 0,
          lstream_list: 0,
          calendar: 0,
          eisot_import: 0,
          orders_list: 0,
          orders_list_buh: 0,
          upload_flag: 0
        },
     }
   },

   mounted() {
//    updated() {
    this.role_privileges =  session_role_privileges
    this.role_id = Number(this.$route.params.roleid)
    if(this.role_id > 0 ){
    axios
      .post(JsonApiURL+'api/role_json.php', {object: {objectId: this.role_id, sessionId: session_t.sessionId } }, {withCredentials: true})
      .then(response => { 
            this.info = response.data
       //     this.role = this.info.role
	        this.role_name = this.info.result.role_name

          this.accountedit = this.info.result.accountedit
          if( this.accountedit >= 1)
            this.accountedit_on_read = true
          if( this.accountedit == 2)
            this.accountedit_on_write = true

          this.self_list = this.info.result.self_list
          if( this.self_list >= 1)
            this.self_list_on_read = true
          if( this.self_list == 2)
            this.self_list_on_write = true

          this.rolelist = this.info.result.rolelist
          if( this.rolelist >= 1)
            this.rolelist_on_read = true
          if( this.rolelist == 2)
            this.rolelist_on_write = true

          this.accountslist = this.info.result.accountslist
          if( this.accountslist >= 1)
            this.accountslist_on_read = true
          if( this.accountslist == 2)
            this.accountslist_on_write = true

          this.set_template_contract = this.info.result.set_template_contract
          if( this.set_template_contract >= 1)
            this.set_template_contract_on_read = true
          if( this.set_template_contract == 2)
            this.set_template_contract_on_write = true

          this.validity_period_counterparty_list = this.info.result.validity_period_counterparty_list
          if( this.validity_period_counterparty_list >= 1)
            this.validity_period_counterparty_list_on_read = true
          if( this.validity_period_counterparty_list == 2)
            this.validity_period_counterparty_list_on_write = true

          this.course_category_list = this.info.result.course_category_list
          if( this.course_category_list >= 1)
            this.course_category_list_on_read = true
          if( this.course_category_list == 2)
            this.course_category_list_on_write = true

          this.courses_list = this.info.result.courses_list
          if( this.courses_list >= 1)
            this.courses_list_on_read = true
          if( this.courses_list == 2)
            this.courses_list_on_write = true

          this.teachers_commission_list = this.info.result.teachers_commission_list
          if( this.teachers_commission_list >= 1)
            this.teachers_commission_list_on_read = true
          if( this.teachers_commission_list == 2)
            this.teachers_commission_list_on_write = true

          this.teacher_list = this.info.result.teacher_list
          if( this.teacher_list >= 1)
            this.teacher_list_on_read = true
          if( this.teacher_list == 2)
            this.teacher_list_on_write = true

          this.template_list = this.info.result.template_list
          if( this.template_list >= 1)
            this.template_list_on_read = true
          if( this.template_list == 2)
            this.template_list_on_write = true

          this.counterparty_list = this.info.result.counterparty_list
          if( this.counterparty_list >= 1)
            this.counterparty_list_on_read = true
          if( this.counterparty_list == 2)
            this.counterparty_list_on_write = true

          this.students_list = this.info.result.students_list
          if( this.students_list >= 1)
            this.students_list_on_read = true
          if( this.students_list == 2)
            this.students_list_on_write = true

          this.orders_analytics = this.info.result.orders_analytics
          if( this.orders_analytics >= 1)
            this.orders_analytics_on_read = true
          if( this.orders_analytics == 2)
            this.orders_analytics_on_write = true

          this.orders_table = this.info.result.orders_table
          if( this.orders_table >= 1)
            this.orders_table_on_read = true
          if( this.orders_table == 2)
            this.orders_table_on_write = true

          this.stat_report = this.info.result.stat_report
          if( this.stat_report >= 1)
            this.stat_report_on_read = true
          if( this.stat_report == 2)
            this.stat_report_on_write = true

          this.groups_list = this.info.result.groups_list
          if( this.groups_list >= 1)
            this.groups_list_on_read = true
          if( this.groups_list == 2)
            this.groups_list_on_write = true

          this.lstream_list = this.info.result.lstream_list
          if( this.lstream_list >= 1)
            this.lstream_list_on_read = true
          if( this.lstream_list == 2)
            this.lstream_list_on_write = true

          this.calendar = this.info.result.calendar
          if( this.calendar >= 1)
            this.calendar_on_read = true
          if( this.calendar == 2)
            this.calendar_on_write = true

          this.eisot_import = this.info.result.eisot_import
          if( this.eisot_import >= 1)
            this.eisot_import_on_read = true
          if( this.eisot_import == 2)
            this.eisot_import_on_write = true

          this.orders_list = this.info.result.orders_list
          if( this.orders_list >= 1)
            this.orders_list_on_read = true
          if( this.orders_list == 2)
            this.orders_list_on_write = true

          this.orders_list_buh = this.info.result.orders_list_buh
          if( this.orders_list_buh >= 1)
            this.orders_list_buh_on_read = true
          if( this.orders_list_buh == 2)
            this.orders_list_buh_on_write = true

          this.upload_flag = this.info.result.upload_flag
          if( this.upload_flag >= 1)
            this.upload_flag_on_read = true
          if( this.upload_flag == 2)
            this.upload_flag_on_write = true
          

       //     this.email = this.info.result.email
       //     this.login = this.info.result.login

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })
    }

  },


  methods: {
        roleSave() {
          var accountedit_on_rw = 0
          var self_list_on_rw = 0
          var rolelist_on_rw = 0
          var accountslist_on_rw = 0
          var set_template_contract_on_rw = 0
          var validity_period_counterparty_list_on_rw = 0
          var course_category_list_on_rw = 0
          var courses_list_on_rw = 0
          var teachers_commission_list_on_rw = 0
          var teacher_list_on_rw = 0
          var template_list_on_rw = 0
          var counterparty_list_on_rw = 0
          var students_list_on_rw = 0
          var orders_analytics_on_rw = 0
          var orders_table_on_rw = 0
          var stat_report_on_rw = 0
          var groups_list_on_rw = 0
          var lstream_list_on_rw = 0
          var calendar_on_rw = 0
          var eisot_import_on_rw = 0
          var orders_list_on_rw = 0
          var orders_list_buh_on_rw = 0
          var upload_flag_on_rw = 0

          if(this.accountedit_on_write==true)
          accountedit_on_rw = 2
          else
          if(this.accountedit_on_read==true)
          accountedit_on_rw = 1

          if(this.self_list_on_write==true)
          self_list_on_rw = 2
          else
          if(this.self_list_on_read==true)
          self_list_on_rw = 1

          if(this.rolelist_on_write==true)
          rolelist_on_rw = 2
          else
          if(this.rolelist_on_read==true)
          rolelist_on_rw = 1
        
          if(this.accountslist_on_write==true)
          accountslist_on_rw = 2
          else
          if(this.accountslist_on_read==true)
          accountslist_on_rw = 1

          if(this.set_template_contract_on_write==true)
          set_template_contract_on_rw = 2
          else
          if(this.set_template_contract_on_read==true)
          set_template_contract_on_rw = 1

          if(this.validity_period_counterparty_list_on_write==true)
          validity_period_counterparty_list_on_rw = 2
          else
          if(this.validity_period_counterparty_list_on_read==true)
          validity_period_counterparty_list_on_rw = 1

          if(this.course_category_list_on_write==true)
          course_category_list_on_rw = 2
          else
          if(this.course_category_list_on_read==true)
          course_category_list_on_rw = 1

          if(this.courses_list_on_write==true)
          courses_list_on_rw = 2
          else
          if(this.courses_list_on_read==true)
          courses_list_on_rw = 1

          if(this.teachers_commission_list_on_write==true)
          teachers_commission_list_on_rw = 2
          else
          if(this.teachers_commission_list_on_read==true)
          teachers_commission_list_on_rw = 1

          if(this.teacher_list_on_write==true)
          teacher_list_on_rw = 2
          else
          if(this.teacher_list_on_read==true)
          teacher_list_on_rw = 1

          if(this.template_list_on_write==true)
          template_list_on_rw = 2
          else
          if(this.template_list_on_read==true)
          template_list_on_rw = 1

          if(this.counterparty_list_on_write==true)
          counterparty_list_on_rw = 2
          else
          if(this.counterparty_list_on_read==true)
          counterparty_list_on_rw = 1

          if(this.students_list_on_write==true)
          students_list_on_rw = 2
          else
          if(this.students_list_on_read==true)
          students_list_on_rw = 1      
        
          if(this.orders_analytics_on_write==true)
          orders_analytics_on_rw = 2
          else
          if(this.orders_analytics_on_read==true)
          orders_analytics_on_rw = 1          

          if(this.orders_table_on_write==true)
          orders_table_on_rw = 2
          else
          if(this.orders_table_on_read==true)
          orders_table_on_rw = 1

          if(this.stat_report_on_write==true)
          stat_report_on_rw = 2
          else
          if(this.stat_report_on_read==true)
          stat_report_on_rw = 1

          if(this.groups_list_on_write==true)
          groups_list_on_rw = 2
          else
          if(this.groups_list_on_read==true)
          groups_list_on_rw = 1

          if(this.lstream_list_on_write==true)
          lstream_list_on_rw = 2
          else
          if(this.lstream_list_on_read==true)
          lstream_list_on_rw = 1

          if(this.calendar_on_write==true)
          calendar_on_rw = 2
          else
          if(this.calendar_on_read==true)
          calendar_on_rw = 1

          if(this.eisot_import_on_write==true)
          eisot_import_on_rw = 2
          else
          if(this.eisot_import_on_read==true)
          eisot_import_on_rw = 1

          if(this.orders_list_on_write==true)
          orders_list_on_rw = 2
          else
          if(this.orders_list_on_read==true)
          orders_list_on_rw = 1

          if(this.orders_list_buh_on_write==true)
          orders_list_buh_on_rw = 2
          else
          if(this.orders_list_buh_on_read==true)
          orders_list_buh_on_rw = 1

          if(this.upload_flag_on_write==true)
          upload_flag_on_rw = 2
          else
          if(this.upload_flag_on_read==true)
          upload_flag_on_rw = 1




	    axios
            .post(JsonApiURL+'api/role_json.php', {save: {objectId: this.role_id, accountedit: accountedit_on_rw, self_list: self_list_on_rw, rolelist: rolelist_on_rw, accountslist: accountslist_on_rw, set_template_contract: set_template_contract_on_rw, validity_period_counterparty_list: validity_period_counterparty_list_on_rw, course_category_list: course_category_list_on_rw, courses_list: courses_list_on_rw, teachers_commission_list: teachers_commission_list_on_rw, teacher_list: teacher_list_on_rw, template_list: template_list_on_rw, counterparty_list: counterparty_list_on_rw, students_list: students_list_on_rw, orders_analytics: orders_analytics_on_rw, orders_table: orders_table_on_rw, stat_report: stat_report_on_rw, groups_list: groups_list_on_rw, lstream_list: lstream_list_on_rw, calendar: calendar_on_rw, eisot_import: eisot_import_on_rw, orders_list: orders_list_on_rw, orders_list_buh: orders_list_buh_on_rw, upload_flag: upload_flag_on_rw, sessionId: session_t.sessionId } }, {withCredentials: true})
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

              if(response.data.status==0) {
	
                   this.$router.push({ path: '/rolelist'}) 
		          }
              
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })

        },


        accountCreate() {
          
          var accountedit_on_rw = 0
          var self_list_on_rw = 0
          var rolelist_on_rw = 0
          var accountslist_on_rw = 0
          var set_template_contract_on_rw = 0
          var validity_period_counterparty_list_on_rw = 0
          var course_category_list_on_rw = 0
          var courses_list_on_rw = 0
          var teachers_commission_list_on_rw = 0
          var teacher_list_on_rw = 0
          var template_list_on_rw = 0
          var counterparty_list_on_rw = 0
          var students_list_on_rw = 0
          var orders_analytics_on_rw = 0
          var orders_table_on_rw = 0
          var stat_report_on_rw = 0
          var groups_list_on_rw = 0
          var lstream_list_on_rw = 0
          var calendar_on_rw = 0
          var eisot_import_on_rw = 0
          var orders_list_on_rw = 0
          var orders_list_buh_on_rw = 0
          var upload_flag_on_rw = 0
          

          if(this.accountedit_on_write==true)
          accountedit_on_rw = 2
          else
          if(this.accountedit_on_read==true)
          accountedit_on_rw = 1

          if(this.self_list_on_write==true)
          self_list_on_rw = 2
          else
          if(this.self_list_on_read==true)
          self_list_on_rw = 1

          if(this.rolelist_on_write==true)
          rolelist_on_rw = 2
          else
          if(this.rolelist_on_read==true)
          rolelist_on_rw = 1
        
          if(this.accountslist_on_write==true)
          accountslist_on_rw = 2
          else
          if(this.accountslist_on_read==true)
          accountslist_on_rw = 1

          if(this.set_template_contract_on_write==true)
          set_template_contract_on_rw = 2
          else
          if(this.set_template_contract_on_read==true)
          set_template_contract_on_rw = 1

          if(this.validity_period_counterparty_list_on_write==true)
          validity_period_counterparty_list_on_rw = 2
          else
          if(this.validity_period_counterparty_list_on_read==true)
          validity_period_counterparty_list_on_rw = 1

          if(this.course_category_list_on_write==true)
          course_category_list_on_rw = 2
          else
          if(this.course_category_list_on_read==true)
          course_category_list_on_rw = 1

          if(this.courses_list_on_write==true)
          courses_list_on_rw = 2
          else
          if(this.courses_list_on_read==true)
          courses_list_on_rw = 1

          if(this.teachers_commission_list_on_write==true)
          teachers_commission_list_on_rw = 2
          else
          if(this.teachers_commission_list_on_read==true)
          teachers_commission_list_on_rw = 1

          if(this.teacher_list_on_write==true)
          teacher_list_on_rw = 2
          else
          if(this.teacher_list_on_read==true)
          teacher_list_on_rw = 1

          if(this.template_list_on_write==true)
          template_list_on_rw = 2
          else
          if(this.template_list_on_read==true)
          template_list_on_rw = 1

          if(this.counterparty_list_on_write==true)
          counterparty_list_on_rw = 2
          else
          if(this.counterparty_list_on_read==true)
          counterparty_list_on_rw = 1

          if(this.students_list_on_write==true)
          students_list_on_rw = 2
          else
          if(this.students_list_on_read==true)
          students_list_on_rw = 1      
        
          if(this.orders_analytics_on_write==true)
          orders_analytics_on_rw = 2
          else
          if(this.orders_analytics_on_read==true)
          orders_analytics_on_rw = 1          

          if(this.orders_table_on_write==true)
          orders_table_on_rw = 2
          else
          if(this.orders_table_on_read==true)
          orders_table_on_rw = 1

          if(this.stat_report_on_write==true)
          stat_report_on_rw = 2
          else
          if(this.stat_report_on_read==true)
          stat_report_on_rw = 1

          if(this.groups_list_on_write==true)
          groups_list_on_rw = 2
          else
          if(this.groups_list_on_read==true)
          groups_list_on_rw = 1

          if(this.lstream_list_on_write==true)
          lstream_list_on_rw = 2
          else
          if(this.lstream_list_on_read==true)
          lstream_list_on_rw = 1

          if(this.calendar_on_write==true)
          calendar_on_rw = 2
          else
          if(this.calendar_on_read==true)
          calendar_on_rw = 1

          if(this.eisot_import_on_write==true)
          eisot_import_on_rw = 2
          else
          if(this.eisot_import_on_read==true)
          eisot_import_on_rw = 1

          if(this.orders_list_on_write==true)
          orders_list_on_rw = 2
          else
          if(this.orders_list_on_read==true)
          orders_list_on_rw = 1

          if(this.orders_list_buh_on_write==true)
          orders_list_buh_on_rw = 2
          else
          if(this.orders_list_buh_on_read==true)
          orders_list_buh_on_rw = 1

          if(this.upload_flag_on_write==true)
          upload_flag_on_rw = 2
          else
          if(this.upload_flag_on_read==true)
          upload_flag_on_rw = 1

          

	    axios
            .post(JsonApiURL+'api/role_json.php', {insert: { role_name:this.role_name,  accountedit: accountedit_on_rw, self_list: self_list_on_rw, rolelist: rolelist_on_rw, accountslist: accountslist_on_rw, set_template_contract: set_template_contract_on_rw, validity_period_counterparty_list: validity_period_counterparty_list_on_rw, course_category_list: course_category_list_on_rw, courses_list: courses_list_on_rw, teachers_commission_list: teachers_commission_list_on_rw, teacher_list: teacher_list_on_rw, template_list: template_list_on_rw, counterparty_list: counterparty_list_on_rw, students_list: students_list_on_rw, orders_analytics: orders_analytics_on_rw, orders_table: orders_table_on_rw, stat_report: stat_report_on_rw, groups_list: groups_list_on_rw, lstream_list: lstream_list_on_rw, calendar: calendar_on_rw, eisot_import: eisot_import_on_rw, orders_list: orders_list_on_rw, orders_list_buh: orders_list_buh_on_rw, upload_flag: upload_flag_on_rw, sessionId: session_t.sessionId } }, {withCredentials: true})
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

              if(response.data.status==0) {
		if(this.password1!='' && this.password1==this.password2){
		    axios
        	    .post(JsonApiURL+'api/auth_json.php', {update_password: {accountId: this.account_id,   password0: this.password0, password1: this.password1, password2: this.password2,   sessionId: session_t.sessionId } }, {withCredentials: true})
        	    .then(response3 => {
            		console.log(response3)
    	    		//this.info9 = response2.data

            		if(response3.data.status==0) {
	    		    this.$router.push({ path: '/rolelist'}) 
            		}
            		else {
                	    this.message = response3.data.error
            		}
        	    })
        	    .catch(error3 => {
            		console.log(error3.response)
        	    })
	    	    this.$router.push({ path: '/'}) 
		}
		else {
                   this.$router.push({ path: '/rolelist'}) 
		}

              }
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })

        }


  },


	template: `
  <container v-if="role_privileges.rolelist == 2">
  <div><navigation></navigation><h3>{{this.role_name}}</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">

  <div class="mb-2 row">	
  <label for="input_role_name"  class="form-label col-sm-2"   >Роль</label>
    <input v-model="role_name"   class="form-control col" id="input_role_name"   type="text"  >
  </div>

<!--{{this.info}}-->


  <table class="table">
      <thead>
        <tr  >
          <th scope="col-3"> Раздел/Объект для доступа  </th>
          <th scope="col"></th>
          <th scope="col" style="text-align:center"> Чтение</th>
          <th scope="col" style="text-align:center"> Запись</th>
        </tr>
      </thead>

     <tbody >   
        <tr  >
          <td > Настройки аккаунта  </td>
          <td >
          <p v-if="accountedit_on_read != true&&accountedit_on_write != true ">Скрыто</p>
          </td>
          <td > 
          <!--style="display: flex; justify-content: center;"-->
            <div class="form-check" style="display: flex; justify-content: center;" >
            <input class="form-check-input" style="padding:auto; align:center;" type="checkbox" id="inlineCheckbox_read" v-model="accountedit_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="accountedit_on_write">
                       
            </div>
          </td>
          </tr>


            <tr  >
          <td > Реквизиты организации  </td>
          <td>
          <p v-if="self_list_on_read != true&&self_list_on_write != true ">Скрыто</p>
          
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="self_list_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="self_list_on_write">
                       
            </div>
          </td>
          </tr>
  
            <tr  >
          <td > Роли пользователей  </td>
          <td>
          <p v-if="rolelist_on_read != true&&rolelist_on_write != true">Скрыто</p>
          </td>          
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="rolelist_on_read">
                      
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="rolelist_on_write">
                       
            </div>
          </td>
          </tr>

            <tr  >
          <td > Настройки доступа администраторов  </td>
          <td>
          <p v-if="accountslist_on_read != true&&accountslist_on_write != true">Скрыто</p>
          </td>
          <td >  
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="accountslist_on_read">
                      
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="accountslist_on_write">
                      
            </div>
          </td>
          </tr>  

            <tr  >
          <td > Набор используемых шаблонов документов  </td>
          <td>
          <p v-if="set_template_contract_on_read != true&&set_template_contract_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="set_template_contract_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="set_template_contract_on_write">
                       
            </div>
          </td>
          </tr>



            <tr  >
          <td > Уведомление об окончании срока действия документов </td>
                    <td>
          <p v-if="validity_period_counterparty_list_on_read != true&&validity_period_counterparty_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="validity_period_counterparty_list_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="validity_period_counterparty_list_on_write">
                       
            </div>
          </td>
          </tr>  

            <tr  >
          <td > Категории  </td>
                    <td>
          <p v-if="course_category_list_on_read != true&&course_category_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="course_category_list_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="course_category_list_on_write">
                      
            </div>
          </td>
          </tr>

            <tr  >
          <td > Курсы  </td>
                    <td>
          <p v-if="courses_list_on_read != true&&courses_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="courses_list_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="courses_list_on_write">
                       
            </div>
          </td>
          </tr>

            <tr  >
          <td > Состав комиссии  </td>
                    <td>
          <p v-if="teachers_commission_list_on_read != true&&teachers_commission_list != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="teachers_commission_list_on_read">
                      
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="teachers_commission_list_on_write">
                      
            </div>
          </td>
          </tr>

            <tr  >
          <td > Преподаватели  </td>
                    <td>
          <p v-if="teacher_list_on_read != true&&teacher_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="teacher_list_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="teacher_list_on_write">
                        
            </div>
          </td>
          </tr>

            <tr  >
          <td > Шаблоны документов  </td>
                    <td>
          <p v-if="template_list_on_read != true&&template_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="template_list_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="template_list_on_write">
                        
            </div>
          </td>
          </tr>

            <tr  >
          <td > Контрагенты  </td>
                    <td>
          <p v-if="counterparty_list_on_read != true&&counterparty_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="counterparty_list_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="counterparty_list_on_write">
                        
            </div>
          </td>
          </tr>

            <tr  >
          <td > Список слушателей  </td>
                    <td>
          <p v-if="students_list_on_read != true&&students_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="students_list_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="students_list_on_write">
                        
            </div>
          </td>
          </tr>

            <tr  >
          <td > Аналитика по заявкам  </td>
                    <td>
          <p v-if="orders_analytics_on_read != true&&orders_analytics_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="orders_analytics_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="orders_analytics_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Сводная таблица по заявкам  </td>
                    <td>
          <p v-if="orders_table_on_read != true&&orders_table_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="orders_table_on_read">
                       
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="orders_table_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Отчёт - Федеральное статистическое наблюдение  </td>
                    <td>
          <p v-if="stat_report_on_read != true&&stat_report_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="stat_report_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="stat_report_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Учебные группы  </td>
                    <td>
          <p v-if="groups_list_on_read != true&&groups_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="groups_list_on_read">
                        
            </div>
          </td>
          <td >
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="groups_list_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Учебные потоки  </td>
                    <td>
          <p v-if="lstream_list_on_read != true&&lstream_list_on_write != true">Скрыто</p>
          </td>
          <td >  
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="lstream_list_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="lstream_list_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Расписание  </td>
                    <td>
          <p v-if="calendar_on_read != true&&calendar_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="calendar_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="calendar_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Импорт номеров удостоверений из ЕИСОТ  </td>
                    <td>
          <p v-if="eisot_import_on_read != true&&eisot_import_on_write != true">Скрыто</p>
          </td>
          <td >  
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="eisot_import_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="eisot_import_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Заявки  </td>
                    <td>
          <p v-if="orders_list_on_read != true&&orders_list_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="orders_list_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="orders_list_on_write">
                        
            </div>
          </td>
          </tr>
 
            <tr  >
          <td > Бухгалтерские документы на странице "Заявки"  </td>
                    <td>
          <p v-if="orders_list_buh_on_read != true&&orders_list_buh_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="orders_list_buh_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="orders_list_buh_on_write">
                        
            </div>
          </td>
          </tr>
          <tr  >
          <td > Загрузка документов  </td>
          <td>
          <p v-if="upload_flag_on_read != true && upload_flag_on_write != true">Скрыто</p>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_read" v-model="upload_flag_on_read">
                        
            </div>
          </td>
          <td > 
            <div class="form-check" style="display: flex; justify-content: center;">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_write" v-model="upload_flag_on_write">
                        
            </div>
          </td>
          </tr>
 

  </tbody>
  </table>


  <div class="mb-2">	
    <div align="right">
    <button v-if="role_id>0"   class="btn btn-primary"    @click="roleSave()"> Сохранить </button>
    <button v-if="role_id==0"  class="btn btn-primary"    @click="accountCreate()"> Добавить роль </button>
      &nbsp<router-link to="/rolelist" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>




</div>
	</div>
  </container>`

};




