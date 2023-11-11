<template>
    <div class="export-map">
        <point-form-field
            v-if="currentField.mapType === 'POINT'"
            ref="map"
            :field="currentField"
            :exportable="true"
        />

        <polygon-form-field
            v-else-if="currentField.mapType === 'POLYGON'"
            ref="map"
            :field="currentField"
            :exportable="true"
        />

        <multi-polygon-form-field
            v-else-if="currentField.mapType === 'MULTI_POLYGON'"
            ref="map"
            :field="currentField"
            :exportable="true"
        />
    </div>
</template>

<script setup>
import {ref, watch, defineEmits} from 'vue'
import PolygonFormField from '../form-fields/PolygonFormField'
import PointFormField from '../form-fields/PointFormField'
import MultiPolygonFormField from '../form-fields/MultiPolygonFormField'


const emit = defineEmits([
    'update:modelValue'
])


// variables
const props = defineProps({
    field: {
        type: Object,
        required: true
    },
    modelValue: [String | Number | Object | Array],
    fieldValue: [String | Number | Object | Array]
})

const map = ref(null)
const currentField = ref(props.field)
const config = ref({
    enabled: false,
    width: null,
    height: null,
    maxZoom: null,
    nearest: null,
    padding: null
})


// watchers
watch(() => props.fieldValue, async (value) => {
    if (props.field.mapType === 'POLYGON') {
        value = JSON.parse(value.toString())
        value = value.map(v => [v[1], v[0]])

        value = JSON.stringify([value])
    }
    else if (props.field.mapType === 'MULTI_POLYGON') {
        value = JSON.parse(value.toString())

        value = value.map(v => {
            return [v.map(vv => [vv[1], vv[0]])]
        })

        value = JSON.stringify(value)
    }

    currentField.value.value = value

    await setValue()
})



// methods
function init() {
    if (props.field?.capture) {
        config.value = props.field.capture
        config.value.enabled = true
    }
}

async function setValue() {
    if (map.value) {
        await map.value.initCenter()

        if (props.field.mapType === 'POLYGON' || props.field.mapType === 'MULTI_POLYGON') {
            await map.value.initZones()
        }

        await capture()
    }
}

async function capture() {
    if (config.value.enabled === false) {
        return null
    }

    const res = await map.value?.capture()

    if (res.file) {
        emit('update:modelValue', res.file)
    }
}


init()
</script>

<style lang="scss" scoped>
.export-map {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -99999;
    top: -999999;
    left: -999999;
    opacity: 0;
}
</style>
