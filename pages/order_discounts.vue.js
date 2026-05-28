var OrderDiscounts = {
  data: function () {
    return {
      role: '',
      order_id: 0,
      info: [],
      info2: [],
      info4: [],
      info3: [],
      info5: [],
      info9: [],
      date_order: '',
      number_order: '',
      status_id: '',
      counterparty_id: 0,
      course: 0,
      price: [],
      a_course_id: [],
      print_v: 'true',
      variation: 1,
      variation1_title: '',
      msg_result: '',
      message: ''
     }
   },


   mounted() {
    this.order_id = Number(this.$route.params.orderid)
    this.a_counterparty_id = Number(this.$route.params.counterpartyid)
    if(isNaN(this.a_counterparty_id))
        this.a_counterparty_id = 0
    
    if(this.order_id>0) { 
        axios
    	    .post(JsonApiURL+'api/orders_json.php', {object: {objectId: this.order_id, sessionId: session_t.sessionId } })
            .then(response => { 
        	    this.info = response.data
	            this.role = this.info.role
		        this.date_order = this.info.result.date_order
		        this.number_order = this.info.result.number
        	    this.counterparty_id  = this.info.result.counterparty_id
                this.status_id = this.info.result.status_id
                this.date_begin = this.info.result.date_begin
                this.date_end = this.info.result.date_end

                if( this.role=='counterparty' )
                        this.print_v = 'false'

                if(this.counterparty_id > 0) {
    	            axios
    		     .post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
    		     .then(response3 => { 
        	         console.log(response3)
        	         this.info3 = response3.data
    		     })
    		     .catch(error => {
            	         console.log(error.response)
        	     })
                }

    	        axios
    		    .post(JsonApiURL+'api/orders_json.php', {order_course: {objectId: this.order_id, sessionId: session_t.sessionId } })
    		    .then(response5 => { 
        	         console.log(response5)
        	         this.info5 = response5.data
                     for (item in this.info5.list) {
                         this.price.push(this.info5.list[item].price2)
                         this.a_course_id.push(this.info5.list[item].course_id)
                         //this.p = this.info5.consulting_list.length
                         //this.balance = this.info5.balance
                    }
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
    save(){
        axios
          .post(JsonApiURL+'api/orders_json.php', {discounts_save: {objectId:  this.order_id,  course_id: this.a_course_id, price: this.price,  sessionId: session_t.sessionId }})
          .then(response => { 
	         console.log(response)
                //this.info9 = response.data
                    if(response.data.status==0) {
                        this.$router.push({ name: 'orders_list', params: {counterpartyid: this.a_counterparty_id  }}) 
                    }
            })
          .catch(error => {
              console.log(error.response)
            })

    }


},


	template: `<div><navigation></navigation><h3>Скидки по заявке</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
<div class="container">

    <br />
    <h4  v-if="role!='counterparty' && counterparty_id>0"   style="text-align: right;" > {{info3.result.name}} </h4>


 <div class="row justify-content-between">
    <div class="col-8">
  <div align="left">
  <h5>{{date_order}}/{{number_order}}</h5>
  </div>
    </div>
    <div class="col-4">
    </div>
  </div>


    
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Курс </th>
          <th width="10%" scope="col" style="text-align: center;"> Стоимость руб. </th>
          <!--<th width="10%" scope="col" style="text-align: center;"> Скидка % </th>-->
          <!--<th width="10%" scope="col" style="text-align: center;"> Итого </th>-->
          <th width="10%" scope="col" style="text-align: center;"> Со скидкой </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="(item, index) in info5.list"  align="left">  
            <td>
                {{item.shortname}} ({{item.hours}} ч.)  
            </td>
            <td style="text-align: center;">
                {{item.price}}   
            </td>
            <!--<td>
              <input v-model="price_p"   class="form-control" id="input_price_p"   type="text"  >
            </td>-->
            <td>
              <input v-model="price[index]"   class="form-control" :id="'input_price'+index"   type="text"  >
            </td>


        </tr>
  </tbody>
  </table>



  <div align="right">
    <button  class="btn btn-primary"    @click="save()"> Сохранить </button>
      &nbsp<router-link  :to="{ name: 'orders_list', params: {counterpartyid: this.a_counterparty_id}}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
  </div>

	</div>
  </div>
</div>`
};




