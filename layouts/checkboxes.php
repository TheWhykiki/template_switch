<?php
    use Joomla\CMS\Language\Text;
    $params = $displayData['params'];
    $state = $displayData['state'];
?>

<form name="form" action="" method="post">
    <label for="<?= $params->get('theme'); ?>">
        <?= Text::_('PLG_SYSTEM_TEMPLATE_SWITCH_LABEL_OPTION_1');?>
        <input <?php if($state === $params->get('theme')): ?>checked<?php endif; ?> class="switcher" type="radio" id="<?= $params->get('theme'); ?>" name="theme" value="<?= $params->get('theme'); ?>">
    </label>

    <label for="<?= $params->get('themeAlternative'); ?>">
        <?= Text::_('PLG_SYSTEM_TEMPLATE_SWITCH_LABEL_OPTION_2');?>

        <input type="radio" <?php if($state === $params->get('themeAlternative')): ?>checked<?php endif; ?> class="switcher" id="<?= $params->get('themeAlternative'); ?>" name="theme" value="<?= $params->get('themeAlternative'); ?>">
    </label>
</form>
