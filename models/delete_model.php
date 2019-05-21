<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Delete_Model extends CI_Model
  {
    public function delete_slide($slide_id) {
      $this->db->where('slide_id', $slide_id);
      $query = $this->db->get('slides');
      if ($query->num_rows() == 1) {
        $row = $query->row();
        unlink(getcwd().'/'.$row->media_uri);
        if ($row->seq_num > 0)
          unlink(getcwd().'/'.$row->thumb_uri);
        $this->db->delete('slides', array('slide_id' => $slide_id));
      }
    }
    public function delete_node($user_id, $tour_id, $node_id) {
      $this->db->where('node_id', $node_id);
      $query = $this->db->get('slides');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          $this->delete_slide($row->slide_id);
        }
      }
      $node_dir = getcwd().'/ct/u'.$user_id.'/t'.$tour_id.'/n'.$node_id.'/';
      $media_dir = $node_dir.'media/';
      $thumb_dir = $media_dir.'thumbs/';
      rmdir($thumb_dir);
      rmdir($media_dir);
      unlink($node_dir.'index.html');
      unlink($node_dir.'qr.png');
      rmdir($node_dir);
      $this->db->delete('nodes', array('node_id' => $node_id));
    }
    public function delete_tour($user_id, $tour_id) {
      $this->db->where('tour_id', $tour_id);
      $query = $this->db->get('nodes');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row)
          $this->delete_node($user_id, $tour_id, $row->node_id);
      }
      $tour_dir = getcwd().'/ct/u'.$user_id.'/t'.$tour_id.'/';
      unlink($tour_dir.'dl/index.html');
      unlink($tour_dir.'dl/cache.manifest');
      rmdir($tour_dir.'dl/');
      unlink($tour_dir.'index.html');
      unlink($tour_dir.'qr.png');
      rmdir($tour_dir);
      $this->db->delete('tours', array('tour_id' => $tour_id));
    }
    public function delete_user($user_id) {
      $this->db->where('user_id', $user_id);
      $query = $this->db->get('tours');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row)
          $this->delete_tour($user_id, $row->tour_id);
      }
      $user_dir = getcwd().'/ct/u'.$user_id.'/';
      rmdir($user_dir);
      $this->db->delete('users', array('user_id' => $user_id));
    }
  }

?>