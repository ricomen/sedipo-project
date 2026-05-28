var GroupUser = {
  data: function () {
    return {
      group_id: 0,
      info: [],
      info2: [],
      info3: [],
      info9: [],
      role: '',
      message: '',
      lastname: '',
      firstname: '',
      middlename: '',
      organization_id: 0,
      position: '',
      subdivision: '',
      date_of_birth: '',
      view_link_button: 0,
      user2_id: 0,
      lastname2: '',
      firstname2: '',
      middlename2: '',
      organization2: '',
      position2: '',
      subdivision2: '',
      date_of_birth2: '',
      user_single: 0,
     }
   },

   mounted() {
//    updated() {
    this.group_id = this.$route.params.groupid
    this.role = session_t.role

    axios
      .post(JsonApiURL+'api/users_json.php', {organizations: {}})
      .then(response => { 
            this.info2 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })

  },


  methods: {

        UserSearch () {
	    axios
            .post(JsonApiURL+'api/users_json.php', {user_search: { groupId: this.group_id, lastname: this.lastname, firstname: this.firstname, middlename: this.middlename, organizationId: this.organization_id, subdivision: this.subdivision, position: this.position, date_of_birth: this.date_of_birth,  email:"", login:"" } })
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                this.info9 = response.data;
            	if(this.info9.userInfo.userId > 0){
            	    this.view_link_button = 1;
            	    this.user2_id = this.info9.userInfo.userId;
            	    this.lastname2 = this.info9.userInfo.lastname;
            	    this.firstname2 = this.info9.userInfo.firstname;
            	    this.middlename2 = this.info9.userInfo.middlename;
    		    this.organization2 = this.info9.userInfo.organization;
    		    this.position2 = this.info9.userInfo.position;
    		    this.subdivision2 = this.info9.userInfo.subdivision;
    		    this.date_of_birth2 = this.info9.userInfo.date_of_birth;
		    this.user_single = this.info9.userSingle;
                 }
                else
            	    this.view_link_button = 0;

                   //this.$router.push({ path: '/groupitems'}) 
              }
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })
        },


        UserLink () {
	    axios
            .post(JsonApiURL+'api/api_json.php', {user_link: {groupId: this.group_id, userId: this.user2_id} })
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                   this.$router.push({ name: 'groupitems', params: {groupid: this.group_id }})
              }
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })
        },


  },



	template: `<div><navigation></navigation><h3>Добавить существующую учетную запись в группу</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

{{info9}}
<div align="left">

<div class="container">
  <div class="row">
    <div class="col">

  <div class="mb-2">	
      <label for="input_organization" class="form-label">Организация  </label>
	<select  v-model="organization_id"  id=="input_organization" class="form-select" aria-label="Организация"  >
	<option value="0"> -  Организация  - </option>
        <option v-for="item2 in info2.list" :value="item2.organizationId">
	    {{item2.name.substring(0, 70)}} 
	</option>
	</select>
  </div>


  <div class="mb-2">	
  <label for="input_lastname" class="form-label">Фамилия</label>
    <input v-model="lastname"   class="form-control" id="input_lastname"   type="text" @input="UserSearch()" >
  </div>

  <div class="mb-2">	
    <label for="input_firstname" class="form-label">Имя</label>
      <input v-model="firstname"  class="form-control" id="input_firstname"    type="text" @input="UserSearch()" >
  </div>

  <div class="mb-2">	
    <label for="input_middlename" class="form-label">Отчество</label>
     <input v-model="middlename"  class="form-control" id="input_middlename"    type="text"  @input="UserSearch()"  >
  </div>

  <div class="mb-2">	
    <label for="input_subdivision" class="form-label">Подразделение</label>
     <input v-model="subdivision"  class="form-control" id="input_subdivision"    type="text"  @input="UserSearch()"  >
  </div>

  <div class="mb-2">	
    <label for="input_position" class="form-label">Должность</label>
     <input v-model="position"  class="form-control" id="input_position"    type="text"  @input="UserSearch()"  >
  </div>

  <!--<div class="mb-2">	
    <label for="input_date_of_birth" class="form-label">Ключевое слово</label>
     <input v-model="date_of_birth"  class="form-control" id="input_date_of_birth"    type="text"  @input="UserSearch()"  >
  </div>-->




 </div>
 <div class="col">

     <span v-if="view_link_button >0 ">
	<br />
	<p>Фамилия: <i>{{lastname2}}</i></p>
	<p>Имя: <i>{{firstname2}}</i></p>
	<p>Отчество: <i>{{middlename2}}</i></p>
    	<p>Организация: <i>{{organization2}}</i></p>
    	<p>Подразделение: <i>{{subdivision2}}</i></p>
    	<p>Должность: <i>{{position2}}</i></p>
    	<p>Ключевое слово: <i>{{date_of_birth2}}</i></p>
	<span v-if="user_single"> 
	 <div align="right">
           <button class="btn btn-warning"  type="primary"  @click="UserLink()"> Использовать существующую учетную запись </button>
	 </div>
	</span>
	<span v-else>
	    <p><i class="fas fa-ellipsis-v"></i><p>
	</span>
     </span>

    <br />
    <div align="right">
       <router-link  :to="{ name: 'userdit', params: { userid: 0, groupid: group_id, lastname: lastname, firstname: firstname, middlename: middlename  }}"   title="Создать новую учетную запись" ><button class="btn btn-primary"  type="primary"  > Создать новую учетную запись </button></router-link>
    &nbsp<router-link class="nav-link" :to="{ name: 'groupitems', params: {groupid: group_id  }}"><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>

 </div>
 </div>

</div>
	</div>`

};




