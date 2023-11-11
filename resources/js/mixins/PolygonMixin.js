import {Collection} from 'ol'
import {Fill, Stroke, Style} from 'ol/style'
import {altKeyOnly, shiftKeyOnly} from 'ol/events/condition'
import {getCenter} from 'ol/extent'
import {inject} from 'vue'
import debounce from 'lodash/debounce'

export default {
    data() {
        return {
            isDirty: false,
            fieldValue: '',
            zones: [],
            selectedFeatures: new Collection(),
            drawIsEnabled: true,
            modifyIsEnabled: false,
            selectCondition: null,
            altKeyIsDown: false,
            shiftKeyIsDown: false,
            geoJsonObject: {
                type: 'FeatureCollection',
                crs: {
                    type: 'name',
                    properties: {
                        name: this.field.projection
                    }
                },
                features: []
            }
        }
    },
    computed: {
        isReadonly() {
            return this.readonly === true
        },

        isDrawable() {
            return this.drawIsEnabled && !this.isReadonly && !this.altKeyIsDown && !this.shiftKeyIsDown
        },

        isEditable() {
            return this.modifyIsEnabled && !this.isReadonly
        },

        vectorStyle() {
            return new Style({
                stroke: new Stroke({
                    color: 'blue',
                    width: 3
                }),
                fill: new Fill({
                    color: 'rgba(0, 0, 255, 0.4)'
                })
            })
        }
    },
    methods: {
        updateCenter(geometry) {
            const extend = geometry.getExtent()

            this.center = getCenter(extend)
        },

        setDirty() {
            this.isDirty = true
        },

        onDrawEnd(event) {
            const geometry = event.feature.getGeometry()

            this.zones.push(event.feature)
            this.selectedFeatures.push(event.feature)

            this.drawIsEnabled = false
            this.modifyIsEnabled = true

            this.setValue(geometry.getCoordinates())
            // this.updateCenter(geometry)
        },

        featureSelected(event) {
            this.modifyIsEnabled = event.selected.length > 0

            this.selectedFeatures.value = event.target.getFeatures()
        },

        isTransformable(evt) {
            this.altKeyIsDown = altKeyOnly(evt)
            this.shiftKeyIsDown = shiftKeyOnly(evt)

            return this.field.transform.isEnabled && !this.isDrawable && this.altKeyIsDown
        }
    },
    created() {
        this.initZones()

        const selectConditions = inject('ol-selectconditions')
        this.selectCondition = selectConditions.click
    },
    mounted() {
        if (this.$refs.map && this.field.transform.isEnabled) {
            this.$refs.map.map.on('pointerdrag', debounce((evt) => {
                this.onModifyEnd(evt)
            }, 300))
        }
    }
}
