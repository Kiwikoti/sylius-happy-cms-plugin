import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

/**
 * @property {HTMLDivElement} element
 * @property {HTMLDivElement} modalTarget
 * @property {HTMLDivElement} parentTarget
 * @property {HTMLInputElement} csrfTokenTarget
 */

export default class extends Controller {
  static targets = ['modal', 'parent', 'csrfToken'];

  connect() {
    this.element.addEventListener('happycms:page:open_delete_modal', (event) => {
      this.csrfTokenTarget.value = event.detail.csrfToken;
      this.modalElement = this.modalTarget;

      this.modalElement.closest('[data-modal-delete-page-target]').appendChild(this.modalElement);
      this.modal = new Modal(this.modalElement);
      this.modal.show();

      this.modalElement.addEventListener('hidden.bs.modal', () => {
        this.parentTarget.appendChild(this.modalElement);
      }, {once: true});
    });
  }
}
