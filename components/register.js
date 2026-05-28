/**
 * Регистрация глобальных компонентов Sedipo.
 * Вызывать: registerSedComponents(app) после createApp, до mount.
 */
function registerSedComponents(app) {
  function toTag(name) {
    return 'sed-' + name.replace(/^Sed/, '').replace(/([A-Z])/g, '-$1').toLowerCase();
  }

  const map = {
    SedPageShell,
    SedPageFooterClose,
    SedPagination,
    SedSearchToolbar,
    SedFioSearch,
    SedCreateEntityButton,
    SedRowActions,
    SedDataTable,
    SedBootstrapModal,
    SedSplitButtonDropdown,
    SedPrivilegeGuard,
    SedDateRangeFilter,
    SedYearMonthTabs,
    SedStatusSelect,
    SedCounterpartyPicker,
    SedCategorySelect,
    SedPerformerSelect,
    SedArchiveToggle,
    SedFormFieldHelp,
    SedSnilsField,
    SedDocumentExportLinks,
    SedFileUploadModal,
    SedOrderStatusActions,
    SedOrderWarningIcons,
    SedContractDocumentsModal,
    SedStudentsCountBadge,
    SedOrderEntityLinks,
  };

  let n = 0;
  Object.entries(map).forEach(([name, component]) => {
    if (typeof component === 'undefined') {
      console.warn('[register] компонент не загружен:', name);
      return;
    }
    app.component(toTag(name), component);
    n++;
  });

  console.info('[register] зарегистрировано компонентов:', n);
}
