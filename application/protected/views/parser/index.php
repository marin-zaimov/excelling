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
    <span class="text">This will upload a file, copy codes of identical entries, copy codes for entries that follow one of the specified exceptions and then allow you to select similar entries(from the Master List) for all the rest of the entries in the retail List.</span>
  </div>
  
</div>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/parserManager.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/plugins/ajaxupload.js"></script>

