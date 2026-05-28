/** @stub — options из status_json */
const SedStatusSelect = {
  props: { modelValue: [String, Number], options: { type: Array, default: () => [] } },
  emits: ['update:modelValue', 'change'],
  template: `
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value); $emit('change')">
      <option value="0">— статус —</option>
      <option v-for="o in options" :key="o.status_id" :value="o.status_id">{{ o.name }}</option>
    </select>
  `,
};
