/**
 * Кнопка «Закрыть» внизу формы — возврат к списку.
 * Props: to (строка или объект route), label
 */
const SedPageFooterClose = {
  props: {
    to: { type: [String, Object], required: true },
    label: { type: String, default: 'Закрыть' },
  },
  template: `
    <div class="mb-2">
      <div align="right">
        <router-link :to="to">
          <button type="button" class="btn btn-outline-primary">{{ label }}</button>
        </router-link>
      </div>
    </div>
  `,
};
