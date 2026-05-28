var P_item = { 
  data: function () {
    return {
      info: [],
      rprg_id: 0,
      item_id: 0,
      parent: 0,
      name: '',
      hours_o: '',
      hours_z: '',
      hours_l: '',
      hours_p: '',
      hours_s: '',
      hours_a: '',
      hours_k: '',
      form: '',
      description: '',
      np: '',
      bib: '',
      s_fl: '',
      message: ''
     }
   },

  updated() {
//        window.scrollTo(0, 250 );
  },


   mounted() {
    this.item_id = this.$route.params.p_id
    this.parent = this.$route.params.parent
    this.rprg_id = this.$route.params.id

        window.scrollTo(0, 250 );



    if(this.item_id >0){
      axios
      .post(JsonApiURL+'api_json.php', {p_item_detalies: {p_Id: this.item_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
            this.rprg_id = response.data.p.rprg_id
	    this.name = response.data.p.name
	    this.hours_o = response.data.p.hours_o
	    this.hours_z = response.data.p.hours_z
	    this.hours_l = response.data.p.hours_l
	    this.hours_p = response.data.p.hours_p
	    this.hours_s = response.data.p.hours_s
	    this.hours_a = response.data.p.hours_a
	    this.form = response.data.p.form
	    this.description  = response.data.p.description
	    this.np  = response.data.p.np
	    this.bib  = response.data.p.bib
	    this.s_fl  = response.data.p.s_fl
       })
      .catch(error => {
              console.log(error.response)
            })
     }
  },


  methods: {

        itemSave() {
	this.rprg_id = this.$route.params.id

	    axios
            .post(JsonApiURL+'api_json.php', {p_item_save: {p_Id: this.item_id, Id: this.rprg_id, parent: this.parent,  name: this.name, form: this.form, hours_o: this.hours_o, hours_z: this.hours_z, hours_l: this.hours_l, hours_p: this.hours_p, hours_s: this.hours_s, hours_a: this.hours_a, description: this.description, np: this.np, bib: this.bib,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)

		this.item_id = response.data.rprg.p_Id

                if(this.rprg_id>0) {
		    this.$router.push({ name: 'edit', params: {id: this.rprg_id, pos: 1600 }})

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




	template: `<div><navigation></navigation> <h3 v-if="parent==0">Модуль</h3><h3 v-else>Тема</h3>
<div align="left">

<span v-if="parent==0">
<p><span  class="os">Название модуля:</span> <input type="text" class="form-control" placeholder="Название модуля" v-model="name" ></p>
<h4>Очная форма </h4>
<div class="row">
<div class="col">
<p><span class="os">Количество часов Лекций:</span> {{hours_l}}</p>
</div>
<div class="col">
<p><span class="os">Количество часов Пр. занятий:</span> {{hours_p}}</p>
</div>
</div>

<h4>Заочная форма </h4>
<div class="row">
<div class="col">
<p><span class="os">Количество часов Теории:</span> {{hours_z}}</p>
</div>
<div class="col">
<p><span class="os">Количество часов Пр. занятий:</span> {{hours_o}}</p>
</div>
</div>

<div class="row">
<div class="col">
<p><span class="os">Количество часов {{s_fl}}:</span> {{hours_s}}</p>
</div>
</div>


<div class="row">
<div class="col">
<p><span v-if="parent==0" class="os">Форма контроля:</span> {{form}}, 
<span class="os">часов:</span> {{hours_a}}</p>
</div>
</div>

</span>
<span v-else>
<p><span class="os">Название темы:</span> <input type="text" class="form-control" placeholder="Название темы" v-model="name" ></p>
<h4>Очная форма </h4>
<div class="row">
<div class="col">
<p><span class="os">Количество часов Лекций (теории):</span> <input type="text" class="form-control" placeholder="Количество часов Лекций" v-model="hours_l" > </p>
</div>
<div class="col">
<p><span class="os">Количество часов Пр. занятий:</span> <input type="text" class="form-control" placeholder="Количество часов Пр. занятий" v-model="hours_p" > </p>
</div>
</div>

<h4>Зачная форма </h4>
<div class="row">
<div class="col">
<p><span class="os">Количество часов Теории:</span> <input type="text" class="form-control" placeholder="Количество часов Лекций" v-model="hours_z" > </p>
</div>
<div class="col">
<p><span class="os">Количество часов Пр. занятий:</span> <input type="text" class="form-control" placeholder="Количество часов Пр. занятий" v-model="hours_o" > </p>
</div>
</div>

<div class="row">
<div class="col">
<p><span class="os">{{s_fl}} - Количество часов:</span> <input type="text" class="form-control" placeholder="Количество часов Стажировки" v-model="hours_s" > </p>
</div>
</div>


<!--<div class="row">
<div class="col-8">
<p><span class="os">Форма контроля:</span>  <select class="form-select" aria-label="Форма контроля" v-model="form" ><option value=''></option><option value='Опрос'>Опрос</option><option value='Кейс'>Кейс</option><option value='Зачет'>Зачет</option><option value='Тест'>Тест</option></select></p>
</div>
<div class="col">
<p><span class="os">часов:</span> <input type="text" class="form-control" placeholder="Аттестации" v-model="hours_a" ></p>
</div>
</div>-->

</span>


<p><span class="os">Содержание:</span>  <textarea class="form-control" v-model="description"  rows="15" > </textarea></p>

<span v-if="parent==0">
<p><span class="os">Нормативно правовая информация:</span>  <textarea class="form-control" v-model="np"  rows="15" > </textarea></p>
<p><span class="os">Литература:</span>  <textarea class="form-control" v-model="bib"  rows="15" > </textarea></p>
</span>

<router-link :to="{ name: 'edit', params: {id: this.rprg_id, pos: 1600}}"><button class="red"> Отмена </button></router-link>
<button class="green" @click="itemSave()"> Сохранить </button>

</div></div>`
};