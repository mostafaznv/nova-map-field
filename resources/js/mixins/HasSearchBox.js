import Geocoder from 'ol-geocoder'

export default {
    mounted() {
        const search = this.field.search

        if (search.isEnabled && !this.isReadonly) {
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

            geocoder.element?.querySelector('input').addEventListener('click', () => {
                this.isDirty = true
            })

            this.$refs.map.map.addControl(geocoder)
        }
    }
}
