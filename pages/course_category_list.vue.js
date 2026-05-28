var CourseCategoryList  = {
  data: function () {
    return {
      info: [],
      role: '',
      message: '',
      namesearch: '',
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
          orders_list_buh: 0
        },
      is_DEVELOPMENT: IS_DEVELOPMENT,
     }
   },


   mounted() {
//    updated() {
    this.role = session_t.role
    this.role_privileges =  session_role_privileges
//  v-if="role=='admin'" 

    axios
      .post(JsonApiURL+'api/course_category_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
       })
      .catch(error => {
              console.log(error.response)
            })
  },


  methods: {

    argDelete(argId, name ){
    var fl = confirm('Удалить  запись: ' + name + '?');
    if(fl) {
    axios
          .post(JsonApiURL+'api/course_category_json.php', {delete: {objectId: argId}})
          .then(response => { 
            //this.info9 = response.data
	    axios
    	    .post(JsonApiURL+'api/course_category_json.php', {list: { sessionId: session_t.sessionId }})
    	    .then(response2 => { 
        	this.info = response2.data
    	    })
    	    .catch(error2 => {
              console.log(error2.response)
            })

        })
        .catch(error => {
              console.log(error.response)
        })
     }

   }

},


    template: `
    <container v-if="role_privileges.course_category_list != 0">
    <div><navigation></navigation><h3>Категории курсов</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <div  v-if="is_DEVELOPMENT==1"   style="text-align: left; padding-top: 15px;"><button  class="btn btn-light" > <router-link  :to="{ name: 'course_category_edit', params: { categoryid:0 }}"    title="Добавить категорию" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новая категория </nobr></div></router-link >  </button></div>
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Наименование категории  </th>
          <th scope="col"><div style="float: right"> </div></th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left">  
            <td>{{item.name}}&nbsp;&nbsp;<router-link  v-if="role_privileges.course_category_list == 2"   :to="{ name: 'course_category_edit', params: { categoryid: item.category_id }}"   title="Редактировать" ><i class="fa-solid fa-pencil"></i></router-link > </td>
            <td> <div style="float: right">
	            <table border="0"><tr>
	                <td style="padding-left: 0px; padding-right: 9px;">
	                </td>
	                <td  v-if="is_DEVELOPMENT==1" style="padding-left: 9px; padding-right: 0px;">
	                    <a  v-if="role_privileges.course_category_list == 2"  @click="argDelete(item.category_id, item.name)"   title="Удалить" style="color: red;" ><i class="far fa-trash-alt"></i></a>
	                </td>
	            </tr></table>
            </div></td>
        </tr>
  </tbody>
  </table>

    </div>
    </container>`


};


