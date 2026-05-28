/** @stub — иконка подсказки рядом с полем */
const SedFormFieldHelp = {
  props: { text: { type: String, default: '' } },
  template: `
    <a v-if="text" :title="text"><span class="text-primary"><i class="fa-regular fa-circle-question"></i></span></a>
  `,
};
