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
      <p>Edit the settings for your node below.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>cms/node-settings/<?php echo $tour_id;?>/<?php echo $node_id;?>/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Node Name</label>
              <input type="text" name="node_name" value="<?php echo $node_name;?>" required>
              <small class="error">A node name is required.</small>
            </div>
            <div class="row">
              <label>Node Location</label>
              <input type="text" name="node_location" value="<?php echo $node_location;?>" required>
              <small class="error">A node location is required.</small>
            </div>
            <!--add node lat and node long-->
            <div class="row">
              <label>Node Latitude</label>
              <input type="text" name="node_lat" value="<?php echo $node_lat;?>" required>
              <small class="error">A node latitude is required.</small>
            </div>
            <div class="row">
              <label>Node Longitude</label>
              <input type="text" name="node_long" value="<?php echo $node_long;?>" required>
              <small class="error">A node longitude is required.</small>
            </div>
            <div class="row"><br>
              <input type="checkbox" name="delete"><label>Delete Node</label>
            </div>
            <div class="row text-center">
              <input type="hidden" name="tour_id" value="<?php echo $tour_id;?>">
              <br><input type="submit" value="Submit" class="expand button">
            </div>
          </fieldset>
        </form>
        <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
        <p class="text-center"><a href="<?php echo base_url().'cms/node/'.$tour_id.'/'.$node_id;?>">Back to the upload page.</a></p>
      </div>  
    </div>