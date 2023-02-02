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
            login: '',
            password: '',
            recaptchaResponse: '',
        }
    },

    props: {
        config: {
            type: String,
            required: true
        }
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
                'email': this.login,
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
            if (errors['confirmEmail']) {
                Object.keys(errors['confirmEmail']).forEach(key => {
                    messages.error(errors['confirmEmail'][key]);
                });
            }
        },
    },
});
