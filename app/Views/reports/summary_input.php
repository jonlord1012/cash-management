<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="form-group">
    <div class="input-group" id="monthYearPicker">
        <input 
            type="text" 
            class="form-control" 
            placeholder="Pilih Bulan"
            readonly
        />
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fas fa-calendar-alt"></i>
            </span>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url('public/src/js/year_month_picker.js') ?>"></script>
<?= $this->endSection() ?>