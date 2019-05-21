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
      <p>Edit your account settings below. Note that you must enter your current password to make any changes.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>reset_password/send_form?token=<?php echo ($token);?>" method="POST">
          <fieldset>
            <div class="row">
              <label>New Password</label>
              <input type="password" name="new_password" pattern="password" placeholder="Leave blank if you don't want to change your password.">
              <small class="error">Your password must have a minimum of eight characters and include both uppercase and lowercase letters as well as either numbers or special characters.</small>
            </div>
            <div class="row">
              <label>Confirm New Password</label>
              <input type="password" name="confirm_new_password" pattern="password" placeholder="Leave blank if you don't want to change your password.">
              <small class="error">Your password does not meet the requirements. Please pick a new one.</small>
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Submit" class="expand button">
            </div>
          </fieldset>
        </form>
        <br><p class="text-center"><a href="<?php echo base_url().'cms/home';?>">Back to your homepage.</a></p>
      </div>  
    </div>
