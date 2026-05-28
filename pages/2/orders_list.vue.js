var OrdersList = {
  data: function () {
    return {
      role: '',    
      moodle_path: '',
      info: [],
      info2: [],
      info3: [],
      info9: [],
      form_id: '',
      a_counterparty_id: 0,
      a_order_id: 0,
      a_sort: 0,
      form_of_study: [ {form_id: 1, name: 'Очная'}, {form_id: 2, name: 'Заочная'}, {form_id: 3, name: 'Очно-заочная'}, {form_id: 4, name:  'Заочная с применением дистанционных технологий'} ],
      status_array:  [], 
      status2_array:  [], 
      message: '',
      namesearch: '',
      ordersearch: '',
      invoicesearch: '',
      datesearch: '',
      datesearch2: '',
      counterparty_с: '',
      counterparty_с_id: 0,
      status: 0,
      check1: '',
      check2: '',
      check3: '',
      print_v: 'true',
      numPages: 0,
      page: 1,
      mon_arr: [{name: 'Январь', t_class: 'nav-link'}, {name: 'Февраль', t_class: 'nav-link'}, {name: 'Март', t_class: 'nav-link'}, {name: 'Апрель', t_class: 'nav-link'}, {name: 'Май', t_class: 'nav-link'}, {name: 'Июнь', t_class: 'nav-link'}, {name: 'Июль', t_class: 'nav-link'}, {name: 'Август', t_class: 'nav-link'}, {name: 'Сентябрь', t_class: 'nav-link'}, {name: 'Октябрь', t_class: 'nav-link'}, {name: 'Ноябрь', t_class: 'nav-link'}, {name: 'Декабрь', t_class: 'nav-link'} ],
      year_arr: [],
      year_tab: new Date().getFullYear(),
      mon__tab: 0,
      contract_file0: '',
      contract_file: [],
      contract_modal_counterparty_id: 0,
      //contract_modal_contract_id: 0,
      modal_order_id: 0,
      modal_date_order: '',
      modal_date_completed: '',
      modal_number: 0,
      modal_invoice: '', 
      modal_completed: [],
      modal_contract_list: [],
      importfile1: [],
      importfile1_filename: [],
      importfile1_file_type: [],
      a_contract_id: [],
      date_contract: [],
      payment_receipt: '', 
      payment_receipt_date: '',
      date_exist: true,
      divs: [],
      cancelled: 1,
      is_1C: IS_1C,
     }
   },


   mounted() {
      this.page = session_var.order_page
      this.ordersearch = session_var.order_ordersearch
      this.invoicesearch = session_var.order_invoicesearch
      this.namesearch = session_var.order_namesearch 
      this.counterparty_с_id = session_var.order_counterparty_с_id
      this.counterparty_с  =  session_var.order_counterparty_с
      this.datesearch = session_var.order_datesearch
      this.datesearch2 = session_var.order_datesearch2
      this.status = session_var.order_status
      this.cancelled = session_var.order_cancelled
      this.orders_list_buh = session_t.role_privileges.orders_list_buh

      if(Number(this.$route.params.counterpartyid) > 0)
            this.a_counterparty_id = Number(this.$route.params.counterpartyid)

      if( this.$route.params.counterpartyid == ''){
            this.page  = 1
      }

      if(Number(this.$route.params.orderid) > 0 )
            this.a_order_id = Number(this.$route.params.orderid)

      if(Number(this.$route.params.sort > 0))
            this.a_sort = Number(this.$route.params.sort)

      if(this.$route.params.sort == null)
            this.a_sort = session_var.order_sort

      for (var i=-1; i<4; i++){
        this.year_arr.push({name: this.year_tab-i})
      }    

      this.moodle_url = MoodleApiURL

    var counterparty_id = this.counterparty_с_id
    if(this.a_counterparty_id>0)
          counterparty_id = this.a_counterparty_id

    var status = this.status
    if( this.cancelled > 1 )
            status = this.cancelled


//      .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, course: this.namesearch, status: this.status,  counterparty_id: this.a_counterparty_id, orderid: this.a_order_id, page: this.page,  sessionId: session_t.sessionId }})

    axios
      .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, order_name: this.ordersearch, course: this.namesearch, status: status,  counterparty_id: counterparty_id,  orderid: this.a_order_id, page: this.page, sort: this.a_sort,   sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
            this.numPages = this.info.numPages
            this.page = this.info.page
 
            if( this.role=='counterparty' )
                    this.print_v = 'false'
       })
      .catch(error => {
              console.log(error.response)
        })


      if(this.a_counterparty_id == 0) {
	    /*axios
                .post(JsonApiURL+'api/counterparty_json.php', {list: { sessionId: session_t.sessionId }})
    		.then(response2 => { 
        	    this.info2 = response2.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})*/
       }
       if(this.a_counterparty_id > 1) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.a_counterparty_id, sessionId: session_t.sessionId } })
    		.then(response3 => { 
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
       }


    axios
      .post(JsonApiURL+'api/status_json.php', {list: {  sessionId: session_t.sessionId }})
      .then(response => { 
            //this.status_array = response.data.list
            for(var i=0; i< response.data.list.length; i++ ) {
                  if(response.data.list[i].status_id <=15 )
                        this.status_array.push(response.data.list[i])
                  else
                        this.status2_array.push(response.data.list[i])
            }
       })
      .catch(error => {
              console.log(error.response)
        })

  },

  updated() {
        window.scrollTo(0, Number(session_var.order_posTop) );
  },


  methods: {
      
    search_go(clean) {
    if(clean>0){
        this.ordersearch = ''
        this.invoicesearch = ''
        this.namesearch = ''
        this.datesearch = ''
        this.datesearch2 = ''
        this.counterparty_с_id = 0
        this.counterparty_с = ''
        this.status = 0
    }
    var counterparty_id = this.counterparty_с_id
    if(this.a_counterparty_id>0)
          counterparty_id = this.a_counterparty_id

    session_var.order_ordersearch = this.ordersearch
    session_var.order_invoicesearch =  this.invoicesearch
    session_var.order_namesearch = this.namesearch
    session_var.order_counterparty_с_id = this.counterparty_с_id
    session_var.order_counterparty_с = this.counterparty_с
    session_var.order_datesearch = this.datesearch
    session_var.order_datesearch2 = this.datesearch2
    session_var.order_status = this.status
    session_var.order_cancelled = this.cancelled
    session_var.order_posTop = 0

    var status = this.status
    if( this.cancelled > 1 )
            status = this.cancelled


    axios
      .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, order_name: this.ordersearch,  course: this.namesearch, status: status,  counterparty_id: counterparty_id, orderid: this.a_order_id, page: this.page, sort: this.a_sort,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.numPages = this.info.numPages
	        this.page = this.info.page
             console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            })
    },


    page_go(page) {
    this.page = page
    session_var.order_page = page

    session_var.order_ordersearch = this.ordersearch
    session_var.order_invoicesearch =  this.invoicesearch
    session_var.order_namesearch = this.namesearch
    session_var.order_counterparty_с_id = this.counterparty_с_id
    session_var.order_counterparty_с = this.counterparty_с
    session_var.order_datesearch = this.datesearch
    session_var.order_datesearch2 = this.datesearch2
    session_var.order_status = this.status
    session_var.order_cancelled = this.cancelled
    session_var.order_posTop = 0



    var status = this.status
     if( this.cancelled > 1 )
            status = this.cancelled

     axios
      .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, order_name: this.ordersearch, course: this.namesearch, status: status, counterparty_id: this.a_counterparty_id, orderid: this.a_order_id, page: this.page, sort: this.a_sort,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
            this.numPages = this.info.numPages
            this.page = this.info.page
       })
      .catch(error => {
              console.log(error.response)
        })

    },

      newStatus(order_id, a_status, is_modal) {
          session_var.order_posTop = window.pageYOffset

        if(is_modal > 0){
            var orderId = this.modal_order_id
            var fl = true
        }
        else {
            orderId = order_id 
            if(status==1)
                fl = confirm("Подтверждаю, что действительно производится возврат к первому этапу формирования заявки, счет на оплату будет выписан повторно");
            else
                fl = confirm("Подтверждаю, что данные заполнены корректно");
        }

        var status = a_status
        if( this.cancelled > 1 )
                status = cancelled
        /*if( status == 15 )
                status = 12
        else if( status == 16 )
                status = 1*/
        
        if(is_modal == 2 && this.payment_receipt>0 && this.payment_receipt_date!=null && this.payment_receipt_date!='' && this.payment_receipt_date!='0000-00-00'){
                axios
                .post(JsonApiURL+'api/orders_json.php', {payment_receipt: {objectId: orderId, payment_receipt: this.payment_receipt, payment_receipt_date: this.payment_receipt_date,  sessionId: session_t.sessionId }})
                .then(response => { 
                    this.info9 = response.data
                    console.log(response)

                })
                .catch(error => {
                      console.log(error.response)
                })
        }        

        if(fl) {
            axios
            .post(JsonApiURL+'api/orders_json.php', {update_status: { objectId: orderId,  status: status,  sessionId: session_t.sessionId }})
            .then(response => { 
                //this.info9 = response.data
                  console.log(response)
                axios
                .post(JsonApiURL+'api/orders_json.php', {list: { counterparty_id: this.a_counterparty_id, orderid: this.a_order_id, page: this.page,  sessionId: session_t.sessionId }})
                .then(response2 => { 
                    this.info = response2.data
                    console.log(response2)

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

      newStatus_preset(item, date_order, number){
          this.modal_order_id = item
          this.modal_date_order = date_order
          this.modal_number = number
          session_var.order_posTop = window.pageYOffset
      },
      
      orderSync(order_id){
        axios
          .post(JsonApiURL+'api/orders_json.php', {sync: {objectId: order_id,  sessionId: session_t.sessionId }})
           .then(response => { 
        })
        .catch(error => {
              console.log(error.response)
        })
          
      },

    tab_go(month) {
     if(this.year_tab>0) {    
        var date_t1 = new Date(this.year_tab, month, 1, 0, 0, 0, 0).toLocaleDateString('en-CA')
        var date_t2 = new Date(this.year_tab, month+1, 0, 0, 0, 0, 0).toLocaleDateString('en-CA')
        this.datesearch = date_t1
        this.datesearch2 = date_t2
        for (var i=0; i<12; i++){
            this.mon_arr[i].t_class =  'nav-link'
        }    
        this.mon_arr[month].t_class =  'nav-link active'

        session_var.order_ordersearch = this.ordersearch
        session_var.order_invoicesearch =  this.invoicesearch
        session_var.order_namesearch = this.namesearch
        session_var.order_counterparty_с_id = this.counterparty_с_id
        session_var.order_counterparty_с = this.counterparty_с
        session_var.order_datesearch = this.datesearch
        session_var.order_datesearch2 = this.datesearch2
        session_var.order_status = this.status
        session_var.order_cancelled = this.cancelled
        session_var.order_posTop = 0


        axios
        .post(JsonApiURL+'api/orders_json.php', {list: {date1: date_t1, date2: date_t2,  counterparty_id: this.a_counterparty_id, page: this.page,  sessionId: session_t.sessionId }})
        .then(response => { 
            this.info = response.data
            this.numPages = this.info.numPages
	        this.page = this.info.page
        })
        .catch(error => {
              console.log(error.response)
        })
     }    
    },


    orderDelete(orderId, name ){
	var fl = confirm('Удалить  запись: ' + name + '?');
	if(fl) {
	axios
          .post(JsonApiURL+'api/orders_json.php', {delete: {objectId: orderId, sessionId: session_t.sessionId }})
          .then(response => { 
            //this.info9 = response.data
                console.log(response)

                         axios
                          .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, course: this.namesearch, status: status,  counterparty_id: counterparty_id,  orderid: this.a_order_id, page: this.page, sort: this.a_sort,   sessionId: session_t.sessionId }})
                          .then(response2 => { 
                              this.info = response2.data
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


    search2(index){
       this.counterparty_с = this.info2.list[index].shortname 
       this.counterparty_с_id = this.info2.list[index].counterparty_id
       this.info2.list = []
    },


    load_combo(){
      if(this.counterparty_с!=''){
       axios
        .post(JsonApiURL+'api/counterparty_json.php', {list: {conditions: {"type": 0}, search: this.counterparty_с, sessionId: session_t.sessionId }})
        .then(response => { 
            this.info2 = response.data
        })
        .catch(error => {
              console.log(error.response)
        })
      }
      else {
          this.info2.list = []
      }
    },



    contractsModalArg(counterparty_id, contract_id, order_id, date_order, number, date_completed, contract_list, completed_a, invoice) {
        this.contract_modal_counterparty_id = counterparty_id
//this.contract_modal_contract_id = contract_id
        this.modal_order_id = order_id
        this.modal_date_order = date_order
        this.modal_date_completed = date_completed 
        this.modal_number = number
        this.modal_invoice = invoice
        this.modal_contract_list = contract_list
        this.modal_completed = completed_a
        this.a_contract_id = []
        for (var i = 0; i < contract_list.length; i++) {
            this.a_contract_id[i] = contract_list[i].contract_id
        }
        this.$refs.r_contract_file0.value = ''

        for (var i = 0; i < this.importfile1.length; i++) {
            this.importfile1[i] = null
//            if( this.importfile1[i] != null ) {
                    //document.value[this.importfile1[i]] = null
//            }
        }
        for (var i = 0; i < this.divs.length; i++) {
            if(this.divs[i]  != null )
                     this.divs[i].value = null
        }
        for (var i = 0; i < this.importfile1.length; i++) {
                              this.importfile1[i] = null
        }
    },

     

/*     contractSave() {
        if(this.contract_modal_counterparty_id > 0 ){
            
            axios
              //.post(JsonApiURL+'api/counterparty_contract_json.php', {update: {counterparty_id: this.contract_modal_counterparty_id, date_contract: this.date_contract, a_contract_id: this.a_contract_id, enterprise_manager: this.enterprise_manager, enterprise_manager2: this.enterprise_manager2, enterprise_manager_signs: this.enterprise_manager_signs, sessionId: session_t.sessionId } })
              .post(JsonApiURL+'api/orders_json.php', {contract_update: {counterparty_id: this.contract_modal_counterparty_id, order_id: this.modal_order_id, a_contract_id: this.a_contract_id,  sessionId: session_t.sessionId } })
              .then(response => { 
                 this.contractUpload()            
                 console.log(response)
            })
              .catch(error => {
                 console.log(error.response)
            })

        }
     },
*/
    
     contractUpload() {
        var counterparty_id = this.counterparty_с_id
        if(this.a_counterparty_id>0)
              counterparty_id = this.a_counterparty_id

        var status = this.status
        if( this.cancelled > 1 )
                status = this.cancelled

        for (var i = 0; i < this.importfile1.length; i++) {  
            this.wait = 1;
            if(this.importfile1_file_type[i] == 'application/pdf'){
                const formData = new FormData();
                formData.append('upload1', this.importfile1[i])
                //formData.append('name1', this.importfile1_filename)
                formData.append('counterparty_id', this.contract_modal_counterparty_id); 
                if(i == 0 )
                    formData.append('contract_id', 0 ); 
                else
                    formData.append('contract_id', this.a_contract_id[ parseInt((i-1)/4) ]); 
                formData.append('order_id', this.modal_order_id); 
                //formData.append('contract_id', this.contract_modal_contract_lis[(i-1)/4].contract_id); 	
                if( i==0)
                        formData.append('date_contract', this.modal_date_completed); 
                else if( (i-1)%4 == 0 )
                        formData.append('date_contract', this.date_contract[(i-1)/4]); 
                else
                        formData.append('date_contract', this.modal_date_order); 

                formData.append('addition', (i-1)%4);
                formData.append('sessionId', session_t.sessionId); 

                axios
                   .post(JsonApiURL+'api/order_contract_upload_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                   .then(response => {
                    this.info9 = response.data
                    if( i ==  this.importfile1.length-1 ){
                         this.wait = 0;

                         axios
                          .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, course: this.namesearch, status: status,  counterparty_id: counterparty_id,  orderid: this.a_order_id, page: this.page, sort: this.a_sort,   sessionId: session_t.sessionId }})
                          .then(response2 => { 
                              this.info = response2.data
                          })
                          .catch(error => {
                              console.log(error.response)
                          })

                    }
                    console.log(response)
                })
                .catch(error => {
                    console.log(error.response)
                })
            }
        }

     },
    

     handleUpload(index, n) {
            this.importfile1[index*4 + n  +1  ] = document.getElementById('input_contract_file_'+index+'_'+n).files[0];
            this.importfile1_filename[index*4 + n  +1  ] = this.importfile1[index*4 + n +1 ].name;
            this.importfile1_file_type[index*4 + n  +1  ] = this.importfile1[index*4 + n +1 ].type;
            this.checkDate(index)
     },
      
     handleUpload0() {
            this.importfile1[0 ] = document.getElementById('input_contract_file0').files[0];
            this.importfile1_filename[0] = this.importfile1[0 ].name;
            this.importfile1_file_type[0 ] = this.importfile1[0 ].type;
     },

     checkDate(index) {
            this.date_exist = true
            if( this.importfile1[index*4  +1 ]!=null && (this.date_contract[index]=='' || this.date_contract[index]==null ||  this.date_contract[index]=='1000-01-01' ) && index>0  )
                   this.date_exist = false
     },

     order_sort(s){
           this.a_sort = s 
           session_var.order_sort = s

           session_var.order_ordersearch = this.ordersearch
           session_var.order_invoicesearch =  this.invoicesearch
           session_var.order_namesearch = this.namesearch
           session_var.order_counterparty_с_id = this.counterparty_с_id
           session_var.order_counterparty_с = this.counterparty_с
           session_var.order_datesearch = this.datesearch
           session_var.order_datesearch2 = this.datesearch2
           session_var.order_status = this.status
           session_var.order_cancelled = this.cancelled
           session_var.order_posTop = 0


           var counterparty_id = this.counterparty_с_id
           if(this.a_counterparty_id>0)
                counterparty_id = this.a_counterparty_id

           var status = this.status
           if( this.cancelled > 1 )
                  status = this.cancelled

           if(this.a_sort>0){
              var ordersearch = ''
              var date1 = ''
              var date2 =  ''
              var course = ''
              var status =  0
              var counterparty_id = 0
           }
           else {
              ordersearch = this.ordersearch
              date1 = this.datesearch
              date2 = this.datesearch2
              course = this.namesearch
              status = this.status
              counterparty_id = this.counterparty_id
           }


           axios
              .post(JsonApiURL+'api/orders_json.php', {list: {date1: this.datesearch, date2: this.datesearch2, course: this.namesearch, status: status,  counterparty_id: counterparty_id,  orderid: this.a_order_id, page: this.page, sort: this.a_sort,   sessionId: session_t.sessionId }})
              .then(response => { 
                    this.info = response.data
                    this.numPages = this.info.numPages
	            this.page = this.info.page
 
                    if( this.role=='counterparty' )
                        this.print_v = 'false'

                    this.$router.push({ name: 'orders_list', params: {counterpartyid: this.a_counterparty_id, orderid: this.a_order_id, sort: this.a_sort }}) 
       })
      .catch(error => {
              console.log(error.response)
        })

     },

     posTop(clean){
        if(clean==0)
           session_var.order_posTop = window.pageYOffset
        else
           session_var.order_posTop = 0
     }

},


	template: `<div><navigation></navigation><h3>Заявки на обучение {{this.orders_list_buh}}</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <h4 v-if="role!='counterparty' && a_counterparty_id>0"  style="text-align: right;" > {{info3.result.shortname}} </h4>

    <table >
        <tr  align="left">
            <td  style="padding-left: 0px;  padding-right: 10px;" >c </td>
            <td  style="padding-left: 0px;  padding-right: 15px;" ><input type="date" v-model="datesearch" placeholder="Дата"  ></td>
            <td style="padding-left: 5px;  padding-right: 5px;"> по </td>
            <td  style="padding-left: 0px;  padding-right: 15px;" ><input type="date" v-model="datesearch2" placeholder="Дата"  ></td>
            <td style="padding-left: 10px;  padding-right: 5px;" >
                <span v-if="cancelled==1">
		    <select  v-model="status"  id="input_status"  aria-label="Статус">
    		    <option value="0"> - статус - </option>
    		    <option v-for="item_status in status_array" :value="item_status.status_id">{{item_status.name}} </option>
		    </select>
                </span>
	    </td>
        <td style="padding-left: 5px;  padding-right: 15px;"  v-if="a_counterparty_id==0 && role!='counterparty'" ><small>
	        <!--<select v-model="counterpartysearch">
	        <option value="0"> -  Организация  - </option>
                <option v-for="item2 in info2.list" :value="item2.counterparty_id">
	            {{item2.shortname.substring(0, 45)}} 
	        </option>
	        </select>-->
	    </small></td>

        <td  style="padding-left: 15px;  padding-right: 0px; text-align: right;" > <button  @click="search_go(0)">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button>&nbsp;&nbsp;&nbsp;&nbsp;<button  title="Сбросить все фильтры"   @click="search_go(1)"  >&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;</button> </td>
        
        </tr>

        <tr  align="left">
          <td colspan="5" style="padding-top: 10px; padding-right: 15px;">
            <div class ="container">
                <input type="text" v-model="ordersearch" placeholder="Заявка"   size="25"  @keyup.enter="search_go(0)" >
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" v-model="invoicesearch" placeholder="Номер счета"   size="25"  @keyup.enter="search_go(0)" >
            </div>
         </td>
        </tr>

        
        <tr  align="left">
          <td colspan="5" style="padding-top: 10px; padding-right: 15px;">
   <div class="mb-2">	
      <div class ="container">
        <input  v-model="counterparty_с"   id="input_counterparty_c"  type="text" @input="load_combo()" placeholder="Организация"   size="70"   @keyup.enter="search_go(0)" >
		<ul class ="list-group" id ="listItem" style="text-align: left;">
			<li  v-for="(item2, index) in info2.list" class ="list-group" @click="search2(index)" > {{item2.shortname.substring(0, 72)}}</li>
		</ul>
      </div>
   </div>
	      </td>
        </tr>
        
        <tr  align="left">
          <td colspan="5" style="padding-top: 2px; padding-right: 15px;">
      <div class ="container">
            <input type="text" v-model="namesearch" placeholder="Курс"   size="70"   @keyup.enter="search_go(0)">
      </div>
         </td>
        </tr>
    </table>

    <br />


    <table class="table">
      <thead>
        <tr  align="left">
          <td scope="col">
           <button v-if="role!='counterparty'" class="btn btn-light" > <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: a_counterparty_id }}"   title="Создать новую заявку"  @click="posTop(1)" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новая заявка ЮЛ</nobr></div></router-link ></div>  </button>
           <button v-else class="btn btn-light"> <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: this.info.counterparty_id }}"   title="Создать новую заявку"  @click="posTop(1)" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новая заявка ЮЛ</nobr></div></router-link ></div>  </button>
          </td>
          <td scope="col">
           <button v-if="role!='counterparty'" class="btn btn-light" > <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: 1 }}"   title="Создать новую заявку для физ. лиц"  @click="posTop(1)" ><div><nobr><i class="fa-regular fa-id-badge"></i> Новая заявка ФЛ</nobr></div></router-link ></div>  </button>
           <button v-else class="btn btn-light"> <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: 1 }}"   title="Создать новую заявку для физ. лиц"  @click="posTop(1)" ><div><nobr><i class="fa-regular fa-id-badge"></i> Новая заявка ФЛ</nobr></div></router-link ></div>  </button>
          </td>
          <td v-if="a_counterparty_id==0" >  </td>
          <td v-if="role!='counterparty'" colspan="3" style="text-align: right">
            <nobr><small>
                <input v-model="print_v" type="radio" value="false" class="form-check-input" id="print_Check0" > <label class="form-check-label" for="print_Check0"> скан-копия </label>&nbsp;&nbsp;&nbsp;&nbsp; 
                <input v-model="print_v" type="radio" value="true"  class="form-check-input" id="print_Check1" > <label class="form-check-label" for="print_Check1"> версия для печати </label>&nbsp;&nbsp;&nbsp;&nbsp;
                <input v-model="print_v" type="radio" value="edit"  class="form-check-input" id="print_Check3"> <label class="form-check-label" for="print_Check3"> редактор</label>
            </small></nobr>
          </td>
          <td scope="col" colspan="2" align="right">
               <small>
                  <a @click="order_sort(1)"  v-if="a_sort==0" title="Показать последние измененные"><i class="fa-solid fa-arrow-down-9-1"></i></a>
                  <a v-else @click="order_sort(0)" title="Показать список"><i class="fa-solid fa-arrow-up-wide-short"></i></a>
               </small>
          </td>
        </tr>

      
        <tr  align="center" valign="middle">
          <th scope="col" style="font-size: smaller; ">
           <!--<button v-if="role!='counterparty'" class="btn btn-light" > <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: a_counterparty_id }}"   title="Создать новую заявку" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новая заявка</nobr></div></router-link ></div>  </button>
           <button v-else class="btn btn-light"> <div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: this.info.counterparty_id }}"   title="Создать новую заявку"  ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новая заявка</nobr></div></router-link ></div>  </button>
           <br /><br /> -->
            Заявка
          </th>
          <th v-if="role!='counterparty' && a_counterparty_id==0"  scope="col" style="font-size: smaller;"> Организация </th>
          <th scope="col"  align="center" colspan="2" style="font-size: smaller;">Список слушателей</th>
          <th  scope="col" align="center" style="font-size: smaller;"><center>Статус</center></th>
          <!--<th scope="col"  align="center"  style="font-size: smaller;"> Бухгалтерские <br />документы </th>-->
          <th  align="center" style="font-size: smaller;">Учебные <br />группы</th>
          <th  align="center" style="font-size: smaller;"></th>
          <th v-if="role!='counterparty'"  scope="col"> <!--<div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: a_counterparty_id }}"   title="Новая заявка" ><b style=" font-size: larger;"><i class="far fa-plus-square"></i></b></router-link ></div>-->  </th>
          <th v-else scope="col"> <!--<div style="float: right"><router-link  :to="{ name: 'order_edit', params: {orderid: 0, counterpartyid: this.info.counterparty_id }}"   title="Новая заявка" ><b style=" font-size: larger;"><i class="far fa-plus-square"></i></b></router-link ></div>-->  </th>
        </tr>
      </thead>
     <tbody>   
        <tr v-for="item in info.list"  align="left" >
            <td><table border="0"><tr>
                  <td valign="middle" style="padding-left: 0px; padding-right: 7px; font-size: larger; padding-bottom: 10 px;" >  <!--<nobr><u><b>{{item.name_order}}</b></u></nobr>-->
                     <a :title="'Бухгалтерские документы (CED-'+item.order_id+')'" ><div style="text-align: right;"><button  @click="contractsModalArg(item.counterparty_id, 0, item.order_id, item.date_order, item.number, item.date_completed, item.contract_list, item.completed_a, item.invoice)"  class="btn btn-outline-primary  btn-sm"  data-bs-toggle="modal" data-bs-target="#contractLoadModal"><div style=" font-size: larger;"><nobr>{{item.name_order}}</nobr></div></button></div></a>
                     <!--<br /><a title="Бухгалтерские документы" ><div style="text-align: right;"><button  @click="contractsModalArg(item.counterparty_id, 0, item.order_id, item.date_order, item.number, item.date_completed, item.contract_list, item.completed_a, item.invoice )"  class="btn btn-outline-primary  btn-sm"  data-bs-toggle="modal" data-bs-target="#contractLoadModal"><div style="font-size: smaller;">Бухгалтерские <br />документы</div></button></div></a>-->
                     <div style="font-size: smaller; color: #505050; padding: 5px;"><i title="Номер счета">(<span v-if="item.invoice==''">CED-{{item.order_id}}</span><span v-else">{{item.invoice}}</span>)</i></div>
                  </td>
                  <td valign="top" style="padding-left: 0px; padding-right: 5px;"><router-link  :to="{ name: 'order_edit', params: {orderid: item.order_id, counterpartyid: this.a_counterparty_id}}" title="Редактировать настройки" @click="posTop(0)" ><span class="icon"><i class="fa-solid fa-pencil"></i></span></router-link>

                </tr></table>
            </td>
            <td v-if="role!='counterparty' && a_counterparty_id==0" >{{item.counterparty_name}}</td>
            
            <td align="center" style="padding-right: 1px;">
                <!--<div class="btn-group" >-->
                  <router-link  :to="{ name: 'order_items', params: {orderid: item.order_id, counterpartyid: this.a_counterparty_id  }}"  type="button" class="btn btn-outline-primary  btn-sm"  title="Редактировать список слушателей"  @click="posTop(0)"  ><i class="fa-solid fa-list-check" @click="posTop(0)" ></router-link></a></span>
                  <!--<router-link  :to="{ name: 'group_items', params: {groupid: item.groupId, orderid: this.order_id }}"  type="button" class="btn btn-outline-primary  btn-sm" title="Cписок обучающихся / документы по образованию" ><i class="fa-solid fa-list-check"></router-link>-->
                  <!--<button type="button" class="btn btn-outline-primary  btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
	            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="--bs-dropdown-min-width: 30rem;">
	                <li><a :href="'group_txt.php?groupId='+item.order_id+'&groupName='+item.date_order" target="_blank"  class="nav-link" title="Логины и пароли" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Логины и пароли</a></li>
	            </ul>
                </div>-->
                 <br /><div align="right"  style="font-size: smaller; color: #505050; padding-top: 5px;"><i title="Количество обучающихся">({{item.count}} чел.)</i></div>
              </td>
           </td>
           <td align="left" style="padding-left: 1px; padding-right: 1px; padding-top: 10px;">
                <span v-if="item.count==0">&nbsp<a title="Необходимо сформировать список" style="color: red;"><i class="fa-solid fa-circle-exclamation"></i></span>
                <span v-if="!item.snils_check_sum && item.count>0" style="color: red;" title="Некорректный СНИЛС обучающегося" ><i class="fa-solid fa-circle-exclamation"></i></span>
           </td>
 

            <td align="center"  style="padding-right: 15px;">
              <p><i>{{item.status_name}}</i></p>
               <span v-if="item.status_id>=5  && item.status_id<=10  && item.groups_count==0">
                    <p><b><small>Необходимо сформировать учебные группы <i class="fa-regular fa-circle-right"></i></small></b><p>
               </span>
              <div class="d-grid gap-1">
                <button v-if="role!='counterparty' && item.activity==2 && (item.status_id==3 || item.status_id==13)  && item.payment_receipt==0"  @click="newStatus_preset(item.order_id, item.date_order, item.number)"  class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#statusPayModal"><b>{{item.action}}</b></button>
                <button v-else-if="role!='counterparty' && item.activity==2"  @click="newStatus(item.order_id, 0, 0)" class="btn btn-success btn-sm"><b>{{item.action}}</b></button>
                <button v-if="role!='counterparty' && item.activity2==2 && item.status_id>=1 && item.new_status2>0"  @click="newStatus(item.order_id, -1, 0)" class="btn btn-outline-success btn-sm"><b>{{item.action2}}</b></button>

                <span v-if=" role=='counterparty' && (item.activity==1 && item.activity2==1)" class="d-grid gap-1">
                   <button v-if="item.activity==1 && item.status_id==1 && role!='counterparty' && a_counterparty_id>0"   @click="newStatus_preset(item.order_id, item.date_order, item.number)"  type="button"  class="btn btn-secondary btn-sm"  data-bs-toggle="modal" data-bs-target="#statusModal"><b>{{item.action}}</b></button>
                   <button v-else-if="item.activity==1 && item.status_id>=1"  @click="newStatus(item.order_id, 0, 0)" class="btn btn-secondary btn-sm"><b>{{item.action}}</b></button>
                   <button v-if="item.activity2==1 && item.status_id>1 && item.new_status2>0"  @click="newStatus(item.order_id, -1, 0)" class="btn btn-outline-secondary btn-sm"><b>{{item.action2}}</b></button>
                </span>
              </div>
            </td>

            <!--<td align="center">
	            <button  @click="contractsModalArg(item.counterparty_id, 0, item.order_id, item.date_order, item.number, item.date_completed, item.contract_list, item.completed_a )"  class="btn btn-light text-primary"  data-bs-toggle="modal" data-bs-target="#contractLoadModal"><i class="fa-regular fa-folder-open"></i></button>
            </td>-->

            <td align="center"> 
                    <nobr>
                    <span  v-if="item.status_id>5 && ! item.groups_count"><a title="Необходимо сформировать учебные группы"><span style="color: red;"><i class="fa-solid fa-circle-exclamation"></i></span>&nbsp</span> 
                    <router-link v-if="item.status_id>1 && item.status_id<10"  :to="{ name: 'groups_list', params: {orderid: item.order_id, make: 1, counterpartyid: this.a_counterparty_id  }}" type="button" class="btn btn-outline-primary  btn-sm" title="Создать/дополнить учебные группы" @click="posTop(0)"  ><i class="fa-solid fa-user-group"></i></router-link>
                    <router-link v-else-if="item.status_id>1 && item.groups_count"  :to="{ name: 'groups_list', params: {orderid: item.order_id, make: 0, counterpartyid: this.a_counterparty_id  }}" type="button" class="btn btn-outline-primary  btn-sm" title="Учебные группы"  @click="posTop(0)" ><i class="fa-solid fa-user-group"></i></router-link>
                    </nobr>
                    <br />
                    <div v-if="role!='counterparty' && a_counterparty_id==0" style="padding-top: 15px;" > <router-link v-if="item.status_id>1 && item.lstream_count>0"  :to="{ name: 'lstream_list', params: {orderid: item.order_id,  counterpartyid: this.a_counterparty_id  }}" type="button" class="btn btn-outline-primary  btn-sm" title="Потоки"  @click="posTop(0)" ><i class="fa-solid fa-users-line"></i></router-link></div>
            </td>

            <td align="center"> 
                <div v-if="item.status_id<=3" class="col-1" style="padding-left: 0px;  padding-right: 0px;" >
                  <router-link  :to="{ name: 'order_discounts', params: {orderid: item.order_id, counterpartyid: this.a_counterparty_id}}" title="Скидки"  @click="posTop(0)"  ><button  class="btn btn-light text-primary"><i class="fa-solid fa-piggy-bank"></i></button></router-link>
                </div>

                <div v-if="item.status_id>10 && item.status_id<16 " class="btn-group" class="col-1"   style="padding-bottom: 5px; padding-right: 0px;" >
                  <button  class="btn btn-light btn-sm text-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"  title="Документы по образованию"><b><i class="fa-regular fa-file-lines"></i></b></button>
                    <ul class="dropdown-menu"  style="--bs-dropdown-min-width: 15rem; padding: 7px;">
                          <li><a :href="'d-api/documents-protocol.php?groupid=0&id='+item.order_id+'&print_v='+this.print_v"  target="_blank"  class="nav-link" title="Протоколы" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Протоколы</a></li>
                          <li><a :href="'d-api/documents-cert.php?template=0&groupid=0&id='+item.order_id+'&print_v='+this.print_v" target="_blank"  class="nav-link"  title="Дипломы / Удостоверения" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Дипломы / Удостоверения</a></li>
                    </ul>
                </div>

                <div v-if="item.status_id>1 && item.status_id<=12 && this.is_1C>0"  class="col-1" style="padding-left: 0px;  padding-right: 0px; padding-top: 15px;" >
                  <span v-if="item.status_id==3 || item.status_id>11">
                    <a style="padding-left: 0px;  padding-right: 0px;" title="Повторная синхронизация с 1С"><button @click="orderSync(item.order_id)"  class="btn btn-light text-primary"  ><b><i class="fa-solid fa-arrow-right-arrow-left"></i></b></button></a>
                  </span>
                  <span v-else>
                    <a style="padding-left: 0px;  padding-right: 0px;" ><button class="btn btn-light text-primary disabled"  ><b><i class="fa-solid fa-arrow-right-arrow-left"></i></b></button></a>
                  </span>
                </div>
            </td>

            <td align="right">
              <div  class="row">
                  <!--<router-link  :to="{ name: 'order_edit', params: {orderid: item.order_id, counterpartyid: this.a_counterparty_id}}" title="Редактировать настройки" ><i class="fa-solid fa-pen-to-square"></i></router-link>-->
                <div  v-if="role!='counterparty' || item.status_id==1"  class="col-1">
	            <div  style="padding-left: 20px;  padding-right: 0px; text-align: right; color: red;" class="dropdown">
                     <button class="btn btn-link text-danger dropdown-toggle dropdown-toggle-split"  role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;"><i class="fa-solid fa-xmark"></i></button>
   	            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"  style="--bs-dropdown-min-width: 9rem;">
                        <li v-if="cancelled==1">&nbsp;&nbsp; <a  @click="newStatus(item.order_id, 15, 0)"   title="В архив" style="cursor: pointer;"><i class="fa-solid fa-boxes-packing"></i>&nbsp;В архив</a></li>
                        <li v-if="cancelled==1"><hr /></li>
                        <li v-if="cancelled==1">&nbsp;&nbsp; <a  @click="newStatus(item.order_id, 16, 0)"   title="Аннулировать" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i>&nbsp;Аннулировать</a></li>
                        <li v-if="cancelled==1"><hr /></li>
                        <li >&nbsp;&nbsp;<a   @click="orderDelete(item.order_id, item.date_order+'_'+item.number)"   title="Удалить" style="color: red; cursor: pointer;" ><i class="far fa-trash-alt"></i>&nbsp;Удалить</a></li>
	            </ul>
	            </div>
                    </div>
               </div>
		    </td>
          </tr>

        <tr v-if="role!='counterparty'" align="left">
          <td v-if="a_counterparty_id==0" >  </td>
          <td colspan="2" style="text-align: right">
            <small>
                <input v-model="print_v" type="radio" value="false" class="form-check-input" id="print_Check0" > <label class="form-check-label" for="print_Check0"> скан-копия </label>&nbsp;&nbsp;&nbsp;&nbsp; 
                <input v-model="print_v" type="radio" value="true"  class="form-check-input" id="print_Check1" > <label class="form-check-label" for="print_Check1"> версия для печати </label>&nbsp;&nbsp;&nbsp;&nbsp;
                <input v-model="print_v" type="radio" value="edit"  class="form-check-input" id="print_Check3"> <label class="form-check-label" for="print_Check3"> редактор</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <!--<input v-model="print_v" type="radio" value="office"  class="form-check-input" id="print_Check2"> <label class="form-check-label" for="print_Check2"> office&nbsp;  </label>&nbsp;&nbsp;&nbsp;&nbsp;-->
            </small>
          </td>

         <!--<td style="text-align: right"> </td>-->
         <td style="text-align: right" colspan="5">
	            <small><select  v-model="cancelled"  id="input_cancelled"  @change="search_go(0)" >
                    <option value="1"> Активные заявки </option>
                    <option v-for="item2_status in status2_array" :value="item2_status.status_id"  >
	               {{item2_status.name}} 
	            </option>
	            </select></small>
            </td>

        </tr>

  </tbody>
  </table>

<span v-if="role!='counterparty' && a_counterparty_id>0">
  <br />
  <span v-if="a_order_id>0">
    <div class="mb-2">	
        <div align="right">
            <router-link :to="{ name: 'lstream_list'}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
        </div>
    </div>
  </span>
  <span v-else>
    <div class="mb-2">	
        <div align="right">
            <router-link :to="{ name: 'counterparty_list'}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
        </div>
    </div>
  </span>
</span>




<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Согласие с правилами сайта</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <p>Подтверждаю, что список слушателей внесен полностью, список курсов введен полностью. Соглашаясь Вы подтверждаете, что корректировка заявки в дальнейшем приведет к потере части введенной информации</p>
      
        <div class="form-check">
            <input v-model="check1" class="form-check-input" type="checkbox" id="flexCheck1">
            <label class="form-check-label" for="flexCheck1"> С политикой конфиденциальности согласен </label>
        </div>
        <div class="form-check">
            <input v-model="check2" class="form-check-input" type="checkbox" id="flexCheck2">
            <label class="form-check-label" for="flexCheck2">С политикой конфиденциальности согласен 2</label>
        </div>
        <div class="form-check">
            <input v-model="check3" class="form-check-input" type="checkbox" id="flexCheck3">
            <label class="form-check-label" for="flexCheck3">С правилами оплаты и возвратов ознакомлен</label>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Отмена </button>
        <button v-if="check1==true && check2==true && check3==true" @click="newStatus(0, 0, 1)"  type="button" class="btn btn-primary" data-bs-dismiss="modal"> &nbsp&nbspok&nbsp&nbsp </button>
        <button v-else  type="button" class="btn btn-primary" data-bs-dismiss="modal" disabled> &nbsp&nbspok&nbsp&nbsp </button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<!--<div class="modal fade modal-xl" id="contractViewModal" tabindex="-1" aria-labelledby="contractViewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Подписанный договор</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" align="left">

        <embed :src="'/d-api/documents-contract.php?contract_id='+this.contract_modal_contract_id+'&counterparty_id='+this.contract_modal_counterparty_id+'&id='+this.modal_order_id+'&print_v=true'+'&paper=l'"  frameborder="0" width="100%" height="680px" ></embed>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Закрыть </button>
      </div>
    </div>
  </div>
</div>-->



<!-- Modal -->
<div class="modal fade modal-xl" id="contractLoadModal" tabindex="-1" aria-labelledby="contractLoadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="ModalLabel"><nobr>Бухгалтерские документы &nbsp;&nbsp;{{modal_date_order}}/{{modal_number}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i style="color: #555;">(<span v-if="modal_invoice==''">CED-{{modal_order_id}}</span><span v-else">{{modal_invoice}}</span>)</i></nobr></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" align="left">

        <!-- v-if="role!='counterparty' || item.status_id>1" --> 
	    <a v-if="contract_modal_counterparty_id>1 && (this.orders_list_buh == 2 || this.orders_list_buh == 1)" :href="'d-api/documents-order.php?contract_id=100&id='+this.modal_order_id+'&print_v='+this.print_v" target="_blank"  class="nav-link"  title="Версия для печати"  > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Счет </a>
        <div class="row">
                <div class="col-md-4">
	                <a v-if="this.orders_list_buh == 2 || this.orders_list_buh == 1" :href="'d-api/documents-order.php?contract_id=101&id='+this.modal_order_id+'&enable_lock=1&print_v='+this.print_v" target="_blank"  class="nav-link"  title="Версия для печати"  > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Акт выполненных работ </a>
                </div>
                <div class="col-md-2" style="padding-top: 5px; color: green;">
                    <span v-if="modal_completed.upload_file != null" ><i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;<a :href="modal_completed.upload_dir+modal_completed.upload_file"  target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-file-pdf"></i></button></a></span>
                </div>
                <div class="col-md-5">
                    <input type="file" v-model="contract_file0" placeholder="Скан акта выполненных работ"   class="form-control"  id="input_contract_file0"  @change="handleUpload0()"  ref="r_contract_file0" >
                </div>
                <div class="col-md-1">
                    .pdf
                </div>
        </div>

        <br />
        <span v-for="(item2, index) in modal_contract_list">
            <span v-if="item2.longtime_contract_is_signed>0 && this.orders_list_buh == 2 || this.orders_list_buh == 1">
                <p>&nbsp<i class="fa-regular fa-file-lines"></i>&nbsp<i>{{item2.name}}</i>&nbsp<span style="color: green;">&nbsp<i class="fa-solid fa-check"></i></span>
                &nbsp&nbsp&nbsp;&nbsp;<a :href="item2.upload_dir+item2.upload_file"  target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-file-pdf"></i></button></a></p>
            </span>
            <span v-else><a v-if="this.orders_list_buh == 2 || this.orders_list_buh == 1"  :href="'d-api/documents-order.php?contract_id='+item2.contract_id+'&counterparty_id='+this.contract_modal_counterparty_id+'&id='+this.modal_order_id+'&print_v='+this.print_v+'&paper=l'"  href="" target="_blank"  title="Не загружен скан договора"   class="nav-link">&nbsp<i class="fa-regular fa-file-lines"></i> {{item2.name}}&nbsp<span style="color: red;"><i class="fa-solid fa-circle-exclamation"></i></span></a><span v-if="this.orders_list_buh = '0'">{{item2.name}}</span></span>

            <div v-if="item2.longtime_contract_is_signed==0 && this.orders_list_buh == 2 || this.orders_list_buh == 1"   class="row"> 
                <div class="col-md-2" style="text-align: center; padding-top: 5px; color: green;">
                    <span v-if="item2.upload_file != '' &&  item2.longtime_contract==0"  ><i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;<a :href="item2.upload_dir+item2.upload_file"   target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary">&nbsp;&nbsp;<i class="fa-solid fa-print"></i>&nbsp;&nbsp;</button></a></span> 
                </div>

                <div class="col-md-5">
                    <input type="file" v-model="contract_file[index*4]" placeholder="Скан договора"   class="form-control"  :id="'input_contract_file_'+index+'_0'"  @change="handleUpload(index, 0)"    :ref="el => { if (el) divs[index*4] = el }"  >
                </div>
                <div class="col-md-1">
                    .pdf
                </div>
                <div class="col-md-1" style="text-align: right;">
                    от:
                </div>
                <div class="col-md-2">
                     <input type="date" v-model="date_contract[index]" placeholder="Дата"   class="form-control" @change="checkDate(index)"  >
                </div>
                <div class="col-md-1" style=" padding-top: 5px; color: red;"><span v-if="importfile1[index*4  +1 ]!=null "><b>*</b></span></div>
            </div>
            <br v-if="item2.longtime_contract_is_signed==0" \>

            <ul>
                <li v-if="item2.additions_count>=1 && this.orders_list_buh == 2 || this.orders_list_buh == 1" style=" list-style-type: none;"><div class="row">
                <div class="col-md-3">
                    <li v-if="item2.additions_count>=1" ><a :href="'d-api/documents-order.php?contract_id='+item2.contract_id+'&addition=1&id='+this.modal_order_id+'&print_v='+this.print_v+'&paper=l'"  title="Версия для печати"  target="_blank"  class="nav-link"> &nbsp&nbsp&nbsp<i class="fa-regular fa-file"></i>  &nbspПриложение 1 </a></li>
                </div>
                <div class="col-md-2" style="text-align: center; padding-top: 5px; color: green;">
                    <span v-if="item2.additions_list[0].addition==1 && item2.additions_list[0].date_contract != '' && item2.additions_list[0].upload_file != ''" ><i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;<a  :href="item2.additions_list[0].upload_dir+item2.additions_list[0].upload_file"  target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-file-pdf"></i></button></a></span>
                </div>
                <div class="col-md-6">
                    <input type="file" v-model="contract_file[index*4  +1 ]" placeholder="Скан договора"   class="form-control"  :id="'input_contract_file_'+index+'_1'"  @change="handleUpload(index, 1)"    :ref="el => { if (el) divs[index*4+1] = el }"  >
                </div>
                <div class="col-md-1">
                    .pdf
                </div>
                </div>
                </li>

                <li v-if="item2.additions_count>=2 && this.orders_list_buh == 2 || this.orders_list_buh == 1" style=" list-style-type: none;"><div class="row">
                <div class="col-md-3">
                <li v-if="item2.additions_count>=2"><a :href="'d-api/documents-order.php?contract_id='+item2.contract_id+'&addition=2&id='+this.modal_order_id+'&print_v='+this.print_v+'&paper=l'"  title="Версия для печати"   target="_blank"  class="nav-link"> &nbsp&nbsp&nbsp<i class="fa-regular fa-file"></i>  &nbspПриложение 2 </a></li>
                </div>
                <div class="col-md-2" style="text-align: center; padding-top: 5px; color: green;">
                    <span v-if="item2.additions_list[1].addition==2 &&  item2.additions_list[1].date_contract != '' && item2.additions_list[1].upload_file != ''" ><i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;<a :href="item2.additions_list[1].upload_dir+item2.additions_list[1].upload_file"  target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-file-pdf"></i></button></a></span>
                </div>
                <div class="col-md-6">
                    <input type="file" v-model="contract_file[index*4  +2 ]" placeholder="Скан договора"   class="form-control"  :id="'input_contract_file_'+index+'_2'"  @change="handleUpload(index, 2)"    :ref="el => { if (el) divs[index*4+2] = el }"  >
                </div>
                <div class="col-md-1">
                    .pdf
                </div>
                </div>
                </li>
<br />



                <!--<li v-if="item2.additions_count>=3" style=" list-style-type: none;"><div class="row">-->
                <li style=" list-style-type: none;"><div class="row">
                <div class="col-md-4">
                <li ><a :href="'d-api/documents-order.php?contract_id='+item2.contract_id+'&addition=0&id='+this.modal_order_id+'&print_v='+this.print_v+'&paper=l'"  title="Версия для печати"  target="_blank"  class="nav-link"> &nbsp&nbsp&nbsp<i class="fa-regular fa-file"></i>  &nbsp Заявление / Согласие на обработку персональных данных
                 </a></li>
                </div>
                <div class="col-md-1" style="text-align: center; padding-top: 5px; color: green;">
                    <span  v-if="item2.additions_list[2].addition==0 &&  item2.additions_list[2].date_contract != '' && item2.additions_list[2].upload_file != ''"   ><i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;<a :href="item2.additions_list[2].upload_dir+item2.additions_list[2].upload_file"  target="_blank" title="Сохраненный скан"  style="padding-top: 0px; padding-bottom: 15px;"><button type="button" class="btn btn-secondary"><i class="fa-solid fa-file-pdf"></i></button></a></span>
                </div>
                <div class="col-md-6">
                    <input type="file" v-model="contract_file[index][index*4  +3]" placeholder="Скан договора"   class="form-control"  :id="'input_contract_file_'+index+'_3'"  @change="handleUpload(index, 3)"   :ref="el => { if (el) divs[index*4+3] = el }" >
                </div>
                <div class="col-md-1">
                    .pdf
                </div>
                </div>
                </li>
            </ul>
        </span>

            <div class="modal-footer"></div>
            <div class="row" v-if="role!='counterparty'"  style="text-align: right">
              <div class="col-md-11" style="text-align: right; padding-top: 5px;"><small>
                <input v-model="print_v" type="radio" value="false" class="form-check-input" id="print_Check0" > <label class="form-check-label" for="print_Check0"> скан-копия </label>&nbsp;&nbsp;&nbsp;&nbsp; 
                <input v-model="print_v" type="radio" value="true"  class="form-check-input" id="print_Check1" > <label class="form-check-label" for="print_Check1"> версия для печати </label>&nbsp;&nbsp;&nbsp;&nbsp;
                <input v-model="print_v" type="radio" value="edit"  class="form-check-input" id="print_Check3"> <label class="form-check-label" for="print_Check3"> редактор</label>
              </small></div>
          </div>
        </div>
      <div class="modal-footer">
        <span v-if="this.date_exist"> 
            <button  class="btn btn-primary"    @click="contractUpload()"  data-bs-dismiss="modal"  > Сохранить </button>
        </span>
        <span v-else> 
            <button  class="btn btn-primary"     data-bs-dismiss="modal" disabled > Сохранить </button>
        </span>
        &nbsp<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Закрыть </button>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade " id="statusPayModal" tabindex="-1" aria-labelledby="statusPayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="ModalLabel">Счет оплачен &nbsp;&nbsp; {{modal_date_order}}/{{modal_number}} &nbsp;&nbsp; <b>CED-{{modal_order_id}}</b> </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" align="left">

        <p><input type="text" v-model="payment_receipt" placeholder="Сумма"   class="form-control" ></p>
        <p><input type="date" v-model="payment_receipt_date" placeholder="Дата"   class="form-control" ></p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Отмена </button>
        <button v-if="1" @click="newStatus(0, 0, 2)"  type="button" class="btn btn-primary" data-bs-dismiss="modal"> &nbsp&nbspok&nbsp&nbsp </button>
        <button v-else  type="button" class="btn btn-primary" data-bs-dismiss="modal" disabled> &nbsp&nbspok&nbsp&nbsp </button>
      </div>
    </div>
  </div>
</div>



<div class="row">
<div class="col"> </div>
<div  v-if="numPages>1" aria-label="Page navigation" class="btn-toolbar col"  aria-label="pages"   style="text-align: center;">
  <ul class="pagination" >
    <li v-if="page>1" class="page-item">
      <button  @click="page_go(this.page-1)"  class="page-link" aria-label="Previous"> <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span> </button>
    </li>
    <li v-if="page>1"class="page-item"><button @click="page_go(1)"  class="page-link" >1</button></li>
    <li v-if="page>2" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page-2>2"class="page-item"><button @click="page_go(this.page-2)"  class="page-link" >{{page-2}}</button></li>
    <li v-if="page-1>2"class="page-item"><button @click="page_go(this.page-1)"  class="page-link" >{{page-1}}</button></li>
    <li class="page-item active" aria-current="page"><button   class="page-link">{{page}}</button></li>
    <li v-if="page+1<numPages"class="page-item"><button @click="page_go(this.page+1)"  class="page-link" >{{page+1}}</button></li>
    <li v-if="page+2<numPages"  class="page-item"><button @click="page_go(this.page+2)"  class="page-link" >{{page+2}}</button></li>
    <li v-if="page+3<numPages" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page<numPages" class="page-item"><button @click="page_go(this.numPages)"  class="page-link" >{{numPages}}</button></li>
    <li v-if="page<numPages" class="page-item">
      <button  @click="page_go(this.page+1)"  class="page-link"  aria-label="Next">  <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>  </button>
    </li>
  </ul>
</div>
<div class="col-2"> </div>
</div>

<div class="row">
<small class="col-1" >
		    <select  v-model="year_tab"  id="input_status" class="form-select form-select-sm"  aria-label="Статус"  >
    		    <option value="0"> - год - </option>
    		    <option v-for="item in year_arr" :value="item.name">{{item.name}}&nbsp; </option>
		</select>
</small>    
<small class="col"><ul class="nav nav-tabs" >
    <li v-for="(item, index) in mon_arr"  class="nav-item">
        <a :class="item.t_class" @click="tab_go(index)">{{item.name}}</a>
    </li>
    </ul></small>
</div>

    </div>`
};




