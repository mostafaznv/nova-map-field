<template>
    <div class="map-container">
        <ol-map ref="map" @click="setDirty" :load-tiles-while-animating="true" :load-tiles-while-interacting="true"
                :style="mapStyles">
            <ol-view
                @change:center="setDirty"
                :center="center"
                :rotation="rotation"
                :zoom="zoom"
                :projection="projection"
            />

            <ol-tile-layer>
                <ol-source-osm :url="field.templateUrl" />
            </ol-tile-layer>

            <ol-vector-layer :styles="vectorStyle">
                <ol-source-vector ref="source" :features.sync="zones">
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
                    <ol-style-stroke :color="field.style.strokeColor" :width="field.style.strokeWidth" />
                    <ol-style-fill :color="field.style.fillColor" />

                    <ol-style-circle :radius="7">
                        <ol-style-fill color="blue" />
                    </ol-style-circle>
                </ol-style>
            </ol-vector-layer>

            <ol-interaction-select @select="featureSelected" :condition="selectCondition" :features="selectedFeatures">
                <ol-style>
                    <ol-style-stroke :color="field.style.strokeColor" :width="field.style.strokeWidth" />
                    <ol-style-fill :color="field.style.fillColor" />
                </ol-style>
            </ol-interaction-select>

            <ol-interaction-transform
                :condition="isTransformable"
                :scale="field.transform.scale"
                :rotate="field.transform.rotate"
                :stretch="field.transform.stretch"
            />

            <ol-zoom-control v-if="withZoomControl" />
            <ol-zoomslider-control v-if="withZoomSlider" />
            <ol-fullscreen-control v-if="withFullScreenControl" />
        </ol-map>
    </div>
</template>

<script>
import {toLonLat, fromLonLat} from 'ol/proj'
import {GeoJSON} from 'ol/format'
import HasMap from '../../mixins/HasMap'
import PolygonMixin from '../../mixins/PolygonMixin'
import HasSearchBox from '../../mixins/HasSearchBox'
import HasUndoControl from '../../mixins/HasUndoControl'
import HasClearMapControl from '../../mixins/HasClearMapControl'
import polylabel from 'polylabel'

export default {
    mixins: [
        HasMap, PolygonMixin, HasSearchBox, HasUndoControl, HasClearMapControl
    ],
    props: ['resourceName', 'resourceId', 'field', 'readonly'],
    methods: {
        initCenter() {
            let value = []

            if (this.field.value) {
                value = JSON.parse(this.field.value)
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

            const center = polylabel(coordinates)

            if (center[0] !== NaN){
                return [
                    center[0],
                    center[1]
                ]
            }

            return coordinates[0][0]
        },

        initZones() {
            this.zones = new GeoJSON().readFeatures(this.geoJsonObject)

            if (this.zones.length) {
                this.selectedFeatures.push(this.zones[0])
            }
        },

        onModifyEnd() {
            const features = this.$refs.source.source.getFeatures()

            if (features.length) {
                const geometry = features[0].getGeometry()

                this.setValue(geometry.getCoordinates())
            }
            else {
                this.setValue([])
            }
        },

        setValue(coordinates) {
            if (coordinates.length) {
                coordinates = coordinates[0]
                    .map(coordinate => toLonLat(coordinate))
                    .map(coordinate => [coordinate[1], coordinate[0]])

                this.fieldValue = JSON.stringify(coordinates)
                this.setDirty()
            }
            else {
                this.fieldValue = ''
            }
        }
    }
}
</script>
