<template>
    <div>
        <span v-if="doesntHaveCoordinates">&#8212;</span>

        <template v-else>
            <div v-for="(coordinate, key) in coordinates" class="coordinate" :key="key">
                <div class="map-location-information">
                    <location-svg-icon />
                    <span>{{ __('Latitude') }}: {{ coordinate[0] }}</span>
                </div>

                <div class="map-location-information">
                    <location-svg-icon />
                    <span>{{ __('Longitude') }}: {{ coordinate[1] }}</span>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import LocationSvgIcon from './LocationSvgIcon'

export default {
    name: 'LocationsDetail',
    components: {
        LocationSvgIcon
    },
    props: {
        doesntHaveCoordinates: {
            type: Boolean,
            required: true
        },
        coordinates: {
            type: Array,
            required: true
        },
    }
}
</script>

<style lang="scss" scoped>
.coordinate {
    // border-bottom: solid 1px rgba(var(--colors-gray-200),var(--tw-border-opacity));
    padding-bottom: 4px;
    margin-bottom: 4px;

    &:last-child {
        // border-bottom: 0;
        padding-bottom: 0;
        margin-bottom: 0;
    }
}
</style>
