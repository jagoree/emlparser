<div class="row">
    <?= $this->Form->create(['errors' => [], 'schema' => []], ['type' => 'file']); ?>
    <?= $this->Form->control('file', ['type' => 'file', 'label' => 'ZIP-архив']); ?>
    <?= $this->Form->button('Загрузить'); ?>
    <?= $this->Form->end(); ?>
</div>