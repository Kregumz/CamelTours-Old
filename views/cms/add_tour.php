    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box alert round" style="display: <?php echo ($error) ? "block" : "none"; ?>">
          <?php echo ($error) ? $error."\n" : "<!-- No errors -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row text-center">
      <p>Fill out the information below to create a new tour.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>cms/add-tour/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Tour Name</label>
              <input type="text" name="tour_name" value="<?php echo isset($form_data['tour_name']) ? $form_data['tour_name'] : ''; ?>" required>
              <small class="error">A tour name is required.</small>
            </div>
            <div class="row">
              <label>Tour Location</label>
              <input type="text" name="tour_location" value="<?php echo isset($form_data['tour_location']) ? $form_data['tour_location'] : ''; ?>" required>
              <small class="error">A tour location is required.</small>
            </div>
            <div class="row">
              <label>Tour Latitude</label>
              <input type="text" name="tour_lat" value="<?php echo isset($form_data['tour_lat']) ? $form_data['tour_lat'] : ''; ?>" required>
              <small class="error">A tour latitude is required.</small>
            </div>
            <div class="row">
              <label>Tour Longitude</label>
              <input type="text" name="tour_long" value="<?php echo isset($form_data['tour_long']) ? $form_data['tour_long'] : ''; ?>" required>
              <small class="error">A tour longitude is required.</small>
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Create Tour" class="expand button">
            </div>
          </fieldset>
        </form>
        <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
        <br><p class="text-center"><a href="<?php echo base_url().'cms/home';?>">Back to your homepage.</a></p>
      </div>  
    </div>