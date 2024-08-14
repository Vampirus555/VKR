<template>
    <div>
      <div class="tabs">
        <div
          v-for="(tab, index) in tabs"
          :key="index"
          :class="{ 'active': activeTab === index }"
          @click="activeTab = index"
        >
          {{ tab.title }}
        </div>
      </div>
      <div class="tab-content">
        {{ tabs[activeTab].content }}
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeTab: 0,
        tabs: [
          { title: 'Парки в виде карточек', content: 'Content for Tab 1' },
          { title: 'Парки на карте', content: 'Content for Tab 2' },
        ],
      };
    },
  };
  </script>
  
  <style>
  .tabs {
    display: flex;
    justify-content: center;
    color: #FFFFFF;
    font-size: 18px;
    font-weight: bold;
  }
  
  .tab-content {
    flex: 1;
    border: 2px solid #FFCC66;
    border-radius: 15px;
    padding: 10px;
    margin: 10px;
  }

  .tabs div {
    padding: 10px;
    cursor: pointer;
    border: 2px solid #FFCC66;
    border-radius: 15px;
  }

  .tabs div.active {
    background-color: #6633FF;
  }
  </style>