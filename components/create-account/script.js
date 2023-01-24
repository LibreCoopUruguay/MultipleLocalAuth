app.component('create-account', {
    template: $TEMPLATES['create-account'],

    setup() {
        const messages = useMessages();
        const text = Utils.getTexts('create-account')
        return { text }
    },

    data() {
        const terms = $MAPAS.config.LGPD;
        const termsQtd = Object.entries(terms).length;

        return {
            step: 1,
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
                if (Math.ceil(this.scrollTop + this.offsetHeight) == this.scrollHeight) {
                    document.querySelector("#acceptTerm" + index).classList.remove('disabled');
                }
            });
        });
    },

    destroyed() {
        window.removeEventListener('scroll');
    },

    computed: {

        strategies() {
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

        nextStep() {
            if (this.step <= this.totalSteps) {            
                if (this.step == 1 && (!this.validateEmail() || !this.validateCPF() || !this.validatePassword() || !this.validateConfirmPassword())) {
                    return false;
                }

                if (this.step == this.totalSteps - 1) {
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

        acceptTerm(slug) {
            this.slugs.push(slug);
        },

        registerTerms(id) {
            let url = Utils.createUrl('lgpd', 'accept');
            let api = new API();
            api.POST(url, [this.slugs, id]);
        },

        async registerAgent(agentId) {
            let api = new API('agent');
            let query = {
                '@select': '*',
                'id': `EQ(`+agentId+`)`
            };    
            let createdAgent = await api.find(query);
            createdAgent.name = this.agent.name;
            createdAgent.terms.area = this.agent.terms.area;
            createdAgent.shortDescription = this.agent.shortDescription;
            createdAgent.save();
        },

        async register() {
            let api = new API();
            let data = {
                'name': this.agent.name,
                'email': this.email,
                'cpf': this.cpf,
                'password': this.password,
                'confirm_password': this.confirmPassword
            }

            if (this.validateAgent()) {                
                await api.POST($MAPAS.baseURL+"autenticacao/register", data).then(response => response.json().then(dataReturn => {
                    this.registerTerms(dataReturn.id);
                    this.registerAgent(dataReturn.profile.id);

                }));
            }
        },

        cancel() {
            this.step = 1;
            this.strongnessClass = 'fraco';
            this.email = '';
            this.cpf = '';
            this.password = '';
            this.confirmPassword = '';
            this.agent = null;
        },
        
        /* Validações */        
        validatePassword() {
            let strongness = this.passwordStrongness;
            if (this.password == '') {
                messages.error(__('Senha obrigatória', 'create-account'));
                return false;
            }
            if (strongness < 100) {
                messages.error(__('Senha não atende os requisitos', 'create-account'));
                return false;
            }
            return true;
        },

        validateConfirmPassword() {
            if (this.password !== this.confirmPassword) {
                messages.error(__('Senhas diferentes', 'create-account'));
                return false;
            }
            return true;
        },

        validateCPF() {
            let cpf = this.cpf.replace(/[^\d]+/g, '');
            let invalidCpfs = ["00000000000", "11111111111", "22222222222", "33333333333", "44444444444", "55555555555", "66666666666", "77777777777", "88888888888", "99999999999"];
            let soma, resto = 0;

            if (cpf == '') {
                messages.error(__('CPF obrigatório', 'create-account'));
                return false;
            }
            if (!/[0-9]{11}/.test(cpf) || invalidCpfs.indexOf(cpf) !== -1) {
                messages.error(__('CPF inválido', 'create-account'));
                return false;
            }

            /* 1º digito */
            soma = 0;
            for (i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            resto = 11 - (soma % 11);
            resto = (resto == 10 || resto == 11 || resto < 2) ? 0 : resto;
            if (resto != parseInt(cpf.charAt(9))) {
                messages.error(__('CPF inválido', 'create-account'));
                return false;
            }

            /* 2º digito */
            soma = 0;
            for (i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = 11 - (soma % 11);
            resto = (resto == 10 || resto == 11 || resto < 2) ? 0 : resto;
            if (resto != parseInt(cpf.charAt(10))) {
                messages.error(__('CPF inválido', 'create-account'));
                return false;
            }
            
            return true;
        },

        validateEmail() {
            if (this.email == '') {
                messages.error(__('Email obrigatório', 'create-account'));
                return false;
            }
            if (!/^[\w+.]+@\w+\.\w{2,}(?:\.\w{2})?$/.test(this.email)) {
                messages.error(__('Email inválido', 'create-account'));
                return false;
            }
            return true;
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
