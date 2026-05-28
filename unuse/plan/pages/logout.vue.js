var Logout = {
  data: function () {
    return {
	info: ''
     }
   },


   mounted() {
//    updated() {

        axios
        .post(JsonApiURL+'account_json.php', {logout: {sessionId: session_t.sessionId}})
        .then(response => { 
            this.info = response.data
	    session_t.sessionId = ''
	    window.location.href = '/#/auth'
         })
        .catch(error => {
              console.log(error.response)
            })

    },

	template: `<div><navigation></navigation><h3></h3>

    </div>`
};