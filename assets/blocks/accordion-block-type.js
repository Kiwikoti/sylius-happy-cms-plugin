const accordions = document.querySelectorAll('[js-accordion-block-type-block-type]');

const resetAccordions = () => {
    accordions.forEach((accordion) => {
        accordion.classList.remove('is-active');
        accordion.querySelector('[js-accordion-block-type-content]').style.maxHeight = 0;
    })
}

const openAccordions = (accordion, content, contentHeight) => {
    accordion.classList.add('is-active');
    content.style.maxHeight = `${contentHeight}px`;
}

accordions.forEach((accordion) => {
    const accordionTitle = accordion.querySelector('[js-accordion-block-type-title]');
    const content = accordion.querySelector('[js-accordion-block-type-content]');
    const contentHeight = content.querySelector('[js-accordion-block-type-transition]').offsetHeight;
    accordionTitle.addEventListener('click', () => {
        accordion.classList.toggle('is-active');
        if(accordion.classList.contains('is-active')) {
            resetAccordions();
            openAccordions(accordion, content, contentHeight);
        } else {
            resetAccordions();
        }
    })
})
