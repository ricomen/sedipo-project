var Home = {
  data: function () {
    return {
	info9: '',
        role: '',
     }
   },


   mounted() {
    this.session = session_t

    axios
      .post(JsonApiURL+'api/payment_1c.php', {payment_1c: {sessionId: session_t.sessionId }})
      .then(response => { 
            this.info9 = response.data
            this.role = this.info9.role
       })
      .catch(error => {
              console.log(error.response)
        })

    },

	template: `
	<div><navigation></navigation><h3></h3>
    </div>`
};