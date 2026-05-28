/**
 * Обёртка table.table со слотами header и default (body rows).
 */
const SedDataTable = {
  template: `
    <table class="table">
      <thead><slot name="header"></slot></thead>
      <tbody><slot></slot></tbody>
    </table>
  `,
};
