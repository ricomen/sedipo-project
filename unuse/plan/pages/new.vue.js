var New = { 
  data: function () {
    return {
      info: [],
      rprg_id: 0,
      name: '',
      hours: '',
      form: '',
      message: ''
     }
   },


  methods: {
        rprgSave() {

	    axios
            .post(JsonApiURL+'api_json.php', {rprg_save: {Id: 0, name: this.name, form: this.form, hours: this.hours, p1: '', p2: '',  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

		this.rprg_id = response.data.rprg.Id

                if(this.rprg_id>0) {
		    this.$router.push({ name: 'edit', params: {id: this.rprg_id }})

                }
                else {
                    this.message = response.data.error
                }
            })
            .catch(error => {
              console.log(error.response)
            })

        }



  },



	template: `<div><navigation></navigation> <h3>Сформировать новую рабочую программу</h3>
  <h4 style="text-align: center; color: red;">{{message}}</h4>
<div align="left">

<p><span class="os">Название программы:</span> <input type="text" class="form-control" placeholder="Название программы" v-model="name" ></p>
<p><span class="os">Нормативный срок освоения программы:</span>  [часов] <input type="text" class="form-control" placeholder="Нормативный срок освоения программы" v-model="hours" ></p>

<button class="blue" @click="rprgSave()">Далее > </button>

</div></div>`
};