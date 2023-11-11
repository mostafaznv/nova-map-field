import Geocoder from 'ol-geocoder'

export default {
    mounted() {
        const search = this.field.search
        const isExportable = this.exportable === true

        if (search.isEnabled && !this.isReadonly && !isExportable) {
            const geocoder = new Geocoder('nominatim', {
                provider: search.provider,
                key: search.apiKey || '',
                autoComplete: search.withAutocomplete,
                autoCompleteMinLength: search.autocompleteMinLength,
                autoCompleteTimeout: search.autocompleteTimeout,
                lang: search.language,
                placeholder: search.placeholder,
                targetType: search.boxType,
                limit: search.resultLimit,
                keepOpen: search.resultKeepOpen
            });

            geocoder.on('addresschosen', (evt) => {
                if (this.selectPointOnSearch) {
                    geocoder.getSource().clear()

                    if (this.initValue.longitude === null) {
                        const layer = this.$refs.map.map.getAllLayers()[1]

                        if (layer !== 'undefined') {
                            layer.getSource().clear()
                        }
                    }

                    this.initValue = {
                        longitude: evt.coordinate[0],
                        latitude: evt.coordinate[1]
                    }

                    this.setValue(evt.coordinate[1], evt.coordinate[0])
                }
                else {
                    this.isDirty = true
                }
            });

            this.$refs.map.map.addControl(geocoder)
        }
    }
}
