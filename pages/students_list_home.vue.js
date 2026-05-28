//var UserListHome = UserList

var UserListHome = {

  data: function () {
    return {
     }
   },

   mounted() {

    this.$router.push({ name: "students_list" }) 

    },


	template: `
	<div><navigation></navigation><h3></h3>
    </div>`
};
