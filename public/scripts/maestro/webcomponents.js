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

        if (htmxAttributes.length === 0) {
            return "";
        }

        return htmxAttributes.map(a => {
            const key = a.replace(`-${element}`, "");
            const value = this.getAttribute(a);
            return `${key}="${value}"`;
        }).join("\n");
    }
}

class WTGoTop extends HTMXElement {

    static observedAttributes = ["id","label","offset"];
    constructor() {
        super();

    }

    scrollFunction(offset) {
        console.log('calling scrollfunction', document.body.scrollTop,document.documentElement.scrollTop);
        if (document.body.scrollTop > offset || document.documentElement.scrollTop > offset) {
            document.getElementById(this.id + '_button').style.display = "block";
        } else {
            document.getElementById(this.id + '_button').style.display = "none";
        }
    }

// When the user clicks on the button, scroll to the top of the document
    topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }

    connectedCallback() {
        const id = this.getAttribute("id");
        const label = this.getAttribute("label");
        const offset= this.getAttribute("offset");

        console.log(id);
        console.log(label);
        console.log(offset);

        console.log('callback');
        this.innerHTML = `
    <button id="${id}_button" class="wt-go-top hxBtn hxDanger">
        ${label}
    </button>
        `;
        console.log(this.innerHTML);
        document.getElementById(this.id + '_button').addEventListener('click', this.topFunction);
        window.onscroll = () => {
            this.scrollFunction(offset)
        };
        // let that = this;
        // $(`#${this.id}`).combobox.defaults.onSelect = (r) => this._onSelect(r);
        // $.parser.parse(`#${this.id}`);
        // console.log($(`#${this.id}`)[0]);
        // console.log($(`#${this.id}`).combobox.defaults);
        // console.log($(`#${this.id}`)[0].onSelect);
        //$(`#${this.id}`).combobox({onSelect: that.onSelect})
    }

    _onSelect(r) {
        // console.log('selected', r)
        // this.setAttribute('value', r.idLU);
    }
}

customElements.define("wt-go-top", WTGoTop);
