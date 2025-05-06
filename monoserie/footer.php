<style>
    .footer {
        background-color: #141414;
        color: #757575;
        text-align: center;
        padding: 20px 5%;
        margin-top: 10%;
    }

    .footer-container { 
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-links {
        list-style: none; 
        padding: 0; 
        margin: 0 0 20px 0; 
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        gap: 15px 25px; 
    }

    .footer-title { 
        font-size: 16px;
        margin-bottom: 20px;
    }

    .footer-links li a {
        color: #757575;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease-in-out;
    }

    .footer-links li a:hover {
        text-decoration: underline;
        color: white;
    }

    .language-select {
        background: transparent;
        color: #757575;
        border: 1px solid #757575;
        padding: 5px;
        margin-top: 15px;
        cursor: pointer;
        border-radius: 5px;
    }

    .footer-copy {
        font-size: 12px;
        margin-top: 20px;
        opacity: 0.8;
    }
</style>


<footer class="footer">
   <div class="footer-container"> 
        <ul class="footer-links">
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Confidentialité</a></li>
            <li><a>Mentions légales</a></li>
        </ul>
        <p class="footer-copy">Les informations sur les séries sont issues de Wikipedia, IMDB et AlloCiné à des fins éducatives.

</p> 
        <p class="footer-copy">© <?= date('Y') ?> MonoSerie. Tous droits réservés.</p> 
    </div>
</footer>