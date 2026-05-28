/**
 * Иконки действий в строке таблицы.
 * Props: showEdit, showClone, showDelete, showReport, editTo, cloneTo, reportTo
 * Emits: delete, clone (если без router-link)
 */
const SedRowActions = {
  props: {
    showEdit: { type: Boolean, default: true },
    showClone: { type: Boolean, default: false },
    showDelete: { type: Boolean, default: false },
    showReport: { type: Boolean, default: false },
    editTo: { type: [String, Object], default: null },
    cloneTo: { type: [String, Object], default: null },
    reportTo: { type: [String, Object], default: null },
    deleteTitle: { type: String, default: 'Удалить' },
  },
  emits: ['delete', 'clone'],
  template: `
    <table border="0"><tr>
      <td v-if="showEdit && editTo" style="padding-right: 5px;">
        <router-link :to="editTo" title="Редактировать"><i class="fa-solid fa-pencil"></i></router-link>
      </td>
      <td v-if="showClone && cloneTo" style="padding-right: 5px;">
        <router-link :to="cloneTo" title="Создать копию"><i class="fa-solid fa-clone"></i></router-link>
      </td>
      <td v-if="showReport && reportTo">
        <router-link :to="reportTo" title="Отчёт"><i class="fa-solid fa-file-circle-check"></i></router-link>
      </td>
      <td v-if="showDelete" style="padding-left: 5px;">
        <a href="#" :title="deleteTitle" style="color: red;" @click.prevent="$emit('delete')">
          <i class="fa-solid fa-trash-can"></i>
        </a>
      </td>
    </tr></table>
  `,
};
