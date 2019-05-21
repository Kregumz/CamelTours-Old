    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <p><b>Welcome to your tour creation home!</b></p>
      <p>Edit your tours below, or <a href="<?php echo base_url();?>cms/add-tour">create a new one</a>. Questions? See the<a href="<?php echo base_url();?>faq"> FAQ.</a></p>
      <br>
    </div>
    <div class="row">
      <div class="small-11 medium-8 large-6 columns small-centered medium-centered large-centered">
        <div class="row">
          <dl class="accordion" data-accordion>
            <?php if (empty($tours)) echo "<p class=\"text-center\">No tours found.</p>"; else foreach ($tours as $tour_id => $tour) { ?><!-- Tour <?php echo $tour_id; ?> -->
              <dd>
                <a href="#tour<?php echo $tour_id;?>"><?php echo $tour['name'];?></a>
                <div id="tour<?php echo $tour_id;?>" class="content active">
                  <a href="<?php echo base_url().'cms/add-node/'.$tour_id;?>" class="tiny secondary button"><span class="glyphicon glyphicon-plus"></span></a>
                  <a href="<?php echo base_url().'ct/u'.$this->session->userdata('user_id').'/t'.$tour_id.'/';?>" target="_blank" class="tiny secondary button"><span class="glyphicon glyphicon-eye-open"></span></a>
                  <a href="<?php echo base_url().'ct/u'.$this->session->userdata('user_id').'/t'.$tour_id.'/qr.png';?>" target="_blank" class="tiny secondary button"><span class="glyphicon glyphicon-qrcode"></span></a>
                  <a href="<?php echo base_url().'cms/tour-settings/'.$tour_id;?>" class="tiny secondary right button"><span class="glyphicon glyphicon-wrench"></span></a>
                  <br><br>
                  <?php if (empty($tour['nodes'])) echo "<p class=\"text-center\">No nodes found for this tour.</p>"; else foreach ($tour['nodes'] as $node_id => $node) { ?><!-- Node <?php echo $node_id; ?> -->
                    <div class="thumb-holder"><a href="<?php echo base_url().'cms/node/'.$tour_id.'/'.$node_id;?>"><div class="thumb-caption"><?php echo $node['caption'];?></div></a><a title="<?php echo $node['name']?>" class="th" href="<?php echo base_url().'cms/node/'.$tour_id.'/'.$node_id;?>"><img alt="<?php echo $node['name']?>" src="<?php echo $node['cover']?>"></a></div>
                  <?php } ?><!-- End tour nodes -->
                  <br>
                </div>
              </dd>
            <?php } ?><!-- End user tours -->
          </dl>
          <br>
        </div>
      </div>
    </div>

    <!-- Collapse accordion on JS-enabled browsers. -->
    <script>
      window.onload = function() {
        <?php foreach ($tours as $t_id => $t) if ($t_id != $active_tour) echo 'document.getElementById("tour'.$t_id.'").className = "content";'."\n        "; ?>// Deactivate Each Tour Div
      }
    </script>