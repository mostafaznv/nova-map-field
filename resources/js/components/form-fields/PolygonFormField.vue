<template>
    <div class="map-container">
        <ol-map ref="map" @click="setDirty" :load-tiles-while-animating="true" :load-tiles-while-interacting="true" :style="mapStyles">
            <ol-view
                @centerChanged="setDirty"
                :center="center"
                :rotation="rotation"
                :zoom="zoom"
                :projection="projection"
            />

            <ol-tile-layer>
                <ol-source-osm  :url="'https://stamen-tiles.a.ssl.fastly.net/toner/{z}/{x}/{y}.png'" />
            </ol-tile-layer>

            <ol-vector-layer :style="vectorStyle">
                <ol-source-vector :features="zones">
                    <ol-interaction-modify
                        v-if="isEditable"
                        @modifyend="onModifyEnd"
                        :features="selectedFeatures"
                    />

                    <ol-interaction-draw
                        v-if="isDrawable"
                        @drawend="onDrawEnd"
                        type="Polygon"
                        :stopClick="true"
                        :min-points="3"
                    />

                    <ol-interaction-snap v-if="isEditable" />
                </ol-source-vector>

                <ol-style>
                    <ol-style-stroke :color="`rgba(35, 115, 132, 1)`" :width="2"></ol-style-stroke>
                    <ol-style-fill color="rgba(255,255,255,0.1)"></ol-style-fill>
                    <ol-style-circle :radius="7">
                        <ol-style-fill :color="`rgba(35, 115, 132, 1)`"></ol-style-fill>
                    </ol-style-circle>
                </ol-style>
            </ol-vector-layer>

            <ol-interaction-select @select="featureSelected" :condition="selectCondition" :features="selectedFeatures">
                <ol-style>
                    <ol-style-stroke :color="`rgba(35, 115, 132, 1)`" :width="2"></ol-style-stroke>
                    <ol-style-fill :color="`rgba(83, 178, 199, 0.3)`"></ol-style-fill>
                </ol-style>
            </ol-interaction-select>

            <button type="button"
                    @click="clear"
                    class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
                    v-if="isEditable || drawIsEnabled">
                Borrar Todo
            </button>

            <ol-zoom-control v-if="withZoomControl" />
            <ol-zoomslider-control v-if="withZoomSlider" />
            <ol-fullscreen-control v-if="withFullScreenControl" />
        </ol-map>
    </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import {toLonLat, fromLonLat} from 'ol/proj';
import {GeoJSON} from 'ol/format'
import HasMap from '../../mixins/HasMap'
import PolygonMixin from '../../mixins/PolygonMixin'
import HasSearchBox from '../../mixins/HasSearchBox'

export default {
    mixins: [FormField, HandlesValidationErrors, HasMap, PolygonMixin, HasSearchBox],
    props: ['resourceName', 'resourceId', 'field', 'readonly'],
    methods: {
        clear() {
            this.selectedFeatures.pop();
            this.zones = [];
            this.drawIsEnabled = true;
            this.modifyIsEnabled = false;
            this.setDirty();
        },
        initCenter() {
            let value = []

            if (this.field.value) {
                value = JSON.parse(this.field.value)

                console.log(value);
            }

            if (value.length) {
                const coordinates = [
                    value[0].map(v => fromLonLat([v[0], v[1]]))
                ]

                this.center = this.initFeatures(coordinates)
                this.setValue(coordinates)
            }
            else {
                this.center = fromLonLat([
                    this.field.defaultLongitude,
                    this.field.defaultLatitude
                ])
            }
        },

        initFeatures(coordinates) {
            this.geoJsonObject.features = [
                {
                    type: 'Feature',
                    geometry: {
                        type: 'Polygon',
                        coordinates
                    }
                }
            ]


            this.drawIsEnabled = false
            this.modifyIsEnabled = true

            return coordinates[0][0]
        },

        initZones() {
            this.zones = new GeoJSON().readFeatures(this.geoJsonObject)

            console.log(this.zones, this.geoJsonObject)

            if (this.zones.length) {
                this.selectedFeatures.push(this.zones[0])
            }
        },

        onModifyEnd(event) {
            const geometry = event.features.getArray()[0].getGeometry()

            this.setValue(geometry.getCoordinates())
        },

        setValue(coordinates) {
            if (coordinates.length) {
                coordinates = coordinates[0].map(coordinate => toLonLat(coordinate))

                this.fieldValue = JSON.stringify(coordinates)
                this.setDirty()
            }
        }
    }
}
</script>
