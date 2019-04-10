<?php include("header-top.php"); ?>
<title>Cars</title>
<?php include("header-bottom.php"); ?>
<section id="addManufacturer" class="section-thm-top">
  <div class="container">
    <form id="addManufacturerForm" method="post" action="">
      <h2>Add Manufacturer</h2>
      <div class="col-sm-6 col-sm-offset-3">
        <div class="form-group">
          <label>Manufacturer Name</label>
          <input type="text" class="form-control required" placeholder="Enter Manufacturer Name" fieldName="manufacturer name" name="m_name" />
        </div>
        <div id="getRequestMessage"></div>
        <div class="form-group">
          <input type="hidden" name="function" value="addManufacturer" />
          <button type="submit" class="btn" name="submit">Add Manufacturer</button>
        </div>
      </div>
    </form>
  </div>
</section>
<?php include("footer.php"); ?>