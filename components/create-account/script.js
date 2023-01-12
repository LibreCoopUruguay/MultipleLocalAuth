app.component('create-account', {
    template: $TEMPLATES['create-account'],
    
    // define os eventos que este componente emite
    emits: [],

    setup() { 
        // os textos estão localizados no arquivo texts.php deste componente 
        const text = Utils.getTexts('create-account')
        return { text }
    },

    
    mounted() {
        let api = new API();
        api.GET($MAPAS.baseURL+"auth/passwordvalidationinfos").then(async response => response.json().then(validations => { 
            this.passwordRules = validations.passwordRules; 
        }));

        Object.entries(this.terms).forEach(function(term, index) {
            document.querySelector("#term"+index).addEventListener('scroll', function() {
                if (Math.ceil(this.scrollTop+this.offsetHeight) == this.scrollHeight) {
                    document.querySelector("#acceptTerm"+index).classList.remove('disabled');
                }
            });
        });
    },

    destroyed () {
        window.removeEventListener('scroll');
    },

    data() {        
        const terms = $MAPAS.config.LGPD;
        const termsQtd = Object.entries(terms).length;

        return {
            step: 1,
            totalSteps: termsQtd + 2,
            terms: terms,

            passwordRules: {},
            strongness: 0,
            strongnessClass: 'fraco',

            email: '',
            cpf: '',
            password: '',
            confirmPassword: '',
            agent: null,
        }
    },

    computed: {
        passwordStrongness() {
            if (this.password) {
                let passwordMustHaveCapitalLetters = /[A-Z]/;
                let passwordMustHaveLowercaseLetters = /[a-z]/;
                let passwordMustHaveSpecialCharacters = /[$@$!%*#?&\.\,\:<>+\_\-\"\'()]/;
                let passwordMustHaveNumbers = /[0-9]/;
                let minimumPasswordLength = 8;
                let pwd = this.password;
                let rules = [];                  

                if(this.passwordRules.passwordMustHaveCapitalLetters) {
                    rules.push(passwordMustHaveCapitalLetters);
                }

                if(this.passwordRules.passwordMustHaveLowercaseLetters) {
                    rules.push(passwordMustHaveLowercaseLetters);
                }

                if(this.passwordRules.passwordMustHaveSpecialCharacters) {
                    rules.push(passwordMustHaveSpecialCharacters);
                }

                if(this.passwordRules.passwordMustHaveNumbers) {
                    rules.push(passwordMustHaveNumbers);
                }

                if(this.passwordRules.minimumPasswordLength) {
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

                this.strongness = currentPercent.toFixed(0);
            } else {
                this.strongness = 0;
            }

            if (this.strongness >= 0 && this.strongness <= 40) {
                this.strongnessClass = 'fraco';
            }
            if (this.strongness >= 40 && this.strongness <= 90) {
                this.strongnessClass = 'medio';
            }
            if (this.strongness >= 90 && this.strongness <= 100) {
                this.strongnessClass = 'forte';
            }
        },
        errosSenha() {
            if (this.password.length == 0) {
                return '';
            }

            if (this.strongness < 40) {
                return __('Senha fraca', 'create-account')
            }

            if (this.password.length < this.passwordRules.minimumPasswordLength) {
                return __('Senha pequena', 'create-account');
            }

            if (this.password !== this.confirmPassword) {
                return __('Senhas diferentes', 'create-account');
            }

            return false
        }
    },
    
    methods: { 
        startAgent() {
            this.agent = Vue.ref(new Entity('agent'));
            this.agent.type = 1;
            this.agent.terms = {area: []}
        },

        releaseAcceptButton() {
            setTimeout(() => { 
                let termArea = this.$refs.terms[this.step-2];
                /* Em caso do termo ser curto o suficiente para não aparecer o scroll */
                if ( termArea && termArea.offsetHeight < 600 ) {
                    document.querySelector("#acceptTerm"+(this.step-2)).classList.remove('disabled');
                }
            }, 1000);
        },

        nextStep() {
            if (this.step <= this.totalSteps) {
                if ((this.step == 1 && this.errosSenha != '')) {
                    return false;
                }
                if (this.step == this.totalSteps-1) {
                    this.startAgent();
                }
                ++this.step;
                this.releaseAcceptButton();
            }
        },

        previousStep() {
            if (this.step > 1) {
                --this.step;
            }
        },

        register() {
            let api = new API();
            let data = {
                'name': this.agent.name,
                'email': this.email,
                'cpf': this.cpf,
                'password': this.password,
                'confirm_password': this.confirmPassword
            }
            
            /* api.POST($MAPAS.baseURL+"autenticacao/register", data).then( async response => {
                console.log('teste', response);
            }); */
        },

        cancelarCadastro() {
            this.step =  1;
            this.strongness =  0;
            this.strongnessClass =  'fraco';
            this.email =  '';
            this.cpf =  '';
            this.password =  '';
            this.confirmPassword =  '';
            this.agent =  null;
        },
    },
});
