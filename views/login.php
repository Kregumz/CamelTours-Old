    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-4 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box alert round" style="display: <?php echo ($error) ? "block" : "none"; ?>">
          <?php echo ($error) ? $error."\n" : "<!-- No errors -->\n"; ?>
        </div>        
        <p>Log into your CamelTours account.</p>
        <form action="<?php echo base_url();?>login/attempt-login" method="POST">
          <fieldset>
            <div class="row text-left">
              <label>Email Address</label>
              <input type="text" name="username" value="<?php echo $user;?>">
            </div>
            <div class="row text-left">
              <label>Password</label>
              <input type="password" name="password">
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Log In" class="expand button">
            </div>
            <div class="row text-center">
              <br><a href="<?php echo base_url();?>forgot">Forgot your password?</a>
            </div>
          </fieldset>
        </form>
        <div class="row text-center">
          <p>Don't have an account?<b><a href="<?php echo base_url();?>signup"> Sign up </a></b>today!</p>
        </div>
      </div>
    </div>
