"use strict";
(self["webpackChunk_agence_adeliom_sylius_happy_cms_plugin"] = self["webpackChunk_agence_adeliom_sylius_happy_cms_plugin"] || []).push([["_agence-adeliom_sylius-happy-cms-assets_Resources_assets_controllers_DeletePageController_js"],{

/***/ "./@agence-adeliom/sylius-happy-cms-assets/Resources/assets/controllers/DeletePageController.js":
/*!******************************************************************************************************!*\
  !*** ./@agence-adeliom/sylius-happy-cms-assets/Resources/assets/controllers/DeletePageController.js ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }


/**
 * @property {HTMLDivElement} element
 * @property {HTMLDivElement} modalTarget
 * @property {HTMLDivElement} parentTarget
 * @property {HTMLInputElement} csrfTokenTarget
 */
var _default = /*#__PURE__*/function (_Controller) {
  function _default() {
    _classCallCheck(this, _default);
    return _callSuper(this, _default, arguments);
  }
  _inherits(_default, _Controller);
  return _createClass(_default, [{
    key: "connect",
    value: function connect() {
      var _this = this;
      this.element.addEventListener('happycms:page:open_delete_modal', function (event) {
        _this.csrfTokenTarget.value = event.detail.csrfToken;
        _this.modalElement = _this.modalTarget;
        _this.modalElement.closest('[data-modal-delete-page-target]').appendChild(_this.modalElement);
        _this.modal = new window.bootstrap.Modal(_this.modalElement);
        _this.modal.show();
        _this.modalElement.addEventListener('hidden.bs.modal', function () {
          _this.parentTarget.appendChild(_this.modalElement);
        }, {
          once: true
        });
      });
    }
  }]);
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);
_defineProperty(_default, "targets", ['modal', 'parent', 'csrfToken']);


/***/ })

}]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiX2FnZW5jZS1hZGVsaW9tX3N5bGl1cy1oYXBweS1jbXMtYXNzZXRzX1Jlc291cmNlc19hc3NldHNfY29udHJvbGxlcnNfRGVsZXRlUGFnZUNvbnRyb2xsZXJfanMuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFnRDs7QUFFaEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBTEEsSUFBQUMsUUFBQSwwQkFBQUMsV0FBQTtFQUFBLFNBQUFELFNBQUE7SUFBQUUsZUFBQSxPQUFBRixRQUFBO0lBQUEsT0FBQUcsVUFBQSxPQUFBSCxRQUFBLEVBQUFJLFNBQUE7RUFBQTtFQUFBQyxTQUFBLENBQUFMLFFBQUEsRUFBQUMsV0FBQTtFQUFBLE9BQUFLLFlBQUEsQ0FBQU4sUUFBQTtJQUFBTyxHQUFBO0lBQUFDLEtBQUEsRUFVRSxTQUFBQyxPQUFPQSxDQUFBLEVBQUc7TUFBQSxJQUFBQyxLQUFBO01BQ1IsSUFBSSxDQUFDQyxPQUFPLENBQUNDLGdCQUFnQixDQUFDLGlDQUFpQyxFQUFFLFVBQUNDLEtBQUssRUFBSztRQUMxRUgsS0FBSSxDQUFDSSxlQUFlLENBQUNOLEtBQUssR0FBR0ssS0FBSyxDQUFDRSxNQUFNLENBQUNDLFNBQVM7UUFDbkROLEtBQUksQ0FBQ08sWUFBWSxHQUFHUCxLQUFJLENBQUNRLFdBQVc7UUFFcENSLEtBQUksQ0FBQ08sWUFBWSxDQUFDRSxPQUFPLENBQUMsaUNBQWlDLENBQUMsQ0FBQ0MsV0FBVyxDQUFDVixLQUFJLENBQUNPLFlBQVksQ0FBQztRQUMzRlAsS0FBSSxDQUFDVyxLQUFLLEdBQUcsSUFBSUMsTUFBTSxDQUFDQyxTQUFTLENBQUNDLEtBQUssQ0FBQ2QsS0FBSSxDQUFDTyxZQUFZLENBQUM7UUFDMURQLEtBQUksQ0FBQ1csS0FBSyxDQUFDSSxJQUFJLENBQUMsQ0FBQztRQUVqQmYsS0FBSSxDQUFDTyxZQUFZLENBQUNMLGdCQUFnQixDQUFDLGlCQUFpQixFQUFFLFlBQU07VUFDMURGLEtBQUksQ0FBQ2dCLFlBQVksQ0FBQ04sV0FBVyxDQUFDVixLQUFJLENBQUNPLFlBQVksQ0FBQztRQUNsRCxDQUFDLEVBQUU7VUFBQ1UsSUFBSSxFQUFFO1FBQUksQ0FBQyxDQUFDO01BQ2xCLENBQUMsQ0FBQztJQUNKO0VBQUM7QUFBQSxFQWhCMEI1QiwwREFBVTtBQUFBNkIsZUFBQSxDQUFBNUIsUUFBQSxhQUNwQixDQUFDLE9BQU8sRUFBRSxRQUFRLEVBQUUsV0FBVyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vQGFnZW5jZS1hZGVsaW9tL3N5bGl1cy1oYXBweS1jbXMtcGx1Z2luLy4vQGFnZW5jZS1hZGVsaW9tL3N5bGl1cy1oYXBweS1jbXMtYXNzZXRzL1Jlc291cmNlcy9hc3NldHMvY29udHJvbGxlcnMvRGVsZXRlUGFnZUNvbnRyb2xsZXIuanMiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29udHJvbGxlciB9IGZyb20gJ0Bob3R3aXJlZC9zdGltdWx1cyc7XG5cbi8qKlxuICogQHByb3BlcnR5IHtIVE1MRGl2RWxlbWVudH0gZWxlbWVudFxuICogQHByb3BlcnR5IHtIVE1MRGl2RWxlbWVudH0gbW9kYWxUYXJnZXRcbiAqIEBwcm9wZXJ0eSB7SFRNTERpdkVsZW1lbnR9IHBhcmVudFRhcmdldFxuICogQHByb3BlcnR5IHtIVE1MSW5wdXRFbGVtZW50fSBjc3JmVG9rZW5UYXJnZXRcbiAqL1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBleHRlbmRzIENvbnRyb2xsZXIge1xuICBzdGF0aWMgdGFyZ2V0cyA9IFsnbW9kYWwnLCAncGFyZW50JywgJ2NzcmZUb2tlbiddO1xuXG4gIGNvbm5lY3QoKSB7XG4gICAgdGhpcy5lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2hhcHB5Y21zOnBhZ2U6b3Blbl9kZWxldGVfbW9kYWwnLCAoZXZlbnQpID0+IHtcbiAgICAgIHRoaXMuY3NyZlRva2VuVGFyZ2V0LnZhbHVlID0gZXZlbnQuZGV0YWlsLmNzcmZUb2tlbjtcbiAgICAgIHRoaXMubW9kYWxFbGVtZW50ID0gdGhpcy5tb2RhbFRhcmdldDtcblxuICAgICAgdGhpcy5tb2RhbEVsZW1lbnQuY2xvc2VzdCgnW2RhdGEtbW9kYWwtZGVsZXRlLXBhZ2UtdGFyZ2V0XScpLmFwcGVuZENoaWxkKHRoaXMubW9kYWxFbGVtZW50KTtcbiAgICAgIHRoaXMubW9kYWwgPSBuZXcgd2luZG93LmJvb3RzdHJhcC5Nb2RhbCh0aGlzLm1vZGFsRWxlbWVudCk7XG4gICAgICB0aGlzLm1vZGFsLnNob3coKTtcblxuICAgICAgdGhpcy5tb2RhbEVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignaGlkZGVuLmJzLm1vZGFsJywgKCkgPT4ge1xuICAgICAgICB0aGlzLnBhcmVudFRhcmdldC5hcHBlbmRDaGlsZCh0aGlzLm1vZGFsRWxlbWVudCk7XG4gICAgICB9LCB7b25jZTogdHJ1ZX0pO1xuICAgIH0pO1xuICB9XG59XG4iXSwibmFtZXMiOlsiQ29udHJvbGxlciIsIl9kZWZhdWx0IiwiX0NvbnRyb2xsZXIiLCJfY2xhc3NDYWxsQ2hlY2siLCJfY2FsbFN1cGVyIiwiYXJndW1lbnRzIiwiX2luaGVyaXRzIiwiX2NyZWF0ZUNsYXNzIiwia2V5IiwidmFsdWUiLCJjb25uZWN0IiwiX3RoaXMiLCJlbGVtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsImV2ZW50IiwiY3NyZlRva2VuVGFyZ2V0IiwiZGV0YWlsIiwiY3NyZlRva2VuIiwibW9kYWxFbGVtZW50IiwibW9kYWxUYXJnZXQiLCJjbG9zZXN0IiwiYXBwZW5kQ2hpbGQiLCJtb2RhbCIsIndpbmRvdyIsImJvb3RzdHJhcCIsIk1vZGFsIiwic2hvdyIsInBhcmVudFRhcmdldCIsIm9uY2UiLCJfZGVmaW5lUHJvcGVydHkiLCJkZWZhdWx0Il0sInNvdXJjZVJvb3QiOiIifQ==