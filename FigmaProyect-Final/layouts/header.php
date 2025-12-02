<div id="headerContainer">
    <div id="zaLogo">
        <img src="./img/ZathuraLogo.png" alt="Logo">
    </div>

    <div id="schlName">
        <h1>Colegio Betito Indio</h1>
        <!-- Validamos que exista la variable $name para evitar errores -->
        <h2><?php echo isset($name) ? $name : ''; ?></h2>
    </div>

    <div id="logos" style="position: relative;"> <!-- Added relative for menu positioning -->
        <button id="userBtn">
            <i class="fa-solid fa-user" style="color: #ffffff;"></i>
        </button>

        <button>
            <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
        </button>

        <!-- MENU DESPLEGABLE -->
        <div id="userMenu" class="user-menu">
            <!-- Asegúrate de que esta ruta sea correcta según donde guardaste logout.php -->
            <a href="./php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    // Script integrado para el menú
    document.addEventListener("DOMContentLoaded", function() {
        const userBtn = document.getElementById('userBtn');
        const userMenu = document.getElementById('userMenu');

        if(userBtn && userMenu){
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita que el clic cierre el menú inmediatamente
                userMenu.classList.toggle('show');
            });

            window.addEventListener('click', (event) => {
                if (!userBtn.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.remove('show');
                }
            });
        }
    });
</script>