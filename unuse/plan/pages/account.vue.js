var AccountEdit = {
  data: function () {
    return {
      info: [],
      message: '',
      account_id: 0,
      group_id: 0,
      fullname: '',
      email: '',
      login: '',
      password0: '',
      password1: '',
      password2: '',
     }
   },

   mounted() {
//    updated() {
    this.account_id = this.$route.params.accountid

    axios
      .post(JsonApiURL+'account_json.php', {account_detalies: {accountId: this.account_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
	    this.fullname = this.info.accountInfo.fullname
            this.email = this.info.accountInfo.email
            this.login = this.info.accountInfo.login

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })


  },


  methods: {
        accountSave() {
	    axios
            .post(JsonApiURL+'account_json.php', {account_save: {accountId: this.account_id,  fullname: this.fullname,  email: this.email, login: this.login, password0: this.password0, password1: this.password1, password2: this.password2,   sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data
              //this.user2_id = this.info9.userInfo.userId

              if(response.data.status==0) {
	        this.$router.push({ path: '/'}) 

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



	template: `<div><navigation></navigation><h3>Редактирование записи</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">

  <div class="mb-2">	
  <label for="input_fullname" class="form-label">Имя пользователя</label>
    <input v-model="fullname"   class="form-control" id="input_fullname"   type="text"  >
  </div>

  <div class="mb-2">	
    <label for="input_email" class="form-label">email</label>
    <input v-model="email"  class="form-control" id="input_email"    type="text"  >
  </div>

  <div class="mb-2">	
      <label for="input_login" class="form-label">login</label>
      <input v-model="login"   class="form-control" id="input_login"   type="text"  >
  </div>

  <br /><hr />
  <h4>Сменить пароль </h4> 
  <div class="mb-2">	
      <label for="input_password" class="form-label">Текущий пароль</label>
      <input v-model="password0"  class="form-control" id="input_password"    type="password"  >

   </div>

  <div class="mb-2">	
      <label for="input_password1" class="form-label">Новый пароль</label>
      <input v-model="password1"  class="form-control" id="input_password1"    type="password"  >

   </div>

  <div class="mb-2">	
      <label for="input_password2" class="form-label">Новый пароль повторно</label>
      <input v-model="password2"  class="form-control" id="input_password2"    type="password"  >

   </div>


  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="accountSave()"> Сохранить </button>
      &nbsp<router-link to="/" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>




</div>
	</div>`

};




