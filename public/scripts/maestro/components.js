class HTMXElement extends HTMLElement {

    constructor() {
        super();
    }

    getAttributeOrDefault(attribute, defaultValue = "") {
        const attrValue = this.getAttribute(attribute);
        return attrValue ? attrValue : defaultValue;
    }

    getElementClassOrDefault(element, defaultValue = "") {
        return this.getAttributeOrDefault(`class-${element}`, defaultValue);
    }

    renderedAttributeIfSet(targetAttribute, sourceAttribute) {
        const sourceAttributeValue = this.getAttribute(sourceAttribute);
        return sourceAttributeValue ? `${targetAttribute}="${sourceAttributeValue}"` : "";
    }

    renderedElementClassIfSet(element) {
        return this.renderedAttributeIfSet("class", `class-${element}`);
    }

    renderedElementHTMXAttributes(element) {
        const htmxAttributes = this.getAttributeNames()
            .filter(a => a.startsWith("hx-") && a.endsWith(element));

        if (htmxAttributes.length == 0) {
            return "";
        }

        return htmxAttributes.map(a => {
            const key = a.replace(`-${element}`, "");
            const value = this.getAttribute(a);
            return `${key}="${value}"`;
        }).join("\n");
    }
}

// class CustomModal extends HTMXElement {

//     constructor() {
//         super();
//         this._modalContainerClass = `${modalContainerClass} ${this.getElementClassOrDefault("container")}`;
//         this._modalContentClass = this.getElementClassOrDefault("content", defaultModalContentClass);
//         this._modalCloseClass = `${modalCloseClass} ${this.getElementClassOrDefault("close")}`;
//         this._render();
//     }

//     _render(title, message, titleAdditionalClass, messageAdditionalClass) {
//         const titleToRender = title ? title : "Default title";
//         const messageToRender = message ? message : "Default message";

//         const titleClass = `${this.getElementClassOrDefault("title")} ${titleAdditionalClass ? titleAdditionalClass : ""}`;
//         const messageClass = `${this.getElementClassOrDefault("message")} ${messageAdditionalClass ? messageAdditionalClass : ""}`;

//         this.innerHTML = `
//         <div class="${this._modalContainerClass}">
//             <div style="position: relative;" class="${this._modalContentClass}">
//               <span class="${this._modalCloseClass}">&times;</span>
//               <div class="${titleClass}">${titleToRender}</div>
//               <div class="${messageClass}">${messageToRender}</div>
//             </div>
//         </div>
//         `;
//     }

//     connectedCallback() {
//         this.showModal = (e) => {
//             const eDetail = e.detail;
//             this._render(eDetail.title, eDetail.message, eDetail.titleAdditionalClass, eDetail.messageAdditionalClass);
//             this._modalContainer().style.display = "block";
//             this.querySelector("span").onclick = () => this._modalContainer().style.display = "none";
//         }

//         this.hideModal = e => {
//             const modal = this._modalContainer();
//             if (e.target == modal) {
//                 modal.style.display = "none";
//             }
//         }

//         window.addEventListener("click", this.hideModal);
//         window.addEventListener("show-custom-modal", this.showModal);
//     }

//     _modalContainer() {
//         return this.querySelector("div");
//     }

//     disconnectedCallback() {
//         window.removeEventListener("click", this.hideModal);
//         window.removeEventListener("show-custom-modal", this.showModal);
//     }
// }

class InputError extends HTMXElement {

    static observedAttributes = ["message"];

    constructor() {
        super();
        const message = this.getAttributeOrDefault("message");
        this._render(message);
    }

    _render(message) {
        const errorClass = this.getElementClassOrDefault("error", defaultErrorClass);
        this.innerHTML = `<p class="${errorClass} ${message ? "" : hiddenClass}">${message}</p>`;
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this._render(newValue);
    }
}

