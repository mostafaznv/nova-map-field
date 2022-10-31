import ClearMap from '../controls/ClearMap'

export default {
    mounted() {
        if (this.field.withClearMapControl && !this.readonly) {
            this.$refs.map.map.addControl(new ClearMap(this))
        }
    }
}
