<template>
    <div>
        <Button @click="showModal" variant="outline" dusk="open-preview-button">
            {{ __('Show Details') }}
        </Button>

        <Modal
            @close-via-escape="closeModal"
            maxWidth="2xl"
            data-testid="preview-resource-modal"
            :show="modal"
        >
            <Card class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <slot>
                    <ModalHeader class="flex items-center uppercase">{{ __('Details') }}</ModalHeader>

                    <ModalContent class="px-8">
                        <slot name="content" />
                    </ModalContent>
                </slot>

                <ModalFooter>
                    <div class="ml-auto">
                        <Button @click.prevent="closeModal" dusk="close-preview-button">{{ __('Close') }}</Button>
                    </div>
                </ModalFooter>
            </Card>
        </Modal>
    </div>
</template>

<script>
import {Button} from 'laravel-nova-ui'

export default {
    name: 'LocationDetailModal',
    components: {
        Button
    },
    data() {
        return {
            modal: false
        }
    },
    methods: {
        showModal() {
            this.modal = true
        },

        closeModal() {
            this.modal = false
        }
    }
}
</script>
