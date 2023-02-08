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
            recoveryEmailSent: false,
            
            recoveryMode: $MAPAS.recoveryMode?.status ?? '',
            recoveryEmail: $MAPAS.recoveryMode?.email ?? '',
            recoveryToken: $MAPAS.recoveryMode?.token ?? '',
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

        async doRecover() {
            let api = new API();
               
            let dataPost = {
                'email': this.recoveryEmail,
                'password': this.password,
                'confirm_password': this.confirmPassword,
                'token': this.recoveryToken
            }

            await api.POST($MAPAS.baseURL+"autenticacao/dorecover", dataPost).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    messages.success('Senha alterada com sucesso!');
                    setTimeout(() => {
                        window.location.href = $MAPAS.baseURL+'autenticacao';
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
            if (errors['captcha']) {
                Object.keys(errors['captcha']).forEach(key => {
                    messages.error(errors['captcha'][key]);
                });
            }
            if (errors['login']) {
                Object.keys(errors['login']).forEach(key => {
                    messages.error(errors['login'][key]);
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
            if (errors['user']) {
                Object.keys(errors['user']).forEach(key => {
                    messages.error(errors['user'][key]);
                });
            }
            if (errors['password']) {
                Object.keys(errors['password']).forEach(key => {
                    messages.error(errors['password'][key]);
                });
            }
            if (errors['token']) {
                Object.keys(errors['token']).forEach(key => {
                    messages.error(errors['token'][key]);
                });
            }
        },
    },
});
