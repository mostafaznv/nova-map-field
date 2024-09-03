import Control from 'ol/control/Control.js'
import {CLASS_CONTROL, CLASS_UNSELECTABLE} from 'ol/css.js'
import EventType from 'ol/events/EventType'
import {Draw} from "ol/interaction";

class Undo extends Control {
    constructor(options) {
        options = options ? options : {}

        super({
            element: document.createElement('div'),
            target: options.target,
        })

        const className = options.className !== undefined ? options.className : 'ol-undo'
        const label = options.label !== undefined ? options.label : '⎌'
        const tipLabel = options.tipLabel !== undefined ? options.tipLabel : 'Undo'

        const el = document.createElement('button')
        el.className = className
        el.setAttribute('type', 'button')
        el.title = tipLabel
        el.appendChild(typeof label === 'string' ? document.createTextNode(label) : label)
        el.addEventListener(EventType.CLICK, this.handleClick_.bind(this), false)

        const cssClasses = className + ' ' + CLASS_UNSELECTABLE + ' ' + CLASS_CONTROL
        const element = this.element
        element.className = cssClasses
        element.appendChild(el)
    }

    handleClick_(event) {
        event.preventDefault()
        this.undo()
    }


    undo() {
        const map = this.getMap();

        map.getInteractions().getArray().forEach((obj) => {
            if (obj instanceof Draw) {
                obj.removeLastPoint()
            }
        })
    }
}

export default Undo
