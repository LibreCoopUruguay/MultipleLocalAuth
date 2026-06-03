app.component('login', {
    template: $TEMPLATES['login'],

    components: {
        VueRecaptcha
    },

    setup() {
        const text = Utils.getTexts('login')
        return { text }
    },

    data() {
        return {
            email: '',
            password: '',
            confirmPassword: '',
            recaptchaResponse: '',

            passwordRules: {},

            recoveryRequest: false,
            recoveryEmailSent: false,

            recoveryMode: ($MAPAS.recoveryMode && $MAPAS.recoveryMode.status) ? $MAPAS.recoveryMode.status : '',
            recoveryToken: ($MAPAS.recoveryMode && $MAPAS.recoveryMode.token) ? $MAPAS.recoveryMode.token : '',
        }
    },

    props: {
        config: {
            type: String,
            required: true
        }
    },

    mounted() {
        let api = new API();
        api.GET($MAPAS.baseURL + "auth/passwordvalidationinfos").then(async response => response.json().then(validations => {
            this.passwordRules = validations.passwordRules;
        }));
    },

    computed: {
        configs() {
            return JSON.parse(this.config);
        },

        multiple() {
            return (this.configs.strategies.Google && this.configs.strategies.Google.visible) && (this.configs.strategies.govbr && this.configs.strategies.govbr.visible);
        }
    },

    methods: {

        /* Do login */
        async doLogin() {
            console.log('[MFA DEBUG] doLogin called');
            let api = new API();

            let dataPost = {
                'email': this.email,
                'password': this.password,
                'g-recaptcha-response': this.recaptchaResponse
            }

            console.log('[MFA DEBUG] Sending login request', dataPost);

            await api.POST($MAPAS.baseURL + "autenticacao/login", dataPost).then(response => response.json().then(dataReturn => {
                console.log('[MFA DEBUG] Login response received:', dataReturn);

                if (dataReturn.error) {
                    console.log('[MFA DEBUG] Error in response, throwing errors');
                    this.throwErrors(dataReturn.data);
                } else {
                    console.log('[MFA DEBUG] Success response');
                    console.log('[MFA DEBUG] mfa_required:', dataReturn.mfa_required);
                    console.log('[MFA DEBUG] redirectTo:', dataReturn.redirectTo);

                    // Check if MFA is required
                    if (dataReturn.mfa_required) {
                        console.log('[MFA DEBUG] MFA required, redirecting to:', dataReturn.redirectTo || $MAPAS.baseURL + 'autenticacao/mfa');
                        // Redirect to MFA verification page
                        window.location.href = dataReturn.redirectTo || $MAPAS.baseURL + 'autenticacao/mfa';
                    } else if (dataReturn.redirectTo) {
                        console.log('[MFA DEBUG] Normal redirect to:', dataReturn.redirectTo);
                        window.location.href = dataReturn.redirectTo;
                    } else {
                        console.log('[MFA DEBUG] Default redirect to panel');
                        window.location.href = Utils.createUrl('panel', 'index');
                    }
                }
            }));
        },

        /* Request password recover */
        async requestRecover() {
            let api = new API();

            let dataPost = {
                'email': this.email,
                'g-recaptcha-response': this.recaptchaResponse
            }

            await api.POST($MAPAS.baseURL + "autenticacao/recover", dataPost).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    this.recoveryEmailSent = true;
                }
            }));
        },

        async doRecover() {
            let api = new API();

            let dataPost = {
                'password': this.password,
                'confirm_password': this.confirmPassword,
                'token': this.recoveryToken
            }

            await api.POST($MAPAS.baseURL + "autenticacao/dorecover", dataPost).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    const messages = useMessages();
                    messages.success('Senha alterada com sucesso!');
                    setTimeout(() => {
                        window.location.href = $MAPAS.baseURL + 'autenticacao';
                    }, "1000")
                }
            }));
        },

        /* Validações */
        async verifyCaptcha(response) {
            this.recaptchaResponse = response;
        },

        expiredCaptcha() {
            this.recaptchaResponse = '';
        },

        throwErrors(errors) {
            const messages = useMessages();

            if (this.recaptchaResponse !== '') {
                grecaptcha.reset();
                this.expiredCaptcha();
            }

            for (let key in errors) {
                for (let val of errors[key]) {
                    messages.error(val);
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
