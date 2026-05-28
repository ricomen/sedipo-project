/** @stub — autocomplete контрагента */
const SedCounterpartyPicker = {
  props: { modelValue: String, suggestions: { type: Array, default: () => [] } },
  emits: ['update:modelValue', 'select'],
  template: `<input type="text" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" placeholder="Контрагент">`,
};
