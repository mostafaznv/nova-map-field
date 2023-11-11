import mapImageRenderer from './MapImageRenderer'


/**
 * credit: https://github.com/jmmluna/ol-map-screenshot/
 */
class MapExporter {
    async getScreenshot(map, param) {
        const promise = new Promise((resolve, reject) => {
            const options = this.getOptions(map, param)

            if (options.validation) {
                reject({
                    message: options.validation
                })

                return
            }

            const mapCurrentSize = map.getSize()

            this.setResolutionAndSize(map, options.width, options.height)

            map.once('rendercomplete', async () => {
                const mapImage = await mapImageRenderer.getImage(options)
                map.setSize(mapCurrentSize)

                resolve({
                    img: mapImage.img,
                    file: mapImage.file,
                    width: options.width,
                    height: options.height
                })
            })
        })

        map.renderSync()

        return promise
    }

    setResolutionAndSize(map, width, height) {
        const printSize = [width, height]
        map.setSize(printSize)

        const mapSize = map.getSize()
        const scaling = Math.min(width / mapSize[0], height / mapSize[1])
        const viewResolution = map.getView().getResolution()

        map.getView().setResolution(viewResolution / scaling)
    }

    getOptions(map, param) {
        const size = map.getSize()
        const width = param?.width ? param.width : size[0]
        const height = param?.height ? param.height : size[1]


        return {
            map: map,
            width: width,
            height: height,
            format: 'image/jpeg'
        }
    }
}

export default new MapExporter()
