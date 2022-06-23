<template>
    <div>
        <span v-if="locationIsNotSet">&#8212;</span>

        <template v-else>
            <div class="map-location-information">
                <location-svg-icon />
                <span>{{ __('Latitude') }}: {{ latitude }}</span>
            </div>

            <div class="map-location-information">
                <location-svg-icon />
                <span>{{ __('Longitude') }}: {{ longitude }}</span>
            </div>
        </template>
    </div>
</template>

<script>
import LocationSvgIcon from './LocationSvgIcon'

export default {
    name: 'LocationDetail',
    components: {
        LocationSvgIcon
    },
    props: {
        locationIsNotSet: {
            type: Boolean,
            required: true
        },
        latitude: [Number, String, undefined],
        longitude: [Number, String, undefined]
    }
}
</script>
