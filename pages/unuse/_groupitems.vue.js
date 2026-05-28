var GroupItems = {
  data: function () {
    return {
      group_id: 0,
      info: [],
      info3: [],
      info5: [],
      role: '',
      name: '',
      status: '',
      date_begin: '',
      date_end: '',
      counterparty_id: 0,
      message: ''
     }
   },


   mounted() {
    this.group_id = this.$route.params.groupid

    if(this.group_id>0) { 
        axios
    	    .post(JsonApiURL+'api/groups_json.php', {object: {objectId: this.group_id, sessionId: session_t.sessionId } })
            .then(response => { 
        	this.info = response.data
		this.name = this.info.result.name
        	this.counterparty_id  = this.info.result.organization_id
                this.status = this.info.result.status
                this.date_begin = this.info.result.date_begin
                this.date_end = this.info.result.date_end

                if(this.counterparty_id > 0) {
    	            axios
    		     .post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
    		     .then(response3 => { 
        	         console.log(response3)
        	         this.info3 = response3.data
    		     })
    		     .catch(error => {
            	         console.log(error.response)
        	     })
                }

    	        axios
    		    .post(JsonApiURL+'api/groups_json.php', {items: {groupId: this.group_id, sessionId: session_t.sessionId } })
    		    .then(response5 => { 
        	         console.log(response5)
        	         this.info5 = response5.data
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


  methods: {

    userUnlink(userId, name ){
	var fl = confirm('Исключить: ' + name + '?');
	if(fl) {
	axios
          .post(JsonApiURL+'api/groups_json.php', {item_del: {groupId: this.group_id, userId: userId, sessionId: session_t.sessionId }})
          .then(response => { 
            //this.info = response.data

    	        axios
    		    .post(JsonApiURL+'api/groups_json.php', {items: {groupId: this.group_id, sessionId: session_t.sessionId } })
    		    .then(response5 => { 
        	         console.log(response5)
        	         this.info5 = response5.data
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


	template: `<div><navigation></navigation><h3>Список группы</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <h4  v-if="counterparty_id>0"   style="text-align: right;" > {{info3.result.name}} </h4>

 <div class="row justify-content-between">
    <div class="col-8">
  <div align="left">
  <h5>{{name}}</h5>
  </div>
    </div>
    <div class="col-4">
  <div align="right">
    <router-link  :to="{ name: 'groupimport', params: { userid: 0 }}"  class="nav-link"   title="Импорт списка" >  <button  class="btn btn-primary btn-sm"> Импорт списка </button></router-link>
  </div>
    </div>
  </div>

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <th scope="col"> Организация </th>
          <th scope="col"> Подразделение </th>
          <th scope="col"> Должность </th>
          <th scope="col" align="center"> <center>Документы</center> </th>
          <th scope="col" width="10%"><div style="float: right"><router-link  :to="{ name: 'groupuser', params: { userid: 0 }}"   title="Добавить существующую учетную запись" ><b style=" font-size: larger;"><i class="fa-solid fa-user-check"></i></router-link ></div></th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info5.list"  align="left">  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td>{{item.organization}}</td>
            <td>{{item.subdivision}}</td>
            <td>{{item.position}}</td>

            <td align="center"> <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;"><td style="padding-right: 5px;">
		<router-link    :to="{ name: 'student_report', params: { userid: item.userId }}"   title="Документы об обучении" ><i class="fa-solid fa-file-circle-check"></i></router-link >
	    </td></tr></table> </td>

            <td> <div style="float: right">
		<table border="0"  width="100%"><tr><td style="padding-left: px; padding-right: 5px;">
		<a  @click="userUnlink(item.userId, item.lastname + ' ' + item.firstname + '...')"   title="Исключить учетную запись из списка группы" style="color: red;" ><i class="fa-solid fa-user-minus"></i></a>
		</td><td style="padding-left: 5px; padding-right: 0px;">
		<router-link    :to="{ name: 'student_edit', params: { userid: item.userId, counterpartyid: this.counterparty_id, groupid: this.group_id}}"   title="Редактировать учетную запись" ><i class="fas fa-edit"></i></router-link >
		</td></tr></table>
	    </div></td>
          </tr>
  </tbody>
  </table>

  <div align="right">
    <router-link  :to="{ name: 'groups_list', params: {counterpartyid: this.counterparty_id}}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
  </div>

	</div>`

};




