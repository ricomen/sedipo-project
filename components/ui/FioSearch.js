/**
 * Поиск по ФИО (три поля).
 * Props: lastname, firstname, middlename
 * Emits: update:lastname|firstname|middlename, search, reset
 */
const SedFioSearch = {
  props: {
    lastname: { type: String, default: '' },
    firstname: { type: String, default: '' },
    middlename: { type: String, default: '' },
  },
  emits: ['update:lastname', 'update:firstname', 'update:middlename', 'search', 'reset'],
  template: `
    <table border="0">
      <tr align="left">
        <td><small><input type="text" :value="lastname" placeholder="Фамилия" size="10" @input="$emit('update:lastname', $event.target.value)"></small></td>
        <td><small><input type="text" :value="firstname" placeholder="Имя" size="10" @input="$emit('update:firstname', $event.target.value)"></small></td>
        <td><small><input type="text" :value="middlename" placeholder="Отчество" size="10" @input="$emit('update:middlename', $event.target.value)"></small></td>
        <td width="2%"></td>
        <td align="left"><small><button type="button" @click="$emit('search')"><nobr>&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</nobr></button></small></td>
        <td width="2%"></td>
        <td><small><button type="button" title="Сбросить все фильтры" @click="$emit('reset')">&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;</button></small></td>
      </tr>
    </table>
  `,
};
