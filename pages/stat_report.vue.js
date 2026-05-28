var StatReport = {
  data: function () {
    return {
      role: '',    
       info9: [],
       years: [],
       report_template: 0,
       report_date: 0,
     }
   },


   mounted() {
        axios
          .post(JsonApiURL+'api/auth_json.php', {info: { sessionId: session_t.sessionId } })
          .then(response => { 
            this.info9 = response.data
             this.role = this.info9.role
        })
          .catch(error => {
              console.log(error.response)
        })

        var  years = [];
        var year1 = new Date().getFullYear()
        for (var i = 0; i <= 5; i++) {
           years.push(year1 - i );
        }
        this.years = years;

  },


  methods: {
      
  
    
},


	template: `<div><navigation></navigation><h3>Федеральное статистическое наблюдение</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <div class="row">
        <div class="col">

          <select  v-model="report_date"  >
             <option value="0"> - Период - </option>
             <option v-for="item in years" :value="item">{{item}}</option>
          </select>
        </div>
        <div class="col">
          <select  v-model="report_template"  >
             <option value="1"> По основным программам профессионального обучения </option>
             <option value="2"> По дополнительным профессиональным программам   </option>
          </select>
        </div>
        <div class="col">
        </div>
        <div class="col-4" >
            <div v-if="report_date!='' && report_template>0" align="right"><a :href="'reports/stat_report.php?year='+report_date+'&report_template='+report_template" target="_blank"><button class="btn btn-primary"><i class="fa-solid fa-file-excel"></i> Скачать отчет  </button></a></div>
            <div v-else align="right"><a :href="'reports/stat_report.php?year='+report_date+'&report_template='+report_template" target="_blank"><button class="btn btn-outline-secondary"><i class="fa-solid fa-file-excel"></i> Скачать отчет  </button></a></div>
        </div>
    </div>
    <br />
    </div>`
};




