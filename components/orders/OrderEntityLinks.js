/** @stub — ссылки на items / groups / lstream / discounts */
const SedOrderEntityLinks = {
  props: { orderId: Number, counterpartyId: { type: Number, default: 0 }, item: { type: Object, required: true } },
  template: `<div class="sed-order-entity-links"><slot :order-id="orderId" :item="item"></slot></div>`,
};
