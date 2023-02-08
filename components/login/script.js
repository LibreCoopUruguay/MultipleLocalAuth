app.component('login', {
    template: $TEMPLATES['login'],

    components: {
        VueRecaptcha
    },

    setup() {
        const messages = useMessages();
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
            recoveryEmailSent: false
        }
    },

    props: {
        config: {
            type: String,
            required: true
        },
        recoveryMode: {
            type: Boolean,
            default: false
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
    },

    methods: {

        /* Do login */
        async doLogin() {
            let api = new API();
               
            let dataPost = {
                'email': this.email,
                'password': this.password,
                'g-recaptcha-response': this.recaptchaResponse
            }

            await api.POST($MAPAS.baseURL+"autenticacao/login", dataPost).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    window.location.href = $MAPAS.baseURL+'panel';
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

            await api.POST($MAPAS.baseURL+"autenticacao/recover", dataPost).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    this.recoveryEmailSent = true;
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
            if (errors['captcha']) {
                Object.keys(errors['captcha']).forEach(key => {
                    messages.error(errors['captcha'][key]);
                });
            }
            if (errors['email']) {
                Object.keys(errors['email']).forEach(key => {
                    messages.error(errors['email'][key]);
                });
            }
            if (errors['confirmEmail']) {
                Object.keys(errors['confirmEmail']).forEach(key => {
                    messages.error(errors['confirmEmail'][key]);
                });
            }
            if (errors['sendEmail']) {
                Object.keys(errors['sendEmail']).forEach(key => {
                    messages.error(errors['sendEmail'][key]);
                });
            }
        },
    },
});
