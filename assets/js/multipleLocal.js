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
        let passwordMustHaveSpecialCharacters = /[$@$!%*#?&]/;
        let passwordMustHaveNumbers = /[0-9]/;
        let minimumPasswordLength = 8;

        let rules = [];

        //faz uma requisição para pegar as configs de força de senha
        $.get( `${MapasCulturais.baseURL}auth/passwordvalidationinfos`, function( data ) {

            if(data.passwordRules.passwordMustHaveCapitalLetters) {
                rules.push(passwordMustHaveCapitalLetters);
                $("#passwordRulesUL").append("<li>A senha deve conter uma letra maiúsculas</li>");
            }

            if(data.passwordRules.passwordMustHaveLowercaseLetters) {
                rules.push(passwordMustHaveLowercaseLetters);
                $("#passwordRulesUL").append("<li>A senha deve conter uma letra minúsculas</li>");
            }

            if(data.passwordRules.passwordMustHaveSpecialCharacters) {
                rules.push(passwordMustHaveSpecialCharacters);
                $("#passwordRulesUL").append("<li>A senha deve conter um caractere especial</li>");
            }

            if(data.passwordRules.passwordMustHaveNumbers) {
                rules.push(passwordMustHaveNumbers);
                $("#passwordRulesUL").append("<li>A senha deve conter um numero</li>");
            }

            if(data.passwordRules.minimumPasswordLength) {
                minimumPasswordLength = data.passwordRules.minimumPasswordLength
            }
            $("#passwordRulesUL").append(`<li>A senha deve conter no minimo ${minimumPasswordLength} digitos</li>`);

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

            document.getElementById("progresslabel").innerHTML = `${currentPercentPasswordCorrect.toFixed(2)}%`;
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

    

});
