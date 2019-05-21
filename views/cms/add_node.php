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
      <p>Fill out the information below to create a new node.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>cms/add-node/send-form" method="POST">
          <fieldset>
          <!--node name field-->
            <div class="row">
              <label>Node Name</label>
              <input type="text" name="node_name" value="<?php echo isset($form_data['node_name']) ? $form_data['node_name'] : ''; ?>" required>
              <small class="error">A node name is required.</small>
            </div>
            <!--node location space-->
            <div class="row">
              <label>Node Location</label>
              <input type="text" name="node_location" value="<?php echo isset($form_data['node_location']) ? $form_data['node_location'] : ''; ?>" required>
              <small class="error">A node location is required.</small>
            </div>
            <!--add node lat field-->
            <div class="row">
              <label>Node Latitude</label>
              <input type="text" name="node_lat" value="<?php echo isset($form_data['node_lat']) ? $form_data['node_lat'] : ''; ?>" required>
              <small class="error">A node latitude is required.</small>
            </div>
            <!--add node long field-->
            <div class="row">
              <label>Node Longitude</label>
              <input type="text" name="node_long" value="<?php echo isset($form_data['node_long']) ? $form_data['node_long'] : ''; ?>" required>
              <small class="error">A node longitude is required.</small>
            </div>
            <div class="row text-center">
              <input type="hidden" name="tour_id" value="<?php echo $tour_id;?>">
              <br><input type="submit" value="Create Node" class="expand button">
            </div>
          </fieldset>
        </form>
        <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
        <br><p class="text-center"><a href="<?php echo base_url().'cms/home';?>">Back to your homepage.</a></p>
      </div>  
    </div>