app.component('create-account', {
    template: $TEMPLATES['create-account'],

    components: {
        VueRecaptcha
    },

    setup() {
        const messages = useMessages();
        const text = Utils.getTexts('create-account')
        return { text }
    },

    data() {
        const terms = $MAPAS.config.LGPD;
        const termsQtd = Object.entries(terms).length;

        return {
            actualStep: 1,
            totalSteps: termsQtd + 2,
            terms,
            passwordRules: {},
            strongness: 0,
            strongnessClass: 'fraco',
            slugs: [],
            email: '',
            cpf: '',
            password: '',
            confirmPassword: '',
            agent: null,
            recaptchaResponse: '',
            created: false,
            emailSent: false,
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

        Object.entries(this.terms).forEach(function (term, index) {
            document.querySelector("#term" + index).addEventListener('scroll', function () {
                if (Math.ceil(this.scrollTop + this.offsetHeight) >= this.scrollHeight) {
                    document.querySelector("#acceptTerm" + index).classList.remove('disabled');
                }
            });
        });
    },

    destroyed() {
        window.removeEventListener('scroll');
    },

    computed: {

        step () {
            if (this.actualStep >= this.totalSteps) {
                this.actualStep = this.totalSteps;
            } 

            if (this.actualStep <= 1) {
                this.actualStep = 1;
            }

            return this.actualStep;
        },

        configs() {
            return JSON.parse(this.config);
        },

        cpfMask() {
            this.cpf = this.cpf.replace(/\D/g,"")
            this.cpf = this.cpf.replace(/(\d{3})(\d)/,"$1.$2")
            this.cpf = this.cpf.replace(/(\d{3})(\d)/,"$1.$2")
            this.cpf = this.cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
        },

        passwordStrongness() {
            if (this.password) {
                let passwordMustHaveCapitalLetters = /[A-Z]/;
                let passwordMustHaveLowercaseLetters = /[a-z]/;
                let passwordMustHaveSpecialCharacters = /[$@$!%*#?&\.\,\:<>+\_\-\"\'()]/;
                let passwordMustHaveNumbers = /[0-9]/;
                let minimumPasswordLength = 8;
                let pwd = this.password;
                let rules = [];

                if (this.passwordRules.passwordMustHaveCapitalLetters) {
                    rules.push(passwordMustHaveCapitalLetters);
                }

                if (this.passwordRules.passwordMustHaveLowercaseLetters) {
                    rules.push(passwordMustHaveLowercaseLetters);
                }

                if (this.passwordRules.passwordMustHaveSpecialCharacters) {
                    rules.push(passwordMustHaveSpecialCharacters);
                }

                if (this.passwordRules.passwordMustHaveNumbers) {
                    rules.push(passwordMustHaveNumbers);
                }

                if (this.passwordRules.minimumPasswordLength) {
                    minimumPasswordLength = this.passwordRules.minimumPasswordLength
                }

                let rulesLength = rules.length;
                let prog = rules.reduce(function (accumulator, test) {
                    return accumulator + (test.test(pwd) ? 1 : 0);
                }, 0);

                let percentToAdd = 100 / (rulesLength + 1);
                let currentPercent = prog * 100 / (rulesLength + 1);

                if (pwd.length > minimumPasswordLength - 1) {
                    currentPercent = currentPercent + percentToAdd;
                }

                let strongness = currentPercent.toFixed(0)
                if (strongness >= 0 && strongness <= 40) {
                    this.strongnessClass = 'fraco';
                }
                if (strongness >= 40 && strongness <= 90) {
                    this.strongnessClass = 'medio';
                }
                if (strongness >= 90 && strongness <= 100) {
                    this.strongnessClass = 'forte';
                }   

                return currentPercent.toFixed(0);
            } else {
                return 0;
            }

        }
    },

    methods: {
        startAgent() {
            this.agent = Vue.ref(new Entity('agent'));
            this.agent.type = 1;
            this.agent.terms = { area: [] }
        },

        releaseAcceptButton() {
            setTimeout(() => {
                let termArea = this.$refs.terms[this.step - 2];
                /* Em caso do termo ser curto o suficiente para não aparecer o scroll */
                if (termArea && termArea.offsetHeight < 600) {
                    document.querySelector("#acceptTerm" + (this.step - 2)).classList.remove('disabled');
                }
            }, 1000);
        },

        async nextStep() {
            if (this.step <= this.totalSteps) {          
                
                if(this.actualStep == 1) {                    
                    if (await this.validateFields()) {
                        ++this.actualStep;
                    }                    
                } else {
                    if (this.step == this.totalSteps - 1) {
                        this.startAgent();
                    }
                    ++this.actualStep;
                    this.releaseAcceptButton();
                }

            }
        },

        previousStep() {
            if (this.step > 1) {
                --this.actualStep;
            }
        },

        /* Terms */
        acceptTerm(slug) {
            this.slugs.push(slug);
        },

        /* Do register */
        async register() {
            let api = new API();

            if (this.validateAgent()) {                
                let dataPost = {
                    'name': this.agent.name,
                    'email': this.email,
                    'cpf': this.cpf,
                    'password': this.password,
                    'confirm_password': this.confirmPassword,
                    'slugs': this.slugs,
                    'g-recaptcha-response': this.recaptchaResponse,
                    'agentData': {
                        'name': this.agent.name,
                        'terms:area': this.agent.terms.area,
                        'shortDescription': this.agent.shortDescription,
                    },
                }

                await api.POST($MAPAS.baseURL+"autenticacao/register", dataPost).then(response => response.json().then(dataReturn => {
                    if (dataReturn.error) {
                        this.throwErrors(dataReturn.data);
                    } else {
                        this.created = true;
                        if (dataReturn.emailSent) {
                            this.emailSent = true;
                        }
                    }
                }));
            }
        },

        /* Cancel register */
        cancel() {
            this.actualStep = 1;
            this.strongnessClass = 'fraco';
            this.email = '';
            this.cpf = '';
            this.password = '';
            this.confirmPassword = '';
            this.agent = null;
        },
        
        /* Validações */    
        
        async verifyCaptcha(response) {
            this.recaptchaResponse = response;
        },

        expiredCaptcha() {
            this.recaptchaResponse = '';
        },

        async validateFields() {
            let api = new API();
            let success = true;
            let data = {
                'cpf': this.cpf,
                'email': this.email,
                'password': this.password,
                'confirm_password': this.confirmPassword,
                'g-recaptcha-response': this.recaptchaResponse,
            }
            await api.POST($MAPAS.baseURL+"autenticacao/validate", data).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                    success = false;
                } else {
                    this.recaptchaResponse = '';
                }
            }));

            return success;
        },

        throwErrors(errors) {
            if (errors['user']) {
                Object.keys(errors['user']).forEach(key => {
                    errors['user'][key].forEach(function(value){
                        messages.error(value);
                    });
                });
            }
            if (errors['captcha']) {
                Object.keys(errors['captcha']).forEach(key => {
                    messages.error(errors['captcha'][key]);
                });
            }
        },

        validateAgent() {
            if (!this.agent.name) {
                messages.error(__('Nome obrigatório', 'create-account'));
                return false;
            }
            if (!this.agent.shortDescription) {
                messages.error(__('Descrição obrigatória', 'create-account'));
                return false;
            }
            if (this.agent.terms.area.length == 0) {
                messages.error(__('Área de atuação obrigatória', 'create-account'));
                return false;
            }
            return true;
        }
    },
});
