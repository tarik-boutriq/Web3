<style>
    /* Header */

    header {
        padding: 0 20px;  
        height: 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header .logo img {
        width: 300px;
    }

    header .menu {
        list-style: none;
        display: flex;
    }

    header .menu li {
        margin: 0 20px;
    }

    header .menu li a {
        color: #fff;
        text-decoration: none;
        text-transform: uppercase;
        transition: color 0.3s ease-in-out;
    }

    header .menu li a:hover {
        color: #f39c12; 
    }  
    
/*Search Bar*/
    .search-bar {
        align-items: center;
        justify-content: right;
        margin: 30px auto;
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 12px 18px;
        backdrop-filter: blur(12px);
    }

    .search-bar input {
        flex: 1;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 20px; 
        padding: 12px;
        outline: none;
    }

    .search-bar input::placeholder {
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
    }

    .search-bar button {
        background-color: #E50914;
        border: none;
        color: #fff;
        font-size: 15px;
        padding: 12px 24px;
        border-radius: 50px;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-bar button:hover {
        background-color: #B20710;
    }

    .menu .dropdown ul {
        display: none; 
        position: absolute;
        background-color: rgba(255, 255, 255, 0.15); 
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu .dropdown:hover ul {
        display: block; 
    }

/*logo */
    .logo {
        font-family: "Lucida Handwriting", cursive;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        background-color:rgba(255, 255, 255, 0.15);
        padding: 10px 30px;
        border-radius: 50px;

    }


</style>

<header class="menu-header" style="margin:40px;">
    <div class="logo">
        MONOSERIE
    </div>
    <nav>
        <ul class="menu">
            <li><a href="/Web3/monoserie">Accueil</a></li>
            <li><a href="/Web3/monoserie/pages/categorie.php">Categories</a></li>
            <?php if (isset($_SESSION['admin_id'])): ?>

                <li class="dropdown">
                    <a>ajouter</a>
                    <ul class="submenu">
                        <li>
                            <a href="/Web3/monoserie/pages/ajouterserie.php">Série</a>
                        </li>
                        <li>
                            <a href="/Web3/monoserie/pages/ajouteracteur.php">Acteur</a>
                        </li>
                    </ul>
                </li>

                <li><a href="/Web3/monoserie/pages/admin.php">Admin</a></li>
                <li><a href="/Web3/monoserie/admin/logout.php">Déconnecter</a></li>
                
            <?php else: ?>
                <li><a href="/Web3/monoserie/pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="search-bar">
        <form action="/Web3/monoserie/series/recherche.php" method="GET"> 
            <input type="text" name="s" placeholder="Rechercher une série..." required>
            <button type="submit">Rechercher</button>
        </form>
    </div>


</header>
