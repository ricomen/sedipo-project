var SelfList  = {
  data: function () {
    return {
      info: [],
      info9: [],
      role: '',
      message: '',
      namesearch: '',
      count: 0,
      max_id: 0,
     }
   },


   mounted() {
//    updated() {
    this.role = session_t.role
//  v-if="role=='admin'" 

    axios
      .post(JsonApiURL+'api/self_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.count = this.info.list.length
       })
      .catch(error => {
              console.log(error.response)
            }),

      axios
      .post(JsonApiURL+'api/self_json.php', {max_index_id: { sessionId: session_t.sessionId } })
      .then(response => { 
            this.info9 = response.data
            this.max_id = this.info9.result
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
          .post(JsonApiURL+'api/self_json.php', {delete: {objectId: argId}})
          .then(response => { 
            //this.info9 = response.data
	    axios
    	    .post(JsonApiURL+'api/self_json.php', {list: { sessionId: session_t.sessionId }})
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


    template: `<div><navigation></navigation><h3>Редактирование реквизитов по периодам</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <div  v-if="role!='counterparty'"   style="text-align: left; padding-top: 15px;"><button  class="btn btn-light" > <router-link  :to="{ name: 'self_edit', params: { editionid: 0, maxid: max_id }}"    title="Создать запись на  новый  период" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Создать запись на  новый  период </nobr></div></router-link >  </button></div>
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Дата начала периода</th>
          <th scope="col"><div style="float: right"> </div></th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="(item, index) in info.list"  align="left">  
            <td>{{item.edition}}&nbsp;&nbsp;<router-link   :to="{ name: 'self_edit', params: { editionid: item.edition_id, maxid: max_id }}"   title="Редактировать" ><i class="fa-solid fa-pencil"></i></router-link > </td>
            <td> <div style="float: right">
	            <table border="0"><tr>
	                <td style="padding-left: 0px; padding-right: 9px;">
	                </td>
	                <td  style="padding-left: 9px; padding-right: 0px;">
	                    <a v-if="index+1<count"   @click="argDelete(item.edition_id, item.edition)"   title="Удалить" style="color: red;" ><i class="far fa-trash-alt"></i></a>
	                </td>
	            </tr></table>
            </div></td>
        </tr>
  </tbody>
  </table>

    </div>`


};


