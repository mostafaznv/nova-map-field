import Control from 'ol/control/Control.js'
import {CLASS_CONTROL, CLASS_UNSELECTABLE} from 'ol/css.js'
import EventType from 'ol/events/EventType'

class ClearMap extends Control {
    field = null

    constructor(field, options) {
        options = options ? options : {}

        super({
            element: document.createElement('div'),
            target: options.target,
        })

        this.field = field

        const className = options.className !== undefined ? options.className : 'ol-clear-map'
        const label = options.label !== undefined ? options.label : '✕'
        const tipLabel = options.tipLabel !== undefined ? options.tipLabel : 'Clear Map'

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
        this.clear()
    }


    clear() {
        this.field.zones.pop()
        this.field.selectedFeatures.pop()

        this.field.onModifyEnd()

        this.field.drawIsEnabled = true
        this.field.modifyIsEnabled = false
    }
}

export default ClearMap
