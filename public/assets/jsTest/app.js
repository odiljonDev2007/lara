import './bootstrap';

// Создаём слушатель событий, отправляющий POST-запрос
// серверу, когда пользователь нажмёт на кнопку.
document.querySelector('#submit-button').addEventListener(
    'click',
    () => window.axios.post('/button/clicked')
);


// Подписываемся на публичный канал с именем "public-channel"
Echo.channel('public-channel')

    // Прослушиваем событие с именем "button.clicked"
    .listen('.button.clicked', (e) => {

        // Отображение "сообщения" в окне оповещения
        alert(e.message);
    });