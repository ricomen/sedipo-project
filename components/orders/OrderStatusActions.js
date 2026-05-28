/** @stub — кнопки смены статуса заявки */
const SedOrderStatusActions = {
  props: { item: { type: Object, required: true }, role: { type: String, default: '' } },
  emits: ['action', 'action2', 'preset'],
  template: `<div class="sed-order-status-actions"><slot :item="item"></slot></div>`,
};
