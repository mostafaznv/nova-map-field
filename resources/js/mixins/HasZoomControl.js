import Undo from '../controls/Undo'

export default {
    mounted() {
        if (this.field.withUndoControl && !this.readonly) {
            this.$refs.map.map.addControl(new Undo)
        }
    }
}
