<template>
    <div class="map-container with-marker" :class="['marker-icon-' + markerIcon, isDirty ? '' : 'is-not-dirty']">
        <ol-map :load-tiles-while-animating="true" :load-tiles-while-interacting="true" :style="mapStyles">
            <ol-view
                @centerChanged="onCenterChanged"
                :center="center"
                :rotation="rotation"
                :zoom="zoom"
                :projection="projection"
            />

            <ol-tile-layer>
                <ol-source-osm />
            </ol-tile-layer>

            <ol-zoom-control v-if="withZoomControl" />
            <ol-zoomslider-control v-if="withZoomSlider" />
            <ol-fullscreen-control v-if="withFullScreenControl" />
        </ol-map>
    </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import HasMap from '../../mixins/HasMap'

export default {
    mixins: [FormField, HandlesValidationErrors, HasMap],
    props: ['resourceName', 'resourceId', 'field'],
    data() {
        return {
            isDirty: false,
            fieldValue: {
                longitude: null,
                latitude: null
            }
        }
    },
    methods: {
        initCenter() {
            let value = {}

            if (this.field.value) {
                value = JSON.parse(this.field.value)
            }

            if (value.latitude && value.longitude) {
                this.center = [
                    value.longitude,
                    value.latitude
                ]

                this.setValue(value.latitude, value.longitude)
            }
            else {
                this.center = [
                    this.field.defaultLongitude,
                    this.field.defaultLatitude
                ]
            }
        },

        onCenterChanged(center) {
            this.setValue(center[1], center[0])
        },

        setValue(latitude, longitude) {
            const data = {
                latitude, longitude
            }

            this.isDirty = true
            this.fieldValue = JSON.stringify(data)
        }
    },
}
</script>
