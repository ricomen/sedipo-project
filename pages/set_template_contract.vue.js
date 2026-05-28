var SetTemplateContract  = {
  data: function () {
    return {
      role: '',
      info: [],
      info1: [],
      info2: [],
      info3: [],
      info4: [],
      info5: [],
      info5a: [],
      info6: [],
      info6a: [],
      info7: [],
      info9: [],
      entity: 1,
      message: '',
      contract_id: [],
      contract_name: [],
      contract_prefix: [],
      contract1_id: [],
      contract2_id: [],
      contract3_id: [],
      contract_a_id: [],
      contract_a_name: [],
      contract_a_prefix: [],
      contract1_a_id: [],
      contract2_a_id: [],
      contract3_a_id: [],
      b_contract_id: [],
      b_contract_prefix: [],
      c_contract_id: [],
      c_contract_prefix: [],
      }
   },

   mounted() {

    var conditions =  {"type": 0}
    axios
      .post(JsonApiURL+'api/contract_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
            var contracs_count = this.info.list.length

            for(var i = 0; i<contracs_count; i++){
                 this.contract_name[i] = this.info.list[i].name
                 this.contract_id[i] = this.info.list[i].template_id
                 this.contract1_id[i] = this.info.list[i].template1_id
                 this.contract2_id[i] = this.info.list[i].template2_id
                 this.contract3_id[i] = this.info.list[i].template3_id
                 this.contract_prefix[i] = this.info.list[i].prefix
            }

       })
      .catch(error => {
              console.log(error.response)
            })

    conditions =  {"type": 1}
    axios
      .post(JsonApiURL+'api/contract_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info1 = response.data
            var contracs_count = this.info1.list.length

            for(var i = 0; i<contracs_count; i++){
                 this.contract_a_name[i] = this.info1.list[i].name
                 this.contract_a_id[i] = this.info1.list[i].template_id
                 this.contract1_a_id[i] = this.info1.list[i].template1_id
                 this.contract2_a_id[i] = this.info1.list[i].template2_id
                 this.contract3_a_id[i] = this.info1.list[i].template3_id
                 this.contract_a_prefix[i] = this.info1.list[i].prefix
            }

       })
      .catch(error => {
              console.log(error.response)
            })


    conditions =  {"type": 10}
    axios
      .post(JsonApiURL+'api/contract_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info2 = response.data
            var contracs_count = this.info2.list.length

            for(var i = 0; i<contracs_count; i++){
                 this.b_contract_id[i] = this.info2.list[i].template_id

            }

       })
      .catch(error => {
              console.log(error.response)
            })


    conditions =  {"type": 20}
    axios
      .post(JsonApiURL+'api/contract_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info3 = response.data
            var contracs_count = this.info3.list.length

            for(var i = 0; i<contracs_count; i++){
                 this.c_contract_id[i] = this.info3.list[i].template_id

            }

       })
      .catch(error => {
              console.log(error.response)
            })



    conditions =  {"type": 5, "application": 0}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info5 = response.data

       })
      .catch(error => {
              console.log(error.response)
            })


    conditions =  {"type": 5, "application": 1}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info5a = response.data

       })
      .catch(error => {
              console.log(error.response)
            })


    conditions =  {"type": 6, "application": 0}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info6 = response.data

       })
      .catch(error => {
              console.log(error.response)
            })


    conditions =  {"type": 6, "application": 1}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info6a = response.data

       })
      .catch(error => {
              console.log(error.response)
            })



    conditions =  {"type": 4}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info4 = response.data

       })
      .catch(error => {
              console.log(error.response)
            })

    conditions =  {"type": 7}
    axios
      .post(JsonApiURL+'api/template_json.php', {list: {conditions,  sessionId: session_t.sessionId } })
      .then(response => { 
            this.info7 = response.data

       })
      .catch(error => {
              console.log(error.response)
            })



  },

//    updated() {
//      .post(JsonApiURL+'api/set_template_contract_json.php', {object: {objectId: this.course_id, sessionId: session_t.sessionId } })

