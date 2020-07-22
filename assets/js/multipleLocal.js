$(function() {

    // mascara para o input de CPF
    var cpfInput = document.getElementById("RegraValida");
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

    function ValidaCPF(){	
        var RegraValida=document.getElementById("RegraValida").value; 
        var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/;	 
        if (cpfValido.test(RegraValida) == true)	{ 
            console.log("CPF Válido");	
        } else	{	 
            console.log("CPF Inválido");	
        }
    }

    
       
    
    var password = document.getElementById("pwd-progress-bar-validation");
    password.addEventListener('keyup', function() {
        var pwd = password.value
        
        // Reset if password length is zero
        if (pwd.length === 0) {
            document.getElementById("progresslabel").innerHTML = "";
            document.getElementById("progress").value = "0";
            return;
        }
        
        // Check progress
        var prog = [/[$@$!%*#?&]/, /[A-Z]/, /[0-9]/, /[a-z]/]
            .reduce((memo, test) => memo + test.test(pwd), 0);
        
        // Length must be at least 8 chars
        if(prog > 2 && pwd.length > 7){
            prog++;
        }
        
        // Display it
        var progress = "";
        var strength = "";
        switch (prog) {
            case 0:
            case 1:
            case 2:
            strength = "25%";
            progress = "25";
            break;
            case 3:
            strength = "50%";
            progress = "50";
            break;
            case 4:
            strength = "75%";
            progress = "75";
            break;
            case 5:
            strength = "100% - Senha valida";
            progress = "100";
            break;
        }
        document.getElementById("progresslabel").innerHTML = strength;
        document.getElementById("progress").value = progress;
    
    });

    $('#multiple-login-recover').click(function() {
        $('#multiple-login').hide();
        $('#multiple-recover').show();
    });
    
    $('#multiple-login-recover-cancel').click(function() {
        $('#multiple-login').show();
        $('#multiple-recover').hide();
    });

    

});
