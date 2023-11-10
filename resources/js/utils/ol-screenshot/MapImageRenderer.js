class MapImageRenderer {
    async getImage(param) {
        const map = param.map

        const mapCanvas = document.createElement('canvas')
        const selectors = map.getViewport().querySelectorAll('.ol-layer canvas')

        mapCanvas.width = param.width
        mapCanvas.height = param.height
        const mapContext = mapCanvas.getContext('2d')

        Array.prototype.forEach.call(selectors, (canvas) => {
            if (canvas.width > 0) {
                const opacity = canvas.parentNode.style.opacity
                mapContext.globalAlpha = opacity === '' ? 1 : Number(opacity)

                const transform = canvas.style.transform
                const matrix = transform.match(/^matrix\(([^\(]*)\)$/)[1].split(',').map(Number);

                CanvasRenderingContext2D.prototype.setTransform.apply(mapContext, matrix)
                mapContext.drawImage(canvas, 0, 0)
            }
        })

        return {
            img: mapCanvas.toDataURL(param.format),
            file: await this.canvasToFile(mapCanvas, param.format)
        }
    }

    async canvasToFile(canvas, format) {
        const fileName = format === 'image/png' ? 'capture.png' : 'capture.jpg'

        return await new Promise(
            (resolve) => canvas.toBlob(
                (blob) => {
                    const file = new File(
                        [blob],
                        fileName,
                        {
                            type: format
                        }
                    )

                    resolve(file)
                },
                format,
            )
        )
    }
}

export default new MapImageRenderer()
