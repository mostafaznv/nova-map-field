<template>
    <default-field :field="field" :errors="errors" :full-width-content="true" :show-help-text="showHelpText">
        <template #field>
            <div
                class="z-10 p-0 w-full form-control form-input-bordered overflow-hidden relative"
                :class="mapErrorClasses"
                :style="{height: field.mapHeight + 'px'}"
            >
                <point-form-field
                    v-if="mapType === 'POINT'"
                    v-model="fieldValue"
                    :field="field"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <polygon-form-field
                    v-else-if="mapType === 'POLYGON'"
                    v-model="fieldValue"
                    :field="field"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <multi-polygon-form-field
                    v-else-if="mapType === 'MULTI_POLYGON'"
                    v-model="fieldValue"
                    :field="field"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />
            </div>
        </template>
    </default-field>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import PointFormField from './form-fields/PointFormField'
import PolygonFormField from './form-fields/PolygonFormField'
import MultiPolygonFormField from './form-fields/MultiPolygonFormField'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        PolygonFormField,
        MultiPolygonFormField,
        PointFormField
    },
    data() {
        return {
            mapType: this.field.mapType,
            fieldValue: ''
        }
    },
    computed: {
        hasLocationError() {
            return this.errors.has(this.field.attribute)
        },

        mapErrorClasses() {
            return this.hasLocationError ? this.errorClass : ''
        }
    },
    methods: {
        fill(formData) {
            formData.append(this.field.attribute, this.fieldValue || '')
        }
    }
}
</script>
