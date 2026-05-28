/** @stub — учебные центры (counterparty type=1) */
const SedPerformerSelect = {
  props: { modelValue: [String, Number], options: { type: Array, default: () => [] } },
  emits: ['update:modelValue', 'change'],
  template: `
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value); $emit('change')">
      <option value="0">— Учебный центр —</option>
      <option v-for="o in options" :key="o.counterparty_id" :value="o.counterparty_id">{{ o.name }}</option>
    </select>
  `,
};
