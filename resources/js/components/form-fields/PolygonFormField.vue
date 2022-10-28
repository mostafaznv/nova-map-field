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
                <ol-source-osm />
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
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import {toLonLat, fromLonLat} from 'ol/proj'
import {GeoJSON} from 'ol/format'
import HasMap from '../../mixins/HasMap'
import PolygonMixin from '../../mixins/PolygonMixin'
import HasSearchBox from '../../mixins/HasSearchBox'
import HasUndoControl from '../../mixins/HasUndoControl'

export default {
    mixins: [
        FormField, HandlesValidationErrors, HasMap, PolygonMixin, HasSearchBox, HasUndoControl
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
                    value[0].map(v => fromLonLat([v[1], v[0]]))
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

            if (this.zones.length) {
                this.selectedFeatures.push(this.zones[0])
            }
        },

        onModifyEnd() {
            const geometry = this.$refs.source.source.getFeatures()[0].getGeometry()

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
