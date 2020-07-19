<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends Admin_Controller {
	public function after_init() {
		$this->set_scripts_and_styles();
		$this->load->model('admin/products_model', 'products');
		$this->load->model('admin/product_images_model', 'images');
		$this->load->model('admin/product_categories_model', 'categories');
	}

	public function products($page = 1) {
		$merchant_row = $this->_user_data['merchant_row'];

		$this->_data['add_label']= "New Product";
		$this->_data['add_url']	 = base_url() . "marketplace/products/new";

		$actions = array(
			'update'
		);

		$select = array(
			array(
				'product_id as id',
				'product_name as Name',
				'product_description as Description',
				'product_date_created as "Date Posted"',
				'product_status as status'
			)
		);

		$where = array(
			'merchant_id' => $merchant_row->merchant_id
		);

		$total_rows = $this->products->get_count(
			$where
		);
		$offset = $this->get_pagination_offset($page, $this->_limit, $total_rows);
	    $results = $this->products->get_data($select, $where, array(), array('filter'=>'product_id', 'sort'=>'DESC'), $offset, $this->_limit);

		$this->_data['listing'] = $this->table_listing('', $results, $total_rows, $offset, $this->_limit, $actions, 4, false, false, '', '', base_url() . "marketplace/products/");
		$this->_data['title']  = "Marketplace - Products";
		$this->set_template("marketplace/list", $this->_data);
	}

	public function product_new() {
		$merchant_row = $this->_user_data['merchant_row'];

		$this->_data['form_url']		= base_url() . "marketplace/products/new";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		$category_id = "";
		
		if ($_POST) {
			$category_id			= $this->input->post("category");
			$product_name 			= $this->input->post("product-name");
			$product_description 	= $this->input->post("product-description");
			$images					= $_FILES["images"];		

			if ($this->form_validation->run('product_new')) {
				$product_id = $this->products->insert(
					array(
						'product_category_id' 	=> $category_id,
						'product_name' 			=> $product_name,
						'product_description' 	=> $product_description,
						'merchant_id'			=> $merchant_row->merchant_id,
						'product_date_created' 	=> $this->_today
					)
				);

				// do image upload
				$upload_result = $this->upload_files($images, "bambupay_{$product_id}");
				if (isset($upload_result['error'])) {
					// has error
				} else {
					if (isset($upload_result["results"])) {
						$results = $upload_result["results"];

						// do saving
						foreach ($results as $result) {
							$this->images->insert(
								array(
									'product_id' 			=> $product_id,
									'product_image_id' 		=> $result['image_id'],
									'product_image_base64' 	=> $result['base64_image']
								)
							);
						}
					}
				}

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Saved!'));
				redirect($this->_data['form_url']);
			}
		}
		
		$this->_data['category'] = $this->generate_selection(
			"category",
			$this->categories->get_data(
				array(
					'product_category_id as id',
					'product_category_description as name'
				)
			),
			$category_id,
			"id",
			"name"
		);

		$this->_data['title']  = "Product New";
		$this->set_template("marketplace/products/form", $this->_data);
	}

	public function product_update( $id ) {
		$merchant_row = $this->_user_data['merchant_row'];

		$this->_data['form_url']		= base_url() . "marketplace/products/update/{$id}";
		$this->_data['notification'] 	= $this->session->flashdata('notification');

		$product_id = $id;

		$images_results = $this->images->get_data(
			array(
				'product_image_id as id',
				'product_image_base64 as base64_image'
			),
			array(
				'product_id' => $product_id
			)
		);


		$datum = $this->products->get_datum(
			'',
			array(
				'product_id' => $product_id,
				'merchant_id' => $merchant_row->merchant_id
			)
		)->row();

		if ($datum == "") {
			redirect(base_url());
		}

		$category_id = $datum->product_category_id;

		$this->_data['post'] = array(
			'product-name' => $datum->product_name,
			'product-description' => $datum->product_description
		);
		
		if ($_POST) {
			$category_id			= $this->input->post("category");
			$product_name 			= $this->input->post("product-name");
			$product_description 	= $this->input->post("product-description");
			$images					= $_FILES["images"];

			if ($this->form_validation->run('product_new')) {
				$this->products->update(
					$id,
					array(
						'product_category_id' 	=> $category_id,
						'product_name' 			=> $product_name,
						'product_description' 	=> $product_description,
					)
				);

				// do image upload
				$upload_result = $this->upload_files($images, "bambupay_{$product_id}");
				if (isset($upload_result['error'])) {
					// has error
				} else {
					if (isset($upload_result["results"])) {
						$results = $upload_result["results"];

						// do saving
						foreach ($results as $result) {
							$this->images->insert(
								array(
									'product_id' 			=> $product_id,
									'product_image_id' 		=> $result['image_id'],
									'product_image_base64' 	=> $result['base64_image']
								)
							);
						}
					}
				}

				$this->session->set_flashdata('notification', $this->generate_notification('success', 'Successfully Updated!'));
				redirect($this->_data['form_url']);
			}
		}
		
		$this->_data['image_gallery'] = $this->generate_image_gallery(
			$images_results
		);

		$this->_data['category'] = $this->generate_selection(
			"category",
			$this->categories->get_data(
				array(
					'product_category_id as id',
					'product_category_description as name'
				)
			),
			$category_id,
			"id",
			"name"
		);

		$this->_data['title']  = "Product Update";
		$this->set_template("marketplace/products/form-update", $this->_data);
	}

	public function confirmation_remove_product_image($id) {
		$this->_data['form_url'] = base_url() . "marketplace/products/confirmation-remove/image-{$id}";

		$datum = $this->images->get_datum(
			'',
			array(
				'product_image_id' => $id
			)
		)->row();

		if ($datum == "") {
			redirect(base_url());
		}

		if ($_POST) {
			// do delete
			$this->images->delete($id);

			$redirect_url = base_url() . "marketplace/products/update/{$datum->product_id}";
			redirect($redirect_url);
		}

		$this->_data['base64_image'] = $datum->product_image_base64;

		$this->set_template("marketplace/products/confirmation-remove-image", $this->_data);
	}
}
