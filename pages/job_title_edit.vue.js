var JobTitleEdit = {
  data: function () {
    return {
      info: [],
      message: '',
      name: '',
      position_id: 0,
     }
   },

   mounted() {
//    updated() {
    this.position_id = this.$route.params.positionid

    axios
      .post(JsonApiURL+'api_json.php', {position_detalies: {positionId: this.position_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
	    this.name = this.info.positionInfo.name

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })

  },


  methods: {
        positionSave () {

	    axios
            .post(JsonApiURL+'api_json.php', {position_save: {positionId: this.position_id, name: this.name,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                   this.$router.push({ path: '/positions'}) 
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



	template: `<div><navigation></navigation><h3>Редактирование записи </h3> 
  <h4 style="text-align: center; color: red;">{{message}}</h4>


<div align="left">

<div class="container">

  <div class="mb-2">	
  <label for="input_name" class="form-label">Название должности</label>
    <input v-model="name"   class="form-control" id="input_name"   type="text" >
  </div>



  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="positionSave()"> Сохранить </button>
    &nbsp<router-link to="/positions" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>



</div>
</div>
	</div>`

};




