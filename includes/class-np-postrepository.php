<?php

class NP_PostRepository {

	/**
	* Update Order
	*/
	public function updateOrder($posts, $parent = 0)
	{
		$this->validateIDs($posts);
		foreach( $posts as $key => $post )
		{
			wp_update_post(array(
				'ID' => $post['id'],
				'menu_order' => $key,
				'post_parent' => $parent
			));

			if ( count($post['children']) > 0 ){
				$this->updateOrder($post['children'], $post['id']);
			}
		}
		return true;
	}


	/**
	* Validate Post IDs before saving
	*/
	private function validateIDs($posts)
	{
		foreach ($posts as $post)
		{
			if ( !is_numeric($post['id']) ){
				return wp_send_json(array('status'=>'error', 'message'=>'Incorrect Form Field'));
			}
		}
	}


	/**
	* Update Post
	*/
	public function updatePost($data)
	{
		$date = $this->validateDate($data);
		$cs = ( isset($data['comment_status']) ) ? 'open' : 'closed';
		$updated_post = array(
			'ID' => sanitize_text_field($data['post_id']),
			'post_author' => sanitize_text_field($data['post_author']),
			'post_name' => sanitize_text_field($data['post_name']),
			'post_date' => $date,
			'comment_status' => $cs,
			'post_status' => sanitize_text_field($data['_status'])
		);
		wp_update_post($updated_post);
		$this->updateTemplate($data);
		return $updated_post;
	}


	/**
	* Update Page Template
	*/
	public function updateTemplate($data)
	{
		update_post_meta( 
			sanitize_text_field($data['post_id']), 
			'_wp_page_template', 
			sanitize_text_field($data['page_template'])
		);
	}



	/**
	* Validate Date Input
	*/
	private function validateDate($data)
	{
		// First validate that it is an actual date
		if ( !checkdate( $data['mm'], $data['jj'], $data['aa'] ) ){
			return wp_send_json(array('status' => 'error', 'message' => 'Please provide a valid date.'));
			die();
		}

		// Validate all the fields are there
		if ( ($data['aa'] !== "") 
			&& ( $data['mm'] !== "" )
			&& ( $data['jj'] !== "" )
			&& ( $data['hh'] !== "" )
			&& ( $data['mm'] !== "" )
			&& ( $data['ss'] !== "" ) )
		{
			$date = $data['aa'] . '-' . $data['mm'] . '-' . $data['jj'] . ' ' . $data['hh'] . ':' . $data['mm'] . ':' . $date['ss'];
			return $date;
		} else {
			return wp_send_json(array('status' => 'error', 'message' => 'Please provide a valid date.'));
			die();
		}
	}


}