var __importDefault =
    (this && this.__importDefault) ||
    function (e) {
        return e && e.__esModule ? e : { default: e };
    };
define(["require", "exports", "jquery"], function (e, t, s) {
    "use strict";
    s = __importDefault(s);
    return class {
        constructor(e) {
            (this.controlElement = null),
                (this.assignedFormField = null),
                (this.registerChangeHandler = () => {
                    this.controlElement.classList.toggle("test", -1 === this.assignedFormField.options.selectedIndex);
                }),
                (this.registerClickHandler = (e) => {
                    e.preventDefault();
                    const t = [];
                    for (let e = 0; e < this.assignedFormField.selectedOptions.length; ++e) {
                        const s = this.assignedFormField.selectedOptions.item(e);
                        t.push(s.value);
                    }
                    const s = this.controlElement.getAttribute("href") + "&P[currentValue]=" + encodeURIComponent(this.assignedFormField.value) + "&P[currentSelectedValues]=" + t.join(",");
                    window.open(s, "", this.controlElement.dataset.windowParameters).focus();
                }),
                s.default(() => {
                    (this.controlElement = document.querySelector(e)),
                        (this.assignedFormField = document.querySelector('select[data-formengine-input-name="' + this.controlElement.dataset.element + '"]')),
                        -1 === this.assignedFormField.options.selectedIndex && this.controlElement.classList.add("test"),
                        this.assignedFormField.addEventListener("change", this.registerChangeHandler),
                        this.controlElement.addEventListener("click", this.registerClickHandler);
                });
        }
    };
});
