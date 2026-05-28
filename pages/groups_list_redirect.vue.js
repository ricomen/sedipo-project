

var GroupsListRedirect = {

  data: function () {
    return {

     }
   },


   mounted() {

      this.$router.push({ name: "groups_list" }) 

    },





	template: `
	<div><navigation></navigation><h3></h3>
    </div>`
};
