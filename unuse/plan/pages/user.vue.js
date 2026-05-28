var UserEdit = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info9: [],
      message: '',
      user_id: 0,
      group_id: 0,
      lastname: '',
      firstname: '',
      middlename: '',
      email_lms: '',
      email_c: '',
      login: '',
      password: '',
      organization_id: 0,
      position_id: 0,
      position_name: '',
      subdivision: '',
      date_of_birth: '',
      view_password: 0,
      v_password_txt: ' показать пароль ',
      view_link_button: 0,
      user2_id: 0,
      cart_id: ''
     }
   },

   mounted() {
//    updated() {
    this.user_id = this.$route.params.userid
    this.group_id = this.$route.params.groupid

    axios
      .post(JsonApiURL+'api_json.php', {user_detalies: {userId: this.user_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
	    this.lastname = this.info.userInfo.lastname
            this.firstname = this.info.userInfo.firstname
            this.middlename = this.info.userInfo.middlename
            this.email_lms = this.info.userInfo.email_lms
            this.login = this.info.userInfo.login
            this.password = this.info.userInfo.password
            this.organization_id = this.info.userInfo.organization_id
            this.position_id = this.info.userInfo.position_id
            this.position_name = this.info.userInfo.position
            this.subdivision = this.info.userInfo.subdivision
            this.date_of_birth = this.info.userInfo.date_of_birth
            this.cart_id = this.info.userInfo.cart_id

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'api_json.php', {organizations: {}})
      .then(response => { 
            this.info2 = response.data
       })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'api_json.php', {positions: {}})
      .then(response => { 
            this.info3 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })


  },


  methods: {
        userSave () {

	    axios
            .post(JsonApiURL+'api_json.php', {user_save: {userId: this.user_id, lastname: this.lastname, firstname: this.firstname, middlename: this.middlename,  email_lms: this.email_lms, login: this.login, password: this.password, organization_id: this.organization_id, position_id: this.position_id, position_name: this.position_name,  subdivision: this.subdivision, date_of_birth: this.date_of_birth, groupId: this.group_id,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data
              //this.user2_id = this.info9.userInfo.userId

              if(response.data.status==0) {
		if( this.group_id >0 ) {
                   this.$router.push({ name: 'groupitems', params: {groupid: this.group_id }})
		}
		else {
                   this.$router.push({ path: '/list'}) 
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



        onChangeUser(){
	   //if(this.lastname != '' || this.email_lms != '' || this.login  != '') {
	    axios
    	     .post(JsonApiURL+'api_json.php', {user_search: {lastname: this.lastname, firstname: this.firstname, middlename: this.middlename,  email_lms: this.email_lms, login: this.login} })
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


  },



	template: `<div><navigation></navigation><h3>Редактирование записи</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">
  <div class="row">
    <div class="col">

  <div class="mb-2 row">	
  <label for="input_lastname" class="form-label col-sm-2" >Фамилия</label>
    <input v-model="lastname"  class="form-control col" id="input_lastname"   type="text" @input="onChangeUser()" >
  </div>

  <div class="mb-2 row">	
    <label for="input_firstname" class="form-label col-sm-2">Имя</label>
      <input v-model="firstname"  class="form-control col" id="input_firstname"    type="text" @input="onChangeUser()" >
  </div>

  <div class="mb-2 row">	
    <label for="input_middlename" class="form-label col-sm-2">Отчество</label>
     <input v-model="middlename"  class="form-control col" id="input_middlename"    type="text"  @input="onChangeUser()"  >
  </div>

  <br />
  <div class="mb-2 row">	
      <label for="input_organization" class="form-label col-sm-2">Место работы  </label>
	<select  v-model="organization_id"  id=="input_organization" class="form-select col" aria-label="Организация">
	<option value="0"> -  Организация  - </option>
        <option v-for="item2 in info2.list" :value="item2.organizationId">
	    {{item2.name.substring(0, 70)}} 
	</option>
	</select>
  </div>

  <div class="mb-2 row">	
    <label for="input_subdivision" class="form-label col-sm-2">Подразделение</label>
     <input v-model="subdivision"  class="form-control col" id="input_subdivision"    type="text"  @input="onChangeUser()"  >
  </div>

  <div class="mb-2 row">	
      <label for="input_position" class="form-label col-sm-2">Должность </label>
      <input v-model="position_name"  class="form-control col" id="input_position"    type="text"  @input="onChangeUser()"  >
	<!--<select  v-model="position_id"  id=="input_position" class="form-select" aria-label="Должность">
	<option value="0"> -  Должность  - </option>
        <option v-for="item3 in info3.list" :value="item3.positionId">
	    {{item3.name.substring(0, 70)}} 
	</option>
	</select>-->
  </div>

  <br />

  <div class="mb-2 row">	
    <label for="input_email_c" class="form-label col-sm-2">email_c для связи</label>
    <input v-model="email_c"  class="form-control col" id="input_email_c"    type="text"  >
  </div>


  <br />
  <div class="mb-2 row">	
    <label for="input_date_of_birth" class="form-label col-sm-2" style="font-size: smaller;">Год  месяц день рождения</label>
     <input v-model="date_of_birth"  class="form-control col" id="input_date_of_birth"    type="text"    >
  </div>

  <div class="mb-2 row">	
    <label for="input_idcard" class="form-label col-sm-2" style="font-size: smaller;">№ Карты</label>
     <input v-model="cart_id"  class="form-control col" id="input_idcard"    type="text"  >
  </div>






  <span v-if="user_id>0" >

    <br />
    <div class="accordion accordion-flush" id="accordionFlushExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
             Логин, Пароль
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">


  <div class="mb-2 row">	
    <label for="input_email_lms" class="form-label col-sm-2">email для  LMS</label>
    <input v-model="email_lms"  class="form-control col" id="input_email_lms"    type="text"  >
  </div>

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
    <span v-if="group_id>0">
      &nbsp<router-link :to="{ name: 'groupitems', params: {groupid: group_id  }}"><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </span>
    <span v-else>
      &nbsp<router-link to="/list" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </span>
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




