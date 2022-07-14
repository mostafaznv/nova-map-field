<template>
    <div class="map-container" :class="['marker-icon-' + markerIcon, isDirty ? '' : 'is-not-dirty']">
        <ol-map @click="setDirty" :load-tiles-while-animating="true" :load-tiles-while-interacting="true" :style="mapStyles">
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
import {inject} from 'vue'
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import {toLonLat, fromLonLat} from 'ol/proj';
import {GeoJSON} from 'ol/format'
import {Fill, Stroke, Style} from 'ol/style'
import {getCenter} from 'ol/extent'
import {Collection} from 'ol'
import HasMap from '../../mixins/HasMap'

export default {
    mixins: [FormField, HandlesValidationErrors, HasMap],
    props: ['resourceName', 'resourceId', 'field', 'readonly'],
    data() {
        return {
            isDirty: false,
            fieldValue: [],
            zones: [],
            selectedFeatures: new Collection(),
            drawIsEnabled: true,
            modifyIsEnabled: false,
            selectCondition: null,
            geoJsonObject: {
                type: 'FeatureCollection',
                crs: {
                    type: 'name',
                    properties: {
                        name: 'EPSG:3857'
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
            return this.drawIsEnabled && !this.isReadonly
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

        onModifyEnd(event) {
            const geometry = event.features.getArray()[0].getGeometry()

            this.setValue(geometry.getCoordinates())
        },

        featureSelected(event) {
            this.modifyIsEnabled = event.selected.length > 0

            this.selectedFeatures.value = event.target.getFeatures()
        },

        setValue(coordinates) {
            if (coordinates.length) {
                coordinates = coordinates[0].map(coordinate => toLonLat(coordinate))

                this.fieldValue = JSON.stringify(coordinates)
                this.setDirty()
            }
        }
    },
    created() {
        this.initZones()

        const selectConditions = inject('ol-selectconditions')
        this.selectCondition = selectConditions.click
    }
}
</script>
