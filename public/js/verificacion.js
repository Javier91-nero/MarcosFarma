document.addEventListener('DOMContentLoaded', () => {
    let tiempo = 20;
    const btn = document.getElementById('reenviar-btn');
    const contador = document.getElementById('contador');

    const intervalo = setInterval(() => {
        tiempo--;
        contador.textContent = tiempo;

        if (tiempo <= 0) {
            clearInterval(intervalo);
            btn.removeAttribute('disabled');
            btn.textContent = 'Reenviar código';
        }
    }, 1000);
});