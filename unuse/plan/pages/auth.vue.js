var Auth = {
  data: function () {
    return {
      title: '',
      sessionId: '',
      info: '',
      login: '',
      key: '',
      password: '',
      message: '',
     }
   },


   mounted() {
//    updated() {
    this.title = AuthTitle
    this.sessionId = session_t.sessionId
    this.key = this.$route.params.key


  },


  methods: {
	a_login() {

      axios
        .post(JsonApiURL+'account_json.php', {auth: {login: this.login, password: this.password}})
        .then(response => { 
            this.info = response.data
	    session_t.sessionId = this.info.sessionId
	    session_t.role = this.info.role
	    window.location.href = '#/'

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
         })
        .catch(error => {
              console.log(error.response)
            })

      }

   },

	template: `<div>
  <h4 style="text-align: center; color: red;">{{message}}</h4>

<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="/images/logo.jpg"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

	  <br/><br/>    
          <div class="form-outline mb-4">
            <input  v-model="login"   type="text" id="formLogin" class="form-control form-control-lg"      placeholder="Логин" />
            <label class="form-label" for="formLogin">Логин</label>
          </div>

          <div class="form-outline mb-3">
            <input  v-model="password"  type="password" id="formPassword" class="form-control form-control-lg"      placeholder="Пароль" />
            <label class="form-label" for="formPassword">Пароль</label>
          </div>


          <div class="text-center text-lg-start mt-4 pt-2">
            <button  @click="a_login()"  type="button" class="btn btn-secondary btn-lg"      style="padding-left: 2.5rem; padding-right: 2.5rem;"> Войти </button>
          </div>
	  <br />

      </div>
    </div>
  </div>
</section>




<!--
	<br /><br />
	<div align="center">
        <div class="shadow" style="min-height: 300px;max-height: 300px;background: linear-gradient(45deg, var(--bs-gray-200), var(--bs-gray-100));min-width: 300px;max-width: 300px;">
            <div style="min-height: 60px;max-height: 60px;">
                <h4 class="text-white d-flex align-items-center" style="padding: 16px;background: linear-gradient(45deg, var(--bs-primary), var(--bs-info));min-height: 60px;max-height: 60px;">Авторизация в системе</h4>
            </div>
            <div style="padding: 16px;">
                <label class="form-label" style="min-width: 100%;max-width: 100%;">Логин<input class="form-control" type="text"></label>
		<label class="form-label" style="min-width: 100%;max-width: 100%;">Пароль<input class="form-control" type="password"></label>
                <div class="btn-group" role="group" style="padding-top: 14px;min-width: 100%;max-width: 100%;"><button @click="this.login='';  this.password='';"  class="btn btn-light" >Отмена</button><button @click="a_login()" class="btn btn-success">Войти</button></div>
            </div>
        </div>
        </div>
-->

	</div>`

};




