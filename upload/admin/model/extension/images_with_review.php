<?php

class ModelExtensionImagesWithReview extends Model{

    public function install(){
        $this->db->query("CREATE TABLE oc_review_images (review_image_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, review_id INT(6) NOT NULL, image VARCHAR(150) NOT NULL)");
    }

    public function uninstall(){
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "review_images`");
    }
}