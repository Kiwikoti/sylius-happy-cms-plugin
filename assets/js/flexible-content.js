const flexibleContentModule = function () {
  const self = this;

  self.blockToMove = null;

  const initModule = function () {
    // Au chargement on va initialiser le comportement lié au catalogue des blocs :

    //    - Identifier le wrapper de la colonne de gauche
    self.wFlexibleContent = document.querySelector('.w-flexible-content');

    //    - Identifier le wrapper de la colonne de droite
    self.wFlexibleBlock = document.querySelector('.w-flexible-blocks');

    //    - le dropdown des catégories (au choix) : masquer tous les blocs sauf ceux demandés
    const dropDownBlockCategories = self.wFlexibleBlock.querySelector('.block-categories');
    dropDownBlockCategories.addEventListener('click', self.listenBlockCategoriesChanges);

    //    - le bouton ajouter
    self.wFlexibleBlock.querySelectorAll('button.add-flexible-block')
      .forEach((addButton) => {
        addButton.addEventListener('click', self.handleAddButton);
      });

    //    - Initialiser les comportements des blocs existants
    self.wFlexibleContent.querySelectorAll('.bloc-wrapper')
      .forEach((block) => {
        self.handleBlock(block);
      });

    self.wFlexibleContent.querySelectorAll('.move-here').forEach(el => {
      self.handleClickMove(el);
    });

    //    - Initialiser les positions des blocks
    self.recalculateBlockPositions();
  };

  self.handleClickMove = function(el) {
    el.addEventListener('click', function(ev) {
      if (self.blockToMove !== null) {
        // change positions
        const nextMoveLayer = self.blockToMove.nextElementSibling;
        ev.target.after(self.blockToMove);
        self.blockToMove.after(nextMoveLayer);
        // change block position value
        self.recalculateBlockPositions();
        // disable active move behavior
        const event = new Event("click");
        self.blockToMove
          .querySelector('[data-action="move"]')
          .dispatchEvent(event);
      }
    });
  };

  self.recalculateBlockPositions = function() {
    const collection = self.wFlexibleContent.closest('[data-sylius-flexible-content-field]');
    const blockPositionInputs = collection
      .querySelectorAll('.bloc-wrapper [data-layer="content"] input[type="hidden"]');
    let count = self.wFlexibleContent.querySelectorAll('.bloc-wrapper').length;
    blockPositionInputs.forEach((field) => {
      if (field.id.includes("_position")) {
        field.value = count;
        count ++;
      }
    });
  };

  self.handleBlock = function(block) {
    //    - click open / close
    self.handleToggleContent(block);
    //    - click move
    self.handleMoveContent(block);
    //    - click + alert trash
    self.handleRemoveContent(block);
    //    - publish
    self.handlePublishContent(block);
  };

  self.addNewBlock = function (data, index) {
    // Lorsqu'on ajoute un bloc il faut initiliser son comportement
    //    - Récupérer le prototype du wrapper du futur block
    const blockWrapper = self.getBlockWrapperPrototype();
    //    - Y injecter le prototype du block
    const newContents = self.generateNewBlock(blockWrapper, data, index);
    self.wFlexibleContent.style.opacity = 0;
    self.appendNewBlock(newContents[0])
      .then(() => {
        const block = document.getElementById('w-wrapper-prototype').previousElementSibling;
        document.getElementById('w-wrapper-prototype').before(newContents[1]);
        const { blockName } = data;
        //    - Remplacer le titre par le nom du bloc
        block.querySelector('[data-layer="title"]').innerHTML = blockName;
        //    - Init block events
        self.handleBlock(block);
        //    - Recalculer les positions
        self.recalculateBlockPositions();
        //    - executer les scripts js
        Array.from(self.wFlexibleContent.lastElementChild.querySelectorAll('script'))
          .forEach((oldScript) => {
            if (!oldScript.src) {
              self.evalScript(oldScript.innerHTML);
            }
          });
        /* global $ */
        $('.ui.checkbox').checkbox();
        self.wFlexibleContent.style.opacity = 1;
      });
  };

  // action move
  self.handleMoveContent = function (block) {
    block.querySelector('[data-action="move"]')
      .addEventListener('click', function (event) {
        self.wFlexibleContent.querySelectorAll('[data-action="move"].green').forEach(el => {
          if (el != event.target)
            el.classList.remove('green');
        });
        block.querySelector('[data-action="move"]')
          .classList.toggle('green');
        setTimeout(() => {
          self.wFlexibleContent.querySelectorAll('.move-here').forEach(el => {
            const moveEnabled = block.querySelector('[data-action="move"]')
              .classList.contains('green');
            self.blockToMove = moveEnabled ? block : null;
            el.style.display = moveEnabled ? 'table' : 'none';
            if (moveEnabled) {
              self.blockToMove.previousElementSibling.style.display = 'none';
              if (self.blockToMove.nextElementSibling) {
                self.blockToMove.nextElementSibling.style.display = 'none';
              }
            }
          });
        }, 150);
      });
  };

  // action when change published checkbox
  self.handlePublishContent = function (block) {

    // Apply checked state based on published hidden input
    let blockPublishedInputs = block
      .querySelectorAll('input[type="hidden"]');
    blockPublishedInputs.forEach(function (field) {
      if (field.id.includes("_block_published")) {
        block.querySelector('.block_published [type="checkbox"]').checked = field.value === '1';
        if (field.value === '1') {
          block.classList.remove('brown');
          block.classList.add('green');
        } else {
          block.classList.add('brown');
          block.classList.remove('green');
        }
      }
    });

    block.querySelector('.block_published [type="checkbox"]')
      .addEventListener('change', function (event) {
        blockPublishedInputs = block
          .querySelectorAll('input[type="hidden"]');
        blockPublishedInputs.forEach(function (field) {
          if (field.id.includes("_block_published")) {
            field.value = event.target.checked ? '1' : '0';
          }
        });
        if (event.target.checked) {
          block.classList.remove('brown');
          block.classList.add('green');
        } else {
          block.classList.add('brown');
          block.classList.remove('green');
        }
      });
  };

  // action when remove a content
  self.handleRemoveContent = function (block) {
    block.querySelector('[data-action="delete"]')
      .addEventListener('click', function () {
        if (confirm(self.wFlexibleBlock.querySelector('#confirm_sentence').textContent)) {
          const collection = block.closest('[data-sylius-flexible-content-field]');
          block.remove();
          collection.dataset.numItems = collection.querySelectorAll('.bloc-wrapper').length - 1;
        }
      });
  };

  // action when click to toggle a block
  self.handleToggleContent = function (block) {
    block.querySelector('[data-action="toggle"]')
      .addEventListener('click', function (event) {
        const isDown = event.target.classList.contains('down');
        block.querySelector('[data-layer="content"]').style.display =
          isDown ? 'block' : 'none';
        block.style.padding =
          isDown ? '3.5em 1em 1em' : '19px';
        event.target.classList.toggle('down');
        event.target.classList.toggle('up');
      });
  };

  self.getBlockWrapperPrototype = function () {
    return document.getElementById('w-wrapper-prototype')
      .querySelector('.bloc-wrapper');
  };

  self.generateNewBlock = function (blockWrapper, data, index) {
    const { formTypeNamePlaceholder, prototype } = data;
    const labelRegexp = new RegExp(`${formTypeNamePlaceholder}label__`, 'g');
    const nameRegexp = new RegExp(formTypeNamePlaceholder, 'g');

    const newPrototype = prototype
      .replace(labelRegexp, index)
      .replace(nameRegexp, index);

    const blockElement = blockWrapper.cloneNode(true);
    const moveHereWrapper = blockWrapper.nextElementSibling.cloneNode(true);
    blockElement.querySelector('[data-layer="content"]')
      .insertAdjacentHTML('beforeend', newPrototype);

    return [blockElement, moveHereWrapper];
  };

  self.appendNewBlock = function (content) {

    const remote = [];

    document.getElementById('w-wrapper-prototype').before(content);

    Array.from(self.wFlexibleContent.lastElementChild.previousElementSibling.querySelectorAll('script'))
      .forEach((oldScript) => {
        if (oldScript.src) {
          remote.push(self.loadScript(oldScript.src));
        }
      });

    Array.from(self.wFlexibleContent.lastElementChild.previousElementSibling.querySelectorAll('link'))
      .forEach((oldScript) => {
        if (oldScript.href && oldScript.rel === 'stylesheet') {
          remote.push(self.loadStylesheet(oldScript.href));
        }
      });

    return new Promise((resolve) => {
      Promise.all(remote)
        .then(() => {
          setTimeout(() => {
            Array.from(self.wFlexibleContent.lastElementChild.previousElementSibling.querySelectorAll('script'))
              .forEach((oldScript) => {
                if (!oldScript.src) {
                  const newScript = document.createElement('script');
                  Array.from(oldScript.attributes)
                    .forEach((attr) => newScript.setAttribute(attr.name, attr.value));
                  newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                  oldScript.parentNode.replaceChild(newScript, oldScript);
                  self.evalScript(oldScript.innerHTML);
                }
              });
            resolve();
          }, 1);
        });
    });
  };

  self.evalScript = function(content) {
    return new Promise((resolve) => {
      eval(content);
      resolve();
    });
  };

  self.loadScript = function(src) {
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = src;
      script.type = 'text/javascript';

      script.onload = () => resolve(script);
      script.onerror = () => reject(new Error(`Style load error for ${src}`));

      document.head.append(script);
      resolve();
    });
  };

  self.loadStylesheet = function(src) {
    return new Promise((resolve, reject) => {
      const link = document.createElement('link');
      link.href = src;
      link.rel = 'stylesheet';

      link.onload = () => resolve(link);
      link.onerror = () => reject(new Error(`Style load error for ${src}`));

      document.head.append(link);
      resolve();
    });
  };

  self.handleAddButton = function (event) {
    event.stopPropagation();
    event.preventDefault();
    const addButton = event.target;
    const collection = addButton.closest('.field-collection');
    let numItems = parseInt(collection.dataset.numItems);
    collection.dataset.numItems = ++numItems;
    self.addNewBlock(addButton.dataset, self.generateItemId());
    if (document.querySelector('.empty-flexible-content')) {
      document.querySelector('.empty-flexible-content').remove();
    }
    return false;
  };

  self.generateItemId = function () {
    let dt = new Date().getTime();
    const uuid = 'xxxx-xxx'.replace(/[xy]/g, (c) => {
      const r = (dt + Math.random()*16)%16 | 0;
      dt = Math.floor(dt/16);
      return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
    return uuid;
  };

  self.listenBlockCategoriesChanges = function (event) {
    let activeCategory = null;
    const activeItem = event.target.closest('.block-categories')
      .querySelector('.item.active');
    if (activeItem) {
      activeCategory = activeItem.dataset.value;
    }
    event.target.closest('.w-flexible-blocks')
      .querySelectorAll('.card')
      .forEach((card) => {
        card.style.display = activeCategory === null || activeCategory === 'all_blocks' ? 'flex' : 'none';
      });
    if (activeCategory) {
      event.target.closest('.w-flexible-blocks')
        .querySelectorAll(`.card[data-block-category="${activeCategory}"]`)
        .forEach((card) => {
          card.style.display = 'flex';
        });
    }
  };

  initModule();
};

window.addEventListener('DOMContentLoaded', flexibleContentModule);
