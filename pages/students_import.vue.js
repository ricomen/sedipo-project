var StudentsImport = {
  data: function () {
    return {
      info: [],
      info3: [],
      message: '',
      a_counterparty_id: 0,
      order_id: 0,
      item: 0,
      wait: 0,
      import_mode: 0,
      importfile: '',
      filename: '',
      file_type: '',

     }
   },

   mounted() {
         this.a_counterparty_id = Number(this.$route.params.counterpartyid)
         this.order_id = Number(this.$route.params.orderid)
         this.item = Number(this.$route.params.item)

         if(this.a_counterparty_id > 1) {
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
        listSave () {
            this.wait = 1;
            const formData = new FormData();
            formData.append('upload', this.importfile);
            formData.append('counterparty_id', this.a_counterparty_id);
            formData.append('order_id', this.order_id);
            formData.append('item', this.item);
            //formData.append('import_mode', this.email_mode);
            formData.append('sessionId', session_t.sessionId); 

	    axios
	    .post(JsonApiURL+'api/students_import_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
              this.info = response.data
              console.log(response)

              if(response.data.status==0) {
                  if(this.order_id>0)
                    this.$router.push({ name: 'order_items', params: { orderid: this.order_id, counterpartyid: this.a_counterparty_id  }}) 
                  else
                    this.$router.push({ name: 'students_list', params: { counterpartyid: this.a_counterparty_id  }}) 
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
        } 


  },



	template: `<div><navigation></navigation><h3>Импорт списка сотрудников / результатов обучения</h3>
	                  <h4 v-if="order_id>0">Импорт списка для заявки на обучение</h4> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>
  <br />
    <h4 v-if="role!='counterparty' && a_counterparty_id>0"  style="text-align: right;" > {{info3.result.name}} </h4>

<div align="left">

<div class="container">

  <p> Формат файла:  <b>.xlsx</b></p>
  <p>  <a href="/docs/users-list.xlsx">Шаблон файла импорта .xlsx</a></p>
  <hr />

  <div class="mb-2">	
  <label for="input_list" class="form-label">Список обучающихся</label>
    <input class="form-control"  type="file" name="importfile" id="importfile"  @change="handleFileUpload"   accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"  >
  </div>
  <select v-model="import_mode">
    <option value="0"> Загрузить список сотрудников и реультаты обучения </option>
    <option value="1"> Загрузить результаты обучения </option>
  </select>

  <br /><br />
  <p v-if="order_id>0"><i>Список сотрудников будет добавлен в заявку на обучение. Для сотрудников уже имеющих учетные записи будут использованы существующие. <br />Для вновь появившихся в списке сотрудников, будут созданы учетные записи в базе данных. </i></p>
  <p v-else><i>Для вновь появившиеся в списке сотрудников, будут созданы учетные записи в базе данных. <br />Для уже имеющихся учетных записей будут дополнены результаты обучения.</i></p>
  
  <div class="mb-2">	
    <div align="right">
    <button v-if="file_type=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'"  class="btn btn-primary"    @click="listSave()"> Загрузить </button>
    &nbsp<router-link v-if="order_id>0"  :to="{ name: 'order_items', params: { orderid: order_id, counterpartyid: a_counterparty_id  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
         <router-link v-else :to="{ name: 'students_list', params: { counterpartyid: a_counterparty_id  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    
    </div>
  </div>
 </div>



 </div>

</div>
	</div>`

};