//  },


  methods: {
    objectSave() {

        for(var i = 0; i<this.info.list.length; i++){
            //this.contract_a_name[i] = this.info1.list[i].name
            axios
                .post(JsonApiURL+'api/contract_json.php', {update: {objectId: this.info.list[i].contract_id,  template_id: this.contract_id[i],  template1_id: this.contract1_id[i],  template2_id: this.contract2_id[i],  template3_id: this.contract3_id[i],  name:this.contract_name[i],   prefix: this.contract_prefix[i],    sessionId: session_t.sessionId } })
                .then(response => {
                  console.log(response)
                  //this.info9 = response.data

                })
                  .catch(error => {
                  console.log(error.response)
                })
        }

        for(var i = 0; i<this.info1.list.length; i++){
            //this.contract_a_name[i] = this.info1.list[i].name
            axios
                .post(JsonApiURL+'api/contract_json.php', {update: {objectId: this.info1.list[i].contract_id,  template_id: this.contract_a_id[i],  template1_id: this.contract1_a_id[i],  template2_id: this.contract2_a_id[i],  template3_id: this.contract3_a_id[i],   name: this.contract_a_name[i],    prefix: this.contract_a_prefix[i],    sessionId: session_t.sessionId } })
                .then(response => {
                  console.log(response)
                  //this.info9 = response.data

                })
                  .catch(error => {
                  console.log(error.response)
                })
        } 

        for(var i = 0; i<this.info2.list.length; i++){
            //this.contract_a_name[i] = this.info1.list[i].name
            axios
                .post(JsonApiURL+'api/contract_json.php', {update: {objectId: this.info2.list[i].contract_id,  template_id: this.b_contract_id[i],   sessionId: session_t.sessionId } })
                .then(response => {
                  console.log(response)
                   //this.info9 = response.data
                  //if(i == this.info2.list.length-1)
                    // this.$router.push({ name: 'template_list' }) 

                })
                  .catch(error => {
                  console.log(error.response)
                })
        }

        for(var i = 0; i<this.info3.list.length; i++){
            axios
                .post(JsonApiURL+'api/contract_json.php', {update: {objectId: this.info3.list[i].contract_id,  template_id: this.с_contract_id[i],   sessionId: session_t.sessionId } })
                .then(response => {
                  console.log(response)

                })
                  .catch(error => {
                  console.log(error.response)
                })
        }

        this.$router.push({ name: 'template_list' }) 
     }


   },


	template: `<div><navigation></navigation><h3>Шаблоны договоров</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<!--<div  v-if="role=='admin' || role=='metodist' " align="left">-->
<div   align="left">
<br />


  <div  class="mb-3 row">
      <label  class="form-label col-sm-2">  </label>
      <div class="form-label col-sm">
        <input v-model="entity" type="radio" value="1" class="form-check-input" id="entity_Check1" > <label class="form-check-label" for="entity_Check1"> юр. лицо </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <input v-model="entity" type="radio" value="3" class="form-check-input" id="entity_Check3" > <label class="form-check-label" for="entity_Check3"> физ. лицо </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input v-model="entity" type="radio" value="7" class="form-check-input" id="entity_Check7" > <label class="form-check-label" for="entity_Check7"> документы для отчетности </label> 
       </div>
  </div>
  <hr />

 <span v-if="entity==1">
  <span  v-for="(item, index) in info.list" >
  <div class="mb-2 row">
      <label for="input_directive" class="form-label col-sm-2">Наименование  </label>
      <div class="form-label col-sm">
      <input v-model="contract_name[index]"   class="form-control" id="input_name" type="text"  />
      </div>
      <label for="input_prefix" class="form-label col-sm-1" style="text-align: right;">Префикс:  </label>
      <div class="form-label col-sm-2">
      <input v-model="contract_prefix[index]"   class="form-control" id="input_prefix" type="text" />
      </div>
      <!--<div clacc="col-1"> </div>-->
  </div>

  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон договора </label>
      <div class="mb-3 col">
      	<select  v-model="contract_id[index]"  :id="'input_contract_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон договора  - </option>
                <option v-for="item2 in info5.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1" style="text-align: right;"><span v-if="item.performer>0"><i class="fa-regular fa-handshake"></i></span><span v-else><i class="fa-brands fa-leanpub"></i></span></div>
  </div>

  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №1 к договору </label>
      <div class="mb-3 col">
      	<select  v-model="contract1_id[index]"  :id="'input_contract1_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info5a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №2 к договору </label>
      <div class="mb-3 col">
      	<select  v-model="contract2_id[index]"  :id="'input_contract2_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info5a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №3 к договору </label>
      <div class="mb-3 col">
      	<select  v-model="contract3_id[index]"  :id="'input_contract3_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info5a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  </span>
 </span>

 <span v-if="entity==7">
  <span  v-for="(item, index) in info2.list" >
  <div class="mb-2 row">
      <label for="input_protocol" class="form-label col-sm-2">{{item.name}} </label>
      <div class="mb-3 col">
      	<select  v-model="b_contract_id[index]"  :id="'input_buch_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info4.list" :value="item2.template_id">
	            {{item2.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  </span>
  <hr />

  <span  v-for="(item, index) in info3.list" >
  <div class="mb-2 row">
      <label for="input_protocol" class="form-label col-sm-2">{{item.name}} </label>
      <div class="mb-3 col">
      	<select  v-model="c_contract_id[index]"  :id="'input_buch_'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item3 in info7.list" :value="item3.template_id">
	            {{item3.name.substring(0, 70)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  </span>
 </span>



 <!--  -----  -->
 <span v-if="entity==3">
  <span  v-for="(item, index) in info1.list" >
  <div class="mb-2 row">
      <label for="input_directive" class="form-label col-sm-2">Наименование  </label>
      <div class="form-label col-sm">
      <input v-model="contract_a_name[index]"   class="form-control" id="input_name_b" type="text" />
      </div>
      <label for="input_prefix" class="form-label col-sm-1" style="text-align: right;">Префикс:  </label>
      <div class="form-label col-sm-2">
      <input v-model="contract_a_prefix[index]"   class="form-control" id="input_prefix" type="text" />
      </div>
      <!--<div clacc="col-1"> </div>-->
  </div>

  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон договора </label>
      <div class="mb-3 col">
      	<select  v-model="contract_a_id[index]"  :id="'input_contract_b'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон договора  - </option>
            <option v-for="item2 in info6.list" :value="item2.template_id">
	            {{item2.name.substring(0, 90)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1" style="text-align: right;"><span v-if="item.performer>0"><i class="fa-regular fa-handshake"></i></span><span v-else><i class="fa-brands fa-leanpub"></i></span></div>
  </div>

  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №1 к договору </label>
      <div class="mb-3 col">
            <select  v-model="contract1_a_id[index]"  :id="'input_contract1_b'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info6a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 90)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №2 к договору </label>
      <div class="mb-3 col">
            <select  v-model="contract2_a_id[index]"  :id="'input_contract2_b'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info6a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 90)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  <div  class="mb-3 row">
      <label for="input_protocol" class="form-label col-sm-2">Шаблон приложения №3 к договору </label>
      <div class="mb-3 col">
            <select  v-model="contract3_a_id[index]"  :id="'input_contract3_b'+index" class="form-select col" aria-label="template" >
	        <option value="0"> -  Шаблон   - </option>
                <option v-for="item2 in info6a.list" :value="item2.template_id">
	            {{item2.name.substring(0, 90)}} 
	        </option>
	    </select>
      </div>
    <div class="col-1">  </div>
  </div>
  </span>

 </span>

  <br /><hr />

  <br / >
  <div class="mb-2">
    <div align="right">
    <button   class="btn btn-primary"    @click="objectSave()"> Сохранить </button>
      &nbsp<router-link  :to="{ name: 'template_list'}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>
</span>



</div>
	</div>`

};








