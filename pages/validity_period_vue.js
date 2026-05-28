var ValidityPeriod  = {
  data: function () {
    return {
      info: [],
      info3: [],
      role: '',
      info9: [],
      counterparty_id: 0,
      message: '',
      IS_SUBDIVISION: 0,

     }
   },


   mounted() {
    this.counterparty_id = Number(this.$route.params.counterpartyid)
    if(isNaN(this.counterparty_id))
                this.counterparty_id = 0

    axios
//      .post(JsonApiURL+'api/students_json.php', {users_validity_period: {   counterparty_id: this.counterparty_id, month: 1, month_stop: 1,  sessionId: session_t.sessionId }})
      .post(JsonApiURL+'analytics/validity_period.php', {validity_period: {   counterparty_id: this.counterparty_id, month: 1, month_stop: 1,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role

            console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            });

       if(this.counterparty_id > 0) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
    		.then(response3 => { 
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
       }

   },
   
   methods: {   
    report( counterparty_id ){
         var argj = [ counterparty_id,   session_t.sessionId];
         window.open(JsonApiURL+'analytics/validity_period.php?search='+argj.join(), '_blank');
    }

   },
   
   

 template: `<div><navigation></navigation><h4> Истекает срок действия документов на {{ new Date().toLocaleDateString('ru-RU')}} </h4> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

  <div align="left">
    <br />
    <h4 v-if="counterparty_id>0"  style="text-align: right;" > {{info3.result.shortname}} </h4>


    <table v-if="counterparty_id>0"   class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <!--<th  v-if="counterparty_id==0 && role!='counterparty'" scope="col"> Организация </th>-->
          <th  v-if="IS_SUBDIVISION"  scope="col"> Подразделение </th>
          <th  scope="col"> Должность </th>
          <th scope="col"> Курс</th>
          <th scope="col"> № документа</th>
          <th scope="col"> Дата </th>
          <th scope="col"> Дата окончания действия документа </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left" style="float: none;" >  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <!--<td v-if="a_counterparty_id==0 && role!='counterparty'" >{{item.organization_name}}</td>-->
            <td  v-if="IS_SUBDIVISION"  >{{item.subdivizion}}</td>
            <td >{{item.job_title}}</td>
            <td >{{item.course}}</td>
            <td >{{item.certificate}}</td>
            <td >{{item.date_convert}}</td>
            <td >{{item.date_finish_convert}}</td>
          <th scope="col"> </th>
        </tr>
  </tbody>
  </table>

  <div align="right"><button @click="report(counterparty_id)" class="btn btn-outline-primary btn-sm"><nobr><i class="fa-solid fa-file-pdf"></i> Скачать список</nobr></button></div>
  <br />
   </div>`
} ;
  