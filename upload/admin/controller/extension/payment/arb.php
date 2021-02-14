<?php
class ControllerExtensionPaymentArb extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/arb');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_arb', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['trans_id'])) {
			$data['error_trans_id'] = $this->error['trans_id'];
		} else {
			$data['error_trans_id'] = '';
		}

		if (isset($this->error['security'])) {
			$data['error_security'] = $this->error['security'];
		} else {
			$data['error_security'] = '';
		}
		if (isset($this->error['resource'])) {
			$data['error_resource'] = $this->error['resource'];
		} else {
			$data['error_resource'] = '';
		}
		if (isset($this->error['endpoint'])) {
			$data['error_endpoint'] = $this->error['endpoint'];
		} else {
			$data['error_endpoint'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/arb', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/arb', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_arb_trans_id'])) {
			$data['payment_arb_trans_id'] = $this->request->post['payment_arb_trans_id'];
		} else {
			$data['payment_arb_trans_id'] = $this->config->get('payment_arb_trans_id');
		}

		if (isset($this->request->post['payment_arb_security'])) {
			$data['payment_arb_security'] = $this->request->post['payment_arb_security'];
		} else {
			$data['payment_arb_security'] = $this->config->get('payment_arb_security');
		}
		if (isset($this->request->post['payment_arb_resource'])) {
			$data['payment_arb_resource'] = $this->request->post['payment_arb_resource'];
		} else {
			$data['payment_arb_resource'] = $this->config->get('payment_arb_resource');
		}
		if (isset($this->request->post['payment_arb_endpointe'])) {
			$data['payment_arb_endpoint'] = $this->request->post['payment_arb_endpoint'];
		} else {
			$data['payment_arb_endpoint'] = $this->config->get('payment_arb_endpoint');
		}

		$data['callback'] = HTTP_CATALOG . 'index.php?route=extension/payment/arb/callback';

		if (isset($this->request->post['payment_arb_total'])) {
			$data['payment_arb_total'] = $this->request->post['payment_arb_total'];
		} else {
			$data['payment_arb_total'] = $this->config->get('payment_arb_total');
		}

		if (isset($this->request->post['payment_arb_order_status_id'])) {
			$data['payment_arb_order_status_id'] = $this->request->post['payment_arb_order_status_id'];
		} else {
			$data['payment_arb_order_status_id'] = $this->config->get('payment_arb_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_arb_geo_zone_id'])) {
			$data['payment_arb_geo_zone_id'] = $this->request->post['payment_arb_geo_zone_id'];
		} else {
			$data['payment_arb_geo_zone_id'] = $this->config->get('payment_arb_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_arb_status'])) {
			$data['payment_arb_status'] = $this->request->post['payment_arb_status'];
		} else {
			$data['payment_arb_status'] = $this->config->get('payment_arb_status');
		}

		if (isset($this->request->post['payment_arb_sort_order'])) {
			$data['payment_arb_sort_order'] = $this->request->post['payment_arb_sort_order'];
		} else {
			$data['payment_arb_sort_order'] = $this->config->get('payment_arb_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/arb', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/arb')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_arb_trans_id']) {
			$this->error['trans_id'] = $this->language->get('error_trans_id');
		}

		if (!$this->request->post['payment_arb_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}
		if (!$this->request->post['payment_arb_resource']) {
			$this->error['resource'] = $this->language->get('error_resource');
		}
		if (!$this->request->post['payment_arb_endpoint']) {
			$this->error['endpoint'] = $this->language->get('error_endpoint');
		}

		return !$this->error;
	}
}