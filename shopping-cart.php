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
            // QUERY: READ ALL USER SHOPPING CART FROM DATABASE
            // TODO: This placeholder will generate error because it cannot be called statically,
            // on login we need to save the correct UtenteCompratore object in SESSION VARIABLE
            $user_cart = UtenteCompratore::carrello();
            // $user_cart = $_SESSION['logged-user']->carrello();
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <nav><!-- This will be replaced by the navbar template --></nav>
        <!-- Slider template -->
        <section>
            <ul>
            <?php
            $total = 0;
            foreach($user_cart as $object):
                $product = Prodotto::where('id', $object->id_prodotto);
                $subtotal = $product->quantità*$product->prezzo;
                $total += $subtotal;
            ?>
                <li>
                    <p><?php echo $product->nome; ?></p>
                    <input type="number" value="<?php echo $product->quantità; ?>"/>
                    <p> <?php echo $subtotal; ?> €</p>
                    <button class="shopping-bin-product">Cassonetto</button>
                </li>
            <?php endforeach;?>
            </ul>
            <p>Total: <?php echo $total; ?> €</p>
            <p>Consegna prevista: BOHHH</p>
            <p>Presso: Gondor (serve inserire in variabile di sessione luogo di consegna scelto)</p>
            <button class="buy-button">ACQUISTA PATACCA!!</button>
        </section>
        <footer><!-- ?? Possible footer template ?? --></footer>
    </body>
    <script>
    </script>
</html>
