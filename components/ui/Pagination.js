/**
 * Пагинация списков (как в courses_list / orders_list).
 * Props: page, numPages
 * Emits: page-change(pageNumber)
 */
const SedPagination = {
  props: {
    page: { type: Number, required: true },
    numPages: { type: Number, required: true },
  },
  emits: ['page-change'],
  methods: {
    go(p) {
      if (p >= 1 && p <= this.numPages) this.$emit('page-change', p);
    },
  },
  template: `
    <div v-if="numPages > 1" class="row">
      <div class="col"></div>
      <div class="btn-toolbar col" style="text-align: center;" aria-label="pages">
        <ul class="pagination">
          <li v-if="page > 1" class="page-item">
            <button type="button" class="page-link" aria-label="Previous" @click="go(page - 1)">
              <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span>
            </button>
          </li>
          <li v-if="page > 1" class="page-item">
            <button type="button" class="page-link" @click="go(1)">1</button>
          </li>
          <li v-if="page > 2" class="page-item"><button type="button" class="page-link">...</button></li>
          <li v-if="page - 2 > 2" class="page-item">
            <button type="button" class="page-link" @click="go(page - 2)">{{ page - 2 }}</button>
          </li>
          <li v-if="page - 1 > 2" class="page-item">
            <button type="button" class="page-link" @click="go(page - 1)">{{ page - 1 }}</button>
          </li>
          <li class="page-item active" aria-current="page">
            <button type="button" class="page-link">{{ page }}</button>
          </li>
          <li v-if="page + 1 < numPages" class="page-item">
            <button type="button" class="page-link" @click="go(page + 1)">{{ page + 1 }}</button>
          </li>
          <li v-if="page + 2 < numPages" class="page-item">
            <button type="button" class="page-link" @click="go(page + 2)">{{ page + 2 }}</button>
          </li>
          <li v-if="page + 3 < numPages" class="page-item"><button type="button" class="page-link">...</button></li>
          <li v-if="page < numPages" class="page-item">
            <button type="button" class="page-link" @click="go(numPages)">{{ numPages }}</button>
          </li>
          <li v-if="page < numPages" class="page-item">
            <button type="button" class="page-link" aria-label="Next" @click="go(page + 1)">
              <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
            </button>
          </li>
        </ul>
      </div>
      <div class="col-2"></div>
    </div>
  `,
};
