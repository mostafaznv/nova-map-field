<template>
    <default-field :field="currentField" :errors="allErrors" :full-width-content="true" :show-help-text="!isReadonly && showHelpText">
        <template #field>
            <div
                class="z-10 p-0 w-full form-control form-input-bordered overflow-hidden relative"
                :class="mapErrorClasses"
                :style="{height: currentField.mapHeight + 'px'}"
            >
                <point-form-field
                    v-if="mapType === 'POINT'"
                    ref="mapField"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <polygon-form-field
                    v-else-if="mapType === 'POLYGON'"
                    ref="mapField"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />

                <multi-polygon-form-field
                    v-else-if="mapType === 'MULTI_POLYGON'"
                    ref="mapField"
                    v-model="fieldValue"
                    :field="currentField"
                    :resource-id="resourceId"
                    :resource-name="resourceName"
                />
            </div>

            <map-export
                v-if="currentField?.capture?.enabled"
                v-model="image"
                :field="currentField"
                :field-value="fieldValue"
            />
        </template>
    </default-field>
</template>

<script>
import {DependentFormField, HandlesValidationErrors, Errors} from 'laravel-nova'
import PointFormField from './form-fields/PointFormField'
import PolygonFormField from './form-fields/PolygonFormField'
import MultiPolygonFormField from './form-fields/MultiPolygonFormField'
import MapExport from './other/MapExport'


export default {
    mixins: [DependentFormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        MapExport,
        PolygonFormField,
        MultiPolygonFormField,
        PointFormField
    },
    data() {
        return {
            fieldValue: '',
            image: null
        }
    },
    watch: {
        fieldValue(value) {
            this.emitFieldValueChange(this.currentField.attribute, value)
        }
    },
    computed: {
        allErrors() {
            const attribute = this.currentField.attribute
            const valueKey = attribute + '.value'
            const imageKey = attribute + '.image'

            const errors = {}

            errors[valueKey] = [
                ...(this.errors.has(valueKey) ? this.errors.get(valueKey) : []),
                ...(this.errors.has(imageKey) ? this.errors.get(imageKey) : [])
            ]

            return new Errors(errors)
        },

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
                formData.append(this.currentField.attribute + '[value]', this.fieldValue || '')
                formData.append(this.currentField.attribute + '[image]', this.image || '')
            }
        }
    }
}
</script>
