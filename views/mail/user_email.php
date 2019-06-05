Welcome to swiftportal.com, these are your credentials
<br>
<br>
<p>
    <strong>User:</strong> <?= $model->email; ?><br>
    <strong>Password:</strong> <?= $model->password; ?>
</p>
<p>
    Go to this <a href="<?= \yii\helpers\Url::toRoute('user/change-password', true) ?><?= '?token=' . $model->token; ?>" target="_blank">link</a> to complete next step
</p>