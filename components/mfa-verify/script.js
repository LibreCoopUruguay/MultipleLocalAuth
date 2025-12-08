app.component('mfa-verify', {
    template: $TEMPLATES['mfa-verify'],

    data() {
        return {
            code: '',
            error: null,
            success: null,
            isLoading: false
        }
    },

    methods: {
        async verify() {
            this.isLoading = true;
            this.error = null;

            let api = new API();
            let data = { code: this.code };

            try {
                const response = await api.POST($MAPAS.baseURL + "auth/verify_mfa", data);
                const dataReturn = await response.json();

                if (dataReturn.error) {
                    this.error = dataReturn.data;
                } else if (dataReturn.success) {
                    window.location.href = dataReturn.redirectTo;
                }
            } catch (e) {
                this.error = 'Ocurrió un error inesperado.';
            } finally {
                this.isLoading = false;
            }
        },

        async resend() {
            this.isLoading = true;
            this.error = null;
            this.success = null;

            let api = new API();

            try {
                const response = await api.POST($MAPAS.baseURL + "auth/resend_mfa", {});
                const dataReturn = await response.json();

                if (dataReturn.error) {
                    this.error = dataReturn.data;
                } else if (dataReturn.success) {
                    this.success = dataReturn.data;
                }
            } catch (e) {
                this.error = 'Error de conexión.';
            } finally {
                this.isLoading = false;
            }
        }
    }
});
