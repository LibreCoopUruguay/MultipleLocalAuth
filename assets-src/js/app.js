$(function() {
    // mascara para o input de CPF
    var cpfInput = document.getElementById("RegraValida");
    if(cpfInput) {
        cpfInput.addEventListener('keydown', function() {
        
            function fMasc(objeto,mascara) {
                obj=objeto
                masc=mascara
                setTimeout(() => {
                    obj.value=masc(obj.value)
                },1)
            }
    
            function mCPF(cpf){
                cpf=cpf.replace(/\D/g,"")
                cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
                cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
                cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
                return cpf
            }
    
            fMasc( this, mCPF )
    
        });
        // função para dizer se o CPF é valido ou invalido no front-end (NÃO ESTÁ SENDO USADA MAS ESTÁ AQUI PARA REFERENCIA)
        function ValidaCPF(){	
            var RegraValida=document.getElementById("RegraValida").value; 
            var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/;	 
            if (cpfValido.test(RegraValida) == true)	{ 
                console.log("CPF Válido");	
            } else	{	 
                console.log("CPF Inválido");	
            }
        }
    }
    
    var password = document.getElementById("pwd-progress-bar-validation");
    if(password) {
        // verifica a força da senha
        let passwordMustHaveCapitalLetters = /[A-Z]/;
        let passwordMustHaveLowercaseLetters = /[a-z]/;
        let passwordMustHaveSpecialCharacters = /[$@$!%*#?&\.\,\:<>+\_\-\"\'()]/;
        let passwordMustHaveNumbers = /[0-9]/;
        let minimumPasswordLength = 8;

        let rules = [];

        //faz uma requisição para pegar as configs de força de senha
        $.get( `${MapasCulturais.baseURL}auth/passwordvalidationinfos`, function( data ) {

            if(data.passwordRules.passwordMustHaveCapitalLetters) {
                rules.push(passwordMustHaveCapitalLetters);
                
                $("#passwordRulesUL").append(`<li> ${MapasCulturais.labels.multiplelocal.passwordMustHaveCapitalLetters} </li>`);
            }

            if(data.passwordRules.passwordMustHaveLowercaseLetters) {
                rules.push(passwordMustHaveLowercaseLetters);
                $("#passwordRulesUL").append(`<li> ${MapasCulturais.labels.multiplelocal.passwordMustHaveLowercaseLetters} </li>`);
            }

            if(data.passwordRules.passwordMustHaveSpecialCharacters) {
                rules.push(passwordMustHaveSpecialCharacters);
                $("#passwordRulesUL").append(`<li> ${MapasCulturais.labels.multiplelocal.passwordMustHaveSpecialCharacters} </li>`);
            }

            if(data.passwordRules.passwordMustHaveNumbers) {
                rules.push(passwordMustHaveNumbers);
                $("#passwordRulesUL").append(`<li> ${MapasCulturais.labels.multiplelocal.passwordMustHaveNumbers} </li>`);
            }

            if(data.passwordRules.minimumPasswordLength) {
                minimumPasswordLength = data.passwordRules.minimumPasswordLength
            }
            $("#passwordRulesUL").append(`<li> ${MapasCulturais.labels.multiplelocal.minimumPasswordLength} ${minimumPasswordLength} </li>`);

            console.log("get passwordvalidationinfos OK");
        });


        password.addEventListener('keyup', function() {
            var pwd = password.value
            
            // Reset if password length is zero
            if (pwd.length === 0) {
                document.getElementById("progresslabel").innerHTML = "";
                document.getElementById("progress").value = "0";
                return;
            }

            let rulesLength = rules.length;

            var prog = rules
                .reduce((memo, test) => memo + test.test(pwd), 0);


            let percentToAdd = (100 / (rulesLength+1));
            let currentPercentPasswordCorrect = ((prog*100) / (rulesLength+1));
            if(pwd.length > minimumPasswordLength-1) {
                currentPercentPasswordCorrect = currentPercentPasswordCorrect + percentToAdd
            }

            document.getElementById("progresslabel").innerHTML = `${currentPercentPasswordCorrect.toFixed(0)}%`;
            document.getElementById("progress").value = `${currentPercentPasswordCorrect.toFixed(2)}`;

        });
    }
    

    $('#multiple-login-recover').click(function() {
        $('#multiple-login').hide();
        $('#multiple-recover').show();
    });
    
    $('#multiple-login-recover-cancel').click(function() {
        $('#multiple-login').show();
        $('#multiple-recover').hide();
    });

    $('#multiple-login .account-link > button').click(function() {
        // $('#multiple-login').hide();
        $('.section-register').addClass('active').focus();
        $([document.documentElement, document.body]).animate({
            scrollTop: $(".section-register").offset().top - 30
        }, 200);
    });

    if($('body').hasClass('action-register')) {
        if($(window).width() < 1025) {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".section-register").offset().top - 80
            }, 200);

            if($('.alerta.erro').length) {
                $($('.alerta.erro')).insertBefore('.section-register');
            }

            if($('.alerta.sucesso').length) {
                $($('.alerta.sucesso')).insertBefore('.section-register');

                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".section-register").offset().top - 200
                }, 200);
            }

            
        }

        
    }
    
});
