var Template2Edit = {
  data: function () {
    return {
      role: '', 
      info: [],
      info2: [],
      info3: [],
      info9: [],
      message: '',
      template_id: 0,
      name: '',
      filename: '',
      type_id: '',
      size_page: '',
      num_of_page: 1,
      wysiwyg: 'true',
      template_html: '',
      footer_file: '',
      wait: 0,
      allow_wysiwyg: [0, 0, 1, 1, 0, 1, 1, 0],
     }
   },

   mounted() {
    this.template_id = Number(this.$route.params.templateid)

    if(this.template_id >0 )
         var templateId = this.template_id 

    if(templateId >0 ) {
        axios
            .post(JsonApiURL+'api/template_json.php', {object: {objectId: templateId, sessionId: session_t.sessionId } })
            .then(response => { 
                this.info = response.data
                this.role = this.info.role
                this.filename = this.info.result.file
                this.footer_file = this.info.result.footer_file
                this.type_id = this.info.result.type
                this.size_page = this.info.result.size_page
                this.num_of_page = this.info.result.num_of_page
                this.wysiwyg = this.info.result.wysiwyg
                this.name = this.info.result.name
 
                axios
                    .post(JsonApiURL+'api/template2_json.php', {footer_html: {objectId: templateId, sessionId: session_t.sessionId } })
                    .then(response2 => { 
                        this.info2 = response2.data
                        this.template_html = this.info2.result

          
                })
                  .catch(error => {
                      console.log(error.response)
                })

            })
            .catch(error => {
              console.log(error.response)
            })

          }

  },


  methods: {
        templateSave () {
          if(this.template_id >0){

              var text = document.getElementById("editor").value;


                        axios
                            .post(JsonApiURL+'api/template2_json.php', {footer_save: {objectId: this.template_id, template_html: text,  sessionId: session_t.sessionId } })
                            .then(response3 => {
                             console.log(response3)
                                //this.info9 = response3.data
                                this.$router.push({ name: 'template_list', params: { }})

                            })
                             .catch(error3 => {
                             console.log(error3.response)
                         })

           
          }
        },


      


},

	template: `<div><navigation></navigation><h3>Дополнительный блок шаблона</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
  <p>{{name}}</p>

  <textarea id="editor" name="editor" style="width:1290px; height:400px;"  >{{template_html}}</textarea>
  <br />


  <div class="mb-2">	
    <div align="right">
    <button v-if="template_id>0"  class="btn btn-primary"    @click="templateSave()"> Сохранить </button>
      &nbsp<router-link :to="{ name: 'template_list'}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>



</div>
	</div>`

};




