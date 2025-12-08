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
            currentPassword: null,
            newPassword: null,
            confirmNewPassword: null,
            mfaEnabled: false
        }
    },

    props: {
        entity: {
            type: Entity,
            required: true
        },
        myAccount: {
            type: Boolean,
            required: false
        },
    },

    mounted() {
        let api = new API();
        api.GET($MAPAS.baseURL + "auth/passwordvalidationinfos").then(async response => response.json().then(validations => {
            this.passwordRules = validations.passwordRules;
        }));

        if (this.myAccount) {
            api.GET($MAPAS.baseURL + "auth/get_mfa_status").then(async response => response.json().then(data => {
                if (data.success) {
                    this.mfaEnabled = data.mfa_enabled;
                }
            }));
        }
    },

    methods: {
        async toggleMFA() {
            let api = new API();
            let data = { enable: this.mfaEnabled };

            // Revertir cambio visual hasta confirmar respuesta
            // Pero como v-model actualiza antes, lo dejamos así y si falla revertimos.

            await api.POST($MAPAS.baseURL + "auth/toggle_mfa", data).then(response => response.json().then(dataReturn => {
                if (dataReturn.success) {
                    this.messages.success(this.mfaEnabled ? 'MFA Activado' : 'MFA Desactivado');
                } else {
                    this.mfaEnabled = !this.mfaEnabled; // Revertir
                    this.messages.error('Error al actualizar MFA');
                }
            })).catch(() => {
                this.mfaEnabled = !this.mfaEnabled;
                this.messages.error('Error de conexión');
            });
        },
        async changePassword(modal) {
            let api = new API();
            if (this.myAccount) {
                let data = {
                    'current_password': this.currentPassword,
                    'new_password': this.newPassword,
                    'confirm_new_password': this.confirmNewPassword,
                }
                await api.POST($MAPAS.baseURL + "autenticacao/changepassword", data).then(response => response.json().then(dataReturn => {
                    if (dataReturn.error) {
                        this.throwErrors(dataReturn.data);
                    } else {
                        this.messages.success('Senha alterada com sucesso!');
                        this.cancel(modal);
                    }
                }));
            } else {
                let data = {
                    'new_password': this.newPassword,
                    'confirm_new_password': this.confirmNewPassword,
                    'email': this.entity.email,
                }
                await api.POST($MAPAS.baseURL + "autenticacao/adminchangeuserpassword", data).then(response => response.json().then(dataReturn => {
                    if (dataReturn.error) {
                        this.throwErrors(dataReturn.data);
                    } else {
                        this.messages.success('Senha alterada com sucesso!');
                        this.cancel(modal);
                    }
                }));
            }
        },

        cancel(modal) {
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

        togglePassword(id, event) {
            if (document.getElementById(id).type == 'password') {
                event.target.style.background = "url('https://api.iconify.design/carbon/view-off-filled.svg') no-repeat center center / 22.5px"
                document.getElementById(id).type = 'text';
            } else {
                event.target.style.background = "url('https://api.iconify.design/carbon/view-filled.svg') no-repeat center center / 22.5px"
                document.getElementById(id).type = 'password';
            }
        },
    },
});