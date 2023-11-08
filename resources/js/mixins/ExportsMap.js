class Map {
    constructor(map) {
        this._map = map
    }


    async sync() {
        await this._map.renderSync()
    }

    getZoom() {
        return this._map.getView().getZoom()
    }

    getCenter() {
        return this._map.getView().getCenter()
    }

    setSize(width, height) {
        this._map.setSize([width, height])
    }

    setZoom(zoom) {
        this._map.getView().setZoom(zoom)
    }

    setCenter(center) {
        this._map.getView().setCenter(center)
    }

    updateSize() {
        this._map.updateSize()
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
        const mapCanvas = document.createElement('canvas')

        mapCanvas.width = width
        mapCanvas.height = height

        const mapContext = mapCanvas.getContext('2d')

        Array.prototype.forEach.call(
            this._map.getViewport().querySelectorAll('.ol-layer canvas, canvas.ol-layer'), function (canvas) {
                if (canvas.width > 0) {
                    const opacity = canvas.parentNode.style.opacity || canvas.style.opacity
                    mapContext.globalAlpha = opacity === '' ? 1 : Number(opacity)

                    const transform = canvas.style.transform

                    // Apply the transform to the export map context
                    CanvasRenderingContext2D.prototype.setTransform.apply(
                        mapContext,
                        transform
                            ? transform.match(/^matrix\(([^\(]*)\)$/)[1].split(',').map(Number)
                            : [
                                parseFloat(canvas.style.width) / canvas.width, 0, 0,
                                parseFloat(canvas.style.height) / canvas.height, 0, 0,
                            ]
                    )


                    if (canvas.parentNode.style.backgroundColor) {
                        mapContext.fillStyle = canvas.parentNode.style.backgroundColor
                        mapContext.fillRect(0, 0, canvas.width, canvas.height)
                    }

                    mapContext.drawImage(canvas, 0, 0)
                }
            }
        )

        mapContext.globalAlpha = 1
        mapContext.setTransform(1, 0, 0, 1, 0, 0)

        return await new Promise(
            (resolve) => mapCanvas.toBlob(
                (blob) => {
                    const file = new File(
                        [blob],
                        'capture.png',
                        {
                            type: 'image/png'
                        }
                    )

                    resolve(file)
                },
                'image/png',
            )
        )
    }
}


export default {
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
                return null
            }

            const map = new Map(this.$refs.map.map)
            const width = this.captureConfig.width
            const height = this.captureConfig.height

            const currentZoom = map.getZoom()
            const currentCenter = map.getCenter()

            map.setSize(width, height)
            await map.sync()

            await map.fit(this.$refs.source.source.getExtent(), this.captureConfig)
            await map.sync()

            const file = await map.export(width, height)

            map.updateSize()
            map.setZoom(currentZoom)
            map.setCenter(currentCenter)

            await map.sync()

            return file
        }
    },

    created() {
        if (this.field?.capture) {
            this.captureConfig = this.field.capture
            this.captureConfig.enabled = true
        }
    }
}
