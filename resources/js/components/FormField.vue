<template>
    <default-field :field="currentField" :errors="errors" :full-width-content="true" :show-help-text="showHelpText">
        <template #field>
            <div
                class="z-10 p-0 w-full form-control form-input-bordered overflow-hidden relative"
                :class="mapErrorClasses"
                :style="{height: currentField.mapHeight + 'px'}"
            >
                <point-form-field
                    v-if="mapType === 'POINT'"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <polygon-form-field
                    v-else-if="mapType === 'POLYGON'"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <multi-polygon-form-field
                    v-else-if="mapType === 'MULTI_POLYGON'"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />
            </div>
        </template>
    </default-field>
</template>

<script>
import {DependentFormField, HandlesValidationErrors} from 'laravel-nova'
import PointFormField from './form-fields/PointFormField'
import PolygonFormField from './form-fields/PolygonFormField'
import MultiPolygonFormField from './form-fields/MultiPolygonFormField'

export default {
    mixins: [DependentFormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        PolygonFormField,
        MultiPolygonFormField,
        PointFormField
    },
    data() {
        return {
            fieldValue: ''
        }
    },
    watch: {
        fieldValue(value) {
            this.emitFieldValueChange(this.currentField.attribute, value)
        }
    },
    computed: {
        mapType() {
            return this.currentField.mapType
        },

        hasLocationError() {
            return this.errors.has(this.currentField.attribute)
        },

        mapErrorClasses() {
            return this.hasLocationError ? this.errorClass : ''
        }
    },
    methods: {
        fill(formData) {
            if (this.currentField.visible) {
                formData.append(this.currentField.attribute, this.fieldValue || '')
            }
        }
    }
}
</script>
