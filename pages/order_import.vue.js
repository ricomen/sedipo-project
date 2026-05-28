var OrderImport = {
  data: function () {
    return {
      info: [],
      message: '',
      order_id: 0,
      a_counterparty_id: 0, 
      importfile: '',
      filename: '',
      file_type: '',
      email_mode: 1

     }
   },

   mounted() {
//    updated() {
         this.order_id = Number(this.$route.params.orderid)
         this.a_counterparty_id = Number(this.$route.params.counterpartyid)

  },


  methods: {
        orderSave () {
            this.wait = 1;
            const formData = new FormData();
            formData.append('csvfile', this.importfile);
            formData.append('order_id', this.order_id);
            formData.append('email_mode', this.email_mode);
            formData.append('sessionId', session_t.sessionId); 

	    axios
	    .post(JsonApiURL+'api/import_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
              this.info = response.data
              console.log(response)

              if(response.data.status==0) {
                   this.$router.push({ name: 'order_items', params: { orderid: this.order_id, counterpartyid: this.a_counterparty_id  }}) 
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



	template: `<div><navigation></navigation><h3>Импорт списка </h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>


<div align="left">

<div class="container">

  <p> Возможный формат файла:</p>
  <ul>
   <li> .xls, <a href="/docs/order-list.xls">Шаблон файла импорта .xls</a></li>
   <li>  .csv в кодировке UTF-8, с разделителем полей ";", без символов ограничения строк, <a  href="/docs/order-list.csv">Шаблон файла импорта .csv</a></li>
  </ul>
  <hr />

  <div class="mb-2">	
  <label for="input_list" class="form-label">Список слушателей</label>
    <input class="form-control"  type="file" name="importfile" id="importfile"  @change="handleFileUpload">
  </div>
  <select v-model="email_mode">
    <option value="1"> Испрльзовать email из файла </option>
    <option value="0"> Создавать email на локальном домене </option>
  </select>

  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="orderSave()"> Загрузить </button>
    &nbsp<router-link :to="{ name: 'order_items', params: { orderid: order_id, counterpartyid: a_counterparty_id  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>



 </div>

</div>
	</div>`

};




