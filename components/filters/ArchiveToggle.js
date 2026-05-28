/** @stub — активные / архив */
const SedArchiveToggle = {
  props: { modelValue: { type: [String, Number], default: 0 } },
  emits: ['update:modelValue', 'change'],
  template: `
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value); $emit('change')">
      <option value="0">Активные записи</option>
      <option value="1">Архив</option>
    </select>
  `,
};
