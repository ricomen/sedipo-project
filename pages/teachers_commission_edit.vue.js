var TeachersCommissionEdit = {
  data: function () {
    return {
      role: '',
      info: [],
      info1: [],
      info2: [],
      info3: [],
      info4: [],
      info9: [],
      message: '',
      object_id: 0,
      name: '',
      edition: 0,
      n: [],
      teacher_id: [],
      fullname: [],
      job_title: [],
      sign: [],
      make_t: false,
      new_date: '',
      directive_num: '',
      sign_upload: [],
      uploadfile1: [],
      file1_name: [],
      file1_type: [],
      role_privileges: {
          accountedit: 0,
          self_list: 0,
          rolelist: 0,
          accountslist: 0,
          set_template_contract: 0,
          validity_period_counterparty_list: 0,
          course_category_list: 0,
          courses_list: 0,
          teachers_commission_list: 0,
          teacher_list: 0,
          template_list: 0,
          counterparty_list: 0,
          students_list: 0,
          orders_analytics: 0,
          orders_table: 0,
          stat_report: 0,
          groups_list: 0,
          lstream_list: 0,
          calendar: 0,
          eisot_import: 0,
          orders_list: 0,
          orders_list_buh: 0
        },
     }
   },

   mounted() {
    this.object_id = Number(this.$route.params.commissionid)
    this.copy_id = Number(this.$route.params.copyid)
    this.role_privileges = session_role_privileges
    if(isNaN(this.copy_id))
        this.copy_id = 0

    if(this.object_id > 0 || this.copy_id > 0 ){
       var objectId = this.object_id
       if(this.object_id == 0 && this.copy_id > 0 )
           objectId = this.copy_id


       axios
         .post(JsonApiURL+'api/teachers_commission_json.php', {object: {objectId: objectId, sessionId: session_t.sessionId } })
         .then(response => { 
            this.info = response.data
             this.role = this.info.role
            this.name = this.info.result.name
            if(this.copy_id > 0 )
                this.name = this.name + '-копия'

          })
         .catch(error => {
              console.log(error.response)
         })

       conditions =  {"commission_id": objectId}
       axios
         .post(JsonApiURL+'api/teachers_commission_edition_json.php', {list: {conditions, sessionId: session_t.sessionId } })
         .then(response => { 
            this.info2 = response.data

          })
         .catch(error => {
              console.log(error.response)
          })


    }
    else {
       axios
          .post(JsonApiURL+'api/auth_json.php', {info: { sessionId: session_t.sessionId } })
          .then(response => { 
            this.info9 = response.data
             this.role = this.info9.role
        })
          .catch(error => {
              console.log(error.response)
        })

        this.n.push(0) 
        this.n.push(1) 
        this.n.push(2) 
        this.teacher_id.push(0)
        this.fullname.push('') 
        this.job_title.push('') 
        this.sign.push('') 
        this.teacher_id.push(0)
        this.fullname.push('') 
        this.job_title.push('') 
        this.sign.push('') 
        this.teacher_id.push(0)
        this.fullname.push('') 
        this.job_title.push('') 
        this.sign.push('') 
    }
    
  },


  methods: {

        objectLoad() {
           conditions =  {"edition_id": this.edition}
           this.make_t = false

           this.n = []
           this.teacher_id = []
           this.fullname = []
           this.job_title = []
           this.sign = []

           axios
             .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {list: {conditions, sessionId: session_t.sessionId } })
             .then(response => { 
                 this.info1 = response.data
                 for( var i=0; i<this.info1.list.length; i++ ) {
                    this.n.push(this.info1.list[i].n) 
                    this.teacher_id.push(this.info1.list[i].teacher_id) 
                    this.fullname.push(this.info1.list[i].fullname) 
                    this.job_title.push(this.info1.list[i].job_title) 
                    this.sign.push(this.info1.list[i].sign) 
                 }
                 if(this.info1.list.length>0){
                      this.n.push(this.info1.list[this.info1.list.length-1].n+1)
                 }
                 else{
                   this.n.push(0) 
                   this.n.push(1) 
                   this.n.push(2) 
                   this.teacher_id.push(0)
                   this.fullname.push('') 
                   this.job_title.push('') 
                   this.sign.push('') 
                   this.teacher_id.push(0)
                   this.fullname.push('') 
                   this.job_title.push('') 
                   this.sign.push('') 
                }
                this.teacher_id.push(0)
                this.fullname.push('') 
                this.job_title.push('') 
                this.sign.push('') 

            })
           .catch(error => {
              console.log(error.response)
            })


            axios
               .post(JsonApiURL+'api/teachers_commission_edition_json.php', {object: {objectId: this.edition, sessionId: session_t.sessionId } })
               .then(response => { 
                  this.info4 = response.data
                  this.directive_num = this.info4.result.directive_num
             })
             .catch(error => {
                  console.log(error.response)
             })

     },



        objectSave() {

           axios
            .post(JsonApiURL+'api/teachers_commission_json.php', {update: {objectId: this.object_id,  name: this.name,    sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
              //this.info9 = response.data

            })
            .catch(error => {
              console.log(error.response)
            })


           axios
            .post(JsonApiURL+'api/teachers_commission_edition_json.php', {update: {objectId: this.edition,  directive_num: this.directive_num,    sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      //this.info9 = response.data

            })
            .catch(error => {
              console.log(error.response)
            })



            for( var i=0; i<this.n.length; i++ ) {
              if(this.teacher_id[i]>0 && this.fullname[i]!=''){
                   axios
                   //.post(JsonApiURL+'api/teachers_commission_teacher_json.php', {update: {objectId: this.teacher_id[i],  n: i, edition_id: this.edition,   fullname: this.fullname[i], job_title: this.job_title[i], sign: this.sign[i],  sessionId: session_t.sessionId } })
                   .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {update: {objectId: this.teacher_id[i],  n: i, edition_id: this.edition,   fullname: this.fullname[i], job_title: this.job_title[i],  sessionId: session_t.sessionId } })
                   .then(response => {
                     console.log(response)
                   })
                   .catch(error => {
                     console.log(error.response)
                   })
               }
               else if(this.teacher_id[i]==0 && this.fullname[i]!=''){
                   axios
                   .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {insert: {commission_id: this.object_id,  n: i, edition_id: this.edition,  fullname: this.fullname[i], job_title: this.job_title[i], sign: this.sign[i],  sessionId: session_t.sessionId } })
                   .then(response => {
                     console.log(response)
                     this.teacher_id[i] = response.result
                   })
                   .catch(error => {
                     console.log(error.response)
                   })
               }
               else {
                   axios
                   .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {delete: {objectId: this.teacher_id[i],  sessionId: session_t.sessionId } })
                   .then(response => {
                     console.log(response)
                   })
                   .catch(error => {
                     console.log(error.response)
                   })
               }

               if(i == this.n.length-1){
                   this.uploadGo()
               }
            }
            this.$router.push({ path: '/teachers_commission_list'}) 


        },


        objectCreate() {
           var commissionId=0
           axios
            .post(JsonApiURL+'api/teachers_commission_json.php', {insert: {  name: this.name, order_id: 0,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
    	      this.info9 = response.data
              commissionId=this.info9.result

               /*for( var i=0; i<this.n.length; i++ ) {
                   axios
                   .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {insert: {commission_id: commissionId,  n: i,  fullname: this.fullname[i], job_title: this.job_title[i], sign: this.sign[i],  sessionId: session_t.sessionId } })
                   .then(response => {
                     console.log(response)
                    //this.info9 = response.data
  
                   })
                   .catch(error => {
                     console.log(error.response)
                   })
 
                }*/

             if(this.edition >0){
                 var newDate = this.info2.list[this.info2.list.findIndex(item => item.edition_id == this.edition)].edition

                 axios
                  .post(JsonApiURL+'api/teachers_commission_edition_json.php', {insert: {commission_id: commissionId, edition: newDate, directive_num: this.directive_num,  sessionId: session_t.sessionId } })
                  .then(response => { 
                     this.info3 = response.data
                     editionId = this.info3.result

                     for( var i=0; i<this.n.length; i++ ) {
                       if( this.fullname[i]!=''){
                         axios
                         .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {insert: {edition_id: editionId,  commission_id: commissionId,  n: i,  fullname: this.fullname[i], job_title: this.job_title[i], sign: this.sign[i],  sessionId: session_t.sessionId } })
                         .then(response => {
                           console.log(response)
                        //this.info9 = response.data
  
                         })
                        .catch(error => {
                          console.log(error.response)
                         })
                       }
                     }
                  })
                   .catch(error => {
                      console.log(error.response)
                  })
                }

                this.$router.push({ path: '/teachers_commission_list'}) 
            })
            .catch(error => {
              console.log(error.response)
            })

        },

        newEdition(){
           this.edition = this.new_date

           axios
            .post(JsonApiURL+'api/teachers_commission_edition_json.php', {insert: {commission_id: this.object_id, edition: this.new_date, directive_num: this.directive_num,  sessionId: session_t.sessionId } })
            .then(response => { 
             this.info3 = response.data
             editionId = this.info3.result

               for( var i=0; i<this.n.length; i++ ) {
                 if( this.fullname[i]!=''){
                   axios
                   .post(JsonApiURL+'api/teachers_commission_teacher_json.php', {insert: {edition_id: editionId,  commission_id: this.object_id,  n: i,  fullname: this.fullname[i], job_title: this.job_title[i], sign: this.sign[i],  sessionId: session_t.sessionId } })
                   .then(response => {
                     console.log(response)
                    //this.info9 = response.data
  
                   })
                   .catch(error => {
                     console.log(error.response)
                   })
                 }
               }
               conditions =  {"commission_id": this.object_id}
               axios
                  .post(JsonApiURL+'api/teachers_commission_edition_json.php', {list: {conditions, sessionId: session_t.sessionId } })
                  .then(response => { 
                      this.info2 = response.data

                   })
                     .catch(error => {
                       console.log(error.response)
                   })

               this.make_t = false
             })
             .catch(error => {
                  console.log(error.response)
             })
        },


       dtDelete( ){
       var name = this.info2.list[this.info2.list.findIndex(item => item.edition_id == this.edition)].edition

       var fl = confirm('Удалить  период : ' + name + '?');
       if(fl) {
         axios
          .post(JsonApiURL+'api/teachers_commission_edition_json.php', {delete: {objectId: this.edition}})
          .then(response => { 
            //this.info9 = response.data
            this.edition = 0

	    axios
    	    .post(JsonApiURL+'api/teachers_commission_edition_json.php', {list: { sessionId: session_t.sessionId }})
    	    .then(response2 => { 
        	this.info2 = response2.data
    	    })
    	    .catch(error2 => {
              console.log(error2.response)
            })

         })
         .catch(error => {
              console.log(error.response)
         })
       }

      },

     uploadGo( ) {
        for (var i = 0; i < this.uploadfile1.length; i++) {  
alert(i)
            this.wait = 1;
            if(this.file1_type[i] == 'image/png' || this.file1_type[i] == 'image/jpeg' ||  this.file1_type[i] == 'image/webp' ){
                const formData = new FormData();
                formData.append('upload1', this.uploadfile1[i])
                formData.append('file1_name', this.file1_name[i])
                formData.append('teacher_id', this.teacher_id[i])
                formData.append('commission_id', this.object_id)
                formData.append('edition_id', this.edition)
                formData.append('sessionId', session_t.sessionId)

                axios
                    .post(JsonApiURL+'api/teachers_commission_upload_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                      this.info9 = response.data
                      this.wait = 0;
                      console.log(response)
                })
                .catch(error => {
                    console.log(error.response)
                })
            }
        }        
     },
    

     handleUpload(index) {
            this.uploadfile1[index] = document.getElementById('input_file_sign'+index).files[0];
            this.file1_name[index] = this.uploadfile1[index].name;
            this.file1_type[index] = this.uploadfile1[index].type;
     },




  },



	template: `
  <container v-if="role_privileges.teachers_commission_list != 0">
  <div><navigation></navigation><h3>Редактирование состава комиссии</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

{{uploadfile1}}

<div align="left">

  <div class="mb-2">	
  <label for="input_name"  class="form-label col-sm-2"   ><b>Наименование комиссии</b> </label>
    <input v-model="name"   class="form-control" id="input_fullname"   type="text"  >
  </div>

  <span v-if="object_id>0 || copy_id > 0">
  <div v-if="!make_t"  class="mb-2 row">
    <label v-if="copy_id>0" for="input_date"  class="form-label col-sm-4"  style="padding-top: 5px;" >Дата состава комисси для копирования</label>
    <label v-else for="input_date"  class="form-label col-sm-1"  style="padding-top: 5px;" > <b>Дата</b> </label>
    <select  v-model="edition" class="form-select col" id="input_date"   @change="objectLoad()" ><option  value="0"> - дата - </option> 
       <option v-for="item_o in info2.list"   :value="item_o.edition_id">{{item_o.edition}}</option>
    </select>
    <div class="col-1" style="padding-top: 7px;"><a v-if="role==('admin' || role=='metodist') && edition!='' "   @click="dtDelete()"   title="Удалить период" style="color: red;" ><i class="far fa-trash-alt"></i></a></div>

  <div class="col-8"> 
    <div  v-if="(role_privileges.teachers_commission_list == 2) && copy_id == 0 "  style="text-align: right;"><button @click="this.make_t=true"  class="btn btn-light  text-primary" > <nobr><i class="fa-solid fa-file-circle-plus link"></i> Создать запись на  новый  период </nobr>  </button></div>
  </div>
  </div>

  <div  v-if="make_t"   class="mb-2 row">
    <span class="col-sm-2">
     <input v-model="new_date"  v-model="new_date"   class="form-control" type="date" >
    </span>
    
    <button v-if="new_date!=''"  @click="newEdition()"  class="btn btn-light  text-success col-sm-1" id="input_new_date" > <nobr><i class="fa-solid fa-check"></i><small> Создать </small></nobr>  </button>&nbsp&nbsp
    <label v-else for="input_new_date"  class="form-label text-success col-sm-2" style="padding-top: 5px;"  > <b>Выберите дату</b> </label>
    &nbsp<button  @click="this.make_t=false; this.new_date='' "  class="btn btn-light  text-secondary col-sm-1" > <nobr><i class="fa-solid fa-xmark"></i><small> Отмена </small></nobr>  </button>
  </div>
  <br />




 <table width="100%" border="0">
 <tr v-for="(item, index) in n">
  <td style="padding: 5px 7px 5px 0px;" width="30%"><span v-if="item==0"><nobr><b>Председатель комиссии</b></nobr><br /></span><span v-if="item==1"><nobr><b>Члены комиссии</b></nobr><br /></span>
        <input v-model="fullname[index]"   class="form-control"  id="input_fullname_index"   type="text" placeholder="Фамилия И.О." ></td>
 <td style="padding: 5px 7px 5px 7px;" valign="bottom" width="30%"><input v-model="job_title[index]"   class="form-control"  id="input_job_title_index"   type="text"  placeholder="Должность"></td>
  <!--<td style="padding: 5px 0px 5px 7px;" valign="bottom"><input v-model="sign[index]"   class="form-control"  id="input_job_title_sign"   type="text"  placeholder="Файл скана подписи"></td> -->
  <td style="padding: 7px 0px 0px 3px;" valign="bottom"><img v-if="sign[index]!=''"  :src="'/documents/'+sign[index]" width="80px" >  </td>
  <td style="padding: 5px 0px 5px 7px;" valign="bottom"><span v-if="item==0"><nobr><b><i>Файл скана подписи</i></b></nobr><br /></span><input v-model="sign_upload[index]"   class="form-control"  :id="'input_file_sign'+index" @change="handleUpload(index)"   type="file"  placeholder="Файл скана подписи"   accept=".png,.jpg,.jpeg,image/png,image/jpeg"  ></td>
<!--  :ref="el => { if (el) divs[index] = el }" -->
 </tr>
</table>

  </span>

  <br />
  <div v-if="edition>0" class="mb-2 row">
      <label for="input_directive" class="form-label col-sm-3"><b>Абзац протокола / Номер приказа</b></label>
      <input v-model="directive_num"   class="form-control col" id="input_directive" type="text" />
  </div>


  <br />
  <div  v-if="role_privileges.teachers_commission_list == 2 "  class="mb-2">
    <div align="right">
    <button v-if="object_id>0"   class="btn btn-primary"    @click="objectSave()"> Сохранить </button>
    <button v-if="object_id==0"  class="btn btn-success"    @click="objectCreate()"> Добавить комиссию </button>
      &nbsp<router-link to="/teachers_commission_list" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>




</div>
	</div>
  </container>`

};