class ItemForm extends HTMXElement {
    constructor() {
        super();
        this.innerHTML = `
        <div ${this.renderedElementClassIfSet("container")}>
            <input-error ${this.renderedAttributeIfSet("class-error", "class-generic-error")}></input-error>
            <form ${this.renderedElementHTMXAttributes("form")}>
                ${this._renderedInput("id", "Item id")}
                <input-error></input-error>
                ${this._renderedInput("name", "Item name")}
                <input-error></input-error>
                <input ${this.renderedElementClassIfSet("submit")} type="submit" value="Add item">
            </form>
        <div>
        `;
    }

    _renderedInput(name, placeholder) {
        const inputId = `${name}-input`;
        return `<input ${this.renderedElementHTMXAttributes(inputId)}
            ${this.renderedElementClassIfSet(inputId)}
            hx-trigger="input changed delay:500ms"
            hx-target="next input-error"
            hx-swap="outerHTML"
            style="display: block" name="${name}" placeholder="${placeholder}">`;
    }

    connectedCallback() {
        const from = this.querySelector("form");
        const genericError = this.querySelector("input-error");
        const inputs = this.querySelectorAll(`input:not([type="submit"])`);

        this._afterRequestListener = e => {
            if (!HTMX.isRequestFromElement(e, from)) {
                return;
            }
            if (HTMX.isFailedRequest(e)) {
                const error = HTMX.requestResponseFromEvent(e);
                genericError.setAttribute("message", error);
            } else {
                inputs.forEach(i => i.value = "");
            }
        };

        this.addEventListener(HTMX.events.afterRequest, this._afterRequestListener);

        inputs.forEach(i => {
            i.addEventListener("input", i => {
                genericError.setAttribute("message", "");
            });
        });
    }


    disconnectedCallback() {
        this.removeEventListener(HTMX.events.afterRequest, this._afterRequestListener);
    }
}

class ItemElement extends HTMXElement {
    constructor() {
        super();
        const id = this.getAttribute("item-id");
        const name = this.getAttribute("item-name");

        this.innerHTML = `<li ${this.renderedElementClassIfSet("item")}>Id: ${id}, Name: ${name}</li>`;

        this.querySelector("li").onclick = () => {
            window.dispatchEvent(new CustomEvent("item-element-clicked", {detail: {id, name}}));
        };
    }
}

class ItemsList extends HTMXElement {
    constructor() {
        super();
        this.innerHTML = `
        <ul ${this.renderedElementClassIfSet("container")}>
            ${this.innerHTML}
        </ul>
        `;
    }
}

class WTTest extends HTMXElement {
    constructor() {
        super();
        const id = this.getAttribute("id");
        const label = this.getAttribute("label");
        const value = this.getAttribute("value");

//         this.innerHTML = `
// <div class="form-field">
//     <label for="${id}">${label}</label>
//     <input id="${id}" name="${id}" class="easyui-combobox" data-options="valueField: 'idLU',textField: 'name',mode: 'remote',method: 'get',url:'/lu/listForSelect',value:'${this.value}'">
// </div>
//         `;

        console.log(id,this.renderedElementHTMXAttributes('button'));
        this.innerHTML = `
    <button id="${id}" type="submit" class="btn btn-primary" ${this.renderedElementHTMXAttributes('button')}>
        ${label}
    </button>
        `;

    }

    connectedCallback() {
        console.log('callback');
        // let that = this;
        // $(`#${this.id}`).combobox.defaults.onSelect = (r) => this._onSelect(r);
        // $.parser.parse(`#${this.id}`);
        // console.log($(`#${this.id}`)[0]);
        // console.log($(`#${this.id}`).combobox.defaults);
        // console.log($(`#${this.id}`)[0].onSelect);
        //$(`#${this.id}`).combobox({onSelect: that.onSelect})
    }

    _onSelect(r) {
        console.log('selected', r)
        this.setAttribute('value', r.idLU);
    }
}


customElements.define("input-error", InputError);
customElements.define("item-form", ItemForm);
customElements.define("item-element", ItemElement);
customElements.define("items-list", ItemsList);
customElements.define("wt-test", WTTest);
