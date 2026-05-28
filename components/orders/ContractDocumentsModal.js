/** @stub — модалка бухгалтерских документов */
const SedContractDocumentsModal = {
  props: { id: { type: String, default: 'contractLoadModal' } },
  template: `<sed-bootstrap-modal :id="id" title="Бухгалтерские документы"><slot></slot></sed-bootstrap-modal>`,
};
