import OpenLayersMap from 'vue3-openlayers'
import FormField from './components/FormField'
import IndexField from './components/IndexField'
import DetailField from './components/DetailField'

Nova.booting(Vue => {
    Vue.use(OpenLayersMap)

    Vue.component('form-nova-map-field', FormField)
    Vue.component('index-nova-map-field', IndexField)
    Vue.component('detail-nova-map-field', DetailField)
})
