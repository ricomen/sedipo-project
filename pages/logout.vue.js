var Logout = {
  data: function () {
    return {
	info: ''
     }
   },


   mounted() {

        axios
        .post(JsonApiURL+'api/auth_json.php', {logout: {sessionId: session_t.sessionId}}, {withCredentials: true})
        .then(response => { 
            this.info = response.data
	    //session_t.sessionId = ''
	    //window.location.href = '/'
	    window.location.href = '/#/login'
         })
        .catch(error => {
              console.log(error.response)
            })

    },

	template: `<div><navigation></navigation><h3></h3>

    </div>`
};