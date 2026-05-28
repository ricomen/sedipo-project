var LstreamItems = {
  data: function () {
    return {
      IS_SUBDIVISION: 0,
      role: '',
      lstream_id: 0,
      a_order_id: 0,
      info: [],
      info3: [],
      info5: [],
      name: '',
      status: '',
      date_begin: '',
      date_end: '',
      counterparty_id: 0,
      course_name: '',
      order_id: 0,
      message: ''
     }
   },


   mounted() {
    this.lstream_id = Number(this.$route.params.lstreamid)


    if(this.lstream_id>0) { 
    	   axios
    		    .post(JsonApiURL+'api/lstream_json.php', {items: {objectId: this.lstream_id, sessionId: session_t.sessionId } })
    		    .then(response5 => { 
        	         console.log(response5)
        	         this.info5 = response5.data
                     this.role = this.info5.role
		             this.name = this.info5.result.name
		             this.course_name = this.info5.result.course_name
		             
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
    		    .post(JsonApiURL+'api/groups_json.php', {items: {objectId: this.cohort_id, sessionId: session_t.sessionId } })
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


	template: `<div><navigation></navigation><h3>Список учебного потока {{name}}</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
  <h4 style="text-align: center; color: red;">{{message}}</h4>

 <div class="row justify-content-between">
    <div class="col-8">
    <div align="left">
        <h5>{{course_name}}</h5>
    </div>
</div>

    <!--<div class="col-4">
        <div  v-if="role!='counterparty'"  align="right">
        <router-link  :to="{ name: 'groupimport', params: { userid: 0 }}"  class="nav-link"   title="Импорт списка" >  <button  class="btn btn-primary btn-sm"> Импорт списка </button></router-link>
    </div>
    </div>-->
  </div>

    <br />
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <th scope="col"> Организация </th>
          <th v-if="IS_SUBDIVISION" scope="col"> Подразделение </th>
          <th scope="col"> Должность </th>
          <!--<th scope="col" align="center"> <center>Удостоверения&nbsp;/ <br />Сертификаты </center> </th>-->
          <th scope="col" width="10%"><!--<div  v-if="role!='counterparty'"  style="float: right"><router-link  :to="{ name: 'groupuser', params: { userid: 0 }}"   title="Добавить существующую учетную запись" ><b style=" font-size: larger;"><i class="fa-solid fa-user-check"></i></router-link ></div>--></th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info5.list"  align="left">  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td>{{item.organization_name}}</td>
            <td v-if="IS_SUBDIVISION">{{item.subdivision}}</td>
            <td>{{item.position}}</td>

            <!--<td align="center"> <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;"><td style="padding-right: 5px;">
              <span v-for="item2 in info5.course_list"> <div class="row">
            	<div class="dropdown">
                <button class="btn btn-link dropdown-toggle dropdown-toggle-split" href="" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;"><i class="fa-solid fa-folder-open"></i>&nbsp;</button>
	            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="--bs-dropdown-min-width: 30rem;">
	                <li v-if="role!='counterparty' || this.info.result.status_id>=10" ><a :href="'d-api/documents-cert.php?template='+item2.certificate_temlate+'&id='+this.order_id+'&userid='+item.userId+'&courseid='+item2.course_id+'&file='+item2.certificate_name+'-'+item.lastname+'_'+item.firstname+'_'+item.middlename+'-'+item2.shortname" target="_blank"  class="nav-link" title="документ об обраовании" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> {{item2.certificate_name}} </a></li>
	                <li v-if="role!='counterparty' || this.info.result.status_id>=10" ><span v-if="item2.certificate_a1_name!=''" ><a :href="'d-api/documents-cert.php?template='+item2.certificate_a1_temlate+'&id='+this.order_id+'&userid='+item.userId+'&courseid='+item2.course_id+'&file='+item2.certificate_a1_name+'-'+item.lastname+'_'+item.firstname+'_'+item.middlename+'-'+item2.shortname" target="_blank"  class="nav-link" title="документ об обраовании" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> {{item2.certificate_a1_name}} </a></span></li>
	            </ul>
	            </div>
              </span>	            
	        </td></tr></table> </td>-->

            <td  > <div style="float: right">
		        <table border="0"  width="100%"><tr>
		        <td style="padding-left: px; padding-right: 5px;">
		            <!--<a  v-if="role!='counterparty'"  @click="userUnlink(item.userId, item.lastname + ' ' + item.firstname + '...')"   title="Исключить учетную запись из списка группы" style="color: red;" ><i class="fa-solid fa-user-minus"></i></a>-->
		        </td><td style="padding-left: 5px; padding-right: 0px;">
		            <router-link    :to="{ name: 'student_edit', params: { userid: item.userId, counterpartyid: this.counterparty_id, groupid: this.group_id}}"   title="Редактировать учетную запись" ><i class="fas fa-edit"></i></router-link >
		        </td></tr></table>
	    </div></td>
          </tr>
  </tbody>
  </table>

  <div align="right">
    <router-link  :to="{ name: 'lstream_list', params: {}}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
  </div>

	</div>`

};




