import './flexible-content.css';

const flexibleContentModule = function () {
  const self = this;

  self.blockToMove = null;

  const initIframePreviewModule = function () {
      document.querySelectorAll('.iframe-tooltip').forEach(link => {
          let tooltipInstance = null;

          link.addEventListener('mouseenter', async () => {
              const url = link.dataset.url;

              /*
                Sur un écran desktop 1920x1080 : 600 x 400
                Sur un écran tablet 768x1024 : 460 x 400
                Sur un mobile (360x640) : 216 x 256
               */
              const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
              const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
                // Dimensions responsives par défaut
              const width = link.dataset.width || Math.min(0.6 * vw, 600); // max 600px ou 60% du viewport
              const height = link.dataset.height || Math.min(0.4 * vh, 400); // max 400px ou 40% du viewport

              const tooltip = new bootstrap.Tooltip(link, {
                  html: true,
                  template: `<div class="flexible-tooltip" role="tooltip"><div class="iframe-container" style="width: ${width}px; height: ${height}px;">
                <div class="spinner-grow text-primary" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
              </div></div>`,
                  placement: 'left',
                  trigger: 'manual',
                  container: 'body'
              });

              tooltip.show();

              // Insertion dynamique de l’iframe après affichage du tooltip
              setTimeout(() => {
                  const container = document.querySelector('.flexible-tooltip .iframe-container');
                  if (container && !container.querySelector('iframe')) {
                      const iframe = document.createElement('iframe');
                      iframe.src = url;
                      iframe.style.width = width+'px';
                      iframe.style.height = height+'px';

                      iframe.onload = () => {
                          const loader = container.querySelector('.spinner-grow');
                          if (loader) loader.remove();
                          iframe.style.opacity = '1';
                      };

                      container.appendChild(iframe);
                  }
              }, 100);
          });

          link.addEventListener('mouseleave', () => {
              const tooltip = bootstrap.Tooltip.getInstance(link);
              if (tooltip) {
                  tooltip.hide();
              }
          });
      });
  };

  const initModule = function () {
    // Au chargement on va initialiser le comportement lié au catalogue des blocs :

    //    - Identifier le wrapper de la colonne de gauche
    self.wFlexibleContent = document.querySelector('.w-flexible-content');

    //    - Identifier le wrapper de la colonne de droite
    self.wFlexibleBlock = document.querySelector('.w-flexible-blocks');

    //    - le dropdown des catégories (au choix) : masquer tous les blocs sauf ceux demandés
    const dropDownBlockCategories = self.wFlexibleBlock.querySelector('.block-categories');
    dropDownBlockCategories.addEventListener('change', self.listenBlockCategoriesChanges);

    //    - le dropdown des catégories (au choix) : masquer tous les blocs sauf ceux demandés
    const filterBlocks = self.wFlexibleBlock.querySelector('#block-filter');
      filterBlocks.addEventListener('keyup', self.listenBlockFilterChanges);

    document.getElementById('open-blocks').addEventListener('click', () => {
        setTimeout(() => {
            self.wFlexibleBlock.querySelector('#block-filter').focus();
        }, 300)
    });

    //    - le bouton ajouter
    self.wFlexibleBlock.querySelectorAll('a.add-flexible-block')
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
        el.after(self.blockToMove);
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
          //$('.ui.checkbox').checkbox();
          self.wFlexibleContent.style.opacity = 1;
        });
  };

  // action move
  self.handleMoveContent = function (block) {
    block.querySelector('[data-action="move"]')
        .addEventListener('click', function (event) {
          self.wFlexibleContent.querySelectorAll('[data-action="move"].border-teal').forEach(el => {
            if (el != event.target)
              el.classList.remove('border-teal');
          });
          block.querySelector('[data-action="move"]')
              .classList.toggle('border-teal');
          setTimeout(() => {
            self.wFlexibleContent.querySelectorAll('.move-here').forEach(el => {
              const moveEnabled = block.querySelector('[data-action="move"]')
                  .classList.contains('border-teal');
              self.blockToMove = moveEnabled ? block : null;
              el.style.display = moveEnabled ? 'block' : 'none';
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
          block.classList.remove('border-gray');
          block.classList.add('border-teal');
        } else {
          block.classList.add('border-gray');
          block.classList.remove('border-teal');
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
            block.classList.remove('border-gray');
            block.classList.add('border-teal');
          } else {
            block.classList.add('border-gray');
            block.classList.remove('border-teal');
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
            block.querySelector('[data-layer="content"]').style.display = isDown ? 'none' : 'block';
            event.target.classList.toggle('down')
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

    const checkboxInNewBlock = blockElement.querySelector('.form-check-input');
    const labelInNewBlock = blockElement.querySelector('.form-check-label');
    const lastBlockInContent = document.getElementById('w-wrapper-prototype').parentElement.parentElement.querySelector('.w-flexible-content > .card');
    if (lastBlockInContent && checkboxInNewBlock && labelInNewBlock) {
        const idAttr = lastBlockInContent.querySelector('.form-check-input').getAttribute('id');
        // get the number part of the for attribute (block-checkbox-3 -> 3)
        const newId = parseInt(idAttr.split('-')[2], 10) + 1;
        checkboxInNewBlock.setAttribute('id', `block-checkbox-${newId}`);
        labelInNewBlock.setAttribute('for', `block-checkbox-${newId}`);
    }

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

    const myModalEl = document.querySelector('#block-list')
    const modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
    modal.hide();
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
    const activeCategory = event.target.value;
    event.target.closest('.w-flexible-blocks')
        .querySelectorAll('[data-block-category]')
        .forEach((card) => {
          card.style.display = activeCategory === null || activeCategory === 'all_blocks' ? 'block' : 'none';
        });
    if (activeCategory) {
      event.target.closest('.w-flexible-blocks')
          .querySelectorAll(`[data-block-category="${activeCategory}"]`)
          .forEach((card) => {
            card.style.display = 'block';
          });
    }
  };

  self.listenBlockFilterChanges = function (event) {
    const filter = event.target.value;
      event.target.closest('.w-flexible-blocks')
          .querySelectorAll(`[data-block-category] .card-title`)
          .forEach((title) => {
              const regex = new RegExp(filter, 'i');
              title.parentElement.parentElement.style.display = regex.test(title.innerText) || !filter ? 'block' : 'none';
          });
  };

  initModule();
  initIframePreviewModule();
};

window.addEventListener('DOMContentLoaded', flexibleContentModule);
