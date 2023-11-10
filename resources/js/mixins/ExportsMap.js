import olMapScreenshot from '../utils/ol-screenshot/MapExporter'

class Map {
    constructor(map) {
        this._map = map
    }

    destroy() {
        this._map = null
    }


    async sync() {
        await this._map.renderSync()
    }

    getCenter() {
        return this._map.getView().getCenter()
    }

    setSize(width, height) {
        this._map.setSize([width, height])
    }

    setCenter(center) {
        this._map.getView().setCenter(center)
    }

    async fit(extent, config) {
        await this._map.getView().fit(extent, {
            size: [
                config.width, config.height
            ],
            padding: config.padding,
            maxZoom: config.maxZoom,
            nearest: config.nearest,
        })
    }

    async export(width, height) {
        try {
            return await olMapScreenshot.getScreenshot(this._map, {
                width: width,
                height: height
            })
        }
        catch (e) {
            console.error(e)

            return {
                img: null,
                file: null
            }
        }
    }
}


export default {
    props: {
        exportable: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            captureConfig: {
                enabled: false,
                width: null,
                height: null,
                maxZoom: null,
                nearest: null,
                padding: null
            }
        }
    },

    methods: {
        async capture() {
            if (this.captureConfig.enabled === false) {
                return {
                    img: null,
                    file: null
                }
            }

            const map = new Map(this.$refs.map.map)
            const width = this.captureConfig.width
            const height = this.captureConfig.height

            map.setCenter(map.getCenter())

            await map.sync()

            await map.fit(this.$refs.source.source.getExtent(), this.captureConfig)
            await map.sync()

            const res = await map.export(width, height)

            map.destroy()

            return res
        }
    },

    created() {
        if (this.field?.capture) {
            this.captureConfig = this.field.capture
            this.captureConfig.enabled = true
        }
    }
}
