<template>
    <div class="map-container">
        <ol-map ref="map" :load-tiles-while-animating="true" :load-tiles-while-interacting="true" :style="mapStyles" :controls="[]">
            <ol-view :center="center" :rotation="rotation" :zoom="zoom" :projection="projection" />

            <ol-tile-layer>
                <ol-source-osm :url="field.templateUrl" />
            </ol-tile-layer>

            <ol-vector-layer>
                <ol-source-vector ref="source" :projection="projection">
                    <ol-feature v-if="hasInitValue">
                        <ol-geom-point :coordinates="[initValue.longitude, initValue.latitude]" />
                    </ol-feature>

                    <template v-if="!isReadonly && !exportable">
                        <ol-interaction-modify v-if="isDirty" @modifyend="onModifyEnd" />
                        <ol-interaction-draw v-else type="Point" @drawend="onDrawEnd" />
                    </template>
                </ol-source-vector>

                <ol-style>
                    <ol-style-icon :src="markerIcon" :anchor="[0.5, 1]" :scale="0.1" />
                </ol-style>
            </ol-vector-layer>


            <ol-zoom-control v-if="withZoomControl && !exportable" />
            <ol-zoomslider-control v-if="withZoomSlider && !exportable" />
            <ol-fullscreen-control v-if="withFullScreenControl && !exportable" />
        </ol-map>
    </div>
</template>

<script>
import {toLonLat, fromLonLat} from 'ol/proj'
import HasMap from '../../mixins/HasMap'
import HasSearchBox from '../../mixins/HasSearchBox'
import ExportsMap from '../../mixins/ExportsMap'


export default {
    mixins: [
        HasMap, HasSearchBox, ExportsMap
    ],
    props: [
        'field', 'readonly'
    ],
    expose: [
        'initCenter', 'capture', 'isDirty'
    ],
    data() {
        return {
            isDirty: false,
            selectPointOnSearch: true,
            initValue: {
                longitude: null,
                latitude: null
            },
            fieldValue: ''
        }
    },
    computed: {
        isReadonly() {
            return this.readonly === true
        },

        hasInitValue() {
            return !!this.initValue.latitude && !!this.initValue.longitude
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
                this.initValue = {
                    longitude: lonLat[0],
                    latitude: lonLat[1]
                }

                this.setValue(lonLat[1], lonLat[0])
            }
            else {
                this.center = fromLonLat([
                    this.field.defaultLongitude,
                    this.field.defaultLatitude
                ])
            }
        },

        onDrawEnd(evt) {
            const coordinate = evt.feature?.getGeometry().getCoordinates()

            if (Array.isArray(coordinate)) {
                this.setValue(coordinate[1], coordinate[0])
            }
        },

        onModifyEnd(evt) {
            const coordinate = evt.features?.getArray()[0].getGeometry().getCoordinates()

            if (Array.isArray(coordinate)) {
                this.setValue(coordinate[1], coordinate[0])
            }
        },

        setDirty() {
            this.isDirty = true
        },

        setValue(latitude, longitude) {
            const lonLat = toLonLat([longitude, latitude])
            const data = {
                longitude: lonLat[0],
                latitude: lonLat[1]
            }

            this.setDirty()
            this.fieldValue = JSON.stringify(data)
        }
    }
}
</script>
