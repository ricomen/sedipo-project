var Calendar = {
  data: function () {
    return {
      role: '',    
      moodle_path: '',
      info: [],
      datesearch: '',
      datesearch2: '',
      total: 0,
     }
   },


   mounted() {
    axios
      .post(JsonApiURL+'analytics/calendar.php', {calendar: {date1: '', date2: '',   sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
             console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            })

  },


  methods: {
      
    search_go() {

    axios
      .post(JsonApiURL+'analytics/calendar.php', {calendar: {date1: this.datesearch, date2: this.datesearch2,   sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
             console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            })
    },


    report(){
         var argj = [ this.datesearch, this.datesearch2,   session_t.sessionId];
         window.open(JsonApiURL+'analytics/calendar.php?search='+argj.join(), '_blank');
    },

},


	template: `<div><navigation></navigation><h3>Расписание</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

{{datesearch}} {{datesearch2}}
    <br />
    <table >
        <tr  align="left">
            <td  style="padding-left: 0px;  padding-right: 10px;" >c </td>
            <td  style="padding-left: 0px;  padding-right: 15px;" ><input type="date" v-model="datesearch" placeholder="Дата"  ></td>
            <td style="padding-left: 5px;  padding-right: 5px;"> по </td>
            <td  style="padding-left: 0px;  padding-right: 15px;" ><input type="date" v-model="datesearch2" placeholder="Дата"  ></td>

        <td  style="padding-left: 15px;  padding-right: 0px; text-align: right;" > <button  @click="search_go(0)">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Показать&nbsp;</button> </td>
        
        </tr>
        
    </table>
    <hr />
    <div class="row">
        <div class="col">
         <div v-html="info.result"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
        </div>
        <div class="col">
        </div>
        <div class="col-2" >
            <div   align="right"><button @click="report()" class="btn btn-outline-primary"><i class="fa-solid fa-file-pdf"></i> Скачать </button></div>
            <!--<div v-else align="right"><button @click="report()" class="btn btn-outline-secondary"><i class="fa-solid fa-file-excel"></i> Скачать </button></div>-->
        </div>
    </div>
    <hr />


    </div>`
};




