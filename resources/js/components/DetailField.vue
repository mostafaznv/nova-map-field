<template>
    <panel-item :field="field" :label="field.name">
        <template #value>
            <div class="map-detail-field">
                <template v-if="mapType === 'POINT'">
                    <point-form-field class="readonly" :field="field" />
                    <point-index-field class="mt-3" :field="field" :modal-mode="true" />
                </template>

                <template v-else-if="mapType === 'POLYGON'">
                    <polygon-form-field :field="field" :readonly="true" />
                    <polygon-index-field class="mt-3" :field="field" :modal-mode="true" />
                </template>
            </div>
        </template>
    </panel-item>
</template>

<script>
import PointFormField from './form-fields/PointFormField'
import PolygonFormField from './form-fields/PolygonFormField'
import PointIndexField from './index-fields/PointIndexField'
import PolygonIndexField from './index-fields/PolygonIndexField'

export default {
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        PolygonIndexField,
        PointIndexField,
        PolygonFormField,
        PointFormField
    },
    data() {
        return {
            mapType: this.field.mapType
        }
    }
}
</script>

<style lang="scss" scoped>
.map-detail-field {
    .readonly {
        pointer-events: none;
    }
}
</style>
