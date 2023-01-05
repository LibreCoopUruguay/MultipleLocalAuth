app.component('create-account', {
    template: $TEMPLATES['create-account'],
    
    // define os eventos que este componente emite
    emits: [],

    setup() { 
        // os textos estão localizados no arquivo texts.php deste componente 
        const text = Utils.getTexts('create-account')
        return { text }
    },

    beforeCreate() { },
    created() { },

    beforeMount() { },
    mounted() { },

    beforeUpdate() { },
    updated() { },

    beforeUnmount() {},
    unmounted() {},

    props: {
    },

    data() {
        return {
            step: 1,
        }
    },

    computed: {
    },
    
    methods: {
        nextStep() {

        },
        previousStep() {

        },
        cancelRegister() {
            
        }
    },
});
