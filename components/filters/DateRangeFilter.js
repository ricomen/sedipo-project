/** @stub — date1/date2. См. orders_list, groups_list */
const SedDateRangeFilter = {
  props: { date1: String, date2: String },
  emits: ['update:date1', 'update:date2', 'change'],
  template: `
    <span>
      <input type="date" :value="date1" @input="$emit('update:date1', $event.target.value); $emit('change')">
      —
      <input type="date" :value="date2" @input="$emit('update:date2', $event.target.value); $emit('change')">
    </span>
  `,
};
