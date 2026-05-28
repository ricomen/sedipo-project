/**
 * Кнопка создания сущности (router-link).
 * Props: to, label, icon (fontawesome class без fa-), variant
 */
const SedCreateEntityButton = {
  props: {
    to: { type: [String, Object], required: true },
    label: { type: String, default: 'Создать' },
    icon: { type: String, default: 'fa-file-circle-plus' },
    variant: { type: String, default: 'light' },
  },
  template: `
    <div style="text-align: left; padding-top: 15px;">
      <button type="button" :class="'btn btn-' + variant">
        <router-link :to="to" :title="label">
          <div><nobr><i :class="'fa-solid ' + icon"></i> {{ label }}</nobr></div>
        </router-link>
      </button>
    </div>
  `,
};
