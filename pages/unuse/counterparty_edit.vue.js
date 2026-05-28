var CounterpartyEdit = {
  data: function () {
    return {
      role: '',
      info: [],
      info1: [],
      info9: [],
      check_status: 0,
      message: '',
      counterparty_id: 0,
      is_make: 0,
      account_id: 0,
      c_type: 0,
      entity: 1,
      logo: '',
      name: '',
      shortname: '',
      email1: ' ',
      phone1: ' ',
      email_contact: ' ',
      phone_contact: ' ',
      inn: '',
      kpp: '',
      ogrn: '',
      position_head: '',
      enterprise_manager: '',
      position_head2: '',
      enterprise_manager2: '',
      addres1: '',
      addres2: '',
      bank: '',
      checking_account: '',
      correspondent_account: '',
      bik: '',
      comment: '',
      contact: '',
      longtime_contract: 'true',
      //login: '',
      password0: '',
      password1: '',
      password2: '',
      helpers: {},
      normative: {},
     }
   },

   mounted() {
//    updated() {
    this.counterparty_id = Number(this.$route.params.counterpartyid)
    if( Number(this.$route.params.make)>0 )
            this.is_make = Number(this.$route.params.make)

    if(this.counterparty_id>0){
      axios
      .post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
            this.account_id = this.info.result.account_id
            this.name = this.info.result.name
            this.shortname = this.info.result.shortname
            this.c_type  = this.info.result.type
            this.entity = this.info.result.entity
            this.email1 = this.info.result.email
            this.phone1 = this.info.result.phone
            this.inn = this.info.result.inn
            this.kpp = this.info.result.kpp
            this.ogrn = this.info.result.ogrn
            this.position_head = this.info.result.position_head
            this.enterprise_manager = this.info.result.enterprise_manager
            this.position_head2 = this.info.result.position_head2
            this.enterprise_manager2 = this.info.result.enterprise_manager2
            this.addres1 = this.info.result.addres1
            this.addres2 = this.info.result.addres2
            this.bank = this.info.result.bank
            this.checking_account = this.info.result.checking_account
            this.correspondent_account = this.info.result.correspondent_account
            this.bik = this.info.result.bik
            this.comment = this.info.result.comment
            this.longtime_contract = this.info.result.longtime_contract
            if(this.longtime_contract==1)
                this.longtime_contract = 'true'

            if(this.position_head2 == '')
                    this.position_head2 = this.position_head
            if(this.enterprise_manager2 == '')
                    this.enterprise_manager2 = this.enterprise_manager
       })
      .catch(error => {
              console.log(error.response)
        })

      axios
      .post(JsonApiURL+'api/account_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info1 = response.data
	    //this.name = this.info1.result.login
            this.email_contact = this.info1.result.email
            this.phone_contact = this.info1.result.phone
            this.contact = this.info1.result.fullname

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
        })

     axios
      .post(JsonApiURL+'api/helpers_json.php', {table: 'a_counterparty_edit'})
      .then(response => {
            const data = response.data;
            console.log(data);

            if(Object.keys(data).length) {

                const values = Object.values(data);
                for (const value of values) {
                    //console.log(value);
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
                console.log('NO DATA IN HELPERS')
            }

       })
      .catch(error => {
              console.log(error.response)
      })



    }
    else {
      axios
      .post(JsonApiURL+'api/auth_json.php', {is_auth: { sessionId: session_t.sessionId } })
      .then(response => { 
            this.info1 = response.data
            this.role = this.info1.role
       })
      .catch(error => {
              console.log(error.response)
        })
    }

  },


  methods: {
        accountSave(fl) {
         var rc=0;     
        if(this.longtime_contract==1 || this.longtime_contract==true)
                this.longtime_contract = 'true'
         
	     if(this.counterparty_id>0 && this.name!='' ){
	        axios
            .post(JsonApiURL+'api/counterparty_json.php', {update: {objectId: this.counterparty_id, name: this.name,  shortname: this.shortname, type: this.c_type,  entity: this.entity,   email: this.email1,   phone: this.phone1,  addres1: this.addres1, addres2: this.addres2,  inn: this.inn, kpp: this.kpp, ogrn: this.ogrn,  bank: this.bank, position_head: this.position_head, enterprise_manager: this.enterprise_manager, position_head2: this.position_head2, enterprise_manager2: this.enterprise_manager2, checking_account: this.checking_account, correspondent_account: this.correspondent_account,  bik: this.bik,  longtime_contract: this.longtime_contract, comment: this.comment,    sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

              if(response.data.status==0 && this.account_id>1 && this.counterparty_id>1) {
		        axios
        	    .post(JsonApiURL+'api/account_json.php', {update: {objectId: this.account_id, login: this.email1,  fullname: this.name,  email: this.email_contact,  phone: this.phone_contact,   sessionId: session_t.sessionId } })
        	    .then(response2 => {
                      console.log(response2)
    	    		  this.info9 = response2.data

            		  if(response2.data.status==0) {
			            if(this.password1!='' && this.password1==this.password2){
				            axios
        			        .post(JsonApiURL+'api/auth_json.php', {update_password: {accountId: this.account_id,   password0: this.password0, password1: this.password1, password2: this.password2,   sessionId: session_t.sessionId } })
        			        .then(response3 => {
            			        console.log(response3)
    	    			        //this.info9 = response3.data

            			        if(response3.data.status==0) {
            			            if(this.role=='counterparty') {
                			            this.$router.push({ path: '/'} )
                			            return
            			            }
        				            else if(fl){
            				            rc = confirm('Сформировать персональный прайслист органиации?')
        				            }
                            		if(rc)
                			            this.$router.push({ name: 'courses_list', params: { counterpartyid: this.counterparty_id }} ) 
                            		else
                			            this.$router.push({ path: '/counterparty_list'}) 
            			        }
            			        else {
                			        this.message = 'Указанный Официальный email уже использован в другой учетной записи' /*+ response3.data.error*/
            			        }
        			        })
        			        .catch(error3 => {
            			        console.log(error3.response)
        			        })
			        }
			        else {
		                if(this.role=='counterparty') {
			                this.$router.push({ path: '/'} )
			                return
		                }
        			    else if(fl){
            			    rc = confirm('Сформировать персональный прайслист органиации?')
        			    }
                        if(rc)
                		    this.$router.push({ name: 'courses_list', params: { counterpartyid: this.counterparty_id }} ) 
                        else
                		    this.$router.push({ path: '/counterparty_list'}) 
			        }
                }
                else {
                	    this.message = response.data.error
                }
                      
        	    })
        	    .catch(error2 => {
                       alert("Не заполнено поле 'Официальный email', или указанный email уже использован а другой учетной записи")                  
            	       console.log(error2.response)
            	    })
              }
              else {
                    alert("Не создан личный кабинет заказчика")                  
                    this.message = response.data.error
		            this.$router.push({ path: '/counterparty_list'} )
              }
            })
            .catch(error => {
              console.log(error.response)
            })
        }
        else {
           alert('Не все обяательные поля заполнены')
        }
      },


      accountCreate(fl) {
	  var objectId
	  /*var account_role
	  if( this.c_type==0 )
	        account_role = 'counterparty'
	  else if( this.c_type==1 )
	        account_role = 'performer'*/
	  
	  if(this.counterparty_id == 0){
        if(this.longtime_contract==1 || this.longtime_contract==true)
                this.longtime_contract = 'true'
	      
	    axios
            .post(JsonApiURL+'api/account_json.php', {insert: { login: this.email1,  fullname: this.name,   email: this.email_contact,  phone: this.phone_contact,   role: 'counterparty',  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info3 = response.data
              this.account_id = response.data.result
              if(response.data.status==0) {
		        axios
        	    .post(JsonApiURL+'api/counterparty_json.php', {insert: { objectId: this.account_id, account_id: this.account_id, name: this.name,  shortname: this.shortname, type: this.c_type,  entity: this.entity,  email: this.email1,   phone: this.phone1,  addres1: this.addres1, addres2: this.addres2,  inn: this.inn, kpp: this.kpp, ogrn: this.ogrn,  bank: this.bank, position_head: this.position_head, enterprise_manager: this.enterprise_manager, position_head2: this.position_head2, enterprise_manager2: this.enterprise_manager2, checking_account: this.checking_account, correspondent_account: this.correspondent_account,  bik: this.bik,  longtime_contract: this.longtime_contract, comment: this.comment,    sessionId: session_t.sessionId } })
        	    .then(response2 => {
                      console.log(response2)

            	    if(response2.data.status==0) {
			    if(this.password1!='' && this.password1==this.password2){
				axios
        			.post(JsonApiURL+'api/auth_json.php', {update_password: {accountId: this.account_id,   password0: this.password0, password1: this.password1, password2: this.password2,   sessionId: session_t.sessionId } })
        			.then(response3 => {
            			    console.log(response3)
    	    			    this.info9 = response2.data


            			    if(response3.data.status==0) {
                                        if(is_make>0){
                			    this.$router.push({ name: 'order_edit', params: { orderid: 0,  counterpartyid: this.account_id }} ) 
                                        }
        				if(fl){
            				    var rc = confirm('Сформировать персональный прайслист органиации?')
        				}
                            		if(rc)
                			    this.$router.push({ name: 'courses_list', params: { counterpartyid: this.account_id }} ) 
                            		else
                			    this.$router.push({ path: '/counterparty_list'}) 
            			    }
            			    else {
                			this.message = response3.data.error
            			    }
        			})
        			.catch(error3 => {
            			    console.log(error3.response)
        			})
			    }
			    else {
        			if(fl){
            			    var rc = confirm('Сформировать персональный прайслист органиации?')
        			}
                                if(rc)
                		    this.$router.push({ name: 'courses_list', params: { counterpartyid: this.account_id }} ) 
                                else
                		    this.$router.push({ path: '/counterparty_list'}) 
			    }
            	    }
            	    else {
                	this.message = response2.data.error
            		}
        	    })
        	    .catch(error2 => {
            	       console.log(error2.response)
        	  })

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
      
      check_unique() {
//alert("test")
        var status = 0
        axios
          .post(JsonApiURL+'api/counterparty_check_json.php', {check_unique: {is_create: this.counterparty_id, objectId: this.counterparty_id, account_id: this.counterparty_id, inn: this.inn, name: this.name, shortname: this.shortname,  email: this.email1, sessionId: session_t.sessionId } })
          .then(response => { 
              console.log(response)
                this.info9 = response.data
                //this.role = this.info9.role
                this.check_status = this.info9.status
                this.message = this.info9.error
                
                if(this.check_status>0)
                    alert(this.message)
        })
        .catch(error => {
              console.log(error.response)
        })          
      },
      
      check_required() {
//alert("test")
        var status = 0
        if(this.inn == '') {
            status = status +1
            this.message = this.message + "\n Не запллнено поле 'ИНН'"
        }            
        if(this.name == '') {
            status = status +1
            this.message = this.message + "\n Не запллнено поле 'Полное название'"
        }            
        if(this.shortname == '') {
            status = status +1
            this.message = this.message + "\n Не запллнено поле 'Краткое название'"
        }            
        if(this.email1 == '' || this.email1 == ' ') {
            status = status +1
            this.message = this.message + "\n Не запллнено поле 'Официальный email'"
        }            

        if(status>0)
            alert(this.message)
        
        return status
      },
      

      accountCheckCreate(fl) {
        var status = 0
        var rc=0
        
        rc = this.check_required()
        if(rc>0)
            return
            
        axios
          .post(JsonApiURL+'api/counterparty_check_json.php', {check_unique: {is_create: this.counterparty_id, objectId: this.counterparty_id, account_id: this.counterparty_id, inn: this.inn, name: this.name, shortname: this.shortname,  email: this.email1, sessionId: session_t.sessionId } })
          .then(response => { 
              console.log(response)
                this.info9 = response.data
                //this.role = this.info9.role
                this.check_status = this.info9.status
                this.message = this.info9.error
                
                if(this.check_status>0)
                    alert(this.message)
                else    
                    this.accountCreate(fl)
        })
        .catch(error => {
              console.log(error.response)
        })          
      },
      
      accountCheckSave(fl) {
        var rc=0
        
        rc = this.check_required()
        if(rc>0)
            return
            
        this.accountSave(fl)
      }
      

  },



	template: `<div><navigation></navigation><h3>Редактирование реквизитов контрагента</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
<div class="container">
  
  <br /> 
  <div v-if="role=='admin' && counterparty_id!=1"   class="mb-2 row">
  <label for="input_type" class="form-label col-sm-2">Роль</label>
  <select  v-model="c_type"  class="form-select col" id="input_type" >
    <option value="0">Заказчик</option>
    <option value="1">Учебный центр</option>
  </select>
    <div class="col-2">  </div>
  <label for="input_entity" class="form-label col-sm-3" style="text-align: center;" >Хозяйствующий субъект</label>
  <select  v-model="entity"  class="form-select col" id="input_entity" >
    <option value="0"> </option>
    <option value="1">Предприятие</option>
    <option value="2">Индивидуальный предприниматель</option>
    <!--<option value="3">Частное лицо</option>-->
  </select>
  </div>
  <br />

  <div class="mb-2 row">	
     <label for="input_logo" class="form-label col-sm-2" > Логотип </label>
     <div class="col-1">
        <p>__</p>
     </div>
     <input v-model="logo"  class="form-control col" id="input_logo"    type="file"     @change="handleLogoUpload()" >
  </div>
  

  <div class="mb-2 row">	
  <label for="input_name" class="form-label col-sm-2">Полное название</label>
    <input v-model="name"   class="form-control col" id="input_name"   type="text" @change="check_unique()" >
    <div class="col-1" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

  <div class="mb-2 row">
  <label for="input_shortname" class="form-label col-sm-2">Краткое название</label>
    <input v-model="shortname"   class="form-control col" id="input_shortname"   type="text"  @change="check_unique()" >
    <div class="col-2" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

<!--  <div class="mb-2">	
      <label for="input_login" class="form-label">login</label>
      <input v-model="login"   class="form-control" id="input_login"   type="text"  >
  </div>  <hr />-->



   <div class="mb-2 row">
   <label for="input_inn" class="form-label col-sm-2">ИНН</label>
     <input v-model="inn"   class="form-control col" id="input_inn"   type="text"  @change="check_unique()" >
     <div class="col" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
   </div>


   <div class="mb-2 row">
   <label for="input_kpp" class="form-label col-sm-2">КПП</label>
     <input v-model="kpp"   class="form-control col" id="input_kpp"   type="text"  >
     <div class="col"> </div>
   </div>


   <div class="mb-2 row">
   <label for="input_ogrn" class="form-label col-sm-2"><span v-if="entity==2">ОГРНИП</span><span v-else>ОГРН</span></label>
     <input v-model="ogrn"   class="form-control col" id="input_ogrn"   type="text"  >
     <div class="col"> </div>
   </div>

  <div class="mb-2 row">
  <label for="input_addres1" class="form-label col-sm-2">Юридический адрес</label>
    <textarea v-model="addres1"   class="form-control col" id="input_addres1" ></textarea>
  </div>

  <div class="mb-2 row">
  <label for="input_addres2" class="form-label col-sm-2">Фактический адрес</label>
    <textarea v-model="addres2"   class="form-control col" id="input_addres2" ></textarea>
  </div>

  <div class="mb-2 row">	
    <label for="input_email1" class="form-label col-sm-2">Официальный email</label>
    <input v-model="email1"  class="form-control col" id="input_email1"    type="text"   @change="check_unique()" >
    <div class="col-4" style="color: red"> <a title="Обязательное для заполнения поле"><b>*</b></a> </div>
  </div>

  <div class="mb-2 row">
  <label for="input_phone1" class="form-label col-sm-2"> Телефон</label>
    <input v-model="phone1"   class="form-control col" id="input_phone1"   type="text"  >
    <div class="col-4">  </div>
  </div>

 <hr />

  <div class="mb-2 row">
  <label for="input_bank" class="form-label col-sm-1">Банк</label>
    <textarea v-model="bank"   class="form-control col" id="input_bank"  ></textarea>
  </div>

  <div class="mb-2 row">
  <label for="input_rs" class="form-label col-sm-1">Р/c</label>
    <input v-model="checking_account"   class="form-control col" id="input_rs"   type="text"  >
    <div class="col-2">  </div>
  </div>

  <div class="mb-2 row">
  <label for="input_ks" class="form-label col-sm-1">К/c</label>
    <input v-model="correspondent_account"   class="form-control col" id="input_ks"   type="text"  >
    <div class="col-2">  </div>
  </div>

  <div class="mb-2 row">
  <label for="input_bik" class="form-label col-sm-1">БИК</label>
    <input v-model="bik"   class="form-control col" id="input_bik"   type="text"  >
    <div class="col-2">  </div>
  </div>

 <span v-if="entity != 0">
  <hr />

  <div class="mb-2 row">
  <label class="form-label col-sm-3"></label>
    <div class="col"><b>В именительном падеже</b></div>
    <div class="col"><b>В родительном падеже</b></div>
  </div>

  <div class="mb-2 row">
  <label for="input_position_head" class="form-label col-sm-3">Должность руководителя</label>
    <input v-model="position_head"   class="form-control col" id="input_position_head"   type="text"  >
     <div class="col"><input v-model="position_head2"   class="form-control col" id="input_position_head2"   type="text"  ></div>
  </div>


  <div class="mb-2 row">
  <label for="input_enterprise_manager" class="form-label col-sm-3">Руководитель</label>
    <input v-model="enterprise_manager"   class="form-control col" id="input_enterprise_manager"   type="text"  >
     <div class="col"><input v-model="enterprise_manager2"   class="form-control col" id="input_enterprise_manager2"   type="text"  ></div>
  </div>
 </span>

 <sapan v-if="counterparty_id!=1">  
  <hr />
<!--  <div class="mb-2 row">
  <label for="input_contact" class="form-label col-sm-3">Контактное лицо</label>
    <input v-model="contact"   class="form-control col" id="contact"   type="text"  >
     <div class="col-1"></div>
  </div>-->

  <div class="mb-2 row">	
    <label for="input_contact_email" class="form-label col-sm-3"> Email</label>
    <input v-model="email_contact"  class="form-control col" id="input_contact_email"    type="text"  >
     <div class="col-4"></div>
  </div>

  <div class="mb-2 row">
  <label for="input_phone_contact" class="form-label col-sm-3"> Телефон</label>
    <input v-model="phone_contact"   class="form-control col" id="input_phone_contact"   type="text"  >
     <div class="col-4"></div>
  </div>
  </sapan>
  
 <sapan v-if="role == 'admin'  && counterparty_id!=1">  
  <hr />
  
  <div class="mb-2 row">
        <div class="mb-2 col-sm-2">
        </div>
        <div class="mb-2 col-8">
            <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="inlineCheckboxContract" v-model="longtime_contract">
            <label class="form-check-label" for="inlineCheckboxContract"> долгосрочный договор </label>&nbsp;&nbsp;
            </div>
        </div>
  </div>

  
  <div class="mb-2 row">	
  <label for="input_comment" class="form-label col-sm-2">Комментарий</label>
    <textarea v-model="comment"   class="form-control col" id="input_comment" ></textarea>
  </div>
 </sapan> 

 <sapan v-if="counterparty_id!=1">  
  <br />
    <div class="accordion accordion-flush" id="accordionFlushExample" style="--bs-accordion-bg: #efefef;">
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
             Сменить пароль личного кабинета
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">


  <div v-if="role=='counterparty'" class="mb-2 row">	
      <label for="input_password0" class="form-label col-sm-3">Текущий пароль</label>
      <input v-model="password0"  class="form-control col" id="input_password0"    type="password"  >
      <div class="col-3">  </div>
   </div>

  <div class="mb-2 row">	
      <label for="input_password1" class="form-label col-sm-3">Новый пароль</label>
      <input v-model="password1"  class="form-control col" id="input_password1"    type="password"  >
      <div class="col-3">  </div>
   </div>

  <div class="mb-2 row">	
      <label for="input_password2" class="form-label col-sm-3">Новый пароль повторно</label>
      <input v-model="password2"  class="form-control col" id="input_password2"    type="password"  >
      <div class="col-3">  </div>
   </div>

   </div>
   </div>
   </div>
  <br />
</sapan> 



  <br />
  <div class="mb-2">	
    <div align="right">
    <button v-if="counterparty_id>1 && role=='admin' && c_type==0"  class="btn btn-success"    @click="accountCheckSave(1)"> Создать личный кабинет заказчика </button>
    &nbsp<button v-if="counterparty_id>0"  class="btn btn-primary"    @click="accountCheckSave(0)"> Сохранить </button>

    <button v-if="counterparty_id==0 && role=='admin' && c_type==0  && is_make==0"  class="btn btn-success"    @click="accountCheckCreate(1)"> Создать учетную запись и личный кабинет заказчика </button>
    &nbsp<button v-if="counterparty_id==0 && role=='admin' && is_make>0 "  class="btn btn-success"    @click="accountCheckCreate(0)"> Создать учетную запись <i class="fa-solid fa-circle-right"></i> </button>
    &nbsp<button v-if="counterparty_id==0 && role=='admin' && is_make<=0"  class="btn btn-success"    @click="accountCheckCreate(0)"> Создать учетную запись </button>
      
      &nbsp<router-link  v-if="role!='counterparty' && counterparty_id>1"  to="/counterparty_list" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
      <router-link v-else-if="is_make>0" to="/#/order_edit/0/0" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
      <router-link v-else to="/#" ><button  class="btn btn-outline-primary">Отмена</button></router-link>  <!--  { name: 'order_edit', params: { orderid: 0,  counterpartyid: this.account_id }}  -->
    </div>
  </div>
 </div>




</div>
	</div>`

};




