
<style>
  .for-table-spinner {
    position: relative;
    left: 85px;
    top: -10px;
  }
  .for-upload-spinner {
    position: relative;
    left: 165px;
    top: -25px;
  }
  .for-download-spinner {
    position: relative;
    right: 15px;
    top: 15px;
  }
  #downloadMaster, #newMaster, #removeMasters {
    margin-bottom: 10px;
  }

</style>

<h3 class="page-header">Master List Files</h3>

<div class="row-fluid">
  <div class="span4">
    <a href="#" id="newMaster" class="btn btn-primary" ><i class="icon-upload icon-white"></i> Upload New File</a>
    <div class="for-upload-spinner"></div>
    <?php if (!empty($masterLists)): ?>
    <span>This will replace the current master list along with new entries from retail lists run! Download the current list for your records before you upload.</span>
    <?php endif; ?>
  </div>
  <?php if (!empty($masterLists)): ?>
  <div class="span4">
    <a href="downloadMaster" id="downloadMaster" class="btn btn-info" ><i class="icon-download icon-white"></i> Download Current</a>
    <div class="for-download-spinner right"></div>
    <br/>
    <span>Download the most current Master List along with the added on rows from the Retail Lists run since it was uploaded.</span>
  </div>
  <div class="span4">
    <a href="#" id="removeMasters" class="btn btn-danger" ><i class="icon-remove icon-white"></i> Remove all Master Lists</a>
    <div class="for-upload-spinner"></div>
    <span class="text-error">This will permanently delete all Master List files uploaded and all of the currently stored Master List (Basically anything related to any Master List).</span>
  </div>
  <?php endif; ?>
</div>

<br/>
<div>
<?php if (empty($masterLists)): ?>
<strong>You have not uploaded any Master List Files so far</strong>
<? else: ?>
<table class="table table-bordered table-striped">
  <tr>
    <th>Name</th>
    <th>Date Uploaded</th>
    <th>Uploaded By</th>
    <th># entries</th>
    <th>Currently Used</th>
  </tr>
  <?php foreach($masterLists as $m): ?>
  <tr id="<?php echo $m->id; ?>">
    <td><?php echo $m->filename; ?></td>
    <td><?php echo date('m-d-Y h:i:s a', strtotime($m->date)); ?></td>
    <td><?php echo $m->user->firstName .' '.$m->user->lastName; ?></td>
    <td><?php echo $m->numRows; ?></td>
    <td>
      <?php if($currentMaster == $m->id): ?>
      <span class="text-success">Current</span>
      <?php else: ?>
      <a href="#" class=" btn btn-mini use-this-master"><i class="icon-ok-circle"></i> Use This</a><div class="for-table-spinner"></div>
      <?php endif; ?>
    </td>
  </tr>
  <? endforeach; ?>
</table>


<?php endif; ?>

</div>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/masterManager.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/ajaxupload.js"></script>


