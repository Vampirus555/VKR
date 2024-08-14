<template>
    <section class="game-section">
      <div class="custom-carousel">
        <div
          v-for="(item, index) in items"
          :key="index"
          :class="['item', { active: index === activeIndex }]"
          :style="{ backgroundImage: `url(${item.image})` }"
          @click="setActive(index)"
        >
          <div class="item-desc">
            <h3>{{ item.title }}</h3>
            <p>{{ item.description }}</p>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeIndex: 0,
        items: [
          {
            title: 'Возможности',
            description: 'Веб-приложение позволит вам находить актуальную информацию. Для этого вам доступно несколько вариантов нахождения мероприятия. Также если возникли вопросы, вы можете обратиться в поддержку, заполнив специально для этого отведенную форму в соответсвующем разделе.',
            image: '/detail.webp',
          },
          {
            title: 'Для пользователей',
            description: 'По мере просмотра мероприятий добавляйте их в заметки. Это аналогичный принцип избранным, только с добавлением комментариев. Дополнительно только вам доступно написание отзывов, что делает систему отзывов честной!!!',
            image: '/detail2.webp',
          },
          {
            title: 'Для организаторов',
            description: 'Вам предоставлена возможность свободно размещать свои мероприятия и отслеживать их статус. Если что-то пошло не так, или парк временно закрыт, вы всегда можете отредактирвоать параметры мероприятия.',
            image: '/detail3.webp',
          },
          {
            title: 'Для представителей',
            description: 'PUBG 2 is a multiplayer online battle arena by Valve. The game is a sequel to Defense of the Ancients, which was a community-created mod for Blizzard Entertainment\'s Warcraft III.',
            image: '/detail4.webp',
          },
        ],
      };
    },
    methods: {
      setActive(index) {
        this.activeIndex = index;
      },
    },
  };
  </script>
  
  <style scoped>
  img {
    max-width: 100%;
    border: 0px;
  }
  
  .line-title {
    position: relative;
    width: 400px;
  }
  .line-title::before,
  .line-title::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    border-radius: 2px;
  }
  .line-title::before {
    width: 100%;
    background: #f2f2f2;
  }
  .line-title::after {
    width: 32px;
    background: #e73700;
  }
  
  .game-section {
    padding: 20px;
  }
  .game-section .custom-carousel {
    display: flex;
    overflow-x: auto;
  }
  .game-section .item {
    flex: 0 0 auto;
    margin: 0 15px 60px;
    width: 320px;
    height: 400px;
    display: flex;
    align-items: flex-end;
    background: #343434 no-repeat center center / cover;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s ease-in-out;
    cursor: pointer;
  }
  .game-section .item.active {
    width: 500px;
    box-shadow: 12px 40px 40px rgba(0, 0, 0, 0.25);
  }
  .game-section .item:after {
    content: "";
    display: block;
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 1));
  }
  .game-section .item-desc {
    padding: 0 24px 12px;
    color: #fff;
    position: relative;
    z-index: 1;
    overflow: hidden;
    transform: translateY(calc(100% - 54px));
    transition: all 0.4s ease-in-out;
  }
  .game-section .item.active .item-desc {
    transform: none;
  }
  .game-section .item-desc p {
    opacity: 0;
    transform: translateY(32px);
    transition: all 0.4s ease-in-out 0.2s;
  }
  .game-section .item.active .item-desc p {
    opacity: 1;
    transform: translateY(0);
  }
  </style>