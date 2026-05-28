/**
 * Оболочка страницы: navigation + заголовок + сообщение об ошибке + guard по правам.
 *
 * Props: title, message, privilege, privilegeLevel (1|2), showNav (default true)
 */
const SedPageShell = {
  props: {
    title: { type: String, default: '' },
    message: { type: String, default: '' },
    privilege: { type: String, default: '' },
    privilegeLevel: { type: Number, default: 1 },
    showNav: { type: Boolean, default: true },
  },
  computed: {
    allowed() {
      if (!this.privilege) return true;
      const p = typeof session_role_privileges !== 'undefined'
        ? session_role_privileges[this.privilege]
        : 2;
      const need = this.privilegeLevel;
      return p === need || p === 2 || (need === 1 && p === 1);
    },
  },
  template: `
    <div v-if="allowed">
      <navigation v-if="showNav"></navigation>
      <h3 v-if="title">{{ title }}</h3>
      <h4 v-if="message" style="text-align: center; color: red;">{{ message }}</h4>
      <slot></slot>
    </div>
  `,
};
