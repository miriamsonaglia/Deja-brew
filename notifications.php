<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/role.php';

use App\Models\Ordine;
use App\Models\Notifica;
use App\Models\TipoNotifica;
use App\Models\UtenteVenditore;
use App\Models\Prodotto;
use Illuminate\Database\Capsule\Manager as DB;

session_start();
$userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;
$userId = $_SESSION['LoggedUser']['id'] ?? null;

// Gestione aggiornamenti via POST (AJAX) - DEVE ESSERE PRIMA DI QUALSIASI OUTPUT HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $userId) {
    header('Content-Type: application/json');

    $orderId = (int)($_POST['order_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($orderId <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID ordine non valido']);
        exit;
    }

    $ordine = Ordine::find($orderId);

    if (!$ordine) {
        echo json_encode(['success' => false, 'message' => 'Ordine non trovato']);
        exit;
    }

    // Controlla permessi
    if ($userRole === Role::VENDOR->value) {
        // Verifica se è un ordine del venditore
        $venditore = UtenteVenditore::where('id_utente', $userId)->first();
        $prodotto = Prodotto::find($ordine->id_prodotto);
        if (!$venditore || $prodotto->id_venditore !== $venditore->id) {
            echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
            exit;
        }
    } elseif ($userRole === Role::BUYER->value) {
        if ($ordine->id_utente !== $userId) {
            echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ruolo non valido']);
        exit;
    }

    if ($action === 'ship' && $userRole === Role::VENDOR->value && $ordine->status === 'confermato') {
        $ordine->status = 'spedito';
        $ordine->save();
        echo json_encode(['success' => true, 'new_status' => 'spedito']);
    } elseif ($action === 'receive' && $userRole === Role::BUYER->value && $ordine->status === 'spedito') {
        $ordine->status = 'ricevuto';
        $ordine->save();
        echo json_encode(['success' => true, 'new_status' => 'ricevuto']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Azione non valida o stato errato']);
    }
    exit;
}

// Preparazione dati per la visualizzazione
$orders = collect(); // Collection vuota di default

if ($userRole !== Role::GUEST->value && $userId) {
    if ($userRole === Role::BUYER->value) {
        // Per compratore: fetcha i suoi ordini
        $orders = Ordine::where('id_utente', $userId)
            ->orderBy('id', 'desc')
            ->get();
    } elseif ($userRole === Role::VENDOR->value) {
        // Per venditore: fetcha gli ordini dei suoi prodotti
        $venditore = UtenteVenditore::where('id_utente', $userId)->first();
        if ($venditore) {
            $prodottiIds = Prodotto::where('id_venditore', $venditore->id)->pluck('id');
            $orders = Ordine::whereIn('id_prodotto', $prodottiIds)
                ->orderBy('id', 'desc')
                ->get();
        }
    }

    // Aggiungiamo le notifiche a ogni ordine
    foreach ($orders as $order) {
        $notifications = [];

        // Notifica base: Ordine confermato (sempre presente)
        $notifications[] = [
            'type' => 'ORDER_CONFIRMED',
            'actionable' => ($userRole === Role::VENDOR->value && $order->status === 'confermato')
        ];

        if (in_array($order->status, ['spedito', 'ricevuto'])) {
            $notifications[] = [
                'type' => 'PRODUCT_SENT',
                'actionable' => ($userRole === Role::BUYER->value && $order->status === 'spedito')
            ];
        }

        if ($order->status === 'ricevuto') {
            $notifications[] = [
                'type' => 'PRODUCT_RECEIVED',
                'actionable' => false
            ];
        }

        // Attributi temporanei sul modello (non salvati nel DB)
        $order->notifications = $notifications;
        $order->order_id = $order->id;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Notifiche - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./dist/custom/css/new-style.css">
</head>
<body>

<?php require_once __DIR__ . '/navbar-selector.php'; ?>

<div class="container my-5" style="max-width: 900px;">

    <h2 class="mb-4 text-primary-brown fw-bold">
        <i class="bi bi-bell me-2"></i>Notifiche
    </h2>

    <?php if ($userRole === Role::GUEST->value): ?>

        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <h3>Nessuna notifica</h3>
            <p>Accedi per visualizzare le notifiche.</p>
        </div>

    <?php else: ?>

        <!-- ACCORDION ORDINI -->
        <div class="accordion" id="ordersAccordion">

            <?php foreach ($orders as $order): ?>
                <?php $accordionId = 'order-' . $order->order_id; ?>

                <div class="accordion-item mb-3 shadow-custom border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?= $accordionId ?>">
                            <i class="bi bi-bag me-2"></i>
                            Ordine #<?= $order->order_id ?>
                        </button>
                    </h2>

                    <div id="<?= $accordionId ?>"
                         class="accordion-collapse collapse"
                         data-bs-parent="#ordersAccordion">
                        <div class="accordion-body bg-cream">

                            <?php foreach ($order->notifications as $notification): ?>

                                <?php
                                    switch ($notification['type']) {
                                        case 'ORDER_CONFIRMED':
                                            $icon = 'bi-bag-check';
                                            $color = 'text-secondary-red';
                                            $title = 'Ordine confermato';
                                            $message = $userRole === Role::VENDOR->value
                                                ? 'Hai ricevuto un nuovo ordine. Conferma la spedizione quando pronto.'
                                                : 'Il tuo ordine è stato confermato dal venditore.';
                                            break;

                                        case 'PRODUCT_SENT':
                                            $icon = 'bi-truck';
                                            $color = 'text-primary-brown';
                                            $title = 'Prodotto spedito';
                                            $message = $userRole === Role::BUYER->value
                                                ? 'Il tuo ordine è stato spedito. Conferma quando lo ricevi.'
                                                : 'Hai confermato la spedizione dell\'ordine.';
                                            break;

                                        case 'PRODUCT_RECEIVED':
                                            $icon = 'bi-check-circle';
                                            $color = 'text-success';
                                            $title = 'Prodotto ricevuto';
                                            $message = $userRole === Role::VENDOR->value
                                                ? 'Il compratore ha confermato la ricezione dell\'ordine.'
                                                : 'Hai confermato la ricezione dell\'ordine.';
                                            break;

                                        default:
                                            continue 2;
                                    }
                                ?>

                                <div class="notification-item mb-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="notification-icon rounded-circle bg-light p-2">
                                            <i class="bi <?= $icon ?> fs-4 <?= $color ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-semibold"><?= htmlspecialchars($title) ?></h5>
                                            <p class="mb-1 text-muted"><?= htmlspecialchars($message) ?></p>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime('-' . rand(1,30) . ' days')) ?>
                                            </small>
                                        </div>

                                        <!-- BOTTONI AZIONI -->
                                        <?php if ($notification['actionable']): ?>
                                            <?php if ($notification['type'] === 'ORDER_CONFIRMED'): ?>
                                                <button class="btn btn-primary-custom btn-sm action-btn"
                                                        data-action="ship"
                                                        data-order="<?= $order->order_id ?>">
                                                    <i class="bi bi-truck me-1"></i>Conferma spedizione
                                                </button>

                                            <?php elseif ($notification['type'] === 'PRODUCT_SENT'): ?>
                                                <button class="btn btn-outline-primary-custom btn-sm action-btn"
                                                        data-action="receive"
                                                        data-order="<?= $order->order_id ?>">
                                                    <i class="bi bi-check-circle me-1"></i>Conferma ricezione
                                                </button>
                                            <?php endif; ?>
                                        <?php elseif ($notification['type'] === 'PRODUCT_RECEIVED'): ?>
                                            <span class="badge bg-success">Completato</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <?php if ($orders->isEmpty()): ?>
            <div class="empty-state mt-4">
                <i class="bi bi-bell-slash"></i>
                <h3>Nessuna notifica</h3>
                <p>Non ci sono ordini con notifiche al momento.</p>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- MODAL CONFERMA -->
<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-custom">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Conferma azione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Annulla
                </button>
                <button class="btn btn-primary-custom" id="confirmActionBtn">
                    Conferma
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
<script src="./dist//custom//js/cart-manager.js"></script>
<script>
    let selectedButton = null;
    const modal = new bootstrap.Modal(document.getElementById('confirmActionModal'));

    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
            selectedButton = this;

            const action = this.dataset.action;
            const message = action === 'ship'
                ? 'Sei sicuro di voler confermare la spedizione dell\'ordine?'
                : 'Sei sicuro di aver ricevuto l\'ordine?';

            document.getElementById('confirmMessage').innerText = message;
            modal.show();
        });
    });

    document.getElementById('confirmActionBtn').addEventListener('click', async function () {
        if (!selectedButton) return;

        const action = selectedButton.dataset.action;
        const orderId = selectedButton.dataset.order;

        // Disabilita bottone durante la richiesta
        selectedButton.disabled = true;

        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: action,
                    order_id: orderId
                })
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Errore durante l\'aggiornamento');
                selectedButton.disabled = false;
            }
        } catch (error) {
            alert('Errore di rete: ' + error.message);
            selectedButton.disabled = false;
        }

        modal.hide();
        selectedButton = null;
    });

    <?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
        updateCartCount();
    <?php endif; ?>
</script>
<?php require_once __DIR__ . '/reusables/footer.php' ?>
</body>
</html>