/**
 * Показывает слот только при достаточном уровне права.
 * Props: name (ключ role_privileges), minLevel (1|2)
 */
const SedPrivilegeGuard = {
  props: {
    name: { type: String, required: true },
    minLevel: { type: Number, default: 1 },
  },
  computed: {
    allowed() {
      const p = typeof session_role_privileges !== 'undefined'
        ? session_role_privileges[this.name]
        : 0;
      if (this.minLevel === 2) return p === 2;
      return p === 1 || p === 2;
    },
  },
  template: `<div v-if="allowed"><slot></slot></div>`,
};
