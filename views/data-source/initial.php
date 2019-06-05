
<?php
/* @var $this yii\web\View */
$this->registerJs("
    var dataTypeSelect = $('#data-type');
    $('#go-overview, #go-create').on('click', function(event){
        event.preventDefault();
        var type = dataTypeSelect.find('option:selected').val();
        var target = $(this).data('target');

        if(!type){
            sweetAlert('Oops...', 'Please select a data type', 'error');
            return false;
        }
        window.location.href = target+'?type='+type+'&fromInitial=1';
    });

");
?>
<section class="content-header">
    <h1>
        <span>Manager</span> > <span>Data Sources</span>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <h4>Please select the type of action you want to perform</h4>
                    <hr>
                    <div>
                        <?php
                        \yii\widgets\ActiveForm::begin();
                        ?>
                        <div class="form-group">
                            <label class="control-label">Data type:</label>
                            <?php
                            echo yii\helpers\Html::dropDownList('type', null, [
                                'data' => 'Tenstreet Data Source',
                                'call' => 'Call Source'
                                    ], [
                                'class' => 'form-control max-width-300',
                                'id' => 'data-type',
                                'prompt' => 'Please select an option',
                                    ]
                            );
                            ?>
                        </div>
                        <div class="form-group">
                            <a data-target="<?php echo \yii\helpers\Url::base(true) . '/data-source/index'; ?>" href="javascript:void(0);" class="btn btn-primary" id="go-overview">Go to Overview</a>
                            <a data-target="<?php echo \yii\helpers\Url::base(true) . '/data-source/create'; ?>" href="javascript:void(0);" class="btn btn-primary" id="go-create">Add new</a>
                        </div>
                        <?php
                        \yii\widgets\ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

