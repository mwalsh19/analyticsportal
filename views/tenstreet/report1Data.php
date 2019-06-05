<?php
use yii\helpers\Url;

$basePath = Url::base();
?>

<?php
    echo $publishers[0]['tenstreet_referrer_part'];
    for ($i = 0; $i < 10; $i++) {
?>
<br><br>
<p class="publisher-title">Publisher <?= $i; ?></p>
<table id="table" class="table table-bordered table-striped table-condensed" style="width: 100% !important;font-size: 13px;">
  <thead>
    <tr>
      <th>Referrer Code</th>
      <th>Total (all Tenstreet available rows)</th>
      <th>Hired</th>
      <th>Attending Academy</th>
      <th>Not Qualified</th>
      <th>Not Interested</th>
      <th>No Response</th>
      <th>Duplicate App</th>
      <th>Unqualified Da</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
  <tfoot>
    <tr id="totals-row">
      <th>Total (viewable columns)</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
    <tr style="background: #000; color: #fff;">
      <th style="font-weight: bold;">Grand Total</th>
      <th id="grand-total" colspan="8" style="text-align: left; font-weight: bold;">0</th>
    </tr>
  </tfoot>
</table>
<?php } ?>

