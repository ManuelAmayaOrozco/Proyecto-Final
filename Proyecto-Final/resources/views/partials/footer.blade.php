<!--Estructura del footer.-->
<footer class="body__footer">
    <img src="{{ asset('storage/imagenesBugs/Bug2.png') }}" class="bg-image--footer" alt="">

    <div class="footer__div_contactos">
        <h5>Contactos</h5>
        <p>+34-874-234-234</p>
    </div>
    <div class="footer__div_redes">
        <h5>Redes sociales</h5>
        <ul>
            <li><a href="{{ route('dummy.404') }}">Twitter</a></li>
            <li><a href="{{ route('dummy.404') }}">Youtube</a></li>
            <li><a href="{{ route('dummy.404') }}">Facebook</a></li>
        </ul>
    </div>
    <div class="footer__div_info">
        <h5>Info adicional</h5>
        <address>C/Mercedes nº 4 Zaragoza</address>
    </div>
</footer>