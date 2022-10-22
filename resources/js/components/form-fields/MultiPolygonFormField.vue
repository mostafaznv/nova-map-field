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
                <ol-source-osm :url="'https://stamen-tiles.a.ssl.fastly.net/toner/{z}/{x}/{y}.png'" />
            </ol-tile-layer>

            <ol-vector-layer :style="vectorStyle">
                <ol-source-vector ref="source" :features="zones">
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
                    <ol-style-stroke color="red" :width="2"></ol-style-stroke>
                    <ol-style-fill color="rgba(255,255,255,0.1)"></ol-style-fill>
                    <ol-style-circle :radius="7">
                        <ol-style-fill color="blue"></ol-style-fill>
                    </ol-style-circle>
                </ol-style>
            </ol-vector-layer>

            <ol-interaction-select @select="featureSelected" :condition="selectCondition" :features="selectedFeatures">
                <ol-style>
                    <ol-style-stroke :color="'red'" :width="2"></ol-style-stroke>
                    <ol-style-fill :color="`rgba(255, 0, 0, 0.4)`"></ol-style-fill>
                </ol-style>
            </ol-interaction-select>

            <ol-zoom-control v-if="withZoomControl" />
            <ol-zoomslider-control v-if="withZoomSlider" />
            <ol-fullscreen-control v-if="withFullScreenControl" />
        </ol-map>
    </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import HasMap from '../../mixins/HasMap'
import PolygonMixin from '../../mixins/PolygonMixin'
import {fromLonLat, toLonLat} from 'ol/proj'
import {GeoJSON} from "ol/format";
import HasSearchBox from "../../mixins/HasSearchBox";

export default {
    mixins: [FormField, HandlesValidationErrors, HasMap, PolygonMixin, HasSearchBox],
    props: ['resourceName', 'resourceId', 'field', 'readonly'],
    data() {
        return {
            values: [],
        }
    },
    methods: {
        initCenter() {
            this.values = []
            let values = []

            if (this.field.value) {
                values = JSON.parse(this.field.value)
            }

            if (values.length) {
                const coordinates = values.map(value => {
                    return value[0].map(v => fromLonLat([v[1], v[0]]))
                })

                this.center = this.initFeatures(coordinates)

                coordinates.forEach(coordinate => this.setValue([coordinate]))
            }
            else {
                this.center = fromLonLat([
                    this.field.defaultLongitude,
                    this.field.defaultLatitude
                ])
            }
        },

        initFeatures(coordinates) {
            this.geoJsonObject.features = coordinates.map((c) => {
                return {
                    type: 'Feature',
                    geometry: {
                        type: 'Polygon',
                        coordinates: [c]
                    }
                }
            })

            this.drawIsEnabled = false
            this.modifyIsEnabled = true

            return coordinates[0][0]
        },

        initZones() {
            this.zones = new GeoJSON().readFeatures(this.geoJsonObject)

            if (this.zones.length) {
                this.zones.forEach(zone => this.selectedFeatures.push(zone))
            }
        },

        onModifyEnd() {
            this.values = []

            this.$refs.source.source.getFeatures().forEach((feature) => {
                this.setValue(feature.getGeometry().getCoordinates())
            })
        },

        setValue(coordinates) {
            this.drawIsEnabled = true

            if (coordinates.length) {
                this.values.push(coordinates[0].map(coordinate => toLonLat(coordinate)))

                this.fieldValue = JSON.stringify(this.values)
                this.setDirty()
            }
        }
    }
}
</script>
