<template>
    <div class="map-container">
        <ol-map
            ref="map"
            :load-tiles-while-animating="true"
            :load-tiles-while-interacting="true"
            :style="mapStyles"
            :controls="[]"
        >
            <ol-view
                :center="center"
                :rotation="rotation"
                :zoom="zoom"
                :projection="projection"
            />

            <ol-tile-layer>
                <ol-source-osm :url="field.templateUrl"/>
            </ol-tile-layer>

            <ol-vector-layer>
                <ol-source-vector ref="source">
                  <ol-feature v-if="hasInitValue">
                    <ol-geom-point :coordinates="[initValue.longitude, initValue.latitude]" />
                  </ol-feature>
                </ol-source-vector>

              <ol-style>
                <ol-style-icon :src="markerIcon" :anchor="[0.5, 1]" :scale="0.1" />
              </ol-style>
            </ol-vector-layer>

            <ol-zoom-control v-if="withZoomControl && !exportable"/>
            <ol-zoomslider-control v-if="withZoomSlider && !exportable"/>
            <ol-fullscreen-control v-if="withFullScreenControl && !exportable"/>
        </ol-map>
    </div>
</template>

<script>
import HasMap from '../../mixins/HasMap'
import {fromLonLat, toLonLat} from 'ol/proj'
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
                    return value[0].map(v => fromLonLat([v[0], v[1]]))
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

        setValue(coordinates) {
            this.drawIsEnabled = true

            if (coordinates.length) {
                this.values.push(
                    coordinates[0]
                        .map(coordinate => toLonLat(coordinate))
                        .map(coordinate => [coordinate[1], coordinate[0]])
                )

                this.fieldValue = JSON.stringify(this.values)
                this.setDirty()
            }
            else {
                this.fieldValue = ''
            }
        }
    }
}
</script>
