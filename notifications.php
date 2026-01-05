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

    <?php
        require_once __DIR__ . '/bootstrap.php';
        require_once __DIR__ . '/role.php';
        session_start();

        $userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;

        /*
            DATI FAKE PER DESIGN
            Struttura IDENTICA a quella che userà il backend
        */
        $orders = [
            [
                'order_id' => 12345,
                'notifications' => [
                    ['type' => 'ORDER_CONFIRMED', 'actionable' => true],
                    ['type' => 'PRODUCT_SENT', 'actionable' => false]
                ]
            ],
            [
                'order_id' => 67890,
                'notifications' => [
                    ['type' => 'ORDER_CONFIRMED', 'actionable' => true]
                ]
            ]
        ];
    ?>
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

            <?php foreach ($orders as $index => $order): ?>
                <?php $accordionId = 'order-' . $order['order_id']; ?>

                <div class="accordion-item mb-3 shadow-custom border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?= $accordionId ?>">
                            <i class="bi bi-bag me-2"></i>
                            Ordine #<?= $order['order_id'] ?>
                        </button>
                    </h2>

                    <div id="<?= $accordionId ?>"
                         class="accordion-collapse collapse"
                         data-bs-parent="#ordersAccordion">
                        <div class="accordion-body bg-cream">

                            <?php foreach ($order['notifications'] as $notification): ?>

                                <?php
                                    switch ($notification['type']) {
                                        case 'ORDER_CONFIRMED':
                                            $icon = 'bi-bag-check';
                                            $color = 'text-secondary-red';
                                            $title = 'Ordine confermato';
                                            $message = 'L’ordine è stato confermato.';
                                            break;

                                        case 'PRODUCT_SENT':
                                            $icon = 'bi-truck';
                                            $color = 'text-primary-brown';
                                            $title = 'Prodotto inviato';
                                            $message = 'Il prodotto è stato spedito.';
                                            break;

                                        case 'PRODUCT_RECEIVED':
                                            $icon = 'bi-check-circle';
                                            $color = 'text-secondary-green';
                                            $title = 'Prodotto ricevuto';
                                            $message = 'Ordine completato.';
                                            break;
                                    }

                                    $highlight = $notification['actionable']
                                        ? 'border-start border-4 border-secondary-red'
                                        : '';
                                ?>

                                <div class="card mb-3 border-0 <?= $highlight ?>">
                                    <div class="card-body d-flex justify-content-between align-items-start">

                                        <div class="d-flex">
                                            <i class="bi <?= $icon ?> fs-2 <?= $color ?> me-3"></i>
                                            <div>
                                                <h6 class="fw-semibold mb-1"><?= $title ?></h6>
                                                <p class="text-muted mb-1"><?= $message ?></p>
                                            </div>
                                        </div>

                                        <!-- AZIONI -->
                                        <div>
                                            <?php if (
                                                $notification['type'] === 'ORDER_CONFIRMED' &&
                                                $userRole === Role::VENDOR->value
                                            ): ?>
                                                <button class="btn btn-primary-custom btn-sm action-btn"
                                                        data-action="ship"
                                                        data-order="<?= $order['order_id'] ?>">
                                                    <i class="bi bi-truck me-1"></i>Spedisci
                                                </button>

                                            <?php elseif (
                                                $notification['type'] === 'PRODUCT_SENT' &&
                                                $userRole === Role::BUYER->value
                                            ): ?>
                                                <button class="btn btn-outline-primary-custom btn-sm action-btn"
                                                        data-action="receive"
                                                        data-order="<?= $order['order_id'] ?>">
                                                    <i class="bi bi-check-circle me-1"></i>Conferma ricezione
                                                </button>

                                            <?php elseif ($notification['type'] === 'PRODUCT_RECEIVED'): ?>
                                                <span class="badge bg-success">Completato</span>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

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

<script>
    let selectedButton = null;

    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
            selectedButton = this;

            const action = this.dataset.action;
            const message = action === 'ship'
                ? 'Sei sicuro di voler segnare l’ordine come spedito?'
                : 'Sei sicuro di voler confermare la ricezione dell’ordine?';

            document.getElementById('confirmMessage').innerText = message;

            new bootstrap.Modal(
                document.getElementById('confirmActionModal')
            ).show();
        });
    });

    document.getElementById('confirmActionBtn').addEventListener('click', function () {
        if (!selectedButton) return;

        selectedButton.disabled = true;
        selectedButton.classList.remove(
            'btn-primary-custom',
            'btn-outline-primary-custom'
        );
        selectedButton.classList.add('btn-secondary');
        selectedButton.innerHTML =
            '<i class="bi bi-check me-1"></i>Completato';

        bootstrap.Modal
            .getInstance(document.getElementById('confirmActionModal'))
            .hide();

        selectedButton = null;
    });
</script>

</body>
</html>
