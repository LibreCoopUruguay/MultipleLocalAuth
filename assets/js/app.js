/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  // mascara para o input de CPF
  var cpfInput = document.getElementById("RegraValida");

  if (cpfInput) {
    // função para dizer se o CPF é valido ou invalido no front-end (NÃO ESTÁ SENDO USADA MAS ESTÁ AQUI PARA REFERENCIA)
    var ValidaCPF = function ValidaCPF() {
      var RegraValida = document.getElementById("RegraValida").value;
      var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/;

      if (cpfValido.test(RegraValida) == true) {
        console.log("CPF Válido");
      } else {
        console.log("CPF Inválido");
      }
    };

    cpfInput.addEventListener('keydown', function () {
      function fMasc(objeto, mascara) {
        obj = objeto;
        masc = mascara;
        setTimeout(function () {
          obj.value = masc(obj.value);
        }, 1);
      }

      function mCPF(cpf) {
        cpf = cpf.replace(/\D/g, "");
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        return cpf;
      }

      fMasc(this, mCPF);
    });
  }

  var password = document.getElementById("pwd-progress-bar-validation");

  if (password) {
    // verifica a força da senha
    var passwordMustHaveCapitalLetters = /[A-Z]/;
    var passwordMustHaveLowercaseLetters = /[a-z]/;
    var passwordMustHaveSpecialCharacters = /[$@$!%*#?&\.\,\:<>+\_\-\"\'()]/;
    var passwordMustHaveNumbers = /[0-9]/;
    var minimumPasswordLength = 8;
    var rules = []; //faz uma requisição para pegar as configs de força de senha

    $.get("".concat(MapasCulturais.baseURL, "auth/passwordvalidationinfos"), function (data) {
      if (data.passwordRules.passwordMustHaveCapitalLetters) {
        rules.push(passwordMustHaveCapitalLetters);
        $("#passwordRulesUL").append("<li> ".concat(MapasCulturais.labels.multiplelocal.passwordMustHaveCapitalLetters, " </li>"));
      }

      if (data.passwordRules.passwordMustHaveLowercaseLetters) {
        rules.push(passwordMustHaveLowercaseLetters);
        $("#passwordRulesUL").append("<li> ".concat(MapasCulturais.labels.multiplelocal.passwordMustHaveLowercaseLetters, " </li>"));
      }

      if (data.passwordRules.passwordMustHaveSpecialCharacters) {
        rules.push(passwordMustHaveSpecialCharacters);
        $("#passwordRulesUL").append("<li> ".concat(MapasCulturais.labels.multiplelocal.passwordMustHaveSpecialCharacters, " </li>"));
      }

      if (data.passwordRules.passwordMustHaveNumbers) {
        rules.push(passwordMustHaveNumbers);
        $("#passwordRulesUL").append("<li> ".concat(MapasCulturais.labels.multiplelocal.passwordMustHaveNumbers, " </li>"));
      }

      if (data.passwordRules.minimumPasswordLength) {
        minimumPasswordLength = data.passwordRules.minimumPasswordLength;
      }

      $("#passwordRulesUL").append("<li> ".concat(MapasCulturais.labels.multiplelocal.minimumPasswordLength, " ").concat(minimumPasswordLength, " </li>"));
      console.log("get passwordvalidationinfos OK");
    });
    password.addEventListener('keyup', function () {
      var pwd = password.value; // Reset if password length is zero

      if (pwd.length === 0) {
        document.getElementById("progresslabel").innerHTML = "";
        document.getElementById("progress").value = "0";
        return;
      }

      var rulesLength = rules.length;
      var prog = rules.reduce(function (memo, test) {
        return memo + test.test(pwd);
      }, 0);
      var percentToAdd = 100 / (rulesLength + 1);
      var currentPercentPasswordCorrect = prog * 100 / (rulesLength + 1);

      if (pwd.length > minimumPasswordLength - 1) {
        currentPercentPasswordCorrect = currentPercentPasswordCorrect + percentToAdd;
      }

      document.getElementById("progresslabel").innerHTML = "".concat(currentPercentPasswordCorrect.toFixed(0), "%");
      document.getElementById("progress").value = "".concat(currentPercentPasswordCorrect.toFixed(2));
    });
  }

  $('#multiple-login-recover').click(function () {
    $('#multiple-login').hide();
    $('#multiple-recover').show();
  });
  $('#multiple-login-recover-cancel').click(function () {
    $('#multiple-login').show();
    $('#multiple-recover').hide();
  });
  $('#multiple-login .account-link > button').click(function () {
    // $('#multiple-login').hide();
    $('.section-register').addClass('active').focus();
    $([document.documentElement, document.body]).animate({
      scrollTop: $(".section-register").offset().top - 30
    }, 200);
  });

  if ($('body').hasClass('action-register')) {
    if ($(window).width() < 1025) {
      $([document.documentElement, document.body]).animate({
        scrollTop: $(".section-register").offset().top - 80
      }, 200);

      if ($('.alerta.erro').length) {
        $($('.alerta.erro')).insertBefore('.section-register');
      }

      if ($('.alerta.sucesso').length) {
        $($('.alerta.sucesso')).insertBefore('.section-register');
        $([document.documentElement, document.body]).animate({
          scrollTop: $(".section-register").offset().top - 200
        }, 200);
      }
    }
  }
});

/***/ }),

/***/ "./sass/app.scss":
/*!***********************!*\
  !*** ./sass/app.scss ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*****************************************!*\
  !*** multi ./js/app.js ./sass/app.scss ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /home/isaquemelo/Documents/mapasculturais-aldirblanc/plugins/plugin-MultipleLocalAuth/assets-src/js/app.js */"./js/app.js");
module.exports = __webpack_require__(/*! /home/isaquemelo/Documents/mapasculturais-aldirblanc/plugins/plugin-MultipleLocalAuth/assets-src/sass/app.scss */"./sass/app.scss");


/***/ })

/******/ });