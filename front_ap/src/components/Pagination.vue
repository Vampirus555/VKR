<template>
    <div class="pagination">
        <vue-awesome-paginate :total-items="this.totalItems" :items-per-page="this.itemsPerPage" v-model="currentPage">
        <template #prev-button>
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="#ffffff"
              width="11"
              height="11"
              viewBox="0 0 24 24"
            >
              <path d="M8.122 24l-4.122-4 8-8-8-8 4.122-4 11.878 12z" />
            </svg>
          </span>
        </template>
  
        <template #next-button>
          <span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="#ffffff"
              width="11"
              height="11"
              viewBox="0 0 24 24"
            >
              <path d="M8.122 24l-4.122-4 8-8-8-8 4.122-4 11.878 12z" />
            </svg>
          </span>
        </template>
      </vue-awesome-paginate>
    </div>
  </template>
  
  <script>
import { ref, watchEffect } from 'vue';

export default {
  name: 'Pagination',
  props: {
    totalItems: {
      type: Number,
      required: true
    },
    itemsPerPage: {
      type: Number,
      required: true
    },
    

  },
  setup(props, { emit }) {
    const currentPage = ref(1);

    watchEffect(() => {
      emit('update:currentPage', currentPage.value);
    });

    return {
      currentPage
    };
  }
};

</script>
  
  <style>
  .pagination .pagination-container {
    border-radius: 15px;
    overflow: hidden;
    border: 2px solid #ffcc66;
  }
  .pagination .paginate-buttons {
    width: 44px;
    height: 44px;
    cursor: pointer;
    background-color: #7b5c47;
    border: none;
    color: #ffffff;
    border-inline: 0.5px solid #ffcc66;
  }
  .pagination .active-page {
    background-color: #6633ff;
    color: #ffffff;
  }
  .pagination .paginate-buttons:hover {
    background-color: #34495e;
  }
  
  /* .example-three .active-page:hover {
    background-color: #1abc9c;
  } */
  .pagination .back-button {
    border-inline-start: none;
  }
  .pagination .next-button {
    border-inline-end: none;
  }
  .pagination .back-button svg {
    transform: rotate(180deg);
  }
  
  .pagination .back-button:active,
  .pagination .next-button:active {
    background-color: #3c5167;
  }
  </style>
  