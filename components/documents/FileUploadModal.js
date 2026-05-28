/** @stub — загрузка файла (FormData) */
const SedFileUploadModal = {
  props: { id: { type: String, default: 'fileUploadModal' }, title: { type: String, default: 'Загрузка файла' } },
  emits: ['upload'],
  template: `
    <sed-bootstrap-modal :id="id" :title="title">
      <input type="file" @change="$emit('upload', $event.target.files)">
    </sed-bootstrap-modal>
  `,
};
