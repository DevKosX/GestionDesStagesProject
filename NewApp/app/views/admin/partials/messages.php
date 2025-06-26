<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i> 
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success toast-success">
        <i class="fas fa-check-circle"></i> 
        <?= htmlspecialchars($success) ?>
    </div>
<?php elseif (isset($_GET['success'])): ?>
    <?php
    $successMessages = [
        'user_created' => 'Utilisateur créé avec succès !',
        'stage_created' => 'Stage créé avec succès !',
        'action_type_created' => 'Type d\'action créé avec succès !',
        'actions_assigned' => 'Actions assignées avec succès !',
        'user_updated' => 'Utilisateur modifié avec succès !',
        'user_deleted' => 'Utilisateur supprimé avec succès !'
    ];
    $message = $successMessages[$_GET['success']] ?? 'Opération réalisée avec succès !';
    ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> 
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?> 