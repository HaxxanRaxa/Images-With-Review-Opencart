<?php
class ControllerExtensionModuleImagesWithReview extends Controller{

    const DEFAULT_MODULE_SETTINGS = [
        'code' => 'images_with_review',
        'images_with_review_no_of_images' => 3,
        'images_with_review_image_size' => 1048576, // 1 MB
    ];



    /**
     * This will store errors
     * @var array
     */
    private $error = array();

    public function index(){

        $this->load->language('extension/module/images_with_review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->model_setting_setting->editSetting('images_with_review', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }


        $data = array();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/age_restriction', 'user_token=' . $this->session->data['user_token'], true)
        );

        $module_setting = $this->model_setting_setting->getSetting('images_with_review');
//        var_dump($module_setting);
//        die();
        if (isset($this->request->post['images_with_review_no_of_images'])) {
            $data['images_with_review_no_of_images'] = $this->request->post['images_with_review_no_of_images'];
        } else {
            $data['images_with_review_no_of_images'] = $module_setting['images_with_review_no_of_images'];
        }
        if (isset($this->request->post['images_with_review_image_size'])) {
            $data['images_with_review_image_size'] = $this->request->post['images_with_review_image_size'];
        } else {
            $data['images_with_review_image_size'] = $module_setting['images_with_review_image_size'];
        }

        if (isset($this->request->post['images_with_review_status'])) {
            $data['images_with_review_status'] = $this->request->post['images_with_review_image_size'];
        } else {
            $data['images_with_review_status'] = $module_setting['images_with_review_image_size'];
        }

        $data['action']['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action']['save'] = "";
        $data['error'] = $this->error;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $htmlOutput = $this->load->view('extension/module/images_with_review', $data);
        $this->response->setOutput($htmlOutput);

    }

    /**
     * Validate requested form
     * @return bool
     */
    public function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/images_with_review')) {
            $this->error['permission'] = true;
            return false;
        }

        if (!utf8_strlen($this->request->post['images_with_review_no_of_images'])) {
            $this->error['images'] = true;
        }
        if (!utf8_strlen($this->request->post['images_with_review_image_size'])) {
            $this->error['size'] = true;
        }

        return empty($this->error);
    }

    /**
     * settings while installing module
     */
    public function install(){
        $this->load->model("extension/images_with_review");
        $this->model_extension_images_with_review->install();

        $this->addModule();
    }

    /**
     * settings while uninstalling module
     */
    public function uninstall(){
        $this->load->model("extension/images_with_review");
        $this->model_extension_images_with_review->uninstall();
    }

    /**
     * Function to add module
     * @return mixed
     */
    private function addModule() {
        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting('images_with_review', self::DEFAULT_MODULE_SETTINGS);

        return $this->db->getLastId();
    }

}