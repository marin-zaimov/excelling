<style>

  
  .for-upload-spinner {
    position: relative;
    right: 15px;
    top: 15px;
  }
  #uploadAndRun {
    margin-bottom: 10px;
  }

</style>
 
<div>
  <a href="#" id="uploadAndRun" class="btn btn-primary right" ><i class="icon-upload icon-white"></i> Upload and Run</a>
  <div class="for-upload-spinner right"></div>
  <h3 class="page-header">Upload and run a Retail List</h3>

  <div id="similarEntries">
    <span class="text">This will upload a file, copy codes of identical entries, copy codes for entries that follow one of the specified exceptions and then allow you to select similar entries(from the Master List) or create new codes for all the rest of the entries in the retail List.</span>
  </div>
  
</div>
<br/>
<div>
  <?php if (empty($retailLists)): ?>
  <strong>You have not uploaded any Retail List Files so far</strong>
  <? else: ?>
  <h4>Existing Files</h4>
  <table class="table table-bordered table-striped">
    <tr>
      <th>Name</th>
      <th>Date Uploaded</th>
      <th>Uploaded By</th>
      <th># entries</th>
      <th>Status</th>
      <th>Retail List</th>
      <th>Migration List</th>
      <th>Remove</th>
    </tr>
    <?php foreach($retailLists as $m): ?>
    <tr id="<?php echo $m->id; ?>">
      <td><?php echo $m->filename; ?></td>
      <td><?php echo date('m-d-Y h:i:s a', strtotime($m->dateUploaded)); ?></td>
      <td><?php echo $m->user->firstName .' '.$m->user->lastName; ?></td>
      <td><?php echo $m->numRows; ?></td>
      <td> 
        <?php if ($m->status == 'COMPLETE'): ?>
          <span class="text-success">Complete</span>
        <?php else: ?>
        <span class="text-warning">Incomplete</span>
        <a href="#" class="retail-continue btn btn-mini btn-primary right">Continue</a>
        <?php endif; ?>
      </td>
      <td><a href="downloadRetail?retailId=<?php echo $m->id; ?>" class="btn btn-info btn-mini"><i class="icon-download icon-white"></i> Download</a></td>
      <td><a href="downloadMigration?retailId=<?php echo $m->id; ?>" class="btn btn-info btn-mini"><i class="icon-download icon-white"></i> Download</a></td>
      <td><a href="removeRetail?retailId=<?php echo $m->id; ?>" class="btn btn-danger btn-mini"><i class="icon-remove icon-white"></i> Remove List + Migration</a></td>
    </tr>
    <? endforeach; ?>
  </table>
  <?php endif; ?>

</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/retailManager.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/ajaxupload.js"></script>

