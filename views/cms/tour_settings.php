    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box <?php echo ($error) ? 'alert' : 'success';?> round" style="display: <?php echo ($message) ? "block" : "none"; ?>">
          <?php echo ($message) ? $message."\n" : "<!-- No messages -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row text-center">
      <p>Edit the settings for your tour below.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>cms/tour-settings/<?php echo $tour_id;?>/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Tour Name</label>
              <input type="text" name="tour_name" value="<?php echo $tour_name;?>" required>
              <small class="error">A tour name is required.</small>
            </div>
            <div class="row">
              <label>Tour Location</label>
              <input type="text" name="tour_location" value="<?php echo $tour_location;?>" required>
              <small class="error">A tour location is required.</small>
            </div>
            <!-- TOUR LAT AND TOUR LONG -->
            <div class="row">
              <label>Tour Latitude</label>
              <input type="text" name="tour_lat" value="<?php echo $tour_lat;?>" required>
              <small class="error">A tour latitude is required.</small>
            </div>
            <div class="row">
              <label>Tour Longitude</label>
              <input type="text" name="tour_long" value="<?php echo $tour_long;?>" required>
              <small class="error">A tour longitude is required.</small>
            </div>
            <div class="row"><br>
              <input type="checkbox" name="delete"><label>Delete Tour</label>
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Submit" class="expand button">
            </div>
          </fieldset>
        </form>
        <br>
        <form class="text-center" style="margin:0" data-abide action="<?php echo base_url();?>cms/tour-settings/<?php echo $tour_id;?>/download-zip" method="POST">
          <input type="submit" value="Download zip archive of tour." class="tiny round button">
        </form>
        <p class="text-center"><a href="<?php echo base_url().'cms/home/'.$tour_id;?>">Back to your homepage.</a></p>
      </div>  
    </div>
