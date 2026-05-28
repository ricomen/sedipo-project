var JobTitleList = {
  data: function () {
    return {
      info: [],
      message: '',
      namesearch: '',
     }
   },


   mounted() {
//    updated() {
    axios
      .post(JsonApiURL+'api_json.php', {positions: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })
  },


  methods: {
    search_go() {
    axios
      .post(JsonApiURL+'api_json.php', {positions: {search: 1, name: this.namesearch, sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })
    },


    positionDelete(positionId, name ){
	var fl = confirm('Удалить  запись: ' + name + '?');
	if(fl) {
	axios
          .post(JsonApiURL+'api_json.php', {position_del: {positionId: positionId}})
          .then(response => { 
            //this.info9 = response.data
        })
          .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'api_json.php', {positions: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })
     }

   }



},




	template: `<div><navigation></navigation><h3>Должности</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <table width="100%">
        <tr  align="left">
            <td><input type="text" v-model="namesearch" placeholder="Должность"   size="20"></td>
	    <td width="15%"> </td>
            <td align="left"> <button  @click="search_go()">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button></td>
	    <td width="15%"> </td>
            <td align="right"> <button  title="Сбросить все фильтры"   ><i class="fa-solid fa-text-slash"></i> </button> </td>
        </tr>
    </table>
    <hr />

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Должность </th>
          <th scope="col"> Компетенция </th>
          <th scope="col"> Соответствие </th>
          <th scope="col"> <div style="float: right"><router-link  class="nav-link" :to="{ name: 'positionedit', params: { positionid: 0 }}"   title="Добавить должность" ><b style=" font-size: larger;"><i class="far fa-plus-square"></i></b></router-link ></div></th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left">  
            <td>{{item.name}}</td>
            <td></td>
            <td></td>
            <td> <div style="float: right">
		<table border="0"><tr><td>
		<router-link  class="nav-link" :to="{ name: 'positionedit', params: { positionid: item.positionId }}"   title="Редактировать" ><i class="fas fa-edit"></i></router-link >
		</td><td>
		<a  @click="positionDelete(item.positionId, item.name)"   title="Удалить" style="color: red;" ><i class="far fa-trash-alt"></i></a>
		</td></tr></table>
	    </div></td>
          </tr>
  </tbody>
  </table>

	</div>`

};




