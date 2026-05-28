var List = { 
  data: function () {
    return {
      list: [],
      name: '',
      hours: '',
      form: '',
      message: ''
     }
   },



   mounted() {
//    updated() {

    axios
      .post(JsonApiURL+'api_json.php', {rprg_list: {search: 0, page: 1,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.list = response.data.list
       })
      .catch(error => {
              console.log(error.response)
            })
  },


  methods: {
        rprgDel(id, name) {
	var fl = confirm('Удалить: ' + name + '?');
	if(fl) {
	    axios
            .post(JsonApiURL+'api_json.php', {rprg_delete: {Id: id,   sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

            })
            .catch(error => {
              console.log(error.response)
            })

           }
        

    axios
      .post(JsonApiURL+'api_json.php', {rprg_list: {search: 0, page: 1,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.list = response.data.list
       })
      .catch(error => {
              console.log(error.response)
            })

      }

  },




	template: `<div><navigation></navigation> <h3>Список программ</h3>
<div align="left">

<p><router-link  :to="{ name: 'new'}"><button class="green">+ Новая программа</button></router-link> </p>

<ul class="list-ul">
    <li v-for="item in list"   ><router-link  :to="{ name: 'edit', params: {id: item.Id }}"> {{item.name}}</router-link>,  {{item.hours}} часов: {{item.form}} <button class="red" @click="rprgDel(item.Id, item.name)"> Удалить </button>
       <a :href="'export.php?id='+item.Id" target="_blank"><button class="pred"> XML </button></a>
    </li>

</ul>



</div></div>`
};