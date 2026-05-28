
var LstreamListRedirect = {

  data: function () {
    return {
     }
   },


   mounted() {

    this.$router.push({ name: "lstream_list" }) 

    },


	template: `
	<div><navigation></navigation><h3></h3>
    </div>`
};
