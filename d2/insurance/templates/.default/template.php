
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->addExternalCss($this->GetFolder()."/style.css");
?>
<div class="insuranse">
    <form method="POST" class="insuranse-form">
        <input type="hidden" name="path" value=<?=$arResult["PATH"]?>>
        <input type="hidden" name="product" value="<?=$arParams["PRODUCT_ID"]?>">
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("SIRNAME")?>
            </label>
            <input type="text" name="surname" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("NAME")?>
            </label>
            <input type="text" name="name" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("BIRTHDAY")?>
            </label>
            <input type="date" name="birthday" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("PHONE")?>
            </label>
            <input type="tel" name="phone" id="phone" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("ADDRESS")?>
            </label>
            <input type="text" name="address" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item">
            <label for="" class="insurance-form-label">
                <?=GetMessage("PASSPORT")?>
            </label>
            <input type="text" name="passport" id="passport" class="insurance-form-input" required>
        </div>
        <div class="insuranse-form-item insuranse-form-item--notification">

        </div>
        <div class="insuranse-form-item">
            <input type="submit" class="insurance-form-submit" value="<?=GetMessage("BUTTON")?>">
        </div>
    </form>
</div>