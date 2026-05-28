var TeacherEdit = {
  data: function () {
    return {
      role: '', 
      info: [],
      info2: [],
      info9: [],
      message: '',
      user_id: 0,
      lastname: '',
      firstname: '',
      middlename: '',
      email: '',
      on_staff: false,
      education: '',
      login: '',
      password: '',
      phone: '',
      date_of_birth: '',
      view_password: 0,
      v_password_txt: ' показать пароль ',
      view_link_button: 0,
      user2_id: 0,
      snils: '',
      inn: '',
      pasport: '',
      pasport2: '',
      address: '',
      diplom: '',
      user_status_id: 0,
      
      wait: 0,
     }
   },

   mounted() {
    this.user_id = Number(this.$route.params.userid)

    if(this.user_id >0 ) {
        axios
            .post(JsonApiURL+'api/teacher_json.php', {object: {objectId: this.user_id, sessionId: session_t.sessionId } })
            .then(response => { 
                this.info = response.data
                this.role = this.info.role
                this.lastname = this.info.result.lastname
                this.firstname = this.info.result.firstname
                this.middlename = this.info.result.middlename
                this.email = this.info.result.email
                this.phone = this.info.result.phone
                this.on_staff = this.info.result.on_staff
                if( this.on_staff >= 1)
                     this.on_staff = true
                
                this.login = this.info.result.login
                this.password = this.info.result.password
                this.counterparty_id = this.info.result.counterparty_id
                this.date_of_birth = this.info.result.date_of_birth
                this.inn = this.info.result.inn
                this.snils = this.info.result.snils
                this.pasport = this.info.result.pasport
                this.pasport2 = this.info.result.pasport2
                this.address = this.info.result.address
                this.education = this.info.result.education
                this.diplom = this.info.result.diplom


                axios
                    .post(JsonApiURL+'api/fm_json.php', {mkdir: {parent_directory: 'documents1', filemanager_directory: 't'+this.user_id, sessionId: session_t.sessionId } })
                    .then(response2 => { 
                        this.info9 = response2.data
                })
                    .catch(error => {
                        console.log(error.response)
                })

                filemanager( 'documents1', 'Документы', 't'+this.user_id, this.lastname + ' ' + this.firstname + ' ' + this.middlename );

            })
            .catch(error => {
                console.log(error.response)
            })
    }




  },


  methods: {
        userSave () {
          var on_staff = 0    
          if(this.on_staff==true)
              on_staff = 1

	        axios
            .post(JsonApiURL+'api/teacher_json.php', {save: {objectId: this.user_id, lastname: this.lastname, firstname: this.firstname, middlename: this.middlename,  on_staff: on_staff, login: this.login, password: this.password,  date_of_birth: this.date_of_birth, inn: this.inn, snils: this.snils, pasport: this.pasport, pasport2: this.pasport2, phone: this.phone, email: this.email, address: this.address,  education: this.education, diplom: this.diplom, status: 0,   sessionId: session_t.sessionId } })
            .then(response => {
                console.log(response)
    	        this.info9 = response.data
                    this.$router.push({ name: 'teacher_list', params: { }}) 
            })
            .catch(error => {
              console.log(error.response)
            })

        },


        onChangeUser(){
	   //if(this.lastname != '' || this.email_lms != '' || this.login  != '') {
	    axios
    	     .post(JsonApiURL+'api/teacher_json.php', {user_search: {lastname: this.lastname, firstname: this.firstname, middlename: this.middlename,  email_lms: this.email_lms, login: this.login} })
    	     .then(response => { 
              this.info9 = response.data;
              if(this.info9.userId > 0){
                this.view_link_button = 1;
                this.user2_id = this.info9.userId;
              }
              else
                this.view_link_button = 0;
                this.user2_id = 0;
           })
              .catch(error => {
              console.log(error.response)
    	   })
         //}
       },

        show_hide_password() {
          if(this.view_password == 0) {
	    this.view_password = 1;
  	    this.v_password_txt = ' скрыть пароль ';
          }
          else {
	    this.view_password = 0;
	    this.v_password_txt = ' показать пароль ';
          }
        },

        ucFirst(str) {
            if (!str) return str;

            return str[0].toUpperCase() + str.slice(1);
        },
        


        handleDiplomUpload() {
            this.diplomtfile = document.getElementById('input_diplom').files[0];
            this.diplom_filename = this.diplomtfile.name;
            this.diplom_file_type = this.diplomtfile.type;
        }, 

        handlePhotoUpload() {
            this.photofile = document.getElementById('input_photo').files[0];
            this.photo_filename = this.photofile.name;
            this.photo_file_type = this.photofile.type;
        }, 

        
    phoneMask(){
        var  x = this.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        this.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    }  


  },



	template: `<div><navigation></navigation><h3>Редактирование записи</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
<div class="container">
  <div class="row">
    <div class="col">

  <div class="mb-2 row">	
  <label for="input_lastname" class="form-label col-sm-2" >Фамилия</label>
    <input v-model="lastname"  class="form-control col text-capitalize" id="input_lastname"   type="text" @input="onChangeUser()" >
    <div class="col-2" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

  <div class="mb-2 row">	
    <label for="input_firstname" class="form-label col-sm-2">Имя</label>
      <input v-model="firstname"  class="form-control col text-capitalize" id="input_firstname"    type="text" @input="onChangeUser()" >
    <div class="col-2" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

  <div class="mb-2 row">	
    <label for="input_middlename" class="form-label col-sm-2">Отчество</label>
     <input v-model="middlename"  class="form-control col text-capitalize" id="input_middlename"  type="text"  @input="onChangeUser()"  >
    <div class="col-2" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

  <div class="mb-2 row">	
    <label for="input_email_c" class="form-label col-sm-2">Email</label>
    <input v-model="email"  class="form-control col" id="input_email_c"    type="text"  >
    <div class="col-2"> </div>
  </div>


  <div class="mb-2 row">	
    <label for="input_phone" class="form-label col-sm-2">Телефон</label>
    <div class="input-group col-sm">
      <span class="input-group-text" id="phone-addon">+7</span>
      <input v-model="phone"  class="form-control" id="input_phone" type="text" @change="phoneMask()" >
    </div>
    <div class="col-6"> </div>
  </div>

  <br />
  <div class="mb-2 row">
      <div lass="col-sm-2">  </div>
      <div class="mb-2 col-sm">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox_on_staff" v-model="on_staff">
            <label class="form-check-label" for="inlineCheckbox_on_staff"> в штате </label>&nbsp;&nbsp;
        </div>
      </div>
  </div>

  <hr />
  <div class="mb-2 row">	
    <label for="input_inn" class="form-label col-sm-2" style="font-size: smaller;"> ИНН </label>
     <input v-model="inn"  class="form-control col" id="input_inn"    type="text"  >
     <div class="col-6" ></div>
  </div>
  
  <div class="mb-2 row">	
    <label for="input_idcard" class="form-label col-sm-2" style="font-size: smaller;"> СНИЛС </label>
     <input v-model="snils"  class="form-control col" id="input_idcard"    type="text"  >
     <div class="col-6" ></div>
  </div>
  

  <div class="mb-2 row">	
    <label for="input_pasport1" class="form-label col-sm-2" style="font-size: smaller;"> Серия и номер паспорта </label>
     <input v-model="pasport"  class="form-control col" id="input_pasport1"    type="text"  >
  </div>
  <div class="mb-2 row">	
    <label for="input_pasport2" class="form-label col-sm-2" style="font-size: smaller;"> Паспорт выдан </label>
     <textarea v-model="pasport2"  class="form-control col" id="input_pasport2"  ></textarea>
  </div>


  <div class="mb-2 row">	
    <label for="input_address" class="form-label col-sm-2" style="font-size: smaller;"> Адрес регистрации </label>
     <textarea v-model="address"  class="form-control col" id="input_address" ></textarea>
  </div>
  
  <div class="mb-2 row">	
    <label for="input_date_of_birth" class="form-label col-sm-2" style="font-size: smaller;">Дата  рождения</label>
     <input v-model="date_of_birth"  class="form-control col" id="input_date_of_birth" type="date">
     <div class="col-6" style="color: green"></div>
  </div>

  <div class="mb-2 row">	
    <label for="input_education" class="form-label col-sm-2" style="font-size: smaller;"> Образование </label>
     <textarea v-model="education"  class="form-control col" id="input_education" ></textarea>
  </div>

  <div class="mb-2 row">	
    <label for="input_diplom" class="form-label col-sm-2" style="font-size: smaller;"> Документ об образовании </label>
     <input v-model="diplom"  class="form-control col" id="input_diplom"    type="text"  >
  </div>


  <br />
  <span v-if="on_staff==true">
  <p>Необходимо загрузить скан документов: документ об образовании, справка о наличии (отсутствии) судимости</p>
  </span>
  <div id="filemanager" style="height: 40vh; max-height: 240px; position: relative;"></div>


  <span v-if="user_id>0" >
    <br />
    <div class="accordion accordion-flush" id="accordionFlushLigin" style="--bs-accordion-bg: #efefef;">
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
             Логин, Пароль личного кабинета 
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">

  <!--<div class="mb-2 row">	
    <label for="input_email_lms" class="form-label col-sm-2">email для  LMS</label>
    <input v-model="email_lms"  class="form-control col" id="input_email_lms"    type="text"  >
  </div>-->

  <div class="mb-2 row">	
      <label for="input_login" class="form-label col-sm-2">login</label>
      <input v-model="login"   class="form-control col" id="input_login"   type="text"  >
  </div>

  <div class="mb-2 row">	
      <label for="input_password" class="form-label col-sm-2">password</label>
      <div class="form-control col"> 
      <span v-if="view_password>0">
           <input v-model="password" id="input_password"    type="text" class="col-sm-6"  >
      </span>
      <span v-else">
	<br />
      </span>

        <button  class="btn btn-outline-secondary"  @click="show_hide_password();" >{{v_password_txt}}</button>
    </span>
    </div>
  </div>
          </div>
        </div>
      </div>
     </div>



  </span>
   <span v-else>
    <input v-model="email_lms"  id="input_email_lms"    type="hidden" >
    <input v-model="login"   id="input_login"   type="hidden"  >
    <input v-model="password"   id="input_password"    type="hidden"  >
    <br />
  </span>



  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="userSave()"> Сохранить </button>
      &nbsp<router-link :to="{ name: 'teacher_list'}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>



 <div class="col">

   <p>{{this.info2.lastname}}</p>
   <p>{{this.info2.firstname}}</p>
   <p>{{this.info2.middlename}}</p>
   <p>{{this.info2.email_lms}}</p>
    <div align="right">
     <span v-if="view_link_button >0 ">
        <button class="btn btn-success"  type="primary"  @click="userLink()"> Использовать существующую учетную запись </button>
     </span>
    </div>


 </div>
 </div>

</div>
	</div>`

};




