
<style>
  #masterListRows {
    border: 1px solid #555;
    max-height: 300px;
    overflow-y: auto;
  }
  #newCode {
    margin-bottom: 0px;
  }
  #retailEntryId, #currentRetailListId {
    display: none;
  }
  hr {
    margin-bottom: 10px;
    margin-top: 10px;
  }
</style>

<h4>Retail Entry</h4>
<input id="retailEntryId" type="text" value="<?php echo $model->id; ?>" />
<input id="currentRetailListId" type="text" value="<?php echo $model->retailListId; ?>" />
<table class="table table-striped table-condensed table-bordered">
  <tr>
    <?php foreach ($this->retailHeaders as $h): ?>
      <th><?php echo $h; ?></th>
    <?php endforeach; ?>
  </tr>
  <tr>
    <?php $attr = $model->attributes;
      unset($attr['id']);
      unset($attr['retailListId']);
      unset($attr['code']);
    foreach ($attr as $a): ?>
      <td><?php echo $a; ?></td>
    <?php endforeach; ?>
  </tr>
</table>

<hr/>

<div class="row-fluid">
  <div class="span6">
    <input class="input-small" type="text" id="newCode" value="<?php echo $newCode; ?>" placeholder="<?php echo $newCode; ?>" />
    <button id="addNewCode" class="btn btn-primary">Use This Code</button>
  </div>
  <div class="span6">Auto generated NEW code: <strong><?php echo $newCode; ?></strong></div>
</div>

<hr/>

<div class="row-fluid">
  <div class="span8">
    <h4>Similar Master List Entries</h4> Click on a row to add its code to the retail entry above
  </div>
  <div class="span4">
  <br/>
    Search: <input type="text" class="input-medium" id="searchMasters" />
  </div>
</div>
<div id="masterListRows">
  <table class="table table-striped table-hover table-condensed">
    <tr>
      <?php foreach ($this->masterHeaders as $h): ?>
        <th><?php echo $h; ?></th>
      <?php endforeach; ?>
    </tr>
    <?php foreach ($similarMasters as $m): ?>
    <tr id="<?php echo $m->retailerCode; ?>" class="master-row">
        <?php  $attr = $m->attributes;
          unset($attr['origin']);
          unset($attr['masterListId']); ?>
        
          <td class="master-search"><?php echo $m->retailerCode; ?></td>
          <td class="master-search"><?php echo $m->retailerName; ?></td>
          <td class="master-search"><?php echo $m->city; ?></td>
          <td class="master-search"><?php echo $m->state; ?></td>
          <td class="master-search"><?php echo $m->zip; ?></td>
          <td class="master-search"><?php echo $m->distributionChannel; ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<hr/>
<button id="generateAll" class="btn btn-primary">Auto Generate All Rest</button

