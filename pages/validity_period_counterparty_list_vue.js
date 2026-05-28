var ValidityPeriodCounterpartyList  = {
  data: function () {
    return {
      info: [],
      role: '',
      info9: [],
      counterparty_id: 0,
      message: '',
      c_hidden: [],
      IS_SUBDIVISION: 0,
     }
   },


   mounted() {

    axios
//      .post(JsonApiURL+'api/students_json.php', {users_validity_period: {   counterparty_id: this.counterparty_id, month: 1, month_stop: 1,  sessionId: session_t.sessionId }})
      .post(JsonApiURL+'analytics/validity_period.php', {validity_period: {   counterparty_id: 0, month: 1, month_stop: 1,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role

            var list_c = this.info.list
            for (var i = 0; i < list_c.length; i++) {
                this.c_hidden[i] = 0 
            }

            console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            });





   },
   
   methods: {   

    report( counterparty_id ){
         var argj = [ counterparty_id,   session_t.sessionId];
         window.open(JsonApiURL+'analytics/validity_period.php?search='+argj.join(), '_blank');
    },

    report_hidden( counterparty_id, index ){
        this.c_hidden[index] = 1
    }

   },
   
   

 template: `<div><navigation></navigation><h3> Истекает срок действия документов </h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

  <div align="left">
    <br />
    <!--<h4 v-if="counterparty_id>0"  style="text-align: right;" > {{info3.result.shortname}} </h4>-->

    <table v-if="counterparty_id==0"   class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Организация </th>
          <th scope="col">  </th>
          <th scope="col">  </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="(item, index) in info.list"  align="left" style="float: none;" >  
            <td v-if="c_hidden[index]==0"><router-link  :to="{ name: 'validity_period', params: { counterpartyid: item.counterparty_id }}" :title="item.counterparty_title" class="nav-link active" >{{item.counterparty_shortname}} - <nobr>{{item.count}} чел.</nobr></router-link></td>
            <td v-if="c_hidden[index]==0" width="15%" align="left"><button @click="report(item.counterparty_id)" class="btn btn-outline-primary btn-sm"><nobr><i class="fa-solid fa-file-pdf"></i> Скачать список</nobr></button></td>
            <td v-if="c_hidden[index]==0" width="5%"  align="left"><button @click="report_hidden(item.counterparty_id, index)" class="btn btn-link btn-sm" title="Скрыть"> <i class="fa-regular fa-eye-slash"></i> </button></td>
        </tr>
  </tbody>
  </table>



   </div>`
} ;
  