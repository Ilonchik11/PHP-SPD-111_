<h1>API & Backend</h1>
<div class="card-panel orange">
    <button class="btn" onclick="getClick()">CREATE</button>
    <button class="btn indigo" onclick="postClick()">POST</button>
    <div id="api-result"></div>
</div>
<h2>Робота з БД</h2>
<p>
    Підготовчі роботи. 
    Встановлюємо СУБД (MySQL/MariaDB).
    Створюємо окрему базу даних для проєкту, користувача для неї. 
    <code>CREATE DATABASE php_spd_111 ;</code>
    <code>CREATE USER 'spd_111_user'@'localhost' IDENTIFIED BY 'spd_pass';</code>
    Даємо користувачу права на дану БД <br/>
    <code>GRANT ALL PRIVILEGES ON php_spd_111.* TO 'spd_111_user'@'localhost';</code>
    Оновлюємо таблицю доступу <br/>
    <code>FLUSH PRIVILEGES</code>
    <p>
        Підключення.  
        Є дві групи технологій роботи з БД: "індивідуальні" - набори команд під 
        кожну БД окремо (mysql_connect(), ib_connect(),...) та "універсальна" - 
        технологія PDO (аналог ADo у .NET). Далі розглядаємо PDO.
    </p>
</p>
<script>
    function getClick() {
        fetch("/test")
            .then(r => r.text())
            .then(t => {
                document.getElementById("api-result").innerText = t;
            });
        }
        
    function postClick() {
        fetch("/test", {
            method: 'POST'
        })
        .then(r => r.text())
        .then(t => {
            document.getElementById("api-result").innerText = t;
        });
    }
</script>