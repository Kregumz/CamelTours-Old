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
      <div class="small-12 medium-10 large-10 columns small-centered medium-centered large-centered">
        <p>Enter the email address associated with your CamelTours account.</p>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>forgot/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Email</label>
              <input type="text" name="email" value="<?php echo $email;?>" required pattern="email">
              <small class="error">A valid email address is required.</small>
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Submit" class="expand button">
            </div>
          </fieldset>
        </form>
      </div>
    </div>
