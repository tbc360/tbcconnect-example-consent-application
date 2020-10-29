<template>
    <jet-action-section>

        <template #title>
            Cancel Tbcconnect Agreement
        </template>

        <template #content>
            <template v-if="agree">
                <div class="max-w-xl text-sm text-gray-600">
                    Once your cancel agreement, tbcconnect don't pick your personal data anymore.
                </div>

                <div class="mt-5">
                    <jet-danger-button @click.native="openDialog = true">
                        cancel agreement
                    </jet-danger-button>
                </div>
            </template>
            <template v-else>
                <div class="max-w-xl text-sm text-gray-600">
                    your canceled agreement successfully!
                </div>
            </template>

            <!-- Cancel Agreement Modal -->
            <jet-dialog-modal :show="openDialog" @close="openDialog = false">
                <template #title>
                    Cancel Agreement
                </template>

                <template #content>
                    Are you sure you want to cancel Agreement
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="openDialog = false">
                        No
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="cancelAgreement">
                        Yes
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        props: ['user'],

        components: {
            JetActionSection,
            JetDangerButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        data() {
            return {
                openDialog: false,
                agree: true
            }
        },

        methods: {
            cancelAgreement() {
                _paq.push(['logout']);

                axios.post(route('consent.agree').url(), { agree: false }).then(({ data }) => {
                    if (data.success) {
                        this.openDialog = false
                        this.agree = false
                    }
                })
            },
        }
    }
</script>
