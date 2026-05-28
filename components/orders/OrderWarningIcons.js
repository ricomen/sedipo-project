/** @stub — предупреждения в строке заявки */
const SedOrderWarningIcons = {
  props: { item: { type: Object, required: true } },
  template: `
    <span>
      <span v-if="item.count == 0" style="color: red;" title="Необходимо сформировать список">
        <i class="fa-solid fa-circle-exclamation"></i>
      </span>
      <span v-if="!item.snils_check_sum && item.count > 0" style="color: red;" title="Некорректный СНИЛС">
        <i class="fa-solid fa-circle-exclamation"></i>
      </span>
    </span>
  `,
};
