export default {
    data() {
        return {
            center: [0, 0],
            projection: this.field.defaultProjection,
            zoom: this.field.zoom,
            withZoomControl: this.field.withZoomControl,
            withZoomSlider: this.field.withZoomSlider,
            withFullScreenControl: this.field.withFullScreenControl,
            mapHeight: this.field.mapHeight,
            markerIcon: this.field.markerIcon,
            mapType: this.field.mapType,
            rotation: 0
        }
    },
    computed: {
        mapStyles() {
            return {
                height: this.mapHeight + 'px'
            }
        }
    },
    watch: {
        fieldValue: {
            immediate: true,
            deep: true,
            handler(value) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    created() {
        this.initCenter()
    }
}
