<template>
    <div>
        <template v-if="modalMode === true">
            <location-detail-modal>
                <template v-slot:content>
                    <div v-for="(coordinate, key) in coordinates" :key="key" class="mb-4">
                        <span class="mb-1 block">{{ __('Polygon') }} {{ key + 1}}</span>

                        <locations-detail
                            :doesnt-have-coordinates="doesntHaveCoordinates"
                            :coordinates="coordinate[0]"
                        />
                    </div>
                </template>
            </location-detail-modal>
        </template>

        <template v-else>
            <div v-for="(coordinate, key) in coordinates" :key="key" class="mb-4">
                <span class="mb-1 block">{{ __('Polygon') }} {{ key + 1}}</span>

                <locations-detail
                    :doesnt-have-coordinates="doesntHaveCoordinates"
                    :coordinates="coordinate[0]"
                />
            </div>
        </template>
    </div>
</template>

<script>
import LocationDetailModal from '../other/LocationDetailModal'
import LocationsDetail from '../other/LocationsDetail'

export default {
    props: ['field', 'modalMode'],
    components: {
        LocationsDetail,
        LocationDetailModal
    },
    data() {
        return {
            modal: false
        }
    },
    computed: {
        coordinates() {
            if (this.field.value) {
                const coordinates = JSON.parse(this.field.value)

                if (Array.isArray(coordinates) && coordinates.length) {
                    return coordinates
                }
            }

            return []
        },

        hasCoordinates() {
            return this.coordinates.length > 0
        },

        doesntHaveCoordinates() {
            return !this.hasCoordinates
        }
    },
    methods: {
        showModal() {
            this.modal = true
        },

        closeModal() {
            this.modal = false
        }
    }
}
</script>
