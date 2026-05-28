var AccountsList  = {
  data: function () {
    return {
      info: [],
      role: '',
      message: '',
      namesearch: '',
     }
   },


   mounted() {
//    updated() {
    this.role = session_t.role
//  v-if="role=='admin'" 

    axios
      .post(JsonApiURL+'account_json.php', {accounts_list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
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
          .post(JsonApiURL+'account_json.php', {account_del: {accountId: argId}})
          .then(response => { 
            //this.info9 = response.data
        })
          .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'account_json.php', {accounts_list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
       })
      .catch(error => {
              console.log(error.response)
            })
     }

   }

},


    template: `<div><navigation></navigation><h3>Аккаунты администраторов</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Логин  </th>
          <th scope="col"> Права доступа  </th>
          <th scope="col"> <div style="float: right"><router-link  :to="{ name: 'accountedit', params: { accountid:0 }}"   title="Добавить Аккаунт" ><b style=" font-size: larger;"><i class="far fa-plus-square"></i></b></router-link ></div>  </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left">  
            <td>{{item.login}}</td>
            <td>{{item.role}}</td>
            <td> <div style="float: right">
	<table border="0"><tr><td>
	<router-link  class="nav-link" :to="{ name: 'accountedit', params: { accountid: item.accountId }}"   title="Редактировать" ><i class="fas fa-edit"></i></router-link >
	</td><td>
	<a  @click="argDelete(item.accountId, item.login)"   title="Удалить" style="color: red;" ><i class="far fa-trash-alt"></i></a>
	</td></tr></table>
        </div></td>
          </tr>
  </tbody>
  </table>

    </div>`


};


