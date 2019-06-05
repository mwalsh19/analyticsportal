<div class="custom-overlay" id="custom-overlay">
    <div class="custom-overlay-content">
        <div class="overlay-wrap">
            <div class="overlay-text">
                <h2><?php echo $string; ?></h2>
            </div>
            <div class="actions-btn">
                <a href="javascript:void(0);" class="btn btn-danger" onclick="document.getElementById('custom-overlay').style.display = 'none'">No Thanks</a>&nbsp;&nbsp;
                <a href="<?php echo $url; ?>" class="btn btn-info">Yes</a>
            </div>
        </div>
    </div>
</div>