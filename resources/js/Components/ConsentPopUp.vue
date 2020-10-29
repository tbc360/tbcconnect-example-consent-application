<template>
    <jet-dialog-modal :show="openDialog" @close="openDialog = false">

        <template #content>
            <div class="consent__text" v-html="consentText"></div>
        </template>

        <template #footer>
            <jet-secondary-button @click.native="confirmConsent(false)">
                reject
            </jet-secondary-button>

            <jet-button class="ml-2" @click.native="confirmConsent(true)">
                Accept
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
import JetButton from '@/Jetstream/Button';
import JetDialogModal from '@/Jetstream/DialogModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';

export default {
    name: 'ConsentPopUp',
    components: {
        JetButton,
        JetDialogModal,
        JetSecondaryButton,
    },
    data() {
        return {
            openDialog: false,
            consentText: ''
        }
    },
    methods: {
        confirmConsent(agree) {
            if (agree) {
                _paq.push(['login', this.$page.user.email, {
                    name: this.$page.user.email
                }]);
            }

            axios.post(route('consent.agree').url(), { agree }).then(({ data }) => {
                if (data.success) {
                    this.openDialog = false
                }
            })
        }
    },
    created() {
        axios.post(route('consent.check').url()).then(({ data }) => {
            if (data.success && data.data.show_popup) {
                this.openDialog = true
                this.consentText = data.data.terms_and_conditions
            }
        })
    }
};
</script>

<style scoped>
    .consent__text {
        max-height: 20rem;
        overflow-y: scroll;
    }
</style>
