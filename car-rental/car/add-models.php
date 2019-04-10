<?php include("header-top.php"); ?>
<title>Cars</title>
<?php include("header-bottom.php"); ?>
<section id="addManufacturer" class="section-thm-top">
  <div class="container">
    <form id="addManufacturerForm" method="post" action="">
      <h2>Add Models</h2>
      <div class="col-sm-8 col-sm-offset-2">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Model Name</label>
              <input type="text" class="form-control required" placeholder="Enter Model Name" fieldName="model name" name="md_name" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Manufacturer</label>
              <select class="form-control required select" fieldName="manufacturer" name="m_id">
                <option value="">Select Manufacturer</option>
                <?php
					
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Color</label>
              <input type="text" class="form-control required" placeholder="Enter Color" fieldName="color" name="md_color" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Manufacturing Year</label>
              <select class="form-control required select" fieldName="manufacturing year" name="md_mfg_year">
                <option value="">Select Manufacturer</option>
                <?php
					$year = date('Y');
					for($i = $year; $i>$year-20; $i--){
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Registration Number</label>
              <input type="text" class="form-control required" placeholder="Enter Registration Number" fieldName="registration number" name="md_reg_number" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Chachis Number</label>
              <input type="text" class="form-control required" placeholder="Enter Chachis Name" fieldName="chachis number" name="md_chachis_number" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Notes</label>
              <textarea class="form-control required" name="md_notes" placeholder="Enter Notes" fieldName="notes"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Upload Car Images</label>
              <input type="file" class="form-control required select" name="md_car_image[]" fieldName="car images" multiple />
            </div>
          </div>
        </div>
        <div id="getRequestMessage"></div>
        <div class="form-group">
          <input type="hidden" name="function" value="addModel" />
          <button type="submit" class="btn" name="submit">Add Model</button>
        </div>
      </div>
    </form>
  </div>
</section>
<?php include("footer.php"); ?>