/** @stub — course_category_json list */
const SedCategorySelect = {
  props: { modelValue: [String, Number], options: { type: Array, default: () => [] }, showEmpty: { type: Boolean, default: true } },
  emits: ['update:modelValue', 'change'],
  template: `
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value); $emit('change')">
      <option v-if="showEmpty" value="0">— Категория —</option>
      <option v-for="o in options" :key="o.category_id" :value="o.category_id">{{ o.name }}</option>
    </select>
  `,
};
