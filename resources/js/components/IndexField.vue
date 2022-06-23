<template>
    <div class="map-index-field-container">
        <point-index-field v-if="mapType === 'POINT'" :field="field" />
        <polygon-index-field v-else-if="mapType === 'POLYGON'" :field="field" />
    </div>
</template>

<script>
import PointIndexField from './index-fields/PointIndexField'
import PolygonIndexField from './index-fields/PolygonIndexField'

export default {
    props: ['field'],
    components: {
        PolygonIndexField,
        PointIndexField
    },
    data() {
        return {
            mapType: this.field.mapType
        }
    }
}
</script>
