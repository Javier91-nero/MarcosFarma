// Validar que no contenga números en nombre y apellido
function validarTextoSinNumeros(input) {
    const valor = input.value;
    const contieneNumero = /\d/.test(valor);
    const errorId = input.id + "Error";
    const errorElem = document.getElementById(errorId);

    if (contieneNumero) {
        errorElem.classList.remove('d-none');
        input.classList.add('is-invalid');
    } else {
        errorElem.classList.add('d-none');
        input.classList.remove('is-invalid');
    }
}

// Validar solo números y longitud máxima para DNI y teléfono
function validarSoloNumeros(input, maxLength, errorId) {
    let valor = input.value.replace(/\D/g, '');

    if (valor.length > maxLength) {
        valor = valor.slice(0, maxLength);
    }

    input.value = valor;

    const errorElem = document.getElementById(errorId);
    if (valor.length > maxLength) {
        errorElem.classList.remove('d-none');
        input.classList.add('is-invalid');
    } else {
        errorElem.classList.add('d-none');
        input.classList.remove('is-invalid');
    }
}

// Evaluar la seguridad de la contraseña
function evaluarSeguridadPassword(password) {
    const strengthElem = document.getElementById('passwordStrength');
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[\W_]/.test(password)) strength++;

    let mensaje = '';
    let clase = '';

    switch (strength) {
        case 0:
        case 1:
        case 2:
            mensaje = 'Contraseña débil';
            clase = 'text-danger fw-bold';
            break;
        case 3:
        case 4:
            mensaje = 'Contraseña moderada';
            clase = 'text-warning fw-bold';
            break;
        case 5:
            mensaje = 'Contraseña fuerte';
            clase = 'text-success fw-bold';
            break;
    }

    strengthElem.textContent = mensaje;
    strengthElem.className = 'form-text mt-1 ' + clase;
}