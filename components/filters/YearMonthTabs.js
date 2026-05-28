/** @stub — year_arr / mon_arr. См. orders_list */
const SedYearMonthTabs = {
  props: { years: Array, months: Array, year: Number, month: Number },
  emits: ['update:year', 'update:month'],
  template: `<div class="sed-year-month-tabs"><slot></slot></div>`,
};
