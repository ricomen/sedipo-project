/** @stub — ссылки d-api/documents-*.php */
const SedDocumentExportLinks = {
  props: { links: { type: Array, default: () => [] } },
  template: `
    <ul class="dropdown-menu" style="--bs-dropdown-min-width: 20rem; padding: 7px;">
      <li v-for="(link, i) in links" :key="i">
        <a :href="link.href" target="_blank" class="nav-link" :title="link.title">
          <i class="fa-regular fa-file-lines"></i> {{ link.label }}
        </a>
      </li>
    </ul>
  `,
};
