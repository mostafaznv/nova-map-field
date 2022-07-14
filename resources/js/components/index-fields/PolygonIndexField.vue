<template>
    <div>
        <template v-if="modalMode === true">
            <location-detail-modal>
                <template v-slot:content>
                    <locations-detail
                        :doesnt-have-coordinates="doesntHaveCoordinates"
                        :coordinates="coordinates"
                    />
                </template>
            </location-detail-modal>
        </template>

        <locations-detail
            v-else
            :doesnt-have-coordinates="doesntHaveCoordinates"
            :coordinates="coordinates"
        />
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
                    return coordinates[0]
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
