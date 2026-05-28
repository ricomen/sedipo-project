/** @stub — поле СНИЛС */
const SedSnilsField = {
  props: { modelValue: { type: String, default: '' }, invalid: { type: Boolean, default: false } },
  emits: ['update:modelValue'],
  template: `
    <input type="text" :value="modelValue" placeholder="СНИЛС" @input="$emit('update:modelValue', $event.target.value)">
    <span v-if="invalid" style="color: red;" title="Некорректный СНИЛС"><i class="fa-solid fa-circle-exclamation"></i></span>
  `,
};
