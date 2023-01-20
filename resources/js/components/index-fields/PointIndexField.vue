<template>
    <div>
        <template v-if="modalMode === true">
            <location-detail-modal>
                <template v-slot:content>
                    <location-detail
                        :location-is-not-set="locationIsNotSet"
                        :latitude="latitude"
                        :longitude="longitude"
                    />
                </template>
            </location-detail-modal>
        </template>

        <location-detail
            v-else
            :location-is-not-set="locationIsNotSet"
            :latitude="latitude"
            :longitude="longitude"
        />
    </div>
</template>

<script>
import LocationDetailModal from '../other/LocationDetailModal'
import LocationDetail from '../other/LocationDetail'

export default {
    props: ['field', 'modalMode'],
    components: {
        LocationDetail,
        LocationDetailModal
    },
    data() {
        return {
            value: JSON.parse(this.field.value || '{}')
        }
    },
    computed: {
        latitude() {
            return this.value.latitude
        },

        longitude() {
            return this.value.longitude
        },

        hasLatitude() {
            return this.latitude !== 0 && this.latitude !== null
        },

        hasLongitude() {
            return this.longitude !== 0 && this.longitude !== null
        },

        locationIsSet() {
            return this.hasLatitude || this.hasLongitude
        },

        locationIsNotSet() {
            return !this.locationIsSet
        }
    }
}
</script>
