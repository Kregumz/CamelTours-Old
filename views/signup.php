    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box alert round" style="display: <?php echo ($error) ? "block" : "none"; ?>">
          <?php echo ($error) ? $error."\n" : "<!-- No errors -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-9 large-9 columns small-centered medium-centered large-centered">
        <p>Thank you for your interest in CamelTours!</p>
        <p>User accounts for CamelTours require moderator approval. Please fill out the following form, and our team will respond as soon as possible with your account activation information. By submitting the following form you acknowledge that CamelTours.org can remove your content if it is deemed harmful in any way to the communities it impacts.</p>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>signup/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Your Full Name</label>
              <input type="text" name="name" value="<?php echo isset($form_data['name']) ? $form_data['name'] : ''; ?>" required placeholder="e.g. Jane Doe">
              <small class="error">Your name is required.</small>
            </div>
            <div class="row">
              <label>Your Email</label>
              <input type="text" name="email" value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>" required pattern="email" placeholder="e.g. jdoe@example.com">
              <small class="error">A valid email address is required.</small>
            </div>
            <div class="row">
              <label>Password</label>
              <input type="password" name="password" required pattern="password" placeholder="e.g. C@m3lsRul3!">
              <small class="error">Your password must have a minimum of eight characters and include both uppercase and lowercase letters as well as either numbers or special characters.</small>
            </div>
            <div class="row">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" required pattern="password" placeholder="Same as above!">
              <small class="error">Your password does not meet the requirements. Please pick a new one.</small>
            </div>
            <div class="row">
              <label>Briefly describe the purpose, content, and location of the tour(s) you aim to design.</label>
              <textarea name="essay" required placeholder="What's this tour all about?"><?php echo isset($form_data['essay']) ? $form_data['essay'] : ''; ?></textarea>
              <small class="error">Please give a description of your tour.</small>
            </div>
            <div class="row">
              <label>What is <?php echo $num1;?> + <?php echo $num2;?>? *</label>
              <input type="text" name="sum" required placeholder="Please enter the sum of the numbers.">
              <small class="error">Please answer the security question.</small>
              <input type="hidden" name="num1" value="<?php echo $num1;?>">
              <input type="hidden" name="num2" value="<?php echo $num2;?>">
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Sign Up" class="expand button">
            </div>
          </fieldset>
        </form>
      </div>  
    </div>