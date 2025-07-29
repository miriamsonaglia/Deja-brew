<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS AND JAVASCRIPT NECESSARY IMPORTS -->
        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            use App\Models\UtenteCompratore;
            use App\Models\Prodotto;

            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/UtenteCompratore.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            // QUERY: READ ALL USER WISHLIST FROM DATABASE
            // TODO: This placeholder will generate error because it cannot be called statically,
            // on login we need to save the correct UtenteCompratore object in SESSION VARIABLE
            $user = new UtenteCompratore();
            $user_wishlist = $user->desideri();
            // $user_wishlist = $_SESSION['logged-user']->desideri();
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <nav><!-- This will be replaced by the navbar template --></nav>
        <!-- Slider template -->
        <section>
            <ul>
            <?php
            foreach($user_wishlist as $object):
                $product = Prodotto::where('id', $object->id_prodotto);
            ?>
                <li>
                    <p><?php echo $product->nome; ?></p>
                    <button class="shopping-bin-product">Cassonetto</button>
                </li>
            <?php endforeach;?>
            </ul>
        </section>
        <footer><!-- ?? Possible footer template ?? --></footer>
    </body>
    <script>
    </script>
</html>
