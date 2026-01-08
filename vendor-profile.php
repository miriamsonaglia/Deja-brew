<?php

    require __DIR__ . '/bootstrap.php';

    use App\Models\UtenteVenditore;
    use App\Models\Utente;
    use App\Models\Ordine;

    session_start();
                
    $utente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
    if ($utente == null) {
        // Handle missing user data: redirect or show error
        echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
        exit;
    }

    // Get the user ID from query string
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        die("Invalid user ID");
    }

    $vendor = UtenteVenditore::where('id_utente', $id)->first();

    if ($vendor == null) {
        die("Vendor profile not found");
    }

    // Fetch the user
    $user = Utente::find($id);
    if (!$user) {
        die("User not found");
    }

    // Fetch orders for the user
    $orders = Ordine::where('id_utente', $user->id)->get();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($user->name ?? 'User Profile') ?></title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .profile, .orders { max-width: 700px; margin: auto; border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; }
            .profile h2 { margin-top: 0; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
            th { background: #f0f0f0; }
        </style>
    </head>
    <body>

    <div class="profile">
        <h2><?= htmlspecialchars($user->name ?? 'No Name') ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($user->email ?? '-') ?></p>
        <p><strong>Joined:</strong> <?= htmlspecialchars($user->created_at ?? '-') ?></p>

        <?php
        $loggedInUserId = 2; // replace with actual logged-in user logic
        if ($user->id == $loggedInUserId): ?>
            <p>This is your profile!</p>
            <a href="edit_profile.php?id=<?= $user->id ?>">Edit Profile</a>
        <?php endif; ?>
    </div>

    <div class="orders">
        <h3>Orders</h3>
        <?php if($orders->isEmpty()): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Status mapping
                $statusMap = [
                    1 => 'Ordered',
                    2 => 'Paid',
                    3 => 'Shipped',
                    4 => 'Received'
                ];

                foreach($orders as $order): ?>
                    <tr>
                        <td><?= $order->id ?></td>
                        <td><?= $order->id_prodotto ?></td>
                        <td><?= $statusMap[$order->status] ?? $order->status ?></td>
                        <td><?= number_format($order->prezzo_totale, 2) ?>€</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    </body>
</html>
