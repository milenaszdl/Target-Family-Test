<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="feedback-list mt-5">
    <h2 class="mb-4">Последние отзывы</h2>

    <?php foreach ($arResult["ITEMS"] as $item): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1"><?= htmlspecialcharsbx($item["PROPERTIES"]["NAME"]["VALUE"]) ?></h5>
                        <h6 class="card-subtitle text-muted mb-2"><?= htmlspecialcharsbx($item["PROPERTIES"]["EMAIL"]["VALUE"]) ?></h6>
                    </div>
                </div>
                <p class="card-text"><?= nl2br(htmlspecialcharsbx($item["PREVIEW_TEXT"])) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
