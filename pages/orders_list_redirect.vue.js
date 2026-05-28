

var OrdersListRedirect = {

  data: function () {
    return {
      a_counterparty_id: 0,
      a_order_id: 0,
      a_sort: 0,
    }
   },


   mounted() {
        //this.a_counterparty_id = Number(this.$route.params.counterpartyid)
        //this.a_order_id = Number(this.$route.params.orderid)
        //this.a_sort = Number(this.$route.params.sort)

//        this.$router.push({name: 'orders_list', params: { counterpartyid: a_counterparty_id }})
        this.$router.push({name: 'orders_list'})
    },

	template: `
	<div><navigation></navigation><h3></h3>
    </div>`
};
