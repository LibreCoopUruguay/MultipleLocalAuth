app.component('change-password', {
    template: $TEMPLATES['change-password'],

    components: {
        VueRecaptcha
    },

    setup() {
        const messages = useMessages();
        const text = Utils.getTexts('change-password')
        return { text, messages }
    },

    data() {
        return { 
            passwordRules: {},
            currentPassword: '',
            newPassword: '',
            confirmNewPassword: ''
        }
    },

    mounted() {        
        let api = new API();
        api.GET($MAPAS.baseURL + "auth/passwordvalidationinfos").then(async response => response.json().then(validations => {
            this.passwordRules = validations.passwordRules;
        }));
    },

    methods: {
        async changePassword(modal) {
            let api = new API();
            let data = {
                'new_password': this.newPassword,
                'confirm_new_password': this.confirmNewPassword
            }
            await api.POST($MAPAS.baseURL+"autenticacao/newpassword", data).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    this.messages.success('Senha alterada com sucesso!');
                    this.cancel(modal);
                }
            }));
        },

        cancel(modal) {
            this.currentPassword = '';
            this.newPassword = '';
            this.confirmNewPassword = '';
            modal.close();
        },

        throwErrors(errors) {
            for (let key in errors) {
                for (let val of errors[key]) {
                    this.messages.error(val);
                }
            }
        },
    },
});
