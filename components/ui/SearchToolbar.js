/**
 * Поиск + сброс фильтров.
 * Props: modelValue (v-model), placeholder, inputSize
 * Emits: update:modelValue, search, reset
 */
const SedSearchToolbar = {
  props: {
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Поиск' },
    inputSize: { type: Number, default: 50 },
  },
  emits: ['update:modelValue', 'search', 'reset'],
  methods: {
    onInput(e) {
      this.$emit('update:modelValue', e.target.value);
    },
  },
  template: `
    <table border="0">
      <tr>
        <td style="padding-left: 0; padding-right: 5px;">
          <input
            type="text"
            :value="modelValue"
            :placeholder="placeholder"
            :size="inputSize"
            @input="onInput"
            @keyup.enter="$emit('search')"
          >
        </td>
        <td style="padding-left: 15px; padding-right: 15px;" align="left">
          <button type="button" @click="$emit('search')">
            &nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;
          </button>
        </td>
        <td style="padding-left: 15px;" align="right">
          <button type="button" title="Сбросить все фильтры" @click="$emit('reset')">
            &nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;
          </button>
        </td>
      </tr>
    </table>
  `,
};
