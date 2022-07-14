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
import {toLonLat, fromLonLat} from 'ol/proj';
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
                const lonLat = fromLonLat([
                    value.longitude,
                    value.latitude
                ])

                this.center = lonLat

                this.setValue(lonLat[1], lonLat[0])
            }
            else {
                this.center = fromLonLat([
                    this.field.defaultLongitude,
                    this.field.defaultLatitude
                ])
            }
        },

        onCenterChanged(center) {
            this.setValue(center[1], center[0])
        },

        setValue(latitude, longitude) {
            const lonLat = toLonLat([longitude, latitude])
            const data = {
                longitude: lonLat[0],
                latitude: lonLat[1]
            }

            this.isDirty = true
            this.fieldValue = JSON.stringify(data)
        }
    },
}
</script>
