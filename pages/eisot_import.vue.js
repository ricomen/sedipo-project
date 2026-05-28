var EisotImport = {
  data: function () {
    return {
      info: [],
      info3: [],
      message: '',
      wait: 0,
      importfile: '',
      filename: '',
      file_type: '',
      import_total: 0,
     }
   },

   mounted() {

         if(this.a_counterparty_id > 0) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.a_counterparty_id, sessionId: session_t.sessionId } })
    		.then(response3 => { 
        	    console.log(response3)
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
         }

  },


  methods: {
        eisotSave () {
            this.wait = 1;
            this.import_total = 0;
            const formData = new FormData();
            if(this.importfile_file_type!=''){
               formData.append('upload1', this.importfile);
            }
            formData.append('name', this.filename)
            formData.append('type', this.file_type)
            formData.append('lstream_id', 0); 
            formData.append('sessionId', session_t.sessionId); 

	    axios
            .post(JsonApiURL+'reports/eisot_import_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
              this.info = response.data
              this.wait = 0;
              this.import_total = this.info.result.total 
              console.log(response)

              if(response.data.status==0) {
                    this.$router.push({ to: '/'}) 
              }
              else {
                    console.log(response)
                    this.message = response.data.error
              }

            })
            .catch(error => {
              console.log(error.response)
            })
        },


        handleFileUpload(){
            this.importfile = document.getElementById('importfile').files[0];
            this.filename = this.importfile.name;
            this.file_type = this.importfile.type;
            this.import_total = 0;
        } 

  },



	template: `<div><navigation></navigation><h3>Импорт номеров удостоверений из ЕИСОТ</h3>

  <h4 style="text-align: center; color: red;">{{message}}</h4>
  <br />
    <h4 v-if="role!='counterparty' && a_counterparty_id>0"  style="text-align: right;" > {{info3.result.name}} </h4>

<div align="left">

<div class="container">

  <div class="mb-2">	
  <label for="input_list" class="form-label">Файл номеров удостоверений из ЕИСОТ</label>
    <input class="form-control"  type="file" name="importfile" id="importfile"  @change="handleFileUpload"  accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" >
  </div>

  <div class="mb-2">	
    <span v-if="import_total>0"><p>Загружено: {{import_total}} записей</p></span>
    <div align="right">
       <span v-if="wait"><i class="fa-solid fa-cog fa-spin"></i></span>
       <span v-if="import_total==0 && file_type=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'"><button class="btn btn-primary"    @click="eisotSave()"> Загрузить </button></span>
       &nbsp<router-link  to="/"  ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
    </div>
  </div>
 </div>



 </div>

</div>
	</div>`

};




