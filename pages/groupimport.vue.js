var GroupImport = {
  data: function () {
    return {
      info: [],
      message: '',
      group_id: 0,
      a_facultet_id: 0,
      a_form_id: 0,
      importfile: '',
      filename: '',
      file_type: '',
      email_mode: 0

     }
   },

   mounted() {
//    updated() {
         this.group_id = Number(this.$route.params.groupid)
         this.a_facultet_id = Number(this.$route.params.facultetid)
         this.a_form_id = Number(this.$route.params.formid)

  },


  methods: {
        groupSave () {
            this.wait = 1;
            const formData = new FormData();
            formData.append('csvfile', this.importfile);
            formData.append('group_id', this.group_id);

	    axios
	    .post(JsonApiURL+'import_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                   this.$router.push({ name: 'groupitems', params: { groupid: this.group_id }} ) 
              }
              else {
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



	template: `<div><navigation></navigation><h3>Импорт списка группы</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

      {{filename}}
      {{file_type}}


<div align="left">

<div class="container">

  <p> Возможный формат файла:</p>
  <ul>
   <li> .xls, <a href="/doc/group-list.xls">Шаблон файла импорта .xls</a></li>
  </ul>
  <hr />

  <div class="mb-2">	
  <label for="input_list" class="form-label">Список группы</label>
    <input class="form-control"  type="file" name="importfile" id="importfile"  @change="handleFileUpload">
  </div>
  <select v-model="email_mode">
    <option value="1"> Испрльзовать email из файла </option>
    <option value="0"> Создавать email на локальном домене </option>
  </select>

  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="groupSave()"> Загрузить </button>
    &nbsp<span ><router-link :to="{ name: 'groupitems', params: { groupid: group_id }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link></span>
    </div>
  </div>
 </div>



 </div>

</div>
	</div>`

};




