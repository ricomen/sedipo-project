/**
 * Bootstrap 5 modal (статический id для data-bs-target).
 * Props: id, title, size (modal-dialog класс, напр. modal-lg)
 * Slots: body, footer (опционально)
 */
const SedBootstrapModal = {
  props: {
    id: { type: String, required: true },
    title: { type: String, default: '' },
    size: { type: String, default: '' },
  },
  template: `
    <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
      <div class="modal-dialog" :class="size">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" :id="id + 'Label'">{{ title }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          </div>
          <div class="modal-body"><slot></slot></div>
          <div v-if="$slots.footer" class="modal-footer"><slot name="footer"></slot></div>
          <div v-else class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
          </div>
        </div>
      </div>
    </div>
  `,
};
